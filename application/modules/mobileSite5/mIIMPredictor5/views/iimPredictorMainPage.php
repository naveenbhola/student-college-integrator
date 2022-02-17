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
                          'm_meta_title'=> 'IIM & Non IIM Call Predictor – Check IIM Cut offs, CAT Cut offs, Predict Calls',
                          'm_meta_description' => "Check CAT Cut offs and predict calls from IIMs and other Top MBA Colleges, whether your CAT score is 70 percentile, 80 percentile or 90 percentile. Check Fees, Placement Reviews, Admission, Shortlist Criteria and eligibility of all MBA Colleges",
                          'm_canonical_url'=> SHIKSHA_HOME.'/mba/resources/iim-call-predictor'
                         );
$this->load->view('mcommon5/headerV2',$headerComponent);

?>
<?php global $shiksha_site_current_url;global $shiksha_site_current_refferal ;?>

<!-- <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'> -->

<style type="text/css">
	<?php $this->load->view('mIIMPredictor5/CSS/iimPredictorCss'); ?>
</style>

<div id="wrapper" data-role="page" class="of-hide" style="min-height: 413px;padding-top:40px;">
    <?php $this->load->view('mIIMPredictor5/iimpredictorHeader')?> 
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
				<?php //$this->load->view('mIIMPredictor5/iimpredictorWelcome'); 
					$this->load->view('mIIMPredictor5/iimpredictorWelcome'); 
					$this->load->view('mIIMPredictor5/iimpredictorInputStep1'); 
					$this->load->view('mIIMPredictor5/iimpredictorInputStep2'); 
					$this->load->view('mIIMPredictor5/iimpredictorInputStep3'); 
					$this->load->view('mIIMPredictor5/catScoreStep'); 
					$this->load->view('mIIMPredictor5/interimOutput');
				?>
	    
			<div>
				<form id="iimPredictorRegistrationForm" action="/muser5/MobileUser/register?registrationSource=ICP&tracking_keyid=537" method="post" type="hidden">
				<?php global $managementStreamMR;
	            global $mbaBaseCourse;
	            global $fullTimeEdType;
	             ?>
					<input type='hidden' id='stream' name='stream' value="<?php echo $managementStreamMR; ?>" />
	            	<input type='hidden' id='baseCourses' name='baseCourses[]' value="<?php echo $mbaBaseCourse; ?>" />
	            	<input type='hidden' id='educationType' name='educationType[]' value="<?php echo $fullTimeEdType; ?>" />
					<input id="yearOfPassing" type="hidden" value="2015" name="yearOfPassing">
					<input type="hidden" value="100" id="CAT_EXAM" name="CAT_EXAM">
					<input type="hidden" value="Yes" id="fromICP" name="fromICP">
					<input type="hidden" value="0" id="userICPDataId" name="userICPDataId" />
				</form>
			</div>


				<?php $this->load->view('/mcommon5/footerLinksV2',array(
														  'jsFooter'=>array('iimpredictor'))); ?>
			
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
</div>
</div>
</div>
<div id="popupBasicBack" data-enhance='false'></div>
<img id = 'beacon_img' src="/public/images/blankImg.gif" width=1 height=1 >

<script type="text/javascript">
	var pageType = 'inputScreens';
	var getStringType = '<?php echo $this->input->get("type"); ?>';
	var GA_currentPage = "ICP INPUT PAGE";
    var ga_user_level = "<?php echo $GA_userLevel;?>";
    var ga_commonCTA_name = "_ICP_INPUT_PAGE_MOB";
    var initialValid = true;
    var groupId = '<?php echo $eResponseData['groupId'];?>';
    var examName = 'CAT';

    $(window).load(function() { // for start again click
    	if(typeof(getUrlParameter) != 'undefined' && getUrlParameter('modify') != 'yes'){
	    	var uri = window.location.href.toString();
			if (uri.indexOf("?") > 0) {
			    var clean_uri = uri.substring(0, uri.indexOf("?"));
			    window.history.replaceState({}, document.title, clean_uri);
			}
		}
	});
	if(isUserLoggedIn()){
		setCookie('isICPUser',1, 30);
	}else{
		setCookie('isICPUser',0, 30);
		setCookie('isZeroResult','',30);
	}
</script>
<?php $this->load->view('/mcommon5/footerV2');?>
<?php ob_end_flush(); ?>
