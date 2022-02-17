<?php ob_start('compress'); ?>
<?php
$headerComponent = array('mobilecss'  => array('campusConnect_home'),
                         'pageName'   => $boomr_pageid);
$this->load->view('mcommon5/header',$headerComponent);
?>
<div id="wrapper" data-role="page" class="of-hide" style="background: #e5e5da !important;min-height: 413px;padding-top: 40px;">
        <?php
                echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
                echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
        ?>
        <?php $this->load->view('campus_connect/ccHomepageHeader'); ?>
        <div data-role="content" style="background:#e6e6dc !important;">
            <?php 
                $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
            ?>
                <div data-enhance="false">
                <!-- Top search widget - start -->
                <?php // $this->load->view('campus_connect/searchWidget'); ?>
                <!-- Top search widget - end -->
                
                <!-- Institute, Course Name Widget - start -->
                <?php $this->load->view('campus_connect/instituteHeader'); ?>
                <!-- Institute, Course Name Widget - end -->
                
                <!-- Ask question widget - start -->
                <?php $this->load->view('campus_connect/intermediatePageAskQuestion'); ?>
                <!-- Ask question widget - end -->
                
                <!-- Question widget - start -->
                <?php $this->load->view('campus_connect/intermediatePageQuestionsWidget'); ?>
                <!-- Question widget - end -->
                
                <!-- CA list - start -->
                <?php $this->load->view('campus_connect/intermediatePageCAListWidget'); ?>
                <!-- CA list - end -->
                
                <?php $this->load->view('mcommon5/footerLinks'); ?>
                </div>
        </div>
</div>
<div id="popupBasicBack" data-enhance='false'></div> 
<?php $this->load->view('mcommon5/footer'); ?>
<script>
        $(document).ready(function(){
                $(document).click(function (e)
                {
                        var container1 = $("#intermediateQuestionDropDownList");
                        if (!container1.is(e.target) // if the target of the click isn't the container...
                                && container1.has(e.target).length === 0) // ... nor a descendant of the container
                        {
                                $('#intermediateQuestionDropDown').hide();
                        }
                        var container2 = $("#questionCategoryList");
                        if (!container2.is(e.target) // if the target of the click isn't the container...
                                && container2.has(e.target).length === 0) // ... nor a descendant of the container
                        {
                                $('#questionCategoryDropDown').hide();
                        }
                        var container3 = $("#questionCourseList");
                        if (!container3.is(e.target) // if the target of the click isn't the container...
                                && container3.has(e.target).length === 0) // ... nor a descendant of the container
                        {
                                $('#questionCourseDropDown').hide();
                        }
                });
        });
</script>
<?php ob_end_flush(); ?>
