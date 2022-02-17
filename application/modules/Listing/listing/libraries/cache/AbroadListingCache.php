<?php

class AbroadListingCache extends Cache
{
	private $standardCacheTime = 21600; //6 hours
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function getAbroadExams() {
		$data = unserialize($this->get('AbroadExams', 'ExamsList'));
		return $data;
    }
	
	public function storeAbroadExams($exams) {
		if(!empty($exams)){
			$data = serialize($exams);
			$this->store('AbroadExams', 'ExamsList', $data, $this->standardCacheTime, NULL, 1);	
		}
	}
	
	public function getCurrencyConversionFactor($sourceCurrencyId = NULL, $destinationCurrencyId = NULL,$allExchangeRate = false) {
		$data 	= unserialize($this->get('AbroadCurrency', 'ConversionRates'));
		if($allExchangeRate === true)
		{
			return $data;
		}
		$factor = $data[$sourceCurrencyId][$destinationCurrencyId]['factor'];
		if(empty($factor)){
			return false;
		} else {
			return $factor;
		}
    }
	
	public function storeCurrencyConversionFactor($exchangeRates = array()){
		if(!empty($exchangeRates)){
			$data = serialize($exchangeRates);
			$this->store('AbroadCurrency', 'ConversionRates', $data, $this->standardCacheTime, NULL, 1);
		}
	}
	
	
	public function storeSimilarCoursesDataOfUniversity($universityId, $courseData) {
		if(is_array($courseData)){
			$data = serialize($courseData);
			$this->store('similarCourseData', $universityId, $data, 3600, NULL, 1);
		}
	}
	
	public function getSimilarCoursesDataOfUniversity($universityId) {
		if(!empty($universityId)){
			$data = unserialize($this->get('similarCourseData', $universityId));
		}
		return $data;
	}
	
	public function storeVisaGuideDetail($countryId, $visaGuidData) {
		if(!empty($visaGuidData)){
			$data = serialize($visaGuidData);
			$this->store('visaGuideDetail', $countryId, $data, $standardCacheTime, NULL, 1);
		}
	}
	
	public function getVisaGuideDetail($countryId) {
		if(!empty($countryId)){
			$data = unserialize($this->get('visaGuideDetail', $countryId));
		}
		return $data;
	}
	
	public function getViewCountForCourses($days=7) {
		$key = 'AbroadViewCount-'.$days;
		$data = unserialize($this->get($key, 'CourseList'));
		return $data;
	}
	
	public function storeViewCountForCourses($data,$days=7) {
		if(!empty($data)){
			$data = serialize($data);
			$key = 'AbroadViewCount-'.$days;
			$this->store($key, 'CourseList', $data, $this->standardCacheTime, NULL, 1);
		}
	}
	
	public function storeViewCountForListings($data,$listingType,$days=7){
		if(!empty($data)){
			$data = serialize($data);
			$key = 'AbroadListingsViewCount-'.$listingType.'-'.$days;
			$this->store($key, 'AbroadListingsViewCount', $data,$this->standardCacheTime, NULL, 1);
		}
	}
	
	public function getViewCountForListings($listingType,$days=7){
		$key = 'AbroadListingsViewCount-'.$listingType.'-'.$days;
		$data = unserialize($this->get($key, 'AbroadListingsViewCount'));
		return $data;
	}
	
	public function getAbroadMainLDBCourses() {
		$data = unserialize($this->get('AbroadCourses', 'MainLDBCourses'));
		return $data;
    }
	
	public function storeAbroadMainLDBCourses($data) {
		if(!empty($data)) {
			$data = serialize($data);
			$this->store('AbroadCourses', 'MainLDBCourses', $data, $this->standardCacheTime, NULL, 1);
		}
	}
	
	public function getAbroadCourseLevels() {
		$data = unserialize($this->get('AbroadCourseLevels', 'CourseLevels'));
		return $data;
    }
	
	public function storeAbroadCourseLevels($data) {
		if(!empty($data)) {
			$data = serialize($data);
			$this->store('AbroadCourseLevels', 'CourseLevels', $data, $this->standardCacheTime, NULL, 1);
		}
	}
	/*
	 * course levels that are used in registration forms are stored separately 
	 */ 
	public function getAbroadCourseLevelsForRegistrationForms() {
		$data = unserialize($this->get('AbroadCourseLevelsForRegistrationForms', 'CourseLevels'));
		return $data;
    }
	/*
	 * course levels that are used in registration forms are stored separately 
	 */ 
	public function storeAbroadCourseLevelsForRegistrationForms($data) {
		if(!empty($data)) {
			$data = serialize($data);
			$this->store('AbroadCourseLevelsForRegistrationForms', 'CourseLevels', $data, $this->standardCacheTime, NULL, 1);
		}
	}
	public function getSubCatsForDesiredCourses($ldbCourseId) {
		$data = unserialize($this->get('AbroadSubcatForLdbCourse', $ldbCourseId));
		return $data;
    }
	
	public function storeSubCatsForDesiredCourses($ldbCourseId, $data) {
		if(!empty($data)) {
			$data = serialize($data);
			$this->store('AbroadSubcatForLdbCourse', $ldbCourseId, $data, $this->standardCacheTime, NULL, 1);
		}
	}
	
