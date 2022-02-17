<?php

class SearchPage extends ShikshaMobileWebSite_Controller {
	
	private $abroadCommonLibInstance;
	private $userid;
        private $abroadListingCommonLib;
    
        public function __construct() {
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
                
                $this->load->builder('ListingBuilder','listing');
		$listingBuilder 			= new ListingBuilder;
                $this->abroadCourseRepository 	= $listingBuilder->getAbroadCourseRepository();
                $this->abroadUniversityRepository 	= $listingBuilder->getUniversityRepository();
                            
	}
	
	public function index() {
              	$keyword = $_REQUEST['keyword'];
		$pageType = $_REQUEST['from_page'];
		$pageType = empty($pageType) ? 'searchPage' : $pageType;
		if(!empty($keyword)){
			if(ENABLE_ABROAD_SEARCH) // studyabroadconfig
			{
				$displayData 	= Modules::run('search/Search/getStudyAbroadSearchResults');
			}
			$keyword 	= preg_replace('#<script(.*?)>(.*?)</script>#', '', $_REQUEST['keyword']);
			$displayData['keyword'] 	= $keyword;
		}
	
		$displayData['keywordEncoded'] = base64_encode($keyword);
		$displayData['from_page'] = $pageType;
		$displayData['trackingId'] = $this->searchTracking($displayData['keywordEncoded'],$displayData['from_page'],$displayData['sa_course_count'],$displayData['university_count']);
		$coursePresentInCache=$this->_getCoursesObjectFromRepo($displayData);
                if(!$coursePresentInCache){
                    $displayData['courseIdsBySimilarCourse']=array();
                }
		$this->_getSeoDetails($displayData);
		$displayData['userShortlistedCourses'] = $this->fetchIfUserHasShortListedCourses();
		$displayData['userShortlistedCourses'] = $displayData['userShortlistedCourses']['courseIds'];
		
		
		$displayData['trackForPages'] = true; //For JSB9 Tracking
		//tracking
		$this->_prepareTrackingData($displayData);

        $displayData['rateMyChanceCtlr'] = Modules::load('rateMyChancePage/rateMyChance');
		$displayData['validateuser'] = $this->checkUserValidation();
		if($displayData['validateuser'] != "false"){
			$shikshaApplyLib = $this->load->library('rateMyChances/ShikshaApplyCommonLib');
			$displayData['userRmcCourses'] = $shikshaApplyLib->getUserRmcRatings($displayData['validateuser'][0]['userid']);
		}else{
			$displayData['userRmcCourses'] = array();
		}
		$displayData['isSearchPage'] = true;
		$displayData['compareCookiePageTitle'] = "Search Page";
		$displayData['compareLayerTrackingSource'] = 609;
		$displayData['compareButtonTrackingId'] = 662;
                $isNewSearch=$_REQUEST['newsearchpage'];
                $isNewSearch = empty($isNewSearch) ? 0 : (int)$isNewSearch;
                $displayData['isNewSearchPage']=$isNewSearch;
                $this->load->view('searchPage/searchOverview', $displayData);
  	}
        
        private function _prepareTrackingData(&$displayData)   
        {    
            
            $displayData['beaconTrackData'] = array(
                                              'pageIdentifier' => $displayData['pgType'],
                                              'pageEntityId' => '0',
                                              'extraData' => null
                                              );
        }
	
	public function loadCourseSearchResults(){
		$displayData = Modules::run('search/Search/getStudyAbroadSearchResults');
		$keyword = $_REQUEST['keyword'];
		$keyword = preg_replace('#<script(.*?)>(.*?)</script>#', '', $_REQUEST['keyword']);
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
		$rmcController = Modules::load('rateMyChancePage/rateMyChance');
		$validateuser = $this->checkUserValidation();
		if($validateuser != "false"){
			$shikshaApplyLib = $this->load->library('rateMyChances/ShikshaApplyCommonLib');
			$rmcCourses = $shikshaApplyLib->getUserRmcRatings($displayData['validateuser'][0]['userid']);
		}else{
			$rmcCourses = array();
		}
		$tuplehtml = "";
		if($returnData['totalCourseResultsOnCurrentPage'] > 0) {
                   
			$this->_getCoursesObjectFromRepo($displayData);
			$displayData['userShortlistedCourses'] = $this->fetchIfUserHasShortListedCourses();
			$displayData['userShortlistedCourses'] = $displayData['userShortlistedCourses']['courseIds'];
		
			foreach($displayData['courseIdsBySimilarCourse'] as $courseIds)
			{
				$courseObjArr = array();
				foreach($courseIds as $id){
				    $courseObjArr[] = $displayData['courseObj'][$id];
				}
				$dataArray = array(
					  'universityObject' => $displayData['uniObj'][$displayData['courseObj'][$id]->getUniversityId()],     
					  'courseObj' => $courseObjArr,
					  'identifier' => 'SearchListTuple',
					  'pageType' => 'searchPage_mob',
					  'rateMyChanceCtlr' => $rmcController,
					  'userRmcCourses' => $rmcCourses
					);
				$tuplehtml .= $this->load->view("categoryPage/widgets/categoryPageTuple",$dataArray,true);
			}
                }
		$returnData['html'] = base64_encode($tuplehtml);
		echo json_encode($returnData,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);    
                
        }
        
