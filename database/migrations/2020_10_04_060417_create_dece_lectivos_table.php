<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeceLectivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dece_lectivos', function (Blueprint $table) {
            $table->char('idPersona',10);

            $table->timestamps();

            $table->foreignId('idPeriodoLectivo');
            $table->foreign('idPeriodoLectivo')->references('idPeriodoLectivo')->on('periodo_lectivos')->onDelete('cascade')->onUpdate('cascade');
            
            $table->foreign('idPersona')->references('idPersona')->on('cuerpo_deces')->onDelete('cascade')->onUpdate('cascade');
            
            $table->primary(['idPeriodoLectivo','idPersona']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dece_lectivos');
    }
}
