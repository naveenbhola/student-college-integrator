<?php
class CollegeReviewForm extends ShikshaMobileWebSite_Controller
{
    function __construct()
    {
            parent::__construct();
            $this->load->helper('mcommon5/mobile_html5');
    }

    function showReviewForm($str,$pageType){
        $data = array();
        if($str!=''){

            $this->load->model('CollegeReviewForm/collegereviewmodel');
            $this->crmodel = new CollegeReviewModel();

            $str = str_replace(' ','+',$str);
            $this->load->library('CollegeReviewForm/CollegeReviewLib'); 
            $this->CollegeReviewLib = new CollegeReviewLib();
            $finalData = $this->CollegeReviewLib->decodeReviewFormEditURL($str);

            /*$dataArr = explode("_",$str);
            $finalData = array();
            for($i = 0 ; $i < count($dataArr); $i++) {
                    $tempArr = explode("~",$dataArr[$i]);
                    $finalData[$tempArr[0]] = $tempArr[1];
            } */

            if($pageType != 'campusRep'){
                $data['canonicalURL'] = SHIKSHA_HOME.'/college-review-form';
            }
            
            if( isset($finalData['email']) && isset($finalData['reviewerId']) ){

                if($finalData['reviewerId'] == 0) {
                    
                    $userStatus = $this->checkUserValidation();

                    $userId = $userStatus[0]['userid'];

                    $data = $this->crmodel->getReviewDataWithCRDetails($finalData['reviewerId'], $finalData['reviewId'], $userId);

                    $data['userId'] = $userId;

                } else {

                    $data = $this->crmodel->getReviewDataWithPersonalDetails($finalData);
                    $data['countryIdForIsdCode'] = $this->crmodel->getCountryIdFromIsdCode($data['isdCode']);   
                    $data['countryIdForIsdCode'] = $data['isdCode'].'-'.$data['countryIdForIsdCode'];

                }

                $data['ratingParams'] = $this->CollegeReviewLib->getRatingValues($data['reviewId']);
                //$data['motivationFactorValue'] = $this->crmodel->getMotivationIdByReviewId($data['reviewId']);
                if($data['isShikshaInstitute']=='NO'){
                        $data['instituteIdentifier'] = $data['instituteName'];
                }
                //$data['motivationFactor'] = $data['motivationMasterId'];
                
                if($data['isShikshaInstitute']=='YES'){
                        $this->load->builder("nationalCourse/CourseBuilder");
                        $listingBuilder = new CourseBuilder();
                        $this->courseRepository = $listingBuilder->getCourseRepository();
                        $res = $this->courseRepository->find($data['courseId'],array('basic','location'));
                        $data['instituteName'] = $res->getInstituteName();
                        $data['instituteIdentifier'] = $data['instituteId'];
                       // $data['locationList'] = $this->getLocationForInstitute($data['instituteId']);
                        $data['selectedlocationId'] = $data['locationId'];
                        
                        //$data['courseList'] = $this->getCoursesForInstituteAndLocation($data['instituteId'],$data['locationId']);
                        $data['selectedCourseId'] = $data['courseId'];
                        
                        // $allLocations = $res->getLocations();
                        // $locName = $allLocations[$data['locationId']]->getLocalityName();
                        // $cityName = $allLocations[$data['locationId']]->getCityName();
                        // $stateName = $allLocations[$data['locationId']]->getStateName();
                        // $countryName = 'India';
                        // $data['location'] = (($locName)?$locName.", ":"").$cityName. (($stateName)? ', '.$stateName:"").', '.$countryName;
                }
            }else{
                // Checking that referral code allocated to any user or not
                if(($pageType == 'campusRep') && (!empty($str))) {
                    $userReferralCodeInfo = $this->crmodel->getUserReferralInfoByReferralCode($str);
                    if(empty($userReferralCodeInfo)) {
                        header("Location: ".SHIKSHA_HOME,TRUE,301);exit;
                    }
                    $data['parentReferralId'] = $userReferralCodeInfo['id'];
                    $data['parentEmailId'] = $userReferralCodeInfo['email'];
                }
            }
        }
        if($pageType == 'letsIntern'){
            $data['letsIntern'] = $pageType;
        }

        $data['rateSectionHeading'] = 'Rate Your College On The Following Parameters';

        if ($data['courseId'] == '' || empty($data['courseId'])) {
            $data['ratingParam'] = $this->getDefaultReviewRatingForm(); //will get default form MBA form
        }else{
            $data['ratingParam'] = $this->getReviewRatingFormByCourseId($data['courseId']);
        }


        $isdCode = new \registration\libraries\FieldValueSources\IsdCode;
        $data['isdCode'] = $isdCode->getValues();

        $data['pageType'] = $pageType;
        $data['pageName'] = 'collegeReview';
		//for beacon tracking purpose
        $data['trackingpageIdentifier']='collegeReviewRatingForm';
        $data['trackingcountryId']=2;
        //this used for storing the data in beacon varaible for tracking purpose
        $this->tracking=$this->load->library('common/trackingpages');
        $this->tracking->_pagetracking($data);
        $this->load->view('collegeReviewOverview',$data);
    }
    
