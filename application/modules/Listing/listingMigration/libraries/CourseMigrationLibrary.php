<?php

class CourseMigrationLibrary {
	function __construct() {
        $this->CI =& get_instance();

        $this->logFileName = 'log_course_migration_'.date('y-m-d');
        $this->logFilePath = '/tmp/'.$this->logFileName;

        $this->courseMigrationModel = $this->CI->load->model('listingMigration/coursemigrationmodel');
        $this->baseAttributeLibrary = $this->CI->load->library('listingBase/BaseAttributeLibrary');

        $attributesUsed = array('Education Type', 'Medium/Delivery Method', 'Course Variant', 'Credential', 'Course Type', 'Course Level', 'Course Recognition', 'Medium of Instruction', 'Time of Learning', 'Difficulty / Skill Level');
        $this->attributeValues = $this->baseAttributeLibrary->getValuesForAttributeByName($attributesUsed);
        
        $this->CI->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder = new CategoryBuilder;
        $this->categoryRepository = $categoryBuilder->getCategoryRepository();
		
		$this->CI->load->builder('LDBCourseBuilder','LDB');
		$LDBCourseBuilder = new LDBCourseBuilder;
		$this->LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();

        $this->coursePostingLibrary = $this->CI->load->library('nationalCourse/CoursePostingLib');

        $this->currencyCodes = $this->courseMigrationModel->getCurrencyCodes();

        $this->examMappingOldToNew = array(
                '9193' => '3280',
                '9175' => '330',
                '331'  => '330',
                '1601' => '330',
                '9253' => '9241',
                '9210' => '6616',
                '6218' => '304',
                '321'  => '304',
                '322'  => '304',
                '323'  => '304',
                '324'  => '304',
                '325'  => '304',
                '326'  => '304',
                '11967'=> '9179',
                '9607' => '9606',
                '9272' => '11934',
                '305'  => '327',
                '328'  => '306',
                '308'  => '307',
                '1035' => '307',
                '1125' => '307',
                '329'  => '309',
                '13052'=> '9208',
                '13071'=> '9252',
                '13056'=> '9267',
                '414'  => '3300',
                '416'  => '3300',
                '415'  => '3300',
                '410'  => '409',
                '411'  => '409',
                '6227' => '10249',
                '312'  => '311',
                '313'  => '311',
                '314'  => '311',
                '315'  => '311',
                '318'  => '317',
                '319'  => '317',
                '320'  => '317',
                '11443'=> '9169',
                '13068'=> '9206',
                '13059'=> '9207',
                '13038'=> '9214',
                '13123'=> '9270',
                '10832'=> '10118',
                '10119'=> '6214'
            );
    }

	function migrateCoursesFromDB($courseId, $source = 'cron') {
		$round = 1; $limit = 2000;
		while(1) {
            if(empty($courseId)) {
                error_log("Starting round: ".$round.", batch size: ".$limit."...\n", 3, $this->logFilePath);
            }
            if($source == 'cms') {
                if(empty($courseId)) {
                    break;
                }
                $exists = $this->checkIfAlreadyExists($courseId);
                if($exists) {
                    break;
                }
            }
            $offset = ($round-1)*$limit;
            
            //TABLES - course_details, listings_main
            error_log("Fetching base course details...\n", 3, $this->logFilePath);
            $data['basicData'] = $this->getBasicCourseDetails($courseId, $limit, $offset);

			$courseIdsFromDB = array(); $instituteIdMap = array();
            foreach ($data['basicData'] as $key => $value) {
                $courseIdsFromDB[] = $value['course_id'];
                $instituteIdMap[$value['course_id']] = $value['institute_id'];
            }
            
            if(empty($courseIdsFromDB)) {
                error_log("No course ids found in this round.\n", 3, $this->logFilePath);
                break;
            }
            //error_log("Courses to be migrated in this round: ".print_r($courseIdsFromDB, true)."\n", 3, $this->logFilePath);

            //TABLE - clientCourseToLDBCourseMapping
            error_log("Fetching course hierarchy details...\n", 3, $this->logFilePath);
	        $data['hierarchyData'] = $this->getCourseSpecializations($courseIdsFromDB);

            //If mandatory data is missing for live courses, change it to draft
            foreach ($data['basicData'] as $key => $basicData) {
                if(($data['hierarchyData'][$basicData['course_id']][0]['status'] == 'draft') || empty($data['hierarchyData'][$basicData['course_id']])) {
                    //error_log("\t Changing status to draft: ".$basicData['course_id']."\n", 3, $this->logFilePath);
                    $this->draftCourses[] = $basicData['course_id'];

                    $data['basicData'][$key]['status'] = 'draft';
                    $data['hierarchyData'][$basicData['course_id']][0]['status'] = 'draft';
                }
                $this->courseStatus[$basicData['course_id']] = $data['basicData'][$key]['status'];
            }
            
            //TABLE - listings_ebrochures
            error_log("Fetching custom brochures...\n", 3, $this->logFilePath);
            $data['brochureData'] = $this->getCustomBrochures($courseIdsFromDB);
            
            //TABLE - course_attributes
            error_log("Fetching course attributes...\n", 3, $this->logFilePath);
            $data['attributeData'] = $this->getCourseAttributes($courseIdsFromDB);
            
            //TABLE - listingExamMap
            error_log("Fetching course exam details...\n", 3, $this->logFilePath);
            $data['examData'] = $this->getCourseExams($courseIdsFromDB);
            
			//TABLES - listing_contact_details, course_location_attribute
            error_log("Fetching course location details...\n", 3, $this->logFilePath);
	        $data['locationData'] = $this->getCourseLocationContactFees($courseIdsFromDB);

            //TABLES - company_logo_mapping
            error_log("Fetching course recruiting company details...\n", 3, $this->logFilePath);
            $data['companyData'] = $this->getCourseRecruitingCompanies($courseIdsFromDB);

            //TABLES - shiksha_institutes_media_locations_mapping
            error_log("Fetching course media details...\n", 3, $this->logFilePath);
            $data['mediaData'] = $this->getCourseMedia($instituteIdMap);

            //TABLES - listings_main
            error_log("Fetching listings_main details...\n", 3, $this->logFilePath);
            $data['listingsMainData'] = $this->getListingsMainDetails($courseIdsFromDB, $data);

            //write in DB
            error_log("Performing insert in DB...\n", 3, $this->logFilePath);
            $this->courseMigrationModel->insertDbData($data);
            
            $round++;
	        
            if(!empty($courseId)) {
	        	break;
	        }
        }
        return $this->draftCourses;
    }

