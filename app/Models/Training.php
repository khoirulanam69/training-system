<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trainings';
    protected $fillable = ['approved_status','kode_training','training_name','spectraining_id','trainer_name','training_location','duration','start_date','end_date','status'];
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    public function spec_training()
    {
        return $this->belongsTo('App\Models\Training', 'spectraining_id');
    }

    public function training_result()
    {
        return $this->hasMany('App\Models\Trainingres', 'trainingID');
    }

}
