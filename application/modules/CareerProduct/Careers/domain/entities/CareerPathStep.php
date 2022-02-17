<?php
class CareerPathStep
{
	private $id;
	private $stepTitle;
	private $stepDescription;
	private $stepOrder;

	function getId(){
		return $this->id;
	}

	function getStepTitle(){
		return $this->stepTitle;
	}

	function getStepDescription(){
		return $this->stepDescription;
	}

	function getStepOrder(){
		return $this->stepOrder;
	}

	function __set($property,$value)
	{ 
		$this->$property = $value;
	}

}

?>
