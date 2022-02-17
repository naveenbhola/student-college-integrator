<?php
class Eligibility {
	private $exam_id;
	private $exam_name;
	private $category;
	private $value;
	private $unit;
	private $max_value;

	function __set($property,$value) {
		$this->$property = $value;
	}

	function getExamId(){
		return $this->exam_id;
	}

	function getExamName(){
		return $this->exam_name;
	}

	function getCategory(){
		return $this->category;
	}

	function getValue(){
		return $this->value;
	}

	function getUnit(){
		return $this->unit;
	}

	function getMaxValue(){
		return $this->max_value;
	}
}