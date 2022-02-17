<?php

class searchTrackingModel extends MY_Model
{
    /**
     * returns a data base handler object
     *
     * @param none
     * @return object
     */
    private function initiateModel($operation='read'){
	if($operation=='read'){
	    $this->dbHandle = $this->getReadHandle();
	}else{
	    $this->dbHandle = $this->getWriteHandle();
	}
    }
    
    /**
     * inserts the tracking data into the table CA_instituteSearchTracking
     * @param data sent from controller
     * @return none
     */
    function insertItemInSearchTracking($track_data)
    {
	$this->initiateModel('write');
	$this->dbHandle->insert('CA_instituteSearchTracking', $track_data);
    }
}