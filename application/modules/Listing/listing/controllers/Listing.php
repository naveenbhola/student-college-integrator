<?php

/*

   Copyright 2007 Info Edge India Ltd

   $Rev:: 500           $:  Revision of last commit
   $Author: manishz $:  Author of last commit
   $Date: 2010-06-29 11:38:49 $:  Date of last commit

   $Id: Listing.php,v 1.258 2010-06-29 11:38:49 manishz Exp $:

 */

class Listing extends MX_Controller {
    //Controller default API to ask for specific API call
    var $cacheLib;
    var $HTMLCaching = 'true';
    var $perfImprove = 'true';

    function index()
    {
        echo "Please specify the Name of the API to be called!!";
    }

    function init() {
	ini_set('max_execution_time', '1800000');
        $this->load->helper(array('form', 'url', 'image_helper','shikshaUtility'));
        $this->load->library(array('miscelleneous','message_board_client','blog_client','ajax','category_list_client','listing_client','register_client','alerts_client','keyPagesClient','lmsLib','relatedClient','cacheLib'));
	$this->load->library('Event_cal_client');
        $this->userStatus = $this->checkUserValidation();
        $this->cacheLib = new cacheLib();
    }    

    //function when the institute or course viwed is deleted
    function displayForDeletedListing($displayData){
      $this->load->view('listing/listing_detail_deleted',$displayData);
    }

    function checkIfUserExistsForListingAnA(){
        $this->init();
        $email = $this->input->post('email');
        $ListingObj = new Listing_client();
        $check = $ListingObj->checkIfUserExistsForListingAnA($email);
        if ($check!=''){
            echo  json_encode($email);
        }else{
            echo  0;
        }
    }
	
	function makeBreadCrumb($details){
		$this->load->library('categoryList/categoryPageRequest');
		$this->load->library('listing/Listing_client');
		$Listing_client = new Listing_client();
		$this->load->builder('CategoryBuilder','categoryList');
		$categoryBuilder = new CategoryBuilder;
		$categoryRepository = $categoryBuilder->getCategoryRepository();
		
		$refrer = $_SERVER['HTTP_REFERER'];
		$refrer = strtolower($refrer);
		if(stripos($refrer,"search/index") !== FALSE){
			$crumb[0]['name'] = "Search Results";
			$crumb[0]['url'] = $_SERVER['HTTP_REFERER'];
		}else{
			if(stripos($refrer,"-categorypage-") !== FALSE){
				$categoryPage = explode("-categorypage-",$refrer);
				$requestURL = new CategoryPageRequest($categoryPage[1]);
				$request = explode("-",$categoryPage[1]);
			}else{
				$requestURL = new CategoryPageRequest();
				if(count($details['categoryArr']) <= 0){
					return array();
				}
				$i = rand(0,count($details['categoryArr'])-1);
				$subCategory = $categoryRepository->find($details['categoryArr'][$i]['category_id']);
				$category = $categoryRepository->find($subCategory->getParentId());
				$requestURL->setData(array('categoryId' => $category->getId(),'subCategoryId' => $subCategory->getId(),'countryId' => $details['locations'][0]['country_id'] ));
			}
			$crumb[0]['url'] = $requestURL->getURL();
			if($request[1] == 1 || $requestURL->getSubCategoryId() == 1){
				$category = $categoryRepository->find($requestURL->getCategoryId());
				$crumb[0]['name'] = $category->getName();
			}else{
				$subCategory = $categoryRepository->find($requestURL->getSubCategoryId());
				$category = $categoryRepository->find($subCategory->getParentId());
				$crumb[0]['name'] = $subCategory->getName();
				$requestURL->setData(array('categoryId' => $category->getId(),'subCategoryId'=> 1,'LDBCourseId'=>1));
				$crumb[1]['url'] = $requestURL->getURL();
				$crumb[1]['name'] = $category->getName();
			}
		}
		return $crumb;
	}
	
    function sortArray(&$courseList,$count){
        //error_log(print_r($courseList,true),3,'/home/aakash/Desktop/aakash.log');
        //error_log(print_r($count,true),3,'/home/aakash/Desktop/aakash.log');
        for($i=0;$i<$count;$i++){
             $temp = array();
             for($j=$i+1;$j<$count;$j++){
                 if($courseList[$i]['course_order']>$courseList[$j]['course_order']){
                 $temp = $courseList[$i];
                 $courseList[$i] = $courseList[$j];
                 $courseList[$j] = $temp;
                 }
             }
         }
         return $courseList;
    }

    function sortCourseOrder($identifier,$categoryList,$courseList,$courseId){

        $courseList = unserialize(base64_decode($courseList));
        $count = count($courseList);
        if($identifier == 'institute'){

         /*for($i=0;$i<$count;$i++){
             $temp = array();
             for($j=$i+1;$j<$count;$j++){
                 if($courseList[$i]['course_order']>$courseList[$j]['course_order']){
                 $temp = $courseList[$i];
                 $courseList[$i] = $courseList[$j];
                 $courseList[$j] = $temp;
                 }
             }
         }*/
            $courseList = $this->sortArray($courseList,$count);
        }elseif($identifier == 'course'){
        $currentCourseCategory = array();
        for($i=0;$i<$count;$i++){
            if($categoryList[$i]['course_id'] == $courseId){
                $currentCourseCategory = explode(",",$categoryList[$i]['category_id']);
                break;
            }
        }

        $similarCategoryCourses = array();
        $differentCategoryCourses = array();
        for($i=0;$i<$count;$i++){
            $tempCategory = explode(",",$categoryList[$i]['category_id']);

            $result = array_intersect($currentCourseCategory,$tempCategory);

            if(empty($result)){
                $differentCategoryCourses[] = $courseList[$i];
            }else{
                $similarCategoryCourses[] = $courseList[$i];
            }
        }
        $similarCoursesDetails = array();
        $differentCoursesDetails = array();
        //$courseList = array();
        $similarCategoryCourses= $this->sortArray($similarCategoryCourses,count($similarCategoryCourses));
        $differentCategoryCourses= $this->sortArray($differentCategoryCourses,count($differentCategoryCourses));
        $tempList = array();
        if(!empty($similarCategoryCourses)){
            foreach($similarCategoryCourses as $similar){
            $tempList[] = $similar;
            }
        }
        if(!empty($differentCategoryCourses)){
            foreach($differentCategoryCourses as $diff){
            $tempList[] = $diff;
           }
        $courseList = $tempList;
        }
        }

        return $courseList;


    }

    function getDataForAnAWidget($institute_id,$listing_type,$tab){
	//This function is not used anymore
        echo "";
        exit;

    $this->init();
    $appId =1;
    $ListingClientObj = new Listing_client();
    $RelatedClient = new RelatedClient();
    $crumb = array();
    $otherInstitutesCategory = '';
        if(!isCategoryPageRefrer($crumb)){
            $otherInstitutesCategory = '';
        }
        else{
            if(strtolower($crumb['subCat']) != 'all' && strlen($crumb['subCat']) > 0){
                $categoryClient = new Category_list_client();
                $otherInstitutesCategory = $categoryClient->getSubCategoryIdByURLName($appId,$crumb['catId'], $crumb['subCat']);
            }
            else{
                $otherInstitutesCategory = $crumb['catId'];
            }
        }
    //$listingDetails = $ListingClientObj->getListingDetails($appId,$institute_id,'institute',$otherInstitutesCategory,1);

$listingDetails = $ListingClientObj->getBasicDataForListing($appId,$institute_id,'institute');

    //Ankur: Check if the Question data is available in the DB and not expired. If yes, then get the Question Ids from the DB, else get it from search
    $questionIds = $ListingClientObj->checkListingQuestions($appId,$institute_id);
    $displayData['searchAgainFlag'] = $questionIds['1'];
    $questionIds = $questionIds['0'];	
    if((! (is_array($questionIds) && isset($questionIds[0]))) || ($displayData['searchAgainFlag']=='1') ){
	$keywordForQues = $RelatedClient->getKeywordForQues($institute_id, $listing_type);
	$key = md5('questionIdArray_'.$institute_id);

	$questionIds = $this->getQuestionIdsForListing($keywordForQues,$institute_id,$listing_type);
	//Checking for recently posted questions in Cache
	if(isset($_COOKIE[$key])){
	    $recentlyAddedQuestion = array();
	    $recentlyAddedQuestion = $_COOKIE[$key];
	    $recentlyAddedQuestion = explode(",",$recentlyAddedQuestion);
	    foreach($recentlyAddedQuestion as $quesId){
		array_push($questionIds,$quesId);
	    }
	}
	//Ankur: Enter this data in the DB
	//Added to remove two questions from a Listing page
	$questionIdsTemp = array(); $j=0;
	if(in_array('1680073', $questionIds)){ 
	    for($i=0; $i<count($questionIds);$i++) {
	            if (!($questionIds[$i] == '1680073' || $questionIds[$i] == '1635456')){ $questionIdsTemp[$j] = $questionIds[$i]; $j++;}
		    
	    }
	    $questionIds = $questionIdsTemp;
	}

	$questionIdsList = implode(",",$questionIds);
	if($questionIdsList!=''){
	  $ListingClientObj->updateListingQuestions($appId,$institute_id,$questionIdsList,'All');
	}
	else{	//Modified by Ankur on 4 April for shifting the ANA widget in Cache. In case the search data does not return anything, store it in Cache
	    $keyAnA = md5('anaData_'.$institute_id);
	    $this->cacheLib->store($keyAnA,'No_Search_Data',43200);
	}
	//End Modifications by Ankur
    }
    else{
	$questionIds = $questionIds[0]['questionIds'];
	$questionIds = explode(",",$questionIds);
    }
    //End Code by Ankur for storing Listing related questions

    //Modified by Ankur on 4 April for shifting the ANA widget in Cache
    //Further modified by Ankur on 17 May, 2011 to shift the AnA widget data from APC to HTML file. This was done because APC size was increasing considerably due to this.
    /*$keyAnA = md5('anaWallData_'.$institute_id);
    if($this->cacheLib->get($keyAnA)!="ERROR_READING_CACHE" && $this->cacheLib->get($keyAnA)!='' && $this->cacheLib->get($keyAnA)!='No_Search_Data'){
	$wallDataForListings = $this->cacheLib->get($keyAnA);
    }
    else{
	$wallDataForListings = $this->getWallDataForListings($questionIds,'1');
	$this->cacheLib->store($keyAnA,$wallDataForListings,43200);
    }*/
    $anaWidgetFile = "ListingAnACache/anaWallData_".$institute_id.".html";
    $makeDBCall = true;
    if(file_exists($anaWidgetFile)){
	$last_modified = filemtime($anaWidgetFile);
	$nowTime = time();
	if(($nowTime - $last_modified) < 43200)
	  $makeDBCall = false;
    }
    if(!$makeDBCall){
	$wallDataForListings =  unserialize(file_get_contents($anaWidgetFile));
    }
    else{
	$wallDataForListings = $this->getWallDataForListings($questionIds,'1');
	$fp=fopen($anaWidgetFile,'w+');
	fputs($fp,serialize($wallDataForListings));
	fclose($fp);
    //After creating the file on one server, we have to copy the content to another file also
    $this->callToCopyFileToServers(base64_encode($anaWidgetFile));
    }
    //End Modifications by Ankur
    $countData = array();
    if($questionIds!='' && count($questionIds)>0)
        $countData = $ListingClientObj->getCountDataForAnAWidget($institute_id,$questionIds);
    $displayData['tab'] = $tab;
    $displayData['countData'] = $countData;
    $displayData['instituteId'] = $institute_id;
    $displayData['details'] = $listingDetails[0];
    $displayData['questionIds'] = $questionIds;
    $displayData['topicListings'] = $wallDataForListings;
    $displayData['validateuser'] = $this->userStatus;
    //error_log(print_r($displayData['topicListings']['0']['results'],true),3,'/home/aakash/Desktop/aakash.log');
    echo ($this->load->view('/listing/widgets/askNAnswer_widget',$displayData));
    }

    function searchAgainListingQuestion(){
        $this->init();
        $ListingClientObj = new Listing_client();
        $RelatedClient = new RelatedClient();

        $institute_id= $this->input->post('instituteId');

	if($institute_id=='' || $institute_id<=0){
		show_404();
	}

        $keywordForQues = $RelatedClient->getKeywordForQues($institute_id, 'institute');

        $questionIds = $this->getQuestionIdsForListing($keywordForQues,$institute_id,'institute');
        //Checking for recently posted questions in Cache

        //Ankur: Enter this data in the DB
	//Added to remove two questions from a Listing page
	$questionIdsTemp = array(); $j=0;
	if(in_array('1680073', $questionIds)){ 
	    for($i=0; $i<count($questionIds);$i++) {
	            if (!($questionIds[$i] == '1680073' || $questionIds[$i] == '1635456')){ $questionIdsTemp[$j] = $questionIds[$i]; $j++;}
		    
	    }
	    $questionIds = $questionIdsTemp;
	}

        $questionIdsList = implode(",",$questionIds);
        if($questionIdsList!=''){
          $ListingClientObj->updateListingQuestions($appId,$institute_id,$questionIdsList,'All');
        }
        echo 1;
    }	

    function getUrlForOverviewTab($type_id ,$identifier){

        $listingObj = new Listing_client();
        $url =array();
        $url = $listingObj->getUrlForOverviewTab($type_id ,$identifier);
        return $url;
    }


    function getWallDataForListings($questionIds=1,$categoryId=1) {
            $this->init();
            $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
            $lastTimeStamp = date('Y-m-d');
            $messageBoard = new Message_board_client();
            $wallData = $messageBoard->getWallDataForListings($appId=1,$userId,$start=0,$count=10,$categoryId,$countryId=1,$threadIdCsv='1',$lastTimeStamp,$questionIds);
            //error_log(print_r(count($wallData),true),3,'/home/aakash/Desktop/aakash.log');
            return $wallData;
        }

   function getQuestionIdsForListing($keywordForQues,$type_id,$listing_type) {
			$this->load->builder('SearchBuilder', 'search');
			$searchCommonLib 	= SearchBuilder::getSearchCommon();
			$questionIds = $searchCommonLib->getSearchListingIdsByType($keywordForQues, 'question', 0, 1000);
			return $questionIds;
			/*
			$AppId = 1;
            $location = '';
            $countryId = '';
            $categoryId = '';
            $cityId = '';
            $cityType = '';
            $courseLevel = '';
            $durationMin = '';
            $durationMax = '';
            $type = '';
            $relaxFlag = '0';
            $start = 0;
            $rows = '1000';
            $cType = '';
            $searchType = 'question';

            $tempArray = array($type_id,$listing_type);
            $listingDetail = implode(" ",$tempArray);

            $ListingClientObj = new Listing_client();
            $searchResult = $ListingClientObj->listingSponsorSearch($AppId,$keywordForQues,$location,$countryId,$categoryId,$start,$rows,$type,$searchType,$cityId,$relaxFlag,$cType,$courseLevel,$durationMin,$durationMax,$listingDetail);

            $questionIds = array();


            foreach($searchResult['results'] as $row) {
                $questionIds[] = $row['typeId'];
            }

            foreach($searchResult['extenedResults'] as $row) {
                $questionIds[] = $row['typeId'];
            }
            */
		}

/*This function is used to get Wiki for the listing on the
 * Institute Details and Course Details Page
 */
   function getWiki(){

       $this->init();
       $appId =1;
       $listingId = $this->input->post('listingId');
       $listingType = $this->input->post('listingType');
       $ListingObj = new Listing_client();
       if(empty($listingId) || empty($listingType)) {
            return;
       }
       $wiki = $ListingObj->getWikiForListing($appId,$listingId,$listingType);
       $response = unserialize(base64_decode($wiki));
       echo json_encode($response);
   }

