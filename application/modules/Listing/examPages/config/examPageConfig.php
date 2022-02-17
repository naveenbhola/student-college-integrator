<?php
// Mapping of section identifier and page type(seo url)
$config['examPagesActiveSections'] = array( 
									'homepage'=>"homepage",
									'admitcard'=>"admit-card",
									'answerkey'=>"answer-key",
									'importantdates'=>"dates",
									'applicationform'=>"application-form",
									'counselling'=>"counselling",
									'cutoff'=>"cutoff",
									'pattern'=>"pattern",
									'results'=>"results",
									'samplepapers'=>"question-papers",
									'slotbooking'=>"slot-booking",
									'syllabus'=>'syllabus',
									'vacancies' => 'vacancies',
									'callletter' => 'call-letter',
									'news' => 'news',
									'preptips' => 'preptips'
                                );

// Mapping of section identifier (Breadcrumb)
$config['sectionNamesMapping'] = array(
									'homepage'=> "Summary",
									'admitcard'=>"Admit Card",
									'answerkey'=>"Answer Key",
									'importantdates' => "Dates",
									'applicationform'=>"Application Form",
									'counselling'=>"Counselling",
									'cutoff'=>"Cut Off",
									'pattern'=>"Pattern",
									'results'  =>"Results",
									'samplepapers'=>"Question Papers",
									'slotbooking'=>"Slot Booking",
									'syllabus'=>'Syllabus',
									'preptips' => 'Prep Tips',
									'vacancies' => 'Vacancies',
									'callletter' => 'Call Letter',
									'news' => 'News'
                                );

//Old Exam Page Redirection mapping to new Exam pages
$config ['engExamRedirectedToNewExamPages'] = array (
													"jee-main" => "jee mains",
													"jee-advanced" => "jee advanced",
													"bitsat" => "bitsat",
													"karnataka-cet" => "kcet",
													"viteee" => "viteee",
													"srmeee" => "srmjeee",
													"comedk" => "comedk",
													"keam" => "keam",
													"rpet"=>"rpet", 
													"wbjee"=>"wbjee",
													"uptu-see" => "upsee",
													"upsee" => "upsee",
													"eamcet" => "eamcet",
													"tnea" => "tnea", 
												    "kiitee"=>"kiitee"
											);

//exam tags
$config ['examPageTags'] = array ('CAT'         => 'cat2014',
                                  'XAT'         => 'xat2015',
                                  'MAT'         => 'mat2014-4',
                                  'CMAT'        => 'cmat2014-2',
                                  'SNAP'        => 'snap2015',
                                  'IIFT'        => 'iift2015',
                                  'NMAT'        => 'nmat2015',
                                  'IBSAT'       => 'ibsat2015',
								  'JEE Mains'       => 'jeemain2015',
								  'JEE Advanced'       => 'jeeadvanced2015',
								  'KIITEE'       => 'kiitee2015',
								  'BITSAT'       => 'bitsat2015',
								'KCET'       => 'kcet2015',
								'RPET'       => 'rpet2015',
								'EAMCET'       => 'eamcet2015',
								'TNEA'       => 'tnea2015',
								'KEAM'       => 'keam2015',
								'UPSEE'       => 'upsee2015',
								'WBJEE'       => 'wbjee2015',
								'COMEDK'       => 'comedk2015',
								'VITEEE'       => 'viteee2015',
								'SRMJEEE'       => 'srmjeee2015',
								'UPES-EAT' => 'upeseat2015',
								'AEEE' => 'aeee2015',
								'PESSAT' => 'pessat2015',
								'PESSAT MBA' => 'pessatmba2015',
								'VITMEE' => 'vitmee2015',
								'JCECE' => 'jcece2015',
								'LPU-NEST' => 'lpunest2015',
								'ATMA' => 'atma2015',
								'KIITEE MBA' => 'kiitteemanagement2015',
								'MICAT' => 'micat2015',
								'GATE' => 'gate2015',
								'MU-OET' => 'muoet2015',
								'NATA' => 'nata2015',
                                'OJEE' => 'ojeemba2015',
                                'AUEET'=> 'aueet2015',
                                'MAH-CET'=> 'mahcet2015'
								);

$config['examMapping'] = array('mba'=>101,
							   'engineering'=>10,
							   'design'=>3,
							   'law'=>5
							   );


