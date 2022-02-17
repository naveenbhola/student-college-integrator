<?php $this->load->view('/mcommon5/header');
echo jsb9recordServerTime('SHIKSHA_MOB_NATIONAL_COURSE_LISTINGS',1);
?>
<script>var responseCounter = false;</script>
<?php
global $shiksha_site_current_url;
global $shiksha_site_current_referral ; 
global $companiesLogoArray;
?>
<?php if(isset($_COOKIE['MOB_A_C'])){

	$data['appliedCourseArr'] = explode(',',$_COOKIE['MOB_A_C']);

}
?>
<?php
   deleteTempUserData('confirmation_message_ins_page');
   deleteTempUserData('confirmation_message');
?>
<script src="//<?php echo JSURL; ?>/public/mobile5/js/vendor/<?php echo getJSWithVersion("jquery.zebra.owlSlider.min","nationalMobileVendor"); ?>"></script>
	<div id="popupBasic" style="display:none">
	

    	<header class="recommen-head" style="border-radius:0.6em 0.6em 0 0;">
	<p style="width:210px;" class="flLt">Students who showed interest in this institute also looked at</p>
	<a href="#" class="flRt popup-close" onclick = "$('#popupBasic').hide();$('#popupBasicBack').hide();">&times;</a>
	<div class="clearfix"></div>
	</header>
	
		<div id="recomendation_layer_listing" style="margin-bottom:20px;"></div>
	</div>
<div id="popupBasicBack" data-enhance='false'>	
</div>
<!-- for email result registration -->
<div style="display: none;">
        <form method="post" action="/muser5/MobileUser/register" id="emailResultsForm">
                <input type="hidden" name="current_url" value="<?=url_base64_encode($shiksha_site_current_url)?>">
                <input type="hidden" name="referral_url" value="<?=url_base64_encode($shiksha_site_current_refferal)?>">
                <input type="hidden" name="from_where" value="MOB_ONLINE_APPLY">
                <input type="hidden" name="tracking_keyid" id="tracking_keyid_start" value="">
        </form>
</div>
            <!-- For Email result message -->
<div id="popupBasicBackground"></div>
<div class="popup-layer" id="popupBasic-Success" style="display: none;" >
        <header class="layer-head">
        <p id="popup-head"></p>
        <a href="javascript: void(0)" class="close-box" onclick = "closeConfirmLayer();" id="closeBtn">&times;</a>
        <div class="clearfix"></div>
        </header>
        <div class="layer-content" id="successLayer1">
                <p style="margin-bottom: 10px" id="success-msg">You have successfully started your application. We have sent you an email with the link to your application. Open that link on the desktop/laptop for an easy and smooth process to complete your submission.</p>
        </div>
</div>