    private function checkIfAlreadyExists($courseId) {
        $data = $this->courseMigrationModel->checkIfAlreadyExists($courseId);
        if(empty($data)) {
            return false;
        } else {
            return true;
        }
    }

    private function getBasicCourseDetails($courseId, $limit, $offset) {
        $basicData = $this->courseMigrationModel->getBasicCourseDetails($courseId, $limit, $offset);
        $basicData = $this->formatBasicDataFromDb($basicData);
        return $basicData;
    }

    private function formatBasicDataFromDb($basicData) {
    	foreach ($basicData as $key => $rowValue) {
        	foreach ($rowValue as $attribute => $value) {
        		$basicData[$key]['course_type'] = array_search('Academic', $this->attributeValues['Course Type']);
        		$value = trim($value);
        		switch ($attribute) {
        			case 'institute_type':
        				if($value == 'university_national') {
        					$basicData[$key]['institute_type'] = 'university';
        				} elseif($value == 'institute') {
							$basicData[$key]['institute_type'] = 'institute';
        				}
        				break;

        			case 'course_type':
        				if(in_array($value, array('Full Time', 'Part Time'))) {
        					$basicData[$key]['education_type'] = array_search($value, $this->attributeValues['Education Type']);
                            $basicData[$key]['delivery_method'] = 33;
        				}
        				elseif(in_array($value, array('Correspondence', 'E-learning'))) {
        					if($value == 'E-learning') {
                                $value = 'Online';
                            }
                            else if(strtolower($value) == 'correspondence') {
        						$value = 'Distance/Correspondence';
                            }
        					$basicData[$key]['education_type'] = array_search('Part Time', $this->attributeValues['Education Type']);
        					$basicData[$key]['delivery_method'] = array_search($value, $this->attributeValues['Medium/Delivery Method']);
        				}
        				unset($basicData[$key]['course_type']);
        				break;

        			case 'course_level':
        				if(in_array($value, array('Degree', 'Diploma', 'Certification'))) {
        					if($value == 'Certification') {
        						$value = 'Certificate';
                                $basicData[$key]['course_type_info'][0]['course_level'] = 19;
        					}
                            //$basicData[$key]['status'] = 'live';
        					$basicData[$key]['course_variant'] = array_search('Single', $this->attributeValues['Course Variant']);
        					$basicData[$key]['course_type_info'][0]['credential'] = array_search($value, $this->attributeValues['Credential']);
        				}
        				elseif ($value == 'Dual Degree') {
        					$basicData[$key]['course_variant'] = array_search('Double', $this->attributeValues['Course Variant']);
                            //$basicData[$key]['status'] = 'draft';
                        }
                        else {
                            //$basicData[$key]['status'] = 'draft';
                        }
        				unset($basicData[$key]['course_level']);
        				break;
        				
        			case 'course_level_1':
                        if($value == 'Under Graduate' || $value == 'Diploma') {
                            $value = 'UG';
                        }
                        if($value == 'Post Graduate' || $value == 'Post Graduate Diploma') {
                            $value = 'PG';
                        }
    					if(!empty($value)) {
    						$basicData[$key]['course_type_info'][0]['course_level'] = array_search($value, $this->attributeValues['Course Level']);
    					} else {
                            //$basicData[$key]['status'] = 'draft';
                        }

        				unset($basicData[$key]['course_level_1']);
        				break;

                    case 'fees_unit':
                        $basicData[$key]['fees_unit'] = $this->currencyCodes[$basicData[$key]['fees_unit']];
                        break;

                    case 'duration_unit':
                        if($value == 'Year') {
                            $value = 'years';
                        }
                        $basicData[$key]['duration_unit'] = $value;
                        break;

        			case 'date_form_submission':
        				if ($value != '0000-00-00 00:00:00' && !empty($value)) {
        					$time = strtotime($value);
        					$basicData[$key]['important_dates'][0]['event_name'] = 'Application Submit Date';
							$basicData[$key]['important_dates'][0]['start_year'] = date("Y", $time);
							$basicData[$key]['important_dates'][0]['start_month'] = ltrim(date("m", $time), 0);
							$basicData[$key]['important_dates'][0]['start_date'] = ltrim(date("d", $time), 0);
        				}
        				unset($basicData[$key]['date_form_submission']);
        				break;
        				
        			case 'date_result_declaration':
        				if ($value != '0000-00-00 00:00:00' && !empty($value)) {
        					$time = strtotime($value);
        					$basicData[$key]['important_dates'][1]['event_name'] = 'Results Date';
							$basicData[$key]['important_dates'][1]['start_year'] = date("Y", $time);
							$basicData[$key]['important_dates'][1]['start_month'] = ltrim(date("m", $time), 0);
							$basicData[$key]['important_dates'][1]['start_date'] = ltrim(date("d", $time), 0);
        				}
        				unset($basicData[$key]['date_result_declaration']);
        				break;
        				
        			case 'date_course_comencement':
        				if ($value != '0000-00-00 00:00:00' && !empty($value)) {
        					$time = strtotime($value);
        					$basicData[$key]['important_dates'][2]['event_name'] = 'Course Commencement Date';
							$basicData[$key]['important_dates'][2]['start_year'] = date("Y", $time);
							$basicData[$key]['important_dates'][2]['start_month'] = ltrim(date("m", $time), 0);
							$basicData[$key]['important_dates'][2]['start_date'] = ltrim(date("d", $time), 0);
        				}
    					unset($basicData[$key]['date_course_comencement']);
        				break;
        		}
        	}
        }

        return $basicData;
    }

