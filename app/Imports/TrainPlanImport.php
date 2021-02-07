<?php

namespace App\Imports;

use App\Models\Training;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use DB;
HeadingRowFormatter::default('none');

class TrainPlanImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if(!empty($row['Kode Training'])){

            $cek = Training::where('kode_training',$row['Kode Training'])->count();

            if($cek == 0){
                if(strtolower($row['Status']) == 'belum berlangsung' || $row['Status'] == 1){
                    $status = 1;
                }else if(strtolower($row['Status']) == 'sedang berlangsung' || $row['Status'] == 2){
                    $status = 2;
                }else if(strtolower($row['Status']) == 'sudah berlangsung' || $row['Status'] == 3){
                    $status = 3;
                }else{
                    $status = 1;
                }
                $spectraining_id = DB::table('spectrains as s')
                                    ->select('s.id')
                                    ->where('s.traintypeid','=',$row['ID Spesifikasi Training'])
                                    ->first();
                
                if($spectraining_id){
                    $spectraining_id = $spectraining_id->id;
                }
                else{
                    $errMessage = 'Tidak ditemukan ID Spesifikasi Training \''. $row['ID Spesifikasi Training'].'\'. Mohon pastikan ID Spesifikasi Training valid dan benar ada.';
                    throw new \Exception($errMessage);
                }
                
                try {
                    $start_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['Tanggal Mulai']);
                } catch (\Throwable $th) {
                    $errMessage = 'Mohon pastikan Tanggal Mulai sesuai dengan format (tanggal/bulan/tahun).';
                    throw new \Exception($errMessage);
                }
                try {
                    $end_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['Tanggal Berakhir']);
                } catch (\Throwable $th) {
                    $errMessage = 'Mohon pastikan Tanggal Berahir sesuai dengan format (tanggal/bulan/tahun).';
                    throw new \Exception($errMessage);
                }
                if(!is_numeric($row['Durasi Jam'])){
                    $errMessage = 'Mohon pastikan Durasi Jam adalah berupa angka.';
                    throw new \Exception($errMessage);
                }
                return new Training([
                    'approved_status'   => 0,
                    'kode_training'     => $row['Kode Training'],
                    'training_name'     => $row['Nama Training'],
                    'spectraining_id'   => $spectraining_id,
                    'trainer_name'      => $row['Nama Trainer'],
                    'training_location' => $row['Lokasi Training'],
                    'duration'          => $row['Durasi Jam'],
                    'start_date'        => $start_date,
                    'end_date'          => $end_date,
                    'status'            => $status
                ]);
            }else{
                if(strtolower($row['Status']) == 'belum berlangsung' || $row['Status'] == 1){
                    $status = 1;
                }else if(strtolower($row['Status']) == 'sedang berlangsung' || $row['Status'] == 2){
                    $status = 2;
                }else if(strtolower($row['Status']) == 'sudah berlangsung' || $row['Status'] == 3){
                    $status = 3;
                }else{
                    $status = 1;
                }
                $spectraining_id = DB::table('spectrains as s')
                                    ->select('s.id')
                                    ->where('s.traintypeid','=',$row['ID Spesifikasi Training'])
                                    ->first();
                
                if($spectraining_id){
                    $spectraining_id = $spectraining_id->id;
                }
                else{
                    $errMessage = 'Tidak ditemukan ID Spesifikasi Training \''. $row['ID Spesifikasi Training'].'\'. Mohon pastikan ID Spesifikasi Training valid dan benar ada.';
                    throw new \Exception($errMessage);
                }
                
                try {
                    $start_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['Tanggal Mulai']);
                } catch (\Throwable $th) {
                    $errMessage = 'Mohon pastikan Tanggal Mulai sesuai dengan format (tanggal/bulan/tahun).';
                    throw new \Exception($errMessage);
                }
                try {
                    $end_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['Tanggal Berakhir']);
                } catch (\Throwable $th) {
                    $errMessage = 'Mohon pastikan Tanggal Berahir sesuai dengan format (tanggal/bulan/tahun).';
                    throw new \Exception($errMessage);
                }
                if(!is_numeric($row['Durasi Jam'])){
                    $errMessage = 'Mohon pastikan Durasi Jam adalah berupa angka.';
                    throw new \Exception($errMessage);
                }

                $arr = [
                    'approved_status'   => 0,
                    'kode_training'     => $row['Kode Training'],
                    'training_name'     => $row['Nama Training'],
                    'spectraining_id'   => $spectraining_id,
                    'trainer_name'      => $row['Nama Trainer'],
                    'training_location' => $row['Lokasi Training'],
                    'duration'          => $row['Durasi Jam'],
                    'start_date'        => $start_date,
                    'end_date'          => $end_date,
                    'status'            => $status
                ];

                if(!empty($arr)){
                    Training::where('kode_training',$row['Kode Training'])
                    ->update($arr);
                }
            }
        }
    }
}