    function storeReviewFormData(){

        $this->load->model('CollegeReviewForm/collegereviewmodel');
        $this->crmodel = new CollegeReviewModel();
        $data['anonymous'] = isset($_POST['anonymous'])?$this->input->post('anonymous'):'NO';
        $data['userId'] = isset($_POST['userId'])?$this->input->post('userId'):'0';
        $data['reviewTitle'] = $this->input->post('titleReview');
        $data['placementDescription'] = $this->input->post('placementDescription');
        $data['infraDescription'] = $this->input->post('infraDescription');
        $data['facultyDescription'] = $this->input->post('facultyDescription');
        $data['reviewDescription'] = $this->input->post('reviewDescription');
        $data['fees']                 = str_replace(',', '', $this->input->post('fees'));
        $data['ratingParam'] = unserialize(dec_enc('decrypt',$this->security->xss_clean($this->input->post('ratingParam'))));


        $count = 0;
        $totalRating=0;
        //to get the value of all the ratings param, generated dynamically
        foreach ($data['ratingParam'] as $key => $value) {
            $postVariable = "Rating_".$key;
            $paramValue[$key] = $this->input->post($postVariable);
            $totalRating = $totalRating + $paramValue[$key];
            $count++;
        }

        $data['averageRating'] = $totalRating/$count;
        $data['ratingValues'] = $paramValue;
        unset($paramValue);

        if($this->validateMandatoryReviewData($data) == false){
            echo 'error';
            return;
        }

        $data['recommendCollegeFlag'] = $this->input->post('recommendCollegeFlag');
        $data['isShikshaInst'] = $this->input->post('isShikshaInst');

        
        $data['suggested_institutes'] = $this->input->post('suggestedInstitutes');

        if(is_array($data['suggested_institutes']) && !empty($data['suggested_institutes'])){
            $data['suggested_institutes'] = $data['suggested_institutes'][0];
        }

        if($data['suggested_institutes'] == 0){
            echo "error";
            return;
        }

        $data['course'] = $this->input->post('course');

        if(is_array($data['course']) && !empty($data['course'])){
            $data['course'] = $data['course'][0];
        }

        //$data['location'] = $this->input->post('location');
        $locationMapId = $this->input->post('location');

        if(is_array($locationMapId) && !empty($locationMapId)){
            $mapId = $locationMapId[0];
            list($stateId,$cityId,$localityId) = explode("_", $mapId);            
        }

        $this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder                = new CourseBuilder();
        $courseRepo                   = $courseBuilder->getCourseRepository(); 
        $courseObj                    = $courseRepo->find($data['course'],array('basic','location'));
        $data['primary_institute_id'] = $courseObj->getInstituteId();        
        $locations                    = $courseObj->getLocations();

        foreach ($locations as $listingLocationId => $locObj) {
            $objStateId    = $locObj->getStateId();
            $objCityId     = $locObj->getCityId();
            $objLocalityId = $locObj->getLocalityId();

            if($objStateId == $stateId && $objCityId == $cityId && $objLocalityId == $localityId){
                $data['location'] = $listingLocationId;     
                break;
            }
        }

        if(!isset($data['location'])){
            echo "error";
            return;
        }

        $data['yearOfGraduation'] = $this->input->post('yearOfGraduation');

        if(is_array($data['yearOfGraduation']) && !empty($data['yearOfGraduation'])){
            $data['yearOfGraduation'] = $data['yearOfGraduation'][0];
        }

        $isdCode = $this->input->post('isdCode');
        $isdCode = explode('-', $isdCode);
        
        $personalData['isdCode'] = $isdCode[0];

        if(is_array($personalData['isdCode']) && !empty($personalData['isdCode'])){
            $personalData['isdCode'] = $personalData['isdCode'][0];
        }
        
        $error = $this->validateData($data);

        if($error != "" && $error != NULL){
            echo $error;
            return;
        }

        $formName = $_POST['formName'];
      
        $reviewerId = isset($_POST['reviewerId'])?$this->input->post('reviewerId'):0;
        $data['reviewSource']    = (isset($_POST['reviewSource']) && $_POST['reviewSource']!='')?$this->input->post('reviewSource'):NULL;
        $personalData['firstname'] = $this->input->post('firstname');
        $personalData['lastname'] = $this->input->post('lastname');
        $personalData['email'] = $this->input->post('email');
        $personalData['mobile'] = $this->input->post('mobile');
        $personalData['linkedInURL'] = $this->input->post('linkedInURL');
        $personalData['facebookURL'] = $this->input->post('facebookURL');
        $parentReferralId = $this->input->post('parentReferralId');
        $parentEmailId = $this->input->post('parentEmailId');
        $pageType = $this->input->post('pageType');
        $oldReviewId = $this->input->post('reviewId');
        $data['incentiveFlag'] = $this->input->post('incentiveFlag');

        $caUtilityLib = $this->load->library('CAEnterprise/CAUtilityLib');
        if(empty($oldReviewId)) {
            $reviewDetails = $caUtilityLib->checkIfDetailsExistInDB($personalData['email'],$data['suggested_institutes'],$data['course'],$data['isShikshaInst']);
            if(isset($reviewDetails['id'])){
                if($reviewDetails['status'] == "published"){
                    echo "reviewExist";return;
                }else{
                    $reviewId = $reviewDetails['id'];
                }
            }
        } else { // this is case of edit review via CA
            $reviewId = $oldReviewId;
        }
        if($reviewId>0){
                $action = "reviewEdited";
                $this->crmodel->updateMainTableData($data,$reviewerId,$reviewId);
                $this->crmodel->updateRatingMapping($data,$reviewId);
                $this->crmodel->updateShikshaInstitute($data,$reviewId);
                
                if($reviewerId > 0) {
                    $this->crmodel->updatePersonalReviewData($personalData,$reviewerId);
                }

                //insert into indexing queue
                Modules::run('CollegeReviewForm/SolrIndexing/insertReviewForIndex',$reviewId);

                // Generate User College Review Referral Code
                $this->processReviewReferral($personalData['email'], $pageType, $parentReferralId, $parentEmailId, $reviewId, 'NO');
        }else{
                $action = "reviewAdded";
                $data['visitorSessionId'] = getVisitorSessionId();
                $reviewerId = $this->crmodel->submitPersonalReviewData($personalData);
                $reviewId = $this->crmodel->submitReviewData($data,$reviewerId);
                $this->crmodel->updateRatingMapping($data,$reviewId);
                $this->crmodel->insertIntoShikshaInstitute($data,$reviewId);
                
                //insert into indexing queue
                Modules::run('CollegeReviewForm/SolrIndexing/insertReviewForIndex',$reviewId);
                // Generate User College Review Referral Code
                $this->processReviewReferral($personalData['email'], $pageType, $parentReferralId, $parentEmailId, $reviewId, 'YES');

                //send mail to user after review submission
                $this->collegeReviewMailers('recieved', $personalData['email'], $reviewerId,'','',$data['incentiveFlag']);

        }

        // track college review
        $caUtilityLib->trackCollegeReview($reviewId, $action, $data, 0);

        echo $reviewId."#######";
                return;
       
    }


