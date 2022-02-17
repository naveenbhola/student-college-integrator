<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Define the Constants here with "ENT_SA_"(Entity of study abroad) prefix.
**/
$config['ENT_SA'] = 'ENT_SA';

define("ENT_SA_CMS_PATH" , "/listingPosting/AbroadListingPosting/");
define("ENT_SA_CMS_PUBLISHER_RANKING_PATH" , "/publisherRanking/publisherRanking/");
define("ENT_SA_CMS_EXPERT_POSTING_PATH" , "/expertPosting/expertPosting/");
define("ENT_SA_CMS_SCHOLARSHIP_PATH" , "/scholarshipPosting/ScholarshipPosting/");
define("ENT_SA_CMS_CONSULTANT_PATH" , "/consultantPosting/ConsultantPosting/");
define("ENT_SA_CMS_RMC_PATH" , "/shikshaApplyCRM/rmcPosting/");
define("ENT_SA_CMS_NOTIFICATION_PATH" , "/shikshaApplyCRM/shikshaApplyNotification/");
define("ENT_SA_CMS_SHIKSHA_APPLY_IL_PATH" , "/shikshaApplyCRM/shikshaApplyIncomingLead/");
define("ENT_SA_CMS_SHIKSHA_APPLY_SS_PATH" , "/shikshaApplyCRM/shikshaApplySearchStudent/");
define("ENT_SA_CMS_SHIKSHA_APPLY_FOLLOWUP_PATH" , "/shikshaApplyCRM/shikshaApplyFollowup/");
define("ENT_SA_CMS_RMC_FOLLOWUP_PATH" , "/shikshaApplyCRM/rmcFollowUpUsers/");
define("ENT_SA_EXCEPTION_RULES_PATH" , "/shikshaApplyCRM/shikshaApplyExceptionRules/");
define("ENT_SA_CMS_PENDING_LEADS_PATH", "/shikshaApplyCRM/shikshaApplyLeadsAndResponses/");
define("ENT_SA_CMS_SALES" , "/salesDashboard/SalesDashboard/");
define("ENT_SA_CMS_DOCKETS_PATH" , "/dockets/dockets/");
define("ENT_SA_CMS_TOOLS_PATH" , "/saCMSTools/saCMSTools/");
define("ENT_SA_CMS_COUNTRYHOME_PATH" , "/countryPage/countryHomePosting/");
define("ENT_SA_CMS_NAVBARS_PATH" , "/contentNavbarPosting/ContentNavbarPosting/");
define("ENT_SA_CMS_CLIENT_ACTIVATION_PATH" , "/dashboard/clientActivation/");
define("ENT_SA_CMS_COUNSELLOR_REPORT_PATH","/shikshaApplyCRM/counsellorConversionReport/");

define("ENT_SA_HIGH_VALUE_FIELD_STATUS", 5);
define("ENT_SA_LOW_VALUE_FIELD_STATUS", 2);
define("ENT_SA_DUMMY_DEPT_STATUS", "dummydept");

//Exam Navbar Links Min and Max Tuple Limit
define("ENT_SA_EXAM_NAVBAR_MIN_TUPLE_COUNT", 2);
define("ENT_SA_EXAM_NAVBAR_MAX_TUPLE_COUNT", 10);


// END


/**
* Constants of method names of univ,dept,course etc for every action
**/
// City
define("ENT_SA_FORM_ADD_CITY"                           , "addCityForm");
define("ENT_SA_VIEW_LISTING_CITY"                       , "viewCityListing");
define("ENT_SA_FORM_EDIT_CITY"                          , "editCityForm");
    
// University   
define("ENT_SA_FORM_ADD_UNIVERSITY"                     , "addUniversityForm");
define("ENT_SA_FORM_EDIT_UNIVERSITY"                    , "editUniversityForm");
define("ENT_SA_VIEW_LISTING_UNIVERSITY"                 , "viewUniversityListing");
define("ENT_SA_DELETE_UNIVERSITY"                       , "deleteUniversity");
    
// Department   
define("ENT_SA_FORM_ADD_DEPARTMENT"                     , "addDepartmentForm");
define("ENT_SA_FORM_EDIT_DEPARTMENT"                    , "editDepartmentForm");
define("ENT_SA_VIEW_LISTING_DEPARTMENT"                 , "viewDepartmentListing");
    
// Course       
define("ENT_SA_FORM_ADD_COURSE"                         , "showAddCourseForm");
define("ENT_SA_FORM_EDIT_COURSE"                        , "showEditCourseForm");
define("ENT_SA_VIEW_LISTING_COURSE"                     , "viewCourseListing");
define("ENT_SA_DELETE_COURSE"                           , "deleteCourseListing");
define("ENT_SA_VIEW_RESTORE_COURSE"                     , "viewRestoreCourseListing");
define("ENT_SA_RESTORE_COURSE"                          , "restoreCourseListing");

// Snapshot Course      
define("ENT_SA_FORM_ADD_SNAPSHOT_COURSE"                , "addSnapshotCourseForm");
define("ENT_SA_FORM_EDIT_SNAPSHOT_COURSE"               , "editSnapshotCourseForm");
define("ENT_SA_VIEW_LISTING_SNAPSHOT_COURSE"            , "viewSnapshotCourseListing");
define("ENT_SA_FORM_ADD_BULK_SNAPSHOT_SOURES"           , "addBulkSnapshotCourseForm");
define("ENT_SA_DELETE_LISTING_SNAPSHOT_COURSE"          , "deleteSnapshotCourse");
    
// Ranking      
define("ENT_SA_FORM_ADD_RANKING"                        , "addRankingForm");
define("ENT_SA_FORM_EDIT_RANKING"                       , "editRankingForm");
define("ENT_SA_VIEW_LISTING_RANKING"                    , "viewRankingListing");
define("ENT_SA_DELETE_LISTING_RANKING"                  , "deleteRankingListing");

// Publisher Ranking      
define("ENT_SA_FORM_ADD_PUBLISHER_RANKING"              , "addPublisherRanking");
define("ENT_SA_FORM_EDIT_PUBLISHER_RANKING"             , "editPublisherRanking");
define("ENT_SA_VIEW_PUBLISHER_RANKING"                  , "viewPublisherRanking");
define("ENT_SA_DELETE_PUBLISHER_RANKING"                , "deletePublisherRanking");

// Expert
define("ENT_SA_FORM_ADD_EXPERT"              , "addExpert");
define("ENT_SA_FORM_EDIT_EXPERT"             , "editExpert");
define("ENT_SA_VIEW_EXPERT"                  , "viewExpert");

// Advance Search
define("ENT_SA_FORM_ADVANCE_SEARCH_UNIVERSITY"          , "advanceSearchUniversityForm");
define("ENT_SA_FORM_ADVANCE_SEARCH_DEPARTMENT"          , "advanceSearchDepartmentForm");
define("ENT_SA_FORM_ADVANCE_SEARCH_COURSE"              , "advanceSearchCourseForm");
define("ENT_SA_VIEW_LISTING_ADVANCE_SEARCH_UNIVERSITY"  , "viewAdvanceSearchUniversityListing");
define("ENT_SA_VIEW_LISTING_ADVANCE_SEARCH_DEPARTMENT"  , "viewAdvanceSearchDepartmentListing");
define("ENT_SA_VIEW_LISTING_ADVANCE_SEARCH_COURSE"      , "viewAdvanceSearchCourseListing");

// Scholarship
define("ENT_SA_VIEW_SCHOLARSHIPS"                    ,"viewAllScholarships");
define("ENT_SA_ADD_SCHOLARSHIP"                      ,"addScholarshipForm");
define("ENT_SA_EDIT_SCHOLARSHIP"                     ,"editScholarshipForm");
define("ENT_SA_DELETE_SCHOLARSHIP"                   ,"deleteScholarship");

//Dockets
define("ENT_SA_FORM_ADD_DOCKETS"                    ,"addDockets");
define("ENT_SA_FORM_EDIT_DOCKETS"                   ,"editDockets");
define("ENT_SA_VIEW_DOCKETS"                        ,"viewDockets");

//Content
define("ENT_SA_VIEW_LISTING_CONTENT"                    , "viewContentListing");
define("ENT_SA_FORM_ADD_CONTENT"                        , "addContentListing");
define("ENT_SA_FORM_EDIT_CONTENT"                       , "editContentListing");
define("ENT_SA_FORM_DELETE_CONTENT"                       , "deleteContentListing");

//Exam Navbar Links
define("ENT_SA_EXAM_NAVBAR_LINKS"                    , "examApplyNavbarLinks");
define("ENT_SA_CONTENT_NAVBARS"                      , "viewContentNavbars");
define("ENT_SA_EDIT_CONTENT_NAVBAR_LINKS"            , "addEditContentNavbarLinks");

// paid client
define("ENT_SA_VIEW_PAID_CLIENT"                        , "viewPaidClient");
define("ENT_SA_FORM_ADD_PAID_CLIENT"                    , "addPaidClient");
define("ENT_SA_FORM_EDIT_PAID_CLIENT"                   ,"editPaidClient");

// RMS
define("ENT_SA_VIEW_RMS_COUNSELLOR"                     ,"viewRMSCounsellor");
define("ENT_SA_FORM_ADD_RMS_COUNSELLOR"                 ,"addRMSCounsellor");
define("ENT_SA_FORM_EDIT_RMS_COUNSELLOR"                ,"editRMSCounsellor");
define("ENT_SA_FORM_DELETE_RMS_COUNSELLOR"              ,"deleteRMSCounsellor");
define("ENT_SA_VIEW_EXAM_EXCEPTION_RULES"           ,"viewExamExceptionRules");
define("ENT_SA_VIEW_NON_EXAM_EXCEPTION_RULES"           ,"viewNonExamExceptionRules");
define("ENT_SA_EDIT_NON_EXAM_EXCEPTION_RULES"           ,"editNonExamExceptionRule");

 //RMS Universities
define("ENT_SA_VIEW_RMS_UNIVERSITIES"                        ,"viewRMSUniversities");
define("ENT_SA_FORM_ADD_RMS_UNIVERSITIES"                    ,"addRMSUniversities");
define("ENT_SA_FORM_DELETE_RMS_Universities"                 ,"deleteRMSUniversities");

//Consultants
define('ENT_SA_FORM_ADD_CONSULTANT'                 , 'addConsultantForm');
define('ENT_SA_FORM_EDIT_CONSULTANT'                , 'editConsultantForm');
define('ENT_SA_VIEW_CONSULTANT_TABLE'               , 'viewConsultants');
define('ENT_SA_DELETE_CONSULTANT'                   , 'deleteConsultant');

define('ENT_SA_FORM_ADD_CONSULTANT_LOCATION'        , 'addConsultantLocationForm');
define('ENT_SA_FORM_EDIT_CONSULTANT_LOCATION'       , 'editConsultantLocationForm');
//define('ENT_SA_VIEW_CONSULTANT_LOCATION_TABLE'      , 'viewConsultantLocations');
define('ENT_SA_DELETE_CONSULTANT_LOCATION'          , 'deleteConsultantLocation');

define('ENT_SA_FORM_ADD_CONSULTANT_UNIVERSITY_MAPPING'                 , 'addConsultantUniversityMappingForm');
define('ENT_SA_FORM_EDIT_CONSULTANT_UNIVERSITY_MAPPING'                , 'editConsultantUniversityMappingForm');
define('ENT_SA_VIEW_CONSULTANT_UNIVERSITY_MAPPING_TABLE'               , 'viewConsultantUniversityMapping');
define('ENT_SA_DELETE_CONSULTANT_UNIVERSITY_MAPPING'                   , 'deleteConsultantUniversityMapping');

define('ENT_SA_FORM_ADD_STUDENT_PROFILE'           , 'addStudentProfileForm');
define('ENT_SA_FORM_EDIT_STUDENT_PROFILE'          , 'editStudentProfileForm');
define('ENT_SA_VIEW_STUDENT_PROFILE'               , 'viewStudentProfile');
define('ENT_SA_DELETE_STUDENT_PROFILE'             , 'deleteStudentProfile');

define('ENT_SA_FORM_ASSIGN_REGION'           , 'assignRegionForm');
define('ENT_SA_FORM_EDIT_ASSIGNED_REGION'          , 'editRegionForm');
define('ENT_SA_VIEW_ASSIGNED_REGION'               , 'viewAssignedRegions');

