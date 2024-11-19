<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('usr_id');
            $table->string('usr_username', 50)->unique();
            $table->string('usr_password', 255);
            $table->string('usr_nombre_completo', 100);
            $table->string('usr_correo_electronico', 100)->unique();
            $table->string('usr_telefono', 20)->nullable();
            $table->string('usr_foto_perfil', 255)->nullable();
            $table->boolean('usr_activo')->default(true);
            $table->unsignedInteger('usr_rol_id')->nullable();
            
            // **Nuevo campo para Puntos de Fidelidad**
            $table->unsignedInteger('usr_points')->default(0);
            
            // **Campos de Timestamps Personalizados**
            $table->timestamp('usr_created_at')->useCurrent();
            $table->timestamp('usr_updated_at')->useCurrent()->useCurrentOnUpdate();
            
            $table->string('usr_recuperacion_token', 255)->nullable();
            $table->dateTime('usr_recuperacion_expira')->nullable();
            $table->dateTime('usr_ultimo_acceso')->nullable();
            
            // Claves Foráneas
            $table->foreign('usr_rol_id')->references('rol_id')->on('roles')->onDelete('set null');
            
            // Índices
            $table->index('usr_rol_id', 'idx_usr_rol_id');
        });
    }

    /**
     * Revertir las migraciones.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropForeign(['usr_rol_id']);
            $table->dropIndex('idx_usr_rol_id');
        });
        Schema::dropIfExists('usuarios');
    }
}
