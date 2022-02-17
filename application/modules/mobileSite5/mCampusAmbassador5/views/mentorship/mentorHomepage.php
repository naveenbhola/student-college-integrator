<?php ob_start('compress'); ?>
<?php
$headerComponent = array('mobilecss'  => array('mentorship-mobile'),
                         'pageName'   => $boomr_pageid
			 );

$this->load->view('mcommon5/header',$headerComponent);
?>
<div id="wrapper" style="background:#efeff0;min-height: 413px;padding-top: 40px;" data-role="page" class="of-hide ">
        <?php
                echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
                echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
        ?>
        <?php $this->load->view('campus_connect/ccHomepageHeader'); ?>
        <div data-role="content" style="background:#e6e6dc !important;">
            <?php 
                $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
            ?>
                <div>
                
               <section style="padding:0;" class="clearfix content-section">  
      	<div style="text-align:center;padding:10px 0;" class="mentor-widget-box clearfix" id="mainImage">
   	    	<img width="320" height="226" alt="mentor-bg" src="<?php echo SHIKSHA_HOME;?>/public/mobile5/images/mentor-bg.jpg">
        </div>
      </section>
	       <section class="clearfix content-section">
      		<div class="mentor-widget-head">
            	<h1 class="font-20" style="font-weight:normal;">Planning for Engineering..</h1>
				<p class="font-16" style="margin-top:5px;">And confused? </p>
            </div>
	      	<div class="mentor-widget-box get-mentor-sec clearfix">
        	<strong>Get a mentor</strong>
            <p>Who will guide you through the entire engineering preparation and college selection process.</p>
            <?php if(isset($isMentee) && $isMentee < 1){?><a class="get-mentor-btn" href="javascript:void(0);" onclick="goToForm(); trackEventByGAMobile('MOBILE_GET_A_MENTOR_FROM_<?php echo strtoupper($boomr_pageid);?>_TOP_OF_PAGE');">GET A MENTOR</a><?php }?>	
        </div>
      </section>
	       <section class="clearfix content-section">
	      	<div class="mentor-widget-box get-mentor-sec clearfix">
            <h2 class="mentor-widget-title">Who are these mentors? </h2>
			<i class="mentor-mobile-sprite mentor-image"></i>
            
	    <h3 style=font-weight:normal;><?php if(isset($totalMentor['totalMentor']) && $totalMentor['totalMentor'] > 100){?><span class="count-color"><?php echo $totalMentor['totalMentor'];?></span><?php }?> CURRENT ENGINEERING STUDENTS</h3>
            <ul class="mentor-widget-list">
            	<li><span>Studying in various branches</span></li>
                <li><span>From top colleges like IITs, NITs</span></li>
                <li><span>Spread across 22 states</span></li>
            </ul>
        </div>
      </section>
	       <section class="clearfix content-section">
	      	<div class="mentor-widget-box get-mentor-sec clearfix">
            <h2 class="mentor-widget-title">How does it work?</h2>
            <ul class="mentor-work-list">
            	<li>
                	<div class="enroll-work-head">
                    	<strong class="flLt">Enroll</strong>
                        <i class="mentor-mobile-sprite flRt mentor-enrol-icon"></i>
                        <div class="clearfix"></div>
                    </div>
                    <div class="enroll-work-info">
                    	Submit your details along with preferences
                    </div>
                </li>
                <li>
                	<div class="enroll-work-head">
                    	<strong class="flLt">Mentor Match</strong>
                        <i class="mentor-mobile-sprite flRt mentor-match-icon"></i>
                        <div class="clearfix"></div>
                    </div>
                    <div class="enroll-work-info">
                    	We will assign a mentor for your preferred branch &amp; location

                    </div>
                </li>
                <li>
                	<div class="enroll-work-head">
                    	<strong class="flLt">Connect</strong>
                        <i class="mentor-mobile-sprite flRt mentor-connect-icon"></i>
                        <div class="clearfix"></div>
                    </div>
                    <div class="enroll-work-info">
                    	 Ask questions and schedule chats with your mentor
                    </div>
                </li>
            </ul>
            </div>
       </section>
	        <!---get-mentor--->
                <?php $this->load->view('mentorship/getMentorForm');?>
                <!--end-get-mentor-->
                <?php $this->load->view('mentorship/mentorList'); ?>
		<?php if(isset($isMentee) && $isMentee < 1){?>
			<a id="bottom_navigation_bar" class="fixed-mentor-btn" href="javascript:void(0);" onclick="goToForm('<?php echo $bottomTrackingPageKeyId;?>'); trackEventByGAMobile('MOBILE_GET_A_MENTOR_FROM_<?php echo strtoupper($boomr_pageid);?>_STICKY');">GET A MENTOR</a>
		<?php }?>
                <?php $this->load->view('mcommon5/footerLinks'); ?>
                </div>
        </div>
</div>
<div id="popupBasicBack" data-enhance='false'></div>

<?php $this->load->view('mcommon5/footer'); ?>

<!-- location layer-->
<div data-role="page" id="locationDiv" data-enhance="false" class="of-hide">
      <?php echo Modules::run('mCampusAmbassador5/MentorController/prepareLocationLayer');?>
</div>

<script>
$(document).ready(function(){
	if(getCookie('openMenteeLayer') == 1){
		setCookie('openMenteeLayer','',0,'seconds');
		successLayer();
	}
	
	$("#menteeExamList :input:checkbox").removeAttr('checked');
	$('.pcl').val('');
});

$(window).load(function(){
    if(typeof(document.URL.split("?")) != 'undefined')
    {
        if(document.URL.split("?").length > 1){
            if(typeof(document.URL.split("?")[1].split("for_focus")[1])!='undefined'){
                goToForm();
            }
        }
    }
})

function successLayer() {
	divIdPosition= $('#mainImage');  
	openConfirmShortlistCompareLayer('A mentor will be assigned to you based on your submitted preferences in 3-5 business days.<br> In the meantime, you can explore: <ul><li><a href="<?php echo SHIKSHA_HOME.'/top-engineering-colleges-in-india-rankingpage-44-2-0-0-0';?>">Top Engineering Colleges</a></li><li><a href="<?php echo SHIKSHA_HOME.'/blogs/shikshaBlog/showArticlesList?category=56';?>">News & Articles about Engineering</a></li><li><a href="<?php echo SHIKSHA_HOME.'/jee-mains-college-predictor';?>">JEE Main College Predictor</a></li></ul>', 'Thank you !', divIdPosition, 'OK', window.goHomePage);
	$('#cmpMCloseBtn').attr('onclick','goHomePage()');
}

function goHomePage() {
	window.location.href = '<?php echo SHIKSHA_HOME;?>';
}

// this function is used for mozial

function getoTopLocation() {
   setTimeout(function(){$('html, body').animate({ scrollTop: 0}, 'fast');},500);
}
</script>
<?php ob_end_flush(); ?>
