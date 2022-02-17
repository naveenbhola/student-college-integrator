<?php 
class CareerPath
{
	private $pathId;
	private $pathName;
	private $steps = array();

	public function getPathId(){
		return $this->pathId;
	}

	public function getPathName(){
		return $this->pathName;
	}

	public function getSteps(){
		return $this->steps;
	}

	public function addStep(CareerPathStep $careerPathStep)
	{
		$this->steps[] = $careerPathStep;
	}

	function __set($property,$value)
	{ 
		$this->$property = $value;
	}

}

?>
