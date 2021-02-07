@extends('layouts.master')

@section('title')
    TWS Citra | Report Training
@endsection

<?php $userinfo = Session::get('userinfo'); ?>
@section('content')
    <form id="ereport" href="#">
        <div class="row form-group align-items-center">
            <div class="col col-md-3">
                <select id="departmentID" name="departmentID" class="form-control">
                    @if( $userinfo['user_role'] == "3")
                        <option value="{{$user_department->id}}">{{$user_department->code}} - {{$user_department->name}}</option>
                    @else
                        <option value="">All Department</option>
                        @foreach($departments as $dpt)
                            <option value="{{$dpt->id}}">{{$dpt->code}} - {{$dpt->name}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col col-md-3">
                <select id="jabatanID" name="jabatanID" class="form-control">
                    <option value="">All Position</option>
                    @foreach($jabatan as $tr)
                        <option value="{{$tr->id}}">{{$tr->code}} - {{$tr->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col col-md-2 float-lg-right">
                <input id="karyawan_name" name="karyawan_name" class="form-control" placeholder="All Karyawan">
            </div>
            <div class="col col-md-2 float-lg-right">
                <button class="mb-2 btn btn-sm btn-primary filterby">Filter</button>
            </div>
        </div>
    </form>
    <div class="table-responsive m-b-40 ">
        <table id="reports" class="table table-striped table-earning">
            <thead>
            <tr>
                {{-- <th>No.</th> --}}
                <th>created at</th>
                <th>Nama</th>
                <th>NIK</th>
                <th>Departemen</th>
                <th>Jabatan</th>
                <th>Opsi</th>
            </tr>
            </thead>
        </table>
    </div>
    <script>
        $(document).ready(function(){
            let customColumn =
            [
                {data: 'created_at'},
                {data: 'userName'},
                {data: 'userNIK'},
                {data: null,
                    render: function (data) {
                        let departmentCode = data.departmentCode == null? '' : data.departmentCode
                        let departmentName = data.departmentName == null? '' : data.departmentName
                        return departmentCode + " - " + departmentName;
                    }
                },
                {data: 'positionName'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]

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
            var table = $('#reports').dataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{{ route("matriks.datatables") }}',
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
                columns: customColumn
            });
        });

    </script>
@endsection
