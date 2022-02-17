<?php 

class InstituteDataAggregator{
	
	private $_CI;
	private $courseId;

	public function __construct()
	{
		$this->_CI = & get_instance();
	    // load the builder
	    $this->_CI->load->builder("nationalInstitute/InstituteBuilder");
	    $instituteBuilder = new InstituteBuilder();

	    // get institute repository with all dependencies loaded
	    $this->instituteRepo = $instituteBuilder->getInstituteRepository();
	    $this->instituteRepo->disableCaching();

		$this->_CI->load->library(array('indexer/processors/institute/InstituteBasicData',
										'indexer/processors/institute/instituteFacilityData',
										'ShikshaPopularity/ShikshaPopularityDataLib'
										));

		$this->_CI->load->helper("search/SearchUtility");

	}

	/**
	* Function to get the Course Object and Process/Format the Data for Solr
	*/
	// 49258 = facility
	public function getData($instituteId = 49258, $fieldArray = array(), $extraData = array()){
		
		$sections =  $this->_filterSectionsInformation($fieldArray);
		$instituteData = array();

		if(!empty($sections)){
			// Get the Course Object
			$instituteObj = $this->instituteRepo ->find($instituteId,$sections);
			
			// Process the Sections Data for Solr
			$instituteData = $this->_processDataFromObject($instituteId, $instituteObj, $sections, $extraData);
		}
		
		return $instituteData;
	}

	// 
	private function _processDataFromObject($instituteId,$instituteObj,$sections, $extraData){
		
		// For Each Sections, iterate and process the Data
		foreach ($sections as $key => $sectionName) {
			$instituteData[$sectionName] = $this->_processSections($instituteId, $instituteObj, $sectionName);
		}
		return $instituteData;
		//$this->_separateSections($courseData, 'basic', 'course_order', array());
	}

	private function _processSections($instituteId, $instituteObj,$sectionName){
		$courseData = array();
		switch ($sectionName) {
			case 'basic':
				// Process Basic Section Data
				$sectionData = $this->_CI->institutebasicdata->compileData($instituteObj);
				break;

			case 'facility' : 
				$sectionData = $this->_CI->institutefacilitydata->compileData($instituteObj);
				break;

			case 'view_count':
				// View Count
				$viewCount = $this->_CI->listingcommonlib->listingViewCount($instituteObj->getListingType(),array($instituteId));
				if(!empty($viewCount) && !empty($viewCount[$instituteId])){
					$sectionData['nl_institute_view_count_year'] = $viewCount[$instituteId];	
				}else{
					$sectionData['nl_institute_view_count_year'] = 0;	
				}
				break;

			case 'last_modify_date':
				$lastModifyDate = $this->_CI->listingcommonlib->getNationalListingsLastModifyDate($instituteObj->getListingType(),array($instituteId));
				if(!empty($lastModifyDate)){
					$sectionData['nl_institute_last_modify_date'] = solrDateFormater($lastModifyDate[$instituteId]);
				}else{
					$sectionData['nl_institute_last_modify_date'] = null;
				}
				break;

			case 'popularity' :
				$sectionData = $this->_CI->shikshapopularitydatalib->getPopularityDataForInstitute($instituteId);
				break;


		}
		return $sectionData;
	}

	public function getCoursesListForInstitues($instituteId){
		$courseList = $this->instituteRepo->getCoursesListForInstitutes(array($instituteId));

		if(array_key_exists($instituteId, $courseList)){
			$courseList = $courseList[$instituteId];
		}else{
			$courseList = array();
		}
		return $courseList;
	}

	private function _filterSectionsInformation($sections=array()){

		$sectionsForObject = array();
		$sectionMappings = $this->_CI->config->item('InstituteSectionMappng');

		if(empty($sections)){
			$sections = array_keys($sectionMappings);
		}
		
		foreach ($sections as $key => $value) {
			$internalSections = $sectionMappings[$value];
			if(is_array($internalSections)){
				$sectionsForObject = array_merge($sectionsForObject,$internalSections);	
			}else{
				$sectionsForObject[] = $internalSections;
			}
		}
		return $sectionsForObject;

	}



}

?>
