<?php

class ListingPageAnA extends MX_Controller
{
    function index($institute,$categories)
    {
		$this->load->helper(array('form', 'url', 'image_helper','shikshaUtility'));
		$this->load->library(array('relatedClient','listing_client','message_board_client','ajax'));
		$wallDataForListings = array();
		$questionIds = array();
		$category_id_array = array();
		$RelatedClient = new RelatedClient();
		$ListingClientObj = new Listing_client();
		$this->userStatus = $this->checkUserValidation();
        if(!is_array($categories)){
            $categories = array();
        }

        //We will have to divide this into two parts
        //The first part will have questions asked on this listing only. So, we will call a function to get the Wall data of only such questions.
        $wallDataForListings = $this->getWallDataForListings($questionIds,$categories,'INSTITUTE',$institute->getId());
        $displayData['topicListings'] = $wallDataForListings;

        //Now, in the second part, we will fetch the related questions and then get the Wall of such questions    
		$questionIds = $ListingClientObj->checkListingQuestions($appId,$institute->getId());
		$questionIds = $questionIds['0'];
    	if(! (is_array($questionIds) && isset($questionIds[0])) ){
      		$keywordForQues = $RelatedClient->getKeywordForQues($institute->getId(), 'institute');
			$key = md5('questionIdArray_'.$institute->getId());
	        $questionIds = $this->getQuestionIdsForListing($keywordForQues,$institute->getId(),'institute');
	        //Checking for recently posted questions in Cache
			if(isset($_COOKIE[$key])){
        	    $recentlyAddedQuestion = array();
	            $recentlyAddedQuestion = $_COOKIE[$key];
        	    $recentlyAddedQuestion = explode(",",$recentlyAddedQuestion);
	            foreach($recentlyAddedQuestion as $quesId){
        	        array_push($questionIds,$quesId);
            	}
      		}
			$questionIdsTemp = array(); $j=0;
			if(in_array('1680073', $questionIds)){ 
				for($i=0; $i<count($questionIds);$i++) {
					if (!($questionIds[$i] == '1680073' || $questionIds[$i] == '1635456'))
					{
						$questionIdsTemp[$j] = $questionIds[$i]; $j++;
					}
				}
				$questionIds = $questionIdsTemp;
			}
			$questionIdsList = implode(",",$questionIds);
			if($questionIdsList!=''){
				$ListingClientObj->updateListingQuestions($appId,$institute->getId(),$questionIdsList,'All');
			}
		}else{
			$questionIds = $questionIds[0]['questionIds'];
			$questionIds = explode(",",$questionIds);
		}
		//Before calling for the wall data of AnA, we have to remove the Institute questions from the questions that arrived from Search.
		$this->load->model('QnAModel');
		$relatedQuestionList = $this->QnAModel->getOnlyRelatedQuestions(implode(",",$questionIds),$institute->getId());
		$questionIds = ($relatedQuestionList!='')?explode(",",$relatedQuestionList):array();
		//Now, get the wall data		
		$wallDataForListings = $this->getWallDataForListings($questionIds,$categories,'RELATED',$institute->getId());

        //Now get the other data and load the View files
		$pageKeyForAskQuestion = 'LISTING_INSTITUTEDETAIL_ASK_INSTITUTE_POSTQUESTION';
		 $this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$instituteId= $institute->getId() ;
		$courses=$this->_getCourses($instituteId);
		$institutes= $listingBuilder->getInstituteRepository()->findWithCourses(array($instituteId => $courses));
		foreach ($institutes as $institute){
		     $courses=$institute->getCourses();
		}
		$data['courses'] = $courses;
		$this->load->view('listing/widgets/ask_institute_form',array('categoryId' => $categories[0],'locationId' => $institute->getMainLocation()->getCountry()->getId(),'instituteId' => $institute->getId() , 'pageName' => 'institute','titleOfInstitute' => $institute->getName() ,'pageKeyForAskQuestion' => $pageKeyForAskQuestion,'questionIds' => $questionIds,'courses'=>$courses));
		$displayData['categoryId'] = $categories;
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
		$Validate = $this->userStatus;
        $displayData['instituteId'] = $institute->getId();
        $displayData['questionIds'] = array();
        $displayData['titleOfInstitute'] = $institute->getName();
        $displayData['pageKeySuffixForDetail'] = 'ASK_ASKHOMEPAGE_WALL_';
		$displayData['countryId'] = $institute->getMainLocation()->getCountry()->getId();
		$displayData['userId'] = $userId;
		$displayData['userGroup'] = $userGroup;
		$displayData['validateuser'] = $Validate;
        $displayData['relatedQuestions'] = false;
		$this->load->view('listing/widgets/ask&answerWall',$displayData);
        $displayData['questionIds'] = $questionIds;
        $displayData['relatedQuestions'] = true;
        $displayData['topicListings'] = $wallDataForListings;
        $this->load->view('listing/widgets/ask&answerWall',$displayData);
    }

