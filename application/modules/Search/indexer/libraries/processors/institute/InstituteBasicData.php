<?php 
require_once dirname(__FILE__).'/../DataAbstract.php';
class InstituteBasicData extends DataAbstract{
	

	public function __construct()
	{
		$this->_CI = & get_instance();		
		$this->_CI->load->config('indexer/nationalIndexerConfig');
	}


	public function _getDataFromObject($instituteObj)
	{
		$instituteBasicData = array();
		$instituteBasicData['nl_institute_id'] = $instituteObj->getId();
		
		$instituteBasicData['nl_institute_name'] = $instituteObj->getName();
		$instituteBasicData['nl_institute_synonyms'] = $instituteObj->getSynonym();

		$instituteBasicData['nl_institute_synonyms'] = explode(INSTTIUE_SYN_DEL, $instituteBasicData['nl_institute_synonyms']);
		$instituteBasicData['nl_institute_abbreviation'] = null;
		$dbAbbr = $instituteObj->getAbbreviation();
		if(!empty($dbAbbr)){
			$instituteBasicData['nl_institute_abbreviation'] = $dbAbbr;
			if(!in_array($dbAbbr, $instituteBasicData['nl_institute_synonyms'])) {
				$instituteBasicData['nl_institute_synonyms'][] = $dbAbbr;
			}
		}
		/*$instituteBasicData['nl_institute_accrediation'] = $instituteObj->getAccreditation();
		$instituteBasicData['nl_institute_ownership'] = $instituteObj->getOwnership();*/
		$instituteBasicData['nl_institute_type'] = $instituteObj->getListingType();

		$instituteBasicData['nl_institute_specification_type'] = $instituteObj->getInstituteSpecificationType();
		$instituteBasicData['nl_university_specification_type'] = $instituteObj->getUniversitySpecificationType();

		$PCW_INSTITUTES = $this->_CI->config->item('PCW_INSTITUTES');
		if(in_array($instituteObj->getId(), $PCW_INSTITUTES)) {
			$instituteBasicData['nl_institute_popular'] = 1;
		} else {
			$instituteBasicData['nl_institute_popular'] = 0;
		}
		
		return $instituteBasicData;
	}

	public function _processData($instituteBasicData){
		return $instituteBasicData;
	}


}

?>
