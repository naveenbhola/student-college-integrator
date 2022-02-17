<?php

class CFInputResponse extends MX_Controller
{
	private $dbHandle;	
	private $writeDBHandle;
	function __construct()
	{
		$this->load->library('DbLibCommon');
		$this->dbLibObj = DbLibCommon::getInstance('MISTracking');
		$this->dbHandle = $this->_loadDatabaseHandle();
		$this->writeDbHandle = $this->_loadDatabaseHandle('write');
	}
	
	function updateResponses()
	{
		$this->validateCron();
		$today = date("Y-m-d");
		$yesterday = date("Y-m-d", strtotime("-1 day"));

		$sql = "select b.visitorId, a.listing_type_id
			from tempLMSTable a
			inner join session_tracking b on b.sessionId = a.visitorsessionid
			left join abroadCategoryPageData c on (c.course_id = a.listing_type_id and c.status = 'live')
			where a.listing_type = 'course'
			and a.visitorsessionid is not null
			and a.action not in ('Viewed_Listing', 'Viewed_Listing_Pre_Reg', 'Viewed_Listing_sa_mobile', 'Institute_Viewed','MOB_Institute_Viewed')
			and c.id is null
			and a.submit_date > ?
			and submit_date < ?";

		$query = $this->dbHandle->query($sql, array($yesterday, $today));
		$results = $query->result_array();

		//error_log($this->dbHandle->last_query());
		//error_log(count($results));

		$batchSize = 2000;
		$count = 0;

		$visitorBatch = array();
		$responseBatch = array();

		foreach($results as $result) {
			$visitorBatch[] = $result['visitorId'];
			$responseBatch[] = array($result['visitorId'], $result['listing_type_id']);
			$count++;
			
			if($count == $batchSize) {
				$this->addVisitorBatch($visitorBatch);
				$this->addResponseBatch($responseBatch);
				//break;
				
				$count = 0;
				$visitorBatch = array();
				$responseBatch = array();
			}			
		}

		if($count > 0) {
			$this->addVisitorBatch($visitorBatch);
			$this->addResponseBatch($responseBatch);
		}

		//error_log(print_r($results, true));
		//error_log($yesterday);
	}

	private function addVisitorBatch($visitorBatch)
	{
		if(is_array($visitorBatch) && count($visitorBatch) > 0) {
			$insertBatch = array();
			foreach($visitorBatch as $visitorId) {
				$insertBatch[] = "(\"$visitorId\")";
			}
			$sql = "INSERT INTO CF_INPUT_USERMAPPING(visitorId) ".
                                "VALUES ".implode(",", $insertBatch)." ".
                                "ON DUPLICATE KEY UPDATE visitorId = visitorId";
			//error_log($sql);
			$this->writeDbHandle->query($sql);	
		}
	}

	private function addResponseBatch($responseBatch)
        {
                if(is_array($responseBatch) && count($responseBatch) > 0) {
                        $insertBatch = array();
                        foreach($responseBatch as $rb) {
                                $insertBatch[] = "(\"".$rb[0]."\", ".$rb[1].")";
                        }

			$sql = "INSERT INTO CF_INPUT_RESPONSE(visitorId, courseId) ".
                               "VALUES ".implode(",", $insertBatch)." ".
                               "ON DUPLICATE KEY UPDATE visitorId = visitorId, courseId = courseId";

                        //error_log($sql);
                        $this->writeDbHandle->query($sql);    
                }
        }
}
