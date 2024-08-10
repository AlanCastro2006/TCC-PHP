<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Método responsável por aplicar a migração, adicionando uma nova coluna 'img' na tabela 'cards'
     * 
     * @return void
     */
    public function up()
    {
        // Adiciona a coluna 'img' na tabela 'cards'
        Schema::table('cards', function (Blueprint $table) {
            $table->string('img')->default('default_image.jpg'); // Define um valor padrão Permite valores nulos na coluna 'img' do tipo string
        });
    }

    /**
     * Reverse the migrations.
     * Método responsável por reverter a migração, removendo a coluna 'img' da tabela 'cards'
     * 
     * @return void
     */
    public function down()
    {
        // Remove a coluna 'img' da tabela 'cards'
        Schema::table('cards', function (Blueprint $table) {
            $table->dropColumn('img'); // Remove a coluna 'img'
        });
    }
};
