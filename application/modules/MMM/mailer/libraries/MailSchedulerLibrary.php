<?php
class MailSchedulerLibrary
{
	function __construct(){
		$this->CI     = & get_instance();
    }

    function getClientDetails($queryValue,$type){
    	if(empty($queryValue) && empty($type)){
    		return;
    	}
    	$mailerSchedulerModel = $this->CI->load->model('mailer/mailerschedulermodel');
    	if($type=='clientId'){
    		$clientData = $mailerSchedulerModel->getClientDetailsByUserId($queryValue);
    	}
    	else if($type=='emailId'){
    		$clientData = $mailerSchedulerModel->getClientDetailsByEmailId($queryValue);
    	}

    	if(empty($clientData)){
    		return;
    	}

        $this->CI->load->config('mailer/mailerConfig');
        $mailerAdminUserId = $this->CI->config->item('mailerAdminUserId');    
        $MMM_BaseProductId = $this->CI->config->item('MMM_BaseProductId');    

        if($clientData['userid'] != $mailerAdminUserId) {
            $sumsmodel = $this->CI->load->model('sumsmodel','sums');
            $returnData['subscriptionDetails'] = $sumsmodel->getAllSubscriptionsForUser($clientData['userid'], $MMM_BaseProductId);
        }
        $susbIds = array();
        foreach ($returnData['subscriptionDetails'] as $key => $value) {
            $susbIds[] = intval($value['SubscriptionId']);
        }

        $lockedAmountForSubscriptions = $mailerSchedulerModel->getLockedAmountForSubscription($susbIds);
        $lockedAmountForSubscriptionsMapping = array();

        foreach ($lockedAmountForSubscriptions as $lockedAmountData) {
            if(empty($lockedAmountData['lockedAmount'])){
                $lockedAmountData['lockedAmount'] = 0;
            }

            $lockedAmountForSubscriptionsMapping[$lockedAmountData['subscriptionId']] = $lockedAmountData['lockedAmount'];
        }

        foreach ($returnData['subscriptionDetails'] as $key => $value) {
            $returnData['subscriptionDetails'][$key]['BaseProdRemainingQuantity'] = $returnData['subscriptionDetails'][$key]['BaseProdRemainingQuantity'] - $lockedAmountForSubscriptionsMapping[intval($returnData['subscriptionDetails'][$key]['SubscriptionId'])];
        }

    	$returnData['clientId'] = $clientData['userid'];
    	$returnData['emailId'] = $clientData['email'];
    	$returnData['firstName'] = $clientData['firstname'];
    	$returnData['lastName'] = $clientData['lastname'];

    	return $returnData;
    }

    function getAllUserSearchCriteria($userId, $groupId, $adminType){

        $this->CI->load->model('mailer/mailermodel');
        $mailermodelObj = new mailermodel();
        $usersets = $mailermodelObj->getAllUserSearchCriteria($userId, $groupId, $adminType);
        return $usersets;
    }

    function processUserSetCSV($file,$templateType,$downloadCheck,$schedule,$userId,$userGroup){
        $csv_array = array();
        $skipCheck = FALSE;
        $tempFileName = "";
        $csv_array = $this->buildCVSArray($file);
        $numUsers = count($csv_array['email']);
        if( $numUsers > 25000){
            $var_result['error'] = 'Upload failed. Please upload a maximum of 25,000 email ids only.';
            return $var_result;
        } else if (count($csv_array['email']) == 0) {
            $var_result['error'] = 'Your CSV file is Empty.';
            return $var_result;
        } 
        
        $invalidCSVIndex = $this->findInvalidIndex($csv_array); 
        if( $downloadCheck == 0 && !empty( $invalidCSVIndex )){
            $var_result['error'] = 'Your CSV file contains invalid email id, choose a correct CSV .';
            return $var_result;
        }
        
        if($downloadCheck == 1){
            $invalidEmailIds = $this->getInvalidEmailIds($csv_array,$invalidCSVIndex);
           
            if(!empty($invalidEmailIds)){
                 $data =  implode(',', $invalidEmailIds);
            } else {
                $data  = "No Invalid Email Ids.";
            }
            return $data;
        } else {
            $numUsers = count($csv_array['email']);
            if($templateType == "mail" && !(isset($csv_array["email"]))) {

                $var_result['error'] = 'Your CSV file is not matched with selected Template variables, choose a correct CSV.';
            return $var_result;
            }
        }
        if ($numUsers > 0 && $schedule == 1)
        {
            $mailerSchedulerModel = $this->CI->load->model('mailer/mailerschedulermodel');
            $listId = $mailerSchedulerModel->insertUserSetForMailer($csv_array,count($csv_array['email']),$userId,$userGroup);
            $var_result["listId"] = $listId;
        }
       $var_result['numUsers'] =  $numUsers;
       return $var_result;
    }