   function getReasonToJoinInstitute($instituteId){
   $this->init();
   $ListingObj = new Listing_client();
   $reasonJoin = $ListingObj->getReasonToJoinInstitute($instituteId);
   return $reasonJoin;
   }
//This function add recently posted question From ANA widget And ANA Tab in the user cookie to get it displayed as indexing takes 5-6 min
   function addQuestionId(){
       $this->init();
       $questionIdArray = explode(",",($this->input->post('questionArray')));
       $threadId = $this->input->post('threadId');
       $instituteId = $this->input->post('instituteId');
       $key = md5('questionIdArray_'.$instituteId);
       if(isset($_COOKIE[$key])){
       $cookieArray = $_COOKIE[$key];
       $cookieArray = explode(",",$cookieArray);
       array_push($cookieArray,$threadId);
       $cookieArray = implode(",",$cookieArray);
       setcookie($key,$cookieArray,time()+1800,'/',COOKIEDOMAIN);
       }else{
           $cookieArray['0'] = $threadId;
           $cookieArray = implode(",",$cookieArray);
           setcookie($key,$cookieArray,time()+1800,'/',COOKIEDOMAIN);
       }
       echo 1;
   }


    function getSignInForm($redirUrl){
        header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
        $this->init();
        $displayData = array();
        $displayData['validateuser'] = $this->userStatus;
        $displayData['redirectUrl'] = base64_decode($redirUrl);
        $this->load->view('listing/signInWidget',$displayData);
    }

    function getLeadForm($title, $type_id, $type, $toEmail, $ccEmail){
        header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
        $this->init();
        $displayData = array();
        $displayData['validateuser'] = $this->userStatus;
        $categoryClient = new Category_list_client();
        $subCategoryList = $categoryClient->getSubCategories(1,1);
        $displayData['subCategories'] = $subCategoryList;
        $displayData['listing_type'] = $type;
        $displayData['type_id'] = $type_id;
        $displayData['details']['title'] = $title;
        $displayData['registerText'] = $this->getRegisterationFormText(1,$type);
        $displayData['leadSubmitAction'] = 'javascript:showLeadSubmitStatus(request.responseText);';
        $displayData['details']['contact_email']  = $toEmail;

        $this->load->view('listing/leadWidget',$displayData);
    }

    function trackViewContactDetail(){
        error_log_shiksha("CONTACTDETAILSVIEWTRACKING");
        $this->init();
        $listingInfo = getListingTypeAndId();
        $signedInUser = $this->userStatus;
        $email = explode('|',$signedInUser[0]['cookiestr']);
        $addReqInfo['listing_type'] = $listingInfo['type'];
        $addReqInfo['listing_type_id'] = $listingInfo['typeId'];
        $addReqInfo['displayName'] = $signedInUser[0]['displayname'];
        $addReqInfo['contact_cell'] = $signedInUser[0]['mobile'];
        $addReqInfo['userId'] = $signedInUser[0]['userid'];
        $addReqInfo['contact_email'] = $email[0];
        $addReqInfo['action'] = "contactinfo";
        $addReqInfo['userInfo'] = json_encode($signedInUser);
        $addReqInfo['sendMail'] = true;
        $LmsClientObj = new LmsLib();
        $addLeadStatus = $LmsClientObj->insertLead(1,$addReqInfo);

        error_log("BC".print_r($addLeadStatus,true));
        echo 1;
    }

    function sendResponseMail($data){
        $validateuser = $this->userStatus;
        $mail_client = new Alerts_client();
        $subject = "Shiksha.com member's query for ".$data['listingTitle'];
        $cook = explode('|',$validateuser[0]['cookiestr']);
        $data['usernameemail'] = (strlen($data['usernameemail']) > 0) ? $data['usernameemail'] : $cook[0];
        $data['nameOfUser'] = (strlen($data['nameOfUser'])>0) ? $data['nameOfUser']: $validateuser[0]['firstname'];
        $data['mobile'] = (strlen($data['mobile'])>0) ? $data['mobile'] : $validateuser[0]['mobile'];
        $data['educationLevel'] = $validateuser[0]['educationLevel']." ".$validateuser[0]['degree'];
        $data['educationInterest'] = $validateuser[0]['catofinterest'];
        $data['age'] = date('Y') - date('Y',strtotime($validateuser[0]['DOB']));
        $data['curDate'] = date('j F');
        $data['usercity'] = $validateuser[0]['cityname'];
        $content = $this->load->view('listing/responseMail',$data,true);
        $ccmail = 'response@shiksha.com';
        if(strlen($data['tomail'])>0){
            $tomails =explode(",",$data['tomail']);
            error_log_shiksha(print_r($data,true));
            error_log_shiksha($content);
            for($i = 0; $i < count($tomails); $i++){
                $response=$mail_client->externalQueueAdd("12",ADMIN_EMAIL,$tomails[$i],$subject,$content,$contentType="html");
            }
        }
        return $response;
    }

    function reportAbuse(){
        $this->init();
        $appId = 1;
        $validateuser = $this->userStatus;
        if(isset($validateuser[0])){
            $ListingClientObj = new Listing_client();
            $type_id =	$this->input->post('type_id',true);
            $listing_type = 	$this->input->post('listing_type',true);
            error_log_shiksha("CONTROLLER getCityList APP ID=> $appId :: $type_id  $listing_type");
            $reportedAbuseStatus = $ListingClientObj->reportAbuse($appId,$type_id,$listing_type);
            echo $reportedAbuseStatus['QueryStatus'];
        }else{
            echo "Invalid Call";
        }
    }


    function getJoinGroupInfo(){
        $this->init();
        $appId = 1;
        $ListingClientObj = new Listing_client();
        $instituteId = $this->uri->segment(4);
        error_log_shiksha("getjoingroup");
        $joinGroupInfo = $ListingClientObj->getJoinGroupInfo($appId,$instituteId);
			$network = array('results' =>$joinGroupInfo);
			if(is_array($joinGroupInfo) && count($joinGroupInfo))
				echo json_encode($network);
			else
				echo "";
    }

    function deleteListing(){
    	$this->init();
    	$appId = 1;
    	$validateuser = $this->userStatus;
    	$ListingClientObj = new Listing_client();
    	$type_id = $this->input->post('type_id',true);
    	$listing_type = $this->input->post('listing_type',true);
    	$course[0]['type'] =$listing_type;
    	$course[0]['typeId'] =$type_id;
	$user_id = $validateuser[0]['userid'];

    	//Check for Online form. If the institute/course contains an Online form, we should not allow it to be deleted.
    	$this->load->library('Online_form_client');
    	$onlineClient = new Online_form_client();
    	$listingHasOnlineForm = $onlineClient->checkIfListingHasOnlineForm($listing_type,$type_id);
    	if($listingHasOnlineForm){
    		echo 'OnlineFormAvailable';
    		return 1;
    	}

    	$listingDetails = $ListingClientObj->getMetaInfo($appID,$course,'"draft","live"');
    	if($validateuser[0]['usergroup'] == "cms")
    	{
    		$inst_id = "";
    		if($listing_type == 'course') {
    			$this->load->library('listing_client');
    			$ListingObj = new Listing_client();
    			$inst_id = $ListingObj->getInstituteIdForCourseId(1,$type_id);
    		}
    		$deleteStatus = $ListingClientObj->delete_listing($appId, $type_id, $listing_type, $user_id);
    		// delete cache
    		$this->load->library('categoryList/CacheUtilityLib');
    		$cacheUtilityLib = new CacheUtilityLib;
			$this->load->library('listing/ListingCache');
			$listingCacheObj = new ListingCache();
    		if($listing_type == 'institute') {
    			$cacheUtilityLib->refreshCacheForInstitutes(array($type_id));
			$listingCacheObj->deleteInstitute($type_id);
    		} else if($listing_type == 'course') {
    			$this->load->library('listing/ListingProfileLib');
    			$this->listingprofilelib->updateProfileCompletion($inst_id);
    			$cacheUtilityLib->refreshCacheForCourses(array($type_id));
			$listingCacheObj->deleteCourse($type_id);
    		}

		// update popular institute	
                $this->load->model('institutemodel');	
		$this->load->library('cacheLib');		
		$this->institutemodel->updatePopularInstitute(array($type_id),$listing_type);
		$key=md5('getInstituteForTabs');
		$this->cachelib->clearCache($key);

    		echo $deleteStatus['QueryStatus'];
    	}else{
    		echo "Invalid Call";
    	}
    }
	
    function deleteMultipleCourses(){
        $this->init();
        $appId = 1;
        $validateuser = $this->userStatus;
        $ListingClientObj = new Listing_client();
        $courses = explode("|",$this->input->post('courses',true));
        $institute = $this->input->post('institute',true);
	$user_id = $validateuser[0]['userid'];

		foreach($courses as $c){
			//Check for Online form. If the course contains an Online form, we should now allow it to be deleted.
			$this->load->library('Online_form_client');
			$onlineClient = new Online_form_client();
			$listingHasOnlineForm = $onlineClient->checkIfListingHasOnlineForm('course',$c);
			if($listingHasOnlineForm){
			      echo "The course Id: $c has an Online form attached to it and cannot be deleted.";
			      return 1;
			}
		}
		$this->load->library('listing/cache/ListingCache');	
		$listingCacheObj = new ListingCache();
		foreach($courses as $c){
			$course[0]['type'] ='course';
			$course[0]['typeId'] =$c;

			$listingDetails = $ListingClientObj->getMetaInfo($appID,$course,'"draft","live"');
			if( ($validateuser[0]['usergroup'] == "cms"))
			{
				$ListingClientObj->delete_listing($appId,$c,'course', $user_id); 
				$listingCacheObj->deleteCourse($c);
			} else {
				echo "Invalid Call";
                                return;
                        }
			
		}
        if($institute >0) {

        	$this->load->library('listing/ListingProfileLib');
    		$this->listingprofilelib->updateProfileCompletion($institute);
        }

	$this->load->model('institutemodel');	
	$this->load->library('cacheLib');
	// update popular institute	                      	
	$this->institutemodel->updatePopularInstitute($courses,'course');
	$key=md5('getInstituteForTabs');
	$this->cachelib->clearCache($key);

	echo "Courses Deleted. Press OK to continue..";
    }


    function askQuery() {
        $appId = 1;
        $this->init();
        $ListingClientObj = new Listing_client();
        $mb_client = new  Message_board_client();
        $askQuery = array();
        if(is_array($this->userStatus)){
            $signedInUser = $this->userStatus;
            $email = explode('|',$signedInUser[0]['cookiestr']);

            $askQuery['listing_type'] = $this->input->post('listing_type',true);
            $askQuery['listing_type_id'] = $this->input->post('listing_type_id',true);
            $askQuery['listing_type_id'] = $this->input->post('listing_type_id',true);
            $threadId = $this->input->post('threadId',true);
            $parentId = $this->input->post('threadId',true);
            $boardId = $this->input->post('categoryId',true);
            $msgTxt = $this->input->post('queryContent',true);
            $requestIp = S_REMOTE_ADDR;
            error_log_shiksha("$appId   $boardId   $signedInUser");
            $mbReplyResponse = $mb_client-> postReply($appId,$boardId,$signedInUser[0]['userid'],$msgTxt,'',$threadId,$parentId,$requestIp,'1');
            error_log_shiksha(print_r($mbReplyResponse,true));
            echo $mbReplyResponse['MsgID'];
        }
    }

