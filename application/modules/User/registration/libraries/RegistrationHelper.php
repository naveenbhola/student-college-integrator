<?php
/**
 * File having General purpose view level functions
 */ 
namespace registration\libraries;

/**
 * General purpose view level functions
 */ 
class RegistrationHelper
{
    /**
     * Fields in registration form
     * 
     * @var array of \registration\libraries\RegistrationFormField objects
     */
    private $fields;
    private $regFormId;
    
     /**
     * Constructor
     *
     * @param  array $fields
     * @param string $regFormId
     */
    function __construct($fields = array(),$regFormId)
    {
        $this->fields = $fields;
        $this->regFormId = $regFormId;
    }
    
    /**
     * Get custom attributes of the block of a field
     * Block is an HTML element (div, li) which houses the field
     * Field visibility is managed at block level
     *
     * @param string $fieldId
     * @param string $customStyle Custom styles to be associated to block
     * @return string $display attributes for block html element
     */
    public function getBlockCustomAttributes($fieldId,$customStyle = '',$display = null)
    {
        $attributes = array();
        $field = $this->fields[$fieldId];
        
        if($field) {
            
            $attributes[] = "id='".$fieldId."_block_".$this->regFormId."' class='regFieldBlock_".$this->regFormId."' blockFor='".$fieldId."' ";
            
            if(!empty($display)) {
                $attributes[] = "visible='".$display['visible']."' style='".$display['style']."'";
            }
            else {
                if($field->isVisible()) {
                    $attributes[] = "visible='Yes' style='".$customStyle."'";        
                }
                else {
                    $attributes[] = "visible='No' style='display:none; ".$customStyle."'";    
                }
            }
        }
        
        return implode(" ",$attributes);
    }
    
    /**
     * Get custom attributes of a parent block
     * Parent block is an HTML element which houses multiple blocks
     *
     * @param string $blockId
     * @param array $fieldsInBlock IDs of blocks contained
     * @param string $customStyle Custom styles to be associated to parent block
     * @return string attributes for parent block html element
     */
    public function getParentBlockCustomAttributes($blockId,$fieldsInBlock,$customStyle = '')
    {
        $hideBlock = TRUE;
        foreach($fieldsInBlock as $fieldId) {
            $field = $this->fields[$fieldId];
            if($field->isVisible()) {
                $hideBlock = FALSE;
            }
        }
        
        $attributes = array();
        $attributes[] = "id='".$blockId."_block'";
        
        if($hideBlock) {
            $attributes[] = "visible='Yes' style='display:none; ".$customStyle."'";    
        }
        else {
            $attributes[] = "visible='No' style='".$customStyle."'";    
        }
        
        return implode(" ",$attributes);
    }
    
    /**
     * Get custom attributes of a field (HTML form input (select, checkbox, radio etc))
     *
     * @param string $fieldId
     * @param array $params Additional parameters to customize attributes
     * @return string attributes for field html element
     */
    public function getFieldCustomAttributes($fieldId,$params = array())
    {
        $attributes = array();
        $field = $this->fields[$fieldId];
        
        if($field) {
            
            $attributes = array();
            
            $regFieldId = $fieldId;
            if($params['num']) {
                $regFieldId = $fieldId.$params['num'];
            }
            
            $attributes[] = "regFieldId='".$regFieldId."'";
            
            if($field->isMandatory()) {
                $attributes[] = "mandatory='1'";
            }
            
            if($label = $field->getLabel()) {
                $attributes[] = "label='".$label."'";
            }
            
            if($caption = $field->getCaption()) {
                $attributes[] = "caption='".$caption."'";
            }
            else {
                $attributes[] = "caption='".$label."'";
            }
        }
        return implode(" ",$attributes);
    }
    
    /**
     * Check whether a particular field exists in registration form
     *
     * @param string $fieldId
     * @return bool
     */
    public function fieldExists($fieldId)
    {
        return array_key_exists($fieldId,$this->fields);    
    }
    
    /**
     * Get form data for all visible fields in the forms
     *
     * @param array $data
     * @return array
     */
    public function getFormData($data)
    {
        $formData = $data;
        foreach($this->fields as $fieldId => $field) {
            $preSelectedValues = $field->getPreSelectedValues();
            if(is_array($preSelectedValues) && count($preSelectedValues) > 0) {
                $formData[$fieldId] = $preSelectedValues;
            }
            else {
                if($field->isVisible()) {
                    /**
                     * For preferred study locality, we need to combine city and locality
                     * in format "City Id+Locality Id"
                     * If locality id is Anywhere, we represent it as *
                     */ 
                    if($fieldId == 'preferredStudyLocality') {
                        if(is_array($data['preferredStudyLocality']) && is_array($data['preferredStudyLocalityCity'])) {
                            for($i=0;$i<count($data['preferredStudyLocality']);$i++) {
                                
                                if($data['preferredStudyLocality'][$i] == '-1') {
                                    $data['preferredStudyLocality'][$i] = '*';    
                                }
                                
                                if($data['preferredStudyLocality'][$i] && $data['preferredStudyLocalityCity'][$i]) {
                                    $data['preferredStudyLocality'][$i] = $data['preferredStudyLocalityCity'][$i].'+'.$data['preferredStudyLocality'][$i];
                                }
                            }
                        }
                    }
                    
                    $formData[$fieldId] = $data[$fieldId];
                }
                else {
                    $formData[$fieldId] = NULL;
                }
            }
        }
        return $formData;
    }
    
