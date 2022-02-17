<?php ob_start('compress'); ?>
<?php
$headerComponents = array(
 'm_meta_description' => 'Apply online to top '.$onlineFormsDepartments[$department]['shortName'].' colleges of your choice at Shiksha.com. Fill online application form once & apply to multiple '.$onlineFormsDepartments[$department]['shortName'].' colleges to get admission.',
 'm_meta_title' => $onlineFormsDepartments[$department]['shortName'].' Application Forms: Apply online via Shiksha',
 'm_meta_keywords' => 'college admission, online '.$onlineFormsDepartments[$department]['shortName'].' application, Online '.$onlineFormsDepartments[$department]['shortName'].' application form, online application, apply online, '.$onlineFormsDepartments[$department]['shortName'].' admission, online admission form, list of colleges, engineering admission, college admission form, online admission process, Online college admission form, online application forms, Online college admission, list of institutes, online admission forms, list of online colleges, application form online',
 'm_canonical_url' => $current_page_url
);
$this->load->view('mcommon5/header',$headerComponents); ?>


<?php
deleteTempUserData('onlineForm_StartApplication');
/*
if (getTempUserData('homepage_message')){
?>
	<div style="border:1px solid #fff590; background:#feffcd; padding:10px; font:bold 13px Arial, Helvetica, sans-serif; margin:8px;">
	 <?php echo getTempUserData('homepage_message'); ?>
	</div> 
<?php
}
*/
?>

<div id="wrapper" data-role="page" class="of-hide">

   <?php
	 echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
	 echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
   ?>

   <header id="page-header"  class="header ui-header-fixed" data-role="header" data-tap-toggle="false" data-position="fixed">
     <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger=true);?>
    </header>

    
    <div data-role="content">
    	<?php 
        	$this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    	?>
	<section class="form-expiring-sec" id="referralMessage">
	  <!-- <p class="font-15">Apply online to these <?=$onlineFormsDepartments[$department]['shortName']?> colleges</p> -->
	  <h1 class="font-15" style="font-weight: normal">Apply online to <?=$onlineFormsDepartments[$department]['shortName']?> colleges</h1>
	  <p><label>Forms Expiring : </label>
	      <a href="javascript: void(0);" data-type="thisWeek" id="thisWeekAnchor">This Week</a>
	      <span id="thisWeekSpan" style="display:none;">This Week</span>
	      <span class="expire-sprtr">|</span>
	      <a href="javascript: void(0);" data-type="nextWeek" id="nextWeekAnchor">Next Week</a>
	      <span id="nextWeekSpan" style="display:none;">Next Week</span>
	      <span class="expire-sprtr">|</span>
	      <a href="javascript: void(0);" data-type="all" id="allAnchor">All</a>
	      <span id="allSpan" style="display:none;">All</span>
	  </p>
	</section>
	
	<div id="instituteList">
	 <?php $this->load->view('showInstituteList',$headerComponents); ?>
	</div>

	<!-- Footer Links Section -->
	<?php $this->load->view('mcommon5/footerLinks'); ?> 



    </div>
    
</div>


<?php
global $shiksha_site_current_url;
global $shiksha_site_current_refferal;
?>
<div style="display: none;">
        <form method="post" action="/muser5/MobileUser/register" id="emailResultsForm">
                <input type="hidden" name="current_url" value="<?=url_base64_encode($shiksha_site_current_url)?>">
                <input type="hidden" name="referral_url" value="<?=url_base64_encode($shiksha_site_current_refferal)?>">
                <input type="hidden" name="from_where" value="MOB_ONLINE_APPLY">
                <input type="hidden" name="tracking_keyid" id="tracking_keyid" value='<?php echo $trackingPageKeyId;?>'>
        </form>
</div>



<script>
$(document).ready(function() {

	<?php if(isset($_COOKIE['onlineForm_SuccessMessage']) && $_COOKIE['onlineForm_SuccessMessage']!=''){ ?>
                        name = base64_decode('<?=$_COOKIE['onlineForm_SuccessMessage']?>');
                        openConfirmLayer('Application for '+name, $('#referralMessage'));					
	<?php 
	      deleteTempUserData('onlineForm_SuccessMessage');
	} ?>

	$('#thisWeekAnchor, #nextWeekAnchor, #allAnchor').on('click',function(){
		var type = $(this).attr('data-type');
		if(type){
			getInstituteListForHomepage(type);
		}
	});
});

var department = '<?=$department?>';

</script>

<?php 
//deleteTempUserData('homepage_message');
?>

<?php $this->load->view('mcommon5/footer'); ?>

<?php ob_end_flush(); ?>
