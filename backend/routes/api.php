<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CapturedController;
use App\Http\Controllers\PokemonController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes / Rotas da API
|--------------------------------------------------------------------------
| EN: All endpoints consumed by the Vue 3 frontend live here.
| PT: Todos os endpoints consumidos pelo frontend Vue 3 ficam aqui.
*/

// EN: Public authentication endpoint. / PT: Endpoint público de autenticação.
Route::post('/login', [AuthController::class, 'login']);

// EN: Protected routes require a valid Sanctum token.
// PT: Rotas protegidas exigem um token Sanctum válido.
Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // EN: Pokédex (data fetched and cached from PokeAPI).
    // PT: Pokédex (dados buscados e cacheados da PokeAPI).
    Route::get('/pokemon', [PokemonController::class, 'index']);
    Route::get('/pokemon/{idOrName}', [PokemonController::class, 'show']);
    Route::get('/pokemon/{idOrName}/evolution', [PokemonController::class, 'evolution']);

    // EN: Captured Pokémon owned by the authenticated user (full CRUD).
    // PT: Pokémon capturados do usuário autenticado (CRUD completo).
    Route::apiResource('captured', CapturedController::class)->except(['show']);
});
