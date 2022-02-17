<?php 
$config['SEARCH_PARAMS_FIELDS_ALIAS'] = array(
		"keyword"            => "q",
		"pageNumber"         => "pn",
		"categoryId"         => "c",
		"subCategoryId"      => "sc",
		"city"               => "ct",
		"state"              => "st",
		"locality"           => "lo",
		"specialization"     => "sp",
		"fees"               => "fe",
		"exams"              => "ex",
		"courseLevel"        => "cl",
		"mode"               => "mo",
		"degreePref"         => "dp",
		"affiliation"        => "af",
		"classtimings"		 => "clt",
		"qerResults"         => "qer",
		"searchedAttributes" => "sa",
		"facilities"         => "fa",
		"twoStepClosedSearch"=> "tscs",
		"trackingSearchId"	 => "ts",
		"requestFrom"		 => "rf",
		"trackingFilterId" 	 => "tf",
		"selectedLdbId"		 => "ldb",
		"oldKeyword"		 => "okw",
		"relevantResults"	 => "rr" );
// which subcategories to contain what filters
//show custom filters for these categories else show default filters

$config['FILTER_FACETS_ON_SUBCAT_SEARCH'] = array(
										'56' => array(	
														'location',
														'exams',
														'specialization',
														'degreePref',
														'affiliation',
														'fees',
														'facilities'
													),
										'23' => array(
														'location',
														'exams',
														'specialization',
														'degreePref',
														'affiliation',
														'courseLevel',
														'fees',
														'facilities'
													),
										'59' => array(
														'location',
														'exams',
														'specialization',
														'degreePref',
														'affiliation',
														'mode',
														'facilities',
													),
										'24' => array(
														'location',
														'degreePref',
														'affiliation',
														'specialization',
														'courseLevel'
													),
										'27' => array(
														'location',
														'degreePref',
														'affiliation',
														'specialization',
														'courseLevel'
													),
										'25' => array(
														'location',
														'exams',
														'degreePref',
														'affiliation',
														'specialization',
														'mode',
														'courseLevel',
														'facilities'
													),
										'26' => array(
														'location',
														'degreePref',
														'affiliation',
														'specialization',
														'mode',
														'courseLevel'
													),
										'default'=> array(
														'specialization',
														'location',
														'courseLevel',
														'degreePref',
														'affiliation',
														'mode',
														'classTimings'
													)
									);