    function buildCVSArray($File)
    {
        $handle = fopen($File, "r");
        $fields = fgetcsv($handle, 1000, ",");
        while($data = fgetcsv($handle, 1000, ",")) {
                $detail[] = $data;          
        }
        $x = 0;
        foreach($fields as $z) {
            foreach($detail as $i) {
                $stock[$z][] = $i[$x];
            }
            $x++;
        }
        return $stock;
    }

    function validateEmail($emailID){
        
            $pattern = "/^((([a-z]|[A-Z]|[0-9]|\-|_)+(\.([a-z]|[A-Z]|[0-9]|\-|_)+)*)@((((([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.))*([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/";

    /*$pattern = "/^((([a-z]|[A-Z]|[0-9]|\-|_)+(\.([a-z]|[A-Z]|[0-9]|\-|_)+)*)@((((([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.))*([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$";*/
        
        return preg_match($pattern, $emailID) ? true : false;
    }

    function findInvalidIndex($csv_array){

        foreach ($csv_array as $key => $value) {
            if($key == 'email'){
                foreach ($value as $index => $emailID) {
                    $validCheck = $this->validateEmail($emailID);
                    if(!$validCheck){
                        $invalidCSVIndex[] = $index;
                    }
                }
            }       
        }

        return $invalidCSVIndex;
    }

    function getInvalidEmailIds($csv_array,$invalidCSVIndex){
        $invalidEmailIds =array();
        if(!empty($csv_array) && !empty($invalidCSVIndex)){
            foreach ($invalidCSVIndex as $index) {          
                    $invalidEmailIds[] = $csv_array['email'][$index];
            }
        }
        return $invalidEmailIds;
    }
    
