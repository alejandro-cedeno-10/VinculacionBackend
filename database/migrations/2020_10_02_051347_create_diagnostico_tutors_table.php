<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiagnosticoTutorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diagnostico_tutors', function (Blueprint $table) {
            $table->char('idProfesor',10);
            $table->string('descripcion');

            $table->timestamps();

            $table->foreign('idProfesor')->references('idProfesor')->on('profesors')->onDelete('cascade')->onUpdate('cascade');            
            
            $table->foreignId('idAnomalia');
            $table->foreign('idAnomalia')->references('idAnomalia')->on('anomalias')->onDelete('cascade')->onUpdate('cascade');            

            $table->primary(['idAnomalia', 'idProfesor']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diagnostico_tutors');
    }
}
