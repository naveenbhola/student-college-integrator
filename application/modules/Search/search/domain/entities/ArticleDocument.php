<?php

class ArticleDocument {
	
	private $id;
	
	private $title;
	
	private $body;
	
	private $summary;
	
	private $image_url;
	
	private $country_id;
	
	private $country_name;
	
	private $unique_id;
	
	private $facetype;
	
	private $score;
	
	private $user_id;
	
	private $user_display_name;
	
	private $user_image_url;
	
	private $category_entity;
	
	private $created_time;
	
	private $url;
	
	private $view_count;
	
	private $comment_count;
	
	public function __construct(){
			
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function getTitle(){
		$maxCharacters = 140;
		if(strlen($this->title) > $maxCharacters){
			$this->title = substr($this->title, 0, $maxCharacters - 3) . " ...";
		}
		return $this->title . "</b>";
	}
	
	public function getBody(){
		$removeCharacterList = array("\s","\t",".", ",", "-", ";", "'");
		for($count=0; $count < count($removeCharacterList); $count++){
			foreach($removeCharacterList as $character){
				$this->body = trim($this->body, $character);
			}
		}
		$maxCharacters = 305;
		if(strlen($this->body) >= $maxCharacters){
			$this->body = substr($this->body, 0, $maxCharacters) . "...";
		}
		return $this->body . "</b>";
	}
	
	public function getSummary(){
		return $this->summary;	
	}
	
	public function getImageUrl(){
		if(strlen(trim($this->image_url)) == 0){
			return "/public/images/photoNotAvailable.gif";
		} else {
			return trim($this->image_url);	
		}
	}
	
	public function getUrl(){
		return $this->url;
	}
	
	public function getCountryId(){
		return $this->country_id;
	}
	
	public function getCountryName(){
		return $this->country_name;
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
	
	public function getCreatedTime(){
		return $this->created_time;
	}
	
	public function getViewCount(){
		return $this->view_count;
	}
	
	public function getCommentCount(){
		return $this->comment_count;
	}
	
	public function setCategoryEntity($categoryId, Category $category){
		if(get_class($category) == "Category"){
			$this->category_entity[$categoryId] = $category;
		}
	}
	
	public function __set($paramName, $paramValue){
		$this->$paramName = $paramValue;
	}
	
}

?>