<?php
include_once('AbstractPortingDataFetcher.php');
class UserPrefSpecializationData extends AbstractPortingDataFetcher{

    public function getData($ids,$params, $ported_item_id = array()){
       return $this->portingmodel->getUserPrefSpecializationForPorting($ids,$params); 
    }
}