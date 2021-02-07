<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    use SoftDeletes;

    protected $table = 'users';

    protected $fillable = ['name', 'email', 'alamat', 'password', 'phone', 'jabatanID', 'datebirth', 'departmentID', 'nik', 'npwp', 'softskill', 'hardskill', 'status', 'roleID', 'jabatanID', 'created_at', 'updated_at'];

    protected $hidden = ['password'];

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
    public $incrementing = false;

    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'roleID');
    }

    public function position()
    {
        return $this->belongsTo('App\Models\Position', 'jabatanID');
    }

    public function department()
    {
        return $this->belongsTo('App\Models\Department', 'departmentID');
    }

    public function profpic()
    {
        return $this->belongsTo('App\Models\Media', 'mediaID');
    }
}
