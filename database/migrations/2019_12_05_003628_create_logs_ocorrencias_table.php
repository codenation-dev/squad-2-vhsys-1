<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsOcorrenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs_ocorrencias', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('id_log')->unsigned();
            $table->foreign('id_log')
                ->references('id')
                ->on('logs')
                ->onDelete('cascade');

            $table->timestamp('data_inclusao')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs_ocorrencias');
    }
}
