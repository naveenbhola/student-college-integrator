<?php
class OnlineFormUtilityLib
{
	
	function __construct() {
		$this->CI = & get_instance();
	}

	private function init() {
		$this->CI->load->library('dashboardconfig');
	}

	public function getUrl($instituteIds=array()) {
		$this->init();
		$OFData = DashboardConfig::$institutes_autorization_details_array;
		$PBTSeoData = Modules::run('onlineFormEnterprise/PBTFormsAutomation/getExternalFormConfigDetails', array($instituteIds));
		$OFData += $PBTSeoData;
		foreach($instituteIds as $key=>$value){
			$online_form_institute_seo_url[$value] = $OFData[$value]['seo_url'];
		}
		return $online_form_institute_seo_url;
	}

	/**
	* Purpose  : Method to get the Percentage  completed by the user of the given Online Form
	* Params   : 1. Course-IDs 	 - Array
	* 	     2. UserId - Integer
	* Author   : Bharat Issar
	*/
	function getOnlineFormStatus($courseIds , $userId) {
		$this->init();
		$this->CI->load->model("onlineparentmodel");
		$onlinemodelObj = $this->CI->load->model("onlinemodel");
		$result = $onlinemodelObj->getOnlineFormStatus($courseIds , $userId);
		$final 	= array();
		
		foreach ($result as $value) {
			$percent = $value['NoofPages'] * 20;
			$courseId = $value['courseId'];
			$final[$courseId] = $percent;
		}
		
		return $final;
	}

	/**
	* Purpose	: API to get basic information of online form (url, last date, etc), if form exist for the course ID
	* Inpiut 	: Valid Course ID
	* OutPut 	: Basic info array
	* Author 	: Virender Singh
	*/
	public function getOAFBasicInfo($course_ids = 0) {
		if(!is_array($course_ids) && $course_ids != '' && $course_ids > 0){
			$course_ids = array($course_ids);
		}else if(empty($course_ids)){
			return array();
		}
		$this->CI->load->library('dashboardconfig');
		$this->CI->load->helper('url');
		$response = array();

		$displayOnlineButton = $this->checkIfCoursesHaveOnlineForm($course_ids);
		if(is_array($displayOnlineButton) && count($displayOnlineButton) > 0) {
			//Get OAF config data from config and DB and merge them
			$online_form_institute_seo_url = DashboardConfig::$institutes_autorization_details_array;
			$PBTSeoData = Modules::run('onlineFormEnterprise/PBTFormsAutomation/getExternalFormConfigDetails');
			$online_form_institute_seo_url += $PBTSeoData;

			//Load course repository
			$this->CI->load->builder("nationalCourse/CourseBuilder");
			$builder = new CourseBuilder();
			$this->course_repository = $builder->getCourseRepository();

			foreach ($displayOnlineButton as $courseId => $formData) {
				if(isset($formData['mainCourseId'])){ //other course
					$course_object = $this->course_repository->find($courseId);
					$seoURL = str_replace('<courseName>', strtolower(seo_url($course_object->getName(),'-',30)), $online_form_institute_seo_url[$formData['mainCourseId']]['seo_url']);

	            	$seoURL = str_replace('<courseId>', $courseId, $seoURL);
	            	$urlArray = explode('-',$seoURL);
					$urlArray[count($urlArray)-1] = $formData['mainCourseId'];
	                $seoURL = implode('-', $urlArray);
					$seo_url = ($seoURL!='')?SHIKSHA_HOME.$seoURL:SHIKSHA_HOME."/Online/OnlineForms/showOnlineForms/".$formData['mainCourseId'];
				}else{ //main course
					$course_object = $this->course_repository->find($courseId);
					$seoURL = str_replace('<courseName>', strtolower(seo_url($course_object->getName(),'-',30)), $online_form_institute_seo_url[$courseId]['seo_url']);
	            	$seoURL = str_replace('<courseId>', $courseId, $seoURL);
					$seo_url = ($seoURL!='')?SHIKSHA_HOME.$seoURL:SHIKSHA_HOME."/Online/OnlineForms/showOnlineForms/".$courseId;
				}
				$externalURL = !empty($formData['externalURL']) ? $formData['externalURL'] : '';
				$isExternal = !empty($formData['externalURL']) ? 1 : 0;
				$last_date = !empty($formData['last_date']) ? $formData['last_date'] : '';
				$creationDate = !empty($formData['creationDate']) ? $formData['creationDate'] : '';
				$response[$courseId] = array(
										'of_external_url' => $seo_url,
										'of_seo_url'      => $seo_url,
										'of_last_date'    => $last_date,
										'of_creationDate' => $creationDate,
										'isExternal'      => $isExternal
									);
			}
		}
		return $response;
	}

