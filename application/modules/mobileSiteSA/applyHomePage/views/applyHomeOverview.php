<?php 
if(!$onRMCSuccessFlag){
    $this->load->view('applyHomePage/applyHomeHeader');
}
echo jsb9recordServerTime('SA_MOB_APPLY_HOMEPAGE',1);
$this->load->view('applyHomePage/applyHomeContent');
if(!$onRMCSuccessFlag){
    $this->load->view('applyHomePage/applyHomeFooter');
}
?>