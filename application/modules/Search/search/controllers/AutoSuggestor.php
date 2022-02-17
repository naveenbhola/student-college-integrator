<?php

class AutoSuggestor extends MX_Controller {
	
	private $searchServer;
	
	public function __construct() {
		$this->load->builder('SearchBuilder');
		$this->config->load('search_config');
		$this->searchServer = SearchBuilder::getSearchServer($this->config->item('search_server'));
	}
	
	public function autosuggestor() {
		$time_start = microtime(true);
		$textParam 				= (urldecode($this->input->post('text')) != false) ? urldecode($this->input->post('text')) : '';
		$requiredFiltersParam 	= (urldecode($this->input->post('requiredFilterString')) != false) ? urldecode($this->input->post('requiredFilterString')) : '';
		$achievedFiltersParam 	= (urldecode($this->input->post('achievedFilterString')) != false) ? urldecode($this->input->post('achievedFilterString')) : '';
		$achievedWordsParam 	= (urldecode($this->input->post('achievedWords')) != false) ? urldecode($this->input->post('achievedWords')) : '';
		$queryType 				= (urldecode($this->input->post('queryType')) != false) ? urldecode($this->input->post('queryType')) : 'prefixEdgeNGram';
		$eachfacetResultCount 	= (urldecode($this->input->post('eachfacetResultCount')) != false) ? urldecode($this->input->post('eachfacetResultCount')) : 10;
		$requiredFilters 		= explode("!$-$!", $requiredFiltersParam);
		$achievedFilters 		= explode("!$-$!", $achievedFiltersParam);
		$achievedWords 			= explode("!$-$!", $achievedWordsParam);
		
		$resultForCategoryId 	= (urldecode($this->input->post('resultForCategoryId')) != false) ? urldecode($this->input->post('resultForCategoryId')) : false;
		$suggestionIncludes 	= (urldecode($this->input->post('suggestionIncludes')) != false) ? urldecode($this->input->post('suggestionIncludes')) : false;
		$solr_results 			= "";
		if($textParam == '') {
			//get trending suggestions
			$solr_results = array();
			$solr_results['solr_facet_to_heading_mapping']['trending_searches'] = "Trending Searches";
			$TrendingSearchLib = $this->load->library('listingCommon/TrendingSearchLib');
    		$trendingSearchForCareer = $TrendingSearchLib->getTrendingSearches('career');
			$solr_results['career_name_id_map'] = $trendingSearchForCareer['nameIdMap'];
			$solr_results['solr_results']['trending_searches'] = $trendingSearchForCareer['trendingSearches'];
			$solr_results = json_encode($solr_results);
		}
		elseif($queryType == "prefixEdgeNGram"){
			$solr_results = $this->getSuggestionsByPrefixEdgeNGramMethod($textParam, $requiredFilters, $achievedFilters, $achievedWords, $eachfacetResultCount, $resultForCategoryId, $suggestionIncludes);
		}
		$time_end 	= microtime(true);
		$time 		= $time_end - $time_start;
		error_log("TIMEX time taken seconds: " . (string)$time/1000000 . " seconds");
		echo $solr_results;
	}

