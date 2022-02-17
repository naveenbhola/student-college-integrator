<?php
/**
 *Copyright 2015-16 Info Edge India Ltd
 *$Author: Akhter (UGC Team)
 *$Id: Mentor Controller
 **/

class MentorController extends MX_Controller
{

        function init($library=array('ajax'),$helper=array('url','image','shikshautility','utility_helper')){
		if(is_array(  $helper)){
			$this->load->helper($helper);
		}
		if(is_array($library)){
			$this->load->library($library);
		}
		if(($this->userStatus == "")){
			$this->userStatus = $this->checkUserValidation();
		}
		
		$this->load->helper('coursepages/course_page');
		
		$this->load->model('mentormodel');
		$this->mentormodel = new mentormodel();
		
		$this->load->config('MentorConfig',TRUE);
		$this->mentorConfig = $this->config->item('settings','MentorConfig');
	}

	/**
	 * Mentor Homapage
	 * @param  : None
	 * @return : None
	 * @author : akhter
	 */
	function mentorHomepage($url){
		redirect(SHIKSHA_SCIENCE_HOME."/engineering-questions-coursepage",'location',301);exit;
		$this->init();
		$displayData = array();
		$addCategoryUrl = explode('-',$url);
		$addCategoryUrl = $addCategoryUrl[1];
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;	

		//assigning page identifier value in array for beacon tracking
		$displayData['trackingpageIdentifier']= 'mentorshipPage';
		
                // Engineering Nav Bar, Hard code for Be/B.tech
                $subcatId = 56;
		$subCatName  =  ($subcatId == 56) ? 'Engineering' : 'MBA';
				$displayData['subcatId'] = $subcatId;
                $displayData['tab_required_course_page'] = checkIfCourseTabRequired($subcatId);
                $displayData['subcat_id_course_page'] = $subcatId;
                $displayData['course_pages_tabselected'] = '';
		
		//Google Remarketing Code
                $googleRemarketingParams = array(
                                "categoryId"    => '2',
                                "subcategoryId" => '56',
                                "countryId"     => '2',
                                "cityId"        => ''
                );
                $displayData['googleRemarketingParams'] = $googleRemarketingParams;
		
		//Get SEO Details
                $displayData['m_meta_title'] = 'Mentors for '.ucfirst($addCategoryUrl).' Exams, Colleges & Courses - Shiksha.com';
                $displayData['m_meta_description'] = 'Get a dedicated student mentor to guide for Engineering exams, selecting colleges and courses. Connect with current '.$addCategoryUrl.' students for entire '.$addCategoryUrl.' preparation.';
                $displayData['canonicalURL'] = SHIKSHA_HOME.'/mentors-'.$addCategoryUrl.'-exams-colleges-courses';
		
		$displayData['totalMentor'] = $this->mentormodel->getMentorDetails($subcatId);
		$displayData['totalMentor']['result'] = array_slice($displayData['totalMentor']['result'],0,8);
		$this->_prepareMentorList($displayData);
		
		$this->load->library('listing/cache/ListingCache');
		$this->load->library('category_list_client');
		$this->listingCache = new ListingCache;
		$categoryClient     = new Category_list_client();
		$exam_list = $this->listingCache->getExamsList();
	        if(empty($exam_list)){
	            $exam_list = $categoryClient->getTestPrepCoursesList(1);
	            $this->listingCache->storeExamsList($exam_list);
	        }
		$exam_list = $this->prepareMExamList($exam_list);
		$displayData['exam_list'] = $exam_list[$subCatName];
		$displayData['branchList'] = $this->mentorConfig['mentorBranchName'];
		
		$this->load->helper('string');
		$displayData['regFormId'] = random_string('alnum', 6);
		$displayData['isMentee']  = $this->existMentee();
		$cacheLib = $this->load->library('cacheLib');
		$locationKey = 'getMentorLocationlist';
                if($cacheLib->get($locationKey)=='ERROR_READING_CACHE'){
			$locationList = $this->getLocationList();
			$cacheLib->store($locationKey,$locationList, 86400);
		}else{
			$locationList = $cacheLib->get($locationKey);
		}
		$displayData['locationList'] = $locationList;

		$displayData['trackingsubCatID']=$subcatId;
		$displayData['trackingcatID']=2;
		$displayData['trackingcountryId']=2;
		//loading library to use store beacon traffic inforamtion
		$this->tracking=$this->load->library('common/trackingpages');

		$this->tracking->_pagetracking($displayData);

		//below lines used for conversiontracking purpose
		$displayData['topTrackingPageKeyId'] = isset($_GET['tracking_keyid']) && is_numeric($_GET['tracking_keyid']) ? $_GET['tracking_keyid'] : 171;
		$displayData['bottomTrackingPageKeyId']=172;
		
		$this->load->view('mentorship/mentorHomepage',$displayData);
	}