<div id="wrapper" data-role="page" style="min-height: 413px;padding-top: 40px;" itemprop="" itemscope itemtype="http://schema.org/WebPage">

	<?php $this->load->view('listingHeader'); ?>
	
        <div data-role="content">
	       <!----subheader--->
	       <?php $this->load->view('listingSubHeader');?>
	       <!--end-subheader-->
         <div>
	
	<?php $this->load->view('listingTabs');  ?>

	<section class="content-wrap2 tb-space" id="detailPage"> 
		
		
	<?php if($course){  ?>
		<article class="inst-details" style="margin:0">
		 <h2 class="ques-title">
			<p class="flLt" style="padding-top:10px;">Course Details </p>
			        <!----shortlist-course---->
				<div class="flRt">
				<?php
				$data['courseId'] = $course->getId();
				$data['pageType'] = 'mobileCourseDetailPage';
				$data['tracking_keyid']=$shortlistTrackingPageKeyId;
				$this->load->view('/mcommon5/mobileShortlistStar',$data);
				?>
				</div>
				<!-----end-shortlist------>
				<div class="clearfix"></div>
		</h2>
		<div class="notify-details" data-enhance="false">
		<div class="inst-detail-list" id="courseDesc">
		      <p>
			<?php $this->load->view('course_header_description');?>

	                <?php  if(!in_array((int)$course->getId(),$data['appliedCourseArr'])) { $style = 'style="display:none;"'; } else { $style = 'style="padding:0;"'; } ?>
			<section id= "thanksMsg<?php echo $course->getId();?>" class="top-msg-row" <?php echo  $style;?>>
			<div class="thnx-msg" >
		                <i class="icon-tick"></i>
                		<p>Thank you for your request. You will be receiving E-brochure of the selected institute(s) in your mailbox shortly.</p>
		        </div>
			</section>

			<?php $this->load->view('requestEbrochureCoursePage',$data); ?>
		      </p>
		      
		      <?php if($courseComplete->getDescriptionAttributes()){  ?>
			<?php $this->load->view('mobile_course_description');  ?>
		    <?php }?>
		</div>
		</div>
		</article>
        <?php } ?>
	
	<?php   $mobile_details = json_decode($_COOKIE['ci_mobile_capbilities'],true);
    		$screenWidth = $mobile_details['resolution_width'];
    		$screenHeight = $mobile_details['resolution_height'];
    ?>
    <input type="hidden" value="<?php echo $screenWidth;?>" id="screenwidth" />
    <input type="hidden" value="<?php echo $screenHeight;?>" id="screenheight" />
    
	<?php //$this->load->view('requestEbrochureCoursePage',$data); ?>
	
	<?php $this->load->view('OtherCourses');?>

    <div id="naukri_widget_data">
    </div>
    
	<?php  
	if(isset($subcatIDForWidgetDisplayCheck) && $subcatIDForWidgetDisplayCheck == "23"){
		?>
		<?php if(in_array($course_id, $IIMCourses)){
		echo Modules::Run('mIIMPredictor5/IIMPredictor/getIIMCallPredictorWidget',$fromPage);
	 	} else { ?>
	<div class="alumini-title" style='float:none;'>Tools to decide your MBA College</div>
		<div id="mbaToolsWidget" data-enhance="false">
			<div style="margin: 20px; text-align: center;"><img border="0" src="/public/mobile5/images/ajax-loader.gif"></div>
            <?php
                echo $collegeReviewWidget;  
            ?>
        </div>
		<?php
		
	}}
	 ?>
	<?php $this->load->view('placeCompAllBranches');?>
   
	</section>
	
	<?php $this->load->view('campusRepWidget');?>

		<?php			
		$isMultiLocation=$course->isCourseMultilocation();
                                        foreach($course->getLocations() as $course_location){
                                                        $locality_name = $course_location->getLocality()->getName();
                                                        if($locality_name !='') $locality_name = ' |'.$course_location->getLocality()->getName();
                                                        $addReqInfoVars[$course->getName().' | '.$course_location->getCity()->getName().$locality_name]=$course->getId()."*".html_escape($institute->getName())."*".$course->getUrl()."*".$course_location->getCity()->getId()."*".$course_location->getLocality()->getId();
                                        }
					
					$addReqInfoVars=serialize($addReqInfoVars);
					$addReqInfoVars=base64_encode($addReqInfoVars);
		?>
		<form action="/muser5/MobileUser/renderRequestEbrouchre" method="post" id="brochureForm<?=$course->getId();?>">
                                <input type="hidden" name="courseAttr" value = "<?php echo $addReqInfoVars; ?>" />
                                <input type="hidden" name="current_url" value = "<?php echo url_base64_encode($shiksha_site_current_url); ?>" />
                                <input type="hidden" name="referral_url" value = "<?php echo url_base64_encode($shiksha_site_current_refferal); ?>" />
                                <input type="hidden" name="selected_course" value = "<?php echo $course->getId(); ?>" />
                                <input type="hidden" name="list" value="<?php echo $course->getId(); ?>" />
                                <input type="hidden" name="institute_id" value="<?php echo $institute->getId(); ?>" />
                                <input type="hidden" name="pageName" value="COURSE_DETAIL_PAGE" />
                                <input type="hidden" name="from_where" value="MOBILE5_COURSE_DETAIL_PAGE" />
                                <input type="hidden" name="tracking_keyid" id="tracking_keyid<?=$course->getId();?>" value=''/>
                </form>

