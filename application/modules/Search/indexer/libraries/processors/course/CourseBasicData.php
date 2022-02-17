<?php 
require_once dirname(__FILE__).'/../DataAbstract.php';
class CourseBasicData extends DataAbstract{
	

	public function __construct()
	{
		$this->_CI = & get_instance();
		$this->_CI->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();
		$this->courseRepo = $courseBuilder->getCourseRepository();

		$this->_CI->load->library('listingBase/BaseAttributeLibrary');
		$this->baseAttributeLibrary = new BaseAttributeLibrary;
	}


	public function _getDataFromObject($courseObject,$customData)
	{
		$courseBasicData = array();
		$courseListingHieracyData = $customData['courseListingHieracyData'];
		$courseBasicData['nl_course_name'] = $courseObject->getName();
		$courseBasicData['nl_course_id'] = $courseObject->getId();
		$courseBasicData['nl_institute_id'] = $courseObject->getInstituteId();
		$courseBasicData['nl_course_offered_by_id'] = $courseObject->getOfferedById();
		$courseBasicData['nl_course_offered_by'] = $courseObject->getOfferedByShortName();
		if(!empty($courseBasicData['nl_course_offered_by_id'])){
			$courseBasicData['nl_course_offered_by_name_id_map'] = $courseObject->getOfferedByShortName().":".$courseObject->getOfferedById();	
		}
		
		$courseBasicData['nl_course_paid'] = $courseObject->isPaid() ? 1 : 0;
		$courseBasicData['twinning'] = $courseObject->isTwinning();
		$courseBasicData['lateral'] = $courseObject->isLateral();
		$courseBasicData['dual'] = $courseObject->isDual();
		$courseBasicData['executive'] = $courseObject->isExecutive();
		$courseBasicData['integrated'] = $courseObject->isIntegrated();
		$educationType = $courseObject->getEducationType();
		

		if(!empty($educationType)){
			$courseBasicData['nl_course_education_type_id'] = $courseObject->getEducationType()->getId();
			$courseBasicData['nl_course_education_type_name'] = $courseObject->getEducationType()->getName();
			$courseBasicData['nl_course_education_type_name_id_map'] = $courseObject->getEducationType()->getName().":".$courseObject->getEducationType()->getId();	
		}
		
		$deliveryMethod = $courseObject->getDeliveryMethod();
		if(!empty($deliveryMethod)){
			$courseBasicData['nl_course_delivery_method_id'] = $courseObject->getDeliveryMethod()->getId();
			$courseBasicData['nl_course_delivery_method_name_id_map'] = $courseObject->getDeliveryMethod()->getName().":".$courseObject->getDeliveryMethod()->getId()
			;
			$courseBasicData['nl_course_delivery_method_name'] = $courseObject->getDeliveryMethod()->getName();
	
		}
		
		$courseBasicData['nl_course_approval_id'] = array();
		$recognitions = $courseObject->getRecognition();
		if(!empty($recognitions)){
			foreach ($courseObject->getRecognition() as $attributeObject) {
				$courseBasicData['nl_course_approval_id'][] = $attributeObject->getId();
				$courseBasicData['nl_course_approval_name'][] = $attributeObject->getName();
				$courseBasicData['nl_course_approval_name_id_map'][] = $attributeObject->getName().":".$attributeObject->getId();
			}
		}
		return $courseBasicData;
		
	}

	public function _processData($courseBasicData,$customData){
		try{
			if(empty($courseBasicData)) return array();
			$courseListingHieracyData = $customData['courseListingHieracyData'];
			$attributesInformation = array();
			$courseTypeId = array();
			$courseTypeNameIdMap = array();
			$courseTypeName = array();

			$attributesToBeFetchedByName = array();
			$attributesToBeFetchedByValueId = array();

			if(isset($courseBasicData['twinning']) && $courseBasicData['twinning'] == 1) {
				$attributesToBeFetchedByName[] = "Twinning";
			}
			if(isset($courseBasicData['executive']) && $courseBasicData['executive'] == 1) {
				$attributesToBeFetchedByName[] = "Executive";
			}
			if(isset($courseBasicData['lateral']) && $courseBasicData['lateral'] == 1) {
				$attributesToBeFetchedByName[] = "Lateral Entry";	
			}
			if(isset($courseBasicData['integrated']) && $courseBasicData['integrated'] == 1) {
				$attributesToBeFetchedByName[] = "Integrated";
			}
			if(isset($courseBasicData['dual']) && $courseBasicData['dual'] == 1) {
				$attributesToBeFetchedByName[] = "Dual";
			}
			

			if(!empty($attributesToBeFetchedByName)){
				$attributesInformation = $this->baseAttributeLibrary->getValuesForAttributeByName($attributesToBeFetchedByName);
				foreach ($attributesInformation as $attrName => $value) {
					$tempData = array();
					$tempData = array_keys($value);
					$courseTypeId[] = $tempData[0];
					$courseTypeNameIdMap[] = $attrName.":".$tempData[0];
					$courseTypeName[] = $attrName;
				}
			}

			$courseBasicData['nl_course_type_id'] = $courseTypeId;
			$courseBasicData['nl_course_type_name'] = $courseTypeName;
			$courseBasicData['nl_course_type_name_id_map'] = $courseTypeNameIdMap;
			
			if(empty($courseBasicData['nl_course_type_id'])){
				$courseBasicData['nl_course_type_id'] = null;
				$courseBasicData['nl_course_type_name'] = null;
				$courseBasicData['nl_course_type_name_id_map'] = null;
			}

			$courseBasicData['nl_course_et_dm_name_id_map'] = $courseBasicData['nl_course_education_type_name'].":".$courseBasicData['nl_course_education_type_id'];

			if(isset($courseBasicData['nl_course_delivery_method_id']) && !empty($courseBasicData['nl_course_delivery_method_id'])){
				$courseBasicData['nl_course_et_dm_name_id_map'] .= "::".$courseBasicData['nl_course_delivery_method_name'].":".$courseBasicData['nl_course_delivery_method_id'];
			}else{
				$courseBasicData['nl_course_et_dm_name_id_map'] .= ":: ";
			}

			// $courseBasicData['nl_insttId_courseId'] = $courseBasicData['nl_institute_id'].":".$courseBasicData['nl_course_id'];

			// GRANTS
			if(isset($courseListingHieracyData['university'])){
				foreach ($courseListingHieracyData['university'] as $key => $value) {
					if(isset($value['is_ugc_approved']) && !empty($value['is_ugc_approved']) && $value['is_ugc_approved'] != 0){
						$courseBasicData['nl_course_approval_name_id_map'][] = "UGC:UGC";
						$courseBasicData['nl_course_approval_name'][] = "UGC";
						$courseBasicData['nl_course_approval_id'][] = "UGC";
					}
					if(isset($value['is_aiu_membership']) && !empty($value['is_aiu_membership']) && $value['is_aiu_membership'] != 0){
						$courseBasicData['nl_course_approval_name_id_map'][] = "AIU:AIU";
						$courseBasicData['nl_course_approval_name'][] = "AIU";
						$courseBasicData['nl_course_approval_id'][] = "AIU";
					}
					break;
				}	
			}

			if(empty($courseBasicData['nl_course_approval_id'])){
				$courseBasicData['nl_course_approval_id'] = null;
				$courseBasicData['nl_course_approval_name'] = null;
				$courseBasicData['nl_course_approval_name_id_map'] = null;
			}

			return $courseBasicData;
		}
		catch(Exception $e){
			$this->logException("Exception Occurs while Processing basic Data for course => ".$courseBasicData['nl_course_id'], true);
		}
	}


}

?>
