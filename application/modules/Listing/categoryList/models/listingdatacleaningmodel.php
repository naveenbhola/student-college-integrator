<?php
define("SHOW_QUERY_FLAG", 1);
class ListingDataCleaningModel extends MY_Model
{
	// Data members
	public $cleanData;
	
	function __construct(){
		parent::__construct('CategoryList');
		$this->db = $this->getReadHandle();
	}
	
	// Member functions
	function init($cleanDataFlag = 0)
	{
	    $this->cleanData = empty($cleanDataFlag) ? 0 : 1 ;
	}
	
	public function getListingsMainData($listing_id, $status = array('live'))
	{
	    $query = "SELECT * FROM `listings_main` a WHERE 1
		      AND a.`listing_type_id` = ?
		      AND a.listing_type = 'course' ";
//		      AND a.`status` like '".$status."'";
	    
	    if(!empty($status))
	    {
		$status = "'" . implode("','", $status) . "'";
		    $query .= " AND `status` in (".$status.")";
	    }

	    $data = $this->db->query($query, array($listing_id))->result_array();
	    return $data;
	}
	
	public function checkListingsMainLiveDraftPositionIssue($listing_id)
	{
	    $query = "SELECT a.*
		      FROM `listings_main` a
		      WHERE 1
		      AND a.listing_type_id = ? 
		      AND a.listing_type = 'course'
		      AND a.status = 'draft'
		      AND a.version <= (SELECT b.version FROM listings_main b WHERE b.`listing_type_id` = ? 
		      AND b.listing_type = 'course'
		      AND b.status = 'live' limit 1 )
		      limit 1";
		  
	        
	    $data = $this->db->query($query, array($listing_id, $listing_id))->result_array();
	    return $data;
	}
	
	public function checkIfLiveVersionIfIncorrect($listing_id)
	{
	    $query = "SELECT a.*
		      FROM `listings_main` a
		      WHERE 1
		      AND a.listing_type_id = ? 
		      AND a.listing_type = 'course'
		      AND a.status = 'draft' ";
	        
	    $data = $this->db->query($query, array($listing_id))->result_array();
	    
	    if(!$data[0])
	    {
		$query = "SELECT a.version
		      FROM `listings_main` a
		      WHERE 1
		      AND a.listing_type_id = ? 
		      AND a.listing_type = 'course'
		      AND a.status = 'live' order by version asc ";
	        
		$data = $this->db->query($query, array($listing_id))->result_array();
		
		if($data[0] && $data[0]['version'] < 1 )
		    return true;
	    }
	    else
		return false;

	    return false;
	}

	public function checkIfLiveVersionIsIncorrectCourseDetails($listing_id)
	{
	    $query = "SELECT a.*
		      FROM `course_details` a
		      WHERE 1
		      AND a.course_id = ? 
		      AND a.status = 'draft' ";
	        
	    $data = $this->db->query($query, array($listing_id))->result_array();
	    
	    if(!$data[0])
	    {
		$query = "SELECT a.version
		      FROM `course_details` a
		      WHERE 1
		      AND a.course_id = ? 
		      AND a.status = 'live' order by version asc ";
	        
		$data = $this->db->query($query, array($listing_id))->result_array();
		
		if($data[0] && $data[0]['version'] < 1 )
		    return true;
	    }
	    else
		return false;

	    return false;
	}	
	
	
	public function getCourseDetailsData($listing_id, $status = "" )
	{
	     $query = "SELECT * FROM `course_details` WHERE 1
		       AND `course_id` = ? ";
		       
	    if(!empty($status))
	    {
		$status = "'" . implode("','", $status) . "'";
		    $query .= " AND `status` in (".$status.")";
	    }
	    
	    $data = $this->db->query($query, array($listing_id))->result_array();
	    
	    return $data;
	}
	
	public function checkCourseDetailsLiveDraftPositionIssue($listing_id)
	{
	    $query = "SELECT a.*
		      FROM `course_details` a
		      WHERE 1
		      AND a.course_id = ? 
		      AND a.status = 'draft'
		      AND a.version <= (SELECT b.version FROM course_details b WHERE b.course_id = ? AND b.status = 'live' limit 1 )
		      limit 1 ";
	    
	    $data = $this->db->query($query, array($listing_id, $listing_id))->result_array();
	    return $data;
	}
	

	public function getClientCourseToLDBCourseMappingData($listing_id, $status)
	{
	    $query = "SELECT `clientCourseID`, `LDBCourseID` , count(*)
		      FROM `clientCourseToLDBCourseMapping`
		      WHERE 1
		      and `clientCourseID` = ?";
	
	    if(!empty($status))
	    {
		$status = "'" . implode("','", $status) . "'";
		    $query .= " AND `status` in (".$status.")";
	    }
	    
	    $query .= " GROUP BY `LDBCourseID`
		        HAVING count(*) > 1
		        order by count(*) desc";

	    $data = $this->db->query($query, array($listing_id))->result_array();
	    return $data;
	      
	}
	
	public function getClientCourseToLDBCourseMappingLiveDraftPositionIssue($listing_id)
	{
	    $query = "SELECT a.*
		      FROM clientCourseToLDBCourseMapping a
		      WHERE 1
		      AND a.`clientCourseID` = ? 
		      AND a.status = 'draft'
		      AND a.version <= (SELECT b.version FROM clientCourseToLDBCourseMapping b
				       WHERE b.`clientCourseID` = ? AND b.status = 'live' limit 1 )
		      limit 1 ";
		      
	    $data = $this->db->query($query, array($listing_id, $listing_id))->result_array();
	    return $data;		      
	}
	
	public function getListingAttributesTableData($listing_id, $status)
	{
	    $query = "SELECT listing_type_id, listing_type, caption, count( * )
		      FROM `listing_attributes_table`
		      WHERE 1
		      AND `listing_type_id` = ? 
		      AND `listing_type` = 'course' ";
	    
	    if(!empty($status))
	    {
		    $status = "'" . implode("','", $status) . "'";
		    $query .= " AND `status` in (".$status.") ";
	    }
	    
	    $query .= " GROUP BY caption, attributeValue	
		        HAVING count( * ) > 1 ";

	    $data = $this->db->query($query, array($listing_id))->result_array();
	    return $data;		      
	}
	
	public function getListingAttributesTableLiveDraftPositionIssue($listing_id)
	{
	    $query = "SELECT a.`listing_attribute_id`
		      FROM listing_attributes_table a
		      WHERE 1
		      AND listing_type = 'course' 
		      AND `listing_type_id` = ? 
		      AND a.status = 'draft'
		      AND a.version <= (SELECT b.version FROM listing_attributes_table b
					where 1
					AND  `listing_type_id` = ? 
					AND listing_type = 'course' 
					AND b.`caption` = a.`caption`
					AND b.`attributeValue` = a.`attributeValue`
					AND b.status = 'live' limit 1 )
		      group by `caption`,`attributeValue` ";
		      
	    $data = $this->db->query($query, array($listing_id, $listing_id))->result_array();
	    return $data;	
	}
	
	public function getCourseFeaturesData($listing_id, $status)
	{
		$query = "SELECT listing_id,status, count(*)
			    FROM `course_features`
			    WHERE 1
			    AND listing_id = ?";
			   
		if(!empty($status))
		{
		    $status = "'" . implode("','", $status) . "'";
		    $query .= " AND `status` in (".$status.") ";
		}
		
		$query .= " group by listing_id,salient_feature_id
			    having count(*) > 1 ";

		$data = $this->db->query($query, array($listing_id))->result_array();
		return $data;	
	}
	
	public function getCourseFeaturesLiveDraftPositionIssue($listing_id)
	{
	    	$query = "SELECT a.id
			    FROM course_features a
			    WHERE 1
			    AND listing_id = ? 
			    AND a.status = 'draft'
			    AND a.version <= (SELECT b.version FROM course_features b
					    where 1
					    AND listing_id = ? 
					    AND b.salient_feature_id = a.salient_feature_id
					    AND b.status = 'live' limit 1 )
			    group by salient_feature_id ";
		      
		$data = $this->db->query($query, array($listing_id, $listing_id))->result_array() ;
		
		return $data;	
	}
	
	public function getListingExamMapData($listing_id, $status)
	{
		$query = "SELECT typeId,status,examId, count(*)
			    FROM `listingExamMap`
			    WHERE 1
			    AND examId 	!= -1 
			    AND typeId = ? ";
			   
		if(!empty($status))
		{
		    $status = "'" . implode("','", $status) . "'";
		    $query .= " AND `status` in (".$status.") ";
		}
		
		$query .= " group by typeId,examId
			    having count(*) > 1 ";

		$data = $this->db->query($query, array($listing_id))->result_array();
		return $data;		    
	}
	
