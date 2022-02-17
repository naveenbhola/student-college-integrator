<?php
class ListingScripts extends MX_Controller {

	function init() {
        $this->courseLevelArray = array('degree'=>array('under graduate'=>'under graduate degree','post graduate'=>'post graduate degree'),
                                        'diploma'=>array('diploma'=>'diploma','post graduate diploma'=>'post graduate diploma'),
                                        'dual degree'=>array('under graduate'=>'under graduate degree','post graduate'=>'post graduate degree'),
                                        'exam preparation',
                                        'vocational',
                                        'certification');
        $this->courseTypeArray = array('full time','part time','correspondence','e-learning');
		$this->load->helper(array('form', 'url'));
        $this->load->library(array('miscelleneous','message_board_client','blog_client','ajax','category_list_client','listing_client','register_client','alerts_client','keyPagesClient'));
    }

    function clearCache($product,$master=0){
		$this->load->library('cacheLib');
        $cacheLib  =  new CacheLib();
	echo $cacheLib->clearCache($product,$master);
    }


    function clearCategoryPageCache($categoryPageData)
    {
	    if($categoryPageData) {
		    $categoryPageData = explode('-',$categoryPageData);
		    if(count($categoryPageData) == 5) {
			    $data = array(
					    'categoryId' => $categoryPageData[0],
					    'subCategoryId' => $categoryPageData[1],
					    'cityId' => $categoryPageData[2],
					    'stateId' => $categoryPageData[3],
					    'countryId' => $categoryPageData[4]
					 );

			    $this->load->library('categoryList/CategoryPageRequest');
			    $categoryPageRequest = new CategoryPageRequest();
			    $categoryPageRequest->buildFromCustomData($data);

			    $this->load->library('listing/ListingCache');
			    $this->listingcache->deleteCategoryPageInstitutes($categoryPageRequest);
		    }
	    }
    }


    function updateQuestionsCountry($countryName,$countryId){
	    set_time_limit(0);
	    $this->init();
	    $appId = 1;

        $this->load->library('upload_client');
        $curlClient = new Upload_client();
        $msgBrdClient = new Message_board_client();
        $flagToContinue = 1;

        $numResults = 100;
        $start = 0;
        while($flagToContinue){
            $searchUrl = "http://".SHIKSHACLIENTIP."/shikshaSearchApi?keyword=question+$countryName&startCount=$start&rowCount=$numResults&listingType=question&outputType=json";
            echo $searchUrl;
            echo "<br/>";
            $data = $curlClient->makeCurlCall(array(),$searchUrl);
            $fullresultArray = json_decode($data,true);
            //        echo "<pre>";        print_r($fullresultArray);        echo "</pre>";
            $resultSet = $fullresultArray['resultList'];
            $updateArray = array();
            for($i = 0 ;$i < count($resultSet) ; $i++){
                $updateArray[$i]['threadId'] = $resultSet[$i]['typeId'];
                $updateArray[$i]['countryId'] = $countryId;
            }
            if(count($resultSet) < $numResults){
                $flagToContinue = 0;
            }
            $start += $numResults;
            echo "<pre>";        print_r($updateArray);        echo "</pre>";
            $response = $msgBrdClient->updateCountry($updateArray);
            echo "<pre>";        print_r($response);        echo "</pre>";
        }
    }

    function updateCourseType($type_id){
        $this->init();
        $appId = 1;

        $this->load->library('upload_client');
        $curlClient = new Upload_client();
        $ListingClientObj = new Listing_client();
        $listing_type = 'course';
        error_log("CONTROLLER getCityList APP ID=> $appId :: $type_id  $listing_type");
        $listingDetails = $ListingClientObj->getListingDetails($appId,$type_id,$listing_type);
        $details = $listingDetails[0];
//                echo "<pre>";print_r($details); echo "</pre>";
        $numResults = 20;
        $searchUrl = "http://linuxcp10201.dn.net/shikshaSearchApi?keyword=".urlencode($details['title'])."&startCount=0&rowCount=".$numResults."&listingType=course&outputType=json";
        echo $searchUrl;
        echo "<br/>";
        echo $details['title'];
        echo "<br/>";
        echo $details['course_type'];
        echo "<br/>";
        echo $details['course_level'];
        echo "<br/>";
        echo $details['duration'];
        echo "<br/>";
        $data = $curlClient->makeCurlCall(array(),$searchUrl);
//        echo "<pre>";        print_r(json_decode($data));        echo "</pre>";
        $fullresultArray = json_decode($data,true);
        $courseTypeMap = array();
        $courseLevelMap = array();
        $resultArray = $fullresultArray['resultList'];
        for($i = 0; $i < count($resultArray); $i++){
            if(strlen($resultArray[$i]['courseType']) > 0){
                $courseTypeMap[$resultArray[$i]['courseType']]++;
            }
            if(strlen($resultArray[$i]['courseLevel']) > 0){
                $courseLevelMap[$resultArray[$i]['courseLevel']]++;
            }
        }

        arsort($courseTypeMap);
        arsort($courseLevelMap);
        echo "<pre>";
        print_r($courseTypeMap);
        print_r($courseLevelMap);
        echo "</pre>";
        echo "\n";
        echo "<br/>";
        $totalCourseTypes = 0;
        $totalCourseLevels = 0;
        foreach($courseTypeMap as $key=>$val){
            $totalCourseTypes +=$val;
        }
        foreach($courseLevelMap as $key=>$val){
            $totalCourseLevels +=$val;
        }

        foreach($courseLevelMap as $key=>$val){
            if($val > $totalCourseLevels/2){
                echo "Predicted Course Level:::$type_id ::: ".$details['title']."::: ".$key;
                echo "<br/>";
                break;
            }
            else
                break;
        }
        foreach($courseTypeMap as $key=>$val){
            if($val > $totalCourseTypes/2){
                echo "Predicted Course Type:::$type_id ::: ".$details['title']."::: ".$key;
                echo "<br/>";
                break;
            }
            else
                break;
        }
    }

    function deleteAndCreateEvent(){
        $this->init();
        $appId = 1;

        $this->load->library('event_cal_client');
        $ListingClientObj = new Listing_client();
        $type_id = $this->uri->segment(3);
        $listing_type = $this->uri->segment(4);
        error_log_shiksha("CONTROLLER getCityList APP ID=> $appId :: $type_id  $listing_type");
        $listingDetails = $ListingClientObj->getListingDetails($appId,$type_id,$listing_type);
        $editNotificationData = $listingDetails[0];
        echo "<pre>";print_r($editNotificationData); echo "</pre>";
        echo "deleting already created events";
        $eventObj = new Event_cal_client();
        $indexResponse = $eventObj->deleteListingEvent($appId,$type_id,$listing_type);
        echo "<pre>";print_r($indexResponse);echo "</pre>";
        $joinGroupInfo = $editNotificationData['instituteArr'];
        $locationsArr = $joinGroupInfo[0]['locations'];
        $collegeName = $joinGroupInfo[0]['institute_name'];
        $collegeName = str_replace('-',' ',$collegeName);
        //echo "<pre>";print_r($joinGroupInfo); echo "</pre>";
    	//add event if dates are set

        $instituteUrl = $editNotificationData['instituteArr'][0]['url'];
    	if(isset($editNotificationData['application_brochure_start_date']) && $editNotificationData['application_brochure_start_date'] != "0000-00-00 00:00:00")
    	{
    		$eventArray = array();
    		$eventArray['email'] = ($editNotificationData['contact_email']!="")?$editNotificationData['contact_email']:ADMIN_EMAIL;
            $eventArray['description'] = htmlspecialchars_decode($editNotificationData['desc']);
            if(strlen($editNotificationData['application_procedure']) > 0 ){
                $eventArray['description'] .= " <br/><b>Application Process:</b> ".htmlspecialchars_decode($editNotificationData['application_procedure'])." <br/>";
            }
            if(strlen($editNotificationData['application_fees'])> 0 ){
                $eventArray['description'] .= " <b> Fees: </b> ".$editNotificationData['application_fees']." <br/>";
            }
            if(strlen($joinGroupInfo[0]['institute_name'])>0){
                $eventArray['description'] .= " <br/> <br/> <b><a href='$instituteUrl'> ".$joinGroupInfo[0]['institute_name']."</a> </b> <br/>";
            }

            $eventArray['user_id'] = 1;
/*            $cats = $_REQUEST['c_categories'];
            $eventArray['board_id'] = $cats[0];*/


            //categories in event from arr
            $eventArray['board_id'] = $editNotificationData['categoryArr'][0]['category_id'];
            for($i = 1; $i < count($editNotificationData['categoryArr']); $i++){
                $eventArray['board_id'] = ",".$editNotificationData['categoryArr'][$i]['category_id'];
            }

            $eventArray['contact_person'] = $editNotificationData['contact_name'];
    		$eventArray['fax'] = $editNotificationData['contact_fax'];
    		$eventArray['phone'] = $editNotificationData['contact_cell'];
            $eventArray['start_date'] = $editNotificationData['application_brochure_start_date'];
            if(isset($editNotificationData['application_brochure_end_date']) && $editNotificationData['application_brochure_end_date'] != "0000-00-00 00:00:00")
            {
                $eventArray['end_date'] = $editNotificationData['application_brochure_end_date'];
            }else{
                $eventArray['end_date'] = $eventArray['start_date'];
            }
            echo $eventArray['end_date'];
            if(isset($editNotificationData['application_end_date']) && $editNotificationData['application_end_date'] != "0000-00-00 00:00:00")
            {
                $eventArray['end_date'] = $editNotificationData['application_end_date'];
            }
            //$eventArray['end_date'] = $editNotificationData['application_end_date']?$editNotificationData['application_end_date']:$eventArray['end_date'];
            echo $eventArray['end_date'];
            $eventTitle = str_replace('-',' ',$editNotificationData['title'])." - $collegeName - Sale of forms";
            $eventTitle .=" - ".date(' d F ',strtotime($editNotificationData['application_brochure_start_date']));
            $eventTitle = $editNotificationData['application_end_date']? $eventTitle." - Submission of forms till "." - ".date(' d F ',strtotime($editNotificationData['application_end_date'])): $eventTitle;
            $eventArray['event_title'] = $eventTitle;
            $locations = array();
            for($i=0;$i<count($locationsArr);$i++) {
                array_push($locations,array(
                            array(
                                'Address_Line1'=>array($locationsArr[$i]['address'],'string'),
                                'city'=>array($locationsArr[$i]['city_id'],'string'),
                                'zip'=>array($locationsArr[$i]['zip'],'string'),
                                'country'=>array($locationsArr[$i]['country_id'],'string'),
                                'email'=>array($eventArray['email'],'string'),
                                'contact_person'=>array($eventArray['contact_person'],'string'),
                                'fax' => array($editNotificationData['contact_fax'],'string'),
                                'phone' => array($editNotificationData['contact_cell'],'string')
                                ),'struct')
                        );//close array_push
            }
            $this->load->library('message_board_client');
            $msgbrdClient = new Message_board_client();
            $topicDescription = "You can discuss on this event below";
            $requestIp = S_REMOTE_ADDR;
            $topicResult = $msgbrdClient->addTopic($appID,1,$topicDescription,$eventArray['board_id'],$requestIp,'event');
            $eventArray['threadId'] = $topicResult['ThreadID'];
            echo "<pre>";print_r($eventArray); echo "</pre>";
    		$eventResponse = $eventObj->addEventNew($appId,$eventArray,1,$locations,$editNotificationData['admission_notification_id'],'notification');
            echo "<pre>";print_r($eventResponse); echo "</pre>";
    	}
        if($editNotificationData['entrance_exam'] == "yes" ) {
            if(isset($editNotificationData['exam_info'][0]['exam_date']) && $editNotificationData['exam_info'][0]['exam_date'] != "0000-00-00 00:00:00")
            {
                if (count($editNotificationData['exam_centres_info'])>0)
                {
                    $locations = array();
                    $this->load->library('event_cal_client');
                    $eventArray = array();
                    $eventArray['email'] = ($editNotificationData['contact_email']!="")?$editNotificationData['contact_email']:ADMIN_EMAIL;
                    $eventArray['description'] = htmlspecialchars_decode($editNotificationData['desc']);
                    if(strlen($editNotificationData['application_procedure']) > 0 ){
                        $eventArray['description'] .= " <br/><b>Application Process:</b> ".htmlspecialchars_decode($editNotificationData['application_procedure'])." <br/>";
                    }
                    if(strlen($editNotificationData['application_fees'])> 0 ){
                        $eventArray['description'] .= " <b> Fees: </b> ".$editNotificationData['application_fees']." <br/>";
                    }
                    if(strlen($joinGroupInfo[0]['institute_name'])>0){
                        $eventArray['description'] .= " <br/> <br/> <b><a href='$instituteUrl'> ".$joinGroupInfo[0]['institute_name']."</a> </b> <br/>";
                    }

                    $eventArray['user_id'] = 1;
                    $eventArray['contact_person'] = $editNotificationData['contact_name'];
                    $eventArray['fax'] = $editNotificationData['contact_fax'];
                    $eventArray['phone'] = $editNotificationData['contact_cell'];
                    $eventArray['board_id'] = $editNotificationData['categoryArr'][0]['category_id'];
                    for($i = 1; $i < count($editNotificationData['categoryArr']); $i++){
                        $eventArray['board_id'] = ",".$editNotificationData['categoryArr'][$i]['category_id'];
                    }
                    $str = $editNotificationData['title'];
                    $exam_name = $editNotificationData['exam_info'][0]['exam_name']?$editNotificationData['exam_info'][0]['exam_name']:' Exam ';
                    $eventArray['event_title'] = "$exam_name - Date ".date(' d F Y ',strtotime($editNotificationData['exam_info'][0]['exam_date']))." - ".str_replace('-',' ',$str)." - $collegeName";
                    $eventArray['start_date'] = $editNotificationData['exam_info'][0]['exam_date'];
                    $eventArray['end_date'] = $editNotificationData['exam_info'][0]['exam_date'];
                    for($i=0;$i<count($editNotificationData['exam_centres_info']);$i++) {
                        array_push($locations,array(
                                    array(
                                        'Address_Line1'=>array($editNotificationData['exam_centres_info'][$i]['address_line1'],'string'),
                                        'city'=>array($editNotificationData['exam_centres_info'][$i]['city_id'],'string'),
                                        'zip'=>array($editNotificationData['exam_centres_info'][$i]['zip'],'string'),
                                        'country'=>array($editNotificationData['exam_centres_info'][$i]['country_id'],'string'),
                                        'email'=>array($eventArray['email'],'string'),
                                        'contact_person'=>array($eventArray['contact_person'],'string'),
                                        'fax' => array($editNotificationData['contact_fax'],'string'),
                                        'phone' => array($editNotificationData['contact_cell'],'string')
                                        ),'struct')
                                );//close array_push
                    }
                    error_log_shiksha("NEWEVENT ".print_r($eventArray,true));

                    $this->load->library('message_board_client');
                    $msgbrdClient = new Message_board_client();
                    $topicDescription = "You can discuss on this event below";
                    $requestIp = S_REMOTE_ADDR;
                    $topicResult = $msgbrdClient->addTopic($appID,1,$topicDescription,$eventArray['board_id'],$requestIp,'event');
                    $eventArray['threadId'] = $topicResult['ThreadID'];

                    $eventResponse = $eventObj->addEventNew($appId,$eventArray,3,$locations,$editNotificationData['admission_notification_id'],'notification');
                    echo "<pre>";print_r($eventResponse); echo "</pre>";
                    error_log_shiksha("NEWEVENT ADD ADMISSION LISTING : create event for exam centre RESPONSE : " .print_r($eventResponse,true));
                }
            }
        }

    }

    function mailSend()
    {
        $this->init();
        $appId = 1;
        $ListingClientObj = new Listing_client();
        $type_id = $this->uri->segment(3);
        $listing_type = $this->uri->segment(4);
        error_log_shiksha("CONTROLLER getCityList APP ID=> $appId :: $type_id  $listing_type");
        $listingDetails = $ListingClientObj->getListingDetails($appId,$type_id,$listing_type);

        $details = $listingDetails[0];
        $email = $details['contact_email'];
        $fromMail = "enterprise@shiksha.com";
        $ccmail = "sales@shiksha.com";
        $mail_client = new Alerts_client();
        $subject = "Your Listing on Shiksha.com, Listing Id- ".$listing_type."-".$type_id;
        $data['listingType'] = $listing_type;
        $data['listingTitle'] = $details['title'];
        $data['listingUrl'] = "http://www.shiksha.com/getListingDetail/".$type_id."/".$listing_type;
        $content = $this->load->view('common/mailTBS',$data,true);
        $response=$mail_client->externalQueueAdd("12",$fromMail,$email,$subject,$content,$contentType="html",$ccmail);
        print_r($response);
    }

function updateTopicForListing(){
		$this->init();
		$appId = 1;

		$ListingClientObj = new Listing_client();
		$type_id = $this->uri->segment(3);
		$listing_type = $this->uri->segment(4);
		error_log_shiksha("CONTROLLER getCityList APP ID=> $appId :: $type_id  $listing_type");
		$listingDetails = $ListingClientObj->getListingDetails($appId,$type_id,$listing_type);

		$details = $listingDetails[0];
		$countryList = array();
		$cityList = array();
        $fromOthers = 'group';
		$toBeIndex = 1;
		$topicCat = 1;
        $userId = 1;
		$msgbrdClient = new message_board_client();
		if($listing_type == "institute"){
			$topicResult = $msgbrdClient->addTopic($appId,$userId,$details['title'],$topicCat,'127.0.0.1',$fromOthers,$type_id, $listing_type, $toBeIndex);
			$newthreadId= $topicResult['ThreadID'];
		}
        if($newthreadId > 0){
            $response = $ListingClientObj->updateThreadId(1,$type_id,$listing_type,$newthreadId);
        }else {
            echo "ERROR:threadId:".$newthreadId;
        }
    }

	function indexListing(){
		$appId = 1;
		$this->init();
		$displayData = array();
		$ListingClientObj = new Listing_client();
		$type_id = $this->uri->segment(3);
		$listing_type = $this->uri->segment(4);
		error_log_shiksha("CONTROLLER getCityList APP ID=> $appId :: $type_id  $listing_type");

                $searchFlag = 1;
                
        if($listing_type == "instituteGroups")
        {
            $listingDetails = $ListingClientObj->getListingDetails($appId,$type_id,"institute", "", 0, $searchFlag);
        }
		elseif($listing_type == "institute")
        {
            $listingDetails = $ListingClientObj->getListingDetails($appId,$type_id,"institute", "", 0, $searchFlag);
		}
        else
        {
            $listingDetails = $ListingClientObj->getListingDetails($appId,$type_id,$listing_type, "", 0, $searchFlag);
        }
		switch($listing_type){
	    case "institute":
		$luceneArr = $this->indexInstitute($listingDetails[0]);
	    	break;
            case "course":
                $listingDetails = $ListingClientObj->getListingDetails($appId,$listingDetails[0]['institute_id'],"institute",  "", 0, $searchFlag);
                $listing_type = "institute";
                $luceneArr = $this->indexInstitute($listingDetails[0]);
                break;
            case "scholarship":
				$luceneArr = $this->indexScholarship($listingDetails[0]);
			break;
			case "notification":
				$luceneArr = $this->indexNotification($listingDetails[0]);
			break;
            case "instituteGroups":
                $luceneArr = $this->indexCollegeNetwork($listingDetails[0]);
            break;
		}
		_p("lucene array");
		_p($luceneArr);
		$luceneResponse = $ListingClientObj->indexListing(1,$luceneArr);
		$courseDetailsForIndexing = array();
        if($listing_type == 'institute') {
			_p("individual course indexing starts");
			$courseDetailsForAutosuggestorIndexing = $this->_getCourseDataForIndexing($listingDetails[0]);
			
			$institutePackType = $luceneArr['packtype'];
			_p("oldinstitutepacktype: " . $institutePackType);
			$institutePaidStatus = false;
			foreach($this->_getCourseDataForIndexing($listingDetails[0]) as $course) {
				$coursePackType = $course['course_pack_type'];
				if($coursePackType > 0 && $coursePackType != 7){
					   $institutePaidStatus = true;
					   break;
				}
			}
			if($institutePaidStatus){
			  $institutePackType = 1;
			}
			_p("newinstitutepacktype: " . $institutePackType);
			foreach($this->_getCourseDataForIndexing($listingDetails[0]) as $course) {
				_p("individual course data");
				_p($course);
                $courseArr = $luceneArr;
				$courseArr['packtype'] = $institutePackType;
                $courseArr['Id'] = $course['course_id'];
				$courseArr['instituteId']=$listingDetails[0]['institute_id'];
				$courseArr['type'] = 'course';
                if(isset($course['course_type_display']))
                {
                    $courseArr['cType'] = $this->cleanCSVField($course['course_type_display']);
                }
                else
                {
                    $courseArr['cType'] = "Others";
                }
                if(isset($course['course_level_display']))
                {
                    $courseArr['courseLevel'] =  $this->cleanCSVField($course['course_level_display']);
                }
                else
                {
                    $courseArr['courseLevel'] = "Others";
                }
				$courseArr['courseTitle'] = $course['courseTitle'];
				$courseArr['ldb_course_id'] = $this->getLDBIdForCourseId($course['course_id']);
				$ldb_course_details = array();
				foreach($courseArr['ldb_course_id'] as $key=>$value){
					$ldb_course_details[] = $this->getLdbCourseDetailsForLdbId($value);
				}
				$courseArr["ldb_specialization_name"] = "";
				$courseArr["ldb_course_name"] = "";
				$courseArr["ldb_category_name"] = "";
				foreach($ldb_course_details as $key=>$value){
					$courseArr["ldb_specialization_name"] = $value["ldb_specialization_name"];
					$courseArr["ldb_course_name"] = $value["ldb_course_name"];
					$courseArr["ldb_category_name"] = $value["ldb_course_name"];
				}
				$durationArr = $this->getNormalizedDuration($course['duration_value']." ".$course['duration_unit']);	 
				$courseArr['sduration'] = $durationArr[0];	
				$luceneResponse = $ListingClientObj->indexListing(1,$courseArr);
				_p("lucene response for course index");
				_p($luceneResponse);
            }
			$this->indexAutosuggestorData($luceneArr, $courseDetailsForAutosuggestorIndexing, $listingDetails[0]['institute_id']);
		}
	}
	
