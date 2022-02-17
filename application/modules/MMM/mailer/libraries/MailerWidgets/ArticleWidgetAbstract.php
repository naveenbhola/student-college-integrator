<?php
include_once('MailerWidgetAbstract.php');

class ArticleWidgetAbstract extends MailerWidgetAbstract
{
	private $desiredCourseAndCategoryPref;
	private $desiredCountryPref;
	private $articleList;
	
	protected $articleModel;
	protected $userPreferenceManager;
	
	function __construct(MailerModel $mailerModel,ArticleModel $articleModel,UserPreferenceManager $userPreferenceManager)
	{
		parent::__construct($mailerModel);
		$this->articleModel = $articleModel;
		$this->userPreferenceManager = $userPreferenceManager;
	}
	
	
	
	/**
	 * Main API to fetch article widget
	 */ 
	public function getData($userIds, $params)
	{
		if(!is_array($userIds) || count($userIds) == 0) {
			return array();
		}
		
		$widgetData = array();
		$userArticles = $this->getArticles($userIds);
		
		foreach($userArticles as $userId => $articlesForUser) {
			
			if(is_array($articlesForUser) && count($articlesForUser)) {
				$displayData = array();
				$displayData['articleWidgetsData'] = $articlesForUser;
				$displayData['countryID'] = $this->desiredCountryPref[$userId][0];
				$displayData['categoryID'] = $this->desiredCourseAndCategoryPref[$userId]['categoryId'];
				
				if($displayData['categoryID'] == 2 || $displayData['categoryID'] == 3) {
					$widgetData[$userId][$this->getWidgetKey()] = '';
					$widgetData[$userId][$this->getWidgetKey().'_titleArticle'] = '';
				}
				else {
					$widgetHTML = $this->getWidgetHTML($displayData);
					$widgetData[$userId][$this->getWidgetKey()] = $widgetHTML;
					/**
					 * Title article
					 * i.e. article which can be shown in mail subject
					 */ 
					$widgetData[$userId][$this->getWidgetKey().'_titleArticle'] = $articlesForUser[0]['blogTitle'];
				}
			}
			else {
				$widgetData[$userId][$this->getWidgetKey()] = '';
				$widgetData[$userId][$this->getWidgetKey().'_titleArticle'] = '';
			}
		}
		
		/**
		 * Log articles being generated for each user
		 */ 
		$this->_logMailedArticles($userArticles);
		
		return $widgetData;			
	}
	
	/**
	 * Fetch articles for all the users based on their preferences
	 */ 
	public function getArticles($userIds)
	{
		/**
		 * Get preferences for all the users i.e. desired course, category, subcategory, countries etc.
		 */ 
		$this->desiredCourseAndCategoryPref = $this->userPreferenceManager->getDesiredCourseAndCategoryPref($userIds);
		$this->desiredCountryPref = $this->userPreferenceManager->getDesiredCountryPref($userIds);
		
		/**
		 * Get article list for the widget
		 */ 
		$this->articleList = $this->_indexArticles($this->getArticleList());
		
		/**
		 * Compute articles for each user to appear in widget
		 */ 
		$userArticles = array();		
		foreach($userIds as $userId) {
			$userArticles[$userId] = $this->_getArticlesForUser($userId);
		}
		
		return $userArticles;
	}
		
	/**
	 * Index articles by country - category - subcategory - LDB course
	 * to optimize matching with user attributes
	 */ 
	private function _indexArticles($articles)
	{
		$indexedArticles = array();
		foreach($articles as $article) {
			$indexedArticles[intval($article['countryId'])]
							[intval($article['categoryId'])]
							[intval($article['subCategoryId'])]
							[intval($article['ldbCourseId'])][] = $this->_getArticleFormat($article);																   
		}
		return $indexedArticles;
	}
	
	private function _getArticleFormat($article)
	{
		if($article['mailerTitle'] != NULL){
			$article['blogTitle'] = $article['mailerTitle'];
		}
		else{
			$article['blogTitle'] = $article['blogTitle'];
		}
		
		return array(
					'blogId' => $article['blogId'],
					'blogTitle' => $article['blogTitle'],
					'url' => $article['url'],
					'blogImageURL' => $article['blogImageURL'],
					'numComments' => $article['numComments'],
					'categoryId' => $article['categoryId']
				);
	}
	
