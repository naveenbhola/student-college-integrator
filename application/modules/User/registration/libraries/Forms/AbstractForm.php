<?php

/**
 * Abstract class file for registration forms 
 */
namespace registration\libraries\Forms;

/**
 * Abstract class for registration forms
 */
abstract class AbstractForm
{
    /**
     * Variable for CI Instance
     * @var object CodeIgniter object
     */
    protected $CI;
    
    /**
     * Variable for MMP model
     * @var object customizemmp_model (model @ User/customizedmmp/models/)
     */
    protected $mmpModel;
    
    /**
     * Array for storing form fields
     * @var array
     */
    protected $fields = array();
    
    /**
     * Array  for storing custom rules data
     * @var array
     */
    protected $customRules = array();
    
    /**
     * Constructor
     */
    function __construct()
    {
        /**
         * Load required libraries/models
         */ 
        $this->CI = & get_instance();
        
        $this->CI->load->model('customizedmmp/customizemmp_model');
        $this->mmpModel = new \customizemmp_model();
        
        $this->CI->load->model('userVerification/verificationmodel');
        $this->verificationmodel = new \verificationmodel();
    }
    
    /**
     * Get fields of the form
     *
     * @param boolean $showPassword
     *
     * @return array of \registration\libraries\RegistrationFormField
     */
    public function getFields($showPassword = false)
    {
        $masterFieldSettings = \registration\builders\RegistrationBuilder::getMasterFieldSettings();
           
        /**
         * Get field settings from child form class
         */ 
        $fieldSettings = $this->getFieldSettings($showPassword);
    
        /**
         * Make field objects
         */ 
        $fields = array();
        foreach($fieldSettings as $fieldId => $settings) {
            $settings['caption'] = $masterFieldSettings[$fieldId]['caption'];
            $settings['validations'] = $masterFieldSettings[$fieldId]['validations'];
            $settings['type'] = $masterFieldSettings[$fieldId]['type'];
            $settings['label'] = $masterFieldSettings[$fieldId]['label'];
            $fields[$fieldId] = $this->_makeField($fieldId,$settings);
        }
        
        return $fields;
    }
    
    /**
     * Make a field object from field settings
     *
     * @param string $fieldId
     * @param array $settings Field settings
     * @return object \registration\libraries\RegistrationFormField
     */
    private function _makeField($fieldId,$settings)
    {
        $field = new \registration\libraries\RegistrationFormField($fieldId); 
        
        if($settings['visible'] == 'Yes') {
            $field->setVisible();
        }
        if($settings['mandatory'] == 'Yes') {
            $field->setMandatory();
        }
        if($settings['custom'] == 'Yes') {
            $field->setCustom();
        }
        if($type = $settings['type']) {
            $field->setType($type);
        }
        if($step = $settings['step']) {
            $field->setStep($step);
        }
        if($values = $settings['values']) {
            $field->setValues($values);
        }
        if($preSelectedValues = $settings['preSelectedValues']) {
            $preSelectedValues = explode('|',$preSelectedValues);
            $field->setPreSelectedValues($preSelectedValues);
        }
        if($order = $settings['order']) {
            $field->setOrder($order);
        }
        if($label = $settings['label']) {
            $field->setLabel($label);
        }
        if($caption = $settings['caption']) {
            $field->setCaption($caption);
        }
        if($validations = $settings['validations']) { 
            $field->setValidations($validations);
        }
        
        if(!$field->isCustom()) {
            /*
             * Set the value source of field
             */
            if($valueSource = \registration\builders\RegistrationBuilder::getFieldValueSource($fieldId)) {
                $field->setValueSource($valueSource);
            }
        }
        $field->setForm($this);
        return $field;
    }
    
    /**
     * Get one specific field of the form
     *
     * @param string $fieldId
     * @return object \registration\libraries\RegistrationFormField
     */
    public function getField($fieldId)
    {
        $fields = $this->getFields();
        return $fields[$fieldId];
    }
    
    /**
     * Add field to the form
     *
     * @param object \registration\libraries\RegistrationFormField
     */
    public function addField(\registration\libraries\RegistrationFormField $field)
    {
        $this->fields[$field->getId()] = $field;
    }
    
    /**
     * Get JSON of fields added to the form
     * Only the fields added using addField() are considered
     * If no field is added, fields are fetched using current field settings
     *
     * @return string JSON containing properties of fields of the form
     */
    public function getFieldJSON()
    {
        $fields = $this->fields;
        if($fields == 0) {
            $fields = $this->getFields();    
        }
        
        $data = array();
        foreach($fields as $field) {
            $data[$field->getId()] = array(
                'visible'   => $field->isVisible() ? 'Yes' : 'No',
                'mandatory' => $field->isMandatory() ? 'Yes' : 'No',
                'custom'    => $field->isCustom() ? 'Yes' : 'No',
                'type' => $field->getType(),
                'label' => $field->getLabel(),
                'caption' => $field->getCaption(),
                'values' => $field->isCustom() ? $field->getValues() : NULL,
                'preSelectedValues' => $field->getPreSelectedValues(),
                'order' => $field->getOrder(),
            );
        }
        
        return json_encode($data);
    }
        