	public function storeSnapshotCourse($snapshotCourse){
		if(!empty($snapshotCourse)){
			$data = serialize($snapshotCourse);
			$this->store('SnapshotCourseListing',$snapshotCourse->getId(),$data,-1,NULL,1);
		}
	}
	
	public function getSnapshotCourse($snapshotCourseId){
		die;
		$course = unserialize($this->get('SnapshotCourseListing',$snapshotCourseId));
		return $course;
	}
	
	public function deleteSnapshotCourse($snapshotCourseId){
		$this->delete('SnapshotCourseListing',$snapshotCourseId);
	}
	
	function getMultipleSnapshotCourses($courseIds,$categoryPageFlag)
    {		
		$courses =  $this->multiGet('SnapshotCourseListing',$courseIds);
		if($categoryPageFlag){
			foreach($courses as $course){
				$course = unserialize($course);
				$course->cleanForCategoryPage();
				$courses[$course->getId()] = $course;
			}
		}else{
			$courses = array_map('unserialize',$courses);
		}
		return $courses;
    }
	/*
	 * get average first year fees for a country course combination
	 * [Note] key structure :
	 * - desiredCourse : <countryId>-<ldbCourseId>
	 * - others : <countryId>-<categoryId>-<course_level>
	 */
	public function getAverage1stYearFees($key) {
		$avgFee = json_decode(($this->get('CountryAverage1stYearFees', $key)),true);
		return $avgFee;
    }
	/*
	 * store average first year fees for a country course combination (key)
	 * [Note] key structure :
	 * - desiredCourse : <countryId>-<ldbCourseId>
	 * - others : <countryId>-<categoryId>-<course_level>
	 */
	public function storeAverage1stYearFees($key,$data){
		if(!empty($data)){
			$this->store('CountryAverage1stYearFees', $key, json_encode($data), 108000, NULL, 1);
		}
	}
	/*
	 * get average living expenses for a country (key)
	 * [Note] key structure : <countryId>
	 */
	public function getAverageLivingExpense($key) {
		$avgExpense = json_decode(($this->get('CountryAverageLivingExpense', $key)),true);
		return $avgExpense;
    }
	/*
	 * store average living expenses for a country (key)
	 * [Note] key structure : <countryId>
	 */
	public function storeAverageLivingExpense($key,$data){
		if(!empty($data)){
			$this->store('CountryAverageLivingExpense', $key, /*serialize(*/json_encode($data)/*)*/, 108000, NULL, 1);
		}
	}
	/*
	 * get average exam score & num of total records used, for a country course exam combination
	 * [Note] key structure :
	 * - desiredCourse : <countryId>-<ldbCourseId>-<examId>
	 * - others : <countryId>-<categoryId>-<course_level>-<examId>
	 */	
	public function getAverageExamScores($key) {
		$avgScore= json_decode($this->get('CountryAverageExamScore', $key),true);
		return $avgScore;
    }
	/*
	 * store average exam score & num of total records used, for a country course exam combination (key)
	 * [Note] key structure :
	 * - desiredCourse : <countryId>-<ldbCourseId>-<examId>
	 * - others : <countryId>-<categoryId>-<course_level>-<examId>
	 */
	public function storeAverageExamScores($key,$avgScore){
		if(!empty($avgScore)){
			$this->store('CountryAverageExamScore', $key, json_encode($avgScore), 108000, NULL, 1);
		}
	}

    /**
     * store average exam score and number of total number of courses found for that exam
     * key Structure
     * <countryId>-<examId>
     */
    public function storeAllCoursesAverageExamScores($key,$avgScore){
        if(!empty($avgScore)){
            $this->store('CountryAllCoursesAverageExamScore',$key,json_encode($avgScore),108000,NULL,1);
        }
    }

	public function getCurrencyCodeById($id){
		if($id > 0){
			$currencyData = unserialize($this->get('AbroadCurrenciesData',$id));
			if(!empty($currencyData)){
				return $currencyData;
			}
		}
		return false;
	}

	public function storeCurrencyData($currencyId,$currencyData){
		if(!empty($currencyId) && !empty($currencyData)){
			$this->store('AbroadCurrenciesData', $currencyId, serialize($currencyData), 108000, NULL, 1);
		}
	}

	public function getCurrenncyRateDetails($source, $dest){
		$data = unserialize($this->get('AbroadCurrencyRate', 'ConversionRates'.$source.'_'.$dest));
		if(!empty($data)){
			return $data;
		}
		return false;
	}

	public function storeCurrenncyRateDetails($source, $dest, $data){
		if(!empty($data)){
			$this->store('AbroadCurrencyRate', 'ConversionRates'.$source.'_'.$dest, serialize($data), -1, NULL, 1);
		}
	}

	public function deleteCurrenncyRateDetails($source, $dest){
		$this->delete('AbroadCurrencyRate', 'ConversionRates'.$source.'_'.$dest);
	}

	public function getCollegeCountsByExamAndLDBCourse($examId){
		if($examId > 0){
			$collegeCount = json_decode($this->get('CollegeCountsByExamAndLDBCourse',$examId), true);
			if(!empty($collegeCount)){
				return $collegeCount;
			}
		}
		return false;
	}

	public function storeCollegeCountsByExamAndLDBCourse($examId,$collegeData){
		if(!empty($examId) && !empty($collegeData)){
			$this->store('CollegeCountsByExamAndLDBCourse', $examId, json_encode($collegeData), 43200, NULL, 1);
		}
	}
}
