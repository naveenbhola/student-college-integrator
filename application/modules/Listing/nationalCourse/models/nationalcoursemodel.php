<?php
require_once dirname(__FILE__).'/../../nationalInstitute/models/NationalListingModelAbstract.php';

class nationalcoursemodel extends NationalListingModelAbstract {
	private $status = 'live';
	function __construct() {
		parent::__construct();
		$this->load->config('nationalCourse/courseSectionConfig');
		global $courseSections;
		$this->defaultFilters = $courseSections;
	}

	function getData($courseId, $filters = '') {
		if(is_array($filters)) {
			$filters = implode('|', $filters);
		}

		Contract::mustBeNumericValueGreaterThanZero($courseId,'Course ID');

		$courseIds = array($courseId);
		$data = $this->_getFilterwiseConsolidatedData($courseIds,$filters);
		return array($courseId => $data);
	}

	function getDataForMultipleCourses($courseIds, $filters = '') {
        
        global $forceListingWriteHandle;
        if($forceListingWriteHandle) {
        	$this->db = $this->getWriteHandle();
        }

		Contract::mustBeNonEmptyArrayOfIntegerValues($courseIds,'Course IDs');

		$data = $this->_getFilterwiseConsolidatedData($courseIds,$filters);
		
		$data = $this->indexFilteredDataByListingIds($data,'course_id');
		
		return $data;
	}

	private function _getFilterwiseConsolidatedData($courseIds,$filters = '')
	{
		$validFilters = $this->validateFilters($filters);

		$data = array();
		foreach($validFilters as $filter){

			if(empty($courseIds) && empty($data['basic'])) {
				break;
			}

			switch($filter) {
				case 'basic':
					$data['basic'] = $this->_getBasicInfo($courseIds);
					break;
				case 'eligibility':
					$data['eligibility'] = $this->_getEligibilityInfo($courseIds);
					break;
				case 'location' : 
					$data['location'] = $this->_getLocationInfo($courseIds);
					break;
				/*case 'fees' : 
					$data['fees'] = $this->_getFeesInfo($courseIds);
					break;*/
				case 'course_type_information':
					$data['course_type_information'] = $this->_getCourseTypeInformation($courseIds);
					break;
				case 'placements_internships':
					$data['placements_internships'] = $this->_getPlacementsIntershipsInformation($courseIds);
					break;
				// case 'academic' : 
				// 	$data['academic'] = $this->_getAcademicInfo($courseIds);
				// case 'research_project':
				case 'usp':
					if(!isset($additionalData)){
						$additionalData = $this->_getAdditionalInfo($courseIds);
						foreach ($additionalData as $additionalDataRow) {
							$data[$additionalDataRow['description_type']][] = $additionalDataRow;
						}
					}
					break;
				// case 'event' :
				// 	$data['event'] = $this->_getEventInfo($courseIds);
				// 	break;
				case 'media' :
					$data['media'] = $this->_getMediaInfo($courseIds);
					break;
				case 'medium_of_instruction' : 
					$data['medium_of_instruction'] = $this->_getMediumOfInstruction($courseIds);
					break;
				case 'partner' : 
					$data['partner'] = $this->_getPartnerInfo($courseIds);
					break;
				
			}
		}
		
		return $data;
	}

