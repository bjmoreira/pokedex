<?php

namespace Database\Seeders;

use App\Models\Captured;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * EN: Seed the database with a default admin user (and sample data).
     * PT: Popula o banco com um usuário admin padrão (e dados de exemplo).
     */
    public function run(): void
    {
        // EN: Default test account requested for the demo environment.
        // PT: Conta de teste padrão solicitada para o ambiente de demonstração.
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
            ],
        );

        // EN: Give the admin a couple of starter Pokémon (Bulbasaur, Charmander, Squirtle).
        // PT: Dá ao admin alguns Pokémon iniciais (Bulbasaur, Charmander, Squirtle).
        $starters = [
            ['pokemon_id' => 1, 'nickname' => 'Bulby', 'level' => '5', 'detail_note' => 'Starter / Inicial'],
            ['pokemon_id' => 4, 'nickname' => 'Charmy', 'level' => '5', 'detail_note' => 'Starter / Inicial'],
            ['pokemon_id' => 7, 'nickname' => 'Squirt', 'level' => '5', 'detail_note' => 'Starter / Inicial'],
        ];

        foreach ($starters as $starter) {
            Captured::query()->updateOrCreate(
                ['user_id' => $admin->id, 'pokemon_id' => $starter['pokemon_id']],
                $starter + ['user_id' => $admin->id],
            );
        }
    }
}
