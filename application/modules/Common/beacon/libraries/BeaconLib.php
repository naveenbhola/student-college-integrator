<?php
/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: amitj $:  Author of last commit
$Date: 2008/09/09 06:12:09 $:  Date of last commit

event_cal_client.php makes call to server using XML RPC calls.

$Id: BeaconLib.php,v 1.8 2008/09/09 06:12:09 amitj Exp $: 

*/

class BeaconLib{
    var $CI_Instance;
    function loadCache() {
			$this->CI_Instance->load->library('cacheLib');
            $cacheLibObj = new cacheLib();

	    $myFile = "/var/www/html/shiksha/beacon_conf";
	    $fh = fopen($myFile, 'r');
	    $theData = fread($fh, filesize($myFile));
	    //	    error_log_shiksha($theData);
	    fclose($fh);
	    $lineData =  split("\n", $theData);
	    $k = 0;
	    $lineDataNew = null;
	    //	    error_log_shiksha(print_r($lineData,true));
	    for($i = 0; $i < count($lineData); $i++) {
		    if(substr($lineData[$i],0,1) == "#") {
			    continue;
		    }else {
			    $lineDataNew[$k] = ($lineData[$i]);
			    $k++;
		    }
	    }
	    //	    error_log_shiksha(print_r($lineDataNew,true));
	    for($i = 0; $i < count($lineDataNew); $i++) {
		    $array =  split(" ", $lineDataNew[$i]);

		    $dataStore = "";
		    for($k = 1;$k < count($array); $k++) {
			    if($k == 2) {
				    $temp = $array[$k];
				    switch ($array[$k]){
					    case 'MESSAGEBOARD_SERVER':
						    $array[$k] = MESSAGEBOARD_SERVER;
						    break;
					    case 'BLOG_SERVER':
						    $array[$k] = BLOG_WRITE_SERVER;
						    break;
					    case 'Listing_SERVER_URL':
						    $array[$k] = Listing_SERVER_URL;
						    break;
					    case 'SA_SERVER_URL':
						    $array[$k] = SA_SERVER_URL;
						    break;
						case 'EXAM_PAGE_SERVER':
							$array[$k] = EXAM_PAGE_SERVER;
							break;
				    }
			    }
			    $dataStore .=  $array[$k]." ";
		    }
                    error_log_shiksha("MYLOGS::".$array[0].'   '.$dataStore);
		    $cacheLibObj->store("b_".$array[0],$dataStore);
		    $dataStore = "";
	    }
    }

        
    function init(){
	    $this->CI_Instance = & get_instance();
	    $this->CI_Instance->load->library('xmlrpc');
	    $this->CI_Instance->xmlrpc->set_debug(0);
    }

    //get category list based on category ID
    function update($productId=null,$id=null,$userId){
        $this->init();

        $this->CI_Instance->load->library('cacheLib');
        $cacheLibObj = new cacheLib();

	$data = $cacheLibObj->get("b_".$productId);
	error_log_shiksha("Product id = ".$productId);
	error_log_shiksha("id = ".$id);
        if($data == 'ERROR_READING_CACHE') {
            $this->loadCache();
	    $data = $cacheLibObj->get("b_".$productId);
        }
	$array =  split(" ", $data);
	if($array[0] == "1"){
	    $this->CI_Instance->xmlrpc->server($array[1], 80);	
	    error_log_shiksha("server url = ".$array[1]);
	    error_log_shiksha("method = ".$array[2]);
	    $this->CI_Instance->xmlrpc->method($array[2]);  
            $idArr = explode(" ", $id);
	    $request = array();
            array_push($request,1);
            for($i = 0; $i < count($idArr); $i++) {
                error_log_shiksha("\nPushing ".$idArr[$i]."\n");
                array_push($request,$idArr[$i]);
            }
	    //Push the session id in the request array
            array_push($request,sessionId());
	    
	    //Push the user id in the request array
	    array_push($request,$userId);
	    
	    $this->CI_Instance->xmlrpc->request($request);	
	    if ( ! $this->CI_Instance->xmlrpc->send_request()){
		    return $this->CI_Instance->xmlrpc->display_error();
	    }else{
		    return ($this->CI_Instance->xmlrpc->display_response());
	    }	
	}
    }
}
?>