	private function _getBasicInfo(&$courseIds){
			$listingMainStatus = $this->config->item("listingMainStatus");
			$listingsMainLiveStatus = $listingMainStatus['live'];

				$sql = "SELECT c.course_id,
					   c.name,
					   c.primary_id,
					   c.parent_id,
					   c.parent_type,
					   c.course_order,
					   i.name as primary_name,
					   i1.name as parent_name,
					   i1.short_name as parent_short_name,
					   i.short_name as primary_short_name,
					   c.primary_type,
					   c.course_variant, 
					   c.education_type, 
					   c.lateral, 
					   c.twinning, 
					   c.dual, 
					   c.integrated, 
					   c.executive, 
					   c.delivery_method, 
					   c.affiliated_university_id, 
					   c.affiliated_university_scope, 
					   c.affiliated_university_name, 
					   c.difficulty_level, 
					   b.brochure_url,
					   b.brochure_year,
					   b.brochure_size,
					   b.is_auto_generated as is_brochure_auto_generated,
					   c.duration_unit,
					   c.duration_value,
					   c.duration_disclaimer,
					   c.disabled_url,
					   c.total_seats,
					   c.time_of_learning,
					   NOW() as object_creation_date
					    FROM shiksha_courses c 
					    JOIN listings_main lm ON (lm.listing_type_id = c.course_id AND lm.listing_type = 'course')
					    LEFT JOIN shiksha_institutes i ON (i.listing_id = c.primary_id)
					    LEFT JOIN shiksha_institutes i1 ON (i1.listing_id = c.parent_id)
					    LEFT JOIN shiksha_listings_brochures b ON (c.course_id = b.listing_id AND b.cta = 'brochure' AND b.listing_type = 'course' AND b.status='live')
				WHERE c.status='live' AND c.course_id IN (?) AND c.status = 'live' AND i.status='live' AND i1.status = 'live' AND lm.status = ?" ;
		
		$basicData = $this->db->query($sql,array($courseIds,$listingsMainLiveStatus))->result_array();
		// _p($courseIds); 
		$courseIdMap = array_flip($courseIds);
		foreach ($basicData as $basicDataRow) {
			$courseIdsFromDb[] = $basicDataRow['course_id'];
		}
		// _p($courseIdsFromDb);
		$deletedCourses = array_diff($courseIds, $courseIdsFromDb);
		$courseIds = array_values(array_diff($courseIds, $deletedCourses));
		// _p($basicData); die;
		// _p($courseIds); die;
		if(empty($courseIds)) {
			return array();
		}
		$attributeNamesInSmall = array("education type", "medium/delivery method","time of learning","difficulty / skill level");

		$sql = "SELECT * FROM base_attribute_list WHERE status = 'live' AND LOWER(attribute_name) IN (?)";
		$attributeData = $this->db->query($sql,array($attributeNamesInSmall))->result_array();

		$finalAttributeData = array();
		foreach ($attributeData as $key => $value) {

			$finalAttributeData[$value['value_id']] = $value['value_name'];
		}
	
		$basicInfo = array();
		foreach ($basicData as $key => $value) {

			if(!empty($value['education_type']) && array_key_exists($value['education_type'], $finalAttributeData)){
				$value['education_type_name'] = $finalAttributeData[$value['education_type']];
			}else{
				$value['education_type_name'] = "";
			}

			if(!empty($value['delivery_method']) && array_key_exists($value['delivery_method'], $finalAttributeData)){
				$value['delivery_method_name'] = $finalAttributeData[$value['delivery_method']];
			}else{
				$value['delivery_method_name'] = "";
			}

			if(!empty($value['time_of_learning']) && array_key_exists($value['time_of_learning'], $finalAttributeData)){
				$value['time_of_learning_name'] = $finalAttributeData[$value['time_of_learning']];
			}else{
				$value['time_of_learning_name'] = "";
			}

			if(!empty($value['difficulty_level']) && array_key_exists($value['difficulty_level'], $finalAttributeData)){
				$value['difficulty_level_name'] = $finalAttributeData[$value['difficulty_level']];
			}else{
				$value['difficulty_level_name'] = "";
			}

			if($value['primary_type'] != "university"){
				$value['offered_by_id'] = $value['primary_id'];
				$value['offered_by_name'] = $value['primary_name'];
				if(!empty($value['primary_short_name'])){
					$value['offered_by_short_name'] = $value['primary_short_name'];
				}else{
					$value['offered_by_short_name'] = $value['primary_name'];
				}
				

			}else if($value['parent_type'] != "university"){
				$value['offered_by_id'] = $value['parent_id'];
				$value['offered_by_name'] = $value['parent_name'];
				if(!empty($value['parent_short_name'])){
					$value['offered_by_short_name'] = $value['parent_short_name'];
				}else{
					$value['offered_by_short_name'] = $value['parent_name'];
				}
				

			}else{
				$value['offered_by_id'] = "";
				$value['offered_by_name'] = "";
				$value['offered_by_short_name'] = "";
			}

			$basicInfo[$value['course_id']] = $value;
			$basicInfo[$value['course_id']]['education_type'] = array('id'=>$value['education_type'],'name' => $value['education_type_name']);
			$basicInfo[$value['course_id']]['delivery_method'] = array('id'=>$value['delivery_method'],'name' => $value['delivery_method_name']);
			$basicInfo[$value['course_id']]['time_of_learning'] = array('id'=>$value['time_of_learning'],'name' => $value['time_of_learning_name']);
			$basicInfo[$value['course_id']]['difficulty_level'] = array('id'=>$value['difficulty_level'],'name' => $value['difficulty_level_name']);
			unset($basicInfo[$value['course_id']]['education_type_name']);
			unset($basicInfo[$value['course_id']]['delivery_method_name']);
			unset($basicInfo[$value['course_id']]['time_of_learning_name']);
			unset($basicInfo[$value['course_id']]['difficulty_level_name']);
		}
		
		// Fetch College Reviews Count
		$sql 	= "SELECT count(*) as count,
				  CMS.courseId
				  FROM CollegeReview_MappingToShikshaInstitute CMS,
				  CollegeReview_MainTable CM 
				  WHERE CMS.courseId IN (?)
				  AND CMS.reviewId = CM.id 
				  AND CM.status = 'published'
				  GROUP by CMS.courseId
				  ";

		$reviewsInfo = $this->db->query($sql,array($courseIds))->result_array();

		// Populate it in main Data
		foreach ($reviewsInfo as $key => $value) {
			$basicInfo[$value['courseId']]['review_count'] = $value['count'];
		}

		// Set count as 0 for which no data is found
		foreach ($courseIds as $value) {
			if(empty($basicInfo[$value]['review_count'])){
				$basicInfo[$value]['review_count'] = 0;
			}
		}


		// Fetch Questions Count
		$sql = "SELECT count(*) as count,
				qlr.courseId
				FROM questions_listing_response qlr
				WHERE qlr.status = 'live'
				AND qlr.courseId IN (?)
				GROUP BY qlr.courseId";

		$questionsCountInfo = $this->db->query($sql,array($courseIds))->result_array();

		// Populate it in main Data
		foreach ($questionsCountInfo as $key => $value) {
			$basicInfo[$value['courseId']]['questions_count'] = $value['count'];
		}

		// Set count as 0 for which no data is found
		foreach ($courseIds as $value) {
			if(empty($basicInfo[$value]['questions_count'])){
				$basicInfo[$value]['questions_count'] = 0;
			}
		}

		
		$sql = "SELECT lm.listing_type_id,
					   lm.pack_type,
					   lm.listing_seo_url, 
					   lm.listing_seo_title,
					   lm.listing_seo_description,
					   lm.last_modify_date,
					   lm.username,
					   lm.expiry_date
					   FROM listings_main lm
				WHERE lm.status = ?  AND lm.listing_type = 'course' AND lm.listing_type_id IN (?) ";

		$listingMainInfo = $this->db->query($sql,array($listingsMainLiveStatus,$courseIds))->result_array();


		foreach($listingMainInfo as $val){
				$basicInfo[$val['listing_type_id']]['pack_type'] = $val['pack_type'];
				$basicInfo[$val['listing_type_id']]['listing_seo_url'] = $val['listing_seo_url'];
				$basicInfo[$val['listing_type_id']]['seo_title'] = $val['listing_seo_title'];
				$basicInfo[$val['listing_type_id']]['seo_description'] = $val['listing_seo_description'];
				$basicInfo[$val['listing_type_id']]['last_modify_date'] = $val['last_modify_date'];
				$basicInfo[$val['listing_type_id']]['username'] = $val['username'];
				$basicInfo[$val['listing_type_id']]['expiry_date'] = ($val['expiry_date'] == '0000-00-00 00:00:00') ? NULL : $val['expiry_date'];

		}


		$locationData = $this->_getLocationInfo($courseIds, true);
		foreach ($locationData as $key => $value) {
			$basicInfo[$value['course_id']]['main_location'] = $value;
		}
		
		$sql = "SELECT sci.course_id, 
					sci.attribute_value_id, 
					sci.info_type,
					bal.value_name as attribute_value_name
					FROM shiksha_courses_additional_info sci 
					LEFT JOIN base_attribute_list bal 
					ON (sci.attribute_value_id = bal.value_id and bal.status = 'live')
				WHERE sci.course_id IN (?) AND sci.status='live' AND sci.info_type ='recognition' order by attribute_value_name";
		$courseAdditionInfo = $this->db->query($sql,array($courseIds))->result_array();

		foreach($courseAdditionInfo as $key => $value) {
			$basicInfo[$value['course_id']]['recognition'][] = array('id' => $value['attribute_value_id'], 'name' => $value['attribute_value_name']);
		}
		
		$sql = "SELECT scf.fees_value,
					   scf.fees_unit,
					   scf.category,
					   scf.period,
					   scf.fees_type,
					   scf.course_id,
					   scf.total_includes,
					   scf.fees_disclaimer,
					   cur.currency_code as fees_unit_name
					   FROM shiksha_courses_fees scf
					   LEFT JOIN currency cur ON (cur.id = scf.fees_unit)
					   WHERE scf.status = 'live' AND scf.period = 'overall' 
					   AND scf.fees_type = 'total' AND scf.listing_location_id = -1
					   AND scf.course_id IN (?)";
		
		$courseFeesInfo = $this->db->query($sql,array($courseIds))->result_array();
		
		foreach ($courseFeesInfo as $key => $value) {
			if(isset($value['fees_value'])){
				$temp = array();
				$temp['fees_value'] = $value['fees_value'];
				$temp['fees_unit'] = $value['fees_unit'];
				$temp['fees_unit_name'] = $value['fees_unit_name'];
				$temp['category'] = $value['category'];
				$temp['fees_type'] = $value['fees_type'];
				$temp['period'] = $value['period'];	
				$temp['fees_disclaimer'] = $value['fees_disclaimer'];	
				$total_includes = trim($value['total_includes']);
				$temp['total_includes'] = $this->_generateTotalInclude($total_includes);
				$basicInfo[$value['course_id']]['fees'][] = $temp;
			}			
		}
		
		return $basicInfo;
	}