    function getTemplatesByUserId($userid,$groupId,$adminType){
    	$mailerSchedulerModel = $this->CI->load->model('mailer/mailerschedulermodel');
    	$allTemplates = $mailerSchedulerModel->getTemplatesByUserId($userid,$groupId,$adminType);

    	return $allTemplates;
    }
    function lockCredits($subscriptionData,$details,$campaignName,$templateMappings,$campaignId,$isEdit=0){

        $this->CI->load->config('mailer/mailerConfig');
        $mailerAdminUserId = $this->CI->config->item('mailerAdminUserId');
        if($details['parent']['clientId']==$mailerAdminUserId || $details['opened']['clientId']==$mailerAdminUserId || $details['openedAndClicked']['clientId']==$mailerAdminUserId || $details['openedAndNotClicked']['clientId']==$mailerAdminUserId || $details['notOpenedAndNotClicked']['clientId']==$mailerAdminUserId){

            $mailerSchedulerModel = $this->CI->load->model('mailer/mailerschedulermodel');

            $mailerSchedulerModel->startTransaction();

            $updateMailerData = $this->insertRestDataIntoMailer($details,$campaignName,$templateMappings,$dbIdSubsIdMap,$campaignId,$isEdit);

            $mailerSchedulerModel->completeTransaction();

            $campaignId = $updateMailerData["campaignId"];
            unset($updateMailerData["campaignId"]);       
            
            return array("responseText"=>"1","campaignId"=>$campaignId);
        }

        if(empty($subscriptionData)){
            return array("responseText"=>"0","error"=>"subscription data is empty");
        }
        $subsIdCreditMapping = array();
        $subscriptionIds = array();
        $mailerSchedulerModel = $this->CI->load->model('mailer/mailerschedulermodel');
        foreach ($subscriptionData as $data){
            if(!empty($subsIdCreditMapping[intval($data['subscriptionId'])])){
                $subsIdCreditMapping[intval($data['subscriptionId'])] += $data['userSet'];
            }
            else{
                $subsIdCreditMapping[intval($data['subscriptionId'])] = $data['userSet'];
                $subscriptionIds[] = $data['subscriptionId'];
            }
        }

        $remainingBaseProdQuantity = $mailerSchedulerModel->getSubscribtionDetails($subscriptionIds);

        if(empty($remainingBaseProdQuantity)){
            return array("responseText"=>"0","error"=>"remaining base product quantity is empty for selected subscriptions");
        }

        $remainingBaseProdQuantityMapping = array();

        foreach ($remainingBaseProdQuantity as $data) {
            $remainingBaseProdQuantityMapping[intval($data['SubscriptionId'])] = $data['BaseProdRemainingQuantity'];
        }

        unset($remainingBaseProdQuantity);

        $lockedAmountForSubscriptions = $mailerSchedulerModel->getLockedAmountForSubscription($subscriptionIds);
        $lockedAmountForSubscriptionsMapping = array();

        foreach ($lockedAmountForSubscriptions as $lockedAmountData) {
            if(empty($lockedAmountData['lockedAmount'])){
                $lockedAmountData['lockedAmount'] = 0;
            }

            $lockedAmountForSubscriptionsMapping[$lockedAmountData['subscriptionId']] = $lockedAmountData['lockedAmount'];
        }

        $lessAmountFlag = 0;
        $errorMsg = "you don't have sufficient free base product quantity for subscriptionIds ";
        foreach ($remainingBaseProdQuantityMapping as $subId => $remainingBaseProdQuantityForSubs) {

            if(empty($lockedAmountForSubscriptionsMapping[$subId])){
                $lockedAmount = 0;
            }
            else{
                $lockedAmount = $lockedAmountForSubscriptionsMapping[$subId];
            }

            if( ($remainingBaseProdQuantityMapping[$subId] - $lockedAmount) < $subsIdCreditMapping[$subId]){
                $lessAmountFlag = 1;
                 $errorMsg .= $subId.",";
            }

        }

        if($lessAmountFlag==1){
            $errorMsg = substr($errorMsg, 0,-1);
            return array("responseText"=>"0","error"=>$errorMsg);
        }

        $dbIdSubsIdMap = array();
        $index = 0;
        $BaseProdRemainingQuantity = $data['BaseProdRemainingQuantity'];
        foreach ($subscriptionData as $data) {
            $insertId = $mailerSchedulerModel->lockCredits($data['subscriptionId'],$data['userSet'],$BaseProdRemainingQuantity,$index);
            $dbIdSubsIdMap[$insertId] = $data['subscriptionId'];
            $index +=1;
        }
        
        $updateMailerData = $this->insertRestDataIntoMailer($details,$campaignName,$templateMappings,$dbIdSubsIdMap,$campaignId,$isEdit);
        $campaignId = $updateMailerData["campaignId"];
        unset($updateMailerData["campaignId"]);
        $this->updateMailerInLockedAmountTable($updateMailerData);        
        
        return array("responseText"=>"1","campaignId"=>$campaignId);
    }

