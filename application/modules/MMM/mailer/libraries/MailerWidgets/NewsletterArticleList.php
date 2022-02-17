<?php
include_once('MailerWidgetAbstract.php');

class NewsletterArticleList extends MailerWidgetAbstract
{
	function __construct(MailerModel $mailerModel)
	{
		parent::__construct($mailerModel);
	}
	
	public function getData($userIds, $params = "")
	{
		$this->CI = & get_instance();	
		$this->CI->load->config('mailerConfig');

		$this->CI->load->model('mailer/mailermodel');
		$newsletterParams = $this->CI->mailermodel->getNewsletterParams($params['templateId']);

		$params['include_MPT_Tuple'] = $newsletterParams['include_MPT_tuple'];
		$params['articleIds'] = $newsletterParams['articleIds'];

		$mailerName = 'MMMNewsletterArticles';
		
		$params['headerFooterType'] = $this->CI->config->item('newsletterMailHeaderFooterType');
		$params['numberOfArticlesRequired'] = $this->CI->config->item('numberOfArticlesInNewsletterMailer');
		$params['mptHtmlHashkey'] = $this->CI->config->item('mptHtmlHashkey');	
		$params['leanHeaderFooterV2'] = 1;

		$widgetHTML = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailerName, $params);
		return $widgetHTML;
	}
}
