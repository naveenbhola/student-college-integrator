<?php
include_once('AbstractPortingDataFetcher.php');
class LDB_Residence_State extends AbstractPortingDataFetcher{

    public function getData($ids,$params, $ported_item_id = array()){
        
        return $this->portingmodel->getStateForLDBUser($ids,$params); 
    
    }
}

