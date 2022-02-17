<?php

class StudyAbroadListingMigrationScripts extends MX_Controller {
	protected $dbLibObj;
	protected $dbHandle;
	
	function __construct(){
		exit();
	}
	
	function initDB() {
		$this->dbLibObj = DbLibCommon::getInstance('Listing');
		$this->dbHandle = $this->_loadDatabaseHandle("write");
	}
	
	/* $files : array for files that are to be INCLUDE'd or REQUIRE'd
	 * 
	 * try to include/require inside your function, code is kept here just to demonstrate its use
	 *
	$files = array(dirname(dirname(dirname(dirname(__FILE__))))."/etc/dbUpdates/excel_migration/<further path & filename>",
		       "");
	
	foreach($files as $filename){
		require($filename);
	}
	*/
   
    
	/*
	* This function was used to delete old study abroad courses when new study abroad goes live
	* 
	*/
	function deleteOldAbroadListings(){
		// Code break point to void data corruption for not running more than once.
		echo "<br/> This code is broken here internally so as to not run it more than once for avoiding data corruption.";
		echo "<br/> To run this again, make code changes in Controller";
		
		die();
		
		
		ini_set('max_execution_time', -1);
		ini_set('memory_limit', '-1');
		$seeker = 0;
		$this->initDB();
		// first get all institutes that were posted as abroad course earlier....
		$sql = "select distinct ".
			" lm.listing_type_id , ".
			" lm.listing_type , ".
			" lm.listing_title, ".
			" lm.status, ".
			" ilt.country_id, ".
			" ium.university_id ".
			" from ".
			" listings_main lm ".
			 	" inner join ".
			" institute_location_table ilt ON (lm.listing_type_id = ilt.institute_id ".
			 	" and ilt.status = lm.status) ".
			 	" left join ".
			" institute_university_mapping ium ON (lm.listing_type_id = ium.institute_id ".
			 	" and ium.status in ('live' , 'staging', 'draft')) ".
			" where ".
			" lm.listing_type = 'institute' ".
			 	" and ium.institute_id is null ".
			 	" and ilt.country_id > 2 ".
			 	" and lm.status in ('live' , 'draft')";
			
		$oldInstitutes = $this->dbHandle->query($sql)->result_array();
		error_log("\n SQL for getting institutes that are goin to be deleted : ".$sql, 3,"/tmp/SA-deletion.log");
		
		$inInstituteStr = "";
		$inInstituteChunkStr = "";
		$instituteChunks = array();
		$comma = "";$count = 0;
		foreach ($oldInstitutes  as $k=>$row){
			if($count >2000)
			{
				$instituteChunks[] = $inInstituteChunkStr;
				$inInstituteChunkStr = "";
				$comma = "";$count = 0;
			}
			//echo  "<pre>";var_dump($row['listing_type_id']);echo  "</pre>";
			$inInstituteStr .= $comma.$row['listing_type_id'];
			$inInstituteChunkStr .= $comma.$row['listing_type_id'];
			$comma = ",";$count++;
		}
		$instituteChunks[] = $inInstituteChunkStr;
		//$inInstituteStr = "32850,36201,36819,31548,25568,25568,32938,29576,25591";
		error_log("\n Institutes that are goin to be deleted : ".print_r($instituteChunks,true), 3,"/tmp/SA-deletion.log");
		
		// now get all courses of these institutes ...
		$sql = 	" select distinct ".
			" cd.course_id, ".
			" cd.courseTitle, ".
			" cla.institute_location_id, ".
			" ilt.institute_location_id ".
			" from ".
			" course_details cd ".
			 	" inner join ".
			" institute_location_table ilt ON (cd.institute_id = ilt.institute_id ".
			 	" and cd.status = ilt.status ".
			 	" and ilt.country_id > 2) ".
			 	" inner join ".
			" course_location_attribute cla ON (cd.course_id = cla.course_id ".
			 	" and cla.status = cd.status) ".
			" where ".
			 	" cd.institute_id in (".$inInstituteStr.") ".
			 	" and cd.status in ('live' , 'draft') ";
				//echo "sql:".$sql;
		$oldCourses = $this->dbHandle->query($sql)->result_array();
		error_log("\n SQL for getting institutes that are goin to be deleted : ".$sql, 3,"/tmp/SA-deletion.log");
		
		$comma = "";$count = 0;
		$courseChunks = array();
		foreach ($oldCourses  as $k=>$row){
			if($count == 2000){
				$courseChunks[] = $inCourseStr;
				$inCourseStr = "";
				$comma = "";
				$count = 0;
			}
			//echo  "<pre>";var_dump($row['listing_type_id']);echo  "</pre>";
			$inCourseStr .= $comma.$row['course_id'];
			$comma = ",";$count++;
		}
		$courseChunks[] = $inCourseStr;
		error_log("\n courses that are goin to be deleted : ".print_r($courseChunks,true), 3,"/tmp/SA-deletion.log");
		//_p($instituteChunks);_p($courseChunks);
		/* UPDATE/DELETE COURSES !!!!!!
		 * prepare update sql for individual tables joined with listings_main
		 * and eventually for listings_main as well
		 */
		$updateSqls = array();
		foreach($courseChunks as $courseChunk)
		{	
			$lmWhereClause = "where listing_type = 'course' and listing_type_id in (".$courseChunk.") and status in ('draft','live') ";
			$setWhereStatus = " set status = 'deleted' where status in ('draft','live') ";
			if($courseChunk!=""){
				$updateSqls[] =  "update  course_details ".$setWhereStatus.
						  " and course_id in (".$courseChunk.")";
				$updateSqls[] =  "update  categoryPageData  ".$setWhereStatus.
						  " and course_id in (".$courseChunk.")";
				$updateSqls[] =  "update  listing_attributes_table  ".$setWhereStatus.
						  " and listing_type = 'course' and listing_type_id in (".$courseChunk.")";
				$updateSqls[] =  "update  listing_category_table  ".$setWhereStatus.
						  " and listing_type = 'course' and listing_type_id in (".$courseChunk.")";
				$updateSqls[] =  "update  company_logo_mapping  ".$setWhereStatus.
						  " and listing_type = 'course' and listing_id in (".$courseChunk.")";
				$updateSqls[] =  "update  listingRankings  ".$setWhereStatus.
						  " and listingType = 'course' and listingId in (".$courseChunk.")";
				$updateSqls[] =  "update  listing_contact_details  ".$setWhereStatus.
						  " and listing_type = 'course' and listing_type_id in (".$courseChunk.")";
				$updateSqls[] =  "update  listingExamMap  ".$setWhereStatus.
						  " and type = 'course' and typeId in (".$courseChunk.")";
				$updateSqls[] =  "update  header_image  ".$setWhereStatus.
						  " and listing_type = 'course' and listing_id in (".$courseChunk.")";
				$updateSqls[] =  "update  clientCourseToLDBCourseMapping  ".$setWhereStatus.
						  " and clientCourseID in (".$courseChunk.")";
				$updateSqls[] =  "update  listing_media_table  ".$setWhereStatus.
						  " and type = 'course' and type_id in (".$courseChunk.")";
				$updateSqls[] =  "update  listings_ebrochures ".$setWhereStatus.
						  " and listingType = 'course' and listingTypeId in (".$courseChunk.")";
				$updateSqls[] =  "update  course_attributes  ".$setWhereStatus.
						  " and course_id in (".$courseChunk.")";
				$updateSqls[] =  "update  course_features  ".$setWhereStatus.
						  " and listing_id in (".$courseChunk.")";
				$updateSqls[] =  "update listings_main ".
						" set status = 'deleted' , last_modify_date = now(), comments = 'SA MAJOR RELEASE 20TH MAY OLD ABROAD COURSE DELETION ' ".$lmWhereClause;
				$updateSqls[] =  "update  course_location_attribute  ".$setWhereStatus.
						  " and course_id in (".$courseChunk.")";
		}
			// update all the above table for this chunk
			if(count($updateSqls)>0){
				foreach($updateSqls as $sql)
				{	
					$this->dbHandle->query($sql);
					echo "</br> seeker : ".$seeker++;
					error_log("\n update for course deletion : ".$sql, 3,"/tmp/SA-deletion.log");
				}
			}
		}
		/* UPDATE/DELETE INSTITUTES !!!!!!
		 * prepare update sql for individual tables joined with listings_main
		 * and eventually for listings_main as well
		 */
		$updateSqls = array();
		foreach($instituteChunks as $instituteChunk)
		{	
			$lmWhereClause = "where listing_type = 'institute' and listing_type_id in (".$instituteChunk.") and status in ('draft','live') ";
			$setWhereStatus = " set status = 'deleted' where status in ('draft','live') ";
			if($instituteChunk!=""){
				$updateSqls[] =  "update  institute ".$setWhereStatus.
						  " and institute_id in (".$instituteChunk.")";
				$updateSqls[] =  "update  listing_attributes_table  ".$setWhereStatus.
						  " and listing_type = 'institute' and listing_type_id in (".$instituteChunk.")";
				$updateSqls[] =  "update  listing_category_table  ".$setWhereStatus.
						  " and listing_type = 'institute' and listing_type_id in (".$instituteChunk.")";
				$updateSqls[] =  "update  listing_contact_details  ".$setWhereStatus.
						  " and listing_type = 'institute' and listing_type_id in (".$instituteChunk.")";
				$updateSqls[] =  "update  company_logo_mapping  ".$setWhereStatus.
						  " and listing_type = 'institute' and listing_id in (".$instituteChunk.")";
				$updateSqls[] =  "update  institute_join_reason  ".$setWhereStatus.
						  " and institute_id in (".$instituteChunk.")";
				$updateSqls[] =  "update  header_image  ".$setWhereStatus.
						  " and listing_type = 'institute' and listing_id in (".$instituteChunk.")";
				$updateSqls[] =  "update  topinstitutes  ".$setWhereStatus.
						  " and instituteid in (".$instituteChunk.")";
				$updateSqls[] =  "update  listing_media_table  ".$setWhereStatus.
						  " and type='institute' and type_id in (".$instituteChunk.")";
				$updateSqls[] =  "update  listings_ebrochures  ".$setWhereStatus.
						  " and listingType = 'course' and listingTypeId in (".$instituteChunk.")";
				$updateSqls[] =  "update  listingRankings ".$setWhereStatus.
						  " and listingType = 'course' and listingId in (".$instituteChunk.")";
				$updateSqls[] =  "update  listing_admission_contact_details  ".$setWhereStatus.
						  " and listing_type = 'institute' and listing_type_id in (".$instituteChunk.")";
				$updateSqls[] =  "update listings_main ".
						" set status = 'deleted' , last_modify_date = now(), comments = 'SA MAJOR RELEASE 20TH MAY OLD ABROAD INSTITUTE DELETION ' ".$lmWhereClause;
				$updateSqls[] =  "update  institute_location_table  ".$setWhereStatus.
						  " and institute_id in (".$instituteChunk.")";
			}			
			// update all the above table for this chunk
			if(count($updateSqls)>0){
				foreach($updateSqls as $sql)
				{
					$this->dbHandle->query($sql);
					echo "</br> seeker : ".$seeker++;
					error_log("\n update for institute deletion : ".$sql, 3,"/tmp/SA-deletion.log");
				}
			}
			
		}
		error_log("\n Halfway done...",3,"tmp/SA-deletion.log");
		//remove from search as well
		foreach($oldCourses as $oldCourse)
		{
			modules::run('search/Indexer/delete', $oldCourse['course_id'],"course","false");
		}
		foreach($oldInstitutes as $oldInstitute)
		{
			modules::run('search/Indexer/delete', $oldInstitute['listing_type_id'],"institute","false");
		}
		
		error_log("\n deletion Successful",3,"tmp/SA-deletion.log");

	}


    
	public function _init(){
	    $this->load->config('studyAbroadRedirectionConfig');
	    $this->instituteMappings = $this->config->item('migrationInstituteUniversityMappings');
	    $this->categoryMappings = $this->config->item('studyAbroadParentCategoryIdMappings');
	}
    