    private function validateMandatoryReviewData($data){        
        if(empty($data['placementDescription']) || strlen($data['placementDescription']) < 250){
            return false;
        }

        if(empty($data['infraDescription']) || strlen($data['infraDescription']) < 250){
            return false;
        }


        if(empty($data['facultyDescription']) || strlen($data['facultyDescription']) < 250){
            return false;
        }

        if(empty($data['reviewTitle']) || strlen($data['reviewTitle']) < 25){
            return false;
        }
        
        return true;
    }

    public function processReviewReferral($email, $pageType = '', $parentReferralId, $parentEmailId, $last_insert_id_camaintable, $doTracking = 'NO') {

        $userReferralInfo = $this->crmodel->getUserReferralInfoByEmail($email);

        if(!empty($userReferralInfo)) {
            $referralCode = $userReferralInfo['referralCode'];
            $referralURL = COLLEGE_REVIEW_REFERRAL_URL."/".$referralCode;
            $userReferralId = $userReferralInfo['id'];
        } else {

            $this->load->library('CollegeReviewForm/CollegeReviewLib');
            $this->CollegeReviewLib = new CollegeReviewLib();

            $isreferralUniqueCode = 'NO';
            $referralCode = '';
            while($isreferralUniqueCode != 'YES') {
                $codeLength = 6;
                $referralCode = $this->CollegeReviewLib->generateRandomString($codeLength);
                if(!empty($referralCode)) {
                    $userReferralCodeInfo = $this->crmodel->getUserReferralInfoByReferralCode($referralCode);
                    if(empty($userReferralCodeInfo)) {
                        $isreferralUniqueCode = 'YES';
                    }
                }
            }

            $userReferralData = array();
            $userReferralData['email'] = $email;
            $userReferralData['referralCode'] = $referralCode;
            
            $referralURL = COLLEGE_REVIEW_REFERRAL_URL."/".$referralCode;
            $userReferralData['referralURL'] = $referralURL;

            $userReferralId = $this->crmodel->saveUserReferralInfo($userReferralData);
        }
        echo $referralURL.'#######';
        $socialSharingData = array();
        $socialSharingData['permURL'] = COLLEGE_REVIEW_REFERRAL_URL;
        $socialSharingData['view'] = 'permalinkBottom'; 
        $socialSharingData['share'] = array('facebook','twitter','linkedin','google');
        $socialSharingData['subTitle'] = 'Guys,review your college. It helps future students and might help me drive a Merc as well. ';
        //$socialView = $this->load->view('mCollegeReviews5/socialSharingMobile',$socialSharingData,TRUE);
        echo "&nbsp".'#######';
        $googleConversionArray = array();
        $googleConversionArray['referralCode'] = $referralCode;
        $googleConversionArray['pageType'] = $pageType;
        $this->load->view('CollegeReviewForm/reviewGoogleConversionCode',$googleConversionArray);                        

        if(($pageType == 'campusRep') && (!empty($parentReferralId)) && (!empty($parentEmailId)) && (!empty($userReferralId)) && ($doTracking == 'YES')) {
            $referralTrackingData = array();
            $referralTrackingData['parentReferralId'] = $parentReferralId;
            $referralTrackingData['userReferralId'] = $userReferralId;
            $referralTrackingData['userReviewId'] = $last_insert_id_camaintable;
            if($email != $parentEmailId) {
                $referralTrackingData['reviewSubmittedBy'] = 'other';
            } else {
                $referralTrackingData['reviewSubmittedBy'] = 'self';
            }
            $this->crmodel->saveReferralTrackingInfo($referralTrackingData);
        }

    }
    
