@extends('layouts.app')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Recapt Finish Good</h1>

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
    <div class="col-md-12 table-responsive mt-4  row">
        <table class="table table-striped" id="tblMaster" style="width:100%;">
            <thead>
                <tr>
                    <th class="text-center">Part Number</th>
                    <th class="text-center">Part Name</th>
                    <th class="text-center">Customer</th>
                    <th class="text-center">Stock</th>
                </tr>
            </thead>
        </table>   
    </div>   
@endsection

@push('js')
<script>

    let tableMaster = $('#tblMaster')
    .DataTable({
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
            data: 'total_stock', 
            name: 'total_stock',
            class: 'dt-center',
        }]
    });
</script>
@endpush
