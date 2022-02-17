<?php

// Mapping of section identifier and page type(seo url)
$config['abroad_exam_page_section_details'] = array(
                                 1=>array('name'            =>'about section',
                                          'title'           =>'About the exam',
                                          'breadcrumbTitle' =>'',
                                          'text'            =>'This exam is accepted across many universities in various countries.',
                                          'urlPattern'      =>'',
                                          'seoTitle'        =>'<exam-name>: <exam-full-form> | Shiksha.com',
                                          'seoDescription'  =>'<exam-full-form> (<exam-name>) is one of the most popular MBA entrance exams to assesses your aptitude for MBA programmes across many countries. Get complete details about <exam-name> exam admission process, preparation tips, latest news, and syllabus on StudyAbroad.Shiksha.com.'),
                                 2=>array('name'            =>'exam pattern',
                                          'title'           =>'<exam-name> Exam Pattern',
                                          'breadcrumbTitle' =>'<exam-name> Exam pattern',
                                          'text'            =>'Understand the various sections covered under the exam, along with exam duration and marks allotted for each section.',
                                          'urlPattern'      =>'exam-pattern',
                                          'seoTitle'        =>'<exam-name> Exam Pattern | Shiksha.com',
                                          'seoDescription'  =>'Learn more about <exam-name> exam pattern and other important details about the <exam-name> exam on Shiksha.com'),
                                 3=>array('name'            =>'scoring section',
                                          'title'           =>'Scoring in <exam-name>',
                                          'breadcrumbTitle' =>'Scoring in <exam-name>',
                                          'text'            =>'Find out how your performance in the test will be evaluated and how the final scores will be arrived at.',
                                          'urlPattern'      =>'exam-score',
                                          'seoTitle'        =>'<exam-name> Scoring | Shiksha.com',
                                          'seoDescription'  =>'Understand more about how scoring is done in <exam-name> exam on Shiksha.com'),
                                 4=>array('name'            =>'important dates',
                                          'title'           =>'Important dates',
                                          'breadcrumbTitle' =>'<exam-name> Important dates',
                                          'text'            =>'Get all the important dates at a glance and know when you can appear for the exam.',
                                          'urlPattern'      =>'important-dates',
                                          'seoTitle'        =>'<exam-name> Dates | Shiksha.com',
                                          'seoDescription'  =>'Learn more about important dates for the <exam-name> exam on Shiksha.com'),
                                 5=>array('name'            =>'prepration tips',
                                          'title'           =>'Preparation tips',
                                          'breadcrumbTitle' =>'<exam-name> Preparation tips',
                                          'text'            =>'Ensure that you are well prepared for the exam, with these useful prep tips.',
                                          'urlPattern'      =>'preparation-tips',
                                          'seoTitle'        =>'<exam-name> Preparation Tips | Shiksha.com',
                                          'seoDescription'  =>'Get preparation tips for the <exam-name> exam. Learn tips, tricks, do\'s, don\'ts and much more on Shiksha.com'),
                                 6=>array('name'            =>'practice and sample paper',
                                          'title'           =>'Practice & sample papers',
                                          'breadcrumbTitle' =>'<exam-name> Practice & sample papers',
                                          'text'            =>'Give your preparation a boost with these mock papers to help you understand the question paper pattern.',
                                          'urlPattern'      =>'practice-test',
                                          'seoTitle'        =>'<exam-name> Sample Papers | Shiksha.com',
                                          'seoDescription'  =>'View sample papers for the <exam-name> exam on Shiksha.com'),
                                 7=>array('name'            =>'syllabus',
                                          'title'           =>'<exam-name> Syllabus',
                                          'breadcrumbTitle' =>'<exam-name> Syllabus',
                                          'text'            =>'Find out the exam topics covered under each of the sections for a focussed preparation strategy.',
                                          'urlPattern'      =>'syllabus',
                                          'seoTitle'        =>'<exam-name> Syllabus | Shiksha.com',
                                          'seoDescription'  =>'See the detailed syllabus for the <exam-name> exam on Shiksha.com'),
                                 8=>array('name'            =>'colleges',
                                          'title'           =>'Colleges accepting <exam-name>',
                                          'breadcrumbTitle' =>'Colleges accepting <exam-name>',
                                          'text'            =>'Find a list of the colleges accepting the scores of this exam, all on one page.',
                                          'urlPattern'      =>'colleges',
                                          'seoTitle'        =>'Colleges Accepting <exam-name> | Shiksha.com',
                                          'seoDescription'  =>'See the comprehensive list of colleges which accept <exam-name> score for admissions on Shiksha.com'),
                                 9=>array('name'            =>'articles',
                                          'title'           =>'Articles about <exam-name>',
                                          'breadcrumbTitle' =>'Articles about <exam-name>',
                                          'text'            =>'Check out this space for a collection of related articles and explore more about the exam.',
                                          'urlPattern'      =>'exam-articles',
                                          'seoTitle'        =>'Articles about <exam-name> | Shiksha.com',
                                          'seoDescription'  =>'Read news and articles covering the <exam-name> exam on Shiksha.com'),
                                 );
$config['about_exam'] = array(
                              'GMAT'=>'Graduate Management Admission Test (GMAT) assesses your aptitude for MBA programmes across many countries.',
                              'GRE'=>'Graduate Record Examinations (GRE) assesses your aptitude for engineering and management courses across countries.',
                              'TOEFL'=>'Test of English as a Foreign Language (TOEFL) tests your proficiency levels in the English language.',
                              'IELTS'=>'International English Language Testing System (IELTS) tests your proficiency levels in the English language.',
                              'SAT'=>'Scholastic Assessment Test (SAT) is used to get admissions into undergraduate colleges across many countries.'
                              );

$config['seoDescription'] = array(
                              'GMAT'=>'Take GMAT exam to get admission into world\'s leading business schools. Get complete details about GMAT exam registration, preparation tips, fees and syllabus on Shiksha.com',
                              'GRE'=>'GRE is a standardized test to take admission in most graduate and post-graduate schools. Get complete details about GRE exam registration, preparation tips, fees and syllabus on Shiksha.com',
                              'TOEFL'=>'TOEFL is a standardized English Language test for admission in English speaking countries. Get complete details here about TOEFL exam registration, preparation tips, dates etc.',
                              'IELTS'=>'IELTS is most popular English language test. Get complete details about IELTS exam registration, preparation tips, fees and syllabus on Shiksha.com',
                              'SAT'=>'SAT is a standardized test for admission in more than 2000 colleges. Get complete details about GRE exam registration, preparation tips, fees and syllabus on Shiksha.com'
                              );

//Mapping of Old Exam Guide page content Id to New Exam Pages Content Id For 301 Redirection 
//EXAM PAGE ID : 321 => GMAT, 324 => SAT, 320 => TOEFL, 323 => IELTS, 322 => GRE

$config['abroad_oldExam_to_newExamPage_idMapping'] = array(
                                                    17 => 321,
                                                    304=>324,
                                                    156=>320,
                                                    155=>323,
                                                    154=>322
                                                    );
