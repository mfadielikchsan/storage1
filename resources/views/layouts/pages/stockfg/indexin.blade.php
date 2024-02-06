@extends('layouts.app')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">In Finish Good</h1>

    @if (session('success'))
        <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('status'))
        <div class="alert alert-success border-left-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <!-- Main Content goes here -->
    <div class="row">
        <div class="col-md-2">
            <a href="{{ route('stock-fg.scanin') }}"><button type="button" class="btn btn-success" style="width: 90%"><i class="fa fa-plus-circle"></i> Scan In</button></a>
        </div>
    </div>
    <div class="col-md-12 row mt-3">
        <div class="col-md-3">
            <label for="part">Part Name</label>
            <select class="form-control select2" id="part" style="width:100%">
                <option value="ALL" selected>ALL</option>
                @foreach ($part as $p)
                    <option value="{{ $p->id }}">{{ $p->part_name }}</option>
                @endforeach
            </select>   
        </div>
        <div class="col-md-2">
            <label for="ket">Keterangan</label>
            <select class="form-control select2" id="ket" style="width:100%">
                <option value="ALL" selected>ALL</option>
                <option value="1">REG</option>
                <option value="2">EXP</option>
            </select>   
        </div>
    </div>
    <div class="col-md-12 table-responsive mt-4  row">
        <table class="table table-striped" id="tblMaster" style="min-width:1350px;">
            <thead>
                <tr>
                    <th class="text-center" style="width: 15%">Part Number</th>
                    <th class="text-center" style="width: 20%">Part Name</th>
                    <th class="text-center">Customer</th>
                    <th class="text-center">Surat Jalan</th>
                    <th class="text-center">Lot Number</th>
                    <th class="text-center" style="width: 8%">Quantity</th>
                    <th class="text-center">Keterangan</th>
                </tr>
            </thead>
        </table>   
    </div>   
@endsection

@push('js')
<script>

    let tableMaster = $('#tblMaster')
    .on('preXhr.dt', function(e, settings, data) {
        data.part = $('#part').val()
        data.ket = $('#ket').val()
    }).DataTable({
        ajax: {
            url: '{!! url()->current() !!}'
        },
        "order": [],
        "iDisplayLength": 25,
        responsive: false,
        "scrollX": true,
        "scrollY": "410px",
        "scrollCollapse": true,
        processing: true,
        serverSide: true,
        destroy: true,
        searching: true,
        "oLanguage": {
            'sProcessing': '<div id="processing" style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(102, 102, 102); z-index: 30001; opacity: 0.8;"><p style="position: absolute; color: White; top: 50%; left: 45%;"></p></div>Processing...'
        },
        fixedColumns: {
            left: 2
        },
        columns: 
        [{
            data: 'part_number', 
            name: 'part_number',
        }, 
        {
            data: 'part_name', 
            name: 'part_name',
        }, 
        {
            data: 'customer', 
            name: 'customer',
            class: 'dt-center',
        }, 
        {
            data: 'no_sj', 
            name: 'no_sj',
        }, 
        {
            data: 'lot_number', 
            name: 'lot_number',
        }, 
        {
            data: 'quantity', 
            name: 'quantity',
            class: 'dt-center',
        }, 
        {
            data: 'ket_in', 
            name: 'ket_in',
            class: 'dt-center',
        }],
        "initComplete": function() {
            $('#part, #ket').change(function() {
                tableMaster.ajax.url("{!! url()->current() !!}").load();
            });
        }
    });
</script>
@endpush
