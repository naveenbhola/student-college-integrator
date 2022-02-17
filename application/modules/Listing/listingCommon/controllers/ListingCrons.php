<?php

class ListingCrons extends MX_Controller{

	private $mailingList = array("ankur.gupta@shiksha.com","kalva.nithishredyy@99acres.com");
	function __construct(){

	}

	/**
	 * Cron method to populate view count cache of courses and institute
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2016-10-21
	 * @return none
	 */
	function populateListingViewCountCache(){

        $this->validateCron();
        
		// allow cron to run with no time limit
		ini_set("max_execution_time", "-1");

		// load dependencies
		$this->load->library('alerts_client');
		$this->alertClient  = new Alerts_client();
		$listingcommonmodel = $this->load->model("listingCommon/listingcommonmodel");
		$redis_client       = PredisLibrary::getInstance();

		// send cron start mail
		$scriptStartTime   = time();
		$this->sendCronAlert("Started : ".__METHOD__, "");

		$courseViewCountHashKey    = "courses_view_count";
		$instituteViewCountHashKey = "institutes_view_count";

		// for course
		$courseViewCount = $listingcommonmodel->fetchAllListingViewCount(array("course_free","course_paid"), 365);

		// for institute
		$instituteViewCount = $listingcommonmodel->fetchAllListingViewCount(array('institute_free','institute_paid'), 365);

		
		// delete previous cache data
		$redis_client->deleteKey(array($courseViewCountHashKey, $instituteViewCountHashKey));		
		
		// store data in cache
		$redis_client->addMembersToHash($courseViewCountHashKey,$courseViewCount,FALSE);
		$redis_client->addMembersToHash($instituteViewCountHashKey,$instituteViewCount,FALSE);

		$scriptEndTime = time();
		$timeTaken     = ($scriptEndTime - $scriptStartTime) / 60;
		$text 		   = __METHOD__." Cron Ended Successfully.<br/>Time Taken : ".$timeTaken." mins<br/>Contact Person: Ankur Gupta";
		$this->sendCronAlert("Ended : ".__METHOD__, $text);
	}

	/**
	 * Cron to get all the universities who have admission info(in hierarchy) and store them into cache
	 * @author Yamini Bisht
	 * @date   2016-12-23
	 * @return [type]     [description]
	 */
	function updateAdmissionFlagUniversities($listingIds = ''){
        $this->validateCron();
        ini_set('memory_limit','1000M');
        set_time_limit(0);

        $this->_init();

        // load dependencies
		$this->load->library('alerts_client');
		$this->alertClient  = new Alerts_client();
		$listingcommonmodel = $this->load->model("listingCommon/listingcommonmodel");
		$redis_client       = PredisLibrary::getInstance();

		// send cron start mail
		$scriptStartTime   = time();
		$this->sendCronAlert("Started : ".__METHOD__, "");

		$this->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $this->instituteRepo = $instituteBuilder->getInstituteRepository();

        if($listingIds!=''){
            $universityIds = explode(',', $listingIds);
        }
        else{
            $allcontent = $this->load->model("nationalInstitute/allcontentmodel");
            $universities = $allcontent->getAllUniversities();

            foreach($universities as $key=>$val){
                $universityIds[] =  $val['listing_id'];
            }
        }
        _p($universityIds);
        $admissionAvailableFlag = array();
        if(!empty($universityIds)){
            foreach($universityIds as $id){
                $instituteObj = $this->instituteRepo->find($id,array('basic'));
                $instituteType = $instituteObj->getType();
                $admissionDetail = $instituteObj->getAdmissionDetails();
                
                if(empty($admissionDetail)){
                    $this->instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');
                    $this->courseDetailLib = $this->load->library('nationalCourse/CourseDetailLib');
                    $courseArray = $this->instituteDetailLib->getInstituteCourseIds($id, $instituteType);
                    foreach ($courseArray['courseIds'] as $courseId){
                        $courseAdmission = $this->courseDetailLib->getAdmissionsData($courseId);
                        if(!empty($courseAdmission)){
                            $admissionAvailableFlag[] = $id;
                            break;
                        }
                    }
                }
                else{
                    $admissionAvailableFlag[] = $id;
                }
                
            }
        }

        _p($admissionAvailableFlag);
        $admissionAvailableFlag = array_values(array_unique($admissionAvailableFlag));
        _p($admissionAvailableFlag);

        // store the data into redis
        $redisKey = "UnivWithAdmissionInfo";
        $redis_client = PredisLibrary::getInstance();

        // delete the old set
        $redis_client->deleteKey(array($redisKey));

        if(!empty($admissionAvailableFlag)){
	        // add the new set for admission details
	        $redis_client->addMembersToSet("UnivWithAdmissionInfo", (array)$admissionAvailableFlag);
	    }

        // cron ended
        $scriptEndTime = time();
		$timeTaken     = ($scriptEndTime - $scriptStartTime) / 60;
		$text 		   = __METHOD__." Cron Ended Successfully.<br/>Time Taken : ".$timeTaken." mins<br/>Contact Person: Yamini Bisht";
		$this->sendCronAlert("Ended : ".__METHOD__, $text);
    }

