<?php

class DiscussionDocument {
	
	private $id;
	
	private $title;
	
	private $description;
	
	private $thread_id;
	
	private $created_time;
	
	private $unique_id;
	
	private $facetype;
	
	private $score;
	
	private $user_id;
	
	private $user_display_name;
	
	private $user_image_url;
	
	private $selected_comment_index;
	
	private $selected_comment_text;
	
	private $comments_json;
	
	private $discussion_comment_count;
	
	private $seoUrl = "";

	private $discussion_view_count;
	
	public function __construct(){
		
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function getUrl(){
		if(empty($this->seoUrl)){
			$this->seoUrl = getSeoUrl($this->getThreadId(), 'discussion', $this->getTitle());
		} 
		return $this->seoUrl;
	}
	
	public function getTitle(){
		$maxCharacters = 140;
		if(strlen($this->title) > $maxCharacters){
			$this->title = substr($this->title, 0, $maxCharacters - 3) . " ...";
		}
		return $this->title . "</b>";
	}

	public function getFullTitle(){
		return $this->title;
	}

	public function getDescription(){
		return $this->description;	
	}
	
	public function getThreadId(){
		return $this->thread_id;	
	}
	
	public function getCreatedTime(){
		return $this->created_time;
	}
	
	public function getFacetype(){
		return $this->facetype;
	}
	
	public function getUniqueId(){
		return $this->unique_id;
	}
	
	public function getDocumentScore(){
		return $this->score;
	}
	
	public function getUserId(){
		return $this->user_id;
	}
	
	public function getUserDisplayName(){
		return $this->user_display_name;
	}
	
	public function getUserImageUrl(){
		if(trim($this->user_image_url) == ""){
			return "/public/images/photoNotAvailable_m.gif";
		} else {
			return $this->user_image_url;		
		}
	}
	
	public function getCommentDetails(){
		$jsonDecodedComments = json_decode(html_entity_decode($this->comments_json), true);
		return $jsonDecodedComments;
	}
	
	public function getCommentText(){
		$commentText = false;
		if(isset($this->selected_comment_text) && !empty($this->selected_comment_text)){
			$commentText = $this->selected_comment_text;
		} else {
			$commentText = $this->getDescription();
		}
		return $commentText . "</b>";
	}
	
	public function getCommentId(){
		if($this->selected_comment_index != false && trim($this->selected_comment_index) != ""){
			return $this->getCommentInfo("discussion_comment_id");	
		} else {
			return $this->getId();
		}
	}
	
	public function getCommentUserDisplayName(){
		if($this->selected_comment_index !== false && trim($this->selected_comment_index) != ""){
			return $this->getCommentInfo("discussion_comment_user_displayname");	
		} else {
			return $this->getUserDisplayName();
		}
	}

	public function getCommentUserImageUrl(){
		if($this->selected_comment_index != false && trim($this->selected_comment_index) != ""){
			$imageUrl = $this->getCommentInfo("discussion_comment_user_image_url");
			if(trim($imageUrl) == ""){
				return "/public/images/photoNotAvailable_m.gif";
			} else {
				return $imageUrl;		
			}
		} else {
			return $this->getUserImageUrl();
		}
	}
	
	public function getCommentUserId(){
		if($this->selected_comment_index != false && trim($this->selected_comment_index) != ""){
			return $this->getCommentInfo("discussion_comment_user_id");	
		} else {
			return $this->getUserId();
		}
	}
	
	public function getCommentInfo($key){
		$value = "";
		if($this->selected_comment_index !== false && trim($this->selected_comment_index) != ""){
			$commentDetails = $this->getCommentDetails();
			if(array_key_exists($this->selected_comment_index, $commentDetails)){
				$commentInfo = $commentDetails[$this->selected_comment_index];
				if(array_key_exists($key, $commentInfo)){
					$value = $commentInfo[$key];
				}
			}
		}
		return $value;
	}
	
	public function getCommentCount(){
		return $this->discussion_comment_count;
	}

	public function getViewCount(){
		return $this->discussion_view_count;
	}

	public function setViewCount($viewCount){
		$this->discussion_view_count = $viewCount;
	}

	public function setCommentCount($count){
		$this->discussion_comment_count = $count;
	}
	
	public function __set($paramName, $paramValue){
		$this->$paramName = $paramValue;
	}
	
}

?>