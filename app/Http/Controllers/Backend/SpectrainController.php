<?php

namespace App\Http\Controllers\Backend;

use App\Models\Department;
use App\Models\Spectrain;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use Session;
use Validator;
use DB;

class SpectrainController extends Controller
{
    public function datatable(Request $request)
    {


        // if ($request->ajax()) {
        $data = Spectrain::select('spectrains.*')
            ->get();
        $p = 0;
        foreach ($data as $dt) {
            if (!empty($dt['positions'])) {
                $position_array = unserialize($dt['positions']);
                $i = 0;
                foreach ($position_array as $key) {
                    $postarry[$i++] = $key['name'];
                }
                $data[$p]['positions'] = implode(', ', $postarry);

                $grade = DB::table('positions')
                    ->where('name', '=', $dt['positions'])
                    ->orderBy('id', 'asc')
                    ->value('grade');
                if ($grade != NULL) {
                    $data[$p]['grade'] = $grade;
                } else {
                    $data[$p]['grade'] = "-";
                }
            } else {
                $data[$p]['positions'] = "";
            }

            if (!empty($dt['important_aspect'])) {
                $aspect_array = unserialize($dt['important_aspect']);
                $data[$p]['important_aspect'] = implode(', ', (array) $aspect_array);
            } else {
                $data[$p]['important_aspect'] = "";
            }
            $p++;
        }

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $btn = '<a href="spectrain/edit/' . $row->id . '" data-toggle="tooltip" data-name="' . $row->traintypeid . '"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editUser">Edit</a>';

                $btn = $btn . ' <span  data-name="' . $row->traintypeid . '" data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm delete">Delete</span>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        // }
    }

    public function index()
    {

        $userinfo = Session::get('userinfo');

        if ($userinfo['user_role'] != 1) {
            return redirect('/home/dashboard');
        }

        $datas = array(
            'title' => 'Spectrain List',
            'slug' => 'spectrains'
        );
        return view('spectrains.index', $datas);
    }

    public function addSpectrain(Request $request)
    {
        $userinfo = Session::get('userinfo');

        if ($userinfo['user_role'] != 1) {
            return redirect('/home/dashboard');
        }

        if ($request->isMethod('GET')) {
            $departments = Department::all();
            $data = array(
                'title' => 'Create Spectrain',
                'slug' => 'spectrains',
                'departments' => $departments
            );

            return view('spectrains.add', $data);
        }

        if ($request->isMethod('POST')) {
            $response = $this->createSpectrain($request);
            if ($response['response']['success']) {
                Session::flash('success', 'Data berhasil dibuat');
                Session::flash('mode', 'success');
                return redirect()->route('spectrain.index');
            } else {
                if ($response['response']['error']) {
                    Session::flash('error', 'Ada sesuatu yang salah, Data gagal dibuat');
                    Session::flash('mode', 'error');
                    return redirect()->route('spectrain.add')
                        ->withErrors($response['response']['error'])
                        ->withInput();
                } else {
                    Session::flash('error', 'Ada sesuatu yang salah, Data gagal dibuat');
                    Session::flash('mode', 'error');
                    return redirect()->back();
                }
            }
        }
    }

