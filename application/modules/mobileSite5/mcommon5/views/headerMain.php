<?php
	$notificationArray     = Modules::run('mobile_myShortlist5/MyShortlistMobile/fetchUserNotificationsData');
	$this->load->config('mAnA5/MobileSiteTracking');
	$notificationData      = $notificationArray['resultdata'];
	$isAllNotificationSeen = $notificationArray['isAllNotificationSeen'];
	$totalCmp[] = '';
	$sum = 0;
	$cookieCmpName = 'mob-compare-global-data';
	if($_COOKIE[$cookieCmpName]){
		$totalCmp = explode('|',$_COOKIE[$cookieCmpName]);
		$totalCmp = count($totalCmp);
	}else{
		$totalCmp = 0;
	}
	$sum = ($totalCmp + $anaInAppNotificationCountForMobileSite);
	$mobile_details = json_decode($_COOKIE['ci_mobile_capbilities'],true);
	$screenWidth = $mobile_details['resolution_width'];
	$screenHeight = $mobile_details['resolution_height'];


	/*$countryName = strtoupper($_SERVER['GEOIP_COUNTRY_NAME']);
	if ($countryName == 'INDIA'){
    	setcookie("gdpr","1",time() + (86400 * 90));
	}*/
?>
<div class="mys-notifyOvrly" style="display: none;" onclick="toggleNotificationLayer();"></div>
<?php 
$deviceType = 'mobile';
global $isWebViewCall;
if(empty($_COOKIE['gdpr']) && !$isWebViewCall)
{
	$this->load->view('mcommon5/cookie_banner');
}
if($_REQUEST['test'] != '1234' && !$isWebViewCall){
	echo Modules::run('mcommon5/MobileSiteHamburger/getAppBanner', $deviceType, $pageName,$isShowIcpBanner, $predBannerStream);
}	

if($noJqueryMobile)
{
	$mypanelHref        = ' href="javascript:void(0);" data-page-id="mypanel" ';
	$myrightpanelHref   = ' href="javascript:void(0);" data-page-id="myrightpanel" ';
	$questionPostingHref= ' href="javascript:void(0);" data-page-id="questionPostingLayerOneDiv" ';
	$socialShareIcon    = ' href="javascript:void(0);" data-page-id="socialShare" ';
}else{
	$mypanelHref        = ' href="#mypanel" ';
	$myrightpanelHref	= ' href="#myrightpanel" ';
	$questionPostingHref= ' href="#questionPostingLayerOneDiv" ';
	$socialShareIcon    = ' href="#socialShare" ';
}
$collegeSearchHref = SHIKSHA_HOME . '/searchLayer';
?>

