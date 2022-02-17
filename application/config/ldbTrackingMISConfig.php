<?php

$config['leftMenuArray'] = array(
			'Matched Response Allocation Metrics' => array(
						'className' => "fa-home",
						'children' => array(
											'Lead Matching'	=> SHIKSHA_HOME."/trackingMIS/ldb/LeadMatching/Y",
											'Lead Allocation' => SHIKSHA_HOME."/trackingMIS/ldb/LeadAllocation/Y"
										)
						),
			'Non-MR Allocation Metrics' => array(
						'className' => "fa-home",
						'children' => array(
											'Lead Matching'	=> SHIKSHA_HOME."/trackingMIS/ldb/LeadMatching/N",
											'Lead Allocation' => SHIKSHA_HOME."/trackingMIS/ldb/LeadAllocation/N"
										)
						),
					);