	function indexAutosuggestorData($luceneArr, $data, $instituteId){
		$validAutosuggestorFields = array("type", "instituteId", "original_id", "instituteList", "Id", "ldb_course_name", "ldb_specialization_name", "ldb_category_name", "cType", "courseLevel", "countryList", "state", "cityList");
		_p("in indexAutosuggestor");
		if(!empty($data)){
			$ListingClientObj = new Listing_client();
			foreach($data as $course) {
				$ldb_course_ids = $this->getLDBIdForCourseId($course['course_id']);
				_p("ldb_course_ids");
				_p($ldb_course_ids);
				$ldb_course_details = array();
				$count = 0;
				foreach($ldb_course_ids as $ldb_course_id){
					$ldb_course_details = $this->getLdbCourseDetailsForLdbId($ldb_course_id, true);
					$ldb_specialization_names = $ldb_course_details['ldb_specialization_name'];
					$ldb_course_names = $ldb_course_details['ldb_course_name'];
					$ldb_category_names = $ldb_course_details['ldb_category_name'];
					_p("ldb_course_details");
					_p($ldb_course_details);
					foreach($ldb_course_names as $ldb_co_name){ //for every ldb course name 
						foreach($ldb_specialization_names as $ldb_sp_name){ // for every ldb specialization name
							foreach($ldb_category_names as $ldb_cat_name){ // for every ldb category name
								$courseArr = $luceneArr;
								if($count == 0){
									$courseArr['Id'] = $course['course_id'];	
								} else {
									$courseArr['Id'] = $course['course_id'] . "_" . $count;
								}
								$courseArr['original_id'] = $course['course_id'];
								$courseArr['instituteId'] = $instituteId;
								$courseArr['type'] = 'autosuggestor';
								if(isset($course['course_type_display'])) {
									$courseArr['cType'] = $this->cleanCSVField($course['course_type_display']);
								} else {
									$courseArr['cType'] = "Others";
								}
								if(isset($course['course_level_display'])) {
									$courseArr['courseLevel'] =  $this->cleanCSVField($course['course_level_display']);
								} else {
									$courseArr['courseLevel'] = "Others";
								}
								$courseArr['courseTitle'] = $course['courseTitle'];
								$courseArr['ldb_course_id'] = $ldb_course_id;
								$courseArr["ldb_specialization_name"] = $ldb_sp_name;
								$courseArr["ldb_course_name"] = $ldb_co_name;
								$courseArr["ldb_category_name"] = $ldb_cat_name;
								
								$durationArr = $this->getNormalizedDuration($course['duration_value']." ".$course['duration_unit']);
								$courseArr['sduration'] = $durationArr[0];
								$count++;
								
								$autoSuggestorArray = array();
								foreach($courseArr as $key=>$value){
									if(in_array($key, $validAutosuggestorFields)){
										$autoSuggestorArray[$key] = $value;
									}
								}
								$luceneResponse = $ListingClientObj->indexListing(1,$autoSuggestorArray);
								_p("lucene response for autosuggestor");
								_p($luceneResponse);
							}	
						}
					}
				}
            }
		}
	}
	
	function getDbHandle() {
                $appId = 1;
                $this->load->library('listingconfig');
                $dbConfig = array( 'hostname'=>'localhost');
                $this->listingconfig->getDbConfig_test($appId,$dbConfig);
                $dbHandle = $this->load->database($dbConfig,TRUE);
                if($dbHandle == ''){
                        error_log('error occurred...can not create db handle');
                }
                return $dbHandle;
        }

	function getLDBIdForCourseId($courseId){
		$ListingClientObj = new Listing_client();
		$ldbCourseId = $ListingClientObj->getLDBIdForCourseId($courseId);
		return $ldbCourseId;
	}
	
	function getLdbCourseDetailsForLdbId($ldb_id, $multiple = false){
		$ListingClientObj = new Listing_client();
		$ldb_details = $ListingClientObj->getLdbCourseDetailsForLdbId($ldb_id, $multiple);
		return $ldb_details;
	}
	

    function indexInstitute($details){
        $details['category_id'] = '';
        if(isset($details['categoryArr'][0])){
            $details['category_id'] = $details['categoryArr'][0]['category_id'];

            for($i =1; $i < count($details['categoryArr']); $i++){
                $details['category_id'] .= ",".$details['categoryArr'][$i]['category_id'];

            }
        }
        /*  echo "<pre>";
        print_r($details);
        echo "</pre>";*/
        $countryList = array();
        $cityList = array();
        for($i = 0; $i < count($details['locations']) ; $i++)
        {
            $countryList[$i] = $details['locations'][$i]['country_id'];
            $cityList[$i] = $details['locations'][$i]['city_id'];
        }

        $luceneData = array();
        if($details['crawled'] == 'crawled'){
            $luceneData['hack'] = 'craaaaawled';
        }
        else{
            $luceneData['hack'] = '';
        }

        $luceneData['title'] = $details['title'];
        $luceneData['packtype'] = $details['packType'];
        $luceneData['type'] = 'institute';
        $luceneData['Id'] = $details['institute_id'];
        $luceneData['categoryList'] = $details['category_id'];
        $luceneData['countryList'] = implode(',',$countryList);
        $luceneData['cityList'] = implode(',',$cityList);
        $luceneData['imageUrl'] = trim($details['institute_logo']);
        $luceneData['timestamp'] = $details['timestamp'];
        $luceneData['tags'] = $details['tags'];
        $luceneData['hiddenTags'] = $details['hiddenTags'];
        $luceneData['courseType'] = $details['tags'];
        $luceneData['contact_email'] = $details['contact_email'];
        $luceneData['noOfViews'] = $details['viewCount'];
        $luceneData['seoUrl'] = $details['seoListingUrl'];	//Added by Ankur for adding SeoUrl in Lucene


        $wikiDetails = $this->_getWikiForIndexing($details);
        $luceneData1 =  array_merge($luceneData,$wikiDetails);
        //print_r($luceneData1);
        $luceneData = $luceneData1;
        $details['courselisting'] = $this->_getCourseDataForIndexing($details);
        $courseList = json_encode($details['courselisting']);
        //print_r($courseList);
        //print_r($luceneData);

        if(isset($details['instituteDuration']) && strlen(trim($details['instituteDuration']))>0)
        {
            $luceneData['duration'] = $details['instituteDuration'];
        }
        else
        {
            $luceneData['duration'] = "";
        }

        if(isset($details['instituteInterDuration']) && strlen(trim($details['instituteInterDuration']))>0)
        {
            $luceneData['iDuration'] = $details['instituteInterDuration'];
        }
        else
        {
            $luceneData['iDuration'] = $luceneData['duration'];
        }

        $details['course_type'] = "";
        $details['courseLevel'] = "";
        $courseTitle = array();
	//$courseSeo = array();		//Added by Ankur for adding SeoUrl in Lucene
        $c = 0;
        foreach($details['courselisting'] as $val)
        {
            echo 1;
            $courseTitle[$c] = $val['courseTitle'];
            //$courseSeo[$c] = $val['listing_seo_url']; 		//Added by Ankur for adding SeoUrl in Lucene
            $c++;
            /*if(in_array($val['course_type'],$this->courseTypeArray))
            {
                $details['course_type'] .= ",".$val['course_type'];
            }
            else
            {
                $details['course_type'] .= ",Others";
            }
            if(isset($this->courseLevelArray[$val['course_level']]))
            {
                $tempCLevel = $this->courseLevelArray[$val['course_level']];
                if(is_array($tempCLevel))
                {
                    if(isset($tempCLevel[$val['course_level_1']]))
                    {
                        $tempCLevel = $tempCLevel[$val['course_level_1']];
                        $details['courseLevel'] .= ",".$tempCLevel;
                    }
                    else
                    {
                        $details['courseLevel'] .= ",Others";
                    }
                }
                else
                {
                    $details['courseLevel'] .= ",".$tempCLevel;
                }
            }
            else
            {
                $details['courseLevel'] .= ",Others";
            }*/
            if(isset($val['course_type_display']))
            {
                $details['course_type'] .= ",".$val['course_type_display'];
            }
            else
            {
                $details['course_type'] .= ",Others";
            }
            if(isset($val['course_level_display']))
            {
                $details['courseLevel'] .= ",".$val['course_level_display'];
            }
            else
	    {
		    $details['courseLevel'] .= ",Others";
	    }
	    /*error_log("dhwaj ldb shiksha course id".$val['course_id']);
	    if(trim($val['shiksha_course_id'])!='')
	    {
		    error_log("dhwaj ldb SHISKHA_COURSE_ID ".$val['course_id']);
		    $details['ldb_course_id'][]=$this->getLDBIdForCourseId($val['course_id']);
                    error_log("dhwaj ldb ldb_course_id1 details indexInsti".print_r($details['ldb_course_id'], true));

	    }
	    $durationArr = $this->getNormalizedDuration($val['duration_value']." ".$val['duration_unit']);	
	    echo "sduration1 ".print_r($durationArr)."\n";
	    if(is_numeric($durationArr[0])) 
	    {
		    echo "sduration ".$durationArr[0]."\n";
		    $details['sduration'][]= $durationArr[0];
	    }*/
	}
	/*$luceneData['ldb_course_id'] = $details['ldb_course_id'];
	$luceneData['sduration'] = $details['sduration'];
        echo "LDB_COURSE_ID ".$details['ldb_course_id']."\n";*/
        if(isset($details['course_type']) && strlen(trim($details['course_type']))>0)
        {
            $luceneData['cType'] = $this->cleanCSVField($details['course_type']);
        }
        else
        {
            $luceneData['cType'] =  "Others";
        }
        if(isset($details['courseLevel']) && strlen(trim($details['courseLevel']))>0)
        {
            $luceneData['courseLevel'] = $this->cleanCSVField($details['courseLevel']);
        }
        else
        {
            $luceneData['courseLevel'] = "Others";
        }
	        echo "Type ".$luceneData['cType']."\n";
        echo "Level ".$luceneData['courseLevel']."\n";

        $courseTitles =implode(":::",$courseTitle);
        $luceneData['courseTitle'] = $courseTitles;
        //$luceneData['courseSeoUrl'] = $courseSeo;	//Added by Ankur for adding SeoUrl in Lucene

        $luceneData['courses'] = $courseList;
        $luceneData['userId'] = $details['userId'];
        return $luceneData;
    }

function indexCourse($details){
		$details['category_id'] = '';
		if(isset($details['categoryArr'][0])){
			$details['category_id'] = $details['categoryArr'][0]['category_id'];

			for($i =1; $i < count($details['categoryArr']); $i++){
				$details['category_id'] .= ",".$details['categoryArr'][$i]['category_id'];

			}
		}
		$luceneData = array();
        if($details['crawled'] == 'crawled'){
            $luceneData['hack'] = 'craaaaawled';
        }
        else{
            $luceneData['hack'] = '';
        }


	$luceneData['title'] = $details['title'];
	//$luceneData['content'] = $details['overview'];
	//$luceneData['courseContent'] = $details['contents'];
	$luceneData['courseType'] = $details['course_type'];
    $luceneData['contact_email'] = $details['contact_email'];
    //$courseLevelArray = array('Post Graduate Degree','Post Graduate Diploma','Under Graduate Degree','Under Graduate Diploma','Certification');
    //$courseTypeArray = array('Full time','Part time','Correspondence course','Exam Preparation');
    if(!in_array($details['course_type'],$this->courseTypeArray))
    {
        $details['course_type'] .= "Others";
    }
    if(in_array($details['course_level_1'],$this->courseLevelArray))
    {
        $details['courseLevel'] .= ",".$details['course_level_1'];
        if(in_array($details['course_level_2'],$this->courseLevelArray))
        {
            $details['courseLevel'] .= ",".$details['course_level_2'];
        }
    }
    elseif(in_array($details['course_level_2'],$this->courseLevelArray))
    {
        $details['courseLevel'] .= ",".$details['course_level_2'];
    }
    else
    {
        $details['courseLevel'] .= ",Others";
    }

	if(isset($details['course_type']) && strlen(trim($details['course_type']))>0)
	{
		$luceneData['cType'] = $this->cleanCSVField($details['course_type']);
	}
	else
	{
		$luceneData['cType'] =  "Others";
	}
	if(isset($details['course_level']) && strlen(trim($details['course_level']))>0)
	{
		$luceneData['courseLevel'] = $this->cleanCSVField($details['course_level']);
	}
	else
	{
		$luceneData['courseLevel'] = "Others";
	}
	echo "Type ".$luceneData['cType']."\n";
	echo "Level ".$luceneData['courseLevel']."\n";

	$luceneData['levels'] = $details['course_level'];
	$luceneData['packtype'] = $details['packType'];
	$luceneData['type'] = 'course';
	$luceneData['Id'] = $details['course_id'];
	//Added by Ankur for adding Seo url in Lucene
	if(isset($details['seoListingUrl']) && strlen(trim($details['seoListingUrl']))>0)
	    $luceneData['seoUrl'] = $details['seoListingUrl'];
	else
	    $luceneData['seoUrl'] = "";

	$luceneData['imageCount'] = count($details['photos']);
	$luceneData['videoCount'] = count($details['videos']);
    $luceneData['hiddenTags'] = $details['hiddenTags'];
	$luceneData['categoryList'] = $details['category_id'];
	$countryList = array();
	$cityList = array();
	for($i = 0; $i < count($details['locations']) ; $i++)
	{
		$countryList[$i] = $details['locations'][$i]['country_id'];
		$cityList[$i] = $details['locations'][$i]['city_id'];
	}
	$luceneData['countryList'] = implode(',',$countryList);
	$luceneData['cityList'] = implode(',',$cityList);
	$luceneData['instituteId'] = $details['institute_id'];
	$luceneData['fees'] = $details['fees'];
	$luceneData['duration'] = $details['duration'];
	if(isset($details['intermediateDuration']) && strlen($details['intermediateDuration'])){
		$luceneData['iDuration'] = $details['intermediateDuration'];
	}
	else{
		$luceneData['iDuration'] = $details['duration'];
	}
    	$luceneData['sduration'] = array($this->getNormalizedDuration($luceneData['iDuration']),'struct');
	if(is_numeric($luceneData['course_id']))
            {
                    error_log("1 dhwaj ldb SHISKHA_COURSE_ID ".$luceneData['course_id']);
                    $luceneData['ldb_course_id']=$this->getLDBIdForCourseId($luceneData['course_id']);
                    error_log("1 dhwaj ldb ldb_course_id".print_r($luceneData['ldb_course_id']));
            }

	$luceneData['timestamp'] = $details['timestamp'];
	$luceneData['tags'] = $details['tags'];
	$luceneData['userId'] = $details['userId'];
	return $luceneData;
	}

	function indexScholarship($details){
		$details['category_id'] = '';
		if(isset($details['categoryArr'][0])){
			$details['category_id'] = $details['categoryArr'][0]['category_id'];
			for($i =1; $i < count($details['categoryArr']); $i++){
				$details['category_id'] .= ",".$details['categoryArr'][$i]['category_id'];
			}
		}
        if(isset($details['eligibilityArr'][0]))
        {
            $details['eligibility']="";
            foreach($details['eligibilityArr'] as $row)
            {
                foreach($row as $value)
                {
                    if($i==0)
                    {
                        $details['eligibility'].=$value." ";
                        $i=1;
                    }
                    else
                    {
                        $details['eligibility'].=$value.":";
                        $i=0;
                    }
                }
            }
        }
//        print_r($details);
		$luceneData = array();
        if($details['crawled'] == 'crawled'){
            $luceneData['hack'] = 'craaaaawled';
        }
        else{
            $luceneData['hack'] = '';
        }


		$luceneData['title'] = $details['title'];
		$luceneData['content'] = $details['desc'];
		$luceneData['levels'] = $details['levels'];
		$luceneData['packtype'] = $details['packType'];
		$luceneData['type'] = 'scholarship';
		$luceneData['Id'] = $details['scholarship_id'];
		$luceneData['categoryList'] = $details['category_id'];
		$luceneData['countryList'] = $details['country_id'];
		$luceneData['cityList'] = $details['city_id'];
		$luceneData['instituteId'] = $details['institute_id'];
		//$luceneData['address'] = $details['address_line1']."  " . $details['address_line2'];
		//$luceneData['addressCountry'] = $details['country_id'];
		//$luceneData['addressCity'] = $details['city_id'];
		$luceneData['institutedBy'] = $details['institution'];
		$luceneData['value'] = $details['value'];
		$luceneData['number'] = $details['num_of_schols'];
		$luceneData['eligibility'] = $details['eligibility'];
		$luceneData['timestamp'] = $details['timestamp'];
		$luceneData['userId'] = $details['userId'];
        $luceneData['contact_email'] = $details['contact_email'];
		return $luceneData;
	}
	function indexNotification($details)
    {
		$details['category_id'] = '';
		if(isset($details['categoryArr'][0])){
			$details['category_id'] = $details['categoryArr'][0]['category_id'];
			for($i =1; $i < count($details['categoryArr']); $i++){
				$details['category_id'] .= ",".$details['categoryArr'][$i]['category_id'];
			}
		}
        $instituteList=array();
        $countryList=array();
        $cityList=array();
        foreach($details['instituteArr'] as $row)
        {
            $ListingClientObj = new Listing_client();
            $instituteDetails = $ListingClientObj->getListingDetails($appId,$row['institute_id'],'institute');
            $instituteList[count($instituteList)]=$instituteDetails[0]['institute_id'];
            for($i = count($countryList), $j=0; $i < count($instituteDetails[0]['locations']) ; $i++,$j++)
            {
                $countryList[$i] = $instituteDetails[0]['locations'][$j]['country_id'];
                $cityList[$i] = $instituteDetails[0]['locations'][$j]['city_id'];
            }
        }
/*        echo "<pre>";
        print_r($details);
        echo "</pre>";*/
        $luceneData = array();
        if($details['crawled'] == 'crawled'){
            $luceneData['hack'] = 'craaaaawled';
        }
        else{
            $luceneData['hack'] = '';
        }


		$luceneData['title'] = $details['title'];
		$luceneData['content'] = $details['desc'];
		$luceneData['admissionYear'] = $details['admission_year'];
		$luceneData['packtype'] = $details['packType'];
		$luceneData['type'] = 'notification';
		$luceneData['Id'] = $details['admission_notification_id'];
		$luceneData['categoryList'] = $details['category_id'];
		$luceneData['countryList'] = implode(',',$countryList);
		$luceneData['cityList'] = implode(',',$cityList);
		$luceneData['instituteId'] = implode(',',$instituteList);
		$luceneData['endDate'] = $details['application_end_date'];
		$luceneData['userId'] = $details['userId'];
        $luceneData['contact_email'] = $details['contact_email'];
		return $luceneData;
	}
	function indexCollegeNetwork($details){
		$countryList = array();
		$cityList = array();
        print_r($details);
		for($i = 0; $i < count($details['locations']) ; $i++)
		{
			$countryList[$i] = $details['locations'][$i]['country_id'];
			$cityList[$i] = $details['locations'][$i]['city_id'];
		}

        $luceneData = array();
        if($details['crawled'] == 'crawled'){
            $luceneData['hack'] = 'craaaaawled';
        }
        else{
            $luceneData['hack'] = '';
        }

        $luceneData['title'] = $details['title'];
        $luceneData['content'] = "";
        $luceneData['packtype'] = $details['packType'];
        $luceneData['type'] = 'collegegroup';
        $luceneData['Id'] = $details['institute_id'];
		$luceneData['countryList'] = implode(',',$countryList);
		$luceneData['cityList'] = implode(',',$cityList);
		$luceneData['imageUrl'] = trim($details['institute_logo']);
		$luceneData['timestamp'] = $details['timestamp'];
		$luceneData['userId'] = $details['userId'];
		return $luceneData;
	}

	function getNormalizedDuration($duration)
	{
		$durationArray=array();
		$durationList = explode(",","$duration");
		foreach ($durationList as $val)
		{
			$durationval=$this->checkDuration($val);
			if($durationval!=0)
			{
				$durationArray[]= $durationval;
			}
		}

		return $durationArray;
	}

	function checkDuration($duration)
	{
		$durationvalues = explode(" ",$duration);
		if(is_numeric($durationvalues['0']))
		{
			$durationval = $durationvalues['0']*$this->getDurationNormalizationCoefficient($durationvalues['1']);
		}
		else
		{
			$durationval = 0;
		}
		if($durationval>=240)
		{
			return (round($durationval/240))*240;
		}
		else if ($durationval>=20)
		{
			return (round($durationval/20))*20;
		}
		else if ($durationval>=5)
		{
			return (round($durationval/5))*5;
		}
		else if ($durationval>=1)
		{
			return (round($durationval/1))*1;
		}
		else
		{
			return (round($durationval*8))/8;
		}


		echo "Durationvalues".print_r($durationvalues)."Durationval ".$durationval."\n";
		return $durationval;
	}

	function getDurationNormalizationCoefficient($durationType)
	{
		$normalizationCoeff = 0;
		switch(strtolower($durationType))
		{
			case "week":
			case "weeks":
				$normalizationCoeff = 5;
				break;
			case "year":
			case "years":
				$normalizationCoeff = 240;
				break;
			case "hour":
			case "hours":
				$normalizationCoeff = 1/8;
				break;
			case "month":
			case "months":
				$normalizationCoeff = 20;
				break;
			case "day":
			case "days":
				$normalizationCoeff = 1;
				break;
		}
		echo "normalizationCoeff ".$normalizationCoeff."\n";
		return $normalizationCoeff;
	}