    function updateMailerInLockedAmountTable($updateMailerData){
        if(empty($updateMailerData)){
            return false;
        }

        $mailerSchedulerModel = $this->CI->load->model('mailer/mailerschedulermodel');
        foreach ($updateMailerData as $mailerData) {
            $mailerSchedulerModel->updateMailerInLockedAmountTable($mailerData['mailerId'],$mailerData['id']);
        }   
        $mailerSchedulerModel->completeTransaction();
    }

    
    private function insertRestDataIntoMailer($mailerDetails,$campaignName,$templateMappings,$dbIdSubsIdMap,$campaignId,$isEdit){
        $mailerSchedulerModel = $this->CI->load->model('mailer/mailerschedulermodel');
        if ($campaignId <= 0){
            $campaignId = $mailerSchedulerModel->insertCampaignName($campaignName,0);
        }

        $mailerDetails = $this->createDuplicateTemplate($templateMappings,$mailerDetails);
        $mailerIds = array();
        
        if (empty($this->CI->input->post("parentMailerId"))){
            $parentMailerId = $mailerSchedulerModel->saveMailerInformation($mailerDetails["parent"],$campaignId,"",0);
        }
        else{
            $parentMailerId = $this->CI->input->post("parentMailerId");
        }
        if ($this->CI->input->post("resendMailer")!=1){

            $mailerIdToSubscription[] = $parentMailerId;
        }
        foreach ($mailerDetails as $key => $value) {
            if ($key == "parent"){
                continue;
            }
            $childId = $mailerSchedulerModel->saveMailerInformation($value,$campaignId,$parentMailerId,0);
            if (!empty($childId)){
                $mailerIdToSubscription[] = $childId;
            }
        }
      
        if($isEdit == 1){
         $allMailerIds = $this->CI->input->post("mailer_id");
         $mailerIdsToDelete = array_diff($allMailerIds,$mailerIdToSubscription);
         $mailerSchedulerModel->updateUnselectedMailers($mailerIdsToDelete,1);
        }

        $index =0;
        foreach ($dbIdSubsIdMap as $lockTableKey => $subscriptionValue) {
            if(empty($subscriptionValue)){
                continue;
            }
            $returnData[] =array ("id"=>$lockTableKey,"mailerId"=>$mailerIdToSubscription[$index]);
            $index++; 
        }
        $returnData["campaignId"] = $campaignId;
        return $returnData;
    }
	
    private function createDuplicateTemplate($templateMappings,$mailerDetails){
        $mailerSchedulerModel = $this->CI->load->model('mailer/mailerschedulermodel');
        foreach ($templateMappings as $key => $templateId) {
            if ($this->CI->input->post("resendMailer")==1 && $key =='parent'){
                continue;
            }
            if (!empty($templateId)){
                $newTemplateId  = $mailerSchedulerModel->createDuplicateTemplate($templateId);
                $mailerDetails[$key]['templateId'] = $newTemplateId;
                $oldTemplateData = $mailerSchedulerModel->getNewsletterTemplateData($templateId);
                if(!empty($oldTemplateData[0])){
                    $oldTemplateData[0]['date'] = date('Y-m-d H:i:s');
                    $oldTemplateData[0]['templateId'] = $newTemplateId;
                    $mailerSchedulerModel->insertNewsletterData($oldTemplateData);
                }
            }
        }
        return $mailerDetails; 
    }

