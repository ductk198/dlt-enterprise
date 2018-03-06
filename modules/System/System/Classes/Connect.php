<?php

namespace Modules\System\System\Classes;

use DB;

/**
 * Helper hỗ trợ module user.
 *
 * @author Duclt
 */
class Connect
{
	private $conn;
	// tao connect moi
	public function __construct($namedb){
        $serverName = env('DB_HOST');
        $connectionInfo = array( "Database"=>$namedb, "UID"=>env('DB_USERNAME'), "PWD"=>env('DB_PASSWORD'), "CharacterSet" => "UTF-8");
        $conn = sqlsrv_connect( $serverName, $connectionInfo );
        if( $conn === false ) {
         die( print_r( sqlsrv_errors(), true));
        }
		$this->conn = $conn;
    }
	
	public function excute($sql){
        
    }

    public function getResult($sql){
        $conn = $this->conn;
		$stmt = sqlsrv_query( $conn, $sql);
		$Result = array();
		if($stmt){
			$i= 0;
			while( $ArrResult = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
				$Result[$i] = $ArrResult;
				$i++;
			}
		}else{
			$arrError = sqlsrv_errors();
			$Result = $arrError[0]['message'];
		}
		return $Result;
	}
	
	public function getResult_excute($sql){
        $conn = $this->conn;
		$stmt = sqlsrv_query( $conn, $sql);
		$Result = array();
		if($stmt){
			$i= 0;
			$Result['message'] = 'result';
			while( $ArrResult = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
				$Result['data'][$i] = $ArrResult;
				$i++;
			}
		}else{
			$Result['message'] = 'error';
			$arrError = sqlsrv_errors();
			$Result['data'] = $arrError[0]['message'];
		}
		
		if(!isset($Result['data'])){
			$Result['data'] = "Command(s) completed successfully.";
			$Result['message'] = 'error';
		}
		return $Result;
    }
}
