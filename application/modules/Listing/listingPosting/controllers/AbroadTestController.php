<?php

class AbroadTestController extends MX_Controller
{
    /**
    * Class data member declaration section
    */
    private $abroadCommonLib;
	
	/**
    * Constructor
    */
    public function __construct()
    {
		// load the config
		$this->config->load('studyAbroadCMSConfig');
		$this->abroadCommonLib 		= $this->load->library('listingPosting/AbroadCommonLib');
	}
    
    function testCurrencyExchangeRates($sourceCurrencyId = NULL, $destinationCurrencyId = NULL, $amount = NULL) {
		$convertedCurrency = $this->abroadCommonLib->convertCurrency($sourceCurrencyId, $destinationCurrencyId, $amount);
		_p("ORIGNIAL VALUE: " . $amount);
		_p("CONVERTED VALUE: " . $convertedCurrency);
	}
    
	function abroadCourse($courseId) {
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder 			= new ListingBuilder;
		$courseRepo = $listingBuilder->getCourseRepository();
		$courseObjs = $courseRepo->find($courseId);
 		  //$courseObjs = $courseRepo->findMultiple(array(979));
		_p($courseObjs);
	}
	
	function abroadInstitute($instId) {
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder 			= new ListingBuilder;
		$insRepo = $listingBuilder->getInstituteRepository();
		$insObj = $insRepo->find($instId);
		//$insObj = $insRepo->findMultiple(array(169));
		
		_p($insObj);
		
	}

   	function resolveInstituteLocationIdConflictForAbroad($instituteId) {
		$this->abroadCmsModelObj	= $this->load->model('listingPosting/abroadcmsmodel');
		$this->abroadCmsModelObj->resolveInstituteLocationIdConflictForAbroad($instituteId);
		
	}

   	function downLoadFile($file){
		
		
		if (file_exists($file)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/csv');
			header('Content-Disposition: attachment; filename='.basename($file));
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			ob_clean();
			flush();
			readfile($file);
			exit;
		} else {
			echo "FILE NOT GENERATED";
		} 
		
	}
	
	public function generateInaccurateContactsReports() {
		$this->listingReports = $this->load->library('listing/ListingReports');
		$file = $this->listingReports->generateReportForInaccurateReportedContacts();
		$this->downLoadFile($file);
	}

	public function releaseTransactionLock($type,$typeId)
	{
		$this->load->library('cacheLib');
		$cacheLib = new CacheLib;
		
		$cacheLib->clearCacheForKey('TransactionLock_'.$type.'_'.$typeId);
	}

	// This is a temporary code solution to get data for users' tag affinity in Redis Cache System
	/*public function getTagAffinityForUsers(){
		$predisLibrary = PredisLibrary::getInstance();
		$userIds = array(4912685,5536867,5603343,5832233,5850636,5863353,387913,1030868,1299021,1569168,2917994,3197356,3282297,4732058,5062211,5223079,5227797,5228907,5230542,5253509,5330783,5387950,5429541,5440544,5455632,5566085,5630309,5641403,4081459,4399041,5399409,5541163,5669055,5674907,5679241,5847684,5860551,5861727,5872524,5895418,4619058,4928992,5524320,5549582,5808289,5827271,5829858,5846263,5850660,5873713,5886413,5892721,271920,5233568,5251780,5264419,5319354,5319420,5319451,5322225,5333314,5454578,5460377,5492143,5566673,5614851,5649704,5699363,5812525,5856644,5871622,3391394,5223325,5227492,5248134,5283189,5319250,5319587,5330856,5336487,5360474,5391326,5614734,5647385,5858267,467451,888196,416188,1567994,2427159);
		error_log("\n ::::::::::::   TAG AFFINITY FOR USERS  :::::::::::: ",3,'/tmp/tagAffinityForUsers.log');
		foreach ($userIds as $value){
			$tagAffinityForUser = $predisLibrary->getMembersInSortedSet('userFollowsTag:user:'.$value, 0, -1, TRUE, TRUE);
			error_log("\n Users ID => ".$value." :: Tag Affnity => ".json_encode($tagAffinityForUser),3,'/tmp/tagAffinityForUsers.log');
		}
		error_log("\n  :::::::::::  ALL DONE  ::::::::::  ");
	}*/
}
