<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

global $scholarshipCategoryPageCountries;
$scholarshipCategoryPageCountries = array('3','8','4','12','21','14','9','5','7','6','11');
define('SCHOLARSHIP_CATEGORY_PAGE_PAGE_SIZE','20');
define('SUGGEST_MISSING_SCHOLARSHIP_LINK','http://bit.ly/suggest-scholarship');

global $scholarshipMasterLevels;
$scholarshipMasterLevels = array('Masters','Masters Diploma','PhD','Masters Certificate','all');
global $scholarshipBachelorLevels;
$scholarshipBachelorLevels = array('Bachelors','Bachelors Diploma','Bachelors Certificate','all');

// mapping between query string for filters & their corresponding fields in scholarship solr documents
global $queryStringAliasToSolrFieldMapping;
$queryStringAliasToSolrFieldMapping =
    array(
        'lv' => 'saScholarshipCourseLevel', //these shoudn't be accepted from a level X all countries page
        'ct' => 'saScholarshipCountryId', //these shoudn't be accepted from a country X all levels page
        'sa' => 'saScholarshipAmount',
        'ss' => 'saScholarshipCategoryId',
        'st' => 'saScholarshipType',
        'sr' => 'saScholarshipSpecialRestriction',
        'sc' => 'saScholarshipCategory',
        'su' => 'saScholarshipUnivId',
        'sd' => 'saScholarshipApplicationEndDate',
        'sn' => 'saScholarshipStudentNationality',
        'ad' => 'saScholarshipAwardsCount',
        'si' => 'saScholarshipIntakeYear'
    );
// mapping between input field names and their corresponding query string aliases
global $queryStringFieldNameToAliasMapping;
$queryStringFieldNameToAliasMapping =
    array(
        'scholarshipLevel'      => 'lv',
        'destinationCountry'    => 'ct',
        'schAmountMin'          => 'sa',
        'schAmountMax'          => 'sa',
        'scholarshipStream'     => 'ss',
        'scholarshipType'       => 'st',
        'specialRestrictions'   => 'sr',
        'scholarshipCategory'   => 'sc',
        'scholarshipUniversity' => 'su',
        'schDeadlineMin'        => 'sd',
        'schDeadlineMax'        => 'sd',
        'studentNationality'    => 'sn',
        'schAwardMin'           => 'ad',
        'schAwardMax'           => 'ad',
        'intakeYear'            => 'si',
        'scholarshipSorting'    => 'so'
    );
global $scholarshipSortingCriteriaToSolrFieldMapping;
$scholarshipSortingCriteriaToSolrFieldMapping =
    array(
        'popularity' => 'saScholarshipPopularityIndex desc',
        'deadline'   => 'score asc',
        'amount'     => 'saScholarshipAmount desc',
        'awards'     => 'saScholarshipAwardsCount desc'
    );

$config['seoUrlConfig'] = array();

//Bachelors X Stream X Country (1*5*11 URLs)
$config['seoUrlConfig']['bachelors_3_239'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_3_242'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_3_243'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_3_244'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_3_245'] = 'LevelCountryStreamTemplate';

$config['seoUrlConfig']['bachelors_4_239'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_4_242'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_4_243'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_4_244'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_4_245'] = 'LevelCountryStreamTemplate';

$config['seoUrlConfig']['bachelors_5_239'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_5_242'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_5_243'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_5_244'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_5_245'] = 'LevelCountryStreamTemplate';

$config['seoUrlConfig']['bachelors_6_239'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_6_242'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_6_243'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_6_244'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_6_245'] = 'LevelCountryStreamTemplate';

$config['seoUrlConfig']['bachelors_7_239'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_7_242'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_7_243'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_7_244'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_7_245'] = 'LevelCountryStreamTemplate';

$config['seoUrlConfig']['bachelors_8_239'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_8_242'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_8_243'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_8_244'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_8_245'] = 'LevelCountryStreamTemplate';

$config['seoUrlConfig']['bachelors_9_239'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_9_242'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_9_243'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_9_244'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_9_245'] = 'LevelCountryStreamTemplate';

$config['seoUrlConfig']['bachelors_11_239'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_11_242'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_11_243'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_11_244'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_11_245'] = 'LevelCountryStreamTemplate';

