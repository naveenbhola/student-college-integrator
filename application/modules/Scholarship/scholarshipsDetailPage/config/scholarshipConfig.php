<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// used for scholarship details coutnry widget
$config['scholarshipCountryIds'] = array(3, 5, 8, 4, 7, 9);

$config['seoDetails'] = array(
	'scholarshipDetailPage' => array(
		'seoTitle'         => "<scholarship_name> - Eligibility, Application Process, Dates.",
		'seoDescription' 	=> "Find out details about <scholarship_name> like amount, eligibility, application process, important dates, and applicable study areas.",
		),
	);

global $scholarshipSections;
$scholarshipSections = array('basic'=>array('full'),'eligibility'=>array('full'),'specialRestrictions'=>array('full'),'application'=>array('full'),'amount'=>array('full'),'hierarchy'=>array('full'),'deadline'=>array('full'));

global $scholarshipSectionToFieldsMapping;
$scholarshipSectionToFieldsMapping = array(
                            'basic'                 =>array('scholarshipId','name','link','category','extApplicationRequired','description','type','scholarshipType2','seoTitle','seoKeywords','seoDescription','seoUrl','organisationName','organisationLogo','modifiedAt','subscriptionType'),
                            'eligibility'           =>array('workXPRequired','workXP','interviewRequired','eligibilityDescription','eligibilityLink','selectionPreference','scholarshipExamsData','scholarshipEducationData'),
                            'application'           =>array('applicableCountries','docsDescription','applyNowLink','faqLink','contactEmail','contactPhone','scholarshipBrochureUrl','SOP','LOR','Essays','CV','financialDocuments','extraCurricularActivities','workExperience','officialTranscripts','applicableNationalities','intakeYear'),
                            'amount'                =>array('totalAmountPayout','amountCurrency','amountInterval','amountDescription','amountDescriptionLink','expensesCovered','convertedTotalAmountPayout'),
                            'specialRestrictions'   =>array('specialRestrictionDescription','specialRestrictionLink','specialRestriction'),
                            'hierarchy'             =>array('courseLevel','parentCategory','subcategory','specialization','courseCategoryComments','university','department','course','allCategorizations'),
                            'deadline'              =>array('applicationStartDate','applicationStartDateDescription','applicationEndDate','applicationEndDateDescription','additionalInfo','numAwards','numAwardsDescription','deadLineType','importantDates'),
                            );
global $scholarshipFieldToSectionMapping;
$scholarshipFieldToSectionMapping = array(
    'scholarshipId'             => 'basic',
    'name'                      => 'basic',
    'link'                      => 'basic',
    'category'                  => 'basic',
    'extApplicationRequired'    => 'basic',
    'description'               => 'basic',
    'type'                      => 'basic',
    'scholarshipType2'          => 'basic',
    'seoTitle'                  => 'basic',
    'seoKeywords'               => 'basic',
    'seoDescription'            => 'basic',
    'seoUrl'                    => 'basic',
    'organisationName'          => 'basic',
    'organisationLogo'          => 'basic',
    'modifiedAt'                => 'basic',
    'subscriptionType'          => 'basic',
    'specialRestrictionDescription'=> 'specialRestrictions',
    'specialRestrictionLink'       => 'specialRestrictions',
    'specialRestriction'           => 'specialRestrictions',
    'totalAmountPayout'            => 'amount',
    'amountCurrency'               => 'amount',
    'amountInterval'               => 'amount',
    'amountDescription'            => 'amount',
    'amountDescriptionLink'        => 'amount',
    'expensesCovered'              => 'amount',
    'convertedTotalAmountPayout'   => 'amount',
    
    'courseLevel'                  => 'hierarchy',
    'parentCategory'               => 'hierarchy',
    'subcategory'                  => 'hierarchy',
    'specialization'               => 'hierarchy',
    'courseCategoryComments'       => 'hierarchy',
    'course'                       => 'hierarchy',
    'university'                   => 'hierarchy',
    'department'                   => 'hierarchy',
    'allCategorizations'           => 'hierarchy',   // it should not be passed to repo as a field, pass other hierarchy fields, if scholarship has allCategorization then repo will return it.
    'workXPRequired'               => 'eligibility',
    'workXP'                       => 'eligibility',
    'interviewRequired'            => 'eligibility',
    'eligibilityDescription'       => 'eligibility',
    'eligibilityLink'              => 'eligibility',
    'selectionPreference'          => 'eligibility',
    'scholarshipExamsData'         => 'eligibility',
    'scholarshipEducationData'     => 'eligibility',
    'applicableCountries'          => 'application',
    'docsDescription'              => 'application',
    'applyNowLink'                 => 'application',
    'faqLink'                      => 'application',
    'contactEmail'                 => 'application',
    'contactPhone'                 => 'application',
    'scholarshipBrochureUrl'       => 'application',
    'SOP'                          => 'application',
    'LOR'                          => 'application',
    'Essays'                       => 'application',
    'CV'                           => 'application',
    'financialDocuments'           => 'application',
    'extraCurricularActivities'    => 'application',
    'workExperience'               => 'application',
    'officialTranscripts'          => 'application',
    'applicableNationalities'      => 'application',
    'intakeYear'                   => 'application',    
    'applicationStartDate'               => 'deadline',
    'applicationStartDateDescription'    => 'deadline',
    'applicationEndDate'                 => 'deadline',
    'applicationEndDateDescription'      => 'deadline',
    'additionalInfo'                     => 'deadline',
    'numAwards'                          => 'deadline',
    'numAwardsDescription'               => 'deadline',
    'deadLineType'                       => 'deadline',
    'importantDates'                     => 'deadline',
);

$config['SCHOLARSHIP_AMOUNT_INTERVAL'] = array('one_time'=>'One Time',
                                   'year'=>'Yearly',
                                   'semester'=>'Every Semester',
                                   'trimester'=>'Every Trimester',
                                   'month'=>'Monthly');

$config['countryPriorityOrder']  = array(3=>'USA',8=>'Canada',5=>'Australia',4=>'UK',7=>'New Zealand',9=>'Germany',21=>'Ireland',12=>'France',11=>'Sweden',14=>'Netherlands',6=>'Singapore');

$config['categoryPriorityOrder'] = array(239=>'Business',240=>'Engineering',241=>'Computers',242=>'Science',243=>'Medicine',244=>'Humanities',245=>'Law');

?>
