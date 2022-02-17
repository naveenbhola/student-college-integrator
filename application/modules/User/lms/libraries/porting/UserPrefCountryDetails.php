<?php
include_once('AbstractPortingDataFetcher.php');
class UserPrefCountryDetails extends AbstractPortingDataFetcher{

    public function getData($ids,$params, $ported_item_id = array()){
       return $this->portingmodel->getUserPreferredCountryForPorting($ids,$params); 
    }
}

