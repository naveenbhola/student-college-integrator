<?php
class CourseTypeInformation {
	private $credential;
	private $type;
	private $course_level;
	private $base_course;
	private $hierarchy;

	function __set($property,$value) {
		$this->$property = $value;
	}

	public function getCredential(){
		//special check
		if($this->credential->getName() == 'None'){
			$this->credential->setName("");
		}
		return $this->credential;
	}

	public function getCourseLevel(){		
		//special check
		if($this->course_level->getName() == 'None'){
			$this->course_level->setName("");
		}

		return $this->course_level;
	}

	public function getBaseCourse(){
		return $this->base_course;
	}

	public function getHierarchies(){
		return $this->hierarchy;
	}

	public function addCourseLevel($course_level){
		$this->course_level = $course_level;
	}

	public function addCredential($credential){
		$this->credential = $credential;
	}
}