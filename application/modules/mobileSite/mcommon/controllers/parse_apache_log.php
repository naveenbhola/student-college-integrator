<?php
// ====================================================================
// CREATE TABLE `access_log` (
//  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
//  `resolution_width` char(5) DEFAULT NULL,
//  `resolution_height` char(5) DEFAULT NULL,
//  `url` varchar(1000) DEFAULT NULL,
//  `is_js_support` char(1) DEFAULT 'n',
//  `brand_name` varchar(200) DEFAULT NULL,
//  `ip` char(50) DEFAULT NULL,
//  `ua` text,
//  `screen` varchar(200) DEFAULT NULL,
//  PRIMARY KEY (`id`),
//  KEY `ip` (`ip`)
//) ENGINE=MyISAM 
// ------------------------------------------------------------------


class parse_apache_log extends MX_Controller {

	/**
	 * [runjob description]
	 * @return [type] [description]
	 */
    public function runjob()
    {
    	error_reporting(0);
    	set_time_limit(0);
    	ini_set('memory_limit', '2048M');
    	$this->getData("/home/raviraj/access_log.1");
    }
    /**
	 * [runjob description]
	 * @return [type] [description]
	 */
	public function getData($file,$limit = FALSE)
	{
		/**
         * As per discussion with Romil, this code is not getting used.
         * So commenting it as of now to avoid sql injection issues.
         *
         * if(!is_readable($file))
		{
			return 	FALSE;
		}
		$handle = fopen($file, 'rb');
		if ($handle) {
			$count = 1;
			$lines = array();
		    while (!feof($handle)) {
		    	
		    	$result = array();
		    	$arr    = array();

		        $buffer = fgets($handle);
		        
		        $arr = $this->parseAccessLog($buffer);
		        
		        //echo $arr['ip'];
		        //echo $arr['method']
		        //echo $arr['URI']
		        //echo $arr['userAgent'];
				
		        $this->load->library('mcommon/wurfl');

		        $wurfl_obj = new Wurfl;
		        
		        $wurfl_obj->load($arr['userAgent']);
                
                if ( 
                		($wurfl_obj->getCapability('is_wireless_device') == "true") &&
                		(strlen($arr['userAgent']) > 10)
                   )
                {
                		$result['resolution_height']       = $wurfl_obj->getCapability('resolution_height');
                		$result['resolution_width']        = $wurfl_obj->getCapability('resolution_width');
                		$result['brand_name']              = $wurfl_obj->getCapability('brand_name');
                		$result['ajax_support_javascript'] = $wurfl_obj->getCapability('ajax_support_javascript');
                		$result['ip_address'] 		       = $arr['ip'];
                		$result['url'] 				       = $arr['URI'];
                		$result['userAgent']               = $arr['userAgent'];

                		if (!$link = mysql_connect('localhost', 'root', 'root')) {
					    	echo 'Could not connect to mysql';
					    	exit;
					    }

						if (!mysql_select_db('mobile_log', $link)) {
					    	echo 'Could not select database';
					    	exit;
						}
						
						$is_js_support = 's'; // not sure

                        if ( $result['ajax_support_javascript'] == 'false') {
                        		$is_js_support = 'n';
                        }
                        if($result['ajax_support_javascript'] == 'true'){
                        		$is_js_support = 'y';
                        }
						  
						$screen = $result['resolution_width'] . "X" . $result['resolution_height'];
                		
                		$sql = "insert into access_log set 
                		resolution_width='".$result['resolution_width']."',
                		resolution_height='".$result['resolution_height']."',
                		is_js_support = '".$is_js_support."' ,
                		brand_name='".$result['brand_name']."' ,
                		ip='".$result['ip_address'] ."',
                		ua='".addslashes($result['userAgent'])."' ,
                		screen='".$screen."'";
                		
                		echo "\n" . $sql . "\n";
                		
                		mysql_query($sql, $link);
                }

		        if($limit && $count == $limit)
		        {
		        	break;
		        }
		        $count++;

		        unset($arr);
		        unset($result);
		    }

		    fclose($handle);
		}*/
	}

	/**
	 * [runjob description]
	 * @return [type] [description]
	 */
    
    function parseAccessLog($line)
    {
	    $res = array();
	    $pattern = "/^([\d]+\.[\d]+\.[\d]+\.[\d]+)(.*)/";
	    preg_match($pattern,$line,$t);
	    $res["ip"] = $t[1];
	    $line = trim($t[2]);
	    $pattern = "/^- -(.*)/";
	    preg_match($pattern,$line,$t);
	    $line = trim($t[1]);
	    $pattern = "/^\[(.*) \+0530\](.*)/";
	    preg_match($pattern,$line,$t);
	    $res["timestamp"] = $t[1];
	    $line = $t[2];
	    $arr = explode("\"",$line);
	    $arr1 = explode(" ",$arr[1]);
	    $res["method"] = $arr1[0];
	    $res["URI"] = $arr1[1];
	    $res["protocol"] = $arr1[2];
	    $arr1 = explode(" ",trim($arr[2]));
	    $res["returnCode"] = $arr1[0];
	    $res["size"] = $arr1[1];
	    $res["subdomain"] = $arr[3];
	    $res["googleUrl"] = $arr[5];
	    $pattern = "@q\=([^\&]*)@";
	    preg_match($pattern,$arr[5],$t);
	    $res["googleKeyword"] = isset($t[1])?urldecode(str_replace("+"," ",$t[1])):"";
	    $res["userAgent"] = $arr[7];
	    unset($arr);
	    unset($arr1);
	    unset($line);
	    unset($pattern);
	    return $res;
	}

}