    function cleanCSVField($str){
		$str=trim(preg_replace("/^,/","",trim($str)),"-");
		$tags=explode(",",$str);
		$tagList="";
		foreach($tags as $tag)
		{
			if(strlen(trim($tag))>0)
			{
				$tag=trim(preg_replace("/[^A-Za-z0-9,]/","-",trim($tag)),"-");
				$tagList=($tagList=="")?$tag:$tagList.",".trim($tag);
			}
			else
            {
                $tagList=($tagList=="")?"Others":$tagList.",Others";
            }
		}
        if($tagList!="")
        {
            return trim(preg_replace("/[^A-Za-z0-9,]/","-",trim($tagList)),"-");
        }
        else
        {
            return("Others");
        }
    }


    function _getCourseDataForIndexing($details){
        $courseList = unserialize(base64_decode($details['courseList']));
        for($i=0;$i<count($courseList);$i++)
        {
            $val = $courseList[$i];
            if(in_array(strtolower($val['course_type']),$this->courseTypeArray))
            {
                $courseList[$i]['course_type_display'] = strtolower($val['course_type']);
            }
            else
            {
                $details['course_type_display'] = "Others";
            }
	    //Added by Ankur for adding Seo url in Lucene
	    if(isset($val['seoListingUrl']) && strlen(trim($val['seoListingUrl']))>0)
		$courseList[$i]['seoUrl'] = $val['seoListingUrl'];
	    else
		$courseList[$i]['seoUrl'] = "";

            if(isset($this->courseLevelArray[strtolower($val['course_level'])]))
            {
                $tempCLevel = $this->courseLevelArray[strtolower($val['course_level'])];
                if(is_array($tempCLevel))
                {
                    if(isset($tempCLevel[strtolower($val['course_level_1'])]))
                    {
                        $tempCLevel = $tempCLevel[strtolower($val['course_level_1'])];
                        $courseList[$i]['course_level_display'] = $tempCLevel;
                    }
                    else
                    {
                        $courseList[$i]['course_level_display']  = "Others";
                    }
                }
                else
                {
                        $courseList[$i]['course_level_display'] = $tempCLevel;
                }
            }
            else
            {
                if(in_array(strtolower($val['course_level']),$this->courseLevelArray))
                {
                        $courseList[$i]['course_level_display'] = strtolower($val['course_level']);
                }
                else
                {
                    $courseList[$i]['course_level_display']  = "Others";
                }
            }
        }
        return $courseList;
    }

    function _getWikiForIndexing($details){
        $detailPageComponents = array();
        $userFields = array();
        $wikiSections = unserialize(base64_decode($details['wikiFields']));
        for($j = 0; $j < count($wikiSections); $j++){
            if(strlen($wikiSections[$j]['key_name']) > 0){
                $detailPageComponents['system_fields'][$wikiSections[$j]['key_name']] = $wikiSections[$j]['attributeValue'];
            }
            else{
		    $detailPageComponents['user_fields'][$wikiSections[$j]['caption']]= $wikiSections[$j]['attributeValue'];
            }
        }
	$wikiDataOutput['system_fields'] = json_encode($detailPageComponents['system_fields']);
	$wikiDataOutput['user_fields'] = json_encode($detailPageComponents['user_fields']);
        //return $detailPageComponents;
	return $wikiDataOutput;
    }



    function deleteListing(){

                $appId = 1;
		$this->init();
		$displayData = array();
		$ListingClientObj = new Listing_client();
		$type_id = $this->uri->segment(3);
		$listing_type = $this->uri->segment(4);
		// error_log_shiksha("CONTROLLER getCityList APP ID=> $appId :: $type_id  $listing_type");

                // die("<br>App id: ".$appId.", type id: ".$type_id.", listing_type: ".$listing_type);

                $luceneResponse = $ListingClientObj->deleteListing($appId,$listing_type,$type_id);

		echo "<pre>";
		print_r($luceneResponse);
		echo "</pre>";
    }
	
	public function getListingDetailsByScope($scope) {
		ini_set("memory_limit", '4000M');
		ini_set('max_execution_time', '14400');
		
		if($scope == 'abroad') {
			$logFileName = 'log_abroad_listing_details_'.date('y-m-d');
		} else if($scope == 'national'){
			$logFileName = 'log_national_listing_details_'.date('y-m-d');
		} else {
			$scope = 'both';
		}
		
		error_log("Cron started for ".$scope." at ".date('y-m-d H:i')."\n", 3, "/tmp/log_listing_details_".date('y-m-d'));
		
		//load library to send mail
        $this->load->library('alerts_client');
        $alertClient = new Alerts_client();
		
		//send mail
		$subject = $scope." listing details cron started.";
		$emailIdarray=array('nikita.jain@shiksha.com', 'pankaj.meena@shiksha.com');
		foreach($emailIdarray as $key=>$emailId){
			$alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emailId, $subject, "", "html", '', 'n');
		}
		
		//load model for the script
		$this->load->model('listing/listingdetailsscript');
	    $this->listingDetailsScript = new listingdetailsscript('MISTracking');
		//call appropriate function
		if($scope == 'abroad') {
			error_log("Running script (getAbroadListingDetails) for ".$scope." at ".date('y-m-d H:i')."\n", 3, "/tmp/log_listing_details_".date('y-m-d'));
			$this->listingDetailsScript->getAbroadListingDetails();
			error_log("Script ended (getAbroadListingDetails) for ".$scope." at ".date('y-m-d H:i')."\n", 3, "/tmp/log_listing_details_".date('y-m-d'));
		}
		else if ($scope == 'national') {
			error_log("Running script (getNationalListingDetails) for ".$scope." at ".date('y-m-d H:i')."\n", 3, "/tmp/log_listing_details_".date('y-m-d'));
			$this->listingDetailsScript->getNationalListingDetails();
			error_log("Script ended (getNationalListingDetails) for ".$scope." at ".date('y-m-d H:i')."\n", 3, "/tmp/log_listing_details_".date('y-m-d'));
		}
		else {
			error_log("Running script (getAbroadListingDetails) for ".$scope." at ".date('y-m-d H:i')."\n", 3, "/tmp/log_listing_details_".date('y-m-d'));
			$this->listingDetailsScript->getAbroadListingDetails();
			error_log("Script ended (getAbroadListingDetails) for ".$scope." at ".date('y-m-d H:i')."\n", 3, "/tmp/log_listing_details_".date('y-m-d'));
			
			error_log("Running script (getNationalListingDetails) for ".$scope." at ".date('y-m-d H:i')."\n", 3, "/tmp/log_listing_details_".date('y-m-d'));
			$this->listingDetailsScript->getNationalListingDetails();
			error_log("Script ended (getNationalListingDetails) for ".$scope." at ".date('y-m-d H:i')."\n", 3, "/tmp/log_listing_details_".date('y-m-d'));
		}
		
		//send mail
		$subject = "Listing details cron ended for ".$scope;
		foreach($emailIdarray as $key=>$emailId){
			$alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emailId, $subject, "", "html", '', 'n');
		}
		
		error_log("Cron ended for ".$scope." at ".date('y-m-d H:i')."\n", 3, "/tmp/log_listing_details_".date('y-m-d'));
	}
	
	/**
	* Purpose       : Method to generate reponse report for the 15 days period
	* Params        : none
	* Author        : Romil Goel
	*/
	function generateReponseReport()
	{
		ini_set("memory_limit", '2000M');
        ini_set('max_execution_time', '3600');
        $scriptStartTime = time();
		//load library to send mail
		$this->load->library('alerts_client');
		$alertClient = new Alerts_client();

		//send mail
		$emailIdarray=array('romil.goel@shiksha.com', 'pankaj.meena@shiksha.com');
		foreach($emailIdarray as $key=>$emailId)
			$alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emailId, "Response Report cron started.", "", "html", '', 'n');

		error_log("RESPONSE_RPT : ===================== SCRIPT STARTED ==========================");
		//load required files

		$this->listingReportsLib = $this->load->library('listing/ListingReports');

		// calculate the period of the report
		$dateToday 	= date('Y-m-d');
		$startTime 	= date('Y-m-d', strtotime('-15 day', strtotime($dateToday)));//"2014-05-15";
		$endTime 	= date('Y-m-d', strtotime('-1 day', strtotime($dateToday)));//"2014-06-01";
		$period 	= array("start" => $startTime, "end" => $endTime);

		$this->listingReportsLib->generateReponseReport($period);
		error_log("RESPONSE_RPT : ===================== SCRIPT ENDED SUCCESSFULLY ==========================");
		$scriptEndTime = time();
		$timeTaken = ($scriptEndTime - $scriptStartTime) / 60;
		foreach($emailIdarray as $key=>$emailId)
			$alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emailId, "Response Report cron ended.", "Time Taken = ".$timeTaken." mins", "html", '', 'n');
	}
	
	function generateReportForInaccurateReportedContacts() {
		error_log("Generate inaccurate reported contacts : start");
		$this->listingReportsLib = $this->load->library('listing/ListingReports');
		$this->listingReportsLib->generateReportForInaccurateReportedContacts();
		error_log("Generate inaccurate reported contacts : ends");
	}

	
function updateEnggCoursesURLs()
{
    _p("Starting : ".date("d-m-Y h:i:s"));
    error_log("::UPDATING_URLS:: Started");
    ini_set("memory_limit", "-1");
    ini_set('max_execution_time', -1);
    $finalEnggCourses = array();
    $this->national_course_lib = $this->load->library('listing/NationalCourseLib');	    
    $this->listingmodel = $this->load->model("listing/listingmodel");
    $this->load->builder('ListingBuilder','listing');
    $listingBuilder = new ListingBuilder;
    $this->courseRepository = $listingBuilder->getCourseRepository();
    
    $multiSubcatEnggCourses = $this->listingmodel->getEnggCourses(1);
    $ids = array_map(function ($ar) {return $ar['listing_type_id'];}, $multiSubcatEnggCourses);
    $multiSubcatEnggCourses = $this->listingmodel->getCoursesCatCount($ids);
    
    $multiSubCatCourses 	= array();
    $singleSubCatCourses 	= array();
    foreach($multiSubcatEnggCourses as $courseRow)
    {
	if($courseRow["subcatCount"] > 1)
	    $multiSubCatCourses[] = $courseRow["listing_type_id"];
	else
	    $singleSubCatCourses[] = $courseRow["listing_type_id"];
    }
    
    $finalEnggCourses = $singleSubCatCourses;
    $courseMappedToEnggAndOtherCategories = $this->listingmodel->getEnggCoursesMappedToOtherCategories($multiSubCatCourses);
    
    //$courseMappedToEnggAndOtherCategories = explode(",",$courseMappedToEnggAndOtherCategories[0]["courseIds"]);
    $courseMappedToEnggAndOtherCategories = array_map(function ($ar) {return $ar['courseIds'];}, $courseMappedToEnggAndOtherCategories);
    
    $courseSubCategories = $this->listingmodel->getSubCategoriesOfCourses($courseMappedToEnggAndOtherCategories);
    
    $multiSubCatEnggCourses = array_diff($multiSubCatCourses, $courseMappedToEnggAndOtherCategories);
    $finalEnggCourses = array_merge($finalEnggCourses, $multiSubCatEnggCourses);
    
    $courseWiseSubcats = array();
    $categorySubCatMap = array();
    foreach($courseSubCategories as $courseRow)
    {
	$courseWiseSubcats[$courseRow["listing_type_id"]][] = $courseRow["category_id"];
	$categorySubCatMap[$courseRow["category_id"]] = $courseRow["parentId"];
    }
    
    foreach($courseWiseSubcats as $courseId=>$subCatArr)
    {
	$dominantSubCat = $this->national_course_lib->getDominantSubCategoryForCourse($courseId, $subCatArr);
	$hasManagementSubcatFlag = 0;
	$isEngineeringCourse	 = 0;
	if($dominantSubCat["allSubcatCountEqualFlag"])
	{
		foreach($subCatArr as $subcatId)
		{
			if($categorySubCatMap[$subcatId] == 3)
				$hasManagementSubcatFlag = 1;
			else if($categorySubCatMap[$subcatId] == 2)
				$isEngineeringCourse = 1;
		}
	}
	
	$courseWiseSubcats[$courseId]["dominantSubcat"] = $dominantSubCat["dominant"];
	if(($categorySubCatMap[$dominantSubCat["dominant"]] == 2 || $isEngineeringCourse) && !$hasManagementSubcatFlag )
	{
	    $finalEnggCourses[] = $courseId;
	}
    }
    
    $headOfficeLocations 	= $this->listingmodel->getHeadOfficeLocationOfCourses($finalEnggCourses);
    $coursesData 		= $this->listingmodel->getCoursesAndInstInfo($finalEnggCourses);
    $coursesHeadOfficeCities 	= array();
    $finalCoursesUrls		= array();

    foreach($headOfficeLocations as $courseLocationRow)
    {
	$coursesHeadOfficeCities[$courseLocationRow["course_id"]] = $courseLocationRow["city_name"];
    }

    foreach($coursesData as $courseRow)
    {
	$courseId = $courseRow["course_id"];
	$cityName = "";
	$cityName = $coursesHeadOfficeCities[$courseId] ? $coursesHeadOfficeCities[$courseId] : "";
	
	$acronym  = $courseRow["abbreviation"] ? $courseRow["abbreviation"] : "";
	
	$url = $this->national_course_lib->getEnggCourseURL($courseRow["course_id"], $courseRow["courseTitle"], $acronym, $courseRow["institute_name"], $cityName);
	
	$finalCoursesUrls[$courseId] = $url;
    }

    error_log("::UPDATING_URLS:: STarting updation");
    $rs = $this->listingmodel->updateCourseURL($finalCoursesUrls);
    if($rs)
	error_log("::UPDATING_URLS:: Successfully Updated !!!");
    else
	error_log("::UPDATING_URLS:: Something went wrong !!!");

    error_log("::UPDATING_URLS:: Updation Ended");
	
    error_log("::UPDATING_URLS:: Refreshing cache of the courses ...");

    $coursesToBeRefreshed = array_keys($finalCoursesUrls);
    $coursesToBeRefreshed = array_chunk($coursesToBeRefreshed,5000);
    foreach($coursesToBeRefreshed as $chunk)
    {
	$this->courseRepository->disableCaching();
	if(is_array($chunk) && count($chunk) > 0) {
		$this->courseRepository->findMultiple($chunk);
	}
	error_log("::UPDATING_URLS:: Refreshed courses count : ".count($chunk));
    }
    
    error_log("::UPDATING_URLS:: Refreshing cache Done !!!");

    // re-index the courses whose urls are changed
    error_log("::UPDATING_URLS:: Re-indexing the courses : Starting ....!!!");
    $coursesToBeRefreshed = array_keys($finalCoursesUrls);
    foreach($coursesToBeRefreshed as $key=>$courseId)
    {
	modules::run('search/Indexer/index', $courseId,"course","false");
	if($key%1000 == 0)
		error_log("::UPDATING_URLS:: Indexed courses count : 1000");
    }
    error_log("::UPDATING_URLS:: Re-indexing the courses : Done !!!");
    error_log("::UPDATING_URLS:: Ending");
    _p("Ending : ".date("d-m-Y h:i:s"));
    _p("Done !!!");
    //_p($finalCoursesUrls);
}

/**
 * Migrating the URLs of the engineering course listings from engineering subdomain to main domain
 * @author Romil Goel <romil.goel@shiksha.com>
 * @date   2015-04-03
 * @return none
 */
function migrateEnggUrlsToMainDomain()
{
    _p("Starting : ".date("d-m-Y h:i:s"));
    error_log("\n".date("d-m-Y h:i:s")."::UPDATING_URLS:: Started");

    ini_set("memory_limit", "-1");
    ini_set('max_execution_time', -1);
    $finalEnggCourses          = array();
    $this->national_course_lib = $this->load->library('listing/NationalCourseLib');     
    $this->listingmodel        = $this->load->model("listing/listingmodel");
    $this->load->builder('ListingBuilder','listing');
    $listingBuilder            = new ListingBuilder;
    $this->courseRepository    = $listingBuilder->getCourseRepository();
    
    // get all course-id whose urls are on engineering subdomain
    $finalEnggCourses          = $this->listingmodel->getCoursesWithEnggUrls();
    $finalEnggCourses          = array_map(function ($ar) {return $ar['listing_type_id'];}, $finalEnggCourses);
    error_log("\nFinal Engineering courses : ".print_r(implode(",", $finalEnggCourses), true), 3,'/tmp/enggCoursesList.log');

    // get the details of the courses for building urls
    $headOfficeLocations     = $this->listingmodel->getHeadOfficeLocationOfCourses($finalEnggCourses);
    $coursesData             = $this->listingmodel->getCoursesAndInstInfo($finalEnggCourses);
    $coursesHeadOfficeCities = array();
    $finalCoursesUrls        = array();
    $instituteCourseMap      = array();

    foreach($headOfficeLocations as $courseLocationRow)
    {
        $coursesHeadOfficeCities[$courseLocationRow["course_id"]] = $courseLocationRow["city_name"];
    }

    foreach($coursesData as $courseRow)
    {
        $courseId                      = $courseRow["course_id"];
        $cityName                      = "";
        $cityName                      = $coursesHeadOfficeCities[$courseId] ? $coursesHeadOfficeCities[$courseId] : "";
        $acronym                       = $courseRow["abbreviation"] ? $courseRow["abbreviation"] : "";
        $instituteCourseMap[$courseId] = $courseRow["institute_id"];
        $url                           = $this->national_course_lib->getEnggCourseURL($courseRow["course_id"], $courseRow["courseTitle"], $acronym, $courseRow["institute_name"], $cityName);
        
        $finalCoursesUrls[$courseId] = $url;
    }

    // update the urls in listings_main
    error_log("\n::UPDATING_URLS:: STarting updation", 3,'/tmp/enggCoursesList.log');
    $rs = $this->listingmodel->updateCourseURL($finalCoursesUrls);

    if($rs)
        error_log("\n::UPDATING_URLS:: Successfully Updated !!!", 3,'/tmp/enggCoursesList.log');
    else
        error_log("\n::UPDATING_URLS:: Something went wrong !!!", 3,'/tmp/enggCoursesList.log');

    error_log("\n::UPDATING_URLS:: Updation Ended", 3,'/tmp/enggCoursesList.log');
    error_log("\n::UPDATING_URLS:: Refreshing cache of the courses ...", 3,'/tmp/enggCoursesList.log');

    // refreah the cache of the courses in batches
    $coursesToBeRefreshed = array_keys($finalCoursesUrls);
    $coursesToBeRefreshed = array_chunk($coursesToBeRefreshed,5000);
    foreach($coursesToBeRefreshed as $chunk)
    {
        $this->courseRepository->disableCaching();
        if(is_array($chunk) && count($chunk) > 0) {
            $this->courseRepository->findMultiple($chunk);
        }
        error_log("\n::UPDATING_URLS:: Refreshed courses count : ".count($chunk), 3,'/tmp/enggCoursesList.log');
    }
    
    error_log("\n::UPDATING_URLS:: Refreshing cache Done !!!", 3,'/tmp/enggCoursesList.log');

    // re-index the courses whose urls are changed
    error_log("\n::UPDATING_URLS:: Re-indexing the courses : Starting ....!!!", 3,'/tmp/enggCoursesList.log');
    $coursesToBeRefreshed = array_keys($finalCoursesUrls);
    foreach($coursesToBeRefreshed as $key=>$courseId)
    {
        // skip the courses of EduKart(as they will be indexed manually)
        if($instituteCourseMap[$courseId] == 35861){
            error_log("\n::UPDATING_URLS:: Skipping ".$courseId." course", 3,'/tmp/enggCoursesList.log');
            continue;
        }

        modules::run('search/Indexer/index', $courseId,"course","false");
        if($key%1000 == 0)
            error_log("\n::UPDATING_URLS:: Indexed courses count : 1000", 3,'/tmp/enggCoursesList.log');
    }
    error_log("\n::UPDATING_URLS:: Re-indexing the courses : Done !!!", 3,'/tmp/enggCoursesList.log');
    error_log("\n".date("d-m-Y h:i:s")."::UPDATING_URLS:: Ending", 3,'/tmp/enggCoursesList.log');
    _p("Ending : ".date("d-m-Y h:i:s"));
    _p("Done !!!");
}

function downloadReport($filename)
{
	
	$national_course_lib = $this->load->library('listing/NationalCourseLib');
	//$reportFileURL = MDB_SERVER."reports/".$filename;
	$reportFileURL = "/var/www/html/shiksha/mediadata/reports/".$filename;
	/*
	$ch = curl_init($reportFileURL);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$data = curl_exec($ch);
	curl_close($ch);
	$file = '/tmp/'.$filename;
	*/
	$file = $reportFileURL;
	file_put_contents($file, $data);

	if (file_exists($file)) {
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.basename($file));
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
                ob_end_clean();
		readfile($file);
		exit;
	}
}

/*
function downloadReport($filename)
{
	$national_course_lib = $this->load->library('listing/NationalCourseLib'); 
	$reportFileURL = MDB_SERVER."reports/".$filename;
	$curl_response = $national_course_lib->makeCurlRequest($reportFileURL);
	// Now download the file.. //set appropriate headers first.. 
	ob_start();
	header('Content-Description: File Transfer'); 
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment; filename='.basename($reportFileURL)); 
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize($reportFileURL));
	ob_clean();
	flush();
	
	echo $curl_response['body'];
	exit("Report downloaded");
}
*/
    /**
     * LF:2591 to break A.P into A.P and telangana
     * Author: Aman Varshney
     * @return [type] [description]
     */
    function breakState(){

        ini_set("memory_limit", "-1");
        ini_set('max_execution_time', -1);
        $start = microtime(true);

        //load model for the script
        $this->load->model('breakstatemodel');     
        $breakStateModel = new BreakStateModel();
        $breakStateModel->update();

        $stop      = microtime(true);
        $seconds   = $stop - $start;
        $resultLog = 'Start:' . $start.PHP_EOL.'Stop:' . $stop.PHP_EOL.'Seconds:' . $seconds.PHP_EOL;

        error_log("Script Executed".PHP_EOL,3,'/tmp/telanganaActivityLog.txt');
        error_log("Script Execution Time : ".$resultLog.PHP_EOL,3,'/tmp/telanganaActivityLog.txt');

    }