    // Backend checking for Form
    function validateData($data){
            
        if($data['suggested_institutes'] == NULL || $data['suggested_institutes'] == ""){
            return "error";
        }
        if($data['location'] == NULL || $data['location'] == ""){
            return "error";
        }
        if($data['course'] == NULL || $data['course'] == ""){
            return "error";
        }
        if($data['yearOfGraduation'] == NULL || $data['yearOfGraduation'] == ""){
            return "error";
        }
        
        foreach ($data['ratingValues'] as $key => $value) {
            if(empty($value)){
                return "error";
            }
        }

        if($data['recommendCollegeFlag'] == NULL || $data['recommendCollegeFlag'] == ""){
                return "error";
        }
        if($data['isShikshaInst'] == NULL || $data['isShikshaInst'] == ""){
                return "error";
        }
        if($data['placementDescription'] == NULL || $data['placementDescription'] == ""){
                return "error";
        }
        if($data['infraDescription'] == NULL || $data['infraDescription'] == ""){
                return "error";
        }
        if($data['facultyDescription'] == NULL || $data['facultyDescription'] == ""){
                return "error";
        }
        if($data['reviewTitle'] == NULL || $data['reviewTitle'] == ""){
            return "error";
        }

        return "";
    }

    function collegeReviewMailers($mailType, $email, $revid, $userId=0, $reviewId=0,$incentiveFlag='')
    {
            $attachment = array();
            $contentArr = array();
            $this->load->model('CollegeReviewForm/collegereviewmodel');
            $this->crmodel = new CollegeReviewModel();
            $param = array('email'=> $email, 'reviewerId'=>$revid);
            //get review and personal data of user
            if($revid == 0)
            {
                    $data = $this->crmodel->getReviewDataWithCRDetails($revid, $reviewId, $userId);
                    $email = $data['email'];
            }
            else
            {
                    $data = $this->crmodel->getReviewDataWithPersonalDetails($param);
            }
            $this->load->library('CollegeReviewForm/CollegeReviewLib');
            $this->CollegeReviewLib = new CollegeReviewLib();
            if($data['isShikshaInstitute']=='NO'){
                    $contentArr['college_name']  = $data['instituteName'];
            }
            
            if($data['isShikshaInstitute']=='YES'){
                    $this->load->builder("nationalCourse/CourseBuilder");
                    $courseBuilder = new CourseBuilder();
                    $this->courseRepository = $courseBuilder->getCourseRepository();          
                    if($data['courseId']>0)
                    {
                            $res = $this->courseRepository->find($data['courseId']);
                            $contentArr['college_name']  = $res->getInstituteName();
                    }
                    
            }
            
            $contentArr['username']  = ucwords($data['firstname']);
            $contentArr['mobile'] = $data['mobile'];
            $contentArr['email'] = $data['email'];
            $contentArr['reviewDescription']  = $data['reviewDescription'];
            $contentArr['placementDescription'] = $data['placementDescription'];
            $contentArr['infraDescription'] = $data['infraDescription'];
            $contentArr['facultyDescription'] = $data['facultyDescription'];
            $contentArr['reviewTitle'] = $data['reviewTitle'];
            //$contentArr['form_url'] = $form_url = SHIKSHA_HOME.'/college-review-rating-form';


            if($mailType == 'recieved' || $mailType == 'published') {
                $userReferralInfo = $this->crmodel->getUserReferralInfoByEmail($email);
                $contentArr['referralURL'] = $userReferralInfo['referralURL'];
            }

            $contentArr['incentiveFlag'] = '';
            $form_url = SHIKSHA_HOME.'/college-review-form';
            if($incentiveFlag == '1'){
                $form_url = SHIKSHA_HOME.'/college-review-rating-form';
                $contentArr['incentiveFlag'] = 'incentive';
            }

            switch($mailType)
            {
                    /* review recieved mailer */
                    case 'recieved':
                            echo Modules::run('systemMailer/SystemMailer/CollegeReviewReceived_Mail',$email, $contentArr, $attachment);
                            break;
                    /* review accept mailer */
                    case 'published':
                            //encoding the url parameters
                            $qryParam = $this->CollegeReviewLib->encodeReviewFormEditURL($email, $revid);
                            $contentArr['edit_review_url'] = $form_url.'/'.$qryParam;
                            //$contentArr['isCampusRep'] = ($revid==0)?1:0;
                            Modules::run('systemMailer/SystemMailer/CollegeReviewPublish_Mail',$email, $contentArr, $attachment);
                            break;
                    /* review reject mailer */
                    case 'rejected':
                            //encoding the url parameters
                            $qryParam = $this->CollegeReviewLib->encodeReviewFormEditURL($email, $revid);
                            $contentArr['edit_review_url'] = $form_url.'/'.$qryParam;
                            //$contentArr['isCampusRep'] = ($revid==0)?1:0;
                            Modules::run('systemMailer/SystemMailer/CollegeReviewReject_Mail',$email, $contentArr, $attachment);
                            break;
            }
    }
        
