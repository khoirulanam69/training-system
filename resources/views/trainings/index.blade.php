@extends('layouts.master')

@section('title')
    TWS Citra | Training Plan
@endsection

@section('content')
    <?php $userinfo = Session::get('userinfo'); ?>
    <div class="row">
        @if(in_array($userinfo['user_role'],array('1','2')))
            <div class="col-md-2">
                    <a href="{{route('training.add')}}" type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Data
                    </a>
            </div>
            <div class="text-left col-md-2">
                <span> Or import from Excel file </span>
            </div>
            <div class="col-md-7">
                <form enctype="multipart/form-data" action="{{route('training.import.excel')}}" method="post">
                    {{ csrf_field() }}
                    <input type="file" name="training_file" id="training_file">
                    @if (empty($spectrain[0]->id))
                    <button class="btn btn-success" type="submit" disabled>Upload</button>
                    <span style="color: red;" class="ml-3">Tabel Spesifikasi training kosong</span>
                    @else
                    <button class="btn btn-success" type="submit">Upload</button>
                    @endif
                </form>
                <a class="my-3 btn btn-primary" href="{{url('/sample/sample-training-plan.xlsx')}}">Download Sample</a>
            </div>
        @endif
    </div>
    <div class="table-responsive table--no-card m-b-30">
        <table id="trainings" class="table table-borderless table-striped table-earning">
            <thead>
            <tr>
                <th>created at</th>
                <th>Kode Training</th>
                <th>Nama Training</th>
                <th>Spesifikasi Training</th>
                <th>Durasi</th>
                <th>Trainer</th>
                <th>Lokasi</th>
                <th>Tanggal Mulai</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>
    </div>
    <select id="clone_karyawan" class="form-control d-none">
        @foreach($karyawans as $kar)
            <option value="{{$kar->id}}">{{$kar->name}}</option>
        @endforeach
    </select>
    <script>
        var table = $('#trainings').DataTable({
            processing: true,
            serverSide: true,
            searchable: false,
            ajax: "{{ route('training.datatables') }}",
            order: [[0, 'desc']],
            columns: [
                {data: 'created_at'},
                {data: 'kode_training', name: 'kode_training'},
                {data: 'training_name'},
                {data: 'traintype'},
                {data: 'duration', render: function (data) {
                            return data+" Jam";
                    }},
                {data: 'trainer_name', name: 'trainer_name'},
                {data: 'training_location', name: 'training_location'},
                {data: 'start_date', name: 'start_date'},
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
                    }},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('#trainings tbody').on('click', '.penugasan', function () {
            var data = table.row( $(this).parents('tr') ).data();
            var name = "";
            var selectkaryawan = [];
            var count_karyawan = $(data.karyawan).length;
            var count_requestby = $(data.request_karyawan).length;

            var clone_option =  $('#clone_karyawan').find('option').clone(true);
            $("#karyawanid option[value!='']").remove();
            $('#karyawanid').append(clone_option);

            if(count_requestby > 0){
                $.each( data.request_karyawan, function( key, value ) {
                    $("#karyawanid option[value='"+value.userid+"']").remove();
                });
            }

            if(count_karyawan > 0){
                $.each( data.karyawan, function( key, value ) {
                    selectkaryawan[key] = value.userid;
                    $("#karyawanid option[value='"+value.userid+"']").remove();
                    if(count_karyawan === 1){
                        name += value.username+' ';
                    } else {
                        if (key+1 === count_karyawan) {
                            name += value.username+' ';
                        }else{
                            name += value.username+', ';
                        }
                    }
                });
                $('#karyawantugas').html(name);
            }else {
                $('#karyawantugas').html('Belum ada penugasan karyawan');
            }

            $('#karyawanid').find('option[value = ""]').remove();
            $('#formdetail input[name="trainingID"]').val(data.id);
        });


        $(document).ready(function() {
            $('#trainings').on('click', '.delete', function(){
                var confirm = window.confirm('Delete posiiton: ' + $(this).data('name') + ' ?');
                var el = this;
                var dataid = $(this).data('id');
                if (confirm){

                    $.ajax({
                        method: 'post',
                        url: '{{ route('training.delete') }}',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            'id': dataid
                        },
                        success: function( res ) {
                            alert('Delete success');
                            $(el).closest('tr').css('background','tomato');
                            $(el).closest('tr').fadeOut(800,function(){
                                $(this).remove();
                            });

                            $('#trainings').DataTable().ajax.reload();

                        },
                        error: function ( msg ) {
                            alert("An error occured. Please contact your system administrator");
                        }
                    });
                }else{
                    return false;
                }
            });
        });
    </script>
@endsection

@section('modal')
    {{--  Modal --}}
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" >
        <form id="formdetail">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mediumModalLabel">Penugasan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="row form-group">
                                    <div class="col-12 col-md-9">
                                        <label for="karyawantugas" class=" form-control-label"><strong>Daftar Nama Karyawan yang Ditugaskan</strong></label>
                                        <p id="karyawantugas">
                                        </p>
                                        <input type="hidden" name="trainingID" class="form-control" required="true" value="" >
                                    </div>
                                </div>
                                @if($userinfo['user_role'] == 2)
                                <hr>
                                    <div class="row form-group">
                                        <div class="col-12 col-md-9">
                                            <label for="karyawanid" class=" form-control-label"><strong>Nama Karyawan</strong></label>
                                            <select id="karyawanid" name="karyawanid[]" class="form-control select2" multiple>
                                            </select>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @if($userinfo['user_role'] == 2)
                            <button type="button" data-id="" data-status=2 class="btn btn-primary submit-tugas">Submit</button>
                        @endif
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        $('#formdetail').on('click', '.submit-tugas', function(){
            var confirm = window.confirm('Assign request training?');

            if(confirm){
                $.ajax({
                    method: 'post',
                    url: '{{ route('assignment.hrdaddassign') }}',
                    data: $("#formdetail").serialize(),
                    dataType:"json", //misal kita ingin format datanya brupa json
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function( res ) {
                        table.ajax.reload();
                        alert('Data has been send');
                        $('#detailModal').modal('toggle');

                    },
                    error: function ( msg ) {
                        alert("An error occured. Data penugasan karyawan sudah pernah di input");
                        $('#detailModal').modal('toggle');
                    }
                });
            }else{
                return false;
            }
        });
        $('#formdetail').on('click', '.acceptance', function(){
            var el = this;
            var dataid = $(el).data('id');
            var status = $(el).data('status');
            var trainingname = $(el).data('trainname');
            var karyawanname = $(el).data('karyawanname');

            if(status == 2){
                var confirm = window.confirm('Accept request training: ' + trainingname +' for '+ karyawanname +' ?');
            }else{
                var confirm = window.confirm('Not Accept request training: ' + trainingname +' for '+ karyawanname +' ?');
            }
            if (confirm){

                $.ajax({
                    method: 'post',
                    url: '{{ route('trainingreq.updatestatus') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        'type': status,
                        'id': dataid,
                    },
                    success: function( res ) {
                        alert('Request has been send');
                        $(el).closest('tr').css('background','tomato');
                        $(el).closest('tr').fadeOut(800,function(){
                            $(this).remove();
                        });

                        $('#trainingreq').DataTable().ajax.reload();

                    },
                    error: function ( msg ) {
                        alert("An error occured. Please contact your system administrator");
                    }
                });
            }else{
                return false;
            }
        });
    </script>
@endsection
