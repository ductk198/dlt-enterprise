<?php

namespace Modules\System\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class UnitModel extends Model {

    protected $table = 'USER_UNIT';
    protected $primaryKey = 'PK_UNIT';
    public $incrementing = false;
    public $timestamps = false;

}
