<?php ob_start('compress');
$headerComponent = array('mobilecss'  => array('campusConnect_home'),
                         'pageName'   => $boomr_pageid,
                         'searchPage' => 'true',
                         'schemaName'=> 'cr',
                         'totalResult'=>6,
                         'inputKeyId'=>'keywordSuggest',
                         'container'=>'suggestions_container');

$this->load->view('mcommon5/header',$headerComponent);
?>
<div id="wrapper" style="background:#e5e5da;min-height: 413px;padding-top: 40px;" data-role="page" class="of-hide">
        <?php
                echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
                echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
        ?>
        <?php $this->load->view('campus_connect/ccHomepageHeader'); ?>
        <div data-role="content" style="background:#e6e6dc !important;">
        	<?php 
  			  $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
			?>
                <div data-enhance="false">
                <!-- Top search widget - start -->
                <?php $this->load->view('campus_connect/searchWidget');?>
                <!-- Top search widget - end -->
                
                <!-- College Card widget - start -->
                <?php echo modules::run('mCampusAmbassador5/CCHomepageController/campusConnectCollegeCardWidget',$programId,true); ?>
                <!-- College Card widget - end -->
                
                <!-- Question widget - start -->
                <?php $this->load->view('campus_connect/questionsWidget'); ?>
                <!-- Question widget - start -->
                
                <?php $this->load->view('mcommon5/footerLinks'); ?>
                </div>
        </div>
</div>
<div id="popupBasicBack" data-enhance='false'></div>

<script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion("jquery.flexslider-min","nationalMobile"); ?>"></script>
<?php $this->load->view('mcommon5/footer'); ?>
<script>
        $(document).ready(function(){
		
		$(document).click(function (e)
		{
			var container1 = $("#collegeCardDownList");
			if (!container1.is(e.target) && container1.has(e.target).length === 0) // ... nor a descendant of the container
			{
				$('#questionWidgetDropDownList').css({'z-index':9});
				$('#collegeCardDropDown').hide();
			}
			
			var container2 = $("#questionWidgetDropDownList");
			if (!container2.is(e.target) // if the target of the click isn't the container...
				&& container2.has(e.target).length === 0) // ... nor a descendant of the container
			{
				$('#questionWidgetDropDown').hide();
			}
		});

		programId = <?php echo $programId;?>;
		
		$('#topRatedQuestions').trigger('click');
		$('.college-Widget').flexslider({
		animation: "slide",
		slideshow : false,
		directionNav: false,
		controlNav : false,
		animationSpeed : 250,
		smoothHeight : true,
		useCSS : false,
		pauseOnAction : false,
		touch : true,
		easing : "linear",
		pauseOnHover: false,
		slideshowSpeed: 6000,
		after: function(slider){/* slider.pause(); slider.play();*/ }
	      });
	      
	      $('.slide-prev').on('click', function(){
		  $('.college-Widget').flexslider('prev')
		  return false;
	      });
	      
	      $('.slide-next').on('click', function(){
	      	lazyLoadImageForCC();
	      });
        });
        
        
        $(window).load(function() {
          setInstitutImage();
        });


	
function lazyLoadImageForCC(){
      	var sliderectedDiv;
   		var campusconnectsliderclass =$('.imageFinder');
   		campusconnectsliderclass.closest('.lazyLoadCC').each(function(key,ele){
     	if($(ele).hasClass('flex-active-slide')){
       	key +=2;
       	selectedDiv = campusconnectsliderclass[key];
      	 if(typeof(selectedDiv) != 'undefined'){
           var src = $(selectedDiv).attr("data-src");
           $(selectedDiv).attr("src", src);
      	 }
       	return;
     	}
   		});
   	
	  	$('.college-Widget').flexslider('next')
		 return false;
	
}






</script>
<?php ob_end_flush(); ?>
