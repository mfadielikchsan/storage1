@extends('layouts.app')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Stock Finish Good</h1>

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
        <div class="col-md-2">
            <a href="{{ route('stock-fg.scanout') }}"><button type="button" class="btn btn-danger" style="width: 90%"> <i class="fa fa-arrow-circle-right"></i> Scan Out</button></a>
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
        <div class="col-md-3">
            <label for="statusout">Status Out</label>
            <select class="form-control select2" id="statusout" style="width:100%">
                <option value="ALL" selected>ALL</option>
                <option value="NULL">NULL</option>
                @foreach ($statusout as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>   
        </div>
        <div class="col-md-2">
            <label for="gate">Gate</label>
            <select class="form-control select2" id="gate" style="width:100%">
                <option value="ALL" selected>ALL</option>
                <option value="NULL">NULL</option>
                @foreach ($gate as $g)
                    <option value="{{ $g->id }}">{{ $g->name }}</option>
                @endforeach
            </select>   
        </div>
    </div>
    <div class="col-md-12 table-responsive mt-4  row">
        <table class="table table-striped" id="tblMaster" style="min-width:1550px;">
            <thead>
                <tr>
                    <th class="text-center" style="width: 12%">Part Number</th>
                    <th class="text-center" style="width: 17%">Part Name</th>
                    <th class="text-center">Surat Jalan</th>
                    <th class="text-center">Lot Number</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Status Out</th>
                    <th class="text-center">Date Out</th>
                    <th class="text-center">Gate</th>
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
        data.statusout = $('#statusout').val()
        data.gate = $('#gate').val()
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
        }, 
        {
            data: 'ket_in', 
            name: 'ket_in',
        }, 
        {
            data: 'status_out_id', 
            name: 'status_out_id',
        }, 
        {
            data: 'date_out', 
            name: 'date_out',
        }, 
        {
            data: 'gate_id', 
            name: 'gate_id',
        }],
        "initComplete": function() {
            $('#part, #ket, #statusout, #gate').change(function() {
                tableMaster.ajax.url("{!! url()->current() !!}").load();
            });
        }
    });
</script>
@endpush
