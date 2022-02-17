<?php
$sendSMSToUserCriteria[] = array(
							'criteria' =>array(
												'stream'        => 2,
												'baseCourses'   => array(
																	10
																   ),
												'educationType' => array(
																		20
																   )
											  ),
							'smsText' => array(
												"Dear B.Tech Aspirant, Check out the list of Popular Private Engineering Exams in India that you can consider this year. Know more www.bit.ly/Exams-2k18"
   											  )
						   );

$config['smsCriteria']  = $sendSMSToUserCriteria;


