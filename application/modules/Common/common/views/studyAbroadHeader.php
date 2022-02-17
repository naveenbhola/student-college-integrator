<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="https://www.facebook.com/2008/fbml" lang="en">
<head>
<title><?php echo $title?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="dns-prefetch" href="//<?php echo JSURL; ?>">
<link rel="dns-prefetch" href="//<?php echo CSSURL; ?>">
<link rel="dns-prefetch" href="//<?php echo IMGURL; ?>">
<link rel="dns-prefetch" href="<?php echo MEDIAHOSTURL; ?>">
<link rel="preconnect" href="//<?php echo JSURL; ?>" crossorigin>
<link rel="preconnect" href="//<?php echo CSSURL; ?>" crossorigin>
<link rel="preconnect" href="<?php echo MEDIAHOSTURL; ?>" crossorigin>
<meta name="verify-v1" content="4ijm0YHCDh8EJGQiN9HxXsBccQg1cbkBQi6bCRo/xcQ=" />
<META NAME="Description" CONTENT="<?php echo isset($metaDescription)?$metaDescription:''; ?>"/>
	<?php if($metaKeywords != "") {
	?>
	<META NAME="Keywords" CONTENT="<?php echo $metaKeywords; ?>"/>
	<?php } ?>
<meta name="copyright" content="<?php echo date('Y') ;?> Shiksha.com" />
<meta http-equiv="content-language" content="en" />

<meta name="author" content="www.Shiksha.com" />
<?php
//_p($beaconTrackData['pageIdentifier']);die;
    $robotContent = ($robotsMetaTag=='')?"ALL":$robotsMetaTag;

    if(is_null($loggedInUserData)){
        $userCriteria = Modules::run('commonModule/User/getUserData');
        GLOBAL $validateuser, $loggedInUserData, $checkIfLDBUser;
        $validateuser = $userCriteria['validateuser'];
        $loggedInUserData = $userCriteria['loggedInUserData'];
        $checkIfLDBUser = $userCriteria['checkIfLDBUser'];
        if(isset($validateuser[0]['avtarurl'])){
			$loggedInUserData['avtarurl'] = $validateuser[0]['avtarurl'];
		}
    }
?>
<meta name="robots" content="<?=$robotContent?>" />
<meta name="viewport" content="width=1024" />

<?php if(isset($articleImage) && $articleImage!=''){ ?>
<meta property="og:title" content="<?php echo $title?>" />
<meta property="og:type" content="article" />
<meta property="og:image" content="<?=$articleImage?>" />
<meta property="og:site_name" content="https://www.shiksha.com/" />
<meta property="fb:app_id" content="<?php echo FACEBOOK_API_ID; ?>" />
<?php } ?>


<?php if(isset($canonicalURL) && $canonicalURL!=''){ ?>
<link rel="canonical" href="<?php echo $canonicalURL; ?>" />
<?php } ?>
<?php if(isset($previousURL) && $previousURL!=''){ ?>
<link rel="prev" href="<?php echo $previousURL; ?>" />
<?php } ?>
<?php if(isset($nextURL) && $nextURL!=''){ ?>
<link rel="next" href="<?php echo $nextURL; ?>" />
<?php } ?>

<?php
switch($pageIdentifier)
{
	case '404Page' :
		$trackingPageKeyId = 648;
		break;

	case 'coursePage' :
		$trackingPageKeyId = 476;
		break;

	case 'countryHomePage' :
		$trackingPageKeyId = 466;
		break;

	case 'universityPage' :
		$trackingPageKeyId = 467;
		break;

	case 'guidePage' :
		$trackingPageKeyId = 468;
		break;

	case 'homePage' :
		$trackingPageKeyId = 348;
		break;

	case 'categoryPage' :
		$trackingPageKeyId = 469;
		break;

	case 'articlePage' :
		$trackingPageKeyId = 470;
		break;

	case 'savedCoursesPage' :
		$trackingPageKeyId = 471;
		break;

	case 'courseRankingPage' :
		$trackingPageKeyId = 472;
		break;

	case 'universityRankingPage' :
		$trackingPageKeyId = 473;
		break;

	case 'examPage' :
		$trackingPageKeyId = 474;
		break;

	case 'searchPage' :
		$trackingPageKeyId = 475;
		break;

	case 'departmentPage' :
		$trackingPageKeyId = 477;
		break;

	case 'consultantPage' :
		$trackingPageKeyId = 478;
		break;

	case 'stagePage' :
		$trackingPageKeyId = 649;
		break;

	case 'countryPage' :
		$trackingPageKeyId = 479;
		break;

	case 'applyContentPage' :
		$trackingPageKeyId = 480;
		break;

	case 'compareCoursesPage' :
		$trackingPageKeyId = 554;
		break;

	case 'examContentPage':
		$trackingPageKeyId = 698;
		break;

	case 'applyHomePage':
		$trackingPageKeyId = 903;
		break;

	case 'counselorPage':
		$trackingPageKeyId = 917;
		break;

	case 'scholarshipDetailPage':
		$trackingPageKeyId = 1264;
                break;
        case 'scholarshipCategoryPage':
                $trackingPageKeyId = 1273;
                break;
	default:
		// case of signup page (if this is the case of response also, there will be tracking id available)
		if(is_null($trackingPageKeyId)){
			$trackingPageKeyId = 0;
		}
		break;
}
?>
<link rel="icon" href="<?php echo IMGURL_SECURE?>/public/images/faviconSA_v2.png" type="image/x-icon" />
<link rel="shortcut icon" href="<?php echo IMGURL_SECURE?>/public/images/faviconSA_v2.png" type="image/x-icon" />
<link rel="publisher" href="https://plus.google.com/+shiksha"/>
<?php

