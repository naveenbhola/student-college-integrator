<?php

/*
 * config related to mobile site 
 *
 */

$small_browsers = array
(
	'2.0 MMP'
	,'240x320'
	,'AvantGo'
	,'BlackBerry'
	,'Blazer'
	,'Cellphone'
	,'Danger'
	,'DoCoMo'
	,'Elaine/3.0'
	,'EudoraWeb'
	,'hiptop'
	,'IEMobile'
	,'KYOCERA/WX310K'
	,'LG/U990'
	,'MIDP-2.0'
	,'MMEF20'
	,'MOT-V'
	,'NetFront'
	,'Newt'
	,'Nintendo Wii'
	,'Nitro'
	,'Nokia'
	,'Opera Mini'
	,'Palm'
	,'Playstation Portable'
	,'portalmmm'
	,'Proxinet'
	,'ProxiNet'
	,'SHARP-TQ-GX10'
	,'Small'
	,'SonyEricsson'
	,'Symbian OS'
	,'SymbianOS'
	,'TS21i-10'
	,'UP.Browser'
	,'UP.Link'
	,'Windows CE'
	,'WinWAP'
);

$config['small_browsers'] = $small_browsers;

$config['mobile_website_home_page_config_1'] = 3;

$config['mobile_website_home_page_config_2'] = 2;

$config['mobile_website_pagination_count']   = 10;

$config['ans_char_count_limit'] = 140;

$config['flag_mobile_rum_tracking'] = 'true';

$config['secret_key'] = 'P@ssw0rdsh!ksh@';

$config['human_typing_time'] = '3';

$config['contactusUrl'] = SHIKSHA_HOME.'/mcommon5/MobileSiteStatic/contactUs';

$config['logoutUrl']    = SHIKSHA_HOME.'/muser5/MobileUser/logout';

$config['loginUrl']     =  SHIKSHA_HOME.'/muser5/MobileUser/login';

$config['registerUrl']  =  SHIKSHA_HOME.'/muser5/MobileUser/register';

$config['termUrl']      =  SHIKSHA_HOME.'/mcommon5/MobileSiteStatic/terms';

$config['aboutusUrl']   =  SHIKSHA_HOME.'/mcommon5/MobileSiteStatic/aboutus';

$config['articleUrl']   =  SHIKSHA_HOME.'/blogs/shikshaBlog/showArticlesList';

$config['policyUrl']    =  SHIKSHA_HOME.'/mcommon5/MobileSiteStatic/privacy';

$config['cookieUrl']    =  SHIKSHA_HOME.'/mcommon5/MobileSiteStatic/cookie';

$config['helplineUrl']  =  SHIKSHA_HOME.'/mcommon5/MobileSiteStatic/studentHelpLine';

$config['communityGuidelineUrl']  =  SHIKSHA_HOME.'/mcommon5/MobileSiteStatic/loadHelpPagesOfApp/communityGuideline/mobileSite';

$config['userPointSystemUrl']  =  SHIKSHA_HOME.'/mcommon5/MobileSiteStatic/loadHelpPagesOfApp/userPointSystem/mobileSite';

$config['viewfullSiteUrl']             =  SHIKSHA_HOME.'/mcommon5/MobileSiteStatic/viewfullsite';

$config['page_count_per_category_page'] = 10;
$config['height_for_auto_pagination_on_category_page'] = 500;

$config['collegePredictorUrl_JEE-Mains']   =  COLLEGE_PREDICTOR_BASE_URL.'/jee-mains-college-predictor';
$config['cutOffPredictorUrl_JEE-Mains']   =  COLLEGE_PREDICTOR_BASE_URL.'/jee-mains-cut-off-predictor';
$config['branchPredictorUrl_JEE-Mains']   =  COLLEGE_PREDICTOR_BASE_URL.'/jee-mains-branch-predictor';

$config['collegePredictorUrl_KCET']   =  COLLEGE_PREDICTOR_BASE_URL.'/kcet-college-predictor';
$config['cutOffPredictorUrl_KCET']   =  COLLEGE_PREDICTOR_BASE_URL.'/kcet-cut-off-predictor';
$config['branchPredictorUrl_KCET']   =  COLLEGE_PREDICTOR_BASE_URL.'/kcet-branch-predictor';

$config['collegePredictorUrl_COMEDK']   =  COLLEGE_PREDICTOR_BASE_URL.'/comedk-college-predictor';
$config['cutOffPredictorUrl_COMEDK']   =  COLLEGE_PREDICTOR_BASE_URL.'/comedk-cut-off-predictor';
$config['branchPredictorUrl_COMEDK']   =  COLLEGE_PREDICTOR_BASE_URL.'/comedk-branch-predictor';

