<?php

class ArticleFinder {
	
	private $_ci;
	
	public function __construct(){
		$this->_ci = & get_instance();
		$this->_ci->load->model("search/SearchModel", "", true);
		$this->_ci->load->builder("ListingBuilder", "listing");
		$this->listingBuilder = new ListingBuilder();
	}
	
	public function getData($id = null) {
		if($id == null){
			return array();
		}
		$indexData = false;
		$articleData = $this->getArticleData($id);
		if(!empty($articleData) && is_array($articleData)){
			$indexData = $this->preprocessRawData($articleData);
			$dataSufficientFlag = $this->isDataSufficient($indexData);
			if(!$dataSufficientFlag){
				$indexData = false;
			}
		}
		return $indexData;
	}
	
	public function preprocessRawData($data = array()) {
		if(empty($data) || !is_array($data)){
			return false;
		}
		$article['article_id'] 			= $data['blogId'];
		$article['article_title'] 		= $data['blogTitle'];
		$articleBody = "";
		foreach($data['description'] as $description){
			$articleBody .= $description . "  ";
		}
		$article['article_body'] 			= $articleBody;
		$article['article_summary'] 		= $data['summary'];
		$article['article_user_id'] 		= $data['userId'];
		$article['article_user_displayname']= $data['displayname'];
		$article['article_user_image_url'] 	= $data['image_url'];
		$article['article_image_url'] 		= $data['blogImageURL'];
		$article['article_country_id'] 		= $data['countryId'];
		$article['article_country_name'] 	= $data['country_name'];
		$article['article_url'] 			= $data['url'];
		$article['article_created_time'] 	= solrDateFormater($data['lastModifiedDate']);
		$article['article_stream_id'] 		= $data['hierarchyInfo']['stream_id'];
		if(empty($data['hierarchyInfo']['stream_id'])){
			$logFile = "/tmp/articleCronLog".date('Y-m-d');
			error_log($data['blogId']."\n", 3, $logFile);
		}
		$article['article_substream_id'] 	= $data['hierarchyInfo']['substream_id'];
		$article['article_spec_id'] 		= $data['hierarchyInfo']['spec_id'];
		$article['facetype'] 				= 'article';
		$article['unique_id'] 				= 'article_'.$article['article_id'];
		return $article;
	}
	
	private function getArticleData($id = null){
		$data = array();
		if($id == null || empty($id)){
			return false;
		}
		$articleLib = $this->_ci->load->library('article/ArticleUtilityLib');
		$searchModel = new SearchModel();
		$tempArticleData = $searchModel->getArticleDetails($id);
		$articleData = array();
		$articleDescription = array();
		foreach($tempArticleData as $key => $data){
			if($key == 0){
				$articleData = $data;
				unset($articleData['description']);
			}
			$articleDescription[] = strip_tags($data['description']);
		}
		$articleData['description'] = $articleDescription;
		$categoryInfo = array();
		if(empty($articleData) || !is_array($articleData)){
			return false;
		}
	//	$categoryInfo = array('category_ids' => array(), 'category_names' => array(), 'category' => array());
		$hierarchyInfo = array('stream_id' => array(), 'substream_id' => array(),'spec_id' => array());
  		$hierarchyInfo = $articleLib->getArticleMappingForRecommendedArticles(array($id));
		$articleData['hierarchyInfo'] = $hierarchyInfo;
		return $articleData;
	}
	
	private function isDataSufficient($data = array()) {
		$returnFlag = false;
		if(!empty($data) && is_array($data)){
			if(!empty($data['article_id'])){
				$returnFlag = true;
			}	
		}
		return $returnFlag;
	}
}
