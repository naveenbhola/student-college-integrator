<?php
    // if we need to add other elements having data-role = page it can be passed as below
$footerComponents = array(
    'js'=> array('shortlist'),
    'pages'=>array('commonModule/layers/brochureWithRequestCallback'),
    'trackingPageKeyIdForReg' => 484,
    'commonJSV2'=>true,
    'loadLazyJSFile'=>false,
);
$this->load->view('commonModule/footerV2',$footerComponents);
?>
<script>
    var courseToBeFocussed = '<?php echo $courseToBeFocussed;?>';
    var rmcCoursesCount = '<?php echo $rmcCourses;?>';
</script>