define('ENT_SA_VIEW_LOCALITY_TABLE'                  , 'viewLocality');
define('ENT_SA_FORM_ADD_LOCALITY'                   , 'addLocality');
define('ENT_SA_FORM_EDIT_LOCALITY'                  , 'editLocality');

define('ENT_SA_VIEW_UPGRADE_CONSULTANT'                  , 'viewClientConsultantSubscription');
define('ENT_SA_FORM_ADD_CLIENT_CONSULTANT_SUBSCRIPTION'  , 'addClientConsultantSubscription');
define('ENT_SA_FORM_EDIT_CLIENT_CONSULTANT_SUBSCRIPTION' , 'editClientConsultantSubscription');

//Rate My Chance RMC
define('ENT_SA_VIEW_PENDING_RMC'                 , 'viewPendingRMCTable');
define('ENT_SA_VIEW_INCOMING_LEADS'              , 'viewIL');
define('ENT_SA_VIEW_SS_LEADS'                    , 'viewStudent');
define('ENT_SA_VIEW_FOLLOWUP'                    , 'viewFollowup');
define('ENT_SA_VIEW_FOLLOWUP_RMC'                , 'viewFollowUpRMCTable');
define('ENT_SA_VIEW_RMC_CANDIDATES'              , 'viewRMCCandidatesTable');
define('ENT_SA_RMC_CANDIDATES_DETAILS'           , 'rmcCandidateProfile');
define('ENT_SA_VIEW_RMC_CANDIDATES_SHORTLIST'    , 'viewRMCCandidatesShortlistTable');
define('ENT_SA_ADD_EDIT_CANDIDATES_SHORTLIST'    , 'addEditRMCCandidatesShortlists');
define('ENT_SA_VIEW_NOTIFICATION'                , 'viewCRMNotifications');
define('ENT_SA_VIEW_COUNSELLOR_REPORT'           , 'viewCounsellorConversionReport');

//Pending Leads
define('ENT_SA_VIEW_PENDING_LEADS', 'viewPendingLeadsTable');
define('ENT_SA_VIEW_PENDING_RESPONSES', 'viewPendingResponsesTable');

//RMC Universities
define("ENT_SA_VIEW_RMC_UNIVERSITIES"                        ,"viewRMCUniversities");
define("ENT_SA_FORM_ADD_RMC_UNIVERSITIES"                    ,"addRMCUniversities");
define("ENT_SA_FORM_EDIT_RMC_UNIVERSITIES"                   ,"editRMCUniversities");
define("ENT_SA_FORM_DELETE_RMC_Universities"                 ,"deleteRMCUniversities");

//RMC Rules
define("ENT_SA_ADD_RMC_RULE"	,"addRMCRule");
define("ENT_SA_EDIT_RMC_RULE"	,"editRMCRule");
define("ENT_SA_RMC_RULE_FORM"	,"addEditRMCRule");
define("ENT_SA_VIEW_RMC_RULE"	,"viewRMCListingRules");

//Specializations
define("ENT_SA_VIEW_SPECIALIZATIONS","viewSpecializations");
define("ENT_SA_SPECIALIZATION_FORM", "addEditSpecializations");

//Customer Delivery
define("ENT_SA_CUSTOMER_DELIVERY","clientDeliveryDashboard");

//Couselling sales dashboard
define("ENT_SA_FORM_ADD_CLIENT_ACTIVATION", "addClientActivationForm");
define("ENT_SA_FORM_EDIT_CLIENT_ACTIVATION", "editClientActivationForm");
define("ENT_SA_VIEW_LISTING_CLIENT_ACTIVATION", "viewClientActivation");

//Tools: Currencies
define("ENT_SA_CURRENCIES","viewCurrencies");
define("ENT_SA_SALES_MIS_USERS","viewAddSASalesMISUsers");
define("ENT_SA_REPORT_SPAM", "getSpamContentData");

//Country home widgets
define('ENT_SA_VIEW_COUNTRYHOME_WIDGETS', 'viewCountryHomeWidgetTable');
define('ENT_SA_EDIT_COUNTRYHOMEWIDGETS' , 'editCountryHomeWidgets');

//Lead Allocation Rules
define('ENT_SA_VIEW_LEAD_ALLOCATION_PATH','/shikshaApplyCRM/abroadLeadAllocation/');
define('ENT_SA_VIEW_LEAD_ALLOCATION_RULES','viewLeadAllocationRulesTable');
define('ENT_SA_EDIT_LEAD_ALLOCATION_RULES','editLeadAllocationRules');

//RMC candidate table specific
define("ENT_SA_ADMIN_ID", 0);
define("ENT_SA_AUDITOR_ID", -1);
define("ENT_SIMRAN_USER_ID", 2762136);

global $abroadLeadAllocationCountries;
$abroadLeadAllocationCountries = array(3=>'USA',4=>'UK',5=>'Australia',6=>'Singapore',7=>'New Zealand',8=>'Canada',9=>'Germany',11=>'Sweden',12=>'France',14=>'Netherlands',21=>'Ireland',0=>'Other');
global $abroadLeadAllocationRuleNames;

