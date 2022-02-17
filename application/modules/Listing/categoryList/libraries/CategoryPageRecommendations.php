<?php

class CategoryPageRecommendations
{
    private $_ci;
    
    function __construct()
    {
        $this->_ci = & get_instance();
        $this->_ci->load->library('recommendation_front_lib');
		$this->_ci->load->model('recommendation_model');
    }
    
    function getAlsoViewedInstitutes($courseId)
	{
	    $recommendations = $this->_ci->recommendation_front_lib->getAlsoViewedInstitutes($courseId);
	    return $this->_formatRecommendations($recommendations);
	}

        function getAbroadAlsoViewedInstitutes($courseId)
        {
                $this->_ci->load->library('recommendation_abroad/abroad_alsoviewed');
                $recommendations = $this->_ci->abroad_alsoviewed->getAlsoViewedListings(array($courseId));
                return $this->_formatRecommendations($recommendations);
        }
	
	function getMahoutAlsoViewedInstitutes($courseId)
	{
		if(is_array($courseId)) {
			$courseId = $courseId[0];
		}
	    $recommendations = $this->_ci->recommendation_model->getMahoutRecommendations($courseId);
	    return $this->_formatRecommendations($recommendations);
	}
	
	function getSimilarInstitutes($courseId,$LDBCourseId,$pageCityId,$customExclusionList,$limitCount=9)
	{		
		$this->_ci->load->builder('ListingBuilder','listing');
	    $listingBuilder = new ListingBuilder;
	    $courseRepository = $listingBuilder->getCourseRepository();
	    
	    unset($listingBuilder);				//unset to check for memory utilization
	    		
		$this->_ci->load->builder('LocationBuilder','location');
	    $locationBuilder = new LocationBuilder;
	    $locationRepository = $locationBuilder->getLocationRepository();			
	    
	    unset($locationBuilder);			//unset to check for memory utilization
		
		$this->_ci->load->library('recommendation/similarinstitutes');
		
		$course = $courseRepository->find($courseId);
		$cityId = $course->getMainLocation()->getCity()->getId();
		$stateId = $course->getMainLocation()->getState()->getId();
		
		unset($courseRepository);   //unset to check for memory utilization
		
		if($pageCityId) {
			$pageCity = $locationRepository->findCity($pageCityId);
			if($pageCity) {
				$cityId = $pageCity->getId();
				$stateId = $pageCity->getStateId();
			}
		}
		
		
		$instituteId = $course->getInstId();
		
		$exclusionList = array();
		if(!empty($instituteId)) {
			$exclusionList = array($instituteId);
		}
		
		if($customExclusionList) {
			$customExclusionList = explode(',',$customExclusionList);
			foreach($customExclusionList as $inst) {
				$exclusionList[] = $inst;
			}
		}
	
		/*
         Prepare seed data for similar institutes algo
        */
        $seedData = array(array('course_id' => $course->getId(),'ldb_course_id' => $LDBCourseId,'city_id' => $cityId));
		$recommendations = $this->_ci->similarinstitutes->getSimilarInstitutes($seedData,$limitCount,$exclusionList);
		
		$stateResultsIncluded = FALSE;
		
		/**
		
		 * if recommendations are less than required no., relax criteria to state
		 */
		if(count($recommendations) < 9) {
			/**
			 * Add to exclusion list
			 */
			foreach($recommendations as $reco) {
				$exclusionList[] = $reco[0];
			}

			$seedData = array(array('course_id' => $course->getId(),'ldb_course_id' => $LDBCourseId,'state_id' => $stateId));
			$stateBasedRecommendations = $this->_ci->similarinstitutes->getSimilarInstitutes($seedData,9,$exclusionList);
			foreach($stateBasedRecommendations as $reco) {
				$recommendations[] = $reco;
				$stateResultsIncluded = TRUE;
				if(count($recommendations) == 9) {
					break;
				}
			}
		}

		
		$recommendations = $this->_formatRecommendations($recommendations);
        return array('recommendations' => $recommendations,'stateResultsIncluded' => $stateResultsIncluded);
	}
	
	
	function getAbroadSimilarInstitutes($courseId,$countryId,$customExclusionList)
	{
	    $this->_ci->load->builder('ListingBuilder','listing');
	    $listingBuilder = new ListingBuilder;
	    $courseRepository = $listingBuilder->getAbroadCourseRepository();
		
	    $this->_ci->load->library('recommendation/similarinstitutes');
		
	    $course = $courseRepository->find($courseId);
	    $instituteId = $course->getInstId();
	    
	    $exclusionList = array($instituteId);
	    
	    if($customExclusionList) {
		    $customExclusionList = explode(',',$customExclusionList);
		    foreach($customExclusionList as $inst) {
			    $exclusionList[] = $inst;
		    }
	    }
	    
	    /*
	     Prepare seed data for similar institutes algo
	    */
	    $seedData = array(array('course_id' => $course->getId(),'country_id' => $countryId));
	    $recommendations = $this->_ci->similarinstitutes->getSimilarInstitutes($seedData,9,$exclusionList);
	    $recommendations = $this->_formatRecommendations($recommendations);
	    return $recommendations;
	}
	
	private function _formatRecommendations($recommendations)
	{
		$formattedRecommendations = array();
		foreach($recommendations as $recommendation) {
			$instituteId = $recommendation[0];
			$courseId = $recommendation[1];
			$formattedRecommendations[$instituteId] = $courseId;
		}
		return $formattedRecommendations;
	}
	
	function redirectUserIfNotLoggedIn($category_id)
	{
		$this->_ci->load->library('Category_list_client');
		$categoryTree = $this->_ci->category_list_client->getCategoryTree(1);
		
		foreach($categoryTree as $category) {
			if($category['categoryID'] == $category_id) {
				$category_url = constant('SHIKSHA_'. strtoupper($category['urlName']) .'_HOME');
			}
		}
		header("Location: $category_url");
	}

	function getMILByCourseId($courseId){
		$this->_ci->load->builder('ListingBuilder','listing');
	    $listingBuilder = new ListingBuilder;
	    $courseRepository = $listingBuilder->getCourseRepository();	   
	    unset($listingBuilder);				//unset to check for memory utilization	    				
		$course = $courseRepository->find($courseId);
				
		$data['subCategoryId'] = $course->getCourseSubCategory();
		$data['desiredCourse'] = $course->getDesiredCourseId();
		$data['levelCourse']   = $course->getCourseLevel1Value();
		$data['courseId']      = $courseId;
		$data['countryId']     = $course->getCountryId();
		$solrRequestGenerator = $this->_ci->load->library('categoryList/solr/AbroadCategoryPageSolrRequestGenerator');
		$solrRequestUrl       = $solrRequestGenerator->generateURLToGetMILByCourse($data);
		$solrClient           = $this->_ci->load->library("SASearch/AutoSuggestorSolrClient");
		$response             = $solrClient->getCategoryPageResults($solrRequestUrl, 'MIL ON thank you page');
		$groups = $response['grouped']['saUnivId']['groups'];	
		$milCourseIds = array();
		foreach ($groups as $key => $group) {
			$docs = $group['doclist']['docs'];
			foreach($docs as $doc) {
				$universityId = $doc['saUnivId'];
				$courseId     = $doc['saCourseId'];
				$milCourseIds[$universityId] = $courseId;				
			}			
		}

		return $milCourseIds;		
	}
}
