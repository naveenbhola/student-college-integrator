<?php
include_once('AbstractPortingDataFetcher.php');
class UserCityData extends AbstractPortingDataFetcher{

    public function getData($ids,$params , $ported_item_id = array()){
       return $this->portingmodel->getUserCityForPorting($ids,$params); 
    }
}