	private function _prepareMentorList(&$displayData) {
		$mentorListData = $displayData['totalMentor']['result'];
		
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder  = new ListingBuilder;
		$insRepo = $listingBuilder->getInstituteRepository();
		$courseRepo = $listingBuilder->getCourseRepository();
		
		$this->load->builder('LocationBuilder','location');
		$locationBuilder  = new LocationBuilder;
		$locationRepo = $locationBuilder->getLocationRepository();
		
		$this->load->model('CAEnterprise/caenterprisemodel');
		$enterpriseModel = new caenterprisemodel;
		$mentorCount = 1;	
		foreach($mentorListData as $key=>$listData)
		{
			//$mentorListData[$key]['menteeCount'] = $enterpriseModel->findMenteeCount($listData['userId']);
			
			if($listData['instituteId'] > 0) {
			$insObj = $insRepo->find($listData['instituteId']);
			}
			if($listData['courseId'] > 0) {
			$courseObj = $courseRepo->find($listData['courseId']);
			}
			if($listData['locationId'] > 0){
			$insLoc = $insObj->getLocations();
			}
			$insLocObj = $insLoc[$listData['locationId']];
			if(empty($insLocObj)){
                                unset($mentorListData[$key]);
                                continue;
                        }
			$cityObj = $insLocObj->getCity();
			$mentorListData[$key]['menteeCount'] = $enterpriseModel->findMenteeCount($listData['userId']);
			$mentorListData[$key]['instituteName'] = $insObj->getName();
			$mentorListData[$key]['instituteURL'] = $insObj->getURL();
			$mentorListData[$key]['courseName'] = $courseObj->getName();
			$mentorListData[$key]['courseURL'] = $courseObj->getURL();
			$mentorListData[$key]['city'] = $cityObj->getName();
		}
		$displayData['mentorListData'] = $mentorListData;
		$displayData['pageNo'] = isset($displayData['pageNo']) ? $displayData['pageNo'] : 0;
		$displayData['mentorCount'] =  $displayData['totalMentor']['totalMentor'];
	}
	
	function getMentorListAjax($pageNo,$subcatId) {
		$this->init();
		$displayData['pageNo'] = $pageNo;
		$displayData['subcatId'] = $subcatId;
		$displayData['totalMentor']  = $this->mentormodel->getMentorDetails($subcatId);
		$displayData['totalMentor']['result'] = array_slice($displayData['totalMentor']['result'],$pageNo*8,8);
		$this->_prepareMentorList($displayData);
		echo $this->load->view('mentorship/studentMentors',$displayData,true);
	}

	/***
	 * functionName : prepareMExamList
	 * functionType : return type
	 * param        : $exam_list (array)
	 * desciption   : formats exam list
	 * @author      : akhter
	 * @team        : UGC
	***/
	
	function prepareMExamList($exam_list = array()) {

		$final_exam_list = array();
		if(count($exam_list) >0) {
			foreach ($exam_list as $list) {
				foreach ($list as $list_child) {
					$final_exam_list[$list_child['acronym']][] = $list_child['child']['acronym'];
				}
			}
		}
		//Entry for no exam required in MBA
		if(!empty($final_exam_list['MBA'])){
			$final_exam_list['MBA'][] = "No Exam Required";
		}
		return $final_exam_list;
	}
	
	/***
	 * functionName : addMentee
	 * functionType : return type
	 * param        : POST type
	 * desciption   : this function is calling on get a mentor form
	 * @author      : akhter
	 * @team        : UGC
	***/
	
