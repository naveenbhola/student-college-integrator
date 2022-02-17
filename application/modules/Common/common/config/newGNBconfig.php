<?php
global $newGNBconfigData;
$config = array(
	'MBA'=> array(
		'Popular Courses' => array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'MBA/PGDM' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba-masters-of-business-administration-chp'),
                            'Executive MBA/PGDM' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/executive-mba-pgdm-chp'),
							'Distance MBA' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/distance-mba-pgdm-chp'),
							'Online MBA' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/online-mba-pgdm-chp'),
							'Part-Time MBA' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/part-time-mba-pgdm-chp')							
							)
						)
					),
		'Exams' => array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'Popular Exams' => array('type'=>'heading'),
							'CAT' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba/cat-exam'),
							'CMAT' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba/cmat-exam'),
							'SNAP' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba/snap-exam'),
							'XAT' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba/xat-exam'),
							'MAT' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba/mat-exam'),
							'ATMA' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba/atma-exam'),
							'NMAT by GMAC' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba/nmat-by-gmac-exam'),
							'All MBA Exams' => array('type'=>'urlWithAnchor', 'url'=>SHIKSHA_HOME.'/mba/exams-pc-101'),
							'Exam Calendar' => array('type'=>'urlWithAnchor', 'url'=>SHIKSHA_MBA_CALENDAR)
							),
					'secondColumn' =>
						array(
							'Featured Exams' => array('type'=>'heading')
							)
						)
					),
		'Colleges by Location' => array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'MBA Colleges in India' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba/colleges/mba-colleges-india'),
							'MBA Colleges in Bangalore' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba/colleges/mba-colleges-bangalore'),
							'MBA Colleges in Chennai' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba/colleges/mba-colleges-chennai'),
							'MBA colleges in Delhi-NCR' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba/colleges/mba-colleges-delhi-ncr'),
							'MBA Colleges in Hyderabad' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba/colleges/mba-colleges-hyderabad'),
							'MBA Colleges in Kolkata' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba/colleges/mba-colleges-kolkata'),
							'MBA Colleges in Mumbai' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba/colleges/mba-colleges-mumbai-all'),
							'MBA Colleges in Pune' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba/colleges/mba-colleges-pune'),
							'All Locations' => array('type'=>'all', 'url'=>SHIKSHA_HOME.'/mba/colleges/mba-colleges-india')
							)
						)
					),
		'Top Colleges' => array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'Popular Colleges' => array('type'=>'heading'),
							'IIM Ahmedabad' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/indian-institute-of-management-ahmedabad-vastrapur-307'),
							'IIM Bangalore' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/indian-institute-of-management-bangalore-bannerghatta-road-318'),
							'XLRI' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/xlri-xavier-school-of-management-jamshedpur-28564'),
							'FMS' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/faculty-of-management-studies-university-of-delhi-north-campus-28361'),
							'IIM Indore' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/indian-institute-of-management-indore-29623'),
							'SIBM Pune' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/symbiosis-institute-of-business-management-lavale-village-pune-3959'),
							'MBA College Rankings' =>array('type'=>'urlWithAnchor', 'url'=>SHIKSHA_HOME.'/mba/ranking/top-mba-colleges-in-india/2-2-0-0-0'),
							'Executive MBA College Rankings' =>array('type'=>'urlWithAnchor', 'url'=>SHIKSHA_HOME.'/business-management-studies/ranking/top-executive-mba-colleges-in-india/18-2-0-0-0')
							),
					'secondColumn' =>
						array(
							'Featured Colleges' => array('type'=>'heading')
							)
						)
					),
		'Compare Colleges' => array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'Popular Comparisons' => array('type'=>'heading'),
							'IIM-A vs IIM-B' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/resources/college-comparison-iim-ahemdabad-vs-iim-bangalore-1653-1688'),
							'IIM-A vs IIM-C' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/resources/college-comparison-iim-ahmedabad-vs-iim-kolkata-1653-22931 '),
							'SIBM vs SCMHRD' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/resources/college-comparison-sibm-vs-scmhrd-13280-164869'),
							'SP Jain Mumbai vs MDI Gurgaon' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/resources/college-comparison-spjimr-mumbai-vs-mdi-gurgaon-165223-9938'),
							'SIES Mumbai vs PUMBA Pune' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/resources/college-comparison-sies-mumbai-vs-pumba-pune-110936-123782'),
							'NMIMS vs SPJIMR' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/resources/college-comparison-nmims-mumbai-vs-spjain-mumbai-228082-165223'),
							'Compare other MBA colleges' => array('type'=>'urlWithAnchor', 'url'=>SHIKSHA_HOME.'/resources/college-comparison')
						)
					)
				),
		'College Reviews' => array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'Popular Reviews' => array('type'=>'heading'),
							'IIMs' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba/resources/reviews/reviews-of-best-iims'),
							'Non IIMs' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba/resources/reviews/shiksha-top-mba-colleges'),
							'MBA Colleges in Bangalore' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba/resources/reviews/top-mba-colleges-in-bangalore'),
							'MBA Colleges in Delhi-NCR' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba/resources/reviews/top-mba-colleges-in-delhi-ncr'),
							'MBA Colleges in Mumbai' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba/resources/reviews/top-mba-colleges-in-mumbai'),
							'MBA Colleges in Pune' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba/resources/reviews/top-mba-colleges-in-pune'),
							'All College Reviews' => array('type'=>'urlWithAnchor', 'url'=>SHIKSHA_HOME.'/mba/resources/college-reviews/1'),
							'Write a review' => array('type'=>'urlWithAnchor', 'url'=>SHIKSHA_HOME.'/college-review-form')
						)
					)
				),
		'CAT Percentile Predictor' => array(
							'url'=>SHIKSHA_HOME.'/mba/cat-exam-percentile-predictor',
							 ),

		'IIM & Non IIM Call Predictor' => array(
							'url'=>SHIKSHA_HOME.'/mba/resources/iim-call-predictor',
							 ),

		'College Predictors' => array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'MAHCET '.date('Y').' College predictor' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba/resources/mahcet-college-predictor')
							)
						)
			),		
		'Ask Current MBA Students' => array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'Popular Colleges' => array('type'=>'heading'),
							'XIME Bangalore' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/xavier-institute-of-management-and-entrepreneurship-bangalore-hosur-road-28230/questions'),
							'SIBM Pune' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/symbiosis-institute-of-business-management-lavale-village-pune-3959/questions'),
							'JBIMS Mumbai' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/jamnalal-bajaj-institute-of-management-studies-churchgate-mumbai-344/questions'),
							'FMS' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/faculty-of-management-studies-university-of-delhi-north-campus-28361/questions'),
							'IIM Ahmedabad' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/indian-institute-of-management-ahmedabad-vastrapur-307/questions'),
							'NMIMS' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/nmims-school-of-business-management-mumbai-narsee-monjee-vile-parle-west-46487/questions'),
							'Other MBA colleges' => array('type'=>'urlWithAnchor', 'url'=>SHIKSHA_HOME.'/business-management-studies/resources/campus-connect-program-1')
						)
					)
				),
		'Resources' => array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'MBA Alumni Salary Data' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba/resources/mba-alumni-data'),
							'Ask a Question' => array('type'=>'url', 'url'=>SHIKSHA_HOME."/tags/mba-tdp-422"),
							'Discussions' => array('type'=>'url', 'url'=>SHIKSHA_HOME."/tags/mba-tdp-422?type=discussion"),
							'News and Articles' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba/articles-pc-101'),
							'Apply to colleges' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mba/resources/application-forms'),
							'Trends in MBA' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/analytics/ShikshaTrends/showETP/base_course/101')
						)
					)
				)
		),
	'Engineering'=> array(
		'Popular Courses' => array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'B.E/B.Tech' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-e-b-tech-chp'),
							'M.E/M.Tech' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/m-e-m-tech-chp'),
							'Ph.D.' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/ph-d-chp'),
							'Diploma Courses' => array('type'=>'url', 'url' =>SHIKSHA_HOME.'/engineering/colleges/diploma-courses-india'),
							'Distance Diploma Courses' => array('type'=>'url', 'url' =>SHIKSHA_HOME.'/engineering/colleges/distance-correspondence-diploma-courses-india'),
							'Distance B.Tech' => array('type'=>'url', 'url' =>SHIKSHA_HOME.'/distance-b-e-b-tech-chp'),
							'All Engineering Courses' => array('type'=>'urlWithAnchor', 'url' =>SHIKSHA_HOME.'/engineering/colleges/colleges-india'),
						)
					)
				),
		'Popular Specializations' => array(
			'firstRow'=>array(
					'firstColumn'=>array(
						'Computer Science Engineering' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/computer-science-engineering-chp'),
						'Mechanical Engineering' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/mechanical-engineering-chp'),
						'Civil Engineering' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/civil-engineering-chp'),
						'Electronics & Communication Engineering' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/electronics-communication-engineering-chp'),
						'Aeronautical Engineering' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/aeronautical-engineering-chp'),
						'Aerospace Engineering' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/aerospace-engineering-chp'),
						'Information Technology' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/information-technology-chp'),
						'Electrical Engineering' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/electrical-engineering-chp'),
						'Electronics Engineering' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/electronics-engineering-chp'),
						'Nanotechnology' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/nanotechnology-chp'),
						'Chemical Engineering' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/chemical-engineering-chp'),
						'Automobile Engineering' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/automobile-engineering-chp'),
						'Biomedical Engineering' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/biomedical-engineering-chp'),
						'Construction Engineering' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/construction-engineering-chp'),
						'Pulp & Paper Technology' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/pulp-paper-technology-chp')
					),
					'secondColumn'=>array(
						'Biomedical Engineering' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/biomedical-engineering-chp'),
						'Marine Engineering' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/marine-engineering-chp'),
						'Genetic Engineering' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/genetic-engineering-chp'),
						'Food Technology' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/food-technology-chp'),
						'Petroleum Engineering' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/petroleum-engineering-chp'),
						'Control Systems' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/control-systems-chp'),
						'Industrial Engineering' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/industrial-engineering-chp'),
						'Production Engineering' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/production-engineering-chp'),
						'Environmental Engineering' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/environmental-engineering-chp'),
						'Robotics Engineering' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/robotics-engineering-chp'),
						'Telecommunication Engineering' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/telecommunication-engineering-chp'),
						'Materials Science' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/materials-science-chp'),
						'Structural Engineering' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/structural-engineering-chp'),
						'Aircraft Maintenance' => array('type'=>'url','url'=>SHIKSHA_HOME.'/aviation/aircraft-maintenance-engineering-chp'),
						'RF & Microwave Engineering' => array('type'=>'url','url'=>SHIKSHA_HOME.'/engineering/rf-microwave-engineering-chp')
					),
					'thirdColumn' => array(
						'VLSI Design' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/engineering/vlsi-design-chp'),
						'Mechatronics Engineering' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/engineering/mechatronics-engineering-chp'),
						'Mining Engineering' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/engineering/mining-engineering-chp'),
						'Biotechnology Engineering' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/engineering/biotechnology-engineering-chp'),
						'Transportation Engineering' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/engineering/transportation-engineering-chp'),
						'Metallurgical Engineering' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/engineering/metallurgical-engineering-chp'),
						'Textile Engineering' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/engineering/textile-engineering-chp'),
						'Naval Architecture' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/engineering/naval-architecture-chp'),
						'Power Engineering' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/engineering/power-engineering-chp'),
						'Dairy Technology' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/engineering/dairy-technology-chp'),
						'Microelectronics' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/engineering/microelectronics-chp'),
						'Communications Engineering' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/engineering/communications-engineering-chp'),
						'Tool Engineering' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/engineering/tool-engineering-chp'),
						'Ceramic Engineering' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/engineering/ceramic-engineering-chp'),
						'Jute & Fiber Technology' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/engineering/jute-fiber-technology-chp')
					)
			)
		),
		'Exams' => array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'Popular Exams' => array('type'=>'heading'),
							'JEE Main' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-tech/jee-main-exam'),
							'UPSEE' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-tech/upsee-exam'),
							'COMEDK' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-tech/comedk-uget-exam'),
							'BITSAT' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-tech/bitsat-exam'),	
							'WBJEE' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-tech/wbjee-exam'),	
							'JEE Advanced' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-tech/jee-advanced-exam'),
							'GATE' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/engineering/gate-exam'),
							'LPU-NEST'  => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/lovely-professional-university/lpu-nest-exam'),
							'All Engineering Exams' => array('type'=>'urlWithAnchor', 'url'=>SHIKSHA_HOME.'/b-tech/exams-pc-10'),
							'Exam Calendar' => array('type'=>'urlWithAnchor', 'url'=>SHIKSHA_ENGINEERING_CALENDAR)
						),
					'secondColumn' =>
						array(
							'Featured Exams' => array('type'=>'heading')
							)
					)
				),
		'Colleges by Location' => array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'Engineering Colleges in India' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-tech/colleges/b-tech-colleges-india'),
							'Engineering Colleges in Bangalore' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-tech/colleges/b-tech-colleges-bangalore'),
							'Engineering Colleges in Chennai' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-tech/colleges/b-tech-colleges-chennai'),
							'Engineering Colleges in Delhi-NCR' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-tech/colleges/b-tech-colleges-delhi-ncr'),
							'Engineering Colleges in Kolkata' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-tech/colleges/b-tech-colleges-kolkata'),
							'Engineering Colleges in Mumbai' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-tech/colleges/b-tech-colleges-mumbai-all'),
							'Engineering Colleges in Pune' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-tech/colleges/b-tech-colleges-pune'),
							'Engineering Colleges in Hyderabad' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-tech/colleges/b-tech-colleges-hyderabad'),
							'All Locations' => array('type'=>'all', 'url'=>SHIKSHA_HOME.'/b-tech/colleges/b-tech-colleges-india')
						)
					)
				),
		'Top Colleges' => array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'Popular Colleges' => array('type'=>'heading'),
							'IIT Delhi' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/indian-institute-of-technology-delhi-53938'),
							'IIT Bombay' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/indian-institute-of-technology-bombay-mumbai-54212'),
							'BITS Pilani' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/birla-institute-of-technology-and-science-pilani-467'),
							'IIT Kanpur' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/indian-institute-of-technology-kanpur-25116'),
							'NIT Trichy' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/national-institute-of-technology-tiruchirappalli-2996'),
							'Engineering College Rankings' =>array('type'=>'urlWithAnchor', 'url'=>SHIKSHA_HOME.'/b-tech/ranking/top-engineering-colleges-in-india/44-2-0-0-0')
						),
					'secondColumn' =>
						array(
							'Featured Colleges' => array('type'=>'heading')
							)
					)
				),
		'Compare Colleges' => array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'Popular Comparisons' => array('type'=>'heading'),
							'IIT Madras vs IIT Kanpur' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/resources/college-comparison-iit-madras-vs-iit-kanpur-8158-109925'),
							'VNIT vs NIT Rourkela' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/resources/college-comparison-vnit-nagpur-vs-nit-rourkela-61703-112801'),
							'Alliance University vs Christ University' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/resources/college-comparison-alliance-university-bangalore-vs-christ-university-bangalore-155426-110241'),
							'IIT Bombay vs IIT Delhi' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/resources/college-comparison-iit-bombay-vs-iit-delhi-109575-110582'),
							'BITS vs DTU' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/resources/college-comparison-bits-pilani-delhi-vs-dce-delhi-119869-52813'),
							'Compare other colleges' => array('type'=>'urlWithAnchor', 'url'=>SHIKSHA_HOME.'/resources/college-comparison')
						)
					)
				),
		'Rank Predictors' => array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'COMEDK '.date('Y').' Rank predictor' =>array('type'=>'url', 'url'=>RANK_PREDICTOR_BASE_URL.'/comedk-rank-predictor'),
							'JEE Advanced '.date('Y').' Rank predictor' =>array('type'=>'url', 'url'=>RANK_PREDICTOR_BASE_URL.'/jee-advanced-rank-predictor'),
							'JEE MAIN '.date('Y').' Rank predictor' =>array('type'=>'url', 'url'=>RANK_PREDICTOR_BASE_URL.'/jee-main-rank-predictor')
						)
					)
				),
		'College Predictors' => array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'CGPET '.date('Y').' College predictor' =>array('type'=>'url', 'url'=>COLLEGE_PREDICTOR_BASE_URL.'/cgpet-college-predictor'),
							'COMEDK '.date('Y').' College predictor' =>array('type'=>'url', 'url'=>COLLEGE_PREDICTOR_BASE_URL.'/comedk-college-predictor'),
							'HSTES '.date('Y').' College predictor' =>array('type'=>'url', 'url'=>COLLEGE_PREDICTOR_BASE_URL.'/hstes-college-predictor'),
							'JEE MAIN '.date('Y').' College predictor' =>array('type'=>'url', 'url'=>COLLEGE_PREDICTOR_BASE_URL.'/jee-mains-college-predictor'),
							'KCET '.date('Y').' College predictor' =>array('type'=>'url', 'url'=>COLLEGE_PREDICTOR_BASE_URL.'/kcet-college-predictor'),
							'KEAM '.date('Y').' College predictor' =>array('type'=>'url', 'url'=>COLLEGE_PREDICTOR_BASE_URL.'/keam-college-predictor'),
							'MHCET '.date('Y').' College predictor' =>array('type'=>'url', 'url'=>COLLEGE_PREDICTOR_BASE_URL.'/mhcet-college-predictor'),
							'MPPET '.date('Y').' College predictor' =>array('type'=>'url', 'url'=>COLLEGE_PREDICTOR_BASE_URL.'/mppet-college-predictor'),
							'PTU '.date('Y').' College predictor' =>array('type'=>'url', 'url'=>COLLEGE_PREDICTOR_BASE_URL.'/ptu-college-predictor'),
							'Engineering College Predictor' => array('type'=>'urlWithAnchor', 'url'=>SHIKSHA_HOME.'/college-predictor')

						),
                                        'secondColumn' =>
                                                array(
                                                        'TNEA '.date('Y').' College predictor' =>array('type'=>'url', 'url'=>COLLEGE_PREDICTOR_BASE_URL.'/tnea-college-predictor'),
                                                        'UPSEE '.date('Y').' College predictor' =>array('type'=>'url', 'url'=>COLLEGE_PREDICTOR_BASE_URL.'/upsee-college-predictor'),
                                                        'WBJEE '.date('Y').' College predictor' =>array('type'=>'url', 'url'=>COLLEGE_PREDICTOR_BASE_URL.'/wbjee-college-predictor'),
                                                        'AP-EAMCET '.date('Y').' College predictor' =>array('type'=>'url', 'url'=>COLLEGE_PREDICTOR_BASE_URL.'/ap-eamcet-college-predictor'),
                                                        'TS-EAMCET '.date('Y').' College predictor' =>array('type'=>'url', 'url'=>COLLEGE_PREDICTOR_BASE_URL.'/ts-eamcet-college-predictor'),
                                                        'BITSAT '.date('Y').' College predictor' =>array('type'=>'url', 'url'=>COLLEGE_PREDICTOR_BASE_URL.'/bitsat-college-predictor'),
                                                        'GGSIPU '.date('Y').' College predictor' =>array('type'=>'url', 'url'=>COLLEGE_PREDICTOR_BASE_URL.'/ggsipu-college-predictor'),
                                                        'OJEE '.date('Y').' College predictor' =>array('type'=>'url', 'url'=>COLLEGE_PREDICTOR_BASE_URL.'/ojee-college-predictor'),
                                                        'GUJCET '.date('Y').' College predictor' =>array('type'=>'url', 'url'=>COLLEGE_PREDICTOR_BASE_URL.'/gujcet-college-predictor')                                                
                                                        )

					)
				),
		'College Reviews' => array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'Popular Reviews' => array('type'=>'heading'),
							'IITs & ISM' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/btech/resources/reviews/reviews-of-iits-and-ism-in-india'),
							'NITs' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/btech/resources/reviews/reviews-of-nits-in-india'),
							'Engineering Colleges in Bangalore' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/btech/resources/reviews/reviews-of-engineering-colleges-in-bangalore'),
							'Engineering Colleges in Delhi-NCR' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/btech/resources/reviews/reviews-of-engineering-colleges-in-delhi-ncr'),
							'All College Reviews' => array('type'=>'urlWithAnchor', 'url'=>SHIKSHA_HOME.'/btech/resources/college-reviews/1'),
							'Write a review' => array('type'=>'urlWithAnchor', 'url'=>SHIKSHA_HOME.'/college-review-form')
						)
					)
				),
		'Resources' => array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'Ask a Question' => array('type'=>'url', 'url'=>SHIKSHA_HOME."/tags/engineering-tdp-20"),
							'Discussions' => array('type'=>'url', 'url'=>SHIKSHA_HOME."/tags/engineering-tdp-20?type=discussion"),
							'News and Articles' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/engineering/articles-st-2'),
							'Apply to colleges' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/engineering/resources/application-forms'),
							'Trends in BTech' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/analytics/ShikshaTrends/showETP/base_course/10'),
							'Trends in Engineering' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/analytics/ShikshaTrends/showETP/stream/2')
						)
					)
				)
		),
	'Law' => array(
		'Popular Courses' => array(
			'firstRow'=>array(
					'firstColumn' => array(
                        'B.A. LL.B.'     => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-a-ll-b-chp'),
                        'BBA LL.B.'     => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/bba-ll-b-chp'),
                        'LL.B.' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/ll-b-chp'),
                        'LL.M.'  => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/ll-m-chp'),
                        'B.Sc. LL.B' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-sc-ll-b-chp'),
                        'B.Com LL.B' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-com-ll-b-chp'),
                        'B.L.S. LL.B.' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-l-s-ll-b-bachelor-of-legal-science-bachelor-of-law-chp'),
                        'All Law Courses'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/law/colleges/colleges-india')
                    	)
					)
				),
		'Popular Specializations' => array(
			'firstRow'=>array(
					'firstColumn'=>array(
						'Company Law' => array('type'=>'url','url'=>SHIKSHA_HOME.'/law/company-law-chp'),
						'Business Law' => array('type'=>'url','url'=>SHIKSHA_HOME.'/law/business-law-chp'),
						'Cyber Law' => array('type'=>'url','url'=>SHIKSHA_HOME.'/law/cyber-law-chp'),
						'Corporate Law' => array('type'=>'url','url'=>SHIKSHA_HOME.'/law/corporate-law-chp'),
						'Criminal Law' => array('type'=>'url','url'=>SHIKSHA_HOME.'/law/criminal-law-chp'),
						'Administrative Law' => array('type'=>'url','url'=>SHIKSHA_HOME.'/law/administrative-law-chp'),
						'Family Law' => array('type'=>'url','url'=>SHIKSHA_HOME.'/law/family-law-chp'),
						'Constitutional Law' => array('type'=>'url','url'=>SHIKSHA_HOME.'/law/constitutional-law-chp'),
						'Environmental Law' => array('type'=>'url','url'=>SHIKSHA_HOME.'/law/environmental-law-chp'),
						'Intellectual Property Law' => array('type'=>'url','url'=>SHIKSHA_HOME.'/law/intellectual-property-law-chp')					),
					'secondColumn'=>array(
						'Banking Law' => array('type'=>'url','url'=>SHIKSHA_HOME.'/law/banking-law-chp'),
						'Competition Law' => array('type'=>'url','url'=>SHIKSHA_HOME.'/law/competition-law-chp'),
						'Commercial Law' => array('type'=>'url','url'=>SHIKSHA_HOME.'/law/commercial-law-chp'),
						'Immigration Law' => array('type'=>'url','url'=>SHIKSHA_HOME.'/law/immigration-law-chp'),
						'Tax Law' => array('type'=>'url','url'=>SHIKSHA_HOME.'/law/tax-law-chp'),
						'Insurance Law' => array('type'=>'url','url'=>SHIKSHA_HOME.'/law/insurance-law-chp'),
						'Energy Law' => array('type'=>'url','url'=>SHIKSHA_HOME.'/law/energy-law-chp'),
						'International Trade Law' => array('type'=>'url','url'=>SHIKSHA_HOME.'/law/international-trade-law-chp'),
						'Consumer Law' => array('type'=>'url','url'=>SHIKSHA_HOME.'/law/consumer-law-chp'),
						'Arbitration Law' => array('type'=>'url','url'=>SHIKSHA_HOME.'/law/arbitration-law-chp')
					),
					'thirdColumn' => array(
						'Real Estate / Infrastructure Law' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/real-estate-infrastructure-law-chp'),
						'Information Technology Law' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/information-technology-law-chp'),
						'Healthcare Law' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/healthcare-law-chp'),
						'Labor & Employment Law' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/labor-employment-law-chp'),
						'Air & Space Law' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/air-space-law-chp'),
						'Nuclear Law' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/nuclear-law-chp'),
						'Human Rights & International Humanitarian Law' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/human-rights-international-humanitarian-law-chp'),
						'Security & Investment Law' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/security-investment-law-chp'),
						'Entertainment & Media Law' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/entertainment-media-law-chp')
					)
			)
		),
		'Exams' => array(
			'firstRow'  => array(
                'firstColumn' => array(
                	'CLAT'                    => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/clat-exam'),
                    'LSAT'                     => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/lsat-exam'),
                    'AILET'                  => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/ailet-exam'),
                    'DU LLB  Entrance'                  => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/du-llb-entrance-exam'),
                    'AMU Law Entrance'                  => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/amu-entrance-exam'),
                    'ACLAT'                  => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/aclat-exam'),
                    'All Law Exams' => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/law/exams-st-5')
                		)
                	)	
                ),
		'Colleges by Location'=>array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'Law Colleges in India' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/colleges/colleges-india'),
							'Law Colleges in Punjab' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/colleges/colleges-punjab'),
							'Law Colleges in Delhi' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/colleges/colleges-delhi'),
							'Law Colleges in Chandigarh' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/colleges/colleges-chandigarh'),
							'Law Colleges in Maharashtra' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/colleges/colleges-maharashtra'),
							'Law Colleges in Orissa' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/colleges/colleges-orissa'),
							'Law Colleges in Uttarakhand' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/colleges/colleges-uttarakhand'),
							'Law Colleges in West Bengal' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/colleges/colleges-west-bengal'),
							'Law Colleges in Karnataka' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/colleges/colleges-karnataka'),
							'Law Colleges in Ludhiana' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/colleges/colleges-ludhiana'),
							'Law Colleges in Pune' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/colleges/colleges-pune'),
							'Law Colleges in Jalandhar' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/colleges/colleges-jalandhar'),
							'Law Colleges inBhubaneswar' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/colleges/colleges-bhubaneswar'),
							'Law Colleges in Roorkee' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/colleges/colleges-roorkee'),
							'Law Colleges in Kolkata' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/colleges/colleges-kolkata'),
							'Law Colleges in Udupi' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/colleges/colleges-udupi')
							)
						)
			),
		'Top Colleges' => array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'Popular Colleges' => array('type'=>'heading'),
		                    'NALSAR Hyderabad'  => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/nalsar-university-of-law-hyderabad-4194'),
		                    'GNLU Gujrat'  => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/gujarat-national-law-university-gandhinagar-22077'),
		                    'Symbiosis Law School Pune'  => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/symbiosis-law-school-viman-nagar-pune-34409'),
		                    'NLSIU Bangalore'  => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/national-law-school-of-india-university-bangalore-483'),
		                    'Law Centre, Delhi University'  => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/law-centre-1-university-of-delhi-north-campus-48491'),
		                    'The West Bengal National University of Juridical Sciences'  => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/the-west-bengal-national-university-of-juridical-sciences-kolkata-24886'),
		                    'NLIU Bhopal'  => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/the-national-law-institute-university-bhopal-23639'),
		                    'Law College Rankings'  => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/law/ranking/top-law-colleges-in-india/56-2-0-0-0')
		                		),
						'secondColumn' =>
						array(
							'Featured Colleges' => array('type'=>'heading')
							)
						)
					),
		'College Predictors' => array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'CLAT '.date('Y').' College predictor' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/resources/clat-college-predictor')
							)
						)
			),
		'Resources' => array(
			'firstRow'  => array(
                'firstColumn' => array(
                    		'Resources' => array('type'=>'heading'),
                        	'Ask a Question' => array('type'=>'url', 'url'=>SHIKSHA_HOME."/tags/law-tdp-15"),
                        	'Discussions' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/tags/law-tdp-15?type=discussion'),
                        	'News and Articles' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/articles-st-5'),
                        	'Trends in Law' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/analytics/ShikshaTrends/showETP/stream/5')
                    	)
                	)	
                )
		),
	'Design'=> array(
		'Popular Specializations' => array(
			'firstRow'=>array(
					'firstColumn'=>array(
						'Fashion Design' => array('type'=>'url','url'=>SHIKSHA_HOME.'/design/fashion-design-chp'),
						'Interior Design' => array('type'=>'url','url'=>SHIKSHA_HOME.'/design/interior-design-chp'),
						'Graphic Design' => array('type'=>'url','url'=>SHIKSHA_HOME.'/design/graphic-design-chp'),
						'Jewellery Design' => array('type'=>'url','url'=>SHIKSHA_HOME.'/design/jewellery-design-chp'),
						'Web Design' => array('type'=>'url','url'=>SHIKSHA_HOME.'/design/web-design-chp'),
						'Furniture Design' => array('type'=>'url','url'=>SHIKSHA_HOME.'/design/furniture-design-chp'),
						'Game Design' => array('type'=>'url','url'=>SHIKSHA_HOME.'/it-software/game-design-chp'),
						'Industrial / Product Design' => array('type'=>'url','url'=>SHIKSHA_HOME.'/design/industrial-product-design-chp'),
						'Textile Design' => array('type'=>'url','url'=>SHIKSHA_HOME.'/design/textile-design-chp'),
						'Visual Merchandising' => array('type'=>'url','url'=>SHIKSHA_HOME.'/design/visual-merchandising-chp'),
						'Ceramic & Glass Design' => array('type'=>'url','url'=>SHIKSHA_HOME.'/design/ceramic-glass-design-chp'),
						'Film & Video Design' => array('type'=>'url','url'=>SHIKSHA_HOME.'/design/film-video-design-chp')
					),
					'secondColumn'=>array(
						'UI / UX' => array('type'=>'url','url'=>SHIKSHA_HOME.'/design/ui-ux-chp'),
						'Footwear Design' => array('type'=>'url','url'=>SHIKSHA_HOME.'/design/footwear-design-chp'),
						'Automotive Design' => array('type'=>'url','url'=>SHIKSHA_HOME.'/design/automotive-design-chp'),
						'Communication Design' => array('type'=>'url','url'=>SHIKSHA_HOME.'/design/communication-design-chp'),
						'Apparel Design' => array('type'=>'url','url'=>SHIKSHA_HOME.'/design/apparel-design-chp'),
						'Exhibition Design' => array('type'=>'url','url'=>SHIKSHA_HOME.'/design/exhibition-design-chp'),
						'Information Design' => array('type'=>'url','url'=>SHIKSHA_HOME.'/design/information-design-chp'),
						'Knitwear Design' => array('type'=>'url','url'=>SHIKSHA_HOME.'/design/knitwear-design-chp'),
						'Leather Design' => array('type'=>'url','url'=>SHIKSHA_HOME.'/design/leather-design-chp'),
						'Toy Design' => array('type'=>'url','url'=>SHIKSHA_HOME.'/design/toy-design-chp'),
						'Ceramic & Glass Design' => array('type'=>'url','url'=>SHIKSHA_HOME.'/design/ceramic-glass-design-chp'),
						'Lifestyle Accesory Design' => array('type'=>'url','url'=>SHIKSHA_HOME.'/design/lifestyle-accesory-design-chp'),
						'All Design Specializations' => array('type'=>'urlWithAnchor', 'url'=>SHIKSHA_HOME.'/design/colleges/colleges-india')
					)
					// 'firstColumn'=>
					// 	array(
					// 		'Communication Design' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/communication-design/colleges/colleges-india'),
					// 		'Fashion Design' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/fashion-design-chp'),
					// 		'Industrial / Product Design' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/industrial-product-design/colleges/colleges-india'),
					// 		'Interior Design' => array('type'=>'url', 'url' =>SHIKSHA_HOME.'/design/interior-design-chp'),
					// 		'All Design Specializations' => array('type'=>'urlWithAnchor', 'url'=>SHIKSHA_HOME.'/design/colleges/colleges-india')
					// 	)
					)
				),
		'Popular Courses' => array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'B.Des' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-des-chp'),
							'M.Des' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/m-des-chp'),
							'B.Des in Fashion Design' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/b-des-in-fashion-design-chp'),
							'B.Des in Interior Design' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/b-des-in-interior-design-chp'),
							'B.Sc. In Fashion Design' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/b-sc-in-fashion-design-chp'),
							'B.Sc in Interior Design' => array('type'=>'url', 'url' =>SHIKSHA_HOME.'/design/b-sc-in-interior-design-chp'),
							'All Design Courses' => array('type'=>'urlWithAnchor', 'url' =>SHIKSHA_HOME.'/design/colleges/colleges-india')
						)
					)
				),
		'Exam Predictors' => array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'NIFT' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/resources/nift-college-predictor'),
						)
					)
				),
		'Exams' => array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'Popular Exams' => array('type'=>'heading'),
							'AICET' => array('type'=>'url','url'=>SHIKSHA_HOME.'/design/fashion-design/aicet-exam'),
							'CEED' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/ceed-exam'),
							'NID Entrance Exam' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/fashion-design/nid-entrance-exam'),
							'NIFT Entrance Exam' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/fashion-design/nift-entrance-exam'),
							'UCEED' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/industrial-product-design/uceed-exam'),
							'All Design Exams' => array('type'=>'urlWithAnchor', 'url'=>SHIKSHA_HOME.'/design/exams-st-3')
						),
					'secondColumn' =>
						array(
							'Featured Exams' => array('type'=>'heading')
							)
					)
				),
		'Colleges by Location'=>array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'Design Colleges in India' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/colleges/colleges-india'),
							'Design Colleges in Maharashtra' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/colleges/colleges-maharashtra'),
							'Design Colleges in Delhi' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/colleges/colleges-delhi'),
							'Design Colleges in Karnataka' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/colleges/colleges-karnataka'),
							'Design Colleges in Punjab' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/colleges/colleges-punjab'),
							'Design Colleges in Gujarat' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/colleges/colleges-gujarat'),
							'Design Colleges in Chandigarh' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/law/colleges/colleges-west-bengal'),
							'Design Colleges in Rajasthan' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/colleges/colleges-rajasthan'),
							'Design Colleges in Madhya Pradesh' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/colleges/colleges-madhya-pradesh'),
							'Design Colleges in Uttar Pradesh' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/colleges/colleges-uttar-pradesh')
						),
					'secondColumn'=>array(
							'Design Colleges in Pune' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/colleges/colleges-pune'),
							'Design Colleges in Mumbai' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/colleges/colleges-mumbai'),
							'Design Colleges in Bangalore' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/colleges/colleges-bangalore'),
							'Design Colleges in Hyderabad' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/colleges/colleges-hyderabad'),
							'Design Colleges in Ahmedabad' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/colleges/colleges-ahmedabad'),
							'Design Colleges in Ludhiana' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/colleges/colleges-ludhiana'),
							'Design Colleges in Jalandhar' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/colleges/colleges-jalandhar'),
							'Design Colleges in Jaipur' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/colleges/colleges-jaipur'),
							'Design Colleges in Ahmedabad' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/colleges/colleges-ahmedabad'),
							'Design Colleges in Ludhiana' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/colleges/colleges-ludhiana'),
							'Design Colleges in Jalandhar' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/colleges/colleges-jalandhar'),
							'Design Colleges in Jaipur' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/colleges/colleges-jaipur'),
							'Design Colleges in Indore' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/colleges/colleges-indore'),
							'Design Colleges in Gurgaon' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/colleges/colleges-gurgaon')
						)
				)
			),
		'Top Colleges' => array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'Popular Colleges' => array('type'=>'heading'),
								'NID Ahmedabad' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/national-institute-of-design-ahmedabad-paldi-33410'),
								'NIFT Delhi' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/national-institute-of-fashion-technology-delhi-hauz-khas-27356'),
								'NIFT Mumbai' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/national-institute-of-fashion-technology-mumbai-navi-mumbai-27354'),
								'SID Pune' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/symbiosis-institute-of-design-viman-nagar-pune-21891'),
								'Pearl Academy Delhi' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/pearl-academy-delhi-ncr-west-campus-naraina-27682'),	
								'Fashion Designing College Rankings' => array('type'=>'urlWithAnchor', 'url'=>SHIKSHA_HOME.'/design/fashion-design/ranking/top-fashion-design-colleges-in-india/94-2-0-0-0')
							),
						'secondColumn' =>
							array(
								'Featured Colleges' => array('type'=>'heading')
							)
						)
					),
		'Resources' => array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'Ask a Question' => array('type'=>'url', 'url'=>SHIKSHA_HOME."/tags/design-tdp-10"),
							'Discussions' => array('type'=>'url', 'url'=>SHIKSHA_HOME."/tags/design-tdp-10?type=discussion"),
							'News and Articles' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/design/articles-st-3'),
							'Trends in Design' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/analytics/ShikshaTrends/showETP/stream/3')
						)
					)
				)

		),
	'More Courses'=> array(
					'Sarkari Exams' => array(
						'url'=>SHIKSHA_HOME.'/government-exams/exams-st-21',
						'firstRow' =>array(
							'firstColumn' => array(
								'Exams' => array('type'=>'heading'),
								'SSC CGL' => array('type'=>'url','url'=>SHIKSHA_HOME.'/exams/ssc-cgl'),
								'IBPS PO' => array('type'=>'url','url'=>SHIKSHA_HOME.'/exams/ibps-po'),
								'SBI PO' => array('type'=> 'url','url'=>SHIKSHA_HOME.'/exams/sbi-po'),
								'NDA'=> array('type'=>'url','url'=>SHIKSHA_HOME.'/exams/nda'),
								'UPSC Civil Services Exam' => array('type'=>'url','url'=> SHIKSHA_HOME.'/exams/upsc-civil-services-exam'),
								'IES' => array('type'=>'url','url'=>SHIKSHA_HOME.'/exams/ies'),
								'SSC CHSL' => array('type'=>'url','url'=>SHIKSHA_HOME.'/exams/ssc-chsl'),
								'AFCAT' => array('type'=>'url','url'=>SHIKSHA_HOME.'/exams/afcat'),
								'IBPS CWE RRB' => array('type'=>'url','url'=>SHIKSHA_HOME.'/exams/ibps-cwe-rrb'),
								'SSC JE' => array('type'=>'url','url'=>SHIKSHA_HOME.'/exams/ssc-je'),
								'CDS' => array('type'=>'url','url'=>SHIKSHA_HOME.'/exams/cds'),
								'SBI Clerk' => array('type'=>'url','url'=>SHIKSHA_HOME.'/exams/sbi-clerk'),
								'IBPS Clerk' => array('type'=>'url','url'=>SHIKSHA_HOME.'/exams/ibps-clerk'),
								'View All Sarkari Exams' => array('type'=>'link','url'=>SHIKSHA_HOME.'/government-exams/exams-st-21')
								
							)
						)
					),


					'Hospitality & Travel' => array(
						'url'=>SHIKSHA_HOME.'/hospitality-travel-chp',
						'firstRow'  => array(
                            'firstColumn' => array(
                                'Popular Courses' => array('type'=>'heading'),
                                'BHM'  => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/bhm-chp'),
                                'Diploma in Hotel Management'  => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/hospitality-travel/diploma-in-hotel-hospitality-management-chp'),
                                'B.Sc. In Hotel Management'     => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/hospitality-travel/b-sc-in-hotel-hospitality-management-chp'),
                                'All Hospitality Courses'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/hospitality-travel/colleges/colleges-india')
                            ),
                            'secondColumn' => array(
                                'Top Colleges' => array('type'=>'heading'),
                                'IHM Ahmedabad' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/institute-of-hotel-management-ahmedabad-gandhinagar-921'),
                                'IHM Hyderabad'            => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/institute-of-hotel-management-catering-technology-and-applied-nutrition-hyderabad-vidyanagar-28056'),
                                'IHM Mumbai'            => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/institute-of-hotel-management-mumbai-dadar-west-917'),
                                'IHM Bangalore'            => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/institute-of-hotel-management-bangalore-seshadri-road-29260'),
                                'IHM Chennai'            => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/institute-of-hotel-management-catering-technology-and-applied-nutrition-chennai-taramani-28048'),
                                'Hotel Management College Rankings'            => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/hospitality-travel/hotel-hospitality-management/ranking/top-hotel-management-colleges-in-india/98-2-0-0-0'),
                            ),
                            'thirdColumn' => array(
                            	'Exams' => array('type'=>'heading'),
								'IIHM eCHAT' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/hospitality-travel/hotel-hospitality-management/iihm-echat-exam'),
								'NCHMCT JEE' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/hospitality-travel/hotel-hospitality-management/nchmct-jee-exam'),
								'Ecole Hoteliere Lavasa Exam' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/hospitality-travel/hotel-hospitality-management/ecole-hoteliere-lavasa-exam'),
								'All Hospitality Exams' => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/hospitality-travel/exams-st-4')
                            )
                        ),
					'secondRow' => array(
						'firstColumn' => array(
							'Popular Specializations' => array('type'=>'heading'),
							'Catering' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/hospitality-travel/catering-courses-chp'),
							'Culinary Arts' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/hospitality-travel/culinary-arts-chp'),
							'Event Management' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/hospitality-travel/event-management-chp'),
							'Fares & Ticketing' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/hospitality-travel/fares-ticketing-courses-chp'),
							'Hotel / Hospitality Management' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/hospitality-travel/hotel-hospitality-management-chp'),
							'Travel & Tourism' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/hospitality-travel/travel-tourism-chp'),
                            'All Hospitality Specializations'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/hospitality-travel/colleges/colleges-india')

							),
						'secondColumn' => array(
							'Featured Colleges' => array('type'=>'heading')
						),
						'thirdColumn' => array(
								'Resources'         => array('type'=>'heading'),
                                'NCHMCT 2018 College Predictor' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/hospitality-travel/resources/nchmct-college-predictor'),
                                'Ask a Question'    => array('type'=>'url', 'url'=>SHIKSHA_HOME."/tags/hotel-management-tdp-22"),
                                'Discussions'       => array('type'=>'url', 'url'=>SHIKSHA_HOME."/tags/hotel-management-tdp-22?type=discussion"),
                                'News and Articles' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/hospitality-travel/articles-st-4'),
                                'Trends in Hospitality & Travel' =>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/analytics/ShikshaTrends/showETP/stream/4')

							)
						)
                    ),
                                
                'Animation' => array(						
                		'url'=>SHIKSHA_HOME.'/animation-chp',
                        'firstRow' => array(
                            'firstColumn' => array(
                                'Popular Courses'   => array('type'=>'heading'),
                                'B.Sc. in Animation' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/animation/b-sc-in-animation-chp'),
                                'M.Sc. in Animation' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/animation/m-sc-in-animation-chp'),
                                'Diploma in Web/Graphic Design' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/animation/diploma-in-graphic-web-design-chp'),
                                'Diploma in VFX' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/animation/diploma-in-visual-effects-vfx-chp'),
                                'Diploma in Animation' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/diploma-in-animation-chp'),
                                'All Animation Courses'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/animation/colleges/colleges-india')
                            ),
                            'secondColumn' => array(
                                'Top Colleges' => array('type'=>'heading'),
                                'MAAC' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/maac-pune-camp-camp-31765'),
                                'Arena Animation' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/arena-animation-delhi-ncr-noida-52753'),
                                'FX School' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/fx-school-andheri-west-mumbai-28453'),
                            ),
                            'thirdColumn' => array(
                                'Resources' => array('type'=>'heading'),
								'Ask a Question'  => array('type'=>'url', 'url'=>SHIKSHA_HOME."/tags/animation-tdp-365"),
								'Discussions'  => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/tags/animation-tdp-365?type=discussion'),
								'News and Articles'  => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/animation/articles-st-6'),
								'Trends in Animation'  => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/analytics/ShikshaTrends/showETP/stream/6')
                            )
                                            ),
                        'secondRow' => array(
                            'firstColumn' => array(
                                'Popular Specializations' => array('type'=>'heading'),
                                '2D/3D Animation'        => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/animation/2d-3d-animation-chp'),
                                'Animation Film Making'        => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/animation/animation-film-making-chp'),
                                'Graphic / Web Design'        => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/animation/graphic-web-design-chp'),
                                'Sound & Video Editing'        => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/animation/sound-video-editing-chp'),
                                'VFX'        => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/animation/visual-effects-vfx-chp'),
                                'Game Design'        => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/it-software/game-design-chp'),
                                'All Animation Specializations'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/animation/colleges/colleges-india')
                            ),
                            'secondColumn' => array(
                               'Featured Colleges' => array('type'=>'heading')
                            ),
                            'thirdColum' => array(
                            )
                        )
                    ),
				'Mass Communication & Media' => array(
						'url'=>SHIKSHA_HOME.'/mass-communication-media-chp',
                        'firstRow'  => array(
                            'firstColumn' => array(
                                'Popular Courses'      => array('type'=>'heading'),
                                'B.J.' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-j-bachelor-of-journalism-chp'),
                                'B.J.M.C.'  => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-j-m-c-bachelor-of-journalism-mass-communication-chp'),
                                'B.M.M.'  => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-m-m-bachelor-of-mass-media-chp'),
                                'M.A.'     => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mass-communication-media/m-a-in-mass-communication-media-chp'),
                                'Diploma in Journalism'     => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mass-communication-media/diploma-in-journalism-chp'),
                                'All Mass Comm & Media Courses'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/mass-communication-media/colleges/colleges-india')
                            ),
                            'secondColum' => array(
                                'Top Colleges'    => array('type'=>'heading'),
                                'LSR'        => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/lady-shri-ram-college-for-women-lajpat-nagar-delhi-23895'),
                                'Madras Christian College'        => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/madras-christian-college-tambaram-sanatorium-chennai-5946'),
                                'IIMC Delhi' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/indian-institute-of-mass-communication-qutab-institutional-area-delhi-2974'),
                                'Mass Communication College Rankings' => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/mass-communication-media/ranking/top-mass-communication-colleges-in-india/99-2-0-0-0')
                            ),
                            'thirdColumn' => array(
                            	'Exams' => array('type'=>'heading'),
                            	'IIMC Entrance Exam' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mass-communication-media/iimc-entrance-exam'),
                            	'JMI Entrance Exam' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mass-communication-media/jamia-millia-islamia-entrance-exam'),
                            	'XIC OET' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mass-communication-media/xic-oet-exam'),
                            	'ACJ Entrance Exam' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mass-communication-media/acj-entrance-exam'),
								'All Mass Comm & Media Exams' => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/mass-communication-media/exams-st-7')

                            )
                        ),
                        'secondRow' => array(
                            'firstColumn' => array(
                            	'Popular Specializations' => array('type'=>'heading'),
                                'Advertising' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mass-communication-media/advertising-chp'),
                                'Event Management' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/hospitality-travel/event-management-chp'),
                                'Film & TV' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mass-communication-media/film-tv-chp'),
                                'Journalism' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mass-communication-media/journalism-chp'),
                                'Music & Sound Production' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mass-communication-media/music-sound-production-chp'),
                                'Public Relations' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mass-communication-media/public-relations-chp'),
                                'Radio' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mass-communication-media/radio-chp'),
                                'All Mass Comm & Media Specializations'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/mass-communication-media/colleges/colleges-india')

                            ),
                            'secondColumn' => array(
                               'Featured Colleges' => array('type'=>'heading')
                            ),
                            'thirdColumn' => array(
                            	'Resources' => array('type' => 'heading'),
								'Ask a Question'  => array('type'=>'url', 'url'=>SHIKSHA_HOME."/tags/mass-communication-media-tdp-14"),
								'Discussions'  => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/tags/mass-communication-media-tdp-14?type=discussion'),
                            	'News and Articles' => array('type' => 'url', 'url'=>SHIKSHA_HOME.'/mass-communication/articles-st-7'),
								'Trends in Mass Communication & Media' => array('type' => 'url', 'url'=>SHIKSHA_HOME.'/analytics/ShikshaTrends/showETP/stream/7'),
								'Delhi University Cut-Offs '.date('Y') => array('type' => 'url', 'url' => SHIKSHA_HOME.'/university/university-of-delhi-24642/cutoff'),
                            )
                        )
                    ),
				'Business & Management Studies' => array(
						'url'=>SHIKSHA_HOME.'/business-management-studies-chp',
                        'firstRow'  => array(
                            'firstColumn' => array(
                                'Popular Courses' => array('type'=>'heading'),
                                'BBA'    => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/bba-chp'),
                                'Management Certifications'    => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/certificate-in-business-management-studies-chp'),
                                'MBA/PGDM' => array('type'=>'url','title'=>'MBA/PGDM', 'url'=>SHIKSHA_HOME.'/mba-masters-of-business-administration-chp'),
								'Executive MBA/PGDM' => array('type'=>'url','title'=>'Executive MBA', 'url'=>SHIKSHA_HOME.'/executive-mba-pgdm-chp'),
								'Distance MBA' => array('type'=>'url','title'=>'Distance MBA', 'url'=>SHIKSHA_HOME.'/distance-mba-pgdm-chp'),
								'Online MBA' => array('type'=>'url','title'=>'Online MBA', 'url'=>SHIKSHA_HOME.'/online-mba-pgdm-chp'),
								'Part-Time MBA' => array('type'=>'url','title'=>'Part-Time MBA', 'url'=>SHIKSHA_HOME.'/part-time-mba-pgdm-chp'),
                                'All Management Courses'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/business-management-studies/colleges/colleges-india')
							),
                            'secondColumn' => array(
                                'Top Colleges' => array('type'=>'heading'),
                                'Christ University'        => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/christ-university-bangalore-421'),
                                'Mount Carmel College'        => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/mount-carmel-college-mcc-bangalore-palace-road-24896'),
                                'Ethiraj College'        => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/ethiraj-college-for-women-ethiraj-egmore-chennai-12875'),
                                'BBA College Rankings'        => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/bba/ranking/top-bba-colleges-in-india/93-2-0-0-0'),
                            ),
                            'thirdColumn' => array(
                            	'Exams' => array('type'=>'heading'),
                            	'SET BBA' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/bba/set-bba-exam'),
                            	'NPAT BBA' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/bba/npat-bba-exam'),
                            	'SUAT BBA' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/bba/suat-bba-exam'),
                            	'DU JAT' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/bba/du-jat-exam'),
								'All Management Exams' => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/business-management-studies/exams-st-1')

                            )
                        ),
                        'secondRow' => array(
                            'firstColumn' => array(
                            	'Popular Specializations' => array('type'=>'heading'),
                            	'Finance'=> array('type'=>'url', 'url'=>SHIKSHA_HOME.'/business-management-studies/finance-chp'),
                            	'Sales & Marketing'=> array('type'=>'url', 'url'=>SHIKSHA_HOME.'/business-management-studies/sales-marketing-chp'),
                            	'Entrepreneurship'=> array('type'=>'url', 'url'=>SHIKSHA_HOME.'/business-management-studies/entrepreneurship-courses-chp'),
                            	'Digital Marketing'=> array('type'=>'url', 'url'=>SHIKSHA_HOME.'/business-management-studies/digital-marketing-chp'),
                            	'Operations'=> array('type'=>'url', 'url'=>SHIKSHA_HOME.'/business-management-studies/operations-chp'),
                            	'Infrastructure'=> array('type'=>'url', 'url'=>SHIKSHA_HOME.'/business-management-studies/infrastructure-courses-chp'),
                            	'Human Resources'=> array('type'=>'url', 'url'=>SHIKSHA_HOME.'/business-management-studies/human-resources-chp'),
                            	'Telecom'=> array('type'=>'url', 'url'=>SHIKSHA_HOME.'/business-management-studies/telecom-courses-chp'),
                            	'Business Analytics'=> array('type'=>'url', 'url'=>SHIKSHA_HOME.'/business-management-studies/business-analytics-chp'),
                            	'Supply Chain'=> array('type'=>'url', 'url'=>SHIKSHA_HOME.'/business-management-studies/supply-chain-chp'),
                            	'Retail'=> array('type'=>'url', 'url'=>SHIKSHA_HOME.'/business-management-studies/retail-courses-chp'),
                            	'Import & Export'=> array('type'=>'url', 'url'=>SHIKSHA_HOME.'/business-management-studies/import-export-chp'),
                            	'International Business'=> array('type'=>'url', 'url'=>SHIKSHA_HOME.'/business-management-studies/international-business-chp'),
                            	'Business Economics'=> array('type'=>'url', 'url'=>SHIKSHA_HOME.'/business-management-studies/business-economics-chp'),
                            	'Family Business'=> array('type'=>'url', 'url'=>SHIKSHA_HOME.'/business-management-studies/family-business-courses-chp'),
                            	'Materials Management'=> array('type'=>'url', 'url'=>SHIKSHA_HOME.'/business-management-studies/materials-management-chp'),
                            	'Rural Management'=> array('type'=>'url', 'url'=>SHIKSHA_HOME.'/business-management-studies/rural-management-chp'),
                            	'Textile Management'=> array('type'=>'url', 'url'=>SHIKSHA_HOME.'/business-management-studies/textile-management-chp'),
                            	'Agriculture & Food Business'=> array('type'=>'url', 'url'=>SHIKSHA_HOME.'/business-management-studies/agriculture-food-business-chp'),
                            	'All Management Specializations'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/business-management-studies/colleges/colleges-india')
                            ),
                            'secondColumn' => array(
                                'Featured Colleges' => array('type'=>'heading'),
                            ),
                            'thirdColumn' => array(
                                'Resources'       => array('type'=>'heading'),
                                'Ask a Question' => array('type' => 'url', 'url'=>SHIKSHA_HOME."/tags/business-management-studies-tdp-17"),
                            	'Discussions' => array('type' => 'url', 'url'=>SHIKSHA_HOME.'/tags/business-management-studies-tdp-17?type=discussion'),
                            	'News and Articles' => array('type' => 'url', 'url'=>SHIKSHA_HOME.'/business-management-studies/articles-st-1'),
                            	'Trends in Business & Management Studies' => array('type' => 'url', 'url'=>SHIKSHA_HOME.'/analytics/ShikshaTrends/showETP/stream/1')
                            )
                        )
                    ),
				'IT & Software' => array(
						'url'=>SHIKSHA_HOME.'/it-software-chp',
                        'firstRow'  => array(
                            'firstColumn' => array(
                                'Popular Courses'      => array('type'=>'heading'),
								'BCA ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/bca-chp'),
								'B.Sc. in IT & Software' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/it-software/b-sc-in-it-software-chp'),
								'Distance BCA' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/distance-correspondence-bca-chp'),
								'MCA ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mca-chp'),
								'M.Sc. in IT & Software ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/it-software/m-sc-in-it-software-chp'),
								'Part-Time MCA ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/part-time-mca-chp'),
								'Distance MCA ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/distance-correspondence-mca-chp'),
								'CCNA ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/ccna-chp'),
								'DOEACC \'O\' Level ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/doeacc-o-level-chp'),
                                'All IT Courses'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/it-software/colleges/colleges-india')

                            ),
                            'secondColum' => array(
                                'Top Colleges'    => array('type'=>'heading'),
                                'Aptech'        => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/aptech-computer-education-mumbai-andheri-west-27320'),
                                'Jetking'        => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/jetking-infotrain-ltd-borivali-west-mumbai-30045'),
                                'BCA College Rankings'        => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/it-software/ranking/top-bca-colleges-in-india/96-2-0-0-0')
                            ),
                            'thirdColumn' => array(
                            	'Exams' => array('type'=>'heading'),
								'NIMCET' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/it-software/nimcet-exam'),
								'WBJEE JECA' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/it-software/wbjee-jeca-exam'),
								'All IT & Software Exams' => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/it-software/exams-st-8')
                            )
                        ),
                        'secondRow' => array(
                            'firstColumn' => array(
                                'Popular Specializations' => array('type'=>'heading'),
                                'AI & Robotics' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/it-software/ai-robotics-chp'),
								'Big Data & Analytics ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/it-software/big-data-analytics-chp'),
								'CAD / CAM / CAE ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/it-software/cad-cam-cae-chp'),
								'Cloud Computing ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/it-software/cloud-computing-chp'),
								'CRM ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/it-software/crm-customer-relationship-management-chp'),
								'Databases ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/it-software/databases-chp'),
								'ERP ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/it-software/erp-enterprise-resource-planning-chp'),
								'Game Design ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/it-software/game-design-chp'),
								'IT Service Management' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/it-software/it-service-management-chp'),
								'Mobile App Development ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/it-software/mobile-app-development-chp'),
								'Networking, Hardware & Security' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/it-software/networking-hardware-security-chp'),
								'Office Suite' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/it-software/office-suite-chp'),
								'Operating Systems' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/it-software/operating-systems-chp'),
								'Programming ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/it-software/programming-chp'),
								'Project Management' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/it-software/project-management-chp'),
								'Quality Assurance & Testing' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/it-software/quality-assurance-testing-chp'),
								'All IT Specializations'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/it-software/colleges/colleges-india')
                            ),
                            'secondColumn' => array(
                               'Featured Colleges' => array('type'=>'heading')
                            ),
                            'thirdColumn' => array(
								'Resources' => array('type'=>'heading'),
                            	'Ask a Question' => array('type'=>'url', 'url'=>SHIKSHA_HOME."/tags/information-technology-tdp-266"),
                            	'Discussions' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/tags/information-technology-tdp-266?type=discussion'),
                            	'News and Articles' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/information-technology/articles-st-8'),
                            	'Trends in IT & Software' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/analytics/ShikshaTrends/showETP/stream/8')
                            	)
                        )
                    ),
				'Humanities & Social Sciences' => array(
						'url'=>SHIKSHA_HOME.'/humanities-social-sciences-chp',
                        'firstRow'  => array(
                            'firstColumn' => array(
                                'Popular Courses'          => array('type'=>'heading'),
								'B.A.' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-a-bachelor-of-arts-chp'),
								'B.Sc. in Humanities & Social Sciences' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/humanities-social-sciences/b-sc-in-humanities-social-sciences-chp'),
								'BSW ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/bsw-bachelor-of-social-work-chp'),
								'M.A.' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/m-a-chp'),
								'M.Phil' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/m-phil-master-of-philosophy-chp'),
								'M.Sc. in Humanities & Social Sciences' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/humanities-social-sciences/m-sc-in-humanities-social-sciences-chp'),
								'MSW ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/msw-master-of-social-work-chp'),
                                'All Humanities Courses'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/humanities-social-sciences/colleges/colleges-india')

                            ),
                            'secondColumn' => array(
                                'Top Colleges' => array('type'=>'heading'),
                                'Loyola College' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/loyola-college-lc-chennai-nungambakkam-1108'),
                                'Fergusson College' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/fergusson-college-fc-pune-fergusson-college-road-25748'),
                                'Hansraj College' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/hans-raj-college-kamla-nagar-delhi-3062'),
                                'Arts & Humanities College Rankings' => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/humanities-social-sciences/ranking/top-arts-colleges-in-india/95-2-0-0-0'),
                            ),
                            'thirdColumn' => array(
                                'Exams' => array('type'=>'heading'),
								'JNUEE' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/humanities-social-sciences/jnuee-exam'),
								'DUET' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/humanities-social-sciences/duet-exam'),
								'PUBDET' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/humanities-social-sciences/pubdet-exam'),
                            	)
                            ),
                            'secondRow' => array(
                            'firstColumn' => array(
                                'Popular Specializations' => array('type'=>'heading'),
                                'Anthropology ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/humanities-social-sciences/anthropology-chp'),
								'Archaeology ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/humanities-social-sciences/archaeology-chp'),
								'Communication Studies' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/humanities-social-sciences/communication-studies-chp'),
								'Economics ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/humanities-social-sciences/economics-chp'),
								'Geography ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/humanities-social-sciences/geography-chp'),
								'History ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/humanities-social-sciences/history-chp'),
								'Languages ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/humanities-social-sciences/languages-chp'),
								'Library & Information Science' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/humanities-social-sciences/library-information-science-chp'),
								'Linguistics ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/humanities-social-sciences/linguistics-chp'),
								'Literature ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/humanities-social-sciences/literature-chp'),
								'Philosophy ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/humanities-social-sciences/philosophy-chp'),
								'Political Science ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/humanities-social-sciences/political-science-chp'),
								'Psychology ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/humanities-social-sciences/psychology-chp'),
								'Religious Studies' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/humanities-social-sciences/religious-studies-chp'),
								'Rural Studies' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/humanities-social-sciences/rural-studies-chp'),
								'Social Work ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/humanities-social-sciences/social-work-chp'),
								'Sociology ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/humanities-social-sciences/sociology-chp'),
                                'All Humanities Specializations'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/humanities-social-sciences/colleges/colleges-india')

                        			),
							'secondColumn' => array(
									'Featured Colleges' => array('type'=>'heading')
								),
							'thirdColumn' => array(
								'Resources'       => array('type'=>'heading'),
                                'Ask a Question' => array('type'=>'url', 'url'=>SHIKSHA_HOME."/tags/humanities-social-sciences-tdp-429459"),
                                'Discussions'    => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/tags/humanities-social-sciences-tdp-429459?type=discussion'),
                                'News and Articles' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/arts-humanities/articles-st-9'),
                                'Trends in Humanities & Social Sciences' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/analytics/ShikshaTrends/showETP/stream/9'),
                                'Delhi University Cut-Offs '.date('Y') => array('type' => 'url', 'url' => SHIKSHA_HOME.'/university/university-of-delhi-24642/cutoff'),
                                )
						)
                   ),
				'Arts (Fine/Visual/Performing)' => array(
						'url'=>SHIKSHA_HOME.'/arts-fine-visual-performing-chp',
						'firstRow'  => array(
                            'firstColumn'  => array(
                                'Popular Courses'  => array('type'=>'heading'),
								'BFA ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/bfa-bachelor-of-fine-arts-chp'),
								'MFA ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mfa-master-of-fine-arts-chp'),  
	                            'All Arts Courses'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/arts-fine-visual-performing/colleges/colleges-india')

                            ),
                            'secondColumn' => array(
                                'Top Colleges'                              => array('type'=>'heading'),
                                'Stella Maris College' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/stella-maris-college-cathedral-road-chennai-3786'),
                                'Sir JJ Institute of Applied Art' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/sir-j-j-institute-of-applied-art-d-n-road-fort-mumbai-51259'),
                                'L S Raheja School of Art' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/l-s-raheja-school-of-art-worli-lsrsaw-worli-mumbai-24427'),
                            ),
                            'thirdColumn'   => array(
                                'Exams' => array('type'=>'heading'),
								'Jamia Millia Islamia Entrance' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mass-communication-media/jamia-millia-islamia-entrance-exam'),
                            )
                        ),
                        'secondRow' => array(
                            'firstColumn'  => array(
                                'Popular Specializations' => array('type'=>'heading'),
                                'Applied Arts' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/arts-fine-visual-performing/applied-arts-chp'),
								'Art History & Aesthetics' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/arts-fine-visual-performing/art-history-aesthetics-chp'),
								'Ceramics ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/arts-fine-visual-performing/ceramics-chp'),
								'Dance & Choreography ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/arts-fine-visual-performing/dance-choreography-chp'),
								'Decorative Arts' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/arts-fine-visual-performing/decorative-arts-chp'),
								'Film Making' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/arts-fine-visual-performing/film-making-chp'),
								'Graphics Art' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/arts-fine-visual-performing/graphics-art-chp'),
								'Murals ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/arts-fine-visual-performing/murals-chp'),
								'Music ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/arts-fine-visual-performing/music-chp'),
								'Painting & Drawing ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/arts-fine-visual-performing/painting-drawing-chp'),
								'Photography ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/arts-fine-visual-performing/photography-chp'),
								'Sculpture ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/arts-fine-visual-performing/sculpture-chp'),
								'Theatre' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/arts-fine-visual-performing/theatre-chp'),
                                'All Arts Specializations'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/arts-fine-visual-performing/colleges/colleges-india')

                            ),
                            'secondColumn' => array(
                                'Featured Colleges' => array('type'=>'heading')
                            ),
                            'thirdColumn'   => array(
                            	'Resources'      => array('type'=>'heading'),
                                'Ask a Question' => array('type'=>'url', 'url'=>SHIKSHA_HOME."/tags/arts-fine-visual-performing-tdp-429912"),
                                'Discussions' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/tags/arts-fine-visual-performing-tdp-429912?type=discussion'),
                                'News and Articles' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/arts-humanities/articles-st-10'),
                                'Trends in Arts ( Fine / Visual / Performing )' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/analytics/ShikshaTrends/showETP/stream/10')
                            )
                        )
                    ),
				'Science' => array(
						'url'=>SHIKSHA_HOME.'/science-chp',
						'firstRow'  => array(
                            'firstColumn' => array(
                                'Popular Courses'      => array('type'=>'heading'),
								'BSc ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-sc-chp'),
								'MSc ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/m-sc-chp'),
								'Distance B.Sc. ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/distance-correspondence-b-sc-chp'),
								'Distance M.Sc.' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/distance-correspondence-m-sc-chp'),
                                'All Science Courses'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/science/colleges/colleges-india')

                            ),
                            'secondColum' => array(
                                'Top Colleges'    => array('type'=>'heading'),
                                'Fergusson College'        => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/fergusson-college-fc-pune-fergusson-college-road-25748'),
                                'Miranda House'        => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/miranda-house-north-campus-delhi-3090'),
                                'St. Stephen\'s College'        => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/st-stephen-s-college-north-campus-delhi-23933'),
                                'Science College Rankings' => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/science/ranking/top-science-colleges-in-india/101-2-0-0-0'),
                            ),
                            'thirdColumn' => array(
                            	'Exams' => array('type'=>'heading'),
								'MCAER CET' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/science/mcaer-cet-exam'),
								'NEST' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/science/nest-exam'),
								'IIT JAM' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/science/iit-jam-exam'),
								'JEST' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/science/jest-exam'),
								'All Science Exams' => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/science/exams-st-11')
                            )
                        ),
                        'secondRow' => array(
                            'firstColumn' => array(
                            ),
                            'secondColumn' => array(
                               'Featured Colleges' => array('type'=>'heading')
                            ),
                            'thirdColumn' => array(
	                        	'Resources' => array('type'=>'heading'),
	                        	'Ask a Question' => array('type'=>'url', 'url'=>SHIKSHA_HOME."/tags/science-tdp-18"),
	                        	'Discussions' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/tags/science-tdp-18?type=discussion'),
	                        	'News and Articles' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/science/articles-st-11'),
	                        	'Trends in Science' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/analytics/ShikshaTrends/showETP/stream/11'),
	                        	'Delhi University Cut-Offs '.date('Y') => array('type' => 'url', 'url' => SHIKSHA_HOME.'/university/university-of-delhi-24642/cutoff'),
	                        )
                        )
                    ),
				'Architecture & Planning' => array(
						'url'=>SHIKSHA_HOME.'/architecture-planning-chp',
                        'firstRow'  => array(
                            'firstColumn'  => array(
                                'Popular Courses' => array('type'=>'heading'),
								'B.Arch' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-arch-bachelor-of-architecture-chp'),
								'M.Arch' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/m-arch-master-of-architecture-chp'),
								'M.Plan' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/m-plan-master-of-planning-chp'),
                                'All Architecture Courses'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/architecture-planning/colleges/colleges-india')

                            ),
                            'secondColumn' => array(
                                'Top Colleges' => array('type'=>'heading'),
                                'SPA Delhi' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/school-of-planning-and-architecture-delhi-25068'),
                                'Sir J J College of Architecture' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/sir-j-j-college-of-architecture-mumbai-24830'),
                                'Chandigarh College of Architecture' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/chandigarh-college-of-architecture-3568'),
                            ),
                            'thirdColumn' => array(
                            	'Exams' => array('type'=>'heading'),
								'AAT' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/architecture-planning/aat-exam'),
								'NATA' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-tech/nata-exam'),
								'UPAT' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/architecture-planning/upat-exam'),
								'All Architecture & Planning Exams' => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/architecture-planning/exams-st-12')
                            )
                        ),
                        'secondRow' => array(
                            'firstColumn'  => array(
                                'Popular Specializations' => array('type'=>'heading'),
								'Environmental ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/architecture-planning/environmental-chp'),
								'Landscape ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/architecture-planning/landscape-chp'),
								'Rural (Regional)' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/architecture-planning/rural-regional-chp'),
								'Urban ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/architecture-planning/urban-chp'),
                                'All Architecture Specializations'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/architecture-planning/colleges/colleges-india')

                            ),
                            'secondColumn' => array(
                                'Featured Colleges' => array('type'=>'heading')
                            ),
							'thirdColumn' => array(
								'Resources' => array('type'=>'heading'),
                            	'Ask a Question' => array('type'=>'url', 'url'=>SHIKSHA_HOME."/tags/architecture-tdp-21"),
                            	'Discussions' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/tags/architecture-tdp-21?type=discussion'),
                            	'News and Articles' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/architecture/articles-st-12'),
                            	'Trends in Architecture & Planning' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/analytics/ShikshaTrends/showETP/stream/12')
                            	)
                        )
                    ),
                'Accounting & Commerce' => array(
						'url'=>SHIKSHA_HOME.'/accounting-commerce-chp',
                        'firstRow' => array(
                            'firstColumn' => array(
                                'Popular Courses'   => array('type'=>'heading'),
								'B.Com' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-com-chp'),
								'M.Com' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/m-com-chp'),
								'CA ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/ca-chartered-accountant-chp'),
								'CS ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/cs-company-secretary-chp'),
								'Diploma in Accounting' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/accounting-commerce/diploma-in-accounting-chp'),
								'Diploma in Taxation' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/accounting-commerce/diploma-in-taxation-chp'),
                                'All Accounting & Commerce Courses'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/accounting-commerce/colleges/colleges-india')

                                                   ),
                            'secondColumn' => array(
                                'Top Colleges'    => array('type'=>'heading'),
                                'MCC Chennai' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/madras-christian-college-tambaram-sanatorium-chennai-5946'),
                                'Hansraj College' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/hans-raj-college-kamla-nagar-delhi-3062'),
                                'Hindu College' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/hindu-college-north-campus-delhi-23907'),
                                'SRCC Delhi' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/shri-ram-college-of-commerce-maurice-nagar-delhi-23930'),
                                'Commerce College Rankings' => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/accounting-commerce/ranking/top-commerce-colleges-in-india/97-2-0-0-0')
                                                    ),
                            'thirdColumn' => array(
                                'Exams' => array('type'=>'heading'),
								'ICAI' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/accounting-commerce/accounting/icai-exam-exam'),
								'ICSI' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/accounting-commerce/accounting/icsi-exam'),
								'ICWAI' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/accounting-commerce/accounting/icwai-exam'),
								'All Accounting & Commerce Exams' => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/accounting-commerce/exams-st-13')
                            )
                        ),
                        'secondRow' => array(
                            'firstColumn' => array(
                                'Popular Specializations'                => array('type'=>'heading'),
								'Accounting ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/accounting-commerce/accounting-chp'),
								'Taxation ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/accounting-commerce/taxation-chp'),
                                'All Accounting & Commerce Specializations'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/accounting-commerce/colleges/colleges-india')

                            ),
                            'secondColumn' => array(
                                'Featured Colleges' => array('type'=>'heading')
                            ),
                            'thirdColumn' => array(
                            	'Resources'         => array('type'=>'heading'),
                                'Ask a Question'    => array('type'=>'url', 'url'=>SHIKSHA_HOME."/tags/accounting-tdp-55"),
                                'Discussions'       => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/tags/accounting-tdp-55?type=discussion'),
                                'News and Articles' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/accounting/articles-st-13'),
                                'Trends in Accounting & Commerce' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/analytics/ShikshaTrends/showETP/stream/13'),
                                'Delhi University Cut-Offs '.date('Y') => array('type' => 'url', 'url' => SHIKSHA_HOME.'/university/university-of-delhi-24642/cutoff')
                                )
                        )
                    ),
                'Banking, Finance & Insurance' => array(
						'url'=>SHIKSHA_HOME.'/banking-finance-insurance-chp',
                        'firstRow'  => array(
                            'firstColumn' => array(
                                'Popular Courses'           => array('type'=>'heading'),
								'CFA ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/cfa-chp'),
								'CFP ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/cfp-certified-financial-planner-chp'),
								'Diploma in Banking, Finance & Insurance' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/diploma-in-banking-finance-insurance-chp'),
                                'All Banking & Finance Courses'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/banking-finance-insurance/colleges/colleges-india')

                            ),
                            'secondColumn' => array(
                                'Top Colleges'         => array('type'=>'heading'),
                                'AIMA Delhi' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/aima-centre-for-management-education-lodi-colony-delhi-2864'),
                                'XIME Bangalore' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/xavier-institute-of-management-and-entrepreneurship-bangalore-hosur-road-28230'),
                                'ISBF' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/indian-school-of-business-and-finance-lajpat-nagar-delhi-27907'),
                            ),
                            'thirdColumn' => array(
                                'Exams' => array('type'=>'heading'),
								'CFA' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/banking-finance-insurance/cfa-exam'),
                            )
                        ),
                        'secondRow' => array(
                            'firstColumn' => array(
                            ),
                            'secondColumn' => array(
                                'Featured Colleges' => array('type'=>'heading')
                            ),
							'thirdColumn' => array(
								'Resources'          => array('type'=>'heading'),
                                'Ask a Question'    => array('type'=>'url', 'url'=>SHIKSHA_HOME."/tags/banking-finance-insurance-tdp-430369"),
                                'Discussions'       => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/tags/banking-finance-insurance-tdp-430369?type=discussion'),
                                'News and Articles' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/banking-finance-insurance/articles-st-14'),
                                'Trends in Banking, Finance & Insurance' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/analytics/ShikshaTrends/showETP/stream/14')
                                )
                        )
                    ),
                'Aviation' => array(
						'url'=>SHIKSHA_HOME.'/aviation-chp',
                        'firstRow'  => array(
                            'firstColumn' => array(
                                'Popular Specializations'  => array('type'=>'heading'),
								'Cabin Crew / Air Hostess' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/aviation/cabin-crew-air-hostess-chp'),
								'Cargo Management' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/aviation/cargo-management-chp'),
								'Flying / Pilot Training' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/aviation/flying-pilot-training-chp'),
								'Ground Services' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/aviation/ground-services-chp'),
								'Aircraft Maintenance Engineering' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/aviation/aircraft-maintenance-engineering-chp'),
                                'All Aviation Courses'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/aviation/colleges/colleges-india')

                            ),
                            'secondColumn' => array(
                                'Top Colleges' => array('type'=>'heading'),
                                'Indian Aviation Academy' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/indian-aviation-academy-andheri-west-mumbai-24815'),
                                'Rajiv Gandhi Aviation Academy' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/rajiv-gandhi-aviation-academy-bowenpally-hyderabad-21635'),
                                'Hindustan Aviation Academy' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/hindustan-aviation-academy-marathahalli-bangalore-22133'),
                                'Frankfinn' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/frankfinn-institute-of-air-hostess-training-rajouri-garden-delhi-47223'),
                            ),
                            'thirdColumn' => array(
                                'Resources'       => array('type'=>'heading'),
                                'Ask a Question' => array('type'=>'url', 'url'=>SHIKSHA_HOME."/tags/aviation-tdp-24"),
                                'Discussions'    => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/tags/aviation-tdp-24?type=discussion'),
                                'News and Articles' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/aviation/articles-st-15'),
                                'Trends in Aviation' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/analytics/ShikshaTrends/showETP/stream/15')
                            )
                        ),
                        'secondRow' => array(
                            'firstColumn' => array(
                            ),
                            'secondColumn' => array(
                                'Featured Colleges' => array('type'=>'heading')
                            ),
							'thirdColumn' => array()
                        )
                    ),
				'Teaching & Education' => array(
						'url'=>SHIKSHA_HOME.'/teaching-education-chp',
                        'firstRow'  => array(
                            'firstColumn' => array(
                                'Popular Courses'      => array('type'=>'heading'),
								'B.Ed' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-ed-chp'),
								'B.P.Ed' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-p-ed-bachelor-of-physical-education-chp'),
								'B.Voc' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/b-voc-bachelor-of-vocational-education-chp'),
								'M.Ed ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/m-ed-master-of-education-chp'),
								'M.P.Ed' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/m-p-ed-master-of-physical-education-chp'),
								'D.Ed ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/d-ed-diploma-in-education-chp'),
                                'All Teaching & Education Courses'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/teaching-education/colleges/colleges-india')
                            ),
                            'secondColumn' => array(
                                'Top Colleges'    => array('type'=>'heading'),
                                'LSR Delhi'        => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/lady-shri-ram-college-for-women-lajpat-nagar-delhi-23895'),
                                'Lady Irwin College' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/lady-irwin-college-pragati-maidan-delhi-23897'),
                                'Loreto College Kolkata' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/loreto-college-park-street-kolkata-47646'),
                            ),
                            'thirdColumn' => array(
                               'Exams' => array('type'=>'heading'),
								'CTET' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/teaching-education/ctet-exam'),
								'TSTET' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/teaching-education/tstet-exam'),
								'UGC NET' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/teaching-education/ugc-net-exam'),
								'All Teaching & Education Exams' => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/teaching-education/exams-st-16') 
                            )
                        ),
                        'secondRow' => array(
                            'firstColumn' => array(
                                'Popular Specializations' => array('type'=>'heading'),
								'Middle School' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/teaching-education/middle-school-chp'),
								'Physical Education' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/teaching-education/physical-education-chp'),
								'Pre Primary & Primary School ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/teaching-education/pre-primary-primary-school-chp'),
								'Secondary & Sr. Secondary School' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/teaching-education/secondary-sr-secondary-school-chp'),
								'Special Education' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/teaching-education/special-education-chp'),
								'Vocational Education' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/teaching-education/vocational-education-chp'),
                                'All Teaching & Education Specializations'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/teaching-education/colleges/colleges-india')

                            ),
                            'secondColumn' => array(
                               'Featured Colleges' => array('type'=>'heading')
                            ),
                            'thirdColumn' => array(
                            	'Resources'       => array('type'=>'heading'),
                                'Ask a Question' => array('type'=>'url', 'url'=>SHIKSHA_HOME."/tags/teaching-education-tdp-16"),
                                'Discussions'    => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/tags/teaching-education-tdp-16?type=discussion'),
                                'News and Articles' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/teaching-education/articles-st-16'),
                                'Trends in Teaching & Education' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/analytics/ShikshaTrends/showETP/stream/16')
                            )
                        )
                    ),
				'Nursing' => array(
						'url'=>SHIKSHA_HOME.'/nursing-chp',
                        'firstRow'  => array(
                            'firstColumn' => array(
                                'Popular Courses'      => array('type'=>'heading'),
								'B.Sc. in Nursing ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/nursing/b-sc-in-nursing-chp'),
								'M.Sc. in Nursing' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/nursing/m-sc-in-nursing-chp'),
                                'All Nursing Courses'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/nursing/colleges/colleges-india')

                            ),
                            'secondColumn' => array(
                                'Resources'    => array('type'=>'heading'),
                                'Ask a Question' => array('type'=>'url', 'url'=>SHIKSHA_HOME."/tags/nursing-tdp-83"),
                                'Discussions'    => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/tags/nursing-tdp-83?type=discussion'),
                                'News and Articles' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/nursing/articles-st-17'),
                                'Trends in Nursing' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/analytics/ShikshaTrends/showETP/stream/17')
                            )
                        ),
                        'secondRow' => array(
                            'firstColumn' => array(
                                'Popular Specializations' => array('type'=>'heading'),
                                'Nursing & Midwifery' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/nursing/nursing-midwifery-chp'),
                                'All Nursing Specializations'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/nursing/colleges/colleges-india')

                            ),
                            'secondColumn' => array(
                               'Featured Colleges' => array('type'=>'heading')
                            )
                        )
                    ),
				'Medicine & Health Sciences' => array(
						'url'=>SHIKSHA_HOME.'/medicine-health-sciences-chp',
                        'firstRow'  => array(
                            'firstColumn' => array(
                                'Popular Courses'      => array('type'=>'heading'),
                                'MBBS' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mbbs-chp'),
								'MD ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/md-chp'),
								'BMLT ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/bmlt-bachelor-in-medical-laboratory-technology-chp'),
								'MPT ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mpt-master-of-physiotherapy-chp'),
								'MPH ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/mph-master-of-public-health-chp'),
                                'All Medical Courses'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/medicine-health-sciences/colleges/colleges-india')
                            ),
                            'secondColumn' => array(
                                'Top Colleges' => array('type'=>'heading'),
                                'AIIMS Delhi' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/all-india-institute-of-medical-sciences-aiims-delhi-safdarjang-enclave-24433'),
                                /*'JIPMER Pondicherry' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/jawaharlal-institute-of-post-graduate-medical-education-and-research-pondicherry-1294'),*/
                                'AFMC Pune' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/college/armed-forces-medical-college-wanowrie-pune-22431'),
                                'Medical College Rankings' => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/medicine-health-sciences/ranking/top-medical-colleges-in-india/100-2-0-0-0')
                            ),
                            'thirdColumn' => array(
                               'Exams' => array('type'=>'heading'),
								'NEET UG' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/medicine-health-sciences/medicine/neet-ug-exam'),
								'AIIMS MBBS' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/medicine-health-sciences/aiims-mbbs-exam'),
								'JIPMER MBBS' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/medicine-health-sciences/medicine/jipmer-mbbs-exam'),
								'All Medicine Exams' => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/medicine-health-sciences/exams-st-18') 
                            )
                        ),
                        'secondRow' => array(
                            'firstColumn' => array(
                                'Popular Specializations' => array('type'=>'heading'),
								'Alternative Medicine' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/medicine-health-sciences/alternative-medicine-chp'),
								'Dental ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/medicine-health-sciences/dental-chp'),
								'Dietics & Nutrition' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/medicine-health-sciences/dietics-nutrition-chp'),
								'Medicine ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/medicine-health-sciences/medicine-chp'),
								'Paramedical ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/medicine-health-sciences/paramedical-chp'),
								'Pharmacy ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/medicine-health-sciences/pharmacy-chp'),
								'Physiotherapy ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/medicine-health-sciences/physiotherapy-chp'),
								'Public Health & Management' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/medicine-health-sciences/public-health-management-chp'),
                                'All Medical Specializations'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/medicine-health-sciences/colleges/colleges-india')

                            ),
                            'secondColumn' => array(
                               'Featured Colleges' => array('type'=>'heading')
                            ),
                            'thirdColumn' => array(
                            	'Resources'    => array('type'=>'heading'),
                                'Ask a Question' => array('type'=>'url', 'url'=>SHIKSHA_HOME."/tags/medicine-health-sciences-tdp-430824"),
                                'Discussions'    => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/tags/medicine-health-sciences-tdp-430824?type=discussion'),
                                'News and Articles' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/medical/articles-st-18'),
                                'Trends in Medicine & Health Sciences' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/analytics/ShikshaTrends/showETP/stream/18')
                            )
                        )
                    ),
                'Beauty & Fitness' => array(
						'url'=>SHIKSHA_HOME.'/beauty-fitness-chp',
                        'firstRow'  => array(
                            'firstColumn' => array(
                                'Popular Specializations' => array('type'=>'heading'),
                                'Beauty Culture & Cosmetology' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/beauty-fitness/beauty-culture-cosmetology-chp'),
								'Massage & Spa Therapy ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/beauty-fitness/massage-spa-therapy-chp'),
								'Yoga ' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/beauty-fitness/yoga-chp'),
                                'All Beauty & Fitness Courses'     => array('type'=>'link', 'url'=>SHIKSHA_HOME.'/beauty-fitness/colleges/colleges-india')

                            ),
                            'secondColumn' => array(
                                'Resources'    => array('type'=>'heading'),
                                'Ask a Question' => array('type'=>'url', 'url'=>SHIKSHA_HOME."/tags/beauty-tdp-114"),
                                'Discussions'    => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/tags/beauty-tdp-114?type=discussion'),
                                'News and Articles' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/beauty/articles-st-19'),
                                'Trends in Beauty & Fitness' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/analytics/ShikshaTrends/showETP/stream/19')
                            ),
                            'thirdColumn' => array()
                        ),
                        'secondRow' => array(
                            'firstColumn' => array(),
                            'secondColumn' => array(
                               'Featured Colleges' => array('type'=>'heading')
                            ),
                        'thirdColumn' => array()
                        	)
                    ),
                'Universities and Colleges' => array(
                        'firstRow'  => array(
                            'firstColumn' => array(
                                'Top Central Universities' => array('type'=>'heading'),
                                'University of Delhi' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/university-of-delhi-24642'),
                                'JNU Delhi' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/jawaharlal-nehru-university-delhi-4225'),
                                'IGNOU Delhi' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/indira-gandhi-national-open-university-delhi-3030'),
                                'Banaras Hindu University' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/banaras-hindu-university-varanasi-23336'),
                            ),
                            'secondColumn' => array(
                                'Top State Universities' => array('type'=>'heading'),
                                'University of Mumbai'=> array('type'=>'url','url'=>SHIKSHA_HOME.'/university/university-of-mumbai-fort-campus-856'),
                                'Anna University' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/anna-university-chennai-3084'),
                                'Gujarat University' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/gujarat-university-ahmedabad-29851'),
                                'CCS University' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/chaudhary-charan-singh-university-meerut-19694'),
                            ),
                        ),
                        'secondRow' => array(
                            'firstColumn' => array(
                                'Top Private Universities' => array('type'=>'heading'),
                                'Amity University' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/amity-university-noida-41334'),
                                'Galgotias university' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/galgotias-university-greater-noida-37105'),
                                'LPU Jalandhar' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/lovely-professional-university-jalandhar-28499'),
                                'Chandigarh University' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/chandigarh-university-45668')
                            ),
                            'secondColumn' => array(
                                'Top Deemed Universities' => array('type'=>'heading'),
                                'Manipal University' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/manipal-university-30822'),
                                'IISC Bangalore' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/indian-institute-of-science-bangalore-22350'),
                                'SRM University' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/srm-university-chennai-kattankulathur-campus-24749'),
                                'BIT Mesra' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/university/birla-institute-of-technology-mesra-ranchi-24087')
                            ),
                        ),'thirdRow' => array(
                            'firstColumn' => array(
                                'Colleges by State' => array('type'=>'heading'),
                                'Colleges in Maharashtra' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/colleges/maharashtra'),
                                'Colleges in Karnataka' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/colleges/karnataka'),
                                'Colleges in Uttar Pradesh' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/colleges/uttar-pradesh'),
                                'Colleges in Kerala' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/colleges/kerala')
                            ),
                            'thirdColumn' => array(
                                'Colleges by City' => array('type'=>'heading'),
                                'Colleges in Delhi' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/colleges/delhi'),
                                'Colleges in Bangalore' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/colleges/bangalore'),
                                'Colleges in Mumbai' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/colleges/mumbai'),
                                'Colleges in Hyderabad' => array('type'=>'url', 'url'=>SHIKSHA_HOME.'/colleges/hyderabad')
                            ),
                        )
                    ),
                ),
	'Study Abroad'=> array(
		'Colleges' => array(
			'firstRow'=>array(
                            'firstColumn'=>array(
                    'Bachelors'=>array('type'=>'heading'),
					'By course' => array('type'=>'sub-heading'),
					'Btech' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/be-btech-in-abroad-dc11510')
				),
				'secondColumn'=>array(
                                        'Masters'=>array('type'=>'heading'),
					'By course' => array('type'=>'sub-heading'),
					'MBA' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/mba-in-abroad-dc11508'),
					'MS' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/ms-in-abroad-dc11509')
				),
                                'thirdColumn' => array(
                                    'Certificate - Diploma'=>array('type'=>'heading'),
                                )
			),
			'secondRow'=>array(
				'firstColumn'=>
						array(
							'Bachelors'=>array('type'=>'heading', 'hide'=>'yes'),
							'By stream' => array('type'=>'sub-heading'),
							'Business' => array('type'=>'url','title'=>'Buisness','url'=>SHIKSHA_STUDYABROAD_HOME.'/bachelors-of-business-in-abroad-cl1239'),
							'Computers' => array('type'=>'url','title'=>'Computers','url'=>SHIKSHA_STUDYABROAD_HOME.'/bachelors-of-computers-in-abroad-cl1241'),
							'Engineering' => array('type'=>'url','title'=>'Engineering','url'=>SHIKSHA_STUDYABROAD_HOME.'/bachelors-of-engineering-in-abroad-cl1240'),
							'Humanities' => array('type'=>'url','title'=>'Humanities','url'=>SHIKSHA_STUDYABROAD_HOME.'/bachelors-of-humanities-in-abroad-cl1244'),
							'Law' => array('type'=>'url','title'=>'Law','url'=>SHIKSHA_STUDYABROAD_HOME.'/bachelors-of-law-in-abroad-cl1245'),
							'Medicine' => array('type'=>'url','title'=>'Medicine','url'=>SHIKSHA_STUDYABROAD_HOME.'/bachelors-of-medicine-in-abroad-cl1243'),
							'Science' => array('type'=>'url','title'=>'Science','url'=>SHIKSHA_STUDYABROAD_HOME.'/bachelors-of-science-in-abroad-cl1242')
							
						),
					'secondColumn'=>
						array(
							'Masters'=>array('type'=>'heading', 'hide'=>'yes'),
							'By stream' => array('type'=>'sub-heading'),
							'Business' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/masters-of-business-in-abroad-cl1239'),
							'Computers' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/masters-of-computers-in-abroad-cl1241'),
							'Engineering' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/masters-of-engineering-in-abroad-cl1240'),
							'Humanities' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/masters-of-humanities-in-abroad-cl1244'),
							'Law' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/masters-of-law-in-abroad-cl1245'),
							'Medicine' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/masters-of-medicine-in-abroad-cl1243'),
							'Science' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/masters-of-science-in-abroad-cl1242')
							
					),
				'thirdColumn'=>
						array(
                            'Certificate-Diploma'=>array('type'=>'heading', 'hide'=>'yes'),
							'By stream' => array('type'=>'sub-heading'),
							'Business' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/certificate-diploma-of-business-in-abroad-cl1239'),
							'Computers' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/certificate-diploma-of-computers-in-abroad-cl1241'),
							'Engineering' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/certificate-diploma-of-engineering-in-abroad-cl1240'),
							'Humanities' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/certificate-diploma-of-humanities-in-abroad-cl1244'),
							'Law' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/certificate-diploma-of-law-in-abroad-cl1245'),
							'Medicine' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/certificate-diploma-of-medicine-in-abroad-cl1243'),
							'Science' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/certificate-diploma-of-science-in-abroad-cl1242')
							
						),
			),
		),
		'Countries'=>array(
			'firstRow'=>array(
					'firstColumn'=>
						array(
							'Universities by Country' => array('type'=>'heading'),
							'Universities in Australia' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/australia/universities'),
							'Universities in Canada' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/canada/universities'),
							'Universities in Germany' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/germany/universities'),
							'Universities in New Zealand' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/new-zealand/universities'),
							'Universities in Singapore' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/singapore/universities'),
							'Universities in UK' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/uk/universities'),
							'Universities in USA' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/usa/universities'),
							'Explore more countries'=>array('type'=>'urlWithAnchor', 'url'=>SHIKSHA_STUDYABROAD_HOME.'/abroad-countries-countryhome'),
						),
					'secondColumn'=>
						array(
							'Country Home' => array('type'=>'heading'), 
							'Study in USA' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/usa'), 
							'Study in Canada' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/canada'), 
							'Study in Australia' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/australia'), 
							'Study in UK' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/uk'), 
							'Study in Germany' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/germany'), 
							'Study in Singapore' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/singapore'), 
							'Study in New Zealand' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/new-zealand'), 
							'Study in Netherlands' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/netherlands'), 
							'Explore More Countries' => array('type'=>'urlWithAnchor','url'=>SHIKSHA_STUDYABROAD_HOME.'/abroad-countries-countryhome'),
						),
					'thirdColumn'=>
						array(
							'Rankings' => array('type'=>'heading'),
							'Top MBA Colleges in UK' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/top-mba-colleges-in-uk-abroadranking29'),
							'Top MS Colleges in UK' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/top-ms-colleges-in-uk-abroadranking31'),
							'Top MBA Colleges in USA' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/top-mba-colleges-in-usa-abroadranking30'),
							'Top UG Business Schools in USA' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/top-colleges-for-bachelors-of-business-from-usa-abroadranking42'),
							'Top MS Colleges Abroad' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/top-ms-colleges-in-abroad-abroadranking33'),
							'Top Universities Abroad' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/top-universities-in-abroad-abroadranking32')
						)
			)
		),
		'Application Process'=>array(
			'firstRow'=>array(
				'firstColumn'=>
					array(
						'Exams' => array('type'=>'heading'),
						'Language Exams' => array('type'=>'sub-heading'),
						'IELTS' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/exams/ielts'),
						'TOEFL' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/exams/toefl'),
						'PTE' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/exams/pte'),
						'Aptitude Exams' => array('type'=>'sub-heading'),
						'GRE' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/exams/gre'),
						'GMAT' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/exams/gmat'),
						'SAT' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/exams/sat')
					),
				'secondColumn'=>
					array(
						'Application Writing' => array('type'=>'heading'),
						'Statement Of Purpose (SOP)' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/sop-statement-of-purpose-applycontent1701'),
						'Letter Of Recommendation (LOR)' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/lor-recommendation-letter-applycontent2702'),
						'Admission Essays' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/college-admission-essays-applycontent3705'),
						'Student CV' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/resume-writing-tips-applycontent4703'),
						'Student Visa' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/student-visa-guide-applycontent5706')
					),
				),
			'secondRow'=>array(
				'firstColumn'=>array(),
				'secondColumn'=>array()
			),
		),
                'Scholarships'=>array(
			'url'=> SHIKSHA_STUDYABROAD_HOME.'/scholarships',
                        'firstRow'=>array(
                                'firstColumn'=>
                                        array(
                                                'By Course' => array('type'=>'heading'),
                                                'Scholarships for Bachelors' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/scholarships/bachelors-courses'),
                                                'Scholarships for Masters' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/scholarships/masters-courses')
                                        ),
                                'secondColumn'=>
                                        array(
                                                'By Country' => array('type'=>'heading'),
                                                'Scholarships for USA' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/scholarships/usa-cp'),
                                                'Scholarships for Canada' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/scholarships/canada-cp'),
                                                'Scholarships for Australia' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/scholarships/australia-cp'),
                                                'Scholarships for UK' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/scholarships/uk-cp'),
                                                'Scholarships for Germany' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/scholarships/germany-cp'),
                                                'Scholarships for Singapore' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/scholarships/singapore-cp'),
                                                'Scholarships for New Zealand' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/scholarships/new-zealand-cp'),
                                                'Scholarships for Netherlands' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/scholarships/netherlands-cp'),
                                                'Scholarships for Ireland' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/scholarships/ireland-cp'),
                                                'Scholarships for Sweden' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/scholarships/sweden-cp'),
                                                'Scholarships for France' => array('type'=>'url','url'=>SHIKSHA_STUDYABROAD_HOME.'/scholarships/france-cp')
                                        ),
                                ),
                        'secondRow'=>array(
                                'firstColumn'=>array(),
                                'secondColumn'=>array()
                        ),
                ),
	),
	'Counseling'=> array(
		'Get Expert Guidance'=>array(
			'firstRow'=>array(
					'firstColumn'=>array(
						"Ask a Question"=>array('type'=>'url', 'url'=>SHIKSHA_ASK_HOME),
				    "Discussions"=>array('type'=>'url', 'url'=>SHIKSHA_ASK_HOME.'/discussions'),
					),
			),
		),
		'Careers after 12th'=>array(
			'firstRow'=>array(
					'firstColumn'=>array(
						'By Stream' => array('type'=>'heading'),
		            	'Science'=>array('type'=>'click', 'click'=>"openCareerCounsellingPage('Science');",'url'=>SHIKSHA_HOME.'/careers/opportunities'),
		            	'Commerce'=>array('type'=>'click', 'click'=>"openCareerCounsellingPage('Commerce');",'url'=>SHIKSHA_HOME.'/careers/opportunities'),
		            	'Humanities'=>array('type'=>'click', 'click'=>"openCareerCounsellingPage('Humanities');",'url'=>SHIKSHA_HOME.'/careers/opportunities'),
			        ),
			        'secondColumn'=>array(
			        	'Popular Careers' => array('type'=>'heading'),
		            	'Aeronautical Engineer'=>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/careers/aeronautical-engineer-4' ),
		            	'Chartered Accountant'=>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/careers/chartered-accountant-23'),
		            	'Computer Engineer'=>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/careers/computer-engineer-31' ),
		            	'Doctor'=>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/careers/medicine-76'),
		            	'Hotel Manager'=>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/careers/hotel-manager-62'),
		            	'Pilot'=>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/careers/pilot-91'),
		            	'All other careers'=>array('type'=>'urlWithAnchor', 'url'=>SHIKSHA_HOME.'/careers'),
			        ),
				),
			),
                'Courses after 12th'=>array(
                        'firstRow'=>array(
                                'firstColumn'=>array(
                                        'Science Stream'=>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/courses-after-12th/science'),
                                        'Commerce Stream'=>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/courses-after-12th/commerce'),
                                        'Arts Stream'=>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/courses-after-12th/arts'),
                                        'All Class 12th Streams' => array('type'=>'urlWithAnchor', 'url' =>SHIKSHA_HOME.'/courses-after-12th'),
                                ),
                        ),
                ),
                'National Boards'=>array(
                        'firstRow'=>array(
                                'firstColumn'=>array(
                                        'CBSE'=>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/boards/cbse'),
                                        'ICSE'=>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/boards/icse'),
                                        'NIOS'=>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/boards/nios'),
                                        'All Education Boards' => array('type'=>'urlWithAnchor', 'url' =>SHIKSHA_HOME.'/boards'),
                                ),
                        ),
                ),
                'State Boards'=>array(
                        'firstRow'=>array(
                                'firstColumn'=>array(
                                        'UP Board'=>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/boards/up-board'),
                                        'BSEB'=>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/boards/bseb'),
                                        'PSEB'=>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/boards/pseb'),
                                        'RBSE'=>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/boards/rbse'),
                                        'JKBOSE'=>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/boards/jkbose'),
                                ),
                                'secondColumn'=>array(
                                        'GSEB'=>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/boards/gseb'),
                                        'HPBOSE'=>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/boards/hpbose'),
                                        'MPBSE'=>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/boards/mpbse'),
                                        'BIEAP'=>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/boards/bieap'),
                                ),
                                'thirdColumn'=>array(
                                        'BSEH'=>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/boards/bseh'),
                                        'CGBSE'=>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/boards/cgbse'),
                                        'WBBSE'=>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/boards/wbbse'),
					'WBCHSE'=>array('type'=>'url', 'url'=>SHIKSHA_HOME.'/boards/wbchse'),
                                        'All Education Boards'=>array('type'=>'urlWithAnchor', 'url'=>SHIKSHA_HOME.'/boards'),
                                ),
                        ),
                ),
                'Abroad Counseling Service' => array(
                                'url'=>SHIKSHA_STUDYABROAD_HOME.'/apply',
                ),

		)
	);
$newGNBconfigData = $config;
?>
