<?php

class QuestionDocument {
	
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
	
	private $category_entity;
	
	private $question_comment_count;
	
	private $question_view_count;
	
	private $question_answer_count;

	private $question_answers_count;
	
	private $answer_user_id;
	
	private $answer_user_display_name;
	
	private $answer_user_image_url;
	
	private $answer_title;
	
	private $answer_id;
	
	private $answer_created_time;
	
	private $question_institute_id;
	
	private $question_institute_title;
	
	private $seoUrl = "";

	private $question_bestAnswerId;

	private $question_inMasterList;

	public function __construct(){
		
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function getUrl(){
		if(empty($this->seoUrl)){
			$this->seoUrl = getSeoUrl($this->getId(), 'question', $this->getTitle());	
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
		return $this->user_image_url;	
	}
	
	public function setQuestionCategory($categoryId, Category $category){
		if(get_class($category) == "Category"){
			$this->category_entity[$categoryId] = $category;
		}
	}
	
	public function getAnswerId(){
		return $this->answer_id;	
	}
	
	public function getAnswerUserId(){
		return $this->answer_user_id;
	}
	
	public function getAnswerUserDisplayName(){
		return $this->answer_user_display_name;
	}
	
	public function getAnswerUserImageUrl(){
		if(trim($this->answer_user_image_url) != ""){
			return $this->answer_user_image_url;	
		} else {
			return "/public/images/photoNotAvailable_m.gif";
		}
	}
	
	public function getAnswerTitle(){
		$maxCharacters = 300;
		if(strlen($this->answer_title) > $maxCharacters){
			$this->answer_title = substr($this->answer_title, 0, $maxCharacters) . "...";
		}
		return $this->answer_title . "</b>";
	}
	
	public function getAnswerCreatedTime(){
		return $this->answer_created_time;
	}
	
	public function getViewCount(){
		return $this->question_view_count;
	}
	
	public function getCommentCount(){
		return $this->question_comment_count;
	}
	
	public function getAnswerCount(){
		return $this->question_answer_count;
	}

	public function getAnswersCount(){
		return $this->question_answers_count;
	}
	
	public function getQuestionInstituteId(){
		return trim($this->question_institute_id);
	}
	
	public function getQuestionInstituteTitle(){
		return trim($this->question_institute_title);
	}
	
	public function getQuestionInstituteURL(){
		$url = SHIKSHA_HOME."/getListingDetail/".trim($this->question_institute_id)."/institute/";
		return $url;
	}

	public function getBestAnswerId(){
		if(!empty($this->question_bestAnswerId))
			return $this->question_bestAnswerId;
	}

	public function getInMasterList(){
		if(!empty($this->question_inMasterList))
			return $this->question_inMasterList;
	}

	public function setViewCount($viewCount){
		$this->question_view_count = $viewCount;
	}

	public function setCommentCount($count){
		$this->question_comment_count = $count;
	}

	public function __set($paramName, $paramValue){
		$this->$paramName = $paramValue;
	}
	
}

?>
