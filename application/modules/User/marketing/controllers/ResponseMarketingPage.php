<?php

class ResponseMarketingPage extends MX_Controller
{
	const RESULTS_PER_PAGE = 15;
	
    function index()
    {
    	ini_set('memory_limit', '2048M');
		/**
		 * Load dependencies
		 */
		$this->load->builder('LDBCourseBuilder','LDB');
        $LDBCourseBuilder = new LDBCourseBuilder;
        $LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();
        
        $this->load->builder('CategoryBuilder','categoryList');
		$categoryBuilder = new CategoryBuilder;
		$categoryRepository = $categoryBuilder->getCategoryRepository();
        
		$this->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder;
		$locationRepository = $locationBuilder->getLocationRepository();
		
        $this->load->builder('CategoryPageBuilder','categoryList');
		$categoryPageBuilder = new CategoryPageBuilder;
        
        $this->load->library('categoryList/CategoryPageRequest');
		
		/**
		 * Collect request data
		 */
		$isAJAXRequest = (int) $this->input->post('AJAX');
		$pageNumber = (int) $this->input->post('pageNumber');
		$isLoadMoreResultsRequest = (int) $this->input->post('loadMoreResultsRequest');
		
		/**
		 * Make category page request using LDB Course Id
		 */ 
        $LDBCourseId = 2;
        $LDBCourse = $LDBCourseRepository->find($LDBCourseId);
        
        $subCategory = $categoryRepository->getCategoryByLDBCourse($LDBCourseId);
        $subCategoryId = $subCategory->getId();
        $categoryId = $subCategory->getParentId();
        
        $currentPageNumber = $pageNumber ? $pageNumber : 1;
	
        $categoryPageRequest = new CategoryPageRequest;
        $categoryPageRequest->setData(array(
           'categoryId' => $categoryId,
           'subCategoryId' => $subCategoryId,
           'LDBCourseId' => $LDBCourseId,
           'cityId' => 1,
           'stateId' => 1,
           'countryId' => 2,
		   'pageIdentifier' => 'ResponseMarketing',
		   'pageNumber' => $currentPageNumber
        ));
        
		/**
		 * Set pre-applied filters
		 * i.e. filters passed in the URL
		 */ 
		$filters = array();
		if(!$isAJAXRequest) {
			if($filters = $this->_getPreAppliedFilters()) {
				$this->_setPreAppliedFilters($categoryPageRequest,$filters);
			}
			else {
				$this->_clearAppliedFilters($categoryPageRequest);
			}
		}
		
		/**
		 * Fetch the category page using category page request created above
		 */ 
        $categoryPageBuilder->setRequest($categoryPageRequest);
        $categoryPage = $categoryPageBuilder->getCategoryPage();
        
		$resultsPerPage = self::RESULTS_PER_PAGE;
		$institutesOnPage = array();
		$pageNumberCount = 1;
		$numInstitutesToSkip = ($currentPageNumber - 1) * $resultsPerPage;
		$paidInstituteCount = 0;
		$canLoadMoreInstitutes = FALSE;
		
		/**
		 * Fetch PAID-ONLY institutes from category page until we get desired
		 * number of institutes to be shown on current page
		 */ 
		while(true) {
			
			/**
			 * Statrting with 1, fetch results for each page number until we get desired no. of PAID results
			 */ 
			$categoryPageRequest->setData(array('pageNumber' => $pageNumberCount));
			$categoryPage->setRequest($categoryPageRequest);
			$institutes = $categoryPage->getInstitutes(TRUE);
			
			/**
			 * No more institutes left
			 */ 
			if(count($institutes) == 0) {
				break;
			}
			
			foreach($institutes as $instituteId => $institute) {
				$course = $institute->getFlagshipCourse();
				
				/**
				 * If course is not paid, all subsequent results will be non-paid
				 * so no need to check further
				 */ 
				if(!$course->isPaid()) {
					break 2;
				}
				
				/**
				 * Skip institutes already displayed
				 */ 
				$paidInstituteCount++;
				if($paidInstituteCount <= $numInstitutesToSkip) {
					continue;
				}
				
				/**
				 * Add to institutes to be displayed on page
				 */ 
				$institutesOnPage[$instituteId] = $institute;
				
				/**
				 * Got desired no. for the page
				 * Have one extra to set flag that there are more to load
				 */ 
				if(count($institutesOnPage) == $resultsPerPage+1) {
					break 2;
				}
			}
			$pageNumberCount++;
		}
		
		/**
		 * We have at least one more paid institute left
		 * set flag that there are more institutes to load
		 * and remove the last institute we added for this check
		 */ 
		if(count($institutesOnPage) == $resultsPerPage+1) {
			$canLoadMoreInstitutes = TRUE;
			array_pop($institutesOnPage);
		}
		
		/**
		 * Prepate data for view
		 */ 
		$data = array();
		$data['categoryPage'] = $categoryPage;
		$data['institutes'] = $institutesOnPage;
		$data['request'] = $categoryPageRequest;
		$data['passedFilters'] = $this->_formatFiltersForDisplay($filters);
		$data['canLoadMoreInstitutes'] = $canLoadMoreInstitutes;
		$data['currentPageNumber'] = $currentPageNumber;
		
		$data['categoryRepository'] = $categoryRepository;
		$data['LDBCourseRepository'] = $LDBCourseRepository;
		$data['locationRepository'] = $locationRepository;
		
		$data['loadMoreResultsRequest'] = $isLoadMoreResultsRequest;
		
		global $listings_with_localities;
		$data['listings_with_localities']= json_encode($listings_with_localities);
		
		$data['validateuser'] = $this->checkUserValidation();
		
		if($isAJAXRequest) {
			$this->load->view('marketing/ResponseMarketingPage/snippets',$data);
		}
		else {
			$this->load->view('marketing/ResponseMarketingPage/main',$data);
		}
    }
	