/**
* Purpose : Method to update indexing of all institute under a state.
* Params  :	$stateId (int)  State id for indexing institute.
* Author  : Vinay Airan
*/

function indexInstitutesUnderAState($stateId) {
	if(empty($stateId)) {
	 _p("Enter a StateId");	
	}
	ini_set("memory_limit", "-1");
	ini_set('max_execution_time', -1);
	$model = $this->load->model('breakstatemodel');
	$instituteIdsToSkip = array(33544,35861);
	
	//fetch all institutes related to stateId.
	$data = $model->institutesUnderAState($stateId);
	$instituteIds = array();
	foreach($data as $key=>$institueId) {
	 $instituteIds[] = $institueId['institute_id'];
	}
	
	//Create file for logging.
	$filePostfixCount = 1;
	$fileNameListInstiuteIds = "InstituteIds_to_index";
	$fileNameIndexedInstiuteIds = "Indexed_instituteIds";
	$fileFound = true;
	while($fileFound) {
	$fileFullPath = "/tmp/InstituteIds_to_index_for_state_id_".$stateId.".log_".date("Y_m_d")."-".$filePostfixCount;
	$fileFound = file_exists($fileFullPath);
	$filePostfixCount++;
	}
	$fileNameListInstiuteIds = $fileFullPath;
	$fileNameIndexedInstiuteIds = "/tmp/Indexed_instituteIds_for_state_id_".$stateId.".log_".date("Y_m_d")."-".($filePostfixCount-1);

	//Start Indexing and log status.
	error_log(implode(',',$instituteIds),3,$fileNameListInstiuteIds);
	$_REQUEST['indexingStatus'] = 1;
	error_log('Indexing Started ... for '.count($instituteIds).' institutes \n' ,3,$fileNameIndexedInstiuteIds);
	$count = 1;
	foreach($instituteIds as $instituteId) {
	 //institutes to skip 
	 	if(in_array($instituteId,$instituteIdsToSkip)) {	
	 		error_log($count++.' Institute Id '.$instituteId.' deletion status '.'Skipped'.'\n' ,3,$fileNameIndexedInstiuteIds);
	 		error_log("Institute Id ".$instituteId." index status "."Skipped"."\n",3,$fileNameIndexedInstiuteIds);
	 	} else {
		
		//delete Indexed data for institute
		$deleteStatus = Modules::run ( 'search/Indexer/delete', $instituteId, "institute",false);
		error_log($count++.' Institute Id '.$instituteId.' deletion status '.$deleteStatus.'\n' ,3,$fileNameIndexedInstiuteIds);
		//index data for institute.
		$indexStatus = Modules::run ( 'search/Indexer/index', $instituteId, "institute",false);
		error_log("Institute Id ".$instituteId." index status ".$indexStatus."\n",3,$fileNameIndexedInstiuteIds);
	 	}
	 }
	error_log('Indexing Complete ...',3,$fileNameIndexedInstiuteIds);
 }

/*
*   Add a city in State.
*/


 
  function addACityUnderAState($stateId,$cityName){
  	 $userData = $this->checkUserValidation();
  	 
  	  if($userData[0]['usergroup'] == "cms") {
  	  	$this->load->model('breakstatemodel');
  	  	$breakStateModel = new BreakStateModel();
  	  	$result  = $breakStateModel->addACityUnderAState($stateId,$cityName,$userData[0]['userid']);
  	  	_p($result);
  	  } else {
  	  	_p("You are not authorised. To add a City.");
  	  }
  	 
  }

