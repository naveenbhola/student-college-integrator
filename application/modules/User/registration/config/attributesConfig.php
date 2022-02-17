<?php 

$attributeDetailsMapping = array(
							// 1 => array('id' => 1, 'type' => 'Credential', 'name' => 'Degree'),
							// 2 => array('id' => 2, 'type' => 'Course Level', 'name' => 'PG'),	
							3 => array('id' => 3, 'type' => 'Education Type', 'name' => 'Full Time'),
							4 => array('id' => 4, 'type' => 'Education Type', 'name' => 'Part Time'),
							5 => array('id' => 5, 'type' => 'Medium/Delivery Method', 'name' => 'Online', 'parent' => 4),
							6 => array('id' => 6, 'type' => 'Medium/Delivery Method', 'name' => 'Correspondence', 'parent' => 4),
							// 9 => array('id' => 9, 'type' => 'Credential', 'name' => 'Degree'),
							// 10 => array('id' => 10, 'type' => 'Credential', 'name' => 'Diploma'),
							// 14 => array('id' => 14, 'type' => 'Course Level', 'name' => 'UG'),	
							// 15 => array('id' => 15, 'type' => 'Course Level', 'name' => 'PG'),	
							// 16 => array('id' => 16, 'type' => 'Course Level', 'name' => 'Advanced Masters'),
							// 17 => array('id' => 17, 'type' => 'Course Level', 'name' => 'PhD'),	
							// 34 => array('id' => 34, 'type' => 'Course Level', 'name' => 'UG'),	
							// 78 => array('id' => 78, 'type' => 'Credential', 'name' => 'Diploma'),
							-1 => array('id' => -1, 'type' => 'Course Type', 'name' => 'Other UG Degree Courses', 'level' => 14, 'credential' => 9),
							-2 => array('id' => -2, 'type' => 'Course Type', 'name' => 'Other PG Degree Courses', 'level' => 15, 'credential' => 9),
							-3 => array('id' => -3, 'type' => 'Course Type', 'name' => 'Other UG Diploma Courses', 'level' => 14, 'credential' => 10),
							-4 => array('id' => -4, 'type' => 'Course Type', 'name' => 'Other PG Diploma Courses', 'level' => 15, 'credential' => 10),
							-5 => array('id' => -5, 'type' => 'Course Type', 'name' => 'Other Certificate Courses', 'credential' => 11)
							);

$otherValuesMappingForBaseCourseField = array(
		'UG'=>array(
				'Degree'=> array('id'=>'-1',
								 'name'=>'Other UG Degree Courses'	

					),
				'Diploma'=> array('id'=>'-3',
								 'name'=>'Other UG Diploma Courses'	

					)
			),

		'PG'=>array(
				'Degree'=> array('id'=>'-2',
								 'name'=>'Other PG Degree Courses'	

					),
				'Diploma'=> array('id'=>'-4',
								 'name'=>'Other PG Diploma Courses'	

					)
			),

		'Pre UG'=>array(
				'Degree'=> array('id'=>'-6',
								 'name'=>'Other Pre UG Degree Courses'	

					),
				'Diploma'=> array('id'=>'-7',
								 'name'=>'Other Pre UG Diploma Courses'	

					)
			),

		'Advanced Masters'=>array(
				'Degree'=> array('id'=>'-8',
								 'name'=>'Other Advanced Masters Degree Courses'	

					),
				'Diploma'=> array('id'=>'-9',
								 'name'=>'Other Advanced Masters Diploma Courses'	

					)
			),

		'Doctorate'=>array(
				'Degree'=> array('id'=>'-10',
								 'name'=>'Other Doctorate Degree Courses'	

					),
				'Diploma'=> array('id'=>'-11',
								 'name'=>'Other Doctorate Diploma Courses'	

					)
			),

		'Post Doctorate'=>array(
				'Degree'=> array('id'=>'-12',
								 'name'=>'Other Post Doctorate Degree Courses'	

					),
				'Diploma'=> array('id'=>'-13',
								 'name'=>'Other Post Doctorate Diploma Courses'	

					)
			),

		'None'=>array(
				'Degree'=> array('id'=>'-14',
								 'name'=>'Other None Degree Courses'	

					),
				'Diploma'=> array('id'=>'-15',
								 'name'=>'Other None Diploma Courses'	

					)
			),

	);

?>