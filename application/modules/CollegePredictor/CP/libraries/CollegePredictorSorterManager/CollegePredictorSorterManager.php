<?php

class CollegePredictorSorterManager{
	public function __construct($_ci){
		if(!empty($_ci)){
			$this->_ci = $_ci;
		}
	}

	public function applySorter($branchObj, CollegePredictorSorter $collegepredictorSorter){
		$sortKey   		= $collegepredictorSorter->getKey();
		$sortOrder 		= $collegepredictorSorter->getOrder();
		$sortKeyValue 		= $collegepredictorSorter->getKeyValue();
		
		switch($sortKey){
			case 'branch':
				$this->sortByBranch($branchObj, $sortOrder);
				break;
			case 'rank':
				$this->sortByRank($branchObj, $sortOrder);
				break;
			case 'college':
				$this->sortByCollege($branchObj, $sortOrder);
				break;
		}

	}

	public function sortByRank(Branch $branchObj, $sortOrder = NULL){
		if(empty($sortOrder) || empty($branchObj)){
			return;
		}
		$pageData = $branchObj->getPageData();
		switch($sortOrder){
			case 'asc':
				usort($pageData, array($this, 'rankCompareASC'));
				break;
			case 'desc':
				usort($pageData, array($this, 'rankCompareDESC'));
				break;
		}
		//error_log(print_r($pageData,true),3,'/home/pranjul/Desktop/pranjul.txt');
		$branchObj->setPageData($pageData);
	}

	public function sortByBranch(Branch $branchObj, $sortOrder = NULL){
		if(empty($sortOrder) || empty($branchObj)){
			return;
		}
		$pageData = $branchObj->getPageData();
		switch($sortOrder){
			case 'asc':
				usort($pageData, array($this, 'sortByCollegeCompareASC'));
				break;
			case 'desc':
				usort($pageData, array($this, 'branchCompareDESC'));
				break;
		}
		//error_log(print_r($pageData,true),3,'/home/pranjul/Desktop/pranjul.txt');
		$branchObj->setPageData($pageData);
	}
	
	public function sortByCollege(Branch $branchObj, $sortOrder = NULL){
		if(empty($sortOrder) || empty($branchObj)){
			return;
		}
		$pageData = $branchObj->getPageData();
		switch($sortOrder){
			case 'asc':
				usort($pageData, array($this, 'collegeCompareASC'));
				break;
			case 'desc':
				usort($pageData, array($this, 'collegeCompareDESC'));
				break;
		}
		//error_log(print_r($pageData,true),3,'/home/pranjul/Desktop/pranjul.txt');
		$branchObj->setPageData($pageData);
	}


	public function rankCompareASC($a, $b){
		return (int)$a->getClosingRank() - (int)$b->getClosingRank();
	}

	public function rankCompareDESC($a, $b){
		//error_log($a->getClosingRank().'-'.$b->getClosingRank().'<br/>',3,'/home/pranjul/Desktop/pranjul.txt');
		return (int)$b->getClosingRank() - (int)$a->getClosingRank();
	}


	public function branchCompareASC($a, $b){
		return strcmp($a->getBranchName(), $b->getBranchName());
	}

	public function branchCompareDESC($a, $b){ 
		$cmp = strcmp($a->getBranchName(), $b->getBranchName());
      		return -$cmp;
	}
	
	public function collegeCompareASC($a, $b){
		return strcmp($a->getCollegeName(), $b->getCollegeName());
	}

	public function collegeCompareDESC($a, $b){ 
		$cmp = strcmp($a->getCollegeName(), $b->getCollegeName());
      		return -$cmp;
	}

	/**
	 *@method: Get sorter request object based on key name and sort order
	*/
	public function getSortRequestObject($keyName = NULL, $sortOrder = NULL, $keyValue = ""){
		$this->_ci->load->library("CP/CollegePredictorSorterManager/CollegePredictorSorter");
		$collegepredictorSortObject = false;
		$collegepredictorSortObject = new CollegePredictorSorter();
		$collegepredictorSortObject->setKey($keyName);
		$collegepredictorSortObject->setOrder($sortOrder);
		$collegepredictorSortObject->setKeyValue($keyValue);
		return $collegepredictorSortObject;
	}
}
?>