$abroadLeadAllocationRuleNames = array(
    DESIRED_COURSE_MBA =>'MBA',
    DESIRED_COURSE_MS => 'MS',
    DESIRED_COURSE_BTECH =>'BTech',
    DESIRED_COURSE_MEM=>'MEM',
    DESIRED_COURSE_MPHARM=>'MPharm',
    DESIRED_COURSE_MFIN=>'MFin',
    DESIRED_COURSE_MDES=>'MDes',
    DESIRED_COURSE_MFA=>'MFA',
    DESIRED_COURSE_MENG=>'MEng',
    DESIRED_COURSE_BSC=>'BSc',
    DESIRED_COURSE_BBA=>'BBA',
    DESIRED_COURSE_MBBS=>'MBBS',
    DESIRED_COURSE_BHM=>'BHM',
    DESIRED_COURSE_MARCH=>'MArch',
    DESIRED_COURSE_MIS=>'MIS',
    DESIRED_COURSE_MIM=>'MIM',
    DESIRED_COURSE_MASC=>'MASc',
    DESIRED_COURSE_MA=>'MA',
    2=>'Bachelors Certification / Diploma',
    1=>'Masters Certification / Diploma',
    0=>'Others'
);
/**
* Left navigation's detailed array
**/
$config['ENT_SA_LEFT_NAVIGATION_DETAILED_ARR'] =
    array(
        "MAIN_LISTINGS" =>  array(
                                
                                "CHILDREN"  => array(
                                        "UNIVERSITY"      => array("URL"          => ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_UNIVERSITY,
                                                                  "DISPLAY_TEXT" => "Universities",
                                                                  "USER_GROUP_HIDE" => array('saSales','saRMS')),
                                        "DEPARTMENT"      => array("URL"           => ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_DEPARTMENT,
                                                                  "DISPLAY_TEXT" => "Departments",
                                                                  "USER_GROUP_HIDE" => array('saSales','saRMS')),
                                        "COURSE"          => array("URL"           => ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_COURSE,
                                                                  "DISPLAY_TEXT" => "Courses",
                                                                  "USER_GROUP_HIDE" => array('saSales','saRMS')),
                                        "RESTORE_COURSE"          => array("URL"           => ENT_SA_CMS_PATH.ENT_SA_VIEW_RESTORE_COURSE,
                                                                    "DISPLAY_TEXT" => "Restore Course",
                                                                    "USER_GROUP_HIDE" => array('saSales','saRMS')),
                                        "SPECIALIZATIONS"=> array(
                                                                "URL" => ENT_SA_CMS_PATH.ENT_SA_VIEW_SPECIALIZATIONS,
                                                                "DISPLAY_TEXT" => "Specializations",
                                                                "USER_GROUP_HIDE" => array('saSales','saCMS')
                                        ),
                                        "RANKING"         => array("URL"           => ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_RANKING,
                                                                  "DISPLAY_TEXT" => "Ranking",
                                                                  "USER_GROUP_HIDE" => array('saSales','saRMS')),
                                        "ADVANCE_SEARCH"  => array("URL"           => ENT_SA_CMS_PATH.ENT_SA_FORM_ADVANCE_SEARCH_UNIVERSITY,
                                                                  "DISPLAY_TEXT" => "Advance Search",
                                                                  "USER_GROUP_HIDE" => array('saSales','saRMS')),
                                        "PAID_CLIENT"    => array("URL"           => ENT_SA_CMS_PATH.ENT_SA_VIEW_PAID_CLIENT,
                                                                  "DISPLAY_TEXT" => "Upgrade Courses",
                                                                  "USER_GROUP_HIDE" => array('saCMS','saRMS','saCMSLead'))
                                ),
                                "DISPLAY_TEXT"        => "Main Listings",
                                "USER_GROUP_HIDE" => array('saContent','saRMS','saShikshaApply','saCustomerDelivery','saAuditor')
                            ),
        "SCHOLARSHIP" =>  array(
                                
                                "CHILDREN"  => array(
                                        "SCHOLARSHIP"      => array("URL"          => ENT_SA_CMS_SCHOLARSHIP_PATH.ENT_SA_VIEW_SCHOLARSHIPS,
                                                                  "DISPLAY_TEXT" => "Scholarships",
                                                                  "USER_GROUP_HIDE" => array('saSales','saRMS','saContent','saShikshaApply','saCustomerDelivery'))
                                ),
                                "DISPLAY_TEXT"        => "Scholarships",
                                "USER_GROUP_HIDE" => array('saSales','saRMS','saContent','saShikshaApply','saCustomerDelivery','saAuditor')
                            ),
        "LOOKUPS"       =>  array(
                                "CHILDREN"  =>    array(
                                        "CITY"      => array("URL"          => ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_CITY,
                                                            "DISPLAY_TEXT" => "City",
                                                            "USER_GROUP_HIDE" => array('saRMS')),
                                        "PUBLISHER_RANKING" => array("URL"           => ENT_SA_CMS_PUBLISHER_RANKING_PATH.ENT_SA_VIEW_PUBLISHER_RANKING,
                                                                  "DISPLAY_TEXT" => "Ranking",
                                                                  "USER_GROUP_HIDE" => array('saSales','saRMS'))
                                ),
                                "DISPLAY_TEXT" => "Lookups",
                                "USER_GROUP_HIDE" => array('saContent','saSales','saRMS','saShikshaApply','saCustomerDelivery','saAuditor')
                            ),
        "CONTENT"       =>  array(
                                "CHILDREN"  =>    array(
                                        "CONTENT"   =>  array("URL"          => ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_CONTENT,
                                                            "DISPLAY_TEXT" => "Content",
                                                             "USER_GROUP_HIDE"=>array('saCMSLead')
                                                        ),
                                        "EXAM_LINKS"   =>  array("URL"          => ENT_SA_CMS_PATH.ENT_SA_EXAM_NAVBAR_LINKS,
                                            "DISPLAY_TEXT" => "Exam Apply Navbar Links",
                                            "USER_GROUP_HIDE" => array('saCMS','saSales','saRMS','saShikshaApply','saCustomerDelivery','saCMSLead')
                                        ),
                                        "CONTENT_LINKS"   =>  array("URL"          => ENT_SA_CMS_NAVBARS_PATH.ENT_SA_CONTENT_NAVBARS,
                                            "DISPLAY_TEXT" => "Content Navbar Links",
                                            "USER_GROUP_HIDE" => array('saCMS','saSales','saRMS','saShikshaApply','saCustomerDelivery','saCMSLead')
                                        ),
                                        "COUNTRY_HOME"   => array("URL"          => ENT_SA_CMS_COUNTRYHOME_PATH.ENT_SA_VIEW_COUNTRYHOME_WIDGETS,
                                                             "DISPLAY_TEXT" => "Country Home",
                                                             "USER_GROUP_HIDE"=>array('saCMSLead')
                                                            ),
                                        "EXPERT_PROFILE"   => array("URL"          => ENT_SA_CMS_EXPERT_POSTING_PATH.ENT_SA_VIEW_EXPERT,
                                                             "DISPLAY_TEXT" => "Expert Profile"
                                                            ),
                                ),
                                "DISPLAY_TEXT"  =>    "Content",
                                "USER_GROUP_HIDE" => array('saSales','saRMS','saShikshaApply','saCustomerDelivery','saAuditor')
                            ) , 
      
        "RMS"           =>  array(
                                "CHILDREN"  =>    array(
                                        "RMS_COUNSELLORS"   => array("URL"          => ENT_SA_CMS_PATH.ENT_SA_VIEW_RMS_COUNSELLOR,
                                                                    "DISPLAY_TEXT" => "RMS Counsellors"
                                                            ),
                                        "RMS_UNIVERSITIES"  => array("URL"          => ENT_SA_CMS_PATH.ENT_SA_VIEW_RMS_UNIVERSITIES,
                                                                    "DISPLAY_TEXT" => "RMS Universities"
                                                            ),
                                        "RMC_UNIVERSITIES"  => array("URL"          => ENT_SA_CMS_RMC_PATH.ENT_SA_VIEW_RMC_UNIVERSITIES,
                                                                    "DISPLAY_TEXT" => "RMC Universities"
                                                            ),
                                        "RMC_RULES"  		=> array("URL"          => ENT_SA_CMS_RMC_PATH.ENT_SA_VIEW_RMC_RULE,
                                                                    "DISPLAY_TEXT" => "Rating Rules"
                                                            ),
                                        "EXAM_EXCEPTION_RULES"  		=> array("URL"          => ENT_SA_EXCEPTION_RULES_PATH.ENT_SA_VIEW_EXAM_EXCEPTION_RULES,
                                                                    "DISPLAY_TEXT" => "Exam exception rules"
                                                            ),
                                        "NON_EXAM_EXCEPTION_RULES"      => array("URL"          => ENT_SA_EXCEPTION_RULES_PATH.ENT_SA_VIEW_NON_EXAM_EXCEPTION_RULES,
                                                                    "DISPLAY_TEXT" => "Non-exam exception rules"
                                                            ),
					"LEAD_ALLOCATION_RULES" => array("URL"=>ENT_SA_VIEW_LEAD_ALLOCATION_PATH.ENT_SA_VIEW_LEAD_ALLOCATION_RULES,
                                                                    "DISPLAY_TEXT"=>"Allocation Table"
                                                            )
                                ),
                                "DISPLAY_TEXT"  =>    "RMS",
                                "USER_GROUP_HIDE" => array('saSales','saContent','saCMS','saShikshaApply','saCMSLead','saCustomerDelivery','saAuditor')
                            ),
       
        'CONSULTANTS'   =>  array(
                                'CHILDREN'  =>      array(
                                          'CONSULTANTS'       =>  array('URL'        =>  ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_CONSULTANT_TABLE,
                                                                          'DISPLAY_TEXT'  =>  'Consultants',
                                                                          "USER_GROUP_HIDE" => array('saSales','saRMS','saContent')
                                                              ),
                                          'MAP_UNIVERSITY'    =>  array('URL'        =>  ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_CONSULTANT_UNIVERSITY_MAPPING_TABLE,
                                                                          'DISPLAY_TEXT'  =>  'Map University',
                                                                          "USER_GROUP_HIDE" => array('saSales','saRMS','saContent')
                                                                          ),
                                          'MAP_PROFILES'      =>  array('URL'        => ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_STUDENT_PROFILE,
                                                                          'DISPLAY_TEXT'  => 'Map Profiles',
                                                                          "USER_GROUP_HIDE" => array('saSales','saRMS','saContent')
                                                                          ),
                                          'ASSIGN_CITIES'     =>  array('URL'        => ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_ASSIGNED_REGION,
                                                                          'DISPLAY_TEXT'  =>  'Assign Regions',
                                                                          "USER_GROUP_HIDE" => array('saCMS','saRMS','saContent')
                                                                          ),
                                          'LOCATIONS'     =>  array('URL'        => ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_LOCALITY_TABLE,
                                                                          'DISPLAY_TEXT'  =>  'Locations',
                                                                          "USER_GROUP_HIDE" => array('saRMS','saSales','saContent')
                                                                          ),
                                          'UPGRADE_CONSULTANT'     =>  array('URL'        => ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_UPGRADE_CONSULTANT,
                                                                          'DISPLAY_TEXT'  =>  'Upgrade Consultant',
                                                                          "USER_GROUP_HIDE" => array('saRMS','saContent','saCMS')
                                                                          )
                                ),
                                'DISPLAY_TEXT'  =>  'Consultants',
                                'USER_GROUP_HIDE'   =>  array('saRMS','saContent','saCMS','saShikshaApply','saCustomerDelivery','saAuditor')
                                ),

              "RMC"   =>  array(
                                "CHILDREN"  =>  array(

                                        // "NOTIFICATION"  => array(   "URL"          => ENT_SA_CMS_NOTIFICATION_PATH.ENT_SA_VIEW_NOTIFICATION,
                                        //                             "DISPLAY_TEXT" => "Notifications",
                                        //                             "USER_GROUP_HIDE" => array('saCMS','saCMSLead','saAuditor')

                                        //                     ),
                                        "RMC_PENDING"   => array(   "URL"          => ENT_SA_CMS_RMC_PATH.ENT_SA_VIEW_PENDING_RMC,
                                                                    "DISPLAY_TEXT" => "Pending RMC",
                                                                    "USER_GROUP_HIDE" => array('saCMS','saCMSLead','saAuditor')
                                                            ),
                                        // "RESPONSES_PENDING" => array(   "URL"          => ENT_SA_CMS_PENDING_LEADS_PATH.ENT_SA_VIEW_PENDING_RESPONSES,
                                        //                             "DISPLAY_TEXT" => "Pending Non RMC",
                                        //                              "USER_GROUP_HIDE" => array('saCMS','saCMSLead','saAuditor')
                                            
                                        //                     ),
                                        // "LEADS_PENDING" => array(   "URL"          => ENT_SA_CMS_PENDING_LEADS_PATH.ENT_SA_VIEW_PENDING_LEADS,
                                        //                             "DISPLAY_TEXT" => "Pending Leads",
                                        //                              "USER_GROUP_HIDE" => array('saCMS','saCMSLead','saAuditor')
                                            
                                        //    
                                        "RMC_SEARCHSTUDENT"  => array(   "URL"          => ENT_SA_CMS_SHIKSHA_APPLY_SS_PATH.ENT_SA_VIEW_SS_LEADS,
                                                                    "DISPLAY_TEXT" => "Search Student",
                                                                    "USER_GROUP_HIDE" => array('saCMS','saCMSLead','saShikshaApply')
                                                            ),    
                                        "RMC_INCOMINGLEADS"  => array(   "URL"          => ENT_SA_CMS_SHIKSHA_APPLY_IL_PATH.ENT_SA_VIEW_INCOMING_LEADS,
                                                                    "DISPLAY_TEXT" => "Incoming Leads",
                                                                    "USER_GROUP_HIDE" => array('saCMS','saCMSLead','saAuditor')
                                                            ),
                                        "RMC_FOLLOWUP"  => array(   "URL"          => ENT_SA_CMS_SHIKSHA_APPLY_FOLLOWUP_PATH.ENT_SA_VIEW_FOLLOWUP,
                                                                    "DISPLAY_TEXT" => "Followup",
                                                                    "USER_GROUP_HIDE" => array('saCMS','saCMSLead','saAuditor')
                                                            ),
                                        // "RMC_FOLLOWUP"  => array(   "URL"          => ENT_SA_CMS_RMC_FOLLOWUP_PATH.ENT_SA_VIEW_FOLLOWUP_RMC,
                                        //                             "DISPLAY_TEXT" => "Followup RMC",
                                        //                             "USER_GROUP_HIDE" => array('saCMS','saCMSLead','saAuditor')
                                        //                     ),
                                        "RMC_CANDIDATES"=> array(   "URL"          => ENT_SA_CMS_RMC_PATH.ENT_SA_VIEW_RMC_CANDIDATES,
                                                                    "DISPLAY_TEXT" => "Candidates",
                                                                    "USER_GROUP_HIDE" => array('saCMS','saCMSLead')
                                                            ),
                                                                          
                                        "RMC_SHORTLISTS"=> array(   "URL"          => ENT_SA_CMS_RMC_PATH.ENT_SA_VIEW_RMC_CANDIDATES_SHORTLIST,
                                                                    "DISPLAY_TEXT" => "Shortlist",
                                                                    "USER_GROUP_HIDE" => array('saCMS','saCMSLead','saAuditor')
                                                            ),
                                        "DOCKETS"    => array("URL"           => ENT_SA_CMS_DOCKETS_PATH.ENT_SA_VIEW_DOCKETS,
                                                                  "DISPLAY_TEXT" => "Dockets",
                                                                  "USER_GROUP_HIDE" => array('saAuditor')
                                                              ),
                                    "COUNSELLOR_CONVERSION" =>array(
                                                                "URL" => ENT_SA_CMS_COUNSELLOR_REPORT_PATH.ENT_SA_VIEW_COUNSELLOR_REPORT,
                                                                "DISPLAY_TEXT"=>"Counsellor Report",
                                                                "USER_GROUP_HIDE" =>array('saCMS','saCMSLead','saAuditor')
                                                                )
                                ),
                                "DISPLAY_TEXT"  =>    "Counseling",
                                "USER_GROUP_HIDE" => array('saContent','saSales','saRMS','saCustomerDelivery')
                              ),
              "SALESDASHBOARD" => array(
                                "CHILDREN" => array(
                                        "CLIENT_DELIVERY" => array("URL" => ENT_SA_CMS_SALES.ENT_SA_CUSTOMER_DELIVERY,
                                                                   "DISPLAY_TEXT" => "Client Delivery",
                                                                    "USER_GROUP_HIDE" => array('saSales','saRMS','saCMS','saContent','saCMSLead','saAuditor')
                                                              ),
                                        "CLIENT_ACTIVATION" => array("URL" =>ENT_SA_CMS_CLIENT_ACTIVATION_PATH.ENT_SA_VIEW_LISTING_CLIENT_ACTIVATION,
                                                                    "DISPLAY_TEXT" => "Client Activation",
                                                                    "USER_GROUP_HIDE" => array()

                                        )
                                ),
                                "USER_GROUP_HIDE" => array(),
                                "DISPLAY_TEXT" => 'Dashboard'
                              ),
              "TOOLS" => array(
                                "CHILDREN" => array(
                                        "CURRENCIES" => array("URL" => ENT_SA_CMS_TOOLS_PATH.ENT_SA_CURRENCIES,
                                                                   "DISPLAY_TEXT" => "Currencies",
                                                                   "USER_GROUP_HIDE" => array('saContent')
                                                              ),
                                        "SA_SALES_MIS_USERS" => array("URL" => ENT_SA_CMS_TOOLS_PATH.ENT_SA_SALES_MIS_USERS,
                                                                   "DISPLAY_TEXT" => "SA Sales MIS Users",
                                                                   "USER_GROUP_HIDE" => array('saCMS','saContent','saSales','saRMS','saShikshaApply','saCMSLead')
                                                                   ),
                                        "REPORT_SPAM" => array("URL" => ENT_SA_CMS_TOOLS_PATH.ENT_SA_REPORT_SPAM,
                                                                   "DISPLAY_TEXT" => "Report Spam",
                                                                   "USER_GROUP_HIDE" => array('saCMS','saSales','saRMS','saShikshaApply','saCMSLead')
                                                              ),
                                ),
                                "USER_GROUP_HIDE" => array('saCMS','saSales','saRMS','saShikshaApply','saAuditor'),
                                "DISPLAY_TEXT" => 'Tools'
                              )
    );

