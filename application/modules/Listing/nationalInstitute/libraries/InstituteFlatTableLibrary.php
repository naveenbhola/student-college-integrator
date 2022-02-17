<?php

class InstituteFlatTableLibrary{
	function __construct() {
		$this->CI =& get_instance();
		// load dependencies
		$this->institutedetailsmodel = $this->CI->load->model("nationalInstitute/institutedetailsmodel");
		$this->institutePostingLib = $this->CI->load->library('nationalInstitute/InstitutePostingLib');
		$this->CI->load->builder("nationalInstitute/InstituteBuilder");
	    $instituteBuilder = new InstituteBuilder();
	    $this->instituteRepo = $instituteBuilder->getInstituteRepository();
	    $this->CI->load->library('nationalInstitute/InstitutePostingLib');
    	$this->institutePostingLib = new InstitutePostingLib;
	}

	function populateFlatTable(){

		ini_set("max_execution_time", "-1");
		ini_set('memory_limit', "2000M");
	
		$comleteResult = $this->institutedetailsmodel->generateDataForFlatTable();

		$instituteCourseData = $comleteResult[0];
		$instituteNames = $comletehierarchy_Result[1];
		$instituteSat = $comleteResult[2];
		
	    $count = 0;
	    
	    foreach ($instituteCourseData as $institute => $courseIdsArray) {
	    	
	    	$count++;
			if($count % 1000  == 0){
				_P("Count is ".$count);
				error_log("Count is ".$count);
				
			}
	    	$dataToInsert = array();
	    	$isEmptyCourses = true;
	    	list($instituteId, $instituteType) = explode("_", $institute);
	    	if(empty($instituteId) || empty($instituteType)) continue;

	    	$parentHierarchy = $this->institutePostingLib->getParentHierarchyById($instituteId,$instituteType);
	    	ksort($parentHierarchy);
	    	$level = 0;
	    	foreach ($parentHierarchy as $key => $value) {
	    		$parentHierarchy[$key]['level'] = $level++;
	    	}
	    	if(empty($parentHierarchy)) continue;
	    	foreach ($parentHierarchy as $value) {
    		
	    		$courseIdsArray = array_filter($courseIdsArray);
	    		foreach ($courseIdsArray as $courseId) {
	    			$isEmptyCourses = false;

	    			$temp = array();
					$temp['course_id'] = $courseId;
					$temp['level'] = $value['level'];
					$temp['primary_parent_id'] = $instituteId;
					$temp['primary_parent_type'] = $instituteType;
					$temp['hierarchy_parent_id'] = $value['listing_id'];
					if($value['type'] == "university"){
						$hierarchy_parent_type = "university";
					}else{
						$hierarchy_parent_type = "institute";
					}
				
					$temp['primary_is_satellite'] = $instituteSat[$instituteId];
					$dataToInsert[] = $temp;
	    		}

	    		if($isEmptyCourses){
	    			$temp['course_id'] = 0;
					$temp['primary_parent_id'] = $instituteId;
					$temp['level'] = $value['level'];
					$temp['primary_parent_type'] = $instituteType;
					$temp['hierarchy_parent_id'] = $value['listing_id'];
					if($value['type'] == "university"){
						$hierarchy_parent_type = "university";
					}else{
						$hierarchy_parent_type = "institute";
					}
					
					$temp['primary_is_satellite'] = $instituteSat[$instituteId];
					$dataToInsert[] = $temp;
	    		}
    	}

    	// $this->institutedetailsmodel->insertIntoFlatTable($dataToInsert);
    	$this->institutedetailsmodel->insertIntoFlatTableNew($dataToInsert);
    }

 }

	function flatTableInstituteUpdate($instituteWithChange=null){
		if(empty($instituteWithChange)) return;

		// Delete where prinary parent
		// Get all institutes where hierarchy Parent ==> R1 Result
		// Delete All where hierarchy parent
		// Re-compute for all R1
		$institutesToBeUpdated = $this->institutedetailsmodel->updateFlatTableForRemoveMapping($instituteWithChange);
		if($institutesToBeUpdated === false) return;
		if(!in_array($instituteWithChange, $institutesToBeUpdated)){
			$institutesToBeUpdated[] = $instituteWithChange;
		}
		$instituteCoursesArray = $this->instituteRepo->getCoursesListForInstitutes($institutesToBeUpdated);
		$instituteData = $this->institutedetailsmodel->getListingData($institutesToBeUpdated);

		foreach ($instituteData as $instituteId=>$data) {
		
			$parentHierarichyDetails = $this->institutePostingLib->getParentHierarchyById($instituteId, $data['type']);
			ksort($parentHierarichyDetails);
			$level = 0;
	    	foreach ($parentHierarichyDetails as $key => $value) {
	    		$parentHierarichyDetails[$key]['level'] = $level++;
	    	}

			$dataToInsert = array();
			if(empty($instituteCoursesArray[$instituteId]))	{
				$dataToInsert = $this->makeDBData($parentHierarichyDetails,array(),$instituteId, $data['type'], $data['is_satellite']);
			}else{
				$dataToInsert = $this->makeDBData($parentHierarichyDetails, $instituteCoursesArray[$instituteId],$instituteId,$data['type'], $data['is_satellite']);
			}
	    	$this->institutedetailsmodel->insertIntoFlatTable($dataToInsert);
		}
		$this->CI->load->library('nationalInstitute/cache/NationalInstituteCache');  
        $this->CI->nationalinstitutecache->removeInstituteCourses($institutesToBeUpdated);
	
	}

