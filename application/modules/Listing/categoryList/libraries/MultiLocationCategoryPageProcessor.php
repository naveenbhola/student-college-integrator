<?php
/*
 * This library performs certain operations that include
 * - rotation (via roataion service)
 * - consolidation
 * - pagination 
 * - data population for multi location category pages
 */

class MultiLocationCategoryPageProcessor
{
    private $inventoryBuckets;  // category sponsor/main/paid/free
    private $dataToBeProcessed; // location wise institute,course ids clubbed at inventory level
    private $processedDataForAllInventories; // data received after processing of above($dataToBeProcessed)
    private $consolidatedList; // finalized list as per given logic of showing first 5 Category Sponsors -> MILs -> remaining CS -> Paid -> Free
    //private $mainRotationIndex; // rotationIndex across all inventory buckets
    private $rotationService;
    private $institutesPerPage; // for pagination
    
    public function __construct()
    {
	$this->CI = & get_instance();
	$this->CI->config->load('categoryPageConfig');
	// this can be put inside config ???
	$this->inventoryBuckets = array('cs','main','paid','free');
	$this->dataToBeProcessed = array();
	//$this->mainRotationIndex = 0;
    }
    public function processLocationWiseData($baseArray,$rotationService)
    { 	//_p($baseArray);
	// received rotation service
	$this->rotationService = $rotationService;
	// apply rotation service...
	$baseArray = $this->rotationService->rotateLocationWiseInventory($baseArray);
	//echo "AFTER ROTATION ";_p($baseArray );
	
	$processedArray = array();
	$this->processedDataForAllInventories = array();
	foreach($this->inventoryBuckets as $bucketType)
	{     //echo "<br>dumstrag";_p($this->processInventoryBucketData($baseArray,$bucketType));echo "<br>dumstrag END";
	    $this->processedDataForAllInventories[$bucketType] = $this->processInventoryBucketData($baseArray,$bucketType);
	    //break;
	}
	// consolidate bucketwise data to create a finalized list
	$this->consolidateInventoryWiseData();
	//
	return $this->consolidatedList;
    }
    /*
     * function to get inventory wise data processed for all locations
     * note: this is useful once processLocationWiseData() is run
     */
    public function getProcessedDataForAllInventories()
    {
	return $this->processedDataForAllInventories;
    }
    /*
     * This function processes the data having locationwise institute data for each inventory
     * @params : $baseArray ($locationWise data)
     */
    public function processInventoryBucketData($baseArray, $currentBucket)
    {
    // STEP 1. pick a bucket (cs for now)
	$processedArray = array();
	
    // STEP 2. find location with max length of current bucket's array
	$locationWithMaxInstituteInBucket = $this->getLocationWithMaxInstitutesInBucket($baseArray,$currentBucket);
	//echo "</br> find location with max length of current bucket's array";_p($locationWithMaxInstituteInBucket);
	
    // STEP 3. Initialize a common insertion index
	$insertionIndex = 0;
	
    // STEP 4. Loop over all locations until all have been picked up.
	//This will be ensured by running this loop as many no of times as the max num of institutes found in any of the locations for current bucket
	while($insertionIndex < $locationWithMaxInstituteInBucket['maxLength']){
	    foreach($baseArray as $k => $locationWiseData)
	    {
		$keyMap = array_keys($locationWiseData['instituteData'][$currentBucket]);
		//echo "</br>keymap";_p($locationWiseData['data'][$currentBucket][$keyMap[$insertionIndex]]);
		if(count($locationWiseData['instituteData'][$currentBucket][$keyMap[$insertionIndex]])>0){
		    $processedArray[$keyMap[$insertionIndex]]	= $locationWiseData['instituteData'][$currentBucket][$keyMap[$insertionIndex]];
		}
	    }
	    //
	    $insertionIndex++;
	}
	//echo "processed array for cs bucket..[$currentBucket]. needs to be dissected for first 5 & remaining cs list";
	//_p($processedArray);
	return $processedArray;
    }
    
    /*
     * This function returns the location that has max no of institutes within the bucket passed 
     * @params :: $baseArray (i.e. the location - inventory Wise institute,courses distribution array)
     * 	      $currentBucket (i.e. the inverntory bucket for which institutes would be counted)
     */
    private function getLocationWithMaxInstitutesInBucket($baseArray,$bucket)
    {
	$location = $maxLength= 0;
	foreach ($baseArray as $k=>$locationWiseData)
	{ //echo "</br>for loc id  = ".$locationWiseData['location_id']." , count: "._p($locationWiseData['instituteData'][$bucket]);
	    if($location == 0 || count($locationWiseData['instituteData'][$bucket]) > $maxLength)
	    {$maxLength = count($locationWiseData['instituteData'][$bucket]);
	     $location  = $locationWiseData['location_id'];
	    }
	}
	return array('maxLength'=>$maxLength , 'locationId'=>$location);
    }
	
    //public function applyRotation()
    //{}
    
    /*
     * this function consolidates data from inventory wise buckets to single array as per the Locationwise rendering logic
     *
     */
    public function consolidateInventoryWiseData()
    {
	//echo "<br>preconsolidated data";
	//_p($this->processedDataForAllInventories);
	$this->consolidatedList = array();
	$remainingCS = $first5CS = $mil = $paid = $free = array();
	
	foreach($this->processedDataForAllInventories as $inventoryBucket => $processedData)
	{
	    
	    if($inventoryBucket == 'cs'){
		// category sponsors need to be sliced into 2 groups: first 5 & the rest
		$count = 0;
		foreach($processedData as $instituteId => $institute)
		{
		    if($count < 5)
			{ $first5CS[$instituteId] = $institute; }
		    else
			{ $remainingCS[$instituteId] = $institute; }
		    // increment count
		    $count++;
		}//echo "first5CS:";_p($first5CS);echo "remCS:";_p($remainingCS );
	    }
	}
	$this->consolidatedList = $first5CS + $this->processedDataForAllInventories['main'] + $remainingCS + $this->processedDataForAllInventories['paid'] + $this->processedDataForAllInventories['free'];
	//echo "<br>final consolidated list::".count($this->consolidatedList)." ::";_p($this->consolidatedList);
    }
}