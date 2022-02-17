<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Search Config, This file contains all the config variables related to search
|--------------------------------------------------------------------------
|
*/


/**
 *
|---------------------------------------------------------
| QER Section											  
|---------------------------------------------------------
 */

/**
 * Use QER or not
 */
$config['qer'] = true;

/**
 * QER URL
 */
//$config['qer_url'] = "http://10.208.65.61:8983/entity/entity";
//$config['qer_url'] = "http://172.10.16.71:8984/entity/entity";
//$config['qer_url_new'] = "http://172.10.16.82:8985/query_entity_recognition/quiet";
//$config['qer_url_upgraded'] = "http://172.10.16.71:8984/query_entity_recognition/quiet";

$config['qer_url'] = "http://slave01.shiksha.jsb9.net:8984/entity/entity";
$config['qer_url_new'] = "http://mailermaster.shiksha.jsb9.net:8985/query_entity_recognition/quiet";
$config['qer_url_upgraded'] = "http://10.10.16.101:8983/query_intent_entity_tagger/quiet";
$config['qer_url_mapupdate'] = "http://10.10.16.101:8983/query_intent_entity_tagger/mapupdate?dbgenerate=true&candidatecsv=all";

/**
 * QUE Race URL
 */
$config['querace_url'] = "http://10.208.65.61:8983/querace/select?";



$config['min_threshhold_for_institute_type_search'] = 5;

/**
 *
|---------------------------------------------------------
| Search result highlighting section											  
|---------------------------------------------------------
 */
/**
 * Enable highlighting
 */
$config['highlight']  = true;

/**
 * Search Highlight prefix string
 */
$config['highlight_prefix']  = "<b>";

/**
 * Search Highlight suffix string
*/
$config['highlight_suffix']  = "</b>";

/**
 *
|---------------------------------------------------------
| Search facet fields section
|---------------------------------------------------------
 */
/**
 * Search Facet fields
 */
$config['facet_fields']  = array(
								'course_location_cluster',
								'course_type_cluster',
								'course_level_cluster'
								);

/**
 *
|---------------------------------------------------------
| Search general section
|---------------------------------------------------------
 */
/**
 * Search server
 */
$config['search_server'] = "solr";

/**
 * Solr response format
*/
$config['solr_response_format']  = "phps";

/**
 * Boost values used while searching in general fields
*/
$config['query_time_fields_boost']  = array(
										'course_country_name' => 20,
										'course_city_name' => 20,
										'facetype' => 5,
										'course_type_cluster' => 5,
										'institute_abbreviation' => 20,
										'institute_title' => 500,
										'course_title' => 1500
										);

/**
 * Max sponsored results
*/
$config['max_sponsored_results'] = 3;
/**
 * Max featured results
*/
$config['max_featured_results'] = 3;
/**
 * Max banner results
*/
$config['max_banner_results'] = 1;

/**
 * Max CMS sponsored results
*/
$config['cms_max_sponsored_results'] = 2;
/**
 * Max featured results CMS side
*/
$config['cms_max_featured_results'] = 2;
/**
 * Max banner results CMS side
*/
$config['cms_max_banner_results'] = 0;

/**
 * Valid search types
*/
$config['search_index_types'] = array(
								"institute",
								"course",
								"question",
								"article",
								"autosuggestor",
								"discussion",
								"university",
								"abroadcourse",
								"abroadinstitute",
								"tag",
								"career"
							);

/**
 * Document format
*/
$config['document_format'] = "solr_xml";

/**
 * Default content rows in content type search
*/
$config['content_rows_in_content_search'] = 10;
/**
 * Default institute rows in content type search
*/
$config['institute_rows_in_content_search'] = 10;
/**
 * Default content rows in content type search in CMS
*/
$config['cms_content_rows_in_content_search'] = 0;
/**
 * Default institute rows in content type search in CMS
*/
$config['cms_institute_rows_in_content_search'] = 7;

