<?php
class LDBLeadMigrationScript extends MX_Controller {
	
	protected $dbLibObj;
	protected $dbHandle;
	
	function initDB() {
		//die;
		$this->dbLibObj = DbLibCommon::getInstance ( 'Listing' );
		$this->dbHandle = $this->_loadDatabaseHandle ( "read" );
		// $this->dbHandle = $this->_loadDatabaseHandle ( "read", "MISTracking");
	}
	
	/*
	 *script to transfer records from LDBLeadViewCount to LDBLeadCourseTrack with desired course and type of lead
	 */
	function completeLeadMigration() {
		
		ini_set("memory_limit",-1);
		ini_set("max_execution_time",-1);
		
		$this->initDB ();
		$query = "SELECT count(*) as count FROM `LDBLeadViewCount` ";
		$records = $this->dbHandle->query( $query )->result_array();
		
		$count = $records[0]['count'];
		$index = 0;
		$remaining = round($count/100000)+1;
				
		error_log("==shiksha== << === Migration Script starts === >> == count ==".$count);

		while($index < $count) {
			
			$query = "SELECT LVC . * , TF.PrefId, TF.ExtraFlag, TF.DesiredCourse"
				." FROM shiksha.LDBLeadViewCount LVC"
				." LEFT JOIN shiksha.tUserPref TF ON ( LVC.UserId = TF.UserId )"
				." GROUP BY TF.`UserId`"
				." ORDER BY UserId ASC "
				." LIMIT ".$index.", 100000";
				
			$start = microtime(true);	
			$records = $this->dbHandle->query( $query )->result_array();
			$finalVariableSet = array();
			
			foreach ($records as $key => $val) {
				
				if ($val['ExtraFlag'] == 'testprep') {
					$prefId = $val['PrefId'];
					$query = "SELECT blogid FROM tUserPref_testprep_mapping" 
					." WHERE prefid = ? AND status = 'live'";
					$result = $this->dbHandle->query ( $query, $prefId )->result_array ();
					$val['DesiredCourse'] = $result [0] ['blogid'];
					$val['ExtraFlag'] = 'testprep';
					
				} else {
					if($val['ExtraFlag'] == 'studyabroad'){
						$val['ExtraFlag'] = 'studyabroad';
					}else{
						$val['ExtraFlag'] = 'national';
					}
					unset($val['PrefId']);
				}
				
				$finalVariableSet[] = array(
							    'UserId'=>$val['UserId'],
							    'ViewCount'=>$val['ViewCount'],
							    'EmailCount'=>$val['EmailCount'],
							    'SmsCount'=>$val['SmsCount'],
							    'DesiredCourse'=>$val['DesiredCourse'],
							    'Flag'=>$val['ExtraFlag'],
							    'UpdateTime'=>$val['UpdateTime'] 
							    ); 
			}
			
			$this->dbHandle->insert_batch('shiksha.LDBLeadCourseViewTrack',$finalVariableSet);
			//_P($finalVariableSet);
			$index +=100000;
			$remaining --;
			error_log("==shiksha== done = ".$index." = remaining = ".($count-$index)." = lbuid = ".$finalVariableSet[99999]['UserId']. " = remaining = ".$remaining);
		}

		error_log("==shiksha== << === Migration Script ends === >> ");
	}

	function addSpecialization()
	{
		return;
		$this->initDB ();
		$specializationArray = array("Instrumentation and Control Engineering","Biomedical Engineering","Production Engineering","Food Technology","Mechatronics Engineering","Environmental Engineering","Petroleum Engineering","Power Engineering","Polymer Technology","Printing Technology","Plastic Technology","Energy Engineering","Ceramic Engineering","Safety & Fire Engineering","Dairy Technology");
		
		
		
		// data for query1
		$courseName = "B.E./B.Tech";
		$courseLevel = "Degree";
		$courseLevel1 = "UG";
		//$courseLevel2 = null;
		$categoryId = 2 ;// Science and Engg (from categoryBoards Rtable)
		$parentId = 52; // For B.E/Tech from tCourseSpecializationMapping table
		$date = date('Y-m-d');
		
		//data for query3
		$groupId = 3; 
		$status = "live";  // same for query4
		$extraFlag = "course";
		
		//data for query4
		$mode = "";
		$level = "Post Graduate Degree";
		$type = "ldbcourse";
		
		//data for query5
		$groupIdQuery5 = 51;
		// Got this by
		// select group_concat(specialzationId) from tcousrespecialzationmapping where parentID = 52(for btech)
		// then select groupId from courseSpecializationGroupMapping where courseId in (UPPER QUERY RESULT which is 12,51 in this case)
		// then check in table  categoryGroupSpecializationMaster for 12,51 group id and select 51 as it has category id 2(science and engg)
		
		
		
		//data for query6
		$groupIdQuery6 = 2;
		$groupname = "National UG";
		$acronym = "nationalUG";
		// Got this by
		// select group_concat(specialzationId) from tcousrespecialzationmapping where parentID = 52(for btech)
		// then select groupId from  courseSpecializationGroupMapping where courseSpecializationId in (UPPER QUERY RESULT which is 12 in this case)
		
		
		
		foreach($specializationArray as $specialization)
		{
			error_log("==shiksha== << === Adding Specialization -->".$specialization." === >> ");
			//echo $specialization;
			$query1 = "INSERT into tCourseSpecializationMapping ".
				"(SpecializationName,CourseName,CourseLevel,CourseLevel1 ".
				",CategoryId,ParentId,SubmitDate) ".
				"values(?,?,?,?,?,?,?)";
			
			$this->dbHandle->query($query1, array($specialization, $courseName, $courseLevel, $courseLevel1, $categoryId, $parentId, $date));
			
			echo $specializationId = $this->dbHandle->insert_id();
			//$specializationId = 1542;
			echo "<br />";
			// 56 -> id for btech in categoryborads table
			$query2 = "INSERT into LDBCoursesToSubcategoryMapping(ldbCourseID,categoryID) values(?,'56')";
			$this->dbHandle->query($query2, array($specializationId));
			
			$query3 = "INSERT into tCourseGrouping(groupId,courseId,status,extraFlag) ".
			"values(?,?,?,?)";
			$this->dbHandle->query($query3, array($groupId, $specializationId, $status, $extraFlag));
			
			$query4 = "INSERT into LDBCourseFilterHide(type,typeId,mode,courseLevel,status) ".
				  "values(?,?,?,?,?)";
			$this->dbHandle->query($query4, array($type,$specializationId,$mod,$level,$status));
			
			$query5 = "INSERT into courseSpecializationGroupMapping(groupId,courseSpecializationId) ".
			"values(?,?)";
			$this->dbHandle->query($query5, array($groupIdQuery5,$specializationId));
			
			$query6 = "INSERT into  cmp_coursegroupmapping(groupid,groupname,acronym,courseid) ".
			"values(?,?,?,?)";
			$this->dbHandle->query($query6, array($groupIdQuery6,$groupname,$acronym,$specializationId));
			
			
			
			
			error_log("==shiksha== << === Added the Specialization with ID-->".$specializationId." === >> ");
				
		}
	}	
	
	/*
	 *script to update userid in tempLMSTable where email id exists and userid is 0
	 */
	function updateUserIdInTempLMS() {
		
		$this->initDB ();

		$queryToFetchUserIdForEmailId = "SELECT b.userid, a.email"
						." FROM tempLMSTable a"
						." LEFT JOIN tuser b ON ( a.email = b.email )"
						." WHERE a.userId =0"
						." AND a.email NOT"
						." IN ("
						." '', '0')";
		$start = microtime(true);
		$result = $this->dbHandle->query($queryToFetchUserIdForEmailId)->result_array();
		error_log("==shiksha== No. of users found such that their userId in tempLMSTable is 0 and their email exists in both tables are :".count($result));
		
		foreach($result as $r=>$row){
			$updateQuery = "UPDATE IGNORE `shiksha`.`tempLMSTable` SET userId = ? WHERE email = ?";
			$updateResult = $this->dbHandle->query($updateQuery,array($row['userid'],$row['email']));
			error_log("==shiksha== User being updated in tempLMSTable : ".$row['userid']."--email--".$row['email']);
		}

		$queryToFetchUserIdForEmailIdInRequest = "SELECT b.userid, a.email"
						." FROM tempLmsRequest a"
						." LEFT JOIN tuser b ON ( a.email = b.email )"
						." WHERE a.userId =0"
						." AND a.email NOT"
						." IN ("
						." '', '0')";
		
		$resultRequest = $this->dbHandle->query($queryToFetchUserIdForEmailIdInRequest)->result_array();
		error_log("==shiksha== No. of users found such that their userId in tempLmsRequest is 0 and their email exists in both tables are :".count($resultRequest));
		
		
		foreach($resultRequest as $r2=>$row2){
			$updateQueryRequest = "UPDATE IGNORE `shiksha`.`tempLmsRequest` SET userId = ? WHERE email = ?";
			$updateRequestResult = $this->dbHandle->query($updateQueryRequest,array($row2['userid'],$row2['email']));
			error_log("==shiksha== User being updated in tempLmsRequest : ".$row2['userid']."--email--".$row2['email']);
		}
		
	}
	
	/**
	 * One time script to update the Course Page for MBA (LDB-2206)
	 */
	function updateCoursePage(){
		return;
		$widgetName = "College Review Widget";
		$widgetKey = "CollegeReviewWidget";
		$status = "live";
		$this->initDB ();
		$sql1 = "INSERT into course_pages_widgets(widgetName,status,widgetKey) values(?,?,?)";
		error_log("==shiksha== << === Inserting widget in table  course_pages_widgets--> === >> ");
		$this->dbHandle->query($sql1, array($widgetName, $status, $widgetKey));
		
		$widgetId = $this->dbHandle->insert_id();
		
		error_log("==shiksha== << === Inserted widget in table  course_pages_widgets with widgetId ".$widgetId."--> === >> ");
		
		$careerWidgetId = "";
		$topDiscussionWidgetId = "";
		
		$sql2 = "select widgetId from  course_pages_widgets where widgetKey = 'NaukriToolWidget'";
		$query = $this->dbHandle->query($sql2);
		$careerWidgetId = $query->row()->widgetId;
		error_log("==shiksha== << === Fetched widget ID for NaukriToolWidget from  course_pages_widgets with widgetId ".$careerWidgetId."--> === >> ");
		
		$sql3 = "select widgetId from  course_pages_widgets where widgetKey = 'TopDiscussionsWidget'";
		$query = $this->dbHandle->query($sql3);
		$topDiscussionWidgetId = $query->row()->widgetId;
		error_log("==shiksha== << === Fetched widget ID for TopDiscussionsWidget from  course_pages_widgets with widgetId ".$topDiscussionWidgetId."--> === >> ");
		
		
		if($careerWidgetId != "" && $topDiscussionWidgetId!= "") {
			$sql4 = "UPDATE course_pages_category_widgets_mapping set widgetId = ?".
				",widgetHeading = 'Tools to decide your MBA college' WHERE widgetId =? and subCatId =23 and status = 'live'";
			error_log("==shiksha== << === Updating course_pages_category_widgets_mapping for new adding new widget");	
			$this->dbHandle->query($sql4, array($widgetId, $topDiscussionWidgetId));
			error_log("==shiksha== << === Updated course_pages_category_widgets_mapping for new adding new widget");
			
			error_log("==shiksha== << === Updating course_pages_category_widgets_mapping for new Removin campus connect");	
			$sql5 = "UPDATE course_pages_category_widgets_mapping set widgetId = ?".
				",widgetHeading = 'Recent Discussions on MBA' WHERE widgetId =? and subCatId =23 and status = 'live'";
			$this->dbHandle->query($sql5, array($topDiscussionWidgetId, $careerWidgetId));
			error_log("==shiksha== << === Updated course_pages_category_widgets_mapping for new Removin campus connect");	
		} else {
			error_log("==shiksha== << === Some error coucred");	
		}
		
		
		
	}


