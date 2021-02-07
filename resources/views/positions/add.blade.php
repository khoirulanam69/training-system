@extends('layouts.master')

@section('title')
    TWS Citra | Create Position
@endsection

@section('content')
    <form action="{{route('position.add')}}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="name" class=" form-control-label">Name</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="text" id="name" name="name" placeholder="e.g. Developer" class="form-control">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="code" class=" form-control-label">Code</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="text" name="code" placeholder="e.g. DEV" class="form-control">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="department" class=" form-control-label">Department</label>
            </div>
            <div class="col-12 col-md-9">
                <select name="departmentID" class="form-control">
                    <option value="">Please Select One</option>
                    @foreach($departments as $department)
                        <option value="{{$department->id}}">{{$department->name}}</option>
                        @endforeach
                </select>
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="grade" class=" form-control-label">Grade</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="text" id="grade" name="grade" placeholder="e.g. NOVICE" class="form-control">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="grade_code" class=" form-control-label">Grade Code</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="text" name="grade_code" placeholder="e.g. NOV" class="form-control">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="competency" class=" form-control-label">Competency</label>
            </div>
            <div class="col-12 col-md-9">
                <textarea class="form-control" name="competency" placeholder="Type here..." rows="3"></textarea>
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

    </script>
@endsection
