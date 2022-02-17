<?php
include_once('AbstractPortingDataFetcher.php');
class UserBasicData extends AbstractPortingDataFetcher{

    public function getData($ids,$params, $ported_item_id = array()){
       return $this->portingmodel->getBasicUserDetailsForPorting($ids,$params); 
    }
}

