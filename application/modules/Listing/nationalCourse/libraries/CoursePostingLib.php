<?php

class CoursePostingLib{
	function __construct() {
		$this->CI =& get_instance();
		
		//load model
		$this->coursepostingmodel = $this->CI->load->model('nationalCourse/coursepostingmodel');
		$this->baseAttributeLib = $this->CI->load->library('listingBase/BaseAttributeLibrary');
		$this->CI->load->config('nationalCourse/courseConfig');
		$this->CI->load->builder('ListingBaseBuilder','listingBase');
		$builder    = new ListingBaseBuilder();
		$this->baseCourseRepo = $builder->getBaseCourseRepository();
		$this->institutePostingLib = $this->CI->load->library('nationalInstitute/InstitutePostingLib');
		$this->examLib = $this->CI->load->library('examPages/ExamMainLib');
	}

	function prepareDynamicAttribute(){
		$data = array();
		$dynamicAttributeList = array(
									'Education Type'           =>'education_type',
									'Schedule'                 =>'schedule',
									'Medium/Delivery Method'   =>'medium_delivery',
									'Time of Learning'         =>'time_learning',
									'Course Variant'           =>'course_variant',
									'Credential'               =>'credential',
									'Course Level'             =>'course_level',
									'Difficulty / Skill Level' =>'course_difficulty_level',
									'Course Recognition' 	   =>'course_recognition',
									'Medium of Instruction'    =>'medium_of_instruction',
 									);

		$courseAttrubuteList = $this->baseAttributeLib->getValuesForAttributeByName(array_keys($dynamicAttributeList));
		foreach ($courseAttrubuteList as $attributeName => $attribute) {						
			$temp = array();
			foreach ($attribute as $key => $value) {
				$data[$dynamicAttributeList[$attributeName]][] = array('value'=>"".$key."",'label'=>$value);
			}
			if(in_array($attributeName, array('Medium of Instruction','Course Recognition'))){
				usort($data[$dynamicAttributeList[$attributeName]], array('CoursePostingLib','compareByName'));
			}			 
		}

		$categories = $this->CI->config->item('categories');
		asort($categories['general']);
		asort($categories['addMore']);
		foreach ($categories as $category=>$value) {
			foreach ($value as $key => $label) {
				$data['categories'][] = array('value'=>$key,'label'=>$label,'type'=>$category);
			}	
		}

		$durationOptions = $this->CI->config->item('durationOptions');
		foreach ($durationOptions as $duration=>$value) {
				$data['duration'][] = array('value'=>$duration,'label'=>$value);
		}


		$unitList = $this->coursepostingmodel->getCurrency();
		foreach ($unitList as $unit=>$value) {
				$data['fees_currencies'][] = array('value'=>$value['id'],'label'=>$value['currency_name']." (".$value['currency_code'].")");
		}

		$feesDurationOptions = $this->CI->config->item('feesDurationOptions');
		foreach ($feesDurationOptions as $feesDuration=>$value) {
				$data['feesDurationOptions'][] = array('value'=>$feesDuration,'label'=>$value);
		}

		$structureOptions = $this->CI->config->item('courseStructureGroupByOptions');
		foreach ($structureOptions as $key=>$value) {
				$data['structureOptions'][] = array('value'=>$key,'label'=>$value);
		}

		$scoreTypes = $this->CI->config->item('scoreTypes');
		foreach ($scoreTypes as $key=>$value) {
				$data['score_types'][] = array('value'=>$key,'label'=>$value);
		}

		$stage_names = $this->CI->config->item('stage_names');
		foreach ($stage_names as $key=>$value) {
				$data['stage_names'][] = array('value'=>$key,'label'=>$value);
		}

		$attributeDependencies = $this->coursepostingmodel->getAllAttributesHavingDependencies();
		$attributeOrder = $this->CI->config->item('attributeValueOrder');
		$arr = array_diff_key($attributeDependencies, $attributeOrder);
		foreach ($attributeOrder as $key => $value) {
			$arr[$key] = $attributeDependencies[$key];
		}

		$data['attribute_dependencies'] = $arr;
		
		$data['base_course_list']       = $this->getAllBaseCourses();
		
		$data['eligibility_subjects']   = $this->CI->config->item('eligibleSubjects');

		$data['exam_list'] = array();
		$exams = $this->examLib->getExamsList();
		foreach ($exams as $id => $value) {
			$data['exam_list'][] = array('value'=>$id,'label' => $value);
		}

		$examCutOffUnit = $this->CI->config->item('examCutOffUnit');
		foreach ($examCutOffUnit as $key=>$value) {
				$data['exam_cutoff_unit'][] = array('value'=>$key,'label'=>$value);
		}
		
		$course12thCutOffSubjects = $this->CI->config->item('course12thCutOffSubjects');
		foreach ($course12thCutOffSubjects as $key=>$value) {
				$data['course12thCutOffSubjects'][] = array('value'=>$key,'label'=>$value);
		}

		return $data;

	}

	function compareByName($a, $b) {
  		return strcmp($a["label"], $b["label"]);
	}