	function sendCronAlert($subject, $body, $emailIds){

		if(empty($emailIds))
			$emailIds = $this->mailingList;

		foreach($emailIds as $key=>$emailId)
			$this->alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emailId, $subject, $body, "html", '', 'n');
    }

	/**
	 * [generateAllCoursesBrochure this function will generate brochure for all courses which doesn't have any brochure attached]
	 * @author Ankit Garg <g.ankit@shiksha.com>
	 * @date   2017-01-24
	 * @return [type]     [description]
	 */
	function generateAllCoursesBrochure($inputCourseId) {
        $this->validateCron();
		ini_set('memory_limit','2000M');
		set_time_limit(0);
		$this->load->builder("nationalCourse/CourseBuilder");
		$this->load->library("listingCommon/CourseBrochureGenerator");
        $courseBuilder = new CourseBuilder();
        $CourseBrochureGeneratorObj = new CourseBrochureGenerator();
        $this->courseRepo = $courseBuilder->getCourseRepository();

        $this->coursecache = $this->load->library('nationalCourse/cache/NationalCourseCache');

        $courseIds = $this->courseRepo->getCoursesWithoutBrochure(); 
        $courseIds = array_unique(array_filter($courseIds));
        if($inputCourseId) {	
			$courseIds = array($inputCourseId);
        }
        $chunkSize = 100;
        $batchSize = ceil(count($courseIds)/$chunkSize);
        $courseIdsChunk = array();
        // $finalCount = 0;
        for($i = 0; $i < $batchSize; $i++) {
        	$courseIdsChunk[] = array_slice($courseIds, $i*$chunkSize,$chunkSize);
        	// $finalCount += count(array_slice($courseIds, $i*$chunkSize,$chunkSize));
        }
        $this->brochureMessage = '';
        $coursesProcessedCount = 0;
	    // error_log("\n".'Total courses not containing brochure : '.count($courseIds),3,"/tmp/courseAutogenerateBrochure.log"); 
        foreach ($courseIdsChunk as $key => $courseIdsArr) {
	    	// error_log("\n".'Total courses not containing brochure:'.count($courseIds),3,"/tmp/courseAutogenerateBrochure.log"); 
	        $CoursesObj = $this->courseRepo->findMultiple($courseIdsArr, 'full');
	        // error_log("\n".'Starting brochure generation for chunk : '.$key .' with length '.count($courseIdsArr),3,"/tmp/courseAutogenerateBrochure.log"); 
	        // _p($Courses); die;
			        	// _p($CoursesObj); die;
			foreach ($courseIdsArr as $courseId) {
				if(is_object($CoursesObj[$courseId]) && $CoursesObj[$courseId]->getId()) {
					$res = $CourseBrochureGeneratorObj->generateBrochure($CoursesObj[$courseId], $courseId);
					if($res['status'] == 'error') {
						$this->brochureMessage .= $res['msg'].'<br/>';
					}
					else {
						$coursesProcessedCount++;
						$this->coursecache->removeCoursesCache(array($courseId));
					}
				}
				else {
					$this->brochureMessage .= 'Course Object not found for course id: '.$courseId.'<br/>';
					// error_log("\n".,3,"/tmp/courseAutogenerateBrochure.log"); 
				}
			}
			// die;
        }
        $this->load->library('alerts_client');
		$this->alertClient  = new Alerts_client();
        $summaryBody = 'Total courses not containing brochure : '.count($courseIds).'<br/>Brochure created for total courses: '.$coursesProcessedCount.' <br/>'.$this->brochureMessage;
        $this->sendCronAlert("Summary : ".__METHOD__.' cron', $summaryBody,array('listingstech@shiksha.com'));
	}

    function generatePdfForCTAs($instituteId, $cta) {
        $this->courseBrochureGeneratorLib = $this->load->library("listingCommon/CourseBrochureGenerator");
        $this->courseBrochureGeneratorLib->generatePdfForCTAs($instituteId, $cta);
    }

    function invalidateInstituteCache($instituteId){

    	// purpose of this function is to invalidate institute cache through Google map image creation worker
    	if(!empty($instituteId)){
    		$this->load->library("nationalInstitute/cache/NationalInstituteCache");
    		$nationalInstituteCache = new NationalInstituteCache();

    		$nationalInstituteCache->removeInstitutesCache(array($instituteId));
    	}
    }

    function removeAndUpdateListing() {
        ini_set('memory_limit','-1');
        set_time_limit(0);

        $courseArray = array();
        if (($handle = fopen("/var/www/html/shiksha/public/deletedCourses.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $courseArray[] = trim($data[0]);

            }
            fclose($handle);
        }

        if(!empty($courseArray)){
            $this->coursecache = $this->load->library('nationalCourse/cache/NationalCourseCache');

            foreach($courseArray as $key=>$courseId){
 $this->coursecache->removeCoursesCache(array($courseId));
                error_log('::::::::course remove From cache::: '.$courseId.':::::',3,"/tmp/deleteAndRemoveCourseCron.log");
            }

        }

    } 

    /**
     * [cacheTrendingSearches this function will cache trending searches into cache]
     * @author Ankit Garg <g.ankit@shiksha.com>
     * @date   2017-06-22
     * @return [type]     [description]
     */
    function cacheTrendingSearches() {
        $this->validateCron();
    	ini_set('memory_limit','2000M');
		set_time_limit(0);
    	$TrendingSearchLib = $this->load->library('listingCommon/TrendingSearchLib');
    	// _p($TrendingSearchLib->getTrendingSearches());
    	$TrendingSearchLib->storeTrendingSearches();
    	echo "Trending Search Cache refreshed successfully";
    	// $TrendingSearchLib->validateAndUpdateTrendingSearches(3029, 'collegeAndCourse');
    	
    }



    function processNotificationMailerData(){
        $this->validateCron();
        ini_set('memory_limit','2000M');
        set_time_limit(0);
        $this->coursePostingLib = $this->load->library('nationalCourse/CoursePostingLib');
        $data = $this->coursepostingmodel->getDataForNotificationMailer();
        if(empty($data)){
            $mailContentHtml ='<html><body><p>No paid course has been changed.</p></body></html>';
            $this->sendMail($mailContentHtml);
            _P('No Data for Mail');
            return true;
        }
        $dataForHtml   = $this->coursePostingLib->formatNotificationMailerData($data);
        $mailContentHtml =  $this->load->view('/nationalCourse/notificationMailerView',$dataForHtml,true);
        if($this->sendMail($mailContentHtml)){
            $this->coursepostingmodel->updateNotificationMailerTable();
            echo 'mail send successfully';
            return ture;
        }
        echo 'Internal error when we try to send mail';
        return false;       
    }       

    public function sendMail($courseChangeData) {
        if(empty($courseChangeData)){
            return false;
        }
        $this->load->library('alerts_client');
        $this->alertClient = new Alerts_client();
        $subject      = "Course Change Notification";
        $emailIdarray = array('akash.g@99acres.com',"prateek.rustagi@shiksha.com","shamender.srivastav@shiksha.com","saumya.tyagi@shiksha.com","yaseen@shiksha.com","Meenu.Asthana@shiksha.com","sacheta.pandey@shiksha.com","neha.maurya@shiksha.com","zubin.ray@shiksha.com","prabhat.sachan@shiksha.com","listingstech@shiksha.com",'sweksha.srivastava@Shiksha.com');
        $content = $courseChangeData;
        foreach($emailIdarray as $key=>$emailId) {
            $this->alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emailId, $subject, $content, "html", '', 'n');
        }
        return true;
    }


    /**
     *  @JIRA- https://infoedge.atlassian.net/browse/MAB-4554
        @Desc -If any course is paid then all the courses, institutes and universities in its hierarchy will be considered  paid. This has to be done by the following logic:
        1. The top entity (university/institute) will be stored for every entity below it and if that top entity is paid then all others will be paid.
     * @author Akhter
     * @date   2018-09-18
     * @return none
     */
    function updateInstitutePaidStatus(){
        $this->validateCron();

        ini_set('memory_limit','1000M');
        set_time_limit(0);
        
        $time_start = microtime_float(); $start_memory = memory_get_usage();
        $bucketSize = 100;
        
        $this->institutedetailsmodel  = $this->load->model("nationalInstitute/institutedetailsmodel");
        $this->instituteDetailLib     = $this->load->library('nationalInstitute/InstituteDetailLib');
        $this->nationalinstitutecache = $this->load->library('nationalInstitute/cache/NationalInstituteCache');

        $result = $this->institutedetailsmodel->getAllInstitute();
        $this->resultData = $result;

        $totalResult = count($this->resultData);
        if($totalResult <= $bucketSize){
            $this->preparePaidClientData($this->resultData);
        }else{
            //step-2 Split into chunks
            $num = $totalResult;
            $divideby = $bucketSize;
            $remainder=$num % $divideby;
            $number=explode('.',($num / $divideby));
            $loop=$number[0];
            $pages = ceil($num/$divideby);
            $pages = $pages - 1;

            for($i=0; $i <= $loop ; $i++){
                $start = $i*$divideby;
                if($i == $pages){
                    $limit =  " limit $start , $remainder";
                    echo $limit.'<br>';
                    $chunk = array_slice($this->resultData, $start, $remainder);
                    $this->preparePaidClientData($chunk);
                }else{
                    $limit = " limit $start , $bucketSize";
                    echo $limit.'<br>';
                    $chunk = array_slice($this->resultData, $start, $bucketSize);
                    $this->preparePaidClientData($chunk);
                }
            }
        }
        echo getLogTimeMemStr($time_start, $start_memory);
    }

    function preparePaidClientData($instituteIds){
        //$instituteIds = array(24642,307,35861);
        $instituteCourseIds = $this->instituteDetailLib->getAllCoursesForMultipleInstitutes($instituteIds);
        unset($instituteIds);
        $courseArray = array();
        foreach ($instituteCourseIds as $instituteId => $value) {
            foreach ($value['courseIds'] as $key => $courseId) {
                $courseArray[] = $courseId;
            }
        }

        $paidCourses = $this->institutedetailsmodel->getPaidCourses($courseArray);
    
        $hierarchyFreeInstituteList = array();
        $hierarchyPaidInstituteList = array();
        $subscriptionFreeInstituteList = array();
        $subscriptionPaidInstituteList = array();
        
        foreach ($instituteCourseIds as $instituteId => $value) {
            $instituteWisePaidCourses = array_intersect($paidCourses, $value['courseIds']);
            //getting all unique  intitute ids including parent and it's all child.
            $coursesInstitutesList = array_unique((array_merge(array_keys($value['instituteWiseCourses']), array($instituteId))));
            if(!empty($instituteWisePaidCourses)) {
                //$paidCourses = array_diff($paidCourses, $instituteWisePaidCourses);
                $hierarchyPaidInstituteList = array_merge($hierarchyPaidInstituteList,$coursesInstitutesList);
                $subscriptionPaidInstituteList[$instituteId]= $instituteId;
            }else {
                $hierarchyFreeInstituteList = array_merge($hierarchyFreeInstituteList,$coursesInstitutesList);
                $subscriptionFreeInstituteList[$instituteId] = $instituteId;
            }
        }
        
        $finalData['hierarchy']['free']     = $hierarchyFreeInstituteList; 
        $finalData['hierarchy']['paid']     = $hierarchyPaidInstituteList; 
        $finalData['subscription']['free']  = $subscriptionFreeInstituteList;
        $finalData['subscription']['paid']  = $subscriptionPaidInstituteList;
        
        foreach ($finalData as $rootKey => $value) {
            foreach ($value as $type => $instituteIds) {
                $isPaid = ($type == 'paid') ? 1 : 0;
                if($rootKey == 'hierarchy' && count($instituteIds)>0){
                    $this->institutedetailsmodel->updateInstitutePaidStatus($isPaid, $instituteIds);
                }
                if($rootKey == 'subscription' && count($instituteIds)>0){
                    //$this->institutedetailsmodel->updateInstituteSubscriptionStatus($isPaid, $instituteIds); // NA 
                }
                unset($instituteIds);
            }
        }
        
        if(count($finalData['hierarchy']['free'])>0 || count($finalData['hierarchy']['paid'])>0){
            foreach ($finalData['hierarchy'] as $type => $instituteIds) {
                foreach ($instituteIds as $key => $instituteId) {
                    $data['isHierarchyPaid'] = ($type == 'paid') ? 1 : 0;
                    if($type == 'free'){
                        $data['hasPaidCourses']  = 0;
                    }else if($type == 'paid'){
                        $data['hasPaidCourses']  = ($finalData['subscription'][$type][$instituteId]) ? 1 : 0;
                    }
                    $cacheData[$instituteId] = json_encode($data);
                }
            }
        }else if(count($finalData['subscription']['free'])>0 || count($finalData['subscription']['paid'])>0){
            foreach ($finalData['subscription'] as $type => $instituteIds) {
                foreach ($instituteIds as $key => $instituteId) {
                    $data['hasPaidCourses'] = ($type == 'paid') ? 1 : 0;
                    $data['isHierarchyPaid']= 0;
                    $cacheData[$instituteId] = json_encode($data);
                }
            }
        }
        // store data in cache
        $this->nationalinstitutecache->setInstitutePaidStatus($cacheData);
        unset($finalData, $cacheData);
    } 

    
     /* 
        @JIRA - https://infoedge.atlassian.net/browse/MAB-4577
        @Desc - Custom ExamName mapping.
     *  @author Akhter
     *  @date   2018-09-25
     *  @return none
     */
    function customEPMappings(){
        $this->validateCron();

        ini_set('memory_limit','1000M');
        set_time_limit(0);

            $customEPMappings = '[{"Exam name":"CUCET","Exam id":13544},{"Exam name":"AUCET","Exam id":13543},{"Exam name":"AP PGECET","Exam id":13733},{"Exam name":"TUET","Exam id":13624},{"Exam name":"DUET","Exam id":13632},{"Exam name":"MUEE","Exam id":13680},{"Exam name":"VTUEEE","Exam id":11842},{"Exam name":"UGAT","Exam id":13550},{"Exam name":"AP ECET","Exam id":13739},{"Exam name":"CUEE","Exam id":13658},{"Exam name":"HPCET","Exam id":13104},{"Exam name":"NEET MDS","Exam id":13755},{"Exam name":"KRUCET","Exam id":13646},{"Exam name":"OUCET","Exam id":13626},{"Exam name":"DCET","Exam id":13717},{"Exam name":"SPSAT","Exam id":13562},{"Exam name":"VSAT","Exam id":13537},{"Exam name":"GAT (PGT)","Exam id":13595},{"Exam name":"Banasthali University Aptitude Test","Exam id":13682},{"Exam name":"CIEAT","Exam id":12189},{"Exam name":"SAAT","Exam id":13660},{"Exam name":"NIMCET","Exam id":13578},{"Exam name":"SRMS EET","Exam id":13688},{"Exam name":"Uni-GAUGE","Exam id":13728},{"Exam name":"SU JEE","Exam id":13713},{"Exam name":"Haryana LEET","Exam id":13672},{"Exam name":"UKSEE","Exam id":13663},{"Exam name":"IIT JAM","Exam id":13560},{"Exam name":"MHCET Law","Exam id":13693},{"Exam name":"PAT","Exam id":13745},{"Exam name":"SRMJEEE (PG)","Exam id":13607},{"Exam name":"TS EAMCET","Exam id":10307},{"Exam name":"GATE","Exam id":3298},{"Exam name":"ISI Admission Test","Exam id":13743},{"Exam name":"DE-CODE","Exam id":13767},{"Exam name":"LPUPET","Exam id":13638},{"Exam name":"PULEET","Exam id":13655},{"Exam name":"ACET","Exam id":13751},{"Exam name":"BVP CET","Exam id":13183},{"Exam name":"KLUBSAT","Exam id":13634},{"Exam name":"GPAT","Exam id":13599},{"Exam name":"KIITEE","Exam id":13539},{"Exam name":"MMET","Exam id":13723},{"Exam name":"Sports Management Admission Test (SMAT)","Exam id":13709},{"Exam name":"TANCET","Exam id":9276},{"Exam name":"TSICET","Exam id":10692},{"Exam name":"URATPG","Exam id":13635},{"Exam name":"AUMAT","Exam id":13555},{"Exam name":"IPU CET","Exam id":13606},{"Exam name":"GLAET","Exam id":13669},{"Exam name":"HITSEEE","Exam id":13534},{"Exam name":"IUET","Exam id":13763},{"Exam name":"KEE","Exam id":11641},{"Exam name":"KLUEEE","Exam id":13664},{"Exam name":"MAH MCA CET","Exam id":13576},{"Exam name":"MAH-CET","Exam id":9983},{"Exam name":"MHCET","Exam id":9247},{"Exam name":"NEST","Exam id":13564},{"Exam name":"NPAT","Exam id":13541},{"Exam name":"PU MET","Exam id":13116},{"Exam name":"SEEE","Exam id":13593},{"Exam name":"SOFT CET","Exam id":12968},{"Exam name":"SPJAT","Exam id":13094},{"Exam name":"TUEE","Exam id":13623},{"Exam name":"UPAT","Exam id":13568},{"Exam name":"UPESEEE","Exam id":13715},{"Exam name":"WBJEE JELET","Exam id":13673},{"Exam name":"WBUT PGET","Exam id":13611},{"Exam name":"X-GMT","Exam id":13122},{"Exam name":"BHUET","Exam id":13085},{"Exam name":"GATA","Exam id":13625},{"Exam name":"ITM-NEST Entrance Test","Exam id":13730},{"Exam name":"GGSIPU","Exam id":13606}]';
            $customEPMappings = json_decode($customEPMappings,true);
            
        $this->institutedetailsmodel  = $this->load->model("nationalInstitute/institutedetailsmodel");

        foreach ($customEPMappings as $key => $value) {
            $customEPName[] = $value['Exam name'];
        }

        $courseIds = $this->institutedetailsmodel->getCourseByCustomExamName($customEPName);
        $courseIdsArr = array_unique($courseIds);
        unset($courseIds);

        foreach ($customEPMappings as $key => $value) {
            $customEPName = $value['Exam name'];
            $examId       = $value['Exam id'];
            $this->institutedetailsmodel->updateCustomExamNameWithExamId($examId, $customEPName);
        }
        echo 'Custom Exam mapping :: Done <br>';
        $this->resetCacheForCustomExamPageMapping($courseIdsArr);
    }

    function resetCacheForCustomExamPageMapping($courseIdsArr){
        ini_set("memory_limit", "6000M");
        ini_set('max_execution_time', -1);

        $this->load->model("indexer/NationalIndexingModel");
        $this->nationalIndexingModel = new NationalIndexingModel();
        $NationalCourseCache = $this->load->library('nationalCourse/cache/NationalCourseCache');

        $this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $this->courseRepo = $courseBuilder->getCourseRepository();
        $this->courseRepo->disableCaching();

        $batchSize = 500;
        $maxCount = count($courseIdsArr);
        error_log("== MAX SIZE == ".$maxCount);
        $currentCount = 0;
        while ($currentCount < $maxCount) {
            error_log("== CUUERNT COUNT == ".$currentCount);
            $slice = array_slice($courseIdsArr, $currentCount, $batchSize);
            $currentCount += $batchSize;
            $NationalCourseCache->removeCoursesCache($slice);
            $this->courseRepo->findMultiple($slice,'full');
            $this->nationalIndexingModel->addfullIndexQueue($slice, 'course');
        }
        echo 'Custom Exam mapping :: Course Reset Cache/Indexs :: Done';
    }

    /***
    *   below function is used for creating TOC for admission content both HTML and AMP HTML
    */
    function createTocForContent($instituteId, $content = "admission") {
        //$this->validateCron();
        ini_set('memory_limit','2000M');
        set_time_limit(0);

        $this->listingCommonLib = $this->load->library("listingCommon/ListingCommonLib");
        $this->listingCommonLib->createTocForContent($instituteId, $content);

        error_log("\n Done", 3, "/tmp/log_toc_generation.log");
    }
}