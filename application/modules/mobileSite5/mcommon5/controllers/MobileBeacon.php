<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MobileBeacon extends ShikshaMobileWebSite_Controller
{

    function __construct()
    {
            parent::__construct();
            $this->load->model('smsModel');
    }

    function mobilebeacon()
    {
            /* GET DATA ,PARSE & PREAPER DATA */
            $resultArray = $this->_parseInput($_REQUEST);

            /* STORE DATA INTO DB */
            $this->_saveData($resultArray);

	    //Remove CSS3 Tracking for now, since it is not required
            /* GET CSS DATA ,PARSE & PREAPER DATA */
            //$resultArray = $this->_parseCSSInput($_REQUEST);

            /* STORE CSS DATA INTO DB */
            //$this->_saveCSSData($resultArray);
            
            /* RENDER IMAGE */
            $this->_renderImage();
    }

    function _parseInput($array)
    {
            $resultArray = array();

            $resultArray['boomr_pageid'] = isset($array['boomr_pageid'])?$array['boomr_pageid']:"0";
            $resultArray['bandwidth'] = isset($array['bw'])?$array['bw']:"0";
            $resultArray['bandwidth_err'] = isset($array['bw_err'])?$array['bw_err']:"0";
            $resultArray['ip_address'] = isset($array['ip_address'])?$array['ip_address']: ip2long(S_REMOTE_ADDR);
            $resultArray['latency'] = isset($array['lat'])?$array['lat']:"0";
            $resultArray['latency_err'] = isset($array['lat_err'])?$array['lat_err']:"0";
            $resultArray['ref_url'] = isset($array['r'])?$array['r']:"0";
            $resultArray['current_url'] = isset($array['u'])?$array['u']:"0";
            $resultArray['perceived_loadtime_page'] = isset($array['t_done'])?$array['t_done']:"0"; // Perceived load time of the page.
            $resultArray['time_head_page_ready'] = isset($array['t_page'])?$array['t_page']:"0"; // Time taken from the head of the page to page_ready.
            $resultArray['time_head_page_first_byte'] = isset($array['t_resp'])?$array['t_resp']:"0"; // Time taken from the user initiating the request to the first byte of the response.
            
            $other_timers = isset($array['t_other'])?$array['t_other']:"0"; 
            $b  = explode("|", $other_timers);
            foreach ($b as $c)
            {
                if(strstr($c, 't_head'))
                {
                    $tmp = explode(",", $c);
                    $result['t_head'] = $tmp[0];
                }

                if(strstr($c, 't_body'))
                {
                    $tmp = explode(",", $c);
                    $result['t_body'] = $tmp[0];
                }
            }
            $resultArray['t_head'] = isset($result['t_head'] )?$result['t_head'] :"0";
            $resultArray['t_body'] = isset($result['t_body'] )?$result['t_body'] :"0";

            $resultArray['logged_at'] = date("Y-m-d H:i:s");
            $resultArray['session_id'] = isset($array['session_id'])?$array['session_id']:sessionId();
            $resultArray['user_id'] = isset($array['userid'])?$array['userid']:"0";
            $resultArray['user_agent'] = isset($array['user_agent'])?$array['user_agent']:$_SERVER['HTTP_USER_AGENT'];
            $resultArray['server_p_time'] = isset($array['server_p_time'])?$array['server_p_time']:"0";
            $resultArray['hml5applicationcache'] = ($array['hml5applicationcache'] == 'true')?"1":"0";                        
            $resultArray['hml5canvas'] = ($array['hml5canvas'] == 'true')?"1":"0";    
            $resultArray['hml5frmdate'] = ($array['hml5frmdate'] == 'true')?"1":"0";
            $resultArray['hml5frmsautofocus'] = ($array['hml5frmsautofocus'] == 'true')?"1":"0";
            $resultArray['hml5geolocation'] = ($array['hml5geolocation'] == 'true')?"1":"0";
            $resultArray['hml5history'] = ($array['hml5history'] == 'true')?"1":"0";
            $resultArray['hml5localstorage'] = ($array['hml5localstorage'] == 'true')?"1":"0";
            $resultArray['hml5video'] = ($array['hml5video'] == 'true')?"1":"0";
            $resultArray['hml5webworkers'] = ($array['hml5webworkers'] == 'true')?"1":"0"; 
            $resultArray['activity_type'] = isset($array['activity_type'])?$array['activity_type']:"request";
            $resultArray['activity_type_value'] = isset($array['activity_type_value'])?$array['activity_type_value']:"";
            
            $resultArray['isHTML5Site'] = isset($array['isHTML5Site'])?$array['isHTML5Site']:"0";
	    
			if(isset($array['sourceSite'])){
			$resultArray['sourceSite'] = $array['sourceSite'];
			}
            
            return $resultArray;
    }

    function logdataFrmbackend($type,$value,$userid)
    {
             $data = array("activity_type" =>$type, "activity_type_value" =>$value, "userid"=>$userid);
             $resultArray = $this->_parseInput($data);
             $this->_saveData($resultArray);
    }

    function _saveData($array)
    {       
            $this->load->model('ShikshaModel');
            $this->ShikshaModel->insertmobileTracking($array);
            return true;
    }

    function _renderImage()
    {
        //  Send a BEACON image back to the user's browser
        header( 'Content-type: image/gif' );
        # The transparent, beacon image
        echo chr(71).chr(73).chr(70).chr(56).chr(57).chr(97).
          chr(1).chr(0).chr(1).chr(0).chr(128).chr(0).
          chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).
          chr(33).chr(249).chr(4).chr(1).chr(0).chr(0).
          chr(0).chr(0).chr(44).chr(0).chr(0).chr(0).chr(0).
          chr(1).chr(0).chr(1).chr(0).chr(0).chr(2).chr(2).
          chr(68).chr(1).chr(0).chr(59);
    }

    function displaymobilesitereport()
    {
          ini_set('memory_limit','1024M');
          set_time_limit(0);
         $this->load->model('ShikshaModel');
         $date = date('Y-m-d 00:00:00');
         $data = $this->ShikshaModel->shikshamobilesitereport($date);
         _p($data);
    }

    function generateMobileMIS()
    {
        set_time_limit(0);

        $this->load->library('Alerts_client');
        $alerts_client = new Alerts_client();
        
        $this->load->model('ShikshaModel');
        $date = date('Y-m-d 00:00:00');
        $dateYesterday = strtotime("-1 days",strtotime($date));
        $dateYest = date ( 'Y-m-j' , $dateYesterday );
	$dateDisplay = date ( 'd/m/Y' , $dateYesterday );

        $siteData = $this->ShikshaModel->shikshamobilesitereport($dateYest);
        $performanceData = $this->ShikshaModel->shikshamobileperformancereport($dateYest);

        $data['siteDate'] = $siteData;
        $data['performanceData'] = $performanceData;
        $data['date'] = $dateDisplay;
        
        $content = $this->load->view('mobileMIS', $data, true);
        $subject = "Mobile MIS ".$dateDisplay;
        $senderList = array('ankur.gupta@shiksha.com','nasr.khan@shiksha.com','ravi.raj@shiksha.com','shalabh@brijj.com','prakash.sangam@naukri.com','puja.kamath@shiksha.com','anurag.jain@shiksha.com','soumendu.g@naukri.com','anil.narayanan@shiksha.com');
        foreach ($senderList as $senderEmail){
            $response = $alerts_client->externalQueueAdd("12", ADMIN_EMAIL, $senderEmail, $subject, $content, "html", "0000-00-00 00:00:00");
        }
    }

    function _parseCSSInput($array)
    {
            $resultArray = array();
            $resultArray['logged_at'] = date("Y-m-d H:i:s");
            $resultArray['session_id'] = isset($array['session_id'])?$array['session_id']:sessionId();
            $resultArray['user_id'] = isset($array['userid'])?$array['userid']:"0";
            $resultArray['user_agent'] = isset($array['user_agent'])?$array['user_agent']:$_SERVER['HTTP_USER_AGENT'];
            $resultArray['mediaqueries'] = ($array['mediaqueries'] == 'true')?"1":"0";
            $resultArray['fontface'] = ($array['fontface'] == 'true')?"1":"0";
            $resultArray['backgroundsize'] = ($array['backgroundsize'] == 'true')?"1":"0";
            $resultArray['borderimage'] = ($array['borderimage'] == 'true')?"1":"0";
            $resultArray['borderradius'] = ($array['borderradius'] == 'true')?"1":"0";
            $resultArray['boxshadow'] = ($array['boxshadow'] == 'true')?"1":"0";
            $resultArray['flexbox'] = ($array['flexbox'] == 'true')?"1":"0";
            $resultArray['opacity'] = ($array['opacity'] == 'true')?"1":"0";
            $resultArray['cssanimations'] = ($array['cssanimations'] == 'true')?"1":"0";
            $resultArray['cssgradients'] = ($array['cssgradients'] == 'true')?"1":"0";
            $resultArray['cssreflections'] = ($array['cssreflections'] == 'true')?"1":"0";
            $resultArray['csstransforms'] = ($array['csstransforms'] == 'true')?"1":"0";
            $resultArray['csstransitions'] = ($array['csstransitions'] == 'true')?"1":"0";
            $resultArray['activity_type'] = isset($array['activity_type'])?$array['activity_type']:"request";
            return $resultArray;
    }

    function _saveCSSData($array)
    {       
            $this->load->model('ShikshaModel');
            $this->ShikshaModel->insertmobileTrackingForCSS($array);
            return true;
    }
    
}
