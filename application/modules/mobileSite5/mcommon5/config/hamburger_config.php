<?php
$config['menuLayer1Groups'] = array('grp1'=>'COURSES / COLLEGES',
									'grp2'=>'EXAMS',
									'grp3'=>'TOOLS',
									'grp4'=>'EXPERT GUIDANCE',
									'grp5'=>'RESOURCES',
									'grp6'=>'ABOUT SHIKSHA',
									);

$config['menuLayer1Subtitles'] = array(
									0 => array('name'=>'Find Colleges by Specialization', 'group'=>'grp1', 'apiCall'=>'getFindCollegesHtml'),
									1 => array('name'=>'College Rankings', 'group'=>'grp1', 'apiCall'=>'getRankingMenuHtml'),
									2 => array('name'=>'Read {name} Student Reviews', 'group'=>'grp1', 'apiCall'=>'_getReviewsHtml'),
									3 => array('name'=>'Compare Colleges', 'group'=>'grp1', 'apiCall'=>'getCompareCollegesHtml'),
									
									4 => array('name'=>'View {name} Exam Details', 'group'=>'grp2', 'apiCall'=>'getViewExamDetailsHtml'),
									5 => array('name'=>'Check {name} exam dates', 'group'=>'grp2', 'apiCall'=>'getExamImportantDatesHtml'),
									
									6 => array('name'=>'Predict your Exam Rank', 'group'=>'grp3', 'apiCall'=>'getRankPredictorHtml'),
									7 => array('name'=>'Predict college basis rank/score', 'group'=>'grp3', 'apiCall'=>'getCollegePredictorHtml'),
									8 => array('name'=>'IIM & Non IIM Call Predictor', 'group'=>'grp3', 'apiCall'=>'getIIMPredictorHtml'),
									9 => array('name'=>'Check Alumni Salary Data', 'group'=>'grp3', 'apiCall'=>'getAlumniHtml'),
									18 =>array('name'=>'DU Cut-Offs', 'group'=>'grp3', 'apiCall'=>'collegeCutoffHTML'),
									
									10 => array('name'=>'Ask Shiksha Experts', 'group'=>'grp4', 'apiCall'=>'getAnaLayerHtml'),
									11 => array('name'=>'Ask Current {name} Students', 'group'=>'grp4', 'apiCall'=>'getCampusRepPrograms'),
									
									12 => array('name'=>'News & Articles', 'group'=>'grp5', 'apiCall'=>'getNewsHtml'),
									13 => array('name'=>'Student Discussions', 'group'=>'grp5', 'apiCall'=>'getDiscussionsHtml'),
									14 => array('name'=>'View Student Questions', 'group'=>'grp5', 'apiCall'=>'getQuestionsHtml'),
									
									15 => array('name'=>'Learn About Us', 'group'=>'grp6', 'apiCall'=>'getAboutHtml'),
									16 => array('name'=>'Student Helpline', 'group'=>'grp6', 'apiCall'=>'getHelpHtml'),
									17 => array('name'=>'Find Colleges by Course', 'group'=>'grp1', 'apiCall'=>'getFindCollegeByCourseHtml'),
									19 => array('name'=>'Apply to colleges', 'group'=>'grp5', 'apiCall'=>'getApplyCollegesHtml')
								);

//mapping of stream id with menu id, stream id 0 stands for all
$config['streamWiseMenuIds'] = array(
	'0' => array(0, 17, 1, 2, 3, 4, 5, 6, 7, 8, 18, 9, 10, 11, 12, 19, 13, 14, 15, 16),
	'1' => array(0, 17, 1, 2, 3, 4, 5, 7, 8, 9, 10, 11, 12, 19, 13, 14, 15, 16),
	'2' => array(0, 17, 1, 2, 3, 4, 5, 6, 7, 10, 11, 12, 19, 13, 14, 15, 16),
	'3' => array(0, 17, 1, 2, 3, 4, 7, 10, 11, 12, 13, 14, 15, 16),
	'4' => array(0, 17, 1, 2, 3, 4, 10, 11, 7, 12, 13, 14, 15, 16),
	'5' => array(0, 17, 1, 2, 3, 4, 7, 10, 11, 12, 13, 14, 15, 16),
	'6' => array(0, 17, 1, 2, 3, 4, 10, 11, 12, 13, 14, 15, 16),
	'7' => array(0, 17, 1, 2, 3, 4, 18, 10, 11, 12, 13, 14, 15, 16),
	'8' => array(0, 17, 1, 2, 3, 4, 10, 11, 12, 13, 14, 15, 16),
	'9' => array(0, 17, 1, 2, 3, 4, 18,	10, 11, 12, 13, 14, 15, 16),
	'10' => array(0, 17, 1, 2, 3, 4, 10, 11, 12, 13, 14, 15, 16),
	'11' => array(0, 17, 1, 2, 3, 4, 18, 10, 11, 12, 13, 14, 15, 16),
	'12' => array(0, 17, 1, 2, 3, 4, 10, 11, 12, 13, 14, 15, 16),
	'13' => array(0, 17, 1, 2, 3, 4, 18, 10, 11, 12, 13, 14, 15, 16),
	'14' => array(0, 17, 1, 2, 3, 4, 10, 11, 12, 13, 14, 15, 16),
	'15' => array(0, 17, 1, 2, 3, 4, 10, 11, 12, 13, 14, 15, 16),
	'16' => array(0, 17, 1, 2, 3, 4, 10, 11, 12, 13, 14, 15, 16),
	'17' => array(0, 17, 1, 2, 3, 4, 10, 11, 12, 13, 14, 15, 16),
	'18' => array(0, 17, 1, 2, 3, 4, 10, 11, 12, 13, 14, 15, 16),
	'19' => array(0, 17, 1, 2, 3, 4, 10, 11, 12, 13, 14, 15, 16),
);

