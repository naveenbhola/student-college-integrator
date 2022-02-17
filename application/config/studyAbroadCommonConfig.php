<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* CONTENT_LIFECYCLE_TAGS:
 * - tags required for study abroad content organisation pages,
 * - must be set from CMS.
 * - 2D array : 1st dim - first level tags, 2nd dim - second level tags
 */
$config['CONTENT_LIFECYCLE_TAGS'] = array(
                                    'COUNTRY'               => array(
                                                                     'ORDER' => 1,  
                                                                     'LEVEL1_VALUE' => 'Country',
                                                                     'LEVEL2_SOURCE' => 'country',
                                                                     'LEVEL2_VALUES' => array()
                                                                     ),
                                    'COURSE'                => array(
                                                                     'ORDER' => 2,  
                                                                     'LEVEL1_VALUE' => 'Course',
                                                                     'LEVEL2_SOURCE' => 'course',
                                                                     'LEVEL2_VALUES' => array()
                                                                     ),
                                    'EXAM'                  => array(
                                                                     'ORDER' => 3,  
                                                                     'LEVEL1_VALUE' => 'Exam',
                                                                     'LEVEL2_SOURCE' => 'country',
                                                                     'LEVEL2_VALUES' => array()
                                                                     ),
                                    'COLLEGE'               => array(
                                                                     'ORDER' => 4,  
                                                                     'LEVEL1_VALUE' => 'College',
                                                                     'LEVEL2_SOURCE' => 'country',
                                                                     'LEVEL2_VALUES' => array()
                                                                     ),
                                    'APPLICATION_PROCESS'   => array(
                                                                     'ORDER' => 5,  
                                                                     'LEVEL1_VALUE' => 'Application Process',
                                                                     'LEVEL2_SOURCE' => 'self',
                                                                     'LEVEL2_VALUES' => array('PROCESS' => 'Process',
                                                                                              'DEADLINES' => 'Deadlines',
                                                                                              'HOW_TO' => 'How to',
                                                                                              'DOCUMENTS' => 'Documents')
                                                                     ),
                                    'SCHOLARSHIP_FUNDS'     => array(
                                                                     'ORDER' => 6,  
                                                                     'LEVEL1_VALUE' => 'Scholarship & Funds',
                                                                     'LEVEL2_SOURCE' => 'self',
                                                                     'LEVEL2_VALUES' => array('SCHOLARSHIP' => 'Scholarship',
                                                                                              'FUNDING' => 'Funding')
                                                                     ),
                                    'VISA_DEPARTURE'        => array(
                                                                     'ORDER' => 7,  
                                                                     'LEVEL1_VALUE' => 'Visa & Departure',
                                                                     'LEVEL2_SOURCE' => 'country',
                                                                     'LEVEL2_VALUES' => array()
                                                                     ),
                                    'STUDENT_LIFE'          => array(
                                                                     'ORDER' => 8,  
                                                                     'LEVEL1_VALUE' => 'Student Life',
                                                                     'LEVEL2_SOURCE' => 'country',
                                                                     'LEVEL2_VALUES' => array()
                                                                     )
                                          );


/**
 * Hide exam list
 */
$config['HIDE_EXAM_IDS'] = array(11,13,15);