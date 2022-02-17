<?php 

class CourseStatusData {
	

	public function __construct()
	{
		$this->_CI = & get_instance();
	    $this->_CI->load->library("nationalCourse/CourseDetailLib");
	    $this->courseDetailLib = new CourseDetailLib();
	    $this->_CI->load->model("indexer/NationalIndexingModel");
		$this->nationalIndexingModel = new NationalIndexingModel();
		$this->_CI->load->config('nationalInstitute/instituteStaticAttributeConfig');

	}

	public function processCourseStatusValues($courseId, $courseObject, $hierarchyData){
		
	//	$courseStatusData = $this->courseDetailLib->getCourseStatus(array($courseId), array($courseId => $courseObject), array($courseId => $hierarchyData));

		$courseStatusData = $this->courseDetailLib->getCourseStatus(array($courseId));
		// Course Status
		$courseStatusId = null;
		$courseStatusName = null;
		$courseStatusNameIdMap = null;
		$courseStatusDependentId = null;
		$courseStatusAffiliationsId = null;
		foreach ($courseStatusData as $courseStatusDataInner) {
			foreach ($courseStatusDataInner['courseStatus'] as $value) {
				$courseStatusId[] = $value['id'];
				$courseStatusName[] = $value['value'];
				$courseStatusNameIdMap[] = $value['value'].":".$value['id'];
			}
			$courseStatusDependentId = $courseStatusDataInner['courseStatusDependentId'];
			$courseStatusAffiliationsId = reset($courseStatusDataInner['courseStatusAffiliationsId']);
		}

		$finalResult['nl_course_status_id'] = $courseStatusId;
		$finalResult['nl_course_status_name'] = $courseStatusName;
		$finalResult['nl_course_status_name_id_map'] = $courseStatusNameIdMap;
		$finalResult['nl_course_hierarchy_institute_id'] = $courseStatusDependentId;
		$finalResult['nl_course_affiliations_institute_id'] = $courseStatusAffiliationsId;

		$this->_populateAccrediationAndOwnership($finalResult, $courseObject, $hierarchyData);

		return $finalResult;
	}

	public function _populateAccrediationAndOwnership(& $finalResult, $courseObject, $hierarchyData){

		$instStaticData = $this->_CI->config->item('static_data');
		$ownershipMap = $instStaticData['ownernship'];

		$updatedOwnershipMap = array();
		foreach ($ownershipMap as $key => $value) {
			$updatedOwnershipMap[$value['value']] = $value['label'];
		}
		
		//Ownership & Accredation
		$finalResult['nl_course_accrediation_id'] = null;
		$finalResult['nl_course_accrediation_name'] = null;
		$finalResult['nl_course_accrediation_name_id_map'] = null;

		$finalResult['nl_course_ownership_id'] = null;
		$finalResult['nl_course_ownership_name'] = null;
		$finalResult['nl_course_ownership_name_id_map'] = null;
		
		$processDataFlag = true;
		if(!empty($hierarchyData['university'])){
			foreach ($hierarchyData['university'] as $universityId => $universityData) {
				if($universityData['is_primary'] == 1){
					$processDataFlag = false;
					if(!empty($universityData['ownership'])){
						$finalResult['nl_course_ownership_id'] = ucfirst($universityData['ownership']);
						$finalResult['nl_course_ownership_name'] = $updatedOwnershipMap[$universityData['ownership']];
						$finalResult['nl_course_ownership_name_id_map'] = $updatedOwnershipMap[$universityData['ownership']].":".ucfirst($universityData['ownership']);
					}

					if(!empty($universityData['accreditation'])){
						$accreditation = $this->getAccreditation($universityData['accreditation']);
						$finalResult['nl_course_accrediation_id'] = array($accreditation['id']);
						$finalResult['nl_course_accrediation_name'] = array($accreditation['name']);
						$finalResult['nl_course_accrediation_name_id_map'] = array($accreditation['name'].":".$accreditation['id']);
					}
					break;
				}
			}
		}

		if(!empty($hierarchyData['institute']) && $processDataFlag){
			foreach ($hierarchyData['institute'] as $instituteId => $instituteData) {
				if($instituteData['is_primary'] == 1){
					$processDataFlag = false;
					if(!empty($instituteData['ownership'])){
						$finalResult['nl_course_ownership_id'] = ucfirst($instituteData['ownership']);
						$finalResult['nl_course_ownership_name'] = $updatedOwnershipMap[$instituteData['ownership']];
						$finalResult['nl_course_ownership_name_id_map'] = $updatedOwnershipMap[$instituteData['ownership']].":".ucfirst($instituteData['ownership']);
					}

					if(!empty($instituteData['accreditation'])){
						$accreditation = $this->getAccreditation($instituteData['accreditation']);
						$finalResult['nl_course_accrediation_id'] = array($accreditation['id']);
						$finalResult['nl_course_accrediation_name'] = array($accreditation['name']);
						$finalResult['nl_course_accrediation_name_id_map'] = array($accreditation['name'].":".$accreditation['id']);
					}
					break;
				}	
			}
		}


		$isNBA = $courseObject->isNBAAccredited();

		if($isNBA){
			if($finalResult['nl_course_accrediation_id'] == null){
				$finalResult['nl_course_accrediation_id'] = array(NBA_APPROVAL);
				$finalResult['nl_course_accrediation_name'] = array("NBA");
				$finalResult['nl_course_accrediation_name_id_map'] = array("NBA:".NBA_APPROVAL);
			}
			else{
				$finalResult['nl_course_accrediation_id'][] = NBA_APPROVAL;
				$finalResult['nl_course_accrediation_name'][] = "NBA";
				$finalResult['nl_course_accrediation_name_id_map'][] = "NBA:".NBA_APPROVAL;	
			}	
		}
		
	}

	public function processCourseListingHierarchy($courseListingHierarchy){
		$result = array();
		foreach ($courseListingHierarchy as $key => $value) {
			if(strpos($value, "university") !== FALSE){
				$valueData = explode(":", $value);
				$result['id'] = $key;
				$result['university_specification_type'] = $valueData[1];
				$result['is_open'] = $valueData[2];
				$result['is_ugc_approved'] = $valueData[3];
				$result['is_aiu_membership'] = $valueData[4];
				break;
			}
		}
		return $result;
	}

	function getAccreditation($dbaccreditation){
		list($grade, $accreditation) = explode('_', $dbaccreditation);
		$accreditationName = "NAAC Grade ".strtoupper($accreditation);
		$accreditationId = $this->nationalIndexingModel->findAccrediationId($accreditationName);
		$accreditation = array('id' => $accreditationId , 'name' => $accreditationName);
		return $accreditation;
	}


}

?>
