@extends('layouts.master')

@section('title')
    TWS Citra | Department List
@endsection

@section('content')
    <a href="{{route('department.add')}}" type="submit" class="btn btn-primary btn-sm mb-2">
        <i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Data
    </a>
    <div class="table-responsive table--no-card m-b-30">
        <table id="departments" class="table table-borderless table-striped table-earning">
            <thead>
            <tr>
                <th>created at</th>
                <th>ID</th>
                <th>Name</th>
                <th>Code</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>
    </div>
    <script>
        var table = $('#departments').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('department.datatables') }}",
            order: [[0, 'desc']],
            columns: [
                {data: 'created_at'},
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'code', name: 'code'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        $(document).ready(function() {
            $('#departments').on('click', '.delete', function(){
                var confirm = window.confirm('Delete department: ' + $(this).data('name') + ' ?');
                var el = this;
                var dataid = $(this).data('id');
                if (confirm){

                    $.ajax({
                        method: 'post',
                        url: '{{ route('department.delete') }}',
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

                            $('#departments').DataTable().ajax.reload();

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
