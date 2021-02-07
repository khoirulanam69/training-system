@extends('layouts.master')

@section('title')
    TWS Citra | Add Training Plan
@endsection

@section('content')
    <form action="{{route('training.add')}}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="kode_training" class=" form-control-label">Kode Training</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="text" name="kode_training" placeholder="e.g. TRA001" class="form-control" required="">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="training_name" class=" form-control-label">Nama Training</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="text" name="training_name" placeholder="e.g. Training Manajer" class="form-control" required="">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="spectraining_id" class=" form-control-label">Spesifikasi Training</label>
            </div>
            <div class="col-12 col-md-9">
                <select name="spectraining_id" class="form-control" required="">
                    <option disabled="" selected="" value=""> -- select an option -- </option>
                    @foreach($specs as $spec)
                        <option value="{{$spec->id}}">{{$spec->traintypeid}} - {{$spec->traintype}} - {{$spec->competency}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="duration" class=" form-control-label">Durasi (Jam)</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="number" name="duration" placeholder="e.g. 10" class="form-control" required="" min="0">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="trainer_name" class=" form-control-label">Nama Trainer</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="text" name="trainer_name" placeholder="e.g. Wildan Wibowo" class="form-control" required="">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="training_location" class=" form-control-label">Lokasi Training</label>
            </div>
            <div class="col-12 col-md-9">
                <textarea name="training_location" id="training_location" rows="9" placeholder="Jl. Widuri No.93 Banyuwangi" class="form-control" required=""></textarea>
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="start_date" class=" form-control-label">Tanggal Mulai</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="date" id="start_date" name="start_date" placeholder="10-30-2019" class="form-control" required="">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="end_date" class=" form-control-label">Tanggal Berakhir</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="date" id="end_date" name="end_date" placeholder="10-30-2019" class="form-control" required="">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="status" class=" form-control-label">Status</label>
            </div>
            <div class="col-12 col-md-9">
                <select name="status" id="status" class="form-control">
                    <option disabled="" selected="" value=""> -- select an option -- </option>
                    <option value="1">Belum Berlangsung</option>
                    <option value="2">Sedang Berlangsung</option>
                    <option value="3">Sudah Berlangsung</option>
                </select>
            </div>
        </div>
        <hr>
        <div class="row form-group">
            <div class="col-12 col-md-3">
                <button type="submit" class="btn btn-submit btn-primary ladda-button btn-block">Create</button>
            </div>
        </div>
        {{csrf_field()}}
    </form>
    <script>
        // var today = new Date();
        // var dd = today.getDate();
        // var mm = today.getMonth()+1; //January is 0!
        // var yyyy = today.getFullYear();
        // if(dd<10){
        //     dd='0'+dd
        // }
        // if(mm<10){
        //     mm='0'+mm
        // }
        //
        // today = yyyy+'-'+mm+'-'+dd;
        // document.getElementById("start_date").setAttribute("min", today);
        // document.getElementById("end_date").setAttribute("min", today);

    </script>
@endsection
