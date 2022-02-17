<?php 

include_once '../WidgetsAggregatorInterface.php';

class streamDigestColleges implements WidgetsAggregatorInterface{
	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->CI = & get_instance();
		$this->nationalCategoryPageLib = $this->CI->load->library('nationalCategoryList/NationalCategoryPageLib');
		$this->popularityLib = $this->CI->load->library('ShikshaPopularity/ShikshaPopularityDataLib');
	}

	public function getWidgetData() {
		// get top 3 colleges by popularity
		$customParams = $this->_params['customParams'];
		$data         = $customParams;
		$streamId     = $customParams['streamId'];
		$userId       = $customParams['userId'];
		$source       = $customParams['source'];
		// get all india ctpg link and count
		$categoryData = $this->nationalCategoryPageLib->getAllIndiaPageByStreamId($streamId);
		if(!empty($categoryData)){
			
			$utmTerm = 'PopularColleges';
			if(!empty($source)){
				$utmTerm = $utmTerm."_".$source;
			}

			$data['ctpgLink'] = add_query_params($categoryData['url'],array('utm_term' => $utmTerm));
			$data['ctpgButtonText'] = 'View all '.$categoryData['result_count'].' Colleges';
			$instituteIds = $this->popularityLib->getTopCollegesByStream($streamId);
			if(!empty($instituteIds)){
				$this->CI->load->builder('nationalInstitute/InstituteBuilder');
				$instituteBuilder = new InstituteBuilder;
				$instituteRepository = $instituteBuilder->getInstituteRepository();
				$instituteObjs = $instituteRepository->findMultiple($instituteIds);
				$instituteData = array();
				foreach ($instituteObjs as $instId => $instituteObj) {
					if(!empty($instituteObj) && $instituteObj->getId()){
						$instituteData[$instId] = array('name' => $instituteObj->getName(),'url' => add_query_params($instituteObj->getURL(),array('utm_term' => $source)));
					}
				}
				$data['instituteData'] = $instituteData;
			}
			$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/streamDigestColleges", $data, true);
		}
		// _p($data);
		return array('key' => 'streamDigestColleges','data' => $widgetHTML);
	}
}

?>