$config['collegePredictorUrl_KEAM']   =  COLLEGE_PREDICTOR_BASE_URL.'/keam-college-predictor';
$config['cutOffPredictorUrl_KEAM']   =  COLLEGE_PREDICTOR_BASE_URL.'/keam-cut-off-predictor';
$config['branchPredictorUrl_KEAM']   =  COLLEGE_PREDICTOR_BASE_URL.'/keam-branch-predictor';

$config['rankPredictorUrl_JEE-Mains']   =  RANK_PREDICTOR_BASE_URL.'/jee-mains-rank-predictor';
$config['rankPredictorUrl_COMEDK']   =  RANK_PREDICTOR_BASE_URL.'/comedk-rank-predictor';

$config['collegePredictorUrl_WBJEE']   =  COLLEGE_PREDICTOR_BASE_URL.'/wbjee-college-predictor';
$config['cutOffPredictorUrl_WBJEE']   =  COLLEGE_PREDICTOR_BASE_URL.'/wbjee-cut-off-predictor';
$config['branchPredictorUrl_WBJEE']   =  COLLEGE_PREDICTOR_BASE_URL.'/wbjee-branch-predictor';

$config['collegePredictorUrl_MPPET']   =  COLLEGE_PREDICTOR_BASE_URL.'/mppet-college-predictor';
$config['cutOffPredictorUrl_MPPET']   =  COLLEGE_PREDICTOR_BASE_URL.'/mppet-cut-off-predictor';
$config['branchPredictorUrl_MPPET']   =  COLLEGE_PREDICTOR_BASE_URL.'/mppet-branch-predictor';

$config['collegePredictorUrl_CGPET']   =  COLLEGE_PREDICTOR_BASE_URL.'/cgpet-college-predictor';
$config['cutOffPredictorUrl_CGPET']   =  COLLEGE_PREDICTOR_BASE_URL.'/cgpet-cut-off-predictor';
$config['branchPredictorUrl_CGPET']   =  COLLEGE_PREDICTOR_BASE_URL.'/cgpet-branch-predictor';

$config['collegePredictorUrl_TNEA']   =  COLLEGE_PREDICTOR_BASE_URL.'/tnea-college-predictor';
$config['cutOffPredictorUrl_TNEA']   =  COLLEGE_PREDICTOR_BASE_URL.'/tnea-cut-off-predictor';
$config['branchPredictorUrl_TNEA']   =  COLLEGE_PREDICTOR_BASE_URL.'/tnea-branch-predictor';

$config['collegePredictorUrl_PTU']   =  COLLEGE_PREDICTOR_BASE_URL.'/ptu-college-predictor';
$config['cutOffPredictorUrl_PTU']   =  COLLEGE_PREDICTOR_BASE_URL.'/ptu-cut-off-predictor';
$config['branchPredictorUrl_PTU']   =  COLLEGE_PREDICTOR_BASE_URL.'/ptu-branch-predictor';

$config['collegePredictorUrl_UPSEE']   =  COLLEGE_PREDICTOR_BASE_URL.'/upsee-college-predictor';
$config['cutOffPredictorUrl_UPSEE']   =  COLLEGE_PREDICTOR_BASE_URL.'/upsee-cut-off-predictor';
$config['branchPredictorUrl_UPSEE']   =  COLLEGE_PREDICTOR_BASE_URL.'/upsee-branch-predictor';

$config['collegePredictorUrl_MHCET']   =  COLLEGE_PREDICTOR_BASE_URL.'/mhcet-college-predictor';
$config['cutOffPredictorUrl_MHCET']   =  COLLEGE_PREDICTOR_BASE_URL.'/mhcet-cut-off-predictor';
$config['branchPredictorUrl_MHCET']   =  COLLEGE_PREDICTOR_BASE_URL.'/mhcet-branch-predictor';

$config['collegePredictorUrl_HSTES']   =  COLLEGE_PREDICTOR_BASE_URL.'/hstes-college-predictor';
$config['cutOffPredictorUrl_HSTES']   =  COLLEGE_PREDICTOR_BASE_URL.'/hstes-cut-off-predictor';
$config['branchPredictorUrl_HSTES']   =  COLLEGE_PREDICTOR_BASE_URL.'/hstes-branch-predictor';

$config['collegePredictorUrl_AP-EAMCET']   =  COLLEGE_PREDICTOR_BASE_URL.'/ap-eamcet-college-predictor';
$config['cutOffPredictorUrl_AP-EAMCET']   =  COLLEGE_PREDICTOR_BASE_URL.'/ap-eamcet-cut-off-predictor';
$config['branchPredictorUrl_AP-EAMCET']   =  COLLEGE_PREDICTOR_BASE_URL.'/ap-eamcet-branch-predictor';

