<?php

class Form
{
    private $_id;
    private $_CI;
    private $_validation;
    
    function __construct($id)
    {
        
        
        $this->_id = $id;
        
        $this->_CI = & get_instance();
        $this->_CI->load->library('common/validation/validation');
        $this->_validation = new Validation($id);
    }
    
    public function validate()
    {
        if(!$this->_validation->run()) {
            $this->_saveValidationErrorsInSession($this->_validation->getErrors());
            $this->_saveFormPostDataInSession();
            return FALSE;
        }
        
        return TRUE;
    }
    
    private function _saveValidationErrorsInSession($validationErrors = array())
    {
        $this->_setFlashData('form_validation_errors',$validationErrors);
    }
    
    public function getValidationErrors($fromSession = TRUE)
    {
        return $this->_getFlashData('form_validation_errors');
    }

    private function _saveFormPostDataInSession()
    {
        $this->_setFlashData('form_post_data',$_POST);
    }
    
    public function getFormPostData()
    {
        return $this->_getFlashData('form_post_data');
    }
    
    public function setMessage($type,$message)
    {
        $this->_setFlashData('form_status_message',array('type' => $type, 'message' => $message));
    }
    
    public function getMessage()
    {
        return $this->_getFlashData('form_status_message');
    }
    
    private function _setFlashData($key,$value)
    {
        storeTempUserData($key,$value);
    }
    
    private function _getFlashData($key)
    {
        $data = getTempUserData($key);
        deleteTempUserData($key);
    }
}