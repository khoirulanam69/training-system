@extends('layouts.master')

@section('title')
    TWS Citra | Training Result
@endsection

@section('content')
{{-- {{dd($data)}} --}}
    <?php $userinfo = Session::get('userinfo'); ?>
    @if($userinfo['user_role'] == 1 || $userinfo['user_role'] == 2)
        <a href="{{route('trainingres.add')}}" type="submit" class="btn btn-primary btn-sm mb-2">
            <i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Data
        </a>
    @endif
    <div class="table-responsive table--no-card m-b-30">
        <table id="trainings" class="table table-borderless table-striped table-earning">
            <thead>
                <tr>
                @if($userinfo['user_role']==1)
                    <th>created at</th>
                    <th>Spesifikasi Training</th>
                    <th>Training Plan ID</th>
                    <th>Training Yang Diperlukan</th>
                    <th>Nama Karyawan</th>
                    <th>Nilai Training</th>
                    <th>Status</th>
                    <th>Action</th>
                @elseif($userinfo['user_role']==2)
                    <th>created at</th>
                    <th>Kompetensi</th>
                    <th>Nama Training</th>
                    <th>Standard Kompetensi</th>
                    <th>Nama Karyawan</th>
                    <th>Nilai Training</th>
                    <th>Status</th>
                @elseif($userinfo['user_role']==4)
                    <th>created at</th>
                    <th>Kompetensi</th>
                    <th>Nama Training</th>
                    <th>Standard Kompetensi</th>
                    <th>Nilai Training</th>
                    <th>Status</th>
                    <th>Sertifikat</th>
                @endif
                </tr>
            </thead>
        </table>
    </div>
    <script>
        var user_role = <?=$userinfo['user_role']?>;
        if (user_role == 1) {
            var table = $('#trainings').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('trainingres.datatables') }}",
                order: [[0, 'desc']],
                columns: [
                    {data: 'created_at'},
                    {data: 'specsname'},
                    {data: 'trainingID'},
                    {data: 'training_needed'},
                    {data: 'karyawan_name'},
                    {data: 'score'},
                    {data: null,
                        render: function (data) {
                            if(data.status == 1){
                                return "Lulus";
                            }else{
                                return "Tidak Lulus";
                            }
                        }
                    },
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        }

        if (user_role == 4) {
            var table = $('#trainings').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('trainingres.datatables') }}",
                order: [[0, 'desc']],
                columns: [
                    {data: 'created_at'},
                    {data: 'competency'},
                    {data: 'training_name'},
                    {data: 'standard'},
                    {data: 'score'},
                    {data: null,
                        render: function (data) {
                            if(data.status == 1){
                                return "Lulus";
                            }else{
                                return "Tidak Lulus";
                            }
                        }
                    },
                    {data: 'file_training', name: 'file_training', orderable: false, searchable: false},
                ]
            });
        }

        if (user_role == 2) {
            var table = $('#trainings').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('trainingres.datatables') }}",
                order: [[0, 'desc']],
                columns: [
                    {data: 'created_at'},
                    {data: 'competency'},
                    {data: 'training_name'},
                    {data: 'standard'},
                    {data: 'karyawan_name'},
                    {data: 'score'},
                    {data: null,
                        render: function (data) {
                            if(data.status == 1 ){
                                return "Lulus";
                            }else{
                                return "Tidak Lulus";
                            }
                        }
                    },
                ]
            });
        }



        $(document).ready(function() {
            $('#trainings').on('click', '.delete', function(){
                var confirm = window.confirm('Delete this : ' + $(this).data('name') + ' result ?');
                var el = this;
                var dataid = $(this).data('id');
                if (confirm){

                    $.ajax({
                        method: 'post',
                        url: '{{ route('trainingres.delete') }}',
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