	public function getListingExamMapLiveDraftPositionIssue($listing_id)
	{
	    	$query = "SELECT a.id
			    FROM listingExamMap a
			    WHERE 1
			    AND typeId = ?
			    AND a.status = 'draft'
			    AND a.version <= (SELECT b.version FROM listingExamMap b
					    where 1
					    AND typeId = ?
					    AND b.examId = a.examId
					    AND b.status = 'live' limit 1 )
			    group by examId ";
		      
		$data = $this->db->query($query, array($listing_id, $listing_id))->result_array() ;
		
		return $data;	
	    
	}
	
	public function getCourseLocationAttributeData($listing_id, $status)
	{
		$query = "SELECT course_location_attribute_id,course_id,status,count(*)
			    FROM course_location_attribute
			    WHERE 1
			    AND course_id = ? ";
			   
		if(!empty($status))
		{
		    $status = "'" . implode("','", $status) . "'";
		    $query .= " AND `status` in (".$status.") ";
		}
		
		$query .= " group by institute_location_id,attribute_type,attribute_value
			    having count(*) > 1 ";

		$data = $this->db->query($query, array($listing_id))->result_array();
		return $data;		    
	}
	
	public function getCourseLocationAttributeLiveDraftPositionIssue($listing_id)
	{
	    	$query = "SELECT a.course_location_attribute_id
			    FROM course_location_attribute a
			    WHERE 1
			    AND course_id = ? 
			    AND a.status = 'draft'
			    AND a.version <= (SELECT b.version FROM course_location_attribute b
					    where 1
					    AND course_id = ? 
					    AND b.institute_location_id = a.institute_location_id
					    AND b.attribute_type = a.attribute_type
					    AND b.attribute_value = a.attribute_value
					    AND b.status = 'live' limit 1 )
			    group by institute_location_id,attribute_type,attribute_value ";
		      
		$data = $this->db->query($query, array($listing_id, $listing_id))->result_array() ;

		return $data;	
	}

	public function getListingContactDetailsData($listing_id, $status)
	{
		$query = "SELECT contact_details_id,listing_type_id,status,count(*)
			    FROM listing_contact_details
			    WHERE 1
			    AND listing_type = 'course' 
			    AND listing_type_id = ?";
			   
		if(!empty($status))
		{
		    $status = "'" . implode("','", $status) . "'";
		    $query .= " AND status in (".$status.") ";
		}
		
		$query .= " group by institute_location_id,listing_type_id
			    having count(*) > 1 ";

		$data = $this->db->query($query, array($listing_id))->result_array();
		return $data;		    
	}
	
	public function getListingContactDetailsLiveDraftPositionIssue($listing_id)
	{
	    	$query = "SELECT a.contact_details_id
			    FROM listing_contact_details a
			    WHERE 1
			    AND a.listing_type = 'course' 
			    AND a.listing_type_id = ?
			    AND a.status = 'draft'
			    AND a.version <= (SELECT b.version FROM listing_contact_details b
					    where 1
					    AND b.listing_type_id = ? 
					    AND b.listing_type = 'course' 
					    AND b.institute_location_id = a.institute_location_id
					    AND b.status = 'live' limit 1 )
			    group by institute_location_id,listing_type_id ";
		
		$data = $this->db->query($query, array($listing_id, $listing_id))->result_array() ;
		
		return $data;	
	}

	public function getListingCategoryTableData($listing_id, $status)
	{
		$query = "SELECT listing_category_id,listing_type_id,status,count(*)
			    FROM listing_category_table
			    WHERE 1
			    AND listing_type = 'course' 
			    AND listing_type_id = ?";
			   
		if(!empty($status))
		{
		    $status = "'" . implode("','", $status) . "'";
		    $query .= " AND status in (".$status.") ";
		}
		
		$query .= " group by category_id,listing_type_id
			    having count(*) > 1 ";

		$data = $this->db->query($query, array($listing_id))->result_array();
		return $data;		    
	}
	
	/*************************************************************************
	* START : DATA FIXING SECTION
	**************************************************************************/

	public function clearCourseDetailsDataForMultipleStatus($listing_id, $status, $latest_version)
	{
		$highest_status_version = 0;

		if($latest_version < 1 && $status != 'live')
		{
		    _p("Cannot clean multiple entries of ".$status." status and its version in the listings_main table is ".$latest_version);
		    return;
		}

		// START ----------------------------------
		$query = "SELECT a.*,a.version as version, a.id as id FROM `course_details` a WHERE 1
			and status = ? 
			and `course_id` = ? 
			order by version DESC , id DESC 
			limit 1 ";
	    
		$data = $this->db->query($query, array($status, $listing_id))->result_array();
		if(empty($data[0]))
		{
		    _p("Inside ".__METHOD__." 1. AN ERROR OCCURRED");
		    exit(0);
		}
		
		$primaryKey = $data[0]['id'];
		$highest_status_version = $data[0]['version'];
		//_p($primaryKey);
		// END ----------------------------------

		// START ------------------------
		// show the data that will be updated
		$query = "SELECT  a.id as id,a.* FROM `course_details` a WHERE 1
			    and status = ? 
			    and `course_id` = ? 
			    and a.id not in (".$primaryKey.")"
			    ;
		$data = $this->db->query($query, array($status, $listing_id))->result_array();
		_p("The data that will be updated to history status : ");
		// $data = $this->db->query($query)->result_array();
		$this->showTabularData($data);
		// END ---------------------------

		// START --------------------------------
		
	    
		$this->db = $this->getWriteHandle();

		// Update all redundant entries to history status
		$query = "update course_details set status='history' where course_id = ? 
			and status = ? and id not in (".$primaryKey.")";
		if(SHOW_QUERY_FLAG)
		    _p("<u>Inside : ".__METHOD__."</u> <br/> QUERY : ".print_r($query,true));

		if($this->cleanData)
		{
			$this->db->query($query, array($listing_id, $status));
			_p("Update Done !!! Rows updated : ".$this->db->affected_rows());
		}
		else
		    _p("Update NOT done");
		// END ----------------------------------

		// START ----------------------------------
		// Update the version of the live/draft entry in course_details if it doesn't matches with the listings_main version
		if( $latest_version != $highest_status_version )
		{
			$query = "update course_details set version= ? where course_id = ?
				  and status = ? and id in (".$primaryKey.")";
			if(SHOW_QUERY_FLAG)
			    _p("<u>Inside : ".__METHOD__."</u> <br/> QUERY : ".print_r($query,true));

			if($this->cleanData)
			{
			    $this->db->query($query, array($latest_version, $listing_id, $status));
			    _p("Update Done !!! Rows updated : ".$this->db->affected_rows());
			}
			else
			    _p("Update NOT done");
		}
		// END ----------------------------------
	}
	
	public function clearCourseDetailsDataForLiveDraftPositionIssue($listing_id, $live_version)
	{
    	if($live_version < 1)
		{
		    _p("Cannot clean Live/draft positioning issue as live version provided  = ".$live_version);
		    return;
		}
		
		// make all draft entries having version less than live version to history
		
		$this->db = $this->getWriteHandle();
		
		$query = "update course_details set status='history' where course_id = ?
			and status = 'draft' and version <= ?";
			
		if(SHOW_QUERY_FLAG)
		    _p("<u>Inside : ".__METHOD__."</u><br/> QUERY : ".print_r($query,true));
		
		if($this->cleanData)
		{
		    $this->db->query($query, array($listing_id, $live_version));
		    _p("Update Done !!! Rows Updated : ".$this->db->affected_rows());
		}
		else
		    _p("Update NOT done");
	}

	public function clearClientCourseToLDBCourseMappingDataForMultipleStatus($listing_id, $status, $latest_version)
	{
		$highest_status_version = 0;
			
		if($latest_version < 1 && $status != 'live')
		{
		    _p("Cannot clean multiple entries of ".$status." status and its version in the listings_main table is ".$latest_version);
		    return;
		}
		
		$query = "SELECT a.*,a.version as version
			FROM `clientCourseToLDBCourseMapping` a
			WHERE 1
			AND status = ? 
			AND `clientCourseID` = ? 
			ORDER BY version DESC
			LIMIT 1 ";
	    
		$data = $this->db->query($query, array($status, $listing_id))->result_array();
		if(empty($data[0]))
		{
		    _p("Inside ".__METHOD__." 1. AN ERROR OCCURRED");
		    exit(0);
		}
		$highest_status_version = $data[0]['version'];
	    
		$this->db = $this->getWriteHandle();

		// START ------------------------
		// show the data that will be updated
		$query = "select * from clientCourseToLDBCourseMapping where clientCourseID = ?
			and status = ? ";
		$data = $this->db->query($query, array($listing_id, $status))->result_array();
		_p("The data that will be updated to history status : ");
		// $data = $this->db->query($query)->result_array();
		$this->showTabularData($data);
		// END ---------------------------
		
		// Update all redundant entries to history status
		$query = "update clientCourseToLDBCourseMapping set status='history' where clientCourseID = ?
			and status = ? ";
		if(SHOW_QUERY_FLAG)
		    _p("<u>Inside : ".__METHOD__."</u> <br/> QUERY : ".print_r($query,true));
		    
		if($this->cleanData)
		{
		    $this->db->query($query, array($listing_id, $status));
		    _p("Update Done !!! Rows Updated : ".$this->db->affected_rows());
		}
		else
		    _p("Update NOT done");
		
		// Update the version of the live/draft entry in clientCourseToLDBCourseMapping if it doesn't matches with the listings_main version
		if( $latest_version != $highest_status_version )
		{
			$query = "update clientCourseToLDBCourseMapping set version= ? where clientCourseID = ?
				and status = ? ";
			if(SHOW_QUERY_FLAG)
			    _p("<u>Inside : ".__METHOD__."</u></br> QUERY : ".print_r($query,true));
			    
			if($this->cleanData)
			{
			    $this->db->query($query, array($latest_version, $listing_id, $status));
			    _p("Update done !!! Rows Updated : ".$this->db->affected_rows());
			}
			else
			    _p("Update NOT done");
		}
	}

