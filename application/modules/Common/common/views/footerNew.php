<?php  
$this->benchmark->mark('Footer_New_Compare_start');
echo Modules::run('comparePage/comparePage/generateCollegeCompareTool');
$this->benchmark->mark('Footer_New_Compare_end');
/*$countryName = strtoupper($_SERVER['GEOIP_COUNTRY_NAME']);
if ($countryName == 'INDIA'){
    setcookie("gdpr","1",time() + (86400 * 90));
}*/
?>
</div>
</div></div></div></div></div></div> <!-- just in case div's are not closed  -->
<?php 
// hide assistant for selected pages
if(!in_array($product,array('CollegeReviewForm'))){
?>
<div id="chat-container"></div>
<?php 
}
?>
<script>
	// Attach window click event handler for GA tracking
    if(typeof loadNewHomePageWidget == 'function'){
	   loadNewHomePageWidget('/shiksha/loadAjaxForm/abroadtab','hmpgrdsn_tababroad');
    }
</script>
<?php
//if(isset($beaconTrackData)){
//	loadBeaconTracker($beaconTrackData);
//}

if(!in_array($product,array('campusAmbassador','writeForUs','CollegeReviewForm','collegeCutoff','CMSExam'))){
    $this->benchmark->mark('Footer_New_HTML_Add_Cache_DFP_start');
    $dfpFooter  = $this->load->view('dfp/dfpFooter');
    echo $dfpFooter;
    $this->benchmark->mark('Footer_New_HTML_Add_Cache_DFP_end');
}

$footerhtmlcache = "HomePageRedesignCache/footerNewWithAsk.html";
if(file_exists($footerhtmlcache)) {
    $this->benchmark->mark('Footer_New_HTML_Cache_start');
    echo file_get_contents($footerhtmlcache);
    $this->benchmark->mark('Footer_New_HTML_Cache_end');
}else{
    $this->benchmark->mark('Footer_New_HTML_Add_Cache_start');
	$footerHtml = $this->load->view('common/footerHtml',array('isAskButton' => true),true); 
	$pageContent = sanitize_output($footerHtml);

  	echo $pageContent;
    $fp=fopen($footerhtmlcache,'w+');
    flock( $fp, LOCK_EX ); // exclusive lock
    fputs($fp,$pageContent);
    flock( $fp, LOCK_UN ); // release the lock
    fclose($fp);

    $this->benchmark->mark('Footer_New_HTML_Add_Cache_end');
}
?>	
<div id="onLoadOverlayContainer"></div>
<?php
    if (!(isset($search) && $search=="false")) {
        $this->benchmark->mark('Footer_New_Calendar_Div_start');
        if(!is_array($validateuser) || !isset($validateuser[0])) {
            if(!isset($calendarDivLoaded) || $calendarDivLoaded ==0){ ?>
                <script>try{ overlayViewsArray.push(new Array('common/calendardiv','calendardivId')); }catch(e){ }</script>
                    <?php  }
        }
        $this->benchmark->mark('Footer_New_Calendar_Div_end');
    }
    if(!isset($commonOverlayDivLoaded) || $commonOverlayDivLoaded ==0){
        $this->benchmark->mark('Footer_New_Overlay_start');
        ?>
        <script>try{ overlayViewsArray.push(new Array('network/commonOverlay','addRequestOverlay')); }catch(e){ }</script>
        <?php $this->benchmark->mark('Footer_New_Overlay_end');
    }
?>

<?php 
$this->benchmark->mark('Footer_New_CSS_JS_start');
$this->load->view('common/footer/cvsJsInclude');
$this->benchmark->mark('Footer_New_CSS_JS_end');

global $clientIP;

if(strpos($clientIP,"shiksha")!==false) { 
$this->benchmark->mark('Footer_New_GA_start');
	 $this->load->view('common/ga'); 
$this->benchmark->mark('Footer_New_GA_end');
} 

/*
//Commented by Ankur Gupta on 28 Nov. The usage of this Tracker is not clear and it is throing 500 error on many pages of Shiksha
if(PAGETRACK_BEACON_FLAG)
{
    $this->load->view('common/pageTrack_beacon.php');
} */

/**********************************
 *
 * Management remarketing Code
 * for tagging management traffic
 * 
 **********************************/
if($showApplicationFormHomepage || (is_array($mainCategoryIdsOnPage) && in_array(3,$mainCategoryIdsOnPage))) {
$this->benchmark->mark('Footer_New_Remarketing_Code_start');
        $this->load->view('multipleMarketingPage/managementRemarketingCode');
$this->benchmark->mark('Footer_New_Remarketing_Code_end');
}
?>

</body>
</html>

<script>
// added for switch tracking of search results on and off
var track_search_results_flag = "<?php echo TRACK_SEARCH_RESULTS;?>";
var facebook_channel_path = "<?php echo FB_CHANNEL_PATH; ?>";

<?php 
if(!in_array($product, array('SearchV2','ranking'))) { 
$this->benchmark->mark('Footer_New_Post_Question_start'); ?>
	function proceedToPostQuestionFromHome(objForm,id){
	    objForm.setAttribute("method",'get');
	    objForm.setAttribute("action",'<?php echo SHIKSHA_ASK_HOME."/messageBoard/MsgBoard/postQuestionFromCafeForm"; ?>');
	    objForm.submit();
	}
<?php 
$this->benchmark->mark('Footer_New_Post_Question_end'); }
?>

</script>