	public function getSuggestionsByPrefixEdgeNGramMethod($text, $requiredFilters = array(), $achievedFilters = array(), $achievedWords=array(), $eachfacetResultCount, $resultForCategoryId = false, $suggestionIncludes = false) {
		$solrResults = array();
		$solrUrls = array();
		if(count($requiredFilters) > 0){
			//creating fq param
			$achievedFiltersString = "";
			foreach($achievedFilters as $filterKey=>$filterValue){
				if(strlen(trim($filterValue)) > 0){
					$achievedFiltersString .= '&fq=' . $filterValue . ':' . '"'. urlencode($achievedWords[$filterKey]) . '"';
				}
			}
			//creating q param
			if(trim($text) != ""){
				$textToBeSearchedString = "&q=" . urlencode($text);
			} else {
				$textToBeSearchedString = "&q.alt=*:*";
			}
			$extraDefaultParams = "&facet=true&defType=edismax&fq=facetype:autosuggestor&wt=phps&rows=0&facet.zeros=false&";
			//creating qf param
			$queryFieldParms = array();
			$facetFieldParams = array();
			foreach($requiredFilters as $key=>$value){
				if(strlen(trim($value)) > 0 && $value != 'trending_searches'){
					$value = trim($value);
					$updateValue = $value;
					$pos = strrpos($value, "_");
					if ($pos !== false) {
						$updateValue = substr($value, 0, $pos);
						$updateValue .= "_edgeNGram";
					} else {
						$updateValue .= "_edgeNGram";
					}
					array_push($queryFieldParms, $updateValue);
					$facetFieldParams[$updateValue] = $value;
				}
			}
			$solrResults = array();
			foreach($queryFieldParms as $key=>$value){
				if($value == 'related_career_name_edgeNGram') {
					continue;
				}
				switch($value) {
					case "institute_title_edgeNGram":
						//creating Extra params required
						/*
						$extraDefaultParams = "";
						if($resultForCategoryId){
							$catIds = explode(",", $resultForCategoryId);
							$catIdString = "";
							foreach($catIds as $catId){
								$catIdString .= '"'.$catId.'"';
							}
							$extraDefaultParams = '&fq=course_category_id_list:('.$catIdString.')';
						}
						if($suggestionIncludes && $suggestionIncludes == 'reviews'){
							$extraDefaultParams .= "&stats=true&stats.field=course_review_count&stats.facet=institute_id&fq=course_review_count:[3%20TO%20*]";
						} else if($suggestionIncludes && $suggestionIncludes == 'cr'){
							$extraDefaultParams .= "&fq=course_cr_exist:1";
						}
						$solrUrl = $this->getSolrUrlForInstitute($value, $textToBeSearchedString, $facetFieldParams, $achievedFiltersString, $eachfacetResultCount, $extraDefaultParams);
						*/
						break;
					case "career_name_edgeNGram":
						$solrUrl = $this->getSolrUrlForCareer($value, $textToBeSearchedString, $facetFieldParams, $achievedFiltersString, $eachfacetResultCount);
						break;
					default:
						$solrUrl = $this->getSolrUrlForGeneralField($value, $textToBeSearchedString, $facetFieldParams, $achievedFiltersString, $eachfacetResultCount, $extraDefaultParams);
						break;
				}
				
				if($solrUrl != null){
					$solrUrls[] = $solrUrl;
					$solrContent = unserialize($this->searchServer->curl($solrUrl));
					switch($value){
						case "institute_title_edgeNGram":
							$instituteSolrResponse = $this->parseSolrResultsForInstitute($solrContent, $facetFieldParams[$value], "institute_id", $suggestionIncludes);
							$solrResults[$facetFieldParams[$value]] = $instituteSolrResponse;
							break;
						case "career_name_edgeNGram":
							$solrResults = $this->parseSolrResultsForCareer($solrContent, $facetFieldParams[$value], "career_id");
							if(empty($solrResults)) {
								$solrUrl = $this->getSolrUrlForRelatedCareerSearch($textToBeSearchedString);
								$solrUrls[] = $solrUrl;
								$solrContent = unserialize($this->searchServer->curl($solrUrl));
								$solrResults = $this->parseSolrResultsForCareer($solrContent, $facetFieldParams[$value], "career_id", "related");
								$solrResults['result_type'] = "related_careers";
							}
							break;
						default:
							if(count($solrContent["facet_counts"]["facet_fields"]) > 0){
								$solrResults[$facetFieldParams[$value]]  = $this->parseSolrResultsForFacetFields($solrContent,$facetFieldParams[$value]); 
							}
							break;
					}
				}
			}
		}
		$returnData["solr_results"] = $solrResults;
		$returnData["solr_urls"] = $solrUrls;
		if(!empty($solrResults['career_name_id_map'])) {
			$returnData['career_name_id_map'] = $solrResults['career_name_id_map'];
			unset($solrResults['career_name_id_map']);
		}
		$jsonEncodedData = json_encode($returnData);
		return $jsonEncodedData;
	}
	