	/**
	 * [formatCourseData this function will parse and format data and store it into DB]
	 * @date   2016-09-29
	 * @param  [type]     $formatData [description]
	 * @return [type]                 [description]
	 */
	function formatCourseData($formatData) {

		$courseData = array();
		
		$courseData['sectionsChanged']   = array();
		$courseData['sectionsExtraData'] = array();
		if(!empty($formatData['sectionsChanged']['sections'])){
			$courseData['sectionsChanged']   = $formatData['sectionsChanged']['sections'];
			$courseData['sectionsExtraData'] = $formatData['sectionsChanged']['extraData'];
		}

		$data = $formatData['formData'];

		$courseData['saveAs'] = $formatData['saveAs'];
		$this->trimObjRecursively($data);

		if(empty($data['courseId'])){
			$courseData['mode'] = 'add';
		}
		else{
			$courseData['mode'] = 'edit';
			$courseData['courseId'] = $data['courseId'];
		}

		//userId 
		$courseData['userId'] = $formatData['userId'];
		$courseData['isDisabled'] = $data['isDisabled'];
		$courseData['clientId'] = $data['clientId'];
		$courseData['packType'] = $data['packType'];
		$courseData['subscriptionId'] = $data['subscriptionId'];

		//CourseInfo
		$courseInfo                               = array();
		$courseInfo['name']                       = trim($data['courseBasicInfoForm']['course_name']);
		$parentCourseHierarchy                    = explode("_", $data['hierarchyForm']['parent_course_hierarchy']);
		$courseInfo['parent_id']                  = $parentCourseHierarchy[1];
		$courseInfo['parent_type']                = $parentCourseHierarchy[0];
		$primaryCourseHierarchy                   = explode("_", $data['hierarchyForm']['primary_course_hierarchy']);		
		$courseInfo['primary_id']                 = $primaryCourseHierarchy[1];
		$courseInfo['primary_type']               = $primaryCourseHierarchy[0];
		$courseInfo['course_order']               = '';
		$courseInfo['course_variant']             = $data['courseTypeForm']['course_variant'];
		// $courseInfo['course_variant']             = '3';
		$courseInfo['executive']                  = $data['courseTypeForm']['course_tags']['is_executive'];
		$courseInfo['lateral']                    = $data['courseTypeForm']['course_tags']['is_lateral'];
		$courseInfo['twinning']                   = $data['courseTypeForm']['course_tags']['is_twinning'];
		$courseInfo['dual']                       = $data['courseTypeForm']['course_tags']['is_dual'];
		$courseInfo['integrated']                 = $data['courseTypeForm']['course_tags']['is_integrated'];
		$courseInfo['course_type']                = $data['course_type'];
		$courseInfo['education_type']             = $data['scheduleForm']['education_type'];
		$courseInfo['place_of_learning']          = 0;
		$courseInfo['delivery_method']            = $data['scheduleForm']['delivery_method'];
		$courseInfo['time_of_learning']           = $data['scheduleForm']['time_of_learning'];
		$courseInfo['total_seats']           	  = $data['courseSeats']['total_seats'];
		$courseInfo['duration_unit']              = 'hours';
		if(!empty($data['courseBasicInfoForm']['affiliated_university_name'])){
			$courseInfo['affiliated_university_scope'] = $data['courseBasicInfoForm']['affiliated_university_scope'];
			$courseInfo['affiliated_university_id']    = !empty($data['courseBasicInfoForm']['affiliated_university_id']) ? $data['courseBasicInfoForm']['affiliated_university_id'] : NULL;
			$courseInfo['affiliated_university_name']  = $data['courseBasicInfoForm']['affiliated_university_name'];
			$courseInfo['affiliated_university_year']  = $data['courseBasicInfoForm']['affiliated_university_year'] ? $data['courseBasicInfoForm']['affiliated_university_year'] : NULL;
		}
		
		$courseInfo['difficulty_level']            = $data['courseBasicInfoForm']['difficulty_level'];
		
		if($data['courseBasicInfoForm']['duration_value']){
			$courseInfo['duration_value']      = trim($data['courseBasicInfoForm']['duration_value']);
			$courseInfo['duration_unit']       = $data['courseBasicInfoForm']['duration_unit'];			
			$courseInfo['duration_disclaimer'] = $data['courseBasicInfoForm']['duration_disclaimer'];			
		}
		
		$courseData['courseInfo'] 				  = $courseInfo;

		$courseAdditionalInfo                     = array();
		if(is_array($data['scheduleForm']['schedule'])){
			foreach ($data['scheduleForm']['schedule'] as $key => $value) {
				$temp = array();
				$temp['info_type']        		   = 'schedule';	
				$temp['attribute_value_id']        = $value;	
				$courseAdditionalInfo[] 		   = $temp;
			}
		}

		if(is_array($data['courseBasicInfoForm']['recognition'])){
			foreach ($data['courseBasicInfoForm']['recognition'] as $key => $value) {
				$temp = array();
				$temp['info_type']        		   = 'recognition';	
				$temp['attribute_value_id']        = $value;	
				$courseAdditionalInfo[] 		   = $temp;
			}
		}

		if(is_array($data['courseBasicInfoForm']['instruction_medium'])){
			foreach ($data['courseBasicInfoForm']['instruction_medium'] as $key => $value) {
				$temp = array();
				$temp['info_type']        		   = 'instruction_medium';	
				$temp['attribute_value_id']        = $value;	
				$courseAdditionalInfo[] 		   = $temp;
			}
		}
		if(count($courseAdditionalInfo) > 0){
			$courseData['courseAdditionalInfo']  = $courseAdditionalInfo;				
		}

		$courseTypeInfo = array();
		$primaryHierarchy = array();
		foreach ($data['courseTypeForm']['mapping_info'] as $entry) {
			foreach ($entry['hierarchyMapping'] as $hierarchy) {
				if(!empty($hierarchy['streamId'])){
					$temp                      = array();
					$temp['type']              = $entry['type'];
					$temp['credential']        = $entry['credential'];
					$temp['course_level']      = $entry['course_level'];
					$temp['base_course']       = $entry['courseMapping'];
					$temp['stream_id']         = $hierarchy['streamId'];
					$temp['substream_id']      = $hierarchy['substreamId'];
					$temp['specialization_id'] = $hierarchy['specializationId'];
					$temp['primary_hierarchy'] = $hierarchy['is_primary'];
					if(!$temp['primary_hierarchy']){
						$primaryHierarchy[] = $temp;
					}
					$courseTypeInfo[]          = $temp;
				}
			}
		}
		$courseData['courseTypeInfo'] = $courseTypeInfo;

		$eligibility['scoreData'] = array();$eligibility['entityData'] = array();$eligibility['examScoreData'] = array();$eligibility['mainData'] = array();

		if(!empty($data['courseEligibilityForm']['batch'])){

			// $this->trimObjRecursively($data['courseEligibilityForm']);
			
			$sections = array('10th_details','12th_details','graduation_details','postgraduation_details');

			foreach ($sections as $section) {
				$sectionData = $data['courseEligibilityForm'][$section];
				switch ($section) {
					case '10th_details':
						$standard = 'X';
						break;
					case '12th_details':
						$standard = 'XII';
						break;
					case 'graduation_details':
						$standard = 'graduation';
						break;
					case 'postgraduation_details':
						$standard = 'postgraduation';
						break;
				}
				foreach ($sectionData['category_wise_cutoff'] as $name => $categoryValue) {
					if(!empty($categoryValue) && $name != 'outof'){
						$temp = array();

						$temp['standard']             = $standard;
						$temp['category']             = $name;
						$temp['value']                = $categoryValue;
						$temp['unit']                 = $sectionData['score_type'];
						$temp['max_value']            = $sectionData['category_wise_cutoff']['outof'];
						$temp['specific_requirement'] = NULL;
						$eligibility['scoreData'][]   = $temp;
					}
				}
				if(!empty($sectionData['description'])){
					$eligibility['scoreData'][] = array('standard'=>$standard,'category'=>NULL,'value'=>NULL,'unit'=>NULL,'max_value'=>NULL,'specific_requirement' => $sectionData['description']);
				}
				if(!empty($sectionData['entityMapping'])){
					foreach ($sectionData['entityMapping'] as $mapping) {
						$temp = array();
						if(!empty($mapping['base_course'])){
							$temp['base_course'] = $mapping['base_course'];
							$eligibility['entityData'][] = array('base_course' => $mapping['base_course'],'specialization' => $mapping['specialization'],'type'=>$standard);
						}
					}
				}
			}

			$examScoreData = $data['courseEligibilityForm']['exams_accepted'];
			$examIds = array();
			foreach ($examScoreData as $row) {
				if(!empty($row['exam_name']) && $row['exam_name']!='other'){
					$examIds[$row['exam_name']] = $row['exam_name'];
				}
			}
			$examNameMapping = $this->examLib->getExamDetailsByIds($examIds);
			foreach ($examScoreData as $row) {
				if(!empty($row['exam_name'])){
					$insertForExam = false;
					foreach ($row as $col=>$val) {
						if(!in_array($col,array('exam_name','exam_unit','outof','custom_exam'))){
							if(!empty($val)){
								$temp = array();
								if($row['exam_name'] == 'other'){
									$temp['exam_id']   = 0;
									$temp['exam_name'] = $row['custom_exam'];
								}
								else{
									$temp['exam_id']   = $row['exam_name'];
									$temp['exam_name'] = $examNameMapping[$row['exam_name']]['examName'];
								}
								$temp['unit']      = $row['exam_unit'];
								$temp['max_value'] = $row['outof'];
								$temp['category']  = $col;
								$temp['value']     = $val;
								$eligibility['examScoreData'][] = $temp;
								$insertForExam = true;
							}
						}
					}
					if(!$insertForExam){
						$temp = array();
						if($row['exam_name'] == 'other'){
							$temp['exam_id']   = 0;
							$temp['exam_name'] = $row['custom_exam'];
						}
						else{
							$temp['exam_id']   = $row['exam_name'];
							$temp['exam_name'] = $examNameMapping[$row['exam_name']]['examName'];
						}
						$temp['unit']      = empty($row['exam_unit']) ? NULL : $row['exam_unit'];
						$temp['max_value'] = NULL;
						$temp['category']  = NULL;
						$temp['value']     = NULL;
						$eligibility['examScoreData'][] = $temp;
					}
				}
			}
			$mainData = array();

			$subjects = empty($data['courseEligibilityForm']['subjects']) ? array() : $data['courseEligibilityForm']['subjects'];
			foreach ($data['courseEligibilityForm']['other_subjects'] as $subject) {
				if(!empty($subject)){
					$subjects[] = $subject;
				}
			}

			//don't save subjects if course level is selected after 10th
			if(!empty($subjects) && $data['courseTypeForm']['mapping_info'][0]['course_level'] != 13){
				$mainData['subjects'] = json_encode($subjects);
			}
			if(!empty($data['courseEligibilityForm']['work-ex_min'])){
				$mainData['work-ex_min'] = $data['courseEligibilityForm']['work-ex_min'];
				$mainData['work-ex_unit'] = $data['courseEligibilityForm']['work-ex_unit'];
			}
			if(!empty($data['courseEligibilityForm']['work-ex_max'])){
				$mainData['work-ex_max'] = $data['courseEligibilityForm']['work-ex_max'];
				$mainData['work-ex_unit'] = $data['courseEligibilityForm']['work-ex_unit'];
			}
			if(!empty($data['courseEligibilityForm']['age_min'])){
				$mainData['age_min'] = $data['courseEligibilityForm']['age_min'];
			}
			if(!empty($data['courseEligibilityForm']['age_max'])){
				$mainData['age_max'] = $data['courseEligibilityForm']['age_max'];
			}
			if(!empty($data['courseEligibilityForm']['international_description'])){
				$mainData['international_students_desc'] = $data['courseEligibilityForm']['international_description'];
			}
			if(!empty($data['courseEligibilityForm']['description'])){
				$mainData['description'] = $data['courseEligibilityForm']['description'];
			}

			if(empty($mainData)){
				if(empty($eligibility['scoreData']) && empty($eligibility['examScoreData']) && empty($eligibility['entityData'])){
					$eligibility['mainData'] = array();
				}
				else{
					$mainData['batch_year'] = $data['courseEligibilityForm']['batch'];
					$eligibility['mainData'] = $mainData;
				}
			}
			else{
				$mainData['batch_year'] = $data['courseEligibilityForm']['batch'];
				$eligibility['mainData'] = $mainData;
			}

		}
		$courseData['eligibility'] = $eligibility;

		if($data['coursePartnerForm']['course_partner_institute_flag'] == 1) {
			$courseData['partnerDetails']['partnerInstituteFormArr']	= $data['coursePartnerForm']['partnerInstituteFormArr'];
			$courseData['partnerDetails']['partnerInstituteFormArrExit']	= $data['coursePartnerForm']['partnerInstituteFormArrExit'];
		}

		//check for batch so that if the user unselects eligibility year dropdown then we don't want to save data
		if(!empty($data['courseExamCutOff']) && !empty($data['courseEligibilityForm']['batch'])) {
			$courseData['examsCutOff'] = $this->formatExamsCutOffData($data['courseExamCutOff']);
		}

		if(!empty($data['course12thCutOff'])) {
			$courseData['course12thCutOff'] = $this->format12thCutOffData($data['course12thCutOff']);
		}
		// _p($data);
		if(!empty($data['courseBrochure']) && !empty($data['courseBrochure']['brochure_url'])) {
			$courseData['courseBrochureForm'] = $data['courseBrochure'];
		}

		$courseFees = array();
		if(is_array($data['courseFeesForm'])){

			$courseFeesIncludes = array();
			$courseFeesIncludesFilter = '';
			foreach ($data['courseFeesForm']['total_fees_includes'] as $includes =>$value) {
				if($includes == 'OthersText'){
					$otherText = array();
					foreach ($value as $key => $val) {
						if($val['other_text'] != ''){
							$otherText[] = $val['other_text'];
						}
					}
					if(count($otherText) > 0){
						$courseFeesIncludes[] = 'Others';
						$courseFeesIncludes[] = "other_text--".implode("|", $otherText);
					} else {
						$data['courseFeesForm']['total_fees_includes']['Others'] = 0;
					}
				}else{
					if($value == '1' && $includes != 'Others'){
						$courseFeesIncludes[] = $includes;	
					}
				}
			}
			$courseFeesIncludes = array_map('trim',$courseFeesIncludes);
			$courseFeesIncludes = array_filter($courseFeesIncludes,'strlen');
			if(!empty($courseFeesIncludes)){
				$courseFeesIncludesFilter = implode(';', $courseFeesIncludes);
			}

			foreach (array('total_fees','tuition_fees') as $value) {
				foreach($data['courseFeesForm'][$value] as $key => $feesCategories) {
					switch ($value) {
						case 'total_fees':
							$type   = 'total';
							break;
						case 'tuition_fees':
							$type   = 'tuition';
							break;
					}

					$this->formatCourseFeesData($type,$feesCategories,$data['courseFeesForm'],$key,$courseFeesIncludesFilter,$data['courseFeesForm'][$value.'_period'],$courseFees);					 
				}
			}
			// _p($courseFees);
			foreach (array('total_fees_total','total_fees_one_time_payment','tuition_fees_total','tuition_fees_one_time_payment','hostel_fees') as $value) {
					switch ($value) {
						case 'total_fees_total':
							$period = 'overall';
							$type   = 'total';
							break;
						case 'total_fees_one_time_payment':
							$period = 'otp';
							$type   = 'total';
							break;
						case 'tuition_fees_total':
							$period = 'overall';
							$type   = 'tuition';
							$courseFeesIncludesFilter = '';
							break;
						case 'tuition_fees_one_time_payment':
							$period = 'otp';
							$type   = 'tuition';
							$courseFeesIncludesFilter = '';
							break;						
						case 'hostel_fees':
							$period = 'year';
							$type   = 'hostel';
							$courseFeesIncludesFilter = '';
							break;
					}

					$this->formatCourseFeesData($type,$data['courseFeesForm'][$value],$data['courseFeesForm'],0,$courseFeesIncludesFilter,$period,$courseFees);					 
			}

			//other info
			if(trim($data['courseFeesForm']['other_info'])){
				$courseFees[] = array(
					'listing_location_id'=>-1,
					'fees_disclaimer' => $data['courseFeesForm']['fees_disclaimer'],
					'batch_year' => $data['courseFeesForm']['batch'],
					'other_info'=>trim($data['courseFeesForm']['other_info']),
					'fees_value' => NULL,
					'fees_unit' => NULL,'fees_type' => NULL,'category'=>NULL,'period'=>NULL,'order'=>'','total_includes'=>''
				);
			}			
			
		}
		// _p($courseFees); die('ank');
		$courseData['courseFees'] = $courseFees;

		$courseAdmissionProcess = array();
		if(is_array($data['courseAdmissionProcess']['admission_process'])){
			foreach ($data['courseAdmissionProcess']['admission_process'] as $key => $value) {
				if(trim($value['admission_description'])){
					$temp = array();
					$temp['stage_order']        		   = $key+1;
					$temp['admission_name']        			   = $value['admission_name'];	
					$temp['admission_name_other'] = null;
					if($value['admission_name'] == 'Others'){
						$temp['admission_name_other'] = trim($value['admission_name_others']);
					}
					$temp['admission_description']          = trim($value['admission_description']);	
					$courseAdmissionProcess[] 		       = $temp;
				}
			}
		}

		if(count($courseAdmissionProcess) > 0){
			$courseData['courseAdmissionProcess']  = $courseAdmissionProcess;				
		}

		$courseUsp = array();
		if(is_array($data['courseUsp']['usp_list'])){
			foreach ($data['courseUsp']['usp_list'] as $key => $value) {
				if(trim($value['usp'])){
					$temp                = array();
					$temp['info_type']   = 'usp';	
					$temp['description'] = trim($value['usp']);	
					$courseUsp[]         = $temp;					
				}
			}
		}
		if(count($courseUsp) > 0){
			$courseData['courseUsp']  = $courseUsp;				
		}

		$coursePlacements = array();
		if(is_array($data['coursePlacements'])){
			if($data['coursePlacements']['batch']){
				$checkSalaryFlag = false;
				$coursePlacements['type']                          = 'placements';
				$coursePlacements['batch_year']                    = $data['coursePlacements']['batch'];
				$course = $data['coursePlacements']['course'];
				if(!empty($course)){
					$course = explode('_',$course);
					if($course[0] == 'clientCourse'){
						$value = NULL;
					}
					else{
						$value = $course[1];
					}
					$coursePlacements['course'] = $value;
					$coursePlacements['course_type'] = $course[0];
				}
				if(trim($data['coursePlacements']['batch_percentage'])){
					$coursePlacements['percentage_batch_placed']       = trim($data['coursePlacements']['batch_percentage']);
				}
				if(trim($data['coursePlacements']['batch_min_salary'])){
					$coursePlacements['min_salary']                    = trim($data['coursePlacements']['batch_min_salary']);
					$checkSalaryFlag = true;
				}
				if(trim($data['coursePlacements']['batch_median_salary'])){
					$coursePlacements['median_salary']                    = trim($data['coursePlacements']['batch_median_salary']);
					$checkSalaryFlag = true;
				}
				if(trim($data['coursePlacements']['batch_average_salary'])){
					$coursePlacements['avg_salary']                    = trim($data['coursePlacements']['batch_average_salary']);
					$checkSalaryFlag = true;
				}
				if(trim($data['coursePlacements']['batch_max_salary'])){
					$coursePlacements['max_salary']                    = trim($data['coursePlacements']['batch_max_salary']);
					$checkSalaryFlag = true;
				}
				if($checkSalaryFlag == true){
					$coursePlacements['salary_unit']                   = $data['coursePlacements']['batch_unit'];	
				}
				
				if(trim($data['coursePlacements']['international_offers'])){
					$coursePlacements['total_international_offers']    = trim($data['coursePlacements']['international_offers']);	
				}

				if(trim($data['coursePlacements']['max_salary'])){
					$coursePlacements['max_international_salary']    = trim($data['coursePlacements']['max_salary']);	
					$coursePlacements['max_international_salary_unit']    = trim($data['coursePlacements']['max_salary_unit']);	
				}
				if($data['coursePlacements']['report_url']){
					$coursePlacements['report_url']          = $data['coursePlacements']['report_url'];	
				}
				
			}
		}

		

		if(count($coursePlacements) > 2){
			$courseData['coursePlacements']  = $coursePlacements;				
		}

		$courseInternship = array();
		if(is_array($data['courseInternship'])){
			if(trim($data['courseInternship']['intern_batch'])){
				$courseInternship['type']        = 'internship';
				$courseInternship['batch_year']  = $data['courseInternship']['intern_batch'];
				$internStipendFlag = false;
				if(trim($data['courseInternship']['intern_min_stipend'])){
					$courseInternship['min_salary']  = trim($data['courseInternship']['intern_min_stipend']);
					$internStipendFlag				= true;
				}

				if(trim($data['courseInternship']['intern_median_stipend'])){
					$courseInternship['median_salary'] = trim($data['courseInternship']['intern_median_stipend']);
					$internStipendFlag				= true;
				}

				if(trim($data['courseInternship']['intern_average_stipend'])){
					$courseInternship['avg_salary']  = trim($data['courseInternship']['intern_average_stipend']);
					$internStipendFlag				= true;
				}

				if(trim($data['courseInternship']['intern_max_stipend'])){
					$courseInternship['max_salary']  = trim($data['courseInternship']['intern_max_stipend']);
					$internStipendFlag				= true;	
				}
					
				if($internStipendFlag == true){	
					$courseInternship['salary_unit'] = $data['courseInternship']['intern_batch_unit'];
				}

				if($data['courseInternship']['report_url']){
					$courseInternship['report_url']          = $data['courseInternship']['report_url'];	
				}
			}
		}

		if(count($courseInternship) > 2){
			$courseData['courseInternship']  = $courseInternship;				
		}

		$courseStructureInfo = array();
		// $this->trimObjRecursively($data['courseStructureForm']);
		foreach ($data['courseStructureForm']['group_courses'] as $index => $value) {
			foreach ($value as $val) {
				if(!empty($val)){
					$courseStructureInfo[] = array('period'=>$data['courseStructureForm']['group_by'],'period_value'=>$index+1,'courses_offered'=>$val);
				}
			}
		}
		$courseData['courseStructureInfo'] = $courseStructureInfo;

		$importantDates = array();
		foreach ($data['importantDatesForm']['events'] as $index => $val) {
			if(empty($val['start']['year']) || empty($val['start']['label'])){
				continue;
			}
			$temp = array();
			$temp['event_name']  = $val['start']['label'];
			$temp['start_date']  = empty($val['start']['date']) ? NULL : $val['start']['date'];
			$temp['start_month'] = empty($val['start']['month']) ? NULL : $val['start']['month'];
			$temp['start_year']  = empty($val['start']['year']) ? NULL : $val['start']['year'];
			$temp['end_date']    = empty($val['end']['date']) ? NULL : $val['end']['date'];
			$temp['end_month']   = empty($val['end']['month']) ? NULL : $val['end']['month'];
			$temp['end_year']    = empty($val['end']['year']) ? NULL : $val['end']['year'];
			$importantDates[] = $temp;
		}
		$courseData['importantDates'] = $importantDates;


		$seats = array();
		foreach ($data['courseSeats']['seats_by_category'] as $category => $value) {
			if(!empty($value)){
				$temp = array();
				$temp['breakup_by'] = 'category';
				$temp['category']   = $category;
				$temp['seats']      = $value;
				$seats[]   = $temp;
			}
		}

		foreach ($data['courseSeats']['seats_by_entrance_exam'] as $examId => $value) {
			if(!empty($value) && !empty($data['courseEligibilityForm']['batch'])){
				list($examId, $customExam) = explode('$$',$examId);
				$temp = array();
				$temp['breakup_by']       = 'exam';
				if($examId == 'Others'){
					$temp['exam_id'] = NULL;
				}
				else{
					$temp['exam_id']      = $examId;
				}
				$temp['seats']            = $value;
				$temp['custom_exam_name'] = $customExam;
				$seats[]                  = $temp;
			}
		}

		foreach ($data['courseSeats']['seats_by_domicile'] as $category => $value) {
			if(!empty($value) && $category!= 'related_state_list'){
				$temp               = array();
				$temp['breakup_by'] = 'domicile';
				$temp['category']   = $category;
				$temp['seats']      = $value;
				if($category == 'related_state' && $data['courseSeats']['seats_by_domicile']['related_state_list']){
					$temp['related_state_list'] = implode(',', $data['courseSeats']['seats_by_domicile']['related_state_list']);
				}
				$seats[]            = $temp;
			}
		}
		$courseData['courseSeats'] = $seats;


		$courseLocation       = array();
		$courseLocationFees   = array();
		$courseLocationFees_temp   = array();
		$courseContactDetails = array();
		//_p($data['courseLocations']);die;
		foreach ($data['courseLocations']['locations'] as $key => $location) {
			if($location['locationId']){
				$temp                        = array();
				$temp['listing_location_id'] = $location['locationId'];
				if($data['courseLocations']['locationsMain'] == $location['locationId']){
					$temp['is_main'] = 1;
				}else{
					$temp['is_main'] = 0;
				}
				$courseLocation[]            = $temp;

				$nonEmptyContactFound = false;
				$temp_contact_details = array();
				$fieldMapping = array('locationAddress'=>'address','locationWebsite'=>'website_url','locationCoordinatesLat'=>'latitude','locationCoordinatesLong'=>'longitude','locationAdmissionContactNumber'=>'admission_contact_number','locationAdmissionEmail'=>'admission_email','locationGenericContactNumber'=>'generic_contact_number','locationGenericEmail'=>'generic_email');

				foreach ($fieldMapping as $fieldName => $fieldValue) {
					if(!empty($location['contact_details'][$fieldName])){
						$nonEmptyContactFound = true;
						$temp_contact_details[$fieldValue] = $location['contact_details'][$fieldName];
					}
					else{
						$temp_contact_details[$fieldValue] = NULL;
					}
				}
				if(!empty($nonEmptyContactFound)){
					$temp_contact_details['listing_location_id']      = $location['locationId'];
					$temp_contact_details['listing_type']             = 'course';
					$courseContactDetails[]                           = $temp_contact_details;
				}
				
				$fees_attributes['fees_disclaimer']    			  = ($location['fees_disclaimer'] == 1) ? 1 : 0;
				$fees_attributes['batch']    					  = $data['courseFeesForm']['batch'];
				$fees_attributes['fees_unit']    				  = $data['courseFeesForm']['fees_unit'];

				$period = 'overall';
				$type   = 'total';
				$this->formatCourseFeesData($type,$location['fees'],$fees_attributes,0,'',$period,$courseLocationFees_temp);
				if($courseLocationFees_temp){
					foreach ($courseLocationFees_temp as $key => $value) {
						$tempFees                        = array();
						$tempFees['fees_value']          = $value['fees_value'];
						$tempFees['fees_unit']           = $value['fees_unit'];
						$tempFees['batch_year']          = $value['batch_year'];
						$tempFees['fees_type']           = $value['fees_type'];
						$tempFees['category']            = $value['category'];
						$tempFees['period']              = $value['period'];
						$tempFees['order']               = $value['order'];
						$tempFees['fees_disclaimer']     = ($value['fees_disclaimer'] == 1) ? 1 : 0;
						$tempFees['listing_location_id'] = $location['locationId'];
						$courseLocationFees[]            = $tempFees;
					}	
				}
				$courseLocationFees_temp = array();				
			}
		}
		
		$courseData['courseLocation']['courseLocationData'] = $courseLocation;	
		$courseData['courseLocation']['courseLocationFeesData'] = $courseLocationFees;	
		$courseData['courseLocation']['courseLocationContactDetails'] = $courseContactDetails;	
		
		//_p($data['coursePlacements']['recruitingCompanies']);die;
		$courseCompaniesMapping = array();
		if(is_array($data['coursePlacements']['recruitingCompanies'])){
			foreach ($data['coursePlacements']['recruitingCompanies'] as $key => $value) {
				$temp                     = array();
				$temp['company_id']       = $value;	
				$courseCompaniesMapping[] = $temp;					
			}
		}
		if(count($courseCompaniesMapping) > 0){
			$courseData['courseCompaniesMapping']  = $courseCompaniesMapping;				
		}

		$courseMediasMapping = array();
		if(is_array($data['courseMedia'])){
			$totalMediaCount = $data['courseMedia']['mediaCount'];
			if($totalMediaCount != ''){
				unset($data['courseMedia']['mediaCount']);			
				$mappedCourseMediaCounter = 0;
				foreach ($data['courseMedia'] as $mediaId => $value) {
					$temp    = array();
					if($value){
						$mappedCourseMediaCounter = $mappedCourseMediaCounter + 1;
						$temp['media_id']      = $mediaId;	
						$courseMediasMapping[] = $temp;					
					}
				}		
				if($totalMediaCount == $mappedCourseMediaCounter){
					$courseMediasMapping = array();
					$courseMediasMapping[]['media_id'] = -1;
				}
			}	
		}
		if(count($courseMediasMapping) > 0){
			$courseData['courseMediasMapping']  = $courseMediasMapping;				
		}

		//for getting formatted course seo data
		foreach ($courseData['courseTypeInfo'] as $key => $value) {
			if($value['primary_hierarchy'] == 1) {
				$primaryHierarchy = $value;
				break;
			}
		}
		$courseSeoData = array();
		$courseSeoData['primaryInstituteId']   = $courseInfo['primary_id'];
		$courseSeoData['courseTypeInfo']       = $primaryHierarchy;
		$courseSeoData['mainLocationId']       = $data['courseLocations']['locationsMain'];
		$courseSeoData['baseCourseId']         = $data['courseTypeForm']['mapping_info'][0]['courseMapping'];
		$courseSeoData['courseName']           = $courseInfo['name'];
		if(!empty($courseData['courseId'])) {
			$courseSeoData['courseId'] = $courseData['courseId'];
		}

		$formattedSeoData                      = $this->createCourseSeoUrl($courseSeoData);
		// _p($formattedSeoData);
		// _p($data['courseSeo']); die;
		$courseData['listing_seo_url']         = $formattedSeoData['listing_seo_url'];
		$courseData['listing_seo_title']       = (empty($data['courseSeo']['title'])) ? $formattedSeoData['listing_seo_title'] : $data['courseSeo']['title'];
		$courseData['listing_seo_description'] = (empty($data['courseSeo']['description'])) ? $formattedSeoData['listing_seo_description'] : $data['courseSeo']['description'];
		$courseData['posting_comments']        = $data['courseComments']['comment'];
		
		$courseId = $this->coursepostingmodel->saveCourse($courseData);
		$paidCoursePackValue = array(GOLD_SL_LISTINGS_BASE_PRODUCT_ID,SILVER_LISTINGS_BASE_PRODUCT_ID,GOLD_ML_LISTINGS_BASE_PRODUCT_ID);
	
		if(!empty($courseId) && $courseData['mode'] == 'edit' && (in_array($courseData['packType'],$paidCoursePackValue))){
			$courseData['primaryHierarchy']['stream_id'] = $primaryHierarchy['stream_id'];
			$courseData['primaryHierarchy']['substream_id'] = $primaryHierarchy['substream_id']=='none'?0:$primaryHierarchy['substream_id'];
			$courseData['primaryHierarchy']['specialization_id'] = $primaryHierarchy['specialization_id']=='none'?0:$primaryHierarchy['specialization_id'];
			$courseData['primaryHierarchy']['primary_hierarchy'] = $primaryHierarchy['primary_hierarchy'];
			$courseData['isScript'] = isset($formatData['isScript'])?$formatData['isScript']:'false';
			$this->saveNotificationMailerData($courseData);
		}
		return $courseId;
	}