/**
 * Default institute rows in institute type search
*/
$config['institute_rows_in_institute_search'] = 15;
/**
 * Default content rows in institute type search
*/
$config['content_rows_in_institute_search'] = 5;	

/**
 * Default institute rows in institute type search in CMS
*/
$config['cms_institute_rows_in_institute_search'] = 5;
/**
 * Default content rows in institute type search in CMS
*/
$config['cms_content_rows_in_institute_search'] = 0;


$config['max_discussion_comments'] = 10;

/**
 * Search types
*/
$config['search_types'] = array('institute', 'content');


/**
 * What consist of content search
*/
$config['facetype_in_content_search'] = array('discussion', 'article', 'question', 'discussion');

/**
 * Valid QER fields for single search result
 * 
 */
$config['single_result_qer_fields_other_than_institute'] = array('course_city_id', 'course_locality_id', 'course_zone_id', 'course_country_id');

/**
 * Sponsored Product Keys
*/
$config['sponsored_base_product_keys'] = array('ssp_india_subcat1_tier1',
											   'ssp_india_subcat1_tier2',
											   'ssp_india_subcat1_tier3',
											   'ssp_india_subcat2_tier1',
											   'ssp_india_subcat2_tier2',
											   'ssp_india_subcat2_tier3',
											   'ssp_india_subcat3_tier1',
											   'ssp_india_subcat3_tier2',
											   'ssp_india_subcat3_tier3',
											   'ssp_india_subcat4_tier1',
											   'ssp_india_subcat4_tier2',
											   'ssp_india_subcat4_tier3',
											   'ssp_india_subcat5_tier1',
											   'ssp_india_subcat5_tier2',
											   'ssp_india_subcat5_tier3',
											   'ssp_abroad_category1_country1',
											   'ssp_abroad_category2_country1',
											   'ssp_abroad_category3_country1',
											   'ssp_abroad_category4_country1',
											   'ssp_abroad_category5_country1',
											   'ssp_abroad_category1_country2',
											   'ssp_abroad_category2_country2',
											   'ssp_abroad_category3_country2',
											   'ssp_abroad_category4_country2',
											   'ssp_abroad_category5_country2',
											);

/**
 * Sponsored base Product ids
*/
$config['ssp_india_subcat1_tier1'] 		= 376;
$config['ssp_india_subcat1_tier2'] 		= 381;
$config['ssp_india_subcat1_tier3'] 		= 386;
$config['ssp_india_subcat2_tier1'] 		= 377;
$config['ssp_india_subcat2_tier2'] 		= 382;
$config['ssp_india_subcat2_tier3'] 		= 387;
$config['ssp_india_subcat3_tier1'] 		= 378;
$config['ssp_india_subcat3_tier2'] 		= 383;
$config['ssp_india_subcat3_tier3'] 		= 388;
$config['ssp_india_subcat4_tier1'] 		= 379;
$config['ssp_india_subcat4_tier2'] 		= 384;
$config['ssp_india_subcat4_tier3'] 		= 389;
$config['ssp_india_subcat5_tier1'] 		= 380;
$config['ssp_india_subcat5_tier2'] 		= 385;
$config['ssp_india_subcat5_tier3'] 		= 390;
$config['ssp_abroad_category1_country1'] 	= 406;
$config['ssp_abroad_category2_country1'] 	= 407;
$config['ssp_abroad_category3_country1'] 	= 408;
$config['ssp_abroad_category4_country1'] 	= 409;
$config['ssp_abroad_category5_country1'] 	= 410;
$config['ssp_abroad_category1_country2'] 	= 411;
$config['ssp_abroad_category2_country2'] 	= 412;
$config['ssp_abroad_category3_country2'] 	= 413;
$config['ssp_abroad_category4_country2'] 	= 414;
$config['ssp_abroad_category5_country2'] 	= 415;


/**
 * Featured Product keys
 */
