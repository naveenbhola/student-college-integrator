<?php
include_once('../WidgetsAggregatorInterface.php');


class RankPredictorWidget implements WidgetsAggregatorInterface{

      private $_params;
      private $_CI;
      private $_rankPredictorInfo;

      public function __construct($params){
        $this->_params = $params;
        $this->_CI     = & get_instance();
        $this->_CI->load->config('coursepages/CoursePageConfig');
        $this->_rankPredictorInfo = $this->_CI->config->item('rankPredictorWidgetInfo');
      }
      public function getWidgetData(){
        $rankPredictorInfo = $this->_rankPredictorInfo;
        $courseHomePageId  = $this->_params['courseHomePageId'];
        if(!isset($rankPredictorInfo[$courseHomePageId])){
          return array('key'=>'rankPredictorWidget','data'=>array());
        }
        $examsForRankPrediction = $rankPredictorInfo[$courseHomePageId]['examsForRankPrediction'];
        if(count($examsForRankPrediction)==0){
          return array('key'=>'rankPredictorWidget','data'=>array());
        }
        else return array('key'=>'rankPredictorWidget','data'=>$rankPredictorInfo[$courseHomePageId]);
      }
}
?>