$config['helptext'] =array(

                            "university_tooltips" => array(
                                                    "univ_logo" =>  "<ul><li>Logo should not be blurred</li></ul>",
                                                    "univ_estd" =>  "<ul><li>Should first be sourced from Univ website else from Wikipedia.</li><li style=\"list-style:none\">For eg:- 1924</li></ul>",
                                                    "univ_acrnym"   =>  "<ul><li>Need not necessarily be a short form of every Univ name, it should be a known acronym and should be sourced from the Univ website.</li><li style=\"list-style:none\">For eg:- U of A for University of Alberta</li></ul>",
                                                    "univ_usp"  =>  "<ol><li>Crisp and clear</li><li>Avoid long sentence formations</li><li>Avoid copying exact content from website</li><li>Focus more on the USPs (like, ranked 3rd in UK by Times Education, or, 40% international student population in Univ, accredited by AACSB)</li><li>Avoid generic statements like, wi-fi enabled campus, interactive sessions, latest teaching methodologies. Such statements maybe used only when we can't find appropriate USPs</li><li>Should ideally be around 4-5 points</li><li>Should be in bullets</li><li>Should be in 3rd person</li></ol><b>For eg:-</b><ul><li>University is largest in the east of England with over 30,000 students</li><li>Has international community of over 120 nationalities</li><li>University's International support services are recognised amongst the best (International Student Barometer 2013)</li><li>Its Student Services team is rated the best in the country (Times Higher Education Awards 2012)</li><li>Employment Bureau, university's on-campus recruitment agency support students in finding part-time work whilst studying, and full-time employment after graduation</li></ul>",
                                                    "univ_instype1" =>  "<ul><li>The same can either be determined from Univ website of Wikipedia.Generally always available on Wikipedia.</li><li style=\"list-style:none\">For eg:- Public/Private</li></ul>",
                                                    "univ_instype2" =>  "<ul><li>Can be determined by Institute/University name.</li><li style=\"list-style:none\">For ex:- University of Alberta is a <b>University</b>, London School of Business is a <b>College</b></li></ul>",
                                                    "univ_affDetails"  =>  "<ul><li>Affiliation-To adopt/accept as a member, subordinate associate or branch</li><li>Rely only on Univ website for the same</li><li>When putting affiliation details, ensure the word 'affiliated to' is mentioned. Do Not fill this column if the phrases read 'associated with', 'approved by' etc. Such details may be put in Why Join if it seems important</li><li>Make sure affiliations are at the Univ level (before the phrase affiliated to', the name of the course/department/Univ will be mentioned)</li><li style=\"list-style:none\">For eg:- EURASHE, SPACE, BUTEX, ECREA, HUMANE, HECSU, SUPC, ABS</li></ul>",
                                                    "univ_accrDetails"  =>  "<ul><li>Accreditation-It is an act of granting credit/recognition to maintain suitable standards</li><li>The phrase 'accredited by' should be mentioned and not anything else</li><li>Make sure accreditation is at Univ level.....before the phrase 'accredited by', the name of the course/department/Univ will be mentioned. For eg:- <b>British Accreditation Council</b></li></ul>",
                                                    "univ_contemail"    =>  "<ul><li style=\"list-style:none\">Which e-mail id is to be considered? Put the mail id as per the below order of importance (make sure the below mail ids are at the Univ level and not specific to a particular department/course):-</li><li>Dedicated mail id for international students. For eg:- international@ntu.ac.uk</li><li>General enquries. For eg:- angliaruskin@enquiries.uk.com</li><li>Admissions. For eg:- admissions@anglia.ac.uk</li><li>If none of the above, then anything else</li></ul>",
                                                    "univ_contphn"  =>  "<ul><li>Follow the same order as in 'Email' in deciding which number to mention.</li><li style=\"list-style:none\">For eg:- +44 (0) 151 794 2000</li></ul>",
                                                    "univ_contaddress"  =>  "<ul><li>University website</li><li>In case of multiple campuses, mention the first address which appears when you put the Univ name on google</li><li style=\"list-style:none\">For eg:- Cambridge Campus, E Rd, Cambridge CB1 1PT, United Kingdom</li></ul>",
                                                    "univ_contwebsite"  =>  "<ul><li>Mention the URL from where the contact information was sourced and <b>NOT</b> the URL of Univ homepage.</li><li style=\"list-style:none\">For eg:- http://www.derby.ac.uk/international/contact/</li></ul>",
                                                    "univ_pics" =>  "<ul><li>Available on Univ website, make sure the pictures aren't blurred.</li><li style=\"list-style:none\">For eg:- http://www.anglia.ac.uk/ruskin<br/>/en/home/faculties/aibs/about_us<br/>/picture_gallery.html</li></ul>",
                                                    "univ_video"    =>  "<ol><li>Link should be sourced only from youtube</li><li>Video should ideally include contents related to Infrastructure, labs, classroms, classrom interactions, a little about the environs around the campus etc.</li><li>You may put multiple links if need be</li><li>Video should ideally be around 2 - 3 mins of length</li><li style=\"list-style:none\">For eg;- https://www.youtube.com/watch?v=wY8WnpmCYHU</li></ol>",
                                                    "univ_fbpage"   =>  "<ul><li>Click on the FB link on website and copy URL. In case link is not available, you can try searching for the same by logging in to your FB a/c.</li><li style=\"list-style:none\">For eg:- https://www.facebook.com/universityofleeds</li></ul>",
                                                    "univ_website"  =>  "<ul><li>URL of Univ homepage.</li><li style=\"list-style:none\">For eg:- http://www.derby.ac.uk/</li></ul>",
                                                    "univ_country"    =>  "<ul><li>Choose appropriate country name from dropdown</li><li style=\"list-style:none\">For eg:- UK</li></ul>",
                                                    "univ_state"    =>  "<ul><li>Choose the appropriate option from dropdown</li><li style=\"list-style:none\">For eg:- Nottingham</li></ul>",
                                                    "univ_city" =>  "<ul><li>City name should be picked from the Univ main campus.</li><li style=\"list-style:none\">For eg:- Nottingham</li></ul>",
                                                    "univ_contperson"   =>  "<ul><li>Put name of person who is in the admissions office of the Univ.</li><li style=\"list-style:none\">For eg:- Ben Jones (MIT, USA)</li></ul>",
                                                    "univ_deptname" =>  "<ol><li>Every Univ would have multiple Faculties/Departments, for eg:- Faculty of Arts&Media, Faculty of Business etc. Such names need to be mentioned.</li><li>It might be possible that there are departments within a Faculty. For eg:- under Faculty of Business & Management, there are Departments of Accounts, Economics, International Business etc. In such cases we need to put the main Faculty name (upper layer), i.e., Faculty of Business & Management, in this case</li></ol><ul><li style=\"list-style:none\"><b>For eg:-</b></li><li>Faculty of Development and Society</li><li>Faculty of Arts, Computing, Engineering and Sciences</li><li>Faculty of Health and Wellbeing</li><li>Sheffield Business School</li></ul>",
                                                    "univ_deptwebsite"  =>  "<ul><li style=\"list-style:none\">URL for each of the Faculty/Department website.</li><li style=\"list-style:none\"><b>For eg:-</b></li><li>http://www.shu.ac.uk/faculties/ds/</li><li>http://www.shu.ac.uk/faculties/aces/</li><li>http://www.shu.ac.uk/faculties/hwb/</li><li>http://www.shu.ac.uk/sbs/</li></ul>",
                                                    "univ_accomodationdesc" =>  "<ol><li>Should ideally contain around 3-4 points and can even be generic. For eg:- in-campus accommodation available at affordable rates</li><li>If accommodation facilty is available for spouse/guardian/children, make sure the same is mentioned</li><li>Proximity of residence to the college may also be mentioned. For eg:- residence facility available within 1 mile of the college</li></ol><ul><li style=\"list-style:none\"><b>For eg:-</b></li><li>Univ provides on-campus accommodation facility for all students</li><li>Accommodation facility also provided for upto 2 family member</li><li>All accommodation facility within 300 metres of the main study centre</li></ul>",
                                                    "univ_accomodationlink" =>  "<ul><li>URL containing University accommodation details. For eg:- www.ualberta.ca/residences</li></ul>",
                                                    "univ_livexpense"   =>  "<ul><li>Summation of accommodation and meals (per annum)</li><li>Expenses should only be mentioned if accommodation is <b>in-campus</b></li><li>In case the expenses are given on a daily/weekly/monthly basis, then mention the same amount and in the comment box, write, daily/weekly/monthly</li><li>Univ would generally have various accommodation plans, pick the one which is cheapest (in-campus)</li><li style=\"list-style:none\">For eg:- $ 4,368</li></ul>",
                                                    "univ_livexpensedesc" => "<ol><li style=\"list-style:lower-alpha\">Mention the items that are included in the cost. For eg:- Accommodation <b>OR</b> Accommodation & Meal</li><li style=\"list-style:lower-alpha\">In case the rates given on the website is daily/weekly/monthly, what calculation was done to arrive at a per annum figure</li></ol>",
                                                    "univ_livexpenselink"   =>  "<ul><li>Put URL of the page where info on living expenses is mentioned</li></ul>",
                                                    "univ_brochurelink" =>  "<ol><li>Make sure the brochure is at the Univ level</li><li>Can contain details of facilities in campus, culture, details of student/faculty background, info of all courses available etc. The brochure should not eb specific to any 1 faculty/department/course</li><li>Avoid putting flyers of just 2-3 pages of content. The same can be done only when nothing else is available/relevant</li><li style=\"list-style:none\">For eg:- http://www.cdu.edu.au/sites/default/files/2014-international-prospectus.pdf</li></ol>",
                                                    "univ_campusname"   =>  "<ul><li style=\"list-style:none\">Big Universities would have campuses in multiple locations, details of each one of them would necessarily be available on the Univ website.</li><li style=\"list-style:none\"><b>For eg:-</b></li><li>Alice Springs</li><li>Casuarina</li><li>Jabiru</li><li>Katherine</li><li>CDU Melbourne</li></ul>",
                                                    "univ_campuslink"   =>  "<ol><li>Source from Univ website</li><li>Might be possible campuses don't have dedicated websites, in such a case, put the URL where names and other details of each campus is available. Else, individual URLs need to be mentioned for different campuses.</li></ol><ul><li style=\"list-style:none\"><b>For eg:-</b></li><li>http://www.cdu.edu.au/campuses-centres/alice-springs-campus</li><li>http://www.cdu.edu.au/campuses-centres/casuarina-campus</li><li>http://www.cdu.edu.au/campuses-centres/jabiru-centre</li><li>http://www.cdu.edu.au/campuses-centres/katherine-campus</li><li>http://www.cdu.edu.au/international/melbourne</li></ul>",
                                                    "univ_campusaddress"    =>  "<ul><li style=\"list-style:none\">Source from Univ website.</li><li style=\"list-style:none\"><b>For eg:-</b></li><li>Grevillea Drive, Alice Springs</li><li>Ellengowan Drive, Casuarina</li><li>PO Box 121, Jabiru NT 0886</li><li>PMB 155, Katherine NT 0852</li><li>CDU Melbourne Clinical Practice Suite, Level 2, 104 Franklin Street, Melbourne, Vic 3000</li></ul>",
                                                    "univ_indianconslink"   =>  "<ul><li>Many big Universities have their representatives for different countries. Mention the URL where the name and contact details pf Indian representative is available. For eg:- http://www.cdu.edu.au/international/future-students/agent#india</li></ul>",
                                                    "univ_abroadstudentpage"    =>  "<ul><li>Most Univ websites have a section dedicated for International students. Mention that URL. For eg:- http://www.derby.ac.uk/international/</li></ul>",
                                                    "univ_comments" => "<ul><li>Mention the date, sections where the changes have been made and name of the persons who made changes.</li><li>For the first time the user can mention that the entire listing was created.</li></ul>"
                                                      ),

                            "department_tooltips" => array(
                                                    "dept_parentUniv"   =>  "<ul><li>Name of the University/Institue to which the Department/Faculty belongs. For eg:- Anglia Ruskin University</li></ul>",
                                                    "dept_website"  =>  "<ol><li>URL of the department website.</li><li>In case , if there are departments/schools within a Faculty, in that case the choice has to be made as per the points below:-</li><li style=\"list-style:none\"><ol type=\"a\"><li>Ideal choice would be to pick the layer closest to the Course. For eg:- we have Faculty of    Business, under that, Dept. of Economics and Accounts, under which there is the couse MSc. Economics, then our choice would be to create this page for Dept. of Economics and Accounts</li><li>We can create the layer above that (in this case the Faculty) only if sufficient information is not available at the Dept./School level to fill this page</li></ol></li><li style=\"list-style:none\">For eg:- http://www.cdu.edu.au/business</li></ol>",
                                                    "dept_accreditation" =>  "<ol><li>Accreditation-It is an act of granting credit/recognition to maintain suitable standards</li><li>The phrase 'accredited by' should be mentioned and not anything else</li><li>Make sure accreditation is at Department/School/Faculty level for which this page is being created. Before the phrase 'accredited to', the name of the course/department/Univ will be mentioned.</li><li style=\"list-style:none\">For eg:- AACSB, EQUIS, AMBA etc.</li></ol>",
                                                    "dept_school"    =>  "<ul><li>Name of the Faculty/Department/School for which this page is being created. Make sure the exact same name is copied as mentioned on the website.</li><li style=\"list-style:none\">For eg:- Lord Ashcroft International Business School</li></ul>",
                                                    "dept_schoolacrnym"  =>  "<ul><li>Need not necessarily be a short form of every Dept. name. It should be a known acronym and should be sourced from the Dept. website. For eg:- SEMAL (for School of Enterprise, Managament and Leadership, University of Chichester).</li></ul>",
                                                    "dept_schoolDesc"   =>  "<ol><li>Crisp and clear</li><li>Avoid long sentence formations</li><li>Avoid copying exact content from website</li><li>Focus more on the USPs (For eg:- ranked 3rd in UK by Times Education, or, 40% international student population in Univ, accredited by AACSB)</li><li>Avoid generic statements like, interactive sessions, latest teaching methodologies, world class faculty/staff. Such statements maybe used only when we can't find appropriate USPs.</li><li>Mention in what areas study/research opportunities are provided (For eg:- Bournemouth University's Business school provides opportunities for study, executive education, consultancy and research in the areas of accounting, finance & economics, business & management, and law)</li><li>Should ideally be around 4-5 points</li><li>Should be in bullet points</li><li>Should be in 3rd person (For eg:- Kingston University's business school is the only business school in the world with EPAS accreditation at Bachelors, Masters and Doctoral level)</li><li>Any other kind of impressive stats. (For eg:- 94% of graduates either get into full time employment or commence their PG course within 3 months of course completion)</li></ol>",
                                                    "dept_contpersname" =>  "<ul><li style=\"list-style:none\">The name should be picked as per the below order:-</li><li>SPOC for Intl students</li><li>Admission Office</li><li>Dean/Associate Dean</li><li>If none of the above is available, then any administrative staff</li><li style=\"list-style:none\">For eg:- Susan Barnes (Bournemouth University)</li></ul>",
                                                    "dept_contemail"    =>  "<ul><li style=\"list-style:none\">Which e-mail id is to be considered? Put the mail id as per the below order of importance (make sure the below mail ids are at the Dept. level and do not replicate the mail ID already mentioned in the Univ page):-</li><li>Dedicated mail id for international students (in case a seperate one is provided at the Dept. level)</li><li>General enquries</li><li>Admissions</li><li>Dean</li><li style=\"list-style:none\">For eg:- catherine.foottit@anglia.ac.uk (Dean, Anglia Ruskin University)</li></ul>",
                                                    "dept_contphn"  =>  "<ul><li>As per the order mentioned in Contact email.</li><li style=\"list-style:none\">For eg:- 44 (0) 1245 493131</li></ul>",
                                                    "dept_linkfaculty"  =>  "<ul><li>URL of page where staff (teaching) information is provided.</li><li style=\"list-style:none\">For eg:- Name, designation, the person's role, short description about his/her academic/career credentials</li></ul>",
                                                    "dept_linkalumini"  =>  "<ul><li>URL of page where any sort Department alumni information is available.</li><li style=\"list-style:none\">For eg:- http://www.anglia.ac.uk/ruskin<br/>/en/home/faculties/aibs/alumni.html</li></ul>",
                                                    "dept_linkfb"   =>  "<ul><li>Click on the FB link on website and copy URL. In case link is not available, you can try searching for the same by logging in to your FB a/c.</li><li style=\"list-style:none\">For eg:- https://www.facebook.com/laibs1</li></ul>",
                                                    "dept_comments" => "<ul><li>Mention the date, sections where the changes have been made and name of the persons who made changes.</li><li>For the first time the user can mention that the entire listing was created.</li></ul>"
                                                      ),

                            "course_tooltips" => array(
                                                "cour_parentUniv"   =>  "<ul><li>Name of the University/Institue to which the Course belongs. For eg:- Anglia Ruskin University</li></ul>",
                                                "cour_parentDept"   =>  "<ul><li>Name of the Department/School to which the Course belongs (Copy the exact name from the website). For eg:- Lord Ashcroft International Business School</li></ul>",
                                                "cour_type"   =>  "<ul><li>It can be either a Degree, Diploma or Certification/Certificate. For eg:- Degree</li></ul>",
                                                //"cour_level"   =>  "<ul><li>It can be either a Bachelors, Masters or Doctoral (don't always go by the course name to determine the same, we also need to check other details like course duration & eligibility). For eg:- there maybe a course MEng in UK which is of 4 years and the candidate needs to have secured atleast 70% in class XIIth. In this case it will be Bachelors</li><li>In case of Certificate/Diploma, in case the student can appear for the same before UG then put it as Bachelors. In case the student can appear for the same before or after PG then put it as Masters</li><li>There maybe certain certifications which can be undertaken either before or after UG, like, Six Sigma. In such cases put it as Bachelors</li></ul>",
                                                "cour_level"   =>  "<ul><li>In order to choose the correct level, don't always go by the course name to determine the same, we also need to check other details like course duration, eligibility & the category (on Univ website) to which the course belongs. For eg:- there maybe a course MEng in UK which is of 4 years and the candidate needs to have secured atleast 70% in class XIIth and put under UG courses</li></ul>",
                                                "cour_exName"   =>  "<ul><li>Exact name of the course as appearing on the course page of the Univ website. For eg:- BSc (Honours) Computer Science</li></ul>",
                                                "cour_affDetails"   =>  "<ul><li>Affiliation-To adopt/accept as a member, subordinate associate or branch</li><li>Rely only on Univ website for the same</li><li>When putting affiliation details, ensure the word 'affiliated to' is mentioned. Do Not fill this column if the phrases read 'associated with', 'approved by' etc. Such details may be put in Course Description if it seems important</li><li>Make sure affiliations are at the Course level (before the phrase affiliated to', the name of the course/department/Univ will be mentioned)</li></ul>",
                                                "cour_accrDetails"   =>  "<ul><li>Accreditation-It is an act of granting credit/recognition to maintain suitable standards</li><li>Same as above, the phrase 'accredited by' should be mentioned and not anything else</li><li>Make sure accreditation is at the Course level......before the phrase 'accredited to', the name of the course/department/Univ will be mentioned. For eg:- Masters in Business Administration in University of Alberta is accredited by EQUIS. Herein, it's a Course level accreditation</li></ul>",
                                                "cour_webLink"   =>  "<ul><li>Put URL of the course description page. For eg:- http://www.dmu.ac.uk/study/courses/undergraduate-courses/graphic-design-degree/graphic-design-ba.aspx</li></ul>",
                                                "cour_duration"   =>  "<ol><li>Info can be sourced from course description page, prospectus/brochure etc. from the Univ website</li><li>Course duration info should only be for full-time. For eg:- 3 years (for BSc H Comp Sc.)</li><li>We can also mention duration bracket if such an option is available. For eg:- in many Universities, for full-time MBA, they provide a duration bracket of 12-15 months.</li></ol>",
                                                "cour_startDate"   =>  "<ul><li>Mention all months when student intakes take place. For eg:- Jan, Sep</li></ul>",
                                                "cour_durationLink"   =>  "<ul><li>Put URL of the page from where info on Course Duration has been sourced. For eg:- http://www.edgehill.ac.uk/study/courses/master-of-business-administration</li></ul>",
                                                "cour_description"   =>  "<ul><li>Should be clear, precise and brief</li><li>Mention the USP of the course (For eg:- Melbourne Business School's MBA has been ranked No.1 in the Asia Pacific Region by <I>The Economist</I> and has been ranked No.1 in Australia by <I>AFR Boss Magazine</I>).</li><li>Mention the kind of skills the course aims to enhance (For eg:- The program is designed to address managerial issues that are relevant to doing business across national boundaries, while incorporating the necessary basics of business)</li><li>Mention if the course is approved by any major body/organization</li><li>A single line mention of the kind of careers the person can get into after completion of the course, or, the kind of further studies the students can take up (For eg:- The program position students for successful careers in Intl Trade, Intl Business, Project Management, Corporate Communications etc.)</li><li>Should be in bullet points</li><li>Should be in third person</li></ul>",
                                                "cour_deadLineLink"   =>  "<ul><li>Mention URL of the page which mentions last date/month of filing application for the course. For eg:- http://www1.aston.ac.uk/aston-business-school/programmes/aston-mba/apply/faqs/</li></ul>",
                                                "cour_admWebLink"   =>  "<ul><li>Mention URL of the page showing entry requirements/eligibility. For eg:- http://www1.aston.ac.uk/aston-business-school/programmes/aston-mba/full-time-mba/</li><li>Also, mention URL of the page for filing online application. For eg:- http://www1.aston.ac.uk/aston-business-school/programmes/aston-mba/apply/</li></ul>",
                                                "cour_xamReq"   =>  "<ul><li>Mention the kind of qualifications the student needs to possess in order to be eligible for the said course.</li><li style=\"list-style:none\">For eg:-</li><li>Applicant must have a degree recognised by Aston University, or</li><li>A degree-level professional qualification</li><li style=\"list-style:none\">Plus</li><li>An official academic transcript of grades (in English)</li></ul>",
                                                "cour_xamReqtoefl"   =>  "<ul><li>Mention 'Yes' if required, else 'No' (Mention 'Any one ELP' in the comments section in case the student doesn't need to appear for all the ELP tests). If mentioned recommended but not mandatory, then put 'No' and mention 'Recommended' in the 'Comments' section.</li></ul>",
                                                "cour_xamReqielts"   =>  "<ul><li>Mention 'Yes' if required, else 'No' (Mention 'Any one ELP' in the comments section in case the student doesn't need to appear for all the ELP tests). If mentioned recommended but not mandatory, then put 'No' and mention 'Recommended' in the 'Comments' section.</li></ul>",
                                                "cour_xamReqpte"   =>  "<ul><li>Mention 'Yes' if required, else 'No' (Mention 'Any one ELP' in the comments section in case the student doesn't need to appear for all the ELP tests). If mentioned recommended but not mandatory, then put 'No' and mention 'Recommended' in the 'Comments' section.</li></ul>",
                                                "cour_xamReqgre"   =>  "<ul><li>Mention 'Yes' if required, else 'No'. If mentioned recommended but not mandatory, then put 'No' and mention 'Recommended' in the 'Comments' section.</li></ul>",
                                                "cour_xamReqgmat"   =>  "<ul><li>Mention 'Yes' if required, else 'No'. If mentioned recommended but not mandatory, then put 'No' and mention 'Recommended' in the 'Comments' section.</li></ul>",
                                                "cour_xamReqsat"   =>  "<ul><li>Mention 'Yes' if required, else 'No'. If mentioned recommended but not mandatory, then put 'No' and mention 'Recommended' in the 'Comments' section.</li></ul>",
                                                "cour_xamReqcael"   =>  "<ul><li>Mention 'Yes' if required, else 'No' (Mention 'Any one ELP' in the comments section in case the student doesn't need to appear for all the ELP tests). If mentioned recommended but not mandatory, then put 'No' and mention 'Recommended' in the 'Comments' section.</li></ul>",
                                                "cour_xamReqmelab"   =>  "<ul><li>Mention 'Yes' if required, else 'No' (Mention 'Any one ELP' in the comments section in case the student doesn't need to appear for all the ELP tests). If mentioned recommended but not mandatory, then put 'No' and mention 'Recommended' in the 'Comments' section.</li></ul>",
                                                "cour_xamReqCamCert"   =>  "<ul><li>Mention 'Yes' if required, else 'No' (Mention 'Any one ELP' in the comments section in case the student doesn't need to appear for all the ELP tests). If mentioned recommended but not mandatory, then put 'No' and mention 'Recommended' in the 'Comments' section.</li></ul>",
                                                "cour_xamReqOther"   =>  "<ul><li>Mention name of the test on left and mention 'Yes' if required, else 'No' (Mention 'Any one ELP' in the comments section in case the student doesn't need to appear for all the ELP tests). If mentioned recommended but not mandatory, then put 'No' and mention 'Recommended' in the 'Comments' section.</li></ul>",
                                                "cour_cutoffs"   =>  "<ul><li>Put URL of the page where cut-off scores for various ELPs and/or entrance exams (like, GRE, GMAT etc) and/or XIIth percentage and GPA/Grad percentage is mentioned. For eg:- http://www1.aston.ac.uk/international-students/admissions-advice/english-language-requirements/</li></ul>",
                                                "cour_cftoefl"   =>  "<ul><li>Mention TOEFL iBT cut-off and in the comments section it needs to be mentioned if there are cut-offs for sub-sections also. For eg:- TOEFL score of 85 'Comments':- minimum of 24 in Writing. For more info on TOEFL, refer to the website:- http://www.ets.org/toefl</li></ul>",
                                                "cour_cfielts"   =>  "<ul><li>Mention IELTS cut-off and in the comments section it needs to be mentioned if there are cut-offs for sub-sections also. For eg:- IELTS score 6.5 'Comments':- No sub score less than 6.0. For more info on IELTS, refer to website:- http://www.ielts.org/</li></ul>",
                                                "cour_cfpte"   =>  "<ul><li>Mention PTE cut-off and in the comments section it needs to be mentioned if there are cut-offs for sub-sections also. For eg:-PTE score of 65 'Comments':- with Communicative Skills score in writing above the Overall Score. For more info on PTE, refer to website:- http://www.pearsonpte.com/Pages/Home.aspx</li></ul>",
                                                "cour_cfgre"   =>  "<ul><li>GRE score is recommended in most Universities in case the student is applying for a PG course. A good GRE score neither confirms entry into the said institution nor a bad score disqualify the student with certainty. However, it acts as one of the elements in the selction process. GRE scores may either be mentioned seperately for each section or a combined one. For eg:- <li>Verbal-158; Quantitative-159; Writing-5.3</li><li>317 (Combined)</li><li>For more info on GRE, refer to website:- http://www.ets.org/gre</li></ul>",
                                                "cour_cfgmat"   =>  "<ul><li>GMAT score are recommended in most Universities in case the student is applying for the MBA course. A good GMAT score neither confirms entry into the said institution nor a bad score disqualify the student with certainty. However, it acts as one of the elements in the selction process. Mention the recommended GMAT score with subscores in the 'Comments' section. For eg:- 650 (also mention sub-scores in comments sections if applicable)</li><li>For more info on GMAT, refer to website:- http://www.mba.com/</li></ul>",
                                                "cour_cfsat"   =>  "<ul><li>SAT score is recommended in many Universities in case the student is applying for a UG course. A good SAT score neither confirms entry into the said institution nor a bad score disqualify the student with certainty. However, it acts as one of the elements in the selction process. SAT scores may either be mentioned seperately or combined. For eg:-</li><li>Critical Readin:- 500; Maths:- 550; Writing:- 550</li><li>1800 (Combined)</li><li>For more info on SAT, refer to website:- http://sat.collegeboard.org/home</li></ul>",
                                                "cour_cfcael"   =>  "<ul><li>Mention CAEL cut-off and in the comments section it needs to be mentioned if there are cut-offs for sub-sections also. For eg:-Overall minimum of 60, 'Comments':- with at least 60 on each subtest</li></ul>",
                                                "cour_cfmelab"   =>  "<ul><li>Mention MELAB cut-off and in the comments section it needs to be mentioned if there are cut-offs for sub-sections also. For eg:-</li></ul>",
                                                "cour_cfCamCert"   =>  "<ul><li>Mention CAE cut-off. For eg:- C+. For more info on Cambridge Certificate of Advanced English, refer to website:- www.cambridgeenglish.org</li></ul>",
                                                "cour_cfOthers"   =>  "<ul><li>Mention the name of the test on the left and the cut-off in this cell.</li></ul>",
                                                "cour_cfClass12"   =>  "<ul><li>In case of a UG course the University would generally have a minimum desirable Class XIIth percentage. In certain cases the Univ would mention different cut-offs for CBSE and ICSE Boards. For eg:- Minimum 70% in Class XIIth</li><li>There might be a possibility that the Univ doesn't have a fixed criteria. In such a case, it may just be mentioned 'NA'</li></ul>",
                                                "cour_cfBachGPA"   =>  "<ul><li>For entry to PG courses the Universities demand a UG degree with a certain minimum GPA. For eg:- 2.9</li><li>In certain cases the Universities mention the Avg percentage based on the Univ the student studied in. For eg:- 65% (Delhi University)</li><li>It maybe possible there isn't any such eligibility criteria. In such cases it maybe mentioned 'NA'</li></ul>",
                                                "cour_classProfile"   =>  "<ul><li>Put URL of the page containing info on class profile (profile of current students) for the said course. This section is usually available with the name 'Class Profile'. For eg:- http://www.bond.edu.au/study-at-bond/postgraduate-degrees/list/master-of-business-administration/class-profile/index.htm?fos=Business&cl=Your%20Degree</li></ul>",
                                                "cour_cpAvgWorkExp"   =>  "<ul><li>Average no.of years of work experience of current students in that course. For eg:- 8 years</li></ul>",
                                                "cour_cpAvgBachGPA"   =>  "<ul><li>In case of a PG course, average GPA of students who are currently studying in that course. For eg:- 2.9</li><li>In case the same is not available, it can be mentioned as 'NA'</li></ul>",
                                                "cour_cpAvgClass12"   =>  "<ul><li>In case of a UG course, average class XIIth percentage of students currently studying in that course. For eg:- 65%</li><li>In case the same is not available, it can be mentioned as 'NA'</li></ul>",
                                                "cour_cpAvgGmat"   =>  "<ul><li>Average GMAT score of students who are currently studying in that course. For eg:- 680</li><li>In case the same is not available, it can be mentioned as 'NA'</li></ul>",
                                                "cour_cpAvgAge"   =>  "<ul><li>Average age of students currently studying in that course. For eg:- 8 years</li></ul>",
                                                "cour_cpAvgInterStudent"   =>  "<ul><li>Percentage of international students currently studying in that course. For eg:- 65%.</li></ul>",
                                                "cour_feeDetail"   =>  "<ul><li>Put URL of the page containing details of course fees. For eg:- http://www2.le.ac.uk/study/postgrad/taught-campus/business/mba</li></ul>",
                                                "cour_tutionFee"   =>  "<ul><li>Tution fees to be mentioned per annum</li><li>In case other components are mentioned seperately, like, library fees, recreation fees etc. the same is not to be included in Tution fees</li><li>Ensure the tution fees mentioned is mentioned with the right currency</li><li style=\"list-style:none\">For eg:- \A318,320</li></ul>",
                                                "cour_linkCourse"   =>  "<ul><li>Mention URL of the page containing scholarship details only specific to the said course</li></ul>",
                                                "cour_linkDept"   =>  "<ul><li>Mention URL of the page containing scholarship details for any course within the concerned faculty (faculty under which the present course falls)</li></ul>",
                                                "cour_linkUniv"   =>  "<ul><li>Mention URL of the page containing scholarship details available to students of this Univ irrespective of what course the student is studying or to what faculty it belongs</li></ul>",
                                                "cour_careerLink"   =>  "<ul><li>Put URL of the page containing details related to any kind of assistance provided by the Univ in the student's job search. For eg:- organizing job fairs, help in preparing CV, honing interview skills etc. Placements stats, companies who recently hired candidates, avg salary etc. <b>do not</b> come under Career Services.</li><li style=\"list-style:none\">For eg:- http://www.lboro.ac.uk/service/careers/</li></ul>",
                                                "cour_percentEmpl"   =>  "<ul><li>Percentage students who get employed after completion of the course. For eg:- 86% students get employed within 3 months of course completion (MBA from University of Alberta)</li></ul>",
                                                "cour_avgSalary"   =>  "<ul><li>Average salary earned by students after completion of the course. For eg:- $75,500 per annum (MBA pass outs from University of Alberta)</li></ul>",
                                                "cour_popularSector"   =>  "<ul><li>Common sectors/profiles in which students get employed after completion of the course. For eg:- Sectors:- IT, Consultancy, Banking etc. Profiles:- Project Management, HR, consultant, etc.</li></ul>",
                                                "cour_internship"   =>  "<ol><li>Mention if there is internship provision for students of this course</li><li>If yes, at what stage it begins</li><li>If there is any sort of eligibility required for the same</li><li>Duration of internship</li><li>Mention in bullets</li><li>Mention as 3rd person</li></ol><ul><li style=\"list-style:none\">For eg:- </li><li>Students are provided with the opportunity of a unique 12 weeks industry internship</li><li>Internship usually takes place in the 2nd year of study</li><li>Any student with a GPA of 2.6 or above is eligible to apply</li></ul>",
                                                "cour_internshipLink"   =>  "<ul><li>Put URL of the page where such internship details are available. <br/>For eg:- http://www.anglia.ac.uk/ruskin/en/home<br/>/microsites/internships.html</li></ul>",
                                                "cour_recruitCompany"   =>  "<ul><li>Put names of companies where students usually get hired. For eg:- IBM, Microsoft, Syntel etc.</li></ul>",
                                                "cour_facultyInfo"   =>  "<ul><li>Put URL of the page where faculty info like, name, designation, contact no., e-mail id etc. is provided. For eg:- http://www.cdu.edu.au/business/staff-contacts</li></ul>",
                                                "cour_aluminInfo"   =>  "<ul><li>Put URL of the page where alumni info is provided, like, their names, present companies they are working in, alumni groups, alu,ni publications & events etc. For eg:- http://www.cdu.edu.au/mace/alumni</li></ul>",
                                                "cour_brochure"   =>  "<ul><li>Out URL of the page containing course brochure</li><li>It maybe possible that the available brochure contains info regarding various courses. The same can be put.</li><li>In case brochure link/file is not available, an e-mail has to be sent to the Univ requesting for the same.</li><li>On certain Univ websites, it is mandatory to fill an online form for request of brochure. The same needs to be done and following it up by sending an e-mail to the Univ.</li><li style=\"list-style:none\">For eg:- http://www.cdu.edu.au/sites/default/files/2014-international-prospectus.pdf</li></ul>",
                                                "cour_faq"   =>  "<ul><li>Put URL of the page containing course FAQs. It may also be possible that FAQs maybe for multiple courses or at the department/faculty level, or at the Univ level. The same can also be put. For eg:- https://www.cdu.edu.au/international/future-students/faqs</li></ul>",
                                                "cour_comments" => "<ul><li>Mention the date, sections where the changes have been made and name of the persons who made changes.</li><li>For the first time the user can mention that the entire listing was created.</li></ul>"
                                                  ),
                            
                            "consultant_tooltips" => array(
                                                    "cons_logo"     =>  "<ul>
                                                                            <li>Preferably in png or jpg image format. Shouldn't be too small in size otherwise it won't look good.</li>
                                                                        </ul>",
                                                                    
                                                    "cons_name"     =>  "<ul>
                                                                            <li>Should be the exact name as mentioned on the consultant website.</li>
                                                                        </ul>",
                                                                    
                                                    "cons_yoe"     =>  "<ul>
                                                                            <li>Year in which consultant was established.</li>
                                                                        </ul>",
                                                                    
                                                    "cons_desc"     =>  "<ol>
                                                                            <li> A unique selling point of the consultant. Different consultants will have different selling points.</li>
                                                                            <ul><li style=\"list-style:none\">For eg:- larger ones will mention size of operation, smaller ones will mention dedicated attention and experienced counselors, some will mention specilization in sending students to particular course or country, some will mention sending students on scholarships etc.</li></ul>
                                                                            <li>No.of students sent abroad annually.</li>
                                                                            <li>Any country or course where the consultant specializes in sending students.</li>
                                                                            <li>Mention names of top Universities where students have been sent.</li>
                                                                        </ol>",
                                                                        
                                                    "cons_linkedin"  =>  "<ul>
                                                                            <li>Link of the Linkedin Page of the consultant.</li>
                                                                        </ul>",
                                                                        
                                                    "cons_fb"       =>  "<ul>
                                                                            <li>Link of the Facebook Page of the consultant</li>
                                                                        </ul>",
                                                                        
                                                    "cons_photos"   =>  "<ol>
                                                                            <li> Atleast 1 picture is mandatory.</li>
                                                                            <li>Appropriate pictures of building, campus, facility, interiors, reception area etc.</li>
                                                                            <li>Ensure the pictures give a decent impression and avoid pictures that either depict small size of the facility, not very well maintained interiors etc.</li>
                                                                        </ol>
                                                                        <ul>
                                                                            <li>Desirable:-</li>
                                                                            <li>Appropriate pictures of:-</li>
                                                                        </ul>
                                                                        <ol>
                                                                            <li> Building.</li>
                                                                            <li> Campus.</li>
                                                                            <li> Interiors/Reception area/Facility.</li>
                                                                        </ol>
                                                                        <ul>
                                                                            <li>Not Desirable:-</li>
                                                                        </ul>
                                                                        <ol>
                                                                            <li> Blurred images.</li>
                                                                            <li> Small size of the facility.</li>
                                                                            <li> Not well maintained interiors.</li>
                                                                        </ol>",
                                                                        
                                                    "cons_website"  =>  "<ul>
                                                                            <li>Mention consultant website.</li>
                                                                        </ul>",
                                                    
                                                    "cons_paid"     =>  "<ul>
                                                                            <li>Many consultants offer paid services for their services to apply to universities which are not tied up with them. It could also vary by country .</li>
                                                                            <li style=\"list-style:none\">The Chopras will not charge student any fees for applying to UK universities but for US, it will charge around 40-50k because it has no direct tie-ups there. If any paid packages are available, please mention Yes (so for Chopra's it will be a Yes). If the consultant has no paid package (only free), then mention No.</li>
                                                                        </ul>",
                                                                        
                                                    "cons_paidDesc" =>  "<ul>
                                                                            <li>If the consultant is offering paid services, then description of those paid services is mandatory.</li>
                                                                        </ul>
                                                                        <ol>
                                                                            <li> Mention the kind of paid services that the consultant is offering.</li>
                                                                            <li> Clearly mention fees/charges (country wise, course wise as the consultant feels appropriate). In case exact charges vary, mention the range (e.g. application packages for 5 universities vary between 30-50k).</li>
                                                                            <li>  Mention if it is including or excluding taxes.</li>
                                                                            <li>   Clearly mention the services where no money/fees is charged so that student is clear on exact nature of services.</li>
                                                                        </ol>",
                                                                        
                                            "cons_offerTestPrep" =>     "<ul>
                                                                            <li>Some consultants offer test preparation services for different exams (e.g. GRE, GMAT, IELTS, TOEFL). This field is to indicate whether the consultant offers Test Prep services.</li>
                                                                        </ul>",
                                            
                                            "cons_testServices" =>     "<ul>
                                                                            <li>IF the consultant is offering test preparation services, then description of those paid services is mandatory. .</li>
                                                                        </ul>
                                                                        <ol>
                                                                            <li> Mention the kind of test preparation services it offers.</li>
                                                                            <li> Exams for which the it provides the coaching (e.g. GRE, GMAT etc)</li>
                                                                            <li> Mention if there is classroom coaching or online or some other medium. </li>
                                                                            <li> Mention the duration of classes (1 month, 3 months).</li>
                                                                            <li>  Clearly mention the exact price of test prepration services. IF exact fees varies, then mention the range (e.g. GMAT classroom preparation packages vary between 15-25k for a 3 month session).</li>
                                                                        </ol>",
                                                                         
                                                    "cons_coe"      =>  "<ul>
                                                                            <li>Name of the CEO/MD.</li>
                                                                        </ul>",
                                                                        
                                                    "cons_coeDesc"  =>  "<ol>
                                                                            <li> Mention the pedigree/education qualification or story of CEO.</li>
                                                                            <li> Is he/she educated from abroad?</li>
                                                                            <li> For how long has he/she been in this business.</li>
                                                                            <li> It should all be in bullet points.</li>
                                                                        </ol>
                                                                        <ul>
                                                                            <li>For many smaller/medium scale consultants, students go to them because they find that the owner is himself/herself studied abroad and can provide good guidance. Sometimes they have studied from same university where a student wants to go and hence it becomes a big selling point.</li>
                                                                        </ul>",
                                                                        
                                                    "cons_employees" => "<ul>
                                                                            <li>Choose one of the options below:-</li>
                                                                        </ul>
                                                                        <ol>
                                                                            <li>1-10</li>
                                                                            <li>10-20</li>
                                                                            <li>20-50</li>
                                                                            <li>50-100</li>
                                                                            <li>100-200</li>
                                                                            <li>200-500</li>
                                                                            <li>500 and above</li>
                                                                        </ol>
                                                                        <ul>
                                                                            <li>This field is taken to indicate the size of the consultant. Otherwise a normal student would not know how to distinguish consultant A from B. Bigger and smaller consultants both have their unique selling points. Some students prefer a larger name who is more trusted and has helped thousands of students. Other students prefer smaller consultants because of the personal attention they can provide and better quality counselors.</li>
                                                                        </ul>"
                                                                        
                                                      ),
                            
                            "consultant_univ_mapping_tooltips" => array(
                                
                                                    "cons_name"    =>  "<ul>
                                                                            <li>Name of the consultant.</li>
                                                                        </ul>",
                                                    
                                                    "univ_country"    =>  "<ul>
                                                                            <li>Country of the university.</li>
                                                                        </ul>",
                                                    
                                                    "univ_name"    =>  "<ul>
                                                                            <li>Name of the university.</li>
                                                                        </ul>",
                                                    
                                                    "univ_repre"    =>  "<ul>
                                                                            <li>It could be either a Yes or No.</li>
                                                                            <li>Indicates if the consultant is an official representative of the university.</li>
                                                                        </ul>",
                                                                    
                                                    "valid_from"    =>  "<ol>
                                                                            <li> Mandatory field if the consultant is an official representative.</li>
                                                                            <li>Date since when the consultant is an official representative.</li>
                                                                          </ol>",
                                                                    
                                                    "valid_till"     =>  "<ol>
                                                                            <li> Mandatory field if the consultant is an official representative .</li>
                                                                            <li> Mention the exact date till when the cotract is valid.</li>
                                                                            <li> It can be maximum 15 years from the present date.</li>
                                                                            <li>The date cannot be historical.</li>
                                                                        </ol>",
                                                                        
                                                "univ_proof_type"  =>  "<ul>
                                                                            <li>It could be either of the below:-</li>
                                                                        </ul>
                                                                        <ol>
                                                                            <li>Some universities mention the name of official consultants on their website. If that's the case, please use this option: Name on University website.</li>
                                                                            <li>If name on website is not available, we would require a mail from the University representative/admission office confirming their alliance. This mail will be forwarded to us by the sales tream.</li>
                                                                            <ul>
                                                                                <li> It should read as below:-</li>
                                                                                <li>This is to confirm that consultant ABC is an official consultant of our university from India and has been working with us since Feb 2010. Their contract with us lasts till Jan 2015.</li>
                                                                            </ul>
                                                                        </ol>",
                                                                        
                                              "link_on_univ_site"    =>  "<ul>
                                                                            <li>If 'Name on University website' has been chosen from the above field, then the URL of that page should be mentioned here.</li>
                                                                        </ul>",
                                                                        
                                            "univ_personName"      =>  "<ul>
                                                                            <li>If the consultant has sent us an email from university website, then the name of the University person who has sent the mail should be mentioned.</li>
                                                                        </ul>",
                                                                        
                                            "univ_personEmailDes"   =>  "<ol>
                                                                            <li>Email of the person whose mail has come from university side.</li>
                                                                            <li>Has to be an official mail ID.</li>
                                                                            <li>Designation of the person whose mail has come from the University side.</li>
                                                                        </ol>",
                                                                                                            
                                              "emailApproval"      =>  "<ul>
                                                                            <li>The email that is received from the university admissions office will be uploaded here.</li>
                                                                        </ul>",
                                                "salesPerson"        => "<ul>
                                                                            <li>Full name of the sales person closing the deal.</li>
                                                                        </ul>"
                                                                        
                                                      ),
                            "consultant_studentprofiles_tooltips" => array(
                                
                                                "cons_name"  =>         "<ul>
                                                                            <li>Name of the consultant.</li>
                                                                        </ul>",
                                                "student_name"  =>  "<ul>
                                                                            <li>Name of the Student.</li>
                                                                        </ul>",
                                                                        
                                                    "univ_country"  =>  "<ul>
                                                                            <li>The country to which the University belongs.</li>
                                                                        </ul>",
                                                                    
                                               "univ_admittedTo"    =>  "<ul>
                                                                            <li>Exact and full University name should be mentioned.</li>
                                                                        </ul>",
                                                                    
                                                  "course_name"     =>  "<ul>
                                                                            <li>Exact course name to which the student has been admitted. Free text field for course course.</li>
                                                                        </ul>",
                                                                        
                                                "course_category"   =>  "<ul>
                                                                            <li>To be filled by Content Ops</li>
                                                                        </ul>",
                                                                        
                                              "course_subCategory"  =>  "<ul>
                                                                            <li>To be filled by Content Ops</li>
                                                                        </ul>",
                                                                        
                                               "course_desired"     =>  "<ul>
                                                                            <li>To be filled by Content Ops</li>
                                                                        </ul>",
                                                                        
                                            "scholarship_received"  =>  "<ul>
                                                                            <li>Yes/No depending on whether the student received any scholarship.</li>
                                                                        </ul>",
                                                    
                                            "scholarship_details"   =>  "<ul>
                                                                            <li>Percentage of scholarship received OR Total Amount with currency.</li>
                                                                        </ul>",
                                                                        
                                              "year_admission"      =>  "<ul>
                                                                            <li>Year of intake.</li>
                                                                        </ul>",
                                                                                                   
                                              "study_abroadExam"    =>  "<ul>
                                                                            <li>Exams that the student has appeared for, for the purpose of studying abroad.</li>
                                                                        </ul>",
                                                                                                  
                                        "study_abroadExamScore"     =>  "<ul>
                                                                            <li>Score obtained.</li>
                                                                        </ul>",
                                                                                                 
                                              "residence_city"      =>  "<ul>
                                                                            <li>City from where student originally belongs.</li>
                                                                        </ul>",
                                                                        
                                              "10_percent"          =>  "<ul>
                                                                            <li>Percentage marks the student scored in 10th.</li>
                                                                        </ul>",
                                                                                                                                     
                                              "10_passingYear"      =>  "<ul>
                                                                            <li>4 digits.</li>
                                                                        </ul>",
                                                                        
                                              "12_percent"          =>  "<ul>
                                                                            <li>Percentage marks the student scored in 12th.</li>
                                                                        </ul>",
                                                                                                                                     
                                              "12_passingYear"      =>  "<ul>
                                                                            <li>4 digits.</li>
                                                                        </ul>",
                                                                                                                                     
                                              "graduation_univ"     =>  "<ul>
                                                                            <li>Exact name of the University.</li>
                                              
                                                                        </ul>",
                                              "graduation_gpa"     =>  "<ul>
                                                                            <li>Graduation GPA.</li>
                                                                        </ul>",
                                                                                                                                     
                                           "graduation_college"     =>  "<ol>
                                                                            <li>Exact name of the college.</li>
                                                                            <li> Could be left blank in case the study was undertaken directly at the University level. </li>
                                                                        </ol>
                                                                        <ul>
                                                                            <li>For eg:- doing distance learning courses.</li>
                                                                        </ul>",
                                                                                                                                     
                                        "graduation_collegeLoc"     =>  "<ul>
                                                                            <li>City name where the college was located.</li>
                                                                        </ul>",
                                                                                                                                     
                                           "graduation_percent"     =>  "<ul>
                                                                            <li>Percentage marks the student scored in graduation.</li>
                                                                        </ul>",
                                                                                                                                     
                                        "graduation_passingYear"    =>  "<ul>
                                                                            <li>4 digits.</li>
                                                                        </ul>",
                                                                                                                                     
                                           "graduation_desc"        =>  "<ul>
                                                                            <li>Free text field for capturing additional details (if required).</li>
                                                                        </ul>",
                                                                                                                                     
                                                    "work_exp"      =>  "<ul>
                                                                            <li>Total number of months of work experience.</li>
                                                                        </ul>",
                                                                        
                                                    "last_comp"     =>  "<ul>
                                                                            <li>Last company that the student has worked with.</li>
                                                                        </ul>",
                                                                        
                                                    "work_exp"      =>  "<ul>
                                                                            <li>Total number of months of work experience.</li>
                                                                        </ul>",                                                                                   
                                                                        
                                                    "job_start"     =>  "<ul>
                                                                            <li>Year in which the job started.</li>
                                                                        </ul>",
                                                                        
                                                    "job_end"       =>  "<ul>
                                                                            <li>Year in which the job ended.</li>
                                                                        </ul>",
                                                                        
                                            "work_exp_domain"       =>  "<ul>
                                                                            <li>The profile which the student held.</li>
                                                                        </ul>",                                                                        
                                                                        
                                            "extra_curriculars"      =>  "<ol>
                                                                            <li> In case of social work, exact name of the programme enrolled in should be mentioned.</li>
                                                                            <li> All the various types of activities involved in should be seperated by commas.</li>
                                                                        </ol>",                                                                        
                                                                        
                                                "linkedIn_link"      =>  "<ul>
                                                                            <li>Linkedin profile of student profile.</li>
                                                                        </ul>",                                                                        
                                                                        
                                                    "fb_link"       =>  "<ul>
                                                                            <li>Facebook profile of student profile.</li>
                                                                        </ul>",                                                                        
                                                        
                                            "student_number"        =>  "<ul>
                                                                            <li>10 digit phone number.</li>
                                                                        </ul>",
                                                                        
                                                 "doc_proof"        =>  "<ul>
                                                                            <li>Consultant has to show college offer letter (or I20/CAS etc) + Student Mark sheet details. The details would be uploaded here.</li>
                                                                        </ul>",                                                                        
                                                                        
                                                                        
                                            "student_email"          =>  "<ul>
                                                                            <li>Student email id.</li>
                                                                        </ul>"
                                                      ),
                    
           
                    "consultant_assign_city_tooltips" => array(
                                                    "cons_name"   =>  "<ul>
                                                                            <li>Name of the consultant.</li>
                                                                        </ul>",
                                                                        
                                                    "univ_name"   =>  "<ul>
                                                                            <li>Name of the University.</li>
                                                                        </ul>",
                                                                        
                                                    "assign_city"   =>  "<ul>
                                                                            <li>Name of the region in India where the consultant is located.</li>
                                                                        </ul>",
                                                                    
                                                    "start_date"    =>  "<ul>
                                                                            <li>Start date since when the consultant want to start showing next to university/course listings.</li>
                                                                        </ul>",
                                                                    
                                                     "end_date"     =>  "<ul>
                                                                            <li>End date till when the consultant want to show their name next to University/Course listing.</li>
                                                                        </ul>",
                                                                        
                                                 "sales_rep_name"   =>  "<ul>
                                                                            <li>Full name of the sales person closing the deal.</li>
                                                                        </ul>"     
                                                      ),

                    "consultant_branches/loc_tooltips" => array(
                                                        "cons_name"   =>  "<ul>
                                                                            <li>Name of the consultant.</li>
                                                                        </ul>",
                                                   
                                            "contact_personName"    =>  "<ul>
                                                                            <li>The single point of contact for all student queries from Shiksha.com.</li>
                                                                        </ul>",
                                                                    
                                                       "phoneNo"    =>  "<ul>
                                                                            <li>This is the number to which the incoming calls will be routed. It must be answered otherwise the incoming calls will go waste. You can choose to add another alternate number below..</li>
                                                                        </ul>",
                                                       "phoneNo2"    =>  "<ul>
                                                                            <li>Additional phone no.</li>
                                                                        </ul>",
                                                    
                                                       "displayPRI"    =>  "<ul><li>Phone number should be in the below format:-</li></ul>
                                                                                <ol><li>Area code. For ex:- 011</li>
                                                                                <li>Hyphen</li>
                                                                                <li>First 4 digits of the PRI number</li>
                                                                                <li>Hyphen</li>
                                                                                <li>Last 4 digits of the PRI</li></ol>
                                                                                <ul><li>For ex:- 011-3038-3981</li></ul>
                                                                            ",

                                                        "pincode"    =>  "<ul>
                                                                            <li>City pin code.</li>
                                                                        </ul>",
                                                                                        
                                                                    
                                                     "emailId"      =>  "<ul>
                                                                            <li>Official mail ID for forwarding of all responses.</li>
                                                                            <li>We need a central email to which all responses will be sent for a city branch.</li>
                                                                        </ul>",
                                                                        
                                                      "locality"    =>  "<ul>
                                                                            <li>Area name.</li>
                                                                        </ul>",
                                                                        
                                            "location_address"      =>  "<ul>
                                                                            <li>Complete postal address.</li>
                                                                        </ul>",
                                                                        
                                                          "city"    =>  "<ul>
                                                                            <li>City name.</li>
                                                                        </ul>",
                                                                        
                                                      "latitude"    =>  "<ul>
                                                                            <li>Use google maps to get the same.</li>
                                                                        </ul>",
                                                                        
                                                     "longitude"    =>  "<ul>
                                                                            <li>Use google maps to get the same.</li>
                                                                        </ul>",
                                                                        
                                                    "headOffice"    =>  "<ul>
                                                                            <li>(Yes, No). To indicate if the office branch is the head office.</li>
                                                                        </ul>",
                                                                        
                                                 "default_branch"   =>  "<ul>
                                                                            <li>Indicate if this office is the default branch in case of more than 1 branch in the same city.</li>
                                                                            <li>This is required because all responses of a city will be sent to the default branch and from there can be allocated further e.g. Chopras can have 3 branches in Delhi/NCR, but when we show Chopras as a consultant for a student in Delhi, we can only show 1 branch and this will be default branch. All responses will be sent to this branch office phone and email. IF the student happens to be closer to another branch, then the default branch can redirect him/her to another office. But for the student, it should be a single contactable number.</li>
                                                                        </ul>",
                                                                        
                                                 "contact_hours"    =>  "<ul>
                                                                            <li>Mention the contact hours and days when office is open. It's important for the student to know when should he/she plan a visit depending on office hours.</li>
                                                                        </ul>"
                                                                        
                                                      )
                            );
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

