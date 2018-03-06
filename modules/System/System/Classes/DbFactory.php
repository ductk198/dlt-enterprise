<?php

namespace Modules\System\System\Classes;

use Modules\System\System\Classes\Table;
use Modules\System\System\Classes\Database;
use Modules\System\System\Classes\Functions;
use Modules\System\System\Classes\StoreProcedure;
use Modules\System\System\Classes\Views;
use Modules\System\System\Classes\Other;
use Modules\System\System\Classes\Trigger;


/**
 * Class Factory Method.
 *
 * @author Duclt
 */

abstract class AbstractDbClass
{
    // Force Extending class to define this method
    abstract protected function getAll($dbname = false, $name = false);
	abstract protected function getSingle($id);
	abstract protected function getScript($id=false,$dbname=false);
	abstract protected function getChildByTree($id = false, $root = false, $name = false);
	abstract protected function setPropertie($id = false, $root = false);

}


class DbFactory
{
	public static function getObjDb($type){
		if($type == 'table'){
			return new Table();
		}else if($type == 'database'){
			return new Database();
		}else if($type == 'store'){
			return new StoreProcedure();
		}else if($type == 'view'){
			return new Views();
		}else if($type == 'function'){
			return new Functions();
		}else if($type == 'other'){
			return new Other();
		}else if($type == 'trigger'){
			return new Trigger();
		}
		return null;
	}
}
