<?php 
class trendingsearchmodel extends MY_Model {
	public $dbHandle = '';
    public $dbHandleMode = '';
    
    function __construct() {
		parent::__construct('Listing');
    }

    private function initiateModel($mode = "read") {
		if($this->dbHandle && $this->dbHandleMode == 'write')
		    return;
		
		$this->dbHandleMode = $mode;
		$this->dbHandle = NULL;
		if($mode == 'read') {
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
    }

    /**
     * [getTrendingSearchesForCareer this will return array of career ids and their name]
     * @author Ankit Garg <g.ankit@shiksha.com>
     * @date   2017-06-22
     * @return [type]     [description]
     */
    function getTrendingSearchesForCareer($dateInterval, $limit = 10) {
    	$this->initiateModel();
    	$this->dbHandle->select('keyword, careerId, count(careerId) as viewCount');
    	// $this->dbHandle->select('keyword, careerId, count(careerId) as count');
    	$this->dbHandle->where('careerId IS NOT NULL', NULL, false);
    	$this->dbHandle->where('careerId != ""', NULL, false);
    	$this->dbHandle->where('careerId > 0', NULL, false);
    	$this->dbHandle->where('DATE(created) > ', $dateInterval);
    	$this->dbHandle->group_by('careerId'); 
    	$this->dbHandle->order_by('viewCount desc'); 
    	$this->dbHandle->limit($limit);
    	$careerTrendingSearch = $this->dbHandle->get('searchCareerTracking')->result_array();
    	return $careerTrendingSearch;
    }

    function getTrendingSearchesForExam($dateInterval, $limit = 10) {
    	$this->initiateModel();
    	$this->dbHandle->select('examId, type, count(examId) as viewCount');
    	$this->dbHandle->where('examId IS NOT NULL', NULL, false);
    	$this->dbHandle->where('examId != ""', NULL, false);
    	$this->dbHandle->where('type != ""', NULL, false);
    	$this->dbHandle->where('type IS NOT  NULL', NULL, false);
    	$this->dbHandle->where('examId > 0', NULL, false);
    	$this->dbHandle->where('DATE(created) > ', $dateInterval);
    	$this->dbHandle->group_by('examId, type'); 
    	$this->dbHandle->order_by('viewCount desc'); 
    	$this->dbHandle->limit($limit);
    	$examTrendingSearch = $this->dbHandle->get('searchExamTracking')->result_array();
    	return $examTrendingSearch;
    }

    function getTrendingSearchesForCourse($dateInterval, $searchType, $limit = 10) {
    	$this->initiateModel();
    	switch ($searchType) {
    		case 'close':
    			$pageType = array('close', 'interim');
    			$fields = 'entityId, entityType, keyword';
    			$countField = 'count(entityId)';
    			break;
    		
    		default:
    			$pageType = array('open');
    			$fields = 'keyword';
    			$countField = 'count(keyword)';
    			break;
    	}
    	$this->dbHandle->select($fields.', '.$countField.' as viewCount');
    	$this->dbHandle->where('resultCount > 0', NULL, false);
        $this->dbHandle->where("keyword != 'btech bangalore'", NULL, false); //hack to ignore open searches earlier categorized in close
    	$this->dbHandle->where_in('pageType', $pageType);
    	$this->dbHandle->where('DATE(created) > ', $dateInterval);
    	$this->dbHandle->group_by($fields);
    	$this->dbHandle->order_by('viewCount desc'); 
    	$this->dbHandle->limit($limit);
    	$courseTrendingSearch = $this->dbHandle->get('searchCourseTracking')->result_array();
    	return $courseTrendingSearch;
    }

    function getTrendingSearchesForCollege($dateInterval, $limit = 10) {
    	$this->initiateModel();
    	$this->dbHandle->select('instituteId, count(instituteId) as viewCount');
    	$this->dbHandle->where('instituteId IS NOT NULL', NULL, false);
    	$this->dbHandle->where('instituteId > 0', NULL, false);
    	$this->dbHandle->where('DATE(created) > ', $dateInterval);
    	$this->dbHandle->group_by('instituteId'); 
    	$this->dbHandle->order_by('viewCount desc'); 
    	$this->dbHandle->limit($limit);
    	$collegeTrendingSearch = $this->dbHandle->get('searchInstituteTracking')->result_array();
    	return $collegeTrendingSearch;
    }
}