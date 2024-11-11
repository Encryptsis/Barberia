<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiciosTable extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->increments('srv_id');
            $table->string('srv_nombre', 100)->unique();
            $table->text('srv_descripcion')->nullable();
            $table->decimal('srv_precio', 10, 2);
            $table->time('srv_duracion');
            $table->boolean('srv_disponible')->default(true);
            $table->string('srv_imagen', 255)->nullable();
            $table->index('srv_disponible', 'idx_srv_disponible');
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
        Schema::dropIfExists('servicios');
    }
}
