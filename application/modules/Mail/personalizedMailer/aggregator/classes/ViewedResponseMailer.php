<?php

include_once '../WidgetsAggregatorInterface.php';

class ViewedResponseMailer implements WidgetsAggregatorInterface{

    private $_params = array();
    
    public function __construct($params) {
        $this->_params = $params;
        $this->CI = & get_instance();
    }
    
    public function getWidgetData() {
        $params = $this->_params['customParams'];

        $widgetHTML = $this->CI->load->view("personalizedMailer/widgets/ViewedResponseWidget", $params, true);
        
        return array('key'=>'ViewedResponseWidget','data'=>$widgetHTML);
    }
}