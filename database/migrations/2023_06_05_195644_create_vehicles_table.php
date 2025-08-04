<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    { 
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->year('year');
            $table->string('patent')->unique();
            $table->integer('mileage');
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->string('client_name');
            //puede que necesite relacionar con el cliente y de ser asi tengo que dar la opcion de poder editar de quien es el auto
            //un pantalla donde se edite la info del auto y en el campo dueño se selecciona de la lista de clientes, es como editarle el lider a una vendedora en arbell

            $table->date('register_date');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
