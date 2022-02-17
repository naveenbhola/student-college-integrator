<?php
/*
   Copyright 2014 Info Edge India Ltd

   $Author: Pranjul

   $Id: CollegePredictorConfig.php
*/

class CollegePredictorConfig{
	 // associative array stores static details for the exams
	 public static $settings =
	 array(
		  'tabNames'=>array(
				    'DESKTOP' =>array('JEE_MAINS'=>array('Find college and branch for your rank','Know cut-offs of a college','Want a branch? Know all the colleges')),
				    'MOBILE5' =>array('JEE_MAINS'=>array('Find college and branch for your rank','Know cut-offs of a college','Want a branch? Know all the colleges'))
				    ),
		  'defaultTabInfo'=>array(
			   	    'DESKTOP' =>array('JEE_MAINS'=>array('rankType'=>'Other','categoryName'=>'GEN','domicile'=>'NO','rank'=>1,'round'=>'all','examName'=>'JEE-Mains','tabType'=>'rank')),
				    'MOBILE5' =>array('JEE_MAINS'=>array('rankType'=>'Other','categoryName'=>'GEN','domicile'=>'NO','rank'=>1,'round'=>'all','examName'=>'JEE-Mains','tabType'=>'rank')),
				    )
		  );
}
?>
