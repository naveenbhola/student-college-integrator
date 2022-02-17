<?php
$config['test'] = 1;
global $courseBasicSubSections;
// $courseBasicSubSections = array('main_location');
$courseBasicSubSections = array('fees', 'main_location','recognition','education_type','delivery_method','time_of_learning', 'difficulty_level');
global $courseSections;
$courseSections = array('basic', 'eligibility', 'course_type_information', 'location', 'academic', 'facility', 'research_project', 'usp', 'event', 'medium_of_instruction', 'partner', 'company', 'placements_internships', 'media');
global $courseSectionNotCached;
$courseSectionNotCached = array();

global $courseLocationSubSections;
$courseLocationSubSections = array('fees','contact_details');

global $courseTypeSubSections;
$courseTypeSubSections = array('credential','course_level');

global $courseSectionNotCached;
$courseSectionNotCached = array(
	/*'academic',
	'usp',
	'medium_of_instruction',
	'partner'*/
	);

$listingMainStatus = array("draft"=>"draft",
						   "live"=>"live");
$config['listingMainStatus'] = $listingMainStatus;