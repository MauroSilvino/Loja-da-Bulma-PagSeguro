<?php

namespace App\Services;

use App\Models\Usuario;
use App\Models\Endereço;
use Log;

class ClienteService{

    public function salvarUsuario(Usuario $user, Endereço $endereco){
        try{
            $dbUsuario = Usuario::where('login', $user->login)->first();
            if($dbUsuario){
                return ['status' => 'err', 'message' => 'Login já cadastrado no sistema.'];
            }

            \DB::beginTransaction();
            $user->save();
            $endereco->usuario_id = $user->id;
            $endereco->save();
            \DB::commit();

            return ['status' => 'ok', 'message' => 'Cadastro feito com sucesso'];

        }catch(\Exception $e){
            \DB::rollback();
            //dd($endereco);
            \Log::error("ERRO", ['file' => 'ClienteService.salvarUsuario', 'message' => $e->getMessage()]);
            return ['status' => 'err', 'message' => 'Não pode cadastrar o usuario, verifique todos os campos.'];
        }


    }

}