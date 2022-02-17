<?php
include_once('MailerWidgetAbstract.php');
class MEAAlertsWidget extends MailerWidgetAbstract
{
	private $collaborativeFilter;
	
	function __construct(MailerModel $mailerModel,profile_based_collaborative_filter_lib $collaborativeFilter)
	{
		parent::__construct($mailerModel);
		$this->collaborativeFilter = $collaborativeFilter;
		$this->CI = & get_instance();
		$this->CI->load->config('mailer/mailerConfig');
		$this->mptHtmlHashkey = $this->CI->config->item('mptHtmlHashkey');
		$this->mailerName = 'ShikshaMEAMailer';
	}
	
	/**
	 * API for getting exam dates data
	 */
	public function getData($userIds, $params)
	{
		$mailData = array();
		$mailData['leanHeaderFooterV2'] = 1;
		$mailData['mailer_name'] = $this->mailerName;
		$mailData['mailer_type'] = 'MMM';
		$mailData['mptHtmlHashkey'] = $this->mptHtmlHashkey;
		$mailData['params'] = $params;

		$mptHTML = array();
		if(!empty($userIds)){
			try {
				$mailerParams = array();
				$mailerParams['mailerDetails']['mailer_id'] = '121';
				$mailerParams['mailerDetails']['mailer_name'] = $this->mailerName;
				$mailerParams['mailerDetails']['mailer_type'] = 'MMM';
				$mptHTML = Modules::run('MPT/MPTController/getMPTHtmlForUsers', $userIds, $mailerParams);
			} catch(Exception $e) {
				mail('naveen.bhola@shiksha.com,mohd.alimkhan@shiksha.com,virender.singh@shiksha.com','Exception in getting HTML for MPT tupple (file MEAAlertsWidget.php) at '.date('Y-m-d H:i:s'), 'Exception: '.$e->getMessage().'<br/>Chunk:'.print_r($userIds, true));
			}
		}
		$mailer_html = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $this->mailerName, $mailData);
		$data = array();
		foreach ($userIds as $userId) {
			if(!empty($mptHTML[$userId])) {
				$mailer_html = str_replace($this->mptHtmlHashkey, $mptHTML[$userId], $mailer_html);
			}
			else{
				$mailer_html = str_replace($this->mptHtmlHashkey, '', $mailer_html);
			}
			$data[$userId]['MEAAlerts'] = $mailer_html;
			$data[$userId]['Stream'] = $params['stream']['name'];
			if($params['stream']['id'] == 1){
				$data[$userId]['Stream'] = 'MBA';
			}else if($params['stream']['id'] == 21){
				$data[$userId]['Stream'] = 'Government';
			}
		}
		unset($mailer_html);
		unset($mptHTML);
		unset($mailData);
		unset($mailerParams);
		return $data;
	}
}
