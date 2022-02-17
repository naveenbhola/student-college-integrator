<div class="dateWraper" id="dateWraper_<?php echo $mailCriteria?>_<?php echo $widgetNumber?>">

  <?php
  if($widgetNumber == 1) { 
    $later ='';
    $disabled = 'disabled="disabled"';
  ?>

    <span>
       <input type="radio" <?php echo $later;?> name="mailSchedule_<?php echo $mailCriteria?>" id="ltr1_<?php echo $mailCriteria?>" onclick="changeMailSchedule(this,<?php echo $mailCriteria?>,<?php echo $widgetNumber?>)" value="later"> 
       <label for="ltr1">Later</label>
    </span> 

  <?php } ?>

  <input type="text" <?php echo $disabled;?> readonly="" id="mailer_start_date_<?php echo $mailCriteria?>_<?php echo $widgetNumber?>" name="mailer_start_date_<?php echo $mailCriteria?>_<?php echo $widgetNumber?>" value="<?php echo $scheduleDate;?>" class="date-input"  onclick="openCalendar(<?php echo $mailCriteria?>,<?php echo $widgetNumber?>);">
  <img src="<?php echo SHIKSHA_HOME;?>/public/images/eventIcon.gif" style="cursor: pointer" align="absmiddle" id="start_date_<?php echo $mailCriteria?>_<?php echo $widgetNumber?>" onclick="openCalendar(<?php echo $mailCriteria?>,<?php echo $widgetNumber?>);">&nbsp;
  <select name="mail_start_hours_<?php echo $mailCriteria?>_<?php echo $widgetNumber?>" id="mail_start_hours_<?php echo $mailCriteria?>_<?php echo $widgetNumber?>" <?php echo $disabled;?>>
    <!-- <option value=''>Hour</option> -->
    <?php
    for($i=0;$i<10;$i++) {
      $hourchecked = '';
      if($i == $scheduleHour) {
        $hourchecked ='selected="selected"';
      }
      echo "<option value='0".$i."' ".$hourchecked.">0".$i."</option>";
    }
    for($i=10;$i<24;$i++) {
      $hourchecked = '';
      if($i == $scheduleHour) {
        $hourchecked ='selected="selected"';
      }
      echo "<option value='".$i."' ".$hourchecked.">".$i."</option>";
    }
    ?>
   </select>
   &nbsp;
   <select name="mail_start_minutes_<?php echo $mailCriteria?>_<?php echo $widgetNumber?>" id="mail_start_minutes_<?php echo $mailCriteria?>_<?php echo $widgetNumber?>" <?php echo $disabled;?>>
		<!-- <option value=''>Min</option> -->
    <?php
    for($i=0;$i<10;$i++) {
      $minutechecked = '';
      if($i == $scheduleMinute) {
        $minutechecked ='selected="selected"';
      }
      echo "<option value='0".$i."' ".$minutechecked.">0".$i."</option>";
    }
    for($i=10;$i<60;$i++) {
      $minutechecked = '';
      if($i == $scheduleMinute) {
        $minutechecked ='selected="selected"';
      }
      echo "<option value='".$i."' ".$minutechecked.">".$i."</option>";
    }
    ?>
	</select>
  <i class="pwaicono-exclamationCircle default-exclCircle">
    <span class="help-text-popup">Set date and time of mail scheduling</span>
  </i>
</div>

 <div id='schedule_date_error_<?php echo $mailCriteria?>_<?php echo $widgetNumber?>' class="drip--form-fileds campaign_errors"> </div>
