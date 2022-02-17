<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<!-- <link href="/public/css/homepage.css" type="text/css" rel="stylesheet" /> -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<title>
	<?php 
	$title = isset($title) ? $title : ''; 
	echo $title; 
	$notShowSearch = isset($notShowSearch)?$notShowSearch:false;
	?>
	</title>
    <?php echo getHeadTrackJs(); ?>
	<link rel="icon" href="/public/images/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="/public/images/favicon.ico" type="image/x-icon" />
	<link rel="publisher" href="https://plus.google.com/+shiksha"/> 
	<meta name="verify-v1" content="4ijm0YHCDh8EJGQiN9HxXsBccQg1cbkBQi6bCRo/xcQ=" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<META NAME="Description" CONTENT="<?php echo isset($metaDescription)?$metaDescription:''; ?>"/>
	<META NAME="Keywords" CONTENT="<?php echo isset($metaKeywords)?$metaKeywords:''; ?>"/>
	<META NAME="TITLE" CONTENT="<?php echo isset($metaTitle)?$metaTitle:''; ?>"/>
	<meta name="copyright" content=" <?php echo date('Y') ;?> Shiksha.com" />
	<meta name="content-language" content="EN" />
	<meta name="author" content="www.Shiksha.com" />
	<meta name="resource-type" content="document" />
	<meta name="distribution" content="GLOBAL" />
	<meta name="robots" content="ALL" />
	<meta name="revisit-after" content="1 day" />
	<meta name="rating" content="general" />
	<meta name="pragma" content="no-cache" />
	<meta name="classification" content="Education and Career: education portal, college university directory, career forum" />
    <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("common_new"); ?>" type="text/css" rel="stylesheet" />
    <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("shikshaLayout"); ?>" type="text/css" rel="stylesheet" />
    <?php 
    	// echo includeCSSFiles('desktopCommon');
    ?>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
<?php
if($product == 'home' && $_SERVER['REQUEST_URI'] == '/') {
?>
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("shiksha_common"); ?>" type="text/css" rel="stylesheet" />
<?php
}elseif(isset($css) && is_array($css) && !in_array('mainStyle',$css)){ ?>
		<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("common"); ?>" type="text/css" rel="stylesheet" />
<?php	}else{ ?>
		<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("mainStyle"); ?>" type="text/css" rel="stylesheet" />
<?php
}
?>


<?php 
global $jsRepos;
$this->load->library('category_list_client');
$categoryClient = new Category_list_client();
$jsRepos = $categoryClient->getJSFromRepos();
?>
<?php $cssExclude = array('footer','shiksha_common','mainStyle','header'); 
 		if(isset($css) && is_array($css)) :
	 foreach($css as $cssFile) : 
 if(!in_array($cssFile,$cssExclude)) { ?>
            <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion($cssFile); ?>" type="text/css" rel="stylesheet" />
<?php } 
	 endforeach;
     endif;
?>

<?php
//If show search box than only load the search.css file
if(!$notShowSearch) {
?>
	<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("search"); ?>" type="text/css" rel="stylesheet" />
<?php
}
?>
<!-- Added for Canonical URL Start -->
<?php if(isset($canonicalURL) && $canonicalURL!=''){ ?>
<link rel="canonical" href="<?php echo $canonicalURL; ?>" />
<?php } ?>
<!-- Added for Canonical URL End -->
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('header'); ?>"></script>
</head>
<noscript> <div class="jser"> <img style="vertical-align: middle;" src="http://w10.naukri.com/jobsearch/images/jsoff.gif"/>Javascript is disabled in your browser due to this certain functionalities will not work.<a target="_blank" href="/public/enableJavascript.html">Click Here</a>, to know how to enable it.</div></noscript>
<div id="enableCookieMsg" style="display:none;margin-left:10px;color:#ff0000" class="jser bld" align="center"></div>
<script>
<?php
if(SHOW_AUTOSUGGESTOR){
?>
	var SHOW_AUTOSUGGESTOR_JS = true;
<?php
} else {
?>
	var SHOW_AUTOSUGGESTOR_JS = false;
<?php
}
?>

