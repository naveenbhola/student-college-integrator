<?php

include_once '../WidgetsAggregatorInterface.php';

class NewsletterArticlesWidget implements WidgetsAggregatorInterface{

	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->CI = & get_instance();
	}
	
	public function getWidgetData() {

		$params = $this->_params['customParams'];

		$templateId = $params['templateId'];
		$numberOfArticlesRequired = $params['numberOfArticlesRequired'];

		$this->CI->load->model('mailer/mailermodel');
		$commonFormLib = $this->CI->load->library('common/commonFormLib');
		$this->CI->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new ListingBaseBuilder();
		$hierarchyRepo = $listingBase->getHierarchyRepository();

		if(!trim($params['articleIds'])) {
			return array();
		}

		$articleIdsArray = explode(',',$params['articleIds']);

		if(empty($articleIdsArray) || $articleIdsArray[0] == '') {
			return arrary();
		}

		$articleIds = array();
		$articleCount = count($articleIdsArray);
		for($i = 0; $i < $articleCount; $i++) {
			$articleIds[] = $articleIdsArray[$i];
			if($i == $numberOfArticlesRequired-1) { break; }
		}

		$this->CI->load->model('article/articlemodel');
		$articleData = $this->CI->articlemodel->getArticlesData($articleIds,FALSE);
		
		$articles = array();
		foreach($articleData as $article) {
			$articleKey = $article['blogId'];
			$articles[$articleKey] = $article;
			$articles[$articleKey]['blogImageURL'] = str_replace('_s','', $article['blogImageURL']);

			if($article['blogId'] == $articleIdsArray[0]) {
				
				$articleHierarchyData = $this->CI->articlemodel->getArticleHierarchyData($article['blogId'],'live');
				
				if(!empty($articleHierarchyData)){
					$formattedArticleHierarchyData = $commonFormLib->getFormattedArray($articleHierarchyData);
				}
				if(!empty($formattedArticleHierarchyData['primaryHierarchyId'])){
					$baseEntities = $hierarchyRepo->getBaseEntitiesByHierarchyId($formattedArticleHierarchyData['primaryHierarchyId'],1,'array');
					$streamId = $baseEntities[$formattedArticleHierarchyData['primaryHierarchyId']]['stream']['id'];
					$streamName = $baseEntities[$formattedArticleHierarchyData['primaryHierarchyId']]['stream']['name'];
				}
			}

		}
		unset($articleIdsArray);
		unset($articleKeyMapping);
		unset($articleHierarchyData);
		unset($formattedArticleHierarchyData);
		unset($baseEntities);
		unset($newsletterParams);

		$newsArticlesPageUrl = Modules::run('blogs/shikshaBlog/getStreamSpecificNewsArticlesPage', $streamId, $streamName);
		
		if($newsArticlesPageUrl == ''){
			$newsArticlesPageUrl = SHIKSHA_HOME;
		}

		$data = array();
		$data['articles'] = $articles;	
		$data['articleIds'] = $articleIds;	
		$data['newsArticlesPageUrl'] = $newsArticlesPageUrl;
		$data['utm_source'] = $params['mailerDetails']['utm_source'];
		$data['utm_medium'] = $params['mailerDetails']['utm_medium'];
		$data['utm_campaign'] = $params['mailerDetails']['utm_campaign'];		
		
		$widgetHTML = $this->CI->load->view("personalizedMailer/widgets/NewsletterArticlesWidget", $data, true);
		
		return array('key'=>'NewsletterArticlesWidget','data'=>$widgetHTML);
	}
}
