<?php
    $this->load->view('coursePageHeader');
    echo jsb9recordServerTime('SA_MOB_LISTINGPAGE',1);

    $this->load->view('coursePageContents');
    
    $this->load->view('coursePageFooter');
?>