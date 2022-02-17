<?php

class abroadnotificationsmodel extends MY_Model{
    private $dbHandle = '';
    private $dbHandleMode = '';
    
    
    public function __construct() {
        parent::__construct('ShikshaApply'); // compatibility with shiksha apply crm
    }
    
    // function to be called for getting dbHandle with read/write mode
    private function initiateModel($mode='read'){
        if($this->dbHandle && $this->dbHandleMode == 'write'){
            return ;
        }
        $this->dbHandleMode = $mode;
        if($mode == 'write'){
            $this->dbHandle = $this->getWriteHandle();
        }elseif ($mode == 'read') {
            $this->dbHandle = $this->getReadHandle();
        }
    }

	/*
	 * add a notification record to studyAbroadUserNotifications
	 * @params: single record having userId,listingType,listingTypeId,notification,notificationType
	 */
	public function addStudyAbroadUserNotification($data, $isTransactionActive = false)
	{
		$this->initiateModel('write');
		if(!$isTransactionActive){
			$this->dbHandle->trans_start();
		}
		if(empty($data['addedOn'])){
			$data['addedOn'] = date( 'Y-m-d H:i:s');
		}
		if($data['userId']>0)
		{
			// insert new row
			$this->dbHandle->insert('studyAbroadUserNotifications', $data);
		}
		
		if(!$isTransactionActive){
			$this->dbHandle->trans_complete();
			
			if ($this->dbHandle->trans_status() === FALSE)
			{
			throw new Exception('Transaction Failed');
			return true;
			}
		}
	}
	/*
	 * add a notification record to studyAbroadUserNotifications
	 * @params: userId
	 */
	public function getStudyAbroadUserNotification($userId)
	{
		$this->initiateModel();
		// query
		$this->dbHandle->select('id, userId, listingType, listingTypeId, notification, notificationType, addedOn, isViewed');
		$this->dbHandle->from('studyAbroadUserNotifications');
		$this->dbHandle->where('userId',$userId); //not type/listingType
		$this->dbHandle->order_by('id','desc');
		$results = $this->dbHandle->get()->result_array();
		return $results;
	}
	/*
	 * set isviewed & viewedon in studyAbroadUserNotifications table
	 */
	public function markNotificationsAsViewed($userId, $isTransactionActive = false)
	{
		if(!($userId>0))
		{
			return false;
		}
		$this->initiateModel('write');
		if(!$isTransactionActive){
			$this->dbHandle->trans_start();
		}
		// update 
		$udata = array(
			   'isViewed' => 1,
			   'viewedOn'=>date('Y-m-d H:i:s')
			);
		$this->dbHandle->where('isViewed=0','',false); // so that viewedOn date is not changed for old 'seen' notifications
		$this->dbHandle->where('userId', $userId);
		$this->dbHandle->update('studyAbroadUserNotifications', $udata);
		if(!$isTransactionActive){
			$this->dbHandle->trans_complete();
			
			if ($this->dbHandle->trans_status() === FALSE)
			{
			throw new Exception('Transaction Failed');
			return true;
			}
		}
	}
}
