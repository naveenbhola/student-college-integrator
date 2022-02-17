<?php
class ChpClient
{
    private $CI;
    function __construct() {
        $this->CI =& get_instance();
    }

    function makeCURLCall($method, $url, $data,$headerArray = ""){
  
    	if(empty($headerArray))
    	{	
    		$headerArray = array('Content-Type:application/json');
    	}

    	if(function_exists('getallheaders')) {
    		$headers = getallheaders();
	    	if($headers['X-transaction-ID']){
	    		$headerArray[] = 'x-transaction-id:'.$headers['X-transaction-ID'];
	    	}	
    	}

		$c = curl_init();
		switch ($method){
	      case "POST":
	         	curl_setopt($c, CURLOPT_POST, 1);
	         if ($data)
	            curl_setopt($c, CURLOPT_POSTFIELDS, $data);
	         break;
	      case "GET":
	      		curl_setopt($c, CURLOPT_POST, 0);
	         	curl_setopt($c, CURLOPT_HTTPGET, 1);
	         if ($data)
	            $url = sprintf("%s?%s", $url, http_build_query($data));
	         break;
	      default:
	         if ($data)
	            $url = sprintf("%s?%s", $url, http_build_query($data));
		}
		
		curl_setopt($c, CURLOPT_HTTPHEADER, $headerArray);
   		curl_setopt($c, CURLOPT_URL,$url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($c, CURLOPT_TIMEOUT, 10);
        
        $cookies = array();
		foreach ($_COOKIE as $key => $value)
		{
		    if ($key != 'Array')
		    {
		        $cookies[] = $key . '=' . $value;
		    }
		}
		curl_setopt( $c, CURLOPT_COOKIE, implode(';', $cookies) );
        $output =  curl_exec($c);
       	if(!$output){die("Connection Failure");}
        curl_close($c);
        return $output;
    }

	//Desc - this is used to get chp other topics    
	//$params - should be an array('key'=>value)
    function getCHPInterLinking($pageType,$params){
		$this->CI->load->config("chp/chpAPIs");
		if($pageType == 'UILP'){
       		$apiUrl = $this->CI->config->item('GET_CHP_OTHER_TOPICS_UILP');
       		$bips = implode(',', $params['bips']);
       		$sips = implode(',', $params['sips']);
       		if(!empty($bips) || !empty($sips)){
       			$queryParams = $this->build_http_query(array('bips'=>$bips,'sips'=>$sips));
       			$apiUrl = $apiUrl.'?'.$queryParams;
				return $this->makeCURLCall('GET', $apiUrl);
       		}
		}else if($pageType == 'CLP'){
			$apiUrl = $this->CI->config->item('GET_CHP_OTHER_TOPICS_CLP');
			$limit = $params['limit'];
			if(!empty($params['courseObj']) && is_object($params['courseObj'])){
	            $courseObj  = $params['courseObj'];
	            $hierarchy  = $courseObj->getPrimaryHierarchy();   
	            $baseCourse = $courseObj->getBaseCourse();
	            $baseCourse = $baseCourse['entry'];
	            return $this->makeCURLCall('GET', $apiUrl, array('stream'=>$hierarchy['stream_id'],'substream'=>$hierarchy['substream_id'],'spec'=>$hierarchy['specialization_id'],'basecourse'=>$baseCourse,'limit'=>$limit));
        	}
		}
    }

    /*
    Dummy data should be like this.
    Array
	(
	    [streamId] => 1
	    [substreamId] => 0
	    [specId] => 0
	    [limit] => 5
	)
	Output => streamId=1&substreamId=0&specId=0&limit=5
	*/
    function build_http_query($query) {
	    $query_array = array();
	    foreach( $query as $key => $key_value ){
	        $query_array[] = urlencode( $key ).'='.$key_value;
	    }
	    return implode( '&', $query_array );
    }
}
?>
