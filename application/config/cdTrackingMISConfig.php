	<?php
$config['topTiles'] = array(
		'DomesticOverview' => array(
		'Overall India' => array('Total Clients','Total Sales','Total Collections','Total Leads','Total Responses'),
		'North Zone' => array('Total Clients','Total Sales','Total Collections','Total Leads','Total Responses'),
		'South Zone' => array('Total Clients','Total Sales','Total Collections','Total Leads','Total Responses'),
		'East Zone' => array('Total Clients','Total Sales','Total Collections','Total Leads','Total Responses'),
		'West Zone' => array('Total Clients','Total Sales','Total Collections','Total Leads','Total Responses')
		),
		'StudyAbroadCustomerOverview' => array('Total Registrations','Total PageViews','Total Traffic'),
		'DomesticContentOverview' => array('Artcles Published','Total Traffic'),
		'byInstitute' => array('uniqTopTile'=>'Unique Users','bounceTopTile'=>'Bounce Rate','exitTopTile'=>'Exit Rate','appsTopTile'=>'Avg Page Per Session','asdTopTile'=>'Avg Session Duraion','pageviewTopTile'=>'Page Views','regTopTile'=>'Registrations','questionTopTile'=>'Questions','answerTopTile'=>'Answers','digupTopTile'=>'Number of Answers Liked','responseTopTile'=>'Responses','leadTopTile'=>'Leads Delivered','mrTopTile'=>'Matched Responses Delivered'),
		'bySubcatId'  => array('regTopTile'=>'Registrations','questionTopTile'=>'Questions','answerTopTile'=>'Answers','digupTopTile'=>'Number of Answers Liked','responseTopTile'=>'Responses','avgResponse' => 'Avg Response For Paid Course'),
		'byUniversity' => array('uniqTopTile'=>'Unique Users','bounceTopTile'=>'Bounce Rate','exitTopTile'=>'Exit Rate','appsTopTile'=>'Avg Page Per Session','asdTopTile'=>'Avg Session Duraion','pageviewTopTile'=>'Page Views','regTopTile'=>'Registrations','responseTopTile'=>'Responses','leadTopTile'=>'Leads Delivered','mrTopTile'=>'Matched Responses Delivered'),
		'bySubcatId_SA' => array('regTopTile'=>'Registrations','responseTopTile'=>'Responses','avgResponse' => 'Avg Response For Paid Course')
		);

$config['leftMenuArray'] = array(
						'Domestic' => array(
									'className' => "fa-home",
									'children' => array(
														'Overview' => SHIKSHA_HOME."/trackingMIS/cdMIS/customerDeliveryDashBoard/DomesticOverview",
														'By Institute' => SHIKSHA_HOME."/trackingMIS/cdMIS/customerDeliveryDashBoard/byInstitute",
														'By SubCat' => SHIKSHA_HOME."/trackingMIS/cdMIS/customerDeliveryDashBoard/bySubcatId"
													)
									),
						'Study Abroad' => array(
								'className' => "fa-home",
								'children' => array(
														'Overview' => SHIKSHA_HOME."/trackingMIS/cdMIS/customerDeliveryDashBoard/StudyAbroadOverview",
														'By University' => SHIKSHA_HOME."/trackingMIS/cdMIS/customerDeliveryDashBoard/byUniversity",
														'By SubCat'  =>	   SHIKSHA_HOME."/trackingMIS/cdMIS/customerDeliveryDashBoard/bySubcatId_SA",
														'SA Sales' => SHIKSHA_HOME."/trackingMIS/saSalesDashboard/",
									)
							),
						'Actual Delivery' => array(
							'className' => "fa-home",
							'children' => array(
													'By Client' => SHIKSHA_HOME."/trackingMIS/cdMIS/customerDeliveryDashBoard/ActualDelivery"
								)
							)

							/*,
						'Content Delivery' => array(
									'className' => "fa-home",
									'children' => array(
														'By SubCat' => SHIKSHA_HOME."/trackingMIS/cdMIS/dashboard/content-bySubcatId",
														'By Article Id' => SHIKSHA_HOME."/trackingMIS/cdMIS/dashboard/byArticle"
											)
							),
						'Content Delivery - SA'=>array(
							'className' => "fa-home",
									'children' => array(
														'By SubCat' => SHIKSHA_HOME."/trackingMIS/cdMIS/dashboard/SA-Content-bySubcatId",
														'By Article Id' => SHIKSHA_HOME."/trackingMIS/cdMIS/dashboard/SA-Content-byArticleId"
							)
						),
						'Discussions in Content' => array(
							'className' => "fa-home",
									'children' => array(
														'National' =>  SHIKSHA_HOME."/trackingMIS/cdMIS/dashboard/National-Discussions",
														'Study Abroad' => SHIKSHA_HOME."/trackingMIS/cdMIS/dashboard/StudyAbroad-Discussions"
										)
							)*/
					);
