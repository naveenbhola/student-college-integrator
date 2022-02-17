<?php ob_start('compress'); ?>

<?php
$headerComponents = array(
      'mobilecss' => array('mVitResult'),
      'noIndexNoFollow' => true
        );
$this->load->view('/mcommon5/header',$headerComponents);
?>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic" rel="stylesheet" type="text/css">

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
<div data-role="content" id="pageMainContainerId">  
    <div class="head-group" data-enhance="false">
        <div class="left-align" style="margin: 11px 5px 10px 49px; margin-left: 12px;">
            <div style="margin-bottom:2px; float: left; width: 100%; font-size: 18px;"><h1 style="float: left; margin-right: 5px; padding: 0; line-height: 1.5">VITEEE 2017 Result</h1></div>
        <div class="clearfix"></div>
        </div>   
    </div>   
      <div data-enhance="false" >
        <div class="vitee-r-cont">
            <div class="rslt-bx">
                <div class="rslt-info">
                    <img class="vit-img" src="/public/mobile5/images/vit_m.png" alt="vitee result 2017 png" width="84" height="88" />
                    <p>“Shiksha is hosting the official VITEEE 2017 Result for your convenience. The scores you can see are directly sourced from the VIT website. <a href="http://www.vit.ac.in/" ga-attr="VIT_WEBSITE">Click here</a> to get redirected to the actual result page.”</p>
                    <div><a class="btn-primary" id="modify-search" ga-attr="MODIFY_SEARCH">Modify Search</a></div>
                    <table class="ex-det">
                        <tr>
                            <td><label>Name</label></td>
                            <td><?=ucwords(strtolower($studentInfo['candidate_name']));?></td>
                        </tr>
                        <tr>
                            <td><label>Application Number</label></td>
                            <td><?=$studentInfo['app_num'];?></td>
                        </tr>
                        <tr>
                            <td><label>DOB</label></td>
                            <td><?=date('d/m/Y',strtotime($studentInfo['dob']));?></td>
                        </tr>
                        <tr>
                            <td><label>Gender</label></td>
                            <td><?=ucfirst(strtolower($studentInfo['gender']));?></td>
                        </tr>
                        <tr>
                            <td><label>Rank</label></td>
                            <td class="ex-rnk"><strong><?=$studentInfo['rank'];?></strong></td>
                        </tr>
                    </table>

                </div>
            </div>
            <div class="rslt-bx">
                <div class="rslt-info">
                    <div class="vte-crd">
                        <p>To learn more about VIT University</p>
                        <a class="vte-clk" ga-attr="VIT_LINK" href="<?=SHIKSHA_HOME;?>/university/vit-university-vellore-29714">Click Here</a>
                    </div>
                </div>
            </div>
        </div>
        <?php 
        echo modules::run('mobile_listing5/InstituteMobile/getRecommendedListingWidget',$listing_id,$listing_type, 'alsoViewed', $courseIdsMapping);
        $this->load->view('/mcommon5/footerLinks'); ?>
      </div>
  </div>
</div>

<div id="popupBasicBack" data-enhance='false'></div>
<img id = 'beacon_img' src="/public/images/blankImg.gif" width=1 height=1 >
<?php $this->load->view('/mcommon5/footer', array("jsMobileFooter" => array('mVitResult')));?>    
<?php ob_end_flush(); ?>
<script type="text/javascript">
  var GA_currentPage = "VIT RESULT PAGE";
  var ga_commonCTA_name = '_VIT_RESULT_PAGE_MOB';
  var ga_user_level = "Logged In";
  jQuery(document).ready(function($){
    initializePage();
  });
</script>
