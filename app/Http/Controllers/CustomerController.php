<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $d = Customer::all();
            return DataTables::of($d)
            ->editColumn('action', function ($d){
                $d = json_encode($d);
                return "<button class='btn btn-success' onclick='edit($d)'><i class='fas fa-edit'></i></button>";
            })
            ->make();
        }
        return view('layouts.pages.customer.index');
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
                $data = [
                    'name' => $request->customer,
                ];
                
                if ($request->primarykey) {
                    $customer = Customer::find($request->primarykey);
                    $customer->update($data);
                    $msg = 'Edit Customer Berhasil';
                } else {
                    $customer = Customer::create($data);
                    $msg = 'Tambah Customer Berhasil';
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
        
    }

    public function edit(string $id)
    {
        
    }

    public function update(Request $request, string $id)
    {
        
    }


    public function destroy(string $id)
    {
        $id = base64_decode($id);
        DB::beginTransaction();
        try {
            $data = Customer::find($id);
            $data->delete();
            DB::commit();
            return response()->json(['head' => 'Berhasil', 'msg' => 'Hapus Berhasil', 'status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['head' => 'Gagal', 'msg' => 'Error ' . $e->getMessage(), 'status' => 'error']);
        }
    }
}
