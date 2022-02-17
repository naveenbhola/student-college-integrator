<?php
class salesOperationLib {
    private $CI;
    private $salseOpsModel;
    public function __construct(){
        $this->CI = & get_instance();
        $this->CI->load->model('splice/salesoperationmodel');
        $this->salseOpsModel = new salesoperationmodel();
    } 
    
    /*
     *This function will confirm that a valid user from the allowed usergroups is logged in.
     *Output/Functionality:
     *  If a valid user is logged in, returns the user validation object
     *  If an invalid user is logged in, sends to the unauthorizedUser page and returns -1.
     *  If noone is logged in, returns bool(false).
    */    
    public function checkValidUser($isLoginPage = false){
        $validate = $this->CI->checkUserValidation();
        if($validate !== "false"){
            $groupId = $this->salseOpsModel->getGroupId($validate[0]['userid']);
            if($groupId > 0){
                $this->salseOpsModel->updateLastLogin($validate[0]['userid']);
                $validate[0]['groupId'] = $groupId;
                return $validate;
            }else{
                redirect('splice/dashboard/unauthorizedUser', 'refresh');
                die;
            }
        }
        if($isLoginPage){
            return false;
        }else{
            redirect('splice/dashboard/login', 'refresh');
            die;
        }
    }

    public function getGroupDetails($groupIds){
        if(count($groupIds) > 0){
            $result = $this->salseOpsModel->getGroupDetails($groupIds);
            foreach ($result as $key => $value) {
                $groupDetails[$value['id']] = $value['groupName'];
                
            }
        }
        return $groupDetails;
    }

    public function checkIfExistUser($email){
        $result = array();
        $isUserExist = $this->salseOpsModel->getUserDetails($email,'email');

        if($isUserExist){
            $result['registerForShiksha'] = true;
            $result['userName'] = $isUserExist[0]['firstname'].' '.$isUserExist[0]['lastname'];
            $result['userId'] = $isUserExist[0]['userId'];
            $groupId = $this->salseOpsModel->getGroupId($isUserExist[0]['userId'], false);
            if($groupId > 0){
                $result['registerForSalesOpsInterface'] = true;
            }else{
                $result['registerForSalesOpsInterface'] = false;
            }
        }else{
            $result['registerForShiksha'] = false;
        }
        return $result;
    }

    public function addNewMemberToInterface($inputData){
        return $this->salseOpsModel->addNewMemberToInterface($inputData);
    }

    public function updateLastLogin($userid){
        $this->salseOpsModel->updateLastLogin($userid);
    }

    public function getUserDetailsForGroup($viewMemberDetails,$userId){
        //_p($userId);die;        
        $tableDetails = array();
        $userIds = $this->getUserIdsForViewDashboardData($userId,$viewMemberDetails);

        // for not to see his details in data table
        $userIds = array_diff($userIds, array($userId));
        //_p($userIds);die;
        if(count($userIds) > 0){
            $result = $this->salseOpsModel->getSpliceUserDetails($userIds);
            //$totalRows = $result['totalRows'];
            //$result = $result['result'];
            if(count($result) > 0){
                foreach ($result as $key => $value) {
                    $userIds[] = $value['userId'];
                    $userIds[] = $value['addedBy'];
                    $groupIds[] = $value['groupId'];
                }
                $groupIds = array_unique($groupIds);
                $userIds = array_unique($userIds);
                $groupDetails = $this->getGroupDetails($groupIds);
                //_p($groupIds);die;
                //_p($userIds);_p($groupIds);die;

                //Get User Details
                $ucFirst = preg_replace('/([a-z])(_){0,}([A-Z])/', '$1 $3', $value->Pivot); // Make camel 
                $userDetails = $this->getUserDetails($userIds,'userId');
                foreach ($result as $key => $value) {
                    $tableDetails[] = array(
                        'User Name'     => $userDetails[$value['userId']]['userName'] .' ['.ucFirst(preg_replace('/([a-z])(_){0,}([A-Z])/', '$1 $3',$groupDetails[$value['groupId']])).']',
                        'Email Id'      => $userDetails[$value['userId']]['email'],
                        'Added By'      => $userDetails[$value['addedBy']]['userName'],
                        'Added On'      => $value['addedOn'],
                        'Last Login Date'   => $value['lastLoginOn'],
                        'isActive'      => $value['isActive']
                        );
                }
            }
        }        
        /*return array('tableDetails'=>$tableDetails,
                    'totalRows' =>$totalRows);*/
        return $tableDetails;                    
    }

    public function getUserName($userIds){
        $result = $this->salseOpsModel->getUserName($userIds);
        foreach ($result as $key => $value) {
            $userDetails[$value['userId']] = $value['firstname'].' '.$value['lastname'];
        }
        return $userDetails;
    }

    public function checkIfListingIsValid($site,$listingId){
        $result =$this->salseOpsModel->checkIfListingIsValid($site,$listingId);
        return (isset($result['listingName'])) ?$result:0;
    }

    public function checkIfLandingPageURLIsValid($landingPageURL,$site){
        $prefix = ($site == 'domestic') ? SHIKSHA_HOME :SHIKSHA_STUDYABROAD_HOME;
        $landingPageURL = str_replace($prefix, '', $landingPageURL);
        $result =$this->salseOpsModel->checkIfLandingPageURLIsValid($landingPageURL);
        return $result ?1:0;        
    }

    public function saveNewRequest($inputArray){
        //_p($inputArray);die;
        if($inputArray['newRequestType'] == 'mailer'){
            $noOfDaysForTAT = $inputArray['defaultTAT']['mailerRequest']['new'];
            $TATDate = $this->_calculateTATForTask($noOfDaysForTAT);
            unset($inputArray['defaultTAT']);
        }else if($inputArray['newRequestType'] == 'other'){
            //_p($inputArray);die;
            $noOfDaysForTATForCampaign = 0;
            //Listing Request
            if($inputArray['listingRequest']){
                $listingRequest = $inputArray['listingRequest'];
                if($listingRequest['changeType']){
                    $noOfDaysForTAT = $inputArray['defaultTAT']['listingRequest'][$listingRequest['site']][$listingRequest['requestType']][$listingRequest['changeType']];
                }else{
                    $noOfDaysForTAT = $inputArray['defaultTAT']['listingRequest'][$listingRequest['site']][$listingRequest['requestType']];
                }
                if($noOfDaysForTATForCampaign < $noOfDaysForTAT){
                    $noOfDaysForTATForCampaign = $noOfDaysForTAT;
                }
                $listingRequest['TATDate'] = $this->_calculateTATForTask($noOfDaysForTAT);
            }

            if($inputArray['bannerRequest']){
                $bannerRequest = $inputArray['bannerRequest'];
                $noOfDaysForTAT = $inputArray['defaultTAT']['bannerRequest'][$bannerRequest['requestType']];
                if($noOfDaysForTATForCampaign < $noOfDaysForTAT){
                    $noOfDaysForTATForCampaign = $noOfDaysForTAT;
                }
                unset($inputArray['defaultTAT']['bannerRequest']);
                $bannerRequest['TATDate'] = $this->_calculateTATForTask($noOfDaysForTAT);                    
            }

            if($inputArray['shoshkeleRequest']){
                $shoshkeleRequest = $inputArray['shoshkeleRequest'];
                $noOfDaysForTAT = $inputArray['defaultTAT']['shoshkeleRequest'][$shoshkeleRequest['requestType']];
                if($noOfDaysForTATForCampaign < $noOfDaysForTAT){
                    $noOfDaysForTATForCampaign = $noOfDaysForTAT;
                }
                unset($inputArray['defaultTAT']['shoshkeleRequest']);
                $shoshkeleRequest['TATDate'] = $this->_calculateTATForTask($noOfDaysForTAT);
            }

            if($inputArray['campaignActivationRequest']){
                $campaignActivationRequest = $inputArray['campaignActivationRequest'];
                $noOfDaysForTAT = $inputArray['defaultTAT']['campaignActivationRequest'];
                $noOfDaysForTAT += $noOfDaysForTATForCampaign;
                unset($inputArray['defaultTAT']['campaignActivationRequest']);
                $currentDate=date_create(date('Y-m-d'));
                $campaignActivationDate=date_create($inputArray['campaignLiveDate']);
                $diff=date_diff($campaignActivationDate,$currentDate);
                $dateDiff = $diff->days;
                if($noOfDaysForTAT < $dateDiff){
                    $TATDate = $inputArray['campaignLiveDate'];
                    while (1){
                        $dayNo = date('w',strtotime($TATDate));
                        if(!($dayNo == 0 || $dayNo == 6)){
                            break;
                        }
                        $TATDate = date("Y-m-d",strtotime($TATDate."+1 day"));
                    }
                    $TATDate .= ' 23:59:59';
                }else{
                    $TATDate = $this->_calculateTATForTask($noOfDaysForTAT);    
                }
                $campaignActivationRequest['TATDate'] = $TATDate;
            }else{
                $TATDate = $this->_calculateTATForTask($noOfDaysForTAT);
                unset($inputArray['defaultTAT']);    
            }
        }
        $data = array(
            'requestType'       => $inputArray['newRequestType'],
            'salesOrderNumber'  => $inputArray['salesOrderNo'],
            'campaignDate'      => $inputArray['campaignLiveDate'],
            'requestedBy'       => $inputArray['userId'],
            'TATDate'           => $TATDate.' 23:59:59',
            'lastUpdatedOn'     => date('Y-m-d H:i:s'),
            'clientName'        => $inputArray['clientName']
            );
        $requestId = $this->salseOpsModel->addNewRequest($data,'spliceRequests');
        if($requestId){
            if($inputArray['newRequestType'] == 'mailer'){
                $inputArray['TATDate'] = $TATDate;                
                $response = $this->_saveMailerTask($inputArray,$requestId);                
                if($response > 0){
                    $status['status'] = 1;
                    $status['mailerRequestTaskId'] = $response;
                    $status['TATDate'] = $inputArray['TATDate'];
                }
            }else if($inputArray['newRequestType'] == 'other'){
                //_p($inputArray);die;
                $noOfDaysForTATForCampaign = 0;
                //Listing Request
                if($inputArray['listingRequest']){
                    $listingRequest['attachment'] = $inputArray['attachmentURLs']['listingRequestAttachment'];
                    $listingRequest['userId'] = $inputArray['userId'];
                    $response = $this->_saveListingTask($listingRequest,$requestId);
                    if($response > 0){
                        $status['status'] = 1;
                        $status['taskDetails']['listingRequest']['requestTaskId'] = $response;
                        $status['taskDetails']['listingRequest']['TATDate'] =$listingRequest['TATDate'];
                    }
                }

                if($inputArray['bannerRequest']){
                    $bannerRequest['attachment'] = $inputArray['attachmentURLs']['bannerRequestAttachment'];
                    $bannerRequest['userId'] = $inputArray['userId'];
                    //_p($bannerRequest);die;
                    $response = $this->_saveBannerTask($bannerRequest,$requestId);
                    if($response > 0){
                        $status['status'] = 1;                    
                        $status['taskDetails']['bannerRequest']['requestTaskId'] = $response;
                        $status['taskDetails']['bannerRequest']['TATDate'] =$bannerRequest['TATDate'];
                    }
                }

                if($inputArray['shoshkeleRequest']){
                    $shoshkeleRequest['attachment'] = $inputArray['attachmentURLs']['shoshkeleRequestAttachment'];
                    $shoshkeleRequest['userId'] = $inputArray['userId'];
                    //_p($shoshkeleRequest);die;
                    $response = $this->_saveShoshkeleTask($shoshkeleRequest,$requestId);
                    if($response > 0){
                        $status['status'] = 1;                        
                        $status['taskDetails']['shoshkeleRequest']['requestTaskId'] = $response;
                        $status['taskDetails']['shoshkeleRequest']['TATDate'] =$shoshkeleRequest['TATDate'];
                    }
                }

                if($inputArray['campaignActivationRequest']){
                    $campaignActivationRequest['attachment'] = $inputArray['attachmentURLs']['campaignActivationRequestAttachment'];
                    $campaignActivationRequest['userId'] = $inputArray['userId'];
                    //_p($campaignActivationRequest);die;
                    $insertInCampaignPendingRequest = 0;
                    if(!$inputArray['shoshkeleRequest'] && !$inputArray['bannerRequest'] && !$inputArray['listingRequest']){
                        $insertInCampaignPendingRequest = 1;
                    }
                    $response = $this->_saveCampaignActivationTask($campaignActivationRequest,$requestId,$insertInCampaignPendingRequest);
                    if($response > 0){
                        $status['status'] = 1;
                        $status['taskDetails']['campaignActivationRequest']['requestTaskId'] = $response;
                        $status['taskDetails']['campaignActivationRequest']['TATDate'] =$campaignActivationRequest['TATDate'];
                    }                    
                }                
                //_p($status);die;
            }
            $status['requestId'] = $requestId;
            if($status && ($status['status'] == 1)){
                $response = $status;
            }else{                
                $response['status'] = 0;
                $response['error'] = 'DB connection invild';
            }
        }else{
            $response['status'] = 0;
            $response['error'] = 'DB connection invild';
        }
        return $response;
    }

    private function _saveCampaignActivationTask($inputArray,$requestId,$insertInCampaignPendingRequest,$currentStatus='In Progress'){
        //_p($inputArray); _p($requestId);die;        
        $statusFlag = true;
        $data = array(
            'requestId' => $requestId,
            'taskCategory' => 'Campaign Activation',
            'taskTitle' => $inputArray['requestTitle'],
            'taskType' => $inputArray['requestType'],
            'site'  => $inputArray['site']=='domestic'?'Domestic':'Study Abroad',            
            'taskRequestedBy' => $inputArray['requestedBy'] ?$inputArray['requestedBy']:'',
            'changeType'    => $inputArray['changeType'] ?$inputArray['changeType']:'',
            'attachmentURL' => $inputArray['attachment'] ? $inputArray['attachment']:'',            
            'TATDate' => $inputArray['TATDate'].' 23:59:59',
            'description' => $inputArray['description'],
            'assignee' => $inputArray['defaultAssigneeForRequest'],
            'lastUpdatedOn'     => date('Y-m-d H:i:s')
            );
        //_p($data);die;
        $taskId = $this->_addNewRequest($data,'spliceRequestTasks');
        //_p($taskId);die;
        if($taskId){
            $inputArray['newRequestType'] = 'campaignActivation';
            $taskAttributeId = $this->_saveTaskAttributes($inputArray,$taskId);            
            if(!$taskAttributeId){
                $statusFlag = false;        
            }    
            $taskActionDetailsId = $this->_saveTaskActionDetails($inputArray,$taskId);
            if(!$taskActionDetailsId){
                $statusFlag = false;    
            }
            if($insertInCampaignPendingRequest == 1){
                $inputArrayForCA['requestTaskId'] = $taskId;
                $inputArrayForCA['requestId'] = $requestId;
                $this->_insertOrUpdatePendingCampaignRequest($inputArrayForCA);
            }
        }else{
            $statusFlag = false;
        }
        if($statusFlag){
            return $taskId;
        }else{
            return 0;
        }        
    }

    private function _insertOrUpdatePendingCampaignRequest($inputArray){
        $inputData = array(
            'requestId' => $inputArray['requestId'],
            'requestTaskId' => $inputArray['requestTaskId']
            );
        $this->salseOpsModel->insertOrUpdatePendingCampaignRequest($inputData);
    }

    private function _saveShoshkeleTask($inputArray,$requestId,$currentStatus='In Progress'){
        //_p($inputArray); _p($requestId);die;        
        $statusFlag = true;
        $data = array(
            'requestId' => $requestId,
            'taskCategory' => 'Shoshkele',
            'taskTitle' => $inputArray['requestTitle'],
            'taskType' => $inputArray['requestType'],
            'site'  => $inputArray['site']=='domestic'?'Domestic':'Study Abroad',            
            'taskRequestedBy' => $inputArray['requestedBy'] ?$inputArray['requestedBy']:'',
            'changeType'    => $inputArray['changeType'] ?$inputArray['changeType']:'',
            'attachmentURL' => $inputArray['attachment'] ? $inputArray['attachment']:'',            
            'TATDate' => $inputArray['TATDate'].' 23:59:59',
            'description' => $inputArray['description'],
            'assignee' => $inputArray['defaultAssigneeForRequest'],
            'lastUpdatedOn'     => date('Y-m-d H:i:s')
            );
        //_p($data);die;
        $taskId = $this->_addNewRequest($data,'spliceRequestTasks');
        //_p($taskId);die;
        if($taskId){
            $inputArray['newRequestType'] = 'shoshkele';
            $taskAttributeId = $this->_saveTaskAttributes($inputArray,$taskId);            
            if(!$taskAttributeId){
                $statusFlag = false;        
            }  
            $taskActionDetailsId = $this->_saveTaskActionDetails($inputArray,$taskId);
            if(!$taskActionDetailsId){
                $statusFlag = false;    
            }          
                       
        }else{
            $statusFlag = false;
        }
        if($statusFlag){
            return $taskId;
        }else{
            return 0;
        }        
    }

    private function _saveBannerTask($inputArray,$requestId,$currentStatus='In Progress'){
        //_p($inputArray); _p($requestId);die;        
        $statusFlag = true;
        $data = array(
            'requestId' => $requestId,
            'taskCategory' => 'Banner',
            'taskTitle' => $inputArray['requestTitle'],
            'taskType' => $inputArray['requestType'],
            'site'  => $inputArray['site']=='domestic'?'Domestic':'Study Abroad',            
            'taskRequestedBy' => $inputArray['requestedBy'] ?$inputArray['requestedBy']:'',
            'changeType'    => $inputArray['changeType'] ?$inputArray['changeType']:'',
            'attachmentURL' => $inputArray['attachment'] ? $inputArray['attachment']:'',            
            'TATDate' => $inputArray['TATDate'].' 23:59:59',
            'description' => $inputArray['description'],
            'assignee' => $inputArray['defaultAssigneeForRequest'],
            'lastUpdatedOn'     => date('Y-m-d H:i:s')
            );
        //_p($data);die;
        $taskId = $this->_addNewRequest($data,'spliceRequestTasks');
        //_p($taskId);die;
        if($taskId){
            $inputArray['newRequestType'] = 'banners';
            $taskAttributeId = $this->_saveTaskAttributes($inputArray,$taskId);            
            if(!$taskAttributeId){
                $statusFlag = false;        
            }
            $taskActionDetailsId = $this->_saveTaskActionDetails($inputArray,$taskId);
            if(!$taskActionDetailsId){
                $statusFlag = false;    
            }
            //_p($taskAttributeId);die;
                       
        }else{
            $statusFlag = false;
        }
        if($statusFlag){
            return $taskId;
        }else{
            return 0;
        }        
    }

    private function _saveListingTask($inputArray,$requestId,$currentStatus='In Progress'){
        //_p($inputArray); _p($requestId);die;        
        $statusFlag = true;
        $data = array(
            'requestId' => $requestId,
            'taskCategory' => 'Listing',
            'taskTitle' => $inputArray['requestTitle'],
            'taskType' => $inputArray['requestType'],
            'site'  => $inputArray['site']=='domestic'?'Domestic':'Study Abroad',            
            'taskRequestedBy' => $inputArray['requestedBy'] ?$inputArray['requestedBy']:'',
            'changeType'    => $inputArray['changeType'] ?$inputArray['changeType']:'',
            'attachmentURL' => $inputArray['attachment'] ? $inputArray['attachment']:'',            
            'TATDate' => $inputArray['TATDate'].' 23:59:59',
            'description' => $inputArray['description'],
            'assignee' => $inputArray['defaultAssigneeForRequest'],
            'lastUpdatedOn'     => date('Y-m-d H:i:s')
            );
        //_p($data);die;
        $taskId = $this->_addNewRequest($data,'spliceRequestTasks');        
        if($taskId){
            if($inputArray['listingId']){
                $inputArray['newRequestType'] = 'listings';                
                $taskAttributeId = $this->_saveTaskAttributes($inputArray,$taskId);
                if(!$taskAttributeId){
                    $statusFlag = false;        
                }
                //_p($taskAttributeId);die;
            }
            // save default assignee and status of task          
            $taskActionDetailsId = $this->_saveTaskActionDetails($inputArray,$taskId);
            if(!$taskActionDetailsId){
                $statusFlag = false;    
            }
        }else{
            $statusFlag = false;
        }
        if($statusFlag){
            return $taskId;
        }else{
            return 0;
        }        
    }

    private function _addNewRequest($data,$tableName){
        $taskId = $this->salseOpsModel->addNewRequest($data,$tableName);
        return $taskId;
    }

    private function _saveMailerTask($inputArray,$requestId){
        //_p($inputArray); _p($requestId);die;        
        $data = array(
            'requestId' => $requestId,
            'taskCategory' => 'Mailer',
            'taskTitle' => $inputArray['requestTitle'],
            'taskType' => $inputArray['requestType'],
            'site'  => $inputArray['site']=='domestic'?'Domestic':'Study Abroad',            
            'taskRequestedBy' => /*$inputArray['requestedBy'] ?$inputArray['requestedBy']:*/'',
            'changeType'    => /*$inputArray['changeType'] ?$inputArray['changeTypeForMailer'][$inputArray['changeType']]:*/'',
            'attachmentURL' => $inputArray['attachmentURL'] ? $inputArray['attachmentURL']:'',            
            'TATDate' => $inputArray['TATDate'].' 23:59:59',
            'description' => $inputArray['description'],
            'currentStatus' =>  $inputArray['currentStatus'],
            'assignee' => $inputArray['defaultAssigneeForRequest'],
            'lastUpdatedOn'     => date('Y-m-d H:i:s')
        );

        $taskId = $this->_addNewRequest($data,'spliceRequestTasks');    
        if($taskId){
            $inputArray['courseName'] = $inputArray['courseListForMailerRequest'][$inputArray['course']];
            $taskAttributeId = $this->_saveTaskAttributes($inputArray,$taskId);

            // save default assignee and status of task
            $taskActionDetailsId = $this->_saveTaskActionDetails($inputArray,$taskId);

        }
        if($taskAttributeId && $taskActionDetailsId){
            return $taskId;
        }else{
            return 0;
        }        
    }

    private function _saveTaskActionDetails($inputArray,$taskId){
        //_p($inputArray);_p($taskId);die;
        $currentLevel = $this->_getTaskActionDetailCurrentLevel($taskId);
        if($currentLevel){
            $currentLevel = $currentLevel[0]['level'];
            $currentLevel = $currentLevel++;
        }else{
            $currentLevel = 1;
        }
        $data = array(
            'requestTaskId'     => $taskId,
            'level'             => $currentLevel,
            'assignee'          => $inputArray['defaultAssigneeForRequest'],
            'status'            => $inputArray['status']?$inputArray['status'] : 'inProgress',            
            'assignedBy'        => $inputArray['userId']
        );

        $taskActionDetailsId = $this->salseOpsModel->addNewRequest($data,'spliceTasksActionDetails');
        return $taskActionDetailsId;
    }

    private function _getTaskActionDetailCurrentLevel($taskId){
        return $this->salseOpsModel->getTaskActionDetailCurrentLevel($taskId);
    }

    private function _saveTaskAttributes($inputArray,$taskId){
        if($inputArray['newRequestType'] == 'mailer'){
            $data[] = array(
                'attributeName' => 'course',
                'attributeValue' => $inputArray['courseName'],
                'requestTaskId' => $taskId
                );
            if($inputArray['mailerType']){
                $data[] = array(
                'attributeName' => 'mailerType',
                'attributeValue' => $inputArray['mailerType'],
                'requestTaskId' => $taskId
                );    
            }
        }else if($inputArray['newRequestType'] == 'listings'){
            $data[] = array(
                'attributeName' => $inputArray['listingURLType'],
                'attributeValue' => $inputArray['listingId'],
                'requestTaskId' => $taskId
                );
        }else if($inputArray['newRequestType'] == 'banners'){
            $data[] = array(
                'attributeName' => 'diffBannerSize',
                'attributeValue' => json_encode($inputArray['diffBannerSize']),
                'requestTaskId' => $taskId
            );
        }else if($inputArray['newRequestType'] == 'shoshkele'){
            $data[] = array(
                'attributeName' => 'categorySponsorType',
                'attributeValue' => $inputArray['categorySponsorType'],
                'requestTaskId' => $taskId
                );
            if($inputArray['landingPageURL']){
                $prefix = ($inputArray['site'] == 'domestic') ? SHIKSHA_HOME : SHIKSHA_STUDYABROAD_HOME;
                $inputArray['landingPageURL'] = str_replace($prefix, '', $inputArray['landingPageURL']);
                $data[] = array(
                'attributeName' => 'landingPageURL',
                'attributeValue' => $inputArray['landingPageURL'],
                'requestTaskId' => $taskId
                );
            }
        }else if($inputArray['newRequestType'] == 'campaignActivation'){
            $data[] = array(
                'attributeName' => 'diffCampaignTypes',
                'attributeValue' => json_encode($inputArray['diffCampaignType']),
                'requestTaskId' => $taskId
                );
            if($inputArray['landingPageURL']){
                $prefix = ($inputArray['site'] == 'domestic') ? SHIKSHA_HOME : SHIKSHA_STUDYABROAD_HOME;
                $inputArray['landingPageURL'] = str_replace($prefix, '', $inputArray['landingPageURL']);
                $data[] = array(
                'attributeName' => 'landingPageURL',
                'attributeValue' => $inputArray['landingPageURL'],
                'requestTaskId' => $taskId
                );
            }
        }
        //_p($data);die;
        $taskAttributeId = $this->_addNewRequest($data,'spliceRequestTaskAttributes');
        return $taskAttributeId;
    }

