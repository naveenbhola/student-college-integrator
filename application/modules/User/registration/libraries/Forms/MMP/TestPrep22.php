<?php
/**
 * File representing an testprep-type MMP registration form
 */
namespace registration\libraries\Forms\MMP;

/**
 * Class representing an testprep-type MMP registration form
 */
class TestPrep extends AbstractMMP
{
   /**
     * Constructor
     * 
     * @param integer $mmpFormId
     * @param integer $courseId
     */ 
    function __construct($mmpFormId,$courseId = 0)
    {
        parent::__construct($mmpFormId,$courseId);
    }
     
    /**
     * Get field settings
     * Currently there are no customizations in test prep
     *
     * @return array
     */
    public function getFieldSettings()
    {
        if($this->courseId) {
            $courseGroup = \registration\builders\RegistrationBuilder::getCourseGroupForTestPrep($this->courseId);
        }
        else {
            /**
             * Get dominant group i.e. group with highest no. of courses
             */ 
            $courses = $this->mmpModel->getTestPrepCourses($this->mmpFormId);
            $groups = array();
            foreach($courses as $course) {
                $courseGroup = \registration\builders\RegistrationBuilder::getCourseGroupForTestPrep($course['course_id']);
                $groups[$courseGroup]++;
            }
            arsort($groups);
            $courseGroup = key($groups);
        }
        
        return \registration\builders\RegistrationBuilder::getFieldSettingsForCourseGroup($courseGroup);
    }
    
    /**
     * Get custom rules created for the MMP
     * Currently there are no customizations in test prep, so no custom rules
     * 
     * @return array 
     */   
    protected function getCustomRules()
    {
        return array();
    }
}