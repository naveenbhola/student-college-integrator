<?php

class ChpCache extends Cache {
    private $standardCacheTime = 21600; //6 hours
    
    function __construct() {
		parent::__construct();
	}

    function setCHPCMSUserLockingInfo($chpId,$data){
        if($chpId){
            $expireInSeconds = 3600;
            $data = json_encode($data);
            $this->store('chpCmsUser_',$chpId, $data, $expireInSeconds);    
        }
    }

    function getCHPCMSUserLockingInfo($chpId){
        if($chpId){
            $data = $this->get('chpCmsUser_', $chpId);
            return json_decode($data,true);    
        }
    }

    function deleteCHPCMSUserLockingInfo($chpId){
        if($chpId){
            $this->delete("chpCmsUser_", $chpId);
            return;    
        }
    }

}
