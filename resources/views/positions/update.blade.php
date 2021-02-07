@extends('layouts.master')

@section('title')
    TWS Citra | Update Position
@endsection

@section('content')
    <form action="{{route('position.update',$data->id)}}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="name" class=" form-control-label">Name</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="hidden" name="id" class="form-control" required="true" value="<?=$data->id?>" >
                <input type="text" id="name" name="name" placeholder="e.g. John Depp" class="form-control" required="true" value="<?=$data->name?>" >
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="code" class=" form-control-label">Posision Code</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="text" id="code" name="code" placeholder="e.g. DIV" class="form-control" required="true" value="<?=$data->code?>">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="grade" class=" form-control-label">Grade</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="text" id="grade" name="grade" placeholder="e.g. NOVICE" class="form-control" required="true" value="<?=$data->grade?>">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="grade_code" class=" form-control-label">Grade Code</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="text" id="grade_code" name="grade_code" placeholder="e.g. NOV" class="form-control" required="true" value="<?=$data->grade_code?>">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="competency" class=" form-control-label">Competency</label>
            </div>
            <div class="col-12 col-md-9">
                <textarea class="form-control" name="competency" placeholder="Type here..." rows="3" required><?=$data->competency?></textarea>
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="department" class=" form-control-label">Department</label>
            </div>
            <div class="col-12 col-md-9">
                <select name="departmentID" class="form-control" required = "true">
                    <option value="">Please select one</option>
                    @foreach($departments as $department)
                        <option value="{{$department->id}}" {{($department->id == $data->departmentID)? "selected" : ""}}>{{$department->name}}</option>
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

    </script>
@endsection