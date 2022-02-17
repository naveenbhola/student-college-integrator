<?php

include_once '../WidgetsAggregatorInterface.php';

class FaqWidget implements WidgetsAggregatorInterface {

	private $_params = array();
	private $_CI = null;
        private $courseModel;
        public function __construct($params) {

		$this->_params = $params;
		$this->_CI = & get_instance();
		$this->courseModel=$this->_CI->load->model('coursepages/coursepagemodel');
		$this->cache = $this->_CI->load->library('coursepages/cache/CoursePagesCache');
	}

	public function getWidgetData() {
		$courseHomePageId  = $this->_params["courseHomePageId"];
                $courseHomePageList=  $this->_params["courseHomePageList"];
                $coursePageCommonLib=  $this->_params["courseCommonLibObject"];
                $courseUrlObject=  $this->_params["courseUrlRequestObject"];
		if($this->cache->isCPGSCachingOn() && $faq_questions = $this->cache->getFaqWidgetData($courseHomePageId)) {
                   
			return array('key'=>'faqQuestions','data'=>$faq_questions);
		}
                $faq_questions =$coursePageCommonLib->getFaqQuestionsOnHomePageByCourseHomePageId($this->courseModel,
                        $courseHomePageId,$courseUrlObject,$courseHomePageList);
		$this->cache->storeFaqWidgetData($courseHomePageId,$faq_questions);
		return array('key'=>'faqQuestions','data'=>$faq_questions);
	}
}
