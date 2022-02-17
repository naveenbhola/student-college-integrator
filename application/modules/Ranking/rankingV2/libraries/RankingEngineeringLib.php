<?php

class RankingEngineeringLib {

	private $rankingRelatedLib;
	private $_ci;

	public function __construct($_ci,$rankingPageRelatedLib){
		if(!empty($rankingPageRelatedLib)){
			$this->rankingRelatedLib = $rankingPageRelatedLib;
			$this->_ci = $_ci;
		}
	}

	public function getCategoryRelatedLinks($rankingPageRequest){
		return  $this->rankingRelatedLib->getCategoryRelatedLinks($rankingPageRequest);
	}

}