<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Adm extends Model implements Authenticatable
{
    use HasFactory, AuthenticatableTrait;

    /**
     *  Define o nome da tabela associada ao modelo.
     * Se a tabela não seguir a convenção plural do Laravel (ou seja, 'adms' ao invés de 'adm'),
     *  defina o nome da tabela aqui.
     */
    protected $table = 'adm';

    /**
     * Define se a tabela usa as colunas 'created_at' e 'updated_at'.
     * Se a tabela não possui essas colunas, defina como false.
     */
    public $timestamps = false;

    /** 
     *  Define os campos que podem ser preenchidos em massa (mass assignable).
     * Isso ajuda a proteger contra ataques de injeção de massa, permitindo
     * apenas os campos especificados serem preenchidos via requests.
     */
    protected $fillable = ['username', 'password'];
}
