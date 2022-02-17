<?php
	$footerComponents = array(
			    'js'                => array('studyAbroadCommon','studyAbroadListings'),
				//'asyncJs'           => array('jquery.royalslider.min','jquery.tinycarouselV2.min'),
			);
	$this->load->view('common/studyAbroadFooter',$footerComponents);
?>
<script type="text/javascript">
var isSignupThankYouPage= true;
var customReferer = '<?php echo str_replace("'","\\'",$customReferer); ?>';
var refererTitle = '<?php echo str_replace("'","\\'",$refererTitle); ?>';
 $j(window).on('load',function() {
	$j("#startDownload").trigger('click');
 });
</script>
