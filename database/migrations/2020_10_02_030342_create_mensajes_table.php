<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMensajesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mensajes', function (Blueprint $table) {
            $table->id('idMensaje');
            $table->char('idPersona',10);
            $table->char('receptor',10);
            $table->string('mensaje');
            
            $table->timestamps();

            $table->foreign('idPersona')->references('idPersona')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('receptor')->references('idPersona')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->primary('idMensaje');                       
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mensajes');
    }
}
