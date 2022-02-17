<?php

/**
 * File representing an MMP test prep-type registration form
 */
namespace registration\libraries\Forms\MMP;

/**
 * Class representing an MMP test prep-type registration form
 */
class TestPrep extends AbstractMMP
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
			$numberOfCourseInmmp = count($this->mmpModel->getTestPrepCourses($this->mmpFormId));		
			if($numberOfCourseInmmp == 1) {
						$dominantGroup = $this->mmpModel->getDominantGroup($this->mmpFormId);
						return $this->_getFieldSettingsForGroup($dominantGroup);
			} else {
						return \registration\builders\RegistrationBuilder::getFieldSettingsForCourseGroup('default','MMP');
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
        $groupAcronym = \registration\builders\RegistrationBuilder::getCourseGroupForTestPrep($this->courseId);
        $groupDetails = $this->mmpModel->getGroupDetailsByAcronym($groupAcronym);
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
            return \registration\builders\RegistrationBuilder::getFieldSettingsForCourseGroup($group);
        }
    }
    
    /**
     * Get custom rules created for the MMP
     * 
     * @return array 
     */
    protected function getCustomRules()
    {
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
            $dominantGroup = $this->mmpModel->getDominantGroup($this->mmpFormId);
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
        $groupAcronym = \registration\builders\RegistrationBuilder::getCourseGroupForTestPrep($this->courseId);
        $groupDetails = $this->mmpModel->getGroupDetailsByAcronym($groupAcronym);
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
}