<?php
Class CollegeReviewSolrResponseParser {
	function parseAggregateReviewsData($result) {
		$solrRatingDocs = $result['stats']['stats_fields'];
		$returnData = array();
		foreach ($solrRatingDocs as $key => $statsData) {
			if(!empty($statsData['sum'])) {
				$returnData['aggregateRating'][$key] = round($statsData['mean'], 1);
			}
		}

		$solrFacetDocs = $result['facet_counts']['facet_intervals'];
		$intervalNumber = 1;
		foreach ($solrFacetDocs['averageRating'] as $key => $count) {
			$returnData['intervalRatingCount'][$intervalNumber."-".($intervalNumber+1)] = $count;
			$intervalNumber++;
		}

    	return $returnData;
	}	

	function parseAggregateReviewsDataForMultipleCourses($response){
		$pivotData = $response['facet_counts']['facet_pivot'];
		$returnData = array();
		foreach ($pivotData['statspivot'] as $row) {
			$courseId = $row['value'];
			$returnData[$courseId]['totalCount'] = $row['count'];
			foreach ($row['stats']['stats_fields'] as $key => $statsData) {
				foreach ($statsData as $statName => $statValue) {
					$returnData[$courseId]['aggregateRating'][$key][$statName] = round($statValue, 1);
				}
			}
		}
		foreach ($pivotData['countpivot'] as $row) {
			$courseId = $row['value'];
			$returnData[$courseId]['totalCount'] = $row['count'];
			$returnData[$courseId]['intervalRatingCount']['1-2'] = $row['ranges']['averageRating']['counts']['0.0'] + $row['ranges']['averageRating']['counts']['1.0'];
			$returnData[$courseId]['intervalRatingCount']['2-3'] = $row['ranges']['averageRating']['counts']['2.0'];
			$returnData[$courseId]['intervalRatingCount']['3-4'] = $row['ranges']['averageRating']['counts']['3.0'];
			$returnData[$courseId]['intervalRatingCount']['4-5'] = $row['ranges']['averageRating']['counts']['4.0'];
			$returnData[$courseId]['intervalRatingCountForPlacement']['1-2'] = $row['ranges']['avgSalaryPlacementRating']['counts']['0.0'] + $row['ranges']['avgSalaryPlacementRating']['counts']['1.0'];
			$returnData[$courseId]['intervalRatingCountForPlacement']['2-3'] = $row['ranges']['avgSalaryPlacementRating']['counts']['2.0'];
			$returnData[$courseId]['intervalRatingCountForPlacement']['3-4'] = $row['ranges']['avgSalaryPlacementRating']['counts']['3.0'];
			$returnData[$courseId]['intervalRatingCountForPlacement']['4-5'] = $row['ranges']['avgSalaryPlacementRating']['counts']['4.0'];
		}
		return $returnData;
	}



	function parseAggregateReviewsDataForPlacementTags($response){
		
		$tagsData =  $response['facet_counts']['facet_fields']['placements_topics'];
		
		$tagIds=array();
		if(!empty($tagsData)){
			$tagIds =  array_keys($tagsData);
		}

		return $tagIds;
	}
}