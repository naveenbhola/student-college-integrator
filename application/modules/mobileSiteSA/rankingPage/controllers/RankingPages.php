<?php 
class RankingPages extends MX_Controller {
    private $rankingLib;
    private $userStatus;
    private $validateUser;
    private $rankingPageBuilder;
    private $rankingPageRepository;
    private $checkIfLDBUser;
    
    public function __construct()
        {
            parent::__construct();
            $this->validateUser = $this->checkUserValidation();
            
            if($this->validateUser !== 'false') {
                $this->load->model('user/usermodel');
                $usermodel = new usermodel;
                
                $userId 	= $this->validateUser[0]['userid'];
                $user 	= $usermodel->getUserById($userId);
                if(!is_object($user))
                {
                     $loggedInUserData = false;
                     $this->checkIfLDBUser = 'NO';
                }
                else
                {
                    $name = $user->getFirstName().' '.$user->getLastName();
                    $email = $user->getEmail();
                    $userFlags = $user->getFlags();
                    $isLoggedInLDBUser = $userFlags->getIsLDBUser();
                    $this->checkIfLDBUser = $isLoggedInLDBUser;
                    $pref = $user->getPreference();
                    if(is_object($pref)){
                        $desiredCourse = $pref->getDesiredCourse();
                    }else{
                        $desiredCourse = null;
                    }
                    $loc = $user->getLocationPreferences();
                    $isLocation = count($loc);
                    $loggedInUserData = array('userId' => $userId, 'name' => $name, 'email' => $email, 'isLDBUser' => $isLoggedInLDBUser, 'desiredCourse' => $desiredCourse, 'isLocation'=>$isLocation);
                }
            }
            else {
                $loggedInUserData = false;
                $this->checkIfLDBUser = 'NO';
            }
            $this->userStatus = $loggedInUserData;
            $this->load->builder('RankingPageBuilder', 'abroadRanking');
            $this->rankingPageBuilder = new RankingPageBuilder;
            $this->rankingLib 	= $this->rankingPageBuilder->getRankingLib();
            $this->rankingPageRepository = $this->rankingPageBuilder->getRankingPageRepository($this->rankingLib);
        }
       private function _validateRankingId($rankingId)
       {
         if(strpos($rankingId, '-') === false) {
                $rankingId = $rankingId;            
                $pageNumber = 1;
            } else {
                $rawData = explode("-", $rankingId);
                $rankingId = $rawData[0];            
                $pageNumber = $rawData[1];
            }
            $rankingParams['rankingId']= $rankingId;
            $rankingParams['pageNumber']= $pageNumber;

            return $rankingParams;
       }


        public function rankingPage($rankingId,$sliceNumber = 0,$ajaxCall=false)
        {

            $rankingParams = $this->_validateRankingId($rankingId);
            $rankingId  = $rankingParams['rankingId'];
            $pageNumber = $rankingParams['pageNumber'];

            $rankingPageObject  = $this->rankingPageRepository->find($rankingId);
            
            if(!$rankingPageObject){
                    show_404_abroad();
            }
            
            $rankingPageObject  = reset($rankingPageObject);
            $recommendedUrl     = $this->rankingLib->getRankingUrl($rankingPageObject);
            
            $this->RankingLib                     = $this->load->library('rankingPage/rankingPagesLib');
            
            //URL validation  
            if(!$ajaxCall)
            {
               $this->RankingLib->validateMobileRankingPageURL($recommendedUrl,$pageNumber);    
            }      
            
            if($this->rankingLib->isZeroResultPage($rankingPageObject))
            {
                 $abroadCategoryPageReqObj       = $this->load->library('categoryList/AbroadCategoryPageRequest');
                 $url = $abroadCategoryPageReqObj->getURLForCountryPage($rankingPageObject->getCountryId());
                 redirect($url, 'location', 301);
            }
            
            $displayData = array();
            $displayData['trackForPages'] = true; //For JSB9 Tracking
            $displayData = array_merge($displayData,array('validateuser'=>$this->validateUser,'loggedInUserData'=>$this->userStatus,'checkIfLDBUser'=>$this->checkIfLDBUser));
            $displayData['rankingId'] = $rankingId;
            
            // Get SEO related data
            $seoData                            = $this->rankingLib->getSeoInfo($rankingPageObject);
            $seoData['canonicalUrl']            = $recommendedUrl;
            
            $displayData['rankingPageObject']   = $rankingPageObject;
            if($rankingPageObject->getType() != 'university')
            {
                //Handle Deleted University Case From the Ranking Page Object
               $rankingPageDataArr                  = $rankingPageObject->getRankingPageData(); 
               $this->rankingLib->deletedUniversityRemoval($rankingPageDataArr,$rankingPageObject,$rankingId);
               $displayData['rankingLibObj']        = $this->rankingLib;
               $displayData['rankingPageObject']    = $rankingPageObject;
            }
           

            //tracking for page view and registration
      	    $this->_prepareTrackingData($displayData);
         
            $rankingPageDataArray = $rankingPageObject->getRankingPageData();
            $totalRankingTuplesCount = count($rankingPageDataArray);
            $displayData['totalRankingTuplesCount'] = $totalRankingTuplesCount;
            if(!$ajaxCall)
            {
                $this->RankingLib->validatePaginationNumber($recommendedUrl,$pageNumber,$totalRankingTuplesCount,$sliceNumber);
                $this->rankingLib->setNextPrevUrls($recommendedUrl,$pageNumber,$displayData);
                $this->_prepareDataForTuplesNonAjax($pageNumber,$rankingPageDataArray,$sliceNumber,$displayData,$rankingPageObject,$recommendedUrl);
                $this->getDownloadBrochureData($displayData, $rankingPageObject->getType());
            }
            else//handle the case of load more via ajax
            {
                $rankingPageData = array_slice($rankingPageDataArray,$sliceNumber,RANKING_PAGE_TUPLE_COUNT);
                $displayData['rankingTuplesCount']  = $sliceNumber+count($rankingPageData);
                $displayData['rankingPageData']     = $rankingPageData;
                $displayData['startRank']           = $sliceNumber+1;
                $this->getDownloadBrochureData($displayData, $rankingPageObject->getType());
                
                 if($rankingPageObject->getType()=='course'){
                    $displayData['rankingCoursesFeesData'] = $this->rankingLib->processCoursesFees($displayData['rankingPageData']);
                    echo $this->load->view("widgets/courseRankingPageTuples",$displayData,true);   
                    return; 
                 }
                 else
                 {
                    echo $this->load->view("widgets/universityRankingPageTuples",$displayData,true);    
                    return;
                 }
            }
            
            $displayData['seoData']             = $seoData;
                    
            $this->load->view("rankingOverview",$displayData);
        }    

