@extends('layouts.master')

@section('title')
    TWS Citra | Penugasan Training
@endsection

@section('content')
    <div class="table-responsive table--no-card m-b-30">
        <table id="assignment" class="table table-borderless table-striped table-earning">
            <thead>
            <tr>
                <th>Kompetensi</th>
                <th>Kode Training</th>
                <th>Tanggal Mulai</th>
                <th>Durasi (Bulan)</th>
                <th>Trainer</th>
                <th>Lokasi</th>
                <th>Standar Kompetensi</th>
                <th>Status</th>
                <th>File Module</th>
                <th>URL Video Youtube</th>
            </tr>
            </thead>
        </table>
    </div>
    <script>
        var table = $('#assignment').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('assignment.datatables') }}",
            columns: [
                {data: 'competency'},
                {data: 'kode_training'},
                {data: 'start_date'},
                {data: 'duration'},
                {data: 'trainer_name'},
                {data: 'location'},
                {data: 'standard'},
                {data: null,
                    render: function () {
                        return 'Request Diterima';
                    }
                },
                {data: 'filemodule', name: 'filemodule', orderable: false, searchable: false},
                {data: 'urlvideo',  render:function(data){
                    if(data == ""){
                        return '<span class="badge badge-pill badge-secondary">No URL video</span>'
                    }else{
                        return '<a href="/home/module/redirect?cb=' + data + '" target="_blank">'+ data +'</a>'
                    }
                }},
            ]
        });
    </script>
@endsection
