<?php
include_once('AbstractPortingDataFetcher.php');
class StateTable extends AbstractPortingDataFetcher{

    public function getData($ids,$params, $ported_item_id = array()){
        
        return $this->portingmodel->getStateForLDBUser($ids,$params); 
    
    }
}

