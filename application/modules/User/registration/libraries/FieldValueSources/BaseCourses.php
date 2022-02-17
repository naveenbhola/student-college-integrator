<?php

namespace registration\libraries\FieldValueSources;

/**
 * Value source for ShikshaCourse Field
 */ 

class BaseCourses extends AbstractValueSource 
{
	/**
	 * Get values
	 *
	 * @param array $params - streamIdsArr (mandatory), substreamIdsArr (optional), specializationIdsArr (optional), isHyperLocal (flag-(True/False) optional), isPopular (flag-(True/False) optional), isExecutive (flag-(True/False) optional)
	 * @return array
	 */ 

	function getValues($params = array()){

		$this->CI->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new \ListingBaseBuilder();

		$this->CI->load->library('listingBase/BaseAttributeLibrary');
		$BaseAttributeLibrary = new \BaseAttributeLibrary(); 

		$BaseCourseRepository = $listingBase->getBaseCourseRepository();
		$baseEntityArr = $params['baseEntityArr'];
		$isHyperLocal = $params['isHyperLocal'];

		foreach($baseEntityArr as $key=>$value){
			if(empty($value['substreamId'])){
				$baseEntityArr[$key]['substreamId'] = 'none';
			}
		}	

		$arrangeInAlpha = !empty($params['arrangeInAlpha'])? 'yes':'no';

		$returnType = 'object';
		
		$baseCoursesObject = $BaseCourseRepository->getBaseCoursesByMultipleBaseEntities($baseEntityArr, 0, $returnType);
		
		$attributeMapping = $BaseAttributeLibrary->getValuesForAttributeByName(array('Course Level', 'credential'));
		
		/*Get Values for response form */
		if(!empty($params['isResponseCall']) && $params['isResponseCall'] == 'yes' && !empty($params['requiredLevel'])){
			$dummyCourses = $BaseCourseRepository->getAllDummyBaseCourses();
			if(is_object($params['requiredLevel'])){
				$requiredLevel = $params['requiredLevel']->getId();
			} else {
				$requiredLevel = $params['requiredLevel'];
			}
			if(is_object($params['requiredCredential'])){
				$requiredCredential = $params['requiredCredential']->getId();
			} else {
				$requiredCredential = $params['requiredCredential'];
			}

			return $this->_getFilteredBaseCoursesByHierarchyLevelAndCredential($baseCoursesObject, $attributeMapping, $dummyCourses, $requiredLevel, $requiredCredential);
		}


		$level         = $attributeMapping['Course Level'];
		$credential    = $attributeMapping['credential'];
		$credential[0] = 'Certificates';
		$level[0]      = 'Certificates';
		
		$popularCourses = array();
		$data = array();


		foreach ($baseCoursesObject as $key => $value) {
			$value = $value->getObjectAsArray();

			/*Case to filter out Non HyperLocal courses */
			if($isHyperLocal == 'Yes' && $value['is_hyperlocal'] == 0){
				continue;
			}

			/*Case to filter out HyperLocal courses */
			if($isHyperLocal == 'No' && $value['is_hyperlocal'] == 1){
				continue;
			}

			$levelName = $level[$value['level']];
			if($levelName == 'None') {
				$levelName = 'Certificates';
			}
			// if($value['is_popular']){
			// 	$popularCourses[$value['base_course_id']]['name'] = $value['name'];
			// 	$popularCourses[$value['base_course_id']]['level'] = $levelName;
			// }

			$data[ $levelName ][ $credential[$value['credential'][0]]][$value['base_course_id']] = $value['name'];
		}

		$dummyCourses = $BaseCourseRepository->getAllDummyBaseCourses();
		
		if($arrangeInAlpha == 'no'){
			foreach ($dummyCourses as $key => $dummyCourseData) {

				if($dummyCourseData['name'] == 'None'){
					continue;
				}
				
				if($isHyperLocal == 'Yes' && $dummyCourseData['is_hyperlocal'] == 0){
					continue;
				}

				/*Case to filter out HyperLocal courses */
				if($isHyperLocal == 'No' && $dummyCourseData['is_hyperlocal'] == 1){
					continue;
				}

				$data[ $level[$dummyCourseData['level']] ][ $credential[$dummyCourseData['credential']]][$dummyCourseData['base_course_id']] = $dummyCourseData['name'];
			}
		}else{
			$tempCourses = array();
			foreach ($data as $cLevel => $credentialvalues) {
				foreach ($credentialvalues as $credId => $coursevalues) {
					foreach ($coursevalues as $courseId => $courseValue) {
						$tempCourses[$cLevel][$courseId] = $courseValue;
					}
				}
				asort($tempCourses[$cLevel], SORT_STRING | SORT_FLAG_CASE | SORT_NATURAL);

			}

			foreach ($dummyCourses as $key => $dummyCourseData) {

				if($dummyCourseData['name'] == 'None'){
					continue;
				}

				if($isHyperLocal == 'Yes' && $dummyCourseData['is_hyperlocal'] == 0){
					continue;
				}

				/*Case to filter out HyperLocal courses */
				if($isHyperLocal == 'No' && $dummyCourseData['is_hyperlocal'] == 1){
					continue;
				}

				$tempCourses[ $level[$dummyCourseData['level']] ][$dummyCourseData['base_course_id']] = $dummyCourseData['name'];
			}

			$data = $tempCourses;
		}
		
		$returnArray = array();
		Global $coursePriorities;

		foreach ($coursePriorities as $key => $value) {

			$tempKey = $value;

			if($value =='Certificates'){
				$value = 'None';

				if (empty($data[$value])) {
					$data[$value] = array();

				}

				if (empty($data[$tempKey])) {
					$data[$tempKey] = array();

				}

				$data[$tempKey] = $data[$tempKey] + $data[$value];
				$value = $tempKey;
			}

			$returnArray[$tempKey] = $data[$value];
			unset($data[$value]);
		}

		// $returnArray['Popular Courses'] = $popularCourses;

		/*Code to filter out the custom values from the master list. It is required for the case when we want to limit the selection list */
		if(!empty($params['customBaseCourses'])){
			$customCourses = array();
			foreach($params['customBaseCourses'] as $key=>$baseCourse){
				$customCourses[$baseCourse] = $baseCourse;
			}
			foreach($returnArray as $level=>$courses){
				foreach($courses as $courseId=>$courseName){
					if(empty($customCourses[$courseId])){
						unset($returnArray[$level][$courseId]);
					}
				}

				if(empty($returnArray[$level])){
					unset($returnArray[$level]);
				}

			}
		}
		return $returnArray;
	}

