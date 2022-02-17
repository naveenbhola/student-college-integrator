<?php

class AbroadInstitute
{
	private $institute_id;
	private $institute_name;
	private $abbreviation;
	private $institute_type;
	
	private $listing_seo_url;
	private $listing_seo_title;
	private $listing_seo_description;
	private $listing_seo_keywords;
	private $cumulativeViewCount;
	private $pack_type;

	private $viewCount;

	private $locations;
	private $courses;
	
	private $institute_description;
	private $accreditation;

	private $faculty_page_link;
	private $alumni_page_link;
	private $facebook_page;
	
	private $university_id;
	private $university_name;
	private $university_type;
	
    private $last_modify_date;
    
	function __construct()
	{

	}

	function __clone()
	{
		foreach($this->courses as $key=>$course)
		$this->courses[$key] = clone $course;
	}
    
	public function getLastUpdatedDate()
	{
		return $this->last_modify_date;
	}
	
	public function getAbbreviation()
	{
		return $this->abbreviation;
	}
	
	public function addLocation(InstituteLocation $location)
	{
		$this->locations[$location->getLocationId()] = $location;
	}

	public function addCourse(Course $course)
	{
		$this->courses[] = $course;
	}

	public function setCourses($courses)
	{
		$this->courses = $courses;
	}
	
	public function setAdditionalURLParams($additionalURLParams)
	{
		$this->additionalURLParams = $additionalURLParams;
	}

	public function getURL()
	{
                if(!empty($this->listing_seo_url)){
                    return SHIKSHA_STUDYABROAD_HOME.$this->listing_seo_url;
                }
		return;
	}
	
	public function getMetaData()
	{
		$deptName = htmlentities($this->getName());
		$universityName = htmlentities($this->getUniversityName());
		$countryName = htmlentities($this->getMainLocation()->getCountry()->getName());
	
		$this->listing_seo_title 	= ($this->listing_seo_title 	  == ""? $deptName." - ".$universityName.", ".$countryName." - Shiksha.com" :$this->listing_seo_title 	);
		$this->listing_seo_description 	= ($this->listing_seo_description == ""? "Find complete information about ".$deptName." ".$universityName.", ".$countryName." like courses offered, campus placements, fee Structure, contact details, and more on Shiksha.com" :$this->listing_seo_description);

		return array(
					 'seoTitle' => $this->listing_seo_title,
					 'seoKeywords' => $this->listing_seo_keywords,
					 'seoDescription' => $this->listing_seo_description
					 );
	}
	
	public function getInstituteType()
	{
		return $this->institute_type;
	}

	public function isPaid()
	{
		return ($this->pack_type == GOLD_SL_LISTINGS_BASE_PRODUCT_ID || $this->pack_type == SILVER_LISTINGS_BASE_PRODUCT_ID);
	}

	public function isApplyable()
	{
		return ($this->pack_type > 0 && $this->pack_type != 7);
	}

	public function setSticky()
	{
		$this->sticky = TRUE;
	}
	
	public function isSticky()
	{
		return $this->sticky;
	}
	
	public function setMain()
	{
		$this->main = TRUE;
	}
	
	public function isMain()
	{
		return $this->main;
	}

	public function getCourses()
	{
		if(!is_array($this->courses) || count($this->courses) == 0) {
			return FALSE;
		}

		/*
		 * Sort by course order
		 */
	  	return $this->courses;
		$tempCourses = array();
		foreach($this->courses as $course){
			if(is_object($course) && get_class($course) == 'Course') {
				$id = $course->getId();
				if(!empty($id)){
					$tempCourses[] = $course;
				}
			}
		}
		$this->courses = $tempCourses;
		$courses = $this->courses;
		usort($courses,array('Institute','sortCoursesByCourseOrder'));
		return $courses;
	}
	
	public function getFlagshipCourse()
	{
		$courses = $this->getCourses();
		return $courses[0];
	}

	public static function sortCoursesByCourseOrder($course1,$course2)
	{
		if(!empty($course1) && !empty($course2)){
			return intval($course1->getOrder()) - intval($course2->getOrder());
		} else if(!empty($course1) && empty($course2)){
			return intval($course1->getOrder());
		} else if(empty($course1) && !empty($course2)){
			return intval($course2->getOrder());
		} else {
			return -1;
		}
	}

	/*
	 * Getters
	 */
	public function getId()
	{
		return $this->institute_id;
	}
	
	public function getName()
	{
		return $this->institute_name;
	}
	
	public function getMainLocation()
	{
		return reset($this->locations);	
	}
	
	public function getLocations()
	{
		return $this->locations;
	}
	
	public function getViewCount()
	{
		return $this->cumulativeViewCount;
	}
	
    public function getPackType()
	{
        return $this->pack_type;
    }
	
	public function getDescription()
	{
		return $this->institute_description;
	}
	
	public function getAccreditation()
	{
		return $this->accreditation;
	}
	
	public function getFacebookPage()
	{
		return $this->facebook_page;
	}
	
	public function getAlumniPage()
	{
		return $this->alumni_page_link;
	}
    
	public function getFacultyPage()
	{
		return $this->faculty_page_link;
	}
	
	public function isDisplayable()
	{
		return $this->university_type == 'university' ? TRUE : FALSE;
	}
	
	function __set($property,$value)
	{
		$this->$property = $value;
	}
	
	function getUniversityId()
	{
		return $this->university_id;
	}
	
	function getUniversityName()
	{
		return $this->university_name;
	}		
	
	public function removeCourse($courseId)
	{
		foreach($this->courses as $key=>$course)
		{
			if($course->getId() == $courseId)
				unset($this->courses[$key]);
		}
	}
}