	public function clearClientCourseToLDBCourseMappingForLiveDraftPositionIssue($listing_id, $live_version)
	{
	    if($live_version < 1)
	    {
		_p("Cannot clean Live/draft positioning issue as live version provided  = ".$live_version);
		return;
	    }
	    
	    $query = "SELECT a.*
		      FROM clientCourseToLDBCourseMapping a
		      WHERE 1
		      AND a.clientCourseID = ?
		      AND a.status = 'draft'
		      AND a.version <= (SELECT b.version FROM clientCourseToLDBCourseMapping b
			 	       WHERE b.`clientCourseID` = ? AND b.status = 'live' limit 1 ) ";
		
	    $data = $this->db->query($query, array($listing_id, $listing_id))->result_array();
	    
	    // START ------------------------
	    // show the data that will be updated
	    _p("The data that will be updated to history status : ");
	    $data = $this->db->query($query)->result_array();
	    $this->showTabularData($data);
	    // END ---------------------------

	    // make all draft entries having version less than live version to history
	    
	    $this->db = $this->getWriteHandle();
	    
	    $query = "update clientCourseToLDBCourseMapping 
		      set status='history' 
		      where `clientCourseID` = ? and status = 'draft' and version <= ? ";
	    if(SHOW_QUERY_FLAG)
		_p("<u>Inside : ".__METHOD__."</u><br/> QUERY : ".print_r($query,true));
		
	    if($this->cleanData)
	    {
		$this->db->query($query, array($listing_id, $live_version));
		_p("Update Done !!! Rows Updated : ".$this->db->affected_rows());
	    }
	    else
		_p("Update NOT done");
	}
	
	public function clearListingAttributesTableDataForMultipleStatus($listing_id, $status, $latest_version)
	{
		$highest_status_version = 0;
			
		if($latest_version < 1 && $status != 'live')
		{
		    _p("Cannot clean multiple entries of ".$status." status as its version in the listings_main table is ".$latest_version);
		    return;
		}
		
		// START-----------------------------
		// the query will return the latest version and their the tuples ids of the data
		$query = " SELECT version as latest_version, 
			   max(listing_attribute_id) as listing_attribute_id 
			      FROM listing_attributes_table a
			   WHERE listing_type_id = ?
			   AND listing_type = 'course' 
			   AND status = ? 
			   GROUP BY caption , attributeValue ";
		
		$data = $this->db->query($query, array($listing_id, $status))->result_array();
		if(empty($data[0]))
		{
		    _p("Inside ".__METHOD__." 1. AN ERROR OCCURRED");	
		    exit(0);
		}
		$primarykeys = array();
		foreach($data as $row)
		{
		    $primarykeys[] = $row['listing_attribute_id'];
		}
		$primarykeys = implode(",",$primarykeys);
		// END --------------------------
		
		// START ------------------------
		// show the data that will be updated
		$query = "select * from listing_attributes_table where 1
		          and listing_type_id = ? 
		          and status = ? 
			  and listing_type = 'course' 
		          and listing_attribute_id NOT IN (".$primarykeys.") ";
		_p("The data that will be updated to history status : ");
		$data = $this->db->query($query, array($listing_id, $status))->result_array();
		$this->showTabularData($data);
		// END ---------------------------
		
		// START --------------------------
		$this->db = $this->getWriteHandle();
		
		$query = "update listing_attributes_table set status = 'history' where 1
		          and listing_type_id = ? 
		          and status = ? 
			  and listing_type = 'course' 
		          and listing_attribute_id NOT IN (".$primarykeys.") ";
			  
		if(SHOW_QUERY_FLAG)
		    _p("<u>Inside : ".__METHOD__."</u> <br/> QUERY : ".print_r($query,true));
		
		if($this->cleanData)
		{
		    $this->db->query($query, array($listing_id, $status));
		    _p("Update Done !!! Rows Updated : ".$this->db->affected_rows());
		}
		else
		    _p("Update NOT Done");
		// END --------------------------
		    
		// START ------------------------
		// Update the version of the live/draft entry in listing_attributes_table as in accordance of listings_main version
		$query = "update listing_attributes_table set version= ? where listing_type_id = ? 
			  and status = ? 
			  and listing_type = 'course' 
			  and listing_attribute_id IN (".$primarykeys.") ";
			  
		if(SHOW_QUERY_FLAG)
		    _p("<u>Inside : ".__METHOD__."</u></br> QUERY : ".print_r($query,true));
		    
		if($this->cleanData)
		{
		    $this->db->query($query, array($latest_version, $listing_id, $status));
		    _p("Update done !!! Rows Updated : ".$this->db->affected_rows());
		}
		else
		    _p("Update NOT done");
		// END ----------------------------
		
	}
	
	public function clearListingAttributesTableForLiveDraftPositionIssue($listing_id, $live_version)
	{
	    if($live_version < 1)
	    {
		_p("Cannot clean Live/draft positioning issue as live version provided  = ".$live_version);
		return;
	    }
	    
	    // START -------------------------------
	    $query = "SELECT * 
		      FROM listing_attributes_table a
		      WHERE 1
		      AND a.listing_type = 'course' 
		      AND a.listing_type_id = ? 
		      AND a.status = 'draft'
		      AND a.version <= (SELECT b.version FROM listing_attributes_table b
					where 1
					AND b.listing_type_id = ? 
					AND b.listing_type = 'course' 
					AND b.`caption` = a.`caption`
					AND b.`attributeValue` = a.`attributeValue`
					AND b.status = 'live' limit 1 )
		      group by `caption`,`attributeValue` ";
	    
	    $data = $this->db->query($query, array($listing_id, $listing_id))->result_array();
	    
	    $primarykeys = array();
	    foreach($data as $row)
	    {
		$primarykeys[] = $row['listing_attribute_id'];
	    }
	    
	    if(empty($data[0]) )
	    {
		_p("Inside ".__METHOD__." 1. AN ERROR OCCURRED");	
		exit(0);
	    }else if(empty($primarykeys))
	    {
		_p("Inside ".__METHOD__." 2. AN ERROR OCCURRED");	
		exit(0);
	    }
	    
	    $primarykeys = implode(",",$primarykeys);
	    // END -------------------------------
	    
	    //START ------------------------------
	     _p("Status for following rows will be made history : ");
	    $this->showTabularData($data);
	    
	    // make all draft entries having version less than live version to history
	    $this->db = $this->getWriteHandle();
	    
	    $query = "update listing_attributes_table 
		      set status='history' 
		      where 1
		      AND  `listing_type_id` = ? 
		      AND listing_type = 'course' 
		      AND status = 'draft'
		      AND listing_attribute_id IN (".$primarykeys.") ";
		      
	    if(SHOW_QUERY_FLAG)
		_p("<u>Inside : ".__METHOD__."</u><br/> QUERY : ".print_r($query,true));
		
	    if($this->cleanData)
	    {
		$this->db->query($query, array($listing_id));
		_p("Update Done !!! Rows Updated : ".$this->db->affected_rows());
	    }
	    else
		_p("Update NOT done");
	    // END -----------------------------
	}
	