    /**
	 * Get locations for Institute using institute Id
	 * Constructs the locations using location name , city name, state and country 
	 */
	function getLocationForInstitute($insId){
		$locationMappingArray = array();
		$this->load->builder('nationalInstitute/InstituteBuilder');
        $listingBuilder = new InstituteBuilder;
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$result = $instituteRepository->find($insId,array('basic','location'));
		$locations = $result->getLocations();
		$count = 0 ;
		
		// Constructing location string as combination of location,city,state and country
		foreach ($locations as $id => $insObj) {
			$locationStr = "";
			$locationName = $insObj->getLocalityName();
            $cityName = $insObj->getCityName();
            $stateName = $insObj->getStateName();
            $country = "India";
			
			if(!empty($locationName)) {
				$locationStr .= $locationName.', ';
			}
			
			if(!empty($cityName)) {
				$locationStr .= $cityName.', ';
			}
			
			if(!empty($stateName)) {
				$locationStr .= $stateName.', ';
			}
			
			if(!empty($country)) {
				$locationStr .= $country;
			}
			
			if(!empty($id)) {
				$locationArray['location_id'] = $id;
				$locationArray['location_name'] = $locationStr;
				$locationMappingArray[$count++] = $locationArray; 
			}			
		}
		return $locationMappingArray;
	}
	
