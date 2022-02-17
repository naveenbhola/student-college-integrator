<?php
/**
 * Tags Class
 * This is the class for all the Tags APIs Like Detail page
 * @date    2015-08-27
 * @author  Ankur Gupta
 * @todo    none
*/

class Tags extends APIParent {

        private $validationObj;
	private $commonObj;

        function __construct() {
                parent::__construct();
                $this->load->library(array('TagsValidationLib','TagCommonLib'));
                $this->validationObj = new TagsValidationLib();
		        $this->commonObj = new TagCommonLib();
        }

        /**
         * @desc API to Fetch the Content to be displayed on the Tag detail page
         * @param GET param contentType which will be the type of the content to be shown (question/discussion/unanswered)
         * @param GET param start which will be the starting number
         * @param GET param count which will be the count of entities to be shown
         * @param GET param sorting which will be sorting which needs to be applied (rating/oldest/youngest)
         * @return JSON object with HTTP Code 200 and Object of all entities
         * @date 2015-08-27
         * @author Ankur Gupta
         */
        function getTagDetailPage($tagId, $contentType = "question", $start = 0, $count = 10, $sorting = "Newest"){
        
            //step 1:Fetch the Input from GET/POST
            $Validate = $this->getUserDetails();
            $userId = isset($Validate['userId'])?$Validate['userId']:'';
            
            //step 2:validate all the fields
            if(! $this->validationObj->validateGetTagDetailPage($this->response, array('userId'=>$userId, 'tagId'=>$tagId, 'contentType'=>$contentType, 'start'=>$start, 'count'=>$count, 'sorting'=>$sorting))){
                    return;
            }
        
            //Step 3: Send the Input to Backend and make the changes
    	    $result = array();
    	    $tagFound = true;
            $this->load->model('TagsModel');

    	    global $isWebAPICall;
            global $webAPISource;
    	    if(( ($contentType == "all" || $contentType == "question") && $start == 0) || $isWebAPICall == 1){
               //Store the tag details results in Cache    
               //$result = $this->TagsModel->getTagDetails($tagId);
               $this->load->library("common_api/APICommonCacheLib");
               $apiCommonCacheLib = new APICommonCacheLib();
               $tagResult = $apiCommonCacheLib->getTagStats($tagId);
               if(!empty($tagResult)){
                  $result = array();
                  $res = json_decode($tagResult[0],true);
                  $result['tagName'] = $res['tagName'];
                  $result['tagType'] = $res['tagType'];
                  $result['description'] = $res['description'];
                  $result['questionCount'] = $res['questionCount'];
                  $result['discussionCount'] = $res['discussionCount'];
                  $result['followerCount'] = $res['followerCount'];
                  $result['expertCount'] = $res['expertCount'];
               }
               else{
                  $result = $this->TagsModel->getTagDetails($tagId);
                  if(isset($result['questionCount']) && $result['questionCount'] > 1000 && $result['followerCount'] > 1000){
                     $tagsData = array(
                           "tagName"            => $result['tagName'],
                           "tagType"            => $result['tagType'],
                           "description"        => $result['description'],
                           "questionCount"      => $result['questionCount'],
                           "discussionCount"    => $result['discussionCount'],
                           "followerCount"      => $result['followerCount'],
                           "expertCount"        => $result['expertCount']
                     );
                     $apiCommonCacheLib->insertTagStats($tagId, array(json_encode($tagsData)));
                  }
               }

        		if(is_array($result) && count($result) == 0){
        			$tagFound = false;
        		}
        	
		if($userId != '' && $userId > 0){
                	$isUserFollowing = $this->TagsModel->isUserFollowingTag($userId, array($tagId));
	        	$result['isUserFollowing'] = $isUserFollowing[$tagId];
		}
		else{
			$result['isUserFollowing'] = 'false';
		}
    	    }

    	    if($tagFound){
    	        $result['content'] = $this->TagsModel->getDetailPageContent($tagId, $contentType, $start, $count, $sorting, $userId);

        		if($start == 0){

                    // Don't fetch Top contributors for Desktop Calls
                    if(!($isWebAPICall == 1 && $webAPISource == 'desktop')){
                        $topContributors = $this->commonObj->getTopContributorsForTag($tagId, $result['tagName'], $userId);
                        if($topContributors)
                            $result = array_merge ( $result, $topContributors);
                    }

            		//Get Related Tags
        			$result['relatedTags'] = $this->commonObj->getRelatedTags($tagId, $userId);
        		        $result['showActiveUserRecommendationsAtPostion'] = 4;
                		$result['showTagsRecommendationsAtPostion'] = 9;

                        if(empty($result['content'])){
                            $noInfoAvailableText = $this->config->item("noInfoAvailableText");
                            $noInfoText = $noInfoAvailableText[$contentType] ?  $noInfoAvailableText[$contentType] : NO_INFO_AVAILABLE;
                            $this->response->setResponseMsg($noInfoText);
                        }
        		}
    	    }
    	    else{	//If tag details are not found, return error
                    $this->response->setStatusCode(STATUS_CODE_FAILURE);
                    $this->response->setResponseMsg("Tag doesn't exist.");
                    $this->response->output();
                    return;
    	    }
            $result['rowsCount'] = $count;

            // set view count duration for question/answer tracking
            $result['viewTrackParams'] = array("question" => QUESTION_VIEW_DURATION, "answer" => ANSWER_VIEW_DURATION, "trackDuration" => VIEWCOUNT_TRACKING_INTERVAL, "trackingPageType" => "tagDetailPage");

    	    $this->response->setBody($result);
            //Step 4: Return the Response
            $this->response->output();    
        }

        /**
         * @desc API to Fetch Most active users data for a given tag
         * @param POST param tagId (Tag Id for which data needs to be fetched)
         * @param POST param tagName(optional)
         * @return JSON object with HTTP Code 200 and Object with most active user data
         * @date 2016-07-08
         * @author Romil Goel
         */
        function getTagsMostActiveUsers(){

            //step 1:Fetch the Input from GET/POST
            $Validate = $this->getUserDetails();
            $userId   = isset($Validate['userId'])?$Validate['userId']:'';
            $tagId    = $this->input->post("tagId");
            $tagName  = $this->input->post("tagName");

            //step 2:validate all the fields : none

            //Step 3: Fetch the Data from DB + Logic
            if(empty($tagName)){
		$this->load->model('TagsModel');
                $tagData = $this->TagsModel->getTagsDetailsById(array($tagId));
                $tagName = $tagData[$tagId]['tags'];
            }

            $returnArray = array();
            $topContributors = $this->commonObj->getTopContributorsForTag($tagId, $tagName, $userId);
            $returnArray = array_merge ( $returnArray, $topContributors);

            $this->response->setBody($returnArray);

            //Step 4: Return the Response
            $this->response->output();   
        }
        
}

