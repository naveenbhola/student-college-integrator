
   </div>
   <div class="clearFix"></div>
   <div class="wrapperFxd">
   <div id="footerText">
   		<a href="javascript:void(0);" onclick="return popitup('http://www.shiksha.com/shikshaHelp/ShikshaHelp/termCondition');">Terms &amp; Conditions</a> &nbsp;|&nbsp; <a onclick="return popitup('http://www.shiksha.com/shikshaHelp/ShikshaHelp/privacyPolicy');" href="javascript:void(0);">Privacy Policy</a><br />
		<p>Trade Marks belongs to the respective owners.<br />
        Copyright &copy; <?php echo date('Y'); ?> Info Edge India Ltd. All rights reserved.
		</p>
   </div>
</div>

    <div id="onLoadOverlayContainer"></div>
    <?php
    if (!(isset($search) && $search=="false")) {
        if(!is_array($validateuser) || !isset($validateuser[0])) {
            if(!isset($calendarDivLoaded) || $calendarDivLoaded ==0){ ?>
                <script>try{ overlayViewsArray.push(new Array('common/calendardiv','calendardivId')); }catch(e){ }</script>
                    <?php  }
        }

    }
    if(!isset($commonOverlayDivLoaded) || $commonOverlayDivLoaded ==0){ ?>
        <script>try{ overlayViewsArray.push(new Array('network/commonOverlay','addRequestOverlay')); }catch(e){ }</script>
   <?php } ?>
<?php
$alreadyAddedJsFooter = array('footer','user','lazyload');
if(!isset($jsFooter)){
	$jsFooter = array();
}
$jsFooter = getJsToInclude(array_unique(array_merge($alreadyAddedJsFooter, $jsFooter)));
    if(isset($jsFooter) && is_array($jsFooter)) {
        foreach($jsFooter as $jsFile) {
?>
                <script language="javascript" src="<?php echo $jsFile;?>"></script>
<?php
        }
    }
?>
<?php
	$cvsJsIncludedOnPage = '';
	if(is_array($js)){
		$cvsJsIncludedOnPage = implode(",",$js);
	}
	if(is_array($jsFooter)){
		if(strlen($cvsJsIncludedOnPage) > 0)
			$cvsJsIncludedOnPage .= ','.implode(",",$jsFooter);
		else
			$cvsJsIncludedOnPage .= implode(",",$jsFooter);
	}
?>
<input type="hidden" name="cvsJsIncludedOnPage" id="cvsJsIncludedOnPage" value="<?php echo $cvsJsIncludedOnPage; ?>" />
<?php global $clientIP;
if(strpos($clientIP,"shiksha")!==false) { ?>
<?php $this->load->view('common/ga'); ?>
<?php } ?>

<?php echo TrackingCode::SCANSmartPixel($googleRemarketingParams); ?>
<?php //echo TrackingCode::vizury(); ?>
<?php //echo TrackingCode::SCANAudienceBuildingPixel(); ?>
<?php //echo TrackingCode::FBConvertedAudiencePixel(); ?>
<?php //echo TrackingCode::GoogleConvertedAudiencePixel(); ?>

<?php
/*
//Commented by Ankur Gupta on 28 Nov. The usage of this Tracker is not clear and it is throing 500 error on many pages of Shiksha
if(PAGETRACK_BEACON_FLAG)
{
    $this->load->view('common/pageTrack_beacon.php');
}*/
global $serverStartTime;
$trackForPages = isset($trackForPages)?$trackForPages:false;
$endserverTime =  microtime(true);
$tempForTracking = ($endserverTime - $serverStartTime)*1000;
$reset_password = trim(strip_tags($_REQUEST['resetpwd']));
if($reset_password == 1) {
	$reset_password_token = trim(strip_tags($_REQUEST['uname']));
	$reset_usremail = trim(strip_tags($_REQUEST['usremail']));							
}
?>
</body>
</html>
<?php
    echo getTailTrackJs($tempForTracking,true,$trackForPages,'http://track.99acres.com/images/zero.gif');
?>
<!-- Begin comScore Tag -->
<script>
  var _comscore = _comscore || [];
  _comscore.push({ c1: "2", c2: "6035313" });
  (function() {
    var s = document.createElement("script"), el = document.getElementsByTagName("script")[0]; s.async = true;
    s.src = (document.location.protocol == "https:" ? "https://sb" : "http://b") + ".scorecardresearch.com/beacon.js";
    el.parentNode.insertBefore(s, el);
  })();
</script>
<noscript>
<img src="http://b.scorecardresearch.com/p?c1=2&c2=6035313&cv=2.0&cj=1" />
</noscript>
<!-- End comScore Tag -->

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script> $j = $.noConflict();</script>
<script>
	