global $useSingleSignUpForm;
$useSingleSignUpForm = Modules::run('common/ABTesting/executeABTesting',NEW_SINGLESIGNUPFORM_EXPOSURE_PERCENTAGE,ABROAD_SIGNLESIGNUPFORM_ABTESTNAME);
echo '<link rel="stylesheet" type="text/css" href="//'.CSSURL.'/public/responsiveAssets/css/'.getCSSWithVersion("saGNB","responsiveAssets").'" media="all">';
if(isset($css) && is_array($css)) {
    foreach($css as $cssFile) {
?>
        <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion($cssFile); ?>" type="text/css" rel="stylesheet" />
<?php
        }
    }?>
    <link href="//<?php echo CSSURL; ?>/public/css/jquery-ui.min.css" type="text/css" rel="stylesheet" />
<?php
    $this->load->view('common/TrackingCodes/JSErrorTrackingCode');
	echo getHeadTrackJs();
	echo includeJSFiles('sa-common-shiksha-com','nationalDesktop',array('crossorigin'));
?>
<script>
    var isStudyAbroadPage = 1;
    var isSAGlobalNavSticky = <?php echo isSAGlobalNavSticky($beaconTrackData['pageIdentifier'])?1:0; ?>;

<?php if (is_array($validateuser) && is_array($validateuser[0]) && (isset($validateuser[0]['userid']))&& !empty($validateuser[0]['userid'])): ?>
isUserLoggedIn = true;
<?php else: ?>
isUserLoggedIn = false;
<?php endif; ?>
<?php addJSVariables(); ?>
var COOKIEDOMAIN = '<?php echo COOKIEDOMAIN; ?>';
<?php $studyAbroadURL =  'https://'.$_SERVER['SERVER_NAME'];
if($studyAbroadURL == SHIKSHA_STUDYABROAD_HOME)
{ ?>
	var isStudyAbroadDomain = true;
<?php } else {?>
var isStudyAbroadDomain = false;
<?php }?>

</script>
<?php if($relPrev!=''){ ?>
<link rel="prev" href="<?php echo $relPrev; ?>" />
<?php } ?>

<?php if($relNext!=''){ ?>
<link rel="next" href="<?php echo $relNext;?>" />
<?php }
//$this->load->view('studyAbroadCommon/css/commonFirstFoldCss');
 ?>

</head>
<body>
<div style="display:none;">
<?php
if($_REQUEST['mmpbeacon'] != 1) {
    loadBeaconTracker($beaconTrackData);
}
?>
</div>
	<div id="fb-root"></div>
	<?php $this->load->view('common/googleCommon');
	echo Modules::run('studyAbroadCommon/Navigation/getMainHeader',
        array('trackingPageKeyId'=>$trackingPageKeyId, 'userData'=>$loggedInUserData));
	?>
	<div id="main-wrapper">
	<div class="menu-overlay-sa"></div>

<?php
	$this->load->view('common/studyAbroadOverlay');
	$this->load->view('common/disablePageLayer');
if(!$skipCompareCode){
?>
<script>
	var compareCookiePageTitle = '<?php echo base64_encode($compareCookiePageTitle) ?>';
	var compareOverlayTrackingKeyId = '<?php echo $compareOverlayTrackingKeyId ?>';
	var compareButtonTrackingId = '<?php echo $compareButtonTrackingId ?>';
</script>
<?php } ?>
