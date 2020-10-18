<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoAsignaturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_asignaturas', function (Blueprint $table) {
            $table->id('idTipoAsignatura');
            $table->string('nombreTipoAsignatura');
            
            $table->timestamps();

            $table->primary('idTipoAsignatura');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_asignaturas');
    }
}
