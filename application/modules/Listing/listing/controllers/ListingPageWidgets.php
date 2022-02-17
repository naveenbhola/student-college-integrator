<?php

class ListingPageWidgets extends MX_Controller
{
	function placementCompanies($recrutingCompanies)
	{
		if(count($recrutingCompanies) <= 0 || $recrutingCompanies[0]->getLogoURL() == ""){
			return false;
		}
		return $this->load->view('listing/listingPage/widgets/placementCompanies',array('recrutingCompanies' => $recrutingCompanies),true);
	}
	
	function salaryStatastics($salary){
		if($salary['min'] || $salary['max'] || $salary['avg']){
			
			$salaryForIndex = ($salary['max']!=0)?$salary['max']:(($salary['avg']!=0)?$salary['avg']:$salary['min']);
    
			if($salaryForIndex%5!=0){
				$graphIndex = $salaryForIndex +'10'-($salaryForIndex%10);
			}else{
				$graphIndex = $salaryForIndex;
			}
		
			$salary['min'] = $salary['min']/100000;
			$salary['max'] = $salary['max']/100000;
			$salary['avg'] = $salary['avg']/100000;
			return $this->load->view('listing/listingPage/widgets/salaryStatastics',array('salary' => $salary,'graphIndex' => $graphIndex),true);
		}
		
	}
	
	function mediaWidget($photos,$videos,$mediaTabUrl)
	{
		if(count($media = array_merge($photos,$videos)) <= 0){
			return false;
		}
		shuffle($media);
		return $this->load->view('listing/listingPage/widgets/mediaWidget',array('media' => $media, 'photos' => $photos, 'videos' => $videos, 'mediaTabUrl' => $mediaTabUrl),true);
	}
	
	function alumniSpeak($institute_id){
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$this->instituteRepository = $listingBuilder->getInstituteRepository();
		$displayData['institute'] = $this->instituteRepository->find($institute_id);
		//$displayData['alumnisReviews'] = $this->instituteRepository->findAlumanisReviewsOnInstitute($institute_id);
		return $this->load->view('listing/listingPage/widgets/alumniWidget',$displayData,true);
	}
	
	function shikshaAnalytics($institute_id,$course_id,$pageType){
		$this->load->builder('ListingBuilder','listing');
		$this->load->library('listing_client');
		$Listing_client = new Listing_client();
		$listingBuilder = new ListingBuilder;
		if($pageType == 'course'){
			$this->courseRepository = $listingBuilder->getcourseRepository();
			$course = $this->courseRepository->findcourseWithValueObjects($course_id,array('viewcount'));
			$displayData['viewCount'] = $course->getCumulativeViewCount()->getCount();
		}else{
			$this->instituteRepository = $listingBuilder->getInstituteRepository();
			$institute = $this->instituteRepository->findInstituteWithValueObjects($institute_id,array('viewcount'));
			$displayData['viewCount'] = $institute->getCumulativeViewCount()->getCount();
		}
		$searchCount = $Listing_client->getSearchSnippetCount(1,'institute',$institute_id);
		$displayData['searchCount'] = $searchCount[0]['count'];
		$leads = $Listing_client->getCountForResponseForm($institute_id);
		//$leads = $this->ldbmodel->getLeadsForInstitutes(array($displayData['institute']->getId()),false);
		$displayData['responseCount'] = $leads;
		$displayData['pageType'] = $pageType;
		$this->load->view('listing/listingPage/widgets/analyticsWidget',$displayData);
	}
	
	
 function shikshaAnalyticsForNationalPage($institute_id,$course_id,$pageType,$updatedTime)
	{
		/* Adding XSS cleaning (Nikita) */
		$institute_id = $this->security->xss_clean($institute_id);
		$course_id = $this->security->xss_clean($course_id);
		$pageType = $this->security->xss_clean($pageType);
		$updatedTime = $this->security->xss_clean($updatedTime);
		
		/* Last Updated Date */
		$updatedDate = explode ( "-", $updatedTime );
		$updatedDateReadableFormat = array (
				$updatedDate [2],
				$updatedDate [1],
				$updatedDate [0]
		);
		$updatedDate = implode ( "/", $updatedDateReadableFormat );
		$displayData['updatedDate'] = $updatedDate;
		
		/* View Count */
		
		$this->load->builder('ListingBuilder','listing');
		$this->load->library('listing_client');
		$Listing_client = new Listing_client();
		$listingBuilder = new ListingBuilder;
		if ($pageType == 'course') {
			$this->instituteRepository = $listingBuilder->getInstituteRepository ();
			$coursesOfInstitute = $this->instituteRepository->getCoursesOfInstitutes ( array ($institute_id) );
			$courseIdList = $coursesOfInstitute [$institute_id] ['courseList'];
			$LastYearViewResponseCountForInstitute = $this->instituteRepository->getLastYearViewResponseCountForInstitute ( $institute_id, $courseIdList );
			$displayData ['viewCount'] = $LastYearViewResponseCountForInstitute [$institute_id] ['viewCount'];
			$displayData ['responseCount'] = $LastYearViewResponseCountForInstitute [$institute_id] ['responseCount'];
		}
		else if ($pageType == 'institute') {
			$this->instituteRepository = $listingBuilder->getInstituteRepository ();
			$coursesOfInstitute = $this->instituteRepository->getCoursesOfInstitutes ( array ($institute_id) );
			$courseIdList = $coursesOfInstitute [$institute_id] ['courseList'];
			$LastYearViewResponseCountForInstitute = $this->instituteRepository->getLastYearViewResponseCountForInstitute ( $institute_id, $courseIdList );
			$displayData ['viewCount'] = $LastYearViewResponseCountForInstitute [$institute_id] ['viewCount'];
			$displayData ['responseCount'] = $LastYearViewResponseCountForInstitute [$institute_id] ['responseCount'];
		}
		
		 $this->load->view('national/widgets/shikshaAnalytics',$displayData);
			//echo " yes coming";die();
		
	}
	


