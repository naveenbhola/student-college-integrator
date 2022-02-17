<?php

$headerComponents = array(
    'cssBundleMobile' => 'sa-in-line-login-mobile',
    'canonicalURL'    => '',
    'title'           => 'Login Page',
    'metaDescription' => $metaDescription,
    'hideSeoRevisitFlag' => true,
    'pgType'	        => $pgType,
    'robotsMetaTag' => $robots,
    'metaKeywords'    => '',
    'firstFoldCssPath'    => 'commonModule/inLineLoginForm/css/inLineLoginFirstFoldCss',
    'deferCSS' => true
);
$this->load->view('commonModule/headerV2',$headerComponents);

echo jsb9recordServerTime('SA_MOB_LOGINPAGE',1);
$this->load->view("registration/loginInLineStudyAbroadMobile");

$footerComponents = array(
    'js'=> array('inLineLogin','contentSA','scholarshipResponseSA'),
    'pages'=>array(),
    'commonJSV2'=>true,
    'loadLazyJSFile'=>false,
);
$this->load->view('commonModule/footerV2',$footerComponents);
?>
