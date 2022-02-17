<?php

class ApplyHomeLib {
    private $CI;
    private $applyHomePageModel;
    //private $cacheLib;
    //private $useCache = true;
    //private $cacheTimeLimit = 90000;
	function __construct(){
        $this->CI =& get_instance();
        $this->_setDependencies();
    }
    
    private function _setDependencies(){
        //$this->CI->load->library('Common/cacheLib');
        //$this->cacheLib = new cacheLib();
        $this->applyHomePageModel = $this->CI->load->model('applyHome/applyhomepagemodel');
    }
    
    public function getCounsellorDataByUrl($userEnteredUrl){
        if(empty($userEnteredUrl)){
            return;
        }else {
            return $this->applyHomePageModel->getCounsellorDataByUrl($userEnteredUrl);
        }
    }

    public function validateUrl(){
        $userEnteredUrl = str_replace(SHIKSHA_STUDYABROAD_HOME,'',getCurrentPageURLWithoutQueryParams());
        $counsellorData = $this->getCounsellorDataByUrl($userEnteredUrl);
		if(!empty($counsellorData['counsellor_id'])){
			return $counsellorData;
		}else{
			show_404_abroad();
		}
	}
    /*
     *  To get SEO data 
     */
    public function getApplyHomeSeoData(){
		$seoData = array();
        $seoData['canonicalUrl']    	= SHIKSHA_STUDYABROAD_HOME.'/apply';
		$seoData['seoTitle']           = 'Education Counseling for Study Abroad Programs';
		$seoData['seoDescription']     = 'Get free study abroad counseling & guidance from a team of career counselors to know about various career options and make an educational decision.';
        return $seoData;
    }
    
    public function getCounselorHomeSeoData(&$counsellorData){
		$seoData = array();
        $seoData['canonicalUrl']       = SHIKSHA_STUDYABROAD_HOME.$counsellorData['seoUrl'];
		$seoData['seoTitle']           = ucfirst($counsellorData['counsellor_name']).' - Education and Career Counselor';
		$seoData['seoDescription']     = 'Get free education and career guidance from counselor '.$counsellorData['counsellor_name'].' to select the best college and course for studying abroad';
        return $seoData;
    }


    public function getSuccessStoryWidgetDetails(){
        $this->CI->load->config('studyAbroadCounselorInfoConfig');
        $successVideoArray = $this->CI->config->item('successVideoArray');
	if(isMobileSite() !== false)
	{
		$successVideoArray = array_slice($successVideoArray,0,3);
	}
        /*$contentIds = array_map(function($a){ return $a['articleId']; },$successVideoArray);
        $saContentModel = $this->CI->load->model('blogs/sacontentmodel');
        $contentDetailsArry = $saContentModel->getMultipleContentUrlAndTitleForMailer($contentIds);
        if(count($contentDetailsArry)==0){
            return array();
        }   
        foreach ($contentDetailsArry as $key => $value) {
            $contentDetails[$value['content_id']] = $value; 
        }
        */
        return $successVideoArray;
    }
	
	/*
	 * function to get data for counselor widget on apply home page
	 * 
	 */
	public function getCounselorWidgetData($counselorCount = 3)
	{
		$applyHomePageModel = $this->CI->load->model('applyHome/applyhomepagemodel');
		$topCounselors = $applyHomePageModel->getTopRatedCounselors($counselorCount);
		$counselorIds = array_map(function($a){return $a['counselorId'];},$topCounselors);
		
		$counselorRatingsInfo = $this->getRatingInfoByCounselorIds($counselorIds);
		
		foreach($topCounselors as $key=>$value) {
			$topCounselors[$key]['counselorRatings'] = $counselorRatingsInfo[$topCounselors[$key]['counselorId']];
		}
		return $topCounselors;
	}

	public function getTopReviewsByCounselorIds($counselorIds){
		$applyHomePageModel = $this->CI->load->model('applyHome/applyhomepagemodel');
		$reviews = $applyHomePageModel->getTopReviewsByCounselorIds($counselorIds);
		$reviewSet = array();
		//_p($reviews);
		foreach($reviews as $review)
		{	
			// keep 3 for each counselor
			if(count($reviewSet[$review['counselorId']])<3){
				$reviewSet[$review['counselorId']][] = $review;
			}
		}
		$finalReviewSet = array();
		for($i=0;$i<count($counselorIds);$i++)
		{
			$j = array_rand($reviewSet[$counselorIds[$i]]);
			unset($reviewSet[$counselorIds[$i]][$j]['counselorId']);
			$finalReviewSet[$counselorIds[$i]] = $reviewSet[$counselorIds[$i]][$j];
		}
		return $finalReviewSet;
	}

	public function getReviewsCountByCounselorIds($counselorIds){
		$applyHomePageModel = $this->CI->load->model('applyHome/applyhomepagemodel');
		$reviewCounts = $applyHomePageModel->getReviewsCountByCounselorIds($counselorIds);
		return $reviewCounts;
	}

	/*
	 * function to get counselor Info : name , photo, expertise
	 */
	public function getCounselorInfo($counselorIds = array())
	{
		if(count($counselorIds)==0)
		{
			return array();
		}
		$userModel = $this->CI->load->model('user/usermodel');
		$counselorBasicInfo = $userModel->getUsersBasicInfoById($counselorIds);
		// add expertise from config
		$studyAbroadCounselorConfig = $this->CI->load->config('studyAbroadCounselorInfoConfig');
		$counselorConfig = $this->CI->config->item('COUNSELOR_INFO');
		$counselorExpertise = array();
		$urlIds				= array();
		foreach($counselorConfig as $key=>$counselorRow)
		{
			$counselorExpertise[$counselorRow['counselorId']] = $counselorRow['counselorExpertise'];
			$urlIds[$counselorRow['counselorId']]			  = $key;	
		}
		$counselorInfo = array();
		foreach($counselorIds as $counselorId)
		{
			$counselorInfo[$counselorId] = $counselorBasicInfo[$counselorId];
			$counselorInfo[$counselorId]['counselorExpertise'] = $counselorExpertise[$counselorId];
			$counselorInfo[$counselorId]['counselorImageUrl']  = MEDIAHOSTURL.($counselorInfo[$counselorId]['avtarimageurl']==''?'/public/images/photoNotAvailable.gif':$counselorInfo[$counselorId]['avtarimageurl']);
			$counselorInfo[$counselorId]['counselorPageUrl']   = SHIKSHA_STUDYABROAD_HOME."/apply/counselors/".strtolower(trim($counselorInfo[$counselorId]['firstname']))."-".$urlIds[$counselorId];
			unset($counselorInfo[$counselorId]['avtarimageurl']);
			unset($counselorInfo[$counselorId]['displayname']);
			unset($counselorInfo[$counselorId]['password']);
		}
		return $counselorInfo;
	}

