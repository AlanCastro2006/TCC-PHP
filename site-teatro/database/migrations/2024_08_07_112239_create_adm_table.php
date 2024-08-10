<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmTable extends Migration
{
    /**
     * Run the migrations.
     * Este método aplica a migração, criando a tabela 'adm' no banco de dados.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adm', function (Blueprint $table) {
            $table->id(); // Coluna 'id' do tipo big integer auto-incrementável
            $table->string('username')->unique(); // Coluna 'username' do tipo string, com valor único
            $table->string('password'); // Coluna 'password' do tipo string
            $table->timestamps(); // Colunas 'created_at' e 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     * Este método reverte a migração, removendo a tabela 'adm' do banco de dados.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adm'); // Remove a tabela 'adm' se ela existir
    }
}
