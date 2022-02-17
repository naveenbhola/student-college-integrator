<?php

$this->load->view('contentNavbarPosting/navbarPostingHeader');
// load left navigation file from listingPosting module
$this->load->view('listingPosting/abroad/abroadCMSLeftColumn');
$this->load->view('contentNavbarPosting/navbarPostingPageContent');
$this->load->view('contentNavbarPosting/navbarPostingFooter');

?>
