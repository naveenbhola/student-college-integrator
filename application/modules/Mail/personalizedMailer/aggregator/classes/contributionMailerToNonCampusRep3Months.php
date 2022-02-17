<?php

include_once '../WidgetsAggregatorInterface.php';

class contributionMailerToNonCampusRep3Months implements WidgetsAggregatorInterface{

    private $_params = array();

    public function __construct($params) {
        $this->_params = $params;
        $this->_CI = & get_instance();
    }

    public function getWidgetData() {
        $customParams = $this->_params['customParams'];
        $content = $customParams['viewContent'];
        $mailerHtml = $this->_CI->load->view('personalizedMailer/contributionMailerToNonCR',$content,TRUE);
        return array('key'=>'contributionMailerToNonCR','data'=>$mailerHtml);
    }
}
