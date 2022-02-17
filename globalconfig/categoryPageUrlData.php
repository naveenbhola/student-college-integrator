<?php
/*for only ug categories
which should have colleges or courses. Can be extended for specialization as well.
By default specialization takes that of parent subcategory*/
$typeMappingForCatOrSubcat = array(
		'courses' => array(
				'category' 		 => array(4,6,7,10,11,12,13),
				'subcategory' 	 => array(15,16,17,19,20,21,22,41,42,43,44,45,46,70,71,72,73,74,75,76,77,78,79,80,81,82,85,86,87,88,89,90,91,92,93,94,95,96,97,99,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,124,125,126,127,233,234)
			),
		'classes' => array(
				'category'		 => array(14),
				'subcategory'	 => array(47,48,49,50,51,52,53,54,232)
			)
	);
// types of meta titles possible
$metaTitleTypeMapping = array(
		'type1' => '{coursetype} {type} in {location} | Shiksha.com',
		'type2' => 'Medical {type} in {location} | Shiksha.com'
	);

// types of h1 titles possible
$h1TitleTypeMapping = array(
		'type1' => '{coursetype} {type} in {location}',
		'type2' => 'Medical {type} in {location}'
	);
// types of meta description possible
$metaDescriptionMapping = array(
		'type1' => 'View {resultCount} {coursetype} {type} in {location}. See course details, fees, duration, eligibility and other details.',
		'type2' => 'View {resultCount} {coursetype} {type} in {location}. See eligibility, admission process, fees, course details, reviews and other details.',
		'type3' => 'View {resultCount} {coursetype} {type} in {location}. See admission process, fees, course details, eligibility and other details.',
		'type4' => 'View {resultCount} {coursetype} {type} in {location}. See fees, contact details, admissions, reviews and other details.',
		'type5' => 'View {resultCount} Medical {type} in {location}. See admission process, fees, eligibility and other details.'
	);
// mapping of categories and subcategories to types of meta titles and descriptions.
// can be extended for specialization as well. By default takes that of parent subcategory
$categoryMetaTitleMapping = array(
		'type1'=>array(
				2,4,5,6,7,9,10,11,12,13,14
			),
		'type2'=>array()
	);

$categoryMetaDescMapping = array('type1'=>array(4,6,7,10,11,12),'type2'=>array(2),'type3'=>array(9),'type4'=>array(14),'type5'=>array(5));

$subcategoryMetaTitleMapping = array(
		'type1'=>array(
				15,16,17,18,19,20,21,22,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,57,58,60,61,62,63,64,65,66,67,68,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,124,125,126,127,128,129,130,131,132,134,135,136,137,138,139,140,141,142,143,232,233,234,235,237,238
			),
		'type2'=>array(133)
	);

$subcategoryMetaDescMapping = array(
		'type1'=>array(15,16,17,18,19,20,21,22,41,42,43,44,45,46,74,75,76,77,78,79,80,81,82,84,85,86,87,88,89,90,91,92,93,94,95,96,97,99,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,124,125,126,127,128,129,130,131,132,134,135,136,137,138,139,140,141,142,143,233,234,235,237),
		'type2'=>array(57,58,60,61,62,63,64,65,66,67,68,83,98,100,238),
		'type3'=>array(32,33,34,35,36,37,38,39,40),
		'type4'=>array(47,48,49,50,51,52,53,54,232),
		'type5'=>array(133)
	);

// $specializationMetaTitleMapping = array('type1'=>array(),'type2'=>array());

// $specializationMetaDescMapping = array('type1'=>array(),'type2'=>array(),'type3'=>array(),'type4'=>array(),'type5'=>array());


$categoryURLDataIndia = array(

2 => array(
		'url' => 'science-engineering-colleges-in-{location}',
		'keywords' => 'Science Engineering Colleges in {location}, Science Engineering Courses in {location}, Science Engineering Courses, Engineering Colleges in {location}, Engineering courses, universities, institutes, career, career options, Education'
),
3 => array(
		//'url' => 'mba-in-{location}',
		'url' => 'management-colleges-in-{location}',
		'title' => 'MBA in {location} - MBA Courses, Colleges, Institutes - Shiksha.com',
		'description' => 'Search for MBA in {location} - Get a list of MBA (Management) Courses, Colleges and institutes. Know more about the full and part time MBA programs',
		'keywords' => 'MBA, management, MBA Courses in {location}, management courses, management colleges, {location} MBA Colleges, List of MBA Colleges in {location}, MBA in {location}, mba courses, Business Schools in {location}, b-schools in {location}, MBA courses, mba colleges, mba institutes, mba education'
),
4 => array(
		'url' => 'banking-finance-courses-in-{location}',
		'keywords' => 'Banking Finance courses in {location}, Banking Finance Colleges in {location}, Banking Finance institutes in {location}, Banking Finance courses, accounting colleges, accounting institutes, accounting schools, full time accounting courses in {location}, part time accounting courses in {location}'
),
5 => array(
		'url' => 'medical-colleges-in-{location}',
		'keywords' => 'Medical courses in {location}, Medical colleges in {location}, Medical institutes in {location}, Medical courses, Medical colleges, Medical institutes'
),
6 => array(
		'url' => 'toursim-aviation-hospitality-colleges-in-{location}',
		'keywords' => 'Toursim Aviation Hospitality Colleges in {location}, Toursim Aviation Hospitality Courses in {location}, Toursim Aviation Hospitality institutes in {location}, Toursim Aviation Hospitality courses, Toursim Aviation Hospitality colleges, Toursim Aviation Hospitality institutes'
),
7 => array(
		'url' => 'media-film-mass-communication-colleges-in-{location}',
		'keywords' => 'Media FIlms & Mass Communication Colleges, Film Media Institutes, Media Colleges, Media Schools, media institutes, mass media course, college for mass media, Masscomm colleges, media institute, media education, media training, media school'
),
9 => array(
		'url' => 'teaching-arts-law-colleges-in-{location}',
		'keywords' => 'Teaching Arts Law Colleges in {location}, Law Courses,Teaching  Colleges, Arts Colleges, Law Colleges, Law Courses  in {location}, Law education, top 10 Law colleges, universities, institutes, career, career options, Education'
),
10 => array(
		'url' => 'information-technology-it-colleges-in-{location}',
		'keywords' => 'Information Technology IT Colleges in {location}, Information Technology IT Courses in {location}, Information Technology IT Colleges, Information Technology IT Courses,universities, institutes, career, career options, Education'
),
11 => array(
		'url' => 'retail-courses-in-{location}',
		'keywords' => 'Retail Courses in {location}, Retail Management Courses, Retail Management Courses in {location}, Institutes in {location}, Inventory Management  colleges, education'
),
12 => array(
		'url' => 'animation-gaming-courses-in-{location}',
		'keywords' => 'Animation Gaming courses in {location}, Animation Gaming schools in {location}, Animation Gaming colleges in {location}, Animation Gaming institutes in {location}, visual effects courses, gaming courses in {location}, comic courses in {location}, gaming courses, comic courses'
),
13 => array(
		'url' => 'designing-courses-in-{location}',
		'description' => 'View {resultCount} Colleges offering design courses in {location} with their admission criteria, fees, rankings, placements, alumni ratings, and more details.',
		'keywords' => 'Design courses in {location}, Design colleges in {location}, Design institutes in {location}, Design courses, Design colleges, design institutes,  full time design courses in {location}, part time design courses in {location}'
),
14 => array(
		'url' => 'entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'coaching classes in {location}, entrance exams coaching, coaching classes, entrance exams coaching institutes, entrance exams coaching classes, entrance exams in {location}, list of entrance exams coaching institutes, popular coaching classes in {location}'
)
);


$categoryURLDataStudyAbroad = array(

1 => array(
		'url' => 'study-abroad-in-{location}',
		'keywords' => ''
),
2 => array(
		'url' => 'science-engineering-colleges-in-{location}',
		'keywords' => 'Science Engineering Colleges in {location}, Science Engineering Courses in {location}, Science Engineering Courses, Engineering Colleges in {location}, Engineering courses, universities, institutes, career, career options, Education'
),
3 => array(
		'url' => 'management-courses-in-{location}',
		'keywords' => 'Management Courses in {location},  {location} Management Colleges, List of Management Colleges in {location}, Management in {location}, Types of Management Programs, mba courses, Business Schools in {location}, Institutes in {location}, Management courses, colleges, education'
),
4 => array(
		'url' => 'banking-finance-courses-in-{location}',
		'keywords' => 'Banking Finance courses in {location}, Banking Finance Colleges in {location}, Banking Finance institutes in {location}, Banking Finance courses, accounting colleges, accounting institutes, accounting schools, full time accounting courses in {location}, part time accounting courses in {location}'
),
5 => array(
		'url' => 'medical-colleges-in-{location}',
		'keywords' => 'Medical courses in {location}, Medical colleges in {location}, Medical institutes in {location}, Medical courses, Medical colleges, Medical institutes'
),
6 => array(
		'url' => 'toursim-aviation-hospitality-colleges-in-{location}',
		'keywords' => 'Toursim Aviation Hospitality Colleges in {location}, Toursim Aviation Hospitality Courses in {location}, Toursim Aviation Hospitality institutes in {location}, Toursim Aviation Hospitality courses, Toursim Aviation Hospitality colleges, Toursim Aviation Hospitality institutes'
),
7 => array(
		'url' => 'media-film-mass-communication-colleges-in-{location}',
		'keywords' => 'Media FIlms & Mass Communication Colleges, Film Media Institutes, Media Colleges, Media Schools, media institutes, mass media course, college for mass media, Masscomm colleges, media institute, media education, media training, media school'
),
9 => array(
		'url' => 'teaching-arts-law-colleges-in-{location}',
		'keywords' => 'Teaching Arts Law Colleges in {location}, Law Courses,Teaching  Colleges, Arts Colleges, Law Colleges, Law Courses  in {location}, Law education, top 10 Law colleges, universities, institutes, career, career options, Education'
),
10 => array(
		'url' => 'information-technology-it-colleges-in-{location}',
		'keywords' => 'Information Technology IT Colleges in {location}, Information Technology IT Courses in {location}, Information Technology IT Colleges, Information Technology IT Courses,universities, institutes, career, career options, Education'
),
11 => array(
		'url' => 'retail-courses-in-{location}',
		'keywords' => 'Retail Courses in {location}, Retail Management Courses, Retail Management Courses in {location}, Institutes in {location}, Inventory Management  colleges, education'
),
12 => array(
		'url' => 'animation-gaming-courses-in-{location}',
		'keywords' => 'Animation Gaming courses in {location}, Animation Gaming schools in {location}, Animation Gaming colleges in {location}, Animation Gaming institutes in {location}, visual effects courses, gaming courses in {location}, comic courses in {location}, gaming courses, comic courses'
),
13 => array(
		'url' => 'designing-courses-in-{location}',
		'keywords' => 'Design courses in {location}, Design colleges in {location}, Design institutes in {location}, Design courses, Design colleges, design institutes,  full time design courses in {location}, part time design courses in {location}'
),
14 => array(
		'url' => 'entrance-exam-coaching-classes-in-{location}',
		'keywords' => 'Coaching Classes in {location}, Entrance Exams, Coaching Classes, Entrance Exams in {location}, universities, institutes, career, career options, Education'
)
);

$subCategoryURLData = array(


15 => array(
		'url' => 'dj-courses-acting-schools-in-{location}',
		'keywords' => 'acting schools in {location}, modeling schools in {location}, anchoring schools in {location}, jockeying schools in {location},acting schools, acting courses, acting colleges, acting institutes, modeling schools, modeling courses, modeling colleges, modeling institutes, anchoring schools, anchoring courses, anchoring colleges, anchoring institutes, jockeying schools, jockeying courses, jockeying colleges, jockeying institutes'
),
16 => array(
		'url' => 'advertising-pr-courses-in-{location}',
		'keywords' => 'advertising courses in {location}, public relation courses in {location}, advertising colleges in {location}, public relations colleges in {location}, advertising courses, pr courses, advertising colleges, advertising institutes, pr schools, pr colleges, pr institutes'
),
17 => array(
		'url' => 'film-making-courses-in-{location}',
		'keywords' => 'film making courses in {location}, film making schools in {location}, film making colleges in {location}, film making institutes in {location}, film making courses, film making colleges, film making institutes, film making schools'
),
18 => array(
		'url' => 'mass-communication-courses-in-{location}',
		'keywords' => 'mass communication courses in {location}, mass communication colleges in {location}, mass communication schools in {location}, mass communication institutes in {location},mass communication schools, mass communication courses, mass communication colleges, mass communication institutes'
),
19 => array(
		'url' => 'event-management-courses-in-{location}',
		'keywords' => 'event management courses in {location}, event management schools in {location}, event management colleges in {location}, event management institutes in {location},event management schools, event management courses, event management colleges, event management institutes'
),
20 => array(
		'url' => 'journalism-courses-in-{location}',
		'keywords' => 'journalism courses in {location}, journalism colleges in {location}, journalism schools in {location}, journalism institutes in {location},journalism schools, journalism courses, journalism colleges, journalism institutes'
),
21 => array(
		'url' => 'mass-media-courses-in-{location}',
		'keywords' => 'mass media courses in {location}, mass media colleges in {location}, mass media institutes in {location}, mass media schools in {location},mass media schools, mass media courses, mass media colleges, mass media institutes'
),
22 => array(
		'url' => 'photography-courses-in-{location}',
		'keywords' => 'photography courses in {location}, photography colleges in {location}, photography schools in {location}, photography institutes in {location},photography schools, photography courses, photography colleges, photography institutes'
),
23 => array(
		'url' => 'mba-colleges-in-{Location}',
		'keywords' => 'MBA Colleges in {Location}, {Location}MBA Colleges, List of MBA Colleges in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
24 => array(
		'url' => 'mba-distance-learning-in-{Location}',
		'title' => 'Distance/Correspondence MBA Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Distance/Correspondence MBA colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'MBA Distance Learning in {Location}, MBA Distance Learning courses, List of Distance MBA Colleges in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
25 => array(
		'url' => 'executive-mba-in-{Location}',
		'title' => 'Executive MBA Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Executive MBA colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Executive MBA in {Location}, Executive MBA courses, List of Executive MBA in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
26 => array(
		'url' => 'part-time-mba-in-{Location}',
		'title' => 'Part Time MBA Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Part Time MBA colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Part Time MBA in {Location}, Part Time MBA courses, List of Part Time MBA in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
27 => array(
		'url' => 'online-mba-in-{Location}',
		'title' => 'Online MBA Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Online MBA colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Online MBA in {Location}, Online MBA courses, List of Online MBA in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
28 => array(
		'url' => 'bba-bms-bbm-bbs-colleges-in-{Location}',
		'title' => 'BBA Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} BBA colleges in {Location}, check their courses, fees, admissions, eligibility, and more details.',
		'keywords' => 'BBM Colleges in {Location}, BBA BMS BBM BBS Colleges in {Location}, {Location}BBA BMS BBM BBS Colleges, List of BBA BMS BBM BBS Colleges in {Location}, BBA BMS BBM BBS in {Location}, Types of BBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
29 => array(
		'url' => 'integrated-btech-mba-in-{Location}',
		'title' => 'Integrated MBA (B.Tech + MBA) Dual Degree Courses and Colleges in {Location} - Shiksha.com',
		'description' => 'List of colleges, institutes, and universities offering integrated MBA (B.Tech + MBA) dual degree programs in {Location}',
		'keywords' => ''
),
30 => array(
		'url' => 'integrated-mba-in-{Location}',
		'title' => 'Integrated MBA (BBA + MBA) Dual Degree Courses and Colleges in {Location} - Shiksha.com',
		'description' => 'List of colleges, institutes, and universities offering integrated MBA (BBA + MBA) dual degree programs in {Location}',
		'keywords' => ''
),
31 => array(
		'url' => 'certification-courses-in-{Location}',
		'title' => 'Certification Courses in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Certification courses in {location}, check their courses, fees, admissions, eligibility and more details.',
		'keywords' => 'Certification Courses in {Location}, Certification Courses, Certification,universities, institutes, career, career options, Education'
),
32 => array(
		'url' => 'arts-colleges-in-{location}',
		'keywords' => 'Arts Colleges in {location}, Humanities Courses, Arts Colleges, Arts courses, Institutes in {Location}, arts education, top 10 arts colleges, top arts colleges, universities, institutes, career, career options, Education'
),
33 => array(
		'url' => 'law-colleges-in-{location}',
		'keywords' => 'Law Colleges in {location}, Law Courses, Law Colleges, Law Courses  in {Location}, Law education, top 10 Law colleges, universities, institutes, career, career options, Education'
),
34 => array(
		'url' => 'language-learning-courses-in-{location}',
		'keywords' => 'Language Learning in {Location}, Language Courses, Language Learning Courses, Language Courses in {Location}, universities, institutes, career, career options, Education'
),
35 => array(
		'url' => 'bpo-call-center-training-institutes-in-{location}',
		'keywords' => 'call center training, bpo training, bpo training institute, bpo training material, bpo courses, bpo training courses, training for call center, training in bpo, universities, institutes, career, career options, Education'
),
36 => array(
		'url' => 'social-sciences-colleges-in-{location}',
		'keywords' => 'Social Sciences Colleges in {Location}, Social Science Courses, Social Sciences Colleges, Social Science Courses in {Location}, universities, institutes, career, career options, Education'
),
37 => array(
		'url' => 'government-sector-in-{location}',
		'keywords' => 'Government Sector Courses in {Location}, Government Sector Institutes in {Location},Government Sector Courses, Government Sector Institutes, universities, institutes, career, career options, Education'
),
38 => array(
		'url' => 'teacher-training-courses-in-{location}',
		'keywords' => 'Teacher Training Courses in {Location}, Teacher Training Institutes in {Location},Teacher Training Courses, Teacher Training Institutes, universities, institutes, career, career options, Education'
),
39 => array(
		'url' => 'creative-commercial-arts-colleges-in-{location}',
		'keywords' => 'Creative Commercial Arts Colleges in {Location}, Creative Arts Colleges in {Location}, commercial arts,  Creative Arts,  universities, institutes, career, career options, Education'
),
40 => array(
		'url' => 'arts-courses-distance-learning-in-{location}',
		'keywords' => 'Arts Distance Colleges in {location}, Humanities Distance Courses, Arts Distance Colleges, Arts Distance courses, Institutes in {Location}, arts education, top 10 arts colleges, top arts colleges, universities, institutes, career, career options, Education'
),
41 => array(
		'url' => 'front-office-management-courses-in-{location}',
		'keywords' => 'Front Office Management Courses in {Location}, {Location} Front Office Management Courses, Front Office Management training in {Location}, Front Office Management Courses, retail management courses in {Location}, Institutes in {Location}, Inventory Management  colleges, education'
),
42 => array(
		'url' => 'inventory-management-courses-in-{location}',
		'keywords' => 'Inventory Management Courses in {Location}, {Location} Inventory Management Courses, Inventory Management training in {Location}, Inventory Management Courses, retail management courses in {Location}, Institutes in {Location}, MBA courses, Inventory Management  colleges, education'
),
43 => array(
		'url' => 'shop-floor-management-in-{location}',
		'keywords' => 'Shop Floor Management Courses in {Location}, {Location} Shop Floor Management Courses, Shop Floor Management training in {Location}, Shop Floor Management Courses, retail management courses in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
44 => array(
		'url' => 'store-management-courses-in-{location}',
		'keywords' => 'Store Management Courses in {Location}, {Location} Store Management Courses, Store Management training in {Location}, Store Management Courses, retail management courses in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
45 => array(
		'url' => 'supply-chain-management-courses-in-{location}',
		'keywords' => 'Supply Chain Management Courses in {Location}, scm courses, {Location} Supply Chain Management Courses, Supply Chain Management training in {Location}, Supply Chain Management Courses, retail management courses in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
46 => array(
		'url' => 'merchandising-management-in-{location}',
		'keywords' => 'Merchandising Management Courses in {Location}, {Location} Merchandising Management Courses, Merchandising Management training in {Location}, Merchandising Management Courses, retail management courses in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
47 => array(
		'url' => 'mba-entrance-exams-{location}',
		'keywords' => 'MBA exam coaching classes in {location}, MBA entrance exams coaching, coaching classes, MBA entrance exams coaching institutes, MBA entrance exams coaching classes, MBA entrance exams in {location}, list of MBA entrance exams coaching institutes, popular MBA coaching classes'
),
48 => array(
		'url' => 'engineering-entrance-exams-{location}',
		'keywords' => 'Engineering exam coaching classes in {location}, Engineering entrance exams coaching, coaching classes, Engineering entrance exams coaching institutes, Engineering entrance exams coaching classes, Engineering entrance exams in {location}, list of Engineering entrance exams coaching institutes, popular Engineering coaching classes'
),
49 => array(
		'url' => 'medical-entrance-exams-{location}',
		'keywords' => 'Medical exam coaching classes in {location}, Medical entrance exams coaching, coaching classes, Medical entrance exams coaching institutes, Medical entrance exams coaching classes, Medical entrance exams in {location}, list of Medical entrance exams coaching institutes, popular Medical coaching classes'
),
50 => array(
		'url' => 'law-entrance-exams-{location}',
		'keywords' => 'Law exam coaching classes in {location}, Law entrance exams coaching, coaching classes, Law entrance exams coaching institutes, Law entrance exams coaching classes, Law entrance exams in {location}, list of Law entrance exams coaching institutes, popular Law coaching classes'
),
51 => array(
		'url' => 'study-abroad-classes-in-{location}',
        'keywords' => 'Study Abroad exam coaching classes in {location}, Study Abroad entrance exams coaching, coaching classes, Study Abroad entrance exams coaching institutes, Study Abroad entrance exams coaching classes, Study Abroad entrance exams in {location}, list of Study Abroad entrance exams coaching institutes, popular Study Abroad coaching classes'
),
52 => array(
		'url' => 'bcom-coaching-in-{location}',
        'keywords' => 'B.Com exam coaching classes in {location}, B.Com entrance exams coaching, coaching classes, B.Com entrance exams coaching institutes, B.Com entrance exams coaching classes, B.Com entrance exams in {location}, list of B.Com entrance exams coaching institutes, popular B.Com coaching classes'
),
53 => array(
		'url' => 'bsc-coaching-in-{location}',
		'title' => 'B.Sc Entrance Exams Coaching Institutes and Classes in {location} - Shiksha.com',
		'description' => 'Get the list of B.Sc Entrance Exams Coaching Institutes and Classes in {location}. Find contact details, reviews, ranking, fees, duration, and other useful information about B.Sc Exams Coaching Classes.',
		'keywords' => 'B.Sc exam coaching classes in {location}, B.Sc entrance exams coaching, coaching classes, B.Sc entrance exams coaching institutes, B.Sc entrance exams coaching classes, B.Sc entrance exams in {location}, list of B.Sc entrance exams coaching institutes, popular B.Sc coaching classes'
),
54 => array(
		'url' => 'government-exams-in-{location}',
		'keywords' => 'Civil Services & Government exam coaching classes in {location}, Civil Services & Government entrance exams coaching, coaching classes, Civil Services & Government entrance exams coaching institutes, Civil Services & Government entrance exams coaching classes, Civil Services & Government entrance exams in {location}, list of Civil Services & Government entrance exams coaching institutes, popular Civil Services & Government coaching classes'
),
55 => array(
		'url' => 'diploma-in-engineering-in-{location}',
		'title' => 'Diploma Colleges in {location} | Shiksha.com',
		'description' => 'View {resultCount} Diploma colleges in {location} with their courses, fees, admissions, eligibility, placements, and more details.',
		'keywords' => 'Diploma in Engineering in {Location}, Diploma Engineering, Engineering Diploma, Diploma in Engineering, Diploma courses, Engineering diploma colleges, universities, institutes, career, career options, Education'
),
56 => array(
		'url' => 'be-btech-colleges-in-{location}',
		'keywords' => 'BE Colleges in {location}, Btech Colleges in {Location}, Btech courses, BE courses, Engineering colleges in {Location}, Engineering courses, universities, institutes, career, career options, Education'
),
57 => array(
		'url' => 'distance-diploma-in-{location}',
		'keywords' => 'Distance Diploma in Engineering in {Location}, Distance Diploma Engineering, Engineering Distance Diploma, Distance Diploma in Engineering, Distance Diploma courses, Engineering distance diploma colleges, universities, institutes, career, career options, Education'
),
58 => array(
		'url' => 'distance-learning-engineering-in-{location}',
		'keywords' => 'Distance Learning Engineering in {location}, Distance BTech Colleges in {Location}, Distance Engineering Colleges, BTech Distance Learning Engineering in {location}, Btech Colleges in {Location}, Btech courses, Engineering colleges in {Location}, Engineering courses, universities, institutes, career, career options, Education'
),
59 => array(
		'url' => 'me-mtech-in-{location}',
		'title' => 'M.Tech Colleges in {location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech colleges in {location} with their courses, fees, admissions, eligibility, placements, and more details.',
		'keywords' => 'ME Colleges in {location}, Mtech Colleges in {Location}, Mtech courses, ME courses, Engineering colleges in {Location}, Engineering courses, universities, institutes, career, career options, Education'
),
60 => array(
		'url' => 'architecture-colleges-in-{location}',
		'keywords' => 'Architecture Colleges in {location}, Architecture Courses, Architecture Colleges, Architecture Courses in {Location}, Architecture engineering Colleges, universities, institutes, career, career options, Education'
),
61 => array(
		'url' => 'aircraft-maintenance-engineering-colleges-in-{location}',
		'keywords' => 'Aircraft Maintenance Engineering, Aircraft Maintenance Engineering Colleges in {location}, Engineering Colleges in {location}, Engineering Colleges, universities, institutes, career, career options, Education'
),
62 => array(
		'url' => 'be-marine-engineering-colleges-in-{location}',
		'keywords' => 'BE Marine Engineering Colleges in {location}, Marine Engineering Colleges, Engineering Colleges in {Location}, Engineering Colleges, universities, institutes, career, career options, Education'
),
63 => array(
		'url' => 'me-marine-engineering-colleges-in-{location}',
		'keywords' => 'ME Marine Engineering Colleges in {location}, Marine Engineering Colleges, Engineering Colleges in {Location}, Engineering Colleges, universities, institutes, career, career options, Education'
),
64 => array(
		'url' => 'bsc-colleges-in-{location}',
		'keywords' => 'BSc Colleges in {location}, BSc Courses in {location}, BSc Colleges, BSc Courses, universities, institutes, career, career options, Education'
),
65 => array(
		'url' => 'msc-colleges-in-{location}',
		'keywords' => 'MSc Colleges in {location}, MSc Courses in {location}, MSc Colleges, MSc Courses, universities, institutes, career, career options, Education'
),
66 => array(
		'url' => 'bsc-distance-learning-in-{location}',
		'keywords' => 'BSc Distance Learning in {Location}, BSc Distance Learning Courses in {Location}, BSc Distance Learning, BSc Distance Learning Courses, universities, institutes, career, career options, Education'
),
67 => array(
		'url' => 'msc-distance-learning-in-{location}',
		'keywords' => 'MSc Distance Learning in {Location}, MSc Distance Learning Courses in {Location}, MSc Distance Learning, MSc Distance Learning Courses,  universities, institutes, career, career options, Education'
),
68 => array(
		'url' => 'phd-courses-in-{location}',
		'keywords' => 'Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
69 => array(
		'url' => 'fashion-textile-designing-courses-in-{location}',
		'title' => 'Fashion and Textile Designing Colleges in {location} | Shiksha.com',
		'description' => 'View fashion and textile designing colleges in {location} with their admission criteria, fees, rankings, placements, alumni ratings, and more details',
		'keywords' => 'fashion and textile design courses in {location}, fashion and textile design colleges in {location}, fashion and textile design institutes in {location}, fashion and textile design courses, fashion and textile design colleges, fashion and textile design institutes,  full time fashion and textile design courses in {location}, part time fashion and textile design courses in {location}'
),
70 => array(
		'url' => 'interior-designing-courses-in-{location}',
		'title' => 'Interior Design Courses in {location} | Shiksha.com',
		'description' => 'View Interior Design Courses in {location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'interior designing courses in {location}, interior designing colleges in {location}, interior designing institutes in {location}, interior designing courses, interior designing colleges, interior designing institutes,  full time interior designing courses in {location}, part time interior designing courses in {location}'
),
71 => array(
		'url' => 'jewellery-design-courses-in-{location}',
		'title' => 'Jewellery & Accessory Design Courses in {location} | Shiksha.com',
		'description' => 'View Jewellery & Accessory Design Courses in {location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'jewellery designing courses in {location}, jewellery designing colleges in {location}, jewellery designing institutes in {location}, jewellery designing courses, jewellery designing colleges, jewellery designing institutes,  full time jewellery designing courses in {location}, part time jewellery designing courses in {location}'
),
72 => array(
		'url' => 'industrial-automotive-product-design-courses-in-{location}',
		'title' => 'Industrial, Automotive, Product Design Courses in {location} | Shiksha.com',
		'description' => 'View Industrial, Automotive, Product Design Courses in {location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'product design courses in {location}, product design colleges in {location}, product design institutes in {location}, product design courses, product design colleges, product design institutes,  full time product design courses in {location}, part time product design courses in {location}'
),
73 => array(
		'url' => 'interaction-design-courses-in-{location}',
		'title' => 'Interaction Design Courses in {location} | Shiksha.com',
		'description' => 'View Interaction Design Courses in {location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'interaction design courses in {location}, interaction design colleges in {location}, interaction design institutes in {location}, interaction design courses, interaction design colleges, interaction design institutes,  full time interaction design courses in {location}, part time interaction design courses in {location}'
),
74 => array(
		'url' => 'accounting-courses-in-{location}',
		'keywords' => 'accounting courses in {location}, accounting colleges in {location}, accounting institutes in {location}, accounting courses, accounting colleges, accounting institutes, accounting schools, full time accounting courses in {location}, part time accounting courses in {location}'
),
75 => array(
		'url' => 'banking-courses-in-{location}',
		'keywords' => 'banking courses in {location}, banking colleges in {location}, banking institutes in {location}, banking courses, banking colleges, banking institutes,  full time banking courses in {location}, part time banking courses in {location}'
),
76 => array(
		'url' => 'finance-courses-in-{location}',
		'keywords' => 'finance courses in {location}, finance colleges in {location}, finance institutes in {location}, finance courses, finance colleges, finance institutes,  full time finance courses in {location}, part time finance courses in {location}'
),
77 => array(
		'url' => 'ca-courses-in-{location}',
		'keywords' => 'CA courses in {location}, CA colleges in {location}, CA institutes in {location}, CA courses, CA colleges, CA institutes,  full time CA courses in {location}, part time CA courses in {location}'
),
78 => array(
		'url' => 'insurance-courses-in-{location}',
		'keywords' => 'insurance courses in {location}, insurance colleges in {location}, insurance institutes in {location}, insurance courses, insurance colleges, insurance institutes,  full time insurance courses in {location}, part time insurance courses in {location}'
),
79 => array(
		'url' => 'securties-trading-courses-in-{location}',
		'keywords' => 'security and trading courses in {location}, security and trading colleges in {location}, security and trading institutes in {location}, security and trading courses, security and trading colleges, security and trading institutes,  full time security and trading courses in {location}, part time security and trading courses in {location}'
),
80 => array(
		'url' => 'cfa-cpa-cima-courses-in-{location}',
		'keywords' => 'CIMA courses in {location}, CFA courses in {location}, CPA courses in {location}, CIMA institutes in {location}, CPA institutes in {location}, CFA institutes in {location}, CIMA courses, CIMA institutes,  CPA institutes, CFA institutes, full time CIMA courses in {location}, part time CIMA courses in {location}'
),
81 => array(
		'url' => 'icwai-courses-in-{location}',
		'keywords' => 'ICWAI courses in {location}, ICWAI colleges in {location}, ICWAI institutes in {location}, ICWAI courses, ICWAI colleges, ICWAI institutes,  full time ICWAI courses in {location}, part time ICWAI courses in {location}'
),
82 => array(
		'url' => 'cs-company-secretary-courses-in-{location}',
		'keywords' => 'company secretary courses in {location}, cs courses in {location}, company secretary colleges in {location}, company secretary institutes in {location}, company secretary courses, company secretary colleges, company secretary institutes,  full time company secretary courses in {location}, part time company secretary courses in {location}'
),
83 => array(
		'url' => 'commerce-courses-in-{location}',
		'keywords' => 'commerce courses in {location}, commerce colleges in {location}, commerce institutes in {location}, commerce courses, commerce colleges, commerce institutes,  full time commerce courses in {location}, part time commerce courses in {location}'
),
84 => array(
		'url' => 'hotel-management-colleges-in-{location}',
		'keywords' => 'hotel management courses in {location}, hotel management colleges in {location}, hotel management institutes in {location}, hotel management courses, hotel management colleges, hotel management institutes, hotel management schools'
),
85 => array(
		'url' => 'event-management-courses-in-{location}',
		'keywords' => 'event management courses in {location}, event management colleges in {location}, event management institutes in {location}, event management courses, event management colleges, event management institutes, event management schools'
),
85 => array(
		'url' => 'event-management-courses-in-{location}',
		'keywords' => 'event management courses in {location}, event management schools in {location}, event management colleges in {location}, event management institutes in {location},event management schools, event management courses, event management colleges, event management institutes'
),
86 => array(
		'url' => 'travel-and-tourism-courses-in-{location}',
		'keywords' => 'travel and tourism courses in {location}, travel and tourism colleges in {location}, travel and tourism institutes in {location}, travel and tourism courses, travel and tourism colleges, travel and tourism institutes, travel and tourism schools'
),
87 => array(
		'url' => 'aviation-courses-in-{location}',
		'keywords' => 'aviation courses in {location}, aviation colleges in {location}, aviation institutes in {location}, aviation courses, aviation colleges, aviation institutes, aviation schools'
),
88 => array(
		'url' => 'aircraft-maintenance-engineering-colleges-in-{location}',
		'keywords' => 'Aircraft Maintenance Engineering, Aircraft Maintenance Engineering Colleges in {Location}, Engineering Colleges in {Location}, Engineering Colleges, universities, institutes, career, career options, Education'
),
88 => array(
		'url' => 'aircraft-maintenance-engineering-in-{location}',
		'keywords' => 'aircraft engineering courses in {location}, aircraft engineering colleges in {location}, aircraft engineering institutes in {location}, aircraft engineering courses, aircraft engineering colleges, aircraft engineering institutes, aircraft engineering schools'
),
89 => array(
		'url' => 'animation-courses-in-{location}',
		'keywords' => 'animation courses in {location}, animation schools in {location}, animation colleges in {location}, animation institutes in {location}, visual effects courses, gaming courses in {location}, comic courses in {location}, gaming courses, comic courses'
),
90 => array(
		'url' => 'multimedia-courses-in-{location}',
		'keywords' => 'multimedia courses in {location}, multimedia schools in {location}, multimedia colleges in {location}, multimedia institutes in {location}, multimedia courses, multimedia institutes, multimedia colleges'
),
91 => array(
		'url' => 'vfx-courses-in-{location}',
		'keywords' => 'vfx courses in {location}, vfx schools in {location}, vfx colleges in {location}, vfx institutes in {location}, vfx courses, vfx institutes, vfx colleges'
),
92 => array(
		'url' => 'graphic-designing-courses-in-{location}',
		'keywords' => 'graphic designing courses in {location}, graphic designing schools in {location}, graphic designing colleges in {location}, graphic designing institutes in {location}, graphic designing courses, graphic designing institutes, graphic designing colleges'
),
93 => array(
		'url' => 'web-designing-courses-in-{location}',
		'keywords' => 'web designing courses in {location}, web designing schools in {location}, web designing colleges in {location}, web designing institutes in {location}, web designing courses, web designing institutes, web designing colleges'
),
94 => array(
		'url' => 'game-development-courses-in-{location}',
		'keywords' => 'mobile application development courses in {location}, mobile application development schools in {location}, mobile application development colleges in {location}, mobile application development institutes in {location}, mobile application development courses, mobile application development institutes, mobile application development colleges'
),
95 => array(
		'url' => 'mobile-application-development-courses-in-{location}',
		'keywords' => 'mobile application development courses in {location}, mobile application development schools in {location}, mobile application development colleges in {location}, mobile application development institutes in {location}, mobile application development courses, mobile application development institutes, mobile application development colleges'
),
96 => array(
		'url' => 'comic-design-courses-in-{location}',
		'keywords' => 'comic design courses in {location}, comic design schools in {location}, comic design colleges in {location}, comic design institutes in {location}, comic design courses, comic design institutes, comic design colleges'
),
97 => array(
		'url' => 'mca-distance-education-in-{location}',
		'keywords' => 'MCA Distance Education in {Location}, MCA Distance Learning, MCA Colleges, MCA Courses,universities, institutes, career, career options, Education'
),
98 => array(
		'url' => 'mca-colleges-in-{location}',
		'keywords' => 'MCA Colleges in {Location}, MCA Courses in {Location}, MCA Colleges, MCA Courses,universities, institutes, career, career options, Education'
),
99 => array(
		'url' => 'bca-distance-education-in-{location}',
		'keywords' => 'BCA Distance Education in {Location}, BCA Distance Learning, BCA Colleges, BCA Courses,universities, institutes, career, career options, Education'
),
100 => array(
		'url' => 'bca-colleges-in-{location}',
		'keywords' => 'BCA Colleges in {Location}, BCA Courses in {Location}, BCA Colleges, BCA Courses,universities, institutes, career, career options, Education'
),
101 => array(
		'url' => 'cisco-certification-courses-in-{location}',
		'keywords' => 'Cisco Certification Courses in {Location}, Cisco Courses, Cisco Certification,universities, institutes, career, career options, Education'
),
102 => array(
		'url' => 'doeacc-{location}-courses',
		'keywords' => 'DOEACC Courses in {Location}, DOEACC O A B C Level Courses, universities, institutes, career, career options, education'
),
103 => array(
		'url' => 'microsoft-certification-courses-in-{location}',
		'keywords' => 'Microsoft Certification Courses in {Location}, Microsoft Courses, Microsoft Certification, universities, institutes, career, career options, Education'
),
104 => array(
		'url' => 'red-hat-certification-courses-in-{location}',
		'keywords' => 'Red Hat Certification Courses in {Location}, Red Hat Courses, Red Hat Certification, universities, institutes, career, career options, Education'
),
105 => array(
		'url' => 'computer-programming-language-courses-in-{location}',
		'keywords' => 'Computer Programming Language Courses in {Location}, Programming Courses, universities, institutes, career, career options, Education'
),
106 => array(
		'url' => 'oracle-dba-training-in-{location}',
		'keywords' => 'Oracle DBA Training in {Location}, Oracle Courses, universities, institutes, career, career options, Education'
),
107 => array(
		'url' => 'cae-cad-cam-courses-in-{location}',
		'keywords' => 'Cae Courses in {Location}, Cam Courses in {Location}, Cad Courses in {Location}, universities, institutes, career, career options, Education'
),
108 => array(
		'url' => 'vlsi-embedded-systems-training-in-{location}',
		'keywords' => 'VLSI Embedded Systems Training in {Location}, VLSI Embedded Systems Training, universities, institutes, career, career options, Education'
),
109 => array(
		'url' => 'ethical-hacking-courses-in-{location}',
		'keywords' => 'Ethical Hacking Courses in {Location}, Ethical Hacking Courses, Ethical Hacking Training, universities, institutes, career, career options, Education'
),
110 => array(
		'url' => 'san-certification-courses-in-{location}',
		'keywords' => 'SAN Certification Courses in {Location}, SAN Certification Courses, SAN Certification, universities, institutes, career, career options, Education'
),
111 => array(
		'url' => 'linux-training-in-{location}',
		'keywords' => 'Linux Training in {Location}, Linux Training, Linux Training Courses, universities, institutes, career, career options, Education'
),
112 => array(
		'url' => 'hardware-and-networking-courses-in-{location}',
		'keywords' => 'Hardware and Networking Courses in {Location}, Hardware and Networking Courses, Hardware and Networking institute, universities, institutes, career, career options, Education'
),
113 => array(
		'url' => 'cloud-computing-training-in-{location}',
		'keywords' => 'Cloud Computing Training in {Location}, Cloud Computing Training Courses, Cloud Computing Training institute, universities, institutes, career, career options, Education'
),
114 => array(
		'url' => 'ibm-mainframe-training-in-{location}',
		'keywords' => 'IBM Mainframe Training in {Location}, IBM Mainframe Training Courses, IBM Mainframe Training institute, universities, institutes, career, career options, Education'
),
115 => array(
		'url' => 'java-certification-courses-in-{location}',
		'keywords' => 'Java Certification Courses in {Location}, Java Certification Courses, Java Certification Training, universities, institutes, career, career options, Education'
),
116 => array(
		'url' => 'sap-certification-courses-in-{location}',
		'keywords' => 'SAP Certification Courses in {location}, SAP Certification Courses, SAP Certification training, universities, institutes, career, career options, Education'
),
117 => array(
		'url' => 'sas-certification-courses-in-{location}',
		'keywords' => 'Sas Certification Courses in {Location}, Sas Certification Courses, Sas Certification Training, universities, institutes, career, career options, Education'
),
118 => array(
		'url' => 'six-sigma-certification-in-{location}',
		'keywords' => 'Six Sigma Certification in {Location}, Six Sigma Certification Courses, Six Sigma Certification training, universities, institutes, career, career options, Education'
),
119 => array(
		'url' => 'software-testing-courses-in-{location}',
		'keywords' => 'Six Sigma Certification in {Location}, Six Sigma Certification Courses, Six Sigma Certification training, universities, institutes, career, career options, Education'
),
120 => array(
		'url' => 'tally-courses-in-{location}',
		'keywords' => 'Tally Courses in {Location}, Tally Courses, Tally Training, universities, institutes, career, career options, Education'
),
121 => array(
		'url' => 'telecom-management-courses-in-{location}',
		'keywords' => 'Search Telecom Management Courses in {location}, Telecom Management Courses, universities, institutes, career, career options, Education'
),
122 => array(
		'url' => 'game-development-courses-in-{location}',
		'keywords' => 'game design courses in {location}, game design colleges in {location}, game design institutes in {location}, game design courses, game development courses, game design colleges'
),
122 => array(
		'url' => 'game-development-courses-in-{location}',
		'keywords' => 'Game Design and Development Courses in {location}, Game Design Courses, Game animation courses, universities, institutes, career, career options, Education'
),
123 => array(
		'url' => 'mobile-application-development-courses-in-{location}',
		'keywords' => 'Mobile Application Development Courses in {Location}, Mobile Application Development Courses, universities, institutes, career, career options, Education'
),
124 => array(
		'url' => 'itil-certification-in-{location}',
		'keywords' => 'ITIL Certification in {Location}, ITIL Certification Courses, ITIL Certification Training, universities, institutes, career, career options, Education'
),
125 => array(
		'url' => 'real-time-projects-training-in-{location}',
		'keywords' => 'Real Time Projects Training in {Location}, Real Time Projects Training Courses, Real Time Projects Training, universities, institutes, career, career options, Education'
),
126 => array(
		'url' => 'project-management-courses-in-{location}',
		'keywords' => 'Project Management Courses in {Location}, Project Management Courses, Project Management training, universities, institutes, career, career options, Education'
),
127 => array(
		'url' => 'it-cyber-security-courses-in-{location}',
		'keywords' => 'IT Cyber Security Courses in {Location}, IT Cyber Security Courses, universities, institutes, career, career options, Education'
),
128 => array(
		'url' => 'beautician-cosmetology-courses-in-{location}',
		'keywords' => 'beautician courses in {location}, cosmetology courses in {location}, hair dressing courses in {location}, makeup courses in {location}, beautician courses, cosmetology courses, hair dressing courses, makeup courses'
),
129 => array(
		'url' => 'clinical-research-courses-in-{location}',
		'keywords' => 'clinical research courses in {location}, clinical research institutes in {location}, clinical research colleges in {location}, clinical research courses'
),
130 => array(
		'url' => 'clinical-trials-courses-in-{location}',
		'keywords' => 'clinical trials courses in {location}, clinical trials institutes in {location}, clinical trials colleges in {location}, clinical trials courses'
),
131 => array(
		'url' => 'dental-colleges-in-{location}',
		'keywords' => 'dental courses in {location}, dental colleges in {location}, dental institutes in {location}, dental courses, dental colleges, dental institutes'
),
132 => array(
		'url' => 'hospital-management-courses-in-{location}',
		'keywords' => 'hospital management courses in {location}, hospital management institutes in {location}, hospital management colleges in {location}, hospital management courses'
),
133 => array(
		'url' => 'mbbs-medical-colleges-in-{location}',
		'keywords' => 'mbbs courses in {location}, medical courses in {location}, mbbs colleges in {location}, mbbs institutes in {location}, mbbs courses, mbbs colleges, mbbs institutes'
),
134 => array(
		'url' => 'md-courses-in-{location}',
		'keywords' => 'md courses in {location}, md institutes in {location}, md colleges in {location}, md courses'
),
135 => array(
		'url' => 'microbiology-colleges-in-{location}',
		'keywords' => 'microbiology courses in {location}, microbiology colleges in {location}, microbiology institutes in {location}, microbiology courses, microbiology colleges, microbiology institutes'
),
136 => array(
		'url' => 'biotechnology-colleges-in-{location}',
		'keywords' => 'biotechnology courses in {location}, biotechnology colleges in {location}, biotechnology institutes in {location}, biotechnology courses, biotechnology colleges, biotechnology institutes'
),
137 => array(
		'url' => 'nursing-colleges-in-{location}',
		'keywords' => 'nursing courses in {location}, nursing colleges in {location}, nursing institutes in {location}, nursing courses, nursing colleges, nursing institutes'
),
138 => array(
		'url' => 'pharmacovigilance-courses-in-{location}',
		'keywords' => 'pharmacovigilance courses in {location}, pharmacovigilance institutes in {location}, pharmacovigilance colleges in {location}, pharmacovigilance courses'
),
139 => array(
		'url' => 'pharmacy-colleges-in-{location}',
		'keywords' => 'pharmacy courses in {location}, pharmacy colleges in {location}, pharmacy institutes in {location}, pharmacy courses, pharmacy colleges, pharmacy institutes'
),
140 => array(
		'url' => 'paramedical-sciences-courses-in-{location}',
		'keywords' => 'paramedical sciences courses in {location}, paramedical sciences institutes in {location}, paramedical sciences colleges in {location}, paramedical sciences courses'
),
141 => array(
		'url' => 'medical-transcription-courses-in-{location}',
		'keywords' => 'medical transcription courses in {location}, medical transcription institutes in {location}, medical transcription schools in {location}, medical transcription colleges in {location}, medical transcription courses'
),
142 => array(
		'url' => 'physiotherapy-courses-in-{location}',
		'keywords' => 'physiotherapy courses in {location}, physiotherapy institutes in {location}, physiotherapy schools in {location}, physiotherapy colleges in {location}, physiotherapy courses'
),
143 => array(
		'url' => 'alternate-medicine-courses-in-{location}',
		'keywords' => 'alternative medicine courses in {location}, alternative medicine institutes in {location}, alternative medicine schools in {location}, alternative medicine colleges in {location}, alternative medicine courses'
),
144 => array(
		'url' => 'animation-courses-in-{location}',
		'keywords' => 'animation courses in {location}, animation colleges in {location}, animation institutes in {location}, animation schools in {location}, animation courses, animation colleges, animation institutes'
),
145 => array(
		'url' => 'game-development-courses-in-{location}',
		'keywords' => 'game development courses in {location}, game development colleges in {location}, game development institutes in {location}, game designing courses, game development schools in {location}, game development courses, game development colleges, game development institutes'
),
146 => array(
		'url' => 'graphic-designing-courses-in-{location}',
		'keywords' => 'graphic designing courses in {location}, graphic designing colleges in {location}, graphic designing institutes in {location}, graphic designing courses, graphic designing schools in {location}, graphic designing colleges, graphic designing institutes'
),
147 => array(
		'url' => 'mobile-application-development-courses-in-{location}',
		'keywords' => 'mobile application development courses in {location}, mobile application development colleges in {location}, mobile application development institutes in {location}, mobile application development courses, mobile application development schools in {location}, mobile application development colleges, mobile application development institutes'
),
148 => array(
		'url' => 'multimedia-courses-in-{location}',
		'keywords' => 'multimedia courses in {location}, multimedia colleges in {location}, multimedia institutes in {location}, multimedia schools in {location}, multimedia courses, multimedia colleges, multimedia institutes'
),
149 => array(
		'url' => 'web-designing-courses-in-{location}',
		'keywords' => 'web designing courses in {location}, web designing colleges in {location}, web designing institutes in {location}, web designing schools in {location}, web designing courses, web designing colleges, web designing institutes'
),
150 => array(
		'url' => 'comic-design-courses-in-{location}',
		'keywords' => 'comic design courses in {location}, comic design colleges in {location}, comic design institutes in {location}, comic design schools in {location}, comic design courses, comic design colleges, comic design institutes'
),
151 => array(
		'url' => 'acting-schools-in-{location}',
		'keywords' => 'acting courses in {location}, acting colleges in {location}, acting institutes in {location}, acting schools in {location}, acting courses, acting colleges, acting institutes, acting schools'
),
152 => array(
		'url' => 'advertising-pr-courses-in-{location}',
		'keywords' => 'advertising courses in {location}, advertising colleges in {location}, advertising institutes in {location}, public relation courses in {location}, public relations schools in {location}, advertising courses, pr courses, advertising colleges, advertising institutes, pr schools, pr colleges, pr institutes'
),
153 => array(
		'url' => 'event-management-courses-in-{location}',
		'keywords' => 'event management courses in {location}, event management schools in {location}, event management colleges in {location}, event management institutes in {location},event management schools, event management courses, event management colleges, event management institutes'
),
154 => array(
		'url' => 'film-making-courses-in-{location}',
		'keywords' => 'film making courses in {location}, film making schools in {location}, film making colleges in {location}, film making institutes in {location}, film making courses, film making colleges, film making institutes, film making schools'
),
155 => array(
		'url' => 'journalism-courses-in-{location}',
		'keywords' => 'journalism courses in {location}, journalism colleges in {location}, journalism schools in {location}, journalism institutes in {location},journalism schools, journalism courses, journalism colleges, journalism institutes'
),
156 => array(
		'url' => 'mass-communication-colleges-in-{location}',
		'keywords' => 'mass communication courses in {location}, mass communication colleges in {location}, mass communication schools in {location}, mass communication institutes in {location},mass communication schools, mass communication courses, mass communication colleges, mass communication institutes'
),
157 => array(
		'url' => 'mass-media-courses-in-{location}',
		'keywords' => 'mass media courses in {location}, mass media colleges in {location}, mass media schools in {location}, mass media institutes in {location},mass media schools, mass media courses, mass media colleges, mass media institutes'
),
158 => array(
		'url' => 'photography-courses-in-{location}',
		'keywords' => 'photography courses in {location}, photography colleges in {location}, photography schools in {location}, photography institutes in {location},photography schools, photography courses, photography colleges, photography institutes'
),
159 => array(
		'url' => 'bbm-bba-colleges-in-{Location}',
		'keywords' => 'bbm colleges in {location}, bba colleges in {location}, bbm institutes in {location}, BBA institutes in {location}, bbm colleges, bba colleges, bbm institutes, bba iinstitutes'
),
160 => array(
		'url' => 'certification-courses-in-{Location}',
		'keywords' => 'certification courses in {location}, certification colleges in {location}, certification institutes in {location}, certification courses, certification colleges, certification institutes'
),
161 => array(
		'url' => 'online-mba-in-{Location}',
		'keywords' => 'online mba courses in {location}, online mba colleges in {location}, online mba institutes in {location}, online mba courses, online mba colleges, online mba institutes'
),
162 => array(
		'url' => 'executive-mba-in-{Location}',
		'keywords' => 'executive mba courses in {location}, executive mba colleges in {location}, executive mba institutes in {location}, executive mba courses, executive mba colleges, executive mba institutes'
),
163 => array(
		'url' => 'mba-colleges-in-{Location}',
		'keywords' => 'mba courses in {location}, mba colleges in {location}, mba institutes in {location}, mba courses, mba colleges, mba institutes'
),
164 => array(
		'url' => 'integrated-btech-mba-in-{Location}',
		'keywords' => ''
),
165 => array(
		'url' => 'integrated mba-in-{Location}',
		'keywords' => ''
),
167 => array(
		'url' => 'URLs to be created on pattern similar to existing URLs in Test Preparation',
		'keywords' => ''
),
168 => array(
		'url' => '',
		'keywords' => ''
),
169 => array(
		'url' => '',
		'keywords' => ''
),
170 => array(
		'url' => '',
		'keywords' => ''
),
171 => array(
		'url' => '',
		'keywords' => ''
),
172 => array(
		'url' => '',
		'keywords' => ''
),
173 => array(
		'url' => '',
		'keywords' => ''
),
174 => array(
		'url' => 'arts-colleges-in-{location}',
		'keywords' => 'Arts Colleges in {location}, Humanities Courses, Arts Colleges, Arts courses, Institutes in {location}, arts education, top 10 arts colleges, top arts colleges, universities, institutes, career, career options, Education'
),
175 => array(
		'url' => 'law-colleges-in-{location}',
		'keywords' => ''
),
176 => array(
		'url' => 'teacher-training-colleges-in-{location}',
		'keywords' => 'Teacher Training Courses in {location}, Teacher Training Institutes in {location},Teacher Training Courses, Teacher Training Institutes, universities, institutes, career, career options, Education'
),
177 => array(
		'url' => 'creative-commercial-arts-colleges-in-{location}',
		'keywords' => 'Creative Commercial Arts Colleges in {location}, Creative Arts Colleges in {location}, commercial arts,  Creative Arts,  universities, institutes, career, career options, Education'
),
178 => array(
		'url' => 'social-sciences-colleges-in-{location}',
		'title' => 'Social Sciences Colleges in {location} - Social Sciences Courses - shiksha.com',
		'description' => 'Search Social Sciences Colleges in {location} - Get a list of all Social Sciences, institutes and courses. Know more about the full and part time programs',
		'keywords' => 'Social Sciences Colleges in {location}, Social Science Courses, Social Sciences Colleges, Social Science Courses in {location}, universities, institutes, career, career options, Education'
),
179 => array(
		'url' => 'government-colleges-courses-in-{location}',
		'title' => 'Government Colleges, Courses in {location} - Government Education in {location} - shiksha.com',
		'description' => 'Search Government Colleges, Courses in {location} - Get a list of all Government Education Colleges, institutes and Courses. Know more about the full and part time programs',
		'keywords' => 'Government Colleges in {location}, Government Courses in {location}, Government Education, Government Institutes in {location}, Government Courses, Government Colleges, universities, institutes, career, career options, Education'
),
180 => array(
		'url' => 'language-learning-courses-in-{location}',
		'title' => 'Language Learning  in {location} - Language Courses - shiksha.com',
		'description' => 'Search Language Learning Courses in {location} - Get a list of all Language Learning, institutes and courses. Know more about the full and part time programs',
		'keywords' => 'Language Learning in {location}, Language Courses, Language Learning Courses, Language Courses in {location}, universities, institutes, career, career options, Education'
),
181 => array(
		'url' => 'religious-studies-in-{location}',
		'title' => 'Religious Studies Courses in {location} - Religious Studies Institutes in {location} - shiksha.com',
		'description' => 'Search Religious Studies Courses in {location} - Get a list of all Religious Studies institutes and courses. Know more about the full and part time programs',
		'keywords' => 'Religious Studies Courses in {location}, Religious Studies Institutes in {location}, Religious Studies Courses, Religious Studies Institutes, universities, institutes, career, career options, Education'
),
182 => array(
		'url' => 'culinary-arts-courses-in-{location}',
		'title' => 'Culinary Arts Colleges in {location} - Culinary Arts Courses in {location} - shiksha.com',
		'description' => 'Search Culinary Arts Colleges in {location} - Get a list of all Culinary Arts colleges, institutes and courses. Know more about the full and part time programs',
		'keywords' => 'Culinary Arts Colleges in {location}, Culinary Arts Colleges, Culinary Arts courses, Culinary Arts Institutes in {location}, Culinary Arts education, top Culinary Arts colleges, universities, institutes, career, career options, Education'
),
183 => array(
		'url' => 'phd-courses-in-{location}',
		'keywords' => ''
),
184 => array(
		'url' => 'diploma-in-engineering-in-{location}',
		'keywords' => ''
),
185 => array(
		'url' => 'be-btech-colleges-in-{location}',
		'keywords' => ''
),
186 => array(
		'url' => 'ms-colleges-in-{location}',
		'keywords' => ''
),
187 => array(
		'url' => 'architecture-colleges-in-{location}',
		'keywords' => ''
),
188 => array(
		'url' => 'aircraft-maintenance-engineering-in-{location}',
		'keywords' => ''
),
189 => array(
		'url' => 'be-marine-engineering-colleges-in-{location}',
		'keywords' => ''
),
190 => array(
		'url' => 'fashion-textile-designing-courses-in-{location}',
		'keywords' => ''
),
191 => array(
		'url' => 'interior-designing-courses-in-{location}',
		'keywords' => ''
),
192 => array(
		'url' => 'jewellery-design-courses-in-{location}',
		'keywords' => ''
),
193 => array(
		'url' => 'industrial-automotive-product-design-courses-in-{location}',
		'keywords' => ''
),
194 => array(
		'url' => 'interaction-design-courses-in-{location}',
		'keywords' => ''
),
195 => array(
		'url' => 'accounting-courses-in-{location}',
		'keywords' => ''
),
196 => array(
		'url' => 'finance-courses-in-{location}',
		'keywords' => ''
),
197 => array(
		'url' => 'insurance-courses-in-{location}',
		'keywords' => ''
),
198 => array(
		'url' => 'cfa-cpa-cima-courses-in-{location}',
		'keywords' => ''
),
199 => array(
		'url' => 'commerce-colleges-in-{location}',
		'keywords' => ''
),
200 => array(
		'url' => 'hotel-management-colleges-in-{location}',
		'keywords' => ''
),
201 => array(
		'url' => 'event-management-courses-in-{location}',
		'keywords' => ''
),
202 => array(
		'url' => 'travel-and-tourism-courses-in-{location}',
		'keywords' => ''
),
203 => array(
		'url' => 'aviation-courses-in-{location}',
		'keywords' => ''
),
204 => array(
		'url' => 'aircraft-maintenance-engineering-in-{location}',
		'keywords' => ''
),
205 => array(
		'url' => 'msc-it-computer-science-colleges-in-{location}',
		'keywords' => ''
),
206 => array(
		'url' => 'bsc-it-computer-science-colleges-in-{location}',
		'keywords' => ''
),
207 => array(
		'url' => 'diploma-in-it-in-{location}',
		'keywords' => ''
),
208 => array(
		'url' => 'microsoft-certification-courses-in-{location}',
		'keywords' => ''
),
209 => array(
		'url' => 'six-sigma-certification-in-{location}',
		'keywords' => ''
),
210 => array(
		'url' => 'cisco-certification-courses-in-{location}',
		'keywords' => ''
),
211 => array(
		'url' => 'game-development-courses-in-{location}',
		'keywords' => ''
),
212 => array(
		'url' => 'beautician-cosmetology-courses-in-{location}',
		'keywords' => ''
),
213 => array(
		'url' => 'clinical-research-courses-in-{location}',
		'keywords' => ''
),
214 => array(
		'url' => 'clinical-trials-courses-in-{location}',
		'keywords' => ''
),
215 => array(
		'url' => 'dental-colleges-in-{location}',
		'keywords' => ''
),
216 => array(
		'url' => 'hospital-management-courses-in-{location}',
		'keywords' => ''
),
217 => array(
		'url' => 'mbbs-medical-colleges-in-{location}',
		'keywords' => ''
),
218 => array(
		'url' => 'md-courses-in-{location}',
		'keywords' => ''
),
219 => array(
		'url' => 'microbiology-colleges-in-{location}',
		'keywords' => ''
),
220 => array(
		'url' => 'biotechnology-colleges-in-{location}',
		'keywords' => ''
),
221 => array(
		'url' => 'nursing-colleges-in-{location}',
		'keywords' => ''
),
222 => array(
		'url' => 'pharmacovigilance-courses-in-{location}',
		'keywords' => ''
),
223 => array(
		'url' => 'pharmacy-colleges-in-{location}',
		'keywords' => ''
),
224 => array(
		'url' => 'medical-transcription-courses-in-{location}',
		'keywords' => ''
),
225 => array(
		'url' => 'physiotherapy-courses-in-{location}',
		'keywords' => ''
),
226 => array(
		'url' => 'paramedical-courses-in-{location}',
		'keywords' => ''
),
227 => array(
		'url' => 'radiology-courses-in-{location}',
		'keywords' => ''
),
228 => array(
		'url' => 'alternate-medicine-courses-in-{location}',
		'keywords' => ''
),
229 => array(
		'url' => 'medical-lab-technology-courses-in-{location}',
		'keywords' => ''
),
232 => array(
		'url' => 'bank-po-coaching-classes-in-{location}',
        'keywords' => 'Bank PO exam coaching classes in {location}, Bank PO entrance exams coaching, coaching classes, Bank PO entrance exams coaching institutes, Bank PO entrance exams coaching classes, Bank PO entrance exams in {location}, list of Bank PO entrance exams coaching institutes, popular Bank PO coaching classes'
),
233 => array(
		'url' => 'financial-planning-courses-in-{location}',
		'keywords' => ''
),
234 => array(
		'url' => 'wealth-management-courses-in-{location}',
		'keywords' => ''
),

235=> array(
		'url' => 'optometry-courses-in-{Location}',
		'keywords' => ''),

237=> array(
		'url' => 'public-health-courses-in-{Location}',
		'keywords' => ''),

238=> array(
		'url' => 'advanced-technical-courses-in-{Location}',
		'keywords' => '')
);


$LDBCourseURLData = array(

3 => array(
		'url' => 'mba-in-operations-in-{Location}',
		'keywords' => 'MBA in Operations in {Location}, {Location}MBA in Operations Colleges, List of MBA in Operations in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
5 => array(
		'url' => 'mba-in-sales-and-marketing-in-{Location}',
		'keywords' => 'MBA in Sales & Marketing in {Location}, {Location}MBA in Sales & Marketing Colleges, List of MBA in Sales & Marketing in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
6 => array(
		'url' => 'mba-in-finance-in-{Location}',
		'keywords' => 'MBA in Finance in {Location}, {Location}MBA in Finance Colleges, List of MBA in Finance in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
7 => array(
		'url' => 'mba-in-hr-in-{Location}',
		'keywords' => 'MBA in Human Resources in {Location}, {Location}MBA in Human Resources Colleges, List of MBA in Human Resources in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
8 => array(
		'url' => 'mba-in-it-in-{Location}',
		'keywords' => 'MBA in IT in {Location}, {Location}MBA in IT Colleges, List of MBA in IT in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
9 => array(
		'url' => 'mba-in-international-business-in-{Location}',
		'keywords' => 'MBA in International Business in {Location}, {Location}MBA in International Business Colleges, List of MBA in International Business in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
12 => array(
		'url' => 'mba-in-hospital-management-in-{Location}',
		'keywords' => 'MBA in hospital/Health Care Management in {Location}, {Location}MBA in hospital/Health Care Management Colleges, List of MBA in hospital/Health Care Management in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
14 => array(
		'url' => 'executive-mba-in-operations-in-{Location}',
		'title' => 'Executive MBA in Operations Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Executive MBA in Operations colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Executive MBA in Operations in {Location}, Executive MBA in Operations courses, List of Executive MBA in Operations in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
16 => array(
		'url' => 'executive-mba-in-sales-and-marketing-in-{Location}',
		'title' => 'Executive MBA in Sales & Marketing Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Executive MBA in Sales & Marketing colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Executive MBA in Sales & Marketing in {Location}, Executive MBA in Sales & Marketing courses, List of Executive MBA in Sales & Marketing in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
17 => array(
		'url' => 'executive-mba-in-finance-in-{Location}',
		'title' => 'Executive MBA in Finance Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Executive MBA in Finance colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Executive MBA in Finance in {Location}, Executive MBA in Finance courses, List of Executive MBA in Finance in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
18 => array(
		'url' => 'executive-mba-in-hr-in-{Location}',
		'title' => 'Executive MBA in Human Resources Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Executive MBA in Human Resources colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Executive MBA in Human Resources in {Location}, Executive MBA in Human Resources courses, List of Executive MBA in Human Resources in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
19 => array(
		'url' => 'executive-mba-in-it-in-{Location}',
		'title' => 'Executive MBA in IT Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Executive MBA in IT colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Executive MBA in IT in {Location}, Executive MBA in IT courses, List of Executive MBA in IT in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
20 => array(
		'url' => 'executive-mba-in-international-business-in-{Location}',
		'title' => 'Executive MBA in International Business Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Executive MBA in International Business colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Executive MBA in International Business in {Location}, Executive MBA in International Business courses, List of Executive MBA in International Business in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
23 => array(
		'url' => 'executive-mba-in-hospital-management-in-{Location}',
		'title' => 'Executive MBA in hospital/Health Care Management Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Executive MBA in hospital/Health Care Management colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Executive MBA in hospital/Health Care Management in {Location}, Executive MBA in hospital/Health Care Management courses, List of Executive MBA in hospital/Health Care Management in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
27 => array(
		'url' => 'mba-hr-distance-learning-in-{Location}',
		'title' => 'Distance/Correspondence MBA in Human Resources Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Distance/Correspondence MBA in Human Resources colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'MBA Human Resources Distance Learning in {Location}, MBA Human Resources Distance Learning, List of MBA Human Resources Distance Learning in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
28 => array(
		'url' => 'mba-finance-distance-learning-in-{Location}',
		'title' => 'Distance/Correspondence MBA in Finance Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Distance/Correspondence MBA in Finance colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'MBA Finance Distance Learning in {Location}, MBA Finance Distance Learning, List of MBA Finance Distance Learning in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
29 => array(
		'url' => 'mba-international-business-distance-learning-in-{Location}',
		'title' => 'Distance/Correspondence MBA in International Business Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Distance/Correspondence MBA in International Business colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'MBA International Business Distance Learning in {Location}, MBA International Business Distance Learning, List of MBA International Business Distance Learning in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
30 => array(
		'url' => 'mba-it-distance-learning-in-{Location}',
		'title' => 'Distance/Correspondence MBA in IT Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Distance/Correspondence MBA in IT colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'MBA IT Distance Learning in {Location}, MBA IT Distance Learning, List of MBA IT Distance Learning in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
31 => array(
		'url' => 'mba-marketing-distance-learning-in-{Location}',
		'title' => 'Distance/Correspondence MBA in Marketing Management Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Distance/Correspondence MBA in Marketing Management colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'MBA Marketing Management Distance Learning in {Location}, MBA Marketing Management Distance Learning, List of MBA Marketing Management Distance Learning in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
32 => array(
		'url' => 'mba-sales-and-marketing-distance-learning-in-{Location}',
		'title' => 'Distance/Correspondence MBA in Sales Management Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Distance/Correspondence MBA in Sales Management colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'MBA Sales Management Distance Learning in {Location}, MBA Sales Management Distance Learning, List of MBA Sales Management Distance Learning in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
33 => array(
		'url' => 'mba-hospital-management-distance-learning-in-{Location}',
		'title' => 'Distance/Correspondence MBA in hospital/Health Care Management Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Distance/Correspondence MBA in hospital/Health Care Management colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'MBA hospital/Health Care Management Distance Learning in {Location}, MBA hospital/Health Care Management Distance Learning, List of MBA hospital/Health Care Management Distance Learning in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
34 => array(
		'url' => 'mba-operations-distance-learning-in-{Location}',
		'title' => 'Distance/Correspondence MBA in Operations Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Distance/Correspondence MBA in Operations colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'MBA Operations Distance Learning in {Location}, MBA Operations Distance Learning, List of MBA Operations Distance Learning in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
54 => array(
		'url' => 'c-cplus-plus-certification-training-institutes-in-{location}',
		'keywords' => 'C Cplus plus Certification Training Institutes In {Location}, C Cplus plus Certification Courses,Certification Courses, Certification Training, universities, institutes, career, career options, Education'
),
57 => array(
		'url' => 'java-certification-training-institutes-in-{location}',
		'keywords' => 'Java Certification Training Institutes In {Location}, Java Certification Courses,Certification Courses, Certification Training, universities, institutes, career, career options, Education'
),
59 => array(
		'url' => 'j2ee-certification-training-institutes-in-{location}',
		'keywords' => 'J2EE Certification Training Institutes In {Location}, J2EE Certification Courses,Certification Courses, Certification Training, universities, institutes, career, career options, Education'
),
60 => array(
		'url' => 'core-java-certification-training-institutes-in-{location}',
		'keywords' => 'Core Java Certification Training Institutes In {Location}, Core Java Certification Courses,Certification Courses, Certification Training, universities, institutes, career, career options, Education'
),
61 => array(
		'url' => 'php-certification-training-institutes-in-{location}',
		'keywords' => 'PHP Certification Training Institutes In {Location}, PHP Certification Courses,Certification Courses, Certification Training, universities, institutes, career, career options, Education'
),
62 => array(
		'url' => 'vb-net-certification-training-institutes-in-{location}',
		'keywords' => 'VB NET Certification Training Institutes In {Location}, VB NET Certification Courses, Certification Courses, Certification Training, universities, institutes, career, career options, Education'
),
73 => array(
		'url' => 'cisco-ccent-certification-training-institutes-in-{location}',
		'keywords' => ' CCENT Certification Training Institutes In {Location},  CCENT Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
74 => array(
		'url' => 'cisco-ccna-certification-training-institutes-in-{location}',
		'keywords' => ' CCNA Certification Training Institutes In {Location},  CCNA Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
75 => array(
		'url' => 'cisco-ccda-certification-training-institutes-in-{location}',
		'keywords' => 'Cisco Certifications CCDA Certification Training Institutes In {Location}, Cisco Certifications CCDA Certification Courses,Cisco Certifications Certification Courses, Cisco Certifications Certification Training, universities, institutes, career, career options, Education'
),
79 => array(
		'url' => 'cisco-ccnp-certification-training-institutes-in-{location}',
		'keywords' => ' CCNP Certification Training Institutes In {Location},  CCNP Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
80 => array(
		'url' => 'cisco-ccsp-certification-training-institutes-in-{location}',
		'keywords' => ' CCSP Certification Training Institutes In {Location},  CCSP Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
81 => array(
		'url' => 'cisco-ccvp-certification-training-institutes-in-{location}',
		'keywords' => ' CCVP Certification Training Institutes In {Location},  CCVP Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
82 => array(
		'url' => 'cisco-ccdp-certification-training-institutes-in-{location}',
		'keywords' => ' CCDP Certification Training Institutes In {Location},  CCDP Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
83 => array(
		'url' => 'cisco-ccip-certification-training-institutes-in-{location}',
		'keywords' => ' CCIP Certification Training Institutes In {Location},  CCIP Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
84 => array(
		'url' => 'cisco-ccie-certification-training-institutes-in-{location}',
		'keywords' => ' CCIE Certification Training Institutes In {Location},  CCIE Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
91 => array(
		'url' => 'microsoft-mcp-certification-training-institutes-in-{location}',
		'keywords' => ' MCP Certification Training Institutes In {Location},  MCP Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
92 => array(
		'url' => 'microsoft-mcitp-certification-training-institutes-in-{location}',
		'keywords' => ' MCITP Certification Training Institutes In {Location},  MCITP Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
93 => array(
		'url' => 'microsoft-mcpd-certification-training-institutes-in-{location}',
		'keywords' => ' MCPD Certification Training Institutes In {Location},  MCPD Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
94 => array(
		'url' => 'microsoft-mcts-certification-training-institutes-in-{location}',
		'keywords' => ' MCTS Certification Training Institutes In {Location},  MCTS Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
95 => array(
		'url' => 'microsoft-mcsa-certification-training-institutes-in-{location}',
		'keywords' => ' MCSA Certification Training Institutes In {Location},  MCSA Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
96 => array(
		'url' => 'microsoft-mcdba-certification-training-institutes-in-{location}',
		'keywords' => 'Microsoft Certifications MCDBA Certification Training Institutes In {Location}, Microsoft Certifications MCDBA Certification Courses,Microsoft Certifications Certification Courses, Microsoft Certifications Certification Training, universities, institutes, career, career options, Education'
),
98 => array(
		'url' => 'microsoft-mcse-certification-training-institutes-in-{location}',
		'keywords' => ' MCSE Certification Training Institutes In {Location},  MCSE Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
99 => array(
		'url' => 'microsoft-mos-certification-training-institutes-in-{location}',
		'keywords' => ' MOS Certification Training Institutes In {Location},  MOS Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
100 => array(
		'url' => 'doeacc-o-level-course-in-{location}',
		'keywords' => 'DOEACC O Level Course in {Location}, DOEACC Courses, DOEACC O Level Courses, DOEACC Courses in {Location}, universities, institutes, career, career options, Education'
),
101 => array(
		'url' => 'doeacc-a-level-course-in-{location}',
		'keywords' => 'DOEACC A Level Course in {Location}, DOEACC Courses, DOEACC A Level Courses, DOEACC Courses in {Location}, universities, institutes, career, career options, Education'
),
103 => array(
		'url' => 'doeacc-c-level-course-in-{location}',
		'keywords' => 'DOEACC C Level Course in {Location}, DOEACC Courses, DOEACC C Level Courses, DOEACC Courses in {Location}, universities, institutes, career, career options, Education'
),
104 => array(
		'url' => 'rhct-certification-training-institutes-in-{location}',
		'keywords' => 'RHCT Certification Training Institutes In {Location}, RHCT Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
105 => array(
		'url' => 'rhce-certification-training-institutes-in-{location}',
		'keywords' => 'RHCE Certification Training Institutes In {Location}, RHCE Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
106 => array(
		'url' => 'rhcss-certification-training-institutes-in-{location}',
		'keywords' => 'RHCSS Certification Training Institutes In {Location}, RHCSS Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
108 => array(
		'url' => 'rhca-certification-training-institutes-in-{location}',
		'keywords' => 'RHCA Certification Training Institutes In {Location}, RHCA Certification Courses,Red Hat Certifications Certification Courses, Red Hat Certifications Certification Training, universities, institutes, career, career options, Education'
),
113 => array(
		'url' => 'oracle-oca-certification-training-institutes-in-{location}',
		'keywords' => 'Oracle/DB OCA Certification Training Institutes In {Location}, Oracle/DB OCA Certification Courses,Oracle/DB Certification Courses, Oracle/DB Certification Training, universities, institutes, career, career options, Education'
),
115 => array(
		'url' => 'data-warehousing-certification-training-institutes-in-{location}',
		'keywords' => 'Data Warehousing Certification Training Institutes In {Location}, Data Warehousing Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
117 => array(
		'url' => 'oracle-db2-certification-training-institutes-in-{location}',
		'keywords' => ' DB2 Certification Training Institutes In {Location},  DB2 Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
118 => array(
		'url' => 'oracle-dba-certification-training-institutes-in-{location}',
		'keywords' => ' DBA Certification Training Institutes In {Location},  DBA Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
127 => array(
		'url' => 'ceh-certification-training-institutes-in-{location}',
		'keywords' => 'CEH - Certifies Ethical Hacker Certification Training Institutes In {Location}, CEH - Certifies Ethical Hacker Certification Courses,Ethical Hacking Certification Courses, Ethical Hacking Certification Training, universities, institutes, career, career options, Education'
),
128 => array(
		'url' => 'chfi-certification-training-institutes-in-{location}',
		'keywords' => 'CHFI - Computer Hacking Forensic Investigator Certification Training Institutes In {Location}, CHFI - Computer Hacking Forensic Investigator Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
158 => array(
		'url' => 'erp-certification-training-institutes-in-{location}',
		'keywords' => 'ERP Certification Training Institutes In {Location}, ERP Certification Courses,Linux Certification Courses, Linux Certification Training, universities, institutes, career, career options, Education'
),
190 => array(
		'url' => 'bsc-animation-in-{location}',
		'keywords' => 'bsc animation colleges in {location}, bsc animation institutes in {location}, bsc animation colleges, bsc animation institutes, bsc animation course'
),
195 => array(
		'url' => 'ba-animation-in-{location}',
		'keywords' => 'ba animation colleges in {location}, ba animation institutes in {location}, ba animation colleges, ba animation institutes, ba animation course'
),
201 => array(
		'url' => 'diploma-in-animation-in-{location}',
		'keywords' => 'diploma in animation in {location}, diploma courses in animation in {location}, diploma colleges in animation in {location}, diploma institutes in animation in {location}, full time diploma courses in animation in {location}, part time diploma courses in animation in {location}'
),
205 => array(
		'url' => 'msc-animation-in-{location}',
		'keywords' => 'msc animation colleges in {location}, msc animation institutes in {location}, msc animation colleges, msc animation institutes, msc animation course'
),
207 => array(
		'url' => 'msc-multimedia-in-{location}',
		'keywords' => 'bsc multimedia colleges in {location}, msc multimedia institutes in {location}, msc multimedia colleges, msc multimedia institutes, msc multimedia course'
),
209 => array(
		'url' => 'pg-diploma-in-animation-in-{location}',
		'keywords' => 'pg diploma in animation in {location}, pg diploma courses in animation in {location}, pg, diploma colleges in animation in {location}, pg diploma institutes in animation in {location}, full time pg diploma courses in animation in {location}, part time pg diploma courses in animation in {location}'
),
259 => array(
		'url' => 'bsc-in-toursim-in-{location}',
		'keywords' => 'bsc in tourism in {location}, bsc in tourism colleges in {location}, bsc in tourism institutes in {location}, bsc in tourism courses, bsc in tourism colleges, bsc in tourism institutes, full time bsc in tourism courses, part time bsc in tourism courses'
),
261 => array(
		'url' => 'ba-in-travel-and-tourism-in-{location}',
		'keywords' => 'ba in travel and tourism in {location}, ba in travel and tourism colleges in {location}, ba in travel and tourism institutes in {location}, ba in travel and tourism courses, ba in travel and tourism colleges, ba in travel and tourism institutes, ba in travel and tourism courses, full time ba in travel and tourism courses, part time ba in travel and tourism courses'
),
264 => array(
		'url' => 'bachelor-in-hotel-management-in-{location}',
		'keywords' => 'bachelor in hotel management courses in {location}, bachelor in hotel management colleges in {location}, bachelor in hotel management institutes in {location}, bachelor in hotel management courses, bachelor in hotel management colleges, bachelor in hotel management institutes, bachelor in hotel management schools, full time bachelor in hotel management, part time bachelor in hotel management'
),
265 => array(
		'url' => 'bba-in-aviation-in-{location}',
		'keywords' => 'bba in aviation courses in {location}, bba in aviation colleges in {location}, bba in aviation institutes in {location}, bba in aviation courses, bba in aviation colleges, bba in aviation institutes, bba in aviation schools'
),
268 => array(
		'url' => 'bba-in-travel-tourism-management-in-{location}',
		'keywords' => 'bba in travel and tourism management in {location}, bba in travel and tourism management colleges in {location}, bba in travel and tourism management institutes in {location}, bba in travel and tourism management courses, bba in travel and tourism management colleges, bba in travel and tourism management institutes, bba in travel and tourism courses, full time bba in travel and tourism management courses, part time bba in travel and tourism management courses'
),
269 => array(
		'url' => 'diploma-in-airport-management-in-{location}',
		'keywords' => 'diploma in airport management in {location}, diploma in airport management colleges in {location}, diploma in airport management institutes in {location}, diploma in airport management courses, diploma in airport management colleges, diploma in airport management institutes, diploma in airport management, full time diploma in airport management, part time diploma in airport management'
),
271 => array(
		'url' => 'diploma-in-cabin-crew-training-in-{location}',
		'keywords' => 'diploma in cabin crew in {location}, cabin crew training in {location}, diploma in cabin crew colleges in {location}, diploma in cabin crew institutes in {location}, diploma in cabin crew courses, diploma in cabin crew colleges, diploma in cabin crew institutes, diploma in cabin crew, full time diploma in cabin crew, part time diploma in cabin crew'
),
278 => array(
		'url' => 'diploma-in-travel-and-tourism-management-in-{location}',
		'keywords' => 'travel and tourism management in {location}, travel and tourism management colleges in {location}, travel and tourism management institutes in {location}, travel and tourism management courses, travel and tourism management colleges, travel and tourism management institutes, travel and tourism courses, full time travel and tourism management courses, part time travel and tourism management courses'
),
287 => array(
		'url' => 'pg-diploma-in-travel-and-tourism-management-in-{location}',
		'keywords' => 'travel and tourism management in {location}, travel and tourism management colleges in {location}, travel and tourism management institutes in {location}, travel and tourism management courses, travel and tourism management colleges, travel and tourism management institutes, travel and tourism courses, full time travel and tourism management courses, part time travel and tourism management courses'
),
291 => array(
		'url' => 'iata-courses-in-aviation-travel-industry',
		'keywords' => 'IATA courses in {location}, courses in aviation travle industry in {location}, aviation travle industry colleges in {location}, aviation travle industry institutes in {location}, aviation travle industry courses, aviation travle industry colleges, aviation travle industry institutes, full time courses in aviation travel industry, part time courses in aviation travle industry'
),
301 => array(
		'url' => 'commercial-pilot-training-in-{location}',
		'keywords' => 'commercial pilot training in {location}, commercial pilot training colleges in {location}, commercial pilot training institutes in {location}, commercial pilot training courses, commercial pilot training colleges, commercial pilot training institutes, commercial pilot training, full time commercial pilot training courses, part time commercial pilot training courses'
),
320 => array(
		'url' => 'aerospace-aeronautical-engineering-colleges-in-{location}',
		'keywords' => 'Aeronautical/Aerospace Engineering Colleges in {Location},  Aeronautical/Aerospace Engineering Courses, Aeronautical/Aerospace Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
321 => array(
		'url' => 'agricultural-engineering-colleges-in-{location}',
		'keywords' => 'Agricultural Engineering Colleges in {Location},  Agricultural Engineering Courses, Agricultural Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
322 => array(
		'url' => 'automobile-engineering-colleges-in-{location}',
		'keywords' => 'Automobile Engineering Colleges in {Location},  Automobile Engineering Courses, Automobile Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
323 => array(
		'url' => 'chemical-engineering-colleges-in-{location}',
		'keywords' => 'Chemical Engineering Colleges in {Location},  Chemical Engineering Courses, Chemical Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
324 => array(
		'url' => 'civil-engineering-colleges-in-{location}',
		'keywords' => 'Civil Engineering Colleges in {Location},  Civil Engineering Courses, Civil Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
325 => array(
		'url' => 'computer-science-engineering-colleges-in-{location}',
		'keywords' => 'Computer Science Engineering Colleges in {Location},  Computer Science Engineering Courses, Computer Science Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
326 => array(
		'url' => 'electrical-engineering-colleges-in-{location}',
		'keywords' => 'Electrical Engineering Colleges in {Location},  Electrical Engineering Courses, Electrical Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
327 => array(
		'url' => 'electronics-and-communication-engineering-colleges-in-{location}',
		'keywords' => 'Electronics & Communications Engineering Colleges in {Location},  Electronics & Communications Engineering Courses, Electronics & Communications Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
328 => array(
		'url' => 'industrial-engineering-colleges-in-{location}',
		'keywords' => 'Industrial Engineering Colleges in {Location},  Industrial Engineering Courses, Industrial Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
329 => array(
		'url' => 'information-technology-it-engineering-colleges-in-{location}',
		'keywords' => 'Information Technology (IT) Engineering Colleges in {Location},  Information Technology (IT) Engineering Courses, Information Technology (IT) Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
330 => array(
		'url' => 'marine-engineering-colleges-in-{location}',
		'keywords' => 'Marine Engineering Colleges in {Location},  Marine Engineering Courses, Marine Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
331 => array(
		'url' => 'mechanical-engineering-colleges-in-{location}',
		'keywords' => 'Mechanical Engineering Colleges in {Location},  Mechanical Engineering Courses, Mechanical Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
332 => array(
		'url' => 'metallurgical-engineering-colleges-in-{location}',
		'keywords' => 'Metallurgical Engineering Colleges in {Location},  Metallurgical Engineering Courses, Metallurgical Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
333 => array(
		'url' => 'mining-engineering-colleges-in-{location}',
		'keywords' => 'Mining Engineering Colleges in {Location},  Mining Engineering Courses, Mining Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
334 => array(
		'url' => 'nano-technology-engineering-colleges-in-{location}',
		'keywords' => 'Nanotechnology Engineering Colleges in {Location},  Nanotechnology Engineering Courses, Nanotechnology Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
335 => array(
		'url' => 'textile-engineering-colleges-in-{location}',
		'keywords' => 'Textile Engineering Colleges in {Location},  Textile Engineering Courses, Textile Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
336 => array(
		'url' => 'me-mtech-in-automobile-engineering-in-{location}',
		'title' => 'M.Tech in Automobile Engineering Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Automobile Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Automobile Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Automobile Engineering in {Location}, ME in Automobile Engineering in {Location}, universities, institutes, career, career options, Education'
),
337 => array(
		'url' => 'me-mtech-in-avionics-engineering-in-{location}',
		'title' => 'M.Tech in Avionics Engineering colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Avionics Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Avionics Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Avionics Engineering in {Location}, ME in Avionics Engineering in {Location}, universities, institutes, career, career options, Education'
),
338 => array(
		'url' => 'me-mtech-in-chemical-engineering-in-{location}',
		'title' => 'M.Tech in Chemical Engineering colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Chemical Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Chemical Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Chemical Engineering in {Location}, ME in Chemical Engineering in {Location}, universities, institutes, career, career options, Education'
),
339 => array(
		'url' => 'me-mtech-in-communications-engineering-in-{location}',
		'title' => 'M.Tech in Communications Engineering colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Communications Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Communications Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Communications Engineering in {Location}, ME in Communications Engineering in {Location}, universities, institutes, career, career options, Education'
),
340 => array(
		'url' => 'me-mtech-in-computer-science-engineering-in-{location}',
		'title' => 'M.Tech in Computer Science Engineering colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Computer Science Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Computer Science Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Computer Science Engineering in {Location}, ME in Computer Science Engineering in {Location}, universities, institutes, career, career options, Education'
),
341 => array(
		'url' => 'me-mtech-in-control-systems-engineering-in-{location}',
		'title' => 'M.Tech in Control Systems Engineering colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Control Systems Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Control Systems Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Control Systems Engineering in {Location}, ME in Control Systems Engineering in {Location}, universities, institutes, career, career options, Education'
),
342 => array(
		'url' => 'me-mtech-in-electronics-engineering-in-{location}',
		'title' => 'M.Tech in Electronics & Communications Engineering colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Electronics & Communications Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Electronics & Communications Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Electronics & Communications Engineering in {Location}, ME in Electronics & Communications Engineering in {Location}, universities, institutes, career, career options, Education'
),
343 => array(
		'url' => 'me-mtech-in-instrumentation-engineering-in-{location}',
		'title' => 'M.Tech in Instrumentation Engineering colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Instrumentation Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Instrumentation Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Instrumentation Engineering in {Location}, ME in Instrumentation Engineering in {Location}, universities, institutes, career, career options, Education'
),
344 => array(
		'url' => 'me-mtech-in-mechatronics-engineering-in-{location}',
		'title' => 'M.Tech in Mechatronics Engineering colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Mechatronics Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Mechatronics Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Mechatronics Engineering in {Location}, ME in Mechatronics Engineering in {Location}, universities, institutes, career, career options, Education'
),
345 => array(
		'url' => 'me-mtech-in-nanotechnology-engineering-in-{location}',
		'title' => 'M.Tech in Nanotechnology Engineering colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Nanotechnology Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Nanotechnology Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Nanotechnology Engineering in {Location}, ME in Nanotechnology Engineering in {Location}, universities, institutes, career, career options, Education'
),
346 => array(
		'url' => 'me-mtech-in-power systems-engineering-in-{location}',
		'title' => 'M.Tech in Power Systems Engineering colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Power Systems Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Power Systems Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Power Systems Engineering in {Location}, ME in Power Systems Engineering in {Location}, universities, institutes, career, career options, Education'
),
347 => array(
		'url' => 'me-mtech-in-signal processing-engineering-in-{location}',
		'title' => 'M.Tech in Signal Processing Engineering colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Signal Processing Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Signal Processing Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Signal Processing Engineering in {Location}, ME in Signal Processing Engineering in {Location}, universities, institutes, career, career options, Education'
),
348 => array(
		'url' => 'me-mtech-in-solar-alternative-engineering-in-{location}',
		'title' => 'M.Tech in Solar & Alternative Engineering colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Solar & Alternative Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Solar & Alternative Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Solar & Alternative Engineering in {Location}, ME in Solar & Alternative Engineering in {Location}, universities, institutes, career, career options, Education'
),
349 => array(
		'url' => 'me-mtech-in-telecommunications-engineering-in-{location}',
		'title' => 'M.Tech in Telecommunications Engineering colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Telecommunications Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Telecommunications Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Telecommunications Engineering in {Location}, ME in Telecommunications Engineering in {Location}, universities, institutes, career, career options, Education'
),
350 => array(
		'url' => 'me-mtech-in-vlsi-engineering-in-{location}',
		'title' => 'M.Tech in VLSI Engineering colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in VLSI Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in VLSI Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in VLSI Engineering in {Location}, ME in VLSI Engineering in {Location}, universities, institutes, career, career options, Education'
),
351 => array(
		'url' => 'me-mtech-in-wireless-communication-engineering-in-{location}',
		'title' => 'M.Tech in Wireless Communication Engineering colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Wireless Communication Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Wireless Communication Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Wireless Communication Engineering in {Location}, ME in Wireless Communication Engineering in {Location}, universities, institutes, career, career options, Education'
),
354 => array(
		'url' => 'm-pharma-colleges-in-{location}',
		'keywords' => 'm pharma courses in {location}, m pharma colleges in {location}, m pharma institutes in {location}, m pharma courses, m pharma colleges, m pharma institutes'
),
387 => array(
		'url' => 'msc-in-clinical-research-in-{location}',
		'keywords' => 'msc in clinical research in {location}, clinical research colleges in {location}, msc in clinical research, clinical research courses in {location}, clinical research institutes in {location}, msc in clinical research'
),
391 => array(
		'url' => 'pg-diploma-in-clinical-research-in-{location}',
		'keywords' => 'PG diploma in clinical research in {location}, PG diploma courses in clinical research in {location}, PG diploma colleges in clinical research in {location}, PG diploma institutes in clinical research in {location}, full time PG diploma courses in clinical research in {location}, part time PG diploma courses in clinical research in {location}'
),
420 => array(
		'url' => 'bsc-electronic-media-colleges-in-{location}',
		'keywords' => 'bsc in electronic media in {location}, electronic media colleges in {location}, bsc in electronic media, electronic media courses in {location}, electronic media institutes in {location}'
),
421 => array(
		'url' => 'bsc-mass-communication-courses-in-{location}',
		'keywords' => 'bsc mass communication courses in {location}, bsc mass communication colleges in {location}, bsc mass communication schools in {location}, bsc mass communication institutes in {location}, bsc mass communication courses'
),
422 => array(
		'url' => 'bsc-multimedia-in-{location}',
		'keywords' => 'bsc multimedia colleges in {location}, bsc multimedia institutes in {location}, bsc multimedia colleges, bsc multimedia institutes, bsc multimedia course'
),
423 => array(
		'url' => 'bj-bachelor-of-journalism-in-{location}',
		'keywords' => 'bsc mass communication courses in {location}, bsc mass communication colleges in {location}, bsc mass communication schools in {location}, bsc mass communication institutes in {location}, bsc mass communication courses'
),
424 => array(
		'url' => 'diploma-in-acting-in-{location}',
		'keywords' => 'diploma in acting in {location}, diploma courses in acting in {location}, diploma colleges in acting in {location}, diploma institutes in acting in {location}, full time diploma courses in acting in {location}, part time diploma courses in acting in {location}'
),
428 => array(
		'url' => 'diploma-in-film-direction-in-{location}',
		'keywords' => 'diploma in film direction in {location}, diploma courses in film direction in {location}, diploma colleges in film direction in {location}, diploma institutes in film direction in {location}, full time diploma courses in film direction in {location}, part time diploma courses in film direction in {location}'
),
429 => array(
		'url' => 'diploma-in-film-production-in-{location}',
		'keywords' => 'diploma in film production in {location}, diploma courses in film production in {location}, diploma colleges in film production in {location}, diploma institutes in film production in {location}, full time diploma courses in film production in {location}, part time diploma courses in film production in {location}'
),
430 => array(
		'url' => 'diploma-in-journalism-in-{location}',
		'keywords' => 'diploma in journalism in {location}, diploma courses in journalism in {location}, diploma colleges in journalism in {location}, diploma institutes in journalism in {location}, full time diploma courses in journalism in {location}, part time diploma courses in journalism in {location}'
),
431 => array(
		'url' => 'diploma-in-media-management-in-{location}',
		'keywords' => 'diploma in media management in {location}, media management colleges in {location}, diploma in media management, media management courses in {location}, media management institutes in {location}'
),
433 => array(
		'url' => 'diploma-in-anchoring-in-{location}',
		'keywords' => 'diploma in anchoring in {location}, diploma courses in anchoring in {location}, diploma colleges in anchoring in {location}, diploma institutes in anchoring in {location}, full time diploma courses in anchoring in {location}, part time diploma courses in anchoring in {location}'
),
435 => array(
		'url' => 'diploma-in-radio-jockeying-in-{location}',
		'keywords' => 'diploma in radio jockeying in {location}, diploma courses in radio jockeying in {location}, diploma colleges in radio jockeying in {location}, diploma institutes in radio jockeying in {location}, full time diploma courses in radio jockeying in {location}, part time diploma courses in radio jockeying in {location}'
),
438 => array(
		'url' => 'diploma-in-visual-communication-in-{location}',
		'keywords' => ''
),
440 => array(
		'url' => 'ma-mass-communication-colleges-in-{location}',
		'keywords' => 'M.A. in mass communication courses in {location}, M.A. in mass communication colleges in {location}, M.A. in mass communication institutes in {location}, M.A. in mass communication courses'
),
441 => array(
		'url' => 'msc-mass-communication-colleges-in-{location}',
		'keywords' => 'bsc mass communication courses in {location}, bsc mass communication colleges in {location}, bsc mass communication schools in {location}, bsc mass communication institutes in {location}, bsc mass communication courses'
),
442 => array(
		'url' => 'mba-in-media-management-in-{location}',
		'keywords' => 'mba in media management in {location}, media management colleges in {location}, mba in media management, media management courses in {location}, media management institutes in {location}'
),
444 => array(
		'url' => 'pg-diploma-in-mass-communication-in-{location}',
		'keywords' => 'pg diploma in mass communication in {location}, pg diploma in mass communication colleges in {location}, pg diploma in mass communication courses in {location}, pg diploma in mass communication institutes in {location}, pg diploma in mass communication courses'
),
449 => array(
		'url' => 'sound-recording-courses-in-{location}',
		'keywords' => 'sound recording courses in {location}, sound editing courses in {location}, sound recording colleges in {location}, sound recording schools in {location}, sound recording institutes in {location}'
),
453 => array(
		'url' => 'ba-colleges-in-{location}',
		'keywords' => 'BA Colleges in {location}, BA Courses,  BA Arts Colleges,  BA Arts courses, Institutes in {Location}, arts education, top 10 arts colleges, top arts colleges, universities, institutes, career, career options, Education'
),
454 => array(
		'url' => 'ma-colleges-in-{location}',
		'keywords' => 'MA Colleges in {location}, MA Courses,  MA Arts Colleges,  MA Arts courses, Institutes in {Location}, arts education, top 10 arts colleges, top arts colleges, universities, institutes, career, career options, Education'
),
459 => array(
		'url' => 'llb-colleges-in-{location}',
		'keywords' => 'LLB Colleges in {Location}, LLB Courses, LLB Colleges,  LLB Courses in {Location}, universities, institutes, career, career options, Education'
),
460 => array(
		'url' => 'llm-colleges-in-{location}',
		'keywords' => 'LLM Colleges in {Location}, LLM Courses, LLM Colleges, LLM Courses in {Location}, universities, institutes, career, career options, Education'
),
461 => array(
		'url' => 'pg-diploma-in-law-in-{location}',
		'keywords' => 'PG Diploma in Law in {Location}, PG Diploma Courses in law, Law Colleges in {location}, Law Courses, Law Colleges, Law Courses  in {Location}, Law education, universities, institutes, career, career options, Education'
),
477 => array(
		'url' => 'bed-colleges-in-{location}',
		'keywords' => 'Bed Colleges in {Location}, Bed Courses, Bed Teacher Training in {Location}, Teacher Training Courses in {Location}, Teacher Training Institutes in {Location},Teacher Training Courses, Teacher Training Institutes, universities, institutes, career, career options, Education'
),
478 => array(
		'url' => 'med-colleges-in-{location}',
		'keywords' => 'Med Colleges in {Location}, Bed Courses, Med Teacher Training in {Location}, Med Colleges, Teacher Training Courses in {Location}, Teacher Training Institutes in {Location},Teacher Training Courses, Teacher Training Institutes, universities, institutes, career, career options, Education'
),
487 => array(
		'url' => 'bsw-colleges-in-{location}',
		'keywords' => 'BSW Colleges in {Location}, BSW Courses, BSW Colleges, BSW Courses in {Location}, universities, institutes, career, career options, Education'
),
491 => array(
		'url' => 'msw-colleges-in-{location}',
		'keywords' => 'MSW Colleges in {Location}, MSW Courses, MSW Colleges, MSW Courses in {Location}, universities, institutes, career, career options, Education'
),
494 => array(
		'url' => 'english-language-learning-courses-in-{location}',
		'keywords' => 'English Language Learning Courses in {Location}, English Language Learning in {Location}, English Language Courses, Language Learning Courses, Language Courses in {Location}, universities, institutes, career, career options, Education'
),
496 => array(
		'url' => 'chinese-language-learning-courses-in-{location}',
		'keywords' => 'Chinese Language Learning Courses in {Location}, Chinese Language Learning in {Location}, Chinese Language Courses, Language Learning Courses, Language Courses in {Location}, universities, institutes, career, career options, Education'
),
497 => array(
		'url' => 'german-language-learning-courses-in-{location}',
		'keywords' => 'German Language Learning Courses in {Location}, German Language Learning in {Location}, German Language Courses, Language Learning Courses, Language Courses in {Location}, universities, institutes, career, career options, Education'
),
498 => array(
		'url' => 'french-language-learning-courses-in-{location}',
		'keywords' => 'French Language Learning Courses in {Location}, French Language Learning in {Location}, French Language Courses, Language Learning Courses, Language Courses in {Location}, universities, institutes, career, career options, Education'
),
499 => array(
		'url' => 'spanish-language-learning-courses-in-{location}',
		'keywords' => 'Spanish Language Learning Courses in {Location}, Spanish Language Learning in {Location}, Spanish Language Courses, Language Learning Courses, Language Courses in {Location}, universities, institutes, career, career options, Education'
),
500 => array(
		'url' => 'japanese-language-learning-courses-in-{location}',
		'keywords' => 'Japanese Language Learning Courses in {Location}, Japanese Language Learning in {Location}, Japanese Language Courses, Language Learning Courses, Language Courses in {Location}, universities, institutes, career, career options, Education'
),
506 => array(
		'url' => 'acca-exam-coaching-classes-in-{location}',
		'keywords' => 'ACCA Exam Coaching Classes in {Location}, ACCA Coaching Classes, ACCA Exam Coaching Classes Courses, ACCA Exam in {Location}, universities, institutes, career, career options, Education'
),
508 => array(
		'url' => 'ca-cpt-exam-coaching-classes-in-{location}',
		'keywords' => 'CA CPT Exam Coaching Classes in {Location}, CA CPT Coaching Classes, CA CPT Exam Coaching Classes Courses, CA CPT Exam in {Location}, universities, institutes, career, career options, Education'
),
509 => array(
		'url' => 'ca-final-exam-coaching-classes-in-{location}',
		'keywords' => 'CA Final Exam Coaching Classes in {Location}, CA Final Coaching Classes, CA Final Exam Coaching Classes Courses, CA Final Exam in {Location}, universities, institutes, career, career options, Education'
),
510 => array(
		'url' => 'ca-gmc-exam-coaching-classes-in-{location}',
		'keywords' => 'CA GMC Exam Coaching Classes in {Location}, CA GMC Coaching Classes, CA GMC Exam Coaching Classes Courses, CA GMC Exam in {Location}, universities, institutes, career, career options, Education'
),
511 => array(
		'url' => 'ca-ipcc-exam-coaching-classes-in-{location}',
		'keywords' => 'CA IPCC Exam Coaching Classes in {Location}, CA IPCC Coaching Classes, CA IPCC Exam Coaching Classes Courses, CA IPCC Exam in {Location}, universities, institutes, career, career options, Education'
),
512 => array(
		'url' => 'ca-pce-exam-coaching-classes-in-{location}',
		'keywords' => 'CA PCE Exam Coaching Classes in {Location}, CA PCE Coaching Classes, CA PCE Exam Coaching Classes Courses, CA PCE Exam in {Location}, universities, institutes, career, career options, Education'
),
513 => array(
		'url' => 'cfa-exam-coaching-classes-in-{location}',
		'keywords' => 'CFA Exam Coaching Classes in {Location}, CFA Coaching Classes, CFA Exam Coaching Classes Courses, CFA Exam in {Location}, universities, institutes, career, career options, Education'
),
514 => array(
		'url' => 'cima-exam-coaching-classes-in-{location}',
		'keywords' => 'CIMA Exam Coaching Classes in {Location}, CIMA Coaching Classes, CIMA Exam Coaching Classes Courses, CIMA Exam in {Location}, universities, institutes, career, career options, Education'
),
515 => array(
		'url' => 'cpa-exam-coaching-classes-in-{location}',
		'keywords' => 'CPA Exam Coaching Classes in {Location}, CPA Coaching Classes, CPA Exam Coaching Classes Courses, CPA Exam in {Location}, universities, institutes, career, career options, Education'
),
516 => array(
		'url' => 'general-insurance-agents-license-renewal-course',
		'keywords' => 'General Insurance Agents License Renewal Course in {Location}, General Insurance Agents License Renewal Course, Diploma Courses, Diploma in Banking in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
517 => array(
		'url' => 'general-insurance-agents-pre-licensing-course',
		'keywords' => 'General Insurance Agents Pre-licensing Course in {Location}, General Insurance Agents Pre-licensing Course, Diploma Courses, Diploma in Banking in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
518 => array(
		'url' => 'pg-diploma-in-banking-in-{location}',
		'keywords' => 'PG Diploma in Banking in {Location}, PG Diploma in Banking, Diploma Courses, Diploma in Banking in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
519 => array(
		'url' => 'pg-diploma-in-banking-operations-in-{location}',
		'keywords' => 'PG Diploma in Banking Operations in {Location}, PG Diploma in Banking Operations, Diploma Courses, Diploma in Banking in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
520 => array(
		'url' => 'pg-diploma-in-finance-in-{location}',
		'keywords' => 'PG Diploma in Finance in {Location}, PG Diploma in Finance, Diploma Courses, Diploma in Banking in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
521 => array(
		'url' => 'pg-diploma-in-finance-planning-in-{location}',
		'keywords' => 'PG Diploma in Financial Planning in {Location}, PG Diploma in Financial Planning, Diploma Courses, Diploma in Banking in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
522 => array(
		'url' => 'pg-diploma-in-insurance-and-risk-management',
		'keywords' => 'PG Diploma in Insurance & Risk Management in {Location}, PG Diploma in Insurance & Risk Management, Diploma Courses, Diploma in Banking in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
523 => array(
		'url' => 'pg-diploma-in-securities-and-trading-in-{location}',
		'keywords' => 'PG Diploma in Securities & Trading in {Location}, PG Diploma in Securities & Trading, Diploma Courses, Diploma in Banking in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
524 => array(
		'url' => 'pg-diploma-in-wealth-management-in-{location}',
		'keywords' => 'PG Diploma in Wealth Management in {Location}, PG Diploma in Wealth Management, Diploma Courses, Diploma in Banking in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
1419 => array(
		'url' => 'certificate-in-certified-financial-planner-training-institutes-in-{location}',
		'keywords' => 'Certificate in Certified Financial Planner Training Institutes In {Location}, Certificate in Certified Financial Planner Certification Courses, Certificate in Certified Financial Planner Certification Courses, Certificate in Certified Financial Planner Certification Training, universities, institutes, career, career options, Education'
),
1420 => array(
		'url' => 'certificate-in-certified-industrial-accounting-training-institutes-in-{location}',
		'keywords' => 'Certificate in Certified Industrial Accounting Training Institutes In {Location}, Certificate in Certified Industrial Accounting Certification Courses, Certificate in Certified Industrial Accounting Certification Courses, Certificate in Certified Industrial Accounting Certification Training, universities, institutes, career, career options, Education'
),
542 => array(
		'url' => 'cs-foundation-exam-coaching-classes-in-{location}',
		'keywords' => 'CS - Foundation Exam Coaching Classes in {Location}, CS - Foundation Coaching Classes, CS - Foundation Exam Coaching Classes Courses, CS - Foundation Exam in {Location}, universities, institutes, career, career options, Education'
),
543 => array(
		'url' => 'cs-intermediate-exam-coaching-classes-in-{location}',
		'keywords' => 'CS - Intermediate Exam Coaching Classes in {Location}, CS - Intermediate Coaching Classes, CS - Intermediate Exam Coaching Classes Courses, CS - Intermediate Exam in {Location}, universities, institutes, career, career options, Education'
),
544 => array(
		'url' => 'cs-final-exam-coaching-classes-in-{location}',
		'keywords' => 'CS - Final Exam Coaching Classes in {Location}, CS - Final Coaching Classes, CS - Final Exam Coaching Classes Courses, CS - Final Exam in {Location}, universities, institutes, career, career options, Education'
),
545 => array(
		'url' => 'icwai-foundation-exam-coaching-classes-in-{location}',
		'keywords' => 'ICWAI - Foundation Exam Coaching Classes in {Location}, ICWAI - Foundation Coaching Classes, ICWAI - Foundation Exam Coaching Classes Courses, ICWAI - Foundation Exam in {Location}, universities, institutes, career, career options, Education'
),
546 => array(
		'url' => 'icwai-intermediate-exam-coaching-classes-in-{location}',
		'keywords' => 'ICWAI - Intermediate Exam Coaching Classes in {Location}, ICWAI - Intermediate Coaching Classes, ICWAI - Intermediate Exam Coaching Classes Courses, ICWAI - Intermediate Exam in {Location}, universities, institutes, career, career options, Education'
),
547 => array(
		'url' => 'icwai-final-exam-coaching-classes-in-{location}',
		'keywords' => 'ICWAI - Final Exam Coaching Classes in {Location}, ICWAI - Final Coaching Classes, ICWAI - Final Exam Coaching Classes Courses, ICWAI - Final Exam in {Location}, universities, institutes, career, career options, Education'
),
580 => array(
		'url' => 'me-mtech-in-aeronautical-engineering-in-{location}',
		'title' => 'M.Tech in Aeronautical/Aerospace Engineering Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Aeronautical/Aerospace Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Aeronautical/Aerospace Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Aeronautical/Aerospace Engineering in {Location}, ME in Aeronautical/Aerospace Engineering in {Location}, universities, institutes, career, career options, Education'
),
581 => array(
		'url' => 'me-mtech-in-agricultural-engineering-in-{location}',
		'title' => 'M.Tech in Agricultural Engineering Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Agricultural Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Agricultural Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Agricultural Engineering in {Location}, ME in Agricultural Engineering in {Location}, universities, institutes, career, career options, Education'
),
582 => array(
		'url' => 'me-mtech-in-civil-engineering-in-{location}',
		'title' => 'M.Tech in Civil Engineering Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Civil Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Civil Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Civil Engineering in {Location}, ME in Civil Engineering in {Location}, universities, institutes, career, career options, Education'
),
583 => array(
		'url' => 'me-mtech-in-electrical-engineering-in-{location}',
		'title' => 'M.Tech in Electrical Engineering Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Electrical Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Electrical Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Electrical Engineering in {Location}, ME in Electrical Engineering in {Location}, universities, institutes, career, career options, Education'
),
584 => array(
		'url' => 'me-mtech-in-industrial-engineering-in-{location}',
		'title' => 'M.Tech in Industrial Engineering Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Industrial Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Industrial Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Industrial Engineering in {Location}, ME in Industrial Engineering in {Location}, universities, institutes, career, career options, Education'
),
585 => array(
		'url' => 'me-mtech-in-information-technology-it-engineering-in-{location}',
		'title' => 'M.Tech in Information Technology (IT) Engineering Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Information Technology (IT) Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Information Technology (IT) Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Information Technology (IT) Engineering in {Location}, ME in Information Technology (IT) Engineering in {Location}, universities, institutes, career, career options, Education'
),
586 => array(
		'url' => 'me-mtech-in-marine-engineering-in-{location}',
		'title' => 'M.Tech in Marine Engineering Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Marine Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Marine Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Marine Engineering in {Location}, ME in Marine Engineering in {Location}, universities, institutes, career, career options, Education'
),
587 => array(
		'url' => 'me-mtech-in-mechanical-engineering-in-{location}',
		'title' => 'M.Tech in Mechanical Engineering Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Mechanical Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Mechanical Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Mechanical Engineering in {Location}, ME in Mechanical Engineering in {Location}, universities, institutes, career, career options, Education'
),
588 => array(
		'url' => 'me-mtech-in-metallurgical-engineering-in-{location}',
		'title' => 'M.Tech in Metallurgical Engineering Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Metallurgical Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Metallurgical Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Metallurgical Engineering in {Location}, ME in Metallurgical Engineering in {Location}, universities, institutes, career, career options, Education'
),
589 => array(
		'url' => 'me-mtech-in-mining-engineering-in-{location}',
		'title' => 'M.Tech in Mining Engineering Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Mining Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Mining Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Mining Engineering in {Location}, ME in Mining Engineering in {Location}, universities, institutes, career, career options, Education'
),
590 => array(
		'url' => 'me-mtech-in-textile-engineering-in-{location}',
		'title' => 'M.Tech in Textile Engineering Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Textile Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Textile Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Textile Engineering in {Location}, ME in Textile Engineering in {Location}, universities, institutes, career, career options, Education'
),
591 => array(
		'url' => 'me-mtech-in-bio-technology-engineering-in-{location}',
		'title' => 'M.Tech in Bio-Technology Engineering Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} M.Tech in Bio-Technology Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'ME Mtech in Bio-Technology Engineering in {Location}, ME Colleges in {location}, M.Tech Colleges in {Location}, Engineering Colleges, Mtech in Bio-Technology Engineering in {Location}, ME in Bio-Technology Engineering in {Location}, universities, institutes, career, career options, Education'
),
592 => array(
		'url' => 'bio-technology-engineering-colleges-in-{location}',
		'title' => 'Bio-Technology Engineering Colleges in {Location} - Bio-Technology Engineering Courses - Shiksha.com',
		'description' => 'Search Bio-Technology  Engineering Colleges in {Location} - Get a list of all Bio-Technology  Engineering courses, Colleges and institutes. Know more about the full and part time programs.',
		'keywords' => 'Bio-Technology Engineering Colleges in {Location},  Bio-Technology Engineering Courses, Bio-Technology Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
593 => array(
		'url' => 'diploma-in-aeronautical-engineering-in-{location}',
		'title' => 'Diploma in Aeronautical/Aerospace Engineering Colleges in {location} | Shiksha.com',
		'description' => 'View {resultCount} Diploma in Aeronautical/Aerospace Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Diploma in Aeronautical/Aerospace Engineering in {Location}, Diploma Courses in Aeronautical/Aerospace Engineering, Diploma Courses, Engineering in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
594 => array(
		'url' => 'diploma-in-agricultural-engineering-in-{location}',
		'title' => 'Diploma in Agricultural Engineering Colleges in {location} | Shiksha.com',
		'description' => 'View {resultCount} Diploma in Agricultural Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Diploma in Agricultural Engineering in {Location}, Diploma Courses in Agricultural Engineering, Diploma Courses, Engineering in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
595 => array(
		'url' => 'diploma-in-automobile-engineering-in-{location}',
		'title' => 'Diploma in Automobile Engineering Colleges in {location} | Shiksha.com',
		'description' => 'View {resultCount} Diploma in Automobile Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Diploma in Automobile Engineering in {Location}, Diploma Courses in Automobile Engineering, Diploma Courses, Engineering in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
596 => array(
		'url' => 'diploma-in-chemical-engineering-in-{location}',
		'title' => 'Diploma in Chemical Engineering Colleges in {location} | Shiksha.com',
		'description' => 'View {resultCount} Diploma in Chemical Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Diploma in Chemical Engineering in {Location}, Diploma Courses in Chemical Engineering, Diploma Courses, Engineering in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
597 => array(
		'url' => 'diploma-in-civil-engineering-in-{location}',
		'title' => 'Diploma in Civil Engineering Colleges in {location} | Shiksha.com',
		'description' => 'View {resultCount} Diploma in Civil Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Diploma in Civil Engineering in {Location}, Diploma Courses in Civil Engineering, Diploma Courses, Engineering in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
598 => array(
		'url' => 'diploma-in-computer-science-engineering-in-{location}',
		'title' => 'Diploma in Computer Science Engineering Colleges in {location} | Shiksha.com',
		'description' => 'View {resultCount} Diploma in Computer Science Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Diploma in Computer Science Engineering in {Location}, Diploma Courses in Computer Science Engineering, Diploma Courses, Engineering in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
599 => array(
		'url' => 'diploma-in-electrical-engineering-in-{location}',
		'title' => 'Diploma in Electrical Engineering Colleges in {location} | Shiksha.com',
		'description' => 'View {resultCount} Diploma in Electrical Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Diploma in Electrical Engineering in {Location}, Diploma Courses in Electrical Engineering, Diploma Courses, Engineering in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
600 => array(
		'url' => 'diploma-in-electronics-and-communication-engineering-in-{location}',
		'title' => 'Diploma in Electronics & Communications Engineering Colleges in {location} | Shiksha.com',
		'description' => 'View {resultCount} Diploma in Electronics & Communications Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Diploma in Electronics & Communications Engineering in {Location}, Diploma Courses in Electronics & Communications Engineering, Diploma Courses, Engineering in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
601 => array(
		'url' => 'diploma-in-industrial-engineering-in-{location}',
		'title' => 'Diploma in Industrial Engineering Colleges in {location} | Shiksha.com',
		'description' => 'View {resultCount} Diploma in Industrial Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Diploma in Industrial Engineering in {Location}, Diploma Courses in Industrial Engineering, Diploma Courses, Engineering in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
602 => array(
		'url' => 'diploma-in-information-technology-it-engineering-in-{location}',
		'title' => 'Diploma in Information Technology (IT) Engineering Colleges in {location} | Shiksha.com',
		'description' => 'View {resultCount} Diploma in Information Technology (IT) Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Diploma in Information Technology (IT) Engineering in {Location}, Diploma Courses in Information Technology (IT) Engineering, Diploma Courses, Engineering in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
603 => array(
		'url' => 'diploma-in-marine-engineering-in-{location}',
		'title' => 'Diploma in Marine Engineering Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Diploma in Marine Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Diploma in Marine Engineering in {Location}, Diploma Courses in Marine Engineering, Diploma Courses, Engineering in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
604 => array(
		'url' => 'diploma-in-mechanical-engineering-in-{location}',
		'title' => 'Diploma in Mechanical Engineering Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Diploma in Mechanical Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Diploma in Mechanical Engineering in {Location}, Diploma Courses in Mechanical Engineering, Diploma Courses, Engineering in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
605 => array(
		'url' => 'diploma-in-metallurgical-engineering-in-{location}',
		'title' => 'Diploma in Metallurgical Engineering Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Diploma in Metallurgical Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Diploma in Metallurgical Engineering in {Location}, Diploma Courses in Metallurgical Engineering, Diploma Courses, Engineering in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
606 => array(
		'url' => 'diploma-in-mining-engineering-in-{location}',
		'title' => 'Diploma in Mining Engineering Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Diploma in Mining Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Diploma in Mining Engineering in {Location}, Diploma Courses in Mining Engineering, Diploma Courses, Engineering in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
607 => array(
		'url' => 'diploma-in-nanotechnology-in-{location}',
		'title' => 'Diploma in Nanotechnology Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Diploma in Nanotechnology Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Diploma in Nanotechnology in {Location}, Diploma Courses in Nanotechnology, Diploma Courses, Engineering in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
608 => array(
		'url' => 'diploma-in-textile-engineering-in-{location}',
		'title' => 'Diploma in Textile Engineering Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Diploma in Textile Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Diploma in Textile Engineering in {Location}, Diploma Courses in Textile Engineering, Diploma Courses, Engineering in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
609 => array(
		'url' => 'diploma-in-bio-technology-in-{location}',
		'title' => 'Diploma in Biotechnology Engineering Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Diploma in Biotechnology Engineering colleges in {location} with their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Diploma in Biotechnology in {Location}, Diploma Courses in Biotechnology, Diploma Courses, Engineering in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
610 => array(
		'url' => 'distance-diploma-in-aeronautical-engineering-in-{location}',
		'keywords' => 'Distance Diploma in Aeronautical/Aerospace Engineering in {Location}, Distance Diploma Courses in Aeronautical/Aerospace Engineering, Distance Diploma Courses, Engineering in {Location}, Distance Diploma in {Location}, universities, institutes, career, career options, Education'
),
611 => array(
		'url' => 'distance-diploma-in-agricultural-engineering-in-{location}',
		'keywords' => 'Distance Diploma in Agricultural Engineering in {Location}, Distance Diploma Courses in Agricultural Engineering, Distance Diploma Courses, Engineering in {Location}, Distance Diploma in {Location}, universities, institutes, career, career options, Education'
),
612 => array(
		'url' => 'distance-diploma-in-automobile-engineering-in-{location}',
		'keywords' => 'Distance Diploma in Automobile Engineering in {Location}, Distance Diploma Courses in Automobile Engineering, Distance Diploma Courses, Engineering in {Location}, Distance Diploma in {Location}, universities, institutes, career, career options, Education'
),
613 => array(
		'url' => 'distance-diploma-in-chemical-engineering-in-{location}',
		'keywords' => 'Distance Diploma in Chemical Engineering in {Location}, Distance Diploma Courses in Chemical Engineering, Distance Diploma Courses, Engineering in {Location}, Distance Diploma in {Location}, universities, institutes, career, career options, Education'
),
614 => array(
		'url' => 'distance-diploma-in-civil-engineering-in-{location}',
		'keywords' => 'Distance Diploma in Civil Engineering in {Location}, Distance Diploma Courses in Civil Engineering, Distance Diploma Courses, Engineering in {Location}, Distance Diploma in {Location}, universities, institutes, career, career options, Education'
),
615 => array(
		'url' => 'distance-diploma-in-computer-science-engineering-in-{location}',
		'keywords' => 'Distance Diploma in Computer Science Engineering in {Location}, Distance Diploma Courses in Computer Science Engineering, Distance Diploma Courses, Engineering in {Location}, Distance Diploma in {Location}, universities, institutes, career, career options, Education'
),
616 => array(
		'url' => 'distance-diploma-in-electrical-engineering-in-{location}',
		'keywords' => 'Distance Diploma in Electrical Engineering in {Location}, Distance Diploma Courses in Electrical Engineering, Distance Diploma Courses, Engineering in {Location}, Distance Diploma in {Location}, universities, institutes, career, career options, Education'
),
617 => array(
		'url' => 'distance-diploma-in-electronics-and-communication-engineering-in-{location}',
		'keywords' => 'Distance Diploma in Electronics & Communications Engineering in {Location}, Distance Diploma Courses in Electronics & Communications Engineering, Distance Diploma Courses, Engineering in {Location}, Distance Diploma in {Location}, universities, institutes, career, career options, Education'
),
618 => array(
		'url' => 'distance-diploma-in-industrial-engineering-in-{location}',
		'keywords' => 'Distance Diploma in Industrial Engineering in {Location}, Distance Diploma Courses in Industrial Engineering, Distance Diploma Courses, Engineering in {Location}, Distance Diploma in {Location}, universities, institutes, career, career options, Education'
),
619 => array(
		'url' => 'distance-diploma-in-information-technology-it-engineering-in-{location}',
		'keywords' => 'Distance Diploma in Information Technology (IT) Engineering in {Location}, Distance Diploma Courses in Information Technology (IT) Engineering, Distance Diploma Courses, Engineering in {Location}, Distance Diploma in {Location}, universities, institutes, career, career options, Education'
),
620 => array(
		'url' => 'distance-diploma-in-marine-engineering-in-{location}',
		'keywords' => 'Distance Diploma in Marine Engineering in {Location}, Distance Diploma Courses in Marine Engineering, Distance Diploma Courses, Engineering in {Location}, Distance Diploma in {Location}, universities, institutes, career, career options, Education'
),
621 => array(
		'url' => 'distance-diploma-in-mechanical-engineering-in-{location}',
		'keywords' => 'Distance Diploma in Mechanical Engineering in {Location}, Distance Diploma Courses in Mechanical Engineering, Distance Diploma Courses, Engineering in {Location}, Distance Diploma in {Location}, universities, institutes, career, career options, Education'
),
622 => array(
		'url' => 'distance-diploma-in-metallurgical-engineering-in-{location}',
		'keywords' => 'Distance Diploma in Metallurgical Engineering in {Location}, Distance Diploma Courses in Metallurgical Engineering, Distance Diploma Courses, Engineering in {Location}, Distance Diploma in {Location}, universities, institutes, career, career options, Education'
),
623 => array(
		'url' => 'distance-diploma-in-mining-engineering-in-{location}',
		'keywords' => 'Distance Diploma in Mining Engineering in {Location}, Distance Diploma Courses in Mining Engineering, Distance Diploma Courses, Engineering in {Location}, Distance Diploma in {Location}, universities, institutes, career, career options, Education'
),
624 => array(
		'url' => 'distance-diploma-in-nanotechnology-in-{location}',
		'keywords' => 'Distance Diploma in Nanotechnology Engineering in {Location}, Distance Diploma Courses in Nanotechnology Engineering, Distance Diploma Courses, Engineering in {Location}, Distance Diploma in {Location}, universities, institutes, career, career options, Education'
),
625 => array(
		'url' => 'distance-diploma-in-textile-engineering-in-{location}',
		'keywords' => 'Distance Diploma in Textile Engineering in {Location}, Distance Diploma Courses in Textile Engineering, Distance Diploma Courses, Engineering in {Location}, Distance Diploma in {Location}, universities, institutes, career, career options, Education'
),
626 => array(
		'url' => 'distance-diploma-in-bio-technology-in-{location}',
		'keywords' => 'Distance Diploma in Biotechnology Engineering in {Location}, Distance Diploma Courses in Biotechnology Engineering, Distance Diploma Courses, Engineering in {Location}, Distance Diploma in {Location}, universities, institutes, career, career options, Education'
),
627 => array(
		'url' => 'distance-learning-aeronautical-engineering-in-{location}',
		'keywords' => 'B.Tech Distance learning Aeronautical/Aerospace Engineering in {location}, Distance Learning Aeronautical/Aerospace Engineering, Aeronautical/Aerospace Engineering in {location}, Btech Distance Learning, universities, institutes, career, career options, Education'
),
628 => array(
		'url' => 'distance-learning-agricultural-engineering-in-{location}',
		'keywords' => 'B.Tech Distance learning Agricultural Engineering in {location}, Distance Learning Agricultural Engineering, Agricultural Engineering in {location}, Btech Distance Learning, universities, institutes, career, career options, Education'
),
629 => array(
		'url' => 'distance-learning-automobile-engineering-in-{location}',
		'keywords' => 'B.Tech Distance learning Automobile Engineering in {location}, Distance Learning Automobile Engineering, Automobile Engineering in {location}, Btech Distance Learning, universities, institutes, career, career options, Education'
),
630 => array(
		'url' => 'distance-learning-chemical-engineering-in-{location}',
		'keywords' => 'B.Tech Distance learning Chemical Engineering in {location}, Distance Learning Chemical Engineering, Chemical Engineering in {location}, Btech Distance Learning, universities, institutes, career, career options, Education'
),
631 => array(
		'url' => 'distance-learning-civil-engineering-in-{location}',
		'keywords' => 'B.Tech Distance learning Civil Engineering in {location}, Distance Learning Civil Engineering, Civil Engineering in {location}, Btech Distance Learning, universities, institutes, career, career options, Education'
),
632 => array(
		'url' => 'distance-learning-computer science-engineering-in-{location}',
		'keywords' => 'B.Tech Distance learning Computer Science Engineering in {location}, Distance Learning Computer Science Engineering, Computer Science Engineering in {location}, Btech Distance Learning, universities, institutes, career, career options, Education'
),
633 => array(
		'url' => 'distance-learning-electrical-engineering-in-{location}',
		'keywords' => 'B.Tech Distance learning Electrical Engineering in {location}, Distance Learning Electrical Engineering, Electrical Engineering in {location}, Btech Distance Learning, universities, institutes, career, career options, Education'
),
634 => array(
		'url' => 'distance-learning-electronics-communications-engineering-in-{location}',
		'keywords' => 'B.Tech Distance learning Electronics & Communications Engineering in {location}, Distance Learning Electronics & Communications Engineering, Electronics & Communications Engineering in {location}, Btech Distance Learning, universities, institutes, career, career options, Education'
),
635 => array(
		'url' => 'distance-learning-industrial-engineering-in-{location}',
		'keywords' => 'B.Tech Distance learning Industrial Engineering in {location}, Distance Learning Industrial Engineering, Industrial Engineering in {location}, Btech Distance Learning, universities, institutes, career, career options, Education'
),
636 => array(
		'url' => 'distance-learning-information technology-it-engineering-in-{location}',
		'keywords' => 'B.Tech Distance learning Information Technology (IT) engineering in {location}, Distance Learning Information Technology (IT) engineering, Information Technology (IT) engineering in {location}, Btech Distance Learning, universities, institutes, career, career options, Education'
),
637 => array(
		'url' => 'distance-learning-marine-engineering-in-{location}',
		'keywords' => 'B.Tech Distance learning Marine Engineering in {location}, Distance Learning Marine Engineering, Marine Engineering in {location}, Btech Distance Learning, universities, institutes, career, career options, Education'
),
638 => array(
		'url' => 'distance-learning-mechanical-engineering-in-{location}',
		'keywords' => 'B.Tech Distance learning Mechanical Engineering in {location}, Distance Learning Mechanical Engineering, Mechanical Engineering in {location}, Btech Distance Learning, universities, institutes, career, career options, Education'
),
639 => array(
		'url' => 'distance-learning-metallurgical-engineering-in-{location}',
		'keywords' => 'B.Tech Distance learning Metallurgical Engineering in {location}, Distance Learning Metallurgical Engineering, Metallurgical Engineering in {location}, Btech Distance Learning, universities, institutes, career, career options, Education'
),
640 => array(
		'url' => 'distance-learning-mining-engineering-in-{location}',
		'keywords' => 'B.Tech Distance learning Mining Engineering in {location}, Distance Learning Mining Engineering, Mining Engineering in {location}, Btech Distance Learning, universities, institutes, career, career options, Education'
),
641 => array(
		'url' => 'distance-learning-nanotechnology-engineering-in-{location}',
		'keywords' => 'B.Tech Distance learning Nanotechnology engineering in {location}, Distance Learning Nanotechnology engineering, Nanotechnology engineering in {location}, Btech Distance Learning, universities, institutes, career, career options, Education'
),
642 => array(
		'url' => 'distance-learning-textile-engineering-in-{location}',
		'keywords' => 'B.Tech Distance learning Textile Engineering in {location}, Distance Learning Textile Engineering, Textile Engineering in {location}, Btech Distance Learning, universities, institutes, career, career options, Education'
),
643 => array(
		'url' => 'distance-learning-bio-technology-engineering-in-{location}',
		'keywords' => 'B.Tech Distance learning Bio-Technology engineering in {location}, Distance Learning Bio-Technology engineering, Bio-Technology engineering in {location}, Btech Distance Learning, universities, institutes, career, career options, Education'
),
645 => array(
		'url' => 'bsc-physics-in-{location}',
		'keywords' => 'BSc Physics  Colleges in {Location}, BSc Physics  Courses in {Location}, BSc Colleges, BSc Courses, universities, institutes, career, career options, Education'
),
646 => array(
		'url' => 'bsc-chemistry-in-{location}',
		'keywords' => 'BSc Chemistry  Colleges in {Location}, BSc Chemistry  Courses in {Location}, BSc Colleges, BSc Courses, universities, institutes, career, career options, Education'
),
647 => array(
		'url' => 'bsc-maths-in-{location}',
		'keywords' => 'BSc Maths  Colleges in {Location}, BSc Maths  Courses in {Location}, BSc Colleges, BSc Courses, universities, institutes, career, career options, Education'
),
648 => array(
		'url' => 'bsc-biology-in-{location}',
		'keywords' => 'BSc Biology  Colleges in {Location}, BSc Biology  Courses in {Location}, BSc Colleges, BSc Courses, universities, institutes, career, career options, Education'
),
649 => array(
		'url' => 'bsc-agriculture-forestry-in-{location}',
		'keywords' => 'BSc Agriculture & Forestry  Colleges in {Location}, BSc Agriculture & Forestry  Courses in {Location}, BSc Colleges, BSc Courses, universities, institutes, career, career options, Education'
),
650 => array(
		'url' => 'msc-physics-in-{location}',
		'keywords' => 'MSc Physics  Colleges in {Location}, MSc Physics  Courses in {Location}, MSc Colleges, MSc Courses, universities, institutes, career, career options, Education'
),
651 => array(
		'url' => 'msc-chemistry-in-{location}',
		'keywords' => 'MSc Chemistry  Colleges in {Location}, MSc Chemistry  Courses in {Location}, MSc Colleges, MSc Courses, universities, institutes, career, career options, Education'
),
652 => array(
		'url' => 'msc-maths-in-{location}',
		'keywords' => 'MSc Maths  Colleges in {Location}, MSc Maths  Courses in {Location}, MSc Colleges, MSc Courses, universities, institutes, career, career options, Education'
),
653 => array(
		'url' => 'msc-biology-in-{location}',
		'keywords' => 'MSc Biology  Colleges in {Location}, MSc Biology  Courses in {Location}, MSc Colleges, MSc Courses, universities, institutes, career, career options, Education'
),
654 => array(
		'url' => 'msc-agriculture-forestry-in-{location}',
		'keywords' => 'MSc Agriculture & Forestry  Colleges in {Location}, MSc Agriculture & Forestry  Courses in {Location}, MSc Colleges, MSc Courses, universities, institutes, career, career options, Education'
),
655 => array(
		'url' => 'bsc-physics-distance-learning-in-{location}',
		'keywords' => 'BSc Physics Distance Learning in {Location}, BSc Physics Distance Learning Courses in {Location}, BSc Physics Distance Learning, BSc Distance Learning Courses, universities, institutes, career, career options, Education'
),
656 => array(
		'url' => 'bsc-chemistry-distance-learning-in-{location}',
		'keywords' => 'BSc Chemistry Distance Learning in {Location}, BSc Chemistry Distance Learning Courses in {Location}, BSc Chemistry Distance Learning, BSc Distance Learning Courses, universities, institutes, career, career options, Education'
),
657 => array(
		'url' => 'bsc-mathematics-distance-learning-in-{location}',
		'keywords' => 'BSc Maths Distance Learning in {Location}, BSc Maths Distance Learning Courses in {Location}, BSc Maths Distance Learning, BSc Distance Learning Courses, universities, institutes, career, career options, Education'
),
658 => array(
		'url' => 'bsc-biology-distance-learning-in-{location}',
		'keywords' => 'BSc Biology Distance Learning in {Location}, BSc Biology Distance Learning Courses in {Location}, BSc Biology Distance Learning, BSc Distance Learning Courses, universities, institutes, career, career options, Education'
),
659 => array(
		'url' => 'bsc-agriculture-forestry-distance-learning-in-{location}',
		'keywords' => 'BSc Agriculture & Forestry Distance Learning in {Location}, BSc Agriculture & Forestry Distance Learning Courses in {Location}, BSc Agriculture & Forestry Distance Learning, BSc Distance Learning Courses, universities, institutes, career, career options, Education'
),
660 => array(
		'url' => 'msc-physics-distance-learning-in-{location}',
		'keywords' => 'MSc Physics Distance Learning in {Location}, MSc Physics Distance Learning Courses in {Location}, MSc Physics Distance Learning, BSc Distance Learning Courses, universities, institutes, career, career options, Education'
),
661 => array(
		'url' => 'msc-chemistry-distance-learning-in-{location}',
		'keywords' => 'MSc Chemistry Distance Learning in {Location}, MSc Chemistry Distance Learning Courses in {Location}, MSc Chemistry Distance Learning, BSc Distance Learning Courses, universities, institutes, career, career options, Education'
),
662 => array(
		'url' => 'msc-mathematics-distance-learning-in-{location}',
		'keywords' => 'MSc Maths Distance Learning in {Location}, MSc Maths Distance Learning Courses in {Location}, MSc Maths Distance Learning, BSc Distance Learning Courses, universities, institutes, career, career options, Education'
),
663 => array(
		'url' => 'msc-biology-distance-learning-in-{location}',
		'keywords' => 'MSc Biology Distance Learning in {Location}, MSc Biology Distance Learning Courses in {Location}, MSc Biology Distance Learning, BSc Distance Learning Courses, universities, institutes, career, career options, Education'
),
664 => array(
		'url' => 'msc-agriculture-forestry-distance-learning-in-{location}',
		'keywords' => 'MSc Agriculture & Forestry Distance Learning in {Location}, MSc Agriculture & Forestry Distance Learning Courses in {Location}, MSc Agriculture & Forestry Distance Learning, BSc Distance Learning Courses, universities, institutes, career, career options, Education'
),
665 => array(
		'url' => 'phd-in-aerospace-aeronautical-in-{location}',
		'keywords' => 'Phd in Aeronautical/Aerospace Engineering in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
666 => array(
		'url' => 'phd-in-agricultural-in-{location}',
		'keywords' => 'Phd in Agricultural Engineering in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
667 => array(
		'url' => 'phd-in-automobile-in-{location}',
		'keywords' => 'Phd in Automobile Engineering in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
668 => array(
		'url' => 'phd-in-avionics-in-{location}',
		'keywords' => 'Phd in Avionics Engineering in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
669 => array(
		'url' => 'phd-in-chemical-in-{location}',
		'keywords' => 'Phd in Chemical Engineering in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
670 => array(
		'url' => 'phd-in-civil-in-{location}',
		'keywords' => 'Phd in Civil Engineering in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
671 => array(
		'url' => 'phd-in-communications-in-{location}',
		'keywords' => 'Phd in Communications Engineering in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
672 => array(
		'url' => 'phd-in-computer-science-in-{location}',
		'keywords' => 'Phd in Computer Science Engineering in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
673 => array(
		'url' => 'phd-in-control-systems-in-{location}',
		'keywords' => 'Phd in Control Systems Engineering in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
674 => array(
		'url' => 'phd-in-electrical-in-{location}',
		'keywords' => 'Phd in Electrical Engineering in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
676 => array(
		'url' => 'phd-in-industrial-in-{location}',
		'keywords' => 'Phd in Industrial Engineering in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
677 => array(
		'url' => 'phd-in-it-information-technology-in-{location}',
		'keywords' => 'Phd in Information Technology (IT) in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
678 => array(
		'url' => 'phd-in-instrumentation-in-{location}',
		'keywords' => 'Phd in Instrumentation Engineering in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
679 => array(
		'url' => 'phd-in-marine-in-{location}',
		'keywords' => 'Phd in Marine Engineering in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
680 => array(
		'url' => 'phd-in-mechanical-in-{location}',
		'keywords' => 'Phd in Mechanical Engineering in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
681 => array(
		'url' => 'phd-in-mechatronics-in-{location}',
		'keywords' => 'Phd in Mechatronics Engineering in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
682 => array(
		'url' => 'phd-in-metallurgical-in-{location}',
		'keywords' => 'Phd in Metallurgical Engineering in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
683 => array(
		'url' => 'phd-in-mining-in-{location}',
		'keywords' => 'Phd in Mining Engineering in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
684 => array(
		'url' => 'phd-in-nanotechnology-in-{location}',
		'keywords' => 'Phd in Nanotechnology in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
685 => array(
		'url' => 'phd-in-power systems-in-{location}',
		'keywords' => 'Phd in Power Systems Engineering in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
686 => array(
		'url' => 'phd-in-signal processing-in-{location}',
		'keywords' => 'Phd in Signal Processing in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
687 => array(
		'url' => 'phd-in-solar-and-alternative-in-{location}',
		'keywords' => 'Phd in Solar & Alternative Engineering in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
688 => array(
		'url' => 'phd-in-telecommunications-in-{location}',
		'keywords' => 'Phd in Telecommunications Engineering in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
689 => array(
		'url' => 'phd-in-textile-in-{location}',
		'keywords' => 'Phd in Textile Engineering in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
690 => array(
		'url' => 'phd-in-vlsi-in-{location}',
		'keywords' => 'Phd in VLSI in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
691 => array(
		'url' => 'phd-in-wireless-communication-in-{location}',
		'keywords' => 'Phd in Wireless Communication in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
692 => array(
		'url' => 'phd-in-physics-in-{location}',
		'keywords' => 'Phd in Physics in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
693 => array(
		'url' => 'phd-in-chemistry-in-{location}',
		'keywords' => 'Phd in Chemistry in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
694 => array(
		'url' => 'phd-in-maths-in-{location}',
		'keywords' => 'Phd in Maths in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
695 => array(
		'url' => 'phd-in-biology-in-{location}',
		'keywords' => 'Phd in Biology in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
696 => array(
		'url' => 'phd-in-agriculture-and-forestry-in-{location}',
		'keywords' => 'Phd in Agriculture & Forestry in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
697 => array(
		'url' => 'phd-in-bio-technology-in-{location}',
		'keywords' => 'Phd in Bio-Technology in {Location}, Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),
699 => array(
		'url' => 'mba-in-aviation-in-{location}',
		'keywords' => 'mba in aviation courses in {location}, mba in aviation colleges in {location}, mba in aviation institutes in {location}, mba in aviation courses, mba in aviation colleges, mba in aviation institutes, mba in aviation schools'
),
700 => array(
		'url' => 'ecsp-certification-training-institutes-in-{location}',
		'keywords' => 'ECSP - EC Council Certified Secure Programmer Certification Training Institutes In {Location}, ECSP - EC Council Certified Secure Programmer Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
701 => array(
		'url' => 'ecsa-certification-training-institutes-in-{location}',
		'keywords' => 'ECSA - EC Council Certified Security Analyst Certification Training Institutes In {Location}, ECSA - EC Council Certified Security Analyst Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
702 => array(
		'url' => 'ecnsa-certification-training-institutes-in-{location}',
		'keywords' => 'ECNSA - EC Council Network Security Administrator Certification Training Institutes In {Location}, ECNSA - EC Council Network Security Administrator Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
703 => array(
		'url' => 'android-application-development-courses-in-{location}',
		'keywords' => 'Android Application Development Courses in {Location}, Mobile Application Development Courses, Certification Training, universities, institutes, career, career options, Education'
),
704 => array(
		'url' => 'iphone-application-development-courses-in-{location}',
		'keywords' => 'iPhone Application Development Courses in {Location}, Mobile Application Development Courses, Certification Training, universities, institutes, career, career options, Education'
),
705 => array(
		'url' => 'blackberry-application-development-courses-in-{location}',
		'keywords' => 'Blackberry Application Development Courses in {Location}, Mobile Application Development Courses, Certification Training, universities, institutes, career, career options, Education'
),
706 => array(
		'url' => 'symbian-application-development-courses-in-{location}',
		'keywords' => 'Symbian Application Development Courses in {Location}, Mobile Application Development Courses, Certification Training, universities, institutes, career, career options, Education'
),
707 => array(
		'url' => 'pl-sql-certification-training-institutes-in-{location}',
		'keywords' => 'PL SQL Certification Training Institutes In {Location}, PL SQL Certification Courses, Certification Courses,  Certification Training, universities, institutes, career, career options, Education'
),
708 => array(
		'url' => 'asp-net-certification-training-institutes-in-{location}',
		'keywords' => 'ASP.NET Certification Training Institutes In {Location}, ASP.NET Certification Courses,Certification Courses, Certification Training, universities, institutes, career, career options, Education'
),
713 => array(
		'url' => 'mba-in-biotechnology-in-{Location}',
		'keywords' => 'MBA in BioTechnology in {Location}, {Location}MBA in BioTechnology Colleges, List of MBA in BioTechnology in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
714 => array(
		'url' => 'mba-in-telecom-mangement-in-{Location}',
		'keywords' => 'MBA in Telecom Management in {Location}, {Location}MBA in Telecom Management Colleges, List of MBA in Telecom Management in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
715 => array(
		'url' => 'mba-in-infrastructure-management-in-{Location}',
		'keywords' => 'MBA in Infrastructure & Development in {Location}, {Location}MBA in Infrastructure & Development Colleges, List of MBA in Infrastructure & Development in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
716 => array(
		'url' => 'mba-in-hospitality-management-in-{Location}',
		'keywords' => 'MBA in Hospitality Management in {Location}, {Location}MBA in Hospitality Management Colleges, List of MBA in Hospitality Management in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
717 => array(
		'url' => 'mba-in-retail-in-{Location}',
		'keywords' => 'MBA in Retail in {Location}, {Location}MBA in Retail Colleges, List of MBA in Retail in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
718 => array(
		'url' => 'mba-in-accounting-in-{Location}',
		'keywords' => 'MBA in Accounting in {Location}, {Location}MBA in Accounting Colleges, List of MBA in Accounting in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
719 => array(
		'url' => 'mba-in-aviation-in-{Location}',
		'keywords' => 'MBA in Aviation in {Location}, {Location}MBA in Aviation Colleges, List of MBA in Aviation in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
720 => array(
		'url' => 'executive-mba-in-biotechnology-in-{Location}',
		'title' => 'Executive MBA in BioTechnology Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Executive MBA in BioTechnology colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Executive MBA in BioTechnology in {Location}, Executive MBA in BioTechnology courses, List of Executive MBA in BioTechnology in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
721 => array(
		'url' => 'executive-mba-in-telecom-mangement-in-{Location}',
		'title' => 'Executive MBA in Telecom Management Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Executive MBA in Telecom Management colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Executive MBA in Telecom Management in {Location}, Executive MBA in Telecom Management courses, List of Executive MBA in Telecom Management in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
722 => array(
		'url' => 'executive-mba-in-infrastructure-management-in-{Location}',
		'title' => 'Executive MBA in Infrastructure & Development Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Executive MBA in Infrastructure & Development colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Executive MBA in Infrastructure & Development in {Location}, Executive MBA in Infrastructure & Development courses, List of Executive MBA in Infrastructure & Development in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
723 => array(
		'url' => 'executive-mba-in-hospitality-management-in-{Location}',
		'title' => 'Executive MBA in Hospitality Management Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Executive MBA in Hospitality Management colleges  in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Executive MBA in Hospitality Management in {Location}, Executive MBA in Hospitality Management courses, List of Executive MBA in Hospitality Management in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
724 => array(
		'url' => 'executive-mba-in-retail-in-{Location}',
		'title' => 'Executive MBA in Retail Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Executive MBA in Retail colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Executive MBA in Retail in {Location}, Executive MBA in Retail courses, List of Executive MBA in Retail in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
725 => array(
		'url' => 'executive-mba-in-accounting-in-{Location}',
		'title' => 'Executive MBA in Accounting Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Executive MBA in Accounting colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Executive MBA in Accounting in {Location}, Executive MBA in Accounting courses, List of Executive MBA in Accounting in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
726 => array(
		'url' => 'executive-mba-in-aviation-in-{Location}',
		'title' => 'Executive MBA in Aviation Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Executive MBA in Aviation colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Executive MBA in Aviation in {Location}, Executive MBA in Aviation courses, List of Executive MBA in Aviation in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
728 => array(
		'url' => 'mba-biotechnology-distance-learning-in-{Location}',
		'title' => 'Distance/Correspondence MBA in BioTechnology Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Distance/Correspondence MBA in BioTechnology colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'MBA BioTechnology Distance Learning in {Location}, MBA BioTechnology Distance Learning, List of MBA BioTechnology Distance Learning in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
729 => array(
		'url' => 'mba-telecom-mangement-distance-learning-in-{Location}',
		'title' => 'Distance/Correspondence MBA in Telecom Management Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Distance/Correspondence MBA in Telecom Management colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'MBA Telecom Management Distance Learning in {Location}, MBA Telecom Management Distance Learning, List of MBA Telecom Management Distance Learning in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
730 => array(
		'url' => 'mba-infrastructure-management-distance-learning-in-{Location}',
		'title' => 'Distance/Correspondence MBA in Infrastructure & Development Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Distance/Correspondence MBA in Infrastructure & Development colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'MBA Infrastructure & Development Distance Learning in {Location}, MBA Infrastructure & Development Distance Learning, List of MBA Infrastructure & Development Distance Learning in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
731 => array(
		'url' => 'mba-hospitality-management-distance-learning-in-{Location}',
		'title' => 'Distance/Correspondence MBA in Hospitality Management Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Distance/Correspondence MBA in Hospitality Management colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'MBA Hospitality Management Distance Learning in {Location}, MBA Hospitality Management Distance Learning, List of MBA Hospitality Management Distance Learning in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
732 => array(
		'url' => 'mba-retail-distance-learning-in-{Location}',
		'title' => 'Distance/Correspondence MBA in Retail Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Distance/Correspondence MBA in Retail colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'MBA Retail Distance Learning in {Location}, MBA Retail Distance Learning, List of MBA Retail Distance Learning in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
733 => array(
		'url' => 'mba-accounting-distance-learning-in-{Location}',
		'title' => 'Distance/Correspondence MBA in Accounting Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Distance/Correspondence MBA in Accounting colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'MBA Accounting Distance Learning in {Location}, MBA Accounting Distance Learning, List of MBA Accounting Distance Learning in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
734 => array(
		'url' => 'mba-aviation-distance-learning-in-{Location}',
		'title' => 'Distance/Correspondence MBA in Aviation Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Distance/Correspondence MBA in Aviation colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'MBA Aviation Distance Learning in {Location}, MBA Aviation Distance Learning, List of MBA Aviation Distance Learning in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
735 => array(
		'url' => 'part-time-mba-in-operations-in-{Location}',
		'title' => 'Part Time MBA in Operations Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Part Time MBA in Operations colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Part Time MBA in Operations in {Location}, Part Time MBA in Operations Courses, List of Part Time MBA in Operations in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
737 => array(
		'url' => 'part-time-mba-in-sales-and-marketing-in-{Location}',
		'title' => 'Part Time MBA in Sales & Marketing Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Part Time MBA in Sales & Marketing colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Part Time MBA in Sales & Marketing in {Location}, Part Time MBA in Sales & Marketing Courses, List of Part Time MBA in Sales & Marketing in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
738 => array(
		'url' => 'part-time-mba-in-finance-in-{Location}',
		'title' => 'Part Time MBA in Finance Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Part Time MBA in Finance colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Part Time MBA in Finance in {Location}, Part Time MBA in Finance Courses, List of Part Time MBA in Finance in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
739 => array(
		'url' => 'part-time-mba-in-hr-in-{Location}',
		'title' => 'Part Time MBA in Human Resources Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Part Time MBA in Human Resources colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Part Time MBA in Human Resources in {Location}, Part Time MBA in Human Resources Courses, List of Part Time MBA in Human Resources in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
740 => array(
		'url' => 'part-time-mba-in-it-in-{Location}',
		'title' => 'Part Time MBA in IT Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Part Time MBA in IT colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Part Time MBA in IT in {Location}, Part Time MBA in IT Courses, List of Part Time MBA in IT in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
741 => array(
		'url' => 'part-time-mba-in-international-business-in-{Location}',
		'title' => 'Part Time MBA in International Business Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Part Time MBA in International Business colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Part Time MBA in International Business in {Location}, Part Time MBA in International Business Courses, List of Part Time MBA in International Business in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
744 => array(
		'url' => 'part-time-mba-in-hospital-management-in-{Location}',
		'title' => 'Part Time MBA in Hosptial/Health Care Management Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Part Time MBA in Hosptial/Health Care Management colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Part Time MBA in Hosptial/Health Care Management in {Location}, Part Time MBA in Hosptial/Health Care Management Courses, List of Part Time MBA in Hosptial/Health Care Management in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
745 => array(
		'url' => 'part-time-mba-in-biotechnology-in-{Location}',
		'title' => 'Part Time MBA in BioTechnology Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Part Time MBA in BioTechnology colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Part Time MBA in BioTechnology in {Location}, Part Time MBA in BioTechnology Courses, List of Part Time MBA in BioTechnology in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
746 => array(
		'url' => 'part-time-mba-in-telecom-mangement-in-{Location}',
		'title' => 'Part Time MBA in Telecom Management Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Part Time MBA in Telecom Management colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Part Time MBA in Telecom Management in {Location}, Part Time MBA in Telecom Management Courses, List of Part Time MBA in Telecom Management in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
747 => array(
		'url' => 'part-time-mba-in-infrastructure-management-in-{Location}',
		'title' => 'Part Time MBA in Infrastructure & Development Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Part Time MBA in Infrastructure & Development colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Part Time MBA in Infrastructure & Development in {Location}, Part Time MBA in Infrastructure & Development Courses, List of Part Time MBA in Infrastructure & Development in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
748 => array(
		'url' => 'part-time-mba-in-hospitality-management-in-{Location}',
		'title' => 'Part Time MBA in Hospitality Management Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Part Time MBA in Hospitality Management colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Part Time MBA in Hospitality Management in {Location}, Part Time MBA in Hospitality Management Courses, List of Part Time MBA in Hospitality Management in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
749 => array(
		'url' => 'part-time-mba-in-retail-in-{Location}',
		'title' => 'Part Time MBA in Retail Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Part Time MBA in Retail colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Part Time MBA in Retail in {Location}, Part Time MBA in Retail Courses, List of Part Time MBA in Retail in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
750 => array(
		'url' => 'part-time-mba-in-accounting-in-{Location}',
		'title' => 'Part Time MBA in Accounting Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Part Time MBA in Accounting colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Part Time MBA in Accounting in {Location}, Part Time MBA in Accounting Courses, List of Part Time MBA in Accounting in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
751 => array(
		'url' => 'part-time-mba-in-aviation-in-{Location}',
		'title' => 'Part Time MBA in Aviation Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Part Time MBA in Aviation colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Part Time MBA in Aviation in {Location}, Part Time MBA in Aviation Courses, List of Part Time MBA in Aviation in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
752 => array(
		'url' => 'online-mba-in-operations-in-{Location}',
		'title' => 'Online MBA in Operations Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Online MBA in Operations colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Online MBA in Operations in {Location}, Online MBA in Operations Courses, List of Online MBA in Operations in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
754 => array(
		'url' => 'online-mba-in-sales-and-marketing-in-{Location}',
		'title' => 'Online MBA in Sales & Marketing Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Online MBA in Sales & Marketing colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Online MBA in Sales & Marketing in {Location}, Online MBA in Sales & Marketing Courses, List of Online MBA in Sales & Marketing in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
755 => array(
		'url' => 'online-mba-in-finance-in-{Location}',
		'title' => 'Online MBA in Finance Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Online MBA in Finance colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Online MBA in Finance in {Location}, Online MBA in Finance Courses, List of Online MBA in Finance in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
756 => array(
		'url' => 'online-mba-in-hr-in-{Location}',
		'title' => 'Online MBA in Human Resources Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Online MBA in Human Resources colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Online MBA in Human Resources in {Location}, Online MBA in Human Resources Courses, List of Online MBA in Human Resources in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
757 => array(
		'url' => 'online-mba-in-it-in-{Location}',
		'title' => 'Online MBA in IT Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Online MBA in IT colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Online MBA in IT in {Location}, Online MBA in IT Courses, List of Online MBA in IT in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
758 => array(
		'url' => 'online-mba-in-international-business-in-{Location}',
		'title' => 'Online MBA in International Business Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Online MBA in International Business colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Online MBA in International Business in {Location}, Online MBA in International Business Courses, List of Online MBA in International Business in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
761 => array(
		'url' => 'online-mba-in-hospital-management-in-{Location}',
		'title' => 'Online MBA in hospital/Health Care Management Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Online MBA in hospital/Health Care Management colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Online MBA in hospital/Health Care Management in {Location}, Online MBA in hospital/Health Care Management Courses, List of Online MBA in hospital/Health Care Management in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
762 => array(
		'url' => 'online-mba-in-biotechnology-in-{Location}',
		'title' => 'Online MBA in BioTechnology Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Online MBA in BioTechnology colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Online MBA in BioTechnology in {Location}, Online MBA in BioTechnology Courses, List of Online MBA in BioTechnology in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
763 => array(
		'url' => 'online-mba-in-telecom-mangement-in-{Location}',
		'title' => 'Online MBA in Telecom Management Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Online MBA in Telecom Management colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Online MBA in Telecom Management in {Location}, Online MBA in Telecom Management Courses, List of Online MBA in Telecom Management in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
764 => array(
		'url' => 'online-mba-in-infrastructure-management-in-{Location}',
		'title' => 'Online MBA in Infrastructure & Development Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Online MBA in Infrastructure & Development colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Online MBA in Infrastructure & Development in {Location}, Online MBA in Infrastructure & Development Courses, List of Online MBA in Infrastructure & Development in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
765 => array(
		'url' => 'online-mba-in-hospitality-management-in-{Location}',
		'title' => 'Online MBA in Hospitality Management Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Online MBA in Hospitality Management colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Online MBA in Hospitality Management in {Location}, Online MBA in Hospitality Management Courses, List of Online MBA in Hospitality Management in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
766 => array(
		'url' => 'online-mba-in-retail-in-{Location}',
		'title' => 'Online MBA in Retail Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Online MBA in Retail colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Online MBA in Retail in {Location}, Online MBA in Retail Courses, List of Online MBA in Retail in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
767 => array(
		'url' => 'online-mba-in-accounting-in-{Location}',
		'title' => 'Online MBA in Accounting Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Online MBA in Accounting colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Online MBA in Accounting in {Location}, Online MBA in Accounting Courses, List of Online MBA in Accounting in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
768 => array(
		'url' => 'online-mba-in-aviation-in-{Location}',
		'title' => 'Online MBA in Aviation Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Online MBA in Aviation colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Online MBA in Aviation in {Location}, Online MBA in Aviation Courses, List of Online MBA in Aviation in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
769 => array(
		'url' => 'finance-certification-courses-in-{Location}',
		'title' => 'Certification in Finance Courses in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Certification in Finance courses in {location}, check their courses, fees, admissions, eligibility and more details.',
		'keywords' => 'Finance Certification Courses in {location}, Finance Certification Courses, Finance Certification training, universities, institutes, career, career options, Education'
),
770 => array(
		'url' => 'it-certification-courses-in-{Location}',
		'title' => 'Certification in IT Courses in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Certification in IT courses in {location}, check their courses, fees, admissions, eligibility and more details.',
		'keywords' => 'IT Certification Courses in {location}, IT Certification Courses, IT Certification training, universities, institutes, career, career options, Education'
),
771 => array(
		'url' => 'hr-certification-courses-in-{Location}',
		'title' => 'Certification in HR Courses in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Certification in HR courses in {location}, check their courses, fees, admissions, eligibility and more details.',
		'keywords' => 'HR Certification Courses in {location}, HR Certification Courses, HR Certification training, universities, institutes, career, career options, Education'
),
772 => array(
		'url' => 'marketing-certification-courses-in-{Location}',
		'title' => 'Certification in Marketing Courses in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Certification in Marketing courses in {location}, check their courses, fees, admissions, eligibility and more details.',
		'keywords' => 'Marketing Certification Courses in {location}, Marketing Certification Courses, Marketing Certification training, universities, institutes, career, career options, Education'
),
773 => array(
		'url' => 'international-business-certification-courses-in-{Location}',
		'title' => 'Certification in International Business Courses in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Certification in International Business courses in {location}, check their courses, fees, admissions, eligibility and more details.',
		'keywords' => 'International Business Certification Courses in {location}, International Business Certification Courses, International Business Certification training, universities, institutes, career, career options, Education'
),
774 => array(
		'url' => 'operations-certification-courses-in-{Location}',
		'title' => 'Certification in Operations Courses in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Certification in Operations courses in {location}, check their courses, fees, admissions, eligibility and more details.',
		'keywords' => 'Operations Certification Courses in {location}, Operations Certification Courses, Operations Certification training, universities, institutes, career, career options, Education'
),
775 => array(
		'url' => 'logistics-certification-courses-in-{Location}',
		'title' => 'Certification in Logistics Courses in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Certification in Logistics courses in {location}, check their courses, fees, admissions, eligibility and more details.',
		'keywords' => 'Logistics Certification Courses in {location}, Logistics Certification Courses, Logistics Certification training, universities, institutes, career, career options, Education'
),
776 => array(
		'url' => 'sales-certification-courses-in-{Location}',
		'title' => 'Certification in Sales Courses in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Certification in Sales courses in {location}, check their courses, fees, admissions, eligibility and more details.',
		'keywords' => 'Sales Certification Courses in {location}, Sales Certification Courses, Sales Certification training, universities, institutes, career, career options, Education'
),
777 => array(
		'url' => 'safety-management-certification-courses-in-{Location}',
		'title' => 'Certification in Safety Management Courses in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Certification in Safety Management courses in {location}, check their courses, fees, admissions, eligibility and more details.',
		'keywords' => 'Safety Management Certification in {Location}, Safety Management Certification Courses, Safety  Management Certification training, universities, institutes, career, career options, Education'
),
778 => array(
		'url' => 'six-sigma-certification-courses-in-{Location}',
		'title' => 'Certification in Six Sigma Courses in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Certification in Six Sigma courses in {location}, check their courses, fees, admissions, eligibility and more details.',
		'keywords' => 'Six Sigma Certification in {Location}, Six Sigma Certification Courses, Six Sigma Certification training, universities, institutes, career, career options, Education'
),
779 => array(
		'url' => 'export-import-certification-courses-in-{Location}',
		'title' => 'Certification in Export Import Courses in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Certification in Export Import courses in {location}, check their courses, fees, admissions, eligibility and more details.',
		'keywords' => 'Export Import Certification in {Location}, Export Import Certification Courses, Export Import Certification training, universities, institutes, career, career options, Education'
),
780 => array(
		'url' => 'quality-management-certification-courses-in-{Location}',
		'title' => 'Certification in Quality Management Courses in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Certification in Quality Management courses in {location}, check their courses, fees, admissions, eligibility and more details.',
		'keywords' => 'Quality Management Certification in {Location}, Quality Management Certification Courses, Quality Management Certification training, universities, institutes, career, career options, Education'
),
784 => array(
		'url' => 'diploma-in-event-management-in-{location}',
		'keywords' => 'diploma in event management in {location}, diploma in event management colleges in {location}, diploma in event management institutes in {location}, diploma in event management courses, diploma in event management colleges, diploma in event management institutes, diploma in event management schools, full time diploma in event management, part time diploma in event management'
),
784 => array(
		'url' => 'diploma-in-event-management-in-{location}',
		'keywords' => 'diploma in event management in {location}, diploma courses in event management in {location}, diploma colleges in event management in {location}, diploma institutes in event management in {location}, full time diploma courses in event management in {location}, part time diploma courses in event management in {location}'
),
785 => array(
		'url' => 'pg-diploma-in-event-management-in-{location}',
		'keywords' => 'PG diploma in event management in {location}, PG diploma in event management colleges in {location}, PG diploma in event management institutes in {location}, PG diploma in event management courses, PG diploma in event management colleges, PG diploma in event management institutes, PG diploma in event management schools, full time PG diploma in event management, part time PG diploma in event management'
),
785 => array(
		'url' => 'pg-diploma-in-event-management-in-{location}',
		'keywords' => 'PG diploma in event management in {location}, PG diploma courses in event management in {location}, PG diploma colleges in event management in {location}, PG diploma institutes in event management in {location}, full time PG diploma courses in event management in {location}, part time PG diploma courses in event management in {location}'
),
786 => array(
		'url' => 'pg-diploma-in-journalism-in-{location}',
		'keywords' => 'PG diploma in journalism in {location}, PG diploma courses in journalism in {location}, PG diploma colleges in journalism in {location}, PG diploma institutes in journalism in {location}, full time PG diploma courses in journalism in {location}, part time PG diploma courses in journalism in {location}'
),
787 => array(
		'url' => 'bds-dental-colleges-in-{location}',
		'keywords' => 'bds courses in {location}, bds colleges in {location}, bds institutes in {location}, bds courses, bds colleges'
),
788 => array(
		'url' => 'mds-dental-colleges-in-{location}',
		'keywords' => 'mds courses in {location}, mds colleges in {location}, mds institutes in {location}, mds courses, mds colleges'
),
789 => array(
		'url' => 'bsc-in-medical-lab-technology-in-{location}',
		'keywords' => 'bsc in medical lab technology in {location}, medical lab technology colleges in {location}, bsc in medical lab technology, medical lab technology courses in {location}, medical lab technology institutes in {location}'
),
790 => array(
		'url' => 'msc-in-medical-lab-technology-in-{location}',
		'keywords' => 'msc in medical lab technology in {location}, medical lab technology colleges in {location}, msc in medical lab technology, medical lab technology courses in {location}, medical lab technology institutes in {location}'
),
791 => array(
		'url' => 'b-pharma-colleges-in-{location}',
		'keywords' => 'b pharma courses in {location}, b pharma colleges in {location}, b pharma institutes in {location}, b pharma courses, b pharma colleges, b pharma institutes'
),
792 => array(
		'url' => 'bpt-bachelor-in-physiotherapy-courses-in-{location}',
		'keywords' => 'bachelor in physiotherapy courses in {location}, BPT courses in {location}, bachelor in physiotherapy colleges in {location}, physiotherapy courses, BPT colleges'
),
793 => array(
		'url' => 'mpt-master-in-physiotherapy-courses-in-{location}',
		'keywords' => 'master in physiotherapy courses in {location}, MPT courses in {location}, master in physiotherapy colleges in {location}, physiotherapy courses, MPT colleges'
),
809 => array(
        'url' => 'cat-entrance-exams-coaching-classes-in-{location}',
        'keywords' => 'CAT coaching institutes, CAT coaching classes, CAT entrance exam coaching, CAT coaching classes in {location}, CAT coaching institutes in {location}, list of CAT coaching institutes {location}'
),
810 => array(
        'url' => 'snap-entrance-exams-coaching-classes-in-{location}',
        'keywords' => 'SNAP coaching institutes, SNAP coaching classes, SNAP entrance exam coaching, SNAP coaching classes in {location}, SNAP coaching institutes in {location}, list of SNAP coaching institutes {location}'
),
811 => array(
        'url' => 'xat-entrance-exams-coaching-classes-in-{location}',
        'keywords' => 'XAT coaching institutes, XAT coaching classes, XAT entrance exam coaching, XAT coaching classes in {location}, XAT coaching institutes in {location}, list of XAT coaching institutes {location}'
),
812 => array(
        'url' => 'mat-entrance-exams-coaching-classes-in-{location}',
        'keywords' => 'MAT coaching institutes, MAT coaching classes, MAT entrance exam coaching, MAT coaching classes in {location}, MAT coaching institutes in {location}, list of MAT coaching institutes {location}'
),
813 => array(
        'url' => 'jmet-entrance-exams-coaching-classes-in-{location}',
        'keywords' => 'JMET coaching institutes, JMET coaching classes, JMET entrance exam coaching, JMET coaching classes in {location}, JMET coaching institutes in {location}, list of JMET coaching institutes {location}'
),
814 => array(
        'url' => 'iift-entrance-exams-coaching-classes-in-{location}',
        'keywords' => 'IIFT coaching institutes, IIFT coaching classes, IIFT entrance exam coaching, IIFT coaching classes in {location}, IIFT coaching institutes in {location}, list of IIFT coaching institutes {location}'
),
815 => array(
        'url' => 'fms-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'FMS coaching institutes, FMS coaching classes, FMS entrance exam coaching, FMS coaching classes in {location}, FMS coaching institutes in {location}, list of FMS coaching institutes {location}'
),
816 => array(
		'url' => 'tiss-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'TISS coaching institutes, TISS coaching classes, TISS entrance exam coaching, TISS coaching classes in {location}, TISS coaching institutes in {location}, list of TISS coaching institutes {location}'
),
817 => array(
		'url' => 'ibsat-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'IBSAT coaching institutes, IBSAT coaching classes, IBSAT entrance exam coaching, IBSAT coaching classes in {location}, IBSAT coaching institutes in {location}, list of IBSAT coaching institutes {location}'
),
818 => array(
		'url' => 'nmat-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'NMAT coaching institutes, NMAT coaching classes, NMAT entrance exam coaching, NMAT coaching classes in {location}, NMAT coaching institutes in {location}, list of NMAT coaching institutes {location}'
),
819 => array(
		'url' => 'mhcet-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'MHCET coaching institutes, MHCET coaching classes, MHCET entrance exam coaching, MHCET coaching classes in {location}, MHCET coaching institutes in {location}, list of MHCET coaching institutes {location}'
),
820 => array(
		'url' => 'atma-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'ATMA coaching institutes, ATMA coaching classes, ATMA entrance exam coaching, ATMA coaching classes in {location}, ATMA coaching institutes in {location}, list of ATMA coaching institutes {location}'
),
821 => array(
		'url' => 'pgcet-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'PGCET coaching institutes, PGCET coaching classes, PGCET entrance exam coaching, PGCET coaching classes in {location}, PGCET coaching institutes in {location}, list of PGCET coaching institutes {location}'
),
822 => array(
		'url' => 'tancet-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'TANCET coaching institutes, TANCET coaching classes, TANCET entrance exam coaching, TANCET coaching classes in {location}, TANCET coaching institutes in {location}, list of TANCET coaching institutes {location}'
),
823 => array(
		'url' => 'aimat-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'AIMAT coaching institutes, AIMAT coaching classes, AIMAT entrance exam coaching, AIMAT coaching classes in {location}, AIMAT coaching institutes in {location}, list of AIMAT coaching institutes {location}'
),
824 => array(
		'url' => 'orissa-mat-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'Orissa MAT coaching institutes, Orissa MAT coaching classes, Orissa MAT entrance exam coaching, Orissa MAT coaching classes in {location}, Orissa MAT coaching institutes in {location}, list of Orissa MAT coaching institutes {location}'
),
825 => array(
		'url' => 'kmat-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'KMAT coaching institutes, KMAT coaching classes, KMAT entrance exam coaching, KMAT coaching classes in {location}, KMAT coaching institutes in {location}, list of KMAT coaching institutes {location}'
),
826 => array(
		'url' => 'jemat-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'JEMAT coaching institutes, JEMAT coaching classes, JEMAT entrance exam coaching, JEMAT coaching classes in {location}, JEMAT coaching institutes in {location}, list of JEMAT coaching institutes {location}'
),
827 => array(
		'url' => 'gcet-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'GCET coaching institutes, GCET coaching classes, GCET entrance exam coaching, GCET coaching classes in {location}, GCET coaching institutes in {location}, list of GCET coaching institutes {location}'
),
828 => array(
		'url' => 'openmat-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'OpenMAT coaching institutes, OpenMAT coaching classes, OpenMAT entrance exam coaching, OpenMAT coaching classes in {location}, OpenMAT coaching institutes in {location}, list of OpenMAT coaching institutes {location}'
),
829 => array(
		'url' => 'mpmet-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'MPMET coaching institutes, MPMET coaching classes, MPMET entrance exam coaching, MPMET coaching classes in {location}, MPMET coaching institutes in {location}, list of MPMET coaching institutes {location}'
),
830 => array(
		'url' => 'up-mcat-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'UP-MCAT coaching institutes, UP-MCAT coaching classes, UP-MCAT entrance exam coaching, UP-MCAT coaching classes in {location}, UP-MCAT coaching institutes in {location}, list of UP-MCAT coaching institutes {location}'
),
831 => array(
		'url' => 'rmat-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'RMAT coaching institutes, RMAT coaching classes, RMAT entrance exam coaching, RMAT coaching classes in {location}, RMAT coaching institutes in {location}, list of RMAT coaching institutes {location}'
),
832 => array(
		'url' => 'uajet-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'UAJET coaching institutes, UAJET coaching classes, UAJET entrance exam coaching, UAJET coaching classes in {location}, UAJET coaching institutes in {location}, list of UAJET coaching institutes {location}'
),
833 => array(
		'url' => 'irma-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'IRMA coaching institutes, IRMA coaching classes, IRMA entrance exam coaching, IRMA coaching classes in {location}, IRMA coaching institutes in {location}, list of IRMA coaching institutes {location}'
),
834 => array(
		'url' => 'hpcmat-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'HPCMAT coaching institutes, HPCMAT coaching classes, HPCMAT entrance exam coaching, HPCMAT coaching classes in {location}, HPCMAT coaching institutes in {location}, list of HPCMAT coaching institutes {location}'
),
835 => array(
		'url' => 'micat-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'MICAT coaching institutes, MICAT coaching classes, MICAT entrance exam coaching, MICAT coaching classes in {location}, MICAT coaching institutes in {location}, list of MICAT coaching institutes {location}'
),
836 => array(
		'url' => 'iitjee-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'IITJEE coaching institutes, IITJEE coaching classes, IITJEE entrance exam coaching, IITJEE coaching classes in {location}, IITJEE coaching institutes in {location}, list of IITJEE coaching institutes {location}'
),
837 => array(
		'url' => 'aieee-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'AIEEE coaching institutes, AIEEE coaching classes, AIEEE entrance exam coaching, AIEEE coaching classes in {location}, AIEEE coaching institutes in {location}, list of AIEEE coaching institutes {location}'
),
838 => array(
		'url' => 'bitsat-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'BITSAT coaching institutes, BITSAT coaching classes, BITSAT entrance exam coaching, BITSAT coaching classes in {location}, BITSAT coaching institutes in {location}, list of BITSAT coaching institutes {location}'
),
839 => array(
		'url' => 'enat-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'ENAT coaching institutes, ENAT coaching classes, ENAT entrance exam coaching, ENAT coaching classes in {location}, ENAT coaching institutes in {location}, list of ENAT coaching institutes {location}'
),
840 => array(
		'url' => 'srmjeee-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'SRMJEEE coaching institutes, SRMJEEE coaching classes, SRMJEEE entrance exam coaching, SRMJEEE coaching classes in {location}, SRMJEEE coaching institutes in {location}, list of SRMJEEE coaching institutes {location}'
),
841 => array(
		'url' => 'viteee-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'VITEEE coaching institutes, VITEEE coaching classes, VITEEE entrance exam coaching, VITEEE coaching classes in {location}, VITEEE coaching institutes in {location}, list of VITEEE coaching institutes {location}'
),
842 => array(
		'url' => 'cetk-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'CETK coaching institutes, CETK coaching classes, CETK entrance exam coaching, CETK coaching classes in {location}, CETK coaching institutes in {location}, list of CETK coaching institutes {location}'
),
843 => array(
		'url' => 'mhtcet-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'MHTCET coaching institutes, MHTCET coaching classes, MHTCET entrance exam coaching, MHTCET coaching classes in {location}, MHTCET coaching institutes in {location}, list of MHTCET coaching institutes {location}'
),
844 => array(
		'url' => 'comedk-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'COMEDK coaching institutes, COMEDK coaching classes, COMEDK entrance exam coaching, COMEDK coaching classes in {location}, COMEDK coaching institutes in {location}, list of COMEDK coaching institutes {location}'
),
845 => array(
		'url' => 'eamcet-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'EAMCET coaching institutes, EAMCET coaching classes, EAMCET entrance exam coaching, EAMCET coaching classes in {location}, EAMCET coaching institutes in {location}, list of EAMCET coaching institutes {location}'
),
846 => array(
		'url' => 'tnea-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'TNEA coaching institutes, TNEA coaching classes, TNEA entrance exam coaching, TNEA coaching classes in {location}, TNEA coaching institutes in {location}, list of TNEA coaching institutes {location}'
),
847 => array(
		'url' => 'upsee-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'UPSEE coaching institutes, UPSEE coaching classes, UPSEE entrance exam coaching, UPSEE coaching classes in {location}, UPSEE coaching institutes in {location}, list of UPSEE coaching institutes {location}'
),
848 => array(
		'url' => 'gate-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'GATE coaching institutes, GATE coaching classes, GATE entrance exam coaching, GATE coaching classes in {location}, GATE coaching institutes in {location}, list of GATE coaching institutes {location}'
),
849 => array(
		'url' => 'punjab-cet-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'Punjab CET coaching institutes, Punjab CET coaching classes, Punjab CET entrance exam coaching, Punjab CET coaching classes in {location}, Punjab CET coaching institutes in {location}, list of Punjab CET coaching institutes {location}'
),
850 => array(
		'url' => 'orissa-jee-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'Orissa JEE coaching institutes, Orissa JEE coaching classes, Orissa JEE entrance exam coaching, Orissa JEE coaching classes in {location}, Orissa JEE coaching institutes in {location}, list of Orissa JEE coaching institutes {location}'
),
851 => array(
		'url' => 'rpet-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'RPET coaching institutes, RPET coaching classes, RPET entrance exam coaching, RPET coaching classes in {location}, RPET coaching institutes in {location}, list of RPET coaching institutes {location}'
),
852 => array(
		'url' => 'mp pet-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'MP PET coaching institutes, MP PET coaching classes, MP PET entrance exam coaching, MP PET coaching classes in {location}, MP PET coaching institutes in {location}, list of MP PET coaching institutes {location}'
),
853 => array(
		'url' => 'keam-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'KEAM coaching institutes, KEAM coaching classes, KEAM entrance exam coaching, KEAM coaching classes in {location}, KEAM coaching institutes in {location}, list of KEAM coaching institutes {location}'
),
854 => array(
		'url' => 'gujcet-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'GUJCET coaching institutes, GUJCET coaching classes, GUJCET entrance exam coaching, GUJCET coaching classes in {location}, GUJCET coaching institutes in {location}, list of GUJCET coaching institutes {location}'
),
855 => array(
		'url' => 'wbjee-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'WBJEE coaching institutes, WBJEE coaching classes, WBJEE entrance exam coaching, WBJEE coaching classes in {location}, WBJEE coaching institutes in {location}, list of WBJEE coaching institutes {location}'
),
856 => array(
		'url' => 'ggsipu-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'GGSIPU coaching institutes, GGSIPU coaching classes, GGSIPU entrance exam coaching, GGSIPU coaching classes in {location}, GGSIPU coaching institutes in {location}, list of GGSIPU coaching institutes {location}'
),
857 => array(
		'url' => 'nat-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'NAT coaching institutes, NAT coaching classes, NAT entrance exam coaching, NAT coaching classes in {location}, NAT coaching institutes in {location}, list of NAT coaching institutes {location}'
),
858 => array(
		'url' => 'nerist-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'NERIST coaching institutes, NERIST coaching classes, NERIST entrance exam coaching, NERIST coaching classes in {location}, NERIST coaching institutes in {location}, list of NERIST coaching institutes {location}'
),
859 => array(
		'url' => 'pacet-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'PACET coaching institutes, PACET coaching classes, PACET entrance exam coaching, PACET coaching classes in {location}, PACET coaching institutes in {location}, list of PACET coaching institutes {location}'
),
860 => array(
		'url' => 'atit-icfai-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'ATIT ICFAI coaching institutes, ATIT ICFAI coaching classes, ATIT ICFAI entrance exam coaching, ATIT ICFAI coaching classes in {location}, ATIT ICFAI coaching institutes in {location}, list of ATIT ICFAI coaching institutes {location}'
),
861 => array(
		'url' => 'iist-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'IIST coaching institutes, IIST coaching classes, IIST entrance exam coaching, IIST coaching classes in {location}, IIST coaching institutes in {location}, list of IIST coaching institutes {location}'
),
862 => array(
		'url' => 'wat-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'WAT coaching institutes, WAT coaching classes, WAT entrance exam coaching, WAT coaching classes in {location}, WAT coaching institutes in {location}, list of WAT coaching institutes {location}'
),
863 => array(
		'url' => 'nata-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'NATA coaching institutes, NATA coaching classes, NATA entrance exam coaching, NATA coaching classes in {location}, NATA coaching institutes in {location}, list of NATA coaching institutes {location}'
),
864 => array(
		'url' => 'delhi-cee-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'Delhi CEE coaching institutes, Delhi CEE coaching classes, Delhi CEE entrance exam coaching, Delhi CEE coaching classes in {location}, Delhi CEE coaching institutes in {location}, list of Delhi CEE coaching institutes {location}'
),
865 => array(
		'url' => 'set-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'SET coaching institutes, SET coaching classes, SET entrance exam coaching, SET coaching classes in {location}, SET coaching institutes in {location}, list of SET coaching institutes {location}'
),
866 => array(
		'url' => 'jnueee-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'JNUEEE coaching institutes, JNUEEE coaching classes, JNUEEE entrance exam coaching, JNUEEE coaching classes in {location}, JNUEEE coaching institutes in {location}, list of JNUEEE coaching institutes {location}'
),
867 => array(
		'url' => 'vee-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'VEE coaching institutes, VEE coaching classes, VEE entrance exam coaching, VEE coaching classes in {location}, VEE coaching institutes in {location}, list of VEE coaching institutes {location}'
),
868 => array(
		'url' => 'mp-dmat-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'MP DMAT - Madhya Pradesh Dental & Medical Admission Test coaching institutes, MP DMAT - Madhya Pradesh Dental & Medical Admission Test coaching classes, MP DMAT - Madhya Pradesh Dental & Medical Admission Test entrance exam coaching, MP DMAT - Madhya Pradesh Dental & Medical Admission Test coaching classes in {location}, MP DMAT - Madhya Pradesh Dental & Medical Admission Test coaching institutes in {location}, list of MP DMAT - Madhya Pradesh Dental & Medical Admission Test coaching institutes {location}'
),
869 => array(
		'url' => 'aims-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'AIMS - Amrita Institute of Medical Sciences Entrance Exam coaching institutes, AIMS - Amrita Institute of Medical Sciences Entrance Exam coaching classes, AIMS - Amrita Institute of Medical Sciences Entrance Exam entrance exam coaching, AIMS - Amrita Institute of Medical Sciences Entrance Exam coaching classes in {location}, AIMS - Amrita Institute of Medical Sciences Entrance Exam coaching institutes in {location}, list of AIMS - Amrita Institute of Medical Sciences Entrance Exam coaching institutes {location}'
),
870 => array(
		'url' => 'aipgmee-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'AIPGMEE - All India Post Graduate Medical/Dental Entrance Exam coaching institutes, AIPGMEE - All India Post Graduate Medical/Dental Entrance Exam coaching classes, AIPGMEE - All India Post Graduate Medical/Dental Entrance Exam entrance exam coaching, AIPGMEE - All India Post Graduate Medical/Dental Entrance Exam coaching classes in {location}, AIPGMEE - All India Post Graduate Medical/Dental Entrance Exam coaching institutes in {location}, list of AIPGMEE - All India Post Graduate Medical/Dental Entrance Exam coaching institutes {location}'
),
871 => array(
		'url' => 'au-aimee-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'AU AIMEE - Annamalai University - All India Medical Entrance Examinations coaching institutes, AU AIMEE - Annamalai University - All India Medical Entrance Examinations coaching classes, AU AIMEE - Annamalai University - All India Medical Entrance Examinations entrance exam coaching, AU AIMEE - Annamalai University - All India Medical Entrance Examinations coaching classes in {location}, AU AIMEE - Annamalai University - All India Medical Entrance Examinations coaching institutes in {location}, list of AU AIMEE - Annamalai University - All India Medical Entrance Examinations coaching institutes {location}'
),
872 => array(
		'url' => 'amupmdc-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'AMUPMDC - Association of Managements of Unaided Private Medical and Dental Colleges coaching institutes, AMUPMDC - Association of Managements of Unaided Private Medical and Dental Colleges coaching classes, AMUPMDC - Association of Managements of Unaided Private Medical and Dental Colleges entrance exam coaching, AMUPMDC - Association of Managements of Unaided Private Medical and Dental Colleges coaching classes in {location}, AMUPMDC - Association of Managements of Unaided Private Medical and Dental Colleges coaching institutes in {location}, list of AMUPMDC - Association of Managements of Unaided Private Medical and Dental Colleges coaching institutes {location}'
),
873 => array(
		'url' => 'bharati-vidyapeeth-university-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'Bharati Vidyapeeth University Medical Entrance Exam coaching institutes, Bharati Vidyapeeth University Medical Entrance Exam coaching classes, Bharati Vidyapeeth University Medical Entrance Exam entrance exam coaching, Bharati Vidyapeeth University Medical Entrance Exam coaching classes in {location}, Bharati Vidyapeeth University Medical Entrance Exam coaching institutes in {location}, list of Bharati Vidyapeeth University Medical Entrance Exam coaching institutes {location}'
),
874 => array(
		'url' => 'bhu-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'BHU - Banaras Hindu University Medical Entrance Examination coaching institutes, BHU - Banaras Hindu University Medical Entrance Examination coaching classes, BHU - Banaras Hindu University Medical Entrance Examination entrance exam coaching, BHU - Banaras Hindu University Medical Entrance Examination coaching classes in {location}, BHU - Banaras Hindu University Medical Entrance Examination coaching institutes in {location}, list of BHU - Banaras Hindu University Medical Entrance Examination coaching institutes {location}'
),
875 => array(
		'url' => 'blde-uget-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'BLDE UGET - BLDE University Under Graduate Entrance Test coaching institutes, BLDE UGET - BLDE University Under Graduate Entrance Test coaching classes, BLDE UGET - BLDE University Under Graduate Entrance Test entrance exam coaching, BLDE UGET - BLDE University Under Graduate Entrance Test coaching classes in {location}, BLDE UGET - BLDE University Under Graduate Entrance Test coaching institutes in {location}, list of BLDE UGET - BLDE University Under Graduate Entrance Test coaching institutes {location}'
),
876 => array(
		'url' => 'cetppmc-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'CETPPMC - Common Entrance Test for Pondicherry Private Medical Colleges coaching institutes, CETPPMC - Common Entrance Test for Pondicherry Private Medical Colleges coaching classes, CETPPMC - Common Entrance Test for Pondicherry Private Medical Colleges entrance exam coaching, CETPPMC - Common Entrance Test for Pondicherry Private Medical Colleges coaching classes in {location}, CETPPMC - Common Entrance Test for Pondicherry Private Medical Colleges coaching institutes in {location}, list of CETPPMC - Common Entrance Test for Pondicherry Private Medical Colleges coaching institutes {location}'
),
877 => array(
		'url' => 'comedk-pget-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'COMEDK PGET - Consortium of Medical Engineering and Dental Colleges of Karnataka Post Graduate Entrance Test coaching institutes, COMEDK PGET - Consortium of Medical Engineering and Dental Colleges of Karnataka Post Graduate Entrance Test coaching classes, COMEDK PGET - Consortium of Medical Engineering and Dental Colleges of Karnataka Post Graduate Entrance Test entrance exam coaching, COMEDK PGET - Consortium of Medical Engineering and Dental Colleges of Karnataka Post Graduate Entrance Test coaching classes in {location}, COMEDK PGET - Consortium of Medical Engineering and Dental Colleges of Karnataka Post Graduate Entrance Test coaching institutes in {location}, list of COMEDK PGET - Consortium of Medical Engineering and Dental Colleges of Karnataka Post Graduate Entrance Test coaching institutes {location}'
),
878 => array(
		'url' => 'christian-medical-college-cmc-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'Christian Medical College Ludhiana Entrance Test coaching institutes, Christian Medical College Ludhiana Entrance Test coaching classes, Christian Medical College Ludhiana Entrance Test entrance exam coaching, Christian Medical College Ludhiana Entrance Test coaching classes in {location}, Christian Medical College Ludhiana Entrance Test coaching institutes in {location}, list of Christian Medical College Ludhiana Entrance Test coaching institutes {location}'
),
879 => array(
		'url' => 'dumet-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'DUMET - Delhi University Medical/Dental Entrance Test coaching institutes, DUMET - Delhi University Medical/Dental Entrance Test coaching classes, DUMET - Delhi University Medical/Dental Entrance Test entrance exam coaching, DUMET - Delhi University Medical/Dental Entrance Test coaching classes in {location}, DUMET - Delhi University Medical/Dental Entrance Test coaching institutes in {location}, list of DUMET - Delhi University Medical/Dental Entrance Test coaching institutes {location}'
),
880 => array(
		'url' => 'dupgmet-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'DUPGMET - Delhi University Post Graduate Medical Entrance Test coaching institutes, DUPGMET - Delhi University Post Graduate Medical Entrance Test coaching classes, DUPGMET - Delhi University Post Graduate Medical Entrance Test entrance exam coaching, DUPGMET - Delhi University Post Graduate Medical Entrance Test coaching classes in {location}, DUPGMET - Delhi University Post Graduate Medical Entrance Test coaching institutes in {location}, list of DUPGMET - Delhi University Post Graduate Medical Entrance Test coaching institutes {location}'
),
881 => array(
		'url' => 'duset-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'DUSET - Delhi University Super-Specialty Entrance Test coaching institutes, DUSET - Delhi University Super-Specialty Entrance Test coaching classes, DUSET - Delhi University Super-Specialty Entrance Test entrance exam coaching, DUSET - Delhi University Super-Specialty Entrance Test coaching classes in {location}, DUSET - Delhi University Super-Specialty Entrance Test coaching institutes in {location}, list of DUSET - Delhi University Super-Specialty Entrance Test coaching institutes {location}'
),
882 => array(
		'url' => 'dr-dy-patil-medical-mbbs-bds-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'Dr. D. Y.Patil Institute All India Common Entrance Test for MBBS and BDS coaching institutes, Dr. D. Y.Patil Institute All India Common Entrance Test for MBBS and BDS coaching classes, Dr. D. Y.Patil Institute All India Common Entrance Test for MBBS and BDS entrance exam coaching, Dr. D. Y.Patil Institute All India Common Entrance Test for MBBS and BDS coaching classes in {location}, Dr. D. Y.Patil Institute All India Common Entrance Test for MBBS and BDS coaching institutes in {location}, list of Dr. D. Y.Patil Institute All India Common Entrance Test for MBBS and BDS coaching institutes {location}'
),
883 => array(
		'url' => 'haryana-pmt-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'Haryana PMT - Haryana Pre-Medical Test coaching institutes, Haryana PMT - Haryana Pre-Medical Test coaching classes, Haryana PMT - Haryana Pre-Medical Test entrance exam coaching, Haryana PMT - Haryana Pre-Medical Test coaching classes in {location}, Haryana PMT - Haryana Pre-Medical Test coaching institutes in {location}, list of Haryana PMT - Haryana Pre-Medical Test coaching institutes {location}'
),
884 => array(
		'url' => 'hp-cpmt-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'HP CPMT - Himachal Pradesh Combined Pre Medical Entrance Test coaching institutes, HP CPMT - Himachal Pradesh Combined Pre Medical Entrance Test coaching classes, HP CPMT - Himachal Pradesh Combined Pre Medical Entrance Test entrance exam coaching, HP CPMT - Himachal Pradesh Combined Pre Medical Entrance Test coaching classes in {location}, HP CPMT - Himachal Pradesh Combined Pre Medical Entrance Test coaching institutes in {location}, list of HP CPMT - Himachal Pradesh Combined Pre Medical Entrance Test coaching institutes {location}'
),
885 => array(
		'url' => 'jipmer-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'JIPMER - Jawaharlal Institute of Post-Graduate Medical Education and Research coaching institutes, JIPMER - Jawaharlal Institute of Post-Graduate Medical Education and Research coaching classes, JIPMER - Jawaharlal Institute of Post-Graduate Medical Education and Research entrance exam coaching, JIPMER - Jawaharlal Institute of Post-Graduate Medical Education and Research coaching classes in {location}, JIPMER - Jawaharlal Institute of Post-Graduate Medical Education and Research coaching institutes in {location}, list of JIPMER - Jawaharlal Institute of Post-Graduate Medical Education and Research coaching institutes {location}'
),
886 => array(
		'url' => 'kle-ugaiet-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'KLE UGAIET - KLE University Under Graduate All India Entrance Test coaching institutes, KLE UGAIET - KLE University Under Graduate All India Entrance Test coaching classes, KLE UGAIET - KLE University Under Graduate All India Entrance Test entrance exam coaching, KLE UGAIET - KLE University Under Graduate All India Entrance Test coaching classes in {location}, KLE UGAIET - KLE University Under Graduate All India Entrance Test coaching institutes in {location}, list of KLE UGAIET - KLE University Under Graduate All India Entrance Test coaching institutes {location}'
),
887 => array(
		'url' => 'kle-pgaiet-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'KLE PGAIET - KLE University Post Graduate All India Entrance Test coaching institutes, KLE PGAIET - KLE University Post Graduate All India Entrance Test coaching classes, KLE PGAIET - KLE University Post Graduate All India Entrance Test entrance exam coaching, KLE PGAIET - KLE University Post Graduate All India Entrance Test coaching classes in {location}, KLE PGAIET - KLE University Post Graduate All India Entrance Test coaching institutes in {location}, list of KLE PGAIET - KLE University Post Graduate All India Entrance Test coaching institutes {location}'
),
888 => array(
		'url' => 'keam-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'KEAM - Kerala Engineering Architecture Medical Entrance Exam coaching institutes, KEAM - Kerala Engineering Architecture Medical Entrance Exam coaching classes, KEAM - Kerala Engineering Architecture Medical Entrance Exam entrance exam coaching, KEAM - Kerala Engineering Architecture Medical Entrance Exam coaching classes in {location}, KEAM - Kerala Engineering Architecture Medical Entrance Exam coaching institutes in {location}, list of KEAM - Kerala Engineering Architecture Medical Entrance Exam coaching institutes {location}'
),
889 => array(
		'url' => 'mgims-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'MGIMS - Mahatma Gandhi Institute Of Medical Science coaching institutes, MGIMS - Mahatma Gandhi Institute Of Medical Science coaching classes, MGIMS - Mahatma Gandhi Institute Of Medical Science entrance exam coaching, MGIMS - Mahatma Gandhi Institute Of Medical Science coaching classes in {location}, MGIMS - Mahatma Gandhi Institute Of Medical Science coaching institutes in {location}, list of MGIMS - Mahatma Gandhi Institute Of Medical Science coaching institutes {location}'
),
890 => array(
		'url' => 'maher-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'MAHER - Meenakshi Academy Of Higher Education & Research coaching institutes, MAHER - Meenakshi Academy Of Higher Education & Research coaching classes, MAHER - Meenakshi Academy Of Higher Education & Research entrance exam coaching, MAHER - Meenakshi Academy Of Higher Education & Research coaching classes in {location}, MAHER - Meenakshi Academy Of Higher Education & Research coaching institutes in {location}, list of MAHER - Meenakshi Academy Of Higher Education & Research coaching institutes {location}'
),
891 => array(
		'url' => 'manipal-pmt-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'Manipal PMT - Manipal Pre Medical Test coaching institutes, Manipal PMT - Manipal Pre Medical Test coaching classes, Manipal PMT - Manipal Pre Medical Test entrance exam coaching, Manipal PMT - Manipal Pre Medical Test coaching classes in {location}, Manipal PMT - Manipal Pre Medical Test coaching institutes in {location}, list of Manipal PMT - Manipal Pre Medical Test coaching institutes {location}'
),
892 => array(
		'url' => 'mgpgi-pgdee-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'MGPGI PGDEE - Mahatma Gandhi Postgraduate Institute of Dental Sciences coaching institutes, MGPGI PGDEE - Mahatma Gandhi Postgraduate Institute of Dental Sciences coaching classes, MGPGI PGDEE - Mahatma Gandhi Postgraduate Institute of Dental Sciences entrance exam coaching, MGPGI PGDEE - Mahatma Gandhi Postgraduate Institute of Dental Sciences coaching classes in {location}, MGPGI PGDEE - Mahatma Gandhi Postgraduate Institute of Dental Sciences coaching institutes in {location}, list of MGPGI PGDEE - Mahatma Gandhi Postgraduate Institute of Dental Sciences coaching institutes {location}'
),
893 => array(
		'url' => 'mgdch-jet-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'MGDCH JET - Mahatma Gandhi Dental College and Hospital - Joint Entrance Test coaching institutes, MGDCH JET - Mahatma Gandhi Dental College and Hospital - Joint Entrance Test coaching classes, MGDCH JET - Mahatma Gandhi Dental College and Hospital - Joint Entrance Test entrance exam coaching, MGDCH JET - Mahatma Gandhi Dental College and Hospital - Joint Entrance Test coaching classes in {location}, MGDCH JET - Mahatma Gandhi Dental College and Hospital - Joint Entrance Test coaching institutes in {location}, list of MGDCH JET - Mahatma Gandhi Dental College and Hospital - Joint Entrance Test coaching institutes {location}'
),
894 => array(
		'url' => 'mh-pgmpgdcet-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'MH PGMPGDCET - Maharashtra Post Graduate Medical, Postgraduate Dental Common Entrance Test coaching institutes, MH PGMPGDCET - Maharashtra Post Graduate Medical, Postgraduate Dental Common Entrance Test coaching classes, MH PGMPGDCET - Maharashtra Post Graduate Medical, Postgraduate Dental Common Entrance Test entrance exam coaching, MH PGMPGDCET - Maharashtra Post Graduate Medical, Postgraduate Dental Common Entrance Test coaching classes in {location}, MH PGMPGDCET - Maharashtra Post Graduate Medical, Postgraduate Dental Common Entrance Test coaching institutes in {location}, list of MH PGMPGDCET - Maharashtra Post Graduate Medical, Postgraduate Dental Common Entrance Test coaching institutes {location}'
),
895 => array(
		'url' => 'mh-sset-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'MH SSET - Maharashtra Superspeciality Entrance Test coaching institutes, MH SSET - Maharashtra Superspeciality Entrance Test coaching classes, MH SSET - Maharashtra Superspeciality Entrance Test entrance exam coaching, MH SSET - Maharashtra Superspeciality Entrance Test coaching classes in {location}, MH SSET - Maharashtra Superspeciality Entrance Test coaching institutes in {location}, list of MH SSET - Maharashtra Superspeciality Entrance Test coaching institutes {location}'
),
896 => array(
		'url' => 'mpmt-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'MPMT - Madhya Pradesh Pre-Medical Test coaching institutes, MPMT - Madhya Pradesh Pre-Medical Test coaching classes, MPMT - Madhya Pradesh Pre-Medical Test entrance exam coaching, MPMT - Madhya Pradesh Pre-Medical Test coaching classes in {location}, MPMT - Madhya Pradesh Pre-Medical Test coaching institutes in {location}, list of MPMT - Madhya Pradesh Pre-Medical Test coaching institutes {location}'
),
897 => array(
		'url' => 'pgimer-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'PGIMER - Post Graduate Institute of Medical Education & Research coaching institutes, PGIMER - Post Graduate Institute of Medical Education & Research coaching classes, PGIMER - Post Graduate Institute of Medical Education & Research entrance exam coaching, PGIMER - Post Graduate Institute of Medical Education & Research coaching classes in {location}, PGIMER - Post Graduate Institute of Medical Education & Research coaching institutes in {location}, list of PGIMER - Post Graduate Institute of Medical Education & Research coaching institutes {location}'
),
898 => array(
		'url' => 'pims-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'PIMS - Pravara Institute Of Medical Sciences Entrance Exam coaching institutes, PIMS - Pravara Institute Of Medical Sciences Entrance Exam coaching classes, PIMS - Pravara Institute Of Medical Sciences Entrance Exam entrance exam coaching, PIMS - Pravara Institute Of Medical Sciences Entrance Exam coaching classes in {location}, PIMS - Pravara Institute Of Medical Sciences Entrance Exam coaching institutes in {location}, list of PIMS - Pravara Institute Of Medical Sciences Entrance Exam coaching institutes {location}'
),
899 => array(
		'url' => 'pmet-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'PMET - Punjab Medical Entrance Test coaching institutes, PMET - Punjab Medical Entrance Test coaching classes, PMET - Punjab Medical Entrance Test entrance exam coaching, PMET - Punjab Medical Entrance Test coaching classes in {location}, PMET - Punjab Medical Entrance Test coaching institutes in {location}, list of PMET - Punjab Medical Entrance Test coaching institutes {location}'
),
900 => array(
		'url' => 'rpmt-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'RPMT - Rajasthan Pre-Medical Test coaching institutes, RPMT - Rajasthan Pre-Medical Test coaching classes, RPMT - Rajasthan Pre-Medical Test entrance exam coaching, RPMT - Rajasthan Pre-Medical Test coaching classes in {location}, RPMT - Rajasthan Pre-Medical Test coaching institutes in {location}, list of RPMT - Rajasthan Pre-Medical Test coaching institutes {location}'
),
901 => array(
		'url' => 'sri-balaji-vidyapeeth-university-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'SBVUMCET - Sri Balaji Vidyapeeth University Medical Common Entrance Test coaching institutes, SBVUMCET - Sri Balaji Vidyapeeth University Medical Common Entrance Test coaching classes, SBVUMCET - Sri Balaji Vidyapeeth University Medical Common Entrance Test entrance exam coaching, SBVUMCET - Sri Balaji Vidyapeeth University Medical Common Entrance Test coaching classes in {location}, SBVUMCET - Sri Balaji Vidyapeeth University Medical Common Entrance Test coaching institutes in {location}, list of SBVUMCET - Sri Balaji Vidyapeeth University Medical Common Entrance Test coaching institutes {location}'
),
902 => array(
		'url' => 'srm-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'SRM Institute of Science and Technology Medical Entrance Exam coaching institutes, SRM Institute of Science and Technology Medical Entrance Exam coaching classes, SRM Institute of Science and Technology Medical Entrance Exam entrance exam coaching, SRM Institute of Science and Technology Medical Entrance Exam coaching classes in {location}, SRM Institute of Science and Technology Medical Entrance Exam coaching institutes in {location}, list of SRM Institute of Science and Technology Medical Entrance Exam coaching institutes {location}'
),
903 => array(
		'url' => 'upcat-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'UPCAT - Uttar Pradesh Common Admission Test coaching institutes, UPCAT - Uttar Pradesh Common Admission Test coaching classes, UPCAT - Uttar Pradesh Common Admission Test entrance exam coaching, UPCAT - Uttar Pradesh Common Admission Test coaching classes in {location}, UPCAT - Uttar Pradesh Common Admission Test coaching institutes in {location}, list of UPCAT - Uttar Pradesh Common Admission Test coaching institutes {location}'
),
904 => array(
		'url' => 'upcmet-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'UPCMET - Uttar Pradesh Combined Medical Entrance Test coaching institutes, UPCMET - Uttar Pradesh Combined Medical Entrance Test coaching classes, UPCMET - Uttar Pradesh Combined Medical Entrance Test entrance exam coaching, UPCMET - Uttar Pradesh Combined Medical Entrance Test coaching classes in {location}, UPCMET - Uttar Pradesh Combined Medical Entrance Test coaching institutes in {location}, list of UPCMET - Uttar Pradesh Combined Medical Entrance Test coaching institutes {location}'
),
905 => array(
		'url' => 'upcpmt-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'UPCPMT - Uttar Pradesh Combined Pre Medical Test coaching institutes, UPCPMT - Uttar Pradesh Combined Pre Medical Test coaching classes, UPCPMT - Uttar Pradesh Combined Pre Medical Test entrance exam coaching, UPCPMT - Uttar Pradesh Combined Pre Medical Test coaching classes in {location}, UPCPMT - Uttar Pradesh Combined Pre Medical Test coaching institutes in {location}, list of UPCPMT - Uttar Pradesh Combined Pre Medical Test coaching institutes {location}'
),
906 => array(
		'url' => 'upmt-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'UPMT - Uttaranchal Pre-Medical Test coaching institutes, UPMT - Uttaranchal Pre-Medical Test coaching classes, UPMT - Uttaranchal Pre-Medical Test entrance exam coaching, UPMT - Uttaranchal Pre-Medical Test coaching classes in {location}, UPMT - Uttaranchal Pre-Medical Test coaching institutes in {location}, list of UPMT - Uttaranchal Pre-Medical Test coaching institutes {location}'
),
907 => array(
		'url' => 'uppgmee-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'UPPGMEE - Uttar Pradesh Post Graduate Medical Entrance Examination coaching institutes, UPPGMEE - Uttar Pradesh Post Graduate Medical Entrance Examination coaching classes, UPPGMEE - Uttar Pradesh Post Graduate Medical Entrance Examination entrance exam coaching, UPPGMEE - Uttar Pradesh Post Graduate Medical Entrance Examination coaching classes in {location}, UPPGMEE - Uttar Pradesh Post Graduate Medical Entrance Examination coaching institutes in {location}, list of UPPGMEE - Uttar Pradesh Post Graduate Medical Entrance Examination coaching institutes {location}'
),
908 => array(
		'url' => 'vinayaka-missions-university-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'Vinayaka Missions University All India Common Entrance Examination coaching institutes, Vinayaka Missions University All India Common Entrance Examination coaching classes, Vinayaka Missions University All India Common Entrance Examination entrance exam coaching, Vinayaka Missions University All India Common Entrance Examination coaching classes in {location}, Vinayaka Missions University All India Common Entrance Examination coaching institutes in {location}, list of Vinayaka Missions University All India Common Entrance Examination coaching institutes {location}'
),
909 => array(
		'url' => 'aiims-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'AIIMS - All India Institute of Medical Sciences coaching institutes, AIIMS - All India Institute of Medical Sciences coaching classes, AIIMS - All India Institute of Medical Sciences entrance exam coaching, AIIMS - All India Institute of Medical Sciences coaching classes in {location}, AIIMS - All India Institute of Medical Sciences coaching institutes in {location}, list of AIIMS - All India Institute of Medical Sciences coaching institutes {location}'
),
910 => array(
		'url' => 'afmc-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'AFMC - Armed Forces Medical College Exam coaching institutes, AFMC - Armed Forces Medical College Exam coaching classes, AFMC - Armed Forces Medical College Exam entrance exam coaching, AFMC - Armed Forces Medical College Exam coaching classes in {location}, AFMC - Armed Forces Medical College Exam coaching institutes in {location}, list of AFMC - Armed Forces Medical College Exam coaching institutes {location}'
),
911 => array(
		'url' => 'aipmt-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'AIPMT - All India Pre-Medical/Dental Entrance Examination coaching institutes, AIPMT - All India Pre-Medical/Dental Entrance Examination coaching classes, AIPMT - All India Pre-Medical/Dental Entrance Examination entrance exam coaching, AIPMT - All India Pre-Medical/Dental Entrance Examination coaching classes in {location}, AIPMT - All India Pre-Medical/Dental Entrance Examination coaching institutes in {location}, list of AIPMT - All India Pre-Medical/Dental Entrance Examination coaching institutes {location}'
),
912 => array(
		'url' => 'aipgdet-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'AIPGDET - All India Post Graduate Dental Entrance Test coaching institutes, AIPGDET - All India Post Graduate Dental Entrance Test coaching classes, AIPGDET - All India Post Graduate Dental Entrance Test entrance exam coaching, AIPGDET - All India Post Graduate Dental Entrance Test coaching classes in {location}, AIPGDET - All India Post Graduate Dental Entrance Test coaching institutes in {location}, list of AIPGDET - All India Post Graduate Dental Entrance Test coaching institutes {location}'
),
913 => array(
		'url' => 'aipgmet-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'AIPGMET - All India Post Graduate Medical Entrance Test coaching institutes, AIPGMET - All India Post Graduate Medical Entrance Test coaching classes, AIPGMET - All India Post Graduate Medical Entrance Test entrance exam coaching, AIPGMET - All India Post Graduate Medical Entrance Test coaching classes in {location}, AIPGMET - All India Post Graduate Medical Entrance Test coaching institutes in {location}, list of AIPGMET - All India Post Graduate Medical Entrance Test coaching institutes {location}'
),
914 => array(
		'url' => 'aipvt-medical-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'AIPVT - All India Pre Veterinart Test coaching institutes, AIPVT - All India Pre Veterinart Test coaching classes, AIPVT - All India Pre Veterinart Test entrance exam coaching, AIPVT - All India Pre Veterinart Test coaching classes in {location}, AIPVT - All India Pre Veterinart Test coaching institutes in {location}, list of AIPVT - All India Pre Veterinart Test coaching institutes {location}'
),
915 => array(
		'url' => 'svu-lawcet-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'SVU LAWCET coaching institutes, SVU LAWCET coaching classes, SVU LAWCET entrance exam coaching, SVU LAWCET coaching classes in {location}, SVU LAWCET coaching institutes in {location}, list of SVU LAWCET coaching institutes {location}'
),
916 => array(
		'url' => 'rulet-law-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'RULET coaching institutes, RULET coaching classes, RULET entrance exam coaching, RULET coaching classes in {location}, RULET coaching institutes in {location}, list of RULET coaching institutes {location}'
),
917 => array(
		'url' => 'ilsat-law-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'ILSAT coaching institutes, ILSAT coaching classes, ILSAT entrance exam coaching, ILSAT coaching classes in {location}, ILSAT coaching institutes in {location}, list of ILSAT coaching institutes {location}'
),
918 => array(
		'url' => 'klsat-law-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'KLSAT coaching institutes, KLSAT coaching classes, KLSAT entrance exam coaching, KLSAT coaching classes in {location}, KLSAT coaching institutes in {location}, list of KLSAT coaching institutes {location}'
),
919 => array(
		'url' => 'amu-law-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'AMU Law Entrance Exam coaching institutes, AMU Law Entrance Exam coaching classes, AMU Law Entrance Exam entrance exam coaching, AMU Law Entrance Exam coaching classes in {location}, AMU Law Entrance Exam coaching institutes in {location}, list of AMU Law Entrance Exam coaching institutes {location}'
),
920 => array(
		'url' => 'lawcet-law-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'LAWCET coaching institutes, LAWCET coaching classes, LAWCET entrance exam coaching, LAWCET coaching classes in {location}, LAWCET coaching institutes in {location}, list of LAWCET coaching institutes {location}'
),
921 => array(
		'url' => 'ilicat-law-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'ILICAT coaching institutes, ILICAT coaching classes, ILICAT entrance exam coaching, ILICAT coaching classes in {location}, ILICAT coaching institutes in {location}, list of ILICAT coaching institutes {location}'
),
922 => array(
		'url' => 'ailet-law-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'AILET coaching institutes, AILET coaching classes, AILET entrance exam coaching, AILET coaching classes in {location}, AILET coaching institutes in {location}, list of AILET coaching institutes {location}'
),
923 => array(
		'url' => 'kerala-law-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'Kerala Law coaching institutes, Kerala Law coaching classes, Kerala Law entrance exam coaching, Kerala Law coaching classes in {location}, Kerala Law coaching institutes in {location}, list of Kerala Law coaching institutes {location}'
),
924 => array(
		'url' => 'clat-law-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'CLAT coaching institutes, CLAT coaching classes, CLAT entrance exam coaching, CLAT coaching classes in {location}, CLAT coaching institutes in {location}, list of CLAT coaching institutes {location}'
),
925 => array(
		'url' => 'klee-law-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'KLEE coaching institutes, KLEE coaching classes, KLEE entrance exam coaching, KLEE coaching classes in {location}, KLEE coaching institutes in {location}, list of KLEE coaching institutes {location}'
),
926 => array(
		'url' => 'gndu-cet-law-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'GNDU CET coaching institutes, GNDU CET coaching classes, GNDU CET entrance exam coaching, GNDU CET coaching classes in {location}, GNDU CET coaching institutes in {location}, list of GNDU CET coaching institutes {location}'
),
927 => array(
		'url' => 'gre-exams-coaching-classes-in-{location}',
		'keywords' => 'GRE coaching institutes, GRE coaching classes, GRE entrance exam coaching, GRE coaching classes in {location}, GRE coaching institutes in {location}, list of GRE coaching institutes {location}'
),
928 => array(
		'url' => 'gmat-exams-coaching-classes-in-{location}',
		'keywords' => 'GMAT coaching institutes, GMAT coaching classes, GMAT entrance exam coaching, GMAT coaching classes in {location}, GMAT coaching institutes in {location}, list of GMAT coaching institutes {location}'
),
929 => array(
		'url' => 'toefl-exams-coaching-classes-in-{location}',
		'keywords' => 'TOEFL coaching institutes, TOEFL coaching classes, TOEFL entrance exam coaching, TOEFL coaching classes in {location}, TOEFL coaching institutes in {location}, list of TOEFL coaching institutes {location}'
),
930 => array(
		'url' => 'ielts-exams-coaching-classes-in-{location}',
		'keywords' => 'IELTS coaching institutes, IELTS coaching classes, IELTS entrance exam coaching, IELTS coaching classes in {location}, IELTS coaching institutes in {location}, list of IELTS coaching institutes {location}'
),
931 => array(
		'url' => 'sat-exams-coaching-classes-in-{location}',
		'keywords' => 'SAT coaching institutes, SAT coaching classes, SAT entrance exam coaching, SAT coaching classes in {location}, SAT coaching institutes in {location}, list of SAT coaching institutes {location}'
),
932 => array(
		'url' => 'lsat-exams-coaching-classes-in-{location}',
		'keywords' => 'LSAT coaching institutes, LSAT coaching classes, LSAT entrance exam coaching, LSAT coaching classes in {location}, LSAT coaching institutes in {location}, list of LSAT coaching institutes {location}'
),
933 => array(
		'url' => 'usmle-exams-coaching-classes-in-{location}',
		'keywords' => 'USMLE coaching institutes, USMLE coaching classes, USMLE entrance exam coaching, USMLE coaching classes in {location}, USMLE coaching institutes in {location}, list of USMLE coaching institutes {location}'
),
934 => array(
		'url' => 'bcom-coaching-in-{Location}',
		'keywords' => 'B.Com Coaching coaching institutes, B.Com Coaching coaching classes, B.Com Coaching entrance exam coaching, B.Com Coaching coaching classes in {location}, B.Com Coaching coaching institutes in {location}, list of B.Com Coaching coaching institutes {location}'
),
935 => array(
		'url' => 'bsc-coaching-in-{Location}',
		'keywords' => 'B.Sc Coaching coaching institutes, B.Sc Coaching coaching classes, B.Sc Coaching entrance exam coaching, B.Sc Coaching coaching classes in {location}, B.Sc Coaching coaching institutes in {location}, list of B.Sc Coaching coaching institutes {location}'
),
936 => array(
		'url' => 'ias-exam-coaching-classes-in-{location}',
		'keywords' => 'UPSC - Indian Administrative Services (IAS) coaching institutes, UPSC - Indian Administrative Services (IAS) coaching classes, UPSC - Indian Administrative Services (IAS) entrance exam coaching, UPSC - Indian Administrative Services (IAS) coaching classes in {location}, UPSC - Indian Administrative Services (IAS) coaching institutes in {location}, list of UPSC - Indian Administrative Services (IAS) coaching institutes {location}'
),
937 => array(
		'url' => 'bank-po-coaching-classes-in-{location}',
		'keywords' => 'BPOE - Bank Probationary Officers Examination coaching institutes, BPOE - Bank Probationary Officers Examination coaching classes, BPOE - Bank Probationary Officers Examination entrance exam coaching, BPOE - Bank Probationary Officers Examination coaching classes in {location}, BPOE - Bank Probationary Officers Examination coaching institutes in {location}, list of BPOE - Bank Probationary Officers Examination coaching institutes {location}'
),
938 => array(
		'url' => 'ssc-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'SSC - Staff Selection Commission coaching institutes, SSC - Staff Selection Commission coaching classes, SSC - Staff Selection Commission entrance exam coaching, SSC - Staff Selection Commission coaching classes in {location}, SSC - Staff Selection Commission coaching institutes in {location}, list of SSC - Staff Selection Commission coaching institutes {location}'
),
939 => array(
		'url' => 'net-entrance-exams-coaching-classes-in-{location}',
		'keywords' => 'NET coaching institutes, NET coaching classes, NET entrance exam coaching, NET coaching classes in {location}, NET coaching institutes in {location}, list of NET coaching institutes {location}'
),
940 => array(
		'url' => 'Full Time MBA/PGDM-colleges-in-{location}',
		'keywords' => ''
),
941 => array(
		'url' => 'Phd-colleges-in-{location}',
		'keywords' => ''
),
942 => array(
		'url' => 'Diploma-colleges-in-{location}',
		'keywords' => ''
),
943 => array(
		'url' => 'BE/Btech / Bachelor-colleges-in-{location}',
		'keywords' => ''
),
944 => array(
		'url' => 'MS / Masters-colleges-in-{location}',
		'keywords' => ''
),
945 => array(
		'url' => 'Animation-colleges-in-{location}',
		'keywords' => ''
),
946 => array(
		'url' => 'Game Design and Development-colleges-in-{location}',
		'keywords' => ''
),
947 => array(
		'url' => 'Graphic Designing-colleges-in-{location}',
		'keywords' => ''
),
948 => array(
		'url' => 'Mobile App / Game design-colleges-in-{location}',
		'keywords' => ''
),
949 => array(
		'url' => 'Multimedia Courses-colleges-in-{location}',
		'keywords' => ''
),
950 => array(
		'url' => 'Web Designing-colleges-in-{location}',
		'keywords' => ''
),
951 => array(
		'url' => 'Comic Design-colleges-in-{location}',
		'keywords' => ''
),
952 => array(
		'url' => 'Animation-colleges-in-{location}',
		'keywords' => ''
),
953 => array(
		'url' => 'Game Design and Development-colleges-in-{location}',
		'keywords' => ''
),
954 => array(
		'url' => 'Graphic Designing-colleges-in-{location}',
		'keywords' => ''
),
955 => array(
		'url' => 'Mobile App / Game design-colleges-in-{location}',
		'keywords' => ''
),
956 => array(
		'url' => 'Multimedia Courses-colleges-in-{location}',
		'keywords' => ''
),
957 => array(
		'url' => 'Web Designing-colleges-in-{location}',
		'keywords' => ''
),
958 => array(
		'url' => 'Comic Design-colleges-in-{location}',
		'keywords' => ''
),
959 => array(
		'url' => 'Acting-colleges-in-{location}',
		'keywords' => ''
),
960 => array(
		'url' => 'Advertising & PR-colleges-in-{location}',
		'keywords' => ''
),
961 => array(
		'url' => 'Event Management-colleges-in-{location}',
		'keywords' => ''
),
962 => array(
		'url' => 'Film making & Production-colleges-in-{location}',
		'keywords' => ''
),
963 => array(
		'url' => 'Journalism-colleges-in-{location}',
		'keywords' => ''
),
964 => array(
		'url' => 'Mass Communication-colleges-in-{location}',
		'keywords' => ''
),
965 => array(
		'url' => 'Media-colleges-in-{location}',
		'keywords' => ''
),
966 => array(
		'url' => 'Photography-colleges-in-{location}',
		'keywords' => ''
),
967 => array(
		'url' => 'Acting-colleges-in-{location}',
		'keywords' => ''
),
968 => array(
		'url' => 'Advertising & PR-colleges-in-{location}',
		'keywords' => ''
),
969 => array(
		'url' => 'Event Management-colleges-in-{location}',
		'keywords' => ''
),
970 => array(
		'url' => 'Film making & Production-colleges-in-{location}',
		'keywords' => ''
),
971 => array(
		'url' => 'Journalism-colleges-in-{location}',
		'keywords' => ''
),
972 => array(
		'url' => 'Mass Communication-colleges-in-{location}',
		'keywords' => ''
),
973 => array(
		'url' => 'Media-colleges-in-{location}',
		'keywords' => ''
),
974 => array(
		'url' => 'Photography-colleges-in-{location}',
		'keywords' => ''
),
975 => array(
		'url' => 'bba-bbm-colleges-in-{location}',
		'keywords' => ''
),
976 => array(
		'url' => 'certification-courses-in-{Location}',
		'keywords' => ''
),
977 => array(
		'url' => 'online-mba-in-{Location}',
		'keywords' => ''
),
978 => array(
		'url' => 'executive-mba-in-{Location}',
		'keywords' => ''
),
979 => array(
		'url' => 'mba-in-Operations-in-{location}',
		'keywords' => ''
),
980 => array(
		'url' => 'mba-in-General Management-in-{location}',
		'keywords' => ''
),
981 => array(
		'url' => 'mba-in-Marketing-in-{location}',
		'keywords' => ''
),
982 => array(
		'url' => 'mba-in-Finance-in-{location}',
		'keywords' => ''
),
983 => array(
		'url' => 'mba-in-Human Resources-in-{location}',
		'keywords' => ''
),
984 => array(
		'url' => 'mba-in-IT-in-{location}',
		'keywords' => ''
),
985 => array(
		'url' => 'mba-in-International Business-in-{location}',
		'keywords' => ''
),
986 => array(
		'url' => 'mba-in-Import/Export-in-{location}',
		'keywords' => ''
),
987 => array(
		'url' => 'mba-in-Retail-in-{location}',
		'keywords' => ''
),
988 => array(
		'url' => 'mba-in-Health Care-in-{location}',
		'keywords' => ''
),
989 => array(
		'url' => 'mba-in-BioTechnology-in-{location}',
		'keywords' => ''
),
990 => array(
		'url' => 'mba-in-Telecom Management-in-{location}',
		'keywords' => ''
),
991 => array(
		'url' => 'mba-in-Infrastructure & Development-in-{location}',
		'keywords' => ''
),
992 => array(
		'url' => 'mba-in-Hospitality Management-in-{location}',
		'keywords' => ''
),
994 => array(
		'url' => 'mba-in-Accounting-in-{location}',
		'keywords' => ''
),
995 => array(
		'url' => 'mba-in-Aviation-in-{location}',
		'keywords' => ''
),
996 => array(
		'url' => 'integrated-btech-mba-in-{Location}',
		'keywords' => ''
),
997 => array(
		'url' => 'integrated-bba-mba-in-{Location}',
		'keywords' => ''
),
998 => array(
		'url' => 'Retail-colleges-in-{location}',
		'keywords' => ''
),
999 => array(
		'url' => 'Retail-colleges-in-{location}',
		'keywords' => ''
),
1000 => array(
		'url' => 'GRE-colleges-in-{location}',
		'keywords' => ''
),
1001 => array(
		'url' => 'GMAT-colleges-in-{location}',
		'keywords' => ''
),
1002 => array(
		'url' => 'TOEFL-colleges-in-{location}',
		'keywords' => ''
),
1003 => array(
		'url' => 'IELTS-colleges-in-{location}',
		'keywords' => ''
),
1004 => array(
		'url' => 'SAT-colleges-in-{location}',
		'keywords' => ''
),
1005 => array(
		'url' => 'LSAT-colleges-in-{location}',
		'keywords' => ''
),
1006 => array(
		'url' => 'USMLE-colleges-in-{location}',
		'keywords' => ''
),
1007 => array(
		'url' => 'Arts & Humanities-colleges-in-{location}',
		'keywords' => ''
),
1008 => array(
		'url' => 'Law-colleges-in-{location}',
		'keywords' => ''
),
1009 => array(
		'url' => 'Teaching-colleges-in-{location}',
		'keywords' => ''
),
1010 => array(
		'url' => 'Creative/Commercial/Performing Arts-colleges-in-{location}',
		'keywords' => ''
),
1011 => array(
		'url' => 'Social Sciences-colleges-in-{location}',
		'keywords' => ''
),
1012 => array(
		'url' => 'Government Sector-colleges-in-{location}',
		'keywords' => ''
),
1013 => array(
		'url' => 'Language Learning-colleges-in-{location}',
		'keywords' => ''
),
1014 => array(
		'url' => 'Religious Studies-colleges-in-{location}',
		'keywords' => ''
),
1015 => array(
		'url' => 'Culinary Art-colleges-in-{location}',
		'keywords' => ''
),
1016 => array(
		'url' => 'Arts & Humanities-colleges-in-{location}',
		'keywords' => ''
),
1017 => array(
		'url' => 'Law-colleges-in-{location}',
		'keywords' => ''
),
1018 => array(
		'url' => 'Teaching-colleges-in-{location}',
		'keywords' => ''
),
1019 => array(
		'url' => 'Creative/Commercial/Performing Arts-colleges-in-{location}',
		'keywords' => ''
),
1020 => array(
		'url' => 'Social Sciences-colleges-in-{location}',
		'keywords' => ''
),
1021 => array(
		'url' => 'Government Sector-colleges-in-{location}',
		'keywords' => ''
),
1022 => array(
		'url' => 'Language Learning-colleges-in-{location}',
		'keywords' => ''
),
1023 => array(
		'url' => 'Religious Studies-colleges-in-{location}',
		'keywords' => ''
),
1024 => array(
		'url' => 'Culinary Art-colleges-in-{location}',
		'keywords' => ''
),
1025 => array(
		'url' => 'phd-in-Aeronautical/Aerospace Engineering-in-{location}',
		'keywords' => ''
),
1026 => array(
		'url' => 'phd-in-Agricultural Engineering-in-{location}',
		'keywords' => ''
),
1027 => array(
		'url' => 'phd-in-Automobile Engineering-in-{location}',
		'keywords' => ''
),
1028 => array(
		'url' => 'phd-in-Avionics Engineering-in-{location}',
		'keywords' => ''
),
1029 => array(
		'url' => 'phd-in-Chemical Engineering-in-{location}',
		'keywords' => ''
),
1030 => array(
		'url' => 'phd-in-Civil Engineering-in-{location}',
		'keywords' => ''
),
1031 => array(
		'url' => 'phd-in-Communications Engineering-in-{location}',
		'keywords' => ''
),
1032 => array(
		'url' => 'phd-in-Computer Science Engineering-in-{location}',
		'keywords' => ''
),
1033 => array(
		'url' => 'phd-in-Control Systems Engineering-in-{location}',
		'keywords' => ''
),
1034 => array(
		'url' => 'phd-in-Electrical Engineering-in-{location}',
		'keywords' => ''
),
1035 => array(
		'url' => 'phd-in-Electronics Engineering-in-{location}',
		'keywords' => ''
),
1036 => array(
		'url' => 'phd-in-Industrial Engineering-in-{location}',
		'keywords' => ''
),
1037 => array(
		'url' => 'phd-in-Information Technology (IT)-in-{location}',
		'keywords' => ''
),
1038 => array(
		'url' => 'phd-in-Instrumentation Engineering-in-{location}',
		'keywords' => ''
),
1039 => array(
		'url' => 'phd-in-Marine Engineering-in-{location}',
		'keywords' => ''
),
1040 => array(
		'url' => 'phd-in-Mechanical Engineering-in-{location}',
		'keywords' => ''
),
1041 => array(
		'url' => 'phd-in-Mechatronics Engineering-in-{location}',
		'keywords' => ''
),
1042 => array(
		'url' => 'phd-in-Metallurgical Engineering-in-{location}',
		'keywords' => ''
),
1043 => array(
		'url' => 'phd-in-Mining Engineering-in-{location}',
		'keywords' => ''
),
1044 => array(
		'url' => 'phd-in-Nanotechnology-in-{location}',
		'keywords' => ''
),
1045 => array(
		'url' => 'phd-in-Power Systems Engineering-in-{location}',
		'keywords' => ''
),
1046 => array(
		'url' => 'phd-in-Signal Processing-in-{location}',
		'keywords' => ''
),
1047 => array(
		'url' => 'phd-in-Solar & Alternative Engineering-in-{location}',
		'keywords' => ''
),
1048 => array(
		'url' => 'phd-in-Telecommunications Engineering-in-{location}',
		'keywords' => ''
),
1049 => array(
		'url' => 'phd-in-Textile Engineering-in-{location}',
		'keywords' => ''
),
1050 => array(
		'url' => 'phd-in-VLSI-in-{location}',
		'keywords' => ''
),
1051 => array(
		'url' => 'phd-in-Wireless Communication-in-{location}',
		'keywords' => ''
),
1052 => array(
		'url' => 'phd-in-Physics-in-{location}',
		'keywords' => ''
),
1053 => array(
		'url' => 'phd-in-Chemistry-in-{location}',
		'keywords' => ''
),
1054 => array(
		'url' => 'phd-in-Maths-in-{location}',
		'keywords' => ''
),
1055 => array(
		'url' => 'phd-in-Biology-in-{location}',
		'keywords' => ''
),
1056 => array(
		'url' => 'phd-in-Agriculture & Forestry-in-{location}',
		'keywords' => ''
),
1057 => array(
		'url' => 'phd-in-Bio-Technology-in-{location}',
		'keywords' => ''
),
1058 => array(
		'url' => 'diploma-in-Aeronautical/Aerospace Engineering-in-{location}',
		'keywords' => ''
),
1059 => array(
		'url' => 'diploma-in-Agricultural Engineering-in-{location}',
		'keywords' => ''
),
1060 => array(
		'url' => 'diploma-in-Automobile Engineering-in-{location}',
		'keywords' => ''
),
1061 => array(
		'url' => 'diploma-in-Avionics Engineering-in-{location}',
		'keywords' => ''
),
1062 => array(
		'url' => 'diploma-in-Chemical Engineering-in-{location}',
		'keywords' => ''
),
1063 => array(
		'url' => 'diploma-in-Civil Engineering-in-{location}',
		'keywords' => ''
),
1064 => array(
		'url' => 'diploma-in-Communications Engineering-in-{location}',
		'keywords' => ''
),
1065 => array(
		'url' => 'diploma-in-Computer Science Engineering-in-{location}',
		'keywords' => ''
),
1066 => array(
		'url' => 'diploma-in-Control Systems Engineering-in-{location}',
		'keywords' => ''
),
1067 => array(
		'url' => 'diploma-in-Electrical Engineering-in-{location}',
		'keywords' => ''
),
1068 => array(
		'url' => 'diploma-in-Electronics Engineering-in-{location}',
		'keywords' => ''
),
1069 => array(
		'url' => 'diploma-in-Industrial Engineering-in-{location}',
		'keywords' => ''
),
1070 => array(
		'url' => 'diploma-in-Information Technology (IT)-in-{location}',
		'keywords' => ''
),
1071 => array(
		'url' => 'diploma-in-Instrumentation Engineering-in-{location}',
		'keywords' => ''
),
1072 => array(
		'url' => 'diploma-in-Marine Engineering-in-{location}',
		'keywords' => ''
),
1073 => array(
		'url' => 'diploma-in-Mechanical Engineering-in-{location}',
		'keywords' => ''
),
1074 => array(
		'url' => 'diploma-in-Mechatronics Engineering-in-{location}',
		'keywords' => ''
),
1075 => array(
		'url' => 'diploma-in-Metallurgical Engineering-in-{location}',
		'keywords' => ''
),
1076 => array(
		'url' => 'diploma-in-Mining Engineering-in-{location}',
		'keywords' => ''
),
1077 => array(
		'url' => 'diploma-in-Nanotechnology-in-{location}',
		'keywords' => ''
),
1078 => array(
		'url' => 'diploma-in-Power Systems Engineering-in-{location}',
		'keywords' => ''
),
1079 => array(
		'url' => 'diploma-in-Signal Processing-in-{location}',
		'keywords' => ''
),
1080 => array(
		'url' => 'diploma-in-Solar & Alternative Engineering-in-{location}',
		'keywords' => ''
),
1081 => array(
		'url' => 'diploma-in-Telecommunications Engineering-in-{location}',
		'keywords' => ''
),
1082 => array(
		'url' => 'diploma-in-Textile Engineering-in-{location}',
		'keywords' => ''
),
1083 => array(
		'url' => 'diploma-in-VLSI-in-{location}',
		'keywords' => ''
),
1084 => array(
		'url' => 'diploma-in-Wireless Communication-in-{location}',
		'keywords' => ''
),
1085 => array(
		'url' => 'diploma-in-Physics-in-{location}',
		'keywords' => ''
),
1086 => array(
		'url' => 'diploma-in-Chemistry-in-{location}',
		'keywords' => ''
),
1087 => array(
		'url' => 'diploma-in-Maths-in-{location}',
		'keywords' => ''
),
1088 => array(
		'url' => 'diploma-in-Biology-in-{location}',
		'keywords' => ''
),
1089 => array(
		'url' => 'diploma-in-Agriculture & Forestry-in-{location}',
		'keywords' => ''
),
1090 => array(
		'url' => 'diploma-in-Bio-Technology-in-{location}',
		'keywords' => ''
),
1091 => array(
		'url' => 'advanced-diploma-in-Aeronautical/Aerospace Engineering-in-{location}',
		'keywords' => ''
),
1092 => array(
		'url' => 'advanced-diploma-in-Agricultural Engineering-in-{location}',
		'keywords' => ''
),
1093 => array(
		'url' => 'advanced-diploma-in-Automobile Engineering-in-{location}',
		'keywords' => ''
),
1094 => array(
		'url' => 'advanced-diploma-in-Avionics Engineering-in-{location}',
		'keywords' => ''
),
1095 => array(
		'url' => 'advanced-diploma-in-Chemical Engineering-in-{location}',
		'keywords' => ''
),
1096 => array(
		'url' => 'advanced-diploma-in-Civil Engineering-in-{location}',
		'keywords' => ''
),
1097 => array(
		'url' => 'advanced-diploma-in-Communications Engineering-in-{location}',
		'keywords' => ''
),
1098 => array(
		'url' => 'advanced-diploma-in-Computer Science Engineering-in-{location}',
		'keywords' => ''
),
1099 => array(
		'url' => 'advanced-diploma-in-Control Systems Engineering-in-{location}',
		'keywords' => ''
),
1100 => array(
		'url' => 'advanced-diploma-in-Electrical Engineering-in-{location}',
		'keywords' => ''
),
1101 => array(
		'url' => 'advanced-diploma-in-Electronics Engineering-in-{location}',
		'keywords' => ''
),
1102 => array(
		'url' => 'advanced-diploma-in-Industrial Engineering-in-{location}',
		'keywords' => ''
),
1103 => array(
		'url' => 'advanced-diploma-in-Information Technology (IT)-in-{location}',
		'keywords' => ''
),
1104 => array(
		'url' => 'advanced-diploma-in-Instrumentation Engineering-in-{location}',
		'keywords' => ''
),
1105 => array(
		'url' => 'advanced-diploma-in-Marine Engineering-in-{location}',
		'keywords' => ''
),
1106 => array(
		'url' => 'advanced-diploma-in-Mechanical Engineering-in-{location}',
		'keywords' => ''
),
1107 => array(
		'url' => 'advanced-diploma-in-Mechatronics Engineering-in-{location}',
		'keywords' => ''
),
1108 => array(
		'url' => 'advanced-diploma-in-Metallurgical Engineering-in-{location}',
		'keywords' => ''
),
1109 => array(
		'url' => 'advanced-diploma-in-Mining Engineering-in-{location}',
		'keywords' => ''
),
1110 => array(
		'url' => 'advanced-diploma-in-Nanotechnology-in-{location}',
		'keywords' => ''
),
1111 => array(
		'url' => 'advanced-diploma-in-Power Systems Engineering-in-{location}',
		'keywords' => ''
),
1112 => array(
		'url' => 'advanced-diploma-in-Signal Processing-in-{location}',
		'keywords' => ''
),
1113 => array(
		'url' => 'advanced-diploma-in-Solar & Alternative Engineering-in-{location}',
		'keywords' => ''
),
1114 => array(
		'url' => 'advanced-diploma-in-Telecommunications Engineering-in-{location}',
		'keywords' => ''
),
1115 => array(
		'url' => 'advanced-diploma-in-Textile Engineering-in-{location}',
		'keywords' => ''
),
1116 => array(
		'url' => 'advanced-diploma-in-VLSI-in-{location}',
		'keywords' => ''
),
1117 => array(
		'url' => 'advanced-diploma-in-Wireless Communication-in-{location}',
		'keywords' => ''
),
1118 => array(
		'url' => 'advanced-diploma-in-Physics-in-{location}',
		'keywords' => ''
),
1119 => array(
		'url' => 'advanced-diploma-in-Chemistry-in-{location}',
		'keywords' => ''
),
1120 => array(
		'url' => 'advanced-diploma-in-Maths-in-{location}',
		'keywords' => ''
),
1121 => array(
		'url' => 'advanced-diploma-in-Biology-in-{location}',
		'keywords' => ''
),
1122 => array(
		'url' => 'advanced-diploma-in-Agriculture & Forestry-in-{location}',
		'keywords' => ''
),
1123 => array(
		'url' => 'advanced-diploma-in-Bio-Technology-in-{location}',
		'keywords' => ''
),
1124 => array(
		'url' => 'Aeronautical/Aerospace Engineering-in-{location}',
		'keywords' => ''
),
1125 => array(
		'url' => 'Agricultural Engineering-in-{location}',
		'keywords' => ''
),
1126 => array(
		'url' => 'Automobile Engineering-in-{location}',
		'keywords' => ''
),
1127 => array(
		'url' => 'Avionics Engineering-in-{location}',
		'keywords' => ''
),
1128 => array(
		'url' => 'Chemical Engineering-in-{location}',
		'keywords' => ''
),
1129 => array(
		'url' => 'Civil Engineering-in-{location}',
		'keywords' => ''
),
1130 => array(
		'url' => 'Communications Engineering-in-{location}',
		'keywords' => ''
),
1131 => array(
		'url' => 'Computer Science Engineering-in-{location}',
		'keywords' => ''
),
1132 => array(
		'url' => 'Control Systems Engineering-in-{location}',
		'keywords' => ''
),
1133 => array(
		'url' => 'Electrical Engineering-in-{location}',
		'keywords' => ''
),
1134 => array(
		'url' => 'Electronics Engineering-in-{location}',
		'keywords' => ''
),
1135 => array(
		'url' => 'Industrial Engineering-in-{location}',
		'keywords' => ''
),
1136 => array(
		'url' => 'Information Technology (IT)-in-{location}',
		'keywords' => ''
),
1137 => array(
		'url' => 'Instrumentation Engineering-in-{location}',
		'keywords' => ''
),
1138 => array(
		'url' => 'Marine Engineering-in-{location}',
		'keywords' => ''
),
1139 => array(
		'url' => 'Mechanical Engineering-in-{location}',
		'keywords' => ''
),
1140 => array(
		'url' => 'Mechatronics Engineering-in-{location}',
		'keywords' => ''
),
1141 => array(
		'url' => 'Metallurgical Engineering-in-{location}',
		'keywords' => ''
),
1142 => array(
		'url' => 'Mining Engineering-in-{location}',
		'keywords' => ''
),
1143 => array(
		'url' => 'Nanotechnology-in-{location}',
		'keywords' => ''
),
1144 => array(
		'url' => 'Power Systems Engineering-in-{location}',
		'keywords' => ''
),
1145 => array(
		'url' => 'Signal Processing-in-{location}',
		'keywords' => ''
),
1146 => array(
		'url' => 'Solar & Alternative Engineering-in-{location}',
		'keywords' => ''
),
1147 => array(
		'url' => 'Telecommunications Engineering-in-{location}',
		'keywords' => ''
),
1148 => array(
		'url' => 'Textile Engineering-in-{location}',
		'keywords' => ''
),
1149 => array(
		'url' => 'VLSI-in-{location}',
		'keywords' => ''
),
1150 => array(
		'url' => 'Wireless Communication-in-{location}',
		'keywords' => ''
),
1151 => array(
		'url' => 'Physics-in-{location}',
		'keywords' => ''
),
1152 => array(
		'url' => 'Chemistry-in-{location}',
		'keywords' => ''
),
1153 => array(
		'url' => 'Maths-in-{location}',
		'keywords' => ''
),
1154 => array(
		'url' => 'Biology-in-{location}',
		'keywords' => ''
),
1155 => array(
		'url' => 'Agriculture & Forestry-in-{location}',
		'keywords' => ''
),
1156 => array(
		'url' => 'Bio-Technology-in-{location}',
		'keywords' => ''
),
1157 => array(
		'url' => 'ms-in-Aeronautical/Aerospace Engineering-in-{location}',
		'keywords' => ''
),
1158 => array(
		'url' => 'ms-in-Agricultural Engineering-in-{location}',
		'keywords' => ''
),
1159 => array(
		'url' => 'ms-in-Automobile Engineering-in-{location}',
		'keywords' => ''
),
1160 => array(
		'url' => 'ms-in-Avionics Engineering-in-{location}',
		'keywords' => ''
),
1161 => array(
		'url' => 'ms-in-Chemical Engineering-in-{location}',
		'keywords' => ''
),
1162 => array(
		'url' => 'ms-in-Civil Engineering-in-{location}',
		'keywords' => ''
),
1163 => array(
		'url' => 'ms-in-Communications Engineering-in-{location}',
		'keywords' => ''
),
1164 => array(
		'url' => 'ms-in-Computer Science Engineering-in-{location}',
		'keywords' => ''
),
1165 => array(
		'url' => 'ms-in-Control Systems Engineering-in-{location}',
		'keywords' => ''
),
1166 => array(
		'url' => 'ms-in-Electrical Engineering-in-{location}',
		'keywords' => ''
),
1167 => array(
		'url' => 'ms-in-Electronics Engineering-in-{location}',
		'keywords' => ''
),
1168 => array(
		'url' => 'ms-in-Industrial Engineering-in-{location}',
		'keywords' => ''
),
1169 => array(
		'url' => 'ms-in-Information Technology (IT)-in-{location}',
		'keywords' => ''
),
1170 => array(
		'url' => 'ms-in-Instrumentation Engineering-in-{location}',
		'keywords' => ''
),
1171 => array(
		'url' => 'ms-in-Marine Engineering-in-{location}',
		'keywords' => ''
),
1172 => array(
		'url' => 'ms-in-Mechanical Engineering-in-{location}',
		'keywords' => ''
),
1173 => array(
		'url' => 'ms-in-Mechatronics Engineering-in-{location}',
		'keywords' => ''
),
1174 => array(
		'url' => 'ms-in-Metallurgical Engineering-in-{location}',
		'keywords' => ''
),
1175 => array(
		'url' => 'ms-in-Mining Engineering-in-{location}',
		'keywords' => ''
),
1176 => array(
		'url' => 'ms-in-Nanotechnology-in-{location}',
		'keywords' => ''
),
1177 => array(
		'url' => 'ms-in-Power Systems Engineering-in-{location}',
		'keywords' => ''
),
1178 => array(
		'url' => 'ms-in-Signal Processing-in-{location}',
		'keywords' => ''
),
1179 => array(
		'url' => 'ms-in-Solar & Alternative Engineering-in-{location}',
		'keywords' => ''
),
1180 => array(
		'url' => 'ms-in-Telecommunications Engineering-in-{location}',
		'keywords' => ''
),
1181 => array(
		'url' => 'ms-in-Textile Engineering-in-{location}',
		'keywords' => ''
),
1182 => array(
		'url' => 'ms-in-VLSI-in-{location}',
		'keywords' => ''
),
1183 => array(
		'url' => 'ms-in-Wireless Communication-in-{location}',
		'keywords' => ''
),
1184 => array(
		'url' => 'ms-in-Physics-in-{location}',
		'keywords' => ''
),
1185 => array(
		'url' => 'ms-in-Chemistry-in-{location}',
		'keywords' => ''
),
1186 => array(
		'url' => 'ms-in-Maths-in-{location}',
		'keywords' => ''
),
1187 => array(
		'url' => 'ms-in-Biology-in-{location}',
		'keywords' => ''
),
1188 => array(
		'url' => 'ms-in-Agriculture & Forestry-in-{location}',
		'keywords' => ''
),
1189 => array(
		'url' => 'ms-in-Bio-Technology-in-{location}',
		'keywords' => ''
),
1190 => array(
		'url' => 'Architecture-colleges-in-{location}',
		'keywords' => ''
),
1191 => array(
		'url' => 'Aircraft Maintenance Engineering-colleges-in-{location}',
		'keywords' => ''
),
1192 => array(
		'url' => 'Marine Engineering-colleges-in-{location}',
		'keywords' => ''
),
1193 => array(
		'url' => 'Architecture-colleges-in-{location}',
		'keywords' => ''
),
1194 => array(
		'url' => 'Aircraft Maintenance Engineering-colleges-in-{location}',
		'keywords' => ''
),
1195 => array(
		'url' => 'Marine Engineering-colleges-in-{location}',
		'keywords' => ''
),
1196 => array(
		'url' => 'Fashion & Textile Design-colleges-in-{location}',
		'keywords' => ''
),
1197 => array(
		'url' => 'Interior Design-colleges-in-{location}',
		'keywords' => ''
),
1198 => array(
		'url' => 'Jewellery & Accessory Design-colleges-in-{location}',
		'keywords' => ''
),
1199 => array(
		'url' => 'Industrial, Automotive, Product Design-colleges-in-{location}',
		'keywords' => ''
),
1200 => array(
		'url' => 'Interaction Design-colleges-in-{location}',
		'keywords' => ''
),
1201 => array(
		'url' => 'Fashion & Textile Design-colleges-in-{location}',
		'keywords' => ''
),
1202 => array(
		'url' => 'Interior Design-colleges-in-{location}',
		'keywords' => ''
),
1203 => array(
		'url' => 'Jewellery & Accessory Design-colleges-in-{location}',
		'keywords' => ''
),
1204 => array(
		'url' => 'Industrial, Automotive, Product Design-colleges-in-{location}',
		'keywords' => ''
),
1205 => array(
		'url' => 'Interaction Design-colleges-in-{location}',
		'keywords' => ''
),
1206 => array(
		'url' => 'Accounting-colleges-in-{location}',
		'keywords' => ''
),
1207 => array(
		'url' => 'Finance-colleges-in-{location}',
		'keywords' => ''
),
1208 => array(
		'url' => 'Insurance-colleges-in-{location}',
		'keywords' => ''
),
1209 => array(
		'url' => 'CFA, CPA, CIMA-colleges-in-{location}',
		'keywords' => ''
),
1210 => array(
		'url' => 'Commerce-colleges-in-{location}',
		'keywords' => ''
),
1211 => array(
		'url' => 'Accounting-colleges-in-{location}',
		'keywords' => ''
),
1212 => array(
		'url' => 'Finance-colleges-in-{location}',
		'keywords' => ''
),
1213 => array(
		'url' => 'Insurance-colleges-in-{location}',
		'keywords' => ''
),
1214 => array(
		'url' => 'CFA, CPA, CIMA-colleges-in-{location}',
		'keywords' => ''
),
1215 => array(
		'url' => 'Commerce-colleges-in-{location}',
		'keywords' => ''
),
1216 => array(
		'url' => 'Hotel Management-colleges-in-{location}',
		'keywords' => ''
),
1217 => array(
		'url' => 'Event Management-colleges-in-{location}',
		'keywords' => ''
),
1218 => array(
		'url' => 'Tourism Management-colleges-in-{location}',
		'keywords' => ''
),
1219 => array(
		'url' => 'Aviation-colleges-in-{location}',
		'keywords' => ''
),
1220 => array(
		'url' => 'Aircraft Maintenance Engineering-colleges-in-{location}',
		'keywords' => ''
),
1221 => array(
		'url' => 'Hotel Management-colleges-in-{location}',
		'keywords' => ''
),
1222 => array(
		'url' => 'Event Management-colleges-in-{location}',
		'keywords' => ''
),
1223 => array(
		'url' => 'Tourism Management-colleges-in-{location}',
		'keywords' => ''
),
1224 => array(
		'url' => 'Aviation-colleges-in-{location}',
		'keywords' => ''
),
1225 => array(
		'url' => 'Aircraft Maintenance Engineering-colleges-in-{location}',
		'keywords' => ''
),
1226 => array(
		'url' => 'M.Sc CS/IT-colleges-in-{location}',
		'keywords' => ''
),
1227 => array(
		'url' => 'B.Sc CS/IT-colleges-in-{location}',
		'keywords' => ''
),
1228 => array(
		'url' => 'Diploma in IT-colleges-in-{location}',
		'keywords' => ''
),
1229 => array(
		'url' => 'Microsoft Certifications-colleges-in-{location}',
		'keywords' => ''
),
1230 => array(
		'url' => 'Six Sigma Certification-colleges-in-{location}',
		'keywords' => ''
),
1231 => array(
		'url' => 'Cisco Certifications-colleges-in-{location}',
		'keywords' => ''
),
1232 => array(
		'url' => 'Game Design and Development-colleges-in-{location}',
		'keywords' => ''
),
1233 => array(
		'url' => 'M.Sc CS/IT-colleges-in-{location}',
		'keywords' => ''
),
1234 => array(
		'url' => 'B.Sc CS/IT-colleges-in-{location}',
		'keywords' => ''
),
1235 => array(
		'url' => 'Diploma in IT-colleges-in-{location}',
		'keywords' => ''
),
1236 => array(
		'url' => 'Microsoft Certifications-colleges-in-{location}',
		'keywords' => ''
),
1237 => array(
		'url' => 'Six Sigma Certification-colleges-in-{location}',
		'keywords' => ''
),
1238 => array(
		'url' => 'Cisco Certifications-colleges-in-{location}',
		'keywords' => ''
),
1239 => array(
		'url' => 'Game Design and Development-colleges-in-{location}',
		'keywords' => ''
),
1240 => array(
		'url' => 'Beauty, Cosmetology, Hair Dressing & Makeup-colleges-in-{location}',
		'keywords' => ''
),
1241 => array(
		'url' => 'Clinical Research (CR)-colleges-in-{location}',
		'keywords' => ''
),
1242 => array(
		'url' => 'Clinical Trials-colleges-in-{location}',
		'keywords' => ''
),
1243 => array(
		'url' => 'Dental Surgery-colleges-in-{location}',
		'keywords' => ''
),
1244 => array(
		'url' => 'Healthcare  & Hospital Management-colleges-in-{location}',
		'keywords' => ''
),
1245 => array(
		'url' => 'MBBS-colleges-in-{location}',
		'keywords' => ''
),
1246 => array(
		'url' => 'MD-colleges-in-{location}',
		'keywords' => ''
),
1247 => array(
		'url' => 'Microbiology-colleges-in-{location}',
		'keywords' => ''
),
1248 => array(
		'url' => 'Biotechnology-colleges-in-{location}',
		'keywords' => ''
),
1249 => array(
		'url' => 'Nursing-colleges-in-{location}',
		'keywords' => ''
),
1250 => array(
		'url' => 'Pharmacovigilance-colleges-in-{location}',
		'keywords' => ''
),
1251 => array(
		'url' => 'Pharmacy-colleges-in-{location}',
		'keywords' => ''
),
1252 => array(
		'url' => 'Medical Transcription-colleges-in-{location}',
		'keywords' => ''
),
1253 => array(
		'url' => 'Physiotherapy-colleges-in-{location}',
		'keywords' => ''
),
1254 => array(
		'url' => 'Paramedical-colleges-in-{location}',
		'keywords' => ''
),
1255 => array(
		'url' => 'Radiology-colleges-in-{location}',
		'keywords' => ''
),
1256 => array(
		'url' => 'Alternate Medicine-colleges-in-{location}',
		'keywords' => ''
),
1257 => array(
		'url' => 'Medical Lab Technology-colleges-in-{location}',
		'keywords' => ''
),
1259 => array(
		'url' => 'Beauty, Cosmetology, Hair Dressing & Makeup-colleges-in-{location}',
		'keywords' => ''
),
1260 => array(
		'url' => 'Clinical Research (CR)-colleges-in-{location}',
		'keywords' => ''
),
1261 => array(
		'url' => 'Clinical Trials-colleges-in-{location}',
		'keywords' => ''
),
1262 => array(
		'url' => 'Dental Surgery-colleges-in-{location}',
		'keywords' => ''
),
1263 => array(
		'url' => 'Healthcare  & Hospital Management-colleges-in-{location}',
		'keywords' => ''
),
1266 => array(
		'url' => 'Microbiology-colleges-in-{location}',
		'keywords' => ''
),
1267 => array(
		'url' => 'Biotechnology-colleges-in-{location}',
		'keywords' => ''
),
1268 => array(
		'url' => 'Nursing-colleges-in-{location}',
		'keywords' => ''
),
1269 => array(
		'url' => 'Pharmacovigilance-colleges-in-{location}',
		'keywords' => ''
),
1270 => array(
		'url' => 'Pharmacy-colleges-in-{location}',
		'keywords' => ''
),
1271 => array(
		'url' => 'Medical Transcription-colleges-in-{location}',
		'keywords' => ''
),
1272 => array(
		'url' => 'Physiotherapy-colleges-in-{location}',
		'keywords' => ''
),
1273 => array(
		'url' => 'Paramedical-colleges-in-{location}',
		'keywords' => ''
),
1274 => array(
		'url' => 'Radiology-colleges-in-{location}',
		'keywords' => ''
),
1275 => array(
		'url' => 'Alternate Medicine-colleges-in-{location}',
		'keywords' => ''
),
1276 => array(
		'url' => 'Medical Lab Technology-colleges-in-{location}',
		'keywords' => ''
),
1278 => array(
		'url' => 'life-insurance-agents-license-renewal-course',
		'keywords' => 'Life Insurance Agent\'s License Renewal Course in {Location}, Life Insurance Agent\'s License Renewal Course, Diploma Courses, Diploma in Banking in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
1279 => array(
		'url' => 'life-insurance-agent-pre-licensing-course',
		'keywords' => 'Life Insurance Agent\'s Pre-licensing Course in {Location}, Life Insurance Agent\'s Pre-licensing Course, Diploma Courses, Diploma in Banking in {Location}, Diploma in {Location}, universities, institutes, career, career options, Education'
),
1322 => array(
		'url' => 'executive-mba-in-Operations-in-{location}',
		'keywords' => ''
),
1323 => array(
		'url' => 'executive-mba-in-General Management-in-{location}',
		'keywords' => ''
),
1324 => array(
		'url' => 'executive-mba-in-Marketing-in-{location}',
		'keywords' => ''
),
1325 => array(
		'url' => 'executive-mba-in-Finance-in-{location}',
		'keywords' => ''
),
1326 => array(
		'url' => 'executive-mba-in-Human Resources-in-{location}',
		'keywords' => ''
),
1327 => array(
		'url' => 'executive-mba-in-IT-in-{location}',
		'keywords' => ''
),
1328 => array(
		'url' => 'executive-mba-in-International Business-in-{location}',
		'keywords' => ''
),
1329 => array(
		'url' => 'executive-mba-in-Import/Export-in-{location}',
		'keywords' => ''
),
1330 => array(
		'url' => 'executive-mba-in-Retail-in-{location}',
		'keywords' => ''
),
1331 => array(
		'url' => 'executive-mba-in-Health Care-in-{location}',
		'keywords' => ''
),
1332 => array(
		'url' => 'executive-mba-in-BioTechnology-in-{location}',
		'keywords' => ''
),
1333 => array(
		'url' => 'executive-mba-in-Telecom Management-in-{location}',
		'keywords' => ''
),
1334 => array(
		'url' => 'executive-mba-in-Infrastructure & Development-in-{location}',
		'keywords' => ''
),
1335 => array(
		'url' => 'executive-mba-in-Hospitality Management-in-{location}',
		'keywords' => ''
),
1336 => array(
		'url' => 'executive-mba-in-Retail-in-{location}',
		'keywords' => ''
),
1337 => array(
		'url' => 'executive-mba-in-Accounting-in-{location}',
		'keywords' => ''
),
1338 => array(
		'url' => 'executive-mba-in-Aviation-in-{location}',
		'keywords' => ''
),
1339 => array(
		'url' => 'online-mba-in-Operations-in-{location}',
		'keywords' => ''
),
1340 => array(
		'url' => 'online-mba-in-General Management-in-{location}',
		'keywords' => ''
),
1341 => array(
		'url' => 'online-mba-in-Marketing-in-{location}',
		'keywords' => ''
),
1342 => array(
		'url' => 'online-mba-in-Finance-in-{location}',
		'keywords' => ''
),
1343 => array(
		'url' => 'online-mba-in-Human Resources-in-{location}',
		'keywords' => ''
),
1344 => array(
		'url' => 'online-mba-in-IT-in-{location}',
		'keywords' => ''
),
1345 => array(
		'url' => 'online-mba-in-International Business-in-{location}',
		'keywords' => ''
),
1346 => array(
		'url' => 'online-mba-in-Import/Export-in-{location}',
		'keywords' => ''
),
1347 => array(
		'url' => 'online-mba-in-Retail-in-{location}',
		'keywords' => ''
),
1348 => array(
		'url' => 'online-mba-in-Health Care-in-{location}',
		'keywords' => ''
),
1349 => array(
		'url' => 'online-mba-in-BioTechnology-in-{location}',
		'keywords' => ''
),
1350 => array(
		'url' => 'online-mba-in-Telecom Management-in-{location}',
		'keywords' => ''
),
1351 => array(
		'url' => 'online-mba-in-Infrastructure & Development-in-{location}',
		'keywords' => ''
),
1352 => array(
		'url' => 'online-mba-in-Hospitality Management-in-{location}',
		'keywords' => ''
),
1353 => array(
		'url' => 'online-mba-in-Retail-in-{location}',
		'keywords' => ''
),
1354 => array(
		'url' => 'online-mba-in-Accounting-in-{location}',
		'keywords' => ''
),
1355 => array(
		'url' => 'online-mba-in-Aviation-in-{location}',
		'keywords' => ''
),
1356 => array(
		'url' => 'Certification-in-Finance-in-{location}',
		'keywords' => ''
),
1357 => array(
		'url' => 'Certification-in-IT-in-{location}',
		'keywords' => ''
),
1358 => array(
		'url' => 'Certification-in-HR-in-{location}',
		'keywords' => ''
),
1359 => array(
		'url' => 'Certification-in-Marketing-in-{location}',
		'keywords' => ''
),
1360 => array(
		'url' => 'Certification-in-International Business-in-{location}',
		'keywords' => ''
),
1361 => array(
		'url' => 'Certification-in-Operations-in-{location}',
		'keywords' => ''
),
1362 => array(
		'url' => 'Certification-in-Logistics-in-{location}',
		'keywords' => ''
),
1363 => array(
		'url' => 'Certification-in-Sales-in-{location}',
		'keywords' => ''
),
1364 => array(
		'url' => 'Certification-in-Safety Management-in-{location}',
		'keywords' => ''
),
1365 => array(
		'url' => 'Certification-in-Six Sigma Certifications-in-{location}',
		'keywords' => ''
),
1366 => array(
		'url' => 'Certification-in-Export Import-in-{location}',
		'keywords' => ''
),
1367 => array(
		'url' => 'Certification-in-Quality Management-in-{location}',
		'keywords' => ''
),

2 => array(
		'url' => 'mba-colleges-in-{Location}',
		'keywords' => 'MBA Colleges in {Location}, {Location} MBA Colleges, List of MBA Colleges in {Location}, MBA in {Location}, Types of MBA Programs, MBA courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
4 => array(
		'url' => 'mba-in-general-management-in-{location}',
		'keywords' => 'MBA in General Management in {Location}, {Location} MBA in General Management Colleges, List of MBA in General Management in {Location}, MBA in {Location}, Types of MBA Programs, MBA courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
10 => array(
		'url' => 'mba-in-import-export-courses-in-{location}',
		'keywords' => 'MBA in Import Export in {Location}, {Location} MBA in Import Export Colleges, List of MBA in Import Export in {Location}, MBA in {Location}, Types of MBA Programs, MBA courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
11 => array(
		'url' => 'mba-in-retail-management-in-{location}',
		'keywords' => 'MBA in Retail in {Location}, {Location} MBA in Retail Colleges, List of MBA in Retail in {Location}, MBA in {Location}, Types of MBA Programs, MBA courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
13 => array(
		'url' => 'executive-mba-colleges-in-{location}',
		'title' => 'Executive MBA Colleges in {Location} - shiksha.com',
		'description' => 'Search Executive MBA  Colleges in {Location} - Get a list of all Executive MBA  Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'Executive MBA in {Location}, {Location} Executive MBA Colleges, List of Executive MBA Colleges in {Location}, Executive MBA in {Location}, Types of Executive MBA Programs, Executive MBA courses, Business Schools in {Location}, Institutes in {Location}, Executive MBA courses, colleges, education'
),
15 => array(
		'url' => 'executive-mba-general-management-courses-in-{location}',
		'title' => 'Executive MBA in General Management Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Executive MBA in General Management colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Executive MBA in General Management in {Location}, Executive MBA in General Management courses, List of Executive MBA in General Management in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
21 => array(
		'url' => 'executive-mba-import-export-courses-in-{location}',
		'title' => 'Executive MBA in Import Export Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Executive MBA in Import Export colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Executive MBA in Import Export in {Location}, Executive MBA in Import Export courses, List of Executive MBA in Import Export in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
22 => array(
		'url' => 'executive-mba-retail-management-in-{location}',
		'title' => 'Executive MBA in Retail in {Location} - shiksha.com',
		'description' => 'Search Executive MBA in Retail in {Location} - Get a list of all Executive MBA in Retail Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'Executive MBA in Retail in {Location}, Executive MBA in Retail courses, List of Executive MBA in Retail in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
24 => array(
		'url' => 'distance-mba-colleges-in-{location}',
		'title' => 'Distance MBA Colleges in {Location} - shiksha.com',
		'description' => 'Search Distance MBA  Colleges in {Location} - Get a list of all Distance MBA  in Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'Distance MBA in {Location}, {Location} Distance MBA Colleges, List of Distance MBA Colleges in {Location}, Distance MBA in {Location}, Types of Distance MBA Programs, Distance MBA courses, Business Schools in {Location}, Institutes in {Location}, Distance MBA courses, colleges, education'
),
25 => array(
		'url' => 'distance-mba-in-general-management-in-{location}',
		'title' => 'Distance/Correspondence MBA in General Management Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Distance/Correspondence MBA in General Management colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Distance MBA in General Management in {Location}, Distance MBA in General Management courses, List of Distance MBA in General Management in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
26 => array(
		'url' => 'distance-mba-in-finance-in-{location}',
		'title' => 'Distance/Correspondence MBA in Finance Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Distance/Correspondence MBA in Finance colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Distance MBA in Finance in {Location}, Distance MBA in Finance courses, List of Distance MBA in Finance in {Location}, MBA in {Location}, Types of MBA Programs, mba courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
46 => array(
		'url' => 'bca-colleges-in-{location}',
		'keywords' => 'BCA Colleges in {Location}, BCA Courses in {Location}, BCA Colleges, BCA Courses,universities, institutes, career, career options, Education'
),
47 => array(
		'url' => 'mca-colleges-in-{location}',
		'keywords' => 'MCA Colleges in {Location}, MCA Courses in {Location}, MCA Colleges, MCA Courses,universities, institutes, career, career options, Education'
),
48 => array(
		'url' => 'bsc-it-colleges-in-{location}',
		'keywords' => 'BSc IT Colleges in {Location}, BSc IT Courses in {Location}, BSc IT Colleges, BSc IT Courses,universities, institutes, career, career options, Education'
),
49 => array(
		'url' => 'msc-it-colleges-in-{location}',
		'keywords' => 'MSc IT Colleges in {Location}, MSc IT Courses in {Location}, MSc IT Colleges, MSc IT Courses,universities, institutes, career, career options, Education'
),
50 => array(
		'url' => 'bsc-cs-colleges-in-{location}',
		'keywords' => 'BSc CS Colleges in {Location}, BSc CS Courses in {Location}, BSc CS Colleges, BSc CS Courses,universities, institutes, career, career options, Education'
),
51 => array(
		'url' => 'msc-cs-colleges-in-{location}',
		'keywords' => 'MSc CS Colleges in {Location}, MSc CS Courses in {Location}, MSc CS Colleges, MSc CS Courses,universities, institutes, career, career options, Education'
),
52 => array(
		'url' => 'btech-colleges-in-{location}',
		'keywords' => 'B.Tech Colleges in {Location}, B.Tech Courses in {Location}, B.Tech Colleges, B.Tech Courses,universities, institutes, career, career options, Education'
),
53 => array(
		'url' => 'me-mtech-colleges-in-{location}',
		'keywords' => 'M.E M.Tech Colleges in {Location}, M.E M.Tech Courses in {Location}, M.E M.Tech Colleges, M.E M.Tech Courses,universities, institutes, career, career options, Education'
),
55 => array(
		'url' => 'cplusplus-certification-training-institutes-in-{location}',
		'keywords' => 'C++ Certification Training Institutes In {Location}, C++ Certification Courses,C++ Certification Courses, C++ Certification Training, universities, institutes, career, career options, Education'
),
56 => array(
		'url' => 'vcplusplus-certification-training-institutes-in-{location}',
		'keywords' => 'VC++ Certification Training Institutes In {Location}, VC++ Certification Courses,VC++ Certification Courses, VC++ Certification Training, universities, institutes, career, career options, Education'
),
58 => array(
		'url' => 'javascript-certification-training-institutes-in-{location}',
		'keywords' => 'Javascript Certification Training Institutes In {Location}, Javascript Certification Courses,Javascript Certification Courses, Javascript Certification Training, universities, institutes, career, career options, Education'
),
63 => array(
		'url' => 'adobe-flex-certification-training-institutes-in-{location}',
		'keywords' => 'Adobe Flex Certification Training Institutes In {Location}, Adobe Flex Certification Courses,Adobe Flex Certification Courses, Adobe Flex Certification Training, universities, institutes, career, career options, Education'
),
64 => array(
		'url' => 'ajax-certification-training-institutes-in-{location}',
		'keywords' => 'AJAX Certification Training Institutes In {Location}, AJAX Certification Courses,AJAX Certification Courses, AJAX Certification Training, universities, institutes, career, career options, Education'
),
65 => array(
		'url' => 'ejb-certification-training-institutes-in-{location}',
		'keywords' => 'EJB Certification Training Institutes In {Location}, EJB Certification Courses,EJB Certification Courses, EJB Certification Training, universities, institutes, career, career options, Education'
),
66 => array(
		'url' => 'hibernate-certification-training-institutes-in-{location}',
		'keywords' => 'Hibernate Certification Training Institutes In {Location}, Hibernate Certification Courses,Hibernate Certification Courses, Hibernate Certification Training, universities, institutes, career, career options, Education'
),
67 => array(
		'url' => 'perl-certification-training-institutes-in-{location}',
		'keywords' => 'Perl Certification Training Institutes In {Location}, Perl Certification Courses,Perl Certification Courses, Perl Certification Training, universities, institutes, career, career options, Education'
),
68 => array(
		'url' => 'ror-ruby-on-rails-certification-training-institutes-in-{location}',
		'keywords' => 'ROR Ruby On Rails Certification Training Institutes In {Location}, ROR Ruby On Rails Certification Courses,ROR Ruby On Rails Certification Courses, ROR Ruby On Rails Certification Training, universities, institutes, career, career options, Education'
),
69 => array(
		'url' => 'shellscripting-certification-training-institutes-in-{location}',
		'keywords' => 'Shell Scripting Certification Training Institutes In {Location}, Shell Scripting Certification Courses,Shell Scripting Certification Courses, Shell Scripting Certification Training, universities, institutes, career, career options, Education'
),
70 => array(
		'url' => 'spring-certification-training-institutes-in-{location}',
		'keywords' => 'Spring Certification Training Institutes In {Location}, Spring Certification Courses,Spring Certification Courses, Spring Certification Training, universities, institutes, career, career options, Education'
),
71 => array(
		'url' => 'struts-certification-training-institutes-in-{location}',
		'keywords' => 'Struts Certification Training Institutes In {Location}, Struts Certification Courses,Struts Certification Courses, Struts Certification Training, universities, institutes, career, career options, Education'
),
72 => array(
		'url' => 'python-certification-training-institutes-in-{location}',
		'keywords' => 'Python Certification Training Institutes In {Location}, Python Certification Courses,Python Certification Courses, Python Certification Training, universities, institutes, career, career options, Education'
),
76 => array(
		'url' => 'ccna-security-certification-training-institutes-in-{location}',
		'keywords' => 'CCNA Security Certification Training Institutes In {Location}, CCNA Security Certification Courses,CCNA Security Certification Courses, CCNA Security Certification Training, universities, institutes, career, career options, Education'
),
77 => array(
		'url' => 'ccna-voice-certification-training-institutes-in-{location}',
		'keywords' => 'CCNA Voice Certification Training Institutes In {Location}, CCNA Voice Certification Courses,CCNA Voice Certification Courses, CCNA Voice Certification Training, universities, institutes, career, career options, Education'
),
78 => array(
		'url' => 'ccna-wireless-certification-training-institutes-in-{location}',
		'keywords' => 'CCNA Wireless Certification Training Institutes In {Location}, CCNA Wireless Certification Courses,CCNA Wireless Certification Courses, CCNA Wireless Certification Training, universities, institutes, career, career options, Education'
),
85 => array(
		'url' => 'ccde-certification-training-institutes-in-{location}',
		'keywords' => 'CCDE Certification Training Institutes In {Location}, CCDE Certification Courses,CCDE Certification Courses, CCDE Certification Training, universities, institutes, career, career options, Education'
),
86 => array(
		'url' => 'ccie-security-certification-training-institutes-in-{location}',
		'keywords' => 'CCIE Security Certification Training Institutes In {Location}, CCIE Security Certification Courses,CCIE Security Certification Courses, CCIE Security Certification Training, universities, institutes, career, career options, Education'
),
87 => array(
		'url' => 'ccie-service-provider-certification-training-institutes-in-{location}',
		'keywords' => 'CCIE Service Provider Certification Training Institutes In {Location}, CCIE Service Provider Certification Courses,CCIE Service Provider Certification Courses, CCIE Service Provider Certification Training, universities, institutes, career, career options, Education'
),
88 => array(
		'url' => 'ccie-storage-networking-certification-training-institutes-in-{location}',
		'keywords' => 'CCIE Storage Networking Certification Training Institutes In {Location}, CCIE Storage Networking Certification Courses,CCIE Storage Networking Certification Courses, CCIE Storage Networking Certification Training, universities, institutes, career, career options, Education'
),
89 => array(
		'url' => 'ccie-voice-certification-training-institutes-in-{location}',
		'keywords' => 'CCIE Voice Certification Training Institutes In {Location}, CCIE Voice Certification Courses,CCIE Voice Certification Courses, CCIE Voice Certification Training, universities, institutes, career, career options, Education'
),
90 => array(
		'url' => 'ccie-wireless-certification-training-institutes-in-{location}',
		'keywords' => 'CCIE Wireless Certification Training Institutes In {Location}, CCIE Wireless Certification Courses,CCIE Wireless Certification Courses, CCIE Wireless Certification Training, universities, institutes, career, career options, Education'
),
97 => array(
		'url' => 'mcdst-certification-training-institutes-in-{location}',
		'keywords' => 'MCDST Certification Training Institutes In {Location}, MCDST Certification Courses,MCDST Certification Courses, MCDST Certification Training, universities, institutes, career, career options, Education'
),
102 => array(
		'url' => 'doeacc-b-level-course-certification-training-institutes-in-{location}',
		'keywords' => 'DOEACC B Level Course Certification Training Institutes In {Location}, DOEACC B Level Course Certification Courses,DOEACC B Level Course Certification Courses, DOEACC B Level Course Certification Training, universities, institutes, career, career options, Education'
),
107 => array(
		'url' => 'rhcds-certification-training-institutes-in-{location}',
		'keywords' => 'RHCDS Certification Training Institutes In {Location}, RHCDS Certification Courses,RHCDS Certification Courses, RHCDS Certification Training, universities, institutes, career, career options, Education'
),
109 => array(
		'url' => 'scja-certification-training-institutes-in-{location}',
		'keywords' => 'SCJA Certification Training Institutes In {Location}, SCJA Certification Courses,SCJA Certification Courses, SCJA Certification Training, universities, institutes, career, career options, Education'
),
110 => array(
		'url' => 'scjp-certification-training-institutes-in-{location}',
		'keywords' => 'SCJP Certification Training Institutes In {Location}, SCJP Certification Courses,SCJP Certification Courses, SCJP Certification Training, universities, institutes, career, career options, Education'
),
111 => array(
		'url' => 'scjd-certification-training-institutes-in-{location}',
		'keywords' => 'SCJD Certification Training Institutes In {Location}, SCJD Certification Courses,SCJD Certification Courses, SCJD Certification Training, universities, institutes, career, career options, Education'
),
112 => array(
		'url' => 'scwcd-certification-training-institutes-in-{location}',
		'keywords' => 'SCWCD Certification Training Institutes In {Location}, SCWCD Certification Courses,SCWCD Certification Courses, SCWCD Certification Training, universities, institutes, career, career options, Education'
),
114 => array(
		'url' => 'ocp-certification-training-institutes-in-{location}',
		'keywords' => 'OCP Certification Training Institutes In {Location}, OCP Certification Courses,OCP Certification Courses, OCP Certification Training, universities, institutes, career, career options, Education'
),
116 => array(
		'url' => 'database-management-certification-training-institutes-in-{location}',
		'keywords' => 'Database Management Certification Training Institutes In {Location}, Database Management Certification Courses,Database Management Certification Courses, Database Management Certification Training, universities, institutes, career, career options, Education'
),
119 => array(
		'url' => 'autodesk-maya-certification-training-institutes-in-{location}',
		'keywords' => 'Autodesk MAYA Certification Training Institutes In {Location}, Autodesk MAYA Certification Courses,Autodesk MAYA Certification Courses, Autodesk MAYA Certification Training, universities, institutes, career, career options, Education'
),
120 => array(
		'url' => 'autodesk-revit-certification-training-institutes-in-{location}',
		'keywords' => 'Autodesk Revit Certification Training Institutes In {Location}, Autodesk Revit Certification Courses,Autodesk Revit Certification Courses, Autodesk Revit Certification Training, universities, institutes, career, career options, Education'
),
121 => array(
		'url' => 'cad-cam-certification-training-institutes-in-{location}',
		'keywords' => 'CAD/CAM Certification Training Institutes In {Location}, CAD/CAM Certification Courses,CAD/CAM Certification Courses, CAD/CAM Certification Training, universities, institutes, career, career options, Education'
),
122 => array(
		'url' => 'delcam-certification-training-institutes-in-{location}',
		'keywords' => 'Delcam Certification Training Institutes In {Location}, Delcam Certification Courses,Delcam Certification Courses, Delcam Certification Training, universities, institutes, career, career options, Education'
),
123 => array(
		'url' => 'master-cam-certification-training-institutes-in-{location}',
		'keywords' => 'Master CAM Certification Training Institutes In {Location}, Master CAM Certification Courses,Master CAM Certification Courses, Master CAM Certification Training, universities, institutes, career, career options, Education'
),
124 => array(
		'url' => 'pro-e-certification-training-institutes-in-{location}',
		'keywords' => 'Pro/E Certification Training Institutes In {Location}, Pro/E Certification Courses,Pro/E Certification Courses, Pro/E Certification Training, universities, institutes, career, career options, Education'
),
125 => array(
		'url' => 'aplus-certification-training-institutes-in-{location}',
		'keywords' => 'A+ Certification Training Institutes In {Location}, A+ Certification Courses,A+ Certification Courses, A+ Certification Training, universities, institutes, career, career options, Education'
),
126 => array(
		'url' => 'apmg-certification-training-institutes-in-{location}',
		'keywords' => 'APMG Certification Training Institutes In {Location}, APMG Certification Courses,APMG Certification Courses, APMG Certification Training, universities, institutes, career, career options, Education'
),
129 => array(
		'url' => 'chip-level-repair-course-certification-training-institutes-in-{location}',
		'keywords' => 'Chip Level Repair Course Certification Training Institutes In {Location}, Chip Level Repair Course Certification Courses,Chip Level Repair Course Certification Courses, Chip Level Repair Course Certification Training, universities, institutes, career, career options, Education'
),
130 => array(
		'url' => 'embedded-systems-certification-training-institutes-in-{location}',
		'keywords' => 'Embedded Systems Certification Training Institutes In {Location}, Embedded Systems Certification Courses,Embedded Systems Certification Courses, Embedded Systems Certification Training, universities, institutes, career, career options, Education'
),
131 => array(
		'url' => 'achnp-certification-training-institutes-in-{location}',
		'keywords' => 'ACHNP Certification Training Institutes In {Location}, ACHNP Certification Courses,ACHNP Certification Courses, ACHNP Certification Training, universities, institutes, career, career options, Education'
),
132 => array(
		'url' => 'ibm-mainframe-certification-training-institutes-in-{location}',
		'keywords' => 'IBM MainFrame Certification Training Institutes In {Location}, IBM MainFrame Certification Courses,IBM MainFrame Certification Courses, IBM MainFrame Certification Training, universities, institutes, career, career options, Education'
),
133 => array(
		'url' => 'ibm-as-400-certification-training-institutes-in-{location}',
		'keywords' => 'IBM AS/400 Certification Training Institutes In {Location}, IBM AS/400 Certification Courses,IBM AS/400 Certification Courses, IBM AS/400 Certification Training, universities, institutes, career, career options, Education'
),
134 => array(
		'url' => 'ieee-projects-certification-training-institutes-in-{location}',
		'keywords' => 'IEEE Projects Certification Training Institutes In {Location}, IEEE Projects Certification Courses,IEEE Projects Certification Courses, IEEE Projects Certification Training, universities, institutes, career, career options, Education'
),
135 => array(
		'url' => 'itil-certification-training-institutes-in-{location}',
		'keywords' => 'ITIL Certification Training Institutes In {Location}, ITIL Certification Courses,ITIL Certification Courses, ITIL Certification Training, universities, institutes, career, career options, Education'
),
136 => array(
		'url' => 'jde-certification-training-institutes-in-{location}',
		'keywords' => 'JDE Certification Training Institutes In {Location}, JDE Certification Courses,JDE Certification Courses, JDE Certification Training, universities, institutes, career, career options, Education'
),
137 => array(
		'url' => 'jsf-certification-training-institutes-in-{location}',
		'keywords' => 'JSF Certification Training Institutes In {Location}, JSF Certification Courses,JSF Certification Courses, JSF Certification Training, universities, institutes, career, career options, Education'
),
138 => array(
		'url' => 'lamp pro-certification-training-institutes-in-{location}',
		'keywords' => 'LAMP Pro Certification Training Institutes In {Location}, LAMP Pro Certification Courses,LAMP Pro Certification Courses, LAMP Pro Certification Training, universities, institutes, career, career options, Education'
),
139 => array(
		'url' => 'linux-certification-training-institutes-in-{location}',
		'keywords' => 'Linux Certification Training Institutes In {Location}, Linux Certification Courses,Linux Certification Courses, Linux Certification Training, universities, institutes, career, career options, Education'
),
140 => array(
		'url' => 'middleware-technologies-certification-training-institutes-in-{location}',
		'keywords' => 'Middleware Technologies Certification Training Institutes In {Location}, Middleware Technologies Certification Courses,Middleware Technologies Certification Courses, Middleware Technologies Certification Training, universities, institutes, career, career options, Education'
),
141 => array(
		'url' => 'ms-biz-talk-server-certification-training-institutes-in-{location}',
		'keywords' => 'MS Biz Talk Server Certification Training Institutes In {Location}, MS Biz Talk Server Certification Courses,MS Biz Talk Server Certification Courses, MS Biz Talk Server Certification Training, universities, institutes, career, career options, Education'
),
142 => array(
		'url' => 'ms-bi-ssas-certification-training-institutes-in-{location}',
		'keywords' => 'MS BI SSAS Certification Training Institutes In {Location}, MS BI SSAS Certification Courses,MS BI SSAS Certification Courses, MS BI SSAS Certification Training, universities, institutes, career, career options, Education'
),
143 => array(
		'url' => 'ms-bi-ssis-certification-training-institutes-in-{location}',
		'keywords' => 'MS BI SSIS Certification Training Institutes In {Location}, MS BI SSIS Certification Courses,MS BI SSIS Certification Courses, MS BI SSIS Certification Training, universities, institutes, career, career options, Education'
),
144 => array(
		'url' => 'ms-bi-ssrs-certification-training-institutes-in-{location}',
		'keywords' => 'MS BI SSRS Certification Training Institutes In {Location}, MS BI SSRS Certification Courses,MS BI SSRS Certification Courses, MS BI SSRS Certification Training, universities, institutes, career, career options, Education'
),
145 => array(
		'url' => 'ms-exchange-certification-training-institutes-in-{location}',
		'keywords' => 'MS Exchange Certification Training Institutes In {Location}, MS Exchange Certification Courses,MS Exchange Certification Courses, MS Exchange Certification Training, universities, institutes, career, career options, Education'
),
146 => array(
		'url' => 'ms-office-certification-training-institutes-in-{location}',
		'keywords' => 'MS Office Certification Training Institutes In {Location}, MS Office Certification Courses,MS Office Certification Courses, MS Office Certification Training, universities, institutes, career, career options, Education'
),
147 => array(
		'url' => 'ms-project-certification-training-institutes-in-{location}',
		'keywords' => 'MS Project Certification Training Institutes In {Location}, MS Project Certification Courses,MS Project Certification Courses, MS Project Certification Training, universities, institutes, career, career options, Education'
),
148 => array(
		'url' => 'nplus-certification-training-institutes-in-{location}',
		'keywords' => 'N+ Certification Training Institutes In {Location}, N+ Certification Courses,N+ Certification Courses, N+ Certification Training, universities, institutes, career, career options, Education'
),
149 => array(
		'url' => 'networking-certification-training-institutes-in-{location}',
		'keywords' => 'Networking Certification Training Institutes In {Location}, Networking Certification Courses,Networking Certification Courses, Networking Certification Training, universities, institutes, career, career options, Education'
),
150 => array(
		'url' => 'plc-designing-certification-training-institutes-in-{location}',
		'keywords' => 'PLC Designing Certification Training Institutes In {Location}, PLC Designing Certification Courses,PLC Designing Certification Courses, PLC Designing Certification Training, universities, institutes, career, career options, Education'
),
151 => array(
		'url' => 'pmp-certification-training-institutes-in-{location}',
		'keywords' => 'PMP Certification Certification Training Institutes In {Location}, PMP Certification Certification Courses,PMP Certification Certification Courses, PMP Certification Certification Training, universities, institutes, career, career options, Education'
),
152 => array(
		'url' => 'primavera-certification-training-institutes-in-{location}',
		'keywords' => 'Primavera Certification Training Institutes In {Location}, Primavera Certification Courses,Primavera Certification Courses, Primavera Certification Training, universities, institutes, career, career options, Education'
),
153 => array(
		'url' => 'prince-2-certification-training-institutes-in-{location}',
		'keywords' => 'Prince 2 Certification Certification Training Institutes In {Location}, Prince 2 Certification Certification Courses,Prince 2 Certification Certification Courses, Prince 2 Certification Certification Training, universities, institutes, career, career options, Education'
),
154 => array(
		'url' => 'project-management-certification-training-institutes-in-{location}',
		'keywords' => 'Project Management Certification Training Institutes In {Location}, Project Management Certification Courses,Project Management Certification Courses, Project Management Certification Training, universities, institutes, career, career options, Education'
),
155 => array(
		'url' => 'real-time-projects-certification-training-institutes-in-{location}',
		'keywords' => 'Real Time Projects Certification Training Institutes In {Location}, Real Time Projects Certification Courses,Real Time Projects Certification Courses, Real Time Projects Certification Training, universities, institutes, career, career options, Education'
),
156 => array(
		'url' => 'rexx-mq-series-certification-training-institutes-in-{location}',
		'keywords' => 'REXX MQ Series Certification Training Institutes In {Location}, REXX MQ Series Certification Courses,REXX MQ Series Certification Courses, REXX MQ Series Certification Training, universities, institutes, career, career options, Education'
),
157 => array(
		'url' => 'robotics-training-certification-training-institutes-in-{location}',
		'keywords' => 'Robotics Training Certification Training Institutes In {Location}, Robotics Training Certification Courses,Robotics Training Certification Courses, Robotics Training Certification Training, universities, institutes, career, career options, Education'
),
159 => array(
		'url' => 'sas-certification-training-institutes-in-{location}',
		'keywords' => 'SAS Certification Training Institutes In {Location}, SAS Certification Courses,SAS Certification Courses, SAS Certification Training, universities, institutes, career, career options, Education'
),
160 => array(
		'url' => 'share-point-certification-training-institutes-in-{location}',
		'keywords' => 'Share Point Certification Training Institutes In {Location}, Share Point Certification Courses,Share Point Certification Courses, Share Point Certification Training, universities, institutes, career, career options, Education'
),
161 => array(
		'url' => 'six-sigma-certification-training-institutes-in-{location}',
		'keywords' => 'Six Sigma Certification Certification Training Institutes In {Location}, Six Sigma Certification Certification Courses,Six Sigma Certification Certification Courses, Six Sigma Certification Certification Training, universities, institutes, career, career options, Education'
),
162 => array(
		'url' => 'software-testing-certification-training-institutes-in-{location}',
		'keywords' => 'Software Testing Certification Training Institutes In {Location}, Software Testing Certification Courses,Software Testing Certification Courses, Software Testing Certification Training, universities, institutes, career, career options, Education'
),
163 => array(
		'url' => 'synon-certification-training-institutes-in-{location}',
		'keywords' => 'Synon Certification Training Institutes In {Location}, Synon Certification Courses,Synon Certification Courses, Synon Certification Training, universities, institutes, career, career options, Education'
),
164 => array(
		'url' => 'tally-certification-training-institutes-in-{location}',
		'keywords' => 'Tally Certification Training Institutes In {Location}, Tally Certification Courses,Tally Certification Courses, Tally Certification Training, universities, institutes, career, career options, Education'
),
165 => array(
		'url' => 'technical-writing-certification-training-institutes-in-{location}',
		'keywords' => 'Technical Writing Certification Training Institutes In {Location}, Technical Writing Certification Courses,Technical Writing Certification Courses, Technical Writing Certification Training, universities, institutes, career, career options, Education'
),
166 => array(
		'url' => 'unigraphic-certification-training-institutes-in-{location}',
		'keywords' => 'Unigraphic Certification Training Institutes In {Location}, Unigraphic Certification Courses,Unigraphic Certification Courses, Unigraphic Certification Training, universities, institutes, career, career options, Education'
),
167 => array(
		'url' => 'vlsi-design-certification-training-institutes-in-{location}',
		'keywords' => 'VLSI Design Certification Training Institutes In {Location}, VLSI Design Certification Courses,VLSI Design Certification Courses, VLSI Design Certification Training, universities, institutes, career, career options, Education'
),
168 => array(
		'url' => 'Aeronautical-engineering-colleges-in-{location}',
		'keywords' => 'Aeronautical Engineering Colleges in {Location},  Aeronautical Engineering Courses, Aeronautical Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
169 => array(
		'url' => 'Biotechnology-engineering-colleges-in-{location}',
		'keywords' => 'Biotechnology Engineering Colleges in {Location},  Biotechnology Engineering Courses, Biotechnology Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
170 => array(
		'url' => 'Computers-engineering-colleges-in-{location}',
		'keywords' => 'Computers Engineering Colleges in {Location},  Computers Engineering Courses, Computers Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
171 => array(
		'url' => 'Civil-engineering-colleges-in-{location}',
		'keywords' => 'Civil Engineering Colleges in {Location},  Civil Engineering Courses, Civil Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
172 => array(
		'url' => 'Chemical-engineering-colleges-in-{location}',
		'keywords' => 'Chemical Engineering Colleges in {Location},  Chemical Engineering Courses, Chemical Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
173 => array(
		'url' => 'Electrical-engineering-colleges-in-{location}',
		'keywords' => 'Electrical Engineering Colleges in {Location},  Electrical Engineering Courses, Electrical Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
174 => array(
		'url' => 'Electrical-engineering-colleges-in-{location}',
		'keywords' => 'Electrical Engineering Colleges in {Location},  Electrical Engineering Courses, Electrical Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
175 => array(
		'url' => 'Industrial-engineering-colleges-in-{location}',
		'keywords' => 'Industrial Engineering Colleges in {Location},  Industrial Engineering Courses, Industrial Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
176 => array(
		'url' => 'IT-engineering-colleges-in-{location}',
		'keywords' => 'IT Engineering Colleges in {Location},  IT Engineering Courses, IT Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
177 => array(
		'url' => 'Mechanical-engineering-colleges-in-{location}',
		'keywords' => 'Mechanical Engineering Colleges in {Location},  Mechanical Engineering Courses, Mechanical Engineering Colleges,  Engineering Colleges in {Location},  Engineering Courses in {Location}, universities, institutes, career, career options, Education'
),
178 => array(
		'url' => 'mtech-in-Aeronautical-engineering-in-{location}',
		'keywords' => 'M.Tech in Aeronautical Engineering in {Location}, Category} Colleges in {Location}, Engineering Colleges, Mtech in Aeronautical Engineering in {Location}, ME in Aeronautical Engineering in {Location}, universities, institutes, career, career options, Education'
),
179 => array(
		'url' => 'mtech-in-Biotechnology-engineering-in-{location}',
		'keywords' => 'M.Tech in Biotechnology Engineering in {Location}, Category} Colleges in {Location}, Engineering Colleges, Mtech in Biotechnology Engineering in {Location}, ME in Biotechnology Engineering in {Location}, universities, institutes, career, career options, Education'
),
180 => array(
		'url' => 'mtech-in-Computers-engineering-in-{location}',
		'keywords' => 'M.Tech in Computers Engineering in {Location}, Category} Colleges in {Location}, Engineering Colleges, Mtech in Computers Engineering in {Location}, ME in Computers Engineering in {Location}, universities, institutes, career, career options, Education'
),
181 => array(
		'url' => 'mtech-in-Civil-engineering-in-{location}',
		'keywords' => 'M.Tech in Civil Engineering in {Location}, Category} Colleges in {Location}, Engineering Colleges, Mtech in Civil Engineering in {Location}, ME in Civil Engineering in {Location}, universities, institutes, career, career options, Education'
),
182 => array(
		'url' => 'mtech-in-Chemical-engineering-in-{location}',
		'keywords' => 'M.Tech in Chemical Engineering in {Location}, Category} Colleges in {Location}, Engineering Colleges, Mtech in Chemical Engineering in {Location}, ME in Chemical Engineering in {Location}, universities, institutes, career, career options, Education'
),
183 => array(
		'url' => 'mtech-in-Electrical-engineering-in-{location}',
		'keywords' => 'M.Tech in Electrical Engineering in {Location}, Category} Colleges in {Location}, Engineering Colleges, Mtech in Electrical Engineering in {Location}, ME in Electrical Engineering in {Location}, universities, institutes, career, career options, Education'
),
184 => array(
		'url' => 'mtech-in-Electrical-engineering-in-{location}',
		'keywords' => 'M.Tech in Electrical Engineering in {Location}, Category} Colleges in {Location}, Engineering Colleges, Mtech in Electrical Engineering in {Location}, ME in Electrical Engineering in {Location}, universities, institutes, career, career options, Education'
),
185 => array(
		'url' => 'mtech-in-Industrial-engineering-in-{location}',
		'keywords' => 'M.Tech in Industrial Engineering in {Location}, Category} Colleges in {Location}, Engineering Colleges, Mtech in Industrial Engineering in {Location}, ME in Industrial Engineering in {Location}, universities, institutes, career, career options, Education'
),
186 => array(
		'url' => 'mtech-in-IT-engineering-in-{location}',
		'keywords' => 'M.Tech in IT Engineering in {Location}, Category} Colleges in {Location}, Engineering Colleges, Mtech in IT Engineering in {Location}, ME in IT Engineering in {Location}, universities, institutes, career, career options, Education'
),
187 => array(
		'url' => 'mtech-in-Mechanical-engineering-in-{location}',
		'keywords' => 'M.Tech in Mechanical Engineering in {Location}, Category} Colleges in {Location}, Engineering Colleges, Mtech in Mechanical Engineering in {Location}, ME in Mechanical Engineering in {Location}, universities, institutes, career, career options, Education'
),
188 => array(
		'url' => 'distance-mca-colleges-in-{location}',
		'keywords' => 'Distance MCA in {Location}, {Location} Distance MCA Colleges, List of Distance MCA Colleges in {Location}, Distance MCA in {Location}, Types of Distance MCA Programs, Distance MCA courses, Business Schools in {Location}, Institutes in {Location}, Distance MCA courses, colleges, education'
),
189 => array(
		'url' => 'distance-bca-colleges-in-{location}',
		'keywords' => 'Distance BCA in {Location}, {Location} Distance BCA Colleges, List of Distance BCA Colleges in {Location}, Distance BCA in {Location}, Types of Distance BCA Programs, Distance BCA courses, Business Schools in {Location}, Institutes in {Location}, Distance BCA courses, colleges, education'
),
191 => array(
		'url' => 'bsc-game-art-courses-in-{location}',
		'keywords' => 'BSc Game Art in {Location}, {Location} BSc Game Art Courses, List of BSc Game Art Colleges in {Location}, BSc Game Art in {Location}, Types of BSc Game Art Programs, BSc Game Art courses, Business Schools in {Location}, Institutes in {Location}, BSc Game Art courses, colleges, education'
),
192 => array(
		'url' => 'bsc-multimedia-courses-in-{location}',
		'keywords' => 'BSc Multimedia in {Location}, {Location} BSc Multimedia Courses, List of BSc Multimedia Colleges in {Location}, BSc Multimedia in {Location}, Types of BSc Multimedia Programs, BSc Multimedia courses, Business Schools in {Location}, Institutes in {Location}, BSc Multimedia courses, colleges, education'
),
193 => array(
		'url' => 'bsc-visual-effects-courses-in-{location}',
		'keywords' => 'BSc Visual Effects in {Location}, {Location} BSc Visual Effects Courses, List of BSc Visual Effects Colleges in {Location}, BSc Visual Effects in {Location}, Types of BSc Visual Effects Programs, BSc Visual Effects courses, Business Schools in {Location}, Institutes in {Location}, BSc Visual Effects courses, colleges, education'
),
194 => array(
		'url' => 'ba-3d-animation-courses-in-{location}',
		'keywords' => 'BA 3D Animation in {Location}, {Location} BA 3D Animation Courses, List of BA 3D Animation Colleges in {Location}, BA 3D Animation in {Location}, Types of BA 3D Animation Programs, BA 3D Animation courses, Business Schools in {Location}, Institutes in {Location}, BA 3D Animation courses, colleges, education'
),
196 => array(
		'url' => 'ba-digital-film-making-courses-in-{location}',
		'keywords' => 'BA Digital Film Making in {Location}, {Location} BA Digital Film Making Courses, List of BA Digital Film Making Colleges in {Location}, BA Digital Film Making in {Location}, Types of BA Digital Film Making Programs, BA Digital Film Making courses, Business Schools in {Location}, Institutes in {Location}, BA Digital Film Making courses, colleges, education'
),
197 => array(
		'url' => 'ba-visual-effects-courses-in-{location}',
		'keywords' => 'BA Visual Effects in {Location}, {Location} BA Visual Effects Courses, List of BA Visual Effects Colleges in {Location}, BA Visual Effects in {Location}, Types of BA Visual Effects Programs, BA Visual Effects courses, Business Schools in {Location}, Institutes in {Location}, BA Visual Effects courses, colleges, education'
),
198 => array(
		'url' => 'bachelors-in-3d-animation-courses-in-{location}',
		'keywords' => 'Bachelors In 3D Animation in {Location}, {Location} Bachelors In 3D Animation Courses, List of Bachelors In 3D Animation Colleges in {Location}, Bachelors In 3D Animation in {Location}, Types of Bachelors In 3D Animation Programs, Bachelors In 3D Animation courses, Business Schools in {Location}, Institutes in {Location}, Bachelors In 3D Animation courses, colleges, education'
),
199 => array(
		'url' => 'bachelors-in-visual-effects-courses-in-{location}',
		'keywords' => 'Bachelors in Visual Effects in {Location}, {Location} Bachelors in Visual Effects Courses, List of Bachelors in Visual Effects Colleges in {Location}, Bachelors in Visual Effects in {Location}, Types of Bachelors in Visual Effects Programs, Bachelors in Visual Effects courses, Business Schools in {Location}, Institutes in {Location}, Bachelors in Visual Effects courses, colleges, education'
),
200 => array(
		'url' => 'diploma-in-3d-animation-courses-in-{location}',
		'keywords' => 'Diploma In 3D Animation in {Location}, {Location} Diploma In 3D Animation Courses, List of Diploma In 3D Animation Colleges in {Location}, Diploma In 3D Animation in {Location}, Types of Diploma In 3D Animation Programs, Diploma In 3D Animation courses, Business Schools in {Location}, Institutes in {Location}, Diploma In 3D Animation courses, colleges, education'
),
202 => array(
		'url' => 'diploma-in-broadcast-courses-in-{location}',
		'keywords' => 'Diploma in Broadcast in {Location}, {Location} Diploma in Broadcast Courses, List of Diploma in Broadcast Colleges in {Location}, Diploma in Broadcast in {Location}, Types of Diploma in Broadcast Programs, Diploma in Broadcast courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Broadcast courses, colleges, education'
),
203 => array(
		'url' => 'diploma-in-print-courses-in-{location}',
		'keywords' => 'Diploma in Print in {Location}, {Location} Diploma in Print Courses, List of Diploma in Print Colleges in {Location}, Diploma in Print in {Location}, Types of Diploma in Print Programs, Diploma in Print courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Print courses, colleges, education'
),
204 => array(
		'url' => 'diploma-in-visual-effects-courses-in-{location}',
		'keywords' => 'Diploma in Visual Effects in {Location}, {Location} Diploma in Visual Effects Courses, List of Diploma in Visual Effects Colleges in {Location}, Diploma in Visual Effects in {Location}, Types of Diploma in Visual Effects Programs, Diploma in Visual Effects courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Visual Effects courses, colleges, education'
),
206 => array(
		'url' => 'msc-film-post-production-courses-in-{location}',
		'keywords' => 'MSc Film Post Production in {Location}, {Location} MSc Film Post Production Courses, List of MSc Film Post Production Colleges in {Location}, MSc Film Post Production in {Location}, Types of MSc Film Post Production Programs, MSc Film Post Production courses, Business Schools in {Location}, Institutes in {Location}, MSc Film Post Production courses, colleges, education'
),
208 => array(
		'url' => 'msc-visual-effects-courses-in-{location}',
		'keywords' => 'MSc Visual Effects in {Location}, {Location} MSc Visual Effects Courses, List of MSc Visual Effects Colleges in {Location}, MSc Visual Effects in {Location}, Types of MSc Visual Effects Programs, MSc Visual Effects courses, Business Schools in {Location}, Institutes in {Location}, MSc Visual Effects courses, colleges, education'
),
210 => array(
		'url' => 'pg-diploma-in-graphic-designing-courses-in-{location}',
		'keywords' => 'PG Diploma in Graphic Designing in {Location}, {Location} PG Diploma in Graphic Designing Courses, List of PG Diploma in Graphic Designing Colleges in {Location}, PG Diploma in Graphic Designing in {Location}, Types of PG Diploma in Graphic Designing Programs, PG Diploma in Graphic Designing courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Graphic Designing courses, colleges, education'
),
211 => array(
		'url' => 'pg-diploma-in-visual-communications-courses-in-{location}',
		'keywords' => 'PG Diploma in Visual Communications in {Location}, {Location} PG Diploma in Visual Communications Courses, List of PG Diploma in Visual Communications Colleges in {Location}, PG Diploma in Visual Communications in {Location}, Types of PG Diploma in Visual Communications Programs, PG Diploma in Visual Communications courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Visual Communications courses, colleges, education'
),
212 => array(
		'url' => '2d-animation-certification-training-institutes-in-{location}',
		'keywords' => '2D Animation Certification Training Institutes In {Location}, 2D Animation Certification Courses,2D Animation Certification Courses, 2D Animation Certification Training, universities, institutes, career, career options, Education'
),
213 => array(
		'url' => '3d-animation-certification-training-institutes-in-{location}',
		'keywords' => '3D Animation Certification Training Institutes In {Location}, 3D Animation Certification Courses,3D Animation Certification Courses, 3D Animation Certification Training, universities, institutes, career, career options, Education'
),
214 => array(
		'url' => '3d-animation-basic-certification-training-institutes-in-{location}',
		'keywords' => '3D Animation Basic Certification Training Institutes In {Location}, 3D Animation Basic Certification Courses,3D Animation Basic Certification Courses, 3D Animation Basic Certification Training, universities, institutes, career, career options, Education'
),
215 => array(
		'url' => '3d-animation-maya-certification-training-institutes-in-{location}',
		'keywords' => '3D Animation Maya Certification Training Institutes In {Location}, 3D Animation Maya Certification Courses,3D Animation Maya Certification Courses, 3D Animation Maya Certification Training, universities, institutes, career, career options, Education'
),
216 => array(
		'url' => 'action-scripting-certification-training-institutes-in-{location}',
		'keywords' => 'Action Scripting Certification Training Institutes In {Location}, Action Scripting Certification Courses,Action Scripting Certification Courses, Action Scripting Certification Training, universities, institutes, career, career options, Education'
),
217 => array(
		'url' => 'animation-certification-training-institutes-in-{location}',
		'keywords' => 'Animation Certification Training Institutes In {Location}, Animation Certification Courses,Animation Certification Courses, Animation Certification Training, universities, institutes, career, career options, Education'
),
218 => array(
		'url' => 'audio-editing-certification-training-institutes-in-{location}',
		'keywords' => 'Audio Editing Certification Training Institutes In {Location}, Audio Editing Certification Courses,Audio Editing Certification Courses, Audio Editing Certification Training, universities, institutes, career, career options, Education'
),
219 => array(
		'url' => 'audio-video-editing-certification-training-institutes-in-{location}',
		'keywords' => 'Audio Video Editing Certification Training Institutes In {Location}, Audio Video Editing Certification Courses,Audio Video Editing Certification Courses, Audio Video Editing Certification Training, universities, institutes, career, career options, Education'
),
220 => array(
		'url' => 'audio-video-post production-certification-training-institutes-in-{location}',
		'keywords' => 'Audio Video Post Production Certification Training Institutes In {Location}, Audio Video Post Production Certification Courses,Audio Video Post Production Certification Courses, Audio Video Post Production Certification Training, universities, institutes, career, career options, Education'
),
221 => array(
		'url' => 'authoring-tbt-certification-training-institutes-in-{location}',
		'keywords' => 'Authoring TBT Certification Training Institutes In {Location}, Authoring TBT Certification Courses,Authoring TBT Certification Courses, Authoring TBT Certification Training, universities, institutes, career, career options, Education'
),
222 => array(
		'url' => 'cad-certification-training-institutes-in-{location}',
		'keywords' => 'CAD Certification Training Institutes In {Location}, CAD Certification Courses,CAD Certification Courses, CAD Certification Training, universities, institutes, career, career options, Education'
),
223 => array(
		'url' => 'character-animation-motion-builder-certification-training-institutes-in-{location}',
		'keywords' => 'Character Animation Motion Builder Certification Training Institutes In {Location}, Character Animation Motion Builder Certification Courses,Character Animation Motion Builder Certification Courses, Character Animation Motion Builder Certification Training, universities, institutes, career, career options, Education'
),
224 => array(
		'url' => 'character-design-certification-training-institutes-in-{location}',
		'keywords' => 'Character Design Certification Training Institutes In {Location}, Character Design Certification Courses,Character Design Certification Courses, Character Design Certification Training, universities, institutes, career, career options, Education'
),
225 => array(
		'url' => 'digital-architecture-certification-training-institutes-in-{location}',
		'keywords' => 'Digital Architecture Certification Training Institutes In {Location}, Digital Architecture Certification Courses,Digital Architecture Certification Courses, Digital Architecture Certification Training, universities, institutes, career, career options, Education'
),
226 => array(
		'url' => 'digital-design-visualisation-walkthrough-certification-training-institutes-in-{location}',
		'keywords' => 'Digital Design Visualisation & Walkthrough Certification Training Institutes In {Location}, Digital Design Visualisation & Walkthrough Certification Courses,Digital Design Visualisation & Walkthrough Certification Courses, Digital Design Visualisation & Walkthrough Certification Training, universities, institutes, career, career options, Education'
),
227 => array(
		'url' => 'digital-film-making-certification-training-institutes-in-{location}',
		'keywords' => 'Digital Film Making Certification Training Institutes In {Location}, Digital Film Making Certification Courses,Digital Film Making Certification Courses, Digital Film Making Certification Training, universities, institutes, career, career options, Education'
),
228 => array(
		'url' => 'digital-fx-certification-training-institutes-in-{location}',
		'keywords' => 'Digital FX Certification Training Institutes In {Location}, Digital FX Certification Courses,Digital FX Certification Courses, Digital FX Certification Training, universities, institutes, career, career options, Education'
),
229 => array(
		'url' => 'digital-graphics-animation-certification-training-institutes-in-{location}',
		'keywords' => 'Digital Graphics & Animation Certification Training Institutes In {Location}, Digital Graphics & Animation Certification Courses,Digital Graphics & Animation Certification Courses, Digital Graphics & Animation Certification Training, universities, institutes, career, career options, Education'
),
230 => array(
		'url' => 'drawing-animation-toonboom-certification-training-institutes-in-{location}',
		'keywords' => 'Drawing & Animation ToonBoom Certification Training Institutes In {Location}, Drawing & Animation ToonBoom Certification Courses,Drawing & Animation ToonBoom Certification Courses, Drawing & Animation ToonBoom Certification Training, universities, institutes, career, career options, Education'
),
231 => array(
		'url' => 'final-cut-pro-fcp-certification-training-institutes-in-{location}',
		'keywords' => 'Final Cut Pro (FCP) Certification Training Institutes In {Location}, Final Cut Pro (FCP) Certification Courses,Final Cut Pro (FCP) Certification Courses, Final Cut Pro (FCP) Certification Training, universities, institutes, career, career options, Education'
),
232 => array(
		'url' => 'core-game-art-certification-training-institutes-in-{location}',
		'keywords' => 'Game Art (Core) Certification Training Institutes In {Location}, Game Art (Core) Certification Courses,Game Art (Core) Certification Courses, Game Art (Core) Certification Training, universities, institutes, career, career options, Education'
),
233 => array(
		'url' => 'professional-game-art-certification-training-institutes-in-{location}',
		'keywords' => 'Game Art (Professional) Certification Training Institutes In {Location}, Game Art (Professional) Certification Courses,Game Art (Professional) Certification Courses, Game Art (Professional) Certification Training, universities, institutes, career, career options, Education'
),
234 => array(
		'url' => 'game-design-certification-training-institutes-in-{location}',
		'keywords' => 'Game Design Certification Training Institutes In {Location}, Game Design Certification Courses,Game Design Certification Courses, Game Design Certification Training, universities, institutes, career, career options, Education'
),
235 => array(
		'url' => 'game-programming-flash-certification-training-institutes-in-{location}',
		'keywords' => 'Game Programming (Flash) Certification Training Institutes In {Location}, Game Programming (Flash) Certification Courses,Game Programming (Flash) Certification Courses, Game Programming (Flash) Certification Training, universities, institutes, career, career options, Education'
),
236 => array(
		'url' => 'game-programming-mobile-certification-training-institutes-in-{location}',
		'keywords' => 'Game Programming (Mobile) Certification Training Institutes In {Location}, Game Programming (Mobile) Certification Courses,Game Programming (Mobile) Certification Courses, Game Programming (Mobile) Certification Training, universities, institutes, career, career options, Education'
),
237 => array(
		'url' => 'games-development-certification-training-institutes-in-{location}',
		'keywords' => 'Games Development Certification Training Institutes In {Location}, Games Development Certification Courses,Games Development Certification Courses, Games Development Certification Training, universities, institutes, career, career options, Education'
),
238 => array(
		'url' => 'graphic-designing-certification-training-institutes-in-{location}',
		'keywords' => 'Graphic Designing Certification Training Institutes In {Location}, Graphic Designing Certification Courses,Graphic Designing Certification Courses, Graphic Designing Certification Training, universities, institutes, career, career options, Education'
),
239 => array(
		'url' => 'graphics-sketching-certification-training-institutes-in-{location}',
		'keywords' => 'Graphics & Sketching Certification Training Institutes In {Location}, Graphics & Sketching Certification Courses,Graphics & Sketching Certification Courses, Graphics & Sketching Certification Training, universities, institutes, career, career options, Education'
),
240 => array(
		'url' => 'illustrations-certification-training-institutes-in-{location}',
		'keywords' => 'Illustrations Certification Training Institutes In {Location}, Illustrations Certification Courses,Illustrations Certification Courses, Illustrations Certification Training, universities, institutes, career, career options, Education'
),
241 => array(
		'url' => 'image-magic-certification-training-institutes-in-{location}',
		'keywords' => 'Image Magic Certification Training Institutes In {Location}, Image Magic Certification Courses,Image Magic Certification Courses, Image Magic Certification Training, universities, institutes, career, career options, Education'
),
242 => array(
		'url' => 'interior-design-certification-training-institutes-in-{location}',
		'title' => 'Interior Design Certification Training Institutes In {Location} - shiksha.com',
		'description' => 'Search Interior Design  Certification Training Institutes in {location} - Get a list of all Certification Courses, institutes. Log on to shiksha.com to Know more about the full and part time programs.',
		'keywords' => 'Interior Design Certification Training Institutes In {Location}, Interior Design Certification Courses,Interior Design Certification Courses, Interior Design Certification Training, universities, institutes, career, career options, Education'
),
243 => array(
		'url' => 'max-pro-certification-training-institutes-in-{location}',
		'keywords' => 'Max Pro Certification Training Institutes In {Location}, Max Pro Certification Courses,Max Pro Certification Courses, Max Pro Certification Training, universities, institutes, career, career options, Education'
),
244 => array(
		'url' => 'media-publishing-certification-training-institutes-in-{location}',
		'keywords' => 'Media Publishing Certification Training Institutes In {Location}, Media Publishing Certification Courses,Media Publishing Certification Courses, Media Publishing Certification Training, universities, institutes, career, career options, Education'
),
245 => array(
		'url' => 'multimedia-authoring-certification-training-institutes-in-{location}',
		'keywords' => 'Multimedia Authoring Certification Training Institutes In {Location}, Multimedia Authoring Certification Courses,Multimedia Authoring Certification Courses, Multimedia Authoring Certification Training, universities, institutes, career, career options, Education'
),
246 => array(
		'url' => 'multimedia-programming-certification-training-institutes-in-{location}',
		'keywords' => 'Multimedia Programming Certification Training Institutes In {Location}, Multimedia Programming Certification Courses,Multimedia Programming Certification Courses, Multimedia Programming Certification Training, universities, institutes, career, career options, Education'
),
247 => array(
		'url' => 'photoshop-certification-training-institutes-in-{location}',
		'keywords' => 'Photoshop Certification Training Institutes In {Location}, Photoshop Certification Courses,Photoshop Certification Courses, Photoshop Certification Training, universities, institutes, career, career options, Education'
),
248 => array(
		'url' => 'print-publishing-certification-training-institutes-in-{location}',
		'keywords' => 'Print & Publishing Certification Training Institutes In {Location}, Print & Publishing Certification Courses,Print & Publishing Certification Courses, Print & Publishing Certification Training, universities, institutes, career, career options, Education'
),
249 => array(
		'url' => 'video-streaming-editing-certification-training-institutes-in-{location}',
		'keywords' => 'Video Streaming & Editing Certification Training Institutes In {Location}, Video Streaming & Editing Certification Courses,Video Streaming & Editing Certification Courses, Video Streaming & Editing Certification Training, universities, institutes, career, career options, Education'
),
250 => array(
		'url' => 'visual-effects-certification-training-institutes-in-{location}',
		'keywords' => 'Visual Effects Certification Training Institutes In {Location}, Visual Effects Certification Courses,Visual Effects Certification Courses, Visual Effects Certification Training, universities, institutes, career, career options, Education'
),
251 => array(
		'url' => 'visual-effects-nuke-certification-training-institutes-in-{location}',
		'keywords' => 'Visual Effects (NUKE) Certification Training Institutes In {Location}, Visual Effects (NUKE) Certification Courses,Visual Effects (NUKE) Certification Courses, Visual Effects (NUKE) Certification Training, universities, institutes, career, career options, Education'
),
252 => array(
		'url' => 'web-animation-flash-certification-training-institutes-in-{location}',
		'keywords' => 'Web Animation (Flash) Certification Training Institutes In {Location}, Web Animation (Flash) Certification Courses,Web Animation (Flash) Certification Courses, Web Animation (Flash) Certification Training, universities, institutes, career, career options, Education'
),
253 => array(
		'url' => 'web-designing-certification-training-institutes-in-{location}',
		'keywords' => 'Web Designing Certification Training Institutes In {Location}, Web Designing Certification Courses,Web Designing Certification Courses, Web Designing Certification Training, universities, institutes, career, career options, Education'
),
254 => array(
		'url' => 'web-graphics-certification-training-institutes-in-{location}',
		'keywords' => 'Web Graphics Certification Training Institutes In {Location}, Web Graphics Certification Courses,Web Graphics Certification Courses, Web Graphics Certification Training, universities, institutes, career, career options, Education'
),
255 => array(
		'url' => 'webweaver-certification-training-institutes-in-{location}',
		'keywords' => 'Webweaver Certification Training Institutes In {Location}, Webweaver Certification Courses,Webweaver Certification Courses, Webweaver Certification Training, universities, institutes, career, career options, Education'
),
256 => array(
		'url' => 'advanced-diploma-in-hotel-management-in-{location}',
		'keywords' => 'Advanced Diploma in Hotel Management (3 Years) in {Location}, {Location} Advanced Diploma in Hotel Management (3 Years) Courses, List of Advanced Diploma in Hotel Management (3 Years) Colleges in {Location}, Advanced Diploma in Hotel Management (3 Years) in {Location}, Types of Advanced Diploma in Hotel Management (3 Years) Programs, Advanced Diploma in Hotel Management (3 Years) courses, Business Schools in {Location}, Institutes in {Location}, Advanced Diploma in Hotel Management (3 Years) courses, colleges, education'
),
257 => array(
		'url' => 'bsc-in-hotel-management-colleges-in-{location}',
		'keywords' => 'BSc Hotel Management in {Location}, {Location} BSc Hotel Management Courses, List of BSc Hotel Management Colleges in {Location}, BSc Hotel Management in {Location}, Types of BSc Hotel Management Programs, BSc Hotel Management courses, Business Schools in {Location}, Institutes in {Location}, BSc Hotel Management courses, colleges, education'
),
258 => array(
		'url' => 'bsc-in-international-hospitality-administration-colleges-in-{location}',
		'keywords' => 'BSc International Hospitality Administration in {Location}, {Location} BSc International Hospitality Administration Courses, List of BSc International Hospitality Administration Colleges in {Location}, BSc International Hospitality Administration in {Location}, Types of BSc International Hospitality Administration Programs, BSc International Hospitality Administration courses, Business Schools in {Location}, Institutes in {Location}, BSc International Hospitality Administration courses, colleges, education'
),
260 => array(
		'url' => 'ba-in-international-culinary-management-colleges-in-{location}',
		'keywords' => 'BA International Culinary Management in {Location}, {Location} BA International Culinary Management Courses, List of BA International Culinary Management Colleges in {Location}, BA International Culinary Management in {Location}, Types of BA International Culinary Management Programs, BA International Culinary Management courses, Business Schools in {Location}, Institutes in {Location}, BA International Culinary Management courses, colleges, education'
),
262 => array(
		'url' => 'ba-in-tourism-studies-bts-colleges-in-{location}',
		'keywords' => 'BA Tourism Studies (BTS) in {Location}, {Location} BA Tourism Studies (BTS) Courses, List of BA Tourism Studies (BTS) Colleges in {Location}, BA Tourism Studies (BTS) in {Location}, Types of BA Tourism Studies (BTS) Programs, BA Tourism Studies (BTS) courses, Business Schools in {Location}, Institutes in {Location}, BA Tourism Studies (BTS) courses, colleges, education'
),
263 => array(
		'url' => 'bachelor-in-catering-technology-colleges-in-{location}',
		'keywords' => 'Bachelor in Catering Technology in {Location}, {Location} Bachelor in Catering Technology Courses, List of Bachelor in Catering Technology Colleges in {Location}, Bachelor in Catering Technology in {Location}, Types of Bachelor in Catering Technology Programs, Bachelor in Catering Technology courses, Business Schools in {Location}, Institutes in {Location}, Bachelor in Catering Technology courses, colleges, education'
),
266 => array(
		'url' => 'bba-hospitality-management-colleges-in-{location}',
		'keywords' => 'BBA Hospitality Management in {Location}, {Location} BBA Hospitality Management Courses, List of BBA Hospitality Management Colleges in {Location}, BBA Hospitality Management in {Location}, Types of BBA Hospitality Management Programs, BBA Hospitality Management courses, Business Schools in {Location}, Institutes in {Location}, BBA Hospitality Management courses, colleges, education'
),
267 => array(
		'url' => 'bba-hotel-management-colleges-in-{location}',
		'keywords' => 'BBA Hotel Management in {Location}, {Location} BBA Hotel Management Courses, List of BBA Hotel Management Colleges in {Location}, BBA Hotel Management in {Location}, Types of BBA Hotel Management Programs, BBA Hotel Management courses, Business Schools in {Location}, Institutes in {Location}, BBA Hotel Management courses, colleges, education'
),
270 => array(
		'url' => 'diploma-in-aviation-management-colleges-in-{location}',
		'keywords' => 'Diploma in Aviation Management in {Location}, {Location} Diploma in Aviation Management Courses, List of Diploma in Aviation Management Colleges in {Location}, Diploma in Aviation Management in {Location}, Types of Diploma in Aviation Management Programs, Diploma in Aviation Management courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Aviation Management courses, colleges, education'
),
272 => array(
		'url' => 'diploma-in-food-beverage-colleges-in-{location}',
		'keywords' => 'Diploma in Food & Beverage in {Location}, {Location} Diploma in Food & Beverage Courses, List of Diploma in Food & Beverage Colleges in {Location}, Diploma in Food & Beverage in {Location}, Types of Diploma in Food & Beverage Programs, Diploma in Food & Beverage courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Food & Beverage courses, colleges, education'
),
273 => array(
		'url' => 'diploma-in-front-office-colleges-in-{location}',
		'keywords' => 'Diploma in Front Office in {Location}, {Location} Diploma in Front Office Courses, List of Diploma in Front Office Colleges in {Location}, Diploma in Front Office in {Location}, Types of Diploma in Front Office Programs, Diploma in Front Office courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Front Office courses, colleges, education'
),
274 => array(
		'url' => 'diploma-in-hospitality-management-colleges-in-{location}',
		'keywords' => 'Diploma in Hospitality Management in {Location}, {Location} Diploma in Hospitality Management Courses, List of Diploma in Hospitality Management Colleges in {Location}, Diploma in Hospitality Management in {Location}, Types of Diploma in Hospitality Management Programs, Diploma in Hospitality Management courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Hospitality Management courses, colleges, education'
),
275 => array(
		'url' => 'diploma-in-hotel-management-colleges-in-{location}',
		'keywords' => 'Diploma in Hotel Management in {Location}, {Location} Diploma in Hotel Management Courses, List of Diploma in Hotel Management Colleges in {Location}, Diploma in Hotel Management in {Location}, Types of Diploma in Hotel Management Programs, Diploma in Hotel Management courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Hotel Management courses, colleges, education'
),
276 => array(
		'url' => 'diploma-in-house-keeping-services-colleges-in-{location}',
		'keywords' => 'Diploma in House Keeping Services in {Location}, {Location} Diploma in House Keeping Services Courses, List of Diploma in House Keeping Services Colleges in {Location}, Diploma in House Keeping Services in {Location}, Types of Diploma in House Keeping Services Programs, Diploma in House Keeping Services courses, Business Schools in {Location}, Institutes in {Location}, Diploma in House Keeping Services courses, colleges, education'
),
277 => array(
		'url' => 'diploma-in-tourism-business-management-colleges-in-{location}',
		'keywords' => 'Diploma in Tourism Business Management in {Location}, {Location} Diploma in Tourism Business Management Courses, List of Diploma in Tourism Business Management Colleges in {Location}, Diploma in Tourism Business Management in {Location}, Types of Diploma in Tourism Business Management Programs, Diploma in Tourism Business Management courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Tourism Business Management courses, colleges, education'
),
279 => array(
		'url' => 'executive-mba-hospitality-management-colleges-in-{location}',
		'keywords' => 'Executive MBA Hospitality Management in {Location}, {Location} Executive MBA Hospitality Management Courses, List of Executive MBA Hospitality Management Colleges in {Location}, Executive MBA Hospitality Management in {Location}, Types of Executive MBA Hospitality Management Programs, Executive MBA Hospitality Management courses, Business Schools in {Location}, Institutes in {Location}, Executive MBA Hospitality Management courses, colleges, education'
),
280 => array(
		'url' => 'mba-hospitality-management-colleges-in-{location}',
		'keywords' => 'MBA Hospitality Management in {Location}, {Location} MBA Hospitality Management Courses, List of MBA Hospitality Management Colleges in {Location}, MBA Hospitality Management in {Location}, Types of MBA Hospitality Management Programs, MBA Hospitality Management courses, Business Schools in {Location}, Institutes in {Location}, MBA Hospitality Management courses, colleges, education'
),
281 => array(
		'url' => 'mba-hotel-management-colleges-in-{location}',
		'keywords' => 'MBA Hotel Management in {Location}, {Location} MBA Hotel Management Courses, List of MBA Hotel Management Colleges in {Location}, MBA Hotel Management in {Location}, Types of MBA Hotel Management Programs, MBA Hotel Management courses, Business Schools in {Location}, Institutes in {Location}, MBA Hotel Management courses, colleges, education'
),
282 => array(
		'url' => 'pg-diploma-in-event-management-colleges-in-{location}',
		'keywords' => 'PG Diploma in Event Management in {Location}, {Location} PG Diploma in Event Management Courses, List of PG Diploma in Event Management Colleges in {Location}, PG Diploma in Event Management in {Location}, Types of PG Diploma in Event Management Programs, PG Diploma in Event Management courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Event Management courses, colleges, education'
),
283 => array(
		'url' => 'pg-diploma-in-facility-management-colleges-in-{location}',
		'keywords' => 'PG Diploma in Facility Management in {Location}, {Location} PG Diploma in Facility Management Courses, List of PG Diploma in Facility Management Colleges in {Location}, PG Diploma in Facility Management in {Location}, Types of PG Diploma in Facility Management Programs, PG Diploma in Facility Management courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Facility Management courses, colleges, education'
),
284 => array(
		'url' => 'pg-diploma-in-hospitality-management-colleges-in-{location}',
		'keywords' => 'PG Diploma in Hospitality Management in {Location}, {Location} PG Diploma in Hospitality Management Courses, List of PG Diploma in Hospitality Management Colleges in {Location}, PG Diploma in Hospitality Management in {Location}, Types of PG Diploma in Hospitality Management Programs, PG Diploma in Hospitality Management courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Hospitality Management courses, colleges, education'
),
285 => array(
		'url' => 'pg-diploma-in-hospitality-operations-management-colleges-in-{location}',
		'keywords' => 'PG Diploma in Hospitality Operations Management in {Location}, {Location} PG Diploma in Hospitality Operations Management Courses, List of PG Diploma in Hospitality Operations Management Colleges in {Location}, PG Diploma in Hospitality Operations Management in {Location}, Types of PG Diploma in Hospitality Operations Management Programs, PG Diploma in Hospitality Operations Management courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Hospitality Operations Management courses, colleges, education'
),
286 => array(
		'url' => 'pg-diploma-in-public-relations-colleges-in-{location}',
		'keywords' => 'PG Diploma in Public Relations in {Location}, {Location} PG Diploma in Public Relations Courses, List of PG Diploma in Public Relations Colleges in {Location}, PG Diploma in Public Relations in {Location}, Types of PG Diploma in Public Relations Programs, PG Diploma in Public Relations courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Public Relations courses, colleges, education'
),
288 => array(
		'url' => 'pg-diploma-in-tourism-management-pgdtm-colleges-in-{location}',
		'keywords' => 'PG Diploma in Tourism Management (PGDTM) in {Location}, {Location} PG Diploma in Tourism Management (PGDTM) Courses, List of PG Diploma in Tourism Management (PGDTM) Colleges in {Location}, PG Diploma in Tourism Management (PGDTM) in {Location}, Types of PG Diploma in Tourism Management (PGDTM) Programs, PG Diploma in Tourism Management (PGDTM) courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Tourism Management (PGDTM) courses, colleges, education'
),
292 => array(
		'url' => 'iata-gds-fares-ticketing-certification-training-institutes-in-{location}',
		'keywords' => 'IATA GDS Fares Ticketing Certification Training Institutes In {Location}, IATA GDS Fares Ticketing Certification Courses,IATA GDS Fares Ticketing Certification Courses, IATA GDS Fares Ticketing Certification Training, universities, institutes, career, career options, Education'
),
293 => array(
		'url' => 'iata-dgca-dangerous-goods-regulation-basic-certification-training-institutes-in-{location}',
		'keywords' => 'IATA/DGCA Dangerous Goods Regulation-Basic Certification Training Institutes In {Location}, IATA/DGCA Dangerous Goods Regulation-Basic Certification Courses,IATA/DGCA Dangerous Goods Regulation-Basic Certification Courses, IATA/DGCA Dangerous Goods Regulation-Basic Certification Training, universities, institutes, career, career options, Education'
),
294 => array(
		'url' => 'iata-dgca-dangerous-goods-regulation-refresher-certification-training-institutes-in-{location}',
		'keywords' => 'IATA/DGCA Dangerous Goods Regulation-Refresher Certification Training Institutes In {Location}, IATA/DGCA Dangerous Goods Regulation-Refresher Certification Courses,IATA/DGCA Dangerous Goods Regulation-Refresher Certification Courses, IATA/DGCA Dangerous Goods Regulation-Refresher Certification Training, universities, institutes, career, career options, Education'
),
295 => array(
		'url' => 'iata-fiata-cargo-introductory-course-certification-training-institutes-in-{location}',
		'keywords' => 'IATA/FIATA Cargo Introductory Course Certification Training Institutes In {Location}, IATA/FIATA Cargo Introductory Course Certification Courses,IATA/FIATA Cargo Introductory Course Certification Courses, IATA/FIATA Cargo Introductory Course Certification Training, universities, institutes, career, career options, Education'
),
296 => array(
		'url' => 'iata-uftaa-cargo-rating-certification-training-institutes-in-{location}',
		'keywords' => 'IATA/UFTAA Cargo Rating Certification Training Institutes In {Location}, IATA/UFTAA Cargo Rating Certification Courses,IATA/UFTAA Cargo Rating Certification Courses, IATA/UFTAA Cargo Rating Certification Training, universities, institutes, career, career options, Education'
),
297 => array(
		'url' => 'iata-uftaa-consultant-course-certification-training-institutes-in-{location}',
		'keywords' => 'IATA/UFTAA Consultant Course Certification Training Institutes In {Location}, IATA/UFTAA Consultant Course Certification Courses,IATA/UFTAA Consultant Course Certification Courses, IATA/UFTAA Consultant Course Certification Training, universities, institutes, career, career options, Education'
),
298 => array(
		'url' => 'iata-uftaa-foundation-course-certification-training-institutes-in-{location}',
		'keywords' => 'IATA/UFTAA Foundation Course Certification Training Institutes In {Location}, IATA/UFTAA Foundation Course Certification Courses,IATA/UFTAA Foundation Course Certification Courses, IATA/UFTAA Foundation Course Certification Training, universities, institutes, career, career options, Education'
),
299 => array(
		'url' => 'other-certifications-certification-training-institutes-in-{location}',
		'keywords' => 'Other Certifications Certification Training Institutes In {Location}, Other Certifications Certification Courses,Other Certifications Certification Courses, Other Certifications Certification Training, universities, institutes, career, career options, Education'
),
300 => array(
		'url' => 'airport-handling-certification-training-institutes-in-{location}',
		'keywords' => 'Airport Handling Certification Training Institutes In {Location}, Airport Handling Certification Courses,Airport Handling Certification Courses, Airport Handling Certification Training, universities, institutes, career, career options, Education'
),
302 => array(
		'url' => 'communication-certification-training-institutes-in-{location}',
		'keywords' => 'Communication Certification Training Institutes In {Location}, Communication Certification Courses,Communication Certification Courses, Communication Certification Training, universities, institutes, career, career options, Education'
),
303 => array(
		'url' => 'customer-service-skills-certification-training-institutes-in-{location}',
		'keywords' => 'Customer Service Skills Certification Training Institutes In {Location}, Customer Service Skills Certification Courses,Customer Service Skills Certification Courses, Customer Service Skills Certification Training, universities, institutes, career, career options, Education'
),
304 => array(
		'url' => 'event-management-certification-training-institutes-in-{location}',
		'keywords' => 'Event Management Certification Training Institutes In {Location}, Event Management Certification Courses,Event Management Certification Courses, Event Management Certification Training, universities, institutes, career, career options, Education'
),
305 => array(
		'url' => 'facility-management-certification-training-institutes-in-{location}',
		'keywords' => 'Facility Management Certification Training Institutes In {Location}, Facility Management Certification Courses,Facility Management Certification Courses, Facility Management Certification Training, universities, institutes, career, career options, Education'
),
306 => array(
		'url' => 'galileo-crs-certification-training-institutes-in-{location}',
		'keywords' => 'Galileo CRS Certification Training Institutes In {Location}, Galileo CRS Certification Courses,Galileo CRS Certification Courses, Galileo CRS Certification Training, universities, institutes, career, career options, Education'
),
307 => array(
		'url' => 'hospitality-operations-certification-training-institutes-in-{location}',
		'keywords' => 'Hospitality Operations Certification Training Institutes In {Location}, Hospitality Operations Certification Courses,Hospitality Operations Certification Courses, Hospitality Operations Certification Training, universities, institutes, career, career options, Education'
),
308 => array(
		'url' => 'hotel-management-certification-training-institutes-in-{location}',
		'keywords' => 'Hotel Management Certification Training Institutes In {Location}, Hotel Management Certification Courses,Hotel Management Certification Courses, Hotel Management Certification Training, universities, institutes, career, career options, Education'
),
309 => array(
		'url' => 'house-keeping-certification-training-institutes-in-{location}',
		'keywords' => 'House Keeping Certification Training Institutes In {Location}, House Keeping Certification Courses,House Keeping Certification Courses, House Keeping Certification Training, universities, institutes, career, career options, Education'
),
310 => array(
		'url' => 'international-tourism-specialist-training-certification-training-institutes-in-{location}',
		'keywords' => 'International Tourism Specialist Training Certification Training Institutes In {Location}, International Tourism Specialist Training Certification Courses,International Tourism Specialist Training Certification Courses, International Tourism Specialist Training Certification Training, universities, institutes, career, career options, Education'
),
311 => array(
		'url' => 'itinerary-planning-certification-training-institutes-in-{location}',
		'keywords' => 'Itinerary Planning Certification Training Institutes In {Location}, Itinerary Planning Certification Courses,Itinerary Planning Certification Courses, Itinerary Planning Certification Training, universities, institutes, career, career options, Education'
),
312 => array(
		'url' => 'personality-development-certification-training-institutes-in-{location}',
		'keywords' => 'Personality Development Certification Training Institutes In {Location}, Personality Development Certification Courses,Personality Development Certification Courses, Personality Development Certification Training, universities, institutes, career, career options, Education'
),
313 => array(
		'url' => 'pilot-training-certification-training-institutes-in-{location}',
		'keywords' => 'Pilot Training Certification Training Institutes In {Location}, Pilot Training Certification Courses,Pilot Training Certification Courses, Pilot Training Certification Training, universities, institutes, career, career options, Education'
),
314 => array(
		'url' => 'public-relations-certification-training-institutes-in-{location}',
		'keywords' => 'Public Relations Certification Training Institutes In {Location}, Public Relations Certification Courses,Public Relations Certification Courses, Public Relations Certification Training, universities, institutes, career, career options, Education'
),
315 => array(
		'url' => 'spoken-english-certification-training-institutes-in-{location}',
		'keywords' => 'Spoken English Certification Training Institutes In {Location}, Spoken English Certification Courses,Spoken English Certification Courses, Spoken English Certification Training, universities, institutes, career, career options, Education'
),
316 => array(
		'url' => 'ticketing-certification-training-institutes-in-{location}',
		'keywords' => 'Ticketing Certification Training Institutes In {Location}, Ticketing Certification Courses,Ticketing Certification Courses, Ticketing Certification Training, universities, institutes, career, career options, Education'
),
317 => array(
		'url' => 'tour-management-certification-training-institutes-in-{location}',
		'keywords' => 'Tour Management Certification Training Institutes In {Location}, Tour Management Certification Courses,Tour Management Certification Courses, Tour Management Certification Training, universities, institutes, career, career options, Education'
),
318 => array(
		'url' => 'tour-operation-certification-training-institutes-in-{location}',
		'keywords' => 'Tour Operation Certification Training Institutes In {Location}, Tour Operation Certification Courses,Tour Operation Certification Courses, Tour Operation Certification Training, universities, institutes, career, career options, Education'
),
319 => array(
		'url' => 'travel-tourism-certification-training-institutes-in-{location}',
		'keywords' => 'Travel Tourism Certification Training Institutes In {Location}, Travel Tourism Certification Courses,Travel Tourism Certification Courses, Travel Tourism Certification Training, universities, institutes, career, career options, Education'
),
352 => array(
		'url' => 'aircraft-maintenance-engineering-ame-colleges-in-{location}',
		'keywords' => 'Aircraft Maintenance Engineering (AME) in {Location}, {Location} Aircraft Maintenance Engineering (AME) Courses, List of Aircraft Maintenance Engineering (AME) Colleges in {Location}, Aircraft Maintenance Engineering (AME) in {Location}, Types of Aircraft Maintenance Engineering (AME) Programs, Aircraft Maintenance Engineering (AME) courses, Business Schools in {Location}, Institutes in {Location}, Aircraft Maintenance Engineering (AME) courses, colleges, education'
),
353 => array(
		'url' => 'bpharma-colleges-in-{location}',
		'keywords' => 'B.Pharma in {Location}, {Location} B.Pharma Courses, List of B.Pharma Colleges in {Location}, B.Pharma in {Location}, Types of B.Pharma Programs, B.Pharma courses, Business Schools in {Location}, Institutes in {Location}, B.Pharma courses, colleges, education'
),
355 => array(
		'url' => 'iata-bsp-system-bsp-language-certification-training-institutes-in-{location}',
		'keywords' => 'IATA BSP System and BSP Language Certification Training Institutes In {Location}, IATA BSP System and BSP Language Certification Courses,IATA BSP System and BSP Language Certification Courses, IATA BSP System and BSP Language Certification Training, universities, institutes, career, career options, Education'
),
356 => array(
		'url' => 'biotechnology-courses-in-{location}',
		'keywords' => 'Biotechnology in {Location}, {Location} Biotechnology Courses, List of Biotechnology Colleges in {Location}, Biotechnology in {Location}, Types of Biotechnology Programs, Biotechnology courses, Business Schools in {Location}, Institutes in {Location}, Biotechnology courses, colleges, education'
),
357 => array(
		'url' => '',
		'keywords' => ''
),
358 => array(
		'url' => '',
		'keywords' => ''
),
359 => array(
		'url' => '',
		'keywords' => ''
),
360 => array(
		'url' => '',
		'keywords' => ''
),
361 => array(
		'url' => '',
		'keywords' => ''
),
362 => array(
		'url' => '',
		'keywords' => ''
),
363 => array(
		'url' => '',
		'keywords' => ''
),
364 => array(
		'url' => '',
		'keywords' => ''
),
365 => array(
		'url' => '',
		'keywords' => ''
),
366 => array(
		'url' => '',
		'keywords' => ''
),
367 => array(
		'url' => '',
		'keywords' => ''
),
368 => array(
		'url' => '',
		'keywords' => ''
),
369 => array(
		'url' => '',
		'keywords' => ''
),
370 => array(
		'url' => '',
		'keywords' => ''
),
371 => array(
		'url' => '',
		'keywords' => ''
),
372 => array(
		'url' => '',
		'keywords' => ''
),
373 => array(
		'url' => '',
		'keywords' => ''
),
374 => array(
		'url' => '',
		'keywords' => ''
),
375 => array(
		'url' => '',
		'keywords' => ''
),
376 => array(
		'url' => '',
		'keywords' => ''
),
377 => array(
		'url' => '',
		'keywords' => ''
),
378 => array(
		'url' => '',
		'keywords' => ''
),
379 => array(
		'url' => '',
		'keywords' => ''
),
380 => array(
		'url' => '',
		'keywords' => ''
),
381 => array(
		'url' => 'bba-colleges-in-{location}',
		'keywords' => 'BBA in {Location}, {Location} BBA Courses, List of BBA Colleges in {Location}, BBA in {Location}, Types of BBA Programs, BBA courses, Business Schools in {Location}, Institutes in {Location}, BBA courses, colleges, education'
),
383 => array(
		'url' => 'professional-diploma-in-clinical-research-in-{location}',
		'keywords' => 'Professional Diploma in Clinical Research in {Location}, {Location} Professional Diploma in Clinical Research Courses, List of Professional Diploma in Clinical Research Colleges in {Location}, Professional Diploma in Clinical Research in {Location}, Types of Professional Diploma in Clinical Research Programs, Professional Diploma in Clinical Research courses, Business Schools in {Location}, Institutes in {Location}, Professional Diploma in Clinical Research courses, colleges, education'
),
384 => array(
		'url' => 'm-pharma-in-clinical-research-in-{location}',
		'keywords' => 'M Pharma Clinical Research in {Location}, {Location} M Pharma Clinical Research Courses, List of M Pharma Clinical Research Colleges in {Location}, M Pharma Clinical Research in {Location}, Types of M Pharma Clinical Research Programs, M Pharma Clinical Research courses, Business Schools in {Location}, Institutes in {Location}, M Pharma Clinical Research courses, colleges, education'
),
385 => array(
		'url' => 'msc-in-clinical-research-one-year-course-in-{location}',
		'keywords' => 'MSc Clinical Research (1 Year) in {Location}, {Location} MSc Clinical Research (1 Year) Courses, List of MSc Clinical Research (1 Year) Colleges in {Location}, MSc Clinical Research (1 Year) in {Location}, Types of MSc Clinical Research (1 Year) Programs, MSc Clinical Research (1 Year) courses, Business Schools in {Location}, Institutes in {Location}, MSc Clinical Research (1 Year) courses, colleges, education'
),
386 => array(
		'url' => 'msc-in-clinical-research-two-years-course-in-{location}',
		'keywords' => 'MSc Clinical Research (2 Years) in {Location}, {Location} MSc Clinical Research (2 Years) Courses, List of MSc Clinical Research (2 Years) Colleges in {Location}, MSc Clinical Research (2 Years) in {Location}, Types of MSc Clinical Research (2 Years) Programs, MSc Clinical Research (2 Years) courses, Business Schools in {Location}, Institutes in {Location}, MSc Clinical Research (2 Years) courses, colleges, education'
),
388 => array(
		'url' => 'pg-diploma-in-advanced-clinical-research-in-{location}',
		'keywords' => 'PG Diploma in Advanced Clinical Research in {Location}, {Location} PG Diploma in Advanced Clinical Research Courses, List of PG Diploma in Advanced Clinical Research Colleges in {Location}, PG Diploma in Advanced Clinical Research in {Location}, Types of PG Diploma in Advanced Clinical Research Programs, PG Diploma in Advanced Clinical Research courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Advanced Clinical Research courses, colleges, education'
),
389 => array(
		'url' => 'pg-diploma-in-analytical-molecular-techniques-in-{location}',
		'keywords' => 'PG Diploma in Analytical and Molecular Techniques in {Location}, {Location} PG Diploma in Analytical and Molecular Techniques Courses, List of PG Diploma in Analytical and Molecular Techniques Colleges in {Location}, PG Diploma in Analytical and Molecular Techniques in {Location}, Types of PG Diploma in Analytical and Molecular Techniques Programs, PG Diploma in Analytical and Molecular Techniques courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Analytical and Molecular Techniques courses, colleges, education'
),
390 => array(
		'url' => 'pg-diploma-in-clinical-data-management-in-{location}',
		'keywords' => 'PG Diploma in Clinical Data Management in {Location}, {Location} PG Diploma in Clinical Data Management Courses, List of PG Diploma in Clinical Data Management Colleges in {Location}, PG Diploma in Clinical Data Management in {Location}, Types of PG Diploma in Clinical Data Management Programs, PG Diploma in Clinical Data Management courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Clinical Data Management courses, colleges, education'
),
392 => array(
		'url' => 'pg-diploma-in-clinical-research-business-development-in-{location}',
		'keywords' => 'PG Diploma in Clinical Research in Business Development in {Location}, {Location} PG Diploma in Clinical Research in Business Development Courses, List of PG Diploma in Clinical Research in Business Development Colleges in {Location}, PG Diploma in Clinical Research in Business Development in {Location}, Types of PG Diploma in Clinical Research in Business Development Programs, PG Diploma in Clinical Research in Business Development courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Clinical Research in Business Development courses, colleges, education'
),
393 => array(
		'url' => 'pg-diploma-in-clinical-trial-management-in-{location}',
		'keywords' => 'PG Diploma in Clinical Trial Management in {Location}, {Location} PG Diploma in Clinical Trial Management Courses, List of PG Diploma in Clinical Trial Management Colleges in {Location}, PG Diploma in Clinical Trial Management in {Location}, Types of PG Diploma in Clinical Trial Management Programs, PG Diploma in Clinical Trial Management courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Clinical Trial Management courses, colleges, education'
),
394 => array(
		'url' => 'pg-diploma-in-pharmacovigilance-in-{location}',
		'keywords' => 'PG Diploma in Pharmacovigilance in {Location}, {Location} PG Diploma in Pharmacovigilance Courses, List of PG Diploma in Pharmacovigilance Colleges in {Location}, PG Diploma in Pharmacovigilance in {Location}, Types of PG Diploma in Pharmacovigilance Programs, PG Diploma in Pharmacovigilance courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Pharmacovigilance courses, colleges, education'
),
395 => array(
		'url' => 'pg-diploma-in-quality-in-{location}',
		'keywords' => 'PG Diploma in Quality in {Location}, {Location} PG Diploma in Quality Courses, List of PG Diploma in Quality Colleges in {Location}, PG Diploma in Quality in {Location}, Types of PG Diploma in Quality Programs, PG Diploma in Quality courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Quality courses, colleges, education'
),
396 => array(
		'url' => 'clinical-biochemistry-certification-training-institutes-in-{location}',
		'keywords' => 'Certificate in Clinical Biochemistry Certification Training Institutes In {Location}, Certificate in Clinical Biochemistry Certification Courses,Certificate in Clinical Biochemistry Certification Courses, Certificate in Clinical Biochemistry Certification Training, universities, institutes, career, career options, Education'
),
397 => array(
		'url' => 'clinical-research-nurses-certification-training-institutes-in-{location}',
		'keywords' => 'Certificate in Clinical Research (Nurses) Certification Training Institutes In {Location}, Certificate in Clinical Research (Nurses) Certification Courses,Certificate in Clinical Research (Nurses) Certification Courses, Certificate in Clinical Research (Nurses) Certification Training, universities, institutes, career, career options, Education'
),
398 => array(
		'url' => 'oncology-clinical-trials-training-certification-training-institutes-in-{location}',
		'keywords' => 'Oncology Clinical Trials Training Certification Training Institutes In {Location}, Oncology Clinical Trials Training Certification Courses,Oncology Clinical Trials Training Certification Courses, Oncology Clinical Trials Training Certification Training, universities, institutes, career, career options, Education'
),
399 => array(
		'url' => 'pg-certification-clinical-research-nurses-training-institutes-in-{location}',
		'keywords' => 'PG Certificate in Clinical Research (Nurses) Certification Training Institutes In {Location}, PG Certificate in Clinical Research (Nurses) Certification Courses,PG Certificate in Clinical Research (Nurses) Certification Courses, PG Certificate in Clinical Research (Nurses) Certification Training, universities, institutes, career, career options, Education'
),
400 => array(
		'url' => 'bsc-bdes-accessory-design-courses-in-{location}',
		'title' => 'BSc/BDes Accessory Design Courses in {Location} | Shiksha.com',
		'description' => 'View BSc/BDes Accessory Design Courses in {Location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'BSc/BDes  Accessory Design in {Location}, {Location} BSc/BDes Accessory Design Courses, List of BSc/BDes  Accessory Design Colleges in {Location}, BSc/BDes  Accessory Design in {Location}, Types of BSc/BDes  Accessory Design Programs, BSc/BDes  Accessory Design courses, Business Schools in {Location}, Institutes in {Location}, BSc/BDes  Accessory Design courses, colleges, education'
),
401 => array(
		'url' => 'bsc-bdes-fashion-design-courses-in-{location}',
		'title' => 'BSc/BDes Fashion Design Courses in {Location} - shiksha.com',
		'description' => 'Search BSc/BDes Fashion Design  Courses in {Location} - Get a list of all BSc/BDes Fashion Design  Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'BSc/BDes Fashion Design in {Location}, {Location} BSc/BDes Fashion Design Courses, List of BSc/BDes Fashion Design Colleges in {Location}, BSc/BDes Fashion Design in {Location}, Types of BSc/BDes Fashion Design Programs, BSc/BDes Fashion Design courses, Business Schools in {Location}, Institutes in {Location}, BSc/BDes Fashion Design courses, colleges, education'
),
402 => array(
		'url' => 'bsc-bdes-jewellery-design-courses-in-{location}',
		'title' => 'BSc/BDes Jewellery Design Courses in {Location} | Shiksha.com',
		'description' => 'View BSc/BDes Jewellery Design Courses in {Location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'BSc/BDes Jewellery Design in {Location}, {Location} BSc/BDes Jewellery Design Courses, List of BSc/BDes Jewellery Design Colleges in {Location}, BSc/BDes Jewellery Design in {Location}, Types of BSc/BDes Jewellery Design Programs, BSc/BDes Jewellery Design courses, Business Schools in {Location}, Institutes in {Location}, BSc/BDes Jewellery Design courses, colleges, education'
),
403 => array(
		'url' => 'bsc-bdes-knitwear-design-courses-in-{location}',
		'title' => 'BSc/BDes Knitwear Design Courses in {Location} - shiksha.com',
		'description' => 'Search BSc/BDes Knitwear Design  Courses in {Location} - Get a list of all BSc/BDes Knitwear Design  Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'BSc/BDes Knitwear Design in {Location}, {Location} BSc/BDes Knitwear Design Courses, List of BSc/BDes Knitwear Design Colleges in {Location}, BSc/BDes Knitwear Design in {Location}, Types of BSc/BDes Knitwear Design Programs, BSc/BDes Knitwear Design courses, Business Schools in {Location}, Institutes in {Location}, BSc/BDes Knitwear Design courses, colleges, education'
),
404 => array(
		'url' => 'bsc-bdes-leather-design-colleges-in-{location}',
		'title' => 'BSc/BDes Leather Design Colleges in {Location} - shiksha.com',
		'description' => 'Search BSc/BDes Leather Design  Courses in {Location} - Get a list of all BSc/BDes Leather Design  Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'BSc/BDes Leather Design in {Location}, {Location} BSc/BDes Leather Design Courses, List of BSc/BDes Leather Design Colleges in {Location}, BSc/BDes Leather Design in {Location}, Types of BSc/BDes Leather Design Programs, BSc/BDes Leather Design courses, Business Schools in {Location}, Institutes in {Location}, BSc/BDes Leather Design courses, colleges, education'
),
405 => array(
		'url' => 'bsc-bdes-textile-design-colleges-in-{location}',
		'title' => 'BSc/BDes Textile Design Colleges in {Location} - shiksha.com',
		'description' => 'Search BSc/BDes Textile Design  Courses in {Location} - Get a list of all BSc/BDes Textile Design  Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'BSc/BDes Textile Design in {Location}, {Location} BSc/BDes Textile Design Courses, List of BSc/BDes Textile Design Colleges in {Location}, BSc/BDes Textile Design in {Location}, Types of BSc/BDes Textile Design Programs, BSc/BDes Textile Design courses, Business Schools in {Location}, Institutes in {Location}, BSc/BDes Textile Design courses, colleges, education'
),
406 => array(
		'url' => 'bsc-bdes-interior-design-colleges-in-{location}',
		'title' => 'BSc/BDes Interior Design Courses in {Location} | Shiksha.com',
		'description' => 'View BSc/BDes Interior Design Courses in {Location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'BSc/BDes Interior Design in {Location}, {Location} BSc/BDes Interior Design Courses, List of BSc/BDes Interior Design Colleges in {Location}, BSc/BDes Interior Design in {Location}, Types of BSc/BDes Interior Design Programs, BSc/BDes Interior Design courses, Business Schools in {Location}, Institutes in {Location}, BSc/BDes Interior Design courses, colleges, education'
),
407 => array(
		'url' => 'bachelors-apparel-production-courses-in-{location}',
		'title' => 'Bachelors in Apparel Production Courses in {Location} - shiksha.com',
		'description' => 'Search Bachelors in Apparel Production  Courses in {Location} - Get a list of all Bachelors in Apparel Production  Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'Bachelors in Apparel Production in {Location}, {Location} Bachelors in Apparel Production Courses, List of Bachelors in Apparel Production Colleges in {Location}, Bachelors in Apparel Production in {Location}, Types of Bachelors in Apparel Production Programs, Bachelors in Apparel Production courses, Business Schools in {Location}, Institutes in {Location}, Bachelors in Apparel Production courses, colleges, education'
),
408 => array(
		'url' => 'bachelors-in-fashion-communication-courses-in-{location}',
		'title' => 'Bachelors in Fashion Communication Courses in {Location} - shiksha.com',
		'description' => 'Search Bachelors in Fashion Communication  Courses in {Location} - Get a list of all Bachelors in Fashion Communication  Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'Bachelors in Fashion Communication in {Location}, {Location} Bachelors in Fashion Communication Courses, List of Bachelors in Fashion Communication Colleges in {Location}, Bachelors in Fashion Communication in {Location}, Types of Bachelors in Fashion Communication Programs, Bachelors in Fashion Communication courses, Business Schools in {Location}, Institutes in {Location}, Bachelors in Fashion Communication courses, colleges, education'
),
409 => array(
		'url' => 'bachelors-in-fashion-technology-courses-in-{location}',
		'title' => 'Bachelors in Fashion Technology Courses in {Location} - shiksha.com',
		'description' => 'Search Bachelors in Fashion Technology  Courses in {Location} - Get a list of all Bachelors in Fashion Technology  Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'Bachelors in Fashion Technology in {Location}, {Location} Bachelors in Fashion Technology Courses, List of Bachelors in Fashion Technology Colleges in {Location}, Bachelors in Fashion Technology in {Location}, Types of Bachelors in Fashion Technology Programs, Bachelors in Fashion Technology courses, Business Schools in {Location}, Institutes in {Location}, Bachelors in Fashion Technology courses, colleges, education'
),
410 => array(
		'url' => 'bachelors-in-merchandising-courses-in-{location}',
		'title' => 'Bachelors in Merchandising Courses in {Location} - shiksha.com',
		'description' => 'Search Bachelors in Merchandising  Courses in {Location} - Get a list of all Bachelors in Merchandising  Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'Bachelors in Merchandising in {Location}, {Location} Bachelors in Merchandising Courses, List of Bachelors in Merchandising Colleges in {Location}, Bachelors in Merchandising in {Location}, Types of Bachelors in Merchandising Programs, Bachelors in Merchandising courses, Business Schools in {Location}, Institutes in {Location}, Bachelors in Merchandising courses, colleges, education'
),
411 => array(
		'url' => 'diploma-in-fashion-design-in-{location}',
		'title' => 'Diploma in Fashion Design Institutes in {Location} - shiksha.com',
		'description' => 'Search Diploma in Fashion Design  Courses in {Location} - Get a list of all Diploma in Fashion Design  Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'Diploma in Fashion Design in {Location}, {Location} Diploma in Fashion Design Courses, List of Diploma in Fashion Design Colleges in {Location}, Diploma in Fashion Design in {Location}, Types of Diploma in Fashion Design Programs, Diploma in Fashion Design courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Fashion Design courses, colleges, education'
),
412 => array(
		'url' => 'diploma-interior-design-courses-in-{location}',
		'title' => 'Diploma in Interior Design Courses in {Location} | Shiksha.com',
		'description' => 'View Diploma in Interior Design Courses in {Location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'Diploma in Interior Design in {Location}, {Location} Diploma in Interior Design Courses, List of Diploma in Interior Design Colleges in {Location}, Diploma in Interior Design in {Location}, Types of Diploma in Interior Design Programs, Diploma in Interior Design courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Interior Design courses, colleges, education'
),
413 => array(
		'url' => 'diploma-in-jewellery-design-courses-in-{location}',
		'title' => 'Diploma in Jewellery Design Courses in {Location} | Shiksha.com',
		'description' => 'View Diploma in Jewellery Design Courses in {Location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'Diploma in Jewellery Design in {Location}, {Location} Diploma in Jewellery Design Courses, List of Diploma in Jewellery Design Colleges in {Location}, Diploma in Jewellery Design in {Location}, Types of Diploma in Jewellery Design Programs, Diploma in Jewellery Design courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Jewellery Design courses, colleges, education'
),
414 => array(
		'url' => 'diploma-in-merchandising-colleges-in-{location}',
		'title' => 'Diploma in Merchandising Colleges in {Location} - shiksha.com',
		'description' => 'Search Diploma in Merchandising  Courses in {Location} - Get a list of all Diploma in Merchandising  Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'Diploma in Merchandising in {Location}, {Location} Diploma in Merchandising Courses, List of Diploma in Merchandising Colleges in {Location}, Diploma in Merchandising in {Location}, Types of Diploma in Merchandising Programs, Diploma in Merchandising courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Merchandising courses, colleges, education'
),
415 => array(
		'url' => 'diploma-textile-design-colleges-in-{location}',
		'title' => 'Diploma in Textile Design Colleges in {Location} - shiksha.com',
		'description' => 'Search Diploma in Textile Design  Courses in {Location} - Get a list of all Diploma in Textile Design  Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'Diploma in Textile Design in {Location}, {Location} Diploma in Textile Design Courses, List of Diploma in Textile Design Colleges in {Location}, Diploma in Textile Design in {Location}, Types of Diploma in Textile Design Programs, Diploma in Textile Design courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Textile Design courses, colleges, education'
),
416 => array(
		'url' => 'masters-in-design-courses-in-{location}',
		'title' => 'Masters in Design Courses in {Location} | Shiksha.com',
		'description' => 'View Masters in Design Courses in {Location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'Masters in Design in {Location}, {Location} Masters in Design Courses, List of Masters in Design Colleges in {Location}, Masters in Design in {Location}, Types of Masters in Design Programs, Masters in Design courses, Business Schools in {Location}, Institutes in {Location}, Masters in Design courses, colleges, education'
),
417 => array(
		'url' => 'masters-in-fashion-management-in-{location}',
		'title' => 'Masters in Fashion Management Institutes in {Location} - shiksha.com',
		'description' => 'Search Masters in Fashion Management  Courses in {Location} - Get a list of all Masters in Fashion Management  Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'Masters in Fashion Management in {Location}, {Location} Masters in Fashion Management Courses, List of Masters in Fashion Management Colleges in {Location}, Masters in Fashion Management in {Location}, Types of Masters in Fashion Management Programs, Masters in Fashion Management courses, Business Schools in {Location}, Institutes in {Location}, Masters in Fashion Management courses, colleges, education'
),
418 => array(
		'url' => 'masters-in-fashion-technology-in-{location}',
		'title' => 'Masters in Fashion Technology Institutes in {Location} - shiksha.com',
		'description' => 'Search Masters in Fashion Technology  Courses in {Location} - Get a list of all Masters in Fashion Technology  Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'Masters in Fashion Technology in {Location}, {Location} Masters in Fashion Technology Courses, List of Masters in Fashion Technology Colleges in {Location}, Masters in Fashion Technology in {Location}, Types of Masters in Fashion Technology Programs, Masters in Fashion Technology courses, Business Schools in {Location}, Institutes in {Location}, Masters in Fashion Technology courses, colleges, education'
),
419 => array(
		'url' => 'masters-in-merchandising-courses-in-{location}',
		'title' => 'Masters in Merchandising Courses in {Location} - shiksha.com',
		'description' => 'Search Masters in Merchandising  Courses in {Location} - Get a list of all Masters in Merchandising  Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'Masters in Merchandising in {Location}, {Location} Masters in Merchandising Courses, List of Masters in Merchandising Colleges in {Location}, Masters in Merchandising in {Location}, Types of Masters in Merchandising Programs, Masters in Merchandising courses, Business Schools in {Location}, Institutes in {Location}, Masters in Merchandising courses, colleges, education'
),
425 => array(
		'url' => 'diploma-broadcast-journalism-courses-in-{location}',
		'keywords' => 'Diploma in Broadcast Journalism in {Location}, {Location} Diploma in Broadcast Journalism Courses, List of Diploma in Broadcast Journalism Colleges in {Location}, Diploma in Broadcast Journalism in {Location}, Types of Diploma in Broadcast Journalism Programs, Diploma in Broadcast Journalism courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Broadcast Journalism courses, colleges, education'
),
426 => array(
		'url' => 'diploma-camera-lighting-courses-in-{location}',
		'keywords' => 'Diploma in Camera & Lighting in {Location}, {Location} Diploma in Camera & Lighting Courses, List of Diploma in Camera & Lighting Colleges in {Location}, Diploma in Camera & Lighting in {Location}, Types of Diploma in Camera & Lighting Programs, Diploma in Camera & Lighting courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Camera & Lighting courses, colleges, education'
),
427 => array(
		'url' => 'diploma-in-dance-courses-in-{location}',
		'keywords' => 'Diploma in Dance in {Location}, {Location} Diploma in Dance Courses, List of Diploma in Dance Colleges in {Location}, Diploma in Dance in {Location}, Types of Diploma in Dance Programs, Diploma in Dance courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Dance courses, colleges, education'
),
432 => array(
		'url' => 'diploma-in-modelling-courses-in-{location}',
		'keywords' => 'Diploma in Modelling in {Location}, {Location} Diploma in Modelling Courses, List of Diploma in Modelling Colleges in {Location}, Diploma in Modelling in {Location}, Types of Diploma in Modelling Programs, Diploma in Modelling courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Modelling courses, colleges, education'
),
434 => array(
		'url' => 'diploma-in-post-production-courses-in-{location}',
		'keywords' => 'Diploma in Post Production in {Location}, {Location} Diploma in Post Production Courses, List of Diploma in Post Production Colleges in {Location}, Diploma in Post Production in {Location}, Types of Diploma in Post Production Programs, Diploma in Post Production courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Post Production courses, colleges, education'
),
436 => array(
		'url' => 'diploma-radio-programming-production-courses-in-{location}',
		'keywords' => 'Diploma in Radio Programming & Production in {Location}, {Location} Diploma in Radio Programming & Production Courses, List of Diploma in Radio Programming & Production Colleges in {Location}, Diploma in Radio Programming & Production in {Location}, Types of Diploma in Radio Programming & Production Programs, Diploma in Radio Programming & Production courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Radio Programming & Production courses, colleges, education'
),
437 => array(
		'url' => 'diploma-screenplay-script-writing-courses-in-{location}',
		'keywords' => 'Diploma in Screenplay and Script Writing in {Location}, {Location} Diploma in Screenplay and Script Writing Courses, List of Diploma in Screenplay and Script Writing Colleges in {Location}, Diploma in Screenplay and Script Writing in {Location}, Types of Diploma in Screenplay and Script Writing Programs, Diploma in Screenplay and Script Writing courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Screenplay and Script Writing courses, colleges, education'
),
439 => array(
		'url' => 'diploma-vocal-music-courses-in-{location}',
		'keywords' => 'Diploma in Vocal Music in {Location}, {Location} Diploma in Vocal Music Courses, List of Diploma in Vocal Music Colleges in {Location}, Diploma in Vocal Music in {Location}, Types of Diploma in Vocal Music Programs, Diploma in Vocal Music courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Vocal Music courses, colleges, education'
),
443 => array(
		'url' => 'pg-diploma-broadcast-journalism-courses-in-{location}',
		'keywords' => 'PG Diploma in Broadcast Journalism in {Location}, {Location} PG Diploma in Broadcast Journalism Courses, List of PG Diploma in Broadcast Journalism Colleges in {Location}, PG Diploma in Broadcast Journalism in {Location}, Types of PG Diploma in Broadcast Journalism Programs, PG Diploma in Broadcast Journalism courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Broadcast Journalism courses, colleges, education'
),
445 => array(
		'url' => 'pg-diploma-print-journalism-courses-in-{location}',
		'keywords' => 'PG Diploma in Print Journalism in {Location}, {Location} PG Diploma in Print Journalism Courses, List of PG Diploma in Print Journalism Colleges in {Location}, PG Diploma in Print Journalism in {Location}, Types of PG Diploma in Print Journalism Programs, PG Diploma in Print Journalism courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Print Journalism courses, colleges, education'
),
446 => array(
		'url' => 'pg-diploma-tv-journalism-courses-in-{location}',
		'keywords' => 'PG Diploma in TV Journalism in {Location}, {Location} PG Diploma in TV Journalism Courses, List of PG Diploma in TV Journalism Colleges in {Location}, PG Diploma in TV Journalism in {Location}, Types of PG Diploma in TV Journalism Programs, PG Diploma in TV Journalism courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in TV Journalism courses, colleges, education'
),
447 => array(
		'url' => 'camera-lighting-certification-training-institutes-in-{location}',
		'keywords' => 'Camera and Lighting Certification Training Institutes In {Location}, Camera and Lighting Certification Courses,Camera and Lighting Certification Courses, Camera and Lighting Certification Training, universities, institutes, career, career options, Education'
),
448 => array(
		'url' => 'non-linear-editing-nle-certification-training-institutes-in-{location}',
		'keywords' => 'Non Linear Editing (NLE) Certification Training Institutes In {Location}, Non Linear Editing (NLE) Certification Courses,Non Linear Editing (NLE) Certification Courses, Non Linear Editing (NLE) Certification Training, universities, institutes, career, career options, Education'
),
450 => array(
		'url' => 'mba-healthcare-management-courses-in-{location}',
		'keywords' => 'MBA Healthcare Management in {Location}, {Location} MBA Healthcare Management Courses, List of MBA Healthcare Management Colleges in {Location}, MBA Healthcare Management in {Location}, Types of MBA Healthcare Management Programs, MBA Healthcare Management courses, Business Schools in {Location}, Institutes in {Location}, MBA Healthcare Management courses, colleges, education'
),
451 => array(
		'url' => 'pg-diploma-regulatory-affairs-courses-in-{location}',
		'keywords' => 'PG Diploma in Regulatory Affairs in {Location}, {Location} PG Diploma in Regulatory Affairs Courses, List of PG Diploma in Regulatory Affairs Colleges in {Location}, PG Diploma in Regulatory Affairs in {Location}, Types of PG Diploma in Regulatory Affairs Programs, PG Diploma in Regulatory Affairs courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Regulatory Affairs courses, colleges, education'
),
452 => array(
		'url' => 'bbm-colleges-in-{location}',
		'keywords' => 'BBM in {Location}, {Location} BBM Courses, List of BBM Colleges in {Location}, BBM in {Location}, Types of BBM Programs, BBM courses, Business Schools in {Location}, Institutes in {Location}, BBM courses, colleges, education'
),
455 => array(
		'url' => 'mphil-colleges-in-{location}',
		'keywords' => 'M.Phil. in {Location}, {Location} M.Phil. Courses, List of M.Phil. Colleges in {Location}, M.Phil. in {Location}, Types of M.Phil. Programs, M.Phil. courses, Business Schools in {Location}, Institutes in {Location}, M.Phil. courses, colleges, education'
),
456 => array(
		'url' => 'distance-ba-colleges-in-{location}',
		'keywords' => 'Distance BA in {Location}, {Location} Distance BA Courses, List of Distance BA Colleges in {Location}, Distance BA in {Location}, Types of Distance BA Programs, Distance BA courses, Business Schools in {Location}, Institutes in {Location}, Distance BA courses, colleges, education'
),
457 => array(
		'url' => 'distance-ma-colleges-in-{location}',
		'keywords' => 'Distance MA in {Location}, {Location} Distance MA Courses, List of Distance MA Colleges in {Location}, Distance MA in {Location}, Types of Distance MA Programs, Distance MA courses, Business Schools in {Location}, Institutes in {Location}, Distance MA courses, colleges, education'
),
458 => array(
		'url' => 'distance-mphil-courses-in-{location}',
		'keywords' => 'Distance MPhil in {Location}, {Location} Distance MPhil Courses, List of Distance MPhil Colleges in {Location}, Distance MPhil in {Location}, Types of Distance MPhil Programs, Distance MPhil courses, Business Schools in {Location}, Institutes in {Location}, Distance MPhil courses, colleges, education'
),
462 => array(
		'url' => 'diploma-in-music-courses-in-{location}',
		'keywords' => 'Diploma in Music in {Location}, {Location} Diploma in Music Courses, List of Diploma in Music Colleges in {Location}, Diploma in Music in {Location}, Types of Diploma in Music Programs, Diploma in Music courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Music courses, colleges, education'
),
463 => array(
		'url' => 'diploma-in-drawing-painting-courses-in-{location}',
		'keywords' => 'Diploma in Drawing Painting in {Location}, {Location} Diploma in Drawing Painting Courses, List of Diploma in Drawing Painting Colleges in {Location}, Diploma in Drawing Painting in {Location}, Types of Diploma in Drawing Painting Programs, Diploma in Drawing Painting courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Drawing Painting courses, colleges, education'
),
464 => array(
		'url' => 'diploma-in-dance-courses-in-{location}',
		'keywords' => 'Diploma in Dance in {Location}, {Location} Diploma in Dance Courses, List of Diploma in Dance Colleges in {Location}, Diploma in Dance in {Location}, Types of Diploma in Dance Programs, Diploma in Dance courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Dance courses, colleges, education'
),
465 => array(
		'url' => 'diploma-in-creative-writing-courses-in-{location}',
		'keywords' => 'Diploma in Creative Writing in {Location}, {Location} Diploma in Creative Writing Courses, List of Diploma in Creative Writing Colleges in {Location}, Diploma in Creative Writing in {Location}, Types of Diploma in Creative Writing Programs, Diploma in Creative Writing courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Creative Writing courses, colleges, education'
),
466 => array(
		'url' => 'diploma-in-calligraphy-courses-in-{location}',
		'keywords' => 'Diploma in Calligraphy in {Location}, {Location} Diploma in Calligraphy Courses, List of Diploma in Calligraphy Colleges in {Location}, Diploma in Calligraphy in {Location}, Types of Diploma in Calligraphy Programs, Diploma in Calligraphy courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Calligraphy courses, colleges, education'
),
467 => array(
		'url' => 'diploma-in-fine-arts-courses-in-{location}',
		'keywords' => 'Diploma in Fine Arts in {Location}, {Location} Diploma in Fine Arts Courses, List of Diploma in Fine Arts Colleges in {Location}, Diploma in Fine Arts in {Location}, Types of Diploma in Fine Arts Programs, Diploma in Fine Arts courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Fine Arts courses, colleges, education'
),
468 => array(
		'url' => 'certificate-in-music-certification-training-institutes-in-{location}',
		'keywords' => 'Certificate in Music Training Institutes In {Location}, Certificate in Music Courses, Certificate in Music Courses, Certificate in Music Training, universities, institutes, career, career options, Education'
),
469 => array(
		'url' => 'certificate-in-drawing-painting-certification-training-institutes-in-{location}',
		'keywords' => 'Certificate in Drawing Painting Training Institutes In {Location}, Certificate in Drawing Painting Courses, Certificate in Drawing Painting Courses, Certificate in Drawing Painting Training, universities, institutes, career, career options, Education'
),
470 => array(
		'url' => 'certificate-in-dance-certification-training-institutes-in-{location}',
		'keywords' => 'Certificate in Dance Training Institutes In {Location}, Certificate in Dance Courses, Certificate in Dance Courses, Certificate in Dance Training, universities, institutes, career, career options, Education'
),
471 => array(
		'url' => 'diploma-in-creative-writing-courses-in-{location}',
		'keywords' => 'Diploma in Creative Writing in {Location}, {Location} Diploma in Creative Writing Courses, List of Diploma in Creative Writing Colleges in {Location}, Diploma in Creative Writing in {Location}, Types of Diploma in Creative Writing Programs, Diploma in Creative Writing courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Creative Writing courses, colleges, education'
),
472 => array(
		'url' => 'diploma-in-calligraphy-courses-in-{location}',
		'keywords' => 'Diploma in Calligraphy in {Location}, {Location} Diploma in Calligraphy Courses, List of Diploma in Calligraphy Colleges in {Location}, Diploma in Calligraphy in {Location}, Types of Diploma in Calligraphy Programs, Diploma in Calligraphy courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Calligraphy courses, colleges, education'
),
473 => array(
		'url' => 'ba-in-music-institutes-in-{location}',
		'keywords' => 'BA in Music in {Location}, {Location} BA in Music Courses, List of BA in Music Colleges in {Location}, BA in Music in {Location}, Types of BA in Music Programs, BA in Music courses, Business Schools in {Location}, Institutes in {Location}, BA in Music courses, colleges, education'
),
474 => array(
		'url' => 'ba-in-dance-institutes-in-{location}',
		'keywords' => 'BA in Dance in {Location}, {Location} BA in Dance Courses, List of BA in Dance Colleges in {Location}, BA in Dance in {Location}, Types of BA in Dance Programs, BA in Dance courses, Business Schools in {Location}, Institutes in {Location}, BA in Dance courses, colleges, education'
),
475 => array(
		'url' => 'ba-in-drawing-painting-courses-in-{location}',
		'keywords' => 'BA in Drawing Painting in {Location}, {Location} BA in Drawing Painting Courses, List of BA in Drawing Painting Colleges in {Location}, BA in Drawing Painting in {Location}, Types of BA in Drawing Painting Programs, BA in Drawing Painting courses, Business Schools in {Location}, Institutes in {Location}, BA in Drawing Painting courses, colleges, education'
),
476 => array(
		'url' => 'ba-in-fine-arts-courses-in-{location}',
		'keywords' => 'BA in Fine Arts in {Location}, {Location} BA in Fine Arts Courses, List of BA in Fine Arts Colleges in {Location}, BA in Fine Arts in {Location}, Types of BA in Fine Arts Programs, BA in Fine Arts courses, Business Schools in {Location}, Institutes in {Location}, BA in Fine Arts courses, colleges, education'
),
479 => array(
		'url' => 'diploma-in-teaching-courses-in-{location}',
		'keywords' => 'Diploma in Teaching in {Location}, {Location} Diploma in Teaching Courses, List of Diploma in Teaching Colleges in {Location}, Diploma in Teaching in {Location}, Types of Diploma in Teaching Programs, Diploma in Teaching courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Teaching courses, colleges, education'
),
480 => array(
		'url' => 'pg-diploma-in-teaching-courses-in-{location}',
		'keywords' => 'PG Diploma in Teaching in {Location}, {Location} PG Diploma in Teaching Courses, List of PG Diploma in Teaching Colleges in {Location}, PG Diploma in Teaching in {Location}, Types of PG Diploma in Teaching Programs, PG Diploma in Teaching courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Teaching courses, colleges, education'
),
481 => array(
		'url' => 'ba-in-political-science-courses-in-{location}',
		'keywords' => 'BA in Political Science in {Location}, {Location} BA in Political Science Courses, List of BA in Political Science Colleges in {Location}, BA in Political Science in {Location}, Types of BA in Political Science Programs, BA in Political Science courses, Business Schools in {Location}, Institutes in {Location}, BA in Political Science courses, colleges, education'
),
482 => array(
		'url' => 'ma-in-political-science-courses-in-{location}',
		'keywords' => 'MA in Political Science in {Location}, {Location} MA in Political Science Courses, List of MA in Political Science Colleges in {Location}, MA in Political Science in {Location}, Types of MA in Political Science Programs, MA in Political Science courses, Business Schools in {Location}, Institutes in {Location}, MA in Political Science courses, colleges, education'
),
483 => array(
		'url' => 'ma-in-public-administration-courses-in-{location}',
		'keywords' => 'MA in Public Administration in {Location}, {Location} MA in Public Administration Courses, List of MA in Public Administration Colleges in {Location}, MA in Public Administration in {Location}, Types of MA in Public Administration Programs, MA in Public Administration courses, Business Schools in {Location}, Institutes in {Location}, MA in Public Administration courses, colleges, education'
),
484 => array(
		'url' => 'masters-program-in-government-colleges-in-{location}',
		'keywords' => 'Masters Program in Government in {Location}, {Location} Masters Program in Government Courses, List of Masters Program in Government Colleges in {Location}, Masters Program in Government in {Location}, Types of Masters Program in Government Programs, Masters Program in Government courses, Business Schools in {Location}, Institutes in {Location}, Masters Program in Government courses, colleges, education'
),
485 => array(
		'url' => 'ba-sociology-courses-in-{location}',
		'keywords' => 'B.A. Sociology in {Location}, {Location} B.A. Sociology Courses, List of B.A. Sociology Colleges in {Location}, B.A. Sociology in {Location}, Types of B.A. Sociology Programs, B.A. Sociology courses, Business Schools in {Location}, Institutes in {Location}, B.A. Sociology courses, colleges, education'
),
486 => array(
		'url' => 'ba-social-science-courses-in-{location}',
		'keywords' => 'B.A. Social Science in {Location}, {Location} B.A. Social Science Courses, List of B.A. Social Science Colleges in {Location}, B.A. Social Science in {Location}, Types of B.A. Social Science Programs, B.A. Social Science courses, Business Schools in {Location}, Institutes in {Location}, B.A. Social Science courses, colleges, education'
),
488 => array(
		'url' => 'ba-history-courses-in-{location}',
		'keywords' => 'B.A. History in {Location}, {Location} B.A. History Courses, List of B.A. History Colleges in {Location}, B.A. History in {Location}, Types of B.A. History Programs, B.A. History courses, Business Schools in {Location}, Institutes in {Location}, B.A. History courses, colleges, education'
),
489 => array(
		'url' => 'ma-sociology-courses-in-{location}',
		'keywords' => 'M.A Sociology in {Location}, {Location} M.A Sociology Courses, List of M.A Sociology Colleges in {Location}, M.A Sociology in {Location}, Types of M.A Sociology Programs, M.A Sociology courses, Business Schools in {Location}, Institutes in {Location}, M.A Sociology courses, colleges, education'
),
490 => array(
		'url' => 'ma-social-science-courses-in-{location}',
		'keywords' => 'M.A. Social Science in {Location}, {Location} M.A. Social Science Courses, List of M.A. Social Science Colleges in {Location}, M.A. Social Science in {Location}, Types of M.A. Social Science Programs, M.A. Social Science courses, Business Schools in {Location}, Institutes in {Location}, M.A. Social Science courses, colleges, education'
),
492 => array(
		'url' => 'ma-history-courses-in-{location}',
		'keywords' => 'M.A. History in {Location}, {Location} M.A. History Courses, List of M.A. History Colleges in {Location}, M.A. History in {Location}, Types of M.A. History Programs, M.A. History courses, Business Schools in {Location}, Institutes in {Location}, M.A. History courses, colleges, education'
),
493 => array(
		'url' => 'pg-diploma-in-ngo-management-courses-in-{location}',
		'keywords' => 'PG Diploma in NGO Management in {Location}, {Location} PG Diploma in NGO Management Courses, List of PG Diploma in NGO Management Colleges in {Location}, PG Diploma in NGO Management in {Location}, Types of PG Diploma in NGO Management Programs, PG Diploma in NGO Management courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in NGO Management courses, colleges, education'
),
495 => array(
		'url' => 'public-speaking-certification-training-institutes-in-{location}',
		'keywords' => 'Public Speaking Certification Training Institutes In {Location}, Public Speaking Certification Courses,Public Speaking Certification Courses, Public Speaking Certification Training, universities, institutes, career, career options, Education'
),
501 => array(
		'url' => 'ba-languages-courses-in-{location}',
		'keywords' => 'B.A. (Languages) in {Location}, {Location} B.A. (Languages) Courses, List of B.A. (Languages) Colleges in {Location}, B.A. (Languages) in {Location}, Types of B.A. (Languages) Programs, B.A. (Languages) courses, Business Schools in {Location}, Institutes in {Location}, B.A. (Languages) courses, colleges, education'
),
502 => array(
		'url' => 'ma-languages-courses-in-{location}',
		'keywords' => 'M.A. (Languages) in {Location}, {Location} M.A. (Languages) Courses, List of M.A. (Languages) Colleges in {Location}, M.A. (Languages) in {Location}, Types of M.A. (Languages) Programs, M.A. (Languages) courses, Business Schools in {Location}, Institutes in {Location}, M.A. (Languages) courses, colleges, education'
),
503 => array(
		'url' => 'call-centre-training-certification-training-institutes-in-{location}',
		'keywords' => 'Call Centre Training Certification Training Institutes In {Location}, Call Centre Training Certification Courses,Call Centre Training Certification Courses, Call Centre Training Certification Training, universities, institutes, career, career options, Education'
),
504 => array(
		'url' => 'voice-and-accent-training-certification-institutes-in-{location}',
		'keywords' => 'Voice and Accent Training Certification Training Institutes In {Location}, Voice and Accent Training Certification Courses,Voice and Accent Training Certification Courses, Voice and Accent Training Certification Training, universities, institutes, career, career options, Education'
),
505 => array(
		'url' => 'call-centre-certificate-program-training-institutes-in-{location}',
		'keywords' => 'Call Centre Certificate Program Certification Training Institutes In {Location}, Call Centre Certificate Program Certification Courses,Call Centre Certificate Program Certification Courses, Call Centre Certificate Program Certification Training, universities, institutes, career, career options, Education'
),
507 => array(
		'url' => 'acturial-science-courses-in-{location}',
		'keywords' => 'Acturial Science in {Location}, {Location} Acturial Science Courses, List of Acturial Science Colleges in {Location}, Acturial Science in {Location}, Types of Acturial Science Programs, Acturial Science courses, Business Schools in {Location}, Institutes in {Location}, Acturial Science courses, colleges, education'
),
525 => array(
		'url' => 'certificate-in-capital-market-certification-training-institutes-in-{location}',
		'keywords' => 'Certificate in Capital Market Certification Training Institutes In {Location}, Certificate in Capital Market Certification Courses,Certificate in Capital Market Certification Courses, Certificate in Capital Market Certification Training, universities, institutes, career, career options, Education'
),
526 => array(
		'url' => 'certificate-in-financial-planning-certification-training-institutes-in-{location}',
		'keywords' => 'Certificate in Financial Planning Certification Training Institutes In {Location}, Certificate in Financial Planning Certification Courses,Certificate in Financial Planning Certification Courses, Certificate in Financial Planning Certification Training, universities, institutes, career, career options, Education'
),
527 => array(
		'url' => 'bcom-colleges-in-{location}',
		'keywords' => 'B.Com in {Location}, {Location} B.Com Courses, List of B.Com Colleges in {Location}, B.Com in {Location}, Types of B.Com Programs, B.Com courses, Business Schools in {Location}, Institutes in {Location}, B.Com courses, colleges, education'
),
528 => array(
		'url' => 'diploma-in-accounting-taxation-courses-in-{location}',
		'keywords' => 'Diploma in Accounting & Taxation in {Location}, {Location} Diploma in Accounting & Taxation Courses, List of Diploma in Accounting & Taxation Colleges in {Location}, Diploma in Accounting & Taxation in {Location}, Types of Diploma in Accounting & Taxation Programs, Diploma in Accounting & Taxation courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Accounting & Taxation courses, colleges, education'
),
529 => array(
		'url' => 'diploma-in-banking-finance-courses-in-{location}',
		'keywords' => 'Diploma in Banking & Finance in {Location}, {Location} Diploma in Banking & Finance Courses, List of Diploma in Banking & Finance Colleges in {Location}, Diploma in Banking & Finance in {Location}, Types of Diploma in Banking & Finance Programs, Diploma in Banking & Finance courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Banking & Finance courses, colleges, education'
),
530 => array(
		'url' => 'diploma-in-financial-planning-courses-in-{location}',
		'keywords' => 'Diploma in Financial Planning in {Location}, {Location} Diploma in Financial Planning Courses, List of Diploma in Financial Planning Colleges in {Location}, Diploma in Financial Planning in {Location}, Types of Diploma in Financial Planning Programs, Diploma in Financial Planning courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Financial Planning courses, colleges, education'
),
531 => array(
		'url' => 'diploma-in-international-banking-finance-courses-in-{location}',
		'keywords' => 'Diploma in International Banking & Finance in {Location}, {Location} Diploma in International Banking & Finance Courses, List of Diploma in International Banking & Finance Colleges in {Location}, Diploma in International Banking & Finance in {Location}, Types of Diploma in International Banking & Finance Programs, Diploma in International Banking & Finance courses, Business Schools in {Location}, Institutes in {Location}, Diploma in International Banking & Finance courses, colleges, education'
),
532 => array(
		'url' => 'diploma-in-insurance-risk-management-courses-in-{location}',
		'keywords' => 'Diploma in Insurance & Risk Management in {Location}, {Location} Diploma in Insurance & Risk Management Courses, List of Diploma in Insurance & Risk Management Colleges in {Location}, Diploma in Insurance & Risk Management in {Location}, Types of Diploma in Insurance & Risk Management Programs, Diploma in Insurance & Risk Management courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Insurance & Risk Management courses, colleges, education'
),
533 => array(
		'url' => 'diploma-in-treasury-investment-courses-in-{location}',
		'keywords' => 'Diploma in Treasury Investment in {Location}, {Location} Diploma in Treasury Investment Courses, List of Diploma in Treasury Investment Colleges in {Location}, Diploma in Treasury Investment in {Location}, Types of Diploma in Treasury Investment Programs, Diploma in Treasury Investment courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Treasury Investment courses, colleges, education'
),
534 => array(
		'url' => 'mcom-colleges-in-{location}',
		'keywords' => 'M.Com in {Location}, {Location} M.Com Courses, List of M.Com Colleges in {Location}, M.Com in {Location}, Types of M.Com Programs, M.Com courses, Business Schools in {Location}, Institutes in {Location}, M.Com courses, colleges, education'
),
535 => array(
		'url' => 'mba-banking-finance-courses-in-{location}',
		'keywords' => 'MBA Banking & Finance in {Location}, {Location} MBA Banking & Finance Courses, List of MBA Banking & Finance Colleges in {Location}, MBA Banking & Finance in {Location}, Types of MBA Banking & Finance Programs, MBA Banking & Finance courses, Business Schools in {Location}, Institutes in {Location}, MBA Banking & Finance courses, colleges, education'
),
536 => array(
		'url' => 'pg-diploma-accounting-taxation-courses-in-{location}',
		'keywords' => 'PG Diploma in Accounting & Taxation in {Location}, {Location} PG Diploma in Accounting & Taxation Courses, List of PG Diploma in Accounting & Taxation Colleges in {Location}, PG Diploma in Accounting & Taxation in {Location}, Types of PG Diploma in Accounting & Taxation Programs, PG Diploma in Accounting & Taxation courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Accounting & Taxation courses, colleges, education'
),
537 => array(
		'url' => 'pg-diploma-in-banking-finance-courses-in-{location}',
		'keywords' => 'PG Diploma in Banking & Finance in {Location}, {Location} PG Diploma in Banking & Finance Courses, List of PG Diploma in Banking & Finance Colleges in {Location}, PG Diploma in Banking & Finance in {Location}, Types of PG Diploma in Banking & Finance Programs, PG Diploma in Banking & Finance courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Banking & Finance courses, colleges, education'
),
538 => array(
		'url' => 'pg-diploma-in-investment-banking-courses-in-{location}',
		'keywords' => 'PG Diploma in Investment Banking in {Location}, {Location} PG Diploma in Investment Banking Courses, List of PG Diploma in Investment Banking Colleges in {Location}, PG Diploma in Investment Banking in {Location}, Types of PG Diploma in Investment Banking Programs, PG Diploma in Investment Banking courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Investment Banking courses, colleges, education'
),
539 => array(
		'url' => 'pg-diploma-in-treasury-investment-courses-in-{location}',
		'keywords' => 'PG Diploma in Treasury Investment in {Location}, {Location} PG Diploma in Treasury Investment Courses, List of PG Diploma in Treasury Investment Colleges in {Location}, PG Diploma in Treasury Investment in {Location}, Types of PG Diploma in Treasury Investment Programs, PG Diploma in Treasury Investment courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Treasury Investment courses, colleges, education'
),
540 => array(
		'url' => 'pg-diploma-in-international-banking-finance-courses-in-{location}',
		'keywords' => 'PG Diploma in International Banking & Finance in {Location}, {Location} PG Diploma in International Banking & Finance Courses, List of PG Diploma in International Banking & Finance Colleges in {Location}, PG Diploma in International Banking & Finance in {Location}, Types of PG Diploma in International Banking & Finance Programs, PG Diploma in International Banking & Finance courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in International Banking & Finance courses, colleges, education'
),
541 => array(
		'url' => 'pg-diploma-treasury-investment-courses-in-{location}',
		'keywords' => 'PG Diploma in Treasury Investment in {Location}, {Location} PG Diploma in Treasury Investment Courses, List of PG Diploma in Treasury Investment Colleges in {Location}, PG Diploma in Treasury Investment in {Location}, Types of PG Diploma in Treasury Investment Programs, PG Diploma in Treasury Investment courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Treasury Investment courses, colleges, education'
),
549 => array(
		'url' => 'certificate-in-industrial-design-certification-training-institutes-in-{location}',
		'title' => 'Certificate in Industrial Design Courses in {Location} | Shiksha.com',
		'description' => 'View Certificate in Industrial Design Courses in {location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'Certificate in Industrial Design Certification Training Institutes In {Location}, Certificate in Industrial Design Certification Courses,Certificate in Industrial Design Certification Courses, Certificate in Industrial Design Certification Training, universities, institutes, career, career options, Education'
),
550 => array(
		'url' => 'certificate-in-product-design-certification-training-institutes-in-{location}',
		'title' => 'Certificate in Product Design - Product Design Training Institutes In {Location} - shiksha.com',
		'description' => 'Search Certificate in Product Design  Training Institutes in {location} - Get a list of all Certification Courses,institutes. Log on to shiksha.com to Know more about the full and part time programs.',
		'keywords' => 'Certificate in Product Design Certification Training Institutes In {Location}, Certificate in Product Design Certification Courses,Certificate in Product Design Certification Courses, Certificate in Product Design Certification Training, universities, institutes, career, career options, Education'
),
551 => array(
		'url' => 'certificate-in-ceramic-glass-design-certification-training-institutes-in-{location}',
		'title' => 'Certificate in Ceramic & Glass Design - Ceramic & Glass Design Training Institutes In {Location} - shiksha.com',
		'description' => 'Search Certificate in Ceramic & Glass Design  Training Institutes in {location} - Get a list of all Certification Courses,institutes. Log on to shiksha.com to Know more about the full and part time programs.',
		'keywords' => 'Certificate in Ceramic & Glass Design Certification Training Institutes In {Location}, Certificate in Ceramic & Glass Design Certification Courses,Certificate in Ceramic & Glass Design Certification Courses, Certificate in Ceramic & Glass Design Certification Training, universities, institutes, career, career options, Education'
),
552 => array(
		'url' => 'certificate-in-transportation-automobile-design-certification-training-institutes-in-{location}',
		'title' => 'Certificate in Transportation  & Automobile Design Certification Training Institutes In {Location} - shiksha.com',
		'description' => 'Search Certificate in Transportation  & Automobile Design  Certification Training Institutes in {location} - Get a list of all Certification Courses,institutes. Log on to shiksha.com to Know more about the full and part time programs.',
		'keywords' => 'Certificate in Transportation  & Automobile Design Certification Training Institutes In {Location}, Certificate in Transportation  & Automobile Design Certification Courses,Certificate in Transportation  & Automobile Design Certification Courses, Certificate in Transportation  & Automobile Design Certification Training, universities, institutes, career, career options, Education'
),
553 => array(
		'url' => 'certificate-in-information-interface-design-certification-training-institutes-in-{location}',
		'title' => 'Certificate in Information & Interface Design Courses in {Location} | Shiksha.com',
		'description' => 'View Certificate in Information & Interface Design Courses in {location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'Certificate in Information & Interface Design Certification Training Institutes In {Location}, Certificate in Information & Interface Design Certification Courses,Certificate in Information & Interface Design Certification Courses, Certificate in Information & Interface Design Certification Training, universities, institutes, career, career options, Education'
),
554 => array(
		'url' => 'diploma-in-industrial-design-courses-in-{location}',
		'title' => 'Diploma in Industrial Design Courses in {Location} | Shiksha.com',
		'description' => 'View Diploma in Industrial Design Courses in {Location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'Diploma in Industrial Design in {Location}, {Location} Diploma in Industrial Design Courses, List of Diploma in Industrial Design Colleges in {Location}, Diploma in Industrial Design in {Location}, Types of Diploma in Industrial Design Programs, Diploma in Industrial Design courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Industrial Design courses, colleges, education'
),
555 => array(
		'url' => 'diploma-in-product-design-courses-in-{location}',
		'title' => 'Diploma in Product Design Courses in {Location} | Shiksha.com',
		'description' => 'View Diploma in Product Design Courses in {Location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'Diploma in Product Design in {Location}, {Location} Diploma in Product Design Courses, List of Diploma in Product Design Colleges in {Location}, Diploma in Product Design in {Location}, Types of Diploma in Product Design Programs, Diploma in Product Design courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Product Design courses, colleges, education'
),
556 => array(
		'url' => 'diploma-in-ceramic-glass-design-courses-in-{location}',
		'title' => 'Diploma in Ceramic & Glass Design Courses in {Location} - shiksha.com',
		'description' => 'Search Diploma in Ceramic & Glass Design  Courses in {Location} - Get a list of all Diploma in Ceramic & Glass Design  Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'Diploma in Ceramic & Glass Design in {Location}, {Location} Diploma in Ceramic & Glass Design Courses, List of Diploma in Ceramic & Glass Design Colleges in {Location}, Diploma in Ceramic & Glass Design in {Location}, Types of Diploma in Ceramic & Glass Design Programs, Diploma in Ceramic & Glass Design courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Ceramic & Glass Design courses, colleges, education'
),
557 => array(
		'url' => 'diploma-in-transportation-automobile-design-courses-in-{location}',
		'title' => 'Diploma in Transportation  & Automobile Design Courses in {Location} - shiksha.com',
		'description' => 'Search Diploma in Transportation  & Automobile Design  Courses in {Location} - Get a list of all Diploma in Transportation  & Automobile Design  Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'Diploma in Transportation  & Automobile Design in {Location}, {Location} Diploma in Transportation  & Automobile Design Courses, List of Diploma in Transportation  & Automobile Design Colleges in {Location}, Diploma in Transportation  & Automobile Design in {Location}, Types of Diploma in Transportation  & Automobile Design Programs, Diploma in Transportation  & Automobile Design courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Transportation  & Automobile Design courses, colleges, education'
),
558 => array(
		'url' => 'diploma-in-information-interface-design-courses-in-{location}',
		'title' => 'Diploma in Information & Interface Design Courses in {Location} | Shiksha.com',
		'description' => 'View Diploma in Information & Interface Design Courses in {Location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'Diploma in Information & Interface Design in {Location}, {Location} Diploma in Information & Interface Design Courses, List of Diploma in Information & Interface Design Colleges in {Location}, Diploma in Information & Interface Design in {Location}, Types of Diploma in Information & Interface Design Programs, Diploma in Information & Interface Design courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Information & Interface Design courses, colleges, education'
),
559 => array(
		'url' => 'pg-diploma-in-industrial-design-courses-in-{location}',
		'title' => 'PG Diploma in Industrial Design Courses in {Location} | Shiksha.com',
		'description' => 'View PG Diploma in Industrial Design Courses in {Location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'PG Diploma in Industrial Design in {Location}, {Location} PG Diploma in Industrial Design Courses, List of PG Diploma in Industrial Design Colleges in {Location}, PG Diploma in Industrial Design in {Location}, Types of PG Diploma in Industrial Design Programs, PG Diploma in Industrial Design courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Industrial Design courses, colleges, education'
),
560 => array(
		'url' => 'pg-diploma-in-ceramic-glass-design-courses-in-{location}',
		'title' => 'PG Diploma in Ceramic & Glass Design Courses in {Location} - shiksha.com',
		'description' => 'Search PG Diploma in Ceramic & Glass Design  Courses in {Location} - Get a list of all PG Diploma in Ceramic & Glass Design  Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'PG Diploma in Ceramic & Glass Design in {Location}, {Location} PG Diploma in Ceramic & Glass Design Courses, List of PG Diploma in Ceramic & Glass Design Colleges in {Location}, PG Diploma in Ceramic & Glass Design in {Location}, Types of PG Diploma in Ceramic & Glass Design Programs, PG Diploma in Ceramic & Glass Design courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Ceramic & Glass Design courses, colleges, education'
),
561 => array(
		'url' => 'pg-diploma-in-product-design-courses-in-{location}',
		'title' => 'PG Diploma in Product Design Courses in {Location} | Shiksha.com',
		'description' => 'View PG Diploma in Product Design Courses in {Location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'PG Diploma in Product Design in {Location}, {Location} PG Diploma in Product Design Courses, List of PG Diploma in Product Design Colleges in {Location}, PG Diploma in Product Design in {Location}, Types of PG Diploma in Product Design Programs, PG Diploma in Product Design courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Product Design courses, colleges, education'
),
562 => array(
		'url' => 'pg-diplom-in-transportation-automobile-design-courses-in-{location}',
		'title' => 'PG Diploma in Transportation & Automobile Design Courses in {Location} | Shiksha.com',
		'description' => 'View PG Diploma in Transportation & Automobile Design Courses in {Location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'PG Diploma in Transportation  & Automobile Design in {Location}, {Location} PG Diploma in Transportation  & Automobile Design Courses, List of PG Diploma in Transportation  & Automobile Design Colleges in {Location}, PG Diploma in Transportation  & Automobile Design in {Location}, Types of PG Diploma in Transportation  & Automobile Design Programs, PG Diploma in Transportation  & Automobile Design courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Transportation  & Automobile Design courses, colleges, education'
),
563 => array(
		'url' => 'pg-diploma-in-information-interface-design-courses-in-{location}',
		'title' => 'PG Diploma in Information & Interface Design Courses in {Location} | Shiksha.com',
		'description' => 'View PG Diploma in Information & Interface Design Courses in {Location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'PG Diploma in Information & Interface Design in {Location}, {Location} PG Diploma in Information & Interface Design Courses, List of PG Diploma in Information & Interface Design Colleges in {Location}, PG Diploma in Information & Interface Design in {Location}, Types of PG Diploma in Information & Interface Design Programs, PG Diploma in Information & Interface Design courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Information & Interface Design courses, colleges, education'
),
564 => array(
		'url' => 'ug-certificate-in-automotive-design-certification-training-institutes-in-{location}',
		'title' => 'UG Certificate in Automotive Design Automative Design Training Institutes In {Location} - shiksha.com',
		'description' => 'Search UG Certificate in Automotive Design  Training Institutes in {location} - Get a list of all Certification Courses, institutes. Log on to shiksha.com to Know more about the full and part time programs.',
		'keywords' => 'UG Certificate in Automotive Design Certification Training Institutes In {Location}, UG Certificate in Automotive Design Certification Courses,UG Certificate in Automotive Design Certification Courses, UG Certificate in Automotive Design Certification Training, universities, institutes, career, career options, Education'
),
565 => array(
		'url' => 'pg-certificate-in-automotive-design-certification-training-institutes-in-{location}',
		'title' => 'PG Certificate in Automotive Design Courses In {Location} | Shiksha.com',
		'description' => 'View PG Certificate in Automotive Design Courses in {location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'PG Certificate in Automotive Design Certification Training Institutes In {Location}, PG Certificate in Automotive Design Certification Courses,PG Certificate in Automotive Design Certification Courses, PG Certificate in Automotive Design Certification Training, universities, institutes, career, career options, Education'
),
566 => array(
		'url' => 'diploma-in-automotive-design-courses-in-{location}',
		'title' => 'Diploma in Automotive Design Courses in {Location} | shiksha.com',
		'description' => 'View Diploma in Automotive Design Courses in {Location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'Diploma in Automotive Design in {Location}, {Location} Diploma in Automotive Design Courses, List of Diploma in Automotive Design Colleges in {Location}, Diploma in Automotive Design in {Location}, Types of Diploma in Automotive Design Programs, Diploma in Automotive Design courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Automotive Design courses, colleges, education'
),
567 => array(
		'url' => 'pg-diploma-in-automotive-design-courses-in-{location}',
		'title' => 'PG Diploma in Automotive Design Courses in {Location} | Shiksha.com',
		'description' => 'View PG Diploma in Automotive Design Courses in {Location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'PG Diploma in Automotive Design in {Location}, {Location} PG Diploma in Automotive Design Courses, List of PG Diploma in Automotive Design Colleges in {Location}, PG Diploma in Automotive Design in {Location}, Types of PG Diploma in Automotive Design Programs, PG Diploma in Automotive Design courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Automotive Design courses, colleges, education'
),
568 => array(
		'url' => 'certificate-in-apparel-design-certification-training-institutes-in-{location}',
		'title' => 'Certificate in Apparel Design - Certification Training Institutes In {Location} - shiksha.com',
		'description' => 'Search Certificate in Apparel Design  Training Institutes in {location} - Get a list of all Certification Courses, institutes. Log on to shiksha.com to Know more about the full and part time programs.',
		'keywords' => 'Certificate in Apparel Design Certification Training Institutes In {Location}, Certificate in Apparel Design Certification Courses,Certificate in Apparel Design Certification Courses, Certificate in Apparel Design Certification Training, universities, institutes, career, career options, Education'
),
569 => array(
		'url' => 'certificate-in-textile-design-certification-training-institutes-in-{location}',
		'title' => 'Certificate in Textile Design - Certification Training Institutes In {Location} - shiksha.com',
		'description' => 'Search Certificate in Textile Design Training Institutes in {location} - Get a list of all Certification Courses, institutes. Log on to shiksha.com to Know more about the full and part time programs.',
		'keywords' => 'Certificate in Textile Design Certification Training Institutes In {Location}, Certificate in Textile Design Certification Courses,Certificate in Textile Design Certification Courses, Certificate in Textile Design Certification Training, universities, institutes, career, career options, Education'
),
570 => array(
		'url' => 'certificate-in-jewellery-design-certification-training-institutes-in-{location}',
		'title' => 'Certificate in Jewellery Design | Shiksha.com',
		'description' => 'View Certificate in Jewellery Design Courses in {location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'Certificate in Jewellery Design Certification Training Institutes In {Location}, Certificate in Jewellery Design Certification Courses,Certificate in Jewellery Design Certification Courses, Certificate in Jewellery Design Certification Training, universities, institutes, career, career options, Education'
),
571 => array(
		'url' => 'certificate-in-accessory-design-certification-training-institutes-in-{location}',
		'title' => 'Certificate in Accessory Design | Shiksha.com',
		'description' => 'View Certificate in Accessory Design Courses in {location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'Certificate in Accessory Design Certification Training Institutes In {Location}, Certificate in Accessory Design Certification Courses,Certificate in Accessory Design Certification Courses, Certificate in Accessory Design Certification Training, universities, institutes, career, career options, Education'
),
572 => array(
		'url' => 'diploma-in-apparel-design-courses-in-{location}',
		'title' => 'Diploma in Apparel Design Courses in {Location} - shiksha.com',
		'description' => 'Search Diploma in Apparel Design Courses in {Location} - Get a list of all Diploma in Apparel Design  Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'Diploma in Apparel Design in {Location}, {Location} Diploma in Apparel Design Courses, List of Diploma in Apparel Design Colleges in {Location}, Diploma in Apparel Design in {Location}, Types of Diploma in Apparel Design Programs, Diploma in Apparel Design courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Apparel Design courses, colleges, education'
),
573 => array(
		'url' => 'diploma-in-textile-design-courses-in-{location}',
		'keywords' => 'Diploma in Textile Design in {Location}, {Location} Diploma in Textile Design Courses, List of Diploma in Textile Design Colleges in {Location}, Diploma in Textile Design in {Location}, Types of Diploma in Textile Design Programs, Diploma in Textile Design courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Textile Design courses, colleges, education'
),
574 => array(
		'url' => 'diploma-in-jewellery-design-courses-in-{location}',
		'keywords' => 'Diploma in Jewellery Design in {Location}, {Location} Diploma in Jewellery Design Courses, List of Diploma in Jewellery Design Colleges in {Location}, Diploma in Jewellery Design in {Location}, Types of Diploma in Jewellery Design Programs, Diploma in Jewellery Design courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Jewellery Design courses, colleges, education'
),
575 => array(
		'url' => 'diploma-in-accessory-design-courses-in-{location}',
		'title' => 'Diploma in Accessory Design Courses in {Location} | Shiksha.com',
		'description' => 'View Diploma in Accessory Design Courses in {Location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'Diploma in Accessory Design in {Location}, {Location} Diploma in Accessory Design Courses, List of Diploma in Accessory Design Colleges in {Location}, Diploma in Accessory Design in {Location}, Types of Diploma in Accessory Design Programs, Diploma in Accessory Design courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Accessory Design courses, colleges, education'
),
576 => array(
		'url' => 'pg-diploma-in-apparel-design-courses-in-{location}',
		'title' => 'PG Diploma in Apparel Design Courses in {Location} - shiksha.com',
		'description' => 'Search PG Diploma in Apparel Design Courses in {Location} - Get a list of all PG Diploma in Apparel Design  Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'PG Diploma in Apparel Design in {Location}, {Location} PG Diploma in Apparel Design Courses, List of PG Diploma in Apparel Design Colleges in {Location}, PG Diploma in Apparel Design in {Location}, Types of PG Diploma in Apparel Design Programs, PG Diploma in Apparel Design courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Apparel Design courses, colleges, education'
),
577 => array(
		'url' => 'pg-diploma-in-textile-design-courses-in-{location}',
		'title' => 'PG Diploma in Textile Design Courses in {Location} - shiksha.com',
		'description' => 'Search PG Diploma in Textile Design Courses in {Location} - Get a list of all PG Diploma in Textile Design  Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'PG Diploma in Textile Design in {Location}, {Location} PG Diploma in Textile Design Courses, List of PG Diploma in Textile Design Colleges in {Location}, PG Diploma in Textile Design in {Location}, Types of PG Diploma in Textile Design Programs, PG Diploma in Textile Design courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Textile Design courses, colleges, education'
),
578 => array(
		'url' => 'pg-diploma-in-jewellery-design-courses-in-{location}',
		'title' => 'PG Diploma in Jewellery Design Courses in {Location} | Shiksha.com',
		'description' => 'View PG Diploma in Jewellery Design Courses in {Location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'PG Diploma in Jewellery Design in {Location}, {Location} PG Diploma in Jewellery Design Courses, List of PG Diploma in Jewellery Design Colleges in {Location}, PG Diploma in Jewellery Design in {Location}, Types of PG Diploma in Jewellery Design Programs, PG Diploma in Jewellery Design courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Jewellery Design courses, colleges, education'
),
579 => array(
		'url' => 'pg-diploma-in-accessory-design-courses-in-{location}',
		'title' => 'PG Diploma in Accessory Design Courses in {Location} | Shiksha.com',
		'description' => 'View PG Diploma in Accessory Design Courses in {Location} with their admission criteria, fees, duration, placements, alumni ratings, and more details.',
		'keywords' => 'PG Diploma in Accessory Design in {Location}, {Location} PG Diploma in Accessory Design Courses, List of PG Diploma in Accessory Design Colleges in {Location}, PG Diploma in Accessory Design in {Location}, Types of PG Diploma in Accessory Design Programs, PG Diploma in Accessory Design courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Accessory Design courses, colleges, education'
),
644 => array(
		'url' => 'architecture-colleges-in-{location}',
		'keywords' => 'Architecture in {Location}, {Location} Architecture Courses, List of Architecture Colleges in {Location}, Architecture in {Location}, Types of Architecture Programs, Architecture courses, Business Schools in {Location}, Institutes in {Location}, Architecture courses, colleges, education'
),
675 => array(
		'url' => 'phd-electronics-communications-engineering-in-{location}',
		'keywords' => 'Phd in {Location}, {Location} Phd Courses, List of Phd Colleges in {Location}, Phd in {Location}, Types of Phd Programs, Phd courses, Business Schools in {Location}, Institutes in {Location}, Phd courses, colleges, education'
),
698 => array(
		'url' => 'diploma-in-event-management-courses-in-{location}',
		'keywords' => 'Diploma in Event Management in {Location}, {Location} Diploma in Event Management Courses, List of Diploma in Event Management Colleges in {Location}, Diploma in Event Management in {Location}, Types of Diploma in Event Management Programs, Diploma in Event Management courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Event Management courses, colleges, education'
),
709 => array(
		'url' => 'vb-vbnet-certification-training-institutes-in-{location}',
		'keywords' => 'VB VB.NET Certification Training Institutes In {Location}, VB VB.NET Certification Courses,VB VB.NET Certification Courses, VB VB.NET Certification Training, universities, institutes, career, career options, Education'
),
710 => array(
		'url' => 'part-time-mba-courses-in-{location}',
		'title' => 'Part time MBA Courses in {Location} - shiksha.com',
		'description' => 'Search Part time MBA  Courses in {Location} - Get a list of all Part time MBA  Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'Part time MBA in {Location}, {Location} Part time MBA Courses, List of Part time MBA Colleges in {Location}, Part time MBA in {Location}, Types of Part time MBA Programs, Part time MBA courses, Business Schools in {Location}, Institutes in {Location}, Part time MBA courses, colleges, education'
),
711 => array(
		'url' => 'online-mba-colleges-in-{location}',
		'title' => 'Online MBA Institutes in {Location} - shiksha.com',
		'description' => 'Search Online MBA  Institutes in {Location} - Get a list of all Online MBA  Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'Online MBA in {Location}, {Location} Online MBA Courses, List of Online MBA Colleges in {Location}, Online MBA in {Location}, Types of Online MBA Programs, Online MBA courses, Business Schools in {Location}, Institutes in {Location}, Online MBA courses, colleges, education'
),
712 => array(
		'url' => 'certifications-in-{location}',
		'title' => 'Certifications Certification Training Institutes In {Location} - shiksha.com',
		'description' => 'Search Certifications  Certification Training Institutes in {location} - Get a list of all Certification Courses,institutes. Log on to shiksha.com to Know more about the full and part time programs.',
		'keywords' => 'Certifications Certification Training Institutes In {Location}, Certifications Certification Courses,Certifications Certification Courses, Certifications Certification Training, universities, institutes, career, career options, Education'
),
727 => array(
		'url' => 'distance-mba-in-import-export-in-{location}',
		'title' => 'Distance/Correspondence MBA in Import/Export colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Distance/Correspondence MBA in Import/Export colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Distance MBA in Import/Export in {Location}, {Location} Distance MBA in Import/Export Colleges, List of Distance MBA in Import/Export in {Location}, Distance MBA in {Location}, Types of Distance MBA Programs, Distance MBA courses, Business Schools in {Location}, Institutes in {Location}, Distance MBA courses, colleges, education'
),
736 => array(
		'url' => 'part-time-mba-in-general-management-in-{location}',
		'title' => 'Part time MBA in General Management Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Part time MBA in General Management colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Part time MBA in General Management in {Location}, {Location} Part time MBA in General Management Colleges, List of Part time MBA in General Management in {Location}, Part time MBA in {Location}, Types of Part time MBA Programs, Part time MBA courses, Business Schools in {Location}, Institutes in {Location}, Part time MBA courses, colleges, education'
),
742 => array(
		'url' => 'part-time-mba-import-export-in-{location}',
		'title' => 'Part time MBA in Import/Export Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Part time MBA in Import/Export colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Part time MBA in Import/Export in {Location}, {Location} Part time MBA in Import/Export Colleges, List of Part time MBA in Import/Export in {Location}, Part time MBA in {Location}, Types of Part time MBA Programs, Part time MBA courses, Business Schools in {Location}, Institutes in {Location}, Part time MBA courses, colleges, education'
),
743 => array(
		'url' => 'part-time-mba-retail-in-{location}',
		'title' => 'Part-time MBA in Retail Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Part-time MBA in Retail colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Part-time MBA in Retail in {Location}, {Location} Part-time MBA in Retail Colleges, List of Part-time MBA in Retail in {Location}, Part-time MBA in {Location}, Types of Part-time MBA Programs, Part-time MBA courses, Business Schools in {Location}, Institutes in {Location}, Part-time MBA courses, colleges, education'
),
753 => array(
		'url' => 'online-mba-general-management-in-{location}',
		'title' => 'Online MBA in General Management Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Online MBA in General Management colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Online MBA in General Management in {Location}, {Location} Online MBA in General Management Colleges, List of Online MBA in General Management in {Location}, Online MBA in {Location}, Types of Online MBA Programs, Online MBA courses, Business Schools in {Location}, Institutes in {Location}, Online MBA courses, colleges, education'
),
759 => array(
		'url' => 'online-mba-in-import-export-in-{location}',
		'title' => 'Online MBA in Import/Export Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Online MBA in Import/Export colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Online MBA in Import/Export in {Location}, {Location} Online MBA in Import/Export Colleges, List of Online MBA in Import/Export in {Location}, Online MBA in {Location}, Types of Online MBA Programs, Online MBA courses, Business Schools in {Location}, Institutes in {Location}, Online MBA courses, colleges, education'
),
760 => array(
		'url' => 'online-mba-in-retail-in-{location}',
		'title' => 'Online MBA in Retail Colleges in {Location} | Shiksha.com',
		'description' => 'View {resultCount} Online MBA in Retail colleges in {Location}, check their courses, placements, fees, admissions, alumni reviews, eligibility, and more details.',
		'keywords' => 'Online MBA in Retail in {Location}, {Location} Online MBA in Retail Colleges, List of Online MBA in Retail in {Location}, Online MBA in {Location}, Types of Online MBA Programs, Online MBA courses, Business Schools in {Location}, Institutes in {Location}, Online MBA courses, colleges, education'
),
781 => array(
		'url' => 'bba-bbm-colleges-in-{location}',
		'title' => 'BBA/BBM Colleges in {Location} - shiksha.com',
		'description' => 'Search BBA/BBM  Colleges in {Location} - Get a list of all BBA/BBM  in Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'BBA/BBM in {Location}, {Location} BBA/BBM Courses, List of BBA/BBM Colleges in {Location}, BBA/BBM in {Location}, Types of BBA/BBM Programs, BBA/BBM courses, Business Schools in {Location}, Institutes in {Location}, BBA/BBM courses, colleges, education'
),
782 => array(
		'url' => 'integrated-btech-mba-colleges-in-{location}',
		'keywords' => 'Integrated (B.Tech + MBA) in {Location}, {Location} Integrated (B.Tech + MBA) Courses, List of Integrated (B.Tech + MBA) Colleges in {Location}, Integrated (B.Tech + MBA) in {Location}, Types of Integrated (B.Tech + MBA) Programs, Integrated (B.Tech + MBA) courses, Business Schools in {Location}, Institutes in {Location}, Integrated (B.Tech + MBA) courses, colleges, education'
),
783 => array(
		'url' => 'integrated-bba mba-colleges-in-{location}',
		'title' => 'Integrated (BBA  + MBA) Colleges in {Location} - shiksha.com',
		'description' => 'Search Integrated (BBA  + MBA)  Colleges in {Location} - Get a list of all Integrated (BBA  + MBA)  in Colleges, courses and institutes. Know more about the full and part time programs.',
		'keywords' => 'Integrated (BBA  + MBA) in {Location}, {Location} Integrated (BBA  + MBA) Courses, List of Integrated (BBA  + MBA) Colleges in {Location}, Integrated (BBA  + MBA) in {Location}, Types of Integrated (BBA  + MBA) Programs, Integrated (BBA  + MBA) courses, Business Schools in {Location}, Institutes in {Location}, Integrated (BBA  + MBA) courses, colleges, education'
),
794 => array(
		'url' => 'pg-diploma-in-front-office-management-colleges-in-{location}',
		'keywords' => 'PG Diploma in Front Office Management in {Location}, {Location} PG Diploma in Front Office Management Courses, List of PG Diploma in Front Office Management Colleges in {Location}, PG Diploma in Front Office Management in {Location}, Types of PG Diploma in Front Office Management Programs, PG Diploma in Front Office Management courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Front Office Management courses, colleges, education'
),
795 => array(
		'url' => 'pg-diploma-in-inventory-management-colleges-in-{location}',
		'keywords' => 'PG Diploma in Inventory Management in {Location}, {Location} PG Diploma in Inventory Management Courses, List of PG Diploma in Inventory Management Colleges in {Location}, PG Diploma in Inventory Management in {Location}, Types of PG Diploma in Inventory Management Programs, PG Diploma in Inventory Management courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Inventory Management courses, colleges, education'
),
796 => array(
		'url' => 'pg-diploma-in-shop-floor management-colleges-in-{location}',
		'keywords' => 'PG Diploma in Shop Floor Management in {Location}, {Location} PG Diploma in Shop Floor Management Courses, List of PG Diploma in Shop Floor Management Colleges in {Location}, PG Diploma in Shop Floor Management in {Location}, Types of PG Diploma in Shop Floor Management Programs, PG Diploma in Shop Floor Management courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Shop Floor Management courses, colleges, education'
),
797 => array(
		'url' => 'pg-diploma-in-store-management-colleges-in-{location}',
		'keywords' => 'PG Diploma in Store Management in {Location}, {Location} PG Diploma in Store Management Courses, List of PG Diploma in Store Management Colleges in {Location}, PG Diploma in Store Management in {Location}, Types of PG Diploma in Store Management Programs, PG Diploma in Store Management courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Store Management courses, colleges, education'
),
798 => array(
		'url' => 'pg-diploma-in-supply-chain-management-colleges-in-{location}',
		'keywords' => 'PG Diploma in Supply Chain Management in {Location}, {Location} PG Diploma in Supply Chain Management Courses, List of PG Diploma in Supply Chain Management Colleges in {Location}, PG Diploma in Supply Chain Management in {Location}, Types of PG Diploma in Supply Chain Management Programs, PG Diploma in Supply Chain Management courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Supply Chain Management courses, colleges, education'
),
799 => array(
		'url' => 'pg-diploma-in-merchandising-colleges-in-{location}',
		'keywords' => 'PG Diploma in Merchandising in {Location}, {Location} PG Diploma in Merchandising Courses, List of PG Diploma in Merchandising Colleges in {Location}, PG Diploma in Merchandising in {Location}, Types of PG Diploma in Merchandising Programs, PG Diploma in Merchandising courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Merchandising courses, colleges, education'
),
800 => array(
		'url' => 'diploma-in-front-office-management-colleges-in-{location}',
		'keywords' => 'Diploma in Front Office Management in {Location}, {Location} Diploma in Front Office Management Courses, List of Diploma in Front Office Management Colleges in {Location}, Diploma in Front Office Management in {Location}, Types of Diploma in Front Office Management Programs, Diploma in Front Office Management courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Front Office Management courses, colleges, education'
),
801 => array(
		'url' => 'diploma-in-inventory-management-colleges-in-{location}',
		'keywords' => 'Diploma in Inventory Management in {Location}, {Location} Diploma in Inventory Management Courses, List of Diploma in Inventory Management Colleges in {Location}, Diploma in Inventory Management in {Location}, Types of Diploma in Inventory Management Programs, Diploma in Inventory Management courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Inventory Management courses, colleges, education'
),
802 => array(
		'url' => 'diploma-in-shop-floor-management-colleges-in-{location}',
		'keywords' => 'Diploma in Shop Floor Management in {Location}, {Location} Diploma in Shop Floor Management Courses, List of Diploma in Shop Floor Management Colleges in {Location}, Diploma in Shop Floor Management in {Location}, Types of Diploma in Shop Floor Management Programs, Diploma in Shop Floor Management courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Shop Floor Management courses, colleges, education'
),
803 => array(
		'url' => 'diploma-in-store-management-colleges-in-{location}',
		'keywords' => 'Diploma in Store Management in {Location}, {Location} Diploma in Store Management Courses, List of Diploma in Store Management Colleges in {Location}, Diploma in Store Management in {Location}, Types of Diploma in Store Management Programs, Diploma in Store Management courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Store Management courses, colleges, education'
),
804 => array(
		'url' => 'diploma-in-supply-chain-management-colleges-in-{location}',
		'keywords' => 'Diploma in Supply Chain Management in {Location}, {Location} Diploma in Supply Chain Management Courses, List of Diploma in Supply Chain Management Colleges in {Location}, Diploma in Supply Chain Management in {Location}, Types of Diploma in Supply Chain Management Programs, Diploma in Supply Chain Management courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Supply Chain Management courses, colleges, education'
),
805 => array(
		'url' => 'diploma-in-merchandising-colleges-in-{location}',
		'keywords' => 'Diploma in Merchandising in {Location}, {Location} Diploma in Merchandising Courses, List of Diploma in Merchandising Colleges in {Location}, Diploma in Merchandising in {Location}, Types of Diploma in Merchandising Programs, Diploma in Merchandising courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Merchandising courses, colleges, education'
),
806 => array(
		'url' => 'diploma-in-retail-management-colleges-in-{location}',
		'keywords' => 'Diploma in Retail Management in {Location}, {Location} Diploma in Retail Management Courses, List of Diploma in Retail Management Colleges in {Location}, Diploma in Retail Management in {Location}, Types of Diploma in Retail Management Programs, Diploma in Retail Management courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Retail Management courses, colleges, education'
),
807 => array(
		'url' => 'pg-diploma-in-retail-management-colleges-in-{location}',
		'keywords' => 'PG Diploma in Retail Management in {Location}, {Location} PG Diploma in Retail Management Courses, List of PG Diploma in Retail Management Colleges in {Location}, PG Diploma in Retail Management in {Location}, Types of PG Diploma in Retail Management Programs, PG Diploma in Retail Management courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Retail Management courses, colleges, education'
),
934 => array(
		'url' => 'bcom-coaching-in-{Location}',
		'keywords' => 'B Com Coaching in {Location}, Entrance Exam For B.com, B Com, B.Com Coaching classes, universities, institutes, career, career options, Education,shiksha, college'
),
935 => array(
		'url' => 'bsc-coaching-in-{Location}',
		'keywords' => 'Bsc Coaching in {Location}, Entrance Exam For Bsc, BSc colleges, B.Sc Coaching classes, universities, institutes, career, career options, Education,shiksha, college'
),
993 => array(
		'url' => 'mba-pgdm-in-retail-management-in-{location}',
		'keywords' => 'Full Time MBA/PGDM in Retail in {Location}, {Location} Full Time MBA/PGDM in Retail Colleges, List of Full Time MBA/PGDM in Retail in {Location}, Full Time MBA/PGDM in {Location}, Types of Full Time MBA/PGDM Programs, Full Time MBA/PGDM courses, Business Schools in {Location}, Institutes in {Location}, Full Time MBA/PGDM courses, colleges, education'
),
1258 => array(
		'url' => 'physiotherapy-colleges-in-{location}',
		'keywords' => 'Physiotherapy in {Location}, {Location} Physiotherapy Courses, List of Physiotherapy Colleges in {Location}, Physiotherapy in {Location}, Types of Physiotherapy Programs, Physiotherapy courses, Business Schools in {Location}, Institutes in {Location}, Physiotherapy courses, colleges, education'
),
1264 => array(
		'url' => 'mbbs-medical-colleges-in-{location}',
		'keywords' => 'MBBS in {Location}, {Location} MBBS Courses, List of MBBS Colleges in {Location}, MBBS in {Location}, Types of MBBS Programs, MBBS courses, Business Schools in {Location}, Institutes in {Location}, MBBS courses, colleges, education'
),
1265 => array(
		'url' => 'md-courses-in-{location}',
		'keywords' => 'MD in {Location}, {Location} MD Courses, List of MD Colleges in {Location}, MD in {Location}, Types of MD Programs, MD courses, Business Schools in {Location}, Institutes in {Location}, MD courses, colleges, education'
),
1277 => array(
		'url' => 'physiotherapy-colleges-in-{location}',
		'keywords' => 'Physiotherapy in {Location}, {Location} Physiotherapy Courses, List of Physiotherapy Colleges in {Location}, Physiotherapy in {Location}, Types of Physiotherapy Programs, Physiotherapy courses, Business Schools in {Location}, Institutes in {Location}, Physiotherapy courses, colleges, education'
),
1280 => array(
		'url' => 'diploma-in-cosmetology-colleges-in-{location}',
		'keywords' => 'Diploma in Cosmetology in {Location}, {Location} Diploma in Cosmetology Courses, List of Diploma in Cosmetology Colleges in {Location}, Diploma in Cosmetology in {Location}, Types of Diploma in Cosmetology Programs, Diploma in Cosmetology courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Cosmetology courses, colleges, education'
),
1281 => array(
		'url' => 'diploma-in-beauty-culture-colleges-in-{location}',
		'keywords' => 'Diploma in Beauty Culture in {Location}, {Location} Diploma in Beauty Culture Courses, List of Diploma in Beauty Culture Colleges in {Location}, Diploma in Beauty Culture in {Location}, Types of Diploma in Beauty Culture Programs, Diploma in Beauty Culture courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Beauty Culture courses, colleges, education'
),
1282 => array(
		'url' => 'diploma-in-hair-dressing-colleges-in-{location}',
		'keywords' => 'Diploma in Hair Dressing in {Location}, {Location} Diploma in Hair Dressing Courses, List of Diploma in Hair Dressing Colleges in {Location}, Diploma in Hair Dressing in {Location}, Types of Diploma in Hair Dressing Programs, Diploma in Hair Dressing courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Hair Dressing courses, colleges, education'
),
1283 => array(
		'url' => 'certificate-course-in-beauty-culture-certification-training-institutes-in-{location}',
		'keywords' => 'Certificate Course in Beauty Culture Certification Training Institutes In {Location}, Certificate Course in Beauty Culture Certification Courses,Certificate Course in Beauty Culture Certification Courses, Certificate Course in Beauty Culture Certification Training, universities, institutes, career, career options, Education'
),
1284 => array(
		'url' => 'certificate-course-in-hair-dressing-certification-training-institutes-in-{location}',
		'keywords' => 'Certificate Course in Hair Dressing Certification Training Institutes In {Location}, Certificate Course in Hair Dressing Certification Courses,Certificate Course in Hair Dressing Certification Courses, Certificate Course in Hair Dressing Certification Training, universities, institutes, career, career options, Education'
),
1285 => array(
		'url' => 'diploma-in-makeup-colleges-in-{location}',
		'keywords' => 'Diploma in Makeup in {Location}, {Location} Diploma in Makeup Courses, List of Diploma in Makeup Colleges in {Location}, Diploma in Makeup in {Location}, Types of Diploma in Makeup Programs, Diploma in Makeup courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Makeup courses, colleges, education'
),
1286 => array(
		'url' => 'advanced-diploma-in-cosmetology-colleges-in-{location}',
		'keywords' => 'Advanced Diploma in Cosmetology in {Location}, {Location} Advanced Diploma in Cosmetology Courses, List of Advanced Diploma in Cosmetology Colleges in {Location}, Advanced Diploma in Cosmetology in {Location}, Types of Advanced Diploma in Cosmetology Programs, Advanced Diploma in Cosmetology courses, Business Schools in {Location}, Institutes in {Location}, Advanced Diploma in Cosmetology courses, colleges, education'
),
1287 => array(
		'url' => 'advanced-certificate-in-beauty-culture-certification-training-institutes-in-{location}',
		'keywords' => 'Advanced Certificate in Beauty Culture Certification Training Institutes In {Location}, Advanced Certificate in Beauty Culture Certification Courses,Advanced Certificate in Beauty Culture Certification Courses, Advanced Certificate in Beauty Culture Certification Training, universities, institutes, career, career options, Education'
),
1288 => array(
		'url' => 'advanced-certificate-in-hair-designing-certification-training-institutes-in-{location}',
		'keywords' => 'Advanced Certificate in Hair Designing Certification Training Institutes In {Location}, Advanced Certificate in Hair Designing Certification Courses,Advanced Certificate in Hair Designing Certification Courses, Advanced Certificate in Hair Designing Certification Training, universities, institutes, career, career options, Education'
),
1289 => array(
		'url' => 'post-graduate-diploma-in-cosmetology-colleges-in-{location}',
		'keywords' => 'Post Graduate Diploma in Cosmetology in {Location}, {Location} Post Graduate Diploma in Cosmetology Courses, List of Post Graduate Diploma in Cosmetology Colleges in {Location}, Post Graduate Diploma in Cosmetology in {Location}, Types of Post Graduate Diploma in Cosmetology Programs, Post Graduate Diploma in Cosmetology courses, Business Schools in {Location}, Institutes in {Location}, Post Graduate Diploma in Cosmetology courses, colleges, education'
),
1290 => array(
		'url' => 'certificate-course-in-spa-therapies-certification-training-institutes-in-{location}',
		'keywords' => 'Certificate Course in Spa Therapies Certification Training Institutes In {Location}, Certificate Course in Spa Therapies Certification Courses,Certificate Course in Spa Therapies Certification Courses, Certificate Course in Spa Therapies Certification Training, universities, institutes, career, career options, Education'
),
1291 => array(
		'url' => 'diploma-in-dietics-health-nutrition-colleges-in-{location}',
		'keywords' => 'Diploma in Dietics, Health and Nutrition in {Location}, {Location} Diploma in Dietics, Health and Nutrition Courses, List of Diploma in Dietics, Health and Nutrition Colleges in {Location}, Diploma in Dietics, Health and Nutrition in {Location}, Types of Diploma in Dietics, Health and Nutrition Programs, Diploma in Dietics, Health and Nutrition courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Dietics, Health and Nutrition courses, colleges, education'
),
1292 => array(
		'url' => 'distance-certification-health-nutrition-certification-training-institutes-in-{location}',
		'keywords' => 'Distance Certification in Health and Nutrition Certification Training Institutes In {Location}, Distance Certification in Health and Nutrition Certification Courses,Distance Certification in Health and Nutrition Certification Courses, Distance Certification in Health and Nutrition Certification Training, universities, institutes, career, career options, Education'
),
1293 => array(
		'url' => 'diploma-in-photography-colleges-in-{location}',
		'keywords' => 'Diploma in Photography in {Location}, {Location} Diploma in Photography Courses, List of Diploma in Photography Colleges in {Location}, Diploma in Photography in {Location}, Types of Diploma in Photography Programs, Diploma in Photography courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Photography courses, colleges, education'
),
1294 => array(
		'url' => 'pg-diploma-in-photography-colleges-in-{location}',
		'keywords' => 'PG Diploma in Photography in {Location}, {Location} PG Diploma in Photography Courses, List of PG Diploma in Photography Colleges in {Location}, PG Diploma in Photography in {Location}, Types of PG Diploma in Photography Programs, PG Diploma in Photography courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Photography courses, colleges, education'
),
1295 => array(
		'url' => 'diploma-in-advertising-pr-colleges-in-{location}',
		'keywords' => 'Diploma in Advertising & PR in {Location}, {Location} Diploma in Advertising & PR Courses, List of Diploma in Advertising & PR Colleges in {Location}, Diploma in Advertising & PR in {Location}, Types of Diploma in Advertising & PR Programs, Diploma in Advertising & PR courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Advertising & PR courses, colleges, education'
),
1296 => array(
		'url' => 'pg-diploma-in-advertising-pr-colleges-in-{location}',
		'keywords' => 'PG Diploma in Advertising & PR in {Location}, {Location} PG Diploma in Advertising & PR Courses, List of PG Diploma in Advertising & PR Colleges in {Location}, PG Diploma in Advertising & PR in {Location}, Types of PG Diploma in Advertising & PR Programs, PG Diploma in Advertising & PR courses, Business Schools in {Location}, Institutes in {Location}, PG Diploma in Advertising & PR courses, colleges, education'
),
1297 => array(
		'url' => 'certificate-in-photography-certification-training-institutes-in-{location}',
		'keywords' => 'Certificate in Photography Certification Training Institutes In {Location}, Certificate in Photography Certification Courses,Certificate in Photography Certification Courses, Certificate in Photography Certification Training, universities, institutes, career, career options, Education'
),
1298 => array(
		'url' => 'ma-economics-colleges-in-{location}',
		'keywords' => 'M.A. Economics in {Location}, {Location} M.A. Economics Courses, List of M.A. Economics Colleges in {Location}, M.A. Economics in {Location}, Types of M.A. Economics Programs, M.A. Economics courses, Business Schools in {Location}, Institutes in {Location}, M.A. Economics courses, colleges, education'
),
1299 => array(
		'url' => 'ba-economics-colleges-in-{location}',
		'keywords' => 'B.A. Economics in {Location}, {Location} B.A. Economics Courses, List of B.A. Economics Colleges in {Location}, B.A. Economics in {Location}, Types of B.A. Economics Programs, B.A. Economics courses, Business Schools in {Location}, Institutes in {Location}, B.A. Economics courses, colleges, education'
),
1300 => array(
		'url' => 'phd-colleges-in-{location}',
		'keywords' => 'PhD in {Location}, {Location} PhD Courses, List of PhD Colleges in {Location}, PhD in {Location}, Types of PhD Programs, PhD courses, Business Schools in {Location}, Institutes in {Location}, PhD courses, colleges, education'
),
1301 => array(
		'url' => 'blib-colleges-in-{location}',
		'keywords' => 'B. Lib in {Location}, {Location} B. Lib Courses, List of B. Lib Colleges in {Location}, B. Lib in {Location}, Types of B. Lib Programs, B. Lib courses, Business Schools in {Location}, Institutes in {Location}, B. Lib courses, colleges, education'
),
1302 => array(
		'url' => 'mlib-colleges-in-{location}',
		'keywords' => 'M.Lib in {Location}, {Location} M.Lib Courses, List of M.Lib Colleges in {Location}, M.Lib in {Location}, Types of M.Lib Programs, M.Lib courses, Business Schools in {Location}, Institutes in {Location}, M.Lib courses, colleges, education'
),
1303 => array(
		'url' => 'diploma-in-law-colleges-in-{location}',
		'keywords' => 'Diploma in Law in {Location}, {Location} Diploma in Law Courses, List of Diploma in Law Colleges in {Location}, Diploma in Law in {Location}, Types of Diploma in Law Programs, Diploma in Law courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Law courses, colleges, education'
),
1304 => array(
		'url' => 'web-application-certification-training-institutes-in-{location}',
		'keywords' => 'Web Application Certification Training Institutes In {Location}, Web Application Certification Courses,Web Application Certification Courses, Web Application Certification Training, universities, institutes, career, career options, Education'
),
1305 => array(
		'url' => 'diploma-in-management-colleges-in-{location}',
		'keywords' => 'Diploma in Management in {Location}, {Location} Diploma in Management Courses, List of Diploma in Management Colleges in {Location}, Diploma in Management in {Location}, Types of Diploma in Management Programs, Diploma in Management courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Management courses, colleges, education'
),
1306 => array(
		'url' => 'advance-diploma-in-management-colleges-in-{location}',
		'keywords' => 'Advance Diploma in Management in {Location}, {Location} Advance Diploma in Management Courses, List of Advance Diploma in Management Colleges in {Location}, Advance Diploma in Management in {Location}, Types of Advance Diploma in Management Programs, Advance Diploma in Management courses, Business Schools in {Location}, Institutes in {Location}, Advance Diploma in Management courses, colleges, education'
),
1307 => array(
		'url' => 'mba-logistics-management-colleges-in-{location}',
		'keywords' => 'MBA in Logistics Management in {Location}, {Location} MBA in Logistics Management Colleges, List of MBA in Logistics Management in {Location}, MBA in {Location}, Types of MBA Programs, MBA courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
1308 => array(
		'url' => 'phd-fellow-programme-in-management-colleges-in-{location}',
		'keywords' => 'PhD / Fellow Programme in Management in {Location}, {Location} PhD / Fellow Programme in Management Courses, List of PhD / Fellow Programme in Management Colleges in {Location}, PhD / Fellow Programme in Management in {Location}, Types of PhD / Fellow Programme in Management Programs, PhD / Fellow Programme in Management courses, Business Schools in {Location}, Institutes in {Location}, PhD / Fellow Programme in Management courses, colleges, education'
),
1309 => array(
		'url' => 'retail-certification-training-institutes-in-{location}',
		'keywords' => 'Certifications in Retail in {Location}, {Location} Certifications in Retail Colleges, List of Certifications in Retail in {Location}, Certifications in {Location}, Types of Certifications Programs, Certifications courses, Business Schools in {Location}, Institutes in {Location}, Certifications courses, colleges, education'
),
1310 => array(
		'url' => 'mba-agribusiness-management-colleges-in-{location}',
		'keywords' => 'MBA in Agribusiness Management in {Location}, {Location} MBA in Agribusiness Management Colleges, List of MBA in Agribusiness Management in {Location}, MBA in {Location}, Types of MBA Programs, MBA courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
1311 => array(
		'url' => 'mba-entrereneurship-in-colleges-{location}',
		'keywords' => 'MBA in Entrereneurship in {Location}, {Location} MBA in Entrereneurship Colleges, List of MBA in Entrereneurship in {Location}, MBA in {Location}, Types of MBA Programs, MBA courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
1312 => array(
		'url' => 'mba-pharma-management-colleges-in-{location}',
		'keywords' => 'MBA in Pharma Management in {Location}, {Location} MBA in Pharma Management Colleges, List of MBA in Pharma Management in {Location}, MBA in {Location}, Types of MBA Programs, MBA courses, Business Schools in {Location}, Institutes in {Location}, MBA courses, colleges, education'
),
1313 => array(
		'url' => 'mphil-in-management-colleges-in-{location}',
		'keywords' => 'M.Phil in Management in {Location}, {Location} M.Phil in Management Courses, List of M.Phil in Management Colleges in {Location}, M.Phil in Management in {Location}, Types of M.Phil in Management Programs, M.Phil in Management courses, Business Schools in {Location}, Institutes in {Location}, M.Phil in Management courses, colleges, education'
),
1314 => array(
		'url' => 'diploma-in-quality-management-colleges-in-{location}',
		'keywords' => 'Diploma in Quality Management in {Location}, {Location} Diploma in Quality Management Courses, List of Diploma in Quality Management Colleges in {Location}, Diploma in Quality Management in {Location}, Types of Diploma in Quality Management Programs, Diploma in Quality Management courses, Business Schools in {Location}, Institutes in {Location}, Diploma in Quality Management courses, colleges, education'
),
1315 => array(
		'url' => 'bsc-microbiology-colleges-in-{location}',
		'keywords' => 'B.Sc Microbiology in {Location}, {Location} B.Sc Microbiology Courses, List of B.Sc Microbiology Colleges in {Location}, B.Sc Microbiology in {Location}, Types of B.Sc Microbiology Programs, B.Sc Microbiology courses, Business Schools in {Location}, Institutes in {Location}, B.Sc Microbiology courses, colleges, education'
),
1316 => array(
		'url' => 'bsc-nursing-colleges-in-{location}',
		'keywords' => 'B.Sc Nursing in {Location}, {Location} B.Sc Nursing Courses, List of B.Sc Nursing Colleges in {Location}, B.Sc Nursing in {Location}, Types of B.Sc Nursing Programs, B.Sc Nursing courses, Business Schools in {Location}, Institutes in {Location}, B.Sc Nursing courses, colleges, education'
),
1317 => array(
		'url' => 'clinical-psychology-colleges-in-{location}',
		'keywords' => 'Clinical Psychology in {Location}, {Location} Clinical Psychology Courses, List of Clinical Psychology Colleges in {Location}, Clinical Psychology in {Location}, Types of Clinical Psychology Programs, Clinical Psychology courses, Business Schools in {Location}, Institutes in {Location}, Clinical Psychology courses, colleges, education'
),
1318 => array(
		'url' => 'msc-microbiology-colleges-in-{location}',
		'keywords' => 'M.Sc Microbiology in {Location}, {Location} M.Sc Microbiology Courses, List of M.Sc Microbiology Colleges in {Location}, M.Sc Microbiology in {Location}, Types of M.Sc Microbiology Programs, M.Sc Microbiology courses, Business Schools in {Location}, Institutes in {Location}, M.Sc Microbiology courses, colleges, education'
),
1319 => array(
		'url' => 'msc-nursing-colleges-in-{location}',
		'keywords' => 'M.Sc Nursing in {Location}, {Location} M.Sc Nursing Courses, List of M.Sc Nursing Colleges in {Location}, M.Sc Nursing in {Location}, Types of M.Sc Nursing Programs, M.Sc Nursing courses, Business Schools in {Location}, Institutes in {Location}, M.Sc Nursing courses, colleges, education'
),
1320 => array(
		'url' => 'mbbs-medical-colleges-in-{location}',
		'keywords' => 'MBBS in {Location}, {Location} MBBS Courses, List of MBBS Colleges in {Location}, MBBS in {Location}, Types of MBBS Programs, MBBS courses, Business Schools in {Location}, Institutes in {Location}, MBBS courses, colleges, education'
),
1321 => array(
		'url' => 'md-courses-in-{location}',
		'keywords' => 'MD in {Location}, {Location} MD Courses, List of MD Colleges in {Location}, MD in {Location}, Types of MD Programs, MD courses, Business Schools in {Location}, Institutes in {Location}, MD courses, colleges, education'
),
1368 => array(
		'url' => 'bsc-colleges-in-{Location}',
		'keywords' => 'BSc Colleges in {Location}, BSc Courses in {Location}, BSc Colleges, BSc Courses, universities, institutes, career, career options, Education'
),
1369 => array(
		'url' => 'diploma-in-engineering-in-{location}',
		'title' => 'Diploma in Engineering in {Location} - Diploma Engineering - Engineering Diploma - Shiksha.com',
		'description' => 'Search Diploma in Engineering in {Location} - Get a list of all Diploma Engineering courses, institutes. Know more about the full and part time programs',
		'keywords' => 'Diploma in Engineering in {Location}, Diploma Engineering, Engineering Diploma, Diploma in Engineering, Diploma courses, Engineering diploma colleges, universities, institutes, career, career options, Education'
),
1370 => array(
		'url' => 'bsc-distance-learning-in-{Location}',
		'keywords' => 'BSc Distance Learning in {Location}, BSc Distance Learning Courses in {Location}, BSc Distance Learning, BSc Distance Learning Courses, universities, institutes, career, career options, Education'
),
1371 => array(
		'url' => 'distance-learning-engineering-in-{Location}',
		'keywords' => 'Distance Learning Engineering in {Location}, Distance BTech Colleges in {Location}, Distance Engineering Colleges, BTech Distance Learning Engineering in {Location}, Btech Colleges in {Location}, Btech courses, Engineering colleges in {Location}, Engineering courses, universities, institutes, career, career options, Education'
),
1372 => array(
		'url' => 'distance-diploma-in-{Location}',
		'keywords' => 'Distance Diploma in Engineering in {Location}, Distance Diploma Engineering, Engineering Distance Diploma, Distance Diploma in Engineering, Distance Diploma courses, Engineering distance diploma colleges, universities, institutes, career, career options, Education'
),
1373 => array(
		'url' => 'msc-distance-learning-in-{Location}',
		'keywords' => 'MSc Distance Learning in {Location}, MSc Distance Learning Courses in {Location}, MSc Distance Learning, MSc Distance Learning Courses,  universities, institutes, career, career options, Education'
),
1374 => array(
		'url' => 'msc-colleges-in-{Location}',
		'keywords' => 'MSc Colleges in {Location}, MSc Courses in {Location}, MSc Colleges, MSc Courses, universities, institutes, career, career options, Education'
),
1375 => array(
		'url' => 'integrated-btech-mba-in-{location}',
		'title' => 'Integrated B.Tech MBA Courses and Colleges in {Location} - shiksha.com',
		'description' => 'Search for Integrated B.Tech MBA Courses in {Location} - Get a list of all Integrated BTech MBA Courses, Colleges and institutes. Know more about the full and part time programs.',
		'keywords' => 'Integrated BTech MBA Colleges in {Location}, Integrated B Tech MBA Courses in {Location}, Btech+MBA, List of Integrated BTech MBA Colleges in {Location}, Integrated BTech MBA in {Location}, Types of Integrated MBA Programs, Integrated MBA courses, Business Schools in {Location}, Institutes in {Location}, 5 Yr Integrated MBA courses, colleges, education'
),
1376 => array(
		'url' => 'be-marine-engineering-colleges-in-{location}',
		'keywords' => 'BE Marine Engineering Colleges in {Location}, Marine Engineering Colleges, Engineering Colleges in {Location}, Engineering Colleges, universities, institutes, career, career options, Education'
),
1377 => array(
		'url' => 'me-marine-engineering-colleges-in-{Location}',
		'keywords' => 'ME Marine Engineering Colleges in {Location}, Marine Engineering Colleges, Engineering Colleges in {Location}, Engineering Colleges, universities, institutes, career, career options, Education'
),
1378 => array(
		'url' => 'phd-courses-in-{location}',
		'keywords' => 'Phd Courses in {Location}, Phd colleges in {Location}, Phd Courses, Phd colleges, universities, institutes, career, career options, Education'
),

1379 => array(
		'url' => 'B.Sc - Telecom Management-in-{location}',
		'keywords' => ''
),
1380 => array(
		'url' => 'M.Sc - Telecom Management-in-{location}',
		'keywords' => ''
),
1381 => array(
		'url' => 'Cyber Security/IT Security - CCNA-in-{location}',
		'keywords' => ''
),
1382 => array(
		'url' => 'Cyber Security/IT Security - CCIE-in-{location}',
		'keywords' => ''
),
1383 => array(
		'url' => 'Cyber Security/IT Security - CCIE-in-{location}',
		'keywords' => ''
),
1384 => array(
		'url' => 'SAN Certified Data Warehousing-in-{location}',
		'keywords' => ''
),
1385 => array(
		'url' => 'SAN Certified Database Management-in-{location}',
		'keywords' => ''
),
1386 => array(
		'url' => 'SAN Certifed DB2-in-{location}',
		'keywords' => ''
),
1387 => array(
		'url' => 'SAN Certified DBA-in-{location}',
		'keywords' => ''
),
1388 => array(
		'url' => 'Game design - Maya-in-{location}',
		'keywords' => ''
),
1389 => array(
		'url' => 'Game design - Revit-in-{location}',
		'keywords' => ''
),
1390 => array(
		'url' => 'Comic Character design-in-{location}',
		'keywords' => ''
),
1391 => array(
		'url' => 'Mobile Game Art (Core)-in-{location}',
		'keywords' => ''
),
1392 => array(
		'url' => 'Mobile Game Art (Professional)-in-{location}',
		'keywords' => ''
),
1393 => array(
		'url' => 'Mobile Game Design-in-{location}',
		'keywords' => ''
),
1394 => array(
		'url' => 'Mobile Flash Game Programming-in-{location}',
		'keywords' => ''
),
1395 => array(
		'url' => 'Mobile Game Programming-in-{location}',
		'keywords' => ''
),
1396 => array(
		'url' => 'Mobile Games Development-in-{location}',
		'keywords' => ''
),
1397 => array(
		'url' => 'Comic Sketching-in-{location}',
		'keywords' => ''
),
1398 => array(
		'url' => 'Comic Illustrations-in-{location}',
		'keywords' => ''
),
1399 => array(
		'url' => 'Image Magic - Comic Design-in-{location}',
		'keywords' => ''
),
1400 => array(
		'url' => 'Aircraft Maintenance Engineering-in-{location}',
		'keywords' => ''
),
1401 => array(
		'url' => 'PG Diploma in Medical Transcription-in-{location}',
		'keywords' => ''
),
1402 => array(
		'url' => 'PG Diploma - Regulatory Affairs & Transcription-in-{location}',
		'keywords' => ''
),
1403 => array(
		'url' => 'Spoken English for Call Centre & BPO-in-{location}',
		'keywords' => ''
),
1404 => array(
		'url' => 'Public Speaking - Call Centre & BPO Training-in-{location}',
		'keywords' => ''
),
1405 => array(
		'url' => 'PG Diploma in Finance-in-{location}',
		'keywords' => ''
),
1406 => array(
		'url' => 'PG Diploma in Wealth Management & Finance-in-{location}',
		'keywords' => ''
),
1407 => array(
		'url' => 'Certificate in Finance-in-{location}',
		'keywords' => ''
),
1408 => array(
		'url' => 'Diploma in Finance-in-{location}',
		'keywords' => ''
),
1409 => array(
		'url' => 'Diploma in Event Management-in-{location}',
		'keywords' => ''
),
1410 => array(
		'url' => 'MBA - Aviation-in-{location}',
		'keywords' => ''
),
1411 => array(
		'url' => 'Bachelor in Alternate Medicine-in-{location}',
		'keywords' => ''
),
1412 => array(
		'url' => 'Master in Alternate Medicine-in-{location}',
		'keywords' => ''
),
1413 => array(
		'url' => 'Event Management - UG-in-{location}',
		'keywords' => ''
),
1414 => array(
		'url' => 'Event Management - PG-in-{location}',
		'keywords' => ''
),
1415 => array(
		'url' => 'Game Design and Development - UG-in-{location}',
		'keywords' => ''
),
1416 => array(
		'url' => 'Game Design and Development - PG-in-{location}',
		'keywords' => ''
),
1417 => array(
		'url' => 'Web Game Design-in-{location}',
		'keywords' => ''
),
1418 => array(
		'url' => 'Cloud Computing-in-{location}',
		'keywords' => ''
),

1421=> array(
		'url' => 'certificate-in-ophthalmic-assistance-in-{Location}',
		'keywords' => ''),
1422=> array(
		'url' => 'diploma-in-ophthalmic-assistance-in-{Location}',
		'keywords' => ''),
1423=> array(
		'url' => 'diploma-in-optometry-ophthalmic-technology-in-{Location}',
		'keywords' => ''),
1432=> array(
		'url' => 'msc-anthropology-in-{location}',
		'keywords' => ''),
1433=> array(
		'url' => 'msc-basic-sciences-in-{location}',
		'keywords' => ''),
1434=> array(
		'url' => 'msc-bio-analytical-sciences-in-{location}',
		'keywords' => ''),
1435=> array(
		'url' => 'msc-bio-chemistry-in-{location}',
		'keywords' => ''),
1436=> array(
		'url' => 'msc-bio-informatics-in-{location}',
		'keywords' => ''),
1437=> array(
		'url' => 'msc-botany-in-{location}',
		'keywords' => ''),
1438=> array(
		'url' => 'msc-computer-science-in-{location}',
		'keywords' => ''),
1439=> array(
		'url' => 'msc-environmental-sciences-in-{location}',
		'keywords' => ''),
1440=> array(
		'url' => 'msc-fabric-apparel-science-in-{location}',
		'keywords' => ''),
1441=> array(
		'url' => 'msc-food-nutrition-in-{location}',
		'keywords' => ''),
1442=> array(
		'url' => 'msc-genetics-in-{location}',
		'keywords' => ''),
1443=> array(
		'url' => 'msc-geoinformatics-in-{location}',
		'keywords' => ''),
1444=> array(
		'url' => 'msc-geology-in-{location}',
		'keywords' => ''),
1445=> array(
		'url' => 'msc-herbal-science-in-{location}',
		'keywords' => ''),
1446=> array(
		'url' => 'msc-home-science-in-{location}',
		'keywords' => ''),
1447=> array(
		'url' => 'msc-microbiology-in-{location}',
		'keywords' => ''),
1448=> array(
		'url' => 'msc-nano-science-nano-technology-in-{location}',
		'keywords' => ''),
1449=> array(
		'url' => 'msc-nutraceuticals-in-{location}',
		'keywords' => ''),
1450=> array(
		'url' => 'msc-operational-research-in-{location}',
		'keywords' => ''),
1451=> array(
		'url' => 'msc-zoology-in-{location}',
		'keywords' => ''),
1452=> array(
		'url' => 'certificate-in-public-health-in-{location}',
		'keywords' => ''),
1453=> array(
		'url' => 'bachelors-in-public-health-in-{location}',
		'keywords' => ''),
1454=> array(
		'url' => 'pg-diploma-in-public-health-in-{location}',
		'keywords' => ''),
1455=> array(
		'url' => 'masters-in-public-health-in-{location}',
		'keywords' => ''),
1456=> array(
		'url' => 'ba-llb-colleges-in-{location}',
		'keywords' => ''),
1457=> array(
		'url' => 'bba-llb-colleges-in-{location}',
		'keywords' => ''),
1459 => array(
		'url' => 'advanced-technical-courses-in-Agricultural-Engineering-in-{Location}',
		'keywords' => ''
),
1460 => array(
		'url' => 'advanced-technical-courses-in-Automobile-Engineering-in-{Location}',
		'keywords' => ''
),
1461 => array(
		'url' => 'advanced-technical-courses-in-Avionics-Engineering-in-{Location}',
		'keywords' => ''
),
1462 => array(
		'url' => 'advanced-technical-courses-in-Chemical-Engineering-in-{Location}',
		'keywords' => ''
),
1463 => array(
		'url' => 'advanced-technical-courses-in-Civil-Engineering-in-{Location}',
		'keywords' => ''
),
1464 => array(
		'url' => 'advanced-technical-courses-in-Communications-Engineering-in-{Location}',
		'keywords' => ''
),
1465 => array(
		'url' => 'advanced-technical-courses-in-Computer-Science-Engineering-in-{Location}',
		'keywords' => ''
),
1466 => array(
		'url' => 'advanced-technical-courses-in-Control-Systems-Engineering-in-{Location}',
		'keywords' => ''
),
1467 => array(
		'url' => 'advanced-technical-courses-in-Electrical-Engineering-in-{Location}',
		'keywords' => ''
),
1468 => array(
		'url' => 'advanced-technical-courses-in-Electronics-Engineering-in-{Location}',
		'keywords' => ''
),
1469 => array(
		'url' => 'advanced-technical-courses-in-Industrial-Engineering-in-{Location}',
		'keywords' => ''
),
1470 => array(
		'url' => 'advanced-technical-courses-in-Information-Technology-(IT)-in-{Location}',
		'keywords' => ''
),
1471 => array(
		'url' => 'advanced-technical-courses-in-Instrumentation-Engineering-in-{Location}',
		'keywords' => ''
),
1472 => array(
		'url' => 'advanced-technical-courses-in-Marine-Engineering-in-{Location}',
		'keywords' => ''
),
1473 => array(
		'url' => 'advanced-technical-courses-in-Mechanical-Engineering-in-{Location}',
		'keywords' => ''
),
1474 => array(
		'url' => 'advanced-technical-courses-in-Mechatronics-Engineering-in-{Location}',
		'keywords' => ''
),
1475 => array(
		'url' => 'advanced-technical-courses-in-Metallurgical-Engineering-in-{Location}',
		'keywords' => ''
),
1476 => array(
		'url' => 'advanced-technical-courses-in-Mining-Engineering-in-{Location}',
		'keywords' => ''
),
1477 => array(
		'url' => 'advanced-technical-courses-in-Nanotechnology-in-{Location}',
		'keywords' => ''
),
1478 => array(
		'url' => 'advanced-technical-courses-in-Power-Systems-Engineering-in-{Location}',
		'keywords' => ''
),
1479 => array(
		'url' => 'advanced-technical-courses-in-Signal-Processing-in-{Location}',
		'keywords' => ''
),
1480 => array(
		'url' => 'advanced-technical-courses-in-Solar-&-Alternative-Engineering-in-{Location}',
		'keywords' => ''
),
1481 => array(
		'url' => 'advanced-technical-courses-in-Telecommunications-Engineering-in-{Location}',
		'keywords' => ''
),
1482 => array(
		'url' => 'advanced-technical-courses-in-Textile-Engineering-in-{Location}',
		'keywords' => ''
),
1483 => array(
		'url' => 'advanced-technical-courses-in-VLSI-in-{Location}',
		'keywords' => ''
),
1484 => array(
		'url' => 'advanced-technical-courses-in-Wireless-Communication-in-{Location}',
		'keywords' => ''
),
1485 => array(
		'url' => 'advanced-technical-courses-in-Physics-in-{Location}',
		'keywords' => ''
),
1486 => array(
		'url' => 'advanced-technical-courses-in-Chemistry-in-{Location}',
		'keywords' => ''
),
1487 => array(
		'url' => 'advanced-technical-courses-in-Maths-in-{Location}',
		'keywords' => ''
),
1488 => array(
		'url' => 'advanced-technical-courses-in-Biology-in-{Location}',
		'keywords' => ''
),
1489 => array(
		'url' => 'advanced-technical-courses-in-Agriculture-&-Forestry-in-{Location}',
		'keywords' => ''
),
1490 => array(
		'url' => 'advanced-technical-courses-in-Bio-Technology-in-{Location}',
		'keywords' => ''
),
1491 => array(
		'url' => 'advanced-technical-courses-in-Aeronautical-Aerospace-Engineering-in-{Location}',
		'keywords' => ''
),
1458 => array(
		'url' => 'advanced-technical-courses-in-{Location}',
		'keywords' => ''
),
1492 => array(
		'url' => 'CTET-entrance-exams-coaching-classes-in-{Location}',
		'keywords' => 'CTET - Central Teacher Eligibility Test coaching institutes, CTET - Central Teacher Eligibility Test coaching classes, CTET - Central Teacher Eligibility Test entrance exam coaching, CTET - Central Teacher Eligibility Test coaching classes in {location}, CTET - Central Teacher Eligibility Test coaching institutes in {location}, list of CTET - Central Teacher Eligibility Test coaching institutes {location}'
),
1493 => array(
		'url' => 'PTE-exams-coaching-classes-in-{Location}',
		'keywords' => 'PTE - Pearson Test of English coaching institutes, PTE - Pearson Test of English coaching classes, PTE - Pearson Test of English entrance exam coaching, PTE - Pearson Test of English coaching classes in {location}, PTE - Pearson Test of English coaching institutes in {location}, list of PTE - Pearson Test of English coaching institutes {location}'
),
1500 => array(
		'url' => 'CMAT-entrance-exams-coaching-classes-in-{Location}',
		'keywords' => 'CMAT coaching institutes, CMAT coaching classes, CMAT entrance exam coaching, CMAT coaching classes in {location}, CMAT coaching institutes in {location}, list of CMAT coaching institutes {location}'
)
);
