<?php

namespace Modules\Backend\Main\Helpers;

class WebserviceHelper {
	private $_wsdl;
	function __construct($wsdl) {
	    $this->_wsdl = $wsdl;
	}
	public function _getData($fn_name,$arrParam){
		$error=0;
		try
		{ 
		  $client = new \SoapClient($this->_wsdl);
		  $info = $client->__call($fn_name, $arrParam);
		}catch(Exception $e){
		   echo "a"; exit;
		  $error = 1; 
		  errorReport($fault->faultcode,$fault->faultstring);
		  die;
        }
	    if($error==1)
	    {
	         $result=$fault->faultstring;
	    }else{
	        $result = $info;
	    }
		return (array)json_decode($result);
	}	
}