<?php if($makeAutoResponse){?>
<script>
if(!responseCounter){
responseCounter = true;
var instituteId="<?php echo $institute->getId();?>";
var localityId="<?php echo $currentLocation->getLocality()->getId();?>";
var cityId="<?php echo $currentLocation->getCity()->getId(); ?>";
var isMulitpleLocation="<?php echo $isMultiLocation;?>";
var courseId="<?php echo $course->getId();?>";
var viewedListingTrackingPageKeyId = 647;
postRequestEBrochureData(instituteId,localityId,cityId,isMulitpleLocation,courseId,'autoListingResponse','',viewedListingTrackingPageKeyId);
}
</script>
<?php } ?>


<!--	Add accordion widget on Course description -->
<script>	
function make(trackingPageKeyId)
{
	$('#request_e_brochure<?php echo $course->getId();?>_another').attr('onClick','makeResponse('+trackingPageKeyId+')');
}




function makeResponse(trackingPageKeyId){
	var instituteId="<?php echo $institute->getId();?>";
        var localityId="<?php echo $currentLocation->getLocality()->getId();?>";
        var cityId="<?php echo $currentLocation->getCity()->getId(); ?>";
        var isMulitpleLocation="<?php echo $isMultiLocation;?>";
        var courseId="<?php echo $course->getId();?>";
	validateRequestEBrochureFormData(instituteId,localityId,cityId,isMulitpleLocation,courseId,'',trackingPageKeyId);
}
</script>
<?php if($isMBAPage && $userData['userId'] == 0) { ?>
<form action="/muser5/MobileUser/showRegistrationForm" method="post" id="<?php echo $searchExamFormName;?>">
	<section style="margin:20px 0;" data-enhance="false">
		<div class="reg-course-listing">
			<ul class="reg-course-list">
				<li>
					<p class="list-text">Get Detailed Information On MBA Colleges</p>
					<p class="list-text list-text-hd">Based On Exam And Location</p>
				</li>
				<li>
					<div class="course-exam"> 
					<select name="examName" class="course-city" id="examList_<?php echo $searchExamFormName;?>" caption="Exam" onchange="toShowErrorMsgShortlist(this);" required="true" validationtype="select">
							<option value="">Exams Taken</option>
							<?php 
							global $myShortlistStaticExamList;
							foreach($myShortlistStaticExamList as $examName) { ?>
							<option value="<?php echo $examName;?>"><?php echo $examName;?></option>
							<?php } ?>
						</select>
						<div id="examList_<?php echo $searchExamFormName?>_error" class="errorMsg" style="display:none;text-align:left;"></div>
					</div>
					<div class="course-exam">
						<select name="residenceCity" id="locationList_<?php echo $searchExamFormName;?>" class="exam-city" caption="Location" onchange="toShowErrorMsgShortlist(this);" required="true" validationtype="select">
							<?php $this->load->view('registration/common/dropdowns/residenceCity',array('defaultOptionText' => 'Current City', 'isNational' => true)); ?>
						</select>
						<div id="locationList_<?php echo $searchExamFormName;?>_error" class="errorMsg" style="display:none;text-align:left;padding-left:10px;"></div>
					</div>
				</li>
				<li><input type="button" class="list-bt" value="Search" onclick="return validateSearchByExamWidget('<?php echo $searchExamFormName;?>')"></li>
			</ul>
		</div>
	</section>
	<input type="hidden" name="course_id" value = "<?php echo $course_id; ?>" />
	<input type="hidden" name="action" value="registrationHookFromSearch" />
	<input type="hidden" name="show_course_selected" value="yes" />
	<input type="hidden" name="current_url" value="<?php echo $_SERVER['SCRIPT_URI'];?>#MOBILE_REGISTRATION_SEARCH_BY_EXAM" />
	<input type="hidden" name="tracking_keyid" id="tracking_keyid" value="<?php echo $searchTrackingPageKeyId;?>">
</form>
<?php } ?>

<div id="alsoOnShiksha">
</div>

	 </div>	
	<?php $this->load->view('/mcommon5/footerLinks');?>

	 <?php
	 if($call_back_yes == 1):
	 ?>
	 <a  href="#callbackpopup" data-position-to="window" data-inline="true" data-rel="popup" id="callbacklink" data-transition="pop" ></a>
	 <div data-role="popup" id="callbackpopup">
	    <?php $this->load->view("callBackLayer");?>
	 </div>
	 <?php
	 endif;
	 ?>
	
	
      </div>
	
	<!--div id="reb_sticky_button" style="position: fixed;left: 0;bottom: 0px;width:100%;background-color:#737373;padding:10px 0;display:none;">
	</div-->
	<!--script>setTimeout(function(){hideShowREBButton("request_e_brochure"+courseId,'alsoOnShiksha',courseId,'course',rebButtonStatus)},1000);</script-->
	
	
