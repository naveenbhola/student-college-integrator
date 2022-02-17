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
}
