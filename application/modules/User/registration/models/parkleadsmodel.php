<?php

/**
 * File for funciton for ParkLead controller
 */

/**
 * Model class for funciton for ParkLead controller
 */
class parkleadsmodel extends MY_Model
{
    /**
     * @var Object DB Handle
     */
    private $dbHandle;
    
    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct('User');
    }
    
    /**
     * Initiate the model
     *
     * @param string $operation
     */
    private function initiateModel($operation = 'read')
    {
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}
		else{
        	$this->dbHandle = $this->getWriteHandle();
		}
	}
    
    /**
     * Function to get the list of leads
     * @return array $result
     */
    public function getleads()
    {
        $this->initiateModel('write');
        $sql = "SELECT tuf.userId FROM tuserflag as tuf inner join tUserPref tup on tuf.userId = tup.UserId left join tempLMSTable tlr on tuf.userId = tlr.userId where tuf.isLDBUser ='NO' and  (tlr.listing_subscription_type = 'free' OR tlr.id IS NULL) and tup.SubmitDate >= '2014-10-15 18:00:00' and tup.DesiredCourse is not null and (tup.ExtraFlag is null or tup.ExtraFlag != 'studyabroad' ) group by tuf.userId";
		$query = $this->dbHandle->query($sql);
		$result  = $this->getColumnArray($query->result_array(),'userId');
        return $result;
    }
	
	/**
	 * Function to update the LDB User Flag
	 * @param integer $userId
	 */ 
	public function updateflag($userId) {
		$this->initiateModel('write');
		$sql = "update tuserflag set isLDBUser ='YES'  where userId = ?";
		$query = $this->dbHandle->query($sql, array($userId));
	}
}