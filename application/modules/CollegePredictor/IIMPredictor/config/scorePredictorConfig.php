<?php
/*
 * EXPLAINATION HOW TO UPDATE CONFIG
 *
 */

/* 
 * ********************************************************************************************************************
 *  IIM Ahmedabad Score Criteria
 * ********************************************************************************************************************
*/

/* Rating Scores for 10th Std. Exam (Rating Score IIM_Ahmedabad_X) */
$IIM_Ahmedabad_X_criteria = array(
		'<=55'=>1,
		'>55 && <=60'=>2,
		'>60 && <=70'=>3,
		'>70 && <=80'=>5,
		'>80 && <=90'=>8,
		'>90'=>10
	);

$IIM_Ahmedabad_X = marksRangeChecker($IIM_Ahmedabad_X_criteria, $data['xthPercentage']);

/* Rating Scores for 12th Std. Exam (Rating Score IIM_Ahmedabad_XII) */
$IIM_Ahmedabad_XII_criteria= array(
		'Science' => array(
				'<=55'=>1,
				'>55 && <=60'=>2,
				'>60 && <=70'=>3,
				'>70 && <=80'=>5,
				'>80 && <=90'=>8,
				'>90'=>10
			),
		'Commerce' => array(
				'<=50'=>1,
				'>50 && <=55'=>2,
				'>55 && <=65'=>3,
				'>65 && <=75'=>5,
				'>75 && <=90'=>8,
				'>90'=>10
			),
		'Arts' => array(
				'<=45'=>1,
				'>45 && <=50'=>2,
				'>50 && <=60'=>3,
				'>60 && <=70'=>5,
				'>70 && <=85'=>8,
				'>85'=>10
			),
	);

$IIM_Ahmedabad_XII = marksRangeChecker($IIM_Ahmedabad_XII_criteria[$data['xiithStream']], $data['xiithPercentage']);

/* Rating Scores for bachelor’s degree Exam (Rating Score IIM_Ahmedabad_Grad) */
$Graduation_Percentage_AC2 = array(
		'<=50'=>1,
		'>50 && <=55'=>2,
		'>55 && <=60'=>3,
		'>60 && <=65'=>4,
		'>65 && <=75'=>7,
		'>75'=>10
	);

$Graduation_Percentage_AC4 = array(
		'<=60'=>1,
		'>60 && <=65'=>2,
		'>65 && <=70'=>3,
		'>70 && <=75'=>4,
		'>75 && <=85'=>7,
		'>85'=>10
	);

$Graduation_Percentage_AC6 = array(
		'<=55'=>1,
		'>55 && <=60'=>2,
		'>60 && <=62'=>3,
		'>62 && <=65'=>4,
		'>65 && <=70'=>7,
		'>70'=>10
	);

$Graduation_Percentage_AC7 = array(
		'<=50'=>1,
		'>50 && <=53'=>2,
		'>53 && <=55'=>3,
		'>55 && <=57'=>4,
		'>57 && <=63'=>7,
		'>63'=>10
	);

$Graduation_Percentage_AC_Others = array(
		'<=55'=>1,
		'>55 && <=60'=>2,
		'>60 && <=65'=>3,
		'>65 && <=70'=>4,
		'>70 && <=80'=>7,
		'>80'=>10
	);


