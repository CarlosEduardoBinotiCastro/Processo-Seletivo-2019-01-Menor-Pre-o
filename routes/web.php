<?php

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

// rotas para home
Route::get('/', function () {
    return redirect()->route('home');
});
Route::get('/v1', function () {
    return redirect()->route('home');
});

// Grupos de rota para a versão 1
Route::group(['prefix' => 'v1'], function () {

    Route::get('/home', function () {
        return view('home');
    })->name('home');

    // somente para apagar os dados mais rapidos. (para teste)
    Route::get('/apagar', 'ImportController@apagar');

    // Rotas do controller de import
    Route::get('/importar', 'ImportController@importar');
    Route::get('/padronizar', 'ImportController@padronizarDados');
    Route::get('/download', 'ImportController@download');

    // Rotas para verificar se existe ou nao parametros
    Route::group(['middleware' => 'Parametros'], function () {
        Route::get('/produtos', 'ProdutosController@buscarProdutos');
        Route::get('produtos/{parametros}', 'ProdutosController@buscarProdutos')->where('parametros','.+');

    });

});

