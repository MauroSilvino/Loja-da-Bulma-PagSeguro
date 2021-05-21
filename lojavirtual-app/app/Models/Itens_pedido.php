<?php

namespace App\Models;

class Itens_pedido extends RModel
{
    protected $table = "itens_pedidos";
    protected $fillable = ['quantidade', 'valor', 'dt_item', 'produto_id', 'pedido_id'];
}