	public function trimObjRecursively(&$obj){
		foreach ($obj as $key => $value) {
			if(is_array($value)){
				$this->trimObjRecursively($obj[$key]);
			}
			else{
				if(!empty($value)){
					$obj[$key] = trim($value);
				}
			}
		}
	}


	function formatCourseFeesData($type,$feesCategories,$courseFeesForm,$order,$courseFeesIncludesFilter,$period, &$courseFees){

		foreach ($feesCategories as $category => $value) {
			if($value != '' || $value === 0){
				$temp                        = array();
				$temp['listing_location_id'] = '-1';
				$temp['fees_value']          = $value;
				$temp['fees_unit']           = $courseFeesForm['fees_unit'];
				$temp['batch_year']          = $courseFeesForm['batch'];
				$temp['fees_type']           = $type;
				$temp['category']            = $category;
				$temp['period']              = $period;
				$temp['order']               = $order+1;
				$feesDisclaimer = ($type=='hostel')?0:$courseFeesForm['fees_disclaimer'];
				$temp['fees_disclaimer']     = ($feesDisclaimer == 1) ? $feesDisclaimer : 0;
				
				if($courseFeesIncludesFilter)
					$temp['total_includes']      = $courseFeesIncludesFilter;

				$courseFees[] = $temp;	
			}			
		}
	}

