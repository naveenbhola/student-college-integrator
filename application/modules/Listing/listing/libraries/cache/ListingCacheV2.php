<?php

class ListingCacheV2 extends Cache
{
	private $universityKey = "V1University";
	private $courseKey = "V2Course";
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * If field list is passed then it returns data for those fields else all fields in hash of given key & id
	 * works for both univ & course
	 * @param: String $key, integer $id, list of String fields
	 * @return: array containing data for those fields
	 */
	private function _getDataFromHashByFields($key, $id, $fields =array())
	{
		$data = array();
		if(count($fields)>0){
			$data = $this->getMembersOfHashByFieldNameWithValue($key,$id,$fields);
		}else{
			$data = $this->getAllMembersOfHashWithValue($key,$id);
		}
		return $data;
	}
	
	/**
	 * Parses ('json_decode's) data from hash, field wise
	 * works for both univ & course
	 */
	private function _parseJSONFromHashFields(& $data)
	{
		foreach ($data as $key => $val) {
			$parsedData = json_decode($val, true);
			if (json_last_error() == JSON_ERROR_NONE && $parsedData != null) {
				$data[$key] = $parsedData;
			} else {
				$data[$key] = $val;
			}
		}
	}
	
	/*
	* get single Course hash from cache
	*/
	function getCourse($courseId,$fields=array())
	{
		// $fields = array("courseId","name","universityId","instituteId","level","categoryId","subCategoryId","desiredCourseId","specializationIds","brochureURL","seoURL","seoDetails","clientId","packtype","lastModifiedDate","expiryDate","duration","durationURL","feeCurrency","tuition","roomBoard","mealFlag","insurance","transportation","customFees","courseExams","courseCustomExams","courseDescription","examRequiredDetails","accreditation","affiliation","rankingDetails","curriculum","nzqfCategorization","recruitingCompanies","cumulativeViewCount","courseWebsiteURL","admissionWebsiteURL","feesPageURL","alumniInfoURL","scholarshipURLUniversityLevel","applicationDeadlineURL","faqURL","scholarshipURLCourseLevel","scholarshipURLDeptLevel","facultyInfoURL","englishProficiencyURL","anyOtherEligibilityURL","jobProfile","classProfile","applicationDetailId","isRmcEnabled");
		$courseData = $this->_getDataFromHashByFields($this->courseKey, $courseId, $fields);
		$this->_parseJSONFromHashFields($courseData);
		if(!isset($courseData) || empty($courseData)){
			return false;
		}
		return $courseData;
	}
	
	/**
	 * same as getCourse but for multiple
	 */
	function getMultipleCourses($courseIds, $fields = array())
	{
		// $fields = array("courseId","name","universityId","instituteId","level","categoryId","subCategoryId","desiredCourseId","specializationIds","brochureURL","seoURL","seoDetails","clientId","packtype","lastModifiedDate","expiryDate","duration","durationURL","feeCurrency","tuition","roomBoard","mealFlag","insurance","transportation","customFees","courseExams","courseCustomExams","courseDescription","examRequiredDetails","accreditation","affiliation","rankingDetails","curriculum","nzqfCategorization","recruitingCompanies","cumulativeViewCount","courseWebsiteURL","admissionWebsiteURL","feesPageURL","alumniInfoURL","scholarshipURLUniversityLevel","applicationDeadlineURL","faqURL","scholarshipURLCourseLevel","scholarshipURLDeptLevel","facultyInfoURL","englishProficiencyURL","anyOtherEligibilityURL","jobProfile","classProfile","applicationDetailId","isRmcEnabled");
		// $fields = array("courseId","name","universityId","level","seoURL", "packtype", "isRmcEnabled", "courseExams","customFees", "transportation", "insurance", "tuition","roomBoard", "duration", "feeCurrency" );
		$dataFromCache = array();
		foreach ($courseIds as $courseId){
			$dataFromCache[$courseId] = $this->_getDataFromHashByFields($this->courseKey, $courseId, $fields);
		}
		$finalCourses = array();
		if(is_array($dataFromCache)) {
			foreach ($dataFromCache as $courseId => &$course) {
				$this->_parseJSONFromHashFields($course);
				if(isset($course) && !empty($course)) {
					$finalCourses[$courseId] = $course;
				}
			}
		}
		unset($dataFromCache);
		return $finalCourses;
	}
	