	function addMentee()
	{
		$userId    = isset($_POST['userId']) ? $this->input->post('userId') : 0;
		
		if($userId>0)
		{
			$examList  = isset($_POST['examList']) ? $this->input->post('examList') : '';
			$examScore  = isset($_POST['examScore']) ? $this->input->post('examScore') : '';
			$examYr    = isset($_POST['examYr']) ? $this->input->post('examYr') : '';
			$targetClg = isset($_POST['targetClg']) ? $this->input->post('targetClg') : null;
			$branchPref1 = isset($_POST['branchPref1']) ? $this->input->post('branchPref1') : null;
			$branchPref2 = isset($_POST['branchPref2']) ? $this->input->post('branchPref2') : null;
			$branchPref3 = isset($_POST['branchPref3']) ? $this->input->post('branchPref3') : null;
			$prefC1 = isset($_POST['prefC1']) ? $this->input->post('prefC1') : null;
			$prefC2 = isset($_POST['prefC2']) ? $this->input->post('prefC2') : null;
			$prefC3 = isset($_POST['prefC3']) ? $this->input->post('prefC3') : null;
		
			$this->load->model('mentormodel');
			$this->mentormodel = new mentormodel();
			$menteeId = $this->mentormodel->addMentee($userId,$prefC1,$prefC2,$prefC3,$examYr,$branchPref1,$branchPref2,$branchPref3,$targetClg);
			if($menteeId>0)
			{       if(count($examList)>0)
				{
					foreach($examList as $key=>$examName){
						$res = $this->mentormodel->addMenteeExam($menteeId,$examName,$examScore[$key]);
					}
				}
				$this->load->library('MentorMailers');
				$mentorMailer = new MentorMailers;
				$mentorMailer->sendMenteeRegistrationMailertoMentee($menteeId);
				$layer = $this->load->view('mentorship/successLayer',array(),true);
				echo json_encode(array('response'=>'success','thankuLayer'=>$layer));	
			}
		}	
	}
	
	/***
	 * functionName : existMentee
	 * functionType : return type
	 * param        : POST type
	 * desciption   : this function is used to check exist mentee for exist user
	 * @author      : akhter
	 * @team        : UGC
	***/
	
	function existMentee()
	{
		$this->init();
		$displayData = array();
		$displayData['validateuser'] = $this->userStatus;
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;				
		$res = $this->mentormodel->existMentee($userId);
		if($res>0)
		{
			return true;
		}
		return false;
	}
	
	/***
	 * functionName : getLocationList
	 * functionType : return type json
	 * param        : no
	 * desciption   : this function is prepare all stats and city(top+other) list
	 * @author      : akhter
	 * @team        : UGC
	***/
	
	
	function getLocationList()
	{
		$this->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder;
		$locationRepository = $locationBuilder->getLocationRepository();
		$cityList = $locationRepository->getCitiesByMultipleTiers(array(1,2,3),2);
		$data['cityList'] = $cityList;
		$data['locationRepository'] = $locationRepository;
		
		$i=0;
		foreach($cityList[1] as $city){
			$cityListArray['cityNames'][$i]['Name'] = $city->getName();
			$cityListArray['cityNames'][$i]['Id'] = $city->getId();
			$topCityListArray['cityNames'][$i]['Name'] = $city->getName();
			$topCityListArray['cityNames'][$i]['Id'] = $city->getId();
			$i++;
		}
		
		foreach($cityList[2] as $city){
			$cityListArray['cityNames'][$i]['Name'] = $city->getName();
			$cityListArray['cityNames'][$i]['Id'] = $city->getId();
			$otherCityListArray[$city->getName()][$i]['Name'] = $city->getName();
			$otherCityListArray[$city->getName()][$i]['Id'] = $city->getId();
			$i++;
		}
	    
		foreach($cityList[3] as $city){
			$cityListArray['cityNames'][$i]['Name'] = $city->getName();
			$cityListArray['cityNames'][$i]['Id'] = $city->getId();
			$otherCityListArray[$city->getName()][$i]['Name'] = $city->getName();
			$otherCityListArray[$city->getName()][$i]['Id'] = $city->getId();
			$i++;
		} 
		ksort($otherCityListArray);
		
		$states = $locationRepository->getStatesByCountry(2);
		global $EXCLUDED_STATES_IN_LOCATION_LAYER;
		foreach($states as $state){
		    if(in_array($state->getId(),$EXCLUDED_STATES_IN_LOCATION_LAYER)){//Hiding Delhi State
			    continue;
		    }
		    $stateListArray['stateNames'][$i]['Name'] = $state->getName();
		    $stateListArray['stateNames'][$i]['Id'] = $state->getId();
		    $i++;
		}
		foreach( $topCityListArray['cityNames'] as $key=>$value){
			$cityArr['cityList'][] = array('cityId'=>$value['Id'],'cityName'=>$value['Name']);
		}
		foreach( $otherCityListArray as $key=>$value){
			foreach($value as $k=>$v){
			$cityArr['cityList'][] = array('cityId'=>$v['Id'],'cityName'=>$v['Name']);
			}
		}
		foreach( $stateListArray['stateNames'] as $key=>$value){
			$stateArr['stateList'][] = array('stateId'=>$value['Id'],'stateName'=>$value['Name']);
		}
		return json_encode(array('cityList'=>$cityArr,'stateList'=>$stateArr));
	}