	function getAllBaseCourses(){
		$courses = $this->baseCourseRepo->getAllBaseCourses();$returnData = array();
		foreach ($courses as $course) {
			$returnData[] = array('value'=>"".$course['id']."",'label'=>$course['name']);
		}
		return $returnData;
	}

	function getCourseData($courseId) {
		$formatData = array();
		$data       = $this->coursepostingmodel->getCourseData($courseId);

		if(empty($data)){
			return 'NO_SUCH_LISTING_FOUND_IN_DB';
		}

		$extraData = array();

		$hierarchyForm  = array();
		$instituteNames = array();
		$instituteIds   = array();
		$universityIds  = array();
		// $abroadInstituteIds = array();
		$abroadUniversityIds = array();

		if($data['course_info']['parent_type'] == 'institute'){
			$instituteIds[] = $data['course_info']['parent_id'];
		}
		else if($data['course_info']['parent_type'] == 'university'){
			$universityIds[] = $data['course_info']['parent_id'];
		}

		if($data['course_info']['primary_type'] == 'institute'){
			$instituteIds[] = $data['course_info']['primary_id'];
		}
		else if($data['course_info']['primary_type'] == 'university'){
			$universityIds[] = $data['course_info']['primary_id'];
		}

		if(!empty($data['course_info']['affiliated_university_id'])){
			if($data['course_info']['affiliated_university_scope'] == 'domestic'){
				$universityIds[] = $data['course_info']['affiliated_university_id'];
			}
			else{
				$abroadUniversityIds[] = $data['course_info']['affiliated_university_id'];
			}
		}

		foreach($data['coursePartnerForm'] as $key => $partnerDetails) {
			if($partnerDetails['scope'] == 'domestic'){
				if($partnerDetails['partner_type'] == 'university'){
					$universityIds[] = $partnerDetails['partner_id'];
				}
				else if($partnerDetails['partner_type'] == 'institute'){
					$instituteIds[] = $partnerDetails['partner_id'];
				}
			}
			else if($partnerDetails['scope'] == 'studyAbroad'){
				$abroadUniversityIds[] = $partnerDetails['partner_id'];
			}
		}
		if(!empty($instituteIds)){
			$temp = $this->institutePostingLib->getInstituteNamesById($instituteIds,array('name'));
			$instituteNames = array_merge($temp['idNameArr'],$instituteNames);
		}
		if(!empty($universityIds)){
			$temp = $this->institutePostingLib->getUniversityNamesById($universityIds,array('name'));
			$instituteNames = array_merge($temp['idNameArr'],$instituteNames);
		}
		if(!empty($abroadUniversityIds)){
			$temp = $this->getAbroadUniversityNamesById($abroadUniversityIds);
			$instituteNames = array_merge($temp['idNameArr'],$instituteNames);
		}

		$hierarchyForm['parent_course_hierarchy']       = $data['course_info']['parent_type']."_".$data['course_info']['parent_id'];
		$hierarchyForm['primary_course_hierarchy']      = $data['course_info']['primary_type']."_".$data['course_info']['primary_id'];
		$hierarchyForm['parent_course_hierarchy_name']  = $instituteNames[$hierarchyForm['parent_course_hierarchy']];
		$hierarchyForm['primary_course_hierarchy_name'] = $instituteNames[$hierarchyForm['primary_course_hierarchy']];
		$extraData['hierarchyKey']                      = $this->institutePostingLib->getParentHierarchyById($data['course_info']['parent_id'],$data['course_info']['parent_type']);
		$scheduleForm                     = array();
		$scheduleForm['education_type']   = $data['course_info']['education_type'];
		$scheduleForm['delivery_method']  = $data['course_info']['delivery_method'];
		if($scheduleForm['delivery_method'] == '39'){
			$scheduleForm['time_of_learning'] = $data['course_info']['time_of_learning'];	
		}
		
		$courseBasicInfoForm                        = array();
		$courseBasicInfoForm['course_name']         = $data['course_info']['name'];
		$courseBasicInfoForm['duration_value']      = $data['course_info']['duration_value'];
		$courseBasicInfoForm['duration_unit']       = $data['course_info']['duration_unit'];
		if(!empty($data['course_info']['duration_disclaimer'])){
			$courseBasicInfoForm['duration_disclaimer'] = $data['course_info']['duration_disclaimer'];
		}

		if(!empty($data['course_info']['difficulty_level'])){
			$courseBasicInfoForm['difficulty_level'] = $data['course_info']['difficulty_level'];
		}
		if(!empty($data['course_info']['affiliated_university_id'])){
			$data['course_info']['affiliated_university_name'] = $instituteNames['university_'.$data['course_info']['affiliated_university_id']];
		}
		if(!empty($data['course_info']['affiliated_university_name'])){
			$courseBasicInfoForm['affiliated_university_scope'] = $data['course_info']['affiliated_university_scope'];
			if(!empty($data['course_info']['affiliated_university_id'])){
				$courseBasicInfoForm['affiliated_university_id'] = $data['course_info']['affiliated_university_id'];
			}
			if(!empty($data['course_info']['affiliated_university_year'])){
				$courseBasicInfoForm['affiliated_university_year'] = $data['course_info']['affiliated_university_year'];
			}
			$courseBasicInfoForm['affiliated_university_name'] = $data['course_info']['affiliated_university_name'];
		}
		$formatData['courseUsp'] = array();
		foreach ($data['course_addditional_info'] as $key => $value) {
			if($value['info_type'] == 'schedule'){
				$scheduleForm[$value['info_type']][]= $value['attribute_value_id'];	
			}elseif ($value['info_type'] == 'recognition' || $value['info_type'] == 'instruction_medium') {
				$courseBasicInfoForm[$value['info_type']][] = $value['attribute_value_id'];
			}
			else if($value['info_type'] == 'usp'){
				$formatData['courseUsp']['usp_list'][] = array('usp'=>$value['description']);
			}
		}
		$formatData['courseId']            = $data['course_info']['course_id'];
		$formatData['clientId']            = $data['listingMainData']['username'];
		$formatData['subscriptionId']      = $data['listingMainData']['subscriptionId'];
		$formatData['packType']            = $data['listingMainData']['pack_type'];
		$formatData['hierarchyForm']       = $hierarchyForm;
		$formatData['scheduleForm']        = $scheduleForm;
		$formatData['courseBasicInfoForm'] = $courseBasicInfoForm;
		$formatData['extraData']           = $extraData;
		if(!empty($data['course_info']['disabled_url'])){
			$formatData['isDisabled'] = $data['course_info']['disabled_url'];
		}

		$courseTypeInfo                                 = array();
		$courseTypeInfo['course_variant']               = $data['course_info']['course_variant'];

		$fieldMapping = array('executive'=>'is_executive','twinning'=>'is_twinning','lateral'=>'is_lateral','integrated'=>'is_integrated','dual'=>'is_dual');
		foreach ($fieldMapping as $key => $value) {
			if(!empty($data['course_info'][$key])){
				$courseTypeInfo['course_tags'][$value] = $data['course_info'][$key];
			}
		}

		$typeInfo = array();
		foreach ($data['courseTypeInfo'] as $row) {
			if(empty($typeInfo[$row['type']])){
				$typeInfo[$row['type']] = array('credential'=>$row['credential'],'course_level'=>$row['course_level'],'hierarchyMapping'=>array(),'type'=>$row['type']);
				if(!empty($row['base_course'])){
					$typeInfo[$row['type']]['courseMapping'] = $row['base_course'];
				}
			}
			$temp = array();
			if(!empty($row['stream_id'])){
				$temp['stream_id'] = empty($row['stream_id']) ? NULL : $row['stream_id'];
				$temp['substream_id'] = empty($row['substream_id']) ? NULL : $row['substream_id'];
				$temp['specialization_id'] = empty($row['specialization_id']) ? NULL : $row['specialization_id'];
				$temp['is_primary'] = $row['primary_hierarchy'];
				$typeInfo[$row['type']]['hierarchyMapping'][] = $temp;
			}
		}
		$typeInfo = array_values($typeInfo);
		foreach ($typeInfo as $key => $row) {
			if(empty($row['hierarchyMapping'])){
				$typeInfo[$key]['hierarchyMapping'] = array('stream_id' => '');
			}
		}
		$courseTypeInfo['mapping_info'] = $typeInfo;

		$formatData['courseTypeForm'] = $courseTypeInfo;

		$eligibility = array();
		$mainData = $data['eligibility']['mainData'];
		if(!empty($mainData)){
			$fieldMapping = array('batch'=>'batch_year','work-ex_min'=>'work-ex_min','work-ex_max'=>'work-ex_max','work-ex_unit'=>'work-ex_unit','age_min'=>'age_min','age_max'=>'age_max','international_description'=>'international_students_desc','description'=>'description');
			foreach ($fieldMapping as $key => $value) {
				if(!empty($mainData[$value])){
					$eligibility[$key] = $mainData[$value];
				}
			}

			$eligibleSubjects = $this->CI->config->item('eligibleSubjects');
			if($mainData['subjects']){
				$subjects = json_decode($mainData['subjects'],true);
				foreach ($subjects as $subject) {
					if(in_array($subject,$eligibleSubjects)){
						$eligibility['subjects'][] = $subject;
					}
					else{
						$eligibility['other_subjects'][] = $subject;
					}
				}
			}
		}

		$scoreData = $data['eligibility']['scoreData'];
		$mapping = array('X' => '10th_details','XII' => '12th_details','graduation' => 'graduation_details','postgraduation' => 'postgraduation_details');

		foreach ($scoreData as $score) {
			if(empty($score['category'])){
				$eligibility[$mapping[$score['standard']]]['description'] = $score['specific_requirement'];
			}
			else{
				$eligibility[$mapping[$score['standard']]]['category_wise_cutoff'][$score['category']] = $score['value'];
				$eligibility[$mapping[$score['standard']]]['category_wise_cutoff']['outof'] = $score['max_value'];
				$eligibility[$mapping[$score['standard']]]['score_type'] = $score['unit'];
			}
		}

		$examScoreData = $data['eligibility']['examScoreData'];$exams_accepted = array();
		foreach($examScoreData as $row){
			$col = $row['exam_id'];
			if(empty($row['exam_id'])){
				$col = $row['exam_name'];
			}
			if(!empty($row['unit'])){
				$exams_accepted[$col]['exam_unit'] = $row['unit'];
			}
			if(!empty($row['exam_id'])){
				$exams_accepted[$col]['exam_name'] = $col;
			}
			else{
				$exams_accepted[$col]['exam_name'] = 'other';
				$exams_accepted[$col]['custom_exam'] = $row['exam_name'];
			}
			if(!empty($row['category'])){
				$exams_accepted[$col][$row['category']] = $row['value'];
			}
			if(!empty($row['max_value'])){
				$exams_accepted[$col]['outof'] = $row['max_value'];
			}
		}
		$eligibility['exams_accepted'] = array_values($exams_accepted);

		$entityData = $data['eligibility']['entityData'];
		$eligibility['graduation_details']['entityMapping'] = array();
		$eligibility['postgraduation_details']['entityMapping'] = array();
		foreach ($entityData as $entity) {
			if($entity['type'] == 'graduation'){
				$eligibility['graduation_details']['entityMapping'][] = array('base_course' => $entity['base_course'], 'specialization' => $entity['specialization']);
			}
			else if($entity['type'] == 'postgraduation'){
				$eligibility['postgraduation_details']['entityMapping'][] = array('base_course' => $entity['base_course'], 'specialization' => $entity['specialization']);
			}
		}

		$formatData['courseEligibilityForm'] = $eligibility;


		$placements                         = array();
		$fieldMapping = array('batch_year'=>'batch','percentage_batch_placed'=>'batch_percentage','salary_unit'=>'batch_unit','min_salary'=>'batch_min_salary','median_salary'=>'batch_median_salary','avg_salary'=>'batch_average_salary','max_salary'=>'batch_max_salary','total_international_offers'=>'international_offers','max_international_salary'=>'max_salary','max_international_salary_unit'=>'max_salary_unit','report_url'=>'report_url');
		foreach ($data['placements'] as $key => $value) {
			if(array_key_exists($key, $fieldMapping) && !empty($value)){
				$placements[$fieldMapping[$key]] = $value;
			}
		}
		if(!empty($placements)){
			$placements['course'] = ($data['placements']['course_type'] == 'clientCourse') ? 'clientCourse' : $data['placements']['course_type'].'_'.$data['placements']['course'];
			$formatData['coursePlacements'] = $placements;
		}

		$internship                           = array();
		$fieldMapping = array('batch_year'=>'intern_batch','salary_unit'=>'intern_batch_unit','min_salary'=>'intern_min_stipend','median_salary'=>'intern_median_stipend','avg_salary'=>'intern_average_stipend','max_salary'=>'intern_max_stipend','report_url'=>'report_url');
		foreach ($data['internship'] as $key => $value) {
			if(array_key_exists($key, $fieldMapping) && !empty($value)){
				$internship[$fieldMapping[$key]] = $value;
			}
		}
		if(!empty($internship)){
			$formatData['courseInternship'] = $internship;
		}

		$courseSeats = array();
		if(!empty($data['course_info']['total_seats'])){
			$courseSeats['total_seats'] = $data['course_info']['total_seats'];
		}
		
		foreach($data['seats'] as $row){
			if($row['breakup_by'] == 'domicile'){
				$courseSeats['seats_by_domicile'][$row['category']] = $row['seats'];
				if(!empty($row['related_state_list'])){
					$courseSeats['seats_by_domicile']['related_state_list'] = explode(',',$row['related_state_list']);
				}
			}
			else if($row['breakup_by'] == 'category'){
				$courseSeats['seats_by_category'][$row['category']] = $row['seats'];
			}
			else if($row['breakup_by'] == 'exam'){
				if(!empty($row['exam_id'])){
					$courseSeats['seats_by_entrance_exam'][$row['exam_id']] = $row['seats'];
				}
				else{
					$courseSeats['seats_by_entrance_exam']['Others$$'.$row['custom_exam_name']] = $row['seats'];
				}
			}
		}
		if(!empty($courseSeats)){
			$formatData['courseSeats'] = $courseSeats;
		}

		$courseStructureInfo = array();
		foreach ($data['courseStructureInfo'] as $row) {
			$courseStructureInfo['group_by'] = $row['period'];
			$courseStructureInfo['group_courses'][$row['period_value'] - 1][] = $row['courses_offered'];
		}
		if(!empty($courseStructureInfo)){
			$structureInfo = array();
			$max = max(array_keys($courseStructureInfo['group_courses']));
			if($max != (count($courseStructureInfo['group_courses'])-1)){
				for($i=0;$i <= $max;$i++){
					if(empty($courseStructureInfo['group_courses'][$i])){
						$structureInfo[] = array();
					}
					else{
						$structureInfo[] = $courseStructureInfo['group_courses'][$i];
					}
				}
				$courseStructureInfo['group_courses'] = $structureInfo;
			}
		}
		$formatData['courseStructureForm'] = $courseStructureInfo;

		$importantDates = array();
		$labels_start = array('Application Start Date','Application Submit Date','Course Commencement Date','Results Date');
		foreach($data['importantDates'] as $row){
			$temp = array();
			if(!empty($row['start_date'])){
				$temp['start']['date'] = $row['start_date'];
			}
			if(!empty($row['start_month'])){
				$temp['start']['month'] = $row['start_month'];
			}
			$temp['start']['year'] = $row['start_year'];
			$temp['start']['label'] = $row['event_name'];

			if(!empty($row['end_date'])){
				$temp['end']['date'] = $row['end_date'];
			}
			if(!empty($row['end_month'])){
				$temp['end']['month'] = $row['end_month'];
			}
			if(!empty($row['end_year'])){
				$temp['end']['year'] = $row['end_year'];
				$temp['end']['type'] = 'end';
			}
			if(in_array($temp['start']['label'],$labels_start)){
				$temp['start']['type'] = 'start';
			}
			else{
				$temp['start']['type'] = 'others';
			}
			$importantDates[] = $temp;
		}
		

		if(!empty($importantDates)){
			$formatData['importantDatesForm']['events'] = $importantDates;
		}

		if(!empty($data['brochureData']['brochure_url']) && !empty($data['brochureData']['brochure_year'])){
			$formatData['courseBrochure']['brochure_url']  = $data['brochureData']['brochure_url'];
			$formatData['courseBrochure']['brochure_year'] = $data['brochureData']['brochure_year'];
			$formatData['courseBrochure']['brochure_size'] = $data['brochureData']['brochure_size'];
		}


		// $courseAdmissionProcess = array();
		foreach ($data['courseAdmissionProcess'] as $key => $value) {
			$formatData['courseAdmissionProcess']['admission_process'][$key]['admission_name'] = $value['admission_name'];
			if($value['admission_name_other']) {
				$formatData['courseAdmissionProcess']['admission_process'][$key]['admission_name_others'] = $value['admission_name_other'];
			}
			$formatData['courseAdmissionProcess']['admission_process'][$key]['admission_description'] = $value['admission_description'];
		}		

		$formatData['courseMedia'] = array();
		//_p($data['courseMediasMapping']);die;
		foreach ($data['courseMediasMapping'] as $key => $value) {
			$formatData['courseMedia'][] = $value['media_id']; 
		}

		// $formatData['coursePlacements'] = array();
		foreach ($data['courseCompaniesMapping'] as $key => $value) {
			$formatData['coursePlacements']['recruitingCompanies'][] = $value['company_id']; 
		}



		$cutOff = $this->getFormattedCutOffData($data);
		if($formatData['courseEligibilityForm']['batch']) {
			$formatData['courseExamCutOff'] = $cutOff['courseExamCutOff'];
		}
		$formatData['course12thCutOff'] = $cutOff['course12thCutOff'];

		$coursePartnerData = $this->getFormattedPartnerDetails($data,$instituteNames);
		$formatData['coursePartnerForm'] = $coursePartnerData;
		
		// _p($data['courseMediasMapping']);
		// _p($data['courseCompaniesMapping']);die;

		$refineContactDetails = array();
		foreach ($data['contactDetails'] as $key => $value) {
			$refineContactDetails[$value['listing_location_id']] = $value; 
		}

		$refineFees = array();
		foreach ($data['courseFees'] as $key => $value) {
			$refineFees[$value['listing_location_id']][] = $value; 
		}


		$formatData['courseLocations']['locations'] = array();	
		$mainLocationId = '';
		if(!empty($data['courseLocations'])){
			foreach ($data['courseLocations'] as $key => $value) {
				$formatData['courseLocations']['locations'][$key]['locationId'] = $value['listing_location_id'];
				if($value['is_main']){
					$mainLocationId = $value['listing_location_id'];
				}

				$contactDetailData = $refineContactDetails[$value['listing_location_id']];
				$contactformatData = array();
				$formatData['courseLocations']['locations'][$key]['contact_details']  = array();
				if(!empty($contactDetailData)){

					$fieldMapping = array('address'=>'locationAddress','website_url'=>'locationWebsite','latitude'=>'locationCoordinatesLat','longitude'=>'locationCoordinatesLong','admission_contact_number'=>'locationAdmissionContactNumber','generic_contact_number'=>'locationGenericContactNumber','admission_email'=>'locationAdmissionEmail','generic_email'=>'locationGenericEmail');
					foreach ($fieldMapping as $fieldName => $fieldValue) {
						if(!empty($contactDetailData[$fieldName])){
							$contactformatData[$fieldValue] = $contactDetailData[$fieldName];
						}
					}
					$formatData['courseLocations']['locations'][$key]['contact_details'] = $contactformatData; 	
				}

				$feesData = $refineFees[$value['listing_location_id']];
				if(!empty($feesData)){
					$formatData['courseLocations']['locations'][$key]['fees'] = array();
					foreach ($feesData as $index => $value) {
						$formatData['courseLocations']['locations'][$key]['fees'][$value['category']] = $value['fees_value'];
						if($value['fees_disclaimer'] == '1'){
							$formatData['courseLocations']['locations'][$key]['fees_disclaimer']          = true;							
						}else{
							$formatData['courseLocations']['locations'][$key]['fees_disclaimer']          = false;
						}
						if($index == 0){
							$formatData['courseFeesForm']['fees_unit'] = $value['fees_unit'];
							$formatData['courseFeesForm']['batch']     = $value['batch_year'];
						}
					}
				}

			}
		}

		$formatData['courseLocations']['locationsMain'] = $mainLocationId;

		$totalFees = array();

		foreach ($data['courseFeesForm'] as $oneFees) {
			if ($oneFees['listing_location_id'] == -1 || $oneFees['listing_location_id'] == 0) {
				if ($oneFees['fees_type'] == 'total') {
					if ($oneFees['period'] == 'overall') {
						$formatData['courseFeesForm']['total_fees_total'][ $oneFees['category'] ] = $oneFees['fees_value'];
					} else if ($oneFees['period'] == 'otp') {
						$formatData['courseFeesForm']['total_fees_one_time_payment'][ $oneFees['category'] ] = $oneFees['fees_value'];
					} else {
						$periodForTotalFees = array('year', 'semester', 'trimester', 'term', 'installment');
						if (in_array($oneFees['period'], $periodForTotalFees)) {
							$totalFees[ $oneFees['order'] ][ $oneFees['category'] ] = $oneFees['fees_value'];
							$formatData['courseFeesForm']['total_fees_period'] = $oneFees['period'];
						}
					}
				} else if ($oneFees['fees_type'] == 'hostel') {
					$formatData['courseFeesForm']['hostel_fees'][ $oneFees['category'] ] = $oneFees['fees_value'];
				}


				if ($oneFees['total_includes'] != null) {
					$totalIncludes = explode(";", $oneFees['total_includes']);

					$otherText = explode("--", $totalIncludes[count($totalIncludes)-1]);
					if(count($otherText) > 1){
						array_pop($totalIncludes);
						array_shift($otherText);
						$formatData['courseFeesForm']['total_fees_includes']["OthersText"] = explode("|", $otherText[0]);
					}

					foreach ($totalIncludes as $oneInclude) {
						$formatData['courseFeesForm']['total_fees_includes'][ $oneInclude ] = 1;
					}
				}

				if ($oneFees['other_info'] != null) {
					$formatData['courseFeesForm']['other_info'] = $oneFees['other_info'];
				}

				if ($oneFees['batch_year'] != null) {
					$formatData['courseFeesForm']['batch'] = $oneFees['batch_year'];
				}

				if ($oneFees['fees_unit'] != null) {
					$formatData['courseFeesForm']['fees_unit'] = $oneFees['fees_unit'];
				}

				if ($oneFees['fees_disclaimer'] != null && !($formatData['courseFeesForm']['fees_disclaimer'])) {
					$formatData['courseFeesForm']['fees_disclaimer'] = $oneFees['fees_disclaimer'] == 1 ? true : false;
				}
			}

		}

		if(count($totalFees) > 0){
			$formatData['courseFeesForm']['total_fees'] = array_values($totalFees);
		}

		$formatData['courseSeo']['title']       = $data['listingMainData']['listing_seo_title'];
		$formatData['courseSeo']['description'] = $data['listingMainData']['listing_seo_description'];

		return $formatData;
	}