$config['seoUrlConfig']['bachelors_12_239'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_12_242'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_12_243'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_12_244'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_12_245'] = 'LevelCountryStreamTemplate';

$config['seoUrlConfig']['bachelors_14_239'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_14_242'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_14_243'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_14_244'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_14_245'] = 'LevelCountryStreamTemplate';

$config['seoUrlConfig']['bachelors_21_239'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_21_242'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_21_243'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_21_244'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['bachelors_21_245'] = 'LevelCountryStreamTemplate';

//Masters X Stream X Country (1*3*11 URLs)
$config['seoUrlConfig']['masters_3_243'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['masters_3_244'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['masters_3_245'] = 'LevelCountryStreamTemplate';

$config['seoUrlConfig']['masters_4_243'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['masters_4_244'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['masters_4_245'] = 'LevelCountryStreamTemplate';

$config['seoUrlConfig']['masters_5_243'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['masters_5_244'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['masters_5_245'] = 'LevelCountryStreamTemplate';

$config['seoUrlConfig']['masters_6_243'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['masters_6_244'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['masters_6_245'] = 'LevelCountryStreamTemplate';

$config['seoUrlConfig']['masters_7_243'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['masters_7_244'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['masters_7_245'] = 'LevelCountryStreamTemplate';

$config['seoUrlConfig']['masters_8_243'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['masters_8_244'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['masters_8_245'] = 'LevelCountryStreamTemplate';

$config['seoUrlConfig']['masters_9_243'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['masters_9_244'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['masters_9_245'] = 'LevelCountryStreamTemplate';

$config['seoUrlConfig']['masters_11_243'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['masters_11_244'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['masters_11_245'] = 'LevelCountryStreamTemplate';

$config['seoUrlConfig']['masters_12_243'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['masters_12_244'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['masters_12_245'] = 'LevelCountryStreamTemplate';

$config['seoUrlConfig']['masters_14_243'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['masters_14_244'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['masters_14_245'] = 'LevelCountryStreamTemplate';

$config['seoUrlConfig']['masters_21_243'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['masters_21_244'] = 'LevelCountryStreamTemplate';
$config['seoUrlConfig']['masters_21_245'] = 'LevelCountryStreamTemplate';

//Bachelors X Country X B.Tech (1*11 URLs)
$config['seoUrlConfig'] ['bachelors_3_240,241'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig'] ['bachelors_4_240,241'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig'] ['bachelors_5_240,241'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig'] ['bachelors_6_240,241'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig'] ['bachelors_7_240,241'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig'] ['bachelors_8_240,241'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig'] ['bachelors_9_240,241'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig']['bachelors_11_240,241'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig']['bachelors_12_240,241'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig']['bachelors_14_240,241'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig']['bachelors_21_240,241'] = 'LevelCountryDesiredCourseTemplate';

//Masters X Country X MBA (1*11 URLs)
$config['seoUrlConfig'] ['masters_3_239'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig'] ['masters_4_239'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig'] ['masters_5_239'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig'] ['masters_6_239'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig'] ['masters_7_239'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig'] ['masters_8_239'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig'] ['masters_9_239'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig']['masters_11_239'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig']['masters_12_239'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig']['masters_14_239'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig']['masters_21_239'] = 'LevelCountryDesiredCourseTemplate';

//Masters X MS X Country (1*11 URLs)
$config['seoUrlConfig'] ['masters_3_240,241,242'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig'] ['masters_4_240,241,242'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig'] ['masters_5_240,241,242'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig'] ['masters_6_240,241,242'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig'] ['masters_7_240,241,242'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig'] ['masters_8_240,241,242'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig'] ['masters_9_240,241,242'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig']['masters_11_240,241,242'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig']['masters_12_240,241,242'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig']['masters_14_240,241,242'] = 'LevelCountryDesiredCourseTemplate';
$config['seoUrlConfig']['masters_21_240,241,242'] = 'LevelCountryDesiredCourseTemplate';

//Bachelors X Country (1*11 URLs)
$config['seoUrlConfig'] ['bachelors_3'] = 'LevelCountryTemplate';
$config['seoUrlConfig'] ['bachelors_4'] = 'LevelCountryTemplate';
$config['seoUrlConfig'] ['bachelors_5'] = 'LevelCountryTemplate';
$config['seoUrlConfig'] ['bachelors_6'] = 'LevelCountryTemplate';
$config['seoUrlConfig'] ['bachelors_7'] = 'LevelCountryTemplate';
$config['seoUrlConfig'] ['bachelors_8'] = 'LevelCountryTemplate';
$config['seoUrlConfig'] ['bachelors_9'] = 'LevelCountryTemplate';
$config['seoUrlConfig']['bachelors_11'] = 'LevelCountryTemplate';
$config['seoUrlConfig']['bachelors_12'] = 'LevelCountryTemplate';
$config['seoUrlConfig']['bachelors_14'] = 'LevelCountryTemplate';
$config['seoUrlConfig']['bachelors_21'] = 'LevelCountryTemplate';

//Masters X Country (1*11 URLs)
$config['seoUrlConfig'] ['masters_3'] = 'LevelCountryTemplate';
$config['seoUrlConfig'] ['masters_4'] = 'LevelCountryTemplate';
$config['seoUrlConfig'] ['masters_5'] = 'LevelCountryTemplate';
$config['seoUrlConfig'] ['masters_6'] = 'LevelCountryTemplate';
$config['seoUrlConfig'] ['masters_7'] = 'LevelCountryTemplate';
$config['seoUrlConfig'] ['masters_8'] = 'LevelCountryTemplate';
$config['seoUrlConfig'] ['masters_9'] = 'LevelCountryTemplate';
$config['seoUrlConfig']['masters_11'] = 'LevelCountryTemplate';
$config['seoUrlConfig']['masters_12'] = 'LevelCountryTemplate';
$config['seoUrlConfig']['masters_14'] = 'LevelCountryTemplate';
$config['seoUrlConfig']['masters_21'] = 'LevelCountryTemplate';

$config['metaDataTemplateConfig'] = array();

$config['metaDataTemplateConfig']['LevelCountryStreamTemplate'] = array(
        'title'       => 'Scholarships to Study {level} of {stream} Courses in {country} for Indian Students',
        'description' => '{NumberOfScholarships} available to study {level} of {stream} Courses in {country}. Find out details about amount, countries, intake year and final deadlines to apply.',
        'h1TagString' => 'Scholarships to Study {level} of {stream} Courses in {country}',
        'breadcrumb'  => '{stream} in {country}'
    );
$config['metaDataTemplateConfig']['LevelCountryDesiredCourseTemplate'] = array(
        'title'       => 'Scholarships to Study {desiredCourse} Courses in {country} for Indian Students',
        'description' => '{NumberOfScholarships} available to study {desiredCourse} Courses in {country}. Find out details about amount, countries, intake year and final deadlines to apply.',
        'h1TagString' => 'Scholarships to Study {desiredCourse} Courses in {country}',
        'breadcrumb'  => '{desiredCourse} in {country}'
    );
$config['metaDataTemplateConfig']['LevelCountryTemplate'] = array(
        'title'       => 'Scholarships to Study {level} Courses in {country} for Indian Students',
        'description' => '{NumberOfScholarships} available to study {level} Courses in {country}. Find out details about amount, countries, intake year and final deadlines to apply.',
        'h1TagString' => 'Scholarships to Study {level} Courses in {country}',
        'breadcrumb'  => '{country}'
    );
$config['metaDataTemplateConfig']['defaultTemplateForLevel'] = array(
        'title'       => 'Scholarships for {level} Abroad',
        'description' => '{NumberOfScholarships} available for {level} abroad. Find out details about amount, countries, intake year and final deadlines to apply.',
        'h1TagString' => 'Scholarships for {level} Courses',
        'breadcrumb'  => ''
    );
$config['metaDataTemplateConfig']['defaultTemplateForCountry'] = array(
        'title'       => 'Scholarships for {country} - {ScholarshipsCount} UG & PG Scholarships to Study in {country} @ Shiksha.com',
        'description' => 'Detailed info on {NumberOfScholarships} to study in {country} for Indian students. Get eligibility, amount, courses & Know how to apply for these Scholarships to Study in {country} only at Studyabroad.shiksha.com',
        'h1TagString' => 'Scholarships to Study in {country}',
        'breadcrumb'  => ''
    );
?>