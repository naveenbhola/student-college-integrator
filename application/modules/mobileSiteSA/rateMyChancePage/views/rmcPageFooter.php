<?php
$footerComponents = array(
    'js'              => array(),
    'skipRegistrationLayer'=>true,
//    'commonJSV2'=>true
);
$this->load->view('commonModule/footerV2',$footerComponents);
?>

<script>
    windowLoadDone = function()
    {
        initializeSearchBarV2();
    };
    shikshaUserRegistrationForm['<?=$regFormId?>'].getSAshikshaApplyFields({'ldbCourseId':'<?=$desiredCourse?>','context':'mobileRmcPage'});
</script>