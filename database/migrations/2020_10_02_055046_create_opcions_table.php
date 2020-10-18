<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpcionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opcions', function (Blueprint $table) {
            $table->id('idOpcion');
            $table->string('opcion');
            
            $table->timestamps();

            $table->foreignId('idPregunta');
            $table->foreign('idPregunta')->references('idPregunta')->on('preguntas')->onDelete('cascade')->onUpdate('cascade');
            
            $table->primary('idOpcion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('opcions');
    }
}
