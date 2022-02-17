<?php

class ListingCommonScripts extends MX_Controller{

	private $mailingList = array("romil.goel@shiksha.com", "ankur.gupta@shiksha.com","kalva.nithishredyy@99acres.com");

	function __construct(){

	}

	/**
	 * Script to create listing media different size variants
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2016-12-22
	 * @return none
	 */
	function createInstituteMediaVariants(){

		ini_set("memory_limit", "2000M");
        ini_set('max_execution_time', -1);

		$this->load->library("ProcessImage");
		$this->listingcommonmodel = $this->load->model("listingCommon/listingcommonmodel");
		$processImage             = new ProcessImage();
		$batchSize                = 1000;
		$logFile                  = '/tmp/photoVariants.log';

		// fetch the set of images for which variants to be created
		$photos = $this->listingcommonmodel->getInstitutePhotosForVariantsCron($batchSize);

		// 1. load the images if exists otherwise log the listing id and continue
		foreach ($photos as $key=>$photo) {

			$mediaUrl = $photo['media_url'];
			$load     = $processImage->load($mediaUrl);

			// image name
			$imagePathParts =  explode("/", $mediaUrl);
			$imageName      = end($imagePathParts);
			$imageName      = explode(".", $imageName);
			$imageName      = $imageName[0];
			
			if($load !== true){
				error_log("\n".date("d-m-Y h:i:sa")." #".$key." Unable to load image id : ".$photo['id'],3, $logFile);
				continue;
			}
			else{
				error_log("\n".date("d-m-Y h:i:sa")." #".$key." Image loaded ".$photo['id'],3, $logFile);
			}

			// 2. create the variants
			$name_id    = $imageName; 
			$ImagesPath = MEDIA_BASE_PATH;

			$extension = end($imagePathParts);
			$extension = explode(".", $extension);
			$ext = $processImage->getImageType();
			if(empty($ext)){
				$ext       = end($extension);
			}

			$image68x54     = $ImagesPath."/images/".$name_id."_68x54.".$ext;
			$image100x78    = $ImagesPath."/images/".$name_id."_100x78.".$ext;
			$image135x100   = $ImagesPath."/images/".$name_id."_135x100.".$ext;
			$image155x116   = $ImagesPath."/images/".$name_id."_155x116.".$ext;
			$image205x160   = $ImagesPath."/images/".$name_id."_205x160.".$ext;
			$image210x157   = $ImagesPath."/images/".$name_id."_210x157.".$ext;
			$image270x200   = $ImagesPath."/images/".$name_id."_270x200.".$ext;
			$imageGallery   = $ImagesPath."/images/".$name_id."_g.".$ext;
			$thumburlSmall  = MEDIA_SERVER."/mediadata/images/".$name_id."_68x54.".$ext;
			$thumburlMedium = MEDIA_SERVER."/mediadata/images/".$name_id."_100x78.".$ext;

			$processImage->processImage(68, 54);   $processImage->output($image68x54); 
			$processImage->processImage(100, 78);  $processImage->output($image100x78);  
			$processImage->processImage(135, 100); $processImage->output($image135x100);
			$processImage->processImage(155, 116); $processImage->output($image155x116);
			$processImage->processImage(205, 160); $processImage->output($image205x160);
			$processImage->processImage(210, 157); $processImage->output($image210x157);
			$processImage->processImage(270, 200); $processImage->output($image270x200);

			$imagePaddingObj = new ProcessImage();
            $imagePaddingObj->load($mediaUrl);
            $imagePaddingObj->scaleImage(640, 480); $imagePaddingObj->output($imageGallery);

            // 3. update shiksha_institute_medias and tImageData
            $this->listingcommonmodel->updateInstituteMediaThumbnailImage($photo['id'], $photo['media_id'], $thumburlSmall, $thumburlMedium);
		}

		// 4. invalidate the institute and course cache
		$this->load->library('nationalInstitute/cache/NationalInstituteCache');  
		$instituteKeys    = $this->nationalinstitutecache->fetchKeysBasedOnPattern("Inst:*");
		$instituteLocKeys = $this->nationalinstitutecache->fetchKeysBasedOnPattern("InstLoc:*");
		$courseKeys       = $this->nationalinstitutecache->fetchKeysBasedOnPattern("Course:*");
		$courseLocKeys    = $this->nationalinstitutecache->fetchKeysBasedOnPattern("CourseLoc:*");

		$instituteKeys = array_merge((array)$instituteKeys, (array)$instituteLocKeys);
		$instituteKeys = array_merge((array)$instituteKeys, (array)$courseKeys);
		$instituteKeys = array_merge((array)$instituteKeys, (array)$courseLocKeys);

		$instituteKeys = array_chunk($instituteKeys, 100);

		foreach ($instituteKeys as $key => $value) {
			$this->nationalinstitutecache->deleteKeys($value);
		}
	}

