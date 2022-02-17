<?php
/*
   Copyright 2015 Info Edge India Ltd

   $Author: UGC akhter

   $Id: MentorConfig.php
*/

	 $config = array(
	 	'settings'=>array(
                                    'mentorBranchName' => array('Civil Engineering','Mechanical Engineering','Information Technology (IT)/Computer Science Engineering','Electrical/Electronics','Chemical Enginnering','Aeronautical/Aerospace Engineering','Automobile Engineering','Marine Engineering','Biomedical Engineering','Industrial Engineering','Petroleum Engineering','Environmental Engineering','Mining Engineering')
	 		),
	 	'enabledSubCats' => array(
	 			28=>array("name"=> "BBA" , "url" => SHIKSHA_HOME.'/bba/resources/bba-questions'),
	 			56=>array("name"=>"engineering", "url" => SHIKSHA_HOME.'/engineering-questions-coursepage'),
	 			69=>array("name"=> "fashion design" , "url" => SHIKSHA_DESIGN_HOME.'/fashion-textile-design-questions-coursepage'),
	 			84=>array("name"=> "hotel management" , "url" => SHIKSHA_HOSPITALITY_HOME.'/hotel-management-questions-coursepage'),
	 			70=>array("name"=> "interior design" , "url" => SHIKSHA_ASK_HOME.'/questions/design'),
	 			20=>array("name"=> "journalism" , "url" => SHIKSHA_ASK_HOME.'/questions/media-films-mass-communication'),
	 			33=>array("name"=> "law" , "url" => SHIKSHA_ASK_HOME.'/questions/arts-law-languages-and-teaching'),
	 			18=>array("name"=> "mass communication" , "url" => SHIKSHA_HOME.'/tags/mass-communication-media-tdp-14')

 			)
	    );
 
?>
