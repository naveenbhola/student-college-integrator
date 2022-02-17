<?php
class eventandexammodel extends MY_Model {
    private $dbHandle = '';
    private $dbHandleMode = '';
    
    function __construct() {
		parent::__construct();
    }
    
    private function initiateModel($mode = "write"){
        if($this->dbHandle && $this->dbHandleMode == 'write') {
            return;
        }
        $this->dbHandleMode = $mode;
        $this->dbHandle = NULL;
        if($mode == 'read') {
                $this->dbHandle = $this->getReadHandle();
        } else {
                $this->dbHandle = $this->getWriteHandle();
        }
    }

    function getAllExamResponsesByInterval(){
		$this->initiateModel('read');
		$this->dbHandle->select('userId, listing_type_id, tracking_keyid');
		$this->dbHandle->from('tempLMSTable');
		$this->dbHandle->where('listing_type', 'exam');
		$this->dbHandle->where('action !=', 'exam_viewed_response');
		$this->dbHandle->where('submit_date > DATE_SUB(NOW(), INTERVAL 61 MINUTE)', NULL, false);
		$this->dbHandle->group_by('userId, listing_type_id');
		$result = $this->dbHandle->get()->result_array();
		$finalRes = array();
		foreach ($result as $value) {
			$finalRes['responses'][$value['listing_type_id']][$value['userId']] = $value['userId'];
			$finalRes['groupIds'][$value['listing_type_id']] = $value['listing_type_id'];
			$finalRes['ctaId'][$value['userId'].'_'.$value['listing_type_id']] = $value['tracking_keyid'];
		}
		return $finalRes;
    }

    function checkIfExamAlreadySubscribed($userIdArr, $groupId, $streamId = 0){
    	$this->initiateModel('read');
    	$this->dbHandle->select('userId');
    	$this->dbHandle->from('eventCalendar_subscriptions');
    	$this->dbHandle->where_in('userId', $userIdArr);
    	$this->dbHandle->where('exam_group_id', $groupId);
    	$this->dbHandle->where('status', 'live');
    	if($streamId > 0){
    		$this->dbHandle->where('streamId', $streamId);
    	}
    	$result = $this->dbHandle->get()->result_array();
    	$finalResult = array();
    	foreach ($result as $value) {
    		$finalResult[] = $value['userId'];
    	}
    	return $finalResult;
    }

    function updateModifiedDateForNewExamResponses($userIds, $groupId){
        if(empty($userIds)){
            return;
        }
        $this->initiateModel('write');
        $sql = 'update eventCalendar_subscriptions set modified_date = NOW() where exam_group_id = ? and userId in (?)';
        $this->dbHandle->query($sql, array($groupId, $userIds));
    }

    function getStreamNameForExamCrons($streams){
        $this->initiateModel('read');
        $this->dbHandle->select('stream_id, name');
        $this->dbHandle->from('streams');
        $this->dbHandle->where_in('stream_id', $streams);
        $this->dbHandle->where('status', 'live');
        $result = $this->dbHandle->get()->result_array();
        $finalResult = array();
        foreach ($result as $value) {
            $finalResult[$value['stream_id']] = $value['name'];
        }
        return $finalResult;
    }

    function autoSubscriberUsers($batchInsertData){
    	if(!empty($batchInsertData) && is_array($batchInsertData)){
    		$this->initiateModel('write');
    		$this->dbHandle->insert_batch('eventCalendar_subscriptions',$batchInsertData);
    	}
    }

    function getAllExamResponsesForJeeMain($examGroupId = 113){
    	$this->initiateModel('read');
		$this->dbHandle->select('lms.userId, lms.tracking_keyid, t.city as userCity');
		$this->dbHandle->from('tempLMSTable lms');
		$this->dbHandle->join('tuser t', 'lms.userId=t.userid');
		$this->dbHandle->where('lms.listing_type', 'exam');
		$this->dbHandle->where('lms.listing_type_id', $examGroupId);
		$this->dbHandle->where('lms.action !=', 'exam_viewed_response');
		$this->dbHandle->where('lms.submit_date > DATE_SUB(NOW(), INTERVAL 61 MINUTE)', NULL, false);
		$this->dbHandle->group_by('lms.userId, lms.listing_type_id');
		$result = $this->dbHandle->get()->result_array();
        return $result;
    }

    function getStatesFromCities($cities, $statesToConsider){
        if(empty($cities)){
            return array();
        }
        $this->initiateModel('read');
        $this->dbHandle->select('city_id, city_name, state_id');
        $this->dbHandle->from('countryCityTable');
        $this->dbHandle->where_in('city_id', $cities);
        $this->dbHandle->where_in('state_id', $statesToConsider);
        $result = $this->dbHandle->get()->result_array();
        $finalData = array();
        foreach ($result as $key => $value) {
            $finalData[$value['city_id']] = $value;
        }
        return $finalData;
    }

    function getDelhiNcrCities(){
    	$delhiNcrId = 10223;
    	$this->initiateModel('read');
    	$this->dbHandle->select('city_id');
    	$this->dbHandle->from('virtualCityMapping');
    	$this->dbHandle->where('virtualCityId', $delhiNcrId);
    	$result = $this->dbHandle->get()->result_array();
    	$finalData = array();
    	foreach ($result as $value) {
            $finalData[] = $value['city_id'];
        }
        return $finalData;
    }

    function getOldExamSubscriptions(){
        $this->initiateModel('read');
        $this->dbHandle->select('id, exam_name, category_name, streamId');
        $this->dbHandle->from('eventCalendar_subscriptions');
        $this->dbHandle->where('status', 'live');
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    function updateGroupIdsInEventSubscriptionTable($updateData, $column){
        $this->initiateModel('write');
        if(!empty($updateData)){
            $this->dbHandle->update_batch('eventCalendar_subscriptions', $updateData, $column);
        }
    }
}