<?php
if(TRACK_AUTOSUGGESTOR_RESULTS){
?>
	var TRACK_AUTOSUGGESTOR_RESULTS_JS = true;
<?php
} else {
?>
	var TRACK_AUTOSUGGESTOR_RESULTS_JS = false;
<?php
}
?>

function loadJsFilesInParallel(){
	$LAB
	.script("//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("AutoSuggestor"); ?>")
	.wait(function(){
		if(SHOW_AUTOSUGGESTOR_JS){
			autosuggestorInstanceCheck = setInterval(function(){
				var fileLoaded = false;
				try{
					var aso = new AutoSuggestor();
					fileLoaded = true;
				} catch(e) {
					fileLoaded = false;
				}
				if(fileLoaded){
					clearInterval(autosuggestorInstanceCheck);
					if(typeof(initializeAutoSuggestorInstance) == 'function') {
                        initializeAutoSuggestorInstance();
                    }
				}
			},1000);
		}
	});
}

//LABJs utility loaded in parallel
(function(g,b,d){var c=b.head||b.getElementsByTagName("head"),D="readyState",E="onreadystatechange",F="DOMContentLoaded",G="addEventListener",H=setTimeout;
function f(){ loadJsFilesInParallel();} H(function(){if("item"in c){if(!c[0]){H(arguments.callee,25);return}c=c[0]}var a=b.createElement("script"),e=false;a.onload=a[E]=function(){if((a[D]&&a[D]!=="complete"&&a[D]!=="loaded")||e){return false}a.onload=a[E]=null;e=true;f()};
a.src="/public/js/LAB.min.js";c.insertBefore(a,c.firstChild)},0);if(b[D]==null&&b[G]){b[D]="loading";b[G](F,d=function(){b.removeEventListener(F,d,false);b[D]="complete"},false)}})(this,document);



var currentPageName= null;
<?php addJSVariables(); ?>
var COOKIEDOMAIN = '<?php echo COOKIEDOMAIN; ?>';
function obtainPostitionX(element) {
    var x=0;
    while(element)	{
        x += element.offsetLeft;
        element=element.offsetParent;
    }
    return x;
}

function showMessagesInline1(confirmMsgPlace,msgToBeShown){
	document.getElementById(confirmMsgPlace).style.display = '';
	document.getElementById(confirmMsgPlace).innerHTML = msgToBeShown;
}

function obtainPostitionY(element) {
    var y=0;
    while(element) {
        y += element.offsetTop;
        element=element.offsetParent;
    }
    return y;
}

//Functions to close overlay without gray background end
var bannerPool = new Array();
function pushBannerToPool(bannerId, bannerUrl){
    if(bannerId != '') bannerPool[bannerId] = bannerUrl;
}
// START GLOBAL variables for F-SHARE
var FACEBOOK_API_ID = '<?php echo FACEBOOK_API_ID; ?>';
var facebook_channel_path = "<?php echo FB_CHANNEL_PATH; ?>";
// START GLOBAL variables for F-SHARE
</script>
<body id="wrapperMainForCompleteShiksha">

    <input type="hidden" value="education india events courses colleges scholarships" id="google_keyword"/>
    <!--StartTopHeaderWithNavigation-->
<script>
<?php if ((isset($displayname)) && !empty($displayname)): ?>
isUserLoggedIn = true;
<?php else: ?>
isUserLoggedIn = false;
<?php endif; ?>

var isQuickSignUpUser = <?php echo (is_array($validateuser) && isset($validateuser[0]) && $validateuser[0]['usergroup'] == 'quicksignupuser') ? "true" : "false"; ?>;

setLandingCookie();

function setLandingCookie()
{
if(getCookie('landingcookie') == '')
{
setCookie('landingcookie',location.href);
}
}

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

function setCookie(c_name,value,expiredays) {
	var exdate=new Date();
	exdate.setDate(exdate.getDate()+expiredays);
	document.cookie=c_name+ "=" +escape(value)+";path=/;domain=<?php echo COOKIEDOMAIN; ?>"+((expiredays==null) ? "" : ";expires="+exdate.toGMTString());
	if(document.cookie== '') {
		return false;
	}
	return true;
}

