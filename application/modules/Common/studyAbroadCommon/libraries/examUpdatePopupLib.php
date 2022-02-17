<?php 
class examUpdatePopupLib{
	private $CI;
    public function __construct() {
        $this->CI = & get_instance();
       
		$this->CI->load->model('studyAbroadCommon/examupdatepopupmodel');
		$this->examupdatepopupmodel = new examupdatepopupmodel();
		
		$this->CI->load->library('listingPosting/AbroadCommonLib');
		$this->AbroadCommonLib = new AbroadCommonLib();
    }

    public function getExamMasterListForPopup(){
    	$finalData = array();
    	$examData =  $this->AbroadCommonLib->getAbroadExamsMasterList('', 0, true);
    	foreach ($examData as $value) {
    		$finalData[$value['exam']] = array('id'=>$value['examId'], 'min'=>$value['minScore'], 'max'=>$value['maxScore']);
    	}
    	return $finalData;
    }

    public function checkIfPopupToBeShown($userId){

        if(empty($userId) || !(is_numeric($userId)) || $userId<=1 ){
    		return array("popupShowFlag"=>false);
    	}
        if($_COOKIE['examUpdateSA'] == $userId){
            return array("popupShowFlag"=>false);
        }
    	$popupShowFlag = false;
        $this->userLib = $this->CI->load->library('user/UserLib');
        $userExamInfo = $this->userLib->getUserCurrentExamDetails($userId);
        $popupInfo = $this->examupdatepopupmodel->getPopupShownInfo(array($userId));
        if((empty($userExamInfo) || empty($userExamInfo[$userId])) && empty($popupInfo)){
            return array("popupShowFlag"=>true, "latestPopUpStatus"=>no);
        }
        $examGiven = $userExamInfo[$userId]['examGiven'];
        $submitDate = $userExamInfo[$userId]['submitDate'];
        $userExamInfoSts = $this->_checkForValidExamScore($userExamInfo[$userId]['examDetails']);
        if(!($userExamInfoSts)){
            $date = empty($popupInfo) ? $submitDate : max($submitDate,$popupInfo[$userId]);
            if($examGiven=='booked'){
                //show popup in 14 days
                if(abs(strtotime(date('Y-m-d 00:00:00')) - strtotime($date))/(60*60*24) > 14){
                    $popupShowFlag = true;
                }
            }else if($examGiven=='no'){
                //show popup in 30 days
                if(abs(strtotime(date('Y-m-d 00:00:00')) - strtotime($date))/(60*60*24) > 30){
                    $popupShowFlag = true;
                }
            }
            if($examGiven=='booked' || $examGiven=='no' || $examGiven=='yes'){
                $this->setCookieForPopupDaysCount($examGiven, $userId, $date);
            }
        }
    
        return array("popupShowFlag"=>$popupShowFlag, "latestPopUpStatus"=>$examGiven);
    }

    private function _checkForValidExamScore(&$examData){
        $exams = $this->getExamMasterListForPopup();
        $count = 0;
        foreach ($examData as $key => $value) {
            if($count>0) return true;
            if($exams[$key]['min']<=$value && $exams[$key]['max']>=$value){
                $count++;
            }
        }
        if($count>0) {return true;}
        return false;
    }

    public function validatePopupPOSTData($action, $examData){
    	$actions = array('yes', 'no', 'booked', 'later');
    	$validateFlag = true;
    	if(!in_array($action, $actions)){
    		$validateFlag = false;
    	}
    	if($action == 'yes' && !empty($examData)){
    		$examMasterData =  $this->AbroadCommonLib->getAbroadExamsMasterList();
    		foreach ($examMasterData as $value) {
    			if(isset($examData[$value['examId']]) && !is_numeric($examData[$value['examId']])){
    				$validateFlag = false;
    			}
    			if(isset($examData[$value['examId']]) && ($examData[$value['examId']] < $value['minScore'] || $examData[$value['examId']] > $value['maxScore'])){
    				$validateFlag = false;
    			}
    		}
    	}
    	return $validateFlag;
    }

    public function addPopupTracking($tData,$uAction){
        if($tData['MISTrackingId']>0 && $tData['userId']>0 && in_array($uAction, array('yes','booked')))
        {            
            // check if user is in 'Dropoff' stage, bring them back to 'Ready'
		    $rmcPostingLib = $this->CI->load->library('shikshaApplyCRM/rmcPostingLib');
            $rmcPostingLib->moveStudentFromDropoffToReady($tData['userId'],"Student updated their exam scores via a post-login popup.");
            // send to ldb exclusion
            $extraDataForExclusion = array(
                'tracking_keyid' => $tData['MISTrackingId'],
                'examScoreUpdated' => true
            );
            $userLib = $this->CI->load->library('user/UserLib');
            $userLib->checkUserForLDBExclusion($tData['userId'],'registration','','','','','',$extraDataForExclusion);
        }
    	$this->examupdatepopupmodel->addPopupTracking($tData);
    }

    public function setCookieForPopupDaysCount($uAction, $userId, $date = ''){
        $time = '';
        if(empty($date)){
            $time = time();
        }else{
            $time = strtotime($date);
        }

        if($uAction == 'booked'){
            setcookie('examUpdateSA', $userId, $time + (60*60*24*14), '/', COOKIEDOMAIN);
        }else if($uAction == 'no'){
            setcookie('examUpdateSA', $userId, $time + (60*60*24*30), '/', COOKIEDOMAIN);
        }
    }
    /**
     * get mis tracking id for conversiontype : examScoreUpdate & given pageIdentifier
     * @params : pageIdentifier
     * @returns : tracking id (int) or null
     */
    public function getTrackingKeyForExamScoreUpdate($pageIdentifier)
    {
        $trackingKeyForExamUpdate = $this->examupdatepopupmodel->getTrackingKeyForExamScoreUpdate($pageIdentifier,isMobileRequest());
        return $trackingKeyForExamUpdate;
    }
}