<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstadosPagosTable extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estados_pagos', function (Blueprint $table) {
            $table->increments('estado_pago_id');
            $table->string('estado_pago_nombre', 50)->unique();
            $table->text('estado_pago_descripcion')->nullable();
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
        Schema::dropIfExists('estados_pagos');
    }
}
