<?php
// @author : Romil Goel

/*****************************
* Constant definition section
*****************************/
// set this contant if all errors(irrespective whether they are occuring or not) need to be shown
define("SHOW_ALL_ERRORS", 0);
// set this contant if script should not stop at point where it should stop in normal behaviour
define("PREVENT_EXIT_POINTS", 0);
/*****************************
* DB Tables covered
* 1. listings_main
* 
*****************************/

class CourseDataInconsistencyFixer extends MX_Controller
{
    
	private function _init()
	{
		ini_set("memory_limit", "-1");
		ini_set('max_execution_time', -1);

		// flag to determine whether clear the data or not
		$this->clearDataFlag = ( isset($_REQUEST['clean']) && !empty($_REQUEST['clean']) ) ? 1 : 0;

		$this->load->model('categoryList/listingdatacleaningmodel');
		// get the objects of the model
		$this->listingdatacleaningmodelObj = new ListingDataCleaningModel;
		$this->listingdatacleaningmodelObj->init($this->clearDataFlag);

		//_p("<style> .error { color: red; font-size: 13px; font-weight:bold; padding-left:60px;} .start { font-size : 15px; font-weight : bold; }</style>");
		$this->clearDataFlag 		= 0;
		$this->inconsistencyFoundFlag 	= 0;
		$this->resultArr 		= array();
		$this->instituteIds		= array();
	}
	
	public function getCoursesToBeValidated()
	{
		$this->_init();
		$courseIds = array();
		$courses = $this->listingdatacleaningmodelObj->getCoursesToBeValidated(array('live','draft','history'));
		foreach($courses as $courseid) $courseIds[] = $courseid['listing_type_id'];
		//_p("At 1 : ".count($courseIds));
		
		$courseIdsToBeExcluded = $this->listingdatacleaningmodelObj->getCoursesToBeValidated(array('deleted'));
		foreach($courseIdsToBeExcluded as $courseid) $courseIdsToBeExcludedIds[] = $courseid['listing_type_id'];
		//_p("At 2 : ".count($courseIdsToBeExcludedIds));
		
		$abroadCourseIds = $this->listingdatacleaningmodelObj->getAbroadCoursesIds();
		foreach($abroadCourseIds as $courseid) $courseIdsToBeExcludedIds[] = $courseid['listing_type_id'];
		//_p("At 3 : ".count($courseIdsToBeExcludedIds));
		
		$finalCoursesIds = array_diff($courseIds, $courseIdsToBeExcludedIds);
		//_p("Final : ".count($finalCoursesIds));
		
		
		//$finalCoursesIds  = array(200020);
		//$finalCoursesIds  = array(202552, 202551, 202550, 23595, 171779, 199565, 2359, 177900);
		
		arsort($finalCoursesIds);
		//$finalCoursesIds = array_slice($finalCoursesIds, 0,5000);
		
		$instituteIds = $this->listingdatacleaningmodelObj->getInstituteIdsOfCourses($finalCoursesIds);
		foreach($instituteIds as $row) $this->instituteIds[$row['course_id']] = $row['institute_id'];
		
		$count = 0;
		foreach($finalCoursesIds as $courseId)
		{
			$this->inconsistencyFoundFlag = 0;
			
			$this->FixInconsistency('course', $courseId);
			$count++;
			
			
			if($count%1000 == 0)
				error_log("CORRUPT_DATA : Checked ".$count." courses out of ".count($finalCoursesIds ).", error found : ".count($this->resultArr));

		}
		
		$this->generateExcelSheet();

	}
	
	public function FixInconsistency( $type = 'course', $listing_id)
	{
		
		if(empty($listing_id) )
		{
		    //_p("Please provide course/institute id");
		    return false;
		}
		$this->_init();
		
		if($type == 'institute')
                {
                        $course_ids = $this->listingdatacleaningmodelObj->getCoursesOfInstitutes($listing_id);
                        _p("Total Courses : ".count($course_ids));

                        foreach($course_ids as $key=>$courseRow)
                        {
				$this->live_version  = "";
				$this->draft_version = "";
                                _p("================== ".$key." ) FOR COURSE ID : ".$courseRow['course_id']." =========================");
                                $this->_cleanCourseData($courseRow['course_id']);
                        }
                }
		
		if($type == 'course' && !empty($listing_id))
		{
		    $this->_cleanCourseData($listing_id);    
		}
	}
	
