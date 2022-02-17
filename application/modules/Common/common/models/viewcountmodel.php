<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Viewcountmodel extends MY_Model {

    var $CI;
    var $cacheLib;
    function __construct(){
        parent::__construct('Beacon');
    }

    function updateViewCounts($request,$browse_page)
    {
        $this->load->helper('date');
	$parameters = $request->output_parameters();
	
	$appId=$parameters['0'];
	$sess_id = $parameters['3'];
	$userId = $parameters['4'];
	$clientIP = getenv("HTTP_TRUE_CLIENT_IP")?getenv("HTTP_TRUE_CLIENT_IP"):(getenv("HTTP_X_FORWARDED_FOR")?getenv("HTTP_X_FORWARDED_FOR"):getenv("REMOTE_ADDR"));
	
	$dbHandle = $this->dbLibObj->getWriteHandle();
        if($dbHandle == ''){
            log_message('error','getCountryTable can not create db handle');
        }
	
        if($browse_page == 'listings')
        {
	    $type_id = $parameters['1'];  // stores listingid
	    $listing_type = $parameters['2'];
	    
	    //Added listing track for 1 year
	    $this->trackListingPageViews($userId, $sess_id, $clientIP, $listing_type, $type_id);
	    
	    if($listing_type == 'institute' || $listing_type == 'university_national') {  // perform conversions so as to enable comparison to enum values in view_Count_Details table's listingType field
		$listing_type = 2;
	    }
	    else if ($listing_type == 'course') {
		$listing_type = 1;
	    }
	    
	    $free_paid_queryCmd = "select pack_type from listings_main where listing_type_id = ? and listing_type = ? and status IN (1,10)";
	    $query_free_paid = $dbHandle->query($free_paid_queryCmd, array((int) $type_id,$listing_type));
	    
	    $listing_type1 = 0;
	    foreach($query_free_paid->result() as $row) // check whether listing is free or paid, $listing_type1 indicates corresponding paid listings
	    {
		if(($row->pack_type == 1) || ($row->pack_type == 2) || ($row->pack_type == 375))  //pack type 375  : suggested by Amit K
		{
		    if($listing_type == 1)
		    {
			$listing_type1 = 10;
		    }
		    else
		    {
			$listing_type1 = 11;
		    }
		}
	    }
	    $queryCmd = "select view_Date,listing_id,listingType from view_Count_Details where view_Date = curdate() and listing_id = ? and listingType in ($listing_type,$listing_type1)";
	    $query = $dbHandle->query($queryCmd, array((int) $type_id));
		
	    if($query->num_rows() > 0) // case when the view_date already exists, then increase view count by 1
	    {
		$queryCmd1 = "update view_Count_Details use index(listing_id) set no_Of_Views = no_Of_Views + 1 where view_Date = curdate() and listing_id = ? and listingType in ($listing_type,$listing_type1)";
		$query1 = $dbHandle->query($queryCmd1, array((int) $type_id));
	    }
	    else
	    {
		  $listing_type = ($listing_type1==0)?$listing_type:$listing_type1;
		  //Check if any deleted entry of the same entity exists in the DB. If yes, then update that entry for todays date, else insert a new row
		  $queryCmd = "select id,view_Date from view_Count_Details where listing_id = ? and listingType = ? and is_deleted = 1";
		  $query = $dbHandle->query($queryCmd, array((int) $type_id,$listing_type));
		  $id = 0;
		  foreach ($query->result_array() as $row){
			  $id  = $row['id'];
		  }
		  if($query->num_rows() > 0)
		  {
		      $queryCmd1 = "update view_Count_Details set no_Of_Views = 1, is_deleted = 0, view_Date = curDate() where listing_id = ? and listingType = ? and id = ".$id;
		      $query1 = $dbHandle->query($queryCmd1, array((int) $type_id,$listing_type));
		  }
		  else
		  {
		      $queryCmd1 = "insert into view_Count_Details set view_Date = curdate(),listing_id = ?,listingType = ?,no_Of_Views = 1";
		      $query1 = $dbHandle->query($queryCmd1, array((int) $type_id,$listing_type));
		  }
	    }
        }
        else if($browse_page == 'blogs')
        {
            $blog_id = $parameters['1'];
            $listing_type  = 8;
            $queryCmd = "select view_Date,listing_id,listingType from view_Count_Details where view_Date = curdate() and listing_id = ? and listingType = ? ";
            $query = $dbHandle->query($queryCmd, array((int) $blog_id,$listing_type));
	    if($query->num_rows() > 0) // case when the view_date already exists, then increase view count by 1
	    {
		  $queryCmd1 = "update view_Count_Details use index(listing_id) set no_Of_Views = no_Of_Views + 1 where view_Date = curdate() and listing_id = ? and listingType = ?";
		  $query1 = $dbHandle->query($queryCmd1, array((int) $blog_id,$listing_type));
	    }
	    else 
	    {
		  //Check if any deleted entry of the same entity exists in the DB. If yes, then update that entry for todays date, else insert a new row
		  $queryCmd = "select id,view_Date from view_Count_Details where listing_id = ? and listingType = ? and is_deleted = 1";
		  $query = $dbHandle->query($queryCmd, array((int) $blog_id,$listing_type));
		  $id = 0;
		  foreach ($query->result_array() as $row){
			  $id  = $row['id'];
		  }
		  if($query->num_rows() > 0)
		  {
		      $queryCmd1 = "update view_Count_Details set no_Of_Views = 1, is_deleted = 0, view_Date = curDate() where listing_id = ? and listingType = ? and id = ".$id;
		      $query1 = $dbHandle->query($queryCmd1, array((int) $blog_id,$listing_type));
		  }
		  else
		  {
		      $queryCmd1 = "insert into view_Count_Details set view_Date = curdate(),listing_id = ?,listingType = ?,no_Of_Views = 1";
		      $query1 = $dbHandle->query($queryCmd1, array((int) $blog_id,$listing_type));
		  }
             }
        }
        elseif($browse_page == 'qna')
        {
            $msg_id = $parameters['1'];
	    $user_id = $parameters['2'];
	    $listing_type = 9;

	    $queryCmd = "select view_Date,listing_id,listingType from view_Count_Details where view_Date = curdate() and listing_id = ? and listingType = ?";
	    $query = $dbHandle->query($queryCmd, array((int) $msg_id,$listing_type));

	    if($query->num_rows() > 0) // case when the view_date already exists, then increase view count by 1
	    {
		$queryCmd1 = "update view_Count_Details use index(listing_id) set no_Of_Views = no_Of_Views + 1 where view_Date = curdate() and listing_id = ? and listingType = ?";
		$query1 = $dbHandle->query($queryCmd1, array((int) $msg_id,$listing_type));
	    }
	    else
	    {
		  //Check if any deleted entry of the same entity exists in the DB. If yes, then update that entry for todays date, else insert a new row
		  $queryCmd = "select id,view_Date from view_Count_Details where listing_id = ? and listingType = ? and is_deleted = 1";
		  $query = $dbHandle->query($queryCmd, array((int) $msg_id,$listing_type));
		  $id = 0;
		  foreach ($query->result_array() as $row){
			  $id  = $row['id'];
		  }
		  if($query->num_rows() > 0)
		  {
		      $queryCmd1 = "update view_Count_Details set no_Of_Views = 1, is_deleted = 0, view_Date = curDate() where listing_id = ? and listingType = ? and id = ".$id;
		      $query1 = $dbHandle->query($queryCmd1, array((int) $msg_id,$listing_type));
		  }
		  else
		  {
		      $queryCmd1 = "insert into view_Count_Details set view_Date = curdate(),listing_id = ?,listingType = ?,no_Of_Views = 1";
		      $query1 = $dbHandle->query($queryCmd1, array((int) $msg_id,$listing_type));
		  }
	    }

        } else if($browse_page == 'abroadlistings')
        {
            $listing_id = $parameters['1'];
            $listing_type = $parameters['2'];
	    
	    if ($listing_type == 'department') {
		$listing_type = 'institute';
	    }
	    
	    if($listing_type == 'institute' || $listing_type == 'course') {
		$this->trackListingPageViews($userId, $sess_id, $clientIP, $listing_type, $listing_id);
	    }
	    
            if($listing_type == 'course') {
            	$listing_type = 12;
            } else if($listing_type == 'university' ) {
            	$listing_type = 13;
            } else if ($listing_type == 'department' || $listing_type == 'institute') {
            	$listing_type = 14;
            } else if ($listing_type == 'snapshotcourse') {
            	$listing_type = 15;
            }
            $queryCmd = "select view_Date,listing_id,listingType from view_Count_Details where view_Date = curdate() and listing_id = ? and listingType = ? ";
            $query = $dbHandle->query($queryCmd, array((int) $listing_id,$listing_type));
	    if($query->num_rows() > 0) // case when the view_date already exists, then increase view count by 1
	    {
		  $queryCmd1 = "update view_Count_Details use index(listing_id) set no_Of_Views = no_Of_Views + 1 where view_Date = curdate() and listing_id = ? and listingType = ?";
		  $query1 = $dbHandle->query($queryCmd1, array((int) $listing_id,$listing_type));
	    }
	    else 
	    {
		  //Check if any deleted entry of the same entity exists in the DB. If yes, then update that entry for todays date, else insert a new row
		  $queryCmd = "select id,view_Date from view_Count_Details where listing_id = ? and listingType = ? and is_deleted = 1";
		  $query = $dbHandle->query($queryCmd, array((int) $listing_id,$listing_type));
		  $id = 0;
		  foreach ($query->result_array() as $row){
			  $id  = $row['id'];
		  }
		  if($query->num_rows() > 0)
		  {
		      $queryCmd1 = "update view_Count_Details set no_Of_Views = 1, is_deleted = 0, view_Date = curDate() where listing_id = ? and listingType = ? and id = ".$id;
		      $query1 = $dbHandle->query($queryCmd1, array((int) $listing_id,$listing_type));
		  }
		  else
		  {
		      $queryCmd1 = "insert into view_Count_Details set view_Date = curdate(),listing_id = ?,listingType = ?,no_Of_Views = 1";
		      $query1 = $dbHandle->query($queryCmd1, array((int) $listing_id,$listing_type));
		  }
         }
        }
        elseif($browse_page == 'exampage')
        {
            $pageId = $parameters['1']; // group Id of exam Page
            $pageType  = 'exam_group'; //page type

            error_log('Product paeg Type'.$pageType);
            $queryCmd = "select view_Date,listing_id,listingType from view_Count_Details where view_Date = curdate() and listing_id = ? and listingType = ? ";
            $query = $dbHandle->query($queryCmd, array((int) $pageId,$pageType));
            error_log('Product page Type'.print_r($dbHandle->last_query(),true));
	    	if($query->num_rows() > 0) // case when the view_date already exists, then increase view count by 1
	    	{
		  		$queryCmd1 = "update view_Count_Details use index(listing_id) set no_Of_Views = no_Of_Views + 1 where view_Date = curdate() and listing_id = ? and listingType = ?";
		  		$query1 = $dbHandle->query($queryCmd1, array((int) $pageId,$pageType));
	    	}
		    else 
		    {
				//Check if any deleted entry of the same entity exists in the DB. If yes, then update that entry for todays date, else insert a new row
				$queryCmd = "select id,view_Date from view_Count_Details where listing_id = ? and listingType = ? and is_deleted = 1";
				  $query = $dbHandle->query($queryCmd, array((int) $pageId,$pageType));
				  $id = 0;
				  foreach ($query->result_array() as $row){
					  $id  = $row['id'];
				  }
				  if($query->num_rows() > 0)
				  {
				      $queryCmd1 = "update view_Count_Details set no_Of_Views = 1, is_deleted = 0, view_Date = curDate() where listing_id = ? and listingType = ? and id = ".$id;
				      $query1 = $dbHandle->query($queryCmd1, array((int) $pageId,$pageType));
				  }
				  else
				  {
				      $queryCmd1 = "insert into view_Count_Details set view_Date = curdate(),listing_id = ?,listingType = ?,no_Of_Views = 1";
				      $query1 = $dbHandle->query($queryCmd1, array((int) $pageId,$pageType));
				  }
             }
        }
    }
    
    function increaseActivityTrackCount($courseId, $clickedCourseId, $instituteId, $action, $widget, $algo, $userId) {
	$dbHandle = $this->dbLibObj->getWriteHandle();
	
	if($widget == 'listingPageTopLinks') {
	    $action = 'LP_ContactDetails';
	    $widget = 'ContactDetails_Top';
	}
	else if($widget == 'listingPageBottomNew') {
	    $action = 'LP_ContactDetails';
	    $widget = 'ContactDetails_Bottom';
	}
	else if($widget == 'listingPageRight') {
	    $action = 'LP_ ReqEBrochure';
	    $widget = 'ReqEBrochure_Right';
	}
	else if($widget == 'listingPageBottom') {
	    $action = 'LP_ ReqEBrochure';
	    $widget = 'ReqEBrochure_Bottom';
	}
	else if($widget == 'listingPageBottom') {
	    $action = 'LP_ ReqEBrochure';
	    $widget = 'ReqEBrochure_Top';
	}
	else if($widget == 'ebrocher') {
	    $action = 'CP_ReqEBrochure';
	    $widget = 'ReqEBrochure_CatPage';
	}
	else if($widget == 'listingPageRightNational') {
		$action = 'LP_ReqEBrochure';
	    $widget = 'ReqEBrochure_InstiRight';
	}
	else if($widget == 'listingPageBottomNational') {
		$action = 'LP_ReqEBrochure';
	    $widget = 'ReqEBrochure_InstiBottom';
	}
	else if($widget == 'listingPageBellyNationalForInstitute') {
		$action = 'LP_ReqEBrochure';
	    $widget = 'ReqEBrochure_InstiTop';
	}
	
	$sessionId = sessionId();
	$clientIP = getenv("HTTP_TRUE_CLIENT_IP")?getenv("HTTP_TRUE_CLIENT_IP"):(getenv("HTTP_X_FORWARDED_FOR")?getenv("HTTP_X_FORWARDED_FOR"):getenv("REMOTE_ADDR"));
	
	$queryCmd = "SELECT * FROM user_session_info WHERE session_id = ?";
	$query = $dbHandle->query($queryCmd, $sessionId);
	
	if($query->num_rows() == 0) {
	    $queryCmd = "INSERT INTO user_session_info SET session_id = ? ,user_id = ? ,user_ip = ? ";
	    $query = $dbHandle->query($queryCmd, array($sessionId, $userId, $clientIP));
	}
	
	$queryCmd = "SELECT id FROM user_session_info WHERE session_id = ? ORDER BY time DESC LIMIT 1";
	$results = $dbHandle->query($queryCmd, $sessionId)->result_array();
	$user_session_id = $results[0]['id'];
	
	if($widget == 'ReqEBrochure_CatPage' || $widget == 'CP_Reco_popupLayer' || $widget == 'CP_Reco_divLayer' || $widget == 'CoursePage_Reco' || $widget == 'CP_Reco_popupLayer_SA' || $widget == 'COMPARE_PAGE') {
	    $table = 'category_action_track';
	}
	else {
	    $table = 'listing_action_track';
	}
	
	$queryCmd = "INSERT INTO $table SET user_session_id = ?, course_id = ?, institute_id = ?, clicked_course_id = ?, action = ?, widget = ?, algo = ?";
	$query = $dbHandle->query($queryCmd, array($user_session_id, $courseId, $instituteId, $clickedCourseId, $action, $widget, $algo));
    }
    
    function getInstituteFlagshipCourse($instituteId)
    {
	$dbHandle = $this->dbLibObj->getReadHandle();
	
	if(isset($instituteId))
	{
		$query = "SELECT course_id
			  FROM course_details
			  WHERE institute_id = ?
			  AND STATUS = 'live'
			  ORDER BY course_order ASC
			  LIMIT 1";
		
		$results = $dbHandle->query($query,array($instituteId))->result_array();
		$flagshipCourse = $results[0]['course_id'];
		
		return $flagshipCourse;
	}
    }
    
    function trackListingPageViews($userId, $sess_id, $clientIP, $listing_type, $listing_type_id) {
	$dbHandle = $this->dbLibObj->getWriteHandle();
	if($listing_type == 'institute') {
	    $listing_type = 2;
	    $is_institute = 1;
	}
	elseif ($listing_type == 'course') {
	    $listing_type = 1;
	    $is_institute = 0;
	}
	elseif ($listing_type == 'university_national') {
	    $listing_type = 2;
	    $is_institute = 1;
	}else{
		// no other listing type allowed here
		return false;
	}
	
	$visitTime = date('Y-m-d H:i:s', strtotime("-1 year"));
	
	//get session id
	$queryCmd = "SELECT id FROM user_session_info WHERE session_id = ? ORDER BY time DESC LIMIT 1";
	$results = $dbHandle->query($queryCmd,array($sess_id))->result_array();
	$user_session_id = $results[0]['id'];
	
	if(empty($user_session_id)) {
	    //insert user session info
	    $queryCmd = "INSERT INTO user_session_info SET session_id = ?,user_id = ?,user_ip = ?";
	    $query = $dbHandle->query($queryCmd, array($sess_id, $userId, $clientIP));
	    $user_session_id = $dbHandle->insert_id();
	}	
	
	$queryCondition ='';

	//insert in listing track
	if($listing_type == 1) {
	    $queryCondition = 'course_id = '.$listing_type_id.' AND ';
	    $queryConditionUpdate = 'course_id = '.$listing_type_id.', ';
	}
	else if($listing_type == 2) {
	    $queryCondition = 'institute_id = '.$listing_type_id.' AND ';
	    $queryConditionUpdate = 'institute_id = '.$listing_type_id.', ';
	}
	
	//default else is missing, moved 'and' clause to above condition

	$queryCmd = "SELECT id FROM listing_track WHERE ".$queryCondition." is_deleted = 1 AND visit_time < '$visitTime' ORDER BY visit_time ASC LIMIT 1";
	$results = $dbHandle->query($queryCmd)->result_array();
	$listing_track_id = $results[0]['id'];
	
	if(isset($id)) {
	    $queryCmd = "UPDATE listing_track SET user_session_id = ?, is_institute = ?, is_deleted = 0, visit_time = NOW() WHERE id = ?";
	    $query = $dbHandle->query($queryCmd, array($user_session_id, $is_institute, $listing_track_id));
	}
	else {
	    $queryCmd = "INSERT INTO listing_track SET user_session_id = ?, ".$queryConditionUpdate." is_institute = ?, is_deleted = 0";
	    $query = $dbHandle->query($queryCmd, array($user_session_id, $is_institute));
	}
    }
    
    
    public function getSessionViewedCourseListings() {
    	$dbHandle = $this->dbLibObj->getReadHandle();
    	$sessionId = sessionId();
    	if(empty($sessionId)) {
    		return array();
    	}
    	$queryCmd = "SELECT course_id 
					 FROM listing_track 
					 WHERE user_session_id = (SELECT id FROM user_session_info WHERE session_id = ? ORDER BY time DESC LIMIT 1) 
					 AND course_id is NOT NULL
					 ORDER BY id DESC LIMIT 1";
    	$results = $dbHandle->query($queryCmd, $sessionId)->result_array();
       	return $results;    	
    }

    public function updateNewInstituteId($oldId,$newId){
    	if(empty($oldId) || empty($newId)){
    		return;
    	}
    	$dbHandle = $this->dbLibObj->getWriteHandle();
    	$query = "update view_Count_Details set listing_id = ? where listingType in ('institute_free','institute_paid') and listing_id = ?";
    	$dbHandle->query($query,array($newId,$oldId));
    }

}
?>
