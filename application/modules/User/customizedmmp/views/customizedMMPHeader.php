<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("mmp"); ?>" type="text/css" rel="stylesheet" />

<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("header"); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("cityList"); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("common"); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("user"); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("tooltip"); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("multipleMarketingPage"); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("trackingCode"); ?>"></script>

<script>
	var urlforveri = 'https://<?php echo SHIKSHACLIENTIP;?>';
	var home_shiksha_url = '<?php echo SHIKSHA_HOME;?>';
	
	<?php if (is_array($validateuser) && is_array($validateuser[0]) && (isset($validateuser[0]['userid']))&& !empty($validateuser[0]['userid'])): ?>
		isUserLoggedIn = true;
	<?php else: ?>
		isUserLoggedIn = false;
	<?php endif; ?>
	
    <?php addJSVariables(); ?>
	var COOKIEDOMAIN = '<?php echo COOKIEDOMAIN; ?>';
	var messageObj = null;
	var unified_form_overlay1_cancel_clicked = false;
	var unified_form_overlay2_cancel_clicked = false;
	var unified_form_overlay3_cancel_clicked = false;
	var arr_unified = new Array();
	var page_identifier_unified = '';
	var listingdetailpage_unified_thankslayer_identifier = '';
	var unified_widget_identifier = '';
	var customizedMMPController = '/multipleMarketingPage/Marketing/';
	var currentPageName = 'MARKETING_PAGE';
	
	overlayViewsArray.push(new Array('marketing/marketingSignInOverlay','marketingSignInOverlayId'));
	overlayViewsArray.push(new Array('network/commonOverlay','addRequestOverlay'));
	overlayViewsArray.push(new Array('user/registerConfirmation','ConfirmRegistration'));
	overlayViewsArray.push(new Array('common/changeOverlay','sendveriOverlay'));
	
	
	function customRemoveTipsForUGDetails(){
		var js = document.getElementById('ug_detials_courses_marks');
        var js1 = document.getElementById('com_year_month');
		var js2 = document.getElementById('com_year_year');
		if(js != undefined){
			js.setAttribute('tip', '');
		}
		if(js1 != undefined){
			js1.setAttribute('tip', '');
		}
		if(js2 != undefined){
			js2.setAttribute('tip', '');
		}
	}

</script>
	<!-- disablePageLayer view starts -->
	<?php $this->load->view('common/disablePageLayer.php'); ?>
	<!-- disablePageLayer view ends -->
	<!-- overlay view starts -->
	<?php $this->load->view('common/overlay.php'); ?>
	<!-- overlay view ends -->
	<!-- categorySearchOverlay view starts -->
	<?php //$this->load->view('common/categorySearchOverlay.php'); ?>
	<!-- categorySearchOverlay view ends -->
<?php
	//Added to check the Blacklisted words in display name
	$newA = file_get_contents("public/blacklisted.txt");
?>
<script>
	var blacklistWords = new Array(<?php echo $newA;?>);
</script>
