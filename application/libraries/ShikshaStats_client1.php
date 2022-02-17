<?php

class ShikshaStats_client1{

    var $CI_Instance;

    var $cacheLib;
    function init(){
        $this->CI_Instance = & get_instance();
        $this->CI_Instance->load->helper('url');
        $server_url = "http://snapdragon.infoedge.com/index.php/shikshaStats/shikshaStatsServer1";
        $this->CI_Instance->load->library('xmlrpc');
        $this->CI_Instance->xmlrpc->set_debug(0);
        $this->CI_Instance->xmlrpc->server($server_url, '80');
        $this->CI_Instance->load->library('cacheLib');
        $this->cacheLib = new cacheLib();
    }

    function getData($timeDuration,$timeUnit,$parentId,$id,$client,$order,$userid=0){ //requestArray is actually _POST array
        $this->init();
       // error_log("i am here at client");
        $this->CI_Instance->xmlrpc->method('getData');
        //                $request = array(array($appID,'int'),array(array("listing_title" => "TESTtitle","contact_email" => "temp@email.com"),'struct'));
        $request = array(
            array($timeDuration,'string'),
            array($timeUnit,'string'),
            array($parentId,'string'),
            array($id,'string'),
            array($client,'string'),
            array($order,'int'),
            array($userid,'int'),
          );
          //            error_log("CLIENT REQUEST ARRAY".print_r($request,TRUE));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
	    error_log("HIHI");
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }
    }
}
?>
