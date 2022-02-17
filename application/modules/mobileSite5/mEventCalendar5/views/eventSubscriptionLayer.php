<div id="userSetAlert_layerContainer" data-enhance="false"  data-role="page">
    <header class="cale-header2">
        <a href="javascript:void(0);" id="userSetAlert_backButton" class="cal-back-btn" data-rel="back"><i class="cale-sprite cl-backicn"></i></a>
        <h3 class="head-cal-txt alrtIcnHed"><i class="cale-sprite cl-msgalert"></i>SET ALERTS</h3>
        <a href="javascript:void(0);" class="head-cal-done" onclick="setAlertsForExam('<?php echo $alerttrackingPageKeyId;?>');">
	    <span id="tick-mark-curtain" class="curtain"></span>
	    <i class="cale-sprite cl-tick"></i>
	</a>
    </header>

    <section class="bgColrWhte setalrt-pg">
       <p class="cale-ptxt">Get alerts for important dates of following Exams in your inbox.</p>
       <span id="userSetAlert_errorDiv" style="display: none;">Please select atleast one exam.</span>
       <ul class="cale-xmsLst" id="userSetAlert_examList" style="padding-bottom: 0px; border-bottom: none;">
           <li><p>Select Exams</p></li>
	   <?php
	   foreach($examNameList as $examName)
	   {
	    $checked = '';
	    if(in_array($examName, $userSubscribedExams)){
			$checked = 'checked="checked"';
	    }
	   ?>
	    <li><input class="userSetAlert_exam_checkbox" type="checkbox" <?php echo $checked?> value="<?php echo $examName?>" id="userSetAlert_exam_<?php echo str_replace(' ', '_', $examName)?>" /><label for="userSetAlert_exam_<?php echo str_replace(' ', '_', $examName)?>"><?php echo $examName?></label></li>
	   <?php
	   }
	   ?>
       </ul>
       <form style="display: none;" id="setAlertsFormEC" action="<?php echo SHIKSHA_HOME?>/muser5/MobileUser/register" method="post">
	<input type="hidden" value="<?php echo base64_encode(SHIKSHA_HOME.'/mEventCalendar5/EventCalendarController/loadEventCalendar');?>" name="current_url">
	<input type="hidden" value="setAlertsFromEventCalendar" name="from_where">
	<input type="hidden" name="tracking_keyid" id="tracking_keyid" value=''>
      </form>
    </section>
</div><!-- end of userSetAlert_layerContainer div -->
