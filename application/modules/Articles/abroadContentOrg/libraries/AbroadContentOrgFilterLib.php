<?php
class AbroadContentOrgFilterLib
{
    private $CI;
    
    function __construct()
    {
        $this->CI =& get_instance();
    }
    
    public function prepareFilterData($stageValues = array(),$stageId = '',$articlesData,$stageName){
	if(empty($stageValues)){
	    return array();
	}
	$stageValues = array_unique($stageValues);
	$fiterData = array();
	switch($stageId){
	    case 'COUNTRY'		:
	    case 'COLLEGE'		:
	    case 'EXAM'			:
	    case 'VISA_DEPARTURE'	:
	    case 'STUDENT_LIFE'		:
					    $this->CI->load->builder('location/LocationBuilder');
					    $locationBuilder = new LocationBuilder();
					    $locationRepo = $locationBuilder->getLocationRepository();
					    $filterCountries = $locationRepo->getAbroadCountryByIds($stageValues);
					    foreach($filterCountries as $key=>$countryObj){
						$fiterData[$countryObj->getID()] = $countryObj->getName();
					    }
					    $fiterData['all'] = 'all';
					    break;
	    
	    case 'COURSE'		:
	    case 'APPLICATION_PROCESS'	:
	    case 'SCHOLARSHIP_FUNDS'	:
					    foreach($stageValues as $key=>$value){
						$fiterData[$value] = $value;
					    }
					    break;
	}
	
	$contentId = array();
	foreach($articlesData as $article){
	    $contentId[] = $article['content_id'];
	}
	$orderedFilter = $this->_getOrderOfFilters($contentId,$stageName);
	//_p($orderedFilter);
	$finalFilterData = array();
	foreach($orderedFilter as $data){
	    $finalFilterData[$data['value']] = $fiterData[$data['value']];
	}
	//return $fiterData;
	return $finalFilterData;
	
    }
    
    private function _getOrderOfFilters($contentId, $stageName){
	$this->contentOrgModel = $this->CI->load->model('abroadContentOrg/abroadcontentorgmodel');
	return $this->contentOrgModel->getOrderOfFilters($contentId,$stageName);	
    }
}

?>    
