<?php
include_once('AbstractPortingDataFetcher.php');
class UserPrefCityData extends AbstractPortingDataFetcher{

    public function getData($ids,$params,$ported_item_id = array()){
       return $this->portingmodel->getUserPrefCityForPorting($ids,$params); 
    }
}

