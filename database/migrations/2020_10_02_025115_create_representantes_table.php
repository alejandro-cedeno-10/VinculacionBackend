<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepresentantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('representantes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->char('idRepresentante',10);
            $table->string('ocupacion');
            $table->string('direccionTrabajo');

            $table->timestamps();

            $table->foreign('idRepresentante')->references('idPersona')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->primary('idRepresentante');                       
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('representantes');
    }
}
