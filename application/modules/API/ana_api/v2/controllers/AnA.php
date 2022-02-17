<?php
/**
 * AnA Class
 * This is the class for all the APIs related to AnA Like Unanswered Tab, Question detail pages, Posting questions/answers/comments
 * @date    2016-05-12
 * @author  Romil Goel
 * @todo    none
*/

class AnA extends APIParent {

        private $validationObj;
        private $anaCommonLib;

        function __construct() {
                parent::__construct();
                $this->load->library(array('v1/AnAValidationLib', 'v1/AnACommonLib'));
                $this->validationObj = new AnAValidationLib();
                $this->anaCommonLib = new AnACommonLib();
        }

                /**
         * @desc API to fetch Homepage News feed of the User
         * @param AuthChecksum containing the logged-in user details
         * @param start value with the Starting counter of Questions
         * @param count value with the number of questions to be fetched
         * @return JSON string with HTTP Code 200 and News feed items
         * @return JSON string with HTTP Code 200 and Message Failure if User id is not valid
         * @date 2016-05-12
         * @author Romil Goel
         */
        function getHomepageData($start = 0, $pageNumber = 0, $filter = 'home'){
            //step 1:Fetch the Input from GET/POST
            $Validate = $this->getUserDetails();
            $userId = isset($Validate['userId'])?$Validate['userId']:'';
            $visitorId = isset($Validate['visitorId'])?$Validate['visitorId']:'';

            //step 2:validate all the fields
            if(! $this->validationObj->validateHomepageTab($this->response, array('userId'=>$userId, 'start'=>$start, 'pagenumber'=>$pageNumber))){
                return;
            }

            //Step 3: Fetch the Data from DB + Logic

            if($pageNumber == 0){
                //A. Get User details
		$this->benchmark->mark('get_user_details_start');
                $returnArray['userDetails'] = $this->anaCommonLib->getUserDetails($userId);
		$this->benchmark->mark('get_user_details_end');
                $returnArray['canStartDiscussion'] = $returnArray['userDetails']['levelId'] >= 11 ? true : false;
                
                //F. check user profile Builder info
                $this->load->library('v1/UserProfileBuilderLib');
                $this->profileBuilderObj = new UserProfileBuilderLib();
		$this->benchmark->mark('get_profile_builder_start');
                $returnArray['profileBuilderInfo'] = $this->profileBuilderObj->getProfileBuilderInfo($userId);
		$this->benchmark->mark('get_profile_builder_end');
            }
            
            // loading data of first page in two parts 3+7
            $totalResponsePerRequest = -1;
            if($pageNumber == 0){
                if($start == 0){
                    $totalResponsePerRequest = 3;
                }else{
                    $totalResponsePerRequest = 7;
                }
            }
            //B. Get Homepage entities
	    $this->benchmark->mark('get_homepage_data_start');
            $homePageRelatedData                = $this->anaCommonLib->getHomepageData($userId, $start, $pageNumber, $filter, 0, $visitorId, $totalResponsePerRequest);
	    $this->benchmark->mark('get_homepage_data_end');
            $returnArray['homepage']            = $homePageRelatedData['homeFeed'];
            $returnArray['nextPaginationIndex'] = $homePageRelatedData['nextPaginationIndex'];
            
            if($homePageRelatedData['newHomeFeedItems'] > 5){
                $returnArray['moreStoriesCount']    = $homePageRelatedData['newHomeFeedItems'];
            }
            
            if($pageNumber == 1 && $filter == 'home'){
                //C. Get Tags Recomemndations : Commented below code as it will not go live in this release 26th Nov 2015
		$this->benchmark->mark('get_tag_reco_start');
                $returnArray['tagsRecommendations'] = $this->anaCommonLib->getTagsRecommendations($userId);
		$this->benchmark->mark('get_tag_reco_end');
                
                //E. Set some common parameters: Commented below code as it will not go live in this release 26th Nov 2015
                $returnArray['showTagsRecommendationsAtPostion'] = 1;
            }

            if($pageNumber == 2 && $filter == 'home'){
                //D. Get User Recommendations : Commented below code as it will not go live in this release 26th Nov 2015
		$this->benchmark->mark('get_user_reco_start');
                $returnArray['userRecommendations'] = $this->anaCommonLib->getUserRecommendations($userId);
		$this->benchmark->mark('get_user_reco_end');
                $returnArray['showUserRecommendationsAtPostion'] = 1;
            }

            // set view count duration for question/answer tracking
            $returnArray['viewTrackParams'] = array("question" => QUESTION_VIEW_DURATION, "answer" => ANSWER_VIEW_DURATION, "trackDuration" => VIEWCOUNT_TRACKING_INTERVAL, "trackingPageType" => "anaHomepage");

            $this->response->setBody($returnArray);

            // track home feed served
	    $this->benchmark->mark('track_api_start');
            $this->_trackAPI($returnArray, "HomefeedTrack");
	    $this->benchmark->mark('track_api_end');

            $noDataAvailableMsg = array("home" => "No Stories for you.", "discussion" => "No Discussions for you.", "unanswered" => "No Un-answered questions for you.");
            if(empty($returnArray['homepage'])){
                $this->response->setResponseMsg($noDataAvailableMsg[$filter]);
            }

            //Step 4: Return the Response
            $this->response->output();
        }
}
