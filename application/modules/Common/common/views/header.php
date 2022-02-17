<!DOCTYPE html>
<html xmlns:fb="https://www.facebook.com/2008/fbml">
	<head>
		<?php 
		if(isset($isAbTestPage) && $isAbTestPage == 'yes') {
			$this->load->view('common/contentExperimentCode');
		} 

		// contains meta tags,robots,canonical,Pagination prev and next,favicon
		$this->load->view('common/header/metaInfo');
		//load css files in header
		$this->load->view('common/header/headerCSS');
		$this->load->view('common/TrackingCodes/JSErrorTrackingCode');
		//jsb9createCookie
		echo getHeadTrackJs();
	?>
	<script type="text/javascript">
	function lazyLoadCss(cssFile){
	var cb = function() {
    var l = document.createElement('link'); l.rel = 'stylesheet';
    l.href = cssFile;
    var h = document.getElementsByTagName('head')[0]; h.appendChild(l);
    };
    var raf = requestAnimationFrame || mozRequestAnimationFrame ||
    webkitRequestAnimationFrame || msRequestAnimationFrame;
    if (raf) raf(cb);
    else window.addEventListener('load', cb);
}</script>
	<?php
		//Amit Singhal : To Avoid loading of header.js twice
		unset($js[array_search("header",$js)=="FALSE"?array_search("header",$js):-10]);
		unset($jsFooter[array_search("header",$jsFooter)=="FALSE"?array_search("header",$jsFooter):-10]);
		//Amit Singhal : To Avoid loading of header.js twice
		
		//load js files in header
		$this->load->view('common/header/headerJS');
		?>
<?php
//need to ask
global $jsRepos;
header('Vary: User-Agent');
$this->load->library('category_list_client');
$categoryClient = new Category_list_client();
$jsRepos        = $categoryClient->getJSFromRepos();
?>
<script type="application/ld+json">
{
	"@context" : "http://schema.org",
	"@type"    : "Organization",
	"url"      : "https://www.shiksha.com",
	"logo"     : "https://www.shiksha.com/public/images/nshik_ShikshaLogo1.gif",
	"name"     : "Shiksha.com",
	"sameAs"   : ["https://www.facebook.com/shikshacafe", "https://twitter.com/ShikshaDotCom", "https://en.wikipedia.org/wiki/Shiksha.com", "https://plus.google.com/+shiksha"]
}
</script>
<?php
	if($product == 'examPage'){
?>
<script type="application/ld+json"> 
{
	"@context"        : "http://schema.org",
	"@type"           : "Article",
	"mainEntityOfPage": {"@type": "WebPage", "@id":"<?php echo $currentUrl; ?>"},
	"headline"        : "<?php echo $titleText; ?>",
	"dateModified"    : "<?php echo $homepageData['updationDate']; ?>",
	"datePublished"   : "<?php echo $homepageData['creationDate']; ?>",
	"author"          : {"@type":"Person","name":"Shiksha"},
	"publisher"       : {"@type":"Organization","name":"Shiksha","logo":{"@type":"ImageObject","name":"shiksha","url":"https://www.shiksha.com/public/images/shiksha-amp-logo.jpg","height":60,"width":167}}
}
</script>
<?php
	}
?>
<script type="text/javascript">
var OF_PAYTM_INTEGRATION_FLAG = '<?php echo OF_PAYTM_INTEGRATION_FLAG;?>', urlforveri = 'https://<?php echo SHIKSHACLIENTIP;?>', home_shiksha_url = '<?php echo SHIKSHA_HOME;?>', currentPageName= null, isCompareEnable = false;
<?php if(SHOW_AUTOSUGGESTOR){ ?>
	var SHOW_AUTOSUGGESTOR_JS = true;
<?php } else { ?>
	var SHOW_AUTOSUGGESTOR_JS = false;
<?php } ?>

<?php if(TRACK_AUTOSUGGESTOR_RESULTS){ ?>
	var TRACK_AUTOSUGGESTOR_RESULTS_JS = true;
<?php } else { ?>
	var TRACK_AUTOSUGGESTOR_RESULTS_JS = false;
<?php }

if(TRACK_SEARCH_RESULTS) {  ?>
  var TRACK_SEARCH_RESULTS_JS = true;
<?php } else { ?>
	var TRACK_SEARCH_RESULTS_JS = false;
<?php } ?>