	function updateSACategoryMappingQueryGenerate(){

		$input = array(
			array(
				'parent' => 'Computers',
				'old' => 'Design',
				//'new' => 'Animation & Design'
				'new' => 'Animation'
			),
			array(
				'parent' => 'Computers',
				'old' => 'Engineering',
				//'new' => 'Computer Science & Engineering'
				'new' => 'Computer Science'
			),
			array(
				'parent' => 'Computers',
				'old' => 'Internet Technologies',
				//'new' => 'IT & Networking'
				'new' => 'Information Technology'
			),
			array(
				'parent' => 'Computers',
				'old' => 'Mobile Technologies',
				//'new' => 'IT & Networking'
				'new' => 'Information Technology'
			),
			array(
				'parent' => 'Computers',
				'old' => 'Digital Media',
				//'new' => 'Animation & Design'
				'new' => 'Animation'
			),
			array(
				'parent' => 'Computers',
				'old' => 'Hardware',
				//'new' => 'IT & Networking'
				'new' => 'Information Technology'
			),
			array(
				'parent' => 'Business',
				'old' => 'Non Profit',
				'new' => 'Other Specializations'
			),
			array(
				'parent' => 'Business',
				'old' => 'Quality',
				'new' => 'Other Specializations'
			),
			array(
				'parent' => 'Business',
				'old' => 'Real estate',
				'new' => 'Other Specializations'
			),
			array(
				'parent' => 'Business',
				'old' => 'Operations Management',
				'new' => 'Other Specializations'
			),
			array(
				'parent' => 'Business',
				'old' => 'Business Administration',
				'new' => 'General Management'
			),
			array(
				'parent' => 'Engineering',
				'old' => 'Mechatronics',
				//'new' => 'Mechanical Engineering'
				'new' => 'Mechanical'
			),
			array(
				'parent' => 'Engineering',
				'old' => 'Signals',
				//'new' => 'Electronics & Electrical Engineering'
				'new' => 'Electronic'
			),
			array(
				'parent' => 'Engineering',
				'old' => 'Telecommunications',
				//'new' => 'Electronics & Electrical Engineering'
				'new' => 'Electronic'
			),
			array(
				'parent' => 'Science',
				'old' => 'Nanotechnology',
				'new' => 'Physics'
			),
			array(
				'parent' => 'Science',
				'old' => 'Space Science',
				'new' => 'Physics'
			),
			array(
				'parent' => 'Medicine',
				'old' => 'Paramedical',
				//'new' => 'Nursing & Paramedical'
				'new' => 'Nursing'
			),
/*			array(
				'parent' => 'Medicine',
				'old' => 'Nursing',
				//'new' => 'Nursing & Paramedical'
				'new' => 'Nursing'

			),*/
			array(
				'parent' => 'Medicine',
				'old' => 'Orthoptics',
				'new' => 'Opthalmic'
			),
			array(
				'parent' => 'Medicine',
				'old' => 'Respiratory',
				'new' => 'ENT'
			),
			array(
				'parent' => 'Humanities',
				'old' => 'Design',
				//'new' => 'Fashion & Design'
				'new' => 'Fashion'
			),
			array(
				'parent' => 'Humanities',
				'old' => 'Religion',
				'new' => 'Social'
			),	
			array(
				'parent' => 'Humanities',
				'old' => 'Gender',
				'new' => 'Social'
			)
		);
		$this->initDB ();
		foreach ($input as $key => $value) {
			$sql = "select boardId from categoryBoardTable where name IN('".$value['old']."','".$value['new']."')".
					 	" and parentId IN (select boardId from categoryBoardTable where name LIKE ?".
					 	" and flag = 'studyabroad') and flag = 'studyabroad' ORDER  BY".
						" FIELD(name,?,?)";


			$query = $this->dbHandle->query($sql, array($value['parent'], $value['old'], $value['new']));
			$result = $query->result();
			$temp = array();
			foreach ($result as $row) {
				array_push($temp, $row->boardId);				
			}

			$output[] = $temp;
			
			
		}	
		foreach ($output as $key => $value) {
			if(count($value) == 2){
				$sql = "UPDATE tUserPref set abroad_subcat_id = '".$value[1]."' where abroad_subcat_id = '".$value[0]."'";
				echo $sql ;
				echo "<br />";
			} 
		}

	}

	function actualQuery(){
		
		$this->initDB ();
		$queryArray = array("UPDATE tUserPref set abroad_subcat_id = '276' where abroad_subcat_id = '340'",
							   "UPDATE tUserPref set abroad_subcat_id = '277' where abroad_subcat_id = '279'",
							   "UPDATE tUserPref set abroad_subcat_id = '275' where abroad_subcat_id = '278'",
							   "UPDATE tUserPref set abroad_subcat_id = '275' where abroad_subcat_id = '282'",
							   "UPDATE tUserPref set abroad_subcat_id = '276' where abroad_subcat_id = '281'",
							   "UPDATE tUserPref set abroad_subcat_id = '275' where abroad_subcat_id = '280'",
							   "UPDATE tUserPref set abroad_subcat_id = '257' where abroad_subcat_id = '258'",
							   "UPDATE tUserPref set abroad_subcat_id = '257' where abroad_subcat_id = '261'",
							   "UPDATE tUserPref set abroad_subcat_id = '257' where abroad_subcat_id = '255'",
							   "UPDATE tUserPref set abroad_subcat_id = '257' where abroad_subcat_id = '253'",
						   	   "UPDATE tUserPref set abroad_subcat_id = '254' where abroad_subcat_id = '251'",
						   	   "UPDATE tUserPref set abroad_subcat_id = '265' where abroad_subcat_id = '271'",
						   	   "UPDATE tUserPref set abroad_subcat_id = '263' where abroad_subcat_id = '273'",
						   	   "UPDATE tUserPref set abroad_subcat_id = '263' where abroad_subcat_id = '272'",
						   	   "UPDATE tUserPref set abroad_subcat_id = '283' where abroad_subcat_id = '298'",
						   	   "UPDATE tUserPref set abroad_subcat_id = '283' where abroad_subcat_id = '291'",
						   	   "UPDATE tUserPref set abroad_subcat_id = '301' where abroad_subcat_id = '308'",
						   	   "UPDATE tUserPref set abroad_subcat_id = '314' where abroad_subcat_id = '310'",
						   	   "UPDATE tUserPref set abroad_subcat_id = '311' where abroad_subcat_id = '312'",
						   	   "UPDATE tUserPref set abroad_subcat_id = '331' where abroad_subcat_id = '341'",
						   	   "UPDATE tUserPref set abroad_subcat_id = '327' where abroad_subcat_id = '324'",
				 		  	   "UPDATE tUserPref set abroad_subcat_id = '327' where abroad_subcat_id = '330'"
				   	   );
		
	
		foreach ($queryArray as $sql) {
			error_log("Executing query -------".$sql);
			$query = $this->dbHandle->query($sql);
			
		}
		error_log("Executing query..............Finished...");


	}

	function collegeReviewDataMigration(){ 
		ini_set("memory_limit", "1024M");
		error_log("==shiksha==<<== script starts here ==>>==");

		$this->initDB ();
		$mainTableToMaterTableMappings = array(
				'moneyrating' =>'Value for Money',
				'crowdCampusRating' =>'Crowd & Campus Life',
				'avgSalaryPlacementRating' =>'Salary & Placements',
				'campusFacilitiesRating' =>'Campus Facilities',
				'facultyRating' =>'Faculty'
			);
		
		$sql = "SELECT id, description FROM  CollegeReview_MasterTable WHERE type = 'rating' AND description IN ('Value for Money', 'Crowd & Campus Life', 'Salary & Placements', 'Campus Facilities', 'Faculty')";
		$queryResult = $this->dbHandle->query($sql)->result_array();
		
		$ratingMasterMappings = array();
		foreach($queryResult as $index=>$value){
			$ratingMasterMappings[$value['description']] = $value['id'];
		}		
		
		error_log("==shiksha== fetching data from main table --");
		$sql = "SELECT id, moneyrating, crowdCampusRating, avgSalaryPlacementRating, campusFacilitiesRating, facultyRating FROM  CollegeReview_MainTable WHERE averageRating = 0";
		$queryResult = $this->dbHandle->query($sql)->result_array();

		error_log("==shiksha== making main table data --");

		$i = 0;
		$chunk = 0;
		$collegeReviewAvgData = array();
		$RatingMappingdata = array();
		foreach($queryResult as $index=>$value){
			$avgRating = ($value['moneyrating'] + $value['crowdCampusRating']+ $value['avgSalaryPlacementRating']+ $value['campusFacilitiesRating']+ $value['facultyRating'])/5;

			$collegeReviewAvgData[$i][] = array(
												'id'=> $value['id'],
												'averageRating' => $avgRating
											);

			foreach($mainTableToMaterTableMappings as $key=>$row){

				$RatingMappingData[$i][] = array(
												'reviewId' => $value['id'],
												'masterRatingId' => $ratingMasterMappings[$row],
												'rating' => $value[$key]
											);
			}

			$chunk++;
			if($chunk == 100){
				$chunk = 0;
				$i++;
			}
		}

		
		error_log("==shiksha== updating avg rating in main table starts");
		foreach($collegeReviewAvgData as $index=>$value){
				$this->dbHandle->update_batch('CollegeReview_MainTable', $value, 'id');
				error_log("==shiksha== update main table ==".$index);
		}

		error_log("==shiksha== insertion starts in mapping table");
		foreach($RatingMappingData as $index=>$value){
			$this->dbHandle->insert_batch('CollegeReview_RatingMapping', $value);
			error_log("==shiksha== insertion index-==".$index);
		}

		error_log("==shiksha==<<== Script finished! congrts!! ==>>==");
	}

