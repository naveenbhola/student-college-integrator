<?php
class Branch{
	private $instituteId;	
	private $collegeName;	
	private $locId;
	private $creationDate;
	private $collegeGroupName;	
	private $exams;	
	private $branchId;
	private $branchAcronym;
	private $branchName;
	private $shikshaCourseId;
	private $courseCode;
	private $instCourseLink;
	private $instLink;
	private $rankType;
	private $isHomeStateTuple;
	private $closingRank;
	private $numberOfRound;
	private $categoryName;
	private $cityName;
	private $stateName;
	private $pageData;
	private $roundsInfo;
	private $remarks;

	public function getInstituteId(){
		return $this->instituteId;
	}

	public function getCollegeName(){
		return $this->collegeName;	
	}

	public function getLocationId(){
		return $this->locId;	
	}
	
	public function getCreationDate(){
		return $this->creationDate;
	}

	public function getCollegeGroupName(){
		return $this->collegeGroupName;	
	}

	public function getExam(){
		return $this->exams;	
	}
	
	public function getBranchId(){
		return $this->branchId;
	}
	
	public function getRankType(){
		return $this->rankType;
	}

	public function getClosingRank(){
		return $this->closingRank;
	}

	public function setClosingRank($rank){
		$this->closingRank = $rank;
	}

	public function getNumberOfRound(){
		return $this->numberOfRound;
	}
	
	public function getBranchAcronym(){
		return $this->branchAcronym;
	}

	
	public function getBranchName(){
		return $this->branchName;
	}
	
	public function getShikshaCourseId(){
		return $this->shikshaCourseId;
	}
	
	public function getCourseCode(){
		return $this->courseCode;
	}
	
	public function getInstCourseLink(){
		return $this->instCourseLink;
	}
	
	public function getInstLink(){
		return $this->instLink;
	}
	
	public function getCategoryName(){
		return $this->categoryName;
	}
	
	public function getCityName(){
		return $this->cityName;
	}
		
	public function getStateName(){
		return $this->stateName;
	}

	public function getPageData(){
		return $this->pageData;
	}

	public function getRoundsInfo(){
		return $this->roundsInfo;
	}

	public function setRoundsInfo($roundsInfo){
		$this->roundsInfo = $roundsInfo;
	}
	
	public function setPageData($data){
		$this->pageData = $data;
	}
	
	public function getRemarks(){
	    return $this->remarks;
	}

	public function setRemarks($remarks){
	    $this->remarks = $remarks;
	}
	
	public function getIsHomeStateTuple(){
		return $this->isHomeStateTuple;
	}
	
	function __set($property,$value)
	{ 
		$this->$property = $value;
	}

}
?>
