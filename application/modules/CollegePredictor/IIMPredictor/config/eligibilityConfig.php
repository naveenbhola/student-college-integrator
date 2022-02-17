<?php

	/*
	* Way to define eligibility constants for IIM
	* {IIM_name}_Eligibility = array(
  	*								'user_category' => array('criteria' => 'limit')	
  	*								)
	*/


	$IIM_Ahmedabad_Eligibility = array(
							'General' => array(
										'X_XII_Science_avg' => '80', //special case for IIMA
										'X_XII_Commerce_avg' => '77',
										'X_XII_Arts_avg' => '75',
										'graduationPercentage' => '50',
										'VRC_Percentile' => '70',
										'DILR_Percentile' => '70',
										'QA_Percentile' => '70',
										'Total_Percentile' => '80'
										),
							'NC-OBC' => array(
										'X_XII_Science_avg' => '75',
										'X_XII_Commerce_avg' => '72',
										'X_XII_Arts_avg' => '70',
										'graduationPercentage' => '50',
										'VRC_Percentile' => '65',
										'DILR_Percentile' => '65',
										'QA_Percentile' => '65',
										'Total_Percentile' => '75'
										),
							'SC' => array(
										'X_XII_Science_avg' => '70',
										'X_XII_Commerce_avg' => '67',
										'X_XII_Arts_avg' => '64',
										'graduationPercentage' => '45',
										'VRC_Percentile' => '60',
										'DILR_Percentile' => '60',
										'QA_Percentile' => '60',
										'Total_Percentile' => '70'
										),
							'ST' => array(
										'X_XII_Science_avg' => '65',
										'X_XII_Commerce_avg' => '62',
										'X_XII_Arts_avg' => '59',
										'graduationPercentage' => '45',
										'VRC_Percentile' => '50',
										'DILR_Percentile' => '50',
										'QA_Percentile' => '50',
										'Total_Percentile' => '60'
										),
							'DA' => array(
										'X_XII_Science_avg' => '70',
										'X_XII_Commerce_avg' => '67',
										'X_XII_Arts_avg' => '64',
										'graduationPercentage' => '45',
										'VRC_Percentile' => '60',
										'DILR_Percentile' => '60',
										'QA_Percentile' => '60',
										'Total_Percentile' => '70'
										),
							'gender-relaxation-transgender' => array(
										'X_XII_Science_avg' => '75',
										'X_XII_Commerce_avg' => '72',
										'X_XII_Arts_avg' => '70',
										'graduationPercentage' => '50',
										'VRC_Percentile' => '65',
										'DILR_Percentile' => '65',
										'QA_Percentile' => '65',
										'Total_Percentile' => '75'
										)
							);

	$IIM_Bangalore_Eligibility = array(
							'General' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '85',
										'DILR_Percentile' => '80',
										'QA_Percentile' => '80',
										'Total_Percentile' => '90'
										),
							'NC-OBC' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '75',
										'DILR_Percentile' => '70',
										'QA_Percentile' => '70',
										'Total_Percentile' => '80'
										),
							'SC' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '70',
										'DILR_Percentile' => '65',
										'QA_Percentile' => '65',
										'Total_Percentile' => '75'
										),
							'ST' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '60',
										'DILR_Percentile' => '60',
										'QA_Percentile' => '60',
										'Total_Percentile' => '75'
										),
							'DA' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '60',
										'DILR_Percentile' => '60',
										'QA_Percentile' => '60',
										'Total_Percentile' => '75'
										)
							);

	$IIM_Calcutta_Eligibility = array(
							'General' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '80',
										'DILR_Percentile' => '80',
										'QA_Percentile' => '80',
										'Total_Percentile' => '90'
										),
							'NC-OBC' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '75',
										'DILR_Percentile' => '75',
										'QA_Percentile' => '75',
										'Total_Percentile' => '85'
										),
							'SC' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '65',
										'DILR_Percentile' => '65',
										'QA_Percentile' => '65',
										'Total_Percentile' => '70'
										),
							'ST' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '60',
										'DILR_Percentile' => '60',
										'QA_Percentile' => '60',
										'Total_Percentile' => '70'
										),
							'DA' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '60',
										'DILR_Percentile' => '60',
										'QA_Percentile' => '60',
										'Total_Percentile' => '70'
										)
							);
						
	$IIM_Lucknow_Eligibility = array(
							'General' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '85',
										'DILR_Percentile' => '85',
										'QA_Percentile' => '85',
										'Total_Percentile' => '90'
										),
							'NC-OBC' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '77',
										'DILR_Percentile' => '77',
										'QA_Percentile' => '77',
										'Total_Percentile' => '82'
										),
							'SC' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '55',
										'DILR_Percentile' => '55',
										'QA_Percentile' => '55',
										'Total_Percentile' => '70'
										),
							'ST' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '50',
										'DILR_Percentile' => '50',
										'QA_Percentile' => '50',
										'Total_Percentile' => '65'
										),
							'DA' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '50',
										'DILR_Percentile' => '50',
										'QA_Percentile' => '50',
										'Total_Percentile' => '65'
										)
							);		

	$IIM_Indore_Eligibility = array(
							'General' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '80',
										'DILR_Percentile' => '80',
										'QA_Percentile' => '80',
										'Total_Percentile' => '90'
										),
							'NC-OBC' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '70',
										'DILR_Percentile' => '70',
										'QA_Percentile' => '70',
										'Total_Percentile' => '80'
										),
							'SC' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '55',
										'DILR_Percentile' => '55',
										'QA_Percentile' => '55',
										'Total_Percentile' => '60'
										),
							'ST' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '45',
										'DILR_Percentile' => '45',
										'QA_Percentile' => '45',
										'Total_Percentile' => '50'
										),
							'DA' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '45',
										'DILR_Percentile' => '45',
										'QA_Percentile' => '45',
										'Total_Percentile' => '50'
										)
							);	

	$IIM_Kozhikode_Eligibility = array(
							'General' => array(
										'xthPercentage' => '60',
										'xiithPercentage' => '60',
										'graduationPercentage' => '50',
										'VRC_Percentile' => '80',
										'DILR_Percentile' => '80',
										'QA_Percentile' => '80',
										'Total_Percentile' => '90'
										),
							'NC-OBC' => array(
										'xthPercentage' => '60',
										'xiithPercentage' => '60',
										'graduationPercentage' => '50',
										'VRC_Percentile' => '70',
										'DILR_Percentile' => '70',
										'QA_Percentile' => '70',
										'Total_Percentile' => '80'
										),
							'SC' => array(
										'xthPercentage' => '55',
										'xiithPercentage' => '55',
										'graduationPercentage' => '45',
										'VRC_Percentile' => '50',
										'DILR_Percentile' => '50',
										'QA_Percentile' => '50',
										'Total_Percentile' => '65'
										),
							'ST' => array(
										'xthPercentage' => '55',
										'xiithPercentage' => '55',
										'graduationPercentage' => '45',
										'VRC_Percentile' => '40',
										'DILR_Percentile' => '40',
										'QA_Percentile' => '40',
										'Total_Percentile' => '55'
										),
							'DA' => array(
										'xthPercentage' => '55',
										'xiithPercentage' => '55',
										'graduationPercentage' => '45',
										'VRC_Percentile' => '40',
										'DILR_Percentile' => '40',
										'QA_Percentile' => '40',
										'Total_Percentile' => '55'
										)
							);

	$IIM_Shillong_Eligibility = array(
							'General' => array(
										'xthPercentage' => '80',
										'xiithPercentage' => '80',
										'graduationPercentage' => '65',
										'VRC_Percentile' => '70',
										'DILR_Percentile' => '70',
										'QA_Percentile' => '70'
										),
							'NC-OBC' => array(
										'xthPercentage' => '80',
										'xiithPercentage' => '80',
										'graduationPercentage' => '65',
										'VRC_Percentile' => '70',
										'DILR_Percentile' => '70',
										'QA_Percentile' => '70'
										),
							'SC' => array(
										'xthPercentage' => '55',
										'xiithPercentage' => '55',
										'graduationPercentage' => '50',
										'VRC_Percentile' => '60',
										'DILR_Percentile' => '60',
										'QA_Percentile' => '60'
										),
							'ST' => array(
										'xthPercentage' => '50',
										'xiithPercentage' => '50',
										'graduationPercentage' => '45',
										'VRC_Percentile' => '50',
										'DILR_Percentile' => '50',
										'QA_Percentile' => '50'
										),
							'DA' => array(
										'xthPercentage' => '50',
										'xiithPercentage' => '50',
										'graduationPercentage' => '45',
										'VRC_Percentile' => '50',
										'DILR_Percentile' => '50',
										'QA_Percentile' => '50'
										)
							);

	$IIM_Kashipur_Eligibility = array(
								'General' => array(
											'graduationPercentage' => '50',
											'VRC_Percentile' => '80',
											'DILR_Percentile' => '80',
											'QA_Percentile' => '80',
											'Total_Percentile' => '90'
											),
								'NC-OBC' => array(
											'graduationPercentage' => '50',
											'VRC_Percentile' => '72',
											'DILR_Percentile' => '72',
											'QA_Percentile' => '72',
											'Total_Percentile' => '81'
											),
								'SC' => array(
											'graduationPercentage' => '45',
											'VRC_Percentile' => '60',
											'DILR_Percentile' => '60',
											'QA_Percentile' => '60',
											'Total_Percentile' => '75'
											),
								'ST' => array(
											'graduationPercentage' => '45',
											'VRC_Percentile' => '50',
											'DILR_Percentile' => '50',
											'QA_Percentile' => '50',
											'Total_Percentile' => '65'
											),
								'DA' => array(
											'graduationPercentage' => '45',
											'VRC_Percentile' => '50',
											'DILR_Percentile' => '50',
											'QA_Percentile' => '50',
											'Total_Percentile' => '65'
											)
								);	

	$IIM_Raipur_Eligibility = array(
							'General' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '80',
										'DILR_Percentile' => '80',
										'QA_Percentile' => '80',
										'Total_Percentile' => '90'
										),
							'NC-OBC' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '72',
										'DILR_Percentile' => '72',
										'QA_Percentile' => '72',
										'Total_Percentile' => '81'
										),
							'SC' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '60',
										'DILR_Percentile' => '60',
										'QA_Percentile' => '60',
										'Total_Percentile' => '75'
										),
							'ST' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '50',
										'DILR_Percentile' => '50',
										'QA_Percentile' => '50',
										'Total_Percentile' => '65'
										),
							'DA' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '50',
										'DILR_Percentile' => '50',
										'QA_Percentile' => '50',
										'Total_Percentile' => '65'
										)
							);

	$IIM_Udaipur_Eligibility = array(
							'General' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '80',
										'DILR_Percentile' => '80',
										'QA_Percentile' => '80',
										'Total_Percentile' => '90'
										),
							'NC-OBC' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '72',
										'DILR_Percentile' => '72',
										'QA_Percentile' => '72',
										'Total_Percentile' => '81'
										),
							'SC' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '60',
										'DILR_Percentile' => '60',
										'QA_Percentile' => '60',
										'Total_Percentile' => '75'
										),
							'ST' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '50',
										'DILR_Percentile' => '50',
										'QA_Percentile' => '50',
										'Total_Percentile' => '65'
										),
							'DA' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '50',
										'DILR_Percentile' => '50',
										'QA_Percentile' => '50',
										'Total_Percentile' => '65'
										)
							);

	$IIM_Trichy_Eligibility = array(
							'General' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '80',
										'DILR_Percentile' => '80',
										'QA_Percentile' => '80',
										'Total_Percentile' => '90'
										),
							'NC-OBC' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '72',
										'DILR_Percentile' => '72',
										'QA_Percentile' => '72',
										'Total_Percentile' => '81'
										),
							'SC' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '60',
										'DILR_Percentile' => '60',
										'QA_Percentile' => '60',
										'Total_Percentile' => '75'
										),
							'ST' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '50',
										'DILR_Percentile' => '50',
										'QA_Percentile' => '50',
										'Total_Percentile' => '65'
										),
							'DA' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '50',
										'DILR_Percentile' => '50',
										'QA_Percentile' => '50',
										'Total_Percentile' => '65'
										)
							);

	$IIM_Ranchi_Eligibility = array(
							'General' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '80',
										'DILR_Percentile' => '80',
										'QA_Percentile' => '80',
										'Total_Percentile' => '90'
										),
							'NC-OBC' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '72',
										'DILR_Percentile' => '72',
										'QA_Percentile' => '72',
										'Total_Percentile' => '81'
										),
							'SC' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '60',
										'DILR_Percentile' => '60',
										'QA_Percentile' => '60',
										'Total_Percentile' => '75'
										),
							'ST' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '50',
										'DILR_Percentile' => '50',
										'QA_Percentile' => '50',
										'Total_Percentile' => '65'
										),
							'DA' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '50',
										'DILR_Percentile' => '50',
										'QA_Percentile' => '50',
										'Total_Percentile' => '65'
										)
							);

	$IIM_Bodhgaya_Eligibility = array(
							'General' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '80',
										'DILR_Percentile' => '80',
										'QA_Percentile' => '80',
										'Total_Percentile' => '90'
										),
							'NC-OBC' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '55',
										'DILR_Percentile' => '55',
										'QA_Percentile' => '55',
										'Total_Percentile' => '75'
										),
							'SC' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '50',
										'DILR_Percentile' => '50',
										'QA_Percentile' => '50',
										'Total_Percentile' => '55'
										),
							'ST' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '30',
										'DILR_Percentile' => '30',
										'QA_Percentile' => '30',
										'Total_Percentile' => '35'
										),
							'DA' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '30',
										'DILR_Percentile' => '30',
										'QA_Percentile' => '30',
										'Total_Percentile' => '35'
										)
							);

	$IIM_Sambalpur_Eligibility = array(
							'General' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '80',
										'DILR_Percentile' => '80',
										'QA_Percentile' => '80',
										'Total_Percentile' => '90'
										),
							'NC-OBC' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '70',
										'DILR_Percentile' => '70',
										'QA_Percentile' => '70',
										'Total_Percentile' => '80'
										),
							'SC' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '55',
										'DILR_Percentile' => '55',
										'QA_Percentile' => '55',
										'Total_Percentile' => '60'
										),
							'ST' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '45',
										'DILR_Percentile' => '45',
										'QA_Percentile' => '45',
										'Total_Percentile' => '50'
										),
							'DA' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '45',
										'DILR_Percentile' => '45',
										'QA_Percentile' => '45',
										'Total_Percentile' => '50'
										)
							);

