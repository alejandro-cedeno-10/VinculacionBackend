<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuerpoDecesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuerpo_deces', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->char('idPersona',10);
            $table->string('cargo');

            $table->timestamps();

            $table->foreign('idPersona')->references('idPersona')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->primary('idPersona');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cuerpo_deces');
    }
}
