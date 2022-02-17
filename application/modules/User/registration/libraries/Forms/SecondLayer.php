<?php
/**
 * File representing registration second layer form
 */
namespace registration\libraries\Forms;

/**
 * Class representing registration second layer form
 */
class SecondLayer extends AbstractForm
{
    /**
     * @var string Course group e.g. default, nationalUG, localPG, studyAbroad etc
     */
    private $courseGroup;
    
    /**
     * Constructor
     * 
     * @param string $courseGroup
     */ 
    function __construct($courseGroup = NULL)
    {
        parent::__construct();
        
        if(!$courseGroup) {
            $courseGroup = 'default';
        }
        $this->courseGroup = $courseGroup;
    }
    
    /**
     * Get field settings for course group and context
     *
     * @return array
     */ 
    public function getFieldSettings()
    {
        $masterFieldSettings = \registration\builders\RegistrationBuilder::getMasterFieldSettings();
        $fieldSettings = \registration\builders\RegistrationBuilder::getFieldSettingsForCourseGroup($this->courseGroup,'SecondLayer');
        
        /** 
         * Merge master and course group settings
         */ 
        foreach($fieldSettings as $fieldId => $settings) {
            $fieldSettings[$fieldId] = array_merge($masterFieldSettings[$fieldId],$settings);
        }
        
        return $fieldSettings;
    }

    /**
     * Get rules to be applied on field of the form
     * For LDB type forms, only master rules are applied
     * 
     * @return array 
     */
    public function getRules()
    {
        return array();
    }
}