	function flatTableInstituteUpdateForCoursePPChange($courseId){
		if(empty($courseId)) return;
		$this->institutedetailsmodel->removeCourseMappingsData($courseId);
	}

	function updateIsSatellite($instituteId=0,$is_satellite){
		if(empty($instituteId)) return;
		$this->institutedetailsmodel->updateIsSatelliteInFlatTable($instituteId,$is_satellite);
	}

	function makeDBData($parentHierarichyDetails, $courseIdsArray, $primaryParentId, $primaryParentType, $is_satellite){

	
    	$isEmptyCourses = true;
		$dataToInsert = array();
		foreach ($parentHierarichyDetails as $key => $value) {
			$courseIdsArray = array_filter($courseIdsArray);
			foreach ($courseIdsArray as $courseId) {
				$isEmptyCourses = false;
				$temp = array();
				$temp['course_id'] = $courseId;
				$temp['primary_parent_id'] = $primaryParentId;
				$temp['primary_parent_type'] = $primaryParentType;
				$temp['hierarchy_parent_id'] = $value['listing_id'];
				$temp['level'] = $value['level'];
				if($value['type'] == "university"){
					$hierarchy_parent_type = "university";
				}else{
					$hierarchy_parent_type = "institute";
				}
				$temp['primary_is_satellite'] = $is_satellite;
				$dataToInsert[] = $temp;
			}

			if($isEmptyCourses){
				$temp = array();
				$temp['course_id'] = 0;
				$temp['primary_parent_id'] = $primaryParentId;
				$temp['hierarchy_parent_id'] = $value['listing_id'];
				$temp['primary_parent_type'] = $primaryParentType;
				if($value['type'] == "university"){
					$hierarchy_parent_type = "university";
				}else{
					$hierarchy_parent_type = "institute";
				}
				$temp['primary_is_satellite'] = $is_satellite;
				$temp['level'] = $value['level'];
				$dataToInsert[] = $temp;	
			}
		}
		return $dataToInsert;
	}

	function flatTableUpdateOnInstDelete($instId = null){
		if(empty($instId)) return;
		$this->institutedetailsmodel->updateFlatTableForInstDelete($instId);		
	}

	function flatTableUpdateOnCourseDelete($courseId = null){
		if(empty($courseId)) return;
		$this->institutedetailsmodel->updateFlatTableForCourseDelete($courseId);		
	}


/*	function addInstitute($primaryParentId,$primaryParentType){

		$coursesArray = $this->instituteRepo->getCoursesListForInstitutes(array($primaryParentId));
		$coursesArray = reset($coursesArray);
		$parentHierarichyDetails = $this->institutePostingLib->getParentHierarchyById($primaryParentId, $primaryParentType);
		
    	$dataToInsert = $this->makeDBData($parentHierarichyDetails,$coursesArray,$primaryParentId);
    	$this->CI->load->model("indexer/NationalIndexingModel");
		$this->nationalIndexingModel = new NationalIndexingModel();
		$this->nationalIndexingModel->gggg($dataToInsert);
	}

	function addCourse($courseId){
		$primaryParent = $this->institutedetailsmodel->fetchPrimaryParent($courseId);
		if(empty($primaryParent)) return;
		$primaryParentId = $primaryParent['primary_id'];
		$primaryParentType = $primaryParent['primary_type'];
		$parentHierarichyDetails = $this->institutePostingLib->getParentHierarchyById($primaryParentId, $primaryParentType);

		$dataToInsert = array();
		$dataToInsert = $this->makeDBData($parentHierarichyDetails,array($courseId),$primaryParentId);
		_P($dataToInsert);
	}
*/


	

}
?>
