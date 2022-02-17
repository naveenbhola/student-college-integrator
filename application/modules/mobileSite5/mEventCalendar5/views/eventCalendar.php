<?php ob_start('compress');
//Since, this is a single page application, Cookies were not getting saved when used pressed Back from any page.
//To avoid this, we are making this page as no-cache
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.
$shareUrl = $examFilter['canonicalUrl'];
$jqueryUIRequired = TRUE;
?>
<?php $this->load->view('/mcommon5/header', array('jqueryUIRequired' => $jqueryUIRequired));?>
<script>
    var eventExamId = new Array();
</script>
<link rel="stylesheet" href="/public/mobile5/css/<?php echo getCSSWithVersion('calendar-mob','nationalMobile'); ?>" >
<div id="wrapper" data-role="page" class="of-hide" style="min-height: 413px;padding-top: 40px !important;">
    <?php
    echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
    echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
    $this->load->view('eventCalendarHeader');
    ?>
    <?php $this->load->view('shareCalendar', array('shareUrl'=>$shareUrl)); ?>
    <div data-role="content" style="background:#e6e6dc !important;">
    <?php 
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
	?>
	<div id="background-black-layer"></div>
	<div data-enhance="false" id="eventListing">
	    <?php $this->load->view('eventListing');?>
	</div>
	<div data-enhance="false">
	    <?php $this->load->view('/mcommon5/footerLinks'); ?>
	</div>
	<!-- Success popup - begin -->
	<a href="#popupBasic3" data-position-to="window" data-inline="true" data-rel="popup" id="alertSuccessPopup" data-transition="pop" ></a>
	<div data-role="popup" id="popupBasic3" data-theme="d" style="background:#EFEFEF;">
	    <div style="padding: 25px; font-size:1.1em;color: #828282;display: block !important;" data-theme="d">
		<p>Your exam alerts for <?php echo $examFilter['examCalendarTitle']?> are saved.<p>
	    </div>
	    <div style="padding-left: 25px; padding-bottom: 12px; font-size:1.0em; text-align: center;">
		<a style="width:70px;" data-theme="d" href="javascript:void(0)" data-rel="back" data-role="button" data-icon="delete" data-iconpos="Close" ><strong>&nbsp;&nbsp;Close</strong></a>
	    </div>
	</div>
	<!-- Success popup - end -->
	<!-- Success popup - begin -->
	<a href="#popupBasic4" data-position-to="window" data-inline="true" data-rel="popup" id="addEventSuccessPopup" data-transition="pop" ></a>
	<div data-role="popup" id="popupBasic4" data-theme="d" style="background:#EFEFEF;">
	    <div style="padding: 25px; font-size:1.1em;color: #828282;display: block !important;" data-theme="d">
		<p id="addEventMessageBox"><p>
	    </div>
	    <div style="padding-left: 25px; padding-bottom: 12px; font-size:1.0em; text-align: center;">
		<a style="width:70px;" data-theme="d" href="javascript:void(0)" data-rel="back" data-role="button" data-icon="delete" data-iconpos="Close" ><strong>&nbsp;&nbsp;Close</strong></a>
	    </div>
	</div>
	<!-- Success popup - end -->
    </div><!-- end of content div -->
</div><!-- end of wrapper div -->
<?php
if(isset($_COOKIE['EC_CM']) && $_COOKIE['EC_CM'] == 1) $this->load->view('coachMarks');
?>
<div data-role="page" data-enhance="false" id="eventFilter">
    <?php $this->load->view('eventFilter');?>
	</div>
<?php $this->load->view('eventReminderLayer'); ?>
<?php $this->load->view('eventSubscriptionLayer'); ?>
<?php $this->load->view('addNewEventLayer'); ?>
<?php
$alertSuccessPopup    = $_COOKIE['eventCalendarSuccessPopup'];
$addEventSuccessPopup = $_COOKIE['mobile_EC_event_action'];
setcookie('eventCalendarSuccessPopup','',time()-3600,'/',COOKIEDOMAIN);
setcookie('mobile_EC_event_action','',time()-3600,'/',COOKIEDOMAIN);
?>
<script src="/public/mobile5/js/<?php echo getJSWithVersion('eventCalendarNM','nationalMobile');?>"></script>
<script>
    var examCalendarTitle = '<?php echo $examFilter['examCalendarTitle']?>';
    var userSetReminders = JSON.parse('<?php echo $userSetRemindersJson?>');
    var subscribedExamCount = '<?php echo count($userSubscribedExams)?>';
    var alertSuccessPopup = '<?php echo $alertSuccessPopup?>';
    var addEventSuccessPopup = '<?php echo $addEventSuccessPopup?>';
    var coachMark = '<?php echo $_COOKIE['EC_CM']?>';
    var dates = ['03/12/2015', '04/29/2015', '04/30/2015', '05/03/2015'];
    $(window).load(function(){
	if (coachMark == 0) {
	    setTimeout(
		function(){
		scrollToClosestDate(alertSuccessPopup, addEventSuccessPopup);
	    },500);
	} else if(coachMark == 1) {
	    $('#wrapper').height($('.cale-tour').height());
	}
	$( "#datepickerFrom" ).datepicker({
	    dateFormat: 'dd/mm/yy',
	    minDate: "-4m",
	    maxDate: "+7m",
	    onSelect: onStartDateSelectionFromAddEvent,
	    showOtherMonths: true,
	    numberOfMonths: 1
	});
	$( "#datepickerTo" ).datepicker({
	    dateFormat: 'dd/mm/yy',
	    minDate: "-4m",
	    maxDate: "+7m",
	    onSelect: onEndDateSelectionFromAddEvent,
	    showOtherMonths: true,
	    numberOfMonths: 1
	});
	if($('#popupBasic3 span, #popupBasic4 span').hasClass('ui-icon'))
	{
	    $('#popupBasic3 span, #popupBasic4 span').removeClass('ui-icon');
	}
    });
</script>
<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"> -->
<?php
global $shiksha_site_current_url;
global $shiksha_site_current_refferal;
setcookie('EC_CM', 0, time()+(30*24*3600), '/', COOKIEDOMAIN);
$_COOKIE['EC_CM'] = 0;
?>
<div id="popupBasicBack" data-enhance='false'></div>
<?php $this->load->view('/mcommon5/footer');?>
<?php ob_end_flush(); ?>
<script>
$(document).click(function (e)
{
	var container = $('#calendarToSelectDate');
	var container1 = $('.cl-filtr3');
	$('.ui-datepicker-inline').css({'width':'100%'});
	if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0 && !container1.is(e.target) && container1.has(e.target).length === 0 && e.target.className!='ui-icon ui-icon-circle-triangle-e' && e.target.className!='ui-icon ui-icon-circle-triangle-w' && e.target.className!='ui-datepicker-next ui-corner-all ui-state-hover ui-datepicker-next-hover' && e.target.className!='ui-datepicker-prev ui-corner-all ui-state-hover ui-datepicker-prev-hover') // ... nor a descendant of the container
	{
		container.hide();
                calendarStaus = 'hide';
                $('#background-black-layer').removeClass('background-black-layer');
                $('#showCalendar').removeClass('actFiltrNav');
   	}
	
	var container2 = $('#shareBox');
	var container3 = $('.cale-widg.cale-widg1');
	if (!container2.is(e.target) && container2.has(e.target).length === 0 && !container3.is(e.target) && container3.has(e.target).length === 0) {
	    $('.ui-panel-wrapper').css('z-index',999);
	    $('#shareBoxAndOverlay').hide();
	}

});
</script>
