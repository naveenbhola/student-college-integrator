<?php ob_start('compress'); ?>
<?php
    $headerComponents = array(
                'm_meta_title' =>      $meta_title,
                'm_meta_description' => $meta_description,
                'canonicalURL' =>$canonicalURL
                );

    $this->load->view('mcommon5/header',$headerComponents);
?>
<script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion("main","nationalMobile"); ?>"></script>
<script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion("collegeReview","nationalMobile"); ?>"></script>
<link rel="stylesheet" href="/public/mobile5/css/<?php echo getCSSWithVersion('collegeReview-home','nationalMobile'); ?>" >
<link rel="stylesheet" href="/public/mobile5/css/<?php echo getCSSWithVersion('permalink-mobile','nationalMobile'); ?>" >

<div id="wrapper" data-role="page" class="of-hide" style="min-height: 413px;padding-top: 40px;">

   <?php
        echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
        echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
   ?>

    <?php $this->load->view('mCollegeReviews5/collegeReviewHeader'); ?>
        
    <div data-role="content">
      <?php 
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    ?>
      
        <!-- Add PAGE Content here -->

        <section class="permalink-content-wrap" style="margin-bottom:0px;">
            
            <?php $this->load->view('mCollegeReviews5/showReviewContentMobile'); ?>

            <div class="permalink-share-list clearfix" style="margin-top:20px;">

                <?php $this->load->view('mCollegeReviews5/socialSharingMobile'); ?>

            </div>   
            
            <?php if($courseReviewsCount >= 3) { ?>
                <a href="<?php echo $reviewData['courseUrl'].'#course-reviews';?>" class="rv_gryBtn1 ldmre" style="background:#E5E5E4; margin-top:20px; color:#575757 !important;">
                    View Other Reviews
                </a>
            <?php } ?>

            <?php $this->load->view('mCollegeReviews5/tilesWidgetMobile'); ?>

        </section>    

        <section class="clearfix content-wrap  content-wrapInhrt colgRvwHP whtBx" style="margin-bottom:10px;">

            <?php $this->load->view('mCollegeReviews5/reviewCollegeWidget'); ?>
            
        </section> 
       
        <!-- PAGE Content ENDS here -->

    <!-- Footer Links Section -->
    <?php $this->load->view('mcommon5/footerLinks'); ?> 

    </div>
</div>

<script>
    $(document).ready(function() {
        // set background image in the tile
        if ($('.tileFinder').length>0) {
            $('.tileFinder').each(function(index){
                    var tileImage = $(this).attr('tileimg');
            if(tileImage.length>10){
                $(this).find('div').css("background-image",'url('+tileImage+')'); 
            }
            $(this).removeAttr('tileimg');
            });
        }
    });
    var initialWidth = 300;
    $(document).ready(function(){
       initialWidth = $('.adjustHeight').width();
       var inititalHeight = (initialWidth * 9) / 21;
       $('.adjustHeight').css('height', inititalHeight);
       $('.gradient-rv').css('height', inititalHeight);
    });

    $( window ).on( "orientationchange", function( event ) {
       setTimeout(function () {
          var newWidth = $('.adjustHeight').width();
          var newHeight = (newWidth * 9) / 21;
          $('.adjustHeight').css('height', newHeight);
          $('.gradient-rv').css('height', newHeight);
       },500);
    });

</script>

<?php $this->load->view('mcommon5/footer'); ?>
<?php ob_end_flush(); ?>
