<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosServiciosTable extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios_servicios', function (Blueprint $table) {
            $table->unsignedInteger('usr_srv_usuario_id');
            $table->unsignedInteger('usr_srv_servicio_id');
            $table->text('usr_srv_notas')->nullable();
            $table->foreign('usr_srv_usuario_id')->references('usr_id')->on('usuarios')->onDelete('cascade');
            $table->foreign('usr_srv_servicio_id')->references('srv_id')->on('servicios')->onDelete('cascade');
            $table->primary(['usr_srv_usuario_id', 'usr_srv_servicio_id']);
        });
    }

    /**
     * Revertir las migraciones.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usuarios_servicios', function (Blueprint $table) {
            $table->dropForeign(['usr_srv_usuario_id']);
            $table->dropForeign(['usr_srv_servicio_id']);
            $table->dropPrimary(['usr_srv_usuario_id', 'usr_srv_servicio_id']);
        });
        Schema::dropIfExists('usuarios_servicios');
    }
}
