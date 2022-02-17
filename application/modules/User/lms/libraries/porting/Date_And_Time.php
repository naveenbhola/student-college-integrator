<?php
include_once('AbstractPortingDataFetcher.php');
class Date_And_Time extends AbstractPortingDataFetcher{

    public function getData($ids, $ported_item_id = array()){
        
		$date = array();
        $date1= date("Y-m-d");
		$date2= date("H:i:s");
        
		foreach($ids as $row) {
            $date[$row]['Date_And_Time'] = $date1.",".$date2;
		}
		
        return $date; 
    }
}