<?php

class coursemigrationmodel extends MY_Model {
	function __construct() {
		parent::__construct('Listing');
        
		$this->logFileName = 'log_course_migration_'.date('y-m-d');
        $this->logFilePath = '/tmp/'.$this->logFileName;

        $this->tableExtension = "";
        $this->load->config('listingMigration/universityConfig');
    }

	private function initiateModel($mode = "write") {
		if($this->dbHandle && $this->dbHandleMode == 'write') {
		    return;
		}
		
		$this->dbHandleMode = $mode;
		$this->dbHandle = NULL;
		if($mode == 'read') {
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
    }

    function getCurrencyCodes() {
        return;
    	$this->initiateModel('write');

    	$sql = "select * from currency";
    	$result = $this->dbHandle->query($sql)->result_array();
		foreach ($result as $key => $value) {
			$currencyCodes[$value['currency_code']] = $value['id'];
		}

    	return $currencyCodes;
    }

    function getBasicCourseDetails($courseId, $limit, $offset) {
        return;
    	$this->initiateModel('write');
    	$universityIds = $this->config->item('universityIds');
    	$universityIds = explode(',',$universityIds);

    	$sql =  "SELECT lm.listing_type as institute_type, cd.institute_id, ".
				"cd.course_id, cd.courseTitle as course_name, ".
				"i.institute_name, ".
    			"course_type, ".
				"course_level, ".
				"course_level_1, ".
				"course_order, ".
				"seats_total, ".
				"duration_value, duration_unit, ".
				"fees_value, fees_unit, ".
				"show_fees_disclaimer, ".
				"feestypes as fees_includes, ".
				"date_form_submission, date_result_declaration, date_course_comencement, ".
				"course_request_brochure_link, ".
				"course_request_brochure_year, ".
				"lm_1.last_modify_date as modified_on, ".
				"lm_1.editedBy as modified_by, ".
				"lm_1.submit_date as created_on ".
    			"FROM course_details cd ".
    			"INNER JOIN institute i ON i.institute_id = cd.institute_id and i.status='live' and i.institute_type ='Academic_Institute' ".
    			"INNER JOIN listings_main lm ON lm.listing_type_id = i.institute_id and lm.status='live' and lm.listing_type IN ('university_national', 'institute') ".
    			"INNER JOIN listings_main lm_1 ON lm_1.listing_type_id = cd.course_id and lm_1.status='live' and lm_1.listing_type = 'course' ".
    			"WHERE cd.status = 'live' ";

    	if(!empty($courseId)) {
			$sql = $sql." and cd.course_id = ? ";
		}
		elseif(!empty($limit)) {
			if(empty($offset)) {
				$sql = $sql." order by cd.course_id asc LIMIT $limit ";
			} else {
				$sql = $sql." order by cd.course_id asc LIMIT $limit OFFSET $offset ";
			}
		}
		
    	$result = $this->dbHandle->query($sql, array($courseId))->result_array();

    	foreach ($result as $key => $row) {
    		if(in_array($row['institute_id'],$universityIds)){
    			$result[$key]['institute_type'] = 'university';
    		}
    	}

    	return $result;
    }

    function getListingsMainDetails($courseIds) {
        return;
    	$this->initiateModel('write');

    	$sql =  "SELECT * from listings_main where status = 'live' and listing_type = 'course' ";

    	if(!empty($courseIds)) {
			$sql = $sql." and listing_type_id IN (".implode(',', $courseIds).") ";
		} else {
			$sql = $sql." ORDER BY listing_type_id asc LIMIT 100 ";
		}

		$result = $this->dbHandle->query($sql)->result_array();
		
    	return $result;
    }

    function getCourseCustomBrochures($courseIds) {
        return;
    	$this->initiateModel('write');

    	$sql =  "SELECT * from listings_ebrochures where status = 'deleted' and listingType = 'course' ";

    	if(!empty($courseIds)) {
			$sql = $sql." and listingTypeId IN (".implode(',', $courseIds).") ";
		} else {
			$sql = $sql." ORDER BY listingTypeId asc LIMIT 100 ";
		}
		$result = $this->dbHandle->query($sql)->result_array();
		
    	return $result;
    }

    function getCourseAttributes($courseIds) {
        return;
    	$this->initiateModel('write');

    	$sql =  "SELECT course_id, attribute, value from course_attributes where status = 'deleted' ".
    			"AND attribute NOT IN ('AffiliatedToIndianUni', 'AffiliatedToForeignUni', 'AffiliatedToDeemedUni', 'AffiliatedToAutonomous') ";

    	if(!empty($courseIds)) {
			$sql = $sql." and course_id IN (".implode(',', $courseIds).") ";
		} else {
			$sql = $sql." ORDER BY course_id asc LIMIT 100 ";
		}
		$result = $this->dbHandle->query($sql)->result_array();
		
    	return $result;
    }

    function getCourseSpecializations($courseIds) {
        return;
    	$this->initiateModel('write');

    	$sql =  "SELECT clientCourseID as course_id, LDBCourseID from clientCourseToLDBCourseMapping where status = 'deleted' ";

    	if(!empty($courseIds)) {
			$sql = $sql." and clientCourseID IN (".implode(',', $courseIds).") ";
		} else {
			$sql = $sql." LIMIT 100";
		}
		$result = $this->dbHandle->query($sql)->result_array();
		
    	return $result;
    }

    function getCourseExams($courseIds) {
        return;
    	$this->initiateModel('write');

    	$sql =  "SELECT typeId as course_id, examId, marks, marks_type as unit from listingExamMap where status = 'deleted' and typeOfMap = 'required' and type = 'course' and examId != -1 ";

    	if(!empty($courseIds)) {
			$sql = $sql." and typeId IN (".implode(',', $courseIds).") ";
		} else {
			$sql = $sql." LIMIT 100";
		}
		
		$result = $this->dbHandle->query($sql)->result_array();
		
    	return $result;
    }

    function getCourseLocationContactFees($courseIds) {
        return;
    	$this->initiateModel('write');

    	$sql =  "SELECT DISTINCT cla.course_id, ".
    			"cla.institute_location_id, ".
    			"cla.attribute_type, ".
    			"cla.attribute_value, ".
    			"lcd.contact_email, ".
    			"lcd.contact_main_phone, ".
    			"lcd.website ".
    			"FROM course_location_attribute cla ".
    			"LEFT JOIN listing_contact_details lcd ON lcd.institute_location_id = cla.institute_location_id and lcd.listing_type_id = cla.course_id and lcd.listing_type = 'course' and lcd.status = 'deleted' ".
    			"WHERE cla.status = 'deleted' ".
    			"AND cla.attribute_type NOT IN ('date_form_submission', 'date_result_declaration', 'date_course_comencement') ";

    	if(!empty($courseIds)) {
			$sql = $sql." and cla.course_id IN (".implode(',', $courseIds).") ";
		} else {
			$sql = $sql." LIMIT 20";
		}
		$result = $this->dbHandle->query($sql)->result_array();
		
    	return $result;
    }

    function getStreamDataByCategoryData($params) {
        return;
        $this->initiateModel('write');

        if(!isset($params['subcategory_id']) || $params['subcategory_id'] == 1) {
            $params['subcategory_id'] = 0;
        }
        if(!isset($params['ldb_course_id']) || $params['ldb_course_id'] == 1) {
            $params['ldb_course_id'] = 0;
        }
        $sql = "SELECT DISTINCT stream_id, substream_id, specialization_id, base_course_id ".
                   "FROM base_entity_mapping ".
                   "WHERE oldcategory_id = ? AND oldsubcategory_id = ? AND oldspecializationid = ? ".
                   "AND stream_id IS NOT NULL AND stream_id != 0 ";

        $result = $this->dbHandle->query($sql, array($params['category_id'], $params['subcategory_id'], $params['ldb_course_id']))->row_array();
        
        return $result;
    }

    function getCourseRecruitingCompanies($courseIds) {
        return;
    	$this->initiateModel('write');

    	$sql =  "SELECT DISTINCT listing_id as course_id, logo_id from company_logo_mapping where linked = 'yes' and status = 'deleted' and listing_type = 'course' and logo_id != 0 ";

    	if(!empty($courseIds)) {
			$sql = $sql." and listing_id IN (".implode(',', $courseIds).") ";
		} else {
			$sql = $sql." LIMIT 100";
		}
		
		$result = $this->dbHandle->query($sql)->result_array();
		
    	return $result;
    }

    function getCourseMedia($instituteIds) {
        return;
    	$this->initiateModel('write');

    	$instituteIds = array_unique($instituteIds);
    	$sql =  "SELECT distinct media_id, listing_id as institute_id from shiksha_institutes_media_locations_mapping where status = 'deleted' ";

    	if(!empty($instituteIds)) {
			$sql = $sql." and listing_id IN (".implode(',', $instituteIds).") ";
		} else {
			$sql = $sql." LIMIT 100";
		}
		
		$result = $this->dbHandle->query($sql)->result_array();
		
    	return $result;
    }

    function checkIfAlreadyExists($courseId) {
        return;
    	$this->initiateModel('write');

    	$sql =  "SELECT course_id from shiksha_courses where status IN ('live', 'draft', 'deleted') ";

    	if(!empty($courseId)) {
			$sql = $sql." and course_id = ".$courseId;
		}

		$result = $this->dbHandle->query($sql)->row_array();
		
    	return $result;
    }

    function insertDbData($data) {
        return;
    	$this->initiateModel('write');
    	$this->dbHandle->trans_start();

		//Basic Details, TABLE - shiksha_courses
		error_log("\t Basic Details...\n", 3, $this->logFilePath);
		$this->insertBasicDetails($data);

		//Basic Details, TABLE - shiksha_listings_brochures
		error_log("\t Brochure Details...\n", 3, $this->logFilePath);
		$this->insertBrochureDetails($data);
		
		//Hierarchy Information, TABLE - shiksha_courses_type_information
		error_log("\t Hierarchy Information...\n", 3, $this->logFilePath);
		$this->insertHierarchyDetails($data);

		//Additional Information, TABLE - shiksha_courses_additional_info
		error_log("\t Additional Information...\n", 3, $this->logFilePath);
		$this->insertAdditionalInfoDetails($data);

		//Important dates, TABLE - shiksha_courses_important_dates
		error_log("\t Important dates...\n", 3, $this->logFilePath);
		$this->insertImportantDates($data);

		//Exam Information, TABLES - shiksha_courses_exams_cut_off, shiksha_courses_eligibility_exam_score
		error_log("\t Exam Information...\n", 3, $this->logFilePath);
		$this->insertExamDetails($data);

		//Salary Information, TABLES - shiksha_courses_placements_internships
		error_log("\t Salary Information...\n", 3, $this->logFilePath);
		$this->insertSalaryDetails($data);

		//Location Information, TABLE - shiksha_courses_locations, shiksha_listings_contacts, shiksha_courses_fees
		error_log("\t Location Information...\n", 3, $this->logFilePath);
		$this->insertLocationDetails($data);

		//Basic Details, TABLE - shiksha_courses_companies_mapping
		error_log("\t Company Details...\n", 3, $this->logFilePath);
		$this->insertCompanyDetails($data);

		//Basic Details, TABLE - shiksha_courses_medias_mapping
		error_log("\t Media Details...\n", 3, $this->logFilePath);
		$this->insertMediaDetails($data);
		
		//Extra entry, TABLE - listings_main
		error_log("\t Duplicating entry in listings_main...\n", 3, $this->logFilePath);
		$this->insertDuplicateEntry($data);

    	$this->dbHandle->trans_complete();
    	if ($this->dbHandle->trans_status() === FALSE) {
			throw new Exception('Transaction Failed');
		}
    }

    function inserExcelData($data) {
        return;
    	$this->initiateModel('write');
    	$this->dbHandle->trans_start();

    	//Basic Details, TABLE - shiksha_courses
    	error_log("\t Basic Details...\n", 3, $this->logFilePath);
    	$this->updateBasicDetails($data);

    	//Course Type Details, TABLE - shiksha_courses_type_information
    	error_log("\t Course Type...\n", 3, $this->logFilePath);
    	$this->updateCourseTypeInformation($data);

    	//Additional Information, TABLE - shiksha_courses_additional_info
    	error_log("\t Additional Information...\n", 3, $this->logFilePath);
    	$this->updateAdditionalInformation($data);

    	//Placement & Internship Information, TABLE - shiksha_courses_placements_internships
    	error_log("\t Placement & Internship Information...\n", 3, $this->logFilePath);
    	$this->updatePlacementInformation($data);

    	//Batch Profile Information, TABLE - shiksha_courses_batch_profile
    	error_log("\t Batch Profile Information...\n", 3, $this->logFilePath);
    	$this->insertBatchProfileInformation($data);

    	//Eligibility Information, TABLE - shiksha_courses_eligibility_main
    	error_log("\t Eligibility Information...\n", 3, $this->logFilePath);
    	$this->insertEligibilityInformation($data);

    	//Admission Process Information, TABLE - shiksha_courses_admission_process
    	error_log("\t Admission Process Information...\n", 3, $this->logFilePath);
    	$this->insertAdmissionProcess($data);

    	$this->dbHandle->trans_complete();
    	if ($this->dbHandle->trans_status() === FALSE) {
			throw new Exception('Transaction Failed');
		}
    }

    private function insertBasicDetails($data) {
        return;
    	foreach($data['basicData'] as $basicData) {
			$dataRow = array(
			    'course_id'						=> $basicData['course_id'],
			    'name'							=> $basicData['course_name'],
			    'parent_id'						=> $basicData['institute_id'],
			    'parent_type'					=> $basicData['institute_type'],
			    'primary_id'					=> $basicData['institute_id'],
			    'primary_type'					=> $basicData['institute_type'],
			    'course_order'					=> $basicData['course_order'],
			    'course_type'					=> $basicData['course_type'],
			    'course_variant'				=> $basicData['course_variant'],
			    'education_type'				=> $basicData['education_type'],
			    'delivery_method'				=> $basicData['delivery_method'],
			    'duration_value'				=> $basicData['duration_value'],
			    'duration_unit'					=> strtolower($basicData['duration_unit']),
			    'affiliated_university_name'	=> $data['attributeData'][$basicData['course_id']]['affiliated_university_name'],
			    'affiliated_university_scope'	=> $data['attributeData'][$basicData['course_id']]['affiliated_university_scope'],
			    'total_seats'					=> $basicData['seats_total'],
			    'status'						=> $basicData['status'],
			    'created_on'					=> $basicData['created_on'],
			    'created_by'					=> '-1',
			    'updated_on'					=> $basicData['modified_on'],
			    'updated_by'					=> $basicData['modified_by']
			);
    		$arrayToBeInserted[] = $dataRow;
		}
		
		$this->dbHandle->insert_batch('shiksha_courses'.$this->tableExtension, $arrayToBeInserted);
    }

    private function insertBrochureDetails($data) {
        return;
    	foreach ($data['basicData'] as $basicData) {
    		$dataRow = array(
    			'listing_id'						=> $basicData['course_id'],
    			'listing_type'						=> 'course',
    			'brochure_url'						=> $basicData['course_request_brochure_link'],
    			'brochure_year'						=> $basicData['course_request_brochure_year'],
    			'updated_on'						=> $basicData['modified_on'],
    			'updated_by'						=> $basicData['modified_by'],
    			'status'							=> $basicData['status'],
    			'is_auto_generated'					=> NULL,
    		);
	    	$courseStatus[$basicData['course_id']] = $basicData['status'];
    		if(!empty($basicData['course_request_brochure_link'])) {
	    		$cmsAddedBrCourseIds[] = $basicData['course_id'];
	    		$arrayToBeInserted[] = $dataRow;
	    	}
    	}
    	
    	foreach ($data['brochureData'] as $courseId => $brochureData) {
    		$dataRow = array(
    			'listing_id'						=> $courseId,
    			'listing_type'						=> 'course',
    			'brochure_url'						=> $brochureData['brochure_url'],
    			'brochure_year'						=> '',
    			'updated_on'						=> $brochureData['updated_on'],
    			'updated_by'						=> '-1',
    			'status'							=> $courseStatus[$courseId],
    			'is_auto_generated'					=> 1,
    		);

    		if(!in_array($courseId, $cmsAddedBrCourseIds)) {
    			$arrayToBeInserted[] = $dataRow;
    		}
    	}
    	
    	if(!empty($arrayToBeInserted)) {
			$this->dbHandle->insert_batch('shiksha_listings_brochures'.$this->tableExtension, $arrayToBeInserted);
		}
    }

    private function insertHierarchyDetails($data) {
        return;
    	foreach ($data['basicData'] as $basicData) {
    		$courseId = $basicData['course_id'];
    		foreach ($data['hierarchyData'][$courseId] as $hierarchyData) {
    			$dataRow = array(
				    'course_id'						=> $courseId,
				    'type'							=> 'entry',
				    'credential'					=> $basicData['course_type_info'][0]['credential'],
				    'course_level'					=> $basicData['course_type_info'][0]['course_level'],
				    'base_course'					=> empty($data['hierarchyData'][$courseId][0]['base_course_id']) ? 0 : $data['hierarchyData'][$courseId][0]['base_course_id'],
				    'stream_id'						=> empty($hierarchyData['stream_id']) ? 0 : $hierarchyData['stream_id'],
				    'substream_id'					=> empty($hierarchyData['substream_id']) ? 0 : $hierarchyData['substream_id'],
				    'specialization_id'				=> empty($hierarchyData['specialization_id']) ? 0 : $hierarchyData['specialization_id'],
				    'primary_hierarchy'				=> empty($hierarchyData['is_primary']) ? 0 : $hierarchyData['is_primary'],
				    'status'						=> $basicData['status'],
				    'updated_on'					=> $basicData['modified_on'],
			    	'updated_by'					=> $basicData['modified_by']
				);
				
	    		$arrayToBeInserted[] = $dataRow;
	    	}
		}
		
		if(!empty($arrayToBeInserted)) {
			$this->dbHandle->insert_batch('shiksha_courses_type_information'.$this->tableExtension, $arrayToBeInserted);
		}
    }

    private function insertAdditionalInfoDetails($data) {
        return;
    	foreach ($data['basicData'] as $basicData) {
    		$courseId = $basicData['course_id'];
    		foreach ($data['attributeData'][$courseId]['additional_info'] as $attributeData) {
    			$dataRow = array(
				    'course_id'						=> $courseId,
				    'info_type'						=> $attributeData['info_type'],
				    'attribute_value_id'			=> $attributeData['attribute_value_id'],
				    'status'						=> $basicData['status'],
				    'updated_on'					=> $basicData['modified_on'],
			    	'updated_by'					=> $basicData['modified_by']
				);
	    		$arrayToBeInserted[] = $dataRow;
	    	}
    	}
    	
    	if(!empty($arrayToBeInserted)) {
			$this->dbHandle->insert_batch('shiksha_courses_additional_info'.$this->tableExtension, $arrayToBeInserted);
		}
    }

    private function insertImportantDates($data) {
        return;
    	foreach ($data['basicData'] as $basicData) {
    		$courseId = $basicData['course_id'];
    		foreach ($basicData['important_dates'] as $impData) {
    			$dataRow = array(
				    'course_id'						=> $courseId,
				    'event_name'					=> $impData['event_name'],
				    'start_date'					=> $impData['start_date'],
				    'start_month'					=> $impData['start_month'],
				    'start_year'					=> $impData['start_year'],
				    'status'						=> $basicData['status'],
				    'updated_on'					=> $basicData['modified_on'],
			    	'updated_by'					=> $basicData['modified_by']
				);
	    		$arrayToBeInserted[] = $dataRow;
	    	}
    	}
    	
    	if(!empty($arrayToBeInserted)) {
			$this->dbHandle->insert_batch('shiksha_courses_important_dates'.$this->tableExtension, $arrayToBeInserted);
		}
    }

    private function insertExamDetails($data) {
        return;
        $eligibilityMainData = array();
        foreach ($data['basicData'] as $basicData) {
            $courseId = $basicData['course_id'];
            foreach ($data['examData'][$courseId] as $key => $examData) {
    			$dataRow1 = array(
				    'course_id'						=> $courseId,
				    'exam_id'						=> $examData['exam_id'],
				    'status'						=> $basicData['status'],
				    'updated_on'					=> $basicData['modified_on'],
			    	'updated_by'					=> $basicData['modified_by']
				);
                if(empty($examData['cut_off_unit'])){
                    $dataRow1['unit'] = NULL;
                }
                else{
                    $dataRow1['unit'] = $examData['cut_off_unit'];
                }

				if(!empty($dataRow1)) {
	    			$arrayToBeInserted1[] = $dataRow1;
                }
                
                $eligibilityMainData[$courseId] = array(
                        'course_id'     => $courseId,
                        'batch_year'    => date("Y"),
                        'status'        => $basicData['status'],
                        'updated_on'    => $basicData['modified_on'],
                        'updated_by'    => $basicData['modified_by']
                );

				$dataRow2 = array(
				    'course_id'						=> $courseId,
				    'exam_id'						=> $examData['exam_id'],
				    'round'							=> -1,
				    'category'						=> 'general',
                    'cut_off_value'                 => $examData['cut_off_value'],
                    'cut_off_unit'                  => $examData['cut_off_unit'],
				    'exam_out_of'					=> $examData['exam_out_of'],
				    'quota'							=> 'all_india',
				    'cut_off_type'					=> 'exam',
				    'status'						=> $basicData['status'],
				    'updated_on'					=> $basicData['modified_on'],
			    	'updated_by'					=> $basicData['modified_by']
				);
				if(!empty($examData['cut_off_value'])) {
	    			$arrayToBeInserted2[] = $dataRow2;
				}
    		}
    	}
        
    	if(!empty($arrayToBeInserted1)) {
			$this->dbHandle->insert_batch('shiksha_courses_eligibility_exam_score'.$this->tableExtension, $arrayToBeInserted1);
		}

		if(!empty($arrayToBeInserted2)) {
			$this->dbHandle->insert_batch('shiksha_courses_exams_cut_off'.$this->tableExtension, $arrayToBeInserted2);
		}

        if(!empty($eligibilityMainData)) {
            $this->dbHandle->insert_batch('shiksha_courses_eligibility_main'.$this->tableExtension, array_values($eligibilityMainData));
        }
    }

    private function insertSalaryDetails($data) {
        return;
    	foreach ($data['basicData'] as $basicData) {
    		$courseId = $basicData['course_id'];
    		$attributeData = $data['attributeData'][$courseId];
			$dataRow = array(
			    'course_id'						=> $courseId,
			    'type'							=> 'placements',
			    'course'						=> $courseId,
                'course_type'                   => 'clientCourse',
			    'batch_year'					=> date('Y'),
			    'salary_unit'					=> $attributeData['salary_unit'],
			    'min_salary'					=> $attributeData['min_salary'],
			    'avg_salary'					=> $attributeData['avg_salary'],
			    'max_salary'					=> $attributeData['max_salary'],
			    'status'						=> $basicData['status'],
			    'updated_on'					=> $basicData['modified_on'],
		    	'updated_by'					=> $basicData['modified_by']
			);
			if(!empty($attributeData['min_salary']) || !empty($attributeData['avg_salary']) || !empty($attributeData['max_salary'])) {
				$arrayToBeInserted[] = $dataRow;
			}
    	}
    	
    	if(!empty($arrayToBeInserted)) {
    		$this->dbHandle->insert_batch('shiksha_courses_placements_internships'.$this->tableExtension, $arrayToBeInserted);
    	}
    }

    private function insertLocationDetails($data) {
        return;
    	foreach ($data['basicData'] as $basicData) {
    		$courseId = $basicData['course_id'];
    		foreach ($data['locationData'][$courseId]['locations'] as $key => $locationData) {
    			$dataRow = array(
				    'course_id'						=> $courseId,
				    'listing_location_id'			=> $locationData['institute_location_id'],
				    'is_main'						=> $locationData['is_main'],
				    'status'						=> $basicData['status'],
				    'updated_on'					=> $basicData['modified_on'],
			    	'updated_by'					=> $basicData['modified_by']
				);
	    		$arrayToBeInserted[] = $dataRow;
    		}
    	}
    	
    	$this->dbHandle->insert_batch('shiksha_courses_locations'.$this->tableExtension, $arrayToBeInserted);
    	
    	foreach ($data['basicData'] as $basicData) {
    		$courseId = $basicData['course_id'];
    		foreach ($data['locationData'][$courseId]['location_details'] as $insttLocationId => $locationDetails) {
    			$dataRow1 = array(
				    'listing_id'					=> $courseId,
				    'listing_type'					=> 'course',
				    'listing_location_id'			=> $insttLocationId,
				    'website_url'					=> $locationDetails['contact_details']['website_url'],
				    'generic_contact_number'		=> $locationDetails['contact_details']['generic_contact_number'],
				    'generic_email'					=> $locationDetails['contact_details']['generic_email'],
				    'status'						=> $basicData['status'],
				    'updated_on'					=> $basicData['modified_on'],
			    	'updated_by'					=> $basicData['modified_by']
				);
				if(!empty($locationDetails['contact_details'])) {
	    			$arrayToBeInserted1[] = $dataRow1;
				}

	    		$dataRow2 = array(
				    'course_id'						=> $courseId,
				    'listing_location_id'			=> $insttLocationId,
				    'fees_value'					=> $locationDetails['location_fees']['fees_value'],
				    'fees_unit'						=> $locationDetails['location_fees']['fees_unit'],
				    'fees_type'						=> 'total',
                    'batch_year'                    => date('Y'),
				    'category'						=> 'general',
				    'period'						=> 'overall',
				    'status'						=> $basicData['status'],
				    'updated_on'					=> $basicData['modified_on'],
			    	'updated_by'					=> $basicData['modified_by'],
			    	'fees_disclaimer'				=> $locationDetails['location_fees']['fees_disclaimer']
				);
				$feesValue = trim($locationDetails['location_fees']['fees_value']);
				if(!empty($feesValue)) {
					$arrayToBeInserted2[] = $dataRow2;
				}
    		}

    		//Total fees(other than location wise)
    		$dataRow2 = array(
			    'course_id'						=> $courseId,
			    'listing_location_id'			=> -1,
			    'fees_value'					=> $basicData['fees_value'],
			    'fees_unit'						=> $basicData['fees_unit'],
			    'fees_type'						=> 'total',
                'batch_year'                    => date('Y'),
			    'category'						=> 'general',
			    'period'						=> 'overall',
			    'status'						=> $basicData['status'],
			    'updated_on'					=> $basicData['modified_on'],
		    	'updated_by'					=> $basicData['modified_by'],
		    	'fees_disclaimer'				=> $basicData['show_fees_disclaimer']
			);
			if(!empty($basicData['fees_value'])) {
				$arrayToBeInserted2[] = $dataRow2;
			}
    	}
    	
    	if(!empty($arrayToBeInserted1)) {
    		$this->dbHandle->insert_batch('shiksha_listings_contacts'.$this->tableExtension, $arrayToBeInserted1);
    	}

    	if(!empty($arrayToBeInserted2)) {
    		$this->dbHandle->insert_batch('shiksha_courses_fees'.$this->tableExtension, $arrayToBeInserted2);
    	}
    }

    private function insertCompanyDetails($data) {
        return;
    	foreach ($data['basicData'] as $basicData) {
    		$courseId = $basicData['course_id'];
    		foreach ($data['companyData'][$courseId] as $companyData) {
    			$dataRow = array(
				    'course_id'						=> $courseId,
				    'company_id'					=> $companyData['company_id'],
				    'status'						=> $basicData['status'],
				    'updated_on'					=> $basicData['modified_on'],
			    	'updated_by'					=> $basicData['modified_by']
				);
				
	    		$arrayToBeInserted[] = $dataRow;
	    	}
		}
		
		if(!empty($arrayToBeInserted)) {
			$this->dbHandle->insert_batch('shiksha_courses_companies_mapping'.$this->tableExtension, $arrayToBeInserted);
		}
    }

    private function insertMediaDetails($data) {
        return;
    	foreach ($data['basicData'] as $basicData) {
    		$dataRow = array(
			    'course_id'						=> $basicData['course_id'],
			    'media_id'						=> '-1',
			    'status'						=> $basicData['status'],
			    'updated_on'					=> $basicData['modified_on'],
		    	'updated_by'					=> $basicData['modified_by']
			);
			if(in_array($basicData['course_id'], $data['mediaData'])) {
    			$arrayToBeInserted[] = $dataRow;
			}
    	}
    	
    	if(!empty($arrayToBeInserted)) {
			$this->dbHandle->insert_batch('shiksha_courses_medias_mapping'.$this->tableExtension, $arrayToBeInserted);
		}
    }

    private function insertDuplicateEntry($data) {
        return;
    	if(!empty($data['listingsMainData'])) {
			$this->dbHandle->insert_batch('listings_main'.$this->tableExtension, $data['listingsMainData']);
		}
    }

    private function updateBasicDetails($data) {
        return;
    	foreach ($data['basicData'] as $courseId => $basicData) {
    		$dataRow = array(
    			'course_id'						=> $basicData['course_id'],
			    'course_variant'				=> $basicData['course_variant'],
			    'executive'						=> $basicData['Executive'],
			    'twinning'						=> $basicData['Twinning'],
			    'integrated'					=> $basicData['Integrated'],
			    'dual'							=> $basicData['Dual'],
			    'education_type'				=> $basicData['education_type'],
			    'delivery_method'				=> $basicData['delivery_method'],
			    'time_of_learning'				=> $basicData['time_of_learning'],
			    'difficulty_level'				=> $basicData['difficulty_level'],
			    'affiliated_university_id'		=> $data['attributeData'][$basicData['course_id']]['affiliated_university_id'],
			    'affiliated_university_name'	=> $data['attributeData'][$basicData['course_id']]['affiliated_university_name'],
			    'affiliated_university_scope'	=> $data['attributeData'][$basicData['course_id']]['affiliated_university_scope'],
			    'updated_on'					=> date("Y-m-d H:i:s"),
			    'updated_by'					=> -1
			);
			$arrayToBeUpdated[] = $dataRow; //this should change the last updated on and by
    	}
    	  
		$this->dbHandle->update_batch('shiksha_courses'.$this->tableExtension, $arrayToBeUpdated, 'course_id');
    }

    private function updateCourseTypeInformation($data) {
        return;
    	foreach ($data['basicData'] as $courseId => $basicData) {
    		foreach ($basicData['course_type_info'] as $courseTypeData) {
	    		$dataRow = array (
					'course_id' 	=> $courseId,
					'type' 			=> $courseTypeData['type'],
					'credential' 	=> $courseTypeData['credential'],
					'course_level' 	=> $courseTypeData['course_level'],
					'updated_on'	=> date("Y-m-d H:i:s"),
			    	'updated_by'	=> -1
				);
		    		
    			if($courseTypeData['type'] == 'entry') {
		    		$arrayToBeUpdated[] = $dataRow;
    			}
    			if($courseTypeData['type'] == 'exit') {
		    		$arrayToBeInserted[] = $dataRow;
    			}
    		}
		}
		
		if(!empty($arrayToBeUpdated)) {
			$this->dbHandle->update_batch('shiksha_courses_type_information'.$this->tableExtension, $arrayToBeUpdated, 'course_id');
		}
		if(!empty($arrayToBeInserted)) {
			$this->dbHandle->insert_batch('shiksha_courses_type_information'.$this->tableExtension, $arrayToBeInserted, 'course_id');
		}
    }

    private function updateAdditionalInformation($data) {
        return;
    	foreach ($data['attributeData'] as $courseId => $attributeData) {
    		foreach ($attributeData['additional_info'] as $key => $value) {
    			$dataRow = array (
	    			'course_id'						=> $courseId,
				    'info_type'						=> $value['info_type'],
				    'attribute_value_id'			=> $value['attribute_value_id'],
				    'description'					=> $value['description'],
				    'status'						=> $data['basicData'][$courseId]['status'],
				    'updated_on'					=> date("Y-m-d H:i:s"),
			    	'updated_by'					=> '-1'
			    );
    			
    			$arrayToBeInserted[] = $dataRow;
    		}
    	}

    	if(!empty($arrayToBeInserted)) {
			$this->dbHandle->insert_batch('shiksha_courses_additional_info'.$this->tableExtension, $arrayToBeInserted);
		}
    }

    private function updatePlacementInformation($data) {
        return;
    	foreach ($data['attributeData'] as $courseId => $attributeData) {
    		if(!empty($attributeData['placements'])) {
				$dataRow = array (
	    			'course_id'						=> $courseId,
	    			'type'							=> 'placements',
				    'batch_year'					=> $attributeData['placements']['batch_year'],
				    'course'						=> $courseId,
				    'course_type'					=> 'clientCourse',
				    'percentage_batch_placed'		=> $attributeData['placements']['percentage_batch_placed'],
				    'salary_unit'					=> 1,
				    'min_salary'					=> $attributeData['placements']['min_salary'],
				    'avg_salary'					=> $attributeData['placements']['avg_salary'],
				    'max_salary'					=> $attributeData['placements']['max_salary'],
				    'median_salary'					=> $attributeData['placements']['median_salary'],
				    'total_international_offers'	=> $attributeData['placements']['total_international_offers'],
				    'max_international_salary'		=> $attributeData['placements']['max_international_salary'],
				    'max_international_salary_unit'	=> $attributeData['placements']['max_international_salary_unit'],
				    'status'						=> $data['basicData'][$courseId]['status'],
				    'updated_on'					=> date("Y-m-d H:i:s"),
			    	'updated_by'					=> -1
			    );
				$placementCourseIds[] = $courseId;
				$arrayToBeInserted[] = $dataRow;
			}
		}

		//mark previous live entries(imported from db) as history
    	$sql = "UPDATE shiksha_courses_placements_internships".$this->tableExtension." SET status = 'history' WHERE status IN ('live', 'draft') and course_id IN (".implode(',', $placementCourseIds).") ";
    	$this->dbHandle->query($sql);

    	if(!empty($arrayToBeInserted)) {
			$this->dbHandle->insert_batch('shiksha_courses_placements_internships'.$this->tableExtension, $arrayToBeInserted);
		}

		$arrayToBeInserted = array();
		foreach ($data['attributeData'] as $courseId => $attributeData) {
			if(!empty($attributeData['internship'])) {
				$dataRow = array (
	    			'course_id'						=> $courseId,
	    			'type'							=> 'internship',
				    'batch_year'					=> $attributeData['internship']['batch_year'],
				    'course'						=> $courseId,
				    'course_type'					=> 'clientCourse',
				    'salary_unit'					=> $attributeData['internship']['salary_unit'],
				    'max_salary'					=> $attributeData['internship']['max_salary'],
				    'median_salary'					=> $attributeData['internship']['median_salary'],
				    'avg_salary'					=> $attributeData['internship']['avg_salary'],
				    'min_salary'					=> $attributeData['internship']['min_salary'],
				    'status'						=> $data['basicData'][$courseId]['status'],
				    'updated_on'					=> date("Y-m-d H:i:s"),
			    	'updated_by'					=> -1
			    );
	    		$arrayToBeInserted[] = $dataRow;
			}
    	}

    	if(!empty($arrayToBeInserted)) {
			$this->dbHandle->insert_batch('shiksha_courses_placements_internships'.$this->tableExtension, $arrayToBeInserted);
		}
    }

    private function insertBatchProfileInformation($data) {
        return;
    	foreach ($data['attributeData'] as $courseId => $attributeData) {
    		if(!empty($attributeData['batch_profile'])) {
    			$dataRow = array (
	    			'course_id'						=> $courseId,
	    			'male'							=> $attributeData['batch_profile']['male'],
				    'female'						=> $attributeData['batch_profile']['female'],
				    'transgender'					=> $attributeData['batch_profile']['transgender'],
				    'avg_work-ex'					=> $attributeData['batch_profile']['avg_work-ex'],
				    'international_students'		=> $attributeData['batch_profile']['international_students'],
				    'status'						=> $data['basicData'][$courseId]['status'],
				    'updated_on'					=> date("Y-m-d H:i:s"),
			    	'updated_by'					=> -1
			    );
	    		$arrayToBeInserted[] = $dataRow;
    		}
    	}
    	
    	if(!empty($arrayToBeInserted)) {
			$this->dbHandle->insert_batch('shiksha_courses_batch_profile'.$this->tableExtension, $arrayToBeInserted);
		}
    }

    private function insertEligibilityInformation($data) {
        return;
    	foreach ($data['attributeData'] as $courseId => $attributeData) {
    		if(!empty($attributeData['eligibility'])) {
    			$dataRow = array (
	    			'course_id'						=> $courseId,
	    			'batch_year'					=> date('Y'),
	    			'work-ex_min'					=> $attributeData['eligibility']['work-ex_min'],
				    'work-ex_max'					=> $attributeData['eligibility']['work-ex_max'],
				    'work-ex_unit'					=> $attributeData['eligibility']['work-ex_unit'],
				    'age_min'						=> $attributeData['eligibility']['age_min'],
				    'age_max'						=> $attributeData['eligibility']['age_max'],
				    'status'						=> $data['basicData'][$courseId]['status'],
				    'updated_on'					=> date("Y-m-d H:i:s"),
			    	'updated_by'					=> -1
			    );
			    $courseIds[] = $courseId;
	    		$arrayToBeInserted[] = $dataRow;
    		}
    	}
    	if(!empty($courseIds)) {
	    	//mark previous live entries(imported from db) as history
	    	$sql = "UPDATE shiksha_courses_eligibility_main".$this->tableExtension." SET status = 'history' WHERE status IN ('live', 'draft') and course_id IN (".implode(',', $courseIds).") ";
	    	$this->dbHandle->query($sql);
	    }
    	
    	if(!empty($arrayToBeInserted)) {
			$this->dbHandle->insert_batch('shiksha_courses_eligibility_main'.$this->tableExtension, $arrayToBeInserted);
		}
    }

    private function insertAdmissionProcess($data) {
        return;
    	foreach ($data['attributeData'] as $courseId => $attributeData) {
    		foreach ($attributeData['admission_process'] as $admissionData) {
    			$data['basicData']['status'] = 'draft';
	    		$dataRow = array (
					'course_id' 					=> $courseId,
					'stage_order' 					=> $admissionData['stage_order'],
					'admission_name' 				=> $admissionData['stage_name'],
					'admission_name_other'			=> $admissionData['stage_name_other'],
					'admission_description'			=> $admissionData['education_background'],
					'status'						=> $data['basicData'][$courseId]['status'],
					'updated_on'					=> date("Y-m-d H:i:s"),
			    	'updated_by'					=> -1
				);
	    		
	    		if(!empty($admissionData['stage_name_other']) && $admissionData['stage_name'] == 'Others') {
	    			$arrayToBeInserted[] = $dataRow;
	    			$courseIds[] = $courseId;
	    		}
    		}
		}
		
		if(!empty($courseIds)) {
	    	//mark previous live entries(imported from db) as history
	    	$sql = "UPDATE shiksha_courses_admission_process".$this->tableExtension." SET status = 'history' WHERE status IN ('live', 'draft') and admission_name = 'Others' and course_id IN (".implode(',', $courseIds).") ";
	    	$this->dbHandle->query($sql);
	    }
		if(!empty($arrayToBeInserted)) {
			$this->dbHandle->insert_batch('shiksha_courses_admission_process'.$this->tableExtension, $arrayToBeInserted, 'course_id');
		}
    }

    function getCourseStatus($courseIds) {
        return;
    	$this->initiateModel('write');

    	$sql = "SELECT course_id, status from shiksha_courses where status IN ('live', 'draft') AND course_id IN (".implode(',', $courseIds).") group by course_id ";

    	$result = $this->dbHandle->query($sql)->result_array();
    	foreach ($result as $key => $value) {
    		$courseStatus[$value['course_id']] = $value['status'];
    	}
		
    	return $courseStatus;
    }

    function getListingsMainAllEntries($type, $typeId, $minId, $maxId) {
        return;
    	$this->initiateModel('write');
    	
    	$sql = "SELECT lm.* from listings_main lm ";
        if($type == 'course') {
            $sql = $sql."INNER JOIN shiksha_courses sc ON sc.course_id = lm.listing_type_id and sc.status IN ('live', 'draft') ";
        } else {
            $sql = $sql."INNER JOIN shiksha_institutes i ON i.listing_id = lm.listing_type_id and i.status IN ('live', 'draft') ";
        }
        $sql = $sql."where lm.status IN ('live', 'stagging', 'stagging_draft') ";

    	if(!empty($type)) {
            if($type == 'institute') {
                $sql = $sql." and lm.listing_type IN ('institute','university_national') ";
            } else {
    	       $sql = $sql." and lm.listing_type = '".$type."' ";
            }
    	}

    	if(!empty($typeId)) {
			$sql = $sql." and lm.listing_type_id = ? ";
		}
		elseif(!empty($minId) && !empty($maxId)) {
			$sql = $sql." AND lm.listing_type_id BETWEEN $minId AND $maxId ";
		} else {
            return;
        }
        
        $result = $this->dbHandle->query($sql, array($typeId))->result_array();
        return $result;
    }

    function changeStatusListingsMain($type = 'course', $liveIds, $data) {
        return;
    	$this->initiateModel('write');
    	$this->dbHandle->trans_start();

    	if(empty($data)) {
    		return;
    	}
    	
		if(!empty($liveIds)) {
			//change live, draft to history
			error_log("Changing live, draft to history for ids - ".print_r($liveIds, true)."...\n", 3, $this->logFilePath);
			$sql = "UPDATE listings_main SET status = 'history' WHERE status IN ('live', 'draft') and listing_type_id IN (".implode(',', $liveIds).") AND listing_type = '".$type."' ";
			$this->dbHandle->query($sql);
		}

    	//change staging, staging draft to history
    	$staggingIds = array_keys($data);
    	error_log("Changing staging, staging draft to history for ids - ".print_r($staggingIds, true)."...\n", 3, $this->logFilePath);
        if($type == 'institute') {
        	$sql = "UPDATE listings_main SET status = 'history' WHERE status IN ('stagging', 'stagging_draft') and listing_type_id IN (".implode(',', $staggingIds).") AND listing_type IN ('institute', 'university_national') ";
        } else {
            $sql = "UPDATE listings_main SET status = 'history' WHERE status IN ('stagging', 'stagging_draft') and listing_type_id IN (".implode(',', $staggingIds).") AND listing_type = '".$type."' ";
        }
    	$this->dbHandle->query($sql);

    	//make new entries with status live, draft
    	foreach ($data as $listingId => $listingData) {
    		foreach ($listingData as $status => $value) {
				unset($value['listing_id']);
    			$arrayToBeInserted[] = $value;
    		}
    	}
    	
    	if(!empty($arrayToBeInserted)) {
    		error_log("Inserting live, draft entries...\n", 3, $this->logFilePath);
			$this->dbHandle->insert_batch('listings_main'.$this->tableExtension, $arrayToBeInserted);
		}

		$this->dbHandle->trans_complete();
    	if ($this->dbHandle->trans_status() === FALSE) {
			throw new Exception('Transaction Failed');
		}
    }

    function getCoursesForUrlUpdate($courseId, $limit, $offset) {
        return;
    	$this->initiateModel('write');
    	
    	$sql = "SELECT DISTINCT listing_type_id, listing_id from listings_main lm ".
        "INNER JOIN shiksha_courses sc ON sc.course_id = lm.listing_type_id and sc.status IN ('live', 'draft') ".
        "WHERE lm.status IN ('stagging', 'stagging_draft') AND lm.listing_type = 'course' ";

    	if(!empty($courseId)) {
			$sql = $sql." and lm.listing_type_id = ? ";
		}
		elseif(!empty($limit)) {
			if(empty($offset)) {
				$sql = $sql." order by lm.listing_type_id asc LIMIT $limit ";
			} else {
				$sql = $sql." order by lm.listing_type_id asc LIMIT $limit OFFSET $offset ";
			}
		}
        
		$result = $this->dbHandle->query($sql, array($courseId))->result_array();
		
    	return $result;
    }

    function getSeoUrlData($courseIds) {
        return;
		$this->initiateModel('write');

		$sql =  "SELECT sc.course_id, sc.primary_id, sc.name as course_name, ti.base_course, ti.stream_id, ti.substream_id, ti.specialization_id, cl.listing_location_id ".
    			"FROM shiksha_courses sc ".
    			"INNER JOIN shiksha_courses_type_information ti ON sc.course_id = ti.course_id AND ti.primary_hierarchy = 1 AND ti.status = 'live' ".
    			"INNER JOIN shiksha_courses_locations cl ON sc.course_id = cl.course_id AND cl.is_main = 1 AND cl.status='live' ".
    			"WHERE sc.status = 'live' AND sc.course_id IN (".implode(',', $courseIds).")";

    	$result = $this->dbHandle->query($sql)->result_array();
		
    	return $result;
    }

    function insertUrl($seoData) {
        return;
    	foreach ($seoData as $courseId => $urlData) {
            $dataRow = array(
                'listing_id'                => $urlData['id'],
                'listing_seo_url'           => $urlData['listing_seo_url'],
                'listing_seo_title'         => $urlData['listing_seo_title'],
                'listing_seo_description'   => $urlData['listing_seo_description']
            );
            if(!empty($urlData['listing_seo_url']) && !empty($urlData['id'])) {
			    $arrayToBeUpdated[] = $dataRow;
            }
    	}
    	
		$this->dbHandle->update_batch('listings_main'.$this->tableExtension, $arrayToBeUpdated, 'listing_id');
    }

    function getCourseToChange() {
        return;
        $this->initiateModel('write');

        $sql = "select distinct a.course_id from shiksha_courses_type_information a 
        inner join listings_main lm on lm.listing_type_id = a.course_id and listing_type = 'course' and lm.status = 'stagging_draft' 
        inner join shiksha_courses sc on sc.course_id = a.course_id and sc.status = 'live' and sc.course_variant = 3
        where a.credential = 11 and a.status = 'draft' and a.stream_id != '' ";
        
        $result = $this->dbHandle->query($sql)->result_array();
        foreach ($result as $key => $value) {
            $courseId[] = $value['course_id'];
        }
        _p($courseId); die;
        $sql = "UPDATE shiksha_courses_contact_details SET status='live' where status = 'draft' and course_id IN (".implode(',', $courseId).")";
        $updateResult = $this->dbHandle->query($sql);
        $sql = "UPDATE shiksha_courses_eligibility_base_entities SET status='live' where status = 'draft' and course_id IN (".implode(',', $courseId).")";
        $updateResult = $this->dbHandle->query($sql);
        $sql = "UPDATE shiksha_courses_eligibility_exam_score SET status='live' where status = 'draft' and course_id IN (".implode(',', $courseId).")";
        $updateResult = $this->dbHandle->query($sql);
        $sql = "UPDATE shiksha_courses_eligibility_main SET status='live' where status = 'draft' and course_id IN (".implode(',', $courseId).")";
        $updateResult = $this->dbHandle->query($sql);
        $sql = "UPDATE shiksha_courses_eligibility_score SET status='live' where status = 'draft' and course_id IN (".implode(',', $courseId).")";
        $updateResult = $this->dbHandle->query($sql);
        $sql = "UPDATE shiksha_courses_exams_cut_off SET status='live' where status = 'draft' and course_id IN (".implode(',', $courseId).")";
        $updateResult = $this->dbHandle->query($sql);
        $sql = "UPDATE shiksha_courses_fees SET status='live' where status = 'draft' and course_id IN (".implode(',', $courseId).")";
        $updateResult = $this->dbHandle->query($sql);
        $sql = "UPDATE shiksha_courses_important_dates SET status='live' where status = 'draft' and course_id IN (".implode(',', $courseId).")";
        $updateResult = $this->dbHandle->query($sql);
        $sql = "UPDATE shiksha_courses_locations SET status='live' where status = 'draft' and course_id IN (".implode(',', $courseId).")";
        $updateResult = $this->dbHandle->query($sql);
        $sql = "UPDATE shiksha_courses_medias_mapping SET status='live' where status = 'draft' and course_id IN (".implode(',', $courseId).")";
        $updateResult = $this->dbHandle->query($sql);
        $sql = "UPDATE shiksha_courses_partner_details SET status='live' where status = 'draft' and course_id IN (".implode(',', $courseId).")";
        $updateResult = $this->dbHandle->query($sql);
        $sql = "UPDATE shiksha_courses_placements_internships SET status='live' where status = 'draft' and course_id IN (".implode(',', $courseId).")";
        $updateResult = $this->dbHandle->query($sql);
        $sql = "UPDATE shiksha_courses_seats_breakup SET status='live' where status = 'draft' and course_id IN (".implode(',', $courseId).")";
        $updateResult = $this->dbHandle->query($sql);
        $sql = "UPDATE shiksha_courses_structure_offered_courses SET status='live' where status = 'draft' and course_id IN (".implode(',', $courseId).")";
        $updateResult = $this->dbHandle->query($sql);
        $sql = "UPDATE shiksha_listings_brochures SET status='live' where status = 'draft' and listing_type = 'course' and listing_id IN (".implode(',', $courseId).")";
        $updateResult = $this->dbHandle->query($sql);
        $sql = "UPDATE shiksha_listings_contacts SET status='live' where status = 'draft' and listing_type = 'course' and listing_id IN (".implode(',', $courseId).")";
        $updateResult = $this->dbHandle->query($sql);

        $sql = "UPDATE listings_main SET status='stagging' where status = 'stagging_draft' and listing_type = 'course' and listing_type_id IN (".implode(',', $courseId).")";
        $updateResult = $this->dbHandle->query($sql);
        $sql = "UPDATE shiksha_courses_type_information SET status='live' where status = 'draft' and course_id IN (".implode(',', $courseId).")";
        $updateResult = $this->dbHandle->query($sql);
    }

    /*
     * Changing same name exams' mapping
     * Tables - shiksha_courses_eligibility_exam_score, shiksha_courses_exams_cut_off
     */
    function changeExamMapping($examMapping) {
        return;
        $this->initiateModel('write');

        foreach ($examMapping as $oldExam => $newExam) {
            $sql = "UPDATE shiksha_courses_eligibility_exam_score SET exam_id = ? where exam_id = ? and status IN ('live','draft') ";
            $result = $this->dbHandle->query($sql, array($newExam, $oldExam));

            $sql = "UPDATE shiksha_courses_exams_cut_off SET exam_id = ? where exam_id = ? and status IN ('live','draft') ";
            $result = $this->dbHandle->query($sql, array($newExam, $oldExam));
        }
    }

    function changeStatusInstitutes ($type = 'institute', $typeId = 0 ){
        return;
        ini_set('memory_limit', '-1');
        set_time_limit(0);

	$this->initiateModel('write');

            $sql    =   "SELECT distinct (lm.listing_type_id) listingId FROM listings_main lm "
                        ." INNER JOIN institute_location_table ilt ON (lm.listing_type_id = ilt.institute_id)"
                        ." WHERE lm.listing_type IN ('institute','university_national') AND ilt.country_id = 2"
                        ." AND lm.status = 'draft' AND ilt.status IN ('live','draft')";
            if($typeId > 0){
                $sql    .=   " AND lm.listing_type_id = '$typeId' ";
            }

	    $resultSet  = $this->dbHandle->query($sql)->result_array();
	    $listingStr = "";
	    foreach ($resultSet as $listing){
		$listingStr .= ($listingStr == '')?$listing['listingId']:','.$listing['listingId'];
	    }

	    if($listingStr != ''){
	            $sql    =   "UPDATE listings_main SET status = 'history' WHERE listing_type IN ('institute','university_national') AND status = 'draft' AND listing_type_id IN ( $listingStr )";
        	    $resultSet  = $this->dbHandle->query($sql);
	    }
    }
	
	function updateAffiliation($data){
        return;
        $this->initiateModel('write');
        $this->dbHandle->trans_start();
        $this->dbHandle->where(array('status' => 'live'));
        $this->dbHandle->update_batch('shiksha_courses', $data, 'course_id'); 
        $this->dbHandle->trans_complete();
        if ($this->dbHandle->trans_status() === FALSE) {
            throw new Exception('Transaction Failed');
        }
        return true;
    }

    function getDeletedCourses($courseId) {
        return;
        $this->initiateModel('read');

        if(empty($courseId)) {
            $sql = "select DISTINCT course_id from course_details where status = 'deleted'";
            $result = $this->dbHandle->query($sql)->result_array();

            $courseIds = array();
            foreach ($result as $key => $value) {
                $courseIds[] = $value['course_id'];
            }
        } else {
            $courseIds[] = $courseId;
        }
        
        $sql = "select DISTINCT course_id from shiksha_courses where course_id IN (".implode(',', $courseIds).")";
        $result = $this->dbHandle->query($sql)->result_array();

        $alreadyDeletedCourseIds = array();
        foreach ($result as $key => $value) {
            $alreadyDeletedCourseIds[] = $value['course_id'];
        }
        
        $result = array_diff($courseIds, $alreadyDeletedCourseIds);
        return $result;
    }

    function getBasicDeletedCourseDetails($courseIds, $limit, $offset) {
        return;
        $this->initiateModel('write');

        $sql =  "SELECT DISTINCT cd.institute_id, ".
                "cd.course_id, cd.courseTitle as course_name, ".
                "course_type, ".
                "course_level, ".
                "course_level_1, ".
                "course_order, ".
                "seats_total, ".
                "duration_value, duration_unit, ".
                "fees_value, fees_unit, ".
                "show_fees_disclaimer, ".
                "feestypes as fees_includes, ".
                "date_form_submission, date_result_declaration, date_course_comencement, ".
                "course_request_brochure_link, ".
                "course_request_brochure_year, ".
                "lm_1.last_modify_date as modified_on, ".
                "lm_1.editedBy as modified_by, ".
                "lm_1.submit_date as created_on, ".
                "cd.status as status ".
                "FROM course_details cd ".
                "INNER JOIN institute i ON i.institute_id = cd.institute_id and i.status IN ('live', 'deleted') and i.institute_type ='Academic_Institute' ".
                "INNER JOIN listings_main lm_1 ON lm_1.listing_type_id = cd.course_id and lm_1.status='deleted' and lm_1.listing_type = 'course' ".
                "INNER JOIN course_location_attribute cla on cla.course_id = cd.course_id and cla.status='deleted' and cla.attribute_type IN ('Head Office') and cla.attribute_value = 'TRUE' ".
                "WHERE cd.status = 'deleted' ";

        if(!empty($courseIds)) {
            $sql = $sql." and cd.course_id IN (".implode(',', $courseIds).") ";
        }
        // elseif(!empty($limit)) {
        //     if(empty($offset)) {
        //         $sql = $sql." order by cd.course_id asc LIMIT $limit ";
        //     } else {
        //         $sql = $sql." order by cd.course_id asc LIMIT $limit OFFSET $offset ";
        //     }
        // }
        $sql = $sql."GROUP BY cd.course_id";

        $result = $this->dbHandle->query($sql)->result_array();

        return $result;
    }

    function insertDbDeletedData($data) {
        return;
        $this->initiateModel('write');
        $this->dbHandle->trans_start();

        //Basic Details, TABLE - shiksha_courses
        error_log("\t Basic Details...\n", 3, $this->logFilePath);
        $this->insertBasicDetailsDeleted($data);

        //Basic Details, TABLE - shiksha_listings_brochures
        error_log("\t Brochure Details...\n", 3, $this->logFilePath);
        $this->insertBrochureDetails($data);
        
        //Hierarchy Information, TABLE - shiksha_courses_type_information
        error_log("\t Hierarchy Information...\n", 3, $this->logFilePath);
        $this->insertHierarchyDetails($data);

        //Additional Information, TABLE - shiksha_courses_additional_info
        error_log("\t Additional Information...\n", 3, $this->logFilePath);
        $this->insertAdditionalInfoDetails($data);

        //Important dates, TABLE - shiksha_courses_important_dates
        error_log("\t Important dates...\n", 3, $this->logFilePath);
        $this->insertImportantDates($data);

        //Exam Information, TABLES - shiksha_courses_exams_cut_off, shiksha_courses_eligibility_exam_score
        error_log("\t Exam Information...\n", 3, $this->logFilePath);
        $this->insertExamDetails($data);

        //Salary Information, TABLES - shiksha_courses_placements_internships
        error_log("\t Salary Information...\n", 3, $this->logFilePath);
        $this->insertSalaryDetails($data);

        //Location Information, TABLE - shiksha_courses_locations, shiksha_listings_contacts, shiksha_courses_fees
        error_log("\t Location Information...\n", 3, $this->logFilePath);
        $this->insertLocationDetails($data);

        //Basic Details, TABLE - shiksha_courses_companies_mapping
        error_log("\t Company Details...\n", 3, $this->logFilePath);
        $this->insertCompanyDetails($data);

        //Basic Details, TABLE - shiksha_courses_medias_mapping
        error_log("\t Media Details...\n", 3, $this->logFilePath);
        $this->insertMediaDetails($data);
        
        $this->dbHandle->trans_complete();
        if ($this->dbHandle->trans_status() === FALSE) {
            throw new Exception('Transaction Failed');
        }
    }

    private function insertBasicDetailsDeleted($data) {
        return;
        foreach($data['basicData'] as $basicData) {
            if($basicData['duration_value'] == NULL) {
                $basicData['duration_value'] = 0;
            }
            $dataRow = array(
                'course_id'                     => $basicData['course_id'],
                'name'                          => $basicData['course_name'],
                'parent_id'                     => $basicData['institute_id'],
                'parent_type'                   => 'institute',
                'primary_id'                    => $basicData['institute_id'],
                'primary_type'                  => 'institute',
                'course_order'                  => $basicData['course_order'],
                'course_type'                   => $basicData['course_type'],
                'course_variant'                => $basicData['course_variant'],
                'education_type'                => $basicData['education_type'],
                'delivery_method'               => $basicData['delivery_method'],
                'duration_value'                => $basicData['duration_value'],
                'duration_unit'                 => strtolower($basicData['duration_unit']),
                'affiliated_university_name'    => $data['attributeData'][$basicData['course_id']]['affiliated_university_name'],
                'affiliated_university_scope'   => $data['attributeData'][$basicData['course_id']]['affiliated_university_scope'],
                'total_seats'                   => $basicData['seats_total'],
                'status'                        => $basicData['status'],
                'created_on'                    => $basicData['created_on'],
                'created_by'                    => '-1',
                'updated_on'                    => $basicData['modified_on'],
                'updated_by'                    => $basicData['modified_by']
            );
            $arrayToBeInserted[] = $dataRow;
        }
        
        $this->dbHandle->insert_batch('shiksha_courses'.$this->tableExtension, $arrayToBeInserted);
    }
}
