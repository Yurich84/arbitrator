<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\FiatCurency;
use App\Models\InterPairs;
use App\Models\Update;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InterC extends Controller
{

    /**
     * Список текуших вилок
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function current(Request $request)
    {

        $min_profit = preg_replace('/[^0-9.]/', '', $request->get('sliderleft', 1));
        $max_profit = preg_replace('/[^0-9.]/', '', $request->get('sliderright', 50));
        $crypto_curr_only = $request->get('crypto_curr_only', 1);
        $min_volume = $request->get('min_volume', 1);
        $stock_ids = [];

        $last_up = Update::latest('id')->first();

        $query = InterPairs::with('stock', 'stock.country')
            ->where('up_id', $last_up->id)
            ->whereNotNull('symbol_id')
            ->where('last', '>=', 0)
            ->where('volume', '>', $min_volume);

        $pairs = $query->oldest('symbol')->get();

        // без фиатних валют
        if($crypto_curr_only) {
            $fiat = FiatCurency::all()->pluck('id')->toArray();
            $fiat[] = 'USDT';
            $fiat[] = 'TUSD';

            $pairs = $pairs->reject(function ($item) use ($fiat) {
                list($base, $quote) = explode('/', $item->symbol);
                return in_array($base, $fiat) || in_array($quote, $fiat);
            });
        }

        // Список бирж
        $current_stocks = $pairs->groupBy('stock_id')->map(function ($val) {
            return $item = (object) [
                'name' => $val->first()->stock->name,
                'logo' => $val->first()->stock->logo,
            ];
        });

        // групировка по торговим парам
        $pairs_grouped = $pairs->groupBy('symbol_id');

        // порівнюємо кожну пару на різних біржах
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

        // фильтр по проценту профита
        $res = collect($res)
            ->where('percent', '>', $min_profit)
            ->where('percent', '<', $max_profit)
            ->sortByDesc('percent');

        return view('front.inter.current', compact(
            'res', 'last_up', 'current_stocks', 'min_profit', 'max_profit', 'crypto_curr_only', 'min_volume'
        ));

    }


    /**
     * Таблица профитности по конкретной паре
     * @param $up_id
     * @param $pair
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($up_id, $pair)
    {
        $last_up = Update::find($up_id);

        // Список бирж
        $stocks = InterPairs::with('stock')
            ->where('symbol', $pair)
            ->where('last', '>', 0)
            ->where('volume', '>', 1)
            ->where('up_id', $up_id)
            ->get();

        $stocks->map(function ($item) {
            list($base, $quote) = explode('/', $item->symbol);
            $stock_url = $item->stock->trade_url;
            $stock_url = str_replace('aa', mb_strtolower($base), $stock_url);
            $stock_url = str_replace('bb', mb_strtolower($quote), $stock_url);
            $stock_url = str_replace('AA', mb_strtoupper($base), $stock_url);
            $stock_url = str_replace('BB', mb_strtoupper($quote), $stock_url);

            $item->stock_url = $stock_url;
        });

        return view('front.inter.show', compact('pair', 'last_up', 'stocks'));
    }

    public function history($pair)
    {
        // Список бирж
        $inter_pair = InterPairs::with('stock')
            ->join('inter_updates as up', 'inter_pairs.up_id', '=', 'up.id')
            ->where('symbol', $pair)
            ->where('last', '>', 0)
            ->where('volume', '>', 1)
            ->get();

        $stocks = $inter_pair->groupBy('stock_id');

        $labels = Update::groupBy(\DB::raw('DATE(time)'))->get()->pluck('time');

        $datasets = [];
        foreach ($stocks as $ups) {
            $color = mt_rand(0, 255) . ',' . mt_rand(0, 255) . ',' . mt_rand(0, 255);

            $ups0 = $ups->groupBy(function($item) {
                return Carbon::parse($item->time)->format('Y-m-d');
            });

            $data = [];
            foreach ($ups0 as $st) {
                $data[] = $st->first()->last;
            }


            $datasets[] = (object) [
                'label' => $ups->first()->stock->name,
                'backgroundColor' => 'rgba(255, 255, 255, 0)',
                'borderColor' => 'rgba(' . $color . ',1)',
                'tension' => '0.4',
                'radius' => '2',
                'data' => $data,
            ];
        }

        return view('front.inter.history', compact('pair', 'datasets', 'labels'));
    }

}