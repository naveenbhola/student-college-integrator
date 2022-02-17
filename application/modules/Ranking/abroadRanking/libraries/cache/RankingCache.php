<?php

class RankingCache extends Cache
{
	function __construct()
	{
		parent::__construct();
	}
	
	/*
	 * function to get Ranking Page Object from cache
	 */ 
	public function getRankingPage($rankingPageId)
	{
		$rankingObject = $this->get('RankingPage',$rankingPageId);
		return $rankingObject;
	}
	public function getMultipleRankingPages($rankingPageIds){
		$rankingObjects =  $this->multiGet('RankingPage',$rankingPageIds);
		return $rankingObjects;
	}
	
	/*
	 * function to store Ranking Page Object to cache
	 */
	public function storeRankingPage($rankingPageId,$rankingObject)
	{
		$this->store('RankingPage',$rankingPageId, $rankingObject,-1, NULL, 1);
	}
	
	/*
	 * delete ranking page object from cache (when ranking page gets deleted)
	 */
	public function deleteRankingPage($rankingPageId)
	{
		$this->delete('RankingPage',$rankingPageId);
	}
	
	public function storeCurrencyDetails($currencyDetails){
		
		$this->store('RankingPageCurrencyDetails',1,$currencyDetails,43200,'',0);
	}
	
	public function getCurrencyDetails()
	{
		return $this->get('RankingPageCurrencyDetails',1);
	}
}
