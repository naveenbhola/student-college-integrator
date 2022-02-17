<?php

class CrModerationSolrResponseParser{
	public function parseCollegeReviewData($solrResponse){
		$parseData                        = array();
		$solrResponse                     = unserialize($solrResponse);
		$parseData['documents']           = $solrResponse['response']['docs'];
		$parseData['filter']              = $solrResponse['facet_counts']['facet_fields'];
		$parseData['totalDocumentCount']  = $solrResponse['response']['numFound'];
		$parseData['instituteWiseStatus'] = $solrResponse['facet_counts']['facet_pivot']['instituteId,reviewStatus'];
		return $parseData;
	}
}