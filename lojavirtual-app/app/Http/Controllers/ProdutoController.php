<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorias;
use App\Models\Produto;
use App\Services\VendaService;
use App\Models\Pedido;
//use App\Services\Usuario;
use App\Models\Itens_pedido;
use PagSeguro\Configuration\Configure;


class ProdutoController extends Controller{

    private $_configs;

    public function __construct(){
        $this->_configs = new Configure();
        $this->_configs->setCharset("UTF-8");
        $this->_configs->setAccountCredentials(env('PAGSEGURO_EMAIL'), env('PAGSEGURO_TOKEN'));
        $this->_configs->setEnvironment(env('PAGSEGURO_AMBIENTE'));
        $this->_configs->setLog(true, storage_path('logs/pagseguro_' . date('Ymd') . '.log'));
    }

    public function getCredentials(){
        return $this->_configs->getAccountCredentials();
    }

    public function index(Request $request){
        $data = [];
        $listaProdutos = Produto::all();
        $data["lista"] = $listaProdutos;
        return view("home", $data);

    
    }

    public function categoria($idcategoria = 0, Request $request){
        $data = [];
        $listaCategorias = Categorias::all();
        $queryProduto = Produto::limit(6);

        if ($idcategoria != 0){
            $queryProduto->where("categoria_id", $idcategoria);
        }

        $listaProdutos = $queryProduto->get();

        $data["lista"] = $listaProdutos;
        $data["listaCategoria"] = $listaCategorias;
        $data["idcategoria"] = $idcategoria;


        return view("categoria", $data);
    
    }

    public function paginaProduto($idproduto = 0, Request $request){
        $data = [];
        $queryProduto = Produto::all();
        if ($idproduto != 0){
            $queryProduto->where("id", $idproduto);
        }
        //dd($queryProduto);
        $prod = $queryProduto->get($idproduto - 1);
        $data["prod"] = $prod;
        $data["idproduto"] = $idproduto;

        return view("paginaproduto", $data);

    }

    public function adicionarCarrinho($idProduto = 0, Request $request){
        $prod = Produto::find($idProduto);

        if($prod){
        $carrinho = session('cart', []);
        }

        array_push($carrinho, $prod);
        session(['cart' => $carrinho]);

        

        

        return redirect()->route('ver_carrinho');

    }

    public function verCarrinho(Request $request){
        $carrinho = session('cart', []);
        $data = ['cart' => $carrinho];

        return view("carrinho", $data);
    }

    public function excluirCarrinho($indice, Request $request){
        $carrinho = session('cart', []);

        if(isset($carrinho[$indice])){
            unset($carrinho[$indice]);
        }
        session(['cart' => $carrinho]);
        return redirect()->route('ver_carrinho');
    }

    public function finalizar(Request $request){

            $prods = session('cart', []);
            
            if(\Auth::user()){
            $vendaService = new VendaService();
            $result = $vendaService->finalizarVenda($prods, \Auth::user());
            
            }else{
                
                $result = ['status' => 'err', 'message' => 'Cadastre-se ou Faça Login Primeiro'];
            }

        $request->session()->flash($result['status'], $result['message']);
        
        if(\Auth::user()){
            $request->session()->flash($result['idpedido']);
            return redirect()->route('pagar');
        }else{
            return redirect()->route('ver_carrinho');
        }
    }

    public function historico(Request $request){
        $data = [];
        $idusuario = \Auth::user()->id;
        $listaPedido = Pedido::where("usuario_id", $idusuario)->orderBy("datapedido", "desc")->get();
        $data["lista"] = $listaPedido;
        
        return view("historico", $data);
    }

    public function detalhes(Request $request){
        $idpedido = $request->input("idpedido");
        //dd($listaItens);
        try{
            //dd($listaItens);
            $listaItens = Itens_pedido::join("produtos", "produtos.id", "=", "itens_pedidos.produto_id")
            ->where("pedido_id", $idpedido)
            ->get(['itens_pedidos.*', 'itens_pedidos.valor as valoritem', 'produtos.*']);
            //dd($listaItens);
        }catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        $data = [];
        $data["listaitens"] = $listaItens;

        return view("detalhes", $data);
    
    }

    public function pagar(Request $request){
        $data = [];
        $carrinho = session('cart', []);
        $data["cart"] = $carrinho;

        $sessionCode = \PagSeguro\Services\Session::create(
            $this->getCredentials()
        );

        $IDSession = $sessionCode->getResult();
        $data["sessionID"] = $IDSession;

        return view("pagar", $data);
        
    }

    public function encerrarPagamento(Request $request){
            $data = [];
            $prods = session('cart', []);

   //         $request->session()->forget("cart");
            $credCard = new \PagSeguro\Domains\Requests\DirectPayment\CreditCard();
            $credCard->setReference("PED_", session("idpedido"));
            $credCard->setCurrency("BRL");

            foreach($prods as $p){
                $credCard->addItems()->withParameters(
                    $p->id,
                    $p->nome,
                    1,
                    number_format($p->valor, 2,".","")
                );
            }

            $user = \Auth::user();
            $credCard->setSender()->setName($user->nome);
            $credCard->setSender()->setEmail($user->login . "@sandbox.pagseguro.com.br");
            $credCard->setSender()->setHash($request->input("hashseller"));
            $credCard->setSender()->setPhone()->withParameters(21,22443453);
            $credCard->setSender()->setDocument()->withParameters("CPF", $user->login);

            $credCard->setShipping()->setAddress()->withParameters(
                'Av. A',
                '1234',
                'Jardim Botanico',
                '23456777',
                'Rio de Janeiro',
                'RJ',
                'BRA',
                'Apt. 100'
            );

            $credCard->setBilling()->setAddress()->withParameters(
                'Av. A',
                '1234',
                'Jardim Botanico',
                '23456777',
                'Rio de Janeiro',
                'RJ',
                'BRA',
                'Apt. 100'
            );

            $cardtoken = $request->input("cardtoken");
            
            $credCard->setToken($cardtoken);
         

            $nparcela = $request->input("nparcela");
            $totalapagar = $request->input("totalapagar");
            $totalparcela = $request->input("totalparcela");
            //dd($totalapagar);
            $credCard->setInstallment()->withParameters($nparcela, number_format($totalparcela, 2,".",""));
            
            $credCard->setHolder()->setName($user->nome);
            $credCard->setHolder()->setDocument()->withParameters("CPF", $user->login);
            $credCard->setHolder()->setBirthdate("23/07/1993");
            $credCard->setHolder()->setPhone()->withParameters(21, 34566543);

            $credCard->setMode("DEFAULT");
            $result = $credCard->register($this->getCredentials());

            //return view("obrigado", $data);
            $request->session()->forget("cart");
            return redirect()->route("obrigado");

        }

        public function obrigado(Request $request){

            return view("obrigado");
        }

        public function quantidade(Request $request){
            
    
            return redirect()->route("ver_carrinho");
        }
    }
