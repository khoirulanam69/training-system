@extends('layouts.master')

@section('title')
    TWS Citra | Report Training
@endsection

<?php $userinfo = Session::get('userinfo'); ?>
@section('content')
    <form id="ereport" href="#">
        <div class="row form-group align-items-center">
            <div class="col-md-3">
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
            <div class="col-md-3">
                <select id="jabatanID" name="jabatanID" class="form-control">
                    <option value="">All Position</option>
                    @foreach($jabatan as $tr)
                        <option value="{{$tr->id}}">{{$tr->code}} - {{$tr->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 float-lg-right">
                <input type="number" id="report_date" name="report_date" placeholder="Year" class="form-control"  >
            </div>
            <div class="col-md-2 float-lg-right">
                <input id="karyawan_name" name="karyawan_name" class="form-control" placeholder="All Karyawan">
            </div>
            <div class="col-md-2 float-lg-right">
                <button class="mb-2 btn btn-sm btn-primary filterby">Filter</button>
                <button class="mb-2 btn btn-sm btn-success refresh ml-1">Refresh</button>
            </div>
        </div>
    </form>
    <div class="table-responsive m-b-40 ">
        <table id="reports" class="table table-striped table-earning">
            <thead>
            <tr>
                <th>created at</th>
                <th>Grade</th>
                <th>Kompetensi</th>
                <th>Nama Training</th>
                <th>Trainer</th>
                <th>Lokasi</th>
                <th>Tanggal Mulai</th>
                <th>Standard</th>
                <th>Nama Karyawan</th>
                <th>Nilai</th>
                <th>Status</th>
                <th>Jumlah Karyawan</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>
    </div>
    <script>
        let batchMode = false;
        $(document).ready(function(){
            let customColumn =
            [
                {data: 'created_at'},
                {data: 'grade'},
                {data: 'competency'},
                {data: 'training_name'},
                {data: 'trainer_name'},
                {data: 'training_location'},
                {data: 'start_date'},
                {data: 'standard'},
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
                {data: 'jumlah_karyawan', render:function(data){
                    if(data>0){
                        return data
                    }
                    return ""
                }},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]

            $('.refresh').on('click', function(e){
                e.preventDefault();
                $('#jabatanID').val("");
                $('#report_date').val("");
                $('#karyawan_name').val("");
                    batchMode = false;
                    $('#reports').DataTable().column(10).visible(false)
                    $('#reports').DataTable().column(7).visible(true)
                    $('#reports').DataTable().column(8).visible(true)
                    $('#reports').DataTable().column(9).visible(true)
                var table = $('#reports').DataTable();
                table.draw(true);
            });
            $('.filterby').on('click', function(e){
                e.preventDefault();
                if( $('#karyawan_name').val() == ""){
                    batchMode = true;
                    $('#reports').DataTable().column(10).visible(true)
                    $('#reports').DataTable().column(7).visible(false)
                    $('#reports').DataTable().column(8).visible(false)
                    $('#reports').DataTable().column(9).visible(false)
                }else{
                    batchMode = false;
                    $('#reports').DataTable().column(10).visible(false)
                    $('#reports').DataTable().column(7).visible(true)
                    $('#reports').DataTable().column(8).visible(true)
                    $('#reports').DataTable().column(9).visible(true)
                }
                // var cusCol = table.column( $('.cus_col').attr('data-column') );
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
                    url: '{{ route("reports.datatables") }}',
                    type:'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    },
                    data: function (d) {
                        d.departmentID = $('#departmentID').val();
                        d.jabatanID = $('#jabatanID').val();
                        d.report_date = $('#report_date').val();
                        d.karyawan_name = $('#karyawan_name').val();
                        d.batchMode = batchMode == true ? "true": "false";
                    }
                },
                columns: customColumn,
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    title: file_title,
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 10 ]
                    },
                    messageTop: 'TRAINING RESULT REPORT'
                },
                {
                    extend: 'pdf',
                    orientation: 'landscape',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 10 ]
                    },
                    title: file_title,
                    messageTop: 'TRAINING RESULT REPORT'
                },
                {
                    extend: 'print',
                    orientation: 'landscape',
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    },
                    messageTop: 'TRAINING RESULT REPORT DATE :'+today
                }
                ],
                "columnDefs": [
                    {
                        "targets": [ 7 ],
                        "visible": batchMode ? false: true
                    },
                    {
                        "targets": [ 8 ],
                        "visible": batchMode ? false: true
                    },
                    {
                        "targets": [ 9 ],
                        "visible": batchMode ? false: true
                    },
                    {
                        "targets": [ 10 ],
                        "visible": batchMode ? true: false
                    }
                ]
            });
        });

    </script>
@endsection