var reset_password_token = '<?php echo $reset_password_token;?>';
var reset_usremail = '<?php echo $reset_usremail;?>';
var reset_password = '<?php echo $reset_password;?>';
//var registration_context = '';
var user_logged_in_pref_data = "";
var userInfo = null;
var firstTimePageLoad = true;

$j(document).ready(
	function(){
		if (reset_password == 1) {
			var registration_context = 'MMP';
			shikshaUserRegistration.showResetPasswordLayer(reset_password_token,reset_usremail,registration_context);	
		}		
});
</script>

<?php  $this->load->view('common/footer/footerCommonCode');?>
<script>
        <?php if(!empty($subcatNameForGATracking) && !empty($pageTypeForGATracking)) { ?>
                // customized error object
                if(typeof pageTracker != "undefined") {
                    pageTracker._setCustomVar(1, "NationalSubcatLevelTrack", '<?=$subcatNameForGATracking?>/<?=$pageTypeForGATracking?>', 2);
                    pageTracker._setCustomVar(5, "NationalSubcatLevelTrack_page", '<?=$subcatNameForGATracking?>/<?=$pageTypeForGATracking?>_page', 3);
					pageTracker._trackEventNonInteractive('dummyTrackingCat', '<?=$pageTypeForGATracking?>', '<?=$subcatNameForGATracking?>', 0, true);

                    //send a DB tracking for URL and custom variable key-value
                    <?php if(DB_TRACK_FOR_GA_PARAM_VERIFICATION) {
                        $currentUrl = get_full_url(); ?>
                        $j.ajax({
                            url:'/shiksha/dbTrackingForGAParams',
                            type:'POST',
                            data :{'currentUrl':'<?=$currentUrl?>','gaString':'<?=$subcatNameForGATracking?>/<?=$pageTypeForGATracking?>','source':'desktop'},
                            success: function(response) {
                                //console.log('success');
                            }
                        });
                    <?php } ?>
                }
        <?php } ?>
</script>


<script>
/*
    Function which put values into global vars. like is_ldb user or cross btn click
*/

function loadRequiredDataForUnifiedRegistrationProcess()
{
        /* overlay global obj is called */
     	messageObj = new DHTML_modalMessage();
     	/* ajax to set if user register or not START */
        checkLdbUser();
        /* ajax to set if user register or not END */
        /* set variable to check whether user has clicked unified overlay or not*/
        unified_form_overlay1_cancel_clicked = getCookie('is_unified_overlay1_clicked');
        unified_form_overlay2_cancel_clicked = getCookie('is_unified_overlay2_clicked');
        unified_form_overlay3_cancel_clicked = getCookie('is_unified_overlay3_clicked');
        /* set Form submit url for diff types of overlays */
        if(typeof(arr_unified) !== 'undefined') {
        	ShikshaUnifiedRegistarion.url_unified = ShikshaUnifiedRegistarion.ajaxUrlHelper(arr_unified);
        }
}
/* UNIFIED REGISTRATION APIs END */

function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      if (oldonload) {
        oldonload();
      }
      func();
    }
  }
}

addLoadEvent(function() {
    if(self.buttonForFConnectAndFShare) {
        buttonForFConnectAndFShare();
    }
    try {
    	initiateShikshaAutoSuggest();
    } catch(err) {
    	// do nothing, we only need it on home page
    }
    initForUnifiedRegistration();
	<?php
	if(isset($_REQUEST['apply'])){
	?>
		setTimeout(function(){ApplyNowFromCategory();},1000);
	<?php
		}
	?>
    publishBanners();
});
var facebook_channel_path = "<?php echo FB_CHANNEL_PATH; ?>";
</script>


<script>
	     // make auto response for compare courses
	     $j(window).load(function(){
	     var userMadeResponse = 'cmp_user_md_rspns';
	     var cookieDomain = '<?php echo COOKIEDOMAIN; ?>';
	     <?php if (is_array($validateuser) && is_array($validateuser[0]) && (isset($validateuser[0]['userid']))&& !empty($validateuser[0]['userid'])){ ?>
		     var loggedInUId = '<?php echo $validateuser[0]['userid'];?>';
		     if (loggedInUId !='' && loggedInUId >0 && getCookie(userMadeResponse) == '')
		     {
			     $j.ajax({
				     type: "POST",
				     data: {'userId':loggedInUId},
				     url: '/comparePage/comparePage/getLoggedInUserForMadeResponse',
				     success: function(request){ setCookie(userMadeResponse, 'true', 1, '/', cookieDomain);}
				     });
		     }
	     <?php }else{ ?>
		     setCookie(userMadeResponse, '', -1, '/', cookieDomain);
	     <?php }?>
	     
	     });     
</script>

<script type="text/javascript">
setTimeout(function(){var a=document.createElement("script");
var b=document.getElementsByTagName("script")[0];
a.src=document.location.protocol+"//dnn506yrbagrg.cloudfront.net/pages/scripts/0019/0281.js?"+Math.floor(new Date().getTime()/3600000);
a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);
</script>