	private function _getEligibilityInfo($courseIds) {
		$sql = "SELECT sce.course_id, 
					sce.exam_id, 
					epm.name as exam_name,
					sce.exam_name as exam_name_other,
					sce.category,
					sce.value,
					sce.unit,
					sce.max_value
					FROM shiksha_courses_eligibility_exam_score sce 
					LEFT JOIN exampage_main epm
					ON sce.exam_id = epm.id and epm.status = 'live'
				WHERE sce.course_id IN (?) AND sce.status='live'";
		$eligibilityArr = $this->db->query($sql,array($courseIds))->result_array();
		$eligibilityData = array();
		foreach($eligibilityArr as $key => $value) {
			if($value['exam_id'] == 0){
				$value['exam_name'] = $value['exam_name_other'];				
			}
			unset($value['exam_name_other']);
			$eligibilityData[] = $value;
		}
		
		return $eligibilityData;
	}

	private function _getLocationInfo($courseIds, $mainLocation = false) {
		$sql = "SELECT scl.course_id, 
					sil.listing_location_id, 
					sil.state_id,  
					sil.city_id,  
					sil.locality_id,  
					scl.is_main,
					scf.fees_value,
					scf.fees_unit,
					scf.fees_type,
					scf.category,
					scf.period,
					scf.fees_disclaimer,
					scf.total_includes,
					cur.currency_code as fees_unit_name
					FROM shiksha_courses_locations scl 
				JOIN shiksha_institutes_locations sil 
					ON sil.listing_location_id = scl.listing_location_id
				LEFT JOIN shiksha_courses_fees scf 
					ON scf.listing_location_id = scl.listing_location_id AND scf.status = 'live' AND scf.fees_type = 'total'  and scf.period = 'overall' AND scf.course_id = scl.course_id
				LEFT JOIN currency cur
				ON scf.fees_unit = cur.id
				WHERE sil.status = 'live' 
					AND scl.status='live' 
					AND scl.course_id IN (?) ";

		if($mainLocation){
			$sql .= ' AND scl.is_main = 1';
		}
		$locationsArr = $this->db->query($sql,array($courseIds))->result_array();

		$locations = array();
		foreach($locationsArr as $key => $value) {
			if(empty($feesArray[$value['course_id']."_".$value['listing_location_id']])){
				$feesArray[$value['course_id']."_".$value['listing_location_id']] = array();
			}
			$locations[$value['course_id']."_".$value['listing_location_id']] = $value;
			$stateIds[$value['state_id']]                               	= $value['state_id'];
			$cityIds[$value['city_id']]                                		= $value['city_id'];
			$localityIds[$value['locality_id']]                            	= $value['locality_id'];
			$keyToBeUsed = $value['course_id']."_".$value['listing_location_id'];
			if(isset($value['fees_value'])){
				$locations[$keyToBeUsed]['fees'] = array();
				$locations[$keyToBeUsed]['fees']['fees_value'] = $value['fees_value'];
				$locations[$keyToBeUsed]['fees']['fees_unit'] = $value['fees_unit'];
				$locations[$keyToBeUsed]['fees']['fees_unit_name'] = $value['fees_unit_name'];
				$locations[$keyToBeUsed]['fees']['fees_type'] = $value['fees_type'];
				$locations[$keyToBeUsed]['fees']['category'] = $value['category'];
				$locations[$keyToBeUsed]['fees']['period'] = $value['period'];	
				$locations[$keyToBeUsed]['fees']['fees_disclaimer'] = $value['fees_disclaimer'];	
				$total_includes = $value['total_includes'];
				$locations[$keyToBeUsed]['fees']['total_includes'] = $this->_generateTotalInclude($total_includes);
				
				$feesArray[$keyToBeUsed][] = $locations[$keyToBeUsed]['fees'];
				$locations[$keyToBeUsed]['fees'] = $feesArray[$keyToBeUsed];
			}
			
			unset($locations[$value['course_id']."_".$value['listing_location_id']][fees_value]);
			unset($locations[$value['course_id']."_".$value['listing_location_id']][fees_unit]);
			unset($locations[$value['course_id']."_".$value['listing_location_id']][fees_unit_name]);
			unset($locations[$value['course_id']."_".$value['listing_location_id']][fees_type]);
			unset($locations[$value['course_id']."_".$value['listing_location_id']][period]);
			unset($locations[$value['course_id']."_".$value['listing_location_id']][category]);
			unset($locations[$value['course_id']."_".$value['listing_location_id']][total_includes]);
			unset($locations[$value['course_id']."_".$value['listing_location_id']][fees_disclaimer]);

		}
		$stateIds    = array_unique(array_filter(array_values($stateIds)));
		$cityIds     = array_unique(array_filter(array_values($cityIds)));
		$localityIds = array_unique(array_filter(array_values($localityIds)));

		$statesData     = array();
		$citiesData     = array();
		$localitiesData = array();

		if($stateIds){
			$sql = "SELECT state_id,state_name FROM stateTable WHERE state_id IN (?)";
			$data = $this->db->query($sql,array($stateIds))->result_array();			

			foreach ($data as $value) {
				$statesData[$value['state_id']] = $value['state_name'];
			}
		}

		if($cityIds){
			$sql = "SELECT city_id,city_name FROM countryCityTable WHERE city_id IN (?)";
			$data = $this->db->query($sql,array($cityIds))->result_array();			

			foreach ($data as $value) {
				$citiesData[$value['city_id']] = $value['city_name'];
			}
		}

		if($localityIds){
			$sql = "SELECT localityId,localityName FROM localityCityMapping WHERE localityId IN (?)";
			$data = $this->db->query($sql,array($localityIds))->result_array();			

			foreach ($data as $value) {
				$localitiesData[$value['localityId']] = $value['localityName'];
			}
		}

		foreach ($locations as &$value) {
			if($value['state_id'])
				$value['state_name'] = $statesData[$value['state_id']];

			if($value['city_id'])
				$value['city_name'] = $citiesData[$value['city_id']];

			if($value['locality_id'])
				$value['locality_name'] = $localitiesData[$value['locality_id']];
		}

		$sql = "SELECT con.listing_location_id,
					   con.website_url,
					   con.address,
					   con.latitude,
					   con.longitude,
					   con.admission_contact_number,
					   con.admission_email,
					   con.generic_contact_number,
					   con.generic_email,
					   con.listing_id
					    FROM 
					    shiksha_listings_contacts con
				WHERE con.status='live' AND con.listing_type ='course' AND con.listing_id IN (?)";

		$contactsResult = $this->db->query($sql,array($courseIds))->result_array();
		$contactDetails = array();
		foreach ($contactsResult as $contactRow) {
			$contactDetails[$contactRow['listing_id']."_".$contactRow['listing_location_id']] = $contactRow;
			
		}

		foreach ($locations as $key=>&$locationRow) {
			$locationRow['contact_details'] = $contactDetails[$key];
		}
		return $locations;
	}

