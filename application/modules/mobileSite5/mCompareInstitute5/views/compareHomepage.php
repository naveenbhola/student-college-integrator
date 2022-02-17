<?php ob_start('compress');?>
<?php 
$title = (isset($seoDetails['title']))?$seoDetails['title']:'College Comparison Tool, compare colleges, universities and institutes';
$description = (isset($seoDetails['description']))?$seoDetails['description']:'Compare Colleges, Universities, and Institutes on the basis of course, fees, placements, alumni ratings, faculty, infrastructure, mode of study, and various other details.';
$canonical = (isset($seoDetails['canonical']))?$seoDetails['canonical']:SHIKSHA_HOME."/resources/college-comparison";

$headerComponents = array(
        'js'=>array('AutoSuggestor'),
        'm_canonical_url'      => $canonical,
        'm_meta_title'             => $title,
        'm_meta_description'   => $description,
	'pageType' => 'comparePage',
	'searchPage' => 'true',
	'pageName' => 'comparePage'
	);

?>
<?php $this->load->view('/mcommon5/headerV2',$headerComponents); ?>
<style type="text/css">
    <?php $this->load->view('/css/comparePageCSS'); ?>
</style>
<script>
STUDY_ABROAD_TRACKING_KEYWORD_PREFIX = "";
var reviewLayerLoadMoreStart = 0;
var compareSlide = 1;
var compareHomePageKeyId = '<?php echo $compareHomePageKeyId;?>'; 
var $categorypage = {};
$categorypage.LDBCourseId = 0;
$categorypage.key = "comparePage";
$categorypage.currentUrl = "https://<?=$_SERVER[HTTP_HOST]?><?=$_SERVER[REQUEST_URI]?>";

var manageFieldCallMade = false;
var makeViewedResponse = 0;
<?php
if(isset($validateuser) && isset($validateuser[0]['userid'])){
	echo "userlogin=true;";
}
else{
	echo "userlogin=false;";
}
?>
</script>
<?php
	//$this->load->view('autoSuggestorInstitute');
	
?>
<?php
    //Check if user came directly on this page. If the referrer is not shiksha, we have to display the Hamburger menu
    $displayHamburger = false;
    if(!$_SERVER['HTTP_REFERER']){  //If no referer is defined, show Hamburger menu
            $displayHamburger = true;
    }
    else if( strpos($_SERVER['HTTP_REFERER'],'shiksha') === false){ //If referer is not from Shiksha, show Hamburger menu
            $displayHamburger = true;
    }
    else if(strpos($_SERVER['HTTP_REFERER'],'compare-colleges') === false){
            setcookie('back-button-link-on-compare-page', $_SERVER['HTTP_REFERER'],time()+3600,'/',COOKIEDOMAIN);
    }
?>
<div id="wrapper" data-role="page" class="of-hide" style="min-height: 413px;">
	<?php
	if($displayHamburger){
		echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
	}
	echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
	?>
	<?php   $this->load->view('comparePageHeader',array('displayHamburger'=>$displayHamburger,'isBackBtnCookei'=>$_COOKIE['back-button-link-on-compare-page'])); ?>
	<div data-role="content" id="comparePageMainContainer">
        <?php 
            $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
        ?>
		<!--subheader-->
	       <?php $this->load->view('comparePageSubHeader');?>
		<!--end-subheader-->
                <div data-enhance="false">
                        <?php
                        if (getTempUserData('confirmation_message')){?>
                        <section class="top-msg-row"  id="successMsgSection">
                                <div class="thnx-msg">
                                    <i class="icon-tick"></i>
                                        <p>
                                        <?php echo getTempUserData('confirmation_message'); ?>
                                        </p>
                                </div>
                                <div style="clear:both"></div>
                        </section>
                        <?php } ?>
                        <?php
                           deleteTempUserData('confirmation_message');
                           deleteTempUserData('confirmation_message_ins_page');
                           deleteTempUserData('collegepredictor_email_link');
                        ?>
                </div>
		
                <div data-enhance="false">
            <?php   $this->load->view('compareSections'); ?>
		</div>		
                <div data-enhance="false">
                    <?php $this->load->view('/mcommon5/footerLinksV2',array('jsFooter'=>array('mComparePage'),'cssFooter'=>$cssFooter)); ?>
                </div>		
	</div>
</div>

<?php $this->load->view('shareLayer');  ?>
<?php
global $shiksha_site_current_url;
global $shiksha_site_current_refferal;
?>
<div style="display: none;">
        <!--<form method="post" action="/muser5/MobileUser/renderShortRegistration" id="emailResultsForm">-->
	<form method="post" action="/muser5/MobileUser/register" id="emailResultsForm">
                <input type="hidden" name="current_url" value="<?=url_base64_encode($shiksha_site_current_url)?>">
                <input type="hidden" name="referral_url" value="<?=url_base64_encode($shiksha_site_current_refferal)?>">
                <input type="hidden" name="from_where" value="COMPARE_PAGE">
                <input type='hidden' name="tracking_keyid" id="tracking_keyid" value='<?php echo $emailTrackingPageKeyId;?>'>
        </form>
</div>


<div id="collegeReviewViewDetails" data-enhance="false" data-role="page" class="clearfix content-wrap">
<?php //$this->load->view('collegeReviewLayer');?>
</div>

<div data-role="page" data-enhance="false" id="LayerForAskQuesCompareMobile" style="background-color:#e6e6dc !important;" class="clearfix content-wrap">
   <?php //$this->load->view('askQuestionLayer'); ?>
</div>

<?php $this->load->view('/mcommon5/footerV2');?>
<?php if(isset($validateuser) && $validateuser!='false'){
     foreach($courseIdArr as $clientCourseId){
        $pageKey = empty($userComparedData[$clientCourseId]['trackeyId']) ? $compareHomePageKeyId : $userComparedData[$clientCourseId]['trackeyId'];
        $tmpData[$clientCourseId] = array('clientCourseId'=>$clientCourseId,'actionType'=>'MOB_COMPARE_VIEWED','pageKey'=>$pageKey);
    }
}
?>
<script type="text/javascript">
//open course layer 
var data = {'subCatMap':'', 'categoryMap':'', 'layerPageId':'newHomepageToolLayer','hierarchyMap':''};
var homepageWidgets = new homepageWidgetsClass(data);
homepageWidgets.isComparePage = true;
var createResponseData = '<?php echo json_encode($tmpData);?>';
</script>
<?php ob_end_flush(); ?>