</div>

<?php 
$jsFileInclude = array();
if($campusConnectAvailable)
{
	$jsFileInclude = array("jsMobileFooter" => array('ana'));
}
$this->load->view('mCollegeReviews5/readMoreLayer'); 
$this->load->view('/mcommon5/footer',$jsFileInclude);?>

<?php
if($call_back_yes == 1):
?>
<script>
//call back patching	
$(document).ready(function() {
        //alert('1');
	//$('#callbackpopup').html(call_back_data);
	//document.getElementById('callbackpopup').innerHTML = call_back_data;
	$('#callbacklink').click();
});
</script>
<?php
endif;
?>

<div data-role="page" id="branches" data-enhance="false"><!-- dialog-->
 <?php $this->load->view('allBranches'); ?>
</div>

<div data-role="page" id="alumniDataSpecialization" data-enhance="false"><!-- dialog-->
	<header id="page-header" class="clearfix" >
			<div data-role="header" data-position="fixed" class="head-group ui-header-fixed" data-tap-toggle="false">
				<a id="specializationOverlayClose" href="javascript:void(0);" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>
				<h3>Choose Specialization</h3>
			</div>
	</header>
	<section class="content-wrap2 fixed-wrap" id="specLayer">
	</section>
</div>

<img id = 'beacon_img' src="/public/images/blankImg.gif" width=1 height=1 >

<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script>
<?php if(!empty($viewTrackParams)){ ?>
  window['trackingPageType'] = '<?php echo $viewTrackParams['trackingPageType'];?>';
  window['answerViewThreshold'] = <?php echo $viewTrackParams['answer'];?>;
  window['questionViewThreshold'] = <?php echo $viewTrackParams['question'];?>;
  window['threadViewJSWorkerInterval'] = <?php echo $viewTrackParams['trackDuration'];?>;
  <?php } ?>
$(document).ready(function() {

	<?php if(isset($_COOKIE['onlineForm_SuccessMessage']) && $_COOKIE['onlineForm_SuccessMessage']!=''){ ?>
                        name = base64_decode('<?=$_COOKIE['onlineForm_SuccessMessage']?>');
                        openConfirmLayer('Application for '+name, $('#popupBasic-Success'));					
	<?php 
	      deleteTempUserData('onlineForm_SuccessMessage');
	} ?>
});


var naukri_integration_data_base_url = "<?php echo '/listing/Naukri_Data_Integration_Controller/getDataForNaukriSalaryWidget/'.$course->getInstId().'/'.$course->getId()?>";
var universal_selected_naukri_splz = '';
var universal_number_of_funcional = 5;
var universal_no_of_componies = 5;
var isOverlayOpen = false;

function getNaukriIntegrationWidget(splz,number_of_funcional,no_of_componies) {
			var url = naukri_integration_data_base_url;
			universal_selected_naukri_splz = splz;
			universal_number_of_funcional = number_of_funcional;
			universal_no_of_componies = no_of_componies;
			mobileFlag = 1;
			pageType = "mobileListingCoursepage"
			if(splz) {                      
					url = naukri_integration_data_base_url +"/"+ base64_encode(splz)+"/"+number_of_funcional+"/"+no_of_componies+"/"+mobileFlag; 
			} else {
					url = naukri_integration_data_base_url +"/0/"+number_of_funcional+"/"+no_of_componies+"/"+mobileFlag+"/"+pageType; 
			}       
			// load naukri data
			$.ajax({
							url : url,
							method : 'get',
							success : function(response) {
                                                                        if(isOverlayOpen){
                                                                                $('#specializationOverlayClose').click();
                                                                                isOverlayOpen = false;
                                                                        }
								        setTimeout(function(){
                                                                	        $('#naukri_widget_data').html(base64_decode(response));
                                                                        	ajax_parseJs(document.getElementById('naukri_widget_data'));
	                                                                        evaluateCss(document.getElementById('naukri_widget_data'));
								        },200);
							}
							}
			);
}

