<?php

namespace App\Http\Controllers\Backend;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use Session;
use Validator;
use DB;

class PositionController extends Controller
{
    public function getallposition(Request $request)
    {
        $data = Position::where('departmentID', $request->id)
            ->get();
        return json_encode($data);
    }

    public function datatable(Request $request)
    {

        if ($request->ajax()) {
            $data = DB::table('positions')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="position/edit/' . $row->id . '" data-toggle="tooltip" data-name="' . $row->name . '"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editUser">Edit</a>';

                    $btn = $btn . ' <span  data-name="' . $row->name . '" data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm delete">Delete</span>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function index()
    {
        $userinfo = Session::get('userinfo');

        if ($userinfo['user_role'] != 1) {
            return redirect('/home/dashboard');
        }

        $datas = array(
            'title' => 'Position List',
            'slug' => 'position'
        );
        return view('positions.index', $datas);
    }

    public function addPosition(Request $request)
    {
        $userinfo = Session::get('userinfo');

        if ($userinfo['user_role'] != 1) {
            return redirect('/home/dashboard');
        }

        if ($request->isMethod('GET')) {
            //            if(Session::get('userinfo') == ""){
            //                return view ('login');
            //            }
            //            else{
            //                return redirect('/home/dashboard');
            //            }
            $departments = Department::all();
            $data = array(
                'title' => 'Create Position',
                'slug' => 'positions',
                'departments' => $departments
            );

            return view('positions.add', $data);
        }

        if ($request->isMethod('POST')) {
            $response = $this->createPosition($request);
            if ($response['response']['success']) {
                Session::flash('success', 'Data berhasil dibuat');
                Session::flash('mode', 'success');
                return redirect()->route('position.index');
            } else {
                if ($response['response']['error']) {
                    Session::flash('error', 'Ada sesuatu yang salah, Data gagal dibuat');
                    Session::flash('mode', 'error');
                    return redirect()->back()
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

    private function createPosition(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'code' => 'required|unique:positions',
                'departmentID' => 'required',
                'grade' => 'required',
                'grade_code' => 'required|unique:positions',
                'competency' => 'required'
            ],
            [
                'name.required' => 'Nama tidak boleh kosong',
                'code.required' => 'Code tidak boleh kosong',
                'code.unique' => 'Code sudah ada',
                'departmentID.required' => 'Departemen tidak boleh kosong',
                'grade.required' => 'Grade tidak boleh kosong',
                'grade_code.required' => 'Kode grade tidak boleh kosong',
                'grade_code.unique' => 'Kode grade sudah ada',
                'competency.required' => 'Kompetensi tidak boleh kosong',
            ]
        );

        if ($validator->fails()) {
            $return['response']['success'] = false;
            $return['response']['error'] = $validator;
        } else {
            $data = new Position();
            $data->name = $request->name;
            $data->code = $request->code;
            $data->departmentID = $request->departmentID;
            $data->grade = $request->grade;
            $data->grade_code = $request->grade_code;
            $data->competency = $request->competency;

            if ($data->save()) {
                $return['response']['success'] = true;
            } else {
                $return['response']['success'] = false;
            }
        }
        return $return;
    }

    public function updatePosition(Request $request, $id)
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

            $position = DB::table('positions')
                ->where('id', $id)
                ->first();

            $departments = Department::all();

            $data = array(
                'title' => 'Update Position',
                'slug' => 'positions',
                'data' => $position,
                'departments' => $departments
            );

            return view('positions.update', $data);
        }

        if ($request->isMethod('POST')) {
            $input = $request->all();

            //return $input;
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'code' => 'required|unique:positions,code,' . $input['id'],
                'grade' => 'required',
                'grade_code' => 'required|unique:positions,grade_code,' . $input['id'],
                'competency' => 'required',
                'departmentID' => 'required'
            ]);

            if ($validator->fails()) {
                Session::flash('error', 'Something wrong, Data created fail');
                Session::flash('mode', 'error');
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $update = $this->editPosition($input);

                if ($update['response']['success']) {
                    Session::flash('success', 'Data updated successfully');
                    Session::flash('mode', 'success');
                    return redirect()->route('position.index');
                } else {
                    Session::flash('error', 'Something wrong, Data updated fail');
                    Session::flash('mode', 'error');
                    return redirect()->back();
                }
            }
        }
    }

    private function editPosition($input)
    {

        $id = $input['id'];
        $data = Position::find($id);
        $data['name'] = $input['name'];
        $data['code'] = $input['code'];
        $data['grade'] = $input['grade'];
        $data['grade_code'] = $input['grade_code'];
        $data['competency'] = $input['competency'];

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
            if (Position::where('id', $id)->delete()) {

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
