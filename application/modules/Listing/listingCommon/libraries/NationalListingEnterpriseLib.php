<?php
class NationalListingEnterpriseLib {

	function init() {
		$this->CI =& get_instance();
	}
	function getNameIdPair($ids) {
		$this->init();
		$this->institutepostingmodel = $this->CI->load->model('nationalInstitute/institutepostingmodel');
		$instituteData              = array();
		$universityData             = array();
		$universityData['idNameArr'] = array();
		$instituteData['idNameArr'] = array();
		foreach($ids as $type=>$typeIds) {
			switch($type) {
				case 'institute':
						$instituteData = $this->institutepostingmodel->getInstituteNamesById($ids[$type], array('name'));
						if(empty($instituteData)){
							$instituteData['idNameArr'] = array();
						}
						break;
				case 'university':
						$universityData = $this->institutepostingmodel->getInstituteNamesById($ids[$type], array('name'), false, true, 'university');
						if(empty($universityData)){
							$universityData['idNameArr'] = array();
						}
						break;
			}
		}
		return $instituteData['idNameArr']+$universityData['idNameArr'];
	}
}