	private function checkIfCoursesHaveOnlineForm($courseIdArr){
		$this->CI->load->model("Online/onlineparentmodel");
		$onlinemodelObj = $this->CI->load->model('Online/onlinemodel');
		//get all active online forms
		$allForms = $onlinemodelObj->getAllAvailableOnlineForms();
		$finalArr = array();
		$allCourseIds = array();
		
		//format form data - main and other courses
		foreach ($allForms as $key => $value) {
			$finalArr[$value['courseId']] = array('externalURL'=>$value['externalURL'], 'last_date'=>$value['last_date'],'creationDate'=>$value['creationDate']);
			if(!empty($value['otherCourses'])){
				$otherCourses = explode(',', $value['otherCourses']);
				foreach ($otherCourses as $otherCourse) {
					$finalArr[$otherCourse] = array('externalURL'=>$value['externalURL'], 'last_date'=>$value['last_date'], 'mainCourseId'=>$value['courseId'],'creationDate'=>$value['creationDate']);
				}
			}
		}
		$allCourseIds = array_keys($finalArr);
		
		//filtering user provided course list
		$courseWithForms = array_intersect($courseIdArr, $allCourseIds);
		$finalOutput = array();
		foreach ($courseWithForms as $course) {
			$finalOutput[$course] = $finalArr[$course];
		}
		return $finalOutput;
	}
	
	/***
	 * functionName : getOnlineFormAllCourses
	 * functionType : return an array
	 * desciption   : prepare online form dashboard config with otherCouse's as a virtual array
	 * @author      : akhter
	 * @team        : UGC
	***/
	/*
         *  This function is used in national rankingpages. Please co-ordinate if changes are made.
         */
	function getOnlineFormAllCourses()
	{
		$this->CI->load->model("Online/onlineparentmodel");
		$onlinemodelObj = $this->CI->load->model("Online/onlinemodel");
		$this->CI->load->library('dashboardconfig');
		$online_form_institute_seo_url = DashboardConfig::$institutes_autorization_details_array;
		$PBTSeoData = Modules::run('onlineFormEnterprise/PBTFormsAutomation/getExternalFormConfigDetails');
		$online_form_institute_seo_url += $PBTSeoData;
		$allOtherCourse = $onlinemodelObj->getOnlineFormAllCourses();    
		
		if(count($allOtherCourse)>0){
			foreach($allOtherCourse as $c)
			{
				if($c['otherCourses'] !='')
				{
					$other = explode(',',$c['otherCourses']);
					if(count($other)>0)
					{
						foreach($other as $other)
						{
							$mainCourseId = $c['courseId'];
							$online_form_institute_seo_url[$other] = $online_form_institute_seo_url[$mainCourseId];
						}
					}
				}
			}
		}
		return $online_form_institute_seo_url;
	}

	/**
         * API to get LDB Course for client course
         * 
         * @param array $courseIds
         * @return array desiredCourse AND categoryId
         */
        public function getDominantDesiredCourseForClientCourses($courseIds = array()){
	    $this->CI->load->model('LDB/ldbcoursemodel');
            $details = array();
            if(!empty($courseIds)){
                foreach ($courseIds as $courseId) {
                    $ldbCourses = $this->LDBCourseRepository->getLDBCoursesForClientCourse($courseId);
                    foreach($ldbCourses as $ldbCourse) {
                        $ldbCourseDetails = $this->LDBCourseRepository->find($ldbCourse->getId());
			
			if($ldbCourseDetails->getCategoryId() == 14) {
				$details[$courseId]['categoryId'] = 14;
				$testPrepMapping = $this->CI->ldbcoursemodel->getTestPrepCourseMapping($ldbCourseDetails->getId());
				$details[$courseId]['desiredCourse'] = $testPrepMapping[0]['blogId'];
			}
			else {
				$specializationName = $ldbCourseDetails->getSpecialization();
				if(strtolower($specializationName) == 'all'){
				    $details[$courseId]['desiredCourse'] = $ldbCourseDetails->getId();
				}else{
				    $details[$courseId]['desiredCourse'] = $ldbCourseDetails->getParentId();
				}
				$details[$courseId]['categoryId'] = $ldbCourseDetails->getCategoryId();
			}
                        break;
                    }
                }
                return $details;
            }else{
                return NULL;
            }
        }

