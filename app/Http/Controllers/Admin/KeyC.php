<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Config;
use App\Models\Key;
use App\Models\Stock;
use Illuminate\Http\Request;

class KeyC extends Controller
{

    protected $validateRules = [
        'stock_id'  => 'required',
        'key'       => 'required',
        'secret'    => 'required'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $keys = Key::with('stock')
            ->where('user_id', \Auth::id())
            ->get();

        return view('admin.key.index', ['keys' => $keys]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $stocks = Stock::all()->pluck('name', 'id');
        return view('admin.key.create', compact('stocks'));
    }

    /**
     * @param Key $model
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Key $model, Request $request)
    {
        $this->validate($request, $this->validateRules);

        $request->request->add(['user_id' => \Auth::id()]);

        $model->create($request->all());

        return redirect()->route('admin.key.index')->with('status', 'Успешно создано!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $item = Key::find($id);
        $stocks = Stock::all()->pluck('name', 'id');

        return view('admin.key.edit', compact('item', 'stocks'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request)
    {
        $page = Key::findOrFail($id);

        $this->validate($request, $this->validateRules);

        $request->request->add(['user_id', \Auth::id()]);

        $page->fill($request->all())->save();

        return redirect()->back()->with('status', 'Успешно обновлено!');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Key::findOrFail($id)->delete();

        return redirect()->route('admin.key.index')->with('status', 'Успешно удалено!');
    }



}
