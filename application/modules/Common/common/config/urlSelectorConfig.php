<?php
$config = array('desktop'=>array(
	            			'default' =>array('selector' =>array('#main-wrapper'),
	            	                           'heading'  =>array('Main Wrapper')
	            			),
							'homePage'=>array('selector' =>array('#_innerNav', '#_tabCnt', '.linkContainer', '.articleSlide', '.articleLatest','#footer .fotr_seo'),
					                          'heading' =>array('GNB', 'CategoryFold', 'View questions on', 'FEATURED ARTICLES', 'Latest Updates', 'Footer')
							)
               	),
				'mobile'=>array(
					       'default'=>array('selector'=>array('#wrapper'),
					       					'heading'=>array('Main Wrapper')
					       	)
				)
			);
?>