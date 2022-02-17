<?php
class PlacementsInterships {
	private $batch_year;
	private $course;
	private $course_type;
	private $percentage_batch_placed;
	private $salary_unit;
	private $salary_unit_name;
	private $min_salary;
	private $median_salary;
	private $avg_salary;
	private $max_salary;
	private $report_url;
	private $type;

	private $total_international_offers;
	private $max_international_salary;
	private $max_international_salary_unit;
	private $max_international_salary_unit_name;

	function __set($property,$value) {
		$this->$property = $value;
	}

	function getSalary(){
		return array(
			'percentage_batch_placed'            => $this->percentage_batch_placed,			
			'min'                                => $this->min_salary,
			'median'                             => $this->median_salary,
			'avg'                                => $this->avg_salary,
			'max'                                => $this->max_salary,
			'unit'                               => $this->salary_unit,
			'unit_name'                          => $this->salary_unit_name,
			'max_international_salary'           => $this->max_international_salary,
			'max_international_salary_unit'      => $this->max_international_salary_unit,
			'max_international_salary_unit_name' => $this->max_international_salary_unit_name,
			'total_international_offers'         => $this->total_international_offers,
			);
		
	}

	function getCourseType(){
		return $this->course_type;
	}

	function getCourseTypeId(){
		return $this->course;
	}

	function getReportUrl(){
		return addingDomainNameToUrl(array('url' => $this->report_url , 'domainName' =>MEDIA_SERVER));
	}

	function getBatchYear(){
		return $this->batch_year;
	}
}