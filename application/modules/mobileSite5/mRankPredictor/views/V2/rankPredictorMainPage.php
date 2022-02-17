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
<?php $this->load->view('mcollegepredictor5/V2/collegePredictorCss'); ?>
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

	<div data-role="content">
		<?php 
        	$this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    	?>
		<div data-enhance="false">
			<?php 
			if($isInputValuesExist){
			    $this->load->view('mRankPredictor/V2/rankPredictorResultPage',$examName);
			}
			else{
				$this->load->view('mRankPredictor/V2/rankPredictorForm',$examName);
			}
			?>
		</div>
		<div data-enhance="false">
			<?php $this->load->view('/mcommon5/footerLinksV2',array( 'jsFooter'=> array('collegePredictorNM'),'cssFooter'=>array('mcommon') )); ?>
		</div>
	</div>
	<div id="googleRemarketingDiv" style="display: none;"></div>
</div>
<div id="popupBasicBack" data-enhance='false'></div>
<script>
	var examName = '<?php echo $examName;?>';
	var cookieName = '<?php echo $cookieName;?>';
	var cookieNamePer1 = '<?php echo $cookieNamePer1;?>';
	var cookieNamePer2 = '<?php echo $cookieNamePer2;?>';
	var minRange   = parseInt('<?php echo $rpConfig[$examName]['inputField']['score']['minRange'];?>');
	var maxRange   = parseInt('<?php echo $rpConfig[$examName]['inputField']['score']['maxRange'];?>');
	var GA_currentPage = 'RankPredictorMobile';
	var isInputValuesExist = '<?php echo $isInputValuesExist;?>';
	var groupId = '<?php echo $eResponseData['groupId'];?>';
	
	$(document).ready(function(){
		$(document).on('click','#modifySearchButton',function(){
		    setCookie('collegepredictor_search_'+"<?php echo $collegePredictorExamName; ?>",'',1);
		    setCookie('collegepredictor_filterTypeValueData_desktop_'+"<?php echo $collegePredictorExamName; ?>",'',1);
		    window.location.href = "<?php echo $collegePredictorUrl; ?>";
		});
	});

	//load addThis script on scroll
	var addThisLoadCount = 1;
	$(window).scroll(function() {
        var screenHeight = (typeof screen != 'undefined' && typeof screen.height != 'undefined' && screen.height>0) ? (screen.height)/2 : $('#page-header').outerHeight();
	      if(addThisLoadCount == 1 && ($(window).scrollTop() > screenHeight)){
            var addthisScript = document.createElement('script');
            addthisScript.setAttribute('src', '//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5c1b59506e2754b7');
            document.body.appendChild(addthisScript);
            addThisLoadCount = 2;
        }
	});
	$(window).load(function(){
        if(isInputValuesExist){
            setTimeout(function(){window.scrollTo(0,0);},500);
        }
    });
</script>
<?php $this->load->view('/mcommon5/footerV2'); ?>
<?php ob_end_flush(); ?>
