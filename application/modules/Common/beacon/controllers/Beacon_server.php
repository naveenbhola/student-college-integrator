<?php

class Beacon_server extends MX_Controller {

    /**
    *	index function to recieve the incoming request
    */
    function init(){
	    $this->load->library('messageboardconfig'); 
	    $this->load->library('listingconfig');
	    $this->load->library('blogconfig');
	    $this->load->library('dbLibCommon');
            $this->dbLibObj = DbLibCommon::getInstance('Beacon');
	    return true;
    }

    /** 
    * function name: viewsDecayHammer
    * parametres: AppId
    * Brief: deacy the view count of listing, questions and blogs by half after each month. Also, decay the weight of going from one listing to another by 20%
    * This function has been added for Also viewed and Most viewed algorithms
    */
    function viewsDecayHammer($appId=12)
    {
	$this->init();
	$this->validateCron();
        $dbConfig = array( 'hostname'=>'localhost');

	//Get the DB Handle for listing
	//$this->listingconfig->getDbConfig_test($appId,$dbConfig);
	//$dbHandle = $this->load->database($dbConfig,TRUE);
	$dbHandle = $this->dbLibObj->getWriteHandle();
        if($dbHandle == ''){
            error_log_shiksha('error','viewsDecayHammer can not create listing db handle');
        }

	//update the listing_main table
	$queryCm5 = "UPDATE listings_main SET no_Of_Past_Free_Views = no_Of_Past_Free_Views/2, no_Of_Past_Paid_Views = no_Of_Past_Paid_Views/2 WHERE 1 and status='live'";
	$queryC5 = $dbHandle->query($queryCm5);

	$queryCm6 = "UPDATE blogTable SET no_Of_Past_Views = no_Of_Past_Views/2 WHERE 1";
	$queryC6 = $dbHandle->query($queryCm6);

	$queryCm7 = "UPDATE messageTable SET no_Of_Past_Views = no_Of_Past_Views/2 WHERE 1 and status='live'";
	$queryC7 = $dbHandle->query($queryCm7);
	
	
	//also viewed algorithm implementation - decay the weight of also viewed
	global $alsoViewedDecayInterval;
	$currentMonth = date("n");
	
	if($currentMonth % $alsoViewedDecayInterval != 0) {
		die;
	}
	
	global $alsoViewedDecayFactor;
	
	$queryalso_halfCmd = "UPDATE also_viewed_listings SET weight = weight * ".$alsoViewedDecayFactor." WHERE 1";
	$queryalso_half = $dbHandle->query($queryalso_halfCmd);
    }

    /** 
    * function name: trigger_to_update_pastViews
    * parametres: AppId
    * Brief:  acts as a trigger which runs once in a day to make necessary changes to no_Of_Past_Views field in each of table
    *          so as to take care of aging
    * This function has been added for Also viewed and Most viewed algorithms
    */
    function trigger_to_update_pastViews($appId=12)
    {
    	$this->validateCron();
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);

	$this->init();
        
	//Get the 45 day before date
	$date = date("Y-m-d");
        $curDate = strtotime($date);
	$pf_time = strtotime("-45 day",$curDate);
	$newDate = date("Y-m-d", $pf_time);

	//Get the DB Handle
        //$dbConfig = array( 'hostname'=>'localhost');
	//$this->listingconfig->getDbConfig_test($appId,$dbConfig);
	//$dbHandle = $this->load->database($dbConfig,TRUE);
	$dbHandle = $this->dbLibObj->getWriteHandle();
    $misDBHandle = $this->dbLibObj->getReadHandle();//getReadHandleByModule("MISTracking");
	
	//Get all the distict entities whose view count was added 45 days ago
        $queryCm1 = "SELECT distinct(listing_id) as listing_id1, listingType, no_Of_Views FROM view_Count_Details WHERE view_Date<=? and is_Deleted = 0";
	$queryC1 = $dbHandle->query($queryCm1, array($newDate));

