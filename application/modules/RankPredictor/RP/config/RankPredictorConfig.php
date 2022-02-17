<?php
/*
   Copyright 2015 Info Edge India Ltd

   $Author: UGC akhter

   $Id: RankPredictorConfig.php
*/

////
// minRange : is used in js validation
// maxRange : is used in js validation
////
    global $engineeringtStreamMR;
    global $btechBaseCourse;
    global $fullTimeEdType;

	$config =
 array('settings'=>array(
	  'jee-main'=>array(
                            'examName'=>'JEE Main',
                            'requiredForm'=>'form1',
                            'collegePredictorExamName'=>'JEE-Mains',
                            'fieldOfInterest'=>2, // this is used as a hidden field of registration, where course mapping to exam field is needed.
                            'desiredCourse'=>52, // this is used as a hidden field of registration, where fieldOfInterest mapping to stream(B.tech/B.
                            'stream'=>$engineeringtStreamMR,
                            'baseCourse'=>$btechBaseCourse,
                            'educationType'=>$fullTimeEdType,                         
                            'examFieldList'=>'JEE_MAINS', // this is used as a hidden field of registration.
                            'examFieldListId'=>'JEE Mains', // this is used as a hidden field id for the registration.
                            'isShowAakashLogo'=>'NO',
                            'inputField'=>array('score'=>array('label'=>'JEE Score','minLength'=>1,'maxLength'=>3,'minRange'=>0,'maxRange'=>360,'isAllowedDecimal'=>'YES'),
                                                'rollNo'=>array('label'=>'JEE Roll No.','minLength'=>1,'maxLength'=>10)
                                                ),
			    'heading'=>'Predict your JEE Main '.date('Y').' rank based on your actual/expected score.',
                'formHeading'=>'Enter the information below and get your predicted JEE Main rank. We will also show you which colleges you could get into basis your rank:',
                             
			    'helpText'=>'You can calculate your expected score using the JEE Main '.date('Y').' Answer Key',
                            'disclaimer'=>'The above range is only for indicative purposes, and is not valid for any cut-off or admissions related application. Shiksha.com does not take any liability for the accuracy or inaccuracy of these predictions.',
                            'isCallAakashAPI'=>'YES',
                            'ishelpfulWidget'=>'YES',
                            'isTopCollegesWidget' => 'YES',
                            'isStaticCollgePredictorWidget'=>'YES',
                            'seoYear'=> date('Y'),
                            'seoName'=>'JEE Main',
                            'seoTitle'=>'JEE Main Rank Predictor '.date('Y'),
                            'seoDescription'=>'JEE Main Rank Predictor enables candidates to predict their rank based on the percentile/score they expect in the JEE Main '.date('Y').' exam. This tool will also Predict the college, branch, specialization that you will be eligible for in the JEE Main '.date('Y').' JOSAA counselling.',
                            'canonicalURL'=> SHIKSHA_HOME.'/b-tech/resources/jee-main-rank-predictor',
                            'staticWidgetHeading'=>'To view more colleges that you can apply to based on your state rank, category &amp; counselling rounds please use:',
                            'staticWidgetLinkText'=>'JEE MAIN '.date('Y').' College Predictor',
                            'staticWidgetUrl'=> SHIKSHA_HOME.'/b-tech/resources/jee-mains-college-predictor',
                            'keywords' => "jee main rank predictor, jee main rank predictor 2018, jee mains rank predictor",
                            
			),
                  
		  'comedk'=>array(
                            'examName'=>'COMEDK',
                            'requiredForm'=>'form3',
                            'fieldOfInterest'=>2, // this is used as a hidden field of registration, where course mapping to exam field is needed.
                            'desiredCourse'=>52, // this is used as a hidden field of registration, where fieldOfInterest mapping to stream(B.tech/B.E) is needed.
                            'stream'=>$engineeringtStreamMR,
                            'baseCourse'=>$btechBaseCourse,
                            'educationType'=>$fullTimeEdType,
                            'examFieldList'=>'COMED_K', // this is used as a hidden field value for the registration.
                            'examFieldListId'=>'COMED-K', // this is used as a hidden field id for the registration.
                            'isShowAakashLogo'=>'NO',
                            'inputField'=>array('score'=>array('label'=>'COMEDK Score','minLength'=>1,'maxLength'=>3,'minRange'=>0,'maxRange'=>180,'isAllowedDecimal'=>'NO')),
			    'heading'=>'Predict your COMEDK '.date('Y').' rank based on your actual/expected score.',
                            'formHeading'=>'Enter the information below and get your predicted COMEDK rank. We will also show you which colleges you could get into basis your rank:',
                             
			    'helpText'=>'You can calculate your expected score using the COMEDK '.date('Y').' Answer Key',
                            'disclaimer'=>'The above range is only for indicative purposes, and is not valid for any cut-off or admissions related application. Shiksha.com does not take any liability for the accuracy or inaccuracy of these predictions.',
                            'isCallAakashAPI'=>'NO',
                            'ishelpfulWidget'=>'YES',
                            'isStaticCollgePredictorWidget'=>'YES',
                            'isTopCollegesWidget' => 'YES',
                            'seoYear'=> date('Y'),
                            'seoName'=>'COMEDK',
                            'seoTitle'=>'COMEDK '.date('Y').' Rank Predictor - Shiksha.com',
                            'seoDescription'=>'COMEDK Rank Predictor tool to predict your rank in COMEDK '.date('Y').' entrance exam based on COMEDK exam score',
                            'canonicalURL'=> SHIKSHA_HOME.'/b-tech/resources/comedk-rank-predictor',
                            'staticWidgetHeading'=>'To view more colleges, cut-offs for branches and find colleges by branch go to:',
                            'staticWidgetLinkText'=>'COMEDK College Predictor',
                            'staticWidgetUrl'=> SHIKSHA_HOME.'/b-tech/resources/comedk-college-predictor',
                            'keywords' => '',
                            
                        ),
                  'jee-advanced'=>array(
                            'examName'=>'JEE Advanced',
                            'requiredForm'=>'form3',
                            'fieldOfInterest'=>2, // this is used as a hidden field of registration, where course mapping to exam field is needed.
                            'desiredCourse'=>52, // this is used as a hidden field of registration, where fieldOfInterest mapping to stream(B.tech/B.E) is needed.
                            'stream'=>$engineeringtStreamMR,
                            'baseCourse'=>$btechBaseCourse,
                            'educationType'=>$fullTimeEdType,                   
                            'examFieldList'=>'JEE_ADVANCE', // this is used as a hidden field value for the registration.
                            'examFieldListId'=>'JEE Advanced', // this is used as a hidden field id for the registration.
                            'isShowAakashLogo'=>'NO',
                            'inputField'=>array('score'=>array('label'=>'JEE Advanced Score','minLength'=>1,'maxLength'=>4,'minRange'=>0,'maxRange'=>400    ,'isAllowedDecimal'=>'YES')),
			    'heading'=>'Predict your JEE Advanced '.date('Y').' rank based on your actual/expected score.',
                'formHeading'=>'Enter the information below and get your predicted JEE Advanced rank. We will also show you which colleges you could get into basis your rank:',
                             
			    'helpText'=>'You can calculate your expected score using the JEE Advanced '.date('Y').' Answer Key',
                            'disclaimer'=>'The above range is only for indicative purposes, and is not valid for any cut-off or admissions related application. Shiksha.com does not take any liability for the accuracy or inaccuracy of these predictions.',
                            'isCallAakashAPI'=>'NO',
                            'ishelpfulWidget'=>'YES',
                            'isStaticCollgePredictorWidget'=>'NO',
                            'isTopCollegesWidget' => 'NO',
                            'seoYear'=> date('Y'),
                            'seoName'=>'JEE Advanced',
                            'seoTitle'=>'JEE Advanced '.date('Y').' Rank Predictor - Shiksha.com',
                            'seoDescription'=>"Shiksha's JEE Advanced Rank Predictor tool uses an innovative algorithm to predict your possible rank based on your JEE Advanced exam score.",
                            'canonicalURL'=> SHIKSHA_HOME.'/b-tech/resources/jee-advanced-rank-predictor',
                            'staticWidgetHeading'=>'To view more colleges, cut-offs for branches and find colleges by branch go to:',
                            'staticWidgetLinkText'=>'JEE ADVANCED 2017 College Predictor',
                            'staticWidgetUrl'=> SHIKSHA_HOME.'/b-tech/resources/jee-advanced-college-predictor',
                            'keywords' => '',
                            
                        ),
                  
	 		'RPEXAMS' => array( 
		 			    'jee-main' => array('name' => 'JEE Main '.date('Y'), 'url' => 'jee-main-rank-predictor'),
		 			    'comedk' => array('name' => 'COMEDK '.date('Y') , 'url' => 'comedk-rank-predictor'),
						'jee-advanced' => array('name' => 'JEE Advanced '.date('Y'), 'url' => 'jee-advanced-rank-predictor'),
	 				)
	 		)  
	    );
 
?>
