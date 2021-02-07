<?php

namespace App\Imports;

use App\Models\Position;
use App\Models\User;
use App\Models\Department;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use DB;
use Session;

HeadingRowFormatter::
    default('none');

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (!empty($row['Nama'])) {

            if (empty($row['Email'])) {
                $errMessage = 'Mohon pastikan kolom Email tidak kosong.';
                throw new \Exception($errMessage);
            }

            $cek = User::where('email', $row['Email'])->count();

            if ($cek == 0) {
                $jabatan = Position::where('name', $row['Posisi'])
                    ->orWhere('name', 'like', '%' . $row['Posisi'] . '%')
                    ->select('positions.*')->first();

                if ($jabatan) {
                    $jabatanID = $jabatan->id;
                    $departmentID = $jabatan->departmentID;
                } else {
                    $jabatanID = 0;
                    $departmentID = 0;
                    Session::flash('error', 'User dengan nama  ' . $row['Nama'] . ' tidak terdapat jabatan');
                }

                $role = DB::table('roles')
                    ->where('name', $row['Role'])
                    ->orWhere('name', 'like', '%' . $row['Role'] . '%')
                    ->select('id')
                    ->first();

                if ($role) {
                    $roleID = $role->id;
                } else {
                    $roleID = 0;
                    Session::flash('error', 'User dengan nama  ' . $row['Nama'] . ' tidak terdapat role id');
                }

                $softskill = explode(',', $row['Softskill']);
                $hardskill = explode(',', $row['Hardskill']);

                if (count($softskill) > 0) {
                    $softskill = serialize($softskill);
                } else {
                    $softskill = 0;
                }

                if (count($hardskill) > 0) {
                    $hardskill = serialize($hardskill);
                } else {
                    $hardskill = 0;
                }
                try {
                    $tanggalLahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['Tanggal Lahir']);
                } catch (\Throwable $th) {
                    $errMessage = 'Mohon pastikan Tanggal Lahir sesuai dengan format (tanggal/bulan/tahun).';
                    throw new \Exception($errMessage);
                }

                return new User([
                    'name'     => $row['Nama'],
                    'email'    => $row['Email'],
                    'phone'    => $row['Nomor Telepon'],
                    'password' => Hash::make($row['Password']),
                    'departmentID' => $departmentID, //tidak ada di $row//
                    'jabatanID'    => $jabatanID,
                    'roleID'    => $roleID,
                    'datebirth'  => $tanggalLahir,
                    'nik'    => $row['NIK'],
                    'npwp'    => $row['NPWP'],
                    'softskill'    => $softskill,
                    'hardskill'    => serialize($hardskill),
                    'address'    => $row['Alamat'],
                ]);
            } else {
                $jabatan = Position::where('name', $row['Posisi'])
                    ->select('positions.*')->first();

                if ($jabatan) {
                    $jabatanID = $jabatan->id;
                    $departmentID = $jabatan->departmentID;
                } else {
                    $jabatanID = 0;
                    $departmentID = 0;
                    Session::flash('error', 'User dengan nama  ' . $row['Nama'] . ' tidak terdapat jabatan');
                }

                $role = DB::table('roles')
                    ->where('name', $row['Role'])
                    ->select('id')
                    ->first();

                if ($role) {
                    $roleID = $role->id;
                } else {
                    $roleID = 0;
                    Session::flash('error', 'User dengan nama  ' . $row['Nama'] . ' tidak terdapat role id');
                }

                $softskill = explode(',', $row['Softskill']);
                $hardskill = explode(',', $row['Hardskill']);

                if (count($softskill) > 0) {
                    $softskill = serialize($softskill);
                } else {
                    $softskill = null;
                }

                if (count($hardskill) > 0) {
                    $hardskill = serialize($hardskill);
                } else {
                    $hardskill = null;
                }

                try {
                    $tanggalLahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['Tanggal Lahir']);
                } catch (\Throwable $th) {
                    $errMessage = 'Mohon pastikan Tanggal Lahir sesuai dengan format (tanggal/bulan/tahun).';
                    throw new \Exception($errMessage);
                }

                $arr = [
                    'name'     => $row['Nama'],
                    'phone'    => $row['Nomor Telepon'],
                    'password' => Hash::make($row['Password']),
                    'departmentID' => $departmentID, //tidak ada di $row//
                    'jabatanID'    => $jabatanID,
                    'roleID'    => $roleID,
                    'datebirth'    => $tanggalLahir,
                    'nik'    => $row['NIK'],
                    'npwp'    => $row['NPWP'],
                    'softskill'    => $softskill,
                    'hardskill'    => $hardskill,
                    'address'    => $row['Alamat'],
                ];

                if (!empty($arr)) {
                    User::where('email', $row['Email'])
                        ->update($arr);
                }
            }
        }
    }
}
