<?php
class Navigation extends MX_Controller{

	public function getMainHeader($data){

		$this->load->helper('navigation');
        $this->load->model('studyAbroadCommon/navigationmodel');

		$displayData 				 = array();
		$displayData['userData'] = (is_string($data['userData']) &&($data['userData']=='false')?false:$data['userData']);
        $displayData['trackingPageKeyIdForSignUp'] = $data['trackingPageKeyId'];

		$gnbNavigationCache = "HomePageRedesignCache/saGNBNavigation.html";
		$displayData['gnbNavigationCache']	 = $gnbNavigationCache;
		if(file_exists($gnbNavigationCache)){
    		$displayData['saGNBNavigationHTML'] =  file_get_contents($gnbNavigationCache);
    	}else{
	        $navigationmodel             = new navigationmodel;
	        $gnbData                     =  $navigationmodel->getNavigationData();

	        $formatGNBData               = $this->_formatGNBData($gnbData);	    
        	$displayData['gnbData'] 	 = $formatGNBData;
    	}

		$this->load->view('studyAbroadCommon/saGNB',$displayData);
	}

	private function _formatGNBData($gnbData){
	    $structureGNBData 			 = $formatGNBData = array();       

        foreach ($gnbData as $key => $val) {
			$linkData            = array();
			if(strpos($val['links_url'], 'https://') !== 0) {				
			  $val['links_url'] =  SHIKSHA_STUDYABROAD_HOME. $val['links_url'];
			}			
			$linkData['text']    = $val['links_text'];
			$linkData['url']     = $val['links_url'];
			$subSectionHeading   = ($val['sub_section_heading'] == '')?'empty':$val['sub_section_heading'];
            $structureGNBData[$val['group']][$val['main_section_heading']]['sub_section_name'][$subSectionHeading]['links'][$val['order']] = $linkData ;                  
        }

        unset($gnbData);
        
        foreach ($structureGNBData as $key => $value) {
        	$leftBucket  = array_slice($value, 0,1);
        	$rightBucket = array_slice($value, 1);
        	$formatGNBData[$key]['left']  = $leftBucket;
        	$formatGNBData[$key]['right'] = $rightBucket;        	
        }

        unset($structureGNBData);

        return $formatGNBData;
	}

	public function getMainFooter($params){
        $displayData = array();
		$footerNavigationCache = "HomePageRedesignCache/saFooterNavigation.html";
		$displayData['footerNavigationCache'] = $footerNavigationCache;
		
		if(file_exists($footerNavigationCache)){
    		$displayData['saFooterNavigationHTML'] = file_get_contents($footerNavigationCache);
    	}else{
        	$this->load->model('studyAbroadCommon/navigationmodel');
	        $navigationmodel           = new navigationmodel;
	        $footerData                = $navigationmodel->getFooterNavigationData();
	        $formatFooterData          = $this->_formatFooterData($footerData);
        	$displayData['footerData'] = $formatFooterData;
    	}
		$this->load->view('studyAbroadCommon/saFooterLinks', $displayData);
	}

	private function _formatFooterData($footerData){
		$formattedFooterData = array();
		foreach ($footerData as $value) {
			if(strpos($value['links_url'], 'https://') !== 0) {				
			  $value['links_url'] =  SHIKSHA_STUDYABROAD_HOME.$value['links_url'];
			}
			$formattedFooterData[$value['group']][] = array(
				'text' => $value['links_text'],
				'url'  => $value['links_url']
			);
		}
		return $formattedFooterData;
	}
}