	public function getBasecoursesByMultipleBaseEntities($hierarchyArr){
		$courses = $this->baseCourseRepo->getBaseCoursesByMultipleBaseEntities($hierarchyArr,1);$returnData = array();
		foreach ($courses as $courseId => $name) {
			$returnData[] = array('value'=>"".$courseId."",'label'=>$name);
		}
		return $returnData;
	}

	public function getBasecoursesByHirarchyWithFilter($hierarchyArr, $courseLevel, $credential){
		$courses = $this->baseCourseRepo->getBasecoursesByHirarchyWithFilter($hierarchyArr, $courseLevel, $credential, 1);
		$returnData = array();
		foreach ($courses as $courseId => $name) {
			$returnData[] = array('value'=>"".$courseId."",'label'=>$name);
		}
		return $returnData;
	}

	public function getSpecializationsByBaseCourses($baseCourseArr){
		$data = $this->baseCourseRepo->getSpecializationsByBaseCourseIds($baseCourseArr,array(),1);
		$returnData = array();
		foreach ($data as $row) {
			$returnData[] = array('value'=>"".$row['id']."",'label'=>$row['name']);
		}
		usort($returnData,function($a,$b){
			return strcmp($a['label'], $b['label']);
		});
		return $returnData;
	}

	function formatExamsCutOffData($examCutOffData) {
		$courseExamsCutOff = array();
		$key = 0;
		foreach ($examCutOffData as $count => $examData) {
			list($examId, $customExam) = explode('$$',$examData['exam_id']);
            foreach ($examData['cutOffData'] as $roundNumber => $rounds) {
            	foreach ($rounds['round_table_arr'] as $k => $roundTableArr) {
            		foreach ($roundTableArr as $category => $value) {
	            		if($category != 'type' && $value && $examId) {
	            			if($roundTableArr['type'] == 'related_states' && empty($examData['related_states'])) {
	            				continue;
	            			}
			                if($roundTableArr['type'] == 'related_states') {
			                	$courseExamsCutOff[$key]['quota'] = $roundTableArr['type'].":".implode(',', $examData['related_states']);
			                }
			                else {
			                	$courseExamsCutOff[$key]['quota'] = $roundTableArr['type'];
			                }
	            			// echo $category.'--'.$value.'<br/>';
			                $courseExamsCutOff[$key]['exam_id'] = $examId;
			                $courseExamsCutOff[$key]['custom_exam'] = (!empty($customExam)) ? $customExam : null;
			                // $courseExamsCutOff[$key]['exam_id'] = 1;
			                $courseExamsCutOff[$key]['exam_year'] = $examData['exam_year'];
			                // $courseExamsCutOff[$key]['exam_year'] = 2016;
			                $courseExamsCutOff[$key]['category'] = $category;
			                $courseExamsCutOff[$key]['cut_off_value'] = $value;
			                $courseExamsCutOff[$key]['cut_off_unit'] = $examData['exam_unit'];
			                $courseExamsCutOff[$key]['exam_out_of'] = $examData['exam_out_of'];
			                $courseExamsCutOff[$key]['cut_off_type'] = 'exam';
			                //setting round as -1 for round not applicable
			                $courseExamsCutOff[$key]['round'] = ($examData['round_applicable']) ? $roundNumber : -1;
	            			$key++;
	            		}
            		}
            	}
            }
        }
        
        return $courseExamsCutOff;
	}