	/**************
	 * Purpose: Function to fetch the related questions for an Institute
	 * This is currently getting called from AnA Related Widget on Overview tab and Related questions Wall in QnA Tab
	 * Input parameters: Institute Title, Institute Id and type
	 * Output parameters: Array of Related Question Ids
	 *************/
	function getQuestionIdsForListing($keywordForQues,$type_id,$listing_type) {
            $start = 0;
            $rows = '1000';
	    if($keywordForQues!=''){
		//API call to fetch related question ids:
		$this->load->builder('SearchBuilder', 'search'); //Load search builder
		$searchCommonLib = SearchBuilder::getSearchCommon(); //Get searchCommonLib instance
		$ids = $searchCommonLib->getSearchListingIdsByType($keywordForQues, 'question', $start, $rows); // Make call to get related question Ids.
		return (is_array($ids))?$ids:array();
	    }
	    else{
		return array();
	    }
        }
	
	function getWallDataForListings($questionIds=1,$categoryId=1,$type='ALL',$instituteId=0,$start=0,$count=10) {
		$this->init();
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$lastTimeStamp = date('Y-m-d');
		$messageBoard = new Message_board_client();
		
		if($type=='RELATED' && count($questionIds)==0){
		    $wallData = $messageBoard->getWallDataForListings($appId=1,$userId,$start,$count,$categoryId,$countryId=1,$threadIdCsv='1',$lastTimeStamp,$questionIds,$type,$instituteId);		    
		}
		else{
		    $this->load->model('QnAModel');
		    $wallData = $this->QnAModel->getWallDataForListingsByNewAlgorithm($appId=1,$userId,$start,$count,$categoryId,$countryId=1,$threadIdCsv='1',$lastTimeStamp,$questionIds,$type,$instituteId);
		}
		return $wallData;
	}
	
	

	/**************
	 * Purpose: Function to display the AnA Detailed widget on Listing overview tab.
	 * This widget will display the Detail pages of any 5 questions related to that institute
	 * Input parameters: Institute object
	 *************/
	function getInstituteRelatedQuestionDetails($institute){
		$this->load->helper(array('form', 'url', 'image_helper','shikshaUtility'));
		$this->load->library(array('relatedClient','listing_client','message_board_client','ajax'));
		$detailData = array();
		$questionIds = array();
		$ListingClientObj = new Listing_client();
		$this->userStatus = $this->checkUserValidation();
		$numberOfQuestionsToBeDisplayed = 5;
		$numberOfAnswerPerQuestion = 5;
		$numberOfCommentPerAnswer = 5;

		//Get the Related questions for the Institute. First check in DB, if questions found, then retreive them. Else, fetch them from Search
		$questionIds = $ListingClientObj->checkListingQuestions($appId,$institute->getId());
        //If the search again flag is set, it means make an AJAX call and then fetch the Search questions. We will not get them in real time.
        $displayData['searchAgainFlag'] = $questionIds['1'];
		$questionIds = $questionIds['0'];
		if(! (is_array($questionIds) && isset($questionIds[0])) ){
			$RelatedClient = new RelatedClient();
			$keywordForQues = $RelatedClient->getKeywordForQues($institute->getId(), 'institute');
			$questionIds = $this->getQuestionIdsForListing($keywordForQues,$institute->getId(),'institute');
			$questionIdsList = implode(",",$questionIds);
			if($questionIdsList!=''){
				$ListingClientObj->updateListingQuestions($appId,$institute->getId(),$questionIdsList,'All');
			}
		}else{
			$questionIds = $questionIds[0]['questionIds'];
			$questionIds = explode(",",$questionIds);
		}

		//Before calling for the question details of AnA, we have to remove the Institute questions from the questions that arrived from Search and also check if they are Live.
		$this->load->model('QnAModel');
		$relatedQuestionList = $this->QnAModel->getOnlyRelatedQuestions(implode(",",$questionIds),$institute->getId());
		$questionIds = ($relatedQuestionList!='')?explode(",",$relatedQuestionList):array();

		//Now, select any 5 random questions out of these QuestionIds
                $key = md5('getListingRelatedDetailQuestion'.$institute->getId());
		$cacheObj = new cacheLib();
                if(($cacheObj->get($key)=='ERROR_READING_CACHE')){
			shuffle($questionIds);
			for($i=0;$i<$numberOfQuestionsToBeDisplayed;$i++){
                if($questionIds[$i]){
    			    $topQuestions[$i] = $questionIds[$i];
                }
			}
			$topQuestions = implode(',',$topQuestions);
                        $cacheObj->store($key,$topQuestions,86400,'misc');

			//Whenever, we have selected new set of questions for display, we will also update the entry in institute_related table.
			//This is done so that this institute can be added in the incremental sitemap
			//Also, set this institute to be included in the Sitemap only if the number of related question is greater than what is displayed. Else, the same questions will be displayed and there is no need for SEO.
			if(count($questionIds)>$numberOfQuestionsToBeDisplayed){
			    $this->load->model('ListingModel');
			    $relatedQuestionList = $this->ListingModel->updateInstituteRefreshTime($institute->getId());
			}
                }else{
                        $topQuestions = $cacheObj->get($key);
                }

		//Sample questions with Rich content
		//$topQuestions = '1088146,1086597,1041263,1109716,1095097';

		//Now, get the Answers and comments for these questions
		//This is a real time data. But if we face performance issue, we can store this data like HTML files
		$detailData = ($topQuestions!='' && count($questionIds)>0)?$this->QnAModel->getInstituteRelatedQuestionDetails($appId=1,$topQuestions,$numberOfAnswerPerQuestion):array();

		//Now get the other data and load the View files
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$userGroup = isset($this->userStatus[0]['usergroup'])?$this->userStatus[0]['usergroup']:'normal';
		$Validate = $this->userStatus;
		$displayData['instituteId'] = $institute->getId();
		$displayData['titleOfInstitute'] = $institute->getName();
		$displayData['validateuser'] = $Validate;
		$displayData['questionIds'] = $questionIds;
		$displayData['topicListings'] = $detailData;
		$displayData['numberOfQuestionsToBeDisplayed'] = $numberOfQuestionsToBeDisplayed;
		$displayData['numberOfAnswerPerQuestion'] = $numberOfAnswerPerQuestion;
		$displayData['numberOfCommentPerAnswer'] = $numberOfCommentPerAnswer;
		$this->load->view('listingRelatedQuestions',$displayData);
	}
	
