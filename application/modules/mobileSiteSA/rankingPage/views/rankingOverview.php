<?php

 $this->load->view('rankingPageHeader');
 echo jsb9recordServerTime('SA_MOB_RANKINGPAGE',1);
 $this->load->view('rankingPageContent');
 $this->load->view('rankingPageFooter');
?>