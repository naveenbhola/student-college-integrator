<?php
	$this->load->view('rateMyChancePage/successPageHeader');
	echo jsb9recordServerTime('SA_MOB_SUCCESSPAGE',1);
	$this->load->view('rateMyChancePage/successPageContent');
	$this->load->view('rateMyChancePage/successPageFooter');
?>
