<?php

namespace App\Services;

use Log;
use App\Models\Usuario;
use App\Models\Pedido;
use App\Models\Itens_pedido;

class VendaService {
    public function finalizarVenda($prods = [], Usuario $user){
        
        if(session('cart', []) != []){
            try{
                \DB::beginTransaction();
                $dtHoje = new \DateTime();

                $pedido = new Pedido();
                $pedido->datapedido = $dtHoje->format("Y-m-d H:i:s");
                $pedido->status = "PEN";
                $pedido->usuario_id = $user->id;

                $pedido->save();

                foreach($prods as $p){
                    $itens = new Itens_pedido;
            
                    $itens->quantidade = 1;
                    $itens->valor = $p->valor;
                    $itens->dt_item = $dtHoje->format("Y-m-d H:i:s");
                    $itens->produto_id = $p->id;
                    $itens->pedido_id = $pedido->id;
                    $itens->save();

                }
            
                \DB::commit();
            
                return ['status' => 'ok', 'message' => 'Pedido feito com sucesso', 'idpedido' => $pedido->id, 'quantidade' => $itens->quantidade];
            
            }catch(\Exception $e){
                \DB::rollback();

                Log::error("ERRO, VENDA SERVICE", ['message' => $e->getMessage() ]);
                return ['status' => 'err', 'message' => 'Venda não pode ser finalizada'];
            }
        }else{
            return ['status' => 'err', 'message' => 'Seu carrinho está vazio'];

        }
    }
}