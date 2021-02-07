<?php

namespace App\Http\Controllers\Backend;

use App\Models\Training;
use App\Models\User;
use App\Models\Spectrain;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\TrainPlanImport;
use DataTables;
use Session;
use Validator;
use DB;
use Excel;
use Importer;
use Exception;

class TrainingController extends Controller
{
    public function getDashboard()
    {

        $users = User::where(DB::raw('DAY(datebirth)'), '=', DB::raw('DAY(NOW())'))
            ->where(DB::raw('MONTH(datebirth)'), '=', DB::raw('MONTH(NOW())'))
            ->leftjoin('media as m', 'users.mediaID', '=', 'm.id')
            ->select('users.*', 'm.mediapath')
            ->get();

        $data = [
            'title' => 'Worker who celebrating birthday today',
            'slug' => 'dashboard',
            'users' => $users
        ];
        //return $data;
        return view('dashboard', $data);
    }

    public function index()
    {

        $userinfo = Session::get('userinfo');

        if (!in_array($userinfo['user_role'], array('1', '2'))) {
            return redirect('/home/dashboard');
        }

        $karyawan = User::whereIn('roleID', array(2, 3, 4))
            ->get();
        $spectrain = Spectrain::all();

        $datas = array(
            'title' => 'Training Plan List',
            'slug' => 'training_plan',
            'karyawans' => $karyawan,
            'spectrain' => $spectrain
        );
        return view('trainings.index', $datas);
    }

    public function datatable(Request $request)
    {

        $userinfo = Session::get('userinfo');
        if (!$userinfo) {
            return redirect()->route('login');
        }

        if ($request->ajax()) {
            if ($userinfo['user_role'] == 1) { //Super Admin
                $data = DB::table('trainings as t')
                    ->select('t.*', 's.traintypeid', 's.traintype', 's.competency')
                    ->leftjoin('spectrains as s', 't.spectraining_id', '=', 's.id')
                    ->get();
                $i = 0;
                foreach ($data as $dt) {
                    $data[$i]->karyawan = DB::table('assignment as a')
                        ->select('u.id as userid', 'u.name as username')
                        ->leftjoin('users as u', 'a.karyawanID', '=', 'u.id')
                        ->where('trainingID', $dt->id)
                        ->get();
                    $data[$i]->request_karyawan = DB::table('trainingreq as a')
                        ->select('u.id as userid', 'u.name as username')
                        ->leftjoin('users as u', 'a.requestby', '=', 'u.id')
                        ->whereIN('a.status', array('1', '5'))
                        ->where('trainingID', $dt->id)
                        ->get();
                    $i++;
                }

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn = '<span data-toggle="modal" data-name="' . $row->training_name . '" data-id="' . $row->id . '" data-target="#detailModal" data-original-title="Detail" class="btn btn-primary btn-sm penugasan mr-1">Penugasan</span>';

                        $btn = $btn . '<a href="training/edit/' . $row->id . '" data-toggle="tooltip" data-name="' . $row->kode_training . '"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm">Edit</a>';

                        $btn = $btn . ' <span  data-name="' . $row->kode_training . '" data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm delete">Delete</span>';

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            } elseif ($userinfo['user_role'] == 2) { //HRD
                $data = DB::table('trainings as t')
                    ->select('t.*', 's.traintypeid', 's.traintype', 's.competency')
                    ->leftjoin('spectrains as s', 't.spectraining_id', '=', 's.id')
                    ->get();
                $i = 0;
                foreach ($data as $dt) {
                    $data[$i]->karyawan = DB::table('assignment as a')
                        ->select('u.id as userid', 'u.name as username')
                        ->leftjoin('users as u', 'a.karyawanID', '=', 'u.id')
                        ->where('trainingID', $dt->id)
                        ->get();
                    $data[$i]->request_karyawan = DB::table('trainingreq as a')
                        ->select('u.id as userid', 'u.name as username')
                        ->leftjoin('users as u', 'a.requestby', '=', 'u.id')
                        ->whereIN('a.status', array('1', '5'))
                        ->where('trainingID', $dt->id)
                        ->get();
                    $i++;
                }

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $btn = '<span data-toggle="modal" data-name="' . $row->training_name . '" data-id="' . $row->id . '" data-target="#detailModal" data-original-title="Detail" class="btn btn-primary btn-sm penugasan">Penugasan</span>';

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        }
    }

    public function addTraining(Request $request)
    {
        $userinfo = Session::get('userinfo');

        if ($request->isMethod('GET')) {

            if (!in_array($userinfo['user_role'], array('1', '2'))) {
                return redirect('/home/dashboard');
            }
            //spesifikasi training
            $specs = Spectrain::all();

            $data = array(
                'title' => 'Create Training Plan',
                'slug' => 'training_plan',
                'specs' => $specs
            );

            return view('trainings.add', $data);
        }

        if ($request->isMethod('POST')) {
            // return $request->all();
            $response = $this->createTraining($request);
            if ($response['response']['success']) {
                Session::flash('success', 'Data created successfully');
                Session::flash('mode', 'success');
                //                return redirect()->route('training.index');
                return redirect()->back();
            } else {
                if ($response['response']['error']) {
                    Session::flash('error', 'Something wrong, Data created fail');
                    Session::flash('mode', 'error');
                    return redirect()->back()
                        ->withErrors($response['response']['error'])
                        ->withInput();
                } else {
                    Session::flash('error', 'Something wrong, Data created fail');
                    Session::flash('mode', 'error');
                    return redirect()->back();
                }
            }
        }
    }

    private function createTraining(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_training' => 'required|unique:trainings',
            'spectraining_id' => 'required',
        ]);

