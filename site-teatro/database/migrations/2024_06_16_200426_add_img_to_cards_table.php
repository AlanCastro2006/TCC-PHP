<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Este método aplica a migração, adicionando a coluna 'img' à tabela 'cards'.
     * 
     * @return void
     */
    public function up()
    {
        // Adiciona a coluna 'img' à tabela 'cards'
        Schema::table('cards', function (Blueprint $table) {
            // Adiciona a coluna 'img' do tipo string com um valor padrão
            $table->string('img')->default('default_image.jpg'); // Permite valores nulos na coluna 'img'
        });
    }

    /**
     * Reverse the migrations.
     * Este método reverte a migração, removendo a coluna 'img' da tabela 'cards'.
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
