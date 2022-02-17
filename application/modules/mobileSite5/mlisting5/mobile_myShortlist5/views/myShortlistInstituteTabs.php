<?php
$headerComponent = array('mobilecss'  => array('mshortlist'),
                         'pageName'   => $boomr_pageid,
                         'jsMobile'   => array('myShortlistMobile'),
                         'm_meta_title'=> "Add to my Shortlist | Shiksha.com",
                         'm_meta_description' => "Add institutes to your shortlist to find placement data, read reviews, ask questions to current students and get alerts.",
                         'css'        => array('BeatPicker'),
                         'jqueryVersion' => '1.8.0'
                         );
$this->load->view('mcommon5/header',$headerComponent);
?>
<style>
.alumini-title{font-size: 12px;display: block;background:none;box-shadow:none;margin-bottom:0px;color:#000;padding-left: 10px;}
.alumini-cont{padding: 0 0.625em 0.625em;}
.alumini-sub-title{font-size:12px;}
</style>
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
                    <?php $this->load->view("myShortlistSearchByExamResultSnippets",array('tracking_keyid'=>MOBILE_NL_SHORTLIST_COURSE_DETAIL_PAGE_TUPLE_SETLAYER_DEB));?>
                <article class="listing-tupple clearfix">
                    <nav class="listing-nav">
                        <ul id="listingTabMenu">
                            <?php if(in_array($courseObject[0]->getId(),$coursesWithPlacementData)){ ?>
                            <li><a class="active" href="javascript:void(0);" onclick="gaTrackEventCustom('MY_SHORTLIST_PAGE_MOBILE', 'shortlist_tuple_detail_tab', 'placement'); return getNaukriIntegrationWidget('',5,5);" tabname="placements">Placements</a></li><li><span>&#8226;</span></li>
                            <?php }?>
                            <?php if($courseObject[0]->getReviewCount() > 0){?>
                            <li><a href="#" tabname="reviews" onclick="gaTrackEventCustom('MY_SHORTLIST_PAGE_MOBILE', 'shortlist_tuple_detail_tabs', 'reviews'); return getReviewTabData(<?php echo $courseObject[0]->getId();?>);">Reviews</a></li><li><span>&#8226;</span></li>
                            <?php }?>
                            <li><a tabname="ask" onclick="populateQuesDiscLayer('question','530');setHiddenParamsForAsk('<?php echo $courseObject[0]->getId();?>','<?php echo $courseObject[0]->getInstituteId();?>')" data-inline="true" data-rel="dialog" data-transition="fade" href="#questionPostingLayerOneDiv">Ask</a></li><li><span>&#8226;</span></li>
                            <li><a href="#" tabname="notes" onclick="gaTrackEventCustom('MY_SHORTLIST_PAGE_MOBILE', 'shortlist_tuple_detail_tabs', 'notes'); return getNotesTabData(<?php echo $courseObject[0]->getId();?>);">Notes</a></li>
                        </ul>
                    </nav>
                    <div id="widget_data_container"></div>
                    <div id="loader_img" style="text-align:center;margin-top:10px;display:none;"><img id="loadingImage" src="/public/mobile5/images/ajax-loader.gif" border=0 alt="" ></div>
                </article>
                
        </section>

        <?php
        if(!empty($userId))
        {
        ?>
                    <input type="hidden" id="instituteCoursesQP" name="instituteCoursesQP">
                    <input type="hidden" id="instituteIdQP" name="instituteIdQP">
                    <input type="hidden" id="responseActionTypeQP" name="responseActionTypeQP" value="Asked_Question_On_Listing_MOB">
                    <input type="hidden" id="listingTypeQP" name="listingTypeQP" value="institute">

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
<?php if(!empty($shortlistedCoursesIds)){ ?>
    <div class="mys-overlay" id="mys-overlay" style="display:none;"></div>
    <div class="myshortlistActionPoints"></div>
<?php } ?>
<div data-role="dialog" id="walkthroughHTML" data-enhance="false" class="of-hide" style="background-color: #3B3B34;"></div>
<?php $this->load->view('mcommon5/footer');
$this->load->view('mobile_myShortlist5/myShortlistFooterScript');
 ?>

<div data-role="page" id="alumniDataSpecialization" data-enhance="false"><!-- dialog-->
    <header id="page-header" class="clearfix" >
            <div data-role="header" data-position="fixed" class="head-group ui-header-fixed" data-tap-toggle="false">
                <a id="specializationOverlayClose" href="javascript:void(0);" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>
                <h3>Choose Specialization</h3>
            </div>
    </header>
    <section class="content-wrap2 fixed-wrap" id="specLayer">
    </section>
</div>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<!-- <script src="/public/mobile5/js/<?php echo getJSWithVersion("jquery.flexslider-min"); ?>"></script>
 -->
 <script>
var naukri_integration_data_base_url = "<?php echo '/listing/Naukri_Data_Integration_Controller/getDataForNaukriSalaryWidget/'.$courseObject[0]->getInstituteId().'/'.$courseObject[0]->getId()?>";
var universal_selected_naukri_splz   = '';
var universal_number_of_funcional    = 5;
var universal_no_of_componies        = 5;
var isOverlayOpen                    = false;
var stopRedirectAfterQuesPost        = 1;

$(document).ready(function(){
    $("[tabname='<?=$tab_name;?>']").click();
});
</script>
