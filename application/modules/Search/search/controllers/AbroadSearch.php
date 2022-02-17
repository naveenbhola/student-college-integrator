<?php

class AbroadSearch extends MX_Controller {
	
	private $abroadCommonLibInstance;
	private $userid;
        private $abroadListingCommonLib;
	
	public function __construct() {
		$this->load->library('search/Abroad/AbroadCommonLib');
		$this->abroadCommonLibInstance = new AbroadCommonLib();
		$this->load->library('listing/AbroadListingCommonLib');
		$this->abroadListingCommonLib = new AbroadListingCommonLib();
		$this->load->helper(array('form', 'url'));
		//To check whether user is logged in or not.
		$this->userStatus = $this->checkUserValidation();
		if(isset($this->userStatus[0]) && is_array($this->userStatus[0])) {
		    $this->userid = $this->userStatus[0]['userid'];
		} else {
		    $this->userid = -1;
		}
	}
	
	private function _init(& $displayData) {
	}
	
	public function index() {
        $this->userPref = Modules::run('listing/abroadListings/getUserStartTimePrefWithExamsTaken', $this->userStatus);
		$this->_init();
		$keyword = $this->input->get('keyword',true);
		if(!isset($keyword) || $keyword==''){
			$keyword = $this->input->post('keyword',true);
		}

		$pageType = $this->input->get('from_page',true);
		$pageType = empty($pageType) ? 'searchPage' : $pageType;
		if(!empty($keyword)){
			if(ENABLE_ABROAD_SEARCH) // studyabroadconfig
			{
				$displayData 	= Modules::run('search/Search/getStudyAbroadSearchResults');
			}
			$keyword 		= preg_replace('#<script(.*?)>(.*?)</script>#', '', $keyword);
			$displayData['keyword'] 	= $keyword;
        }
		$displayData['validateuser'] = $this->userStatus;
        $displayData['userPref'] = $this->userPref;
		if($displayData['validateuser'] !== 'false') {
			$this->load->model('user/usermodel');
			$usermodel 	= new usermodel;
			$userId 	= $displayData['validateuser'][0]['userid'];
			$user 		= $usermodel->getUserById($userId);
			$name 		= $user->getFirstName().' '.$user->getLastName();
			$email 		= $user->getEmail();
			$userFlags 	= $user->getFlags();
			$isLoggedInLDBUser = $userFlags->getIsLDBUser();
			$displayData['loggedInUserData'] = array('userId' => $userId, 'name' => $name, 'email' => $email, 'isLDBUser' => $isLoggedInLDBUser);
			
			$pref = $user->getPreference();
			$loc = $user->getLocationPreferences();
			$isLocation = count($loc);
			if(is_object($pref)){
			    $desiredCourse = $pref->getDesiredCourse();
			}else{
			    $desiredCourse = null;
			}
			$displayData['loggedInUserData']['desiredCourse'] = $desiredCourse;
			$displayData['loggedInUserData']['isLocation'] = $isLocation;
		} else {
			$displayData['loggedInUserData'] = false;
		}
		$displayData['keywordEncoded'] = base64_encode($keyword);
		$displayData['from_page'] = $pageType;
		$displayData["abroadListingCommonLib"] = $this->abroadListingCommonLib;
		$displayData['trackingId'] = $this->searchTracking($displayData['keywordEncoded'],$displayData['from_page'],$displayData['sa_course_count'],$displayData['university_count']);
		
		//tracking
        $this->_prepareTrackingData($displayData);
		$this->compareCourseLib = $this->load->library('studyAbroadCommon/compareCoursesLib');
		$displayData['userComparedCourses'] = $this->compareCourseLib->getUserComparedCourses();
		$displayData['compareCookiePageTitle'] = "Search Page";
		$displayData['compareOverlayTrackingKeyId'] = 591;
		$displayData['compareButtonTrackingId'] = 656;
		$displayData['trackForPages'] = true;
		$this->load->view('search/abroad/search', $displayData);
	}