	/**************
	 * Purpose: Function to display the AnA Small widget on Listing overview and Alumni tab.
	 * This widget will display the Wall of AnA activities if activities are greater than 3. If not, we will simply display a Ask question widget
	 * Input parameters: Institute Id and Tab (Overview/Alumni). Tab is required because in different tabs, the width and height of the widget differs
	 * Note: We are doing 3 operations in this function ie. Fetch Institute details (Using repository), Fetch Institute questions wall (using HTML files) and Fetch Count data (Real time)
	 *************/
	function getDataForAnAWidget($instituteId,$tab='overview'){
	    echo '';
	    return;
	    $this->load->helper(array('form', 'url', 'image_helper','shikshaUtility'));
	    $this->load->library(array('listing_client','message_board_client','ajax'));
	    $appId =1;
	    $ListingClientObj = new Listing_client();

	    //Get the Institute repository and the institute object
            $this->load->builder('ListingBuilder','listing');
            $listingBuilder = new ListingBuilder;
            $instituteRepository = $listingBuilder->getInstituteRepository();
            $institute = $instituteRepository->find($instituteId);
	    
	    //Get the category for the Institute object
	    $categories = $instituteRepository->getCategoryIdsOfListing($instituteId,'institute');
	    if(!is_array($categories)){
		$categories = array('0');
	    }
	    
        //Check if a discussion exists on this Institute. If it does, get the data for this discussion and then show the widget. If not, we will display the usual QnA widget on the Overview tab of Listing detail page
        $this->load->model('QnAModel');
        $discussionDetails = $this->QnAModel->getInstituteDiscussionDetails($appId=1,$instituteId);
        if($discussionDetails){
                //Now, load the view file
                $displayData['tab'] = $tab;
                $displayData['instituteId'] = $institute->getId();
                $displayData['details'] = $institute;
                $displayData['topicListings'] = $discussionDetails;
                $displayData['validateuser'] = $this->checkUserValidation();
                $displayData['categoryId'] = $categories[0];
                echo ($this->load->view('listingDiscussionWidget',$displayData));
        }
        else{
   	    //Now, get the Wall for the questions asked on the institute. Also, store this as HTML in a file.
	    $anaWidgetFile = "ListingAnACache/anaWallData_".$instituteId.".html";
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
		$wallDataForListings = $this->getWallDataForListings(array(),array('0'),'INSTITUTE',$institute->getId(),0,6);
		$fp=fopen($anaWidgetFile,'w+');
		fputs($fp,serialize($wallDataForListings));
		fclose($fp);
	    }

	    //Get the count data for the Widget like Question count, Answer count and comment count
	    //This is a real time data, but if we face performance issues, we can store this is Cache as earlier.
	    $countData = array();
	    $countData = $ListingClientObj->getCountDataForAnAWidget($institute->getId());
	    
	    $courses = $this->_getCourses($instituteId);
	    $institutes= $listingBuilder->getInstituteRepository()->findWithCourses(array($instituteId => $courses));
	    foreach ($institutes as $institute){
		$courses=$institute->getCourses();
	    }
	    $displayData['courses'] = $courses;
	    //Now, load the view file 
	    $displayData['tab'] = $tab;
	    $displayData['countData'] = $countData;
	    $displayData['instituteId'] = $institute->getId();
	    $displayData['details'] = $institute;
	    $displayData['topicListings'] = $wallDataForListings;
	    $displayData['validateuser'] = $this->checkUserValidation();
	    $displayData['categoryId'] = $categories[0];
	    echo ($this->load->view('askNAnswer_widget',$displayData));
        }
	}
	
	    
	    private function _getCourses($institute_id){
		 $this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$this->instituteRepository = $listingBuilder->getInstituteRepository();
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
