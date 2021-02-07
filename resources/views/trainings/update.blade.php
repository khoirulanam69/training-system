@extends('layouts.master')

@section('title')
    TWS Citra | Update Training Plan
@endsection

@section('content')
    <form action="{{route('training.update',$data->id)}}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="kode_training" class=" form-control-label">Kode Training</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="text" name="kode_training" placeholder="TRA001" class="form-control" required="" value="{{$data->kode_training}}">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="training_name" class=" form-control-label">Nama Training</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="text" name="training_name" placeholder="Training Administrasi" class="form-control" required="" value="{{$data->training_name}}">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="spectraining_id" class=" form-control-label">Spesifikasi Training</label>
            </div>
            <div class="col-12 col-md-9">
                <select name="spectraining_id" class="form-control" required="">
                    <option disabled="" value="">Please Select an Option</option>
                    @foreach($specs as $spec)
                        <option value="{{$spec->id}}" {{($data->spectraining_id == $spec->id)? "Selected = 'true'":""}} >{{$spec->traintypeid}} - {{$spec->traintype}} - {{$spec->competency}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="duration" class=" form-control-label">Durasi (Jam)</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="number" name="duration" placeholder="e.g. 10" class="form-control" required="" min="0" value="{{$data->duration}}">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="trainer_name" class=" form-control-label">Nama Trainer</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="text" name="trainer_name" placeholder="e.g. Wildan Wibowo" class="form-control" required="" value="{{ucfirst($data->trainer_name)}}">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="training_location" class=" form-control-label">Lokasi Training</label>
            </div>
            <div class="col-12 col-md-9">
                <textarea name="training_location" id="training_location" rows="9" placeholder="e.g. Jl. Widuro No. 94 Banyuwangi" class="form-control" required="">{{$data->training_location}}</textarea>
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="start_date" class=" form-control-label">Tanggal Mulai</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="date" id="start_date" name="start_date" placeholder="" class="form-control" required="" value="{{$data->start_date}}">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="end_date" class=" form-control-label">Tanggal Berakhir</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="date" id="end_date" name="end_date" placeholder="" class="form-control" required="" value="{{$data->end_date}}">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="status" class=" form-control-label">Status</label>
            </div>
            <div class="col-12 col-md-9">
                <select name="status" id="status" class="form-control">
                    <?php
                    $status = array(
                        'Belum Berlangsung' => 1,
                        'Sedang Berlangsung' => 2,
                        'Sudah Berlangsung' => 3,
                    );
                    ?>
                    @foreach($status as $sts => $key)
                        <option value="<?=$key?>" @if($data->status == $key) selected @endif ><?=$sts?></option>
                    @endforeach
                </select>
            </div>
        </div>
        <hr>
        <div class="row form-group">
            <div class="col-12 col-md-3">
                <button type="submit" class="btn btn-submit btn-primary ladda-button btn-block">Update</button>
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