    /**
     * Add a custom rule to the form
     *
     * @param object \registration\libraries\CustomLogic\Rule
     */    
    public function addCustomRule(\registration\libraries\CustomLogic\Rule $rule)
    {
        $this->customRules[] = $rule;
    }
    
    /**
     * Get JSON of rules added to the form
     * Only the rules added using addCustomRule() are considered
     *
     * @return string JSON containing properties of rules of the form
     */
    public function getRuleJSON()
    {
        $rules = array();
        foreach($this->customRules as $rule) {
            
            $ruleData = array();
            
            /*
             * Add conditions in rule
             */ 
            $conditionData = array();
            foreach($rule->getConditions() as $condition) {
                $conditionData[] = array('field' => $condition->getField()->getId(), 'value' => $condition->getValue());
            }
            $ruleData['conditions'] = $conditionData;
            
            /*
             * Add condition logic
             */
            $ruleData['conditionLogic'] = $rule->getLogic();
            
            /*
             * Add actions in rule
             */
            $actionData = array();
            foreach($rule->getActions() as $action) {
                $actionData[] = array('field' => $action->getField()->getId(), 'attribute' => $action->getAttribute(), 'value' => $action->getValue());
            }
            $ruleData['actions'] = $actionData;
            
            $rules[] = $ruleData;
        }
        
        return json_encode($rules);
    }
    
    /*
     * Function to check if a user is verified or not
     *  @Param: data=> array(); user input data
     *         
     *  @return: verified => BOOL
     */
    private function _isMobileVerified($data= array()){
        //Skip verification check for profile sections
        
    }

    /**
     * Validate fields of the form against data provided
     *
     * @param array $data Data of the fields (most likely registration post data submitted by user)
     * @return bool
     */
    public function validate($data)
    {
        $fields = $this->getFields();
    
       //$verified = $this->_isMobileVerified($data);
        
        /**
         * If user is logged in, do not validate security code
         */ 
        
        /*if($data['mfid'] && $fields['securityCode']) {
            $fields['securityCode']->setMandatory(FALSE);
        }*/

        //Skip verification check for profile sections
        /*if(empty($data['isdCode']) && empty($data['mobile']) && $data['userId'] > 0 && $data['context'] == 'unifiedProfile'){
            $verified = true;
        }else{
            $isdCode = explode('-', $data['isdCode']);
            $isdCode = $isdCode[0];

            //If OTP is verified
            if($data['isFBCall'] == 1){
                $verified = true;
            }else{

                if($data['stream'] >0){
                    $verified = true;
                }else{
                    $verified = $this->verificationmodel->isUserVerified($data['email'], $data['mobile'], $isdCode);
                }
            }
            
        }
       
        
        global $isMobileApp;
        if(!$verified && !$isMobileApp) {
            $fields['securityCode'] = $this->_makeField('securityCode',array(
                'mandatory' => 'Yes'    
            ));
        }*/

        // As we don't ask resident city from international users
		if(!empty($data['isdCode'])){
            $isdCode = explode('-', $data['isdCode']);
            $isdCode = $isdCode[0];
        }

        if($isdCode != '91' && $isdCode > 0){
            //Handling for local and national courses
            if(isset($fields['residenceCity'])){
                $fields['residenceCity']->setMandatory(FALSE);
            }else if(isset($fields['residenceCityLocality'])){
                $fields['residenceCityLocality']->setMandatory(FALSE);
                $fields['residenceLocality']->setMandatory(FALSE);
            }
        }
		if($data['stream']){
			 $this->CI->load->model('user/usermodel');
        	$this->usermodel = new \usermodel();

        	$isSpecMand = $this->usermodel->isSubStreamSpecMandatory($data['stream']);
        	if($isSpecMand == 'yes'){
             	$fields['subStreamSpecializations']->setMandatory(TRUE);
        	}
		}
       

        // SA shiksha Apply
        if(isset($data['graduationStream']) && isset($data['workExperience']) && ($data['context'] == 'rmcPage'||$data['context'] == 'mobileRmcPage')){
            $fields['CurrentSubjects']->setMandatory(FALSE);
            $fields['tenthmarks']->setMandatory(FALSE);
            $fields['tenthBoard']->setMandatory(FALSE);
            $fields['currentClass']->setMandatory(FALSE);
        }else if(isset($data['CurrentSubjects']) && isset($data['tenthmarks']) && ($data['context'] == 'rmcPage'||$data['context'] == 'mobileRmcPage')){
            $fields['graduationStream']->setMandatory(FALSE);
            $fields['graduationMarks']->setMandatory(FALSE);
            $fields['workExperience']->setMandatory(FALSE);
        }
        
        foreach($fields as $fieldId => $field) {
            if($fieldId == 'budget'){         //for old customized forms, 
                continue;                     //budget field may exist encoded in 
            }                                 //"customization_field" column of "cmp_formcustomization" table
            $fieldStep = $field->getStep();
            if(isset($data['registrationStep']) && isset($fieldStep) && $data['registrationStep'] != $field->getStep()) {
                continue;
            }
            
            if($field->isMandatory() && !$field->isCustom()) {
                $validator = \registration\builders\RegistrationBuilder::getFieldValidator($fieldId);
                if(!$validator->validate($field,$data[$fieldId],$data)) {
                    error_log("FAILEDERRORID".$fieldId);
                    return FALSE;
                }
            }
        }
        
        return TRUE;
    }
}
