<?php 
$config['customElements'] = array(
			'stream' => array(
				'label'      => 'Stream',
				'mandatory'  => 'no',
				'validation' => 'validateStr',
				'isVisible'  => 'yes',
				'cssClass'   => '',
				'id'         => 'stream',
				'showPrimaryHierarchy' => 'yes',
				'customAttr' => array('onchange'=>'hierarchyMappingForm.getAllSubstreamsAndSpecializationByStream(this); hierarchyMappingForm.getHierarcyBasedAtrributes(this, \'\', \'\', \'baseCourse\');')
			),
			'subStream' => array(
				'label'      => 'Sub Stream',
				'mandatory'  => 'no',
				'validation' => 'validateStr',
				'isVisible'  => 'yes',
				'cssClass'   => '',
				'id'         => 'subStream',
				'customAttr' => array('onchange'=>'hierarchyMappingForm.getAllSpecializationsByStreamAndSubstream(this); hierarchyMappingForm.getHierarcyBasedAtrributes(this, \'\', \'\', \'baseCourse\');')
			),
			'specialization' => array(
				'label'      => 'Specialization',
				'mandatory'  => 'no',
				'validation' => 'validateStr',
				'isVisible'  => 'yes',
				'cssClass'   => '',
				'id'         => 'specialization',
				'customAttr' => array('onchange'=>'hierarchyMappingForm.getAllBaseCoursesByHierarchy(this); hierarchyMappingForm.getHierarcyBasedAtrributes(this, \'\', \'\', \'baseCourse\');')
			),
			'course' => array(
				'label'      => 'Course Name',
				'mandatory'  => 'no',
				'validation' => 'validateStr',
				'isVisible'  => 'yes',
				'cssClass'   => '',
				'id'         => 'course',
				'customAttr' => '',
				'type' => 'auto suggestor'
			),
			'institute' => array(
					'label'      => 'Institute',
					'mandatory'  => 'no',
					'validation' => 'validateStr',
					'isVisible'  => 'yes',
					'cssClass'   => '',
					'id'         => 'institute',
					'customAttr' => ''
			),
			'careers' => array(
					'label'      => 'Careers',
					'mandatory'  => 'no',
					'validation' => 'validateStr',
					'isVisible'  => 'yes',
					'cssClass'   => '',
					'id'         => 'careers',
					'customAttr' => ''
				),
			'otherAttributes' => array(
					'label'      => 'Education Mode',
					'mandatory'  => 'no',
					'validation' => 'validateStr',
					'isVisible'  => 'yes',
					'cssClass'   => '',
					'id'         => 'otherAttributes',
					'customAttr' => ''
				),
			'entityNameLabel'    => 'Exam',
	);