	public function clearCourseFeaturesDataForMultipleStatus($listing_id, $status, $latest_version)
	{
		$highest_status_version = 0;
			
		if($latest_version < 1 && $status != 'live')
		{
		    _p("Cannot clean multiple entries of ".$status." status as its version in the listings_main table is ".$latest_version);
		    return;
		}
		
		// START-----------------------------
		// the query will return the latest version and their the tuples ids of the data
		$query = " SELECT max(version) as latest_version, 
			   id as listing_id 
			   FROM course_features a
			   WHERE listing_id = ? 
			   AND status = ? 
			   GROUP BY salient_feature_id ";
		
		$data = $this->db->query($query, array($listing_id, $status))->result_array();
		if(empty($data[0]))
		{
		    _p("Inside ".__METHOD__." 1. AN ERROR OCCURRED");	
		    exit(0);
		}
		$primarykeys = array();
		foreach($data as $row)
		{
		    $primarykeys[] = $row['listing_id'];
		}
		$primarykeys = implode(",",$primarykeys);
		// END --------------------------
		
		// START ------------------------
		// show the data that will be updated
		$query = "select * from course_features where 1
		          and listing_id = ? 
		          and status = ? 
		          and id NOT IN (".$primarykeys.") ";
			  
		_p("The data that will be updated to history status : ");
		$data = $this->db->query($query, array($listing_id, $status))->result_array();
		$this->showTabularData($data);
		// END ---------------------------
		
		// START --------------------------
		$this->db = $this->getWriteHandle();
		
		$query = "update course_features set status = 'history' where 1
		          and listing_id = ? 
		          and status = ? 
		          and id NOT IN (".$primarykeys.") ";
			  
		if(SHOW_QUERY_FLAG)
		    _p("<u>Inside : ".__METHOD__."</u> <br/> QUERY : ".print_r($query,true));
		
		if($this->cleanData)
		{
		    $this->db->query($query, array($listing_id, $status));
		    _p("Update Done !!! Rows updated : ".$this->db->affected_rows());
		}
		else
		    _p("Update NOT Done");
		// END --------------------------

		// START ------------------------
		// update the version of all those entries that needs to be kept in the table(correct data) to the version of the listings_main
		$this->db = $this->getWriteHandle();
		
		$query = "update course_features 
			set version = ? 
			where 1
			AND listing_id = ? 
			AND status = ? 
			AND id IN (".$primarykeys.") ";
			
		if(SHOW_QUERY_FLAG)
		    _p("<u>Inside : ".__METHOD__."</u><br/> QUERY : ".print_r($query,true));
		    
		if($this->cleanData)
		{
		    $this->db->query($query, array($latest_version, $listing_id, $status));
		    _p("Update Done !!! Rows updated : ".$this->db->affected_rows());
		}
		else
		    _p("Update NOT done");
		// END -----------------------------
	}
	
	public function clearCourseFeaturesForLiveDraftPositionIssue($listing_id, $live_version)
	{
		if($live_version < 1)
		{
		    _p("Cannot clean Live/draft positioning issue as live version provided  = ".$live_version);
		    return;
		}
		
		// START -------------------------------
		$query = "SELECT a.id as listing_id
			    FROM course_features a
			    WHERE 1
			    AND a.listing_id = ? 
			    AND a.status = 'draft'
			    AND a.version <= (SELECT b.version FROM course_features b
					    where 1
					    AND b.listing_id = ? 
					    AND b.salient_feature_id = a.salient_feature_id
					    AND b.status = 'live' limit 1 )
			    group by `salient_feature_id`";
		
		$data = $this->db->query($query, array($listing_id, $listing_id))->result_array();
		
		$primarykeys = array();
		foreach($data as $row)
		{
		    $primarykeys[] = $row['listing_id'];
		}
		
		if(empty($data[0]) )
		{
		    _p("Inside ".__METHOD__." 1. AN ERROR OCCURRED");	
		    exit(0);
		}else if(empty($primarykeys))
		{
		    _p("Inside ".__METHOD__." 2. AN ERROR OCCURRED");	
		    exit(0);
		}
		
		$primarykeys = implode(",",$primarykeys);
		// END -------------------------------
		
		//START ------------------------------
		_p("Status for following rows will be made history : ");
		$this->showTabularData($data);
		
		// make all draft entries having version less than live version to history
		$this->db = $this->getWriteHandle();
		
		$query = "update course_features 
			set status='history' 
			where 1
			AND listing_id = ? 
			AND status = 'draft'
			AND id IN (".$primarykeys.") ";
			
		if(SHOW_QUERY_FLAG)
		    _p("<u>Inside : ".__METHOD__."</u><br/> QUERY : ".print_r($query,true));
		    
		if($this->cleanData)
		{
		    $this->db->query($query, array($listing_id));
		    _p("Update Done !!! Rows Updated : ".$this->db->affected_rows());
		}
		else
		    _p("Update NOT done");
		// END -----------------------------
	}
	
	public function clearListingExamMapDataForMultipleStatus($listing_id, $status, $latest_version)
	{
		$highest_status_version = 0;
			
		if($latest_version < 1 && $status != 'live')
		{
		    _p("Cannot clean multiple entries of ".$status." status as its version in the listings_main table is ".$latest_version);
		    return;
		}
		
		// START-----------------------------
		// the query will return the latest version and their the tuples ids of the data
		$query = " SELECT version as latest_version, 
			   max(id) as listing_id 
			   FROM listingExamMap a
			   WHERE typeId = ? 
			   AND status = ? 
			   GROUP BY examId
			   order by version desc, id desc";
		
		$data = $this->db->query($query, array($listing_id, $status))->result_array();
		if(empty($data[0]))
		{
		    _p("Inside ".__METHOD__." 1. AN ERROR OCCURRED");	
		    exit(0);
		}
		$primarykeys = array();
		foreach($data as $row)
		{
		    $primarykeys[] = $row['listing_id'];
		}
		$primarykeys = implode(",",$primarykeys);
		//_p($primarykeys );
		// END --------------------------

		// START ------------------------
		// show the data that will be updated
		$query = "select * from listingExamMap  where 1
		          and typeId = ? 
		          and status = ? 
			  and id NOT IN (".$primarykeys.") ";
		_p("The data that will be updated to history status : ");
		$data = $this->db->query($query, array($listing_id, $status))->result_array();
		$this->showTabularData($data);
		// END ---------------------------
		
		// START --------------------------
		$this->db = $this->getWriteHandle();
		
		$query = "update listingExamMap set status = 'history' where 1
		          and typeId = ? 
		          and status = ? 
		          and id NOT IN (".$primarykeys.") ";
			  
		if(SHOW_QUERY_FLAG)
		    _p("<u>Inside : ".__METHOD__."</u> <br/> QUERY : ".print_r($query,true));
		
		if($this->cleanData)
		{
		    $this->db->query($query, array($listing_id, $status));
		    _p("Update Done !!! Rows updated : ".$this->db->affected_rows());
		}
		else
		    _p("Update NOT Done");
		// END --------------------------

		// START ------------------------
		// update the version of all those entries that needs to be kept in the table(correct data) to the version of the listings_main
		$this->db = $this->getWriteHandle();
		
		$query = "update listingExamMap 
			  set version = ? 
			  where 1
			  AND typeId = ? 
			  AND status = ? 
			  AND id IN (".$primarykeys.") ";
			
		if(SHOW_QUERY_FLAG)
		    _p("<u>Inside : ".__METHOD__."</u><br/> QUERY : ".print_r($query,true));
		    
		if($this->cleanData)
		{
		    $this->db->query($query, array($latest_version, $listing_id, $status));
		    _p("Update Done !!! Rows updated : ".$this->db->affected_rows());
		}
		else
		    _p("Update NOT done");
		// END -----------------------------
	}
	
	public function clearListingExamMapForLiveDraftPositionIssue($listing_id, $live_version)
	{
		if($live_version < 1)
		{
		    _p("Cannot clean Live/draft positioning issue as live version provided  = ".$live_version);
		    return;
		}
		
		// START -------------------------------
		$query = "SELECT a.id as listing_id, a.*
			    FROM listingExamMap a
			    WHERE 1
			    AND a.typeId = ? 
			    AND a.status = 'draft'
			    AND a.version <= (SELECT b.version FROM listingExamMap b
					    where 1
					    AND b.typeId = ? 
					    AND b.examId = a.examId
					    AND b.status = 'live' limit 1 )
			    group by examId";
		
		$data = $this->db->query($query, array($listing_id, $listing_id))->result_array();
		
		$primarykeys = array();
		foreach($data as $row)
		{
		    $primarykeys[] = $row['listing_id'];
		}
		
		if(empty($data[0]) )
		{
		    _p("Inside ".__METHOD__." 1. AN ERROR OCCURRED");	
		    exit(0);
		}else if(empty($primarykeys))
		{
		    _p("Inside ".__METHOD__." 2. AN ERROR OCCURRED");	
		    exit(0);
		}
		
		$primarykeys = implode(",",$primarykeys);
		_p($primarykeys);
		// END -------------------------------
		
		//START ------------------------------
		_p("Status for following rows will be made history : ");
		$this->showTabularData($data);
		// END --------------------------------

		//START ------------------------------
		// make all draft entries having version less than live version to history
		$this->db = $this->getWriteHandle();
		
		$query = "update listingExamMap 
			set status='history' 
			where 1
			AND typeId = ? 
			AND status = 'draft'
			AND id IN (".$primarykeys.") ";
			
		if(SHOW_QUERY_FLAG)
		    _p("<u>Inside : ".__METHOD__."</u><br/> QUERY : ".print_r($query,true));
		    
		if($this->cleanData)
		{
		    $this->db->query($query, array($listing_id));
		    _p("Update Done !!! Rows Updated : ".$this->db->affected_rows());
		}
		else
		    _p("Update NOT done");
		// END -----------------------------
	}
	