	function bookFreeSlotByMentee(){
		$this->init();
		$params = array();
		$params['slotId'] = $this->input->post('slotId');
		$status = $this->mentormodel->checkSlotStatus($params['slotId']);
		if($status=='free'){
			$params['menteeId'] = $this->input->post('menteeId');
			$params['mentorId'] = $this->input->post('mentorId');
			$params['discussionTopic'] = $this->input->post('discussionTopic');
			$this->mentormodel->bookFreeSlotByMentee($params);
			$slotDetails = $this->mentormodel->getSlotDetailsById($params['slotId']);
			$menteeDetails = $this->mentormodel->getUserDetails($params['menteeId']);
			$mentorDetails = $this->mentormodel->getUserDetails($params['mentorId']);
			
			$mentorLandingPage = SHIKSHA_HOME."/CA/CRDashboard/myChatTab";
			$contentArr['mentorLandingPage'] = $mentorLandingPage;
			$menteeLandingPage = SHIKSHA_HOME."/user/MyShiksha/index";
			$contentArr['menteeLandingPage'] = $menteeLandingPage;
			
			$contentArr['menteeDetails'] = $menteeDetails;
			$contentArr['mentorDetails'] = $mentorDetails;
			$contentArr['slotTime'] = date('j F, h:i A',strtotime($slotDetails[0]['slotTime'])).' - '.date('h:i A',strtotime($slotDetails[0]['slotTime'])+1800);
			
			Modules::run('systemMailer/SystemMailer/chatScheduledMailToMentee', $menteeDetails[0]['email'], $contentArr);
			Modules::run('systemMailer/SystemMailer/chatScheduledMailToMentor', $mentorDetails[0]['email'], $contentArr);
			
			//send mail to internal team - added by Virender Singh for UGC-3018 - start
			$this->load->library('MentorMailers');
			$mentorMailer = new MentorMailers;
			$mailerData = array();
			$mailerData['mailSubject'] = 'Mentorship chat notification - Chat scheduled by mentee';
			$mailerData['slotTimeStr'] = date('j F Y, h:i A',strtotime($slotDetails[0]['slotTime'])).' - '.date('h:i A',strtotime($slotDetails[0]['slotTime'])+1800);
			$mailerData['actionTaken'] = 'Scheduled';
			$mailerData['mentorName']  = ucwords($mentorDetails[0]['firstname'].' '.$mentorDetails[0]['lastname']);
			$mailerData['mentorEmail'] = $mentorDetails[0]['email'];
			$mailerData['menteeName']  = ucwords($menteeDetails[0]['firstname'].' '.$menteeDetails[0]['lastname']);
			$mailerData['menteeEmail'] = $menteeDetails[0]['email'];
			$mentorMailer->mentorshipProgramInternalTeamMailers('chatSchedulingActionMailer', $mailerData);
			//send mail to internal team - added by Virender Singh for UGC-3018 - end
		}
		echo $status;exit;
	}