    function redirectionRule($courseId,$trackingPageKeyId){
    	// find institute id in course obj
    	$this->CI->load->config('onlineFormEnterprise/onlineFormConfig');
    	$this->CI->load->builder("nationalCourse/CourseBuilder");
		$builder = new CourseBuilder();
		$courseRepository = $builder->getCourseRepository();
		$courseObj = $courseRepository->find($courseId);
		$instituteId = $courseObj->getInstituteId();
		$primHier = $courseObj->getPrimaryHierarchy();
		$streamToDepttInUrl = $this->CI->config->item('streamToDepttInUrl');
		$urlDeptt = $streamToDepttInUrl[$primHier['stream_id']];

    	$this->CI->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $instituteRepo = $instituteBuilder->getInstituteRepository();
        $instituteObj = $instituteRepo->find($instituteId);

        $listingName = seo_url($instituteObj->getName(),"-","200",true);
        if(stripos($instituteObj->getName(), $instituteObj->getMainLocation()->getCityName()) === FALSE && $instituteObj->getMainLocation()->getCityName() != ''){
            $cityAppend = '-'.seo_url($instituteObj->getMainLocation()->getCityName(),"-","150",true);
        }

        $localityName = $instituteObj->getMainLocation()->getLocalityName();
        if(isset($localityName) && $localityName != ''){
            $localityAppend = '-'.seo_url($localityName,"-","150",true);
        }
        $seoUrl = '/college/'.$listingName.$localityAppend.$cityAppend.'/'.$urlDeptt.'-application-form-'.$courseId;
        if($trackingPageKeyId !=''){
        	$seoUrl .= '?tracking_keyid='.$trackingPageKeyId;	
        }
        if($_SERVER['REQUEST_URI'] !=$seoUrl){
        	$seoUrl = SHIKSHA_HOME.$seoUrl;
        	redirect($seoUrl, 'location', 301);
        }
        return true;
    }

    function createSeoUrl($courseId,$trackingPageKeyId ,$deprtment =''){
    	if($courseId ==''){
    		return false;
    	}

    	if(intval($courseId) <=0){
    		return false;
    	}
    	$allFormData = $this->checkIfCoursesHaveOnlineForm(array($courseId));
		if(isset($allFormData[$courseId]['mainCourseId']) && $allFormData[$courseId]['mainCourseId'] > 0){
			$mainCourseId = $allFormData[$courseId]['mainCourseId'];
		}else{
			$mainCourseId = $courseId;
		}
    	$this->CI->load->library('dashboardconfig');
        $config_array = DashboardConfig::$institutes_autorization_details_array;
        $PBTSeoData = Modules::run('onlineFormEnterprise/PBTFormsAutomation/getExternalFormConfigDetails');
		$config_array += $PBTSeoData;
        $instituteId = $config_array[$mainCourseId]['instituteId'];
        if(empty($instituteId) || $instituteId <=0){
        	show_404();exit();
        }
        
    	$this->CI->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $instituteRepo = $instituteBuilder->getInstituteRepository();
        $instituteObj = $instituteRepo->find($instituteId);

        if(empty($instituteObj) || $instituteObj->getId() <= 0){
        	show_404();exit();
        }
        $listingName = seo_url($instituteObj->getName(),"-","200",true);
        if(stripos($instituteObj->getName(), $instituteObj->getMainLocation()->getCityName()) === FALSE && $instituteObj->getMainLocation()->getCityName() != ''){
            $cityAppend = '-'.seo_url($instituteObj->getMainLocation()->getCityName(),"-","150",true);
        }

        $localityName = $instituteObj->getMainLocation()->getLocalityName();
        if(isset($localityName) && $localityName != ''){
            $localityAppend = '-'.seo_url($localityName,"-","150",true);
        }

		if($deprtment == ''){
			$deprtment = 'mba';
		}        
        $seoUrl = SHIKSHA_HOME.'/college/'.$listingName.$localityAppend.$cityAppend.'/'.$deprtment.'-application-form-'.$courseId;
        if($trackingPageKeyId !=''){
        	$seoUrl .= '?tracking_keyid='.$trackingPageKeyId;	
        }
        
        return $seoUrl;

    }