	/**
	 * Get Courses using institute and location id.
	 */
	function getCoursesForInstituteAndLocation($insId,$locationId) {
		$courseMappingArray = array();
		$this->load->builder('nationalInstitute/InstituteBuilder');
        $this->load->builder('nationalCourse/CourseBuilder');
        $this->courseDetailLib = $this->load->library('nationalCourse/CourseDetailLib');
		$listingBuilder = new InstituteBuilder();
        $courseBuilder = new CourseBuilder();
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$coursesWithLocation = (array)$this->courseDetailLib->getCourseForInstituteLocationWise($insId);
		$courseList = $coursesWithLocation[$locationId];
		$courseList = array_filter($courseList);
        if(is_array($courseList) && count($courseList) > 0) {
            $courseRepository = $courseBuilder->getCourseRepository();
             $courses = $courseRepository->findMultiple($courseList,array('basic','location'));
             foreach ($courses as $courseId => $courseObj) {
                 $courseArray = array();
                 $courseArray['courseId'] = $courseId;
                 $courseArray['courseName'] = $courseObj->getName();
                 $courseMappingArray[] = $courseArray;
             }
             return $courseMappingArray;
        }
	}
        
    function generateDecodedURL($email){    
         //Get the ReviewerId from the Backend
         $this->load->model('CollegeReviewForm/collegereviewmodel');
         $this->crmodel = new CollegeReviewModel();
         $revid = $this->crmodel->getReviewerId($email);
         
         if($revid>0 && $email!=''){
             //Now, lets generate the Decoded Edit URL
             $this->load->library('CollegeReviewForm/CollegeReviewLib');
             $this->CollegeReviewLib = new CollegeReviewLib();
             
             $qryParam = $this->CollegeReviewLib->encodeReviewFormEditURL($email, $revid);
             $form_url = SHIKSHA_HOME.'/college-review-rating-form';
             $edit_review_url = $form_url.'/'.$qryParam;
             echo $edit_review_url;
         }
         else{
             echo "No Match found";
         }                    
    }

    function getReviewRatingFormByCourseId($courseId){
        $this->load->model('collegereviewmodel');
        $this->crmodel = new CollegeReviewModel();
        $this->load->builder("nationalCourse/CourseBuilder");
        $builder   = new CourseBuilder();
        $repo      = $builder->getCourseRepository();
        $courseObj = $repo->find($courseId, array('basic', 'course_type_information'));
        $hierarchy = $courseObj->getPrimaryHierarchy();
        $stream_id = $hierarchy['stream_id'];
        $basecourse = $courseObj->getBaseCourse();
        $base_course = $basecourse['entry'];

        $reviewRatingFormData = $this->crmodel->getReviewRatingForm($stream_id,$base_course);
        
        if(empty($reviewRatingFormData[0]['description'])){
            $reviewRatingFormData = $this->crmodel->getDefaultReviewRatingForm();
        }

        foreach ($reviewRatingFormData as $value) {
            $reviewRatingDescription[$value['id']] = $value['description'];
        }

        return $reviewRatingDescription;

    }

