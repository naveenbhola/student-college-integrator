<?php 
$config['commonElements'] = array(
		'stream' => array(
				'label'      => 'Stream',
				'mandatory'  => 'no',
				'validation' => 'validateStr',
				'isVisible'  => 'yes',
				'cssClass'   => '',
				'id'         => 'stream',
				'showPrimaryHierarchy' => 'yes',
				'customAttr' => array('onchange'=>'hierarchyMappingForm.getAllSubstreamsAndSpecializationByStream(this); hierarchyMappingForm.getHierarcyBasedAtrributes(this, \'exam\', \'popularGrouping\', \'baseCourse\');')
			),
		'subStream' => array(
				'label'      => 'Sub Stream',
				'mandatory'  => 'no',
				'validation' => 'validateStr',
				'isVisible'  => 'yes',
				'cssClass'   => '',
				'id'         => 'subStream',
				'customAttr' => array('onchange'=>'hierarchyMappingForm.getAllSpecializationsByStreamAndSubstream(this); hierarchyMappingForm.getHierarcyBasedAtrributes(this, \'exam\', \'popularGrouping\', \'baseCourse\');')
			),
		'specialization' => array(
				'label'      => 'Specialization',
				'mandatory'  => 'no',
				'validation' => 'validateStr',
				'isVisible'  => 'yes',
				'cssClass'   => '',
				'id'         => 'specialization',
				'customAttr' => array('onchange'=>'hierarchyMappingForm.getAllBaseCoursesByHierarchy(this); hierarchyMappingForm.getHierarcyBasedAtrributes(this, \'exam\', \'popularGrouping\', \'baseCourse\');')
			),
		'hierarchyTuplesCount' => 50,
		'otherFieldInputCount' => 51
	);