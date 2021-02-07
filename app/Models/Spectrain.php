<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spectrain extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'spectrains';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['traintypeid', 'traintype', 'grade', 'competency', 'positions', 'important_aspect', 'training_needed', 'allposition', 'standard'];

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

    public function position()
    {
        return $this->belongsTo('App\Models\Position');
    }

    public function training()
    {
        return $this->hasMany('App\Models\Training', 'spectraining_id');
    }
}
