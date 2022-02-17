<?php
	$footerComponents = array(
			    'js'                => array('studyAbroadListings','studyAbroadApplyHome','jquery.royalslider.min','jquery.tinycarouselV2.min'),
                'asyncJs'           => array('jquery.royalslider.min','jquery.tinycarouselV2.min'),
                'hideHTML'          => "true",
                'hideFeedbackHTML' => true
			);
	$this->load->view('common/studyAbroadFooter',$footerComponents);
?>
<script>
var rmcCourseId = '<?php echo $courseId; ?>';
var pageIdentifier = 'rmcSuccessPage';
$j(window).on('load',function(){ 
    initializeApplyPageWidgets(); 
});
</script>
<!-- Google Code for registration Conversion Page -->
<!--
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1053765138;
var google_conversion_language = "en_GB";
var google_conversion_format = "1";
var google_conversion_color = "ffffff";
var google_conversion_label = "O3WQCOaXRRCS3Lz2Aw";
var google_conversion_value = 1.00;
var google_conversion_currency = "INR";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1053765138/?value=1.00&amp;currency_code=INR&amp;label=O3WQCOaXRRCS3Lz2Aw&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
-->