<?php if (is_array($validateuser) && is_array($validateuser[0]) && (isset($validateuser[0]['userid']))&& !empty($validateuser[0]['userid'])): ?>
isUserLoggedIn = true;
<?php else: ?>
isUserLoggedIn = false;
<?php endif; ?>
<?php addJSVariables(); ?>
var COOKIEDOMAIN = '<?php echo COOKIEDOMAIN; ?>';
<?php global $validDomains; ?>
<?php global $invalidEmailDomains; ?>
var validDomains = <?php echo json_encode($validDomains); ?>;
var invalidDomains = <?php echo json_encode($invalidEmailDomains); ?>;
<?php /*// START $GLOBAL variables used for checking is ldb user, which one form need to be open and if unified overlay closed for a session */ ?>
var messageObj = null, unified_form_overlay1_cancel_clicked = false, unified_form_overlay2_cancel_clicked = false, unified_form_overlay3_cancel_clicked = false, arr_unified = new Array(), page_identifier_unified = '', listingdetailpage_unified_thankslayer_identifier = '', unified_widget_identifier = '';
<?php /*// END $GLOBAL variables used for checking is ldb user, which one form need to be open and if unified overlay closed for a session*/ ?>

<?php /*// START GLOBAL variables for F-SHARE*/ ?>
var FACEBOOK_API_ID = '<?php echo FACEBOOK_API_ID; ?>', facebook_channel_path = "<?php echo FB_CHANNEL_PATH; ?>";
<?php /*// START GLOBAL variables for F-SHARE*/ ?>
</script>

<?php 
//removing adsense from search result page
if(!in_array($product, array('home','ranking','anaDesktopV2','institutePage','coursePage','allContentPage','resultPage', 'ArticlesD','examPage','shiksha_analytics'))){
	$this->load->view('common/header/googleAdsense');	 
}

//Adding New google ad sense on Homepage
if(in_array($product, array('home'))){
        $this->load->view('common/header/googleAdsenseNew');
}

$stream = 0;
if(isset($gtmParams['stream']) && $gtmParams['stream']!=''){
	$stream = $gtmParams['stream'];
}
?>

<?php 
	$this->load->view('dfp/dfpCommonCode');
 ?>

</head>

<?php
	switch ($product) {
		case 'online':
			$bodyClass = 'class="onlineForm"';
			break;
		
		case 'SearchV2':
		case 'Category':
		case 'AllCoursesPage':
			$bodyClass = 'class="cat-fitt-toscr"';
			break;

		default:
			$bodyClass = '';
			break;
	}
?>
<body id="wrapperMainForCompleteShiksha" <?php echo $bodyClass; ?> >
<div style="display:none;">
<?php
if($_REQUEST['mmpbeacon'] != 1) {
    loadBeaconTracker($beaconTrackData);
}
?>
</div>
<script>
var overlayViewsArray = new Array(), bannerPool = new Array();
function pushBannerToPool(bannerId, bannerUrl){
    if(bannerId != '') bannerPool[bannerId] = bannerUrl;
}
<?php if(in_array($product, array('ArticlesD', 'home'))) {  ?>
function getCookie(c_name){
    if (document.cookie.length>0){
        c_start=document.cookie.indexOf(c_name + "=");
        if (c_start!=-1){
            c_start=c_start + c_name.length+1;
            c_end=document.cookie.indexOf(";",c_start);
            if (c_end==-1) { c_end=document.cookie.length ; }
            return unescape(document.cookie.substring(c_start,c_end));
        }
    }
    return "";
}
<?php } ?>
</script>
	<?php
	//removing google Tag Manager from search result page & home page & ranking page
	if(!in_array($product, array('home'))){
		$this->load->view('common/googleCommon');
	}
	?>

	<?php 
    //Start: Add first gutter banner here. This will be displayed only if the resolution is above 1024 px
	if(isset($showGutterBanner) && $showGutterBanner=='1' && !in_array($product,array('SearchV2','home'))){
		if(isset($gutterBannerAlignment) && $gutterBannerAlignment == 'top'){
			$topPos = "bottom: -5px;";
		} else {
			$topPos = "top: 200px;";
		}
	?>
	<div id="fixme" style="left: 0px; position: fixed;  top:0; width:178px; overflow:visible; z-index:99; display:none"></div>
		<div id="fixme1" style="position: fixed; _position:absolute; right:13px; <?=$topPos?>  width:130px; overflow:visible; z-index:99; display:none">
	    <div id="fixme2" style="padding:7px 7px 7px 15px" tabindex="7">
		<?php
		if(!(isset($bannerPropertiesGutter) && is_array($bannerPropertiesGutter)))
		$bannerPropertiesGutter = array('pageId'=>'HOME', 'pageZone'=>'RIGHT_GUTTER');
		$this->load->view('common/banner',$bannerPropertiesGutter);
		?>
	    </div>
	</div>
     <script>
	      //If the resolution of the browser is less than 1024, hide the Gutter banners
	      if (screen.width>1024){
		      document.getElementById('fixme1').style.display = '';
	      }
	</script>
	<?php } ?>

	
    <?php 

	if($dfpData['parentPage']=='DFP_CategoryPage'){
                $this->load->view('dfp/dfpHeader');
        }
	if(!$invisibleGNB){
    	    $resetPage = (isset($_GET['resetPage']) && ($_GET['resetPage'] == '1')) ? true : false;
    	    $gnbCache = "HomePageRedesignCache/gnb.html";
    		if(!(file_exists($gnbCache) && (time() - filemtime($gnbCache)) <= (30*24*60*60)) || $resetPage == 1){
    			Modules::run('common/GlobalShiksha/getGNBConfig');
    		}
    		$this->load->view('common/html5Header',array('gnbCache'=>$gnbCache,'resetPage'=>$resetPage));    		
    		if(!isset($bannerProperties)){
    			$bannerProperties = array();
    		}
    		$adsCheck = isShowingAds($product,$bannerProperties); 
    	}?>
    
    <div id="main-wrapper">
    	<noscript> <div class="jser"> <img style="vertical-align: middle;" src="https://w10.naukri.com/jobsearch/images/jsoff.gif"/>Javascript is disabled in your browser due to this certain functionalities will not work.<a target="_blank" href="/public/enableJavascript.html">Click Here</a>, to know how to enable it.</div></noscript>

    	<?php if(isset($_COOKIE['discussion_not_posted']) && $_COOKIE['discussion_not_posted'] == 'yes') {  ?>
			<div id="genOverlayDscns" class="overlay" style="position: absolute; z-index: 100001; width: 485px; height: 350px; display: none; left: 300px;">
				<div>
					<div id="genOverlayHolderDiv" class="blkRound">
						<div class="layer-title" id="genOverlayTitleCross_hack">
							<a title="Close" onclick="$j('#dim_bg').hide(); $j('#genOverlayDscns').remove();" id="overlayCloseCross" class="close" href="javascript:void();"></a>
							<span>You do not have rights to post a discussion, only a user at level 11 and above can start a discussion.</span><br/>
						<a href="<?=SHIKSHA_HOME?>/shikshaHelp/ShikshaHelp/upsInfo">Click here,</a> to see how you can see move up the level. 
						</div>
					</div>
				</div>
			</div>     
		<?php } ?>
	
 <div id="content-wrapper">
	<?php if(isset($pageType) && in_array($pageType ,array('course','institute')))
		{
			$top = 100;
		}
		else{
			$top = 300;
		}
	?>
	<!-- floating online form widget -->
	<div id="incomplete-widget">
	</div>	
             

 		<div class="wrapperFxd" id="main-wrapper">