$config['aboutusUrl']   =  SHIKSHA_HOME.'/mcommon5/MobileSiteStatic/aboutus';
$config['articleUrl']   =  SHIKSHA_HOME.'/articles-all';
$config['helplineUrl']  =  SHIKSHA_HOME.'/mcommon5/MobileSiteStatic/studentHelpLine';
$config['contactusUrl'] = SHIKSHA_HOME.'/mcommon5/MobileSiteStatic/contactUs';
$config['iimPredictorUrl'] = SHIKSHA_HOME."/mba/resources/iim-call-predictor";
$config['profileUrl']	= SHIKSHA_HOME.'/userprofile/edit';
$config['discussionsHome'] = SHIKSHA_ASK_HOME_URL.'/discussions';

$config['askCurrentStudents'] = array('3' => array('name' => 'MBA',
												   'url'	=> SHIKSHA_HOME.'/mba/resources/ask-current-mba-students'));
$config['collegeReviewsUrl']	= array('1' => array('name' => 'MBA',
													 'url'	=> SHIKSHA_HOME.'/mba/resources/college-reviews'),
										'2' => array('name' => 'B.Tech',
													 'url'	=> SHIKSHA_HOME.'/btech/resources/college-reviews/1'));
$config['examImportantDatesUrl']	= array(
											'1' => array(
													'name' 	=> 'MBA',
													 	'url'	=> SHIKSHA_HOME.'/mba/resources/exam-calendar'),
											'2' => array(
														'name'	=> 'Engineering',
													 	'url'	=> SHIKSHA_HOME.'/engineering-exams-dates'
													 	)
											);

$config['applyColleges']	= array(
											'1' => array(
													'name' 	=> 'MBA',
													 	'url'	=> SHIKSHA_HOME.'/mba/resources/application-forms'),
											'2' => array(
														'name'	=> 'Engineering',
													 	'url'	=> SHIKSHA_HOME.'/engineering/resources/application-forms'
													 	)
											);


$config['streamToTagsMapping'] = array(	
	'1' => array('id'=>17,'name' => 'Business Management Studies'),
	'2' => array('id'=>20,'name' => 'Engineering'),
	'3' => array('id'=>10,'name' => 'Design'),
	'4' => array('id'=>22,'name' => 'Hotel Management'),
	'5' => array('id'=>15,'name' => 'Law'),
	'6' => array('id'=>365,'name' => 'Animation'),
	'7' => array('id'=>118,'name' => 'Mass Communication'),
	'8' => array('id'=>266,'name' => 'Information Technology'),
	'9' => array('id'=>19,'name' => 'Arts & Humanities'),
	'10' => array('id'=>19,'name' => 'Arts & Humanities'),
	'11' => array('id'=>18,'name' => 'Science'),
	'12' => array('id'=>21,'name' => 'Architecture'),
	'13' => array('id'=>55,'name' => 'Accounting'),
	'14' => array('id'=>8,'name' => 'Banking,Finance & Insurance'),
	'15' => array('id'=>24,'name' => 'Aviation'),
	'16' => array('id'=>16,'name' => 'Teaching & Education'),
	'17' => array('id'=>83,'name' => 'Nursing'),
	'18' => array('id'=>12,'name' => 'Medical'),
	'19' => array('id'=>114,'name' => 'Beauty'),
	);
