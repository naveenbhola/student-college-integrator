<?php

include_once '../WidgetsAggregatorInterface.php';

class PredictorWidget implements WidgetsAggregatorInterface {

    private $_params = array();
    private $_CI;
    private $rankingPageInfo;

    public function __construct($params) {
        $this->_params = $params;
        $this->_CI = & get_instance();
        $this->_CI->load->config("coursepages/CoursePageConfig");
        $this->rankingPageInfo = $this->_CI->config->item('rankingWidgetInfo');
    }

    public function getWidgetData() {
        $rankWidgetInfo = $this->rankingPageInfo;
        $courseHomePageId=  $this->_params['courseHomePageId'];
        if (!isset($rankWidgetInfo[$courseHomePageId]) && !isset($rankWidgetInfo[$courseHomePageId])) {
            return array('key'=>'predictorWidget','data'=>array());
        }
        $collegePredictorExams = $rankWidgetInfo[$courseHomePageId]['collegePredictorExams'];
        $rankPredictorExams = $rankWidgetInfo[$courseHomePageId]['rankPredictorExams'];
        if (count($collegePredictorExams) == 0 && count($rankPredictorExams) == 0) {
            return array('key'=>'predictorWidget','data'=>array());
        }
        return array('key'=>'predictorWidget','data'=>array('predictorWidgetData'=>$rankWidgetInfo[$courseHomePageId]));
    }

//put your code here
}
