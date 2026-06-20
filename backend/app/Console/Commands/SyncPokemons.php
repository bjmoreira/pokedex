<?php

namespace App\Console\Commands;

use App\Models\Pokemon;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;

/**
 * EN: Imports ALL Pokémon from PokeAPI into the local database. To be gentle on
 *     the public API the import runs in batches (default 150) with a delay
 *     between batches. Data is reused from the DB and only re-fetched when it
 *     gets stale (default every 5 days). Memory stays low because each batch is
 *     processed and released before the next one.
 * PT: Importa TODOS os Pokémon da PokeAPI para o banco local. Para não
 *     sobrecarregar a API pública, a importação roda em lotes (padrão 150) com
 *     um intervalo entre eles. Os dados são reusados do banco e só re-buscados
 *     quando ficam velhos (padrão a cada 5 dias). O consumo de memória é baixo
 *     porque cada lote é processado e liberado antes do próximo.
 */
class SyncPokemons extends Command
{
    protected $signature = 'pokemon:sync
        {--limit=0 : How many Pokémon to import (0 = all available)}
        {--chunk=150 : Batch size per request wave}
        {--delay=3 : Seconds to wait between batches}
        {--stale-days=5 : Re-import only if local data is older than this}
        {--force : Re-import even if data is present and fresh}';

    protected $description = 'Import all Pokémon from PokeAPI into the local database (batched, cached, refreshed every few days).';

    // EN: Concurrent requests per wave inside a batch. / PT: Requisições concorrentes por onda dentro do lote.
    private const CONCURRENCY = 25;

    /**
     * EN: Maps a PokeAPI generation to its region. / PT: Mapeia a geração da PokeAPI para sua região.
     *
     * @var array<string, string>
     */
    private array $regions = [
        'generation-i' => 'Kanto',
        'generation-ii' => 'Johto',
        'generation-iii' => 'Hoenn',
        'generation-iv' => 'Sinnoh',
        'generation-v' => 'Unova',
        'generation-vi' => 'Kalos',
        'generation-vii' => 'Alola',
        'generation-viii' => 'Galar',
        'generation-ix' => 'Paldea',
    ];

    public function handle(): int
    {
        // EN: The full import keeps several batches of JSON around; give it room.
        // PT: A importação completa mantém alguns lotes de JSON; dá folga de memória.
        ini_set('memory_limit', '512M');

        $base = rtrim((string) config('services.pokeapi.base_url'), '/');
        $staleDays = (int) $this->option('stale-days');

        // EN: Total available = number of species (contiguous ids 1..N).
        // PT: Total disponível = número de espécies (ids contíguos 1..N).
        $limit = (int) $this->option('limit');
        if ($limit <= 0) {
            $limit = (int) (Http::acceptJson()->get("{$base}/pokemon-species", ['limit' => 1])->json('count') ?? 1025);
        }

        // EN: Skip when we already have everything and it is still fresh.
        // PT: Pula quando já temos tudo e ainda está atualizado.
        if (! $this->option('force')) {
            $have = Pokemon::query()->count();
            $newest = Pokemon::query()->max('updated_at');
            $fresh = $newest !== null && Carbon::parse($newest)->gt(now()->subDays($staleDays));

            if ($have >= $limit && $fresh) {
                $this->info("Pokémon up to date ({$have}). Skipping / atualizados, pulando.");

                return self::SUCCESS;
            }
        }

        $chunkSize = max(1, (int) $this->option('chunk'));
        $delay = max(0, (int) $this->option('delay'));
        $batches = array_chunk(range(1, $limit), $chunkSize);
        $total = count($batches);

        $this->info("Importing {$limit} Pokémon in {$total} batch(es) of {$chunkSize} / em {$total} lote(s)...");

        foreach ($batches as $index => $ids) {
            $imported = $this->syncBatch($base, $ids);
            $this->info('Batch / Lote '.($index + 1)."/{$total}: +{$imported}");

            // EN: Pause between batches to avoid hammering the API.
            // PT: Pausa entre lotes para não martelar a API.
            if ($delay > 0 && $index < $total - 1) {
                sleep($delay);
            }
        }

        $this->info('Pokémon import finished / importação concluída.');

        return self::SUCCESS;
    }

