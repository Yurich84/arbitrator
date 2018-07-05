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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        $stocks = Stock::where('inter_active', 1)->get();

        foreach ($stocks as $stock) {

            echo PHP_EOL . $stock->name . ' ';

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

                try {

                    $markets = \DB::table('markets')
                        ->where('stock_id', $stock->id)
                        ->where('active', 1)
                        ->get(['symbol'])->pluck('symbol');

                    $trade_markets = \DB::table('pairs_inter')->get(['symbol'])->pluck('symbol');

                    // із пар вибрать ті які присутні в $pairs_inter

                    $res_markets = $markets->intersect($trade_markets->toArray());

                    dd($res_markets);


                } catch (\ccxt\RequestTimeout $e) {
                    $this->error($e->getMessage());
                } catch (\ccxt\DDoSProtection $e) {
                    $this->error($e->getMessage());
                } catch (\ccxt\AuthenticationError $e) {
                    $this->error($e->getMessage());
                } catch (\ccxt\ExchangeNotAvailable $e) {
                    $this->error($e->getMessage());
                } catch (\ccxt\NotSupported $e) {
                    $this->error($e->getMessage());
                } catch (\ccxt\NetworkError $e) {
                    $this->error($e->getMessage());
                } catch (\ccxt\ExchangeError $e) {
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    $this->error($e->getMessage());
                }

            }


        echo PHP_EOL;

    }
}