function createCouponForPreviousPaidUsers() {
	$this->couponmodel = $this->load->model("common/couponmodel");
	$userIds = $this->couponmodel->getPaidUsersOfThisSeason();
	
	foreach($userIds as $userId) {
		$coupon[$userId] = $this->couponmodel->createUsersCoupon($userId);
	}
	_p('DONE');
}

    /**
     * script to update profile completion percentage of all live institute in institute table  
     * @author Aman Varshney <aman.varshney@shiksha.com>
     * @date   2015-02-06
     * @return 
     */
    function updateProfileCompletionForInstitutes($offset,$limit){
        return;
        ini_set("memory_limit", "-1");
        ini_set('max_execution_time', -1);
        
        $start                     = microtime(true);
        
        $this->load->model('listingmodel'); 
        $this->listingmodel_object = new ListingModel();
        
        // get all live institutes
        $instituteFinderModel = $this->load->model("listing/institutefindermodel");
        $data                 = $instituteFinderModel->getNotNullProfileCompletionInst($limit,$offset);
        
        // all live institutes count
        $institutesCount           = count($data); 
        
        $this->listingprofilelib   = $this->load->library('listing/ListingProfileLib');
        error_log('Profile Completion Institutes Process : '.$institutesCount);
        
        $input_array               = array();

        if($institutesCount > 0){
            foreach ($data as $key => $value) {    
                $inst_id          = $value['instituteId'];
                // api call to get institute profile completion
                $completion_array = $this->listingprofilelib->calculateProfileCompeletion($inst_id);
                $input_array[]    = array(
                                            "profile_percentage_completion" => $completion_array['percentage_completion'],
                                            "institute_id"=>$inst_id
                                         );            
                error_log("Institute Ids:".$inst_id.PHP_EOL,3,"/tmp/profileCompletionInstitutes$offset.txt");
            }
            
            // update Operation
            $this->listingmodel_object->updateBatchProfileCompletionInstitutes($input_array);
        }   

        $stop      = microtime(true);
        $seconds   = $stop - $start;
        $resultLog = 'Start:' . $start.PHP_EOL.'Stop:' . $stop.PHP_EOL.'Seconds:' . $seconds.PHP_EOL;

        error_log("Profile Completion Completed Institutes : ".$resultLog);
        echo $resultLog;
        exit;
        
    }

    /**
     * script to update profile completion percentage of all live courses in course_detail table  
     * @author Aman Varshney <aman.varshney@shiksha.com>
     * @date   2015-02-06
     * @return 
     */
    public function updateProfileCompletionForCourses($offset,$limit) {
        
        ini_set("memory_limit", "-1");
        ini_set('max_execution_time', -1);
        
        $start                     = microtime(true);
        
        $this->load->model('listing/coursemodel');
        $this->course_model_object = new CourseModel();
        
        $coursesFinderModel        = $this->load->model("listing/coursefindermodel");
        $data            = $coursesFinderModel->getNotNullProfileCompletionCourses($offset,$limit);
        
        $this->listingprofilelib   = $this->load->library('listing/ListingProfileLib');
        $coursesCount              = count($data);

        error_log('Courses Process : '.$coursesCount);
        $input_array               = array();

        if($coursesCount > 0){
            foreach ($data as $key => $value) {
            
                $course_id          = $value['courseId'];
                $course_reach       = $this->course_model_object->getCourseReachForCourses(array($course_id));
                
                $course_type        = "local";
                if(is_array($course_reach) && !empty($course_reach[$course_id])) {
                    $course_type        = $course_reach[$course_id];
                }
                
                // api call to get course profile completion
                $score_array        = $this->listingprofilelib->calculateProfileCompeletionForCourse($course_id,$course_type);
                $final_actual_score = $score_array['actual_score'];
                $final_total_score  = $score_array['total_score'] ;
                $percentage_score   = round(($final_actual_score/$final_total_score)*100,2);
                $input_array[]      = array(
                                            'profile_percentage_completion'=>$percentage_score,
                                            'course_id' => $course_id
                                            );
                error_log("Course Ids:".$course_id.PHP_EOL,3,"/tmp/profileCompletionCourse$offset.txt");
            }

            // update Operation
            $this->course_model_object->updateBatchProfileCompletionCourses($input_array);
         }


        $stop      = microtime(true);
        $seconds   = $stop - $start;
        $resultLog = 'Start:' . $start.PHP_EOL.'Stop:' . $stop.PHP_EOL.'Seconds:' . $seconds.PHP_EOL;

        error_log("Profile Completion Completed Courses : ".$resultLog);

        echo $resultLog;
        exit;
        
    }
	
	function downloadInstituteLogos($count) {
		$this->listingScriptsModel = $this->load->model('listingscriptsmodel');
		$insituteLogoLinks = $this->listingScriptsModel->getInstituteLogoUrls($count);
		
		$folderPath = '/tmp/logo_images/';
		mkdir($folderPath);
		foreach($insituteLogoLinks as $key=>$logoLink) {
			error_log('check if here... downloading insituteLogoLink '.$key.': '.$logoLink['logo_link']);
			exec('wget --directory-prefix='.$folderPath.' '.$logoLink['logo_link']);
		}
	}
	
	function scatterChartLogoAnalysis($type) {
		$folderPath = '/tmp/logo_images/';
		$images = scandir($folderPath);		
		for($i = 2; $i < sizeof($images); $i++) {
			list($width[$i-2], $height[$i-2]) = getimagesize($folderPath.$images[$i]);
			$originalAspectRatio[$i-2] = round($width[$i-2]/$height[$i-2], 1);
		}
		
		$aspectRatio = array_unique($originalAspectRatio);
		$aspectRatio = array_values($aspectRatio);
		
		$displayData['xAxisValue'] = $width;
		$displayData['yAxisValue'] = $height;
		
		$displayData['chartTitle'] = 'Width-Height Analysis';
		$displayData['xAxisTitle'] = 'Width';
		$displayData['yAxisTitle'] = 'Height';
		
		if($type == 'aspectRatio') {
			$displayData['yAxisValue'] = $aspectRatio;
			$numberOfImages = array(0);
			foreach($originalAspectRatio as $key=>$ratio) {
				$key = array_search($ratio, $aspectRatio);
				if($numberOfImages[$key] < 500) {
					$numberOfImages[array_search($ratio, $aspectRatio)]++;
				}
			}
			$displayData['xAxisValue'] = $numberOfImages;
			$displayData['chartTitle'] = 'Aspect Ratio - Number Analysis';
			$displayData['xAxisTitle'] = 'Number';
			$displayData['yAxisTitle'] = 'Aspect Ratio';
		}
		
		$this->load->view('scatterChart', $displayData);
	}

    function reindexEngineeringCourses(){

        error_log("\n".date("d-m-Y h:i:s")."::UPDATING_URLS:: Started", 3,'/tmp/enggCoursesList.log');
        $this->load->builder('SearchBuilder','search');
        $solrServer = SearchBuilder::getSearchServer();
        $solrBaseURL = $solrServer->getSolrURL('course','select');

        // get courses whose URLs are on engineering subdomain
        $solrQuery = $solrBaseURL."q=*:*&wt=phps&fq=facetype:course&fq=course_seo_url:*http\://engineering.shiksha*&wt=xml&fl=a:course_id,b:institute_id&hl=off&rows=10000";

        $response = $solrServer->curl($solrQuery);

        $solrResponse = unserialize($response);

        $courseIds          = array();
        $instituteIdMapping = array();
        foreach ($solrResponse['response']['docs'] as $courseidRow) {
            $courseIds[] = $courseidRow['a'];
            $instituteIdMapping[$courseidRow['a']] = $courseidRow['b'];
        }

        foreach($courseIds as $key=>$courseId){
            // skip the courses of EduKart(as they will be indexed manually)
            if($$instituteIdMapping[$courseId] == 35861){
                error_log("\n".date("d-m-Y h:i:s")."::UPDATING_URLS:: Skipping ".$courseId." course", 3,'/tmp/enggCoursesList.log');
                continue;
            }

            modules::run('search/Indexer/index', $courseId,"course","false");
            if($key%100 == 0)
                error_log("\n".date("d-m-Y h:i:s")."::UPDATING_URLS:: Indexed courses count : 100", 3,'/tmp/enggCoursesList.log');
        }

    }
	
    /**
     * Script to update newly added auto_creation_date column with the last Updated date present in listings_main for courses and institutes. This column is need for archiving listings table history(status) rows.
     * @author Romil Goel <romil.goel@shiksha.com>
     * @date   2015-04-14
     * @return [type]     [description]
     */
    function updateCourseListingsDate(){
        ini_set("memory_limit", "-1");
        ini_set('max_execution_time', -1);
        error_log("\n".date("d-m-Y h:i:s")."::STARTED::", 3,'/tmp/updateDateColumn.log');
        // $start                     = microtime(true);
        
        $this->load->model('listingmodel'); 
        $listingmodel_object = new ListingModel();
        $data = $listingmodel_object->getCourseListingsLastUpdatedDate("course");
        $lastUpdatedCourseDates = array();
        foreach ($data as $value) {
            $lastUpdatedCourseDates[$value['listing_type_id']] = $value['latest_date'];
        }

        $data = $listingmodel_object->getCourseListingsLastUpdatedDate("institute");
        $lastUpdatedInstitutesDates = array();
        foreach ($data as $value) {
            $lastUpdatedInstitutesDates[$value['listing_type_id']] = $value['latest_date'];
        }
	
	$data = $listingmodel_object->getCourseListingsLastUpdatedDate("university");
        $lastUpdatedUniversityDates = array();
        foreach ($data as $value) {
            $lastUpdatedUniversityDates[$value['listing_type_id']] = $value['latest_date'];
        }

        // // update course_details table
        // $data = array();
        // foreach($lastUpdatedCourseDates as $courseId=>$lastUpdatedDate)
        // {
        //     $data[] = array('course_id' => $courseId, 'auto_creation_date' => $lastUpdatedDate);
        // }
        // $affectedRows = $listingmodel_object->updateListingsDate('course_details', 'course_id', $data, array());
        // error_log("\n".date("d-m-Y h:i:s")."Updated course_details : ".$affectedRows, 3,'/tmp/updateDateColumn.log');
        

        // // update institute table
        // $data = array();
        // foreach($lastUpdatedInstitutesDates as $instituteId=>$lastUpdatedDate)
        // {
        //     $data[] = array('institute_id' => $instituteId, 'auto_creation_date' => $lastUpdatedDate);
        // }
        // $affectedRows = $listingmodel_object->updateListingsDate('institute', 'institute_id', $data, array());
        // error_log("\n".date("d-m-Y h:i:s")."Updated institute : ".$affectedRows, 3,'/tmp/updateDateColumn.log');

        // // update course_location_attribute table
        // $data = array();
        // foreach($lastUpdatedCourseDates as $courseId=>$lastUpdatedDate)
        // {
        //     $data[] = array('course_id' => $courseId, 'auto_creation_date' => $lastUpdatedDate);
        // }
        // $affectedRows = $listingmodel_object->updateListingsDate('course_location_attribute', 'course_id', $data, array());
        // error_log("\n".date("d-m-Y h:i:s")."Updated course_location_attribute : ".$affectedRows, 3,'/tmp/updateDateColumn.log');

        // // update institute_location_table table
        // $data = array();
        // foreach($lastUpdatedInstitutesDates as $instituteId=>$lastUpdatedDate)
        // {
        //     $data[] = array('institute_id' => $instituteId, 'auto_creation_date' => $lastUpdatedDate);
        // }
        // $affectedRows = $listingmodel_object->updateListingsDate('institute_location_table', 'institute_id', $data, array());
        // error_log("\n".date("d-m-Y h:i:s")."Updated institute_location_table : ".$affectedRows, 3,'/tmp/updateDateColumn.log');

        // update company_logo_mapping table
        $data = array();
        foreach($lastUpdatedCourseDates as $courseId=>$lastUpdatedDate)
        {
            $data[] = array('listing_id' => $courseId, 'auto_creation_date' => $lastUpdatedDate);
        }
        $affectedRows = $listingmodel_object->updateListingsDate('company_logo_mapping', 'listing_id', $data, array(array("listing_type", "course")));
        error_log("\n".date("d-m-Y h:i:s")."Updated company_logo_mapping : ".$affectedRows, 3,'/tmp/updateDateColumn.log');

        // // update course_attributes table
        // $data = array();
        // foreach($lastUpdatedCourseDates as $courseId=>$lastUpdatedDate)
        // {
        //     $data[] = array('course_id' => $courseId, 'auto_creation_date' => $lastUpdatedDate);
        // }
        // $affectedRows = $listingmodel_object->updateListingsDate('course_attributes', 'course_id', $data, array());
        // error_log("\n".date("d-m-Y h:i:s")."Updated course_attributes : ".$affectedRows, 3,'/tmp/updateDateColumn.log');

        // update listing_attributes_table table
        // 1. For Course
        $data = array();
        foreach($lastUpdatedCourseDates as $courseId=>$lastUpdatedDate)
        {
            $data[] = array('listing_type_id' => $courseId, 'auto_creation_date' => $lastUpdatedDate);
        }
        $affectedRows = $listingmodel_object->updateListingsDate('listing_attributes_table', 'listing_type_id', $data, array(array("listing_type", "course")));
        error_log("\n".date("d-m-Y h:i:s")."Updated listing_attributes_table for COURSE : ".$affectedRows, 3,'/tmp/updateDateColumn.log');

        // 2. For institute
        $data = array();
        foreach($lastUpdatedInstitutesDates as $instituteId=>$lastUpdatedDate)
        {
            $data[] = array('listing_type_id' => $instituteId, 'auto_creation_date' => $lastUpdatedDate);
        }
        $affectedRows = $listingmodel_object->updateListingsDate('listing_attributes_table', 'listing_type_id', $data, array(array("listing_type", "institute")));
        error_log("\n".date("d-m-Y h:i:s")."Updated listing_attributes_table for INSTITUTE : ".$affectedRows, 3,'/tmp/updateDateColumn.log');
	
	// // 3. For University
 //        $data = array();
 //        foreach($lastUpdatedUniversityDates as $universityId=>$lastUpdatedDate)
 //        {
 //            $data[] = array('listing_type_id' => $universityId, 'auto_creation_date' => $lastUpdatedDate);
 //        }
 //        $affectedRows = $listingmodel_object->updateListingsDate('listing_attributes_table', 'listing_type_id', $data, array(array("listing_type", "university")));
 //        error_log("\n".date("d-m-Y h:i:s")."Updated listing_attributes_table for UNIVERSITY : ".$affectedRows, 3,'/tmp/updateDateColumn.log');
die;
        // update listing_category_table table
        // 1. For Course
        $data = array();
        foreach($lastUpdatedCourseDates as $courseId=>$lastUpdatedDate)
        {
            $data[] = array('listing_type_id' => $courseId, 'auto_creation_date' => $lastUpdatedDate);
        }
        $affectedRows = $listingmodel_object->updateListingsDate('listing_category_table', 'listing_type_id', $data, array(array("listing_type", "course")));
        error_log("\n".date("d-m-Y h:i:s")."Updated listing_category_table for COURSE : ".$affectedRows, 3,'/tmp/updateDateColumn.log');

        // 2. For institute
        $data = array();
        foreach($lastUpdatedInstitutesDates as $instituteId=>$lastUpdatedDate)
        {
            $data[] = array('listing_type_id' => $instituteId, 'auto_creation_date' => $lastUpdatedDate);
        }
        $affectedRows = $listingmodel_object->updateListingsDate('listing_category_table', 'listing_type_id', $data, array(array("listing_type", "institute")));
        error_log("\n".date("d-m-Y h:i:s")."Updated listing_category_table for INSTITUTE : ".$affectedRows, 3,'/tmp/updateDateColumn.log');

        // update listing_contact_details table
        // 1. For Course
        $data = array();
        foreach($lastUpdatedCourseDates as $courseId=>$lastUpdatedDate)
        {
            $data[] = array('listing_type_id' => $courseId, 'auto_creation_date' => $lastUpdatedDate);
        }
        $affectedRows = $listingmodel_object->updateListingsDate('listing_contact_details', 'listing_type_id', $data, array(array("listing_type", "course")));
        error_log("\n".date("d-m-Y h:i:s")."Updated listing_contact_details for COURSE : ".$affectedRows, 3,'/tmp/updateDateColumn.log');

        // 2. For institute
        $data = array();
        foreach($lastUpdatedInstitutesDates as $instituteId=>$lastUpdatedDate)
        {
            $data[] = array('listing_type_id' => $instituteId, 'auto_creation_date' => $lastUpdatedDate);
        }
        $affectedRows = $listingmodel_object->updateListingsDate('listing_contact_details', 'listing_type_id', $data, array(array("listing_type", "institute")));
        error_log("\n".date("d-m-Y h:i:s")."Updated listing_contact_details for INSTITUTE : ".$affectedRows, 3,'/tmp/updateDateColumn.log');

	// 3. For university
        $data = array();
        foreach($lastUpdatedUniversityDates as $universityId=>$lastUpdatedDate)
        {
            $data[] = array('listing_type_id' => $universityId, 'auto_creation_date' => $lastUpdatedDate);
        }
        $affectedRows = $listingmodel_object->updateListingsDate('listing_contact_details', 'listing_type_id', $data, array(array("listing_type", "university")));
        error_log("\n".date("d-m-Y h:i:s")."Updated listing_contact_details for UNIVERSITY : ".$affectedRows, 3,'/tmp/updateDateColumn.log');
	
        // update listing_media_table table
        // 1. For Course
        $data = array();
        foreach($lastUpdatedCourseDates as $courseId=>$lastUpdatedDate)
        {
            $data[] = array('type_id' => $courseId, 'auto_creation_date' => $lastUpdatedDate);
        }
        $affectedRows = $listingmodel_object->updateListingsDate('listing_media_table', 'type_id', $data, array(array("type", "course")));
        error_log("\n".date("d-m-Y h:i:s")."Updated listing_media_table for COURSE : ".$affectedRows, 3,'/tmp/updateDateColumn.log');

        // 2. For institute
        $data = array();
        foreach($lastUpdatedInstitutesDates as $instituteId=>$lastUpdatedDate)
        {
            $data[] = array('type_id' => $instituteId, 'auto_creation_date' => $lastUpdatedDate);
        }
        $affectedRows = $listingmodel_object->updateListingsDate('listing_media_table', 'type_id', $data, array(array("type", "institute")));
        error_log("\n".date("d-m-Y h:i:s")."Updated listing_media_table for INSTITUTE : ".$affectedRows, 3,'/tmp/updateDateColumn.log');
	
	// 3. For University
        $data = array();
        foreach($lastUpdatedUniversityDates as $universityId=>$lastUpdatedDate)
        {
            $data[] = array('type_id' => $universityId, 'auto_creation_date' => $lastUpdatedDate);
        }
        $affectedRows = $listingmodel_object->updateListingsDate('listing_media_table', 'type_id', $data, array(array("type", "university")));
        error_log("\n".date("d-m-Y h:i:s")."Updated listing_media_table for University : ".$affectedRows, 3,'/tmp/updateDateColumn.log');

    }

    function updateCourseFlatUrlToDirectoryUrl($indexOnly = false, $refreshCache = false) {
        // ini_set("memory_limit", "-1");
        // ini_set('max_execution_time', -1);
        
        $start = microtime(true);
        $this->logFilePath = '/tmp/log_course_url_update_'.date('y-m-d');
        
        if(!$indexOnly) {
            error_log("::UPDATING_URLS:: Started \n", 3, $this->logFilePath);
            
            $categories = array(3);
            $this->listingmodel = $this->load->model("listing/listingmodel");
            $dbResult = $this->listingmodel->getAllCoursesCategoryWise($categories);
            
            $stop = microtime(true);
            $timeElapsed = $stop - $start;
            error_log("::UPDATING_URLS:: Fetched data from DB, time taken: ".$timeElapsed." seconds. \n", 3, $this->logFilePath);
            
            foreach ($dbResult as $key => $value) {
                $courseIds[] = $value['courseId'];
                $allowedSubcatIds[] = $value['subcategoryId'];
                $courseCategoryNameMap[$value['courseId']][$value['subcategoryId']] = $value['categoryUrlName'];
                $courseIdNameMap[$value['courseId']]['courseName'] = $value['courseName'];
                $courseIdNameMap[$value['courseId']]['instituteName'] = $value['instituteName'];
            }
            $courseIds = array_unique($courseIds);
            $allowedSubcatIds = array_unique($allowedSubcatIds);

            $start_2 = microtime(true);
            error_log("::UPDATING_URLS:: Fetching dominant subcategory.... \n", 3, $this->logFilePath);

            $this->national_course_lib = $this->load->library('listing/NationalCourseLib');
            $dominantSubcategory = $this->national_course_lib->getCourseDominantSubCategoryDB($courseIds);
            
            $stop = microtime(true);
            $timeElapsed = $stop - $start_2;
            error_log("::UPDATING_URLS:: Got dominant subcategory from DB, time taken: ".$timeElapsed." seconds. \n", 3, $this->logFilePath);

            //returns map of courseid and it's dominant subcat
            array_walk($dominantSubcategory['subCategoryInfo'], function(&$arr, $key) { $arr = $arr['dominant'];} );
            
            $relevantCourseIds = array();
            foreach ($dominantSubcategory['subCategoryInfo'] as $courseId => $dominantSubcat) {
                if(in_array($dominantSubcat, $allowedSubcatIds) && empty($courseCategoryNameMap[$courseId][$dominantSubcat])) {
                    $corruptCourseIds[] = $courseId;
                    error_log($courseId."\n", 3, '/tmp/log_corrupt_courseIds');
                }
                if(in_array($dominantSubcat, $allowedSubcatIds) && !empty($courseCategoryNameMap[$courseId][$dominantSubcat])) {
                    error_log("::UPDATING_URLS:: Creating new URL for course: ".$courseId."\n", 3, $this->logFilePath);
                    error_log($courseId."\n", 3, '/tmp/log_updated_courseIds');
                    $updatedCourseIds[] = $courseId;

                    $optionalArgs = array();
                    $optionalArgs['institute'] = $courseIdNameMap[$courseId]['instituteName'];
                    $optionalArgs['dominantSubcat'] = $courseCategoryNameMap[$courseId][$dominantSubcat];
                    $relevantCourseIdUrlMap[$courseId] = getSeoUrl($courseId, 'course', $courseIdNameMap[$courseId]['courseName'], $optionalArgs, 'old');
                }
            }
            
            //Write in DB
            error_log("::UPDATING_URLS:: Starting updation in DB... \n", 3, $this->logFilePath);
            $start_3 = microtime(true);

            $writeResult = $this->listingmodel->updateCourseURL($relevantCourseIdUrlMap);
            if($writeResult) {
                $stop = microtime(true);
                $timeElapsed = $stop - $start_3;
                error_log("::UPDATING_URLS:: Successfully updated in DB, time taken: ".$timeElapsed." seconds. \n", 3, $this->logFilePath);
            } else {
                error_log("::UPDATING_URLS:: Something went wrong!! \n", 3, $this->logFilePath);
                return;
            }
        }
        //die;
        
        //Re-index the courses whose urls are changed
        error_log("::UPDATING_URLS:: Re-indexing the courses : Starting ....\n", 3, $this->logFilePath);
        if(empty($updatedCourseIds) && $indexOnly) {
            $file = fopen('/tmp/log_updated_courseIds', 'r');
            while(!feof($file)) {
                $updatedCourseIds[] = fgets($file);
            }
        }
        
        foreach($updatedCourseIds as $key=>$courseId) {
            modules::run('search/Indexer/index', $courseId, "course", "false");
            error_log("::UPDATING_URLS:: Indexed course: ".$courseId."\n", 3, $this->logFilePath);
        }
        error_log("::UPDATING_URLS:: Re-indexing the courses : Done !!!\n", 3, $this->logFilePath);

        //Refresh cache of updated courses
        if($refreshCache) {
            error_log("::UPDATING_URLS:: Deleting cache of the courses ... \n", 3, $this->logFilePath);
            $start_4 = microtime(true);
            $ListingCache = $this->load->library('listing/cache/ListingCache');

            foreach($updatedCourseIds as $courseId) {
                $ListingCache->deleteCourse($courseId);
            }
            $stop = microtime(true);
            $timeElapsed = $stop - $start_4;
            error_log("::UPDATING_URLS:: Deletion of cache done, time taken: ".$timeElapsed." seconds. \n", 3, $this->logFilePath);
        }
        
        $stop          = microtime(true);
        $timeElapsed   = $stop - $start;
        error_log("::UPDATING_URLS:: Updation completed successfully, time taken: ".$timeElapsed." seconds. \n", 3, $this->logFilePath);

        _p('DONE'); die;
    }
	
	public function updateFacilities() {
		$inputFileName 	= '/home/pankaj/Desktop/FacilitiesDataFinal.xlsx';
		$logFile = "/tmp/facilityUpdationLog.txt";
		error_log( date("Y-m-d H:i:s") . ": Facility cron started \n", 3, $logFile);
		$excelColConfig = array(
			0  =>  "InstituteId",
			1  =>  "Hostel",
			2  =>  "Hostel-Text",
			3  =>  "WiFi",
			4  =>  "WiFi-Text",
			5  =>  "Labs",
			6  =>  "Labs-Text",
			7  =>  "Library",
			8  =>  "Library-Text",
			9  =>  "Extra-curricular activity clubs",
			10 => "Extra-curricular-activity-clubs-text",
			11 => "Cafeteria/Mess",
			12 => "Cafeteria/Mess-text",
			13 => "Transport facilities",
			14 => "Transport facilities-text",
			15 => "Auditorium",
			16 => "Auditorium-text",
			17 => "Sports Complex",
			18 => "Sports Complex-text",
			19 => "Hospital/Medical Facilities",
			20 => "Hospital/Medical Facilities-text",
			21 => "Gym",
			22 => "Gym-text",
			23 => "AC Classrooms",
			24 => "AC Classrooms-text",
		);
		
		$facilityExcelMapping = array(
			"7" =>  1,
			"3"	=>  2,
			"1" =>  3,
			"11" => 4,
			"13" => 5,
			"15" => 6,
			"19" => 7,
			"17" => 8,
			"21" => 9,
			"23" => 10,
			"5"	 => 11,
			"9" => 12
		);
		
		$this->load->library('common/PHPExcel');
		$objPHPExcel 	= new PHPExcel();
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objReader->setReadDataOnly(true);
		$objPHPExcel  = $objReader->load($inputFileName);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		error_log( date("Y-m-d H:i:s") . ": Excel file loaded \n", 3, $logFile);
		
		$highestRow    = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		$rows = array();
		$attributes = array();
		for($row = 2; $row <= $highestRow; ++$row) {
			$instituteId = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
			if(!empty($instituteId)){
				if(!in_array($instituteId, array_keys($attributes))) {
					for ($col = 1; $col <= $highestColumnIndex; $col = $col + 2) {
						$value 			= $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
						if(!empty($value) && strtolower($value) == 'yes'){
							$attributeId 	= $facilityExcelMapping[$col];
							$attributeValue = $objWorksheet->getCellByColumnAndRow($col+1, $row)->getValue();
							if(strlen($attributeValue) <= 200) {
								$params = array();
								$params['attributeId'] 		=  $attributeId;
								$params['attributeDesc'] 	=  $attributeValue;
								$params['excelColName'] 	=  $excelColConfig[$col];
								$attributes[$instituteId][] = $params;	
							}
						}
					}
				}
			}
		}
		error_log( date("Y-m-d H:i:s") . ": Attribute mapping list prepared \n", 3, $logFile);
		error_log( date("Y-m-d H:i:s") . ": Attribute mapping list  \n" . print_r($attributes, true) . "\n", 3, $logFile);
		error_log( date("Y-m-d H:i:s") . ": DB operation started \n", 3, $logFile);
		if(!empty($attributes)) {
			$this->load->model("listingscriptsmodel");
			$model = new listingscriptsmodel();
			$model->updateFacilitiesInDb($attributes, $logFile);
		}
		$instituteIds = array_keys($attributes);
		error_log( date("Y-m-d H:i:s") . ": DB operation ended \n", 3, $logFile);
		error_log( date("Y-m-d H:i:s") . ": Facility institute list \n", 3, "/tmp/facilityinstitutelist.txt");
		error_log( date("Y-m-d H:i:s") . print_r($instituteIds, true), 3, "/tmp/facilityinstitutelist.txt");
		
		//Delete cache
		$this->load->library('listing/cache/ListingCache');
		$listingcache = new listingcache();
		foreach($instituteIds as $instituteId) {
			$listingcache->deleteInstitute($instituteId);
		}
		error_log( date("Y-m-d H:i:s") . ": Cache deleted \n", 3, $logFile);
		
		//update indexes
		foreach($instituteIds as $instituteId) {
			modules::run('search/Indexer/delete', $instituteId, "institute", "false");
			modules::run('search/Indexer/index', $instituteId, "institute", "false");
		}
		error_log( date("Y-m-d H:i:s") . ": Re-indexing operation ended \n", 3, $logFile);
		error_log( date("Y-m-d H:i:s") . ": Facility cron ended \n", 3, $logFile);
	}

    public function updateCareerSynonyms(){
        $inputFileName  = '/home/hemanth131/Documents/careersynonyms.xlsx';
        $logFile = "/tmp/CareerSynonymUpdationLog.txt";
        error_log( date("Y-m-d H:i:s") . ": Career cron started \n", 3, $logFile);

        $this->load->library('common/PHPExcel');
        $objPHPExcel    = new PHPExcel();
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);
        $objPHPExcel  = $objReader->load($inputFileName);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        error_log( date("Y-m-d H:i:s") . ": Excel file loaded \n", 3, $logFile);
        
        $highestRow    = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

        $careers = array();
        for($row = 2; $row <= $highestRow; ++$row) {
            $careerId = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
            $value    = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
            if(!empty($careerId) && !empty($value)){
                $temp = explode(',', $value);
                if(!isset($careers[$careerId])){
                    $careers[$careerId] = array();
                }
                $careers[$careerId] = array_merge($careers[$careerId],$temp);
            }
        }

        error_log( date("Y-m-d H:i:s") . ": Attribute mapping list prepared \n", 3, $logFile);
        error_log( date("Y-m-d H:i:s") . ": Attribute mapping list  \n" . print_r($careers, true) . "\n", 3, $logFile);
        error_log( date("Y-m-d H:i:s") . ": DB operation started \n", 3, $logFile);
        if(!empty($careers)) {
            $this->load->model("listingscriptsmodel");
            $model = new listingscriptsmodel();
            $model->updateCareerSynonymsInDB($careers, $logFile);
        }
        $careerIds = array_keys($careers);
        error_log( date("Y-m-d H:i:s") . ": DB operation ended \n", 3, $logFile);
        error_log( date("Y-m-d H:i:s") . ": Careers list \n", 3, "/tmp/Careerlist.txt");
        error_log( date("Y-m-d H:i:s") . print_r($careerIds, true), 3, "/tmp/Careerlist.txt");
        
        //update indexes
        foreach($careerIds as $careerId) {
            modules::run('search/Indexer/delete', $careerId, "career", "false");
            modules::run('search/Indexer/index', $careerId, "career", "false");
        }
        error_log( date("Y-m-d H:i:s") . ": Re-indexing operation ended \n", 3, $logFile);
        error_log( date("Y-m-d H:i:s") . ": Career cron ended \n", 3, $logFile);
        print_r('updated');
    }

    public function updateInstituteSynonyms(){
        ini_set("memory_limit", "-1");
        ini_set("time_limit", "-1");
        $inputFileNameSynonyms  = '/home/hemanth131/Desktop/Synonyms.xlsx';
        $inputFileNameAcronyms  = '/home/hemanth131/Desktop/Acronyms.xlsx';
        $logFile = "/tmp/InstituteSynonymUpdationLog.txt";
        error_log( date("Y-m-d H:i:s") . ": Institute cron started \n", 3, $logFile);

        $this->load->library('common/PHPExcel');
        $objPHPExcel    = new PHPExcel();

        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);
        $objPHPExcel  = $objReader->load($inputFileNameSynonyms);

        $objWorksheet = $objPHPExcel->getActiveSheet();
        error_log( date("Y-m-d H:i:s") . ": Synonym Excel file loaded \n", 3, $logFile);
        
        $highestRow    = $objWorksheet->getHighestRow();

        $institutes = array();
        for($row = 2; $row <= $highestRow; ++$row) {
            $instituteId = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
            $value    = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
            if(!empty($instituteId) && !empty($value)){
                $temp = explode("'", $value);
                if(!isset($institutes[$instituteId])){
                    $institutes[$instituteId] = array();
                }
                $institutes[$instituteId]['synonyms'][] = implode('', $temp);
            }
        }

        $objPHPExcel  = $objReader->load($inputFileNameAcronyms);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        error_log( date("Y-m-d H:i:s") . ": Acronym Excel file loaded \n", 3, $logFile);
        
        $highestRow    = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();

        for($row = 2; $row <= $highestRow; ++$row) {
            $instituteId = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
            $value    = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
            if(!empty($instituteId) && !empty($value)){
                $temp = explode("'", $value);
                if(!isset($institutes[$instituteId])){
                    $institutes[$instituteId] = array();
                }
                $institutes[$instituteId]['acronyms'][] = implode('', $temp);
            }
        }

        error_log( date("Y-m-d H:i:s") . ": Attribute mapping list prepared \n", 3, $logFile);
        error_log( date("Y-m-d H:i:s") . ": Attribute mapping list  \n" . print_r($institutes, true) . "\n", 3, $logFile);
        error_log( date("Y-m-d H:i:s") . ": DB operation started \n", 3, $logFile);

        $instituteIds = array();
        if(!empty($institutes)) {
            $this->load->model("listingscriptsmodel");
            $model = new listingscriptsmodel();
            // $instituteIds = $model->updateInstituteSynonymsInDB($institutes, $logFile);
        }
        
        error_log( date("Y-m-d H:i:s") . ": DB operation ended \n", 3, $logFile);
        error_log( date("Y-m-d H:i:s") . ": Institutes list \n", 3, "/tmp/Institutelist.txt");
        error_log( date("Y-m-d H:i:s") . print_r($instituteIds, true), 3, "/tmp/Institutelist.txt");
        
        //update indexes
        foreach($instituteIds as $instituteId) {
		    Modules::run('search/Indexer/delete', $instituteId, "institute", "false");
            Modules::run('search/Indexer/index', $instituteId, "institute", "false");
        }
        error_log( date("Y-m-d H:i:s") . ": Re-indexing operation ended \n", 3, $logFile);
        error_log( date("Y-m-d H:i:s") . ": Institute cron ended \n", 3, $logFile);
        error_log('updated',3,$logFile);
    }
	
	
	public function updateOldListURLs() {
        $inputFileName  = '/home/pankaj/Desktop/listurlsnew.xlsx';
        $this->load->library('common/PHPExcel');
        $objPHPExcel    = new PHPExcel();
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);
        $objPHPExcel  = $objReader->load($inputFileName);
		
        $objWorksheet = $objPHPExcel->getActiveSheet();
		
        $highestRow    = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

        $listUrls = array();
        for($row = 2; $row <= $highestRow; ++$row) {
			$data = array();
			for ($col = 0; $col <= $highestColumnIndex; $col = $col + 1) {
				$value 	= $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
				$value  = trim($value);
				switch($col){
					case 0:
						$urlstring = explode("/", $value);
						$data['url_string'] = $urlstring[1];
						break;
					case 1:
						$data['keyword'] = $value;
						break;
					case 2:
						$data['location'] = $value;
						break;
					case 3:
						if(strtolower($value) == "y"){
							$data['redirect_to'] = 'category';
						} else {
							$data['redirect_to'] = 'search';
						}
						break;
					case 4:
						$data['subcategory_id'] = (int)$value;
						break;
					case 5:
						$data['city_id'] = (int)$value;
						break;
				}
			}
			if(!empty($data['url_string'])){
				$listUrls[] = $data;
			}
		}
		$listingScriptsModel = $this->load->model('listingscriptsmodel');
		$listingScriptsModel->updateOldListURLs($listUrls);
		
		echo "Shitty work done now enjoy";
	}

    function addRankPredictorData(){
        $examName = "jee-main";
        $inputFileName = '/home/hemanth/Downloads/JEEMainsfinal.xlsx';
        $logFileName = '/tmp/rankPredictorDataUpdate.log';

        error_log("Starting script to add data from excel for exam $examName \n", 3, $logFileName);
        $this->load->library('common/PHPExcel');
        $objPHPExcel    = new PHPExcel();

        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);
        $objPHPExcel  = $objReader->load($inputFileName);

        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();

        $predictorData = array();
        for($row = 2; $row <= $highestRow; ++$row) {
            $score = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
            $minRank = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
            $midRank = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
            $maxRank = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
            
            if(!empty($midRank)){
                $predictorData[] = array('examName' => $examName, 'score' => $score, 'minRank' => $minRank, 'midRank' => $midRank, 'maxRank' => $maxRank,'status' => 'live');
            }
        }
        error_log(count($predictorData)." columns found in excel \n", 3, $logFileName);
        if(!empty($predictorData)){
            // _p($predictorData);die;
            $rpmodel = $this->load->model('RP/rpmodel');
            $rpmodel->saveRankPredictorData($predictorData,$examName);
            _p('DONE');
            error_log("Finished adding data for $examName \n", 3, $logFileName);
        }
    }

    function addMultipleRankingPagesByPublisherAndYear(){
        $sheetArray = array(
                array('sourceId' => 24,'rankingPageId' => 95,'courseAltText' => 'Arts'),
                array('sourceId' => 24,'rankingPageId' => 97,'courseAltText' => 'Commerce'),
                array('sourceId' => 24,'rankingPageId' => 101,'courseAltText' => 'Science'),
                array('sourceId' => 24,'rankingPageId' => 44,'courseAltText' => 'Engineering'),
                array('sourceId' => 24,'rankingPageId' => 94,'courseAltText' => 'Fashion Design'),
                array('sourceId' => 24,'rankingPageId' => 99,'courseAltText' => 'Mass Communication'),
                array('sourceId' => 24,'rankingPageId' => 56,'courseAltText' => 'Law'),
                array('sourceId' => 24,'rankingPageId' => 98,'courseAltText' => 'Hotel Management'),
                array('sourceId' => 24,'rankingPageId' => 100,'courseAltText' => 'Medical')
            );
        $inputFileName = '/home/hemanth/Downloads/TheWeekRankings.xlsx';
        $logFileName = '/tmp/rankingPageDataUpdation.log';

        error_log("Starting script to add data from excel \n", 3, $logFileName);
        $this->load->library('common/PHPExcel');
        $objPHPExcel    = new PHPExcel();

        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);
        $objPHPExcel  = $objReader->load($inputFileName);

        $sheetCount = $objPHPExcel->getSheetCount();
        if($sheetCount != count($sheetArray)){
            error_log("Mismatch in sheet count array and in sheet \n", 3, $logFileName);
            return;
        }

        foreach ($sheetArray as $sheetIndex => $sheetData) {
            $objPHPExcel->setActiveSheetIndex($sheetIndex);
            $courseAltText = $sheetData['courseAltText'];
            $sourceId = $sheetData['sourceId'];
            $rankingPageId = $sheetData['rankingPageId'];

            $objWorksheet = $objPHPExcel->getActiveSheet();
            $highestRow    = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

            $rankingData = array();
            for($row = 2; $row <= $highestRow; ++$row) {
                $rank = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
                $instituteId = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
                $courseId = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
                if(!empty($courseId)){
                    $rankingData[$courseId] = array('rank' => $rank, 'instituteId' => $instituteId, 'courseId' => $courseId);
                }
            }

            // _p($rankingData);die;

            $rankingModel = $this->load->model('rankingV2/ranking_model');
            foreach ($rankingData as $courseId => $data) {
                error_log("Adding $courseId into the ranking page for sheet $courseAltText \n", 3, $logFileName);
                $rank = 'rank:'.$data['rank'];
                $status = $rankingModel->saveRankingCourseDetails($rankingPageId,$data['instituteId'],$courseId,$rank,$courseAltText,$sourceId);
                if(!empty($status['error_type'])){
                    error_log("Error adding $courseId into the ranking page for sheet $courseAltText \n", 3, $logFileName);
                }
            }
            error_log("Finished adding data to ranking page tables for sheet $courseAltText \n", 3, $logFileName);
        }
    }

    function addRankingPageForPublisherAndYear($sourceId,$rankingPageId){
        if(empty($sourceId) || empty($rankingPageId)){
            return;
        }
        $inputFileName = '/tmp/BusinessTodayRankings.xlsx';
        $logFileName = '/tmp/rankingPageDataUpdation.log';
        $courseAltText = 'MBA';
        error_log("Starting script to add data from excel \n", 3, $logFileName);
        $this->load->library('common/PHPExcel');
        $objPHPExcel    = new PHPExcel();

        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);
        $objPHPExcel  = $objReader->load($inputFileName);

        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

        $rankingData = array();
        for($row = 2; $row <= $highestRow; ++$row) {
            $rank = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
            $instituteId = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
            $courseId = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
            if(!empty($courseId)){
                $rankingData[$courseId] = array('rank' => $rank, 'instituteId' => $instituteId, 'courseId' => $courseId);
            }
        }
        // _p($rankingData);die;
        $rankingModel = $this->load->model('rankingV2/ranking_model');
        foreach ($rankingData as $courseId => $data) {
            error_log("Adding $courseId into the ranking page \n", 3, $logFileName);
            $rank = 'rank:'.$data['rank'];
            $status = $rankingModel->saveRankingCourseDetails($rankingPageId,$data['instituteId'],$courseId,$rank,$courseAltText,$sourceId);
            if(!empty($status['error_type'])){
                error_log("Error adding $courseId into the ranking page \n", 3, $logFileName);
            }
        }
        error_log("Finished adding data to ranking page tables \n", 3, $logFileName);
        /*error_log("Executing non zero cron \n", 3, $logFileName);
        modules::run('rankingV2/RankingMain/populateNonZeroRankingData');
        error_log("Non zero cron execution completed \n", 3, $logFileName);*/
    }


    /**
     * [updateRankingPageData2015 will update ranking source for a page 
     * and also it will update all the ranks when an excel file is placed]
     * @author Ankit Garg <g.ankit@shiksha.com>
     * @date   2016-01-04
     * @return [type]     [description]
     */
    function updateRankingPageData2015() {
        return;
        // return;
        ini_set('max_execution_time', 1800);
        $oldSourceName = 'Business World 2014';
        $newSourceName = 'The Week 2015';
        $inputFileName = '/tmp/mbaCourseMappingTheWeek.xlsx';
        $logFileName = '/tmp/rankingPageDataUpdation.log';
        $this->load->library('common/PHPExcel');
        $objPHPExcel    = new PHPExcel();

        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);
        $objPHPExcel  = $objReader->load($inputFileName);

        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        
        $specializationToRankingPageIdMapping = array();
        // for($i = 5; $i < $highestColumnIndex; $i++) {
        //     error_log("Column: $i \n", 3, $logFileName);
        //     $value  = $objWorksheet->getCellByColumnAndRow($i, 1)->getValue();
        //     $value  = trim($value);
        //     error_log("Value: $value \n", 3, $logFileName);
        //     preg_match('/\d+/',$value, $rankingPageId);
        //     $specializationToRankingPageIdMapping[$value] = (is_numeric($rankingPageId[0])) ? $rankingPageId[0] : '';
        // }
        
        $specializationToRankingPageIdMapping = array('MBA' => 2);
        
        $RankingModel = $this->load->model('ranking_new/ranking_model');
        //data for old source
        $oldSourceParamData = reset($RankingModel->getSourceParamIdBySourceName($oldSourceName));
        $rankingPageOldSourceId = $oldSourceParamData['source_id'];
        //data for new source
        $sourceParamData = reset($RankingModel->getSourceParamIdBySourceName($newSourceName));
        $rankingPageNewSourceId = $sourceParamData['source_id'];
        
        $rankingPageSourceParamId = $sourceParamData['param_id'];
        foreach ($specializationToRankingPageIdMapping as $rankingPageName => $rankingPageId) {
            $courseIds = $RankingModel->getRankingPageCourseIdsForSource($rankingPageId, $rankingPageOldSourceId);
            error_log("Deleting for ranking page id: $rankingPageId and source id: $rankingPageOldSourceId \n", 3, $logFileName);
            foreach ($courseIds as $courseIdData) {
                $courseId = $courseIdData['course_id'];
                error_log("Deleting data for course id: $courseId \n", 3, $logFileName);
                Modules::run('ranking_new/RankingEnterprise/removeCourseFromSourceData',$rankingPageId, $courseId, $rankingPageOldSourceId);
            }
        }
        error_log("All courses of $oldSourceName have been deleted \n", 3, $logFileName);
        
        // $subcategorySpecializationIds = array();
        // for ($col = 5; $col < $highestColumnIndex; $col++) {
        //     $value  = $objWorksheet->getCellByColumnAndRow($col, 1)->getValue();
        //     $value  = trim($value);
        //     $subcategorySpecializationIds[$col] = $value;
        // }

        //processing excel file into an array
        $rankingPage = array();
        $rowsProcessedFromExcel = 0;
        for($row = 2; $row <= $highestRow; ++$row) {
            $data = array();
            for ($col = 0; $col < $highestColumnIndex; $col = $col + 1) {
                $value  = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                $value  = trim($value);
                $data[] = $value;
            }
            $defaultPageId = $specializationToRankingPageIdMapping['MBA'];
            if(!empty($defaultPageId)) {
                $rankingPage[$defaultPageId][$row]['instituteId']       = $data[3];
                $rankingPage[$defaultPageId][$row]['courseId']          = (is_numeric($data[4])) ? $data[4] : '';
                $rankingPage[$defaultPageId][$row]['courseAltText']     = 'MBA';
                $rankingPage[$defaultPageId][$row]['overallRank']       = $data[0];
                $rankingPage[$defaultPageId][$row]['score']             = $data[5];
            }
			/*
            //looping through rest of the specializations
            for($x = 6; $x <= $highestColumnIndex; $x++) {
                //checking if specialization exist
                if(!empty($data[$x])) {
                    $pageId                                      = $specializationToRankingPageIdMapping[$subcategorySpecializationIds[$x]];
                    if(!empty($pageId)) {
                        $rankingPage[$pageId][$row]                  = $rankingPage[$defaultPageId][$row];
                        $rankingPage[$pageId][$row]['courseAltText'] = $subcategorySpecializationIds[$x];
                        $rankingPage[$pageId][$row]['courseId']      = (is_numeric($data[$x])) ? $data[$x] : '';
                    }
                }
            } */
            $rowsProcessedFromExcel++;
        }
        error_log("Rows processed from Excel: $rowsProcessedFromExcel \n", 3, $logFileName);
        
        $totalEntriesInsertedIntoDB = $this->insertIntoRankingPageDB($rankingPage, $rankingPageSourceParamId, $rankingPageNewSourceId, $logFileName);

        error_log("Cron ended with total entries inserted into DB: $totalEntriesInsertedIntoDB \n", 3, $logFileName);
    }

    /**
     * [insertNewRankingPages this will insert new ranking pages and their data 
     * into DB if the page has already been created in the draft status]
     * @author Ankit Garg <g.ankit@shiksha.com>
     * @date   2016-01-07
     * @return [type]     [description]
     */
    function insertNewRankingPagesUG() {
        return;
        ini_set('max_execution_time', 1800);
        ini_set('memory_limit', '512M');
        // $oldSourceName = 'Business Today 2014';
        $newSourceName = 'India Today 2015';
        $inputFileName = '/tmp/rankingPageUGCategories.xlsx';
        $logFileName = '/tmp/rankingPageDataUpdation.log';
        $this->load->library('common/PHPExcel');
        $objPHPExcel    = new PHPExcel();

        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);
        $objPHPExcel  = $objReader->load($inputFileName);

        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

        $rankingPageNames = array();
        for($row = 2; $row <= $highestRow; ++$row) {
            $value  = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
            $rankingPageNames[]  = trim($value);
        }
        $RankingModel = $this->load->model('ranking_new/ranking_model');
        
        $sourceParamData = reset($RankingModel->getSourceParamIdBySourceName($newSourceName));
        $rankingPageNewSourceId = $sourceParamData['source_id'];
        $rankingPageSourceParamId = $sourceParamData['param_id'];

        $rankingPageNames = array_unique($rankingPageNames);
        $rankingPageIds = $RankingModel->getRankingPageIdByName($rankingPageNames);
        
        foreach ($rankingPageIds as $rankingPageName => $rankingPageId) {
            $courseIds = $RankingModel->getRankingPageCourseIdsForSource($rankingPageId, $rankingPageOldSourceId);
            error_log("Deleting for ranking page id: $rankingPageId and source id: $rankingPageNewSourceId \n", 3, $logFileName);
            foreach ($courseIds as $courseIdData) {
                $courseId = $courseIdData['course_id'];
                error_log("Deleting data for course id: $courseId \n", 3, $logFileName);
                Modules::run('ranking_new/RankingEnterprise/removeCourseFromSourceData',$rankingPageId, $courseId, $rankingPageNewSourceId);
            }
        }
        error_log("All sources have been deleted \n", 3, $logFileName);


        $score = array();
        for($row = 2; $row <= $highestRow; ++$row) {
            $rankingPage[$pageId];
            //looping through rest of the specializations
            $data = array();
            for ($col = 0; $col < $highestColumnIndex; $col = $col + 1) {
                    $value  = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                    $value  = trim($value);
                    $data[] = $value;
            }
            if($score[$data[0]]) {
                $score[$data[0]] = $score[$data[0]] - 10;    
            }
            else {
                $score[$data[0]] = 990;
            }
            
            $defaultPageId = $rankingPageIds[$data[0]];
            if(!empty($defaultPageId)) {
                $rankingPage[$defaultPageId][$row]['instituteId']       = $data[3];
                $rankingPage[$defaultPageId][$row]['courseId']          = $data[4];
                $rankingPage[$defaultPageId][$row]['courseAltText']     = $data[0];
                $rankingPage[$defaultPageId][$row]['overallRank']       = $data[2];
                $rankingPage[$defaultPageId][$row]['score']             = $score[$data[0]];
            }
        }
        
        $totalEntriesInsertedIntoDB = $this->insertIntoRankingPageDB($rankingPage, $rankingPageSourceParamId, $rankingPageNewSourceId, $logFileName);

        error_log("Cron ended with total entries inserted into DB: $totalEntriesInsertedIntoDB \n", 3, $logFileName);
    }

    private function insertIntoRankingPageDB($rankingPage, $rankingPageSourceParamId, $rankingPageNewSourceId, $logFileName) {
        return;
        $totalEntriesInsertedIntoDB = 0;
        foreach($rankingPage as $rankingPageId => $RankingPageData) {
            error_log("Inserting for ranking page id: $rankingPageId and source id: $rankingPageNewSourceId \n", 3, $logFileName);
            foreach($RankingPageData as $data) {
                $instituteId    = $data['instituteId'];
                $courseId       = $data['courseId'];
                $courseAltText  = $data['courseAltText'];
                $overallRank    = $data['overallRank'];
                $score          = $data['score'];
                $rankScoreDetails = "paramId:$rankingPageSourceParamId,rank:$overallRank,score:$score";
                error_log("Inserting for course id: $courseId \n", 3, $logFileName);
                Modules::run('ranking_new/RankingEnterprise/saveRankingCourseDetails',$rankingPageId, $instituteId, $courseId, $rankScoreDetails, $courseAltText, $overallRank, $rankingPageNewSourceId);
                $totalEntriesInsertedIntoDB++;
            }
        }
        return $totalEntriesInsertedIntoDB;
    }

    function deleteMultipleCourses() {
        $courseIdsString = '130505,130510,130511,130518,167853,168064,168074,168076,168123,168126,168131,168141,168145,168146,168147,168148,168149,168150,168151,168162,168237,168239,168244,168249,168281,168310,168311,168313,168314,168317,168331,168333,168346,168361,168376,168427,168473,168494,202063,202065,202066,202068,202076,202174,202383,202411,202416,202421,202426,202432,202441,202467,202549,202565,202595,202602,202606,202650,202669,202678,202680,202682,202683,202687,202689,202690,202691,202693,202694,202696,202698,202700,202704,202712,202715,202718,202727,202737,205296,227948,227949,227950,228010,228011,228023,228029,228030,228031,228032,228033,228034,228035,228036,228037,228038,228040,228041,228043,228044,228046,228047,228056,228061,228072,228075,228080,228201,228202,228205,228214,228216,228218,228219,228221,228222,228223,228224,228227,228231,228233,228234,228238,228239,228240,228241,228242,228243,228244,228245,228258,228262,228281,228289,228290,228291,228292,228302,228304,228305,228307,228308,228309,228311,228312,228314,228316,228317,228319,228322,228323,228548,228551,228556,228558,228562,229686,229688,229690,229695,229971,229976,229977,229980,229981,229984,229985,230063,230064,230066,230078,230101,230122,230125,230127,230145,230146,230149,233999,234002,234009,234026,234035,234043,234049,234053,234054,234056,234058,234067,234069,234071,234072,234073,234079,234084,130514,228051,234197,234201,234203,234218,234232,234239,168221,130504,130522,130538,167557,167558,167564,167663,168132,202126,202130,202148,202149,202153,227805,234045,234047,234222,168068,168083,168088,168287,168288,168309,168397,168409,168441,202087,202466,202586,202591,202657,202688,203477,203479,203482,203483,203487,227802,228257,229969,229973,229974,229975,229978,229979,230108,230398,234017,234063,234066,234074,234081,234195,234223,234237,234240';
        $courseIds = explode(',',$courseIdsString);
        $url = "http://www.shiksha.com/categoryList/TestListing/deleteCache/course/";
        
        foreach($courseIds as $courseId) {
            echo $url.$courseId."<br/>";
            file_get_contents($url.$courseId);
        }
    }

	public function collectSubscriptionCoursesDataFromTables(){
		$logFileName = '/tmp/paidListingsTracking.log';
		$this->load->model("listingscriptsmodel");
		$this->load->library('sums/Subscription_client');
		$subscriptionClient = new Subscription_client();
		ini_set('memory_limit','200M');
		error_log("Cron for listings subscriptions tracking starts... \n", 3, $logFileName);

		$i = 0;$limit = 200;
		$model = new listingscriptsmodel();
		$totalCourseCount = $model->getTotalCourseCount();//_p($totalCourseCount);die;

		error_log("Total unique Courses : $totalCourseCount \n", 3, $logFileName);
		error_log("Processing at a batch size of $limit ... \n", 3, $logFileName);
		error_log("Total batches to be processed : ".ceil($totalCourseCount/$limit)." \n", 3, $logFileName);

		while($i < $totalCourseCount){
		    error_log("Batch ".($i/$limit+1)." starts ... \n", 3, $logFileName);
		    // take $limit courses at a time
		    $courseIds = $model->getCourseIdsInChunks($limit,$i);//_p($courseIds);die;
		    $insertData = array();
		    if(count($courseIds) > 0){
			$subscriptionIds = array();
			$listingsdata    = array();
			$listingsdata    = $model->getDataFromListingsTable($courseIds);//_p($listingsdata);die;
			error_log("Got data from listings_main table for courseIds ... \n".implode(',', $courseIds)."\n", 3, $logFileName);

			foreach ($listingsdata as $courseId => $courses) {
			    foreach ($courses as $courseDetail) {
				if(!in_array($courseDetail['subscriptionId'], $subscriptionIds)){
				    $subscriptionIds[] = $courseDetail['subscriptionId'];
				}
			    }
			}

			$subscriptionDetails = $subscriptionClient->getMultipleSubscriptionDetails(1,$subscriptionIds,true);
			$subscriptionDetails = $this->_prepareSubscriptionDetails($subscriptionDetails);
			error_log("Got Subscriptions details for these Courses having subscriptionsIds... \n".implode(',', $subscriptionIds)."\n", 3, $logFileName);
			// _p($subscriptionDetails);die;

			foreach($listingsdata as $courseId => $courses){

			    if(count($courses) == 1){
				$isPaidCourse = $this->isPaidCourse($courses[0]['pack_type']);
				if($isPaidCourse && $courses[0]['subscriptionId'] == 0){
				    error_log("Found a course ($courseId) with paid packtype {$courses[0]['pack_type']} but zero subscriptionId \n", 3, $logFileName);
				}
				else{
				    $temp = array();
				    $temp = $this->populateDataForSubscription($courses,$subscriptionDetails,0,'');
				    if(!empty($temp)){
					$insertData[] = $temp;
				    }
				}
			    }
			    else{
				$count = count($courses);$j=0;
				while($j <$count){
				    $nextChangedIndex = '';$temp = array();
				    $isPaidCourse = $this->isPaidCourse($courses[$j]['pack_type']);
				    if($isPaidCourse && $courses[$j]['subscriptionId'] == 0){
					error_log("Found a course ($courseId) with paid packtype {$courses[$j]['pack_type']} but zero subscriptionId \n", 3, $logFileName);
					$j++;
					continue;
				    }
				    else{
					if($j < $count){
					    $paidIndex = $j;
					    $j++;
					    while($j < $count && ($courses[$j]['status']!='deleted') && $courses[$j]['subscriptionId'] == $courses[$paidIndex]['subscriptionId'] && $courses[$j]['pack_type'] == $courses[$paidIndex]['pack_type']){
						$j++;
					    }
					    if($j < $count){
						$nextChangedIndex = $j;
					    }
					    $temp = $this->populateDataForSubscription($courses,$subscriptionDetails,$paidIndex,$nextChangedIndex);
					}
				    }
				    if(!empty($temp)){
					$insertData[] = $temp;
				    }
				}
			    }
			}
		    }
		    if(count($insertData) > 0){//_p($insertData);//die;
			$model->insertSubscriptionDataIntoNewTable($insertData);
		    }//die;
		    // if($i == 600){
		    //     die;
		    // }
		    $last = end($insertData);
		    error_log("Inserted data into database  ... \n", 3, $logFileName);
		    error_log("Last Inserted entry is  \n".$last['courseId']."\n", 3, $logFileName);
		    error_log("Batch ".($i/$limit+1)." ends ... \n", 3, $logFileName);

		    $i += $limit;
		}
		error_log("Cron for listings subscriptions tracking Ended\n", 3, $logFileName);
	    }

	    public function populateDataForSubscription($courses,$subscriptionDetails,$paidIndex,$nextChangedIndex){
		$temp = array();
		if(count($courses)>1 && $courses[$paidIndex]['status'] == 'deleted'){
		    return $temp;
		}
		$temp['courseId'] = $courses[$paidIndex]['listing_type_id'];
		$temp['packType'] = $courses[$paidIndex]['pack_type'];
		$temp['subscriptionId'] = $courses[$paidIndex]['subscriptionId'];
		$temp['clientId'] = $courses[$paidIndex]['username'];
		$temp['updatedBy'] = -1;

		$subscriptionStartDate = $subscriptionDetails[$courses[$paidIndex]['subscriptionId']]['subscriptionStartDate'];
		if($subscriptionStartDate == '0000-00-00 00:00:00' && $this->isPaidCourse($temp['packType'])){
		    $subscriptionStartDate = $courses[0]['last_modify_date'];
		}
		$temp['subscriptionStartDate'] = $subscriptionStartDate;

		$temp['subscriptionExpiryDate'] = $subscriptionDetails[$courses[$paidIndex]['subscriptionId']]['subscriptionExpiryDate'];
		$temp['source'] = 'national';
		$temp['addedFrom'] = 'byscript';

		$editedBy = $courses[$paidIndex]['editedBy'];
		if(empty($editedBy)){
		    $temp['addedBy'] = 29; //29 is the userID for yaseen@naukri.com
		}
		else{
		    $temp['addedBy'] = $editedBy;
		}

		$added = $courses[$paidIndex]['last_modify_date'];
		if($added == '0000-00-00 00:00:00' && count($courses) > 1){
		    $i = $paidIndex+1;
		    while ($i < count($courses)) {
			if($courses[$i]['last_modify_date'] != '0000-00-00 00:00:00' && $courses[$i]['pack_type'] == $courses[$paidIndex]['pack_type']){
			    $added = $courses[$i]['last_modify_date'];
			    break;
			}
			$i++;
		    }
		}

		list($temp['addedOnDate'],$temp['addedOnTime']) = explode('|', date('Y-m-d|H:i:s', strtotime($courses[$paidIndex]['last_modify_date'])));
		if(!empty($nextChangedIndex)){
		    list($temp['endedOnDate'],$temp['endedOnTime']) = explode('|', date('Y-m-d|H:i:s', strtotime($courses[$nextChangedIndex]['last_modify_date'])));
		    $updatedBy = $courses[$nextChangedIndex]['editedBy'];
		    if(empty($updatedBy)){
			$temp['updatedBy'] = 29;
		    }
		    else{
			$temp['updatedBy'] = $updatedBy;
		    }
		}
		else{
		    if($courses[$paidIndex]['status'] == 'deleted'){
			list($temp['addedOnDate'],$temp['addedOnTime']) = explode('|', date('Y-m-d|H:i:s', strtotime($courses[$paidIndex]['submit_date'])));
			list($temp['endedOnDate'],$temp['endedOnTime']) = explode('|', date('Y-m-d|H:i:s', strtotime($courses[$paidIndex]['last_modify_date'])));
		    }
		    else{
			if($this->isPaidCourse($temp['packType'])){
			    if(strtotime($temp['subscriptionExpiryDate']) < time() && time() > strtotime($temp['subscriptionStartDate'])){
				list($temp['endedOnDate'],$temp['endedOnTime']) = explode('|', date('Y-m-d|H:i:s', strtotime($temp['subscriptionExpiryDate'])));
				$temp['updatedBy'] = 1; //for cron
			    }
			    else{
				list($temp['endedOnDate'],$temp['endedOnTime']) = array('0000-00-00','00:00:00');
			    }
			}
			else{
			    list($temp['endedOnDate'],$temp['endedOnTime']) = array('0000-00-00','00:00:00');
			}
		    }
		}
		return $temp;
	    }

	    public function isPaidCourse($packtype){
		return in_array($packtype, array(GOLD_SL_LISTINGS_BASE_PRODUCT_ID,SILVER_LISTINGS_BASE_PRODUCT_ID,GOLD_ML_LISTINGS_BASE_PRODUCT_ID));
	    }

	    public function _prepareSubscriptionDetails($subscriptionDetails){
		$finalDetails = array();
		$statusOrder = array('ACTIVE','INACTIVE','HISTORY','CANCELLED');
		foreach ($subscriptionDetails as $subscription) {
		    $subscriptionId = ltrim($subscription['SubscriptionId'],'0');
		    if(empty($finalDetails[$subscriptionId])){
			$finalDetails[$subscriptionId]['subscriptionStartDate'] = $subscription['SubscriptionStartDate'];
			$finalDetails[$subscriptionId]['subscriptionExpiryDate'] = $subscription['SubscriptionEndDate'];
			$finalDetails[$subscriptionId]['packType'] = $subscription['BaseProductId'];
			$finalDetails[$subscriptionId]['subscriptionStatus'] = $subscription['subscriptionStatus'];
			$finalDetails[$subscriptionId]['SubscrLastModifyTime'] = $subscription['SubscrLastModifyTime'];
		    }
		    else{
			$currentStatusIndex = array_search($finalDetails[$subscriptionId]['subscriptionStatus'], $statusOrder);
			$newStatusIndex = array_search($subscription['subscriptionStatus'], $statusOrder);
			$currentLastTime = strtotime($finalDetails[$subscriptionId]['SubscrLastModifyTime']);
			$newLastTime = strtotime($subscription['SubscrLastModifyTime']);
			if($newStatusIndex <= $currentStatusIndex){
			    if($newStatusIndex == $currentStatusIndex){
				if($newLastTime > $currentLastTime){
				    $finalDetails[$subscriptionId]['subscriptionStartDate'] = $subscription['SubscriptionStartDate'];
				    $finalDetails[$subscriptionId]['subscriptionExpiryDate'] = $subscription['SubscriptionEndDate'];
				    $finalDetails[$subscriptionId]['packType'] = $subscription['BaseProductId'];
				    $finalDetails[$subscriptionId]['subscriptionStatus'] = $subscription['subscriptionStatus'];
				    $finalDetails[$subscriptionId]['SubscrLastModifyTime'] = $subscription['SubscrLastModifyTime'];
				}
			    }
			    else{
				$finalDetails[$subscriptionId]['subscriptionStartDate'] = $subscription['SubscriptionStartDate'];
				$finalDetails[$subscriptionId]['subscriptionExpiryDate'] = $subscription['SubscriptionEndDate'];
				$finalDetails[$subscriptionId]['packType'] = $subscription['BaseProductId'];
				$finalDetails[$subscriptionId]['subscriptionStatus'] = $subscription['subscriptionStatus'];
				$finalDetails[$subscriptionId]['SubscrLastModifyTime'] = $subscription['SubscrLastModifyTime'];
			    }
			}
		    }
		}

		$finalDetails['0']['subscriptionStartDate'] = '0000-00-00 00:00:00';
		$finalDetails['0']['subscriptionExpiryDate'] = '0000-00-00 00:00:00';
		return $finalDetails;
	    }

	    public function getCurrencyExchangeRatesForNational(){
		$logFileName = '/tmp/exchangeRates.log';
		$finalArr = array();
		$listingscriptsmodel = $this->load->model("listingscriptsmodel");
		$curlLib = $this->load->library("Curl");
		$validCurrencies = array('USD','INR','AUD','CAD','SGD','GBP','NZD','EUR');
		foreach($validCurrencies as $key => $currency){
		    $url = "http://api.fixer.io/latest?base=".$currency;
		    $response = json_decode($curlLib->_simple_call('get',$url),true);

		    foreach ($validCurrencies as $curr) {
			$arr = array();
			if($curr != $currency){
			    $arr['baseCurrency'] = $currency;
			    $arr['exchangeCurrency'] = $curr;
			    $arr['exchangeValue'] = $response['rates'][$curr];
			    $arr['status'] = 'live';
			    $arr['created'] = date("Y-m-d H:i:s");
			}
			if(!empty($arr)){
			    $finalArr[] = $arr;
			}
		    }
		}
		// _p($finalArr);die;
		if(!empty($finalArr)){
		    $listingscriptsmodel->insertExchangeRatesForNational($finalArr);
		    $rankingCache = $this->load->library('ranking_new/cache/RankingPageCache');
		    $rankingCache->deleteCurrencyExchangeRatesCacheForNational();
		}
	    }

    function createCourseCache(){

        ini_set("memory_limit", "6000M");
        ini_set('max_execution_time', -1);

        $listingscriptsmodel = $this->load->model("listingscriptsmodel");

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
            error_log("== CUUERNT COUNT == ".$currentCount);
            $slice = array_slice($courseIdsArr, $currentCount, $batchSize);
            $currentCount += $batchSize;
            $this->courseRepo->findMultiple($slice,'full');
        }
    }

    public function removeCompleteRankingInterlinkingForCourse(){
        ini_set("memory_limit", "6000M");
        ini_set('max_execution_time', -1);

        $listingscriptsmodel = $this->load->model("listingscriptsmodel");
        $courseIdsArr = $listingscriptsmodel->fetchCourses();
        $cache = $this->load->library('nationalCourse/cache/NationalCourseCache');

        foreach ($courseIdsArr as $courseId) {
            $cache->removeCourseInterLinkingBySection($courseId,'ranking');
        }
    }

    public function sendStreamDigestForOldUsers(){
        ini_set('max_execution_time', -1);
        
        $listingscriptsmodel = $this->load->model("listingscriptsmodel");
        $listingscriptsmodel->sendStreamDigestForOldUsers();
    }

    public function insertOnlineFormConfigIntoDb(){
        $dashBoardConfig = $this->load->library('studentFormsDashBoard/dashboardconfig');

        $insertData = array();
        foreach (DashboardConfig::$institutes_autorization_details_array as $courseId => $row) {
            $temp = array();
            $temp['courseId'] = $courseId;
            $temp['instituteId'] = empty($row['instituteId']) ? NULL : $row['instituteId'];
            $temp['auth_text'] = empty($row['auth_text']) ? NULL : $row['auth_text'];
            $temp['auth_sign_name'] = empty($row['auth_sign_name']) ? NULL : $row['auth_sign_name'];
            $temp['auth_sign_post'] = empty($row['auth_sign_post']) ? NULL : $row['auth_sign_post'];
            $temp['auth_sign_image'] = empty($row['auth_sign_image']) ? NULL : $row['auth_sign_image'];
            $temp['auth_image'] = empty($row['auth_image']) ? NULL : $row['auth_image'];
            $temp['avatar_image_url'] = empty($row['avatar_image_url']) ? NULL : $row['avatar_image_url'];
            $temp['college_logo_image'] = empty($row['college_logo_image']) ? NULL : $row['college_logo_image'];
            $temp['institute_alias'] = empty($row['institute_alias']) ? NULL : $row['institute_alias'];
            $temp['seo_title'] = empty($row['seo_title']) ? NULL : $row['seo_title'];
            $temp['seo_description'] = empty($row['seo_description']) ? NULL : $row['seo_description'];
            $temp['seo_keywords'] = empty($row['seo_keywords']) ? NULL : $row['seo_keywords'];
            $temp['alt_image_header'] = empty($row['alt_image_header']) ? NULL : $row['alt_image_header'];
            $temp['seo_url'] = empty($row['seo_url']) ? NULL : $row['seo_url'];

            $insertData[] = $temp;
        }
        if(!empty($insertData)){
            $listingscriptsmodel = $this->load->model("listingscriptsmodel");
            $listingscriptsmodel->insertOnlineFormConfigIntoDb($insertData);
        }
        _p('DONE');
    }

    public function generateReviewsCacheForCourses($courseIds, $returnReview = false){
        $this->validateCron();
        ini_set("memory_limit", '6000M');
        ini_set('max_execution_time', -1);

        $listingscriptsmodel = $this->load->model("listingscriptsmodel");
        $CollegeReviewSolrClient = $this->load->library('CollegeReviewForm/solr/CollegeReviewSolrClient');
        $CollegeReviewCache = $this->load->library('CollegeReviewForm/cache/CollegeReviewCache');
        $instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');

        if(!empty($courseIds)){

            if(!is_array($courseIds)){
                $courseIds = explode(',',$courseIds);
                $aggregateRatings = $CollegeReviewSolrClient->getAggregateReviewsForMultipleCourses($courseIds);    
                // _P($aggregateRatings); die;
                $CollegeReviewCache->storeAggregateReviewsForListingToCache($aggregateRatings,$courseIds,'course');
            }
            if($returnReview)
                return $aggregateRatings;
        }
        else{
            $batchSize = 100;
            $count = 1;
            $start = 0;


            do{
                error_log(" ********** Getting courseIds for batch $count ********** ");
                $courseIds = $listingscriptsmodel->fetchCoursesInBatch($start, $batchSize);
                // _p($courseIds);die;
                // $courseIds = array(979,983);
                if(!empty($courseIds)){
                    $aggregateRatings = $CollegeReviewSolrClient->getAggregateReviewsForMultipleCourses($courseIds);
                    $CollegeReviewCache->storeAggregateReviewsForListingToCache($aggregateRatings,$courseIds,'course');
                    $count++;
                    $start += $batchSize;
                }
            }while(!empty($courseIds));
        }
        
        _p('DONE');
    }

    private function generateReviewsCacheForInstitutesHelper($instituteIds){
        $listingscriptsmodel = $this->load->model("listingscriptsmodel");
        $CollegeReviewSolrClient = $this->load->library('CollegeReviewForm/solr/CollegeReviewSolrClient');
        $CollegeReviewCache = $this->load->library('CollegeReviewForm/cache/CollegeReviewCache');
        $instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');

        $hierarchyData = $instituteDetailLib->getAllCoursesForMultipleInstitutes($instituteIds);
        foreach ($hierarchyData as $instituteId => $instituteRow) {
            $aggregateRatings[$instituteId]['totalCount'] = 0;
            $aggregateRatings[$instituteId]['aggregateRating']['averageRating']['count'] = 0;
            $aggregateRatings[$instituteId]['aggregateRating']['averageRating']['sum'] = 0;
            $aggregateRatings[$instituteId]['aggregateRating']['facultyRating']['count'] = 0;
            $aggregateRatings[$instituteId]['aggregateRating']['facultyRating']['sum'] = 0;
            $aggregateRatings[$instituteId]['aggregateRating']['campusFacilitiesRating']['count'] = 0;
            $aggregateRatings[$instituteId]['aggregateRating']['campusFacilitiesRating']['sum'] = 0;
            $aggregateRatings[$instituteId]['aggregateRating']['avgSalaryPlacementRating']['count'] = 0;
            $aggregateRatings[$instituteId]['aggregateRating']['avgSalaryPlacementRating']['sum'] = 0;
            $aggregateRatings[$instituteId]['aggregateRating']['crowdCampusRating']['count'] = 0;
            $aggregateRatings[$instituteId]['aggregateRating']['crowdCampusRating']['sum'] = 0;
            $aggregateRatings[$instituteId]['aggregateRating']['moneyRating']['count'] = 0;
            $aggregateRatings[$instituteId]['aggregateRating']['moneyRating']['sum'] = 0;

            $aggregateRatings[$instituteId]['intervalRatingCount'] = array('1-2' => 0,'2-3' => 0,'3-4' => 0, '4-5' => 0);

            $aggregateRatings[$instituteId]['intervalRatingCountForPlacement'] = array('1-2' => 0,'2-3' => 0,'3-4' => 0, '4-5' => 0);

            $courseReviewData = array();
            if(!empty($instituteRow['courseIds'])){
                $courseReviewData = $CollegeReviewCache->getAggregateReviewsForListingFromCache($instituteRow['courseIds'],'course');
            }

            foreach ($instituteRow['courseIds'] as $courseId) {
                $aggregateRatings[$instituteId]['totalCount'] += $courseReviewData[$courseId]['totalCount'];

                foreach ($courseReviewData[$courseId]['aggregateRating'] as $key => $row) {
                    foreach ($row as $statName => $statValue) {
                        if($statName != 'mean'){
                            $aggregateRatings[$instituteId]['aggregateRating'][$key][$statName] += $statValue;
                        }
                    }
                }
                foreach ($courseReviewData[$courseId]['intervalRatingCount'] as $key => $value) {
                    $aggregateRatings[$instituteId]['intervalRatingCount'][$key] += $value;
                }

                foreach ($courseReviewData[$courseId]['intervalRatingCountForPlacement'] as $key => $value) {
                    $aggregateRatings[$instituteId]['intervalRatingCountForPlacement'][$key] += $value;
                }
            }
        }
        foreach ($aggregateRatings as $instituteId => $row) {
            if($row['totalCount'] <= 0){
                unset($aggregateRatings[$instituteId]);
            }
            else{
                foreach ($row['aggregateRating'] as $ratingName => $ratingData) {
                    $aggregateRatings[$instituteId]['aggregateRating'][$ratingName]['mean'] = round($ratingData['sum']/$ratingData['count'], 1);
                }
            }
        }
        $CollegeReviewCache->storeAggregateReviewsForListingToCache($aggregateRatings,$instituteIds,'institute');
    }

    public function generateReviewsCacheForInstitutes($instituteIds){

        $this->validateCron();
        ini_set("memory_limit", '6000M');
        ini_set('max_execution_time', -1);

        $listingscriptsmodel = $this->load->model("listingscriptsmodel");
        $CollegeReviewSolrClient = $this->load->library('CollegeReviewForm/solr/CollegeReviewSolrClient');
        $CollegeReviewCache = $this->load->library('CollegeReviewForm/cache/CollegeReviewCache');
        $instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');

        $count = 1;
        $start = 0;
        $batchSize = 100;

        do{
            error_log(" ********** Getting instituteIds for batch $count ********** ");
            $dbData = $listingscriptsmodel->fetchInstitutesInBatch($start, $batchSize);

            $instituteData = array();
            foreach ($dbData as $row) {
                $instituteData[$row['listing_id']] = $row['listing_type'];
            }

            if(!empty($instituteData)){
                $aggregateRatings = array();
                $instituteIds = array_keys($instituteData);
                $this->generateReviewsCacheForInstitutesHelper($instituteIds);

                $count++;
                $start += $batchSize;
            }
        }while(!empty($instituteData));

        error_log(" ********** Cache for institute reviews has been generated ********** ");
        _p('DONE');
    }

    public function deleteCollegeReviewsFromSolrByCourses($courseIds){
        if(empty($courseIds)){
            return;
        }
        if(!is_array($courseIds)){
            $courseIds = explode(',',$courseIds);
        }

        $this->config->load('search_config');
        $this->load->builder('SearchBuilder','search');
        $this->solrSearchSever = SearchBuilder::getSearchServer($this->config->item('search_server'));

        $solrUrl = $this->solrSearchSever->getSolrUrl('collegereview','update');

        $courseIdData = array_chunk($courseIds,500);
        foreach ($courseIdData as $chunk) {
            $urlComponents = array();
            $urlComponents[] = 'commit=true';
            $urlComponents[] = 'stream.body='.'<delete><query>(courseId:('.implode(' OR ', $chunk).'))AND(reviewStatus:published)</query></delete>';
            $aggregateReviewUrl = implode('&',$urlComponents);
            $this->sendGetCurlRequest($solrUrl.'?'.$aggregateReviewUrl);
        }
    }

    private function sendGetCurlRequest($url){
        // _p($url);die;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($ch, CURLOPT_TIMEOUT, 800);

        $result = curl_exec($ch);
        $culrRetcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);
        /*_p($result);
        _p($culrRetcode);die;*/
        return $result;
    }

    public function fullIndexReviewsByCourses($courseIds){
        ini_set("memory_limit", '6000M');
        ini_set('max_execution_time', -1);

        if(empty($courseIds)){
            _p('DONE');
            return;
        }
        if(!is_array($courseIds)){
            $courseIds = explode(",",$courseIds);
        }
        $this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $this->courseRepo = $courseBuilder->getCourseRepository();
        $this->courseRepo->disableCaching();

        $this->config->load('search_config');
        $this->load->builder('SearchBuilder','search');
        $this->solrSearchSever = SearchBuilder::getSearchServer($this->config->item('search_server'));

        $solrUrl = $this->solrSearchSever->getSolrUrl('collegereview','update');

        $courseObjs = $this->courseRepo->findMultiple($courseIds);

        $collegereviewmodel = $this->load->model('CollegeReviewForm/collegereviewmodel');
        $reviewData = $collegereviewmodel->getPublishedReviewIdsForCourses($courseIds);
        _p($reviewData);

        $reviewIds = array();
        foreach ($reviewData as $row) {
            $reviewIds[] = $row['reviewId'];
        }
        
        if(!empty($reviewIds)){
            $this->deleteCollegeReviewsFromSolrByCourses($courseIds);
            Modules::run('CollegeReviewForm/SolrIndexing/insertMultipleReviewsIntoIndexLog',$reviewIds,'index');
            Modules::run('indexer/NationalIndexer/nationalProcessIndexLog','collegereview');

            $solrUrl = $this->solrSearchSever->getSolrUrl('collegereview','update');
            $this->sendGetCurlRequest($solrUrl.'?commit=true');

            $this->generateReviewsCacheForCourses($courseIds);
        }
        _p('DONE');
    }

    public function fullIndexReviewsByInstitutes($instituteIds){
        ini_set("memory_limit", '6000M');
        ini_set('max_execution_time', -1);

        if(empty($instituteIds)){
            _p('DONE');
            return;
        }
        if(!is_array($instituteIds)){
            $instituteIds = explode(",",$instituteIds);
        }

        $this->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $this->instituteRepo = $instituteBuilder->getInstituteRepository();
        $instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');
        $this->load->library('ContentRecommendation/ReviewRecommendationLib');

        $instituteData = $instituteDetailLib->getAllCoursesForMultipleInstitutes($instituteIds);
        // _p($instituteData);die;

        foreach ($instituteData as $childId => $data) {
            if(!empty($data['courseIds'])){
                $this->fullIndexReviewsByCourses($data['courseIds']);
                $childInstitutes = array();
                foreach ($data['instituteWiseCourses'] as $instituteId => $row) {
                    $childInstitutes[] = $instituteId;
                }

                $cacheKeyPrefix = getCachePrefix('institute','review');
                $responseFromSource = $this->reviewrecommendationlib->getInstituteReviewCountsFromDB($instituteIds,$data);
                updateCountsCache($responseFromSource,$cacheKeyPrefix,86400);

                if(!empty($childInstitutes)){
                    $this->generateReviewsCacheForInstitutesHelper($childInstitutes);
                }
            }
        }
    }

    function populateAggregateReviewsCache() {
        $this->generateReviewsCacheForCourses();
        $this->generateReviewsCacheForInstitutes();
    }

    public function populateCategoryPageLinksCache(){
             $this->validateCron();   
            ini_set("memory_limit", "6000M");
            ini_set('max_execution_time', -1);
            $this->benchmark->mark('cron_start');
            $this->nationalinstitutecache = $this->load->library('nationalInstitute/cache/NationalInstituteCache');
            $this->benchmark->mark('cron_start');
            $institutedetailsmodel = $this->load->model("nationalInstitute/institutedetailsmodel");
            $data = $institutedetailsmodel->getAllinstitutes();
            $index = 0;
            foreach ($data as $key => $instituteData) {
                $categoryPageLinksLimit =12;
                $listingId =   $instituteData['listing_id'];
                $listingType = $instituteData['listing_type'];
                $this->load->builder("nationalInstitute/InstituteBuilder");
                $instituteBuilder = new InstituteBuilder();
                $instituteRepo = $instituteBuilder->getInstituteRepository();
                $instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');
                $instituteObj = $instituteRepo->find($listingId,'full');
                $instituteCurrentLocation = $instituteDetailLib->getInstituteCurrentLocation($instituteObj);
                if(empty($instituteCurrentLocation)){
                    continue;
                }
                $this->load->builder("nationalCourse/CourseBuilder");
                $builder          = new CourseBuilder();
                $courseRepository = $builder->getCourseRepository();
                $courseList = $instituteDetailLib->getInstituteCourseIds($listingId, $listingType);
                $courseList    = $courseList['courseIds'];
                $allCourseList = $courseList;
                // getting all courses because of all base course/stream section
                if($allCourseList){
                    $allCourses  = $courseRepository->findMultiple($allCourseList, array('basic'), false, false);   
                }
                foreach($allCourses as $key=>$value){
                    if(!$value->getId())
                           unset($allCourses[$key]);
                }
                foreach ($allCourses as $courseObj) {
                    $courseTypeInfo = $courseObj->getCourseTypeInformation();
                    $courseTypeObj  = $courseTypeInfo['entry_course'];
                        if($courseTypeObj){
                            $baseCourseId = $courseTypeObj->getBaseCourse();

                            if($baseCourseId == $mbaBaseCourseId){
                                $mbaCourseIds[] = $courseObj->getId();
                            }
                            if($baseCourseId){
                                $countWiseBaseCourses[$baseCourseId]++;
                            }

                            $hierarchy = $courseTypeObj->getHierarchies();
                            foreach ($hierarchy as $hierarchyRow) {

                                if($hierarchyRow['stream_id'])
                                    $countWiseStreams[$hierarchyRow['stream_id']]++;

                                if($hierarchyRow['substream_id']){
                                    $countWiseSubStreams[$hierarchyRow['substream_id']]++;
                                    $subStreamInfo[$hierarchyRow['substream_id']]['stream'] = $hierarchyRow['stream_id'];
                                }

                                if($hierarchyRow['specialization_id']){
                                    $countWiseSpecs[$hierarchyRow['specialization_id']]++;
                                }
                            }
                        }
                }

                $baseCourseIds = array_keys($countWiseBaseCourses);
                $specializationIds = array_keys($countWiseSpecs);
                $subStreamIds = array_keys($countWiseSubStreams);
                $streamIds = array_keys($countWiseStreams); 
                arsort($countWiseStreams);
                arsort($countWiseSubStreams);
                arsort($countWiseBaseCourses);

                $this->load->library("nationalCategoryList/NationalCategoryPageLib");
                $nationalCategoryPageLib = new NationalCategoryPageLib();

                $this->load->builder("listingBase/ListingBaseBuilder");
                $listingBaseBuilder = new ListingBaseBuilder();
                $baseCourseRepo = $listingBaseBuilder->getBaseCourseRepository();
                $streamRepo     = $listingBaseBuilder->getStreamRepository();   
                $subStreamRepo  = $listingBaseBuilder->getSubstreamRepository();    

                $categoryPageLinks = array();
                $rankingPageLinks=array();
                $streamObjects     = array();
                $subStreamObjects  = array();
                $baseCourseObjects = array();
                
                $cityId            = $instituteCurrentLocation->getCityId();
                $stateId           = $instituteCurrentLocation->getStateId();
                $cityName          = $instituteCurrentLocation->getCityName();
                $stateName          = $instituteCurrentLocation->getStateName();
                $this->load->builder('LocationBuilder','location');
                $locationBuilder    = new LocationBuilder;
                $locationRepo = $locationBuilder->getLocationRepository();

                if($baseCourseIds || $streamIds){
                    if($baseCourseIds){
                        $baseCourseObjects = $baseCourseRepo->findMultiple($baseCourseIds);
                    }
                    if($streamIds){
                        $streamObjects = $streamRepo->findMultiple($streamIds);
                    }
                    if($subStreamIds){
                        $subStreamObjects = $subStreamRepo->findMultiple($subStreamIds);
                    }
                }
                //if category page links are not stored in redis, then get links from db and store it into redis
                // for stream

                foreach ($countWiseStreams as $streamId => $count) {

                    if(count($categoryPageLinks) >= $categoryPageLinksLimit )
                        continue;

                    $url = $nationalCategoryPageLib->getUrlByParams(
                                                         array('stream_id'=>$streamId, 
                                                               'state_id' =>$stateId,
                                                               'city_id' => $cityId,
                                                               'min_result_count' => $categoryPageResultCount));    
                    if($url){
                        $completeUrl = parse_url($url);
                        $url =  $completeUrl['path'];
                    }


                    if($url){
                        if(!$streamObjects[$streamId]){
                            $streamObjects[$streamId] = $streamRepo->find($streamId);
                        }
                        $streamName = $streamObjects[$streamId]->getAlias() ? $streamObjects[$streamId]->getAlias() : $streamObjects[$streamId]->getName();
                        $categoryPageLinks[] = array('url'=>$url, 'title' => $streamName.' colleges in '.$cityName);
                    }
                }
                // for sub-stream
                foreach ($countWiseSubStreams as $substreamId => $count) {

                    if(count($categoryPageLinks) >= $categoryPageLinksLimit )
                        continue;

                    $url = $nationalCategoryPageLib->getUrlByParams(
                                                         array('stream_id'=>$subStreamInfo[$substreamId]['stream'], 
                                                               'substream_id'=>$substreamId, 
                                                               'state_id' =>$stateId,
                                                               'city_id' => $cityId,
                                                               'min_result_count' => $categoryPageResultCount));    
                    if($url){
                        $completeUrl = parse_url($url);
                        $url =  $completeUrl['path'];
                    }
                    if($url){
                        if(!$subStreamObjects[$substreamId]){
                            $subStreamObjects[$substreamId] = $subStreamRepo->find($substreamId);
                        }
                        $subStreamName = $subStreamObjects[$substreamId]->getAlias() ? $subStreamObjects[$substreamId]->getAlias() : $subStreamObjects[$substreamId]->getName();
                        $categoryPageLinks[] = array('url'=>$url, 'title' => $subStreamName.' colleges in '.$cityName);
                    }
                }
                // for base courses
                foreach ($countWiseBaseCourses as $baseCourseId => $count) {

                    if(count($categoryPageLinks) >= $categoryPageLinksLimit )
                        continue;
                    
                    $url = $nationalCategoryPageLib->getUrlByParams(
                                                         array('base_course_id'=>$baseCourseId, 
                                                               'state_id' =>$stateId,
                                                               'city_id' => $cityId,
                                                               'min_result_count' => $categoryPageResultCount));    
                    if($url){
                        $completeUrl = parse_url($url);
                        $url =  $completeUrl['path'];
                    }
                    if($url){
                        if(!$baseCourseObjects[$baseCourseId]){
                            $baseCourseObjects[$baseCourseId] = $baseCourseRepo->find($baseCourseId);
                        }
                        $baseCourseName = $baseCourseObjects[$baseCourseId]->getAlias() ? $baseCourseObjects[$baseCourseId]->getAlias() : $baseCourseObjects[$baseCourseId]->getName();
                        $categoryPageLinks[] = array('url'=>$url, 'title' => $baseCourseName.' colleges in '.$cityName);
                    }
                }
                //store category Page Links of listings into redis

                $this->nationalinstitutecache->storeCategoryLinksForListings($listingId,json_encode($categoryPageLinks));
            }
            $this->benchmark->mark('cron_end');
            _P('cron run successfully');
        }

        public function populateRankingPageLinksCache(){
            $this->validateCron();
            ini_set("memory_limit", "6000M");
            ini_set('max_execution_time', -1);
            $this->nationalinstitutecache = $this->load->library('nationalInstitute/cache/NationalInstituteCache');

            $institutedetailsmodel = $this->load->model("nationalInstitute/institutedetailsmodel");
            $data = $institutedetailsmodel->getAllinstitutes();
            $index = 0 ;
            foreach ($data as $key => $instituteData) {
                
                $rankingPageLinksLimit = 8;
                $listingId =   $instituteData['listing_id'];
                $listingType = $instituteData['listing_type'];
                $this->load->builder("nationalInstitute/InstituteBuilder");
                $instituteBuilder = new InstituteBuilder();
                $instituteRepo = $instituteBuilder->getInstituteRepository();
                $instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');
                $instituteObj = $instituteRepo->find($listingId,'full');
                $instituteCurrentLocation = $instituteDetailLib->getInstituteCurrentLocation($instituteObj);
                 if(empty($instituteCurrentLocation)){
                    continue;
                }
                $this->load->builder("nationalCourse/CourseBuilder");
                $builder          = new CourseBuilder();
                $courseRepository = $builder->getCourseRepository();
                $courseList = $instituteDetailLib->getInstituteCourseIds($listingId, $listingType);
                $courseList    = $courseList['courseIds'];
                $allCourseList = $courseList;
                // getting all courses because of all base course/stream section
                if($allCourseList){
                    $allCourses  = $courseRepository->findMultiple($allCourseList, array('basic'), false, false);   
                }
                foreach($allCourses as $key1=>$value){
                    if(!$value->getId())
                           unset($allCourses[$key]);
                }
                foreach ($allCourses as $courseObj) {
                    $courseTypeInfo = $courseObj->getCourseTypeInformation();
                    $courseTypeObj  = $courseTypeInfo['entry_course'];
                        if($courseTypeObj){
                            $baseCourseId = $courseTypeObj->getBaseCourse();

                            if($baseCourseId == $mbaBaseCourseId){
                                $mbaCourseIds[] = $courseObj->getId();
                            }
                            if($baseCourseId){
                                $countWiseBaseCourses[$baseCourseId]++;
                            }

                            $hierarchy = $courseTypeObj->getHierarchies();
                            foreach ($hierarchy as $hierarchyRow) {

                                if($hierarchyRow['stream_id'])
                                    $countWiseStreams[$hierarchyRow['stream_id']]++;

                                if($hierarchyRow['substream_id']){
                                    $countWiseSubStreams[$hierarchyRow['substream_id']]++;
                                    $subStreamInfo[$hierarchyRow['substream_id']]['stream'] = $hierarchyRow['stream_id'];
                                }

                                if($hierarchyRow['specialization_id']){
                                    $countWiseSpecs[$hierarchyRow['specialization_id']]++;
                                }
                            }
                        }
                }
                arsort($countWiseStreams);
                arsort($countWiseSubStreams);
                arsort($countWiseBaseCourses);

                $this->load->library("nationalCategoryList/NationalCategoryPageLib");
                $nationalCategoryPageLib = new NationalCategoryPageLib();

                $this->load->builder("listingBase/ListingBaseBuilder");
                $listingBaseBuilder = new ListingBaseBuilder();
                $baseCourseRepo = $listingBaseBuilder->getBaseCourseRepository();
                $streamRepo     = $listingBaseBuilder->getStreamRepository();   
                $subStreamRepo  = $listingBaseBuilder->getSubstreamRepository();    

               
                $rankingPageLinks=array();
                $cityId            = $instituteCurrentLocation->getCityId();
                $stateId           = $instituteCurrentLocation->getStateId();
                $cityName          = $instituteCurrentLocation->getCityName();
                $stateName          = $instituteCurrentLocation->getStateName();

                $this->load->builder('LocationBuilder','location');
                $locationBuilder    = new LocationBuilder;
                $locationRepo = $locationBuilder->getLocationRepository();

                $cityObj = $locationRepo->findCity($cityId);
                $virtualCityId = $cityObj->getVirtualCityId();
                $cityTier = !empty($virtualCityId) ? 1 : $cityObj->getTier();
                $this->load->builder('rankingV2/RankingPageBuilder');
                $rankingBuilder = new RankingPageBuilder();
                $rankingURLManager = $rankingBuilder->getURLManager();
                $temp = array();
                $rankingFetchCityId = empty($virtualCityId) ? $cityId : $virtualCityId;
                // for stream

                foreach ($countWiseStreams as $streamId => $count) {

                    $temp[] = array('stream_id'=>$streamId, 'state_id' =>$stateId,'city_id' => $rankingFetchCityId);
                }
                
                if(!empty($temp)){
                    $links = $rankingURLManager->getRankingUrlsByMultipleParams($temp);
                    $rankingPageLinks = array_merge($rankingPageLinks,$links);
                }

                // for sub-stream
                $temp = array();
                foreach ($countWiseSubStreams as $substreamId => $count) {
                    $temp[] = array('stream_id'=>$subStreamInfo[$substreamId]['stream'], 'substream_id'=>$substreamId, 'state_id' =>$stateId,'city_id' => $rankingFetchCityId);
                }
                if(!empty($temp)){
                    $links = $rankingURLManager->getRankingUrlsByMultipleParams($temp);
                    $rankingPageLinks = array_merge($rankingPageLinks,$links);
                }

                // for base courses
                $temp = array();
                foreach ($countWiseBaseCourses as $baseCourseId => $count) {
                    $temp[] = array('base_course_id'=>$baseCourseId, 'state_id' =>$stateId,'city_id' => $rankingFetchCityId);
                }
                
                if(!empty($temp)){
                    $links = $rankingURLManager->getRankingUrlsByMultipleParams($temp);
                    $rankingPageLinks = array_merge($rankingPageLinks,$links);
                   
                }

                // _p($cityTier);die;
                if(!in_array($cityTier,array(1,2))){
                    $rankingPageLinks = array_filter($rankingPageLinks,function($ele){return ($ele['type'] == 'state');});
                }
                else{
                    $temp1 = array_filter($rankingPageLinks,function($ele){return ($ele['type'] == 'city');});
                    $temp2 = array();
                    // if state name is not same as city name
                    if(strpos($cityName, $stateName) === false){
                        $temp2 = array_filter($rankingPageLinks,function($ele){return ($ele['type'] == 'state');});
                    }
                    $rankingPageLinks = array_merge($temp1,$temp2);
                }

                $rankingPageLinks = array_slice($rankingPageLinks,0,8);
                $this->nationalinstitutecache->storeRankingLinksForListings($listingId,$rankingPageLinks);
            }
            $this->benchmark->mark('cron_end');
            _P('cron complete successfully');
        }

    public function populateCourseWidgetForUpdatedCourseCache($courseIds){
        $this->validateCron();
        ini_set("memory_limit", "6000M");
        ini_set('max_execution_time', -1);

        $coursedetailmodel = $this->load->model("nationalCourse/coursedetailmodel");
        $data = $coursedetailmodel->getPrimaryInstituteForCourse($courseIds);
        
        _p("cron starts");
        foreach ($data as $key => $value) {
            $this->populateCourseWidgetCacheForInstitute($value['primary_id']);
        }
        _p("cron ends successfully");
    }

    public function populateCoursesCache(){
        $this->validateCron();
        
        _p("cron starts");
        $this->populateCourseWidgetCache();
        $this->populateAdmissionPageCoursesCache();
        _p("cron ends successfully");
   }

    public function populateCourseWidgetCache(){
        $this->validateCron();
        ini_set("memory_limit", "6000M");
        ini_set('max_execution_time', -1);

        $institutedetailsmodel = $this->load->model("nationalInstitute/institutedetailsmodel");
        $data = $institutedetailsmodel->getAllinstitutes();

        _p("cron starts");
        foreach ($data as $key => $instituteData){
            $listingId =   $instituteData['listing_id'];
            $listingType = $instituteData['listing_type'];
            $this->populateCourseWidgetCacheForInstitute($listingId);
        }
        _p("cron ends successfully");
        
    }

   public function cacheSanitizedBaseEntities(){
        $this->validateCron();
        ini_set("memory_limit", "6000M");
        ini_set('max_execution_time', -1);

        $this->nationalinstitutecache = $this->load->library('nationalInstitute/cache/NationalInstituteCache');
        $this->load->builder('ListingBaseBuilder','listingBase');

        $this->listingBaseBuilder    = new ListingBaseBuilder();
        $this->baseCourseRepo = $this->listingBaseBuilder->getBaseCourseRepository();
        $allbaseCourses = $this->baseCourseRepo->getAllBaseCourses();
        $sanitizedBaseEntitiesData['baseCourse'] = array();
        $sanitizedBaseEntitiesData['stream'] = array();

        foreach ($allbaseCourses as $val) {
            $urlString = sanitizeUrlString($val['name']);
            $sanitizedBaseEntitiesData['baseCourse'][$urlString] = $val['id'];
        }
        $HierarchyRepository = $this->listingBaseBuilder->getHierarchyRepository();
        $allStreams = $HierarchyRepository->getAllStreams();
         foreach ($allStreams as $val) {
            $urlString = sanitizeUrlString($val['name']);
            $sanitizedBaseEntitiesData['stream'][$urlString] = $val['id'];
        }

        $this->nationalinstitutecache->storeSanitizedBaseEntities($sanitizedBaseEntitiesData);
   }


   public function populateAdmissionPageCoursesCache(){
        $this->validateCron();
        ini_set("memory_limit", "6000M");
        ini_set('max_execution_time', -1);

        $institutedetailsmodel = $this->load->model("nationalInstitute/institutedetailsmodel");
        $data = $institutedetailsmodel->getAllinstitutes();

        _p("cron starts");
        foreach ($data as $key => $instituteData){
            $listingId =   $instituteData['listing_id'];
            $this->populateAdmissionPageCoursesData($listingId);
        }
        _p("cron ends successfully");
        
    }

  public function populateAdmissionPageCoursesData($listingId){
        $result = array();
       $this->validateCron();

        ini_set("memory_limit", "6000M");
        ini_set('max_execution_time', -1);

        $listingsCronsLib = $this->load->library('nationalInstitute/ListingsCronsLib');
        $listingsCronsLib->populateAdmissionPageCoursesData($listingId);
        
     }

    public function populateCourseWidgetCacheForInstitute($listingId){
        $this->validateCron();
        ini_set("memory_limit", "6000M");
        ini_set('max_execution_time', -1);
        
        $listingsCronsLib = $this->load->library('nationalInstitute/ListingsCronsLib');
        $listingsCronsLib->populateCourseWidgetCacheForInstitute($listingId);    
    } 

   public function rankingEnterpriseCron(){
        $this->validateCron();
        ini_set("memory_limit", "2000M");
        ini_set('max_execution_time', -1);    
        $this->load->builder('RankingPageBuilder', RANKING_PAGE_MODULE);
        $this->rankingEnterpriseLib = RankingPageBuilder::getRankingPageEnterpriseLib();
        $data = $this->rankingEnterpriseLib->getUnprocessedRankingPages();

        $allRankingPageIds = array_keys($data);
        foreach($allRankingPageIds as $rankingPageId){
            
            $allSource = array_keys($data[$rankingPageId]['sourceWiseData']);
            foreach ($allSource  as $source){
               $this->updateDataforRankingPageIDandSource($rankingPageId,$source,$data[$rankingPageId]);
               $this->rankingEnterpriseLib->markDataAsProcessed($rankingPageId,$source);
              error_log("Cron for ranking_page_id : ".$rankingPageId." and source_id : ".$source." complete");
            }   
        }
    }

   public function updateDataforRankingPageIDandSource($rankingPageId,$source,$data){
        $this->load->builder('RankingPageBuilder', RANKING_PAGE_MODULE);
       
        $rankingCommonLib = $this->load->library(RANKING_PAGE_MODULE.'/rankingCommonLib');
        $courseIds= $this->rankingEnterpriseLib->updateDataforRankingPageIDandSource($rankingPageId,$source,$data);
        $rankingCommonLib->invalidateRankingObjectCache(array($rankingPageId));
        $pageLiveFlag = $rankingCommonLib->checkIfRankingPageExists($rankingPageId);
        if($pageLiveFlag && !empty($courseIds)){
            $rankingCommonLib->indexCourseForRanking($courseIds);
        }
   }    

   //this cron populates flags(checks existence) for child pages
   public function populateChildPageExistsSection($instituteIds){
    // $this->validateCron(); 
    ini_set("memory_limit", "5000M");
    ini_set('max_execution_time', -1);  
    $institutedetailsmodel = $this->load->model("nationalInstitute/institutedetailsmodel");
    $listingsCronsLib = $this->load->library('nationalInstitute/ListingsCronsLib');
    $nationalinstitutecache = $this->load->library('nationalInstitute/cache/NationalInstituteCache');
    if(!is_array($instituteIds) && !empty($instituteIds)) {
        $instituteIds = array($instituteIds);
    }
    $listingIds = array();
    if(empty($instituteIds)){
        $listingIds = $institutedetailsmodel->getAllPrimaryInstitutes();
        /*foreach ($data as $key => $instituteData){
            $listingIds[] = $instituteData['listing_id'];
        }    */
    }
    else {
        $listingIds = $instituteIds;
    }
    //get placement page flags
    $placementPageFlags = $listingsCronsLib->getPlacementPageFlags($listingIds); 
    //get cutoff page flags
    $cutoffPageFlag = $listingsCronsLib->getCutoffPageFlag($listingIds);
    //get review page flag
    $reviewPageFlag = $listingsCronsLib->getReviewPageFlags($listingIds);
    //get all course page flag
    $allCoursePageFlag = $listingsCronsLib->getAllCoursePageFlags($listingIds);
    //get admission page flag
    $admissionPageFlag = $listingsCronsLib->getAdmissionPageFlags($listingIds);

    foreach ($placementPageFlags as $instituteId => $value) {
        if($cutoffPageFlag[$instituteId]['cutoffPageExists']){
            $childPageExists = array(array("listing_id"=>(string)$instituteId, "placementPageExists" => $placementPageFlags[$instituteId]["placementPageExists"], "flagshipCoursePlacementDataExists" => $placementPageFlags[$instituteId]["flagshipCoursePlacementDataExists"], "naukriPlacementDataExists" => $placementPageFlags[$instituteId]["naukriPlacementDataExists"], "cutoffPageExists" => true, "cutoffPageExamName" => $cutoffPageFlag[$instituteId]['examName'],
                "reviewPageExists" => $reviewPageFlag[$instituteId],
                "admissionPageExists" => $admissionPageFlag[$instituteId],
                "allCoursePageExists" => $allCoursePageFlag[$instituteId]
        ));
        }
        else{
            $childPageExists = array(array("listing_id"=>(string)$instituteId, "placementPageExists" => $placementPageFlags[$instituteId]["placementPageExists"], "flagshipCoursePlacementDataExists" => $placementPageFlags[$instituteId]["flagshipCoursePlacementDataExists"], "naukriPlacementDataExists" => $placementPageFlags[$instituteId]["naukriPlacementDataExists"], "cutoffPageExists" => false,"cutoffPageExamName" => $cutoffPageFlag[$instituteId]['examName'],
                "reviewPageExists" => $reviewPageFlag[$instituteId],
                "admissionPageExists" => $admissionPageFlag[$instituteId],
                "allCoursePageExists" => $allCoursePageFlag[$instituteId]
        ));
        }

        _p("instituteId");
        _p($instituteId);
        _p($childPageExists);
        error_log("instituteId");
        error_log($instituteId);
        error_log($childPageExists);

        $nationalinstitutecache->storeInstituteSection($instituteId, "childPageExists", $childPageExists);
    }
    _p("cron ends");
    error_log("cron ends");
   }

} ?>
