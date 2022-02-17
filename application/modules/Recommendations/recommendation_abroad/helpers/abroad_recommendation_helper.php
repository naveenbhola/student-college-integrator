<?php  
if (!defined('BASEPATH')) exit('No direct script access allowed');

function rh_abroad_buildRecommendationData($recommendations,$listing_details,$category_details,$user_details,$random_si_seed_institutes)
{
	$response_recommendations = array();
	
	foreach($recommendations as $user_id => $user_recommendations)
	{
		$response_user_recommendations = array();
		
		foreach($user_recommendations as $category_id => $category_recommendations)
		{
			$response_category_recommendations = array();
			
			$response_category_recommendations['also_viewed'] = array();
			$response_category_recommendations['similar_institutes'] = array();
			$response_category_recommendations['collaborative_profile_based'] = array();
			$response_category_recommendations['profile_based'] = array();
			
			$response_category_recommendations_also_viewed = array();
			$response_category_recommendations_similar_institutes = array();
			$response_category_recommendations_profile_based = array();
			$response_category_recommendations_collaborative_profile_based = array();
			
			
			$random_si_seed_institute = '';
			
			foreach($category_recommendations as $recommendation)
			{
				$algo = $recommendation['algo'];
				$institute_id = $recommendation['institute_id'];
			
				$listing_detail = $listing_details[$institute_id];
				
				$response_recommendation = array(
		                        					'institute_id' => $institute_id,
		                        					'institute_name' => $listing_detail['institute_name'],
		                        					'institute_url' => $listing_detail['institute_url'],
		                        					'city' => $listing_detail['city'],
		                        					'country' => $listing_detail['country'],
		                        					'logo' => $listing_detail['logo'],
		                        					'course_id' => $listing_detail['course_id'],
		                        					'course_name' => $listing_detail['course_name'],
		                        					'course_url' => $listing_detail['course_url'],
		                        					'course_duration' => $listing_detail['course_duration'],
		                        					'course_duration_unit' => $listing_detail['course_duration_unit'],
		                        					'course_type' => $listing_detail['course_type'],
		                        					'fees_value' => $listing_detail['fees_value'],
		                        					'fees_unit' => $listing_detail['fees_unit'],
										'hasBrochure' => $listing_detail['hasBrochure']
		                        				 );
				                        			
				                        				 
				                        		 
				$var = 'response_category_recommendations_'.$algo;                        		 
				array_push($$var, $response_recommendation);      

				if($algo == 'similar_institutes' && !$random_si_seed_institute)
				{
					$random_si_seed_course = $recommendation['random_si_seed_course'];
					$random_si_seed_institute =  $random_si_seed_institutes[$random_si_seed_course];
				}
			}
		
			$response_category_recommendations['also_viewed'] = $response_category_recommendations_also_viewed;
			$response_category_recommendations['similar_institutes'] = $response_category_recommendations_similar_institutes;
			$response_category_recommendations['collaborative_profile_based'] = $response_category_recommendations_collaborative_profile_based;
			$response_category_recommendations['profile_based'] = $response_category_recommendations_profile_based;
			
			
			$response_user_recommendations[$category_id] = array(
																	'recommendations' => $response_category_recommendations,
																	'name' => $category_details[$category_id],
																	'random_si_seed_institute' => $random_si_seed_institute
																 );														
		}	

		$response_recommendations[$user_id] = array(
														'recommendations' => $response_user_recommendations,
														'name' => $user_details[$user_id]['displayname'],
														'email' => $user_details[$user_id]['email'],
														'hash' => $user_details[$user_id]['hash']
													);
																						
	}
	
	return $response_recommendations;
}

function rh_abroad_getCountryForCategory($category_seed_data)
{
	$seed_data_countries = array();
	$seed_data_countries_count = array();
	
	foreach ($category_seed_data as $seed_source => $seed_data)
	{
		foreach ($seed_data as $seed_data_item)
		{
			$seed_data_countries[] = (int) $seed_data_item['country_id'];
			
			if(isset($seed_data_countries_count[$seed_data_item['country_id']]))
			{
				$seed_data_countries_count[$seed_data_item['country_id']] += 1;
			}
			else 
			{
				$seed_data_countries_count[$seed_data_item['country_id']] = 1;
			}
		}
	}
	
	$country_to_use = 0;
	
	if(in_array(2,$seed_data_countries))
	{
		$country_to_use = 2;
	}
	else 
	{
		/*
		 * We'll use the one with hightest number of seed data
		 */
		arsort($seed_data_countries_count);
		$country_to_use = key($seed_data_countries_count);
	}
	
	return $country_to_use;
}

function rh_abroad_filterCategorySeedDataByCountry($category_seed_data,$country)
{
	$filtered_seed_data = array();
	
	foreach ($category_seed_data as $seed_source => $seed_data)
	{	
		foreach ($seed_data as $seed_data_item)
		{
			if($seed_data_item['country_id'] == $country)
			{
				$filtered_seed_data[$seed_source][] = $seed_data_item;
			}	
		}		
	}
	
	return $filtered_seed_data;
}