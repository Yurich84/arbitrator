<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use \DB;

class DashboardC extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        return view('admin.dashboard.index', compact('earnings', 'orders'));
    }

    public function dostavka()
    {
        return view('admin.dashboard.dostavka');
    }

    public function dostavka_edit(Request $request)
    {
        $this->validate($request, [
            'text' => 'required'
        ]);

        \DB::table('configs')
            ->where('name', 'dostavka')
            ->update(['value' => $request->get('text')]);

        return redirect()->route('admin.dostavka.index');
    }

    public function dostavka_reset()
    {
        \DB::table('configs')
            ->where('name', 'dostavka')
            ->update(['value' => NULL]);

        return redirect()->route('admin.dostavka.index');
    }

}
