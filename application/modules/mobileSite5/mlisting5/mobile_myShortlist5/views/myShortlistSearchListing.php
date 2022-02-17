<?php
      $this->load->view('mobile_myShortlist5/myShortlistAssetsLoader');
?>


<div id="wrapper" data-role="page" class="of-hide">
    <?php
        echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
        echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
    ?>
  <?php $this->load->view('mobile_myShortlist5/myShortlistHeader'); ?>


    <div   data-role="content">
        <?php 
            $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
        ?>
        <div data-enhance="false">  
            <section class="content-section">
            <?php $this->load->view('mobile_myShortlist5/myShortlistSearchByExamResult'); ?>
            </section>
            <?php $this->load->view('mcommon5/footerLinks'); ?>
        </div>
    </div>
</div>
<div data-role="dialog" id="walkthroughHTML" data-enhance="false" class="of-hide" style="background-color: #3B3B34;"></div>

<?php
    $this->load->view('mcommon5/footer');
    if( isset($tracking_keyid) ) { // If tracking key has been set in the controller, pass it to the view ahead
        $this->load->view('mobile_myShortlist5/myShortlistFooterScript', array('tracking_keyid' => $tracking_keyid));
    } else {
        $this->load->view('mobile_myShortlist5/myShortlistFooterScript');
    }
?>
