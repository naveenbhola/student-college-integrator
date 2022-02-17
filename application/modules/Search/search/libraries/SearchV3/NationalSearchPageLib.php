<?php

class NationalSearchPageLib {
	function __construct() {
		$this->CI =& get_instance();

		$this->CI->load->config("nationalCategoryList/nationalConfig");
        $this->field_alias = $this->CI->config->item('FIELD_ALIAS');
	}

	/*
     * Set the parameters with the keys as -
     * $filterData['stream']
     * $filterData['substream']
     * $filterData['specialization']
     * $filterData['base_course']
     * $filterData['education_type']
     * $filterData['delivery_method']
     * $filterData['credential']
     * $filterData['exam']
     *
     * Description -
     * Will return URL with query params set as parameters sent in the arguments. Each array key of the parameter can be single/multi-valued.
     */
	function getUrlByParams($filterData) {
		foreach ($filterData as $filter => $filterValue) {
			switch ($filter) {
				case 'stream':
				case 'substream':
				case 'specialization':
				case 'base_course':
				case 'education_type':
				case 'delivery_method':
				case 'course_level':
					if(!empty($filterValue)) {
						if(is_array($filterValue)) {
							foreach ($filterValue as $key => $value) {
								$queryParams[] = $this->field_alias[$filter]."[]=".urlencode($value);
							}
						} else {
							$queryParams[] = $this->field_alias[$filter]."=".urlencode($filterValue);
						}
					}
					break;
			}
		}

		$queryParams = implode("&",$queryParams);
		if(!empty($queryParams)) {
            $url = SHIKSHA_HOME.SEARCH_PAGE_URL_PREFIX.'?'.$queryParams;
            return $url;
		} else {
			return '';
		}
	}
}