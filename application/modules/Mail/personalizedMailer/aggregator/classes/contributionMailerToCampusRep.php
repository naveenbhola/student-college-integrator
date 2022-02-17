<?php

include_once '../WidgetsAggregatorInterface.php';

class contributionMailerToCampusRep implements WidgetsAggregatorInterface{

    private $_params = array();

    public function __construct($params) {
        $this->_params = $params;
        $this->_CI = & get_instance();
    }

    public function getWidgetData() {
        $customParams = $this->_params['customParams'];
        $content = $customParams['viewContent'];
        $mailerHtml = $this->_CI->load->view('personalizedMailer/contributionMailerToCampusRep',$content,TRUE);
        return array('key'=>'contributionMailerToCampusRep','data'=>$mailerHtml);
    }
}
