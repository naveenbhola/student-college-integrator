<?php
$this->load->view('scholarshipDetailPage/scholarshipDetailPageHeader');
echo jsb9recordServerTime('SA_MOB_SCHOLARSHIPDETAILPAGE',1);
$this->load->view('scholarshipDetailPage/scholarshipDetailPageContent');
$this->load->view('scholarshipDetailPage/scholarshipDetailPageFooter');
?>