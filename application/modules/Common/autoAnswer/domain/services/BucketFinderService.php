<?php

class BucketFinderService{

	private $request;
	private $buckets;

	function __construct($buckets){

		$this->buckets = $buckets;
	}

	function find($request){

		$matchingBuckets = array();

		$this->request = $request;
		global $bucketsPriority;

		// match the buckets
		foreach ($bucketsPriority as $bucketName) {

			$bucketObj    = $this->buckets[$bucketName];
			$isApplicable = $bucketObj->isApplicable();

			if($isApplicable !== false){
				$matchingBuckets[] = $bucketObj;
			}
		}

		return $matchingBuckets;
	}
}
?>