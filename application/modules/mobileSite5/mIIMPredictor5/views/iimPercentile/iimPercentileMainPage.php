<?php ob_start('compress'); ?>
<?php
$headerComponent = array(
	'pageName'   => $boomr_pageid,
	'noJqueryMobile' => 1,
	'm_meta_title'=>$m_meta_title,
	'm_meta_description'=>$m_meta_description,
	'm_meta_keywords'=>$m_meta_keywords,
	'canonicalURL' => $canonicalURL);

$this->load->view('/mcommon5/headerV2',$headerComponent);
?>
<style type="text/css">
<?php $this->load->view('mIIMPredictor5/CSS/iimPredictorCss'); ?>
</style>
<div id="wrapper" style="background:#e5e5da;min-height: 413px;" data-role="page" class="of-hide">
	<?php
        echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
	    echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
	?>
	<!-- Show the page Header -->    
	<header id="page-header" class="header ui-header ui-bar-inherit slidedown" data-role="banner" data-tap-toggle="false" style="height: auto;">
	    <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger=true);?>
	</header>    

    <div data-role="content" id="pageMainContainerId">
    	<?php 
        //	$this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    	?>
		<div data-enhance="false">
			<div class="predictor-container">
			<?php 
				if(isset($catScore)){
				    $this->load->view('mIIMPredictor5/iimPercentile/iimPercentileResultPage');
				}
				else{
					$this->load->view('mIIMPredictor5/iimPercentile/iimPercentileForm');
				}
				?>

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
		</div>
		<div data-enhance="false">
			<?php $this->load->view('/mcommon5/footerLinksV2',array( 'jsFooter'=> array('iimPercentile'),'cssFooter'=>array('mcommon') )); ?>
		</div>
	</div>
	<div id="googleRemarketingDiv" style="display: none;"></div>
</div>
<div id="popupBasicBack" data-enhance='false'></div>
<script>
    var lazydBRecolayerCSS = '//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion('tuple','nationalMobile'); ?>';
    var GA_currentPage = 'IIM_PERCENTILE';
    var groupId = '<?php echo $eResponseData['groupId'];?>';
    var examName = 'CAT';
</script>
<?php $this->load->view('/mcommon5/footerV2'); ?>
<?php ob_end_flush(); ?>

