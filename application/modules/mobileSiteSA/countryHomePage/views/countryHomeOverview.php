<?php
    $this->load->view('countryHomeHeader');
    echo jsb9recordServerTime('SA_MOB_COUNTRYHOMEPAGE',1);
    $this->load->view('countryHomeContent');
    $this->load->view('countryHomeFooter');
?>