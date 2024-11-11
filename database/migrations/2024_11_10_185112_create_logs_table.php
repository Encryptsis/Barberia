<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->increments('log_id');
            $table->unsignedInteger('log_usuario_id')->nullable();
            $table->string('log_accion', 255);
            $table->unsignedInteger('log_tipo_accion_id');
            $table->text('log_descripcion')->nullable();
            $table->timestamp('log_fecha')->useCurrent();
            $table->foreign('log_usuario_id')->references('usr_id')->on('usuarios');
            $table->foreign('log_tipo_accion_id')->references('tipo_accion_id')->on('tipos_accion');
            $table->index('log_fecha', 'idx_log_fecha');
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
        Schema::table('logs', function (Blueprint $table) {
            $table->dropForeign(['log_usuario_id']);
            $table->dropForeign(['log_tipo_accion_id']);
            $table->dropIndex('idx_log_fecha');
        });
        Schema::dropIfExists('logs');
    }
}
