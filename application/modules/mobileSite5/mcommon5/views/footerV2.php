<?php 
	$this->load->view('mcommon5/footerDialogCode_jq'); 
	/*$countryName = strtoupper($_SERVER['GEOIP_COUNTRY_NAME']);
	if ( $countryName == 'INDIA'){
    setcookie("gdpr","1",time() + (86400 * 90));
	}*/
?>
<script>
var dfpContainer = ["div-gpt-ad-1534482373064-0","div-gpt-ad-1534482400175-0","div-gpt-ad-1534482438926-0","div-gpt-ad-1534482838395-0","div-gpt-ad-1534478657348-0","div-gpt-ad-1534478774921-0","div-gpt-ad-1534478829086-0","div-gpt-ad-1534478896950-0"];
compareDiv = 1;
var getcHostName = "<?php echo SHIKSHA_HOME;?>";
var isLoadCrazyEgg = false;
var MOB_HAMBURGER_CUSTOMIZE = "<?php echo MOB_HAMBURGER_CUSTOMIZE;?>";
var MOBILE_HAMBURGER_REGISTRATION = "<?php echo MOBILE_HAMBURGER_REGISTRATION;?>";
var fromAMPUrl = '<?php echo $_GET['fromamp'];?>';
var StreamFromAmp = '<?php echo $_GET['ampstream_id'];?>';
</script>
<?php
if(isset($jsMobileFooter) && is_array($jsMobileFooter)) {

    foreach($jsMobileFooter as $jsFileFooter) {
?>
    <script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion($jsFileFooter,'nationalMobile'); ?>"></script>
<?php
    }
}
//loading page based merged files using grunt
echo includeJSFiles($product, 'nationalMobile');

if($boomr_pageid == 'mRanking5') {
	echo includeJSFiles('shikshaMobileWebsiteTour', 'nationalMobile');
}

global $serverStartTime;
$endserverTime =  microtime(true);
global $tempForTracking;
$tempForTracking = ($endserverTime - $serverStartTime)*1000;
$trackForPages = isset($trackForPages)?$trackForPages:false;
$url = SHIKSHA_HOME . "/public/" ;
global $ci_mobile_capbilities; if(!isset($ci_mobile_capbilities)) { $ci_mobile_capbilities = $_COOKIE['ci_mobile_capbilities']; $wurfl_data = json_decode($ci_mobile_capbilities,true);} else { $wurfl_data = $ci_mobile_capbilities; }
?>

<?php 
global $isWebViewCall;
if($isWebViewCall){ ?>
<script>
var gaAccountId = 'UA-4454182-4';
</script>
<?php } else { ?>
<script>
var gaAccountId = 'UA-4454182-1';
</script>
<?php } ?>

<script type="text/javascript">
<?php if(!(isset($doNotLoadImageLazyLoad) && $doNotLoadImageLazyLoad=='true')){ ?>
	if(jQuery("img.lazy").length > 0){
		jQuery("img.lazy").show().lazyload({ 
		    effect : "fadeIn",
		    failure_limit : 5
		});
	}
<?php } ?>
    var _gaq = _gaq || []; _gaq.push(['_setAccount', gaAccountId]); _gaq.push(['_trackPageview']); (function() { var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true; ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s); })(); function _pageTracker(e){ this.type=e; this._trackEventNonInteractive=function(a, b, c, d, e) { _gaq=_gaq||[]; _gaq.push(['_trackEvent', a, b, c, d, e]); }; this._setCallback=function(e){ _gaq.push(['_set', 'hitCallback', e]); }; this._setCustomVar=function(e,t,n,r){ _gaq=_gaq||[]; _gaq.push(["_setCustomVar",e,t,n,r]) }; }var pageTracker=new _pageTracker; var gaCallbackURL;