	function migrateMovitationData(){
		ini_set("memory_limit", "1024M");
		error_log("==shiksha==<<== script starts here ==>>==");

		$this->initDB ();
		$masterTableToMotivationTableMapping = array(
														'crowdcampuslife' => 12,
														'placementsalary' =>15,
														'faculty' =>13,
														'rankingbrandname' => 16,
														'campusinfrastructure' => 11,
														'coursestream' => 17,
														'fees' => 14
													);
		error_log("==shiksha== query to fetch data ==");
		$sql = "select id, crowdCampusLife, salaryPlacement, Campusfacilities, motivationFactor from CollegeReview_MotivationTable where motivationMasterId = 0";
		$queryResult = $this->dbHandle->query($sql)->result_array();
		error_log("==shiksha== query got the data, count == ".count($queryResult)."  and now data making starts ==");
		
		$i = 0;
		$chunk = 0;
		$motivationTableData = array();
		foreach($queryResult as $index=>$value){

			if(empty($value['motivationFactor']) || $value['motivationFactor'] == ""){
				if($value['crowdCampusLife'] == 'YES'){
					$motivationTableData[$i][] = array(
												'id' => $value['id'],
												'motivationMasterId'=> 12
												);
				}else if($value['salaryPlacement'] == 'YES'){
					$motivationTableData[$i][] = array(
												'id' => $value['id'],
												'motivationMasterId'=> 15
												);
				}else if($value['Campusfacilities'] == 'YES'){
					$motivationTableData[$i][] = array(
												'id' => $value['id'],
												'motivationMasterId'=> 11
												);
				}else{
					$motivationTableData[$i][] = array(
												'id' => $value['id'],
												'motivationMasterId'=> 0
												);
				}
			}else{
				$motivationTableData[$i][] = array(
												'id' => $value['id'],
												'motivationMasterId'=> $masterTableToMotivationTableMapping[$value['motivationFactor']]
												);
			}

			$chunk++;
			if($chunk == 100){
				$chunk = 0;
				$i++;
			}
		}

		error_log("==shiksha== data making done! and now update process starts ==");
		foreach($motivationTableData as $index=>$value){
				$this->dbHandle->update_batch('CollegeReview_MotivationTable', $value, 'id');
				error_log("==shiksha== update movtivation table ==".$index." count == ".count($value));
		}
		error_log("==shiksha==<<== Script finished! congrts!! ==>>==");
	}

	function test($formType = 2){
		$data = array();
		$data['formType'] = $formType;
		$this->load->view('test/test', $data);
	}

	function test2(){
		$this->load->view('regTest');
	}
	
	function insertInExlusionTable(){
	die;
		$this->initDB();

		$userid = array(5319288,5319209,5319277,5270551,5319257,5319420,5351411,5418577,5319229,5319357,1773426,5407296,5319366,5430007,5406720,5406739,5330032,5202460,5430007,5319375,1127220,5319469,5330133,1673354,4104460,5330032,5319211,5319364,5308319,5213939,5319544,160016,5319252,2683434,5319372,5319262,5330089,1458185,2024033,5444009,5330174,5430033,1552678,3632855,5443996,5329942,5330767,5429406,5330724,563638,5330887,5388992,5305459,4251945,5430038,5328058,5332691,5319482,5429459,5319322,5429465,5319206,5305462,5327987,5317539,2320323,5319426,5444012,5330001,5330495,5330730,5330870,5328728,5319440,5329963,5330952,5430043,5319385,5429469,5319328,3304308,5429481,5328168,5429488,5328030,5319234,5330733,5330800,5305312,5328015,5330783,5330430,5366129,1880268,5330424,5330832,2179085,5330819,5319587,5319351,5429502,1750745,5330144,5330942,5330750,5319286,5330391,5386828,5319398,5319497,4278987,5429512,5429516,1432708,5330169,5333307,1919510,5328022,5305423,5430057,5328410,5319237,5330817,4174250,5331032,559240,5317606,5319188,5333242,5317671,5330851,5427193,5346698,5328794,5333246,4199437,5350205,5456409,5319528,2301860,5329939,2832703,5319330,5319309,5329966,3169399,5429521,5430075,259521,1762838,5319376,2568620,5429525,577712,5319495,5430086,5329921,4220753,5319377,5120403,5319451,218984,5319573,3828441,5443976,5330027,5443980,2301701,5319441,5328006,5330155,5331015,5319378,5429532,2248622,5328114,5319455,5330794,5328271,5328658,4075194,3067855,5430097,3187249,5444007,5429535,5319302,5319214,5330863,5328003,5319199,4121625,5429537,5330848,3723056,5342445,5230414,5333024,5429541,5332961,5333264,5305522,5330270,5305534,5330147,5305528,5319250,5333281,5319583,5319581,5328668,5328741,4144558,5429455,5429505,194903,2998952,3433247,2834906,3040630,293468,1567164,1187315,706619,1422996,909862,1365664,646644,416188,206959,355548,387913,915312,370814,392229,1840483,1566781,852189,335722,1129242,3248080,3976690,1567994,157979,3457633,2892066,187585,888196,467451,894830,1133802,2053027,24901,22072,3137213,565084,3226151,370970,1999987,4356933,4412058,3248201,933172,2443520,3796507,916563,120484,179046,79826,5319299,5319408,5319431,5350175,4901757,5330762,5328365,5305502,5253246,5319244,5305513,5330995,2631496,5330705,5330793,5330779,5465287,5330847,558851,5350195,53242,5330831,5097792,5333318,5330716,5432363,5319435,5330903,5317514,5318574,5330981,5330327,5333200,5319458,5328428,5328694,5330440,5330778,5434102,5432397,5432432,5432437,5432443,5330658,5332934,5305278,5319197,5319321,5332903,5330803,5332882,5466644,5329996,5330771,5330726,5330892,5330746,5330749,5320668,5325837,5319411,742980,5305237,5319191,5330140,5330400,5330688,5330744,5328797,5330464,5319408,5391120,5330699,5330162,5317595,5330286,5330681,5389260,5305538,5330145,5330760,5330788,5334462,5319242,5319416,5328682,5328813,5330708,5419167,5319423,5319341,5319179,5391475,5129235,5319500,5328670,5330200,5330195,5330775,5317616,5358399,5305540,5319259,5330947,5328387,5330927,5341510,2235841,5328687,2336700,5328133,5319369,5305418,5319514,5305513,2631496,5330847,5328694,5330778,4281610,5328177,5330813,5319185,5330394,5330682,5330677,5330720,5319375,5213939,5319372,5328768,5330701,5319306,5319265,918469,5330692,5391136,3387674,5319223,5319515,5328080,5305449,5319208,5319333,5319421,5330658,5330726,5330746,5330749,5319540,5305475,5305322,5305487,5305490,5319268,5319526,5319230,5319269,5319278,496443,5305513,5319429,5319541,1896015,5331012,5331027,5319361,5319300,5330935,1859436,5330193,5328359,5328724,5328765,4280569,5329834,5328802,1057910,5330061,5330074,5328064,5330759,5330784,5330865,5363631,5350743,5350171,5317588,647759,5317505,2387718,5319551,1910653,5319559,5328651,5319313,5330820,5381519,5317669,5414361,1030868,853082,5319493,5380805,5328329,5330476,5330080,5330660,5319394,5319367,5330662,5330842,5391355,5464470,5402395,4523633,5319527,550249,5461228,5400996,5319501,5464591,5446151,3197356,5339528,5305482,5330179,3957016,5317654,5391326,1115475,5330659,5317602,5419319,988814,5330657,5331005,5391345,3166563,2982743,2842765,5319267,5319504,5330969,5330764,5330853,5407163,5358054,5391333,5391338,2317123,5407318,5191418,5462387,5429959,5429911,5443441,4206564,1762257,5443453,5319315,5319308,5330742,5330834,5329941,5429580,5353157,5441483,5429896,1127262,5360426,5319508,5319301,418328,3454163,5441569,5062211,5319467,5330675,2392013,5328284,5328069,5330840,883577,5330727,5330839,4697087,5328329,5330709,5329899,5319293,169752,5319568,5328782,5328786,5330808,5330183,5330406,4208476,1604334,5330958,5329928,5319350,5319591,4177437,5330188,4451750,5333294,5263339,5330988,985840,5329922,5319413,5330846,5319486,5330857,5328319,5328486,5330661,5330785,1444865,2528470,4319248,5328177,5319379,5305418,5329947,3003353,5330729,5330738,5319185,5319255,5248134,5317582,5328683,249561,5466557,5328702,5330394,5322442,5330682,5330686,5330826,5330720,5330828,5330667,5330658,5330755,5330754,3981219,5330677,2227477,5330696,5319402,5330690,5330825,5330869,5440425,5319420,5440435,5440438,623338,5330781,5440496,5330866,5330835,5440549,5440558,5440568,2036197,5440577,5440581,5330914,5440593,5440605,5440619,5440625,835924,5440658,5449393,1286673,5319262,5319545,4220616,5319270,5319403,5330949,210712,5328042,5330664,5312254,5330758,5330789,5330763,5330766,5330856,5341336,5330721,889263,5341371,5319396,5330924,5330756,5330824,5354115,5330710,5354123,5319388,5330806,5354154,5354160,5354164,4427858,5330867,4281360,5328087,5354192,5354196,5330896,5328790,5330732,5332435,5319295,2104452,5328338,5328761,5330743,5330874,5330849,5330801,5341316,5341344,2593617,5331871,5328439,5328736,5330694,5354095,5330919,5330722,5330776,5330739,1406768,4007838,5354147,5330899,3622469,4938864,5330833,5354206,5354223,5330813,5329957,5328395,4523098,5319569,5319466,5354329,5330714,5334460,5328766,5317544,5328060,5354350,5330910,5319353,5330886,2156595,5330713,5354366,5328350,5319557,5330266,5332201,1653259,5320406,2456555,684742,5319294,5319446,5319595,5331024,3269939,5319484,984220,5330992,5331003,5327964,5319187,5328775,5328704,5330389,5330385,5330347,2074117,5330280,5330499,5330850,1931513,5319248,5319400,5319239,5319327,5391176,852872,5328772,5319354,5328145,2336171,5330748,1198823,5330811,5419069,5336512,892394,1894566,2274801,5330752,5341432,4226813,5341448,5341454,1894598,5317564,5319325,5319464,5319550,5319561,5319193,5319254,5319275,5319334,5319343,1282124,5319365,5319368,5319371,1196317,5319374,5319397,3121102,5332209,5319597,5333314,5331022,5319478,3055057,5330965,5328048,5328054,5328162,5328643,5328791,5329956,5330129,5274477,5330197,5251112,5329808,5330723,941408,5330737,5328343,5340056,5341503,5427537,4066819,5432412,2666593,5432421,5319287,5432428,5432431,5432433,5432438,5330187,5432446,5440544,5432456,3060974,5432469,5432474,5432479,5319307,333325,5432496,2460557,5328020,5432512,5432529,5432535,3149853,5432541,5330770,5432604,5443328,3212911,5432615,5432620,5328180,2340888,5440559,5440564,5440569,5440572,5440574,5440580,5440582,1625982,4048459,5440586,5440592,5440596,5440599,5440617);

		
		$sql = "INSERT ignore into LDBExclusionList(userid,exclusionType,status) values";
		
		foreach ($userid as $user) {
			 $sql .= "('".$user."','Shiksha A&A Experts','live'),";		
		}
		
		$sql = substr($sql, 0,-1);

		$this->dbHandle->query($sql);
		
	}

