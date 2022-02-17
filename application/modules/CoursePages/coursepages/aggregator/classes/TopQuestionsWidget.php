<?php

include_once '../WidgetsAggregatorInterface.php';

class TopQuestionsWidget implements WidgetsAggregatorInterface{

	private $_params = array();
	private $_CI;
	private $QnAModel;
//	private $dateToCheckFor;
	private $cache;
	
	public function __construct($params) {
		$this->_params = $params;
		$this->_CI = & get_instance();
		$this->QnAModel = $this->_CI->load->model('messageBoard/QnAModel');
		$this->cache = $this->_CI->load->library('coursepages/cache/CoursePagesCache');
	}
	
	public function getWidgetData() {
//		$dayValue = -365;
//		$this->dateToCheckFor = date("Y-m-d", strtotime("+".$dayValue." days"));
		$subCategory = $this->_params["courseHomePageId"];
        $tagId       = $this->_params['tagId'];
		
		if($this->cache->isCPGSCachingOn()) {
            $questions = $this->cache->getQuestionsData($tagId);
            if(empty($questions)){
                $subCategoryToTagMapping    = array($subCategory    => $tagId);
                $this->_CI->load->library('coursepages/CoursePagesUrlRequest');
                $questionIdsData = $this->_CI->coursepagesurlrequest->prepareRecentThreadsForCoursePages($subCategoryToTagMapping, TRUE);
                $questionIds = $questionIdsData[$subCategory][$tagId]['questions'];
            }
            else{
                $questionIds = ($questions->count > 0) ? $questions->data : array();
            }
        }
        
        $result             = array();
        global $Question_DISCUSSION_COUNT_ARRAY;
        if(count($questionIds) > 0){
            $topQuestionsData   = $this->QnAModel->getTopQuestionsRelatedData($questionIds);
            foreach($questionIds as $value){
                if(key_exists($value, $topQuestionsData)){
                    $temp   = array();
                    $temp['msgId']          = $topQuestionsData[$value]['msgId'];
                    $temp['msgTxt']         = $topQuestionsData[$value]['msgTxt'];
                    $temp['creationDate']   = $topQuestionsData[$value]['creationDate'];
                    $temp['viewCount']      = $topQuestionsData[$value]['viewCount'];
                    $temp['msgCount']       = $topQuestionsData[$value]['msgCount'];
                    $temp['categoryId']     = $topQuestionsData[$value][$subCategory];
                    $temp['URL']            = getSeoUrl($value, 'question', $topQuestionsData[$value]['msgTxt'], array(), 'NA', date('Y-m-d',  strtotime($topQuestionsData[$value]['creationDate'])));
                    $result[]               = $temp;
                    if(count($result) == $Question_DISCUSSION_COUNT_ARRAY['countOfQuestionsToShow']){
                        break;
                    }
                }
            }
        }
		
        return array('key'=>'topQuestions','data'=>($result));
	}
}