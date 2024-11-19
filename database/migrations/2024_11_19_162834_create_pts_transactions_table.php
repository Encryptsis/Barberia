<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePtsTransactionsTable extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pts_transactions', function (Blueprint $table) {
            $table->increments('pts_id');
            $table->unsignedInteger('pts_usr_id');
            $table->string('pts_type', 20); // 'earn', 'redeem', 'reset'
            $table->integer('pts_amount');
            $table->text('pts_description')->nullable();
            $table->timestamp('pts_created_at')->useCurrent();

            // Clave Foránea
            $table->foreign('pts_usr_id')->references('usr_id')->on('usuarios')->onDelete('cascade');

            // Índices
            $table->index('pts_usr_id', 'idx_pts_usr_id');
            $table->index('pts_type', 'idx_pts_type');
        });
    }

    /**
     * Revertir las migraciones.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pts_transactions', function (Blueprint $table) {
            $table->dropForeign(['pts_usr_id']);
            $table->dropIndex('idx_pts_usr_id');
            $table->dropIndex('idx_pts_type');
        });
        Schema::dropIfExists('pts_transactions');
    }
}
