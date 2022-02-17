<?php
/**
 * AutoSuggestor Library Class.
 * Class for handling all autosuggestor functionality
 * @date    2015-08-19
 * @author  Romil Goel
 * @todo    none
*/
class AutosuggestorLib {
	
	private $_ci;
	
	public function __construct(){
		
		// constants
		define("AUTOSUGGEST_ROW_LIMIT", 10);
		define("AUTOSUGGEST_BOOST_PARAM_EXACTMATCH", 70);
		define("AUTOSUGGEST_BOOST_PARAM_STARTINGMATCH", 50);
		define("AUTOSUGGEST_BOOST_PARAM_TOKENMATCH", 70);
		define("AUTOSUGGEST_BOOST_PARAM_SYNONYM_EXACTMATCH", 90);
		define("AUTOSUGGEST_BOOST_PARAM_SYNONYM_STARTINGMATCH", 10);
		define("AUTOSUGGEST_BOOST_PARAM_SYNONYM_TOKENMATCH", 5);

		$this->_ci = & get_instance();
		$this->_ci->load->builder('SearchBuilder', 'search');
		$this->_ci->load->model('search/SearchModel', '', true);
		$this->_ci->config->load('search_config');
		
		$this->config = $this->_ci->config;
		$this->searchServer = SearchBuilder::getSearchServer($this->config->item('search_server'));

		
	}

	/**
	 * Method to get the autosuggestions based on given text, type and some other search params
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2015-08-19
	 * @param  [string]   $type         [type of the results needed for autosuggestor ]
	 * @param  [string]   $text         [user typed phrase]
	 * @param  array      $optionParams [other search params]
	 * @return [type]                   [autosuggestor results in json format]
	 */
	function getAutoSuggestions($type, $text, $optionParams = array(), $entity){

		switch($type){
			case 'tag':
				$response['tag'] = $this->getTagsAutosuggestions($text, $optionParams, $entity);
				break;
			case 'user':
				$response['user'] = $this->getUserAutosuggestions($text, $optionParams);
				break;
			case 'tag_user':
				$response['tag']  = $this->getTagsAutosuggestions($text, $optionParams, $entity);
				$response['user'] = $this->getUserAutosuggestions($text, $optionParams);
				break;
			default :
				$response['tag'] = $this->getTagsAutosuggestions($text, $optionParams, $entity);
				break;
		}
		
		return $response;
	}

	/**
	 * Method to get Tag Autosuggestions
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2015-08-19
	 * @param  [string]   $type         [type of the results needed for autosuggestor ]
	 * @param  [string]   $text         [user typed phrase]
	 * @param  array      $optionParams [other search params]
	 * @return [type]                   [autosuggestor results in array]
	 */
	function getTagsAutosuggestions($text, $optionParams, $type = ""){

		// prepare solr query
		$solrUrl = $this->prepareTagsAutosuggestorSolrQuery($text, $optionParams, $type);

		// fetch the results
		$solrContent = unserialize($this->searchServer->curl($solrUrl));

		// parse and format the response
		$response = $this->formatAutosuggestorResponse($solrContent, "tag_name", "tag_id");

		return $response;
	}

	/**
	 * Method to get User Autosuggestions
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2015-08-19
	 * @param  [string]   $type         [type of the results needed for autosuggestor ]
	 * @param  [string]   $text         [user typed phrase]
	 * @param  array      $optionParams [other search params]
	 * @return [type]                   [autosuggestor results in array]
	 */
	function getUserAutosuggestions($text, $optionParams){

		$solrUrl = $this->prepareUserAutosuggestorSolrQuery($text, $optionParams);

		$solrContent = unserialize($this->searchServer->curl($solrUrl));

		$response = $this->formatAutosuggestorResponseForUser($solrContent, "user_name_facet", "userId");

		return $response;
	}