$IIM_Amritsar_Eligibility = array(
							'General' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '70',
										'DILR_Percentile' => '70',
										'QA_Percentile' => '70',
										'Total_Percentile' => '95.6'
										),
							'NC-OBC' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '58',
										'DILR_Percentile' => '58',
										'QA_Percentile' => '58',
										'Total_Percentile' => '75'
										),
							'SC' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '50',
										'DILR_Percentile' => '50',
										'QA_Percentile' => '50',
										'Total_Percentile' => '56'
										),
							'ST' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '30',
										'DILR_Percentile' => '30',
										'QA_Percentile' => '30',
										'Total_Percentile' => '35'
										),
							'DA' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '30',
										'DILR_Percentile' => '30',
										'QA_Percentile' => '30',
										'Total_Percentile' => '40'
										)
							);

$IIM_Nagpur_Eligibility = array(
							'General' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '80',
										'DILR_Percentile' => '80',
										'QA_Percentile' => '80',
										'Total_Percentile' => '90'
										),
							'NC-OBC' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '72',
										'DILR_Percentile' => '72',
										'QA_Percentile' => '72',
										'Total_Percentile' => '81'
										),
							'SC' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '60',
										'DILR_Percentile' => '60',
										'QA_Percentile' => '60',
										'Total_Percentile' => '75'
										),
							'ST' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '50',
										'DILR_Percentile' => '50',
										'QA_Percentile' => '50',
										'Total_Percentile' => '65'
										),
							'DA' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '50',
										'DILR_Percentile' => '50',
										'QA_Percentile' => '50',
										'Total_Percentile' => '65'
										)
							);

