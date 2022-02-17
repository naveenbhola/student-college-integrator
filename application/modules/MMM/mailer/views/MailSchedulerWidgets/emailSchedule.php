<div class="mailer_wraper" id="email_subscription_section_<?php echo $mailCriteria?>">
  <div class="maile_data_title">
     Email Scheduling
     <p>Schedule mails and select subscription  to send email</p>
  </div>
  <div class="drip--form_data">
    <label for="" style="position: relative;top: -19px;">Send Date<span class="redText">*</span>:</label>
      <div class="drip--form-fileds">
        <?php
          $immediately = 'checked="checked"';$later ='';
          $scheduleDate = date("Y-m-d");
          $data['scheduleDate'] = $scheduleDate;
        ?>
        <p>
          <input type="radio" <?php echo $immediately;?> name="mailSchedule" id="st1_<?php echo $mailCriteria?>" onclick="changeMailSchedule(this,<?php echo $mailCriteria?>,<?php echo $widgetNumber?>)" value="immediately_<?php echo $mailCriteria?>"> <label for="st1_<?php echo $mailCriteria?>">Immediately</label>
          <i class="pwaicono-exclamationCircle default-exclCircle">
            <span class="help-text-popup">Send mail as soon as schedule button is clicked</span>
          </i>
        </p>

        <?php $this->load->view('mailer/MailSchedulerWidgets/scheduleDateSelector',$data); ?>

      </div>
  </div>

  <?php $this->load->view('mailer/MailSchedulerWidgets/chooseSubscription',$data); ?>

</div>

