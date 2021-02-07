@extends('layouts.master')

@section('title')
    TWS Citra | Training Plan
@endsection

@section('content')
{{-- {{dd($data[0])}} --}}
    <?php $userinfo = Session::get('userinfo'); ?>
    @if($userinfo['user_role'] == 4)
        <span data-toggle="modal" data-target="#addplanModal"  class="btn btn-primary btn-sm mb-2">
            <i class="fa fa-plus-circle" aria-hidden="true"></i> Request New Plan
        </span>
    @endif
    <div class="table-responsive table--no-card m-b-30">
        <table id="trainingreq" class="table table-borderless table-striped table-earning">
            <thead>
            <tr>
                @if($userinfo['user_role'] == 4)
                    <th>created at</th>
                    <th>Kode Training</th>
                    <th>Spesifikasi Training</th>
                    <th>Nama Training</th>
                    <th>Grade</th>
                    <th>Kompetensi</th>
                    <th>Tanggal Mulai</th>
                    <th>Status</th>
                    <th>Action</th>

                @elseif($userinfo['user_role'] == 3)
                    <th>created at</th>
                    <th>Grade</th>
                    <th>Kompetensi</th>
                    <th>Nama Training</th>
                    <th>Nama Karyawan</th>
                    <th>Action</th>

                @elseif($userinfo['user_role'] == 2)
                    <th>created at</th>
                    <th>Grade</th>
                    <th>Kompetensi</th>
                    <th>Nama Training</th>
                    <th>Nama Karyawan</th>
                    <th>Status</th>

                @elseif($userinfo['user_role'] == 1)
                    <th>created at</th>
                    <th>Nama Karyawan</th>
                    <th>Department</th>
                    <th>Jabatan</th>
                    <th>Kompetensi</th>
                    <th>Nama Training</th>
                    <th>Status</th>
                @endif
            </tr>
            </thead>
        </table>
    </div>
    <script>
        const months = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
    </script>
    @if($userinfo['user_role'] == 1)
        <script>
            var user_id = <?=$userinfo['user_id']?>;
            var table = $('#trainingreq').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('trainingreq.datatables') }}",
                columns: [
                    {data: 'created_at'},
                    {data: 'karyawan_name'},
                    {data: 'department_name'},
                    {data: 'position_name'},
                    {data: 'competency'},
                    {data: 'training_name'},
                    {data: 'status',
                        render: function(data){
                        var status_req = "" ;
                            switch (data) {
                                case '1':
                                    status_req = "Requested";
                                    break;
                                case '2':
                                    status_req = "Accepted";
                                    break;
                                case '3':
                                    status_req = "Not Accepted";
                                    break;
                                case '4':
                                    status_req = "Cancel";
                                    break;
                                case '5':
                                    return "Requested";
                                    break;
                            }

                            return status_req;
                        }
                    },
                ]
            });

            $('#trainingreq tbody').on('click', '.detail', function () {
                var data = table.row( $(this).parents('tr') ).data();
                $('#formdetail input[name="kompetensi"]').val(data.competency);
                $('#formdetail input[name="kode_training"]').val(data.kode_training);
                $('#formdetail input[name="training_name"]').val(data.training_name);
                $('#formdetail input[name="duration"]').val(data.duration);
                $('#formdetail input[name="start_date"]').val(data.start_date);
                $('#formdetail input[name="training_location"]').val(data.training_location);
                $('#formdetail input[name="standard"]').val(data.standard);
                $('#formdetail input[name="karyawan_name"]').val(data.karyawan_name);

                var state = $('#formdetail').find('.acceptance').attr('data-id', data.id).attr('data-trainname', data.training_name).attr('data-karyawanname',data.karyawan_name).attr('data-karyawanid',data.requestby);
                console.log(state);
            });

        </script>
    @endif

    @if($userinfo['user_role'] == 2)
        <script>
            var user_id = <?=$userinfo['user_id']?>;
            var table = $('#trainingreq').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('trainingreq.datatables') }}",
                columns: [
                    {data: 'created_at'},
                    {data: 'grade',
                        render: function(data){
                            if(data == undefined){
                                return "-"
                            }else{
                                return data
                            }
                        }
                    },
                    {data: 'competency'},
                    {data: 'training_name'},
                    {data: 'karyawan_name'},
                    {data: 'status',
                        render: function(data){
                        switch (data) {
                            case '1':
                                return "Menunggu Konfirmasi";
                                break;
                            case '2':
                                return "Request Diterima";
                                break;
                            case '3':
                                return "Request Ditolak";
                                break;
                            case '4':
                                return "Telah di Cancel";
                                break;
                            case '5':
                                return "Menunggu Konfirmasi";
                                break;
                            default:
                                return  "Belum Request";
                                break;
                        }
                    }},
                ]
            });
        </script>
    @endif

    @if($userinfo['user_role'] == 3)
        <script>
            var user_id = <?=$userinfo['user_id']?>;
            var table = $('#trainingreq').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('trainingreq.datatables') }}",
                columns: [
                    {data: 'created_at'},
                    {data: 'grade',
                        render: function(data){
                            if(data == undefined){
                                return "-"
                            }else{
                                return data
                            }
                        }
                    },
                    {data: 'competency'},
                    {data: 'training_name'},
                    {data: 'karyawan_name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            $('#trainingreq tbody').on('click', '.detail', function () {
                var data = table.row( $(this).parents('tr') ).data();
                $('#formdetail input[name="grade"]').val(data.grade);
                $('#formdetail input[name="kompetensi"]').val(data.competency);
                $('#formdetail input[name="kode_training"]').val(data.kode_training);
                $('#formdetail input[name="training_name"]').val(data.training_name);
                $('#formdetail input[name="duration"]').val(data.duration);
                $('#formdetail input[name="start_date"]').val(data.start_date);
                $('#formdetail input[name="training_location"]').val(data.training_location);
                $('#formdetail input[name="standard"]').val(data.standard);
                $('#formdetail input[name="karyawan_name"]').val(data.karyawan_name);

                var state = $('#formdetail').find('.acceptance').attr('data-id', data.id).attr('data-trainname', data.training_name).attr('data-karyawanname',data.karyawan_name).attr('data-karyawanid',data.requestby);
                console.log(state);
            });

        </script>
    @endif

    @if($userinfo['user_role'] == 4)
        <script>
            var table = $('#trainingreq').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('trainingreq.datatables') }}",
                columns: [
                    {data: 'created_at'},
                    {data: 'traintypeid'},
                    {data: 'traintype'},
                    {data: 'training_name'},
                    {data: 'grade',
                        render: function(data){
                            if(data == undefined){
                                return "-"
                            }else{
                                return data
                            }
                        }
                    },
                    {data: 'competency'},
                    {data: 'start_date',
                        render: function(data){
                            var dates = new Date(data);
                            var formatted_date = dates.getDate() + " " + months[dates.getMonth()] + " " + dates.getFullYear();
                            return formatted_date;
                        }
                    },
                    {data: 'request_status',
                        render: function(data){
                            switch (data) {
                                case '1':
                                    return "Menunggu Konfirmasi";
                                    break;
                                case '2':
                                    return "Request Diterima";
                                    break;
                                case '3':
                                    return "Request Ditolak";
                                    break;
                                case '4':
                                    return "Telah di Cancel";
                                    break;
                                case '5':
                                    return "Menunggu Konfirmasi";
                                    break;
                                default:
                                    return  "Belum Request";
                                    break;
                            }
                        }},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            $('#trainingreq tbody').on('click', '.detail', function () {
                var data = table.row( $(this).parents('tr') ).data();
                $('#formdetail input[name="grade"]').val(data.grade);
                $('#formdetail input[name="kompetensi"]').val(data.competency);
                $('#formdetail input[name="kode_training"]').val(data.kode_training);
                $('#formdetail input[name="training_name"]').val(data.training_name);
                $('#formdetail input[name="duration"]').val(data.duration);
                $('#formdetail input[name="start_date"]').val(data.start_date);
                $('#formdetail input[name="training_location"]').val(data.training_location);
                $('#formdetail input[name="standard"]').val(data.standard);
                console.log(data);
            });

            $(document).ready(function() {

                $('#trainingreq').on('click', '.request', function(){
                    var confirm = window.confirm('Request training: ' + $(this).data('name') + ' ?');
                    var el = this;
                    var dataid = $(this).data('id');
                    if (confirm){

                        $.ajax({
                            method: 'post',
                            url: '{{ route('trainingreq.updatestatus') }}',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            data: {
                                'status': 1,
                                'id': dataid
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

                $('#trainingreq').on('click', '.cancel', function(){
                    var confirm = window.confirm('Delete request training: ' + $(this).data('name') + ' ?');
                    var el = this;
                    var dataid = $(this).data('id');
                    if (confirm){

                        $.ajax({
                            method: 'post',
                            url: '{{ route('trainingreq.updatestatus') }}',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            data: {
                                'status': 4,
                                'id': dataid
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
            });
        </script>
    @endif
@endsection

@section('modal')
    {{--Add Plan Modal --}}
    <div class="modal fade" id="addplanModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" >
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{route('training.add')}}" method="post" enctype="multipart/form-data" class="form-horizontal">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mediumModalLabel">Request New Plan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="kode_training" class=" form-control-label">Kode Training</label>
                                    </div>
                                    <div class="col-9 col-md-9">
                                        <input type="text" name="kode_training" placeholder="e.g. TRA001" class="form-control" required="" value="10">
                                        <input type="hidden" name="created_by" class="form-control" value="{{--{{$userinfo['user_id']}}--}}" required="">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="training_name" class=" form-control-label">Nama Training</label>
                                    </div>
                                    <div class="col-9 col-md-9">
                                        <input type="text" name="training_name" value="10" placeholder="e.g. Training Manajer" class="form-control" required="">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="spectraining_id" class=" form-control-label">Spesifikasi Training</label>
                                    </div>
                                    <div class="col-9 col-md-9">
                                        <select name="spectraining_id" class="form-control" required="">
                                            <option disabled="" selected="" value=""> -- select an option -- </option>
                                            @foreach($specs as $spec)
                                                <option value="{{$spec->id}}">{{$spec->traintypeid}} - {{$spec->traintype}} - {{$spec->grade}} - {{$spec->competency}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="duration" class=" form-control-label">Durasi (Jam)</label>
                                    </div>
                                    <div class="col-9 col-md-9">
                                        <input type="number" name="duration" placeholder="e.g. 10" class="form-control" required="" min="0" value="10">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="trainer_name" class=" form-control-label">Nama Trainer</label>
                                    </div>
                                    <div class="col-9 col-md-9">
                                        <input type="text" name="trainer_name" placeholder="e.g. Wildan Wibowo" value="10-30-2019" class="form-control" required="">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="training_location" class=" form-control-label">Lokasi Training</label>
                                    </div>
                                    <div class="col-9 col-md-9">
                                        <textarea name="training_location" value="10-30-2019" id="training_location" rows="9" placeholder="Jl. Widuri No.93 Banyuwangi" class="form-control" required=""></textarea>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="start_date" class=" form-control-label">Tanggal Mulai</label>
                                    </div>
                                    <div class="col-9 col-md-9">
                                        <input type="date" id="start_date" name="start_date" placeholder="10-30-2019" value="10-30-2019" class="form-control" required="">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="end_date" class=" form-control-label">Tanggal Berakhir</label>
                                    </div>
                                    <div class="col-9 col-md-9">
                                        <input type="date" id="end_date" name="end_date" placeholder="10-30-2019" value="10-30-2019" class="form-control" required="">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="status" class=" form-control-label">Status</label>
                                    </div>
                                    <div class="col-9 col-md-9">
                                        <select name="status" id="status" class="form-control">
                                            <option disabled="" value=""> -- select an option -- </option>
                                            <option value="1" selected>Belum Berlangsung</option>
                                            <option value="2">Sedang Berlangsung</option>
                                            <option value="3">Sudah Berlangsung</option>
                                        </select>
                                    </div>
                                </div>
                                {{csrf_field()}}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary submit-tugas">Submit</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{--  Detail Modal --}}
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" >
        <form id="formdetail">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mediumModalLabel">Detail Training</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="grade" class=" form-control-label">Grade</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" name="grade" placeholder="" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="kompetensi" class=" form-control-label">Kompetensi</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" name="kompetensi" placeholder="" class="form-control" readonly>
                                    </div>
                                </div>
                                <hr>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="kode_training" class=" form-control-label"><strong>Kode Training :</strong></label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" name="kode_training" placeholder="" class="form-control" readonly>
                                    </div>
                                </div>
                                <hr>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="training_name" class=" form-control-label"><strong>Nama Training :</strong></label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" name="training_name" placeholder="" class="form-control" readonly>
                                    </div>
                                </div>
                                <hr>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="duration" class=" form-control-label"><strong>Durasi (Hari) :</strong></label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" name="duration" placeholder="" class="form-control" readonly>
                                    </div>
                                </div>
                                <hr>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="start_date" class=" form-control-label"><strong>Lokasi :</strong></label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" name="training_location" placeholder="" class="form-control" readonly>
                                    </div>
                                </div>
                                <hr>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="start_date" class=" form-control-label"><strong>Tanggal Mulai :</strong></label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" name="start_date" placeholder="" class="form-control" readonly>
                                    </div>
                                </div>
                                <hr>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="standard" class=" form-control-label"><strong>Standar Kompetensi :</strong></label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" name="standard" placeholder="" class="form-control" readonly>
                                    </div>
                                </div>
                                @if($userinfo['user_role'] == 3)
                                    <div class="row form-group">
                                        <div class="col col-md-3">
                                            <label for="karyawan_name" class=" form-control-label"><strong>Nama Karyawan :</strong></label>
                                        </div>
                                        <div class="col-12 col-md-9">
                                            <input type="text" name="karyawan_name" placeholder="" class="form-control" readonly>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @if($userinfo['user_role'] == 3)
                            <button type="button" data-id="" data-status="3" class="btn btn-danger acceptance">Tidak Setuju</button>
                            <button type="button" data-id="" data-status="2" class="btn btn-success acceptance">Setujui</button>
                        @endif
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        $('#formdetail').on('click', '.acceptance', function(){
            var el = this;
            var dataid = $('#formdetail .acceptance').attr('data-id');
            var status = $(el).attr('data-status');
            console.log(status);
            var trainingname = $('#formdetail .acceptance').attr('data-trainname');
            var karyawanname = $('#formdetail .acceptance').attr('data-karyawanname');

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
                        'status': status,
                        'id': dataid,
                    },
                    success: function( res ) {
                        $('#detailModal').modal('toggle');
                        alert('Request has been send');
                        $(el).closest('tr').css('background','tomato');
                        $(el).closest('tr').fadeOut(800,function(){
                            $(this).remove();
                        });

                        $('#trainingreq').DataTable().ajax.reload();

                    },
                    error: function ( msg ) {
                        $('#detailModal').modal('toggle');
                        alert("An error occured. Please contact your system administrator");
                    }
                });
            }else{
                return false;
            }
        });
    </script>
@endsection
