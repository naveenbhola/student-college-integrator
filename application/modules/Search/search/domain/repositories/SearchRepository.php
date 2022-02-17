<?php

class SearchRepository extends EntityRepository {
	
	private $searchWrapper;
	private $config;
	
	public function __construct() {
		parent::__construct();
		$this->CI->load->entities(array('Course','CourseAttribute','SalientFeature','RecruitingCompany','CourseFees','CourseDuration','Institute'
		,'CourseDescriptionAttribute','ContactDetail','InstituteLocation','CourseLocationAttribute','ListingViewCount', 'HeaderImage'),'listing');
		$this->CI->load->entities(array('Exam'),'common');
		$this->CI->load->entities(array('Locality','Zone','City','State','Country'),'location');
		$this->CI->load->entities(array('CourseDocument', 'TagDocument'),'search');
		$this->CI->load->entities(array('Category'),'categoryList');
		
		$this->CI->load->helper('search/SearchUtility');
		$this->CI->config->load('search_config');
		$this->CI->load->builder('SearchBuilder', 'search');
		
		$this->config = $this->CI->config;
		$this->searchWrapper = SearchBuilder::getSearchWrapper();
		SearchBuilder::loadSolrDataProcessor();
	}
	
	/**
	 * @method array search : This function is a gateway to search results for outside world.
	 * Outside world should use no other function but this to get search results for institute/course/content etc
	 *
	 * @param array $params : params array has all the URL parameter that we using during the search form post.
	 * Behaviour of search results can be controlled by specifying params in correct format.
	 * 
	 * @return array : Result array has all the search results separated by well defined key.
	 * The format of result array is self explainatory
	 *
	*/
	public function search($params = array()) {
		if(!isset($params['keyword']) || empty($params['keyword'])){
			return false;
		}
		$sortType = "best";
		if(!empty($params['sort_type'])){
			$sortType = $params['sort_type'];
		}
		
		$searchResults = $this->searchWrapper->getSearchResults($params); //Get search results based on params specified
		$qerParams = $searchResults['general_institute']['qer_params_value'];
		
		$instituteResults      = $searchResults['institute'];
		$sponsoredInstituteIds = $searchResults['sponsored_institute_ids'];
		 
		//This will separate out sponsored and normal results from search results array.
		$instituteResultsByType = $this->getInstituteSearchResultsByType($instituteResults, $sponsoredInstituteIds);
		
		$instituteSearchResultsRepository = SearchBuilder::getInstituteSearchResultRepository();
		//By calling getInstitutes function of instituteSearchResultRepo on normal array, you will have object of course type
		//Here the array is getting converted into object
		$normalCourseDocumentsGroupedByInstitute 	= $instituteSearchResultsRepository->getInstitutes($instituteResultsByType['normal'], 'normal', $sortType, $qerParams);
		$sponsoredCourseDocumentsGroupedByInstitute = $instituteSearchResultsRepository->getInstitutes($instituteResultsByType['sponsored'], 'sponsored');
		$featuredCourseDocumentsGroupedByInstitute 	= $instituteSearchResultsRepository->getInstitutes($searchResults['featured_institutes'], 'featured');
		$bannerCourseDocumentsGroupedByInstitute 	= $instituteSearchResultsRepository->getInstitutes($searchResults['banner_institutes'], 'banner');
		
		$contentResults = $searchResults['content'];
		$contentSearchResultsRepository = SearchBuilder::getContentSearchResultRepository();
		//By calling getContent function of contentSearchResultsRepository on normal array, you will have object of content type like article/question/discussions
		//We need them for stats purpose later.
		$contentDocumentList = $contentSearchResultsRepository->getContent($contentResults);
		
		$searchCommon = SearchBuilder::getSearchCommon();
		//Extract out listing ids from search results. Here listing ids consist of courses/institutes/article/question/discussions
		$searchListingIds  = $searchCommon->getListingIdsFromSearchResults($searchResults);
		
		//Extract out listing ids from special search results. We need them for stats purpose later.
		$sponsoredCourseIds   = $searchCommon->getListingIdsFromBannerFeaturedResults($instituteResultsByType['sponsored']);
		$bannerCourseIds   	  = $searchCommon->getListingIdsFromBannerFeaturedResults($searchResults['banner_institutes']);
		$featuredCourseIds    = $searchCommon->getListingIdsFromBannerFeaturedResults($searchResults['featured_institutes']);
		
		//Find out the groupBy parameter of course results. It can be institute_id, course_parent_categories etc
		$groupBy = $this->getResultsGroupParam($searchResults);
		//We have all the results combined them in array and pass to outside world.
		$results = array();
		$results['normal_institutes'] 				= $normalCourseDocumentsGroupedByInstitute;
		$results['sponsored_institutes'] 			= $sponsoredCourseDocumentsGroupedByInstitute;
		$results['featured_institutes'] 			= $featuredCourseDocumentsGroupedByInstitute;
		$results['banner_institutes'] 			    = $bannerCourseDocumentsGroupedByInstitute;
		$results['content'] 						= $contentDocumentList;
		
		$results['sponsored_institute_ids'] 		= $searchResults['sponsored_institute_ids'];
		$results['banner_course_ids'] 				= $bannerCourseIds;
		$results['featured_course_ids'] 			= $featuredCourseIds;
		$results['sponsored_course_ids'] 			= $sponsoredCourseIds;
		$results['search_listing_ids'] 				= $searchListingIds;
		
		$results['solr_institute_data'] 			= $searchResults['general_institute'];
		$results['solr_content_data'] 				= $searchResults['general_content'];
		$results['institutes_group_by'] 			= $groupBy;
		$results['search_type'] 					= $searchResults['search_type'];
		$results['general']['rows_count'] 			= $searchResults['rows_count'];
		$results['facets']							= $searchResults['facets'];
		return $results;
	}
        
        
        public function searchAbroad($params = array()){
                if(!isset($params['keyword']) || empty($params['keyword'])){
			return false;
		}
		$params['keyword'] = urlencode(iconv("ISO-8859-1","UTF-8", $params['keyword']));
                $searchResults = $this->searchWrapper->getSearchAbroadResults($params);
                return $searchResults;
        }
    