</script> 
<?php $this->load->view('/mcommon5/mobileConfirmPopUpLayerShortlist'); ?>
<?php $this->load->view('/mcommon5/recommendationLayer'); ?>
<?php if((isset($searchPage) && $searchPage!='') || (isset($collegeReviewPage) && $collegeReviewPage!='') || MOBILE_SEARCH_V2_INTEGRATION_FLAG == 1){ ?>
	<script type="text/javascript">
	var mobileSearch = 'true';
	var fromSearchPage = '<?php if(isset($fromSearchPage)){echo $fromSearchPage;}?>';
	var isPopulate = '<?php if(isset($isPopulate)){echo $isPopulate;}?>';
	var searchFrom = '<?php if(isset($searchFrom)){echo $searchFrom;}?>';
	var totalResult= '<?php if(isset($totalResult)){echo $totalResult;}?>';
	var schemaName = '<?php if(isset($schemaName)){echo $schemaName;}?>';
	var inputKeyId = '<?php if(isset($inputKeyId)){ echo $inputKeyId;}?>';
	var container  = '<?php if(isset($container)){ echo $container;}?>';
	var SEARCH_PAGE_URL_PREFIX = '<?php echo SEARCH_PAGE_URL_PREFIX; ?>';
	</script>
<?php } ?>

<?php $this->load->view('mcommon5/footerCommonCode'); ?>

<?php echo TrackingCode::SCANSmartPixel($googleRemarketingParams);
//echo TrackingCode::SCANAudienceBuildingPixel();
echo TrackingCode::GoogleConvertedAudiencePixel(); ?>
<?php $this->load->view('mcommon5/footerTracking'); ?>
<script>
	<?php if(!empty($subcatNameForGATracking) && !empty($pageTypeForGATracking)) { ?>
        if(typeof _gaq != "undefined") {
            _gaq = _gaq||[];
            _gaq.push(['_setCustomVar', 1, "NationalSubcatLevelTrack", '<?=$subcatNameForGATracking?>/<?=$pageTypeForGATracking?>', 2]);
			_gaq.push(['_setCustomVar', 5, "NationalSubcatLevelTrack_page", '<?=$subcatNameForGATracking?>/<?=$pageTypeForGATracking?>_page', 3]);
            _gaq.push(['_trackEvent', 'dummyTrackingCat', '<?=$pageTypeForGATracking?>', '<?=$subcatNameForGATracking?>',0,true]);
			<?php if(DB_TRACK_FOR_GA_PARAM_VERIFICATION) {
				$currentUrl = get_full_url(); ?>
				$.ajax({
					url:'/shiksha/dbTrackingForGAParams',
					type:'POST',
					data :{'currentUrl':'<?=$currentUrl?>','gaString':'<?=$subcatNameForGATracking?>/<?=$pageTypeForGATracking?>','source':'mobile'},
					success: function(response) {
						//console.log('success');
					}
				});
			<?php } ?>
        }
    <?php } ?>
</script>
<?php
global $shiksha_site_current_url;
global $shiksha_site_current_refferal;
?>
<div style="display: none;">
	<!-- <form method="post" action="< ?=SHIKSHA_HOME?>/muser5/MobileUser/register" id="mobileShortlistLoginForm">
		<input type="hidden" name="current_url" value="< ?=url_base64_encode(htmlentities(strip_tags($shiksha_site_current_url)))?>">
		<input type="hidden" name="referral_url" value="< ?=url_base64_encode($shiksha_site_current_refferal)?>">
		<input type="hidden" name="from_where" value="categoryPage">
		<input type="hidden" name="cmp_url" id="cmp_url" value="">
		<input type="hidden" name="tracking_keyid" id="tracking_keyid" value="<?php if(isset($tracking_keyid)){ echo $tracking_keyid;}?>">
		<input type="hidden" name="actionPoint" id="actionPoint" value="">
	</form>
	<form method="post" action="<?=SHIKSHA_HOME?>/muser5/MobileUser/showRegistrationForm" id="mobileLoginForm">
		<input type="hidden" name="current_url" id="current_url" value="<?php echo htmlentities(strip_tags($shiksha_site_current_url)); ?>">
		<input type="hidden" name="redirect_url" id="redirect_url" value="">
		<input type="hidden" name="course_id" id="courseId" value="">
		<input type="hidden" name="show_course_selected" id="showCourseSelected" value="no">
		<input type="hidden" name="action" id="action" value="">
		<input type="hidden" name="from_where" id="from_where" value="">
		<?php if(isset($tracking_keyid)) { ?><input type="hidden" name="tracking_keyid" id="tracking_keyid" value="<?php echo $tracking_keyid; ?>"><?php } else if(isset($shortlistbottomTrackingPageKeyId)){ ?>
		<input type="hidden" name="tracking_keyid" id="tracking_keyid_shortlist" value="<?php echo $shortlistbottomTrackingPageKeyId;?>"><?php } ?>
		<input type="hidden" name="actionPointShortlist" id="actionPointShortlist" value="">
	</form> -->
