<?php

require_once dirname(__FILE__).'/PostingModelAbstract.php';

class CoursePostModel extends PostingModelAbstract
{
	private $subsciptionConsumer;
	
    function __construct($subsciptionConsumer)
	{
		parent::__construct();
		$this->subsciptionConsumer = $subsciptionConsumer;
    }
	
	private function _updateOwnerInfoForRelatedListings($courseId,$ownerId)
	{
		$courseIds        = array();
		$instituteBucket  = array();
		$universityBucket = array();
		$instituteId      = "";

		//get course institute Id
		$sql ="SELECT sc.primary_id FROM listings_main lm JOIN shiksha_courses sc 
			  ON lm.listing_type_id = sc.course_id and lm.listing_type = 'course' 
			  WHERE sc.course_id = ? AND lm.status = 'live' AND sc.status = 'live'"; 
		$result      = $this->dbHandle->query($sql,array($courseId))->row_array();		
		
		$instituteId = $result['primary_id'];
		if($instituteId){
			$instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');
			$result             = $instituteDetailLib->getAllCoursesForInstitutes($instituteId,'full');
			$courseIds          = $result['courseIds'];
			$instiUniIds        = $result['type'];
		}
		
		foreach ($instiUniIds as $ids => $type) {
			if($type == 'university'){
				$universityBucket[] = $ids;
			}else if($type == 'institute'){
				$instituteBucket[] = $ids;
			}
		}

    	//if ids of listings are not found do nothing
    	if(empty($courseIds) || $ownerId == "" || empty($ownerId)){
    		return true;
    	}
    	if(!empty($instituteBucket)){
	        // update ownership info in listings_main table for institute
	        $sqlInstitute =  "UPDATE listings_main ".
					"SET username = ? ".
					"WHERE listing_type_id in (?) ".
					"AND status in ('draft','live') ".
					"AND listing_type = 'institute'";
	    	$this->dbHandle->query($sqlInstitute,array($ownerId, $instituteBucket));    		
    	}

    	if(!empty($universityBucket)){
	    	// update ownership info in listings_main table for university
	        $sqlUniversity =  "UPDATE listings_main ".
					"SET username = ? ".
					"WHERE listing_type_id in (?) ".
					"AND status in ('draft','live') ".
					"AND listing_type = 'university_national'";
			//error_log("university".print_r($sqlUniversity,true));					
	    	$this->dbHandle->query($sqlUniversity,array($ownerId, $universityBucket));
    	}
		
		if(!empty($courseIds)){
	    	// update ownership info in listings_main table for related listings courses
	    	$sqlCourses =  "UPDATE listings_main ".
					"SET username = ? ".
					"WHERE listing_type_id in (?) ".
					"AND status in ('draft','live') ".
					"AND listing_type = 'course'";
	    	//error_log("course".print_r($sqlCourses,true));	
	    	$this->dbHandle->query($sqlCourses,array($ownerId, $courseIds));			
		}

    	return $courseIds;
    }
	
