<?php ob_start('compress'); ?>
<?php $this->load->view('/mcommon5/header');
echo jsb9recordServerTime('SHIKSHA_MOB_NATIONAL_COLLEGE_LISTINGS',1);
?>
<?php
global $shiksha_site_current_url;
global $shiksha_site_current_referral ;
global $instituteHeaderImage1;
global $instituteHeaderImage2;
$courses = $institute->getCourses();
?>
<script src="//<?php echo JSURL; ?>/public/mobile5/js/vendor/<?php echo getJSWithVersion("jquery.zebra.owlSlider.min","nationalMobileVendor"); ?>"></script>

	<div id="popupBasic" style="display:none">	
		<header class="recommen-head" style="border-radius:0.6em 0.6em 0 0;">
       <p style="width:205px;" class="flLt">Students who showed interest in this institute also looked at</p>
       <a href="#" class="flRt popup-close" onclick = "$('#popupBasic').hide();$('#popupBasicBack').hide();">&times;</a>
       <div class="clearfix"></div>
       </header>
		<div id="recomendation_layer_listing" style="margin-top:20px;margin-bottom:20px;"></div>
	</div>
<div id="popupBasicBack" data-enhance='false'>	
</div>


<div id="wrapper" data-role="page" style="min-height: 413px;padding-top: 40px;">
	<?php $this->load->view('listingHeader'); ?>
        <div data-role="content">
		<!----subheader--->
	       <?php $this->load->view('listingSubHeader');?>
	       <!--end-subheader-->
	<div>

	            
	<?php $this->load->view('listingTabs'); ?>
	<section class="content-wrap2" style="margin-bottom:0;padding-top:0.7em;">   
		<?php $this->load->view('mobile_institute_header_images');
		
		if($abTestVersion == 1 && !empty($course_browse_section_data)){
			$this->load->view('mobile_institute_select_course');
		}
        ?>
		<?php //$this->load->view('mobile_institute_alumni_widget');?>		
		<?php $this->load->view('mobile_contact_details');?>
		<?php $this->load->view('mobile_why_join');?>
		<?php
			if(isset($dominantSubCatData) && $dominantSubCatData == "23"){
				?>
				<?php if(in_array($institute_id, $IIMColleges)){
					echo Modules::Run('mIIMPredictor5/IIMPredictor/getIIMCallPredictorWidget',$fromPage);
					 } else { ?>
					<h2 class="ques-title"><p>Tools to decide your MBA College</p></h2>
						<div id="mbaToolsWidget" data-enhance="false">
							<div style="margin: 20px; text-align: center;"><img border="0" src="/public/mobile5/images/ajax-loader.gif"></div>
		                    <?php
		                        echo $collegeReviewWidget;  
		                    ?>
		                </div>
					<?php	
					}
			 }
		?>
		<?php $this->load->view('mobile_institute_review_detail');?>
		<!--load campus rep page-->
		<?php if(isset($campusConnectAvailable) && $campusConnectAvailable){
			$this->load->view('mobile_institute_campusrep');}
		?>
		<?php $this->load->view('mobile_institute_description');?>
	</section>

	<div id="alsoOnShiksha">
	</div>

	
<!-- Start: Load the Slideshow for Header images -->
<script>
if(listingInstitutePageFlagAccordion){
listingInstitutePageFlagAccordion = false;		
	$("#owl-example").owlCarousel({
	'slideSpeed': 500,
	'autoplay': true,
	'paginationSpeed': 500,
	'pagination' : true
	});
var owl = $(".owl-carousel").data('owlCarousel');
slideshowAutoplay();
}
	
//Develop autoplay for the Carousel since the Automated one is not working
var currentSlide = 0;
function slideshowAutoplay(){
setTimeout(function(){
	if(currentSlide==2){
	owl.goTo(0);
	currentSlide=0;
	}
	else{
	owl.next();
	currentSlide++;
	}
	slideshowAutoplay();
	},5000);
}
</script>
<!-- End: Load the Slideshow for Header images -->

	<div id="reb_sticky_button" style="width:100%;padding:10px 0 15px;">
		<a class="button blue small" style="display: block; font-size: 1.2em;  margin: 0 auto; width: 85%;" id="request_ebrochure_details" href="javascript:void(0);" onClick="$('#tracking_keyid<?php echo $course->getId();  ?>').val('<?=$bottomdTrackingPageKeyId?>');$('#brochureForm<?php echo $course->getId();  ?>').submit();"><p style="text-align:center;"><i style="float:none;left:-4px;top:2px;" class="icon-pencil" aria-hidden="true"></i><span>Request Free E-Brochure</span></p></a>
	</div>
	<script>//setTimeout(function(){hideShowREBButton("request_ebrochure_details",'alsoOnShiksha','<?php echo $course->getId();  ?>', 'institute')},1000);</script>
	

    </div>
	<?php $this->load->view('/mcommon5/footerLinks');?>
	
	<?php
	 if($call_back_yes == 1):
	 ?>
	 <a  href="#callbackpopup" data-position-to="window" data-inline="true" data-rel="popup" id="callbacklink" data-transition="pop" ></a>
	 <div data-role="popup" id="callbackpopup" data-theme="d" style="background:#EFEFEF;width: 92%;left: 4%;right: 4%; top:10%;">
	    <div style="padding: 12px; font-size:1.0em;" data-theme="d">
	    <?php $this->load->view("callBackLayer");?>
	    </div>
	 </div>
	 <?php
	 endif;
	 ?>
		
        </div>
	
</div>
<a href="#callbackpopup" data-position-to="window" data-inline="true" data-rel="popup" id="callbacklink" data-transition="pop" ></a>
<div data-role="popup" id="callbackpopup" data-theme="d" style="background:#EFEFEF;width: 92%;left: 4%;right: 4%; top:10%;">
</div>


