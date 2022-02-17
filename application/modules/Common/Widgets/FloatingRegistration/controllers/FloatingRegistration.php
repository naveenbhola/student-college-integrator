<?php

class FloatingRegistration extends MX_Controller
{
	
	function init($library=array('ajax'),$helper=array('url','image','shikshautility','utility_helper')){
		if(is_array($helper)){
			$this->load->helper($helper);
		}
		if(is_array($library)){
			$this->load->library($library);
		}
		if(($this->userStatus == ""))
			$this->userStatus = $this->checkUserValidation();
	
	}
	
    function index($showAsk='false',$displayAfter='0',$rightColId='',$instituteId = '', $isPaid = 'true',$careerId='0',$type='institute',$courseId='',$trackingPageKeyId='')
    {
	$validateuser = $this->checkUserValidation();
	if($validateuser !== 'false') {
		return;
	}
	
	$data = array();
	$data['showAsk'] = $showAsk;
	$data['displayAfter'] = $displayAfter;
	$data['widget'] = "floatingRegistration";
	$data['rightColId'] = $rightColId;
	$data['instituteId'] = $instituteId;
	//below line is used for conversion tracking purpose
	if(isset($trackingPageKeyId))
	{
		$data['trackingPageKeyId']=$trackingPageKeyId;
	}

	if(isset($_COOKIE['floatingRegisterWidgetClosed']) && $_COOKIE['floatingRegisterWidgetClosed']=='true'){
		$data['floatingRegisterWidgetClosed'] = 'true';
	}

        //Check if a discussion exists on this Institute. If it does, get the data for this discussion and then show the widget at the top.
	$this->load->helper(array('image'));
	$this->load->model('QnAModel');
        $data['discussionDetails'] = $this->QnAModel->getInstituteDiscussionDetails($appId=1,$instituteId);
	if($data['discussionDetails'] && is_array($data['discussionDetails']) && $instituteId>0){
		$data['closeHeading'] = 'Talk to a current student of this institute';
		$data['headingText'] = 'Talk to a current student of this institute';
		$this->load->view('listingFloatingDiscussionWidget',$data);
	}
	else{
	
    //Also, we will have to fetch the user details and check accordingly.
    //If the user is not logged in, we have to display the form in opened state and ready to be filled. When the user closes it, we will display the closed on and upon click on closed one, we will display the open one.
    //If the user is logged in, we have to display the form in prefilled state.
    //If the user is a LDB user, we do not need to display the widget. So, we can simply return.
    $validateuser = $this->checkUserValidation();
    if($validateuser != "false"){
        $data['displayForm'] = "none";
        $data['firstname'] = $validateuser[0]['firstname'];
        $data['lastname'] = $validateuser[0]['lastname'];
        $data['mobile'] = $validateuser[0]['mobile'];
        $data['cookiestr'] = $validateuser[0]['cookiestr'];
        $data['loggedIn'] = "true";
        $this->load->library('LDB_Client');
        $ldbObj = new LDB_Client();
        $result = $ldbObj->isLDBUser($validateuser[0]['userid']);
        $isLDBUser = false;
        if(is_array($result) && isset($result[0]['UserId'])){
              $isLDBUser = true;
        }
        $this->load->library('listing_client');
        $ListingClientObj = new Listing_client();
        if($showAsk=='true')
            $isUserResponse = $ListingClientObj->checkIfUserIsResponse($instituteId,$validateuser[0]['userid']);
    }else{
        $data['displayForm'] = "";
        $data['loggedIn'] = "false";
    }
    $data['validateuser'] = $validateuser;
    $data['isLDBUser'] = $isLDBUser;
	if($showAsk=='true'){

                $this->load->builder('ListingBuilder','listing');
                $listingBuilder = new ListingBuilder;
                $instituteRepository = $listingBuilder->getInstituteRepository();
                $institute = $instituteRepository->find($instituteId);

		$data['locationId'] = $institute->getMainLocation()->getCountry()->getId();
		$data['buttonText'] = 'Ask Now';
		$data['headingText'] = 'Have a question?';
		$data['headingText2'] = '<strong>Ask our Career Counselors!</strong>';
		$data['closeHeading'] = 'Show Interest';
		$data['closeLine'] = ( strlen(html_escape($institute->getName())) > 14 )?'Interested in studying at '.substr($institute->getName(),0,12).'...':'Interested in studying at '.$institute->getName();

        if($isPaid=='true'){
            $data['headingText'] = ( strlen(html_escape($institute->getName())) > 32 )?'Have a question about <br/>'.substr($institute->getName(),0,30).'...':'Have a question about <br/>'.$institute->getName();
            $data['headingText2'] = '<strong>Ask the institute directly</strong>';
	    
	  
        }
        //IN case of free listing, or in case of paid listing with LDB user logged in, we have to change the communication on the Widget
        if($isUserResponse=='true'){
                $data['closeHeading'] = 'Find best Institute for you';
                $data['closeLine'] = 'We need few details to get started';
        }
        if($isPaid=='false' || ($isPaid=='true' && $isLDBUser)){
                $data['closeHeading'] = 'I have a Question';
                $data['closeLine'] = (strlen(html_escape($institute->getName())) > 30 )?'about '.substr($institute->getName(),0,28).'...':'about '.$institute->getName();
        }
	 //code by pragya
	$this->load->builder('ListingBuilder','listing');
	$listingBuilder = new ListingBuilder;
	$this->instituteRepository = $listingBuilder->getInstituteRepository();
	$courses = $this->_getCourses($instituteId);
	$institutes= $listingBuilder->getInstituteRepository()->findWithCourses(array($instituteId => $courses));
	foreach ($institutes as $institute){
	    $courses=$institute->getCourses();
	}
	$data['courses'] = $courses;
        $data['hideCaptcha'] = 'none';

	        $params = array(
                        'instituteId'=>$institute->getId(),
                        'instituteName'=>$institute->getName(),
                        'type'=>'institute',
                        'locality'=>$institute->getMainLocation()->getLocality()?$institute->getMainLocation()->getLocality()->getName():"",
                        'city'=>$institute->getMainLocation()->getCity()->getName()
                    );
		$this->load->helper('url');
		$data['askUrl'] = listing_detail_ask_answer_url($params);
	}
	else{
                if($careerId=='0'){
		    $data['closeHeading'] = 'Find the best Institute for you';
		    $data['closeLine'] = 'We need few details from you to get started';
		}else{
		    $this->load->builder('CareerBuilder','Careers');
		    $careerBuilder = new CareerBuilder;
		    $careerRepository = $careerBuilder->getCareerRepository();
		    $careerData = $careerRepository->find($careerId);
		    $careerName = $careerData->getName();
		    $data['closeHeading'] = 'Stay updated on email about '.$careerName.' as a career';
		    $data['closeLine'] = '';
		}
		$data['headingText'] = 'Find the best Institute for you';
		$data['headingText2'] = '<p>We need few details from you to get started</p>';
		$data['buttonText'] = 'Submit';
                $data['hideCaptcha'] = 'block';
	} 
	if(!$isLDBUser || ($isLDBUser && $isPaid=='true' && $showAsk=='true')){
		if($careerId=='0'){
		    $data['type']=$type;
		    if($type=='course')
		    $data['courseId']=$courseId;
		    $this->load->view('FloatingRegistration',$data);
		}else{
		     $this->load->builder('CareerBuilder','Careers');
		     $careerBuilder = new CareerBuilder;
		     $careerRepository = $careerBuilder->getCareerRepository();
		     $careerData = $careerRepository->find($careerId);
		     $careerName = $careerData->getName();
		    $data['headingText'] = 'Stay updated on email about '.$careerName.' as a career';
		     $data['headingText2'] = '';
		     $this->load->view('CareerFloatingRegistration',$data);
		} 
	}
    }
    }    
    