    private function createSpectrain(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'traintypeid' => 'required|unique:spectrains',
                'traintype' => 'required',
                'grade' => 'required',
                'competency' => 'required',
                'training_needed' => 'required',
                'standard' => 'required',
            ],
            [
                'traintypeid.required' => 'Kode tipe training tidak boleh kosong',
                'traintypeid.unique' => 'Kode tipe training sudah ada',
                'traintype.required' => 'Training type tidak boleh kosong',
                'grade.required' => 'Grade tidak boleh kosong',
                'competency.required' => 'Kompetensi tidak boleh kosong',
                'training_needed.required' => 'Training yang diperlukan tidak boleh kosong',
                'standard.required' => 'Strandar kompetensi tidak boleh kosong',
            ]
        );

        if ($validator->fails()) {
            $return['response']['success'] = false;
            $return['response']['error'] = $validator;
        } else {

            $data = new Spectrain();
            $data->traintypeid = $request->traintypeid;
            $data->traintype = $request->traintype;
            $data->grade = $request->grade;
            $data->competency = $request->competency;
            $data->important_aspect = serialize($request->important_aspect);
            $data->training_needed = $request->training_needed;
            $data->standard = $request->standard;

            if ($request->allposition_check == true) {
                $positions = Position::select('positions.*')->get();
                foreach ($positions as $position) {
                    $positions_arr[] = array(
                        'id' => $position->id,
                        'name' => $position->name
                    );
                }
                $data->positions = serialize($positions_arr);
                $data->allposition = 1;
            } else {
                $positions = Position::select('positions.*')
                    ->whereIn('id', $request->positionid)
                    ->get();

                if (!empty($positions)) {
                    foreach ($positions as $position) {
                        $positions_arr[] = array(
                            'id' => $position->id,
                            'name' => $position->name
                        );
                    }
                    $data->positions = serialize($positions_arr);
                }
            }
            if ($data->save()) {
                $return['response']['success'] = true;
            } else {
                $return['response']['success'] = false;
            }
        }
        return $return;
    }

    public function updateSpectrain(Request $request, $id)
    {

        $userinfo = Session::get('userinfo');

        if ($userinfo['user_role'] != 1) {
            return redirect('/home/dashboard');
        }

        if ($request->isMethod('GET')) {

            //            if(Session::get('userinfo') == ""){
            //                return view ('login');
            //            }else{
            //                return redirect('/home/dashboard');
            //            }

            $spectrain = DB::table('spectrains')
                ->where('id', $id)
                ->first();

            $data = array(
                'title' => 'Update Spectrain',
                'slug' => 'spectrains',
                'data' => $spectrain,
                'departments' => Department::all(),

            );


            if ($spectrain->allposition == 1) {
            } else {

                $specpos = unserialize($spectrain->positions);

                //                Selected departemen
                $position = Position::where('id', $specpos[0]['id'])->first();
                $data['selected_dept'] = Department::where('id', $position->departmentID)
                    ->first();

                //                Selected position
                $position_arr = array();
                foreach ($specpos as $pos) {
                    $position_arr[] = $pos['id'];
                }
                $data['selected_pos'] = Position::whereIn('id', $position_arr)->get();
                $data['positions'] = Position::where('departmentID', $data['selected_dept']['id'])->get();
                $data['departmentId'] = $position->departmentID;
            }

            //            echo "<pre>";
            //            var_dump($spectrain->allposition);
            //            echo "<pre>";
            //            exit();

            return view('spectrains.update', $data);
        }

        if ($request->isMethod('POST')) {
            $input = $request->all();

            $validator = Validator::make($request->all(), [
                'traintypeid' => 'required|unique:spectrains,traintype,' . $input['id'],
                'traintype' => 'required',
            ]);

            if ($validator->fails()) {
                Session::flash('error', 'Something wrong, Data created fail');
                Session::flash('mode', 'error');
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $update = $this->editSpectrain($input);

                if ($update['response']['success']) {
                    Session::flash('success', 'Data updated successfully');
                    Session::flash('mode', 'success');
                    return redirect()->back();
                } else {
                    Session::flash('error', 'Something wrong, Data updated fail');
                    Session::flash('mode', 'error');
                    return redirect()->back();
                }
            }
        }
    }

    private function editSpectrain($input)
    {

        $id = $input['id'];
        $data = Spectrain::find($id);
        $data['traintypeid'] = $input['traintypeid'];
        $data['traintype'] = $input['traintype'];
        $data->grade = $input['grade'];
        $data->competency = $input['competency'];
        $data->important_aspect = serialize($input['important_aspect']);
        $data->training_needed = $input['training_needed'];
        $data->standard = $input['standard'];
        if (array_key_exists('allposition_check', $input)) {
            $positions = Position::select('positions.*')->get();
            foreach ($positions as $position) {
                $positions_arr[] = array(
                    'id' => $position->id,
                    'name' => $position->name
                );
            }
            $data->positions = serialize($positions_arr);
            $data->allposition = 1;
        } else {
            $positions = Position::select('positions.*')
                ->whereIn('id', $input['positionid'])
                ->get();

            if (!empty($positions)) {
                foreach ($positions as $position) {
                    $positions_arr[] = array(
                        'id' => $position->id,
                        'name' => $position->name
                    );
                }
                $data->positions = serialize($positions_arr);
            }
        }

        if ($data->save()) {
            $return['response']['success'] = true;
        } else {
            $return['response']['success'] = false;
        }

        return $return;
    }

    public function delete(Request $request)
    {
        $userinfo = Session::get('userinfo');

        if ($userinfo['user_role'] != 1) {
            return redirect('/home/dashboard');
        }

        $id = $request->id;
        if ($id != null) {
            $return['success'] = true;
            if (Spectrain::where('id', $id)->delete()) {

                $return['success'] = true;
            } else {
                $return['success'] = false;
            }
        } else {
            $return['success'] = false;
        }

        return $return;
    }
}