    public function getMailerDetailsFromPost($index,$mailerName,$indexToCampaignMapping,$mailerCriteria,$clientId,$save,$listId,$isUploadCsv)
    {   
        $mailerSchedulerModel = $this->CI->load->model('mailer/mailerschedulermodel');
        $subscriptionIdIndex = $index+1;        
        $checkBoxIndex = $index+1;
        $timeIndex = $index+1;
        $this->CI->load->config('mailer/mailerConfig');
        $indexToDripMailerType = $this->CI->config->item('indexToDripMailerType');

        if(empty($this->CI->input->post("dripdropdpwn_".$checkBoxIndex)) && $index !=0){
            return ;
        }
        if ($save==1){
            $returnData["mailsSent"] = 'draft';
        }
        else{
            $returnData["mailsSent"] = "false";
        }

        if ($index ==0){
            $returnData["mailerName"] = $mailerName;
             
        }
        else{
            $returnData["mailerName"] = $mailerName.'-'.$indexToCampaignMapping[$index];
            $returnData["dripMailerType"] =  $indexToDripMailerType[$index];
        }
        if (!empty($this->CI->input->post('mails_limit_text_'.$mailerCriteria))){
            $userCount = $this->CI->input->post('mails_limit_text_'.$mailerCriteria);
        }
        else{
            $userCount = $this->CI->input->post("user_count_".$mailerCriteria);
        }

        if (empty($this->CI->input->post("mailSchedule_".$mailerCriteria)) && $index ==0){
            $returnData["time"] = date('Y-m-d H:i:s');
        }
        else{
            $returnData["time"] = $this->CI->input->post("mailer_start_date_".$mailerCriteria."_".$timeIndex)." ".$this->CI->input->post("mail_start_hours_".$mailerCriteria."_".$timeIndex).":".$this->CI->input->post("mail_start_minutes_".$mailerCriteria."_".$timeIndex).":00";
        }

        
        if ($index!=0)
        {
            $returnData["time"] = $this->CI->input->post("mailer_start_date_".$mailerCriteria."_".$timeIndex)." ".$this->CI->input->post("mail_start_hours_".$mailerCriteria."_".$timeIndex).":".$this->CI->input->post("mail_start_minutes_".$mailerCriteria."_".$timeIndex).":00";
        }
        
        $mailerModel = $this->CI->load->model('mailer/mailermodel');

        $userData = $this->CI->cmsUserValidation();
        $userGroupData = $mailerModel->getUserGroupInfo($userData['userid']);

        $returnData["group_id"] = $userGroupData["group_id"];
        $returnData["batch"] = $userGroupData["group_id"]+2;
        $returnData["usergroup"] = $userData['usergroup'];
        $returnData["userId"] = $userData['userid'];
        $returnData["subject"] = $this->CI->input->post('subject_name')[$index];
        $returnData["totalUsersInCriteria"] = $this->CI->input->post("user_count_".$mailerCriteria);
        $returnData["templateId"] = $this->CI->input->post("selected_mail_template")[$index];           
        $returnData["senderName"] = $this->CI->input->post("sender_name")[$index];
        $returnData["id"] = $this->CI->input->post("mailer_id")[$index];
        $returnData["senderMail"] = $this->CI->input->post("sendor_email_id");
        $returnData["totalMailsToBeSent"] = $userCount;
        $returnData["clientId"] = $clientId;
        if ($isUploadCsv == 0){
         
            $returnData["criteria"] = $this->getUserSetCriteriaFromPost($mailerCriteria);
        }
        else{
             $returnData["criteria"] ="";
             $returnData["listId"] = $listId;
            if($save){
                $returnData["totalUsersInCriteria"] = 0;
                $returnData["totalMailsToBeSent"] = 0;
            }
        }

        if ($this->CI->input->post("resendMailer")==1){
             $returnData["criteria"] =$this->CI->input->post("criteria_1");
             $returnData["listId"] = $this->CI->input->post("listId");
        }

        $subscriptionData["subscriptionId"] = $this->CI->input->post('subscriptionId_'.$mailerCriteria.'_'.$subscriptionIdIndex);
        $subscriptionData["userSet"] = $userCount;
        
        unset($userData);
        return array("mailerData"=>$returnData,"subscriptionData"=>$subscriptionData,"templateMappings"=>$templateMappings);
    }

    private function getUserSetCriteriaFromPost($mailerCriteria)
    {
        if ($this->CI->input->post("resendMailer")==1){
            return $this->CI->input->post('criteria_'.$mailerCriteria);
        }
        $criteria = $this->CI->input->post('userset_'.$mailerCriteria);
        $includeArray = $this->removeZeroInArray($criteria[0][0]);
        $excludeArray = $this->removeZeroInArray($criteria[1][0]);
        $finalCriteria = implode('OR', array_unique($includeArray));
        if (empty($finalCriteria)){
            return "";
        }
        $excludeString = implode('OR',array_unique($excludeArray));
        if(!empty($excludeString)){
            $finalCriteria.= "EXCLUDE".$excludeString;
        }
        return $finalCriteria;

    }

