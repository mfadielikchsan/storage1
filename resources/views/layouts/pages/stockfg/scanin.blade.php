@extends('layouts.app')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Scan In Finish Good</h1>

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
        <div class="form-group col-md-2">
            <label for="nosj">No Surat Jalan</label>
            <input type="text" class="form-control" id="nosj">
        </div>
        <div class="form-group col-md-8">
            <button type="button" style="margin-top:30px;" onclick="scanQrCode()" class="btn btn-success">Scan QR <i class="fa fa-qrcode" aria-hidden="true"></i></button>
            <a href="{{ route('stock-fg.indexin') }}"><button type="button" style="margin-top:30px;" class="btn btn-danger fa-pull-right">Back</button></a>
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
                    <h4 class="modal-title">Scan In Finish Good</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <form method="post" id="form_id" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="part_id" id="part_id">
                        <div class="form-group">
                            <label for="keteranganlabel">Keterangan</label>
                            <input type="hidden" name="keterangan" id="keterangan">
                            <input type="text" class="form-control"id="keteranganlabel" readonly required>
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
                        <div class="form-group">
                            <label for="jumlah">Jumah Scan</label>
                            <input type="number" class="form-control" name="jumlah" id="jumlah" autocomplete="off" required>
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
        if(nosj != '') {
            scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
            scanner.addListener('scan', function (content) {
                $('#qrModal').modal('hide');
                console.log('Hasil Scan QR: ' + content);

                if ((content.match(/\|/g) || []).length === 3) {
                    let kode = content.split('|');
                    let nopart = kode[0];

                    $('#loading').show();
                    let url = '{{ route('stock-fg.getpart', ['param1']) }}';
                    url = url.replace('param1', nopart);
                    $.ajax({
                        url: url,
                        type: "GET",
                        success: function(response) {
                            $('#loading').hide();
                            if(response.data) {
                                console.log(response.data)
                                let qty = parseInt(kode[2]);
                                let nolot = kode[3];
                                let ketlabel = '';
                                if(ket == 1) {
                                    ketlabel =  'REG'
                                }else if(ket == 2) {
                                    ketlabel =  'EXP'
                                }

                                $("#part_id").val(response.data.id) 
                                $("#keterangan").val(ket) 
                                $("#keteranganlabel").val(ketlabel) 
                                $("#nosuratjalan").val(nosj) 
                                $("#partno").val(nopart) 
                                $("#partname").val(response.data.part_name) 
                                $("#qty").val(qty) 
                                $("#nomorlot").val(nolot) 
                                $('#modalTambah').modal('show');
                            }else{
                                Swal.fire('Warning', 'Part Number Tidak Ditemukan', 'warning')
                            }
                        }
                    });
                }else{
                    Swal.fire('Warning', 'QR Code Tidak Valid', 'warning')
                }

            });
    
            Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length > 0) {
                    let selectedCamera = null;

                    // Temukan kamera belakang jika ada
                    const rearCamera = cameras.find(camera => camera.name.toLowerCase().includes('back') || camera.name.toLowerCase().includes('belakang'));
                    if (rearCamera) {
                        selectedCamera = rearCamera; // Gunakan kamera belakang jika ditemukan
                    } else {
                        selectedCamera = cameras[0]; // Gunakan kamera pertama yang ditemukan
                    }

                    scanner.start(selectedCamera);
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
            Swal.fire('Warning', 'Pilih No Surat Jalan Terlebih Dahulu', 'warning')
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
                    url: "{{ route('stock-fg.storeScanin') }}",
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
