<?php

/**
 * Class TrendsHomeLibrary
 * Library for shiksha analytics/trends home
 * @date    2017-09-20
 * @author  Romil Goel
 * @todo    none
 *
 */
class TrendsHomeLibrary
{
    function __construct(){

        $this->CI = & get_instance();

        // get the trend repo
        $this->CI->load->builder("analytics/TrendsBuilder");
        $trendsBuilder = new TrendsBuilder();
        $this->trendsRepo = $trendsBuilder->getTrendsRepository();
    }

    function getTrendsHomeData($userStatusData){

    	$data = array();

        // seo data
        $this->_getSeoData($data);

    	// get top metrics
    	$this->_getOverallMetrics($data);

    	// popular universities
    	$this->_getPopularUniversitiesData($data);

    	// popular institutes
    	$this->_getPopularInstitutesData($data);

        // popular Courses
        $this->_getPopularCoursesData($data);

        // popular Exams
        $this->_getPopularExamsData($data);

        // popular Specialization
        $this->_getPopularSpecializationsData($data);

        // popular Questions
        $this->_getPopularQuestionsData($data);

        // popular Articles
        $this->_getPopularArticlesData($data);

        if(($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile')){
            $data['linkTarget'] = "";
        }
        else{
            $data['linkTarget'] = "_blank";
        }

    	return $data;
    }

    function getTestData() {
        return $this->trendsRepo->getTesterData();
    }

    private function _getSeoData(&$data){

        $data['seoTitle'] = "Shiksha Trends - Home";
        $data['seoDesc'] = "Shiksha Trends - Home";
    }

    private function _getOverallMetrics(&$data){

		$overallMetrics          = $this->trendsRepo->getTrendsOverallMetrics();
		$data['overall_metrics'] = $overallMetrics;
    }

    function _getPopularUniversitiesData(&$data){

		$popularUniversities          = $this->trendsRepo->getPopularUniversities();
		$data['popular_universities'] = $popularUniversities;
    }

    function _getPopularInstitutesData(&$data){
    	
		$popularInstitutes          = $this->trendsRepo->getPopularInstitutesData();
        if(!empty($popularInstitutes['stream_share'])){
            foreach ($popularInstitutes['stream_share'] as $key => &$value) {
                $value['text'] = str_replace("&", "and", $value['text']);
            }
        }
		$data['popular_institutes'] = $popularInstitutes;
    }

    function _getPopularCoursesData(&$data){
        $popularCourse           = $this->trendsRepo->getPopularCoursesData();
        $data['popular_courses'] = $popularCourse;
    }

    function _getPopularExamsData(&$data){
        $popularExams          = $this->trendsRepo->getPopularExamsData();

        if(!empty($popularExams['streams'])){
            foreach ($popularExams['streams'] as $key => &$value) {
                $value = str_replace("&", "and", $value);
            }
        }
        
        $data['popular_exams'] = $popularExams;
    }

    function _getPopularSpecializationsData(&$data){
        $popularSpecialization          = $this->trendsRepo->getPopularSpecializationsData();

        if(!empty($popularSpecialization['specialization'])){
            foreach ($popularSpecialization['specialization'] as $key => &$value) {
                $value['text'] = str_replace("&", "and", $value['text']);
            }
        }
        if(!empty($popularSpecialization['streams'])){
            foreach ($popularSpecialization['streams'] as $key => &$value) {
                $value = str_replace("&", "and", $value);
            }
        }

        $data['popular_specialization'] = $popularSpecialization;
    }

    function _getPopularQuestionsData(&$data){
        $popularQuestions          = $this->trendsRepo->getPopularQuestionsData();
        $data['popular_questions'] = $popularQuestions;
    }

    function _getPopularArticlesData(&$data){
        $popularArticles          = $this->trendsRepo->getPopularArticlesData();
        $data['popular_articles'] = $popularArticles;
    }
}
?>