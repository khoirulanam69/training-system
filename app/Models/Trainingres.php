<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainingres extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trainingresult';

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

    public function training()
    {

        return $this->belongsTo('App\Models\Training', 'trainingID');
    }
}