	public function clearCourseLocationAttributeDataForMultipleStatus($listing_id, $status, $latest_version)
	{
		$highest_status_version = 0;

		if($latest_version < 1 && $status != 'live')
		{
		    _p("Cannot clean multiple entries of ".$status." status as its version in the listings_main table is ".$latest_version);
		    return;
		}
		
		// START-----------------------------
		// the query will return the latest version and their the tuples ids of the data
		$query = " SELECT version as latest_version, 
			   max(course_location_attribute_id) as listing_id 
			   FROM course_location_attribute a
			   WHERE course_id = ? 
			   AND status = ? 
			   GROUP BY institute_location_id,attribute_type,attribute_value ";
		
		$data = $this->db->query($query, array($listing_id, $status))->result_array();
		if(empty($data[0]))
		{
		    _p("Inside ".__METHOD__." 1. AN ERROR OCCURRED");	
		    exit(0);
		}
		$primarykeys = array();
		foreach($data as $row)
		{
		    $primarykeys[] = $row['listing_id'];
		}
		$primarykeys = implode(",",$primarykeys);
		_p($primarykeys);
		// END --------------------------

		// START ------------------------
		// show the data that will be updated
		$query = "select * from course_location_attribute where 1
		          and course_id = ? 
		          and status = ? 
			  and course_location_attribute_id NOT IN (".$primarykeys.") ";
		_p("The data that will be updated to history status : ");
		$data = $this->db->query($query, array($listing_id, $status))->result_array();
		$this->showTabularData($data);
		// END ---------------------------

		// START --------------------------
		$this->db = $this->getWriteHandle();
		
		$query = "update course_location_attribute set status = 'history' where 1
		          and course_id = ? 
		          and status = ? 
		          and course_location_attribute_id NOT IN (".$primarykeys.") ";
			  
		if(SHOW_QUERY_FLAG)
		    _p("<u>Inside : ".__METHOD__."</u> <br/> QUERY : ".print_r($query,true));
		
		if($this->cleanData)
		{
		    $this->db->query($query, array($listing_id, $status));
		    _p("Update Done !!! Rows updated : ".$this->db->affected_rows());
		}
		else
		    _p("Update NOT Done");
		// END --------------------------

		// START ------------------------
		// update the version of all those entries that needs to be kept in the table(correct data) to the version of the listings_main
		$this->db = $this->getWriteHandle();
		
		$query = "update course_location_attribute  
			  set version = ? 
			  where 1
			  AND course_id = ? 
			  AND status = ? 
			  AND course_location_attribute_id IN (".$primarykeys.") ";
			
		if(SHOW_QUERY_FLAG)
		    _p("<u>Inside : ".__METHOD__."</u><br/> QUERY : ".print_r($query,true));
		    
		if($this->cleanData)
		{
		    $this->db->query($query, array($latest_version, $listing_id, $status));
		    _p("Update Done !!! Rows updated : ".$this->db->affected_rows());
		}
		else
		    _p("Update NOT done");
		// END -----------------------------
	}
	
	public function clearCourseLocationAttributeForLiveDraftPositionIssue($listing_id, $live_version)
	{
		if($live_version < 1)
		{
		    _p("Cannot clean Live/draft positioning issue as live version provided  = ".$live_version);
		    return;
		}
		
		// START -------------------------------
		$query = "SELECT a.course_location_attribute_id as listing_id, a.*
			    FROM course_location_attribute a
			    WHERE 1
			    AND a.course_id = ? 
			    AND a.status = 'draft'
			    AND a.version <= (SELECT b.version FROM course_location_attribute b
					    where 1
					    AND b.course_id = ? 
					    AND b.institute_location_id = a.institute_location_id
					    AND b.attribute_type  = a.attribute_type
					    AND b.attribute_value = a.attribute_value
					    AND b.status = 'live' limit 1 )
			    group by institute_location_id, attribute_type, attribute_value ";
		
		$data = $this->db->query($query, array($listing_id, $listing_id))->result_array();
		
		$primarykeys = array();
		foreach($data as $row)
		{
		    $primarykeys[] = $row['listing_id'];
		}
		
		if(empty($data[0]) )
		{
		    _p("Inside ".__METHOD__." 1. AN ERROR OCCURRED");	
		    exit(0);
		}else if(empty($primarykeys))
		{
		    _p("Inside ".__METHOD__." 2. AN ERROR OCCURRED");	
		    exit(0);
		}
		
		$primarykeys = implode(",",$primarykeys);
		//_p($primarykeys);
		// END -------------------------------
		
		//START ------------------------------
		_p("Status for following rows will be made history : ");
		$this->showTabularData($data);
		// END --------------------------------

		//START ------------------------------
		// make all draft entries having version less than live version to history
		$this->db = $this->getWriteHandle();
		
		$query = "update course_location_attribute 
			   set status='history' 
			   where 1
			   AND course_id = ? 
			   AND status = 'draft'
			   AND course_location_attribute_id IN (".$primarykeys.") ";
			
		if(SHOW_QUERY_FLAG)
		    _p("<u>Inside : ".__METHOD__."</u><br/> QUERY : ".print_r($query,true));
		    
		if($this->cleanData)
		{
		    $this->db->query($query, array($listing_id));
		    _p("Update Done !!! Rows updated : ".$this->db->affected_rows());
		}
		else
		    _p("Update NOT done");
		// END -----------------------------
	}
	
	public function clearListingContactDetailsDataForMultipleStatus($listing_id, $status, $latest_version)
	{
		$highest_status_version = 0;

		if($latest_version < 1 && $status != 'live')
		{
		    _p("Cannot clean multiple entries of ".$status." status as its version in the listings_main table is ".$latest_version);
		    return;
		}
		
		// START-----------------------------
		// the query will return the latest version and their the tuples ids of the data
		$query = " SELECT version as latest_version, 
			   max(contact_details_id) as listing_id 
			   FROM listing_contact_details a
			   WHERE listing_type_id = ? 
			   AND listing_type = 'course' 
			   AND status = ? 
			   GROUP BY institute_location_id,listing_type_id ";
		
		$data = $this->db->query($query, array($listing_id, $status))->result_array();
		if(empty($data[0]))
		{
		    _p("Inside ".__METHOD__." 1. AN ERROR OCCURRED");	
		    exit(0);
		}
		$primarykeys = array();
		foreach($data as $row)
		{
		    $primarykeys[] = $row['listing_id'];
		}
		$primarykeys = implode(",",$primarykeys);
		//_p($primarykeys);
		// END --------------------------
		
		// START ------------------------
		// show the data that will be updated
		$query = "select * from listing_contact_details where 1
		          and listing_type_id = ? 
		          and status = ? 
			  and listing_type = 'course' 
			  and contact_details_id NOT IN (".$primarykeys.") ";
		_p("The data that will be updated to history status : ");
		$data = $this->db->query($query, array($listing_id, $status))->result_array();
		$this->showTabularData($data);
		// END ---------------------------

		// START --------------------------
		$this->db = $this->getWriteHandle();
		
		$query = "update listing_contact_details set status = 'history' where 1
		          and listing_type_id = ? 
		          and status = ? 
			  and listing_type = 'course' 
		          and contact_details_id NOT IN (".$primarykeys.") ";
			  
		if(SHOW_QUERY_FLAG)
		    _p("<u>Inside : ".__METHOD__."</u> <br/> QUERY : ".print_r($query,true));
		
		if($this->cleanData)
		{
		    $this->db->query($query, array($listing_id, $status));
		    _p("Update Done !!! Rows updated : ".$this->db->affected_rows());
		}
		else
		    _p("Update NOT Done");
		// END --------------------------

		// START ------------------------
		// update the version of all those entries that needs to be kept in the table(correct data) to the version of the listings_main
		$this->db = $this->getWriteHandle();
		
		$query = "update listing_contact_details  
			  set version = ? 
			  where 1
			  AND listing_type_id = ? 
			  AND status = ? 
			  AND contact_details_id IN (".$primarykeys.") ";
			
		if(SHOW_QUERY_FLAG)
		    _p("<u>Inside : ".__METHOD__."</u><br/> QUERY : ".print_r($query,true));
		    
		if($this->cleanData)
		{
		    $this->db->query($query, array($latest_version, $listing_id, $status));
		    _p("Update Done !!! Rows updated : ".$this->db->affected_rows());
		}
		else
		    _p("Update NOT done");
		// END -----------------------------
	    
	}
	
	
	public function clearListingContactDetailsForLiveDraftPositionIssue($listing_id, $live_version)
	{
		if($live_version < 1)
		{
		    _p("Cannot clean Live/draft positioning issue as live version provided  = ".$live_version);
		    return;
		}
		
		// START -------------------------------
		$query = "SELECT a.contact_details_id as listing_id, a.*
			    FROM listing_contact_details a
			    WHERE 1
			    AND a.listing_type = 'course' 
			    AND a.listing_type_id = ? 
			    AND a.status = 'draft'
			    AND a.version <= (SELECT b.version FROM listing_contact_details b
					    where 1
					    AND listing_type_id = ? 
					    AND b.listing_type = 'course' 
					    AND b.institute_location_id = a.institute_location_id
					    AND b.status = 'live' limit 1 )
			    group by institute_location_id,listing_type_id  ";
		
		$data = $this->db->query($query, array($listing_id, $listing_id))->result_array();
		
		$primarykeys = array();
		foreach($data as $row)
		{
		    $primarykeys[] = $row['listing_id'];
		}
		
		if(empty($data[0]) )
		{
		    _p("Inside ".__METHOD__." 1. AN ERROR OCCURRED");	
		    exit(0);
		}else if(empty($primarykeys))
		{
		    _p("Inside ".__METHOD__." 2. AN ERROR OCCURRED");	
		    exit(0);
		}
		
		$primarykeys = implode(",",$primarykeys);
		//_p($primarykeys);
		// END -------------------------------
		
		//START ------------------------------
		_p("Status for following rows will be made history : ");
		$this->showTabularData($data);
		// END --------------------------------

		//START ------------------------------
		// make all draft entries having version less than live version to history
		$this->db = $this->getWriteHandle();
		
		$query = "update listing_contact_details 
			   set status='history' 
			   where 1
			   AND listing_type = 'course' 
			   AND listing_type_id = ? 
			   AND status = 'draft'
			   AND contact_details_id IN (".$primarykeys.") ";
			
		if(SHOW_QUERY_FLAG)
		    _p("<u>Inside : ".__METHOD__."</u><br/> QUERY : ".print_r($query,true));
		    
		if($this->cleanData)
		{
		    $this->db->query($query, array($listing_id));
		    _p("Update Done !!! Rows Updated : ".$this->db->affected_rows());
		}
		else
		    _p("Update NOT done");
		// END -----------------------------
		
	}


