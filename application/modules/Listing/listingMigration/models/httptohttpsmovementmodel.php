<?php

class httptohttpsmovementmodel extends MY_Model {
	function __construct() {
		parent::__construct('Listing');
    }

    private function initiateModel($mode = "write") {
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

    function getCourseListingCount(){
        return;
        /*
    	$this->initiateModel('read');	
    	$query = "SELECT  count(lm.listing_type_id) as count FROM listings_main_http lm ". 
    		   "JOIN shiksha_courses sc ".
    		   "ON lm.listing_type_id = sc.course_id AND lm.listing_type = 'course' ".
    		   "WHERE  lm.status = 'live' AND sc.status = 'live'";

    	$result = $this->dbHandle->query($query)->row_array();
    	return $result;    	
        */
    }

    function getCourseListingData($batch,$limit){
        return;
        /*
    	$this->initiateModel('read');	
    	$offset = $batch * $limit;

    	$query = "SELECT  lm.listing_id,lm.listing_type_id, lm.listing_seo_url FROM listings_main_http lm ". 
    		   "JOIN shiksha_courses sc ".
    		   "ON lm.listing_type_id = sc.course_id AND lm.listing_type = 'course' ".
    		   "WHERE  lm.status = 'live' AND sc.status = 'live' order by lm.listing_type_id asc limit $offset,$limit";

    	$result = $this->dbHandle->query($query)->result_array();
    	return $result;
        */
    }

    function updateListingData($filterListingMain){
        return;
        /*
    		if(empty($filterListingMain))	
    				return;

	    	$this->initiateModel('write');	
	    	$this->dbHandle->trans_start();
			$this->dbHandle->update_batch('listings_main_http', $filterListingMain,'listing_id');
			$this->dbHandle->trans_complete();		
			if ($this->dbHandle->trans_status() === FALSE) {
	    		throw new Exception('Transaction Failed');
	    	}
            */
    }
    
    function getRankingBannerData(){
        return;
        /*
        $this->initiateModel('read');
        return $this->dbHandle->select('id,file_path')->get('ranking_banner_products')->result_array();
        */
    }

    function updateRankingBannerUrls($data){
        return;
        /*
        $this->initiateModel('write');
        $this->dbHandle->trans_start();
        $this->dbHandle->update_batch('ranking_banner_products', $data,'id');
        $this->dbHandle->trans_complete();
        if ($this->dbHandle->trans_status() === FALSE) {
            throw new Exception('Transaction Failed');
        }
        */
    }

    function backupTable($tableName){
        return;
        /*
        $this->initiateModel('write');
        $sql = 'CREATE TABLE IF NOT EXISTS '.$tableName.'_http_bkp AS SELECT * FROM '.$tableName;
        return $this->dbHandle->query($sql);
        */
    }
}