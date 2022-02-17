<?php ob_start('compress');
//Since, this is a single page application, Cookies were not getting saved when used pressed Back from any page.
//To avoid this, we are making this page as no-cache
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.
$headerComponent = array('mobilecss'  => array('mentorship-mobile'),
                         'pageName'   => $boomr_pageid
                    );

$this->load->view('mcommon5/header',$headerComponent);
?>
<script src="/public/mobile5/js/<?php echo getJSWithVersion('mentorship','nationalMobile');?>"></script>
<div id="wrapper" style="background:#e5e5da" data-role="page" class="of-hide">
    <?php
    echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
    echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
    ?>
    <?php $this->load->view('mentorship/header'); ?>
    <div data-role="content" style="background:#e6e6dc !important;">
        <?php 
            $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
        ?>
		<!-- Mentor Widget - start -->
		<div data-enhance="false">
            <?php echo modules::run('mCampusAmbassador5/MenteeChatDashboard/mentorDetail',$mentorId); ?>
        </div>    
        <!-- Mentor WIdget - end -->
		<!-- Mentee Chat Section - start -->
		<div>
            <?php echo modules::run('mCampusAmbassador5/MenteeChatDashboard/menteeChatWidget',$mentorId,$slotData,$scheduleData, $chatId); ?>
		</div>       
		<!-- Mentee Chat Section - end -->
		<!--Chat History - start -->
		<div data-enhance="false">
            <?php echo modules::run('mCampusAmbassador5/MenteeChatDashboard/chatHistoryWidget', $mentorId); ?>
        </div>
        <div data-enhance="false">   
			<!--Chat History Section - end -->
		    <?php $this->load->view('/mcommon5/footerLinks'); ?>
		</div>
	
    </div><!-- end of content div -->
</div><!-- end of wrapper div -->
<div data-role="page" data-enhance="false" id="viewMentorshipChatByMentee" style="background-color: #E6E6DC">
    <header>
	<div class="layer-header">
            <a style="width:10px;" class="back-box" href="javascript:void(0);" data-rel="back"><i class="msprite back-icn"></i></a> 
            <p style="text-align:left; font-size:12px;">Chat History : <span id="layerSlotTimeStr"></span></p>
        </div>
    </header>
    <section class="mentor-widget-box" style="padding: 0px 10px 0 0; margin: 10px; font-size: 14px; word-wrap: break-word;">
	<div>
	    <textarea id="mentorshipChatContainer" class="mentor-chat-area" rows="20">
		
	    </textarea>
	</div>
    </section>
</div>
<?php ob_end_flush(); ?>
<?php $this->load->view('/mcommon5/footer'); ?>