$config['Content-leftMenuArray'] = array(
						'Domestic Articles' => array(
									'className' => "fa-home",
									'children' => array(
														'Overview' => SHIKSHA_HOME."/trackingMIS/cdMIS/contentDeliveryDashBoard/contentDomesticOverview",
														'By SubCat' => SHIKSHA_HOME."/trackingMIS/cdMIS/contentDeliveryDashBoard/content-bySubcatId",
														'By Article Id' => SHIKSHA_HOME."/trackingMIS/cdMIS/contentDeliveryDashBoard/byArticle"
											)
							),
						'Study Abroad Articles'=>array(
							'className' => "fa-home",
									'children' => array(
														'Overview' => SHIKSHA_HOME."/trackingMIS/cdMIS/contentDeliveryDashBoard/contentStudyAbroadOverview",
														'By SubCat' => SHIKSHA_HOME."/trackingMIS/cdMIS/contentDeliveryDashBoard/SA-Content-bySubcatId",
														'By Article Id' => SHIKSHA_HOME."/trackingMIS/cdMIS/contentDeliveryDashBoard/SA-Content-byArticleId"
							)
						),
						'Discussions' => array(
							'className' => "fa-home",
									'children' => array(
														'Overview' => SHIKSHA_HOME."/trackingMIS/cdMIS/contentDeliveryDashBoard/DiscussionOverview",
														'By SubCat' =>  SHIKSHA_HOME."/trackingMIS/cdMIS/contentDeliveryDashBoard/NationalDiscussions",
														'By Discussion Id' => SHIKSHA_HOME."/trackingMIS/cdMIS/contentDeliveryDashBoard/byDiscussionId"
														//'Study Abroad' => SHIKSHA_HOME."/trackingMIS/cdMIS/contentDashBoard/StudyAbroadDiscussions"
										)
							)
	);