$config['featured_base_product_keys'] = array('sfp_india_subcat1_tier1',
											  'sfp_india_subcat1_tier2',
											  'sfp_india_subcat1_tier3',
											  'sfp_india_subcat2_tier1',
											  'sfp_india_subcat2_tier2',
											  'sfp_india_subcat2_tier3',
											  'sfp_india_subcat3_tier1',
											  'sfp_india_subcat3_tier2',
											  'sfp_india_subcat3_tier3',
											  'sfp_india_subcat4_tier1',
											  'sfp_india_subcat4_tier2',
											  'sfp_india_subcat4_tier3',
											  'sfp_india_subcat5_tier1',
											  'sfp_india_subcat5_tier2',
											  'sfp_india_subcat5_tier3',
											  'sfp_abroad_category1_country1',
											  'sfp_abroad_category2_country1',
											  'sfp_abroad_category3_country1',
											  'sfp_abroad_category4_country1',
											  'sfp_abroad_category5_country1',
											  'sfp_abroad_category1_country2',
											  'sfp_abroad_category2_country2',
											  'sfp_abroad_category3_country2',
											  'sfp_abroad_category4_country2',
											  'sfp_abroad_category5_country2',
											);

/**
 * featured base Product ids
*/
$config['sfp_india_subcat1_tier1'] 		= 391;
$config['sfp_india_subcat1_tier2'] 		= 396;
$config['sfp_india_subcat1_tier3'] 		= 401;
$config['sfp_india_subcat2_tier1'] 		= 392;
$config['sfp_india_subcat2_tier2'] 		= 397;
$config['sfp_india_subcat2_tier3'] 		= 402;
$config['sfp_india_subcat3_tier1'] 		= 393;
$config['sfp_india_subcat3_tier2'] 		= 398;
$config['sfp_india_subcat3_tier3'] 		= 403;
$config['sfp_india_subcat4_tier1'] 		= 394;
$config['sfp_india_subcat4_tier2'] 		= 399;
$config['sfp_india_subcat4_tier3'] 		= 404;
$config['sfp_india_subcat5_tier1'] 		= 395;
$config['sfp_india_subcat5_tier2'] 		= 400;
$config['sfp_india_subcat5_tier3'] 		= 405;
$config['sfp_abroad_category1_country1'] 	= 416;
$config['sfp_abroad_category2_country1'] 	= 417;
$config['sfp_abroad_category3_country1'] 	= 418;
$config['sfp_abroad_category4_country1'] 	= 419;
$config['sfp_abroad_category5_country1'] 	= 420;
$config['sfp_abroad_category1_country2'] 	= 421;
$config['sfp_abroad_category2_country2'] 	= 422;
$config['sfp_abroad_category3_country2'] 	= 423;
$config['sfp_abroad_category4_country2'] 	= 424;
$config['sfp_abroad_category5_country2'] 	= 425;

/**
 * Banner Product keys
 */
$config['banner_base_product_keys'] = array(
											'sbp_india_subcat1_tier1',
											'sbp_india_subcat1_tier2',
											'sbp_india_subcat1_tier3',
											'sbp_india_subcat2_tier1',
											'sbp_india_subcat2_tier2',
											'sbp_india_subcat2_tier3',
											'sbp_india_subcat3_tier1',
											'sbp_india_subcat3_tier2',
											'sbp_india_subcat3_tier3',
											'sbp_india_subcat4_tier1',
											'sbp_india_subcat4_tier2',
											'sbp_india_subcat4_tier3',
											'sbp_india_subcat5_tier1',
											'sbp_india_subcat5_tier2',
											'sbp_india_subcat5_tier3',
											'sbp_abroad_category1_country1',
											'sbp_abroad_category2_country1',
											'sbp_abroad_category3_country1',
											'sbp_abroad_category4_country1',
											'sbp_abroad_category5_country1',
											'sbp_abroad_category1_country2',
											'sbp_abroad_category2_country2',
											'sbp_abroad_category3_country2',
											'sbp_abroad_category4_country2',
											'sbp_abroad_category5_country2',
											);

