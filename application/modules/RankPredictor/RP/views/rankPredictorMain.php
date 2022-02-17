<?php 
 header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.

 header('Pragma: no-cache'); // HTTP 1.0.

 header('Expires: 0'); // Proxies.


$tempJsArray = array('myShiksha','user','onlinetooltip');
$headerComponents = array(
		'css'   =>      array('college-predictor'),
		'js' => array('common','rankPredictor', 'userRegistration'),
		'jsFooter'=>    $tempJsArray,
		'title' =>      $m_meta_title,
		'metaDescription' => $m_meta_description,
		'canonicalURL' =>$canonicalURL,
		'product'       =>'rankPredictor',
		'showBottomMargin' => false,
		'displayname'=>(isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),

);


$headerComponents['showGutterBanner'] = 1;
switch($examName){
	case 'jee-main': $bannerName = 'JEE';break;
        case 'default': $bannerName = 'JEE';break;
}
$headerComponents['bannerPropertiesGutter'] = array('pageId'=> $bannerName.'_RANK_PREDICTOR', 'pageZone'=>'RIGHT_GUTTER');

$headerComponents['shikshaCriteria'] = array();
$this->load->view('common/header', $headerComponents);
?>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
	$j = jQuery.noConflict();
</script>
<div id="top-nav" style="visibility:hidden;height:0px"></div>
<?php //$this->load->view('messageBoard/headerPanelForAnA',array('collegePredictor' => true));?>

<?php $this->load->view('rankPredictorForm');?>

<?php
echo modules::run('comparePage/comparePage/generateCollegeCompareTool');
?>

<?php $this->load->view('common/footer'); ?>

<script>
var regFormId  = '<?php echo $regFormId;?>';
var examName   = '<?php echo $examName;?>';
var cookieName = '<?php echo $cookieName;?>';
var rpFeedBackCookeiName = '<?php echo $rpFeedBackCookieName;?>';
// these variables are using in js validation for score only
var minRange   = '<?php echo $rpConfig[$examName]['inputField']['score']['minRange'];?>';
var maxRange   = '<?php echo $rpConfig[$examName]['inputField']['score']['maxRange'];?>';
var scoreLabel = '<?php echo $rpConfig[$examName]['inputField']['score']['label'];?>';
var groupId = '<?php echo $eResponseData['groupId'];?>';

$j(function() {
		if(!isUserLoggedIn && typeof(isUserLoggedIn) != 'undefined'){
		  prepareRPForm();
		}else if(isUserLoggedIn && typeof(isUserLoggedIn) != 'undefined' && getCookie(cookieName) !='') {
		  resetRankPredictor(regFormId);
		}
                $j(window).scroll(function() {
					   if(( ($j(this).scrollTop()+400) >= $j('#predictor-wrap').offset().top+($j(document).height()/4))
                           && !(/MSIE ((5\\.5)|6)/.test(navigator.userAgent) && navigator.platform == "Win32")) {
                                if($j(window).width() < 1000){
                                        $j('#toTop').css('left',($j('#predictor-wrap').offset().left+818) + "px");
                                }else{
                                        $j('#toTop').css('left',($j('#predictor-wrap').offset().left+925) + "px");
                                }
                                $j('#toTop').fadeIn();
                        } else {
                                $j('#toTop').fadeOut();
                        }
                });
         
                $j('#toTop').click(function() {
                        $j('body,html').animate({scrollTop:0},500);
                });     
        });
publishBanners();
$j(document).ready(function () {
    collegePredictorCourseCompare = new collegePredictorCourseCompareClass();
    collegePredictorCourseCompare.refreshCompareCollegediv();

    myCompareObj.setRemoveAllCallBack('collegePredictorCourseCompare.compareCallbackOnCourseRemove');
});
</script>
<div id="toTop">&#9650; Back to Top</div>
<div id="opacityLayer"></div>

<div id="googleRemarketingDiv" style="display: none;"></div>

<?php if(isset($validateuser[0]['userid']) && $validateuser[0]['userid']>0 && isset($_COOKIE[$cookieName]) && $_COOKIE[$cookieName] !=''){?>
<!-- Google Code for registration Conversion Page -->
<!--
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1053765138;
var google_conversion_language = "en_GB";
var google_conversion_format = "1";
var google_conversion_color = "ffffff";
var google_conversion_label = "O3WQCOaXRRCS3Lz2Aw";
var google_conversion_value = 1.00;
var google_conversion_currency = "INR";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1053765138/?value=1.00&amp;currency_code=INR&amp;label=O3WQCOaXRRCS3Lz2Aw&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
-->
<?php }?>
