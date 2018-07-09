<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InterPairs;
use App\Models\Update;

class InterC extends Controller
{

    public function current()
    {

        $last_up_id = Update::max('id');

        $pairs = InterPairs::with('stock')
            ->where('up_id', $last_up_id)
            ->whereNotNull('symbol_id')
            ->where('price', '>', 0)
            ->where('volume', '>', 10)
            ->oldest('symbol')
            ->get();

        $pairs_grouped = $pairs->groupBy('symbol_id');

        // порівнюємо кожну пару на різних біржах
        $res = [];
        foreach ($pairs_grouped as $pair_group) {
            if(count($pair_group) > 3) {

                $pair_min_price = $pair_group->where('price', $pair_group->min('price'))->first();
                $pair_max_price = $pair_group->where('price', $pair_group->max('price'))->first();

                //план тут надо у минимального находить цену по которой предлагают а у максимального по которой покупают

                $percent = round(($pair_max_price->price - $pair_min_price->price)/$pair_max_price->price*100, 4); // %

                $res[] = (object) [
                    'symbol' => $pair_group->first()->symbol,
                    'stock_min' => $pair_min_price->stock->name,
                    'stock_min_url' => $pair_min_price->stock->www,
                    'stock_min_price' => $pair_min_price->price,
                    'stock_min_volume' => $pair_min_price->volume,
                    'stock_max' => $pair_max_price->stock->name,
                    'stock_max_url' => $pair_max_price->stock->www,
                    'stock_max_price' => $pair_max_price->price,
                    'stock_max_volume' => $pair_max_price->volume,
                    'percent' => $percent
                ];

            } else {
                continue;
            }
        }

        return view('admin.inter.current', compact('res'));

    }

}