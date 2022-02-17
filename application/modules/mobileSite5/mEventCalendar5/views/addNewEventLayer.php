<div id="userAddEvent_layerContainer" data-enhance="false"  data-role="page" style="background-color: #fff;">
    <form action="/mEventCalendar5/EventCalendarController/addEventOnCalendar" novalidate="novalidate" method="post" id="userAddEvent_dataForm" autocomplete="off">
    <header class="cale-header2">
        <a href="javascript:void(0);" id="userAddEvent_backButton" class="cal-back-btn" data-rel="back"><i class="cale-sprite cl-cancel"></i></a>
        <h2 class="head-cal-txt">New Event</h2>
        <a href="javascript:void(0);" onclick="addCustomEvent('userAddEvent_dataForm');" class="head-cal-done"><i class="cale-sprite cl-tick"></i></a>
    </header>
    <section class="bgColrWhte">
        <div class="adEv-frmFeld">
	<div id="addEventErrMsg" style="display:none;" class="addEventErrorMsg">There’s no event listed for this day. Would you like to add an event?</div>
            <div class="adEv-frmF adEv-date">
		<label class="userLabel" id="datepickerFromDiv" for="datepickerFrom" onclick="$('#datepickerFrom').datepicker('show');">Start date (dd/mm/yyyy)</label>
                <input id="datepickerFrom" maxlength="10" minlength="10" type="text" name="eventStartDate" validate="validateStr" required="true" placeholder="Start date (dd/mm/yyyy)" caption="start date" />
                <a class="shwPickDte" href="javascript:void(0);" onclick="/*$('#datepickerFrom').focus();*/$('#datepickerFrom').datepicker('show');"><i class="cale-sprite cl-calendarGry"></i></a>
            </div>
	    <div style="display:none;"><div class="errorMsg" id="datepickerFrom_error"></div></div>
            <div class="adEv-frmF adEv-date">
		<label class="userLabel" id="datepickerToDiv" for="datepickerTo" onclick="$('#datepickerTo').datepicker('show');">Start date (dd/mm/yyyy)</label>
                <input id="datepickerTo" type="text" maxlength="10" minlength="10" name="eventEndDate" validate="validateStr" required="true" placeholder="End date (dd/mm/yyyy)" caption="end date" />
                <a class="shwPickDte" href="javascript:void(0);" onclick="/*$('#datepickerTo').focus();*/$('#datepickerTo').datepicker('show');"><i class="cale-sprite cl-calendarGry"></i></a>
            </div>
	    <div style="display:none;"><div class="errorMsg" id="datepickerTo_error"></div></div>
            <div class="adEv-frmF adEv-label">
                <input type="text" id="userAddEvent_eventTitle" maxlength="200" minlength="1" validate="validateStr" required="true" name="eventTitle" placeholder="Event label" caption="event label" />
            </div>
	    <div style="display:none;"><div class="errorMsg"  id="userAddEvent_eventTitle_error"></div></div>
            <div class="adEv-frmF adEv-desc">
                <input type="text" name="eventDescription" validate="validateStr" maxlength="200" minlength="1" required="true" placeholder="Event description" id="userAddEvent_eventDescription" caption="event description" />
		<input type="hidden" name="userId" value="<?php echo $userId?>" id="userAddEvent_userId" />
		<input type="hidden" name="customEventId" value="0" id="userAddEvent_eventId" />
		<input type="hidden" name="iosIconSts" value="off" id="userAddEvent_iosIcon" />
        <input type="hidden" name="streamId" id="userAddEvent_streamId" value="<?php echo $examFilter['streamId'];?>" />
        <input type="hidden" name="courseId" id="userAddEvent_courseId" value="<?php echo $examFilter['courseId'];?>" />
        <input type="hidden" name="educationTypeId" id="userAddEvent_educationTypeId" value="<?php echo $examFilter['educationTypeId'];
        ?>" />
        <input type="hidden" name="examCalendarTitle" id="userAddEvent_examCalendarTitle" value="<?php echo $examFilter['examCalendarTitle'];?>" />
        <input type="hidden" name="tracking_keyid" id="tracking_keyid" value='<?php echo $eventtrackingPageKeyId;?>'>
        <input type="hidden" name="remindertracking_keyid" id="remindertracking_keyid" value='<?php echo $remindertrackingPageKeyId;?>'>
            </div>
	    <div style="display:none;"><div class="errorMsg" id="userAddEvent_eventDescription_error"></div></div>
        </div>
    </section>    
    <section class="bgColrWhte setRemdPange adEv-setRemd">
       <p class="txtRemndPge"><i class="cale-sprite cl-alarm4"></i>Remind me by SMS</p>
       <span id="reminderSetIcon_event" class="remind-check iphoneicnOff" onclick="setReminderStatus('event');">
                                            <p class="firstchild">ON</p>
                                            <p class="lastchild">OFF</p>
                                            <i class="iphoneicn"></i>
                                            <input type="checkbox" checked="" id="ioscheckbx">
                                        </span>
                                        <p class="clr"></p> 
        <div class="adEv-adRemDate EnableRemind"><!--add remove class [EnableRemind]-->
            <span class="cale-cusDrpDwn"><p>on</p>
		<div class="drpDwnBx selectActv">
		    <select onchange="toggleReminderSetIcon(this, 'event');" name="reminder_date_on_addEvent" style="color:#666;">
			<option value="">Choose a date</option>
		    </select>
		</div>
	    </span>
        </div>
    </section>
    </form>
    <form style="display: none;" id="addEventFormEC" action="<?php echo SHIKSHA_HOME?>/muser5/MobileUser/register" method="post">
	<input type="hidden" value="<?php echo base64_encode(SHIKSHA_HOME.'/mEventCalendar5/EventCalendarController/loadEventCalendar');?>" name="current_url">
	<input type="hidden" value="addEventFromEventCalendar" name="from_where">
    <input type="hidden" name="tracking_keyid" id="tracking_keyid" value='<?php echo $eventtrackingPageKeyId;?>'>
    </form>
</div><!-- end of userAddEvent_layerContainer div -->