$config['collegePredictorUrl_TS-EAMCET']   =  COLLEGE_PREDICTOR_BASE_URL.'/ts-eamcet-college-predictor';
$config['cutOffPredictorUrl_TS-EAMCET']   =  COLLEGE_PREDICTOR_BASE_URL.'/ts-eamcet-cut-off-predictor';
$config['branchPredictorUrl_TS-EAMCET']   =  COLLEGE_PREDICTOR_BASE_URL.'/ts-eamcet-branch-predictor';

$config['collegePredictorUrl_OJEE']   =  COLLEGE_PREDICTOR_BASE_URL.'/ojee-college-predictor';
$config['cutOffPredictorUrl_OJEE']   =  COLLEGE_PREDICTOR_BASE_URL.'/ojee-cut-off-predictor';
$config['branchPredictorUrl_OJEE']   =  COLLEGE_PREDICTOR_BASE_URL.'/ojee-branch-predictor';

$config['collegePredictorUrl_GGSIPU']   =  COLLEGE_PREDICTOR_BASE_URL.'/ggsipu-college-predictor';
$config['cutOffPredictorUrl_GGSIPU']   =  COLLEGE_PREDICTOR_BASE_URL.'/ggsipu-cut-off-predictor';
$config['branchPredictorUrl_GGSIPU']   =  COLLEGE_PREDICTOR_BASE_URL.'/ggsipu-branch-predictor';

$config['collegePredictorUrl_BITSAT']   =  COLLEGE_PREDICTOR_BASE_URL.'/bitsat-college-predictor';
$config['cutOffPredictorUrl_BITSAT']   =  COLLEGE_PREDICTOR_BASE_URL.'/bitsat-cut-off-predictor';
$config['branchPredictorUrl_BITSAT']   =  COLLEGE_PREDICTOR_BASE_URL.'/bitsat-branch-predictor';

$config['collegePredictorUrl_GUJCET']   =  COLLEGE_PREDICTOR_BASE_URL.'/gujcet-college-predictor';
$config['cutOffPredictorUrl_GUJCET']   =  COLLEGE_PREDICTOR_BASE_URL.'/gujcet-cut-off-predictor';
$config['branchPredictorUrl_GUJCET']   =  COLLEGE_PREDICTOR_BASE_URL.'/gujcet-branch-predictor';

/*$config['rankingWidget'] = array(
                                    array(
                                            'name'              => 'FULL-TIME MBA',
                                            'positionInLayer'   => '1',
                                            'link'              => SHIKSHA_HOME . '/' . trim('top-mba-colleges-in-india-rankingpage-2-2-0-0-0', '/'),
                                            'otherText'         => 'By city <strong>Delhi, Mumbai</strong> ...<br>By exam <strong>CAT, CMAT</strong> ...<br>By specialisation <strong>Finance, HR</strong>...'
                                        ),
                                    array(
                                            'name'              => 'BE/B.Tech',
                                            'positionInLayer'   => '4',
                                            'link'              => SHIKSHA_HOME . '/' . trim('top-engineering-colleges-in-india-rankingpage-44-2-0-0-0', '/'),
                                            'otherText'         => 'By city <strong>kolkata, hyderabad</strong> ...<br>By exam <strong>jee mains, kcet</strong> ...'
                                        ),
                                    array(
                                            'name'              => 'Other courses',
                                            'otherText'         => 'Executive MBA  |  Part-Time MBA  |  LLB'
                                        )
                                );*/

                               

$config['footerHamburgerDivs'] =	array(	'homepage'				=>	array(	'rankingCourseHamburgerDiv','mbaEntranceExamHamburgerDiv','engineeringExamHamburgerDiv','collegePredictorHamburgerDiv','rankPredictorHamburgerDiv'),
											'categoryPage'			=>	array(	'subCategoryId' =>	array(	23 => array('mbaEntranceExamHamburgerDiv'),
																											56 => array('engineeringExamHamburgerDiv','rankPredictorHamburgerDiv','collegePredictorHamburgerDiv')
																										)
																			),
											'coursePage'			=> array(	'subCategoryId' => array(	23 => array('mbaEntranceExamHamburgerDiv'),
																											56 => array('engineeringExamHamburgerDiv','collegePredictorHamburgerDiv','rankPredictorHamburgerDiv')
																										)
																			),
											'collegePredictorPage'	=> array(	'subCategoryId' => array(	56 => array('engineeringExamHamburgerDiv','rankPredictorHamburgerDiv')
																										)
																			),
											'rankPredictorPage'		=> array(	'subCategoryId' => array(	56 => array('collegePredictorHamburgerDiv','engineeringExamHamburgerDiv')
																										)
																			),
											'rankingPage'			=> array(	'subCategoryId' => array(	23 => array('mbaEntranceExamHamburgerDiv'),
																											56 => array('collegePredictorHamburgerDiv','engineeringExamHamburgerDiv','rankPredictorHamburgerDiv')
																										)
																			),
											'articlePage'			=> array(	'subCategoryId' => array(	23 => array('mbaEntranceExamHamburgerDiv'),
																											56 => array('collegePredictorHamburgerDiv','engineeringExamHamburgerDiv','rankPredictorHamburgerDiv')
																										)
																			)
										);
