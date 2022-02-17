<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<title>
	<?php 
	$title = isset($title) ? $title : ''; 
	echo $title; 
	?>
	</title>
	<link rel="icon" href="/public/images/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="/public/images/favicon.ico" type="image/x-icon" />
	<meta name="verify-v1" content="4ijm0YHCDh8EJGQiN9HxXsBccQg1cbkBQi6bCRo/xcQ=" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<META NAME="Description" CONTENT="<?php echo isset($metaDescription)?$metaDescription:''; ?>"/>
	<META NAME="Keywords" CONTENT="<?php echo isset($metaKeywords)?$metaKeywords:''; ?>"/>
	<meta name="copyright" content=" 2009 Shiksha.com" />
	<meta name="content-language" content="EN" />
	<meta name="author" content="www.Shiksha.com" />
	<meta name="resource-type" content="document" />
	<meta name="distribution" content="GLOBAL" />
	<meta name="robots" content="ALL" />
	<meta name="revisit-after" content="1 day" />
	<meta name="rating" content="general" />
	<meta name="pragma" content="no-cache" />
	<meta name="classification" content="Education and Career: education portal, college university directory, career forum" />

<?php if(isset($css) && is_array($css)) : ?>
	<?php foreach($css as $cssFile) : ?>
        <?php if ($cssFile=="header" && ($_COOKIE['client']<=800)) : ?>
             <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion($cssFile."800x600"); ?>" type="text/css" rel="stylesheet" />
        <?php else: ?>
            <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion($cssFile); ?>" type="text/css" rel="stylesheet" />
        <?php endif; ?>
	<?php endforeach; ?>
<?php endif;

global $jsToBeExcluded;

?>
<?php if((!isset($displayname)) || empty($displayname)) {?>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("EduList"); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("cityList");?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/CalendarPopup.js"></script>
<?php } ?>

<?php if(isset($js) && is_array($js)) :?>
	<?php foreach($js as $jsFile): ?>
            <?php if(in_array($jsFile,$jsToBeExcluded)){?>
                <script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo $jsFile; ?>.js"></script>
            <?php } else { ?>
                <script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion($jsFile); ?>"></script>
            <?php } ?>
	<?php endforeach; ?>
<?php endif; ?>
</head>
<noscript> <div class="jser"> <img style="vertical-align: middle;" src="http://w10.naukri.com/jobsearch/images/jsoff.gif"/>Javascript is disabled in your browser due to this certain functionalities will not work.<a target="_blank" href="/public/enableJavascript.html">Click Here</a>, to know how to enable it.</div></noscript>
<script>

function obtainPostitionX(element) {
    var x=0;
    while(element)	{
        x += element.offsetLeft;
        element=element.offsetParent;
    }
    return x;
}

function obtainPostitionY(element) {
    var y=0;
    while(element) {
        y += element.offsetTop;
        element=element.offsetParent;
    }
    return y;
}
</script>
<body>
    <input type="hidden" value="education india events courses colleges scholarships" id="google_keyword"/>
    <!--StartTopHeaderWithNavigation-->
<script>
<?php if ((isset($displayname)) && !empty($displayname)): ?>
isUserLoggedIn = true;
<?php else: ?>
isUserLoggedIn = false;
<?php endif; ?>

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
				if(setCookie('client',document.body.offsetWidth ,300)) {
					window.location.reload();
				} else {
					alert("Please enable cookies!!");
				}
			} else if(getCookie('client') !=  document.body.offsetWidth && document.body.offsetWidth < 1000 && getCookie('client') > 1000){
				if(setCookie('client',document.body.offsetWidth ,300)) {
					window.location.reload();
				} else {
					alert("Please enable cookies!!");
				}
			} else {
				if(!setCookie('client',document.body.offsetWidth,300)) {
					alert("Please enable cookies!!");
				}
			}
		}
	}catch(e){}
}
setClientCookie();
</script>
<!--Start_SignInBar-->
<div id="headerGradienthome">
	<div>
		<div class="normaltxt_11p_blk float_R" align="right">
			<span id="naukriLogo">&nbsp;</span><span class="setPosTop_3">&nbsp;&nbsp;Hi
			<?php
				$url = base64_encode($taburl);
				if((isset($displayname))&& !empty($displayname)) {
				echo $displayname;
			?>
                    &nbsp;
			        <a href="<?php echo SHIKSHA_HOME; ?>/user/MyShiksha/index">Account &amp; Settings</a>
			        &nbsp;
					<a href="#" onClick = "SignOutUser()">Sign out</a> <!-- |-->
			<?php } else { ?>
					Guest &nbsp;
					<?php if (!(isset($search) && $search=="false")) {?>
					<a href='<?php echo SITE_URL_HTTPS ."user/Userregistration/index/$url"?>'>Join Now</a> |
					<a href="#" id = "signin" name = "signin" onClick = "showuserLoginOverLay('signin')">Sign in</a><!-- |-->
					<?php } else{?>
					<a href="/" id = "Homelink" name = "Homelink">Home</a><!-- |-->
					<?php } ?>
			<?php } ?></span>			
		</div>
		<div style="margin-left:15px;padding-top:2px;" class="normaltxt_11p_blk"><a href="<?php echo ENTERPRISE_HOME; ?>">Add courses and institutes here</a></div>
	</div>
