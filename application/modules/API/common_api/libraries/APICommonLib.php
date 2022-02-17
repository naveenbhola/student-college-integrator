<?php
class APICommonLib
{
    private $CI;
    
    function __construct() {
        $this->CI = &get_instance();

        $this->apicommonmodel = $this->CI->load->model("common_api/apicommonmodel");
    }
    
    /**
     * Function to store tagIds for each thread in Cache(Redis)
     * @param	int entityId : Id of entity for which tags are to be stored. 
     * @param 	string entityType : type of entity for which tags are to be stored.
     * @param	array tagIds : array of tagIds for which tags are to be stored.
     * @example	$entityId=987, $entityType='discussion', $tagIds=array(12,453,535)
     */
    public function storeTagForThreadInCache($entityId, $entityType, $tagIds = array()){
    	try {
    		Contract::mustBeNumericValue($entityId, 'Entity Id');
    		Contract::mustBeNonEmptyVariable($entityType, 'Entity Type');
    		Contract::mustBeNonEmptyArrayOfIntegerValues($tagIds, 'Tag Ids');
    		if (!in_array($entityType, array('discussion','question'))){
    			throw new InvalidArgumentException('Entity Type not Supported for this action');
    		}
    		$predisLib = PredisLibrary::getInstance();//$this->CI->load->library('common/PredisLibrary');
    		$predisLib->setPipeLine();
    		$predisLib->deleteKey(array('threadTags:thread:'.$entityId), TRUE);
    		$predisLib->addMembersToSet('threadTags:thread:'.$entityId, $tagIds, TRUE);
    		$predisLib->executePipeLine();
    	} catch (Exception $e) {
    		error_log(' :: Error Occured :: '.$e->getMessage().' ::: Traces ::: '.$e->getTraceAsString());
    	}
    }

    function getUserLogOutFlagStatus($userId){

        return $this->apicommonmodel->getUserLogOutFlagStatus($userId);
    }

    function resetUserLogOutFlag($userId){

        $this->apicommonmodel->resetUserLogOutFlag($userId);
    }

    function getSuggestedInstitutes($answerIds){
        $suggestionFinalArray = array();
        
        //Fetch the Suggested Institutes with each Answer to be displayed
        $this->CI->load->model('QnAModel');
        $suggestionArray = $this->CI->QnAModel->getSuggestedInstitutes($answerIds);
        if(count($suggestionArray) > 0){
            foreach ($suggestionArray as $suggestion){
                    $answerId = $suggestion['answerId'];
                    $instituteDetails = $this->getInstituteDetails(intval($suggestion['suggestionId']));
                    $suggestionFinalArray[$answerId][] = array($suggestion['suggestionId'],$instituteDetails[0],$instituteDetails[1]);
            }
        }
        return $suggestionFinalArray;
    }

    function getInstituteDetails($instituteId){
        if($instituteId>0){
                //$this->CI->load->builder('ListingBuilder','listing');
                //$listingBuilder = new ListingBuilder;
                //$instituteRepository = $listingBuilder->getInstituteRepository();
        		$this->CI->load->builder("nationalInstitute/InstituteBuilder");
                $instituteBuilder = new InstituteBuilder();
                $instituteRepository = $instituteBuilder->getInstituteRepository();       

                $institute = $instituteRepository->find($instituteId);
                if ($institute->getId() > 0){
                    return array($institute->getName(),$institute->getURL());
                }
                return array();
        }
    }

    function getSuggestedInstituteHTML($suggestionArray){
	$html = '';
        if( count($suggestionArray)>0 ){
                $html .= "<br/><br/>The user recommends following institutes";
                foreach ($suggestionArray as $suggestion){
                    if(isset($suggestion[1]) && $suggestion[1]!=''){
                        $html .= "<br/><a href='".$suggestion[2]."'>".$suggestion[1]."</a>";
                    }
                }
        }
	return $html;
    }

    /**
    * API to check whether user is active on APP or NOT
    * @param userId Integer UserId for whom we need to check
    * @return true = if user is active , false = if user is NOT ACTIVE
    * ACTIVE MEANS LAST NO API HIT on last 14 DAYS
    */
    function isUserActiveOnApp($userId = 0,$activeDuration='14 days'){
        $date = date("Y-m-d", strtotime("-".$activeDuration));
        $result = $this->apicommonmodel->checkIfUserActive($userId,$date);
        return $result;
    }
    