	function registrationTrackingSourceImprovement(){
		$this->initDB();
		
		$startBeg = microtime(true);

		error_log("== Cron starts ====");
		$chunkSize = 500;
		$processed = 0;
		$start = microtime(true);

		error_log("== update query starts ====");
		$sql = 'UPDATE registrationTracking SET source = "free"';
		$this->dbHandle->query($sql);
		error_log("== update query ends and now processing starts ====");

		$paidSessions = array();
		error_log("== reading from DB ====");
		$sql = 'SELECT rt.visitorSessionId FROM registrationTracking rt 
		INNER JOIN session_tracking st ON (rt.visitorSessionId = st.sessionId)
		WHERE st.source = "paid"';
		$result = $this->dbHandle->query($sql)->result();
		error_log("== reading from DB done==== ".(microtime(true)-$start));

		// $test = $this->dbHandle->query($sql)->result_array();
		// error_log("== total result count ===".count($test));

		$totalCount = count($result);

		error_log("== total count ===".count($result));

		foreach ($result as $key => $value) {
			// $paidSessions['"'.$value->visitorSessionId.'"'] = 1;
			$paidSessions[] = '"'.$value->visitorSessionId.'"';
		}
		// $paidSessions = array_keys($paidSessions);
		error_log("== total result count after processing ===".count($paidSessions));

		$processed = 0;
		while( $processed < $totalCount) {
			
			$start = microtime(true);
			$paidSessionsChunk = array_slice($paidSessions, $processed, $chunkSize);
			error_log("== Updating ".($processed/$chunkSize)." chunk==== chunk size ---".count($paidSessionsChunk));
			if(!empty($paidSessionsChunk)){
				$sql = 'UPDATE registrationTracking SET source = "paid" WHERE visitorSessionId IN ('.implode(', ', $paidSessionsChunk).')';
				$this->dbHandle->query($sql);
			}
			error_log("== updating done====");
			$processed+=$chunkSize;
			error_log("== processed count =".$processed." == remaining ===".($totalCount-$processed)." === time ===".(microtime(true)-$start));
			error_log("=====================================================================================================================");
		}
		error_log("== Cron ends ===".(microtime(true)-$startBeg)." seconds");
	}

	function getDataFromMaster($data=27){ 
		ini_set("memory_limit",-1);
		ini_set("max_execution_time",-1);

		if($data > 30){
			echo "Incorrect Date!";
			die;
		}
		
		$this->initDB();
		$index = 1;

		$sql = "SELECT COUNT( * ) as Count FROM  shiksha.`tusersourceInfo` WHERE referer LIKE ('%JEE_Main_2016%') AND TIME >= '2016-04-".$data." 00:00:00' AND TIME <= '2016-04-".$data." 23:59:59'";
		$result = $this->dbHandle->query($sql)->result();
		echo $index.".     ".$sql." ==== count =====>   ".$result[0]->Count."<br/><br/><br/>";
		$index++;

		$sql="SELECT COUNT( * ) as Count FROM  shiksha.`tusersourceInfo` WHERE referer LIKE ('%_Des-Result%') AND TIME >= '2016-04-".$data." 00:00:00' AND TIME <= '2016-04-".$data." 23:59:59'";
		$result = $this->dbHandle->query($sql)->result();
		echo $index.".     ".$sql." ==== count =====>   ".$result[0]->Count."<br/><br/><br/>";
		$index++;

		$sql="SELECT COUNT( * ) as Count FROM  shiksha.`tusersourceInfo` WHERE referer LIKE ('%_Mob-Result%') AND TIME >= '2016-04-".$data." 00:00:00' AND TIME <= '2016-04-".$data." 23:59:59'";
		$result = $this->dbHandle->query($sql)->result();
		echo $index.".     ".$sql." ==== count =====>   ".$result[0]->Count."<br/><br/><br/>";
		$index++;

		$sql="SELECT COUNT( * ) as Count FROM  shiksha.`tusersourceInfo` WHERE referer LIKE ('%utm_source=facebook&utm_medium=social&utm_campaign=jee%' ) AND TIME >= '2016-04-".$data." 00:00:00' AND TIME <= '2016-04-".$data." 23:59:59'";
		$result = $this->dbHandle->query($sql)->result();
		echo $index.".     ".$sql." ==== count =====>   ".$result[0]->Count."<br/><br/><br/>";
		$index++;

		$sql="SELECT COUNT( * ) as Count FROM  shiksha.`tusersourceInfo` WHERE referer LIKE ('%Rank_Predictor%') AND TIME >= '2016-04-".$data." 00:00:00' AND TIME <= '2016-04-".$data." 23:59:59'";
		$result = $this->dbHandle->query($sql)->result();
		echo $index.".     ".$sql." ==== count =====>   ".$result[0]->Count."<br/><br/><br/>";
		$index++;

		$sql="SELECT COUNT( * ) as Count FROM  shiksha.`tusersourceInfo` WHERE referer LIKE ( '%Rank_Calculator%') AND TIME >= '2016-04-".$data." 00:00:00' AND TIME <= '2016-04-".$data." 23:59:59'";
		$result = $this->dbHandle->query($sql)->result();
		echo $index.".     ".$sql." ==== count =====>   ".$result[0]->Count."<br/><br/><br/>";
		$index++;

		$sql="SELECT COUNT( * ) as Count FROM  shiksha.`tusersourceInfo` WHERE referer LIKE ('%JEE_Main_2016_DT_India_Des-Result%') AND TIME >= '2016-04-".$data." 00:00:00' AND TIME <= '2016-04-".$data." 23:59:59'";
		$result = $this->dbHandle->query($sql)->result();
		echo $index.".     ".$sql." ==== count =====>   ".$result[0]->Count."<br/><br/><br/>";
		$index++;

		$sql="SELECT COUNT( * ) as Count FROM  shiksha.`tusersourceInfo` WHERE referer LIKE ('%JEE_Main_2016_DT_India_Mob-Result%') AND TIME >= '2016-04-".$data." 00:00:00' AND TIME <= '2016-04-".$data." 23:59:59'";
		$result = $this->dbHandle->query($sql)->result();
		echo $index.".     ".$sql." ==== count =====>   ".$result[0]->Count."<br/><br/><br/>";
		$index++;

		$sql="SELECT COUNT( * ) as Count FROM  shiksha.`tusersourceInfo` WHERE referer LIKE ( '%College_predictor%') AND TIME >= '2016-04-".$data." 00:00:00' AND TIME <= '2016-04-".$data." 23:59:59'";
		$result = $this->dbHandle->query($sql)->result();
		echo $index.".     ".$sql." ==== count =====>   ".$result[0]->Count."<br/><br/><br/>";
		$index++;

		$sql="SELECT COUNT( * ) as Count FROM  shiksha.`tusersourceInfo` WHERE referer LIKE ('%JEE_Main_2016_DT%') AND TIME >= '2016-04-".$data." 00:00:00' AND TIME <= '2016-04-".$data." 23:59:59'";
		$result = $this->dbHandle->query($sql)->result();
		echo $index.".     ".$sql." ==== count =====>   ".$result[0]->Count."<br/><br/><br/>";
		$index++;

	}

	function addRatingSubCatMapping(){
		$this->initDB();

		global $subCatsForCollegeReviews;
		$ratingMapping = array('1','2','3','4','5','7');
		$resultMapping = array();
		
		$sql = 'select masterId, categoryId from CollegeReview_CategoryMasterMapping where status="live"';
		$result = $this->dbHandle->query($sql)->result_array();
		foreach ($result as $key => $value) {
			$resultMapping[$value['categoryId']][] = $value['masterId'];
		}
		
		_p($resultMapping);
		
		$sql = 'insert into CollegeReview_CategoryMasterMapping (masterId, categoryId, status) values';
		$clauses = array();
		foreach ($subCatsForCollegeReviews as $key => $value) {
			if($value == '1' && $key != '23' && $key != '56'){
				foreach ($ratingMapping as $ratingValue) {
					
					if(!empty($resultMapping[$key]) && in_array($ratingValue, $resultMapping[$key]) ){
						echo $key." ====> ".$ratingValue."<br/>";
						continue;
					}

					$clauses[]= '('.$ratingValue.','.$key.', "live")';
				}				
			}
		}
		$sql .= implode(',', $clauses);
		
		$this->dbHandle->query($sql);
	}


	function showCustomLocUploadingInterface(){
		$userStatus = $this->checkUserValidation();
		
		if (($userStatus == "false" ) || ($userStatus == "") || ($userStatus[0]['usergroup'] != "cms")) {
		echo "Please login with CMS account";
		exit();
		}

		$this->load->view('showCustomLocUploadingInterface');
	}

	public function submitCustomLocCSV(){
		$userStatus = $this->checkUserValidation();
		if (($userStatus == "false" ) || ($userStatus == "") || ($userStatus[0]['usergroup'] != "cms")) {
		echo "Please login with CMS account";
		exit();
		}

		if(isset($_FILES['userSetFile']['name']) && $_FILES['userSetFile']['name'] !=''){
			$csvData = $this->_buildCVSArray($_FILES['userSetFile']['tmp_name']);
			
			$this->initDB();
			foreach ($csvData as $key => $value) {
				if($value['isHeadOffice'] != 'yes'){
					$value['isHeadOffice'] = 'no';
				}
				$this->dbHandle->insert('customMultilocationMail', $value); 
			}
			_p("Insertion Done!");
		}else{
			_p("Invalid file");
		}
		$a = '<a href="'.base_url().'LDBLeadMigrationScript/showCustomLocUploadingInterface"> Click here to go back!</a>';
		echo $a;
	}

	private function _buildCVSArray($File){
		$handle = fopen($File, "r");
		$fields = fgetcsv($handle, 0, ",");
		while($data = fgetcsv($handle, 0, ",")) {
			$detail[] = $data;			
		}
		
		$x = 0;
		$newData = array();
		foreach ($detail as $key => $value) {
			foreach($value as $k=>$v){
				$newData[$x][$fields[$k]] = $v;
			}
			$x++;
		}
		
		return $newData;
	}
	
	
	function addMotivationSubCatMapping(){
		$this->initDB();

		global $subCatsForCollegeReviews;
		$motivationMapping = array('11','12','13','14','15','16','17','18');
		$resultMapping = array();
		
		$sql = 'select masterId, categoryId from CollegeReview_CategoryMasterMapping where status="live"';
		$result = $this->dbHandle->query($sql)->result_array();
		foreach ($result as $key => $value) {
			$resultMapping[$value['categoryId']][] = $value['masterId'];
		}
		
		_p($resultMapping);
		
		$sql = 'insert into CollegeReview_CategoryMasterMapping (masterId, categoryId, status) values';
		$clauses = array();
		foreach ($subCatsForCollegeReviews as $key => $value) {
			if($value == '1' && $key != '23' && $key != '56'){
				foreach ($motivationMapping as $mappingValue) {
					
					if(!empty($resultMapping[$key]) && in_array($mappingValue, $resultMapping[$key]) ){
						echo $key." ====> ".$mappingValue."<br/>";
						continue;
					}

					$clauses[]= '('.$mappingValue.','.$key.', "live")';
				}				
			}
		}
		$sql .= implode(',', $clauses);
		
		$this->dbHandle->query($sql);
	}

