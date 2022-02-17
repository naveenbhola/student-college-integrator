<?php 

include_once '../WidgetsAggregatorInterface.php';

class streamDigestMailHeader implements WidgetsAggregatorInterface{
	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->CI = & get_instance();
		$this->userModel = $this->CI->load->model('user/userModel');
	}

	public function getWidgetData() {
		$customParams = $this->_params['customParams'];
		$customParams['courseLevel'] = $this->userModel->getUserCourseLevel($customParams['userId']);
		$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/streamDigestMailHeader", $customParams, true);
		return array('key' => 'streamDigestMailHeader','data' => $widgetHTML);
	}
}

?>