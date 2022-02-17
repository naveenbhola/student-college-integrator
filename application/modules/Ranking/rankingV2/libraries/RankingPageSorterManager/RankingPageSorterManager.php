<?php

class RankingPageSorterManager {
	
	public function __construct($_ci){
		if(!empty($_ci)){
			$this->_ci = $_ci;
		}
	}
	
	public function applySorter(RankingPage $rankingPage, RankingPageSorter $rankingPageSorter){
		if(empty($rankingPageSorter) || empty($rankingPage)){
			return;
		}
		$sortKey   		= $rankingPageSorter->getKey();
		$sortOrder 		= $rankingPageSorter->getOrder();
		$sortKeyValue 	= $rankingPageSorter->getKeyValue();
		switch($sortKey){
			case 'rank':
				$this->sortRankingPageByRank($rankingPage, $sortOrder);
				break;
			case 'fees':
				$this->sortRankingPageByFees($rankingPage, $sortOrder);
				break;
			case 'marks':
				$this->sortRankingPageByMarks($rankingPage, $sortOrder, $sortKeyValue);
				break;
			case 'institute_name':
				$this->sortRankingPageByInstituteName($rankingPage, $sortOrder);
				break;
			case 'salary':
				$this->sortRankingPageBySalary($rankingPage, $sortOrder);
				break;
			case 'examScore':
				$this->sortRankingPageByExamScore($rankingPage, $sortOrder, $sortKeyValue);
				break;
		}
	}
	
	public function sortRankingPageByRank(RankingPage $rankingPage, $sortOrder = NULL){
		if(empty($sortOrder) || empty($rankingPage)){
			return;
		}
		$rankingPageData = $rankingPage->getRankingPageData();
		switch($sortOrder){
			case 'asc':
				usort($rankingPageData, array($this, 'rankCompareASC'));
				break;
			case 'desc':
				usort($rankingPageData, array($this, 'rankCompareDESC'));
				break;
		}
		$rankingPage->setRankingPageData($rankingPageData);
	}
	
	public function sortRankingPageByFees(RankingPage $rankingPage, $sortOrder = NULL){
		if(empty($sortOrder) || empty($rankingPage)){
			return;
		}
		
		$rankingPageData = $rankingPage->getRankingPageData();
		switch($sortOrder){
			case 'asc':
				usort($rankingPageData, array($this, 'feesCompareASC'));
				break;
			case 'desc':
				usort($rankingPageData, array($this, 'feesCompareDESC'));
				break;
		}
		$rankingPage->setRankingPageData($rankingPageData);
	}
	
	public function sortRankingPageByMarks(RankingPage $rankingPage, $sortOrder = NULL, $sortKeyValue = NULL){
		if(empty($sortOrder) || empty($rankingPage) || empty($sortKeyValue)){
			return;
		}
		$rankingPageData = $rankingPage->getRankingPageData();
		usort($rankingPageData, array(new GeneralSorter($sortKeyValue, $sortOrder), "compareMarks"));
		$rankingPage->setRankingPageData($rankingPageData);
	}
	
	public function sortRankingPageByInstituteName(RankingPage $rankingPage, $sortOrder = NULL){
		if(empty($sortOrder) || empty($rankingPage)){
			return;
		}
		
		$rankingPageData = $rankingPage->getRankingPageData();
		switch($sortOrder){
			case 'asc':
				usort($rankingPageData, array($this, 'instituteNameASC'));
				break;
			case 'desc':
				usort($rankingPageData, array($this, 'instituteNameDESC'));
				break;
		}
		$rankingPage->setRankingPageData($rankingPageData);
	}
	
	public function sortRankingPageBySalary(RankingPage $rankingPage, $sortOrder = NULL){
		if(empty($sortOrder) || empty($rankingPage)){
			return;
		}
		
		$rankingPageData = $rankingPage->getRankingPageData();
		switch($sortOrder){
			case 'asc':
				usort($rankingPageData, array($this, 'salaryCompareASC'));
				break;
			case 'desc':
				usort($rankingPageData, array($this, 'salaryCompareDESC'));
				break;
		}
		$rankingPage->setRankingPageData($rankingPageData);
	}
	
	public function sortRankingPageByExamScore(RankingPage $rankingPage, $sortOrder = NULL, $sortKeyValue){
		if(empty($sortOrder) || empty($rankingPage)){
			return;
		}
		
		$this->sortKeyValue = $sortKeyValue;
		$rankingPageData = $rankingPage->getRankingPageData();
		switch($sortOrder){
			case 'asc':
				usort($rankingPageData, array($this, 'examScoreCompareASC'));
				break;
			case 'desc':
				usort($rankingPageData, array($this, 'examScoreCompareDESC'));
				break;
		}
		$rankingPage->setRankingPageData($rankingPageData);
	}

