<?php

class AutoSuggestorV2 extends MX_Controller {
	
	public function __construct() {
		$this->load->library('search/Solr/AutoSuggestorSolrClient');
        $this->autoSuggestorSolrClient = new AutoSuggestorSolrClient;

	    $this->load->config("nationalCategoryList/nationalConfig");
	    $this->FIELD_ALIAS = $this->config->item('FIELD_ALIAS');
	}

	/*
     * Send the parameters in post with keys as below -
     * 'text' => Input text characters (eg. 'iim').
     * 'eachfacetResultCount' => Maximum number of suggestions to show (default = 10).
     * 'suggestionType' => Input characters will be searched in this entity.
     * 'conditions' => Any filters or conditions to put before getting results (Format = array('stream' => array('2','3'), 'min_course_review' => '3', 'ca_exists' => 1))
     * 					[To see full list of valid conditions, refer SolrRequestGenerator library]
     * 'orderBy' => Sorting order of suggestions (default = 'relevance').
     * 					[Valid orders - 'name', 'result_count', 'relevance', 'institute_view_count']
     *
     * Description -
     * Will return URL strictly with parameters sent in the arguments. If any param is not set, it will not be considered in creating the URL.
     */
	public function getSuggestionsFromSolr($text) {
		$data['text'] = (urldecode($this->input->post('text', true)) != false) ? urldecode($this->input->post('text', true)) : '';
		$data['maxResultCount'] = (urldecode($this->input->post('eachfacetResultCount', true)) != false) ? urldecode($this->input->post('eachfacetResultCount', true)) : 10;
		$data['suggestionType'] = (urldecode($this->input->post('suggestionType', true)) != false) ? urldecode($this->input->post('suggestionType', true)) : 'institute';
		$data['suggestionsFor'] = (urldecode($this->input->post('suggestionIncludes', true)) != false) ? urldecode($this->input->post('suggestionIncludes', true)) : '';
		$data['filters'] = (urldecode($this->input->post('conditions', true)) != false) ? (array) json_decode(urldecode($this->input->post('conditions', true))) : '';
		$data['orderBy'] = ((urldecode($this->input->post('orderBy', true)) != 'false') && urldecode($this->input->post('orderBy', true)) != false) ? urldecode($this->input->post('orderBy', true)) : 'relevance';
		
		// $data['text'] = $text;
		// $data['maxResultCount'] = 10;
		// $data['suggestionType'] = 'question'; //'careers' //'ldbCourseAndInstitutes'

		if (!ctype_digit($data['maxResultCount'])) {
			return;
		}

		$solrResult = '';
		switch ($data['suggestionType']) {
			case 'course_and_institute':
				if($data['text'] == '') {
					$TrendingSearchLib = $this->load->library('listingCommon/TrendingSearchLib');
					$trendingSearchForCollegeCourse = $TrendingSearchLib->getTrendingSearches('collegeAndCourse');
					
					$solrResult['solr_facet_to_heading_mapping']['trending_searches'] = 'Trending Searches';
					$solrResult['solr_results']['trending_searches'] = $trendingSearchForCollegeCourse;
					// $solrResult = '{"solr_facet_to_heading_mapping":{"trending_searches":"Trending Searches","course_ldb_course_name_facet":"Courses","institute_title_facet":"Colleges \/ Universities"},"solr_results":{"trending_searches":{"Computer Science Engineering":"sp_108","Electronics & Communication Engineering":"sp_160","Civil Engineering":"sp_90","instt1":"i_1","instt2":"i_1","univ3":"u_1","univ4":"u_1"}},"solr_urls":["http:\/\/172.16.3.111:8984\/solr\/collection1\/select?wt=phps&defType=edismax&q=\"c\"&fq=facetype:autosuggestor&fq=nl_entity_type:(stream%20OR%20substream%20OR%20specialization%20OR%20base_course%20OR%20certificate_provider%20OR%20popular_group)&fq=-nl_entity_result_count:0&qf=nl_entity_name_edgeNGram+nl_entity_name_keywordEdgeNGram+nl_entity_name_en_keywordEdgeNGram+nl_entity_name_en_edgeNGram+nl_entity_synonyms_autosuggest+nl_entity_synonyms_keywordEdgeNGram+nl_entity_synonyms_spkeywordEdgeNGram+&fl=en:nl_entity_count_name_id_type_map&sort=nl_entity_result_count%20desc&rows=50","http:\/\/172.16.3.111:8984\/solr\/collection1\/select?wt=phps&defType=edismax&q=\"c\"&fq=facetype:course&qf=nl_institute_name_edgeNGram+nl_institute_name_keywordEdgeNGram^50+nl_institute_name_autosuggest^30+nl_institute_synonyms_autosuggest^1000+nl_institute_synonyms_keywordEdgeNGram^100+nl_institute_synonyms_spkeywordEdgeNGram^100&fl=in:nl_institute_name,id:nl_institute_id,it:nl_institute_type&group=true&group.main=true&group.field=nl_institute_id&sort=score%20DESC,nl_institute_view_count_year%20DESC&rows=50"]}';
					echo json_encode($solrResult);
					return;
				}

				$solrResult = $this->autoSuggestorSolrClient->getBaseEntitiesAndInsttSuggestionsFromSolr($data);

				if(isMobileRequest()) {
					$returnData["solr_facet_to_heading_mapping"]['course_ldb_course_name_facet'] = 'Course';
					$returnData["solr_facet_to_heading_mapping"]['institute_title_facet'] = 'College';
				} else {
					$returnData["solr_facet_to_heading_mapping"]['course_ldb_course_name_facet'] = 'Courses';
					$returnData["solr_facet_to_heading_mapping"]['institute_title_facet'] = 'Colleges / Universities';
				}
				break;
			
			case 'institute':
				$solrResult = $this->autoSuggestorSolrClient->getInsttSuggestionsFromSolr($data);
				
				if(isMobileRequest()) {
					$returnData["solr_facet_to_heading_mapping"]['course_ldb_course_name_facet'] = 'Course';
					$returnData["solr_facet_to_heading_mapping"]['institute_title_facet'] = 'College';
				} else {
					$returnData["solr_facet_to_heading_mapping"]['course_ldb_course_name_facet'] = 'Courses';
					$returnData["solr_facet_to_heading_mapping"]['institute_title_facet'] = 'Colleges / Universities';
				}
				break;

			case 'analytics':
				
				$solrResult = $this->autoSuggestorSolrClient->getSuggestionsForAnalyticsFromSolr($data);
				$returnData["solr_facet_to_heading_mapping"]['course_ldb_course_name_facet'] = 'Courses';
				$returnData["solr_facet_to_heading_mapping"]['institute_title_facet'] = 'Colleges / Universities';
				$returnData["solr_facet_to_heading_mapping"]['exam_facet'] = 'Exams';
				break;

			case 'exam':
				if($data['text'] == '') {
					//trending search
					$trendingSearchLib = $this->load->library('listingCommon/TrendingSearchLib');
					$trendingSearchForExam = $trendingSearchLib->getTrendingSearches('exam');
					$solrResult = array();
					$solrResult['solr_results']['trending_searches'] = $trendingSearchForExam;
					$solrResult["solr_facet_to_heading_mapping"]['trending_searches'] = 'Trending Searches';
					// $solrResult = '{"solr_results":{"trending_searches":{"Allahabad University LAT Exam":{"count":"1","name":"Allahabad University LAT Exam","id":"12864","type":"exam","url":"\/law\/exams\/allahabad-university-lat-exam"},"AMIE Exam":{"count":"1","name":"AMIE Exam","id":"13080","type":"exam","url":"\/b-tech\/exams\/amie-exam"},"ICET":{"count":"1","name":"ICET","id":"10002","type":"exam","url":"\/mba\/exams\/icet"},"AJEE":{"count":"1","name":"AJEE","id":"10306","type":"exam","url":"\/engineering\/exams\/ajee"},"Alliance AUEET":{"count":"1","name":"Alliance AUEET","id":"9931","type":"exam","url":"\/b-tech\/exams\/alliance-aueet"},"Assam CEE":{"count":"1","name":"Assam CEE","id":"12986","type":"exam","url":"\/b-tech\/exams\/assam-cee"},"Business & Management Studies Exams":{"count":"60","name":"Business & Management Studies Exams","subType":"stream","id":"1","type":"allexam","url":"\/business-management-studies\/exams-st-1"},"Teaching & Education Exams":{"count":"12","name":"Teaching & Education Exams","subType":"stream","id":"16","type":"allexam","url":"\/teaching-education\/exams-st-16"},"AICET":{"count":"1","name":"AICET","id":"11903","type":"exam","url":"\/design\/fashion-design\/exams\/aicet"},"ALLIANCE AMAT":{"count":"1","name":"ALLIANCE AMAT","id":"13198","type":"exam","url":"\/mba\/exams\/alliance-amat"},"IT & Software Exams":{"count":"7","name":"IT & Software Exams","subType":"stream","id":"8","type":"allexam","url":"\/it-software\/exams-st-8"},"Hospitality & Travel Exams":{"count":"6","name":"Hospitality & Travel Exams","subType":"stream","id":"4","type":"allexam","url":"\/hospitality-travel\/exams-st-4"},"Medicine & Health Sciences Exams":{"count":"6","name":"Medicine & Health Sciences Exams","subType":"stream","id":"18","type":"allexam","url":"\/medicine-health-sciences\/exams-st-18"},"Mass Communication & Media Exams":{"count":"5","name":"Mass Communication & Media Exams","subType":"stream","id":"7","type":"allexam","url":"\/mass-communication-media\/exams-st-7"},"Architecture & Planning Exams":{"count":"3","name":"Architecture & Planning Exams","subType":"stream","id":"12","type":"allexam","url":"\/architecture-planning\/exams-st-12"},"Accounting & Commerce Exams":{"count":"3","name":"Accounting & Commerce Exams","subType":"stream","id":"13","type":"allexam","url":"\/accounting-commerce\/exams-st-13"}}},"solr_instt_type":null,"solr_is_institute_multiple":null,"solr_urls":["http:\/\/localhost:8983\/solr\/collection1\/select?wt=phps&defType=edismax&q=\"a\"&fq=facetype:autosuggestor&fq=nl_entity_type:exam&qf=nl_entity_name_edgeNGram+nl_entity_name_keywordEdgeNGram+nl_entity_name_en_keywordEdgeNGram+nl_entity_name_autosuggest+nl_entity_synonyms_autosuggest+nl_entity_synonyms_keywordEdgeNGram+nl_entity_synonyms_spkeywordEdgeNGram+&fl=en:nl_entity_count_name_id_type_map,url:nl_entity_url&sort=score%20DESC&rows=8","http:\/\/localhost:8983\/solr\/collection1\/select?wt=phps&defType=edismax&q=\"a\"&fq=facetype:autosuggestor&fq=nl_entity_type:allexam&fq=nl_entity_result_count:[1%20TO%20*]&qf=nl_entity_name_edgeNGram+nl_entity_name_keywordEdgeNGram+nl_entity_name_en_keywordEdgeNGram+nl_entity_name_autosuggest+nl_entity_synonyms_autosuggest+nl_entity_synonyms_keywordEdgeNGram+nl_entity_synonyms_spkeywordEdgeNGram+&fl=en:nl_entity_count_name_id_type_map,url:nl_entity_url&sort=nl_entity_result_count%20desc&rows=8"],"solr_facet_to_heading_mapping":{"trending_searches":"Trending Searches"}}';
					echo json_encode($solrResult);
					return;
				}
				$solrResult = $this->autoSuggestorSolrClient->getExamAndAllExamSuggestionsFromSolr($data);
				$returnData["solr_facet_to_heading_mapping"]['trending_searches'] = 'Trending Searches';
				break;

			case 'question':
				$solrResult = $this->autoSuggestorSolrClient->getQuestionAndTopicSuggestionsFromSolr($data);
				$returnData["solr_facet_to_heading_mapping"]['question_title_facet'] = 'Past questions with answers';
				$returnData["solr_facet_to_heading_mapping"]['question_topic_facet'] = 'Topics with past questions';
				break;

			default:
				break;
		}
		
		$returnData["solr_results"] = $solrResult['data'];
		$returnData["solr_instt_type"] = $solrResult['institute_type_map'];
		$returnData["solr_is_institute_multiple"] = $solrResult['is_institute_multiple'];
		$returnData["solr_urls"] = $solrResult['solr_urls'];
		
		$jsonEncodedData = json_encode($returnData);
		echo $jsonEncodedData;
	}

