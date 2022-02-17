<?php
    $this->load->view('categoryPage/countryPageHeader');
    echo jsb9recordServerTime('SA_MOB_COUNTRYPAGE',1);
    $this->load->view('categoryPage/countryPageContent');
    $this->load->view('categoryPage/countryPageFooter');
?>