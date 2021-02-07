@extends('layouts.master')

@section('title')
    TWS Citra | Certificate List
@endsection

@section('content')
    {{-- {{dump($data)}} --}}
    <?php $userinfo = Session::get('userinfo'); ?>
    <div class="col-12 my-3 p-0"><h3>Karyawan yang belum mendapat sertifikat:</h3></div>
    <div class="table-responsive table--no-card m-b-30">
        <table id="nocertificates" class="table table-borderless table-striped table-earning">
            <thead>
            <tr>
                <th>created at</th>
                <th>Kompetensi Training</th>
                <th>Nama Training</th>
                <th>Nama Karyawan</th>
                @if($userinfo['user_role']==1)
                    <th>Action</th>
                @endif
            </tr>
            </thead>
        </table>
    </div>

    <script>
        var user_role = <?=$userinfo['user_role']?>;
        if(user_role == 1) { // Datatable Supe Admin
            var data = $('#nocertificates').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('certificate.noCertificateDatatables') }}",
                columns: [
                    {data: 'created_at'},
                    {data: 'competency'},
                    {data: 'training_name'},
                    {data: 'karyawan_name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        }

        if(user_role == 2){ // Datatables HRD
            var data = $('#nocertificates').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('certificate.noCertificateDatatables') }}",
                columns: [
                    {data: 'created_at'},
                    {data: 'competency'},
                    {data: 'training_name'},
                    {data: 'karyawan_name'},
                ]
            });
        }

        $(document).ready(function() {
            $('#certificates').on('click', '.delete', function(){
                var confirm = window.confirm('Delete module: ' + $(this).data('name') + ' ?');
                var el = this;
                var dataid = $(this).data('id');
                if (confirm){

                    $.ajax({
                        method: 'post',
                        url: '{{ route('certificate.delete') }}',
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

                            $('#certificates').DataTable().ajax.reload();

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

@section('content_certificate')
    <div class="table-responsive table--no-card m-b-30">
        <table id="certificates" class="table table-borderless table-striped table-earning">
            <thead>
            <tr>
                <th>created at</th>
                <th>Kompetensi Training</th>
                <th>Nama Training</th>
                <th>Nama Karyawan</th>
                <th>File Certificate</th>
                @if($userinfo['user_role']==1)
                    <th>Action</th>
                @endif
            </tr>
            </thead>
        </table>
    </div>
    <script>
        var user_role = <?=$userinfo['user_role']?>;
        if(user_role == 1) { // Datatable Supe Admin
            var table = $('#certificates').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('certificate.datatables') }}",
                order: [[0, 'desc']],
                columns: [
                    {data: 'created_at'},
                    {data: 'competency'},
                    {data: 'training_name'},
                    {data: 'karyawan_name'},
                    {data: 'file_training', name: 'file_training', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        }
        if(user_role == 2){ // Datatables HRD
            var table = $('#certificates').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('certificate.datatables') }}",
                order: [[0, 'desc']],
                columns: [
                    {data: 'created_at'},
                    {data: 'competency'},
                    {data: 'training_name'},
                    {data: 'karyawan_name'},
                    {data: 'file_training', name: 'file_training', orderable: false, searchable: false}
                ]
            });
        }
    </script>
@endsection