    private function getListingsMainDetails($courseIds, $resultData) {
        $dataFromDb = $this->courseMigrationModel->getListingsMainDetails($courseIds);
        $formattedData = $this->formatListingsMainDataFromDb($dataFromDb, $resultData);
        return $formattedData;
    }

    private function formatListingsMainDataFromDb($listingMainData, $resultData) {
        $result = array();
        foreach ($listingMainData as $key => $data) {
            foreach ($resultData['basicData'] as $basicData) {
                if($basicData['course_id'] == $data['listing_type_id']) {
                    $status                                             = $basicData['status'];
                    $courseData['courseName']                           = $data['listing_title'];
                    $courseData['primaryInstituteId']                   = $basicData['institute_id'];
                    $courseData['primaryInstituteName']                 = $basicData['institute_name'];
                    $courseData['baseCourseId']                         = $resultData['hierarchyData'][$basicData['course_id']][0]['base_course_id'];
                    $courseData['courseTypeInfo']['stream_id']          = $resultData['hierarchyData'][$basicData['course_id']][0]['stream_id'];
                    $courseData['courseTypeInfo']['substream_id']       = $resultData['hierarchyData'][$basicData['course_id']][0]['substream_id'];
                    $courseData['courseTypeInfo']['specialization_id']  = $resultData['hierarchyData'][$basicData['course_id']][0]['specialization_id'];
                    $courseData['mainLocationId']                       = $resultData['locationData'][$basicData['course_id']]['main_location'];
                    break;
                }
            }
            
            //set status
            if($status == 'live') {
                $listingMainData[$key]['status'] = 'stagging';
            }
            elseif($status == 'draft') {
                $listingMainData[$key]['status'] = 'stagging_draft';
            }

            //set seo details
            if(!empty($courseData['courseTypeInfo']['stream_id'])) {
                // $courseSeoData = $this->coursePostingLibrary->createCourseSeoUrl($courseData, $basicData['course_id']);
            }
            $listingMainData[$key]['listing_seo_url']           = $courseSeoData['listing_seo_url'];
            $listingMainData[$key]['listing_seo_title']         = $courseSeoData['listing_seo_title'];
            $listingMainData[$key]['listing_seo_description']   = $courseSeoData['listing_seo_description'];
            unset($listingMainData[$key]['listing_id']);
        }
        return $listingMainData;
    }

    private function getCustomBrochures($courseIds) {
    	$dataFromDb = $this->courseMigrationModel->getCourseCustomBrochures($courseIds);
    	$formattedData = $this->formatBrochureDataFromDb($dataFromDb);
    	return $formattedData;
    }

    private function formatBrochureDataFromDb($data) {
        $result = array();
        foreach ($data as $key => $rowValue) {
            if(!empty($rowValue['ebrochureUrl'])) {
                $key = $rowValue['listingTypeId'];
                $result[$key]['brochure_url'] = $rowValue['ebrochureUrl'];
                $result[$key]['updated_on'] = $rowValue['createdAt'];
            }
        }
        return $result;
    }

    private function getCourseAttributes($courseIds) {
        $dataFromDb = $this->courseMigrationModel->getCourseAttributes($courseIds);
        $formattedData = $this->formatAttributeDataFromDb($dataFromDb);
        return $formattedData;
    }

    private function formatAttributeDataFromDb($data) {
    	$result = array();
    	foreach ($data as $key => $rowValue) {
    		$key = $rowValue['course_id'];
    		if(!empty($rowValue['value'])) {
	        	switch ($rowValue['attribute']) {
	        		case 'AffiliatedToIndianUniName':
	        			$result[$key]['affiliated_university_name'] = $rowValue['value'];
	        			$result[$key]['affiliated_university_scope'] = 'domestic';
	        			break;

	        		case 'AffiliatedToForeignUniName':
	        			if(empty($result[$key]['affiliated_university_name']) && $result[$key]['affiliated_university_scope'] == 'domestic') {
		        			$result[$key]['affiliated_university_name'] = $rowValue['value'];
		        			$result[$key]['affiliated_university_scope'] = 'abroad';
		        		}
	        			break;

	        		case 'AICTEStatus':
	        			$result[$key]['additional_info'][0]['info_type'] = 'recognition';
	        			$result[$key]['additional_info'][0]['attribute_value_id'] = array_search('AICTE', $this->attributeValues['Course Recognition']);
	        			break;

	        		case 'DECStatus':
	        			$result[$key]['additional_info'][1]['info_type'] = 'recognition';
	        			$result[$key]['additional_info'][1]['attribute_value_id'] = array_search('DEB-UGC', $this->attributeValues['Course Recognition']);
	        			break;
	        		
	        		case 'otherEligibilityCriteria':
	        			$result[$key]['eligibility_description'] = $rowValue['value'];
	        			break;

	        		case 'SalaryMax':
	        			$result[$key]['max_salary'] = $rowValue['value'];
	        			break;

	        		case 'SalaryAvg':
	        			$result[$key]['avg_salary'] = $rowValue['value'];
	        			break;

	        		case 'SalaryMin':
	        			$result[$key]['min_salary'] = $rowValue['value'];
	        			break;

	        		case 'SalaryCurrency':
	        			if(!(empty($result[$key]['min_salary']) && empty($result[$key]['avg_salary']) && empty($result[$key]['max_salary']))) {
	        				$result[$key]['salary_unit'] = $this->currencyCodes[$rowValue['value']];
	        			}
	        			break;
	        	}
	        }
	        unset($data[$key]);
        }
    	return $result;
    }

    private function getCourseSpecializations($courseIds) {
    	$dataFromDb = $this->courseMigrationModel->getCourseSpecializations($courseIds);
        $formattedData = $this->formatSpecializationDataFromDb($dataFromDb);
        return $formattedData;
    }

