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

Auth::routes();
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('/register', function(){
    abort(404);
});


Route::get('/tickers', function () {

    $exchange = new ccxt\exmo (['timeout' => 30000]);

    $pairs = $exchange->fetch_tickers();

    dd(current($pairs));

});

Route::get('/test', function () {

    $rate = \App\Models\Rate::find(1321);

    dd($rate->price);

});


Route::group(['namespace' => 'Admin', 'middleware' => 'auth'], function () {

    Route::get('/', ['uses' => 'DashboardC@index', 'as' => 'admin.dashboard']);

    Route::resource('key', 'KeyC', [
        'except' => ['show'],
        'names' => 'admin.key'
    ]);

    Route::get('/triangle/current', ['uses' => 'TriangleC@current', 'as' => 'admin.triangle.current']);

    Route::get('/triangle/logs', ['uses' => 'TriangleC@logs', 'as' => 'admin.triangle.logs']);
    Route::get('/triangle/show/{id}', ['uses' => 'TriangleC@show', 'as' => 'admin.triangle.show']);
    Route::get('/triangle/logs_data', ['uses' => 'TriangleC@logsData', 'as' => 'admin.triangle.logs_data']);
    Route::get('/triangle/get_data/{id}', ['uses' => 'TriangleC@getTrioData', 'as' => 'admin.triangle.get_data']);
    Route::get('/triangle/show_data/{id}', ['uses' => 'TriangleC@showTrioData', 'as' => 'admin.triangle.show_data']);


    Route::group(['middleware' => 'admin'], function () {
        Route::get('/configs', ['uses' => 'ConfigC@index', 'as' => 'admin.config']);

        Route::resource('stock', 'StockC', [
            'only' => ['index', 'edit', 'update'],
            'names' => 'admin.stock'
        ]);

        Route::get('/stock/active/{id}', ['uses' => 'StockC@active', 'as' => 'admin.stock.active']);

    });

});


