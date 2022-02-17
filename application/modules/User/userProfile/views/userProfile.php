<?php 

$headerComponents = array(
        'js'               =>array('header'),
        'css'              =>array('userProfileUnified'),
        'jsFooter'         =>array(),
        'title'            => $title,
        'metaDescription'  => '',
        'metaKeywords'     => '',
        'product'          => 'new_profile_page',
        'noIndexNoFollow'  => 'Y'    
        );

$this->load->view('common/header', $headerComponents);
?>
<style>
.layer-outer{width: auto !important}
</style>
<div class="main_content reset" >
	<div class="container">
		
    <!-- profile header-->
    <?php $this->load->view('profilePageHeader'); ?>

		<!--profile tabs -->
        <div class="n-row">
            <section class="prf-tabs">
               
                <!-- profile page tabs -->
                <?php $this->load->view('profilePageTabs');?>

                <div class="col-lg-12 pLR0">
                    <div class="prf-tab-cont">
  
                    <!--profile page starts-->
                        <?php $this->load->view('profilePagePersonalProfile');?>
                    <!--profile page ends-->

                    <!--Account Settings starts-->
                        <?php $this->load->view('profilePageAccountsAndSettings');?>
                    <!--Account Settings ends-->
                    
                    <!--My activity Settings starts-->
                        <?php $this->load->view('profileMyActivity');?>
                    <!--My activity Settings ends-->
    
                    </div> 
                </div>
                <!--  Toast Msg on Web ANA-->
                <div class="notify-bubble center report-msg" id="noti" style="top: 130px; opacity: 1;display: none; ">
                      <div class="msg-toast">
                         <a class="cls" href="javascript:void(0);" onclick="closeToast(this);">×</a>
                        <p id="toastMsg"></p>
                      </div>                                    
                    </div>

                <!--right side ad-->
                    <?php $this->load->view('profilePageRightSideAd');?>
                <!--right side ads end-->

        <input id='userId' type='hidden' value='<?php echo htmlentities(strip_tags($userId)); ?>'>
        <input id='activityFlag' type='hidden' value=''>
        <input id = 'publicProfile' type="hidden" value='<?php echo $publicProfile;?>' />
        
        <input type='hidden' id='isStudyAbroadFlag' value='<?php if($userPreference['ExtraFlag'] == 'studyabroad') echo 'yes'; else echo 'no'; ?>' />
        <input type='hidden' id='abroadSpecializationFlag' value='<?php if($userPreference['ExtraFlag'] == 'studyabroad') echo $userPreference['AbroadSpecialization']; ?>' />

         <p class="clr"></p>
            </section>
         </div>

    </div>
</div>


<?php $this->load->view('common/footer', array('errorPageFlag'=>'no'));?>
<?php echo includeJSFiles("userProfileDesktop"); ?>
<!-- userProfileAsync is the combination of  "CalendarPopup.js","user.js","studyAbroadCommon.js","studyAbroadAccountSetting.js" -->
<?php echo includeJSFiles("userProfileAsync"); ?>
<script type="text/javascript">

currentPageName = "newuserprofilepage";	
if(typeof(shikshaUserProfileForm) == 'undefined'){
  var shikshaUserProfileForm = {};
};	
$j(document).ready(function(){
	crteSlider();
});

$j(window).resize(function(){
	crteSlider();
});

var sldrPos=0;
function crteSlider(){
	var w_width=$j(window).width();
	$j('.n-featBanrs li').each(function(){
		$j(this).width((w_width-50)/5);
	});

	$j('.featMoveArw').click(function(){
		var t = $j('.n-featBanrs li').width();
		var mrgnLft = $j('.n-featBanrs').css('margin-left');
		if(sldrPos == 0){
			$j('.n-featBanrs').css('margin-left','-100%');
			sldrPos=1;
		}
		else{
			$j('.n-featBanrs').css('margin-left','0%');
			sldrPos=0;
		}
	});
}


</script>
<script type="text/javascript">
    var selected_tab = '<?php if(isset($_GET['unscr']) && $_GET['unscr'] >= 0) { echo (int)$_GET['unscr']; } else{  echo -1;} ?>';
    $j(document).ready(function() {
        trackEventByGA('UserProfileClick','LINK_PROFILE_TAB_LOAD');

        $j(".prf-tabpane").hide(); //Hide all content

        if(selected_tab >= 0) {
            $j("ul.prft-tabs li").removeClass("current");
            $j("#setngT").addClass("current").show();
            $j(".prf-tabpane:eq(1)").show();
            if(selected_tab > 0) {
                $j('html, body').animate({
                    scrollTop: $j('#communicationPreferenceSection').offset().top-150
                }, 200,function(){$j('#unsubscribeBtn'+selected_tab).parents('.email_cols').addClass('highlight')});
            }else{
                $j('html, body').animate({
                    scrollTop: $j('#communicationPreferenceSection').offset().top-150
                }, 200);
            }
        } else {
            $j("ul.prft-tabs li:first").addClass("current").show();
            $j(".prf-tabpane:first").show();
        }        
    });

    $j(document).ready( function() {
        if($j('#menteeChatSchedulingForm') != 'undefined' || $j('#menteeChatSchedulingForm') != '')
            changeUI('first');
        if($j('#mentee-old-chat-container') != 'undefined' && $j('#mentee-old-chat-container').length > 0 && $j('#mentee-old-chat-container').height() > 300) {
            $j('#mentee-old-chat-container').css({'overflow-y': 'scroll', 'height': '300px'});
        }
        handleAnAToastMsg();
    });

    window.onhashchange = function() {
        var hash = window.location.hash.substr(1);
        var userActivityBinder =  new UserActivityBinder();
        userActivityBinder.hashNavigation(hash);
    }
    lazyLoadCss('//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("registration");?>');
    $j("img.lazy").lazyload({effect:"fadeIn",threshold:100});
    var educationalPrefSectionHTML = $j('#educationalPreferenceSection').html();
</script>
</body>
</html>