	public function setFileTypeStatus($type)
	{
		$result= array();
		$mimesType = array("image/gif",
						   "image/jpeg",
						   "image/jpg",
						   "image/png",
						   "image/bmp",
						   "image/tif",
						   "image/tiff",
						   "image/webp",
						   'image/pjpeg',
						   'image/x-png',
						   'application/pdf',
						   'application/octet-stream',
						   'application/x-download',
						   'application/octectstream',
						   'application/excel',
						   'application/vnd.ms-excel',
						   'application/x-gtar',
						   'application/x-gzip',
							'application/x-tar',
							'application/x-rar',
							'application/x-zip', 
							'application/zip',
							'application/rar',
							'application/tar',
							'application/gzip',    
							'application/x-zip-compressed',
							'application/x-7z-compressed',
							'application/7z-compressed', 
							'application/x-compress', 
							'application/x-compressed', 
							'multipart/x-zip',
							'text/plain',
							'application/msword',
							'application/excel',
							'application/xls',
							'application/vnd.ms-excel',
							'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
							'application/vnd.oasis.opendocument.text',
							'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
							'application/vnd.sun.xml.writer'
						   ); 
        if (in_array($type,$mimesType))
        {
			$result['OK']= true;
			$result['docType']= 'saApplyMedia';
			$result['errorFlag'] = false;
		}
		else {
			$result['Fail'] = "image,pdf,doc,docx,zip formats with max size 8 MB";
			$result['errorFlag'] = true;
		}
		//echo $type;
		//_p($result);
		return $result;			
	}



	public function examScoreCardUpload($fieldName,$docType='saApplyMedia'){

    	$appId = 1;
    	
		if(array_key_exists($fieldName, $_FILES) && !empty($_FILES[$fieldName]['tmp_name'][0]))
		{
			$return_response_array = array();
			$this->CI->load->library('upload_client');
			$uploadClient = new Upload_client();
			
			$errorFlag = false;
			$fileData = array();
			for($x=0;$x<count($_FILES [$fieldName] ['type']);$x++)
			{
				$type = $_FILES [$fieldName] ['type'] [$x];
				$type = trim($type,'"');
				$fileTypeStatus = $this->setFileTypeStatus($type);

				$fileData[$x]['name'] 		= $_FILES[$fieldName]['name'][$x];
				$fileData[$x]['type'] 		= $_FILES[$fieldName]['type'][$x];
				$fileData[$x]['tmp_name'] 	= $_FILES[$fieldName]['tmp_name'][$x];
				$fileData[$x]['error'] 		= $_FILES[$fieldName]['error'][$x];
				$fileData[$x]['size'] 		= $_FILES[$fieldName]['size'][$x];
				$fileData[$x]['OK'] 		= $fileTypeStatus['OK'];
				$fileData[$x]['docType'] 	= $fileTypeStatus['docType'];
				$fileData[$x]['errorFlag'] 	= $fileTypeStatus['errorFlag'];
				if($fileTypeStatus['Fail']!=''){
					$fileData[$x]['Fail'] = $fileTypeStatus['Fail'];
					$return_response_array[$x]['Fail'] = $fileTypeStatus['Fail'];
					$errorFlag = $fileTypeStatus['errorFlag'];
				}				
			}
			if($errorFlag==true)
			{
				$return_response_array['Fail'] = true;
				return $return_response_array;
			}
			else{
				$i = 0;
				foreach($fileData as $key=>$value){
					unset($_FILES);
					$_FILES[$fieldName]['tmp_name'][0] = $fileData[$i]['tmp_name'];
					$_FILES[$fieldName]['name'][0] = $fileData[$i]['name'];
					$_FILES[$fieldName]['type'][0] = $fileData[$i]['type'];
					$_FILES[$fieldName]['error'][0] = $fileData[$i]['error'];
					$_FILES[$fieldName]['size'][0]  =  $fileData[$i]['size'];
					$upload_array = $uploadClient->uploadFile($appId,$fileData[$i]['docType'],$_FILES,array(),"-1",$docType,$fieldName);
					
					if(is_array($upload_array) && $upload_array['status'] == 1)
					{
						$return_response_array[$i]['url'] = $upload_array[0]['imageurl'];
					} else {
						$return_response_array['Fail']= true;
						$return_response_array[$i]['Fail'] = "image,pdf,doc,docx,zip formats with max size 8 MB";
						}
					$i++;	
				}
			}
			return $return_response_array;
		} else {
			return "";
		}
	}