<?php
if($dfpData['parentPage']!='DFP_CategoryPage' && !in_array($product,array('IIM_PERCENTILE','campusAmbassador','writeForUs','CollegeReviewForm','collegeCutoff','online'))){
        $this->load->view('dfp/dfpHeader');
}
$tmp = $product;
global $product;
$product = $tmp;
// function getClass($str)
// {
// 	global $product;
// 	if(strtolower($product) == 'event' && strtolower($str) == 'events') {
// 		$str = 'Event';
// 	}
// 	if (strtolower($str)==strtolower($product)) {
// 		return "tabSelected";
// 	} else {
// 		return "";
// 	}
// }
/*
 * function name
 * @param $arg
 */

// function name($arg) {

// }

if((isset($_COOKIE['user_force_cookie']) && ($_COOKIE['user_force_cookie'] == 'YES')) && (!strstr($_SERVER["HTTP_USER_AGENT"],'iPad'))) { ?>
<div onclick="window.location='<?php echo SHIKSHA_HOME . "/mcommon5/MobileSiteStatic/viewMobileSite"; ?>';" style="cursor: pointer;border:1px solid #c0c0c0; background-image:url(/public/mobile/images/mob-banner-bg.jpg); background-position:left bottom; background-repeat:repeat-x; background-color:#ffffff; padding:10px 0 7px 0 ;overflow:hidden;">
    <div style="background-image:url(/public/mobile/images/mob-banner-icn.jpg); background-position:left bottom; background-repeat:no-repeat; padding:10px 0 12px 95px; font-size:38px; font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-weight:bold; width:400px; margin:0 auto; line-height:40px">Tap here to use the <br />Shiksha mobile site.</div>
</div>
<?php
}
 
 $showBottomMargin = isset($showBottomMargin)?$showBottomMargin:true;
 if($searchEnable == true){
	// $data = array();
	// if(count($request)>0)
	// {
	// 	//load helper to check if tab is required on category page
	// 	$this->load->helper('coursepages/course_page');
	// 	$tabRequired = checkIfCourseTabRequired($request->getSubCategoryId());
	// }
	// else
	// {
	// 	$tabRequired = FALSE;
	// }
	// $data['page_type'] = $product;
	// if ($tabRequired !== TRUE)
	// {
	// 	$this->load->view('common/headerSearch', $data);
	// }
 }else if($showBottomMargin){
?>
	<div class="spacer10 clearFix"></div>
<?php	 
 }

 if(!empty($breadcrumbHtml) && $breadcrumbHtml != '') {
 	echo $breadcrumbHtml;
 }
 else {

 	if($isCategoryPage){
 		$this->load->view('categoryList/breadCrumb');
 	}
 	if($isNaukriPage){
 		$this->load->view('NaukriTool/breadCrumb');
 	}

 	if($isShortlistPage){
 		$this->load->view('myShortlist/breadCrumb');
 	}
 }