$config['KEYWORD_TO_SUBCAT_MAPPING'] = array(
		"pgdm" 					=> array('subcategoryId'=>23, 'categoryId'=>3),
		"bba"					=> array('subcategoryId'=>28, 'categoryId'=>3),
		"llb"					=> array('subcategoryId'=>33, 'categoryId'=>9),
		"ccna" 					=> array('subcategoryId'=>101, 'categoryId'=>10),
		"bca" 					=> array('subcategoryId'=>100, 'categoryId'=>10, 'specializationId'=>46),
		"mca" 					=> array('subcategoryId'=>98, 'categoryId'=>10, 'specializationId'=>47),
		"pgdca" 				=> array('subcategoryId'=>98, 'categoryId'=>10, 'specializationId'=>47),
		
		"mba part time" 		=> array('subcategoryId'=>26, 'categoryId'=>3),
		"part time mba" 		=> array('subcategoryId'=>26, 'categoryId'=>3),
		
		"btech" 				=> array('subcategoryId'=>56, 'categoryId'=>2),
		"b tech" 				=> array('subcategoryId'=>56, 'categoryId'=>2),
		"be" 					=> array('subcategoryId'=>56, 'categoryId'=>2),
		"bebtech" 				=> array('subcategoryId'=>56, 'categoryId'=>2),
		"be btech" 				=> array('subcategoryId'=>56, 'categoryId'=>2),

		"mtech" 				=> array('subcategoryId'=>59, 'categoryId'=>2),
		"m tech" 				=> array('subcategoryId'=>59, 'categoryId'=>2),
		"me" 					=> array('subcategoryId'=>59, 'categoryId'=>2),
		"memtech" 				=> array('subcategoryId'=>59, 'categoryId'=>2),
		"me mtech" 				=> array('subcategoryId'=>59, 'categoryId'=>2),
		
		"mba correspondence" 	=> array('subcategoryId'=>24, 'categoryId'=>3),
		"correspondence mba" 	=> array('subcategoryId'=>24, 'categoryId'=>3),
		"mba correspondance" 	=> array('subcategoryId'=>24, 'categoryId'=>3),
		"correspondance mba" 	=> array('subcategoryId'=>24, 'categoryId'=>3),

		"bcom" 					=> array('subcategoryId'=>83, 'categoryId'=>4, 'specializationId'=>527),
		"b com" 				=> array('subcategoryId'=>83, 'categoryId'=>4, 'specializationId'=>527),

		"mcom" 					=> array('subcategoryId'=>83, 'categoryId'=>4, 'specializationId'=>534),
		"m com" 				=> array('subcategoryId'=>83, 'categoryId'=>4, 'specializationId'=>534),
		
		"bed" 					=> array('subcategoryId'=>38, 'categoryId'=>9, 'specializationId'=>477),
		"b ed" 					=> array('subcategoryId'=>38, 'categoryId'=>9, 'specializationId'=>477),

		"ba" 					=> array('subcategoryId'=>32, 'categoryId'=>9, 'specializationId'=>453),
		"ma" 					=> array('subcategoryId'=>32, 'categoryId'=>9, 'specializationId'=>454),

		"bsc it" 				=> array('subcategoryId'=>100, 'categoryId'=>10, 'specializationId'=>48),
		"b sc it" 				=> array('subcategoryId'=>100, 'categoryId'=>10, 'specializationId'=>48),

		"distance mba"			=> array('subcategoryId'=>24, 'categoryId'=>3),
		"mba distance"			=> array('subcategoryId'=>24, 'categoryId'=>3),

		"fashion"				=> array('subcategoryId'=>69, 'categoryId'=>13),
		"fashion design"		=> array('subcategoryId'=>69, 'categoryId'=>13),
		"fashion designing"		=> array('subcategoryId'=>69, 'categoryId'=>13),
		"textile design"		=> array('subcategoryId'=>69, 'categoryId'=>13),
		"textile designing"		=> array('subcategoryId'=>69, 'categoryId'=>13),
		"interior designing"	=> array('subcategoryId'=>70, 'categoryId'=>13),
		
		"bbm"					=> array('subcategoryId'=>28, 'categoryId'=>3),
		"bbs"					=> array('subcategoryId'=>28, 'categoryId'=>3),
		"bms"					=> array('subcategoryId'=>28, 'categoryId'=>3),

		"arts"					=> array('subcategoryId'=>32, 'categoryId'=>9),
		"humanities"			=> array('subcategoryId'=>32, 'categoryId'=>9),

		"bsw"					=> array('subcategoryId'=>36, 'categoryId'=>9, 'specializationId'=>487),
		"msw"					=> array('subcategoryId'=>36, 'categoryId'=>9, 'specializationId'=>491),

		"barch"					=> array('subcategoryId'=>60, 'categoryId'=>2),
		"tourism"				=> array('subcategoryId'=>86, 'categoryId'=>6),

		"ba" 					=> array('subcategoryId'=>32, 'categoryId'=>9, 'specializationId'=>453),
		"ma" 					=> array('subcategoryId'=>32, 'categoryId'=>9, 'specializationId'=>454),

		"bsc it" 				=> array('subcategoryId'=>100, 'categoryId'=>10, 'specializationId'=>48),
		"b sc it" 				=> array('subcategoryId'=>100, 'categoryId'=>10, 'specializationId'=>48),
	);
$config['SPECIALIZATION_FILTERNAME_MAPPING'] = array(
								'Branch' => array(56,58),
								'Exams'  => array(47,48,49,50,51,52,53,54),
								'Specialisation' => array(23,24,25,26,27,31,55,57,59,64,65,66,67,68,238)
								);
