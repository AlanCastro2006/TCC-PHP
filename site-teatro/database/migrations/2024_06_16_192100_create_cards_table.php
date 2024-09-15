<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->string('img')->default('default_image.jpg');
            $table->string('img1')->default('default_image1.jpg');
            $table->string('img2')->default('default_image2.jpg');
            $table->string('img3')->default('default_image3.jpg');
            $table->string('img4')->default('default_image4.jpg');
            $table->string('img5')->default('default_image5.jpg');
            $table->boolean('visible')->default(true);
            $table->string('ticket_link')->nullable();
            $table->string('classification')->nullable();
            $table->text('description')->nullable();
            $table->string('duration')->nullable();
            $table->string('season')->nullable();
            $table->string('days')->nullable();
            $table->date('season_start')->nullable();
            $table->date('season_end')->nullable();
            
            // Novos campos obrigatórios
            $table->string('texto'); // Autoria do texto ou adaptação
            $table->string('elenco');
            $table->string('direcao');
            $table->string('figurino');
            $table->string('cenografia');
            $table->string('iluminacao');
            $table->string('sonorizacao');
            $table->string('producao');

            // Campos opcionais
            $table->string('costureira')->nullable();
            $table->string('assistente_cenografia')->nullable();
            $table->string('cenotecnico')->nullable();
            $table->string('consultoria_design')->nullable();
            $table->string('co_producao')->nullable();
            $table->text('agradecimentos')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cards');
    }
};