    /* introduced deletedCourseId because for deleted Course we receive blank Object which do not have courseId
	* to save data against any object we need its ID.
    */
    function storeCourse($course, $deletedCourseId = 0)
    {
		if($deletedCourseId != 0) {
            $courseId = $deletedCourseId;
        } else {
			$courseId = $course['courseId'];
        }
		
        foreach($course as $key => $val) {
			if(is_array($val)){
				$member = json_encode($val);
            }
            else{
				$member = $val;
            }
            $this->addMembersToHash($this->courseKey,$courseId, array($key => $member),-1, NULL, 1);
        }
    }
	
	function deleteCourse($courseId)
	{
		$this->delete($this->courseKey,$courseId);
	}
	
	/**
	 * get Multiple universities' data from cache
	 */ 
	public function getUniversities($universityIds,$fields = array())
    {
		$key = $this->universityKey;
		// $fields = array("id","name","fundingType","type","universityLocation","seoURL","universityDefaultImgUrl","brochureURL","announcement","accomodationDetails","accomodationWebsite","livingExpenses");
		$dataFromCache = array();
    	foreach ($universityIds as $universityId) {
			$dataFromCache[$universityId] = $this->_getDataFromHashByFields($key,$universityId,$fields);
    	}
    	$finalData = array();
        if(is_array($dataFromCache)){
			foreach ($dataFromCache as $universityId =>$universityRow) {
				$this->_parseJSONFromHashFields($universityRow);
            	$finalData[$universityId] = $universityRow;            	
            }
        }    
        unset($dataFromCache);
        return $finalData;	
    }

	public function getMultipleUniversityFieldsByUniversityId($universityId,$fields){
		$data = $this->_getDataFromHashByFields($this->universityKey,$universityId,$fields);
		$this->_parseJSONFromHashFields($data);
		if(!isset($data) || empty($data)){
			return false;
		}
		return $data;
	}
	
    public function storeUniversitySection($universityId, $sectionName, $sectionData)
	{
		if(is_array($sectionData)){
			$data       = json_encode($sectionData);			
		}else{
			$data       = $sectionData;
		}

		$hashKey    = $this->universityKey;
		$hashMember = array($sectionName => $data);

		$this->addMembersToHash($hashKey,$universityId,$hashMember,-1,FALSE);
	}

	/*
	* Institute
	*/ 
	public function getInstitute($instituteId)
    {
		$data = unserialize(gzuncompress($this->get('Institute',$instituteId)));
		return $data;
	}
	
	public function getMultipleInstitutes($instituteIds)
    {
		$institutes =  $this->multiGet('Institute',$instituteIds);
		$institutes = array_map('unserialize', array_map('gzuncompress',$institutes));
		return $institutes;
    }
	
	public function storeInstitute($institute)
	{
		$data = gzcompress(serialize($institute), 9);
		$this->store('Institute', $institute->getId(), $data,-1, NULL, 1);
	}
	
	public function deleteInstitute($instituteId)
	{
		$this->delete('Institute',$instituteId);
	}
	
	public function storeUniversity($university)
	{
		$data = serialize($university);
		$this->store('University',$university->getId(), $data,-1, NULL, 1);
	}
	
	public function deleteUniversity($universityId) {
		$this->delete($this->universityKey,$universityId);
	}
	
	function increment($key) {
		return $this->inc($key);
	}
        
	function getCurrency($currencyId)
    {
		return json_decode($this->get('V1Currency',$currencyId),true);
    }
	
	function storeCurrency($currencyId, $currency)
	{
		$this->store('V1Currency',$currencyId,json_encode($currency),-1,NULL,0);
	}

}