	function addUsersToIndexQueue(){
		echo 'addUsersToIndexQueue:: cron started';
		error_log('addUsersToIndexQueue:: cron started');

		$this->initDB();

		$sql = 'select userId from LDBExclusionList';
		$result = $this->dbHandle->query($sql)->result_array();

		$this->load->model('user/usermodel');
		$usermodel =new usermodel;
		
		foreach ($result as $key => $value) {
			$usermodel->addUserToIndexingQueue($value['userId']);
		}

		echo 'addUsersToIndexQueue:: cron finished';
		error_log('addUsersToIndexQueue:: cron finished');
	}

	function updateMotivationFactorForCollegeReviews() {
		$userStatus = $this->checkUserValidation();
		
		if (($userStatus == "false" ) || ($userStatus == "") || ($userStatus[0]['usergroup'] != "cms")) {
			echo "Please login with CMS account";
			exit();
		}

		$this->load->view('updateMotivationFactorForCollegeReviews');
	}

	public function submitMotivationFactorMappingCSV(){
		$userStatus = $this->checkUserValidation();
		if (($userStatus == "false" ) || ($userStatus == "") || ($userStatus[0]['usergroup'] != "cms")) {
			echo "Please login with CMS account";
			exit();
		}

		if(isset($_FILES['mappingFile']['name']) && $_FILES['mappingFile']['name'] !=''){
			$csvData = $this->_buildCVSArray($_FILES['mappingFile']['tmp_name']);
			
			$this->dbLibObj = DbLibCommon::getInstance ( 'SUMS' );
			$this->dbHandle = $this->_loadDatabaseHandle ( "read" );

			// foreach ($csvData as $key => $value) {
			// 	if($value['id'] != ''){
			// 		$this->dbHandle->where('reviewId',$value['id']);
			// 		$data['motivationFactor'] = $value['motivationFactor'];
	  //       		$status = $this->dbHandle->update('CollegeReview_MotivationTable',$data);
			// 	}
   //      		_P('Review ID - '.$value['id'].', Motivation Factor - '.$value['motivationFactor'].', Status - '.$status);
			// }
			// _p("Insertion Done!");

			$i = 0;
			$soNumbers = array();

			// foreach ($csvData as $key => $value) {
			// 	if($value['id'] != ''){
			// 		$this->dbHandle->where('reviewId',$value['id']);
			// 		$data['motivationFactor'] = $value['motivationFactor'];
	  //       		$status = $this->dbHandle->update('CollegeReview_MotivationTable',$data);
			// 	}
   //      		_P('Review ID - '.$value['id'].', Motivation Factor - '.$value['motivationFactor'].', Status - '.$status);
			// }
			// _p("Insertion Done!");

			$i=0;
			foreach ($csvData as $key => $value) {
				if($value['Order No_'] != '' && !in_array($value['Order No_'], $soNumbers)){
					$soNumbers[] = $value['Order No_'];
				}
				$i++;
			}
			
			$finalSONumbers = implode("','", $soNumbers);
			$finalSONumbers = "'".$finalSONumbers."'";
			
			$this->load->model('sums/sumsmodel');
			$sumsmodel = new sumsmodel;
			$resultArray = $sumsmodel->getSODetails($finalSONumbers);
			
			// $f = '';
	        // $f.="REFERENCE_NUMBER,SUBID,TRANSACTION_ID,PROD_ID,PROD_NAME,PROD_DESC,SUB_LINE_NO,STATUS,QUANTITY,QUANTITY_LEFT,SUBSCRIPTION_START_DATE,SUBSCRIPTION_END_DATE\n";
			
			$filename = 'exportedSUMSData.csv';
			$f = fopen('php://output', 'w'); 
		    
		    header('Content-Type: application/csv');
		    header('Content-Disposition: attachment; filename="'.$filename.'"');

		    foreach ($resultArray as $line) { 
		        fputcsv($f, $line, ','); 
		    }
		    
		    fseek($f, 0);
		    return;
		}
		else{
			_P("Invalid file");
		}
		$a = '<a href="'.base_url().'LDBLeadMigrationScript/updateMotivationFactorForCollegeReviews"> Click here to go back!</a>';
		echo $a;
	}
	function getDuplicateDataForCRMTSI(){
		
		$this->load->model('CollegeReviewForm/collegereviewmodel');
		$this->crmodel = new Collegereviewmodel;

		$data = $this->crmodel->getDuplicateDataForCRMTSI();
		foreach ($data as $key => $value) {
			$deleteIds[$value['reviewId']] = $this->crmodel->getIdsForDeletion($value['reviewId']);
		}

		foreach ($deleteIds as $revId => $data) {
			$deleteIdsNew[$revId] = max(explode(',',$data[0]['idList']));
			$this->crmodel->deleteDuplicateRows($deleteIdsNew[$revId],$revId);
		}
		
		/*foreach ($deleteIdsNew as $key => $value) {
			$max = max($value);
			foreach ($value as $index => $id) {
				if($max == $id){
					// echo $value[$index].' ';
					unset($value[$index]);
				}
			}
		}
*/

		die;
	}

	function updateAbsoluteURLs(){
		$contentObj = $this->load->library('common/httpContent');
		$start_time = time();
		error_log("######start_time absolute cron ".$start_time);
		$tableColumnConfig = array();
		$tableColumnConfig = array(
									1 => array("tableName"=>"tusersourceInfo","contentColumnName"=>"referer","primaryColumnName"=>"id"),
									2 => array("tableName"=>"registrationTracking","contentColumnName"=>"referer","primaryColumnName"=>"id"),
									3 => array("tableName"=>"tuserLoginTracking","contentColumnName"=>"login_page_url","primaryColumnName"=>"id"),
									4 => array("tableName"=>"shikshaMailerMis","contentColumnName"=>"url","primaryColumnName"=>"id"),
									5 => array("tableName"=>"marketing_page_master","contentColumnName"=>"destination_url","primaryColumnName"=>"page_id"),
									6 => array("tableName"=>"marketing_page_master","contentColumnName"=>"banner_url","primaryColumnName"=>"page_id"),
									7 => array("tableName"=>"marketing_page_master","contentColumnName"=>"background_url","primaryColumnName"=>"page_id"),
									8 => array("tableName"=>"attachmentTable","contentColumnName"=>"attachment_url","primaryColumnName"=>"id")
								);
		$file = '/tmp/script_error_log.txt';
        $fp = fopen($file,'a');
			try{
				foreach($tableColumnConfig as $key => $columnName){
					$contentObj->findHttpInContent($columnName['tableName'], $columnName['primaryColumnName'], $columnName['contentColumnName']);
				}
			} catch(Exception $e){
				$exception = $e->getMessage();
				fwrite($fp,$exception);
				mail('mahak.arya@shiksha.com','Exception in https cron '.date('Y-m-d H:i:s'), 'From page: '.$_SERVER['HTTP_REFERER'].'<br/>'.print_r($exception));
			}
		$end_time = time();
		$time_delta = $end_time - $start_time;
		error_log("######time taken absolute cron ".$time_delta);
		fwrite($fp,$time_delta);
		fclose($fp);
	}

	function updateRelativeURLs(){
		$httpContentLib = $this->load->library('common/httpContent');
		$tableColumnConfig = array();
		$tableColumnConfig = array(
									1 => array("tableName"=>"CollegeReview_Tile","contentColumnName"=>"dImage"),
									2 => array("tableName"=>"CollegeReview_Tile","contentColumnName"=>"mImage"),
									3 => array("tableName"=>"CollegeReview_Tile","contentColumnName"=>"seoUrl"),
									4 => array("tableName"=>"CollegeReview_UserReferralInfo","contentColumnName"=>"referralURL"),
									5 => array("tableName"=>"tuser","contentColumnName"=>"avtarimageurl"),
									6 => array("tableName"=>"featured_guide","contentColumnName"=>"guide_url"),
									7 => array("tableName"=>"CollegeReview_MainTable","contentColumnName"=>"review_seo_url"),
									8 => array("tableName"=>"marketing_page_mailer","contentColumnName"=>"attachment_url"),
									9 => array("tableName"=>"marketing_page_master","contentColumnName"=>"background_image"),
									10 => array("tableName"=>"company_logos","contentColumnName"=>"logo_url")
								);
		foreach($tableColumnConfig as $key => $columnName){
			$httpContentLib->replaceAbsolutePathWithRelativepath($columnName['tableName'], $columnName['contentColumnName'],array('http://images.shiksha.com','http://www.shiksha.com','http://studyabroad.shiksha.com','http://mba.shiksha.com','http://engineering.shiksha.com'));
		}
	}

	function updateMMMAbsoluteURLs(){
		$start_time = time();
		error_log("######start_time absolute cron ".$start_time);

		$contentObj = $this->load->library('common/httpContent');
		$tableColumnConfig = array();
		$tableColumnConfig = array(
									1 => array("tableName"=>"mailerMis","contentColumnName"=>"trackerId","primaryColumnName"=>"id"),
									2 => array("tableName"=>"mailerTemplate","contentColumnName"=>"htmlTemplate","primaryColumnName"=>"id"),
									3 => array("tableName"=>"templateVariable","contentColumnName"=>"varValue","primaryColumnName"=>"id")
								);
		$contentObj->module = 'Mailer';
		foreach($tableColumnConfig as $key => $columnName){
			$contentObj->findHttpInContent($columnName['tableName'], $columnName['primaryColumnName'], $columnName['contentColumnName']);
		}

		$end_time = time();
		$time_delta = $end_time - $start_time;
		error_log("######mailer time taken absolute cron ".$time_delta);

		$file = '/tmp/script_error_log.txt';
        $fp = fopen($file,'a');
		fwrite($fp,'mailer=='.$time_delta);
		fclose($fp);
	}

	function deleteZeroDataForReviews(){
		$this->load->model('CollegeReviewForm/collegereviewmodel');
		$this->crmodel = new Collegereviewmodel;

		$this->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();
		$this->courseRepo = $courseBuilder->getCourseRepository();

		// $dataWhereYearOfGradZero = $this->crmodel->getZeroYOG();
		// $dataWhereCourseIdZero = $this->crmodel->getZeroCourse();
		$dataWhereInstituteIdZero = $this->crmodel->getZeroInst();
		$dataZeroInstLoc = array();
		foreach ($dataWhereInstituteIdZero as $key => $value) {
			$dataZeroInstLoc[$value['reviewId']] = $value['courseId'];
		}

		$courseObjArr = $this->courseRepo->findMultiple(array_values($dataZeroInstLoc),array('basic','location'));
		foreach ($courseObjArr as $key => $courseObj) {
			$courseData[$key] = array(	
									'instituteId' => $courseObj->getInstituteId(),
									'locationId' => $courseObj->getMainLocation()->getLocationId()
							);
		}

		foreach ($dataZeroInstLoc as $revId => $course) {
			$this->crmodel->updateShikshaMappingForZeroInst($revId,$courseData[$course]['instituteId'],$courseData[$course]['locationId']);
		}


		// $dataWhereLocationIdZero = $this->crmodel->getZeroLoc();

		/*echo "year of grad is zero \n";
		_P($dataWhereYearOfGradZero);
		echo "courseId is zero \n";
		_P($dataWhereCourseIdZero);*/
		echo "instituteId is zero \n";
		_P($dataWhereInstituteIdZero);
		/*echo "locationId is zero \n";
		_P($dataWhereLocationIdZero);*/
		die;
	}

