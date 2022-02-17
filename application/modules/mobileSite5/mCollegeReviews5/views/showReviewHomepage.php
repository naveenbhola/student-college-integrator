<?php ob_start('compress'); ?>
<?php
//Load Autosuggestor JS files
$headerComponent = array('searchPage'=>'true','jqueryIssue' => 'true');
$this->load->view('mcommon5/header',$headerComponent);
global $isWebViewCall;
?>

<link rel="stylesheet" href="/public/mobile5/css/<?php echo getCSSWithVersion('collegeReview-home','nationalMobile'); ?>" >

<?php
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

<script>var searchPageName = 'shikshaHomepage';</script>

<div id="wrapper" data-role="page" class="of-hide" <?php if(!$isWebViewCall){?> style="min-height: 413px;padding-top: 40px;" <?php }?>>

   <?php
        echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
        echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
   ?>

    <?php if(!$isWebViewCall){ $this->load->view('collegeReviewHeader'); }?>

    <div data-role="content">
        <?php 
            $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
        ?>

      
        <!-- Add PAGE Content here -->
       
         <section class="clearfix content-wrap  content-wrapInhrt colgRvwHP whtBx" style="margin-bottom:0px;">

                <h1 class=" content-inner cr_titltxt"><?=$pageFor?> College Reviews</h1>

                <?php $this->load->view('searchWidget'); ?>

                <?php $this->load->view('primaryTilesWidget'); ?>
              
        </section>
        
        <?php $this->load->view('homepageReviewsWidget'); ?>
        
        <section class="clearfix content-wrap  content-wrapInhrt colgRvwHP whtBx" style="margin-bottom: 10px;padding-top: 10px;">

            <?php $this->load->view('secondaryTilesWidget'); ?>

            <?php $this->load->view('reviewCollegeWidget'); ?>

            <?php
            if($stream == "1") {
                $this->load->view('naukriToolWidget'); 
            }?>
            
        </section>
       
        <!-- PAGE Content ENDS here -->

	<!-- Footer Links Section -->
	<?php $this->load->view('mcommon5/footerLinks'); ?> 

    </div>
    <a data-transition="slide" data-rel="dialog" data-inline="true" href="#searchReviewLayer" id="callSearchReviewLayer">&nbsp;</a>
</div>

<div id="searchReviewLayer" data-enhance="false" data-role="page">
</div>

<?php 
//deleteTempUserData('homepage_message');
?>
<div id="popupBasicBack" data-enhance='false'></div> 

<?php $this->load->view('mCollegeReviews5/readMoreLayer'); ?>
<?php $this->load->view('mcommon5/footer'); ?>

<?php ob_end_flush(); ?>
