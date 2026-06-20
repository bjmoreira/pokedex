<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * EN: Read-only endpoints backed by the LOCAL database (imported from PokeAPI
 *     once via `php artisan pokemon:sync`). No upstream calls at request time.
 * PT: Endpoints somente-leitura servidos pelo banco LOCAL (importado da PokeAPI
 *     uma vez via `php artisan pokemon:sync`). Sem chamadas externas por request.
 */
class PokemonController extends Controller
{
    /**
     * EN: Paginated Pokémon list (15 per page) with optional name search.
     * PT: Lista paginada de Pokémon (15 por página) com busca opcional por nome.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Pokemon::query()->orderBy('id');

        if ($search = trim((string) $request->query('search', ''))) {
            $query->where('name', 'like', '%'.$search.'%');
        }

        $paginator = $query->paginate(perPage: 15)->withQueryString();

        return response()->json([
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
            // EN: Tells the frontend the one-time import is still running.
            // PT: Informa ao frontend que a importação inicial ainda está rodando.
            'importing' => Pokemon::query()->count() === 0,
        ]);
    }

    /**
     * EN: Full details for a single Pokémon by id or name.
     * PT: Detalhes completos de um único Pokémon por id ou nome.
     */
    public function show(string $idOrName): JsonResponse
    {
        $pokemon = Pokemon::query()
            ->where('id', $idOrName)
            ->orWhere('name', $idOrName)
            ->firstOrFail();

        return response()->json(['data' => $pokemon]);
    }

    /**
     * EN: Stored evolution chain for a Pokémon.
     * PT: Cadeia de evolução armazenada de um Pokémon.
     */
    public function evolution(string $idOrName): JsonResponse
    {
        $pokemon = Pokemon::query()
            ->where('id', $idOrName)
            ->orWhere('name', $idOrName)
            ->firstOrFail();

        return response()->json(['data' => $pokemon->evolution ?? []]);
    }
}
