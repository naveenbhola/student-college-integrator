<?php

include_once '../WidgetsAggregatorInterface.php';

class examDigestMail implements WidgetsAggregatorInterface{

    private $_params = array();

    public function __construct($params) {
            $this->_params = $params;
            $this->_CI = & get_instance();
    }

    public function getWidgetData() {
        $customParams = $this->_params['customParams'];
	$widgetHTML = $this->_CI->load->view("personalizedMailer/widgets/examDigest/ExamDigest", $customParams['digestInfo'], true);
        return array('key'=>'examDigestMail','data'=>$widgetHTML);
    }
}
