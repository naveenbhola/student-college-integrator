<?php

class ProfileGenerator
{
    private $CI;
    private $baseCourseRepository;
    private $searchServer;

    function __construct()
    {
        $this->CI = & get_instance();

        $this->CI->load->builder('ListingBaseBuilder','listingBase');
		$listingBaseBuilder = new ListingBaseBuilder();
		$this->baseCourseRepository = $listingBaseBuilder->getBaseCourseRepository();

		$this->CI->load->builder('SearchBuilder','search');
        $this->searchServer = SearchBuilder::getSearchServer();
    }

    private function searchProfiles($userId)
    {
        $solrURL = SOLR_LDB_SEARCH_SELECT_URL_BASE;
	$request = "q=*%3A*&wt=phps&fq=userId:$userId&fq=ProfileType:explicit";
        error_log("ProfileBased:: Search profiles solr url: ".$solrURL."?".$request);
	$response = $this->searchServer->leadSearchCurl($solrURL, $request);
        $response = unserialize($response);
        return $response;
    }

    public function generateProfiles($userId)
    {
        $profiles = array();

		$profileSearchResponse = $this->searchProfiles($userId);
		foreach($profileSearchResponse['response']['docs'] as $doc) {
			$profile = array();
			$profile['stream'] = $doc['streamId'];
			$profile['substreams'] = $doc['subStreamId'];
			$profile['specializations'] = $doc['specialization'];
			
			/**
			 * Courses
			 */ 
			$profile['courses'] = $doc['courseId'];

			/**
			 * Fetch level and credential for each of the courses
			 */ 
			if(is_array($profile['courses']) && count($profile['courses']) > 0) {
				$baseCourses = $this->baseCourseRepository->findMultiple($profile['courses']);
				foreach($baseCourses as $baseCourse) {
					$credential = $baseCourse->getCredential();
					$profile['levelAndCredentials'][] = array(
						'level' => intval($baseCourse->getLevel()),
						'credential' => intval($credential[0])
					);
				}
			}

			/**
			 * Residence city of user
			 */ 
			$profile['city'] = $doc['city'];
			
			/**
			 * Education type
			 * Full Time => Attribute value 20,
			 * Part Time => Attribute value 21
			 */ 
			$attributeValues = $doc['attributeValues'];
			foreach($attributeValues as $attributeValue) {
				if($attributeValue == 20 || $attributeValue == 21) {
					$profile['educationType'][] = $attributeValue;
				}
			}

			$profiles[] = $profile;
			//break;
 		}
                error_log("ProfileBased:: User profiles: ".json_encode($profiles));
		return $profiles;
    }
}