/**
 * Banner base Product ids
*/
$config['sbp_india_subcat1_tier1'] 		= 426;
$config['sbp_india_subcat1_tier2'] 		= 431;
$config['sbp_india_subcat1_tier3'] 		= 436;
$config['sbp_india_subcat2_tier1']		= 427;
$config['sbp_india_subcat2_tier2'] 		= 432;
$config['sbp_india_subcat2_tier3'] 		= 437;
$config['sbp_india_subcat3_tier1']		= 428;
$config['sbp_india_subcat3_tier2'] 		= 433;
$config['sbp_india_subcat3_tier3'] 		= 438;
$config['sbp_india_subcat4_tier1']		= 429;
$config['sbp_india_subcat4_tier2'] 		= 434;
$config['sbp_india_subcat4_tier3'] 		= 439;
$config['sbp_india_subcat5_tier1']		= 430;
$config['sbp_india_subcat5_tier2'] 		= 435;
$config['sbp_india_subcat5_tier3'] 		= 440;
$config['sbp_abroad_category1_country1'] 	= 441;
$config['sbp_abroad_category2_country1'] 	= 442;
$config['sbp_abroad_category3_country1'] 	= 443;
$config['sbp_abroad_category4_country1'] 	= 444;
$config['sbp_abroad_category5_country1'] 	= 445;
$config['sbp_abroad_category1_country2'] 	= 446;
$config['sbp_abroad_category2_country2'] 	= 447;
$config['sbp_abroad_category3_country2'] 	= 448;
$config['sbp_abroad_category4_country2'] 	= 449;
$config['sbp_abroad_category5_country2'] 	= 450;


$config['max_featured_institutes_for_cat_loc_tier'] = 3;
$config['max_sponsored_institutes_for_cat_loc_tier'] = 6;
$config['max_banner_institutes_for_cat_loc_tier'] = 2;



/*
 Virtual cities are very few and they don't change normally so instead of getting them
 from DB or memcache. its better to maintain mapping here in code.
*/
$config['virtual_city_mapping'] = array(10223 => array(74, 84, 87, 95, 161, 1616), //Delhi NCR
										10224 => array(151, 158, 838) //Mumbai
								);

/*
 City mapping: If user search for one city than show results for combination of cities
 e.g if user search for delhi(74) than show them results for noida, greater noida, gurgaon etc
*/
$config['city_mapping'] = array( 74 => array(74, 84, 87, 95, 161, 1616),
								151 => array(151, 158, 838)
							   );
/*$config['qer_to_solr_field_mapping'] = array(
												'city' =>'course_city_id',
												'level' =>'course_level_cluster',
												'institute' =>'institute_id',
												'course' =>'course_ldb_id',
												'locality' =>'course_locality_id',
												'country' =>'course_country_id',
												'state' =>'course_state_id',
												'zone' =>'course_zone_id',
												'continent' =>'course_continent_id',
												'duration' =>'course_duration_normalized',
												'mode'=>'course_type_cluster'
												);*/

	$config['qer_to_solr_field_mapping'] = array(
												'city' =>'nl_course_city_id',
												'state' =>'nl_course_state_id',
												'locality' =>'nl_course_locality_id',
												'institute' =>'nl_institute_id',
												'base_course' =>'nl_base_course_id',
												'specialization' =>'nl_specialization_id',
												'substream' =>'nl_substream_id',
												'stream' =>'nl_stream_id',
												'popular_group' =>'nl_popular_group_id',
												'certificate_provider' =>'nl_certificate_provider_id',
												'education_type'=>'nl_course_education_type_id',
												'delivery_method'=>'nl_course_delivery_method_id',
												'course_type'=>'nl_course_type_id',
												'credential'=>'nl_course_credential_id'
												);

$config['tag_quality_factor_boost_params'] = array(
												  "follow" => 0.2,
												  "discussions" => 0.1,
												  "questions" => 0.1
												  );
