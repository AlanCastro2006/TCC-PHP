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
    // Define os campos que podem ser preenchidos em massa
    protected $fillable = [
        'name',
        'img',
        'visible',
        'ticket_link',
        'classification',
        'description',
        'duration',
        'season',
        'days',
        'season_start',
        'season_end',
        'texto',           // Autoria do texto ou adaptação
        'elenco',
        'direcao',
        'figurino',
        'cenografia',
        'iluminacao',
        'sonorizacao',
        'producao',
        'costureira',
        'assistente_cenografia',
        'cenotecnico',
        'consultoria_design',
        'co_producao',
        'agradecimentos'
    ];
}
