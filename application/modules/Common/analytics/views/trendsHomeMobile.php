<?php ob_start('compress'); ?>
<?php
$headerComponents = array(
      'mobilecss' => array('style'),
      'jsMobile' => array('ana'),
      'js' => array('analytics'),
      'css' => array('bootstrapv2','analytics'),
      'm_meta_title' => $seoTitle,
      'm_meta_description' => $seoDesc,
      'product' => 'shiksha_analytics'
        );
$this->load->view('/mcommon5/header',$headerComponents);
?>
<div id="wrapper" data-role="page" style="min-height: 413px;" class="of-hide">

    <?php
    //Check if user came directly on this page. If the referrer is not shiksha, we have to display the Hamburger menu
    $displayHamburger = false;
    if(!$_SERVER['HTTP_REFERER']){  //If no referer is defined, show Hamburger menu
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
    <header id="page-header" class="header ui-header ui-bar-inherit slidedown ui-header-fixed" data-role="header" data-tap-toggle="false" style="height:auto;" role="banner">
           <div id="page-header-container" style=""><?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger,'','mobilesite_LDP');?></div>
    </header>
    <div data-role="content" data-enhance="false" class="analytics-container">
        <?php 
            $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    ?>
        <?php $this->load->view("analytics/homeWidgets/topWidget"); ?>
        <!--Row with two equal columns-->
        <div class="container">
                        <div class="row">
                        <?php 
                            $this->load->view("analytics/homeWidgets/popularUniversities");
                            $this->load->view("analytics/homeWidgets/popularInstitutes");
                        ?>
                        </div>
                        <?php 
                            $this->load->view("analytics/homeWidgets/popularStream");
                            $this->load->view("analytics/homeWidgets/popularCourses");
                        ?>
                        <div class="row">
                            <?php 
                                $this->load->view("analytics/homeWidgets/popularExams");
                                $this->load->view("analytics/homeWidgets/popularSpecialization");
                            ?>
                        </div>
                        <?php
                            $this->load->view("analytics/homeWidgets/trendingQuestions");
                            $this->load->view("analytics/homeWidgets/articles");
                        ?>
                        <p class="disclaimer"><strong>Disclaimer:</strong> Shiksha Trends are calculated using students' behaviour in the last 12 months on various pages of Shiksha website. Scoring is done on a relative scale of 0 to 100, where a value of 100 signifies the most visited topic within a group of topics (eg. Delhi University in All Universities) on Shiksha and a value of 50 signifies a topic half as visited. </p>
        </div>
        <?php
            $this->load->view('/mcommon5/footerLinks');
        ?>
    </div>
</div>


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div id="popupBasicBack" data-enhance='false'></div>
<?php $this->load->view('/mcommon5/footer');?>
<?php ob_end_flush();?>