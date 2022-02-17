<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['COUNSELOR_INFO'] = array(
                                     '362'=>array('counselorName'=>'Iqra',
                                                'counselorId'=>'31907',
                                                'counselorBio'=>'Perfectionist to the core, Iqra guides the Shiksha Apply team by her sound experience of 7 years as a Study Abroad Consultant. Researching, training, organizing events, conducting career workshops has been her forte and finding the right place for every student her goal! Lively at heart, Iqra is a movie buff and prefers popcorn or quality family time over traditional home cooked meals.',
                                                'counselorExpertise'=>'MS, MBA & UG in Europe'),
                                     '385'=>array('counselorName'=>'Udit',
                                                'counselorId'=>'5091368',
                                                'counselorBio'=>'With 6 years of sound experience in the education sector, Udit has effectively created a niche for himself at Shiksha. Zealous and dedicated, he believes in quality over quantity and has to his credit various successful admissions to the top business & graduate programs in USA and Canada. The unofficial gadget guru of the team, Udit loves everything technical and indulges in the best.',
                                                'counselorExpertise'=>'MS, MBA & UG in US, Canada & Singapore'),
                                     /*'388'=>array('counselorName'=>'Ashmeet',
                                                'counselorId'=>'5446766',
                                                'counselorBio'=>'An adventurous travel enthusiast, Ashmeet brings to Shiksha 8 years of strong experience in Study Abroad Counselling and many success stories! A people’s person, Ashmeet loves meeting new people, visiting new countries and exploring the different cultures. When not helping a student, Ashmeet loves to read and manage her home. She believes that if you want it, you can get it!',
                                                'counselorExpertise'=>'MS, MBA & UG in Australia, New Zealand & Canada'),
                                     */
                                     '408'=>array('counselorName'=>'Armaan',
                                                'counselorId'=>'5405594',
                                                'counselorBio'=>'An Electrical Engineer by qualification. Armaan found his zest in helping students and switched over as a full time counsellor for study abroad aspirants. His over two years of experience has helped him guide students to top universities abroad. A travel enthusiast, Armaan believes that there is too much to explore and he has only just started!',
                                                'counselorExpertise'=>'MS & MBA in USA, Canada & Singapore'),
                                     '409'=>array('counselorName'=>'Anamika',
                                                'counselorId'=>'5093210',
                                                'counselorBio'=>'Vibrant and versatile, Anamika is a go-getter and does not believe in tomorrows. It is her dedication that has ensured the 3 years of experience to result in many successful applications abroad. Her aim in life – do it right and do it now. On a relaxed note, Anamika loves to cook and experiment with the diverse Indian cuisine.',
                                                'counselorExpertise'=> 'MBA, MS and UG in Canada & US')
                                );


/*$config['successVideoArray'] = array(
                                        array('videoUrl'=>'https://www.youtube.com/watch?v=L0LWokVXSxs',
                                              'articleId'=>'1048'),
                                        array('videoUrl'=>'https://www.youtube.com/watch?v=Xiu5mw4iBLM',
                                              'articleId'=>'1049'),
                                        array('videoUrl'=>'https://www.youtube.com/watch?v=pVCuVkX0bzU',
                                              'articleId'=>'1053'),
                                        array('videoUrl'=>'https://www.youtube.com/watch?v=Z0x53QY8eew',
                                              'articleId'=>'1036'),
                                        array('videoUrl'=>'https://www.youtube.com/watch?v=s1DBclZSS-A',
                                              'articleId'=>'1037')
                                    );*/
/*
 * Note : keep the exam & score ordered as SAT>GMAT>GRE>IELTS>TOEFL>PTE
 */
$config['successVideoArray'] = array(
                                    array(
                                          'videoId'=>'aKTpaRnis-g',
                                          'articleId'=>'1192',
                                          'articleURL'=>SHIKSHA_STUDYABROAD_HOME.'/nikhil-talks-about-his-experience-of-getting-admission-with-a-scholarship-shiksha-success-stories-articlepage-1192',
                                          'name'=>'Nikhil',
                                          'image'=>'nikhil',
                                          'exam'=>array('GMAT: 640','TOEFL: 100'),
                                          'univName'=>'Simon Fraser University',
                                          'quotes'=>'I was also apprehensive about their service as it was free but gradually...'
                                          ),
                                    array(
                                          'videoId'=>'hMeMEkPHIB8',
                                          'articleId'=>'1199',
                                          'articleURL'=>SHIKSHA_STUDYABROAD_HOME.'/sourabh-shares-his-experience-of-successfully-getting-admits-from-universities-in-us-and-canada-shiksha-success-stories-articlepage-1199',
                                          'name'=>'Sourabh',
                                          'image'=>'saurabh',
                                          'exam'=>array('GRE: 310'),
                                          'univName'=>'University of Illinois (Chicago Campus)',
                                          'quotes'=>'My queries were answered quickly and follow-ups were given by the Shiksha team regarding the applications'
                                          ),
                                    array(
                                          'videoId'=>'6kEzLc1Oqio',
                                          'articleId'=>'1189',
                                          'articleURL'=>SHIKSHA_STUDYABROAD_HOME.'/ankur-talks-about-getting-admit-from-his-dream-university-shiksha-success-stories-articlepage-1189',
                                          'name'=>'Ankur',
                                          'image'=>'ankur',
                                          'exam'=>array('GRE: 300', 'TOEFL: 87'),
                                          'univName'=>'Clark University',
                                          'quotes'=>'Initially, I was totally confused.. I took a chance and registered at Shiksha Study Abroad...'
                                          ),
                                    array(
                                          'videoId'=>'pgnYKRflEck',
                                          'articleId'=>'1201',
                                          'articleURL'=>SHIKSHA_STUDYABROAD_HOME.'/issac-got-admit-in-the-course-he-was-looking-for-after-initial-setbacks-shiksha-success-stories-articlepage-1201',
                                          'name'=>'Isaac',
                                          'image'=>'Isaac',
                                          'exam'=>array('GRE: 278', 'IELTS: 6.5'),
                                          'univName'=>'Pace University',
                                          'quotes'=>'Main difference between Shiksha & other consultants is the transparency. In my previous experiences...'
                                          ),
                                    array(
                                          'videoId'=>'tK1c-BJyTaE',
                                          'articleId'=>'1197',
                                          'articleURL'=>SHIKSHA_STUDYABROAD_HOME.'/how-annapurna-survived-some-wrong-decisions-while-applying-for-studying-abroad-shiksha-success-stories-articlepage-1197',
                                          'name'=>'Annapurna',
                                          'image'=>'anna',
                                          'exam'=>array('GMAT: 550', 'IELTS: 8'),
                                          'univName'=>'Pittsburg State University',
                                          'quotes'=>'I started with a wrong foot, I went to a consultancy.. then a friend of mine suggested Shiksha to me and everything just fell into place.'
                                          ),
                                    array(
                                          'videoId'=>'AaC2y21Q2VA',
                                          'articleId'=>'1193',
                                          'articleURL'=>SHIKSHA_STUDYABROAD_HOME.'/parth-shares-his-experience-of-getting-admission-with-a-scholarship-shiksha-success-stories-articlepage-1193',
                                          'name'=>'Parth',
                                          'image'=>'parth',
                                          'exam'=>array('GMAT: 620'),
                                          'univName'=>'American University',
                                          'quotes'=>'Counselors at Shiksha care about your application. They asked me about the status of my application on regular basis.'
                                          )
                                );
?>
