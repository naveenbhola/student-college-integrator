<?php 

include_once '../WidgetsAggregatorInterface.php';

class streamDigestQuesAns implements WidgetsAggregatorInterface{
	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->CI = & get_instance();
	}

	public function getWidgetData() {
		// top 3 answered questions based on thread quality score from stream tag detail page
		$customParams = $this->_params['customParams'];
		$streamId     = $customParams['streamId'];
		$source       = $customParams['source'];

		$anaLibrary = $this->CI->load->library('messageBoard/AnALibrary');
		$questionsData = $anaLibrary->getAnsweredQuestiontIdBasedOnTagId($streamId, 'stream');
		foreach ($questionsData as $row) {
			$customParams['courseRecommendedQuestions']['questionsDetail'][] = array('URL' => add_query_params($row['URL'],array('utm_term' => $source)),'title' => $row['title']);
		}

		$utmTerm = 'TopQuestions';
		if(!empty($source)){
			$utmTerm = $utmTerm."_".$source;
		}

		// get tag detail page url of stream and count
		$countUrlData = $anaLibrary->getEntityCountAndUrl($streamId,'stream','question');
		$customParams['courseRecommendedQuestions']['questionCount'] = $countUrlData['count'];
		$customParams['courseRecommendedQuestions']['allQuestionURL'] = add_query_params($countUrlData['url'],array('utm_term' => $utmTerm));

		$customParams['instituteName'] = $customParams['streamName'];

		if(!empty($questionsData)){
			$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/courseRecommendedQuestions", $customParams, true);
		}
		return array('key' => 'streamDigestQuesAns', 'data' => $widgetHTML);
	}
}

?>