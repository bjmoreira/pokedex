<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

/*
|--------------------------------------------------------------------------
| Application bootstrap / Inicialização da aplicação
|--------------------------------------------------------------------------
| EN: Configures routing, middleware and exception handling (Laravel 12 style).
| PT: Configura rotas, middleware e tratamento de exceções (estilo Laravel 12).
*/

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // EN: Stateless API — auth is exclusively Sanctum bearer tokens, so we do
        //     NOT enable statefulApi() (that would add session + CSRF for requests
        //     coming from the SPA origin and break token-based login with a 419).
        // PT: API stateless — a autenticação é só por token Bearer do Sanctum, então
        //     NÃO habilitamos statefulApi() (isso adicionaria sessão + CSRF para
        //     requisições vindas da origem do SPA e quebraria o login com erro 419).
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
