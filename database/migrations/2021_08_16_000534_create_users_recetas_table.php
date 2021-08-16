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
        Schema::create('usercomida_receta', function (Blueprint $table) {
            $table->id();
            $table->integer('receta_id')->unsigned();            
            $table->foreign('receta_id')->references('id')->on('recetas')->cascadeOnDelete();
            $table->integer('usercomida_id')->unsigned();            
            $table->foreign('usercomida_id')->references('id')->on('user_comida')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usercomida_receta');
    }
}
