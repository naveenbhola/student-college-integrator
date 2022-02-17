<?php

class CollegeReviewWidget implements WidgetsAggregatorInterface {

    private $_params = array();
    private $_CI = null;
    private $jobsInfo;
    private $reviewRatingInfo;
    private $campusConnectInfo;
    public function __construct($params) {

        $this->_params = $params;
        $this->_CI = & get_instance();
        $this->_CI->load->model('coursepages/coursepagecmsmodel');
        $this->cache = $this->_CI->load->library('coursepages/cache/CoursePagesCache');
        $this->_CI->load->config("coursepages/CoursePageConfig");
        $this->jobsInfo = $this->_CI->config->item('jobsDataWidget');
        $this->reviewRatingInfo = $this->_CI->config->item('reviewRatingWidget');
        $this->campusConnectInfo= $this->_CI->config->item('campusConnectWidget');
    }

    public function getWidgetData() {
        $widget_collegeReviewData=Modules::run('common/CommonReviewWidget/getTilesByHierarchy',$this->_params['coursePageData']);
        $courseHomePageId=$this->_params['courseHomePageId'];
        $dataPresentFor = 0;
        if(isset($this->jobsInfo[$courseHomePageId]))$dataPresentFor++;
        if(isset($this->reviewRatingInfo[$courseHomePageId]))$dataPresentFor++;
        if(isset($this->campusConnectInfo[$courseHomePageId]))$dataPresentFor++;
        if($dataPresentFor>=2)
            {
             return array('key'=>'CollegeReviewWidget','data'=>array('widget_collegeReviewData'=>$widget_collegeReviewData,
            'widgetForPage'=>strtoupper('COURSEPAGE_'.$this->_params['coursePageData']['Name']),
            'loadCareerWidget'=>true,
            'suggestorPageName'=>'all_tags',
            'jobsInfo'=> $this->jobsInfo[$courseHomePageId],
            'reviewRatingInfo'=>  $this->reviewRatingInfo[$courseHomePageId],
            'campusConnectInfo'=>  $this->campusConnectInfo[$courseHomePageId])
                 );
        }
        return array('key'=>'CollegeReviewWidget','data'=>array());
    }


}
