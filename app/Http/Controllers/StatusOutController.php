<?php

namespace App\Http\Controllers;

use App\Models\StatusOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StatusOutController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $d = StatusOut::all();
            return DataTables::of($d)
            ->editColumn('action', function ($d){
                $d = json_encode($d);
                return "<button class='btn btn-success' onclick='edit($d)'><i class='fas fa-edit'></i></button>";
            })
            ->make();
        }
        return view('layouts.pages.statusout.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
                $data = [
                    'name' => $request->status,
                ];
                
                if ($request->primarykey) {
                    $status = StatusOut::find($request->primarykey);
                    $status->update($data);
                    $msg = 'Edit Status Out Berhasil';
                } else {
                    $status = StatusOut::create($data);
                    $msg = 'Tambah Status Out Berhasil';
                }
                DB::commit();
            DB::commit();
            return response()->json(['head' => 'Berhasil', 'msg' => $msg, 'status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['head' => 'Gagal', 'msg' => 'Error ' . $e->getMessage(), 'status' => 'error']);
        }
    }

    public function show(StatusOut $statusOut)
    {
        //
    }

    public function edit(StatusOut $statusOut)
    {
        //
    }

    public function update(Request $request, StatusOut $statusOut)
    {
        //
    }

    public function destroy(StatusOut $statusOut)
    {
        //
    }
}
