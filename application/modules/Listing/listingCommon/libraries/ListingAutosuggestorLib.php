<?php
class ListingAutosuggestorLib {
	private $CI;

	function init() {
		$this->CI =& get_instance();
	}

	/**
	 * [getSuggestions this function institute names and their ids based on input values]
	 * @author Ankit Garg <g.ankit@shiksha.com>
	 * @date   2016-07-12
	 * @param  [string]     $keyword        [text to be searched for]
	 * @param  integer    $limit          [limit to retrieve results]
	 * @param  string     $suggestionType [could be all|institute|university]
	 * @return [array]                     [description]
	 */
	function getSuggestions($keyword, $limit = 10, $suggestionType = 'all',$statusCheck=false) {
		$this->init();
		$this->model = $this->CI->load->model('listingCommon/autosuggestormodel');
		$suggestions = $this->model->getSuggestions($keyword, $limit, $suggestionType,$statusCheck);
		
		$groupedSuggestions = array();
		foreach($suggestions as $suggestion) {
			$groupedSuggestions[$suggestion['type']][$suggestion['id']] = $suggestion['name'];
		}
		return $groupedSuggestions;
	}
}