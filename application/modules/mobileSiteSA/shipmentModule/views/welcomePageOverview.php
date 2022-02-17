<?php
$this->load->view('shipmentModule/welcomePageHeader');
echo jsb9recordServerTime('SA_MOB_SHIPMENTWELCOMEPAGE',1);
$this->load->view('shipmentModule/welcomePageContent');
$this->load->view('shipmentModule/welcomePageFooter');
?>