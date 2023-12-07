<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Gate;
use App\Models\Part;
use App\Models\StockFg;
use App\Models\StatusOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StockFgController extends Controller
{
    public function index(Request $request)
    {
        if (request()->ajax()) {

            $query = StockFg::query();

            if ($request->part != 'ALL') {
                $query->where('part_id', $request->part);
            } else {
                $query->where(function ($query) {
                    $query->whereNotNull('part_id')->orWhereNull('part_id');
                });
            }

            if ($request->gate == 'ALL') {
                $query->where(function ($query) {
                    $query->whereNotNull('gate_id')->orWhereNull('gate_id');
                });
            } elseif ($request->gate == 'NULL') {
                $query->whereNull('gate_id');
            } else {
                $query->where('gate_id', $request->gate);
            }

            $d = $query->get();

            return DataTables::of($d)
            ->editColumn('part_name', function ($d) {
                return $d->part->part_name;
            })
            ->editColumn('ket_in', function ($d) {
                if($d->ket_in == '1') {
                    return 'REG';
                }else{
                    return 'EXP';
                }
            })
            ->editColumn('status_out_id', function ($d) {
                return $d->status_out_id ? $d->statusout->name : '';
            })
            ->editColumn('date_out', function ($d) {
                return $d->date_out ? Carbon::parse($d->date_out)->format('d-m-Y H:i:s') : '';
            })
            ->editColumn('gate_id', function ($d) {
                return $d->gate_id ? $d->gate->name : '';
            })
            ->make();
        }
        $part = Part::all();
        $statusout = StatusOut::all();
        $gate = Gate::all();
        return view('layouts.pages.stockfg.index', compact('part', 'statusout', 'gate'));
    }

    public function scanin()
    {
        $status = StatusOut::all();
        $gate = Gate::all();
        return view('layouts.pages.stockfg.scanin', compact('status', 'gate'));
    }

    public function scanout()
    {
        $status = StatusOut::all();
        $gate = Gate::all();
        return view('layouts.pages.stockfg.scanout', compact('status', 'gate'));
    }
}
