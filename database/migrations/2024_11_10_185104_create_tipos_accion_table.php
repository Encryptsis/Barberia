<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiposAccionTable extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipos_accion', function (Blueprint $table) {
            $table->increments('tipo_accion_id');
            $table->string('tipo_accion_nombre', 50)->unique();
            $table->string('tipo_accion_descripcion', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Revertir las migraciones.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipos_accion');
    }
}
