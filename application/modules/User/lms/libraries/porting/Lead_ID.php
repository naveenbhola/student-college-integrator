<?php

include_once('AbstractPortingDataFetcher.php');
class Lead_ID extends AbstractPortingDataFetcher{

    public function getData($ids,$params,$ported_item_id){
        foreach($ids as $key=>$row) {
            $Lead[$row]['Lead_ID'] = "L".($ported_item_id[$key]);
        }
        return $Lead; 
    }
}