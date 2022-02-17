<?php

require APPPATH.'/modules/Listing/listing/controllers/posting/AbstractListingPost.php';

class ListingPublisher extends AbstractListingPost
{
    private $listingPublishModel;
    
    function __construct()
    {
		parent::__construct();
		
        $this->load->model('listing/posting/listingpublishmodel');
		$this->load->library('Subscription_client');
        $subsciptionConsumer = new Subscription_client();
		
        $this->listingPublishModel = new ListingPublishModel($subsciptionConsumer);

        $this->courseMigrationLibrary = $this->load->library('listingMigration/CourseMigrationLibrary');
    }
    
    function publish()
    {    	
    	return;
    }
	
	private function _checkForTransactionLock($listings)
	{
		$this->load->library('cacheLib');
		$cacheLib = new CacheLib;
		
		/**
		 * Check for transaction lock
		 */
		$transactionLockTaken = FALSE;
		foreach($listings as $listingInTransaction) {
			/**
			 * Check if transaction lock is taken on listing
			 */
			if($cacheLib->get('TransactionLock_'.$listingInTransaction['type'].'_'.$listingInTransaction['typeId']) == 'On') {
				$transactionLockTaken = TRUE;
				break;
			}
		}
		
		if($transactionLockTaken) {
			
			/**
			 * Send back to publish page
			 */ 
			
			$publishInstituteId = 0;
			foreach($listings as $listingInTransaction) {
				if($listingInTransaction['type'] == 'institute') {
					$publishInstituteId = $listingInTransaction['typeId'];
				}
			}
			
			header("location:/enterprise/ShowForms/showPreviewPage/".$publishInstituteId."?transactionAlert=1");
			exit();
		}
		else {
			
			/**
			 * Take transaction lock on all the listings
			 */
			foreach($listings as $listingInTransaction) {
				$cacheLib->store('TransactionLock_'.$listingInTransaction['type'].'_'.$listingInTransaction['typeId'],'On',900,'TransactionLock');
			}
		}
	}
	
	private function _releaseTransactionLock($listings)
	{
		$this->load->library('cacheLib');
		$cacheLib = new CacheLib;
		
		foreach($listings as $listingInTransaction) {
			$cacheLib->clearCacheForKey('TransactionLock_'.$listingInTransaction['type'].'_'.$listingInTransaction['typeId']);
		}
	}

	private function listingCacheRemoveForExamsList($listings){
		$this->load->library('cacheLib');
		$cacheLib = new CacheLib;
		foreach($listings as $listing) {
			$cacheLib->clearCacheForKey('abroadExamsListForCoursePage_'.$listing['typeId']);
		}
	}
}
