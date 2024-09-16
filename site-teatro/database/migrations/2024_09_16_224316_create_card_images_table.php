<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('card_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained()->onDelete('cascade'); // Relaciona com o card
            $table->string('image_path'); // Caminho da imagem
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('card_images');
    }
};
