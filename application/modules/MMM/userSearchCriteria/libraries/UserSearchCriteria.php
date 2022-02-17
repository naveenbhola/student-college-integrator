<?php
/**
 * searchCriteria Library
 *
 * @author
 * @package Mailer
 *
 */

class UserSearchCriteria {

	function __construct(){
		$this->CI = & get_instance();
	}

  	/**
     * Get all Form Fields Values
     *
     * @param array $cityIdArray
     */ 
	public function getFieldsData($userset_type) {

		$registrationForm = \registration\builders\RegistrationBuilder::getRegistrationForm('LDB',array('courseGroup' => 'nationalLeadSearch' ,'context' => 'search'));

		$fields                 = $registrationForm->getFields();

		$residenceCity          = $fields['residenceCity']->getValues(array('isNational'=>1));

		if($userset_type=="profile_india"){
			$data['streams']        = $fields['stream']->getValues(array('showAllStreams'=>true));
			$data['workExperience'] = $fields['workExperience']->getValues(array('national'=>'yes', 'type'=>'number'));
			$data['mode']           = $fields['educationType']->getValues();
		}
		if($userset_type=="exam"){
			$data['streams']        = $fields['stream']->getValues(array('showAllStreams'=>true));
		}

		if(!empty($residenceCity)) {
        	$formattedCities = $this->getFormattedCities($residenceCity);
        	$data['virtualCities'] = $formattedCities['virtualCities'];
        	$data['virtualCitiesParentChildMapping'] = $formattedCities['virtualCitiesParentChildMapping'];
        	$data['virtualCitiesChildParentMapping'] = $formattedCities['virtualCitiesChildParentMapping'];
        	$data['stateCities'] = $residenceCity['stateCities'];

			$this->CI->load->library(array('category_list_client'));
        	$categoryClient = new \Category_list_client();
        	$data['citiesHavingLocalities'] = json_decode($categoryClient->getAllCitiesHavingLocalities());
    	}

    	$this->CI->config->load('userSearchCriteria/searchcriteriaconfig');
		$zonemapping = $this->CI->config->item('zoneMapping');

		$zoneToStateMapping = array();

		foreach ($zonemapping as $stateZone){
			foreach ($data['stateCities'] as $key => $value) {
				if($value['StateName']==$stateZone['state']){
					$data['stateCities'][$key]['zone'][] = $stateZone['zone'];
				}
			}
			$zoneToStateMapping[$stateZone['zone']][] = $stateZone['stateId'];
		}

		$data['zoneToStateMapping'] = $zoneToStateMapping;
		$data['allZonesInMetroCity'] = $this->CI->config->item('allZonesInMetroCity');
		$data['zoneIdMapping'] = $this->CI->config->item('zoneIdMapping');

        return $data;

	}   

	public function getFormattedCities($residenceCity) {

		$i = 0;$virtualCities = array();
		$virtualCitiesParentChildMapping = array();$virtualCitiesChildParentMapping = array();
		foreach($residenceCity['virtualCities'] as $virtualCityId=>$virtualCityDetails){
			$virtualCities[$i]['cityId'] = $virtualCityId;
			$virtualCities[$i]['cityName'] = $virtualCityDetails['name'];
			$j=1;
			foreach($virtualCityDetails['cities'] as $virtualCityDetail) {
				if($virtualCityDetail['state_id'] != '-1') {
					$virtualCitiesParentChildMapping[$virtualCityId][$j]['city_id'] = $virtualCityDetail['city_id'];
					$virtualCitiesParentChildMapping[$virtualCityId][$j]['city_name'] = $virtualCityDetail['city_name'];
					$virtualCitiesChildParentMapping[$virtualCityDetail['city_id']] = $virtualCityId;
					$j++;
				}
			}
			$i++;
		}

		foreach($residenceCity['metroCities'] as $metroCityDetails){
			$virtualCities[$i]['cityId'] = $metroCityDetails['cityId'];
			$virtualCities[$i]['cityName'] = $metroCityDetails['cityName'];
			$i++;
		}	

		$formattedCities['virtualCities'] = $virtualCities;
		$formattedCities['virtualCitiesParentChildMapping'] = $virtualCitiesParentChildMapping;
		$formattedCities['virtualCitiesChildParentMapping'] = $virtualCitiesChildParentMapping;
		return $formattedCities;
		
	}

    /**
     * Get all City Localities
     *
     * @param array $cityIdArray
     */ 
	public function getCityLocalities($cityIdArray) {

		if(empty($cityIdArray)) return;

		$cityIds = implode(",", $cityIdArray);
		
		$this->CI->load->library('category_list_client');
		$categoryClient = new Category_list_client();
		$localities = json_decode($categoryClient->getLocalitiesByCity(1, $cityIds));

		$citiesLocalities = array();
 		if(!empty($localities)) { 
            foreach($localities as $localitiesArray) { 
            	$localityName = str_replace(" ","_",$localitiesArray->localityName);
            	$citiesLocalities[$localitiesArray->cityId][$localityName] = $localitiesArray;
            }
        }
        return $citiesLocalities;

	}   

	/**
     * Get all City Localities in particular format for searching from SOLR
     *
     * @param array $inputArray 
     */ 
	public function formatCityLocalities($inputArray) {

		$cityIdsArray = $inputArray['CurrentCities'];
		$localityIdsArray = $inputArray['currentLocalities'];

		$uniqueCityIdsArray = array_unique($cityIdsArray);

		$newCityIdsArray = array();$newLocalityIdsArray = array();$i = 0;
		foreach ($uniqueCityIdsArray as $cityId) {
			$newCityIdsArray[$i] = $cityId;
			if(!empty($localityIdsArray->$cityId)) {
				$newLocalityIdsArray[$i] = $localityIdsArray->$cityId;
			}
			$i++;
		}

		$finalArray = array();
		$finalArray['CurrentCities'] = $newCityIdsArray;
		$finalArray['currentLocalities'] = $newLocalityIdsArray;

        return $finalArray;

	}   