$config['EXAM_BY_TYPE_ENGLISH_OR_NON_ENGLISH'] = array(
  'ENGLISH'=>array(1,2,3),//,7,8,9 removed
  'NON_ENGLISH'=>array(4,5,6)
);

$config['LAST_UPDATED_DATE'] = array(
        'All'=> array('textToShow'=> 'All Dates', 'tables'=>array('followup','candidate','pending')),
        'Next_7' => array('textToShow'=>'Next 7 Days','tables'=>array('candidate')),
        'Tomorrow' => array('textToShow'=>'Tomorrow','tables'=>array('candidate')),
        'Today' => array('textToShow'=>'Today','tables'=>array('candidate')),
        'Last_1'=> array('textToShow'=>'Last 1 Day' ,'tables'=>array('pending')),
        'Last_2'=> array('textToShow'=>'Last 2 Days','tables'=>array('pending')),
        'Last_3'=> array('textToShow'=>'Last 3 Days','tables'=>array('followup','candidate','pending')),
        'Last_7'=> array('textToShow'=>'Last 7 Days','tables'=>array('followup','candidate','pending')),
        'Last_14'=> array('textToShow'=>'Last 14 Days','tables'=>array('followup','candidate')),
        'Last_one_month'=> array('textToShow'=>'Last 30 Days','tables'=>array('followup','candidate','pending')),
        'Older_than_month'=> array('textToShow'=>'Older than 1 month','tables'=>array('followup','candidate','pending'))
);