	public function clearListingCategoryTableDataForMultipleStatus($listing_id, $status, $latest_version)
	{
		$highest_status_version = 0;

		if($latest_version < 1 && $status != 'live')
		{
		    _p("Cannot clean multiple entries of ".$status." status as its version in the listings_main table is ".$latest_version);
		    return;
		}
		
		// START-----------------------------
		// the query will return the latest version and their the tuples ids of the data
		$query = " SELECT version as latest_version, 
			   max(listing_category_id) as listing_id 
			   FROM listing_category_table a
			   WHERE listing_type_id = ? 
			   AND listing_type = 'course' 
			   AND status = ? 
			   GROUP BY category_id,listing_type_id ";
		
		$data = $this->db->query($query, array($listing_id, $status))->result_array();
		if(empty($data[0]))
		{
		    _p("Inside ".__METHOD__." 1. AN ERROR OCCURRED");	
		    exit(0);
		}
		$primarykeys = array();
		foreach($data as $row)
		{
		    $primarykeys[] = $row['listing_id'];
		}
		$primarykeys = implode(",",$primarykeys);
		_p($primarykeys);
		// END --------------------------
		
		// START ------------------------
		// show the data that will be updated
		$query = "select * from listing_category_table where 1
		          and listing_type_id = ? 
		          and status = ? 
			  and listing_type = 'course' 
			  and listing_category_id NOT IN (".$primarykeys.") ";
		_p("The data that will be updated to history status : ");
		$data = $this->db->query($query, array($listing_id, $status))->result_array();
		$this->showTabularData($data);
		// END ---------------------------

		// START --------------------------
		$this->db = $this->getWriteHandle();
		
		$query = "update listing_category_table set status = 'history' where 1
		          and listing_type_id = ? 
		          and status = ? 
			  and listing_type = 'course' 
		          and listing_category_id NOT IN (".$primarykeys.") ";
			  
		if(SHOW_QUERY_FLAG)
		    _p("<u>Inside : ".__METHOD__."</u> <br/> QUERY : ".print_r($query,true));
		
		if($this->cleanData)
		{
		    $this->db->query($query, array($listing_id, $status));
		    _p("Update Done !!! Rows updated : ".$this->db->affected_rows());
		}
		else
		    _p("Update NOT Done");
		// END --------------------------

		// START ------------------------
		// update the version of all those entries that needs to be kept in the table(correct data) to the version of the listings_main
		$this->db = $this->getWriteHandle();
		
		$query = "update listing_category_table  
			  set version = ?  
			  where 1
			  AND listing_type_id = ? 
			  AND status = ? 
			  AND listing_category_id IN (".$primarykeys.") ";
			
		if(SHOW_QUERY_FLAG)
		    _p("<u>Inside : ".__METHOD__."</u><br/> QUERY : ".print_r($query,true));
		    
		if($this->cleanData)
		{
		    $this->db->query($query, array($latest_version, $listing_id, $status));
		    _p("Update Done !!! Rows updated : ".$this->db->affected_rows());
		}
		else
		    _p("Update NOT done");
		// END -----------------------------
	}
	
	/*************************************************************************************
	* END  : DATA FIXING SECTION
	*************************************************************************************/

	private function showTabularData($data)
	{
	    echo "<table border=1 style='border:1px solid black;border-collapse:collapse;font-size:11px;'>";
	    foreach( $data as $value )
	    {
		echo "<tr>";
		foreach( $value as $key=>$value1 )
		{
		    echo "<th>".$key."</th>";
		}
		echo "</tr>";
		break;
	    }
	    foreach( $data as $value )
	    {
		echo "<tr>";
		foreach( $value as $value1 )
		{
		    echo "<td>".$value1."</td>";
		}
		echo "</tr>";
	    }
	    echo "</table>";
	}
	
	public function getErrorQueryLogs()
	{
	    	$query = "SELECT *
			  FROM queryFailureLogs
			  ";
	

		$data = $this->db->query($query)->result_array();
		return $data;
	}

	public function getQueryErrorLogs($filters = array())
	{
	    $whereClause = "";
	    $sortClause = " order by OccurenceTime desc ";
	    
	    if($filters["sortOrder"] == "occ_desc")
		$sortClause = " order by Occurences desc ";
	    else if($filters["sortOrder"] == "occ_asc")
		$sortClause = " order by Occurences asc ";

		$params = array();
	    if($filters["date"]) {
			$whereClause .= " and date(a.`time`) = ?";
			$params[] = $filters["date"];
	    }

	    if(!empty($filters["resultDays"])) {
			$whereClause .= " AND a.time between DATE_SUB( CURDATE( ) , INTERVAL ? DAY ) and CURDATE() ";
			$params[] = $filters["resultDays"];
	    }
		
	    $query = "SELECT
		    a.id as id,
		    a.`query` as Query,
		    a.`callingScript` as CallingScript,
		    a.`lineNumber` as LineNo,
		    a.`pageURL` as URL,
		    a.`error_text` as ErrorMessage,
		    a.`errorCode` as ErrorCode,
		    count(*) as Occurences,
		    max(a.`time`) as OccurenceTime 
		    FROM `errorQueryLogs` a WHERE 1
		    ".$whereClause."
		    group by `query`,`callingScript`,`errorCode`
		     ".$sortClause;

		    $data = $this->db->query($query, $params)->result_array();
		    return $data;
	}
	
	public function getExceptionLogs($filterArr = array() )//$sortOrder = array(), $resultDays = array())
	{
	    $whereClause = "";
	    $sortClause = " order by OccurenceTime desc ";
	    if($filterArr["sortOrder"] == "occ_desc")
		$sortClause = " order by Occurences desc ";
	    else if($filterArr["sortOrder"] == "occ_asc")
		$sortClause = " order by Occurences asc ";
		
		$params = array();
	    if(!empty($filterArr["resultDays"])) {
			$whereClause .= " AND a.addition_date between DATE_SUB( CURDATE( ) , INTERVAL ? DAY ) and CURDATE() ";
			$params[] = $filterArr["resultDays"];
	    }

	    $query = "SELECT
		    a.id as id,
		    a.`exception_msg` as ErrorMessage,
		    a.`source_file` as sourceFile,
		    a.`line_num` as LineNo,
		    a.`url` as URL,
		    a.`error_class` as ErrorClass,
		    a.`error_code` as ErrorCode,
		    count(*) as Occurences,
		    max(a.`addition_date`) as OccurenceTime 
		    FROM `exceptionLogs` a WHERE 1 
		    ".$whereClause."
		    group by `exception_msg`,`source_file`,`error_code`
		    ".$sortClause;

		$data = $this->db->query($query, $params)->result_array();
		return $data;
	}
	
	public function getSimilarExceptions($id)
	{
	    $query = "SELECT 
		       excp1.id as id,
		       excp1.`exception_msg` as ErrorMessage,
		       excp1.`source_file` as sourceFile,
		       excp1.`line_num` as LineNo,
		       excp1.`url` as URL,
		       excp1.`error_class` as ErrorClass,
		       excp1.`error_code` as ErrorCode,
		       1 as Occurences,
		       excp1.`addition_date` as OccurenceTime 
		      FROM 
		      exceptionLogs excp1
		      inner join 
		      exceptionLogs excp2
		      on(excp2.id = ? and excp1.exception_msg = excp2.exception_msg
		        and excp1.source_file = excp2.source_file and excp1.error_code = excp2.error_code)
		      WHERE 1
		      order by excp1.addition_date desc";
	    
	    $data = $this->db->query($query, array($id))->result_array();
	    return $data;
	}
	
