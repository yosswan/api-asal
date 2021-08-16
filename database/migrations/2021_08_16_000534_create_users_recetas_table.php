<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersRecetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receta_user', function (Blueprint $table) {
            $table->date('fecha');
            $table->enum('tipo', ['desayuno', 'almuerzo', 'cena', 'merienda']);
            $table->integer('receta_id')->unsigned();            
            $table->foreign('receta_id')->references('id')->on('recetas')->cascadeOnDelete();
            $table->integer('user_id')->unsigned();            
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unique(['user_id', 'receta_id', 'fecha', 'tipo']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receta_user');
    }
}
