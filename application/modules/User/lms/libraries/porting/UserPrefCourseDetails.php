<?php
include_once('AbstractPortingDataFetcher.php');
class UserPrefCourseDetails extends AbstractPortingDataFetcher{

    public function getData($ids,$params, $ported_item_id = array()){
       return $this->portingmodel->getUserPreferredCourseForPorting($ids,$params); 
    }
}

