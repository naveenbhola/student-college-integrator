<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists('get_testprep_seo_tags'))
{
        function get_testprep_seo_tags($acronym, $city_name)
        {
            if($city_name != '' && $city_name != NULL) $city_string = "in ".$city_name;
            if($acronym == 'MBA'){
                return array('Title' =>
"MBA Coaching Classes $city_string - MBA Coaching Institutes $city_string - Management Preparation $city_string - Coaching for MBA Entrance $city_string",

'Description' =>
"Find MBA Coaching Classes $city_string - MBA Coaching Institutes $city_string - Management Preparation $city_string - Coaching for MBA Entrance $city_string. Search MBA Coaching Centers in India. Details on MBA crash courses, coaching classes & training centers.",

'Keywords'=>
"MBA Coaching Classes $city_string, MBA Coaching Institutes $city_string, Management Preparation $city_string, MBA Entrance Coaching $city_string, MBA Coaching, Management Coaching, MBA Entrance Preparation, MBA Exam Preparation,
MBA Preparation, Entrance Exams, Test Preparation");
            }

                        if(in_array($acronym, array('CAT','MAT','SNAP','XAT'))){
                return array(
'Title'=>
"$acronym Coaching Classes $city_string - MBA $acronym Preparation $city_string - $acronym Coaching Institutes $city_string",

'Description' =>
"Find $acronym Coaching Classes $city_string - MBA $acronym Preparation $city_string - $acronym Coaching Institutes $city_string. Search Coaching for $acronym Entrance Exam $city_string & related details on $acronym Coaching Centers $city_string",

'Keywords'=>

"$acronym Coaching Classes $city_string, $acronym Preparation $city_string, $acronym Coaching Institutes $city_string, $acronym Coaching, $acronym Classes, Coaching for $acronym, preparation for $acronym, coaching institutes for $acronym, test preparation, entrance exam preparation, test prep classes"
                    );
                        }
                        if($acronym == 'Medical'){
                return array(
'Title'=>
"Medical Coaching Classes $city_string - Pre Medical Entrance Coaching Institutes $city_string - Medical Preparation $city_string - Coaching for MBBS / Dental $city_string",

'Description'=>
"Find Medical Coaching Classes $city_string - Pre Medical Entrance Coaching Institutes $city_string - Medical Preparation $city_string - Coaching for MBBS / Dental $city_string. Search Medical Coaching Centers in India, Details on Medical crash courses, coaching classes & training centers.",

"Keywords"=>
"Medical Coaching Classes $city_string, Medical Coaching Institutes <City>, Medical Preparation $city_string, Medical Entrance Coaching $city_string, Medical Coaching,
Institutes for Medical Coaching, Institutes for Medical Preparation, Coaching for Medical Entrance, Medical Exam Preparation"
                    );
                        }
                        if(in_array($acronym, array('AIPMT','AIIMS'))){
                return array(
'Title'=>
"PMT Coaching / $acronym Coaching Classes $city_string - All India Pre Medical $acronym Preparation $city_string - $acronym Coaching Institutes $city_string",

'Description'=>
"Find PMT Coaching / $acronym Coaching Classes $city_string - All India Pre Medical $acronym Preparation $city_string - $acronym Coaching Institutes $city_string. Search Coaching for $acronym Entrance Exam $city_string & related details on $acronym Coaching Centres $city_string",

'Keywords'=>

"$acronym Coaching Classes $city_string, $acronym Preparation $city_string,
$acronym Coaching Institutes $city_string, Coaching Classes for $acronym,
Coaching Institutes for $acronym, $acronym classes, coaching for $acronym, preparation for $acronym, test preparation, test prep classes, entrance coaching"
                    );
                        }
                        if($acronym == 'Engineering'){
                return array(
'Title'=>
"Engineering Coaching Classes $city_string - B.Tech / Engineering Entrance Coaching Institutes $city_string - Engineering Preparation $city_string - Coaching for Engineering $city_string",

'Description'=>
"Find Engineering Coaching Classes $city_string - B.Tech / Engineering Entrance Coaching Institutes $city_string - Engineering Preparation $city_string - Coaching for Engineering $city_string. Search Engineering Coaching Centers in India, Details on engineering crash courses, coaching classes & training centers.",

'Keywords'=>
"Engineering Coaching Classes $city_string, Engineering Coaching Institutes $city_string,
Engineering Preparation $city_string, Engineering Entrance Coaching $city_string,
Engineering Coaching, Institutes for Engineering Coaching, Institutes for Engineering Preparation, Coaching for Engineering Entrance, Engineering Exam Preparation"
                        );
                        }
                        if(in_array($acronym,array('AIEEE','IIT','BITSAT'))){
                return array(
'Title'=>
"$acronym Coaching Classes $city_string - $acronym Preparation $city_string - $acronym Coaching Institutes $city_string",

'Description'=>
"Find $acronym Coaching Classes $city_string - $acronym Preparation $city_string - $acronym Coaching Institutes $city_string. Search Coaching for $acronym Entrance Exam $city_string & related details on $acronym Coaching Centers $city_string",

'Keywords'=>

"$acronym Coaching Classes $city_string, $acronym Preparation $city_string,
$acronym Coaching Institutes $city_string, Coaching Classes for $acronym,
Coaching Institutes for $acronym, $acronym classes, coaching for $acronym, preparation for $acronym, test preparation, test prep classes, entrance coaching"
                    );
                        }
                        if($acronym == 'Govt'){
                return array(
'Title'=>
"Government Exams Coaching Classes $city_string - Government Entrance Coaching Institutes $city_string - Government Sector Preparation $city_string",

'Description'=>
"Find Government Exams Coaching Classes $city_string - Government Entrance Coaching Institutes $city_string - Government Sector Preparation $city_string - Coaching for Government Exams $city_string. Search Government Sector Coaching Centers in India, Details on crash courses, coaching classes & training centers.",

"Keywords"=>
"Government Exams Coaching Classes $city_string, Government Exams Coaching Institutes $city_string, Government Exams Preparation $city_string, Government Exams Entrance Coaching $city_string, Government Sector Coaching Classes, Government Sector Coaching Institutes, Government Sector Preparation, Government Sector Entrance Coaching,
Government Sector Exam Preparation"
                    );
                        }
                        if($acronym == 'UPSC'){
                return array(
'Title'=>
"$acronym Coaching Classes $city_string - $acronym Preparation $city_string - $acronym Coaching Institutes $city_string",

'Description'=>
"Find $acronym Coaching Classes $city_string - $acronym Preparation $city_string - $acronym Coaching Institutes $city_string. Search Coaching for $acronym Entrance Exam $city_string & related details on $acronym Coaching Centers $city_string",

'Keywords'=>

"$acronym Coaching Classes $city_string, $acronym Preparation $city_string,
$acronym Coaching Institutes $city_string, Coaching Classes for $acronym,
Coaching Institutes for $acronym, $acronym classes, coaching for $acronym, preparation for $acronym, test preparation, test prep classes, entrance coaching"
                    );
                        }
                        if($acronym == 'Foreign'){
                return array(
'Title'=>
"Foreign Study Coaching Classes $city_string - Foreign University Coaching Institutes $city_string - Foreign Education Preparation $city_string",

'Description'=>
"Find Foreign Study Coaching Classes $city_string - Foreign University Coaching Institutes $city_string - Foreign Education Preparation $city_string. Search Foreign Degree Coaching Centers in India, Details on crash courses, coaching classes & training centers.",

'Keywords'=>
"Foreign Study Coaching Classes $city_string, Foreign University Coaching Institutes $city_string, Foreign education Preparation $city_string, Foreign Entrance Coaching $city_string,
Foreign Coaching Classes, Coaching for Foreign Exams, Foreign Exams Preparation"
                    );
                        }
                        if(in_array($acronym, array('GMAT','GRE','TOEFL','SAT'))){
                return array(
'Title'=>
"$acronym Preparation $city_string - $acronym Coaching Institutes $city_string -   $acronym Coaching Classes $city_string - Coaching for $acronym $city_string",

'Description'=>
"Find $acronym Preparation $city_string - $acronym Coaching Institutes $city_string - $acronym Coaching Classes $city_string - Coaching for $acronym $city_string. Search $acronym Classes $city_string & related details on preparation for $acronym $city_string",

'Keywords'=>

"$acronym Coaching Classes $city_string, $acronym Preparation $city_string,
$acronym Coaching Institutes $city_string, Coaching Classes for $acronym,
Coaching Institutes for $acronym, $acronym classes, coaching for $acronym, preparation for $acronym, test preparation, test prep classes, entrance coaching"
                    );
                        }
                return array(
'Title'=>
"$acronym Preparation $city_string - $acronym Coaching Institutes $city_string -   $acronym Coaching Classes $city_string - Coaching for $acronym $city_string",

'Description'=>
"Find $acronym Preparation $city_string - $acronym Coaching Institutes $city_string - $acronym Coaching Classes $city_string - Coaching for $acronym $city_string. Search $acronym Classes $city_string & related details on preparation for $acronym $city_string",

'Keywords'=>

"$acronym Coaching Classes $city_string, $acronym Preparation $city_string,
$acronym Coaching Institutes $city_string, Coaching Classes for $acronym,
Coaching Institutes for $acronym, $acronym classes, coaching for $acronym, preparation for $acronym, test preparation, test prep classes, entrance coaching"

                    );
        }
}



//Start GET LISTING SEO TAGS
if (! function_exists('get_listing_seo_tags'))
{
        function get_listing_seo_tags($instituteName,$locality='',$courseName='',$city,$country='india',$identifier,$acr='')
	{
		if(!empty($locality)) { $locality = $locality.',';}
		if($identifier == 'institute')
		{
			if($acr!='')
			{
				return array(
				'Title'=>"$instituteName | Shiksha.com",
				'Description'=>"See available courses at $instituteName, find out about fees, admissions, courses, placement, faculty and much more only at Shiksha.com",
				'Keywords'=>"$acr, $instituteName, $acr $country, colleges, courses, compare colleges, scholarships, college rankings, admissions, $acr Courses, career, education, higher education"
				);
			}
			else
			{
				return array(
				'Title'=>"$instituteName | Shiksha.com",
				'Description'=>"See available courses at $instituteName, find out about fees, admissions, courses, placement, faculty and much more only at Shiksha.com",
				'Keywords'=>"$instituteName, $instituteName, $country, colleges, courses, compare colleges, scholarships, college rankings, admissions, $instituteName Courses, career, education, higher education"
				);
			}
		}
        
		if($identifier == 'course')
		{
			if($acr!='')
			{
			/*
				Title:
				<Course Name> course in <Institute Location>, <Institute Acronym> Courses - <Institute Complete Name> - Course information - Eligibility Criteria - Syllabus

				Description:
				Find <Course Name> course in <Institute Location>, <Institute Acronym> courses, <Institute Complete Name>. Compare <Course Name> details, courses offered, Eligibility Criteria, Syllabus, qualification & Course Duration at Shiksha.com

				Keywords:
				<Course Name>, <Course Name> eligibility, <Course Name> details, career, course information, course details, <Course Name> syllabus, admissions, colleges, career courses, education forums, education,
			*/
				return array(
				'Title'=>"$courseName at $instituteName | Shiksha.com",
				'Description'=>"Read more about $courseName at $instituteName. Find out about fees, admissions, reviews and more only at Shiksha.com",
				'Keywords'=>"$courseName, $courseName eligibility, $courseName details, career, course information, course details, $courseName syllabus, admissions, colleges, career courses, education forums, education"
				);
			}
			else
			{
			/*
			* Title:
				<Course Name> course in <Institute Location>, <Institute Complete Name> - Course information - Eligibility Criteria - Syllabus

				Description:
				Find <Course Name> course in <Institute Location>, <Institute Complete Name>. Compare <Course Name> details, courses offered, Eligibility Criteria, Syllabus, qualification & Course Duration at Shiksha.com

				Keywords:
				<Course Name>, <Course Name> eligibility, <Course Name> details, career, course information, course details, <Course Name> syllabus, admissions, colleges, career courses, education forums, education,
			*/
				return array(
				'Title'=>"$courseName at $instituteName | Shiksha.com",
				'Description'=>"Read more about $courseName at $instituteName. Find out about fees, admissions, reviews and more only at Shiksha.com",
				'Keywords'=>"$courseName, $courseName eligibility, $courseName details, career, course information, course details, $courseName syllabus, admissions, colleges, career courses, education forums, education "
				);
			}
		}
		if($identifier == 'AnaTab')
		{
			/*
			* Title:
			QNA - Expert Advice - Questions - Answers on <Institute Acronym> - <Institute Complete Name>, <Institute Location>, <Country>

			Description:
			Find Expert Advice, Questions, Answers, QNA on <Institute Acronym> - <Institute Complete Name>, <Institute Location>, <Country>. Discuss & Share your College / Course queries with Education Experts at Shiksha.com

			Keywords:
			College qna, qna, college questions, college experts, college forums, education experts, education advice, discuss queries, discuss questions, Shiksha, education, forums, education forum, expert advice
			*/
			if($acr!='')
			{
				return array(
				'Title'=>"QNA - Expert Advice - Questions - Answers on $acr - $instituteName, $locality $city, $country ",
				'Description'=>"Find Expert Advice, Questions, Answers, QNA on $acr - $instituteName, $locality $city, $country. Discuss & Share your College / Course queries with Education Experts at Shiksha.com",
				'Keywords'=>"College qna, qna, college questions, college experts, college forums, education experts, education advice, discuss queries, discuss questions, Shiksha, education, forums, education forum, expert advice"
				);
			}
			else
			{
				/*
					Title:
					QNA - Expert Advice - Questions - Answers on <Institute Complete Name>, <Institute Location>, <Country>

					Description:
					Find Expert Advice, Questions, Answers, QNA on <Institute Complete Name>, <Institute Location>, <Country>. Discuss & Share your College / Course queries with Education Experts at Shiksha.com

					Keywords:
					College qna, qna, college questions, college experts, college forums, education experts, education advice, discuss queries, discuss questions, Shiksha, education, forums, education forum, expert advice
				*/
				return array(
				'Title'=>"QNA - Expert Advice - Questions - Answers on $instituteName, $locality $city, $country ",
				'Description'=>"Find Expert Advice, Questions, Answers, QNA on $instituteName, $locality $city, $country.Discuss & Share your College / Course queries with Education Experts at Shiksha.com",
				'Keywords'=>"College qna, qna, college questions, college experts, college forums, education experts, education advice, discuss queries, discuss questions, Shiksha, education, forums, education forum, expert advice"
				);
			}
		}
		if($identifier == 'PhotoTab')
		{
			if($acr!='')
			{
				/*
				Title:
				<Institute Acronym> Photos Pictures - <Institute Acronym> Videos Gallery - <Institute Complete Name> Photos Pictures - <Institute Complete Name> Videos Gallery

				Description:
				Find <Institute Acronym> Photos Pictures, <Institute Acronym> Videos Gallery, <Institute Complete Name> Photos Pictures Images & <Institute Complete Name> Videos Gallery. See college campus pictures, college library & other images at Shiksha.com. Get expert advice, share & discuss details about this college at Shiksha QNA.

				Keywords:
				<Institute Acronym> Photos, <Institute Acronym> Pictures, <Institute Acronym> images, <Institute Acronym> videos, <Institute Acronym> gallery, <Institute Complete Name>,  photos, college photos, college pictures
				*/
				return array(
					'Title'=>"$acr Photos Pictures - $acr Videos Gallery - $instituteName Photos Pictures - $instituteName Videos Gallery ",
					'Description'=>"Find $acr Photos Pictures, $acr Videos Gallery, $instituteName Photos Pictures Images & $instituteName Videos Gallery. See college campus pictures, college library & other images at Shiksha.com. Get expert advice, share & discuss details about this college at Shiksha QNA.",
					'Keywords'=>"$acr Photos, $acr Pictures, $acr images, $acr videos, $acr gallery, $instituteName,  photos, college photos, college pictures"
				);
			}
			else
			{
				/*
				*Title:
				<Institute Complete Name> Photos Pictures - <Institute Complete Name> Videos Gallery

				Description:
				Find <Institute Complete Name> Photos Pictures Images & <Institute Complete Name> Videos Gallery. See college campus pictures, college library & other images at Shiksha.com. Get expert advice, share & discuss details about this college at Shiksha QNA.

				Keywords:
				<Institute Complete Name> Photos, <Institute Complete Name> Pictures, <Institute Complete Name> images, <Institute Complete Name> videos, <Institute Complete Name> gallery, images, videos, photos, college photos, college pictures
				*/
				return array(
				'Title'=>"$instituteName Photos Pictures - $instituteName Videos Gallery",
				'Description'=>"Find $instituteName Photos Pictures Images & $instituteName Videos Gallery. See college campus pictures, college library & other images at Shiksha.com. Get expert advice, share & discuss details about this college at Shiksha QNA.",
				'Keywords'=>"$instituteName Photos, $instituteName Pictures, $instituteName images, $instituteName videos, $instituteName gallery, images, videos, photos, college photos, college pictures "
				);
			}
		}
		if($identifier == 'AlumniTab')
		{
			if($acr!='')
			{
				/*
				* Title:
				<Institute Acronym> Reviews - <Institute Acronym> Alumni - <Institute Complete Name> Reviews - <Institute Complete Name> Alumni Speak on College / Feedback / Placements / Infrastructure / Faculties

				Description:
				Find <Institute Acronym> Reviews, <Institute Acronym> Alumni, <Institute Complete Name> Reviews & <Institute Complete Name> Alumni Speak. Get details on Alumni Feedback on college Placements, Faculties and College Infrastructure at Shiksha.com.  Get expert advice, share & discuss details about this college at Shiksha QNA.


				Keywords:
				<Institute Acronym> Reviews, <Institute Acronym> Alumni, alumni speak, Alumni reviews, alumni feedback, placements, reviews, <Institute Acronym> faculties, <Institute Complete Name>, colleges, institutes, courses, college infrastructure, forums, Education
				*/
				return array(
				'Title'=>"$acr Reviews - $acr Alumni - $instituteName Reviews - $instituteName Alumni Speak on College / Feedback / Placements / Infrastructure / Faculties",
				'Description'=>"Find $acr Reviews, $acr Alumni, $instituteName Reviews & $instituteName Alumni Speak. Get details on Alumni Feedback on college Placements, Faculties and College Infrastructure at Shiksha.com.  Get expert advice, share & discuss details about this college at Shiksha QNA.",
				'Keywords'=>"$acr Reviews, $acr Alumni, alumni speak, Alumni reviews, alumni feedback, placements, reviews, $acr faculties, $instituteName, colleges, institutes, courses, college infrastructure, forums, Education"
				);
			}
			else
			{
				/*
				Title:
				<Institute Complete Name> Reviews - <Institute Complete Name> Alumni Speak on College / Feedback / Placements / Infrastructure / Faculties

				Description:
				Find <Institute Complete Name> Reviews & <Institute Complete Name> Alumni Speak about the college. Get details on Alumni Feedback on college Placements, Faculties and College Infrastructure at Shiksha.com.  Get expert advice, share & discuss details about this college at Shiksha QNA.


				Keywords:
				<Institute Complete Name> Reviews, <Institute Complete Name> Alumni, alumni speak, Alumni reviews, alumni feedback, placements, reviews, faculties, colleges, institutes, courses, college infrastructure, forums, Education
				*/
				return array(
				'Title'=>"$instituteName Reviews - $instituteName Alumni Speak on College / Feedback / Placements / Infrastructure / Faculties",
				'Description'=>"Find $instituteName Reviews & $instituteName Alumni Speak about the college. Get details on Alumni Feedback on college Placements, Faculties and College Infrastructure at Shiksha.com.  Get expert advice, share & discuss details about this college at Shiksha QNA.",
				'Keywords'=>"$instituteName Reviews, $instituteName Alumni, alumni speak, Alumni reviews, alumni feedback, placements, reviews, faculties, colleges, institutes, courses, college infrastructure, forums, Education"
				);
			}

		}
		if($identifier == 'CourseTab')
		{
			if($acr!='')
			{
				/*
				Title:
				<Institute Acronym> Courses Offered - <Institute Acronym> Admissions - Courses at <Institute Acronym> - <Institute Full Name> Courses Offered

				Description:
				Find <Institute Acronym> Courses Offered, <Institute Acronym> Admissions, Courses at <Institute Acronym> & <Institute Full Name> Courses Offered. Get expert advice, share & discuss details about this course at Shiksha QNA.

				Keywords:
				<Institute Acronym> Courses, <Institute Acronym> Admissions, course at <Institute Acronym>, Courses, <Institute Complete Name>, course, course details, expert advice, colleges, courses, institutes, courses offered, education, forums, qna
				*/
				return array(
					'Title'=>"$acr Courses Offered - $acr Admissions - Courses at $acr - $instituteName Courses Offered",
					'Description'=>"Find $acr Courses Offered, $acr Admissions, Courses at $acr & $instituteName Courses Offered. Get expert advice, share & discuss details about this course at Shiksha QNA.",
					'Keywords'=>"$acr Courses, $acr Admissions, course at $acr, Courses, $instituteName, course, course details, expert advice, colleges, courses, institutes, courses offered, education, forums, qna"
				);
			}
			else
			{
				/*
				Title:
				<Institute Complete Name> Courses Offered - <Institute Complete Name> Admissions - Courses at <Institute Complete Name>

				Description:
				Find <Institute Complete Name> Courses Offered, <Institute Complete Name> Admissions and complete details of Courses at <Institute Complete Name>. Get expert advice, share & discuss details about this course at Shiksha QNA.

				Keywords:
				<Institute Complete Name> Courses, <Institute Complete Name> Admissions, course at <Institute Complete Name>, Courses, course, course details, expert advice, colleges, courses, institutes, courses offered, education, forums, qna
				*/
				return array(
					'Title'=>"$instituteName Courses Offered - $instituteName Admissions - Courses at $instituteName",
					'Description'=>"Find $instituteName Courses Offered, $instituteName Admissions and complete details of Courses at $instituteName. Get expert advice, share & discuss details about this course at Shiksha QNA.",
					'Keywords'=>"$instituteName Courses, $instituteName Admissions, course at $instituteName, Courses, course, course details, expert advice, colleges, courses, institutes, courses offered, education, forums, qna"
				);
			}
		}
		if($identifier == 'campusRepTab')
		{
			/*
			* Title:
			Campus Representative - <course_Name> from <Institute_ Name>, <location>

			Description:
			Campus representatives to answer all of the questions to <Course_Name> from <Institute_Name>, <location>. Clear your doubts about admission, eligibility, entrance exams, cutoffs, fees, placements etc.

			Keywords:
			Campus representatives to answer all of the questions to <Course_Name> from <Institute_Name>, <location>. Clear your doubts about admission, eligibility, entrance exams, cutoffs, fees, placements etc.
			*/
			if($acr!='')
			{
				return array(
				'Title'=>"Current Student -  $courseName from $acr, $instituteName, $locality $city, $country",
				'Description'=>"Current students to answer all of the questions to $courseName from $acr, $instituteName, $locality $city, $country. Clear your doubts about admission, eligibility, entrance exams, cutoffs, fees, placements etc.",
				'Keywords'=>"College qna, qna, college questions, college experts, college forums, education experts, education advice, discuss queries, discuss questions, Shiksha, education, forums, education forum, expert advice"
				);
			}
			else
			{
				return array(
				'Title'=>"Current Student -  $courseName from $instituteName, $locality $city, $country",
				'Description'=>"Current students to answer all of the questions to $courseName from $instituteName, $locality $city, $country. Clear your doubts about admission, eligibility, entrance exams, cutoffs, fees, placements etc.",
				'Keywords'=>"College qna, qna, college questions, college experts, college forums, education experts, education advice, discuss queries, discuss questions, Shiksha, education, forums, education forum, expert advice"
				);
			}
		}
        }
}
//End GET LISTING SEO TAGS


?>