	function format12thCutOffData($course12thCutOffData) {
		$course12thCutOffFormatted = array();
		$key = 0;
    	foreach ($course12thCutOffData as $k => $roundTableArr) {
    		foreach ($roundTableArr as $category => $value) {
        		if($category != 'type' && $value) {
	                $courseExamsCutOff[$key]['exam_id'] = null;
	                $courseExamsCutOff[$key]['custom_exam'] = (!empty($customExam)) ? $customExam : null;
	                $courseExamsCutOff[$key]['exam_year'] = $examData['exam_year'];
	                $courseExamsCutOff[$key]['category'] = $category;
	                $courseExamsCutOff[$key]['quota'] = $roundTableArr['type'];
	                $courseExamsCutOff[$key]['cut_off_value'] = $value;
	                $courseExamsCutOff[$key]['cut_off_unit'] = null;
	                $courseExamsCutOff[$key]['exam_out_of'] = null;
	                $courseExamsCutOff[$key]['cut_off_type'] = '12th';
	                //setting round as -1 for round not applicable
	                $courseExamsCutOff[$key]['round'] = -1;
        			$key++;
        		}
    		}
    	}
        
        return $courseExamsCutOff;
	}

	public function createCourseSeoUrl($courseSeoData){
		//getting required repos
		$this->CI->load->builder("nationalInstitute/InstituteBuilder");
		$builder                     = new InstituteBuilder();
		$this->instituteRepo         = $builder->getInstituteRepository();
		$this->CI->load->builder("listingBase/ListingBaseBuilder");
		$ListingBaseBuilder          = new ListingBaseBuilder();
		$this->institutepostingmodel = $this->CI->load->model('nationalInstitute/institutepostingmodel');
		$BaseCourseRepository        = $ListingBaseBuilder->getBaseCourseRepository();
		$StreamRepository            = $ListingBaseBuilder->getStreamRepository();
		$specRepository	             = $ListingBaseBuilder->getSpecializationRepository();
		$SubstreamRepository         = $ListingBaseBuilder->getSubstreamRepository();
		$this->nationalCourseLib 	 = $this->CI->load->library('listing/NationalCourseLib');
		$this->caLibrary 	 		 = $this->CI->load->library('CA/CAHelper');
		
		$baseCourseId                = $courseSeoData['baseCourseId'];
		$mainLocationId              = $courseSeoData['mainLocationId'];
		$instituteId                 = $courseSeoData['primaryInstituteId'];
		$primaryHierarchy            = $courseSeoData['courseTypeInfo'];
		$courseName                  = trim($courseSeoData['courseName']);
		
		//priority order for creating URL
		$cases                       = array('popular_course', 'substream', 'stream');
		
		$instituteLocationData       = $this->instituteRepo->getInstituteLocations(array($instituteId),array($mainLocationId));
		$instituteLocationObj        = $instituteLocationData[$instituteId][$mainLocationId];
		
		if(!is_object($instituteLocationObj)) {
			$this->logFileName = 'log_corrupt_courses_'.date('y-m-d');
        	$this->logFilePath = '/tmp/'.$this->logFileName;
			error_log("Institute location data corrupt. Skipping course id - ".$courseSeoData['courseId']."\n", 3, $this->logFilePath);
			return;
		}
		$localityIdMainLocation      = $instituteLocationObj->getLocalityId();

		
	    if(!empty($baseCourseId)) {
			$baseCourseObj = $BaseCourseRepository->find($baseCourseId);
	    }

	    $streamObj = $StreamRepository->find($primaryHierarchy['stream_id']);
		$streamName = $streamObj->getAlias();
		if(!$streamName) {
			$streamName = $streamObj->getName();
		}

		$instiuteObj = $this->instituteRepo->find($instituteId); 
		$instituteNameForUrl = $instiuteObj->getName();
		$instituteShortName = $instiuteObj->getShortName() ? $instiuteObj->getShortName() : $instiuteObj->getName();
		$locationNameForUrl = '';
		$locationNameForSeo = '';
		if(!empty($localityIdMainLocation)){
	        $locationNameForUrl = $instituteLocationObj->getLocalityName();
	        $locationNameForSeo = ', '. $instituteLocationObj->getLocalityName();
		    if(stripos($instituteNameForUrl, $instituteLocationObj->getCityName()) === FALSE){
	        	$locationNameForUrl .= '-'.$instituteLocationObj->getCityName();
	        	$locationNameForSeo .= ', '.$instituteLocationObj->getCityName();
	    	}
	    }else{
	    	if(stripos($instituteNameForUrl, $instituteLocationObj->getCityName()) === FALSE){
	        	$locationNameForUrl .= $instituteLocationObj->getCityName();
	        	$locationNameForSeo .= ', '.$instituteLocationObj->getCityName();
	    	}
	    }

	    $urlComponent = array();
		foreach ($cases as $value) {
			switch ($value) {
				case 'popular_course':
					if($baseCourseId) {
						if($baseCourseObj->getIsPopular()) {
							$casePassed = true;
							$baseCourseName = $baseCourseObj->getAlias();
							if(!$baseCourseName) {
								$baseCourseName = $baseCourseObj->getName();
							}
							$urlComponent[] = seo_url_lowercase($baseCourseName).'/course';
						}
					}
					break;
				case 'substream':

					if($primaryHierarchy['substream_id'] > 0) {
						$urlComponent[] = seo_url_lowercase($streamName);
						$substreamObj = $SubstreamRepository->find($primaryHierarchy['substream_id']);
						$substreamName = $substreamObj->getAlias();
						if(!$substreamName) {
							$substreamName = $substreamObj->getName();
						}
						$casePassed = true;
						$urlComponent[] = seo_url_lowercase($substreamName).'/course';
					}
					break;
				case 'stream':
					$urlComponent[] = seo_url_lowercase($streamName).'/course';
					$casePassed = true;
					break;
				default:
					# code...
					break;
			}
			if($casePassed) {
				$caseName = $value;
				break;
			}
		}
		
		if($locationNameForUrl){
			$urlComponent[] = seo_url_lowercase($courseName).'-'.seo_url_lowercase($instituteNameForUrl).'-'.seo_url_lowercase($locationNameForUrl);
		}else {
			$urlComponent[] = seo_url_lowercase($courseName).'-'.seo_url_lowercase($instituteNameForUrl);			
		}
		
		if($courseSeoData['courseId']) {
			$courseData['listing_seo_url'] =  "/".implode('/', $urlComponent)."-".$courseSeoData['courseId'];
		}
		else {
			//if course id is not present this means it is a new course hence course id will be appended from model
			$courseData['listing_seo_url'] =  "/".implode('/', $urlComponent);
		}
		

		/*if(!$courseData['listing_seo_title']){
			if(!empty($primaryHierarchy['specialization_id']) && $primaryHierarchy['specialization_id'] != "none") {
				$specObj = $specRepository->find($primaryHierarchy['specialization_id']);
				$specName = $specObj->getName();
			}
			if(!empty($baseCourseObj)) {
				$baseCourseName = $baseCourseObj->getName();
			}

			//Case 1: Both Spec and BC are present
			if(!empty($specName) && !empty($baseCourseName)) {
				$listingSeoTitle = "$baseCourseName in $specName at $instituteShortName";
			}
			
			//Case 2: Only Spec is present
			elseif(!empty($specName)) {
				$listingSeoTitle = "$courseName at $instituteShortName";
			}
			
			//Case 3: Only BC is present
			elseif(!empty($baseCourseName)) {
				$listingSeoTitle = "$baseCourseName at $instituteShortName";
			}

			//Case 4: Neither Spec nor BC is present
			else {
				$listingSeoTitle = "$courseName at $instituteShortName";
			}
			//Read more about <Course Name> at <Entity Name>, <Locality>, <City>. Find out about fees, admissions, reviews and more only at Shiksha.com
			$listingSeoTitle = 'Read more about '.trim($courseName)." at " .$instituteNameForUrl.$locationNameForSeo.'. Find out about fees, admissions, reviews and more only at Shiksha.com';
			$courseData['listing_seo_description']       = $listingSeoTitle;
		}

			if(!empty($courseSeoData['courseId'])) {
				$reviewCountArr = $this->nationalCourseLib->getCourseReviewCount(array($courseSeoData['courseId']));
				if(!empty($reviewCountArr[$courseSeoData['courseId']])) {
					$reviewCount = $reviewCountArr[$courseSeoData['courseId']];
				}
				$questionCountArr = $this->caLibrary->getQuestionCountForCourses(array($courseSeoData['courseId']));
				if(!empty($questionCountArr[$courseSeoData['courseId']])) {
					$questionCount = $questionCountArr[$courseSeoData['courseId']];
				}
			}
			
			$listingSeoDesc = "Read $reviewCount Reviews and $questionCount Answered Questions on cutoff, placements, fees, admission, ranking & eligibility of $courseName at $instituteShortName, $locationNameForSeo";
			$courseData['listing_seo_description'] = $listingSeoDesc;
		}*/
		
		return $courseData;
	}

