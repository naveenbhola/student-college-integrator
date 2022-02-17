<?php

class AutoAnswerBuilder
{
    private $CI;
    private $request;
    
    function __construct($params = array())
    {
        $this->CI = & get_instance();
        $this->CI->load->library('autoAnswer/AutoAnswerRequest');
        $this->CI->load->config("bucketPatternsConfig");
        $this->request = new AutoAnswerRequest($params);
    }

    function getAutoAnswerBot(){

        global $bucketsConfig;
        global $bucketsPriority;
        $bucketsConfig = $this->CI->config->item("buckets");
        $bucketsPriority = $this->CI->config->item("buckets_priority");

    	$bucketFinderService = $this->getBucketFinderService();
    	$this->CI->load->domainClass('AutoAnswerClass','autoAnswer');

    	$AutoAnswer = new AutoAnswerClass($this->request, $bucketFinderService);

        return $AutoAnswer;
    }

    function getBucketFinderService(){

        $this->CI->load->service('BucketFinderService','autoAnswer');

		// load bucket services
		$this->CI->load->service(array('AbstractBucketService',
                                       'StaticBucketService',
									   'InstituteAttributesBucketService',
                                       'RankingBucketService')
									   ,'autoAnswer');    	

		$buckets = array(BUCKET_STATIC         => new StaticBucketService($this->request), 
						 BUCKET_INSTITUTE_ATTR => new InstituteAttributesBucketService($this->request),
                         BUCKET_RANKING        => new RankingBucketService($this->request));

		$bucketFinderService = new BucketFinderService($buckets);

		return $bucketFinderService;
    }
}
?>