</div>
<!--End_SignInBar-->

<!--Start_Logo_Advertisement-->
<div class="mar_full_10p" style="height:65px">
	<div class="float_R" style="height:65px">            
		<a href="<?php echo SHIKSHA_HOME; ?>" title="Shiksha.com Home :: Education Information Circle" style="text-decoration:none" >
		<span style="background:url(/public/images/headerImage.gif); background-repeat:no-repeat; background-position:0 -689px; padding-left:229px; padding-bottom:35px;display:block" title="Shiksha.com Home :: Education Information Circle" >&nbsp;</span>
		</a>
	</div>
	<div>
		<a href="http://www.naukri.com" title="Naukri.com"><img src="/public/images/logo_naukri.gif" border="0" alt="Naukri.com" /></a>
    	<span style="position:relative;top:-20px;font-size:28px;font-weight:bold;color:#4d4948;" >Education</span>
	</div>
	<div class="clear_R"></div>
</div>
<!--End_Logo_Advertisement-->
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

if(isset($_REQUEST['keyword']) || isset($_REQUEST['location']) || isset($_COOKIE['PHPSESSID'])){
    $keyword = isset($_REQUEST['keyword']) ? htmlspecialchars_decode(html_entity_decode(str_replace("'","&#039;",$_REQUEST['keyword']))) : htmlspecialchars_decode(html_entity_decode($_COOKIE['searchKeyword']));
    $location = isset($_REQUEST['location']) ? htmlspecialchars_decode(html_entity_decode(str_replace("'","&#039;",$_REQUEST['location']))) : htmlspecialchars_decode(html_entity_decode($_COOKIE['searchLocation']));
}
else{
    $keyword =search_highlight();
    if(strlen($keyword) > 0){
        echo "<script>setCookie('searchKeyword','$keyword');</script>";
    }
}
if(isset($product) && ($product=='home')) {
	$product = 'all';
}
if ($categoryText=="Career Options" && !($product=="events" || $product=="forums"|| $product=="Network"|| $product=="myShiksha"|| $product=="inbox"|| $product=="Alerts") && $product!="search" && $product != 'institute'  && $product != 'category' && $product != 'testprep')
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
if ($product=="search")
{
	if (isset($_REQUEST['searchType']) && $_REQUEST['searchType']!="" && $_REQUEST['searchType']!="Country") {
		$product = $_REQUEST['searchType'];
		$searchType = $_REQUEST['searchType'];
	} else {
		$product = "all";
		$searchType = "";
	}
}
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

