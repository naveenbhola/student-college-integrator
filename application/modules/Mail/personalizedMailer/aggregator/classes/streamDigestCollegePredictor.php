<?php 

include_once '../WidgetsAggregatorInterface.php';

class streamDigestCollegePredictor implements WidgetsAggregatorInterface{
	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->CI = & get_instance();
		$this->CI->load->config('CP/CollegePredictorConfig',TRUE);
	}

	public function getWidgetData() {
		// get top 3 colleges by popularity
		$customParams = $this->_params['customParams'];
		$data         = $customParams;
		$streamId     = $customParams['streamId'];
		$userId       = $customParams['userId'];
		$source       = $customParams['source'];
		$streamName       = $customParams['streamName'];

		$this->cpUrlMapping = array_flip($this->CI->config->item('cpUrlMapping','CollegePredictorConfig'));
		$this->settings = $this->CI->config->item('settings','CollegePredictorConfig');
		$cpExams = $this->settings['CPEXAMS'];

		$utmTerm = 'CollegePredictor';
		if(!empty($source)){
			$utmTerm = $utmTerm."_".$source;
		}

		if(in_array($streamId,array(1,2,3,4))){
			if($streamId == 1){
				$examName = "MAHCET";
				$heading = "What MAHCET rank would you need?";
				$subHeading = "Check your eligibility for every college and quota";
			}
			else if($streamId == 2){
				$examName = "JEE-MAINS";
				$heading = "Will your JEE rank earn you a seat?";
				$subHeading = "Check your eligibility for institutes across India";
			}
			else if($streamId == 3){
				$examName = "NIFT";
				$heading = "Will your rank get you into NIFT?";
				$subHeading = "Find which institutes you have a chance to clear";
			}
			else if($streamId == 4){
				$examName = "NCHMCT";
				$heading = "Dream college with your NCHMCT rank?";
				$subHeading = "See which colleges your rank might allow you to crack";
			}
			$url = $this->cpUrlMapping[$streamName].'/resources/'.$cpExams[$examName]['collegeUrl'];
			$url = add_query_params($url,array('utm_term' => $utmTerm));
			$url = addingDomainNameToUrl(array('url' => $url,'domainName' => SHIKSHA_HOME));
			$data['predictorUrl'] = $url;
			$data['heading'] = $heading;
			$data['subHeading'] = $subHeading;

			$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/streamDigestCollegePredictor", $data, true);

			return array('key' => 'streamDigestCollegePredictor', 'data' => $widgetHTML);
		}
		else{
			return array('key' => 'streamDigestCollegePredictor', 'data' => '');
		}
	}
}

?>