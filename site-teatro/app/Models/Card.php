<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    /**
     * Define o nome da tabela associada ao modelo.
     * Se a tabela não seguir a convenção plural do Laravel (ou seja, 'cards' ao invés de 'card'),
     * defina o nome da tabela aqui.
     */
    protected $table = 'cards';

    /**
     * Define os campos que podem ser preenchidos em massa (mass assignable).
     * Isso ajuda a proteger contra ataques de injeção de massa, permitindo
     * apenas os campos especificados serem preenchidos via requests.
     */
    protected $fillable = ['name', 'date', 'local', 'img', 'visible', 'ticket_link'];

    /** 
     * Define o tipo do atributo 'visible' como booleano.
     * Isso é útil para garantir que o valor do campo seja tratado como um booleano
     * no momento da recuperação e manipulação dos dados.
     */
    protected $casts = [
        'visible' => 'boolean',
    ];
}
