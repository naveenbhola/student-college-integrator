<?php

namespace Online\managers;

class UserFormManager{
	private $userForms;
	private $dao;
	private $fields;
	private $context;
	private $sortableForms;
	private $eligibleFields;
	
	function __construct($context){
        $this->CI = & get_instance();
		$this->CI->load->model('Online/onlineparentmodel');
        $this->CI->load->model('Online/onlinemodel');
        $this->dao = new \OnlineModel();
		$this->context = $context;
		$this->userForms = array();
		$this->fields = array();
		$this->sortableForms = array();
    }
	
	public function populateForms($courseId,$filterSevice,$sorterService,$exculsionService){
		if($this->context == "EnterpriseDataGrid"){
			
			$fields = $this->_getFieldsData($courseId);
			$this->_setFields($fields,$filterSevice,$sorterService);
			$forms = $this->_getAllFormsData($courseId,$sorterService,$exculsionService);
			return $this->_generateProcessedEnterpriseFormData($forms,$filterSevice,$sorterService,$exculsionService);
		}
	}
	
	public function getForms(){
		return $this->userForms;
	}
	
	public function getFields(){
		return $this->fields;
	}
	
	/*
	 * Get Raw Data of both External and Internal Forms
	 */
	
	private function _getAllFormsData($courseId){
		$forms = array();
		$params = array(
			'fields' => $this->eligibleFields
		);

		$forms['external'] = $this->dao->getExternalFormsAndData($courseId,$params);
		$forms['internal'] = $this->dao->getInternalFormsAndData($courseId,$params);
	
		return $forms;
	}
	
	private function _setFields($fields,$filterSevice,$sorterService){
		
		$filters = $filterSevice->getFilterKeys();
		$sorterF = $sorterService->getField();
		
		$this->eligibleFields =  array();
		$this->eligibleFields['external'] = array();
		$this->eligibleFields['internal'] = array();
		$this->eligibleFields['custom'] = array();
		
		foreach($fields['external'] as $key=>$field){
			$newField = array();
			$newField['id'] = $key;
			$order = $field['order'];
			$newField['order'] = $field['order'];
			$newField['name'] = $field['fieldName'];
			$newField['typeOfField'] = $field['typeOfField'];
			$newField['type'] = 'external';
			$newField['shikshaFields'] = $field['shikshaFields'];
			$this->fields[$key] =  $newField;
			if(array_key_exists($key,$filters) || $key == $sorterF){
				$fieldId = explode("_",$key);
				$this->eligibleFields['external'][] = $fieldId[1];
				foreach($field['shikshaFields'] as $f){
					$this->eligibleFields['internal'][] = $f['shikshaFieldName'];
				}
			}
		}

		foreach($fields['custom'] as $key=>$field){
			$order++;
			$newField = array();
			$newField['id'] = $key;
			$newField['order'] = $order;
			$newField['name'] = $field['fieldName'];
			$newField['type'] = 'custom';
			$this->fields[$key] =  $newField;
			if(array_key_exists($key,$filters) || $key == $sorterF){
				$fieldId = explode("_",$key);
				$this->eligibleFields['custom'][] = $fieldId[1];
			}
		}
	}
	
	
	private function _getFieldsData($courseId){
		$fields = array();
		$fields['external'] = $this->dao->getEnterpriseFieldMapping($courseId);
		$fields['custom'] = $this->dao->getCustomFields($courseId);
		return $fields;
	}

	private function _generateProcessedEnterpriseFormData($forms,$filterSevice,$sorterService,$exculsionService){
		
		foreach($forms['internal'] as $key=>$form){
			$newForm = $this->_convertInternalFormToExternal($form);
			if($filterSevice->applyIndividualFilter($newForm,$key,$this,$exculsionService->getExcludedForms())){
				$this->userForms[$key] = $newForm;
				$this->sortableForms[strtolower(trim($this->getFieldValue($newForm,$sorterService->getField())))][] = $key;
			}
 		}
		
		foreach($forms['external'] as $key=>$form){
			if($filterSevice->applyIndividualFilter($form,$key,$this,$exculsionService->getExcludedForms())){
				$this->userForms[$key] = $form;
				$this->sortableForms[strtolower(trim($this->getFieldValue($form,$sorterService->getField())))][] = $key;
			}
		}
		
		$order = 0;
		return $this->userForms;
	}
	
	private function _convertInternalFormToExternal($form){
		$newForm = array();
		$newForm['type'] = "internal";
		$newForm['fields'] = array();
		foreach($this->fields as $key2=>$field){
			$newForm['fields'][$key2] = array();
			$newForm['fields'][$key2]['typeOfField'] = $field['typeOfField'];
			$newForm['fields'][$key2]['name'] = $field['fieldName'];
			$newForm['fields'][$key2]['value'] = "";
			$newForm['fields'][$key2]['shikshaFields'] = array();
			foreach($field['shikshaFields'] as $mapping){
				if($form['fields']["int_".$mapping['shikshaFieldName']]['value']){
					$newForm['fields'][$key2]['value'] .= $form['fields']["int_".$mapping['shikshaFieldName']]['value'].($mapping['Separator']=="Space"?" ":$mapping['Separator']);
				}
			}
		}
		foreach($form['customFields'] as $key2=>$field){
			$newForm['fields'][$key2] = $field;
		}
		return $newForm;
	}
	
	public function getFormByIds($courseId,$formIds){
		
		$finalForms = array();
		foreach($formIds as $formId){
			$form = explode('_',$formId);
			if($form[0] == 'int'){
				$internal[] = $form[1];
			}else{
				$external[] = $form[1];
			}
			
		}
		
		$params = array();
		$params['forms'] = $external;
		$forms['external'] = $this->dao->getExternalFormsAndData($courseId,$params);
		$params['forms'] = $internal;
		$forms['internal'] = $this->dao->getInternalFormsAndData($courseId,$params);
	
		foreach($formIds as $formId){
			$form = explode('_',$formId);
			if($form[0] == 'int'){
				$finalForms[$formId] = $this->_convertInternalFormToExternal($forms['internal'][$formId]);
			}else{
				$finalForms[$formId] = $forms['external'][$formId];
			}
		}
		return $finalForms;
	}
	
	//Utility function for single Form
	
	public function getFieldName($form,$fieldId){
		return $form['fields'][$fieldId]['name'];
	}
	
	public function getFieldValue($form,$fieldId,$date = false){
		if($form['fields'][$fieldId]){
			if($date){
				if($form['fields'][$fieldId]['typeOfField'] == "date"){
					return preg_replace("/(\d+)\D+(\d+)\D+(\d+)/","$3-$2-$1",$form['fields'][$fieldId]['value']);
				}else{
					return $form['fields'][$fieldId]['value'];
				}
			}else{
				return $form['fields'][$fieldId]['value'];
			}
		}else{
			return "";
		}
	}
	
	public function getFieldType($form,$fieldId){
		if($form['fields'][$fieldId]['typeOfField']){
			return $form['fields'][$fieldId]['typeOfField'];
		}else{
			return "text";
		}
	}
	
	public function getFormFields($form){
		return $form['fields'];
	}
	
	public function getFormType($form){
		return $form['type'];
	}
	
	
	public function getSortableForms(){
		return $this->sortableForms;
	}
	
	public function getEligibleFields(){
		return $this->eligibleFields;
	}
}

