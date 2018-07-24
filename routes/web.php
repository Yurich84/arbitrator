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

Route::pattern('id', '\d+');
Route::pattern('slug', '[a-z0-9-_]+');
Route::pattern('url', '[a-z0-9-_]+');
Route::pattern('pair', '[a-zA-z-_\/]+');

Route::get('test', function () {
    $text = "url: " . \Request::fullUrl();

    \Mail::raw($text, function ($message) {
        $message->to(config('app.dev_email'), $name = null);
        $message->subject('Exception');
    });
});


Auth::routes();
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::name('auth.resend_confirmation')
    ->get('/register/confirm/resend', 'Auth\RegisterController@resendConfirmation');
Route::name('auth.confirm')
    ->get('/register/confirm/{confirmation_code}', 'Auth\RegisterController@confirm');


/******************************************
 *              FRONTEND
 */
Route::group(['namespace' => 'Front'], function () {
    /*-------------------------------
     *    Внешнебиржевой арбитраж
     * -----------------------------
     */
    Route::get('/', ['uses' => 'InterC@current', 'as' => 'inter.current']);
    Route::post('/', ['uses' => 'InterC@current', 'as' => 'inter.current_post']);

    Route::get('inter/table/{up_id}/{pair}', ['uses' => 'InterC@show', 'as' => 'inter.table']);
    Route::get('inter/history/{pair}', ['uses' => 'InterC@history', 'as' => 'inter.history']);
});


/******************************************
 *              BACKEND
 */
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'auth'], function () {

    Route::get('/', ['uses' => 'DashboardC@index', 'as' => 'admin.dashboard']);

    Route::resource('key', 'KeyC', [
        'except' => ['show'],
        'names' => 'admin.key'
    ]);


    /*-------------------------------
     *    Внутрибиржевой арбитраж
     * -----------------------------
     */
    Route::get('triangle/current', ['uses' => 'TriangleC@current', 'as' => 'admin.triangle.current']);
    Route::get('triangle/logs', ['uses' => 'TriangleC@logs', 'as' => 'admin.triangle.logs']);
    Route::get('triangle/show/{id}', ['uses' => 'TriangleC@show', 'as' => 'admin.triangle.show']);
    Route::get('triangle/logs_data', ['uses' => 'TriangleC@logsData', 'as' => 'admin.triangle.logs_data']);
    Route::get('triangle/get_data/{id}', ['uses' => 'TriangleC@getTrioData', 'as' => 'admin.triangle.get_data']);
    Route::get('triangle/show_data/{id}', ['uses' => 'TriangleC@showTrioData', 'as' => 'admin.triangle.show_data']);



    /*-------------------------------
     *    Администрация
     * -----------------------------
     */
    Route::group(['middleware' => 'admin'], function () {
        Route::get('/configs', ['uses' => 'ConfigC@index', 'as' => 'admin.config']);

        Route::resource('stock', 'StockC', [
            'only' => ['index', 'edit', 'update'],
            'names' => 'admin.stock'
        ]);

        Route::get('/stock/active/{id}', ['uses' => 'StockC@active', 'as' => 'admin.stock.active']);

    });

});


