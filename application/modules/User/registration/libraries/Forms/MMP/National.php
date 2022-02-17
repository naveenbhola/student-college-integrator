<?php

/**
 * File representing an MMP national-type registration form
 */
namespace registration\libraries\Forms\MMP;

/**
 * Class representing an MMP national-type registration form
 */
class National extends AbstractMMP
{
    /**
     * Constructor
     * 
     * @param integer $mmpFormId
     * @param integer $courseId
     * @param integer $courseGroupId
     */ 
    function __construct($mmpFormId,$courseId,$courseGroupId)
    {
        parent::__construct($mmpFormId,$courseId,$courseGroupId);
    }
    
    /**
     * Get field settings for given MMP form, course id/group
     *
     * @return array
     */
    public function getFieldSettings()
    {
        $mmpDetails = $this->getMMPDetails();
        
        /**
         * If group id is speficied, get setting for that group
         */ 
        if($this->courseGroupId) {
            return $this->_getFieldSettingsForGroup($this->courseGroupId);
        }
        
        /**
         * If neither group id nor course id speficied,
         * Get dominant group in the MMP i.e. group with highest no. of courses
         * and get settings for that group
         */ 
        if(!$this->courseId) {
			 //error_log('THEEEEEEEEEEXXXXXXXXXXXXXXXXXXXXXX1');
			 if($mmpDetails['page_type'] == 'testpreppage') {
	                $numberOfCourseInmmp = count($this->mmpModel->getTestPrepCourses($this->mmpFormId));			 
  		     } else {
					$numberOfCourseInmmp = count($this->mmpModel->getCourses($this->mmpFormId));
			 }
			 
			 //error_log('THEEEEEEEEEEXXXXXXXXXXXXXXXXXXXXXX1_____'.$this->mmpFormId."_______".print_r($this->mmpModel->getTestPrepCourses($this->mmpFormId)));			
			 if($numberOfCourseInmmp == 1) {
				 if($mmpDetails['page_type'] == 'testpreppage') {
					$dominantGroup = $this->_getDominantGroupForTestPrep();
				 } else {				 
					$dominantGroup = $this->mmpModel->getDominantGroup($this->mmpFormId);
				 }
				 return $this->_getFieldSettingsForGroup($dominantGroup);
				 
		   } else {
			if(isMobileRequest()) {
				return \registration\builders\RegistrationBuilder::getFieldSettingsForCourseGroup('default','MMP/mobile');
			} else {
				return \registration\builders\RegistrationBuilder::getFieldSettingsForCourseGroup('default','MMP');
			}
		   }
        }
        
        /**
         * Get settings for course
         * 
         * First check if there are course level customizations
         */ 
        $settings = $this->mmpModel->getCourseCustomizations($this->mmpFormId,$this->courseId);
        if($settings['customization_fields']) {
            return json_decode($settings['customization_fields'],TRUE);
        }
        
        /**
         * Else check if there are group level customizations for the course's group
         */ 
        if($mmpDetails['page_type'] == 'testpreppage') {
            $groupAcronym = \registration\builders\RegistrationBuilder::getCourseGroupForTestPrep($this->courseId);
            $groupDetails = $this->mmpModel->getGroupDetailsByAcronym($groupAcronym);
        }
        else {
            $groupDetails = $this->mmpModel->getGroupDataForCourse($this->courseId);    
        }
        return $this->_getFieldSettingsForGroup($groupDetails['groupid']);
    }
    
    /**
     * Get field settings for a group
     *
     * @param integer $groupId
     * @return array
     */
    private function _getFieldSettingsForGroup($groupId)
    {
        /**
         * Check if there are group level customizations
         */ 
        $settings = $this->mmpModel->getGroupCustomizations($this->mmpFormId,$groupId);
    
        if($settings['customization_fields']) {
            return json_decode($settings['customization_fields'],TRUE);
        }
        else {
            /*
             * Else default settings for the group
             */ 
            $groupDetails = $this->mmpModel->getGroupData($groupId);
            $group = $groupDetails['acronym'];
			if(isMobileRequest()) {
				$fieldSettings = \registration\builders\RegistrationBuilder::getFieldSettingsForCourseGroup($group, 'MMP/mobile');
			} else {
				$fieldSettings = \registration\builders\RegistrationBuilder::getFieldSettingsForCourseGroup($group, 'MMP');
			}
            return $fieldSettings;
        }
    }
    
