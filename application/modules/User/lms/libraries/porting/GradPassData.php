<?php
include_once('AbstractPortingDataFetcher.php');
class GradPassData extends AbstractPortingDataFetcher{

    public function getData($ids,$params, $ported_item_id = array()){
        error_log("abcd :: inside getdata");
       return $this->portingmodel->getUserGradYearForPorting($ids,$params); 
    }
}