        if ($validator->fails()) {
            $return['response']['success'] = false;
            $return['response']['error'] = $validator;
        } else {

            $data = new Training();
            if ($request->exists('created_by')) {
                $data->created_by = $request->created_by;
                $data->approved_status = 0;
            } else {
                $data->approved_status = 1;
            }
            $data->kode_training = $request->kode_training;
            $data->training_name = $request->training_name;
            $data->spectraining_id = $request->spectraining_id;
            $data->duration = $request->duration;
            $data->trainer_name = $request->trainer_name;
            $data->training_location = $request->training_location;
            $data->start_date = $request->start_date;
            $data->end_date = $request->end_date;
            $data->status = $request->status;
            if ($data->save()) {
                $return['response']['success'] = true;
            } else {
                $return['response']['success'] = false;
            }
        }
        return $return;
    }

    public function updateTraining(Request $request, $id)
    {
        $userinfo = Session::get('userinfo');

        if (!in_array($userinfo['user_role'], array('1'))) {
            return redirect('/home/dashboard');
        }

        if ($request->isMethod('GET')) {
            //
            //spesifikasi training
            $training = Training::where('id', $id)
                ->first();
            $specs = Spectrain::all();

            $data = array(
                'title' => 'Update Training Plan',
                'slug' => 'training_plan',
                'data' => $training,
                'specs' => $specs
            );

            return view('trainings.update', $data);
        }

        if ($request->isMethod('POST')) {
            $response = $this->editTraining($request, $id);
            if ($response['response']['success']) {
                Session::flash('success', 'Data updated successfully');
                Session::flash('mode', 'success');
                return redirect()->route('training.update', $id);
            } else {
                if ($response['response']['error']) {
                    Session::flash('error', 'Something wrong, Data updated fail');
                    Session::flash('mode', 'error');
                    return redirect()->back()
                        ->withErrors($response['response']['error'])
                        ->withInput();
                } else {
                    Session::flash('error', 'Something wrong, Data created fail');
                    Session::flash('mode', 'error');
                    return redirect()->back();
                }
            }
        }
    }

    private function editTraining(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_training' => 'required|unique:trainings,kode_training,' . $id,
            'spectraining_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'date|after:start_date'
        ]);

        if ($validator->fails()) {
            $return['response']['success'] = false;
            $return['response']['error'] = $validator;
        } else {
            $data = Training::find($id);
            $data->kode_training = $request->kode_training;
            $data->training_name = $request->training_name;
            $data->spectraining_id = $request->spectraining_id;
            $data->duration = $request->duration;
            $data->trainer_name = $request->trainer_name;
            $data->training_location = $request->training_location;
            $data->start_date = $request->start_date;
            $data->end_date = $request->end_date;
            $data->status = $request->status;
            if ($data->save()) {
                $return['response']['success'] = true;
            } else {
                $return['response']['success'] = false;
            }
        }
        return $return;
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        if ($id != null) {
            $return['success'] = true;
            if (Training::where('id', $id)->delete()) {

                $return['success'] = true;
            } else {
                $return['success'] = false;
            }
        } else {
            $return['success'] = false;
        }

        return $return;
    }

    public function importTrainPlan(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'training_file' => 'required|mimes:xls,xlsx'
        ]);

        if ($validator->fails()) {
            $return['response']['success'] = false;
            $return['response']['error'] = $validator;

            $response = $return;
            Session::flash('error', 'Something wrong, failed to import data');
            Session::flash('mode', 'error');
            return redirect()->back()
                ->withErrors($response['response']['error']);
        } else {
            // Menangkap file excel
            $file = $request->file('training_file');
            // Membuat nama file unik
            $nama_file = rand() . $file->getClientOriginalName();
            // Uplod ke folder /uploads/training_plan di dalam folder public
            if ($newFilePath = $request->file('training_file')->move(public_path('/uploads/training_plan'), $nama_file)) {
                // $file->move('uploads/training_plan', $nama_file);
                // Import data
                try {
                    Excel::import(new TrainPlanImport, public_path('/uploads/training_plan/' . $nama_file));
                } catch (Exception $e) {
                    unlink(public_path('/uploads/training_plan/' . $nama_file));
                    Session::flash('error', 'Gagal import data. ' . $e->getMessage());
                    Session::flash('mode', 'error');
                    return redirect()->route('training.index');
                }
                // Menghapus file yang sudah diproses
                unlink(public_path('/uploads/training_plan/' . $nama_file));
                // notifikasi dengan session
                Session::flash('success', 'Data imported successfully');
                Session::flash('mode', 'success');
                // alihkan halaman kembali
                return redirect()->route('training.index');
            } else {
                // notifikasi dengan session
                Session::flash('error', 'Failed to import file.');
                Session::flash('mode', 'error');
                // alihkan halaman kembali
                return redirect()->route('training.index');
            }
        }
    }
}
