<?php
    if($ajaxRequest){
        $rightSection = $this->load->view('abroadContentOrgRightSection',true);
        echo $rightSection;
        exit;
    }else{
        // abroad Content Org Page header
        $this->load->view('abroadContentOrgHeader');
        // abroad Content Org Page main content
        $this->load->view('abroadContentOrgMainContent');
        // abroad Content Org Page footer
        $this->load->view('abroadContentOrgFooter');
    }
?>