	public function searchQuestions($params = array()) 
	{
		if(!isset($params['keyword']) || empty($params['keyword']))
		{
			return false;
		}
		$sortType = "best";
		if(!empty($params['sort_type'])){
			$sortType = $params['sort_type'];
		}
		$searchResults =array();
		$searchResults = $this->searchWrapper->getSearchResultsQuestions($params); //Get search results based on params specified
		$contentResults['data'] = $searchResults['content'];
		$contentSearchResultsRepository = SearchBuilder::getContentSearchResultRepository();

		//By calling getContent function of contentSearchResultsRepository on normal array, you will have object of content type like article/question/discussions
		//We need them for stats purpose later.
		$contentDocumentList = $contentSearchResultsRepository->getContent($contentResults);
		$finalResultSet = $searchResults;
		unset($finalResultSet['results']);
		$finalResultSet['content'] = $contentDocumentList;
		return $finalResultSet;
	}

	public function searchDiscussions($params = array()) 
	{
		if(!isset($params['keyword']) || empty($params['keyword']))
		{
			return false;
		}
		$sortType = "best";
		if(!empty($params['sort_type'])){
			$sortType = $params['sort_type'];
		}
		$searchResults =array();
		$searchResults = $this->searchWrapper->getSearchResultsDiscussions($params); //Get search results based on params specified
		$contentResults['data'] = $searchResults['content'];
		$contentSearchResultsRepository = SearchBuilder::getContentSearchResultRepository();
		//By calling getContent function of contentSearchResultsRepository on normal array, you will have object of content type like article/question/discussions
		//We need them for stats purpose later.
		$contentDocumentList = $contentSearchResultsRepository->getContent($contentResults);
		$finalResultSet = $searchResults;
		unset($finalResultSet['results']);
		$finalResultSet['content'] = $contentDocumentList;
		return $finalResultSet;
	}

	/**
	 * @method array getResultsGroupParam : This function finds out the groupBy parameter of course search results
	 * @param array $results : Solr search results
	 * @return string: groupby parameter
	 * * Possible Values:
	 * 1. institute_id
	 * 2. course_parent_categories
	 * 3. Sponsor_types
	 *
	*/
	private function getResultsGroupParam($results = array()){
		$groupBy = "institute_id";
		if(array_key_exists('general_institute', $results)){
			$groupBy = $results['general_institute']['group_by'];
		}
		return $groupBy;
	}
	
	/**
	 * @method array getInstituteSearchResultsByType : This function extracts the normal course results and sponsored results based on ids
	 * provided.
	 * @param array $instituteResults : Solr institute/course search results
	 * @param array $sponsoredIds : Sponsorids,
	 * @return array: arrays for sponsored and normal course results with keys.
	*/
	private function getInstituteSearchResultsByType($instituteResults = array(), $sponsoredIds = array()){
		$sponsoredResults = array();
		$normalResults = array();
		foreach($instituteResults as $institute_id => $instituteData){
			if(in_array($institute_id, $sponsoredIds)){
				$sponsoredResults[$institute_id] = $instituteData;
			} else {
				$normalResults[$institute_id] = $instituteData;
			}
		}
		$returnResult = array(
							'sponsored' =>  $sponsoredResults,
							'normal' 	=>  $normalResults,
						);
		return $returnResult;
	}

	public function searchTags($params = array()) 
	{
		if(!isset($params['keyword']) || empty($params['keyword']))
		{
			return false;
		}
	
		$searchResults =array();
		$searchResults = $this->searchWrapper->getSearchResultsTags($params); //Get search results based on params specified

		$solrDataProcessor = new SolrDataProcessor();
		foreach($searchResults['content'] as $key=>$docArray){
			$tagDocumentData = $solrDataProcessor->getTagDocumentData($docArray);	
			$TagDocumentEntity = new TagDocument();
			$this->fillObjectWithData($TagDocumentEntity, $tagDocumentData);
			$searchResults['content'][$key] = $TagDocumentEntity;
		}
		
		return $searchResults;
	}


	
}
?>