	// Clean the inconsistent course data(if any)
	private function _cleanCourseData($listing_id)
	{

		//_getCourseLatestVersion == _cleanListingsMainData
		$returnVal = $this->_cleanListingsMainData($listing_id);
		
		if($returnVal == -1)
			return;
		else if($returnVal == 2 && $this->clearDataFlag)
		{
			$returnVal = $this->_cleanListingsMainData($listing_id);
			if($returnVal == -1)
				return;
			else if($returnVal == 2 && $this->clearDataFlag)
			{
				$returnVal = $this->_cleanListingsMainData($listing_id);
				if($returnVal == -1)
					return;
			}
		}
    
		// Add the calls of function of the table for which the data need to made consistent
		$this->_cleanCourseDetailsData($listing_id);
		
		$this->_cleanClientCourseToLDBCourseMappingData($listing_id);
		
		$this->_cleanListingAttributesTableData($listing_id);
		
		$this->_cleanCourseFeaturesData($listing_id);
		
		$this->_cleanListingExamMapData($listing_id);
		
		$this->_cleanCourseLocationAttributeData($listing_id);
		
		$this->_cleanListingContactDetailsData($listing_id);
		
		$this->_cleanListingCategoryTableData($listing_id);
		
		//$this->_checkForAllHistoryEntriesOfListing($listing_id);
		
		//$this->_checkForVersionOfListing($listing_id);
		
	}

