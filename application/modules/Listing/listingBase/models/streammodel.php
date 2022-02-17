<?php 

class streammodel extends listingbasemodel {
	function __construct() {
		parent::__construct('Listing');
    }

	function initiateModel($mode = "write") {
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

    function getAllStreams($getOrderedResults) {
    	$this->initiateModel('read');

    	$sql = "SELECT stream_id as id, name FROM streams WHERE status = 'live' ORDER BY display_order, name ";

    	$result = $this->dbHandle->query($sql)->result_array();

    	return $result;
    }
} ?>