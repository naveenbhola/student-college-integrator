<?php

class CustomCities{

    private $custom_institutes_cities = array('ICA');
	
	function __construct()
    {
        $CI =& get_instance();
        $this->cacheLibObj = $CI->load->library('common/CacheLib');
		$this->curlObj = $CI->load->library('curl');
    }

	public function addCustomCities(){
		foreach($this->custom_institutes_cities as $institute){
			$functionName = '_add'.$institute.'Cities';
			$js = $this->cacheLibObj->get($functionName);
			if($js == "ERROR_READING_CACHE"){
				$js = $this->$functionName();
				$this->cacheLibObj->store($functionName,$js,86400);
			}
			echo "<script>custom_localities['".$institute."'] = ".$js.";</script>";
		}
	}
	
	private function _addICACities(){
		$data = $this->curlObj->_simple_call('get','http://www.icaerp.com/WebWorkz/ICA.asmx/getCity');
		$baseCities = simplexml_load_string($data);
		$finalLocalityArray = array();
		foreach($baseCities as $city){
			$cityName = get_object_vars($city);
			$cityName = $cityName['@attributes']['CityName'];
			$finalLocalityArray[$cityName] = array();
			$data = $this->curlObj->_simple_call('get','http://www.icaerp.com/WebWorkz/ICA.asmx/getCentre?City='.urlencode($cityName));
			$localities = simplexml_load_string($data);
			foreach($localities as $locality){
				$localityName = get_object_vars($locality);
				$localityName = $localityName['@attributes']['Center_name'];
				$finalLocalityArray[$cityName][] = $localityName;
			}
		}
		$js = json_encode($finalLocalityArray);
		return $js;
	}
}
