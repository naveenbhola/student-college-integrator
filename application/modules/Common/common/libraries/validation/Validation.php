<?php

class Validation
{
    private $_validationId;
    private $_validators = array();
    private $_errorMessages;
    private $_CI;
    private $_pathToStandardValidators;
    private $_pathToCustomValidators;
    private $_validations;

    function __construct($validationId = '')
    {
        $this->_validationId = $validationId;
        
        $this->_CI = & get_instance();
        $this->_CI->load->library('common/validation/validators/AbstractValidator');
        
        $this->_pathToStandardValidators = FCPATH.APPPATH.'modules/Common/common/libraries/validation/validators';
        $this->_pathToCustomValidators = $this->_pathToStandardValidators.'/custom';
    }
    
    public function setValidations($validations)
    {
        $this->_validations = $validations;
    }
    
    public function getValidations()
    {
        if(!$this->_validations) {
            
            $this->_CI->config->load('validations');
            $allValidations = $this->_CI->config->item('validations');
            $this->_validations = $allValidations[$this->_validationId];
        }
        
        return $this->_validations;
    }
    
    public function run($data = NULL)
    {
        $this->_errorMessages = array();
        $validations = $this->getValidations();
        
        $valid = TRUE;
        
        if(!$data) {
            $data = $_POST;
        }
        foreach($data as $key => $value) {
            
            $validationRules = '';
            $label = '';
            
            if(isset($validations[$key])) {
                $validationRules = $validations[$key]['rules'];
                $label = $validations[$key]['label'];
            }
            else {
    
                $validationKeys = array_keys($validations);
                foreach($validationKeys as $validationKey) {
                    if(preg_match("/$validationKey/",$key)) {
                        $validationRules = $validations[$validationKey]['rules'];
                        $label = $validations[$validationKey]['label'];
                    }
                }
            }
             
            if($validationRules) {
                $validators = $this->_getValidators($validationRules);
                if(!$this->_runValidators($validators,$value,$label,$data)) {
                    $valid = FALSE;
                }
            }
        }
        
        return $valid;
    }

    private function _runValidators($validators,$value,$label,$data)
    {
        foreach($validators as $validator) {
            if(!$validator->validate($value,$label,$data)) { 
                $this->_errorMessages[] = $validator->getErrorMsg();
                return FALSE;
            }
        }
        
        return TRUE;
    }
    
    private function _getValidators($rules)
    {
        $validators = array();
        
        $rules = explode('|',$rules);
        foreach($rules as $rule) {
            list($validatorIdentifier,$validatorParams) = explode(':',$rule);
            $validators[] = $this->_getValidator($validatorIdentifier,$validatorParams);
        }
        return $validators;
    }
    
    private function _getValidator($validatorIdentifier,$params = '')
    {
        $validatorClass = $validatorIdentifier.'Validator';
        
        /*
         * First check in standard validators
         */ 
        if(file_exists($this->_pathToStandardValidators.'/'.$validatorClass.'.php')) {
            
            require_once $this->_pathToStandardValidators.'/'.$validatorClass.'.php';
        }
        else if(file_exists($this->_pathToCustomValidators.'/'.$validatorClass.'.php')) {
            require_once $this->_pathToCustomValidators.'/'.$validatorClass.'.php';
        }
        else {
            throw new Exception('Validator '.$validatorIdentifier.' does not exist');
        }
        $params = $params ? explode(',',$params) : array();
        $validator = new $validatorClass($params);
        return $validator;
    }
    
    public function getErrors()
    {
        return $this->_errorMessages;  
    }
}