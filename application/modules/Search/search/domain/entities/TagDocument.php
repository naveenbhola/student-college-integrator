<?php
class TagDocument {
	
	private $id;
	
	private $name;
	
	private $type;
	
	private $quality_factor;
	
	private $description;

	private $isTagFollowedByUser = false;

	private $followCount = 0;

	public function __construct(){
		
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function getType(){
		return $this->type;
	}
	
	public function getQualityFactor(){
		return $this->quality_factor;
	}
	
	public function getDescription(){
		return $this->description;	
	}
	
	function getFollowCount($count){
		return $this->followCount;
	}

	function getTagFollowFlag($flag){
		return $this->isTagFollowedByUser;
	}

	function setFollowCount($count){
		if(empty($count))
			$this->followCount = 0;
		else	
			$this->followCount = $count;
	}

	function setTagFollowFlag($flag){
		$this->isTagFollowedByUser = $flag;
	}

	public function __set($paramName, $paramValue){
		$this->$paramName = $paramValue;
	}
}

?>
