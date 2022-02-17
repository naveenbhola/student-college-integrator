<?php
	$footerComponents = array(
			    'js'                => array('studyAbroadContentOrgPage','jquery.royalslider.min','jquery.tinycarouselV2.min'),
                'asyncJs'           => array('jquery.royalslider.min','jquery.tinycarouselV2.min')
			);
	$this->load->view('common/studyAbroadFooter',$footerComponents);
?>
<div style="position: absolute; z-index: 9999;display:none" id="loadingImage" class="loader"><img src="//<?php echo IMGURL;?>/public/images/Loader2.GIF"> Loading</div>
<script>
	$j(document).ready(function(){
	contentOrgBottomWidgetSlider(contentOrgCurrentSlide);
	
	//Binding function check of "All" to check/uncheck All filter values
	$j('.filter-all').click(function(){
		$j('.filter-value').prop('checked',this.checked);
		setTimeout(function(){submitFilters()},50);
	});
	$j('.filter-value').click(function(event){
		if ($j('.filter-value').length == $j(".filter-value:checked").length) {
			$j('.filter-all').prop('checked',true);
		}else{
			$j('.filter-all').prop('checked',false);
		}
		setTimeout(function(){submitFilters()},100);
		
		
	});
	
	$j('#sub-filter-list li:last-child').addClass('last');
	$j('.filter-all').prop('checked',true);
	$j('.filter-value').prop('checked',true);
	
	});
	
	
</script>
