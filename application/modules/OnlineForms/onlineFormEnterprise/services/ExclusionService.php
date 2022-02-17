<?php
namespace onlineFormEnterprise\services;
class ExclusionService{
	private $excludedFields;
	private $excludedForms;
	
	public function getDisplayableFields($fields){
		$finalFields = array();
		foreach($fields as $key=>$field){
			if(!in_array($key,$this->excludedFields)){
				$finalFields[$key] = $field;
			}
		}
		return $finalFields;
	}
	
	public function setExcludedFields($excludedFields){
		$this->excludedFields = $excludedFields;
	}
	
	public function setExcludedForms($excludedForms){
		$this->excludedForms = $excludedForms;
	}
	
	public function getExcludedForms(){
		return $this->excludedForms;
	}
	
	public function excludeForms(& $forms){
		$finalForms = array();
		foreach($forms as $key=>$form){
			if(!in_array($key,$this->excludedForms)){
				$finalForms[$key] =  $form;
			}
		}
		$forms = $finalForms;
	}
}