$IIM_Ahmedabad_AC_Master = array(
		'ActuarialScience'=> $Graduation_Percentage_AC_Others,
		'Architecture' => $Graduation_Percentage_AC4,
		'AgriculturalEngineering' => $Graduation_Percentage_AC4,
		'Agriculture' => $Graduation_Percentage_AC_Others,
		'AnimalHusbandry' => $Graduation_Percentage_AC_Others,
		'ArtsHumanities' => $Graduation_Percentage_AC6,
		'BioscienceBiotechnology'=> $Graduation_Percentage_AC4,
		'CharteredAccountancy' => $Graduation_Percentage_AC2,
		'CompanySecretaryship' => $Graduation_Percentage_AC2,
		'CommerceEconomics' => $Graduation_Percentage_AC_Others,
		'ComputerApplications'=> $Graduation_Percentage_AC4,
		'DairyScienceTechnology' => $Graduation_Percentage_AC_Others,
		'Dentistry' => $Graduation_Percentage_AC_Others,
		'EngineeringTechnology' => $Graduation_Percentage_AC4,
		'FashionDesign'=> $Graduation_Percentage_AC6,
		'Fisheries' => $Graduation_Percentage_AC_Others,
		'FoodTechnology' => $Graduation_Percentage_AC_Others,
		'Forestry' => $Graduation_Percentage_AC_Others,
		'GeographyGeologicalSciences'=> $Graduation_Percentage_AC_Others,
		'Horticulture' => $Graduation_Percentage_AC_Others,
		'HotelManagement' => $Graduation_Percentage_AC_Others,
		'ICWA' => $Graduation_Percentage_AC2,
		'Law' => $Graduation_Percentage_AC6,
		'Management' => $Graduation_Percentage_AC_Others,
		'MathematicalSciences'=> $Graduation_Percentage_AC_Others,
		'MedicineSurgery' => $Graduation_Percentage_AC_Others,
		'NaturalSciences'=> $Graduation_Percentage_AC4,
		'ParamedicalPhysiotherapy'=> $Graduation_Percentage_AC_Others,
		'Pharmacology/Pharmacy' => $Graduation_Percentage_AC_Others,
		'Planning'=> $Graduation_Percentage_AC_Others,
		'PhysicalEducation' => $Graduation_Percentage_AC6,
		'Rural' => $Graduation_Percentage_AC6,
		'Science' => $Graduation_Percentage_AC_Others,
		'TextileTechnology'=> $Graduation_Percentage_AC4,
		'VetrinaryScience' => $Graduation_Percentage_AC_Others,
		'Others' => $Graduation_Percentage_AC7
	);

$IIM_Ahmedabad_Grad =  marksRangeChecker( $IIM_Ahmedabad_AC_Master[returnMappedGraduationDiscipline( $data['graduationStream'], array_keys($IIM_Ahmedabad_AC_Master) )], $data['graduationPercentage'] );

/* Normailized AR formula*/
$IIM_Ahmedabad_AR = (( $IIM_Ahmedabad_X + $IIM_Ahmedabad_XII + $IIM_Ahmedabad_Grad )/30)*0.3;

$IIM_Ahmedabad_minimum_cs = array(
									'General'=>0.426,
									'NC-OBC' => 0.414,
									'SC' => 0.361,
									'ST' => 0.293,
									'DA' => 0.533
								);

$IIM_Ahmedabad_required_score = ($IIM_Ahmedabad_minimum_cs[$data['category']] - $IIM_Ahmedabad_AR)/0.7;


/* Formula starts from here */
$IIM_Ahmedabad_ScoreData = array(
							'required_percentile' => $percentileData[getValidPercentile($IIM_Ahmedabad_required_score*300)],
							'cutoff_percentile' => $IIM_Ahmedabad_cutoff_percentile[$data['category']],
							'score_calculated' => 'YES'
							);

/* 
 * ********************************************************************************************************************
 *  IIM Bangalore Score Criteria
 * ********************************************************************************************************************
*/

$IIM_Bangalore_ScoreData = array(
							'score_calculated' => 'NO'
							);

/* 
 * ********************************************************************************************************************
 *  IIM Calcutta Score Criteria
 * ********************************************************************************************************************
*/

$IIM_Calcutta_X_criteria = array(
		'>= 80' => 10,
		'>= 75 && < 80' => 8,
		'>= 70 && < 75' => 6,
		'>= 65 && < 70' => 4,
		'>= 60 && < 65' => 2,
		'< 60' => 0
	);

$IIM_Calcutta_X = marksRangeChecker($IIM_Calcutta_X_criteria, $data['xthPercentage']);

$IIM_Calcutta_XII_criteria = array(
		'>= 80' => 10,
		'>= 75 && < 80' => 8,
		'>= 70 && < 75' => 6,
		'>= 65 && < 70' => 4,
		'>= 60 && < 65' => 2,
		'< 60' => 0
	);

$IIM_Calcutta_XII = marksRangeChecker($IIM_Calcutta_XII_criteria, $data['xiithPercentage']);

