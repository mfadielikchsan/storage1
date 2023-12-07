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
                <option value="1">REG</option>
                <option value="2">EXP</option>
            </select>
        </div>
        <div class="form-group col-md-10">
            <button type="button" style="margin-top:30px;" onclick="scanQrCode()" class="btn btn-success">Scan QR <i class="fa fa-qrcode" aria-hidden="true"></i></button>
            <a href="{{ route('stock-fg.index') }}"><button type="button" style="margin-top:30px;" class="btn btn-danger fa-pull-right">Back</button></a>
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

@endsection

@push('js')
<script src="{{ asset('js/instascan.min.js') }}"></script>
<script>
    let scanner;

    function scanQrCode() {
        let ket = $("#ket").val();
        scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
        scanner.addListener('scan', function (content) {
            $('#qrModal').modal('hide');
            console.log('Hasil Scan QR: ' + content);
            // Di sini Anda dapat menangani hasil pemindaian QR sesuai kebutuhan
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
    }

    $('#qrModal').on('hidden.bs.modal', function () {
        // Hentikan pemindaian saat modal ditutup
        if (scanner) {
            scanner.stop();
        }
    });
</script>

@endpush
