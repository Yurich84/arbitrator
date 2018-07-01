<?php

namespace App\Console\Ar;

use App\Models\BlackList;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class Triangles
{

    const START_CURRENT = 'DOGE';

    public static $exchange_namespace = '\\ccxt\\';

    /**
     * Знаходимо трокі валют
     * @param $exchange
     * @return array
     */
    public static function find($exchange, $stock_id)
    {

            $pairs = $exchange->fetch_tickers();

            $pairs_map = array_map(function($item) {
                $simle_arr['symbol'] = $item['symbol'];
                $simle_arr['ask'] = $item['ask'];
                $simle_arr['bid'] = $item['bid'];
                return $simle_arr;
            }, $pairs);

//        include dirname(__DIR__) . "/Commands/data.php";
//        $pairs_map = $data_pairs;

        return self::getPair($pairs_map, $stock_id);
    }


    private static function getPair($pairs_map, $stock_id)
    {
        $bar = new ProgressBar(new ConsoleOutput(), count($pairs_map));
        $bar->setFormat('debug');
        $bar->setBarCharacter('<comment>=</comment>');
        $bar->setBarWidth(50);
        $bar->start();

        $triangles = [];

        foreach ($pairs_map as $pair) {
            list($base_curr, $quote_curr) = explode('/', $pair['symbol']);

            // якщо стартова валюта присутня в парі,
            if(in_array(self::START_CURRENT, [$base_curr, $quote_curr])) {

                // проверить блеклист

                $black_list = BlackList
                    ::where('symbol', $base_curr . '/' . $quote_curr)
                    ->where('stock_id', $stock_id)
                    ->get();
                if($black_list->count() > 0) continue;

                $first_pair = [
                    'base_curr'  => $base_curr,
                    'quote_curr' => $quote_curr,
                    'bid'        => $pair['bid'],
                    'ask'        => $pair['ask'],
                    'min_bid'    => 0,
                    'min_ask'    => 0
                ];

                // то друга - це валюта з якою торгуеться стартова
                $second_curr = (self::START_CURRENT == $base_curr) ? $quote_curr : $base_curr;

                // і шукаемо з якою валютою торгується друга крім self::self::START_CURRENT
                foreach ($pairs_map as $pair2) {
                    list($base_curr, $quote_curr) = explode('/', $pair2['symbol']);

                    // якщо друга валюта присутня в парі,
                    if(in_array($second_curr, [$base_curr, $quote_curr]) && ! in_array(self::START_CURRENT, [$base_curr, $quote_curr]) ) {

                        $black_list = BlackList
                            ::where('symbol', $base_curr . '/' . $quote_curr)
                            ->where('stock_id', $stock_id)
                            ->get();
                        if($black_list->count() > 0) continue;

                        $second_pair = [
                            'base_curr'  => $base_curr,
                            'quote_curr' => $quote_curr,
                            'bid'        => $pair2['bid'],
                            'ask'        => $pair2['ask'],
                            'min_bid'    => 0,
                            'min_ask'    => 0
                        ];

                        // то третя - це валюта з якою торгуеться друга
                        $third_curr = ($second_curr == $base_curr) ? $quote_curr : $base_curr;

                        // знаходимо третю пару
                        if(key_exists(self::START_CURRENT . '/' . $third_curr, $pairs_map )) {
                            $base_curr = self::START_CURRENT;
                            $quote_curr = $third_curr;
                        } else {
                            $quote_curr = self::START_CURRENT;
                            $base_curr = $third_curr;
                        }

                        $pair3 = collect($pairs_map)->where('symbol', $base_curr.'/'.$quote_curr)->first();

                        $third_pair = [
                            'base_curr'  => $base_curr,
                            'quote_curr' => $quote_curr,
                            'bid'        => $pair3['bid'],
                            'ask'        => $pair3['ask'],
                            'min_bid'    => 0,
                            'min_ask'    => 0
                        ];


                        $triangles[] = [
                            'symbol' => self::START_CURRENT.'->'.$second_curr.'->'.$third_curr.'->'.self::START_CURRENT,
                            'pairs'  => [$first_pair, $second_pair, $third_pair]
                        ];

                    }

                }

            }

            $bar->advance();
        }

        echo PHP_EOL;

        $triangles = collect($triangles)->unique('symbol')->toArray();

        return $triangles;
    }

}