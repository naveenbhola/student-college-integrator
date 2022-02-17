<?php 
/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: amitj $:  Author of last commit
$Date: 2008-09-09 06:12:09 $:  Date of last commit

Events.php controller for events.

$Id: Beacon.php,v 1.6 2008-09-09 06:12:09 amitj Exp $: 

*/

class Beacon extends MX_Controller {
	function init() {
		$this->load->library('ajax');
		$this->load->library('beaconLib');
		$this->load->library('beacon/BeaconTracking');
    }

    function index($rand = null,$productId = null, $id=null) {
    	ini_set("max_execution_time","4");
		if(!is_numeric($rand) || !is_numeric($productId) || (strpos($_SERVER['HTTP_HOST'],'shiksha.com') === false && ENVIRONMENT == "production"))
		{
			show_404();
		}
		/* Adding XSS cleaning (Nikita) */
		$rand = $this->security->xss_clean($rand);
		$productId = $this->security->xss_clean($productId);
		$id = $this->security->xss_clean($id);
		
		$idArr = explode(" ", $id);
		foreach ($idArr as $key => $value) {
			if(!preg_match("/^[A-Za-z0-9_]*$/",$value)){
				return;
			}
		}

		$this->init();
		
		$userInfo = $this->checkUserValidation();
		
		if($userInfo == 'false'){
			$userId = 0;
		}
		else{
			$userId = $userInfo[0]['userid'];
		}
		
		$catList = array();
		$EventCalClientObj = new BeaconLib();
        error_log_shiksha("id = $id");
        $recentEventsList =  $EventCalClientObj->update($productId, $id, $userId);
        header('Content-Type: image/gif');
        //echo file_get_contents('public/images/blankImg.gif');
        $str = 'GIF89a\x05';
        echo $str;
    }
	
	function track($rand, $pageIdentifier, $pageEntityId)
	{
		ini_set("max_execution_time","4");
		$rand = $this->security->xss_clean($rand);
		$pageIdentifier = $this->security->xss_clean($pageIdentifier);
		$pageEntityId = $this->security->xss_clean($pageEntityId);
		$startTime = microtime(TRUE);
        //exit();
		$this->init();
		
		$loggedInUserInfo = $this->checkUserValidation();
		if($loggedInUserInfo == 'false'){
			$userId = 0;
		}
		else{
			$userId = $loggedInUserInfo[0]['userid'];
		}
		
		$beaconTracking = new BeaconTracking();
		
		$sessionTrackStartTime = microtime(TRUE);
		$beaconTracking->trackSession($userId, $pageIdentifier, $pageEntityId);
		$sessionTrackEndTime = microtime(TRUE);
		
		$pageviewTrackStartTime = microtime(TRUE);
		$beaconTracking->trackPageView($userId, $pageIdentifier, $pageEntityId);
		$pageviewTrackEndTime = microtime(TRUE);
		
		if(isset($_REQUEST['isApp']) && $_REQUEST['isApp'] == "yes" ){
                	echo json_encode(array("visitorSessionId" => getVisitorSessionId(), "visitorId" => getVisitorId()));
		}else{
			header('Content-Type: image/gif');
		echo file_get_contents('public/images/blankImg.gif');
		}		
//                echo $str; probably unused
		$endTime = microtime(TRUE);
		
		$totalTimeTaken = $endTime - $startTime;
		if($totalTimeTaken >= 1) {
			$sessionTrackTime = $sessionTrackEndTime - $sessionTrackStartTime;
			$pageviewTrackTime = $pageviewTrackEndTime - $pageviewTrackStartTime;
			error_log("BeaconTime: ".$sessionTrackTime." -- ".$pageviewTrackTime);
		}
	}

	// load beacon conf cache
	function loadCache(){

		$this->init();
		$BeaconLib = new BeaconLib();
		$BeaconLib->init();
        $BeaconLib->loadCache();
	}

	function trackViewCountData(){
		$this->validateCron();		
		$indexer = $this->load->library('beacon/TrafficIndexer',array("timeOutRequired"=>"no","ESHost"=>"ES6"));

		$beaconList = array("examPageMain");
		$previousDay = date('Y-m-d',strtotime("-1 day"));
		$startDate = $previousDay." 00:00:00";
		$startDate = convertDateISTtoUTC($startDate);
		$startDate = str_replace(" ", "T", $startDate);

		$endDate   = $previousDay." 23:59:59";
		$endDate = convertDateISTtoUTC($endDate);
		$endDate = str_replace(" ", "T", $endDate);

		foreach ($beaconList as $beacon) {
			if($beacon == "examPage"){
				$viewCountData = $indexer->trackViewCount($beacon, $startDate, $endDate, 'no');
				if($viewCountData !== false){
					$beaconModel = $this->load->model('beacon/beaconmodel');
					$beaconModel->updateViewCount($beacon,$viewCountData);
				}
			}
		}
	}
}
?>
