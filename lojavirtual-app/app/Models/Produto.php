<?php

namespace App\Models;

class Produto extends RModel
{
    protected $table = "produtos";
    protected $fillable = ['nome', 'valor', 'foto', 'descricao', 'categoria_id'];
}
