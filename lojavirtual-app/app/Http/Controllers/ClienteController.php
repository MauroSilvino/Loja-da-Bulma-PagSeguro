<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Endereço;
use App\Services\ClienteService;

class ClienteController extends Controller
{
    public function cadastrar(Request $request){
        $data = [];
        return view("cadastrar", $data);
    
    }

    public function cadastrarCliente(Request $request){
        $values = $request -> all();

        $usuario = new Usuario();
        $usuario->fill($values);
        $usuario->login = $request -> input('cpf', '');

        $senha = $request->input('password', '');
        //$usuario->password = $senha;
        $usuario->password = \Hash::make($senha);

        $endereco = new Endereço();
        $endereco->fill($values);
        $endereco->logradouro = $request -> input('endereco', '');

        $clienteService = new ClienteService();
        $result = $clienteService->salvarUsuario($usuario, $endereco);

        $message = $result['message'];
        $status = $result['status'];

        $request->session()->flash($status, $message);

        return redirect()->route('cadastrar');
    
    }
}

?>