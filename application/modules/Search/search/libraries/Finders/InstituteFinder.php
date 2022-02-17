<?php

class InstituteFinder {
	
	private $_ci;
	private $listingBuilder;
		
	public function __construct(){
		$this->_ci = & get_instance();
		$this->_ci->load->model("search/SearchModel", "", true);
		$this->_ci->load->helper("search/SearchUtility", "", true);
		$this->_ci->load->model("listing/institutemodel", "", true);
		$this->_ci->load->builder("ListingBuilder", "listing");
		$this->listingBuilder = new ListingBuilder();
		$this->_ci->load->library('ShikshaPopularity/ShikshaPopularityDataLib');
		$this->shikshaPopularityDataLib = new ShikshaPopularityDataLib();
		$this->_ci->load->model("search/SearchModel", "", true);
		$this->searchModel = new SearchModel();
	}
	
	public function getData($id = null) {
		if($id == null){
			return false;
		}
		$listingRepos = $this->listingBuilder->getInstituteRepository();
		$listingRepos->disableCaching();
		$instituteDataObject = $listingRepos->find($id);
		$instituteId = $instituteDataObject->getId();
		$instituteDataForIndex = false;
		if(!empty($instituteDataObject) && !empty($instituteId)){
			$instituteDataForIndex = $this->preprocessRawData($instituteDataObject);
			$dataSufficientFlag = $this->isDataSufficient($instituteDataForIndex);
			if(!$dataSufficientFlag){
				$instituteDataForIndex = false;
			}	
		}
		return $instituteDataForIndex;
	}
	
	public function preprocessRawData($instituteDataObject = null) {
		//institute general details
		
		$institute['institute_id'] 				 = $this->getInstituteId($instituteDataObject);
		$institute['institute_title'] 			 = $this->getInstituteTitle($instituteDataObject);
		$institute['institute_display_logo'] 	 = $this->getInstituteDisplayLogo($instituteDataObject);
		$institute['institute_pack_type'] 		 = $this->getInstitutePackType($instituteDataObject);
		$institute['institute_abbreviation'] 	 = $this->getInstituteAbbreviation($instituteDataObject);
		$institute['institute_aima_rating'] 	 = $this->getInstituteAIMARating($instituteDataObject);
		$institute['institute_usp'] 			 = $this->getInstituteUSP($instituteDataObject);
		$institute['institute_photo_count'] 	 = $this->getInstitutePhotoCount($instituteDataObject);
		$institute['institute_video_count'] 	 = $this->getInstituteVideoCount($instituteDataObject);
		$institute['institute_alumni_rating'] 	 = $this->getInstituteAlumniRating($instituteDataObject);
		$institute['institute_established_year'] = $this->getInstituteEstablishedYear($instituteDataObject);
		$institute['institute_view_count'] 		 = $this->getInstituteViewCount($instituteDataObject);
		$institute['institute_type'] 			 = $this->getInstituteType($instituteDataObject);
		$institute['institute_last_modified_date']  = solrDateFormater($this->getInstituteLastUpdatedDate($instituteDataObject));

		//Institute contact details
		$institute_contact_details  = $this->getInstituteLocationDetails($instituteDataObject);
		//Institute category info
		$flagShipCourseId 			= $this->getInstituteFlagShipCourseId($instituteDataObject);
		//Institute category ids
		$instituteCategoryIds 		= $this->getInstituteCategoryIds($institute['institute_id']);
		//Institute wiki info
		$instituteWikiInfo 			= $this->getInstituteWikiData($institute['institute_id']);

		//Institute subcat wise popularity
		$institute_popularity		= $this->getInstitutePopularityScore($institute['institute_id']);
		
		//Institute facilities
		$facility_fields = $this->getInstituteFacilities($instituteDataObject);
		
		//Final institute array
		$institute = array_merge($institute,
								 $instituteCategoryIds,
								 $instituteWikiInfo,
								 $institute_contact_details,
								 $institute_popularity,
								 $facility_fields);
		
		return $institute;
	}

	private function getInstituteDisplayLogo($instituteDataObject){
		$displayLogoLink = "";
		$displayLogoLink = $instituteDataObject->getInstituteDisplayLogo();
		return trim($displayLogoLink);
	}
	
	private function getInstituteId($instituteDataObject){
		$instituteId = -1;
		$instituteId = $instituteDataObject->getId();
		return $instituteId;
	}
	
	private function getInstituteTitle($instituteDataObject){
		$instituteTitle = "";
		$instituteTitle = $instituteDataObject->getName();
		return trim($instituteTitle);
	}
	
	private function getInstitutePackType($instituteDataObject){
		$institute_pack_type = "";
		$institute_pack_type = $instituteDataObject->getPackType();
		return trim($institute_pack_type);
	}
	
	private function getInstituteAbbreviation($instituteDataObject){
		$institute_abbreviation = "";
		$institute_abbreviation = $instituteDataObject->getAbbreviation();
		return trim($institute_abbreviation);
	}
	
	private function getInstituteAIMARating($instituteDataObject){
		$institute_aima_rating = "";
		$institute_aima_rating = $instituteDataObject->getAIMARating();
		return trim($institute_aima_rating);
	}
	
	private function getInstituteUSP($instituteDataObject){
		$institute_usp = "";
		$institute_usp = $instituteDataObject->getUsp();
		return trim($institute_usp);
	}
	
