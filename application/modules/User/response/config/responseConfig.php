<?php

	$config = array();

	global $responseGrades;
	$responseGrades = array(
	'Applied'                                    => -1,
	'Applying'                                   => -1,
	'Clicked_on_SMS'                             => -1,
	'Online_Application_Started'                 => -1,
	'TakenAdmission'                             => -1,
	'CollegePredictor'                           => 1,
	'RankPredictor'                              => 1,
	'rate_my_chances'                            => 1,
	'rate_my_chances_sa_mobile'                  => 1,
	'MOB_Category_Shortlist'                     => 2,
	'MOB_Course_Shortlist'                       => 2,
	'mobileComparePage'                          => 2,
	'mobileRankingPage'                          => 2,
	'ND_ALL_COURSES_PAGE'                        => 2,
	'ND_AllContentPage_Admission'                => 2,
	'ND_AllContentPage_Articles'                 => 2,
	'ND_AllContentPage_Questions'                => 2,
	'ND_AllContentPage_Reviews'                  => 2,
	'ND_CareerCompass_Shortlist'                 => 2,
	'ND_category_shortlist'                      => 2,
	'ND_CategoryReco_shortlist'                  => 2,
	'ND_Compare_shortlist'                       => 2,
	'ND_course_shortlist'                        => 2,
	'ND_InstituteDetailPage'                     => 2,
	'ND_myshortlist_shortlist'                   => 2,
	'ND_NaukriTool'                              => 2,
	'ND_ranking_shortlist'                       => 2,
	'ND_SERP_shortlist'                          => 2,
	'NM_ALL_COURSES_shortlist'                   => 2,
	'NM_CareerCompass_shortlist'                 => 2,
	'NM_category_shortlist'                      => 2,
	'NM_course_shortlist'                        => 2,
	'NM_CourseListing'                           => 2,
	'NM_InstituteDetailPage'                     => 2,
	'NM_myshortlist_shortlist'                   => 2,
	'NM_SERP_shortlist'                          => 2,
	'NM_shortlist_REB'                           => 2,
	'rankingPage'                                => 2,
	'User_ShortListed_Course'                    => 2,
	'User_ShortListed_Course_sa_mobile'          => 2,
	'Compare_Email'                              => 3,
	'CP_Request_Callback'                        => 3,
	'CP_Request_Callback_sa_mobile'              => 3,
	'MOB_COMPARE_EMAIL'                          => 3,
	'Request_Callback'                           => 3,
	'Request_Callback_sa_mobile'                 => 3,
	'Shortlist_Request_Callback'                 => 3,
	'Shortlist_Request_Callback_sa_mobile'       => 3,
	'Get_Admission_Details' 			     	 => 3,
	'Rate_My_Chance'							 => 3,
	'Request_Call_Back'							 => 3,
	'download_cutoff_details'					 => 3,
	'Asked_Question_On_All_Question'             => 4,
	'Asked_Question_On_AllContent_PC_admission'  => 4,
	'Asked_Question_On_AllContent_PC_reviews'    => 4,
	'Asked_Question_On_CCHome'                   => 4,
	'Asked_Question_On_CCHome_MOB'               => 4,
	'Asked_Question_On_Compare_MOB'              => 4,
	'Asked_Question_On_Institute_Questions_Page' => 4,
	'Asked_Question_On_Listing'                  => 4,
	'Asked_Question_On_Listing_MOB'              => 4,
	'COMPARE_AskQuestion'                        => 4,
	'COMPARE_EBrochure'                          => 4,
	'D_MS_Ask'                                   => 4,
	'GetFreeAlert'                               => 4,
	'GetFreeAlert_sa_mobile'                     => 4,
	'LISTING_PAGE_EMAIL_SMS_CONTACT_DETAILS'     => 4,
	'listingContactDetail'                       => 4,
	'MOB_CareerCompass_Ebrochure'                => 4,
	'MOB_COMPARE_EBrochure'                      => 4,
	'MOB_listingContactDetail'                   => 4,
	'brochureCourseSARanking'                    => 5,
	'brochureCourseSARanking_sa_mobile'          => 5,
	'brochureUnivSARanking'                      => 5,
	'brochureUnivSARanking_sa_mobile'            => 5,
	'courseDownloadBrochure'                     => 5,
	'D_MS_Request_e_Brochure'                    => 5,
	'download_brochure_free_course'              => 5,
	'download_brochure_free_course_sa_mobile'    => 5,
	'downloadBrochure'                           => 5,
	'EBrochure_RECO_Layer'                       => 5,
	'MOBILE5_CATEGORY_PAGE'                      => 5,
	'MOBILE5_COLLEGE_PREDICTOR_PAGE'             => 5,
	'MOBILE5_COURSE_DETAIL_PAGE'                 => 5,
	'MOBILE5_COURSE_DETAIL_PAGE_OTHER'           => 5,
	'MOBILE5_INSTITUTE_DETAIL_PAGE'              => 5,
	'MOBILE5_RANK_PREDICTOR_PAGE'                => 5,
	'MOBILE5_SEARCH_PAGE'                        => 5,
	'MOBILE5_SHORTLIST_PAGE'                     => 5,
	'MOBILE5_SIMILAR_COURSE_DETAIL_PAGE'         => 5,
	'MOBILE5_SIMILAR_INSTITUTE_DETAIL_PAGE'      => 5,
	'mobileCollegePredictorPage'                 => 5,
	'MOBILEHTML5'                                => 5,
	'MOBILEHTML5_GETEB'                          => 5,
	'mobilesite'                                 => 5,
	'mobilesitesearch'                           => 5,
	'ND_CareerCompass_Ebrochure'                 => 5,
	'ND_courseDownloadInternship'                => 5,
	'ND_courseDownloadPlacement'                 => 5,
	'NM_CareerCompass'                           => 5,
	'NM_courseDownloadInternship'                => 5,
	'NM_courseDownloadPlacement'                 => 5,
	'OF_Request_E-Brochure'                      => 5,
	'RANKING_MOB_ReqEbrochure'                   => 5,
	'RANKING_PAGE_REQUEST_EBROCHURE'             => 5,
	'Request_E-Brochure'                         => 5,
	'Request_E-Brochure_sa_mobile'               => 5,
	'SEARCH_REQUEST_EBROCHURE'                   => 5,
	'Wanted_to_talk'                             => 5,
	'FBDownloadBrochure'						 =>	5,
	'Mailer_Promotion_Tuple'					 => 5,
	'request_salaryData'                         => 6,
	'Read_Course_Review'						 => 6,
	'Download_CollegeList'						 => 6,
	'Download_Top_Reviews'						 => 6,
	'Download_CourseList'						 => 6,
	'Get_Free_Counselling'						 => 6,
	'Download_Top_Questions'					 => 6,
	'Get_Scholarship_Details'					 => 6,
	'Download_Top_Articles'						 => 6,
	'COMPARE_VIEWED'                             => 7,
	'CoursePage_Reco'                            => 7,
	'CP_MOB_Reco_ReqEbrochure'                   => 7,
	'CP_Reco_divLayer'                           => 7,
	'CP_Reco_popupLayer'                         => 7,
	'CP_Reco_ReqEbrochure'                       => 7,
	'CP_Reco_ReqEbrochure_sa_mobile'             => 7,
	'LP_MOB_Reco_ReqEbrochure'                   => 7,
	'LP_Reco_ ReqEbrochure'                      => 7,
	'LP_Reco_AlsoviewLayer'                      => 7,
	'LP_Reco_AlsoviewLayer_sa_mobile'            => 7,
	'LP_Reco_ReqEbrochure'                       => 7,
	'LP_Reco_ReqEbrochure_sa_mobile'             => 7,
	'LP_Reco_ShowRecoLayer'                      => 7,
	'LP_Reco_SimilarInstiLayer'                  => 7,
	'LP_Reco_SimilarInstiLayer_sa_mobile'        => 7,
	'MOB_COMPARE_VIEWED'                         => 7,
	'RANKING_MOB_Reco_ReqEbrochure'              => 7,
	'reco_after_category'                        => 7,
	'reco_also_view_layer_sa_mobile'             => 7,
	'reco_widget_mailer'                         => 7,
	'reco_widget_mailer_national'                => 7,
	'reco_widget_mailer_national_mobile'         => 7,
	'reco_widget_mailer_sa_mobile'               => 7,
	'RP_Reco_AlsoviewLayer'                      => 7,
	'RP_Reco_AlsoviewLayer_sa_mobile'            => 7,
	'SEARCH_MOB_Reco_ReqEbrochure'               => 7,
	'Shortlist_Page_Reco_ReqEbrochure'           => 7,
	'Shortlist_Page_Reco_ReqEbrochure_sa_mobile' => 7,
	'similar_institute_deb'                      => 7,
	'LP_AdmissionGuide'                          => 8,
	'LP_AdmissionGuide_sa_mobile'                => 8,
	'LP_EligibilityExam'                         => 8,
	'LP_EligibilityExam_sa_mobile'               => 8,
	'ND_AdmissionPage'                           => 8,
	'NM_AdmissionPage'                           => 8,
	'checked_fee_details'						 => 8,
	'MOB_Viewed'                                 => 10,
	'Mob_Viewed_Listing_Pre_Reg'                 => 10,
	'mobile_viewedListing'                       => 10,
	'Viewed_Listing'                             => 10,
	'Viewed_Listing_Pre_Reg'                     => 10,
	'Viewed_Listing_Pre_Reg_sa_mobile'           => 10,
	'Viewed_Listing_sa_mobile'                   => 10,
	'Institute_Viewed'                           => 11,
	'MOB_Institute_Viewed'                       => 11,
	'Mailer_Alert'                               => 19,
	'mailerAlert'                                => 19,
	'MOB_mailerAlert'                            => 19,	
	'other'                                      => 9999
	);

	global $examResponseGrades;
	$examResponseGrades = array(
		'exam_download_guide'              => 1,
		'exam_download_sample_paper'       => 1,
		'exam_download_application_form'   => 1,
		'exam_download_prep_guide'         => 1,
		'exam_apply_online'                => 1,
		'exam_ask_question'                => 2,
		'exam_subscribe_to_latest_updates' => 2,
		'exam_viewed_response'             => 3
	);

	/* $responseGrades = array(
        'Applied' => -1,
        'Applying' => -1,
        'TakenAdmission' => -1,
        'Clicked_on_SMS' => -1,
        'Online_Application_Started' => -1,
        'CollegePredictor' => 1,
        'RankPredictor' => 1,
        'rate_my_chances' => 1,
        'rate_my_chances_sa_mobile' => 1,
		'User_ShortListed_Course' => 2,
		'User_ShortListed_Course_sa_mobile' => 2,
		'MOB_Course_Shortlist' => 2,
		'ND_myshortlist_shortlist' => 2,
		'ND_category_shortlist' => 2,
		'ND_ranking_shortlist' => 2,
		'ND_course_shortlist' => 2,
        'NM_course_shortlist' =>2,
        'MOB_Category_Shortlist' => 2,
		'ND_CategoryReco_shortlist' => 2,
		'Compare_Email' => 3,
		'MOB_COMPARE_EMAIL' => 3,
		'Request_Callback' => 3, 
		'Request_Callback_sa_mobile' => 3, 
		'CP_Request_Callback' => 3, 
		'CP_Request_Callback_sa_mobile' => 3,
		'Shortlist_Request_Callback' => 3,
		'Shortlist_Request_Callback_sa_mobile' => 3,
		'Asked_Question_On_Listing' => 4,
		'Asked_Question_On_Listing_MOB' => 4,
		'Asked_Question_On_CCHome' => 4,
		'Asked_Question_On_CCHome_MOB' => 4,
		'D_MS_Ask' => 4,
		'COMPARE_AskQuestion' => 4,
		'COMPARE_EBrochure' => 4,
		'MOB_COMPARE_EBrochure' => 4,
		'GetFreeAlert' => 4,
		'GetFreeAlert_sa_mobile' => 4,
		'LISTING_PAGE_EMAIL_SMS_CONTACT_DETAILS' => 4,
		'MOB_listingContactDetail' => 4,
		'listingContactDetail' => 4,
		'MOB_CareerCompass_Ebrochure'=> 4,
		'download_brochure_free_course' => 5,
		'download_brochure_free_course_sa_mobile' => 5,
		'MOBILEHTML5' => 5,
		'MOBILE5_CATEGORY_PAGE' => 5,
		'MOBILE5_INSTITUTE_DETAIL_PAGE' => 5,
		'MOBILE5_SIMILAR_INSTITUTE_DETAIL_PAGE' => 5,
		'MOBILE5_SIMILAR_COURSE_DETAIL_PAGE' => 5,
		'MOBILE5_COURSE_DETAIL_PAGE' => 5,
		'MOBILE5_COURSE_DETAIL_PAGE_OTHER' => 5,
		'MOBILE5_SEARCH_PAGE' => 5,
		'MOBILE5_COLLEGE_PREDICTOR_PAGE' => 5,
		'MOBILE5_RANK_PREDICTOR_PAGE' => 5,
		'MOBILE5_SHORTLIST_PAGE' => 5,
		'MOBILEHTML5_GETEB' => 5,
		'RANKING_MOB_ReqEbrochure' => 5,
		'mobilesite' => 5,
		'mobilesitesearch' => 5,
		'RANKING_PAGE_REQUEST_EBROCHURE' => 5,
		'Request_E-Brochure' => 5,
        'Wanted_to_talk' => 5,
		'Request_E-Brochure_sa_mobile' => 5,
		'D_MS_Request_e_Brochure' => 5,
		'OF_Request_E-Brochure' => 5,
		'brochureUnivSARanking' => 5,
		'brochureUnivSARanking_sa_mobile' => 5,
		'brochureCourseSARanking' => 5,
		'brochureCourseSARanking_sa_mobile' => 5,
		'SEARCH_REQUEST_EBROCHURE' => 5,
		'request_salaryData' => 6,
		'CoursePage_Reco' => 7,
		'CP_MOB_Reco_ReqEbrochure' => 7,
		'CP_Reco_divLayer' => 7,
		'CP_Reco_popupLayer' => 7,
		'CP_Reco_ReqEbrochure' => 7,
		'CP_Reco_ReqEbrochure_sa_mobile' => 7,
		'LP_MOB_Reco_ReqEbrochure' => 7,
		'LP_Reco_ ReqEbrochure' => 7,
		'LP_Reco_AlsoviewLayer' => 7,
		'LP_Reco_AlsoviewLayer_sa_mobile' => 7,
		'LP_Reco_ReqEbrochure' => 7,
		'LP_Reco_ReqEbrochure_sa_mobile' => 7,
		'LP_Reco_ShowRecoLayer' => 7,
		'LP_Reco_SimilarInstiLayer' => 7,
		'LP_Reco_SimilarInstiLayer_sa_mobile' => 7,
		'reco_also_view_layer_sa_mobile'=>7,
		'RP_Reco_AlsoviewLayer' => 7,
		'RP_Reco_AlsoviewLayer_sa_mobile' => 7,
		'RANKING_MOB_Reco_ReqEbrochure' => 7,
		'reco_after_category' => 7,
		'reco_widget_mailer' => 7,
		'reco_widget_mailer_sa_mobile' => 7,
		'reco_widget_mailer_national' => 7,
		'reco_widget_mailer_nationa_mobile' => 7,
		'SEARCH_MOB_Reco_ReqEbrochure' => 7,
		'similar_institute_deb' => 7,
		'COMPARE_VIEWED' => 7,
        'MOB_COMPARE_VIEWED' => 7,
		'Shortlist_Page_Reco_ReqEbrochure' => 7,
		'Shortlist_Page_Reco_ReqEbrochure_sa_mobile' => 7,
		'LP_AdmissionGuide' => 8,
		'LP_AdmissionGuide_sa_mobile' => 8,
		'LP_EligibilityExam' => 8,
		'LP_EligibilityExam_sa_mobile' => 8,
		'Viewed_Listing' => 10,
		'Viewed_Listing_sa_mobile' => 10,
		'Viewed_Listing_Pre_Reg' => 10,
		'Viewed_Listing_Pre_Reg_sa_mobile' => 10,
        'Mob_Viewed_Listing_Pre_Reg' => 10,
		'mobile_viewedListing' => 11,
		'Institute_Viewed' => 12,
		'Mailer_Alert' => 19,
		'mailerAlert' => 19,
		'other'=>9999
	); */

	global $action_type_array;
	$action_type_array = array('NATIONAL_PAGE',5,4,2,6,'RANKING_PAGE_REQUEST_EBROCHURE','SEARCH_REQUEST_EBROCHURE','LP_Reco_SimilarInstiLayer','LP_Reco_AlsoviewLayer','LP_Reco_ShowRecoLayer','CP_Reco_popupLayer','CP_Reco_divLayer','CoursePage_Reco',"LISTING_PAGE_EMAIL_SMS_CONTACT_DETAILS", "Asked_Question_On_Listing","Asked_Question_On_Listing_MOB","D_MS_Ask","similar_institute_deb","Viewed_Listing_Pre_Reg","User_ShortListed_Course","MOB_Course_Shortlist","COMPARE_VIEWED","COMPARE_AskQuestion","COMPARE_EBrochure","Compare_Email",'MOB_COMPARE_VIEWED','D_MS_Request_e_Brochure', 'MOB_CareerCompass_Shortlist','Asked_Question_On_CCHome','Asked_Question_On_CCHome_MOB', 'ND_SRP_Request_E_Brochure');
	
	$config['examCsvFields'] = array(
									'name'         => 'Name',
									'firstname'    => 'First Name',
									'lastname'     => 'Last Name',
									'exam'         => 'Exam Name',
									'groupName'    => 'Course',
									// 'city'         => 'City',
									// 'locality'     => 'Locality',
									'CurrentCity'  => 'Current Location',
									'localityName' => 'Current Locality',
									'submit_date'  => 'Response Date',
									'action'       => 'Source',
									'email'        => 'Email',
									'IsdCode'      => 'ISD Code',
									'mobile'       => 'Mobile',
									'isNDNC'       => 'Is in NDNC List',
									'exams_taken'  => 'Exams Taken',
                					'experience'   => 'Work Experience'
									);
