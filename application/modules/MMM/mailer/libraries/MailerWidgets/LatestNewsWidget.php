<?php
include_once('ArticleWidgetAbstract.php');

class LatestNewsWidget extends ArticleWidgetAbstract
{
	protected $NUM_ARTICLES = 5;
	
	function __construct(MailerModel $mailerModel,ArticleModel $articleModel,UserPreferenceManager $userPreferenceManager)
	{
		parent::__construct($mailerModel,$articleModel,$userPreferenceManager);
	}

	public function getArticleList()
	{
		return $this->articleModel->getLatestArticles();
	}

	public function getWidgetHTML($data)
	{
		return $this->CI->load->view('MailerWidgets/LatestNewsWidgetTemplate',$data,true);
	}
	
	public function getWidgetKey()
    {
        return "article";
    }
	
	/**
	 * Get articles to be excluded
	 */
	public function getArticlesToBeExcluded($userIds)
	{
		return array();
	}
	
	/** 
	 * Articles to get in case there is a deficit in number of articles in widget 
	 */ 
	public function getArticlesToFillDeficit($userId,$deficit)
	{
		return array();
	}
}