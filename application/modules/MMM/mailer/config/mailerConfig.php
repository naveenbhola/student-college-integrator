<?php
$config = array();
$config['mptHtmlHashkey'] = '#mptTuppleHtml#';
$config['numberOfArticlesInNewsletterMailer'] = 5;
$config['newsletterMailHeaderFooterType'] = 'MMMV3';

$config['mailerAdminUserId'] = 166660;
$config['MMM_BaseProductId'] = 105;

$config['senderEmailIds'] = array('noreply@shiksha.com','no-reply@shiksha.com','marcomm@shiksha.com','marketing@shiksha.com', 'news@shiksha.com', 'collegealerts@shiksha.com', 'admissions@shiksha.com', 'counselling@shiksha.com', 'features@shiksha.com', 'research@shiksha.com', 'college.reviews@shiksha.com', 'studyabroad@shiksha.com', 'learn.intern@shiksha.com', 'learn-intern@shiksha.com');

$config['dripCampaignTypes'] = array(		
											"OPEN"=>'Opened',
											"OPENCLICK"=>'Opened & Clicked',
											"OPENNOTCLICK"=>'Opened & Not Clicked',
											"NOTOPEN"=>'Not Opened & Clicked'
								);
$config['indexToCampaignMapping'] = array(
											0=>"parent",
											1=>"opened",
											2=>"openedAndClicked",
											3=>"openedAndNotClicked",
											4=>"notOpenedAndNotClicked"
										);
$config['routingKey'] = array(0=>'rkey_1',1=>'rkey_1',2=>'rkey_2',3=>'rkey_2',4=>'rkey_3',5=>'rkey_3',6=>'rkey_4',7=>'rkey_4',8=>'rkey_5',9=>'rkey_5');

$config['dripCampaignMapping'] = array(

'OPEN' => array(
	"isOpen" => "yes"
),
'NOTOPEN' => array(
	"isOpen" => "no"
),
'OPENNOTCLICK' => array(
	"isOpen" => "yes",
	"isClick" => "no"
),
'OPENCLICK' => array(
	"isClick" => "yes"
)

);

$config['indexToDripMailerType'] = array(
											1=>"OPEN",
											2=>"OPENCLICK",
											3=>"OPENNOTCLICK",
											4=>"NOTOPEN"
										);
?>

