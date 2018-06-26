<?php

namespace App\Http\Controllers\Admin;

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

        $active_stocks = Stock::latest('name')->get();

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

        $triangles = $tri->get()->map(function ($item) use($now) {

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
        })
        ;

        return view('admin.triangle.current', compact('triangles'));
    }

    public function logs()
    {
        $stocks = TriangleFork::with('stock')->groupBy('stock_id')->get();
        return view('admin.triangle.logs', compact('stocks'));
    }

    public function logsData(Request $request)
    {
        $triangles = TriangleFork::with('stock');

        if ($trio = $request->get('trio')) {
            $triangles->where('symbol', 'like', "$trio"); // additional users.name search
        }

        $triangles->get();

        return Datatables::of($triangles)
            ->toJson();
    }

}
