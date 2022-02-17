<?php
/*
   Copyright 2015 Info Edge India Ltd

   $Author: Yamini Bisht

   $Id: profileBuilder.php
*/

	 $config = array('profileBuilderData'=>array(
                                    'courseLevel' => array('Ph.D',
                                                           'Bachelors',
                                                           'Certification',
                                                           'Masters'),
                                    
                                    'PopularCountries' => array('India',
                                                                'USA',
                                                                'CANADA',
                                                                'Australia',
                                                                'UK',
                                                                'Singapore',
                                                                'Germany',
                                                                'New Zealand'),
				    'PopularCities' => array('Chandigarh',
								'Delhi/NCR',
								'Mumbai',
								'Bangalore',
								'Pune',
								'Chennai',
								'Kolkata',
								'Hyderabad'),
                                    'userProfileCustomCourseLevel' => array(
                                        'phd' => 'Ph.D',
                                        'masters' => 'Masters',
                                        'bachelors' => 'Bachelors',
                                        'xiith' => 'Class 12',
                                        'xth' => 'Class 10'
                                      ),
                                    'tenthBoard' => array(
                                        'CBSE' => 'CBSE',
                                        'ICSE' => 'ICSE/State Boards',
                                        'IGCSE' => 'Cambridge IGCSE'
                                      ),
                                    'allowedFollowType' => array('stream','country','course_level','stream_interest','specialization','degree','countries_interest','cities_interest'),
                                    'allowedPrivacyFieldArray' => array("shortbio","location","education","workexperience","expertise", "eduPref", "activitystats","xth","xiith","bachelors","phd","masters","Experience"),

                                    'privateFieldstoDBMapping' => array(
                                        'xth' => array('InstituteName10','CourseCompletionDate10','Board10','Subjects10','Marks10'),
                                        'xiith' => array('InstituteName12','CourseCompletionDate12','Board12','Specialization12','Marks12'),
                                        'bachelors' => array('InstituteNameUG','CourseCompletionDateUG','BoardUG','SubjectsUG','NameUG','CourseSpecializationUG','MarksUG'),
                                        'masters' => array('InstituteNamePG','CourseCompletionDatePG','BoardPG','SubjectsPG','NamePG','CourseSpecializationPG','MarksPG'),
                                        'phd' => array('InstituteNamePHD','CourseCompletionDatePHD','BoardPHD','SubjectsPHD','NamePHD','CourseSpecializationPHD','MarksPHD'),
                                        'location' => array('City','Country','Locality'),
                                        'shortbio' => array('Bio'),
                                        'Experience' => array('Experience'),
                                        'expertise' => 'expertise',
                                        'activitystats' => 'activitystats')
  
	    		)  
	    );
 
?>