    function catFloatingRegistration($showAsk='false',$displayAfter='0',$rightColId='',$instituteId = '', $isPaid = 'true',$careerId='0',$pageType='')
    {
	$data = array();
	$data['showAsk'] = $showAsk;
	$data['displayAfter'] = $displayAfter;
	$data['widget'] = "floatingRegistration";
	$data['rightColId'] = $rightColId;
	$data['instituteId'] = $instituteId;
	$data['floatingRegisterWidgetClosed'] = false;
	$validateuser = $this->checkUserValidation();
	//error_log("check if here validateuser: ".print_r($validateuser,true));
	if($validateuser != "false")
	{
	    $data['displayForm'] = "none";
	    $data['firstname'] = $validateuser[0]['firstname'];
	    $data['lastname'] = $validateuser[0]['lastname'];
	    $data['mobile'] = $validateuser[0]['mobile'];
	    $data['cookiestr'] = $validateuser[0]['cookiestr'];
	    $data['loggedIn'] = "true";
	    $this->load->library('LDB_Client');
	    $ldbObj = new LDB_Client();
	    $result = $ldbObj->isLDBUser($validateuser[0]['userid']);
	    $isLDBUser = false;
	    if(is_array($result) && isset($result[0]['UserId']))
	    {
		  $isLDBUser = true;
	    }
	    $this->load->library('listing_client');
	    $ListingClientObj = new Listing_client();
	    if($showAsk=='true' && $pageType!='category')
		$isUserResponse = $ListingClientObj->checkIfUserIsResponse($instituteId,$validateuser[0]['userid']);
	    }
	else
	{
	    $data['displayForm'] = "";
	    $data['loggedIn'] = "false";
	}
	$data['validateuser'] = $validateuser;
	$data['isLDBUser'] = $isLDBUser;
	$data['pageType'] = $pageType;
	$data['buttonText'] = 'Ask Now';
	$data['headingText'] = 'Have a question? Ask our Experts!';
	//$data['headingText2'] = '<strong>Ask our Counselors!</strong>';
	$data['closeHeading'] = 'Ask our Counselors!';
	$data['hideCaptcha'] = 'none';
	
	if((!$isLDBUser || ($isLDBUser && $isPaid=='true' && $showAsk=='true')) && $careerId=='0')
	    $this->load->view('FloatingRegistration',$data);  
    }
    
