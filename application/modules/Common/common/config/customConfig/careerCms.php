<?php 
$config['customElements'] = array(
			'stream' => array(
					'label'      => 'Stream',
					'mandatory'  => 'yes',
					'validation' => 'validateStr',
					'isVisible'  => 'yes',
					'cssClass'   => '',
					'id'         => 'stream',
					'showPrimaryHierarchy' => 'no',
					'customAttr' => array('onchange'=>'hierarchyMappingForm.getAllSubstreamsAndSpecializationByStream(this);')
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
			'entityNameLabel'    => 'Career',
			'redefineAddMorePostion' => true,
			'fromWhere' => 'careerCms',
			'otherFieldInputCount' => 0
	);