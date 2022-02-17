<?php
	$this->load->view('rateMyChancePage/rmcPageHeader');
	echo jsb9recordServerTime('SA_MOB_RMCPAGE',1);
	$this->load->view('rateMyChancePage/rmcPageContent');
	$this->load->view('rateMyChancePage/rmcPageFooter');
?>
