<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockC extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $stocks = Stock::orderByDesc('favorite')->oldest('id')->get();
        return view('admin.stock.index', ['stocks' => $stocks]);
    }


    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $item = Stock::findOrFail($id);
        return view('admin.stock.edit', compact('item'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request)
    {
        $model = Stock::findOrFail($id);

//        $this->validate($request, [
//            'name' => 'required|max:255',
//            'url'  => 'required|not_in:info-, |max:255'
//        ]);

        $model->fill($request->all())->save();

        return redirect()->route('admin.stock.index')->with('status', 'Успешно обновлено!');
    }


    public function active($id)
    {
        $stock = Stock::find($id);
        $stock->inter_active = abs($stock->inter_active - 1);
        $stock->save();
    }

}
