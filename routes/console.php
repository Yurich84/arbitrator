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
 * очистка логов
 */
Artisan::command('arb:clear', function () {
    $yestarday = \Carbon\Carbon::now()->subDays(1);
    DB::table('triangle_forks')
        ->where('created_at', '<', $yestarday)
        ->delete();
})->describe('clear old data in triangle_forks table');

/**
 * Парсим валюти с coinmarketcap
 */
Artisan::command('coinmarketcap', function () {

    DB::table('rates')->truncate();

    $coins = json_decode(file_get_contents('https://api.coinmarketcap.com/v2/listings/'));

    foreach ($coins->data as $coin) {
        DB::table('rates')->insert([
            'id'         => $coin->id,
            'symbol'     => $coin->symbol,
            'name'       => $coin->name,
            'updated_at' => Carbon\Carbon::now()
        ]);
    }

    $this->info('Done!');

})->describe('Парсим валюти с coinmarketcap');


/**
 * Парсим валюти с coinmarketcap limit 100
 */
Artisan::command('cmc_100', function () {

    DB::table('rates')->truncate();

    $tickers = json_decode(file_get_contents('https://api.coinmarketcap.com/v2/ticker/'));

    if ($tickers->data) {
        foreach ($tickers->data as $ticker) {
            DB::table('rates')->insert([
                'id'         => $ticker->id,
                'symbol'     => $ticker->symbol,
                'name'       => $ticker->name,
                'price'      => $ticker->quotes->USD->price,
                'updated_at' => \Carbon\Carbon::createFromTimestamp($ticker->last_updated)
            ]);
        }
        $this->info('Done!');
    } else {
        $this->error($tickers->metadata->error);
    }

})->describe('Парсим валюти с coinmarketcap');

/**
 * Парсим валюти с coinmarketcap
 */
Artisan::command('get_rate {symbol}', function ($symbol) {

    $rate = \App\Models\Rate::where('symbol', mb_strtoupper($symbol))->first();

    $ticker = json_decode(file_get_contents("https://api.coinmarketcap.com/v2/ticker/{$rate->id}/"));

    if ($ticker->data) {
        $rate->price = $ticker->data->quotes->USD->price;
        $rate->updated_at = \Carbon\Carbon::createFromTimestamp($ticker->data->last_updated);
        $rate->save();

        $this->info('Done!');
    } else {
        $this->info($ticker->metadata->error);
    }


})->describe('Парсим валюти с coinmarketcap');

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

/**
 * Найти все биржи где есть bidVolume
 */
Artisan::command('qty1', function () {
    $ex = new \ccxt\yunbi();
    $pairs = $ex->fetch_tickers();
    dd($pairs);
});
Artisan::command('qty', function () {

    $stocks = \App\Models\Stock::where('id', '>', 113)->get();

    foreach ($stocks as $stock) {

        $error = null;

        if(in_array($stock->ccxt_id, ['_1broker', '_1btcxe', 'coincheck', 'allcoin', 'anxpro', 'bit2c'])) continue;

        $exchange = '\\ccxt\\' . $stock->ccxt_id;
        $exchange = new $exchange (['timeout' => 30000]);

        echo PHP_EOL . $stock->ccxt_id;

        try{
            $pairs = $exchange->fetch_tickers();
        } catch (Exception $e) {
            continue;
        }

        $pairs = $exchange->fetch_tickers();

        $count = count($pairs);

        echo ' ' . $count;


        DB::table('stocks')
            ->where('id', $stock->id)
            ->update([
                'market_qty' => $count
            ]);

    }

})->describe('Получить сиакани по парі на біржі');