	private function _getSEOURL($data,$courseId)
	{
		// determine the dominant subcat of the Course(required for engineering subcat - LF -1507)
		$courseLevelDetails 	= array("level" => $data['courseLevel'], "level1" => $data['courseLevel_1'] , "level2" => $data['courseLevel_2']);
		$national_course_lib 	= $this->load->library('listing/NationalCourseLib');
		$subcatArr 		= array_filter($data['courseSubcatMap']);
		$dominantSubCat 	= $national_course_lib->getDominantSubCategoryForCourse("", $subcatArr, NULL, $courseLevelDetails);
		$dominantSubCatKey 	= array_search($dominantSubCat['dominant'], $data['courseSubcatMap']);
		$dominantCategory 	= $data['courseCategoryMap'][$dominantSubCatKey];

		//create directory structure url, LF-3253/SEO-73
		$this->load->builder('CategoryBuilder','categoryList');
		$categoryBuilder = new CategoryBuilder;
		$this->categoryRepository = $categoryBuilder->getCategoryRepository();
		$dominantSubCatObj = $this->categoryRepository->find($dominantSubCat['dominant']);
		$seoUrlCategoryName = $dominantSubCatObj->getSeoUrlDirectoryName();
		if(!empty($seoUrlCategoryName)) {
			$optionalArgs = array();
            $optionalArgs['institute'] = $data['institute_name'];
            $optionalArgs['dominantSubcat'] = $seoUrlCategoryName;
			$url = getSeoUrl($courseId, 'course', $data['courseTitle'], $optionalArgs, 'old');
			return $url;
		}

		$hasManagementSubcatFlag = 0;
		$isEngineeringCourse	 = 0;
		$prepareEnggURLFlag	 = 0;
		if($dominantSubCat["allSubcatCountEqualFlag"])
		{
			foreach($subcatArr as $subcatId)
			{
				$catIdMappingKey = array_search($subcatId, $data['courseSubcatMap']);
				$catIdMapping 	 = $data['courseCategoryMap'][$catIdMappingKey];

				if($catIdMapping == 3)
					$hasManagementSubcatFlag = 1;
				else if($catIdMapping == 2)
					$isEngineeringCourse = 1;
			}
		}
		if(($dominantCategory == 2 || $isEngineeringCourse) && !$hasManagementSubcatFlag)
		{
			$prepareEnggURLFlag = 1;
		}
		
		$sql = "SELECT institute_name,abbreviation
				FROM institute
				WHERE institute_id = ?
				ORDER BY id DESC LIMIT 1";
				
		$query = $this->dbHandle->query($sql,array($data['institute_id']));

		$instituteName = '';
		$cityName ='';
		$localityName = '';
		$countryName = '';
		$acronym = '';
		
		foreach ($query->result_array() as $row){               
			$instituteName = $row['institute_name'];
			$acronym = $row['abbreviation'];
		}
								
		$sql = "SELECT locality_name, cct.city_name as city_name, ct.name as country
				FROM institute_location_table ilt,countryCityTable cct,countryTable ct
				where cct.city_id = ilt.city_id
				AND cct.countryId = ct.countryId
				AND ilt.institute_id = ?
				AND ilt.institute_location_id = ?
				ORDER BY id DESC LIMIT 1";
		
		$query = $this->dbHandle->query($sql,array($data['institute_id'],$data['head_ofc_location_id']));
		foreach ($query->result_array() as $row){
			$cityName = $row['city_name'];
			$localityName = $row['locality_name'];
			$countryName = $row['country'];
		}	
               									
		if(empty($data['listing_seo_url'])) {
			
			// if the dominant category of the course is engineering then get the engineering url format for that course
			if($prepareEnggURLFlag)
			{
				$url = $national_course_lib->getEnggCourseURL($courseId, $data['courseTitle'], $acronym, $instituteName, $cityName);
				return $url;
			}
			$courseTitle = seo_url($data['courseTitle'], "-", 30);
			$instituteTitle = seo_url($instituteName, "-", 30);
			$locations = explode(",",$data['institute_location_ids']);
							
			//While adding a single-location listing (institute/course), this will work as it is and we will have the location in the listing URL.
			if(count($locations) == 1) {
				if(!empty($localityName)) {
					
					$localityName = seo_url($localityName, "-", 10);
					$cityName = seo_url($cityName,"-",10);
					$countryName = seo_url($countryName, "-", 10);
					$acronym = seo_url($acronym, "-", 10);
					
					if(isset($acronym) && $acronym!=''){
						$url = SHIKSHA_HOME_URL."/".$courseTitle."-course-in-".$localityName."-".$cityName."-".$acronym."-".$instituteTitle."-course-information-"."listingcourse-".$courseId;
					}
					else {
						$url = SHIKSHA_HOME_URL."/".$courseTitle."-course-in-".$localityName."-".$cityName."-".$instituteTitle."-course-information-"."listingcourse-".$courseId;
					}
				}
				else {
					
					$cityName = seo_url($cityName,"-",10);
					$acronym = seo_url($acronym, "-", 10);
					
					if(isset($acronym) && $acronym!='') {
						$url = SHIKSHA_HOME_URL."/".$courseTitle."-course-in-".$cityName."-".$acronym."-".$instituteTitle."-course-information-"."listingcourse-".$courseId;
					}
					else {
						$url = SHIKSHA_HOME_URL."/".$courseTitle."-course-in-".$cityName."-".$instituteTitle."-course-information-"."listingcourse-".$courseId;
					}
				}
			}
			else {
				//While adding a Multi-location listing (institute/course), we will not have the location in the listing URL.
				if(isset($acronym) && $acronym!=''){
					$url = SHIKSHA_HOME_URL."/".$courseTitle."-".$acronym."-".$instituteTitle."-course-information-"."listingcourse-".$courseId;
				}
				else {
					$url = SHIKSHA_HOME_URL."/".$courseTitle."-".$instituteTitle."-course-information-"."listingcourse-".$courseId;
				}
			}
		}
		else {
			if(!$prepareEnggURLFlag)
				$url = SHIKSHA_HOME_URL."/".seo_url($data['listing_seo_url'],"-",30)."-course-information-listingcourse-".$courseId;
			else
				$url = SHIKSHA_HOME_URL."/".seo_url($data['listing_seo_url'],"-",30)."-course-information-listingcourse-".$courseId;
		}	
		
		return $url;
	}
	
