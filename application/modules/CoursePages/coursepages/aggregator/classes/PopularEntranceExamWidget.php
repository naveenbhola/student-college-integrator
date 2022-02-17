<?php
include_once('../WidgetsAggregatorInterface.php');


class PopularEntranceExamWidget implements WidgetsAggregatorInterface{

      private $_params;
      private $_CI;

      public function __construct($params){
        $this->_params = $params;
        $this->_CI     = & get_instance();
      }
      public function getWidgetData(){
        $baseCourseId  = $this->_params['coursePageData']['baseCourseId'];
        $examLibObj = $this->_CI->load->library('examPages/ExamPageLib');
        $data  = array($baseCourseId);

        $pageType = 'course';
        if(!empty($data) && $pageType == 'course'){
          $result = $examLibObj->getExamList($data,$pageType);
        }
        $popularExams = $examLibObj->getPopularExamWidgetForCHP($result);
       // _p($popularExams);die;
        return array('key'=>'popularEntranceExamWidget','data'=>$popularExams);
      }
}
?>
