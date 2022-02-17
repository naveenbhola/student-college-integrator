<?php

	$config['validEntityType'] = array('course_name'=>'course_name',
							'course_level'=>'course_level',
							'mr_course'=>'mr_course',
							'ldb_course_level'=>'ldb_course_level',
							'primary_institute'=>'primary_institute',
							'parent_institute'=>'parent_institute',
							'oaf_course'=>'oaf_course',
							'oaf_gender'=>'oaf_gender',
							'oaf_paymentmode'=>'oaf_paymentmode',
							'stream'=>'stream',
							'base_course'=>'base_course',
							'ldb_parent_institute'=>'ldb_parent_institute',
							'ldb_primary_institute'=>'ldb_primary_institute'
						);


    $config['customizedPortingIds'] = array();
    $config['customizedButtons'] = array(9,10,55,57,59,63,65,61,67,69);
    $config['customizedFieldMapping'] = array(

    	'RES_CUS_Course' => array('customField'=>'course_name','type'=>'course','entity_id_text'=>'Course ID','entity_name_text'=>'Course Name'),

		'RES_CUS_Course_Level' => array('customField'=>'course_level','type'=>'course','entity_id_text'=>'Course ID','entity_name_text'=>'Course Name'),

		'RES_CUS_Parent_Institute' => array('customField'=>'parent_institute','type'=>'institute','entity_id_text'=>'Institute ID','entity_name_text'=>'Institute Name'),
		
		'RES_CUS_Primary_Institute' => array('customField'=>'primary_institute','type'=>'institute','entity_id_text'=>'Institute ID','entity_name_text'=>'Institute Name'),
		
		'MAT_RES_CUS_COURSE' => array('customField'=>'mr_course','type'=>'course','entity_id_text'=>'Course ID','entity_name_text'=>'Course Name'),
		
		'LDB_CUS_Stream' => array('customField'=>'stream','type'=>'stream','entity_id_text'=>'Stream ID','entity_name_text'=>'Stream Name'),
		
		'LDB_CUS_BaseCourse' => array('customField'=>'base_course','type'=>'basecourse','entity_id_text'=>'BaseCourse ID','entity_name_text'=>'BaseCourse Name'),

		'MAT_RES_CUS_Parent_Institute' => array('customField'=>'ldb_parent_institute','type'=>'institute','entity_id_text'=>'Institute ID','entity_name_text'=>'Institute Name'),

		'MAT_RES_CUS_Primary_Institute' => array('customField'=>'ldb_primary_institute','type'=>'institute','entity_id_text'=>'Institute ID','entity_name_text'=>'Institute Name'),

		'MAT_RES_CUS_Course_Level' => array('customField'=>'ldb_course_level','type'=>'course','entity_id_text'=>'Course ID','entity_name_text'=>'Course Name')

    );
?>