global $shortlistTrackingKeys;

$shortlistTrackingKeys = array(931,936,950,954,971,997,1009,1014,1018,1022,1026,1032,1035,1042,1047,1065,1070,1075,1080,1086,1089,1093,1097,1100,1101,1105,1108,1109,1135,1137,1139,1142,1151,1156,1163,1169,1176,1178,1185,1192,1197,1208,1224,1225,1231,1236,1240,1267,1270,1278,1282,1344,1345,1346,1347,215,216,218,221,235,245,246,247,251,252,253,256,344,346,512,188,203,204,271,298,299,300,306,309,311,323,327,336,448,681,1501,1368,1374,1457,1459,1493,1503,1567,1563,1581,1609,1615);

global $compareTrackingKeys;

$compareTrackingKeys = array(930,935,952,956,970,994,996,1001,1002,1010,1012,1016,1020,1024,1031,1034,1036,1039,1041,1045,1064,1069,1074,1076,1082,1088,1090,1094,1095,1098,1106,1140,1143,1146,1148,1150,1153,1155,1159,1162,1166,1171,1177,1179,1182,1187,1193,1199,1223,1227,1230,1235,1241,1269,1272,1280,1284,1348,1349,1350,1351,666,187,272,301,302,303,305,324,328,337,505,506,507,508,509,510,511,521,612,613,616,618,619,622,623,624,625,628,650,651,652,665,682,1501,1491,1455,1453,1375,1370,1358,1357,1579,1561,1565,1607,1613);