	function creditConsumptionIssue(){
		ini_set('memory_limit', '2048M');
		ini_set('max_execution_time', -1);
		$this->load->model('user/usermodel');
		$usermodel =new usermodel;

		$LDBLeadContTrack = $usermodel->getDataFromLDBLeadContactedTracking();
		$subscriptionArr = array();
		
		foreach ($LDBLeadContTrack as $key => $value) {
				$usermodel->updateSubscriptionLog($value);
				
				if($subscriptionArr[$value['SubscriptionId']] < 1){
					$subscriptionArr[$value['SubscriptionId']] = 0;
				}

				$subscriptionArr[$value['SubscriptionId']] += 1;
		}

        $file = '/tmp/SubscriptionCreditsData'.date('Y-m-d').".csv"; 
        $fp = fopen($file,'a');
        fwrite($fp,"SubscriptionId,BaseProdRemainingQuantity(Old),BaseProdRemainingQuantity(New)\n");

		foreach ($subscriptionArr as $subId => $count) {
			$newBaseProdRemainingQty = 0;
			$addition = 0;
			$addition = $count * 25;
			error_log('========shiksha=======subscriptionId==='.$subId);
			$getDataToFile = $usermodel->getSubscriptionProductMppingData($subId);
			$newBaseProdRemainingQty = $getDataToFile[0]['BaseProdRemainingQuantity']+$addition;
			$writeData = $subId.", ".$getDataToFile[0]['BaseProdRemainingQuantity'].", ".$newBaseProdRemainingQty."\n";
			fwrite($fp,$writeData);
			$usermodel->updateSubscriptionProductMapping($subId, $addition);
		}
		
		$usermodel->updateLDBLeadContactedTracking();
            fclose($fp);
	}

	function deductCreditsForAbroadGenie(){
		return;
		$genies_ids = $this->getAllGenieForDeduction();
		
        $file = '/tmp/creditsDeductionData'.date('Y-m-d').".csv"; 
        $fp_deduction = fopen($file,'a');

        $file = '/tmp/userscreditsDeductionData'.date('Y-m-d').".csv"; 
        $fp_deduction1 = fopen($file,'a');
        
        fwrite($fp_deduction,"SubscriptionId,CreditsToDeduct,CreditsBeforeDeduction,CreditsAfterDeduction,CreditsDeducted, FullCreditDeduction\n");

        $all_subscription_ids =  array();
		foreach ($genies_ids as $genie) {
			$client_id = $genie['clientId'];
			$subscription_id = $genie['subscriptionId'];

			$credits = 0;
			$credits = $this->getCreditsToDeductForGenie($subscription_id);

			$credits = $credits*20;

			$genie_credit_map[$subscription_id] = $credits ;
		}

		foreach ($genie_credit_map as $subscription_id => $credit) {
			$remaining_credit = $this->getCreditsRemaining($subscription_id);

			if($remaining_credit < $credit){
				$credits_to_deduct = (int)($remaining_credit/20);	
				$credits_to_deduct = 0;
				$flag_full_deduction = 'NO';
			}else{

				$all_subscription_ids[] = $subscription_id;
				$credits_to_deduct = $credit;
				$flag_full_deduction = 'YES';
			}

			if($flag_full_deduction == 'YES'){
				$this->deductCredits($credits_to_deduct, $subscription_id);
			}
			
			$remaining_credit_after_deduct = $this->getCreditsRemaining($subscription_id);

			$writeData_deduction = '';
			$writeData_deduction = $subscription_id.", ".$credit.", ".$remaining_credit.",".$remaining_credit_after_deduct.",".$credits_to_deduct.",".$flag_full_deduction."\n";
			fwrite($fp_deduction,$writeData_deduction);
		}

		if(count($all_subscription_ids)>0){
			$records = array();
			$records = $this->getusersCreditsToDeductForGenie($all_subscription_ids);
			foreach($records as $record) {
				fwrite($fp_deduction1,$record['id'].'\n');
			}
			$this->updateContactTracking($all_subscription_ids);
		}
	}

	function updateContactTracking($all_subscription_ids){
		$this->dbHandleWrite = $this->_loadDatabaseHandle ( "write" );

		$query = "update  shiksha.LDBLeadContactedTracking set CreditConsumed = 85  where SubscriptionId in (?) and ContactDate>'2017-09-29 14:00:00' and CreditConsumed=65";

		$records = $this->dbHandleWrite->query( $query,array($all_subscription_ids) );
	}

	function getAllGenieForDeduction(){
		$this->initDB ();

		$query = "select distinct  sa.clientId, ldb.subscriptionId from SALeadAllocation sla join SAMultiValuedSearchCriteria sam on sla.agentId=sam.searchalertid join SASearchAgent sa on sa.searchagentid=sam.searchAlertId join LDBLeadContactedTracking ldb on sa.clientId=ldb.ClientId where sa.is_active='live' and  sam.keyname='desiredcourse' and sam.value>1499 and ldb.ContactDate>'2017-09-29 14:00:00' and CreditConsumed=65";

		$records = $this->dbHandle->query( $query )->result_array();
		
		return $records;
	}

	function getCreditsToDeductForGenie($subscription_id){
		$this->initDB ();

		$query = "select count(distinct ldb.userId) as cnt from SALeadAllocation sla join SAMultiValuedSearchCriteria sam on sla.agentId=sam.searchalertid join SASearchAgent sa on sa.searchagentid=sam.searchAlertId join LDBLeadContactedTracking ldb on sa.clientId=ldb.ClientId where sa.is_active='live' and  sam.keyname='desiredcourse' and sam.value>1499 and ldb.ContactDate>'2017-09-29 14:00:00' and CreditConsumed=65 and ldb.subscriptionId=?";

		$records = $this->dbHandle->query( $query,array($subscription_id) )->result_array();
		
		return $records[0]['cnt'];
	}

	public function getCreditsRemaining($subscription_id){
		$this->initDB ();

		$query = "select BaseProdRemainingQuantity from  SUMS.Subscription_Product_Mapping where SubscriptionId=? and status='ACTIVE' ";

		$records = $this->dbHandle->query( $query,array($subscription_id) )->result_array();
		
		return $records[0]['BaseProdRemainingQuantity'];
	}

	public function deductCredits($credit, $subscription_id){
		$this->dbHandleWrite = $this->_loadDatabaseHandle ( "write" );

		$query = "update  SUMS.Subscription_Product_Mapping set BaseProdRemainingQuantity = BaseProdRemainingQuantity - ? where SubscriptionId=? and status='ACTIVE'";

		$records = $this->dbHandleWrite->query( $query,array($credit,  $subscription_id) );
		
	}

	function getusersCreditsToDeductForGenie($subscription_id){
		$this->initDB ();

		$query = "select id from shiksha.LDBLeadContactedTracking where CreditConsumed = 85  where SubscriptionId in (?) and ContactDate>'2017-09-29 14:00:00' and CreditConsumed=65";

		$records = $this->dbHandle->query( $query,array($subscription_id) )->result_array();
		
		return $records;
	}


	public function indexSolrUSers(){
		error_log("---------- cron start ----------".date('Y-m-d h:i:s'));
		ini_set('memory_limit','2048M');

		$this->load->library('LDB/searcher/LeadSearchRequestGenerator');
        $this->requestGenerator = new LeadSearchRequestGenerator;
        
        $this->load->builder('SearchBuilder','search');
        $this->solrServer = SearchBuilder::getSearchServer();

		$dateParams = 'fq=-submitDate:[* TO *]';
		$requestParams[] = $dateParams;

		$requestParams[] = 'fq=DocType:user';
		$requestParams[] = 'fq=usergroup:user';

		$request = SOLR_LDB_SEARCH_SELECT_URL_BASE;
	    $request .= '?q=*%3A*&wt=phps&';
        $request .= implode('&',$requestParams);
        $request .= '&fl=userId';
        $request .= '&rows=20000&group=true&group.field=userId&group.ngroups=true';

        $request_array = explode("?",$request); 

        $response = $this->solrServer->MMMSearchCurl($request_array[0], $request_array[1]);

        $response = unserialize($response);          

        $response = $response['grouped']['userId']['groups'];   
        
        foreach ($response as $user_doc) {
        	$user_ids_to_index[] = $user_doc['groupValue'];
        }

        $chunkSize = 1000;
        $itr = 0;
        foreach ($user_ids_to_index as $userId) {
        	$user_chunk[] = $userId;
        	$itr++;
        	if($itr%$chunkSize == 0){
        		error_log('================ counter -> '.$itr);
        		Modules::run("user/UserIndexer/indexMultipleUsers", $user_chunk,true);
				$user_chunk = array();

        	}
        }

        if (count($user_chunk)>0) {
        	Modules::run("user/UserIndexer/indexMultipleUsers", $user_chunk,true);
			$user_chunk = array();
        	
        }


		error_log("---------- cron complete ----------".date('Y-m-d h:i:s'));
	}


	function removeDuplicateDisplayName(){	
		return;
		// fetch all duplicate display name
	 	$this->load->model('LDBCommonmodel');
        $commonModel = new LDBCommonModel();		 
        $users = $commonModel->getDuplipcateDisplayName();

        if(empty($users)){
        	echo 'No Duplicate display name';
        }

        $this->load->library('Register_client');
		$registerClient = new Register_client();
		$appId = 12;
		
		// loop duplicate display name
		$updatedArray = array();
		$duplicateDisplaynameUserIds = array();
		foreach ($users as $key => $value) {
			if(empty($value['displayname'])){
				continue;
			}		
			error_log("DISPLAY NAME    :  ".$value['displayname']);
			//extract user id
			$userRows = $commonModel->getUserIdsByDisplayName($value['displayname']);
			foreach ($userRows as $key => $value) {								
				$newDisplayName = sanitizeString($value['firstname']).rand(10001,99999);
				$checkresponse            = $registerClient->checkAvailability($appId,$newDisplayName,'displayname');
		
				if($checkresponse == 1){
					$responseCheckAvail = 1;
					while($responseCheckAvail == 1){
						$newDisplayName = sanitizeString($value['firstname']).rand(10001,99999);
						$responseCheckAvail = $registerClient->checkAvailability($appId,$newDisplayName,'displayname');
					}
				}		

				$updatedArray[$value['userId']]['displayname'] = $newDisplayName;
				$updatedArray[$value['userId']]['userId']      = $value['userId'];
				$duplicateDisplaynameUserIds[$value['userId']]['userId'] = $value['userId'];
				$duplicateDisplaynameUserIds[$value['userId']]['is_processed'] = 'n';
				$duplicateDisplaynameUserIds[$value['userId']]['oldDisplayName'] = $value['displayname'];
			}	
		}

		//update displayname in table
		$response = $commonModel->updateDisplayName($updatedArray);

		//insert the userIds to reindex in solr
		$response = $commonModel->insertDuplicateDisplayNameUserIds($duplicateDisplaynameUserIds);
	}


