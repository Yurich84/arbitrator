<?php

namespace App\Http\Controllers\Front;

use App\Arbitrator\InterCalculate;
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

        // время последнего обновления
        $last_up = Update::latest('id')
            ->where('time', '<', Carbon::now()->subMinutes(3))
            ->first();

        if (! $request->isMethod('post') && \Auth::check() && ! is_null(\Auth::user()->filter_pref)) {
            // если сохранили настройки то подгружаем
            $request->request->add(unserialize(\Auth::user()->filter_pref));
        }

        $req = [];

        /**
         * Поисковые параметры
         */
        $profit_slider = $req['profit_slider'] = $request->get('profit_slider', '1;50');
        list($min_profit, $max_profit) = explode(';', $profit_slider);
        $crypto_curr_only = $req['crypto_curr_only'] = $request->get('crypto_curr_only', 0);
        $min_volume = $req['min_volume'] = $request->get('min_volume', 1);
        $save_filter = $req['save_filter'] = $request->get('save_filter', 0);
        $stock_ids = $req['stock_ids'] = $request->get('stock_ids', []);

        if($save_filter == 1 && $user = \Auth::user()) {
            // сохраняем настройки фильтра для пользователя
            $user->filter_pref = serialize($req);
            $user->save();
        }

        $query = InterPairs::with('stock', 'stock.country')
            ->where('up_id', $last_up->id)
            ->whereNotNull('symbol_id')
            ->where('last', '>=', 0)
            ->where('volume', '>', $min_volume);

        // список бирж участвубщих в поиске
        $stocks_query = clone $query;
        $stocks = $stocks_query->groupBy('stock_id')->get();
        if( empty($stock_ids)) {
            $stock_ids = $stocks->pluck('stock_id');
        }

        $query->whereIn('stock_id', $stock_ids);

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
                'cap'  => $val->first()->stock->volume_btc,
                'flag' => $val->first()->stock->country->flag,
            ];
        });

        // групировка по торговим парам
        $pairs_grouped = $pairs->groupBy('symbol_id');

        // порівнюємо кожну пару на різних біржах
        $res = InterCalculate::findProfitableLast($pairs_grouped);

        // фильтр по проценту профита
        $res = collect($res)
            ->where('percent', '>', $min_profit)
            ->where('percent', '<', $max_profit)
            ->sortByDesc('percent');

        // метатеги
        \View::share('meta',  [
                'title' => config('app.name') . ' - автоматизация заработака на межбиржевом арбитраже',
                'desc'  => config('app.name') . ' - система поиска внешнебиржевых арбитражных ситуаций, автоматизация заработака на межбиржевом арбитраже',
                'key'   => config('app.name') . ' - межбиржевой арбитраж, арбитражные вилки',
            ]
        );

        return view('front.inter.current', compact(
            'res', 'last_up', 'stocks', 'current_stocks', 'stock_ids', 'min_profit', 'max_profit', 'crypto_curr_only', 'min_volume', 'save_filter'
        ));

    }


    /**
     * Список текуших вилок
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function arbitrage(Request $request)
    {

        // время последнего обновления
        $last_up = Update::latest('id')
            ->where('time', '<', Carbon::now()->subMinutes(3))
            ->first();

        if (! $request->isMethod('post') && \Auth::check() && ! is_null(\Auth::user()->filter_pref)) {
            // если сохранили настройки то подгружаем
            $request->request->add(unserialize(\Auth::user()->filter_pref));
        }

        $req = [];

        /**
         * Поисковые параметры
         */
        $profit_slider = $req['profit_slider'] = $request->get('profit_slider', '1;50');
        list($min_profit, $max_profit) = explode(';', $profit_slider);
        $crypto_curr_only = $req['crypto_curr_only'] = $request->get('crypto_curr_only', 0);
        $min_volume = $req['min_volume'] = $request->get('min_volume', 1);
        $save_filter = $req['save_filter'] = $request->get('save_filter', 0);
        $stock_ids = $req['stock_ids'] = $request->get('stock_ids', []);

        if($save_filter == 1 && $user = \Auth::user()) {
            // сохраняем настройки фильтра для пользователя
            $user->filter_pref = serialize($req);
            $user->save();
        }

        $query = InterPairs::with('stock', 'stock.country')
            ->where('up_id', $last_up->id)
            ->whereNotNull('symbol_id')
            ->where('last', '>=', 0)
            ->where('volume', '>', $min_volume);

        // список бирж участвубщих в поиске
        $stocks_query = clone $query;
        $stocks = $stocks_query->groupBy('stock_id')->get();
        if( empty($stock_ids)) {
            $stock_ids = $stocks->pluck('stock_id');
        }

        $query->whereIn('stock_id', $stock_ids);

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
                'cap'  => $val->first()->stock->volume_btc,
                'flag' => $val->first()->stock->country->flag,
            ];
        });

        // групировка по торговим парам
        $pairs_grouped = $pairs->groupBy('symbol_id');

        // порівнюємо кожну пару на різних біржах
        $res = InterCalculate::findProfitable($pairs_grouped);

        // фильтр по проценту профита
        $res = collect($res)
            ->where('percent', '>', $min_profit)
            ->where('percent', '<', $max_profit)
            ->sortByDesc('percent');

        // метатеги
        \View::share('meta',  [
                'title' => config('app.name') . ' - автоматизация заработака на межбиржевом арбитраже',
                'desc'  => config('app.name') . ' - система поиска внешнебиржевых арбитражных ситуаций, автоматизация заработака на межбиржевом арбитраже',
                'key'   => config('app.name') . ' - межбиржевой арбитраж, арбитражные вилки',
            ]
        );

        return view('front.inter.arbitrage', compact(
            'res', 'last_up', 'stocks', 'current_stocks', 'stock_ids', 'min_profit', 'max_profit', 'crypto_curr_only', 'min_volume', 'save_filter'
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

        return view('front.inter.table', compact('pair', 'last_up', 'stocks'));
    }

    /**
     * Динамика изменения цена на биржах
     * @param $pair
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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