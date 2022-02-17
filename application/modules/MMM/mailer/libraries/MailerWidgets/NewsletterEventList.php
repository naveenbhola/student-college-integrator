<?php
include_once('MailerWidgetAbstract.php');

class NewsletterEventList extends MailerWidgetAbstract
{
	function __construct(MailerModel $mailerModel)
	{
		parent::__construct($mailerModel);
	}
	
	public function getData($userIds, $params = "")
	{
		$templateId = $params['templateId'];
		$newsletterParams = $this->mailerModel->getNewsletterParams($templateId);
		$eventIds = explode(',',$newsletterParams['eventIds']);
	
		$this->CI->load->model('events/eventmodel');
		$eventData = $this->CI->eventmodel->getEventDetails($eventIds);
		
		//_p($eventData);
		
		$data = array();
		$data['events'] = $eventData;
		
		$widgetHTML = $this->CI->load->view('mailer/MailerWidgets/NewsletterEventList',$data,TRUE);
		//echo $widgetHTML;
		
		$widgetData = array();
		
		foreach($userIds as $userId) {
			$widgetData[$userId]['NewsletterEventList'] = $widgetHTML;
		}
		return $widgetData;
	}
}