<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('rol_id');
            $table->string('rol_nombre', 50)->unique();
            $table->text('rol_descripcion')->nullable();
            $table->integer('rol_nivel');
            $table->boolean('rol_activo')->default(true);
            $table->index('rol_nivel', 'idx_rol_nivel');
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
        Schema::dropIfExists('roles');
    }
}