	/**
	 * Compute articles to send for a user
	 * based on his/her preferences
	 */ 
	private function _getArticlesForUser($userId)
	{
		$desiredCategory = (int) $this->desiredCourseAndCategoryPref[$userId]['categoryId'];
		$desiredSubCategory = (int) $this->desiredCourseAndCategoryPref[$userId]['subCategoryId'];
		$desiredCourse = (int) $this->desiredCourseAndCategoryPref[$userId]['ldbCourseId'];
		
		$desiredCountries = $this->desiredCountryPref[$userId];
		
		$articlesToBeExcluded = $this->getArticlesToBeExcluded($userId,$desiredCategory);
		$articlesToBeExcluded = array_fill_keys($articlesToBeExcluded,TRUE);
		
		$articleList = $this->articleList;
		$NUM_ARTICLES = $this->NUM_ARTICLES;
		$articlesForUser = array();
	
		foreach($desiredCountries as $desiredCountry) {
			
			$desiredCountry = intval($desiredCountry);
			/**
			 * Articles from desired course
			 */ 
			if($desiredCourse) {
				foreach($articleList[$desiredCountry][$desiredCategory][$desiredSubCategory][$desiredCourse] as $article) {
					if(!isset($articlesToBeExcluded[$article['blogId']])) {
						$articlesForUser[$article['blogId']] = $article;
						if(count($articlesForUser) == $NUM_ARTICLES) {
							break(2);
						}
					}
				}
			}
			
			/**
			 * Articles from desired subcategory
			 */ 
			if(count($articlesForUser) < $NUM_ARTICLES) {
				foreach($articleList[$desiredCountry][$desiredCategory][$desiredSubCategory] as $ldbCourseId => $ldbCourseArticles) {
					
					if($desiredCourse && $ldbCourseId == $desiredCourse) {
						continue;
					}
					
					foreach($ldbCourseArticles as $article) {
						if(!isset($articlesToBeExcluded[$article['blogId']])) {
							$articlesForUser[$article['blogId']] = $article;
							if(count($articlesForUser) == $NUM_ARTICLES) {
								break(3);
							}
						}
					}
				}
			}
			
			/**
			 * Articles from desired category
			 */ 
			if(count($articlesForUser) < $NUM_ARTICLES) {
				foreach($articleList[$desiredCountry][$desiredCategory] as $subCategoryId => $subCategoryArticles) {
					if($subCategoryId != $desiredSubCategory) {
						foreach($subCategoryArticles as $ldbCourseId => $ldbCourseArticles) {
							foreach($ldbCourseArticles as $article) {
								if(!isset($articlesToBeExcluded[$article['blogId']])) {
									$articlesForUser[$article['blogId']] = $article;
									if(count($articlesForUser) == $NUM_ARTICLES) {
										break(4);
									}
								}
							}
						}
					}
				}
			}
		}
		
		$articlesForUser = array_values($articlesForUser);
		
		/**
		 * There is an article deficit, fill it using deficit filling policy of the widget
		 */ 
		if(count($articlesForUser) < $NUM_ARTICLES) {
			$articlesForUser = $this->_fillArticleDeficit($userId,$desiredCategory,$articlesForUser);
		}
		
		return array_values($articlesForUser);
	}
	
	/**
	 * Fill the deficit in number of articles in the widget
	 * Each article widget (Latest News, Must Read) can have a different deficit filling policy
	 */ 
	private function _fillArticleDeficit($userId,$categoryId,$articlesForUser)
	{
		$articles = array();
		
		$deficit = $this->NUM_ARTICLES - count($articlesForUser);
		$articleIds = $this->getArticlesToFillDeficit($userId,$categoryId,$deficit);
		
		if(count($articleIds)) {
			$articlesData = $this->articleModel->getArticlesData($articleIds);
			foreach($articlesData as $article) {
				$articles[$article['blogId']] = $this->_getArticleFormat($article);
			}
		}
		
		return $articlesForUser + $articles;
	}
	
	/**
	 * Log articles being mailed to the users
	 */ 
	private function _logMailedArticles($articles)
	{
		if($this->mailer) {
			$this->mailerModel->storeMailedArticles($articles,$this->mailer->getId(),$this->getWidgetKey());
		}
	}
	
	public function setNumArticles($numArticles)
	{
		$this->NUM_ARTICLES = $numArticles;
	}
}