	//For each of these entries, update their Past views in the corresponding tables
	foreach($queryC1->result() as $row)
	{
		if(($row->listingType == 'course_free') )
		{
		    $queryCm4 = "UPDATE listings_main SET no_Of_Past_Free_Views = no_Of_Past_Free_Views + $row->no_Of_Views WHERE listing_type_id = ? and listing_type = 1";
		    $queryC4 = $dbHandle->query($queryCm4, array($row->listing_id1));
		}
		elseif(($row->listingType == 'course_paid'))
		{
		    $queryCm4 = "UPDATE listings_main SET no_Of_Past_Paid_Views = no_Of_Past_Paid_Views + $row->no_Of_Views WHERE listing_type_id = ? and listing_type = 1";
		    $queryC4 = $dbHandle->query($queryCm4, array($row->listing_id1));
		}
		elseif(($row->listingType == 'institute_free') )
		{
		    $queryCm4 = "UPDATE listings_main SET no_Of_Past_Free_Views = no_Of_Past_Free_Views + $row->no_Of_Views WHERE listing_type_id = ? and listing_type = 2";
		    $queryC4 = $dbHandle->query($queryCm4, array($row->listing_id1));
		}
		elseif(($row->listingType == 'institute_paid'))
		{
		    $queryCm4 = "UPDATE listings_main SET no_Of_Past_Paid_Views = no_Of_Past_Paid_Views + $row->no_Of_Views WHERE listing_type_id = ? and listing_type = 2";
		    $queryC4 = $dbHandle->query($queryCm4, array($row->listing_id1));
		}
		elseif($row->listingType == 'blogs')
		{
		      $queryCm4 = "UPDATE blogTable SET no_Of_Past_Views = no_Of_Past_Views + $row->no_Of_Views WHERE blogId = ?";
		      $queryC4 = $dbHandle->query($queryCm4, array($row->listing_id1));
		}
		elseif($row->listingType == 'qna')
		{
		      $queryCm4 = "UPDATE messageTable SET no_Of_Past_Views = no_Of_Past_Views + $row->no_Of_Views WHERE msgId = ?";
		      $queryC4 = $dbHandle->query($queryCm4, array($row->listing_id1));
		}
		//Mark all these entities as deleted
		$del_querycmd = "UPDATE view_Count_Details SET is_Deleted = 1 WHERE view_Date<='$newDate' and is_Deleted = 0 and listing_id=? and listingType in ('course_free','institute_free','scholarship','notification','tutor','student','consultant','blogs','qna','course_paid','institute_paid','abroadcourse','abroaduniversity','abraoddepartment','abroadsnapshotcourse')";
		$del_query = $dbHandle->query($del_querycmd, array($row->listing_id1));
	}


	/* 
	* Code Start for Also viewed algorithm
	*/
	global $alsoViewedWeights;
	global $timePerSession;
	global $maximumViewsInSession;
	global $internalIPs;
	$also_view_array = array();
	
	//Get all the different sequences for yesterday and create an array from them. Add -1 as a sequence breaker
	$query_also2Cmd = "SELECT DISTINCT(user_session_id) as session FROM listing_track, user_session_info 
						WHERE listing_track.user_session_id = user_session_info.id 
						AND listing_track.is_deleted = 0 
						AND date(listing_track.visit_time) < '$date' 
						AND user_session_info.user_ip NOT IN ('".implode("','", $internalIPs)."')";
	$query_also2 = $misDBHandle->query($query_also2Cmd);
	
	$b = -1;
	foreach($query_also2->result() as $row)
	{
	    $query_also3Cmd = "SELECT course_id, visit_time FROM listing_track WHERE user_session_id = ? AND is_institute = 0 AND is_deleted = 0 ORDER BY visit_time ASC";
	    
	    $query_also3 = $dbHandle->query($query_also3Cmd, array($row->session));   // select entries corresponding to a particular session id in increasing order of id
	    
	    if($query_also3->num_rows() > $maximumViewsInSession) {
		continue;
	    }
	    
	    $old_timestamp = -1;
	    foreach($query_also3->result() as $row1)
	    {
		if($old_timestamp != -1)         //for checking if difference between two timestamps in greater than 20 min
		{
		      $diff = strtotime($row1->visit_time) - strtotime($old_timestamp);
		      if($diff > $timePerSession)
		      {
			array_push($also_view_array,$b); // push 1 if differnce is greater than 20 min so as to break the sequence
		      }
		}
		$a = array($row1->course_id, 'course');
		array_push($also_view_array, $a);
		$old_timestamp = $row1->visit_time;
	    }
	    array_push($also_view_array,$b ); // push -1 to break the sequence after a particular session id
	}
	
	// Soft delete the entries for the yesterday
	$query_soft_deleteCmd = "SELECT count(*) as cnt from listing_track WHERE visit_time < '".date("Y-m-d")." 00:00:00' and is_deleted = 0";
	$query_soft_delete_count = $dbHandle->query($query_soft_deleteCmd);
	$query_soft_delete_count = $query_soft_delete_count->result();
	$query_soft_delete_count = $query_soft_delete_count[0]->cnt;
	$chunkSize = 20000;
	$chunks = ceil($query_soft_delete_count/$chunkSize);

	for($i=0; $i<=$chunks; $i++){
		error_log("for chunk : ".$i);
		$query_soft_deleteCmd = "UPDATE listing_track SET is_deleted = 1 WHERE visit_time < '".date("Y-m-d")." 00:00:00' and is_deleted = 0 LIMIT ".$chunkSize;
		$query_soft_delete = $dbHandle->query($query_soft_deleteCmd);	
		// delay for 10 sec to prevent slave lag
		sleep(10);
	}

	//Now based on this array, find the first level, second level and third level pairs
	$array_already_covered = array();