$config['clientInfo'] = array(
						'course'=>array(
							'248743'=>	array(
								'clientShikshaFieldsMapping' => array(
															'custom'=>array(
																'Name'			=>	array('type'=>'text','value'=>'firstname+lastname'),
																'Email'			=>	array('type'=>'text','value'=>'email'),
																'Button' =>  array('type'=>'button','value'=>'0'),
																'ul_dial_codeMobile'=>array('type'=>'ul','value'=>'isdCode'),
																'Mobile'		=>	array('type'=>'text','value'=>'mobile'),
																'StateId'		=>	array('type'=>'select','value'=>'state_name'),
																'CityId'		=>	array('type'=>'select','value'=>'city_name')
															),
															'default'=>array(
																'CaptchaImage'	=>	array('type'=>'captchaImage','nationalvalue'=>RESPONSE_PORT_NATIONAL_COORDINATES,'abroadvalue'=>'840,300,950,342'),
																'Captcha'		=>	array('type'=>'captchaText','value'=>''),
																'CourseId'		=>	array('type'=>'select','value'=>'B.Tech. (CSE/ECE/Mech)')
															)
								),
								'clientData'=> array(
									'vendor'			=>  'NPF',
									'form_pattern' 		=>  array('isFrame' => 'yes', 'frameNumber'=>'2'),
									'error_pattern' 	=>  array('type'=>'class', 'value'=>'help-block'),
									'success_pattern'	=>  array('type'=>'tag', 'value'=>'p', 'serialNumber'=>'4','successMessage'=>'Thank you!'),
									'captcha_error'		=>  array('errorIndex'=>6, 'captchaErrorText'=>'Enter exact characters shown in image.'),
									'client_url' 		=> 'https://admissions.bennett.edu.in/Btech19/?utm_source=Shiksha&utm_medium=B.Tech&utm_campaign=CMSITE1'
								)
							),

							'248740'=>	array(
								'clientShikshaFieldsMapping' => array(
															'custom'=>array(
																'Name'			=>	array('type'=>'text','value'=>'firstname+lastname'),
																'Email'			=>	array('type'=>'text','value'=>'email'),
																'Button' =>  array('type'=>'button','value'=>'0'),
																'ul_dial_codeMobile'=>array('type'=>'ul','value'=>'isdCode'),
																'Mobile'		=>	array('type'=>'text','value'=>'mobile'),
																'StateId'		=>	array('type'=>'select','value'=>'state_name'),
																'CityId'		=>	array('type'=>'select','value'=>'city_name')
															),
															'default'=>array(
																'CaptchaImage'	=>	array('type'=>'captchaImage','nationalvalue'=>RESPONSE_PORT_NATIONAL_COORDINATES,'abroadvalue'=>'840,300,950,342'),
																'Captcha'		=>	array('type'=>'captchaText','value'=>''),
																'CourseId'		=>	array('type'=>'select','value'=>'B.Tech. (CSE/ECE/Mech)')
															)
								),
								'clientData'=> array(
									'vendor'			=>  'NPF',
									'form_pattern' 		=>  array('isFrame' => 'yes', 'frameNumber'=>'2'),
									'error_pattern' 	=>  array('type'=>'class', 'value'=>'help-block'),
									'success_pattern'	=>  array('type'=>'tag', 'value'=>'p', 'serialNumber'=>'4','successMessage'=>'Thank you!'),
									'captcha_error'		=>  array('errorIndex'=>6, 'captchaErrorText'=>'Enter exact characters shown in image.'),
									'client_url' 		=> 'https://admissions.bennett.edu.in/Btech19/?utm_source=Shiksha&utm_medium=B.Tech&utm_campaign=CMSITE1'
								)
							),

							'264325'=>	array(
								'clientShikshaFieldsMapping' => array(
															'custom'=>array(
																'Name'			=>	array('type'=>'text','value'=>'firstname+lastname'),
																'Email'			=>	array('type'=>'text','value'=>'email'),
																'Button' =>  array('type'=>'button','value'=>'0'),
																'ul_dial_codeMobile'=>array('type'=>'ul','value'=>'isdCode'),
																'Mobile'		=>	array('type'=>'text','value'=>'mobile'),
																'StateId'		=>	array('type'=>'select','value'=>'state_name'),
																'CityId'		=>	array('type'=>'select','value'=>'city_name')
															),
															'default'=>array(
																'CaptchaImage'	=>	array('type'=>'captchaImage','nationalvalue'=>RESPONSE_PORT_NATIONAL_COORDINATES,'abroadvalue'=>'840,300,950,342'),
																'Captcha'		=>	array('type'=>'captchaText','value'=>''),
																'CourseId'		=>	array('type'=>'select','value'=>'B.Tech. (Biotechnology)')
															)
								),
								'clientData'=> array(
									'vendor'			=>  'NPF',
									'form_pattern' 		=>  array('isFrame' => 'yes', 'frameNumber'=>'2'),
									'error_pattern' 	=>  array('type'=>'class', 'value'=>'help-block'),
									'success_pattern'	=>  array('type'=>'tag', 'value'=>'p', 'serialNumber'=>'4','successMessage'=>'Thank you!'),
									'captcha_error'		=>  array('errorIndex'=>6, 'captchaErrorText'=>'Enter exact characters shown in image.'),
									'client_url' 		=> 'https://admissions.bennett.edu.in/Btech19/?utm_source=Shiksha&utm_medium=B.Tech&utm_campaign=CMSITE1'
								)
							),

							'264284'=>	array(
								'clientShikshaFieldsMapping' => array(
															'custom'=>array(
																'Name'			=>	array('type'=>'text','value'=>'firstname+lastname'),
																'Email'			=>	array('type'=>'text','value'=>'email'),
																'Button' =>  array('type'=>'button','value'=>'0'),
																'ul_dial_codeMobile'=>array('type'=>'ul','value'=>'isdCode'),
																'Mobile'		=>	array('type'=>'text','value'=>'mobile'),
																'StateId'		=>	array('type'=>'select','value'=>'state_name'),
																'CityId'		=>	array('type'=>'select','value'=>'city_name')
															),
															'default'=>array(
																'CaptchaImage'	=>	array('type'=>'captchaImage','nationalvalue'=>RESPONSE_PORT_NATIONAL_COORDINATES,'abroadvalue'=>'840,300,950,342'),
																'Captcha'		=>	array('type'=>'captchaText','value'=>''),
																'CourseId'		=>	array('type'=>'select','value'=>'B.Tech. (CSE/ECE/Mech)')
															)
								),								
								'clientData'=> array(
									'vendor'			=>  'NPF',
									'form_pattern' 		=>  array('isFrame' => 'yes', 'frameNumber'=>'2'),
									'error_pattern' 	=>  array('type'=>'class', 'value'=>'help-block'),
									'success_pattern'	=>  array('type'=>'tag', 'value'=>'p', 'serialNumber'=>'4','successMessage'=>'Thank you!'),
									'captcha_error'		=>  array('errorIndex'=>6, 'captchaErrorText'=>'Enter exact characters shown in image.'),
									'client_url' 		=> 'https://admissions.bennett.edu.in/Btech19/?utm_source=Shiksha&utm_medium=B.Tech&utm_campaign=CMSITE1'
								)
							),

							'248742'=>	array(
								'clientShikshaFieldsMapping' => array(
															'custom'=>array(
																'Name'			=>	array('type'=>'text','value'=>'firstname+lastname'),
																'Email'			=>	array('type'=>'text','value'=>'email'),
																'Button' =>  array('type'=>'button','value'=>'0'),
																'ul_dial_codeMobile'=>array('type'=>'ul','value'=>'isdCode'),
																'Mobile'		=>	array('type'=>'text','value'=>'mobile'),
																'StateId'		=>	array('type'=>'select','value'=>'state_name'),
																'CityId'		=>	array('type'=>'select','value'=>'city_name')
															),
															'default'=>array(
																'CaptchaImage'	=>	array('type'=>'captchaImage','nationalvalue'=>RESPONSE_PORT_NATIONAL_COORDINATES,'abroadvalue'=>'840,300,950,342'),
																'Captcha'		=>	array('type'=>'captchaText','value'=>''),
																'CourseId'		=>	array('type'=>'select','value'=>'B.Tech. (CSE/ECE/Mech)')
															)
								),
								'clientData'=> array(
									'vendor'			=>  'NPF',
									'form_pattern' 		=>  array('isFrame' => 'yes', 'frameNumber'=>'2'),
									'error_pattern' 	=>  array('type'=>'class', 'value'=>'help-block'),
									'success_pattern'	=>  array('type'=>'tag', 'value'=>'p', 'serialNumber'=>'4','successMessage'=>'Thank you!'),
									'captcha_error'		=>  array('errorIndex'=>6, 'captchaErrorText'=>'Enter exact characters shown in image.'),
									'client_url' 		=> 'https://admissions.bennett.edu.in/Btech19/?utm_source=Shiksha&utm_medium=B.Tech&utm_campaign=CMSITE1'
								)
							),

							'273765'=>	array(
								'clientShikshaFieldsMapping' => array(
															'custom'=>array(
																'Name'			=>	array('type'=>'text','value'=>'firstname+lastname'),
																'Email'			=>	array('type'=>'text','value'=>'email'),
																'Button' =>  array('type'=>'button','value'=>'0'),
																'ul_dial_codeMobile'=>array('type'=>'ul','value'=>'isdCode'),
																'Mobile'		=>	array('type'=>'text','value'=>'mobile'),
																'StateId'		=>	array('type'=>'select','value'=>'state_name'),
																'CityId'		=>	array('type'=>'select','value'=>'city_name')
															),
															'default'=>array(
																'CaptchaImage'	=>	array('type'=>'captchaImage','nationalvalue'=>RESPONSE_PORT_NATIONAL_COORDINATES,'abroadvalue'=>'840,300,950,342'),
																'Captcha'		=>	array('type'=>'captchaText','value'=>''),
																'CourseId'		=>	array('type'=>'select','value'=>'B.Tech. (CSE/ECE/Mech)')
															)
								),
								'clientData'=> array(
									'vendor'			=>  'NPF',
									'form_pattern' 		=>  array('isFrame' => 'yes', 'frameNumber'=>'2'),
									'error_pattern' 	=>  array('type'=>'class', 'value'=>'help-block'),
									'success_pattern'	=>  array('type'=>'tag', 'value'=>'p', 'serialNumber'=>'4','successMessage'=>'Thank you!'),
									'captcha_error'		=>  array('errorIndex'=>6, 'captchaErrorText'=>'Enter exact characters shown in image.'),
									'client_url' 		=> 'https://admissions.bennett.edu.in/Btech19/?utm_source=Shiksha&utm_medium=B.Tech&utm_campaign=CMSITE1'
								)
							),

							'273764'=>	array(
								'clientShikshaFieldsMapping' => array(
															'custom'=>array(
																'Name'			=>	array('type'=>'text','value'=>'firstname+lastname'),
																'Email'			=>	array('type'=>'text','value'=>'email'),
																'Button' =>  array('type'=>'button','value'=>'0'),
																'ul_dial_codeMobile'=>array('type'=>'ul','value'=>'isdCode'),
																'Mobile'		=>	array('type'=>'text','value'=>'mobile'),
																'StateId'		=>	array('type'=>'select','value'=>'state_name'),
																'CityId'		=>	array('type'=>'select','value'=>'city_name')
															),
															'default'=>array(
																'CaptchaImage'	=>	array('type'=>'captchaImage','nationalvalue'=>'840,335,950,367','abroadvalue'=>'840,300,950,342'),
																'Captcha'		=>	array('type'=>'captchaText','value'=>''),
																'CourseId'		=>	array('type'=>'select','value'=>'BA-Journalism and Mass Communication (English)')
															)
								),
								'clientData'=> array(
									'vendor'			=>  'NPF',
									'form_pattern' 		=>  array('isFrame' => 'yes', 'frameNumber'=>'2'),
									'error_pattern' 	=>  array('type'=>'class', 'value'=>'help-block'),
									'success_pattern'	=>  array('type'=>'tag', 'value'=>'p', 'serialNumber'=>'4','successMessage'=>'Thank you!'),
									'captcha_error'		=>  array('errorIndex'=>6, 'captchaErrorText'=>'Enter exact characters shown in image.'),
									'client_url' 		=> 'https://admissions.bennett.edu.in/MassCom/?utm_source=Shiksha&utm_medium=MassComm&utm_campaign=CMSITE1'
								)
							),

							'299856'=>	array(
								'clientShikshaFieldsMapping' => array(
															'custom'=>array(
																'Name'			=>	array('type'=>'text','value'=>'firstname+lastname'),
																'Email'			=>	array('type'=>'text','value'=>'email'),
																'Button' =>  array('type'=>'button','value'=>'0'),
																'ul_dial_codeMobile'=>array('type'=>'ul','value'=>'isdCode'),
																'Mobile'		=>	array('type'=>'text','value'=>'mobile'),
																'StateId'		=>	array('type'=>'select','value'=>'state_name'),
																'CityId'		=>	array('type'=>'select','value'=>'city_name')
															),
															'default'=>array(
																'CaptchaImage'	=>	array('type'=>'captchaImage','nationalvalue'=>'840,365,950,397','abroadvalue'=>'840,310,950,342'),
																'Captcha'		=>	array('type'=>'captchaText','value'=>''),
																'CourseId'		=>	array('type'=>'select','value'=>'Integrated BA-LLB (Hons)')
															)
								),
								'clientData'=> array(
									'vendor'			=>  'NPF',
									'form_pattern' 		=>  array('isFrame' => 'yes', 'frameNumber'=>'2'),
									'error_pattern' 	=>  array('type'=>'class', 'value'=>'help-block'),
									'success_pattern'	=>  array('type'=>'tag', 'value'=>'p', 'serialNumber'=>'4','successMessage'=>'Thank you!'),
									'captcha_error'		=>  array('errorIndex'=>6, 'captchaErrorText'=>'Enter exact characters shown in image.'),
									'client_url' 		=> 'https://admissions.bennett.edu.in/BBA-LLB/?utm_source=Shiksha&utm_medium=Law&utm_campaign=CMSITE1'
								)
							),

							'264920'=>	array(
								'clientShikshaFieldsMapping' => array(
															'custom'=>array(
																'Name'			=>	array('type'=>'text','value'=>'firstname+lastname'),
																'Email'			=>	array('type'=>'text','value'=>'email'),
																'Button' =>  array('type'=>'button','value'=>'0'),
																'ul_dial_codeMobile'=>array('type'=>'ul','value'=>'isdCode'),
																'Mobile'		=>	array('type'=>'text','value'=>'mobile'),
																'StateId'		=>	array('type'=>'select','value'=>'state_name'),
																'CityId'		=>	array('type'=>'select','value'=>'city_name')
															),
															'default'=>array(
																'CaptchaImage'	=>	array('type'=>'captchaImage','nationalvalue'=>'840,365,950,397','abroadvalue'=>'840,310,950,342'),
																'Captcha'		=>	array('type'=>'captchaText','value'=>''),
																'CourseId'		=>	array('type'=>'select','value'=>'Integrated BBA-LLB (Hons)')
															)
								),
								'clientData'=> array(
									'vendor'			=>  'NPF',
									'form_pattern' 		=>  array('isFrame' => 'yes', 'frameNumber'=>'2'),
									'error_pattern' 	=>  array('type'=>'class', 'value'=>'help-block'),
									'success_pattern'	=>  array('type'=>'tag', 'value'=>'p', 'serialNumber'=>'4','successMessage'=>'Thank you!'),
									'captcha_error'		=>  array('errorIndex'=>6, 'captchaErrorText'=>'Enter exact characters shown in image.'),
									'client_url' 		=> 'https://admissions.bennett.edu.in/BBA-LLB/?utm_source=Shiksha&utm_medium=Law&utm_campaign=CMSITE1'
								)
							),

							'264201'=>	array(
								'clientShikshaFieldsMapping' => array(
															'custom'=>array(
																'Name'			=>	array('type'=>'text','value'=>'firstname+lastname'),
																'Email'			=>	array('type'=>'text','value'=>'email'),
																'Button' =>  array('type'=>'button','value'=>'0'),
																'ul_dial_codeMobile'=>array('type'=>'ul','value'=>'isdCode'),
																'Mobile'		=>	array('type'=>'text','value'=>'mobile'),
																'StateId'		=>	array('type'=>'select','value'=>'state_name'),
																'CityId'		=>	array('type'=>'select','value'=>'city_name')
															),
															'default'=>array(
																'CaptchaImage'	=>	array('type'=>'captchaImage','nationalvalue'=>'840,325,950,360','abroadvalue'=>'840,285,950,320'),
																'Captcha'		=>	array('type'=>'captchaText','value'=>'')
															)
								),
								'clientData'=> array(
									'vendor'			=>  'NPF',
									'form_pattern' 		=>  array('isFrame' => 'yes', 'frameNumber'=>'2'),
									'error_pattern' 	=>  array('type'=>'class', 'value'=>'help-block'),
									'success_pattern'	=>  array('type'=>'tag', 'value'=>'p', 'serialNumber'=>'4','successMessage'=>'Thank you!'),
									'captcha_error'		=>  array('errorIndex'=>7, 'captchaErrorText'=>'Enter exact characters shown in image.'),
									'client_url' 		=> 'https://admissions.bennett.edu.in/BBA2019/?utm_source=Shiksha&utm_medium=BBA&utm_campaign=CMSITE1'
								)
							)
						),

						'exam' => array()
							/* '113'=>	array(
								'clientShikshaFieldsMapping' => array(
															'custom'=>array(
																'Name'			=>	array('type'=>'text','value'=>'firstname+lastname'),
																'Email'			=>	array('type'=>'text','value'=>'email'),
																'Button' =>  array('type'=>'button','value'=>'0'),
																'ul_dial_codeMobile'=>array('type'=>'ul','value'=>'isdCode'),
																'Mobile'		=>	array('type'=>'text','value'=>'mobile'),
																'StateId'		=>	array('type'=>'select','value'=>'state_name'),
																'CityId'		=>	array('type'=>'select','value'=>'city_name')
															),
															'default'=>array(
																'CaptchaImage'	=>	array('type'=>'captchaImage','nationalvalue'=>RESPONSE_PORT_NATIONAL_COORDINATES,'abroadvalue'=>'840,300,950,342'),
																'Captcha'		=>	array('type'=>'captchaText','value'=>''),
																'CourseId'		=>	array('type'=>'select','value'=>'B.Tech. (CSE/ECE/Mech)')
															)
								),
								'clientData'=> array(
									'vendor'			=>  'NPF',
									'form_pattern' 		=>  array('isFrame' => 'yes', 'frameNumber'=>'2'),
									'error_pattern' 	=>  array('type'=>'class', 'value'=>'help-block'),
									'success_pattern'	=>  array('type'=>'tag', 'value'=>'p', 'serialNumber'=>'4','successMessage'=>'Thank you!'),
									'captcha_error'		=>  array('errorIndex'=>6, 'captchaErrorText'=>'Enter exact characters shown in image.'),
									'client_url' 		=> 'https://admissions.bennett.edu.in/Btech19/?utm_source=Shiksha&utm_medium=B.Tech&utm_campaign=CMSITE1'
								),
								'usersAllowedFromCities' => array(
													702=>702,
													74 => 74,
													138=>138,
													87 => 87,
													64 => 64,
													171=>171,
													130=>130,
													174=>174,
													161=>161,
													151=>151,
													213=>213,
													209=>209,
													278=>278,
													30 => 30,
													84 => 84,
													95 => 95,
													1616=>1616
												)
							)
						) */
					);

