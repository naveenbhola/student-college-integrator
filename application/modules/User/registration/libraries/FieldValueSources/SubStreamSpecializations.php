<?php

namespace registration\libraries\FieldValueSources;

/**
 * Value source for SubstreamsSpecializations Field
 */ 

class SubStreamSpecializations extends AbstractValueSource 
{
	/**
	 * Get values
	 *
	 * @param array $params - streamId and/or courseId 
	 * @return array
	 */ 

	public function getValues($params = array())
	{	
		$this->CI->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new \ListingBaseBuilder();

		$streamIds = $params['streamIds'];
		$baseCourseIds = $params['baseCourseIds'];
		/* Filtering dummy base(other courses) course ids */
		foreach($baseCourseIds as $key=>$value){
			if($value < 0){
				unset($baseCourseIds[$key]);
			}
		}
		if(!empty($baseCourseIds) && !empty($streamIds) && !empty($streamIds[0])){
			$BaseCourseRepository = $listingBase->getBaseCourseRepository();
			$hierarchyArray = $BaseCourseRepository->getBaseEntityTreeByBaseCourseIds($baseCourseIds, $streamIds, 1, array(), 1);

		}else if(!empty($streamIds) && !empty($streamIds[0])){

			$HierarchyRepository = $listingBase->getHierarchyRepository();
			$hierarchyArray = $HierarchyRepository->getSubstreamSpecializationByStreamId($streamIds, 1, 'array', 1);
		}

		/*Code to filter out the custom values from the master list. It is required for the case when we want to limit the selection list */
		if(!empty($params['customSubStreamSpecializations'])){

			if(!empty($params['customSubStreamSpecializations']['value'])){
				$params['customSubStreamSpecializations'] = $params['customSubStreamSpecializations']['value'];
			}
			/*Filtering substream and mapped specializations */
			foreach($hierarchyArray[$streamIds[0]]['substreams'] as $subStream=>$substreamData){
				if(empty($params['customSubStreamSpecializations'][$subStream])){
					unset($hierarchyArray[$streamIds[0]]['substreams'][$subStream]);
				}else{
					$specializations = $hierarchyArray[$streamIds[0]]['substreams'][$subStream]['specializations'];

					$customSpecializations = array();
					foreach ($params['customSubStreamSpecializations'][$subStream] as $key => $specId) {
						$customSpecializations[$specId] = $specId;
					}

					foreach($specializations as $specId=>$specData){
						if(empty($customSpecializations[$specId])){
							unset($hierarchyArray[$streamIds[0]]['substreams'][$subStream]['specializations'][$specId]);
						}
					}
				}
			}

			/*Filtering unmapped specializations */
			if(!empty($params['customSubStreamSpecializations']['ungrouped'])){
				$customSpecializations = array();
				foreach ($params['customSubStreamSpecializations']['ungrouped'] as $key => $specId) {
					$customSpecializations[$specId] = $specId;
				}

				foreach($hierarchyArray[$streamIds[0]]['specializations'] as $specId=>$specData){
					if(empty($customSpecializations[$specId])){
						unset($hierarchyArray[$streamIds[0]]['specializations'][$specId]);
					}
				}
			}else{
				unset($hierarchyArray[$streamIds[0]]['specializations']);
			}

		}
		return $hierarchyArray;
		
	}

}

?>