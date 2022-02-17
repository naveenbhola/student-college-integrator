<?php
$headerComponents = array(
        'css'               => array('studyAbroadCommon','studyAbroadShipment'),
        'canonicalURL'      => $seoDetails['url'],
        'title'	=>$seoDetails['title'],
        'metaDescription' => $seoDetails['description'],
        'hideGNB'           => 'true',
        'hideLoginSignupBar' => 'true',
);
$this->load->view('common/studyAbroadHeader', $headerComponents);
echo jsb9recordServerTime('SA_SHIPMENT_CONFIRMATION_PAGE', 1);
?>
