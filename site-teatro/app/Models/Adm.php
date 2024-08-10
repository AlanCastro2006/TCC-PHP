<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Adm extends Model implements Authenticatable
{
    use HasFactory, AuthenticatableTrait;

    // Se a tabela no banco de dados não segue a convenção plural do Laravel,
    // defina o nome da tabela aqui.
    protected $table = 'adm';

    // Se a tabela não tem os campos 'created_at' e 'updated_at',
    // defina isso como false.
    public $timestamps = false;

    // Defina os campos que podem ser preenchidos em massa.
    protected $fillable = ['username', 'password'];
}