	/**
	 * Method to prepare the solr query for tag autosuggestor
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2015-08-19
	 * @param  [string]   $type         [type of the results needed for autosuggestor ]
	 * @param  [string]   $text         [user typed phrase]
	 * @param  array      $optionParams [other search params]
	 * @return [string]                 [solr url]
	 */
	private function prepareTagsAutosuggestorSolrQuery($text, $optionParams, $type){

		$params   = array();
		
		// tag types to be given boost
		$parentTagTypes = array("Test preparation","Career General","Study Abroad","Exam General","Stream","Specialization","Course","Mode","Course Level","Course Modes","Colleges In location","College Common","Careers","Country","State","City","Generic","Comparison","Colleges","Exams","Course specializations","Demo");

		// prepare the qf params
		$qf = "tag_name^".AUTOSUGGEST_BOOST_PARAM_EXACTMATCH.
			  "+tag_name_text^20".
			  "+tag_name_without_special_chars^20".
			  "+tag_name_keywordEdgeNGram^".AUTOSUGGEST_BOOST_PARAM_STARTINGMATCH.
			  "+tag_name_edgeNGram^".AUTOSUGGEST_BOOST_PARAM_TOKENMATCH.
			  "+tag_synonyms^".AUTOSUGGEST_BOOST_PARAM_SYNONYM_EXACTMATCH.
			  "+tag_synonyms_keywordEdgeNGram^".AUTOSUGGEST_BOOST_PARAM_SYNONYM_STARTINGMATCH.
			  "+tag_synonyms_edgeNGram^".AUTOSUGGEST_BOOST_PARAM_SYNONYM_TOKENMATCH;

		// add query text
		$queryString = implode("' AND '",array_filter(explode(" ",$text)));
		$queryString = "'".$queryString."'";
		$params[] = 'q='.urlencode($queryString).'+OR+"'.urlencode($text).'"^20';

		// add query fields
		$params[] = 'qf='.$qf;

		// add field list : data to be fetched
		if($optionParams['fields'] == 'All')
			$params[] = 'fl=*';
		else
			$params[] = 'fl=tag_name,tag_id';

		// add query parser to be used
		$params[] = 'defType=edismax';

		// add query response type : phps will give the output in serialized version of php array
		$params[] = 'wt=phps';

		// add number of rows to be fetched
		$numRows = $optionParams['numRows'] > 0 ? $optionParams['numRows'] : AUTOSUGGEST_ROW_LIMIT;
		$params[] = 'rows='.$numRows;

		// AND all the tokens given in the query i.e don't fetch the results that don't contain all the tokens given in the query
		$params[] = 'q.op=AND';
		$params[] = 'tie=0.2';

		$params[] = 'bf=ord(tag_quality_factor)^2';
		
		// if specified put a filter on tag type
		if($type)
			$params[] = 'fq=tag_entity:"'.urlencode($type).'"';


        if(empty($optionParams['specialUser']) && !empty($optionParams['specialtags'])){
        	$specialtags = $optionParams['specialtags'];
            if(is_array($specialtags)){
            	$tagCheck = implode(" OR ", $specialtags);
            	$params[] = "fq=-tag_id:(".urlencode($tagCheck).")";	
            }
        }      	
        // add boost query
		foreach ($parentTagTypes as $value) {
			$params[] = "bq=tag_entity:(".urlencode($value).")";
		}

		if($optionParams['start'])
			$params[] = 'start='.$optionParams['start'];

		// prepare the final solr url
		$solrUrl = SOLR_AUTOSUGGESTOR_URL.implode("&", $params);

		return $solrUrl;
	}

	/**
	 * Method to prepare the solr query for USER autosuggestor
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2015-08-19
	 * @param  [string]   $type         [type of the results needed for autosuggestor ]
	 * @param  [string]   $text         [user typed phrase]
	 * @param  array      $optionParams [other search params]
	 * @return [string]                 [solr url]
	 */
	private function prepareUserAutosuggestorSolrQuery($text, $optionParams){

		$params   = array();

		// add query text
		$params[] = 'q='.urlencode($text);

		// add query fields
		$params[] = 'qf=user_name_edgeNGram';

		// add field list : data to be fetched
		$params[] = 'fl=user_name_facet,userId,user_ana_points,user_image,user_ana_level';

		// add query parser to be used
		$params[] = 'defType=edismax';

		// add query response type : phps will give the output in serialized version of php array
		$params[] = 'wt=phps';

		// add number of rows to be fetched
		$numRows = $optionParams['numRows'] > 0 ? $optionParams['numRows'] : AUTOSUGGEST_ROW_LIMIT;
		$params[] = 'rows='.$numRows;

		// AND all the tokens given in the query i.e don't fetch the results that don't contain all the tokens given in the query
		$params[] = 'q.op=AND';

		// exclude all abused users
		$params[] = 'fq=abused:0';

		// boost some users
		if($optionParams['userFollowList']){
			$params[] = 'bq=userId:('.implode("%20OR%20", $optionParams['userFollowList']).')^50';
		}

		// add the sorting param
		$params[] = 'bf=log(sum(user_ana_points,1))^5';

		// prepare the final solr query
		$solrUrl = SOLR_LDB_SEARCH_SELECT_URL_BASE."?".implode("&", $params);

		return $solrUrl;
	}

