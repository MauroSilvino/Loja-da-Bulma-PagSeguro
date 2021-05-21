<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UsuarioController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::match(['get', 'post'], '/', [ProdutoController::class, 'index'])->name('home');
Route::match(['get', 'post'], '/categoria', [ProdutoController::class, 'categoria'])->name('categoria');
Route::match(['get', 'post'], '/{idcategoria}/categoria', [ProdutoController::class, 'categoria'])->name('categoria_por_id');
Route::match(['get', 'post'], '/{idproduto}/carrinho/adicionar', [ProdutoController::class, 'adicionarCarrinho'])->name('adicionar_carrinho');
Route::match(['get', 'post'], '/carrinho', [ProdutoController::class, 'verCarrinho'])->name('ver_carrinho');
Route::match(['get', 'post'], '/carrinho/finalizar', [ProdutoController::class, 'finalizar'])->name('carrinho_finalizar');
Route::match(['get', 'post'], '/{indice}/excluircarrinho', [ProdutoController::class, 'excluirCarrinho'])->name('carrinho_excluir');
Route::match(['get', 'post'], '/cadastrar', [ClienteController::class, 'cadastrar'])->name('cadastrar');
Route::match(['get', 'post'], '/cliente/cadastrar', [ClienteController::class, 'cadastrarCliente'])->name('cadastrar_cliente');
Route::match(['get', 'post'], '/logar', [UsuarioController::class, 'logar'])->name('logar');
Route::match(['get', 'post'], '/sair', [UsuarioController::class, 'sair'])->name('sair');
Route::match(['get', 'post'], '/compras/historico', [ProdutoController::class, 'historico'])->name('comprar_historico');
Route::post('/compras/detalhes', [ProdutoController::class, 'detalhes'])->name('comprar_detalhes');
Route::match(['get', 'post'], '/compras/pagar', [ProdutoController::class, 'pagar'])->name('pagar');
Route::match(['get', 'post'], '/compras/obrigado', [ProdutoController::class, 'obrigado'])->name('obrigado');
Route::match(['get', 'post'], '/compras/pagseguro', [ProdutoController::class, 'encerrarPagamento'])->name('pagseguro');
Route::match(['get', 'post'], '/{idproduto}/produto', [ProdutoController::class, 'paginaProduto'])->name('pagina_produto');
Route::match(['get', 'post'], '/quantidade', [ProdutoController::class, 'quantidade'])->name('quantidade');