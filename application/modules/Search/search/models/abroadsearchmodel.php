<?php
class abroadsearchmodel extends MY_Model {
	private $dbHandle = '';
	private $CI;
   
    function __construct(){
		parent::__construct('Search');
		//$this->CI = &get_instance();
    }
	
	private function initiateModel($mode = "write"){
		$this->dbHandle = NULL;
		if($mode == 'read'){
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
	}
	/*
	 * get data for insertion to QERSA's table : city]
	 */
	public function getCitiesForQER($durationStartDatetime="")
	{
		$this->initiateModel('read');
		$this->dbHandle->select('city_id as id , city_name as name');
		$this->dbHandle->from('countryCityTable');
		$this->dbHandle->where('countryId>2','',false);
		if($durationStartDatetime != '')
		{
			$this->dbHandle->where("creationDate>='".$durationStartDatetime."'",'',false);
		}
		$qerTableDataForCity = $this->dbHandle->get()->result_array();
		return $qerTableDataForCity;
	}
	/*
	 * get data for insertion to QERSA's table : state]
	 */
	public function getStatesForQER()
	{
		$this->initiateModel('read');
		$this->dbHandle->select('state_id as id, state_name as name');
		$this->dbHandle->from('stateTable');
		$this->dbHandle->where('countryId>2','',false);
		$qerTableDataForState = $this->dbHandle->get()->result_array();
		return $qerTableDataForState;
	}
	/*
	 * get data for insertion to QERSA's table : country]
	 */
	public function getCountriesForQER()
	{
		$this->initiateModel('read');
		$this->dbHandle->select('countryId as id, name');
		$this->dbHandle->from('countryTable');
		$this->dbHandle->where('countryId>2','',false);
		$qerTableDataForCountry = $this->dbHandle->get()->result_array();
		return $qerTableDataForCountry;
	}
	/*
	 * get data for insertion to QERSA's table : continent]
	 */
	public function getContinentsForQER()
	{
		$this->initiateModel('read');
		$this->dbHandle->select('continent_id as id, name');
		$this->dbHandle->from('continentTable');
		$qerTableDataForContinent = $this->dbHandle->get()->result_array();
		return $qerTableDataForContinent;
	}
	/*
	 * get data for insertion to QERSA's table : universities]
	 */
	public function getUniversitiesForQER($durationStartDatetime="", $deletedDataFlag = false)
	{
		$this->initiateModel('read');
		$this->dbHandle->select('lm.listing_type_id as id,lm.listing_title as name, u.acronym as more_popular_abb');
		$this->dbHandle->from('university u');
		$this->dbHandle->join('listings_main lm','u.university_id = lm.listing_type_id and lm.listing_type= "university" and u.status = lm.status','inner');
		if($deletedDataFlag === true)
		{
			$this->dbHandle->where('u.status','deleted');
		}
		else
		{
			$this->dbHandle->where('u.status','live');
		}
		if($durationStartDatetime != '')
		{
			$this->dbHandle->where("lm.last_modify_date>='".$durationStartDatetime."'",'',false);
		}
		$qerTableDataForUniversities = $this->dbHandle->get()->result_array();
		return $qerTableDataForUniversities;
	}
	/*
	 * get data for insertion to QERSA's table : institute]
	 */
	public function getInstitutesForQER($durationStartDatetime="", $deletedDataFlag = false)
	{
		$this->initiateModel('read');
		$this->dbHandle->select('i.institute_id as id,i.institute_name as name,i.abbreviation as more_popular_abb');
		$this->dbHandle->from('institute i');
		$this->dbHandle->join('listings_main l','i.institute_id = l.listing_type_id and l.listing_type = "institute" and i.status=l.status','inner');
		
		if($deletedDataFlag === true)
		{
			$this->dbHandle->where('i.status','deleted');
		}
		else
		{
			$this->dbHandle->where('i.status','live');
		}
		$this->dbHandle->where('i.institute_type','Department');
		if($durationStartDatetime != '')
		{
			$this->dbHandle->where("l.last_modify_date>='".$durationStartDatetime."'",'',false);
		}
		$qerTableDataForInstitutes = $this->dbHandle->get()->result_array();
		//_p($this->dbHandle->last_query());
		return $qerTableDataForInstitutes ;
	}
	/*
	 * get data for insertion to QERSA's table : categories]
	 */
	public function getCategoriesForQER()
	{
		$this->initiateModel('read');
		$this->dbHandle->select('boardId as id, name');
		$this->dbHandle->from('categoryBoardTable');
		$this->dbHandle->where("isOldCategory='0'",'',false);
		$this->dbHandle->where("parentId=1",'',false);
		$this->dbHandle->where('flag','studyabroad');
		$qerTableDataForCategories = $this->dbHandle->get()->result_array();
		return $qerTableDataForCategories;
	}
	/*
	 * get data for insertion to QERSA's table : subcategories]
	 */
	public function getSubcategoriesForQER()
	{
		$this->initiateModel('read');
		$this->dbHandle->select('boardId as id, name');
		$this->dbHandle->from('categoryBoardTable');
		$this->dbHandle->where("isOldCategory='0'",'',false);
		$this->dbHandle->where("parentId>1",'',false);
		$this->dbHandle->where('flag','studyabroad');
		$qerTableDataForSubcategories = $this->dbHandle->get()->result_array();
		return $qerTableDataForSubcategories;
	}
	/*
	 * get data for insertion to QERSA's table : level]
	 */
	public function getLevelsForQER()
	{
		$this->initiateModel('read');
		$this->dbHandle->select('distinct CourseName as name',false);
		$this->dbHandle->from('tCourseSpecializationMapping');
		$this->dbHandle->where("isEnabled=1",'',false);
		$this->dbHandle->where("parentId!= -1",'',false);
		$this->dbHandle->where("categoryId>1",'',false);
		$this->dbHandle->where('scope','abroad');
		$this->dbHandle->where('CourseReach','national');
		$result = $this->dbHandle->get()->result_array();
		$qerTableDataForLevels = array();
		foreach($result as $k=>$qerTableDataForLevel)
		{
			$qerTableDataForLevels[] = array('id'=>($k+1),'name'=>$qerTableDataForLevel['name']);
		}
		return $qerTableDataForLevels;
	}
	/*
	 * get data for insertion to QERSA's table : exams]
	 */
	public function getExamsForQER()
	{
		$this->initiateModel('read');
		$this->dbHandle->select('examId as id, exam as name',false);
		$this->dbHandle->from('abroadListingsExamsMasterTable');
		$this->dbHandle->where('status','live');
		$qerTableDataForExams = $this->dbHandle->get()->result_array();
		return $qerTableDataForExams;
	}
	/*
	 * get data for insertion to QERSA's table : desired course]
	 */
	public function getDesiredCoursesForQER($durationStartDatetime="")
	{
		$this->initiateModel('read');
		// desired courses
		$this->dbHandle->select("map.SpecializationId as id, ", false);
    	$this->dbHandle->select("map.CourseName as name",false);
		$this->dbHandle->from('shiksha.tCourseSpecializationMapping map');
    	$this->dbHandle->where('map.scope','abroad');
		$this->dbHandle->where('map.parentId =1','',false);
		$this->dbHandle->where('map.isEnabled =1','',false);
		if($durationStartDatetime != '')
		{
			$this->dbHandle->where("map.SubmitDate>='".$durationStartDatetime."'",'',false);
		}
		$qerTableDataForDesiredCourse= $this->dbHandle->get()->result_array();
		return $qerTableDataForDesiredCourse;
	}
	/*
	 * get data for insertion to QERSA's table : specializations]
	 */
	public function getSpecializationsForQER($durationStartDatetime="")
	{
		$this->initiateModel('read');
    	$this->dbHandle->select("SpecializationName as name,SpecializationId as id",false); // earlier CategoryId 
		$this->dbHandle->from("shiksha.tCourseSpecializationMapping");
		$this->dbHandle->where("SpecializationName!='All'","",false);
		$this->dbHandle->where("CourseName","Bachelors");
		$this->dbHandle->where("scope","abroad");
		$this->dbHandle->where("isEnabled =1",'',false);
		if($durationStartDatetime != '')
		{
			$this->dbHandle->where("SubmitDate>='".$durationStartDatetime."'",'',false);
		}
		$this->dbHandle->order_by("SpecializationName", "asc");
		$res = $this->dbHandle->get()->result_array();
		
		$qerTableDataForSpecializations = $res;
		return $qerTableDataForSpecializations;
	}
}

?>