	private function getInstitutePhotoCount($instituteDataObject){
		$institute_photo_count = -1;
		$institute_photo_count = $instituteDataObject->getPhotoCount();
		return $institute_photo_count;
	}
	
	private function getInstituteVideoCount($instituteDataObject){
		$institute_video_count = -1;
		$institute_video_count = $instituteDataObject->getVideoCount();
		return $institute_video_count;
	}
	
	private function getInstituteAlumniRating($instituteDataObject){
		$institute_alumni_rating = "";
		$institute_alumni_rating = $instituteDataObject->getAlumniRating();
		return trim($institute_alumni_rating);
	}
	
	private function getInstituteEstablishedYear($instituteDataObject){
		$institute_established_year = "";
		$institute_established_year = $instituteDataObject->getEstablishedYear();
		return trim($institute_established_year);
	}
	
	private function getInstituteViewCount($instituteDataObject){
		$institute_view_count = -1;
		$institute_view_count = $instituteDataObject->getViewCount();
		return $institute_view_count;
	}
	
	private function getInstituteType($instituteDataObject){
		$institute_type = -1;
		$institute_type = $instituteDataObject->getInstituteType();
		return trim($institute_type);
	}
	
	private function getInstituteLastUpdatedDate($instituteDataObject) {
		$institute_last_modified_date = "";
		$institute_last_modified_date = $instituteDataObject->getLastUpdatedDate();
		return $institute_last_modified_date;
	}
	
	private function getInstituteFlagShipCourseId($instituteDataObject){
		$institute_flagship_courseid = -1;
		//return trim($institute_type);
	}
	
	
	private function getCategoryIds($id, $type){
		$returnArray = array('category_ids' => '', 'category_ids_trail' => '', 'category_names_trail' => '');
		$searchModel = new searchmodel();
		$categories = $searchModel->getListingCategory($id, $type);
		if(count($categories) > 0){
			$categoryIdsStr = implode($categories, ",");
			$returnArray['category_ids'] = $categoryIdsStr;
			//$categoryTrail = $searchModel->getCategoryTrail($categoryIdsStr);
			$returnArray['category_ids_info'] = $categoryTrail['category'];
			$returnArray['category_ids_trail'] = implode($categoryTrail['category_ids'], ',');
			$returnArray['category_names_trail'] = implode($categoryTrail['category_names'], ',');
		}
		return $returnArray;
	}
	
	private function getInstituteCategoryIds($id){
		$returnArray = array();
		$categoryInfo = $this->getCategoryIds($id, 'institute');
		$returnArray['institute_category_ids'] = $categoryInfo['category_ids'];
		$returnArray['institute_category_info'] = json_encode($categoryInfo['category_ids_info']);
		$returnArray['institute_all_category_ids'] = $categoryInfo['category_ids_trail'];
		//$returnArray['institute_category_ids_trail'] = $categoryInfo['category_ids_trail'];
		//$returnArray['institute_category_names_trail'] = $categoryInfo['category_names_trail'];
		return $returnArray;
	}
	
	private function getInstituteWikiData($id){
		$returnValue = array();
		$searchModel = new searchmodel();
		$wikiInfo = $searchModel->getWikiDescription($id, 'institute');
		$wikiData = array();
		foreach($wikiInfo as $info){
			$wikiData[$info['caption']] = $info['attributeValue'];
		}
		$returnValue['institute_wiki_content'] = json_encode($wikiData);
		return $returnValue;
	}

	private function getInstitutePopularityScore($id) {
		$popularityScore = array();
		$popularityScore = $this->shikshaPopularityDataLib->getInstitutePopularityScore(array($id));

		foreach ($popularityScore[$id] as $subcatId => $popularityScore) {
			$popularity['institute_popularity_subcat_'.$subcatId] = $popularityScore;
			$popularity['n_institute_popularity_subcat_'.$subcatId] = $popularityScore;
		}
		if(empty($popularity)) {
			$popularity = array();
		}
		return $popularity;
	}

	private function getInstituteFacilities($instituteDataObject){
		$facilities = array(); $facility_name = array(); $facility_fields = array();
		$institute_facilities = $instituteDataObject->getInstituteFacilities();
		foreach($institute_facilities as $facilityobj){
			array_push($facilities, trim($facilityobj->getFacilityName()).':'.trim($facilityobj->getFacilityId()));
			array_push($facility_name, trim($facilityobj->getFacilityName()));
		}
		if(!empty($facilities)){
			$facility_fields['institute_facilities'] = $facilities;
			$facility_fields['institute_facility_names'] = $facility_name;
		}
		return $facility_fields;
	}

	private function isDataSufficient($data = array()) {
		$returnFlag = false;
		if(!empty($data) && is_array($data)){
			if(!empty($data['institute_id'])){
				$returnFlag = true;
			}	
		}
		return $returnFlag;
	}
	
	private function getInstituteLocationDetails($instituteDataObject){
		$locationObject = $instituteDataObject->getLocations();
		$institute_contact_details = array();
		foreach($locationObject as $instituteLocationId => $instituteLocation){
			$institute_contact_details['institute_address'] = $instituteLocation->getAddress();
			$institute_contact_details['institute_contact_numbers'] = $instituteLocation->getContactDetail()->getContactNumbers();
			$institute_contact_details['institute_website'] = $instituteLocation->getContactDetail()->getContactWebsite();
			$institute_contact_details['institute_contact_email'] = $instituteLocation->getContactDetail()->getContactEmail();
			break;
		}
		return $institute_contact_details;
	}

}

