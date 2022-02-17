<?php 
$config = array();

$reasonMappingArray = array();

/*- Excluded Genie
- ExclusionOnSolrQuery
- ExclusionInPorting
- ExclusionInsufficientCredits
- ExclusionInAlreadyAllocated
- ExclusionInOldLeadCheck
- ExclusionInDailyLimitReached (Final Clients)*/

$reasonMappingArray['exclude_genie_ids'] 					= 'Excluded Genie';
$reasonMappingArray['Exclusion_on_solr_query'] 			= 'ExclusionOnSolrQuery Genie';
$reasonMappingArray['excluded_insufficient_credits'] 		= 'Insufficient Credits';
$reasonMappingArray['excluded_already_allocated'] 		= 'ExclusionInAlreadyAllocated Genie';
$reasonMappingArray['excluded_old_genie'] 			= 'ExclusionInOldLeadCheck Genie';
$reasonMappingArray['excluded_final_genie'] 	= 'ExclusionInDailyLimitReached Genie';
$reasonMappingArray['excluded_porting'] 	= 'Excluded in Porting Filter';
/*$reasonMappingArray['ExcludedGenie'] 			= 'Excluded Genie';*/

		$request_param['match']['should'][] = array('exclude_genie_ids'=> $genie_id);
		$request_param['match']['should'][] = array('excluded_insufficient_credits'=> $genie_id);
		$request_param['match']['should'][] = array('excluded_porting'=> $genie_id);
		$request_param['match']['should'][] = array('excluded_already_allocated'=> $genie_id);
		$request_param['match']['should'][] = array('excluded_old_genie'=> $genie_id);
		$request_param['match']['should'][] = array('excluded_final_genie'=> $genie_id);


$config['reasonMappingArray'] = $reasonMappingArray;
?>