<?php 
$this->benchmark->mark('Footer_New_Common_Code_start');
$this->load->view('common/footer/footerCommonCode');
$this->benchmark->mark('Footer_New_Common_Code_end');
?>
<?php
if(!(is_array($validateuser) && is_array($validateuser[0]) && (isset($validateuser[0]['userid']))&& !empty($validateuser[0]['userid']))) {
$this->benchmark->mark('Footer_New_Sticky_Registration_start');
    global $pagesToShowBtmRegLyr;
    if(in_array($beaconTrackData['pageIdentifier'], $pagesToShowBtmRegLyr)){ 
        $this->load->view('common/bottomStickyRegistrationLayer');
    }
$this->benchmark->mark('Footer_New_Sticky_Registration_end');
}
?>

<script>
<?php
/*
 * TODO: This is temp FIX !!! Will remove following lines once get update static pages for 404,505 etc.
 * NO NEED TO EXCUTE OVERLAYS AJAX CALL IN 404 ERROR PAGE
 *
 */
if ((!isset($errorPageFlag)) && ($errorPageFlag != 'true')) {
$this->benchmark->mark('Footer_New_2Sec_Wait_start');
?>
  setTimeout(function(){if(typeof loadViewsOnPageLoad == 'function'){loadViewsOnPageLoad();} },2000);
<?php
$this->benchmark->mark('Footer_New_2Sec_Wait_end');
}
?>
</script>


	
<?php if($_GET['showregisterfree'] == 1) { $this->benchmark->mark('Footer_New_Register_Free_start'); ?>
<script type="text/javascript">
	$j(document).ready(function(){
		trackEventByGA('RegisterClick','HEADER_REGISTRATION_BTN');
		data = {};
		data['callregisterfreeform']  = 1;
		shikshaUserRegistration.showRegisterFreeLayer(data);
		});		
</script>
<?php $this->benchmark->mark('Footer_New_Register_Free_end'); } ?>


<?php 
if($product !='home'){
    $this->benchmark->mark('Footer_New_Custom_Course_List_start');
	$this->load->view('common/footer/customCoursesListCode');
    $this->benchmark->mark('Footer_New_Custom_Course_List_end');
}
?>

<?php if(isset($_COOKIE['discussion_not_posted']) && $_COOKIE['discussion_not_posted'] == 'yes') { 
    $this->benchmark->mark('Footer_New_Discussion_Code_start'); ?>     
<script>
var cookieValue = getCookie("discussion_not_posted");
if (cookieValue == 'yes' ){
    unsetDiscussionNotPostingCookie();
    $j('#genOverlayDscns, #dim_bg').show();
    $j('#dim_bg').css('height', $j(document).height()); 
    $j('#genOverlayDscns').css('left', $j(window).width()/2 - $j('#genOverlayDscns').width()/2); 
    $j('#genOverlayDscns').css('top', $j(window).height()/2 - $j('#genOverlayDscns').height()/2); 

}
</script>
<?php $this->benchmark->mark('Footer_New_Discussion_Code_end'); } ?>
<?php
if($trackingpageIdentifier=='rankPredictor' || $trackingpageIdentifier=='collegePredictor'){
?>
<!-- Facebook Pixel Code -->
<!--
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','//connect.facebook.net/en_US/fbevents.js');
fbq('init', '639671932819149');
fbq('track', "PageView");

fbq('track', 'CompleteRegistration');

</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=639671932819149&ev=PageView&noscript=1"
/></noscript>
-->
<!-- End Facebook Pixel Code -->
<?php
}

?>

<div id="signUpForm">
    
</div>
<?php
 $this->benchmark->mark('Footer_New_CSRF_start');
$CI = & get_instance();
$CI->load->library('security');
$CI->security->setCSRFToken();
?>
<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
<input type="hidden" id="footerExecuted" value="true">

<?php 
$this->benchmark->mark('Footer_New_CSRF_end');

// below code is used to verify all URL's from the current page
      // added by - akhter
    $urlPage = isset($_GET['autoUrl']) ? $_GET['autoUrl'] :''; 
    if($urlPage && !is_numeric($urlPage) && ALLOW_AUTOURL_SCRIPT == TRUE){
        $this->benchmark->mark('Footer_New_Verify_URL_start');

        $this->load->config('common/urlSelectorConfig',true);
        $cdata  = $this->config->item('urlSelectorConfig');
        $cdata  = $cdata['desktop'][$urlPage];
        if($cdata){
            $selector = json_encode($cdata);
        }
        echo "<script> var ele = $selector;</script>";
        if(isset($_SERVER["HTTP_USER_AGENT"]) && !stristr($_SERVER["HTTP_USER_AGENT"],'Firefox')){
            echo "<script>alert('Your browser is not supported. Please open in Firefox only.');</script>";
        }else{?>
            <script type="text/javascript">
                $j(window).load(function() {
                    automatedUrlScript();
                });
            </script>
        <?php }

        $this->benchmark->mark('Footer_New_Verify_URL_end');
}?>

<?php if($_GET['showSignUp'] == 1) { 
$this->benchmark->mark('Footer_New_Show_SignUp_start'); ?>
<script type="text/javascript">
    setTimeout(function(){
        $j('.n-loginSgnup a').click();
    },300);

</script>
<?php 
$this->benchmark->mark('Footer_New_Show_SignUp_end');
	} ?>
<script>
if(shikshaProduct=='CMSExam'){
var elem = document.getElementById("cookieAdSlot-d");
elem.parentNode.removeChild(elem);
}
</script>