	public function saveExamScoreRecord($userId,$docPath,$examId,$profileEvaluationTrackingId,$courseId='',$counselorId=0){
		
		if($userId=='' || $docPath =='' || $examId==''){
			return '';
		}
		$applyHomePageModel = $this->CI->load->model('applyHome/applyhomepagemodel');
		$data = array();
		$data['userId'] 	 = $userId;
		$data['documentUrl'] = $docPath;
		$data['examId'] 	 = $examId;
		$data['courseId'] 	 = ($courseId > 0 ? $courseId : NULL);
		$data['addedOn'] 	 = date('Y-m-d H:i:s');
		$data['addedBy'] 	 = $userId;
		$data['status'] 	 = 'live';
		$data['trackingKeyId'] = $profileEvaluationTrackingId;
		$insertId = $applyHomePageModel->saveExamScoreRecord($data);
		// check if user is in 'Dropoff' stage, bring them back to 'Ready'
		$rmcPostingLib = $this->CI->load->library('shikshaApplyCRM/rmcPostingLib');
		$rmcPostingLib->moveStudentFromDropoffToReady($data['userId'],"Student uploaded exam score document(s).");
		
		//To exclude user from LDB lead
		/** As discussed with simran, 
		 * We are commenting code for those users who uploaded exam score
		 *  from Apply page, but while uploading exam score 
		 * if we've shown registration form 
		 * then the user will go through the LDB Exclusion logic.
		 * 
		 * This is commented because, in this flow user isn't changing 
		 * their profile that would lead to their entry in LDB/LDBexclusion.
		 * It was also found that these users would already be assigned to 
		 * counselors but since this flow doesn't work, they would appear 
		 * in the pending leads interface wherever they were visible.
		 */ 
		/*$this->CI->load->model('user/usermodel');
        $usermodel = new usermodel;
        $usermodel->addUserToLDBExclusionList($userId, 'shikshaApplyDoc');
		*/
        $extraDataForExclusion = array(
			'tracking_keyid' => $data['trackingKeyId'],
			'examScoreUpdated'=>true
        );
        $userLib = $this->CI->load->library('user/UserLib');
        $userLib->checkUserForLDBExclusion($userId,'gafpec','','','',$usermodel,'',$extraDataForExclusion);

		// send mail to counselor
		//$this->sendExamDocumentsToCounselor($data,$counselorId);
		return $insertId;
	}
	
