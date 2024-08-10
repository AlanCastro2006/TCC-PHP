<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Migration= Serve somentepara Criar e deletar a tabela no bd (DDL)
     * Model= Manipula os dados na tabela (DML)
     * Precisa Ligar o MySql no xampp (NÃO LIGAR O APACHE)
     *@return void
     */ 
    public function up()
    {
        //Cria a tabela 'cards'
        Schema::create('cards', function (Blueprint $table) {
            $table->id(); // Coluna 'id' do tipo big integer auto-incrementável
            $table->string('name'); // Coluna 'name' do tipo string
            $table->string('date'); // Coluna 'date' do tipo string
            $table->string('local'); // Coluna 'local' do tipo string
            $table->timestamps(); // Colunas 'created_at' e 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     *Método para reverter as migrações, ou seja, excluir a tabela criada
     * @return void
     */
    public function down()
    {
        // Exclui a tabela 'cards' caso exista
        Schema::dropIfExists('cards');
    }
};


/*Dois métodos/funções up,(criar) e down(excluir), os dois são programáveis para dizer como devem ser feitas as exclusoes */