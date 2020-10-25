<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubcategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subcategorias', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('idSubcategoria');
            $table->string('nombreSubcategoria');
            $table->string('sugerencia')->nullable();
                        
            $table->timestamps();

            $table->foreignId('idCategoria');
            $table->foreign('idCategoria')->references('idCategoria')->on('categorias')->onDelete('cascade')->onUpdate('cascade');            
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subcategorias');
    }
}
