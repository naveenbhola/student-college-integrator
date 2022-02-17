<?php

class DiscussionFinder {
	
	private $_ci;
	
	public function __construct(){
		$this->_ci = & get_instance();
		$this->_ci->load->model("search/SearchModel", "", true);
		$this->_ci->load->helper("search/SearchUtility");
		$this->_ci->config->load('search_config');
		$this->config = $this->_ci->config;
	}
	
	public function getData($id = null,$solr_version='old') {
		if($id == null){
			return array();
		}
		$indexData = false;
		$discussionData = $this->getDiscussionData($id, $solr_version);
		
		if(!empty($discussionData) && is_array($discussionData)){
			$indexData = array();
			$indexData = $this->preprocessRawData($discussionData, $solr_version);
			$dataSufficientFlag = $this->isDataSufficient($indexData);
			if(!$dataSufficientFlag){
				$indexData = false;
			}
		}
		return $indexData;
	}
	
	public function preprocessRawData($data = array(),$solr_version='old') {
		$discussion = array();
		$discussionData = $data['discussion'];
		$discussionCommentsData = $data['comments'];
		if(is_array($discussionData) && !empty($discussionData)){
			$discussion['discussion_id'] 					= $discussionData['msgId'];
			$discussion['discussion_thread_id'] 			= $discussionData['threadId'];
			$discussion['discussion_title'] 				= $discussionData['msgTxt'];
			$discussion['discussion_description'] 			= $discussionData['description'];
			$discussion['discussion_category_ids'] 			= $discussionData['category_ids'];
			$discussion['discussion_creator_userid'] 		= $discussionData['userId'];
			$discussion['discussion_creator_displayname'] 	= $discussionData['displayname'];
			$discussion['discussion_creator_image_url'] 	= $discussionData['image_url'];
			$discussion['discussion_created_time'] 			= solrDateFormater($discussionData['creationDate']);


			if($solr_version == 'new'){
				// tags mapping
				$allTags = array();
	            foreach($data['tags_mapped'] as $tagType => $tagArray){
	            	$allTags = array_merge($allTags, (array)$tagArray);
	            	$tagType = str_replace("_", "", $tagType);
	            	$tagType = removeSpacesFromString($tagType);
	            	$discussion['thread_tags_'.$tagType]  = array_filter($tagArray);
	            }
	            $discussion['thread_tags'] 		 				 = array_filter($allTags);
	            $discussion['discussion_comment_count'] 	     = $data['commentCount'] != null ? $data['commentCount'] : 0;

	            $predisLib = PredisLibrary::getInstance();//$this->_ci->load->library("common/PredisLibrary");
            	$threadQualityScore = $predisLib->getMemberOfString("threadQuality:thread:".$discussionData['threadId']);
				$discussion['discussion_quality_score'] 		 = $threadQualityScore ? $threadQualityScore : 0;
			}
			
			$json_comments_info = array();
			if(is_array($discussionCommentsData) && !empty($discussionCommentsData)){
				$counter = 0;
				foreach($discussionCommentsData as $comment){
					$commentDetails = array();
					$commentDetails['discussion_comment_id'] 					= $comment['msgId'] != null ? $comment['msgId'] : "";
					$commentDetails['discussion_comment_user_id'] 				= $comment['userId'] != null ? $comment['userId'] : "";
					$commentDetails['discussion_comment_user_displayname'] 		= $comment['displayname'] != null ? $comment['displayname'] : "";
					$commentDetails['discussion_comment_user_image_url'] 		= $comment['image_url'] != null ? $comment['image_url'] : "";
					$commentDetails['discussion_comment_creation_time'] 		= $comment['creationDate'] != null ? $comment['creationDate'] : "";
					$json_comments_info[$counter] = $commentDetails;
					$discussion['discussion_comment_'.$counter] 				= $comment['msgTxt'];
					$counter++;
				}
			}
		}
		$discussion['discussion_commments_json'] = json_encode($json_comments_info);
		$discussion['unique_id'] = "discussion_". $discussion['discussion_id'];
		$discussion['facetype'] = "discussion";
		return $discussion;
	}
	
	private function getDiscussionData($id = null, $solr_version='old'){
		if($id == null){
			return false;
		}
		$searchModel = new SearchModel();
		$maxDiscussionComments = $this->config->item('max_discussion_comments');
		$discussionData = $searchModel->getDiscussionDetails($id, $maxDiscussionComments);

		if($solr_version == 'new'){
			$contentMappedTags = array();
			$contentMappedTags = $searchModel->getContentMappedTags($id, 'discussion');
			$discussionData['tags_mapped'] = $contentMappedTags;
		}	

		return $discussionData;
	}
	
	private function isDataSufficient($data = array()) {
		$returnFlag = false;
		if(!empty($data) && is_array($data)){
			if(!empty($data['discussion_id'])){
				$returnFlag = true;
			}
		}
		return $returnFlag;
	}
	
	
}

