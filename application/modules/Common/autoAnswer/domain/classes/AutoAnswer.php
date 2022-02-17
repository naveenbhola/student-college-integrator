<?php

class AutoAnswerClass
{

	function __construct($request, $bucketFinderService){

		$this->request = $request;
		$this->bucketFinderService = $bucketFinderService;
	}

	function getReply(){

		/**
		 * 1. Determine the bucket with input string
		 * 2. Call the bucketing service for each satisfying bucket
		 * 3. return the first output got else return "nothing found"
		 */

		// 1. 
		$buckets = $this->bucketFinderService->find($this->request);
		_p($buckets);
	}
}
?>


