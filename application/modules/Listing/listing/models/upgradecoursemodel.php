<?php

class UpgradeCourseModel extends MY_Model {
    function __construct(){
		parent::__construct('Listing');
		$this->db = $this->getReadHandle();
    }


   	public function getCourseWithClientData($courseId = 0)
	{
	    if($courseId == 0){
			return -1;
	    }
	    
	    $sql =  "SELECT 
				lm.listing_type_id as course_id,
				lm.listing_title as course_name,
				lm.username as client_id,
				lm.expiry_date as expiry_date,
				lm.subscriptionId as subscription_id,
				lm.pack_type as pack_type
			    FROM
				listings_main lm JOIN shiksha_courses sc 
				ON lm.listing_type_id = sc.course_id	
		    	WHERE lm.listing_type = 'course' and lm.listing_type_id = ? and lm.status = 'live' ";

	    $result = $this->db->query($sql,array($courseId))->result_array();
	    return $result;
	}
}