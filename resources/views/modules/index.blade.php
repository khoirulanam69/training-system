@extends('layouts.master')

@section('title')
    TWS Citra | Module List
@endsection
@section('cssInject')
    .underlined:hover{
        text-decoration: underline !important;
    }
@endsection
@section('content')
    <?php $userinfo = Session::get('userinfo'); ?>
    <a href="{{route('module.add')}}" type="submit" class="btn btn-primary btn-sm">
        <i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Data
    </a>
    <div class="table-responsive table--no-card my-4">
        <table id="modules" class="table table-borderless table-striped table-earning">
            <thead>
            <tr>
                <th>created at</th>
                <th>Kompetensi Training</th>
                <th>Nama Training</th>
                <th>URL Video Youtube</th>
                <th>File Training</th>
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
            var table = $('#modules').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('module.datatables') }}",
                order: [[0, 'desc']],
                columns: [
                    {data: 'created_at'},
                    {data: 'competency'},
                    {data: 'training_name'},
                    {data: 'urlvideo',  render:function(data){
                        if(data == ""){
                            return '<span class="badge badge-pill badge-secondary">No URL video</span>'
                        }else{
                            return '<a class="underlined" href="/home/module/redirect?cb=' + data + '" target="_blank">'+ data +'</a>'
                        }
                    }},
                    {data: 'file_training', name: 'file_training', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        }

        if(user_role == 2){ // Datatables HRD
            var table = $('#modules').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('module.datatables') }}",
                order: [[0, 'desc']],
                columns: [
                    {data: 'created_at'},
                    {data: 'competency'},
                    {data: 'training_name'},
                    {data: 'urlvideo',  render:function(data){
                        if(data == ""){
                            return '<span class="badge badge-pill badge-secondary">No URL video</span>'
                        }else{
                            return '<a class="underlined" href="/home/module/redirect?cb=' + data + '" target="_blank">'+ data +'</a>'
                        }
                    }},
                    {data: 'file_training', name: 'file_training', orderable: false, searchable: false},
                ]
            });
        }

        $(document).ready(function() {
            $('#modules').on('click', '.delete', function(){
                var confirm = window.confirm('Delete module: ' + $(this).data('name') + ' ?');
                var el = this;
                var dataid = $(this).data('id');
                if (confirm){

                    $.ajax({
                        method: 'post',
                        url: '{{ route('module.delete') }}',
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

                            $('#modules').DataTable().ajax.reload();

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
