<?php ob_start('compress'); ?>
<?php
$headerComponents = array(
 'm_meta_title' => $currentTileData["seoPageTitle"],
 'm_meta_description' => $currentTileData["seoPageDescription"],
 'm_meta_keywords' => ' ',
 'm_canonical_url' => $currentTileData["seoUrl"],
 'jqueryIssue' => 'true',
);
$this->load->view('mcommon5/header',$headerComponents); ?>
<link rel="stylesheet" href="/public/mobile5/css/<?php echo getCSSWithVersion('collegeReview-home','nationalMobile'); ?>" >

<?php

if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!='' && strpos($_SERVER['HTTP_REFERER'],'shiksha') != false){
   
      $displayBackButton = true; 
}else{
      $displayBackButton = false; 
   
}
global $isWebViewCall;
?>

<div id="wrapper" data-role="page" class="of-hide" <?php if(!$isWebViewCall){?> style="min-height: 413px;padding-top: 40px;" <?php }?>>

   <?php
        echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
        echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
   ?>

    <?php if(!$isWebViewCall){$this->load->view('collegeReviewHeader'); }?>

    <div data-role="content" style='background-image: none;background-color:#fff'>
        <?php 
            $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
        ?>
      
      
    <section class="clearfix content-wrap  content-wrapInhrt colgRvwHP whtBx" style="margin-bottom:0px;">
	 <?php if($displayBackButton == true){ ?>
	    <a class="back-col"  href="<?php echo $_SERVER['HTTP_REFERER'];?>" style="float: left;
    padding: 15px 2px !important;border-right:none !important;">
	       <i class="back-arr"></i>
	    </a>
	 <?php } ?>
	 
            <h1 class=" content-inner cr_titltxt" style=" padding-left: 32px;text-align: left;"><?=$currentTileData["seoPageTitle"]?></h1>
</section>
	
	<?php
	if($currentTileData['description']!='' && strlen($currentTileData['description']) > 80)
	{
	?>
	<section class="clearfix content-wrap  content-wrapInhrt colgRvwHP whtBx" style="margin-bottom:0px;">
	    <h2 id="currentTileDescription1" class=" content-inner cr_titltxt" style="padding-left: 10px; font-weight: normal; font-size: 12px; border-bottom: 0px none; text-align: left; padding-right: 10px; text-transform: none; margin: 0px; line-height: 14px; padding-bottom: 0px;"><?=substr($currentTileData['description'],0,80)?>...&nbsp;<a href="javascript:void(0);">more</a></h2>
	    <h2 id="currentTileDescription2" class=" content-inner cr_titltxt" style="display: none; padding-left: 10px; font-weight: normal; font-size: 12px; border-bottom: 0px none; text-align: left; padding-right: 10px; text-transform: none; margin: 0px; line-height: 14px; padding-bottom: 0px;"><?=$currentTileData['description']?></h2>
        </section>
        <?php
	}
	else if($currentTileData['description']!='')
	{
        ?>
	<section class="clearfix content-wrap  content-wrapInhrt colgRvwHP whtBx" style="margin-bottom:0px;">
	    <h2 id="currentTileDescription1" class=" content-inner cr_titltxt" style="padding-left: 10px; font-weight: normal; font-size: 12px; border-bottom: 0px none; text-align: left; padding-right: 10px; text-transform: none; margin: 0px; line-height: 14px; padding-bottom: 0px;"><?=$currentTileData['description']?></h2>
	</section>
	<?php
	}
	?>
        <!-- Add PAGE Content here -->
	<div id="intermediateReviewWidget">
	    <?php $this->load->view('intermediatePageReviewsWidget',$currentTileData); ?>
	</div>

	 <div id="more-intermediate-reviews-list"></div>
	<div style="text-align: center; margin-top: 7px; margin-bottom: 10px; display: none;" id="loader-id">
	  <img border="0" alt="" id="loadingImage" src="/public/mobile5/images/ajax-loader.gif">
	</div>

	<div id="noMoreResultsMsg" style="text-align: center;background-color:#efefef;padding: 10px 0 10px;color: #575757;font-size: 14px;">
	    <span></span>
	 </div>

  <div style='font-size: 0.8em; padding:5px; margin: 5px; background-color:#efefef;'>
    <b style="float:left; display:block;">Disclaimer : </b><span style="display: block; margin-left: 80px; color: rgb(112, 112, 112);">All reviews are submitted by current students & alumni of respective colleges and duly verified by Shiksha.</span>
  </div>
        <!-- PAGE Content ENDS here -->


	<!-- Footer Links Section -->
	<?php $this->load->view('mcommon5/footerLinks'); ?> 

    </div>
    
</div>



<script>

$(window).scroll(function() {
    clearTimeout($.data(this, 'scrollTimer'));
    $.data(this, 'scrollTimer', setTimeout(function() {
        //Check which all Review Divs are in Focus. Whichever of them are in focus, we will have to make a view call at Backend.
        //Also, this call should not happen for Same session-review pair / Same user-review pair
        $("div[id^='readMore']").each (function(ind){
                if( isElementInViewport ($(this)) ){
                        divId =  $(this).attr('id');
                        res = divId.split('More');
                        reviewId = res[1];
                        if(jQuery.inArray(reviewId,reviewsAdded) == -1 && jQuery.isNumeric(reviewId)){
                                markReviewRead(reviewId, 'mobile', 'collegeReviewIntermediate');
                                reviewsAdded.push(reviewId);
                        }
                }
        });
        console.log("Haven't scrolled in 5sec!");
    }, collegeReviewScrollTimer));
});

</script>

<script>
    
    <?php
	
        $item_per_page = 3;
        $total_pages = ceil($reviewData['totalCollegeCards']/$item_per_page);
    ?>
    
    var item_per_page = 3;
    var pageNo = 1;
    var total_pages = '<?php echo $total_pages; ?>';
    var seoUrl = '<?php echo $seoUrl; ?>';
    var reviewPaginationFlag = true;
    
    
</script>
<script>
      // ankit code
       $global_count_mobile_slider = [];
       $flag_left_right = true;
     var widgetHandler = new CollegeReviewSlider(".colgRvwSlidrBx-inner",item_per_page,pageNo,total_pages,seoUrl);
	 widgetHandler.bindCollegeReviewWidget();

		 
      
</script>

<?php 
//deleteTempUserData('homepage_message');
?>
<div id="popupBasicBack" data-enhance='false'></div>
<?php $this->load->view('mCollegeReviews5/readMoreLayer'); ?>
<?php $this->load->view('mcommon5/footer'); ?>
<?php ob_end_flush(); ?>