	public function getSimilarQueryErrors($id)
	{
	    $query = "SELECT 
		        excp1.id as id,
			excp1.`query` as Query,
			excp1.`callingScript` as CallingScript,
			excp1.`lineNumber` as LineNo,
			excp1.`pageURL` as URL,
			excp1.`error_text` as ErrorMessage,
			excp1.`errorCode` as ErrorCode,
			1 as Occurences,
			excp1.`time` as OccurenceTime 
		      FROM 
		      errorQueryLogs excp1
		      inner join 
		      errorQueryLogs excp2
		      on(excp2.id = ? and excp1.query = excp2.query
		        and excp1.callingScript = excp2.callingScript and excp1.errorCode = excp2.errorCode)
		      WHERE 1
		      order by excp1.time desc";
	    
	    $data = $this->db->query($query, array($id))->result_array();
	    return $data;

	}

	public function getSlowQueryLogs()
	{
	    	$query = "SELECT *
			  FROM querySlowLogs";

		$data = $this->db->query($query)->result_array();
		return $data;
	}
	
	public function logErrorQuery1($finalArr)
	{
	    	$this->db = $this->getWriteHandle();
		$query = array();
		
		foreach($finalArr as $detailsArr)
		{
		    // value assignment
		    $mysql_error_code 	= isset($detailsArr['error_num'])?$detailsArr['error_num']:'0';
		    $mysql_error_text 	= isset($detailsArr['error_msg'])?$detailsArr['error_msg']:'none';
		    $callingScripts 	= isset($detailsArr['filename'])?$detailsArr['filename']:'none';
		    $sql		= isset($detailsArr['error_query'])?$detailsArr['error_query']:'';
		    $url		= isset($detailsArr['referer'])?$detailsArr['referer']:'';
		    $date		= isset($detailsArr['date'])?$detailsArr['date']:'';
		    $line_num		= isset($detailsArr['line_number'])?$detailsArr['line_number']:0;
		    		
		    $query[] = sprintf("('%s','%s',%d,'%s','%s',%d,'%s')",
				 mysql_escape_string($sql),
				 mysql_escape_string($callingScripts),
				 $line_num,
				 mysql_escape_string($url),
				 mysql_escape_string($mysql_error_text),
				 $mysql_error_code,
				 $date);
		}
		
		$query = implode(",", $query);
		
		$query = "INSERT INTO errorQueryLogs(query, callingScript, lineNumber, pageURL, error_text, errorCode, time ) VALUES  "
			  .$query;

		//_p($query);
		$this->db->query($query);
	    
	}
	
	public function logExceptions($finalArr)
	{
	    error_log("================ INside logExceptions =====================");
	    	//$this->db = $this->getWriteHandle();
		$query = array();
		$detailsArr = $finalArr;
		//foreach($finalArr as $detailsArr)
		//{
		    // value assignment
		    $mysql_error_code 	= isset($detailsArr['code'])?$detailsArr['code']:'0';
		    $mysql_error_text 	= isset($detailsArr['exceptionMsg'])?$detailsArr['exceptionMsg']:'none';
		    $callingScripts 	= isset($detailsArr['file'])?$detailsArr['file']:'none';
		    $error_class		= isset($detailsArr['class'])?$detailsArr['class']:'';
		    $url		= isset($detailsArr['url'])?$detailsArr['url']:'';
		    $date		= date("Y-m-d h:i:s");//isset($detailsArr['date'])?$detailsArr['date']:'';
		    $line_num		= isset($detailsArr['line'])?$detailsArr['line']:0;
		    		
		    $query[] = sprintf("('%s','%s',%d,'%s',%d,'%s','%s')",
				 mysql_escape_string($mysql_error_text),
				 mysql_escape_string($callingScripts),
				 $line_num,
				 mysql_escape_string($url),
				 $mysql_error_code,
				 mysql_escape_string($error_class),
				 $date);
		//}
		
		$query = implode(",", $query);
		
		$query = "INSERT INTO exceptionLogs(exception_msg, source_file, line_num, url, error_code, error_class, addition_date ) VALUES  "
			  .$query;

		//_p($query);
		$this->db->query($query);
	    
	}
	
	public function logSlowQuery($backtraceObj, $sql, $timeTaken, $conn_id)
	{
	    	$this->db = $this->getWriteHandle();
		
		// initialization
		$host_info 		= "";
		$callingScripts 	= array();
		$url			= "";
		
		// value calculation and assignment
		$host_info		= $conn_id->host_info;
		$url			= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		
		foreach( $backtraceObj as $file )
		{
			$callingScripts[] = $file['file']. " $$ ".$file['class']. " $$ ".$file['function']. " $$ ".$file['line'];
		}
		
		$callingScripts = implode(" @@@ ",$callingScripts);
		
		$query = sprintf("INSERT INTO querySlowLogs(query, callingScript, pageURL, timeTaken, hostInfo ) VALUES ('%s','%s','%s','%s','%s')",
				 mysql_escape_string($sql),
				 mysql_escape_string($callingScripts),
				 mysql_escape_string($url),
				 $timeTaken,
				 mysql_escape_string($host_info));
		
		$this->db->query($query);
	}
	
	
	public function logErrorQuery($backtraceObj, $sql, $conn_id)
	{
	    	$this->db = $this->getWriteHandle();
		
		// initialization
		$host_info 		= "";
		$mysql_error_text 	= "";
		$mysql_error_code 	= "";
		$callingScripts 	= array();
		$url			= "";
		
		// value calculation and assignment
		$host_info		= $conn_id->host_info;
		$mysql_error_text	= $conn_id->error;
		$mysql_error_code	= $conn_id->errno;
		$url			= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		
		foreach( $backtraceObj as $file )
		{
			//$callingScripts[] = $file['file']. " $$ ".$file['class']. " $$ ".$file['function']. " $$ ".$file['line'];
			$filename = $file['file'];
			$line_num = $file['line'];
			break;
		}
		
		$callingScripts = implode(" @@@ ",$callingScripts);
		
		$query = sprintf("INSERT INTO errorQueryLogs(query, callingScript, lineNumber, pageURL, error_text, errorCode, time ) VALUES ('%s','%s','%s','%s','%s',%d,%s)",
				 mysql_escape_string($sql),
				 mysql_escape_string($filename),
				 mysql_escape_string($line_num),
				 mysql_escape_string($url),
				 mysql_escape_string($mysql_error_text),
				 $mysql_error_code,
				 'now()');
		
		error_log("::::ROMIL::::".print_r($query,true));
		//_p($query);
		//_p($this->db);
		$this->db->query($query);
		//@mysql_select_db($this->database, $this->conn_id);
		//@mysql_query($sql, $this->db);
	}
	
	public function getLatestErrorQueryTime()
	{
	    $query = "SELECT max( `time` ) as time
			FROM `errorQueryLogs`";
	    
	    $data = $this->db->query($query)->result_array();
	    return $data;

	}
	
	public function getLatestExceptionTime()
	{
	    $query = "SELECT max( `addition_date` ) as time
			FROM `exceptionLogs`";
	    
	    $data = $this->db->query($query)->result_array();
	    return $data;

	}

	public function getCoursesToBeValidated($statusArr = array('live'))
	{
	    $query = "SELECT distinct(lm1.listing_type_id) 
		     FROM 
		     listings_main lm1 
		     WHERE 1
		     and lm1.listing_type = 'course'
		     and lm1.status in (?) ";
	    
	    $data = $this->db->query($query, array($statusArr))->result_array();
		
	    return $data;
	}
	
	public function getAbroadCoursesIds()
	{
	    $query = "SELECT distinct(`course_id`) as listing_type_id FROM `abroadCategoryPageData` WHERE 1";

	    $data = $this->db->query($query)->result_array();
	    
	    return $data;
	}
	
	public function getAllStatusOfListingMain($listing_type_id, $listing_type)
	{
	    $query = "SELECT distinct(status) FROM `listings_main` WHERE 1
		      and listing_type_id = ? 
		      and listing_type = ? ";
	    
	    $data = $this->db->query($query, array($listing_type_id, $listing_type))->result_array();
	    
	    return $data;
	}
	
	public function getAllStatusOfCourseDetails($listing_type_id)
	{
	    $query = "SELECT distinct(status) FROM `course_details` WHERE 1
		       and course_id = ? ";
	    
	    $data = $this->db->query($query, array($listing_type_id))->result_array();
	    
	    return $data;
	}
	
	function getAllVersionCountInListingMain($listing_type_id, $listing_type)
	{
	    $query = "SELECT version, count(version) as versionCount FROM `listings_main` WHERE 1
		       and listing_type_id = ? 
		       and listing_type = ? 
		       and version = 1";
	    
	    $data = $this->db->query($query, array($listing_type_id, $listing_type))->result_array();
	    
	    return $data;
	}
	
