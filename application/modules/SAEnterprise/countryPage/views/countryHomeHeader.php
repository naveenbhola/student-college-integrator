<?php
    $headerData = array(
                        'extraCss'=>array(),
                        'extraJs'=>array('studyAbroadCountryPageCMS')
                        );
    // load header file from listingPosting module
    $this->load->view('listingPosting/abroad/abroadCMSHeader',$headerData);
?>