	function getSuggestionsFromDbForCMS(){
		$data['text'] = (urldecode($this->input->post('text', true)) != false) ? urldecode($this->input->post('text', true)) : '';
		$data['eachfacetResultCount'] = (urldecode($this->input->post('eachfacetResultCount', true)) != false) ? urldecode($this->input->post('eachfacetResultCount', true)) : 10;
		$data['suggestionType'] = (urldecode($this->input->post('suggestionType', true)) != false) ? urldecode($this->input->post('suggestionType', true)) : '';
		$data['limit'] = ($this->input->post('eachfacetResultCount', true) != false) ? $this->input->post('eachfacetResultCount', true) : 5;
		$solrResults = '';
		$keyword = $data['text'];
		$this->load->library('listingCommon/ListingAutosuggestorLib');
		$ListingAutosuggestorLib = new ListingAutosuggestorLib();
		$res = $ListingAutosuggestorLib->getSuggestions($keyword, $data['limit'], $data['suggestionType']);
		foreach ($res as $key => $value) {
			$groupedSuggestions[$key] = array_flip($value);
		}

		switch($data['suggestionType']) {
			case 'university':
				$result['solr_results']['university_title_facet'] = $groupedSuggestions['university'];
				$result['solr_facet_to_heading_mapping'] = array("university_title_facet"=>"universities","group_title_facet"=>"groups");
				break;
			case 'institute':
			default:
				$result['solr_results']['college_title_facet'] = $groupedSuggestions['institute'];
				$result['solr_results']['university_title_facet'] = $groupedSuggestions['university'];
				$result['solr_facet_to_heading_mapping'] = array("college_title_facet"=>"Colleges","university_title_facet"=>"universities","group_title_facet"=>"groups");
				break;
		}
		/*$result['solr_results']['college_title_facet'] = $groupedSuggestions['institute'];
		$result['solr_results']['university_title_facet'] = $groupedSuggestions['university'];
		$result['solr_facet_to_heading_mapping'] = array("college_title_facet"=>"Colleges","university_title_facet"=>"universities","group_title_facet"=>"groups");*/
		$jsonEncodedData = json_encode($result);
		echo $jsonEncodedData;
	}

