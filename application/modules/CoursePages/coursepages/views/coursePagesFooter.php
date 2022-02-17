</div>
<div class="avgRating-tooltip" style="display: none;width: 286px;">
<i class="common-sprite avg-tip-pointer" style="left:102px"></i>
<p>Rating is based on actual reviews of students who studied at this college</p>
</div>

<?php
	$this->load->view('common/footerNew',array('loadJQUERY' => 'YES'));
?>

<script type="text/javascript">
$j(window).load(function(){
	$j("img.lazy").lazyload({effect : "fadeIn",threshold : 100}); 
});
</script>

<?php

if($selectedTab == "Home") {
?>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('jquery.royalslider.min'); ?>"></script>
<script>
if($j('.featured-slider').length > 0){
   $j(document).ready(function($j) {  
	   
	var control_nav = "";
	if(count_of_featured_slide >1) {
		control_nav = 'bullets';
	}  
	 
	$j('#royalSlider_cp_featured').royalSlider({
	 controlNavigation: control_nav,
	 autoScaleSlider: true,
	 autoHeight:false, 
	 //autoScaleSliderHeight:600,
	 loop: true,
	 numImagesToPreload:4,
	 arrowsNavAutohide: true,
	 arrowsNavHideOnTouch: true,
	 keyboardNavEnabled: true,
	 autoPlay: {		 
		     enabled: true,
		     stopAtAction:false,
		     pauseOnHover: true,
		     delay:5000		  
	}
       });
     
      if($j('#RecommendationWidgetLoad').length > 0) {
	 pushCustomVariable('Recommendations/CoursePageLoad');
      }
   });
   
}

var regFormPrefillValues = JSON.parse('<?php echo json_encode($regFormPrefillValues); ?>');

$j(document).ready(function($j) {  
	setScrollbarForMsNotificationLayer();
});
</script>
<script>
$j(window).bind("load",updateCoursePageFeaturedInstituteTracking);
</script>
<?php
} // End of if($selectedTab == "Home").
?>
<script>
//triggerCoursePagesTabsScrolling();
</script>
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('coursePagesEditor'); ?>" type="text/css" rel="stylesheet" />
