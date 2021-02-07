@extends('layouts.master')

@section('title')
    TWS Citra | Users List
@endsection

@section('content')
    <?php $userinfo = Session::get('userinfo');?>

    @if($userinfo['user_role']==1)
        <a href="{{route('user.addUser')}}" class="btn btn-primary btn-sm" style="margin-bottom: 15px">
            <i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Data
        </a>
        @if($userinfo['user_role']==1)
        @if (empty($position[0]->id))
        <button data-toggle="modal" data-target="#uploadExcelModal" class="btn btn-primary btn-sm" style="margin-bottom: 15px" disabled>
            <i class="fa fa-plus-circle" aria-hidden="true"></i> Import
        </button>
        <span style="color: red;" class="ml-3">Harap isi tabel Jabatan dan Departemen terlebih dahulu</span>
        @else
        <button data-toggle="modal" data-target="#uploadExcelModal" class="btn btn-primary btn-sm" style="margin-bottom: 15px">
            <i class="fa fa-plus-circle" aria-hidden="true"></i> Import
        </button>
        @endif
        @endif
    @endif

    <div class="table-responsive table--no-card m-b-30">
        <table id="users" class="table table-borderless table-striped table-earning data-table">
            <thead>
            <tr>
                <th>created at</th>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>
    </div>
    <script>
        var table = $('#users').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('user.datatables') }}",
            order: [[0, 'desc']],
            columns: [
                {data: 'created_at'},
                {data: 'id'},
                {data: 'name'},
                {data: 'email'},
                {data: 'phone'},
                {data: 'rolename'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $(document).ready(function() {

            $('#importexcel').on('click', function(){
                $('#formupload').find('input[name=excel]').val("");
            });

            $('#users').on('click', '.deleteUser', function(){
                var confirm = window.confirm('Delete user: ' + $(this).data('name') + ' ?');

                if (confirm){

                    var el = this;
                    var dataid = $(this).data('id');

                    $.ajax({
                        method: 'post',
                        url: '{{ route('user.delete') }}',
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
                            console.log(msg);
                            $('.mask-submitted-container').hide();
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
    <div class="modal fade" id="uploadExcelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" >
        <form id="formupload" action="{{ route('user.importexcel') }}"  method="post" enctype="multipart/form-data" class="form-horizontal">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mediumModalLabel">Import Excel</h5>
                        <button type="button" id="importexcel" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <a href="{{url('/sample/sample-user.xlsx')}}">Download Sample</a>
                            </div>
                        </div>
                        <div class="row">
                            <form enctype="multipart/form-data" action="{{route('user.importexcel')}}" method="post">
                                <div class="col-md-12 mb-4">
                                    {{ csrf_field() }}
                                    <input type="file" name="excel" id="excel">
                                </div>
                                <div class="col-md-12 mt-4">
                                    @if($userinfo['user_role'] == 1)
                                        <button class="btn btn-success" type="submit">Upload</button>
                                    @endif
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-12">
                            <div class="form-control col-md-12">
                                <p style="color: red;">NB:</p>
                                <p style="color: red;">  - Disarankan mengunakan sample file yang sudah disediakan.</p>
                                <p style="color: red;">  - Mohon cek kelengkapan data di excel sebelum upload, agar upload tidak gagal*</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{csrf_field()}}
        </form>
    </div>

@endsection
