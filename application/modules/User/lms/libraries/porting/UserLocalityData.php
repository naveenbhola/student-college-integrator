<?php
include_once('AbstractPortingDataFetcher.php');
class UserLocalityData extends AbstractPortingDataFetcher{

    public function getData($ids,$params, $ported_item_id = array()){
       return $this->portingmodel->getUserLocalityForPorting($ids,$params); 
    }
}

