<?php

namespace App\Http\Controllers\Backend;

use App\Models\Department;
use App\Models\Position;
use App\Models\Training;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use DB;
use DataTables;
use PDF;
use Response;

class ReportController extends Controller
{
    public function index()
    {

        $userinfo = Session::get('userinfo');

        if (!in_array($userinfo['user_role'], array('1', '2', '3'))) {
            return redirect('/home/dashboard');
        }

        $departments = Department::all();
        $position = Position::all();

        // Session::flash('mode', 'error');
        $userDep = DB::table('users as u')
            ->select('d.id as id', 'd.name as name', 'd.code as code')
            ->where('u.id', $userinfo['user_id'])
            ->join('departments as d', 'd.id', '=', 'u.departmentID')
            ->get();

        $datas = array(
            'title' => 'Laporan Training',
            'slug' => 'reports',
            'departments' => $departments,
            'jabatan' => $position,
            'user_department' => count($userDep) > 0 ? $userDep[0] : []
        );
        return view('reports.index', $datas);
    }

    public function datatable(Request $request)
    {

        $userinfo = Session::get('userinfo');

        if (!$userinfo) {
            return redirect()->route('login');
        }

        if ($request->ajax()) {
            $departmentID = $request->departmentID;
            $jabatanID = $request->jabatanID;
            $report_date = $request->report_date;
            $karyawan_name = $request->karyawan_name;

            $reportQuery = DB::table('trainingresult as t');

            if ($departmentID) {
                $reportQuery->whereRaw("u.departmentID ='" . $departmentID . "' ");
            }

            if ($jabatanID) {
                $reportQuery->whereRaw("u.jabatanID ='" . $jabatanID . "' ");
            }

            if ($report_date) {
                $reportQuery->whereRaw("YEAR(t.created_at) ='" . $report_date . "' ");
            }

            if ($karyawan_name) {
                $reportQuery->where('u.name', 'like', '%' . $karyawan_name . '%');
            }

            if ($request->batchMode == "true") {
                $data = $reportQuery->select(array('t.*', 's.grade as grade', 's.competency as competency', 't.trainingID as training_id', 'r.training_name', 'r.trainer_name', 'r.training_location', 'r.start_date', 's.standard', 'u.name as karyawan_name', 't.score', 'u.id as karyawan_id', DB::raw('count(*) as jumlah_karyawan')))
                    ->leftjoin('spectrains as s', 't.specstrainID', '=', 's.id')
                    ->leftjoin('users as u', 't.userID', '=', 'u.id')
                    ->leftjoin('trainings as r', 't.trainingID', '=', 'r.id')
                    ->groupBy('t.trainingID')
                    ->get();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $btn = '<a href="/home/report/printpdftraining/' . $row->training_id . '" data-toggle="tooltip"  data-original-title="Download" class="edit btn btn-success btn-sm">Download</a>';

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            } else {
                $data = $reportQuery->select('t.*', 's.grade as grade', 's.competency as competency', 'r.training_name', 'r.trainer_name', 'r.training_location', 'r.start_date', 's.standard', 'u.name as karyawan_name', 't.score', 'u.id as karyawan_id')
                    ->leftjoin('spectrains as s', 't.specstrainID', '=', 's.id')
                    ->leftjoin('users as u', 't.userID', '=', 'u.id')
                    ->leftjoin('trainings as r', 't.trainingID', '=', 'r.id')
                    ->get();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $btn = '<a href="/home/report/printpdfkaryawan/' . $row->karyawan_id . '" data-toggle="tooltip"  data-original-title="Download" class="edit btn btn-primary btn-sm">Download</a>';

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        }
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
