<?php ob_start('compress'); ?>
<?php
//Since, this is a single page application, Cookies were not getting saved when used pressed Back from any page.
//To avoid this, we are making this page as no-cache
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.
?>
<?php $this->load->view('/mcommon5/header');

?>

<div id="wrapper" data-role="page" class="of-hide" style="min-height: 413px;padding-top: 40px;">
    <?php echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
	  echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
    ?>
    
        <?php   $this->load->view('collegePredictorHeader'); ?>

        <div data-role="content">
        	<?php 
      	  $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    	?>
	    <!----subheader--->
	       <?php $this->load->view('collegePredictorSubHeader');?>
	    <!--end-subheader-->
		
		<?php
			$this->load->view('collegePredictorWelcome');
			$this->load->view('collegePredictorSubHeading');
		?>
		
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

		<div>
			<?php
			$this->load->view('collegePredictorTabs');
			$this->load->view('collegePredictorSearch');
			?>
		</div>

		<div id="searchResults"><?=$resultsList?></div>

		<!-- Loading Div -->
		<div id="loading" style="text-align:center;margin-top:10px;display:none;"><img id="loadingImage" border=0 alt="" ></div>
	
		<!-- Discliamer Div -->
		<div id="disclaimerDiv" style='font-size: 0.8em;color: #515151; padding:8px; border: 2px solid #DFDFDF; display: none;margin: 5px;'>
		    <div><strong>Disclaimer: </strong>Please note that the above displayed colleges and branches are only for reference purpose. Shiksha.com does not take any responsibility for the validity of the predictions and analysis shown above.</div>
		    <div style='margin-top:10px;'><?php echo $examSettingsArray['notice'];?></div>
		</div>
		
		<div data-enhance="false">
			<?php $this->load->view('/mcommon5/footerLinks'); ?>
		</div>
		
		<!-- For Email result message -->
		<a href="#popupBasic" data-position-to="window" data-inline="true" data-rel="popup" id="emailSuccessPopup" data-transition="slideup" ></a>
		<div data-role="popup" id="popupBasic" data-theme="d" style="background:#EFEFEF">
			<div style="padding: 25px; font-size:1.1em;color: #828282;" data-theme="d">
				<p>Results have been mailed <br/>to you successfully.<p>
			</div>
			<div style="padding-left: 25px; padding-bottom: 12px; font-size:1.0em; text-align: center;">
				<a style="width:70px;" data-theme="d" id="closeEmailSuccessPopup" href="javascript:void(0)" data-rel="back" data-role="button" data-icon="delete" data-iconpos="Close" ><strong>&nbsp;&nbsp;Close</strong></a>
			</div>
		</div>
		
		<!-- For Rank Predictor Static HTML -->
		<?php if($tab=="1" || $tab=="3"){ ?>
		<a href="#rankPredictorPopup" data-position-to="window" data-inline="true" data-rel="popup" id="rankPredictorPopupLink" data-transition="pop" ></a>
		<div data-role="popup" id="rankPredictorPopup" data-theme="d" style="background:#EFEFEF;width: 92%;left: 4%;right: 4%; top:10%;">
			<div style="padding: 12px; font-size:1.0em;" data-theme="d">
				<?php $this->load->view('rankPredictorHTML');?>
			</div>
			<div style="padding-left: 12px; padding-bottom: 12px;font-size:1.0em; text-align: center;">
				<a style="width:70px;" data-theme="d" href="javascript:void(0)" data-rel="back" data-role="button" data-icon="delete" data-iconpos="Close" ><strong>&nbsp;&nbsp;Close</strong></a>
			</div>
		</div>
		<?php } ?>
		
        </div>
</div>

<?php if($tab=='2'){?>
<div data-role="page" id="instituteDiv" data-enhance="false"><!-- dialog--> 
 <?php $this->load->view('instituteDiv'); ?>
</div>
<?php }else if($tab=='3'){?>
<div data-role="page" id="branchDiv" data-enhance="false"><!-- dialog--> 
 <?php $this->load->view('branchDiv'); ?>
</div>
<?php } ?>

<div data-role="page" id="examsFilterDiv" data-enhance="false"><!-- dialog--> 
</div>

<div data-role="page" id="roundFilterDiv" data-enhance="false"><!-- dialog--> 
</div>

<div data-role="page" id="branchFilterDiv" data-enhance="false"><!-- dialog--> 
</div>

<div data-role="page" id="locationFilterDiv" data-enhance="false"><!-- dialog--> 
</div>

<div data-role="page" id="collegeFilterDiv" data-enhance="false"><!-- dialog--> 
</div>
<?php 
		$examName = strtoupper($examName);
		$this->load->config('CP/CollegePredictorConfig',TRUE);
		$settingsArray =
		$this->config->item('settings','CollegePredictorConfig');
		$examSettings =$settingsArray[$examName];
?>

<?php if($examSettingsArray['showEmail'] == 'YES'):?>
<div id="emailDiv" class="email-result" style="display:none;" onClick="emailPredictorResults();"></div>
<?php endif;?>
<script type="text/javascript">
	var collegePredictorCourseCompare;
</script>
<?php
global $shiksha_site_current_url;
global $shiksha_site_current_refferal;
?>
<div id="popupBasicBack" data-enhance='false'></div>
<?php $this->load->view('/mcommon5/footer');?>
<?php ob_end_flush(); ?>
<?php $this->load->view('common/googleRemarketing');?>
<script type="text/javascript">
$(window).load(function () {   
    collegePredictorCourseCompare = new collegePredictorCourseCompareClass();
    collegePredictorCourseCompare.refreshCompareCollegediv();
    myCompareObj.setRemoveAllCallBack('collegePredictorCourseCompare.removeItem');
});
</script>