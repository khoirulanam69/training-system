@extends('layouts.master')

@section('title')
TWS Citra | Dashboard
@endsection

@section('css')
    <style>
        .karyawan-profile .card {
           border-radius: 10px;
        }

        .karyawan-profile .card .card-header .img-preview {
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin: 10px auto;
            border: 10px solid #ccc;
            border-radius: 50%;
        }
    </style>
@endsection

@section('content')
{{-- {{dump($train)}} --}}
<!-- User Profile -->
<div class="karyawan-profile pb-4">
        <div class="row">
            <div class="mb-3 col-12 text-left">
                <a href="{{ route('matriks.index') }}" class="btn btn-small btn-primary">Kembali</a>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-transparent text-center">
                        <img class="img-preview" src="{{ $data->mediapath }}" alt="User Profile Picture" onerror="imgError(this)" >
                        {{-- <img class="img-preview" src="{{ $data->mediapath }}" alt="" onerror="imgError(this)"> --}}
                        <h5 class="mt-3">{{ $data->name }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-transparent border-0">
                        <h3 class="mb-0"><i class="far fa-clone pr-1"></i>Informasi Umum</h3>
                    </div>
                    <div class="card-body pt-0">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Nama</th>
                                <td width="2%">:</td>
                                <td>{{ $data->name }}</td>
                            </tr>
                            <tr>
                                <th width="30%">Tgl.</th>
                                <td width="2%">:</td>
                                <td>{{ date('d F Y', strtotime($data->positionName)) }}</td>
                            </tr>
                            <tr>
                                <th width="30%">NIK </th>
                                <td width="2%">:</td>
                                <td>{{ $data->nik }}</td>
                            </tr>
                            <tr>
                                <th width="30%">Departemen</th>
                                <td width="2%">:</td>
                                <td>{{ $data->departmentName }}</td>
                            </tr>
                            <tr>
                                <th width="30%">Jabatan</th>
                                <td width="2%">:</td>
                                <td>{{ $data->positionName }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 my-3"><h3>Training yang pernah diikuti:</h3></div>
            <div class="col-12">
                <div class="table-responsive m-b-40 ">
                    <table id="trained" class="table table-striped table-earning">
                        <thead>
                            <tr>
                                <th>created at</th>
                                <th>Kode Training</th>
                                <th>Nama Training</th>
                                <th>Tanggal Mulai</th>
                                <th>Nilai</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Sertifikat</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            </div>
            <div class="col-12 my-3"><h3>Training yang sedang diikuti:</h3></div>
            <div class="col-12">
                <div class="table-responsive m-b-40 ">
                    <table id="train" class="table table-striped table-earning">
                        <thead>
                            <tr>
                                <th>created at</th>
                                <th>Kode Training</th>
                                <th>Nama Training</th>
                                <th>Tanggal Mulai</th>
                                <th>Nilai</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="col-12 my-3"><h3>Training yang belum pernah diikuti:</h3></div>
            <div class="col-12">
                <div class="table-responsive m-b-40 ">
                    <table id="reports" class="table table-striped table-earning">
                        <thead>
                            <tr>
                                <th>created at</th>
                                <th>Kode Training</th>
                                <th>Kompetensi</th>
                                <th>Aspek Penting</th>
                                <th>Training yang diperlukan</th>
                                <th>Standard Kompetensi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
</div>
<!-- /User Profile -->
<script>

    $(document).ready(function(){

        let karyawan = {!! json_encode($data) !!}
        function imgError(image) {
            image.onerror = "";
            image.src = "{{ URL::to('images/big_image_800x600.gif')}}";
            return true;
        }

        let customColumn1 =
        [
            {data: 'created_at'},
            {data: 'kode_training'},
            {data: 'training_name'},
            {data: 'start_date'},
            {data: null,
                render: function (data) {
                    return data.training_result[0].score
                }, orderable: false
            },
            {data: null,
                render: function (data) {
                    if(data.training_result[0].status == 1){
                        return "Lulus";
                    }else{
                        return "Tidak Lulus";
                    }
                }, orderable: false
            },
            {data: 'status',
                render: function(data){
                    switch (data) {
                        case '1':
                            return "Belum Berlangsung";
                            break;
                        case '2':
                            return "Sedang Berlangsung";
                            break;
                        case '3':
                            return "Sudah Berlangsung";
                            break;
                        default:
                            return  "-";
                            break;
                    }
                }
            },
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ]

        let table1 = $('#trained').dataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                url: '/home/matriks/'+ karyawan.id + '/datatables/trained',
                type:'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                data: function (d) {
                    d.departmentID = $('#departmentID').val();
                    d.jabatanID = $('#jabatanID').val();
                    d.report_date = $('#report_date').val();
                    d.karyawan_name = $('#karyawan_name').val();
                }
            },
            columns: customColumn1
        });
        let customColumn2 =
        [
            {data: 'created_at'},
            {data: 'traintypeid'},
            {data: 'competency'},
            {data: 'important_aspect'},
            {data: 'training_needed'},
            {data: 'standard'}
        ]

        $('.refresh').on('click', function(e){
            e.preventDefault();
            $('#jabatanID').val("");
            $('#report_date').val("");
            $('#karyawan_name').val("");
            var table = $('#reports').DataTable();
            table.draw(true);
        });
        $('.filterby').on('click', function(e){
            e.preventDefault();
            var table = $('#reports').DataTable();
            table.draw(true);
        });

        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        if (dd < 10) {
            dd = '0' + dd;
        }
        if (mm < 10) {
            mm = '0' + mm;
        }

        var today = dd + '-' + mm + '-' + yyyy;
        var file_title = 'training_result_report_'+today;
        let table2 = $('#reports').dataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                url: '/home/matriks/'+ karyawan.id + '/datatables/trainings',
                type:'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                data: function (d) {
                    d.departmentID = $('#departmentID').val();
                    d.jabatanID = $('#jabatanID').val();
                    d.report_date = $('#report_date').val();
                    d.karyawan_name = $('#karyawan_name').val();
                },
                error: function(error){
                    console.log(error)
                }
            },
            columns: customColumn2
        });

        let customColumn3 =
        [
            {data: 'created_at'},
            {data: 'kode_training'},
            {data: 'training_name'},
            {data: 'start_date'},
            {data: 'value'},
            {data: 'status',
                render: function(data){
                    switch (data) {
                        case '1':
                            return "Belum Berlangsung";
                            break;
                        case '2':
                            return "Sedang Berlangsung";
                            break;
                        case '3':
                            return "Sudah Berlangsung";
                            break;
                        default:
                            return  "-";
                            break;
                    }
                }
            },
        ]

        let table3 = $('#train').dataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                url: '/home/matriks/'+ karyawan.id + '/datatables/train',
                type:'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                data: function (d) {
                    d.departmentID = $('#departmentID').val();
                    d.jabatanID = $('#jabatanID').val();
                    d.report_date = $('#report_date').val();
                    d.karyawan_name = $('#karyawan_name').val();
                },
                error: function(error){
                    console.log(error)
                }
            },
            columns: customColumn3
        });
    });

</script>
@endsection