	private function _prepareTrackingData(&$displayData) 
    {
            $displayData['beaconTrackData'] = array(
                                                'pageIdentifier' => 'searchPage',
                                                'pageEntityId' => '0',
                                                'extraData' => null
                                                );                
    }


        
         public function loadCourseSearchResults(){
                $displayData = Modules::run('search/Search/getStudyAbroadSearchResults');
                $keyword = $this->input->get('keyword',true);
				if(!isset($keyword) ||  $keyword==''){
					$keyword = $this->input->post('keyword',true);
				}	
                $keyword = preg_replace('#<script(.*?)>(.*?)</script>#', '', $keyword);
                $returnData = array();
                $returnData['totalCourseResultGroupCount'] =     $displayData['sa_course_group_count'];
                $returnData['totalCourseResultCount'] =     $displayData['sa_course_count'];
                $returnData['courseResultsStartOffset'] =   $displayData['course_result_offset'];
                $returnData['totalCourseResultsOnCurrentPage'] = count($displayData['courseList']);
                $returnData['nextPageStart'] = $returnData['courseResultsStartOffset'] + $returnData['totalCourseResultsOnCurrentPage'];
                $resultLeft = $returnData['totalCourseResultGroupCount'] - $returnData['nextPageStart'];
                $tuplePostion = $displayData['course_result_offset']+1;
                if($resultLeft >= $returnData['totalCourseResultsOnCurrentPage']) {
			$resultLeft = $returnData['totalCourseResultsOnCurrentPage'];
		}
		$returnData['resultLeft'] 	= $resultLeft;
		$html = "";
		if($returnData['totalCourseResultsOnCurrentPage'] > 0) {
			$this->compareCourseLib = $this->load->library('studyAbroadCommon/compareCoursesLib');
			$comparedCourses =  $this->compareCourseLib->getUserComparedCourses();
            for($count=0; $count < $returnData['totalCourseResultsOnCurrentPage']; $count++) {
				$courseData['course'] = $displayData['courseList'][$count][0];
				$courseData['tuplePostion'] = $tuplePostion++;
				$courseData['count'] = count($displayData['courseList'][$count]) - 1;
				$fees = $courseData['course']['sa_course_fees_value']+$courseData['course']['sa_course_room_board']+$courseData['course']['sa_course_insurance']+$courseData['course']['sa_course_transportation']+$courseData['course']['sa_course_custom_fees'];
				$fees = $this->abroadListingCommonLib->convertCurrency($courseData['course']['sa_course_fees_currency'], 1, $fees);
				$courseData['fees'] = $this->abroadListingCommonLib->getIndianDisplableAmount($fees, 1);
				$courseData['publicclass'] = $courseData['course']['sa_course_university_type'] === "1"? '&#10004':'&times';
				$courseData['scholarshipclass'] = $courseData['course']['sa_course_scholarship'] === "1" ? '&#10004':'&times';
				$courseData['accomodationclass'] = $courseData['course']['sa_course_university_accomodation'] == "1" ? '&#10004':'&times';
				$courseData['validateuser'] = $this->userStatus;
				$courseData['userPref'] = $this->userPref;
				$courseData['similarCourses'] = array(); 
				for($iter=1; $iter < count($displayData['courseList'][$count]); $iter++){
					$subCourseData['course'] = $displayData['courseList'][$count][$iter];
					$subCourseData['publicclass'] = $subCourseData['course']['sa_course_university_type'] == 1? '&#10004':'&times';
					$subCourseData['scholarshipclass'] = $subCourseData['course']['sa_course_scholarship'] == 1 ? '&#10004':'&times';
					$subCourseData['accomodationclass'] = $subCourseData['course']['sa_course_university_accomodation'] == 1 ? '&#10004':'&times';
					$subCourseData['validateuser'] = $this->userStatus;
					$subCourseData['userPref'] = $this->userPref;
					$fees = $subCourseData['course']['sa_course_fees_value']+$subCourseData['course']['sa_course_room_board']+$subCourseData['course']['sa_course_insurance']+$subCourseData['course']['sa_course_transportation']+$subCourseData['course']['sa_course_custom_fees'];
					$fees = $this->abroadListingCommonLib->convertCurrency($subCourseData['course']['sa_course_fees_currency'], 1, $fees);
					$subCourseData['fees'] = $this->abroadListingCommonLib->getIndianDisplableAmount($fees, 1);
					$courseData['similarCourses'][] = $subCourseData;
				}
				
				$courseData['userComparedCourses'] = $comparedCourses;
				$html .= $this->load->view('abroad/search_course_tuple', $courseData, true);
            }
        }
		$returnData['html'] = $html;
		echo json_encode($returnData);    
    }
        
