<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosTable extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->increments('pago_transaccion_id');
            $table->unsignedInteger('pago_cita_id');
            $table->unsignedInteger('pago_usuario_id');
            $table->unsignedInteger('pago_metodo_id');
            $table->decimal('pago_monto', 10, 2);
            $table->timestamp('pago_fecha')->useCurrent();
            $table->unsignedInteger('pago_estado_pago_id')->default(1);
            $table->foreign('pago_cita_id')->references('cta_id')->on('citas')->onDelete('cascade');
            $table->foreign('pago_usuario_id')->references('usr_id')->on('usuarios');
            $table->foreign('pago_metodo_id')->references('pago_id')->on('metodos_pago');
            $table->foreign('pago_estado_pago_id')->references('estado_pago_id')->on('estados_pagos');
            $table->index('pago_estado_pago_id', 'idx_estado_pago_id');
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
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign(['pago_cita_id']);
            $table->dropForeign(['pago_usuario_id']);
            $table->dropForeign(['pago_metodo_id']);
            $table->dropForeign(['pago_estado_pago_id']);
            $table->dropIndex('idx_estado_pago_id');
        });
        Schema::dropIfExists('pagos');
    }
}
