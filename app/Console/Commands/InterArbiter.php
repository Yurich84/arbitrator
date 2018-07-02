<?php

namespace App\Console\Commands;

use App\Arbitrator\TriangleCalculate;
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
    protected $signature = 'arb:trio {exchange?}';

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

            $fee = $stock->fee ?: config('bot.fee');
            $tax = 1-$fee;

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

                $calculate = new TriangleCalculate($trio['symbol'], $trio['pairs'], $tax);

                if($calculate->profit > 0) { // у нас позитивний профіт

                    // ТУТ БУДЕМ ТОРГУВАТЬ

                    $pairs = [];
                    foreach ($trio['pairs'] as $symbol) {

                        $orderBook = $exchange->fetch_order_book($symbol->base_curr . '/' . $symbol->quote_curr, 1);

                        $pairs[] = (object) [
                            'base_curr'  => $symbol->base_curr,
                            'quote_curr' => $symbol->quote_curr,
                            'bid'        => $orderBook['bids'][0][0],
                            'ask'        => $orderBook['asks'][0][0],
                            'min_bid'    => $orderBook['bids'][0][1],
                            'min_ask'    => $orderBook['asks'][0][1]
                        ];
                    }

                    $calculate = new TriangleCalculate($trio['symbol'], $pairs, $tax);

//                    $this->info($trio['symbol'] . ' - ' . $profit . ' %');

                    // записать в базу
                    \DB::table('triangle_forks')
                        ->insert([
                            'stock_id' => $stock->id,
                            'symbol' => $trio['symbol'],
                            'profit' => $calculate->profit,
                            'pairs'  => json_encode($pairs),
                            'min'    => $calculate->min,
                            'comment'=> $calculate->comment,
                            'created_at' => Carbon::now(),
                        ]);
                }

                $bar->advance();

            }

            $stock->updated_at = Carbon::now();
            $stock->save();

        } else {
            $this->error('Enter correct Exchange name ');
            $this->info(implode(', ', ccex::$exchanges));
        }

        echo PHP_EOL;

    }
}
