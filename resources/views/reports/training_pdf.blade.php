<!DOCTYPE html>
<html>
    <head>
        <title>Training Report</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <body>
        <style type="text/css">
            table tr td,
            table tr th{
                font-size: 12pt;
            }
        </style>
        <div class = "float-md-right">
            <p>Date : {{date('d/m/Y')}}</p>
        </div>
        <div class="card-body card-block">
            <center>
                <h1>Training Report</h1>
            </center>
            <h5><strong>Training Detail</strong></h5>

            <table class='table table-borderless'>
                <tr>
                    <td>ID</td>
                    <td>:</td>
                    <td>{{$training->trainingID}}</td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td>:</td>
                    <td>{{$training->training_name}}</td>
                </tr>
                <tr>
                    <td>Trainer</td>
                    <td>:</td>
                    <td>{{$training->trainer_name}}</td>
                </tr>
                <tr>
                    <td>Location</td>
                    <td>:</td>
                    <td>{{$training->training_location}}</td>
                </tr>
                <tr>
                    <td>Start Date</td>
                    <td>:</td>
                    <td>{{$training->start_date}}</td>
                </tr>
                <tr>
                    <td>Competency Standard</td>
                    <td>:</td>
                    <td>{{$training->standard}}</td>
                </tr>
            </table>
            <hr>
            <h5><strong>Result</strong></h5>
            <table class='table table-bordered'>
                <thead>
                <tr>
                    <th>No</th>
                    <th>NIK</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Score</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @php $i=1 @endphp
                @foreach($users as $user)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{$user->nik}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->department_name}}</td>
                        <td>{{$user->score}}</td>
                        <td>
                            @if($user->status == 1)
                                Lulus
                            @else
                                Tidak Lulus
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </body>
</html>
