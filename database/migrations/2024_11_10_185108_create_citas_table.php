<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitasTable extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->increments('cta_id');
            $table->unsignedInteger('cta_cliente_id');
            $table->unsignedInteger('cta_profesional_id')->nullable();
            $table->date('cta_fecha');
            $table->time('cta_hora');
            $table->unsignedInteger('cta_estado_id')->default(1);
            $table->timestamp('cta_created_at')->useCurrent();
            $table->timestamp('cta_updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('cta_cliente_id')->references('usr_id')->on('usuarios');
            $table->foreign('cta_profesional_id')->references('usr_id')->on('usuarios');
            $table->foreign('cta_estado_id')->references('estado_id')->on('estados_citas');
            $table->index('cta_estado_id', 'idx_cta_estado_id');
        });
    }

    /**
     * Revertir las migraciones.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropForeign(['cta_cliente_id']);
            $table->dropForeign(['cta_profesional_id']);
            $table->dropForeign(['cta_estado_id']);
            $table->dropIndex('idx_cta_estado_id');
        });
        Schema::dropIfExists('citas');
    }
}
