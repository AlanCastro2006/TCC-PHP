<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Este método é responsável por criar a tabela e seus campos no banco de dados.
     * Migration é usada para criar e excluir tabelas (DDL).
     * Model é usado para manipular os dados nas tabelas (DML).
     * Certifique-se de que o MySQL está rodando (não é necessário ligar o Apache no XAMPP para migrações).
     *
     * @return void
     */
    public function up()
    {
        // Cria a tabela 'cards' com os seguintes campos
        Schema::create('cards', function (Blueprint $table) {
            $table->id(); // Coluna 'id' do tipo big integer auto-incrementável
            $table->string('name'); // Coluna 'name' do tipo string
            $table->string('date'); // Coluna 'date' do tipo string
            $table->string('local'); // Coluna 'local' do tipo string
            $table->timestamps(); // Colunas 'created_at' e 'updated_at' para rastreamento de criação e atualização
        });
    }

    /**
     * Reverse the migrations.
     * Este método é responsável por reverter a migração, excluindo a tabela criada.
     *
     * @return void
     */
    public function down()
    {
        // Exclui a tabela 'cards' se ela existir
        Schema::dropIfExists('cards');
    }
};