// Not in use
/*$config['RMC_USER_NOTE_STATUS'] = array(
  1  =>	'No Answer/Busy',
  2  =>	'Not Interested (in touch with consultant)',
  3  =>	'Not Interested (any other reason)',
  4  =>	'Call later (spoke briefly)',
  5  =>	'Not Eligible',
  6  =>	'Interested',
  7  =>	'Wrong Number',
  8  =>	'Already in Shiksha Apply',
  9  =>	'Will get back later (spoke at length)',
  10 =>	'System Generated',
  11 => 'Transferred',
  12 => 'Contacted on Whatsapp',
  13 => 'Looking for Next intake',
  14 => 'Exam booked but not taken'
);*/

$config['RMC_USER_CONTACTED_STATUS'] = array(
    'touched_spoken' => 'Touched & spoken',
    'touched_not_spoken' => 'Touched but not spoken',
    'untouched' => 'Untouched'
);

/*$config['CONTACTED_STATUS_BUCKETS'] = array(
    'touched_spoken' => array(2,3,5,6,7,8,9,13,14),
    'touched_not_spoken' => array(1,4,12),
    'untouched' => array(10,11)
);*/

$config['RMC_CANDIDATE_SHORTLIST'] = array(
    'TERM_SEASON'   =>array('Spring','Summer','Fall','Winter'),
    'REACH_TYPE'      =>array('Blank','Dream','Reach','Safe'),
    'NOTE_CATEGORY'=>array(
                           'Recommended application deadline',
                           'Final application deadline',
                           'Round 1 deadline',
                           'Round 2 deadline',
                           'Round 3 deadline')
);