function getClass($str)
{
	global $product;
	if(strtolower($product) == 'event' && strtolower($str) == 'events') {
		$str = 'Event';
	}
	if(strtolower($product) == 'schoolgroups' || strtolower($product) == 'collegegroup' || strtolower($product) == "groups" ) {
        $product= 'network';
    }
	if (strtolower($str)==strtolower($product)) {
		return "activetab";
	} else {
		return "deactivetab";
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

	/* Required for user oriented sections. like Account & Settings, Alerts, Messages */
	if ((isset($displayname)) && !empty($displayname)) {
		$onclick = '';
	} else {
		$onclick = "showuserLoginOverLay('myShiksha');return false;";
	}
	if($_COOKIE['client']<=800){
		$testPrperationText = 'Test Prep';
		$eventText = 'Imp Dates';
	} else {
		$eventText = 'Important Dates';
		$testPrperationText = 'Test Preparation';
	}
	  $channelId = isset($_REQUEST['channelId']) ? $_REQUEST['channelId'] : 'home_page';
?>

<!--Start_Search-->
<!--[if IE 7]>
<style>
	.searchButtonAll{background-image:url(/public/images/headerImage.gif);  background-repeat:no-repeat; background-position:0 -256px; height:31px; width:98px; color:#FFFFFF; font-weight: bold; font-size:16px; font-family:Arial;position:relative; left:-3px; top:0px}
	.searchTextBox{background-color:#FFFFFF; font-size:16px;padding:0px; margin-right:12px;height:23px; position:relative; top:-1px; padding:2px}
</style>
<![endif]-->
<!--<div style="line-height:6px">&nbsp;</div>-->
<div class="brdHeaderTop mar_full_10p">
	<form id="searchForm" name="searchForm" method="get" action="<?php echo SHIKSHA_HOME_URL; ?>/search/index" onsubmit="return validateSearch();">
        <div class="txt_align_c">
			<div class="lineSpace_5">&nbsp;</div>
			<div class="fontSize_11p bld">
				<span style="position:relative; left:-137px;font-size:11px">Enter Institute, Course, Exam Name, Question etc</span>
				<span style="position:relative; left:-34px;font-size:11px">Enter Country, City etc</span>
			</div>
			<div style="margin-top:-7px">
				<img src="/public/images/search_icon.gif" align="absmiddle" />					
				<input name="keyword" id="keyword" value='<?php echo $keyword; ?>' type="text" class="searchTextBox textBoxColor" style="width:350px" onkeyup="if(event.keyCode == 13){document.getElementById('channelId').value = 'home_page';}"/>&nbsp;
				<input name="location" id="location" value='<?php echo $location; ?>' type="text" class="searchTextBox textBoxColor" style="width:214px" onkeyup="if(event.keyCode == 13){document.getElementById('channelId').value = 'home_page';}"/>
				<button onclick="document.getElementById('channelId').value = 'home_page';" type="submit" class="searchButtonAll">Search</button>
			</div>
			<div style="margin-top:-5px;">
				<span style="position:relative; left:-197px;font-size:11px">Eg. XLRI, MCA, or GMAT</span>
				<span style="position:relative; left:59px;font-size:11px">Eg. Australia, Karnataka or Delhi</span>
			</div>
			<input type="hidden" name="searchType" id="searchType" autocomplete="off" value="<?php echo htmlspecialchars($searchType); ?>"/>
			<input type="hidden" name="cat_id" id="cat_id" autocomplete="off" value="<?php echo htmlspecialchars($catID);?>"/>
			<input name="countOffsetSearch" id="countOffsetSearch" autocomplete="off" value="<?php echo htmlspecialchars($countOffsetSearch); ?>"  type="hidden" />
			<input name="startOffSetSearch" id="startOffSetSearch" autocomplete="off" value="<?php echo htmlspecialchars($startOffSetSearch); ?>" type="hidden" />
			<input name="subCategory" id="subCategory" autocomplete="off" value="<?php echo htmlspecialchars($subCategory); ?>" type="hidden" />
			<input name="subLocation" id="subLocation" autocomplete="off" value="<?php echo htmlspecialchars($subLocation); ?>" type="hidden" />
			<input name="cityId" id="cityId" autocomplete="off" value="<?php echo htmlspecialchars($cityId); ?>" type="hidden" />
			<input name="cType" id="cType" autocomplete="off" value="<?php echo htmlspecialchars($cType); ?>" type="hidden" />
			<input name="durationMin" id="durationMin" autocomplete="off" value="<?php echo htmlspecialchars($durationMin); ?>" type="hidden" />
			<input name="durationMax" id="durationMax" autocomplete="off" value="<?php echo htmlspecialchars($durationMax); ?>" type="hidden" />
			<input name="courseLevel" id="courseLevel" autocomplete="off" value="<?php echo htmlspecialchars($courseLevel); ?>" type="hidden" />
			<input name="subType" id="subType" autocomplete="off" value="<?php echo htmlspecialchars($subType); ?>" type="hidden"/>
			<input name="showCluster" id="showCluster" autocomplete="off" value="<?php echo htmlspecialchars($showCluster); ?>" type="hidden" />
			<input name="channelId" id="channelId" autocomplete="off" value="<?php echo $channelId; ?>" type="hidden"/>
		</div>
		
		<!--
		<div class="searchHelpText darkgray <?php echo $product."InputWidth";?>" id="searchhelptext">
			   <div class="float_L" style="width:68%" id="textforkeyword"><?php echo $arrMap[$product]['keyword'];?></div>
			   <div class="float_L <?php if($product=='forums') echo " hideLoc"; ?>" id="textforlocation"><?php echo $arrMap[$product]['location'];?></div>
				<div class="clear_L"></div>
			</div>
			<div class="clear_L"></div>
			-->
	</form>
</div>
<!--End_Search-->
<?php
	$showUnreadMessageIcon = '';
	if(is_array($validateuser) && isset($validateuser[0]) && $validateuser[0]['newMessageFlag'] > 0) {
		$showUnreadMessageIcon = '<img src="/public/images/newmail.gif" border=0 align="absmiddle"/>';
	}
?>
<!--Start_Navigation-->
<div class="mar_full_10p normaltxt_11p_blk bgNavigation brd_right">
	<div class="navigationBarHeight">
		<div class="float_R">
			<!--<span style="float:left; width:2px"><img src="/public/images/seprator.gif" width="2" height="27" /></span> -->
			<a href="<?php echo SHIKSHA_HOME; ?>/alerts/Alerts/alertsHome" style="text-decoration:none"><span class="<?php echo getClass("alerts"); ?>"><span class="toptxt">Alerts</span></span></a>
			<span style="float:left; width:2px;" class="seprator_header"></span>
			<a href="<?php echo SHIKSHA_HOME; ?>/mail/Mail/mailbox" style="text-decoration:none" onclick="<?php echo $onclick ; ?>"><span class="<?php echo getClass("inbox"); ?>"><span class="toptxt"><?php echo $showUnreadMessageIcon;?> Messages</span></span></a>
			<!--<span style="float:left; width:2px;" class="seprator_header"></span>
			<a href="<?php echo SHIKSHA_HOME; ?>/user/MyShiksha/index" style="text-decoration:none" onclick="<?php echo $onclick ; ?>"><span class="<?php echo getClass("myShiksha"); ?>"><span class="toptxt">Account &amp; Settings</span></span></a>-->
		</div>
		<div>
			<a href="<?php echo SHIKSHA_HOME; ?>"  style="text-decoration:none" title="Education Information"><span onclick="writeSearchCookie();" class="<?php echo getClass("all"); ?>"><span class="toptxt">Home</span></span></a>
<!--			<span style="float:left; width:2px;" class="seprator_header"></span>
			<a href="<?php echo SHIKSHA_GROUPS_HOME; ?>" style="text-decoration:none" title="Search Institute in India and Abroad"><span class="<?php echo getClass("network"); ?>"><span class="toptxt">Groups</span></span></a>-->
            <span style="float:left; width:2px;" class="seprator_header"></span>
			<a href="<?php echo SHIKSHA_ASK_HOME; ?>" style="text-decoration:none" title="Ask and get answers on Education"><span class="<?php echo getClass("forums"); ?>"><span class="toptxt">Ask &amp; Answer</span></span></a>
			<span style="float:left; width:2px;" class="seprator_header"></span>
			<a href="<?php echo SHIKSHA_EVENTS_HOME; ?>" style="text-decoration:none" title="Browse Educational Events"><span class="<?php echo getClass("events"); ?>"><span class="toptxt"><?php echo $eventText; ?></span></span></a>
			<span style="float:left; width:2px;" class="seprator_header"></span>
			<a href="<?php echo 'javascript:void(0);';//SHIKSHA_COUNTRY_HOME_TAB; ?>" style="text-decoration:none" title="Foriegn Education - Study Abroad"><span id="countryOptionTag" class="<?php echo getClass("foreign"); ?>" onclick="drpdwnOpen(this, 'countryOption');"><span class="toptxt">Study Abroad <small>&#9660;</small></span></span></a>
			<span style="float:left; width:2px;" class="seprator_header"></span>
			<a href="<?php echo 'javascript:void(0);';//SHIKSHA_TESTPREP_HOME_TAB; ?>" style="text-decoration:none" title="Test and Examination Preparation"><span id="testPrepOptionTag" class="<?php echo getClass("testprep"); ?>" onclick="drpdwnOpen(this, 'testPreparation');"><span class="toptxt"><?php echo $testPrperationText; ?> <small>&#9660;</small></span></span></a>
			<span style="float:left; width:2px;" class="seprator_header"></span>
            <a href="<?php echo 'javascript:void(0);';//SHIKSHA_CATEGORY_HOME_TAB; ?>" style="text-decoration:none" title="Career Options in various fields"><span id="careerOptionTag" class="<?php echo getClass("categoryHeader"); ?>" onclick="drpdwnOpen(this, 'careerOption')"><span class="toptxt">Career Options <small>&#9660;</small></span></span></a>
        <!--
			<span style="float:left; width:2px;" class="seprator_header"></span>
			<a href="javascript:void(0);" style="text-decoration:none" title="Institutes Options in various fields"><span class="<?php echo getClass("institute"); ?>" onClick="MM_showHideLayers('careerOptions','','show');drpdwnOpen(this)" id="institute"><span class="toptxt">Browse Institutes <small>&#9660;</small><img src="/public/images/mainNavigationArrowWht1.gif" border="0" align="absmiddle" style="height:14px; display:none" /></span></span></a>-->
		</div>
		<div class="clear_R"></div>
	</div>
</div>
<?php if($product != 'all') {?>
<div style="line-height:5px">&nbsp;</div>
<?php } ?>
<!--End_Navigation-->


<?php } ?>

<?php
	$this->load->view('common/disablePageLayer.php');
	$this->load->view('common/overlay.php');
	//$this->load->view('common/categorySearchOverlay.php');
?>
<div class="lineSpace_5">&nbsp;</div>
<div id="dataLoaderPanel" style="position:absolute;display:none">
  	<img src="/public/images/loader.gif"/>
</div>
