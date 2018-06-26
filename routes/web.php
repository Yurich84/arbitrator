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


Route::get('/encrypt', function () {
    $val1 = Crypt::encrypt('K-7cc97c89aed2a2fd9ed7792d48d63f65800c447b');
    echo $val1;
    $val2 = Crypt::decrypt($val1);
    echo "<br>" . $val2;
});


Route::get('/tickers', function () {

    $exchange = new ccxt\exmo (['timeout' => 30000]);

    $pairs = $exchange->fetch_tickers();

    dd(current($pairs));

});



Route::group(['namespace' => 'Admin', 'middleware' => 'auth'], function () {

    Route::get('/', ['uses' => 'DashboardC@index', 'as' => 'admin.dashboard']);

    Route::get('/keys', ['uses' => 'KeyC@index', 'as' => 'admin.keys']);


    Route::get('/triangle/current', ['uses' => 'TriangleC@current', 'as' => 'admin.triangle.current']);

    Route::get('/triangle/logs', ['uses' => 'TriangleC@logs', 'as' => 'admin.triangle.logs']);
    Route::get('/triangle/logs_data', ['uses' => 'TriangleC@logsData', 'as' => 'admin.triangle.logs_data']);


    Route::group(['middleware' => 'admin'], function () {
        Route::get('/configs', ['uses' => 'ConfigC@index', 'as' => 'admin.config']);

        Route::resource('stock', 'StockC', [
            'only' => ['index', 'edit', 'update'],
            'names' => 'admin.stock'
        ]);

    });

});


