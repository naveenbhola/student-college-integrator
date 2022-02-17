<?php
include_once('AbstractPortingDataFetcher.php');
class UserEducationData extends AbstractPortingDataFetcher{

    public function getData($ids,$params, $ported_item_id = array()){
       return $this->portingmodel->getUserXIIYearForPorting($ids,$params); 
    }
}

