<?php

class search_qer_model extends MY_Model {
	private $dbHandle = '';
	private $shikshaDbHandle = '';
   
    function __construct(){
		parent::__construct('QER');
    }
	
	private function initiateModel($mode = "write"){
		$this->dbHandle = NULL;
		if($mode == 'read'){
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
	}
	
	private function initiateModelForShikshaDB($mode = "write"){
		$this->shikshaDbHandle = NULL;
		if($mode == 'read'){
			$this->shikshaDbHandle = $this->getReadHandleByModule('Search');
		} else {
			$this->shikshaDbHandle = $this->getWriteHandleByModule('Search');
		}
	}
	
	public function syncCountries($countries = array()){
		$returnLog = array();
		$returnLog['success_count'] = 0;
		$returnLog['failed_count'] = 0;
		$returnLog['failed_ids'] = array();
		$this->initiateModel();
		if(!empty($countries)){
			foreach($countries as $country){
				$countryId = $country['id'];
				$countryName = $country['name'];
				if(!empty($countryId) && !empty($countryName)){
					$queryCmd = "INSERT INTO country
								(ldb_id,
								local_full_name,
								ldb_full_name,
								url,
								variant_csv,
								more_popular_abb)
								VALUES
								(
								  ?,
								  ?,
								  ?,
								   NULL,
								   NULL,
								   NULL
								)
								ON DUPLICATE KEY UPDATE
								ldb_id = $countryId,
								local_full_name = ?,
								ldb_full_name = ?
								";
					$query = $this->dbHandle->query($queryCmd, array($countryId, addslashes($countryName), addslashes($countryName), addslashes($countryName), addslashes($countryName)));
					if($query === true){
						$returnLog['success_count']++;
					} else {
						$returnLog['failed_count']++;
						$returnLog['failed_ids'][] = $cityId;
					}
				}
			}
		}
		return $returnLog;
	}
	
	public function syncStates($states = array()){
		$returnLog = array();
		$returnLog['success_count'] = 0;
		$returnLog['failed_count'] = 0;
		$returnLog['failed_ids'] = array();
		$this->initiateModel();
		if(!empty($states)){
			foreach($states as $state){
				$stateId = $state['id'];
				$stateName = $state['name'];
				if(!empty($stateId) && !empty($stateName)){
					$queryCmd = "INSERT INTO state
								(ldb_id,
								local_full_name,
								ldb_full_name,
								url,
								variant_csv,
								more_popular_abb)
								VALUES
								(
								  ?,
								  ?,
								  ?,
								   NULL,
								   NULL,
								   NULL
								)
								ON DUPLICATE KEY UPDATE
								ldb_id = $stateId,
								local_full_name = ?,
								ldb_full_name = ?
								";
					$query = $this->dbHandle->query($queryCmd, array($stateId, addslashes($stateName), addslashes($stateName), addslashes($stateName), addslashes($stateName)));
					if($query === true){
						$returnLog['success_count']++;
					} else {
						$returnLog['failed_count']++;
						$returnLog['failed_ids'][] = $cityId;
					}
				}
			}
		}
		return $returnLog;
	}
	
	public function syncCities($cities = array()){
		$returnLog = array();
		$returnLog['success_count'] = 0;
		$returnLog['failed_count'] = 0;
		$returnLog['failed_ids'] = array();
		$this->initiateModel();
		if(!empty($cities)){
			foreach($cities as $city){
				foreach($city as $individualCityData){
					$cityId = $individualCityData['id'];
					$cityName = $individualCityData['name'];
					if(!empty($cityId) && !empty($cityName)){
						$queryCmd = "INSERT INTO city
									(ldb_id,
									local_full_name,
									ldb_full_name,
									url,
									variant_csv,
									more_popular_abb)
									VALUES
									(
									  ?,
									  ?,
									  ?,
									   NULL,
									   NULL,
									   NULL
									)
									ON DUPLICATE KEY UPDATE
									ldb_id = $cityId,
									local_full_name = ?,
									ldb_full_name = ?
									";
						$query = $this->dbHandle->query($queryCmd, array($cityId, addslashes($cityName), addslashes($cityName), addslashes($cityName), addslashes($cityName)));
						if($query === true){
							$returnLog['success_count']++;
						} else {
							$returnLog['failed_count']++;
							$returnLog['failed_ids'][] = $cityId;
						}
					}
				}
			}
		}
		return $returnLog;
	}
	
	public function syncInstitute($institute = NULL){
		$this->initiateModel();
		$returnValue = "failed PARAM_ISSUE";
		if(!empty($institute)){
			$instituteId = $institute['id'];
			$instituteName = $institute['name'];
			/*
			if($instituteId == 45622){
				$institute['relatedWords'] = trim($institute['relatedWords'],' ,') . ", DJ Training, RJ Training";
			} else if(in_array($instituteId, array(41353, 41354, 38132))){
				$institute['relatedWords'] = trim($institute['relatedWords'],' ,') . ", BMU, BML Munjal, BML Munjal University";
			}*/
			$relatedWords = $institute['relatedWords'];
			$websiteComponent = explode("?", $institute['website']);
			$website = $websiteComponent[0];
			
			$queryUpdateString = "";
			
			$queryUpdateString = " ldb_id = $instituteId, local_full_name = '".addslashes($instituteName)."', ldb_full_name = '".addslashes($instituteName)."', more_popular_abb = '".addslashes($relatedWords)."'";
									  
			if(strlen(trim($website)) > 0){
				$queryUpdateString = " ldb_id = $instituteId, local_full_name = '".addslashes($instituteName)."', ldb_full_name = '".addslashes($instituteName)."',more_popular_abb = '".addslashes($relatedWords)."', url = '".addslashes($website)."'";
			}
			
			if(!empty($instituteId) && !empty($instituteName)){
				$queryCmd = "INSERT INTO institute (ldb_id, local_full_name, ldb_full_name, url, variant_csv, more_popular_abb) VALUES ( ?, ?, ?, ?, NULL, ? ) ON DUPLICATE KEY UPDATE $queryUpdateString ";
				$query = $this->dbHandle->query($queryCmd, array($instituteId, addslashes($instituteName), addslashes($instituteName), addslashes($website), addslashes($relatedWords)));
				if($query === true){
					$returnValue = "success";
				} else {
					$returnValue = "failed DB_OP";
				}
			} else {
				$returnValue = "failed INSUFFICIENT DATA";
			}
		}
		return $returnValue;
	}
	
	public function syncDeletedInstitute($instituteId = NULL){
		$this->initiateModel();
		$returnValue = "failed PARAM_ISSUE";
		if(!empty($instituteId)){
			$queryCmd = "DELETE
						 FROM
						 institute
						 WHERE
						 ldb_id = ?
						";
			$query = $this->dbHandle->query($queryCmd, $instituteId);
			if($query === true){
				$returnValue = "success";
			} else {
				$returnValue = "failed DB_OP";
			}
		} else {
			$returnValue = "failed INSUFFICIENT DATA";
		}
		return $returnValue;
	}
	
	
	public function syncQERLdbCourses($course = array()){
		$returnValue = "failed PARAM_ISSUE";
		$this->initiateModel();
		if(!empty($course)){
			$courseId = $course['id'];
			$courseLocalName = $course['local_full_name'];
			$courseLdbName = $course['ldb_full_name'];
			if(!empty($courseId) && !empty($courseLdbName)){
				$queryCmd = "INSERT INTO course
							(ldb_id,
							local_full_name,
							ldb_full_name,
							url,
							variant_csv,
							more_popular_abb)
							VALUES
							(
							   ?,
							  ?,
							  ?,
							   NULL,
							   NULL,
							   NULL
							)
							ON DUPLICATE KEY UPDATE
							ldb_id = $courseId,
							local_full_name = ?,
							ldb_full_name = ?
							";
				$query = $this->dbHandle->query($queryCmd, array($courseId, addslashes($courseLocalName), addslashes($courseLdbName), addslashes($courseLocalName), addslashes($courseLdbName)));
				if($query === true){
					$returnValue = "success";
				} else {
					$returnValue = "failed INSUFFICIENT DATA";
				}
			} else {
				$returnValue = "failed DB_OP";
			}
		}
		return $returnValue;
	}
	
	public function getQERLdbCourseDetails($offset = 0, $limit = 1000, $getTestPrep = 0){
		$this->initiateModelForShikshaDB('read');
		$data = array();
		if($getTestPrep) {
			$whereTestPrep = " AND (cat2.boardId = 14 || map.scope = 'abroad') "; //get all test prep courses as well as aborad courses to remove from QER
		} else {
			$whereTestPrep = " AND cat2.boardId != 14 AND map.scope = 'india' "; //don't sync test prep or abroad courses in QER
		}
		$queryCmd = "
					SELECT 
					map.SpecializationId as id,
					if( cat.name = map.coursename OR LOWER(map.specializationname)<>'all', concat( map.coursename, '::', map.specializationname )  , concat( cat.name, '::', map.coursename )) as local_full_name,
					if( cat.name = map.coursename OR LOWER(map.specializationname)<>'all', concat( map.coursename, '::', map.specializationname ), concat( cat.name, '::', map.coursename )) as ldb_full_name
					FROM
					tCourseSpecializationMapping as map,
					categoryBoardTable as cat,
					LDBCoursesToSubcategoryMapping as subcat,
					categoryBoardTable as cat2
					WHERE subcat.`ldbCourseID` = map.SpecializationId AND
					cat2.boardId = map.`CategoryId` AND
					cat.boardId = subcat.`categoryID` AND
					map.status = 'live' 
					$whereTestPrep
					LIMIT ?, ?
					";
		$query = $this->shikshaDbHandle->query($queryCmd, array($offset, $limit));
		
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$tempResultArray = array();
				$tempResultArray['id'] = $row->id;
				$tempResultArray['local_full_name'] = $row->local_full_name;
				$tempResultArray['ldb_full_name'] = $row->ldb_full_name;
				$data[] = $tempResultArray;
			}
		}
		return $data;
	}
	
	public function getVirtualCityInformationForCities($cityIds = array()){
		$this->initiateModelForShikshaDB('read');
		$data = array();
		if(!empty($cityIds)){
			foreach($cityIds as $cityId){
				$queryCmd = "
							SELECT countryCityTable.city_id, countryCityTable.city_name
							FROM
							countryCityTable
							WHERE
							city_id = (SELECT virtualCityMapping.virtualCityId
										FROM
										virtualCityMapping
										WHERE
										virtualCityMapping.city_id = ? AND
										virtualCityMapping.city_id != virtualCityMapping.virtualCityId
										)
							";
				$query = $this->shikshaDbHandle->query($queryCmd, $cityId);
				if($query->num_rows() > 0) {
					$count = 0;
					$data[$cityId] = array();
					foreach($query->result() as $row) {
						$data[$cityId]['id'] = $cityId;
						$data[$cityId]['virtual_city_id'] = $row->city_id;
						$data[$cityId]['name'] = $row->city_name;
						$count++;
					}
				}			
			}
		}
		return $data;
	}
	
	public function getMissingInstitutesFromShikshaDBForQER(){
		$this->initiateModelForShikshaDB('read');
		$data = array();
		$queryCmd = "
					SELECT distinct listing_type_id FROM `listings_main` WHERE status = 'live' and listing_type = 'institute';
					";
		$query = $this->shikshaDbHandle->query($queryCmd);
		$instituteIds = array();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$instituteIds[] = $row->listing_type_id;
			}
		}
		$this->initiateModel('read');
		$qerInstituteIds = array();
		if(count($instituteIds) > 0){
			$queryCmd = "
						SELECT ldb_id FROM institute WHERE 1;
						";
			$query = $this->dbHandle->query($queryCmd);
			$qerInstituteIds = array();
			if($query->num_rows() > 0) {
				foreach($query->result() as $row) {
					$qerInstituteIds[] = $row->ldb_id;
				}
			}	
		}
		$instituteIdsDiff = array();
		if(count($instituteIds) > 0 && count($qerInstituteIds) > 0){
			$instituteIdsDiff = array_diff($instituteIds, $qerInstituteIds);
		}
		return $instituteIdsDiff;
	}
	
	public function getLDBCourseDetailsAlreadyAddedToQER(){
		$this->initiateModel();
		$data = array();
		$queryCmd = "
					SELECT ldb_id as id from course
					";
		$query = $this->dbHandle->query($queryCmd);
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[] = $row->id;
			}
		}
		return $data;
	}
	
	public function removeTestPrepCoursesAndInstitutes($testPrepCourseIds, $testPrepInstituteIds) {
		$this->initiateModel('write');

		if(!empty($testPrepCourseIds)) {
			$queryCmd = "DELETE from course where ldb_id IN (?)";
			$query = $this->dbHandle->query($queryCmd, array($testPrepCourseIds));
		}
		
		if(!empty($testPrepInstituteIds)) {
			$queryCmd = "DELETE from institute where ldb_id IN (?)";
			$query = $this->dbHandle->query($queryCmd, array($testPrepInstituteIds));
		}
	}

	public function getTestPrepInstitutes() {
		$this->initiateModelForShikshaDB('read');
		$queryCmd = "SELECT distinct lm.listing_type_id
					FROM listings_main lm, institute i
					WHERE lm.listing_type_id = i.institute_id AND 
					lm.listing_type = 'institute' AND
					lm.status = 'live' AND
					i.status = 'live' AND
					i.institute_type IN ('Department', 'Department_Virtual', 'Test_Preparatory_Institute')";
		$queryResult = $this->shikshaDbHandle->query($queryCmd)->result_array();
		foreach ($queryResult as $value) {
			$testPrepInstituteIds[] = $value['listing_type_id'];
		}

		return $testPrepInstituteIds;
	}

	public function syncExams($examsData){
		$this->initiateModel('write');
		
		if(!empty($examsData)){
			$counter = 0;
			$insertQuery = "INSERT INTO exam(ldb_id,local_full_name,ldb_full_name) VALUES";
			foreach ($examsData as $key => $value) {
				$insertQuery .= "(".$value['id'].",".$this->dbHandle->escape($value['name']).",".$this->dbHandle->escape($value['name'])."),";
			}
			$insertQuery = trim($insertQuery,",");
			$insertQuery .= " ON DUPLICATE KEY UPDATE local_full_name = VALUES(local_full_name),ldb_full_name = VALUES(ldb_full_name)";

			$this->dbHandle->query($insertQuery);
		}
	}

	public function syncCareers($careersData){
		$this->initiateModel('write');
		
		if(!empty($careersData)){
			$counter = 0;
			$insertQuery = "INSERT INTO career(ldb_id,local_full_name,ldb_full_name) VALUES";
			foreach ($careersData as $key => $value) {
				$insertQuery .= "(".$value['id'].",".$this->dbHandle->escape($value['name']).",".$this->dbHandle->escape($value['name'])."),";
			}
			$insertQuery = trim($insertQuery,",");
			$insertQuery .= " ON DUPLICATE KEY UPDATE local_full_name = VALUES(local_full_name),ldb_full_name = VALUES(ldb_full_name)";

			$this->dbHandle->query($insertQuery);
		}
	}
	public function syncStreams($streamsData){
		$this->initiateModel('write');
	
		if(!empty($streamsData)){
			$counter = 0;
			$insertQuery = "INSERT INTO stream(ldb_id,local_full_name,ldb_full_name,more_popular_abb) VALUES";
			foreach ($streamsData as $key => $value) {
				$insertQuery .= "(".$value['stream_id'].",".$this->dbHandle->escape($value['name']).",".$this->dbHandle->escape($value['name']).",".$this->dbHandle->escape($value['synonym'])."),";
			}
			$insertQuery = trim($insertQuery,",");
			$insertQuery .= " ON DUPLICATE KEY UPDATE local_full_name = VALUES(local_full_name),ldb_full_name = VALUES(ldb_full_name),more_popular_abb=VALUES(more_popular_abb)";

			$this->dbHandle->query($insertQuery);
		}
	}

	public function syncSubStreams($subStreamsData){
		$this->initiateModel('write');
	
		if(!empty($subStreamsData)){
			$counter = 0;
			$insertQuery = "INSERT INTO substream(ldb_id,local_full_name,ldb_full_name,more_popular_abb) VALUES";
			foreach ($subStreamsData as $key => $value) {

				$insertQuery .= "(".$value['substream_id'].",".$this->dbHandle->escape($value['name']).",".$this->dbHandle->escape($value['name']).",".$this->dbHandle->escape($value['synonym'])."),";
			}
			$insertQuery = trim($insertQuery,",");
			$insertQuery .= " ON DUPLICATE KEY UPDATE local_full_name = VALUES(local_full_name),ldb_full_name = VALUES(ldb_full_name),more_popular_abb=VALUES(more_popular_abb)";
			
			$this->dbHandle->query($insertQuery);
		}

	}

	public function syncSpecializations($specializationData){
		$this->initiateModel('write');
	
		if(!empty($specializationData)){
			$counter = 0;
			$insertQuery = "INSERT INTO specialization(ldb_id,local_full_name,ldb_full_name,more_popular_abb) VALUES";
			foreach ($specializationData as $key => $value) {

				$insertQuery .= "(".$value['specialization_id'].",".$this->dbHandle->escape($value['name']).",".$this->dbHandle->escape($value['name']).",".$this->dbHandle->escape($value['synonym'])."),";
			}
			$insertQuery = trim($insertQuery,",");
			$insertQuery .= " ON DUPLICATE KEY UPDATE local_full_name = VALUES(local_full_name),ldb_full_name = VALUES(ldb_full_name),more_popular_abb=VALUES(more_popular_abb)";

			$this->dbHandle->query($insertQuery);
		}
	}

	public function syncInstituteNew($instituteData){
		$this->initiateModel('write');
	
		if(!empty($instituteData)){
			$counter = 0;
			
			$insertQuery = "INSERT INTO institute(ldb_id,local_full_name,ldb_full_name,more_popular_abb,url) VALUES";
			foreach ($instituteData as $key => $value) {
				$synonymsArr = array();
				if(!empty($value['synonym'])) {
					$synonymsArr = explode(";", $value['synonym']);
				}
				if(!in_array($value['abbreviation'], $synonymsArr) && !empty($value['abbreviation'])) {
					$synonymsArr[] = $value['abbreviation'];
				}
				$value['synonym'] = implode(',', $synonymsArr);
				
				$insertQuery .= "(".$value['institute_id'].",".$this->dbHandle->escape($value['name']).",".$this->dbHandle->escape($value['name']).",".$this->dbHandle->escape($value['synonym']).",".$this->dbHandle->escape($value['website_url'])."),";
			}
			$insertQuery = trim($insertQuery,",");
			$insertQuery .= " ON DUPLICATE KEY UPDATE local_full_name = VALUES(local_full_name),ldb_full_name = VALUES(ldb_full_name),more_popular_abb=VALUES(more_popular_abb),url = VALUES(url)";
			$this->dbHandle->query($insertQuery);
		}
	}

	public function syncPopularGroups($groupsData){
	
		$this->initiateModel('write');
	
		if(!empty($groupsData)){
			$counter = 0;
			$insertQuery = "INSERT INTO popular_group(ldb_id,local_full_name,ldb_full_name,more_popular_abb) VALUES";
			foreach ($groupsData as $key => $value) {

				$insertQuery .= "(".$value['popular_group_id'].",".$this->dbHandle->escape($value['name']).",".$this->dbHandle->escape($value['name']).",".$this->dbHandle->escape($value['synonym'])."),";
			}
			$insertQuery = trim($insertQuery,",");
			$insertQuery .= " ON DUPLICATE KEY UPDATE local_full_name = VALUES(local_full_name),ldb_full_name = VALUES(ldb_full_name),more_popular_abb=VALUES(more_popular_abb)";

			$this->dbHandle->query($insertQuery);
		}		
	}

	public function syncCertificateProvider($certiData){
		$this->initiateModel('write');
	
		if(!empty($certiData)){
			$counter = 0;
			$insertQuery = "INSERT INTO certificate_provider(ldb_id,local_full_name,ldb_full_name,more_popular_abb) VALUES";
			foreach ($certiData as $key => $value) {

				$insertQuery .= "(".$value['certificate_provider_id'].",".$this->dbHandle->escape($value['name']).",".$this->dbHandle->escape($value['name']).",".$this->dbHandle->escape($value['synonym'])."),";
			}
			$insertQuery = trim($insertQuery,",");
			$insertQuery .= " ON DUPLICATE KEY UPDATE local_full_name = VALUES(local_full_name),ldb_full_name = VALUES(ldb_full_name),more_popular_abb=VALUES(more_popular_abb)";

			$this->dbHandle->query($insertQuery);
		}			
	}

	public function syncQERBaseCourses($coursesData){
		$this->initiateModel('read');
		if(!empty($coursesData)){
			$counter = 0;
			$insertQuery = "INSERT INTO base_course(ldb_id,local_full_name,ldb_full_name,more_popular_abb) VALUES";
			foreach ($coursesData as $key => $value) {

				$insertQuery .= "(".$value['base_course_id'].",".$this->dbHandle->escape($value['name']).",".$this->dbHandle->escape($value['name']).",".$this->dbHandle->escape($value['synonym'])."),";
			}
			$insertQuery = trim($insertQuery,",");
			$insertQuery .= " ON DUPLICATE KEY UPDATE local_full_name = VALUES(local_full_name),ldb_full_name = VALUES(ldb_full_name),more_popular_abb=VALUES(more_popular_abb)";

			$this->dbHandle->query($insertQuery);
		}
	}

	public function syncUniversityNew($universityData){
		$this->initiateModel('write');
	
		if(!empty($universityData)){
			$counter = 0;
			$insertQuery = "INSERT INTO university(ldb_id,local_full_name,ldb_full_name,more_popular_abb,url) VALUES";
			foreach ($universityData as $key => $value) {

				$insertQuery .= "(".$value['university_id'].",".$this->dbHandle->escape($value['name']).",".$this->dbHandle->escape($value['name']).",".$this->dbHandle->escape($value['synonym']).",".$this->dbHandle->escape($value['website_url'])."),";
			}
			$insertQuery = trim($insertQuery,",");
			$insertQuery .= " ON DUPLICATE KEY UPDATE local_full_name = VALUES(local_full_name),ldb_full_name = VALUES(ldb_full_name),more_popular_abb=VALUES(more_popular_abb),url = VALUES(url)";
			$this->dbHandle->query($insertQuery);
		}
	}

	public function syncDeletedUniversity($universityIds){
		$this->initiateModel('write');
		if(is_array($universityIds)){
			$universityIds = array_unique(array_filter($universityIds));
			if(!empty($universityIds)){
				$sql = "DELETE FROM university WHERE ldb_id IN (?)";
				$query = $this->dbHandle->query($sql, array($universityIds));
			}
		}
	}

	public function syncDeletedInstituteNew($instituteIds){
		$this->initiateModel('write');
		if(is_array($instituteIds)){
			$instituteIds = array_unique(array_filter($instituteIds));
			if(!empty($instituteIds)){
				$sql = "DELETE FROM institute WHERE ldb_id IN (?)";
				$query = $this->dbHandle->query($sql, array($instituteIds));
			}
		}
	}

	public function getCitiesMappedToShikshaListings() {
		$this->initiateModelForShikshaDB('read');

		$sql = "select distinct city_id from shiksha_institutes_locations where status = 'live'";

		$result = $this->shikshaDbHandle->query($sql)->result_array();

		return $result;
	}

	public function getStatesMappedToShikshaListings() {
		$this->initiateModelForShikshaDB('read');

		$sql = "select distinct state_id from shiksha_institutes_locations where status = 'live'";

		$result = $this->shikshaDbHandle->query($sql)->result_array();

		return $result;
	}
}