$config['tabsOnHomepage'] = array(
    'mba'    => array('streamId'=>1,'baseCourse'=>101),
    'engg'   => array('streamId'=>2,'baseCourse'=>10),
    'design' => array('streamId'=>3),
    'law'    => array('streamId'=>5),
    'other'  => -1,
);

$config['categoryMap'] = array(
						'mba'    => 3,
						'engg'   => 2,
						'design' => 13,
						'law'    => 9,
						'other'  => -1
						);

$config['hierarchyMap'] = array(
						'mba'    => array('streamId'=>'','baseCourse'=>101,'educationType'=>20),
						'engg'   => array('streamId'=>'','baseCourse'=>10,'educationType'=>20),
						'design' => array('streamId'=> 3),
						'law'    => array('streamId'=>5),
						'other'  => array()
						);
/*
$config['subCatMap'] = array(
							'mba' => array(23),
							'engg'=> array(56),
							'design'=>array(69,70,71,72,73),
							'law'=>array(33),
							'other'=>array()
							);
*/
// othter tab on homepage
$config['streamList'] = array(  
								'management'=>array('streamId'=>1,
								               'streamName'=>'Business & Management Studies',
								               'liClass'=>'first',
								               'class'=>'o-mngmnt'),
			                    'engineering'=>array('streamId'=>2,
						                    	'streamName'=>'Engineering',
						                    	'class'=>'o-engg',
						                    	'removeSubCat'=>array(33)),
			                    'design'=>array('streamId'=>3,
						                    	'streamName'=>'Design',
						                    	'class'=>'o-design'),
			                    'hospitality'=>array('streamId'=>4,
						                    	'streamName'=>'Hospitality & Travel',
						                    	'class'=>'o-hsptl'),
			                    'law'=>array('streamId'=>5,
						                    	'streamName'=>'Law',
						                    	'class'=>'o-law'),
			                    'animation'=>array('streamId'=>6,
						                    	'streamName'=>'Animation',
						                    	'class'=>'o-anim',
						                    	'removeSubCat'=>array(23)),
			                    'media'=>array('streamId'=>7,
						                    	'streamName'=>'Mass Communication & Media',
						                    	'class'=>'o-media'),
			                    'it'=>array('streamId'=>8,
						                    	'streamName'=>'IT & Software',
						                    	'class'=>'o-it'),
			                    'humanity'=>array(
			                    				'streamId'=>9,
			                    				'streamName'=>'Humanities & Social Sciences',
			                    				'class'=>'o-human'),
			                    'arts'=>array('streamId'=>10,
						                    	'streamName'=>'Arts ( Fine / Visual / Performing )',
						                    	'class'=>'o-art'),
			                    'science'=>array('streamId'=>11,
						                    	'streamName'=>'Science',
						                    	'class'=>'o-se'),
			                    'arch'=>array('streamId'=>12,
						                    	'streamName'=>'Architecture & Planning',
						                    	'class'=>'o-arch'),
			                    'account'=>array('streamId'=>13,
						                    	'streamName'=>'Accounting & Commerce',
						                    	'class'=>'o-acnt'),
			                    'banking'=>array('streamId'=>14,
						                    	'streamName'=>'Banking, Finance & Insurance',
						                    	'class'=>'o-bank'),
			                    'aviation'=>array('streamId'=>15,
						                    	'streamName'=>'Aviation',
						                    	'class'=>'o-aviation'),
			                    'teaching'=>array('streamId'=>16,
						                    	'streamName'=>'Teaching & Education',
						                    	'class'=>'o-teaching'),
			                    'nursing'=>array('streamId'=>17,
						                    	'streamName'=>'Nursing',
						                    	'class'=>'o-nursing'),
			                    'medicine'=>array('streamId'=>18,
						                    	'streamName'=>'Medicine & Health Sciences',
						                    	'class'=>'o-mdcn'),
			                    'beauty'=>array('streamId'=>19,
			                    				'liClass'=>'last',
						                    	'streamName'=>'Beauty & Fitness',
						                    	'class'=>'o-beauty',
						                    	'removeSubCat'=>array(56))
			                );
		
