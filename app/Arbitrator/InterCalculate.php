<?php

namespace App\Arbitrator;

class InterCalculate
{

    public static function findProfitable($pairs_grouped)
    {
        $res = [];
        foreach ($pairs_grouped as $pair_group) {
            if(count($pair_group) > 2) {

                $pair_group->map(function ($item) {
                    list($base, $quote) = explode('/', $item->symbol);
                    $stock_url = $item->stock->trade_url;
                    $stock_url = str_replace('aa', mb_strtolower($base), $stock_url);
                    $stock_url = str_replace('bb', mb_strtolower($quote), $stock_url);
                    $stock_url = str_replace('AA', mb_strtoupper($base), $stock_url);
                    $stock_url = str_replace('BB', mb_strtoupper($quote), $stock_url);

                    $item->stock_url = $stock_url;
                });

                $pair_min = $pair_group->where('ask', $pair_group->min('ask'))->first();
                $pair_max = $pair_group->where('bid', $pair_group->max('bid'))->first();

                // у минимального находить цену по которой предлагают а у максимального по которой покупают
                $percent = round(($pair_max->bid - $pair_min->ask)/$pair_max->bid*100, 4); // %

                $res[] = (object) [
                    'symbol' => $pair_group->first()->symbol,
                    'stock_min' => $pair_min->stock,
                    'stock_min_url' => $pair_min->stock_url,
                    'stock_min_ask' => $pair_min->ask,
                    'stock_min_volume' => $pair_min->volume,
                    'stock_max' => $pair_max->stock,
                    'stock_max_url' => $pair_max->stock_url,
                    'stock_max_bid' => $pair_max->bid,
                    'stock_max_volume' => $pair_max->volume,
                    'comparision' => $pair_group,
                    'percent' => $percent
                ];

            } else {
                continue;
            }
        }

        return $res;
    }


    public static function findProfitableLast($pairs_grouped)
    {
        $res = [];
        foreach ($pairs_grouped as $pair_group) {
            if(count($pair_group) > 2) {

                $pair_group->map(function ($item) {
                    list($base, $quote) = explode('/', $item->symbol);
                    $stock_url = $item->stock->trade_url;
                    $stock_url = str_replace('aa', mb_strtolower($base), $stock_url);
                    $stock_url = str_replace('bb', mb_strtolower($quote), $stock_url);
                    $stock_url = str_replace('AA', mb_strtoupper($base), $stock_url);
                    $stock_url = str_replace('BB', mb_strtoupper($quote), $stock_url);

                    $item->stock_url = $stock_url;
                });

                $pair_min_price = $pair_group->where('last', $pair_group->min('last'))->first();
                $pair_max_price = $pair_group->where('last', $pair_group->max('last'))->first();

                //план тут надо у минимального находить цену по которой предлагают а у максимального по которой покупают

                $percent = round(($pair_max_price->last - $pair_min_price->last)/$pair_max_price->last*100, 4); // %

                $res[] = (object) [
                    'symbol' => $pair_group->first()->symbol,
                    'stock_min' => $pair_min_price->stock,
                    'stock_min_url' => $pair_min_price->stock_url,
                    'stock_min_price' => $pair_min_price->last,
                    'stock_min_volume' => $pair_min_price->volume,
                    'stock_max' => $pair_max_price->stock,
                    'stock_max_url' => $pair_max_price->stock_url,
                    'stock_max_price' => $pair_max_price->last,
                    'stock_max_volume' => $pair_max_price->volume,
                    'comparision' => $pair_group,
                    'percent' => $percent
                ];

            } else {
                continue;
            }
        }

        return $res;
    }

}