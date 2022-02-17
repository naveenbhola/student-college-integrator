<?php
include_once('MailerWidgetAbstract.php');

class NewsletterNotificationList extends MailerWidgetAbstract
{
	function __construct(MailerModel $mailerModel)
	{
		parent::__construct($mailerModel);
	}
	
	public function getData($userIds, $processingParams)
	{
		
		$mailerName = 'MMMImportantDatesMailer';
		$widgetHTML = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailerName, $processingParams);
		
		$widgetData = array();
		foreach($userIds as $userId) {
			$widgetData[$userId]['NewsletterNotificationList'] = $widgetHTML;
		}
		return $widgetData;
	}
}
