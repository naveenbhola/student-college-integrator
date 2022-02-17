<?php

class systemmailermodel extends MY_Model
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
    
    public function track($mailer,$userId,$url, $activity_type, $email_id, $mailer_id, $mailId)
    {
        $this->initiateModel('write');
        $data = array(
            'mailer' => $mailer,
            'userId' => $userId,
            'url' => $url,
            'date' => date('Y-m-d H:i:s'),
			'activity_type' => $activity_type,
			'mailer_id' => $mailer_id,
			'email_id' => $email_id
        );
        if(is_numeric($mailId) && $mailId>0){
            $data['mail_id'] = $mailId;
        }
        
        $this->dbHandle->insert('shikshaMailerMis',$data);
        $rowId = $this->dbHandle->insert_id();
        return $rowId;
    }
	
	public function get_mailer_details_by_name($mailer_name)
    {
        $this->initiateModel('read');
		$sql = "SELECT * from shikshaMailers where mailer_name = ?";
		$result = $this->dbHandle->query($sql, array($mailer_name))->row_array();
		return $result;
    }
	
	public function get_mail_content($mail_id, $email_id)
    {
        $this->initiateModel('read');
		$sql = "SELECT * from tMailQueue where id = ? and toEmail = ?";
		$result = $this->dbHandle->query($sql, array($mail_id, $email_id))->row_array();
		return $result;
    }
	
	public function update_mail_content($mail_content, $isSent, $mail_id)
    {
        $this->initiateModel('write');
		$sql = "update tMailQueue set content = ?, isSent = ? where id = ?";
		$this->dbHandle->query($sql, array($mail_content, $isSent, $mail_id));
		return true;
    }

 public function isUserRegisteredForApp($userId){

        $dbHandle = $this->getReadHandle();
        $sql      = "SELECT userId from appUserLastActivity where userId = ? ";
        $rs       = $dbHandle->query($sql, array($userId));

        $userIdArray = array();
        foreach($rs->result_array() as $row)
        {
            $userIdArray[] = $row['userId'];
        }

        if(empty($userIdArray))
            return false;
        else
            return true;
    }

    public function updateMailIdInMailerMis($mailerMisId, $mailId){
        if($mailerMisId >0 && $mailId >0){
            $this->initiateModel('write');
            $data = array('mail_id' => $mailId);
            $this->dbHandle->where('id', $mailerMisId);
            $this->dbHandle->update('shikshaMailerMis',$data);
        }
    }


}