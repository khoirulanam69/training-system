@extends('layouts.master')

@section('title')
    TWS Citra | Training Spesification List
@endsection

@section('content')
    <a href="{{route('spectrain.add')}}" type="submit" class="btn btn-primary btn-sm mb-2">
        <i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Data
    </a>
    <div class="table-responsive table--no-card m-b-30">
        <table id="spectrains" class="table table-borderless table-striped table-earning">
            <thead>
            <tr>
                <th>created at</th>
                <th>ID</th>
                <th>Position</th>
                <th>Grade</th>
                <th>Kompetensi</th>
                <th>Aspek Penting</th>
                <th>Training Yang Diperlukan</th>
                <th>Standar Kompetensi</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>
    </div>
    <script>
        var table = $('#spectrains').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('spectrain.datatables') }}",
            order: [[0, 'desc']],
            columns: [
                {data: 'created_at'},
                {data: 'traintypeid'},
                {data: 'positions'},
                {data: 'grade'},
                {data: 'competency'},
                {data: 'important_aspect'},
                {data: 'training_needed'},
                {data: 'standard'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
        });


        $(document).ready(function() {
            $('#spectrains').on('click', '.delete', function(){
                var confirm = window.confirm('Delete posiiton: ' + $(this).data('name') + ' ?');
                var el = this;
                var dataid = $(this).data('id');
                if (confirm){

                    $.ajax({
                        method: 'post',
                        url: '{{ route('spectrain.delete') }}',
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

                            $('#spectrains').DataTable().ajax.reload();

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
