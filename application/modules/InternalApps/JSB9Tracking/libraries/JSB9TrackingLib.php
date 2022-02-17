<?php
class JSB9TrackingLib{
	private $CI;

 	function __construct(){
 		$this->CI =& get_instance();
 	}

	public function getSelectedModulePages($teamPageMap,$moduleName,$sourceApplicationFilter,$selectedSite){
		$selectedModulePages = array();
		if(strtolower($moduleName) == strtolower($selectedSite)){
			if($sourceApplicationFilter != 'all'){
				foreach ($teamPageMap as $team) {
					$selectedModulePages = array_merge($selectedModulePages,$team[$sourceApplicationFilter]);
				}
			}else{
				$selectedModulePages = array('all');
			}
		}else{
			if($sourceApplicationFilter == 'all'){
				foreach ($teamPageMap[$moduleName] as $sourceApplication => $pages) {
					$selectedModulePages = array_merge($selectedModulePages,$pages);	
				}
			}else{
				$selectedModulePages = $teamPageMap[$moduleName][$sourceApplicationFilter];
			}
		}
		return $selectedModulePages;
	}

	public function getJSB9DataForSelectedModule($selectedModulePages,$fromDate,$toDate,$selectedSite){
		// this is temprory , require API from jsb9 team
		$this->CI->load->model('JSB9Trackingmodel');
		$this->JSB9TrackingModel = new JSB9Trackingmodel();
		$result = $this->JSB9TrackingModel->getJSB9DataForSelectedModule($selectedModulePages,$fromDate,$toDate,$selectedSite);
		return $result;
	}

	public function formatJSB9TrackingData($JSB9TrackingData, $pageAttributes){
		$JSB9TrackingFormattedData = array();
		$possibleAttributes = array_keys($pageAttributes);
		foreach ($JSB9TrackingData as $row) {
			if(!is_array($JSB9TrackingFormattedData[$row['PageName']][$row['Date']])){
				$JSB9TrackingFormattedData[$row['PageName']][$row['Date']] = $pageAttributes;	
			}
		
			if(in_array($row['AttributeName'], $possibleAttributes)){	
				$JSB9TrackingFormattedData[$row['PageName']][$row['Date']][$row['AttributeName']] = $row['Average'];	
				if($row['AttributeName'] == 'Server Processing Time'){
					$JSB9TrackingFormattedData[$row['PageName']][$row['Date']]['Page Views'] = $row['Count'];
				}
			}
		}
		return $JSB9TrackingFormattedData;
	}
}
?>