    /**
     * EN: Import a single batch of ids; returns how many were stored.
     * PT: Importa um único lote de ids; retorna quantos foram salvos.
     *
     * @param  array<int, int>  $ids
     */
    private function syncBatch(string $base, array $ids): int
    {
        $details = $this->poolResource($base, 'pokemon', $ids);
        $species = $this->poolResource($base, 'pokemon-species', $ids);

        // EN: Fetch each unique evolution chain once for this batch.
        // PT: Busca cada cadeia de evolução única deste lote uma vez.
        $chainUrls = [];
        foreach ($species as $s) {
            if (isset($s['evolution_chain']['url'])) {
                $chainUrls[$s['evolution_chain']['url']] = true;
            }
        }
        $chains = $this->poolUrls(array_keys($chainUrls));

        // EN: species-name -> ordered evolution entries (resolved from chain urls,
        //     so they work even across batches without extra lookups).
        // PT: nome -> itens da evolução em ordem (resolvidos das urls da cadeia,
        //     então funcionam mesmo entre lotes, sem buscas extras).
        $chainByName = [];
        foreach ($chains as $chain) {
            if ($chain === null) {
                continue;
            }
            $entries = [];
            $this->flattenChain($chain['chain'] ?? [], $entries);
            foreach ($entries as $e) {
                $chainByName[$e['name']] = $entries;
            }
        }

        $imported = 0;
        foreach ($ids as $id) {
            $d = $details[$id] ?? null;
            $s = $species[$id] ?? null;
            if ($d === null) {
                continue;
            }

            $generation = $s['generation']['name'] ?? null;

            Pokemon::query()->updateOrCreate(['id' => $id], [
                'name' => $d['name'],
                'types' => array_map(fn (array $t): string => $t['type']['name'], $d['types']),
                'image' => $this->artwork($id),
                'height' => $d['height'] ?? 0,
                'weight' => $d['weight'] ?? 0,
                'base_experience' => $d['base_experience'] ?? null,
                'stats' => collect($d['stats'] ?? [])
                    ->mapWithKeys(fn (array $st): array => [$st['stat']['name'] => $st['base_stat']])
                    ->all(),
                'abilities' => array_values(array_map(
                    fn (array $a): string => $a['ability']['name'],
                    $d['abilities'] ?? []
                )),
                'generation' => $generation,
                'region' => $generation !== null ? ($this->regions[$generation] ?? null) : null,
                'habitat' => $s['habitat']['name'] ?? null,
                'color' => $s['color']['name'] ?? null,
                'description' => $this->description($s),
                'evolution' => $chainByName[$d['name']] ?? [],
            ]);

            $imported++;
        }

        // EN: Free batch data before the next one. / PT: Libera os dados do lote antes do próximo.
        unset($details, $species, $chains, $chainByName);

        return $imported;
    }

    /**
     * EN: GET many ids of a resource, in concurrent waves.
     * PT: GET de muitos ids de um recurso, em ondas concorrentes.
     *
     * @param  array<int, int>  $ids
     * @return array<int, array<string, mixed>|null>
     */
    private function poolResource(string $base, string $resource, array $ids): array
    {
        $out = [];

        foreach (array_chunk($ids, self::CONCURRENCY) as $wave) {
            $responses = Http::pool(fn (Pool $pool): array => array_map(
                fn (int $id) => $pool->as((string) $id)->acceptJson()->timeout(20)->get("{$base}/{$resource}/{$id}"),
                $wave
            ));

            foreach ($wave as $id) {
                $r = $responses[(string) $id] ?? null;
                $out[$id] = ($r && $r->successful()) ? $r->json() : null;
            }
        }

        return $out;
    }

    /**
     * EN: GET many absolute URLs, in concurrent waves.
     * PT: GET de muitas URLs absolutas, em ondas concorrentes.
     *
     * @param  array<int, string>  $urls
     * @return array<string, array<string, mixed>|null>
     */
    private function poolUrls(array $urls): array
    {
        $out = [];

        foreach (array_chunk($urls, self::CONCURRENCY) as $wave) {
            $responses = Http::pool(fn (Pool $pool): array => array_map(
                fn (string $url) => $pool->as($url)->acceptJson()->timeout(20)->get($url),
                $wave
            ));

            foreach ($wave as $url) {
                $r = $responses[$url] ?? null;
                $out[$url] = ($r && $r->successful()) ? $r->json() : null;
            }
        }

        return $out;
    }

    /**
     * EN: Official artwork URL derived from the id (no extra request needed).
     * PT: URL da arte oficial derivada do id (sem requisição extra).
     */
    private function artwork(int $id): string
    {
        return "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/{$id}.png";
    }

    /**
     * EN: First English flavor text, whitespace-normalized.
     * PT: Primeiro texto descritivo em inglês, com espaços normalizados.
     *
     * @param  array<string, mixed>|null  $species
     */
    private function description(?array $species): ?string
    {
        foreach ($species['flavor_text_entries'] ?? [] as $entry) {
            if (($entry['language']['name'] ?? '') === 'en') {
                return trim((string) preg_replace('/\s+/', ' ', $entry['flavor_text']));
            }
        }

        return null;
    }

    /**
     * EN: Recursively collect {id, name, image} from an evolution chain node.
     *     The id is parsed from the species URL so no extra fetch is required.
     * PT: Coleta recursivamente {id, name, image} de um nó da cadeia de evolução.
     *     O id é extraído da URL da espécie, então nenhuma busca extra é necessária.
     *
     * @param  array<string, mixed>  $node
     * @param  array<int, array<string, mixed>>  $entries
     */
    private function flattenChain(array $node, array &$entries): void
    {
        if (isset($node['species']['name'], $node['species']['url'])) {
            $id = $this->idFromUrl($node['species']['url']);
            if ($id !== null) {
                $entries[] = [
                    'id' => $id,
                    'name' => $node['species']['name'],
                    'image' => $this->artwork($id),
                ];
            }
        }

        foreach ($node['evolves_to'] ?? [] as $next) {
            $this->flattenChain($next, $entries);
        }
    }

    /**
     * EN: Extract the trailing numeric id from a PokeAPI URL.
     * PT: Extrai o id numérico final de uma URL da PokeAPI.
     */
    private function idFromUrl(string $url): ?int
    {
        return preg_match('#/(\d+)/?$#', $url, $m) ? (int) $m[1] : null;
    }
}
