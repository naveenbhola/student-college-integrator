<?php

class TagFinder {
	
	private $_ci;
	
	public function __construct(){
		$this->_ci = & get_instance();
		$this->_ci->load->model("search/SearchModel", "", true);
		$this->_ci->load->helper("search/SearchUtility");
		$this->_ci->config->load('search_config');
		$this->_ci->load->library('search/Common/SearchCommon');
		$this->config = $this->_ci->config;
	}
	
	public function getData($id = null) {
		if($id == null){
			return array();
		}
		$indexData = false;

		$tagData = $this->getTagsData($id, $synonymMapping);
		
		if(!empty($tagData) && is_array($tagData)){
			$indexData = array();
			$indexData = $this->preprocessRawData($tagData);
			$dataSufficientFlag = $this->isDataSufficient($indexData);
			if(!$dataSufficientFlag){
				$indexData = false;
			}
		}
		return $indexData;
	}
	
	public function preprocessRawData($data = array()) {
		$tag = array();
		$tagData = $data['tagdata'];
		if(is_array($tagData) && !empty($tagData)){
			$tag['tag_id']             = $tagData['id'];
			$tag['tag_name']           = $tagData['tags'];
			$tag['tag_entity']         = $tagData['tag_entity'];
			$tag['tag_description']    = $tagData['description'];
			$tag['tag_quality_factor'] = $tagData['qualityFactor'];
			$tag['tag_synonyms']       = $tagData['synonyms'];
		}

		$tag['unique_id'] = "tag_". $tagData['id'];
		$tag['facetype'] = "tag";
		return $tag;
	}
	
	private function getTagsData($id = null){
		if($id == null){
			return false;
		}
		
		$searchModel = new SearchModel();
		$searchCommon = new SearchCommon();
		global $synonymMapping;

		if(!$synonymMapping)
			$synonymMapping = $searchModel->getTagsSynonymsMapping();

		$discussionData = $searchModel->getTagsData($id);

		$tagQualityFactor = $searchCommon->getTagQualityFactor($id);

		// update the tag_quality_score in DB
		$searchModel->updateTagQualityScore($id, $tagQualityFactor);
		$discussionData['tagdata']['qualityFactor'] = $tagQualityFactor;
		return $discussionData;
	}
	
	private function isDataSufficient($data = array()) {
		$returnFlag = false;
		if(!empty($data) && is_array($data)){
			if(!empty($data['tag_id'])){
				$returnFlag = true;
			}
		}
		return $returnFlag;
	}
	
	
}