    function prepareBeaconTrackData($pageIdentifier,$department){
		$hierarchy = array(
			'streamId' =>			$department == 'Engineering' ? ENGINEERING_STREAM:MANAGEMENT_STREAM,
			'substreamId'=> 		0,
			'specializationId'=>	0,
			);

		$beaconTrackData = array(
			'pageIdentifier' => $pageIdentifier,
			'pageEntityId'   => '0', // No Page entity id for this one
			'extraData' => array(
				'hierarchy' => array($hierarchy),
					'countryId' => 2
				)
		);
		$beaconTrackData['extraData']['baseCourseId'] = $department == 'Engineering' ?ENGINEERING_COURSE :MANAGEMENT_COURSE;
		$beaconTrackData['extraData']['educationType'] = EDUCATION_TYPE;
		return $beaconTrackData;
	}

	function prepareBeaconTrackDataForCourse($pageIdentifier,$courseId,$courseObj){
		//_p($pageIdentifier);_p($courseObj);_p($pageEntityId);die;
		if(empty($courseObj) || ($courseObj && !($courseObj->getId()))){
			if($courseId > 0){
				$this->CI->load->builder('CourseBuilder','nationalCourse');
	            $courseBuilder = new CourseBuilder;
	            $courseRepository = $courseBuilder->getCourseRepository();
	            $courseObj = $courseRepository->find($courseId);
			}else{
				$beaconTrackData = array(
	                                'pageIdentifier' => $pageIdentifier,
	                                'pageEntityId' => 0,
	                                'extraData' => array(
	                                	'countryId' => 2
	                                	)
	                                );
				return $beaconTrackData;	
			}
		}
		$beaconTrackData = array(
                                'pageIdentifier' => $pageIdentifier,
                                'pageEntityId' => $courseId
                                );

        $courseTypeInfo = $courseObj->getCourseTypeInformation();
        if(!empty($courseTypeInfo)){
            $beaconEntryHieraries = array();
            $beaconExitHieraries = array();
            if(!empty($courseTypeInfo['entry_course'])){
            	if($courseTypeInfo['entry_course']->getCredential()->getId()){
            		$credentialId = $courseTypeInfo['entry_course']->getCredential()->getId();	
            	}
            	if($courseTypeInfo['entry_course']->getBaseCourse()){
            		$baseCourseId = $courseTypeInfo['entry_course']->getBaseCourse();
            	}

            	if($courseTypeInfo['entry_course']->getCourseLevel()){
            		$courseLevelId = $courseTypeInfo['entry_course']->getCourseLevel()->getId();
            	}

                $entryHierarchies = $courseTypeInfo['entry_course']->getHierarchies();
                if(!empty($entryHierarchies)){
                    foreach ($entryHierarchies as $key => $value) {
                    	$beaconEntryHieraries[] = array(
                    		'streamId'			=> $value['stream_id'],
                    		'substreamId'		=> $value['substream_id'],
                    		'specializationId'	=> $value['specialization_id'],
                    		'primaryHierarchy'	=> $value['primary_hierarchy']
                    		);
                    }
                    $beaconTrackData['extraData']['hierarchy'] = $beaconEntryHieraries;
                }
            }
        }

        $locationObj = $courseObj->getMainLocation();
        if($locationObj){
            $beaconTrackData['extraData']['cityId'] = $locationObj->getCityId();
            $beaconTrackData['extraData']['stateId'] = $locationObj->getStateId();
        }
        $beaconTrackData['extraData']['countryId'] = 2;
        //_p($courseTypeInfo->getCredential());die;
        if(!empty($credentialId)){
        	$beaconTrackData['extraData']['credential'] = $credentialId;
        }

        if(!empty($baseCourseId)){
        	$beaconTrackData['extraData']['baseCourseId'] = $baseCourseId;
        }

        if(!empty($courseLevelId)){
        	$beaconTrackData['extraData']['level'] = $courseLevelId;
        }

        if($courseObj->getEducationType()){
        	if($courseObj->getEducationType()->getId() >= 0){
        		$beaconTrackData['extraData']['educationType'] = $courseObj->getEducationType()->getId();
        	}
        }

        if($courseObj->getDeliveryMethod()){
        	$deliveryMethod = $courseObj->getDeliveryMethod()->getId();
        	if(!empty($deliveryMethod)){
        		$beaconTrackData['extraData']['deliveryMethod'] = $deliveryMethod;
        	}
        }
        return $beaconTrackData;
	}

	public function isCourseIdValid($courseId){
		if(empty($courseId)){
			return false;
		}
		$this->CI->load->builder("nationalCourse/CourseBuilder");
		$builder = new CourseBuilder();
		$courseRepository = $builder->getCourseRepository();
		$courseObj = $courseRepository->find($courseId);
		if(!is_object($courseObj)){
			return false;
		}
		$cId = $courseObj->getId();
		if(empty($cId)){
			return false;
		}
		return true;
	}
}
	
?>
