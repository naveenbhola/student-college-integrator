<?php 
class ArticleSlideshow{
	private $id;
	private $blogId;
	private $title;
	private $subTitle;
	private $description;
	private $sequence;
	private $image;
	private $status;

	public function getSlideshowId(){
		return $this->id;
	}

	public function getBlogId(){
		return $this->blogId;
	}

	public function getTitle(){
		return $this->title;
	}

	public function getSubtitle(){
		return $this->subTitle;
	}

	public function getDescription(){
		return $this->description;
	}

	public function getSequence(){
		return $this->sequence;
	}

	public function getImage(){
		return $this->image;
	}
	
	public function getStatus(){
		return $this->status;
	}

	function __set($property, $value)
	{ 
		$this->$property = $value;
	}
}
?>