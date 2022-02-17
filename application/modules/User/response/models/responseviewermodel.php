<?php
/** 
 * Model for database related operations related to response.
*/

class responseviewermodel extends MY_Model {
	/**
	 * Variable for DB Handling
	 * @var object
	 */
	private $dbHandle = '';

	/**
	 * Constructor Function
	 */
	function __construct(){
		parent::__construct('User');
	}
	
	/**
	 * Function to initiate the Model
	 * 
	 * @param string $operation
	 *
	 */
	private function initiateModel($operation = 'read'){
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		} else {
        	$this->dbHandle = $this->getWriteHandle();
		}
	}

	public function getUserExamResponseSubscriptions($clientId){
		$this->initiateModel('read');
		$this->dbHandle->select('id as subscriptionId,examId, groupIds, startDate, endDate, campaignType, quantityDelivered');
		$this->dbHandle->where('status', 'active');
		$this->dbHandle->where('clientId',$clientId);
		$clientExamResponses = $this->dbHandle->get('examResponseSubscription')->result_array();		
		return $clientExamResponses;
	}

	public function getAllocatedResponsesForSubscriptionByCriteria($subscriptionId, $start='', $count='', $timeInterval = "none", $responseIds){
		// if(empty($subscriptionId)){
		// 	return false;
		// }

		$this->initiateModel('read');
		$this->dbHandle->select('SQL_CALC_FOUND_ROWS era.id, era.userId,era.clientId,era.entityValue,tlt.action,tlt.submit_date',false);
		$this->dbHandle->from('examResponseAllocation era');
		$this->dbHandle->join('tempLMSTable tlt','era.tempLmsId = tlt.id','inner');
		if(!empty($subscriptionId)){
			$this->dbHandle->where('era.subscriptionId',$subscriptionId);
		}
		if(!empty($responseIds)){
			$this->dbHandle->where_in('era.id',$responseIds);			
		}

		if($timeInterval !="none"){
			$this->dbHandle->where('tlt.submit_date >= subdate(now(), INTERVAL '.$timeInterval.')','',false);
		}

		if($start >= 0 && $count >=1){
			$this->dbHandle->limit($count, $start);
		}
		
		$this->dbHandle->order_by('era.id','desc');
		$responses = $this->dbHandle->get()->result_array();	
		

		$queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
		$queryTotal = $this->dbHandle->query($queryCmdTotal);
		$queryResults = $queryTotal->result();
		$totalRows = $queryResults[0]->totalRows;
		//error_log("Exclusion : ".print_r($this->dbHandle->last_query(),true));
		$resultData = array(
			'responses' => $responses,
			'totalResponses' => $totalRows
			);
		return $resultData;
	}

	public function getGroupExamDetails($groupIds){
		if(empty($groupIds) || !is_array($groupIds) || count($groupIds) <=0){
            return false;
        }

        $this->initiateModel('read');
        $this->dbHandle->select('epg.groupId, epg.groupName,epm.name');
        $this->dbHandle->from('exampage_groups epg');
        $this->dbHandle->join('exampage_main epm','epg.examId = epm.id','inner');
        $this->dbHandle->where_in('groupId',$groupIds);
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();
        return $result;
	}

	public function getSubscriptionDetails($subscriptionId, $subscriptionStatus){
		if(empty($subscriptionId) || $subscriptionId <=0){
			return false;
		}

		if(!in_array($type, array("active","expired","inactive","deleted"))){
          return false;
        }

		$this->initiateModel('read');
		$this->dbHandle->select('id, clientId, examId, groupIds, status, quantityDelivered');
		$this->dbHandle->from('examResponseSubscription');
		$this->dbHandle->where('id',$subscriptionId);
		if($subscriptionStatus == 'live'){
			$this->dbHandle->where('status','active');
		}else{
			$this->dbHandle->where_in('status',array('inactive','deleted'));
		}
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();
		return $result;
	}

	function getClientUserCommHistory($clientId, $userIds){

		if(empty($clientId) || $clientId <0){
			return false;
		}

		if(!is_array($userIds) || count($clientId) <0){
			return false;
		}

		//_p($clientId/);_p($userIds);die;
		$this->initiateModel('read');
		$this->dbHandle->select('recipientId, max(contactDate) as contactDate');
		$this->dbHandle->from('trackCommunicationHistory');
		$this->dbHandle->where('senderId',$clientId);
		$this->dbHandle->where_in('recipientId',$userIds);
		$this->dbHandle->where('product','examResponseViewer');
		$this->dbHandle->where('communication','email');
		$this->dbHandle->group_by('recipientId');
		$result = $this->dbHandle->get()->result_array();
		//echo $this->dbHandle->last_query();die;
		return $result;
	}

	function getListingData($listingIds,$listingType,$status){
		$this->initiateModel('read');
		$this->dbHandle->select('listing_type_id,listing_title, status');
		$this->dbHandle->from('listings_main');
		$this->dbHandle->where_in('listing_type_id',$listingIds);

		$this->dbHandle->where('listing_type',$listingType);
		$this->dbHandle->where_in('status',$status);
		$result = $this->dbHandle->get()->result_array();
		return $result;
	}
}
