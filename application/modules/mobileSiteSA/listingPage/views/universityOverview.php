<?php

    $this->load->view('universityPageHeader');
    echo jsb9recordServerTime('SA_MOB_UNIVERSITYPAGE',1);
    
    $this->load->view('universityPageContents');
    
    $this->load->view('universityPageFooter');
    
?>