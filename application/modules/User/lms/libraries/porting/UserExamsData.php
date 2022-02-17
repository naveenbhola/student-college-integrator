<?php
include_once('AbstractPortingDataFetcher.php');
class UserExamsData extends AbstractPortingDataFetcher{

    public function getData($ids,$params, $ported_item_id = array()){
       return $this->portingmodel->getCompetitiveExamsForPorting($ids); 
    }
}

