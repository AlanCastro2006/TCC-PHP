<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVisibleToCardsTable extends Migration
{
    /**
     * Run the migrations.
     * Este método aplica a migração, adicionando a coluna 'visible' à tabela 'cards'.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cards', function (Blueprint $table) {
            // Adiciona a coluna 'visible' do tipo booleano com valor padrão 'true'
            $table->boolean('visible')->default(true); // Permite valores booleanos e define 'true' como valor padrão
        });
    }

    /**
     * Reverse the migrations.
     * Este método reverte a migração, removendo a coluna 'visible' da tabela 'cards'.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cards', function (Blueprint $table) {
            // Remove a coluna 'visible' da tabela 'cards'
            $table->dropColumn('visible'); // Remove a coluna 'visible'
        });
    }
}
