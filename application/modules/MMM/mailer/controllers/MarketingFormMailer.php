<?php

class MarketingFormMailer extends MX_Controller
{
    private $model;

    function __construct()
    {
        $this->load->model('mailer/marketingformmailermodel');
        $this->model = new MarketingFormMailerModel;
    }
    
    function validateAuthorization()
    {
        $loggedInUser = $this->checkUserValidation();
        
        if(is_array($loggedInUser) && is_array($loggedInUser[0])) {

            if(!empty($loggedInUser[0]['userid'])) {
                $mailerModel = $this->load->model('mailer/mailermodel');
                if(empty($this->userGroupData)) {
                    $this->userGroupData = $mailerModel->getUserGroupInfo($loggedInUser[0]['userid']);
                }
                if(empty($this->mailerTabs)) {
                    $this->mailerTabs = $mailerModel->getAllTabs();
                }
            }

            return true;
        }
        else {
            header("Location: /enterprise/Enterprise/loginEnterprise");
            exit();
        }
    }
    
    function index($pageId)
    {
        $this->validateAuthorization();
        
        $data = array();
        $data['validateuser'] = $this->checkUserValidation();
        //$data['page'] = $this->model->getPage($pageId);
        if(!$data['page']['id']) {
            echo "This page does not exist.";
            exit();
        }
        $data['userGroupData'] = $this->userGroupData;
        $data['mailerTabs'] = $this->mailerTabs;
        $this->load->view('mailer/MarketingFormMailer/page',$data);
    }
    
    function listForms()
    {
        $this->validateAuthorization();
        
        $data = array();
        $data['validateuser'] = $this->checkUserValidation();
        $data['userGroupData'] = $this->userGroupData;
        $data['mailerTabs'] = $this->mailerTabs;
        $data['forms'] = $this->model->getForms($data['userGroupData']['user_id'], $data['userGroupData']['group_id'], $data['userGroupData']['user_type']);
        $data['headerTabs'] =$this->getHeaderTabs();
        $data['prodId'] = 780;
        $data['msg'] = $_REQUEST['msg'];
        $data['allAdminData'] = $this->mailermodel->getAllAdminData();

        $this->load->view('mailer/MarketingFormMailer/listForms',$data);
    }
    
    function createForm()
    {
        $this->validateAuthorization();
        
        $data = array();
        $data['validateuser'] = $this->checkUserValidation();
        $data['headerTabs'] =$this->getHeaderTabs();
        $data['prodId'] = 780;
        $data['userGroupData'] = $this->userGroupData;
        $data['mailerTabs'] = $this->mailerTabs;
        $data['userGroupData'] = $this->userGroupData;
        $data['mailerTabs'] = $this->mailerTabs;
        $this->load->view('mailer/MarketingFormMailer/createForm',$data);
    }
    
    function editForm($formId)
    {
        $this->validateAuthorization();
        
        $data = array();
        $data['validateuser'] = $this->checkUserValidation();
        $data['headerTabs'] =$this->getHeaderTabs();
        $data['prodId'] = 780;
        $data['formId'] = $formId;
        $data['form'] = $this->model->getForm($formId);
        $data['userGroupData'] = $this->userGroupData;
        $data['mailerTabs'] = $this->mailerTabs;
        if(!$data['form']['id']) {
            header('Location: /mailer/MarketingFormMailer/listForms?msg=editFormDoesNotExist');
            exit();
        }
        $this->load->view('mailer/MarketingFormMailer/createForm',$data);
    }
    
    function htmlSnippet($formId)
    {
        $this->validateAuthorization();
        
        $data = array();
        $data['validateuser'] = $this->checkUserValidation();
        $data['headerTabs'] =$this->getHeaderTabs();
        $data['prodId'] = 780;
        $data['formId'] = $formId;
        $data['form'] = $this->model->getForm($formId);
        $data['userGroupData'] = $this->userGroupData;
        $data['mailerTabs'] = $this->mailerTabs;
        if(!$data['form']['id']) {
            header('Location: /mailer/MarketingFormMailer/listForms?msg=editFormDoesNotExist');
            exit();
        }
        $this->load->view('mailer/MarketingFormMailer/htmlSnippet',$data);
    }
    