    /**
     * This function is responsible for tracking view for threads.
     * Data will be stored in Redis which will further be processed by cron
     * @return boolean
     */
    function trackThreadView($userId, $visitorId, $data, $pageType, $pageSource){
    	$identificationKey = '';
    	if(!empty($userId)){
    		$identificationKey = $userId;
    	}elseif (!empty($visitorId)){
    		$identificationKey = $visitorId;
    	}
    	if($identificationKey == '' || empty($data)) {
    		return FALSE;
    	}
    	$viewDataForCache = array();
    	foreach ($data as $threadIds){
            $threadIds = explode(":", $threadIds);
            if(!isset($threadIds[0])){
            	$threadIds[0] = 0;
            }
            if(!isset($threadIds[1])){
            	$threadIds[1] = 0;
            }
    		$key = $identificationKey.':'.$threadIds[0].':'.$threadIds[1].':'.$pageType.':'.$pageSource;
    		$viewDataForCache[$key] = 1;
    	}
    	$predisLibrary = PredisLibrary::getInstance();
        if(!(empty($viewDataForCache) || count($viewDataForCache) == 0)){
            $predisLibrary->incrementValueOfMembersOfSortedSet('threadViewTracking',$viewDataForCache);
        }
    	return TRUE;
    }
    
    /**
     * Data stored in redis in format <userId/visitorId>:<threadId>:<answer/commentId>:<pageType>:<pageSource>
     * @return boolean
     */
    function updateViewCountOfThreads(){
    	$predisLibrary          = PredisLibrary::getInstance();
    	$dataToProcess          = $predisLibrary->getMembersByRangeInSortedSet('threadViewTracking', 1, "+inf", TRUE, TRUE);
    	$dbViewInsertData		= array();
    	$dbLogInsertData		= array();
    	$redisUpdateViewCount 	= array(); // this array is for updating view count again in redis i.e. subtract view count
    	$userViewedMessage		= array();
    	foreach ($dataToProcess as $viewRelatedData => $viewCount){
    		$redisUpdateViewCount[$viewRelatedData] = "-".$viewCount;
    		
    		$data = explode(":", $viewRelatedData);
    		
    		$temp = array();
    		if(ctype_digit($data[0])){
    			$temp['entityType'] = 'user';
    		}else{
    			$temp['entityType'] = 'visitor';
    		}
    		$temp['entityId']	= $data[0];
    		$temp['pageType']	= $data[3];
    		$temp['pageSource'] = $data[4];
    		
    		if(ctype_digit($data[1]) && $data[1] > 0){
    			if(!isset($userViewedMessage[$data[0]]) || !in_array($data[1], $userViewedMessage[$data[0]])){ // add only if entry for this user is not already made
    				(isset($dbViewInsertData[$data[1]]))?++$dbViewInsertData[$data[1]]:$dbViewInsertData[$data[1]] = 1; // This data is to insert in messageTable
    				$userViewedMessage[$data[0]][] = $data[1]; // mark as this user view has been recorded for this thread. It will not be added again for this thread for this user
    			}
    			$temp['msgId']		= $data[1]; // This is msgId for threadViewTrackingLog
    			$dbLogInsertData[]	= $temp;	// This data is to insert in threadViewTrackingLog
    		}
    		if(ctype_digit($data[2]) && $data[2] > 0){
    			if(!isset($userViewedMessage[$data[0]]) || !in_array($data[2], $userViewedMessage[$data[0]])){ // add only if entry for this user is not already made
    				(isset($dbViewInsertData[$data[2]]))?++$dbViewInsertData[$data[2]]:$dbViewInsertData[$data[2]] = 1; // This data is to insert in messageTable
    				$userViewedMessage[$data[0]][] = $data[2]; // mark as this user view has been recorded for this thread. It will not be added again for this thread for this user
    			}
    			$temp['msgId']		= $data[2];	// This is msgId for threadViewTrackingLog
    			$dbLogInsertData[]	= $temp;	// This data is to insert in threadViewTrackingLog
    		}
    	}
    	//$dbViewInsertData = array_unique($dbViewInsertData);
    	$this->apicronmodel = $this->CI->load->model('common_api/apicronmodel');
    	
    	//$liveClosedMsgIds = $this->apicronmodel->getLiveClosedMessages(array_keys($dbViewInsertData));
    	
    	/* if(count($liveClosedMsgIds) > 0){ // if there are some invalid messageIds, then we will remove those messageIds
    		foreach ($dbLogInsertData as $key => $data){
    			if(!in_array($data['msgId'], $liveClosedMsgIds)){
    				unset($dbLogInsertData[$key]); // unset data from threadViewTrackingLog insert array
    				unset($dbViewInsertData[$data['msgId']]); // unset data from messageTable update view count array
    			}
    		}
    	} */
    	
    	// update view count in message table
    	$this->apicronmodel->updateViewCountOfThreads($dbViewInsertData);
    	
    	// insert view data in threadViewTrackingLog table
    	$this->apicronmodel->insertThreadViewLogData($dbLogInsertData);
    	
        if(!(empty($redisUpdateViewCount) || count($redisUpdateViewCount) == 0)){
            $predisLibrary->incrementValueOfMembersOfSortedSet('threadViewTracking', $redisUpdateViewCount);
        }
        
        $predisLibrary->removeMembersInSortedSetByScore('threadViewTracking',"-inf",0);
    	return TRUE;
    }
    

}
