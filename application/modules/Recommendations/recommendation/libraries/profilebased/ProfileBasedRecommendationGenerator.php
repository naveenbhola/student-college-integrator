<?php

class ProfileBasedRecommendationGenerator
{
    private $CI;
    private $searchServer;

    function __construct()
    {
        $this->CI = & get_instance();

		$this->CI->load->builder('SearchBuilder','search');
        $this->searchServer = SearchBuilder::getSearchServer();
    }

    private function searchProfiles($userId)
    {
        $solrURL = SOLR_LDB_SEARCH_SELECT_URL_BASE;
		$request = "q=*%3A*&wt=phps&fq=userId:$userId&fq=ProfileType:explicit";

		$response = $this->searchServer->leadSearchCurl($solrURL, $request);
        $response = unserialize($response);
        return $response;
    }

    public function generateRecommendations($profiles, $numResults, $exclusionList)
    {
        $results = array();

		foreach($profiles as $profile) {

			$searchResponse = $this->searchRecommendationsForProfile($profile, $numResults, $exclusionList);

			if($searchResponse['grouped']['nl_institute_id']['matches'] > 0) {
				foreach($searchResponse['grouped']['nl_institute_id']['groups'] as $group) {

					$courseId = $group['doclist']['docs'][0]['nl_course_id'];
					$instituteId = $group['doclist']['docs'][0]['nl_institute_id'];
					$viewCount = $group['doclist']['docs'][0]['nl_course_view_count_year'];

					if(!array_key_exists($instituteId, $results) || $results[$instituteId]['viewCount'] < $viewCount) {
						$results[$instituteId] = array(
							'courseId' => $courseId,
							'instituteId' => $instituteId,
							'viewCount' => $viewCount
						);
					}
				}
			}
		}

		return array_slice($results, 0, 2);
    }

	private function searchRecommendationsForProfile($profile, $numResults, $exclusionList)
	{
		$requestParams = array();

		/**
		 * Stream will always be present is the profile
		 */
		$requestParams[] = "fq=nl_stream_id:".intval($profile['stream']);

		/**
		 * Substreams are optional
		 * If substreams were selected, add the clause
		 */
		if(is_array($profile['substreams']) && count($profile['substreams']) > 0) {
			$substreamParams = array();
			foreach($profile['substreams'] as $substreamId) {
				$substreamParams[] = "(nl_substream_id:".$substreamId.")";
			}
			$requestParams[] = "fq=".implode("%20OR%20", $substreamParams);
		}
		
		/**
		 * Specializations are optional
		 * If specializations were selected, add the clause
		 */
		if(is_array($profile['specializations']) && count($profile['specializations']) > 0) {
			$specializationParams = array();
			foreach($profile['specializations'] as $specializationId) {
				$specializationParams[] = "(nl_specialization_id:".$specializationId.")";
			}
			$requestParams[] = "fq=".implode("%20OR%20", $specializationParams);
		}
		
		/**
		 * Courses are optional
		 * If courses were selected, add the clause
		 */
		if(is_array($profile['courses']) && count($profile['courses']) > 0) {
			$courseParams = array();
			foreach($profile['courses'] as $courseId) {
				$courseParams[] = "(nl_base_course_id:".$courseId.")";
			}
			$requestParams[] = "fq=".implode("%20OR%20", $courseParams);
		}
		else {
			/**
			 * Level and credential based matching
			 */
			if(is_array($profile['levelAndCredentials']) && count($profile['levelAndCredentials']) > 0) {
				$levelAndCredentialParams = array();
				foreach($profile['levelAndCredentials'] as $levelAndCredentials) {
					$levelAndCredentialClause = array();
					if($levelAndCredentials['level']) {
						$levelAndCredentialClause[] = "nl_course_level_id:".$levelAndCredentials['level'];
					}
					if($levelAndCredentials['credential']) {
						$levelAndCredentialClause[] = "nl_course_credential_id:".$levelAndCredentials['credential'];
					}
		
					if(count($levelAndCredentialClause) > 0) {
						$levelAndCredentialParams[] = "(".implode("%20AND%20", $levelAndCredentialClause).")";
					}
				}
				if(count($levelAndCredentialParams) > 0) {
					$requestParams[] = "fq=".implode("%20OR%20", $levelAndCredentialParams);
				}
			}
		}
		
		/**
		 * Education type e.g. Full Time/Part Time
		 */
		if(is_array($profile['educationType']) && count($profile['educationType']) > 0) {
			$educationTypeParams = array();
			foreach($profile['educationType'] as $educationType) {
				$educationTypeParams[] = "(nl_course_education_type_id:".$educationType.")";
			}
			$requestParams[] = "fq=".implode("%20OR%20", $educationTypeParams);
		}
		
		/**
		 * User current (residence) city
		 */
		$requestParams[] = "fq=nl_course_city_id:".intval($profile['city']);

		if(is_array($exclusionList) && count($exclusionList) > 0) {
			$instituteExclusion = array();
			foreach($exclusionList as $ex) {
				//$instituteExclusion[] = "nl_institute_id:".intval($ex);
				$instituteExclusion[] = intval($ex);
			}
			$requestParams[] = "fq=-nl_institute_id:(".implode("%20", $instituteExclusion).")";
			//$requestParams[] = "fq=-(".implode("%20OR%20", $instituteExclusion).")";
		}

		$requestParams[] = "fl=nl_course_id,nl_institute_id,nl_course_view_count_year";
		//$requestParams[] = "rows=".$numResults;
		$requestParams[] = "rows=10";

		$requestParams[] = "group=true";
		$requestParams[] = "group.field=nl_institute_id";
		$requestParams[] = "sort=nl_course_view_count_year%20desc";

		//$request = $this->searchServer->getSolrUrl('course', 'select');
		$request = SOLR_AUTOSUGGESTOR_URL;
		$request .= 'q=*%3A*&wt=phps&';
		$request .= implode('&', $requestParams);
                error_log("ProfileBased:: Search recommendation url: ".$request);
		$response = unserialize($this->searchServer->curl($request));
		return $response;
	}
}
