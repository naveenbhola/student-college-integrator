<?php
$footerComponents = array(
    'js'  			=>array('comparePageSA'),
    'pages'=>array('compareCourses/layers/compareCoursesSearch'),
    'trackingPageKeyIdForReg' => 615,
    'openSansFontFlag' => true,
    'commonJSV2'=>true,
    'loadLazyJSFile'=>false,
);
$this->load->view('commonModule/footerV2',$footerComponents);
?>
<script>
	var isComparePage=true;
	var compareOverlayTrackingKeyId = 620;
	var primaryCourseId = '<?php echo ($comparedCourseIds[0] > 0?$comparedCourseIds[0]:NULL); ?>';
	var secondaryCourseId = '<?php echo ($comparedCourseIds[1] > 0?$comparedCourseIds[1]:NULL); ?>';
</script>
