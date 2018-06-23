<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\TriangleFork;
use Carbon\Carbon;
use Illuminate\Http\Request;
use \DB;
use Yajra\Datatables\Datatables;

class TriangleC extends Controller
{

    public function current()
    {
        $triangles = TriangleFork::with('exchange')
            ->where('created_at', '>', Carbon::now()->subMinutes(5))
            ->get();

        return view('admin.triangle.current', compact('triangles'));
    }

    public function logs()
    {
        return view('admin.triangle.logs');
    }

    public function logsData()
    {
        $triangles = TriangleFork::with('exchange')->get();
        return Datatables::of($triangles)
            ->toJson();
    }

}