    function reportChange(){
        $appId = 1;
        $this->init();
        $ListingClientObj = new Listing_client();
        $changeInfo = array();
        $changeInfo['listing_type'] = $this->input->post('listing_type',true);
        $changeInfo['listing_type_id'] = $this->input->post('listing_type_id',true);
        $changeInfo['contact_cell'] = $this->input->post('repChangePhone',true);
        $changeInfo['comment'] =  $this->input->post('comment',true);
        $secCode = $this->input->post('repChange1');
        if(verifyCaptcha('repchange',$secCode,1))
        {
            if(is_array($this->userStatus)){
                $signedInUser = $this->userStatus;
                $changeInfo['name'] = $signedInUser[0]['displayname'];
                $changeInfo['userId'] = $signedInUser[0]['userid'];
                $email = explode('|',$signedInUser[0]['cookiestr']);
                $changeInfo['email'] = $email[0];
            }else{
                $changeInfo['name'] =  $this->input->post('repChangeName',true);
                $changeInfo['userId'] = "-1";
                $changeInfo['email'] = $this->input->post('repChangeEmail',true);
            }
            error_log_shiksha("requested arrary".print_r($changeInfo,true));
            $changeInfoStatus = $ListingClientObj->reportChanges(1,$changeInfo);
            //mail to admin
            $mail_client = new Alerts_client();
            $subject = "Listing Changes - ".$changeInfo['listing_type']." - ".$changeInfo['listing_type_id'];
            $content = $changeInfo['comment']. "  By ".$changeInfo['email'];
            $response=$mail_client->externalQueueAdd("1",ADMIN_EMAIL,ADMIN_EMAIL,$subject,$content,$contentType="text");

            echo json_encode($changeInfoStatus);
        }
        else
        {
            echo 'code';
        }
    }
    /*function requestInfo($seccodeKey = 'seccode2') {
        $appId = 1;
        $this->init();
        $ListingClientObj = new Listing_client();
        $LmsClientObj = new LmsLib();
        $register_client = new Register_client();
        $addReqInfo = array();
        if(is_array($this->userStatus)){
            $secCode = $this->input->post('securityCode1');
            if(verifyCaptcha($seccodeKey,$secCode,1))
            {
                
                $signedInUser = $this->userStatus;
                $email = explode('|',$signedInUser[0]['cookiestr']);

                $addReqInfo['listing_type'] = $this->input->post('listing_type',true);
                $addReqInfo['listing_title'] = $this->input->post('listing_title',true);
                $addReqInfo['listing_type_id'] = $this->input->post('listing_type_id',true);
                $addReqInfo['displayName'] = $this->input->post('reqInfoDispName',true);
                $addReqInfo['contact_cell'] = $this->input->post('reqInfoPhNumber',true);
                $addReqInfo['message'] = $this->input->post('queryContent',true);
                $profaneWord = isProfane($addReqInfo['message']);
                if(strlen($profaneWord)> 0 ){
                    echo "profane";
                    exit;
                }
                if($addReqInfo['contact_cell'] !=  $signedInUser[0]['mobile']){
                    $updatedStatus = $register_client->updateUserAttribute($appId,$signedInUser[0]['userid'],'mobile',$addReqInfo['contact_cell']);
                }
                $addReqInfo['userId'] = $signedInUser[0]['userid'];
                $addReqInfo['contact_email'] = $this->input->post('reqInfoEmail',true);
                $reqInfoStatus = $ListingClientObj->add_requestInfo(1,$addReqInfo);
                $addReqInfo['action'] = "requestinfo";
                $addReqInfo['userInfo'] = json_encode($signedInUser);
                $addReqInfo['sendMail'] = true;
                $addLeadStatus = $LmsClientObj->insertLead(1,$addReqInfo);
                $tomail = mdecrypt($this->input->post('mailtoNew'));
                $isPaid =  $this->input->post('isPaid');
                if(strtolower($isPaid) != "yes"){
                    $tomail ='';
                }
                if(strtolower($isPaid) == "yes" && strlen($tomail)> 0){
                    $mailSendStatus = $this->sendResponseMail(array('listingTitle'=>$addReqInfo['listing_title'],'tomail'=>$tomail,'usernameemail'=>$addReqInfo['contact_email'],'nameOfUser'=>$addReqInfo['displayName'],'mobile'=>$addReqInfo['contact_cell'],'query'=>$addReqInfo['message']));
                }
                if($this->input->post('isPaid',true) == 'yes'){
                    $this->load->view('listing/requestInfo_after');
                }
                else{
                    echo 1;
                }
            }
            else
            {
            echo 'code';
            }
        }
        else{
            $queryMessage = $this->input->post('queryContent',true);
            $profaneWord = isProfane($queryMessage);
            if(strlen($profaneWord)> 0 ){
                echo "profane";
                exit;
            }

            $appId = 1;
            $displayname = $this->input->post('reqInfoDispName');
            $firstName = $this->input->post('reqInfoDispName');
            $responseCheckAvail = $register_client->checkAvailability($appId,$displayname,'displayname');
            while($responseCheckAvail == 1){
                error_log_shiksha($displayname."    ". $responseCheckAvail.'hereiam');
                $displayname = $this->input->post('reqInfoDispName') . rand(1,100000);
                $responseCheckAvail = $register_client->checkAvailability($appId,$displayname,'displayname');
            }
            $email = $this->input->post('reqInfoEmail');
            $password = $this->input->post('reqInfoPassword');
            $mdpassword = sha256($password);
            $userdetail = '';
            $country = $this->input->post('countryofresidence1');
            $city = $this->input->post('citiesofresidence1');
            $mobile = $this->input->post('reqInfoPhNumber');
		    $sourceurl = $this->input->post('sourcereferer');
    		$resolution = $this->input->post('sourceresolution');
    		$sourcename = $this->input->post('sourceproductname');
            $viamobile = 1;
            $viamail = 1;
            $newsletteremail =1;
            $profession = '';
            $educationLevel = $this->input->post('highesteducationlevel');
            if($educationLevel == "School")
            {
                $userstatus = "School";
                $educationLevel = "School";
            }
            else
                $userstatus = "College";
            $experience = 0;
            $categories = $this->input->post('board_id');
            $gender = $this->input->post('reqgender');
            $appID = 1;
            $yearOfBirth = $this->input->post('YOB');
            $userarray['appId'] = 1;
            $userarray['email'] = $email;
            $userarray['password'] = $password;
            $userarray['mdpassword'] = $mdpassword;
            $userarray['displayname'] = $displayname;
            $userarray['country'] = $country;
			$userarray['sourceurl'] = $sourceurl;
			$userarray['sourcename'] = $sourcename;
			$userarray['resolution'] = $resolution;
            $userarray['city'] = $city;
            $userarray['age'] = $yearOfBirth;
            $userarray['mobile'] = $mobile;
            $userarray['educationLevel'] = $educationLevel;
            $userarray['youare'] = $userstatus;
            $userarray['usergroup'] = 'requestinfouser';
            $userarray['firstname'] = $firstName;
            $userarray['gender'] = $gender;
            $userarray['categories'] = $categories;
            $userarray['quicksignupFlag'] = "requestinfouser";
            $secCode = $this->input->post('securityCode1');
            if(verifyCaptcha('seccode2',$secCode,1))
            {
                
                $addResult = $register_client->adduser_new($userarray);
                if($addResult['status'] > 0)
                {
                    $value = $email.'|'.$mdpassword;
                    $this->cookie($value);
                    $signedInUser = $this->userStatus;
                    $addReqInfo = array();
                    $addReqInfo['listing_type'] = $this->input->post('listing_type',true);
                    $addReqInfo['listing_title'] = $this->input->post('listing_title',true);
                    $addReqInfo['listing_type_id'] = $this->input->post('listing_type_id',true);
                    $addReqInfo['displayName'] = $displayname;
                    $addReqInfo['contact_cell'] = $mobile;
                    $addReqInfo['message'] = $this->input->post('queryContent',true);
                    $profaneWord = isProfane($addReqInfo['message']);
                    if(strlen($profaneWord)> 0 ){
                        echo "profane";
                        exit;
                    }

                    $addReqInfo['userId'] = $addResult['status'];
                    $addReqInfo['contact_email'] = $email;
                    error_log_shiksha(print_r($addReqInfo,true));
                    $reqInfoStatus = $ListingClientObj->add_requestInfo(1,$addReqInfo);
                    $addReqInfo['action'] = "requestinfo";
                    $addReqInfo['userInfo'] = json_encode($signedInUser);
                    $addReqInfo['sendMail'] = true;
                    $addLeadStatus = $LmsClientObj->insertLead(1,$addReqInfo);
                    $tomail = mdecrypt($this->input->post('mailtoNew'));
                    error_log_shiksha("mcrypt ".$tomail);
                    $isPaid =  $this->input->post('isPaid');
                    if(strtolower($isPaid) != "yes"){
                        $tomail ='';
                    }
                    if(strtolower($isPaid) == "yes" && strlen($tomail)> 0){
                        $mailSendStatus = $this->sendResponseMail(array('listingTitle'=>$addReqInfo['listing_title'],'tomail'=>$tomail,'query'=>$addReqInfo['message']));
                    }
                    error_log_shiksha(print_r($addLeadStatus,true));
                    switch($addReqInfo['listing_type']) {
                        case 'institute':
                        case 'course':
                            $productId = 1;
                            break;
                        case 'scholarship':
                            $productId = 2;
                            break;
                        case 'notification':
                            $productId = 3;
                            break;
                    }
                    $frequency = 'daily';
                    $alertType = 'byCategory';
                    $mail = 'on';
                    $sms = 'on';
                    $im = 'on';
                    $state = 'on';

                    if($this->input->post('isPaid',true) == 'yes'){
                        $this->load->view('listing/requestInfo_after');
                    }
                    else{
                        $alertClient = new Alerts_client();
                        $creationRes = $alertClient->createWidgetAlert($appId,$addResult['status'],$productId,$alertType,$categories,'','');
                        error_log_shiksha(print_r($creationRes,true));
                        echo 1;
                    }

                    $mail_client = new Alerts_client();
                    $subject = "Your Shiksha Account has been generated";
                    $data['usernameemail'] = $email;
                    $data['userpasswordemail'] = $password;
                    $data['listingType'] = $addReqInfo['listing_type'];
                    $data['listingTitle'] = $addReqInfo['listing_title'];
                    $data['listingUrl'] = $this->input->post('listing_url',true);
                    $content = $this->load->view('common/leadRegistrationMail',$data,true);
                    $response=$mail_client->externalQueueAdd("12",ADMIN_EMAIL,$email,$subject,$content,$contentType="html");

					$this->load->library('Mail_client');
					$mail_client = new Mail_client();
					$receiverIds = array();
					array_push($receiverIds,$addResult['status']);
					$subject = "Congrats! You are a Shiksha member now";
					$body = $content;
					$sendmail = $mail_client->send($appId,1,$receiverIds,$subject,$body,0);
                }
                else{
                    echo 'false';
                }
            }
            else
            {
            echo 'code';
            }
        }
    }*/

    function cookie($value)
    {
        $value1 = $value . '|pendingverification';
        setcookie('user',$value1,0,'/',COOKIEDOMAIN);
        error_log_shiksha("SENDMAILS1".$value);
        $this->userStatus = $this->checkUserValidation($value);
        error_log_shiksha("SENDMAILS5".print_r($this->userStatus,true));
    }

    function getDetailsForListing($type_id, $listing_type) {
		
	if(!is_numeric($type_id)) {
		    show_404();
	}
	
	if($listing_type == "institute") {
        $this->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder    = new InstituteBuilder();
        $this->instituteRepo = $instituteBuilder->getInstituteRepository(); 
        $instituteObj        = $this->instituteRepo->find($type_id,array('basic'));

        $this->instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');
        $this->instituteDetailLib->_checkForCommonRedirections($instituteObj, $typeId, $type);
        /*$instituteRepository = $listingBuilder->getInstituteRepository();
        $institute = $instituteRepository->find($type_id);
        $institute->getId() == "" ? show_404() : redirect($institute->getUrl(), 'location', 301);*/
	} else {
	    $this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $this->load->library('nationalCourse/CourseDetailLib');
        $this->courseDetailLib = new CourseDetailLib; 
        $this->courseRepo = $courseBuilder->getCourseRepository();
        $courseObj = $this->courseRepo->find($type_id);
        $this->courseDetailLib->checkForCommonRedirections($courseObj, $type_id);
     //    $courseRepository = $listingBuilder->getCourseRepository();
	    // $course = $courseRepository->find($type_id);
	    // $course->getId() == "" ? show_404() : redirect($course->getUrl(), 'location', 301);
	}
	exit();
    }

    function getRegisterationFormText($packType,$listing_type){
        error_log_shiksha("packtype $packType $listing_type");
        $retTextArr = array();
        $packType = strtolower($packType);
        if($packType <= 0 || $packType  == 7){
            $retTextArr['heading'] = '<div style="line-height:30px;">Join Shiksha for Free</div>';
            $retTextArr['paid'] = 'no';
            $retTextArr['descText'] = "Fill details below to receive email and mobile alerts about similar information";
        }
        else{
            //$retTextArr['heading'] = '<div width="100%" class="txt_align_l"><img src="/public/images/request_Free_Info.gif" width="57" height="36" align="absmiddle" />Request Free Info</div>';
            $retTextArr['heading'] = '<div width="100%" class="txt_align_c" style="line-height:30px;">Want More Information ?</div>';
            $retTextArr['paid'] = 'yes';
            //$retTextArr['descText'] = 'Enter your information below and our representative will contact you:';
            $retTextArr['descText'] = 'Just fill up the form and our representatives will get back to you.';
        }
        return $retTextArr;
    }

    function getCitiesWithCollege($countryId){
        $appId = 1;
        $this->init();
        $ListingClientObj = new Listing_client();
        $cityList = $ListingClientObj->getCitiesWithCollege($appId,$countryId);
        echo json_encode($cityList);
    }



    function mylisting($appId = 1, $country_id =1, $category_id =1, $username ="Puneet") {
        $this->init();
        $this->load->library('category_list_client');
        $validity = $this->checkUserValidation();
        //error_log_shiksha($validity);
        global $logged;
        global $userid;
        error_log_shiksha(print_r($validity,true));
        if(($validity == "false" )||($validity == "")) {
            $logged = "No";
            error_log_shiksha($logged);
            $currentUrl = site_url('listing/Listing/mylisting');
            redirect('user/Login/userlogin/'.base64_encode($currentUrl),'location');
        }else {
            $logged = "Yes";
            $userid = $validity[0]['userid'];
        }
        $username = $userid;
        $displayData = array();
        $categoryClient = new Category_list_client();
        $countryList = $categoryClient->getCountries($appId);
        $categoryList = $categoryClient->getCategoryTree($appId);
        $this->getCategoryTreeArray($category, $categoryList,0,'Root');
        $displayData['categoryList'] = $categoryList;
        $categoryTree = json_encode($category);
        $displayData['country_id'] = $country_id;
        $displayData['category_id'] = $category_id;
        $displayData['category_tree'] = $categoryTree;
        $displayData['country_list'] = $countryList;
        $displayData['validateuser'] = $this->userStatus;
        $displayData['thisUrl'] = $_SERVER['REQUEST_URI'];
        $displayData['usernameL'] = $userid;
        $displayData['myShiksha'] = 'false';
        $displayData['countOffset'] = 10;
        $displayData['thisUrl'] = $thisUrl;
        $displayData['validateuser'] = $this->userStatus;
        $displayData['mycourse'] = $this->getMyListings($appId,$category_id, $username, 'course',0,$displayData['countOffset']);

        $this->load->view('listing/myListingHome',$displayData);
    }

    private function getMyListings($appId,$categoryId, $username, $listingType, $startFrom,$countOffset) {
        $this->init();
        $ListingClientObj = new Listing_client();
        $filters = array();
        $filters['listing_type'] = $listingType;
        $filters['username'] = $username;
        $filters['start'] = $startFrom;
        $filters['number_of_results'] = $countOffset;
        $filters['saved'] = "true";
        error_log_shiksha(print_r($filters,true));

        $response = $ListingClientObj->getListingsByFilters($appId,$filters);
        $listingsList = array(
                'results' => $this->createListingsList($response[0]['listingsArr']),
                'totalCount'=> $response[0]['totalListings']
                );
        error_log_shiksha(print_r($listingsList,true));
        return json_encode($listingsList);
    }

    function getMyInstituteListings($appId,$categoryId, $username,  $startFrom =0, $countOffset=15) {
        error_log_shiksha("$appId,$categoryId,$username,$startFrom,$countOffset");
        echo $this->getMyListings($appId,$categoryId, $username, 'institute', $startFrom, $countOffset);
    }

    function getMyCourseListings($appId,$categoryId, $username,  $startFrom =0, $countOffset=15) {
        error_log_shiksha("$appId,$categoryId,$username,$startFrom,$countOffset");
        echo $this->getMyListings($appId,$categoryId, $username, 'course', $startFrom, $countOffset);
    }


    function getMyScholarshipListings($appId,$categoryId, $username,  $startFrom =0, $countOffset=15) {
        error_log_shiksha("$appId,$categoryId,$username,$startFrom,$countOffset");
        echo $this->getMyListings($appId,$categoryId, $username, 'scholarship', $startFrom, $countOffset);
    }

    function getMyExamListings($appId,$categoryId, $username,  $startFrom =0, $countOffset=15) {
        error_log_shiksha("$appId,$categoryId,$username,$startFrom,$countOffset");
        echo $this->getMyListings($appId,$categoryId, $username, 'notification', $startFrom, $countOffset);
    }

    function createListingsList($ratings) {
        error_log_shiksha(print_r($ratings,true));
        $ratingsList = array();
        $i = 0;
        if(is_array($ratings)){
            foreach($ratings as $rating) {
                $ratingsList[$i]['Listing'] = $rating;
                $ratingsList[$i]['Listing']['IUG'] = 100;
                $i++;
            }
        }
        return 	$ratingsList;
    }


    //Controller API to get Categories in a Tree Format (parent-child)
    function getCategoryTreeArray(& $returnArray, $categoryTree, $parentId, $parentCategoryName) {
        if(is_array($categoryTree)) {
            $i=0;
            foreach($categoryTree as $categoryLeaf) {
                if($categoryLeaf['parentId'] == $parentId) {
                    $returnArray[$parentCategoryName][$i++] =  $categoryLeaf['categoryID'] ."<=>". $categoryLeaf['categoryName'];
                    $this->getCategoryTreeArray($returnArray[$parentCategoryName], $categoryTree, $categoryLeaf['categoryID'],  $categoryLeaf['categoryName']);
                }
            }
        }
        return  $returnArray;
    }

    //Payment Check for Enterprise user
    function paymentCheck($userid){
    	$this->load->library('sums_product_client');
    	$objSumsProduct = new Sums_Product_client();
        $userProducts = $objSumsProduct->getProductsForUser(1,array('userId'=>$userid));
        //print_r($userProducts);
        $remaining = 0;
    	foreach ($userProducts as $product)
    	{
    		if ($product['BaseProdCategory']=="Listing")
    		{
				$remaining += $product['RemainingQuantity'];
    		}
    	}
    	if($remaining <= 0) {
            header ("location:/enterprise/Enterprise/prodAndServ");
    		exit();
    	}

    	return $userProducts;
    }


    function getFullPath($appId =1) {
        $this->init();
        $ListingClientObj = new Listing_client();

        $response = $ListingClientObj->getFullPath($appId);
        $leafCatArr = $this->getLeafPathIds($response);
        return $leafCatArr;
    }

    function getLeafPathIds($categoryPathArray) {
        $numOfLeaves = count($categoryPathArray);
        $resultArr = array();
        for($i = 0; $i < $numOfLeaves ; $i++){
            $resultArr[$i]['path'] = $categoryPathArray[$i]['name'];
            if(isset($categoryPathArray[$i][3]) && ($categoryPathArray[$i][3] != '')){
                $resultArr[$i]['id'] = $categoryPathArray[$i][3];
            }
            elseif($categoryPathArray[$i][2] != ''){
                $resultArr[$i]['id'] = $categoryPathArray[$i][2];
            }
        }
        return $resultArr;
    }

