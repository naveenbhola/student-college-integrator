<?php
include_once('AbstractPortingDataFetcher.php');
class Time extends AbstractPortingDataFetcher{

    public function getData($ids,$params, $ported_item_id = array()){
        $time = array();
        
        foreach($ids as $row) {
	
            $time[$row]['Time'] = date("H:i:s");
        
        }
        return $time; 
    }
}