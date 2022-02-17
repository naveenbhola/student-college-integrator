<?php

class MarketingFormMailerModel extends MY_Model
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
    
    public function saveForm($data)
    {
        $this->initiateModel('write');
        if($data['formId']) {
            $pageData = array(
                                'name' => $data['name'],
                                'modifiedOn' => date('Y-m-d H:i:s')
                            );
            $this->dbHandle->where('id', $data['formId']);
            $this->dbHandle->update('MarketingFormMailers',$pageData);
        }
        else {
			$fid = generateRandomAlphanumericGUID(20);
            $pageData = array(
                                'name' => $data['name'],
                                'createdBy' => $data['userId'],
                                'createdOn' => date('Y-m-d H:i:s'),
                                'modifiedOn' => date('Y-m-d H:i:s'),
								'fid' => $fid,
								'group_id' => $data['group_id']
                            );
            $this->dbHandle->insert('MarketingFormMailers',$pageData);
        }
    }
    
    public function getForms($user_id, $group_id, $user_type)
    {
        $this->initiateModel();
        $sql = "SELECT * FROM MarketingFormMailers";
        if($user_type == 'group_admin') {
        	$sql .= " where group_id = ".$this->dbHandle->escape($group_id);
        } else if($user_type == 'normal_admin') {
			$sql .= " where createdBy = ".$this->dbHandle->escape($user_id);
        }
        $sql .= " order by createdOn desc";
        $query = $this->dbHandle->query($sql);
        return $query->result_array();
    }
    
    public function getForm($formId)
    {
        $this->initiateModel();
        $this->dbHandle->where("id", $formId); 
        $query = $this->dbHandle->get('MarketingFormMailers');
        return $query->row_array();
    }
	
	public function logFormData($type,$rawData,$email = '')
	{
		$this->initiateModel('write');
		
		$time = date('Y-m-d H:i:s');
		$uniqId = md5($time.$email);
		
		$data = array(
			'type' => $type,
			'email' => $email,
			'rawData' => json_encode($rawData),
			'uniqId' => $uniqId,
			'time' => date('Y-m-d H:i:s')
		);
		$this->dbHandle->insert('MarketingFormMailerLog',$data);
		return $this->dbHandle->insert_id();
	}
	
	public function getUniqueLogId($logId)
	{
		$this->initiateModel();
		$sql = "SELECT uniqId FROM MarketingFormMailerLog WHERE id = ?";
		$query = $this->dbHandle->query($sql,array($logId));
		$row = $query->row_array();
		return $row['uniqId'];
	}
	
	public function getMarketingFormRegistrationData($uniqId)
	{
		$this->initiateModel();
		$sql = "SELECT id,email,rawData,status,uniqId FROM MarketingFormMailerLog WHERE uniqId = ? AND type = 'registration'";
		$query = $this->dbHandle->query($sql,array($uniqId));
		$row = $query->row_array();
		if($row['rawData']) {
			$row['formData'] = json_decode($row['rawData'],TRUE);
		}
		return $row;
	}
	
	public function updateLogStatus($logId,$status)
	{
		$this->initiateModel('write');
		
		$sql = "UPDATE MarketingFormMailerLog SET status = ? WHERE id = ?";
		$this->dbHandle->query($sql,array($status,$logId));
	}
	
	public function logMIS($userId,$formData)
	{
		$this->initiateModel('write');
		
		$mfid = $formData['mfid'];
		
		unset($formData['mfid']);
		unset($formData['redirectURL']);
		
		$data = array(
			'userId' => $userId,
			'mfid' => $mfid,
			'formData' => json_encode($formData),
			'time' => date('Y-m-d H:i:s')
		);
		
		$this->dbHandle->insert('MarketingFormMailerMIS',$data);
	}
	
	public function getMISData($mfid)
	{
		$this->initiateModel();
		$sql =  "SELECT a.formData,b.firstName,b.lastName,b.email,b.mobile ".
				"FROM MarketingFormMailerMIS a ".
				"LEFT JOIN tuser b ON a.userId = b.userid ".
				"WHERE a.mfid = ?";
				
		$query = $this->dbHandle->query($sql,array($mfid));
		$results = $query->result_array();
		
		return $results;
	}
}
