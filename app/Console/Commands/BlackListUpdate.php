<?php

namespace App\Console\Commands;

use App\Models\BlackList;
use App\Models\Stock;
use ccxt\ccex;
use Illuminate\Console\Command;

class BlackListUpdate extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'arb:blacklist {exchange}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Black list';

    public static $exchange_namespace = '\\ccxt\\';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $exchange_arr = $this->argument('exchange');

        $stock = Stock::where('ccxt_id', $exchange_arr)->first();

        if ($stock) {
            // Remove current black listed coins
            BlackList::where('stock_id', $stock->id)->delete();

            $exchange = self::$exchange_namespace . $stock->ccxt_id;
            $exchange = new $exchange ();

            $markets = collect($exchange->fetchMarkets());

            $bad_markets = $markets->where('active', false);

            // Update black listed coins
            foreach ($bad_markets as $trade_pair) {

                $this->comment($trade_pair['symbol']);

                BlackList::create([
                    'symbol' => $trade_pair['symbol'],
                    'stock_id' => $stock->id,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }

            $this->info('Black list coins updated.');
        } else {
            $this->error('Enter correct Exchange name ');
            $this->info(implode(', ', ccex::$exchanges));
        }
    }
}