$config['charts'] = array(
	'byInstitute' => array(
			'PIE_CHART' => array(
						'registration_sourceWise' => array(
								'heading' => 'Registration (Source Wise)',
								'typeHeading' => 'source',
								'countHeading' => 'Total Registrations'
								),
						'registration_deviceWise' => array(
								'heading' => 'Registration (Device Wise)',
								'typeHeading' => 'Device',
								'countHeading' => 'Total Registrations'
								),
						'registration_paidFree' => array(
								'heading'  => 'Registration (Paid/Free)',
								'typeHeading'  => 'type',
								'countHeading' => 'Total Registrations'
							),
								'Traffic-SourceWise' => array(
												'heading' => 'Traffic (Source-Wise)',
												'typeHeading' => 'Traffic Source',
												'countHeading' => 'Total Traffic'
												),
								'Traffic-DeviceWise' => array(
												'heading' => 'Traffic (Device-Wise)',
												'typeHeading' => 'Device Type',
												'countHeading' => 'Total Traffic'
												),
								'response_sourceWise' => array(
								'heading' => 'Response (Source Wise)',
								'typeHeading' => 'source',
								'countHeading' => 'Total Responses'
								),
								/*'PageView-DeviceWise' => array(
										'heading' => 'PageView (Device-Wise)',
										'typeHeading' => 'Device Type',
										'countHeading' => 'Total PageViews'
										),*/
				),
				'LINE_CHART' =>array(
							'leadDelivery' => array(
									'heading' => 'Number of Leads Delivered to Client:'
									),
							'responseData' => array(
									'heading' => 'Number of Responses Generated:'
									),
							'responseDelivery' => array(
									'heading' => 'Number of Responses Delivered to Client:'
									),
									'QuestionResult' => array(
									'heading' => 'Number of Questions Asked:'
									),
								'AnswerResult' => array(
									'heading' => 'Number of Answers answered:'
									),
								
								'DigupResult' => array(
									'heading' => 'Number of Answers Liked:'
									),	
								
							),
				'Top_LINE' => array(
							'trafficData' => array(
									'heading' => 'Unique Sessions'
									),
								'pageviewData' => array(
									'heading' => 'Page Views'
									),
								'bounceRate' => array(
									'heading' => 'Bounce Rate'
									),
								'avgSessionDuration' => array(
									'heading' => 'Average Session Duration(in milli seconds)'
									),
								'avgPagePerSession' => array(
									'heading' => 'Average Page Per Session'
									),
								'exitRateData' => array(
									'heading' => 'Exit Rate'
									),
								'registrationData' => array(
									'heading' => 'Registrations Data'
									),
					)
		),
	'bySubcatId' => array(
			'PIE_CHART' => array(
						'registration_sourceWise' => array(
								'heading' => 'Registration (Source Wise)',
								'typeHeading' => 'source',
								'countHeading' => 'Total Registrations'
								),
						'registration_deviceWise' => array(
								'heading' => 'Registration (Device Wise)',
								'typeHeading' => 'Device',
								'countHeading' => 'Total Registrations'
								),
						'registration_paidFree' => array(
								'heading'  => 'Registration (Paid/Free)',
								'typeHeading'  => 'type',
								'countHeading' => 'Total Registrations'
							),
						'responsePaidFreeWise' => array(
								'heading' => 'Response (Paid and Free)',
								'typeHeading' => 'paidType',
								'countHeading' => 'Total Responses'
								),
						'response_sourceWise' => array(
								'heading' => 'Response (Source Wise)',
								'typeHeading' => 'source',
								'countHeading' => 'Total Responses'
								),
				),
				'LINE_CHART' =>array(
							'leadDelivery' => array(
									'heading' => 'Number of Leads Delivered to Client:'
									),
							'responseData' => array(
									'heading' => 'Number of Responses Generated:'
									),
							'responseDelivery' => array(
									'heading' => 'Number of Responses Delivered to Client:'
									),
									'QuestionResult' => array(
									'heading' => 'Number of Questions Asked:'
									),
								'AnswerResult' => array(
									'heading' => 'Number of Answers answered:'
									),
								
								'DigupResult' => array(
									'heading' => 'Number of Answers Liked:'
									),	
								
							),
				'Top_LINE' => array(
							'trafficData' => array(
									'heading' => 'Unique Sessions'
									),
								'pageviewData' => array(
									'heading' => 'Page Views'
									),
								'bounceRate' => array(
									'heading' => 'Bounce Rate'
									),
								'avgSessionDuration' => array(
									'heading' => 'Average Session Duration(in milli seconds)'
									),
								'avgPagePerSession' => array(
									'heading' => 'Average Page Per Session'
									),
								'exitRateData' => array(
									'heading' => 'Exit Rate'
									),
								'RegistrationData' => array(
									'heading' => 'Registrations Data'
									),
					)
		),
	'byUniversity' => array(
			'PIE_CHART' => array(
						'registration_sourceWise' => array(
								'heading' => 'Registration (Source Wise)',
								'typeHeading' => 'source',
								'countHeading' => 'Total Registrations'
								),
						'registration_deviceWise' => array(
								'heading' => 'Registration (Device Wise)',
								'typeHeading' => 'Device',
								'countHeading' => 'Total Registrations'
								),
						'registration_paidFree' => array(
								'heading'  => 'Registration (Paid/Free)',
								'typeHeading'  => 'type',
								'countHeading' => 'Total Registrations'
							),
								'Traffic-SourceWise' => array(
												'heading' => 'Traffic (Source-Wise)',
												'typeHeading' => 'Traffic Source',
												'countHeading' => 'Total Traffic'
												),
								'Traffic-DeviceWise' => array(
												'heading' => 'Traffic (Device-Wise)',
												'typeHeading' => 'Device Type',
												'countHeading' => 'Total Traffic'
												),
								'response_sourceWise' => array(
								'heading' => 'Response (Source Wise)',
								'typeHeading' => 'source',
								'countHeading' => 'Total Responses'
								),
				),
				'LINE_CHART' =>array(
							'leadDelivery' => array(
									'heading' => 'Number of Leads Delivered to Client:'
									),
							'responseData' => array(
									'heading' => 'Number of Responses Generated:'
									),
							'responseDelivery' => array(
									'heading' => 'Number of Responses Delivered to Client:'
									),
								
							),
				'Top_LINE' => array(
							'trafficData' => array(
									'heading' => 'Unique Sessions'
									),
								'pageviewData' => array(
									'heading' => 'Page Views'
									),
								'bounceRate' => array(
									'heading' => 'Bounce Rate'
									),
								'avgSessionDuration' => array(
									'heading' => 'Average Session Duration(in milli seconds)'
									),
								'avgPagePerSession' => array(
									'heading' => 'Average Page Per Session'
									),
								'exitRateData' => array(
									'heading' => 'Exit Rate'
									),
								'RegistrationData' => array(
									'heading' => 'Registrations Data'
									),
					)
		),
	'bySubcatId_SA' => array(
			'PIE_CHART' => array(
						'registration_sourceWise' => array(
								'heading' => 'Registration (Source Wise)',
								'typeHeading' => 'source',
								'countHeading' => 'Total Registrations'
								),
						'registration_deviceWise' => array(
								'heading' => 'Registration (Device Wise)',
								'typeHeading' => 'Device',
								'countHeading' => 'Total Registrations'
								),
						'registration_paidFree' => array(
								'heading'  => 'Registration (Paid/Free)',
								'typeHeading'  => 'type',
								'countHeading' => 'Total Registrations'
							),
						'responsePaidFreeWise' => array(
								'heading' => 'Response (Paid and Free)',
								'typeHeading' => 'paidType',
								'countHeading' => 'Total Responses'
								),
								'response_sourceWise' => array(
								'heading' => 'Response (Source Wise)',
								'typeHeading' => 'source',
								'countHeading' => 'Total Responses'
								),
				),
				'LINE_CHART' =>array(
							'leadDelivery' => array(
									'heading' => 'Number of Leads Delivered to Client:'
									),
							'responseData' => array(
									'heading' => 'Number of Responses Generated:'
									),
							'responseDelivery' => array(
									'heading' => 'Number of Responses Delivered to Client:'
									),
								
							),
				'Top_LINE' => array(
							'trafficData' => array(
									'heading' => 'Unique Sessions'
									),
								'pageviewData' => array(
									'heading' => 'Page Views'
									),
								'bounceRate' => array(
									'heading' => 'Bounce Rate'
									),
								'avgSessionDuration' => array(
									'heading' => 'Average Session Duration(in milli seconds)'
									),
								'avgPagePerSession' => array(
									'heading' => 'Average Page Per Session'
									),
								'exitRateData' => array(
									'heading' => 'Exit Rate'
									),
								'RegistrationData' => array(
									'heading' => 'Registrations Data'
									),
					)
		),
	'byArticle' => array(
		'PIE_CHART' => array(
								'Traffic-SourceWise' => array(
												'heading' => 'Traffic (Source-Wise)',
												'typeHeading' => 'Traffic Source',
												'countHeading' => 'Total Traffic'
												),
								'Traffic-DeviceWise' => array(
												'heading' => 'Traffic (Device-Wise)',
												'typeHeading' => 'Device Type',
												'countHeading' => 'Total Traffic'
												),
								'PageView-DeviceWise' => array(
										'heading' => 'PageView (Device-Wise)',
										'typeHeading' => 'Device Type',
										'countHeading' => 'Total PageViews'
										),
				),
				'LINE_CHART' =>array(
							'commentResult' => array(
									'heading' => 'Number of Comments Given:'
									),
							'replyResult' => array(
									'heading' => 'Number of Replies Given:'
									)
								
							),
				'Top_LINE' => array(
							'trafficData' => array(
									'heading' => 'Unique Sessions'
									),
								'pageviewData' => array(
									'heading' => 'Page Views'
									),
								'bounceRate' => array(
									'heading' => 'Bounce Rate'
									),
								'avgSessionDuration' => array(
									'heading' => 'Average Session Duration(in milli seconds)'
									),
								'avgPagePerSession' => array(
									'heading' => 'Average Page Per Session'
									),
								'exitRateData' => array(
									'heading' => 'Exit Rate'
									),
					)
		),
	'content-bySubcatId' => array(
		'PIE_CHART' => array(
								'Traffic-SourceWise' => array(
												'heading' => 'Traffic (Source-Wise)',
												'typeHeading' => 'Traffic Source',
												'countHeading' => 'Total Traffic'
												),
								'Traffic-DeviceWise' => array(
												'heading' => 'Traffic (Device-Wise)',
												'typeHeading' => 'Device Type',
												'countHeading' => 'Total Traffic'
												),
								'PageView-DeviceWise' => array(
										'heading' => 'PageView (Device-Wise)',
										'typeHeading' => 'Device Type',
										'countHeading' => 'Total PageViews'
										),
				),
				'LINE_CHART' =>array(
							'commentResult' => array(
									'heading' => 'Number of Comments Given:'
									),
							'replyResult' => array(
									'heading' => 'Number of Replies Given:'
									),
							'ArticleResult' => array(
									'heading' => 'Number of Articles Published:'
									),
								
							),
				'Top_LINE' => array(
							'trafficData' => array(
									'heading' => 'Unique Sessions'
									),
								'pageviewData' => array(
									'heading' => 'Page Views'
									),
								'bounceRate' => array(
									'heading' => 'Bounce Rate'
									),
								'avgSessionDuration' => array(
									'heading' => 'Average Session Duration(in milli seconds)'
									),
								'avgPagePerSession' => array(
									'heading' => 'Average Page Per Session'
									),
								'exitRateData' => array(
									'heading' => 'Exit Rate'
									),
					)
		),
	'SA-Content-byArticleId' => array(
		'PIE_CHART' => array(
								'Traffic-SourceWise' => array(
												'heading' => 'Traffic (Source-Wise)',
												'typeHeading' => 'Traffic Source',
												'countHeading' => 'Total Traffic'
												),
								'Traffic-DeviceWise' => array(
												'heading' => 'Traffic (Device-Wise)',
												'typeHeading' => 'Device Type',
												'countHeading' => 'Total Traffic'
												),
								'PageView-DeviceWise' => array(
										'heading' => 'PageView (Device-Wise)',
										'typeHeading' => 'Device Type',
										'countHeading' => 'Total PageViews'
										),
				),
				'LINE_CHART' =>array(
							'commentResult' => array(
									'heading' => 'Number of Comments Given:'
									),
							'replyResult' => array(
									'heading' => 'Number of Replies Given:'
									)
								
							),
				'Top_LINE' => array(
							'trafficData' => array(
									'heading' => 'Unique Sessions'
									),
								'pageviewData' => array(
									'heading' => 'Page Views'
									),
								'bounceRate' => array(
									'heading' => 'Bounce Rate'
									),
								'avgSessionDuration' => array(
									'heading' => 'Average Session Duration(in milli seconds)'
									),
								'avgPagePerSession' => array(
									'heading' => 'Average Page Per Session'
									),
								'exitRateData' => array(
									'heading' => 'Exit Rate'
									),
					)
		),
	'SA-Content-bySubcatId' => array(
		'PIE_CHART' => array(
								'Traffic-SourceWise' => array(
												'heading' => 'Traffic (Source-Wise)',
												'typeHeading' => 'Traffic Source',
												'countHeading' => 'Total Traffic'
												),
								'Traffic-DeviceWise' => array(
												'heading' => 'Traffic (Device-Wise)',
												'typeHeading' => 'Device Type',
												'countHeading' => 'Total Traffic'
												),
								'PageView-DeviceWise' => array(
										'heading' => 'PageView (Device-Wise)',
										'typeHeading' => 'Device Type',
										'countHeading' => 'Total PageViews'
										),
				),
				'LINE_CHART' =>array(
							'commentResult' => array(
									'heading' => 'Number of Comments Given:'
									),
							'replyResult' => array(
									'heading' => 'Number of Replies Given:'
									),
							'ArticleResult' => array(
									'heading' => 'Number of Articles Published:'
									),
								
							),
				'Top_LINE' => array(
							'trafficData' => array(
									'heading' => 'Unique Sessions'
									),
								'pageviewData' => array(
									'heading' => 'Page Views'
									),
								'bounceRate' => array(
									'heading' => 'Bounce Rate'
									),
								'avgSessionDuration' => array(
									'heading' => 'Average Session Duration(in milli seconds)'
									),
								'avgPagePerSession' => array(
									'heading' => 'Average Page Per Session'
									),
								'exitRateData' => array(
									'heading' => 'Exit Rate'
									),
					)
		),
	'byDiscussionId' => array(
		'PIE_CHART' => array(
								'Traffic-SourceWise' => array(
												'heading' => 'Traffic (Source-Wise)',
												'typeHeading' => 'Traffic Source',
												'countHeading' => 'Total Traffic'
												),
								'Traffic-DeviceWise' => array(
												'heading' => 'Traffic (Device-Wise)',
												'typeHeading' => 'Device Type',
												'countHeading' => 'Total Traffic'
												),
								'PageView-DeviceWise' => array(
										'heading' => 'PageView (Device-Wise)',
										'typeHeading' => 'Device Type',
										'countHeading' => 'Total PageViews'
										),
				),
				'LINE_CHART' =>array(
							'commentResult' => array(
									'heading' => 'Number of Comments Given:'
									)
							),
				'Top_LINE' => array(
							'trafficData' => array(
									'heading' => 'Unique Sessions'
									),
								'pageviewData' => array(
									'heading' => 'Page Views'
									),
								'bounceRate' => array(
									'heading' => 'Bounce Rate'
									),
								'avgSessionDuration' => array(
									'heading' => 'Average Session Duration(in milli seconds)'
									),
								'avgPagePerSession' => array(
									'heading' => 'Average Page Per Session'
									),
								'exitRateData' => array(
									'heading' => 'Exit Rate'
									),
					)
		),
	'NationalDiscussions' => array(
		'PIE_CHART' => array(
								'Traffic-SourceWise' => array(
												'heading' => 'Traffic (Source-Wise)',
												'typeHeading' => 'Traffic Source',
												'countHeading' => 'Total Traffic'
												),
								'Traffic-DeviceWise' => array(
												'heading' => 'Traffic (Device-Wise)',
												'typeHeading' => 'Device Type',
												'countHeading' => 'Total Traffic'
												),
								'PageView-DeviceWise' => array(
										'heading' => 'PageView (Device-Wise)',
										'typeHeading' => 'Device Type',
										'countHeading' => 'Total PageViews'
										),
				),
				'LINE_CHART' =>array(
							'DiscussionResult' => array(
								'heading' => 'Number of Discussions Started:'
								),
							'commentResult' => array(
									'heading' => 'Number of Comments Given:'
									)
							),
				'Top_LINE' => array(
							'trafficData' => array(
									'heading' => 'Unique Sessions'
									),
								'pageviewData' => array(
									'heading' => 'Page Views'
									),
								'bounceRate' => array(
									'heading' => 'Bounce Rate'
									),
								'avgSessionDuration' => array(
									'heading' => 'Average Session Duration(in milli seconds)'
									),
								'avgPagePerSession' => array(
									'heading' => 'Average Page Per Session'
									),
								'exitRateData' => array(
									'heading' => 'Exit Rate'
									),
					)
		)
	);