    private function _calculateTATForTask($noOfDaysForTAT,$TATDate=''){
        //_p($noOfDaysForTAT);_p($TATDate);die;
        // stating from tomorrow        
        $count = 0;
        if($TATDate){
            $TATDate = date("Y-m-d",strtotime($TATDate));
        }else{
            $TATDate = date("Y-m-d");
        }

        if(strtotime($TATDate) < strtotime(date("Y-m-d"))){
            $TATDate = date("Y-m-d");
        }

        while ($count < $noOfDaysForTAT) {            
            $TATDate = date("Y-m-d",strtotime($TATDate."+1 day"));
            $dayNo = date('w',strtotime($TATDate));
            if(!($dayNo == 0 || $dayNo == 6)){
                $count++;
            }            
        }
        //_p($TATDate); die;
        return $TATDate;
    }

    public function getUserIdsForGivenGroups($groupIds){
        $result = $this->salseOpsModel->getUserIdsForGivenGroups($groupIds);
        //_p($userIds);die;
        foreach ($result as $key => $value) {
            $userIds[] = $value['userId'];
        }
        return $userIds;        
    }

    public function getRequestDataForGivenUserIds($inputArray){
        //_p($inputArray);die;
        if($inputArray['requestType'] == 'task'){
            return $this->getTaskDetails($inputArray);
        }else if($inputArray['requestType'] == 'request'){            
            return $this->getRequestDetails($inputArray);
        }
    }

    public function getTaskDetails($inputArray){
        //_p($inputArray);die;
        //_p($inputArray);die;
        $checkForTaskIds = false;
        if($inputArray['filter']){
            $checkForTaskIds = true;
        }else if(!in_array($inputArray['groupId'],$inputArray['teamGroupIds']['admin'])){
            if(in_array($inputArray['groupId'],$inputArray['teamGroupIds']['salesTeam'])){
                if($inputArray['userType'] !='manager'){
                    $checkForTaskIds = true;
                }
            }else{
                if($inputArray['userType'] != 'manager'){
                    $checkForTaskIds = true;
                }
            }
        }

        $requestArray = $this->salseOpsModel->getTaskDataForGivenUserIds($inputArray,$checkForTaskIds);
        $totalRows = $requestArray['totalRows'];
        $requestArray = $requestArray['result'];
        //_p($requestArray);_p($totalRows);die;
        if($requestArray){
            foreach ($requestArray as $key => $value) {
                $userIds[] = $value['requestedBy'];            
            }
            $userIds = array_unique($userIds);
            //_p($userIds);die;
            $userData = $this->salseOpsModel->getUserName($userIds);
            //_p($userData);die;
            foreach ($userData as $key => $value) {
                $userDetails[$value['userId']] = $value['firstname'].' '.$value['lastname'];
            }

            $i = 1;
            //$requestDetails[] = array('S.No.','Task Id','Title','Assigned On','Assigned By','TAT','Status');
            foreach ($requestArray as $key => $value){
                $requestDetails[] = array(
                    'SNO' => $i++,
                    'Task Id' => '<a style="color:blue;" href="/splice/dashboard/viewTaskDetails/'.base64_encode($value['id']).'"" target="_blank">'.$value['id'].'</a>',
                    'Title' => $value['taskTitle'],
                    'Assigned On' => $this->getFormattedDate($value['requestedOn'],'dateTime'),
                    'Assigned By' => $userDetails[$value['requestedBy']],
                    'TAT' => $this->getFormattedDate($value['TATDate'],'date'),
                    'Status' => $this->statusToShowStatusName($value['currentStatus'])
                    );            
                unset($requestArray[$key]);
            }
        }
        //_p($requestDetails);die;
        return array('requestDetails' => $requestDetails,
                    'totalRows' => $totalRows);
    }

    public function getRequestDetails($inputArray){
        //_p($inputArray);die;
        $checkForRequestIds = false;
        if($inputArray['filter'] == 'allCreatedRequest'){
            if(in_array($inputArray['groupId'],$inputArray['teamGroupIds']['salesTeam'])){
                $checkForRequestIds = true;
            }
        }else{
            if(in_array($inputArray['groupId'],$inputArray['teamGroupIds']['salesTeam']) && $inputArray['userType'] == 'manager'){
                $checkForRequestIds = false;
            }else if(in_array($inputArray['groupId'],$inputArray['teamGroupIds']['admin'])){
                $checkForRequestIds = false;
            }else{
                if(count($inputArray['requestIds']) <= 0){
                    return '';
                }
                $checkForRequestIds = true;
            }
        }

        if($inputArray['pendingForCampaignActivation'] == true){
            $result = $this->salseOpsModel->getPendingCampaignRequest($inputArray['requestIds'],$checkForRequestIds,'request');
            $checkForRequestIds = true;
            $finalRequestIds = array();
            foreach ($result as $key => $value) {
                $finalRequestIds[] = $value['requestId'];
            }
            $inputArray['requestIds'] = array_unique($finalRequestIds);
        }

        $requestArray = $this->salseOpsModel->getRequestDataForGivenUserIds($inputArray,$checkForRequestIds);
        $totalRows = $requestArray['totalRows'];
        $requestArray = $requestArray['result'];
        if($requestArray){
            foreach ($requestArray as $key => $value) {
                $userIds[] = $value['requestedBy'];            
            }
            $userIds = array_unique($userIds);
            $userData = $this->salseOpsModel->getUserName($userIds);
            foreach ($userData as $key => $value) {
                $userDetails[$value['userId']] = $value['firstname'].' '.$value['lastname'];
            }
            $i = 1;
            foreach ($requestArray as $key => $value){
                $requestDetails[] = array(
                    'SNO' => $i++,
                    'Request Id' => '<a href="javascript:void(0);" onclick="shikshaSales.getRequestTaskDetals($(this).html());" style="color:blue;">'.$value['id'].'</a>',
                    'Sales Order No' => $value['salesOrderNumber'],
                    'Campaign Date' => $this->getFormattedDate($value['campaignDate'],'date'),
                    'Creation On' => $this->getFormattedDate($value['requestedOn'],'dateTime'),
                    'Created By' => $userDetails[$value['requestedBy']],
                    'Status' => $this->statusToShowStatusName($value['status']),
                    );
                unset($requestArray[$key]);
            }
        }
        //_p($requestDetails);die;
        return array('requestDetails' => $requestDetails,
                    'totalRows' => $totalRows);
    }

    public function getRequestIdsForViewDashboardData($inputUserIds){

        $result = $this->salseOpsModel->getAllAssigneeForSalesTeamForRequest();
        $requestIds = array();
        foreach ($result as $key => $value) {            
            $userIds = explode(',',$value['reassignedUserIds']);
            $userIds[] = $value['requestedBy'];
            foreach ($inputUserIds as $userId) {
                //_p($userId);die;
                if(in_array($userId, $userIds)){
                    $requestIds[] =$value['id'];
                    break;
                }
            }
        }
        //_p(count($requestIds));die;
        return $requestIds;
    }

    public function getRequestTasksDetails($requestId,$requestDisplayName){
        $requestTaskDetails['requestData'] =  $this->salseOpsModel->getRequestDetails($requestId);
        $userIds[] = $requestTaskDetails['requestData'][0]['requestedBy'];
        $requestTaskDetails['taskDetails'] = $this->salseOpsModel->getTaskDetails($requestId);
        foreach ($requestTaskDetails['taskDetails'] as $key => $value) {
            $requestTaskDetails['taskDetails'][$key]['taskCategory'] = $requestDisplayName[$value['taskCategory']];
            $userIds[] = $value['assignee'];
        }
        $userData = $this->salseOpsModel->getUserName($userIds);
        foreach ($userData as $key => $value) {
            $userDetails[$value['userId']] = $value['firstname'].' '.$value['lastname'];
        }
        $requestTaskDetails['requestData'][0]['requestedBy'] = $userDetails[$requestTaskDetails['requestData'][0]['requestedBy']];        
        $requestTaskDetails['requestData'] = $requestTaskDetails['requestData'][0];
        foreach ($requestTaskDetails['requestData'] as $key => $value) {
            $requestTaskDetails['requestDetails'][] = $value;
        }
        $requestTaskDetails['requestDetails'][3] = $this->getFormattedDate($requestTaskDetails['requestDetails'][3],'date');
        $requestTaskDetails['requestDetails'][5] = $this->getFormattedDate($requestTaskDetails['requestDetails'][5],'dateTime');
        foreach ($requestTaskDetails['taskDetails'] as $key => $value) {
            $requestTaskDetails['taskDetails'][$key]['TATDate'] = $this->getFormattedDate($value['TATDate'],'date');
            $requestTaskDetails['taskDetails'][$key]['currentStatus'] = $this->statusToShowStatusName($value['currentStatus']);
            $requestTaskDetails['taskDetails'][$key]['assignee'] = $userDetails[$value['assignee']]?$userDetails[$value['assignee']]:'User Name';
            $requestTaskDetails['taskDetails'][$key]['id'] = '<a style="color:blue;" href="/splice/dashboard/viewTaskDetails/'.base64_encode($value['id']).'"" target="_blank">'.$value['id'].'</a>';
        }
        $i=0;
        foreach ($requestTaskDetails['taskDetails'] as $task => $taskDetails) {
            foreach ($taskDetails as $key => $value) {
                $tbody[$i][] = $value;
            }
            $i++;
        }
        //$requestTaskDetails['taskHeader'] = array('Task Id','Task Category','Task Title','Assigned To','TAT End Date','Status');
        $requestTaskDetails['taskHeader'] = array(
            array(
                'heading'=>'Task Id',
                'width' => '5% !important'),
            array(
                'heading'=>'Task Category',
                'width' => '15% !important'),
            array(
                'heading'=>'Task Title',
                'width' => '40% !important'),
            array(
                'heading'=>'Assigned To',
                'width' => '15% !important'),
            array(
                'heading'=>'TAT End Date',
                'width' => '10% !important'),
            array(
                'heading'=>'Status',
                'width' => '15% !important'),
            );
        $requestTaskDetails['tbody'] = $tbody;
        //_p($requestTaskDetails);die;
        return $requestTaskDetails;
    }

    public function getTaskIdsForRequestIds($requestIds){
        $taskIds = array();
        if(count($requestIds) > 0){
            $result = $this->salseOpsModel->getTaskIdsForRequestIds($requestIds);
            foreach ($result as $key => $value) {
                $taskIds[] = $value['id'];
            }
        }
        return $taskIds;
    }

    public function getPendingRequestTask($requestIds){
        $pendingTasks = $this->salseOpsModel->getPendingRequestTask($requestIds);
        return $pendingTasks;
    }

    public function getPendingRequestTaskForClientApproval($requestIds){
        $pendingTasks = $this->salseOpsModel->getPendingRequestTaskForClientApproval($requestIds);
        return $pendingTasks;
    }

    public function getTATExpiredDetails($requestIds){
        $RequestTATExpired = $this->salseOpsModel->getTATExpiredDetails($requestIds,'request');
        return $RequestTATExpired;
    }

    public function getRequestCreatedByUsers($userIds=''){
        $requestIds = $this->salseOpsModel->getRequestCreatedByUsers($userIds);
        return $requestIds;
    }

    public function _getTopTilesForSales($userIds,$accessDetails,$userId){
        //get request status wise data
        //_p($userIds);_p($accessDetails);_p($userId);die;
        $requestDetailsArray = array(
            'requestCreated' => array(),
            'pendingRequest' => array(),
            'pendingForClientApproval' => array(),
            'TATExpired' => array()            
            );
        $requestIds = array();
        $teams = array('pseudoAdmin','admin');
        if(!in_array($accessDetails['userType'], $teams)){
            $requestIds = $this->getRequestCreatedByUsers($userIds);
            if($requestIds){
                foreach ($requestIds as $key => $value) {
                    $requestDetailsArray['requestCreated'][ucFirst($value['requestType'])]++;
                }
                //$totalRequestCreated = count($requestIds);
            }
            $requestIds = $this->getRequestIdsForViewDashboardData($userIds);
        }else{
            $requestIds = $this->getRequestCreatedByUsers();
            if($requestIds){
                foreach ($requestIds as $key => $value) {
                    $requestDetailsArray['requestCreated'][ucFirst($value['requestType'])]++;
                }
                //$totalRequestCreated = count($requestIds);
            }
            $requestIds = array();
        }
        //_p($requestDetailsArray);die;
        if(is_array($requestIds) && count($requestIds)){
            $pendingTasks = $this->getPendingRequestTask($requestIds);
            if($pendingTasks){
                //$pendingTasks = count($pendingTasks);
                foreach ($pendingTasks as $key => $value) {
                    $requestDetailsArray['pendingRequest'][$value['taskCategory']]++;
                }
            }
            
            $pendingTasksForClientApproval = $this->getPendingRequestTaskForClientApproval($requestIds);
            if($pendingTasksForClientApproval){
                foreach ($pendingTasksForClientApproval as $key => $value) {
                    $requestDetailsArray['pendingForClientApproval'][$value['taskCategory']]++;
                }
            }
            
            $TATExpiredData = $this->salseOpsModel->getTATExpiredDetails($requestIds,'request');
            if($TATExpiredData){
                foreach ($TATExpiredData as $key => $value) {
                    $TATExpiredDetails[] = $value['requestTaskId'];
                }
                $TATExpiredDetails = array_unique($TATExpiredDetails);
                $result = $this->salseOpsModel->getCurrentStatusForTasks($TATExpiredDetails);
                foreach ($result as $key => $value) {
                    $requestDetailsArray['TATExpired'][$value['taskCategory']]++;
                }
            }
        }
        foreach ($requestDetailsArray as $key => $value) {
            if($value){
                $result = $this->prepareTableForTopTiles($value);
                /*$data = '<label>'.$result['totalCount'].'</label>&nbsp&nbsp&nbsp&nbsp<label style="position:absolute;font-size:10px;margin-top: 10px;">'.$result['tableData'].'</label>';*/
                $topTiles[] = array($key,$result['totalCount'],$result['tableData']);
            }else{
                $topTiles[] = array($key,0,'');    
            }
        }
        //_p($topTiles);die;
        return $topTiles;
    }

    public function getTaskIdsForAssignedUserForInputFilter($taskIds,$filter,$userIds){
        //_p($taskIds);die;
        if ($filter == 'assignedTaskTATExpired'){
            $result = $this->salseOpsModel->getTATExpiredDetails($userIds,'task');
            $taskIds = array();
            foreach ($result as $key => $value) {
                $taskIds[] = $value['requestTaskId'];
            }
        }else{
            $result = $this->salseOpsModel->getTaskIdsForAssignedUserForInputFilter($taskIds,$filter);
            $taskIds = array();
            foreach ($result as $key => $value) {
                $taskIds[] = $value['id'];
            }
        }
        //_p($result);die;
        //_p($taskIds);die;
        return $taskIds;
    }

    public function getTaskIdsForCurrentAssignee($userIds){
        $result = $this->salseOpsModel->getTaskIdsForCurrentAssignee($userIds);
        $taskIds = array();
        foreach ($result as $key => $value) {
            $taskIds[] = $value['id'];
        }
        return $taskIds;
    }

    public function getTopTilesForDashboard($groupId,$userId,$accessDetails,$teamGroupIds){
        //_p($accessDetails);die;
        $userIds = $this->getUserIdsForViewDashboardData($userId,$accessDetails);
        //_p($groupId);die;
        if(count($userIds) > 0){
            if($groupId > 0){
                if(in_array($groupId,$teamGroupIds['salesTeam'])){
                    $topTiles = $this->_getTopTilesForSales($userIds,$accessDetails,$userId);
                }else if(in_array($groupId,$teamGroupIds['admin'])){
                    $topTilesForOtherTeam = array();
                    $topTilesForSalesTeam = array();
                    $topTilesForSalesTeam = $this->_getTopTilesForSales($userIds,$accessDetails,$userId);
                    $topTilesForOtherTeam = $this->_getTopTilesForOther($userIds,$accessDetails,$userId,$teamGroupIds,$groupId);
                    $topTiles = array_merge($topTilesForSalesTeam,$topTilesForOtherTeam);
                    //_p($topTiles);die;
                }else{
                    $topTiles = $this->_getTopTilesForOther($userIds,$accessDetails,$userId,$teamGroupIds,$groupId);
                }
            }
        }else{

        }
        return $topTiles;
    }

    function getUserIdsForGroupIds($groupIds){
        $result = $this->salseOpsModel->getAddedUserIds($groupIds,'groupId');
        $userIds = array();
        foreach ($result as $key => $value) {
            $userIds[] = $value['userId'];
        }
        return $userIds;
    }

    public function getUserIdsForViewDashboardData($userId,$accessDetails){
       //_p($userId);_p($accessDetails);die;
        if($accessDetails['userType'] == 'user'){
            $userIds = array($userId);
        }else{
            $result = array();
            if($accessDetails['userType'] == 'lead'){
                $result = $this->salseOpsModel->getAddedUserIds($userId,'userId');
                $userIds[] = $userId;                
            }else if($accessDetails['userType'] == 'manager'){                
                $result = $this->salseOpsModel->getAddedUserIds($accessDetails['viewType'],'groupId');
                $userIds[] = $userId;
            }else if($accessDetails['userType'] == 'pseudoAdmin'){                
                $result = $this->salseOpsModel->getAddedUserIds($accessDetails['viewType'],'groupId');                
                $userIds[] = $userId;
            }else if($accessDetails['userType'] == 'admin'){                
                $result = $this->salseOpsModel->getAddedUserIds($userId);                
                $userIds[] = $userId;
            }else{
                $userIds = array();
            }
            foreach ($result as $key => $value) {
                $userIds[] = $value['userId'];
            }
        }
        //_p($userIds);die;
        return $userIds;
    }

    public function prepareTaskIdForCurrentAssignee($userIds,$groupId,$teamGroupIds){
        //_p($userIds);_p($groupId);_p($teamGroupIds);die;
        $userIds = array_unique($userIds);
        $result = $this->salseOpsModel->getTaskAssignedToUsers($userIds);
        foreach ($result as $key => $value) {
            $taskIds[] = $value['requestTaskId'];
        }
        //$taskIds = array_unique($taskIds);
        $result = $this->salseOpsModel->getTaskAssignedToUsersForPushBack($userIds);
        foreach ($result as $key => $value) {
            $taskActionId[] = $value['taskActionId'];
        }
        $taskActionId = array_unique($taskActionId);
        if(count($taskActionId) > 0){
            $result = $this->salseOpsModel->getRequestTaskIdForTaskActionIds($taskActionId);
            foreach ($result as $key => $value) {
                $taskIds[] = $value['requestTaskId'];
            }
        }
        $taskIds = array_values(array_unique($taskIds));
        if($taskIds){
            if(in_array($groupId, $teamGroupIds['salesOps'])){
                $taskIds = $this->filterCampaignTaskId($taskIds);
            }
        }

        return $taskIds;
    }

    public function filterCampaignTaskId($taskIds){
        $result = $this->salseOpsModel->filterCampaignTaskId($taskIds);
        $taskIds = array();
        foreach ($result as $key => $value) {
            $taskIds[] = $value['id'];
        }
        return $taskIds;
    }

    public function _getTopTilesForOther($userIds,$accessDetails,$userId,$teamGroupIds,$groupId){
        //_p($groupId);_p($userIds);_p($accessDetails);_p($teamGroupIds);die;
        $teams = array('pseudoAdmin','admin');
        $taskDetailsArray = array(
            'taskAssigned' => array(),
            'pendingTask' => array(),
            'TATToday' => array(),
            'TATTomorrow' => array(),
            'TATExpiredForOtherTeam' => array()
            );
        if(in_array($accessDetails['userType'], $teams)){
            $result = $this->salseOpsModel->getAddedUserIds($teamGroupIds['salesTeam'],'groupId');
            //_p($result);die;
            foreach ($result as $key => $value) {
                $salesTeamUserIds[] = $value['userId'];
            }            
            $userIds = array_diff($userIds, $salesTeamUserIds);

            //TAT Expired
            $TATExpiredData = $this->salseOpsModel->getTATExpiredDetails(array(),'request');
        }else{
            //TAT Expired
            $TATExpiredData = $this->salseOpsModel->getTATExpiredDetails($userIds,'task');
            //_p($TATExpiredData);die;
        }

        // Task Assigned
        $totalTaskAssignedArray = $this->prepareTaskIdForCurrentAssignee($userIds,$groupId,$teamGroupIds);
        if($totalTaskAssignedArray){
            $result = $this->salseOpsModel->getCurrentStatusForTasks($totalTaskAssignedArray);
            foreach ($result as $key => $value) {
                $taskDetailsArray['taskAssigned'][$value['taskCategory']]++;
            }
        }

        // Task Expired task by task category
        if($TATExpiredData){
            foreach ($TATExpiredData as $key => $value) {
                $TATExpiredDetails[] = $value['requestTaskId'];
            }
            $TATExpiredDetails = array_unique($TATExpiredDetails);
            //_p($TATExpiredDetails);die;
            $result = $this->salseOpsModel->getCurrentStatusForTasks($TATExpiredDetails);
            foreach ($result as $key => $value) {
                $taskDetailsArray['TATExpiredForOtherTeam'][$value['taskCategory']]++;
            }
        }
            

        // pending Tasks
        $statusArray = array('clientApprovedAndClosed','cancel');
        $getTaskDetails = $this->salseOpsModel->getTaskDetailsForPendingTask($userIds,$statusArray);
        //_p($getTaskDetails);die;
        $todayDate = date('Y-m-d');
        $tomorrowDate = date('Y-m-d',strtotime(date('Y-m-d') . "+1 days"));
        $TATEndToday = 0;
        $TATEndTomorrow = 0;
        //$TATExpired = 0;
        $pendingTaskCount = 0;
        //_p($todayDate);_p($tomorrowDate);
        $taskIds = array();

        $statusForPendingTask = array('pushedBack','markedDone','inProgress','clientApprovedAndCreateHTML');
        foreach ($getTaskDetails as $key => $value) {
            //check for pending task
            if(in_array($value['currentStatus'],$statusForPendingTask)){
                    $taskDetailsArray['pendingTask'][$value['taskCategory']]++;
                // check for TAT 
                if(date('Y-m-d', strtotime($value['TATDate'])) == $todayDate){ //TAT Today
                        $taskDetailsArray['TATToday'][$value['taskCategory']]++;
                }else if(date('Y-m-d', strtotime($value['TATDate'])) == $tomorrowDate){ //TAT Tomorrow
                        $taskDetailsArray['TATTomorrow'][$value['taskCategory']]++;
                }
            }
        }
        foreach ($taskDetailsArray as $key => $value) {
                if($value){
                    $result = $this->prepareTableForTopTiles($value);
                    $topTiles[] = array($key,$result['totalCount'],$result['tableData']);
                }else{
                    $topTiles[] = array($key,0,'');    
                }
            }
        //_p($topTiles);die;
        return $topTiles;
    }

    public function prepareTableForTopTiles($data){
        $tableData = '<table style="font-size:12px;font-weight: normal !important;width: 96% !important;">';
        $totaCount = 0;
        foreach ($data as $key => $value) {
            $totaCount += $value;
            $tableData .=   '<tr>'.
                            '<td style="width:100% !important;text-align:right">'.$key.'&nbsp('.$value.')'.'<td>'.                            
                            '</tr>';
        }
        $tableData .= '</table>';
        //_p($tableData);die;
        return array('totalCount' => $totaCount,
                    'tableData' => $tableData);
    }

    private function _getTaskIdsForInputStatus($statusArray,$userIds){
        $result = $this->salseOpsModel->getTaskIdsForInputStatus($statusArray,$userIds);
    }

    private function _getUserDetailsForDesignTeam($accessDetails,$userId){
        //_p($accessDetails);_p($userId);die;
        if($accessDetails['userType'] == 'user'){
            $userIdsDetails['user'] = array($userId);
        }else if($accessDetails['userType'] == 'lead'){
            $result = $this->salseOpsModel->getAddedUserIds($userId,'userId');
            foreach ($result as $key => $value) {
                $userIds[] = $value['userId'];
            }
            $userIdsDetails['user'] = $userIds;
            $userIdsDetails['lead'] = array($userId);
        }else if($accessDetails['userType'] == 'manager'){
            $result = $this->salseOpsModel->getAddedUserIds($userId,'userId');
            foreach ($result as $key => $value) {
                if($value['groupId'] == '7'){
                    $userIdsDetails['user'][] = $value['userIds'];
                }else if($value['groupId'] == '8'){
                    $userIdsDetails['lead'][] = $value['userIds'];
                }else if($value['groupId'] == '9'){
                    $userIdsDetails['manager'][] = $value['userIds'];
                }                
            }            
        }

        return $userIdsDetails;
    }
    