	$ct = count($also_view_array);
	for($i = 0;$i < $ct - 2; $i++) // take consecutive pair of entries
	{

	    if(($also_view_array[$i] != -1) && ($also_view_array[$i+1] != -1)) // -1 indicates break of sequence
	    {
		  $list_id1 = $also_view_array[$i][0];  /// take the pair to check for
		  $list_type1 = $also_view_array[$i][1];
		  $list_id2 = $also_view_array[$i+1][0];
		  $list_type2 = $also_view_array[$i+1][1];
		  $s = array($list_id1,$list_type1,$list_id2,$list_type2);
		  $already_covered = 0;

		  for($m = 0; $m < count($array_already_covered); $m++)  // check if this pair has been alreday covered for calculation
		  {
			if(($array_already_covered[$m][0] == $list_id1) && ($array_already_covered[$m][1] == $list_type1) && ($array_already_covered[$m][2] == $list_id2) && ($array_already_covered[$m][3] == $list_type2))
			{
			  $already_covered = 1;
			}
		  }

		  if($already_covered == 0 && $list_id1!=$list_id2)
		  {
		      array_push($array_already_covered,$s);
		      $first_level = 1;                    // find first level map
		      for($j = $i+2; $j < ($ct-2); $j++)   // scan from current position onwards for 1st level
		      {
			    if(($also_view_array[$j] != -1) && ($also_view_array[$j+1] != -1))
			    {
				if(($also_view_array[$j][0] == $list_id1) && ($also_view_array[$j][1] == $list_type1) && ($also_view_array[$j+1][0] == $list_id2) && ($also_view_array[$j+1][1] == $list_type2))
				{
				    $first_level++;
				}
			    }
		      }

		      $second_level = 0;           // to find out the second level maps
		      for($j = 0; $j < ($ct-3); $j++) // fix: $j from i+2 to 0
		      {
			  if(($also_view_array[$j] != -1) && ($also_view_array[$j+1] != -1) && ($also_view_array[$j+2] != -1))
			  {
			      if(($also_view_array[$j][0] == $list_id1) && ($also_view_array[$j][1] == $list_type1) && ($also_view_array[$j+2][0] == $list_id2) && ($also_view_array[$j+2][1] == $list_type2))
				{
				    $second_level++;
				}
			  }
		      }

		      $third_level = 0;           // to find out the third level maps
		      for($j = 0; $j < ($ct-4); $j++) // fix: $j from $i to 0
		      {
			  if(($also_view_array[$j] != -1) && ($also_view_array[$j+1] != -1) && ($also_view_array[$j+2] != -1) && ($also_view_array[$j+3] != -1))
			  {
			      if(($also_view_array[$j][0] == $list_id1) && ($also_view_array[$j][1] == $list_type1) && ($also_view_array[$j+3][0] == $list_id2) && ($also_view_array[$j+3][1] == $list_type2))
				{
				    $third_level++;
				}
			  }
		      }

		      $weight_list = ($alsoViewedWeights['level_1'] * $first_level) + ($alsoViewedWeights['level_2'] * $second_level) +  ($alsoViewedWeights['level_3'] * $third_level);
		      $this->updateAlsoViewed($weight_list,$list_id1,$list_type1,$list_id2,$list_type2);
		  }
	    }
	}

	//code for 2nd level map
	for($i = 0;$i < $ct - 3; $i++) // make pair after a gap of one element
	{

	    if(($also_view_array[$i] != -1) && ($also_view_array[$i+1] != -1) && ($also_view_array[$i+2] != -1))// check that sequence doesnt break in between the pair
	    {
		  $list_id1 = $also_view_array[$i][0];  /// take the pair
		  $list_type1 = $also_view_array[$i][1];
		  $list_id2 = $also_view_array[$i+2][0];
		  $list_type2 = $also_view_array[$i+2][1];
		  $s = array($list_id1,$list_type1,$list_id2,$list_type2);
		  $already_covered = 0;

		  for($m = 0; $m < count($array_already_covered); $m++)
		  {
			if(($array_already_covered[$m][0] == $list_id1) && ($array_already_covered[$m][1] == $list_type1) && ($array_already_covered[$m][2] == $list_id2) && ($array_already_covered[$m][3] == $list_type2))
			{
			    $already_covered = 1;
			}
		  }

		  if($already_covered == 0  && $list_id1!=$list_id2)
		  {
			array_push($array_already_covered,$s);
			$first_level = 0;                    // find first level map
			$second_level = 1;           // to find out the second level maps
			for($j = $i+3; $j < ($ct-3); $j++)
			{
			    if(($also_view_array[$j] != -1) && ($also_view_array[$j+1] != -1) && ($also_view_array[$j+2] != -1))
			    {
				if(($also_view_array[$j][0] == $list_id1) && ($also_view_array[$j][1] == $list_type1) && ($also_view_array[$j+2][0] == $list_id2) && ($also_view_array[$j+2][1] == $list_type2))
				{
				      $second_level++;
				}
			    }
			}

			$third_level = 0;           // to find out the third level maps
			for($j = 0; $j < ($ct-4); $j++) // fix: $j from $i to 0
			{
			    if(($also_view_array[$j] != -1) && ($also_view_array[$j+1] != -1) && ($also_view_array[$j+2] != -1) && ($also_view_array[$j+3] != -1))
			    {
				if(($also_view_array[$j][0] == $list_id1) && ($also_view_array[$j][1] == $list_type1) && ($also_view_array[$j+3][0] == $list_id2) && ($also_view_array[$j+3][1] == $list_type2))
				{
				      $third_level++;
				}
			    }
			}

			$weight_list = ($alsoViewedWeights['level_1'] * $first_level) + ($alsoViewedWeights['level_2'] * $second_level) +  ($alsoViewedWeights['level_3'] * $third_level);
			$this->updateAlsoViewed($weight_list,$list_id1,$list_type1,$list_id2,$list_type2);
		  }// check for already covered

	    }

	}

