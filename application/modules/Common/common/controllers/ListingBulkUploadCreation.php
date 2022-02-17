<?php

class ListingBulkUploadCreation extends MX_Controller{

	var $totalDataPointsUpdated = 0;
	var $totalCoursesUpdated = 0;

	var $courseVariant = array('single'=>3,'double'=>4);
	var $credential = array('degree'=>9,'diploma'=>10,'certificate'=>11);
	var $courseLevel = array('after 10th'=>13,'ug'=>14,'pg'=>15,'advanced masters'=>16,'doctorate'=>17,'post doctorate'=>18);
	var $educationType = array('full time'=>20,'part time'=>21);
	var $recognition = array(
		"AICTE"=>45,
		"BCI"=>46,
		"CCH"=>47,
		"CCIM"=>48,
		"COA"=>49,
		"DCI"=>50,
		"DEB-UGC"=>51,
		"ICAR"=>52,
		"INC"=>53,
		"MCI"=>54,
		"NBA"=>55,
		"NCRI"=>56,
		"NCTE"=>57,
		"PCI"=>58,
		"RCI"=>59,
		"SIRO"=>60,
		"VCI"=>61
	);
	var $deliveryMethod = array(
		"classroom" => 33,
		"distance/correspondence" => 34,
		"on the job (apprenticeship)" => 35,
		"virtual classroom" => 36,
		"blend" => 37,
		"online" => 38
	);

        function __construct() {
                $this->instituteDetailLib = $this->load->library('nationalInstitute/InstituteDetailLib');
                $this->model = $this->load->model('common/listingbulkupdatecreationmodel');
		$this->library = $this->load->library('common/ListingBulkUploadCreationLib');
		$this->coursePostingLib = $this->load->library('nationalCourse/CoursePostingLib');
        }

        public function listingBulkUploadValidate($type = 'institute'){

            //Define the file location
	    if($type == "institute"){
		$fileName = "InstituteCreation";
	    }
	    else{
		$fileName = "CourseCreation";
	    }
            $fileName = "/var/www/html/shiksha/public/enterpriseDoc/$fileName.xlsx";


            $this->load->library('common/reader');
            $this->load->library('common/PHPExcel/IOFactory');
            ini_set('memory_limit','2048M');

            //Load the File
            $inputFileName = $fileName;
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);

            /**  Load $inputFileName to a PHPExcel Object  **/
            $objPHPExcel = $objReader->load($inputFileName);

            $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
            $highestRow = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();

