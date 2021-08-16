<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateIngredientesTable.
 */
class CreateIngredientesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ingredientes', function(Blueprint $table) {
            $table->increments('id');
			$table->string('nombre');
			$table->integer('calorias');
			$table->float('carbohidratos');
			$table->float('grasas');
			$table->float('proteinas');
			$table->enum('categoria', [
				'Cereales',
				'Carnes',
				'Pescados',
				'Huevos',
				'Lácteos',
				'Leguminosas',
				'Tubérculos y Raíces',
				'Legumbres',
				'Frutas',
				'Bebidas Alcohólicas',
				'Nueces y Afines',
				'Alimentos preparados',
				'Alimentos Varios'
			]);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ingredientes');
	}
}