	function getFormattedCutOffData($courseData) {
		$course12thCutOffData = array();
		$courseExamCutOffData = array();
		// _p($courseData); die;
		foreach ($courseData['courseCutOff'] as $key => $value) {
			$temp = array();
			$temp[$value['catgegory']] = $value['cut_off_value'];
			$temp['type'] = $value['type'];
			if(!$value['cut_off_value']) {
				continue;
			}
			//for 12th cut off
			if($value['cut_off_type'] == '12th') {
				$course12thCutOffData[$value['quota']][$value['category']] = $value['cut_off_value'];
				$course12thCutOffData[$value['quota']]['type'] = $value['quota'];
			}
			//for exam cut off
			else {
				$examArray = array();
				$examDataArray= array();
				$quota = '';
				$relatedState = '';
				list($quota, $relatedState) = explode(':', $value['quota']);
				if($value['round'] == -1) {
					$roundIndex = 0;
				}
				else {
					$roundIndex = $value['round'];
				}
				
				$examDataArray[$quota][$value['category']] = $value['cut_off_value'];
				$examDataArray[$quota]['type'] = $quota;
				$courseExamCutOffData[$value['exam_id']]['exam_id'] = $value['exam_id'];
				if(!empty($value['custom_exam'])) {
					$courseExamCutOffData[$value['exam_id']]['exam_id'] = 'other$$'.$value['custom_exam'];
				}
				$courseExamCutOffData[$value['exam_id']]['exam_out_of'] = $value['exam_out_of'];
				$courseExamCutOffData[$value['exam_id']]['exam_unit'] = $value['cut_off_unit'];
				$courseExamCutOffData[$value['exam_id']]['exam_year'] = $value['exam_year'];
				
				$courseExamCutOffData[$value['exam_id']]['round_applicable'] = ($value['round'] == -1) ? 0: 1; //for applicable round value is 1 else 0
				if(!empty($relatedState)) {
					$courseExamCutOffData[$value['exam_id']]['related_states'] = explode(',',$relatedState);
				}
				
				$courseExamCutOffData[$value['exam_id']]['cutOffData']['round_table_arr'][$roundIndex][$quota][$value['category']] = $value['cut_off_value'];
				$courseExamCutOffData[$value['exam_id']]['cutOffData']['round_table_arr'][$roundIndex][$quota]['type'] = $quota;

			}
		}
		
		$course12thCutOffData = array_values($course12thCutOffData);
		$courseExamCutOffData = array_values($courseExamCutOffData);
		$formattedData = array();
		foreach ($courseExamCutOffData as $key => $exam) {
			// $formattedData[$key] = $exam;
			foreach ($courseExamCutOffData[$key]['cutOffData']['round_table_arr'] as $key1 => $roundData) {
				foreach ($roundData as $key2 => $round) {
					$courseExamCutOffData[$key]['cutOffData']['round_table_arr'][$key1][] = $round;
					unset($courseExamCutOffData[$key]['cutOffData']['round_table_arr'][$key1][$key2]);
				}
			}
			// unset($formattedData[$key]['cutOffData']['round_table_arr']);
		}
		// _p($courseExamCutOffData); die;
		return array('courseExamCutOff' => $courseExamCutOffData, 'course12thCutOff' => $course12thCutOffData);
	}

