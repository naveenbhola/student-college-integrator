<?php

class AbroadSearchStarter extends MX_Controller {

	/*
    * This method get search page data.
    */
    public function index() {
       // prefillData
       $prefillData = $this->input->get('prefillData', true);
       $referer = $this->input->get('ref', true);
       if(empty($referer)) {
          redirect(SHIKSHA_STUDYABROAD_HOME, 'location');
          return;
       }
       $displayData = array();
       $displayData['prefillData'] = $prefillData;
       $displayData['pageType'] = 'searchStarterPage';
       $displayData['seoTitle'] = 'Search layer';
	     $displayData['metaTitle'] = 'search layer';
	     $displayData['metaDescription'] = 'search layer';
       $displayData['canonicalURL'] = getCurrentPageURLWithoutQueryParams();
       $displayData['beaconTrackData'] = array(
           'pageIdentifier' => $referer,
           'pageEntityId' => '0',
           'extraData' => null
       );
       $displayData['skipBSB']=true;
       //for mobile site
       if(isMobileSite() != false) {
       		$this->load->view('searchPage/searchStarterPage', $displayData);
       } else {
       	    $this->load->view('SASearch/searchStarterPage', $displayData);
       }
    }
}
?>