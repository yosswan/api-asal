<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateComidasTable.
 */
class CreateComidasTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comidas', function(Blueprint $table) {
			$table->increments('id');
			$table->enum('tipo', ['desayuno', 'almuerzo', 'cena', 'merienda']);
			$table->date('fecha');
			$table->unique(['fecha', 'tipo']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('comidas');
	}
}