	function requestChatSlotbyMentee(){
		$this->init();
		$ymd = explode('/', $this->input->post('slotDate'));
		$date = $ymd[2].'-'.$ymd[1].'-'.$ymd[0].', '.$this->input->post('slotHour').':'.$this->input->post('slotMin').' '.$this->input->post('amPmStr');
		$data = array();
		$data['userId']           = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$data['slotTime']         = date('Y-m-d H:i:s', strtotime($date));
		$data['discussionTopic']  = $this->input->post('discussionTopic');
		$data['userType']         = 'mentee';
		$data['slotStatus']       = 'free';
		$data['createdDate']      = date('Y-m-d H:i:s');
		$data['modificationDate'] = date('Y-m-d H:i:s');
		$data['mentorId']         = $this->input->post('mentorId');
		$slotStatus = $this->mentormodel->checkIfRquestedSlotAlreadyBooked($data['slotTime'],$data['mentorId'],$data['userId']);
		if($slotStatus=='1'){
			echo "BOOKED";exit;
		}
		$new_time = date("Y-m-d H:i:s", strtotime('+2 hours'));
		if(strtotime($data['slotTime'])<=strtotime($new_time)){
			echo "PAST_TIME";exit;
		}
		$this->mentormodel->addMentorShipChatSlot($data);
		
		$mentordetails = $this->mentormodel->getUserDetails($data['mentorId']);
		
		$contentArr['mentordetails'] = $mentordetails;
		$contentArr['menteefirstname'] = $this->userStatus[0]['firstname'];
		$contentArr['menteeLastname'] = $this->userStatus[0]['lastname'];
		
		$contentArr['slotTime'] = date('j F, h:i A',strtotime($data['slotTime'])).' - '.date('h:i A',strtotime($data['slotTime'])+1800);
		
		$urlOfLandingPage = SHIKSHA_HOME."/CA/CRDashboard/myChatTab";
		$contentArr['urlOfLandingPage'] = $urlOfLandingPage;
		
		Modules::run('systemMailer/SystemMailer/requestChatByMentee',$mentordetails[0]['email'],$contentArr);
		
		//send mail to internal team - added by Virender Singh for UGC-3018 - start
		$this->load->library('MentorMailers');
		$mentorMailer = new MentorMailers;
		$mailerData = array();
		$mailerData['mailSubject'] = 'Mentorship chat notification - New chat request by mentee';
		$mailerData['slotTimeStr'] = date('j F Y, h:i A',strtotime($data['slotTime'])).' - '.date('h:i A',strtotime($data['slotTime'])+1800);
		$mailerData['actionTaken'] = 'Requested';
		$mailerData['mentorName']  = ucwords($mentordetails[0]['firstname'].' '.$mentordetails[0]['lastname']);
		$mailerData['mentorEmail'] = $mentordetails[0]['email'];
		$mailerData['menteeName']  = ucwords($this->userStatus[0]['firstname'].' '.$this->userStatus[0]['lastname']);
		$menteeEmail = explode('|', $this->userStatus[0]['cookiestr']);
		$mailerData['menteeEmail'] = $menteeEmail[0];
		$mentorMailer->mentorshipProgramInternalTeamMailers('chatSchedulingActionMailer', $mailerData);
		//send mail to internal team - added by Virender Singh for UGC-3018 - end
		echo 'done';exit;
	}

	function getMentorInlineWidget($frompage, $categoryId,$tracking_keyid){
		if(!isset($categoryId)){
			$categoryId = 56;
		}
		$displayData['isMentee']  = $this->existMentee();
		$displayData['frompage'] = $frompage;
		$displayData['tracking_keyid'] = $tracking_keyid;
		$displayData['totalMentorCount'] = $this->load->mentormodel->getTotalMentorCount($categoryId);
		$this->load->view('mentorship/studentMentorInlineWidget',$displayData);
	}

