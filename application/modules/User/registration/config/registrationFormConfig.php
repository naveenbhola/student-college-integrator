<?php
define(USE_MISSED_CALL_VERIFICATION,true);

$config = array();
global $missedCallNumber;
$missedCallNumber = '+91-9212662010';

global $missedCallParams;
$missedCallParams = array(
	'desktop' 	=> 	array(),
	'moblie'	=>	array(
		'totalTimeForMisdCall'    => 60, // seconds
		'waitingTimeInterval'     => 1000, // milli -seconds
		'MisdCallVfyCallInterval' => 5000 // milli -seconds
		)
	);

$config['successTextMsg']  			= "Your Mobile is now verified on Shiksha. Click <Auto-Login> to get insights, recommendations & updates on colleges/courses/exams.";
$config['successTextMsgForFBUSER']  = "Your account is created on Shiksha. Click <Auto-Login> to get insights, recommendations & updates on colleges/courses/exams.";

$config['frequency_for_reminders'] = array(1,3,5);

global $reminder_sms_link;
$reminder_sms_link = 'www.shiksha.com/a/';

global $reminder_sms;
$reminder_sms = "Your registration on Shiksha is incomplete. Click <reminder-url> to complete it & start getting insights suggestions & updates on colleges, courses & exams";

$config['mobile_vfy_reminder'] = array(
	'actionMapping' => array(
		'sc' => 'shortlist course',
		'db' => 'get course brochure',
		'cd' => 'get contact details',
		'eg' => 'get exam guide',
		'qp' => 'get question papers',
		'pg' => 'get prep guide'
	),
	'trackingMap'=>array(
		'downloadBrochure' => 'db',
		'downloadEBrochure' => 'db',
		'downloadGuide' => 'eg',
		'downloadPrepGuide' => 'pg',
		'downloadPrepGuides' => 'pg',
		'downloadQuestionPapers' => 'qp',
		'downloadSamplePapers' => 'qp',
		'sendContactDetails' => 'cd',
		'shortlist' => 'sc',
		'shortlistCollege' => 'sc',
		'showContactDetails' => 'cd'
	),
	'SMSContent' => array(
		'landingUrl' => SHIKSHACLIENTIP.'/rm/',
		'text' => "You're almost there! Verify your mobile number on Shiksha to <action> by clicking on link <landing-url>."
	)
		
	);


$config['other_base_courses'] = array(130,131,132,133,134,135,136,137,138,139,140,141,142,143); 

$test_api_domain = '172.16.3.107:81';
$live_api_domain = 'services.shiksha.jsb9.net:81';
$client_api_domain = (ENVIRONMENT != 'production') ? $test_api_domain : $live_api_domain;

$config['base_courses_by_institute_id_api'] = $client_api_domain.'/listing/api/v1/info/getBLevelResponseFormData';

$config['getBIPResponseCourse'] = $client_api_domain.'/listing/api/v1/info/getBIPResponseCourse';

?>
