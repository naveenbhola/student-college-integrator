<?php
	//variables below used while IIM Call Predictor using database logic
$config = array();
define("DESK_IIM_RESULTS_LIMIT",10);
define("MOB_IIM_RESULTS_LIMIT",10);
define("IIM_RESULTS_YEAR",2018);
$config["ICP_fields_Mapping"] = array(
		'graduationPercentage' => 'Graduation',
		'xthPercentage' => 'Class X',
		'xiithPercentage' => 'Class XII',
		'VRC_Percentile' => 'VRC',
		'DILR_Percentile' => 'DI-LR',
		'QA_Percentile' => 'QA',
		'cat_percentile' => 'CAT',
		'X_XII_Science_avg' => 'Average of<br/>X & XII',
		'X_XII_Commerce_avg' => 'Average of<br/>X & XII',
		'X_XII_Arts_avg' => 'Average of<br/>X & XII',
		'X_XII_avg' => 'Average of<br/>X & XII'
	);
//below institute ids data showing on top of iim call predictor result
$config['default_institute_ids'] = array(307,318,20190,333,29623,20188,32736);
?>