	function getFormattedPartnerDetails($data,$instituteNames) {
		$formattedPartnerDetails = array();
		
		$formattedPartnerDetails['course_partner_institute_flag'] = 0;
		foreach($data['coursePartnerForm'] as $key => $partnerDetails) {
			$type = ($partnerDetails['type'] == 'exit') ? 'partnerInstituteFormArrExit' : 'partnerInstituteFormArr';
			$temp                   = array();
			$temp['duration_value'] = $partnerDetails['duration_value'];
			$temp['duration_unit']  = $partnerDetails['duration_unit'];
			$temp['partner_name']   = $instituteNames[$partnerDetails['partner_type']."_".$partnerDetails['partner_id']];
			$temp['partner_type']   = $partnerDetails['partner_type'];
			$temp['partner_id']     = $partnerDetails['partner_id'];
			$temp['scope']          = $partnerDetails['scope'];
			$formattedPartnerDetails[$type][] = $temp;
			// $data['coursePartnerForm'];
			$formattedPartnerDetails['course_partner_institute_flag'] = 1;
		}
		return $formattedPartnerDetails;
	}

	function getAbroadUniversityNamesById($universityIds,$returnData = array('name'),$returnNormal=false,$statusCheck = true){
		return $this->coursepostingmodel->getAbroadUniversityNamesById($universityIds, $returnData ,$returnNormal,$statusCheck);
	}

	function getBasicCourseAndParentData($courseId,$status = array('live','draft')){
		return $this->coursepostingmodel->getBasicCourseAndParentData($courseId,$status);
	}



	public function saveNotificationMailerData(&$courseData){

        if($courseData['isScript'] == 'false'){
                $cmsUserInfo = modules::run('enterprise/Enterprise/cmsUserValidation');
        }
        else{
                $cmsUserInfo['validity'][0]['displayname'] == 'cmsAdmin';
        }
	   	$this->CI->load->builder("nationalCourse/CourseBuilder");
		$builder          = new CourseBuilder();
		$courseRepository = $builder->getCourseRepository();
		$courseObject = $courseRepository->find($courseData['courseId'],array('basic','location'));	
		$courseOldName = $courseObject->getName();
		$courseOldPrimaryId = $courseObject->getInstituteId();    
		$courseOldPrimaryHierarchy = implode('::',$courseObject->getPrimaryHierarchy());
		$courseOldLocations = $courseObject->getLocations();
		$courseOldMainLocations = $courseObject->getMainLocation()->getLocationId();
		$dataToStore = array();

		$dataToStore['courseId'] = $courseData['courseId'];
		$dataToStore['courseName'] = $courseOldName;
		$dataToStore['listingType'] = 'course';
    	$dataToStore['userId'] = $courseData['userId'];
    	$dataToStore['userName'] = $cmsUserInfo['validity'][0]['displayname'];
    	$dataToStore['action_type'] = 'save as '.$courseData['saveAs'];
		if(strcmp($courseOldName,$courseData['courseInfo']['name'])){
			$temp['field'] = 'courseName';
			$temp['oldValue'] = $courseOldName;
			$temp['newValue'] = $courseData['courseInfo']['name'];
			$dataToStore['section'][]= $temp;
		}
		if($courseOldPrimaryId != $courseData['courseInfo']['primary_id']){
		  	$this->CI->load->builder("nationalInstitute/InstituteBuilder");
	        $instituteBuilder = new InstituteBuilder();
	        $instituteRepo = $instituteBuilder->getInstituteRepository();	
			$instituteObjs = $instituteRepo->findMultiple(array($courseOldPrimaryId,$courseData['courseInfo']['primary_id']));

			$temp['field'] = 'primaryInstituteName';
			$temp['oldValue'] = $instituteObjs[$courseOldPrimaryId]->getName();
			$temp['newValue'] = $instituteObjs[$courseData['courseInfo']['primary_id']]->getName();	
			$dataToStore['section'][]= $temp;
		}
		$newLocationIds = array();
		$newMainLocation ; 
		foreach($courseData['courseLocation']['courseLocationData'] as $key => $locationData){
			if($locationData['is_main'] == 1){
			$newMainLocation = 	$locationData['listing_location_id'];
			}
			$newLocationIds[] = $locationData['listing_location_id'];
		}
		foreach ($courseOldLocations as $locationId => $locationObject) {
			$oldLocationIds[] = $locationId;	
		}
		sort($newLocationIds);
		sort($oldLocationIds);
		if(sizeof($newLocationIds)!=sizeof($oldLocationIds) || array_diff($newLocationIds, $oldLocationIds) || $courseOldMainLocations != $newMainLocation){
			$temp['field'] = 'locations';
			$temp['oldValue'] = implode('::', $oldLocationIds);
			$temp['newValue'] = implode('::', $newLocationIds);
			$dataToStore['section'][]= $temp;			
		}
		//_P($courseOldPrimaryHierarchy);die;
		$courseData['primaryHierarchy'] = implode('::', $courseData['primaryHierarchy']);
		if($courseData['primaryHierarchy'] != $courseOldPrimaryHierarchy){
			$temp['field'] = 'Hierarchy';
			$temp['oldValue'] = $courseOldPrimaryHierarchy;
			$temp['newValue'] =  $courseData['primaryHierarchy'];
			$dataToStore['section'][]= $temp;	
		}

		if(!empty($dataToStore['section'])){
			$this->coursepostingmodel->saveDataForNotificationMailer($dataToStore);
		}
	}

	function formatNotificationMailerData($data){
		$stremIdList = array();
		$substreamIdList = array();
		$specializationIdList = array();
		$locationIdList = array();
		$userIds = array();
	 	foreach($data as $courseData){
			$courseWiseData[$courseData['listing_id']][$courseData['updated_on']][] = $courseData;	
			$courseIds[] = $courseData['listing_id'];
			if($courseData['field'] == 'Hierarchy'){
				$oldHierarchy		 	= 		explode('::', $courseData['old_value']);
				$newHierarchy		 	= 		explode('::', $courseData['new_value']);
				$stremIds[]		 	 	= 		$oldHierarchy[0];$stremIds[] = $newHierarchy[0]; 
				$substreamIds[]		 	= 		$oldHierarchy[1];$substreamIds[] = $newHierarchy[1]; 
				$specializationIds[]   	= 		$oldHierarchy[2];$specializationIds[] = $newHierarchy[2]; 
			}
			if($courseData['field'] == 'locations'){
				$oldLocationId = explode('::', $courseData['old_value']);
				$newLocationId = explode('::', $courseData['new_value']);
				$locationIds = array_unique((array_merge($oldLocationId,$newLocationId)));	
				$locationIdList = array_unique((array_merge($locationIdList,$locationIds)));
			}
	 	}
	 	$stremIdList = array_unique(array_filter($stremIds));
	 	$substreamIdList  = array_unique(array_filter($substreamIds));
	 	$specializationIdList =  array_unique(array_filter($specializationIds));
	 		
	 		
	 	$this->CI->load->builder("nationalInstitute/InstituteBuilder");
	 	$builder                     = new InstituteBuilder();
	 	$this->instituteRepo         = $builder->getInstituteRepository();
	 	$this->CI->load->builder("listingBase/ListingBaseBuilder");
	 	$listingBaseBuilder          = new ListingBaseBuilder();
	 	$this->institutepostingmodel = $this->CI->load->model('nationalInstitute/institutepostingmodel');
	 		
		$streamRepo     = $listingBaseBuilder->getStreamRepository();	
		$subStreamRepo  = $listingBaseBuilder->getSubstreamRepository();	
		$specializationRepo = $listingBaseBuilder->getSpecializationRepository();	

		if(!empty($stremIdList)){
			$streamObjects = $streamRepo->findMultiple($stremIdList);
		}
		if(!empty($substreamIdList)){
			$subStreamObjects = $subStreamRepo->findMultiple($substreamIdList);
		}
		if(!empty($specializationIdList)){
			$specializationObj = $specializationRepo->findMultiple($specializationIdList);
		}
		if(!empty($locationIdList)){
			$this->institutedetailsmodel = $this->CI->load->model("nationalInstitute/institutedetailsmodel");
			$locationData =  $this->institutedetailsmodel->getMultipleListingLocationData($locationIdList);
			foreach ($locationData as $listingLocationId => $data) {
				$locationName[$listingLocationId] = '<b>City:</b>'.$data['city_name'].' <b>state:</b>'.$data['state_name'].' <b>locality:</b>'.$data['locality_name'].'</br>';
			}
		}
		foreach($stremIdList as $key=>$stremId){
			$stremName[$stremId] = $streamObjects[$stremId]->getName(); 
		}
		foreach($substreamIdList as $key=>$substreamId){
			$substreamName[$substreamId] = $subStreamObjects[$substreamId]->getName(); 
		}
		foreach($specializationIdList as $key=>$specializationId){
			$specializationName[$specializationId] = $specializationObj[$specializationId]->getName(); 
		}
		$returnData['locationName']  = $locationName;
		$returnData['streamName']  = $stremName; 
		$returnData['substreamName']  = $substreamName;
		$returnData['specialisationName']  = $specializationName;
		$returnData['courseWiseData'] = $courseWiseData;
		return $returnData;
	}	
}