	/*function _getFeesInfo($courseIds) {
		
		$sql = "SELECT scf.course_id, 
					scf.listing_location_id, 
					scf.fees_value, 
					cur.id AS fees_unit_id , 
					cur.currency_name as unit_name
				FROM shiksha_courses_fees scf 
				JOIN currency cur ON scf.fees_unit = cur.id 
				WHERE course_id = ? 
					AND status = 'live' 
					AND fees_type = 'total' 
					AND category = 'general' 
					AND period = 'overall' ";
	
		$result = $this->db->query($sql,$courseIds)->result_array();
		return $result;
	}
*/
	function _getCourseTypeInformation($courseIds) {
		$sql = "SELECT sct.course_id, 
					sct.credential, 
					sct.type, 
					sct.course_level, 
					sct.base_course, 
					sct.stream_id, 
					sct.substream_id, 
					sct.specialization_id, 
					sct.primary_hierarchy,
					bal.value_name as credential_name,
					bal1.value_name as course_level_name
					FROM shiksha_courses_type_information sct 
					LEFT JOIN base_attribute_list bal
					ON(sct.credential = bal.value_id AND bal.status = 'live')
					LEFT JOIN base_attribute_list bal1
					ON(sct.course_level = bal1.value_id AND bal1.status = 'live')
				WHERE sct.course_id IN (?) AND sct.status='live';";
		
		$courseTypeInformation = $this->db->query($sql,array($courseIds))->result_array();
		
		foreach($courseTypeInformation as $key => $value) {
			$coursetype = $value['type'].'_course';
			if($value['type'] == 'entry'){
				$counter = $value['course_id']."_0";
			}else{
				$counter = $value['course_id']."_1";
			}

			$basicInfo[$counter]['course_id'] = $value['course_id'];
			$basicInfo[$counter]['type'] = $value['type'];
			$basicInfo[$counter]['credential'] = array('id'=>$value['credential'],'name' => $value['credential_name']);
			$basicInfo[$counter]['course_level'] = array('id' => $value['course_level'],'name' => $value['course_level_name']);
			$basicInfo[$counter]['base_course'] = $value['base_course'];
			$basicInfo[$counter]['hierarchy'][] = array('stream_id' => $value['stream_id'],
																				'substream_id' => $value['substream_id'],
																				'specialization_id' => $value['specialization_id'],
																				'primary_hierarchy' => $value['primary_hierarchy']);
		}	
		$basicInfo = array_values($basicInfo);
		return $basicInfo;	
	}


