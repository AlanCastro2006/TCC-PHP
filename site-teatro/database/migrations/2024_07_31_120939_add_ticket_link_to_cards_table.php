<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Este método aplica a migração, adicionando a coluna 'ticket_link' à tabela 'cards'.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cards', function (Blueprint $table) {
            // Adiciona a coluna 'ticket_link' do tipo string, que pode ser nula
            // A coluna será adicionada após a coluna 'img'
            $table->string('ticket_link')->nullable()->after('img');
        });
    }

    /**
     * Reverse the migrations.
     * Este método reverte a migração, removendo a coluna 'ticket_link' da tabela 'cards'.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cards', function (Blueprint $table) {
            // Remove a coluna 'ticket_link' da tabela 'cards'
            $table->dropColumn('ticket_link');
        });
    }
};
