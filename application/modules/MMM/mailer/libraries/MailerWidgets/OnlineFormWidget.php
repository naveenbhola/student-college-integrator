<?php
include_once('MailerWidgetAbstract.php');

class OnlineFormWidget extends MailerWidgetAbstract
{
	private $onlineFormModel;
	
	function __construct(MailerModel $mailerModel,OnlineModel $onlineFormModel)
	{
		parent::__construct($mailerModel);
		$this->onlineFormModel = $onlineFormModel;
	}
	
	public function getData($userIds, $params = "",$processingParams = "")
	{
		$incompleteOnlineFormsData = $this->getIncompleteOnlineFormsData($processingParams);
		
		$widgetData = array();
		
		foreach($userIds as $userId) {
			$data = $incompleteOnlineFormsData[$userId];
			if($data) {	
				$displayData = array();
				$displayData['onlineFormUserName'] = $data['name'];
				$displayData['course_name'] = $data['course_name'];
				$displayData['institute_name'] = $data['institute_name'];
				$displayData['online_form_url'] = $data['online_form_url'];
				
				foreach($params as $key => $value) {
					if ($value == 'OnlineFormsUserName') {
						$widgetData[$userId][$value] = $displayData['onlineFormUserName'];
					}
					else if ($value == 'OnlineFormsInstituteDetails' && !empty($displayData['institute_name'])) {
						$widgetData[$userId][$value] = $this->CI->load->view('MailerWidgets/OnlineFormsInstituteDetails',$displayData,true);
					}
				}
			}
			else {
				foreach($params as $key => $value) {
					$widgetData[$userId][$value] = '';
				}
			}
		}
		
		return $widgetData;
	}
	
	/**
	 * Fetch users who started filling online forms, but did not complete
	 */ 
	public function getIncompleteOnlineFormsData($processingParams)
	{
		$data = array();
		
		$timeWindow = $processingParams['timeWindow'];
		if($timeWindow) {
			list($timeWindowStart,$timeWindowEnd) = explode(';',$timeWindow);
			$date = date('Y-m-d',strtotime($timeWindowStart));
			$timeWindowStartTime = date('H:i:00',strtotime($timeWindowStart));
			$timeWindowEndTime = date('H:i:00',strtotime($timeWindowEnd));
			if($timeWindowEndTime == '00:00:00') {
				$timeWindowEndTime = '23:59:59';
			}
			$currentTime = $processingParams['currentTime'];
			$data = $this->onlineFormModel->getUserIdsForIncompleteFormsAutoMailer($timeWindowStartTime,$timeWindowEndTime,$date,'true',$currentTime);
		}
		
		return $data;
	}
}
