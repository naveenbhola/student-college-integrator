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
echo jsb9recordServerTime('SA_SCHEDULE_PICKUP_PAGE', 1);
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">