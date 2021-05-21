<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function logar(Request $request){
        $data=[];

        if($request->isMethod('POST')){
            //dd($request);
            $login = $request->input("login");
            $password = $request->input("password");

            $login = preg_replace("/[^0-9]/","", $login);

            $credentials = ['login' => $login, 'password' => $password];

            if(Auth::attempt($credentials)){
    
                return redirect()->route("home");

            }else{
                //dd($credential);
                //throw new \Exception($e->getMessage());
                $request->session()->flash("err", "Usuário / Senha inválidos");
                return redirect()->route("logar");
            }
        }

        return view('logar', $data);

    }

    public function sair(Request $request){
        Auth::logout();
        return redirect()->route('home');

    }
}
