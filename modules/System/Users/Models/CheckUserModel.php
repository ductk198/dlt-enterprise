<?php

namespace Modules\System\Users\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CheckUserModel extends Authenticatable
{
    use Notifiable;

    protected $table = 'USER_STAFF';
	protected $primaryKey = 'PK_STAFF';
    public $incrementing = false;
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password',
    ];

}