	function _getPlacementsIntershipsInformation($courseIds) {
		$sql = "SELECT scpi.course_id, 
					scpi.type, 
					scpi.batch_year, 
					scpi.course, 
					scpi.course_type, 
					scpi.percentage_batch_placed, 
					scpi.salary_unit, 
					scpi.min_salary, 
					scpi.median_salary, 
					scpi.avg_salary, 
					scpi.max_salary,
					scpi.total_international_offers, 
					scpi.max_international_salary,
					scpi.max_international_salary_unit, 
					scpi.report_url,
					cur1.currency_code as salary_unit_name,
					cur2.currency_code as max_international_salary_unit_name
					FROM  shiksha_courses_placements_internships scpi 
					LEFT JOIN currency cur1 ON(scpi.salary_unit = cur1.id)
					LEFT JOIN currency cur2 ON(scpi.max_international_salary_unit = cur2.id)
				WHERE scpi.course_id IN (?) AND scpi.status='live';";
		
		$placementsIntershipsData = $this->db->query($sql,array($courseIds))->result_array();

		foreach($placementsIntershipsData as $key => $value) {
			
			if($value['type'] == 'placements'){
				$counter = $value['course_id']."_0";
			}else{
				$counter = $value['course_id']."_1";
			}
			if(!empty($value['percentage_batch_placed']) || !empty($value['salary_unit']) 
				|| !empty($value['min_salary']) || !empty($value['median_salary'])
				|| !empty($value['avg_salary']) || !empty($value['max_salary'])
				|| !empty($value['total_international_offers']) || !empty($value['max_international_salary'])
				|| !empty($value['max_international_salary_unit']) || !empty($value['max_international_salary_unit_name'])
				|| !empty($value['report_url'])  || !empty($value['salary_unit_name'])
				) {
				$finalResult[$counter]['course_id'] = $value['course_id'];
				$finalResult[$counter]['type'] = $value['type'];
				$finalResult[$counter]['batch_year'] = $value['batch_year'];
				$finalResult[$counter]['course'] = $value['course'];
				$finalResult[$counter]['course_type'] = $value['course_type'];
				$finalResult[$counter]['percentage_batch_placed'] = $value['percentage_batch_placed'];
				$finalResult[$counter]['salary_unit'] = $value['salary_unit'];
				$finalResult[$counter]['min_salary'] = $value['min_salary'];
				$finalResult[$counter]['median_salary'] = $value['median_salary'];
				$finalResult[$counter]['avg_salary'] = $value['avg_salary'];
				$finalResult[$counter]['max_salary'] = $value['max_salary'];
				$finalResult[$counter]['total_international_offers'] = $value['total_international_offers'];
				$finalResult[$counter]['max_international_salary'] = $value['max_international_salary'];
				$finalResult[$counter]['max_international_salary_unit'] = $value['max_international_salary_unit'];
				$finalResult[$counter]['max_international_salary_unit_name'] = $value['max_international_salary_unit_name'];
				$finalResult[$counter]['salary_unit_name'] = $value['salary_unit_name'];
				$finalResult[$counter]['report_url'] = $value['report_url'];
			}
			
		}	
		$finalResult = array_values($finalResult);
		return $finalResult;	
	}
	
	public function getCourseDetailById($courseId){

		if($courseId < 0) {
			return;
		}

		$sql = "select course_id from shiksha_courses where course_id = ? and status='live'";
		$result = $this->db->query($sql,array($courseId))->row_array();
		
		return $result;
	}

	public function getPaidCourses()
	{
		$sql = "SELECT listing_type_id
				FROM listings_main
				WHERE listing_type = 'course'
				AND status = 'live' AND pack_type IN (1, 2, 375)";
		$result = $this->db->query($sql)->result_array();
		return $this->getColumnArray($result, 'listing_type_id');		
	}
	
	public function getCourseReviewCount($courseId = NULL) {
		if(empty($courseId)){
			return 0;
		}
		$count = 0;
	
		$queryCmd 	= "SELECT count( * ) as count FROM CollegeReview_MappingToShikshaInstitute CMS, CollegeReview_MainTable CM WHERE CMS.courseId = ? AND CMS.reviewId = CM.id AND CM.status = 'published' ";
		$query 		= $this->db->query($queryCmd, array($courseId));
		if($query->num_rows() > 0) {
			$row = (array)$query->row();
			$count = $row['count'];
		}
		return $count;
	}


	public function getCourseOrder($courseIds){
		$sql  = "SELECT course_id,
				course_order
				FROM shiksha_courses
				WHERE course_id IN (?)
				AND status = 'live'";

		$query = $this->db->query($sql,array($courseIds));

		$result = $query->result_array();

		$finalResult = array();
		foreach ($result as $key => $value) {
			$finalResult[$value['course_id']] = $value['course_order'];
		}
		return $finalResult;

	}

