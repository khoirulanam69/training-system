@extends('layouts.master')

@section('title')
    TWS Citra | Module List
@endsection

@section('content')
    <a href="{{route('position.add')}}" type="submit" class="btn btn-primary btn-sm mb-2">
        <i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Data
    </a>
    <div class="table-responsive table--no-card m-b-30">
        <table id="positions" class="table table-borderless table-striped table-earning">
            <thead>
            <tr>
                <th>created at</th>
                <th>ID</th>
                <th>Name</th>
                <th>Code</th>
                <th>Grade</th>
                <th>Grade Code</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>
    </div>
    <script>
        var table = $('#positions').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('position.datatables') }}",
            order: [[0, 'desc']],
            columns: [
                {data: 'created_at'},
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'code', name: 'code'},
                {data: 'grade', name: 'grade'},
                {data: 'grade_code', name: 'grade_code'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        $(document).ready(function() {
            $('#positions').on('click', '.delete', function(){
                var confirm = window.confirm('Delete posiiton: ' + $(this).data('name') + ' ?');
                var el = this;
                var dataid = $(this).data('id');
                if (confirm){

                    $.ajax({
                        method: 'post',
                        url: '{{ route('position.delete') }}',
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

                            $('#positions').DataTable().ajax.reload();

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
