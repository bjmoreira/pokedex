<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapturedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('captured', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_user')->unsigned();
            $table->integer('id_pokemon')->unsigned();
            $table->string('nickname',50)->nullable();
            $table->string('level')->nullable();
            $table->string('detail_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('captured');
    }
}