	/*
	 * get data for sending exam score card/docs to counselor, prepare the mail & send
	 */
	public function sendExamDocumentsToCounselor($data,$counselorId)
	{
		$data = $this->getDataForExamDocumentMail($data);
		if($data === false)
		{
			// do err reporting
			return false;
		}
		$data['counselorId'] = $counselorId;
		$emailParams = $this->prepareExamDocumentMailContent($data);
		$this->_sendExamScoreDocumentMail($emailParams);
		//_p($emailParams);
	}
	/*
	 * function finally send the exam score document mail to its intended user
	 */
	private function _sendExamScoreDocumentMail($emailParams=array())
	{
		if(empty($emailParams))
		{
			return false;
		}
		// utility helper
		sendMails($emailParams);
	}
	/*
	 * function to prepare parameters for exam score docs mail, sent to counselors
	 */
	public function prepareExamDocumentMailContent($params = array())
	{
		if($params['countryId'] > 0){
			// from course-university data
			$countries = array($params['countryId']);
		}else{
			// from user registrationn data
			$countries = $params['countries']; 
			$params['univType'] = "university";
		}
		// to  (3,6=>usa & singapore | 8=>canada)
		if(count(array_intersect($countries,array(3,6)))>0 || (in_array(8,$countries) && $params['univType'] == 'university'))
		{
			$emailParams['recipients']['to'] = array("udit.narang@shiksha.com");
		}
		else //if(!in_array($params['countryId'],array(3,6)) || $params['univType'] == 'college')
		{
			$emailParams['recipients']['to'] = array("iqra.khan@shiksha.com");
		}
		// cc
		$emailParams['recipients']['cc'] = array("simrandeep.singh@shiksha.com", "rms.shiksha@gmail.com");
		// bcc
		//$emailParams['recipients']['bcc'] = array("satech@shiksha.com", "sumit.saklani@shiksha.com");

		if(ENVIRONMENT != "production")
                {
                        $emailParams['recipients']['to'] = array('simrandeep.singh@shiksha.com');
			unset($emailParams['recipients']['cc']);
                }
		// sender
		$emailParams['sender'] = SA_ADMIN_EMAIL;
		// subject
		$countries = ($params['countryName']==""?implode(',',$params['countryNames']):$params['countryName']);
		$emailParams['subject'] = "Docs received: ".$countries." | ".$params['exam']." | ".$params['course'];
		// body / mail content
		$emailParams['mailContent']  = "Name: ".$params['name'];
		$emailParams['mailContent'] .= "<br>Email: ".$params['email'];
		$emailParams['mailContent'] .= "<br>Mobile: ".$params['mobile'];
		if($params['city'] != 'N/A'){
			$emailParams['mailContent'] .= "<br>Current city: ".$params['city'];
		}else{
			$emailParams['mailContent'] .= "<br>Current country: ".$params['userCountry'];
		}
		if(!empty($params['yearOfStart'])){
			$emailParams['mailContent'] .= "<br>When do you plan to start: ".$params['yearOfStart'];
		}
		$emailParams['mailContent'] .= "<br>Course: ".$params['course'];
		if(!empty($countries)){
			$emailParams['mailContent'] .= "<br>Destination country: ".$countries;
		}
		$emailParams['mailContent'] .= "<br>Exam given: ".$params['examStatus']."<br>";
		if($params['examStatus'] == "Given")
		{	$exacmScoreStr = "";
			foreach($params['exams'] as $examScore)
			{
				 $examScoreStr .= $examScore['name'].":".$examScore['marks'].",";
			}
			$emailParams['mailContent'] .= rtrim($examScoreStr,',');
		}
		else if($params['examStatus'] == "booked")
		{
			$emailParams['mailContent'] .= "Booked exam date";
		}

		if($params['counselorId'] >0)
		{
			$userModel = $this->CI->load->model('user/usermodel');
			$user = $userModel->getUserById($params['counselorId']);
			if(!is_object($user))
			{
				return false;
			}
			// name, email, mobile
			$counselorName  = $user->getFirstName().' '.$user->getLastName();
			$counselorEmail = $user->getEmail();

			$emailParams['subject'] = "Profile ".$emailParams['subject'];	
			$emailParams['mailContent'] .= "<br>Counselor Name: ".$counselorName."<br>";
			array_push($emailParams['recipients']['cc'],$emailParams['recipients']['to'][0]);
			
			$emailParams['recipients']['to'] =  array($counselorEmail);
		}

		if(ENVIRONMENT != "production")
            {
            $emailParams['recipients']['to'] = array('jahangeer.alam@shiksha.com');
			$emailParams['recipients']['cc'] = array("satech@shiksha.com");
            }
		// attachment url
		$emailParams['fileUrl'] = $params['fileUrl'];
		return $emailParams;
	}
	/*
	 * function to get:
	 * - user's name,email,mobile,city,plan to start, destination country, course, exam given, bookde exam date,
	 * - uploaded doc's exam name
	 * - uploaded file url
	 * @params: array having userId, examid, documentUrl, course
	 */
	public function getDataForExamDocumentMail($params = array())
	{
		if(empty($params)){
			return false;
		}
		$this->abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
		$this->desiredCourses = array();
		foreach($this->abroadCommonLib->getAbroadMainLDBCourses() as $desCourse)
		{
			$this->desiredCourses[$desCourse['SpecializationId']] = $desCourse['CourseName'];
		}
		$this->examMasterList = $this->abroadCommonLib->getAbroadExamsMasterList();
		$mailData = array();
		// get course (rmc success page case)
		$this->_getCourseDataForCounselorMail($mailData, $params['courseId']);
		// get user details
		$this->_getUserDetailsForExamDocsMail($mailData, $params);
		if(empty($mailData))
		{
			return false;
		}
		// get exam name
		foreach($this->examMasterList as $exam)
		{
			if($exam['examId'] == $params['examId'])
			{
				$mailData['exam'] = $exam['exam'];
				break;
			}
		}
		// get fileUrl
		$mailData['fileUrl'] = MEDIAHOSTURL.$params['documentUrl'];
		return $mailData;
	}
	/*
	 * function to get a client course's name as :
	 * -desired course in specialization(subcat)
	 *  OR
	 * -level of category in specialization(subcat)
	 * @params : $maildata (ref), client course Id
	 */
	private function _getCourseDataForCounselorMail(& $mailData, $clientCourseId)
	{
		if($clientCourseId >0 ){
			$this->CI->load->builder('ListingBuilder','listing');
			$listingBuilder = new ListingBuilder;
			$abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
			$univRepository = $listingBuilder->getUniversityRepository();
			$courseObj = $abroadCourseRepository->find($clientCourseId);
			$university = $univRepository->find($courseObj->getUniversityId());
			$mailData['countryId'] = $university->getLocation()->getCountry()->getId();
			$mailData['countryName'] = $university->getLocation()->getCountry()->getName();
			$mailData['univType'] = $university->getTypeOfInstitute2();
			$this->CI->load->builder('CategoryBuilder','categoryList');
			$builderObj	= new CategoryBuilder;
			$repoObj = $builderObj->getCategoryRepository();
			if(in_array($courseObj->getDesiredCourseId(),array_keys($this->desiredCourses)))
			{
				$mailData['course']  = $this->desiredCourses[$courseObj->getDesiredCourseId()];
				$subcatObj = $repoObj->find($courseObj->getCourseSubCategory());
				$mailData['course'] .= " in ".$subcatObj->getName();
			}
			else{
				$mailData['course']  = $courseObj->getCourseLevel1Value();
				$subcat = $courseObj->getCourseSubCategory();
				$subcatObj = $repoObj->find($subcat);
				$categoryObj = $repoObj->find($subcatObj->getParentId());
				$mailData['course'] .= " of ".$categoryObj->getName()." in ".$subcatObj->getName();
			}
		}
	}
	/*
	 * get details from user :Name, Email, Mobile, Current city, when do you plan to start Course,	country of interest/destination country,exam,booked exam date.
	 * @param: array having userId
	 */
	private function _getUserDetailsForExamDocsMail(& $mailData, $params)
	{
		//$params['userId'] = 3284455;
		$userModel = $this->CI->load->model('user/usermodel');
		$user = $userModel->getUserById($params['userId']);
		if(!is_object($user))
		{
			return false;
		}
		// name, email, mobile
		$mailData['name']  = $user->getFirstName().' '.$user->getLastName();
		$mailData['email'] = $user->getEmail();
		$mailData['mobile'] = $user->getMobile();
		// current city & destination countries
		$this->_getUserCityCountriesForCounselorMail($mailData, $user);
		
		// booked exam date
		$additionalInfo = $user->getUserAdditionalInfo();
		if(is_object($additionalInfo)){
			$mailData['bookedExam'] = $additionalInfo->getBookedExamDate();
		}
			
		$pref = $user->getPreference();
		if(is_object($pref)){
			// time of start
			$timeOfStart = $pref->getTimeOfStart();
			$mailData['yearOfStart'] = ($timeOfStart == '0000-00-00 00:00:00'?NULL:date_format($timeOfStart,'Y'));
			// get course if not found earlier..
			if(empty($mailData['course'])){
				$this->_getCourseFromUserForCounselorMail($mailData, $pref);
			}
		}else{
			$mailData['desiredCourse'] = null;
		}
		// exam & score
		$this->_getUserExamScoresForCounselorMail($mailData, $params);
	}
	
	/*
	 * function to get course name from user 's course of interest for counselor email
	 * (logged in user case)
	 * @param: reference of maildata , use pref object
	 */
	private function _getCourseFromUserForCounselorMail(& $mailData, $pref)
	{
		$this->CI->load->builder('LDBCourseBuilder','LDB');
		$LDBCourseBuilder = new LDBCourseBuilder;
		$ldbRepository = $LDBCourseBuilder->getLDBCourseRepository();
		$this->CI->load->builder('CategoryBuilder','categoryList');
		$builderObj	= new CategoryBuilder;
		$repoObj = $builderObj->getCategoryRepository();
		if($pref->getDesiredCourse() > 0){
			$desiredCourse = $ldbRepository->find($pref->getDesiredCourse());
			///_p($desiredCourse);
			$mailData['desiredCourse'] = $desiredCourse->getCourseName();
			$category = $repoObj->find($desiredCourse->getCategoryId());
			$mailData['category'] = $category->getName();
			$mailData['desiredCourseId'] = $desiredCourse->getId();
			$mailData['level'] = $desiredCourse->getCourseLevel1();
			$mailData['level'] = $mailData['level'] == "UG"?"Bachelors":($mailData['level']=="PG"?"Masters":$mailData['level']);
		}else{
			$mailData['desiredCourse'] = null;
		}
		// subcat(called specialization here)
		$specializationId = $pref->getAbroadSpecialization();
		if($specializationId>0){
			$specialization = $repoObj->find($specializationId);	
			$mailData['subcat'] = $specialization->getName();
		}
		if(in_array($mailData['desiredCourseId'], array_keys($this->desiredCourses))){
			$mailData['course'] = $mailData['desiredCourse'].($mailData['subcat']!=''?" in ".$mailData['subcat']:"");
		}else{
			if(!empty($mailData['level']))
			{
				$mailData['course'] = $mailData['level']." of ".$mailData['category'].($mailData['subcat']!=''?" in ".$mailData['subcat']:"");
			}
		}
	}
	
