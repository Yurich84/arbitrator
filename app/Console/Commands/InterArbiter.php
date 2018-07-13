<?php

namespace App\Console\Commands;

use App\Models\Stock;
use Carbon\Carbon;
use ccxt\ccex;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class InterArbiter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'arb:inter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inter exchange arbitrage bot';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    public static $exchange_namespace = '\\ccxt\\';

    public $exchanges_w_proxy = [
        'ccex', 'exx', 'ice3x', 'poloniex', 'okex'
    ];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // фиксируем обновление
        $up_id = \DB::table('inter_updates')->insertGetId([
            'time' => Carbon::now()
        ]);

        $stocks = Stock::where('inter_active', 1)
//            ->where('id', '>', 61)
            ->get();

        foreach ($stocks as $stock) {

            echo $stock->name . ' ';

            $exchange = '\\ccxt\\' . $stock->ccxt_id;
            $exchange = new $exchange ();
            $exchange->enableRateLimit = true;

            // если у биржи есть публичние ключи
            if($stock->pub_key !== null) {
                $exchange->apiKey = $stock->pub_key;
            }
            if($stock->pub_key !== null) {
                $exchange->secret = $stock->pub_secret;
            }
            if($stock->pub_key !== null) {
                $exchange->uid = $stock->pub_uid;
            }

            // Для бирж из списка подключаем прокси
            if(in_array($exchange->id, $this->exchanges_w_proxy)) {
                $exchange->proxy = 'https://cors-anywhere.herokuapp.com/';
                $exchange->origin = 'foobar';
                $exchange->userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_4) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/11.1 Safari/605.1.15';
            }

            // Вибираем пари
            $markets = \DB::table('markets')
                ->where('stock_id', $stock->id)
                ->where('active', 1)
                ->get(['symbol'])->pluck('symbol');

            $trade_markets = \DB::table('trade_pairs')->get(['symbol'])->pluck('symbol');

            // із пар вибрать ті які присутні в $pairs_inter
            $res_markets = $markets->intersect($trade_markets->toArray())->toArray();

            sort($res_markets);

            if( empty($res_markets) ) continue;

            $current_tickers = [];

            try {

                if($stock->ccxt_id == 'yobit') {
                    // Yobit разделить на двое
                    $res_markets1 = array_slice($res_markets, 0, 60);
                    $res_markets2 = array_slice($res_markets, 60);

                    $current_tickers1 = $exchange->fetchTickers($res_markets1);
                    $current_tickers2 = $exchange->fetchTickers($res_markets2);

                    $current_tickers = array_merge($current_tickers1, $current_tickers2);

                } elseif ($stock->allow_tickers == 0) {
                    // план: тут еще можно по одной паре дергать с интервалом в 2-10 секунд если пар немного
                    echo ' - парсим по одному ';
                } else {
                    $current_tickers = $exchange->fetchTickers($res_markets);
                }

                if(! empty($current_tickers)) {
                    // пишем пари в базу
                    foreach ($current_tickers as $tiker) {
                        $trading_symbol = \DB::table('trade_pairs')
                            ->select('id')
                            ->where('symbol', $tiker['symbol'])
                            ->first();

                        if($trading_symbol) {
                            \DB::table('inter_pairs')->insert([
                                'stock_id' => $stock->id,
                                'symbol' => $tiker['symbol'],
                                'price' => $tiker['last'],
                                'volume' => $tiker['quoteVolume'], // количество биткоиновив, ефирумов или чего там (того к чему торгуеться основная)
                                'up_id' => $up_id,
                                'symbol_id' => $trading_symbol->id
                            ]);
                        }

                    }
                }

                $message = '';

            } catch (\ccxt\RequestTimeout $e) {
                $message = $e->getMessage();
            } catch (\ccxt\DDoSProtection $e) {
                $message = $e->getMessage();
            } catch (\ccxt\AuthenticationError $e) {
                $message = $e->getMessage();
            } catch (\ccxt\ExchangeNotAvailable $e) {
                $message = $e->getMessage();
            } catch (\ccxt\NotSupported $e) {
                $message = $e->getMessage();
            } catch (\ccxt\NetworkError $e) {
                $message = $e->getMessage();
            } catch (\ccxt\ExchangeError $e) {
                $message = $e->getMessage();
            } catch (\Exception $e) {
                $message = $e->getMessage();
            }

            if($message <> '') {
                $this->error($message);

                // пишем ошибку в базу
                \DB::table('inter_up_error')->insert([
                    'up_id' => $up_id,
                    'stock_id' => $stock->id,
                    'message' => $message
                ]);
            } else {
                $this->comment(count($res_markets));
            }

        }

        echo PHP_EOL;

    }
}
