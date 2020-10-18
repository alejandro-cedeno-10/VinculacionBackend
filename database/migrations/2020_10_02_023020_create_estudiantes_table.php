<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstudiantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->char('idEstudiante',10);
            $table->char('idRepresentante',10);
            $table->string('procedencia');
           
            $table->timestamps();
           
            $table->foreign('idEstudiante')->references('idPersona')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('idRepresentante')->references('idPersona')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->primary('idEstudiante');           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estudiantes');
    }
}