	/**
	 * Get pre-applied filters i.e. filters passed in URL
	 */ 
	private function _getPreAppliedFilters()
	{
		/**
		 * Make GET variables i.e. query string params available
		 */
		$this->load->helper('security');
        $data = xss_clean($_GET);
		parse_str($_SERVER['QUERY_STRING'], $data);
		
		/**
		 * Extract filter values from URL
		 * Different values of a filter are separated by comma
		 * Underscores in in a filter value is converted back to spaces
		 */ 
		$filterTypes = array("duration","exams","AIMARating","mode","courseLevel","degreePref","classTimings","locality","city","country");
		
		$filters = array();
		
		foreach($filterTypes as $filterType) {
			if(isset($data[$filterType]) && $filter = $data[$filterType]) {
				$filterValues = array_unique(explode(',',$filter));
				for($i=0;$i<count($filterValues);$i++) {
					$filterValues[$i] = str_replace('_',' ',$filterValues[$i]);	
				}
				$filters[$filterType] = $filterValues;
			}
		}
		return $filters;
	}
	
	/**
	 * Set pre-applied filters on the page
	 */ 
	private function _setPreAppliedFilters($categoryPageRequest,$filters)
	{
		setcookie('response_marketing_filters-'.$categoryPageRequest->getPageKey(),base64_encode(json_encode($filters)),time() + 2592000 ,'/',COOKIEDOMAIN);
		$_COOKIE['response_marketing_filters-'.$categoryPageRequest->getPageKey()] = base64_encode(json_encode($filters));
	}
	
	/**
	 * Clear all applied filters on the page
	 */ 
	private function _clearAppliedFilters($categoryPageRequest)
	{
		setcookie('response_marketing_filters-'.$categoryPageRequest->getPageKey(),'',time() - 2592000 ,'/',COOKIEDOMAIN);
		$_COOKIE['response_marketing_filters-'.$categoryPageRequest->getPageKey()] = '';
	}
	
	/**
	 * Format filters passed in URL for display in view
	 * Also escape the filter values
	 */ 
	private function _formatFiltersForDisplay($filters)
	{
		$this->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder;
		$locationRepository = $locationBuilder->getLocationRepository();
		
		$formattedFilters = array();
		
		foreach($filters as $filterKey => $filterValues) {
			$filterValues = array_map('html_escape',$filterValues);
			
			/**
			 * Fetch city names for city ids
			 */ 
			if($filterKey == 'city') {
				$cityNames = array();
				foreach($filterValues as $cityId) {
					$city = $locationRepository->findCity($cityId);
					if($city) {
						$cityNames[] = $city->getName();
					}
				}
				$formattedFilters['city'] = $cityNames;
			}
			else {
				$formattedFilters[$filterKey] = $filterValues;
			}
		}
		
		return $formattedFilters;
	}
	
	public function loadResponseForm($instituteId,$courseId)
	{
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$courseRepository = $listingBuilder->getCourseRepository();
		
		$courses = $instituteRepository->getLocationwiseCourseListForInstitute($instituteId);
		
		$courseList = array();
		foreach($courses as $course){
			$courseList = array_merge($courseList,$course['courselist']);
		}
		
		$courseList = array_unique($courseList);
		$institute = reset($instituteRepository->findWithCourses(array($instituteId => $courseList)));
		$courses = $institute->getCourses();
		
		foreach($courses as $course){
			if($course->isPaid()){
				$data['courses'][] = $course;
			}
		}
		
		$course = $courseRepository->find($courseId);
		
		$locations = $course->getLocations();
		$currentLocation = $course->getMainLocation();
		$data['currentLocation'] = $currentLocation;
		
		$data['institute'] = $institute;
		$data['course'] = $course;
		$data['widget'] = 'widget_'.$institute->getId();
		
		$data['validateuser'] = $this->checkUserValidation();
		
		$this->load->view('marketing/ResponseMarketingPage/responseForm',$data);
	}
	
	function listingDetail()
	{
		$url = $this->input->post('url');
		$instituteId = $this->input->post('instituteId');
		
		//$url = str_replace('www.shiksha.com','localshiksha.com',$url);
		
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$institute = $instituteRepository->find($instituteId);
		
		$data = array('url' => $url,'institute' => $institute);
		$this->load->view('marketing/ResponseMarketingPage/listingDetail',$data);
	}
}