$IIM_Calcutta_GD_criteria = array(
		'female' => 2,
		'male' => 0,
		'transgender' => 0
	); 

$IIM_Calcutta_GD = $IIM_Calcutta_GD_criteria[$data['gender']];

$IIM_Calcutta_CATscore = ( $data['cat_total'] )/300;

$IIM_Calcutta_minimum_cs = array(
									'General'=>35.9,
									'NC-OBC' => 31.75,
									'SC' => 28.59,
									'ST' => 10.89,
									'DA' => 25.24
								);

//$IIM_Calcutta_required_score = ($IIM_Calcutta_minimum_cs[$data['category']] - 2*$IIM_Calcutta_X - 2*$IIM_Calcutta_XII - 3*$IIM_Calcutta_GD)/77;
$IIM_Calcutta_required_score = ($IIM_Calcutta_minimum_cs[$data['category']] - ($IIM_Calcutta_X + $IIM_Calcutta_XII + $IIM_Calcutta_GD))/28;




$IIM_Calcutta_ScoreData = array(
							'required_percentile' => $percentileData[getValidPercentile($IIM_Calcutta_required_score*300)],
							'cutoff_percentile' => $IIM_Calcutta_cutoff_percentile[$data['category']],
							'score_calculated' => 'YES'
							);


/* 
 * ********************************************************************************************************************
 *  IIM Kozhikode Score Criteria
 * ********************************************************************************************************************
*/

$IIM_Kozhikode_XII_criteria = array(
		'> 81' => 7.5,
		'> 60 && <= 81' => (($data['xiithPercentage']-60)*7.5)/21,
		'<= 60' => 0
	);

$IIM_Kozhikode_XII = marksRangeChecker($IIM_Kozhikode_XII_criteria, $data['xiithPercentage']);

$IIM_Kozhikode_graduation_criteria = array(
		'> 81' => 7.5,
		'> 60 && <= 81' => (($data['graduationPercentage']-60)*7.5)/21,
		'<= 60' => 0
	);
$IIM_Kozhikode_user_exp = $data['experience'];

$IIM_Kozhikode_experience_criteria = array(
		'>= 36' => 1,
		'> 24 && < 36' => ((36 - $IIM_Kozhikode_user_exp)*4)/12 + 1,
		'> 6 && <=24' => (($IIM_Kozhikode_user_exp - 6)*4)/18 + 1,
		'<= 6' => 0
	);

$IIM_Kozhikode_minimum_cs = array(
		'General'=>64.91,
		'NC-OBC' => 47.57,
		'SC' => 40.62,
		'ST' => 30.5,
		'DA' => 25.2
						);

$IIM_Kozhikode_GD_criteria = array(
		'female' => 5,
		'male' => 0,
		'transgender' => 5
	); 

$IIM_Kozhikode_GD = $IIM_Kozhikode_GD_criteria[$data['gender']];

$IIM_Kozhikode_AD = ($data['graduationStream'] != 'EngineeringTechnology') ? 5 : 0;

$IIM_Kozhikode_grad = marksRangeChecker($IIM_Kozhikode_graduation_criteria, $data['graduationPercentage']);

$IIM_Kozhikode_experience = marksRangeChecker($IIM_Kozhikode_experience_criteria, $IIM_Kozhikode_user_exp);

//$IIM_Kozhikode_required_score = $IIM_Kozhikode_XII + $IIM_Kozhikode_grad + $IIM_Kozhikode_user_exp + max($IIM_Kozhikode_GD, $IIM_Kozhikode_AD) + 75*$IIM_Kozhikode_minimum_cs[$data['category']];

$IIM_Kozhikode_required_score = ($IIM_Kozhikode_minimum_cs[$data['category']] - ($IIM_Kozhikode_XII + $IIM_Kozhikode_grad + $IIM_Kozhikode_experience + max($IIM_Kozhikode_GD, $IIM_Kozhikode_AD)))/75;


$IIM_Kozhikode_ScoreData = array(
							'required_percentile' => $percentileData[getValidPercentile($IIM_Kozhikode_required_score*300)],
							'cutoff_percentile' => $IIM_Kozhikode_cutoff_percentile[$data['category']],
							'score_calculated' => 'YES'
							);