	function _getFilteredBaseCoursesByHierarchyLevelAndCredential($baseCoursesObject = array(), $attributeMapping=array(), $dummyCourses, $requiredLevel, $requiredCredential){

		$level = $attributeMapping['Course Level'];
		$credential = $attributeMapping['credential'];
		$credential[0] = 'Certificates';
		$level[0] = 'Certificates';

		foreach ($baseCoursesObject as $key => $value) {
			$value = $value->getObjectAsArray();

			$levelName = $level[$value['level']];
			// if($levelName == 'None') {
			// 	$levelName = 'Certificates';
			// }
			
			if(!empty($requiredCredential)){
				if($requiredLevel == $value['level'] && in_array($requiredCredential,$value['credential'])){
					$data[ $levelName ][ $credential[$requiredCredential]][$value['base_course_id']] = $value['name'];
				}
			} else {
				if($requiredLevel == $value['level']){
					$data[ $levelName ][$credential[$value['credential'][0]]][$value['base_course_id']] = $value['name'];
				}
			}
		}
		
		if(!empty($data[ $level[$requiredLevel] ]) && !empty($requiredCredential)){

			asort($data[ $level[$requiredLevel] ][ $credential[$requiredCredential] ], SORT_STRING | SORT_FLAG_CASE | SORT_NATURAL);
		}else if(!empty($data[  $level[$requiredLevel] ])){

			foreach ($data[ $level[$requiredLevel] ] as $key => $value) {
				asort($value, SORT_STRING | SORT_FLAG_CASE | SORT_NATURAL);
				$data[ $level[$requiredLevel] ][$key] = $value;
			}
		}else{

			unset($data);
		}

		$tempCourses = array();
		foreach ($data as $cLevel => $credentialvalues) {
			foreach ($credentialvalues as $credId => $coursevalues) {
				foreach ($coursevalues as $courseId => $courseValue) {
					$tempCourses[] = $courseId;
				}
			}
		}
		$data = array();
		$data['courseList'] = $tempCourses;
		
		foreach ($dummyCourses as $key => $dummyCourseData) {
			if($dummyCourseData['level'] == $requiredLevel && $dummyCourseData['credential'] == $requiredCredential){
				$data['dummyCourse'] = $dummyCourseData['base_course_id'];
			}else if($dummyCourseData['level'] == $requiredLevel && empty($requiredCredential)){
				if($dummyCourseData['name'] == 'None'){
					continue;
				}
				$data['dummyCourse'][] = $dummyCourseData['base_course_id'];
			}
		}

		return $data;
	}

}

?>