	private function getSolrUrlForInstitute($fieldValue, $textToBeSearchedString, $facetFieldParams, $achievedFiltersString, $eachfacetResultCount, $extraDefaultParams) {
		$solrAutoSuggestorUrl = null;
		if(trim($fieldValue)  != ""){
			$qfFieldString = "";
			$qfFieldString .= "&qf=" . trim($fieldValue);
			$fieldStringValue = "&fl=".$facetFieldParams[$fieldValue].",institute_id,institute_view_count,course_review_count";
			$extraDefaultParams = $extraDefaultParams . "&defType=edismax&fq=facetype:course&group=true&group.field=institute_id&group.main=true&wt=phps&facet.zeros=false&sort=institute_view_count%20desc&rows=".$eachfacetResultCount;
			$solrAutoSuggestorUrl = SOLR_AUTOSUGGESTOR_URL . $textToBeSearchedString . $qfFieldString . $achievedFiltersString . $fieldStringValue . $extraDefaultParams;
		}
		return $solrAutoSuggestorUrl;
	}
	
	private function getSolrUrlForCareer($fieldValue, $textToBeSearchedString, $facetFieldParams, $achievedFiltersString, $eachfacetResultCount) {
		$solrAutoSuggestorUrl = null;
		if(trim($fieldValue)  != ""){
			$qfFieldString 		= "";
			$qfFieldString 		.= "&qf=" . trim($fieldValue);
			$fieldStringValue 	= "&fl=".$facetFieldParams[$fieldValue].",career_id,career_url";
			$extraDefaultParams = "&defType=edismax&fq=facetype:career&wt=phps&rows=". $eachfacetResultCount;
			$solrAutoSuggestorUrl = SOLR_AUTOSUGGESTOR_URL . $textToBeSearchedString . $qfFieldString . $achievedFiltersString . $fieldStringValue . $extraDefaultParams;
		}
		return $solrAutoSuggestorUrl;
	}
	
	private function getSolrUrlForRelatedCareerSearch($textToBeSearchedString){
		$solrAutoSuggestorUrl = null;
		if(trim($textToBeSearchedString)  != ""){
			$qfFieldString 		= "&qf=career_synonyms_edgeNGram";
			$fieldStringValue 	= "&fl=career_name_facet,career_id,career_url";
			$extraDefaultParams = "&defType=edismax&fq=facetype:career&wt=phps&rows=1000";
			$solrAutoSuggestorUrl = SOLR_AUTOSUGGESTOR_URL . $textToBeSearchedString . $qfFieldString . $fieldStringValue . $extraDefaultParams;
		}
		return $solrAutoSuggestorUrl;
	}
	
	private function getSolrUrlForGeneralField($fieldValue, $textToBeSearchedString, $facetFieldParams, $achievedFiltersString, $eachfacetResultCount, $extraDefaultParams){
		$solrAutoSuggestorUrl = null;
		if(trim($fieldValue)  != ""){
			$qfFieldString = "";
			$qfFieldString .= "&qf=" . trim($fieldValue);
			//creating facet params
			$facetFieldsString = "&facet.field=" . $facetFieldParams[$fieldValue] . "&" . "f." . $facetFieldParams[$fieldValue] . ".facet.limit=" . $eachfacetResultCount;
			$solrAutoSuggestorUrl = SOLR_AUTOSUGGESTOR_URL . $textToBeSearchedString . $qfFieldString . $achievedFiltersString . $facetFieldsString . $extraDefaultParams;	
		}
		return $solrAutoSuggestorUrl;
	}
	
