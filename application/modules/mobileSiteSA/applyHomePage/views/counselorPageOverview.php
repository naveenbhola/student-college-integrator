<?php
    $this->load->view('counselorPageHeader');
    echo jsb9recordServerTime('SA_MOB_COUNSELORPAGE',1);
    $this->load->view('counselorPageContent');
    $this->load->view('counselorPageFooter');
?>