@extends('layouts.app')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Scan Out Non Delivery Finish Good</h1>

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
    <div class="col-md-12 row">
        <div class="form-group col-md-2">
            <label for="ket">Keterangan</label>
            <select name="ket" id="ket" class="form-control select2" style="width: 100%" required>
                <option value="1">REG AHM</option>
                <option value="2">EXP AHM</option>
                <option value="3">NON AHM</option>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control select2" style="width: 100%" required>
                <option value=""></option>
                @foreach ($status as $s)
                    <option value="{{ $s->id }}" statusname="{{ $s->name }}">{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-2">
            <label for="nosj">No Surat Jalan</label>
            <input type="text" class="form-control" id="nosj">
        </div>
        <div class="form-group col-md-5">
            <button type="button" style="margin-top:30px;" onclick="scanQrCode()" class="btn btn-success">Scan QR <i class="fa fa-qrcode" aria-hidden="true"></i></button>
            <a href="{{ route('stock-fg.indexoutnon') }}"><button type="button" style="margin-top:30px;" class="btn btn-danger fa-pull-right">Back</button></a>
        </div>
    </div>

    <!-- Modal QR -->
    <div class="modal" id="qrModal" tabindex="-1" role="dialog" aria-labelledby="qrModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrModalLabel">Scan QR Code</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Preview video untuk pemindaian QR -->
                    <video id="preview" width="100%" height="100%"></video>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Scan Out Non Delivery Finish Good</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <form method="post" id="form_id" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="primarykey" id="primarykey">
                        <div class="form-group">
                            <label for="statuslabel">Status Out</label>
                            <input type="hidden" name="statusout" id="statusout">
                            <input type="text" class="form-control"id="statuslabel" readonly required>
                        </div> 
                        <div class="form-group">
                            <label for="nosuratjalan">No Surat Jalan</label>
                            <input type="text" class="form-control" name="nosuratjalan" id="nosuratjalan" readonly required>
                        </div> 
                        <div class="form-group">
                            <label for="partno">No Part</label>
                            <input type="text" class="form-control" name="partno" id="partno" readonly required>
                        </div> 
                        <div class="form-group">
                            <label for="partname">Part Name</label>
                            <input type="text" class="form-control" name="partname" id="partname" readonly required>
                        </div>    
                        <div class="form-group">
                            <label for="qty">Quantity</label>
                            <input type="text" class="form-control" name="qty" id="qty" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="nomorlot">Nomor LOT</label>
                            <input type="text" class="form-control" name="nomorlot" id="nomorlot" readonly required>
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

@endsection

@push('js')
<script src="{{ asset('js/instascan.min.js') }}"></script>
<script>
    let csrf_token = "{{ csrf_token() }}";
    let scanner;

    $(document).ready(function() { 
        document.getElementById("form_id").addEventListener("submit", (e) => {
            e.preventDefault()
            validate()
        })
    });

    function scanQrCode() {
        let ket = $("#ket").val();
        let nosj = $("#nosj").val();
        let status = $("#status").val();
        let statuslabel = $("#status").find(':selected').attr('statusname');

        if(status != '' && nosj != '') {
            scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
            scanner.addListener('scan', function (content) {
                $('#qrModal').modal('hide');
                console.log('Hasil Scan QR: ' + content);

                if ((content.match(/\|/g) || []).length === 3) {
                    let kode = content.split('|');
                    let nopart = kode[0];
                    let qty = parseInt(kode[2]);
                    let nolot = kode[3];

                    $('#loading').show();
                    $.ajax({
                        url: '{{ route('stock-fg.getpartOut') }}',
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': csrf_token
                        },
                        data: {
                            nopart: nopart,
                            nolot: nolot,
                            qty: qty,
                            ket: ket,
                        },
                        success: function(response) {
                            $('#loading').hide();
                            if(response.data) {
                                console.log(response.data)
                                $("#primarykey").val(response.data.id) 
                                $("#statusout").val(status) 
                                $("#statuslabel").val(statuslabel) 
                                $("#nosuratjalan").val(nosj) 
                                $("#partno").val(nopart) 
                                $("#partname").val(response.data.part.part_name) 
                                $("#qty").val(qty) 
                                $("#nomorlot").val(nolot) 
                                $('#modalTambah').modal('show');
                            } else {
                                Swal.fire('Warning', 'Part Number Tidak Ada Pada Stock', 'warning')
                            }
                        }
                    });
                }else{
                    Swal.fire('Warning', 'QR Code Tidak Valid', 'warning')
                }
            });
    
            Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length > 0) {
                    scanner.start(cameras[0]); // Gunakan kamera pertama yang ditemukan
                } else {
                    console.error('Tidak ada kamera yang ditemukan.');
                }
            }).catch(function (e) {
                console.error(e);
            });
            $('#qrModal').modal().show();
            $('#qrModal').on('hidden.bs.modal', function () {
                // Hentikan pemindaian saat modal ditutup
                if (scanner) {
                    scanner.stop();
                }
            });
        }else{
            Swal.fire({
                title: 'Warning',
                text: 'Status dan No Surat Jalan Tidak Boleh Kosong',
                icon: 'warning'
            })
        }
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
                    url: "{{ route('stock-fg.storeScanoutNon') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.fire(response.head, response.msg, response.status);
                        $('#modalTambah').modal('hide');
                    }
                });
            }
        });
    };

</script>

@endpush
