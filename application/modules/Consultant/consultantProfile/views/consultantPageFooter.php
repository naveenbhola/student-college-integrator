<?php
	$footerComponents = array(
			    'js'                => array('jquery.royalslider.min','jquery.tinycarouselV2.min','studyAbroadConsultantProfile'),
                'asyncJs'           => array('jquery.royalslider.min','jquery.tinycarouselV2.min')
			);
	$this->load->view('common/studyAbroadFooter',$footerComponents);
?>
<script>
	$j(document).ready(function() {
	intilizeslider();
	initializeConsultantOverviewSection();
	initializeMoreButton();
	bindScrollBarForAllTab();
	adjustConsultantDescription();
	prepareRoyalSliderForPhotos();
	// hide help text
	setTimeout(function(){ $j("#courseGutterHelpText").fadeOut(3000); },15000);
    });
</script>