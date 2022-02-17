<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Delimiter Constants for category page URL
define("_ENT_AFFILIATION_END1"		,  "approved"  		);
define("_ENT_AFFILIATION_END2"		,  "recognized"		);
define("_ENT_FEE_START"	      		,  "fees-upto" 		);
define("_ENT_EXAM_START"      		,  "accepts"  		);
define("_ENT_EXAM_END"        		,  "score"    		);
define("_ENT_LOCATION_START1" 		,  "colleges-in"	);
define("_ENT_LOCATION_START2" 		,  "courses-in" 	);
define("_ENT_LOCATION_END1"   		,  "accepts"    	);
define("_ENT_LOCATION_END2"   		,  "fees-upto"  	);

// Sub-categories to be taken up in the R&R 2
define("_ENT_COURSE_MBA"      		,  "mba"		);
define("_ENT_COURSE_BE_BTECH" 		,  "be-btech"		);

// list of affiliation
$config['CP_AFFILIATION_LIST'] 	  	= array( 1 => "aicte",
				     		 2 => "ugc"  ,
				     		 3 => "dec"
					       );

$config['CP_AFFILIATION_TO_VALUE_MAP']  = array( "aicte" => _ENT_AFFILIATION_END1,
						 "ugc"	 => _ENT_AFFILIATION_END2,
						 "dec"	 => _ENT_AFFILIATION_END1
						);


$config['CP_SUB_CATEGORY_NAME_LIST'] 	= array( 23 => "mba", 
				     	      	 56 => "be-btech"
						);

$config['CP_SORTING_LIST'] 		= array( 'none'				,
						 'highfees'			,
						 'lowfees'			,
						 'longduration'			,
						 'shortduration'		,
						 'viewCount'			,
						 'topInstitutes'		,
						 'dateOfCommencement'		,
						 'reversedateOfCommencement'	,
						 'highexamscore'		,
						 'lowexamscore'
						);

$config['CP_FEES_RANGE'] 		= array(
				   		'RS_RANGE_IN_LACS' => array(
                                                 	100000  => "Maximum 1 Lakh",
                                                 	200000  => "Maximum 2 Lacs",
                                                 	500000  => "Maximum 5 Lacs",
                                                 	700000  => "Maximum 7 Lacs",
                                                 	1000000 => "Maximum 10 Lacs",
                                             		)
                             			);

$config['CP_FILTER_KNOCKOUT_PRIORITY'] = array("locality", "examsscore", "locality-examsscore",  "locality-zone", "city", "state", "exam");


// Flag Constant 
define( "_ID_FLAG_OFF"            		, 0 		);
define( "_ID_FLAG_ON"	          		, 1 		);
define( "_ID_EMPTY_STRING"	  		, "" 		);

// Constants for the request variable names of category page 
define( "CP_REQUEST_VAR_NAME_EXAM"		, "exam"	);
define( "CP_REQUEST_VAR_NAME_PAGE_NUM"		, "pgno"	);
define( "CP_REQUEST_VAR_NAME_SORT_TYPE"		, "sort"	);
define( "CP_REQUEST_VAR_NAME_NAUKRI_LEARNING"   , "nl"		);

// constants of default values of request variables
define( "CP_DEFAULT_VAL_SORT" 			, "none"	);
define( "CP_DEFAULT_VAL_PAGE_NUM" 		, 1		);
define( "CP_DEFAULT_VAL_NAUKRI_LEARNING"	, 0		);
define( "CP_DEFAULT_VAL_COURSE_ID"		, 1		);
define( "CP_DEFAULT_VAL_SUB_CATEGORY_ID"	, 1		);

// default values of location parameters
define( "CP_DEFAULT_VAL_LOCALITY_ID" 		, 0		);
define( "CP_DEFAULT_VAL_LOCALITY_NAME"		, ""		);
define( "CP_DEFAULT_VAL_ZONE_ID" 		, 0		);
define( "CP_DEFAULT_VAL_ZONE_NAME"		, ""		);
define( "CP_DEFAULT_VAL_CITY_ID" 		, 1		);
define( "CP_DEFAULT_VAL_CITY_NAME"		, ""		);
define( "CP_DEFAULT_VAL_STATE_ID" 		, 1		);
define( "CP_DEFAULT_VAL_STATE_NAME" 		, ""		);
define( "CP_DEFAULT_VAL_COUNTRY_ID" 		, 2		);
define( "CP_DEFAULT_VAL_COUNTRY_NAME" 		, "India"	);
 
// category page identifier 
define( "CP_CATEGORY_PAGE_URL_IDENTIFIER"	, "ctpg"	);

// constants for data seperators in URL
define( "CP_OTHER_EXAM_AND_EXAM_SEPERATOR"	, "$"		);
define( "CP_OTHER_EXAM_NAME_AND_SCORE_SEPERATOR", ":"		);
define("_ENT_SUBCAT_COURSE_SEPERATOR"		, "in"     	);

define("CP_NEW_RNR_URL_TYPE", "RNRURL");
define("CP_HEADER_NAME",'photos');