	/*
	 * get user destination countries & current city
	 * @param: reference of maildata & user object
	 */
	private function _getUserCityCountriesForCounselorMail(& $mailData, $user)
	{
		$this->CI->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder;
		$locationRepository = $locationBuilder->getLocationRepository();
		if($user->getISDCode() == '91'){
			$userCity = $user->getCity();
			if(is_numeric($userCity)){
				$city               = $locationRepository->findCity($userCity);
				$mailData['city']   = $city->getName();
			}else{
				$mailData['city']   = 'Incorrect value stored. Please report.';
			}
		}
		else{
			$mailData['city']   = 'N/A';
			$userCountry = $user->getCountry();
			if($userCountry > 0){
				$mailData['userCountry'] = $locationRepository->findCountry($userCountry);
				$mailData['userCountry'] = $mailData['userCountry']->getName();
			}
		}
		// destination countries
		$locations = $user->getLocationPreferences();
		$mailData['countries'] = $mailData['countryNames'] = array();
		foreach($locations as $location) {
			$countryId = $location->getCountryId();
			if($countryId > 2)
			{
				$country = $locationRepository->findCountry($countryId);
				array_push($mailData['countries'], $countryId);
				array_push($mailData['countryNames'], $country->getName());
			}
		}
	}
	
	/*
	 * get exams given by user & their scores
	 */
	private function _getUserExamScoresForCounselorMail(& $mailData, $params)
	{
		$userLib = $this->CI->load->library('user/UserLib');
		$userExamInfo = $userLib->getUserExamInfo(array($params['userId']));
		$userExamArr = array();
		$examMasterList = array_map(function($a){return $a['exam'];},$this->examMasterList);
		foreach($userExamInfo[$params['userId']] as $examName=>$userExam)
		{
			if(!in_array(strtoupper($examName), $examMasterList)){ // coz CAE,MELAB,CAEL are not in the system anymore
				continue;
			}
			$userExamArr[$examName] = array();
			$userExamArr[$examName]['name'] = $examName;
			$userExamArr[$examName]['level'] = "Competitive exam";
			if($examName=='IELTS'){
				$userExamArr[$examName]['marks'] = $userExam['Marks'];
			}
			else{
				$userExamArr[$examName]['marks'] = intval($userExam['Marks']);
			}
			$userExamArr[$examName]['marksType'] = $userExam['Type'];
		}
		//_p($userExamArr);
		$mailData['exams'] = $userExamArr;
		
		if(count($mailData['exams'])>0){
		   $mailData['examStatus'] = 'Given';     
		}else if($mailData['bookedExam']==1){
		   $mailData['examStatus'] = 'Booked'; 
		}else{
		   $mailData['examStatus'] = 'Not Given'; 
		}
	}
    
    public function getCounsellorImageUrlById($counsellorId){
        if(empty($counsellorId))return;
        $userModel = $this->CI->load->model('user/usermodel');
		$counselorBasicInfo = $userModel->getUsersBasicInfoById(array($counsellorId));
        return MEDIAHOSTURL.($counselorBasicInfo[$counsellorId]['avtarimageurl']==''?'/public/images/photoNotAvailable.gif':$counselorBasicInfo[$counsellorId]['avtarimageurl']);
    }

    public function getCounsellorCMSImageUrlById($counsellorId){
        if(empty($counsellorId))return;
        $counselorBasicInfo = $this->applyHomePageModel->getCounselorDetails(array($counsellorId));
        return $counselorBasicInfo[$counsellorId]['counselorImageUrl']==''?'/public/images/photoNotAvailable.gif':$counselorBasicInfo[$counsellorId]['counselorImageUrl'];
    }


    /*
	 * get counselor info for counselor page
	 * 
	 */
	public function getCounselorInfoForCounselorPage($counselorConfigIds = array())
	{
		if(is_numeric($counselorConfigIds))
		{
			$counselorConfigIds = array($counselorConfigIds);
		}
		$this->CI->load->config('studyAbroadCounselorInfoConfig');
		$counselorConfig = $this->CI->config->item('COUNSELOR_INFO');
		foreach($counselorConfigIds as $counselorConfigId)
		{
			$counselorInfo = $this->getCounselorInfo(array($counselorConfig[$counselorConfigId]['counselorId']));
			$counselorInfo[$counselorConfig[$counselorConfigId]['counselorId']]['counselorBio'] = $counselorConfig[$counselorConfigId]['counselorBio'];
		}
		return $counselorInfo;
	}

	public function getRatingInfoByCounselorIds($counselorIds){
		if(!is_array($counselorIds)){
			return false;
		}
		$ratingInfo = $this->applyHomePageModel->getRatingInfoByCounselorIds($counselorIds);
		$result = array();
		foreach ($ratingInfo as $key => $value) {
			$result[$value['counselorId']] = $value;
		}
		return $result;
	}