    private function removeZeroInArray($data){
        foreach ($data as $key => $value) {
            if ($value > 0){
                $newArray[] = $value;
            }
        }
        return $newArray;
    }

    public function getParentMailerDetailsForResending($parentMailerId,$indexToCampaignMapping){
        $this->__construct();
        $mailerschedulermodel = $this->CI->load->model('mailer/mailerschedulermodel');
        $mailerData = $mailerschedulermodel->getParentMailerChildDetails($parentMailerId);
        

        $indexToCampaignMappingReverse = array_flip($indexToCampaignMapping);
        foreach ($mailerData as $key => $childMailer) {
            $childName[] = explode('-',$childMailer['mailerName'])[1];
        }
       
        foreach ($childName as $key => $name) {
            $indexToHide[] = $indexToCampaignMappingReverse[$name];
        }
        $parentMailerData = $mailerschedulermodel->getParentMailerDetails($parentMailerId);
        $data["parentMailerData"] = $parentMailerData;
        if ($parentMailerData['listId'] > 0)
        {
            $data['csvList'] = "CSV Uploaded !!"; 
        }

        if ($parentMailerData['criteria']){
            list($include,$exclude) = explode('EXCLUDE',$parentMailerData['criteria']);
            $data['includedUserSet'] = $this->getUserSetName($include);
            $data['excludedUserSet'] = $this->getUserSetName($exclude);
               
        }
        $data["indexToHide"] = $indexToHide;

        $this->CI->load->config('mailer/mailerConfig');
        $mailerAdminUserId = $this->CI->config->item('mailerAdminUserId');    
        $MMM_BaseProductId = $this->CI->config->item('MMM_BaseProductId');    

        if($parentMailerData['clientId'] != $mailerAdminUserId) {
            $sumsmodel = $this->CI->load->model('sumsmodel','sums');
            $data['subscriptionDetails'] = $sumsmodel->getAllSubscriptionsForUser($parentMailerData['clientId'], $MMM_BaseProductId);
        }
        return $data;
     }

    public function updateOpenAndClickRateInES(){
        $verificationmodel = $this->CI->load->model('userVerification/verificationmodel');
        $lastMisQueueId = $verificationmodel->getLastProcessedTrackingId('MAILER_MIS_DATA_FOR_ES');
        $mailerSchedulerModel = $this->CI->load->model('mailer/mailerschedulermodel');
        $mailerschedulermodel = new mailerschedulermodel();

        $misData = $mailerSchedulerModel->getMailMisDataForES($lastMisQueueId);
        if(empty($misData)){
            return;
        }

        $mailerScheduleTime = $this->getMailerScheduleTime($misData);

        $ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
        $this->clientConn5   = $ESConnectionLib->getESServerConnectionWithCredentials();

        foreach ($misData as $data) {
            if($data['id']>$lastMisQueueId){
                $lastMisQueueId = $data['id'];
            }

            if(empty($data['trackingType'])){
                continue;
            }

            $trackingType = $data['trackingType'];
            $routingKey = $this->getRoutingKeyForES($data['mailerid']);

            if($mailerScheduleTime[$data['mailerid']]['year']<2019){
                continue;    
            }else if($mailerScheduleTime[$data['mailerid']]['year'] == 2019){
                if($mailerScheduleTime[$data['mailerid']]['month']<8){
                    continue;
                }
            }

           

            $elasticQuery = $this->searchQueryForMailerDataOnES($mailerScheduleTime,$data,$trackingType,$routingKey);

            $result = $this->clientConn5->search($elasticQuery);
            $doc_id = '';
            if(!empty($result['hits']['hits'])){
                foreach ($result['hits']['hits'] as $key => $ElasticsearchDoc) {
                    $doc_id = $ElasticsearchDoc['_id'];
                    unset($result['hits']['hits'][$key]);
                }
            }

            if(!empty($doc_id)){
                $this->updateMailerDocumentOnES($mailerScheduleTime,$data,$trackingType,$routingKey,$doc_id);
            }
        }

        $verificationmodel->updateLastProcessedTrackingId($lastMisQueueId,'MAILER_MIS_DATA_FOR_ES');

    }