	private function prepareTagsSearchSolrQuery($text, $optionParams, $type){

		$params   = array();
		
		// tag types to be given boost

		// prepare the qf params
		// $qf = "tag_name^100+tag_name_text^50+tag_name_spell^20+tag_synonyms^5+tag_name_keywordEdgeNGram+tag_name_edgeNGram+tag_synonyms_keywordEdgeNGram+tag_synonyms_edgeNGram";
		$qf = "tag_name^50".
			  "+tag_name_without_special_chars^20".
			  "+tag_name_text^40".
			  "+tag_name_string^70".
			  "+tag_synonyms^".AUTOSUGGEST_BOOST_PARAM_SYNONYM_EXACTMATCH;
			  // "+tag_name_keywordEdgeNGram^10".
			  //"+tag_name_edgeNGram^".AUTOSUGGEST_BOOST_PARAM_TOKENMATCH.
			  //"+tag_synonyms_keywordEdgeNGram^".AUTOSUGGEST_BOOST_PARAM_SYNONYM_STARTINGMATCH.
			  //"+tag_synonyms_edgeNGram^".AUTOSUGGEST_BOOST_PARAM_SYNONYM_TOKENMATCH;

		// add query text
		// $queryString = implode("'+'",array_filter(explode(" ",$text)));
		$queryString = implode("+",array_filter(explode(" ",$text)));
		// $queryString = "'".$queryString."'";
		$q_param = 'q='.urlencode($queryString);

		if($optionParams['boostingIds'])
			$q_param .= urlencode(" OR tag_id:(".implode(" ", $optionParams['boostingIds']).")");

		$params[] = $q_param;

		if($optionParams['boostingIds'])
			$params[] = 'bq=tag_id:('.urlencode(implode(" ", $optionParams['boostingIds'])).')^20';

		// add query fields
		$params[] = 'qf='.$qf;

		// add field list : data to be fetched
		if($optionParams['fields'] == 'All')
			$params[] = 'fl=*';
		else
			$params[] = 'fl=tag_name,tag_id';

		// add query parser to be used
		$params[] = 'defType=edismax';

		// add query response type : phps will give the output in serialized version of php array
		$params[] = 'wt=phps';

		// add number of rows to be fetched
		$numRows = $optionParams['numRows'] > 0 ? $optionParams['numRows'] : AUTOSUGGEST_ROW_LIMIT;
		$params[] = 'rows='.$numRows;
		$params[] = 'tie=0.2';

		$params[] = 'bf=ord(tag_quality_factor)^5';

		// if specified put a filter on tag type
		if($type)
			$params[] = 'fq=tag_entity:'.$type;

		if($optionParams['start'])
			$params[] = 'start='.$optionParams['start'];

		// prepare the final solr url
		$solrUrl = SOLR_AUTOSUGGESTOR_URL.implode("&", $params);

		return $solrUrl;
	}

	function getTagsSearchResults($text, $optionParams, $type = ""){

		// prepare solr query
		$solrUrl = $this->prepareTagsSearchSolrQuery($text, $optionParams, $type);

		// fetch the results
		$response = unserialize($this->searchServer->curl($solrUrl));

		return $response;
	}

	/**
	 * Method to format the provided solr content in associative array based on given keys
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2015-08-19
	 * @param  [type]     $solrContent [response returned from solr]
	 * @param  [type]     $indexField  [name of the field to be kept in the array key]
	 * @param  [type]     $dataField   [name of the field to be kept in the array value]
	 * @return [type]                  [formatted array]
	 */
	private function formatAutosuggestorResponse($solrContent, $indexField, $dataField){

		$response = array();
		$i = 0;
		if($solrContent['response']['numFound'] > 0){
			foreach($solrContent['response']['docs'] as $doc){
				$response[$i]['tagId'] = $doc[$dataField];
				$response[$i]['tagName'] = html_entity_decode($doc[$indexField]);
				$i++;
				//$response[$doc[$indexField]] = $doc[$dataField];
			}
		}

		return $response;
	}

	/**
	 * Method to format the provided solr content in associative array based on given keys
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2015-08-19
	 * @param  [type]     $solrContent [response returned from solr]
	 * @param  [type]     $indexField  [name of the field to be kept in the array key]
	 * @param  [type]     $dataField   [name of the field to be kept in the array value]
	 * @return [type]                  [formatted array]
	 */
	private function formatAutosuggestorResponseForUser($solrContent, $indexField, $dataField){

		$this->_ci->load->helper('messageBoard/abuse');
		$this->_ci->load->helper('image');
		
		$response = array();
		$i = 0;
		if($solrContent['response']['numFound'] > 0){
			foreach($solrContent['response']['docs'] as $doc){
				$response[$i]['userId'] = $doc[$dataField];
				$response[$i]['userName'] = html_entity_decode($doc[$indexField]);

				if(strpos($doc['user_image'], "fbcdn") !== false)
					$response[$i]['pic'] = $doc['user_image'] ? $doc['user_image'] : "";
				else{
					$response[$i]['pic'] = $doc['user_image'] ? getSmallImage($doc['user_image']) : "";
					$response[$i]['pic'] = $response[$i]['pic'] ? checkUserProfileImage($response[$i]['pic']) : "";
				}

				$response[$i]['level'] = getLevelNameFromLevelId($doc['user_ana_level']);
				$i++;
			}
		}

		return $response;
	}
	
}