	// code for third level maps
	for($i = 0;$i < $ct - 4; $i++) // make pair after gap of 2 elements
	{

	    if(($also_view_array[$i] != -1) && ($also_view_array[$i+1] != -1) && ($also_view_array[$i+2] != -1) && ($also_view_array[$i+3] != -1))
	    {
		  $list_id1 = $also_view_array[$i][0];  /// make pair after a gap of two elements
		  $list_type1 = $also_view_array[$i][1];
		  $list_id2 = $also_view_array[$i+3][0];
		  $list_type2 = $also_view_array[$i+3][1];
		  $s = array($list_id1,$list_type1,$list_id2,$list_type2);
		  $already_covered = 0;

		  for($m = 0; $m < count($array_already_covered); $m++)
		  {
		      if(($array_already_covered[$m][0] == $list_id1) && ($array_already_covered[$m][1] == $list_type1) && ($array_already_covered[$m][2] == $list_id2) && ($array_already_covered[$m][3] == $list_type2))
		      {
			  $already_covered = 1;
		      }
		  }

		  if($already_covered == 0  && $list_id1!=$list_id2)
		  {
		      array_push($array_already_covered,$s);
		      // correct it to find that this pair is not already covered
		      $first_level = 0;           // find first level map
		      $second_level = 0;          // to find out the second level maps
		      $third_level = 1;           // to find out the third level maps
		      for($j = $i+2; $j < ($ct-4); $j++)
		      {
			    if(($also_view_array[$j] != -1) && ($also_view_array[$j+1] != -1) && ($also_view_array[$j+2] != -1) && ($also_view_array[$j+3] != -1))
			    {
				if(($also_view_array[$j][0] == $list_id1) && ($also_view_array[$j][1] == $list_type1) && ($also_view_array[$j+3][0] == $list_id2) && ($also_view_array[$j+3][1] == $list_type2))
				  {
				      $third_level++;
				  }
			    }
		      }

		      $weight_list = ($alsoViewedWeights['level_1'] * $first_level) + ($alsoViewedWeights['level_2'] * $second_level) +  ($alsoViewedWeights['level_3'] * $third_level);
		      $this->updateAlsoViewed($weight_list,$list_id1,$list_type1,$list_id2,$list_type2);
		  }// check for already covered
	    }

	}
	/* 
	* Code End for Also viewed algorithm
	*/

    }

    /** 
    * function name: updateAlsoViewed
    * parametres: weight_list,list_id1,list_type1,list_id2,list_type2
    * Brief:  Updates the also_viewed_listings table for any Listing1 -> Listing2 weight
    * This function has been added for Also viewed algorithms
    */
    function updateAlsoViewed($weight_list,$list_id1,$list_type1,$list_id2,$list_type2)
    {
	  $this->init();

	  if(empty($list_id2) || empty($list_type1) || !in_array($list_type1, array('institute','course')) || empty($list_id1)){
		return;	  	
	  }
	  //$dbConfig = array( 'hostname'=>'localhost');
	  
	  //Get the DB Handle for listing
	  //$this->listingconfig->getDbConfig_test($appId,$dbConfig);
	  //$dbHandle = $this->load->database($dbConfig,TRUE);
	  $dbHandle = $this->dbLibObj->getWriteHandle();
	  if($dbHandle == ''){
	      log_message('error','updateAlsoViewed can not create listing db handle');
	  }
	  
	  if($list_type1 == 'institute')
	  {
	      $list_type11 = 2;
	  }
	  elseif($list_type1 == 'course')
	  {
	      $list_type11 = 1;
	  }

	  if($list_type2 == 'institute')
	  {
	      $list_type22 = 2;
	  }
	  elseif($list_type2 == 'course')
	  {
	      $list_type22 = 1;
	  }
	  $query_also4Cmd = "select * from also_viewed_listings where listing_type_id = ? and listing_type = ? and also_viewed_id = ? and also_viewed_listing_type = ?";
	  $query_also4 = $dbHandle->query($query_also4Cmd, array($list_id1, $list_type11, $list_id2, $list_type22));

	  if($query_also4->num_rows() > 0)
	  {
	      $query_also5Cmd = "update also_viewed_listings set weight = weight + $weight_list where listing_type_id = ? and listing_type = ? and also_viewed_id = ? and also_viewed_listing_type = ?";
	      $query_also5 = $dbHandle->query($query_also5Cmd, array($list_id1, $list_type11, $list_id2, $list_type22));
	      
	      $query_also5Cmd = "update also_viewed_course_mapping set is_Updated = 0 where course_id = ?";
	      $query_also5 = $dbHandle->query($query_also5Cmd, array($list_id1));
	  }
	  else
	  {
	      $query_also6Cmd = "insert into also_viewed_listings set weight = $weight_list, listing_type_id = ?, listing_type = ?,also_viewed_id = ?,also_viewed_listing_type = ?";
	      $query_also6 = $dbHandle->query($query_also6Cmd, array($list_id1, $list_type11, $list_id2, $list_type22));
	      
	      $alsoViewedCourseMapping = array();

	      $sql = "SELECT also_viewed_courses FROM also_viewed_course_mapping WHERE course_id = ?";
	      $query = $dbHandle->query($sql, array($list_id1));
	      if($query->num_rows() > 0) {
			$row = $query->row();
			$alsoViewedCourseMapping = json_decode($row->also_viewed_courses);
	      }

	      $alsoViewedCourseMapping[] = $list_id2;
	      $alsoViewedCourseMapping = json_encode($alsoViewedCourseMapping);
	      
	      if($query->num_rows() > 0) {
			$sql = "UPDATE also_viewed_course_mapping SET also_viewed_courses = '".$alsoViewedCourseMapping."', is_Updated = 0 WHERE course_id = ".$dbHandle->escape($list_id1);
	      }
	      else {
			$sql = "INSERT INTO also_viewed_course_mapping SET course_id = ".$dbHandle->escape($list_id1).", also_viewed_courses = '".$alsoViewedCourseMapping."', is_Updated = 0";
	      }
	      $query = $dbHandle->query($sql);
	  }
    }
    
    function updateAlsoViewedCourses($rowNum, $maxId)
    {
	ini_set('memory_limit', -1);
	ini_set('max_execution_time', -1);
	
	$this->init();
	$dbHandle = $this->dbLibObj->getWriteHandle();
	
	if(!isset($rowNum)) {
		$rowNum = 0;
	}
	
	if(!isset($maxId)) {
		$maxId = 3742954;
	}
	
	$rowsPerBatch = 100000;
	
	$institutes = array();
	$instituteFlagshipCourseMapping = array();
	$batchResults = array();
	$insertAlsoViewedRows = array();
	$deleteAlsoViewedRows = array();
	
	$sql = "SELECT listing_type_id as institute_id FROM also_viewed_listings WHERE listing_type = 'institute' UNION DISTINCT SELECT also_viewed_id as institute_id FROM also_viewed_listings WHERE also_viewed_listing_type = 'institute'";
	$query = $dbHandle->query($sql);
	$results = $query->result_array();
	foreach($results as $result) {
		$institutes[] = $result['institute_id'];
	}
	
	$sql = "SELECT cd.course_order,cd.course_id,cd.institute_id from (SELECT cd1.institute_id,min(cd1.course_order) as course_order from course_details cd1 WHERE cd1.institute_id IN (".implode(',',$institutes).") and cd1.status='live' group by cd1.institute_id) X JOIN course_details cd ON (cd.institute_id =  X.institute_id and cd.course_order =  X.course_order) WHERE cd.institute_id  IN (".implode(',',$institutes).") and cd.status='live' group by cd.institute_id";
	$query = $dbHandle->query($sql);
	$results = $query->result_array();
	foreach($results as $result) {
		$instituteFlagshipCourseMapping[$result['institute_id']] = $result['course_id'];
	}
	
	while($rowNum < $maxId) {
		if($rowNum + $rowsPerBatch > $maxId) {
			$rowsPerBatch = $maxId - $rowNum;
		}
		//error_log('####AlsoViewedUpdate row num: '.print_r($rowNum, true));
		//error_log('####AlsoViewedUpdate row batch: '.print_r($rowsPerBatch, true));
		
		$sql = "SELECT listing_type_id, listing_type, also_viewed_id, weight, also_viewed_listing_type, id FROM also_viewed_listings ORDER BY id ASC LIMIT ".$rowNum.", ".$rowsPerBatch;
		$query = $dbHandle->query($sql);
		$batchResults = $query->result_array();
		
		foreach($batchResults as $result) {
			$index = $result['id'];
			$listing_type = $result['listing_type'];
			$listing_type_id = $result['listing_type_id'];
			$also_viewed_listing_type = $result['also_viewed_listing_type'];
			$also_viewed_id = $result['also_viewed_id'];
			$weight = floatval($result['weight']);
			
			//error_log('####AlsoViewedUpdate id: '.print_r($index, true));			
			//error_log('####listing type: '.print_r($listing_type, true));
			//error_log('####listing type id: '.print_r($listing_type_id, true));
			//error_log('####also viewed listing type: '.print_r($also_viewed_listing_type, true));
			//error_log('####also viewed id: '.print_r($also_viewed_id, true));
			//error_log('####weight: '.print_r($weight, true));
			
			if($listing_type == 'course' && $also_viewed_listing_type == 'course') {
				continue;
			}
			
			if($listing_type == 'institute') {
				$listing_type_id = $instituteFlagshipCourseMapping[$listing_type_id];
				//error_log('####listing flagship course: '.print_r($listing_type_id, true));
			}
			
			if($also_viewed_listing_type == 'institute') {
				$also_viewed_id = $instituteFlagshipCourseMapping[$also_viewed_id];
				//error_log('####also viewed flagship course: '.print_r($also_viewed_id, true));
			}
			
			if(isset($listing_type_id) && isset($also_viewed_id) && ($listing_type_id != $also_viewed_id)) {
				$sql = "SELECT id, weight FROM also_viewed_listings WHERE listing_type = 'course' AND also_viewed_listing_type = 'course' AND listing_type_id = ? AND also_viewed_id = ?";
				$query = $dbHandle->query($sql, array($listing_type_id, $also_viewed_id));
				$results = $query->result_array();
				$numRows = $query->num_rows();
				
				if($numRows) {
					$alsoViewedRow = array();
					$alsoViewedRow['id'] = $results[0]['id'];
					$alsoViewedRow['weight'] = floatval($results[0]['weight']);
				}
				else {
					$alsoViewedRow = false;
				}			
				
				if($alsoViewedRow == false) {
					//error_log('####insert row');
					$newRow = array();
					$newRow['listing_type_id'] = $listing_type_id;
					$newRow['listing_type'] = 1;
					$newRow['also_viewed_id'] = $also_viewed_id;
					$newRow['weight'] = $weight;
					$newRow['also_viewed_listing_type'] = 1;
					$insertAlsoViewedRows[] = $newRow;
				}
				else {
					//error_log('####update row');
					$weight = $weight + $alsoViewedRow['weight'];
					$sql = "UPDATE also_viewed_listings SET weight = ? WHERE id = ?";
					$query = $dbHandle->query($sql, array($weight, $alsoViewedRow['id']));
				}
			}
			
			//error_log('####delete row');
			$deleteAlsoViewedRows[] = $index;
		}
		
		//error_log('####AlsoViewedUpdate inserting for row: '.print_r($rowNum, true));
		$dbHandle->insert_batch('also_viewed_listings', $insertAlsoViewedRows);
		//error_log('####AlsoViewedUpdate inserted for row: '.print_r($rowNum, true));
		
		$insertAlsoViewedRows = array();
		$rowNum += $rowsPerBatch;
	}
	
	$deleteAlsoViewedRows = array_chunk($deleteAlsoViewedRows, 10000);
	foreach($deleteAlsoViewedRows as $index => $idsToDelete) {
		//error_log('####AlsoViewedUpdate deleting for batch: '.print_r($index, true));
		$sql = "DELETE FROM also_viewed_listings WHERE id IN (".implode(',',$idsToDelete).")";
		$query = $dbHandle->query($sql);
		//error_log('####AlsoViewedUpdate deleted for batch: '.print_r($index, true));
	}
    }
    
    function createAlsoViewedMappingTable() {
	ini_set('memory_limit', -1);
	ini_set('max_execution_time', -1);
	
	$this->init();
	$dbHandle = $this->dbLibObj->getWriteHandle();
	
	$alsoViewedCourseMappings = array();
	$courseIds = array();
	
	$sql = "SELECT DISTINCT listing_type_id as course_id FROM also_viewed_listings WHERE listing_type = 'course'";
	$query = $dbHandle->query($sql);
	$results = $query->result_array();
	foreach($results as $result) {
		$courseIds[] = $result['course_id'];
	}
	
	foreach($courseIds as $courseId) {
		$alsoViewedCourses = array();
		$sql = "SELECT DISTINCT also_viewed_id as course_id FROM also_viewed_listings WHERE listing_type_id = ? AND listing_type = 'course' AND also_viewed_listing_type = 'course'";
		$query = $dbHandle->query($sql, array($courseId));
		$results = $query->result_array();
		if(count($results)) {
			foreach($results as $result) {
				$alsoViewedCourses[] = $result['course_id'];
			}
			
			$alsoViewedCourses = json_encode($alsoViewedCourses);
			
			$newRow = array();
			$newRow['course_id'] = $courseId;
			$newRow['also_viewed_courses'] = $alsoViewedCourses;
			$alsoViewedCourseMappings[] = $newRow;
		}
	}
	
	$dbHandle->insert_batch('also_viewed_course_mapping', $alsoViewedCourseMappings);
    }
    
	/**
	 * This is used for storing pre-computed also viewed courses for ABROAD courses ONLY
     * updateType = full | incremental
     * full = Run for all live abroad courses
     * incremnetal = Run for courses updated after last run
	 */ 
    function storePreComputedAlsoViewedCourses($updateType = 'incremental') {
    $this->validateCron();
   	$this->benchmark->mark('code_start');
   	error_log("STARTED METHOD ########################### storePreComputedAlsoViewedCourses");
    	 
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', '-1');
	
	$this->init();
	$dbHandle = $this->dbLibObj->getWriteHandle();
	
	$this->load->model('recommendation_abroad/abroad_alsoviewed_model');
	$alsoViewedModel = new abroad_alsoviewed_model();
	
	$resultPriority = array('back_mapping' => 1, 'triangle_mapping' => 2, 'no_mapping' => 3);
	$resultTypeMapping = array('back_mapping' => 'bm', 'triangle_mapping' => 'tm', 'no_mapping' => 'nm', 'bm' => 'back-mapping', 'tm' => 'triangular-mapping', 'nm' => 'no-mapping');
	$paidCourses = array();
	$updatedCourses = array();
	$preComputedCourses = array();
	
    if($updateType == 'full') {
        /**
         * Fetch all live abroad courses
         */ 
        $sql = "select distinct course_id from abroadCategoryPageData where status = 'live'";
        $result = $dbHandle->query($sql)->result_array();
    }
    else {
        /**
         * Get live study abroad courses to be updated
         */ 
        $sql = "SELECT DISTINCT also_viewed_course_mapping.course_id
                FROM also_viewed_course_mapping, abroadCategoryPageData
                WHERE also_viewed_course_mapping.course_id = abroadCategoryPageData.course_id
                AND also_viewed_course_mapping.is_Updated = 0
                AND abroadCategoryPageData.status = 'live'";
        
        $result = $dbHandle->query($sql)->result_array();    
    }
    	
	error_log("===Live courses picked===");
	
	if(count($result)) {
		
		error_log("Count of picked courses greater than 0");
		//Get all paid courses
		$sql = "SELECT course_id
			FROM abroadCategoryPageData
			WHERE pack_type IN (1, 2, 375) AND status = 'live'";
		$packTypeResult = $dbHandle->query($sql)->result_array();
		
		foreach($packTypeResult as $row) {
			$paidCourses[$row['course_id']] = TRUE;
		}
		
		foreach($result as $row) {
			$courseId = intval($row['course_id']);
			
			if($courseId > 0) {
				$institutesInReco = array();
				$updatedCourses[] = $courseId;
				
				//Get also viewed results [ldb course filtering]
				$alsoViewedCourses = array();
				$alsoViewedCourses = $alsoViewedModel->getAlsoViewedListings(array($courseId), 'ldb');
				
				if(count($alsoViewedCourses)) {
					foreach($alsoViewedCourses as $course) {
						$instituteId = $course->institute_id;
						$alsoViewedCourseId = $course->course_id;
						$weight = floatval($course->weight);
						$type = $resultTypeMapping[$course->result_type];
						
						$updateData = false;
						if(!empty($institutesInReco[$instituteId])) {
							if($resultPriority[$institutesInReco[$instituteId]['type']] > $resultPriority[$type]) {
								$updateData = true;
							}
							else if($resultPriority[$institutesInReco[$instituteId]['type']] == $resultPriority[$type]) {
								if($institutesInReco[$instituteId]['weight'] < $weight) {
									$updateData = true;
								}
							}
						}
						else {
							if(empty($institutesInReco[$instituteId])) {
								$updateData = true;
							}
						}
						
						if($updateData) {
							$institutesInReco[$instituteId]['f'] = 'LDB';
							$institutesInReco[$instituteId]['i'] = $instituteId;
							$institutesInReco[$instituteId]['c'] = $alsoViewedCourseId;
							$institutesInReco[$instituteId]['w'] = $weight;
							$institutesInReco[$instituteId]['m'] = $type;
							if($paidCourses[$alsoViewedCourseId]) {
								$institutesInReco[$instituteId]['p'] = 'paid';
							}
							else {
								$institutesInReco[$instituteId]['p'] = 'free';
							}
						}
					}
				}
				
				//Get also viewed results [sub-cat filtering]
				$alsoViewedCourses = array();
				$alsoViewedCourses = $alsoViewedModel->getAlsoViewedListings(array($courseId), 'category');
				
				if(count($alsoViewedCourses)) {
					foreach($alsoViewedCourses as $course) {
						$instituteId = $course->institute_id;
						$alsoViewedCourseId = $course->course_id;
						$weight = floatval($course->weight);
						$type = $resultTypeMapping[$course->result_type];
						
						$updateData = false;
						if(!empty($institutesInReco[$instituteId]) && $institutesInReco[$instituteId]['f'] != 'LDB') {
							if($resultPriority[$institutesInReco[$instituteId]['type']] > $resultPriority[$type] ) {
								$updateData = true;
							}
							else if($resultPriority[$institutesInReco[$instituteId]['type']] == $resultPriority[$type]) {
								if($institutesInReco[$instituteId]['weight'] < $weight) {
									$updateData = true;
								}
							}
						}
						else {
							if(empty($institutesInReco[$instituteId])) {
								$updateData = true;
							}
						}
						
						if($updateData) {
							$institutesInReco[$instituteId]['f'] = 'Subcategory';
							$institutesInReco[$instituteId]['i'] = $instituteId;
							$institutesInReco[$instituteId]['c'] = $alsoViewedCourseId;
							$institutesInReco[$instituteId]['w'] = $weight;
							$institutesInReco[$instituteId]['m'] = $type;
							if($paidCourses[$alsoViewedCourseId]) {
								$institutesInReco[$instituteId]['p'] = 'paid';
							}
							else {
								$institutesInReco[$instituteId]['p'] = 'free';
							}
						}
					}
				}
				
				//sort filtered courses
				if(count($institutesInReco)) {
					uasort($institutesInReco, array('Beacon_server','compareAlsoViewedResults'));
					$filteredCourses = array_values($institutesInReco);
					
					$freeCourseIndex = 1;
					$numOfFreeCourses = 0;
					
					foreach($filteredCourses as $course) {
						if($course['p'] == 'free') {
							$numOfFreeCourses++;
						}
					}
					
					foreach($filteredCourses as $course) {
						$dataArray = array();
						$dataArray['course_id'] = $courseId;
						$dataArray['recommended_course_id'] = $course['c'];
						$dataArray['recommended_institute_id'] = $course['i'];
						$dataArray['recommended_course_type'] = $course['p'];
						$dataArray['weight'] = $course['w'];
						
						if($course['p'] == 'free') {
							$dataArray['weight_percentage'] = intval(($freeCourseIndex/$numOfFreeCourses) * 100);
							$freeCourseIndex++;
						}
						else {
							$dataArray['weight_percentage'] = -1;
						}
						
						$dataArray['mapping_type'] = $resultTypeMapping[$course['m']];
						$dataArray['filter_type'] = $course['f'];
						$dataArray['status'] = 'live';
						
						$preComputedCourses[] = $dataArray;
					}
				}
			}
			
			
			//update also viewed filtered courses
			//Entries are picked in chunk of 100; then opertion is being performed
			if(count($updatedCourses) == 50) {		//FIX: chunk size decreased to 50 from 1000
				error_log("50 entries picked for DB operations.");
				error_log("updating table: alsoViewedFilteredCourses; set status as history");
				
				$sql = "UPDATE alsoViewedFilteredCourses SET status = 'history' WHERE course_id IN (".implode(',',$updatedCourses).")";
				$dbHandle->query($sql);
				
				if(count($preComputedCourses)) {
					error_log("Insertion operation for 100 entires in batch on table: alsoViewedFilteredCourses");
					$result = $dbHandle->insert_batch('alsoViewedFilteredCourses', $preComputedCourses);
				}
				
				error_log("updating table: alsoViewedFilteredCourses; set is_updated as 1");
				$sql = "UPDATE also_viewed_course_mapping SET is_Updated = 1 WHERE course_id IN (".implode(',',$updatedCourses).")";
				$dbHandle->query($sql);
				
				$updatedCourses = array();
				$preComputedCourses = array();
			}
		}
	}
	
	//update also viewed filtered courses
	//In last iteration above if remaining entries are less than 100; then those entries will be updated
	if(count($updatedCourses)) {
		error_log("updating table: alsoViewedFilteredCourses; set status as history");
		$sql = "UPDATE alsoViewedFilteredCourses SET status = 'history' WHERE course_id IN (".implode(',',$updatedCourses).")";
		$dbHandle->query($sql);
		
		if(count($preComputedCourses)) {
			error_log("Insertion operation for remaining entries (less than 100 )in batch on table: alsoViewedFilteredCourses");
			$result = $dbHandle->insert_batch('alsoViewedFilteredCourses', $preComputedCourses);
		}
		
		error_log("updating table: alsoViewedFilteredCourses; set is_updated as 1");
		$sql = "UPDATE also_viewed_course_mapping SET is_Updated = 1 WHERE course_id IN (".implode(',',$updatedCourses).")";
		$dbHandle->query($sql);
	}
	
	error_log("Deleting records: alsoViewedFilteredCourses; where status as history");
	$sql = "DELETE FROM alsoViewedFilteredCourses WHERE status = 'history'";
	$dbHandle->query($sql);

	error_log("ENDED METHOD ########################### storePreComputedAlsoViewedCourses");
	$this->benchmark->mark('code_end');
	error_log("total time taken to run index():::".$this->benchmark->elapsed_time('code_start', 'code_end')."seconds");
	
    }
    
    function compareAlsoViewedResults($result1, $result2) {
	if($result1['m'] == $result2['m']) {
		if($result1['w'] >= $result2['w']) {
			return -1;
		}
		else {
			return 1;
		}
	}
	else {
		if($result1['m'] == 'bm') {
			return -1;
		}
		else if($result1['m'] == 'tm') {
			if($result2['m'] == 'bm') {
				return 1;
			}
			else {
				return -1;
			}
			
		}
		else {
			return 1;
		}
	}
    }
    
	/*
	*	Function updates tables (also_viewed_course_mapping, alsoViewedFilteredCourses), if client course is posted or edited
	*/
    public function updateAlsoViewedTables($courseIDs = array()) {
    	$this->validateCron();
    	$this->load->library('dbLibCommon');
            $this->dbLibObj = DbLibCommon::getInstance('Beacon');
            
    	$dbHandle = $this->dbLibObj->getWriteHandle();

    	/*
    	 * Get all the course IDs updated in last 30 min. 
     	*/
    	$query = "Select listing_type_id from  listings_main where last_modify_date >= sysdate() -1800 AND status = 'live'";
    	$queryResult = $dbHandle->query($query)->result_array();
 
    	$courseIDs = $queryResult;
    	
    	
    	foreach ( $courseIDs as $row => $val ) {
    		
    		$courseId = $val['listing_type_id'];
    		
    	 	$this->load->model('listing/posting/listingpublishmodel');
    		$LDBCoursesChanged = $this->listingpublishmodel->_checkLDBCourseChanged ($courseId);
    			
    		if ($LDBCoursesChanged == true) {
   
    			$this->listingpublishmodel->_updateAlsoViewed ( $courseId ); 			
    		}
    			
    		$packTypeChanged = $this->listingpublishmodel->_checkPackTypeChanged ( $courseId );
    			
    		if ($packTypeChanged == true) {
    			
    			$this->listingpublishmodel->_updateAlsoViewed ( $courseId );
    		}  
    	}
    }
}
?>
