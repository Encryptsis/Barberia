<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentLimitsTable extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_limits', function (Blueprint $table) {
            $table->increments('limit_id');
            $table->unsignedInteger('cat_id')->nullable(); // NULL para el límite global
            $table->integer('limite_diario')->default(1);
            $table->timestamps();

            // Clave foránea
            $table->foreign('cat_id')
                  ->references('cat_id')
                  ->on('categorias_servicios')
                  ->onDelete('cascade');

            // Índice único para evitar duplicados
            $table->unique(['cat_id'], 'unique_cat_limit');
        });
    }

    /**
     * Revertir las migraciones.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointment_limits');
    }
}
