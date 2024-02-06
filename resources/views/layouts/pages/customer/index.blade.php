@extends('layouts.app')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Master Customer</h1>

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
            <button type="button" class="btn btn-success" style="width: 90%" onclick="tambahData()"><i class="fa fa-plus"></i> Add Data</button>
        </div>
    </div>
    <div class="col-md-12 table-responsive mt-4 row">
        <table class="table table-striped" id="tblMaster" style="width:100%;">
            <thead>
                <tr>
                    <th class="text-center">Customer</th>
                    <th class="text-center" style="width:30%;">Action</th>
                </tr>
            </thead>
        </table>   
    </div>   

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title"><span id="judulmodal"></span> Master Part</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <form method="post" id="form_id" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="primarykey" id="primarykey">
                        <div class="form-group">
                            <label for="customer">Customer</label>
                            <input type="text" class="form-control" name="customer" id="customer" autocomplete="off" required onkeyup="this.value = this.value.toUpperCase();">
                        </div>                        
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- End of Main Content -->
@endsection

@push('js')
<script>
    let csrf_token = "{{ csrf_token() }}";

    $(document).ready(function() { 
        document.getElementById("form_id").addEventListener("submit", (e) => {
            e.preventDefault()
            validate()
        })
    });

    let tableMaster = $('#tblMaster').DataTable({
        ajax: {
            url: '{!! url()->current() !!}'
        },
        "order": [],
        "iDisplayLength": 25,
        responsive: false,
        "scrollX": true,
        "scrollY": "410px",
        "scrollCollapse": true,
        columns: 
        [{
            data: 'name', 
            name: 'name',
        },  
        {
            data: 'action', 
            name: 'action',
            class: 'dt-center',
        }]
    });

    setInterval(function () {
        tableMaster.ajax.reload();
    }, 1000);

    function tambahData() {
        $("#judulmodal").text("Add");
        $('#primarykey').val('');
        $('#customer').val('');
        $('#modalTambah').modal('show');
    }

    function edit(d) {
        $("#judulmodal").text("Edit");
        $('#primarykey').val(d.id);
        $('#customer').val(d.name);
        $('#modalTambah').modal('show');
    }

    function deleteData(d) {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menghapus?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then(function(result) {
            if (result.value) {
                let url = '{{ route('customer.destroy', ['param1']) }}';
                url = url.replace('param1', btoa(d.id));
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("X-CSRF-Token", csrf_token);
                    },
                    success: function(response) {
                        Swal.fire(response.head, response.msg, response.status);
                        tableMaster.ajax.reload();
                    }
                });
            }
        });
    }

    function validate() {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin mengirimkan formulir?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then(function(result) {
            if (result.value) {
                let form = $('#form_id')[0];
                let formData = new FormData(form);
                $.ajax({
                    type: "POST",
                    url: "{{ route('customer.store') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.fire(response.head, response.msg, response.status);
                        $('#modalTambah').modal('hide');
                        tableMaster.ajax.reload();
                    }
                });
            }
        });
    };
</script>
@endpush
