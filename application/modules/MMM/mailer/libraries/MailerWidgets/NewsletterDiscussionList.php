<?php
include_once('MailerWidgetAbstract.php');

class NewsletterDiscussionList extends MailerWidgetAbstract
{
	function __construct(MailerModel $mailerModel)
	{
		parent::__construct($mailerModel);
	}
	
	public function getData($userIds, $params = "")
	{
		$templateId = $params['templateId'];
		$newsletterParams = $this->mailerModel->getNewsletterParams($templateId);
		if(!trim($newsletterParams['discussionIds'])) {
			return array();
		}
		$discussionIds = explode(',',$newsletterParams['discussionIds']);

		if(count($discussionIds) == 0) {
			return array();
		}
		
		//$discussionIds = array(2489676,2458318,2490243);
		
		$this->CI->load->model('messageBoard/qnamodel');
		$discussionData = $this->CI->qnamodel->getDiscussionDetails($discussionIds);
		//_p($discussionData);
		
		$discussions = array();
		foreach($discussionIds as $discussionId) {
			foreach($discussionData as $discussion) {
				if($discussion['msgId'] == $discussionId) {
					$discussions[] = $discussion;
					break;
				}
			}
		}
		
		//_p($discussions);
		
		$data = array();
		$data['discussions'] = $discussions;
		
		$widgetHTML = $this->CI->load->view('mailer/MailerWidgets/NewsletterDiscussionList',$data,TRUE);
		//echo $widgetHTML;
		
		$widgetData = array();
		foreach($userIds as $userId) {
			$widgetData[$userId]['NewsletterDiscussionList'] = $widgetHTML;
		}
		return $widgetData;
	}
}