function setClientCookie(){
	try{
		if(getCookie('client') == "" || getCookie('client') ==  null || document.body.offsetWidth != getCookie('client')){
			if(getCookie('client') != "" && getCookie('client') !=  document.body.offsetWidth && document.body.offsetWidth > 1000 && getCookie('client') < 800) {
				setCookie('client',document.body.offsetWidth ,300); 
			} else if(getCookie('client') !=  document.body.offsetWidth && document.body.offsetWidth < 1000 && getCookie('client') > 1000){
				setCookie('client',document.body.offsetWidth ,300); 
			} else {
				setCookie('client',document.body.offsetWidth,300);
			}
            if(getCookie('client') == '') {
                    document.getElementById('enableCookieMsg').style.display = '';
					document.getElementById('enableCookieMsg').innerHTML = "Cookies are not getting set in your browser! Please Check.";
            }
		}
	}catch(e){}
}
setClientCookie();
function flipAskQestionAndSearchForm(tabName)
{
	if(tabName == 'search'){
		document.getElementById('askField_Row1').style.display='none';	
		document.getElementById('searchField_Row1').style.display='block';		
		document.getElementById('searchTabShow_LI').className='selected';
		document.getElementById('searchTabShow_A').className='selected';
		document.getElementById('askTabShow_LI').className='';
		document.getElementById('askTabShow_A').className='';
		//selectDiscussionHomeTab('primaryTab','SearchTab','openTab');
	}else{
		document.getElementById('searchField_Row1').style.display='none';		
		document.getElementById('askField_Row1').style.display='block';			
		document.getElementById('searchTabShow_LI').className='';
		document.getElementById('searchTabShow_A').className='';
		document.getElementById('askTabShow_LI').className='selected';
		document.getElementById('askTabShow_A').className='selected';
		//selectDiscussionHomeTab('primaryTab','AskQuestionTab','openTab');
	}
}
</script>

<!--STARTS: Shiksha Main Navigation-->
<?php if(!$invisibleGNB){
	    $resetPage = (isset($_GET['resetPage']) && ($_GET['resetPage'] == '1')) ? true : false;
	    $gnbCache = "HomePageRedesignCache/gnb.html";
		if(!(file_exists($gnbCache) && (time() - filemtime($gnbCache)) <= (30*24*60*60)) || $resetPage == 1){
			Modules::run('common/GlobalShiksha/getGNBConfig');
		}
		$this->load->view('common/html5Header',array('gnbCache'=>$gnbCache,'resetPage'=>$resetPage));
}?>
<!--ENDS: Shiksha Main Navigation-->

<div id="main-wrapper">
<div id="content-wrapper">
	<div class="wrapperFxd" id="main-wrapper">

<?php
$url=$_SERVER['REQUEST_URI'];
global $categoryText;
$categoryText = 'Career Options';
$tmp = $product;
global $product;
$product = $tmp;


$arrMap = array("all" =>array("display"=>"All","value"=>"","keyword"=>"Keywords like MBA, GMAT","location"=>"Location like Australia"),
	"foreign" => array("display"=>"Foreign Education","value"=>"foreign","keyword"=>"Keywords like MBA, GMAT","location"=>"Location like Australia"),
	"testprep" => array("display"=>"Test Preparation","value"=>"testprep","keyword"=>"Keywords like MBA, GMAT","location"=>"Location like Australia"),
	"institute" => array("display"=>"Institutes","value"=>"institute","keyword"=>"Institutes like IIT, IIM","location"=>"Location like Delhi"),
	"course" => array("display"=>"Courses","value"=>"course","keyword"=>"Courses like MBA, B-Tech","location"=>"Location like Australia"),
	"forums" =>array ("display"=>"Ask & Answer","value"=>"forums","keyword"=>"Questions like \"Should i do Masters?\"","location"=>"Location like Australia"),
	"events" =>array ("display"=>"Events","value"=>"events","keyword"=>"Events like \"Sale of CAT Forms\"","location"=>"Location like Australia"),
	"categoryHeader" => array("display"=> getCategoryTextUI(),"value"=>"Category","keyword"=>"Keywords like MBA, GMAT","location"=>"Location like Australia"));

