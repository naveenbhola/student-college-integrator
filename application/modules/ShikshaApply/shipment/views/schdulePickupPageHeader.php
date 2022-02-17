<?php
$headerComponents = array(
        'css'               => array('studyAbroadCommon','studyAbroadShipmentBooking'),
        'canonicalURL'      => $seoDetails['url'],
        'title'	=>$seoDetails['title'],
        'metaDescription' => $seoDetails['description'],
        'hideGNB'           => 'true',
        'hideLoginSignupBar' => 'true',
);
$this->load->view('common/studyAbroadHeader', $headerComponents);
?>