$config['headings'] = array(
	'mainHeading' => array(
		'DomesticOverview' => array(
			'heading' => 'Top Institutes In India on Shiksha'
			),
		'StudyAbroadOverview' => array(
			'heading' => 'Top Universities In Abroad on Shiksha'
			),
		'contentDomesticOverview' => array(
			'heading' => 'Details about Last 30 days Articles Published'
			),
		'contentStudyAbroadOverview' => array(
			'heading' => 'Details about Last 30 days Articles Published'
			)
		),
	'pieChart' => array(
		'Traffic-SourceWise' => array(
			'heading' => 'Traffic (Source-Wise)',
			'typeHeading' => 'Traffic Source',
			'countHeading' => 'Total Traffic'
			),
		'Traffic-DeviceWise' => array(
			'heading' => 'Traffic (Device-Wise)',
			'typeHeading' => 'Device Type',
			'countHeading' => 'Total Traffic'
			),
		'responseData' => array(
			'heading' => 'Response Data',
			'typeHeading'=> 'Response Type',
			'countHeading' => 'Total Responses'
			),
		/*'Traffic-SourceWise1' => array(
			'heading' => 'Traffic (Source-Wise) on CompareDateRange',
			'typeHeading' => 'Traffic Source',
			'countHeading' => 'Total Traffic'
			),
		'Traffic-DeviceWise1' => array(
			'heading' => 'Traffic (Device-Wise) on CompareDateRange',
			'typeHeading' => 'Device Type',
			'countHeading' => 'Total Traffic'
			),
		'responseData1' => array(
			'heading' => 'Response Data on CompareDateRange',
			'typeHeading'=> 'Response Type',
			'countHeading' => 'Total Responses'
			),*/
		'PageView-DeviceWise' => array(
			'heading' => 'PageView (Device-Wise)',
			'typeHeading' => 'Device Type',
			'countHeading' => 'Total PageViews'
			),
		'registration_sourceWise' => array(
			'heading' => 'Registration (Source Wise)',
			'typeHeading' => 'source',
			'countHeading' => 'Total Registrations'
			),
		/*'PageView-DeviceWise1' => array(
			'heading' => 'PageView (Device-Wise) on CompareDateRange',
			'typeHeading' => 'Device Type',
			'countHeading' => 'Total PageViews'
			),
		'BounceRate-DeviceWise0' => array(
			'heading' => 'BounceRate (Device-Wise)',
			'typeHeading' => 'Device Type',
			'countHeading' => 'Total Bounces'),
		'ExitRate-DeviceWise0' => array(
			'heading' => 'BounceRate (Device-Wise)',
			'typeHeading' => 'Device Type',
			'countHeading' => 'Total ExitRate'),
		'BounceRate-DeviceWise1' => array(
			'heading' => 'BounceRate (Device-Wise) on CompareDateRange',
			'typeHeading' => 'Device Type',
			'countHeading' => 'Total Bounces'),
		'ExitRate-DeviceWise1' => array(
			'heading' => 'BounceRate (Device-Wise) on CompareDateRange',
			'typeHeading' => 'Device Type',
			'countHeading' => 'Total ExitRate'),*/
		'splitByAuthor' => array(
			'heading' => 'Articles Published by Author',
			'typeHeading' => 'author Name',
			'countHeading' => 'Total Articles'
			),
		/*'splitByAuthor1' => array(
			'heading' => 'Articles Published by Author on CompareDateRange',
			'typeHeading' => 'author Name',
			'countHeading' => 'Total Articles'
			),*/
		'splitBySubcat' => array(
			'heading' => 'Articles Published by SubCat',
			'typeHeading' => 'SubCat Name',
			'countHeading' => 'Total Articles'
			),
		/*'splitBySubcat1' => array(
			'heading' => 'Articles Published by SubCat on CompareDateRange',
			'typeHeading' => 'SubCat Name',
			'countHeading' => 'Total Articles'
			),*/
		'd_splitByAuthor' => array(
			'heading' => 'Discussions Posted by Author',
			'typeHeading' => 'author Name',
			'countHeading' => 'Total Discussions'
			),
		/*'d_splitByAuthor1' => array(
			'heading' => 'Discussions Posted by Author on CompareDateRange',
			'typeHeading' => 'author Name',
			'countHeading' => 'Total Articles'
			),*/
		'd_splitBySubcat' => array(
			'heading' => 'Discussions Posted by SubCat',
			'typeHeading' => 'SubCat Name',
			'countHeading' => 'Total Discussions'
			),
		'd_splitBySubcat1' => array(
			'heading' => 'Discussions Posted by SubCat on CompareDateRange',
			'typeHeading' => 'SubCat Name',
			'countHeading' => 'Total Articles'
			)
	),
	'lineChart' => array(
		'QuestionResult' => array(
			'heading' => 'Number of Questions Asked:'
			),
		'AnswerResult' => array(
			'heading' => 'Number of Answers answered:'
			),
		'responseData' => array(
			'heading' => 'Number of Responses Generated:'
			),
		'DigupResult' => array(
			'heading' => 'Number of Answers Liked:'
			),
		'DiscussionResult' => array(
			'heading' => 'Number of Discussions Started:'
			),
		'commentResult' => array(
			'heading' => 'Number of Comments Given:'
			),
		'replyResult' => array(
			'heading' => 'Number of Replies Given:'
			),
		'ArticleResult' => array(
			'heading' => 'Number of Articles Published:'
			),
		'RegistrationData' => array(
			'heading' => 'Registrations Data'
			),
		'pageviewData' => array(
			'heading' => 'Page Views'
			),
		'bounceRate' => array(
			'heading' => 'Bounce Rate'
			),
		'avgSessionDuration' => array(
			'heading' => 'Average Session Duration(in milli seconds)'
			),
		'avgPagePerSession' => array(
			'heading' => 'Average Page Per Session'
			),
		'exitRateData' => array(
			'heading' => 'Exit Rate'
			),
		'trafficData' => array(
			'heading' => 'Unique Sessions'
			),
		'compareNew_Old_TrafficData' => array(
			'heading' => 'Traffic Data on New and Old Articles'
			),
		'leadDelivery' => array(
			'heading' => 'Number of Leads Delivered to Client:'
			),
		'responseDelivery' => array(
			'heading' => 'Number of Matched Responses Delivered to Client:'
			)
	));

$config['helperText'] = array(
          'byInstitute' => 'Directly Landing on Institute Detail Page or CourseDetail Page',
          'bySubcatId'  => 'Directly Landing on Institute Detail Page or CourseDetail Page',
          'byUniversity' => 'Directly Landing on University Detail Page or CourseDetail Page',
          'bySubcatId_SA' => 'Directly Landing on University Detail Page or CourseDetail Page',
          'content-bySubcatId' => 'Directly Landing on Article Detail Page',
          'byArticle' => 'Directly Landing on Article Detail Page',
          'SA-content-bySubcatId' => 'Directly Landing on Article Detail Page',
          'SA-content-byArtcileId' => 'Directly Landing on Article Detail Page',
          'NationalDiscussions' => 'Directly Landing on Discussion Detail Page',
          'byDiscussionId' => 'Directly Landing on Discussion Detail Page',
          );