	function sanitizeDisplayName(){		
		return;
		$this->load->model('LDBCommonmodel');
        $commonModel = new LDBCommonModel();	

        $this->load->library('Register_client');
		$registerClient = new Register_client();
		$appId = 12;	 

        $incorrectDisplayName = $commonModel->getUserIdsOfIncorrectDisplayName();        	

    	foreach ($incorrectDisplayName as $key => $value) {
    		$newDisplayName = sanitizeString($value['displayname']);
			$checkresponse  = $registerClient->checkAvailability($appId,$newDisplayName,'displayname');
	
			if($checkresponse == 1){
				$responseCheckAvail = 1;
				while($responseCheckAvail == 1){
					$newDisplayName = sanitizeString($value['displayname']).rand(10001,99999);
					$responseCheckAvail = $registerClient->checkAvailability($appId,$newDisplayName,'displayname');
				}
			}		


    		$updatedArray[$value['userId']]['displayname'] = $newDisplayName;
    		$updatedArray[$value['userId']]['userId']      = $value['userId'];
    		if(count($updatedArray) == 500){
    			$commonModel->updateDisplayName($updatedArray);
    			$updatedArray = array();
    		}
    	}
    	if(!empty($updatedArray)){
    		$commonModel->updateDisplayName($updatedArray);        		
    	}     
		die('completed');
	}

	//one time migration script only
	function indexDisplayNameUsers(){
		$this->validateCron();
		ini_set('memory_limit', '2048M');
		error_log('===== cron starts ==='.date('Y-m-d h:i:s'));

		$itr = 1;
		while ( $itr <= 50) {
			error_log('===== chunk  '.$itr." started ===  ".date('Y-m-d h:i:s'));

			$this->indexChunkUser();

			error_log('===== chunk  '.$itr." ended ===  ".date('Y-m-d h:i:s'));
			$itr++;
		}

        error_log('===== cron ends ==='.date('Y-m-d h:i:s'));
	}

	function indexChunkUser(){
		$this->load->model('LDBCommonmodel');
        $commonModel = new LDBCommonModel();

        $all_user_ids  = array();
        $users = $commonModel->getUserIdsForDisplayName();

        foreach ($users as $user) {
        	$all_user_ids[] =  $user['userId'];
        }

        if(!empty($all_user_ids) && count($all_user_ids) > 0){
        	Modules::run("user/UserIndexer/indexMultipleUsers", $all_user_ids,true);	

        	$commonModel->updateStatusForDisplayName($all_user_ids);
        }
	}

	public function getStateCity() {

		$fileName = '/var/www/html/Naukri_State_Mapping_Zone_Sep_24th.csv';
		$csvData = $this->_buildCVSArray($fileName);
		
		
		$states = array();$cities =array(); $i=0;$j = 1;
		$sql = 'insert into naukri_cities (city_id, city_name, state_id, status) values ';
		foreach ($csvData as $key => $fields) {

			foreach ($fields as $field=>$value) {
				
				
				if($field == 'City') {
					//$cities[$state_name][] = $value;
					$sql .= ', ('.$j.',"'.$value.'",'.$i.',"live")';
					$j++;
				}
				if($field == 'State/UT' && !in_array($value,$states)) {
					$states[] = $value;
					$state_name = $value;
					//echo 'insert into naukri_states (state_id, state_name, status) values ('.$i.',"'.$value.'","live");<br/>';
					$i++;
				}
			}
		}
		echo $sql;
		//_p($cities);
	}

	public function autocomplete() {

$xml = '<?xml version="1.0" encoding="utf-8"?>
         <soap>
             <soapHeader>
                 <userName>Shiksha</userName>
                 <password>talisma</password>
                 <extraHeaderData>
                    <TalismaSessionkey></TalismaSessionkey>
                </extraHeaderData>
             </soapHeader>
             <soapData>
                 <CreateObjectEx>                 
         <objectType>20005</objectType>
                     <objectName></objectName>
                     <ownerID>2</ownerID>
                     <teamID>313</teamID>
                     <propData>
     <PropertyInfo>
<propertyID>23044</propertyID>
                             <propValue>5</propValue>
                             <rowID>-1</rowID>
                             <relJoinID>-1</relJoinID> <constraintId>-1</constraintId>
                         </PropertyInfo> 
     <PropertyInfo>
<propertyID>4810069</propertyID>
<propValue>313</propValue>
                             <rowID>-1</rowID>
                             <relJoinID>-1</relJoinID> <constraintId>-1</constraintId>
                         </PropertyInfo> 
      <PropertyInfo>      
<propertyID>4800005</propertyID>
                             <propValue>313</propValue>
                             <rowID>-1</rowID>
                             <relJoinID>-1</relJoinID> <constraintId>-1</constraintId>
                         </PropertyInfo>       
        <PropertyInfo>
<propertyID>4810007</propertyID>                            
<propValue>261</propValue>
                             <rowID>-1</rowID>
                             <relJoinID>-1</relJoinID> <constraintId>-1</constraintId>
                         </PropertyInfo>    
      <PropertyInfo>
<propertyID>24209</propertyID>                                                       <propValue>30</propValue>
                             <rowID>-1</rowID>
                             <relJoinID>-1</relJoinID> <constraintId>-1</constraintId>
                         </PropertyInfo>     
        <PropertyInfo>
<propertyID>4810021</propertyID>
                             <propValue>1848</propValue>
                             <rowID>-1</rowID>
                             <relJoinID>-1</relJoinID> <constraintId>-1</constraintId>
                         </PropertyInfo>
                         <PropertyInfo>
<propertyID>4800002</propertyID>
<propValue>BALAJI NV</propValue>
                             <rowID>-1</rowID>
                             <relJoinID>-1</relJoinID> <constraintId>-1</constraintId>
                         </PropertyInfo>
                         <PropertyInfo>      
<propertyID>4810051</propertyID>
<propValue>8939431043</propValue>
                             <rowID>-1</rowID>
                             <relJoinID>-1</relJoinID> <constraintId>-1</constraintId>
                         </PropertyInfo> 
                         <PropertyInfo>           
<propertyID>4810065</propertyID>
<propValue>  balajinv777@gmail.com </propValue>
                             <rowID>-1</rowID>
                             <relJoinID>-1</relJoinID> <constraintId>-1</constraintId>
                         </PropertyInfo>
                   <PropertyInfo>      
<propertyID>24758</propertyID>
<propValue>Chennai</propValue>
                             <rowID>-1</rowID>
                             <relJoinID>-1</relJoinID> <constraintId>-1</constraintId>
                         </PropertyInfo>       
      <PropertyInfo>     
<propertyID>25084</propertyID>
<propValue>Canada</propValue>
                             <rowID>-1</rowID>
                             <relJoinID>-1</relJoinID> <constraintId>-1</constraintId>
                         </PropertyInfo>          
       <PropertyInfo>
<propertyID>25087</propertyID>
<propValue>MS</propValue>
                             <rowID>-1</rowID>
                             <relJoinID>-1</relJoinID> <constraintId>-1</constraintId>
                         </PropertyInfo>   
       <PropertyInfo>   
<propertyID>25088</propertyID>
<propValue>Electronics &amp; Electrical Engineering</propValue>
                             <rowID>-1</rowID>
                             <relJoinID>-1</relJoinID> <constraintId>-1</constraintId>
                         </PropertyInfo>    
        <PropertyInfo>
<propertyID>25100</propertyID>
<propValue>NA</propValue>
                             <rowID>-1</rowID>
                             <relJoinID>-1</relJoinID> <constraintId>-1</constraintId>
                         </PropertyInfo>
         <PropertyInfo>
<propertyID>25101</propertyID>
<propValue>NA</propValue>
                             <rowID>-1</rowID>
                             <relJoinID>-1</relJoinID> <constraintId>-1</constraintId>
                         </PropertyInfo>
                           </propData>
                    <operationParams>
<ignoreMandatoryCheck>false</ignoreMandatoryCheck>
<updateReadOnlyProperties>true</updateReadOnlyProperties>
                     </operationParams>
                 </CreateObjectEx>
           </soapData>
         </soap>';
         //echo $xml;exit;
		 $SOAPData =  json_decode(json_encode(simplexml_load_string($xml)),true);
		// _p($SOAPData);exit;
        foreach ($SOAPData['soapData'] as $key => $value) {
            $functionName = $key;
            break;
        }

echo $functionName;
        $soapHeader = $SOAPData['soapHeader'];
        $client = new SOAPClient('https://crmweb-100054.campusnexus.cloud/COFiService100054/Cof.AsmX?wsdl', array("trace" => true,"exceptions" => true));


        $this->CI->load->domainClass('WsseAuthHeader');
        $client->__setSoapHeaders(Array(new WsseAuthHeader($soapHeader)));

        $params = $SOAPData['soapData'][$functionName];
        $response=$client->$functionName($params);
_p($response);exit;

	}

	function sendLeads() {

		$flag =0;
		$start = 0;
		$file = fopen('bbatalentedge.csv', 'r');
		
		while (!feof($file))
		{
		
			$data = array();
			$temp = fgetcsv($file);

 			$flag++;
            if($flag > $start && $flag <= $start+300) {
//                              break;
            } else {
                    continue;
            }


			$data['FirstName'] = $temp[0];
			$data['LastName'] = $temp[1];
			$data['Email'] = $temp[2];
			$data['Mobile'] = $temp[3];
			$data['CityName'] = $temp[4];
			$data['StateName'] = $temp[5];
			$data['Address'] = $temp[6];
			$data['Custom1']= $temp[7];
			$data['Custom2'] = $temp[8];
			$data['Custom3'] = $temp[9];
			$data['Custom4'] = $temp[10];
			$data['Custom5'] = $temp[11];
			$data['Custom6'] = $temp[12];
			$data['CountryName'] = $temp[13];
			$data['SourceName'] = $temp[14];
			$data['MediaName'] = $temp[15];
			$data['CampaignName'] = $temp[16];
			$data['MiddleName'] = $temp[17];
			$data['BatchCode'] = $temp[18];
			$data['Comment'] = $temp[19];
			$data['SourcePath'] = $temp[20];

			$url = "http://182.18.170.106:8084/api/lead";

			$fields_string = http_build_query($data, '', '&amp;');

			echo $fields_string.'<br/>';

			/*$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url );
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($ch, CURLOPT_TIMEOUT,        10);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
			curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

			$result = curl_exec($ch);

			if($errno = curl_errno($ch)) {
			    $error_message = curl_strerror($errno);
			    echo "cURL error ({$errno}):\n {$error_message}";
			}

			curl_close($ch);
			_p($result);*/
			
			$flag++;
		}
		fclose($file);
	}

