<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * EN: Local mirror of PokeAPI data, imported once and reused from the DB.
     * PT: Espelho local dos dados da PokeAPI, importados uma vez e reusados do banco.
     */
    public function up(): void
    {
        Schema::create('pokemons', function (Blueprint $table): void {
            // EN: PokeAPI id (not auto-increment). / PT: Id da PokeAPI (não auto-incremento).
            $table->unsignedInteger('id')->primary();
            $table->string('name')->index();
            $table->json('types');
            $table->string('image')->nullable();
            $table->unsignedInteger('height')->default(0);
            $table->unsignedInteger('weight')->default(0);
            $table->unsignedInteger('base_experience')->nullable();
            // EN: Extra details, all stored locally. / PT: Detalhes extras, todos locais.
            $table->json('stats')->nullable();
            $table->json('abilities')->nullable();
            $table->string('generation')->nullable();
            $table->string('region')->nullable();
            $table->string('habitat')->nullable();
            $table->string('color')->nullable();
            $table->text('description')->nullable();
            $table->json('evolution')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pokemons');
    }
};