    function getOrAddInstitute(){
        $uploadClient = new Upload_client();
        $ListingClientObj = new Listing_client();

        if(($_REQUEST['city'] != -1 ) && ($_REQUEST['college'] != -1)){
            return $_REQUEST['college'];
        }
        elseif($_REQUEST['college'] == -1) {
            $institute_name = isset($_REQUEST['college_name_other'])? $_REQUEST['college_name_other']: "";
            $institute_desc = isset($_REQUEST['college_name_other_desc'])? $_REQUEST['college_name_other_desc']: "";
            $addInstituteData = array();
            $addInstituteData['institute_name'] = $institute_name;
            $addInstituteData['institute_desc'] = $institute_desc;
            $addInstituteData['islisting'] = false;
            $appId =1;
            $newInstitute = $ListingClientObj->add_institute($appId,$addInstituteData);
            $institute_id = $newInstitute['institute_id'];
            if(($_FILES['college_name_other_logo']))
            {
                $uploadRes = $uploadClient->uploadFile($appId,'image',$_FILES,array(),$institute_id,"institute","college_name_other_logo");
                if($uploadRes['status'] == 1){
                    error_log_shiksha("in upload success".print_r($uploadRes,true));
                    $reqArr = array();
                    $reqArr['mediaid']=$uploadRes[0]['mediaid'];
                    $reqArr['url']=$uploadRes[0]['imageurl'];
                    $logoLink = $uploadRes[0]['thumburl_m'];
                    $updateInstituteData = array();
                    $updateInstituteData['institute_id'] = $institute_id;
                    $updateInstituteData['logo_link'] = $logoLink;
                    $status = $ListingClientObj->update_institute($appId,$updateInstituteData);
                }
            }
            return $institute_id;
        }
    }


    function addCollegeCourse()
    {
        header("location:/enterprise/Enterprise/disallowedAccess");
        exit();
        //exit;
        error_log_shiksha("ADD COURSE_COLLEGE LISTING INSTITUTE : START OF FUNCTION");
        $this->load->library('sums_product_client');
        global $logged;
        global $userid;
        global $usergroup;
        $paymentInfo = array();
        $validity = $this->checkUserValidation();
        $userid = $validity[0]['userid'];
	$usergroup = $validity[0]['usergroup'];
        $onBehalfOf = $this->input->post('onBehalfOf',true);
        if ($onBehalfOf == "true")
        {
                $sumsUserId = $userid;
        	$userid = $this->input->post('clientId',true);
        }

        if($usergroup != "cms" || $onBehalfOf =="true"){
            $displayData['userProducts'] = $this->paymentCheck($userid);
        }

        global $userPack;
        $userPack = $this->input->post('userPack',true);

        if($usergroup != "cms" || $onBehalfOf =="true"){
        	//new product chk
        	$sumsProdObj = new Sums_Product_client();
        	$paymentInfo = $sumsProdObj->getProductsForUser(1,array('userId'=>$userid));
        	$packInfo =  $sumsProdObj->getProductFeatures(1,array('productId'=>$userPack));
        	if ($packInfo[$userPack]['BaseProdCategory']!="Listing") {
        		echo "Select Listings Pack";
        		header ("location:/enterprise/Enterprise/prodAndServ");
        		exit;
        	}
        }
        $this->init();
        $this->load->library('upload_client');
        $uploadClient = new Upload_client();
        $ListingClientObj = new Listing_client();

        $addType = $this->input->post('add_type',true);
        $bypass = $this->input->post('bypass',true);

        $productDataList =  $paymentInfo;

        error_log_shiksha("remaining quantity for selected pack: ".$productDataList[$userPack]['RemainingQuantity']);
        if( ($usergroup != "cms" || $onBehalfOf =="true") && ($productDataList[$userPack]['RemainingQuantity'] < 1)) {
            error_log_shiksha("Selected Packs' no. of listing check: Selected Pack is Consumed: Buy that pack first");
            header ("location:/enterprise/Enterprise/prodAndServ");
            exit();
        }

        $maxPhotos = 0;
        if (isset($packInfo[$userPack]['property']['Max_Photos'])) {
            $maxPhotos = $packInfo[$userPack]['property']['Max_Photos'];
        }

        $maxDocs = 0;
        if (isset($packInfo[$userPack]['property']['Max_Docs'])) {
            $maxDocs = $packInfo[$userPack]['property']['Max_Docs'];
        }

        $maxVideos = 0;
        if (isset($packInfo[$userPack]['property']['Max_Videos'])) {
            $maxVideos = $packInfo[$userPack]['property']['Max_Videos'];
        }

        $featuredLogo = "No";
        if (isset($packInfo[$userPack]['property']['Featured_Logo'])) {
            $featuredLogo = $packInfo[$userPack]['property']['Featured_Logo'];
        }

        $featuredPanel = "No";
        if (isset($packInfo[$userPack]['property']['Featured_Panel'])) {
            $featuredPanel = $packInfo[$userPack]['property']['Featured_Panel'];
        }

        if ($usergroup == "cms" && $onBehalfOf != "true") {
            $userPack = 0; // CMS fed listings to have pack_type 0
            $maxPhotos = 3;
            $maxVideos = 3;
            $maxDocs = 3;
            $featuredLogo = "YES";
            $featuredPanel = "YES";
        }

        $countries = $this->input->post('i_country_id');
        $cities = $this->input->post('i_city_id');
        $address = $this->input->post('address');
        $count = count($countries);
        for($i=0;$i<$count;$i++)
        {
            $instituteData['country_id'.$i]= $countries[$i];
            if ($cities[$i]==-1){
                $requestArray = array();
                $requestArray['country_id'] = $countries[$i];
                $requestArray['city_name'] = $this->input->post('cities'.($i+1).'_other');
                $cities[$i] = $ListingClientObj->insertCity($appId,$requestArray);
            }
            $instituteData['city_id'.$i] = $cities[$i];
            $instituteData['address'.$i] = $address[$i];
        }
        $instituteData['numoflocations'] = $count;
        $instituteData['packType'] = $userPack;
        $instituteData['institute_name'] = $this->input->post('c_institute_name',true);
        $instituteData['institute_desc'] = $this->input->post('c_institute_desc',true);
        $instituteData['affiliated_to'] = $this->input->post('affiliated_to',true);
        $instituteData['establish_year'] = $this->input->post('i_establish_year');
        $instituteData['no_of_students'] = $this->input->post('i_no_of_students',true);
        $instituteData['no_of_int_students'] = $this->input->post('i_no_of_i_students',true);
        $instituteData['contact_name'] = $this->input->post('c_cordinator_name',true);
        $instituteData['contact_cell'] = $this->input->post('c_cordinator_no',true);
        $instituteData['contact_email'] = $this->input->post('c_cordinator_email',true);
        $instituteData['url'] = $this->input->post('c_website',true);
        $instituteData['category_id'] = implode(',',$this->input->post('c_categories',true));
        $j = 0;
        for($i=0;$i< count($_REQUEST['i_country_id']);$i++)
        {
            $instituteData['location'][$j] = $_REQUEST['i_country_id'][$i];
            $instituteData['location'][$j+1] = $_REQUEST['i_city_id'][$i];
            $j +=2;
        }
        $instituteData['country_id'] =implode(',',$countries);
        $instituteData['city_id'] =implode(',',$cities);

        $instituteData['username'] = $userid;
        $instituteData['islisting']=true;
        $flag_duplicate_institute = false;
        if ($addType == "new" || $this->input->post('bypass'))
        {
            $msgbrdClient = new message_board_client();
            $selectedCategory = $this->input->post('c_categories');
            $countryId = explode(',',$instituteData['country_id']);
            $msgTxt = "You can discuss about ".$instituteData['institute_name']." here.";
            $topicResult = $msgbrdClient->addTopic($appId,1,$msgTxt,1,S_REMOTE_ADDR,'group');
            $instituteData['threadId']= $topicResult['ThreadID'];
            $instituteData['dataFromCMS'] = $this->input->post('dataFromCMS',true);

            if ($usergroup == "cms") {
                $instituteData['hiddenTags'] = $this->input->post('i_tags',true);
            }
            $instituteData['requestIP'] = S_REMOTE_ADDR;
            $response = $ListingClientObj->add_institute($appId,$instituteData);
            $flag_duplicate_institute = false;
            if (isset($response['duplicate']) && $response['duplicate']==1 )
            {
                $flag_duplicate_institute = true;
                $displayData['duplicate']['institute'] = $response;
                $tempRes = $msgbrdClient->deleteTopicFromCMS(1,$topicResult['ThreadID']);
            }

            // The product is actually NOT consumed, just to get the Log of this and setting expiry date of the NEW institute
            if ($usergroup != "cms" || $onBehalfOf =="true") {
                if ($flag_duplicate_institute==false) {
                    $prodData['clientUserId'] = $userid;
                    if(isset($sumsUserId)){
                        $prodData['sumsUserId']=$sumsUserId;
                    }
                    $prodData['baseProdId'] = $userPack;
                    $prodData['consumedTypeId'] = $response['institute_id'];
                    $prodData['consumedType'] = "institute";
                    $sumsProdObj = new Sums_Product_client();
                    $upResponse = $sumsProdObj->productConsume(1,$prodData);
                }
            }
        }

        if (($addType == "new" || $this->input->post('bypass')) && $flag_duplicate_institute!==true) {
            if($featuredLogo == "YES") {
                $arrCaption = $this->input->post('c_insti_logo_caption');
                $inst_logo= array();
                for($i=0;$i<count($_FILES['i_insti_logo']['name']);$i++){
                    $inst_logo[$i] = ($arrCaption[$i]!="")?$arrCaption[$i]:$_FILES['i_insti_logo']['name'][$i];
                }
                if(isset($_FILES['i_insti_logo']['tmp_name'][0]) && ($_FILES['i_insti_logo']['tmp_name'][0] != ''))
                {
                    $i_upload_logo = $uploadClient->uploadFile($appId,'image',$_FILES,$inst_logo,$response['institute_id'],"institute",'i_insti_logo');
                    if($i_upload_logo['status'] == 1)
                    {
                        for($k = 0;$k < $i_upload_logo['max'] ; $k++)
                        {
                            $reqArr = array();
                            $reqArr['mediaid']=$i_upload_logo[$k]['mediaid'];
                            $reqArr['url']=$i_upload_logo[$k]['imageurl'];
                            $reqArr['title']=$i_upload_logo[$k]['title'];
                            $reqArr['thumburl']=$i_upload_logo[$k]['thumburl_m'];
                            $updateInstituteData = array();
                            $updateInstituteData['institute_id'] = $response['institute_id'];
                            $updateInstituteData['logo_link'] = $i_upload_logo[0]['thumburl_m'];
                            $updateInstituteData['logo_name'] = $i_upload_logo[0]['title'];
                            $status = $ListingClientObj->update_institute($appId,$updateInstituteData);
                        }
                    }
                }
            }

            if($featuredPanel == "YES") {
                $arrCaption = $this->input->post('c_feat_panel_caption');
                $inst_logo= array();
                for($i=0;$i<count($_FILES['i_feat_panel']['name']);$i++){
                    $inst_logo[$i] = ($arrCaption[$i]!="")?$arrCaption[$i]:$_FILES['i_feat_panel']['name'][$i];
                }
                if(isset($_FILES['i_feat_panel']['tmp_name'][0]) && ($_FILES['i_feat_panel']['tmp_name'][0] != ''))
                {
                    $i_upload_logo = $uploadClient->uploadFile($appId,'image',$_FILES,$inst_logo,$response['institute_id'],"featured",'i_feat_panel');
                    if($i_upload_logo['status'] == 1)
                    {
                        for($k = 0;$k < $i_upload_logo['max'] ; $k++)
                        {
                            $reqArr = array();
                            $reqArr['mediaid']=$i_upload_logo[$k]['mediaid'];
                            $reqArr['url']=$i_upload_logo[$k]['imageurl'];
                            $reqArr['title']=$i_upload_logo[$k]['title'];
                            $reqArr['thumburl']=$i_upload_logo[$k]['thumburl_m'];
                            $updateInstituteData = array();
                            $updateInstituteData['institute_id'] = $response['institute_id'];
                            $updateInstituteData['featured_panel'] = $i_upload_logo[0]['thumburl_m'];
                            $updateInstituteData['featured_panel_name'] = $i_upload_logo[0]['title'];
                            $status = $ListingClientObj->update_institute($appId,$updateInstituteData);
                        }
                    }
                }
            }
        }
        if ($addType =="new" || $this->input->post('bypass')) {
            $courseData['institute_id'] = $response['institute_id'];
            $courseData['country_id'] = implode(',',$this->input->post('i_country_id',true));
            $courseData['city_id']=implode(',',$this->input->post('i_city_id',true));
            $instituteName = $instituteData['institute_name'];
        }else {
            $courseData['institute_id'] = $this->input->post('existing_college');
            $queriedInstituteData =  $ListingClientObj->getListingDetails($appId,$courseData['institute_id'],'institute');
            foreach ($queriedInstituteData[0]['locations'] as $loc)
            {
                $strCountries .= $loc['country_id'].',';
                $strCities .= $loc['city_id'].',';
            }
            $courseData['country_id'] = substr($strCountries,0,strlen($strCountries)-1);
            $courseData['city_id'] = substr($strCities,0,strlen($strCities)-1);
            $instituteName = $queriedInstituteData[0]['title'];
        }
        $courseData['packType'] = $userPack;
        $courseData['courseTitle'] = $this->input->post('c_course_title',true);
        $courseData['courseType'] = $this->input->post('course_type',true);
        $courseData['courseLevel'] = $this->input->post('course_level',true);
        if ($courseData['courseLevel']=="Other")
        {
            $courseData['courseLevel'] = $this->input->post('other_course_level',true);
        }
        $courseData['overview'] = $this->input->post('c_overview',true);
        $duration1 = $this->input->post('c_duration1',true);
        $duration2 = $this->input->post('c_duration2',true);
        $courseData['duration'] = $duration1." ".$duration2;
        if ($usergroup == "cms") {
            $courseData['hiddenTags'] = $this->input->post('c_course_tags',true);
        }
        $courseData['startDate'] = $this->input->post('course_start_date',true);
        $courseData['endDate'] = $this->input->post('course_end_date',true);
        $courseData['contents'] = $this->input->post('c_contents',true);
        $courseData['category_id'] = implode(',',$this->input->post('c_categories',true));
        $courseData['eligibility'] = "";
        if ( $this->input->post('eligibility1')){
            $Data['minqual'] = $this->input->post('eligibility1',true);
            $courseData['eligibility'] .= " Min Qual:".$this->input->post('eligibility1',true);
        }
        if ( $this->input->post('c_elig_marks')){
            $Data['marks'] = $this->input->post('c_elig_marks',true);
            $courseData['eligibility'] .= " Marks:".$this->input->post('c_elig_marks',true);
        }
        if ( $this->input->post('eligibility3')){
            $Data['minexp'] = $this->input->post('eligibility3',true);
            $courseData['eligibility'] .= " Min Exp.:".$this->input->post('eligibility3',true);
        }
        if ( $this->input->post('eligibility4')){
            $Data['maxexp'] = $this->input->post('eligibility4',true);
            $courseData['eligibility'] .= " Max Exp.:".$this->input->post('eligibility4',true);
        }
        if ($this->input->post('chk_elig_others')) {
            $Data['others'] = $this->input->post('other_elig',true);
            $courseData['eligibility'] .= " ".$this->input->post('other_elig',true);
        }

        //TEST REQUIRED FOR COURSE
        $tests_required = $this->input->post('c_test',true);
        $testsArray = array();
        $j=0;
        $flag_test_required_other = 0;
        for ($i=0;$i<count($tests_required);$i++)
        {
            if ($tests_required[$i]!="-1")
            {
                $testsArray[$j] = $tests_required[$i];
                $j++;
            }
            else
            {
                $flag_test_required_other = 1;
            }
        }
        $courseData['tests_required'] = implode(",",$testsArray);
        if ($flag_test_required_other == 1)
        {
            $courseData['tests_required_other'] = 'true';
            $courseData['tests_required_exam_name'] =  $this->input->post('c_testOther');
        }

        //TEST PREP FOR COURSE
        $tests_prep = $this->input->post('examPrepRelatedExams',true);
        $testsprepArray = array();
        $j=0;
        $flag_test_prep_other = 0;
        for ($i=0;$i<count($tests_prep);$i++)
        {
            if ($tests_prep[$i]!="-1")
            {
                $testsprepArray[$j] = $tests_prep[$i];
                $j++;
            }
            else
            {
                $flag_test_prep_other = 1;
            }
        }
        $courseData['tests_preparation'] = implode(",",$tests_prep);
        if ($flag_test_prep_other == 1)
        {
            $courseData['tests_preparation_other'] = 'true';
            $courseData['tests_preparation_exam_name'] =  $this->input->post('examPrepRelatedExamsOther');
        }

        $courseData['selection_criteria'] = $this->input->post('c_selection_criteria',true);
        $courseData['invite_emails'] = $this->input->post('i_emailids',true);
        $courseData['placements'] = $this->input->post('c_placements',true);
        if($courseData['placements'] == "yes")
        {
            $courseData['placements'] = $this->input->post('c_placements_desc',true);
        }
        $courseData['scholarships'] = $this->input->post('c_scholarships',true);
        if($courseData['scholarships'] == "yes")
        {
            $courseData['scholarships'] = $this->input->post('c_schol_exams',true);
        }
        $courseData ['hostel_facility'] = $this->input->post('c_hostel_facility',true);
        $courseData['examSelected'] = array($this->input->post('examSelected', true),'struct');//Ashish

        $courseData['fees'] = "";
        if ($this->input->post('c_fees_amount'))
        {
            $fees1 = $this->input->post('c_fees_currency',true);
            $fees2 = $this->input->post('c_fees_amount',true);
            $courseData['fees'] = $fees1." ".$fees2;
        }
        if ($this->input->post('chk_fees_other')) {
            $courseData['fees'] .= ";".$this->input->post('c_fees_other',true);
        }
        $courseData['username'] = $userid;
        $media_content = $this->input->post('c_media_content');
        if (($addType =="new" || $this->input->post('bypass')) && $flag_duplicate_institute!==true){
            error_log_shiksha("ADD COURSE_COLLEGE LISTING : uploading media for institute ".$reponse['institute_id']);
            if($maxPhotos >0)
            {
                $courImgFlag = false;
                for($i = 0;$i < $maxPhotos ; $i++){
                    if(isset($_FILES['c_images']['tmp_name'][$i]) && ($_FILES['c_images']['tmp_name'][$i] != '')){
                        $courImgFlag = true;
                        break;
                    }else{
                        $courImgFlag = false;
                    }
                }

                if($courImgFlag)
                {
                    $arrCaption = $this->input->post('c_images_caption');
                    $photoCaption = array();
                    for($i=0;$i<count($_FILES['c_images']['name']);$i++){
                        $photoCaption[$i] = ($arrCaption[$i]!="")?$arrCaption[$i]:$_FILES['c_images']['name'][$i];
                    }

                    $upload_forms = $uploadClient->uploadFile($appId,'image',$_FILES,$photoCaption,$response['institute_id'], "institute",'c_images');
                    error_log_shiksha("ADD LISTING : upload response for images : ".print_r($upload_forms,true));
                    if($upload_forms['status'] == 1){
                        error_log_shiksha("in upload success".print_r($upload_forms,true));
                        for($k = 0;$k < $upload_forms['max'] ; $k++){
                            $reqArr = array();
                            $reqArr['mediaid']=$upload_forms[$k]['mediaid'];
                            $reqArr['url']=$upload_forms[$k]['imageurl'];
                            $reqArr['title']=$upload_forms[$k]['title'];
                            $reqArr['thumburl']=$upload_forms[$k]['thumburl_m'];
                            $updateCoursePhotos = $ListingClientObj->updateMediaContent($appId,$response['institute_id'],'institute','photos',$reqArr);
                        }
                        $numOfPhotos = $k;
                    }
                }
            }

            if($maxVideos >0)
            {
                $courVidFlag = false;
                for($i = 0;$i < $maxVideos; $i++){
                    if(isset($_FILES['c_videos']['tmp_name'][$i]) && ($_FILES['c_videos']['tmp_name'][$i] != '')){
                        $courVidFlag = true;
                        break;
                    }else{
                        $courVidFlag = false;
                    }
                }
                if($courVidFlag)
                {
                    $arrCaption = $this->input->post('c_videos_caption');
                    $videoCaption = array();
                    for($i=0;$i<count($_FILES['c_videos']['name']);$i++){
                        $videoCaption[$i] = ($arrCaption[$i]!="")?$arrCaption[$i]:$_FILES['c_videos']['name'][$i];
                    }
                    $upload_forms = $uploadClient->uploadFile($appId,'video',$_FILES,$videoCaption,$response['institute_id'], "institute",'c_videos');
                    if($upload_forms['status'] == 1){
                        for($k = 0;$k < $upload_forms['max'] ; $k++){
                            $reqArr = array();
                            $reqArr['mediaid']=$upload_forms[$k]['mediaid'];
                            $reqArr['url']=$upload_forms[$k]['imageurl'];
                            $reqArr['title']=$upload_forms[$k]['title'];
                            $reqArr['thumburl']=$upload_forms[$k]['thumburl'];
                            $updateCourseVideos = $ListingClientObj->updateMediaContent($appId,$response['institute_id'],'institute','videos',$reqArr);
                        }
                        $numOfVideos = $k;
                    }
                }
            }

            if($maxDocs >0)
            {
                $courDocFlag = false;
                for($i = 0;$i < $maxDocs; $i++){
                    if(isset($_FILES['c_docs']['tmp_name'][$i]) && ($_FILES['c_docs']['tmp_name'][$i] != '')){
                        $courDocFlag= true;
                        break;
                    }else{
                        $courDocFlag= false;
                    }
                }
                if($courDocFlag)
                {
                    $arrCaption = $this->input->post('c_docs_caption');
                    $docCaption = array();
                    for($i=0;$i<count($_FILES['c_docs']['name']);$i++){
                        $docCaption[$i] = ($arrCaption[$i]!="")?$arrCaption[$i]:$_FILES['c_docs']['name'][$i];
                    }

                    $upload_forms = $uploadClient->uploadFile($appId,'pdf',$_FILES,$docCaption,$response['institute_id'], "institute",'c_docs');
                    if($upload_forms['status'] == 1){
                        for($k = 0;$k < $upload_forms['max'] ; $k++){
                            $reqArr = array();
                            $reqArr['mediaid']=$upload_forms[$k]['mediaid'];
                            $reqArr['url']=$upload_forms[$k]['imageurl'];
                            $reqArr['title']=$upload_forms[$k]['title'];
                            $reqArr['thumburl']=$upload_forms[$k]['thumburl'];
                            $updateCourseDocs = $ListingClientObj->updateMediaContent($appId,$response['institute_id'],'institute','doc',$reqArr);
                        }
                        $numOfDocs = $k;
                    }
                }
            }
        }

        $courseData['dataFromCMS'] = $this->input->post('dataFromCMS',true);
        $courseData['requestIP'] = S_REMOTE_ADDR;
        $response = $ListingClientObj->add_course($appId,$courseData,$Data,$testsArray);
        error_log_shiksha("ADD COURSE_COLLEGE LISTING : after add_course RESPONSE ".print_r($response,true));

        $flag_duplicate_course = false;
        if (isset($response['duplicate']) && $response['duplicate']==1)
        {
            error_log_shiksha ("ADD COURSE_COLLEGE LISTING : duplicate course found");
            $flag_duplicate_course = true;
            $displayData['duplicate']['course'] = $response;
        }
        $response['title'] = $courseData['courseTitle'];

        //Indexing Institute Info
        if (($addType == "new" || $this->input->post('bypass')) && $flag_duplicate_institute!==true) {
            $ch = curl_init(); // initialize curl handle
            curl_setopt($ch, CURLOPT_URL, "http://".SHIKSHACLIENTIP."/ListingScripts/indexListing/".$courseData['institute_id']."/institute");

            curl_setopt($ch, CURLOPT_VERBOSE, 1); // set url to post to
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 40); // times out after 4s
            curl_setopt($ch, CURLOPT_POSTFIELDS, $content); // add POST fields
            curl_setopt($ch, CURLOPT_POST, 1);
            $result = curl_exec($ch); // run the whole process
            curl_close($ch);
        }


