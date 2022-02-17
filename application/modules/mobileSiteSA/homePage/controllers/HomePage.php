<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class HomePage extends ShikshaMobileWebSite_Controller
{
	public $getTabsContentByCategory = array();

	function __construct()
	{
		parent::__construct();
		$this->homepagelib = $this->load->library('homePage/HomePageLib');
		$this->load->config('studyAbroadMobileConfig');
	}

	function renderHome()
	{	
		$displayData 								= array();
		$displayData['trackForPages'] = true; //For JSB9 Tracking
		$displayData['quickLinksData'] 				= $this->config->item('FOOTER_QUICK_LINKS');
		$displayData['trendingCourses']	 			= $this->homepagelib->getTrendingCourses();
		$displayData['coverage'] 					= $this->homepagelib->getCountStats();
		$listingLib 								= $this->load->library('listingPage/listingPageLib');
		$displayData['recentCourses']  	 			= $listingLib->getRecentCoursesData();
		$displayData['topGuides']  	 				= $this->homepagelib->getGuidesForHomePage();
		$displayData['applyContent']  	 			= $this->homepagelib->getApplyContent();
		
        
        //tracking
		$this->_prepareTrackingData($displayData);
		//$displayData['jqMobileFlag'] = true;
        $this->load->view('homePage',$displayData);
	}
	private function _prepareTrackingData(&$displayData)   
        {    
            
            $displayData['beaconTrackData'] = array(
                                              'pageIdentifier' => 'homePage',
                                              'pageEntityId' => '0',
                                              'extraData' => null
                                              );
        }
	
//	function getDataForCourseSelectionWidget($ldbCourseId,$categoryId,$courseLevel){
//		$data = $this->homepagelib->getCountryDataForCourseSelectionWidget($ldbCourseId,$categoryId,$courseLevel);
//		$courseWidgetResult = $this->load->view("widgets/browseCollegesCountryList",array('courseSelectionWidgetData'=>$data),true);
//		$countryLayerResult = $this->load->view("layers/countryList",array('courseSelectionWidgetData'=>$data),true);
//		echo json_encode(array('widgetCountries' => $courseWidgetResult,'countryList'=>$countryLayerResult));
//	}
	
}
