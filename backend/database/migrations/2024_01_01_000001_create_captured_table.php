<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * EN: Pokémon captured by users, with trainer metadata.
     * PT: Pokémon capturados pelos usuários, com metadados de treinador.
     */
    public function up(): void
    {
        Schema::create('captured', function (Blueprint $table): void {
            $table->id();
            // EN: Owner; cascades on user deletion. / PT: Dono; remove em cascata ao excluir o usuário.
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // EN: PokeAPI Pokémon id. / PT: Id do Pokémon na PokeAPI.
            $table->unsignedInteger('pokemon_id');
            $table->string('nickname', 50)->nullable();
            $table->string('level', 50)->nullable();
            $table->string('detail_note', 255)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'pokemon_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('captured');
    }
};
