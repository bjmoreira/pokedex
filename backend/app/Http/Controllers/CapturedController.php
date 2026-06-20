<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCapturedRequest;
use App\Http\Requests\UpdateCapturedRequest;
use App\Models\Captured;
use App\Models\Pokemon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**
 * EN: CRUD for the authenticated user's captured Pokémon. Every query is
 *     scoped to the current user so trainers never see each other's collection.
 * PT: CRUD dos Pokémon capturados do usuário autenticado. Toda consulta é
 *     restrita ao usuário atual, então treinadores nunca veem a coleção alheia.
 */
class CapturedController extends Controller
{
    /**
     * EN: List the user's captured Pokémon, enriched from the local pokemons table.
     * PT: Lista os capturados do usuário, enriquecidos com a tabela local pokemons.
     */
    public function index(Request $request): JsonResponse
    {
        $captured = $request->user()->captured()->latest()->get();

        // EN: One query to fetch all referenced Pokémon. / PT: Uma query para todos os Pokémon referenciados.
        $pokemons = Pokemon::query()
            ->whereIn('id', $captured->pluck('pokemon_id'))
            ->get()
            ->keyBy('id');

        $data = $captured->map(function (Captured $captured) use ($pokemons): array {
            $pokemon = $pokemons->get($captured->pokemon_id);

            // EN: Pokémon details first, then the capture's own fields win.
            // PT: Detalhes do Pokémon primeiro, depois os campos do capturado prevalecem.
            return array_merge(
                $pokemon ? Arr::except($pokemon->toArray(), ['id', 'created_at', 'updated_at']) : [],
                [
                    'id' => $captured->id,
                    'pokemon_id' => $captured->pokemon_id,
                    'nickname' => $captured->nickname,
                    'level' => $captured->level,
                    'detail_note' => $captured->detail_note,
                ],
            );
        });

        return response()->json(['data' => $data]);
    }

    /**
     * EN: Capture a new Pokémon for the current user.
     * PT: Captura um novo Pokémon para o usuário atual.
     */
    public function store(StoreCapturedRequest $request): JsonResponse
    {
        $captured = $request->user()->captured()->create($request->validated());

        return response()->json(['data' => $captured], 201);
    }

    /**
     * EN: Update trainer metadata (nickname, level, notes) of a captured Pokémon.
     * PT: Atualiza os metadados de treinador (apelido, nível, notas) de um capturado.
     */
    public function update(UpdateCapturedRequest $request, Captured $captured): JsonResponse
    {
        $this->authorizeOwnership($request, $captured);

        $captured->update($request->validated());

        return response()->json(['data' => $captured]);
    }

    /**
     * EN: Release (delete) a captured Pokémon.
     * PT: Solta (exclui) um Pokémon capturado.
     */
    public function destroy(Request $request, Captured $captured): JsonResponse
    {
        $this->authorizeOwnership($request, $captured);

        $captured->delete();

        return response()->json(null, 204);
    }

    /**
     * EN: Abort with 403 if the record does not belong to the current user.
     * PT: Aborta com 403 se o registro não pertencer ao usuário atual.
     */
    private function authorizeOwnership(Request $request, Captured $captured): void
    {
        abort_unless($captured->user_id === $request->user()->id, 403);
    }
}