$config['RMC_USER_DROPOFF_REASON'] = array(
1 =>"Account made by parent or friends and student not reachable",
2 =>"Already applied in universities",
3 =>"Became unresponsive after taking service",
4 =>"Deferred plan of studying abroad",
5 =>"Denied sharing Login details of applications",
6 =>"Dropped plan of studying abroad",
7 =>"Financial constraint",
8 =>"In touch with consultant",
9 =>"Language barrier",
10=>"Looking for options in Non-Focus Country",
11=>"No paid course finalized",
12=>"No response after several reminders and calls",
13=>"Not comfortable signing the contract/online process",
14=>"Not meeting the entry requirements",
15=>"Not sure about studying abroad",
16=>"Reappearing for exam",
17=>"Received admit from Non Shiksha Apply list",
18=>"Student has not taken any exam",
19=>"Student registered with 2 different accounts",
20=>"Want more university options out of Shiksha Apply List",
21=>"Wrong contact details",
22=>"Other reason",
23=>"Non Client Enrollment",
24=>"High profile student for our clients/Don’t have clients for student profile",
25=>"All Reject",
26=>"Could not meet conditions of offer letter",
27=>"Could not arrange funds/loan reject",
28=>"Visa reject",
29=>"No focus course/program"
);

$config['RMC_TL_REVIEW_STATUS'] = array(
  'none' => 'Null',
  'ready' => 'Ready',
  'retry' => 'Retry',
  'pass' => 'Pass',
  'fail' => 'Fail'
);

