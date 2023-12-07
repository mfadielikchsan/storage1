@extends('layouts.app')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Master Part</h1>

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
        <div class="col-md-2">
            <button type="button" class="btn btn-success" style="width: 90%" onclick="modalUpload()"><i class="fa fa-upload"></i> Upload Excel</button>
        </div> 
        <div class="col-md-3">
            <a href="{{ route('part.downloadTemplate') }}"class="btn btn-success" style="width: 80%" target="_blank"><i class="fa fa-download"></i> Download Template</a>
        </div>
    </div>
    <div class="col-md-12 table-responsive mt-4 row">
        <table class="table table-striped" id="tblMaster" style="width:100%;">
            <thead>
                <tr>
                    <th class="text-center">Part Number</th>
                    <th class="text-center">Part Name</th>
                    <th class="text-center">Customer</th>
                    <th class="text-center" style="width: 15%;">Update At</th>
                    <th class="text-center" style="width:15%;">Action</th>
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
                            <label for="part_number">Part Number</label>
                            <input type="text" class="form-control" name="part_number" id="part_number" autocomplete="off" required onkeyup="this.value = this.value.toUpperCase();">
                        </div>
                        <div class="form-group">
                            <label for="part_name">Part Name</label>
                            <input type="text" class="form-control" name="part_name" id="part_name" autocomplete="off" required onkeyup="this.value = this.value.toUpperCase();">
                        </div>
                        <div class="form-group">
                            <label for="customer">Customer</label>
                            <select name="customer" id="customer" class="form-control select2" style="width: 100%" required>
                                <option value=""></option>
                                @foreach ($customer as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
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

    <!-- Modal Upload Excel -->
    <div class="modal fade" id="modalUploadExcel">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title"><span id="judulmodal"></span> Upload File Excel (XLS)</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body row" >
                    <div class="col-md-12">
                        <div class="alert alert-danger">
                            <strong>Warning!</strong>
                            <ol>
                                <li>Masukan File Excel Sesuai Template</li>
                                <li>Pastikan Semua Part Number Terisi</li>
                                <li>Pastikan Semua Part Name Terisi</li>
                            </ol>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="file">File Excel (XLS)</label>
                                    <input type="file" name="file" id="file" required accept=".xlsx">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="upload_excel">Submit</button>
                </div>
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
            data: 'updated_at', 
            name: 'updated_at',
            class: 'dt-center',
        },  
        {
            data: 'action', 
            name: 'action',
            class: 'dt-center',
        }]
    });

    function tambahData() {
        $("#judulmodal").text("Add");
        $('#primarykey').val('');
        $('#part_number').val('');
        $('#part_name').val('');
        $('#customer').val('').change();
        $('#modalTambah').modal('show');
    }

    function edit(d) {
        $("#judulmodal").text("Edit");
        $('#primarykey').val(d.id);
        $('#part_number').val(d.part_number);
        $('#part_name').val(d.part_name);
        $('#customer').val(d.customer.id).change();
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
                let url = '{{ route('part.delete', ['param1']) }}';
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

    function modalUpload() {
        $('#modalUploadExcel').modal('show');
    }

    $('#upload_excel').on('click', function() {
        let file = $('#file').prop('files')[0];
        if(file == undefined || !file) {
            Swal.fire({
                title: 'File Tidak Ada',
                text: 'Mohon pilih file terlebih dahulu!',
                icon: 'error'
            })
        } else {
            let file_name = file.name;
            let file_ext = file_name.substring(file_name.lastIndexOf('.'));
            if(file_ext != '.xlsx' && file_ext != '.xls') {
                Swal.fire({
                    title: 'File Tidak Valid',
                    text: 'Mohon pilih file dengan format yang valid (.xls)!',
                    icon: 'error'
                })
            } else {
                let formData = new FormData();
                formData.append('file', file);
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah anda yakin ingin upload dan sudah sesuai ketentuan?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then(function(result) {
                    if (result.value) {
                        $("#loading").show();
                        $.ajax({
                            type: "POST",
                            url: "{{ route('part.upload') }}",
                            data: formData,
                            dataType: 'json',
                            beforeSend: function (xhr) {
                                xhr.setRequestHeader("X-CSRF-Token", csrf_token);
                            },
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                $("#loading").hide();
                                Swal.fire(response.head, response.msg, response.status);
                                $('#modalUploadExcel').modal('hide');
                                tableMaster.ajax.reload();
                            }
                        });
                    }
                });
            }
        }
    });

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
                    url: "{{ route('part.store') }}",
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
