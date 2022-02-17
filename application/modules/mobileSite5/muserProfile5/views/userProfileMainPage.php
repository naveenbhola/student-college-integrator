<?php ob_start('compress');
//Since, this is a single page application, Cookies were not getting saved when used pressed Back from any page.
//To avoid this, we are making this page as no-cache
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

$headerComponent = array(
    'pageName'           => 'user-profile',
    'boomr_pageid'       => 'user-profile',
    'mobilecss'          => array('userProfileUnified', 'swiper'),
    'm_meta_title'       => 'User Profile | Shiksha.com',
    'm_meta_description' => "",
    'm_canonical_url'    => 'https://www.shiksha.com/userprofile/edit',
    'is_profile_page'    => true,
    'jqueryUIRequired'   => true,
);

$this->load->view('mcommon5/header', $headerComponent);?>
<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>

<div id="wrapper" data-role="page" class="of-hide" style="min-height: 413px;padding-top: 40px;">
    <?php echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger', 'mypanel');
echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel', 'myrightpanel');
?>
    <header id="page-header" class="header ui-header-fixed" data-role="header" data-tap-toggle="false"  data-position="fixed">
        <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader', $displayHamburger = true); ?>
    </header>

        <div data-role="content" id="content_backgroundE6">
          <?php 
            $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
          ?>

    <!-- All info starts here -->
            <?php $this->load->view('muserProfile5/userLandingPage');?>

    <input id = 'useridVal' type="hidden"  value='<?php echo $userId; ?>' />
     <input id = 'shikshaHome' type="hidden"  value='<?php echo SHIKSHA_HOME; ?>' />

    <input id = 'publicProfile' type="hidden"  value='<?php echo $publicProfile; ?>' />
    <input type='hidden' id='isStudyAbroadFlag' value='<?php if($userPreference['ExtraFlag'] == 'studyabroad') echo 'yes'; else echo 'no'; ?>' />
    <input type='hidden' id='abroadSpecializationFlag' value='<?php if($userPreference['ExtraFlag'] == 'studyabroad') echo $userPreference['AbroadSpecialization']; ?>' />

    <?php $privData = $privacyDetails;
$privData           = serialize($privData);
?>
    <input id = 'userPrivacyData' name='userPrivacyData' type="hidden"  value='<?php echo $privData; ?>' />

            <!-- All info ends here -->
    </div><!-- end of content div -->
    <div data-enhance="false" class="">
        <?php $this->load->view('/mcommon5/footerLinks');?>
    </div>

</div><!-- end of wrapper div -->


<a href="#sectionLayer" id="openSectionlayer" class="ui-link" data-inline="true" data-rel="dialog" data-transition="slideup" > </a>


<!-- Other pages start -->
<div data-role="page" id="sectionLayer">
</div>


<!-- Page for Registration dialog Screen -->
  <a href="#fieldSelection" id="openFieldSelection" class="ui-link" data-inline="true" data-rel="dialog" data-transition="none" > </a>
  <div data-role="page" id="fieldSelection" data-enhance="false" class="sltNone">
  </div>


<div data-role="page" id="tupples">

</div>

<!-- <div data-role="popup" data-enhance="false" id="pageThree">

</div> -->
<!-- Other pages start -->

<?php
$alertSuccessPopup    = $_COOKIE['eventCalendarSuccessPopup'];
$addEventSuccessPopup = $_COOKIE['mobile_EC_event_action'];
setcookie('eventCalendarSuccessPopup', '', time() - 3600, '/', COOKIEDOMAIN);
setcookie('mobile_EC_event_action', '', time() - 3600, '/', COOKIEDOMAIN);
?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<?php $this->load->view('/mcommon5/footer');?>

<?php
$regFormId = random_string('alnum', 6);
?>
<script type="text/javascript">
  var regFormId = '<?php echo $regFormId; ?>';
$("img.lazy").lazyload();
</script>

<?php echo includeJSFiles("userProfileMobile", "nationalMobile"); ?>

<script type="text/javascript">
var is_profile_page = true;
  var selected_tab = '<?php if ($_GET['unscr'] >= 0 && $_GET['unscr']!='') {echo (int) $_GET['unscr'];}else{echo -1;}?>';

  $(document).ready(function() {
    if(selected_tab >= 0 && selected_tab!='') {
      $('#editAccountSettings').click();
    }
  });
</script>
<?php ob_end_flush();?>
