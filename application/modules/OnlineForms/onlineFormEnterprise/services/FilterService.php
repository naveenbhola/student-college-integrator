<?php
namespace onlineFormEnterprise\services;
class FilterService{
	private $filters;
	private $filterKeys;
	public function setFilters($filters){
		$this->filters = array();
		foreach($filters as $filter){
			if($filter && $filter['value'] !== ""){
				if($filter['type'] == "equal"){
					$newFilter = new  \onlineFormEnterprise\libraries\Filter\EqualFilter($filter);
				}elseif($filter['type'] == "greaterThan"){
					$newFilter = new  \onlineFormEnterprise\libraries\Filter\GreaterThanFilter($filter);
				}elseif($filter['type'] == "lessThan"){
					$newFilter = new  \onlineFormEnterprise\libraries\Filter\LessThanFilter($filter);
				}elseif($filter['type'] == "similar"){
					$newFilter = new  \onlineFormEnterprise\libraries\Filter\SimilarFilter($filter);
				}elseif($filter['type'] == "in"){
					$newFilter = new  \onlineFormEnterprise\libraries\Filter\InFilter($filter);
				}else{
					$newFilter = false;
				}
				$this->filters[] = $newFilter;
				$this->filterKeys[$filter['field']] = $newFilter;
			}
		}
	}
	
	public function filterForms(& $forms,$fm,$excludedForms){
		if(count($this->filters) > 0 || count($excludedForms)){
			$finalForms = array();
			foreach($forms as $key=>$form){
				if($this->applyIndividualFilter($form,$key,$fm,$excludedForms)){
					$finalForms[$key] = $form;
				}
			}
			$forms = $finalForms;
		}
	}
	
	
	public function applyIndividualFilter($form,$key,$fm,$excludedForms){
		if(!in_array($key,$excludedForms)){
			foreach($this->filters as $filter){
				if($filter){
					if(!$filter->applyFilter($form,$fm)){
						return false;
					}
				}
			}
			return true;
		}else{
			return false;
		}
	}
	
	
	public function getFilters(){
		
		return $this->filters;
	}
	
	public function getFilterKeys(){
		return $this->filterKeys;
	}
}