	/* Get all stream,substream,specialization in particular format for getting data from APIs
     *
     * @param stream Id, sub stream Ids array, specialization array 
     */ 
	public function formatStreamSubStreamSpecialization($streamId, $substreamIds = array(), $specializationIds = array(), $selectedFullSubStreams = array()) {
		
        $baseEntityArr = array();$i = 0;
        global $noSpecId;

        if((empty($substreamIds)) && (empty($specializationIds))) {
        	$baseEntityArr[$i]['streamId'] = $streamId;
	        $baseEntityArr[$i]['substreamId'] = 'any';
	        $baseEntityArr[$i]['specializationId'] = 'any';
        } else {
	        if(!empty($substreamIds)) {

	        	foreach($substreamIds as $subStreamId=>$substreamSpecializations) {

	        		if(!empty($substreamSpecializations)) {

	        			foreach($substreamSpecializations as $specializationId) {
	        				if($specializationId == $noSpecId){
	        					$baseEntityArr[$i]['streamId'] = $streamId;
	        					$baseEntityArr[$i]['substreamId'] = $subStreamId;
	        					$baseEntityArr[$i]['specializationId'] = 'none';
	        					$i++;
	        				} else {
	        					$baseEntityArr[$i]['streamId'] = $streamId;
	        					$baseEntityArr[$i]['substreamId'] = $subStreamId;
	        					$baseEntityArr[$i]['specializationId'] = $specializationId;
	        					$i++;	        				
	        				}     			
	        			}
	        			if(in_array($subStreamId, $selectedFullSubStreams)) {
	        				$baseEntityArr[$i]['streamId'] = $streamId;
	        				$baseEntityArr[$i]['substreamId'] = $subStreamId;
	        				$baseEntityArr[$i]['specializationId'] = 'none';
	        				$i++;
	        			}
	        			
	        		} else {
	    				$baseEntityArr[$i]['streamId'] = $streamId;
	    				$baseEntityArr[$i]['substreamId'] = $subStreamId;
	    				$baseEntityArr[$i]['specializationId'] = 'none';
	    				$i++;
	        		}
	        		
	        	}
	        }

	        if(!empty($specializationIds)) {
	        	foreach($specializationIds as $selectedSpecializationId) {
	        		$baseEntityArr[$i]['streamId'] = $streamId;
					$baseEntityArr[$i]['substreamId'] = 'none';
					$baseEntityArr[$i]['specializationId'] = $selectedSpecializationId;
					$i++;
	        	}
	        }
    	}

    	return $baseEntityArr;
	}   

	public function getExamListFromStreamBaseCourse($baseCourseIds,$streamId){
		$examBasedOnStream = $this->getExamBasedOnStream($streamId);
		$examBasedOnBaseCourses = $this->getExamBasedOnBaseCourses($baseCourseIds);
		$streamCrossBaseCourseExamList = $this->getIntersection($examBasedOnStream,$examBasedOnBaseCourses);
		$this->CI->load->builder('ExamBuilder','examPages');
 		$examBuilder = new ExamBuilder();
		$examRepository = $examBuilder->getExamRepository();

		$examDetails = $examRepository->findMultiple($streamCrossBaseCourseExamList['examIds']);
		foreach ($streamCrossBaseCourseExamList['examToGroupMap'] as $examId => $value) {
			if($examDetails[$examId]){
				$streamCrossBaseCourseExamList['examToGroupMap'][$examId]['name'] = $examDetails[$examId]->getName();
			} else {
				unset($streamCrossBaseCourseExamList['examToGroupMap'][$examId]);
			}
		}
		// foreach ($examDetails as $examId => $obj) {
		// 	$streamCrossBaseCourseExamList['examToGroupMap'][$examId]['name'] = $obj->getName();
		// }
		unset($streamCrossBaseCourseExamList['examIds']);
		return $streamCrossBaseCourseExamList;
	}

	private function getExamBasedOnStream($streamId){
		$examGroupMapping = array();
		$hierarchyModel = $this->CI->load->model('listingBase/hierarchymodel');		
		$examModel = $this->CI->load->model('examPages/exammodel');	
		$hierarchyList = $hierarchyModel->getHierarchyIdsForAllCombinations(array($streamId));
		$hierarchyList = array_column($hierarchyList,"hierarchy_id");
		$examIdList = $examModel->getExamAndGroupId($hierarchyList,array('primaryHierarchy','hierarchy'));
		return $examIdList;
	}

	private function getExamBasedOnBaseCourses($baseCourseIds){
		$examModel = $this->CI->load->model('examPages/exammodel');	
		$examIdList = $examModel->getExamAndGroupId($baseCourseIds,array('course'));
		return $examIdList;
	}

	private function getIntersection($streamExams,$baseCourseExams){
		$mapBasedOnStream = array();
		foreach ($streamExams as $key => $value) {
				$mapBasedOnStream[$value['examId']][$value['groupId']] = $value['groupId'];
		}

		foreach ($baseCourseExams as $key => $value) {
			if ($mapBasedOnStream[$value['examId']][$value['groupId']]){
				
				$examToGroupMap[$value['examId']]['groupId'][$value['groupId']] = $value['groupId'];
				$examIds[$value['examId']] = $value['examId'];
			}
		}
		$result['examToGroupMap'] = $examToGroupMap;
		$result['examIds'] = $examIds;		
		return $result;
	}
}