        public function _getCoursesObjectFromRepo(& $displayData){
            
            $totalCourseResultCount 		= $displayData['sa_course_count'];
            $courseResultsStartOffset 		= $displayData['course_result_offset'];
            $totalCourseResultsOnCurrentPage 	= count($displayData['courseList']);
            $courseList                         = $displayData['courseList'];
            $tuplePostion                       = $displayData['course_result_offset']+1;
            $courseIdsBySimilarCourse           = array();
            $allCourseIds                       = array();
            if($totalCourseResultCount > 0)
            {
                for($count=0; $count < $totalCourseResultsOnCurrentPage; $count++)
                {
                    $ids = array(); 
                    $ids[]          = $courseList[$count][0]['sa_course_id'];
                    $allCourseIds[] = $courseList[$count][0]['sa_course_id'];
                    for($iter=1; $iter < count($courseList[$count]); $iter++)
                    {
                            $ids[] = $courseList[$count][$iter]['sa_course_id'];
                            $allCourseIds[] = $courseList[$count][$iter]['sa_course_id'];
                    }
                    $courseIdsBySimilarCourse[$tuplePostion++] = $ids;               
                }
               $displayData['courseIdsBySimilarCourse']  = $courseIdsBySimilarCourse;
               $displayData['courseObj'] = $this->abroadCourseRepository->findMultiple(array_unique($allCourseIds));
               $univIds = array_map(function($obj) {
                if($obj instanceof AbroadCourse){
                     return $obj->getUniversityId();
                }else{
                     return 0;
                }
               
            }, $displayData['courseObj']);
               $univIds = array_values(array_unique($univIds));
               if(count($univIds)==1 && $univIds[0]==0){
                   return 0;
               }
               $displayData['counsellorData'] = $this->abroadListingCommonLib->checkIfUniversityHasCounsellor($univIds);
	       $displayData['uniObj'] = $this->abroadUniversityRepository->findMultiple($univIds);
	       return 1;
            }
        }
	
	function _getSeoDetails(& $displayData){
		$displayData['seoTitle'] 	= 'Search Education information ? Shiksha.com';
		$displayData['metaTitle'] 	= 'Search Education information ? Shiksha.com';
		$displayData['metaDescription'] = 'Search for all educational and career related information includes latest educational news, articles, exams, colleges, university, institute, courses, notifications, exam dates, and more';
		$displayData['pgType']	        = 'searchPage';
		$displayData['canonicalURL']    = '';
		$displayData['robots']          = 'All';
	}
	
	public function fetchIfUserHasShortListedCourses(){
	    $validity = $this->checkUserValidation ();
	    $data = array();
	    if (! (($validity == "false") || ($validity == ""))) {
		    $data ['userId'] = $validity [0] ['userid'];
	    } 
	    $shortlistListingLib = $this->load->library ( 'listing/ShortlistListingLib' );
	    return $shortlistListingLib->fetchIfUserHasShortListedCourses ( $data);
	    
	}
	
	
	function searchTracking($keyword,$pageType,$courseResultCount = 0,$universityResultCount = 0,$courseResultPageNo = 1,$universityResultPageNo = 1,$tuplePosition = 0,$tupleType,$id =0,$isAjax = false) {
		$validity = $this->checkUserValidation ();
		if (! (($validity == "false") || ($validity == ""))) {
			$data ['userId'] = $validity [0] ['userid'];
		}
		$action = 'insert';
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
		//$trackingId = $this->searchModel->insertIntoSearchTracking($data,$action);
		$this->searchpagelib = $this->load->library('searchPage/SearchPageLib');
		$trackingId =  $this->searchpagelib->insertIntoSearchTracking($data,$action);
		if($isAjax) {
			echo $trackingId;
		}
		return $trackingId;
	}
}        
