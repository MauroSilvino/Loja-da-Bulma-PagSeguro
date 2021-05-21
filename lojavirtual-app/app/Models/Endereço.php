<?php

namespace App\Models;


class Endereço extends RModel
{
    protected $table = "endereços";
    protected $fillable = ['logradouro', 'complemento', 'cep', 'numero', 'cidade', 'estado'];
    
    public function setLoginAttribute($cep){
        $value = preg_replace("/[^0-9]/","", $cep);
        $this->attributes["cep"] = $value;
    }

}
