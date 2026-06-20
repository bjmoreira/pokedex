<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes / Rotas Web
|--------------------------------------------------------------------------
| EN: This backend is an API only. The root just reports its status.
| PT: Este backend é apenas uma API. A raiz apenas informa seu status.
*/

Route::get('/', fn () => response()->json([
    'application' => 'Pokedex API',
    'status' => 'ok',
    'docs' => '/api',
]));