	public function getCourseListingHierarchy($courseId){
		
		$finalResult = array();
		$sql = "SELECT a.primary_id as listing_id, b.listing_type as listing_type,b.institute_specification_type,b.university_specification_type,b.is_autonomous,b.is_open_university,b.is_ugc_approved, b.is_aiu_membership FROM shiksha_courses a JOIN shiksha_institutes b ON(a.primary_id = b.listing_id) WHERE a.course_id = ? and a.status = 'live' AND b.status = 'live' LIMIT 1";
		$query = $this->db->query($sql,array($courseId));
		$result = $query->row_array();
		
		if(empty($result)){
			return $finalResult;
		}else{
			if($result['listing_type'] == "university"){
				$finalResult[$result['listing_type']][$result['listing_id']] = array();
				$finalResult[$result['listing_type']][$result['listing_id']]['listing_id'] = $result['listing_id'];
				$finalResult[$result['listing_type']][$result['listing_id']]['is_primary'] = 1;
				$finalResult[$result['listing_type']][$result['listing_id']]['university_specification_type'] = $result['university_specification_type'];
				$finalResult[$result['listing_type']][$result['listing_id']]['is_open_university'] = $result['is_open_university'];
				$finalResult[$result['listing_type']][$result['listing_id']]['is_ugc_approved'] = $result['is_ugc_approved'];
				$finalResult[$result['listing_type']][$result['listing_id']]['is_aiu_membership'] = $result['is_aiu_membership'];
			}else{
				$finalResult[$result['listing_type']][$result['listing_id']] = array();
				$finalResult[$result['listing_type']][$result['listing_id']]['is_primary'] = 1;
				$finalResult[$result['listing_type']][$result['listing_id']]['listing_id'] = $result['listing_id'];
				$finalResult[$result['listing_type']][$result['listing_id']]['institute_specification_type'] = $result['institute_specification_type'];
				$finalResult[$result['listing_type']][$result['listing_id']]['is_autonomous'] = $result['is_autonomous'];
			}
		}

		$listing_id = $result['listing_id'];
		$count = 0;
		while($listing_id > 0 && !empty($listing_id)){
			$count++;
			if($count > 7) break;
			$sql = "SELECT a.parent_listing_id as listing_id, a.parent_listing_type as listing_type,b.institute_specification_type,b.university_specification_type,b.name,b.is_autonomous,b.is_open_university,b.is_ugc_approved, b.is_aiu_membership  FROM shiksha_institutes a JOIN shiksha_institutes b ON(a.parent_listing_id=b.listing_id) where a.listing_id = ? and a.status = 'live' and b.status = 'live' LIMIT 1";
			$query = $this->db->query($sql,array($listing_id));
			$result = array();
			$result = $query->row_array();
			if(empty($result['listing_id'])) break;
			if($result['listing_type'] == "university"){
				$finalResult[$result['listing_type']][$result['listing_id']] = array();
				$finalResult[$result['listing_type']][$result['listing_id']]['is_primary'] = 0;
				$finalResult[$result['listing_type']][$result['listing_id']]['listing_id'] = $result['listing_id'];
				$finalResult[$result['listing_type']][$result['listing_id']]['university_specification_type'] = $result['university_specification_type'];
				$finalResult[$result['listing_type']][$result['listing_id']]['is_open_university'] = $result['is_open_university'];
				$finalResult[$result['listing_type']][$result['listing_id']]['is_ugc_approved'] = $result['is_ugc_approved'];
				$finalResult[$result['listing_type']][$result['listing_id']]['is_aiu_membership'] = $result['is_aiu_membership'];
			}else{
				$finalResult[$result['listing_type']][$result['listing_id']] = array();
				$finalResult[$result['listing_type']][$result['listing_id']]['is_primary'] = 0;
				$finalResult[$result['listing_type']][$result['listing_id']]['listing_id'] = $result['listing_id'];
				$finalResult[$result['listing_type']][$result['listing_id']]['institute_specification_type'] = $result['institute_specification_type'];
				$finalResult[$result['listing_type']][$result['listing_id']]['is_autonomous'] = $result['is_autonomous'];
			}
			$listing_id = $result['listing_id'];
		}

		return $finalResult;
	}

	/*
	 * Get contact details for course from institute
	 * Returns array
	 */
	public function getCourseContactDetailsFromInstitute($locationIds,$inst_id)
	{
		$results = $this->_getCourseContactDetailsFromInstitute($locationIds,$inst_id);
		if(!is_array($results) || count($results) == 0) {
			return array();
		}
		foreach ($results as $result) {
			$result_array[$result['listing_location_id']] = $result;
		}
		unset($results);
		return $result_array;
	}

		/*
	 * Get contact details defined at institute level for a location
	 */
	private function _getCourseContactDetailsFromInstitute($locationIds,$inst_id) {
		if(empty($inst_id)){return ;}
		$sql = "SELECT slc.listing_location_id,
					   slc.website_url,
					   slc.address,
					   slc.latitude,
					   slc.longitude,
					   slc.admission_contact_number,
					   slc.admission_email,
					   slc.generic_contact_number,
					   slc.generic_email
		        FROM  shiksha_listings_contacts slc 
		        WHERE slc.listing_id = ? 
		        AND slc.listing_type IN ('institute','university') 
		        AND slc. listing_location_id  IN (?) 
		        AND slc.status ='live' ";

		 $result = $this->db->query($sql,array($inst_id,$locationIds))->result_array();

		 return $result;
	}

	public function getCoursesForInstitutesByLevel($instituteIds,$courseLevel){
       if(!is_array($instituteIds) || empty($courseLevel)){
	        return;
	    }
            
            $sql="SELECT sc.primary_id, sc.course_id 
            FROM shiksha_courses sc 
            INNER JOIN shiksha_courses_type_information sctinfo
            ON sc.course_id = sctinfo.course_id
            WHERE sc.primary_id in (?)
            AND sc.status='live'
            AND sctinfo.course_level = ?
            AND sctinfo.status ='live'";

         return $this->db->query($sql,array($instituteIds,$courseLevel))->result_array();
	}
	  private function _generateTotalInclude($total_includes){
            $result = array();
            if(!empty($total_includes)){
                $total_includes = explode(";", $total_includes);
                $subArray = false;
                $key = 0;
                foreach ($total_includes as $includeName) {
                    if($subArray){
                        $result['Others'] = $includeName;
                    }else{
                        if(strtolower($includeName) == "others" && !$subArray){
                                $subArray = true;
                        }else{
                            $aKey = "value_".$key;
                            $result[$aKey] = $includeName;
                            $key++;
                        }

                    }

                }
            }
            if(empty($result)) {
                    return null;
            }
            return $result;
        }

	private function _getAdditionalInfo($courseIds){

		$sql = "SELECT attr.course_id,
					   attr.description,
					   attr.info_type as description_type
					 FROM shiksha_courses_additional_info attr WHERE attr.status='live' AND attr.course_id IN (?)";

		return $this->db->query($sql,array($courseIds))->result_array();
	}

	public function getLiveCoursesByCourseIds($courseIds){

		if(empty($courseIds)) {
			return;
		}

		$sql  = "SELECT course_id FROM shiksha_courses WHERE course_id IN (?) AND status = 'live'";
		$query = $this->db->query($sql,array($courseIds));
		$result = $query->result_array();

		$finalResult = array();
		foreach ($result as $value) {
			$finalResult[$value['course_id']] = $value['course_id'];
		}
		return $finalResult;

	}


