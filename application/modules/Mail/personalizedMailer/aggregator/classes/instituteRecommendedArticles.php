<?php

include_once '../WidgetsAggregatorInterface.php';

class instituteRecommendedArticles implements WidgetsAggregatorInterface{

	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->_CI = & get_instance();
	}
	
	/**
	* function to get data for widgets of college reviews
	*/
	public function getWidgetData() {
		
		$customParams = $this->_params['customParams'];
		$widgetHTML = "";

		if($customParams['listing_type_id'] > 0 && $customParams['instituteId']) {
			$instituteId = $customParams['instituteId'];
			$this->_CI->load->builder("nationalInstitute/InstituteBuilder");
	    	$instituteBuilder = new InstituteBuilder();
			$instituteRepo = $instituteBuilder->getInstituteRepository();
		    $instituteObj = $instituteRepo->find($instituteId);
		    $instituteType = $instituteObj->getListingType();

			$this->_CI->load->library('ContentRecommendation/ArticleRecommendationLib');
			if($instituteType == 'institute'){
			    $articleArray = $this->_CI->articlerecommendationlib->forInstitute($instituteId,array(),3);
			}else if($instituteType == 'university'){
			    $articleArray = $this->_CI->articlerecommendationlib->forUniversity($instituteId,array(),3);
			}
			$finalArray = $articleArray['topContent'];

			if(!empty($finalArray)){
			    $totalArticles = $articleArray['numFound'];
			    $allArticleURL = $instituteObj->getAllContentPageUrl('articles');
			    
			    $this->_CI->load->builder('ArticleBuilder','article');
			    $this->_CI->articleBuilder = new ArticleBuilder;
			    $this->_CI->articleRepository = $this->_CI->articleBuilder->getArticleRepository();
			    $articleObjectArray = $this->_CI->articleRepository->findMultiple($finalArray);
			    $articleData = array();
		        foreach($finalArray as $articleId){
		        	$articleObject = $articleObjectArray[$articleId];
				    if(!empty($articleObject)){
							$id = $articleObject->getId();
							$url = addingDomainNameToUrl(array('domainName'=>SHIKSHA_HOME,'url'=>$articleObject->getUrl()));
							$articleData[$id] = array('url'=>$url, 'blogTitle'=>$articleObject->getTitle() );
					}
				}
		        
		        $customParams['instituteRecommendedArticles'] = array('articleCount'=>$totalArticles, 'articlesDetail'=>$articleData, 'allArticleURL'=>$allArticleURL);
		 		$widgetHTML = $this->_CI->load->view("personalizedMailer/widgets/instituteRecommendedArticles", $customParams, true);
			}
		} 
		return array('key'=>'instituteRecommendedArticles','data'=>$widgetHTML);
	}

}