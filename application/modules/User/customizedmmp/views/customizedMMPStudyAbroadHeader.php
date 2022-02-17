<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("mmp"); ?>" type="text/css" rel="stylesheet" />

<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("header"); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("cityList"); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("common"); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("user"); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("tooltip"); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("trackingCode"); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("multipleMarketingPageStudyAbroad"); ?>"></script>

<script>
	var urlforveri = 'https://<?php echo SHIKSHACLIENTIP;?>';
	var home_shiksha_url = '<?php echo SHIKSHA_HOME;?>';
	var currentPageName= null;
	
	<?php if (is_array($validateuser) && is_array($validateuser[0]) && (isset($validateuser[0]['userid']))&& !empty($validateuser[0]['userid'])): ?>
		isUserLoggedIn = true;
	<?php else: ?>
		isUserLoggedIn = false;
	<?php endif; ?>
	var isLogged = '<?php echo $logged; ?>';
    <?php addJSVariables(); ?>
	var COOKIEDOMAIN = '<?php echo COOKIEDOMAIN; ?>';
	var messageObj = null;
	var customizedMMPController =  '/multipleMarketingPage/Marketing/';
	var currentPageName = 'MARKETING_PAGE';
</script>
	<!-- disablePageLayer view starts -->
	<?php $this->load->view('common/disablePageLayer.php'); ?>
	<!-- disablePageLayer view ends -->
	<!-- overlay view starts -->
	<?php $this->load->view('common/overlay.php'); ?>
	<!-- overlay view ends -->
<?php
	//Added to check the Blacklisted words in display name
	$newA = file_get_contents("public/blacklisted.txt");
?>
<script>
	var blacklistWords = new Array(<?php echo $newA;?>);
</script>
