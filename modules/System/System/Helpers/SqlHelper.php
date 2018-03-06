<?php

namespace Modules\System\System\Helpers;

use Log;
use Request;

use Modules\System\System\Classes\Table;
use DB;
use Modules\System\System\Classes\DbFactory;
use Illuminate\Support\Facades\Cache;


/**
 * Helper hỗ trợ module user.
 *
 * @author Duclt
 */
class SqlHelper
{
    public function getChildByNodeLevel($node_level,$id, $name = false){
        if($node_level > 4){
            $node_level = 4;
        }
        if($node_level == 0){
            $objdb = DbFactory::getObjDb('database');
		}else if($node_level == 2){
            $objdb = DbFactory::getObjDb('other');
		}else if($node_level == 4){
            $arr = explode("_",$id);
            $id = $arr[1];
            if($arr[0] == 'table'){
                $objdb = DbFactory::getObjDb('table');
            }else if($arr[0] == 'view'){
                $objdb = DbFactory::getObjDb('view');
            }else if($arr[0] == 'store'){
                $objdb = DbFactory::getObjDb('store');
            }else if($arr[0] == 'function'){
                $objdb = DbFactory::getObjDb('function');
            }else if($arr[0] == 'trigger'){
                $objdb = DbFactory::getObjDb('trigger');
            }
        }else{
            return false;
        }
        $objdb->setPropertie($id, $node_level);
        $returns['icon'] = $objdb->parent_icon;
        $returns['id'] = $objdb->id;
        $returns['text'] = $objdb->text;
        $returns['type'] = $objdb->parent_type;
        $returns['state']['opened'] = $objdb->state_opened;
        $returns['children'] = $objdb->getChildByTree($id, $node_level,$name);
        return $returns;
    }

    public function getScript($id,$type,$dbname){
        if($type == 'store'){
            $objdb = DbFactory::getObjDb('store');
        }else if($type == 'function'){
            $objdb = DbFactory::getObjDb('function');
        }else if($type == 'trigger'){
            $objdb = DbFactory::getObjDb('trigger');
        }else if($type == 'table'){
            $objdb = DbFactory::getObjDb('table');
        }
        $script = $objdb->getScript($id,$dbname);
        return $script;
    }

    public function getAlldb(){
        $objdb = DbFactory::getObjDb('database');
        return $objdb->getAll();
    }

    public function getTable($id,$type,$dbname){
        $objdb = DbFactory::getObjDb('table');
        $infor = $objdb->getInfor($id,$dbname);
        return $infor;
    }

	public function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public function create_db_temp($namedb,$path){
        $tempsql = "
        CREATE DATABASE [#database_name#]
        CONTAINMENT = NONE
        ON  PRIMARY 
       ( NAME = N'#database_name#', FILENAME = N'#database_path#\#database_name#.mdf' , SIZE = 3072KB , FILEGROWTH = 1024KB )
        LOG ON 
       ( NAME = N'#database_name#_log', FILENAME = N'#database_path#\#database_name#_log.ldf' , SIZE = 1024KB , FILEGROWTH = 10%)
       ALTER DATABASE [#database_name#] SET COMPATIBILITY_LEVEL = 110
       ALTER DATABASE [#database_name#] SET ANSI_NULL_DEFAULT OFF 
       ALTER DATABASE [#database_name#] SET ANSI_NULLS OFF 
       ALTER DATABASE [#database_name#] SET ANSI_PADDING OFF 
       ALTER DATABASE [#database_name#] SET ANSI_WARNINGS OFF 
       ALTER DATABASE [#database_name#] SET ARITHABORT OFF 
       ALTER DATABASE [#database_name#] SET AUTO_CLOSE OFF 
       ALTER DATABASE [#database_name#] SET AUTO_SHRINK OFF 
       ALTER DATABASE [#database_name#] SET AUTO_CREATE_STATISTICS ON
       ALTER DATABASE [#database_name#] SET AUTO_UPDATE_STATISTICS ON 
       ALTER DATABASE [#database_name#] SET CURSOR_CLOSE_ON_COMMIT OFF 
       ALTER DATABASE [#database_name#] SET CURSOR_DEFAULT  GLOBAL 
       ALTER DATABASE [#database_name#] SET CONCAT_NULL_YIELDS_NULL OFF 
       ALTER DATABASE [#database_name#] SET NUMERIC_ROUNDABORT OFF 
       ALTER DATABASE [#database_name#] SET QUOTED_IDENTIFIER OFF 
       ALTER DATABASE [#database_name#] SET RECURSIVE_TRIGGERS OFF 
       ALTER DATABASE [#database_name#] SET  DISABLE_BROKER 
       ALTER DATABASE [#database_name#] SET AUTO_UPDATE_STATISTICS_ASYNC OFF 
       ALTER DATABASE [#database_name#] SET DATE_CORRELATION_OPTIMIZATION OFF 
       ALTER DATABASE [#database_name#] SET PARAMETERIZATION SIMPLE 
       ALTER DATABASE [#database_name#] SET READ_COMMITTED_SNAPSHOT OFF 
       ALTER DATABASE [#database_name#] SET  READ_WRITE 
       ALTER DATABASE [#database_name#] SET RECOVERY FULL 
       ALTER DATABASE [#database_name#] SET  MULTI_USER 
       ALTER DATABASE [#database_name#] SET PAGE_VERIFY CHECKSUM  
       ALTER DATABASE [#database_name#] SET TARGET_RECOVERY_TIME = 0 SECONDS 
       select 1 x     
        ";
        $tempsql = str_replace("#database_name#",$namedb,$tempsql);
        $tempsql = str_replace("#database_path#",$path,$tempsql);
        return $tempsql;
    }

    public function restore_db_temp($namedb,$restore_path,$db_path){
        $tempsql = "
            RESTORE DATABASE [#database_name#] FROM  
            DISK = N'#restore_path#' 
            WITH  FILE = 1,  
            MOVE N'temp' TO N'#database_path#\#database_name#.mdf',  
            MOVE N'temp_log' TO N'#database_path#\#database_name#_log.ldf',  
            NOUNLOAD,  REPLACE,  STATS = 5
            ALTER DATABASE [#database_name#] MODIFY FILE (NAME=N'temp', NEWNAME=N'#database_name#')
            ALTER DATABASE [#database_name#] MODIFY FILE (NAME=N'temp_log', NEWNAME=N'#database_name#_log')
        ";
        $tempsql = str_replace("#database_name#",$namedb,$tempsql);
        $tempsql = str_replace("#restore_path#",$restore_path,$tempsql);
        $tempsql = str_replace("#database_path#",$db_path,$tempsql);
        return $tempsql;
    }
}
