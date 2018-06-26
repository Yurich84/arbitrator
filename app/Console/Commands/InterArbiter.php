<?php

namespace App\Console\Commands;

use App\Console\Ar\Triangles;
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

        $stock = Stock::where('ccxt_id', $exchange_arr)->first();

        if ($stock) {

            // берем биржу
            $exchange = self::$exchange_namespace . $stock->ccxt_id;
            $exchange = new $exchange (['timeout' => 30000]);

            $default_fee = 0.002;

            $fee = $stock->fee ?: $default_fee;
            $tax = pow((1-$fee), 3);

            // перебираем пари і получаєм тройкі
            $this->info('Перебираем пари і получаєм тройкі');
            $triangles = Triangles::find($exchange, $stock->id);

            echo PHP_EOL;
            $this->info('Дивимся профіт по кожній тройці');

            $bar = new ProgressBar(new ConsoleOutput(), count($triangles));
            $bar->setFormat('debug');
            $bar->setBarCharacter('<comment>=</comment>');
            $bar->setBarWidth(50);
            $bar->start();

            foreach ($triangles as $trio) {

                list($A, $B, $C, $A) = explode('->', $trio['symbol']);

                /*
                 * A->B->C->A
                 */
                $price_A_B = $this->getPrice($A, $B, $trio['pairs']);
                $price_B_C = $this->getPrice($B, $C, $trio['pairs']);
                $price_C_A = $this->getPrice($C, $A, $trio['pairs']);

                $profit = 100 * $price_A_B * $price_B_C * $price_C_A * $tax - 100;

                if($profit > 0) { // у нас позитивний профіт

//                    $this->info($trio['symbol'] . ' - ' . $profit . ' %');

                    // записать в базу
                    \DB::table('triangle_forks')
                        ->insert([
                            'stock_id' => $stock->id,
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
