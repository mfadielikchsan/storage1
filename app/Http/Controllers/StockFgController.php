<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Gate;
use App\Models\Part;
use App\Models\StockFg;
use App\Models\StatusOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class StockFgController extends Controller
{
    public function indexin(Request $request)
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

            if ($request->ket != 'ALL') {
                $query->where('ket_in', $request->ket);
            } else {
                $query->where(function ($query) {
                    $query->whereNotNull('ket_in')->orWhereNull('ket_in');
                });
            }

            $query->where(function ($query) {
                $query->whereNull('date_out');
            });            

            $d = $query->get();

            return DataTables::of($d)
            ->editColumn('part_name', function ($d) {
                return $d->part->part_name;
            })
            ->editColumn('customer', function ($d) {
                return $d->part->customer->name;
            })
            ->editColumn('ket_in', function ($d) {
                if($d->ket_in == '1') {
                    return 'REG';
                }else{
                    return 'EXP';
                }
            })
            ->make();
        }
        $part = Part::all();
        return view('layouts.pages.stockfg.indexin', compact('part'));
    }

    public function indexoutnon(Request $request)
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

            if ($request->statusout != 'ALL') {
                $query->where('status_out_id', $request->statusout);
            } else {
                $query->where(function ($query) {
                    $query->whereNotNull('status_out_id')->orWhereNull('status_out_id');
                });
            }

            if ($request->ket != 'ALL') {
                $query->where('ket_in', $request->ket);
            } else {
                $query->where(function ($query) {
                    $query->whereNotNull('ket_in')->orWhereNull('ket_in');
                });
            }

            $query->where(function ($query) {
                $query->whereNotNull('date_out');
                $query->whereNotNull('status_out_id');
            });

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
                return $d->StatusOut->name;
            })
            ->editColumn('date_out', function ($d) {
                return Carbon::parse($d->date_out)->format('d-m-Y');
            })
            ->make();
        }
        $part = Part::all();
        $statusout = StatusOut::all();
        return view('layouts.pages.stockfg.indexoutnon', compact('part', 'statusout'));
    }

    public function indexoutdeliv(Request $request)
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

            if ($request->ket != 'ALL') {
                $query->where('ket_in', $request->ket);
            } else {
                $query->where(function ($query) {
                    $query->whereNotNull('ket_in')->orWhereNull('ket_in');
                });
            }

            if ($request->gate != 'ALL') {
                $query->where('gate_id', $request->gate);
            } else {
                $query->where(function ($query) {
                    $query->whereNotNull('gate_id')->orWhereNull('gate_id');
                });
            }

            $query->where(function ($query) {
                $query->whereNotNull('date_out');
                $query->whereNotNull('gate_id');
            });

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
            ->editColumn('date_out', function ($d) {
                return Carbon::parse($d->date_out)->format('d-m-Y');
            })
            ->editColumn('gate_id', function ($d) {
                return $d->gate->name;
            })
            ->make();
        }
        $part = Part::all();
        $gate = Gate::all();
        return view('layouts.pages.stockfg.indexoutdeliv', compact('part', 'gate'));
    }

    public function scanin()
    {
        return view('layouts.pages.stockfg.scanin');
    }

    public function scanoutnon()
    {
        $status = StatusOut::all();
        $gate = Gate::all();
        return view('layouts.pages.stockfg.scanoutnon', compact('status'));
    }

    public function scanoutdeliv()
    {
        $status = StatusOut::all();
        $gate = Gate::all();
        return view('layouts.pages.stockfg.scanoutdeliv', compact('gate'));
    }

    public function recapt()
    {
        if (request()->ajax()) {
            $d = Part::select('parts.*')
            ->selectSub(function ($query) {
                $query->selectRaw('sum(quantity)')
                    ->from('stock_fgs')
                    ->whereColumn('part_id', 'parts.id')
                    ->whereNull('date_out');
            }, 'total_stock')
            ->get();
            
            return DataTables::of($d)
            ->editColumn('customer', function ($d) {
                return $d->customer->name;
            })
            ->make();
        }
        $part = Part::all();
        return view('layouts.pages.stockfg.recapt', compact('part'));
    }

    public function getpart($partno)
    {
        $part = Part::where('part_number', $partno)->first();
        return response()->json(['data' => $part]);
    }

    public function getpartOut(Request $request)
    {
        $StockFg = StockFg::where('part_number', $request->nopart)
                ->where('lot_number', $request->nolot)
                ->where('quantity', $request->qty)
                ->where('ket_in', $request->ket)
                ->where('date_out', null)
                ->first();
        return response()->json(['data' => $StockFg]);
    }

    public function storeScanin(Request $request)
    {
        DB::beginTransaction();
        try {
                $jumlah = $request->jumlah;

                $data = [
                    'part_id' => $request->part_id,
                    'no_sj' => $request->nosuratjalan,
                    'part_number' => $request->partno,
                    'lot_number' => $request->nomorlot,
                    'quantity' => $request->qty,
                    'ket_in' => $request->keterangan,
                    'created_by' => Auth::user()->name,
                    'created_at' => date("Y-m-d H:i:s"),
                ];
                
                for($i=1; $i <= $jumlah; $i++) {
                    $StockFg = StockFg::create($data);
                }
                $msg = 'Input Scan In Berhasil';
                DB::commit();
            DB::commit();
            return response()->json(['head' => 'Berhasil', 'msg' => $msg, 'status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['head' => 'Gagal', 'msg' => 'Error ' . $e->getMessage(), 'status' => 'error']);
        }
    }

    public function storeScanoutNon(Request $request)
    {
        DB::beginTransaction();
        try {
                $stockFg = StockFg::where('id', $request->primarykey)->first();
                $stockFg->no_sj_out = $request->nosuratjalan;
                $stockFg->status_out_id = $request->statusout;
                $stockFg->date_out = date("Y-m-d H:i:s");
                $stockFg->save();
                $msg = 'Scan Out Non Delivery Berhasil';
                DB::commit();
            DB::commit();
            return response()->json(['head' => 'Berhasil', 'msg' => $msg, 'status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['head' => 'Gagal', 'msg' => 'Error ' . $e->getMessage(), 'status' => 'error']);
        }
    }

    public function storeScanoutDeliv(Request $request)
    {
        DB::beginTransaction();
        try {
                $stockFg = StockFg::where('id', $request->primarykey)->first();
                $stockFg->no_sj_out = $request->nosuratjalan;
                $stockFg->gate_id = $request->gateout;
                $stockFg->date_out = date("Y-m-d H:i:s");
                $stockFg->save();
                $msg = 'Scan Out Delivery Berhasil';
                DB::commit();
            DB::commit();
            return response()->json(['head' => 'Berhasil', 'msg' => $msg, 'status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['head' => 'Gagal', 'msg' => 'Error ' . $e->getMessage(), 'status' => 'error']);
        }
    }
}