/* 
 * ********************************************************************************************************************
 *  IIM Shillong Score Criteria
 * ********************************************************************************************************************
*/

$IIM_Shillong_required_score = '';

$IIM_Shillong_ScoreData = array(
							'required_percentile' => $IIM_Shillong_cutoff_percentile[$data['category']]['Total_Percentile'],
							'cutoff_percentile' => $IIM_Shillong_cutoff_percentile[$data['category']],
							'score_calculated' => 'YES'
							);


/* 
 * ********************************************************************************************************************
 *  IIM Lucknow Score Criteria
 * ********************************************************************************************************************
*/

$IIM_Lucknow_ScoreData = array(
							'score_calculated' => 'NO'
							);

/* 
 * ********************************************************************************************************************
 *  IIM Indore Score Criteria
 * ********************************************************************************************************************
*/



$IIM_Indore_minimum_cs = array(
									'General'=>75.34,
									'NC-OBC' => 66.96,
									'SC' => 63.42,
									'ST' => 34.05,
									'DA' => 41.47
								);

$IIM_Indore_Max_board_score = array(
								'kerala'=>'100',
								'andhraPradesh'=>'99.4',
								'tamilNadu'=>'99.17',
								'CBSE'=>'99',
								'karnataka'=>'98.83',
								'CISCE'=>'98.6',
								'haryana'=>'98.4',
								'maharashtra'=>'98.17',
								'JK'=>'98',
								'himachalPradesh'=>'97.6',
								'madhyaPradesh'=>'97.4',
								'rajasthan'=>'96.8',
								'gujarat'=>'96.8',
								'manipur'=>'96.6',
								'assam'=>'96.4',
								'westBengal'=>'95.8',
								'chattisgarh'=>'95.4',
								'others'=>'95.2920689655172',
								'uttarPradesh'=>'95.2',
								'nationalOpenSchool'=>'95',
								'punjab'=>'94.5',
								'meghalaya'=>'94.2',
								'orissa'=>'93.67',
								'uttrakhand'=>'92.4',
								'bihar'=>'92',
								'goa'=>'91.63',
								'nagaland'=>'90.5',
								'tripura'=>'89.6',
								'mizoram'=>'88.8',
								'jharkhand'=>'83.6'
							);

$IIM_Indore_Xth = 36*(($data['xthPercentage'] - 0)/($IIM_Indore_Max_board_score[$data['xthBoard']] - 0));
$IIM_Indore_XIIth = 40*(($data['xiithPercentage'] - 0)/($IIM_Indore_Max_board_score[$data['xiithBoard']] - 0));

$IIM_Indore_GD_criteria = array(
	'female' => 4,
	'male' => 0,
	'transgender' => 0
); 

$IIM_Indore_GD = $IIM_Indore_GD_criteria[$data['gender']];
//$IIM_Indore_required_score = ($IIM_Indore_minimum_cs[$data['category']] - $IIM_Indore_Xth - $IIM_Indore_XIIth - $IIM_Indore_GD)/27;
$IIM_Indore_required_score = ($IIM_Indore_minimum_cs[$data['category']] - ($IIM_Indore_Xth + $IIM_Indore_XIIth + $IIM_Indore_GD))/20;


$IIM_Indore_ScoreData = array(
							'required_percentile' => $percentileData[getValidPercentile($IIM_Indore_required_score*300)],
							'cutoff_percentile' => $IIM_Indore_cutoff_percentile[$data['category']],
							'score_calculated' => 'YES'
							);



/* 
 * ********************************************************************************************************************
 *  IIM Kashipur Score Criteria
 * ********************************************************************************************************************
*/


$IIM_Kashipur_ScoreData = array(
							'required_percentile' => $IIM_Kashipur_cutoff_percentile[$data['category']]['Total_Percentile'],
							'cutoff_percentile' => $IIM_Kashipur_cutoff_percentile[$data['category']],
							'score_calculated' => 'YES'
							);
/* 
 * ********************************************************************************************************************
 *  IIM Raipur Score Criteria
 * ********************************************************************************************************************
*/