    /*funtion to index sent mails data to Elastic Search*/
    public function indexMMMMailerES(){
        $verificationmodel = $this->CI->load->model('userVerification/verificationmodel');
        $lastMailQueueId = $verificationmodel->getLastProcessedTrackingId('MAILER_DATA_FOR_ES');
        
        $mailerSchedulerModel = $this->CI->load->model('mailer/mailerschedulermodel');
        $mailerschedulermodel = new mailerschedulermodel();

        $mailsData = $mailerschedulermodel->getMailQueueDataForES($lastMailQueueId);

        $mailerScheduleTime = $this->getMailerScheduleTime($mailsData);
        
        foreach ($mailsData as $key => $value) {
            if($value['mailid']>$lastMailQueueId){
                $lastMailQueueId = $value['mailid'];
            }
            if($value['mailerid']==0 || $value['userid']==0){
                unset($mailsData[$key]);
                continue;
            }

            $mailsData[$key]['routingKey'] = $this->getRoutingKeyForES($value['mailerid']);
            $mailsData[$key]['isClick'] = 0;
            $mailsData[$key]['isOpen'] = 0;
            $lastMailQueueId =$value['mailid'];
            $mailsData[$key]['indexName'] = MMM_INDEX_NAME.'_'.$mailerScheduleTime[$value['mailerid']]['month'].'_'.$mailerScheduleTime[$value['mailerid']]['year'];
        }
        $indexType     = 'mmm_mail';
        $this->insertDocumentsinES($mailsData,$indexType);

        $verificationmodel->updateLastProcessedTrackingId($lastMailQueueId,'MAILER_DATA_FOR_ES');
   }

   public function getRoutingKeyForES($mailerId){

       $this->CI->load->config('mailer/mailerConfig');
       $routingKey = $this->CI->config->item('routingKey');  

       $lastDigitofMailerId = $mailerId%10;
       return $routingKey[$lastDigitofMailerId];
   }

   private function insertDocumentsinES($indexData,$indexType){
        $ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
        $this->clientConn5   = $ESConnectionLib->getESServerConnectionWithCredentials();        

        $chunkCounter = 0;
        $params       = array();

        foreach ($indexData as $key => $data) {
            $indexName = $data['indexName'];
            unset($data['indexName']);
         
                $params['body'][] = array(
                                        'index' => array(
                                            '_index' => $indexName,
                                            '_type' => $indexType,
                                            '_id' => $data['mailid'],
                                            '_routing'=>$data['routingKey']
                                          )
                                        ); 

            unset($data['routingKey']);


            $params['body'][] = $data;
            
            $chunkCounter++;
            if($chunkCounter % 100 == 0){
                $response = $this->clientConn5->bulk($params);
                $params = array();
                $params['body'] = array();
                unset($response);                
            }            
        }

        if (!empty($params['body'])) {
            $response = $this->clientConn5->bulk($params);
        }

        return $response;      

    }

