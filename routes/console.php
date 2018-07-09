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
Artisan::command('lm', function () {
    $exchange = new \ccxt\exmo([
//        'verbose' => true,
//        'proxy' => 'https://cors-anywhere.herokuapp.com/',
//        'origin' => 'foobar',
    ]);
    $exchange->userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_4) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/11.1 Safari/605.1.15';
    $exchange->enableRateLimit = true; // enable

//    dd(1);
    $markets = $exchange->load_markets();

    dd($markets);

    foreach ($markets as $market) {
        DB::table('markets')
            ->insert([
                'stock_id' => 64,
                'symbol' => $market['symbol'],
                'active' => isset($market['active']) ? $market['active'] : 1
            ]);
    }

    dd(count($markets));

});

/**
 * тест
 */
Artisan::command('ice3x', function () {
    $exchange = new \ccxt\ice3x([
//        'verbose' => true,
        'proxy' => 'https://cors-anywhere.herokuapp.com/',
        'origin' => 'foobar',
    ]);
    $exchange->userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_4) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/11.1 Safari/605.1.15';
    $exchange->enableRateLimit = true; // enable

//    dd(1);
    $markets = $exchange->fetchTicker ('ETH/BTC');

    dd($markets);

});


Artisan::command('get_markets', function () {

    $stocks = \App\Models\Stock::where('ccxt_id', 'exx')->get();

    foreach ($stocks as $stock) {

        $exchange = '\\ccxt\\' . $stock->ccxt_id;
        $exchange = new $exchange ();

        $this->info($stock->name);

        try {
            $markets = $exchange->load_markets();
//        $markets = $exchange->fetch_tickers();
        } catch (Exception $e) {
            $this->error($e->getMessage());
            continue;
        }

        foreach ($markets as $market) {
            DB::table('markets')
                ->insert([
                    'stock_id' => $stock->id,
                    'symbol' => $market['symbol'],
                    'active' => isset($market['active']) ? $market['active'] : 1
                ]);
        }

    }

})->describe('Получить пари');


Artisan::command('arb:inter_update', function () {

    dd('wqeqweq');

    $stocks = \App\Models\Stock::whereNull('error')->get();

    foreach ($stocks as $stock) {

        $exchange = '\\ccxt\\' . $stock->ccxt_id;
        $exchange = new $exchange ();

        $this->info($stock->name);

        try {
            $markets = $exchange->load_markets();
//        $markets = $exchange->fetch_tickers();
        } catch (Exception $e) {
            $this->error($e->getMessage());
            continue;
        }

        foreach ($markets as $market) {
            DB::table('markets')
                ->where('id', $stock->id)
                ->insert([
                    'stock_id' => $stock->id,
                    'symbol' => $market['symbol'],
                    'active' => isset($market['active']) ? $market['active'] : 1
                ]);
        }

    }
});

 // select symbol, count(*) as stock_qty from `markets` where active = 1 group by symbol having stock_qty > 2 order by stock_qty desc
