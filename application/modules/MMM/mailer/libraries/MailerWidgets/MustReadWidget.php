<?php
include_once('ArticleWidgetAbstract.php');

class MustReadWidget extends ArticleWidgetAbstract
{
	protected $NUM_ARTICLES = 3;
	
	function __construct(MailerModel $mailerModel,ArticleModel $articleModel,UserPreferenceManager $userPreferenceManager)
	{
		parent::__construct($mailerModel,$articleModel,$userPreferenceManager);
	}
	
	public function getArticleList()
	{
		$results = array();
		if($this->mailer) {
			$results = $this->mailerModel->getTaggedArticles($this->mailer->getId());
		}
		
		return $results;
	}
	
	public function getWidgetHTML($data)
	{
		if($this->mailer) {
			$data['mailerId'] = $this->mailer->getId();
		}
		return $this->CI->load->view('MailerWidgets/MustReadTemplate',$data,true);
	}
    
    public function getWidgetKey()
    {
        return "mustread";
    }
	
	/**
	 * Get articles to be excluded
	 * e.g. articles which have already been sent will be excluded
	 */
	public function getArticlesToBeExcluded($userId,$categoryId)
	{
		return $this->mailerModel->getMailedArticles($userId,$categoryId,$this->getWidgetKey());
	}
	
	/** 
	 * Articles to get in case there is a deficit in number of articles in widget 
	 * For must-read use rotaton policy 
	 */ 
	public function getArticlesToFillDeficit($userId,$categoryId,$deficit)
	{
		return $this->mailerModel->getArticlesByRotation($userId,$categoryId,$deficit);
	}
}