</div>
	<a href="#registerNow" id="openRegFreeForm" class="ui-link" data-inline="true" data-rel="dialog" data-transition="slideup" > </a>
	<a href="#forgtPassDiv" id="forgtPassDivClick" class="ui-link" data-inline="true" data-rel="dialog" data-transition="slideup"> </a>
	<div data-role="page" id="registerNow">
	</div>

	<div data-role="page" id="forgtPassDiv">
	</div>

	<a href="#signUpForm" id="openSignUpForm" class="ui-link" data-inline="true" data-rel="dialog" data-transition="slideup" > </a>
	<a href="#signUpForm" id="openOTPForm" class="ui-link" data-inline="true" data-rel="dialog" data-transition="slide" > </a>
	<div data-role="page" id="signUpForm" class="sltNone">
	</div>

	<!-- Page for Registration First Screen -->
	<a href="#regFScreen" id="openRegFScreen" class="ui-link" data-inline="true" data-rel="dialog" data-transition="none" > </a>
	<div data-role="page" id="regFScreen" class="sltNone">
	</div>

	<!-- Page for Registration Second Screen -->
	<a href="#regSScreen" id="openRegSScreen" class="ui-link" data-inline="true" data-rel="dialog" data-transition="slide" > </a>
	<a href="#regSScreen" id="openRegSScreenNoTran" class="ui-link" data-inline="true" data-rel="dialog" data-transition="none" > </a>
	
	<div data-role="page" id="regSScreen" class="sltNone" style="display: none;"></div>

	<!-- Page for Registration dialog Screen -->
	<a href="#dialogPage" id="ssLayerOn" class="ui-link" data-inline="true" data-rel="dialog" data-transition="none" > </a>
	<div data-role="page" id="dialogPage" data-enhance="false" class="sltNone regCss">
	</div>

	<!-- Page for Update Mobile on OTP Screen -->
	<a href="#otpScreen" id="otpSScreen" class="ui-link" data-inline="true" data-rel="dialog" data-transition="none" > </a>
	<div data-role="page" id="otpScreen" data-enhance="false" class="sltNone">
	</div>

	<!-- Page to show miss call time in opt layer  -->
	<a href="#misdCallScreen" id="misdCallSScreen" class="ui-link" data-inline="true" data-rel="dialog" data-transition="slide" > </a>
	<div data-role="page" id="misdCallScreen" class="sltNone">
	</div>

	<!-- Page to show after mobile no verification -->
	<a href="#misdCallVfySucc" id="misdCallVfySuccScreen" class="ui-link" data-inline="true" data-rel="dialog" data-transition="slide" > </a>
	<div data-role="page" id="misdCallVfySucc" class="sltNone">
	</div>

	<!-- CSRF Protection Code -->
	<?php
	$CI = & get_instance();
	$CI->load->library('security');
	$CI->security->setCSRFToken();
	?>
	<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
	<!-- CSRF Protection Code -->

</body>

