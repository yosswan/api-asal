<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersComidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_comida', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('comida_id')->unsigned();            
            $table->foreign('comida_id')->references('id')->on('comidas')->cascadeOnDelete();
            $table->biginteger('user_id')->unsigned();            
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_comida');
    }
}
