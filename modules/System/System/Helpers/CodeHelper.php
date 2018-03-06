<?php

namespace Modules\System\System\Helpers;

use DB;
use Log;
use Request;

use Illuminate\Support\Facades\Cache;


/**
 * Helper hỗ trợ module user.
 *
 * @author Duclt
 */
class CodeHelper
{
	
	public function dirToArray($node_level,$dir,$parrent_dir) { 
   
	   $result = array(); 
	   $i = 0;
	   $j=0;
	   $cdir = scandir($dir); 
	   $directories = array();
	   $files_list  = array();
	   foreach ($cdir as $key => $value) 
	   { 
		  if (!in_array($value,array(".",".."))) 
		  { 
			$child_dir = $dir . DIRECTORY_SEPARATOR . $value;
			$result['children'] = 0; 
			$result['text'] = $value;
			$result['id'] = $parrent_dir."\\".$value;
			$Infor = new \SplFileInfo($child_dir);
			$result['size'] = $this->formatSizeUnits($Infor->getSize());
			$result['date_modify'] = date('d/m/Y H:i', $Infor->getMTime());
			if (is_dir($child_dir)) 
			{ 
				$result['icon'] = 'fa fa-folder fa-hight folder-v1';
				$result['type'] = 'folder'; 
				if($this->check_folder_children($child_dir)){
					$result['children'] = true; 
				}
				$directories[$i] = $result;
				$i++;
			}else{
				$result['li_attr'] =  array(
					"class" => "file-hide"
				);
				$result['icon'] = 'fa fa-file-text fa-hight file-v1';
				$result['type'] = 'file';
				$files_list[$j] = $result;
				$j++;
			} 
		  } 
	   }
	   $return = array_merge($directories,$files_list);
	   return $return; 
	} 

	function check_folder_children($dir) {
		$result = false;
		if($dh = opendir($dir)) {
			while(!$result && ($file = readdir($dh)) !== false) {
				$result = $file !== "." && $file !== "..";
			}
	
			closedir($dh);
		}
	
		return $result;
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
}
