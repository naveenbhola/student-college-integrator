<?php 

include_once '../WidgetsAggregatorInterface.php';

class streamDigestExams implements WidgetsAggregatorInterface{
	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->CI = & get_instance();
	}

	public function getWidgetData() {
		// show any 4 popular exams of this stream
		$customParams = $this->_params['customParams'];
		$streamId     = $customParams['streamId'];
		$source       = $customParams['source'];

		$examMainLib = $this->CI->load->library('examPages/ExamMainLib');
		$examData = $examMainLib->getExamPagesByStream($streamId);
		$examData = array_slice($examData, 0, 4, true); // pick any random 4 entries

		foreach ($examData as $key => $exam) {
			$examData[$key]['url'] = add_query_params($examData[$key]['url'],array('utm_term' => $source));
		}

		$customParams['examData'] = $examData;
		// get all exam page url for stream and count
		$allExamPageUrl = $examMainLib->getAllExamPageUrlByEntity('stream',$streamId);
		if(!empty($allExamPageUrl)){
			$utmTerm = 'ExamsAccepted';
			if(!empty($source)){
				$utmTerm = $utmTerm."_".$source;
			}
			$allExamPageUrl = addingDomainNameToUrl(array('url' => $allExamPageUrl, 'domainName' => SHIKSHA_HOME));
			$customParams['allExamPageUrl'] = add_query_params($allExamPageUrl,array('utm_term' => $utmTerm));
		}
		if(!empty($examData)){
			$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/streamDigestExams", $customParams, true);
		}
		// _p($customParams);
		return array('key' => 'streamDigestExams', 'data' => $widgetHTML);
	}
}

?>