$IIM_Sirmaur_Eligibility = array(
							'General' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '75',
										'DILR_Percentile' => '75',
										'QA_Percentile' => '75',
										'Total_Percentile' => '80'
										),
							'NC-OBC' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '68',
										'DILR_Percentile' => '68',
										'QA_Percentile' => '68',
										'Total_Percentile' => '72'
										),
							'SC' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '50',
										'DILR_Percentile' => '50',
										'QA_Percentile' => '50',
										'Total_Percentile' => '70'
										),
							'ST' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '45',
										'DILR_Percentile' => '45',
										'QA_Percentile' => '45',
										'Total_Percentile' => '65'
										),
							'DA' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '50',
										'DILR_Percentile' => '50',
										'QA_Percentile' => '50',
										'Total_Percentile' => '70'
										)
							);

	$IIM_Visakhapatnam_Eligibility = array(
							'General' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '85',
										'DILR_Percentile' => '80',
										'QA_Percentile' => '80',
										'Total_Percentile' => '90'
										),
							'NC-OBC' => array(
										'graduationPercentage' => '50',
										'VRC_Percentile' => '75',
										'DILR_Percentile' => '70',
										'QA_Percentile' => '70',
										'Total_Percentile' => '80'
										),
							'SC' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '60',
										'DILR_Percentile' => '60',
										'QA_Percentile' => '60',
										'Total_Percentile' => '75'
										),
							'ST' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '50',
										'DILR_Percentile' => '50',
										'QA_Percentile' => '50',
										'Total_Percentile' => '55'
										),
							'DA' => array(
										'graduationPercentage' => '45',
										'VRC_Percentile' => '50',
										'DILR_Percentile' => '50',
										'QA_Percentile' => '50',
										'Total_Percentile' => '55'
										)
							);

		$IIM_Rohtak_Eligibility = array(
							'General' => array(
										'xthPercentage' => '70',
										'xiithPercentage' => '65',
										'graduationPercentage' => '60',
										'VRC_Percentile' => '80',
										'DILR_Percentile' => '80',
										'QA_Percentile' => '80',
										'Total_Percentile' => '85'
										),
							'NC-OBC' => array(
										'xthPercentage' => '65',
										'xiithPercentage' => '60',
										'graduationPercentage' => '55',
										'VRC_Percentile' => '70',
										'DILR_Percentile' => '70',
										'QA_Percentile' => '70',
										'Total_Percentile' => '75'
										),
							'SC' => array(
										'xthPercentage' => '60',
										'xiithPercentage' => '55',
										'graduationPercentage' => '50',
										'VRC_Percentile' => '45',
										'DILR_Percentile' => '45',
										'QA_Percentile' => '45',
										'Total_Percentile' => '60'
										),
							'ST' => array(
										'xthPercentage' => '55',
										'xiithPercentage' => '50',
										'graduationPercentage' => '45',
										'VRC_Percentile' => '40',
										'DILR_Percentile' => '40',
										'QA_Percentile' => '40',
										'Total_Percentile' => '60'
										),
							'DA' => array(
										'xthPercentage' => '55',
										'xiithPercentage' => '50',
										'graduationPercentage' => '45',
										'VRC_Percentile' => '40',
										'DILR_Percentile' => '40',
										'QA_Percentile' => '40',
										'Total_Percentile' => '60'
										)
							);
?>