<?php
	class CollegeShortlistLibrary {
		private $mailingList = array("abhinav.pandey@shiksha.com","singh.satyam@shiksha.com");
		private $emptySpecializationCourseIds = array();
		function __construct(){
			$this->CI = &get_instance();
			$this->CI->load->library('indexer/SolrServerLib');
			$this->solrServerLib = new SolrServerLib;

			$this->CI->load->config('Shortlist/CollegeShortlistConfig');
			$this->collegeshortlistmodel = $this->CI->load->model("Shortlist/CollegeShortlistModel");

            $this->examCache = $this->CI->load->library('examPages/cache/ExamCache');
            $this->CI->load->helper(array('url'));
		}

		function indexCollegeShortlistPredictorDataToSolrByExamId($id){
			ini_set("memory_limit", "30000M");
			// load dependencies	
			$this->CI->load->library('alerts_client');
			$this->alertClient  = new Alerts_client();
			// send script start mail
			$scriptStartTime   = time();
			$this->sendCronAlert("Started : ".__METHOD__, "");

			$this->emptySpecializationCourseIds = array();

			if(!empty($id) && (int)$id > 0) {
				$this->indexCollegeShortlistPredictorDataToSolr($id);
			}else{
				$examIds = $this->collegeshortlistmodel->getSingleFieldsFromCollegeShortlist('exam_id');
				foreach ($examIds as $key => $examId) {
					$this->indexCollegeShortlistPredictorDataToSolr($examId);
				}
			}

			error_log("=== Ended====");
	        $scriptEndTime = time();
			$timeTaken     = ($scriptEndTime - $scriptStartTime) / 60;

			$text 		   = __METHOD__." Script Ended Successfully.<br/>Time Taken : ".$timeTaken." mins<br/>Contact Person: Nithish Reddy";
			$text .= "<br/> Empty SpecializationCourseIds = ".implode('.', $this->emptySpecializationCourseIds);
			$this->sendCronAlert("Ended : ".__METHOD__, $text,$mailingList);
		}


	function indexCollegeShortlistPredictorDataToSolr($examId){
    	$shortlistDbata = $this->collegeshortlistmodel->getCollegeShortlistForSolrIndexing($examId);
    	$examBaseInfo = $this->collegeshortlistmodel->getExamBaseInformation($examId);
    	$categoryInfo = $this->collegeshortlistmodel->getCategoryNameId($examId);
    	$categoryNameIdMap = array();
    	foreach ($categoryInfo as $key => $value) {
    	 	$categoryNameIdMap[$value['id']] = $value['full_name'];
    	 } 

    	/*$shortlistDbata1[] = $shortlistDbata[0];
    	$shortlistDbata = $shortlistDbata1;*/
    	if(empty($shortlistDbata))
    		return;

        $courseIds = array();
        $instituteIds = array();
    	foreach ($shortlistDbata as $shortlistkey => $shortlistvalue) {
    		if( !empty($shortlistvalue['course_id']) && !in_array($shortlistvalue['course_id'], $courseIds)){
    				$courseIds[] = $shortlistvalue['course_id'];
    		}
    	}

    	if(empty($courseIds))
    		return;

    	$chunkSize = 50;
        $batchSize = ceil(count($courseIds)/$chunkSize);
        $courseIdsChunk = array();
        for($i = 0; $i < $batchSize; $i++) {
        	$courseIdsChunk[] = array_slice($courseIds, $i*$chunkSize,$chunkSize);
        }
        $courseData = array();

        $courseRelatedData = $this->getCourseData($courseIdsChunk);
        $courseData = $courseRelatedData['courseData'];
        $this->emptySpecializationCourseIds = $courseRelatedData['emptySpecializationCourseIds'];
        
        $examBasicResult = array();
        foreach ($examBaseInfo as $examkey => $examvalue) {
        	$examBasicResult[$examvalue['exam_id']] = $examvalue;
        }

        $solrIndexData = array();
        $solrChunkSize = 500;
        $incrementCounter = 1;
        $microTime = explode(" ",microtime());
        $batchCount = 1;
        $counter = 0;
        $deleteDocumentsIndex = "all";
        if(!empty($examId)){
        	$deleteDocumentsIndex = $examId;
        }

        $this->solrServerLib->deleteDocuments('collegeshortlist',$deleteDocumentsIndex);

        $examOrderConfig = $this->CI->config->item("examOrder");

        $examOrderConfigCount = count($examOrderConfig);
        $maxNumber = 1000;

        foreach ($shortlistDbata as $dbkey => $dbvalue) {
        	$courseSingleinfo = $courseData[$dbvalue['course_id']];
        	if(array_key_exists($dbvalue['exam_id'], $examOrderConfig)){
        		$examOrder = $examOrderConfig[$dbvalue['exam_id']];
        	}else{
        		$examOrder =  $examOrderConfigCount + 1;	
        	}
        	if(!empty($examBasicResult[$dbvalue['exam_id']]['exam_name'])){
        		$examOrderNameId = str_pad($examOrder, 4, "0", STR_PAD_LEFT).":".$examBasicResult[$dbvalue['exam_id']]['exam_name'].":".$dbvalue['exam_id'];
        	}
        	if($examBasicResult[$dbvalue['exam_id']]['exam_cutoff_type'] != "rank"){
                $cutOffScore = 10000 - $dbvalue['value'];
            }else {
        	    $cutOffScore = $dbvalue['value'];
            }
            $categoryName = $categoryNameIdMap[$dbvalue['category_id']];

            if(!empty($categoryName) && !empty($dbvalue['category_id'])){
            	$categoryNameIdMapping = str_pad($dbvalue['category_id'], 4, "0", STR_PAD_LEFT).":".$categoryName;
            }

            $solrIndexData[] = array(
        	   'unique_id' => $microTime[1]."_".$counter++,
        	   'examId' => (int)$dbvalue['exam_id'],
        	   'examName' => $examBasicResult[$dbvalue['exam_id']]['exam_name'],
    		   "examNameId" => $examBasicResult[$dbvalue['exam_id']]['exam_name'].":".$dbvalue['exam_id'] ,
    		   'examOrder' => $examOrder ,
    		   'examOrderNameId' => $examOrderNameId,
    		   'courseId' => (int)$dbvalue['course_id'],
    		   'instituteId' => (int)$courseSingleinfo['instituteId'],
    		   'specializationId' => (int)$courseSingleinfo['specializationId'],
    		   'specializationName' => $courseSingleinfo['specializationName'], 
    		   'specPopularityNameId' => str_pad($courseSingleinfo['specializationOrder'], 3, "0", STR_PAD_LEFT).":".$courseSingleinfo['specializationName'].":".$courseSingleinfo['specializationId'],
    		   'specializationOrder' => $courseSingleinfo['specializationOrder'], 
    		   'cityId' => (int)$courseSingleinfo['cityId'],
    		   'cityId1' => $courseSingleinfo['cityId1'], 
    		   'cityName' => $courseSingleinfo['cityName'], 
    		   "cityNameId" => $courseSingleinfo['cityName'].":".$courseSingleinfo['cityId']  ,
    		   'stateId' => (int)$courseSingleinfo['stateId'], 
    		   'stateName' => $courseSingleinfo['stateName'], 
    		   "stateNameId"  => $courseSingleinfo['stateName'].":".$courseSingleinfo['stateId'], 
    		   'ownership' => $courseSingleinfo['ownership'] ,
    		   'aggregateReview' => !empty($courseSingleinfo['aggregateReview']) ? $courseSingleinfo['aggregateReview'] : 0, 
    		   'reviewCount' => !empty($courseSingleinfo['reviewCount']) ? $courseSingleinfo['reviewCount'] : 0, 
    		   'fee' => (int)$courseSingleinfo['fee'],
    		   'gender' => $dbvalue['gender_category'],
    		   'rankType' => $dbvalue['rank_type'], 
    		   'round' => (int)$dbvalue['round'], 
    		   'cutoffType' => $examBasicResult[$dbvalue['exam_id']]['exam_cutoff_type'], 
    		   'cutoffScore' => $cutOffScore,
    		   'category' => $dbvalue['category_id'], 
               'subcategory' => $dbvalue['subcategory'], 
    		   'popularity' => $courseSingleinfo['popularity'],
    		   'popularityName' => $courseSingleinfo['popularityName'], 
    		   'popularCity' => $courseSingleinfo['popularCity'], 
    		   'popularState' => $courseSingleinfo['popularState'],
    		   "splexamOrderRoundRankType" => $courseSingleinfo['specializationOrder'].":".$examOrder.":".$dbvalue['round'].":".($dbvalue['rank_type'] == "allindia" ? "2" : "1"),
    		   "popularExamRoundRankType" => $courseSingleinfo['popularity'].":".($maxNumber - $examOrder).":".($maxNumber - $dbvalue['round']).":".($dbvalue['rank_type'] == "allindia" ? "1" : "2"), 
    		   "instituteUrl" => $courseSingleinfo['instituteUrl'], 
    		   'reivewUrl' => $courseSingleinfo['reivewUrl'],
               'remark' => $dbvalue['remarks'],
        	   'courseNameIdMap' => $courseSingleinfo['courseNameIdMap'],
        	   'offeredByInstituteNameIdMap' => $courseSingleinfo['offeredByInstituteNameIdMap'],
        	   'affiliatedUniversityId' => (int)$courseSingleinfo['affiliatedUniversityId'],
        	   'affiliatedUniversityNameIdMap' => $courseSingleinfo['affiliatedUniversityNameIdMap'],
        	   'hierarchyInstituteIds' => $courseSingleinfo['hierarchyInstituteIds'],
        	   'hierarchyInstituteNameIdMap' => $courseSingleinfo['hierarchyInstituteNameIdMap'],
        	   'categoryNameIdMapping' => $categoryNameIdMapping);

        	if($solrChunkSize == $incrementCounter){

        		$indexIds = array();
        		error_log("Solr indexing for college shortlist of examId : ".$examId." batch count ".$batchCount." started");
        		if(!empty($solrIndexData) && count($solrIndexData) > 0) {
		            $indexResponse = $this->solrServerLib->indexFinalData($solrIndexData,false,"collegeshortlist");
		            if($indexResponse[0] == 1) {
		                error_log("Solr indexing for college shortlist of examId : ".$examId." batch count ".$batchCount." success");
		            } else {
		                error_log("Solr indexing for college shortlist of examId : ".$examId." batch count ".$batchCount." failed");
		            }
		        }
		        $solrIndexData = array();
		        $batchCount++;
		        $incrementCounter = 0;
        	}
        	$incrementCounter++;
        }
        
        if(!empty($solrIndexData) && count($solrIndexData) > 0) {
        	error_log("Solr indexing for college shortlist of examId : ".$examId." batch count ".$batchCount." started");
            $indexResponse = $this->solrServerLib->indexFinalData($solrIndexData,false,"collegeshortlist");
            if($indexResponse[0] == 1) {
                error_log("Solr indexing for college shortlist of examId : ".$examId." batch count  ".$batchCount." success");
            } else {
                error_log("Solr indexing for college shortlist of examId : ".$examId." batch count ".$batchCount." failed");
            }
        }
    }

    function deleteSolrDocumentsForCollegeShortlistByExamId($id){
    	$solrDelLib = $this->CI->library('search/DeleteDocument/SolrDeleteDocument');
    	$solrDelLib->getCollegeShortlistDeleteXML($id);
    }

    function sendCronAlert($subject, $body, $emailIds){
		if(empty($emailIds))
			$emailIds = $this->mailingList;

		foreach($emailIds as $key=>$emailId)
			$this->alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emailId, $subject, $body, "html", '', 'n');
	}
		function getCourseData($courseIdsChunk){
			$this->CI->load->builder("nationalCourse/CourseBuilder");
	        $courseBuilder = new CourseBuilder();
	        $this->courseRepo = $courseBuilder->getCourseRepository();

	        $this->apiClient = $this->CI->load->library("common/apiservices/APICallerLib");
	    	$TEST_API_DOMAIN = '172.16.3.107:9004';
			$PROTOCOL   = (ENVIRONMENT != 'production') ? 'http://' : 'http://';
			$API_DOMAIN = (ENVIRONMENT != 'production') ? $TEST_API_DOMAIN : 'apis.shiksha.jsb9.net';
			$url = "/review/api/v1/info/getAggregateReviewsMultiple";
			$headers = array("Content-Type"=> "application/x-www-form-urlencoded");
			$specializationIds = array();
			$emptySpecializationCourseIds = array();


			$specializationOrder = $this->CI->config->item('specializationOrder');

			$this->CI->load->builder("listingBase/ListingBaseBuilder");
			$listingBaseBuilder = new ListingBaseBuilder();
			$specializationRepo  = $listingBaseBuilder->getSpecializationRepository();	

			$this->CI->load->builder("location/LocationBuilder");
			$locationBuilder = new LocationBuilder();
			$locationRepo = $locationBuilder->getLocationRepository();

			$courseDetailLib = $this->CI->load->library("nationalCourse/CourseDetailLib");

			$coursedetailmodel = $this->CI->load->model("nationalCourse/coursedetailmodel");
			
			$this->CI->load->builder("nationalInstitute/InstituteBuilder");
       	 	$instituteBuilder = new InstituteBuilder();
        	$instituteRepo = $instituteBuilder->getInstituteRepository();

			$instituteIds = array();
			$cityIds = array();
			$stateIds = array();
			foreach ($courseIdsChunk as $batchKey => $batchValue) {
	        	$courseObjects = $this->courseRepo->findMultiple($batchValue,array('basic','course_type_information','location','fees'));
	        	$courseTopHierarchyInstituteData = $coursedetailmodel->getTopHierarchyInstitutesForCourses($batchValue);

	        	$data = "listingIds[]=".implode(",", $batchValue)."&listingType=course";
	        	$aggregateReviewsData = $this->apiClient->makeAPICall('REVIEW',$url,'POST','',$data,$headers);
	        	$aggregateReviewsData = json_decode($aggregateReviewsData['output'],true);
	        	$aggregateReviewsData = $aggregateReviewsData['data'];
	        	$aggregateReviewsData = $aggregateReviewsData['aggregateReviewData'];
	        	foreach ($courseObjects as $courseKey => $courseSingle) {
	        		if(empty($courseSingle) || empty($courseKey))
	        			continue;
	        		$courseId = $courseSingle->getId();
	        		$courseName = $courseSingle->getName();
	        		$courseNameIdMap = $courseName.":".$courseKey;
	        		$offeredByInstituteId = $courseSingle->getOfferedById();
	        		$offeredByInstituteName = $courseSingle->getOfferedByName();
	        		if(empty($offeredByInstituteId) || empty($offeredByInstituteName)){
	        			$offeredByInstituteNameIdMap ="";
	        		}
	        		else{
	        			$offeredByInstituteNameIdMap = $offeredByInstituteName.":".$offeredByInstituteId;	
	        		}
	        		$affiliatedUniversityData = $courseSingle->getAffiliations();
					$affiliatedUniversityId = $affiliatedUniversityData['university_id'];
					if(!empty($affiliatedUniversityId)){
						$affiliatedInstituteObj = $instituteRepo->find($affiliatedUniversityId,array('basic'));
						$affiliatedUniversityName = $affiliatedInstituteObj -> getName();	
					}
					if(empty($affiliatedUniversityId) || empty($affiliatedUniversityName)){
	        			$affiliatedUniversityNameIdMap ="";
	        		}
	        		else{
	        			$affiliatedUniversityNameIdMap = $affiliatedUniversityName.":".$affiliatedUniversityId;
	        		}
	        		$hierarchyCourseData = $courseDetailLib->getCourseListingHierarchyDataNew($courseId,array($courseSingle));
	        		$hierarchyInstituteIds = array();
	        		$hierarchyInstituteNameIdMap = array();
	        		foreach($hierarchyCourseData[$courseId] as $key => $hierarchyData){
	        			foreach($hierarchyData as $key => $value){
	        				$hierarchyInstituteIds[] = $key;
	        				$hierarchyInstituteNameIdMap[] = $value['name'].":".$key;
	        			}
	        		}

	        		/*$instituteId = $courseSingle->getInstituteId();*/
                    $instituteId = $courseTopHierarchyInstituteData[$courseSingle->getId()];
	        		$mainLocation = $courseSingle->getMainLocation();

	        		$courseTypeInformation = $courseSingle->getCourseTypeInformation();

	        		if(empty($mainLocation)) {
	        			continue;
	        		}
	        		$courseData[$courseKey]['stateId'] = $mainLocation->getStateId();
	        		$courseData[$courseKey]['stateName'] = $mainLocation->getStateName();
	        		$courseData[$courseKey]['cityId'] = $mainLocation->getCityId();
	        		$courseData[$courseKey]['cityId1'][] = $mainLocation->getCityId();
	        		$courseData[$courseKey]['cityName'] = $mainLocation->getCityName();
	        		$courseData[$courseKey]['courseId'] = $courseSingle->getId();
	        		$courseData[$courseKey]['instituteId'] = $instituteId;
	        		$courseData[$courseKey]['courseNameIdMap'] = $courseNameIdMap;
	        		$courseData[$courseKey]['offeredByInstituteNameIdMap'] = $offeredByInstituteNameIdMap;
	        		$courseData[$courseKey]['affiliatedUniversityId'] = $affiliatedUniversityId;
	        		$courseData[$courseKey]['affiliatedUniversityNameIdMap'] = $affiliatedUniversityNameIdMap;
	        		$courseData[$courseKey]['hierarchyInstituteIds'] = $hierarchyInstituteIds;
	        		$courseData[$courseKey]['hierarchyInstituteNameIdMap'] = $hierarchyInstituteNameIdMap;
	        		if(!empty($instituteId) && !in_array($instituteId, $instituteIds)){
	        			$instituteIds[] = $instituteId;
	        		}

	        		if(!empty($courseData[$courseKey]['cityId']) && !in_array($courseData[$courseKey]['cityId'], $cityIds)) {
	        			$cityIds[] = $courseData[$courseKey]['cityId'];
	        		}

	        		if(!empty($courseData[$courseKey]['stateId']) && !in_array($courseData[$courseKey]['stateId'], $stateIds)) {
	        			$stateIds[] = $courseData[$courseKey]['stateId'];
	        		}

	        		$courseInstituteMapping[$courseKey] = $instituteId;

	        		if(!empty($courseTypeInformation['entry_course'])){
	        			$hierachiesData = $courseTypeInformation['entry_course']->getHierarchies();
	        			foreach ($hierachiesData as $hkey => $hvalue) {
	        				if($hvalue['primary_hierarchy'] == 1){
	        					$courseData[$courseKey]['specializationId'] = $hvalue['specialization_id'];
	        					if(array_key_exists($hvalue['specialization_id'], $specializationOrder)){
	        						$courseData[$courseKey]['specializationOrder'] = $specializationOrder[$hvalue['specialization_id']];
	        					}else{
	        						$courseData[$courseKey]['specializationOrder'] = count($specializationOrder) +1;
	        					}
	        					if(!empty($hvalue['specialization_id']) && !in_array($hvalue['specialization_id'], $specializationIds)){
	        						$specializationIds[] = $hvalue['specialization_id'];
	        					}else{
	        						if(empty($hvalue['specialization_id'])){
	        							$emptySpecializationCourseIds[] = $courseKey;
	        						}
	        					}
	        					break;
	        				}
	        			}
	        		}

	        		if(empty($courseData[$courseKey]['specializationOrder'])){
	        			 $courseData[$courseKey]['specializationOrder'] = count($specializationOrder) +1;
	        		}

	        		$feesData = $courseSingle->getFees();
	        		if(!empty($feesData)){
	        			$courseData[$courseKey]['fee'] = $feesData->getFeesValue();
	        		}
	        		if(isset($aggregateReviewsData[$courseKey])) {
	        			$courseData[$courseKey]['aggregateReview'] = $aggregateReviewsData[$courseKey]['aggregateRating']['averageRating']['mean'];
	        			$courseData[$courseKey]['reviewCount'] = $aggregateReviewsData[$courseKey]['totalCount'];
	        		}
	        	}
	        }
	        if(!empty($specializationIds) && count($specializationIds) > 0){
	        	$specializationObjects = $specializationRepo->findMultiple($specializationIds);
	        }

	        if(!empty($cityIds) && count($cityIds) > 0){
	        	$cityObjects = $locationRepo->findMultipleCities($cityIds);
	        }


	        if(!empty($stateIds) && count($stateIds) > 0){
	        	$stateObjects = $locationRepo->findMultipleStates($stateIds);
	        }

        	foreach ($courseData as $courseIdKey => $courseValue) {
        		if(!empty($specializationObjects[$courseValue['specializationId']])){
        			$courseData[$courseIdKey]['specializationName'] = $specializationObjects[$courseValue['specializationId']]->getName();
        		}
        		if(!empty($cityObjects[$courseValue['cityId']])){
        			$isPopular = $cityObjects[$courseValue['cityId']]->isPopular();
//        			$isPopular = !empty($isPopular) && $isPopular ? 0 : 1;
        			if(!empty($isPopular) && $isPopular == "1"){
                        $isPopular = "0";
                    }else {
                        $isPopular = "1";
                    }
        			$cityName = $cityObjects[$courseValue['cityId']]->getName();
        			//location_city:<popular>:<cityName>:<id>
        			$courseData[$courseIdKey]['popularCity'][] = $isPopular.":".$cityName.":".$courseValue['cityId'];
        			$virtualCityId = $cityObjects[$courseValue['cityId']]->getVirtualCityId();
        			if(!empty($virtualCityId) && (int)$virtualCityId > 0){
        				if(!empty($cityObjects[$virtualCityId])){
        					$isPopular = $cityObjects[$virtualCityId]->isPopular();
                            if(!empty($isPopular) && $isPopular == "1"){
                                $isPopular = "0";
                            }else {
                                $isPopular = "1";
                            }
//        					$isPopular = $isPopular ? 1 : 0;
        					$cityName = $cityObjects[$virtualCityId]->getName();
        					$courseData[$courseIdKey]['popularCity'][] = $isPopular.":".$cityName.":".$virtualCityId;
        				}else{
        					$virualObject = $locationRepo->findCity($virtualCityId);
        					if(!empty($virualObject)){
        						$isPopular = $virualObject->isPopular();
                                if(!empty($isPopular) && $isPopular == "1"){
                                    $isPopular = "0";
                                }else {
                                    $isPopular = "1";
                                }
//        						$isPopular = $isPopular ? 1 : 0;
        						$cityName = $virualObject->getName();
        						$courseData[$courseIdKey]['popularCity'][] = $isPopular.":".$cityName.":".$virtualCityId;
        					}
        				}
        				$courseData[$courseIdKey]['cityId1'][] = $virtualCityId;
        			}
        		}
        		if(!empty($stateObjects[$courseValue['stateId']])){
        			$isPopular = $stateObjects[$courseValue['stateId']]->isPopular();
//        			$isPopular = !empty($isPopular) && $isPopular ? 0 : 1;
        			if(!empty($isPopular) && $isPopular == true){
                        $isPopular = "0";
                    }else{
                        $isPopular = "1";
                    }
        			$stateName = $stateObjects[$courseValue['stateId']]->getName();
        			$courseData[$courseIdKey]['popularState'] = $isPopular.":".$stateName.":".$courseValue['stateId'];
        		}
        	}


	        if(!empty($instituteIds) && count($instituteIds) > 0){
	        	$instituteOwnershipMapping = $this->getInstituteData($instituteIds);
	        	foreach ($courseData as $courseKey => &$courseValue) {
	        		if(array_key_exists($courseValue['instituteId'], $instituteOwnershipMapping)) {
	        			$courseValue['ownership'] = $instituteOwnershipMapping[$courseValue['instituteId']]['ownership'];
	        			$courseValue['popularity'] = str_pad($instituteOwnershipMapping[$courseValue['instituteId']]['popularity'], 10, "0", STR_PAD_LEFT);
	        			$courseValue['popularityName'] = str_pad((1000000000 - $instituteOwnershipMapping[$courseValue['instituteId']]['popularity']), 10, "0", STR_PAD_LEFT).":".$instituteOwnershipMapping[$courseValue['instituteId']]['name'].":".$courseValue['instituteId'];
	        			$courseValue['instituteUrl'] = $instituteOwnershipMapping[$courseValue['instituteId']]['instituteUrl'];
	        			$courseValue['reivewUrl'] = $instituteOwnershipMapping[$courseValue['instituteId']]['reivewUrl'];
	        		}
	        	}
	        }
	        return array('emptySpecializationCourseIds' => $emptySpecializationCourseIds, 'courseData' => $courseData);
		}
		function getInstituteData($instituteIds){

			$instituteOwnershipMapping = array();
			$institutePopularity = array();

			if(!empty($instituteIds) && count($instituteIds) > 0){
				$chunkSize = 50;      
		        $batchSize = ceil(count($instituteIds)/$chunkSize);
		        $instituteIdsChunk = array();

		        $this->CI->load->builder("nationalInstitute/InstituteBuilder");
		        $instituteBuilder = new InstituteBuilder();
		        $instituteRepo = $instituteBuilder->getInstituteRepository();
		        $shikshaPopularityModel = $this->CI->load->model('ShikshaPopularity/shikshaPopularityModel');

		        for($i = 0; $i < $batchSize; $i++) {
		        	$instituteIdsChunk[] = array_slice($instituteIds, $i*$chunkSize,$chunkSize);
		        }

		        foreach ($instituteIdsChunk as $instituteIdIndex => $instituteData) {
		        	$instituteObjects = $instituteRepo->findMultiple($instituteData);
		        	$popularity = $shikshaPopularityModel->fetchPopularityDataBasedonBasecourse($instituteData);
		        	foreach($instituteObjects as $objectIndex => $instituteSingle){
		        		if(empty($instituteSingle)){
		        			continue;
		        		}
		        		$tempInstituteId = $instituteSingle->getId();
		        		$ownership = $instituteSingle->getOwnership();
		        		$instituteUrl = $instituteSingle->getRelativeURL();
		        		$reivewUrl = $instituteSingle->getRelativeAllContentPageUrl('reviews');
		        		$instituteOwnershipMapping[$tempInstituteId] = array('ownership' => $ownership,'popularity' => !empty($popularity[$tempInstituteId]) ? $popularity[$tempInstituteId] : 0,'name' => $instituteSingle->getName(),'instituteUrl' => $instituteUrl,'reivewUrl' => $reivewUrl);
		        	}
		        }
			}
			return $instituteOwnershipMapping;
		}
		function updateCourseDataInCollegeShortList(){
			$this->CI->load->library('alerts_client');
			$this->alertClient  = new Alerts_client();
			// send script start mail
			$scriptStartTime   = time();
			$this->sendCronAlert("Started : ".__METHOD__, "");

	    	$courseIds = $this->collegeshortlistmodel->getSingleFieldsFromCollegeShortlist("course_id");		
	    	if(empty($courseIds)){
	    		return;
	    	}
	    	$chunkSize = 50;      
	        $batchSize = ceil(count($courseIds)/$chunkSize);
	        $courseIdsChunk = array();
	        for($i = 0; $i < $batchSize; $i++) {
	        	$courseIdsChunk[] = array_slice($courseIds, $i*$chunkSize,$chunkSize);
	        }
	        $courseData = array();
	        $courseRelatedData = $this->getCourseData($courseIdsChunk);
	        $courseRelatedData = $courseRelatedData['courseData'];
	        $courseDataChunk = array();
	        $chunkSize = 500;      
	        $batchSize = ceil(count($courseRelatedData)/$chunkSize);
	        for($i = 0; $i < $batchSize; $i++) {
	        	$courseDataChunk[] = array_slice($courseRelatedData, $i*$chunkSize,$chunkSize, true);
	        }
	        $batchCount = 1;
	        foreach ($courseDataChunk as $indexKey => $subCourseArray) {
	        	error_log("Update Collegeshortlist Course started for batch ".$batchCount);
	        	$responseCode = $this->solrServerLib->updateCourseDataInCollegeShortlist($subCourseArray,'collegeshortlist');
	        	if($responseCode[0] == 1){
	        		error_log("Update Collegeshortlist Course ended for batch ".$batchCount." success");
	        	}else{
	        		error_log("Update Collegeshortlist Course ended for batch ".$batchCount." failed");
	        	}
	        	$batchCount++;
	        }
	        $scriptEndTime = time();
			$timeTaken     = ($scriptEndTime - $scriptStartTime) / 60;
			$text 		   = __METHOD__." Script Ended Successfully.<br/>Time Taken : ".$timeTaken." mins<br/>Contact Person: Nithish Reddy";
			$this->sendCronAlert("Ended : ".__METHOD__, $text);
		}

        function cacheSanitizedPredictorExamNames() {
            $exams = $this->collegeshortlistmodel->getExamBaseInformation();
            foreach ($exams as $key => $exam) {
                $cacheData[sanitizeUrlString($exam['exam_name'])] = $exam['exam_id'];
            }
            $this->examCache->storeSanitizedPredictorExamName($cacheData);
        }
	}