    public function getManagerDetails($managerGroupId){
        $result = $this->salseOpsModel->getUserGroupDetails($managerGroupId);        
        foreach ($result as $key => $value) {
            $userIds[] = $value['userId'];
        }
        if(count($userIds)>0){
            $result = $this->salseOpsModel->getUserName($userIds);
            foreach ($result as $key => $value) {
                $userDetails[] = array($value['userId'],$value['firstname'].' '.$value['lastname']) ;
            }
        }
        return $userDetails;
    }

    public function getLeadDetails($type,$typeValue,$selectedGroupId){
        //_p($selectedGroupId);die;
        $leadId = $this->getLeadIdForUser($selectedGroupId);
        //_p($leadId);die;
        $result = $this->salseOpsModel->getLeadDetails($type,$typeValue,$leadId);
        foreach ($result as $key => $value) {
            $userIds[] = $value['userId'];
        }
        if(count($userIds)>0){
            $result = $this->salseOpsModel->getUserName($userIds);
            foreach ($result as $key => $value) {
                $userDetails[] = array($value['userId'],$value['firstname'].' '.$value['lastname']) ;
            }
        }
        return $userDetails;        
    }

    public function getLeadIdForUser($selectedGroupId){
        switch ($selectedGroupId) {
            case 1:            
                return 2;
                break;
            
            case 4:            
                return 5;
                break;

            case 7:            
                return 8;
                break;

            case 10:
                return 11;
                break;

            case 12:
                return 13;
                break;

            default:
                # code...
                break;
        }
    }

    public function getManagerGroupId($currentGroupId){
        switch ($currentGroupId) {
            case 1:
            case 2:
                return 3;
                break;
            
            case 4:
            case 5:
                return 6;
                break;

            case 7:
            case 8:
                return 9;
                break;

            case 10:
            case 11:
            case 12:
            case 13:
                return 14;
                break;

            default:
                # code...
                break;
        }
    }

    public function checkIfUserValidToViewDetails($groupId,$userId,$requestTaskId,$userType,$team,$teamGroupIds){
        //_p($groupId);_p($userId);_p($requestTaskId);_p($userType);_p($team);_p($teamGroupIds);die;
        $defaultGroupIdsToViewTask = array(15,16);
        if(in_array($groupId, $teamGroupIds['admin'])){
            return true;
        }else{
            if(in_array($groupId, $teamGroupIds['salesTeam'])){
                return $this->_checkIfSalesUserIsValidToViewTask($groupId,$userId,$requestTaskId,$userType);
            }else{
                if($groupId){
                    return $this->_checkIfUserIsValidToViewTask($groupId,$userId,$requestTaskId,$userType,$team,$teamGroupIds);
                }
            }
        }
    }

    private function _checkIfSalesUserIsValidToViewTask($groupId,$userId,$requestTaskId,$userType){
        // Check For Requested By 
        if(!$userType){
            return false;
        }else if($userType == 'manager'){
            return true;
        }else{
            $result = $this->salseOpsModel->getTaskCategoryCurrentAssignee($requestTaskId);
            $requestId = $result[0]['requestId'];
            $result = $this->salseOpsModel->getRequestDetails($requestId);
            if($result[0]['reassignedUserIds']){
                $userIds = explode(',',$result[0]['reassignedUserIds']);
            }
            $userIds[] = $result[0]['requestedBy'];
            //_p($userIds);die;
            if($userType == 'lead'){ // lead level
                if(in_array($userId, $userIds)){                    
                    return true;
                }else{ // check for his users
                    $result = $this->salseOpsModel->getAddedUserIds($userId,'userId');
                    $flag = false;
                    foreach ($result as $key => $value) {
                        if(in_array($value['userId'],$userIds))
                        {
                            $flag = true;
                            break;
                        }
                    }
                    return $flag;
                }
            }else if($userType == 'user'){ // user level
                if(in_array($userId, $userIds)){
                    return true;
                }else{
                    return false;
                }
            }
        }
    }

    private function _checkIfUserIsValidToViewTask($groupId,$userId,$requestTaskId,$userType,$team,$teamGroupIds){
        $result = $this->salseOpsModel->getTaskCategoryCurrentAssignee($requestTaskId);
        //_p($result);die;
        $assigneeGroupId = $this->salseOpsModel->getGroupId($result[0]['assignee']);
        if($result){
            $taskDetails = $result[0];
            $designTeamTaskCategory = array('Banner','Shoshkele','Mailer');
            if(in_array($taskDetails['taskCategory'],$designTeamTaskCategory)){
                if($taskDetails['taskCategory'] == 'Mailer'){
                    return $this->_checkIfDesignUserIsValidToViewTask($userId,$requestTaskId,$taskDetails,$userType,$assigneeGroupId,$groupId);
                }else{
                    if(in_array($groupId,$teamGroupIds['salesOps'])){
                        return $this->_checkIfSalesOpsUserIsValidToViewTask($userId,$requestTaskId,$taskDetails,$userType,$assigneeGroupId,$groupId);
                    }else if(in_array($groupId,$teamGroupIds['design'])){
                        return $this->_checkIfDesignUserIsValidToViewTask($userId,$requestTaskId,$taskDetails,$userType,$assigneeGroupId,$groupId);
                    }else{
                        return false;
                    }
                }
            }else if($taskDetails['taskCategory'] == 'Listing'){
                    if(in_array($groupId,$teamGroupIds['salesOps'])){
                        return $this->_checkIfSalesOpsUserIsValidToViewTask($userId,$requestTaskId,$taskDetails,$userType,$assigneeGroupId,$groupId);
                    }else if(in_array($groupId,$teamGroupIds['contentOps'])){
                        return $this->_checkIfListingUserIsValidToViewTask($userId,$requestTaskId,$taskDetails,$userType,$assigneeGroupId,$groupId);
                    }else{
                        return false;
                    }
            }else if($taskDetails['taskCategory'] == 'Campaign Activation'){
                return $this->_checkIfSalesOpsUserIsValidToViewTask($userId,$requestTaskId,$taskDetails,$userType,$assigneeGroupId,$groupId);
            }else{
                return false;    
            }
        }else{
            return false;
        }       
    }

