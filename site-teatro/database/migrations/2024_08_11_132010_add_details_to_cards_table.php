<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('cards', function (Blueprint $table) {
        $table->string('classification')->nullable(); // Classificação indicativa
        $table->text('description')->nullable();      // Descrição da apresentação
        $table->string('duration')->nullable();       // Duração da apresentação
        $table->string('season')->nullable();         // Temporada (substitui a data)
        $table->string('days')->nullable();           // Dias da semana
    });
}

public function down()
{
    Schema::table('cards', function (Blueprint $table) {
        $table->dropColumn('classification');
        $table->dropColumn('description');
        $table->dropColumn('duration');
        $table->dropColumn('season');
        $table->dropColumn('days');
    });
}

};