   function course($showAsk='false',$displayAfter='0',$rightColId='',$instituteId = '', $courseId = '', $currentLocationId ='' ,$isPaid = 'true',$careerId='0') {
   	
	   	$this->init();
	   	 
	   	$this->load->model('CA/cadiscussionmodel');
	   	$this->CADiscussionModel = new CADiscussionModel();
	   	 
	   	 
	   	$this->load->builder('ListingBuilder','listing');
	   	$listingBuilder = new ListingBuilder;
	   	$instituteRepository = $listingBuilder->getInstituteRepository();
	   	$institute = $instituteRepository->find($instituteId);
	   	
	   	$courseRepository = $listingBuilder->getcourseRepository();
	   	$course = $courseRepository->find($courseId);
	   	 
	   	
	   	$courseTuplesData = $this->CADiscussionModel->getCampusReps($courseId,$instituteId);
	   	
	   	$courseTuples["showAsk"] = $showAsk;
	   	$courseTuples["displayAfter"] = $displayAfter;
	   	$courseTuples["widget"] = "floatingRegistration";
	   	$courseTuples["rightColId"] = $rightColId;
	   	$courseTuples["instituteId"] = $instituteId;
	   	$courseTuples["courseId"] = $courseId;
	   	$courseTuples["course"] = $course;
	  	$courseTuples["currentLocationId"] = $currentLocationId;
	   	$courseTuples['closeHeading'] = 'Talk to a current student of this institute';
	   	$courseTuples['headingText'] = 'Talk to a current student of this institute';
	   	$courseTuples['institute'] = $institute;
	   	$courseTuples["data"] = $courseTuplesData;
		$this->load->view('FloatingWidgetCamousConnect',$courseTuples);   
	   		
   }
    	
    private function _getCourses($institute_id){
		if($institute_id){
			$this->courses = $this->instituteRepository->getLocationwiseCourseListForInstitute($institute_id);
		}		
		$courseList = array();
		foreach($this->courses as $course){
			if((($_REQUEST['city'] == $course['city_id']) || !($_REQUEST['city']))
			   && (($_REQUEST['locality'] == $course['locality_id']) || !($_REQUEST['locality']) || $_REQUEST['locality'] == 'All')){
				$courseList = array_merge($courseList,$course['courselist']);
			}
		}
	    return array_unique($courseList);
	}
	
}
