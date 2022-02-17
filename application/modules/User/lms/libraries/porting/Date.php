<?php
include_once('AbstractPortingDataFetcher.php');
class Date extends AbstractPortingDataFetcher{

    public function getData($ids,$params,$ported_item_id = array()){
        $date = array();
       
        foreach($ids as $row) {
	
            $date[$row]['Date'] = date("Y-m-d");
	
        }
        return $date; 
    }
}