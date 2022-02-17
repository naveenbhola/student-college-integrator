<?php

class AbroadAutosuggestor extends MX_Controller {
	
	public function __construct() {
		$this->load->library('SASearch/AutoSuggestorSolrClient');
        $this->autoSuggestorSolrClient = new AutoSuggestorSolrClient;

		//$this->load->builder('CategoryBuilder','categoryList');
		//$this->categoryBuilder = new CategoryBuilder;
		//$this->categoryRepository = $this->categoryBuilder->getCategoryRepository();

		//$this->load->builder('ListingBuilder','listing');
	    //$this->listingBuilder = new ListingBuilder;
	}

	public function getSuggestionsFromSolr() {
		$requestData = array();
		$requestData['text'] = (urldecode($this->input->post('text')) != false) ? urldecode($this->input->post('text')) : '';
		$requestData['text'] = $this->security->xss_clean($requestData['text']);
		$requestData['eachfacetResultCount'] = (urldecode($this->input->post('eachfacetResultCount')) != false) ? urldecode($this->input->post('eachfacetResultCount')) : 10;
		$suggestionType = (urldecode($this->input->post('suggestionType')) != false) ? urldecode($this->input->post('suggestionType')) : 'coursesWithUnivs';
		
		$solrResults = '';
		switch ($suggestionType) {
			case 'coursesUnivsExams':
				$solrResults= $this->autoSuggestorSolrClient->getCourseAndUnivSuggestionsFromSolr($requestData);
				$solrResults['examSuggestions'] = $this->autoSuggestorSolrClient->getExamSuggestionsFromSolr($requestData);
				$solrResults['searchText']=$requestData['text'];
				$saSearchLayerLib = $this->load->library('SASearch/SASearchLayerLib');
				echo $saSearchLayerLib->getAutosuggestorHTML($solrResults);
				return;
				break;
			case 'coursesWithUnivs':
				$solrResults = $this->autoSuggestorSolrClient->getCourseAndUnivSuggestionsFromSolr($requestData);
				break;
			case 'universities':
				$solrResults = $this->autoSuggestorSolrClient->getUnivSuggestionsFromSolr($requestData);
				break;
			case 'compareUniv' :
				$solrResults = array();
				$solrResults['results'] = $this->autoSuggestorSolrClient->getUnivSuggestionsFromSolr($requestData);
				$solrResults['searchText']=$requestData['text'];
				echo $this->load->view("commonModule/compareCourses/layers/searchLayerWidgets/compareCoursesSearchSuggestions",$solrResults);
				return;
			case 'locationSearchLayer': // works for both desktop & mobile
				$courseFilter = ($this->input->post('courseFilter') != false ? $this->input->post('courseFilter') : '');
				if($courseFilter != '')
				{
					$requestData['courseFilter'] = $courseFilter;
				}
				$requestData['locationIds'] = $this->input->post('locationIds',true);
				$solrResults = $this->autoSuggestorSolrClient->getLocationSuggestionsFromSolr($requestData);
				$saSearchLayerLib = $this->load->library('SASearch/SASearchLayerLib');
				echo $saSearchLayerLib->getLocationAutosuggestorHTML($solrResults,$requestData['text']);
				return;
				break;
			case 'locations':
				$solrResults = $this->autoSuggestorSolrClient->getLocationSuggestionsFromSolr($requestData);
				break;
			case 'exams':
				$solrResults = $this->autoSuggestorSolrClient->getExamSuggestionsFromSolr($requestData);
				break;
			default: 
				break;
		}
		//_p($solrResults);die;
		$jsonEncodedData = json_encode($solrResults);
		echo $jsonEncodedData;
	}

