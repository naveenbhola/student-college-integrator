<?php 
class commonFormLib{
	function __construct(){
		$this->CI = & get_instance();
	}
	public function getFinalConfig($common, $custom){
		return array_merge($common, $custom);
	}

	function getFormattedArray($data){
		$this->CI->load->config('common/hierarchyElementMapping');
		$locationConstituentMapping = $this->CI->config->item('locationConstituentMapping');
		$instituteConstituentMapping = $this->CI->config->item('instituteConstituentMapping');
		foreach ($data as $key => $value) {
			$index = $value['entityType'].'Id';
			if(in_array($value['entityType'], $locationConstituentMapping)){
				$returnArray['location'][$value['entityType']][$value['entityId']] = $value['entityId'];
			}
			elseif(in_array($value['entityType'], $instituteConstituentMapping)){
				$returnArray['institute'][$value['entityType']][$value['entityId']] = $value['entityId'];
			}
			elseif($value['entityType'] == 'primaryHierarchy'){
				$returnArray['hierarchyId'][$value['entityId']] = $value['entityId'];
				$returnArray['primaryHierarchyId'] = $value['entityId'];
			}
			else{
				$returnArray[$index][$value['entityId']] = $value['entityId'];
			}
		}
		return $returnArray;
	}

	function sortEntityByName($args, $arrayToSort){
      	usort($arrayToSort, function($a, $b) use($args) { 
        $i = 0; 
        $c = count($args); 
        $cmp = 0; 
        while($cmp == 0 && $i < $c) { 
            $cmp = strcasecmp($a[$args[$i]], $b[$args[$i]]); 
            $i++; 
        }
        return $cmp;
    	}); 
      return $arrayToSort;
	}
}