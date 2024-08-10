<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $table = 'cards';
    protected $fillable = ['name', 'date', 'local', 'img', 'visible', 'ticket_link']; // Adicionando 'visible' como campo preenchível

    // Define o tipo do atributo 'visible' como booleano
    protected $casts = [
        'visible' => 'boolean',
    ];
}