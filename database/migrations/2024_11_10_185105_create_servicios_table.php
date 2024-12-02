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
            $table->unsignedInteger('srv_categoria_id'); 

            // Definir la clave foránea
            $table->foreign('srv_categoria_id')
            ->references('cat_id')
            ->on('categorias_servicios')
            ->onDelete('cascade');

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
        Schema::table('servicios', function (Blueprint $table) {
            // Eliminar la clave foránea antes de eliminar la columna
            $table->dropForeign(['srv_categoria_id']);
        });
        Schema::dropIfExists('servicios');
    }
}
