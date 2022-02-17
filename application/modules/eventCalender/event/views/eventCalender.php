<?php
$tempJsArray = array('myShiksha','user');
$headerComponents = array(
                'css'   =>      array('fullcalendar','calender_view'),
                'js' =>         array('moment.min','common','ajax-api','json2','imageUpload','eventCalender','CalendarPopup','userRegistration'),
                'jsFooter'=>    $tempJsArray,
                'title' =>      $title,
                'metaDescription' => $metaDescription,
                'canonicalURL' => $canonicalURL,
                'product'       =>'eventCalendar',
                'showBottomMargin' => false,
                'displayname'=>(isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
);

$headerComponents['bannerPropertiesGutter'] = array();
$headerComponents['shikshaCriteria'] = array();

global $isNewExamPage;
$isNewExamPage = true;

$this->load->view('common/header', $headerComponents);
?>

<div id="top-nav" style="visibility:hidden;height:0px"></div>
<?php
$this->load->view( 'messageBoard/headerPanelForAnA',array('collegePredictor' => true) );
$eventSubscriptionSuccess = $_COOKIE['eventSubscriptionSuccess'];
$eventReminderSuccess     = $_COOKIE['eventReminderSuccess'];
unset($_COOKIE['eventSubscriptionSuccess']);
?>
<div id="content-wrapper" style="padding-left: 0px;">
	<div class="wrapperFxd">
    	<div id="content-child-wrap p0">
			<div id="management-wrapper p0">
                
                <!--Course Content starts here-->
                <div id="management-content-wrap">
                    <!--Course Left Col Starts here-->
                    <div id="management-left">
                        <div class="calender-vw">
                            <div class="cal-head">
                                <div class="head-g1">
				<?php $currentYear=date("Y");?>
				<div class="social-cont" style="margin-bottom:10px;">
                        <div class="socila-icons">
                        <table cellpadding="0" cellspacing="0" border="0">
        <tr>
        <td>
<?php
	$url_parts = parse_url("https://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
	$constructed_url = $url_parts['scheme'].'://' . $url_parts['host'] . (isset($url_parts['path'])?$url_parts['path']:'');
	$urlCalendar = urlencode($constructed_url);
?>

                                        <div class="flLt">
                                            <iframe src="https://www.facebook.com/plugins/like.php?href=<?php echo $urlCalendar?>&amp;layout=button_count&amp;show_faces=false&amp;width=450&amp;action=like&amp;font=tahoma&amp;stream=true&amp;header=true&amp;appId=<?php echo FACEBOOK_API_ID; ?>" colorscheme="light" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:81px; height:25px"></iframe>
                                        </div>
					<div class="flLt">
                                            <iframe id="twitterFrame" allowtransparency="true" frameborder="0" scrolling="no"  src="about:blank" style="width:82px; height:20px;"></iframe>
                                        </div>
				</td></tr>
			</table>
		</div></div>
                                    <h1><?php echo $examFilter['examCalendarTitle'];?> Entrance Exam Calendar</h1>
                                    <ul class="cal-grp1">
                                        <!--<li><a class="cal-grybtn"><i class="calsprite ic-setting"></i>Customize</a></li>-->
                                        <li><a class="cal-grybtn" href="javascript:void(0);" onclick="loadSubscriptionLayer();trackEventByGA('SET_ALERT_CLICK','EXAM_CALENDAR_SET_ALERTS_<?php echo $examFilter['examCalendarTitle'];?>');"><i class="calsprite ic-alarm"></i>Set Alerts</a></li>
                                        <!--<li><a class="cal-plaingry"><i class="calsprite ic-google-calender"></i>Add to google calender</a></li>-->
                                    </ul>
                                    <p class="clr"></p>
                                  
                                </div>
                                  <div class="cal-grp2">
                                        <div class="cal-grp2L">
                                             <div class="cal-dropdown2">
                                                <i class="calsprite ic-downarw easeall3s"></i>
                                                <span id="_selectedExam">All Exams</span>
                                                <ul id="examList"> 
                                                    <?php if(count($examNameList)>0){foreach($examNameList as $key=>$examTitle){?>
						    <li onclick="trackEventByGA('EXAM_FILTER_CLICK','EXAM_CALENDAR_EXAMFILTER_<?php echo $examFilter['examCalendarTitle'];?>');"><a href="javascript:void(0);" title="<?php echo $examTitle;?>">
                                                            <div class="cal-checkBx" refId="<?php echo $key;?>">
								<input type="checkbox" id="<?php echo $key;?>"  name="exam[]" value="<?php echo $key;?>" class="c-input"/><label for="<?php echo $key;?>" class="c-heck"><i class="calsprite ic-checkdisable"></i><?php echo $examTitle;?></label></div>
                                                        </a></li>
						    <?php }}?>
                                                </ul>
                                            </div>
                                            
                                        </div>
                                        <div class="cal-grp2R">
                                            <h2 class="txtTyp1">View : </h2>
                                            <div class="cal-dropdown3">
                                                <ul class="btngrp-1">
                                                    <li><a class="actveheadbtn">Month</a></li>
                                                    <li><a>Year</a></li>
                                                </ul>
                                                <ul class="btngrp-2">
                                                    <li><a class="actveheadbtn"><i class="calsprite  ic-calender-view"></i></a></li>
                                                    <li><a onclick="showListView('cal-eventList');trackEventByGA('LIST_VIEW_CLICK',' EXAM_CALENDAR_LISTVIEW_<?php echo $examFilter['examCalendarTitle'];?>');"><i class="calsprite ic-list-view"></i></a></li>
                                                </ul>
                                            </div>
                                        </div><p class="clr"></p>
                                    </div>
                            </div>
                             <p class="clr"></p>
                          
                    <!--Course Left Col Ends here-->
                                     
                    <div class="cal-content">
<div id="calendarParent"><div id='calendar'>
		
				<div id='loaderDiv' style='height: 739px; text-align: center;'>
						<img style='padding-top: 215px;' src='/public/images/loader.gif' border=0></img>
				</div>
		
</div></div>
<ul class="calenderLR">
                                    <li onclick="$j('.fc-prev-button').trigger('click');closeToolTipEventCalendar();closeEventListofCalendar();"><a><i class="calsprite ic-arwLeft2"></i></a></li>
                                    <li onclick="$j('.fc-next-button').trigger('click');closeToolTipEventCalendar();closeEventListofCalendar();"><a><i class="calsprite ic-arwRight2"></i></a></li>
                                </ul></div>
                  
		  
		   <!---pop up date event list-->
                             <div class="cal-popup evntLstPopUp" style="display:none;z-index:10;" id="popUpDateEventList">
                                
                             </div>
                             <!---pop up date event list ends-->

		   
			<!--pop up custom event with delete/edit-->
                                <div class="tooltipp rghtTooltp tooltip-v1 toltp3" style="display:none;z-index:10;" id="eventContent">
                                    <div class="toltp3-inner">
					<a class="closePopUpEv" onclick="closeToolTipEventCalendar()"><i class="calsprite ic-close"></i></a>
					<p class="tol-h"><b id="dateToolTipTitle"></b> - Event</p>
					<h2 class="tol-head" id="detailToolTipTitle"></h2>
					<p class="tol-desc" id="eventInfo"></p>
					<p id="articleInfo"></p>
					<div id="reminder-container" style="display: none;" class="tol-remind"></div>
										</div>
										<div class="remind-FotBtns" id="sign-in-for-reminder" style="display: none;">
                                        <a class="remind-del" onclick="userSignInForReminder();">Existing users, Login</a>
                                        <a class="remind-gryBtn" onclick="userSignUpForReminder('<?php echo $reminderTrackingPageKeyId;?>');">Sign Up to Save</a>
                                        <p class="clr"></p>
                                    </div>
                                         <div class="remind-FotBtns" id="deleteEditBtn" style="display: none;">
                                            <a class="remind-del" id="deleteEvent">Delete Event</a>
                                            <a class="remind-gryBtn" id="editEvent">Edit Event</a>
                                             <p class="clr"></p>
                                        
                                            </div>
                                    </div>
                                    <!---pop up custom event with delete/edit ends-->					
										<div class="cal-popup ptyp1 ptyp3 ptyp4" id="deleteConfirmBox" style="display: none;">
                                                         <h2>Delete<a class="closePopUpEv2" id="deleteCloseBtn"><i class="calsprite ic-close2x"></i></a></h2>
                                                         <div class="cal-popup-cont">
                                                                 <p style="margin-bottom:0px;">Do you really want to delete this event? </p>
                                                         </div>
                                                         <div class="popup-fotr">
                                                                         <span><a class="cal-grybtnDrk" id="deleteCancelBtn">Cancel</a>
                                                                         <a class="cal-bluebtn" id="deleteYesBtn">Yes</a></span>
                                                                 </div>
                                                 </div>

                    <?php $this->load->view('eventListView');?>
                    <!--Course Right Col Starts here-->
                    <div id="management-right">

                    </div>
                    <!--Course Right Col Ends here-->
                    <p class="clr"></p>

               </div>
               <!--Course content ends here-->
            </div>
               
       
        
        </div>
    </div>
</div>
<div id="popupovrlay"></div>		
 <!--   google claneder popup -->
 <form id="addEventForm" action="/event/EventController/addEvent" accept-charset="utf-8" method="post" novalidate="novalidate">
       <input type="hidden" name="userId" id="userId" value="<?php echo $userId;?>" />
	   <input type="hidden" name="customEventId" id="customEventId" value="" />
       <input type="hidden" name="streamId" id="streamId" value="<?php echo $examFilter['streamId'];?>" />
       <input type="hidden" name="courseId" id="courseId" value="<?php echo $examFilter['courseId'];?>" />
       <input type="hidden" name="educationTypeId" id="educationTypeId" value="<?php echo $examFilter['educationTypeId'];?>" />
       <input type="hidden" name="tracking_keyid" id="tracking_keyid" value="<?php echo $eventTrackingPageKeyId;?>">
       <div class="cal-popup ptyp1 ptyp3 ptyp5 clear-width" id="addEventLayer" style="display: none;">
		
           <h2><span id="layerHeading"></span><a class="closePopUpEv2" onclick="eventLayer('hide','Add Event');"><i class="calsprite ic-close2x"></i></a></h2>
		   <?php $this->load->view('common/calendardiv'); ?>
           <div class="cal-popup-cont">
		       
               <div class="cal-frmfeild" style="margin-bottom:10px;position: relative;">
                   <label class="cal-form-labl flLt">Start Date</label>
				   <div class="form-field-detail" style="position:absolute">
				   <input type="text" value="dd/mm/yyyy" class="calenderFields cal-from-input inp-1"  name="eventStartDate" id="eventStartDate"  readonly  style="max-width:105px !important"/>
				   <a href="#" class="app-sprite-2 pickDate" title="Calendar" name="from_date_main_img" id="from_date_main_img"  src="/public/images/calender.jpg" style="cursor:pointer" align="top" onClick="calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('eventStartDate'),'from_date_main_img','dd/mm/yyyy');setCalPostion('from_date_main_img');return false;">&nbsp;</a>
				   <div style="display:none;" id="date"><div class="errorMsg" id="startdate_error" style="*float:left; margin-bottom:4px;"></div></div>
				   </div>
				   <div class="clearFix"></div>
               </div>
               <div class="cal-frmfeild" style="margin-bottom:10px;float:left;margin-top:10px;width:100%">
                   <label class="cal-form-labl flLt">End Date</label>
				   <div class="form-field-detail" style="position:absolute">
				   <input type="text" value="dd/mm/yyyy" class="calenderFields cal-from-input inp-1"  name="eventEndDate" id="eventEndDate"  readonly  style="max-width:105px !important"/>
				   <a href="#" class="app-sprite-2 pickDate" title="Calendar" name="from_date_main_img_1" id="from_date_main_img_1"  src="/public/images/calender.jpg" style="cursor:pointer" align="top" onClick="calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('eventEndDate'),'from_date_main_img_1','dd/mm/yyyy');setCalPostion('from_date_main_img_1'); return false;">&nbsp;</a>
				   <div style="display:none;" id="date"><div class="errorMsg" id="enddate_error" style="*float:left; margin-bottom:4px;"></div></div>
				   </div>
				   <div class="clearFix"></div>
               </div>			   
               <div class="cal-frmfeild" style="float:left;margin-top:10px;width:100%">
                   <label class="cal-form-labl flLt">Label</label>
				   <div class="form-field-detail">
                   <input type="text" value="" class="cal-from-input inp-2"  minlength="1" maxlength="200" required="true" validate="validateStr" caption="Label" id="label" name="eventTitle"/>
				   <div style="display:none;" id="loc_main"><div class="errorMsg" id="label_error" style="*float:left"></div></div>  
				   </div>
				   <div class="clearFix"></div>
				 </div>
               <div class="cal-frmfeild">
                   <label class="cal-form-labl flLt">Description</label>
				   <div class="form-field-detail">
                   <textarea class="inp-3" required="true"  minlength="1" maxlength="200" validate="validateStr" caption="Description" id="description" name="eventDescription"></textarea>
				   <div style="display:none;" id="loc_main"><div class="errorMsg" id="description_error" style="*float:left"></div></div>  
				   </div>
				   <div class="clearFix"></div>
				   </div>
               <!--<div class="cal-frmfeild">
                   <label class="cal-form-labl flLt">Note</label>
                   <input type="text" value="" class="cal-from-input inp-4" />
               </div>-->
				    <div class="tol-remind" id="remind-container-for-addEvent">
                                        <b>SMS reminder</b>
                                        <span class="remind-date">on<span id="">
						<select name="reminder_date_on_addEvent" id="reminder_date_on_addEvent">
							<option value="">Choose a date</option>
						</select>
					</span></span>
                                        <span class="remind-time"></span>
                                        <span class="remind-check iphoneicnOff" id="iphoneicnOnOffToggle">
                                            <p class="firstchild">ON</p>
                                            <p class="lastchild">OFF</p>
                                            <i class="iphoneicn"></i>
                                            <input type="checkbox" checked="" id="ioscheckbx">
                                        </span>
                        <input type="hidden" name="remindertracking_keyid" id="remindertracking_keyid" value="<?php echo $reminderTrackingPageKeyId;?>">
                                    </div>
           </div> 
           <div class="popup-fotr">
                   <span><a class="cal-grybtnDrk" href="javascript:void(0);" id="cancelBtn">Cancel</a>
                   <a class="cal-bluebtn" onclick="var validatationResult = validateForm();if(validateFields($('addEventForm')) && validatationResult){ addEvent($('addEventForm'));}else{ return false;}" id="buttonText"></a></span>
				   <a class="cal-bluebtn" onclick="return false" id="dummyButton" style="display: none;"></a></span>
               </div>  
       </div>
</form>	   
      <!--   google claneder popup ends -->
<div class="cal-popup ptyp1 ptyp8 eventSubscriptionSuccessLayer" style="display: none;">
   <h2><span>Success!</span><a href="javascript:void(0);" onclick="hideSubscriptionLayer();" class="closePopUpEv2"><i class="calsprite ic-close2x"></i></a></h2>
   <div class="cal-popup-cont">
       <p>Your exam alerts for <span>exams</span> are saved.</p>
   </div> 
   <div class="popup-fotr">
	<span><a class="cal-grybtnDrk" href="javascript:void(0);" onclick="hideSubscriptionLayer();">Ok</a></span>
   </div>  
</div>
<div class="cal-popup ptyp1 ptyp8 eventReminderSuccessLayer" style="display: none;">
   <h2><span>Success!</span><a href="javascript:void(0);" onclick="hideSubscriptionLayer();" class="closePopUpEv2"><i class="calsprite ic-close2x"></i></a></h2>
   <div class="cal-popup-cont">
       <p>Your reminder has been set successfully.</p>
   </div> 
   <div class="popup-fotr">
	<span><a class="cal-grybtnDrk" href="javascript:void(0);" onclick="hideSubscriptionLayer();">Ok</a></span>
   </div>  
</div>
<div class="cal-popup ptyp1 ptyp8 sorryLayer" style="display: none;">
   <h2><span id="failedTitle"></span><a href="javascript:void(0);" onclick="hideSubscriptionLayer();" class="closePopUpEv2"><i class="calsprite ic-close2x"></i></a></h2>
   <div class="cal-popup-cont">
       <p id="failedMsg"></p>
   </div>
   <div class="popup-fotr">
        <span><a class="cal-grybtnDrk" href="javascript:void(0);" onclick="hideSubscriptionLayer();">Ok</a></span>
   </div>
</div>
<?php $this->load->view('eventSubscribePopup'); ?>

<?php $this->load->view('common/footer'); ?>
<script>
$j(document).ready(function(){
                                                                    $j('ul.evntLst li').after().click(function(){
										if ($j(this).hasClass('evntlsthovrd')) {

										}
										else{
											$j('ul.evntLst li').removeClass('evntlsthovrd');
											$j(this).toggleClass('evntlsthovrd');
										}
									});
                                  });		
</script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("fullcalendar.min"); ?>"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
<script>
    var setDefalutDate = $j.now();  // set defalut date for calendar
    var eventInformation = '<?php echo $data;?>';
    var examCalendarTitle = '<?php echo $examFilter['examCalendarTitle'];?>';
    var eventObj = $j.parseJSON(eventInformation);
    var userSubscribedExams = $j.parseJSON('<?php echo $userSubscribedExamsJson;?>');
    var userSetReminders = $j.parseJSON('<?php echo $userSetRemindersJson;?>');
    var logged_in_user_id = '<?php echo (isset($validateuser[0]['userid'])?$validateuser[0]['userid']:"")?>';
    
    drawEventCalender();
	
	// exam filter 
	$j(document).ready( function (){
		$j("#examList li>a>div").on('click',function(event) {
			filterEventByExam($j(this).attr('refId'));
			event.stopPropagation();event.preventDefault();
		});
		$j('#subscribe_streams').on('click', 'li a', function(){
			$j('#subscribe_stream').html($j(this).html());
			$j('input[name="subscribe_stream"]').val($j(this).html());
		});
		$j('#subscribe-exam-list li>a>div').on('click', function(event){
			if ($j(this).attr('refId') == 'subscribe-all-exams') {
				selectAllExamsCheckbox();
				event.stopPropagation();event.preventDefault();
			} else {
				selectExamCheckbox($j(this).attr('refId'));
				event.stopPropagation();event.preventDefault();
			}
		});
		
		$j('#eventStartDate').on('focusout', function(){
			populateRemindDateDropDown($j(this).val()); 
		});
		$j('#reminder_date_on_addEvent').on('change', function(){
			if ($j(this).val() == '') {
				$j('#iphoneicnOnOffToggle').addClass('iphoneicnOff');
			} else {
				$j('#iphoneicnOnOffToggle').removeClass('iphoneicnOff');
			}
		});
		
		$j('#reminder-container').on('click', '.remind-check', function(){
		    if (isUserLoggedIn == true) {
			if($j(this).hasClass('iphoneicnOff')) {
				$j(this).removeClass('iphoneicnOff')
				addRemoveUserReminder('add',"<?php echo $reminderEventLayerTrackingPageKeyId;?>");
			} else {
				$j(this).addClass('iphoneicnOff')
				addRemoveUserReminder('delete',"<?php echo $reminderEventLayerTrackingPageKeyId;?>");
			}
		    } else {
			$j('#sign-in-for-reminder').slideDown('slow');
		    }
		});
	});
	
	//hide tooltip container when clicked outside
	$j(document).click(function (e)
	{
	    var container = $j("#eventContent");
	    var container2 = $j(".fc-title");
	    if (!container.is(e.target) && container.has(e.target).length === 0 && !container2.is(e.target) && container2.has(e.target).length === 0)
	    { 
		if ($j('#eventContent').length>0) {
			container.hide();
		}
	    }
	    
	});	
	$j('.fc-toolbar').append('<div class="cal-widget1"><a class="cal-bluebtn calbt1" onclick="eventLayer(\'show\',\'Add Event\');trackEventByGA(\'ADD_EVENT_CLICK\',\'EXAM_CALENDAR_ADD_EVENT_<?php echo $examFilter['examCalendarTitle'];?>\');">+  Add Event</a></div>');
	try{
		    addOnBlurValidate(document.getElementById('addEventForm'));
		}
		catch (ex) {
    
		}
</script>
<?php
if($eventSubscriptionSuccess == 'success')
{
	setcookie('eventSubscriptionSuccess','',time()-3600,'/',COOKIEDOMAIN);
?>
<script>
	showSuccessMsgPopup('<?php echo $examFilter['examCalendarTitle'];?>');
</script>
<?php
}
if($eventReminderSuccess == 'success')
{
	setcookie('eventReminderSuccess','',time()-3600,'/',COOKIEDOMAIN);
?>
<script>
	showReminderSuccessMsgPopup();
</script>
<?php
}
?>
<script>
var userStatus = getCookie('user');		
currentPageName = 'Event Calendar';
if(userStatus!='' && getCookie('addEventAlert')>0){
		$j('#popupovrlay').addClass('popupovrlay');             
		$j('.sorryLayer').show();
		$j('#failedTitle').html('Error!');
		
		if (getCookie('addEventAlert') == 3) {
			$j('#failedMsg').html('Reminder creation failed! Please try again.');
		} else if(getCookie('addEventAlert') == 2) {
			$j('#failedMsg').html('Alert creation failed! Please try again.');
		}else if(getCookie('addEventAlert') == 1) {
			$j('#failedMsg').html('Event creation failed! Please try again.');
		}
		setCookie('addEventAlert' ,'0',0 ,'/',COOKIEDOMAIN);
}
</script>