	/**
	 *@method: Get sorter request object based on key name and sort order
	*/
	public function getSortRequestObject($keyName = NULL, $sortOrder = NULL, $keyValue = ""){
		$rankingPageSortObject = false;
		$validSortKeys 		= $this->_ci->config->item('VALID_SORT_KEYS');
		$validSortOrders 	= $this->_ci->config->item('VALID_SORT_ORDERS');
		if(empty($keyName) || empty($sortOrder) || !in_array($keyName, $validSortKeys) || !in_array($sortOrder, $validSortOrders)){
			return $rankingPageSortObject;
		}
		$rankingPageSortObject = new RankingPageSorter();
		$rankingPageSortObject->setKey($keyName);
		$rankingPageSortObject->setOrder($sortOrder);
		$rankingPageSortObject->setKeyValue($keyValue);
		return $rankingPageSortObject;
	}
	
	public function rankCompareDESC($a, $b){
		return (int)$a->getRank() - (int)$b->getRank();
	}
	
	public function rankCompareASC($a, $b){
		return (int)$b->getRank() - (int)$a->getRank();
	}
	
	public function feesCompareASC($a, $b){
		if((int)$a->getfeesValueForSort() == (int)$b->getfeesValueForSort()){
			return (int)$a->getRank() - (int)$b->getRank();
		}
		if($a->getfeesValueForSort() != "" && $b->getfeesValueForSort() != ""){
			return (int)$a->getfeesValueForSort() - (int)$b->getfeesValueForSort();
		} else if($a->getfeesValueForSort() == "" && $b->getfeesValueForSort() != "") {
			return 1;
		} else if($a->getfeesValueForSort() != "" && $b->getfeesValueForSort() == ""){
			return -1;
		}
	}
	
	public function feesCompareDESC($a, $b){
		if((int)$a->getfeesValueForSort() == (int)$b->getfeesValueForSort()){
			return (int)$a->getRank() - (int)$b->getRank();
		}
		return (int)$b->getfeesValueForSort() - (int)$a->getfeesValueForSort();
	}
	
	public function instituteNameASC($a, $b){
		return strcmp($a->getInstituteName(), $b->getInstituteName());
	}
	
	public function instituteNameDESC($a, $b){
		return strcmp($b->getInstituteName(), $a->getInstituteName());
	}
	
	public function salaryCompareASC($a, $b){
		$naukriData_a = $a->getNaukriSalaryData();
		$naukriData_b = $b->getNaukriSalaryData();
		
		$avg_salary_a = $a->getAverageSalary();
		$avg_salary_b = $b->getAverageSalary();
		
		$salary_a  = $naukriData_a ? ($naukriData_a['ctc50'] * 100000): ($avg_salary_a ? $avg_salary_a : 0);
		$salary_b  = $naukriData_b ? ($naukriData_b['ctc50'] * 100000): ($avg_salary_b ? $avg_salary_b : 0);
		
		if($salary_a == 0 && $salary_b != 0)
			return 1;
		else if($salary_a != 0 && $salary_b == 0)
			return -1;
		else
			return (int)$salary_a - (int)$salary_b;
		//error_log("salary_a = ".$salary_a.", salary_b = ".$salary_b.", naukriData_a = ".$naukriData_a['ctc50'].", naukriData_b = ".$naukriData_b['ctc50'].", avg_salary_a = ".$avg_salary_a.", avg_salary_b = ".$avg_salary_b);
		
	}
	
	public function salaryCompareDESC($a, $b){
		$naukriData_a = $a->getNaukriSalaryData();
		$naukriData_b = $b->getNaukriSalaryData();
		
		$avg_salary_a = $a->getAverageSalary();
		$avg_salary_b = $b->getAverageSalary();
		
		$salary_a  = $naukriData_a ? ($naukriData_a['ctc50'] * 100000): ($avg_salary_a ? $avg_salary_a : 0);
		$salary_b  = $naukriData_b ? ($naukriData_b['ctc50'] * 100000): ($avg_salary_b ? $avg_salary_b : 0);
		
		if($salary_a == 0 && $salary_b != 0)
			return 1;
		else if($salary_a != 0 && $salary_b == 0)
			return -1;
		else
			return (int)$salary_b - (int)$salary_a;
	}
	
	public function examScoreCompareDESC($a, $b){
		$a_exams = $a->getExams();
		$b_exams = $b->getExams();
		
		$a_score = 0;
		$b_score = 0;
		
		foreach($a_exams as $exam)
		{
			if($exam['name'] == $this->sortKeyValue)
			{
				$a_score = $exam['marks'];
				break;
			}
		}
		
		foreach($b_exams as $exam)
		{
			if($exam['name'] == $this->sortKeyValue)
			{
				$b_score = $exam['marks'];
				break;
			}
		}
		
		return (($b_score - $a_score) > 0) ? 1 : -1;
	}
	
	public function examScoreCompareASC($a, $b){
		$a_exams = $a->getExams();
		$b_exams = $b->getExams();
		
		$a_score = 0;
		$b_score = 0;
		
		foreach($a_exams as $exam)
		{
			if($exam['name'] == $this->sortKeyValue)
			{
				$a_score = $exam['marks'];
				break;
			}
		}
		
		foreach($b_exams as $exam)
		{
			if($exam['name'] == $this->sortKeyValue)
			{
				$b_score = $exam['marks'];
				break;
			}
		}
		if(empty($b_score) && !empty($a_score))
			return -1;
		else if(empty($a_score) && !empty($b_score))
			return 1;
		else
			return (($b_score - $a_score) < 0) ? 1 : -1;
	}
}
