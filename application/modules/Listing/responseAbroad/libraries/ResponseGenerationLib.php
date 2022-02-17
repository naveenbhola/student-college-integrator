<?php

    class ResponseGenerationLib {
        private $CI;
        private $abroadResponseGenerationModel;
        private $userLib;
        private $userModel;
        private $responseAbroadConfig;
        function __construct(){
            $this->CI =& get_instance();
            $this->abroadResponseGenerationModel = $this->CI->load->model('responseAbroad/abroadresponsegenerationmodel');
            $this->userLib      = $this->CI->load->library('user/UserLib');
            $this->userModel    = $this->CI->load->model('user/usermodel');
            $this->responseAbroadConfig = $this->CI->load->config('responseAbroad/responseAbroadConfig');
  	}
        // returns tempLmsId for a new response and existing response both
        public function insertResponse($data) {
            if($data['listingType']=='course'){ //this function is only working for scholarship response as of now, course has different flow
                return 0;
            }
            if(empty($data) || empty($data['userId']) || empty($data['displayName']) || empty($data['contact_cell'])
                || empty($data['listing_type']) || empty($data['listing_type_id']) || empty($data['contact_email']) 
                || empty($data['sourcePage']) || empty($data['tracking_page_key']) || empty($data['visitorSessionid'])
                || empty($data['submit_date']) || empty($data['listing_subscription_type'])
                ){ 
                return 0;
            }
            $data['prevDayDate'] = date('Y-m-d H:i:s',(strtotime('-1 day',strtotime ($data['submit_date']))));
            $data['action'] = $data['sourcePage'];
            $row = $this->abroadResponseGenerationModel->checkIfResponseExistLastDay($data);
            $extraDataForExclusion = array(
                'tracking_keyid' =>$data['tracking_page_key']
            );
            $tempLmsTableId = 0;
            if($row['id']) { //response exist last day
                $this->_gradeResponse($row,$data);
                $this->abroadResponseGenerationModel->increaseUserListingPerDayResponseCount($data);
                $this->userLib->checkUserForLDBExclusion($data['userId'],'response','scholarship',$data['listing_type_id'],'','', $data['action'],$extraDataForExclusion);
                return $row['id'];
            }else{
                //create response
                $response = $this->abroadResponseGenerationModel->createResponse($data);
                $tempLmsTableId = $response['tempLMSTableId'];

                //insert data in elastic server queue
                $eData['action']   = 'sa_new_response';
                $eDataEncoded      = json_encode($eData);

                $user_response_lib = $this->CI->load->library('response/userResponseIndexingLib');      
                $user_response_lib->insertInResponseIndexLog($data['userId'],$eDataEncoded,$data['queue_id']);

            }
            //update tuserdata table
            $this->userLib->updateUserData($data['userId']);

            $this->userLib->checkUserForLDBExclusion($data['userId'],'response','scholarship',$data['listing_type_id'],'','', $data['action'],$extraDataForExclusion);
            if($data['listing_subscription_type']=='paid'){
                $this->abroadResponseGenerationModel->setIsLdbUser($data['userId'],'NO');
            }
            return $tempLmsTableId;
        }
        
     
        
    private function _gradeResponse($response,$data){
        $responseId     = $response['id'];
        $currentAction  = $response['action'];
        $newAction      = $data['action'];
        $userId         = $data['userId'];
        $listingType    = $data['listing_type'];
        if($listingType == 'course') {
            $courseId = $data['listing_type_id'];
        }
        
        
        $responseGrades = $this->CI->config->item('abroadResponseGrades');
        $currentResponseGrade = $responseGrades[$listingType][$currentAction];
        $newResponseGrade = $responseGrades[$listingType][$newAction];
        
        $makeResponse = false; // for custom action types
        if($currentResponseGrade > 1) {
            if(stripos($newAction, 'client')) {
                $makeResponse = true;
            }
        }
        
        if(($currentResponseGrade && $newResponseGrade && $newResponseGrade < $currentResponseGrade) || $makeResponse) {
            $this->abroadResponseGenerationModel->upgradeResponse($data,$responseId);
            //add user to  tuserIndexingQueue
            $this->userModel->addUserToIndexingQueue($data['userId']);

             //insert data in elastic server queue
            $eData['action']   = 'sa_update_response';
            $eData['tempLMSId']= $responseId;
            $eDataEncoded      = json_encode($eData);

            $user_response_lib = $this->CI->load->library('response/userResponseIndexingLib');      
            $user_response_lib->insertInResponseIndexLog($data['userId'],$eDataEncoded,$data['queue_id']);
        }
    }
    
    private function _createUserResponsesLocationAffinity($response,$data){
        $insertData = array(
            'responseId'            => $response['tempLMSTableId'],
            'instituteLocationId'   => $data['institute_location_id'],
            'requestResponseId'     => $response['tempLMSRequestInsertId']
        );
        $this->abroadResponseGenerationModel->addResponseLocation($insertData);
        $responseCityId = $this->abroadResponseGenerationModel->getResponseCityId($data['institute_location_id']);
        $this->abroadResponseGenerationModel->updateResponseLocationAffinity($data['userId'], $responseCityId);
    }
}
    
?>
