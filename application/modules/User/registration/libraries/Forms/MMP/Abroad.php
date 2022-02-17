<?php
/**
 * File representing an abroad-type MMP registration form
 */
namespace registration\libraries\Forms\MMP;

/**
 * Class representing an abroad-type MMP registration form
 */
class Abroad extends AbstractMMP
{
    /**
     * Constructor
     * 
     * @param integer $mmpFormId
     */ 
    function __construct($mmpFormId)
    {
        parent::__construct($mmpFormId);
    }
     
    /**
     * Get field settings
     * In study abroad, there are no courses/groups
     * Customizations are defined at form level
     *
     * @return array
     */
    public function getFieldSettings()
    {
        /**
         * Check if there are customizations at form level
         */ 
        $settings = $this->mmpModel->getStudyAbroadCustomizations($this->mmpFormId);
        if($settings['customization_fields']) {
            $customization_fields = json_decode($settings['customization_fields'],TRUE);
            $customization_fields['tenthBoard'] = array('visible'=>'Yes', 'mandatory'=>'No', 'custom'=>'No', 'type'=>'select', 'label'=>'Class 10th Board', 'caption'=>'Class 10th Board', 'order'=>'17');
            $customization_fields['tenthmarks'] = array('visible'=>'Yes', 'mandatory'=>'No', 'custom'=>'No', 'type'=>'select', 'label'=>'Class 10th Marks, CGPA or max Grade', 'caption'=>'Class 10th Marks, CGPA or max Grade', 'order'=>'18');
            $customization_fields['currentClass'] = array('visible'=>'Yes', 'mandatory'=>'No', 'custom'=>'No', 'type'=>'select', 'label'=>'Current Class', 'caption'=>'Current Class', 'order'=>'19');
            $customization_fields['currentSchool'] = array('visible'=>'Yes', 'mandatory'=>'No', 'custom'=>'No', 'type'=>'select', 'label'=>'Current School Name', 'caption'=>'Current School Name', 'order'=>'20');
            $customization_fields['graduationStream'] = array('visible'=>'Yes', 'mandatory'=>'No', 'custom'=>'No', 'type'=>'select', 'label'=>'Graduation Stream', 'caption'=>'Graduation Stream', 'order'=>'21');
            $customization_fields['graduationMarks'] = array('visible'=>'Yes', 'mandatory'=>'No', 'custom'=>'No', 'type'=>'select', 'label'=>'Graduation Percentage', 'caption'=>'Graduation Percentage', 'order'=>'22');
            $customization_fields['workExperience'] = array('visible'=>'Yes', 'mandatory'=>'No', 'custom'=>'No', 'type'=>'select', 'label'=>'Work Experience', 'caption'=>'Work Experience', 'order'=>'23');
            return $customization_fields;
        } 
        
        /**
         * Else get default settings for study abroad
         */
        if(STUDY_ABROAD_NEW_REGISTRATION) {
            return \registration\builders\RegistrationBuilder::getFieldSettingsForCourseGroup('studyAbroadRevampedMMP', 'MMP');
        }
        return \registration\builders\RegistrationBuilder::getFieldSettingsForCourseGroup('studyAbroad');
    }
    
    /**
     * Get custom rules created for the MMP
     * 
     * @return array 
     */   
    protected function getCustomRules()
    {
        $settings = $this->mmpModel->getStudyAbroadCustomizations($this->mmpFormId);
        if($settings['customization_rules']) {
            return json_decode($settings['customization_rules'],TRUE);
        }
        else {
            return array();
        }
    }
    
    /**
     * Function to get the Group Type
     * @return String 
     */ 
    public function getGroupType()
    {
        return 'studyAbroad';
    }
}