            $headingsArray = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);
            $headingsArray = $headingsArray[1];

            for ($row = 2; $row <= $highestRow; ++$row) {
		echo "<br/>Row: ".$row."</br>";
                $dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true);
                if(isset($dataRow[$row]) && is_array($dataRow[$row]) && count($dataRow[$row])>1){
		    if($type == "institute"){
			$response = $this->validateInstituteData($dataRow[$row]);
		    }
		    else{
			$response = $this->validateCourseData($dataRow[$row]);
		    }
                }
            }

	    if($this->library->issueFound){
		echo "<br/><br/>Issues Found !!!!";
	    }
	    else{
		echo "<br/><br/>No Issues Found. Please proceed with Upload !!!!";
	    }
        }

        public function validateInstituteData($data){

		$this->library->checkMandatory($data['A'],'instituteType');
		$this->library->checkEnumValue($data['A'],'instituteType');
		$this->library->checkInstituteIdType($data['B'], $data['A']);
		$this->library->checkMandatory($data['C'],'instituteName');
		$this->library->checkValidations($data['C'],'instituteName');
		$this->library->checkEnumValue($data['D'],'ownership');
		$this->library->checkMandatory($data['E'],'location');
		$this->library->checkCity($data['E']);
		$this->library->checkEstablishment($data['F']);
		$this->library->checkEnumValue($data['G'],'studentType');
		$this->library->checkValidations($data['H'],'address');
		$this->library->checkLink($data['I']);
		$this->library->checkPhoneNumber($data['J'],'contactGeneric');
		$this->library->checkPhoneNumber($data['K'],'contactAdmission');
		$this->library->checkEmail($data['L']);
		$this->library->checkEnumValue($data['M'],'naac');
		$this->library->checkEnumValue($data['N'],'isAutonomous');
		$this->library->checkEnumValue($data['O'],'hostel');
		$this->library->checkEnumValue($data['P'],'boysHostel');
		$this->library->checkValidations($data['Q'],'boysNoOfBeds');
		$this->library->checkEnumValue($data['R'],'girlsHostel');
		$this->library->checkValidations($data['S'],'girlsNoOfBeds');
		$this->library->checkEnumValue($data['T'],'coedHostel');
		$this->library->checkValidations($data['U'],'coedNoOfBeds');
		$this->library->checkValidations($data['V'],'hostelHighlights');
		$this->library->checkLatLong($data['W'],'Latitude');
		$this->library->checkLatLong($data['X'],'Longitude');
		$this->library->checkEnumValue($data['Y'],'library');
		$this->library->checkValidations($data['Z'],'libraryHighlights');
		$this->library->checkEnumValue($data['AA'],'labs');
		$this->library->checkValidations($data['AB'],'labHighlights');
		$this->library->checkEnumValue($data['AC'],'sportsComplex');
		$this->library->checkValidations($data['AD'],'sportsHighlights');
		$this->library->checkEnumValue($data['AE'],'auditorium');
		$this->library->checkEnumValue($data['AF'],'gym');
		$this->library->checkValidations($data['AG'],'gymHighlights');
		$this->library->checkEnumValue($data['AH'],'hospital');
		$this->library->checkEnumValue($data['AI'],'cafeteria');
		$this->library->checkValidations($data['AJ'],'cafeteriaHighlights');
		$this->library->checkEnumValue($data['AK'],'wifi');
		
        }


        public function validateCourseData($data){

		$this->library->checkMandatory($data['A'],'parentId');
		$this->library->checkInstituteId($data['A']);

		$this->library->checkMandatory($data['B'],'courseVariant');
		$this->library->checkEnumValue($data['B'],'courseVariant');

		$this->library->checkMandatory($data['C'],'entryCred');
		$this->library->checkEnumValue($data['C'],'entryCred');

		$this->library->checkEntryLevel($data['D'],$data['C']);
		$this->library->checkEnumValue($data['D'],'entryLevel');

		$this->library->checkMandatory($data['E'],'educationType');
		$this->library->checkEnumValue($data['E'],'educationType');

		$this->library->checkEnumValue($data['F'],'deliveryMethod');

		$this->library->checkMandatory($data['G'],'courseName');
		$this->library->checkValidations($data['G'],'courseName');

		$this->library->checkMandatory($data['H'],'duration');
		$this->library->checkValidations($data['H'],'duration');

		$this->library->checkMandatory($data['I'],'durationUnit');
		$this->library->checkEnumValue($data['I'],'durationUnit');

		$this->library->checkMandatory($data['J'],'streamId');
		$this->library->checkStream($data['J'],'streamId');

		$this->library->checkSubStream($data['K'],'subStreamId',$data['J']);

		$this->library->checkSpecialization($data['L'],'specializationId',$data['K'],$data['J']);

		//$this->library->checkStream($data['M'],'secondaryStreamId');
		//$this->library->checkSubStream($data['N'],'secondarySubStreamId');
		//$this->library->checkSpecialization($data['O'],'secondarySpecializationId');

		$this->library->checkBaseCourse($data['M'], 'baseCourseId',$data['J']);

		$this->library->checkEnumValue($data['N'],'exitCredential');

		$this->library->checkEnumValue($data['O'],'exitCourseLevel');

		$this->library->checkStream($data['P'],'exitStreamId');

		$this->library->checkSubStream($data['Q'],'exitSubStreamId',$data['P']);

		$this->library->checkSpecialization($data['R'],'exitSpecializationId',$data['Q'],$data['P']);

		//$this->library->checkStream($data['V'],'exitSecondaryStreamId');
		//$this->library->checkSubStream($data['W'],'exitSecondarySubStreamId');
		//$this->library->checkSpecialization($data['X'],'exitSecondarySpecializationId');

		$this->library->checkBaseCourse($data['S'],'exitBaseCourseId',$data['P']);

		$this->library->checkValidations($data['T'],'totalSeats');

		$this->library->checkEnumValue($data['U'],'approvingBodies');

		$this->library->checkUniversityId($data['V']);

		$this->library->checkEnumValue($data['W'],'affiliatingBody');

		$this->library->checkDeliveryMethod($data['E'],$data['F']);
        }


        public function listingBulkUploadCreate($type = 'institute'){

            //Define the file location
	    if($type == "institute"){
		$fileName = "InstituteCreation";
	    }
	    else{
		$fileName = "CourseCreation";
	    }
            $fileName = "/var/www/html/shiksha/public/enterpriseDoc/$fileName.xlsx";


            $this->load->library('common/reader');
            $this->load->library('common/PHPExcel/IOFactory');
            ini_set('memory_limit','2048M');

            //Load the File
            $inputFileName = $fileName;
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);

            /**  Load $inputFileName to a PHPExcel Object  **/
            $objPHPExcel = $objReader->load($inputFileName);

            $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
            $highestRow = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();

            $headingsArray = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);
            $headingsArray = $headingsArray[1];

            for ($row = 2; $row <= $highestRow; ++$row) {
                $dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true);
                if(isset($dataRow[$row]) && is_array($dataRow[$row]) && count($dataRow[$row])>1){
		    if($type == "institute"){
			$response = $this->createInstituteData($dataRow[$row]);
		    }
		    else{
			$response = $this->createCourseData($dataRow[$row]);
		    }
                }
            }

        }

	public function createInstituteData($row){
		$comment = "Institute created via bulk upload";
		$year = date("Y");

		$instituteType = strtolower($row['A']);

		$parent_entity_name = "";
		$parentType = "";
		$parentId = $row['B'];
		if($parentId > 0){
			$parentDetails = $this->model->getParentDetails($parentId);
			$parentType = $parentDetails['type'];
			$parent_entity_name = $parentType."_".$parentId;
		}
		$instituteName = trim($row['C']);
		$ownership = strtolower($row['D']);
		if($ownership == "public/government"){
			$ownership = "public";
		}
		else if($ownership == "public-private"){
			$ownership = "partnership";
		}
		$studentType = isset($row['G'])?strtolower($row['G']):'co-ed';
 		if(strtolower($studentType) == 'girls only'){
 		    $studentType = "girls";
 		}
 		else if(strtolower($studentType) == 'boys only'){
 		    $studentType = "boys";
 		}

		$cityId = $row['E'];
		$stateId = $this->model->getStateId($cityId);
		$latitude = $row['W'];
		$longitude = $row['X'];
		$address = $row['H'];
		$website = $row['I'];
		$admissionNumber = $row['K'];
		$genericNumber = $row['J'];
		$admissionEmail = $row['L'];
		$naac = isset($row['M'])?"grade_".strtolower($row['M']):"";
		$isAutonomous = $row['N'];

		$basicArray = array(
			"instituteObj" => array(
				"institute_id" => 0,
				"postingListingType" => "Institute",				
				"institute_type" => $instituteType,
				"university_type" => "",
				"is_dummy" => "false",
				"parent_entity_name" => $parent_entity_name,
				"is_satellite_entity" => false,
				"name" => $instituteName,
				"main_location" => array(
					"state_id"=>$stateId,
					"state_name"=>"",
					"city_id"=>$cityId,
					"city_name"=>"",
					"locality_id"=>0,
					"locality_name"=>"",
					"is_main_location"=>true,
					"contact_details" => array(
						"latitude"=>$latitude,
						"longitude"=>$longitude,
						"address"=>$address,
						"website_url"=>$website,
						"admission_contact_number"=>$admissionNumber,
						"generic_contact_number"=>$genericNumber,
						"admission_email"=>$admissionEmail,
						"generic_email"=>""
					)
				),
				"short_name" => "",
				"about_college" => "",
				"abbreviation" => "",
				"synonyms" => array(
					"0" => array("value" => "")
				),
				"ownership" => $ownership,
				"students_type" => $studentType,
				"accreditation" => $naac,
				"academic_staff" => array(
					"0" => array('type'=>'167','position'=>1)
				),
				"staff_faculty_highlights" => "",
				"research_projects" => array(
					"0" => array("value"=>"")
				),
				"events" => array(
					"0" => array("id"=>"","type"=>"","randomIdentifier"=>"event_1","position"=>1)
				),
				"scholarships" => array(
					"0" => array("type" => "")
				),
				"usp" => array(
					"0" => array("value" => "")
				),
				"brochure_url" => "",
				"brochure_size" => "",
				"brochure_year" => $year,
				"locations" => array(),
				"logo_url" => "",
				"photos" => array(),
				"videos" => array(),
				"companies" => array(),
				"seo_title" => "",
				"seo_description" => "",
				"posting_comments" => $comment,
				"statusFlag" => "live",	
				"parentHierarichyDetails" => array(),
				"savingMode" => "add",
				"is_open_university" => false,
				"is_ugc_approved" => true,
				"is_aiu_membership" => false,
				"disabled_url" => "",
				"media_server_domain" => SHIKSHA_HOME,
				"parent_entity_id" => $parentId,
				"parent_entity_type" => $parentType
				)
		);

		$isAutonomous = isset($row['N']) ? (strtolower($row['N']) == 'yes')?'1':'0' : "";
		if($isAutonomous !== ''){
			$basicArray['instituteObj']['is_autonomous'] = $isAutonomous;
		}
		
		$establishmentYear = isset($row['F'])?$row['F']:'';
		if($establishmentYear != ''){
			$basicArray['instituteObj']['establishment_year'] = $establishmentYear;
		}

		//Code to add Facilities array 
		$facilities = array(
			"1" => array(
				"name" => 'library',
				"child_facilities" => array(),
				"id" => 1,
				"display_type" => "yes_no"
			),
			"2" => array(
				"name" => 'Wi-Fi Campus',
				"child_facilities" => array(),
				"id" => 1,
				"display_type" => "yes_no"
			),
                        "3" => Array
                        (
                            "name" => "Hostel",
                            "child_facilities" => Array
                                (
                                    "4" => Array
                                        (
                                            "other_fields" => Array
                                                (
                                                ),
                                            "custom_fields" => Array
                                                (
                                                    "0" => Array
                                                        (
                                                            "name" => "Number of Rooms",
                                                            "value" => "",
                                                            "id" => "1"
                                                        ),
                                                    "1" => Array
                                                        (
                                                            "name" => "Number of beds",
                                                            "value" => $row['Q'],
                                                            "id" => "2"
                                                        ),
                                                    "2" => Array
                                                        (
                                                            "name" => "Single Occupancy",
                                                            "value" => "",
                                                            "id" => "3"
                                                        ),
                                                    "3" => Array
                                                        (
                                                            "name" => "Shared Rooms",
                                                            "value" => "",
                                                            "id" => "4"
                                                        ),
                                                    "4" => Array
                                                        (
                                                            "name" => "In-Campus Hostel",
                                                            "value" => "",
                                                            "id" => "5"
                                                        ),
                                                    "5" => Array
                                                        (
                                                            "name" => "Off-Campus Hostel",
                                                            "value" => "",
                                                            "id" => "6"
                                                        )
                                                ),
                                            "values" => Array
                                                (
                                                ),
                                            "id" => "4",
                                            "name" => "Boys Hostel",
                                            "display_type" => "yes_no"
                                        ),

                                    "5" => Array
                                        (
                                            "other_fields" => Array
                                                (
                                                ),
                                            "custom_fields" => Array
                                                (
                                                    "0" => Array
                                                        (
                                                            "name" => "Number of Rooms",
                                                            "value" => "",
                                                            "id" => "1"
                                                        ),
                                                    "1" => Array
                                                        (
                                                            "name" => "Number of beds",
                                                            "value" => $row['S'],
                                                            "id" => "2"
                                                        ),
                                                    "2" => Array
                                                        (
                                                            "name" => "Single Occupancy",
                                                            "value" => "",
                                                            "id" => "3"
                                                        ),
                                                    "3" => Array
                                                        (
                                                            "name" => "Shared Rooms",
                                                            "value" => "",
                                                            "id" => "4"
                                                        ),
                                                    "4" => Array
                                                        (
                                                            "name" => "In-Campus Hostel",
                                                            "value" => "",
                                                            "id" => "5"
                                                        ),
                                                    "5" => Array
                                                        (
                                                            "name" => "Off-Campus Hostel",
                                                            "value" => "",
                                                            "id" => "6"
                                                        )
                                                ),
                                            "values" => Array
                                                (
                                                ),
                                            "id" => "5",
                                            "name" => "Girls Hostel",
                                            "display_type" => "yes_no"
                                        ),

                                    "6" => Array
                                        (
                                            "other_fields" => Array
                                                (
                                                ),
                                            "custom_fields" => Array
                                                (
                                                    "0" => Array
                                                        (
                                                            "name" => "Number of Rooms",
                                                            "value" => "",
                                                            "id" => "1"
                                                        ),
                                                    "1" => Array
                                                        (
                                                            "name" => "Number of beds",
                                                            "value" => $row['U'],
                                                            "id" => "2"
                                                        ),
                                                    "2" => Array
                                                        (
                                                            "name" => "Single Occupancy",
                                                            "value" => "",
                                                            "id" => "3"
                                                        ),
                                                    "3" => Array
                                                        (
                                                            "name" => "Shared Rooms",
                                                            "value" => "",
                                                            "id" => "4"
                                                        ),
                                                    "4" => Array
                                                        (
                                                            "name" => "In-Campus Hostel",
                                                            "value" => "",
                                                            "id" => "5"
                                                        ),
                                                    "5" => Array
                                                        (
                                                            "name" => "Off-Campus Hostel",
                                                            "value" => "",
                                                            "id" => "6"
                                                        )
                                                ),
                                            "values" => Array
                                                (
                                                ),
                                            "id" => "6",
                                            "name" => "Co-ed Hostel",
                                            "display_type" => "yes_no"
                                        ),

                                    "7" => Array
                                        (
                                            "other_fields" => Array
                                                (
                                                ),

                                            "custom_fields" => Array
                                                (
                                                ),

                                            "values" => Array
                                                (
                                                ),

                                            "id" => 7,
                                            "name" => "Mandatory Hostel",
                                            "display_type" => "yes_no"
                                        )

                                ),

                            "id" => 3,
                            "display_type" => "yes_no"
                        ),
                    "8" => Array
                        (
                            "name" => "Convenience Store",
                            "child_facilities" => Array
                                (
                                ),
                            "id" => 8,
                            "display_type" => "yes_no"
                        ),
                    "9" => Array
                        (
                            "name" => "Cafeteria",
                            "child_facilities" => Array
                                (
                                ),
                            "id" => 9,
                            "display_type" => "yes_no"
                        ),
                    "10" => Array
                        (
                            "name" => "Shuttle Service",
                            "child_facilities" => Array
                                (
                                ),
                            "id" => 10,
                            "display_type" => "yes_no"
                        ),
                    "11" => Array
                        (
                            "name" => "Auditorium",
                            "child_facilities" => Array
                                (
                                ),
                            "id" => 11,
                            "display_type" => "yes_no"
                        ),
                    "12" => Array
                        (
                            "name" => "Hospital / Medical Facilities",
                            "child_facilities" => Array
                                (
                                ),
                            "id" => 12,
                            "display_type" => "yes_no"
                        ),
                    "13" => Array
                        (
                            "name" => "Sports Complex",
                            "child_facilities" => Array
                                (
                                    "23" => Array
                                        (
                                            "other_fields" => Array
                                                (
                                                ),
                                            "custom_fields" => Array
                                                (
                                                ),

                                            "values" => Array
                                                (
                                                ),

                                            "id" => 23,
                                            "name" => "Sports Facilities",
                                            "display_type" => "select"
                                        ),
                                    "24" => Array
                                        (
                                            "other_fields" => Array
                                                (
                                                    "0" => Array
                                                        (
                                                            "value" => ""
                                                        )
                                                ),
                                            "custom_fields" => Array
                                                (
                                                ),
                                            "values" => Array
                                                (
                                                ),
                                            "id" => 24,
                                            "name" => "Others",
                                            "display_type" => "add_more"
                                        )

                                ),

                            "id" => 13,
                            "display_type" => "yes_no"
                        ),
                    "14" => Array
                        (
                            "name" => "Gym",
                            "child_facilities" => Array
                                (
                                ),
                            "id" => 14,
                            "display_type" => "yes_no"
                        ),
                    "15" => Array
                        (
                            "name" => "A/C Classrooms",
                            "child_facilities" => Array
                                (
                                ),
                            "id" => 15,
                            "display_type" => "yes_no"
                        ),
                    "16" => Array
                        (
                            "name" => "Labs",
                            "child_facilities" => Array
                                (
                                    "25" => Array
                                        (
                                            "other_fields" => Array
                                                (
                                                ),
                                            "custom_fields" => Array
                                                (
                                                ),
                                            "values" => Array
                                                (
                                                ),
                                            "id" => 25,
                                            "name" => "Labs Facilities",
                                            "display_type" => "select"
                                        ),
                                    "26" => Array
                                        (
                                            "other_fields" => Array
                                                (
                                                    "0" => Array
                                                        (
                                                            "value" => ""
                                                        )

                                                ),

                                            "custom_fields" => Array
                                                (
                                                ),

                                            "values" => Array
                                                (
                                                ),

                                            "id" => 26,
                                            "name" => "Others",
                                            "display_type" => "add_more"
                                        )

                                ),

                            "id" => 16,
                            "display_type" => "yes_no"
                        ),

                    "17" => Array
                        (
                            "name" => "Others",
                            "child_facilities" => Array
                                (
                                    "18" => Array
                                        (
                                            "other_fields" => Array
                                                (
                                                ),

                                            "custom_fields" => Array
                                                (
                                                ),

                                            "values" => Array
                                                (
                                                ),

                                            "id" => 18,
                                            "name" => "Music Room",
                                            "display_type" => "yes_no"
                                        ),

                                    "19" => Array
                                        (
                                            "other_fields" => Array
                                                (
                                                ),

                                            "custom_fields" => Array
                                                (
                                                ),

                                            "values" => Array
                                                (
                                                ),

                                            "id" => 19,
                                            "name" => "Dance Room",
                                            "display_type" => "yes_no"
                                        ),

                                    "20" => Array
                                        (
                                            "other_fields" => Array
                                                (
                                                ),

                                            "custom_fields" => Array
                                                (
                                                ),

                                            "values" => Array
                                                (
                                                ),

                                            "id" => 20,
                                            "name" => "Design Studio",
                                            "display_type" => "yes_no"
                                        ),

                                    "21" => Array
                                        (
                                            "other_fields" => Array
                                                (
                                                ),

                                            "custom_fields" => Array
                                                (
                                                ),

                                            "values" => Array
                                                (
                                                ),

                                            "id" => 21,
                                            "name" => "Moot Court (Law)",
                                            "display_type" => "yes_no"
                                        ),

                                    "22" => Array
                                        (
                                            "other_fields" => Array
                                                (
                                                    "0" => Array
                                                        (
                                                            "value" => ""
                                                        )

                                                ),

                                            "custom_fields" => Array
                                                (
                                                ),

                                            "values" => Array
                                                (
                                                ),

                                            "id" => 22,
                                            "name" => "Others",
                                            "display_type" => "add_more"
                                        )

                                ),

                            "id" => 17,
                            "display_type" => "none"
                        )


		);

		//Hostel
		if(strtolower($row['O']) == 'yes'){
			$facilities["3"]["is_present"] = "yes";
		}
		else if(strtolower($row['O']) == 'no'){
			$facilities["3"]["is_present"] = "no";
		}
		if(strtolower($row['V']) !== ''){
			$facilities["3"]["description"] = $row['V'];
		}

		//Boys Hostel
		if(strtolower($row['P']) == 'yes'){
			$facilities["3"]["child_facilities"]["4"]["is_present"] = "yes";
		}
		else if(strtolower($row['P']) == 'no'){
			$facilities["3"]["child_facilities"]["4"]["is_present"] = "no";
		}

		//Girls Hostel
		if(strtolower($row['R']) == 'yes'){
			$facilities["3"]["child_facilities"]["5"]["is_present"] = "yes";
		}
		else if(strtolower($row['R']) == 'no'){
			$facilities["3"]["child_facilities"]["5"]["is_present"] = "no";
		}

		//Coed Hostel
		if(strtolower($row['T']) == 'yes'){
			$facilities["3"]["child_facilities"]["6"]["is_present"] = "yes";
		}
		else if(strtolower($row['T']) == 'no'){
			$facilities["3"]["child_facilities"]["6"]["is_present"] = "no";
		}

		//Library
		if(strtolower($row['Y']) == 'yes'){
			$facilities["1"]["is_present"] = "yes";
		}
		else if(strtolower($row['Y']) == 'no'){
			$facilities["1"]["is_present"] = "no";
		}
		if(strtolower($row['Z']) !== ''){
			$facilities["1"]["description"] = $row['Z'];
		}

		//Labs
		if(strtolower($row['AA']) == 'yes'){
			$facilities["16"]["is_present"] = "yes";
		}
		else if(strtolower($row['AA']) == 'no'){
			$facilities["16"]["is_present"] = "no";
		}
		if(strtolower($row['AB']) !== ''){
			$facilities["16"]["description"] = $row['AB'];
		}

		//Sports
		if(strtolower($row['AC']) == 'yes'){
			$facilities["13"]["is_present"] = "yes";
		}
		else if(strtolower($row['AC']) == 'no'){
			$facilities["13"]["is_present"] = "no";
		}
		if(strtolower($row['AD']) !== ''){
			$facilities["13"]["description"] = $row['AD'];
		}

		//Audi
		if(strtolower($row['AE']) == 'yes'){
			$facilities["11"]["is_present"] = "yes";
		}
		else if(strtolower($row['AE']) == 'no'){
			$facilities["11"]["is_present"] = "no";
		}

		//Gym
		if(strtolower($row['AF']) == 'yes'){
			$facilities["14"]["is_present"] = "yes";
		}
		else if(strtolower($row['AF']) == 'no'){
			$facilities["14"]["is_present"] = "no";
		}
		if(strtolower($row['AG']) !== ''){
			$facilities["14"]["description"] = $row['AG'];
		}

		//Hospital
		if(strtolower($row['AH']) == 'yes'){
			$facilities["12"]["is_present"] = "yes";
		}
		else if(strtolower($row['AH']) == 'no'){
			$facilities["12"]["is_present"] = "no";
		}

		//cafeteria
		if(strtolower($row['AI']) == 'yes'){
			$facilities["9"]["is_present"] = "yes";
		}
		else if(strtolower($row['AI']) == 'no'){
			$facilities["9"]["is_present"] = "no";
		}
		if(strtolower($row['AJ']) !== ''){
			$facilities["9"]["description"] = $row['AJ'];
		}

		//Wifi
		if(strtolower($row['AK']) == 'yes'){
			$facilities["2"]["is_present"] = "yes";
		}
		else if(strtolower($row['AK']) == 'no'){
			$facilities["2"]["is_present"] = "no";
		}

		$basicArray['instituteObj']['facilities'] = $facilities;


		//Convert Array to JSON
		$string = json_encode($basicArray);

		//echo $string;exit;

		$returnVal = Modules::run("nationalInstitute/InstitutePosting/saveInstitute", $string);
		$return = json_decode($returnVal, true);
		if($return['data']['status'] == 'success'){
			echo "<br/>".$return['data']['message'];
		}
		else{
			echo "<br/>Institute with Name = $instituteName could not be created due to some error.";
			exit;
		}

	}	


	public function createCourseData($row){
		$comment = "Course created via bulk upload";
		$year = date("Y");

		$parentId = $row['A'];
		$parentDetails = $this->model->getParentDetails($parentId);
		$parentType = $parentDetails['type'];
		$parentName = $parentDetails['name'];
		$parent_entity_name = $parentType."_".$parentId;

		$locationId = $this->model->getLocationId($parentId);

		$variantStr = $this->courseVariant[strtolower($row['B'])];
		$entryCredentialStr = $this->credential[strtolower($row['C'])];
		$entryLevelStr = $this->courseLevel[strtolower($row['D'])];
		$educationTypeStr = $this->educationType[strtolower($row['E'])];
		$deliveryMethodStr = ($row['F']!="")?$this->deliveryMethod[strtolower($row['F'])]:"";

		$courseName = trim($row['G']);
		$durationValue = $row['H'];
		$durationUnit = strtolower($row['I']);
		$primaryStream = $row['J'];
		$primarySubStream = ($row['K']!="" && $row['K']!="0")?$row['K']:"none";
		$primarySpecialization = ($row['L']!="" && $row['L']!="0")?$row['L']:"none";
		$primaryBaseCourseId = ($row['M']!="")?$row['M']:"0";

		$totalSeats = $row['T'];
		$recognitionStr = ($row['U']!="")?$this->recognition[strtoupper($row['U'])]:"";		
		$affiliatedUniversityId = strtolower($row['V']);
		$affiliatedUniversityName = "";
		if($affiliatedUniversityId > 0){
			$parentDetails = $this->model->getParentDetails($affiliatedUniversityId);
			$parentType = $parentDetails['type'];
			$affiliatedUniversityName = $parentDetails['name'];
		}

		$affiliatedUniversityType = strtolower($row['W']);

		$basicArray = array
	(
	    "courseObj" => Array
        	(
	            "formData" => Array
        	    (
                    "courseId" => 0,
                    "course_type" => 1,
                    "isDisabled" => "",
                    "packType" => 7,
                    "clientId" => "",
                    "subscriptionId" => 0,
                    "hierarchyForm" => Array
                        (
                            "parent_course_hierarchy" => $parent_entity_name,
                            "primary_course_hierarchy" => $parent_entity_name,
                            "primary_course_hierarchy_name" => $parentName,
                            "parent_course_hierarchy_name" => $parentName,
                        ),

                    "scheduleForm" => Array
                        (
                            "education_type" => $educationTypeStr,
                            "delivery_method" => $deliveryMethodStr
                        ),

                    "courseBasicInfoForm" => Array
                        (
                            "course_name" => $courseName,
                            "duration_value" => $durationValue,
                            "duration_unit" => $durationUnit,
                            "duration_disclaimer" => "",
                            "instruction_medium" => "",
                            "difficulty_level" => "",
                            "affiliated_university_scope" => $affiliatedUniversityType,
                            "affiliated_university_id" => $affiliatedUniversityId,
                            "affiliated_university_name" => $affiliatedUniversityName,
                            "affiliated_university_year" => ""
                        ),

                    "courseTypeForm" => Array
                        (
                            "course_variant" => $variantStr,
                            "mapping_info" => Array
                                (
                                    "0" => Array
                                        (
                                            "credential" => $entryCredentialStr,
                                            "course_level" => $entryLevelStr,
                                            "hierarchyMapping" => Array
                                                (
                                                    "0" => Array
                                                        (
                                                            "streamId" => $primaryStream,
                                                            "substreamId" => $primarySubStream,
                                                            "specializationId" => $primarySpecialization,
                                                            "is_primary" => 1
                                                        )

                                                ),

                                            "courseMapping" => $primaryBaseCourseId,
                                            "type" => "entry"
                                        )

                                ),

                            "course_tags" => Array
                                (
                                    "is_executive" => "",
                                    "is_lateral" => ""
                                )

                        ),

                    "coursePartnerForm" => Array
                        (
                            "course_partner_institute_flag" => 0
                        ),

                    "courseFeesForm" => Array
                        (
                            "batch" => "",
                            "fees_unit" => 1,
                            "total_fees_period" => "year",
                            "total_fees" => Array
                                (
                                    "0" => Array
                                        (
                                            "general" => "",
                                            "obc" => "",
                                            "sc" => "",
                                            "st" => "",
                                            "ph" => "",
                                        )

                                ),

                            "total_fees_total" => Array
                                (
                                    "general" => "",
                                    "obc" => "",
                                    "sc" => "",
                                    "st" => "",
                                    "ph" => "",
                                ),

                            "total_fees_one_time_payment" => Array
                                (
                                    "general" => "",
                                    "obc" => "",
                                    "sc" => "",
                                    "st" => "",
                                    "ph" => "",
                                ),

                            "hostel_fees" => Array
                                (
                                    "general" => "",
                                    "obc" => "",
                                    "sc" => "",
                                    "st" => "",
                                    "ph" => "",
                                ),

                            "total_fees_includes" => Array
                                (
                                    "Hostel" => "",
                                    "Tuition" => "",
                                    "Admission" => "",
                                    "Library" => "",
                                    "Others" => "",
                                ),

                            "other_info" => "",
                            "fees_disclaimer" => "",
                        ),

                    "courseEligibilityForm" => Array
                        (
                            "10th_details" => Array
                                (
                                    "score_type" => "percentage",
                                    "category_wise_cutoff" => Array
                                        (
                                            "outof" => 100
                                        ),

                                    "description" => "",
                                ),

                            "12th_details" => Array
                                (
                                    "score_type" => "percentage",
                                    "category_wise_cutoff" => Array
                                        (
                                            "outof" => 100
                                        ),

                                    "description" => "",
                                ),

                            "postgraduation_details" => Array
                                (
                                    "score_type" => "percentage",
                                    "category_wise_cutoff" => Array
                                        (
                                            "outof" => 100
                                        ),

                                    "description" => "",
                                    "entityMapping" => Array
                                        (
                                            "0" => Array
                                                (
                                                    "base_course" => "",
                                                    "specialization" => 0
                                                )

                                        )

                                ),

                            "graduation_details" => Array
                                (
                                    "score_type" => "percentage",
                                    "category_wise_cutoff" => Array
                                        (
                                            "outof" => 100
                                        ),

                                    "description" => "",
                                    "entityMapping" => Array
                                        (
                                            "0" => Array
                                                (
                                                    "base_course" => "",
                                                    "specialization" => 0
                                                )

                                        )

                                ),

                            "exams_accepted" => Array
                                (
                                    "0" => Array
                                        (
                                            "exam_name" => "",
                                            "exam_unit" => "",
                                            "custom_exam" => "",
                                            "general" => "",
                                            "obc" => "",
                                            "sc" => "",
                                            "st" => "",
                                            "ph" => "",
                                            "outof" => "",
                                        )

                                ),

                            "subjects" => "",
                            "other_subjects" => Array
                                (
                                    "0" => "",
                                ),

                            "work-ex_min" => "",
                            "work-ex_max" => "",
                            "work-ex_unit" => "months",
                            "age_min" => "",
                            "age_max" => "",
                            "international_description" => "",
                            "description" => "",
                            "batch" => "",
                        ),

                    "courseAdmissionProcess" => Array
                        (
                            "admission_process" => Array
                                (
                                    "0" => Array
                                        (
                                            "admission_name" => "",
                                            "admission_description" => "",
                                        )

                                )

                        ),

                    "courseSeats" => Array
                        (
                            "total_seats" => $totalSeats,
                            "seats_by_category" => Array
                                (
                                    "general" => "",
                                    "obc" => "",
                                    "sc" => "",
                                    "st" => "",
                                    "ph" => "",
                                ),

                            "seats_by_entrance_exam" => Array
                                (
                                ),

                            "seats_by_domicile" => Array
                                (
                                    "home_state" => "",
                                    "related_state" => "",
                                    "related_state_list" => "",
                                    "others_state" => "",
                                )

                        ),

                    "courseBatchProfile" => Array
                        (
                        ),

                    "courseNotableAlumni" => Array
                        (
                        ),

                    "courseStudentExchange" => Array
                        (
                        ),

                    "courseExamCutOff" => Array
                        (
                            "0" => Array
                                (
                                    "exam_id" => "",
                                    "exam_out_of" => "",
                                    "exam_unit" => "",
                                    "exam_year" => "",
                                    "related_states" => "",
                                    "round_applicable" => 0,
                                    "cutOffData" => Array
                                        (
                                            "0" => Array
                                                (
                                                    "round_table_arr" => Array
                                                        (
                                                            "0" => Array
                                                                (
                                                                    "type" => "all_india",
                                                                ),

                                                            "1" => Array
                                                                (
                                                                    "type" => "home_state"
                                                                ),

                                                            "2" => Array
                                                                (
                                                                    "type" => "other_state",
                                                                ),

                                                            "3" => Array
                                                                (
                                                                    "type" => "related_states",
                                                                )

                                                        )

                                                )

                                        )

                                )

                        ),

                    "course12thCutOff" => Array
                        (
                            "0" => Array
                                (
                                    "type" => "cutOff12th",
                                    "general" => "",
                                    "obc" => "",
                                    "sc" => "",
                                    "st" => "",
                                    "ph" => "",
                                ),

                            "1" => Array
                                (
                                    "type" => "science",
                                    "general" => "",
                                    "obc" => "",
                                    "sc" => "",
                                    "st" => "",
                                    "ph" => "",
                                ),

                            "2" => Array
                                (
                                    "type" => "commerce",
                                    "general" => "",
                                    "obc" => "",
                                    "sc" => "",
                                    "st" => "",
                                    "ph" => "",
                                ),

                            "3" => Array
                                (
                                    "type" => "humanities",
                                    "general" => "",
                                    "obc" => "",
                                    "sc" => "",
                                    "st" => "",
                                    "ph" => "",
                                ),

                            "4" => Array
                                (
                                    "type" => "pcm",
                                    "general" => "",
                                    "obc" => "",
                                    "sc" => "",
                                    "st" => "",
                                    "ph" => "",
                                ),

                            "5" => Array
                                (
                                    "type" => "pmbbt",
                                    "general" => "",
                                    "obc" => "",
                                    "sc" => "",
                                    "st" => "",
                                    "ph" => "",
                                )

                        ),

                    "courseUsp" => Array
                        (
                            "usp_list" => Array
                                (
                                    "0" => Array
                                        (
                                            "usp" => "",
                                        )

                                )

                        ),

                    "coursePlacements" => Array
                        (
                            "batch" => "",
                            "course" => "",
                            "batch_percentage" => "",
                            "batch_unit" => 1,
                            "batch_min_salary" => "",
                            "batch_median_salary" => "",
                            "batch_average_salary" => "",
                            "batch_max_salary" => "",
                            "international_offers" => "",
                            "max_salary" => "",
                            "max_salary_unit" => 1,
                            "report_url" => "",
                            "recruitingCompanies" => Array
                                (
                                )

                        ),

                    "courseInternship" => Array
                        (
                            "intern_batch" => "",
                            "intern_batch_unit" => 1,
                            "intern_min_stipend" => "",
                            "intern_median_stipend" => "",
                            "intern_average_stipend" => "",
                            "intern_max_stipend" => "",
                            "report_url" => "",
                        ),

                    "courseBrochure" => Array
                        (
                            "brochure_url" => "",
                            "brochure_year" => $year,
                            "brochure_size" => 0
                        ),

                    "courseStructureForm" => Array
                        (
                            "group_by" => "program",
                            "group_courses" => Array
                                (
                                    "0" => Array
                                        (
                                            "0" => "",
                                            "1" => "",
                                            "2" => "",
                                            "3" => "",
                                            "4" => "",
                                        )

                                )

                        ),

                    "importantDatesForm" => Array
                        (
                            "events" => Array
                                (
                                    "0" => Array
                                        (
                                            "start" => Array
                                                (
                                                    "date" => "",
                                                    "month" => "",
                                                    "year" => "",
                                                    "type" => "start",
                                                    "label" => "Application Start Date"
                                                ),

                                            "end" => Array
                                                (
                                                    "date" => "",
                                                    "month" => "",
                                                    "year" => "",
                                                    "type" => "end",
                                                    "label" =>  ""
                                                )

                                        ),

                                    "1" => Array
                                        (
                                            "start" => Array
                                                (
                                                    "date" => "",
                                                    "month" => "",
                                                    "year" => "",
                                                    "type" => "start",
                                                    "label" => "Application Submit Date"
                                                ),

                                            "end" => Array
                                                (
                                                    "date" => "",
                                                    "month" => "",
                                                    "year" => "",
                                                    "type" => "end",
                                                    "label" =>  "",
                                                )

                                        ),

                                    "2" => Array
                                        (
                                            "start" => Array
                                                (
                                                    "date" => "",
                                                    "month" => "",
                                                    "year" => "",
                                                    "type" => "start",
                                                    "label" => "Course Commencement Date"
                                                ),

                                            "end" => Array
                                                (
                                                    "date" => "",
                                                    "month" => "",
                                                    "year" => "",
                                                    "type" => "end",
                                                    "label" => "",
                                                )

                                        ),

                                    "3" => Array
                                        (
                                            "start" => Array
                                                (
                                                    "date" => "",
                                                    "month" => "",
                                                    "year" => "",
                                                    "type" => "start",
                                                    "label" => "Results Date"
                                                ),

                                            "end" => Array
                                                (
                                                    "date" => "",
                                                    "month" => "",
                                                    "year" => "",
                                                    "type" => "end",
                                                    "label" => "",
                                                )

                                        )

                                )

                        ),

                    "courseLocations" => Array
                        (
                            "locations" => Array
                                (
                                    "0" => Array
                                        (
                                            "locationId" => $locationId,
                                            "contact_details" => Array
                                                (
                                                    "locationAddress" => "",
                                                    "locationWebsite" => "",
                                                    "locationCoordinatesLat" => "",
                                                    "locationCoordinatesLong" => "",
                                                    "locationAdmissionContactNumber" => "",
                                                    "locationGenericContactNumber" => "",
                                                    "locationAdmissionEmail" => "",
                                                    "locationGenericEmail" => "",
                                                ),

                                            "fees" => Array
                                                (
                                                ),

                                            "fees_disclaimer" => "",
                                        )

                                ),

                            "locationsMain" => $locationId
                        ),

                    "courseMedia" => Array
                        (
                            "mediaCount" => ""
                        ),

                    "courseSeo" => Array
                        (
                            "title" => "",
                            "description" => ""
                        ),

                    "courseComments" => Array
                        (
                            "comment" => $comment
                        )

                ),

	        "saveAs" => "live"
        	)

		);

		if( strtolower($row['B']) == 'double' ){
			$exitCredentialStr = $this->credential[strtolower($row['N'])];
			$exitLevelStr = $this->courseLevel[strtolower($row['O'])];
			$exitStream = $row['P'];
			$exitSubStream = ($row['Q']!="" && $row['Q']!="0")?$row['Q']:"none";
			$exitSpecialization = ($row['R']!="" && $row['R']!="0")?$row['R']:"none";
			$exitBaseCourseId = ($row['S']!="")?$row['S']:"0";

			$exitArray = array(
                                            "credential" => $exitCredentialStr,
                                            "course_level" => $exitLevelStr ,
                                            "hierarchyMapping" => Array
                                                (
                                                    "0" => Array
                                                        (
                                                            "streamId" => $exitStream ,
                                                            "substreamId" => $exitSubStream ,
                                                            "specializationId" => $exitSpecialization ,
                                                        )

                                                ),

                                            "courseMapping" => $exitBaseCourseId ,
                                            "type" => "exit"
			);
			
			$basicArray['courseObj']['formData']['courseTypeForm']['mapping_info']['1'] = $exitArray;
		}

        if($recognitionStr != ""){
                $basicArray['courseObj']['formData']['courseBasicInfoForm']['recognition'] = array();
                $basicArray['courseObj']['formData']['courseBasicInfoForm']['recognition'][] = $recognitionStr;
        }

		/*
		//Convert Array to JSON
		$string = json_encode($basicArray);

		$returnVal = Modules::run("nationalCourse/CoursePosting/saveCourse", $string);
		$return = json_decode($returnVal, true);
		if($return['data']['status'] == 'success'){
			echo "<br/>".$return['data']['message'];
		}
		else{
			echo "<br/>Course with Name = $courseName, could not be created due to some error.";
			exit;
		}
		*/

                $data = $basicArray['courseObj'];
                $data['userId'] = '11';
                $courseIdReturned = $this->coursePostingLib->formatCourseData($data);

                if(empty($courseIdReturned)){
			error_log("Course could not be created with CourseName = $courseName");
			exit;
                }
                else{
			error_log("Course created with Id = $courseIdReturned.");
			Modules::run("/nationalCourse/CoursePosting/invalidateAndRebuildListingsCache", array($courseIdReturned));
                }

	}

        public function listingCourseUpdationValidate($file = ""){

            //Define the file location
	    if($file == ""){
	        $fileName = "/var/www/html/shiksha/public/enterpriseDoc/Combined.xlsx";
	    }
	    else{
	        $fileName = "/var/www/html/shiksha/public/enterpriseDoc/$file.xlsx";
	    }


            $this->load->library('common/reader');
            $this->load->library('common/PHPExcel/IOFactory');
            ini_set('memory_limit','2048M');

            //Load the File
            $inputFileName = $fileName;
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);

            /**  Load $inputFileName to a PHPExcel Object  **/
            $objPHPExcel = $objReader->load($inputFileName);

            $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
            $highestRow = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();

            $headingsArray = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);
            $headingsArray = $headingsArray[1];

            for ($row = 2; $row <= $highestRow; ++$row) {
		echo "<br/>Row: ".$row."</br>";
                $dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true);
                if(isset($dataRow[$row]) && is_array($dataRow[$row]) && count($dataRow[$row])>1){
			$response = $this->validateCourseUpdationData($dataRow[$row]);
                }
            }

            //error_log("Total Data Points Updated = ".$this->totalDataPointsUpdated);
	    if($this->library->issueFound){
		echo "<br/><br/>Issues Found !!!!";
	    }
	    else{
		echo "<br/><br/>No Issues Found. Please proceed with Upload !!!!";
	    }
        }

        public function validateCourseUpdationData($data){

            $this->library->checkMandatory($data['E'],'CourseNameDecider');
            $this->library->checkMandatory($data['G'],'baseCourseIdDecider');
            $this->library->checkMandatory($data['I'],'durationDecider');
            $this->library->checkMandatory($data['L'],'approvingDecider');
            $this->library->checkMandatory($data['N'],'affiliatingDecider');
            $this->library->checkMandatory($data['Q'],'placementDecider');
            $this->library->checkMandatory($data['X'],'seatsDecider');
            $this->library->checkMandatory($data['Z'],'feesDecider');
            $this->library->checkMandatory($data['AE'],'eligibilityDecider');

		if($data['C'] != 'NA' && $data['C'] != 'ALL' && $data['C'] != '' && $data['C'] > 0){
			$this->library->checkMandatory($data['C'],'courseId');
            $this->library->checkCourseId($data['C']);
        }
		else{
			$instId = $data['B'];
			$baseCourseId = $data['E'];
			$this->library->checkMandatory($instId,'parentId');
			$this->library->checkInstituteId($instId);
			$this->library->checkBaseCourse($data['H'], 'baseCourseId');
		}

		if(strtoupper($data['E']) == 'Y'){
			$this->library->checkMandatory($data['F'],'courseName');
			$this->library->checkValidations($data['F'],'courseName');
		}

		if(strtoupper($data['G']) == 'Y'){
			$this->library->checkMandatory($data['H'],'baseCourseId');
			$this->library->checkBaseCourse($data['H'], 'baseCourseId');
		}

		if(strtoupper($data['I']) == 'Y'){
			$this->library->checkMandatory($data['J'],'duration');
			$this->library->checkValidations($data['J'],'duration');

			$this->library->checkMandatory($data['K'],'durationUnit');
			$this->library->checkEnumValue($data['K'],'durationUnit');
		}

		if(strtoupper($data['L']) == 'Y'){
			$approvingBodies = explode(',',$data['M']);
			foreach ($approvingBodies as $approve){
				$this->library->checkEnumValue($approve,'approvingBodies');
			}
		}

		if(strtoupper($data['N']) == 'Y'){
			$this->library->checkUniversityId($data['O']);
			$this->library->checkEnumValue($data['P'],'affiliatingBody');
		}

		if(strtoupper($data['Q']) == 'Y'){
			$this->library->checkEnumValue($data['R'], 'batchYear');
			$this->library->validateInteger($data['S'], 'minSalary');
			$this->library->validateInteger($data['T'], 'medianSalary');
			$this->library->validateInteger($data['U'], 'avgSalary');
			$this->library->validateInteger($data['V'], 'maxSalary');
			$this->library->checkSalary($data['S'],$data['T'],$data['V'],'Median salary');
			$this->library->checkSalary($data['S'],$data['U'],$data['V'],'Average salary');
			$this->library->checkBatch($data['W']);
		}

		if(strtoupper($data['X']) == 'Y'){
			$this->library->checkValidations($data['Y'],'totalSeats');
		}

        }
	
        public function listingCourseUpdationBulkUpload($file = ""){

            //Define the file location
	    if($file == ""){
	        $fileName = "/var/www/html/shiksha/public/enterpriseDoc/Combined.xlsx";
	    }
	    else{
	        $fileName = "/var/www/html/shiksha/public/enterpriseDoc/$file.xlsx";
	    }


            $this->load->library('common/reader');
            $this->load->library('common/PHPExcel/IOFactory');
            ini_set('memory_limit','2048M');

            //Load the File
            $inputFileName = $fileName;
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);

            /**  Load $inputFileName to a PHPExcel Object  **/
            $objPHPExcel = $objReader->load($inputFileName);

            $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
            $highestRow = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();

            $headingsArray = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);
            $headingsArray = $headingsArray[1];

            for ($row = 2; $row <= $highestRow; ++$row) {
                $dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true);
                if(isset($dataRow[$row]) && is_array($dataRow[$row]) && count($dataRow[$row])>1){
			$response = $this->updateCourseData($dataRow[$row]);
                }
            }

            error_log("Total Data Points Updated = ".$this->totalDataPointsUpdated);
            error_log("Total Courses Updated = ".$this->totalCoursesUpdated);
			
        }

	public function updateCourseData($data){
		//1. Check if we have the Course Id / Inst Id + Base CourseId
		if($data['C'] != 'NA' && $data['C'] != 'ALL' && $data['C'] != '' && $data['C'] > 0){
			$this->updateCourseDataInDB($data['C'],$data);
		}
		else{
			$instId = $data['B'];
			$baseCourseId = $data['D'];
			if($instId > 0){
			    $this->bulkmodel = $this->load->model('common/listingbulkupdatemodel');
				$preFetchedCourseIds = $this->bulkmodel->getAllDirectCourses($instId, $baseCourseId);
				foreach ($preFetchedCourseIds as $course){
					$this->updateCourseDataInDB($course['courseId'],$data);
				}
			}
		}
		
	}

	public function updateCourseDataInDB($courseId,$data){
		$dataUpdated = 0;

		//First, get the Course Data
		$courseData = $this->coursePostingLib->getCourseData($courseId);

		//Now, check for each section of Data
		if(strtoupper($data['E']) == 'Y'){	//Course Name
			$courseData['courseBasicInfoForm']['course_name'] = $data['F'];
			$dataUpdated++;
		}

		if(strtoupper($data['G']) == 'Y'){	//Base Course Id
			$courseData['courseTypeForm']['mapping_info'][0]['courseMapping'] = $data['H'];
			$dataUpdated++;
		}

		if(strtoupper($data['I']) == 'Y'){	//Duration
			$courseData['courseBasicInfoForm']['duration_value'] = $data['J'];
			$courseData['courseBasicInfoForm']['duration_unit'] = strtolower($data['K']);
			$dataUpdated++;
		}

		if(strtoupper($data['L']) == 'Y'){	//Approving Body OR Recognition
			$courseData['courseBasicInfoForm']['recognition'] = array();
			$approvingBodies = explode(',',$data['M']);
			foreach ($approvingBodies as $approve){
				$courseData['courseBasicInfoForm']['recognition'][] = $this->recognition[strtoupper($approve)];
			}
			$dataUpdated++;
		}

		if(strtoupper($data['N']) == 'Y'){	//Affiliating university
			$affiliatedUniversityId = strtolower($data['O']);
			$affiliatedUniversityName = "";
			if($affiliatedUniversityId > 0){
				$parentDetails = $this->model->getParentDetails($affiliatedUniversityId);
				$parentType = $parentDetails['type'];
				$affiliatedUniversityName = $parentDetails['name'];
			}
			$affiliatedUniversityType = strtolower($data['P']);

                        $courseData['courseBasicInfoForm']['affiliated_university_scope'] = $affiliatedUniversityType;
                        $courseData['courseBasicInfoForm']['affiliated_university_id'] = $affiliatedUniversityId;
                        $courseData['courseBasicInfoForm']['affiliated_university_name'] = $affiliatedUniversityName;
                        $courseData['courseBasicInfoForm']['affiliated_university_year'] = "";
			$dataUpdated++;
		}

		if(strtoupper($data['Q']) == 'Y'){	//Placements
			$courseData['coursePlacements']['batch'] = $data['R'];
			$courseData['coursePlacements']['batch_min_salary'] = $data['S'];
			$courseData['coursePlacements']['batch_median_salary'] = $data['T'];
			$courseData['coursePlacements']['batch_average_salary'] = $data['U'];
			$courseData['coursePlacements']['batch_max_salary'] = $data['V'];
			$courseData['coursePlacements']['batch_percentage'] = $data['W'];
			$courseData['coursePlacements']['batch_unit'] = '1';
			if(strtoupper($data['G']) == 'Y'){
				$courseData['coursePlacements']['course'] = 'baseCourse_'.$data['H'];
			}
			$dataUpdated++;
		}

		if(strtoupper($data['X']) == 'Y'){	//Seats
			$courseData['courseSeats']['total_seats'] = $data['Y'];
			$dataUpdated++;
		}

		if(strtoupper($data['Z']) == 'Y'){	//Fees
			$courseData['courseFeesForm']['total_fees_period'] = "year";
			$courseData['courseFeesForm']['batch'] = $data['AA'];
			$courseData['courseFeesForm']['total_fees_total']['general'] = $data['AB'];
			$courseData['courseFeesForm']['fees_unit'] = "1";
			$courseData['courseFeesForm']['other_info'] = trim($data['AC']);
			$courseData['courseFeesForm']['fees_disclaimer'] = (strtolower($data['AD']) == 'y')?true:false;
			$dataUpdated++;
		}

		if(strtoupper($data['AE']) == 'Y'){	//Eligibility
			$courseData['courseEligibilityForm']['batch'] = $data['AF'];

			$courseData['courseEligibilityForm']['12th_details']['category_wise_cutoff']['general'] = ($data['AH'])?$data['AH']:"";
			$courseData['courseEligibilityForm']['12th_details']['category_wise_cutoff']['obc'] = ($data['AI'])?$data['AI']:"";
			$courseData['courseEligibilityForm']['12th_details']['category_wise_cutoff']['sc'] = ($data['AJ'])?$data['AJ']:"";
			$courseData['courseEligibilityForm']['12th_details']['category_wise_cutoff']['st'] = ($data['AK'])?$data['AK']:"";
			$courseData['courseEligibilityForm']['12th_details']['category_wise_cutoff']['outof'] = "100";
			$courseData['courseEligibilityForm']['12th_details']['score_type'] = "percentage";

			$courseData['courseEligibilityForm']['graduation_details']['category_wise_cutoff']['general'] = ($data['AL'])?$data['AL']:"";
			$courseData['courseEligibilityForm']['graduation_details']['category_wise_cutoff']['obc'] = ($data['AM'])?$data['AM']:"";
			$courseData['courseEligibilityForm']['graduation_details']['category_wise_cutoff']['sc'] = ($data['AN'])?$data['AN']:"";
			$courseData['courseEligibilityForm']['graduation_details']['category_wise_cutoff']['st'] = ($data['AO'])?$data['AO']:"";
			$courseData['courseEligibilityForm']['graduation_details']['category_wise_cutoff']['outof'] = "100";
			$courseData['courseEligibilityForm']['graduation_details']['score_type'] = "percentage";

			$courseData['courseEligibilityForm']['exams_accepted'] = array();
			$examArray = explode(',',$data['AP']);
			foreach ($examArray as $examId){
				$courseData['courseEligibilityForm']['exams_accepted'][] = array('exam_name' => trim($examId));
			}

			$courseData['courseEligibilityForm']['description'] = trim($data['AQ']);
			$dataUpdated++;
		}

		//echo json_encode($courseData);

		//Now, Save this Course
		if($dataUpdated > 0){

			//Update Entries for Stream/SS/Specialization
			foreach ($courseData['courseTypeForm']['mapping_info'] as $key => $mapping_info){
				foreach ($mapping_info['hierarchyMapping'] as $key1 => $heirarchy){
					$courseData['courseTypeForm']['mapping_info'][$key]['hierarchyMapping'][$key1]['streamId'] = $heirarchy['stream_id'];
					$courseData['courseTypeForm']['mapping_info'][$key]['hierarchyMapping'][$key1]['substreamId'] = ($heirarchy['substream_id']=='')?'none':$heirarchy['substream_id'];
					$courseData['courseTypeForm']['mapping_info'][$key]['hierarchyMapping'][$key1]['specializationId'] = ($heirarchy['specialization_id']=='')?'none':$heirarchy['specialization_id'];
				}
			}

			//Update Media Array
			//First, get the count of Institute Media
			$instituteArr = explode('_',$courseData['hierarchyForm']['primary_course_hierarchy']);
			$instituteId = $instituteArr[1];
			$this->institutedetailsmodel = $this->load->model('nationalInstitute/institutedetailsmodel');
			$dataMedia = $this->institutedetailsmodel->getInstituteMedia($instituteId);
			$mediaCount = count($dataMedia);

			if(isset($courseData['courseMedia']) && count($courseData['courseMedia']) > 0){
				$mediaArray = $courseData['courseMedia'];
				unset($courseData['courseMedia']);
				foreach ($mediaArray as $media){
					$courseData['courseMedia'][$media] = 1;
				}
				$courseData['courseMedia']['mediaCount'] = $mediaCount;
			}

			$courseData['courseComments']['comment'] = "Course Updated through Bulk Upload.";
			$courseData['course_type'] = 1;
        	        $dataU['formData'] = $courseData;
                	$dataU['userId'] = '11';
			$dataU['saveAs'] = 'live';
			        $dataU['isScript'] = 'true';

        	        $courseIdReturned = $this->coursePostingLib->formatCourseData($dataU);

			//Remove Course Cache
			$this->coursecache = $this->load->library('nationalCourse/cache/NationalCourseCache');
                	$this->coursecache->removeCoursesCache(array($courseId));

	                // purge html cache
        	        $shikshamodel = $this->load->model("common/shikshamodel");
                	$arr = array("cache_type" => "htmlpage", "entity_type" => "course", "entity_id" => $courseId, "cache_key_identifier" => "");
	                $shikshamodel->insertCachePurgingQueue($arr);

			//Basis return value, show Success/Fail message
        	        if(empty($courseIdReturned)){
				echo "<br>Data could not be updated for CourseId = $courseId.";
				error_log("Data could not be updated for CourseId = $courseId.");
	                }
        	        else{
				echo "<br>Data updated for CourseId = $courseId.";
				error_log("Data updated for CourseId = $courseId.");
				if($dataUpdated > 0){			
					$this->totalDataPointsUpdated += $dataUpdated;
					$this->totalCoursesUpdated++;
				}

				Modules::run("/nationalCourse/CoursePosting/invalidateAndRebuildListingsCache",array($courseId));
	                }
		}
	
	}

}