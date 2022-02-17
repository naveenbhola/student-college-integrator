<?php

class AlumaniFeedback
{
	private $email;
	private $institute_id;
	private $name;
	private $institute_name;
	private $course_name;
	private $course_id;
	private $course_comp_year;
	private $organisation;
	private $designation;
	private $feedbackTime;
	private $showShikshaFlag;
	private $legalFlag;
	private $criteria_id;
	private $criteria_name;
	private $criteria_rating;
	private $criteria_desc;
	private $status;
	private $thread_id;

	function __construct()
	{

	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getEmail()
	{
		return $this->email;
	}

	public function getInstituteId()
	{
		return $this->institute_id;
	}

	public function getInstituteName()
	{
		return $this->institute_name;
	}

	public function getCourseName() {
		return $this->course_name;
	}
	
	public function getCourseId() {
		return $this->$course_id;
	}
	
	public function getCourseComplettionYear() {
		return $this->course_comp_year;
	}

	public function getOrganisation() {
		return $this->organisation;
	}

	public function getDesignation() {
		return $this->designation;
	}

	public function getFeedbackTime() {
		return $this->feedbackTime;
	}

	public function getShowShikshaFlag() {
		return $this->showShikshaFlag;
	}

	public function getLegalFlag() {
		return $this->legalFlag;
	}

	public function getCriteriaId() {
		return $this->criteria_id;
	}

	public function getCriteriaName() {
		return $this->criteria_name;
	}

	public function getCriteriaRating() {
		return $this->criteria_rating;
	}

	public function getCriteriaDescription() {
		return $this->criteria_desc;
	}

	public function getFeedbackStatus() {
		return $this->status;
	}

	public function getThreadid() {
		return $this->thread_id;
	}
	
	function __set($property,$value)
	{
		$this->$property = $value;
	}
}
