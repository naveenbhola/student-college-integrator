<?php
	$this->load->view('ranking_header');
	echo jsb9recordServerTime('SHIKSHA_MOB_NATIONAL_RANKING_PAGE',1);
	global $shiksha_site_current_url;global $shiksha_site_current_refferal;
?>

<script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion("jquery.flexslider-min","nationalMobile"); ?>"></script>
<script src="//<?php echo JSURL; ?>/public/mobile5/js/vendor/<?php echo getJSWithVersion("jquery.zebra.owlSlider.min","nationalMobileVendor"); ?>"></script>
<div id="wrapper" data-role="page" style="min-height: 413px;padding-top: 40px;">
	<?php echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel','true');
	      echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
	?>
	<!-- Show the Search page Header -->    
	<header id="page-header" class="header ui-header-fixed" data-role="header" data-tap-toggle="false"  data-position="fixed">
	    <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger=true);?>
	</header> 
	<!-- End the Search for Category page -->
	
	<?php   
		$mobile_details = json_decode($_COOKIE['ci_mobile_capbilities'],true);
		$screenWidth = $mobile_details['resolution_width'];
		$screenHeight = $mobile_details['resolution_height'];
    ?>	
	<input type="hidden" value="<?php echo $screenWidth;?>" id="screenwidth" />
	<input type="hidden" value="<?php echo $screenHeight;?>" id="screenheight" />
	<input type="hidden" value="<?php echo $rankingPageRequest->getStreamId();?>" id="stream" />
	<input type="hidden" value="<?php echo $rankingPageRequest->getSubstreamId();?>" id="substream" />
	<input type="hidden" value="<?php echo $rankingPageRequest->getSpecializationId();?>" id="specialization" />
	<input type="hidden" value="<?php echo $rankingPageRequest->getBaseCourseId();?>" id="baseCourse" />
    <div data-role="content">
    	<?php 
        	$this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    	?>   
		<!----subheader--->
        <?php $this->load->view('ranking_sub_header');?>
		<!--end-subheader-->
		<div data-enhance="false">
			<!-- Refine Header Starts-->
			<section class="content-wrap2 clearfix" style="box-shadow:none;">
				<div class="filter-head" id="rp_filter_cont" style="border-bottom: none;z-index: 1;">
					<div class="filter-head" id="rp-filter-head">
						<a class="" href="javascript:void(0);" onclick="toggleRankingFilterBox();" style="border-right:1px solid #e1e1e1">FILTER</a>
						<a class="" href="javascript:void(0);" onclick="toggleRankingSourceBox();">SORT</a>
					</div>
					<ul class="filter-list">
						<li id="rp_filterbox_cont" style="display:none">
							<label>Filter By:</label>
							<div class="filter-fields">
							<?php if($rankingPageRequest->getBaseCourseId() == 10){?>
								<?php $this->load->view('filter_course',array('number'=>1));?>
							<?php } ?>
							<?php if(in_array($rankingPageRequest->getBaseCourseId(), array(10,101))){ ?>
								<?php  $this->load->view('filter_exam',array('number'=>1));?>
							<?php } ?>
								<?php  $this->load->view('filter_location',array('number'=>1));?>
							</div>
					   </li>
						<li id="rp_sourcebox_cont" style="display:none">
							<label>Sort By: </label>
							<div class="filter-fields">
								<?php $this->load->view('filter_ranking_source',array('number'=>1));?>
							</div>
						</li>
					</ul>	
				</div>
			</section>
			<!-- Refine Header Ends-->
			<!-- Display Success message for Request E-Brochure -->
                        <!-- This cookie has been removed during shiksha 2.0 as there is no need to show this anymore-->
			<?php
			if (getTempUserData('confirmation_message_ins_page')){?>
			    <div>
				    <section class="top-msg-row">
					    <div class="thnx-msg">
						<i class="icon-tick"></i>
						<p>
						    <?php echo getTempUserData('confirmation_message_ins_page'); ?>
						</p>
					    </div>
				    </section>
			    </div>
			<?php
			}
			deleteTempUserData('confirmation_message_ins_page');
			deleteTempUserData('confirmation_message');
			deleteTempUserData('REB_LOC');
			?>
			<!-- Display Success message for Request E-Brochure Ends -->
			<?php
				if(isset($tracking_keyid)) {
					$ranking_table_data['tracking_keyid'] = $tracking_keyid; // Forward the trackingKey to the view ahead
				}
				$this->load->view('ranking_table_container', $ranking_table_data);
				echo "<div id='rankingAllInstitutes' style='display:none;'><div style='text-align:center;'><img id='loadingImage' border=0 alt='' style='margin:10px;'></div></div>";
				$this->load->view('ranking_relatedlinks');
			?>
			<div class="clearFix"></div>
		</div>
	<?php $this->load->view('/mcommon5/footerLinks'); ?>
	</div>