	private function parseSolrResultsForCareer($solrResultContent, $paramKey, $paramValue, $resultType = '') {
		$results = array();
		if($solrResultContent['response']['numFound'] > 0){
			$solrDocs = $solrResultContent['response']['docs'];
			if($resultType != '') {
				$paramKey = $resultType."_".$paramKey;
			}
			$results[$paramKey] = array();
			foreach($solrDocs as $doc){
				$results[$paramKey][$doc["career_name_facet"]] = $doc["career_url"];
				$results['career_name_id_map'][$doc["career_name_facet"]] = $doc["career_id"];
			}
		}
		return $results;
	}
	
	private function parseSolrResultsForInstitute($solrResultContent, $paramKey, $paramValue, $suggestionIncludes = false){
		$data = array();
		$count = 0;
		$statsData = array();
		if($suggestionIncludes == 'reviews') {
			if(!empty($solrResultContent['stats'])){
				if(!empty($solrResultContent['stats']['stats_fields']['course_review_count']['facets'])){
					$d = $solrResultContent['stats']['stats_fields']['course_review_count']['facets'];
					foreach($d['institute_id'] as $instituteId => $stat){
						$statsData[$instituteId] = $stat['sum'];
					}
				}
			}	
		}
		
		
		if($solrResultContent['response']['numFound'] > 0){
			$solrDocs = $solrResultContent['response']['docs'];
			$keys = array();
			$changedKeys = array();
			$count = 0;
			foreach($solrDocs as $doc){
				$valueArr = array();
				if(array_key_exists($paramKey, $doc) && array_key_exists($paramValue, $doc)){
					if($paramKey == "institute_title_facet"){
						$v = $this->sanatizeInputText(html_entity_decode($doc[$paramKey]));
						if(!in_array($v, $keys)) {
							if($suggestionIncludes == 'reviews'){
								if(isset($statsData[$doc[$paramValue]])){
									$s = 'reviews';
									if($statsData[$doc[$paramValue]] == 1){
										$s = 'review';
									}
									$v .= " (".$statsData[$doc[$paramValue]]." ".$s.")";
									$data[$v] = $doc[$paramValue];
								}
							} else {
								$data[$v] = $doc[$paramValue];
							}
						} else {
							$originalV = $v;
							if(array_key_exists($v, $changedKeys)){
								$old = $changedKeys[$v];
								$v = $old . " ";
							} else {
								$new = $v." ";
								$changedKeys[$v] = $new;
								$v = $new;
							}
							$changedKeys[$originalV] = $v;
							$data[$v] = $doc[$paramValue];
						}
						array_push($keys, $v);
						$count++;
					} else {
						$data[$doc[$paramKey]] = $doc[$paramValue];	
					}
				}
			}
		}
		
		return $data; 
	}
	
	private function parseSolrResultsForFacetFields($solrResultContent, $paramKey){
		if($solrResultContent['response']['numFound'] > 0){
			foreach($solrResultContent["facet_counts"]["facet_fields"][$paramKey] as $ldb_course=>$count) {
				$sanitizedLdbCourseName = $this->sanatizeInputText($ldb_course);
				unset($solrResultContent["facet_counts"]["facet_fields"][$paramKey][$ldb_course]);
				$solrResultContent["facet_counts"]["facet_fields"][$paramKey][$sanitizedLdbCourseName] = $count;
			}
		}
		return $solrResultContent["facet_counts"]["facet_fields"][$paramKey];
	}
  
	private function sanatizeInputText($value){
		$updateValue = $value;
		$updateValue = preg_replace("/\//", " / ", $updateValue);
		$updateValue = preg_replace("/\./", "", $updateValue);
		$updateValue = preg_replace("/\(/", " ( ", $updateValue);
		$updateValue = preg_replace("/\)/", " ) ", $updateValue);
		$updateValue = preg_replace("/[^a-zA-Z0-9+&'\s\\(\\)\\/]/", "", $updateValue);
		$updateValue = preg_replace("/(\s)+amp(\s)+/", " and ", $updateValue);
		$updateValue = preg_replace("/quot/", "", $updateValue);
		$updateValue = preg_replace("/\s+/", " ", $updateValue);
		$updateValue = trim($updateValue);
		return $updateValue;
	
	}
	
}

?>
