<?php 
$headerComponents = array(
    //'cssBundleMobile'         => 'sa-rmc-mobile',
    'jsRequiredInHeader'=> array("userRegistration","ajax-api","commonSA","registrationSA","scholarshipResponseSA","contentSA"),
    'canonicalURL'    => $seoUrl,
    'title'           => $seoDetails['title'],
    'metaDescription' => $seoDetails['description'],
    'hideSeoRevisitFlag' => true,
    'hideSeoRatingFlag' => true,
    'hideSeoPragmaFlag' => true,
    'hideSeoClassificationFlag' => true,
    'hideHeader'        => true,
    'robotsMetaTag' => $robots,
    'metaKeywords'    => '',
    'firstFoldCssPath'    => 'rateMyChancePage/css/rateMyChanceFirstFoldCss',
    'deferCSS' => true,
    'dontLoadRegistrationJS' => true
);
$this->load->view('commonModule/headerV2',$headerComponents);

//echo jsb9recordServerTime('SA_MOB_SIGNUPPAGE',1);
//main content
echo $mainContent; // earlier this used to be layer
// footer
$footerComponents = array(
    'js'              => array(),
    'skipRegistrationLayer' => true,
    'hideFooter' => true,
    // 'commonJSV2'=>true
);
$this->load->view('commonModule/footerV2',$footerComponents);
?>
<script>
<?php if(count($abroadDesiredCourseIds)>0) { 
    global $studyAbroadPopularCourseToLevelMapping;
    ?>
    var abroadDesiredCourseIds = JSON.parse('<?php echo json_encode($abroadDesiredCourseIds); ?>');
    var abroadDesiredCourseLevels = JSON.parse('<?php echo json_encode($studyAbroadPopularCourseToLevelMapping); ?>');
<?php } ?>
</script>
