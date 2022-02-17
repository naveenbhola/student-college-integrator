<?php
	
	Class CookieBannerTrackingModel extends MY_Model
	{
		private $dbHandle;

		private function initiateModel($operation = 'read')
	    {
			if($operation=='read')
			{
				$this->dbHandle = $this->getReadHandle();
			}
			else
			{
	        	$this->dbHandle = $this->getWriteHandle();
			}
		}


		// Inserting cookie data into database.

		function saveCookie($data)
		{
			if($data['userid']<1 || $data['tracked_on']==null)
			{
				return;
			}
			$this->initiateModel('write');
			$query = 'INSERT IGNORE into cookie_banner_tracking(userid,session_id,tracked_on) VALUES (?,?,?)';
			$this->dbHandle->query($query,$data);


		}

		// Fetching Cookie data for a given userid.

		function getCookieByUserId($userid)
		{
			$this->initiateModel('read');
			$query = 'SELECT id FROM cookie_banner_tracking WHERE userid=?';
        	$result = $this->dbHandle->query($query,$userid)->row_array();
        	return $result;
		}
	}
?>
