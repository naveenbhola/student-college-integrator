<?php

class SearchPageLib {
    
    private $CI = '';
    
    function __construct(){
        $this->CI = &get_instance();
    }
    
    function insertIntoSearchTracking($data,$action){
        
        $this->CI->load->model("search/SearchModel", "", true);
	$this->searchModel = new SearchModel();
        $trackingId = $this->searchModel->insertIntoSearchTracking($data,$action);
        return $trackingId;
    }
    
}

