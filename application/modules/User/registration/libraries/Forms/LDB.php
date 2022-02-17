<?php
/**
 * Class representing an LDB registration form
 */
namespace registration\libraries\Forms;

/**
 * Class representing an LDB registration form
 */
class LDB extends AbstractForm
{
    /**
     * @var string Course group e.g. default, nationalUG, localPG, studyAbroad etc
     */
    private $courseGroup;
    
    /**
     * Context represents variations in field settings and view
     * @var object \registration\libraries\Forms\Contexts\AbstractContext (DefaultContext, Unified, RegisterFree)
     */
    private $context;
    
    /**
     * Constructor
     * 
     * @param string $courseGroup
     * @param string $contextId
     */ 
    private $contextId;
    function __construct($courseGroup = NULL,$contextId = NULL)
    {
        parent::__construct();
        
        if(!$courseGroup) {
            $courseGroup = 'default';
        }
        $this->courseGroup = $courseGroup;
        
        /**
         * Load the context object
         */ 
        $this->context = \registration\builders\RegistrationBuilder::getContext($contextId);
        $this->contextId = $contextId;
    }
    
    /**
     * Get field settings for course group and context
     *
     * @param boolean $showPassword
     * @return array
     */ 
    public function getFieldSettings($showPassword)
    {
        $masterFieldSettings = \registration\builders\RegistrationBuilder::getMasterFieldSettings();
        if($this->contextId=='mobile'){
            $fieldSettings = \registration\builders\RegistrationBuilder::getFieldSettingsForCourseGroup($this->courseGroup,$this->contextId);
        }elseif($this->contextId=='findInstitute'){
            $fieldSettings = \registration\builders\RegistrationBuilder::getFieldSettingsForCourseGroup($this->courseGroup,'MMP');
        }else if($this->contextId=='search'){
            $fieldSettings = \registration\builders\RegistrationBuilder::getFieldSettingsForCourseGroup($this->courseGroup,$this->contextId);
        }else{
        $fieldSettings = \registration\builders\RegistrationBuilder::getFieldSettingsForCourseGroup($this->courseGroup);
        }
        
        /** 
         * Merge master and course group settings
         */ 
        foreach($fieldSettings as $fieldId => $settings) {
            $fieldSettings[$fieldId] = array_merge($masterFieldSettings[$fieldId],$settings);
        }
        
        /** 
         * Apply the context
         * The context can change some of the settings, add/remove fields etc.
         */ 
        $fieldSettings = $this->context->apply($fieldSettings);
        
        if($showPassword === true) {
            $fieldSettings['password'] = array('type' => 'textbox','visible' => 'Yes','mandatory' => 'No');
            $fieldSettings['confirmPassword'] = array('type' => 'textbox','visible' => 'Yes','mandatory' => 'No');
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
        return \registration\builders\RegistrationBuilder::getMasterRules(array('courseGroup' => $this->courseGroup));
    }
    
    /**
     * Function to get the group type
     * @return string
     */
    public function getGroupType()
    {
        return $this->courseGroup;
    }
}