       private function getDownloadBrochureData(&$displayData, $type) {

            $this->load->library('listing/AbroadListingCommonLib');
            $this->abroadListingCommonLib = new AbroadListingCommonLib();

            $brochureData = array();
            $rankingPageData = $displayData['rankingPageData'];

            foreach($rankingPageData as $data) {

                if($type=='course') {
                    /* prepare data now required for new single registration form */
                    $catData = $this->abroadListingCommonLib->getCategoryOfAbroadCourse($data['course']->getId());
                    $courseData = array( $data['course']->getId() => array(
                        'desiredCourse' => ($data['course']->getDesiredCourseId()?$data['course']->getDesiredCourseId():$data['course']->getLDBCourseId()),
                        'paid'      => $data['course']->isPaid(),
                        'name'      => $data['course']->getName(),
                        'subcategory'   => $catData['courseSubCategoryId']
                        )
                    );

                    $brochureDataObj = array(
                        'sourcePage'       => 'course_ranking',
                        'courseId'         => $data['course']->getId(),
                        'courseName'       => $data['course']->getName(),
                        'universityId'     => $data['course']->getUniversityId(),
                        'universityName'   => $data['university']->getName(),
                        'destinationCountryId'  => $data['university']->getLocation()->getCountry()->getId(),
                        'destinationCountryName'    => $data['university']->getLocation()->getCountry()->getName(),
                        'courseData'       => base64_encode(json_encode($courseData)),
                        'widget'           => 'tuple',
                        'trackingPageKeyId'=> 1759,
                        'mobile'           => true
                        );

                    $brochureData[$data['course']->getId()] = $brochureDataObj;
                } else if($type=='university') {
                    $brochureDataObj = array(
                        'sourcePage'       => 'university_ranking',
                        'universityId'     => $data['university']->getId(),
                        'universityName'   => $data['university']->getName(),
                        'destinationCountryId'  => $data['university']->getLocation()->getCountry()->getId(),
                        'destinationCountryName'=> $data['university']->getLocation()->getCountry()->getName(),
                        'widget'           => 'tuple',
                        'trackingPageKeyId'=> 1757,
                        'mobile'           => true
                        );
                    $brochureData[$data['university']->getId()] = $brochureDataObj;
                }
            }
            $displayData['brochureDataObjList'] = $brochureData;
        }

        private function _prepareDataForTuplesNonAjax($pageNumber,$rankingPageDataArray,$sliceNumber,&$displayData,$rankingPageObject,$recommendedUrl)
        {
            //handle data and pagination
                if($pageNumber==1)
                {
                    $rankingPageData                    = array_slice($rankingPageDataArray,$sliceNumber,RANKING_PAGE_FIRSTPAGE_COUNT);
                    $displayData['rankingTuplesCount']  = $sliceNumber+count($rankingPageData);
                    $displayData['rankingPageData']     = $rankingPageData;
                    $displayData['startRank']           = $sliceNumber+1;
                }
                else
                {           

                    $sliceNumber = (RANKING_PAGE_FIRSTPAGE_COUNT+(($pageNumber-2)*RANKING_PAGE_TUPLE_COUNT)); 
                    $rankingPageData                    = array_slice($rankingPageDataArray,$sliceNumber,RANKING_PAGE_TUPLE_COUNT);
                    $displayData['rankingTuplesCount']  = $sliceNumber+count($rankingPageData);
                    $displayData['rankingPageData']     = $rankingPageData;
                    $displayData['startRank']           = $sliceNumber+1;
                    $seoData['canonicalUrl']            = $recommendedUrl."-".($pageNumber);
                }   

                if($rankingPageObject->getType()=='course')
                {
                    $displayData['rankingCoursesFeesData'] = $this->rankingLib->processCoursesFees($displayData['rankingPageData']);
                }
        }
        private function _prepareTrackingData(&$displayData,$rankingId)   
        { 
            $rankType = $displayData['rankingPageObject']->getType();
            $rankId = $displayData['rankingPageObject']->getId();
            $displayData['beaconTrackData'] = array(
                                              'pageIdentifier' => $rankType.'RankingPage',
                                              'pageEntityId' => $rankId,
                                              'extraData' => array(
                                                                    'categoryId' => $displayData['rankingPageObject']->getParentCategoryId(),
                                                                    'subCategoryId' => $displayData['rankingPageObject']->getSubCategoryId(),
                                                                    'LDBCourseId' => $displayData['rankingPageObject']->getLDBCourseId(),
                                                                    //'cityId' => 0,
                                                                    //'stateId' => 0,
                                                                    'countryId' => $displayData['rankingPageObject']->getCountryId()
                                                                    //'courseLevel' => $courseLevel
                                                                )
                                              );
        } 
}?>
