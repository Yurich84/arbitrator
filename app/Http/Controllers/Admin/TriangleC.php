<?php

namespace App\Http\Controllers\Admin;

use App\Arbitrator\TriangleCalculate;
use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\TriangleFork;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use \DB;

class TriangleC extends Controller
{

    /**
     * Виводим последние спарсение
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function current()
    {
        $now = Carbon::now();

        $active_stocks = Stock::where('trio_active', 1)->get();

        $last_update_stocks = TriangleFork
            ::select(['stock_id', DB::raw('MAX(created_at) as last_time')])
            ->whereIn('stock_id', $active_stocks->pluck('id'))
            ->groupBy('stock_id')
            ->get();

        $tri = TriangleFork::with('stock')->latest();

        foreach ($last_update_stocks as $last) {
            $tri->orWhere(function ($query) use($last) {
                $query->where('stock_id', $last->stock_id)
                    ->where('created_at', '>', Carbon::parse($last->last_time)->subMinutes(2));
            });
        }

        $triangles = $tri->get()->unique('symbol')->map(function ($item) use($now) {

            $minutes = Carbon::parse($item->created_at)->diffInMinutes($now);

            if($minutes < 1) {
                $color = 'green';
            } elseif ( $minutes < 5 ) {
                $color = 'lightgreen';
            } elseif ( $minutes < 10 ) {
                $color = 'yellow';
            } elseif ( $minutes < 30 ) {
                $color = 'orange';
            } elseif ( $minutes < 60 ) {
                $color = 'pink';
            } else {
                $color = 'red';
            }

            $item->minutes = $minutes;
            $item->color = $color;
            return $item;
        });

        return view('admin.triangle.current', compact('triangles'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function logs()
    {
        $stocks = TriangleFork::with('stock')->groupBy('stock_id')->get();
        return view('admin.triangle.logs', compact('stocks'));
    }


    public function show($id)
    {
        $data = TriangleFork::find($id);
        return view('admin.triangle.show', compact('data'));
    }


    public function logsData(Request $request)
    {
        $triangles = TriangleFork::with('stock');

        if ($trio = $request->get('trio')) {
            $triangles->where('symbol', 'like', "$trio");
        }

        $triangles->get();

        return Datatables::of($triangles)
            ->toJson();
    }


    public function getTrioData($id)
    {
        $exchange_namespace = '\\ccxt\\';

        $log = TriangleFork::find($id);

        $default_fee = 0.002;
        $fee = $log->stock->fee ?: $default_fee;
        $tax = pow((1-$fee), 3);

        $exchange = $exchange_namespace . $log->stock->ccxt_id;
        $exchange = new $exchange();

        // делаем запрос и поллучаем массив

        $pairs = [];
        foreach (json_decode($log->pairs) as $symbol) {

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

        $calculate = new TriangleCalculate($log->symbol, $pairs, $tax);

        $log->pairs      = json_encode($pairs);
        $log->profit     = $calculate->profit;
        $log->min        = $calculate->min;
        $log->comment    = $calculate->comment;
        $log->error      = $calculate->error;
        $log->save();

        return $log;

    }

    public function showTrioData($id)
    {
        return TriangleFork::find($id);
    }

}