	public function loadUniversitySearchResults() {
		$displayData 	= Modules::run('search/Search/getStudyAbroadSearchResults');
		$keyword = $this->input->get('keyword',true);
		if(!isset($keyword) ||  $keyword==''){
			$keyword = $this->input->post('keyword',true);
		}	
		$keyword  		= preg_replace('#<script(.*?)>(.*?)</script>#', '', $keyword);
		$returnData 	= array();
		$returnData['totalUniversityResultCount'] 			= $displayData['university_count'];
                $returnData['universityCourseCount']  = $this->abroadListingCommonLib->getCourseCountOfUniversities($displayData['university_id']);
//		_p($returnData);die();
                $returnData['universityResultsStartOffset'] 		= $displayData['university_result_offset'];
                $tuplePostion = $displayData['university_result_offset']+1;
		$returnData['totalUniversityResultsOnCurrentPage'] 	= count($displayData['universities']);
		$returnData['nextPageStart'] 						= $returnData['universityResultsStartOffset'] + $returnData['totalUniversityResultsOnCurrentPage'];
		$resultLeft 										= $returnData['totalUniversityResultCount'] - $returnData['nextPageStart'];
		if($resultLeft >= $returnData['totalUniversityResultsOnCurrentPage']) {
			$resultLeft = $returnData['totalUniversityResultsOnCurrentPage'];
		}
		$returnData['resultLeft'] 	= $resultLeft;
		$html = "";
		if($returnData['totalUniversityResultsOnCurrentPage'] > 0) {
            for($count=0; $count < $returnData['totalUniversityResultsOnCurrentPage']; $count++) {
				$universityData['university'] = $displayData['universities'][$count];
				$universityData['tuplePostion'] = $tuplePostion++;
				$html .= $this->load->view('abroad/search_university_tuple', $universityData, true);
			}
        }
		$returnData['html'] 		= $html;
		echo json_encode($returnData);
	}
	
	function searchTracking($keyword,$pageType,$courseResultCount = 0,$universityResultCount = 0,$courseResultPageNo = 1,$universityResultPageNo = 1,$tuplePosition = 0,$tupleType,$id =0,$isAjax = false) {
		$validity = $this->checkUserValidation ();
		if (! (($validity == "false") || ($validity == ""))) {
			$data ['userId'] = $validity [0] ['userid'];
		}
		$action = 'insert';
		$this->load->model("search/SearchModel", "", true);
		$this->searchModel = new SearchModel();
		$data['keyword']  = base64_decode($keyword);
		$data['pageType'] = $pageType;
		$data['sessionId'] = sessionId ();
		$data['userId'] =  empty($data['userId']) ? 0 : $data['userId'];
		$data['courseResultCount']  = empty($courseResultCount)?0:$courseResultCount;
		$data['universityResultCount']  = empty($universityResultCount)?0:$universityResultCount;
		$data['courseResultPageNo'] = $courseResultPageNo;
		$data['universityResultPageNo'] = $universityResultPageNo;
		$data['positionOfTuple'] = $tuplePosition;
		$data['typeOfTuple'] = $tupleType;
		$data['searchTime'] = date('Y-m-d H:m:s');
		if($id > 0) {
			$data['id'] = $id;
			$action = 'update';
		}
		$trackingId = $this->searchModel->insertIntoSearchTracking($data,$action);
		if($isAjax) {
			echo $trackingId;
		}
		return $trackingId;
	}	
	
}
