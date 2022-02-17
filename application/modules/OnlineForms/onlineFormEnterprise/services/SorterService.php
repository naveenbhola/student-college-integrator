<?php
namespace onlineFormEnterprise\services;
class SorterService{
	private $field;
	private $order;
	private $pages;
	private $noOfForms;
	
	public function setSorter($sorter){
		
		if($sorter){
			$this->field = $sorter['field'];
			$this->order = $sorter['order'];
		}
	}
	
	
	public function sortForms($sortableForms,$forms,$page,$limit,$download){
		if($this->field){
			if($this->order == "ASC"){
				ksort($sortableForms);
			}else{
				krsort($sortableForms);
			}
		}
		$this->noOfForms = count($forms);
		$i = 0;
		$returnForm = array();
		if($download || !$limit){
			$page = 1;
			$limit = $this->noOfForms;
		}
		$this->setPages($limit);
		foreach($sortableForms as $key=>$val){
			foreach($val as $formId){
				if($i < ($page-1)*$limit){
					$i++;
					continue;
				}
				$returnForm[] = $formId;
				$i++;
				if($i > ($page*$limit)-1){
					break;
				}
			}
			if($i > ($page*$limit)-1){
				break;
			}
		}
		return $returnForm;
	}

	public function getPages(){
		return $this->pages;
	}
	
	public function getNoOfForms(){
		return $this->noOfForms;
	}
	
	public function getField(){
		return $this->field;
	} 
	
	public function getOrder(){
		return $this->order;
	}
	
	public function setNoOfForms($x){
		$this->noOfForms = $x;
	}
	
	public function setPages($limit){
		$this->pages = ceil($this->noOfForms/$limit);
	}
}