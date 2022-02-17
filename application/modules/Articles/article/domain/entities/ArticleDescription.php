<?php 
class ArticleDescription{
	
	private $descriptionId;
	private $description;
	private $descriptionCss;
	private $blogId;
	private $descriptionTag;
	private $imageData;
	
	public function getDescriptionId(){
		return $this->descriptionId;
	}

	public function getBlogId(){
		return $this->blogId;
	}

	public function getDescription(){
		return $this->description;
	}

	public function getDescriptionCss(){
		return $this->descriptionCss;
	}

	public function getDescriptionTag(){
		return $this->descriptionTag;
	}

	function __set($property, $value)
	{ 
		$this->$property = $value;
	}

    public function getImageData(){
        return $this->imageData;
    }

    public function setImageData($imageData){
        $this->imageData = $imageData;
    }
}
?>