	private function _getMediaInfo($courseIds){

		$instituteMediaInfo = array();

		$sql = "SELECT ".
					   " DISTINCT mloc.listing_id,".
					   " mloc.media_id,".
					   " mloc.media_type,".
					   " m.media_url,".
					   " m.media_title,".
					   " m.media_type,".
					   " m.media_order,".
					   " m.media_thumb_url,".
					   " m.media_order,".
					   " cm.course_id,".
					   " mloc.listing_location_id as listing_location_ids".
					   " FROM".
					   " shiksha_courses_medias_mapping cm JOIN shiksha_institutes_medias m". 
					   " ON (cm.media_id = m.media_id or  cm.media_id = -1)".
					   " JOIN shiksha_courses sc ON (sc.course_id = cm.course_id and sc.status = 'live') ".
					   " JOIN shiksha_institutes_media_locations_mapping mloc ".
					   " ON (mloc.media_id = m.media_id AND mloc.media_type = m.media_type AND mloc.listing_id = sc.primary_id AND m.status = 'live')".
					   " WHERE cm.course_id  IN (?) AND cm.status = 'live'".
					   " AND  mloc.listing_type IN ('institute', 'university')".
					   " AND mloc.status = 'live'";
		
		$result = $this->db->query($sql,array($courseIds))->result_array();
				
		foreach($result as $key=>$val){
			$mediaInfo[$val['listing_id']] [$val['media_id']][] = $val;
			$mediaIds[] = $val['media_id'];
		}

		$mediaIdArray = array_unique($mediaIds);
		
		foreach($mediaInfo as $key1=>$value){
			foreach($value as $key2=>$val2){
				foreach ($val2 as $key3 => $val3) {
					if($key3 == 0){
						$mediaInfo[$key1][$key2][0]['listing_location_ids'] = array($val3['listing_location_ids']);
					}else{
						$mediaInfo[$key1][$key2][0]['listing_location_ids'] = array_unique(array_merge(array($val3['listing_location_ids']), $mediaInfo[$key1][$key2][0]['listing_location_ids']));
						unset($mediaInfo[$key1][$key2][$key3]);
					}
				}
			}
		}

		if(!empty($mediaIdArray)){
			$sql = "SELECT mt.tag_id,mt.tag_type,lt.tags as tag_name,events.name as event_name,mt.listing_id,mt.media_id 
						FROM shiksha_institutes_media_tags_mapping mt
					LEFT JOIN
					listing_tags lt ON(mt.tag_id = lt.id AND lt.status='live' AND mt.tag_type='tag')
					LEFT JOIN
					shiksha_institutes_events events ON(mt.tag_id = events.id AND events.status='live' AND mt.tag_type='event')
		    		WHERE mt.status = 'live' AND mt.media_id IN (?)";

			$result = $this->db->query($sql,array($mediaIdArray))->result_array();
			
			foreach($result as $key => $val){
				$tagName = $val['tag_name'] != NULL ? $val['tag_name'] : ($val['event_name'] != NULL ? $val['event_name']  : "");
				$mediaInfo[$val['listing_id']][$val['media_id']][0]['tag_ids'][] = array("id"=>$val['tag_id'], "type"=>$val['tag_type'], "name"=> $tagName);
			}

			 foreach($mediaInfo as $listingId=>$media){
	        	foreach ($media as $key1 => $instituteMedia){
	        		foreach ($instituteMedia as $key2 => $mediaData){
	        			$instituteMediaInfo[] = $mediaData;
	        		}
	        	}
	        }
    	}

		return $instituteMediaInfo;
	}

	public function getInstituteIdsForCourses($courseIds)
	{
		if(!is_array($courseIds) || count($courseIds) == 0){
	        return array();
	    }
				
		$sql="SELECT sc.primary_id, sc.course_id 
		FROM shiksha_courses sc
		WHERE sc.course_id in (?)
		AND sc.status='live'";

		$rows = $this->db->query($sql,array($courseIds))->result_array();
		
		$result = array();
		foreach($rows as $row) {
			$result[$row['course_id']] = $row['primary_id'];
		}
		
        return $result;
	}

	private function _getMediumOfInstruction($courseIds) {
		$sql = "SELECT attr.course_id,
					   attr.attribute_value_id as id,
					   ba.value_name as name
					 FROM shiksha_courses_additional_info attr 
					 	JOIN base_attribute_list ba
					 	ON ba.value_id = attr.attribute_value_id
					  WHERE attr.status='live' AND ba.status='live' AND attr.course_id IN (?) AND info_type = ?";

		return $this->db->query($sql, array($courseIds,'instruction_medium'))->result_array();
	}

	private function _getPartnerInfo($courseIds) {
		$sql = "SELECT course_id,
						partner_id,
						partner_type,
						duration_value,
						duration_unit,
						scope
					 FROM shiksha_courses_partner_details scpd 
					  WHERE status='live' AND course_id IN (?)";

		return $this->db->query($sql,array($courseIds))->result_array();
	}

	function getCoursesWithoutBrochure() {
		$sql = "SELECT DISTINCT course_id
					FROM shiksha_courses sc
					LEFT JOIN shiksha_listings_brochures slb 
					ON slb.listing_id = sc.course_id AND slb.listing_type='course' AND slb.status='live' AND slb.cta = 'brochure'
					  WHERE sc.status='live' AND slb.listing_id is null;";
		return $this->getColumnArray($this->db->query($sql)->result_array(),'course_id');
	}

	public function getExpiringCourses($expiryDate, $courseType = 'ALL')
	{
        $this->db->select('listing_type_id,subscriptionId');
        $this->db->where('listing_type','course');
        $this->db->where('date(expiry_date)',$expiryDate);
        $this->db->where('status','live');

        switch($courseType) {
            case 'PAID':
            	$this->db->where_in('pack_type',array(GOLD_SL_LISTINGS_BASE_PRODUCT_ID, GOLD_ML_LISTINGS_BASE_PRODUCT_ID ,SILVER_LISTINGS_BASE_PRODUCT_ID));
            	break;
            case 'FREE':
            	$this->db->where_not_in('pack_type',array(GOLD_SL_LISTINGS_BASE_PRODUCT_ID, GOLD_ML_LISTINGS_BASE_PRODUCT_ID ,SILVER_LISTINGS_BASE_PRODUCT_ID));
                break;
            default:
            	break;
        }

        $results = $this->db->get('listings_main')->result_array();
        return $results;
	}

