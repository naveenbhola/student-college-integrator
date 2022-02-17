<?php

namespace registration\libraries\FieldValueSources;

/**
 * Value source for Streams Field
 */ 

class Stream extends AbstractValueSource 
{
	/**
	 * Get values
	 *
	 * @param array $params additional parameters
	 * @return array
	 */
	
    public function getValues($params = array())
	{
		$this->CI->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new \ListingBaseBuilder();
		$HierarchyRepository = $listingBase->getHierarchyRepository();
		
		$streamsArray = $HierarchyRepository->getAllStreams();

		/*Code to filter out the custom values from the master list. It is required for the case when we want to limit the selection list */
		if(!empty($params['customStreamIds'])){
			$customStreamIds = array();
			foreach ($params['customStreamIds'] as $key => $streamId) {
				$customStreamIds[$streamId] = $streamId;
			}

			foreach($streamsArray as $key=>$streamData){
				if(empty($customStreamIds[$streamData['id']])){
					unset($streamsArray[$key]);
				}
			}
		}

		if(!$params['showAllStreams']) {
			foreach($streamsArray as $key => $streamData){
				if($streamData['id'] == GOVERNMENT_EXAMS_STREAM){
					unset($streamsArray[$key]);
				}
			}
		}
		
		return $streamsArray;
		
	}
}

?>