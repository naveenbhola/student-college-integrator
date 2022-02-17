<?php ob_start('compress'); ?>
<?php
//Since, this is a single page application, Cookies were not getting saved when used pressed Back from any page.
//To avoid this, we are making this page as no-cache
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

$yearValue = '';
$curDate  = date('j');
$curMonth = date('n');
if($curMonth >= 4 && $curDate >= 1){
  $yearValue = date('Y').'-'.(date('y')+1);
}else{
  $yearValue = (date('Y')-1).'-'.date('y');
}

$headerComponent = array(
                         'pageName'   => $boomr_pageid,
                          'm_meta_title'=> 'IIM & Non IIM Call Predictor - Predict Calls, Check CAT '.$yearValue.' Cut Offs, Admission & Shortlist Criteria',
                          'm_meta_description' => "Predict calls from best IIMs and non IIMs for your CAT score, whether 60 percentile, 70 percentile, 80 percentile or 90 percentile. Check Reviews, Cut Offs, Admission & Shortlist Criteria of all Colleges",
                          'm_canonical_url'=> SHIKSHA_HOME.'/mba/resources/iim-call-predictor'
                         );
//$this->load->view('mcommon5/header',$headerComponent);
$this->load->view('mcommon5/headerV2',$headerComponent);

?>
<style type="text/css">
	<?php $this->load->view('mIIMPredictor5/CSS/iimPredictorCss'); ?>
</style>
<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>

<div id="wrapper" data-role="page" class="of-hide" style="min-height: 413px;padding-top: 40px;">
    <?php echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
	  echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
    ?>
    	<header id="page-header" class="header ui-header ui-bar-inherit slidedown ui-header-fixed" data-role="header" data-tap-toggle="false" style="height:auto;" role="banner">
       		<div id="page-header-container" style=""><?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger=true);?></div>
	       <div id="fixed-card" style="display:none">
	        	<?php $this->load->view('mIIMPredictor5/iimPredictorOutputTabs');?>
	        </div>
    	</header>  
    <div data-role="content" id="pageMainContainerId">
    	<?php 
	        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
	    ?>
    	<div data-enhance="false" >
    	<div class="predictor-container">
    	<?php //$this->load->view('mIIMPredictor5/iimpredictorHeader'); ?>
    		<div class="loader-col initial_hide">
		       <div class="three-quarters-loader">Loading…</div>
		  	</div>

			<?php 
			$this->load->view('mIIMPredictor5/iimpredictorWelcome'); 
			$this->load->view('mIIMPredictor5/iimpredictorInputStep1'); 
			$this->load->view('mIIMPredictor5/iimpredictorInputStep2'); 
			$this->load->view('mIIMPredictor5/iimpredictorInputStep3'); 
			$this->load->view('mIIMPredictor5/catScoreStep'); 
			$this->load->view('mIIMPredictor5/interimOutput');
			$this->load->view('mIIMPredictor5/iimpredictorouput1'); ?>
		<div class="report-msg" style="display: none;"><p class="toastMsg" id="toastMsg"></p></div>
			<?php $this->load->view('/mcommon5/footerLinksV2',array('jsFooter'=>array('iimpredictor'), 'cssFooter'=>array('tuple','mcommon'))); ?>
		
		<!-- For Email result message -->
		<a href="#popupBasic" data-position-to="window" data-inline="true" data-rel="popup" id="emailSuccessPopup" data-transition="slideup" ></a>
		<div data-role="popup" id="popupBasic" data-theme="d" style="background:#EFEFEF;display: none">
			<div style="padding: 25px; font-size:1.1em;color: #828282;" data-theme="d">
				<p>Results have been mailed <br/>to you successfully.</p>
			</div>
			<div style="padding-left: 25px; padding-bottom: 12px; font-size:1.0em; text-align: center;">
				<a style="width:70px;" data-theme="d" id="closeEmailSuccessPopup" href="javascript:void(0)" data-rel="back" data-role="button" data-icon="delete" data-iconpos="Close" ><strong>&nbsp;&nbsp;Close</strong></a>
			</div>
		</div>
    </div>
    <?php //echo Modules::run('mcommon5/MobileSiteBottomNavBar/bottomNavBar','collegePredictorPage', '56');?>
</div>
</div>
</div>
<div id="popupBasicBack" data-enhance='false'></div>
<img id = 'beacon_img' src="/public/images/blankImg.gif" width=1 height=1 >
<?php $this->load->view('/mcommon5/footerV2');?>
<?php //$this->load->view('/mcommon5/footer', array('doNotLoadImageLazyLoad'=>'true'));?>
<script type="text/javascript">
	var pageType = 'outputScreens';
	var GA_currentPage = "ICP OUTPUT PAGE";
    var ga_user_level = "<?php echo $GA_userLevel;?>";
    var ga_commonCTA_name = "_ICP_OUTPUT_PAGE_MOB";
   	currentPageName= 'IIMPredictorOutputPage';
</script>
<?php if($this->input->get('registration') == 'new'){ ?>
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
			<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
			<noscript>
			<div style="display:inline;">
			<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1053765138/?value=1.00&amp;currency_code=INR&amp;label=O3WQCOaXRRCS3Lz2Aw&amp;guid=ON&amp;script=0"/>
			</div>
			</noscript>
			-->

			<!-- Facebook Pixel Code -->
			<!--
			<script>
			!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
			n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
			n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
			t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
			document,'script','//connect.facebook.net/en_US/fbevents.js');

			fbq('init', '639671932819149');
			fbq('track', "CompleteRegistration");
			</script>
			<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=639671932819149&ev=PageView&noscript=1" /></noscript>
			-->
			<!-- End Facebook Pixel Code -->

<?php } ?>

<?php ob_end_flush(); ?>