   public function getsavedMailerData($mailerId, $isDripCampaignDataRequired = true, $status = 'draft'){
        $mailSchedulerModel = $this->CI->load->model('mailer/mailerschedulermodel');
        $parentMailerData = $mailSchedulerModel->getDataForParentMailer($mailerId, $status);

        if($isDripCampaignDataRequired === true) {
            $dripMailerData = $mailSchedulerModel->getDataForChildMailer($mailerId, $status);
            $mailerData = array_merge($parentMailerData,$dripMailerData);
        } else {
            $mailerData = $parentMailerData;
        }

        $campaignName = $mailSchedulerModel->getCampaignName($mailerData[0]['campaignId']);
        $savedMailerData = array();
        $savedMailerData['campaignName'] =  $campaignName ;
        foreach ($mailerData as $mailer) {
            $data = array();
            $criteria = $mailer['criteria'];
            if(!empty($criteria)){
               $criteria = $this->parseUserSetCriteria($criteria);
               $mailer['criteria'] = $criteria ;
            }
            if(empty($mailer['parentMailerId']) || $isDripCampaignDataRequired == false) {
                $savedMailerData['parentMailer'] = $mailer;
            } else {
                $savedMailerData['dripMailer'][$mailer['dripMailerType']] = $mailer;
            }
        }
        return $savedMailerData;
    }

    private function parseUserSetCriteria($criteria){
        $criteria = explode("EXCLUDE",$criteria);
        $criteriaSize = sizeof($criteria);
        $parsedUserSetCriteria = array();
        foreach ($criteria as $key => $criteriaValue) {
            $userSetIds = explode("OR",$criteriaValue);
            if($key == 0){
               $parsedUserSetCriteria["ADD_USERSET"] =  $userSetIds;
            } else {
               $parsedUserSetCriteria["EXCLUDE_USERSET"]= $userSetIds;
            }
        }   
        return $parsedUserSetCriteria;
    }

	private function getMailerScheduleTime($misData){
        $mailerIds = array();
        foreach ($misData as $data) {
            if(!empty($data['mailerid'])){
                $mailerIds[$data['mailerid']] = $data['mailerid'];
            }
        }

        $mailerModel = $this->CI->load->model('mailer/mailermodel');
        $mailerData = $mailerModel->getScheduleTimeForMailer($mailerIds);
        $mailerScheduleTime = array();
        foreach ($mailerData as $data) {
            if($data['month']<10){
                $data['month'] = '0'.$data['month'];
            }
            $mailerScheduleTime[$data['id']]['month'] = $data['month'];
            $mailerScheduleTime[$data['id']]['year'] = $data['year'];
        }

        return $mailerScheduleTime;
    }

    private function searchQueryForMailerDataOnES($mailerScheduleTime,$data,$trackingType,$routingKey){
        $elasticQuery = array();
        $elasticQuery['index'] = MMM_INDEX_NAME.'_'.$mailerScheduleTime[$data['mailerid']]['month'].'_'.$mailerScheduleTime[$data['mailerid']]['year'];
        $elasticQuery['type'] = 'mmm_mail';
        $elasticQuery['routing'] = $routingKey;

        $elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['mailid'] = $data['mailid'];

        if($trackingType=='open'){
            $elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['isOpen'] = 0;
        }

        if($trackingType=='click'){
            $elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['isClick'] = 0;
        }

        return $elasticQuery;
    }

    private function updateMailerDocumentOnES($mailerScheduleTime,$data,$trackingType,$routingKey,$doc_id){
        if($trackingType=='click'){
            $trackingType = 'isClick';
        }
        else{
            $trackingType = 'isOpen';
        }
        $params = [
            'index' => MMM_INDEX_NAME.'_'.$mailerScheduleTime[$data['mailerid']]['month'].'_'.$mailerScheduleTime[$data['mailerid']]['year'],
            'type'  => 'mmm_mail',
            'id'    => $doc_id,
            'routing' => $routingKey,
            'body' => [
                'doc' => [
                    $trackingType => 1
                ]
            ]
        ];

        $response = $this->clientConn5->update($params);
    }

    private function getUserSetName($userSet){
        if (empty($userSet)){
            return;
        }
        else {
            $mailSchedulerModel = $this->CI->load->model('mailer/mailerschedulermodel');
            $userSetName = $mailSchedulerModel->getUserSetName(explode('OR',$userSet));
            $userSetName  =array_column($userSetName,'name');
            return implode(',',$userSetName);
        }
    }

}
?>