    /**
     * Function to get the Dominant Group for test preparation
     *
     * @return integer 
     */
    private function _getDominantGroupForTestPrep()
    {
        $courses = $this->mmpModel->getTestPrepCourses($this->mmpFormId);
        $groups = array();
        foreach($courses as $course) {
            $courseGroup = \registration\builders\RegistrationBuilder::getCourseGroupForTestPrep($course['course_id']);
            $groups[$courseGroup]++;
        }
        arsort($groups);
        $courseGroupAcronym = key($groups);
        $groupDetails = $this->mmpModel->getGroupDetailsByAcronym($courseGroupAcronym);
        return $groupDetails['groupid'];
    }
    
    /**
     * Get custom rules created for the MMP
     * 
     * @return array 
     */
    protected function getCustomRules()
    {
        $mmpDetails = $this->getMMPDetails();
        
        /**
         * If course group id is specified, get rules for that group
         */ 
        if($this->courseGroupId) {
            return $this->_getCustomRulesForGroup($this->courseGroupId);
        }
        
        /**
         * If neither group id nor course id speficied,
         * Get dominant group in the MMP i.e. group with highest no. of courses
         * and get rules for that group
         */ 
        if(!$this->courseId) {
            if($mmpDetails['page_type'] == 'testpreppage') {
                $dominantGroup = $this->_getDominantGroupForTestPrep();
            }
            else {
                $dominantGroup = $this->mmpModel->getDominantGroup($this->mmpFormId);    
            }
            return $this->_getCustomRulesForGroup($dominantGroup);
        }
        
        /**
         * Get rules for course
         * 
         * First check if there are course level customizations
         */ 
        $settings = $this->mmpModel->getCourseCustomizations($this->mmpFormId,$this->courseId);
        
        if($settings['customization_rules']) {
            return json_decode($settings['customization_rules'],TRUE);
        }
        
        /*
         * Else check if there are group level customizations
         */
        
        if($mmpDetails['page_type'] == 'testpreppage') {
            $groupAcronym = \registration\builders\RegistrationBuilder::getCourseGroupForTestPrep($this->courseId);
            $groupDetails = $this->mmpModel->getGroupDetailsByAcronym($groupAcronym);
        }
        else {
            $groupDetails = $this->mmpModel->getGroupDataForCourse($this->courseId);    
        }
        return $this->_getCustomRulesForGroup($groupDetails['groupid']);
    }
    
    /**
     * Get custom rules for a group
     *
     * @param integer $groupId 
     * @return array 
     */
    private function _getCustomRulesForGroup($groupId)
    {
        /*
         * Else check if there are group level customizations
         */ 
        $settings = $this->mmpModel->getGroupCustomizations($this->mmpFormId,$groupId);
        if($settings['customization_rules']) {
            return json_decode($settings['customization_rules'],TRUE);
        }
        else {
            return array();
        }
    }
    
    /**
     * Function to get the Group Type
     */
    public function getGroupType()
    {
        $mmpDetails = $this->getMMPDetails();
        
        $courseGroupId = $this->courseGroupId;
        
        /**
         * If group id is speficied, get setting for that group
         */ 
        if($this->courseGroupId) {
            $courseGroupId = $this->courseGroupId;
        }
        else if($this->courseId) {
            if($mmpDetails['page_type'] == 'testpreppage') {
                return \registration\builders\RegistrationBuilder::getCourseGroupForTestPrep($this->courseId);
            }
            else {
                $groupDetails = $this->mmpModel->getGroupDataForCourse($this->courseId);
                $courseGroupId = $groupDetails['groupid'];
            }
        }
        else {
            if($mmpDetails['page_type'] == 'testpreppage') {
                $courseGroupId = $this->_getDominantGroupForTestPrep();    
            }
            else {
                $courseGroupId = $this->mmpModel->getDominantGroup($this->mmpFormId);
            }
        }
        
        $groupDetails = $this->mmpModel->getGroupData($courseGroupId);
        return $groupDetails['acronym'];
    }
}