	function sendCronAlert($subject, $body, $emailIds){

		if(empty($emailIds))
			$emailIds = $this->mailingList;

		foreach($emailIds as $key=>$emailId)
			$this->alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emailId, $subject, $body, "html", '', 'n');
	}

    /**
    * getting institute/university listing location id's, those doesn't have google static map location url (Limit : 20,000) AND create google static map location image and url
    */
    function getListingsLatitudeLongitudeValues()
    {	
    	$institutedetailsmodel = $this->load->model('nationalInstitute/institutedetailsmodel');
 		$result = $institutedetailsmodel->getListingsLatitudeLongitudeValues();

 		$locationsInfo = array();
 		foreach ($result as $rsKey => $rsValue) {
 			$locationsInfo[] = array('listing_location_id' => $rsValue['listing_location_id'] , 'listingId' => $rsValue['listing_id'], 'status' => $rsValue['status'], 'contact_details' => array( 'latitude' => $rsValue['latitude'] , 'longitude' => $rsValue['longitude']));
 		}
 		if(!empty($locationsInfo) && count($locationsInfo))
 		{
 			//Modules::run('nationalInstitute/InstitutePosting/logListingsForMapIntoQueue', $locationsInfo);
 			$rs = $this->logListingsForMapIntoQueue($locationsInfo);
 			if($rs)
 			{
 				_p('DONE');
 				die;
 			}
 			else
 			{
 				_p('Failed');
 				die;	
 			}

 		}
    }

    /**
    * function will store listing locations ids into rabbitMQueue along with its latitude and longitude values
    */

    function logListingsForMapIntoQueue($locationsData) 
    {
    	 $locationGeoMapping = array();
        foreach ($locationsData as $locationKey => $locationValue) {
            if(!empty($locationValue['contact_details']['latitude']) && !empty($locationValue['contact_details']['longitude']) && !empty($locationValue['listing_location_id']))
            {

                if( (((double) $locationValue['contact_details']['latitude']) > 8.0000 && ((double)$locationValue['contact_details']['latitude']) <= 37.6000) && ( ((double)$locationValue['contact_details']['longitude']) >= 68.7000 && ((double)$locationValue['contact_details']['longitude']) <= 97.2500))
                {
                	$locationGeoMapping = array('listing_location_id' =>  $locationValue['listing_location_id'],'latitude' => $locationValue['contact_details']['latitude'],'longitude' => $locationValue['contact_details']['longitude'], 'listingId' => $locationValue['listingId'], 'status' => $locationValue['status']);

	                try {
	                $this->config->load('amqp');
	                $this->load->library("common/jobserver/JobManagerFactory");
	                $jobManager = JobManagerFactory::getClientInstance();

	                $locationGeoMapping['logType'] = 'staticMapCreation';
	                $jobManager->addBackgroundJob("GoogleStaticMapData", $locationGeoMapping);
	                        }
	                        catch(Exception $e){
	                            error_log("JOBQException: ".$e->getMessage());
	                        }
                }
                else
                {
                	error_log('latitude and longitude values incorrect : listingLocation Id :'.$locationValue['listing_location_id'] .' , latitude : '.$locationValue['contact_details']['latitude'].' , longitude : '.$locationValue['contact_details']['longitude']);
                	echo '<br/>';
                }
            }
        }
        return true;
    }

    /**
     * Script to populate the Institute/University Cache again
     * @author Romil Goel <romil.goel@shiksha.com>
     * @date   2017-01-21
     */
    function populateInstituteUniversityCache(){
    	error_log("=== Started====");
    	// initialize
    	ini_set("memory_limit", "2000M");
        ini_set('max_execution_time', -1);
        $batchSize = 1000;

        // load dependencies
		$this->load->library('alerts_client');
		$this->alertClient  = new Alerts_client();

		// send script start mail
		$scriptStartTime   = time();
		$this->sendCronAlert("Started : ".__METHOD__, "");

        $this->listingcommonmodel = $this->load->model("listingCommon/listingcommonmodel");

        // 1. Get all institutes/universities
        $listings = $this->listingcommonmodel->getAllLiveInstitutes();
        $listings = array_chunk($listings, $batchSize);

        $this->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $this->instituteRepo = $instituteBuilder->getInstituteRepository();

        // 2. create the cache
        foreach ($listings as $listingIdsChunk) {
	
			$this->instituteRepo->disableCaching();
			$this->instituteRepo->findMultiple($listingIdsChunk, 'full');
        }
        error_log("=== Ended====");
        $scriptEndTime = time();
		$timeTaken     = ($scriptEndTime - $scriptStartTime) / 60;
		$text 		   = __METHOD__." Script Ended Successfully.<br/>Time Taken : ".$timeTaken." mins<br/>Contact Person: Romil Goel";
		$this->sendCronAlert("Ended : ".__METHOD__, $text);

    }

  /**
    * Function to populate the flat table shiksha_courses_institutes
    *
    */
    function populateFlatTable(){
		$lib = $this->load->library('nationalInstitute/InstituteFlatTableLibrary');
    	$lib->populateFlatTable();
	}

	/**
	* Function to create the Course Cache
	*/

	function createCourseCache(){

        ini_set("memory_limit", "6000M");
        ini_set('max_execution_time', "-1");

        $listingscriptsmodel = $this->load->model("listingCommon/listingcommonmodel");

        $this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $this->courseRepo = $courseBuilder->getCourseRepository();
        $this->courseRepo->disableCaching();

        $courseIdsArr = $listingscriptsmodel->fetchCourses();
        
        $batchSize = 1000;
        $maxCount = count($courseIdsArr);
        error_log("== MAX SIZE == ".$maxCount);
        $currentCount = 0;
        while ($currentCount < $maxCount) {
            error_log("== CURRENT COUNT == ".$currentCount);
            $slice = array_slice($courseIdsArr, $currentCount, $batchSize);
            $currentCount += $batchSize;
            $this->courseRepo->deleteCoursesCache($slice);
            $this->courseRepo->findMultiple($slice,'full');
        }
    }


    function deleteMultipleListingsCron(){
    	ini_set('memory_limit','-1');
        set_time_limit(0);

		$row = 1;
		$courseArray = array();
		$instituteArray =array();
		if (($handle = fopen("/var/www/html/shiksha/public/deletionCourseData.csv", "r")) !== FALSE) {
		    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		    	$courseArray[trim($data[0])] = trim($data[1]);
		        
		    }
		    fclose($handle);
		}

		if (($handle = fopen("/var/www/html/shiksha/public/deletionInstituteData.csv", "r")) !== FALSE) {
		    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {  	
		    	$instituteArray[trim($data[0])] = trim($data[1]);
		        
		    }
		    fclose($handle);
		}

    	if(!empty($courseArray)){
    		$this->coursecache = $this->load->library('nationalCourse/cache/NationalCourseCache');
    		$this->coursepostingmodel = $this->load->model('nationalCourse/coursepostingmodel');		
    		$this->load->builder("nationalCourse/CourseBuilder");
	    	$builder = new CourseBuilder();
	    	$repo = $builder->getCourseRepository();
	    	$migrateCriteria = array();
	    	$migrateCriteria['crs'] = 1;
	    	$migrateCriteria['reviews'] = 1;
	    	$migrateCriteria['questions'] = 1;
	    	$migrateCriteria['responses'] = 1;

    		foreach($courseArray as $courseId=>$newCourseId){
    			$open = $this->coursecache->canOpenCourseInEdit($courseId,'11');
				if($open['open']){
	    			error_log('::::::::course to be deleted:::::::'. $courseId,3,"/tmp/deleteListingsCron.log");

	    			//check if course has online form mapped to it
			        $this->load->library('Online_form_client');
				    $onlineClient = new Online_form_client();
			        $listingHasOnlineForm = $onlineClient->checkIfListingHasOnlineForm('course',$courseId);
			        if($listingHasOnlineForm) {
			            error_log('::::::::online form is available for:::::::'. $courseId,3,"/tmp/deleteListingsCron.log");
			            continue;
		        	}

	    			$courseObj = $repo->find($courseId);
	    			$instituteId = $courseObj->getInstituteId();

	    			if(!empty($newCourseId)){
	    				$courseObj1 = $repo->find($newCourseId);
	    				$id = $courseObj1->getId();
	    				$newInstituteId =(!empty($id))?$courseObj1->getInstituteId():null;    				
	    			}else{
	    				$newInstituteId = null;
	    			}
	    			
	    			if(!empty($instituteId)){
		    			$courseOrderUpdateCourseId = $this->coursepostingmodel->getCourseInfoForCourseOrder($courseId); 
						$deleteStatus              = $this->coursepostingmodel->deleteCourse($courseId, $instituteId, $newcourseId, $courseOrderUpdateCourseId, $newInstituteId, $migrateCriteria);

						$this->coursecache->removeCoursesCache(array($courseId));
						error_log('::::::::DeleteStatus For course '.$courseId.':::::'.$deleteStatus,3,"/tmp/deleteListingsCron.log");

					}else{
						error_log('::::::::Course Obj doesn\'nt exist for:::::::'. $courseId,3,"/tmp/deleteListingsCron.log");
					}
				}
				error_log('::::::::course deletion done for:::::::'. $courseId,3,"/tmp/deleteListingsCron.log");

    		}
    		
    	}

    	if(!empty($instituteArray)){
    		$breakPoint = false;
    		foreach($instituteArray as $listingId=>$newInstituteId){
	    		//get institute hierarchy  
		        $instituteIds = array();  
		        $this->load->builder("nationalInstitute/InstituteBuilder");
		    	$instituteBuilder = new InstituteBuilder();
		    	$repo = $instituteBuilder->getInstituteRepository(); 
		    	$insObj = $repo->find($listingId);
		    	$listingType = $insObj->getType();
		    	error_log('::::::::'.$listingType.' to be deleted '.$listingId,3,"/tmp/deleteListingsCron.log");

		    	$this->institutepostingmodel = $this->load->model("nationalInstitute/institutepostingmodel");
		    	if(!empty($listingType)){
			        $result = $this->institutepostingmodel->getChildData($listingId,$listingType,false);
				    foreach ($result as $key => $value) {
			            $temp = explode('_', $value[count($value) - 1]);
			            if(!empty($temp[1]))
			                $instituteIds[] = $temp[1];
			            	$instituteType[] = $temp[0];
			        }
			        $finalResult = array();
			        if(!empty($instituteIds)){
			        	$finalResult['instituteIds'] = $instituteIds;
			        	$finalResult['instituteType'] = $instituteType;
			        }

			        $instituteIdsTemp = $finalResult;
			        $instituteIds = $instituteIdsTemp['instituteIds'];
			        $instituteType = $instituteIdsTemp['instituteType'];

			        if(!empty($instituteIds)){
			        	$all_child_ids = implode(',',$instituteIds);
			    	}else{
			    		$instituteIds[] =$listingId;
			    		$instituteType[] = $listingType;
			    		$all_child_ids = $listingId;
			    	}

			    	/**
			        * Check for Online form
			        * If the institute/course contains an Online form,
			        * we should now allow it to be deleted.
			        */
			        $this->load->library('Online_form_client');
			        $onlineClient = new Online_form_client();
			       
			        foreach($instituteIds as $listingId){
			        	$listingHasOnlineForm = $onlineClient->checkIfListingHasOnlineForm('institute',$listingId);
			        	if($listingHasOnlineForm){
			            	error_log('::::::::OnlineForm is Available For this '.$listingType.':::::::'. $listingId,3,"/tmp/deleteListingsCron.log");
			            	$breakPoint = true;
			            	break;
			        	}

			    	}

			    	if($breakPoint) {
			    		$breakPoint = false;
			    		continue;
			    	}

		    		/*update status as deleted in all corresponding tables*/

			       	$deleteStatus = $this->institutepostingmodel->deleteListings($all_child_ids,$user_id,$listingType);


			        if(!empty($newInstituteId)){

			        	 /*update tagId for deleted listing and mark status as deleted in tags_entity Table*/
			        	 $this->institutepostingmodel->updateTagIdForDeletedListing($all_child_ids,$newInstituteId);

			        	 /*update AnARecommendation table*/
			        	 $AnARecomodel = $this->load->model("ContentRecommendation/anarecommendationmodel");
			        	 $AnARecomodel->updateInstituteIdAnARecommendation($all_child_ids,$newInstituteId);

			        	 //migrate questions to the new listing
			        	 $this->institutePostingLib = $this->load->library('nationalInstitute/InstitutePostingLib');
				        foreach($instituteIds as $listingId){
				        	$result = $this->institutePostingLib->migrateDeletedInstituteData($listingId,$newInstituteId,$listingType);
				        }
					     //migrate reviews to the new listing
					     $this->LDBClienLib = $this->load->library('LDB/clients/LDBMigrationLib');
					     $reviewInstitute[$listingTypeId] = $newInstituteId;
					     $this->LDBClienLib->updateDataForInstituteDeletion($all_child_ids,$newInstituteId);

					     $this->institutePostingLib->migrateUGCModules($all_child_ids,$newInstituteId);

			        }        

			     	if(strtolower($listingType) == "institute"){
			                $tagEntityType = "institute";
			        }else{
			                $tagEntityType = "University";
			        }

			        //get all the courses of the institute and update status as deleted in all course corresponding tables
		        	$institutemodel = $this->load->model("nationalInstitute/institutemodel");
					$courseArray       = $institutemodel->getCoursesOfInstitutes($instituteIds);

					if(!empty($courseArray)){
						$coursesList = array();
						foreach ($courseArray as $value) {
							$coursesList = array_merge($coursesList,$value);
						}
						$coursesList = array_values(array_unique($coursesList));
					}	

					if(!empty($coursesList)){
						$coursemodel = $this->load->model("nationalCourse/coursepostingmodel");
						$coursemodel->deleteMultipleCourses($coursesList);     
					}
					
					$this->load->library('Tagging/TaggingCMSLib');
	        		$this->taggingCMSLib = new TaggingCMSLib();
			        foreach($instituteIds as $key1  => $listingIdChild){
			        	if(strtolower($instituteType[$key1]) == "institute"){
			                $tagEntityType = "institute";
				        }else{
		        			$tagEntityType = 'National-University';
				        }
			        	$this->taggingCMSLib->addTagsPendingMappingAction($tagEntityType,$listingIdChild,'Delete');
			        }

			         //Insert an entry in indexlog table
				     $this->institutepostingmodel->updateIndexLog($instituteIds,$listingType,'delete');

			        // Maintain Flat Table
		        	if(!empty($listingTypeId)){
		        		$this->instituteFlatTableLib->flatTableUpdateOnInstDelete($listingTypeId);
		        		if(!empty($instituteIds)){
		        			$this->instituteFlatTableLib->flatTableUpdateOnInstDelete($instituteIds);		
		        		}
		        	}
		        	error_log('::::::::'.$listingType.':::'.$listingId.' has been deleted :::::::',3,"/tmp/deleteListingsCron.log");
		        }else{
		        	error_log('::::::::Institute Obj doesn\'nt exist for:::::::'.$listingId,3,"/tmp/deleteListingsCron.log");
	        	}
	        	error_log('::::::::Institute deletion done for:::::::'. $listingId,3,"/tmp/deleteListingsCron.log");

       		}


    	}

    }

	function inconsitentListingUsername(){

    	$listingcommonmodel = $this->load->model("listingCommon/listingcommonmodel");


    	// get all institutes/university modified after posting live date
    	$possibleCorruptInstituteIds = $listingcommonmodel->getListingUpdatedAfterDate('2016-10-01');
    	_p("count 1 : ".count($possibleCorruptInstituteIds));
    	
    	// get all listings that have more than 1 username including history entries
 		$possibleCorruptInstituteIds = $listingcommonmodel->getListingHavingMultipleUsername($possibleCorruptInstituteIds);
 		_p("count 2 : ".count($possibleCorruptInstituteIds));
    	
    	// for all those listings get all courses, get all live usernames of those courses and if username of institute doesn't matched with any of those usernames then their is a problem
    	
    	$instituteUsername = $listingcommonmodel->getInstitutesUsername($possibleCorruptInstituteIds);
    	_p("count 3 : ".count($instituteUsername));

    	$instituteCoursesUsername = array();
    	$inconsistentInstitutes = array();
    	$paidInconsistent = array();
    	$inconsistentMatchingOldUsername = array();
    	$filteredInconsitentFullList = array();
    	$instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');
    	foreach ($possibleCorruptInstituteIds as $instituteId) {
    		$courses = $instituteDetailLib->getAllCoursesForInstitutes($instituteId, 'all', false);
    		$courses = $courses['courseIds'];

    		$coursesUsernames =  $listingcommonmodel->getCoursesUsername($courses);

    		$instituteCoursesUsername[$instituteId] = $coursesUsernames['username'];
    		$instituteCoursesUsername[$instituteId] = array_unique($instituteCoursesUsername[$instituteId]);

    		$usernameBefore = $listingcommonmodel->getListingUsernameBeforeDate($instituteId, '2016-10-01');

    		if(isset($instituteUsername[$instituteId]) && !in_array($instituteUsername[$instituteId], $instituteCoursesUsername[$instituteId])){

    			_p("Inconsistent for : ".$instituteId);
    			_p("Username : ".$instituteUsername[$instituteId]);
    			_p($instituteCoursesUsername[$instituteId]);
    			
    			$inconsistentInstitutes[] = $instituteId;

    			if(!empty($usernameBefore) && in_array($instituteUsername[$instituteId], $usernameBefore)){
    				_p("Matching old");
    				_p($usernameBefore);
    				$inconsistentMatchingOldUsername[] = $instituteId;
    			}
    			else{
    				$filteredInconsitentFullList[] = $instituteId;
    			}

    			if(in_array($coursesUsernames['pack_type'], array("1","2","375"))){
    				_p("paid listing");
    				$paidInconsistent[] = $instituteId;
    			}
    			_p("---------------------------");
    		}
    	}

    	// check $filteredInconsitentFullList institutes if there is any entry in listing main with same listing_type_id and listing_type not in institute,university_national. if no entry is found then this username is correct.
    	$finalInconsitentFullList = array();
    	foreach ($filteredInconsitentFullList as $instituteId) {
    		$usernames = $listingcommonmodel->getUsernameOfNonInstitute($instituteId);

    		if(!empty($usernames) && in_array($instituteUsername[$instituteId], $usernames)){
    			$finalInconsitentFullList[] = $instituteId;
    		}
    	}

    	_p("Total Inconsistent : ".count($inconsistentInstitutes));
    	// _p($inconsistentInstitutes);

    	_p("Total Inconsistent with matching old username : ".count($inconsistentMatchingOldUsername));
    	// _p($inconsistentMatchingOldUsername);

    	_p("Filtered List of Inconsitent Institutes : ".count($filteredInconsitentFullList));
    	_p($filteredInconsitentFullList);

    	_p("Final List of Inconsitent Institutes : ".count($filteredInconsitentFullList));
    	_p($finalInconsitentFullList);

    	_p("Total Paid Inconsistent : ".count($paidInconsistent));
    	_p($paidInconsistent);
    	
    }

	function fixMesageTableData(){

    	ini_set('memory_limit','-1');
        set_time_limit(0);
        $this->listingcommonmodel = $this->load->model('listingCommon/listingcommonmodel');
        if (($handle = fopen("/var/www/html/shiksha/public/deletionCourseData.csv", "r")) !== FALSE) {
		    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		    	$courseArray[trim($data[0])] = trim($data[1]);
		        
		    }
		    fclose($handle);
		}

    	foreach($courseArray as $courseId=>$newCourseId){
    		$allCourseIds[] = $courseId;
    	}

    	$this->listingcommonmodel->fixAllCorruptedMsgIdsInMessageTable($allCourseIds);

    }

    /**
    	below function is used for invalidating Nginx cache for listings
    */
    function invalidateNginxCache()
    {
    	$url = !empty($_POST['seo_url'])?$_POST['seo_url']:'';
    	if(!empty($url))
    	{	
    		$this->invalidteNginxCacheUsingCurl($url);
    		$this->invalidteNginxCacheUsingCurl($url,'device_mobile');
    	}
    }
    function invalidteNginxCacheUsingCurl($url,$device = 'device_desktop')
    {
    		$c = curl_init();
        	curl_setopt($c, CURLOPT_URL,$url."?device_cache=".$device);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($c, CURLOPT_HTTPGET, 1);
            curl_setopt($c, CURLOPT_HEADER, true);
            curl_setopt($c, CURLOPT_HTTPHEADER, array('shiksha-refresh-cache' => "1",'shiksha-device-cache' => $device, "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8", "Cache-Control: max-age=0" , "Accept-Language: en-US,en;q=0.8", "Connection: keep-alive"
                    ));
            curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');
            curl_setopt($c,CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36');
            //curl_setopt($c, CURLOPT_NOBODY, TRUE); // remove body 
			//curl_setopt($c, CURLINFO_HEADER_OUT, true);
			//curl_setopt($c, CURLOPT_VERBOSE, true);

	        $output =  curl_exec($c);
	        $header_size = curl_getinfo($c);
		    if(strpos($output, 'id=footerExecuted') === FALSE)
		    {
		    	error_log('Nginx Cache is not successfully updated for following url in '.$device.' : '.$url);
		    }else
		    {
		    	error_log('Nginx Cache is successfully updated for following url in '.$device.' : '.$url);
		    }
    }
    function updateCoursePageOnGoogleCDNcacheAMP()
    {

		ini_set("memory_limit", "6000M");
        ini_set('max_execution_time', -1);
        $listingscriptsmodel = $this->load->model("listingscriptsmodel");   

    	$courseIdsArr = $listingscriptsmodel->fetchCourses();
        $totalCount = count($courseIdsArr);
        if($totalCount > 0)
        {
        	$this->load->builder("nationalCourse/CourseBuilder");
	        $courseBuilder = new CourseBuilder();
	        $this->courseRepo = $courseBuilder->getCourseRepository();
        	foreach ($courseIdsArr as $key => $courseId) {
        		$courseObj = $this->courseRepo->find($courseId,array('basic'));
        		if(!empty($courseObj))
        		{
        			$ampURL = $courseObj->getAmpURL();
        			if(!empty($ampURL))
        			{
        				updateGoogleCDNcacheForAMP($ampURL);
        				error_log('updated Google CDN Cache For Course = '.$courseId);	
        			}
        		}
        	}
        }
    }
    function updateInstituteUniveristyPageOnGoogleCDNcacheAMP($listing_type='all')
    {
    	ini_set("memory_limit", "6000M");
        ini_set('max_execution_time', -1);
        $listingscriptsmodel = $this->load->model("listingscriptsmodel"); 

    	$instituteIdArr = $listingscriptsmodel->fetchInstitutesUniversities($listing_type);
        $totalCount = count($instituteIdArr);
        if($totalCount > 0)
        {
        	$this->load->builder("nationalInstitute/InstituteBuilder");

        	$instituteBuilder = new InstituteBuilder();
        	$this->instituteRepo = $instituteBuilder->getInstituteRepository();

        	foreach ($instituteIdArr as $key => $instituteId) {
        		$instituteObj = $this->instituteRepo->find($instituteId,array('basic'));
        		if(!empty($instituteObj))
        		{
        			$ampURL = $instituteObj->getAmpURL();
        			if(!empty($ampURL))
        			{
        				updateGoogleCDNcacheForAMP($ampURL);
        				error_log('updated Google CDN Cache For Institute/University ='.$instituteId);
        			}
        		}
        	}
        }
    }

}
