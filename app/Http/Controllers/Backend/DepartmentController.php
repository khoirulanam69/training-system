<?php

namespace App\Http\Controllers\Backend;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use Session;
use Validator;
use DB;

class DepartmentController extends Controller
{
    public function datatable(Request $request)
    {

        if ($request->ajax()) {
            $data = DB::table('departments')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="department/edit/' . $row->id . '" data-toggle="tooltip" data-name="' . $row->name . '"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editUser">Edit</a>';

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
            'title' => 'Department List',
            'slug' => 'department'
        );
        return view('departments.index', $datas);
    }

    public function addDepartment(Request $request)
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
            $data = array(
                'title' => 'Create Department',
                'slug' => 'departments'
            );

            return view('departments.add', $data);
        }

        if ($request->isMethod('POST')) {
            $response = $this->createDepartment($request);
            if ($response['response']['success']) {
                Session::flash('success', 'Data created successfully');
                Session::flash('mode', 'success');
                return redirect()->route('department.index');
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

    private function createDepartment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required|unique:departments',
        ]);

        if ($validator->fails()) {
            $return['response']['success'] = false;
            $return['response']['error'] = $validator;
        } else {
            $data = new Department();
            $data->name = $request->name;
            $data->code = $request->code;

            if ($data->save()) {
                $return['response']['success'] = true;
            } else {
                $return['response']['success'] = false;
            }
        }
        return $return;
    }

    public function updateDepartment(Request $request, $id)
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

            $department = DB::table('departments')
                ->where('id', $id)
                ->first();

            $data = array(
                'title' => 'Update Department',
                'slug' => 'departments',
                'data' => $department
            );

            return view('departments.update', $data);
        }

        if ($request->isMethod('POST')) {
            $input = $request->all();

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'code' => 'required|unique:departments,code,' . $input['id'],
            ]);

            if ($validator->fails()) {
                Session::flash('error', 'Something wrong, Data created fail');
                Session::flash('mode', 'error');
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $update = $this->editDepartment($input);

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

    private function editDepartment($input)
    {

        $id = $input['id'];
        $data = Department::find($id);
        $data['name'] = $input['name'];
        $data['code'] = $input['code'];

        if ($data->save()) {
            $return['response']['success'] = true;
        } else {
            $return['response']['success'] = false;
        }

        return $return;
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        if ($id != null) {
            $return['success'] = true;
            if (Department::where('id', $id)->delete()) {

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