    private function formatSpecializationDataFromDb($data) {
    	$result = array();
    	foreach ($data as $key => $rowValue) {
    		$categoryDataTemp = array();
    		if(!empty($rowValue['LDBCourseID'])) {
    			$categoryDataTemp['ldb_course_id'] = $rowValue['LDBCourseID'];
	    		
	    		//get subcategory from ldb course id
	    		$ldbCourseObj = $this->LDBCourseRepository->find($rowValue['LDBCourseID']);
	    		$categoryDataTemp['subcategory_id'] = $ldbCourseObj->getSubCategoryId();

	    		//get category from subcategory id
                $streamDataTemp = array();
                if(!empty($categoryDataTemp['subcategory_id'])) {
    	    		$subcategoryObj = $this->categoryRepository->find($categoryDataTemp['subcategory_id']);
    	    		$categoryDataTemp['category_id'] = $subcategoryObj->getParentId();
                
                    $streamDataTemp = $this->courseMigrationModel->getStreamDataByCategoryData($categoryDataTemp);
                }

                if(!empty($streamDataTemp)) {
                    $result[$rowValue['course_id']][] = $streamDataTemp;
                } else {
                    //$result[$rowValue['course_id']] = '';
                }
	    	}
    		unset($data[$key]);
    	}

        if(empty($result[$rowValue['course_id']])) {
                $result[$rowValue['course_id']] = '';
        }

        foreach ($result as $courseId => $value) {
            if(empty($value)) {
                //$result[$courseId][0]['status'] = 'draft';
            } else {
                $baseCourseId = $value[0]['base_course_id'];
                
                foreach ($value as $key => $data) {
                    unset($value[$key]['base_course_id']);
                    foreach ($value as $key1 => $data1) {
                        if($key != $key1) {
                            if($data['stream_id'] == $data1['stream_id'] && $data['substream_id'] == $data1['substream_id'] && $data['specialization_id'] == $data1['specialization_id']) {
                                unset($value[$key]);
                            }
                        }
                    }
                }
                
                $result[$courseId] = array_values($value);
                $result[$courseId][0]['is_primary'] = 1;
                $result[$courseId][0]['base_course_id'] = $baseCourseId;
            }
        }
        
    	return $result;
    }

    private function getCourseExams($courseIds) {
		$dataFromDb = $this->courseMigrationModel->getCourseExams($courseIds);
		$formattedData = $this->formatExamDataFromDb($dataFromDb);
    	return $formattedData;
    }

    private function formatExamDataFromDb($data) {
    	$result = array();
    	foreach ($data as $key => $value) {
    		$examDataTemp = array();
    		if(!empty($value['examId'])) {
	    		$examDataTemp['exam_id'] = $value['examId'];
	    		if(!empty($value['marks'])) {
		    		$examDataTemp['cut_off_value'] = $value['marks'];
		    		if($value['unit'] == 'total_marks') {
		    			$examDataTemp['cut_off_unit'] = 'score/marks';
		    		} else {
                        $examDataTemp['cut_off_unit'] = $value['unit'];
                    }
                    if($examDataTemp['cut_off_unit'] == 'percentage' || $examDataTemp['cut_off_unit'] == 'percentile') {
                        $examDataTemp['exam_out_of'] = 100;
                    } 
                    else if($examDataTemp['cut_off_unit'] == 'score/marks') {
		    		    $examDataTemp['exam_out_of'] = 200000;
                    }
                    else {
                        $examDataTemp['exam_out_of'] = 0;
                    }
		    	}
    			$result[$value['course_id']][] = $examDataTemp;
		    }
    	}
    	return $result;
    }

    private function getCourseLocationContactFees($courseIds) {
    	$dataFromDb = $this->courseMigrationModel->getCourseLocationContactFees($courseIds);
    	$formattedData = $this->formatLocationDataFromDb($dataFromDb);
    	return $formattedData;
    }

    private function formatLocationDataFromDb($data) {
    	foreach ($data as $key => $value) {
    		switch ($value['attribute_type']) {
    			case 'Head Office':
    				$isMain = 0;
	    			if($value['attribute_value'] == 'TRUE') {
	    				$isMain = 1;
                        $result[$value['course_id']]['main_location'] = $value['institute_location_id'];
                    }
                    $result[$value['course_id']]['locations'][] = array('institute_location_id'=>$value['institute_location_id'], 'is_main'=>$isMain);

    				if(!(empty($value['contact_main_phone']) && empty($value['contact_email']) && empty($value['website']))) {
		    			$result[$value['course_id']]['location_details'][$value['institute_location_id']]['contact_details']['generic_contact_number'] = $value['contact_main_phone'];
		    			$result[$value['course_id']]['location_details'][$value['institute_location_id']]['contact_details']['generic_email'] = $value['contact_email'];
		    			$result[$value['course_id']]['location_details'][$value['institute_location_id']]['contact_details']['website_url'] = $value['website'];
		    		}
    				break;

    			case 'Course Fee Value':
    				if(!empty($value['attribute_value'])) {
	    				$result[$value['course_id']]['location_details'][$value['institute_location_id']]['location_fees']['fees_value'] = $value['attribute_value'];
	    			}
	    			break;

    			case 'Course Fee Unit':
    				if(!empty($value['attribute_value'])) {
	    				$result[$value['course_id']]['location_details'][$value['institute_location_id']]['location_fees']['fees_unit'] = $this->currencyCodes[$value['attribute_value']];
	    			}
    				break;

    			case 'Show Fee Disclaimer':
    				if(!empty($value['attribute_value'])) {
	    				$result[$value['course_id']]['location_details'][$value['institute_location_id']]['location_fees']['fees_disclaimer'] = $value['attribute_value'];
	    			}
    				break;
    		}
    		unset($data[$key]);
    	}
    	return $result;
    }

    private function getCourseRecruitingCompanies($courseIds) {
        $dataFromDb = $this->courseMigrationModel->getCourseRecruitingCompanies($courseIds);
        $formattedData = $this->formatCompanyDataFromDb($dataFromDb);
        return $formattedData;
    }

    private function formatCompanyDataFromDb($data) {
        foreach ($data as $key => $value) {
            $result[$value['course_id']][$key]['company_id'] = $value['logo_id'];
        }
        return $result;
    }

    private function getCourseMedia($instituteIds) {
        $dataFromDb = $this->courseMigrationModel->getCourseMedia($instituteIds);
        $formattedData = $this->formatMediaDataFromDb($dataFromDb, $instituteIds);
        return $formattedData;
    }

