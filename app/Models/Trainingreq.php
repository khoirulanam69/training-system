<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainingreq extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trainingreq';

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
}