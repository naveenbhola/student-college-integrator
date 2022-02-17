<?php 

class DataAbstract{
	
	private $_CI;
	private $listingId;
	private $listingType;
	private $sectionName;

	public function __construct()
	{
		$this->_CI = & get_instance();
	}
	
	public function compileData($object,$customData){
			
			try{
				// Getting Data From Object nd Storing them in Arrays
				$sectionData = $this->_getDataFromObject($object,$customData);
				// Format/Process the Data in the array as per the SOLR Document
				$sectionData = $this->_processData($sectionData,$customData);
				// Remove Fields Other that nl_ prefix
				$sectionData = $this->_removeFieldOtherThanNL($sectionData);

				return $sectionData;	
			}
			catch(Exception $e){
				$this->logException("Exception Occurs While Indexing the ".$customData['listingType']." with  ".$customData['listingId']." in ".$customData['sectionName']." section",true);
			}
			
		
	}

	private function _removeFieldOtherThanNL($sectionData){
		foreach ($sectionData as $key => $value) {
			
			/* 2d Array (SECTION DATA)
			 Check whether section is 2d array , also check if the key is nl_
			 For Ex. nl_institute_syn is a 2d array but needs to check as 1d array
			*/
			if(is_array($value) && strpos($key, "nl_") !== 0){
				foreach ($value as $keyInner => $valueInner) {
					if(strpos($keyInner, "nl_") !== 0){
						unset($sectionData[$key][$keyInner]);
					}
				}
			}else{ // 1d array
				if(strpos($key, "nl_") !== 0){
						unset($sectionData[$key]);
					}
			}
		}
		return $sectionData;
	}

	public function logException($exceptionMessage,$throwException=false){
		error_log($exceptionMessage);
		// error_log($exceptionMessage, 3, "/tmp/my-errors.log");
		_p($exceptionMessage);
		if($throwException === true){
			throw new Exception($exceptionMessage);
		}
	}	


}

?>