	function getAllVersionCountInCourseDetails($listing_type_id)
	{
	    $query = "SELECT version, count(version) as versionCount FROM `course_details` WHERE 1
		       and course_id  = ? 
		       and version = 1";
	    
	    $data = $this->db->query($query, array($listing_type_id))->result_array();
	    
	    return $data;
	}
	
	function getInstituteIdsOfCourses($courseIds)
	{
	    $query = "SELECT distinct(course_id), institute_id FROM `course_details` 
		      where course_id in (?)";
	    
	    $data = $this->db->query($query, array($courseIds))->result_array();
	    
	    return $data;
	}
	
	
	public function clearListingsMainDataForMultipleStatus($listing_id, $status)
	{
		$highest_status_version = 0;

		//if($latest_version < 1)
		//{
		//    _p("Cannot clean multiple entries of ".$status." status and its version in the listings_main table is ".$latest_version);
		//    return;
		//}

		// START ----------------------------------
		$query = "SELECT a.*,a.version as version, a.listing_id as id FROM `listings_main` a WHERE 1
			and a.status = ? 
			and a.listing_type_id = ? 
			and a.listing_type = 'course'
			order by a.version DESC , a.listing_id DESC 
			limit 1 ";
	    
		$data = $this->db->query($query, array($status, $listing_id))->result_array();
		if(empty($data[0]))
		{
		    _p("Inside ".__METHOD__." 1. AN ERROR OCCURRED");
		    exit(0);
		}
		
		$primaryKey = $data[0]['id'];
		$highest_status_version = $data[0]['version'];
		//_p($primaryKey);
		// END ----------------------------------
		
		// START ------------------------
		// show the data that will be updated
		$query = "SELECT  a.listing_id as id,a.* FROM `listings_main` a WHERE 1
			    and a.status = ? 
			    and a.listing_type_id = ? 
			    and a.listing_type = 'course'
			    and a.listing_id not in (".$primaryKey.")"
			    ;
		$data = $this->db->query($query, array($status, $listing_id))->result_array();
		_p("The data that will be updated to history status : ");
		// $data = $this->db->query($query)->result_array();
		$this->showTabularData($data);
		// END ---------------------------
		
		// START --------------------------------
		
		   
		$this->db = $this->getWriteHandle();
		
		// Update all redundant entries to history status
		$query = "update listings_main set status='history' where 1 
			  and listing_type_id = ? 
			  and listing_type = 'course'
			  and status = ? and listing_id not in (".$primaryKey.")";
		if(SHOW_QUERY_FLAG)
		    _p("<u>Inside : ".__METHOD__."</u> <br/> QUERY : ".print_r($query,true));
		
		if($this->cleanData)
		{
			$this->db->query($query, array($listing_id, $status));
			_p("Update Done !!! Rows updated : ".$this->db->affected_rows());
		}
		else
		    _p("Update NOT done");
		// END ----------------------------------
	}

	public function clearListingMainsDataForLiveDraftPositionIssue($listing_id)
	{
		
		$query = "SELECT  a.version FROM `listings_main` a WHERE 1
			    and a.status = 'live' 
			    and a.listing_type_id = ? 
			    and a.listing_type = 'course' limit 1 ";
			    
		$data = $this->db->query($query, array($listing_id))->result_array();
		if(empty($data[0]))
		{
		    _p("Inside ".__METHOD__." 1. AN ERROR OCCURRED");
		    exit(0);
		}
		
		$live_version = $data[0]['version'];
		
		// make all draft entries having version less than live version to history
		$this->db = $this->getWriteHandle();
		
		$query = "update listings_main set status='history' where listing_type = 'course' and listing_type_id = ? 
			and status = 'draft' and version <= ? ";
			
		if(SHOW_QUERY_FLAG)
		    _p("<u>Inside : ".__METHOD__."</u><br/> QUERY : ".print_r($query,true));
		
		if($this->cleanData)
		{
		    $this->db->query($query, array($listing_id, $live_version));
		    _p("Update Done !!! Rows Updated : ".$this->db->affected_rows());
		}
		else
		    _p("Update NOT done");
	}
	
	function getCoursesOfInstitutes($institute_id)
	{
	    $query = " select distinct(course_id) from course_details
			where institute_id = ? 
			and status in ('draft','live')";
	    
	    $data = $this->db->query($query, array($institute_id))->result_array();
	    
	    return $data;
	}
	
	
	public function clearListingMainsDataForLiveVersionInconsistency($listing_id)
	{
		// make the version of the live entry as 1
		$this->db = $this->getWriteHandle();
		
		$query = "update listings_main set version = 1 where listing_type = 'course' and listing_type_id = ? 
			and status = 'live' ";
			
		if(SHOW_QUERY_FLAG)
		    _p("<u>Inside : ".__METHOD__."</u><br/> QUERY : ".print_r($query,true));
		
		if($this->cleanData)
		{
		    $this->db->query($query, array($listing_id));
		    _p("Update Done !!! Rows Updated : ".$this->db->affected_rows());
		}
		else
		    _p("Update NOT done");
	}

	public function clearCourseDetailsDataForLiveVersionInconsistency($listing_id)
	{
		// make the version of the live entry as 1
		$this->db = $this->getWriteHandle();
		
		$query = "update course_details set version = 1 where course_id = ? 
			and status = 'live' ";
			
		if(SHOW_QUERY_FLAG)
		    _p("<u>Inside : ".__METHOD__."</u><br/> QUERY : ".print_r($query,true));
		
		if($this->cleanData)
		{
		    $this->db->query($query, array($listing_id));
		    _p("Update Done !!! Rows Updated : ".$this->db->affected_rows());
		}
		else
		    _p("Update NOT done");
	}
	
	function checkAndCleanVersionInconsistency($tableName, $listing_id, $draft_version, $live_version, $field_type)
	{
	    if($field_type == 'listing_type_id')
		$where = " and listing_type_id = ? and listing_type = 'course' ";
	    else if($field_type == 'course_id')
		$where = " and course_id = ?" ;
	    else if($field_type == 'typeId')
		$where = " and typeId = ?" ;
	    else if($field_type == 'listing_id')
		$where = " and listing_id = ?" ;
	    else if($field_type == 'clientCourseID')
		$where = " and clientCourseID = ?" ;
	    
	    if(empty($where))
	    {
		_p("<u>Inside : ".__METHOD__."</u><br/> Where clause is empty for : ".$tableName."....cannot proceed");
		return;
	    }
		
	    if($draft_version === 0 || $draft_version === 1)
	    {
		$query = " select * from ".$tableName."
			where 1 ".$where." 
			AND status = 'draft'
			AND version != ? " ;
		
		$data = $this->db->query($query, array($listing_id, $draft_version))->result_array();
		if(!empty($data[0]))
		{
		    _p("Inside ".__METHOD__." Draft version inconsistency found in ".$tableName);
		
		    $query = " update ".$tableName."
			    set version =  ? 
			    where 1 ".$where." 
			    AND status = 'draft'
			    AND version != ? " ;
			    
		    if(SHOW_QUERY_FLAG)
			_p("<u>Inside : ".__METHOD__."</u><br/> QUERY : ".print_r($query,true));
		    
		    if($this->cleanData)
		    {
			$this->db->query($query, array($draft_version, $listing_id, $draft_version));
			_p("Update Done !!! Rows Updated : ".$this->db->affected_rows());
		    }
		    else
			_p("Update NOT done");
		}
	    }
	    
	    if($live_version === 0 || $live_version === 1)
	    {
		$query = " select * from ".$tableName." 
			where 1 ".$where." 
			AND status = 'live'
			AND version != ? " ;
		
		$data = $this->db->query($query, array($listing_id, $live_version))->result_array();
		if(!empty($data[0]))
		{
		    _p("Inside ".__METHOD__." Live version inconsistency found in ".$tableName);
		    
		    $query = " update ".$tableName."
			    set version =  ? 
			    where 1 ".$where." 
			    AND status = 'live'
			    AND version != ? " ;
			    
		    if(SHOW_QUERY_FLAG)
			_p("<u>Inside : ".__METHOD__."</u><br/> QUERY : ".print_r($query,true));
		    
		    if($this->cleanData)
		    {
			$this->db->query($query, array($live_version, $listing_id, $live_version));
			_p("Update Done !!! Rows Updated : ".$this->db->affected_rows());
		    }
		    else
			_p("Update NOT done");
		}
	    }
	}

	public function getDirtySnapshotCourseData(){
		$sql = "SELECT DISTINCT (university_id) FROM snapshot_courses WHERE course_name LIKE  '%?%'";
		return $this->db->query($sql)->result_array();
	}
	
	public function cleanDirtySnapshotCourseData(){
		$sql = "update snapshot_courses set course_name = replace(course_name,'?','') where course_name like '%?%'";
		error_log("::RAHUL::".$sql);
		$this->db = $this->getWriteHandle();
		$this->db->query($sql);
	}
}