$config['RMC_BUDDY_SPY_REVIEW_STATUS'] = array(
    'strong' => 'Strong',
    'medium' => 'Medium',
    'weak' => 'Weak',
    'not done' => 'Not Called'
);

$config['RMC_INTENT_TYPE'] = array(
    'buddy' => 'Buddy Status',
    'spy' => 'SPY Status'
);

$config['EXPENSES_COVERED_MASTER'] = array(
                    'tuition'           => 'Tuition & Fees',
                    'living'            => 'Living Expense',
                    'accommodation'     => 'Accommodation',
                    'travel_air_fare'   => 'Air Fare/Travel Expenses',
                    'spouse_child_care' => 'Spouse/Child Care',
                    'health'            => 'Health Cover',
                    'application'       => 'Application',
                    'others'            => 'Others'
                    );
$config['AMOUNT_INTERVAL'] = array('one_time'=>'One Time',
                                   'year'=>'Year',
                                   'semester'=>'Semester',
                                   'trimester'=>'Trimester',
                                   'month'=>'Month');

$config['SCHOLARSHIP_CATEGORY'] = array('internal'=>'College-specific',
                                        'external'=>'Non-college specific'
                                  );

$config['SCHOLARSHIP_SPECIAL_RESTRICTION'] = array(
                                        1=>'Only for Women',
                                        2=>'Intend/plan to return to home country after course completion',
                                        3=>'Must return to home country after course completion',
                                        4=>'Must leave the country after course completion',
                                        5=>'Only for students capable of full course funding',
                                        6=>'Only for candidates with a child',
                                        7=>'For candidates with disabilities only',
                                        8=>'Funding provided only for final year of study',
                                        9=>'Limitation on total family income',
                                        10=>'Must be nominated by an Institute faculty',
                                        11=>'Only for Jain students',
                                        12=>'Funding only for immediate family members of current students',
                                        13=>'Must be child/spouse of a diplomatic staff member',
                                        14=>'No Special Restriction applicable',
                                        15=>'Special Restriction/s applicable in this case, details for the same elaborated in Scholarship eligibility',
                                  );
$config['UNIV_INTAKE_SEASON'] = array('fall', 'spring', 'summer', 'winter', 'rolling');

$config['ALLOWED_ADMISSION_STATUS_FOR_APPID_INTAKE_SEASON'] = array('accepted', 'submitted', 'completed', 'rejected', 'notCompleting');

$config['NOTE_CATEGORY_ID_FINANCE'] = 15;
$config['NOTE_CATEGORY_ID_SPY'] = 16;
$config['NOTE_CATEGORY_ID_BUDDY'] = 17;