	public function processSearchedEntityAndInstitute() {
		$data['selectedWordType'] = $this->input->post('filters_achieved', true);
		$data['selectedWordId'] = $this->input->post('words_achieved_id', true);
		$data['selectedWordName'] = $this->input->post('words_achieved', true);
		$data['locationList'] = $this->input->post('locationList', true);
		$data['getLocations'] = $this->input->post('getLocations', true);
		$data['type'] = $this->input->post('type', true);
		$data['isInstituteMultiple'] = $this->input->post('is_institute_multiple', true);
		
		// $data['selectedWordType'] = 'institute_title_facet';
		// $data['selectedWordId'] = 41334; //35861 38354 26990 4268 4211 46364
		// $data['selectedWordId'] = 'l-321 581 594 611 628 666 1459';
		// $data['selectedWordType'] = 'course_ldb_course_name_facet';
		//$data['selectedWordId'] = 's-23'; //subcat id
		//$data['locationList'] = array('city_10223', 'city_278', 'state_106', 'state_110', 'city_1');
		//$data['locationList'] = array('city_278');
		//$data['locationList'] = array();
		//$data['getLocations'] = 1;
		
		if(empty($data['selectedWordId']) && $data['getLocations'] == 1) { //when keyword is random, populate location with all locations
			$data['selectedWordType'] = 'allLocations';
		}

		if(empty($data['selectedWordId']) && $data['selectedWordType'] != 'allLocations') {
			echo json_encode(array('msg'=>'No result'));
			error_log('check if here....no result. Returning.'); //show error
			return;
		}

		$originalWordId = $data['selectedWordId'];
		switch ($data['selectedWordType']) {
			case 'institute_title_facet':
				//location filters
				$locationFilterList = $this->getSelectedLocations($data);
				$data['filters']['institute'][] = $data['selectedWordId'];
				if($locationFilterList['allIndia'] != 1) {
		            $data['filters']['city'] = $locationFilterList['city'];
		            $data['filters']['state'] = $locationFilterList['state'];
		        }
		        if($data['isInstituteMultiple']) { //no need of advanced filters in this case
		        	$solrFilterResults = $this->autoSuggestorSolrClient->getLocationOnMultipleInsttSelection($data);
		        } else {
					$solrFilterResults = $this->autoSuggestorSolrClient->getAdvancedFilterOnInsttSelection($data);
				}
				
				if(!$solrFilterResults['isMultilocation']) { //condition for single location institute
					foreach($solrFilterResults['city'] as $key => $city) {
						$solrFilterResults['city'][$key]['name'] = trim(preg_replace('/other/i', '', $city['name']));
					}
				}
				break;
			
			case 'course_ldb_course_name_facet':
				//location filters
				$locationFilterList = $this->getSelectedLocations($data);
		        if($locationFilterList['allIndia'] != 1) {
		            $data['filters']['city'] = $locationFilterList['city'];
		            $data['filters']['state'] = $locationFilterList['state'];
		        }
		        
				//set entity filter and facet criteria based on entity type
				$selectedWordArr = explode('_', $data['selectedWordId']);
				$entityType 		= $selectedWordArr[0];
				$entityId			= $selectedWordArr[1];
		        $selectedField 		= array_search($entityType, $this->FIELD_ALIAS);

				$data['requestType'] 			= 'advanced_filters';
		        $data['facetCriterion']['type'] = $selectedField;
		        $data['facetCriterion']['id'] 	= $entityId;

        		$data['filters'][$selectedField][] = $entityId;

				$solrFilterResults = $this->autoSuggestorSolrClient->getAdvancedFilterOnEntitySelection($data);
				
				foreach($solrFilterResults['city'] as $key => $city) {
					if(preg_match('/other/i', $city['name'])) {
						unset($solrFilterResults['city'][$key]);
					}
				}
				break;

			case 'allLocations':
				$solrFilterResults = $this->autoSuggestorSolrClient->getAllLocations($data);

				foreach($solrFilterResults['city'] as $key => $city) {
					if(preg_match('/other/i', strtolower($city['name']))) {
						unset($solrFilterResults['city'][$key]);
					}
				}
				break;
		}
		$solrFilterResults['selectedWordId'] 	= htmlentities($originalWordId);
		$solrFilterResults['selectedWordType'] 	= $data['selectedWordType'];
		//$solrFilterResults['courseTypeSelected'] = $data['courseTypeSelected'];
		
		echo json_encode($solrFilterResults);
	}

	private function getSelectedLocations($data) {
		$allIndia = 0;
		if(empty($data['locationList'])) {
			$data['applyLocationFilter'] = 0;
			$cityIds = array();
			$stateIds = array();
			$allIndia = 1;
		} else {
			$data['applyLocationFilter'] = 1;
			foreach ($data['locationList'] as $key => $value) {
				$location = explode("_",$value);
				if($location[0] == 'city') {
					if($location[1] == 1) {
						$allIndia = 1;
						$data['applyLocationFilter'] = 0;
						break;
					} else {
						$cityIds[] = $location[1];
					}
				} elseif($location[0] == 'state') {
					$stateIds[] = $location[1];
				}
			}
		}
		return array('city'=>$cityIds, 'state'=>$stateIds, 'allIndia'=>$allIndia);
	}
}
