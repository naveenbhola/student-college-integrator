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
					'customAttr' => array('onchange'=>'hierarchyMappingForm.getAllSubstreamsAndSpecializationByStream(this);')
				),
			'tag' => array(
					'label'      => 'Tags',
					'mandatory'  => 'yes',
					'validation' => 'validateStr',
					'isVisible'  => 'yes',
					'cssClass'   => '',
					'id'         => 'tag',
					'customAttr' => ''
				),
			'location' => array(
					'label'      => 'Location',
					'mandatory'  => 'yes',
					'validation' => 'validateStr',
					'isVisible'  => 'yes',
					'cssClass'   => '',
					'id'         => 'location',
					'customAttr' => '',
					'subHeading' => 'Country / State'
				),
			'entityNameLabel'    => 'Video CMS',
			'fromWhere' => 'videoCms',
			'otherFieldInputCount' => 0
	);
