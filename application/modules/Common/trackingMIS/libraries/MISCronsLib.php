<?php
class MISCronsLib{
	private $CI;
	public function __construct(){
        $this->CI = & get_instance();
        $this->trackingModel = $this->CI->load->model('trackingMIS/miscronsmodel');
    }

    public function getUserWithExamMapping($userIds){
        $abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
        $abroadExams =  array_map(function($a){return $a['exam'];},$abroadCommonLib->getAbroadExamsMasterList());
        $usersWithExams = $this->trackingModel->checkIfUserHasGivenExam($userIds,$abroadExams);
        $usersWithExams = array_map(function($a){return $a['userId'];}, $usersWithExams);

        $remainingUserIds = array_diff($userIds, $usersWithExams);
        $usersWithExamBooked = $this->trackingModel->userHasBookedExam($remainingUserIds);
        $usersWithExamBooked = array_map(function($a){return $a['userId'];},$usersWithExamBooked);

        $userExamMapping = array();
        foreach ($userIds as $userId) {
            if(in_array($userId, $usersWithExams)){
                $userExamMapping[$userId] = 'yes';
            }else if(in_array($userId, $usersWithExamBooked)){
                $userExamMapping[$userId] = 'booked';
            }else{
                $userExamMapping[$userId] = 'no';
            }
        }
        return $userExamMapping;
    }

    public function getUserDataAttributes($nationalUserIds){
        //_p($nationalUserIds);
        $userIds = array_keys($nationalUserIds);
        $timeInterval = 5;
        $chunkSize = 100;
        $response = array();
        $userDataMapping = array();
        $interestIdToUserMapping = array();
        for($i=0; $i<count($userIds); $i+=$chunkSize)
        {
            $userDataMappingTemp = array();
            $chunkUserIds = array();
            $chunkUserIds = array_slice($userIds, $i, $chunkSize);
            //_p($chunkUserIds);
            // get interestId, stream , sub-stream from tUserInterest
            $result = array();
            $result = $this->trackingModel->getUserInterestData($chunkUserIds);
            //_p($result);die;
            foreach ($result as $key => $userData) {
                if(!isset($userDataMappingTemp[$userData['userId']]) || 
                    isset($userDataMappingTemp[$userData['userId']][strtotime($userData['time'])])
                    ){
                    $userDataMappingTemp[$userData['userId']][strtotime($userData['time'])]['interestIds'][$userData['interestId']] = 1;
                    $userDataMappingTemp[$userData['userId']][strtotime($userData['time'])]['streamIds'][$userData['streamId']] = 1;
                    if($userData['subStreamId'] != ""){
                        $userDataMappingTemp[$userData['userId']][strtotime($userData['time'])]['subStreamIds'][$userData['subStreamId']] = 1;    
                    }
                }
            }
            unset($result);
            foreach ($userDataMappingTemp as $userId => $datewiseData) {
                foreach ($datewiseData as $date => $userData) {
                    if($date <= ($nationalUserIds[$userId]+$timeInterval)){
                        $userDataMapping[$userId] = $userData;
                        foreach ($userData['interestIds'] as $interestId => $value) {
                            $interestIdToUserMapping[$interestId] = $userId;
                        }
                        break;
                    }
                }
            }
            unset($userDataMappingTemp);
        }

        //_p($userDataMappingTemp);
        unset($userDataMappingTemp);
        //_p($userDataMapping);
        //_p($interestIdToUserMapping);_p(count($interestIdToUserMapping));die;
        //_p($userDataMapping);
        if(count($userDataMapping) >0){
            // get specializationId, baseCourseId from tUserCourseSpecialization
            $result = $this->trackingModel->getUserBaseCourse(array_keys($interestIdToUserMapping));
            //_p($result);
            $interestIdToDataMapping = array();
            foreach ($result as $key => $data) {
                $interestIdToDataMapping[$data['interestId']]["baseCourseId"][$data["baseCourseId"]] = 1;
                if($data["specializationId"] != ""){
                    $interestIdToDataMapping[$data['interestId']]["specializationId"][$data["specializationId"]] = 1;
                }
            }
            unset($result);
            //_p($interestIdToDataMapping);

            // get mode from tUserAttributes
            $interestIdToModeMapping = array();
            $result = $this->trackingModel->getUserModeDetails(array_keys($interestIdToUserMapping));
            foreach ($result as $key => $value) {
                $interestIdToDataMapping[$value['interestId']]['attributeValue'][$value['attributeValue']] = 1;
            }
            unset($result);
            //_p($interestIdToDataMapping);
            //echo "==";_p($result);

            foreach ($userDataMapping as $userId => $userData) {
                foreach ($userData['interestIds'] as $interestId => $value) {
                    $baseCourseIds = array_keys($interestIdToDataMapping[$interestId]['baseCourseId']);
                    $specializationIds = array_keys($interestIdToDataMapping[$interestId]['specializationId']);
                    $mode = array_keys($interestIdToDataMapping[$interestId]['attributeValue']);

                    if(isset($userDataMapping[$userId]['baseCourseIds'])){
                        if(count($baseCourseIds) >0){
                            $userDataMapping[$userId]['baseCourseIds'] = array_merge($userDataMapping[$userId]['baseCourseIds'],$baseCourseIds);
                            $userDataMapping[$userId]['baseCourseIds'] = array_unique($userDataMapping[$userId]['baseCourseIds']);
                        }
                    }else{
                        $userDataMapping[$userId]['baseCourseIds'] = $baseCourseIds;
                    }

                    if(isset($userDataMapping[$userId]['specializationIds'])){
                        if(count($specializationIds) >0){
                            $userDataMapping[$userId]['specializationIds'] = array_merge($userDataMapping[$userId]['specializationIds'],$specializationIds);
                            $userDataMapping[$userId]['specializationIds'] = array_unique($userDataMapping[$userId]['specializationIds']);
                        }
                    }else{
                        $userDataMapping[$userId]['specializationIds'] = $specializationIds;
                    }

                    if(isset($userDataMapping[$userId]['mode'])){
                        if(count($mode) >0){
                            $userDataMapping[$userId]['mode'] = array_merge($userDataMapping[$userId]['mode'],$mode);
                            $userDataMapping[$userId]['mode'] = array_unique($userDataMapping[$userId]['mode']);
                        }
                    }else{
                        $userDataMapping[$userId]['mode'] = $mode;
                    }
                }
            }

            foreach ($userDataMapping as $userId => $userDetails) {
                $response[$userId] = array(
                    'streamIds'         => array_keys($userDetails['streamIds']),
                    'subStreamIds'      => array_keys($userDetails['subStreamIds']),
                    'baseCourseIds'     => array_unique($userDetails['baseCourseIds']),
                    'specializationIds' => array_unique($userDetails['specializationIds']),
                    'mode'              => array_unique($userDetails['mode'])
                );
            }
            unset($userDataMapping);
        }
            
        return $response;
    }

    public function getTrackingIdsForPageGroup($pageGroup = "examPageMain"){
        $response = $this->trackingModel->getTrackingIdsForPageGroup($pageGroup);
        $trackingData = array();
        foreach ($response as $key => $value) {
            $trackingData[$value['id']] = $value;
        }
        //_p($trackingData);die;
        return $trackingData;
    }
}

?>