$config['viewedActionTypes'] = array(
								'national'=> array(									
									'course'=>array('COMPARE_VIEWED','MOB_COMPARE_VIEWED','MOB_Viewed','Mob_Viewed_Listing_Pre_Reg','mobile_viewedListing','Viewed_Listing','Viewed_Listing_Pre_Reg'),
									'institute'=>array('Institute_Viewed','MOB_Institute_Viewed'),
									'exam'=>array('exam_viewed_response')
								)
							);

$config['recordsCount'] = 10;
$config['noOfTries'] = 5;
$config['timeDifference'] = 3600; // in seconds
$config['noOfPing'] = 3;
$config['checkService'] = alive;
$config['dataPortService'] = htmlFormSubmit;

$config['viewedActionBucket'] = array('COMPARE_VIEWED' =>'CVR' ,'MOB_COMPARE_VIEWED' =>'CVR' ,'MOB_Viewed' =>'CVR' ,'Mob_Viewed_Listing_Pre_Reg' =>'CVR' ,'mobile_viewedListing' =>'CVR' ,'Viewed_Listing' =>'CVR' ,'Viewed_Listing_Pre_Reg' =>'CVR' ,'Viewed_Listing_Pre_Reg_sa_mobile' =>'CVR','Viewed_Listing_sa_mobile' =>'CVR','Viewed' =>'CVR' ,'Institute_Viewed' =>'IVR' ,'MOB_Institute_Viewed' =>'IVR');

$config['unsubscribe_category'] = array(5);

$config['excludeTrackingIdsForMailer'] = array(3283,3285,3287,3289,3291,3293,3295,3297,3299,3301,3303,3305,3307,3309,3311,3313);

?>
