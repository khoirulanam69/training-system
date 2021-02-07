<?php

namespace App\Http\Controllers\Backend;

use App\Models\Department;
use App\Models\Position;
use App\Models\Training;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Spectrain;
use App\Models\Trainingres;
use App\Models\User;
use Session;
use DB;
use DataTables;
use PDF;
use Response;

class MatriksController extends Controller
{
    public function index()
    {

        $userinfo = Session::get('userinfo');

        if (!in_array($userinfo['user_role'], array('1'))) {
            return redirect('/home/dashboard');
        }

        $departments = Department::all();
        $position = Position::all();

        $userDep = DB::table('users as u')
            ->select('d.id as id', 'd.name as name', 'd.code as code')
            ->where('u.id', $userinfo['user_id'])
            ->join('departments as d', 'd.id', '=', 'u.departmentID')
            ->get();

        $datas = array(
            'title' => 'Matriks Training Karyawan',
            'slug' => 'matriks',
            'departments' => $departments,
            'jabatan' => $position,
            'user_department' => count($userDep) > 0 ? $userDep[0] : []
        );
        return view('matriks.index', $datas);
    }

    public function datatable(Request $request)
    {

        $userinfo = Session::get('userinfo');

        if (!$userinfo) {
            return redirect()->route('login');
        }

        $departmentID = $request->departmentID;
        $jabatanID = $request->jabatanID;
        $report_date = $request->report_date;
        $karyawan_name = $request->karyawan_name;

        $reportQuery = DB::table('users as u');

        if ($departmentID) {
            $reportQuery->whereRaw("u.departmentID ='" . $departmentID . "' ");
        }

        if ($jabatanID) {
            $reportQuery->whereRaw("u.jabatanID ='" . $jabatanID . "' ");
        }

        if ($report_date) {
            $reportQuery->whereRaw("YEAR(u.created_at) ='" . $report_date . "' ");
        }

        if ($karyawan_name) {
            $reportQuery->where('u.name', 'like', '%' . $karyawan_name . '%');
        }

        $data = $reportQuery->select('u.id as userId', 'u.name as userName', 'u.nik as userNIK', 'd.code as departmentCode', 'd.name as departmentName', 'p.name as positionName', 'u.created_at')
            ->leftjoin('departments as d', 'd.id', '=', 'u.departmentID')
            ->leftjoin('positions as p', 'p.id', '=', 'u.jabatanID')
            ->where('u.roleID', '>', '1')
            ->groupBy('u.id')
            ->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $btn = '<a href="/home/matriks/' . $row->userId . '" data-toggle="tooltip"  data-original-title="Detail" class="edit btn btn-primary btn-sm">Detail</a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show($id, Request $request)
    {

        $userinfo = Session::get('userinfo');

        if (!$userinfo) {
            return redirect()->route('login');
        }

        $departmentID = $request->departmentID;
        $jabatanID = $request->jabatanID;
        $report_date = $request->report_date;
        $karyawan_name = $request->karyawan_name;

        $reportQuery = DB::table('users as u');

        if ($departmentID) {
            $reportQuery->whereRaw("u.departmentID ='" . $departmentID . "' ");
        }

        if ($jabatanID) {
            $reportQuery->whereRaw("u.jabatanID ='" . $jabatanID . "' ");
        }

        if ($report_date) {
            $reportQuery->whereRaw("YEAR(u.created_at) ='" . $report_date . "' ");
        }

        if ($karyawan_name) {
            $reportQuery->where('u.name', 'like', '%' . $karyawan_name . '%');
        }

        $data = $reportQuery->select('u.*', 'd.code as departmentCode', 'm.mediapath', 'd.name as departmentName', 'p.name as positionName')
            ->leftjoin('departments as d', 'd.id', '=', 'u.departmentID')
            ->leftjoin('positions as p', 'p.id', '=', 'u.jabatanID')
            ->leftjoin('media as m', 'u.mediaID', '=', 'm.id')
            ->where('u.id', '=', $id)
            ->groupBy('u.id')
            ->first();


        $datas = array(
            'title' => 'Detail Training Karyawan',
            'slug' => 'matriks',
            'data'  =>  $data,
        );

        // return $datas;
        return view('matriks.show', $datas);
    }

    public function datatableTrained($id, Request $request)
    {

        $userinfo = Session::get('userinfo');

        if (!$userinfo) {
            return redirect()->route('login');
        }

        $tr = Training::with(['training_result' => function ($result) use ($id) {
            $result->where('userID', $id);
        }])->get();

        $trainings = [];
        $training_result = [];

        foreach ($tr as $key => $t) {

            if (count($t->training_result) > 0) {

                $training_result[] = $t;
            } else {
                $trainings[] = $t;
            }
        }

        // return $data;

        return Datatables::of($training_result)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                if ($row->training_result[0]->certificateID !== null) {
                    $btn = '✓';
                } else {
                    $btn = '-';
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        // }
    }

    public function datatableTrain($id, Request $request)
    {

        $userinfo = Session::get('userinfo');

        if (!$userinfo) {
            return redirect()->route('login');
        }

        $trains = DB::table('trainings')
            ->join('assignment', 'trainings.id', '=', 'assignment.trainingID')
            ->where('assignment.karyawanID', $id)
            ->get();
        $trainres = DB::table('trainings')
            ->join('trainingresult', 'trainings.id', '=', 'trainingresult.trainingID')
            ->where('trainingresult.userID', $id)
            ->get();

        $trainingres = [];
        foreach ($trainres as $tr) {
            $trainingres[] = $tr->kode_training;
        }

        $result = [];
        foreach ($trains as $t) {
            if (in_array($t->kode_training, $trainingres)) {
                false;
            } else {
                $result[] = $t;
            }
        }

        return Datatables::of($result)
            ->addIndexColumn()
            ->addColumn('value', function () {
                return $value = 0;
            })
            ->make(true);
    }

    public function datatableTrainings($id, Request $request)
    {

        $userinfo = Session::get('userinfo');

        if (!$userinfo) {
            return redirect()->route('login');
        }

        // New Algorithm

        $karyawan = User::where('id', $id)->first();
        $spectrains = Spectrain::all();
        $trainingres = Trainingres::where('userID', $karyawan['id'])->get();

        $requiredtrain = [];
        $reqID = [];
        $p = 0;
        foreach ($spectrains as $spec) {
            $position = unserialize($spec['positions']);
            if ($position[0]['id'] == $karyawan['jabatanID']) {
                if (!empty($spec['important_aspect'])) {
                    $aspect_array = unserialize($spec['important_aspect']);
                    $requiredtrain[$p] = $spec;
                    $reqID[] = $spec['id'];
                    $requiredtrain[$p]['important_aspect'] = implode(', ', (array) $aspect_array);
                } else {
                    $requiredtrain[$p]['important_aspect'] = "";
                }
            }
            $p++;
        }

        $trainID = [];
        foreach ($trainingres as $train) {
            $trainID[] = $train['specstrainID'];
        }

        $diff = array_diff($reqID, $trainID);

        $result = [];
        foreach ($requiredtrain as $reqtrain) {
            for ($i = 0; $i < count($diff) - 1; $i++) {
                if (!empty($diff[$i])) {
                    if ($reqtrain['id'] == $diff[$i]) {
                        $result[] = $reqtrain;
                    }
                }
            }
        }

        return Datatables::of($result)
            ->addIndexColumn()
            ->make(true);
    }

    public function printPdfKaryawan(Request $request, $id)
    {
        $result = DB::table('trainingresult as t')
            ->select('t.*', 's.competency as competency', 'r.training_name', 'r.trainer_name', 'r.training_location', 'r.start_date', 's.standard', 'u.name as karyawan_name', 't.score')
            ->leftjoin('spectrains as s', 't.specstrainID', '=', 's.id')
            ->leftjoin('users as u', 't.userID', '=', 'u.id')
            ->leftjoin('trainings as r', 't.trainingID', '=', 'r.id')
            ->where('userID', $id)
            ->get();

        $user = DB::table('users as u')
            ->where('u.id', $id)
            ->join('roles as r', 'u.roleID', '=', 'r.id')
            ->leftjoin('media as m', 'u.mediaID', '=', 'm.id')
            ->leftjoin('departments as d', 'u.departmentID', '=', 'd.id')
            ->leftjoin('positions as p', 'u.jabatanID', '=', 'p.id')
            ->select('u.*', 'm.mediapath', 'r.name as role_name', 'd.name as department_name', 'd.code as department_code', 'p.name as position_name')
            ->first();

        $training = DB::table('assignment as a')
            ->select(
                'a.*',
                's.traintype as specsname',
                's.competency as competency',
                's.standard as standard',
                't.kode_training as kode_training',
                't.start_date as start_date',
                't.duration as duration',
                't.trainer_name as trainer_name',
                't.training_location as location'
            )
            ->where('a.karyawanID', $id)
            ->leftjoin('trainings as t', 'a.trainingID', '=', 't.id')
            ->leftjoin('spectrains as s', 't.spectraining_id', '=', 's.id')
            ->get();

        $pdf = PDF::loadview(
            'reports.karyawan_pdf',
            [
                'result' => $result,
                'user' => $user,
                'trainings' => $training
            ]
        );
        return $pdf->download('laporan-pegawai.pdf');
    }

    public function printPdfTraining(Request $request, $id)
    {
        $userinfo = Session::get('userinfo');

        if (!$userinfo) {
            return redirect()->route('login');
        }
        $trainingInfo = DB::table('trainingresult as t')
            ->select('t.specstrainID', 't.trainingID', 't.certificateID', 's.competency as competency', 'r.training_name', 'r.trainer_name', 'r.training_location', 'r.start_date', 's.standard')
            ->leftjoin('spectrains as s', 't.specstrainID', '=', 's.id')
            ->leftjoin('trainings as r', 't.trainingID', '=', 'r.id')
            ->where('t.trainingID', $id)
            ->first();

        $user = DB::table('trainingresult as t')
            ->select('u.*', 't.score as score', 'd.name as department_name', 'p.name as position_name')
            ->leftjoin('users as u', 't.userID', '=', 'u.id')
            ->leftjoin('departments as d', 'u.departmentID', '=', 'd.id')
            ->leftjoin('positions as p', 'u.jabatanID', '=', 'p.id')
            ->where('t.trainingID', $id)
            ->get();

        $pdf = PDF::loadview(
            'reports.training_pdf',
            [
                'training' => $trainingInfo,
                'users' => $user
            ]
        );
        return $pdf->download('laporan-training.pdf');
    }
}