    private function _checkIfSalesOpsUserIsValidToViewTask($userId,$requestTaskId,$taskDetails,$userType,$assigneeGroupId,$groupId){
        //_p($userId);_p($requestTaskId);_p($taskDetails);_p($userType);_p($assigneeGroupId);_p($groupId);die;
        if($taskDetails['taskCategory'] == 'Campaign Activation'){
            if($userType == 'manager'){
                return true;
            }else if($userType == 'lead'){
                if($taskDetails['assignee'] == $userId){
                    return true;
                }else{
                    return $this->_checkIfLeadUserIsValid($userId,$requestTaskId);
                }
            }else if($userType == 'user'){
                return $this->checkIfGivenUsersIsViewTask($requestTaskId,array($userId));
            }else{
                return false;
            }
        }else{
            $result = $this->salseOpsModel->getAllTaskForGivenRequestId($taskDetails['requestId']);
            $taskId = '';
            foreach ($result as $key => $value) {
                if($value['taskCategory'] == 'Campaign Activation'){
                    $taskId = $value['id'];
                    break;
                }
            }
            if($taskId != ''){
                if($userType == 'manager'){
                    return true;
                }else if($userType == 'lead'){
                    if($taskDetails['assignee'] == $userId){
                        return true;
                    }else{
                        return $this->_checkIfLeadUserIsValid($userId,$taskId);
                    }
                }else if($userType == 'user'){
                    return $this->checkIfGivenUsersIsViewTask($requestTaskId,array($userId));
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
    }

    private function _checkIfListingUserIsValidToViewTask($userId,$requestTaskId,$taskDetails,$userType,$assigneeGroupId,$groupId){
        if($userType == 'manager'){
            return true;
        }else if($userType == 'lead'){
            if($taskDetails['assignee'] == $userId){
                return true;
            }else{
                return $this->_checkIfLeadUserIsValid($userId,$requestTaskId);
            }
        }else if($userType == 'user'){
            return $this->checkIfGivenUsersIsViewTask($requestTaskId,array($userId));
        }else{
            return false;
        }
    }

    private function _checkIfDesignUserIsValidToViewTask($userId,$requestTaskId,$taskDetails,$userType,$assigneeGroupId,$groupId){
        if($userType == 'manager'){
            return true;
        }else if($userType == 'lead'){
            if($taskDetails['assignee'] == $userId){
                return true;
            }else{
                return $this->_checkIfLeadUserIsValid($userId,$requestTaskId);
            }
        }else if($userType == 'user'){
            return $this->checkIfGivenUsersIsViewTask($requestTaskId,array($userId));
        }else{
            return false;
        }
    }

    public function getTaskIdsForGivenUserIds($userIds){
        $result = $this->salseOpsModel->getTaskActionIdsForUsers($userIds);
        $taskActionIds = array();
        foreach ($result as $key => $value) {
            $taskActionIds[] = $value['taskActionDetailId'];
        }
        $result = $this->salseOpsModel->getTaskIdsForUsers($userIds,$taskActionIds);
        foreach ($result as $key => $value) {
            $requestTaskIds[] = $value['requestTaskId'];
        }
        return $requestTaskIds;
    }

    public function checkIfGivenUsersIsViewTask($requestTaskId,$userIds){
        $result = $this->salseOpsModel->checkIfUserPerformMainActionOnTask($requestTaskId,$userIds);
        //_p($result);die;
        if($result){
            return true;
        }else{
            $result = $this->salseOpsModel->getTaskActionIdsForTaskId($requestTaskId);
            foreach ($result as $key => $value) {
                $taskActionIds[] = $value['id'];
            }
            $result = $this->salseOpsModel->checkIfUserPerformOtherActionOnTask($taskActionIds,$userIds);
            if($result){
                return true;
            }else{
                return false;
            }
        }
    }

    private function _checkIfLeadUserIsValid($userId,$requestTaskId){
        $result = $this->salseOpsModel->getAddedUserIds($userId,'userId');
        foreach ($result as $key => $value) {
            $userIds[] = $value['userId'];
        }
        $userIds[] = $userId;
        //$this->
        return $this->checkIfGivenUsersIsViewTask($requestTaskId,$userIds);
    }

    public function getLastUpdatedOnForTask($requestTaskId){
        return $this->salseOpsModel->getLastUpdatedOnForTask($requestTaskId);
    }

    public function getTaskDetailHistory($requestTaskId,& $attachmentDetails){
        $taskDetailHistory = array();
        $taskActionDetails = array();
        $taskDetailHistory = $this->salseOpsModel->getTaskDetailHistory($requestTaskId);
        //_p($taskDetailHistory);die;

        $taskActionDetailId = array();
        $userIds = array();
        if($taskDetailHistory){
            foreach ($taskDetailHistory as $key => $value) {
                if($value['isOtherActionTaken'] != 'no'){
                    $taskActionDetailId[] = $value['id'];                    
                }
                $userIds[] = $value['assignedBy'];
                $userIds[] = $value['assignee'];
                $userIds[] = $value['actionTakenBy'];
            }
            //_p($taskActionDetailId);die;
            $pushBackAndCommentDetails = array();
            if(count($taskActionDetailId) > 0){
                $result = $this->salseOpsModel->getPushBackAndCommentDetails($taskActionDetailId);
                //_p($result);die;
                foreach ($result as $key => $value) {
                    $userIds[] = $value['assignee'];
                    $pushBackAndCommentDetails[$value['taskActionDetailId']][] = $value;
                }
            }

            // user details
            if(count($userIds)> 0){
                $userIds = array_unique($userIds);
                $userDetails = $this->getUserDetails($userIds);    
            }

            $i=0;
            $level = 0;
            $assignee = '';
            $noOfRows = count($taskDetailHistory);
            $remainingRows = $noOfRows-1;
            //_p($remainingRows);die;
            foreach ($taskDetailHistory as $key => $value) {
                $taskActionDetails[$value['level']]['assignee'] = $userDetails[$value['assignee']]['userName'];
                
                $commentAndPushBackDetails = array();
                if($value['isOtherActionTaken'] != 'no'){                        
                    $commentAndPushBackDetails = $this->preparePushBackAndCommentData($pushBackAndCommentDetails[$value['id']],$userDetails,$attachmentDetails);
                }

                if(count($commentAndPushBackDetails) > 0){
                    $taskActionDetails[$value['level']]['commentDetails']['commentAndPushBackDetails'] = $commentAndPushBackDetails;
                }
                if($remainingRows > 0){
                    $taskActionDetails[$value['level']]['status'] = $this->statusToShowStatusName($value['status']);
                    $taskActionDetails[$value['level']]['updatedOn'] = $this->getFormattedDate($value['updatedDate'],'dateTime');
                    $taskActionDetails[$value['level']]['statusCommentDetails'] = array(
                        'comment' => $value['commentDetails'],
                        'attachmentURL' => $value['attachmentURL']
                        );
                    if($value['attachmentURL']){
                        $attachmentDetails[] = array(
                            'attachmentURL' => $value['attachmentURL'],
                            'addedBy' => $value['actionTakenBy']?$userDetails[$value['actionTakenBy']]['userName']:$userDetails[$value['assignee']]['userName'],
                            'addedOn' => $this->getFormattedDate($value['updatedDate'],'dateTime')
                            );
                    }
                    if($value['actionTakenBy']){
                        $taskActionDetails[$value['level']]['doneBy'] = $userDetails[$value['actionTakenBy']]['userName'].' ( On behalf of '.$userDetails[$value['assignee']]['userName'].' )';
                    }
                }else if($remainingRows == 0){
                    //_p($value);die;
                    $allowStatus = array('pushedBack','cancel','clientApprovedAndClosed');
                    if(in_array($value['status'], $allowStatus)){
                        $taskActionDetails[$value['level']]['status'] = $this->statusToShowStatusName($value['status']);
                        $taskActionDetails[$value['level']]['updatedOn'] = $this->getFormattedDate($value['updatedDate'],'dateTime');

                        if($value['actionTakenBy']){
                            $taskActionDetails[$value['level']]['doneBy'] = $userDetails[$value['actionTakenBy']]['userName'];
                        }
                        if(($value['status'] == 'cancel') || ($value['status'] == 'clientApprovedAndClosed') ){
                            $taskActionDetails[$value['level']]['status'] = '<b>'.$taskActionDetails[$value['level']]['status'].'</b>';
                            $taskActionDetails[$value['level']]['statusCommentDetails'] = array(
                                'comment' => $value['commentDetails'],
                                'attachmentURL' => $value['attachmentURL']
                            );                         
                        }
                        if($value['attachmentURL']){
                            $attachmentDetails[] = array(
                                'attachmentURL' => $value['attachmentURL'],
                                'addedBy' => $value['actionTakenBy']?$userDetails[$value['actionTakenBy']]['userName']:$userDetails[$value['assignee']]['userName'],
                                'addedOn' => $this->getFormattedDate($value['updatedDate'],'dateTime')
                                );
                        }
                        
                    }                        
                }
                $remainingRows = $remainingRows -1;               
            }
        }
        //_p($attachmentDetails);die;
        //$taskActionDetails['attachmentDetails'] = $attachmentDetails;
        //_p($taskActionDetails);die;
        return $taskActionDetails;
    }

    public function statusToShowStatusName($status){
        switch($status) {
            case 'clientApprovedAndClosed' :
                return 'Client Approved & Closed';
                break;

            case 'clientApprovedAndCreateHTML' :
                return 'Client Approved & Create HTML';
                break;

            case 'inProgress':
                return 'In Progress';
                break;

            case 'reassign':
                return 'Reassigned';
                break;

            case 'approvedFromClient':
                return 'Approved From Client';
                break;

            case 'commentAdded':
                return 'Comment Added';
                break;

            case 'doneAndReviewed':
                return 'Done & Reviewed';
                break;

            case 'markedDone':
                return 'Marked Done';
                break;

            case 'pushedBack':
                return 'Pushed Back';
                break;

            case 'forwarded':
                return 'Forwarded';
                break;

            case 'forward':
                return 'Forwarded';
                break;

            case 'cancel':
                return 'Cancelled';
                break;

            case 'closed':
                return 'Closed';
                break;

            case 'partialDoneAndReviewed':
                return 'Partial Done & Reviewed';
                break;

            case 'completePartialDoneAndReviewed':
                return 'Complete Partial Done & Reviewed';
                break;

            default:
                return $status;
                break;
        }
    }

    public function getFormattedDate($date,$format){
        switch ($format) {
            case 'dateTime':
                return date('M d, Y h:i:s A',strtotime($date));
                break;

            case 'date':
                return date('M d, Y',strtotime($date));
                break;                
            
            default:
                # code...
                break;
        }
    }

    public function preparePushBackAndCommentData($pushBackAndCommentDetails,$userDetails,& $attachmentDetails){
        //_p($userDetails);
        //_p($pushBackAndCommentDetails);die;
        $dataForPushBackAndComment = array();
        if(count($pushBackAndCommentDetails) > 0){
            foreach ($pushBackAndCommentDetails as $key => $value) {
                if(!$userDetails[$value['assignedBy']]['userName']){
                    $userIds[] = $value['assignedBy'];
                }
                if(!$userDetails[$value['assignee']]['userName']){
                    $userIds[] = $value['assignee'];
                }
                if(!$userDetails[$value['actionTakenBy']]['userName']){
                    $userIds[] = $value['actionTakenBy'];
                }                
            }            
            $userData =array();
            if(count($userIds)> 0){
                $userIds = array_unique($userIds);                
                $userData = $this->getUserDetails($userIds);    
            }
            foreach ($userData as $key => $value) {
                $userDetails[$key] = $value;
            }
            //_p($userDetails);die;
            $i=0;
            foreach ($pushBackAndCommentDetails as $key => $value) {
                //if($value['status'] == 'commentAdded'){
                    $dataForPushBackAndComment[$i] = array(
                        'statusTakenBy' => $userDetails[$value['assignedBy']]['userName'],
                        'status' => $this->statusToShowStatusName($value['status']),
                        'statusTakenOn' => $this->getFormattedDate($value['updatedDate'],'dateTime'),
                        'comment' => $value['commentDetails'],
                        'attachmentURL' => $value['attachmentURL']
                    );
                    if($value['attachmentURL']){
                        $attachmentDetails[] = array(
                            'attachmentURL' => $value['attachmentURL'],
                            'addedBy' => $value['actionTakenBy']?$userDetails[$value['actionTakenBy']]['userName']:$userDetails[$value['assignedBy']]['userName'],
                            'addedOn' => $this->getFormattedDate($value['updatedDate'],'dateTime')
                            );
                    }
                //}
                if($value['status'] == 'pushedBack'){
                    $dataForPushBackAndComment[$i]['pushBackAssignedTo'] =  $userDetails[$value['assignee']]['userName'];
                }
                if($value['status'] == 'forwarded'){
                    $dataForPushBackAndComment[$i]['pushBackAssignedTo'] =  $userDetails[$value['assignee']]['userName'];
                }
                if($value['actionTakenBy']){
                    $dataForPushBackAndComment[$i]['actionTakenBy'] =    $userDetails[$value['actionTakenBy']]['userName'];
                }
                $i++;
            }   
        }
        //_p($dataForPushBackAndComment);die;
        return $dataForPushBackAndComment;
    }

    public function getTaskDataForViewTaskDetails($requestTaskId){
        $result = $this->salseOpsModel->getTaskData($requestTaskId);
        $taskData = $result[0];
        //_p($taskData);die;
        if($taskData){
            $requestId = $taskData['requestId'];
            $requestData = $this->salseOpsModel->getRequestDetails($requestId);        
            $requestData =$requestData[0];

            $userIds = array($requestData['requestedBy'],$taskData['assignee']);
            $userDetails =$this->getUserDetails($userIds);
            $taskDatails = array(
                'clientName' => $requestData['clientName'],
                'taskId' => $requestTaskId,
                'requestId' => $requestId,
                'taskCategory' => $taskData['taskCategory'],
                'taskTitle' => $taskData['taskTitle'],
                'assignedBy' => $userDetails[$requestData['requestedBy']]['userName'],
                'assignedDate' => $this->getFormattedDate($requestData['requestedOn'],'date'),
                'imageAddedOn' => $this->getFormattedDate($requestData['requestedOn'],'dateTime'),
                'salesOrderNumber' =>$requestData['salesOrderNumber'],
                'campaignDate' => $this->getFormattedDate($requestData['campaignDate'],'date'),
                'assignedTo' => $userDetails[$taskData['assignee']]['userName'],
                'status' => $taskData['currentStatus'],
                'statusToShow' => $this->statusToShowStatusName($taskData['currentStatus']),
                'lastUpdatedOn' => $this->getFormattedDate($requestData['lastUpdatedOn'],'dateTime'),
                'TATDate' => $this->getFormattedDate($taskData['TATDate'],'date'),
                'description' => $taskData['description'],
                'attachmentURL' => $taskData['attachmentURL'],
                'taskAssignedTo' => $taskData['assignee'],
                'lastUpdatedOnDate' => $taskData['lastUpdatedOn'],
                'site' => $taskData['site'],
                'changeType' => $taskData['changeType'],
                'taskRequestedBy' => $taskData['taskRequestedBy'],
                'taskType' => $taskData['taskType'],
                'requestTaskTitle' => $taskData['taskTitle']
                );
            $taskAttributes = $this->salseOpsModel->getTaskAttributes($requestTaskId);
            if($taskDatails['status'] =='cancel' || $taskDatails['status'] =='clientApprovedAndClosed'){
                $taskDatails['statusToShow'] = '<b>'.$taskDatails['statusToShow'].'</b>';
            }
            //_p($taskDatails);die;
            if($taskData['taskCategory'] == 'Mailer'){
                foreach ($taskAttributes as $key => $value) {
                    $taskDatails[$value['attributeName']] = $value['attributeValue'];
                }
            }else if($taskData['taskCategory'] == 'Listing'){
                foreach ($taskAttributes as $key => $value) {
                    if(($value['attributeName'] == 'university') || ($value['attributeName'] == 'institute') || ($value['attributeName'] == 'university_national')){
                        $result = $this->salseOpsModel->getListingName($value['attributeValue'],$value['attributeName']);
                        $taskDatails['listingNameForListing'] = $result[0]['listing_title'];
                        $taskDatails['listingTypeForListing'] = ucFirst($value['attributeName'] =="university_national" ? "university":$value['attributeName']);
                        $taskDatails['listingIdForListing'] = $value['attributeValue'];
                    }
                }
            }else if($taskData['taskCategory'] == 'Banner'){
                foreach ($taskAttributes as $key => $value) {
                    if(($value['attributeName'] == 'diffBannerSize')){
                        $diffBannerSize = json_decode($value['attributeValue']);
                        $taskDatails[$value['attributeName']] = $diffBannerSize;
                        //_p($diffBannerSize);die;
                        //_p($result);die;
                    }
                }
            }else if($taskData['taskCategory'] == 'Shoshkele'){
                foreach ($taskAttributes as $key => $value) {

                    if(($value['attributeName'] == 'landingPageURL')){
                        if($taskDatails['site'] == 'Domestic'){
                            $prefix = SHIKSHA_HOME;
                        }else{
                            $prefix = SHIKSHA_STUDYABROAD_HOME;
                        }
                        $taskDatails[$value['attributeName']] =  '<a href="'.$prefix.$value['attributeValue'].'">'.$prefix.$value['attributeValue'].'</a>';
                    }else{
                        $taskDatails[$value['attributeName']] = $value['attributeValue'];
                    }
                }
            }else if($taskData['taskCategory'] == 'Campaign Activation'){
                foreach ($taskAttributes as $key => $value) {
                    if(($value['attributeName'] == 'landingPageURL')){
                        $prefix='';
                        if($taskDatails['site'] == 'Domestic'){
                            $prefix = SHIKSHA_HOME;
                        }else{
                            $prefix = SHIKSHA_STUDYABROAD_HOME;
                        }
                        $taskDatails[$value['attributeName']] =  '<a href="'.$prefix.$value['attributeValue'].'">'.$prefix.$value['attributeValue'].'</a>';
                    }else if(($value['attributeName'] == 'diffCampaignTypes')){
                        $diffBannerSize = json_decode($value['attributeValue']);
                        $taskDatails[$value['attributeName']] = $diffBannerSize;
                        //_p($diffBannerSize);die;
                        //_p($result);die;
                    }else{
                        $taskDatails[$value['attributeName']] = $value['attributeValue'];
                    }
                }
            }
            return $taskDatails;
        }else{
            return array();
        }
    }

    //-------------------------------------------------------------------------------
    //-------------------------------------------------------------------------------

    // Prepare various status to show and diff dropdown data for various status

    public function prepareStatusForUser($inputForStatusGeneration){
        //_p($inputForStatusGeneration);die;
        //$mainStatusArray = array('Pushed Back','Forwarded','Marked as Done','Closed','In Progress','Approved From Client');
        $userGroupId = $inputForStatusGeneration['groupId'];
        $teamsGroupIds = $inputForStatusGeneration['teamGroupIds'];
        if(in_array($userGroupId,$teamsGroupIds['salesTeam'])){
            $statusArray = $this->prepareStatusForSalesTeam($inputForStatusGeneration);
        }else if(in_array($userGroupId,$teamsGroupIds['salesOps'])){
            $statusArray = $this->prepareStatusForSalesOpsTeam($inputForStatusGeneration);
        }else if(in_array($userGroupId,$teamsGroupIds['admin'])){
            $statusArray = $this->prepareStatusForAdmin($inputForStatusGeneration);
        }else if(in_array($userGroupId,$teamsGroupIds['design'])){
            $statusArray = $this->prepareStatusForDesignTeam($inputForStatusGeneration);
        }else if(in_array($userGroupId,$teamsGroupIds['contentOps'])){
            $statusArray = $this->prepareStatusForContentOpsTeam($inputForStatusGeneration);
        }
        return $statusArray;
    }

    public function prepareStatusForSalesOpsTeam($inputForStatusGeneration){
        //_p($inputForStatusGeneration);_p($currentStatus);die;
        $currentStatus = $inputForStatusGeneration['status'];
        if($inputForStatusGeneration['taskCategoty'] != 'Campaign Activation'){
            $teamStatusArray['Add Comment'] = 'commentAdded';
        }else{
            if($currentStatus == 'pushedBack'){
                $teamStatusArray = $this->prepapreActionForPushedBackStatus($inputForStatusGeneration);
            }else if($currentStatus == 'markedDone'){
                $teamStatusArray = $this->prepapreActionForMarkedDoneStatus($inputForStatusGeneration);
            }else if($currentStatus == 'inProgress'){
                $teamStatusArray = $this->prepapreActionForInProgressStatus($inputForStatusGeneration);
            }else if($currentStatus == 'doneAndReviewed' || $currentStatus == 'partialDoneAndReviewed'){
                $teamStatusArray['Add Comment'] = 'commentAdded';
            }
        }
        return $teamStatusArray;
    }

    public function prepareStatusForSalesTeam($inputForStatusGeneration){
        //_p($inputForStatusGeneration);
        $taskAssignedTo = $inputForStatusGeneration['taskAssignedTo'];
        $currentStatus = $inputForStatusGeneration['status'];
        $groupIdForAssignee = $this->salseOpsModel->getGroupId($taskAssignedTo);
        if(!in_array($groupIdForAssignee, $inputForStatusGeneration['teamGroupIds']['salesTeam'])){
            $statusArray = array('cancel','clientApprovedAndClosed');
            if(!in_array($currentStatus, $statusArray)){
                $salesTeamStatusArray = array(
                    'Cancel' => 'cancel',
                    'Add Comment' => 'commentAdded'
                );
            }
        }else if($currentStatus == 'clientApprovedAndClosed'){
            $salesTeamStatusArray = array();
        }else if($currentStatus == 'cancel'){
            $salesTeamStatusArray = array();
        }else{
            if($currentStatus == 'doneAndReviewed'){
                if($inputForStatusGeneration['userType'] != 'manager'){
                    $result = $this->salseOpsModel->getTaskCategoryCurrentAssignee($inputForStatusGeneration['requestTaskId']);
                    //_p($result);die;
                    $requestId = $result[0]['requestId'];
                    $result = $this->salseOpsModel->getRequestDetails($requestId);
                    //_p($result);die;
                    $requestedBy = $result[0]['requestedBy'];
                    $userIds = explode(',',$result[0]['reassignedUserIds']);
                    $userIdsCount = count($userIds);
                    $lastUserUserId = $userIds[$userIdsCount-1];

                    if($inputForStatusGeneration['userType'] == 'user'){
                        if(($requestedBy == $inputForStatusGeneration['userId']) || ($lastUserUserId == $inputForStatusGeneration['userId'])){
                            $salesTeamStatusArray = array(
                                'Push Back' => 'pushedBack',
                                'Cancel' => 'cancel',            
                                'Add Comment' => 'commentAdded'
                            );
                            $canPerformTaskCloseAction = true;
                        }else{
                            $canPerformTaskCloseAction = false;
                            $salesTeamStatusArray['Add Comment'] = 'commentAdded';
                        }
                    }else if($inputForStatusGeneration['userType'] == 'lead'){
                        $userId = $inputForStatusGeneration['userId'];

                        if($requestedBy == $userId){
                            $canPerformTaskCloseAction = true;
                            $salesTeamStatusArray = array(
                                'Push Back' => 'pushedBack',
                                'Cancel' => 'cancel',            
                                'Add Comment' => 'commentAdded',
                                'Reassign' => 'reassign'
                            );
                        }else if($lastUserUserId == $userId){
                            $canPerformTaskCloseAction = true;
                            $salesTeamStatusArray = array(
                                'Push Back' => 'pushedBack',
                                'Cancel' => 'cancel',            
                                'Add Comment' => 'commentAdded',
                                'Reassign' => 'reassign'
                            );
                        }else if($requestedBy != $userId){
                            if($lastUserUserId != $userId){
                                $teamUserIds = $this->salseOpsModel->getAddedUserIds($inputForStatusGeneration['userId'],'userId');
                                $canPerformTaskCloseAction = false;
                                $canAddComment = false;
                                foreach ($teamUserIds as $key => $value) {
                                    if(in_array($value['userId'],$userIds)){
                                        $canAddComment = true;
                                        if(($lastUserUserId == $value['userId']) || ($requestedBy == $value['userId'])){
                                            $canPerformTaskCloseAction = true;
                                            break;
                                        }
                                    }
                                }                        
                                if($canPerformTaskCloseAction == false){
                                    if($canAddComment == true){
                                        $salesTeamStatusArray['Add Comment'] = 'commentAdded';
                                    }
                                }else{
                                    $salesTeamStatusArray = array(
                                        'Push Back' => 'pushedBack',
                                        'Cancel' => 'cancel',            
                                        'Add Comment' => 'commentAdded',
                                        'Reassign' => 'reassign'
                                    );
                                }
                            }
                        }
                    }
                }else if($inputForStatusGeneration['userType'] == 'manager'){
                    $canPerformTaskCloseAction = true;
                    $salesTeamStatusArray = array(
                        'Push Back' => 'pushedBack',
                        'Cancel' => 'cancel',            
                        'Add Comment' => 'commentAdded',
                        'Reassign' => 'reassign'
                    );
                }
                if($canPerformTaskCloseAction == true){
                    //check if it has previous status a clientApprovedAndCreateHTML
                    $resultData = $this->salseOpsModel->checkIfTaskHaveClientApprovedAndCreateHTMLPrevious($inputForStatusGeneration['requestTaskId']);
                    if($resultData){
                        $salesTeamStatusArray['Client Approved & Closed'] = 'clientApprovedAndClosed';
                    }else{
                        if(($inputForStatusGeneration['taskCategoty'] == 'Mailer') && ($inputForStatusGeneration['taskType'] == 'New Design')){
                            $salesTeamStatusArray['Client Approved & Create HTML'] = 'clientApprovedAndCreateHTML';
                        }else{
                            $salesTeamStatusArray['Client Approved & Closed'] = 'clientApprovedAndClosed';
                        }
                    }
                }
            }else if($currentStatus == 'pushedBack'){
                if($inputForStatusGeneration['userType'] != 'manager'){
                    $result = $this->salseOpsModel->getTaskCategoryCurrentAssignee($inputForStatusGeneration['requestTaskId']);
                    //_p($result);die;
                    $requestId = $result[0]['requestId'];
                    $result = $this->salseOpsModel->getRequestDetails($requestId);
                    //_p($result);die;
                    $requestedBy = $result[0]['requestedBy'];
                    $userIds = explode(',',$result[0]['reassignedUserIds']);
                    $userIdsCount = count($userIds);
                    $lastUserUserId = $userIds[$userIdsCount-1];

                    if($inputForStatusGeneration['userType'] == 'user'){
                        if(($requestedBy == $inputForStatusGeneration['userId']) || ($inputForStatusGeneration['taskAssignedTo'] == $inputForStatusGeneration['userId'])){
                            $salesTeamStatusArray = array(
                                'Forward' => 'forwarded',
                                'Cancel' => 'cancel',
                                'Add Comment' => 'commentAdded'
                            );
                        }else{
                            $salesTeamStatusArray['Add Comment'] = 'commentAdded';
                        }
                    }else if($inputForStatusGeneration['userType'] == 'lead'){
                        $userId = $inputForStatusGeneration['userId'];
                        if($requestedBy == $userId){
                            $salesTeamStatusArray = array(
                                'Forward' => 'forwarded',
                                'Cancel' => 'cancel',
                                'Add Comment' => 'commentAdded'
                            );
                        }else if($inputForStatusGeneration['taskAssignedTo'] == $userId){
                            $salesTeamStatusArray = array(
                                'Forward' => 'forwarded',
                                'Cancel' => 'cancel',
                                'Add Comment' => 'commentAdded'
                            );
                        }else if($requestedBy != $userId){
                            if($inputForStatusGeneration['taskAssignedTo'] != $userId){
                                $teamUserIds = $this->salseOpsModel->getAddedUserIds($inputForStatusGeneration['userId'],'userId');
                                //_p($teamUserIds);
                                $canPerformTaskOtherAction = false;
                                $canAddComment = false;
                                foreach ($teamUserIds as $key => $value) {
                                    if(in_array($value['userId'],$userIds)){
                                        $canAddComment = true;
                                        if(($inputForStatusGeneration['taskAssignedTo'] == $value['userId']) || ($requestedBy == $value['userId'])){
                                            $canPerformTaskOtherAction = true;
                                            break;
                                        }
                                    }
                                }                        
                                if($canPerformTaskOtherAction == false){
                                    if($canAddComment == true){
                                        $salesTeamStatusArray['Add Comment'] = 'commentAdded';
                                    }
                                }else{
                                    $salesTeamStatusArray = array(
                                        'Forward' => 'forwarded',
                                        'Cancel' => 'cancel',
                                        'Add Comment' => 'commentAdded'
                                    );
                                }
                            }
                        }
                    }
                }else if($inputForStatusGeneration['userType'] == 'manager'){
                    $salesTeamStatusArray = array(
                        'Forward' => 'forwarded',
                        'Cancel' => 'cancel',
                        'Add Comment' => 'commentAdded'
                    );
                }
            }else if($currentStatus == 'partialDoneAndReviewed'){
                if($inputForStatusGeneration['userType'] != 'manager'){
                    $result = $this->salseOpsModel->getTaskCategoryCurrentAssignee($inputForStatusGeneration['requestTaskId']);
                    $requestId = $result[0]['requestId'];
                    $result = $this->salseOpsModel->getRequestDetails($requestId);
                    $requestedBy = $result[0]['requestedBy'];
                    $userIds = explode(',',$result[0]['reassignedUserIds']);
                    $userIdsCount = count($userIds);
                    $lastUserUserId = $userIds[$userIdsCount-1];

                    if($inputForStatusGeneration['userType'] == 'user'){
                        if(($requestedBy == $inputForStatusGeneration['userId']) || ($lastUserUserId == $inputForStatusGeneration['userId'])){
                            $salesTeamStatusArray = array(
                                'Push Back'                        => 'pushedBack',
                                'Cancel'                           => 'cancel',            
                                'Add Comment'                      => 'commentAdded',
                                'Complete Partial Done & Reviewed' => 'completePartialDoneAndReviewed'
                            );
                            $canPerformTaskCloseAction = true;
                        }else{
                            $canPerformTaskCloseAction = false;
                            $salesTeamStatusArray['Add Comment'] = 'commentAdded';
                        }
                    }else if($inputForStatusGeneration['userType'] == 'lead'){
                        $userId = $inputForStatusGeneration['userId'];

                        if(($requestedBy == $userId) || ($lastUserUserId == $userId)){
                            $canPerformTaskCloseAction = true;
                            $salesTeamStatusArray = array(
                                'Push Back'                        => 'pushedBack',
                                'Cancel'                           => 'cancel',            
                                'Add Comment'                      => 'commentAdded',
                                'Reassign'                         => 'reassign',
                                'Complete Partial Done & Reviewed' => 'completePartialDoneAndReviewed'
                            );
                        }else if($requestedBy != $userId){
                            if($lastUserUserId != $userId){
                                $teamUserIds = $this->salseOpsModel->getAddedUserIds($inputForStatusGeneration['userId'],'userId');
                                $canPerformTaskCloseAction = false;
                                $canAddComment = false;
                                foreach ($teamUserIds as $key => $value) {
                                    if(in_array($value['userId'],$userIds)){
                                        $canAddComment = true;
                                        if(($lastUserUserId == $value['userId']) || ($requestedBy == $value['userId'])){
                                            $canPerformTaskCloseAction = true;
                                            break;
                                        }
                                    }
                                }                        
                                if($canPerformTaskCloseAction == false){
                                    if($canAddComment == true){
                                        $salesTeamStatusArray['Add Comment'] = 'commentAdded';
                                    }
                                }else{
                                    $salesTeamStatusArray = array(
                                        'Push Back'                        => 'pushedBack',
                                        'Cancel'                           => 'cancel',            
                                        'Add Comment'                      => 'commentAdded',
                                        'Reassign'                         => 'reassign',
                                        'Complete Partial Done & Reviewed' => 'completePartialDoneAndReviewed'
                                    );
                                }
                            }
                        }
                    }
                }else if($inputForStatusGeneration['userType'] == 'manager'){
                    $canPerformTaskCloseAction = true;
                    $salesTeamStatusArray = array(
                        'Push Back'                        => 'pushedBack',
                        'Cancel'                           => 'cancel',            
                        'Add Comment'                      => 'commentAdded',
                        'Reassign'                         => 'reassign',
                        'Complete Partial Done & Reviewed' => 'completePartialDoneAndReviewed'
                    );
                }
                if($canPerformTaskCloseAction == true){
                    //check if it has previous status a clientApprovedAndCreateHTML
                    $resultData = $this->salseOpsModel->checkIfTaskHaveClientApprovedAndCreateHTMLPrevious($inputForStatusGeneration['requestTaskId']);
                    if($resultData){
                        $salesTeamStatusArray['Client Approved & Closed'] = 'clientApprovedAndClosed';
                    }else{
                        if(($inputForStatusGeneration['taskCategoty'] == 'Mailer') && ($inputForStatusGeneration['taskType'] == 'New Design')){
                            $salesTeamStatusArray['Client Approved & Create HTML'] = 'clientApprovedAndCreateHTML';
                        }else{
                            $salesTeamStatusArray['Client Approved & Closed'] = 'clientApprovedAndClosed';
                        }
                    }
                }
            }
        }

        if($inputForStatusGeneration['taskCategoty'] != "Listing"){
            unset($salesTeamStatusArray['Complete Partial Done & Reviewed']);
        }
        return $salesTeamStatusArray;
    }    

    public function prepareStatusForContentOpsTeam($inputForStatusGeneration){
        //_p($inputForStatusGeneration);die;
        $currentStatus = $inputForStatusGeneration['status'];
        if($currentStatus == 'pushedBack'){
            $teamStatusArray = $this->prepapreActionForPushedBackStatus($inputForStatusGeneration);
        }else if($currentStatus == 'markedDone'){
            $teamStatusArray = $this->prepapreActionForMarkedDoneStatus($inputForStatusGeneration);
        }else if(in_array($currentStatus, array("clientApprovedAndCreateHTML","inProgress","completePartialDoneAndReviewed"))){
            $teamStatusArray = $this->prepapreActionForInProgressStatus($inputForStatusGeneration);
        }else if($currentStatus == 'doneAndReviewed' || $currentStatus == 'partialDoneAndReviewed'){
            $teamStatusArray['Add Comment'] = 'commentAdded';
        }
        return $teamStatusArray;
    }

    public function prepareStatusForDesignTeam($inputForStatusGeneration){
        //_p($inputForStatusGeneration);die;
        $currentStatus = $inputForStatusGeneration['status'];
        if($currentStatus == 'pushedBack'){
            $teamStatusArray = $this->prepapreActionForPushedBackStatus($inputForStatusGeneration);
        }else if($currentStatus == 'markedDone'){
            $teamStatusArray = $this->prepapreActionForMarkedDoneStatus($inputForStatusGeneration);
        }else if(($currentStatus == 'inProgress')||($currentStatus == 'clientApprovedAndCreateHTML')){
            $teamStatusArray = $this->prepapreActionForInProgressStatus($inputForStatusGeneration);
        }else if($currentStatus == 'doneAndReviewed' || $currentStatus == 'partialDoneAndReviewed'){
            $teamStatusArray['Add Comment'] = 'commentAdded';
        }
        return $teamStatusArray;
    }

    public function prepareStatusForAdmin($inputForStatusGeneration){
        //_p($inputForStatusGeneration);die;
        $adminStatusArray = array();
        $allowStatus = array('cancel','clientApprovedAndClosed');
        if(!in_array($inputForStatusGeneration['status'], $allowStatus)){
            $adminStatusArray['Add Comment'] = 'commentAdded';
        }
        return $adminStatusArray;
    }

    public function prepapreActionForInProgressStatus($inputData){
        //_p($inputData);
        $userType = $inputData['userType'];
        $currentStatus = $inputData['status'];
        $teamStatusArray = array();
        if($userType == 'user'){
            if($inputData['userId'] == $inputData['taskAssignedTo']){
                $teamStatusArray = array(
                    'Reassign' => 'reassign',
                    'Push Back' => 'pushedBack',
                    'Marked as Done' => 'markedDone'
                );
            }
        }else if($userType == 'lead'){
            $userIds = array();
            $userIds[] = $inputData['userId'];
            $result = $this->salseOpsModel->getAddedUserIds($inputData['userId'],'userId');
            foreach ($result as $key => $value) {
                $userIds[] = $value['userId'];
            }
            if(in_array($inputData['taskAssignedTo'],$userIds)){
                $teamStatusArray = array(
                    'Reassign'                => 'reassign',
                    'Push Back'               => 'pushedBack',
                    'Done & Reviewed'         => 'doneAndReviewed',
                    'Partial Done & Reviewed' => 'partialDoneAndReviewed'
                );
            }
        }else if($userType == 'manager'){
            $teamStatusArray = array(
                'Reassign'                => 'reassign',
                'Push Back'               => 'pushedBack',
                'Done & Reviewed'         => 'doneAndReviewed',
                'Partial Done & Reviewed' => 'partialDoneAndReviewed'
            );
        }
        $teamStatusArray['Add Comment'] = 'commentAdded';

        if($inputData['team'] != "contentOps"){
            unset($teamStatusArray['Partial Done & Reviewed']);
        }

        return $teamStatusArray;
    }

    public function prepapreActionForMarkedDoneStatus($inputData){
        $userType = $inputData['userType'];
        $currentStatus = $inputData['status'];
        $teamStatusArray = array();

        if($userType =='user'){
            if($inputData['userId'] == $inputData['taskAssignedTo']){
                $teamStatusArray = array(
                    'Push Back' => 'pushedBack',
                    'Done & Reviewed' => 'doneAndReviewed',
                    'Partial Done & Reviewed' => 'partialDoneAndReviewed'
                );
            }
        }else if($userType =='lead'){
            $userIds = array();
            $userIds[] = $inputData['userId'];
            $result = $this->salseOpsModel->getAddedUserIds($inputData['userId'],'userId');
            foreach ($result as $key => $value) {
                $userIds[] = $value['userId'];
            }
            if(in_array($inputData['taskAssignedTo'],$userIds)){
                $teamStatusArray = array(
                    'Reassign' => 'reassign',
                    'Push Back' => 'pushedBack',
                    'Done & Reviewed' => 'doneAndReviewed',
                    'Partial Done & Reviewed' => 'partialDoneAndReviewed'
                );
            }
        }else if($userType =='manager'){
            $teamStatusArray = array(
                'Reassign' => 'reassign',
                'Push Back' => 'pushedBack',
                'Done & Reviewed' => 'doneAndReviewed',
                'Partial Done & Reviewed' => 'partialDoneAndReviewed'
            );
        }
        $teamStatusArray['Add Comment'] = 'commentAdded';
        if($inputData['team'] != "contentOps"){
            unset($teamStatusArray['Partial Done & Reviewed']);
        }
        return $teamStatusArray;
    }

    public function prepapreActionForPushedBackStatus($inputData){
        //_p($inputData);
        $userType = $inputData['userType'];
        $currentStatus = $inputData['status'];
        $teamStatusArray = array();
        $groupId = $this->salseOpsModel->getGroupId($inputData['taskAssignedTo']);
        if($userType =='user'){
            if($inputData['userId'] == $inputData['taskAssignedTo']){
                $teamStatusArray['Forward'] = 'forwarded';                        
            }
        }else{
            $result = $this->salseOpsModel->getTaskActionDetailCurrentLevelAndAssignedBy($inputData['requestTaskId']);
            $lastRowId = $result[0]['id'];
            $result = $this->salseOpsModel->getLastAssignedById($lastRowId);
            $result = $result[0];
            if($result['actionTakenBy']){
                $userId = $result['actionTakenBy'];
            }else{
                $userId = $result['assignedBy'];
            }

            $groupId = $this->salseOpsModel->getGroupId($userId);
            $notAllowedGroupIds = array_merge($inputData['teamGroupIds']['admin'],$inputData['teamGroupIds']['salesTeam']);
            if(!in_array($groupId, $notAllowedGroupIds)){
                if($userType == 'lead'){
                    $userIds = array();
                    $userIds[] = $inputData['userId'];
                    $result = $this->salseOpsModel->getAddedUserIds($inputData['userId'],'userId');
                    foreach ($result as $key => $value) {
                        $userIds[] = $value['userId'];
                    }
                    if(in_array($inputData['taskAssignedTo'],$userIds)){
                        $teamStatusArray['Forward']                 = 'forwarded';
                        $teamStatusArray['Done & Reviewed']         = 'doneAndReviewed';
                        $teamStatusArray['Partial Done & Reviewed'] = 'partialDoneAndReviewed';
                    }
                }else{
                    $teamStatusArray['Forward']                 = 'forwarded';
                    $teamStatusArray['Done & Reviewed']         = 'doneAndReviewed';
                    $teamStatusArray['Partial Done & Reviewed'] = 'partialDoneAndReviewed';
                }
            }else{
                $teamStatusArray['Forward'] = 'forwarded';
            }
        }
        $teamStatusArray['Add Comment'] = 'commentAdded';
        if($inputData['team'] != "contentOps"){
            unset($teamStatusArray['Partial Done & Reviewed']);
        }
        return $teamStatusArray;
    }

    public function getTeamName($groupId){
        return $this->salseOpsModel->getTeamName($groupId);
    }

    public function prepareDataForSelectedStatus($inputArray){
        //_p($inputArray);die;
        $dataForSelectedStatus = array();
        if($inputArray['selectedStatus'] == 'pushedBack'){
            return $this->prepareUserDataForPushedBackStatus($inputArray);
        }else{
            $userGroupId = $inputArray['groupId'];
            $teamsGroupIds = $inputArray['teamGroupIds'];
            if(in_array($userGroupId,$teamsGroupIds['salesTeam'])){
                $dataForSelectedStatus = $this->prepareDataForSelectedStatusForSalesTeam($inputArray);
            }else if(in_array($userGroupId,$teamsGroupIds['salesOps'])){
                $dataForSelectedStatus = $this->prepareDataForSelectedStatusForSalesOpsTeam($inputArray);
            }else if(in_array($userGroupId,$teamsGroupIds['design'])){
                $dataForSelectedStatus = $this->prepareDataForSelectedStatusForDesignTeam($inputArray);
            }else if(in_array($userGroupId,$teamsGroupIds['contentOps'])){
                $dataForSelectedStatus = $this->prepareDataForSelectedStatusForContentOpsTeam($inputArray);
            }
            return $dataForSelectedStatus;
        }
    }

    public function prepareUserDataForPushedBackStatus($inputArray){
        //_p($inputArray);die;
        // pushed back always to requestedBy and assigned by and done by
        $userIds = array();
        if(in_array($inputArray['groupId'],$inputArray['teamGroupIds']['salesTeam'])){
            //_p($inputArray);die;
            /*$result = $this->salseOpsModel->getTaskActionDetailCurrentLevelAndAssignedBy($inputArray['requestTaskId']);
            $lastRowId = $result[0]['id'];
            $result = $this->salseOpsModel->getLastAssignedById($lastRowId);
            $result = $result[0];
            if($result['actionTakenBy']){
                $userId = $result['actionTakenBy'];
            }else{
                $userId = $result['assignedBy'];
            }*/
            $result = $this->salseOpsModel->getUserIdForPushedBack($inputArray['requestTaskId']);
            if(count($result) > 0){
                $result =$result[0];                                
                if($result['actionTakenBy']){
                    $userIds[] = $result['actionTakenBy'];
                }else{
                    $userIds[] = $result['assignee'];
                }
                /*if($result['status'] == 'reassign'){
                    $userIds[] = $result['assignedBy'];
                }*/
                $userIds = array_unique($userIds);
            }
        }else{
            //_p($inputArray);die;
            $result = $this->salseOpsModel->getRequestDetails($inputArray['requestId']);
            $userIds[] = $result[0]['requestedBy'];
            $result = $this->salseOpsModel->getSecondLastRowForRequestTaskId($inputArray['requestTaskId']);
            //_p($result);die;
            if(count($result) > 0){
                $result =$result[0];                
                $userIds[] = $result['assignee'];
                if($result['actionTakenBy']){
                    $userIds[] = $result['actionTakenBy'];
                }
                $userIds = array_unique($userIds);
            }
        }

        $userIds = array_diff($userIds, array($inputArray['userId'],$inputArray['currentAssignee']));
        $result = $this->getUserDetails($userIds);
        foreach ($result as $key => $value) {
            $userDetails[] = array($key,$value['userName']);
        }
        return $userDetails;
    }

    public function prepareDataForSelectedStatusForContentOpsTeam($inputArray){
        //_p($inputArray);die;
        if($inputArray['userType'] == 'user'){
            if($inputArray['selectedStatus'] == 'reassign'){
                $currentStatusArray = array('inProgress','completePartialDoneAndReviewed');
                if(in_array($inputArray['currentStatus'], $currentStatusArray)){
                    $supervisorId = $this->getSupervisorId($inputArray['userId']);
                    $result = $this->salseOpsModel->getAddedUserIds($supervisorId,'userId',true);
                    $notAllowUserIds = array($inputArray['userId']);
                }
            }
        }else if($inputArray['userType'] == 'manager'){
            if($inputArray['selectedStatus'] == 'reassign'){
                $currentStatusArray = array('inProgress','markedDone','completePartialDoneAndReviewed');
                if(in_array($inputArray['currentStatus'], $currentStatusArray)){
                    if($inputArray['site'] == 'Domestic'){
                        $groupIds = array(10,11,14);
                        $result = $this->salseOpsModel->getAddedUserIds($groupIds,'groupId',true);
                    }else if($inputArray['site'] == 'Study Abroad'){
                        $groupIds = array(12,13,14);
                        $result = $this->salseOpsModel->getAddedUserIds($groupIds,'groupId',true);
                    }
                    $currentAssignee = $this->salseOpsModel->getTaskCategoryCurrentAssignee($inputArray['requestTaskId']);
                    $currentAssignee = $currentAssignee[0]['assignee'];
                    $notAllowUserIds = array($currentAssignee,$inputArray['userId']);
                }
            }            
        }else if($inputArray['userType'] == 'lead'){
            if($inputArray['selectedStatus'] == 'reassign'){
                if($inputArray['currentStatus'] == 'markedDone'){
                    if($inputArray['site'] == 'Domestic'){
                        $groupIds = array(10,11,14);
                        $result = $this->salseOpsModel->getAddedUserIds($groupIds,'groupId',true);
                    }else if($inputArray['site'] == 'Study Abroad'){
                        $groupIds = array(12,13,14);
                        $result = $this->salseOpsModel->getAddedUserIds($groupIds,'groupId',true);
                    }
                    $currentAssignee = $this->salseOpsModel->getTaskCategoryCurrentAssignee($inputArray['requestTaskId']);
                    $currentAssignee = $currentAssignee[0]['assignee'];
                    $notAllowUserIds = array($currentAssignee,$inputArray['userId']);
                }else if(in_array($inputArray['currentStatus'], array('inProgress','completePartialDoneAndReviewed'))){
                    $result = $this->salseOpsModel->getAddedUserIds($inputArray['userId'],'userId',true);
                    $currentAssignee = $this->salseOpsModel->getTaskCategoryCurrentAssignee($inputArray['requestTaskId']);                    
                    $currentAssignee = $currentAssignee[0]['assignee'];
                    $notAllowUserIds = array($currentAssignee,$inputArray['userId']);
                }
            }            
        }
        return $this->getUserDetailsIdToNamewise($result,$notAllowUserIds);
    }

    public function prepareDataForSelectedStatusForSalesTeam($inputArray){
        //_p($inputArray);die;        
        // pushed back always to requestedBy and assigned by and done by
        if($inputArray['selectedStatus'] == 'reassign'){
            if($inputArray['currentStatus']== 'doneAndReviewed'){
                if($inputArray['userType'] != 'user'){
                    if($inputArray['userType'] == 'manager'){
                        $result = $this->salseOpsModel->getAddedUserIds($inputArray['teamGroupIds']['salesTeam'],'groupId',true);
                    }else if($inputArray['userType'] == 'lead'){
                        $result = $this->salseOpsModel->getAddedUserIds($inputArray['userId'],'userId',true);
                    }
                    /*$currentAssignee = $this->salseOpsModel->getLastRowForRequestTaskId($inputArray['requestTaskId']);
                    $currentAssignee = $currentAssignee[0]['assignee'];*/
                    $notAllowUserIds = array($inputArray['currentAssignee'],$inputArray['userId']);
                    return $this->getUserDetailsIdToNamewise($result,$notAllowUserIds);
                }
            }
                
        }
    }

    public function prepareDataForSelectedStatusForSalesOpsTeam($inputArray){
        //_p($inputArray);die;
        if($inputArray['userType'] == 'user'){
            if($inputArray['selectedStatus'] == 'reassign'){
                $currentStatusArray = array('inProgress');
                if(in_array($inputArray['currentStatus'], $currentStatusArray)){
                    $supervisorId = $this->getSupervisorId($inputArray['userId']);
                    $result = $this->salseOpsModel->getAddedUserIds($supervisorId,'userId',true);
                    $notAllowUserIds = array($inputArray['userId']);
                }
            }
        }else if($inputArray['userType'] == 'manager'){
            if($inputArray['selectedStatus'] == 'reassign'){
                $currentStatusArray = array('inProgress','markedDone');
                if(in_array($inputArray['currentStatus'], $currentStatusArray)){
                    $result = $this->salseOpsModel->getAddedUserIds($inputArray['viewType'],'groupId',true);
                    $currentAssignee = $this->salseOpsModel->getTaskCategoryCurrentAssignee($inputArray['requestTaskId']);                    
                    $currentAssignee = $currentAssignee[0]['assignee'];
                    $notAllowUserIds = array($currentAssignee,$inputArray['userId']);
                }
            }            
        }else if($inputArray['userType'] == 'lead'){
            if($inputArray['selectedStatus'] == 'reassign'){
                if($inputArray['currentStatus'] == 'markedDone'){
                    $groupIds = $inputArray['teamGroupIds']['salesOps'];
                    $result = $this->salseOpsModel->getAddedUserIds($groupIds,'groupId',true);
                    $currentAssignee = $this->salseOpsModel->getTaskCategoryCurrentAssignee($inputArray['requestTaskId']);                    
                    $currentAssignee = $currentAssignee[0]['assignee'];
                    $notAllowUserIds = array($currentAssignee,$inputArray['userId']);
                }else if($inputArray['currentStatus'] == 'inProgress'){
                    $result = $this->salseOpsModel->getAddedUserIds($inputArray['userId'],'userId',true);
                    $currentAssignee = $this->salseOpsModel->getTaskCategoryCurrentAssignee($inputArray['requestTaskId']);                    
                    $currentAssignee = $currentAssignee[0]['assignee'];
                    $notAllowUserIds = array($currentAssignee,$inputArray['userId']);
                }
            }            
        }
        return $this->getUserDetailsIdToNamewise($result,$notAllowUserIds);        
    }

    public function prepareDataForSelectedStatusForDesignTeam($inputArray){
        //_p($inputArray);die;
        if($inputArray['userType'] == 'user'){
            if($inputArray['selectedStatus'] == 'reassign'){
                $currentStatusArray = array('inProgress','clientApprovedAndCreateHTML');
                if(in_array($inputArray['currentStatus'], $currentStatusArray)){
                    $supervisorId = $this->getSupervisorId($inputArray['userId']);
                    $result = $this->salseOpsModel->getAddedUserIds($supervisorId,'userId',true);
                    $notAllowUserIds = array($inputArray['userId']);
                }
            }
        }else if($inputArray['userType'] == 'manager'){
            if($inputArray['selectedStatus'] == 'reassign'){
                $currentStatusArray = array('inProgress','markedDone','clientApprovedAndCreateHTML');
                if(in_array($inputArray['currentStatus'], $currentStatusArray)){
                    $result = $this->salseOpsModel->getAddedUserIds($inputArray['viewType'],'groupId',true);
                    $currentAssignee = $this->salseOpsModel->getTaskCategoryCurrentAssignee($inputArray['requestTaskId']);                    
                    $currentAssignee = $currentAssignee[0]['assignee'];
                    $notAllowUserIds = array($currentAssignee,$inputArray['userId']);
                }
            }            
        }else if($inputArray['userType'] == 'lead'){
            if($inputArray['selectedStatus'] == 'reassign'){
                if($inputArray['currentStatus'] == 'markedDone'){
                    $groupIds = $inputArray['teamGroupIds']['design'];
                    $result = $this->salseOpsModel->getAddedUserIds($groupIds,'groupId',true);
                    $currentAssignee = $this->salseOpsModel->getTaskCategoryCurrentAssignee($inputArray['requestTaskId']);                    
                    $currentAssignee = $currentAssignee[0]['assignee'];
                    $notAllowUserIds = array($currentAssignee,$inputArray['userId']);
                }else if($inputArray['currentStatus'] == 'inProgress' || $inputArray['currentStatus'] == 'clientApprovedAndCreateHTML'){
                    $result = $this->salseOpsModel->getAddedUserIds($inputArray['userId'],'userId',true);
                    $currentAssignee = $this->salseOpsModel->getTaskCategoryCurrentAssignee($inputArray['requestTaskId']);                    
                    $currentAssignee = $currentAssignee[0]['assignee'];
                    $notAllowUserIds = array($currentAssignee,$inputArray['userId']);
                }
            }            
        }
        return $this->getUserDetailsIdToNamewise($result,$notAllowUserIds);
    }

    public function getUserDetailsIdToNamewise($result,$notAllowUserIds){
        if($result){
            foreach ($result as $key => $value) {
                if(!in_array($value['userId'], $notAllowUserIds)){
                    $userIds[] = $value['userId'];    
                }                            
            }
            if(count($userIds) > 0){
                $userDetails = $this->getUserDetails($userIds);
                foreach ($userDetails as $key => $value) {
                    $userArray[] = array($key, ucFirst($value['userName']));
                }                            
                return $userArray;    
            }else{
                return array();
            }
        }else{
            return array();
        }
    }

    public function getSupervisorId($userId){
        $supervisorId = $this->salseOpsModel->getSupervisorId($userId);
        return $supervisorId;
    }

    public function getUserDetails($userIds){
        $result = $this->salseOpsModel->getUserDetails($userIds,'userId');
        //echo'ds';_p($result);die;
        foreach ($result as $key => $value) {
            $userDetails[$value['userId']] = array(
                'email' => $value['email'],
                'userName' => ucFirst($value['firstname']).' '.ucFirst($value['lastname'])
                );
        }       
        return $userDetails;
    }

    public function insertPushBackDetails($inputArray){
        $id = $this->salseOpsModel->insertPushBackDetails($inputArray);
        return $id;
    }
    //-------------------------------------------------------------------------------
    //-------------------------------------------------------------------------------
    private function _updateOrInsertRowInTATMatchingDetails($inputArray){
        //_p($inputArray);die;
        $inputArray['TATExpired'] = '0';
        if((strtotime(date('Y-m-d'))-strtotime($inputArray['TATDate'])) > 0){
            $inputArray['TATExpired'] = '1';
        }
        unset($inputArray['TATDate']);
        $result = $this->salseOpsModel->checkIfUserIdForTaskIsAlreadyExist($inputArray);
        if($result){ // update entry
            $id = $result[0]['id'];
            $dataForUpdation['TATExpired'] = $inputArray['TATExpired'];
            $this->salseOpsModel->updateRowForUserInTATMatchingDetails($id,$dataForUpdation);
        }else{ // make new entry
            $this->salseOpsModel->insertNewRowForUserInTATMatchingDetails($inputArray);
        }
        return $id;
    }

    public function savePushedBackStatus($inputArray){
        //_p($inputArray);die;
        // Update spliceRequests Table
        if($inputArray['team'] == 'Sales'){
            $data['changeType'] = $inputArray['changeType'];
            $inputArray['commentData'] .= '<b>Change Type : </b>'.$inputArray['changeTypeForMailer'][$inputArray['changeType']];
            if($inputArray['requestedBy']){
                $data['taskRequestedBy'] = $inputArray['requestedBy'];
                $inputArray['commentData'] .= '<br><b>Requested By : </b>'.$inputArray['requestedBy'];
            }else{
                $data['taskRequestedBy'] = '';
            }
            //_p($data);die;
            $this->salseOpsModel->updateOrInsertRowInTaskActionTable($data,$inputArray['requestTaskId']);
        }else{
            $dataForTATExpiredStatus = array(
                'requestId' => $inputArray['requestId'],
                'requestTaskId' => $inputArray['requestTaskId'],
                'assignedUserId' => $inputArray['currentAssignee'],
                'TATDate' => $inputArray['TATDate'],
            );
            $this->_updateOrInsertRowInTATMatchingDetails($dataForTATExpiredStatus);
            unset($dataForTATExpiredStatus);
        }
        $todayDate = date('Y-m-d H:i:s');
        $data = array(
            'status' => $inputArray['currentStatus'],
            'requestId' => $inputArray['requestId'],
            'requestTaskId' => $inputArray['requestTaskId'],
            'currentDate' => $todayDate
            );
        $this->updateRequestMainTable($data);

        // Update spliceRequestTasks Table                        
        $data = array(
            'currentStatus' => $inputArray['selectedStatus'],
            'requestTaskId' => $inputArray['requestTaskId'],
            'assignee' => $inputArray['selectedUserId'],
            'lastUpdatedOn' => $todayDate
            ); 
        $this->updateRequestTaskTable($data);

        // get last row
        $id =  $this->updateOtherStatusForTask($inputArray['requestTaskId'],$inputArray['selectedStatus']);        
        //insert new row to spliceTasksPushedBackAndCommentsDetails table
        $data = array(
            'taskActionDetailId' => $id,
            'assignee' => $inputArray['selectedUserId'],
            'status' => $inputArray['selectedStatus'],
            'updatedDate' => $todayDate,
            'commentDetails' => $inputArray['commentData'],
            'attachmentURL' => $inputArray['attachmentURL'],
            'assignedBy' => $inputArray['currentAssignee']
            );
        if($inputArray['currentAssignee'] != $inputArray['userId']){
            $data['actionTakenBy'] = $inputArray['userId'];
        }
        //_p($data);die;
        if(empty($data['assignee']) || $data['assignee']<=0){
            mail("praveen.singhal@99acres.com", "Assignee null in Splice Lib", print_r($_POST,true));
        }
        $taskActionDetailId =  $this->salseOpsModel->insertNewRowTocommentDetails($data);

        // Add New Entry and update previous entry to spliceTasksActionDetails Table
        // Get Previous row to update level 
        $resultData = $this->salseOpsModel->getTaskActionDetailCurrentLevelAndAssignedBy($inputArray['requestTaskId']);
        $level = $resultData[0]['level'];
        if($taskActionDetailId){
            $status = array(
                'status' =>1,
                'assignedUserId' => $inputArray['selectedUserId']
                );
        }else{
            $status['status'] =0;
        }
        return $status;
    }

    public function saveReassignStatus($inputArray){
        //_p($inputArray);die;
        // Update spliceRequests Table
        $todayDate = date('Y-m-d H:i:s');
        $data = array(
            'status' => $inputArray['currentStatus'],
            'requestId' => $inputArray['requestId'],
            'requestTaskId' => $inputArray['requestTaskId'],
            'currentDate' => $todayDate
            );

        if($inputArray['team'] == 'Sales'){
            $result = $this->salseOpsModel->getReassignedUserIdsForTask($inputArray['requestId']);
            $reassignedUserIds = $result[0]['reassignedUserIds'];
            //_p($reassignedUserIds);die;
            if($reassignedUserIds){                                
                $reassignedUserIds = $reassignedUserIds.','.$inputArray['selectedUserId'];
            }else{
                $reassignedUserIds = $inputArray['selectedUserId'];
            }
            $data['reassignedUserIds'] = $reassignedUserIds;
        }else{
            $dataForTATExpiredStatus = array(
                'requestId' => $inputArray['requestId'],
                'requestTaskId' => $inputArray['requestTaskId'],
                'assignedUserId' => $inputArray['currentAssignee'],
                'TATDate' => $inputArray['TATDate'],
            );
            $this->_updateOrInsertRowInTATMatchingDetails($dataForTATExpiredStatus);
            unset($dataForTATExpiredStatus);
        }
        //_p($data);die;
        $this->updateRequestMainTable($data);

        // Update spliceRequestTasks Table                    
        $data = array(
            'currentStatus' => $inputArray['currentStatus'],
            'requestTaskId' => $inputArray['requestTaskId'],
            'assignee' => $inputArray['selectedUserId'],
            'lastUpdatedOn' => $todayDate
            ); 
        $this->updateRequestTaskTable($data);

        
        // Add New Entry and update previous entry to spliceTasksActionDetails Table
        // Get Previous row to update level 
        $resultData = $this->salseOpsModel->getTaskActionDetailCurrentLevelAndAssignedBy($inputArray['requestTaskId']);
        $level = $resultData[0]['level'];
        //_p($level);                        
        $data = array(
            'status' => $inputArray['selectedStatus'],
            'updatedDate' => $todayDate,
            'commentDetails' => $inputArray['commentData'],
            'attachmentURL' => $inputArray['attachmentURL'],
        );
        if($inputArray['currentAssignee'] != $inputArray['userId']){
            $data['actionTakenBy'] = $inputArray['userId'];
        }
        //_p($data);                        
        $this->salseOpsModel->updateLastRowOfTasksActionDetails($data,$level,$inputArray['requestTaskId']);

        //insert a new row
        $data = array(
            'requestTaskId' => $inputArray['requestTaskId'],
            'assignee' => $inputArray['selectedUserId'],
            'level' => $level+1,                            
            'status' => $inputArray['currentStatus'],
            'updatedDate' => $todayDate,
            );
        if($inputArray['currentAssignee'] != $inputArray['userId']){
            $data['assignedBy'] = $inputArray['userId'];
        }else{
            $data['assignedBy'] = $resultData[0]['assignee'];
        }
        $taskActionDetailId = $this->salseOpsModel->insertNewRowToTaskUpdateDetailsTable($data);
        if($taskActionDetailId){
            $status = array(
                'status' =>1,
                'assignedUserId' => $inputArray['selectedUserId']
                );
        }else{
            $status['status'] =0;
        }
        return $status;
    }

    public function checkIfRequestHasCampaignActivationTask($requestId){
        $result = $this->salseOpsModel->getAllTaskForGivenRequestId($requestId, $status = "live");
        //_p($result);die;
        $taskId = '';
        foreach ($result as $key => $value) {
            if($value['taskCategory'] == 'Campaign Activation'){
                $statusArray = array('cancel','clientApprovedAndClosed');
                if(!in_array($value['currentStatus'], $statusArray)){
                    $taskId = $value['id'];
                    break;
                }
            }
        }
        return $taskId;
    }

    public function saveForwardedStatus($inputArray){
        //_p($inputArray);die;
        if($inputArray['team'] != 'Sales'){
            $dataForTATExpiredStatus = array(
                'requestId' => $inputArray['requestId'],
                'requestTaskId' => $inputArray['requestTaskId'],
                'assignedUserId' => $inputArray['currentAssignee'],
                'TATDate' => $inputArray['TATDate'],
            );
            $this->_updateOrInsertRowInTATMatchingDetails($dataForTATExpiredStatus);
            unset($dataForTATExpiredStatus);
        }
        $resultData = $this->salseOpsModel->getTaskActionDetailCurrentLevelAndAssignedBy($inputArray['requestTaskId']);
        $resultData = $resultData[0];
        $level = $resultData['level'];
        //_p($resultData);die;

        // Update spliceRequests Table
        $todayDate = date('Y-m-d H:i:s');
        $data = array(
            'status' => $resultData['status'],
            'requestId' => $inputArray['requestId'],
            'requestTaskId' => $inputArray['requestTaskId'],
            'currentDate' => $todayDate
        );
        $isCampaignActivationTATChanged = false;
        if($inputArray['team'] == 'Sales'){
            $currentTATForRequest = $this->salseOpsModel->getCurrentTATForRequest($inputArray['requestId']);
            $currentTATForRequest = $currentTATForRequest[0]['TATDate'];
            $noOfDaysForTAT = $this->_reCalculateTAT($inputArray);
            $newTAT = $this->_calculateTATForTask($noOfDaysForTAT);
            $newTAT = $newTAT." 23:59:59";
            $taskId = '';
            if($inputArray['taskCategory'] != 'Campaign Activation'){
                $taskId = $this->checkIfRequestHasCampaignActivationTask($inputArray['requestId']);
            }
            if($taskId && $taskId !=''){
                $newTATForRequest = $this->_calculateTATForTask(1,$newTAT);
                $newTATForRequest.=" 23:59:59";
            }else{
                $newTATForRequest = $newTAT;
            }
            if((strtotime($currentTATForRequest) - strtotime($newTATForRequest))<0){
                $requestNewTAT = array(
                    'requestId' => $inputArray['requestId'],
                    'newTAT' => $newTATForRequest
                    );
                $data['TATDate'] = $newTATForRequest;
                if($taskId && $taskId !=''){
                    $isCampaignActivationTATChanged = true;
                }
            }
        }
        $this->updateRequestMainTable($data);
        if($isCampaignActivationTATChanged == true){
            // update campaign activation task TAT
            $data = array();
            $data['requestTaskId'] = $taskId;
            $data['TATDate'] = $newTATForRequest;
            //_p($data);die;
            $this->updateRequestTaskTable($data);
        }

        // Update spliceRequestTasks Table
        $result = $this->salseOpsModel->getLastRowForRequestTaskId($inputArray['requestTaskId']);
        $lastRowId = $result[0]['id'];
        $assignee = $result[0]['assignee'];
        //_p($assignee);die;
        $data = array(
            'currentStatus' => $resultData['status'],
            'requestTaskId' => $inputArray['requestTaskId'],
            'assignee' => $assignee,
            'lastUpdatedOn' => $todayDate
            );
        if(in_array($inputArray['groupId'],$inputArray['teamGroupIds']['salesTeam'])){
            if((strtotime($inputArray['TATDate']) - strtotime($newTAT))<0){
                $data['TATDate'] = $newTAT;
            }
        }
        $this->updateRequestTaskTable($data);

        // Get Current Assignee
        $data = array(
            'taskActionDetailId' => $lastRowId,
            'assignee' => $assignee,
            'status' => $inputArray['selectedStatus'],
            'updatedDate' => $todayDate,
            'commentDetails' => $inputArray['commentData'],
            'attachmentURL' => $inputArray['attachmentURL'],
            'assignedBy' => $inputArray['currentAssignee']
            );
        if($inputArray['currentAssignee'] != $inputArray['userId']){
            $data['actionTakenBy'] = $inputArray['userId'];
        }
        //_p($data);die;
        $taskActionDetailId =  $this->salseOpsModel->insertNewRowTocommentDetails($data);
        if($taskActionDetailId){
            $status = array(
                'status' =>1,
                'assignedUserId' => $assignee
                );
            if($inputArray['team'] == 'Sales'){
                if($requestNewTAT){
                    $status['newTATForRequest'] = $requestNewTAT;
                }
                $status['newTATForTask'] = array(
                    'taskId' => $inputArray['requestTaskId'],
                    'newTAT' => $newTAT
                );
            }
                
            if($isCampaignActivationTATChanged == true){
                $status['newTATForCampaignActivation'] = array(
                    'taskId' => $taskId,
                    'newTAT' => $newTATForRequest
                );
            }

        }else{
            $status['status'] =0;
        }
        return $status;
    }

    public function saveUpdatedStatus($inputArray){
        //_p($inputArray);die;
        switch ($inputArray['selectedStatus']) {
            case 'commentAdded':
                $status = $this->saveCommentStatus($inputArray);
                break;

            case 'pushedBack':
                $status = $this->savePushedBackStatus($inputArray);
                break;

            case 'reassign':
                $status = $this->saveReassignStatus($inputArray);
                break;

            case 'forwarded':
                $status = $this->saveForwardedStatus($inputArray);
                break;
            
            default:
                $teamGroupIds = $inputArray['teamGroupIds'];
                if(in_array($inputArray['groupId'],$teamGroupIds['salesTeam'])){
                    $status = $this->saveUpdatedStatusForSalesTeam($inputArray);
                }else{
                    $otherTeamGroupIds = array_merge($teamGroupIds['design'],$teamGroupIds['salesOps'],$teamGroupIds['contentOps']);
                    if(in_array($inputArray['groupId'],$otherTeamGroupIds)){
                        $status = $this->saveUpdatedStatusForOtherTeam($inputArray);
                    }
                }
                break;
        }
        return $status;
        //_p($statusArray);die;
    }

    private function _updateOrInsertPendingForCampaignActivation($inputArray){
        //_p($inputArray);die;
        if($inputArray['taskCategory'] != 'Mailer'){
            if($inputArray['taskCategory'] == 'Campaign Activation'){
                $result = $this->salseOpsModel->checkIfTaskIdIsAlreadyExist($inputArray['requestTaskId']);
                if($result){
                    $this->salseOpsModel->updateIsPendingForCampaignActivation($inputArray['requestTaskId']);
                }
            }else{
                if($inputArray['hasCampaignActivationTask'] == 1){
                    $result = $this->salseOpsModel->getAllTaskForGivenRequestId($inputArray['requestId']);
                    $isCampaignActivationTaskClosed = false;
                    $closedTaskCount = 0;
                    $noOfTask = 0;
                    foreach ($result as $key => $value) {
                        if($value['taskCategory'] == 'Campaign Activation'){
                            $campaignTaskId = $value['id'];
                            if($value['currentStatus'] == 'cancel' || $value['currentStatus'] == 'clientApprovedAndClosed'){
                                $isCampaignActivationTaskClosed = true;    
                            }
                        }else{
                            $noOfTask ++;
                            if($value['currentStatus'] == 'cancel' || $value['currentStatus'] == 'clientApprovedAndClosed'){
                                $closedTaskCount++;
                            }
                        }
                    }

                    if($isCampaignActivationTaskClosed == false){
                        if($closedTaskCount == $noOfTask){
                            $inputData = array(
                                'requestId' => $inputArray['requestId'],
                                'requestTaskId' => $campaignTaskId
                                );
                            $this->salseOpsModel->insertOrUpdatePendingCampaignRequest($inputData);
                        }
                    }
                }
            }
        }
    }

    public function updateRequestMainTable($inputArray){
        $status = array('clientApprovedAndClosed','cancel');
        $requestId = $inputArray['requestId'];
        if($inputArray['TATDate']){
            $inputData['TATDate'] = $inputArray['TATDate'];
        }
        if($status && in_array($inputArray['status'], $status)){
            $result = $this->salseOpsModel->getTaskDetails($inputArray['requestId']);            
            $flag = 0;
            foreach ($result as $key => $value) {
                if($value['id'] != $inputArray['requestTaskId']){
                    if(!in_array($value['currentStatus'], $status)){
                        $flag = 1;
                    }
                }
            }            
            if($flag == 0){
                foreach ($result as $key => $value) {
                    if($value['id'] != $inputArray['requestTaskId']){
                        $diffStatus[$value['currentStatus']] ++;
                    }
                }
                $diffStatus[$inputArray['status']] ++;
                if(count($diffStatus['clientApprovedAndClosed']) > 0){
                    $inputData['status'] = 'clientApprovedAndClosed';
                }else{
                    $inputData['status'] = 'cancel';
                }
            }
        }
        if($inputArray['reassignedUserIds']){
            $inputData['reassignedUserIds'] = $inputArray['reassignedUserIds'];
        }
        $inputData['lastUpdatedOn'] = $inputArray['currentDate'];
        $this->salseOpsModel->updateSpliceRequestTable($inputData,$requestId);
        return $userArray;
    }

    public function updateRequestTaskTable($inputArray){
        $requestTaskId = $inputArray['requestTaskId'];
        unset($inputArray['requestTaskId']);
        $this->salseOpsModel->updateRequestTaskTable($inputArray,$requestTaskId);    
    }

    private function _getPreviousLevelOfTask($requestTaskId,$currentAssignee,$assignedBy){
        $level = $this->salseOpsModel->getTaskActionDetailCurrentLevel($requestTaskId);
        if($currentAssignee == $assignedBy){
            $level = $level+1;
        }        
        return $level;
    }

    public function saveCommentStatus($inputArray){
        //_p($inputArray);die;
        $todayDate = date('Y-m-d H:i:s');
        $data = array(
            'requestTaskId' => $inputArray['requestTaskId'],                
            'lastUpdatedOn' => $todayDate
            ); 
        $this->updateRequestTaskTable($data);
        // get last row
        $id =  $this->updateOtherStatusForTask($inputArray['requestTaskId'],$inputArray['selectedStatus']);
        //_p($id);die;

        //insert new row to spliceTasksPushedBackAndCommentsDetails table
        $data = array(
            'taskActionDetailId' => $id,
            'assignee' => $inputArray['userId'],
            'status' => $inputArray['selectedStatus'],
            'updatedDate' => $todayDate,
            'commentDetails' => $inputArray['commentData'],
            'attachmentURL' => $inputArray['attachmentURL'],
            'assignedBy' => $inputArray['userId']
            );
        //_p($data);die;
        $taskActionDetailId =  $this->salseOpsModel->insertNewRowTocommentDetails($data);                    

        // Update spliceRequests Table                    
        $data = array(
            'requestId' => $inputArray['requestId'],
            'requestTaskId' => $inputArray['requestTaskId'],
            'currentDate' => $todayDate
            );
        $this->updateRequestMainTable($data);
        if($taskActionDetailId){
            $status['status'] =1;
        }else{
            $status['status'] =0;
        }
        return $status;
    }

    public function updateOtherStatusForTask($requestTaskId,$status){
        $result = $this->salseOpsModel->getPreviousOtherStatusForTask($requestTaskId);
        $previousStatus = $result[0]['isOtherActionTaken'];
        $id = $result[0]['id'];
        //_p($requestTaskId);_p($status);_p($result);die;
        if($previousStatus == 'no'){
            $data['isOtherActionTaken'] = $status;
            $this->salseOpsModel->updateOtherActionTakenField($id,$data);
        }else if($previousStatus != $status){
            $data['isOtherActionTaken'] = 'both';
            $this->salseOpsModel->updateOtherActionTakenField($id,$data);
        }
        return $id;      
    }

    public function saveUpdatedStatusForSalesTeam($inputArray){
        //_p($inputArray);die;
        if($inputArray['selectedStatus'] == 'cancel'){
            $status = $this->saveCancelStatus($inputArray);
        }else if($inputArray['selectedStatus'] == 'clientApprovedAndClosed'){
            $status = $this->saveClientApprovedAndClosed($inputArray);
        }else if($inputArray['selectedStatus'] == 'clientApprovedAndCreateHTML'){
            $status = $this->saveClientApprovedAndCreateHTML($inputArray);            
        }else if($inputArray['selectedStatus'] == 'completePartialDoneAndReviewed'){
            $status = $this->saveCompletePartialDoneAndReviewedStatus($inputArray);            
        }
        return $status;
    }

    public function getRequestTyeAndChangeType($requestTaskId){
        $result = $this->salseOpsModel->getTaskData($requestTaskId);
        //_p($result);die;
        $taskDetailsForCalculatingTAT['taskType'] = $result[0]['taskType'];
        $taskDetailsForCalculatingTAT['changeType'] = $result[0]['changeType'];
        $taskDetailsForCalculatingTAT['site'] = $result[0]['site'];
        return $taskDetailsForCalculatingTAT;
    }

    private function _reCalculateTAT($inputArray){
        //_p($inputArray);die;
        if($inputArray['selectedStatus'] == "completePartialDoneAndReviewed"){
            if($inputArray['taskCategory'] == 'Listing'){
                $site = ($inputArray['taskDetailsForCalculatingTAT']['site'] == 'Domestic')?'domestic':'studyAbroad';
                $noOfDaysForTAT = $inputArray['defaultTAT']['listingRequest'][$site]['Complete Partial Done & Reviewed'];  
            }
        }else{
            if($inputArray['taskCategory'] == 'Mailer'){
                $noOfDaysForTAT = $inputArray['defaultTAT']['mailerRequest']['edit'];          
            }else if($inputArray['taskCategory'] == 'Listing'){
                $site = ($inputArray['taskDetailsForCalculatingTAT']['site'] == 'Domestic')?'domestic':'studyAbroad';
                if($inputArray['taskDetailsForCalculatingTAT']['changeType'] != ''){
                    $noOfDaysForTAT = $inputArray['defaultTAT']['listingRequest'][$site][$inputArray['taskDetailsForCalculatingTAT']['taskType']][$inputArray['taskDetailsForCalculatingTAT']['changeType']];
                }else{
                    $noOfDaysForTAT = $inputArray['defaultTAT']['listingRequest'][$site][$inputArray['taskDetailsForCalculatingTAT']['taskType']];
                }
            }else if($inputArray['taskCategory'] == 'Banner'){
                $noOfDaysForTAT = $inputArray['defaultTAT']['bannerRequest'][$inputArray['taskDetailsForCalculatingTAT']['taskType']];
            }else if($inputArray['taskCategory'] == 'Shoshkele'){
                $noOfDaysForTAT = $inputArray['defaultTAT']['shoshkeleRequest'][$inputArray['taskDetailsForCalculatingTAT']['taskType']];
            }else if($inputArray['taskCategory'] == 'Campaign Activation'){
                    $noOfDaysForTAT = $inputArray['defaultTAT']['campaignActivationRequest'];
            }
        }
        //_p($noOfDaysForTAT);die;
        return $noOfDaysForTAT;
    }

    private function _getAssignedToUserIdForClientApprovedAndCreateHTML($inputArray, $status = "doneAndReviewed"){
        //_p($inputArray);die;
        $result = $this->salseOpsModel->getPreviousOtherStatusForTask($inputArray['requestTaskId']);
        $isOtherActionTaken = $result[0]['isOtherActionTaken'];
        //_p($isOtherActionTaken);die;
        if($isOtherActionTaken =='pushedBack' || $isOtherActionTaken == 'both'){
            $result = $this->salseOpsModel->getLastActionTakenUserIdForForwardAction($result[0]['id']);
            $result = $result[0];
            if($result['actionTakenBy']){
                return $result['actionTakenBy'];
            }else{
                return $result['assignedBy'];
            }
        }else{
            $result = $this->salseOpsModel->getOtherTeamLastUserIdForTask($inputArray['requestTaskId'], $status);
            $result = $result[0];
            if($result['actionTakenBy']){
                return $result['actionTakenBy'];
            }else{
                return $result['assignee'];
            }
        }
    }

    
    public function saveCompletePartialDoneAndReviewedStatus($inputArray){
        //_p($inputArray);
        $noOfDaysForTAT = $this->_reCalculateTAT($inputArray);
        $newTAT = $this->_calculateTATForTask($noOfDaysForTAT);    
        $newTAT = $newTAT." 23:59:59";
        unset($inputArray['defaultTAT']);
        $assignedToUserId = $this->_getAssignedToUserIdForClientApprovedAndCreateHTML($inputArray,'partialDoneAndReviewed');

        // Update spliceRequests Table
        $todayDate = date('Y-m-d H:i:s');

        $data = array(
            'status' => $inputArray['selectedStatus'],
            'requestId' => $inputArray['requestId'],
            'requestTaskId' => $inputArray['requestTaskId'],
            'currentDate' => $todayDate,
        );

        $currentTATForRequest = $this->salseOpsModel->getCurrentTATForRequest($inputArray['requestId']);
        $currentTATForRequest = $currentTATForRequest[0]['TATDate'];

        $taskId = '';
        if($inputArray['taskCategory'] != 'Campaign Activation'){
            $taskId = $this->checkIfRequestHasCampaignActivationTask($inputArray['requestId']);
        }
        if($taskId && $taskId !=''){
            $newTATForRequest = $this->_calculateTATForTask(1,$newTAT);
            $newTATForRequest.=" 23:59:59";
            //$newTATForRequest = date('Y-m-d H:i:s',strtotime($newTAT.' +1 day'));
        }else{
            $newTATForRequest = $newTAT;
        }
        
        $isCampaignActivationTATChanged = false;
        if((strtotime($currentTATForRequest) - strtotime($newTATForRequest))<0){
            $data['TATDate'] = $newTATForRequest;
            $requestNewTAT = array(
                    'requestId' => $inputArray['requestId'],
                    'newTAT' => $newTATForRequest
                );
            if($taskId && $taskId !=''){
                $isCampaignActivationTATChanged = true;
            }
        }
        $this->updateRequestMainTable($data);

        if($isCampaignActivationTATChanged == true){
            // update campaign activation task TAT
            $data = array();
            $data['requestTaskId'] = $taskId;
            $data['TATDate'] = $newTATForRequest;
            //_p($data);die;
            $this->updateRequestTaskTable($data);
        }

        // Update spliceRequestTasks Table                        
        $data = array(
            'currentStatus' => $inputArray['selectedStatus'],
            'requestTaskId' => $inputArray['requestTaskId'],
            'assignee' => $assignedToUserId,
            'lastUpdatedOn' => $todayDate,
            'TATDate' => $newTAT
            ); 
        $this->updateRequestTaskTable($data);

        // Add New Entry and update previous entry to spliceTasksActionDetails Table
        // Get Previous row to update level 
        $resultData = $this->salseOpsModel->getTaskActionDetailCurrentLevelAndAssignedBy($inputArray['requestTaskId']);
        $level = $resultData[0]['level'];
        $data = array(
            'status' => $inputArray['selectedStatus'],
            'updatedDate' => $todayDate,
            'commentDetails' => $inputArray['commentData'],
            'attachmentURL' => $inputArray['attachmentURL'],
        );
        if($inputArray['currentAssignee'] != $inputArray['userId']){
            $data['actionTakenBy'] = $inputArray['userId'];
        }
        $this->salseOpsModel->updateLastRowOfTasksActionDetails($data,$level,$inputArray['requestTaskId']);

        //insert a new row
        $data = array(
            'requestTaskId' => $inputArray['requestTaskId'],
            'assignee' => $assignedToUserId,
            'level' => $level+1,                            
            'status' => $inputArray['selectedStatus'],
            'updatedDate' => $todayDate,
            );
        if($inputArray['currentAssignee'] != $inputArray['userId']){
            $data['assignedBy'] = $inputArray['userId'];
        }else{
            $data['assignedBy'] = $resultData[0]['assignee'];
        }
        $taskActionDetailId = $this->salseOpsModel->insertNewRowToTaskUpdateDetailsTable($data);
        if($taskActionDetailId){
            $status = array(
                'status' =>1,
                'assignedUserId' => $assignedToUserId
                );
            
            if($requestNewTAT){
                $status['newTATForRequest'] = $requestNewTAT;
            }
            $status['newTATForTask'] = array(
                    'taskId' => $inputArray['requestTaskId'],
                    'newTAT' => $newTAT
                );

            if($isCampaignActivationTATChanged == true){
                $status['newTATForCampaignActivation'] = array(
                    'taskId' => $taskId,
                    'newTAT' => $newTATForRequest
                );
            }

        }else{
            $status['status'] =0;
        }
        return $status;
    }

    public function saveClientApprovedAndCreateHTML($inputArray){
        //_p($inputArray);die;
        $noOfDaysForTAT = $this->_reCalculateTAT($inputArray);
        $newTAT = $this->_calculateTATForTask($noOfDaysForTAT);
        $newTAT = $newTAT." 23:59:59";
        //_p($newTAT);die;        
        unset($inputArray['defaultTAT']);
        $assignedToUserId = $this->_getAssignedToUserIdForClientApprovedAndCreateHTML($inputArray);
        //_p($assignedToUserId);die;
        // Update spliceRequests Table
        $todayDate = date('Y-m-d H:i:s');

        $data = array(
            'status' => $inputArray['selectedStatus'],
            'requestId' => $inputArray['requestId'],
            'requestTaskId' => $inputArray['requestTaskId'],
            'currentDate' => $todayDate,
        );

        $currentTATForRequest = $this->salseOpsModel->getCurrentTATForRequest($inputArray['requestId']);
        $currentTATForRequest = $currentTATForRequest[0]['TATDate'];

        $taskId = '';
        if($inputArray['taskCategory'] != 'Campaign Activation'){
            $taskId = $this->checkIfRequestHasCampaignActivationTask($inputArray['requestId']);
        }

        if($taskId && $taskId !=''){
            $newTATForRequest = $this->_calculateTATForTask(1,$newTAT);
            $newTATForRequest.=" 23:59:59";
        }else{
            $newTATForRequest = $newTAT;
        }
        $isCampaignActivationTATChanged = false;
        if((strtotime($currentTATForRequest) - strtotime($newTATForRequest))<0){
            $data['TATDate'] = $newTATForRequest;
            $requestNewTAT = array(
                    'requestId' => $inputArray['requestId'],
                    'newTAT' => $newTATForRequest
                );
            if($taskId && $taskId !=''){
                $isCampaignActivationTATChanged = true;
            }
        }
        $this->updateRequestMainTable($data);

        if($isCampaignActivationTATChanged == true){
            // update campaign activation task TAT
            $data = array();
            $data['requestTaskId'] = $taskId;
            $data['TATDate'] = $newTATForRequest;
            $this->updateRequestTaskTable($data);
        }

        // Update spliceRequestTasks Table                        
        $data = array(
            'currentStatus' => $inputArray['selectedStatus'],
            'requestTaskId' => $inputArray['requestTaskId'],
            'assignee' => $assignedToUserId,
            'lastUpdatedOn' => $todayDate,
            'TATDate' => $newTAT
            ); 
        $this->updateRequestTaskTable($data);

        // Add New Entry and update previous entry to spliceTasksActionDetails Table
        // Get Previous row to update level 
        $resultData = $this->salseOpsModel->getTaskActionDetailCurrentLevelAndAssignedBy($inputArray['requestTaskId']);
        $level = $resultData[0]['level'];
        $data = array(
            'status' => $inputArray['selectedStatus'],
            'updatedDate' => $todayDate,
            'commentDetails' => $inputArray['commentData'],
            'attachmentURL' => $inputArray['attachmentURL'],
        );
        if($inputArray['currentAssignee'] != $inputArray['userId']){
            $data['actionTakenBy'] = $inputArray['userId'];
        }
        $this->salseOpsModel->updateLastRowOfTasksActionDetails($data,$level,$inputArray['requestTaskId']);

        //insert a new row
        $data = array(
            'requestTaskId' => $inputArray['requestTaskId'],
            'assignee' => $assignedToUserId,
            'level' => $level+1,                            
            'status' => $inputArray['selectedStatus'],
            'updatedDate' => $todayDate,
            );
        if($inputArray['currentAssignee'] != $inputArray['userId']){
            $data['assignedBy'] = $inputArray['userId'];
        }else{
            $data['assignedBy'] = $resultData[0]['assignee'];
        }
        $taskActionDetailId = $this->salseOpsModel->insertNewRowToTaskUpdateDetailsTable($data);
        if($taskActionDetailId){
            $status = array(
                'status' =>1,
                'assignedUserId' => $assignedToUserId
                );
            
            if($requestNewTAT){
                $status['newTATForRequest'] = $requestNewTAT;
            }
            $status['newTATForTask'] = array(
                    'taskId' => $inputArray['requestTaskId'],
                    'newTAT' => $newTAT
                );

            if($isCampaignActivationTATChanged == true){
                $status['newTATForCampaignActivation'] = array(
                    'taskId' => $taskId,
                    'newTAT' => $newTATForRequest
                );
            }

        }else{
            $status['status'] =0;
        }
        return $status;
    }

    public function saveClientApprovedAndClosed($inputArray){
        //_p($inputArray);die;
        // Update spliceRequests Table
        $todayDate = date('Y-m-d H:i:s');
        $data = array(
            'status' => $inputArray['selectedStatus'],
            'requestId' => $inputArray['requestId'],
            'requestTaskId' => $inputArray['requestTaskId'],
            'currentDate' => $todayDate
            );

        $taskId = '';
        if($inputArray['taskCategory'] != 'Campaign Activation'){
            $taskId = $this->checkIfRequestHasCampaignActivationTask($inputArray['requestId']);
        }
        //_p($taskId);die;
        if($taskId && $taskId !=''){
            $inputArray['hasCampaignActivationTask'] = true;
            $TATDate = $this->salseOpsModel->getCurrentTATForTask($taskId);
            $TATDate = $TATDate[0]['TATDate'];
            $currentDate = date('Y-m-d');
            $noOfDaysForTAT = $inputArray['defaultTAT']['campaignActivationRequest'];
            $currentDate = date('Y-m-d',strtotime($currentDate.' +'.$noOfDaysForTAT.' day'));
            $currentDate = $currentDate.' 23:59:59';
            if((strtotime($TATDate) - strtotime($currentDate)) <0 ){
                // update campaign activation task TAT
                $dataForCampaignActivation = array();
                $dataForCampaignActivation['requestTaskId'] = $taskId;
                $dataForCampaignActivation['TATDate'] = $currentDate;
                //_p($data);die;
                $this->updateRequestTaskTable($dataForCampaignActivation);
                $hasCampaignActivationTask = true;
            }
            $currentTATForRequest = $this->salseOpsModel->getCurrentTATForRequest($inputArray['requestId']);
            $currentTATForRequest = $currentTATForRequest[0]['TATDate'];
            if((strtotime($currentTATForRequest) - strtotime($currentDate)) <0 ){
                // update Request TAT
                $data['TATDate'] = $currentDate;
                $requestNewTAT = array(
                    'requestId' => $inputArray['requestId'],
                    'newTAT' => $currentDate
                );
            }
            //_p($currentDate);die;
        }
        $this->updateRequestMainTable($data);
        
        // Update spliceRequestTasks Table                        
        $data = array(
            'currentStatus' => $inputArray['selectedStatus'],
            'requestTaskId' => $inputArray['requestTaskId'],
            'lastUpdatedOn' => $todayDate
            );
        $this->updateRequestTaskTable($data);
        
        // Check For Pending For Campaign Activation request
        $this->_updateOrInsertPendingForCampaignActivation($inputArray);
        // Add New Entry and update previous entry to spliceTasksActionDetails Table
        // Get Previous row to update level 
        $resultData = $this->salseOpsModel->getTaskActionDetailCurrentLevelAndAssignedBy($inputArray['requestTaskId']);
        $level = $resultData[0]['level'];
        $data = array(
            'status' => $inputArray['selectedStatus'],
            'updatedDate' => $todayDate,
            'commentDetails' => $inputArray['commentData'],
            'attachmentURL' => $inputArray['attachmentURL'],
        );
        if($inputArray['currentAssignee'] != $inputArray['userId']){
            $data['actionTakenBy'] = $inputArray['userId'];
        }
        $taskActionDetailId =$this->salseOpsModel->updateLastRowOfTasksActionDetails($data,$level,$inputArray['requestTaskId']);
        if($taskActionDetailId){
            $status = array(
                'status' =>1,
                'assignedUserId' => $inputArray['userId']
                );
            
            if($requestNewTAT){
                $status['newTATForRequest'] = $requestNewTAT;
            }

            if($isCampaignActivationTATChanged == true){
                $status['newTATForCampaignActivation'] = array(
                    'taskId' => $taskId,
                    'newTAT' => $currentDate
                );
            }

        }else{
            $status['status'] =0;
        }
        return $status;
    }

    public function saveUpdatedStatusForOtherTeam($inputArray){
        //_p($inputArray);die;        
        if($inputArray['selectedStatus'] == 'doneAndReviewed'){
            $status = $this->saveDoneAndReviewedStatus($inputArray);
        }else if($inputArray['selectedStatus'] == 'markedDone'){
            $status = $this->saveMarkedDoneStatus($inputArray);
        }else if($inputArray['selectedStatus'] == 'partialDoneAndReviewed'){
            $status = $this->savePartialDoneAndReviewedStatus($inputArray);
        }
        return $status;
    }

    public function saveCancelStatus($inputArray){
        //_p($inputArray);die;
        // Update spliceRequests Table
        $todayDate = date('Y-m-d H:i:s');
        $data = array(
            'status' => $inputArray['selectedStatus'],
            'requestId' => $inputArray['requestId'],
            'requestTaskId' => $inputArray['requestTaskId'],
            'currentDate' => $todayDate
        );
        $taskId = '';
        if($inputArray['taskCategory'] != 'Campaign Activation'){
            $taskId = $this->checkIfRequestHasCampaignActivationTask($inputArray['requestId']);
        }
        if($taskId && $taskId !=''){
            $inputArray['hasCampaignActivationTask'] = true;
            $TATDate = $this->salseOpsModel->getCurrentTATForTask($taskId);
            $TATDate = $TATDate[0]['TATDate'];
            $currentDate = date('Y-m-d');
            $noOfDaysForTAT = $inputArray['defaultTAT']['campaignActivationRequest'];
            $currentDate = date('Y-m-d',strtotime($currentDate.' +'.$noOfDaysForTAT.' day'));
            $currentDate = $currentDate.' 23:59:59';
            if((strtotime($TATDate) - strtotime($currentDate)) <0 ){
                // update campaign activation task TAT
                $dataForCampaignActivation = array();
                $datadataForCampaignActivation['requestTaskId'] = $taskId;
                $dataForCampaignActivation['TATDate'] = $currentDate;
                //_p($data);die;
                $this->updateRequestTaskTable($dataForCampaignActivation);
                $hasCampaignActivationTask = true;
            }
            $currentTATForRequest = $this->salseOpsModel->getCurrentTATForRequest($inputArray['requestId']);
            $currentTATForRequest = $currentTATForRequest[0]['TATDate'];
            if((strtotime($currentTATForRequest) - strtotime($currentDate)) <0 ){
                // update Request TAT
                $data['TATDate'] = $currentDate;
                $requestNewTAT = array(
                    'requestId' => $inputArray['requestId'],
                    'newTAT' => $currentDate
                );
            }
            //_p($currentDate);die;
        }
        $this->updateRequestMainTable($data);
        
        // Update spliceRequestTasks Table                        
        $data = array(
            'currentStatus' => $inputArray['selectedStatus'],
            'requestTaskId' => $inputArray['requestTaskId'],
            'lastUpdatedOn' => $todayDate
            );
        $this->updateRequestTaskTable($data);
        

        // Check For Pending For Campaign Activation request
        $this->_updateOrInsertPendingForCampaignActivation($inputArray);

        // Add New Entry and update previous entry to spliceTasksActionDetails Table
        // Get Previous row to update level 
        $resultData = $this->salseOpsModel->getTaskActionDetailCurrentLevelAndAssignedBy($inputArray['requestTaskId']);
        $level = $resultData[0]['level'];
        $data = array(
            'status' => $inputArray['selectedStatus'],
            'updatedDate' => $todayDate,
            'commentDetails' => $inputArray['commentData'],
            'attachmentURL' => $inputArray['attachmentURL'],
        );
        if($inputArray['currentAssignee'] != $inputArray['userId']){
            $data['actionTakenBy'] = $inputArray['userId'];
        }
        $taskActionDetailId =$this->salseOpsModel->updateLastRowOfTasksActionDetails($data,$level,$inputArray['requestTaskId']);

        if($taskActionDetailId){
            $status = array(
                'status' =>1
                );
            
            if($requestNewTAT){
                $status['newTATForRequest'] = $requestNewTAT;
            }
                
            if($isCampaignActivationTATChanged == true){
                $status['newTATForCampaignActivation'] = array(
                    'taskId' => $taskId,
                    'newTAT' => $currentDate
                );
            }

        }else{
            $status['status'] =0;
        }
        return $status;
    }

    public function saveDoneAndReviewedStatus($inputArray){
        //_p($inputArray);die;
        if($inputArray['currentStatus'] == 'pushedBack'){
            // Get Current Assignee
            $result = $this->salseOpsModel->getLastRowForRequestTaskId($inputArray['requestTaskId']);
            $currentAssignee = $result[0]['assignee'];
        }else if($inputArray['currentStatus'] == 'markedDone'){
            $currentAssignee = $inputArray['currentAssignee'];
        }else if($inputArray['currentStatus'] == 'inProgress'){
            $currentAssignee = $inputArray['currentAssignee'];
        }else if($inputArray['currentStatus'] == 'clientApprovedAndCreateHTML'){
            $currentAssignee = $inputArray['currentAssignee'];
        }

        if($inputArray['team'] != 'Sales'){
            $dataForTATExpiredStatus = array(
                'requestId' => $inputArray['requestId'],
                'requestTaskId' => $inputArray['requestTaskId'],
                'assignedUserId' => $inputArray['currentAssignee'],
                'TATDate' => $inputArray['TATDate'],
            );
            $this->_updateOrInsertRowInTATMatchingDetails($dataForTATExpiredStatus);
            unset($dataForTATExpiredStatus);
        }

        // Update spliceRequests Table
        $todayDate = date('Y-m-d H:i:s');
        $data = array(
            'status' => $inputArray['selectedStatus'],
            'requestId' => $inputArray['requestId'],
            'requestTaskId' => $inputArray['requestTaskId'],
            'currentDate' => $todayDate
            );
        $this->updateRequestMainTable($data);

        // Update spliceRequestTasks Table
        // get request raiser id
        $result = $this->salseOpsModel->getRequestDetails($inputArray['requestId']);
        $raiserUserId = $result[0]['requestedBy'];
        
        $data = array(
            'currentStatus' => $inputArray['selectedStatus'],
            'requestTaskId' => $inputArray['requestTaskId'],
            'assignee' => $raiserUserId,
            'lastUpdatedOn' => $todayDate
            ); 
        $this->updateRequestTaskTable($data);        

        // Add New Entry and update previous entry to spliceTasksActionDetails Table
        // Get Previous row to update level 
        $resultData = $this->salseOpsModel->getTaskActionDetailCurrentLevelAndAssignedBy($inputArray['requestTaskId']);
        $level = $resultData[0]['level'];
        //_p($level);

        $data = array(
            'status' => $inputArray['selectedStatus'],
            'updatedDate' => $todayDate,
            'commentDetails' => $inputArray['commentData'],
            'attachmentURL' => $inputArray['attachmentURL'],
        );
        if($currentAssignee != $inputArray['userId']){
            $data['actionTakenBy'] = $inputArray['userId'];
        }
        //_p($data);                        
        $this->salseOpsModel->updateLastRowOfTasksActionDetails($data,$level,$inputArray['requestTaskId']);
        //insert a new row
        $data = array(
            'requestTaskId' => $inputArray['requestTaskId'],
            'assignee' => $raiserUserId,
            'level' => $level+1,                            
            'status' => $inputArray['selectedStatus'],
            'updatedDate' => $todayDate,
            );
        if($currentAssignee != $inputArray['userId']){
            $data['assignedBy'] = $inputArray['userId'];
        }else{
            $data['assignedBy'] = $resultData[0]['assignee'];
        }
        $taskActionDetailId = $this->salseOpsModel->insertNewRowToTaskUpdateDetailsTable($data);
        if($taskActionDetailId){
            $status = array(
                'status' =>1,
                'assignedUserId' => $raiserUserId
                );
        }else{
            $status['status'] =0;
        }
        return $status;
    }

    public function savePartialDoneAndReviewedStatus($inputArray){
        //_p($inputArray);die;
        if($inputArray['currentStatus'] == 'pushedBack'){
            // Get Current Assignee
            $result = $this->salseOpsModel->getLastRowForRequestTaskId($inputArray['requestTaskId']);
            $currentAssignee = $result[0]['assignee'];
        }else if (in_array($inputArray['currentStatus'], array('clientApprovedAndCreateHTML','inProgress','markedDone','completePartialDoneAndReviewed'))){
            $currentAssignee = $inputArray['currentAssignee'];
        }

        if($inputArray['team'] != 'Sales'){
            $dataForTATExpiredStatus = array(
                'requestId' => $inputArray['requestId'],
                'requestTaskId' => $inputArray['requestTaskId'],
                'assignedUserId' => $inputArray['currentAssignee'],
                'TATDate' => $inputArray['TATDate'],
            );
            $this->_updateOrInsertRowInTATMatchingDetails($dataForTATExpiredStatus);
            unset($dataForTATExpiredStatus);
        }

        // Update spliceRequests Table
        $todayDate = date('Y-m-d H:i:s');
        $data = array(
            'status' => $inputArray['selectedStatus'],
            'requestId' => $inputArray['requestId'],
            'requestTaskId' => $inputArray['requestTaskId'],
            'currentDate' => $todayDate
            );
        $this->updateRequestMainTable($data);

        // Update spliceRequestTasks Table
        // get request raiser id
        $result = $this->salseOpsModel->getRequestDetails($inputArray['requestId']);
        $raiserUserId = $result[0]['requestedBy'];
        
        $data = array(
            'currentStatus' => $inputArray['selectedStatus'],
            'requestTaskId' => $inputArray['requestTaskId'],
            'assignee' => $raiserUserId,
            'lastUpdatedOn' => $todayDate
            ); 
        $this->updateRequestTaskTable($data);        

        // Add New Entry and update previous entry to spliceTasksActionDetails Table
        // Get Previous row to update level 
        $resultData = $this->salseOpsModel->getTaskActionDetailCurrentLevelAndAssignedBy($inputArray['requestTaskId']);
        $level = $resultData[0]['level'];
        //_p($level);

        $data = array(
            'status' => $inputArray['selectedStatus'],
            'updatedDate' => $todayDate,
            'commentDetails' => $inputArray['commentData'],
            'attachmentURL' => $inputArray['attachmentURL'],
        );
        if($currentAssignee != $inputArray['userId']){
            $data['actionTakenBy'] = $inputArray['userId'];
        }
        //_p($data);                        
        $this->salseOpsModel->updateLastRowOfTasksActionDetails($data,$level,$inputArray['requestTaskId']);
        //insert a new row
        $data = array(
            'requestTaskId' => $inputArray['requestTaskId'],
            'assignee' => $raiserUserId,
            'level' => $level+1,                            
            'status' => $inputArray['selectedStatus'],
            'updatedDate' => $todayDate,
            );
        if($currentAssignee != $inputArray['userId']){
            $data['assignedBy'] = $inputArray['userId'];
        }else{
            $data['assignedBy'] = $resultData[0]['assignee'];
        }
        $taskActionDetailId = $this->salseOpsModel->insertNewRowToTaskUpdateDetailsTable($data);
        if($taskActionDetailId){
            $status = array(
                'status' =>1,
                'assignedUserId' => $raiserUserId
                );
        }else{
            $status['status'] =0;
        }
        return $status;
    }

    public function saveMarkedDoneStatus($inputArray){
        //_p($inputArray);die;
        if(!in_array($inputArray['groupId'],$inputArray['teamGroupIds']['salesTeam'])){
            $dataForTATExpiredStatus = array(
                'requestId' => $inputArray['requestId'],
                'requestTaskId' => $inputArray['requestTaskId'],
                'assignedUserId' => $inputArray['currentAssignee'],
                'TATDate' => $inputArray['TATDate'],
            );
            $this->_updateOrInsertRowInTATMatchingDetails($dataForTATExpiredStatus);
            unset($dataForTATExpiredStatus);
        }

        if($inputArray['userType'] == 'user'){ // user 
            // get supervisor id
            $supervisorId = $this->getSupervisorId($inputArray['userId']);

            // Update spliceRequests Table
            $todayDate = date('Y-m-d H:i:s');
            $data = array(
                'status' => $inputArray['selectedStatus'],
                'requestId' => $inputArray['requestId'],
                'requestTaskId' => $inputArray['requestTaskId'],
                'currentDate' => $todayDate
                );
            $this->updateRequestMainTable($data);

            // Update spliceRequestTasks Table                        
            $data = array(
                'currentStatus' => $inputArray['selectedStatus'],
                'requestTaskId' => $inputArray['requestTaskId'],
                'assignee' => $supervisorId,
                'lastUpdatedOn' => $todayDate
                ); 
            $this->updateRequestTaskTable($data);

            // Add New Entry and update previous entry to spliceTasksActionDetails Table
            // Get Previous row to update level 
            $resultData = $this->salseOpsModel->getTaskActionDetailCurrentLevelAndAssignedBy($inputArray['requestTaskId']);
            $level = $resultData[0]['level'];
            //_p($level);                        
            $data = array(
                'status' => $inputArray['selectedStatus'],
                'updatedDate' => $todayDate,
                'commentDetails' => $inputArray['commentData'],
                'attachmentURL' => $inputArray['attachmentURL'],
            );
            if($inputArray['currentAssignee'] != $inputArray['userId']){
                $data['actionTakenBy'] = $inputArray['userId'];
            }
            //_p($data);                        
            $this->salseOpsModel->updateLastRowOfTasksActionDetails($data,$level,$inputArray['requestTaskId']);
            //insert a new row
            $data = array(
                'requestTaskId' => $inputArray['requestTaskId'],
                'assignee' => $supervisorId,
                'level' => $level+1,                            
                'status' => $inputArray['selectedStatus'],
                'updatedDate' => $todayDate,
                );
            if($inputArray['currentAssignee'] != $inputArray['userId']){
                $data['assignedBy'] = $inputArray['userId'];
            }else{
                $data['assignedBy'] = $resultData[0]['assignee'];
            }
            $taskActionDetailId = $this->salseOpsModel->insertNewRowToTaskUpdateDetailsTable($data);
        }
        if($taskActionDetailId){
            $status = array(
                'status' =>1,
                'assignedUserId' => $supervisorId
                );
        }else{
            $status['status'] =0;
        }
        return $status;
    }

    public function getTeamGroupIds($team){
        $result = $this->salseOpsModel->getTeamGroupIds($team);
        foreach ($result as $key => $value) {
            $teamwiseGroupIds[$value['team']][] = $value['id'];
        }
        return $teamwiseGroupIds;
    }

    public function getUserIdsForSalesTeam($salesTeamGroupIds){
        $result = $this->salseOpsModel->getAddedUserIds($salesTeamGroupIds,'groupId');
        foreach ($result as $key => $value) {
            $userIds[] = $value['userId'];
        }
        return $userIds;
    }

    public function getTATExpiredRequestTaskDetails($date,$status,$userIds,$teamGroupMailId,$appName,$requestDisplayName){
        $result = $this->salseOpsModel->getTATExpiredYesterdayDetails($date,$status,$userIds);
        $requestTaskDetails = $result;
        if($requestTaskDetails){
            foreach ($requestTaskDetails as $key => $value) {
                $insertArray[$key] = array($value['requestId'],$value['id'],$value['assignee']);
                $requestIds[] = $value['requestId'];
                $userIds[] = $value['assignee'];
            }

            $userDetails = $this->getUserDetails($userIds);
            $requestIds = array_unique($requestIds);
            $result = $this->salseOpsModel->getRequestDetails($requestIds);
            foreach ($result as $key => $value) {
                $requestDetails[$value['id']] = array(
                    'salesOrderNumber' => $value['salesOrderNumber'],
                    'campaignDate' => $value['campaignDate'],
                    'clientName' => $value['clientName']
                    );
            }
            foreach ($requestTaskDetails as $key => $value) {
                $requestTaskDetails[$key]['salesOrderNumber'] = $requestDetails[$value['requestId']]['salesOrderNumber'];
                $requestTaskDetails[$key]['campaignDate'] = $requestDetails[$value['requestId']]['campaignDate'];
                $requestTaskDetails[$key]['clientName'] = $requestDetails[$value['requestId']]['clientName'];
                $requestTaskDetails[$key]['assignee'] = $userDetails[$value['assignee']]['userName'];
                $requestTaskDetails[$key]['assigneeUserId'] = $value['assignee'];
                $requestTaskDetails[$key]['groupMailIdForAssignedTeam'] = $this->getGroupIdForGivenTaskCategory($value['taskCategory'],$teamGroupMailId,$value['site']);
                if($value['taskCategory'] == 'Listing'){
                    if($value['site'] == 'Study Abroad'){
                        $requestTaskDetails[$key]['groupMailIdForAssignedTeam'] = $teamGroupMailId[strtolower($value['taskCategory'])]['studyAbroad'];
                    }else{
                        $requestTaskDetails[$key]['groupMailIdForAssignedTeam'] = $teamGroupMailId[strtolower($value['taskCategory'])]['domestic'];
                    }
                }else if($value['taskCategory'] == 'Campaign Activation'){
                    $requestTaskDetails[$key]['groupMailIdForAssignedTeam'] = $teamGroupMailId['campaignActivation'];
                }else{
                    $requestTaskDetails[$key]['groupMailIdForAssignedTeam'] = $teamGroupMailId[strtolower($value['taskCategory'])];
                }
            }
            unset($requestDetails);
            unset($result);
            //_p($requestDetails);die;
            //_p($requestIds);die;
            //_p($requestTaskDetails);die;_p($insertArray);die;
            $this->salseOpsModel->insertOrUpdateTATMatchingDetailsTable($insertArray);
            $this->_prepareMailForTATExpiredTaskDetails($requestTaskDetails,$appName,$requestDisplayName);
        }
    }

    private function _prepareMailForTATExpiredTaskDetails($requestTaskDetails,$appName,$requestDisplayName){
        //_p($requestTaskDetails);die;
        //_p($salesTeamGroupId);die;
        $commonData = '<br>For more details, please login on '.$appName.' application with your shiksha.com credentials at the following url:  <br>';
        $loginURL = SHIKSHA_HOME.'/splice/dashboard/login';
        $regards = '<br><br>Regards<br>'.ucfirst($appName).' Team';
        
        foreach ($requestTaskDetails as $key => $value) {
            //_p($value);die;
            $dataForTable = array(
                'Client Name'           => $value['clientName'],
                'Sales Order No.'       => $value['salesOrderNumber'],
                'Campaign Date'         => $this->getFormattedDate($value['campaignDate'],'date'),
                'Request Id'            => $value['requestId'],
                'Request Task Id'       => $value['id'],
                'Request Task Title'    => htmlentities($value['taskTitle']),
                'Request Task Category'    => htmlentities($requestDisplayName[$value['taskCategory']]),
                'Current Assignee'      => $value['assignee'],
                'TAT Ended On'          => $this->getFormattedDate($value['TATDate'],'date')
                );
            $tableInfo = $this->prepareTableForMailForVariousAction($dataForTable);
            $mailData = array();
            $mailData['sender'] = ADMIN_EMAIL;
            $mailIds = array();
            $mailIds = $this->getSalesTeamMailIds($value['requestId']);
            $mailIds[] = $value['groupMailIdForAssignedTeam'];
            $hierarchiesMailIds = $this->getHierarchyMailIdsForAssignee($value['assigneeUserId']);
            foreach ($hierarchiesMailIds as $emailId) {
                $mailIds[] = $emailId;
            }
            $mailData['recipients'] = array(
                'to' => $mailIds
            );
            //$mailData['subject']     = ucFirst($appName).': TAT expired for Request Task (Task ID: '.$value['id'].').';
            $mailData['subject']     = ucFirst($appName).' Escalation: TAT expired for Request Task (Task ID: '.$value['id'].').';
            $mailData['mailContent'] = $tableInfo['tableStyle'].'Hi All,'.'<br><br>TAT has been expired for the Request Task (Task ID: '.$value['id'].'), here are the details:<br><br>';
            $mailData['mailContent'] .= $tableInfo['tableData'];
            $mailData['mailContent'] .= $commonData.'Login URL : <a href="'.$loginURL.'">'.$loginURL.'</a>'.$regards;
            $this->_sendMail($mailData,'TATExpiredMail');
        }
    }

    public function getGroupMailId($defaultAssignee){
        foreach ($defaultAssignee as $taskCategory => $details) {
          //_p($value);
          if($taskCategory == 'listing'){
            foreach ($details as $key => $value) {
              $teamGroupMailId[$taskCategory][$key] = $value['notificationMailTo'];
            }
          }else{
            $teamGroupMailId[$taskCategory] = $details['notificationMailTo'];
          }
        }
        return $teamGroupMailId;
    }

    public function prepareTableForMailForVariousAction($dataForTable){
        $tableData = '<table class="mailTable">';
        foreach ($dataForTable as $key => $value) {
            if($key == 'Comment'){
                $tableData = $tableData.'<tr ><td style="vertical-align: top;"><b>'.$key.'</b></td>'.'<td>'.$value.'</td></tr>';
            }else{
                $tableData = $tableData.'<tr ><td ><b>'.$key.'</b></td>'.'<td>'.$value.'</td></tr>';    
            }
            
        }
        $tableData = $tableData.'</table>';
        $tableStyle = '<style>
                        table.mailTable {
                            font-family: verdana,arial,sans-serif;
                            font-size:11px;
                            color:#333333;
                            border-width: 1px;
                            border-color: #666666;
                            border-collapse: collapse;
                        }

                        table.mailTable td{
                            border-width: 1px;
                            padding: 8px;
                            border-style: solid;
                            border-color: #666666;
                            background-color: #ffffff;
                        }</style>';
        return array('tableData' => $tableData,
                    'tableStyle' => $tableStyle);                        
    }

    public function prepareMailDataForVariousAction($inputArray,$appName,$teamGroupMailId){
        //_p($inputArray);die;
        $mailData['sender'] = ADMIN_EMAIL;
        $result = $this->salseOpsModel->getRequestDetails($inputArray['requestId']);
        foreach ($result as $key => $value) {
            $requestDetails[$value['id']] = array(
                'salesOrderNumber' => $value['salesOrderNumber'],
                'campaignDate' => $value['campaignDate'],
                'requestedBy' => $value['requestedBy'],
                'clientName' => $value['clientName']
            );
        }
        unset($result);
        $dataForTable = array(
            'Client Name'        => $requestDetails[$inputArray['requestId']]['clientName'],
            'Sales Order No.'   => $requestDetails[$inputArray['requestId']]['salesOrderNumber'],
            'Campaign Date'     => $this->getFormattedDate($requestDetails[$inputArray['requestId']]['campaignDate'],'date'),
            'Request Id'        => $inputArray['requestId'],
            'Request Task Id'   => $inputArray['requestTaskId'],
            'Request Task Title'    => htmlentities($inputArray['requestTaskTitle']),
            'Request Task Category'    => htmlentities($inputArray['requestDisplayName'][$inputArray['taskCategory']])
            );
        
        $loginURL = SHIKSHA_HOME.'/splice/dashboard/login';
        $regards = '<br><br>Regards<br>'.ucfirst($appName).' Team';

        $commonData = '<br><br>For more details, please login on '.$appName.' application with your shiksha.com credentials at the following url:  <br>'.
                        'Login URL : <a href="'.$loginURL.'">'.$loginURL.'</a>'.$regards;
        $subject = $appName.': Request Task (Task ID: '.$inputArray['requestTaskId'];
        $subject .= ', Client : '.$this->limitTextLength($requestDetails[$inputArray['requestId']]['clientName'],10);
        $listingDetails = array();

        if($inputArray['taskCategory'] == 'Listing'){
            $taskAttributes = $this->salseOpsModel->getTaskAttributes($inputArray['requestTaskId']);
            if(is_array($taskAttributes) && count($taskAttributes) >0){
                foreach ($taskAttributes as $key => $value) {
                    if(($value['attributeName'] == 'university') || ($value['attributeName'] == 'institute') || ($value['attributeName'] == 'university_national')){
                        $result = $this->salseOpsModel->getListingName($value['attributeValue'],$value['attributeName']);
                        $listingDetails = array(
                            'listingId' => $value['attributeValue'],
                            'listingType' => ucFirst($value['attributeName'] =="university_national" ? "university":$value['attributeName']),
                            'listingName' => $result[0]['listing_title']
                        );
                        break;
                    }
                }
            }
        }

        if(isset($listingDetails['listingId'])){
            $subject .= ', '.$listingDetails['listingType'].' Id : '.$listingDetails['listingId'];

            $dataForTable[$listingDetails['listingType'].' Id']   =$listingDetails['listingId'];
            $dataForTable[$listingDetails['listingType'].' Name'] =$listingDetails['listingName'];
        }


        $dataForTable['Comment']       = $inputArray['commentData'];
        $dataForTable['TAT Ending On'] = $this->getFormattedDate($inputArray['TATDate'],'date');
        $dataForTable['Done By']       = $inputArray['loggedInUserName'];

        $tableInfo = $this->prepareTableForMailForVariousAction($dataForTable);
        $commomDetails = " here are the details:<br><br>".$tableInfo['tableData'];

        $subject .= ') has been ';

        if(($inputArray['selectedStatus'] == 'pushedBack')||($inputArray['selectedStatus'] == 'forwarded')){
            $userDetails = $this->getUserDetails(array($inputArray['assigneeId'],$requestDetails[$inputArray['requestId']]['requestedBy']));            
            $groupId = $this->salseOpsModel->getGroupId($inputArray['assigneeId']);
            $groupIds = array($groupId,$inputArray['groupId']);
            $groupIds = array_unique($groupIds);
            $result = $this->salseOpsModel->getGroupDetails($groupIds);
            $groupMailIds = array();
            $teamGroupIds = $inputArray['teamGroupIds'];
            foreach ($result as $key => $value) {
                if(in_array($value['id'],$teamGroupIds['salesTeam'])){
                    $mailIds = $this->getSalesTeamMailIds($inputArray['requestId']);
                    foreach ($mailIds as $key => $value) {
                        $groupMailIds[] = $value;
                    }
                }else{
                    $otherTeamGroupIds = array_merge($teamGroupIds['design'],$teamGroupIds['salesOps'],$teamGroupIds['contentOps']);
                    if(in_array($value['id'],$otherTeamGroupIds)){
                        $groupMailIds[] = $this->getGroupIdForGivenTaskCategory($inputArray['taskCategory'],$teamGroupMailId,$inputArray['site']);
                    }
                }
            }
            //_p($groupMailIds);die;
            $groupMailIds[] = $userDetails[$requestDetails[$inputArray['requestId']]['requestedBy']]['email'];
            
            $groupMailIds = array_unique($groupMailIds);
        }else if(($inputArray['selectedStatus'] == 'commentAdded')){
            $taskActionDetails  = $this->salseOpsModel->getTaskDetailHistory($inputArray['requestTaskId']);
            foreach ($taskActionDetails as $key => $value) {
                if($value['isOtherActionTaken'] != 'no'){
                    $taskActionDetailId[] = $value['id'];                    
                }
                $userIds[] = $value['assignedBy'];
                $userIds[] = $value['assignee'];
                $userIds[] = $value['actionTakenBy'];
            }
            unset($taskActionDetails);
            if(count($taskActionDetailId) > 0){
                $result = $this->salseOpsModel->getPushBackAndCommentDetails($taskActionDetailId);
                foreach ($result as $key => $value) {
                    $userIds[] = $value['assignee'];                    
                }
            }
            unset($result);
            unset($taskActionDetailId);

            // user details
            if(count($userIds)> 0){
                $userIds = array_unique($userIds);
                $userDetails = $this->getUserDetails($userIds);    
            }            
            foreach ($userDetails as $key => $value) {
                $userEmailIdsArray[] = $value['email'];
            }
            $assignedTeamGroupMailId = $this->getGroupIdForGivenTaskCategory($inputArray['taskCategory'],$teamGroupMailId,$inputArray['site']);
        }else if(($inputArray['selectedStatus'] == 'reassign')){
            //$inputArray['assigneeId']
            $userDetails = $this->getUserDetails($inputArray['assigneeId']);
            $groupId = $this->salseOpsModel->getGroupId($inputArray['assigneeId']);
            if(in_array($groupId, $inputArray['teamGroupIds']['salesTeam'])){
                $assignedTeamGroupMailId = $this->getHierarchyMailIdsForAssignee($inputArray['assigneeId']);
            }else{
                $assignedTeamGroupMailId = array();
                $assignedTeamGroupMailId[] = $this->getGroupIdForGivenTaskCategory($inputArray['taskCategory'],$teamGroupMailId,$inputArray['site']);
            }
        }else{
            $userDetails = $this->getUserDetails($inputArray['assigneeId']);
            $assignedTeamGroupMailId = $this->getGroupIdForGivenTaskCategory($inputArray['taskCategory'],$teamGroupMailId,$inputArray['site']);
        }
        $mailData['mailContent'] = $tableInfo['tableStyle'];
        switch ($inputArray['selectedStatus']){
            case 'doneAndReviewed':
                $mailIds = $this->getSalesTeamMailIds($inputArray['requestId']);
                $mailIds[] = $assignedTeamGroupMailId;
                $mailIds = array_diff($mailIds, array($userDetails[$inputArray['assigneeId']]['email']));
                $mailIds = array_unique($mailIds);
                $mailData['recipients'] = array(
                    'to' => array($userDetails[$inputArray['assigneeId']]['email']),
                    'cc'   => $mailIds
                    );
                $mailData['subject'] = $subject.'Done & Reviewed.';
                $mailData['mailContent'] = $mailData['mailContent'].'Hi '.$userDetails[$inputArray['assigneeId']]['userName'].',<br><br>'.
                                'Request Task (Task Id: '.$inputArray['requestTaskId'].') has been <b>Done & Reviewed</b> and assigned to you for further actions, '.$commomDetails.$commonData;
                break;

            case 'markedDone':
                $mailData['recipients'] = array(
                    'to' => array($userDetails[$inputArray['assigneeId']]['email']),
                    'cc'   => array($assignedTeamGroupMailId)
                    );                
                $mailData['subject'] = $subject.'Marked Done.';
                $mailData['mailContent'] = $mailData['mailContent'].'Hi '.$userDetails[$inputArray['assigneeId']]['userName'].',<br><br>'.
                                'Request Task (Task Id: '.$inputArray['requestTaskId'].') has been <b>Marked Done</b> and assigned to you for review,'.$commomDetails.$commonData;

                break;

            case 'reassign':
                $assignedTeamGroupMailId = array_diff($assignedTeamGroupMailId, array($userDetails[$inputArray['assigneeId']]['email']));
                $assignedTeamGroupMailId = array_unique($assignedTeamGroupMailId);
                $mailData['recipients'] = array(
                    'to' => array($userDetails[$inputArray['assigneeId']]['email']),
                    'cc'   => $assignedTeamGroupMailId
                    );                    
                $mailData['subject'] = $subject.'re-assigned.';
                $mailData['mailContent'] = $mailData['mailContent'].'Dear '.$userDetails[$inputArray['assigneeId']]['userName'].',<br><br>'.
                                'Request Task (Task Id: '.$inputArray['requestTaskId'].') has been <b>re-assigned</b> to you,'.$commomDetails.$commonData;
                break;

            case 'pushedBack':
                $groupMailIds = array_diff($groupMailIds,array($userDetails[$inputArray['assigneeId']]['email']));
                $groupMailIds = array_unique($groupMailIds);
                $mailData['recipients'] = array(
                    'to' => array($userDetails[$inputArray['assigneeId']]['email']),
                    'cc'   => $groupMailIds
                    );

                $mailData['subject'] = $subject.'Pushed Back.';
                $mailData['mailContent'] = $mailData['mailContent'].'Dear '.$userDetails[$inputArray['assigneeId']]['userName'].',<br><br>'.
                                'Request Task (Task Id: '.$inputArray['requestTaskId'].') has been <b>Pushed Back</b> to you,'.$commomDetails.$commonData;
                break;

            case 'forwarded':
                $mailIds = array();
                $mailIds[] = $userDetails[$inputArray['assigneeId']]['email'];
                if($inputArray['newTATForCampaignActivation']){
                    $mailIds[] = $inputArray['salesOpsGroupId'];
                }

                $mailIds = array_unique($mailIds);
                $groupMailIds = array_diff($groupMailIds, $mailIds);
                $groupMailIds = array_unique($groupMailIds);
                $mailData['recipients'] = array(
                    'to' => $mailIds,
                    'cc'   => $groupMailIds
                    );

                $mailData['subject'] = $subject.'Forwarded.';
                $mailData['mailContent'] = $mailData['mailContent'].'Dear '.$userDetails[$inputArray['assigneeId']]['userName'].',<br><br>'.
                                'Request Task (Task Id: '.$inputArray['requestTaskId'].') has been <b>Forwarded</b> to you,'.$commomDetails;
                if($inputArray['newTATForTask']){
                    if($inputArray['requestNewTAT']){
                        $mailData['mailContent'] = $mailData['mailContent'].'<br>TAT has been updated for this request and request task.';
                    }else{
                        $mailData['mailContent'] = $mailData['mailContent'].'<br>TAT has been updated for this request task.';
                    }
                }

                $mailData['mailContent'] = $mailData['mailContent'].$commonData;
                break;                

            case 'clientApprovedAndClosed':
                $mailIds = $this->getSalesTeamMailIds($inputArray['requestId']);
                $mailIds[] = $assignedTeamGroupMailId;
                if($inputArray['newTATForCampaignActivation']){
                    $mailIds[] = $inputArray['salesOpsGroupId'];
                }
                $mailIds = array_diff($mailIds, array($userDetails[$inputArray['assigneeId']]['email']));
                $mailIds = array_unique($mailIds);
                $mailData['recipients'] = array(
                    'to' => $mailIds,
                    'cc'   => array($userDetails[$inputArray['assigneeId']]['email']),
                    );

                $mailData['subject'] = $subject.'Client Approved & Closed.';
                $mailData['mailContent'] = $mailData['mailContent'].'Hi All,<br><br>'.
                                'Request Task (Task Id: '.$inputArray['requestTaskId'].') has been <b>Client Approved & Closed</b>,'.$commomDetails;
                $mailData['mailContent'] = $mailData['mailContent'].$commonData;                            
                break;

            case 'clientApprovedAndCreateHTML':
                $mailIds = $this->getSalesTeamMailIds($inputArray['requestId']);
                $mailIds[] = $assignedTeamGroupMailId;
                
                $ToMailIds = array();
                $ToMailIds[] = $userDetails[$inputArray['assigneeId']]['email'];
                if($inputArray['newTATForCampaignActivation']){
                    $ToMailIds[] = $inputArray['salesOpsGroupId'];
                }
                $ToMailIds = array_unique($ToMailIds);
                $mailIds = array_diff($mailIds, $ToMailIds);
                $mailIds = array_unique($mailIds);
                $mailData['recipients'] = array(
                    'to' => $ToMailIds,
                    'cc'   => $mailIds,
                    );

                $mailData['subject'] = $subject.'Client Approved & assigned for Creating HTML.';
                $mailData['mailContent'] = $mailData['mailContent'].'Dear '.$userDetails[$inputArray['assigneeId']]['userName'].',<br><br>'.
                                'Request Task (Task Id: '.$inputArray['requestTaskId'].') has been re-assigned to you for <b>HTML creation</b>,'.$commomDetails;
                if($inputArray['newTATForTask']){
                    if($inputArray['requestNewTAT']){
                        $mailData['mailContent'] = $mailData['mailContent'].'<br>TAT has been updated for this request and request task.';
                    }else{
                        $mailData['mailContent'] = $mailData['mailContent'].'<br>TAT has been updated for this request task.';
                    }
                }
                $mailData['mailContent'] = $mailData['mailContent'].$commonData;
                break;

            case 'cancel':
                $mailIds = $this->getSalesTeamMailIds($inputArray['requestId']);
                $mailIds[] = $assignedTeamGroupMailId;
                if($inputArray['newTATForCampaignActivation']){
                    $mailIds[] = $inputArray['salesOpsGroupId'];
                }
                $mailIds = array_unique($mailIds);
                $mailData['recipients'] = array(
                    'to' => $mailIds
                    );

                $mailData['subject'] = $subject.'Cancelled.';
                $mailData['mailContent'] = $mailData['mailContent'].'Hi All,<br><br>'.
                                'Request Task (Task Id: '.$inputArray['requestTaskId'].') has been <b>Cancelled</b>,'.$commomDetails;
                $mailData['mailContent'] = $mailData['mailContent'].$commonData;
                break;                

            case 'commentAdded':
                $userEmailIdsArray[] = $assignedTeamGroupMailId;
                $mailIds = $this->getSalesTeamMailIds($inputArray['requestId']);
                foreach ($mailIds as $key => $value) {
                    $userEmailIdsArray[] = $value;    
                }
                $userEmailIdsArray = array_unique($userEmailIdsArray);
                $mailData['recipients'] = array(
                    'to' => $userEmailIdsArray
                    );

                $mailData['subject'] = $appName.': A comment has been added for Request Task (Task Id : '.$inputArray['requestTaskId'];
                $mailData['subject'] .= ', Client : '.$this->limitTextLength($requestDetails[$inputArray['requestId']]['clientName'],10);

                if(isset($listingDetails['listingId'])){
                    $mailData['subject'] .= ', '.$listingDetails['listingType'].' Id : '.$listingDetails['listingId'];
                }

                $mailData['subject'] .= ').';

                $mailData['mailContent'] = $mailData['mailContent'].'Hi All,<br><br>'.                
                                'A <b>new comment</b> has been added for the request task (Task Id: '.$inputArray['requestTaskId'].'),'.$commomDetails.$commonData;
                break;

            case 'partialDoneAndReviewed':
                $mailIds = $this->getSalesTeamMailIds($inputArray['requestId']);
                $mailIds[] = $assignedTeamGroupMailId;
                $mailIds = array_diff($mailIds, array($userDetails[$inputArray['assigneeId']]['email']));
                $mailIds = array_unique($mailIds);
                $mailData['recipients'] = array(
                    'to' => array($userDetails[$inputArray['assigneeId']]['email']),
                    'cc'   => $mailIds
                    );
                $mailData['subject'] = $subject.'Partial Done & Reviewed.';
                $mailData['mailContent'] = $mailData['mailContent'].'Hi '.$userDetails[$inputArray['assigneeId']]['userName'].',<br><br>'.
                                'Request Task (Task Id: '.$inputArray['requestTaskId'].') has been <b>Partial Done & Reviewed</b> and assigned to you for further actions,'.$commomDetails.$commonData;
                break;

            case 'completePartialDoneAndReviewed':
                $mailIds = $this->getSalesTeamMailIds($inputArray['requestId']);
                $mailIds[] = $assignedTeamGroupMailId;
                
                $ToMailIds = array();
                $ToMailIds[] = $userDetails[$inputArray['assigneeId']]['email'];
                if($inputArray['newTATForCampaignActivation']){
                    $ToMailIds[] = $inputArray['salesOpsGroupId'];
                }
                $ToMailIds = array_unique($ToMailIds);
                $mailIds = array_diff($mailIds, $ToMailIds);
                $mailIds = array_unique($mailIds);
                $mailData['recipients'] = array(
                    'to' => $ToMailIds,
                    'cc'   => $mailIds,
                    );

                $mailData['subject'] = $subject.'marked as Complete Partial Done & Reviewed.';
                $mailData['mailContent'] = $mailData['mailContent'].'Dear '.$userDetails[$inputArray['assigneeId']]['userName'].',<br><br>'.
                                'Request Task (Task Id: '.$inputArray['requestTaskId'].') has been marked as <b>Complete Partial Done & Reviewed</b> and re-assigned to you,'.$commomDetails;
                if($inputArray['newTATForTask']){
                    if($inputArray['requestNewTAT']){
                        $mailData['mailContent'] = $mailData['mailContent'].'<br>TAT has been updated for this request and request task.';
                    }else{
                        $mailData['mailContent'] = $mailData['mailContent'].'<br>TAT has been updated for this request task.';
                    }
                }
                $mailData['mailContent'] = $mailData['mailContent'].$commonData;
                break;
        }
        //_p($mailData);die;
        $this->_sendMail($mailData);
    }    

    public function getGroupIdForGivenTaskCategory($taskCategory,$teamGroupMailId,$site=''){
        if($taskCategory =='Listing'){
            if($site == 'Study Abroad'){
                $assignedTeamGroupMailId = $teamGroupMailId[strtolower($taskCategory)]['Study Abroad'];
            }else{
                $assignedTeamGroupMailId = $teamGroupMailId[strtolower($taskCategory)]['domestic'];
            }
        }else{
            switch ($taskCategory) {
                case 'Mailer':
                case 'Banner':
                case 'Shoshkele':
                    $assignedTeamGroupMailId = $teamGroupMailId[strtolower($taskCategory)];
                    break;
                case 'Campaign Activation':
                    $assignedTeamGroupMailId = $teamGroupMailId['campaignActivation'];
                    break;
            }
        }
        return $assignedTeamGroupMailId;
    }

    public function getHierarchyMailIdsForAssignee($userId){
        $managerIds = array(3,6,9,14);
        $userIds = array();
        $userIds[] = $userId;

        $result = $this->salseOpsModel->getGroupIdAndSupervisorId(array($userId));
        if(is_array($result) && count($result) >0){
            $result = $result[0];
            if(!(in_array($result['groupId'], $managerIds) || $result['groupId'] == 15 || $result['groupId'] == 16)){
                $userIds[] = $result['supervisorId'];
                $result = $this->salseOpsModel->getGroupIdAndSupervisorId(array($result['supervisorId']));
                if(is_array($result) && count($result) >0){
                    $result = $result[0];
                    if(!in_array($result['groupId'], $managerIds)){
                        $userIds[] = $result['supervisorId'];
                    }
                }
            }
        }

        $userIds = array_unique($userIds);
        $result = $this->salseOpsModel->getUserDetails($userIds,'userId');
        foreach ($result as $key => $value) {
            $emailIds[] = $value['email'];
        }
        $emailIds = array_unique($emailIds);
        return $emailIds;
    }

    public function getSalesTeamMailIdsForGivenUserId($userId){
        //$requestId = 143;
        $userIds = array();
        $userIds[] = $userId;
        $result = $this->salseOpsModel->getGroupIdAndSupervisorId(array($userId));
        if(is_array($result) && count($result) >0){
            $result = $result[0];

            $supervisorId = array();
            if($result['groupId'] != '3'){
                $supervisorId[] = $result['supervisorId'];
                $userIds[] = $result['supervisorId'];

                $result = $this->salseOpsModel->getGroupIdAndSupervisorId($supervisorId);
                if(is_array($result) && count($result) >0){
                    $result = $result[0];

                    $supervisorId = array();
                    if($result['groupId'] != '3'){
                        $supervisorId[] = $result['supervisorId'];
                        $userIds[] = $result['supervisorId'];
                    }
                }
                    
            }
        }

        $userIds = array_unique($userIds);
        $result = $this->salseOpsModel->getUserDetails($userIds,'userId');
        foreach ($result as $key => $value) {
            $emailIds[] = $value['email'];
        }
        $emailIds = array_unique($emailIds);
        return $emailIds;
    }

    public function getSalesTeamMailIds($requestId){
        //$requestId = 143;
        $result = $this->salseOpsModel->getReassignedUserIdsForTask($requestId);
        $result = $result[0];

        if($result['reassignedUserIds']){                                
            $userIds = explode(',',$result['reassignedUserIds']);
        }
        $userIds[] = $result['requestedBy'];

        $result = $this->salseOpsModel->getGroupIdAndSupervisorId($userIds);
        //_p($result);
        $supervisorIds = array();
        $userIds = array();
        foreach ($result as $key => $value) {
            if($value['groupId'] != '3'){
                $supervisorIds[] = $value['supervisorId'];
            }
            $userIds[] = $value['userId'];
        }

        if(count($supervisorIds) > 0){
            $result = $this->salseOpsModel->getGroupIdAndSupervisorId($supervisorIds);
            $supervisorIds = array();
            foreach ($result as $key => $value) {
                if($value['groupId'] != '3'){
                    $supervisorIds[] = $value['supervisorId'];
                }
                $userIds[] = $value['userId'];
            }
        }

        if(count($supervisorIds) > 0){
            $result = $this->salseOpsModel->getGroupIdAndSupervisorId($supervisorIds);
            $supervisorIds = array();
            foreach ($result as $key => $value) {
                if($value['groupId'] != '3'){
                    $supervisorIds[] = $value['supervisorId'];
                }
                $userIds[] = $value['userId'];
            }
        }

        if(is_array($userIds) && count($userIds) >0){
            $userIds = array_unique($userIds);
            $result = $this->salseOpsModel->getUserDetails($userIds,'userId');
            foreach ($result as $key => $value) {
                $emailIds[] = $value['email'];
            }
            $emailIds = array_unique($emailIds);
            return $emailIds;
        }else{
            return array();
        }
      
    }

    public function prepareMailData($mailInputData,$appName,$type='newMemberAdded'){
        $mailBody = "Dear ".$mailInputData['userName'].','.'<br><br>';
        $spliceLoginURL = SHIKSHA_HOME.'/splice/dashboard/login';
        $loginURL = 'Login URL : <a href="'.$spliceLoginURL.'">'.$spliceLoginURL.'</a>';
        $regards = '<br><br>Regards<br>'.ucfirst($appName).' Team';
        $commonData = 'For more details, please login on '.$appName.' application with your shiksha.com credentials at the following url:<br>'.$loginURL.$regards;

        if($type == 'newMemberAdded'){
          $subject = "Welcome to ".$appName;
          $mailBody .= "You have been successfully registered with ".$appName.'.'.' '.$commonData;
          $mailData['recipients'] = array(
                                          'to' => $mailInputData['to']?$mailInputData['to']:array() // $mailData['email']
                                          );
        }else if($type='newRequestAdded'){
            $dataForTable = array(
                'Client Name'           => $mailInputData['clientName'],
                'Sales Order No.'       => $mailInputData['salesOrderNo'],
                'Campaign Date'         => $this->getFormattedDate($mailInputData['campaignDate'],'date'),
                'Request Id'            => $mailInputData['requestId'],
                'Request Task Id'       => $mailInputData['requestTaskId'],
                'Request Task Title'    => htmlentities($mailInputData['requestTitle']),
                'Request Task Category'    => htmlentities($mailInputData['taskCategory'])
            );

            //New request task (Task Id : 6755, Client : Amity Universirt... , Institute Id : 1234) added on Splice
            $subject = "New request task (Task Id : ".$mailInputData['requestTaskId'];
            $subject .= " , Client : ".$this->limitTextLength($mailInputData['clientName'],10);

            if($mailInputData['listingId'] >0){
                $listingTypeName = ($mailInputData['listingURLType'] == 'institute')?"Institute":"University";
                $dataForTable[$listingTypeName.' Id'] = $mailInputData['listingId'];
                $listingName = $this->checkIfListingIsValid($mailInputData['site'],$mailInputData['listingId']);
                $dataForTable[$listingTypeName.' Name'] = $listingName['listingName'];

                $subject .= " , ".$listingTypeName." Id : ".$mailInputData['listingId'];
            }

            $subject .= ") added on ".$appName;

            $dataForTable['TAT Ending Date'] = $this->getFormattedDate($mailInputData['TATDate'],'date');
            $dataForTable['Done By'] = $mailInputData['loggedInUserName'];

            $tableInfo = $this->prepareTableForMailForVariousAction($dataForTable);

            $mailBody .= $tableInfo['tableStyle'].'A new request task has been assigned to you on '.$appName.', here are the details : <br><br>';
            $mailBody .=  $tableInfo['tableData'];
            $mailBody .= '<br>'.$commonData;

            $mailData['recipients'] = array(
                'to' => $mailInputData['to']?$mailInputData['to']:array(),  //$mailData['defaultAssignee']
                'cc' => $mailInputData['cc']?$mailInputData['cc']:array()
            );
        }
        $mailData['sender']           = ADMIN_EMAIL;
        $mailData['subject']          = $subject;
        $mailData['mailContent']      = $mailBody;
        //_p($mailData);die;
        $response = $this->_sendMail($mailData);
        return $response;
    }

    public function getRequestIdForTaskIds($requestTaskIds){
        $result = $this->salseOpsModel->getRequestIdForTaskIds($requestTaskIds);
        foreach ($result as $key => $value) {
            $requestIds[] = $value['requestId'];
        }
        $requestIds = array_unique($requestIds);
        //_p($result);die;
        return $requestIds;
    }

    private function _sendMail($mailData,$filter=''){
        $mailData['recipients'] = $this->_filterAdminEmailIds($mailData['recipients']);
        if($filter == 'TATExpiredMail'){
            $mailData['recipients']['to'][] = 'ambrish@shiksha.com';
            $mailData['recipients']['bcc'] = array('praveen.singhal@99acres.com');
        }

        foreach ($mailData['recipients']['to'] as $key => $email) {
            if($email == "shirish.shukla@shiksha.com" || $email == "blocked__7718972__shirish.shukla@shiksha.com" || $email == "nitin.kalani@shiksha.com"){
                unset($mailData['recipients']['to'][$key]);
            }
        }

        foreach ($mailData['recipients']['bcc'] as $key => $email) {
            if($email == "shirish.shukla@shiksha.com" || $email == "blocked__7718972__shirish.shukla@shiksha.com" || $email == "nitin.kalani@shiksha.com"){
                unset($mailData['recipients']['bcc'][$key]);
            }
        }
        //_p($mailData);die;
        if (ENVIRONMENT == 'production' || ENVIRONMENT == 'beta'){
            $response = sendMails($mailData);
        }
        
        return $response;
    }

    private function _filterAdminEmailIds($recipientsMailIds){
        //_p($recipientsMailIds);
        if($recipientsMailIds['cc']){
            $emailIds = array_merge($recipientsMailIds['to'],$recipientsMailIds['cc']);
        }else{
            $emailIds = $recipientsMailIds['to'];
        }
        //_p($emailIds);
        $adminGroupIds = array(15,16);
        $result = $this->salseOpsModel->getUserIdsForGivenGroups($adminGroupIds);
        //_p($result);
        if($result){
            foreach ($result as $key => $value) {
                $userIds[] = $value['userId'];
            }
            //_p($userIds);
            $result = $this->salseOpsModel->getUserDetails($userIds,'userId');
            $emailIds = array();
            foreach ($result as $key => $value) {
                $emailIds[] = $value['email'];
            }
            //_p($emailIds);
            $recipientsMailIds['to'] = array_diff($recipientsMailIds['to'], $emailIds);
            $recipientsMailIds['cc'] = array_diff($recipientsMailIds['cc'], $emailIds);
            //_p($recipientsMailIds);    
        }
        
        return $recipientsMailIds;
    }

    function limitTextLength($content, $charToDisplay){
        if(strlen($content) > $charToDisplay){
            return (substr($content, 0, $charToDisplay-3).'...');
        }else{
            return $content;
        }
    }
}