	public function migrateInvalidCityUsers(){
		error_log('### cron started ###');
		$this->load->model('LDBCommonmodel');
        $commonModel = new LDBCommonModel();		

        $city_data = $commonModel->getAllCityDetails();

        foreach ($city_data as $city) {
        	$city_data_map[strtoupper($city['city_name'])] = $city['city_id'];		
        }

        $city_data_map['DELHI'] = 74;

        $invalid_users_data = $commonModel->getInvalidCityUsers();
     
        foreach ($invalid_users_data as $key => $value) {
        	if($city_data_map[strtoupper($value['City'])]>0){
        		$invalid_users_data_map[$city_data_map[strtoupper($value['City'])]][] = $value['userId'];
        	}else{
        		$invalid_users_data_map[0][] = $value['userId'];
        	}
        	
        	$indexUserData[$value['userId']]['userId'] = $value['userId'];
			$indexUserData[$value['userId']]['is_processed'] = 'n';
			$indexUserData[$value['userId']]['oldDisplayName'] = $value['City'];

        }

        //insert the userIds to reindex in solr
		$response = $commonModel->insertDuplicateDisplayNameUserIds($indexUserData);

		$itr=0;
        foreach ($invalid_users_data_map as $city_id => $user_ids) {
        	$itr++;
        	error_log('===== counter '.$itr);
        	$commonModel->updateUserCityId($city_id, $user_ids);
        }

        error_log('### cron ended ###');
	}

	function createCopyTable(){
		ini_set('memory_limit', '1024M');
		ini_set('max_execution_time', -1);

		
		_P(date('h:i:s'));
		$this->initDB ();
		$this->dbHandle = $this->_loadDatabaseHandle ( "write" );
		$this->redisLib = PredisLibrary::getInstance();

		$sql = "select value as courseid, searchalertid as searchagentid from SAMultiValuedSearchCriteria join listings_main lm on lm.listing_type_id=value  where keyname = 'clientcourse' and lm.status='live' and listing_type='course' and is_active='live'";

		$result = $this->dbHandle->query($sql)->result_array();

		error_log('===== total rows == '.count($result));

		$itr=0;
		foreach ($result as $row) {
			$itr++;
			
			error_log('==== counter  === '.$itr);

			$sql="select recommended_course_id, recommended_institute_id,weight, last_update from collaborativeFilteredCourses where course_id=? and weight>100000";
			$sub_result = $this->dbHandle->query($sql,array($row['courseid']))->result_array();

			if(count($sub_result) <1){
				continue;
			}

			foreach ($sub_result as $data) {
				$redis_data_set = json_encode(array('sa_id'=>$row['searchagentid'],'c_id'=>$row['courseid']));
				$redis_key 	  = 'sa_mr_courses_'.$data['recommended_course_id'];
				$response = $this->redisLib->addMembersToSet($redis_key,array($redis_data_set));
				
			}		
		}
		
		_P(date('h:i:s'));
	}


	public function portGlaRespones(){
		return;

		$checkData = date('Y-m-d');
		if($checkData > '2019-06-20'){
			mail('teamldb@shiksha.com', "Stop GLA responses porting cron", "method -portGlaRespones");
			die;
		}
	
		//$this->validateCron();
		$this->redisLib = PredisLibrary::getInstance();

		$this->load->model('ldbleadmigrationmodel');
		$ldbleadmigrationmodel =new ldbleadmigrationmodel;				

		$redisKey ='gla_responses';
		$redisLastId = $this->redisLib->getMemberOfString($redisKey);
		$redisLastId = json_decode($redisLastId,true) ;

		$tempLMSId =$redisLastId['tempLMSId'];
		$logId = $redisLastId['logId'];

		/*$tempLMSId =50654327;
		$logId = 806600705;*/

		$courseIds = array(231114,284548,305547,311171,311175,265600,305545,311189,231105,285758,265588,231109,311223,285750,231156,311219,305549,286324,231113,311169,231106,285746,311221,231112,285754,311217,365885,285757,265597,265586,265587,265599,265585,365993,366003,306449,293824,311359,311361,24845,365543,231146,311225,310565,265615,231151,310557,310563,310569,310559,265606,311335,237118,265610,231120,265611,283355,318209,231122,286336,285036,318207,265609,265612,231121,311343,285037,311337,365717);
		$genieIds = array(35705);
		$toEmail = 'glauniversity6@gmail.com';
		$bccEmail = 'anshu.chhabra@shiksha.com';
		$subject = "Responses from Shiksha.com";
		

		$vendorMapping = array();
		$vendor = 'GLA';
		$glaVendorData = $ldbleadmigrationmodel->getGlaVendorMapping($vendor);
		$vendorMapping = $this->formatVendorData($glaVendorData);

		$responseData = $ldbleadmigrationmodel->getAllResponsesForClient($courseIds, $tempLMSId);
		
		error_log('======== 1 ======= ');
		foreach ($responseData as $key => $value) {
			$distinctUserIds[$value['userId']] = 	$value['userId'];
			$tempLMSId=$value['id'];
		}	


		$mrData 	  = $ldbleadmigrationmodel->getAllMatchedResponsesForClient($genieIds, $logId);
		foreach ($mrData as $mrValue) {
			$allLogId[] 					= $mrValue['id'];
			$logIdUserMap[$mrValue['id']] 	= $mrValue['userId'];
			$logId  						= $mrValue['id'];
			$tempData 	  = $ldbleadmigrationmodel->getMRCourseId($mrValue['id'], $mrValue['userId']);
			
			if(count($tempData)>0){
				$mrCourseData[]		  = $tempData;
			}

		}
			
		foreach ($mrCourseData as $key => $mrCourseValue) {
			$tempUserId								= $logIdUserMap[$mrCourseValue['id']];
			$finalMRData[$key] 						= $mrCourseValue;
			$finalMRData[$key]['userId'] 			= $tempUserId;
			$distinctUserIds[$tempUserId] 			= $tempUserId;
		}

		if(count($distinctUserIds)<1){
			_P('return');
			return;
		}
	
		unset($mrCourseData);
		$merged_data = array_merge($responseData,$finalMRData);
		unset($responseData);
		unset($finalMRData);

		$userInfoData = $ldbleadmigrationmodel->getUserInfoData($distinctUserIds);
		
		unset($distinctUserIds);
		foreach ($userInfoData as $userdata) {
			$userDataMap[$userdata['userId']] = $userdata;
		}

		unset($userInfoData);

		//$jsonFormat = {"name":"Jayashree","course":"B. Ed","branch":"No Branch","email":"sathishkutty242@gmail.com","mobile":"8012081803","state":"Tamil Nadu","district":"Cuddalore", "gender":"F","message":" Please provide more details about this course.","source":"Facebook"};

		$this->load->library('alerts/Alerts_client');
		$alerts_client = new Alerts_client();

		error_log('===== 1 =====');
		$userIdDeDup = array();

		foreach ($merged_data as $data) {
			$email = array();
		
			if($userIdDeDup[$data['userId']]>0)	{
				continue;
			}

			$userIdDeDup[$data['userId']] = $data['userId'];


			$userPro = $userDataMap[$data['userId']];
		
			$email['name'] 			= $userPro['firstname'].' '.$userPro['lastname'];
			$email['course'] 		= $vendorMapping['course'][$data['courseId']];
			$email['branch'] 		= $vendorMapping['branch'][$data['courseId']];
			$email['email'] 		= $userPro['email'];
			$email['mobile'] 		= $userPro['mobile'];
			
			$email['state'] 		= $vendorMapping['state'][$userPro['state']];
			if(empty($email['state'])){
				$email['state'] 		= $userPro['state_name'];
			}


			$email['district'] 		= $vendorMapping['city'][$userPro['city']];
			if(empty($email['district'])){
				$email['district'] 		= $userPro['city_name'];
			}

			$email['source'] 		= "Shiksha Response";

			$content = json_encode($email);

			$response = $alerts_client->externalQueueAdd("12", "info@shiksha.com", $toEmail , $subject, $content, $contentType = "html",'', 'n', array(),'',$bccEmail,'Shiksha.com','','Y');

			$response = $alerts_client->externalQueueAdd("12", "info@shiksha.com", 'ajay.sharma@shiksha.com' , $subject, $content, $contentType = "html",'', 'n', array(),'','','Shiksha.com','','Y');
		}
		

		$redisJson = array();
		$redisJson['tempLMSId'] = $tempLMSId;
		$redisJson['logId'] 	= $logId;
		$redisJson = json_encode($redisJson);
		$this->redisLib->addMemberToString($redisKey, $redisJson);
	}

	private function formatVendorData($glaVendorData){
		foreach ($glaVendorData as $key => $value) {
			if($value['entity_type'] == 'state'){
				$returnData['state'][$value['shiksha_entity']]  = $value['vendor_entity'];
			}

			if($value['entity_type'] == 'course'){
				$returnData['course'][$value['shiksha_entity']]  = $value['vendor_entity'];
			}

			if($value['entity_type'] == 'branch'){
				$returnData['branch'][$value['shiksha_entity']]  = $value['vendor_entity'];
			}

			if($value['entity_type'] == 'city'){
				$returnData['city'][$value['shiksha_entity']]  = $value['vendor_entity'];
			}
		}

		return $returnData;
		
	}

	public function populateExamDataforUser(){
		$this->validateCron();
		ini_set("memory_limit", "4000M");
        ini_set('max_execution_time', -1);    
        
       	$examModel = $this->load->model('examPages/exammodel');
       	$examGroupData  = $examModel->getExamNameGroupId();
       	$examNameGroupIdMapping = array();
       
       	foreach ($examGroupData as $examInfo) {
       		$examNameGroupIdMapping[strtoupper(trim($examInfo['name']))] = $examInfo['groupId'];
       	}
      
    	$userModel = $this->load->model('user/userModel');
    	$lastReturnedId = 0;
    	$batchSize = 20000;
       	while(1){
            $examDataforUser = $userModel->getExamDataForUser($lastReturnedId,$batchSize);
           
            if(!empty($examDataforUser)){
            	$updateData = array();
            	foreach ($examDataforUser as $key => $value) {
            		
            		if(!empty($examNameGroupIdMapping[strtoupper(trim($value['Name']))])) {
            			
                		$data = array();
                		$data['id'] = $value['id'];

                		$data['examGroupId'] = $examNameGroupIdMapping[strtoupper(trim($value['Name']))];
                		$updateData[]=$data;
                		
                		unset($data);
                	}
                	$lastReturnedId  = $value['id'];
            	}
            	$userModel->updateGroupIdForUser($updateData);
            	unset($updateData);
            	error_log("Update Done till Id : ".$lastReturnedId );
            	sleep(5);
            } else {
            	break;
            }  
        }
        unset($examNameGroupIdMapping);     
	}
}



