<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientesRecetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingrediente_receta', function (Blueprint $table) {
            $table->integer('receta_id')->unsigned();            
            $table->foreign('receta_id')->references('id')->on('recetas');
            $table->integer('ingrediente_id')->unsigned();            
            $table->foreign('ingrediente_id')->references('id')->on('ingredientes');
            $table->integer('cantidad');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingredientes_recetas');
    }
}
