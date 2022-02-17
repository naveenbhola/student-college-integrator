<?php
class Career{
	private $careerId;	
	private $name;	
	private $status;
	private $creationDate;
	private $shortDescription;	
	private $image;	
	private $difficultyLevel;	
	private $minimumSalaryInLacs;
	private $maximumSalaryInLacs;
	private $minimumSalaryInThousand;
	private $maximumSalaryInThousand;
	private $mandatorySubject;
	private $careerUrl;
	private $otherCareerInformation=array();
	private $careerPaths = array();
	private $hierarchyId = array();
	private $courseId = array();

	public function getCareerId(){
		return $this->careerId;
	}

	public function getName(){
		return $this->name;	
	}

	public function getStatus(){
		return $this->status;	
	}
	
	public function getCreationDate(){
		return $this->creationDate;
	}

	public function getShortDescription(){
		return $this->shortDescription;	
	}

	public function getImage(){
		return $this->image;	
	}

	public function getDifficultyLevel(){
		return $this->difficultyLevel;
	}

	public function getMinimumSalaryInLacs(){
		return $this->minimumSalaryInLacs;	
	}

	public function getMaximumSalaryInLacs(){
		return $this->maximumSalaryInLacs;	
	}

	public function getMinimumSalaryInThousand(){
		return $this->minimumSalaryInThousand;	
	}

	public function getMaximumSalaryInThousand(){
		return $this->maximumSalaryInThousand;	
	}

	public function getMandatorySubject(){
		return $this->mandatorySubject;	
	}

	/*public function getSeoUrl(){
		return $this->seo_url;	
	}*/

	public function getCareerUrl(){
		return $this->careerUrl;	
	}
	
	public function getOtherCareerInformation() {
		return $this->otherCareerInformation;
	}

	public function getOtherCareerOfInterest(){
		return $this->otherCareerOfInterest;
	}

	public function addPath(CareerPath $careerPath)
	{
		$this->careerPaths[] = $careerPath;
	}

	public function getCareerPaths(){
		return $this->careerPaths;
	}
	
	public function getHierarchyId(){
		return $this->hierarchyId;
	}
	
	public function getCourseId(){
                return $this->courseId;
        }

	function __set($property,$value)
	{ 
		$this->$property = $value;
	}
}
?>
