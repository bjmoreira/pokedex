<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// EN: Sample closure-based Artisan command. / PT: Comando Artisan de exemplo baseado em closure.
Artisan::command('inspire', function (): void {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// EN: Refresh the local Pokémon mirror. The command itself only re-fetches from
//     PokeAPI when the data is older than its --stale-days (default 5 days), so
//     running it daily effectively refreshes every few days without hammering.
// PT: Atualiza o espelho local de Pokémon. O próprio comando só re-busca da
//     PokeAPI quando os dados estão mais velhos que --stale-days (padrão 5 dias),
//     então rodá-lo diariamente atualiza a cada poucos dias sem sobrecarregar.
Schedule::command('pokemon:sync')->daily()->withoutOverlapping();
