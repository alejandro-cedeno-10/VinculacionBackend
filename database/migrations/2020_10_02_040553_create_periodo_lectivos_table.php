<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodoLectivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periodo_lectivos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('idPeriodoLectivo');
            $table->date('fechaInicio');
            $table->date('fechaFinal');
            $table->string('periodoLectivo');
            
            $table->timestamps();
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('periodo_lectivos');
    }
}
