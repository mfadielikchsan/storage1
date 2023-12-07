<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Part;
use App\Imports\PartImport;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class PartController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $d = Part::all();
            return DataTables::of($d)
            ->editColumn('updated_at', function ($d) {
                return Carbon::parse($d->updated_at)->format('d-m-Y');
            })
            ->editColumn('customer', function ($d) {
                return $d->customer->name;
            })
            ->editColumn('action', function ($d){
                $d = json_encode($d);
                return "<button class='btn btn-success' onclick='edit($d)'><i class='fas fa-edit'></i></button> <button class='btn btn-danger' onclick='deleteData($d)'><i class='fas fa-trash'></i></button>";
            })
            ->make();
        }
        $customer = Customer::all();
        return view('layouts.pages.part.index', compact('customer'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = [
                'part_number' => $request->part_number,
                'part_name' => $request->part_name,
                'customer_id' => $request->customer,
            ];
            
            if ($request->primarykey) {
                $part = Part::find($request->primarykey);
                $part->update($data);
                $msg = 'Edit Part Berhasil';
            } else {
                $data['created_by'] = Auth::user()->name;
                $part = Part::create($data);
                $msg = 'Tambah Part Berhasil';
            }
            DB::commit();
            return response()->json(['head' => 'Berhasil', 'msg' => $msg, 'status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return response()->json(['head' => 'Gagal', 'msg' => 'Error ' . $e->getMessage(), 'status' => 'error']);
        }
    }


    public function delete($id)
    {
        $id = base64_decode($id);
        DB::beginTransaction();
        try {
            $data = Part::find($id);
            $data->delete();
            DB::commit();
            return response()->json(['head' => 'Berhasil', 'msg' => 'Hapus Berhasil', 'status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return response()->json(['head' => 'Gagal', 'msg' => 'Error ' . $e->getMessage(), 'status' => 'error']);
        }
    }

    public function downloadTemplate()
    {
        $filePath = public_path('report/template-upload-part.xlsx');
        return response()->download($filePath, 'part_template.xlsx');
    }

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls',
            ]);
            $file = $request->file('file');
            Excel::import(new PartImport(), $file);
            return response()->json(['head' => 'Berhasil', 'msg' => 'Upload Part Berhasil', 'status' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['head' => 'Gagal', 'msg' => 'Error ' . $e->getMessage(), 'status' => 'error']);
        }
    }

}
