<?php
class examUpdatePopup extends MX_Controller{
    private $pagesToSkip;
    private $pagesToReload;
    private $validateuser;
    public function __construct(){
        parent::__construct();
        $this->validateuser  = $this->checkUserValidation();
        $this->pagesToSkip   = array('AbroadSignup', 'loginPage', 'profileSettingPage', 'editUserProfilePage');
        $this->pagesToReload = array('coursePage', 'universityPage');
    }

    private function _init(){
    	$this->load->library('studyAbroadCommon/examUpdatePopupLib');
        $this->examUpdatePopupLib = new examUpdatePopupLib();

        $this->load->library('listingPosting/AbroadCommonLib');
		$this->AbroadCommonLib = new AbroadCommonLib();
    }

    public function checkIfExamScoreUpdatePopupToBeShown(){
        $params = $this->input->post('params', true);
        $popupShowFlag = false;
        $examMasterList= array();
        if($this->validateuser!= 'false' && !in_array($params['pageIdentifier'], $this->pagesToSkip)){
            $this->_init();
            $result = $this->examUpdatePopupLib->checkIfPopupToBeShown($this->validateuser[0]['userid']);
            $popupShowFlag = $result['popupShowFlag'];
            $latestPopUpStatus = $result['latestPopUpStatus'];
            if($popupShowFlag){
                $examMasterList = $this->examUpdatePopupLib->getExamMasterListForPopup();
            }
        }
        echo json_encode(array('popupShowFlag'=>$popupShowFlag, 'examMasterList'=>$examMasterList, 'latestPopUpStatus'=>$latestPopUpStatus));
        exit;
    }

    public function checkIfExamScoreUpdatePopupToBeShownAPI(){
        $requestHeader = ($_SERVER['HTTP_ORIGIN'] != null) ? $_SERVER['HTTP_ORIGIN'] : SHIKSHA_HOME;
        header("Access-Control-Allow-Origin: ".$requestHeader);
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
        header("Content-Type: application/json; charset=utf-8");
        $this->checkIfExamScoreUpdatePopupToBeShown();
    }

    public function saveExamScoreFromPopupAPI(){
        $requestHeader = ($_SERVER['HTTP_ORIGIN'] != null) ? $_SERVER['HTTP_ORIGIN'] : SHIKSHA_HOME;
        header("Access-Control-Allow-Origin: ".$requestHeader);
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
        header("Content-Type: application/json; charset=utf-8");
        $this->saveExamScoreFromPopup();
    }

    public function saveExamScoreFromPopup(){
    	$this->_init();
        if($this->validateuser == 'false'){
            echo json_encode(array('status'=>'error', 'msg'=>'Please login to proceed.')); return;
        }
        $uAction           = $this->input->post('uAction', true);
        $uExamData         = $this->input->post('uData', true);
        $pageIdentifier    = $this->input->post('pageIdentifier', true);
        $latestPopUpStatus = $this->input->post('latestPopUpStatus', true);
        $reloadFlag = 0;
        if(in_array($pageIdentifier, $this->pagesToReload)){
            $reloadFlag = 1;
        }
        $status = $this->examUpdatePopupLib->validatePopupPOSTData($uAction, $uExamData);
        $userId = $this->validateuser[0]['userid'];
    	if(!$status){
            echo json_encode(array('status'=>'error', 'msg'=>'Something went wrong with data. Please try again.')); return;
        }else{
            $param = array(); $msg = '';
            if($uAction == 'booked'){
                $params['examType'] = 'booked';
        	}else if($uAction == 'yes'){
                $params['examType'] = 'yes';
                $msg = 'Thank you for submitting your exam scores.';
        	}else if($uAction == 'no'){
                $params['examType'] = 'no';
        	}else if($uAction == 'later'){
                $popUpLaterAction = $latestPopUpStatus;
            }
            $params['userId'] = $userId;
            $examMasterData =  $this->AbroadCommonLib->getAbroadExamsMasterList();
            $formattedMasterData = array();
            foreach ($examMasterData as $value) {
                $formattedMasterData[$value['examId']] = array('exam'=>$value['exam'], 'type'=>$value['type']);
            }
            foreach ($uExamData as $examId => $userScore) {
                $params['exams'][] = array('Name'=>$formattedMasterData[$examId]['exam'], 'Marks'=>$userScore, 'MarksType'=>$formattedMasterData[$examId]['type']);
            }
            $this->load->library('user/UserLib');
            $userLib = new UserLib;
            if($uAction != 'later'){
                $stsCode = $userLib->updateUserEducationDetails($params);
            }
            if($stsCode == 'success' || $uAction == 'later'){
                $popupTrackingData = array();
                $popupTrackingData['userId'] = $this->validateuser[0]['userid'];
                $popupTrackingData['pageName'] = $pageIdentifier;
                $trackingKeyForExamUpdate = $this->examUpdatePopupLib->getTrackingKeyForExamScoreUpdate($pageIdentifier);
                if($trackingKeyForExamUpdate > 0){
                    $popupTrackingData['MISTrackingId'] = $trackingKeyForExamUpdate;
                }
                $popupTrackingData['shownAt'] = date('Y-m-d H:i:s');
                $popupTrackingData['action'] = $uAction=='later'? $popUpLaterAction : $uAction;
                $popupTrackingData['applicationSource'] = isMobileRequest() ? 'mobile' : 'desktop';
                $popupTrackingData['isLatest'] = 1;
                $popupTrackingData['pageUrl'] = $this->input->server('HTTP_REFERER', true);
                $this->examUpdatePopupLib->addPopupTracking($popupTrackingData,$uAction);
                $this->examUpdatePopupLib->setCookieForPopupDaysCount($uAction, $this->validateuser[0]['userid']);
                echo json_encode(array('status'=>'success', 'msg'=>$msg, 'reloadFlag'=>$reloadFlag)); return;
            }else{
                echo json_encode(array('status'=>'error', 'msg'=>'Something went wrong. Please try again.')); return;
            }
        }
    }
}