</div>

<div data-role="page" id="rankingCourseDiv" data-enhance="false"><!-- dialog--> 
</div>


<script>
function redirectRanking(id,number,event){
	var objName = id+'Selection'+number;
	url = $('#'+objName).val();
	label = $('#'+objName+' :selected').text();
	ranking_page_name = $('#mobileRankingPageName').val();
	if (id == 'source') {
		gaTrackEventCustom('MOBILE_RANKING_PAGE_'+ranking_page_name, 'sortBy-'+id, label, event, url);
	}
	else {
		gaTrackEventCustom('MOBILE_RANKING_PAGE_'+ranking_page_name, 'filter-'+id, label, event, url);
	}
	//window.location = url;
}
</script>
<?php
if( isset($tracking_keyid) ) { // Pass on the tracking key to the footer as well
	$this->load->view('mcommon5/footer', array('tracking_keyid' => $tracking_keyid));
} else {
	$this->load->view('mcommon5/footer');
}
?>


<?php
//Fetch the rest of the results usng AJAX only when the results are greater than 10
$rankingPageData = $rankingPage->getRankingPageData();
if( count($rankingPageData)>10  && $isAjax!='true' ){ 
    ?>
<script>
    var pageName ='ranking_page';
var show_recommendation = getCookie('show_recommendation');
var recommendation_course = getCookie('recommendation_course');
var hide_recommendation = getCookie('hide_recommendation');

$(document).ready(function(){
	//Make an AJAX call to fetch the Institutes from 20 - 100
	new loadToolstoDecideMBACollegeWidget("mbaToolsWidget","RANKING_MOBILE").loadWidget();
	$('#rankingAllInstitutes').show();
	jQuery.ajax({
		url: "<?=$_SERVER['REQUEST_URI']?>",
		type: "POST",
		data: {'isAjax':'true' },
		success: function(result){
			$('#rankingAllInstitutes').html(result);
		}
	});	
        
	if(show_recommendation == 'yes' && hide_recommendation != 'yes') {
		$(document).ready(function(){
				var isRankingPage = 'NO';
				var brochureAvailable = 'YES';
				var pageType = 'RANKING_MOB_Reco_ReqEbrochure';
		 		var screenWidth =  window.jQuery('#screenwidth').val();
				var screenHeight = window.jQuery('#screenheight').val();

				var urlRec = '/muser5/MobileUser/showRecommendation/'+recommendation_course+'/CP_Reco_popupLayer'+'/0/0/0/'+brochureAvailable+'/'+isRankingPage + '/' + pageType+'/0/<?php echo MOBILE_NL_RNKINGPGE_TUPLE_DEB ;?>';
				jQuery.ajax({
					url: urlRec,
					type: "POST",
					success: function(result)
					{
		        		   if((result.trim()) != ''){       	
								trackEventByGAMobile('HTML5_RECOMMENDATION_RANKING');
								setCookie('show_recommendation','no',30);
    								setCookie('recommendation_course','no',30);
								$('#recomendation_layer_listing').html(result);							
								$('#popupBasic-popup').css('width',screenWidth);
								$('#popupBasic-popup').css('max-width',screenWidth);

								var window_width = $('#wrapper').width();
								var popup_width = window_width - 5 ;
								
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
		});
	}

	setCookie('hide_recommendation','no',30);        
	setCookie('show_recommendation','no',30)
	
	initializeComparedTuples();
});
</script>
<?php } ?>

<script type="text/javascript">
	function rankingPageStickyFilters() {
	var scrollTop = $(window).scrollTop();
	if($('#rp_filterbox_cont').css('display') != 'none'  ||  $('#rp_sourcebox_cont').css('display') != 'none'){
        	 $("#rp_filterbox_cont, #rp_sourcebox_cont").slideUp();
		}
	var headerHeight = $('header').height();
//        var headerHeight = $('.head-group').height();
	var tableContTop = $('#rtc_main').offset().top;
	var relatedLinksTop = $('#relatedlinks_section').offset().top;
	if( ( scrollTop + headerHeight >=  tableContTop ) && ( scrollTop + headerHeight < relatedLinksTop)) {
		var top = headerHeight ;
		$('#rp_filter_cont').css({ position : "fixed", top : top + "px", left : 0});
	} else {
		$('#rp_filter_cont').css({ position : "", top : "", left : ""});
	}
}
</script>