    function saveForm()
    {
        $this->validateAuthorization();
        
        $loggedInUser = $this->checkUserValidation();
        $loggedInUserId = 0;
        if(is_array($loggedInUser) && is_array($loggedInUser[0])) {
            $loggedInUserId = $loggedInUser[0]['userid'];
        }
        
        $formId = intval(trim($this->input->post('formId')));
        $formName = trim($this->input->post('name'));
        
        $errors = array();
        if(!$loggedInUserId) {
            $errors[] = "You do not have sufficient permissions to create this page.";
        }
        if(empty($this->userGroupData['group_id'])) {
            $errors[] = "You do not have sufficient permissions to create this page.";
        }
        if(!$formName) {
            $errors[] = "Form name is not entered.";
        }
        
        if(count($errors) > 0) {
            $data = array('formId' => $formId,'errors' => $errors);
            $this->load->view('mailer/MarketingFormMailer/error',$data);
        }
        else {
            $data = array(
                'formId' => $formId,
                'userId' => $loggedInUserId,
                'name' => $formName,
                'group_id' => $this->userGroupData['group_id']
            );
            $this->model->saveForm($data);
            $msg = $formId ? "formUpdationSuccessful" : "formCreationSuccessful";
            header('Location: /mailer/MarketingFormMailer/listForms?msg='.$msg);
            exit();
        }
    }
    
    /**
     * Download MIS CSV for a form
     * specified by mfid
     */ 
    function MIS($mfid)
    {
        /**
         * Fetch MIS data
         */ 
        $misData = $this->model->getMISData($mfid);
        
        /**
         * These fields are static
         */ 
        $fields = array('Name','Email ID','Mobile Number');
        
        $otherFields = array();
        
        foreach($misData as $data) {
            $formData = json_decode($data['formData'],TRUE);
            
            $formFields = array();
            foreach($formData as $key => $value) {
                if(is_array($value)) {
                    for($i=0;$i<count($value);$i++) {
                        $formFields[] = $key."^".$i;
                    }
                }
                else {
                    $formFields[] = $key;
                }
            }
            
            if(count($formFields) > count($otherFields)) {
                $otherFields = $formFields;
            }
        }
        
        $fields = array_merge($fields,$otherFields);
        
        $csvData = array();
        
        foreach($fields as $field) {
            $fieldParts = explode("^",$field);
            if(count($fieldParts) == 2) {
                $csvData[0][] = $fieldParts[0].($fieldParts[1]+1);
            }
            else {
                $csvData[0][] = $field;
            }
        }
        
        $k = 1;
        foreach($misData as $data) {
            
            $csvData[$k][] = $data['firstName']." ".$data['lastName'];
            $csvData[$k][] = $data['email'];
            $csvData[$k][] = $data['mobile'];
            
            $misFormData = json_decode($data['formData'],TRUE);
            
            foreach($otherFields as $field) {
                $fieldParts = explode("^",$field);
                if(count($fieldParts) == 2) {
                    $csvData[$k][] = isset($misFormData[$fieldParts[0]]) ? $misFormData[$fieldParts[0]][$fieldParts[1]] : "";
                }
                else {
                    $csvData[$k][] = $misFormData[$field];
                }
            }
    
            $k++;
        }
      
        $csvDataStr = '';
        foreach($csvData as $data) {
            $csvDataStr .= '"'.implode('","',$data).'"'."\n";
        }
      
        $filename = "mis.csv";
        $mime = 'text/x-csv';
      
        if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
            header('Content-Type: "' . $mime . '"');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header("Content-Transfer-Encoding: binary");
            header('Pragma: public');
            header("Content-Length: " . strlen($csvDataStr));
        } else {
            header('Content-Type: "' . $mime . '"');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header("Content-Transfer-Encoding: binary");
            header('Expires: 0');
            header('Pragma: no-cache');
            header("Content-Length: " . strlen($csvDataStr));
        }
        echo ($csvDataStr);      
    }
}