	public function getReviewByCounselorId($counselorId,$limit=20,$totalCountFlag=false,$lastReviewId=0){
		if($counselorId ==''){
			return false;
		}
		$reviewInfo = $this->applyHomePageModel->getReviewByCounselorId($counselorId,$limit,$totalCountFlag,$lastReviewId);
		return $reviewInfo;
	}
    public function deleteReview($validateUser,$reviewId){
        if($validateUser=='false'){
            return 'error';
        }
        $reviewInfo['userId'] = $this->getReviewPostedBy($reviewId);
        if($this->checkUserEligibilityForReviewDeletion($validateUser,$reviewInfo)){
            
            if($this->applyHomePageModel->deleteReview($reviewId,$validateUser[0]['userid'])){
                return "success";
            }else{
                return "error";
            }
        }
    }

    public function getReviewPostedBy($reviewId){
        if(empty($reviewId)){
            return;
        }else{
            return $this->applyHomePageModel->getReviewPostedBy($reviewId);
        }
    }
    public function checkUserEligibilityForReviewDeletion($validateUser,$review){
        if($validateUser=='false'){
            return false;
        }
        if($review['userId'] ===$validateUser[0]['userid']){
            return true;
        }else if($validateUser[0]['usergroup']==='saAdmin'){
            return true;
        }else return false;
    }

    private function _prependDomainToUserImageUrl($imageUrl){
        if(empty($imageUrl)){
            return;
        }
        if(strpos($imageUrl,'public') && strpos($imageUrl,'gif')){//for avatar img
        	return IMGURL_SECURE.$imageUrl;
        }
        if(strpos($imageUrl,'public')){
            $imageUrl = str_replace('/public/images/', '/public/images/studyAbroadCounsellorPage/', $imageUrl);
            $imageUrl = str_replace('.gif', '.jpg', $imageUrl);
            return IMGURL_SECURE.$imageUrl;
        }else return MEDIAHOSTURL.$imageUrl;
    }
    public function getStudentsInfo($reviews){
        if(!empty($reviews)){
		$this->userProfilePageLib = $this->CI->load->library('userProfilePage/userProfilePageLib');
            $userIds = array();
            foreach($reviews as $review){
                $userIds[] = $review['userId'];
            }
            $userIds = array_unique($userIds);
            $userModel = $this->CI->load->model('user/usermodel');
            $userBasicInfo = $userModel->getUsersBasicInfoById($userIds);
            $userBasicInfo[0] = array('avtarimageurl'=>'/public/images/profileDefaultNew1.jpg');
            $userBasicInfo['anonymous'] = array('avtarimageurl'=>'/public/images/profileAnonymousNew.jpg');
            foreach($userBasicInfo as &$userInfo){
				$urlData = $this->userProfilePageLib->getUserProfilePageURL(urlencode($userInfo['displayname']),'viewPage',false, true);
				$userInfo['url'] = $urlData['url'];
                $userInfo['avtarimageurl'] = $this->_prependDomainToUserImageUrl($userInfo['avtarimageurl']);
            }
            $userExamsInfo = $userModel->getUserSAExams($userIds);
            
            $usersExams = $this->_getMostImportantExamDataForUser($userExamsInfo);
            $userAdmittedCourses = $this->applyHomePageModel->getUserAdmittedCourses($userIds);
            $courses = array_values($userAdmittedCourses);
            if(!empty($courses)){
                $this->CI->load->builder('ListingBuilder','listing');
                $listingBuilder = new ListingBuilder;
                $abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
                $courseObjs = $abroadCourseRepository->findMultiple($courses);
                $courseUniversityNames = array();
                foreach($courseObjs as $courseObj){
                    $courseUniversityNames[$courseObj->getId()] = $courseObj->getUniversityName();
                }
            }
			$userAdmittedUniversities = array();
            foreach($userIds as $userId){
                if(!empty($courseUniversityNames[$userAdmittedCourses[$userId]])){
                    $userAdmittedUniversities[$userId]  = $courseUniversityNames[$userAdmittedCourses[$userId]];
                }
            }
            $returnArray['userExams'] = $usersExams;
            $returnArray['userAdmittedUniversities'] = $userAdmittedUniversities;
            $returnArray['userBasicInfo'] = $userBasicInfo;
            return $returnArray;
        }
    }
    private function _getMostImportantExamDataForUser(&$userExamsInfo){
        $usersExams = array();
        $this->CI->load->config('applyHome/counselorReviewConfig');
        global $examsPriorityOrder;
        foreach($userExamsInfo as $examInfo){
            if(!empty($usersExams[$examInfo['UserId']]['examName'])){
                if(array_search($usersExams[$examInfo['UserId']]['examName'],$examsPriorityOrder)>array_search($examInfo['Name'],$examsPriorityOrder)){
                    $usersExams[$examInfo['UserId']]['examName'] = $examInfo['Name'];
                    $usersExams[$examInfo['UserId']]['marks'] = $examInfo['Marks'];
                }
            }else{
                $usersExams[$examInfo['UserId']]['examName'] = $examInfo['Name'];
                $usersExams[$examInfo['UserId']]['marks'] = $examInfo['Marks'];
            }
        }
        return  $usersExams;
    }
    public function getSignupRedirectParams(&$data)
	{
		$temp = $this->CI->security->xss_clean($_COOKIE['gfpecTrackingId']);
		if($temp != ''){
			unset($_COOKIE['gfpecTrackingId']);
			setcookie('gfpecTrackingId', '', (time()-3600), '/', COOKIEDOMAIN);
			$temp = json_decode(base64_decode($temp),true);
			//$data['uploadData']['uploadedFileUrl'] = $temp['url'];
			//$data['regnData']['examId'] = $temp['examId'];
			$data['regnData']['counselorId'] = $temp['counselorId'];
			$data['regnData']['trackingId'] = $temp['trackingId'];
			$data['regnData']['userId'] = $data['validateuser'][0]['userid'];
		}
	}