        if ($flag_duplicate_course!==true) {
            if($maxPhotos >0)
            {
                $courImgFlag = false;
                for($i = 0;$i < $maxPhotos ; $i++){
                    if(isset($_FILES['c_images']['tmp_name'][$i]) && ($_FILES['c_images']['tmp_name'][$i] != '')){
                        $courImgFlag = true;
                        break;
                    }else{
                        $courImgFlag = false;
                    }
                }

                if($courImgFlag)
                {
                    $arrCaption = $this->input->post('c_images_caption');
                    $photoCaption = array();
                    for($i=0;$i<count($_FILES['c_images']['name']);$i++){
                        $photoCaption[$i] = ($arrCaption[$i]!="")?$arrCaption[$i]:$_FILES['c_images']['name'][$i];
                    }

                    $upload_forms = $uploadClient->uploadFile($appId,'image',$_FILES,$photoCaption,$response['Course_id'], "course",'c_images');
                    if($upload_forms['status'] == 1){
                        error_log_shiksha("in upload success".print_r($upload_forms,true));
                        for($k = 0;$k < $upload_forms['max'] ; $k++){
                            $reqArr = array();
                            $reqArr['mediaid']=$upload_forms[$k]['mediaid'];
                            $reqArr['url']=$upload_forms[$k]['imageurl'];
                            $reqArr['title']=$upload_forms[$k]['title'];
                            $reqArr['thumburl']=$upload_forms[$k]['thumburl_m'];
                            $updateCoursePhotos = $ListingClientObj->updateMediaContent($appId,$response['Course_id'],'course','photos',$reqArr);
                        }
                        $numOfPhotos = $k;
                    }
                }
            }

            if($maxVideos >0)
            {
                $courVidFlag = false;
                for($i = 0;$i < $maxVideos; $i++){
                    if(isset($_FILES['c_videos']['tmp_name'][$i]) && ($_FILES['c_videos']['tmp_name'][$i] != '')){
                        $courVidFlag = true;
                        break;
                    }else{
                        $courVidFlag = false;
                    }
                }

                if($courVidFlag)
                {
                    $arrCaption = $this->input->post('c_videos_caption');
                    $videoCaption = array();
                    for($i=0;$i<count($_FILES['c_videos']['name']);$i++){
                        $videoCaption[$i] = ($arrCaption[$i]!="")?$arrCaption[$i]:$_FILES['c_videos']['name'][$i];
                    }

                    $upload_forms = $uploadClient->uploadFile($appId,'video',$_FILES,$videoCaption,$response['Course_id'], "course",'c_videos');

                    if($upload_forms['status'] == 1){
                        for($k = 0;$k < $upload_forms['max'] ; $k++){
                            $reqArr = array();
                            $reqArr['mediaid']=$upload_forms[$k]['mediaid'];
                            $reqArr['url']=$upload_forms[$k]['imageurl'];
                            $reqArr['title']=$upload_forms[$k]['title'];
                            $reqArr['thumburl']=$upload_forms[$k]['thumburl'];
                            $updateCourseVideos = $ListingClientObj->updateMediaContent($appId,$response['Course_id'],'course','videos',$reqArr);
                        }
                        $numOfVideos = $k;
                    }
                }
            }

            if($maxDocs >0)
            {
                $courDocFlag = false;
                for($i = 0;$i < $maxDocs; $i++){
                    if(isset($_FILES['c_docs']['tmp_name'][$i]) && ($_FILES['c_docs']['tmp_name'][$i] != '')){
                        $courDocFlag= true;
                        break;
                    }else{
                        $courDocFlag= false;
                    }
                }
                if($courDocFlag)
                {
                    $arrCaption = $this->input->post('c_docs_caption');
                    $docCaption = array();
                    for($i=0;$i<count($_FILES['c_docs']['name']);$i++){
                        $docCaption[$i] = ($arrCaption[$i]!="")?$arrCaption[$i]:$_FILES['c_docs']['name'][$i];
                    }

                    $upload_forms = $uploadClient->uploadFile($appId,'pdf',$_FILES,$docCaption,$response['Course_id'], "course",'c_docs');

                    if($upload_forms['status'] == 1){
                        for($k = 0;$k < $upload_forms['max'] ; $k++){
                            $reqArr = array();
                            $reqArr['mediaid']=$upload_forms[$k]['mediaid'];
                            $reqArr['url']=$upload_forms[$k]['imageurl'];
                            $reqArr['title']=$upload_forms[$k]['title'];
                            $reqArr['thumburl']=$upload_forms[$k]['thumburl'];
                            $updateCourseDocs = $ListingClientObj->updateMediaContent($appId,$response['Course_id'],'course','doc',$reqArr);
                        }

                        $numOfDocs = $k;
                    }
                }
            }
        }

