<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config = array();

$config['TAG_CATEGORY_MAPPING'] = array(
		'17' => '23',  // Business Mgmt
		'20' => '56', // Engg
		'65' => '69',
		'422' => '23', // MBA 
		'413' => '56', // Btech
		'393' => '80',
		'394' => '80',
		'397' => '80',
		'395' => '81',
		'398' => '77',
		'401' => '133',
		'402' => '134',
		'405' => '131',
		'406' => '131',
		'407' => '64',
		'408' => '65',
		'409' => '32',
		'410' => '32',
		'411' => array('89','91'),
		'412' => array('74','76'),
		'414' => '59',
		'415' => '60',
		'416' => '60',
		'417' => '38',
		'418' => '38',
		'419' => '32',
		'420' => '38',
		'421' => '28',
		'423' => '33',
		'424' => '33',
		'425' => '70',
		'426' => '72',
		'427' => '26',
		'428' => '84',
		'429' => '100',
		'430' => '98',
		'431' => '100',
		'432' => array('18','20'),
		'433' => '20',
		'434' => array('19','18','20','21','16'),
		'435' => '25',
		'436' => '9'
);


$config['TAG_SPECIALIZATION_MAPPING'] = array( 
		'153' => '7',
		'197' => array('320','580','593','610','627','665','1491'), //Aeronautical Engineering
		'198' => array('322','336','595','612','629','667','1460'), //Automobile Engineering
		'199' => array('323','338','596','613','630','669','1462'), //Chemical Engineering
		'200' => array('324','582','597','614','631','670','1463'), //Civil Engineering
		'201' => array('325','340','598','615','632','672','1438','1465'), //Computer Science and Engineering
		'202' => array('326','583','599','616','633','674','1467'), //Electrical Engineering
		'203' => array('327','342','600','617','634','675'), //Electronics and Communication Engineering
		'204' => array('331','587','604','621','638','680','1473'),	//Mechanical Engineering
		'205' => array('1540'),//Bio Medical
		'207' => array('603','620','637','679','1472'), //Marine Engineering
		'208' => array('320','580','593','610','627','665','1491'),//Aerospace Engineering
		'209' => array('1441','321','581','594','611','628','666','1459'), // Agricultural and Food Engineering
		'210' => array('321','581','594','611','628','666','1459'), // Agricultural and Irrigation Engineering
		'211' => array('321','581','594','611','628','666','1459'), // Agricultural Engineering
		'221' => array('591,592,609,626,643,697,1490'), // Bio-technology
		'224' => array('1551'),
		'232' => array('1553'),
		'245' => array('1468'),
		'248' => array('1439','1544'),
		'253' => array('1542'),
		'256' => array('1442'),
		'257' => array('1443'),
		'263' => array('328','584','601','618','635','676','1469'),
		'266' => array('329','585','602','619','636','677','1470'),
		'267' => array('1539'),
		'268' => array('343','678','1471'),
		'277' => array('344','681','1474','1543'),
		'316' => array('1545'),
		'315' => array('1552'),
		'309' => array('335','590','608','625','642','689','1482'),
		'307' => array('349','688','1481'),
		'305' => array('325','340','598','615','632','672','1438','1465'), //Software Engineering
		'303' => array('1552'),
		'296' => array('1546'),
		'294' => array('1547'),
		'293' => array('1549'),
		'283' => array('333','589','606','623','640','683','1476'),
		'28' => array('10','21','727','742','759','779'),
		'32' => array('775','1307'),
		'151' => array('9','20','29','741','758','773'),
		'152' => array('5','16','737','754'),
		'153' => array('7','18','27','739','756'),
		'154' => array('4','15','25','736','753'),
		'155' => array('12','23','33','761'),
		'156' => array('1310'),
		'158' => array('716','723','731','748','765'),
		'159' => array('1312'),
		'160' => array('715','722','730','747','764'),
		'161' => array('714','721','729','746','763'),
	);

$config['LISTING_ATTR'] = array(
	
	"fees" => "Fees",
	"seats" => "Seats",
	"approval" => "Approval",
	"duration" => "Duration",
	"date_apply" => "Date of Apply",
	"salary" => "Salary",
	"affiliation" => "Affiliation",
	"application_url" => "Application URL",
	"entance_exams" => "Entrance Exams",
	"placement_companies" => "Placement Companies"
	);

define("NO_RESPONSE", "Sorry. No pattern Matched !!!");

global $autoAnsweringSynonyms;
$autoAnsweringSynonyms = array(
								"top" => array("top","best","premium"),
								"institute" => array("institute", "college", "b-school", "b school")
								);
?>