	function seeAllBranches($listing,$locations,$overlay="no", $sourcePage = NULL){
		$displayData['name']  = html_escape($listing->getName());
		$displayData['url']  = $listing->getURL();
		$displayData['listing']  = $listing;
		$displayData['overlay'] = $overlay;
		$displayData['instituteCourses']= $listing->instituteCourses;
		$locations = $locations ? $locations : $listing->getLocations();
		if(count($locations) <= 1){
			return "";
		}
		$displayData['loctionsWithLocality'] = array();
		$displayData['otherLocations'] = array();
		foreach($locations as $location){
			if($location->getLocality() && $location->getLocality()->getName()){
				$city = $location->getCity();
				$displayData['loctionsWithLocality'][$city->getId()][] = $location;
			}else{
				$displayData['otherLocations'][] = $location;
			}
		}
		
		if($sourcePage == "search"){
			return $this->load->view('search/search_course_branches_layer',$displayData,true);
		} else {
			return $this->load->view('listing/listingPage/widgets/seeAllBranches',$displayData,true);
		}
	}
	
	
	function contactDetails($institute, $course,$currentLocation,$overlay="no",$buttons=true){
        
		$displayData['institute']  = $institute;
		$displayData['course']  = $course;
		$displayData['currentLocation']  = $currentLocation;
        $displayData['overlay']  = $overlay;
		$displayData['buttons']  = $buttons;
		
		return $this->load->view('listing/listingPage/widgets/contactDetails',$displayData,true);
	}
	
	function contactDetailsNotUpdated($institute, $course,$currentLocation,$overlay="no",$buttons=true){
        $displayData['institute']  = $institute;
		$displayData['course']  = $course;
		$displayData['currentLocation']  = $currentLocation;
        $displayData['overlay']  = $overlay;
		$displayData['buttons']  = $buttons;
		
		return $this->load->view('listing/national/widgets/contactDetailsLayerNotUpdated',$displayData,true);
	}
	
	function contactDetailsTop($is_ajax = "")
	{
		global $listings_with_localities;
		
		$preferred_city = $this->input->post('preferred_city', true);
		$preferred_locality = $this->input->post('preferred_locality', true);
		$course_id = $this->input->post('course_id', true);
		$institute_id = $this->input->post('institute_id', true);
		$listing_type = $this->input->post('listing_type', true);
		$overlay = $this->input->post('overlay', true);
		$page_selected_city_id = intval($this->input->post('page_selected_city_id', true));
		$page_selected_locality_id = intval($this->input->post('page_selected_locality_id', true));
		
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		
		$this->courseRepository = $listingBuilder->getCourseRepository();
        $course = $this->courseRepository->find($course_id);
		
		if($institute_id == 0)
		{
			$institute_id = $course->getInstId();
		}
		$this->instituteRepository = $listingBuilder->getInstituteRepository();
		$institute = $this->instituteRepository->find($institute_id);
		
		//$displayData = array();
		
		if(in_array($institute_id,$listings_with_localities['SMU'])) {
			$displayData['currentLocation'] = Modules::run('listing/ListingPage/getPrefferedLocationObject', $course, $page_selected_city_id, $page_selected_locality_id);	
		}
		else {
			$displayData['currentLocation'] = Modules::run('listing/ListingPage/getPrefferedLocationObject', $course, $preferred_city, $preferred_locality);
		}
				
		$displayData['institute'] = $institute;
		$displayData['course']    = $course;
		$displayData['overlay']   = $overlay;
		$displayData['listingType']   = $listing_type;
		if($course->getMainLocation()->getCountry()->getId() != 2) {
			if($is_ajax == 'YES') {
				$response = $this->load->view('listing/listingPage/widgets/contact-layer',$displayData,true);
				echo base64_encode($response);
			} else {
				$this->load->view('listing/listingPage/widgets/contact-layer',$displayData);
			}
		} else {
			if($is_ajax == 'YES') {
				$response = $this->load->view('listing/national/widgets/contactDetailsLayer.php',$displayData,true);
				echo base64_encode($response);
			} else {	
				$this->load->view('listing/national/widgets/contactDetailsLayer.php',$displayData);
			}
		}	
	}
	
	function contactDetailsBottom($institute, $course,$currentLocation,$overlay="no",$buttons=true){
        
		$displayData['institute']  = $institute;
		$displayData['course']  = $course;
		$displayData['currentLocation']  = $currentLocation;
        $displayData['overlay']  = $overlay;
		$displayData['buttons']  = $buttons;
		
		return $this->load->view('listing/listingPage/widgets/contactDetailsBottom',$displayData,true);
	}
}