    private function formatMediaDataFromDb($data, $courseIdInstituteIdMap) {
        $courseIds = array();
        foreach ($data as $key => $value) {
            $courseIds = array_merge($courseIds, array_keys($courseIdInstituteIdMap, $value['institute_id']));
        }
        $courseIds = array_unique($courseIds);
        return $courseIds;
    }

    private function initializeExcel($value='') {
    	$this->CI->load->library('common/reader');
        $this->CI->load->library('common/PHPExcel/IOFactory');
        
        $directoryPath  = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).'/public/';
        $inputFileName  = $directoryPath.'CourseMigrationDataSheet.xlsx';
        $inputFileType  = PHPExcel_IOFactory::identify($inputFileName);  
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);  
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($inputFileName);
        
        $this->objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
    }

    function migrateCoursesFromExcel($askedCourseId) {
    	$this->initializeExcel();
    	
        $highestRow     = $this->objWorksheet->getHighestRow();
		$highestColumn  = $this->objWorksheet->getHighestColumn();
        $headingsArray  = $this->objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);
        
        $round = 1; $batchSize = 1000000000000;
        while(1) {
            if(empty($askedCourseId)) {
                error_log("EXCEL, Starting round: ".$round.", batch size: ".$limit."...\n", 3, $this->logFilePath);
            }

	        $startRow = (($round - 1) * $batchSize) + 1;
	        if($startRow > $highestRow) {
	        	break;
	        }

	        if(($round * $batchSize) > $highestRow) {
	        	$maxRowsInRound = $highestRow;
	        } else {
	        	$maxRowsInRound = $round * $batchSize;
	        }
        	
        	for($row = $startRow; $row <= $maxRowsInRound; $row++) {
        		if($row == 1) {
        			continue;
        		}
        		
        		$courseId = $this->objWorksheet->getCell('A'.$row)->getValue();
                if(empty($courseId)) {
                    continue;
                }
                if(!empty($askedCourseId)) {
                    if($askedCourseId != $courseId) {
                        continue;
                    }
                }

                error_log("EXCEL, Migrating course: ".$courseId."...\n", 3, $this->logFilePath);
                $basicData[$courseId]['course_id'] = $courseId;
                //$basicData[$courseId]['status'] = $this->courseStatus[$courseId];
                $courseIds[] = $courseId;
        		
        		$infoId = 0;
        		foreach ($headingsArray[1] as $excelColumnKey => $excelColumnValue) {
        			$value = $this->objWorksheet->getCell($excelColumnKey.$row)->getValue();
        			if(empty($value)) {
	                    continue;
	                }
	                
	                switch ($excelColumnValue) {
	                	case 'Course Variant':
	                		$basicData[$courseId]['course_variant'] = array_search($value, $this->attributeValues['Course Variant']);
	                		break;
	                	
	                	case 'Credential 1':
	                		$basicData[$courseId]['course_type_info'][0]['type'] = 'entry';
	                		$basicData[$courseId]['course_type_info'][0]['credential'] = array_search($value, $this->attributeValues['Credential']);
	                		break;

	                	case 'Course Level 1':
	                		$basicData[$courseId]['course_type_info'][0]['type'] = 'entry';
	                		$basicData[$courseId]['course_type_info'][0]['course_level'] = array_search($value, $this->attributeValues['Course Level']);
	                		break;

	                	case 'Credential 2':
	                		$basicData[$courseId]['course_type_info'][1]['type'] = 'exit';
	                		$basicData[$courseId]['course_type_info'][1]['credential'] = array_search($value, $this->attributeValues['Credential']);
	                		break;

	                	case 'Course Level 2':
	                		$basicData[$courseId]['course_type_info'][1]['type'] = 'exit';
	                		$basicData[$courseId]['course_type_info'][1]['course_level'] = array_search($value, $this->attributeValues['Course Level']);
	                		break;

	                	case 'Education Type':
	                		$basicData[$courseId]['education_type'] = array_search($value, $this->attributeValues['Education Type']);
	                		break;

	                	case 'Medium/ Delivery Method':
	                		$basicData[$courseId]['delivery_method'] = array_search($value, $this->attributeValues['Medium/Delivery Method']);
	                		break;

                        case 'Time of Learning (ToL)':
                            $basicData[$courseId]['time_of_learning'] = array_search($value, $this->attributeValues['Time of Learning']);
                            break;

	                	case 'Executive':
	                	case 'Twinning':
	                	case 'Integrated':
	                	case 'Dual':
	                		if ($value == 'Yes') {
	                			$basicData[$courseId][$excelColumnValue] = 1;
	                		}
	                		else if ($value == 'No') {
	                			$basicData[$courseId][$excelColumnValue] = 0;
	                		}
	                		break;

	                	case 'Medium of Instruction':
	                		$attributeData[$courseId]['additional_info'][$infoId]['info_type'] = 'instruction_medium';
	        				$attributeData[$courseId]['additional_info'][$infoId]['attribute_value_id'] = array_search($value, $this->attributeValues['Medium of Instruction']);
	        				$infoId++;
	                		break;

	                	case 'Difficulty/Skill Level':
	                		$basicData[$courseId]['difficulty_level'] = array_search($value, $this->attributeValues['Difficulty / Skill Level']);
	                		break;

	                	case 'Course Recognition':
	                		$attributeData[$courseId]['additional_info'][$infoId]['info_type'] = 'recognition';
	        				$attributeData[$courseId]['additional_info'][$infoId]['attribute_value_id'] = array_search($value, $this->attributeValues['Course Recognition']);
	        				$infoId++;
	                		break;

	                	case 'Affiliation University Type':
	                		$attributeData[$courseId]['affiliated_university_scope'] = strtolower($value);
	                		break;

	                	case 'Affiliation - University ID':
	                		$attributeData[$courseId]['affiliated_university_id'] = $value;
	                		break;

                        case 'Affiliation - University Name':
                            $attributeData[$courseId]['affiliated_university_name'] = $value;
                            break;

	                	case 'Placements: Batch Ending':
	                		$attributeData[$courseId]['placements']['batch_year'] = $value;
	                		break;

	                	case 'Placements: % of batch Placed':
	                		$attributeData[$courseId]['placements']['percentage_batch_placed'] = $value;
	                		break;

                        case 'Placements: Maximum Salary':
                            $attributeData[$courseId]['placements']['max_salary'] = $value;
                            break;

                        case 'Placements: Minimum Salary':
                            $attributeData[$courseId]['placements']['min_salary'] = $value;
                            break;

	                	case 'Placements: Median Salary':
	                		$attributeData[$courseId]['placements']['median_salary'] = $value;
	                		break;

                        case 'Placements: Average Salary':
                            $attributeData[$courseId]['placements']['avg_salary'] = $value;
                            break;

	                	case 'Placements: International Offers (Number)':
	                		$attributeData[$courseId]['placements']['total_international_offers'] = $value;
	                		break;

	                	case 'Placements: International Offers (Max Salary)':
	                		$attributeData[$courseId]['placements']['max_international_salary'] = $value;
	                		break;

	                	case 'Placements: International Offers (Unit)':
	                		$attributeData[$courseId]['placements']['max_international_salary_unit'] = $this->currencyCodes[$value];
	                		break;

	                	case 'Internship: Year':
	                		$attributeData[$courseId]['internship']['batch_year'] = $value;
	                		break;

	                	case 'Internship: Unit':
	                		$attributeData[$courseId]['internship']['salary_unit'] = $this->currencyCodes[$value];
	                		break;

	                	case 'Internship: Average Stipend':
	                		$attributeData[$courseId]['internship']['avg_salary'] = $value;
	                		break;

	                	case 'Internship: Maximum Stipend':
	                		$attributeData[$courseId]['internship']['max_salary'] = $value;
	                		break;

	                	case 'Internship: Median Stipend':
	                		$attributeData[$courseId]['internship']['median_salary'] = $value;
	                		break;

	                	case 'Internship: Minimum Stipend':
	                		$attributeData[$courseId]['internship']['min_salary'] = $value;
	                		break;

	                	case 'Batch Profile: Gender (% Male)':
	                		$attributeData[$courseId]['batch_profile']['male'] = $value;
	                		break;

	                	case 'Batch Profile: Gender (% Female)':
	                		$attributeData[$courseId]['batch_profile']['female'] = $value;
	                		break;

	                	case 'Batch Profile: Gender (% Transgender)':
	                		$attributeData[$courseId]['batch_profile']['transgender'] = $value;
	                		break;

	                	case 'Batch Profile: Average Work-Ex':
	                		$attributeData[$courseId]['batch_profile']['avg_work-ex'] = $value;
	                		break;

	                	case 'Batch Profile: International Students':
	                		$attributeData[$courseId]['batch_profile']['international_students'] = $value;
	                		break;

	                	case 'Eligibility: Work-Ex (Min months)':
	                		$attributeData[$courseId]['eligibility']['work-ex_min'] = $value;
	                		$attributeData[$courseId]['eligibility']['work-ex_unit'] = 'months';
	                		break;

	                	case 'Eligibility: Work-Ex (Max months)':
	                		$attributeData[$courseId]['eligibility']['work-ex_max'] = $value;
	                		$attributeData[$courseId]['eligibility']['work-ex_unit'] = 'months';
	                		break;

	                	case 'Eligibility: Min Age':
	                		$attributeData[$courseId]['eligibility']['age_min'] = $value;
	                		break;

	                	case 'Eligibility: Max Age':
	                		$attributeData[$courseId]['eligibility']['age_max'] = $value;
	                		break;

	                	case 'Stage 1':
	                	case 'Stage 2':
	                	case 'Stage 3':
	                	case 'Stage 4':
	                	case 'Stage 5':
	                	case 'Stage 6':
	                	case 'Stage 7':
	                		$stageOrderArr = explode(' ', $excelColumnValue);
	                		$stageOrder = $stageOrderArr[1];
	                		$attributeData[$courseId]['admission_process'][$stageOrder]['stage_order'] = $stageOrder;
	                		$attributeData[$courseId]['admission_process'][$stageOrder]['stage_name'] = $value;
	                		break;

                        case 'Stage 1 - Others':
                        case 'Stage 2 - Others':
                        case 'Stage 3 - Others':
                        case 'Stage 4 - Others':
                        case 'Stage 5 - Others':
                        case 'Stage 6 - Others':
                        case 'Stage 7 - Others':
                            $attributeData[$courseId]['admission_process'][$stageOrder]['stage_name_other'] = $value;
                            break;

	                	case 'Description':
	                		$attributeData[$courseId]['admission_process'][$stageOrder]['education_background'] = $value;
	                		break;

	                	case 'USP 1':
	                	case 'USP 2':
	                	case 'USP 3':
	                	case 'USP 4':
	                	case 'USP 5':
	                	case 'USP 6':
	                	case 'USP 7':
	                		$attributeData[$courseId]['additional_info'][$infoId]['info_type'] = 'usp';
	                		$attributeData[$courseId]['additional_info'][$infoId]['description'] = $value;
	                		$infoId++;
	                		break;
	                }
	        	}
	        }

            error_log("EXCEL, Getting course status...\n", 3, $this->logFilePath);
            $courseStatusFromDb = $this->courseMigrationModel->getCourseStatus($courseIds);
            
            error_log("EXCEL, Getting course attributes...\n", 3, $this->logFilePath);
            $courseAttributesFromDb = $this->getCourseAttributes($courseIds);
            foreach ($courseIds as $key => $course) {
                foreach ($attributeData[$course]['additional_info'] as $key => $value) {
                    if($value['info_type'] == 'recognition') {
                        if($value['attribute_value_id'] == $courseAttributesFromDb[$course]['additional_info'][0]['attribute_value_id'] || $value['attribute_value_id'] == $courseAttributesFromDb[$course]['additional_info'][1]['attribute_value_id']) {
                            unset($attributeData[$course]['additional_info'][$key]);
                        }
                    }
                }

                $basicData[$course]['status'] = $courseStatusFromDb[$course];
                // if(!empty($attributeData[$course]['placements'])) {
                //     $attributeData[$course]['placements']['max_salary'] = $courseAttributesFromDb[$course]['max_salary'];
                //     $attributeData[$course]['placements']['avg_salary'] = $courseAttributesFromDb[$course]['avg_salary'];
                //     $attributeData[$course]['placements']['min_salary'] = $courseAttributesFromDb[$course]['min_salary'];
                // }
            }

            if(!empty($basicData)) {
    	        $data['basicData'] = $basicData;
    	        $data['attributeData'] = $attributeData;
    	        
    	        //write in DB
                error_log("EXCEL, Performing insert in DB...\n", 3, $this->logFilePath);
    	    	$this->courseMigrationModel->inserExcelData($data);
            }

	        $round++;
	    }
    }

    public function changeStatusListingsMain($type = 'course', $typeId) {
        $round = 1; $limit = 10000;
        while(1) {
            if($round == 30) {
                break;
            }
            if(empty($typeId)) {
                error_log("Starting round: ".$round.", batch size: ".$limit."...\n", 3, $this->logFilePath);
            }
            
            //$offset = ($round-1)*$limit;
            $minId = (($round-1)*$limit) + 1;
            $maxId = ($round)*$limit;

            //Get entries with status staging, staging draft, live
            error_log("Fetching listings_main entries with live, stagging, stagging_draft...\n", 3, $this->logFilePath);
            $dataFromDB = $this->courseMigrationModel->getListingsMainAllEntries($type, $typeId, $minId, $maxId);
            
            if(empty($dataFromDB)) {
                error_log("No data found in this round.\n", 3, $this->logFilePath);
                $round++;
                continue;
            }
            
            $formattedData = array();
            foreach ($dataFromDB as $key => $value) {
                if($value['listing_type'] == 'university_national') {
                    $listingType = 'institute';
                } else {
                    $listingType = $value['listing_type'];
                }
                $formattedData[$listingType][$value['listing_type_id']][$value['status']] = $value;
            }
            
            //copy live data to staging & staging draft both
            $liveCourseIds = array(); $liveInstituteIds = array();
            foreach ($formattedData as $listingType => $listingData) {
                foreach ($listingData as $listingId => $value) {
                    if(!empty($value['stagging']['listing_type_id'])) {
                        if(!empty($value['live'])) {
                            unset($formattedData[$listingType][$listingId]['stagging']['listing_id']);
                            $formattedData[$listingType][$listingId]['stagging']['expiry_date']                 = $value['live']['expiry_date'];
                            $formattedData[$listingType][$listingId]['stagging']['username']                    = $value['live']['username'];
                            $formattedData[$listingType][$listingId]['stagging']['pack_type']                   = $value['live']['pack_type'];
                            $formattedData[$listingType][$listingId]['stagging']['viewCount']                   = $value['live']['viewCount'];
                            $formattedData[$listingType][$listingId]['stagging']['abuseCount']                  = $value['live']['abuseCount'];
                            $formattedData[$listingType][$listingId]['stagging']['subscriptionId']              = $value['live']['subscriptionId'];
                            $formattedData[$listingType][$listingId]['stagging']['no_Of_Past_Free_Views']       = $value['live']['no_Of_Past_Free_Views'];
                            $formattedData[$listingType][$listingId]['stagging']['no_Of_Past_Paid_Views']       = $value['live']['no_Of_Past_Paid_Views'];
                        }

                        $formattedData[$listingType][$listingId]['stagging']['status']                      = 'live';

                        if($listingType == 'course' && !in_array($value['live']['listing_type_id'], $liveCourseIds) && !empty($value['live']['listing_type_id'])) {
                            $liveCourseIds[] = $value['live']['listing_type_id'];
                        }
                        if($listingType == 'institute' && !in_array($value['live']['listing_type_id'], $liveInstituteIds) && !empty($value['live']['listing_type_id'])) {
                            $liveInstituteIds[] = $value['live']['listing_type_id'];
                        }
                    }

                    if(!empty($value['stagging_draft']['listing_type_id'])) {
                        if(!empty($value['live'])) {
                            unset($formattedData[$listingType][$listingId]['stagging_draft']['listing_id']);
                            $formattedData[$listingType][$listingId]['stagging_draft']['expiry_date']           = $value['live']['expiry_date'];
                            $formattedData[$listingType][$listingId]['stagging_draft']['username']              = $value['live']['username'];
                            $formattedData[$listingType][$listingId]['stagging_draft']['pack_type']             = $value['live']['pack_type'];
                            $formattedData[$listingType][$listingId]['stagging_draft']['viewCount']             = $value['live']['viewCount'];
                            $formattedData[$listingType][$listingId]['stagging_draft']['abuseCount']            = $value['live']['abuseCount'];
                            $formattedData[$listingType][$listingId]['stagging_draft']['subscriptionId']        = $value['live']['subscriptionId'];
                            $formattedData[$listingType][$listingId]['stagging_draft']['no_Of_Past_Free_Views'] = $value['live']['no_Of_Past_Free_Views'];
                            $formattedData[$listingType][$listingId]['stagging_draft']['no_Of_Past_Paid_Views'] = $value['live']['no_Of_Past_Paid_Views'];
                        }
                        $formattedData[$listingType][$listingId]['stagging_draft']['status']                = 'draft';

                        if($listingType == 'course' && !in_array($value['live']['listing_type_id'], $liveCourseIds) && !empty($value['live']['listing_type_id'])) {
                            $liveCourseIds[] = $value['live']['listing_type_id'];
                        }
                        if($listingType == 'institute' && !in_array($value['live']['listing_type_id'], $liveInstituteIds) && !empty($value['live']['listing_type_id'])) {
                            $liveInstituteIds[] = $value['live']['listing_type_id'];
                        }
                    }

                    unset($formattedData[$listingType][$listingId]['live']);
                    if(empty($formattedData[$listingType][$listingId])) {
                        unset($formattedData[$listingType][$listingId]);
                    }
                }
            }
            
            //change live & draft to history //change staging, staging draft to live, draft
            $liveCourseIds = array_unique($liveCourseIds);
            $liveInstituteIds = array_unique($liveInstituteIds);
            
            $this->courseMigrationModel->changeStatusListingsMain('course', $liveCourseIds, $formattedData['course']);
            $this->courseMigrationModel->changeStatusListingsMain('institute', $liveInstituteIds, $formattedData['institute']);

            error_log("Migration done for round: ".$round."\n", 3, $this->logFilePath);
            if(!empty($typeId)) {
                break;
            }

            $round++;
        }
    }

    public function migrateCourseUrls($courseId) {
        $round = 1; $limit = 20000;
        while(1) {
            $offset = ($round-1)*$limit;
            $coursesFromDB = $this->courseMigrationModel->getCoursesForUrlUpdate($courseId, $limit, $offset);
            
            if(empty($coursesFromDB)) {
                error_log("No course ids found in this round.\n", 3, $this->logFilePath);
                break;
            }

            $courseIdsFromDB = array(); $coursePrimaryId = array();
            foreach ($coursesFromDB as $key => $value) {
                $courseIdsFromDB[] = $value['listing_type_id'];
                $coursePrimaryId[$value['listing_type_id']] = $value['listing_id'];
            }

            $courseData = $this->courseMigrationModel->getSeoUrlData($courseIdsFromDB);

            $courseSeoData = array();
            foreach ($courseData as $key => $value) {
                error_log("Getting URL for course id - ".print_r($value['course_id'], true)."\n", 3, $this->logFilePath);
                $seoData = array();
                $seoData['courseId']                            = $value['course_id'];
                $seoData['courseName']                          = $value['course_name'];
                $seoData['primaryInstituteId']                  = $value['primary_id'];
                $seoData['baseCourseId']                        = $value['base_course'];
                $seoData['courseTypeInfo']['stream_id']         = $value['stream_id'];
                $seoData['courseTypeInfo']['substream_id']      = $value['substream_id'];
                $seoData['courseTypeInfo']['specialization_id'] = $value['specialization_id'];
                $seoData['mainLocationId']                      = $value['listing_location_id'];
                if(!empty($seoData['courseTypeInfo']['stream_id'])) {
                    error_log("Seo data for course ".$value['course_id']." - ".print_r($seoData, true)."\n", 3, $this->logFilePath);
                    $courseSeoData[$value['course_id']] = $this->coursePostingLibrary->createCourseSeoUrl($seoData);
                    $courseSeoData[$value['course_id']]['id'] = $coursePrimaryId[$value['course_id']];
                }
            }
            
            $this->courseMigrationModel->insertUrl($courseSeoData);

            $round++;
            
            if(!empty($courseId)) {
                break;
            }
        }
    }

    function changeCourseStatus() {
        $result = $this->courseMigrationModel->getCourseToChange();
        foreach ($result as $key => $value) {
            $courseId[] = $value['course_id'];
        }
    }

    function changeExamMapping() {
        $result = $this->courseMigrationModel->changeExamMapping($this->examMappingOldToNew);
    }

    function migrateDeletedCourses($courseId) {
        $batchSize = 2000; $round = 0;

        //Get deleted courses to be migrated
        $coursesToMigrate = $this->courseMigrationModel->getDeletedCourses($courseId);
        
        $roundWiseCourseIds = array_chunk($coursesToMigrate, $batchSize);
        $totalRounds = count($roundWiseCourseIds);
        
        foreach ($roundWiseCourseIds as $round => $courseIds) {
            error_log("Starting round: ".($round+1).", batch size: ".$batchSize."...\n", 3, $this->logFilePath);
            
            //$offset = $round*$batchSize;

            //TABLES - course_details, listings_main
            error_log("Fetching basic course details...\n", 3, $this->logFilePath);
            $data['basicData'] = $this->getBasicDeletedCourseDetails($courseIds);

            if(empty($data['basicData'])) {
                continue;
            }

            //Migrating the ones that are national and has at least 1 deleted entry in listings_main
            $courseIdsFromDB = array(); $instituteIdMap = array();
            foreach ($data['basicData'] as $key => $value) {
                $courseIdsFromDB[] = $value['course_id'];
                $instituteIdMap[$value['course_id']] = $value['institute_id'];
            }
            
            //TABLE - clientCourseToLDBCourseMapping
            error_log("Fetching course hierarchy details...\n", 3, $this->logFilePath);
            $data['hierarchyData'] = $this->getCourseSpecializations($courseIdsFromDB);

            //TABLE - listings_ebrochures
            error_log("Fetching custom brochures...\n", 3, $this->logFilePath);
            $data['brochureData'] = $this->getCustomBrochures($courseIdsFromDB);
            
            //TABLE - course_attributes
            error_log("Fetching course attributes...\n", 3, $this->logFilePath);
            $data['attributeData'] = $this->getCourseAttributes($courseIdsFromDB);
            
            //TABLE - listingExamMap
            error_log("Fetching course exam details...\n", 3, $this->logFilePath);
            $data['examData'] = $this->getCourseExams($courseIdsFromDB);
            
            //TABLES - listing_contact_details, course_location_attribute
            error_log("Fetching course location details...\n", 3, $this->logFilePath);
            $data['locationData'] = $this->getCourseLocationContactFees($courseIdsFromDB);

            //TABLES - company_logo_mapping
            error_log("Fetching course recruiting company details...\n", 3, $this->logFilePath);
            $data['companyData'] = $this->getCourseRecruitingCompanies($courseIdsFromDB);

            //TABLES - shiksha_institutes_media_locations_mapping
            error_log("Fetching course media details...\n", 3, $this->logFilePath);
            $data['mediaData'] = $this->getCourseMedia($instituteIdMap);

            //write in DB
            error_log("Performing insert in DB...\n", 3, $this->logFilePath);
            $this->courseMigrationModel->insertDbDeletedData($data);
        }
    }

    private function getBasicDeletedCourseDetails($courseIds) {
        $basicData = $this->courseMigrationModel->getBasicDeletedCourseDetails($courseIds, $limit, $offset);
        $basicData = $this->formatBasicDataFromDb($basicData);
        return $basicData;
    }
}