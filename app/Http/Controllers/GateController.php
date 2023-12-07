<?php

namespace App\Http\Controllers;

use App\Models\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class GateController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $d = Gate::all();
            return DataTables::of($d)
            ->editColumn('action', function ($d){
                $d = json_encode($d);
                return "<button class='btn btn-success' onclick='edit($d)'><i class='fas fa-edit'></i></button>";
            })
            ->make();
        }
        return view('layouts.pages.gate.index');
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
                    'name' => $request->gate,
                ];
                
                if ($request->primarykey) {
                    $gate = Gate::find($request->primarykey);
                    $gate->update($data);
                    $msg = 'Edit Gate Berhasil';
                } else {
                    $gate = Gate::create($data);
                    $msg = 'Tambah Gate Berhasil';
                }
                DB::commit();
            DB::commit();
            return response()->json(['head' => 'Berhasil', 'msg' => $msg, 'status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['head' => 'Gagal', 'msg' => 'Error ' . $e->getMessage(), 'status' => 'error']);
        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
