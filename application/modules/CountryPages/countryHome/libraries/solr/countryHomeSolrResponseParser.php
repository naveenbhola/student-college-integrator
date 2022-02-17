<?php 
class countryHomeSolrResponseParser
{
	function __construct(){
        $this->CI = & get_instance();
    }

   public function parseCourseData($data){
   		$finalArr = array();
   		foreach ($data['response']['docs'] as $key => $value) {
   			$finalArr[$value['saCourseId']] = $value;
   		}
   		return $finalArr;
   }
}