//default ordering of pages in Exam Content CMS

$config['page_order'] = array('homepage' => 1,'importantdates' => 2,'applicationform' => 3,'pattern' => 4,'syllabus' => 5,'samplepapers' => 6,'admitcard' => 7,'answerkey' => 8,'cutoff' => 9,'results' => 10,'slotbooking' => 11,'counselling' => 12,'preptips'=> 13,'vacancies' => 14,'callletter' => 15, 'news' => 16);

$config['homepage_section_order'] = array('Summary' => 1,'Eligibility' => 2,'Process' => 3,'Exam Centers' => 4,'Exam Analysis' => 5,'Student Reaction' => 6,'Official Website' => 7,'Phone Number' => 8,'Contact Information' => 9);

//below mapping is used in updateToMultiple Groups content flow
$config['sectionToPageMapping'] = array(
							'summary' => array(
										array(
											'type' => 'wiki',
											'section_name' => 'homepage',
											'entity_type' => 'Summary'
											)
									),
							'eligibility' => array(
										array(
											'type' => 'wiki',
											'section_name' => 'homepage',
											'entity_type' => 'Eligibility'
											)
									),
							'process' => array(
										array(
											'type' => 'wiki',
											'section_name' => 'homepage',
											'entity_type' => 'Process'
											)
									),
							'examcenters' => array(
										array(
											'type' => 'wiki',
											'section_name' => 'homepage',
											'entity_type' => 'Exam Centers'
											)
									),
							'examanalysis' => array(
										array(
											'type' => 'wiki',
											'section_name' => 'homepage',
											'entity_type' => 'Exam Analysis'
											)
									),
							'studentreaction' => array(
										array(
											'type' => 'wiki',
											'section_name' => 'homepage',
											'entity_type' => 'Student Reaction'
											)
									),
							'contactinfo' => array(
										array(
											'type' => 'wiki',
											'section_name' => 'homepage',
											'entity_type' => 'Official Website'
											),
										array(
											'type' => 'wiki',
											'section_name' => 'homepage',
											'entity_type' => 'Phone Number'
											),
										array(
											'type' => 'wiki',
											'section_name' => 'homepage',
											'entity_type' => 'Contact Information'
											)
								),
							'homeExtra' => array(
								array('type' => 'wiki',
									'section_name' => 'homepage'
									)
								),
							'importantdates' => array(
										array('type' => 'wiki',
											'section_name' => 'importantdates','entity_type' => 'importantdates'),
										array('type' => 'dates','section_name' => 'importantdates')
									),
							'pattern' => array(array('type'=>'wiki','section_name' => 'pattern','entity_type' => 'pattern')),
							'vacancies' => array(array('type'=>'wiki','section_name' => 'vacancies','entity_type' => 'vacancies')),
							'callletter' => array(array('type'=>'wiki','section_name' => 'callletter','entity_type' => 'callletter')),
							'news' => array(array('type'=>'wiki','section_name' => 'news','entity_type' => 'news')),
							'syllabus' => array(array('type'=>'wiki','section_name' => 'syllabus','entity_type' => 'syllabus')),
							'results' => array(
										array('type' => 'wiki',
											'section_name' => 'results','entity_type' => 'results'),
										array('type' => 'dates','section_name' => 'results')
									),
							'admitcard' => array(array('type'=>'wiki','section_name' => 'admitcard','entity_type' => 'admitcard')),
							'slotbooking' => array(array('type'=>'wiki','section_name' => 'slotbooking','entity_type' => 'slotbooking')),
							'answerkey' => array(array('type'=>'wiki','section_name' => 'answerkey','entity_type' => 'answerkey')),
							'cutoff' => array(array('type'=>'wiki','section_name' => 'cutoff','entity_type' => 'cutoff')),
							'counselling' => array(array('type'=>'wiki','section_name' => 'counselling','entity_type' => 'counselling')),
							'applicationform' => array(array('type'=>'wiki','section_name' => 'applicationform','entity_type' => 'applicationform'),
								array('type'=>'wiki','section_name' => 'applicationform','entity_type' => 'applicationformurl'),
								array('type'=>'files','entity_type' => 'applicationform'),
								),
							'samplepaperswiki' => array(array('type'=>'wiki','section_name' => 'samplepapers','entity_type' => 'samplepapers')
								),
							'preptips' => array(array('type'=>'wiki','section_name' => 'preptips','entity_type' => 'preptipswikidata1'),
								array('type'=>'wiki','section_name' => 'preptips','entity_type' => 'preptipswikidata2'),
								array('type'=>'files','entity_type' => 'preptips')
								),
							'samplepapers' => array(array('type'=>'files','entity_type' => 'samplepapers')),
							'guidepapers' => array(array('type'=>'files','entity_type' => 'guidepapers'))
	);

