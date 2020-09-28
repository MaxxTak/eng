<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/teste', function () {
    return "Ok";
});

Route::middleware(['auth:api'])->group(function () {
    Route::get('/teste', function () {
        return "Ok";
    });

    Route::get('/cnpj/fornecedor','FornecedorController@retornarCnpj');
    Route::get('/buscar/fornecedor','FornecedorController@retornarFornecedor');
    Route::get('/categorias','FornecedorController@retornarCategorias');

    Route::resource('contato','ContatoController');
    Route::resource('vendedor','VendedorController');
    Route::resource('etapa','EtapasController');

    Route::resource('usuario','UsuarioController');
    Route::resource('cliente','ClienteController');
    Route::resource('forma','FormaPagamentoController');
    Route::resource('obra','ObraController');
    Route::resource('tipo/obra','TipoObraController');
    Route::resource('arquivos','ArquivosController');
    Route::resource('fornecedor','FornecedorController');
});