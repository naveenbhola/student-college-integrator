<?php 
Class CollegeReviewSolrClient {
	private $solrSearchSever;
	private $solrRequestGenerator;
    private $solrResponseParser;

	function _init() {
		$this->CI = & get_instance();
        $this->CI->config->load('search_config');
        $this->CI->load->builder('SearchBuilder','search');
        $this->solrSearchSever = SearchBuilder::getSearchServer($this->CI->config->item('search_server'));

        $this->CollegeReviewSolrRequestGenerator = $this->CI->load->library('CollegeReviewForm/solr/CollegeReviewSolrRequestGenerator');
        
        $this->CollegeReviewSolrResponseParser = $this->CI->load->library('CollegeReviewForm/solr/CollegeReviewSolrResponseParser');
	}

	function getAggregateReviews($solrRequestData) {
		$this->_init();
		$aggregateReviewUrl = $this->CollegeReviewSolrRequestGenerator->getAggregateReviewsUrl($solrRequestData);
		$solrUrl = $this->solrSearchSever->getSolrUrl('collegereview','select');
		$solrAggregatedReviews = unserialize($this->solrSearchSever->curl($solrUrl, $aggregateReviewUrl));
		return $this->CollegeReviewSolrResponseParser->parseAggregateReviewsData($solrAggregatedReviews);
	}

	function getAggregateReviewsForMultipleCourses($courseIds){
		$this->_init();
		$aggregateReviewUrl = $this->CollegeReviewSolrRequestGenerator->getAggregateReviewUrlForMultipleCourses($courseIds);
		$solrUrl = $this->solrSearchSever->getSolrUrl('collegereview','select');
		// _p($solrUrl.'?'.$aggregateReviewUrl);die;
		$solrAggregatedReviews = unserialize($this->solrSearchSever->curl($solrUrl, $aggregateReviewUrl));
		return $this->CollegeReviewSolrResponseParser->parseAggregateReviewsDataForMultipleCourses($solrAggregatedReviews);
	}

	public function getPlacementTopicTagsForReviews($solrRequestData){
		
		$this->_init();
		$placementTagsUrl = $this->CollegeReviewSolrRequestGenerator->getCollegeReviewPlacementTagsUrl($solrRequestData);

		$solrUrl = $this->solrSearchSever->getSolrUrl('collegereview','select');

		$solrPlacementTags = unserialize($this->solrSearchSever->curl($solrUrl, $placementTagsUrl));
		
		return $this->CollegeReviewSolrResponseParser->parseAggregateReviewsDataForPlacementTags($solrPlacementTags);
	}
}