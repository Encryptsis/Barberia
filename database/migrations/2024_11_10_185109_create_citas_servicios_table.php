<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitasServiciosTable extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citas_servicios', function (Blueprint $table) {
            $table->unsignedInteger('cta_srv_cita_id');
            $table->unsignedInteger('cta_srv_servicio_id');
            $table->foreign('cta_srv_cita_id')->references('cta_id')->on('citas')->onDelete('cascade');
            $table->foreign('cta_srv_servicio_id')->references('srv_id')->on('servicios')->onDelete('cascade');
            $table->primary(['cta_srv_cita_id', 'cta_srv_servicio_id']);
        });
    }

    /**
     * Revertir las migraciones.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('citas_servicios', function (Blueprint $table) {
            $table->dropForeign(['cta_srv_cita_id']);
            $table->dropForeign(['cta_srv_servicio_id']);
            $table->dropPrimary(['cta_srv_cita_id', 'cta_srv_servicio_id']);
        });
        Schema::dropIfExists('citas_servicios');
    }
}