<?php 
if(!$isWebViewCall){ ?>

<div data-enhance="false" class="header slideShow">
    <!--
    If the user has come from within Shiksha, we will display this Back button.
    If the user has landed directly on this page,
    we will show Hamburger menu on this page
    -->
    <?php if($displayHamburger && $isBackBtnCookei ==''){?>
		<a class="hem-menu _hmPanel_" <?=$mypanelHref;?>>
			<span class="icon-bar"></span>
		</a> 
	<?php } else if($isBackBtnCookei !='' && $pageName == 'ComparePage'){ ?>
		<a class="back-col" data-param="comparePage" href="javascript:void(0);"> <i class="back-ico"></i> </a>
	<?php }else if($isBackBtnCookei !='' && $pageName == 'ShortlistPage'){ ?>
	    <a class="back-col" data-param="shortlistPage" href="javascript:void(0);"> <i class="back-ico"></i> </a>
	<?php }else if($pageName == 'Mobile5ExamPage' || $pageName == 'ranking_page' || $pageName == 'mobileArticlePage' || $pageName == 'mobilesite_AnA_QDP' || $pageName == 'mobilesite_AnA_DDP' || $pageName== 'mobilesite_LDP'){?>
		<a class="back-col" href="javascript:void(0);" onclick="window.history.go(-1);return false;" data-rel="back"> <i class="back-ico"></i> </a>
	<?php }else{ ?>
	    <a class="back-col" href="javascript:void(0);" data-rel="back"> <i class="back-ico"></i> </a>
	<?php } ?>
	<a class="logo" id="_hlogo"><span class="msprite" style="cursor: pointer;<?php echo empty($notificationData) ? '' : 'margin-left:32px;'?>">Shiksha.com</span></a>

	<?php if(!in_array($pageName,array('termCondition'))){?>
	<a  class="short-lst q-srch" id="shareIcon" data-position="header" data-transition="fade" data-inline="true" data-rel="dialog" data-enhance="false" <?php echo $socialShareIcon ?> >
		<span class="social-share-icon">&nbsp;</span>
	</a>
	<?php }?>

	<?php if(MOBILE_SEARCH_V2_INTEGRATION_FLAG == 1 && !in_array($pageName,array('communityGuideline','userPointSystem'))) { ?>
		<a class="short-lst q-srch" id="_hsrchLayerReact" data-transition="fade" data-inline="true" data-rel="dialog" data-enhance="false" href="<?php echo $collegeSearchHref ?>" >
			<span class="search">&nbsp;</span>
		</a>
	<?php } ?>

	<?php if(!in_array($pageName,array('communityGuideline','userPointSystem', 'termCondition'))) { ?>
	<a class="q-ask" id="_q-ask" <?=$questionPostingHref;?> data-param="<?php echo M_ASK_QUES_HEADER?>" data-inline="true" data-rel="dialog" data-transition="fade">
		<span></span>
	</a>
	<?php }?>
	<?php if($notificationArray['resultdata']) { ?>
		<a href="javascript:void(0);" onclick="toggleNotificationLayer();" class="short-lst alrmActv"><i class="msprite notification-bell-icon"></i></a>
	<?php } ?>
	<a class="right-menu" <?=$myrightpanelHref;?>><span id="bellIcon" class="msprite"></span><div class="notification" id="notification" <?php if($sum == 0){?> style="display: none;" <?php }?> ><?php echo $sum;?></div></a>
	<p style="clear:both;height:0px;width:0px;padding:0px;margin:0px; display:block;"></p>
	<?php if($notificationArray['resultdata']) { ?>
		<div class="mys-notify" id="mysNotify" style="display:none;">
    		<ul>
    			<?php foreach ($notificationData as $value) {
    				if($value['is_valid'] == 1){
    					$url = "";
    					if($value['course_id']) {
    						$url = SHIKSHA_HOME."/mobile_myShortlist5/MyShortlistMobile/showCourseDetailsTabs/".$value['course_id'].(in_array($value['link_type'], array('generic','placement'))? '' :"/".$value['link_type']);
    					} ?>
      					<li <?php echo (empty($value['is_seen']) || $value['is_seen'] == NULL)? 'class="gryli"' : ''?>><a href="javascript:void(0);" onclick="openNotification(this,<?php echo $value['id']?>, '<?php echo $url?>');"><?php echo ($value['body']); ?>
        				<span><i class="msprite notfy-watch"></i><?=$value['timeText'] ?></span></a></li>
   					<?php }
    			} ?>
    		</ul>
		</div>
	<?php } ?>
</div>
<?php } ?>

<?php if($isWebViewCall){ ?>
<script type="text/javascript">
document.getElementById('page-header').style.display = "none";
document.getElementById('wrapper').style.padding = "0";
isWebViewCall = 1;
</script>
<?php }
else{ ?>
<script type="text/javascript">
isWebViewCall = 0;
</script>
<?php } ?>

<script type="text/javascript">
var boomr_pageid = '<?php echo strtoupper($boomr_pageid);?>';
var SHIKSHA_HOME = '<?php echo SHIKSHA_HOME;?>';
var HAMBURGER_UPDATE_KEY = '<?php echo base64_encode('HC_'.MOB_HAMBURGER_UPDATE_CONTEXT);?>';
</script>
