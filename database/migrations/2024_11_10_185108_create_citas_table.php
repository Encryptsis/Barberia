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
            $table->boolean('cta_activa')->default(true);

            // Nuevas columnas para puntualidad y penalizaciones
            $table->boolean('cta_arrival_confirmed')->default(false);
            $table->dateTime('cta_arrival_time')->nullable();
            $table->string('cta_punctuality_status', 20)->nullable(); // 'on_time', 'late'
            $table->boolean('cta_penalty_applied')->default(false);
            $table->decimal('cta_penalty_amount', 10, 2)->nullable();
            $table->boolean('cta_is_free')->default(false);
            
            // **Definición manual de timestamps personalizados**
            $table->timestamp('cta_created_at')->useCurrent();
            $table->timestamp('cta_updated_at')->useCurrent()->useCurrentOnUpdate();
  

            // Claves Foráneas
            $table->foreign('cta_cliente_id')->references('usr_id')->on('usuarios');
            $table->foreign('cta_profesional_id')->references('usr_id')->on('usuarios');
            $table->foreign('cta_estado_id')->references('estado_id')->on('estados_citas');
            
            // Índices
            $table->index('cta_estado_id', 'idx_cta_estado_id');
            $table->index('cta_punctuality_status', 'idx_cta_punctuality_status');
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
            $table->dropIndex('idx_cta_punctuality_status');
        });
        Schema::dropIfExists('citas');
    }
}