<div data-role="page" id="subcategoryDiv" data-enhance="false"><!-- dialog--> 
</div>

<?php if(isset($campusConnectAvailable) && $campusConnectAvailable){?>
<script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion("jquery.flexslider-min","nationalMobile");?>"></script>
<?php }?>

<?php $this->load->view('/mcommon5/footer');?>

<?php
if($call_back_yes == 1):
?>
<script>
//call back patching	
$(document).ready(function() {
       $('#callbacklink').click();
});
</script>
<?php
endif;
?>

<img id = 'beacon_img' src="/public/images/blankImg.gif" width=1 height=1 >
<?php 
deleteTempUserData('confirmation_message');
?>

<script>
var campusConnectAvailable = '<?php echo $campusConnectAvailable;?>';
var instId = '<?php echo $institute_id;?>';
var trackingPageKeyId='<?php echo $qtrackingPageKeyId;?>';
//call back patching	
var call_back_data = '<?php echo $call_back_data;?>';
$(document).ready(function() {

	new loadToolstoDecideMBACollegeWidget("mbaToolsWidget","INSTITUTE_LISTING_MOBILE").loadWidget();
	$('#callbackpopup').html(call_back_data);
	$('#callbacklink').click();
});

</script>


<script>

$(document).ready(function(){
if($('#headerthumb1')){ $('#headerthumb1').attr('src','<?=$instituteHeaderImage1?>'); }
if($('#headerthumb2')){ $('#headerthumb2').attr('src','<?=$instituteHeaderImage2?>'); }

$.Zebra_Accordion('#instituteDesc',{
        'hide_speed' : 1,
        'show_speed' : 1,
        'scroll_speed': 1,
        'onClose'    : function(id){$('#desc'+id).attr('class', 'icon-arrow-up');},
        'onOpen'     : function(id){$('#desc'+id).attr('class', 'icon-arrow-dwn');}
});
//Call Beacon for View count
var img = document.getElementById('beacon_img');
var randNum = Math.floor(Math.random()*Math.pow(10,16));
img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0010004/<?=$institute->getId()?>+institute';

var courseId = '<?php echo $course->getId();  ?>';
var show_recommendation = getCookie('show_recommendation');	
var recommendation_course = getCookie('recommendation_course');	
var hide_recommendation = getCookie('hide_recommendation');	

<?php if($responseCreatedInstituteId>0 && $responseCreatedCourseId>0){ ?>
CP_lastREBCourseId = '<?=$responseCreatedCourseId?>';
CP_lastREBInstituteId = '<?=$responseCreatedInstituteId?>';
<?php } ?>

if(show_recommendation == 'yes' && hide_recommendation != 'yes') {	
	var isRankingPage = 'NO';
	var brochureAvailable = 'YES';
	var pageType = 'LP_MOB_Reco_ReqEbrochure';
	var courseId = '<?php echo $course->getId(); ?>';	
	var screenWidth =  window.jQuery('#screenwidth').val();
	var trackingPageKeyId = '<?php echo $recommendationTrackingPageKeyId;?>';
var screenHeight = window.jQuery('#screenheight').val();
   	
	var urlRec = '/muser5/MobileUser/showRecommendation/'+courseId+'/CP_Reco_popupLayer'+'/0/0/0/'+brochureAvailable+'/'+isRankingPage +'/' + pageType+ '/ \'\' /0/'+trackingPageKeyId;
	jQuery.ajax({
	    url: urlRec,
	    type: "POST",
	    success: function(result)
	    {
		        		   if((result.trim()) != ''){       	
								trackEventByGAMobile('HTML5_RECOMMENDATION_LISTING');
								setCookie('show_recommendation','no',30);
    				setCookie('recommendation_course','no',30);
								$('#recomendation_layer_listing').html(result);							
								$('#popupBasic-popup').css('width',screenWidth);
								$('#popupBasic-popup').css('max-width',screenWidth);

								var window_width = $('#wrapper').width();
								var popup_width = window_width - 5 ;
								
								$('#recomendation_layer_listing').css('max-height',screenHeight);
							
								$('#recomendation_layer_listing').css('height',screenHeight);
							
								$('#recomendation_layer_listing').css('overflow-y','scroll');
								
								var top_pos = 10 + $('body').scrollTop() + 'px';
								$('#popupBasic').css({'position':'absolute','z-index':'99999' , 'cursor' : 'pointer' , 'top':top_pos , 'background-color' : '#efefef' , 'margin' : '5px' , 'width' : popup_width });
$('#popupBasic').addClass('ui-popup ui-overlay-shadow ui-corner-all ui-body-c');

//$('#wrapper').css({'background' : '#000' , 'z-index' : '100' , 'opacity' : '0.4'})

								var window_height = $(document).height();
								var window_width = $('#wrapper').width();
$('#popupBasicBack').css({'background' : '#000' , 'opacity' : '0.4' , 'z-index' : '9999' , 'display' : 'block' , 'width'  : window_width , 'height' : window_height , 'position':'absolute'});



								$('#popupBasic').show();
						}
	    },
	    error: function(e){
	    }
	 });
	 		            	
}

setCookie('hide_recommendation','no',30);        
setCookie('show_recommendation','no',30);


});

function trackRequestEbrochure(courseId){
      try{
      var listing_id  = courseId;
      _gaq.push(['_trackEvent', 'HTML5_InstituteListing_Page_Request_Ebrochure', 'click', listing_id]);
      }catch(e){}
}   

</script>
<?php ob_end_flush(); ?>
