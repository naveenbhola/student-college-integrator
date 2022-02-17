<?php 
// ids will be replaced by actual db entry data.
$config['otherAttributes'] = array(
		'educationType'  => array(
				'id'     => 5,
				'label'  => 'Education Type',
				'value'  => array(array('id'=>20, 'name'=>'Full Time - Classroom'), array('id'=>33, 'name'=>'Part Time - Classroom'))
			),
		'deliveryMethod' => array(
				'id'     => 8,
				'label'  => 'Delivery Method',
				'value'  => array(array('id'=>34, 'name'=>'Distance / Correspondence'), array('id'=>39, 'name'=>'Online'),array('id'=>36,'name'=>'Virtual Classroom'),array('id'=>35,'name'=>'On The Job (Apprenticeship)'))
			)
	);

$config['locationConstituentMapping'] = array(
	'1'=>'city',
	'2'=>'state',
	'3'=>'country',
	'4'=>'popularLocation'
	);

$config['instituteConstituentMapping'] = array(
	'1'=>'college',
	'2'=>'university',
	'3'=>'group'
	);