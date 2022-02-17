<?php

include_once '../WidgetsAggregatorInterface.php';

class courseTuple implements WidgetsAggregatorInterface{

    private $_params = array();
    
    public function __construct($params) {
        $this->_params = $params;
        $this->CI = & get_instance();
    }
    
    public function getWidgetData() {
        $params = array();
        $params = $this->_params['customParams'];

        $courseObj = $params['courseObj'];
        $instituteObj = $params['instituteObj'];
        $mailer = $params['mailer_name'];

        $instituteId = $instituteObj->getId();
        $instituteName = $instituteObj->getName();
        $instituteLocation = $instituteObj->getMainLocation();
        
        if(is_object($instituteLocation)) {
            $instituteCity = $instituteLocation->getCityName();
        }

        $instituteLogoDetails = $instituteObj->getHeaderImage();
        
        if(is_array($instituteLogoDetails) && count($instituteLogoDetails) > 0) {
            $instituteLogoUrl = $instituteLogoDetails[0]->getURL();
        }
        else if(is_object($instituteLogoDetails)) {
            $instituteLogoUrl = $instituteLogoDetails->getURL();
        }

        if(empty($instituteLogoUrl)) {
            $instituteLogoUrl = SHIKSHA_HOME."/public/images/instDefault_210x157.png";
        }

        $courseDuration = $courseObj->getDuration();
        $courseApprovals = $courseObj->getRecognition();

        if(count($courseApprovals)) {
            $params['courseApproval'] = $courseApprovals[0]->getName() == 'ugc' ? 'Recognised by: UGC' : 'Approved by: '.strtoupper($courseApprovals[0]->getName());
        } else {
            $params['courseApproval'] = '';
        }
        
        $params['courseId'] = $courseObj->getId();
        $params['courseName'] = str_replace("–", "&#8210;", $courseObj->getName());
        $params['courseUrl'] = $courseObj->getURL();
        $params['courseUrlDB'] = $courseObj->getURL().'?mailer='.$mailer.'&action=db&utm_term=DownloadBrochure';
        $params['courseUrlContact'] = $courseObj->getURL().'?mailer='.$mailer.'&scrollTo=contact&action=showContact&utm_term=ShowPhoneEmail';

        $params['instituteId'] = $instituteId;
        $params['instituteName'] = $instituteName;
        $params['instituteUrl'] = $instituteObj->getURL();
        $params['instituteLogoURL'] = $instituteLogoUrl;
        $params['instituteCityName'] = $instituteCity;
        $params['establishYear'] = $instituteObj->getEstablishedYear();
        $params['courseDuration'] = $courseDuration['value'].' '.ucfirst($courseDuration['unit']);
        
        /**
        * Get data for fees widget
        */
        $fees = $courseObj->getFees();
        if(is_object($fees)) {
            if($fees->getCategory() == 'general') {             
                $params['feesInRupee'] = $fees->getFeesUnitName().' '.$fees->getFeesValue(); // New Rupee sign (₹):&#8377;
            } else {
                $params['feesInRupee'] = '';
            }
        }

        $widgetHTML = $this->CI->load->view("personalizedMailer/widgets/courseTuple", $params, true);
        
        return array('key'=>'courseTuple','data'=>$widgetHTML);
    }
}