	public function processSearchedSubcatAndInstitute() {
		$data['selectedWordType'] = $this->input->post('filters_achieved');
		$data['selectedWordId'] = $this->input->post('words_achieved_id');
		$data['locationList'] = $this->input->post('locationList');
		$data['getLocations'] = $this->input->post('getLocations');
		
		// $data['selectedWordType'] = 'institute_title_facet';
		// $data['selectedWordId'] = 41334; //35861 38354 26990 4268 4211 46364
		// $data['selectedWordId'] = 'l-321 581 594 611 628 666 1459';
		// $data['selectedWordType'] = 'course_ldb_course_name_facet';
		//$data['selectedWordId'] = 's-23'; //subcat id
		//$data['locationList'] = array('city_10223', 'city_278', 'state_106', 'state_110', 'city_1');
		//$data['locationList'] = array('city_278');
		//$data['locationList'] = array();
		//$data['getLocations'] = 1;
		
		if(empty($data['selectedWordId']) && $data['getLocations'] == 1) { //when keyword is random, populate location with all locations
			$data['selectedWordType'] = 'allLocations';
		}

		if(empty($data['selectedWordId']) && $data['selectedWordType'] != 'allLocations') {
			echo json_encode(array('msg'=>'No result'));
			error_log('check if here....no result. Returning.'); //show error
			return;
		}

		$originalWordId = $data['selectedWordId'];
		switch ($data['selectedWordType']) {
			case 'institute_title_facet':
				$this->getSelectedLocations($data);
				$solrFilterResults = $this->autoSuggestorSolrClient->getFilterDataOnInsttSelection($data);
				if(!$solrFilterResults['isMultilocation']) { //condition for single location institute
					foreach($solrFilterResults['cities'] as $cityId => $cityName) {
						$solrFilterResults['cities'][$cityId] = trim(preg_replace('/other/i', '', $cityName));
					}
				}
				break;
			
			case 'course_ldb_course_name_facet':
				$this->getSelectedLocations($data);
				
				//parse selected word id
				$selectedWordArr = explode('-', $data['selectedWordId']);
				if($selectedWordArr[0] == 's') {
					$data['courseTypeSelected'] = 'subcat';
					$data['selectedWordId'] = $selectedWordArr[1];
					
					$subcatObj = $this->categoryRepository->find($data['selectedWordId']);
					$data['subcatName'] = $subcatObj->getName();
					$data['catId'] = $subcatObj->getParentId();
					$catObj = $this->categoryRepository->find($data['catId']);
					$data['catName'] = $catObj->getShortName();
				}

				if($selectedWordArr[0] == 'l') {
					$data['courseTypeSelected'] = 'ldb';
					$data['selectedWordId'] = $selectedWordArr[1];
				}

				$solrFilterResults = $this->autoSuggestorSolrClient->getFilterDataOnSubcatSelection($data);
				$solrFilterResults['catId'] = $data['catId'];
				$cities = array();
				foreach($solrFilterResults['cities'] as $cityId => $cityName) {
					if(!preg_match('/other/i', $cityName)) {
						$cities[$cityId] = $cityName;
					}
				}
				$solrFilterResults['cities'] = $cities;
				break;

			case 'allLocations':
				$data['getLocations'] = 1;
				unset($data['selectedWordId']);
				$this->getSelectedLocations($data);
				$solrFilterResults = $this->autoSuggestorSolrClient->getFilterDataOnSubcatSelection($data);
				$solrFilterResults['cities'] = array_diff_key($solrFilterResults['cities'], array('city_10166' => 'All over India')); //remove all over india city from locations
				$cities = array();
				foreach($solrFilterResults['cities'] as $cityId => $cityName) {
					if(!preg_match('/other/i', $cityName)) {
						$cities[$cityId] = $cityName;
					}
				}
				$solrFilterResults['cities'] = $cities;
				break;
			
			default:
				# code...
				break;
		}
		$solrFilterResults['selectedWordId'] 	= $originalWordId;
		$solrFilterResults['selectedWordType'] 	= $data['selectedWordType'];
		//$solrFilterResults['courseTypeSelected'] = $data['courseTypeSelected'];
		
		echo json_encode($solrFilterResults);
	}

	private function getSelectedLocations(& $data) {
		$allIndia = 0;
		if(empty($data['locationList'])) {
			$data['applyLocationFilter'] = 0;
			$cityIds = array();
			$stateIds = array();
			$allIndia = 1;
		} else {
			$data['applyLocationFilter'] = 1;
			foreach ($data['locationList'] as $key => $value) {
				$location = explode("_",$value);
				if($location[0] == 'city') {
					if($location[1] == 1) {
						$allIndia = 1;
						$data['applyLocationFilter'] = 0;
						break;
					} else {
						$cityIds[] = $location[1];
					}
				} elseif($location[0] == 'state') {
					$stateIds[] = $location[1];
				}
			}
		}
		$data['locationFilterList'] = array('city'=>$cityIds, 'state'=>$stateIds, 'allIndia'=>$allIndia);
	}

	public function getSelectedUniversityDetails(){
		$universityId = (integer)$this->input->post('universityId');
		if($universityId <= 0){
			echo json_encode(false);
			return false;
		}
		$data = $this->autoSuggestorSolrClient->getSelectedUniversityDetails($universityId);
		
		echo json_encode($data);
		return true;
	}

	public function getSelectedCourseDetails(){
		$courseData = $this->input->post('courseData');
		$locationData = $this->input->post('locationData');
		$courseData = json_decode(base64_decode($courseData),true);
		if(!is_array($courseData)){
			echo json_encode(false);
			return false;
		}
		$data = $this->autoSuggestorSolrClient->getSelectedCourseDetails($courseData,$locationData);
		echo json_encode($data);
		return true;
	}
}