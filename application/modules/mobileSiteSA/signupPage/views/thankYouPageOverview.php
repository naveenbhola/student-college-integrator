<?php 
$jsList = array("jquery-ui.min","userRegistration","ajax-api","commonSA","registrationSA");
if($downloadMessageType == "downloadGuide"){
    array_push($jsList,"contentSA");
}
$headerComponents = array(
    'jsRequiredInHeader'=> $jsList,
    'canonicalURL'    => $seoUrl,
    'title'           => $seoDetails['title'],
    'metaDescription' => $seoDetails['description'],
    'hideSeoRevisitFlag' => true,
    'hideSeoRatingFlag' => true,
    'hideSeoPragmaFlag' => true,
    'hideSeoClassificationFlag' => true,
    'robotsMetaTag' => $robots,
    'metaKeywords'    => '',
    'firstFoldCssPath'    => 'signupPage/css/thankYouPageFirstFoldCss',
    'deferCSS' => true,
    'dontLoadRegistrationJS' => true
);
$this->load->view('commonModule/headerV2',$headerComponents);

if($downloadMessageType == 'downloadBrochure' || $downloadMessageType == 'scholarshipDownloadBrochure'){
    $this->load->view('signupPage/thankYouPageDownloadBrochure');
}else{
    $this->load->view('signupPage/thankYouPageDownloadGuide');
}
// footer
$this->load->view('signupPage/thankYouPageFooter');
?>