	function cancelScheduledChat()
	{
		$this->init();
		$slotId       = (isset($_POST['slotId']) && $_POST['slotId'] != '')?$this->input->post('slotId'):0;
		$scheduleId   = (isset($_POST['scheduleId']) && $_POST['scheduleId'] != '')?$this->input->post('scheduleId'):0;
		$userId       = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$userType      = isset($_POST['userType'])?$this->input->post('userType'):'mentor';
		$mentorId = (isset($_POST['mentorId']) && $_POST['mentorId'] != '')?$this->input->post('mentorId'):0;
	
		if($userType=='mentee'){
			$this->mentormodel->updateChatSlotStatus('decline', $slotId);
		}else{
			$this->mentormodel->updateChatSlotStatus('free', $slotId);
		}
		$this->mentormodel->updateChatScheduleStatus('cancelled', $userId, $scheduleId);
		
		$mentorDetails = $this->mentormodel->getUserDetails($mentorId);
		$contentArr['mentorDetails'] = $mentorDetails;
		$slotDetails = $this->mentormodel->getSlotDetailsById($slotId);
		
		$contentArr['slotTime'] = date('j F, h:i A',strtotime($slotDetails[0]['slotTime'])).' - '.date('h:i A',strtotime($slotDetails[0]['slotTime'])+1800);
		
		if($scheduleId>0){
			Modules::run('systemMailer/SystemMailer/chatSessionCancelledByMentee', $mentorDetails[0]['email'], $contentArr);
			
			//send mail to internal team - added by Virender Singh for UGC-3018 - start
			$this->load->library('MentorMailers');
			$mentorMailer = new MentorMailers;
			$mailerData = array();
			$mailerData['mailSubject'] = 'Mentorship chat notification - Chat cancelled by mentee';
			$mailerData['slotTimeStr'] = date('j F Y, h:i A',strtotime($slotDetails[0]['slotTime'])).' - '.date('h:i A',strtotime($slotDetails[0]['slotTime'])+1800);
			$mailerData['actionTaken'] = 'Cancelled';
			$mailerData['mentorName']  = ucwords($mentorDetails[0]['firstname'].' '.$mentorDetails[0]['lastname']);
			$mailerData['mentorEmail'] = $mentorDetails[0]['email'];
			$mailerData['menteeName']  = ucwords($this->userStatus[0]['firstname'].' '.$this->userStatus[0]['lastname']);
			$menteeEmail = explode('|', $this->userStatus[0]['cookiestr']);
			$mailerData['menteeEmail'] = $menteeEmail[0];
			$mentorMailer->mentorshipProgramInternalTeamMailers('chatSchedulingActionMailer', $mailerData);
			//send mail to internal team - added by Virender Singh for UGC-3018 - end
		}
		echo 'success';die;
	}
	
	function startChatSession()
	{
		$this->init();
		$scheduleId = (isset($_POST['scheduleId']) && $_POST['scheduleId'] != '')?$this->input->post('scheduleId'):0;
		$userId     = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		if($scheduleId > 0 && $userId > 0)
		{
			$this->mentormodel->startChatSession($scheduleId);
		}
	}

	function getMenteeListWidget(){
		$this->init();
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$res = $this->mentormodel->getAllMentees($userId);
		$data = array();
		$this->load->builder('LocationBuilder','location');
		$locationBuilder  = new LocationBuilder;
		$locationRepo = $locationBuilder->getLocationRepository();

		foreach ($res as $key => $menteeDetails)
		{
			$menteeUserId = $menteeDetails['menteeId'];
			$menteeId = $this->mentormodel->getMenteeId($menteeUserId);
			$menteePreferenceDetails = $this->mentormodel->getMenteeDetails($menteeId);
			$menteeBasicDetails = $this->mentormodel->getMenteeEmailIdAndName($menteeUserId);
			$menteeExamDetails = $this->mentormodel->getMenteeExamTaken($menteeId);
			$data[$key] = array_merge($menteePreferenceDetails[0], $menteeBasicDetails[0]);
			if($data[$key]['city'] != '' && is_numeric($data[$key]['city'])) {
				$cityObj = $locationRepo->findCity($data[$key]['city']);
				if($cityObj == '')
				{
					unset($data);
					continue;
				}
				$data[$key]['cityName'] = $cityObj->getName();
			} else {
				$data[$key]['cityName'] = 'Not Available';
			}
			$data[$key]['examName'] = implode(', ', array_map(function ($entry) {
		  	return $entry['examName'];}, $menteeExamDetails));
		  	$data[$key]['fullName'] = ucwords($data[$key]['firstname'].' '.$data[$key]['lastname']);
		  	$locationArr = array();
		  	$locationArr[] = $data[$key]['prefCollegeLocation1'];
		  	$locationArr[] = $data[$key]['prefCollegeLocation2'];
		  	$locationArr[] = $data[$key]['prefCollegeLocation3'];
		  	$locationArr = array_filter($locationArr);
		  	$locationArr = implode(", ",$locationArr);
		  	$branchArr = array();
		  	$branchArr[] = $data[$key]['prefEngBranche1'];
		  	$branchArr[] = $data[$key]['prefEngBranche2'];
		  	$branchArr[] = $data[$key]['prefEngBranche3'];
		  	$branchArr = array_filter($branchArr);
		  	$branchArr = implode(", ", $branchArr);
		  	$data[$key]['prefferedLocationCombined'] = $locationArr;
		  	$data[$key]['prefferedBranchCombined'] = $branchArr;
		}
		$displayData['getMenteeList'] = $data;
		$this->load->view('mentorship/menteeListWidget', $displayData);
	}
	
}?>
