<?php
    $extraJs = array('studyAbroadNavbarPosting');
    $headerData = array(
                        'extraCss'=>array('abroad-councelling-cms'),
                        'extraJs'=>$extraJs
                        );
    // load header file from listingPosting module
    $this->load->view('listingPosting/abroad/abroadCMSHeader',$headerData);
?>