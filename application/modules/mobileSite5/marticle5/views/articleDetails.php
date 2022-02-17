<?php 
ob_start('compress');
if(($blogObj->getType() == 'boards' || $blogObj->getType() == 'coursesAfter12th') && !$disableAMPLink){
        $ampURL = getAmpPageURL($blogObj->getType(),$canonicalURL);
}else if(!$disableAMPLink){
        $ampURL = getAmpPageURL('blog',$canonicalURL);
}

$headerComponents = array(
    'm_meta_title'=>$seoTitle,
    'm_meta_description'=>$metaDescription,
    'canonicalURL'=>$canonicalURL,
    'nextURL'=>$nexturl,
    'previousURL'=>$previousurl,
    'pageType' => 'articlePage',
    'mobilePageName' => 'mArticleDetailPage',
    'product' => 'marticleDetailPage',
    'ampUrl'  => $ampURL
); ?>

<?php $this->load->view('/mcommon5/headerV2', $headerComponents);
echo jsb9recordServerTime('SHIKSHA_MOB_NATIONAL_ARTICLE_DETAILS_PAGE',1);
?>
<style type="text/css">
    <?php $this->load->view('marticle5/css/articlePageCSS'); ?>
    <?php $this->load->view('marticle5/css/recoSliderCSS'); ?>
</style>
<div id="wrapper" data-role="page" class="of-hide" style="min-height: 413px;padding-top: 40px;">
    <?php
    //Check if user came directly on this page. If the referrer is not shiksha, we have to display the Hamburger menu
    $displayHamburger = false;
    if(!$_SERVER['HTTP_REFERER'] || ($_SERVER['HTTP_REFERER'] == $currentUrlWithParams)){  //If no referer is defined, show Hamburger menu
        $displayHamburger = true;
    }else if( strpos($_SERVER['HTTP_REFERER'],'shiksha') === false){ //If referer is not from Shiksha, show Hamburger menu
        $displayHamburger = true;
    }    

    //Put a check that if Hash value is added, we have to show the Hamburger
    if(strpos($_SERVER["REQUEST_URI"], 'showHam') > 0){
        $displayHamburger = true;
    }

    if($displayHamburger){
        echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
    }
    echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
    ?>
    <header id="page-header"  class="header ui-header-fixed" data-role="header" data-tap-toggle="false" data-position="fixed">
        <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader', $displayHamburger,'',$boomr_pageid,$isShowIcpBanner);?>
    </header>
    <?php $this->load->view('articleDetailHeader'); ?>
    <div data-role="content">
        <?php 
            $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
        ?>
        <div data-enhance="false">
            <?php 
            $blogDescriptionObj = $blogObj->getDescription();
            if($blogObj->getBlogLayout() != '') {
                switch($blogObj->getBlogLayout()){
                    case 'slideshow':$this->load->view('blogDetailsSlideShow');
                        break;
                    case 'qna':$this->load->view('blogDetailsQnA');
                        break;
                    default: $this->load->view('blogDetailDefault');
                        break;
                }
            }   
            ?>
            <?php 
            if($streamCheck == 'fullTimeMba'){
            ?>
                <div id="mbaToolsWidget" data-enhance="false">
                    <div style="margin: 20px; text-align: center;"><img border="0" src="/public/mobile5/images/ajax-loader.gif"></div>
                </div>
            <?php } ?>

            <!-- comment box start here -->
        <div id="topicContainer">
            <?php
                $url = site_url("messageBoard/MsgBoard/replyMsg");
                $topicUrl = site_url('messageBoard/MsgBoard/topicDetails').'/'.$topicId;
                $userProfile = site_url('getUserProfile').'/';
                $commentData['url'] = $url;
                $commentData['threadId'] = $topicId;
                $commentData['isCmsUser'] = 0;
                $commentData['topicUrl'] = $topicUrl;
                $commentData['userProfile'] = $userProfile;
                $commentData['fromOthers'] = 'blog';
                $commentData['entityTypeShown'] = "Blog Comment";
                $commentData['commentTrackingKey']= $commentTrackingKey;
                $commentData['replyTrackingKey']= $replyTrackingKey;
                $commentData['page'] = 'adpComment';
             ?>
            <?php $this->load->view('mAnA5/showEntityComments',$commentData); ?>
        </div>
        <div id="topicContainer">
            <?php 
                $pageData = array();
                $pageData['pageId'] = $blogObj->getBlogId();
                $pageData['pageType'] = 'ADP';
                $this->load->view('mcommon5/feedbackWidget/feedback', $pageData);
            ?>
        </div>
        <!-- comment box end here -->

            <?php
                $this->load->view('recentArticles');
                $this->load->view('showRelatedArticles');
                $this->load->view('article/articleSchemaMarkup');
                echo Modules::run("Interlinking/InterlinkingFactory/getEntityRHSWidget", $blogEntitiesMapping,'articleDetailPage');
                echo Modules::run("Interlinking/InstituteWidget/getRelatedInstituteWidget", $blogEntitiesMapping,'articleDetailPage');
                echo Modules::run("Interlinking/ExamWidget/getRelatedExamWidget", $blogEntitiesMapping,'articleDetailPage');
                ?>

                 <!-- CHP Interlinking -->
                  <?php $this->load->view('mcommon5/chpInterLinking');?>
                  <!-- CHP Interlinking END-->

                <?php 
                echo Modules::run("Interlinking/ExamWidget/getUpcomingExamDatesWidget", $blogEntitiesMapping,'articleDetailPage');


                if($blogObj->getType() == 'boards' || $blogObj->getType() == 'coursesAfter12th'){
                        echo Modules::run('marticle5/ArticleMobileController/getCustomInterlinkingWidget',$blogObj->getType());
                }

                global $pagesToShowBtmRegLyr;
                if( $validateuser['userid']<1 && in_array($beaconTrackData['pageIdentifier'],$pagesToShowBtmRegLyr) ){
                    $jsFooter = array('recoSlider','bottomStickyRegistration','mArticlePage');
                    $cssFooter = array('tuple','bottomStickyRegistration');
                }else{
                    $jsFooter = array('recoSlider','mArticlePage');
                    $cssFooter = array('tuple');
                }
                $jsFooter[]= 'anaComment';
            ?>

            <?php $this->load->view('/mcommon5/footerLinksV2',array(
                                                          'jsFooter'=>$jsFooter,
                                                           'cssFooter'=>$cssFooter));?>
         </div>
     </div>
	<?php //echo Modules::run('mcommon5/MobileSiteBottomNavBar/bottomNavBar','articlePage', $CategoryId);?>
</div>
<?php $this->load->view('/mcommon5/footerV2');?>
<script type="text/javascript">
    // Load the Tools to decide MBA widget and bind the sliders
    new loadToolstoDecideMBACollegeWidget("mbaToolsWidget","ARTICLEPAGE_MOBILE").loadWidget();
    var GA_currentPage = "<?php echo $GA_currentPage; ?>";
    var ga_user_level = "<?php echo $GA_userLevel;?>";
    var searchPageName = 'mobileArticlePage';
    <?php if($_REQUEST['test'] == '1234'){?>
            var test_request = true;
    <?php } ?>
</script>