<script>
var shortListCookiename = 'mobile-shortlisted-courses';
$(window).load(function(){
    //After user has loggedin,update userId for made response of compare
    var userMadeResponse = 'cmp_user_md_rspns';
    var loggedInUId = logged_in_userid;
    if ((is_user_logged_in == 'true' && loggedInUId >0))
    {
        if((getCookie(userMadeResponse) == ''  && typeof (boomr_pageid) !='undefined' && boomr_pageid !== 'COMPAREPAGE'))
        {
            $.ajax({
            type: "POST",
            data: {'userId':loggedInUId},
            url: '/comparePage/comparePage/getLoggedInUserForMadeResponse',
            success: function(request){
                    if(typeof(setCookie) !='undefined'){
                         setCookie(userMadeResponse, 'true', 1, '/', COOKIEDOMAIN);
                    }
                }
            });
        }
    }else{
        if(typeof(setCookie) !='undefined'){
                setCookie(userMadeResponse, '', -1, '/', COOKIEDOMAIN);
        }
    }
     <?php if($pageName=='Mobile5ExamPage'){
	if(isset($_GET['sel']) && $_GET['sel']=='true'){ ?>
                      getSimilarExamLayer('<?php echo $examId;?>','<?php echo $groupId;?>');
                    <?php } ?>
	<?php if(isset($_GET['scrollTo']) && $_GET['scrollTo']=='article'){ ?>
                      $('html,body').animate({
                        scrollTop: $("#articleSection").offset().top-$('#fixed-card').height()-$('#_hlogo').height()-15},
                      'slow');
                <?php } ?>

	<?php } ?>
});	
jQuery(document).ready(function(){
	<?php if(MOB_HAMBURGER_CUSTOMIZE){?>
			if(typeof(addHamburgerLoader) !='undefined'){addHamburgerLoader();}
	<?php }?>        

	$('._hmPanel_').on("click", function(){
        if(typeof(initiateHamburgerMenuEvents) !='undefined' && MOB_HAMBURGER_CUSTOMIZE){
          initiateHamburgerMenuEvents(MOBILE_HAMBURGER_REGISTRATION, fromAMPUrl,StreamFromAmp); 
        }
        trackEventByGAMobile('MOBILE_MENU_FROM_'+boomerPageName.toUpperCase());
    });

	if(typeof(initiateBanner) !='undefined' && typeof(pageName) !='undefined'){
	        initiateBanner(pageName);// for enable app banner
    }
    
   /* if(typeof(pageName) !='undefined' && pageName == 'MobileMyShortlistHomepage'){
        shortlistCourseFromSearch();
    }*/
    var serviceWorker = "<?php echo getJSWithVersion('service-worker','pwa_mobile')?>";
    if ('serviceWorker' in navigator && typeof serviceWorker == 'string' && serviceWorker.length > 0 && typeof registerServiceWorker == 'function') {
        registerServiceWorker(serviceWorker);
    }

    for(var i in dfpContainer){
    	$('#'+dfpContainer[i]).addClass('ht-dfp');
    }
});
</script>
<?php
	if($_GET['source'] == 'Registration' && $_GET['mmpsrc'] > 0 && $_GET['newUser'] == 1) {
		$this->load->view('registration/common/conversionTracking');
	}
    echo getTailTrackJs($tempForTracking,true,$trackForPages,'https://track.99acres.com/images/zero.gif');
?>
<?php // below code is used to verify all URL's from the current page
      // added by - akhter
    $urlPage = isset($_GET['autoUrl']) ? $_GET['autoUrl'] :''; 
    if($urlPage && !is_numeric($urlPage) && ALLOW_AUTOURL_SCRIPT == TRUE){
        $a = $this->load->config('common/urlSelectorConfig',true);
        $cdata  = $this->config->item('urlSelectorConfig');
        $cdata  = $cdata['mobile'][$urlPage];
        if($cdata){
            $selector = json_encode($cdata);
        }echo "<script> var ele = $selector;</script>";
        $br = stristr($_SERVER["HTTP_USER_AGENT"],'Chrome');
        if(isset($_SERVER["HTTP_USER_AGENT"]) && (!empty($br))){
            echo "<script>alert('Your browser is not supported. Please open in Firefox only.');</script>";
        }else{?>
            <script type="text/javascript">
                $(window).load(function() {
                    automatedUrlScript();
                });
            </script>
    <?php }
}?>
</html>

<?php if($_GET['showSignUp'] == 1) { ?>
<script type="text/javascript">
	 setTimeout(function(){
      $('.n-reg').click();
    },2000);
</script>
<?php } ?>