    /**
     * Mark a field mandatory
     *
     * @param string $fieldId
     */
    public function markMandatory($fieldId)
    {
        if($field = $this->fields[$fieldId]) {
            if($field->isMandatory()) {
                echo "*";
            }
        }
    }
    
    /**
     * Get custom validations for the form fields
     *
     * @return array
     */
    public function getCustomValidations()
    {
        $customValidations = array();
        foreach($this->fields as $fieldId => $field) {
            if($validations = $field->getValidations()) {
                $customValidations[$fieldId] = $validations;
            }
        }
        $customValidations['TOEFL_score']  = 'StudyAbroadExamScore';
        $customValidations['IELTS_score']  = 'StudyAbroadExamScore';
        $customValidations['PTE_score']    = 'StudyAbroadExamScore';
        $customValidations['GRE_score']    = 'StudyAbroadExamScore';
        $customValidations['GMAT_score']   = 'StudyAbroadExamScore';
        $customValidations['SAT_score']    = 'StudyAbroadExamScore';
        $customValidations['CAEL_score']   = 'StudyAbroadExamScore';
        $customValidations['MELAB_score']  = 'StudyAbroadExamScore';
        $customValidations['CAE_score']    = 'StudyAbroadExamScore';
        $customValidations['agreeterms']   = 'MandatoryTerms';
        $customValidations['serviceterms'] = 'MandatoryTerms';
        return $customValidations;
    }
    
    /**
     * Function to get the Default field status
     * @retr array $defaultFieldStates
     */
    public function getDefaultFieldStates()
    {
        $defaultFieldStates = array();
        foreach($this->fields as $fieldId => $field) {
            $visible = $field->isVisible() ? 'yes' : 'no';
            $values = $field->getPreSelectedValues();
            $defaultFieldStates[$fieldId] = array('visible' => $visible,'values' => $values);
        }
        return $defaultFieldStates;
    }
    
    
    /**
     * Function to get the list of exam with scores
     * @return array LIST_OF_EXAMS
     */
    public function getExamsWithScores()
    {
        return array(
            'TOEFL','IELTS','PTE','GRE','GMAT','SAT','CAEL','MELAB','CAE'
        );
    }

     /*
     * Function to get user country using his/her IP address
     * OUTPUT: country name
     */
    public function getUserCountryByIP(){

        return '91-2';
        
        $country = 'India';

        // Getting user IP address
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipList = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = $ipList[0];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        // Extracting country name from maxmind api
        if($ip){
            $locationDetection = $this->load->library('location/LocationDetection',$ip);
            $locationData = $locationDetection->getLocation();
            if(!empty($locationData['country'])){
                $country = $locationData['country'];
            }
        }
    
        // Changing country fot testing
        if(TESTING_ISD_CODE){
            $country = TESTING_ISD_CODE_COUNTRY;
        }

        
        require APPPATH.'modules/User/registration/config/ISDCodeConfig.php';

        $isdCode = $ISDCodesList[$country]['ISD'];
        $countryId = $ISDCodesList[$country]['shikshaCountryId'];

        return $isdCode.'-'.$countryId;
    }

    function getDefaultHelptextForRegistrationLayer(){
        
        global $registrationFormSubHeading;

        //$data = array();
        return $registrationFormSubHeading;
        // $data['heading'] = 'Lakhs of students & parents have registered on Shiksha to:';
        // $data['body'] = $registrationFormSubHeading;

        //return $data;
    }

     public function filterDummyBaseCourses($baseCourses){
        if(empty($baseCourses)){
            return array();
        }

        $returnData = array();
        $this->load->builder('listingBase/ListingBaseBuilder');
        $listingBase = new ListingBaseBuilder();

        $BaseCourseRepository = $listingBase->getBaseCourseRepository();

        $baseCoursesObject = $BaseCourseRepository->findMultiple($baseCourses);

        foreach($baseCoursesObject as $row=>$value){
            if($value->isDummy()){
                 $returnData['dummyCourses'][] = $value->getId();
            }else{
                 $returnData['baseCourses'][] = $value->getId();
            }
        }
        return $returnData;
    }
 
}