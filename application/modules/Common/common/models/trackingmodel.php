<?php
class TrackingModel extends MY_Model {

    function __construct(){
        parent::__construct('Beacon');
		
    }
	
	function trackPageLoad($userId,$sessionId,$isLDBUser,$url,$referalUrl,$personalized,$personalizedArray){
		$this->db = $this->dbLibObj->getWriteHandle();
		$data = array(
					  'sessionId' => $sessionId,
					  'referalURL' => $referalUrl,
					  'URL' => $url,
					  'userId' => $userId,
					  'isLDBUser' => $isLDBUser,
					  'isPersonalized' => $personalized,
					  'personalizedCategory' => $personalizedArray['categoryId'],
					  'personalizedCountry' => $personalizedArray['countryId'],
					  'personalizedRegion' => $personalizedArray['regionId'],
					  'personalizedType' => $personalizedArray['type']
					  );
		$queryCmd = $this->db->insert_string('shikshaTracking'.date("mY"),$data);
		$query = $this->db->query($queryCmd);
	}
}