$IIM_Raipur_ScoreData = array(
							'required_percentile' => $IIM_Raipur_cutoff_percentile[$data['category']]['Total_Percentile'],
							'cutoff_percentile' => $IIM_Raipur_cutoff_percentile[$data['category']],
							'score_calculated' => 'YES'
							);

/* 
 * ********************************************************************************************************************
 *  IIM Udaipur Score Criteria
 * ********************************************************************************************************************
*/


$IIM_Udaipur_ScoreData = array(
							'required_percentile' => $IIM_Udaipur_cutoff_percentile[$data['category']]['Total_Percentile'],
							'cutoff_percentile' => $IIM_Udaipur_cutoff_percentile[$data['category']],
							'score_calculated' => 'YES'
							);

/* 
 * ********************************************************************************************************************
 *  IIM Trichy Score Criteria
 * ********************************************************************************************************************
*/


$IIM_Trichy_ScoreData = array(
							'required_percentile' => $IIM_Trichy_cutoff_percentile[$data['category']]['Total_Percentile'],
							'cutoff_percentile' => $IIM_Trichy_cutoff_percentile[$data['category']],
							'score_calculated' => 'YES'
							);

/* 
 * ********************************************************************************************************************
 *  IIM Ranchi Score Criteria
 * ********************************************************************************************************************
*/


$IIM_Ranchi_ScoreData = array(
							'required_percentile' => $IIM_Ranchi_cutoff_percentile[$data['category']]['Total_Percentile'],
							'cutoff_percentile' => $IIM_Ranchi_cutoff_percentile[$data['category']],
							'score_calculated' => 'YES'
							);

/* 
 * ********************************************************************************************************************
 *  IIM Bodhgaya Score Criteria
 * ********************************************************************************************************************
*/


$IIM_Bodhgaya_ScoreData = array(
							'required_percentile' => $IIM_Bodhgaya_cutoff_percentile[$data['category']]['Total_Percentile'],
							'cutoff_percentile' => $IIM_Bodhgaya_cutoff_percentile[$data['category']],
							'score_calculated' => 'YES'
							);

/* 
 * ********************************************************************************************************************
 *  IIM Sambalpur Score Criteria
 * ********************************************************************************************************************
*/


$IIM_Sambalpur_ScoreData = array(
							'required_percentile' => $IIM_Sambalpur_cutoff_percentile[$data['category']]['Total_Percentile'],
							'cutoff_percentile' => $IIM_Sambalpur_cutoff_percentile[$data['category']],
							'score_calculated' => 'YES'
							);

/* 
 * ********************************************************************************************************************
 *  IIM Nagpur Score Criteria
 * ********************************************************************************************************************
*/


$IIM_Nagpur_ScoreData = array(
							'required_percentile' => $IIM_Nagpur_cutoff_percentile[$data['category']]['Total_Percentile'],
							'cutoff_percentile' => $IIM_Nagpur_cutoff_percentile[$data['category']],
							'score_calculated' => 'YES'
							);

/* 
 * ********************************************************************************************************************
 *  IIM Amritsar Score Criteria
 * ********************************************************************************************************************
*/


$IIM_Amritsar_ScoreData = array(
							'required_percentile' => $IIM_Amritsar_cutoff_percentile[$data['category']]['Total_Percentile'],
							'cutoff_percentile' => $IIM_Amritsar_cutoff_percentile[$data['category']],
							'score_calculated' => 'YES'
							);

/* 
 * ********************************************************************************************************************
 *  IIM Sirmaur Score Criteria
 * ********************************************************************************************************************
*/

$IIM_Sirmaur_ScoreData = array(
							'score_calculated' => 'NO'
							);

/* 
 * ********************************************************************************************************************
 *  IIM Visakhapatnam Score Criteria
 * ********************************************************************************************************************
*/


$IIM_Visakhapatnam_ScoreData = array(
							'required_percentile' => $IIM_Visakhapatnam_cutoff_percentile[$data['category']]['Total_Percentile'],
							'cutoff_percentile' => $IIM_Visakhapatnam_cutoff_percentile[$data['category']],
							'score_calculated' => 'YES'
							);