	public function getReviewPOSTData(&$postData){
   		$postData['userCounselorId'] = $this->CI->input->post('userCounselorId', true);
   		$postData['trackingKeyId'] = $this->CI->input->post('trackingKeyId', true);
   		$postData['userRating1'] = $this->CI->input->post('userRating1', true);
   		$postData['userRating2'] = $this->CI->input->post('userRating2', true);
   		$postData['userRating3'] = $this->CI->input->post('userRating3', true);
   		$postData['userRating4'] = $this->CI->input->post('userRating4', true);
   		$postData['userRating5'] = $this->CI->input->post('userRating5', true);
   		$postData['counselorReviewText'] = $this->CI->input->post('counselorReviewText', true);
   		$postData['counselingServiceReviewText'] = $this->CI->input->post('counselingServiceReviewText', true);
   		$postData['anonymousFlag'] = $this->CI->input->post('anonymousFlag', true);
   }

   public function validatePOSTData(&$postData){
   		if(empty($postData['userCounselorId'])
   			|| empty($postData['trackingKeyId'])
   			|| empty($postData['counselorReviewText'])
   			|| empty($postData['counselingServiceReviewText'])
   			|| ($postData['userRating1'] < 1 && $postData['userRating1'] > 10)
   			|| ($postData['userRating2'] < 1 && $postData['userRating2'] > 10)
   			|| ($postData['userRating3'] < 1 && $postData['userRating3'] > 10)
   			|| ($postData['userRating4'] < 1 && $postData['userRating4'] > 10)
   			|| ($postData['userRating5'] < 1 && $postData['userRating5'] > 10)
   		){
   			return false;
   		}
   		return true;
   }

   public function saveReviewPOSTData($postData, $userDetails){
   		$applyHomePageModel = $this->CI->load->model('applyHome/applyhomepagemodel');
   		$finalArr = $this->_formatPOSTData($postData);
   		$finalArr['device']         = (isMobileRequest()===TRUE ? 'mobile' : 'desktop');
   		$finalArr['userId']         = $userDetails[0]['userid'];
   		$finalArr['StudentName']    = ucwords($userDetails[0]['firstname'].' '.$userDetails[0]['lastname']);
   		$finalArr['addedAt']        = date('Y-m-d H:i:s');
   		$finalArr['reviewCategory'] = 'new';
   		return $applyHomePageModel->saveCounselorReview($finalArr);
   }

   private function _formatPOSTData(&$postData){
   		$finalArr = array();
   		$finalArr['counselorId']          = $postData['userCounselorId'];
   		$finalArr['MISTrackingId']          = $postData['trackingKeyId'];
   		
   		$finalArr['responseRating']  	  = $postData['userRating1'];
   		$finalArr['knowledgeRating']      = $postData['userRating2'];
   		$finalArr['overallRating']        = $postData['userRating3'];
   		$finalArr['studyAbroadExpRating'] = $postData['userRating4'];
   		$finalArr['recommendationRating'] = $postData['userRating5'];
   		
   		$finalArr['reviewText']           = $postData['counselorReviewText'];
   		$finalArr['serviceReviewText']    = $postData['counselingServiceReviewText'];

   		if($postData['anonymousFlag'] == 'yes'){
   			$finalArr['anonymousFlag'] = 1;
   		}
   		return $finalArr;
   }

   public function getUserRelatedCounsellors($userId){
   		if(empty($userId)){
   			return array();
   		}
   		return $this->applyHomePageModel->getUserRelatedCounsellors($userId);
   }

   public function getCounselorDetails($counselorIds){
   		if(count($counselorIds)==0){
   			return array();
   		}
   		return $this->applyHomePageModel->getCounselorDetails($counselorIds);
   }

   public function userStageCheck($userId){
   		if(empty($userId)){
			return false;
		}
   		$result = $this->applyHomePageModel->userStageCheck($userId);
   		if($result){
   			return $result;
   		}
   		return false;
   }

   /*
    * checks if :
    * 1. user is logged in
    * 2. user stage >=3 & not dropped-off
    * @param : validateUser, i.e. result from checkUserValidation()
    */
   public function checkIfUserEligibleToWriteReview($validateUser, $counselorId, $trackingKey='')
   {
		if($validateUser === 'false' || (empty($counselorId) && empty($trackingKey)))
		{
			return false;
		}else{
            $returnArray = array('validStage'=>false,'validCounselor'=>false,'isSCRP'=>false);
            if (!empty($trackingKey))
            {
                $abroadSignupLib = $this->CI->load->library('studyAbroadCommon/AbroadSignupLib');
                $trackingDetails = $abroadSignupLib->getMISTrackingDetails($trackingKey);
                if(!empty($trackingDetails) && $trackingDetails['page']=== 'shikshaCounselingReviewPage')
                {
                    $returnArray['isSCRP'] = true;
                    return $returnArray;
                }
            }
            $result1 = $this->applyHomePageModel->userStageCheck($validateUser[0]['userid']);
            $returnArray['validStage'] = $result1>0?true:false;
            if(!empty($counselorId))
			{
                $result2 = $this->applyHomePageModel->getUserRelatedCounsellors($validateUser[0]['userid'], $counselorId);
                $returnArray['validCounselor'] = count($result2)>0?true:false;
			}
			return $returnArray;
			//return ($result1>0 && count($result2)>0?true:false);
		}
   }

   public function getStageName($stageId){
   		$result = $this->applyHomePageModel->getStageName($stageId);
   		return $result;
   }

   public function getUserOverallRating($row){
   		if($row){
	   		$result = $this->applyHomePageModel->getUserOverallRating($row);
	   		return $result;
	   	}
	   	return;
   }
    public function setBSBParam($userId = 0, $pageName = 'otherPage', $source){
		$bsb = $this->CI->input->cookie('bsb', true);
	  	if($bsb != $userId){
		  	$this->BSBLib = $this->CI->load->library('commonModule/BSBLib');
		  	$userBSBData = $this->BSBLib->getUserBSBData(getVisitorId(), $userId);
		  	$displayData = array();
			$displayData['crossBtnFlag'] = false;
			$displayData['showBSBFlag']  = false;
			$displayData['userId']       = $userId;
		  	$this->BSBLib->getShowFlagsForBSB($displayData, $userBSBData);
		  	if($displayData['showBSBFlag'] == true){
			  	$insertData = array();
				$insertData['userId']            = $userId;
				$insertData['visitorId']         = getVisitorId();
				$insertData['BSBType']           = 'applyPagePromotion';
				$insertData['BSBAction']         = 'clicked';
				$insertData['applicationSource'] = $source;
				$insertData['pageName']          = $pageName;
				$insertData['addedAt']           = date('Y-m-d H:i:s');
				$insertData['clickedAt']         = date('Y-m-d H:i:s');
				$insertData['BSBState']          = $displayData['crossBtnFlag']?'crossEnabled':'crossDisabled';
			  	$this->bsbmodel = $this->CI->load->model('commonModule/bsbmodel');
			  	$this->CI->bsbmodel->addBSBTrackingData($insertData);
		    }
	  	}
	}

