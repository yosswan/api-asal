<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateRecetasTable.
 */
class CreateRecetasTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('recetas', function(Blueprint $table) {
            $table->increments('id');
			$table->string('nombre');
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
		Schema::drop('recetas');
	}
}