    function getReviewRatingForm($courseId){
        $this->load->model('CollegeReviewForm/collegereviewmodel');
        $this->crmodel = new CollegeReviewModel();

        if(!$courseId > 0){
            $courseId = $this->input->post('courseId');
        }
        $ratingTitle = $this->input->post('ratingTitle');
        if(!($courseId > 0)){
            return;
        }
        $this->load->builder("nationalCourse/CourseBuilder");
        $builder   = new CourseBuilder();
        $repo      = $builder->getCourseRepository();
        $courseObj = $repo->find($courseId, array('basic', 'course_type_information'));
        $hierarchy = $courseObj->getPrimaryHierarchy();
        $stream_id = $hierarchy['stream_id'];
        $basecourse = $courseObj->getBaseCourse();
        $base_course = $basecourse['entry'];

        $reviewRatingFormData = $this->crmodel->getReviewRatingForm($stream_id,$base_course);
        
        if(empty($reviewRatingFormData)){
            $reviewRatingFormData = $this->crmodel->getDefaultReviewRatingForm();
        }
        
        foreach($reviewRatingFormData as $value) {
                $reviewRatingDescription[$value['id']] = $value['description'];
        }
        

        $viewArray = array('rateSectionHeading' => $ratingTitle,
                            'ratingParam' => $reviewRatingDescription
                        );

        echo $this->load->view('mCollegeReviewForm5/ratingParameters',$viewArray);

    }

    function getDefaultReviewRatingForm(){
        $this->load->model('CollegeReviewForm/collegereviewmodel');
        $this->crmodel = new CollegeReviewModel();

        $reviewRatingFormData = $this->crmodel->getDefaultReviewRatingForm();

        foreach ($reviewRatingFormData as $value) {
            $reviewRatingDescription[$value['id']] = $value['description'];
        }

        return $reviewRatingDescription;
    }

    /*function getDefaultMotivationList(){
        $this->load->model('CollegeReviewForm/collegereviewmodel');
        $this->crmodel = new CollegeReviewModel();

        $motivationListData = $this->crmodel->getDefaultMotivationList();

        foreach ($motivationListData as $value) {
            $motivationList[$value['id']] = $value['description'];
        }

        return $motivationList;
    }

    function getMotivationList($courseId){
        $this->load->model('CollegeReviewForm/collegereviewmodel');
        $this->crmodel = new CollegeReviewModel();

        $courseId = $this->input->post('courseId');

        $this->nationalCourseLib = $this->load->library('listing/NationalCourseLib');
        $subCatArray = $this->nationalCourseLib->getDominantSubCategoryForCourse($courseId);
        $dominantCategory = $subCatArray['dominant'];
        
        $motivationListData = $this->crmodel->getMotivationList($dominantCategory);

        if(empty($motivationListData)){
            $motivationListData = $this->crmodel->getDefaultMotivationList();
        }

        foreach ($motivationListData as $value) {
            $motivationList[$value['id']] = $value['description'];
        }

        $viewArray = array('motivationReviewRatingFactor' => $motivationList);

        echo $this->load->view('mCollegeReviewForm5/motivationForm',$viewArray);

    }

    function getMotivationListByCourseId($courseId){
        $this->nationalCourseLib = $this->load->library('listing/NationalCourseLib');
        $subCatArray = $this->nationalCourseLib->getDominantSubCategoryForCourse($courseId);
        $dominantCategory = $subCatArray['dominant'];
        
        $motivationListData = $this->crmodel->getMotivationList($dominantCategory);
        
        if(empty($motivationListData[0]['description'])){
            $motivationListData = $this->crmodel->getDefaultMotivationList();
        }


        foreach ($motivationListData as $value) {
            $motivationList[$value['id']] = $value['description'];
        }

        return $motivationList;
    }*/

    function storeUberData($existingUberAccount, $uberEmail){
        $this->load->model('CollegeReviewForm/collegereviewmodel');
        $this->crmodel = new CollegeReviewModel();

        // $existingUberAccount = $this->input->post('existingUberAccount');
        $uberEmail = $this->input->post('uberEmail');
        $reviewId = $this->input->post('reviewId');
        $existingUberAccount = 'No';

        if($existingUberAccount && $uberEmail){
            if($this->crmodel->storeUberData($existingUberAccount, $uberEmail, $reviewId)){
                echo 'success';
            }else{
                echo 'failure';
            }
        }
    }
}
?>
