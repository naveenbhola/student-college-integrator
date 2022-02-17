<?php

class CronModel extends MY_Model
{
    private $dbHandle = null;
	
	private $cronsForLagMonitoring = array(
			'LeadAllocation'             => array('name' => 'Lead Allocation', 'lagLimit' => 3600),
			'LeadDeliveryEmail'          => array('name' => 'Lead Delivery By Email', 'lagLimit' => 3600),
			'LeadDeliverySMS'            => array('name' => 'Lead Delivery By SMS', 'lagLimit' => 3600),
			'ShikshaEmailDelivery'       => array('name' => 'Shiksha Email Delivery', 'lagLimit' => 3600),
			'ShikshaSMSDelivery'         => array('name' => 'Shiksha SMS Delivery', 'lagLimit' => 3600),
			'HourlyResponseDelivery'     => array('name' => 'Hourly Response Delivery', 'lagLimit' => 10800),
			'MMMClientMailerProcessing'  => array('name' => 'MMM Client Mailer Processing', 'lagLimit' => 86400),
			'MMMProductMailerProcessing' => array('name' => 'MMM Product Mailer Processing', 'lagLimit' => 10800),
			'MMMEmailDelivery'           => array('name' => 'MMM Email Delivery', 'lagLimit' => 10800),
			'Porting'                    => array('name' => 'Lead Porting Delivery', 'lagLimit' => 900),
			'MissedCallResponse'         => array('name' => 'Missed call response', 'lagLimit' => 3600),
			'UserIndexingCron'           => array('name' => 'User Indexing Cron', 'lagLimit' => 600)
        );
    
    function __construct()
	{
		parent::__construct('User');
    }

	private function initiateModel($mode = "write", $module = '')
	{
		if($mode == 'read') {
			$this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
			$this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}
    
	public function getCronsForLagMonitoring()
	{
		return $this->cronsForLagMonitoring;
	}
	
    public function getLag($cron)
    {
		$this->initiateModel('write');
		
		$tenDaysAgo = date('Y-m-d',strtotime('-10 days'));
		$fiveDaysAgo = date('Y-m-d',strtotime('-5 days'));
		
		if($cron == 'LeadAllocation') {
			$sql =  "SELECT allocationtime as time ".
					"FROM SALeadAllocation ".
					"ORDER BY id DESC LIMIT 1";
		}
		if($cron == 'LeadDeliveryEmail') {
			$sql =  "SELECT createdTime  as time ".
					"FROM tMailQueue ".
					"WHERE subject LIKE '%New leads from Shiksha.com' ".
					"AND createdTime > '$tenDaysAgo' AND isSent = 'sent' ".
					"ORDER BY id DESC LIMIT 1";
		}
		if($cron == 'LeadDeliverySMS') {
			$sql =  "SELECT createdDate  as time ".
					"FROM smsQueue ".
					"WHERE `text` LIKE 'Lead-%' ".
					"AND createdDate > '$tenDaysAgo' AND status = 'processed' ".
					"ORDER BY id DESC LIMIT 1";
		}
		if($cron == 'ShikshaEmailDelivery') {
			 $sql =  "SELECT createdTime  as time ".
                                        "FROM tMailQueue ".
                                        "WHERE createdTime > '$tenDaysAgo' AND isSent = 'sent' ".
                                        "ORDER BY id DESC LIMIT 1";
		}
		if($cron == 'ShikshaSMSDelivery') {
			$sql =  "SELECT createdDate  as time ".
					"FROM smsQueue ".
					"WHERE createdDate > '$tenDaysAgo' AND status = 'processed' ".
					"ORDER BY id DESC LIMIT 1";
		}
		if($cron == 'HourlyResponseDelivery') {
			$sql =  "SELECT submit_date  as time ".
					"FROM tempLmsRequest ".
					"WHERE mailSent = 'yes' AND submit_date > '$fiveDaysAgo' ".
					"ORDER BY id DESC LIMIT 1";
		}                 
		if($cron == 'MMMClientMailerProcessing') {
			$sql =  "SELECT createdtime  as time ".
					"FROM mailQueue ".
					"WHERE mailertype IN ('mmm' ,'csv') ".
					"ORDER BY mailid DESC LIMIT 1";
					
			$this->initiateModel('write','Mailer');		
		}
		if($cron == 'MMMProductMailerProcessing') {
			$sql =  "SELECT createdtime  as time ".
					"FROM mailQueue ".
					"WHERE mailertype = 'product' ".
					"ORDER BY mailid DESC LIMIT 1";
					
			$this->initiateModel('write','Mailer');				
		}
		if($cron == 'MMMEmailDelivery') {
			$sql =  "SELECT createdtime  as time ".
					"FROM mailQueue ".
					"WHERE  issent = 'yes' ".
					"ORDER BY mailid DESC LIMIT 1";
					
			$this->initiateModel('write','Mailer');				
		}

		if($cron == 'Porting') {
			$sql =  "SELECT request_time  as time ".
					"FROM porting_status ".
					"ORDER BY id DESC LIMIT 1";				
		}

		if($cron == 'MissedCallResponse') {
			$currentHour = date("H",time());
			if($currentHour > 07 ){
				$sql =  "SELECT  time ".
						"FROM missed_call_response ".
						"ORDER BY id DESC LIMIT 1";				
			}else{
				$sql = "SELECT now() as time";
			}
		}

		if($cron == 'UserIndexingCron') {
			$sql =  "SELECT queueTime as time ".
					"FROM tuserIndexingQueue ".
					"WHERE status = 'processed' ".
					"ORDER BY id DESC LIMIT 1";				
		}
		
		$query = $this->dbHandle->query($sql);
        $result = $query->row_array();
        $cronTime = strtotime($result['time']);
        
		$currentTime = time();
        $lag = $currentTime - $cronTime;
        
        return $lag;
    }
	
	public function getCronErrors()
	{
		$this->initiateModel('write');
		
		$sql = "SELECT * FROM cron_php_errors ORDER BY time DESC";
        $query = $this->dbHandle->query($sql);
        return $query->result_array();
	}
}