	public function migrateMainInstitutes()
	{
		// Code break point to void data corruption for not running more than once.
		echo "<br/> This code is broken here internally so as to not run it more than once for avoiding data corruption.";
		echo "<br/> To run this again, make code changes in Controller";
		
		die();
		
		
		echo '<h6>';
		$this->_init();
		$this->initDB();
		$keysToBeAdded = array();        
		$countryIds = $this->dbHandle->query("select countryId from ".ENT_SA_COUNTRY_TABLE_NAME." where countryId > 2")->result_array();
		foreach($countryIds as $record){
		    $i = $record['countryId'];
		    for($j = 239;$j<246;$j++){
			$query = "select keyPageId from tPageKeyCriteriaMapping where countryId = $i and categoryId = $j and stateId = 0 and cityId = 0 and subCategoryId = 0";
			$records = $this->dbHandle->query($query)->result_array();
			$check = reset($records);
			if($check['keyPageId'] != ''){
			    $keysToBeAdded[$i][$j] = $check['keyPageId'];
			    //echo $i.'.'.$j.'.'.$check['keyPageId'].'<br/>';
			}
			else{
			    $query =  "insert into tPageKeyCriteriaMapping (countryId,stateId,cityId,subCategoryId,categoryId) values ($i,0,0,0,$j)";
			    $keyVal = $this->dbHandle->query($query);
			    //echo $i.'.'.$j.'.'.$this->dbHandle->insert_id().'<br/>';
			    $keyVal = $this->dbHandle->insert_id();
			    $keysToBeAdded[$i][$j] = reset($keyVal);
			}
		    }
		}
		//and we have an array that will take category and country for new key Id which we can now assign
		$query = "  select tpkcm.countryId, tpkcm.categoryId, pcd.listing_type_id, pcd.StartDate, pcd.EndDate,
			    pcd.subscriptionId, pcd.id
			    from PageCollegeDb pcd, tPageKeyCriteriaMapping tpkcm
			    where tpkcm.keyPageId = pcd.KeyId and tpkcm.countryId > 2 and pcd.status='live' and pcd.listing_type='institute'";
		$resultTable = $this->dbHandle->query($query)->result_array();
		$idArray = array();
		foreach($resultTable as $row){
		    $data = array();
		    $data['listing_type_id']=$this->imap($row['listing_type_id']);
		    if($data['listing_type_id']!=''){
			$data['KeyId'] = $keysToBeAdded[$row['countryId']][$this->catmap($row['categoryId'])];
			if($data['KeyId'] != ''){
				$data['StartDate']=$row['StartDate'];
				$data['EndDate']=$row['EndDate'];
				$data['subscriptionId']=$row['subscriptionId'];
				$data['status']="'live'";
				$data['listing_type']="'university'";
				$query = "
				    insert into PageCollegeDb
				    (KeyId, listing_type_id,StartDate,EndDate,subscriptionId,status,listing_type)
				    values(";
				$query.= $data['KeyId'].",";
				$query.= $data['listing_type_id'].",";
				$query.= "'".$data['StartDate']."',";
				$query.= "'".$data['EndDate']."',";
				$query.= $data['subscriptionId'].",";
				$query.= $data['status'].",";
				$query.= $data['listing_type'].")";
				echo "Insertion Query : ".$query.'<br/>';
				$this->dbHandle->query($query);
				$idArray[] = $row['id'];
			}
		    }
		}
		$query = "update PageCollegeDb set status = 'deleted' where id in (".implode(",",$idArray).")";
		echo 'DeletionQuery : '.$query;
		$this->dbHandle->query($query);
		echo '</h6>';
	}
    
    private function catmap($oldCategoryId){
        $newCategoryId = $this->categoryMappings[$oldCategoryId];
        return $newCategoryId['id'];
    }
    
    private function imap($instituteid){
        $universityId = $this->instituteMappings[$instituteid];
        return $universityId;
    }

	// function for Shoshkeles & Category Sponsors migration 
    public function shoshkeleCategorySponsors(){
	
	// Code break point to void data corruption for not running more than once.
	echo "<br/> This code is broken here internally so as to not run it more than once for avoiding data corruption.";
	echo "<br/> To run this again, make code changes in Controller";
	
	die();
	
	$pathToMapping = dirname(dirname(dirname(__FILE__)))."/public/mapping/";	// path to mapping folder
	
	$files = array($pathToMapping."Excel/reader.php");
	foreach($files as $filename){
		require($filename);
	}
	$sheetname = "Category_Sponsor.xls";	// file name to be read
	$dataFile = $pathToMapping.'Data/'.$sheetname;
	if(file_exists($dataFile)){
		echo "<br/> File read";
		$data = new Spreadsheet_Excel_Reader();	// Class to Excel Reader
		$data->read($dataFile);	//Read Excel File
		$sheet = 0;
		$countRows = count($data->sheets[$sheet]['cells']);
		echo "<br/> Total Records in sheet".$countRows;
		$rawData = array();
		echo "test".$data->sheets[$sheet]['cells'][1][1];
		for($row=2; $row<=$countRows; $row++){
			array_push($rawData, array("clientId"		=>	$data->sheets[$sheet]['cells'][$row][1],
						   "shoshkeleUrl"	=>	$data->sheets[$sheet]['cells'][$row][2],
						   "listingUrl"		=>	$data->sheets[$sheet]['cells'][$row][3],
						   "oldInstId"		=>	$data->sheets[$sheet]['cells'][$row][4],
						   "newInstId"		=>	$data->sheets[$sheet]['cells'][$row][5],
						   "category"		=>	$data->sheets[$sheet]['cells'][$row][6],
						   "city"		=>	$data->sheets[$sheet]['cells'][$row][7],
						   "subcriptionId"	=>	$data->sheets[$sheet]['cells'][$row][8]
						   ));
		}
		echo "Data From xls file"._p($rawData);
		
		//get DB handle
		$this->initDB();
		
		foreach($rawData as $obj){
			//banner-Id selection from tbanners table
			$sql = "select bannerid from tbanners where status='live' and clientid='".$obj['clientId']."' and bannerurl='".$obj['shoshkeleUrl']."' ";
			$resultSet = $this->dbHandle->query($sql)->row_array();
			if($resultSet['bannerid'] == ''){
				continue;
			}
			$obj['bannerId'] = $resultSet['bannerid'];
			
			//get live data from tbannnerlinks for given subcription-Id in xls sheet 
			$sql = "select * from tbannerlinks where status='live' and subscriptionid='".$obj['subcriptionId']."' ";
			$resultSet = $this->dbHandle->query($sql)->row_array();
			$obj['tbannerlinks'] = $resultSet;
			
			//get live data from tlistingsubscription for given subcription-Id in xls sheet
			$sql = "select * from tlistingsubscription where status='live' and subscriptionid='".$obj['subcriptionId']."' ";
			$resultSet = $this->dbHandle->query($sql)->row_array();
			$obj['tlistingsubscription'] = $resultSet;
			
			//get live data from tcoupling for given subscription-Id and banner-Id
			$sql = "select * from tcoupling where status='coupled' and listingsubsid='".$obj['tlistingsubscription']['listingsubsid']."' and bannerlinkid='".$obj['tbannerlinks']['bannerlinkid']."' ";
			$resultSet = $this->dbHandle->query($sql)->row_array();
			$obj['tcoupling'] = $resultSet;
			
			//Update query for tbannerlinks for given subscription-Id
			$obj['query']['updatebannerlinks'] = "update tbannerlinks set status='deleted' where status='live' and subscriptionid='".$obj['subcriptionId']."' ";
			
			//Insert query for tbannerlinks for given subscription-Id and banner-Id
			$obj['query']['insertbannerlinks'] = "insert into tbannerlinks(bannerlinkid,bannerid,startdate,enddate,categoryid,subcategoryid,countryid,stateId,cityid,subscriptionid,status,comment,product) ";
			$obj['query']['insertbannerlinks'] .= "select max(bannerlinkid)+1,'".$obj['bannerId']."','".$obj['tbannerlinks']['startdate']."','".$obj['tbannerlinks']['enddate']."','".$obj['category']."','".$obj['tbannerlinks']['subcategoryid']."',";
			$obj['query']['insertbannerlinks'] .= "'".$obj['tbannerlinks']['countryid']."','".$obj['tbannerlinks']['stateId']."','".$obj['tbannerlinks']['cityid']."','".$obj['tbannerlinks']['subscriptionid']."','live','".$obj['tbannerlinks']['comment']."','".$obj['tbannerlinks']['product']."' from tbannerlinks ";
			
			//update query for tlistingsubscription for given subscription-Id
			$obj['query']['updatelistingsubscription'] = "update tlistingsubscription set status='deleted' where status='live' and subscriptionid='".$obj['subcriptionId']."' ";
			
			//insert query for tlistingsubscription for given subscription-Id
			$obj['query']['insertlistingsubscription'] = "insert into tlistingsubscription(listing_type_id,startdate,enddate,pagename,listing_type,categoryid,subcategory,subscriptionid,status,comment,clientid,cityid,stateid,countryid) ";
			$obj['query']['insertlistingsubscription'] .= "value('".$obj['newInstId']."','".$obj['tlistingsubscription']['startdate']."','".$obj['tlistingsubscription']['enddate']."','country','university','".$obj['category']."','".$obj['tlistingsubscription']['subcategory']."','".$obj['tlistingsubscription']['subscriptionid']."',";
			$obj['query']['insertlistingsubscription'] .= "'live','".$obj['tlistingsubscription']['comment']."','".$obj['tlistingsubscription']['clientid']."','".$obj['tlistingsubscription']['cityid']."','".$obj['tlistingsubscription']['stateid']."','".$obj['tlistingsubscription']['countryid']."')";
			foreach($obj['query'] as $query){
				$this->dbHandle->query($query);
			}
			$obj['query']['newbannerlinkid'] = "select bannerlinkid from tbannerlinks where status='live' and subscriptionid='".$obj['subcriptionId']."' ";
			$resultSet = $this->dbHandle->query($obj['query']['newbannerlinkid'])->row_array();
			$obj['newbannerlinkid'] = $resultSet['bannerlinkid'];
			
			$obj['query']['newlistingsubsid'] = "select listingsubsid from tlistingsubscription where status='live' and subscriptionid='".$obj['subcriptionId']."' ";
			$resultSet = $this->dbHandle->query($obj['query']['newlistingsubsid'])->row_array();
			$obj['newlistingsubsid'] = $resultSet['listingsubsid'];
			
			$obj['query']['updatecoupling'] = "update tcoupling set status='decoupled' where status='coupled' and listingsubsid='".$obj['tlistingsubscription']['listingsubsid']."' and bannerlinkid='".$obj['tbannerlinks']['bannerlinkid']."' ";
			
			$obj['query']['insertcoupling'] = "insert into tcoupling(listingsubsid,bannerlinkid,status,comment) ";
			$obj['query']['insertcoupling'] .= "value('".$obj['newlistingsubsid']."','".$obj['newbannerlinkid']."','coupled','".$obj['tcoupling']['comment']."') ";
			$this->dbHandle->query($obj['query']['updatecoupling']);
			$this->dbHandle->query($obj['query']['insertcoupling']);
			
			
			_p($obj);
		}
		echo "queries execution success";
	}else{
		echo "<br/> File not read";
	}
	
    }
}
?>

