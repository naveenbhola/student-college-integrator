<div id="smsReminderLayer" data-enhance="false"  data-role="page" style="background: #fff;">
    <header class="cale-header2">
        <a href="javascript:void(0);" id="userSetReminder_backButton" onclick="resetReminderData();" data-rel="back" class="cal-back-btn"><i class="cale-sprite cl-backicn"></i></a>
        <h3 class="head-cal-txt alrtIcnHed2"><i class="cale-sprite cl-alarm3"></i>SET SMS REMINDER</h3>
        <a href="javascript:void(0);" onclick="saveUserReminder();" class="head-cal-done"><i class="cale-sprite cl-tick"></i></a>
    </header>
    <section class="bgColrWhte setRemdPange" id="userEventReminderContainer"></section>
    <form style="display: none;" id="setReminderFormEC" action="<?=SHIKSHA_HOME?>/muser5/MobileUser/register" method="post">
	<input type="hidden" value="<?=base64_encode(SHIKSHA_HOME.'/mEventCalendar5/EventCalendarController/loadEventCalendar');?>" name="current_url">
	<input type="hidden" value="setReminderFromEventCalendar" name="from_where">
    <input type="hidden" name="tracking_keyid" id="tracking_keyid_reminder" value="<?php echo $remindertrackingPageKeyId;?>">
    </form>
</div><!-- end of smsReminderLayer div -->