if(!isset($regularSearch)){
	$regularSearch = "Yes";
}
if($regularSearch=="Yes") {
if(isset($_REQUEST['keyword']) || isset($_REQUEST['location'])){
    $keyword = isset($_REQUEST['keyword']) ? htmlspecialchars_decode(html_entity_decode(str_replace("'","&#039;",$_REQUEST['keyword']))) : htmlspecialchars_decode(html_entity_decode($_COOKIE['searchKeyword']));
    $location = isset($_REQUEST['location']) ? htmlspecialchars_decode(html_entity_decode(str_replace("'","&#039;",$_REQUEST['location']))) : htmlspecialchars_decode(html_entity_decode($_COOKIE['searchLocation']));
     if (isset($_REQUEST['searchType']) && ($_REQUEST['searchType'] != 'ask')) {
    $tempkeyword = $keyword;
    $templocation = $location;
    }
    else {
    $tempkeyword = '';
    $templocation = '';
    }
}
else{
    $keyword =search_highlight();
    if(strlen($keyword) > 0){
        echo "<script>setCookie('searchKeyword','$keyword');</script>";
    }
}
}
if(isset($product) && ($product=='home')) {
	$product = 'all';
}
if ($categoryText=="Career Options" && !($product=="events" || $product=="forums"|| $product=="Network"|| $product=="myShiksha"|| $product=="inbox"|| $product=="Alerts") && $product!="search" && $product != 'institute' && $product != 'category' && $product != 'testprep' && $product != 'Articles')
$product = "all";
if (isset($categoryData) && $product != 'institute')
{
	if ($categoryData['page']=="ENTRANCE_EXAM_PREPARATION_PAGE" || $categoryData['page']=="EXAM_DETAIL_PAGE") 
	$product = "testprep";
	else if  ($categoryData['page']=="FOREIGN_PAGE")
	$product = "foreign";
	else if  ($product =="institute")
	$product = "institute";
	else if  ($categoryData['page']=="INDIA_PAGE")
	$product = "all";
	else
	$product = "categoryHeader";
}
if (($product=="search") && ($regularSearch=="Yes"))
{
	if (isset($_REQUEST['searchType']) && $_REQUEST['searchType']!="" && $_REQUEST['searchType']!="Country") {
		$product = $_REQUEST['searchType'];
		$searchType = $_REQUEST['searchType'];
	} else {
		$product = "all";
		$searchType = "";
	}
}
if ($regularSearch=="Yes") {
$catID = isset($_REQUEST['cat_id'])? $_REQUEST['cat_id'] : -1;
if (isset($_REQUEST['cat_id']) && $_REQUEST['searchType']=="Category")
{
	$product = "categoryHeader";
	global $categoryMap;
	foreach ($categoryMap as $categoryName => $category)
	{
		if ($category['id']==$_REQUEST['cat_id'])
		{
			$categoryData['name'] = $category['name'];
		}
	}
}
}
function getClass($str)
{
	global $product;
	if (strtolower($str)==strtolower($product)) {
		return "tabSelected";
	} else {
		return "";
	}
}
if(isset($categoryData)) {
	global $catName ;
	$catName=$categoryData['name'];
}
function getCategoryTextUI()
{
	return "Career Options"; //For New Change
	global $product;
	global $catName;
	if ($product == "categoryHeader")
	{
		$catNameArr = split(' ', $catName);
		return str_replace(',','',$catNameArr[0]);
	}
	else
	{
		return "Career Options";
	}
}
	if (!(isset($search) && $search=="false")) {
		if ($regularSearch=="Yes") {
		  $startOffSetSearch = (isset($_REQUEST['startOffSetSearch']) && is_numeric($_REQUEST['startOffSetSearch'])) ? $_REQUEST['startOffSetSearch'] : 0;
		  $countOffsetSearch = (isset($_REQUEST['countOffsetSearch']) && is_numeric($_REQUEST['countOffsetSearch'])) ? $_REQUEST['countOffsetSearch'] : 25;
		  $subCategory = (isset($_REQUEST['subCategory']) && is_numeric($_REQUEST['subCategory'])) ? $_REQUEST['subCategory'] : -1;
		  $subLocation = (isset($_REQUEST['subLocation']) && is_numeric($_REQUEST['subLocation'])) ? $_REQUEST['subLocation'] : -1;
		  $cityId = (isset($_REQUEST['cityId']) && is_numeric($_REQUEST['cityId'])) ? $_REQUEST['cityId'] : -1;
		  $cType = isset($_REQUEST['cType']) ? $_REQUEST['cType'] : -1;
		  $courseLevel = isset($_REQUEST['courseLevel'])  ? $_REQUEST['courseLevel'] : -1;
		  $durationMin = (isset($_REQUEST['durationMin']) && is_numeric($_REQUEST['durationMin'])) ? $_REQUEST['durationMin'] : -1;
		  $durationMax = (isset($_REQUEST['durationMax']) && is_numeric($_REQUEST['durationMax'])) ? $_REQUEST['durationMax'] : -1;
		  $subType = (isset($_REQUEST['subType']) && $_REQUEST['subType']!= '') ? $_REQUEST['subType'] : 0;
		  $showCluster = (isset($_REQUEST['showCluster']) && is_numeric($_REQUEST['showCluster'])) ? $_REQUEST['showCluster'] : -1;
		}
	/* Required for user oriented sections. like Account & Settings, Alerts, Messages */
	if ((isset($displayname)) && !empty($displayname)) {
		$onclick = '';
	} else {
        $onRedirection = base64_encode(SHIKSHA_HOME.'/mail/Mail/mailbox');
		$onclick = "showuserLoginOverLay(this,'SHIKSHA_HEADER_TABBARS_MESSAGES','redirect','".$onRedirection."');return false;";
	}
	if($_COOKIE['client']<=800){
		$testPrperationText = 'Test Preparation';
		$eventText = 'Important Dates';
		$tableWidthIE='975px';
		$tableWidthMoz='979px';
		$insidetableWidthIE='969px';
		$insidetableWidthMoz='974px';
		$coloum1='45px';
		$coloum2='190px';
		$coloum3='360px';
		$coloum4='259px';
		$coloum5='110px';
		$selectBox1='130px';
		$inputBox1='337px';
		$inputBox2='227px';
		$inputBoxAsk3='645px';
		$coloum_rValue1='15px';
		$coloum_rValue5='140px';

	} else {
		$eventText = 'Important Dates';
		$testPrperationText = 'Test Preparation';
		$tableWidthIE='975px';
		$tableWidthMoz='979px';
		$insidetableWidthIE='969px';
		$insidetableWidthMoz='974px';
		$coloum1='45px';
		$coloum2='190px';
		$coloum3='360px';
		$coloum4='259px';
		$coloum5='110px';
		$selectBox1='130px';
		$inputBox1='337px';
		$inputBox2='227px';
		$inputBoxAsk3='645px';
		$coloum_rValue1='15px';
		$coloum_rValue5='140px';
	}
	if($_COOKIE['client']>=1150){
		$eventText = 'Important Dates';
		$testPrperationText = 'Test Preparation';
		$tableWidthIE='975px';
		$tableWidthMoz='979px';
		$insidetableWidthIE='969px';
		$insidetableWidthMoz='974px';
		$coloum1='45px';
		$coloum2='190px';
		$coloum3='360px';
		$coloum4='259px';
		$coloum5='110px';
		$selectBox1='130px';
		$inputBox1='337px';
		$inputBox2='227px';
		$inputBoxAsk3='645px';
		$coloum_rValue1='15px';
		$coloum_rValue5='140px';
	}
	  $channelId = isset($_REQUEST['channelId']) ? $_REQUEST['channelId'] : 'home_page';
?>

<?php 
	if ($regularSearch=="No") {
		$product = $searchType;
		$templocation = $location;
		$tempkeyword = $keyword;
	}
?>	
<?php if($product != 'all') {?>
<?php } ?>
<!--End_Navigation-->
<?php if(!($notShowSearch)) { 
	$dataArrayToSearchPanel = array('product' => $product,'askinputWidth' => $askinputWidth,'exampleTextpostion' => $exampleTextpostion,'channelId' => $channelId,'showCluster' => $showCluster,'subType' => $subType,'courseLevel' => $courseLevel,'durationMax' => $durationMax,'durationMin' => $durationMin,'cType' => $cType,'cityId' => $cityId,'subLocation' => $subLocation,'subCategory' => $subCategory,'startOffSetSearch' => $startOffSetSearch,'countOffsetSearch' => $countOffsetSearch,'catID' => $catID,'searchType' => $searchType,'location' => $location,'keyword' => $keyword, 'templocation' => $templocation,'tempkeyword' => $tempkeyword,'tableWidthIE'=>$tableWidthIE, 'tableWidthMoz'=>$tableWidthMoz, 'insidetableWidthIE'=>$insidetableWidthIE, 'insidetableWidthMoz'=>$insidetableWidthMoz, 'coloum1'=>$coloum1, 'coloum2'=>$coloum2, 'coloum3'=>$coloum3, 'coloum4'=>$coloum4, 'coloum5'=>$coloum5, 'selectBox1'=>$selectBox1, 'inputBox1'=>$inputBox1, 'inputBox2'=>$inputBox2, 'inputBoxAsk3'=>$inputBoxAsk3, 'coloum_rValue1'=>$coloum_rValue1, 'coloum_rValue5'=>$coloum_rValue5,'postQuestionKey' => $postQuestionKey);
	$data = array();
	$data['page_type'] = 'account_settings_page';
	//$this->load->view('search/search_bar', $data);
} ?>
<div class="spacer5 clearFix"></div>
<style>
.showMessages{ background:#fffdd6;border:1px solid #facb9d;line-height:25px;padding:0 10px}
</style>

<div id = "loginCommunication" class = "showMessages mar_full_10p" style = "display:none">
<span class="float_R" onClick="hidelogindiv()" style="cursor:pointer;padding-right:21px;position:relative;top:5px;background:url(/public/images/shikIcons.png) no-repeat right -206px;height:14px;overflow:hidden">&nbsp;</span>
<span id = "logindiv" style = "font-size:11px"></span>
</div>
<div class="lineSpace_5">&nbsp;</div>

<script>
var urlforveri = 'http://<?php echo SHIKSHACLIENTIP;?>';
if(getCookie('userresponse') != '')
senduserResponse();
else
{
<?php
if(is_array($validateuser) && isset($validateuser[0])) { ?>
var cookie = getCookie('user').split('|');var msg = cookie[2];
var comm = '';
            if((msg == "hardbounce" || msg == "softbounce") &&  (getCookie('user').indexOf('hideVerification') < 0))
{
if(msg == "softbounce")
comm = "We experienced problem sending email to the address " + cookie[0] + " you provided. Please <a href = '#' onClick = 'showchangeEmailOverlay()'>click here</a> to change the email address or <a href = '#' onClick = 'showverificationMailOverlay()'>click here</a> to resend verification mail and continue using Shiksha.com and avail its benefits."
if(msg == "hardbounce")
comm = "The email address - " + cookie[0] + " you provided appears to be invalid. <a href = '#' onClick = 'showchangeEmailOverlay();'>Click here</a> to provide the correct email address to continue using Shiksha.com and avail its benefits.";
//showMessagesInline1('logindiv',comm);
//document.getElementById('loginCommunication').style.display = '';
}
<?php } ?>
}
function senduserResponse()
{
    if(getCookie('userresponse') != '')
    {
        var cookiesplit = getCookie('userresponse').split('|');
        var key = cookiesplit[0];
        var verifyflag = cookiesplit[1];
        var ans = '';
        if(verifyflag == "unsubscribe")
        {
            ans = confirm("You would not be allowed to login to shiksha.com once you have unsubscribed.Are you sure you want to unsubscribe from shiksha.com ?");
        }
        if(ans == true || verifyflag == "verify")
            sendResponse(key,verifyflag);
        else
	        window.setTimeout(function(){ window.location = 'http://<?php echo SHIKSHACLIENTIP?>'; }, 1000);
            deleteCookie('userresponse');
    }
}

function sendResponse(key,flag)
{
    var xmlHttp = getXMLHTTPObject();
    xmlHttp.onreadystatechange=function() {
        if(xmlHttp.readyState==4) {
            if(xmlHttp.responseText != '')
            {
                var msg = '';
                var result = xmlHttp.responseText;
                var splitresult = result.split("|");
		if(splitresult[0] == "deleted" || splitresult[1] == "invalid")
		{

			if(splitresult[0] == "deleted")
			{
				msg = "Sorry ! You are no longer a valid shiksha.com user. ";
			}
			if(splitresult[1] == "invalid")
				msg = "Sorry ! The url is not valid. Please click on the link sent in the mailer to update your status ";
			if(splitresult[0] == "different" || splitresult[0] == "deleted")
			{
				if(getCookie('user') != '')
				{
					deleteCookie('user');
				}
			}	
		}
		else
		{
                if(flag == "verify")
                {
                    if(splitresult[1] == "already")
                        msg = "You have already verified this email address.Kindly add info@shiksha.com to your email account address book so that you never miss on any communication from shiksha.com.";
                    else
                    {
                        msg = "Thank you. Your e-mail address is successfully verified with us.Kindly add info@shiksha.com to your email account address book so that you never miss on any communication from shiksha.com.";
                    }
                        if(getCookie('user') != '' && splitresult[0] == "same")
                        {
                            var cookieuser = getCookie('user').split('|');
                            var cookieval = cookieuser[0] + '|' + cookieuser[1] + '|verified';
                            setCookie('user',cookieval);
                        }

                }
                else
                {
                    if(splitresult[1] == "already")
                        msg = "You have already unsubscribed for this email address.";
                    else
                        msg = "You have successfully unsubscribed from shiksha.com.You would not be allowed to login to shiksha.com.";
                }
                if(getCookie('user') != '' && (splitresult[0] == "different" || flag == "unsubscribe"))
                {
                    deleteCookie('user');
                }
		}
                showMessagesInline1('logindiv',msg);            
                document.getElementById('loginCommunication').style.display = '';
	            window.setTimeout(function(){ window.location = 'http://<?php echo SHIKSHACLIENTIP?>'; }, 1000);

            }
        }
    };
    var url = '/user/Userregistration/senduserResponse/'+ key + '/' + flag;
    xmlHttp.open("POST",url,true);
    xmlHttp.setRequestHeader("Content-length", 0);
    xmlHttp.setRequestHeader("Connection", "close");
    xmlHttp.send(null);
}
function deleteCookie( name) {
    var path = '/';
    var domain = '';
    if (getCookie( name ) ) document.cookie = name + "=" + ( ( path ) ? ";path=" + path : "") + ( ( domain ) ? ";domain=" + domain : "" ) + ";expires=Thu, 01-Jan-1970 00:00:01 GMT";
}
</script>
<?php
	$showUnreadMessageIcon = '';
	if(is_array($validateuser) && isset($validateuser[0]) && $validateuser[0]['newMessageFlag'] > 0) {
		$showUnreadMessageIcon = '<img src="/public/images/newmail.gif" border=0 align="absmiddle"/>';
	}
?>


<?php } ?>
<script>
overlayViewsArray.push(new Array('network/commonOverlay','addRequestOverlay'));
overlayViewsArray.push(new Array('user/registerConfirmation','ConfirmRegistration'));
overlayViewsArray.push(new Array('common/changeOverlay','sendveriOverlay'));
</script>
<?php
	$this->load->view('common/disablePageLayer.php');
	$this->load->view('common/overlay.php');
	$this->load->view('common/overlayNavigation.php');
    //$this->load->view('network/commonOverlay');
    //$this->load->view('user/registerConfirmation');
?>
<div class="lineSpace_5">&nbsp;</div>
<div id="dataLoaderPanel" style="position:absolute;display:none">
  	<img src="/public/images/loader.gif"/>
</div>
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
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('cityList'); ?>"></script>

<?php
//If show search box than only load the search.js file
if(!$notShowSearch) {
?>
	<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('search'); ?>"></script>
<?php
}
?>

<?php
//Added to check the Blacklisted words in display name
$newA = file_get_contents("public/blacklisted.txt");?>
<script>
var blacklistWords = new Array(<?php echo $newA;?>);
</script>
