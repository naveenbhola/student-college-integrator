<?php 

class OAFPortingLib{
	private $CI;

	public function __construct(){
	    $this->CI = & get_instance();
	    $this->portingmodel = $this->CI->load->model('oafPorting/oafportingmodel');
	}

	public function getOAFResponses(){
		$lastprocessedId = $this->portingmodel->getLastProcessedId('OAF_RESPONSE_PORTING');
		$date = date("Y-m-d H:i:s");
		$time = strtotime($date);
		$time = $time - (15 * 60);
		$date = date("Y-m-d H:i:s", $time);
		$responses = $this->portingmodel->getOnlineFormResponses($lastprocessedId, $date);

		$onlineFormIds = array();
		foreach ($responses as $response) {
			$onlineFormIds[] = $response['onlineFormId'];
		}
		$data = $this->portingmodel->getOnlineFormMetaData($onlineFormIds);

		$formToCourseMapping = array();
		foreach ($data as $row) {
			$formToCourseMapping[$row['onlineFormId']] = $row;
		}

		foreach ($responses as $key => $row) {
			$responses[$key]['clientCourseId'] = $formToCourseMapping[$row['onlineFormId']]['courseId'];
			$responses[$key]['formId'] = $formToCourseMapping[$row['onlineFormId']]['formId'];
			$responses[$key]['formName'] = $formToCourseMapping[$row['onlineFormId']]['formName'];
			$responses[$key]['clientId'] = $formToCourseMapping[$row['onlineFormId']]['username'];
		}
		return $responses;
	}
}

?>