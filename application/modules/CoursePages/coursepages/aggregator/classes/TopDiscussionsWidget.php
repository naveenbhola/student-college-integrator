<?php

include_once '../WidgetsAggregatorInterface.php';

class TopDiscussionsWidget  implements WidgetsAggregatorInterface{

	private $_params = array();
	private $_CI;
	private $QnAModel;
//	private $dateToCheckFor;
	
	public function __construct($params) {
		$this->_params = $params;
		$this->_CI = & get_instance();
		$this->QnAModel = $this->_CI->load->model('messageBoard/QnAModel');
		$this->cache = $this->_CI->load->library('coursepages/cache/CoursePagesCache');
	}
	
	public function getWidgetData() {
//		$dayValue = -365;
//		$this->dateToCheckFor = date("Y-m-d", strtotime("+".$dayValue." days"));
		$subCategory    = $this->_params["courseHomePageId"];
        $tagId          = $this->_params["tagId"];
		if($this->cache->isCPGSCachingOn()) {
            $discussions = $this->cache->getDiscussionsData($tagId);
            // _p($discussions); die;
            if(empty($discussions)){
                $subCategoryToTagMapping    = array($subCategory    => $tagId);
                $this->_CI->load->library('coursepages/CoursePagesUrlRequest');
                $discussionIdsData  = $this->_CI->coursepagesurlrequest->prepareRecentThreadsForCoursePages($subCategoryToTagMapping, TRUE);
                $discussionsIds     = $discussionIdsData[$subCategory][$tagId]['discussions'];
            }
            else{
                $discussionsIds = ($discussions->count > 0) ? $discussions->data : array();
            }
        }
        
        $result             = array();
        global $Question_DISCUSSION_COUNT_ARRAY;
        if(count($discussionsIds) > 0){
            $topDiscussionsData = $this->QnAModel->getTopDiscussionsRelatedData($discussionsIds);
            foreach($discussionsIds as $value){
                if(key_exists($value, $topDiscussionsData)){
                    $temp   =   array();
                    $temp['msgId']          = $topDiscussionsData[$value]['msgId'];
                    $temp['discussionId']   = $topDiscussionsData[$value]['threadId'];
                    $temp['msgTxt']         = $topDiscussionsData[$value]['msgTxt'];
                    $temp['description']    = $topDiscussionsData[$value]['description'];
                    $temp['commentCount']   = $topDiscussionsData[$value]['msgCount'];
                    $temp['URL']            = getSeoUrl($topDiscussionsData[$value]['threadId'], 'discussion', $topDiscussionsData[$value]['msgTxt'], array(), 'NA', date('Y-m-d', strtotime($topDiscussionsData[$value]['creationDate'])));
                    $result[]               = $temp;
                    if(count($result) == $Question_DISCUSSION_COUNT_ARRAY['countOfDiscussionsToShow']){
                        break;
                    }
                }
            }
        }
        
        return array('key'=>'topDiscussions','data'=>($result));
	}
}