	private function _getNewCourseId()
	{
		return Modules::run('common/IDGenerator/generateId','course');
	}
	
	private function _getNewCourseOrder($instituteId)
	{
		$sql = "SELECT MAX(course_order) AS course_order FROM course_details WHERE institute_id = ? ";
		$query = $this->dbHandle->query($sql,array($instituteId));
		$row = $query->row_array();
		$maxCourseOrder = $row['course_order'];
		$newCourseOrder = $maxCourseOrder+1;
		return $newCourseOrder;
	}
	
	private function _getCourseData($courseId)
	{
		$sql = "SELECT * FROM course_details WHERE course_id = ? ORDER BY id DESC LIMIT 1";
		$query = $this->dbHandle->query($sql,array($courseId));
		$row = $query->row_array();
		return $row;
	}
	
	public function isDuplicateCoursePosting($data)
	{
		$this->initiateModel('write');
		
		$sql = "SELECT course_id, courseTitle
				FROM course_details
				WHERE institute_id = ?
				AND courseTitle = ?
				AND course_type = ? AND status IN ('live', 'draft')";
				
		$query = $this->dbHandle->query($sql,array($data['institute_id'],$data['courseTitle'],$data['courseType']));
		if($query->num_rows() > 0) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	private function _getListingData($courseId)
	{
		$sql = "SELECT *
				FROM listings_main
				WHERE listing_type = 'course'
				AND listing_type_id = ? AND status IN ('live', 'draft')
				ORDER BY listing_id DESC LIMIT 1";
				
		$query = $this->dbHandle->query($sql,array($courseId));
		$row = $query->row_array();
		return $row;
	}


	function courseUpgradeDowngrade($subscriptionClient,$data){
        $this->initiateModel('write');
		$this->dbHandle->trans_start();
        $this->load->library('sums_product_client');

        $objSumsProduct      =  new Sums_Product_client();
        $SubscriptionDetails = $objSumsProduct->getAllPseudoSubscriptionsForUser(1,array('userId'=>$data['client_id']));
        
	     /* first make new entries for course (live (and draft if available)) with new values in listings_main
	     * for this first get last entries from table for that course
	     */
        $sql    = "select * from listings_main where listing_type = 'course' and listing_type_id = ? and status in ('draft','live')";
        $result = $this->dbHandle->query($sql,array($data['course_id']))->result_array();

        foreach($result as $row){ 
            $insertData = array();
            foreach($row as $k=>$rowElement){
                switch($k){
                    case 'listing_id': //this will generate itself, however we need to keep this to make this history later
                                       $listingIdForHistory = $rowElement;
                                       break;
                    case 'approve_date':
                                        $insertData[$k]=date('Y-m-d H:i:s');
                                        break;
                    case 'last_modify_date':
                                        $insertData[$k]=date('Y-m-d H:i:s'); //this has to be implemented only for national courses
                                        break;
                    case 'expiry_date':
                                       // this will come from new subscription
                                        $insertData[$k]=$SubscriptionDetails[$data['subscription']]['SubscriptionEndDate'];
                                        break;
                    case 'username':    // new client assigned to course
                                        $insertData[$k]=$data['client_id'];
                                        break;
                    case 'pack_type':   // this will come from new subscription
                                        $insertData[$k]=$SubscriptionDetails[$data['subscription']]['BaseProductId'];
                                        break;
                    case 'subscriptionId':
                                        $insertData[$k]=$data['subscription'];
                                        break;
                    default:    //default case is to save previously saved data as it is
                                    $insertData[$k]=$rowElement;
                                    break;
                    }   
                }
            // update last entries and ...
            $updateSql = "update listings_main set status = 'history',version = 0 where listing_id = ?";
            $this->dbHandle->query($updateSql, array($listingIdForHistory));
            
            //...save the rows
            $this->dbHandle->insert('listings_main', $insertData);
            
            // for logging purpose
			$insertedQuery         = $this->dbHandle->last_query();
			$lastInsertedListingId = $this->dbHandle->insert_id();
			$sqlToGetLastInserted       = "select * from listings_main where listing_type = 'course' and listing_id = ?";
			$lastInsertedResult    = $this->dbHandle->query($sqlToGetLastInserted, array($lastInsertedListingId))->row_array();
        
            /**
             * loggging the details of course for debugging purpose
             */
         	$loggingMessage = "Course Id                          :".$data['course_id'].PHP_EOL
		            		  ."Course Id Status                  :".$row['status'].PHP_EOL
							  ."Before Upgrade Subscription Id    :".$row['subscriptionId'].PHP_EOL
							  ."After Upgrade Subscription Id     :".$data['subscription'].PHP_EOL
							  ."Before Upgrade Username           :".$row['username'].PHP_EOL
							  ."After Upgrade  Username           :".$data['client_id'].PHP_EOL
							  ."Before Upgrade PackType           :".$row['pack_type'].PHP_EOL
							  ."After Upgrade  PackType           :".$SubscriptionDetails[$data['subscription']]['BaseProductId'].PHP_EOL
							  ."Before Upgrade Last Modified Date :".$row['last_modify_date'].PHP_EOL
							  ."After Upgrade  Last Modified Date :".date('Y-m-d H:i:s').PHP_EOL
							  ."Before Upgrade  Expiry Date       :".$row['expiry_date'].PHP_EOL
							  ."After Upgrade  Expiry Date        :".$SubscriptionDetails[$data['subscription']]['SubscriptionEndDate'].PHP_EOL
							  ."SQL QUERY                         :".PHP_EOL.$insertedQuery.PHP_EOL
							  ."Last Inserted Listings Main Array :".PHP_EOL.print_r($lastInsertedResult,true).PHP_EOL.PHP_EOL;
			$currentDate = date('Y-m-d');
			error_log($loggingMessage,3,"/tmp/courseUpgradeDowngradeLog$currentDate.txt"); 
        } //course's entry


        /****** consume a unit of subscription ******/
        // first pseudo....
        $subscriptionClient->consumePseudoSubscription(1,$data['subscription'],'-1',$data['client_id'],$data['editedBy'],'-1',$data['course_id'],'course','-1','-1');
        //....then real
        $subscriptionClient->consumeSubscription(1,$data['subscription'],'-1',$data['client_id'],$data['editedBy'],'-1',$data['course_id'],'course','-1','-1');
        
        $SubscriptionDetailsAfterConsume = $objSumsProduct->getAllPseudoSubscriptionsForUser(1,array('userId'=>$data['client_id']));

        $Subscriptionlog = "Before Upgrade Client Subscription Details :".PHP_EOL.print_r($SubscriptionDetails,true).PHP_EOL
						  ."After Upgrade Client Subscription Details  :".PHP_EOL.print_r($SubscriptionDetailsAfterConsume,true).PHP_EOL
						  ."===================================================================================================".PHP_EOL.PHP_EOL;

		error_log($Subscriptionlog,3,"/tmp/courseUpgradeDowngradeLog$currentDate.txt");

        $courseIds = $this->_updateOwnerInfoForRelatedListings($data['course_id'],$data['client_id']);

        //$this->callToUpdatePacktypeInCategoryPageData($data['course_id'],$SubscriptionDetails[$data['subscription']]['BaseProductId'], true);

        // call to track course upgrade and downgrade
        $this->load->model('listingPosting/abroadcmsmodel');
        $this->abroadcmsmodel = new abroadcmsmodel();
        $data['addedFrom'] = 'updowninterface';
        $returnVal = $this->abroadcmsmodel->_subscriptionHistoricalDetails($SubscriptionDetails,$data,'national');
        if(!$returnVal){
        	throw new Exception('Insertion for tracking subscription info failed');die;
        }

        // comments
	 	$commentForTracking = "New Course Upgrade Interface  : Course Listing upgraded with pack type ".$SubscriptionDetails[$data['subscription']]['BaseProductId']." for client : ".$data['client_id']." by user ".$data['editedBy'];

        /**
		 * Save edit comments
		 */
		$commentsData = array(
			'userId'     => $data['editedBy'],
			'listingId'  => $data['course_id'],
			'tabUpdated' => 'listingUpgrade',
			'comments'   => $commentForTracking
		);
		$this->dbHandle->insert('listingCMSUserTracking',$commentsData);
		
		$this->dbHandle->trans_complete();
		if ($this->dbHandle->trans_status() === FALSE) {
		    throw new Exception('Transaction Failed');
	    }
	    if(!empty($courseIds)){
	    	return $courseIds;	
	    }else{
	    	return false;
	    }
    }

    private function callToUpdatePacktypeInCategoryPageData($courseId,$packtype){
    	$updateSql = "update categoryPageData set pack_type = ?
			  where course_id =  ?
			  and status = 'live'";
	    $this->dbHandle->query($updateSql,array($packtype,$courseId));
    }
}