	public function getExpiringCoursesForAnInterval($dateToCheckFrom,$dateToCheckUpto,$courseType = 'ALL'){
        switch($courseType) {
            case 'PAID':
                $courseTypeClause = ' AND pack_type IN ('.GOLD_SL_LISTINGS_BASE_PRODUCT_ID.', '.GOLD_ML_LISTINGS_BASE_PRODUCT_ID.', '.SILVER_LISTINGS_BASE_PRODUCT_ID.') ';
                break;
            case 'FREE':
                $courseTypeClause = ' AND pack_type NOT IN ('.GOLD_SL_LISTINGS_BASE_PRODUCT_ID.', '.GOLD_ML_LISTINGS_BASE_PRODUCT_ID.', '.SILVER_LISTINGS_BASE_PRODUCT_ID.') ';
                break;
            default :
                $courseTypeClause = '';
                break;
        }

        $this->db->select('listing_type_id,subscriptionId');
        $this->db->where('listing_type','course');
        $this->db->where('date(expiry_date) <= ',$dateToCheckFrom);
        $this->db->where('date(expiry_date) >= ',$dateToCheckUpto);
        $this->db->where('status','live');

        switch($courseType) {
            case 'PAID':
            	$this->db->where_in('pack_type',array(GOLD_SL_LISTINGS_BASE_PRODUCT_ID, GOLD_ML_LISTINGS_BASE_PRODUCT_ID ,SILVER_LISTINGS_BASE_PRODUCT_ID));
            	break;
            case 'FREE':
            	$this->db->where_not_in('pack_type',array(GOLD_SL_LISTINGS_BASE_PRODUCT_ID, GOLD_ML_LISTINGS_BASE_PRODUCT_ID ,SILVER_LISTINGS_BASE_PRODUCT_ID));
                break;
            default:
            	break;
        }

        $results = $this->db->get('listings_main')->result_array();
        return $results;
	}

	public function getCoursesHavingZeroExpiryDate() {
		$sql =  "SELECT listing_type_id,subscriptionId 
                         FROM  listings_main
                         WHERE listing_type = 'course' AND
                         date(expiry_date) = '0000-00-00' AND
                         status = 'live'  AND pack_type IN (".GOLD_SL_LISTINGS_BASE_PRODUCT_ID.", ".GOLD_ML_LISTINGS_BASE_PRODUCT_ID.", ".SILVER_LISTINGS_BASE_PRODUCT_ID.")";
		$results = $this->db->query($sql)->result_array();
        return $results;

	}

	public function getCourseInfoToExpire($courseId,$dateToCheckFrom) {

		$sql =  "SELECT `listing_type_id`,`subscriptionId` 
                         FROM  `listings_main`
                         WHERE `listing_type` = 'course' AND
                         date(expiry_date) <= ? AND
                         listing_type_id = ? AND
                         status = 'live' AND pack_type IN ( '".GOLD_SL_LISTINGS_BASE_PRODUCT_ID."', '".GOLD_ML_LISTINGS_BASE_PRODUCT_ID."', '".SILVER_LISTINGS_BASE_PRODUCT_ID."')";
			 
		$results = $this->db->query($sql,array($dateToCheckFrom,$courseId))->result_array();
    	return $results;
	}

	public function getListingsByClientId($status,$clientId){
		$sql =  "SELECT listing_type, listing_type_id, listing_title
                 FROM  `listings_main`
                 WHERE  status = ?
                 AND username = ?";
	 
		$results = $this->db->query($sql,array($status,$clientId))->result_array();
		$data = array();
		foreach ($results as $key => $row) {
			if($row['listing_type'] == 'institute' || $row['listing_type'] == 'university_national'){
                $instituteIds[] = $row['listing_type_id'];
            } 
            else if($row['listing_type'] == 'course'){
                $courseIds[] = $row['listing_type_id'];
            }
            if($row['listing_type'] == 'university_national') {
				$listingTitles[$row['listing_type_id']] = $row['listing_title']."(University)";
			} else {
				$listingTitles[$row['listing_type_id']] = $row['listing_title'];	
			}
		}
		if($instituteIds)
			$data['institutesIds'] = $instituteIds;

		if($courseIds)
			$data['courseIds']     = $courseIds;

		if($listingTitles)
			$data['listingTitles'] = $listingTitles;
		
    	return $data;
	}

	public function getListingLocationDetails($instituteLocationIds){
		$sql =  "SELECT ilt.listing_location_id as institute_location_id,cct.city_name,lcm.localityName
				FROM shiksha_institutes_locations ilt
				LEFT JOIN countryCityTable cct ON cct.city_id = ilt.city_id
				LEFT JOIN localityCityMapping lcm ON lcm.localityId = ilt.locality_id
				WHERE listing_location_id IN (?) and ilt.status = 'live'";

        $results = $this->db->query($sql,array($instituteLocationIds))->result_array();

        $locationMap = array();
        // _p($results);die;
        foreach($results as $row) {
			$locationMap[$row['institute_location_id']] = array(
                                                            'city'=>$row['city_name'],
                                                            'locality'=>$row['localityName']
                                                            );
		}

		return $locationMap;
	}

	public function getInstituteToUniversityMapping($status,$instituteIds){
		$sql  = "SELECT DISTINCT ium.institute_id,
				    ium.university_id,
				    lm.listing_title,
				    ult.university_location_id,
				    cct.city_name
			     FROM institute_university_mapping ium
			     inner join listings_main lm
			     on ium.university_id = lm.listing_type_id
			     and lm.listing_type = 'university'
			     and lm.status = ?
			     inner join university_location_table ult
			     on ium.university_id = ult.university_id
			     and ult.status = lm.status
			     inner join countryCityTable cct
			     on ult.city_id = cct.city_id
			     where ium.institute_id IN (?)
			     and ium.status = lm.status"; 

		$results = $this->db->query($sql,array($status,$instituteIds))->result_array();

		$data = array();
		foreach ($results as $row) {		
			$data['instituteToUniversityMapping'][$row['institute_id']] = array(
										    'id' => $row['university_id'],
										    'name' => $row['listing_title'],
										    'location' => $row['university_location_id'],
										    'city' => $row['city_name']
										);
			if($row['university_location_id'] > 1 && !isset($universityLocationIds[$row['university_location_id']])) {
				$data['universityLocationIds'][$row['university_location_id']] = TRUE;
			}
		}

		return $data;		
	}

}
