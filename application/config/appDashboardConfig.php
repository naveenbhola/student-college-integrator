<?php
$config['leftMenuArray'] = array(
						'App Dashboard' => array(
									'className' => "fa-home"
									)
					);

$config['charts'] = array(
				'lineChart' => array(
						'Traffic Data' => array(
							'activeUsers' => array(
									'heading' => 'Number of Active Users'
									),
							'appInstall' => array(
									'heading' => 'Shiksha App Installs'
									),
							'apiHits' => array(
									'heading' => 'Backend API Hits'
									),
							
							),
						'Registration Data' => array(
							'appReg' => array(
									'heading' => 'New Registrations from App'
									),
							'totalReg' => array(
									'heading' => 'Total Shiksha Registrations'
									),

							),
						'Answer Rate Data' => array(
								'answer24' => array(
									'heading' => 'Shiksha Answer Rate within 24 Hrs'
									),
								'answer48' => array(
									'heading' => 'Shiksha Answer Rate within 48 Hrs'
									),
							),
						'Questions Data' => array(
							'appQuestions' => array(
									'heading' => 'Questions asked from App'
									),
								'totalQuestions' => array(
									'heading' => 'Total Questions asked'
									),
							),
						'Answers Data' => array(
							'appAnswers' => array(
									'heading' => 'Answers from App'
									),
								'totalAnswers' => array(
									'heading' => 'Total Answers '
									),
							),
						'Follow Data' => array(
							'tagFollowers' => array(
									'heading' => 'Tags followed'
									),
								'userFollowers' => array(
									'heading' => 'Users followed'
									),
							),
						'Sharing Data' => array(
							'appShare' => array(
									'heading' => 'Sharing from App Data '
									),
							),
						'Performance Data' => array(
							'performance' => array(
									'heading' => 'Avg. Server Processing Time (ms)'
									),
							)
					)
		);

?>
