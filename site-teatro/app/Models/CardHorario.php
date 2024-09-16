<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardHorario extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_id', 
        'dia', 
        'horario'
    ];

    // Define o relacionamento com o model Card
    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
