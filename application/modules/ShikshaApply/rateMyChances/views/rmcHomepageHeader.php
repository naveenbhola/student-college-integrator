<?php
$headerComponents = array(
        'css'               => array('studyAbroadCommon','studyAbroadAccountSetting'),
        'canonicalURL'      => $seoUrl,
        'title'	=>$seoDetails['title'],
        'metaDescription' => $seoDetails['description'],
        'hideGNB'           => 'true',
        'hideLoginSignupBar' => 'true',

);

$this->load->view('common/studyAbroadHeader', $headerComponents);

?>

