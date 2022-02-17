<?php

include_once '../WidgetsAggregatorInterface.php';

class RequestEBrochure implements WidgetsAggregatorInterface{

	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->CI = & get_instance();
	}
	
	public function getWidgetData() {
		/* if($this->_params["userInfo"] == 'false') {
			return array('key'=>'WelcomeFullTimeMBA','data'=> '');
		} */

		$customParams = $this->_params['customParams'];
		$mailer_name = $customParams['mailer_name'];
		$customParams['clientMsg'] = '';		
		$courseId = $customParams['courseId'];

		global $COURSE_MESSAGE_KEY_MAPPING;
		global $MESSAGE_MAPPING;		
		
		$customParams['clientMsg'] = $MESSAGE_MAPPING[$COURSE_MESSAGE_KEY_MAPPING[$courseId]]['email'];
		

		// if(empty($customParams['mailContentFirstLine'])) {
		// 	$customParams['mailContentFirstLine'] = 'Your College brochure is here. ';
		// }			
		switch($mailer_name){

			case 'NationalCourseDownloadBrochure1':
				$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/RequestEBrochureWithAttachment", $customParams, true);
				break;
			case 'NationalCourseDownloadBrochure2':
				$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/RequestEBrochureWithNoAttachment", $customParams, true);
				break;
			case 'NationalCourseDownloadBrochure3':
				$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/RequestEBrochureWithLink", $customParams, true);
				break;
			case 'NationalCourseDownloadBrochureMobile1':
				$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/RequestEBrochureWithAttachmentMobile", $customParams, true);
				break;
			case 'NationalCourseDownloadBrochureMobile2':
				$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/RequestEBrochureWithNoAttachmentMobile", $customParams, true);
				break;
			case 'shortlistMailer1':
			case 'shortlistMailer2':
			case 'shortlistMailer3':
				$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/shortlistMail", $customParams, true);
				break; 
			case 'compareMailer1':
			case 'compareMailer2':
			case 'compareMailer3':
				$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/compareMail", $customParams, true);
				break;	
			case 'nationalContactDetails1':
			case 'nationalContactDetails2':
			case 'nationalContactDetails3':
				$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/nationalContactDetails", $customParams, true);
				break;
				
		}
		
		return array('key'=>'RequestEBrochure','data'=>$widgetHTML);
	}
}