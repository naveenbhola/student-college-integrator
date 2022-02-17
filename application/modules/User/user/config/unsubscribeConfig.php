<?php
$config = array();
//showFT means show on frontend
$config['mailerCategory'] = array(
		'1'=> array('category' =>'Action triggered emails'	, 'autoOn' => 1,'showFT' => 1,'description' => "Emails sent on your explicit action on the site such as 'Download Brochure', 'Rate my chances' etc. Please note this option would be auto enabled next time explicit action is done."),
		'2'=> array('category' =>'Service emails'     		, 'autoOn' => 0,'showFT' => 1,'description' => "Emails for services you opted like counselling services, online application form etc. Please note we strongly discourage to disable this option so you continue to receive emails about services opted."),
		'3'=> array('category' =>'Important Updates'      	, 'autoOn' => 0,'showFT' => 1,'description' => "Emails containing important updates about courses, exams, colleges, articles, questions of your interest."),
		'4'=> array('category' =>'Recommendation emails'  	, 'autoOn' => 0,'showFT' => 1,'description' => "System generated emails, digest to recommend you relevant colleges, courses, exams basis your profile."),
		'5'=> array('category' =>'Promotional emails'	  	, 'autoOn' => 0,'showFT' => 1,'description' => "Emails sent by Shiksha or its partners to share information on relevant promotions, paid services or upcoming events."),		
		'6'=> array('category' =>'Miscellaneous emails'  	, 'autoOn' => 0,'showFT' => 0,'description' => "")
	);
?>