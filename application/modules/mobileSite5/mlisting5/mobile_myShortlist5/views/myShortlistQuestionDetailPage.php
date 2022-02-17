<?php
$headerComponent = array('mobilecss'  => array('mshortlist'),
                         'pageName'   => $boomr_pageid,
                         'js'         => array(),
                         'jsMobile'   => array('myShortlistMobile'),
                         'm_meta_title'=> "Add to my Shortlist | Shiksha.com",
                         'm_meta_description' => "Add institutes to your shortlist to find placement data, read reviews, ask questions to current students and get alerts."
                         );
$this->load->view('mcommon5/header',$headerComponent);
?>
<style>.disabled {pointer-events: none; cursor: default;}</style>
<div id="wrapper" data-role="page" class="of-hide">
        <?php
                echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
                echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
        ?>
        <?php $this->load->view('mobile_myShortlist5/myShortlistHeader'); ?>
        <div data-role="content">
            <?php 
                $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
            ?>
            <div data-enhance="false">
                <section class="content-section">
                    <?php $this->load->view("widgets/questionDetailSec");?>
                </section>
        <?php
        if(!empty($userId))
        {
        ?>
        <section>
            <article class="listing-tupple clearfix">
                    <div id="recommendationWidget" style="padding:10px 0px;"></div>
                    <div id="recommendationWidgetLoader"></div>
            </article>
        </section>
        <?php
        }
        ?>
                <?php $this->load->view('mcommon5/footerLinks'); ?>
                </div>
        </div>
</div>


<?php $this->load->view('mcommon5/footer'); ?>
<script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion("jquery.flexslider-min","nationalMobile"); ?>"></script>
<script>
var mshortlistQuesDetailPage = 1;
$(document).ready(function(){
    showRecommendationWidgetShortlist();
});
</script>