$config['wikiFields'] = array(
'pattern' => 'Pattern', 'syllabus' => 'Syllabus', 'cutoff' => 'Cut-Off', 'admitcard' => 'Admit Card', 'answerkey' => 'Answer Key', 'counselling' => 'Counselling', 'slotbooking'=>"Slot Booking",'vacancies' => 'Vacancies','callletter' => 'Call Letter','news' => 'News'
);

define("D_instituteAcceptingLimit", 10);
define('M_instituteAcceptingLimit',3);
define("D_similarExamsLimit", 10);
define("M_similarExamsLimit", 4);
define("D_totalPaperCount", 8);
define("M_totalPaperCount", 4);
define("M_samplePaperCount", 2);
define("D_samplePaperCount", 4);
define("D_updatesCount", 5);
define("M_updatesCount", 5);
define("D_preptipsCount", 4);
define("M_preptipsCount", 2);

$config['pcTrackingKeys'] = array(
								'download_guide'=>1288,           // actionType => tracking_key
								'download_sample_paper'=>1289,
								'download_sample_paper_page'=>1306,
								'download_prep_guide'=>1300,
								'apply_online'=>1290,
								'ask_question'=>1297,
								'subscribe_to_latest_updates'=>1303,
								'viewed_response'=>1309,
								'download_application_form'=>1312,
                                                                'download_guide_middle'=>1485,
                                                                'download_sample_paper_middle'=>1487,
								'download_prep_tips_page'=>1745
							);
$config['msTrackingKeys'] = array(
								'download_guide'=>1291,
								'download_sample_paper'=>1292,
								'download_sample_paper_page'=>1307,
								'download_prep_guide'=>1301,
								'apply_online'=>1293,
								'ask'=>1298,
								'subscribe_to_latest_updates'=>1304,
								'viewed_response'=>1310,
								'download_application_form'=>1313,
								'download_prep_tips_page'=>1747
							);
$config['ampTrackingKeys'] = array(
								'download_guide'=>1294,
								'download_sample_paper'=>1295,
								'download_sample_paper_page'=>1308,
								'download_prep_guide'=>1302,
								'apply_online'=>1296,
								'ask'=>1299,
								'subscribe_to_latest_updates'=>1305,
								'viewed_response'=>1311,
								'download_application_form'=>1314,
								'download_prep_tips_page'=>1749
							);
$config['errorCodes'] = array(
	1 => 'UPLOAD_ERR_INI_SIZE',
    2 => 'UPLOAD_ERR_FORM_SIZE',
    3 => 'UPLOAD_ERR_PARTIAL',
    4 => 'UPLOAD_ERR_NO_FILE',
    6 => 'UPLOAD_ERR_NO_TMP_DIR',
    7 => 'UPLOAD_ERR_CANT_WRITE',
    8 => 'UPLOAD_ERR_EXTENSION',
    
    9 => 'UPLOAD_ERR_NO_FILE_NAME',
    10=> 'UPLOAD_ERR_UNSUPPORTED_FILE',
    11=> 'UPLOAD_ERR_TMP_FAILED',
    12=> 'UPLOAD_ERR_MAX_SIZE'
);
$config['errorCodesMsg'] = array(
	'UPLOAD_ERR_NO_FILE_NAME' => 'File name is empty.',
	'UPLOAD_ERR_UNSUPPORTED_FILE' => 'Only PDF and DOC files are allowed for uploading.',
	'UPLOAD_ERR_TMP_FAILED' => 'File upload error.',
	'UPLOAD_ERR_MAX_SIZE' => 'Please upload a file less than 25 MB in size.',
);

define("ExamBaiscKey", "examV1:");
define("GroupKey", "GroupV1:");
define("ExamContentKey", "examContentV1:");
define("ExamAMPContentKey", "examAmpContentV1:");
define("ExamBasicByName","examByNameV");