        if ($flag_duplicate_course!==true) {
            //Indexing Course Info
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://".SHIKSHACLIENTIP."/ListingScripts/indexListing/".$response['Course_id']."/course");

            curl_setopt($ch, CURLOPT_VERBOSE, 1); // set url to post to
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 40); // times out after 4s
            curl_setopt($ch, CURLOPT_POSTFIELDS, $content); // add POST fields
            curl_setopt($ch, CURLOPT_POST, 1);
            $result = curl_exec($ch); // run the whole process
            curl_close($ch);
        }

        if ($usergroup != "cms" || $onBehalfOf =="true") {
            if ($flag_duplicate_course!==true) {
                $prodData['clientUserId'] = $userid;
                if(isset($sumsUserId)){
                    $prodData['sumsUserId']=$sumsUserId;
                }
                $prodData['baseProdId'] = $userPack;
                $prodData['consumedTypeId'] = $response['Course_id'];
                $prodData['consumedType'] = "course";
                $sumsProdObj = new Sums_Product_client();
                $upResponse = $sumsProdObj->productConsume(1,$prodData);
            }
        }else{
            $displayData['remaining'] = "Unlimited";
            $displayData['productname'] = "CMS";
        }

        $displayData['response'] = $response;
        $displayData['type'] = "course";
        $displayData['validateuser'] = $this->userStatus;
        $displayData['instituteName'] = $instituteName;
        $dataFromCMS = $this->input->post('dataFromCMS',true);
        $prodId = $this->input->post('prodId',true);
        if($usergroup == "user"){
            $this->load->view('listing/resultPage',$displayData);
        }else{
            $displayData['prodId'] = $prodId;
            if($courseData['contact_email'] != ''){
                $email = $courseData['contact_email'];
                $fromMail = "enterprise@shiksha.com";
                $ccmail = "sales@shiksha.com";
                $type_id = $response['Course_id'];
                $mail_client = new Alerts_client();
                $subject = "Your Listing on Shiksha.com, Listing Id- course-".$type_id;
                $data['validateuser'] = $validity;
                $data['listingType'] = "course";
                $data['listingTitle'] = $courseData['courseTitle'];
                $data['listingUrl'] = "http://www.shiksha.com/getListingDetail/".$type_id."/course/course-".seo_url($courseData['courseTitle'],"-",20)."-college-".seo_url($displayData['instituteName'],"-",20);
                $content = $this->load->view('common/mailTBS',$data,true);
                $response=$mail_client->externalQueueAdd("12",$fromMail,$email,$subject,$content,$contentType="html",$ccmail);
            //    print_r($response);
            }
            $this->load->library('enterprise_client');
            $entObj = new Enterprise_client();
            $displayData['headerTabs'] = $entObj->getHeaderTabs(1,$usergroup,$validity[0]['userid']);
            $this->load->view('enterprise/resultPageCMS',$displayData);
        }
    }


    function addScholListingNew($appId = 1) {
        header("location:/enterprise/Enterprise/disallowedAccess");
        exit();
        error_log_shiksha("ADD SCHOLARSHIP LISTING : SCHOLARSHIP START");
        error_log_shiksha("ADD SCHOLARSHIP LISTING : data received ".print_r($_POST,true));
        $this->load->library('sums_product_client');
        global $logged;
        global $userid;
        global $usergroup;
        $paymentInfo = array();
        $validity = $this->checkUserValidation();
        $userid = $validity[0]['userid'];
        $usergroup = $validity[0]['usergroup'];
        $onBehalfOf = $this->input->post('onBehalfOf',true);
        if ($onBehalfOf == "true")
        {
            $sumsUserId = $userid;
            $userid = $this->input->post('clientId',true);
        }

        if ($usergroup != "cms" || $onBehalfOf =="true") {
            $displayData['userProducts'] = $this->paymentCheck($userid);
        }

        global $userPack;
        $userPack = $this->input->post('userPack',true);

        $this->init();

        //new product chk
        if ($usergroup != "cms" || $onBehalfOf =="true") {
            $sumsProdObj = new Sums_Product_client();
            $paymentInfo = $sumsProdObj->getProductsForUser(1,array('userId'=>$userid));
            $packInfo =  $sumsProdObj->getProductFeatures(1,array('productId'=>$userPack));
            if ($packInfo[$userPack]['BaseProdCategory']!="Listing") {
                echo "Select Listings Pack";
                header ("location:/enterprise/Enterprise/prodAndServ");
                exit;
            }
        }

        $productDataList =  $paymentInfo;

        error_log_shiksha("remaining quantity for selected pack: ".$productDataList[$userPack]['RemainingQuantity']);
        if( ($usergroup != "cms" || $onBehalfOf =="true") && ($productDataList[$userPack]['RemainingQuantity'] < 1)) {
            error_log_shiksha("Selected Pack's no. of listing check: Selected Pack is Consumed: Buy that pack first");
            header ("location:/enterprise/Enterprise/prodAndServ");
            exit();
        }

        $maxPhotos = 0;
        if (isset($packInfo[$userPack]['property']['Max_Photos'])) {
            $maxPhotos = $packInfo[$userPack]['property']['Max_Photos'];
        }

        $maxDocs = 0;
        if (isset($packInfo[$userPack]['property']['Max_Docs'])) {
            $maxDocs = $packInfo[$userPack]['property']['Max_Docs'];
        }

        if ($usergroup == "cms" && $onBehalfOf != "true") {
            $userPack = 0;
            $maxPhotos = 3;
            $maxDocs = 3;
        }

        $this->load->library('upload_client');
        //$remaining = $this->paymentCheck();
        //$flagMedia = $remaining['flagMedia'];
        //global $userid;
        $uploadClient = new Upload_client();
        $ListingClientObj = new Listing_client();
        //echo "<pre>";print_r($_REQUEST);echo "</pre>";
        if(isset($_REQUEST['s_schol_name']) )
        {
            $addScholarshipData = array();
            $addScholarshipData['packType'] = $userPack;
            $addScholarshipData['scholarship_name'] = $this->input->post('s_schol_name',true);
            $addScholarshipData['short_desc'] = $this->input->post('s_description',true);
            if ($this->input->post('s_no_of_schol'))
                $addScholarshipData['num'] = $this->input->post('s_no_of_schol',true);
            $addScholarshipData['levels'] = $this->input->post('s_level',true);
            if ($addScholarshipData['levels']=="Graduate" || $addScholarshipData['levels']=="Post-Graduate" || $addScholarshipData['levels']=="Phd/Research")
            {
                $addScholarshipData['country_id'] =implode(',',$_REQUEST['country_id']);
                $addScholarshipData['city_id'] =implode(',',$_REQUEST['city_id']);
                $addScholarshipData['institute_id'] = implode(',',$_REQUEST['institute_id']);
                for($i = 0 ; $i < count($_REQUEST['country_id']); $i++)
                {
                    $addScholarshipData['country_id'.$i] = $_REQUEST['country_id'][$i];
                    $addScholarshipData['city_id'.$i] = $_REQUEST['city_id'][$i];
                    $addScholarshipData['institute_id'.$i] = $_REQUEST['institute_id'][$i];
                }
                $addScholarshipData['numoflocations'] = $i;
            }
            $addScholarshipData['last_date_submission'] = $this->input->post('last_date_sub',true);
            $addScholarshipData['application_procedure'] = $this->input->post('s_app_desc',true);
            $addScholarshipData['selection_process'] = $this->input->post('s_sel_process',true);
            $addScholarshipData['value'] = $this->input->post('s_award_value',true);
            $addScholarshipData['username'] = $userid;
            $addScholarshipData['contact_name'] =  $this->input->post('s_contact_name',true);
            $addScholarshipData['contact_address'] = $this->input->post('s_contact_add',true);
            $addScholarshipData['contact_cell'] =  $this->input->post('s_phone_no',true);
            $addScholarshipData['contact_email'] =  $this->input->post('s_email',true);
            $addScholarshipData['contact_fax'] = $this->input->post('s_fax_no',true);
            //Category
            if(count($_REQUEST['c_categories']) > 0)
            {
                $addScholarshipData['category_id'] = implode(',',$_REQUEST['c_categories']);
            }
            //echo "<pre>";print_r($addScholarshipData);echo "</pre>";
            //Eligibility array
            $s_eligibility = array();
            $eligText = "";
            if ($this->input->post('s_elg_gender'))
            {
                $eligText  = " Gender :".$this->input->post('s_elg_gender',true);
                $s_eligibility['gender'] = $this->input->post('s_elg_gender',true);
            }
            if ($this->input->post('s_elg_age'))
            {
                $eligText  .= " Age :".$this->input->post('s_elg_age',true);
                $s_eligibility['age'] = $this->input->post('s_elg_age',true);
            }
            if ($this->input->post('s_elg_res_stat'))
            {
                $eligText .= " Residency Status :".$this->input->post('s_elg_res_stat',true);
                $s_eligibility['res_stat'] = $this->input->post('s_elg_res_stat',true);
            }
            if ($this->input->post('s_elg_minqual'))
            {
                $eligText .= " Min Qual. :".$this->input->post('s_elg_minqual',true);
                $s_eligibility['minqual'] = $this->input->post('s_elg_minqual',true);
            }
            if ($this->input->post('s_elg_workex'))
            {
                $eligText .= " Work Experience :".$this->input->post('s_elg_workex',true);
                $s_eligibility['workex'] = $this->input->post('s_elg_workex',true);
            }
            if ($this->input->post('s_elg_marks'))
            {
                $eligText .= " Marks ".$this->input->post('s_elg_marks',true);
                $s_eligibility['marks'] = $this->input->post('s_elg_marks',true);
            }
            if ($this->input->post('s_elg_faminc'))
            {
                $eligText .= " Family Income ".$this->input->post('s_elg_faminc',true);
                $s_eligibility['faminc'] = $this->input->post('s_elg_faminc',true);
            }
            if ($this->input->post('s_elg_other'))
            {
                $eligText .= " Other Eligibility :".$this->input->post('s_elg_other',true);
                $s_eligibility['other'] = $this->input->post('s_elg_other',true);
            }

            //echo "<pre>"; print_r($s_eligibility);echo "</pre>";

            $addScholarshipData['dataFromCMS'] = $this->input->post('dataFromCMS',true);
            $addScholarshipData['requestIP'] = S_REMOTE_ADDR;
            error_log_shiksha("ADD SCHOLARSHIP LISTING : add_scholarship called ".print_r($addScholarshipData,true).print_r($s_eligibility,true));
            $response = $ListingClientObj->add_scholarship($appId,$addScholarshipData,$s_eligibility);
            error_log_shiksha("ADD SCHOLARSHIP LISTING : add_scholarship response ".print_r($response,true));
            //echo "<pre>";print_r($response);echo "</pre>";
            $scholarship_id = $response['type_id'];
            $listing_type = $response['listing_type'];
            $response['title'] = $addScholarshipData['scholarship_name'];

            if($maxDocs >0)
            {
                $scholDocFlag = false;
                for($i = 0;$i < $maxDocs; $i++){
                    if(isset($_FILES['s_upload_f']['tmp_name'][$i]) && ($_FILES['s_upload_f']['tmp_name'][$i] != '')){
                        $scholDocFlag= true;
                        break;
                    }else{
                        $scholDocFlag= false;
                    }
                }
                if($scholDocFlag)
                {
                    error_log_shiksha("ADD SCHOLARSHIP LISTING : uploadFile called");
                    $arrCaption = $this->input->post('s_upload_f_caption');
                    $docCaption = array();
                    for($i=0;$i<count($_FILES['s_upload_f']['name']);$i++){
                        $docCaption[$i] = ($arrCaption[$i]!="")?$arrCaption[$i]:$_FILES['s_upload_f']['name'][$i];
                    }
                    $upload_forms = $uploadClient->uploadFile($appId,'pdf',$_FILES,$docCaption,$response['scholarship_id'],"scholarship",'s_upload_f');
                    error_log_shiksha("ADD SCHOLARSHIP LISTING : uploading docs response:::".print_r($upload_forms,true));
                    if($upload_forms['status'] == 1){
                        for($k = 0;$k < $upload_forms['max'] ; $k++){
                            $reqArr = array();
                            $reqArr['mediaid']=$upload_forms[$k]['mediaid'];
                            $reqArr['url']=$upload_forms[$k]['imageurl'];
                            $reqArr['title']=$upload_forms[$k]['title'];
                            $reqArr['thumburl']=$upload_forms[$k]['thumburl'];
                            error_log_shiksha("ADD SCHOLARSHIP LISTING : updating docs request:::".print_r($reqArr,true));
                            $updateScholarship = $ListingClientObj->updateMediaContent($appId,$scholarship_id,'scholarship','doc',$reqArr);
                            error_log_shiksha("ADD SCHOLARSHIP LISTING : updating docs response:::".print_r($updateScholarship,true));
                        }
                    }
                }
            }

            if ($usergroup != "cms" || $onBehalfOf =="true") {
                error_log_shiksha("ADD SCHOLARSHIP LISTING : PRODUCT UPDATE called");
                $prodData['clientUserId'] = $userid;
                if(isset($sumsUserId)){
                    $prodData['sumsUserId']=$sumsUserId;
                }
                $prodData['baseProdId'] = $userPack;
                $prodData['consumedTypeId'] = $scholarship_id;
                $prodData['consumedType'] = "scholarship";
                $sumsProdObj = new Sums_Product_client();
                $upResponse = $sumsProdObj->productConsume(1,$prodData);
                error_log_shiksha("ADD SCHOLARSHIP LISTING : productUpdate RESPONSE ".print_r($upResponse,true));
                //$displayData['remaining'] = $remaining[$userPack]['remaining'];
                //$displayData['productname'] = $remaining[$userPack]['productname'];
            }else{
                $displayData['remaining'] = "Unlimited";
                $displayData['productname'] = "CMS";
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://".SHIKSHACLIENTIP."/ListingScripts/indexListing/".$scholarship_id."/scholarship");

            curl_setopt($ch, CURLOPT_VERBOSE, 1); // set url to post to
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 40); // times out after 4s
            curl_setopt($ch, CURLOPT_POSTFIELDS, $content); // add POST fields
            curl_setopt($ch, CURLOPT_POST, 1);
            $result = curl_exec($ch); // run the whole process
            curl_close($ch);

            if(isset($_REQUEST['json'])){
                echo json_encode($response);
            }
            else{
                $displayData['response'] = $response;
                $displayData['type'] = "scholarship";
                $displayData['validateuser'] = $this->userStatus;
                //            print_r($displayData,true);
                $dataFromCMS = $this->input->post('dataFromCMS',true);
                $prodId = $this->input->post('prodId',true);
                if($usergroup=="user"){
                    error_log_shiksha("ADD SCHOLARSHIP LISTING : view called ".print_r($displayData,true));
                    $this->load->view('listing/resultPage',$displayData);
                }else{
                    $displayData['prodId'] = $prodId;
                    if($addScholarshipData['contact_email'] != ''){
                        $email = $addScholarshipData['contact_email'];
                        $fromMail = "enterprise@shiksha.com";
                        $ccmail = "sales@shiksha.com";
                        $type_id = $scholarship_id;
                        $mail_client = new Alerts_client();
                        $subject = "Your Listing on Shiksha.com, Listing Id- scholarship-".$type_id;
                        $data['validateuser'] = $validity;
                        $data['listingType'] = "scholarship";
                        $data['listingTitle'] = $addScholarshipData['scholarship_name'];
                        $data['listingUrl'] = "http://www.shiksha.com/getListingDetail/".$type_id."/scholarship/college-university-educational-scholarships-".seo_url($addScholarshipData['scholarship_name']);
                        $content = $this->load->view('common/mailTBS',$data,true);
                        $response=$mail_client->externalQueueAdd("12",$fromMail,$email,$subject,$content,$contentType="html",$ccmail);
                        print_r($response);
                    }
                    error_log_shiksha("ADD SCHOLARSHIP LISTING :cms  view called ".print_r($displayData,true));
                    $this->load->library('enterprise_client');
                    $entObj = new Enterprise_client();
                    $displayData['headerTabs'] = $entObj->getHeaderTabs(1,$usergroup,$validity[0]['userid']);
                    $this->load->view('enterprise/resultPageCMS',$displayData);
                }
            }
        }
    }


    //Controller API to Add a Addmission listing
    function addAdmission($appId = 1) {
        header("location:/enterprise/Enterprise/disallowedAccess");
        exit();
        $this->init();
        error_log_shiksha("ADD ADMISSION LISTING : data received ".print_r($_POST,true));
        $this->load->library('sums_product_client');
        global $logged;
        global $userid;
        global $usergroup;
        $paymentInfo = array();
        $validity = $this->checkUserValidation();
        $usergroup = $validity[0]['usergroup'];
        $userid = $validity[0]['userid'];
        $onBehalfOf = $this->input->post('onBehalfOf',true);
        if ($onBehalfOf == "true")
        {
            $sumsUserId = $userid;
            $userid = $this->input->post('clientId',true);
        }
        global $userPack;
        $userPack = $this->input->post('userPack',true);
        if ($usergroup != "cms" || $onBehalfOf =="true") {
            $displayData['userProducts'] = $this->paymentCheck($userid);
            //new product chk
            $sumsProdObj = new Sums_Product_client();
            $paymentInfo = $sumsProdObj->getProductsForUser(1,array('userId'=>$userid));
            $packInfo =  $sumsProdObj->getProductFeatures(1,array('productId'=>$userPack));
            if ($packInfo[$userPack]['BaseProdCategory']!="Listing") {
                echo "Select Listings Product Pack";
                header ("location:/enterprise/Enterprise/prodAndServ");
                exit;
            }
        }
        $productDataList =  $paymentInfo;
        if( ($usergroup != "cms" || $onBehalfOf =="true") && ($productDataList[$userPack]['RemainingQuantity'] < 1)) {
            error_log_shiksha("Selected Packs' no. of listing check: Selected Pack is Consumed: Buy that pack first");
            header ("location:/enterprise/Enterprise/prodAndServ");
            exit();
        }
        $maxPhotos = 0;
        if (isset($packInfo[$userPack]['property']['Max_Photos'])) {
            $maxPhotos = $packInfo[$userPack]['property']['Max_Photos'];
        }
        $maxDocs = 0;
        if (isset($packInfo[$userPack]['property']['Max_Docs'])) {
            $maxDocs = $packInfo[$userPack]['property']['Max_Docs'];
        }

        if ($usergroup == "cms" && $onBehalfOf != "true") {
            $userPack = 0;
            $maxPhotos = 3;
            $maxDocs = 3;
        }

        $this->load->library('upload_client');
        $uploadClient = new Upload_client();
        $ListingClientObj = new Listing_client();

        $username = $userid;
        $editNotificationData['packType'] = $userPack;
        $editNotificationData['admission_notification_name'] = $this->input->post('a_name',true);
        $editNotificationData['short_desc'] = $this->input->post('a_desc',true);
        $editNotificationData['admission_year'] = $this->input->post('a_year',true);
        $editNotificationData['application_brochure_start_date'] = $this->input->post('a_app_bro_start',true);

        $editNotificationData['application_brochure_end_date'] = $this->input->post('a_app_bro_end',true);
        $editNotificationData['application_end_date'] = $this->input->post('a_app_last',true);
        $editNotificationData['application_procedure'] = $this->input->post('a_app_proc',true);
        $editNotificationData['fees'] = $this->input->post('a_app_fees',true);
        $editNotificationData['entrance_exam'] = $this->input->post('a_exam',true);
        if($editNotificationData['entrance_exam'] == "yes" ) {
            //TEST REQUIRED FOR COURSE
            $tests_required = $this->input->post('examSelected',true);
            $testsArray = array();
            $j=0;
            $flag_test_required_other = 0;
            for ($i=0;$i<count($tests_required);$i++)
            {
                if ($tests_required[$i]!="-1")
                {
                    $testsArray[$j] = $tests_required[$i];
                    $j++;
                }
                else
                {
                    $flag_test_required_other = 1;
                }
            }
            $editNotificationData['tests_required'] = implode(",",$testsArray);
            if ($flag_test_required_other == 1)
            {
                $editNotificationData['tests_required_other'] = 'true';
                $editNotificationData['exam_name'] = $this->input->post('a_exam_name',true);
                $editNotificationData['exam_date'] = $this->input->post('a_exam_date',true);
                $editNotificationData['exam_duration'] = $this->input->post('a_exam_duration',true);
                $editNotificationData['exam_timings'] = $this->input->post('a_exam_timing',true);
                $editNotificationData['numOfCentres'] = $this->input->post('a_num_exam_centre',true);
                $addressLine1 = $this->input->post("a_address_line1",true);
                $addressLine2 = $this->input->post("a_address_line2",true);
                $countryId = $this->input->post("a_country_id",true);
                $cityId = $this->input->post("a_city_id",true);
                $zip = $this->input->post("a_zip",true);
                for($i = 0 ; $i <  $editNotificationData['numOfCentres']; $i++){
                    $editNotificationData['address_line1'.$i] = $addressLine1[$i];
                    $editNotificationData['address_line2'.$i] = $addressLine2[$i];
                    $editNotificationData['country_id'.$i] = $countryId[$i];
                    $editNotificationData['city_id'.$i] = $cityId[$i];
                    $editNotificationData['zip'.$i] = $zip[$i];
                }
            }
        }
        $editNotificationData['main_country_id'] = $this->input->post('a_country',true);
        $editNotificationData['main_city_id'] = $this->input->post('a_city',true);

        $editNotificationData['category_id'] = implode(',',$this->input->post('c_categories',true));
        $editNotificationData['username'] = $userid;
        $editNotificationData['institute_id'] = $this->input->post('a_institute');

        if ($this->input->post('s_elg_minqual')) {
            $eligibility['minqual'] = $this->input->post('s_elg_minqual',true);
        }
        if ($this->input->post('s_elg_age')) {
            $eligibility['age'] = $this->input->post('s_elg_age',true);
        }
        if ($this->input->post('s_elg_marks')) {
            $eligibility['marks'] = $this->input->post('s_elg_marks',true);
        }
        if ($this->input->post('s_elg_res_stat')) {
            $eligibility['res_stat'] = $this->input->post('s_elg_res_stat',true);
        }

        $editNotificationData['contact_name'] = $this->input->post('s_contact_name');
        $editNotificationData['contact_address'] = $this->input->post('s_contact_add');
        $editNotificationData['contact_cell'] = $this->input->post('s_phone_no');
        $editNotificationData['contact_email'] = $this->input->post('s_email');
        $editNotificationData['contact_fax'] = $this->input->post('s_fax_no');
        $editNotificationData['dataFromCMS'] = $this->input->post('dataFromCMS',true);
        $editNotificationData['requestIP'] = S_REMOTE_ADDR;
        error_log_shiksha("ADD ADMISSION LISTING : add_admission called".print_r($editNotificationData,true));
        $response = $ListingClientObj->add_admission($appId,$editNotificationData,$eligibility);
        error_log_shiksha("ADD ADMISSION LISTING : response ".print_r($response,true));
        $response['title'] = $editNotificationData['admission_notification_name'];


        $joinGroupInfo = $ListingClientObj->getJoinGroupInfo($appId,$editNotificationData['institute_id']);
        $locationsArr = $joinGroupInfo[0]['locations'];
        $collegeName = $joinGroupInfo[0]['instituteName'];
        $collegeName = str_replace('-',' ',$collegeName);

        $optionalArgs = array();
        for($i=0;$i<count($locationsArr);$i++) {
            $optionalArgs['location'][$i] = $locationsArr[$i]['cityName']."-".$locationsArr[$i]['countryName'];
        }

        $instituteUrl = getSeoUrl($editNotificationData['institute_id'],"institute",$joinGroupInfo[0]['instituteName'],$optionalArgs);
        //add event if dates are set
        if ($this->input->post('a_app_bro_start') )
        {
            $eventArray = array();
            $eventArray['email'] = ($editNotificationData['contact_email']!="")?$editNotificationData['contact_email']:ADMIN_EMAIL;
            $eventArray['description'] = $editNotificationData['short_desc'];
            if(strlen($editNotificationData['application_procedure']) > 0 ){
                $eventArray['description'] .= " <br/><b>Application Process:</b> ".$this->input->post('a_app_proc',true)." <br/>";
            }
            if(strlen($editNotificationData['fees']) > 0){
                $eventArray['description'] .= " <b> Fees: </b> ".$this->input->post('a_app_fees',true)." <br/>";
            }
            if(strlen($joinGroupInfo[0]['instituteName'])>0){
                $eventArray['description'] .= "<br/> <br/> <b><a href='$instituteUrl'> ".$joinGroupInfo[0]['instituteName']."</a> </b> <br/>";
            }

            $eventArray['user_id'] = 1;
            $cats = $_REQUEST['c_categories'];
            $eventArray['board_id'] = $cats[0];
            for($i = 1; $i < count($cats); $i++){
                $eventArray['board_id'] = ",".$cats[$i];
            }

            $eventArray['contact_person'] = $editNotificationData['contact_name'];
            $eventArray['fax'] = $editNotificationData['contact_fax'];
            $eventArray['phone'] = $editNotificationData['contact_cell'];
            $eventArray['event_title'] = str_replace('-',' ',$response['title'])." - $collegeName - Sale of forms";
            $eventArray['start_date'] = $this->input->post('a_app_bro_start');
            $eventArray['end_date'] = $this->input->post('a_app_bro_end')?$this->input->post('a_app_bro_end'):$eventArray['start_date']." 00:01:00";
            $eventArray['end_date'] = $this->input->post('a_app_last')?$this->input->post('a_app_last'):$eventArray['end_date'];
            $this->load->library('event_cal_client');
            $eventObj = new Event_cal_client();
            $eventTitle = str_replace('-',' ',$response['title'])." - $collegeName - Sale of forms";
            $eventTitle .=" - ".date(' d F ',strtotime($editNotificationData['application_brochure_start_date']));
            $eventTitle = $this->input->post('a_app_last')? $eventTitle." - Submission of forms till "." - ".date(' d F ',strtotime($editNotificationData['application_end_date'])): $eventTitle;
            $eventArray['event_title'] = $eventTitle;
            $eventArray['examSelected'] = array($this->input->post('examSelected', true),'struct');//Ashish
            $locations = array();
            for($i=0;$i<count($locationsArr);$i++) {
                array_push($locations,array(
                            array(
                                'Address_Line1'=>array($locationsArr[$i]['address'],'string'),
                                'city'=>array($locationsArr[$i]['cityId'],'string'),
                                'zip'=>array($locationsArr[$i]['zip'],'string'),
                                'country'=>array($locationsArr[$i]['countryId'],'string'),
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

            $eventResponse = $eventObj->addEventNew($appId,$eventArray,1,$locations,$response['Admission_notification_id'],$response['listing_type']);
        }
        if($editNotificationData['entrance_exam'] == "yes" ) {
            if ($editNotificationData['exam_date']!="")
            {
                if ($editNotificationData['numOfCentres']>0)
                {
                    $locations = array();
                    $this->load->library('event_cal_client');
                    $eventObj = new Event_cal_client();
                    $eventArray = array();
                    $eventArray['email'] = ($editNotificationData['contact_email']!="")?$editNotificationData['contact_email']:ADMIN_EMAIL;
                    $eventArray['description'] = $editNotificationData['short_desc'];
                    if(strlen($editNotificationData['application_procedure']) > 0 ){
                        $eventArray['description'] .= " <br/><b>Application Process:</b> ".$this->input->post('a_app_proc',true)." <br/>";
                    }
                    if(strlen($editNotificationData['fees'])> 0 ){
                        $eventArray['description'] .= " <b> Fees: </b> ".$this->input->post('a_app_fees',true)." <br/>";
                    }
                    if(strlen($joinGroupInfo[0]['instituteName'])>0){
                        $eventArray['description'] .= " <br/> <br/> <b><a href='$instituteUrl'> ".$joinGroupInfo[0]['instituteName']."</a> </b> <br/>";
                    }

                    $eventArray['user_id'] = 1;
                    $eventArray['contact_person'] = $editNotificationData['contact_name'];
                    $eventArray['fax'] = $editNotificationData['contact_fax'];
                    $eventArray['phone'] = $editNotificationData['contact_cell'];
                    $cats = $_REQUEST['c_categories'];
                    $eventArray['board_id'] = $cats[0];
                    for($i = 1; $i < count($cats); $i++){
                        $eventArray['board_id'] = ",".$cats[$i];
                    }
                    $str = $editNotificationData['admission_notification_name'];
                    $exam_name = $editNotificationData['exam_name']?$editNotificationData['exam_name']:' Exam ';
                    $eventArray['event_title'] = "$exam_name - Date ".date(' d F Y ',strtotime($editNotificationData['exam_date']))." - ".str_replace('-',' ',$str)." - $collegeName";
                    $eventArray['start_date'] = $editNotificationData['exam_date'];
                    $eventArray['end_date'] = $editNotificationData['exam_date'];
                    for($i=0;$i<$editNotificationData['numOfCentres'];$i++) {
                        array_push($locations,array(
                                    array(
                                        'Address_Line1'=>array($editNotificationData['address_line1'.$i],'string'),
                                        'city'=>array($editNotificationData['city_id'.$i],'string'),
                                        'zip'=>array($editNotificationData['zip'.$i],'string'),
                                        'country'=>array($editNotificationData['country_id'.$i],'string'),
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

                    error_log_shiksha("NEWEVENT ".print_r($locations,true));
                    $eventResponse = $eventObj->addEventNew($appId,$eventArray,3,$locations,$response['Admission_notification_id'],$response['listing_type']);
                    error_log_shiksha("NEWEVENT ADD ADMISSION LISTING : create event for exam centre RESPONSE : " .print_r($eventResponse,true));
                }
            }
        }


        if($maxDocs >0)
        {
            $admitDocFlag = false;
            for($i = 0;$i < $maxDocs; $i++){
                if(isset($_FILES['a_app_forms']['tmp_name'][$i]) && ($_FILES['a_app_forms']['tmp_name'][$i] != '')){
                    $admitDocFlag= true;
                    break;
                }else{
                    $admitDocFlag= false;
                }
            }
            if($admitDocFlag)
            {
                error_log_shiksha("ADD ADMISSION LISTING : upload media called ".print_r($_FILES,true));
                $arrCaption = $this->input->post('a_app_forms_caption');
                $docCaption = array();
                for($i=0;$i<count($_FILES['a_app_forms']['name']);$i++){
                    $docCaption[$i] = ($arrCaption[$i]!="")?$arrCaption[$i]:$_FILES['a_app_forms']['name'][$i];
                }

                $upload_forms = $uploadClient->uploadFile($appId,'pdf',$_FILES,$docCaption,$response['Admission_notification_id'],"notification",'a_app_forms');
                error_log_shiksha("ADD ADMISSION LISTING : uploading docs response:::".print_r($upload_forms,true));
                if($upload_forms['status'] == 1){
                    error_log_shiksha("in upload success".print_r($upload_forms,true));
                    for($k = 0;$k < $upload_forms['max'] ; $k++){
                        $reqArr = array();
                        $reqArr['mediaid']=$upload_forms[$k]['mediaid'];
                        $reqArr['url']=$upload_forms[$k]['imageurl'];
                        $reqArr['title']=$upload_forms[$k]['title'];
                        $reqArr['thumburl']=$upload_forms[$k]['thumburl'];
                        error_log_shiksha("ADD ADMISSION LISTING updatemediacontent before");
                        $updateAdmission = $ListingClientObj->updateMediaContent($appId,$response['Admission_notification_id'],'notification','doc',$reqArr);
                        error_log_shiksha("ADD ADMISSION LISTING : updating admissions response:::".print_r($updateAdmission,true));
                    }
                }
            }
        }

        if ($usergroup != "cms" || $onBehalfOf =="true") {
            error_log_shiksha("ADD ADMISSION LISTING : PRODUCT UPDATE called");
            $prodData['clientUserId'] = $userid;
            if(isset($sumsUserId)){
                $prodData['sumsUserId']=$sumsUserId;
            }
            $prodData['baseProdId'] = $userPack;
            $prodData['consumedTypeId'] = $response['Admission_notification_id'];
            $prodData['consumedType'] = "notification";
            error_log_shiksha("prod consume data for notification: ".print_r($prodData,true));
            $sumsProdObj = new Sums_Product_client();
            $upResponse = $sumsProdObj->productConsume(1,$prodData);
        }else{
            $displayData['remaining'] = "Unlimited";
            $displayData['productname'] = "CMS";
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://".SHIKSHACLIENTIP."/ListingScripts/indexListing/".$response['Admission_notification_id']."/notification");

        curl_setopt($ch, CURLOPT_VERBOSE, 1); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 40); // times out after 4s
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content); // add POST fields
        curl_setopt($ch, CURLOPT_POST, 1);
        $result = curl_exec($ch); // run the whole process
        curl_close($ch);

        if(isset($_REQUEST['json'])){
            echo json_encode($response);
        }
        else{
            $displayData['response'] = $response;
            $displayData['type'] = "institute";
            $displayData['validateuser'] = $this->userStatus;
            $dataFromCMS = $this->input->post('dataFromCMS',true);
            $prodId = $this->input->post('prodId',true);
            if($usergroup== "user"){
                $this->load->view('listing/resultPage',$displayData);
            }else{
                $displayData['prodId'] = $prodId;
                if($editNotificationData['contact_email'] != ''){
                    $email = $editNotificationData['contact_email'];
                    $fromMail = "enterprise@shiksha.com";
                    $ccmail = "sales@shiksha.com";
                    $type_id = $response['Admission_notification_id'];
                    $mail_client = new Alerts_client();
                    $subject = "Your Listing on Shiksha.com, Listing Id- notification-".$type_id;
                    $data['validateuser'] = $validity;
                    $data['listingType'] = "notification";
                    $data['listingTitle'] = $editNotificationData['admission_notification_name'];
                    $data['listingUrl'] = "http://www.shiksha.com/getListingDetail/".$type_id."/notification/educational-events-calender-admission-notifications-".seo_url($editNotificationData['admission_notification_name'],"-",30);
                    $content = $this->load->view('common/mailTBS',$data,true);
                    $response=$mail_client->externalQueueAdd("12",$fromMail,$email,$subject,$content,$contentType="html",$ccmail);
                    print_r($response);
                }
                $this->load->library('enterprise_client');
                $entObj = new Enterprise_client();
                $displayData['headerTabs'] = $entObj->getHeaderTabs(1,$usergroup,$validity[0]['userid']);

                $this->load->view('enterprise/resultPageCMS',$displayData);
            }
        }
    }

    function validateCaptcha($captcha,$secCodeIndex='')
    {
        $response = 0;
        if($secCodeIndex=='')
            $secCodeIndex='security_code';
        if(verifyCaptcha($secCodeIndex,$captcha,1))
        {
            $response = 1;
        }
        echo $response;
    }

    function showScholarshipsList() {
		Header( "HTTP/1.1 301 Moved Permanently" );
		Header( "Location: http://www.shiksha.com");
		exit();
        $selectedCategoryId = isset($_REQUEST['categoryId']) && $_REQUEST['categoryId'] !='' ?$_REQUEST['categoryId']:1;
        $selectedCountryId = isset($_REQUEST['countryId']) && $_REQUEST['countryId'] !='' ? $_REQUEST['countryId'] : 1;
        $startOffset= isset($_REQUEST['startOffset']) && $_REQUEST['startOffset'] !='' ? $_REQUEST['startOffset'] : 0;
        $countOffset = isset($_REQUEST['countOffset']) && $_REQUEST['countOffset'] !='' ? $_REQUEST['countOffset'] : 20;
        $this->init();
        $appId = 1;
        $ListingClientObj = new Listing_client();
        $scholarshipsList= array_pop($ListingClientObj->getScholarshipsForHomePageS($appId,$selectedCategoryId, $selectedCountryId, $startOffset, $countOffset, 1000));
        $displayData = $scholarshipsList;

		$categoryClient = new Category_list_client();
		$countryList = $categoryClient->getCountries($appId);
		$categoryTree = $categoryClient->getCategoryTree($appId);
        $catTree = array();
        for($categoryTreeCount = 0; $categoryTreeCount < count($categoryTree); $categoryTreeCount++) {
            $parentId = $categoryTree[$categoryTreeCount]['parentId'];
            $categoryId = $categoryTree[$categoryTreeCount]['categoryID'];
            if($parentId == 0) {continue;}
            if($parentId == 1) {
                $catTree[$categoryId] = $categoryTree[$categoryTreeCount];
            } else {
                $catTree[$parentId]['subCategories'][$categoryId] = $categoryTree[$categoryTreeCount];
            }
        }
        $displayData['categoryTree'] = $catTree;
        $displayData['countryList'] = $countryList;
        $selectedCategoryName = '';
        foreach($catTree as $category => $categoryStuff) {
            if($selectedCategoryId == $category) {
                $selectedCategoryName = $categoryStuff['categoryName'];
                break;
            }
            if(array_key_exists($selectedCategoryId ,$categoryStuff['subCategories']) ) {
                $selectedCategoryName = $categoryStuff['categoryName'] .' - '. $categoryStuff['subCategories'][$selectedCategoryId]['categoryName'];
                break;
            }
        }
        $selectedCountryName = '';
        foreach($countryList  as $country) {
            if($country['countryID'] == $selectedCountryId) {
                $selectedCountryName = $country['countryName'];
                break;
            }
        }
        $validate = $this->checkUserValidation();
        $displayData['validateuser'] = $validate;
        $displayData['selectedCategory'] = $selectedCategoryId;
        $displayData['selectedCategoryName'] = $selectedCategoryName;
        $displayData['selectedCountry'] = $selectedCountryId;
        $displayData['selectedCountryName'] = $selectedCountryName;
        $this->load->view('listing/showScholarshipsList', $displayData);
    }

    function uploadMedia($formId, $mediaType) {
	    //error_log(print_r($_FILES, true));
	    //error_log(print_r($_POST,true));
	    $this->init();
	    $appId = 1;
	    $ListingClientObj = new Listing_client();
	    $fileCaption= $_POST['fileNameCaption'];
	    $fileName = split("[/\\.]",$_FILES['mediaFile']['name'][0]);
	    $fileExtension = $fileName[count($fileName) - 1];
	    $fileCaption .= $fileExtension == '' ? '' : '.'. $fileExtension;
	    $listingId = $_POST['listingId'];
	    $listingType = $_POST['listingType'];
	    $this->load->library('upload_client');
	    $uploadClient = new Upload_client();
	    $this->load->library('Listing_media_client');
	    $ListingMediaClientObj= new Listing_media_client();
	    switch($mediaType) {
		    case 'photos':
			    $mediaDataType = 'image';
			    $listingMediaType = 'photos';
			    break;
		    case 'videos':
			    $mediaDataType = 'video';
			    $listingMediaType = 'videos';
			    break;
		    case 'documents':
			    $mediaDataType = 'pdf';
			    $listingMediaType = 'doc';
			    break;
	    }
	    $upload_forms = $uploadClient->uploadFile($appId,$mediaDataType,$_FILES,array($fileCaption),$listingId, $listingType,'mediaFile');
	    //error_log('ASHI::'.print_r($upload_forms, true));
	    if(is_array($upload_forms)) {
			    $updateListingMedia = null;
			    if($upload_forms['status'] == 1){
			    for($k = 0;$k < $upload_forms['max'] ; $k++){ //It will always be 1 :-). Added for future cases if multiple uploads will be asked in one go.
				    $reqArr = array();
				    $reqArr['mediaId']=$upload_forms[$k]['mediaid'];
				    $reqArr['mediaUrl']=$upload_forms[$k]['imageurl'];
				    $reqArr['mediaName']=$upload_forms[$k]['title'];
				    $reqArr['mediaThumbUrl']=$upload_forms[$k]['thumburl'];

				    $updateListingMedia = $ListingMediaClientObj->mapMediaContentWithListing($appId,$listingId,$listingType,$listingMediaType,base64_encode(json_encode($reqArr)));
			    }
			    $numOfVideos = $k;
			    }
			    //error_log('ASHISH:::'. print_r($updateListingMedia, true));
			    $displayData['fileId'] = $reqArr['mediaId'];
			    $displayData['fileName'] = $fileCaption;
			    $displayData['mediaType'] = $mediaType;
			    $displayData['fileUrl'] = $reqArr['url'];
			    $displayData['fileThumbUrl'] = $reqArr['thumburl'];
	    } else {
		    $displayData['error'] = $upload_forms;
	    }
	    $displayData['formId'] = $formId;
	    echo json_encode($displayData);
    }

	function updateMediaField() {
		$fieldName = $_POST['fieldName'];
		$fieldValue = $_POST['fieldValue'];
		$fileId = $_POST['fileId'];
		$fileType = $_POST['fileType'];
		$listingType = $_POST['listingType'];
		$listingId = $_POST['listingId'];

		switch($fieldName) {
			case 'fileNameCaption' : $fieldName = 'name';
			break;
			default : $fieldName = '';
		}


		$this->load->library('Listing_media_client');
		$ListingMediaClientObj= new Listing_media_client();
            	$reportedAbuseStatus = $ListingMediaClientObj->updateMediaAttributesForListing($appId, $listingType, $listingId, $fileType, $fileId, $fieldName, $fieldValue);
		//print_r($_POST);
	}

	function updateMediaAssociation() {
		$mediaId = $_POST['mediaId'];
		$entityName = $_POST['entityName'];
		$entityId = $_POST['entityId'];
		$action = $_POST['action'];
		//print_r($_POST);
	}

	function deleteMedia($listingType, $listingId, $fileType, $fileId) {
		// TODO : TO write a webservice to update the fields (caption, etc) of the media file
		$this->load->library('Listing_media_client');
		$ListingMediaClientObj= new Listing_media_client();
            	$reportedAbuseStatus = $ListingMediaClientObj->removeMediaForListing($appId, $listingType, $listingId, $fileType, $fileId);
		//print_r($_POST);
	}
    function getUploadedMediaAssociation(){
		echo "<pre>";
		print_r($_POST);
		echo "<br/>";
		print_r(json_decode($_POST['mediaAssoc'], true));
		echo "</pre>";
		$listingType = $_POST['listingType'];
		$listingId = $_POST['listingId'];
		$this->load->library('Listing_media_client');
		$ListingMediaClientObj= new Listing_media_client();
            	$reportedAbuseStatus = $ListingMediaClientObj->associateMedia($appId, $listingType, $listingId, base64_encode($_POST['mediaAssoc']));
	}


	function migrateDataWiki($database){
        error_log("FGHJ 123");
        $this->load->library('Listing_media_client');
        error_log("FGHJ 123");

        $ListingMediaClientObj= new Listing_media_client();
        $reportedAbuseStatus = $ListingMediaClientObj->migrateWikiSectionsInstitute($database);
        $reportedAbuseStatus = $ListingMediaClientObj->migrateWikiSectionsCourse($database);
    }


    function getDetailsForEbrouchre($type_id, $listing_type) {
        $appId = 1;
        $this->init();
        $displayData = array();
        $registerClient = new register_client();
        $ListingClientObj = new Listing_client();
        $alertClientObj = new Alerts_client();
        $thisUrl = $_SERVER['REQUEST_URI'];
        $fullUrl = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
        $displayData['fullUrl'] = $fullUrl;
        $otherInstitutesCategory = '';
        $listingDetails = $ListingClientObj->getListingDetails($appId,$type_id,$listing_type,$otherInstitutesCategory,1);
        if(isset($listingDetails[0]['lastModifyDate']) && (strtotime($listingDetails[0]['lastModifyDate']) != "")){
            $listingDetails[0]['timestamp'] = $listingDetails[0]['lastModifyDate'];
        }
        $displayData['type_id'] = $type_id;
        $displayData['listing_type'] = $listing_type;
        $displayData['thisUrl'] = $thisUrl;

        $displayData['details'] = $listingDetails[0];
        $displayData['subscribeStatus'] = '';

        $displayData['ListingMode'] = 'view';
        $mediaArray = unserialize(base64_decode($displayData['details']['mediaInfo']));
        $photoArray = array();
        $videoArray= array();
        $docArray = array();
        for($i = 0 ; $i < count($mediaArray); $i++){
            switch($mediaArray[$i]['media_type']){
                case 'doc':
                    array_push($docArray,$mediaArray[$i]);
                    break;
                case 'photo':
                    array_push($photoArray,$mediaArray[$i]);
                    break;
                case 'video':
                    array_push($videoArray,$mediaArray[$i]);
                    break;
            }
        }

        $detailPageComponents = array();
        $i=0;

        $wikiSections = unserialize(base64_decode($displayData['details']['wikiFields']));
        if($displayData['details']['showWiki'] == 'yes'){
            for($j = 0; $j < count($wikiSections); $j++){
                $detailPageComponents[$i]['anchor'] = seo_url($wikiSections[$j]['caption']);
                $detailPageComponents[$i]['title'] = $wikiSections[$j]['caption'];
                $detailPageComponents[$i]['value'] = $wikiSections[$j]['attributeValue'];
                $detailPageComponents[$i]['edit'] = '';
                $i++;
            }
        }
        $displayData['detailPageComponents'] = $detailPageComponents;
        $displayData['courseList'] = unserialize(base64_decode($displayData['details']['courseList']));
        $displayData['docList'] = $docArray;
        $displayData['photoList'] = $photoArray;
        //print_r($displayData);
        $brouchreContent = $this->load->view('listing/eBrouchre',$displayData,true);
        error_log("Attach".$brouchreContent);

        $alertClientObj = new Alerts_client();
        $attachmentResponse = $alertClientObj->createAttachment("12",$type_id,$listing_type,'E-Brochure',$brouchreContent,$displayData['details']['title'].".html",'html');
        foreach($displayData['courseList'] as $course)
        {
            $attachmentResponse = $alertClientObj->createAttachment("12",$course['course_id'],'course','E-Brochure',$brouchreContent,$displayData['details']['title'].".html",'html');
        }
        echo $attachmentResponse;

    }

    function getListingAutoComplete($type,$keyword,$start,$rows)
    {
        $this->init();
        $ListingClientObj = new Listing_client();
        $result = $ListingClientObj->getListingAutoComplete(12,$type,$keyword,$start,$rows);
        echo json_encode($result);
    }

    function textMailInsert()
    {
        $this->init();
        $alertClientObj = new Alerts_client();
        $insertResponse = $alertClientObj->externalQueueAdd(12,ADMIN_EMAIL,'kasa.shirish@naukri.com','Hurray','HIP HIP HURRAY',"text",$sendTime="0000-00-00 00:00:00",'y',array(1,2));
        echo $insertResponse;
    }

    /* CAUTION 8-X
    ONLY ONCE RUNNABLE SCRIPT
    START
    */

    function MIGRATIONcreateMultipleInstiForMultiLocation($database){
        error_log("ERT kldjfklsdf");
        $this->load->library('Listing_client');
        $ListingClientObj = new Listing_client();
        $response = $ListingClientObj->createMultipleInstiForMultiLocation("1",$database);
    }

    function MIGRATIONcreateCoursesForMultipleInstis($database){
        $this->load->library('Listing_client');
        $ListingClientObj = new Listing_client();
        $response = $ListingClientObj->createCoursesForMultipleInstis("1",$database);
    }

    /* CAUTION 8-X
    ONLY ONCE RUNNABLE SCRIPT
    END
    */

    /****************************
    Purpose: Function to delete the Listing Cached HTML files from all the Frontend servers.
	    This is requried because as soon as any Update on done on listings, we need to delete its HTML files from all the servers, so that new HTML files
	    can be created on all of them.
    Input: Listing Id and Listing type
    Output: None
    *****************************/
    function deleteListingCacheHTMLFile($listingId,$listingType)
    {
	$this->load->library('listing_client');
        $ListingClientObj = new Listing_client();
	$ListingClientObj->deleteListingCacheHTMLFile($listingId,$listingType,false);
    }
	
	
	/*
	 @name: trackAutoSuggestStats
	 @description: this is for tracking the user behaviour while using the autosuggest feature
	 @param string $suggestionShown: whether user has seen the suggestion or not
	 @param string $actionTakenByUser: User has clicked, press enter on the suggestion/ or just clicked on the search button
	 @param string $userInput: What user has typed in the text box
	 @param string $pickedSuggestion: Which suggestion user has picked from the suggesitons: if its '-1' than user hasn't gone for autosuggestions
	 @param string $pickedSuggestionNo: The suggestion no that user has picked: if its '-1' than user hasn't gone for autosuggestions
	*/
	function trackAutoSuggestStats($suggestionShown, $actionTakenByUser, $userInput, $pickedSuggestion = '-1', $pickedSuggestionNo = '-1', $navigateInSuggestion, $freeTextSearch = '-1'){
		$paramsArray = array();
		$paramsArray['suggestionShown'] = $suggestionShown;
		$paramsArray['actionTakenByUser'] = $actionTakenByUser;
		$paramsArray['userInput'] = $userInput;
		$paramsArray['pickedSuggestion'] = $pickedSuggestion;
		$paramsArray['pickedSuggestionNo'] = $pickedSuggestionNo;
		$paramsArray['navigateInSuggestion'] = $navigateInSuggestion;
		$paramsArray['freeTextSearch'] = $freeTextSearch;
		$sessionId = '-1';
		$sessionId = sessionId();
		$paramsArray['sessionId'] = $sessionId;
		
		if(trim($userInput) != '' && $userInput != '-1'){
			$this->load->library('Listing_client');
			$ListingClientObj = new Listing_client();
			$response = $ListingClientObj->clientTrackAutoSuggestStats($paramsArray);
		}
	}

    function callToCopyFileToServers($fileName){
        $fileName = base64_decode($fileName);
        //First check if the file exists. If it does, open it and read the content
        /*if(file_exists($fileName)){
                $content = chunk_split(base64_encode(file_get_contents($fileName)));
               //Now, transfer the filename and file content to the other server
                $_POST['filename'] = $fileName;
                $_POST['filecontent'] = $content;
                $url = "http://".COPY_TO_SERVER."/listing/Listing/makeCopyFile";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_POST, 0);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                $result = curl_exec($ch);
                curl_close($ch);
         }*/
    }


    function makeCopyFile(){
         /*if(isset($_POST['filename']) && isset($_POST['filecontent'])){
            if(file_exists($_POST['filename']))
                 unlink($_POST['filename']);
            $fp=fopen($_POST['filename'],'w+');
            flock( $fp, LOCK_EX ); // exclusive lock
            fputs($fp,base64_decode($_POST['filecontent']));
            flock( $fp, LOCK_UN ); // release the lock
            fclose($fp);
         }*/
    }
}
?>
