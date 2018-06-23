<?php

namespace App\Console\Commands;

use App\Console\Ar\Triangles;
use App\Models\Exchange;
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
    protected $signature = 'arb:inter {exchange?}';

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
        $exchange_arr = $this->argument('exchange');

        $exchange_mod = Exchange::where('ccxt_id', $exchange_arr)->first();

        if ($exchange_mod) {

            // план: проверить/создать блеклист

            $exchange = self::$exchange_namespace . $exchange_mod->ccxt_id;
            $exchange = new $exchange (['timeout' => 30000]);

            $default_fee = 0.002;

            $fee = isset($exchange->fees['trading']['maker']) ? $exchange->describe()['fees']['trading']['maker'] : $default_fee;
            $tax = pow((1-$fee), 3);

            // перебираем пари і получаєм тройкі
            $this->info('Перебираем пари і получаєм тройкі');
            $triangles = Triangles::find($exchange);


            echo PHP_EOL;
            $this->info('Дивимся профіт по кожній тройці');

            $bar = new ProgressBar(new ConsoleOutput(), count($triangles));
            $bar->setFormat('debug');
            $bar->setBarCharacter('<comment>=</comment>');
            $bar->setBarWidth(50);
            $bar->start();

            foreach ($triangles as $trio) {

//                dd($trio);

                /*
                 * у нас есть три валюти
                 * будемо торгувать або в одному напрямку clockwise
                 * або в іншому anticlockwise
                 *
                 */

                list($A, $B, $C, $A) = explode('->', $trio['symbol']);

                /*
                 * clockwise
                 * A->B->C->A
                 */
                $price_A_B = $this->getPrice($A, $B, $trio['pairs']);
                $price_B_C = $this->getPrice($B, $C, $trio['pairs']);
                $price_C_A = $this->getPrice($C, $A, $trio['pairs']);

                $clockwise_profit = 100 * $price_A_B * $price_B_C * $price_C_A * $tax - 100;

                /*
                 * anticlockwise
                 * A->C->B->A
                 */
                $price_A_C = $this->getPrice($A, $C, $trio['pairs']);
                $price_C_B = $this->getPrice($C, $B, $trio['pairs']);
                $price_B_A = $this->getPrice($B, $A, $trio['pairs']);

                $anticlockwise_profit = 100 * $price_A_C * $price_C_B * $price_B_A * $tax - 100;

                $profit = max($clockwise_profit, $anticlockwise_profit);

                if($profit > 0) {
                    // у нас позитивний профіт
//                    $this->info($trio['symbol'] . ' - ' . $profit . ' %');

                    // найти минимум на которий модно торговать

                    // записать в базу последовательность, время, минимум, дополнительную инфо в json

                    \DB::table('triangle_forks')
                        ->insert([
                            'exchange_id' => $exchange_mod->id,
                            'symbol' => $trio['symbol'],
                            'profit' => $profit,
                            'created_at' => Carbon::now(),
                        ]);
                }

                $bar->advance();

            }


        } else {
            $this->error('Enter correct Exchange name ');
            $this->info(implode(', ', ccex::$exchanges));
        }

        echo PHP_EOL;

    }


    protected function getPrice($from, $to, $pairs_arr)
    {
        $price = 0;
        foreach ($pairs_arr as $pair) {
            if ($pair['base_curr'] == $from && $pair['quote_curr'] == $to) {
                // SELL: ми продаєм, берем bid
                if($pair['bid'] > 0) {
                    $price0 = $price = $pair['bid'];
                } else {
                    $price = 0;
                }
            } elseif ($pair['base_curr'] == $to && $pair['quote_curr'] == $from) {
                // BUY: ми купуємо, берем ask
                if($pair['ask'] > 0) {
                    $price = 1 / $pair['ask'];
                    $price0 = $pair['ask'];
                } else {
                    $price = 0;
                }
            }
        }

//        $this->info($from . '->' . $to . ' - ' . $price0);

        return $price;

    }
}
