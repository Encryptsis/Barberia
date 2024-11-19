<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePunPenaltiesTable extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pun_penalties', function (Blueprint $table) {
            $table->increments('pun_id');
            $table->unsignedInteger('pun_cta_id');
            $table->unsignedInteger('pun_usr_id');
            $table->decimal('pun_amount', 10, 2);
            $table->timestamp('pun_applied_at')->useCurrent();

            // Claves Foráneas
            $table->foreign('pun_cta_id')->references('cta_id')->on('citas')->onDelete('cascade');
            $table->foreign('pun_usr_id')->references('usr_id')->on('usuarios')->onDelete('cascade');

            // Índices
            $table->index('pun_cta_id', 'idx_pun_cta_id');
            $table->index('pun_usr_id', 'idx_pun_usr_id');
        });
    }

    /**
     * Revertir las migraciones.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pun_penalties', function (Blueprint $table) {
            $table->dropForeign(['pun_cta_id']);
            $table->dropForeign(['pun_usr_id']);
            $table->dropIndex('idx_pun_cta_id');
            $table->dropIndex('idx_pun_usr_id');
        });
        Schema::dropIfExists('pun_penalties');
    }
}
