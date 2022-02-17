<?php
      $this->load->view('mobile_myShortlist5/myShortlistAssetsLoader');
?>

<div id="wrapper" data-role="page" class="of-hide" style="min-height: 413px;padding-top: 40px;">
    <?php
        echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
        echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
    ?>
    <?php $this->load->view('mobile_myShortlist5/myShortlistHeader'); ?>
    <?php 
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    ?>
    <?php
        if(empty($shortlistedCoursesIds) || $addMoreColg) {
            if( isset($tracking_keyid) ) { // If tracking key has been set in the controller, pass it on to the view ahead
                $data = array('searchCollegeWidgetKey' => $tracking_keyid);
                $this->load->view('mobile_myShortlist5/myShortlistSearch', $data);
            } else {
                $this->load->view('mobile_myShortlist5/myShortlistSearch');
            }
        } else {
            $this->load->view('mobile_myShortlist5/myShortlistTuples');
        }
    ?>
</div>
<?php if(!empty($shortlistedCoursesIds)){ ?>
    <div class="mys-overlay" id="mys-overlay" style="display:none;"></div>
    <div class="myshortlistActionPoints"></div>
<?php } ?>

<div data-role="dialog" id="walkthroughHTML" data-enhance="false" class="of-hide" style="background-color: #3B3B34;"></div>

<?php 
    $this->load->view('mcommon5/footer'); 
    $this->load->view('mobile_myShortlist5/myShortlistFooterScript');
?>
