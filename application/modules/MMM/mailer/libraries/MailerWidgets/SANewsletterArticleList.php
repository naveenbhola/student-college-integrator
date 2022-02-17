<?php
include_once('MailerWidgetAbstract.php');

class SANewsletterArticleList extends MailerWidgetAbstract
{
	function __construct(MailerModel $mailerModel)
	{
		parent::__construct($mailerModel);
	}
	
	public function getData($userIds, $params = "")
	{
		$templateId = $params['templateId'];
		
		$newsletterParams = $this->mailerModel->getNewsletterParams($templateId);
		if(!trim($newsletterParams['articleIds'])) {
			return array();
		}
		$articleIds = explode(',',$newsletterParams['articleIds']);

		//$articleIds = array_slice($articleIds, 0, 3);
		
		$featuredArticleIds[] = $articleIds[0];

		for($i=1; $i<3; $i++){
			$regularArticleIds[] = $articleIds[$i];
		}
		
		$this->CI->load->model('blogs/sacontentmodel');
		$articleData = $this->CI->sacontentmodel->getMultipleContentDetailsAndCountryForMailer($articleIds,FALSE);
			
		$articles = array();
		foreach($articleIds as $articleId) {
			foreach($articleData as $article) {
				if($article['content_id'] == $articleId) {
					$articles[] = $article;
					break;
				}
			}
		}
				
		$data = array();
		$new_article_list = array();

		$this->CI->load->library("categoryList/AbroadCategoryPageRequest");
    	$requestObject = new AbroadCategoryPageRequest();
    	$this->CI->load->builder('location/LocationBuilder');
        $locationBuilder = new LocationBuilder();
        $this->locationRepository = $locationBuilder->getLocationRepository();

		foreach($articles as $article_data) {
			if(!empty($article_data['contentImageURL'])) {
				$blog_img_url = $article_data['contentImageURL'];
				$blog_img_url = str_replace("https://","",$blog_img_url);
				$url_array = explode("/",$blog_img_url);                
				$image_file = $url_array[count($url_array)-1]; 
				$image_file_array = explode(".",$image_file);
				$image_name = $image_file_array[0];
				$original_name = $image_file_array[0];			
				$image_name = str_replace("_s","",$image_name);
				$image_name = str_replace($original_name,$image_name,$article_data['contentImageURL']);
				$article_data['blogImageURL'] = $image_name;
			}
			$article_data['url'] = $article_data['contentURL'];
			$article_data['blogTitle'] = $article_data['strip_title'];
			$article_data['mainCountryId'] = $article_data['country_id'][0];
			if($article_data['content_id'] == $featuredArticleIds[0]){
				$linksCountryId = $article_data['country_id'][0];
			} 
			
			$new_article_list[] = $article_data;
		}
		
		if(empty($linksCountryId)) {
			$linksCountryId = 3;
		}

		$requestObject->setData(array('countryId'=>array($linksCountryId)));
		$linkURL['CountryPage'] = $requestObject->getURLForCountryPage($linksCountryId);
		$requestObject->setData(array('countryId'=>array($linksCountryId),'LDBCourseId'=>'1508'));
    	$linkURL['MBA'] = $requestObject->getURL();
    	$requestObject->setData(array('countryId'=>array($linksCountryId),'LDBCourseId'=>'1509'));
    	$linkURL['MS'] = $requestObject->getURL();
    	$requestObject->setData(array('countryId'=>array($linksCountryId),'LDBCourseId'=>'1510'));
    	$linkURL['BTech'] = $requestObject->getURL();

     	$countryData = $this->locationRepository->getAbroadCountryByIds(array($linksCountryId));
		$countryName =  $countryData[$linksCountryId]->getName();
		
    	$articles = $new_article_list;
		$data['articles'] = $articles;
		$data['links'] = $linkURL;
		$data['countryName'] = $countryName;
 		$data['featuredArticleIdsArray'] = $featuredArticleIds;
 		$data['regularArticleIdsArray'] = $regularArticleIds;
 		
		$widgetHTML = $this->CI->load->view('mailer/MailerWidgets/SANewsletterArticleList',$data,TRUE);
		
		$widgetData = array();
		foreach($userIds as $userId) {
			$widgetData[$userId]['SANewsletterArticleList'] = $widgetHTML;
		}
		return $widgetData;
	}
}