	// Clean the inconsistency in course_details table
	private function _cleanCourseDetailsData($listing_id)
	{
		// return if inconsistency for the course is already found somewhere
		if($this->inconsistencyFoundFlag) return;
		
		$tablename = "course_details";
		$this->_startingMessage($tablename);
		
		// check if data exists for this course or not
		$rs = $this->listingdatacleaningmodelObj->getCourseDetailsData($listing_id, array('draft','live'));
		if(count($rs) <= 0 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("message_type"=>3, "status"=>'live', "listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    // #Pending : clean the data
		    _p("Cannot fix this issue");
		    return;
		}

		// check for multiple live entries
		$rs = $this->listingdatacleaningmodelObj->getCourseDetailsData($listing_id, array('live'));
		
		if(count($rs) > 1 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("status"=>'live', "listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    //return;
		    // #Done : clean the data
		    $this->_clearCourseDetailsDataForMultipleStatus($listing_id, 'live');
		}
		
		// check for multiple draft entries
		$rs = $this->listingdatacleaningmodelObj->getCourseDetailsData($listing_id, array('draft'));
		if(count($rs) > 1 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("status"=>'draft', "listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    //return;
		    // #Done : clean the data
		    $this->_clearCourseDetailsDataForMultipleStatus($listing_id, 'draft');
		}
		
		// check if draft version is less than live version
		$rs = $this->listingdatacleaningmodelObj->checkCourseDetailsLiveDraftPositionIssue($listing_id);
		if(count($rs) != 0 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("message_type"=>2,"listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    //return;
		    // #Done : clean the data
		    $this->listingdatacleaningmodelObj->clearCourseDetailsDataForLiveDraftPositionIssue($listing_id, $this->live_version);
		}
		
		// check if live version is 0
		$flag = $this->listingdatacleaningmodelObj->checkIfLiveVersionIsIncorrectCourseDetails($listing_id);
		if($flag || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("message_type"=>7,"listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		     //return;
		     $this->listingdatacleaningmodelObj->clearCourseDetailsDataForLiveVersionInconsistency($listing_id);
		}
		
		$rs = $this->listingdatacleaningmodelObj->checkAndCleanVersionInconsistency($tablename, $listing_id, $this->draft_version, $this->live_version, 'course_id');
		
	}
	
	
	// Clean the inconsistency in clientCourseToLDBCourseMapping table
	private function _cleanClientCourseToLDBCourseMappingData($listing_id)
	{
		// return if inconsistency for the course is already found somewhere
		if($this->inconsistencyFoundFlag) return;
		
		$tablename = "clientCourseToLDBCourseMapping";
	    	$this->_startingMessage($tablename);

		// check for multiple live entries
		$rs = $this->listingdatacleaningmodelObj->getClientCourseToLDBCourseMappingData($listing_id, array('live'));
		if(count($rs) > 0 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("status"=>'live', "listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    //return;
		    // #Done : clean the data
		    $this->listingdatacleaningmodelObj->clearClientCourseToLDBCourseMappingDataForMultipleStatus($listing_id, 'live',$this->live_version);
		}
		
		// check for multiple draft entries
		$rs = $this->listingdatacleaningmodelObj->getClientCourseToLDBCourseMappingData($listing_id, array('draft'));
		if(count($rs) > 0 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("status"=>'draft', "listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    //return;
		    // #Done : clean the data
		    $this->listingdatacleaningmodelObj->clearClientCourseToLDBCourseMappingDataForMultipleStatus($listing_id, 'draft',$this->draft_version);
		}
		
		// check if draft version is less than live version
		$rs = $this->listingdatacleaningmodelObj->getClientCourseToLDBCourseMappingLiveDraftPositionIssue($listing_id);
		if(count($rs) != 0 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("message_type"=>2,"listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    //return;
		    // #Done : clean the data
		    $this->listingdatacleaningmodelObj->clearClientCourseToLDBCourseMappingForLiveDraftPositionIssue($listing_id, $this->live_version);
		}
		
		$rs = $this->listingdatacleaningmodelObj->checkAndCleanVersionInconsistency($tablename, $listing_id, $this->draft_version, $this->live_version, 'clientCourseID');
	}

	// Clean the inconsistency in listing_attributes_table table
	private function _cleanListingAttributesTableData($listing_id)
	{
		// return if inconsistency for the course is already found somewhere
		if($this->inconsistencyFoundFlag) return;
		
		$tablename = "listing_attributes_table";
		$this->_startingMessage($tablename);

		// check for multiple live entries
		$rs = $this->listingdatacleaningmodelObj->getListingAttributesTableData($listing_id, array('live'));
		if(count($rs) > 0 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("status"=>'live', "listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    //return;
		    // #Done : clean the data
		    $this->listingdatacleaningmodelObj->clearListingAttributesTableDataForMultipleStatus($listing_id, 'live',$this->live_version);
		}
		
		// check for multiple draft entries
		$rs = $this->listingdatacleaningmodelObj->getListingAttributesTableData($listing_id, array('draft'));
		if(count($rs) > 0 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("status"=>'draft', "listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    //return;
		    // #Done : clean the data
		    $this->listingdatacleaningmodelObj->clearListingAttributesTableDataForMultipleStatus($listing_id, 'draft',$this->draft_version);
		}
		
		// check if draft version is less than live version
		$rs = $this->listingdatacleaningmodelObj->getListingAttributesTableLiveDraftPositionIssue($listing_id);
		if(count($rs) != 0 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("message_type"=>2,"listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    //return;
		    // #Done : clean the data
		    $this->listingdatacleaningmodelObj->clearListingAttributesTableForLiveDraftPositionIssue($listing_id, $this->live_version);
		}
		
		$rs = $this->listingdatacleaningmodelObj->checkAndCleanVersionInconsistency($tablename, $listing_id, $this->draft_version, $this->live_version, 'listing_type_id');
	
	}

	// Clean the inconsistency in  course_features table
	private function _cleanCourseFeaturesData($listing_id)
	{
		// return if inconsistency for the course is already found somewhere
		if($this->inconsistencyFoundFlag) return;
		
		$tablename = "course_features";
		$this->_startingMessage($tablename);

		// check for multiple live entries
		$rs = $this->listingdatacleaningmodelObj->getCourseFeaturesData($listing_id, array('live'));
		if(count($rs) > 0 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("status"=>'live', "listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    //return;
		    // #Done : clean the data
		    $this->listingdatacleaningmodelObj->clearCourseFeaturesDataForMultipleStatus($listing_id, 'live',$this->live_version);
		}
		
		// check for multiple draft entries
		$rs = $this->listingdatacleaningmodelObj->getCourseFeaturesData($listing_id, array('draft'));
		if(count($rs) > 0 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("status"=>'draft', "listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    //return;
		    // #Done : clean the data
		    $this->listingdatacleaningmodelObj->clearCourseFeaturesDataForMultipleStatus($listing_id, 'draft',$this->draft_version);
		}
		
		// check if draft version is less than live version
		$rs = $this->listingdatacleaningmodelObj->getCourseFeaturesLiveDraftPositionIssue($listing_id);
		if(count($rs) != 0 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("message_type"=>2,"listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    //return;
		    // #Done : clean the data
		    $this->listingdatacleaningmodelObj->clearCourseFeaturesForLiveDraftPositionIssue($listing_id, $this->live_version);
		}
		
		$rs = $this->listingdatacleaningmodelObj->checkAndCleanVersionInconsistency($tablename, $listing_id, $this->draft_version, $this->live_version, 'listing_id');
	}
	
	// Clean the inconsistency in  listingExamMap table
	private function _cleanListingExamMapData($listing_id)
	{
		// return if inconsistency for the course is already found somewhere
		if($this->inconsistencyFoundFlag) return;
		
		$tablename = "listingExamMap";
		$this->_startingMessage($tablename);

		// check for multiple live entries
		$rs = $this->listingdatacleaningmodelObj->getListingExamMapData($listing_id, array('live'));
		
		if(count($rs) > 0 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("status"=>'live', "listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    //return;
		    // #Done : clean the data
		    $this->listingdatacleaningmodelObj->clearListingExamMapDataForMultipleStatus($listing_id, 'live',$this->live_version);
		}
				
		// check for multiple live entries
		$rs = $this->listingdatacleaningmodelObj->getListingExamMapData($listing_id, array('draft'));
		
		if(count($rs) > 0 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("status"=>'draft', "listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    //return;
		    // #Done : clean the data
		    $this->listingdatacleaningmodelObj->clearListingExamMapDataForMultipleStatus($listing_id, 'draft',$this->draft_version);
		}
		
		// check if draft version is less than live version
		$rs = $this->listingdatacleaningmodelObj->getListingExamMapLiveDraftPositionIssue($listing_id);
		if(count($rs) != 0 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("message_type"=>2,"listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    //return;
		    // #Done : clean the data
		    $this->listingdatacleaningmodelObj->clearListingExamMapForLiveDraftPositionIssue($listing_id, $this->live_version);
		}
		
		$rs = $this->listingdatacleaningmodelObj->checkAndCleanVersionInconsistency($tablename, $listing_id, $this->draft_version, $this->live_version, 'typeId');
		
	}

	// Clean the inconsistency in  course_location_attribute table
	private function _cleanCourseLocationAttributeData($listing_id)
	{
		// return if inconsistency for the course is already found somewhere
		if($this->inconsistencyFoundFlag) return;
		
		$tablename = "course_location_attribute";
		$this->_startingMessage($tablename);

		// check for multiple live entries
		$rs = $this->listingdatacleaningmodelObj->getCourseLocationAttributeData($listing_id, array('live'));
		
		if(count($rs) > 0 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("status"=>'live', "listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    //return;
		    // #Done : clean the data
		    $this->listingdatacleaningmodelObj->clearCourseLocationAttributeDataForMultipleStatus($listing_id, 'live',$this->live_version);
		}

		// check for multiple live entries
		$rs = $this->listingdatacleaningmodelObj->getCourseLocationAttributeData($listing_id, array('draft'));
		if(count($rs) > 0 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("status"=>'draft', "listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    //return;
		    // #Done : clean the data
		    $this->listingdatacleaningmodelObj->clearCourseLocationAttributeDataForMultipleStatus($listing_id, 'draft',$this->draft_version);
		}

		// check if draft version is less than live version
		$rs = $this->listingdatacleaningmodelObj->getCourseLocationAttributeLiveDraftPositionIssue($listing_id);
		if(count($rs) != 0 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("message_type"=>2,"listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    //return;
		    // #Done : clean the data
		    $this->listingdatacleaningmodelObj->clearCourseLocationAttributeForLiveDraftPositionIssue($listing_id, $this->live_version);
		}
		
		$rs = $this->listingdatacleaningmodelObj->checkAndCleanVersionInconsistency($tablename, $listing_id, $this->draft_version, $this->live_version, 'course_id');
	}
	
	// Clean the inconsistency in listing_contact_details table
	private function _cleanListingContactDetailsData($listing_id)
	{
		// return if inconsistency for the course is already found somewhere
		if($this->inconsistencyFoundFlag) return;
		
		$tablename = "listing_contact_details";
		$this->_startingMessage($tablename);

		// check for multiple live entries
		$rs = $this->listingdatacleaningmodelObj->getListingContactDetailsData($listing_id, array('live'));
		
		if(count($rs) > 0 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("status"=>'live', "listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    //return;
		    // #Done : clean the data
		    $this->listingdatacleaningmodelObj->clearListingContactDetailsDataForMultipleStatus($listing_id, 'live',$this->live_version);
		}
		
		// check for multiple live entries
		$rs = $this->listingdatacleaningmodelObj->getListingContactDetailsData($listing_id, array('draft'));
		
		if(count($rs) > 0 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("status"=>'draft', "listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    //return;
		    // #Done : clean the data
		    $this->listingdatacleaningmodelObj->clearListingContactDetailsDataForMultipleStatus($listing_id, 'draft',$this->draft_version);
		}

		// check if draft version is less than live version
		$rs = $this->listingdatacleaningmodelObj->getListingContactDetailsLiveDraftPositionIssue($listing_id);

		if(count($rs) != 0 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("message_type"=>2,"listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    //return;
		    // #Done : clean the data
		    $this->listingdatacleaningmodelObj->clearListingContactDetailsForLiveDraftPositionIssue($listing_id, $this->live_version);
		}
		
		$rs = $this->listingdatacleaningmodelObj->checkAndCleanVersionInconsistency($tablename, $listing_id, $this->draft_version, $this->live_version, 'listing_type_id');
	}
	
	// Clean the inconsistency in listing_contact_details table
	// Multiple draft and live/draft positioning issue need not to be checked as no draft entry is present in this table
	private function _cleanListingCategoryTableData($listing_id)
	{
		// return if inconsistency for the course is already found somewhere
		if($this->inconsistencyFoundFlag) return;
		
		$tablename = "listing_category_table";
		$this->_startingMessage($tablename);

		// check for multiple live entries
		$rs = $this->listingdatacleaningmodelObj->getListingCategoryTableData($listing_id, array('live'));
		
		if(count($rs) > 0 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("status"=>'live', "listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    //return;
		    // #Done : clean the data
		    $this->listingdatacleaningmodelObj->clearListingCategoryTableDataForMultipleStatus($listing_id, 'live',$this->live_version);
		}
		
		$rs = $this->listingdatacleaningmodelObj->checkAndCleanVersionInconsistency($tablename, $listing_id, $this->draft_version, $this->live_version, 'listing_type_id');
		
	}
	
	// Get the latest versions of the course
	private function _cleanListingsMainData($listing_id)
	{
		$tablename = "listings_main";
		$this->_startingMessage($tablename);

		// check if data exists for this course or not
		$rs = $this->listingdatacleaningmodelObj->getCourseDetailsData($listing_id, array('draft','live'));
		if(count($rs) <= 0 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("message_type"=>3, "status"=>'live', "listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    //return;
		    // #Pending : clean the data
		    //_p("Exiting Cannot proceed....");
		    if(!PREVENT_EXIT_POINTS)
		    {
			return -1;
		    }

		}
		
		// check for multiple live entries
		$rs = $this->listingdatacleaningmodelObj->getListingsMainData($listing_id, array('live'));
		if(count($rs) > 1 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("status"=>'live', "listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    //return;
		    $this->_clearListingsMainDataForMultipleStatus($listing_id, 'live');
		    //_p("listings_main data cleaned. Please Run it again...");
		    return 2;
		    // _p("Exiting Cannot proceed....");
		    // if(!PREVENT_EXIT_POINTS)
		    // die();
		}
		$this->live_version = intVal($rs[0]['version']);

		// check for multiple draft entries
		$rs = $this->listingdatacleaningmodelObj->getListingsMainData($listing_id, array('draft'));
		if(count($rs) > 1 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("status"=>'draft', "listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    //return;
		    $this->_clearListingsMainDataForMultipleStatus($listing_id, 'draft');
		    //_p("listings_main data cleaned. Please Run it again...");
		    return 2;
		    
		        // _p("Exiting Cannot proceed....");
		        // if(!PREVENT_EXIT_POINTS)
		    	// die();
		}
		$this->draft_version = intVal($rs[0]['version']);
		
		// check if draft version is less than live version
		$rs = $this->listingdatacleaningmodelObj->checkListingsMainLiveDraftPositionIssue($listing_id);
		if(count($rs) != 0 || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("message_type"=>2,"listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		     //return;
		     $this->listingdatacleaningmodelObj->clearListingMainsDataForLiveDraftPositionIssue($listing_id);
		     //_p("listings_main data cleaned. Please Run it again...");
		     return 2;
		        //_p("Exiting Cannot proceed....");
		        //if(!PREVENT_EXIT_POINTS)
		     	//die();
		}

		// check if live version is 0
		$flag = $this->listingdatacleaningmodelObj->checkIfLiveVersionIfIncorrect($listing_id);
		if($flag || SHOW_ALL_ERRORS)
		{
		    $this->_showErrorMessage(array("message_type"=>7,"listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		     //return;
		     $this->listingdatacleaningmodelObj->clearListingMainsDataForLiveVersionInconsistency($listing_id);
		     //_p("listings_main data cleaned. Please Run it again...");
		     return 2;
		        //_p("Exiting Cannot proceed....");
		        //if(!PREVENT_EXIT_POINTS)
		     	//die();
		}

		_p("<b style='color: blue;'>Live Version of listings_main = ".$this->live_version."</b>");
		_p("<b style='color: blue;'>Draft Version of listings_main = ".$this->draft_version."</b>");
	}

	// Function to show different types of error message
	private function _showErrorMessage($errMsgData)
	{
		//		$error_code = ($errMsgData['message_type'] === NULL || empty($errMsgData['message_type'])) ? 1 : $errMsgData['message_type'];
		//
		//$errorMessage = array( 1 => "Multiple entries of ",
		//		       2 => "Draft version is less than Live version",
		//		       3 => "Data doesn't exists for this course",
		//		       4 => "All entries are of History status",
		//		       5 => "Doesn't have exactly one entry of version=1");
		//
		//$errorStatus = "";
		//if($error_code == 1)
		//	$errorStatus = $errMsgData['status'];
		//	
        //$this->resultArr[$errMsgData['listing_id']] = array(
        //                                                            'table_name' => $errMsgData['table_name'],
        //                                                            'message_type' => $error_code,
        //                                                            'error_msg' => $errorMessage[$error_code].$errorStatus);
		//$this->inconsistencyFoundFlag = 1;
		
		    if($errMsgData['message_type'] == 2)
		    {
			_p("<p class='error'>Error : Draft Entry has lesser version number than Live Entry in ".$errMsgData['table_name']." for Course : ".$errMsgData['listing_id']."</p>");
		    }
		    else if($errMsgData['message_type'] == 3)
		    {
			_p("<p class='error'>Error : There is no draft, live entry for this course in ".$errMsgData['table_name']." for Course : ".$errMsgData['listing_id']."</p>");
		    }
		    else if($errMsgData['message_type'] == 7)
		    {
			_p("<p class='error'>Error : Live Version Is Incorrect in ".$errMsgData['table_name']." for Course : ".$errMsgData['listing_id']."</p>");
		    }
		    else
		    {
			_p("<p class='error'>Error : Multiple entries for status = ".$errMsgData['status']." of the course ".$errMsgData['listing_id']." in ".$errMsgData['table_name']."</p>");
		    }
	}
	
	function _startingMessage($msg)
	{
	    //_p("<p class='start'>- Starting Checking for ".$msg."</p>");
	}
	
	
	/*****************************
	* START : DATA FIXING SECTION
	******************************/
	
	function _clearCourseDetailsDataForMultipleStatus($listing_id, $status)
	{
	    $latest_version = 0;
	    if($status == 'live')
		$latest_version = $this->live_version ;
	    else
		$latest_version = $this->draft_version ;
		
	    $this->listingdatacleaningmodelObj->clearCourseDetailsDataForMultipleStatus($listing_id, $status, $latest_version);
	}
	
	function _clearListingsMainDataForMultipleStatus($listing_id, $status)
	{
	    $this->listingdatacleaningmodelObj->clearListingsMainDataForMultipleStatus($listing_id, $status);
	}
	/*****************************
	* END  : DATA FIXING SECTION
	******************************/
	
	function _checkForAllHistoryEntriesOfListing($listing_id)
	{
		// return if inconsistency for the course is already found somewhere
		if($this->inconsistencyFoundFlag) return;

		$tablename = "listings_main";
		// check for multiple live entries
		$rs = $this->listingdatacleaningmodelObj->getAllStatusOfListingMain($listing_id, 'course');
		
		if(count($rs) == 1 && $rs[0]['status'] == 'history')
		{
		    $this->_showErrorMessage(array("message_type"=>4, "listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    return;
		}
		
		$tablename = "course_details";
		// check for multiple live entries
		$rs = $this->listingdatacleaningmodelObj->getAllStatusOfCourseDetails($listing_id);
		
		if(count($rs) == 1 && $rs[0]['status'] == 'history')
		{
		    $this->_showErrorMessage(array("message_type"=>4, "listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    return;
		}
		
	}
	
	function _checkForVersionOfListing($listing_id)
	{
		// return if inconsistency for the course is already found somewhere
		if($this->inconsistencyFoundFlag) return;

		$tablename = "listings_main";
		// check for multiple live entries
		$rs = $this->listingdatacleaningmodelObj->getAllVersionCountInListingMain($listing_id, 'course');
		
		if(count($rs) == 1 && $rs[0]['versionCount'] != 1)
		{
		    $this->_showErrorMessage(array("message_type"=>5, "listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    return;
		}
		
		$tablename = "course_details";
		// check for multiple live entries
		$rs = $this->listingdatacleaningmodelObj->getAllVersionCountInCourseDetails($listing_id);
		if(count($rs) == 1 && $rs[0]['versionCount'] != 1)
		{
		    $this->_showErrorMessage(array("message_type"=>5, "listing_id"=>$listing_id, "table_name"=>$tablename, 'count'=>count($rs)));
		    return;
		}
	}
	
	function generateExcelSheet()
	{
		$this->load->library('common/PHPExcel');
		$objPHPExcel 	= new PHPExcel();
		$rowCount 	= 1;
		$column 	= 'A';
		
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcelActiveSheet = $objPHPExcel->getActiveSheet();
		$objPHPExcelActiveSheet->setTitle("Corrupt Courses");
		
		// header array having heading text and column width
		$headerArray = array(array("Course Id"			,20),
				     array("Institute Id"		,20),
				     array("Table Name"			,30),
				     array("Error"			,50)
				     );
		
		// prepare the column headers of the excel file
		for ($i = 0; $i < count($headerArray); $i++) {
			$objPHPExcelActiveSheet->setCellValue($column.$rowCount, $headerArray[$i][0]);
			$objPHPExcelActiveSheet->getColumnDimension($column)->setWidth($headerArray[$i][1]);
			$objPHPExcelActiveSheet->getStyle($column.$rowCount)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'EEEEEE') )));
			$objPHPExcelActiveSheet->getStyle($column.$rowCount)->getFont()->setBold(true);
			$objPHPExcelActiveSheet->getStyle($column.$rowCount)->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
			$objPHPExcelActiveSheet->getRowDimension($rowCount)->setRowHeight(20);
			$column++;
		}
		
		//$this->resultArr[$errMsgData['listing_id']] = array( 'table_name' => $errMsgData['table_name'], 'message_type' => $errMsgData['message_type'] );
		$fromArray = array();
		foreach ($this->resultArr as $courseId=>$row)
		{
			
			$rowCount++;
			
			// prepare the data for the excel
			$fromArray[] = array($courseId,
					     $this->instituteIds[$courseId],
					     $row['table_name'],
					     $row['error_msg']);

			// for log purpose only
			if($key%500 == 0)
				error_log("CORRUPT_DATA : Creating row ".$key);
		}
		
		// print the data array in the excel sheet
		$objPHPExcelActiveSheet->fromArray($fromArray, null, 'A2');
		error_log("CORRUPT_DATA : Data Rows inserted in excel ");
		$column = chr(ord($column)-1);;
		error_log("CORRUPT_DATA : Total Results  ".count($this->resultArr)."   "."A1".":".($column).$rowCount);
		$objPHPExcel->getActiveSheet()->getStyle("A1".":".($column).$rowCount)->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));

		// save the excel files
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		error_log("CORRUPT_DATA : PHPExcel_Writer_Excel2007 object created");
		$reportName 	= "Corrupt_data_".date("dMY").".xlsx";
		$path 		= "mediadata/reports/";
		$objWriter->save($path.$reportName);
		error_log("CORRUPT_DATA : ".$reportName." Excel file saved");

	}
	
	function test()
	{
		$this->_init();
		$this->_checkForVersionOfListing(29108);
	}
	
}
