<?php

namespace registration\libraries\FieldValueSources;

/**
 * Value source for desired course field
 */ 
class DesiredCourse extends AbstractValueSource
{
	/**
	 * Get values
	 *
	 * @param array $params additional parameters
	 * @return array
	 */ 
    public function getValues($params = array())
    {
        if($categoryId = $params['categoryId']) {
			return $this->_getCoursesInCategory($categoryId);
		}
		if($coursePageSubcategoryId = $params['coursePageSubcategoryId']) {
			return $this->_getCoursesInCoursePageSubcategory($coursePageSubcategoryId);
		}
		else if($mmpFormId = $params['mmpFormId']) {
			return $this->_getCoursesInMMPForm($mmpFormId);
		}
    }
	
	/**
	 * Get courses in category
	 *
	 * @param integer $categoryId
	 * @return array
	 */ 
	private function _getCoursesInCategory($categoryId)
	{
		$this->CI->load->library(array('LDB_Client','category_list_client','MultipleMarketingPageClient'));
		
		/**
		 * Exclude course page specific desired courses which do not allow global registration
		 */
		$this->CI->load->model('registration/registrationmodel');
		$coursePageSpecificCourses = $this->CI->registrationmodel->getCoursePageSpecificCourses();
		
		$values = array();
		
		if($categoryId == 3) {
			$courses = json_decode($this->CI->ldb_client->sgetCourseList($appId,3),true);
			$courseList = array();
			foreach($courses as $course) {
				if(!in_array($course['SpecializationId'],$coursePageSpecificCourses)) {
					$courseList[$course['SpecializationId']] = $course['CourseName'];
				}
			}
			$values[] = $courseList;
		}
		else if($categoryId == 14) {
			$marketingPageClient = \MultipleMarketingPageClient::getInstance();
			$courses = json_decode($marketingPageClient->getTestPrepCoursesListForApage(1,0,'testpreppage','complete_list'),true);
			$values = array();
			foreach($courses['courses_list'] as $courseList) {
				foreach($courseList as $course) {
					$values[$course['title']][$course['child']['blogId']] = $course['child']['acronym'];
				}
			}
		}
		else {
			$courses = json_decode($this->CI->category_list_client->getCourseSpecializationForCategoryIdGroups(1,$categoryId),true);
			foreach($courses as $groupId => $coursesInGroup) {
				$courseList = array();
				foreach($coursesInGroup as $course) {
					if(!in_array($course['SpecializationId'],$coursePageSpecificCourses)) {
						if (strtolower($course['SpecializationName']) == 'all' && strtolower($course['CourseName']) != 'all') {
							$courseList[$course['SpecializationId']] = $course['CourseName'];
						}
						$groupName = $course['groupName'];
					}
				}
				$values[$groupName] = $courseList;
			}
		}
		
		return $values;
	}
	
	/**
	 * Get courses in course page subcategory
	 *
	 * @param integer $coursePageSubcategoryId
	 * @return array
	 */ 
	private function _getCoursesInCoursePageSubcategory($coursePageSubcategoryId)
	{
		$this->CI->load->model('registration/registrationmodel');
		$courses = $this->CI->registrationmodel->getCoursesInCoursePageSubcategory($coursePageSubcategoryId);
		$courseList = array();
		foreach($courses as $course) {
			$courseList[$course['SpecializationId']] = $course['CourseName'];
		}
		return array($courseList);
	}	
	
	/**
	 * Get courses in an MMP form
	 *
	 * @param integer $mmpFormId
	 * @return array
	 */ 
	private function _getCoursesInMMPForm($mmpFormId)
	{
		$this->CI->load->model('customizedmmp/customizemmp_model');
		
		$values = array();
		
		$mmpDetails = $this->CI->customizemmp_model->getMMPDetails($mmpFormId);
		if($mmpDetails['page_type'] == 'testpreppage') {
			$testPrepCourses = $this->CI->customizemmp_model->getTestPrepCourses($mmpFormId);
			foreach($testPrepCourses as $course) {
				$values[$course['blogTitle']][$course['course_id']] = $course['acronym'];
			}
		}
		else {
			$managementCourses = $this->CI->customizemmp_model->getManagementCourses($mmpFormId);
			$courseList = array();
			foreach($managementCourses as $course) {
				$courseList[$course['SpecializationId']] = $course['CourseName'].($course['SpecializationName'] && strtolower($course['SpecializationName']) != 'all' ? ' - '.$course['SpecializationName'] : '');
			}
			$values[] = $courseList;
			
			$nonManagementCourses = $this->CI->customizemmp_model->getNonManagementCourses($mmpFormId);
			foreach($nonManagementCourses as $course) {
				if (strtolower($course['SpecializationName']) == 'all' && strtolower($course['CourseName']) != 'all') {
					$values[$course['groupName']][$course['SpecializationId']] = $course['CourseName'];
				}
			}
		}
		
		return $values;
	}
}