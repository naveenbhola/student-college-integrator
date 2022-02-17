<?php
    ob_start('compress');
    $this->load->view('mobile_listing5/course/coursePageHeader');
    $this->load->view('mobile_listing5/course/coursePageContent');
    $this->load->view('mobile_listing5/course/coursePageFooter');
    ob_end_flush();
?>