	public function getAdmissionApplicationStats(){
		$resultArr = array();
		$submittedApplications = $this->applyHomePageModel->getAllSubmittedApplications();
		$resultArr['noOfApplications'] = count($submittedApplications);
		$resultArr['latestApplicationStr'] = $this->getLatestAdmissionApplicationStr($submittedApplications[0]);
		$courseIdArr = array_map(function($a){return $a['courseId'];},$submittedApplications);
		unset($submittedApplications);
		$courseIdArr = array_filter(array_unique($courseIdArr));
		
		$resultArr['noOfUniversities'] = $resultArr['noOfCountries'] = 0;
		if(count($courseIdArr) > 0){
			$univCountryData = $this->applyHomePageModel->getUnivAndCountryDataFromCourseId($courseIdArr);
			$resultArr['noOfUniversities'] = count($univCountryData['universityIds']);
			$resultArr['noOfCountries']    = count($univCountryData['countryIds']);
		}
		return $resultArr;
	}

	public function getLatestAdmissionApplicationStr($latestApplicationDetails){
		$courseUnivStr = '';
		if(isset($latestApplicationDetails['courseId']) && $latestApplicationDetails['courseId'] > 0){
			$this->CI->load->builder('ListingBuilder','listing');
			$listingBuilder = new ListingBuilder;
			$abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
			$course = $abroadCourseRepository->find($latestApplicationDetails['courseId']);
			$courseUnivStr = $course->getName().' in '.$course->getUniversityName().' '.time_elapsed_string($latestApplicationDetails['modifiedOn']);
		}
		return $courseUnivStr;
	}
	/*
	 * function that chooses whether or not to use ABtest on applyhome (uses a const as a flag to turn ABTest on or off)
	 * and calls ABTesting controller for its execution & creation of a cookie for this page to remember the variation
	 */
	public function pickABTestingVariation(&$displayData)
	{
		if(USE_ABTEST_APPLYHOME === 1){
			$displayData['useNewApplyHome'] = Modules::run('common/ABTesting/executeABTesting',APPLYHOME_EXPOSURE_PERCENTAGE,APPLYHOME_ABTESTNAME);
		}
		if($displayData['useNewApplyHome']==1)
		{
			$displayData['headerText'] = "Overseas Admission Counseling";
			$displayData['headerText2']= "from the most trusted education website in India";
		}else{
			$displayData['headerText'] = "Overseas Admission Counseling";
			$displayData['headerText2']= "from the comfort of your home";
		}
	}


	/**
	 * Function to get Study abroad counselling service rating data from cache
	 */

	public function getStudyAbroadCounsellingRatingData(){
		$this->CI->load->library('applyHome/cache/ApplyHomeCache');
		$cache = $this->CI->applyhomecache;
		$result = $cache->getSACounsellingRatingData();
		if(!isset($result) || empty($result)){
		    $result = $this->applyHomePageModel->getStudyAbroadCounsellingRatingData();
        }
		$ratingData = array();
		$ratingData['ratingCount'] = $result['ratingCount'];
		$ratingData['overallRating'] = $result['overallRating'];
		unset($result);
		return $ratingData;
	}

	/**
	 * function to select top reviews(via config) & get their data
	 */
	public function getTopCounsellingReviews()
	{
		$this->CI->load->config('applyHome/applyHomeConfig');
		$topReviewIds = $this->CI->config->item('topReviews');
//		$topReviewIds = array(200,201,202,208); // testing purpose
		$topReviews = $this->applyHomePageModel->getCounsellingReviewsByIds($topReviewIds);
		$userIds = array_unique(array_map(function($a){return $a['userId']; },$topReviews));
		// now get admitted data for these users
		$userCourseIds = $this->applyHomePageModel->getUserEnrolledCourses($userIds);
		$courseObjs = array();
		if(count($userCourseIds)>0){
			$this->CI->load->builder('ListingBuilder','listing');
			$listingBuilder = new ListingBuilder;
			$abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
			$courseObjs = $abroadCourseRepository->findMultiple(array_values($userCourseIds));
		}
		foreach($topReviews as &$review)
		{
			$courseObj = $courseObjs[$userCourseIds[$review['userId']]]; 
			if(!is_null($courseObj))
			{
				$review['admittedTo'] = $courseObj->getUniversityName();
			}
		}
		return $topReviews;
	}

	/**
     *function to get url of study abroad counselling review page
     */
	public function getSACounsellingReviewPageLink(){
        return SHIKSHA_STUDYABROAD_HOME.'/apply'.'/reviews';
    }

    /**
     * function to get star filling width percentage for apply Home page
     */
    public function getStarRatingWidth($starSpacing,$totalWidth,$rating){
        $singleSpacePercentage = ($starSpacing/$totalWidth)*100;
        $integerPart = floor($rating);
        $fractionPart = $rating-$integerPart;
        $totalSpacingPercentage = 0;
        if($fractionPart > 0 && $integerPart > 0) {
            $totalSpacingPercentage = $singleSpacePercentage * ($integerPart);
        }
        $starPercentage = ($rating/5)*100;
        $filledStarLength = ($starPercentage/100)*($totalWidth-($starSpacing*4));
        $filledStarPercentage = $filledStarLength/$totalWidth*100;
        $finalPercentage = $filledStarPercentage+$totalSpacingPercentage;
        $width = strval($finalPercentage)."%";
        return $width;
    }
}
?>