<?php 
class videoCmsLib{
	private $CI;
	public function __construct(){
	    $this->CI = &get_instance();
	    $this->CI->load->config('vcmsConfig');
	    $this->CI->load->helper('shikshautility_helper');
	}

	public function getAllVideoData(&$displayData, $videoFilter = array('pageNum' => 1)){
		$apiCallerlib = $this->CI->load->library("common/apiservices/APICallerLib");
		$output = $apiCallerlib->makeAPICall("MEDIA", "/mediaservice/videoapi/v1/cms/getCMSTableData", "POST", array(), json_encode($videoFilter), array(), true, true);
		$output = json_decode($output['output'], true);
		if($output['status'] == 'success'){
			$displayData['videoList'] = $output['data']['tupleData'];
			$displayData['pageSize'] = $this->CI->config->item('videoListPageSize');
			$displayData['totalVideoCount'] = $output['data']['totalDataCount'];
		}
	}

	public function getAllVcmsFilters(&$displayData){
		$displayData['filters'] = array();
		$displayData['filters']['videoTypes'] = $this->CI->config->item('videoTypes');
		$displayData['filters']['videoSubTypes'] = $this->CI->config->item('videoSubTypes');

		$this->CI->load->builder('ListingBaseBuilder', 'listingBase');
		$listingBaseBuilder = new ListingBaseBuilder();
		$streamRepository = $listingBaseBuilder->getStreamRepository();
		$displayData['filters']['streams'] = $streamRepository->getAllStreams();

		$this->CI->load->builder('LocationBuilder', 'location');
		$locationBuilder = new LocationBuilder();
		$locationRepository = $locationBuilder->getLocationRepository();
		$displayData['filters']['states'] = $locationRepository->getStatesByCountry(2);
	}

	public function saveVideoData($data){
		$updateParam = ($data['videoId']) ? array('videoId'=>$data['videoId']) : '';
		$apiUrl = "/mediaservice/videoapi/v1/cms/save";	
		$this->CI->load->library("common/apiservices/APICallerLib");
		$output = $this->CI->apicallerlib->makeAPICall("MEDIA", $apiUrl, "POST", $updateParam, json_encode($data), array(), "");
		return $output;
	}

	function getVideoContentById($videoId){
        if(empty($videoId)){
            return;
        }
        $apiUrl = "/mediaservice/videoapi/v1/cms/getData";	
		$this->CI->load->library("common/apiservices/APICallerLib");
		$output = $this->CI->apicallerlib->makeAPICall("MEDIA", $apiUrl, "GET", array('videoId'=>$videoId));
		return json_decode($output['output'],true);
    }    
}