$(document).ready(function(){
<?php if($campusConnectAvailable){ ?>
		initializeThreadViewTracking();
<?php } ?>
		new loadToolstoDecideMBACollegeWidget("mbaToolsWidget","COURSE_LISTING_MOBILE").loadWidget();		
		getNaukriIntegrationWidget('',5,5);

        $.Zebra_Accordion('#courseDesc',{
         'hide_speed' : 1,
         'show_speed' : 1,
         'scroll_speed': 1,
         'onClose' : function(id){$('#desc'+id).attr('class', 'icon-arrow-up'); },
        'onOpen' : function(id){$('#desc'+id).attr('class', 'icon-arrow-dwn');}
        });

	<?php if(count($companiesLogoArray)>0){ 
		foreach ($companiesLogoArray as $key=>$value){
			echo "$('#logo$key').attr('src','$value');";
		}
	 } ?>

	$('#alsoOnShiksha').load('/mobile_listing5/Listing_mobile/alsoOnShiksha/<?=$course->getId()?>/mobileCourseDetailPage/<?=$similardTrackingPageKeyId?>/<?=$similarShortlistTrackingPageKeyId?>/<?php echo $similarcomparetrackingPageKeyId;?>');
	//Call Beacon for View count
        var img = document.getElementById('beacon_img');
        var randNum = Math.floor(Math.random()*Math.pow(10,16));
        img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0010004/<?=$course->getId()?>+course';
    	var courseId = '<?php echo $course->getId();  ?>';
    	var show_recommendation = getCookie('show_recommendation');	
    	var recommendation_course = getCookie('recommendation_course');	
	var hide_recommendation = getCookie('hide_recommendation');	

	<?php if($responseCreatedInstituteId>0 && $responseCreatedCourseId>0){ ?>
	CP_lastREBCourseId = '<?=$responseCreatedCourseId?>';
	CP_lastREBInstituteId = '<?=$responseCreatedInstituteId?>';
	<?php } ?>

    	if(show_recommendation == 'yes' && hide_recommendation!= 'yes') {	
   	var isListingPage = 'YES';
    		var isRankingPage = 'NO';
    		var brochureAvailable = 'YES';
    		var pageType = 'LP_MOB_Reco_ReqEbrochure';
    		var courseId = '<?php echo $course->getId(); ?>';	
 		var screenWidth =  window.jQuery('#screenwidth').val();
		var screenHeight = window.jQuery('#screenheight').val();
		var trackingPageKeyId = '<?php echo $recommendationTrackingPageKeyId;?>';
		   	
    		var urlRec = '/muser5/MobileUser/showRecommendation/'+courseId+'/CP_Reco_popupLayer'+'/0/0/0/'+brochureAvailable+'/'+isRankingPage +'/' + pageType + '/' +isListingPage+'/0/'+trackingPageKeyId;
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


//								$('#recomendation_layer_listing').css('max-height',screenHeight);
							
//								$('#recomendation_layer_listing').css('height',screenHeight);
							
//								$('#recomendation_layer_listing').css('overflow-y','scroll');
								
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


$(window).scroll(function() {
    clearTimeout($.data(this, 'scrollTimer'));
    $.data(this, 'scrollTimer', setTimeout(function() {
        //Check which all Review Divs are in Focus. Whichever of them are in focus, we will have to make a view call at Backend.
        //Also, this call should not happen for Same session-review pair / Same user-review pair
        $("div[id^='readMore']").each (function(ind){
                if( isElementInViewport ($(this)) && $(this).parent().parent().css('display')!="none" ){
                        divId =  $(this).attr('id');
                        res = divId.split('More');
                        reviewId = res[1];
                        if(jQuery.inArray(reviewId,reviewsAdded) == -1 && jQuery.isNumeric(reviewId)){
                                markReviewRead(reviewId, 'mobile', 'courseDetailPage');
                                reviewsAdded.push(reviewId);
                        }
                }
        });
        console.log("Haven't scrolled in 5sec!");
    }, collegeReviewScrollTimer));
});
$('#collegeReviewSelect').val('graduationYear');
</script>
