<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');


/**
 * тестовая ф-ция по созданию файла с временем
 */
Artisan::command('make_file', function () {
    Storage::put('file.txt', time(), 'public');
})->describe('Put into file');


/**
 * тестовая ф-ция по созданию файла с временем
 */
Artisan::command('clear_old_triangle_forks', function () {
    $yestarday = \Carbon\Carbon::now()->subDays(1);
    DB::table('triangle_forks')
        ->where('created_at', '<', $yestarday)
        ->delete();
})->describe('clear old data in triangle_forks table');

/**
 * Показ котировок на конкретной биржи и пари
 */
Artisan::command('ticker {exchange} {symbol}', function ($exchange, $symbol) {

    if ($exchange && in_array ($exchange, \ccxt\ccex::$exchanges)) {

            $exchange = '\\ccxt\\' . $exchange;
            $exchange = new $exchange (['timeout' => 30000]);

            $pair = $exchange->fetch_ticker($symbol);
            dd($pair);
    }

})->describe('Получить сиакани по парі на біржі');