?>

<?php if($isCategoryPage && $request->isStudyAbroadPage()){ ?>
<div class="clearFix"></div>
<?php } ?>

<?php if(!(isset($showApplicationFormHeader) && $showApplicationFormHeader == true)){ ?>

<div id = "loginCommunication" class = "showMessages" style = "display:none; clear:both; width:945px; margin:0 0 10px 10px;">
	<div class="close-icon" onClick="hidelogindiv()">&nbsp;</div>
	<span id = "logindiv" style = "font-size:11px"></span>
</div>

<script>
if(typeof(getCookie) == 'function' && getCookie('userresponse') != '') {
senduserResponse();
} else {
    <?php
        if(is_array($validateuser) && isset($validateuser[0])) { ?>
            var cookie = getCookie('user').split('|');var msg = cookie[2];
            var comm = '';
            if(( msg == "hardbounce" || msg == "softbounce") &&  (getCookie('user').indexOf('hideVerification') < 0))
            {
               if(msg == "softbounce")
                    comm = "We experienced problem sending email to the address " + cookie[0] + " you provided. Please <a href = '#' onClick = 'showchangeEmailOverlay()'>click here</a> to change the email address or <a href = '#' onClick = 'showverificationMailOverlay()'>click here</a> to resend verification mail and continue using Shiksha.com and avail its benefits.";
                        if(msg == "hardbounce")
                            comm = "The email address - " + cookie[0] + " you provided appears to be invalid. <a href = '#' onClick = 'showchangeEmailOverlay();'>Click here</a> to provide the correct email address to continue using Shiksha.com and avail its benefits.";
            }
            <?php
        }
    ?>
}
</script>
<?php } ?>

<?php 
$this->load->view('common/disablePageLayer.php');
if($product != 'examPage'){
	$this->load->view('common/overlay.php');
}
?>
<script>
if(typeof pushIntoOverlayViewsArray == 'function'){
	pushIntoOverlayViewsArray();
}

// if(document.body.offsetWidth < 1000 && document.getElementById('careername'))
// {
// document.getElementById('careername').innerHTML = 'All Courses';
// }
if(typeof(setClientCookie) == 'function'){
	setClientCookie();
}
</script>
<?php
global $jsToBeExcluded;
// $jsToBeExcluded[] = 'cityList';
$alreadyAddedJs = array();
if(!isset($js)){
    $js = array();
}
$jsOrig = $js;
$js = getJsToInclude(array_unique(array_merge($alreadyAddedJs, $js)));
    if(isset($js) && is_array($js)) {
        foreach($js as $jsFile) {
?>
                <script language="javascript" src="<?php echo $jsFile;?>"></script>
<?php
			if(stripos($jsFile,"customCityList") > 0){
				$this->load->library('common/CustomCities');
				$this->customcities->addCustomCities();
			}
		}
    }
?>

<?php
//Added to check the Blacklisted words in display name
$newA = file_get_contents("public/blacklisted.txt");
global $institutesWithoutUnified;
?>
<script>
var blacklistWords = new Array(<?php echo $newA;?>), institutesWithoutUnified = <?=json_encode($institutesWithoutUnified)?>, SEARCH_PAGE_URL_PREFIX = '<?php echo SEARCH_PAGE_URL_PREFIX; ?>', DO_SEARCHPAGE_TRACKING = '<?php echo DO_SEARCHPAGE_TRACKING; ?>',DO_TUPLE_TRACKING = '<?php echo DO_TUPLE_TRACKING; ?>', productPage = '<?php echo $product;?>';
if( productPage== 'institutePage' || productPage == 'allContentPage' || productPage== 'coursePage' || productPage== 'ranking' || productPage == 'myShortlist' || productPage == 'thirdPartyResult' || productPage == 'ArticlesD' || productPage == 'shiksha_analytics' || productPage=="collegePredictorV2" || productPage == 'iimPredictor')
  {
    var lazydBRecolayerCSS = '//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('searchTuple'); ?>';  
  }
var SHIKSHA_HOME = '<?php echo SHIKSHA_HOME; ?>';
var SHIKSHA_TRACK_HOME = '<?php echo SHIKSHA_TRACK_HOME; ?>';
</script>
