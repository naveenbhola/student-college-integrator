<?php if($widgetNumber ==1) { ?>
<div class="mailer_wraper" id="steps_labels">
  <div class="maile_data_title">Email Setup</div>

  <div class="drip--form_data">
    <label for="">Mailer Name:<span class="redText">*</span></label>
    <div class="drip--form-fileds">
      <input id="mailer_name_<?php echo $mailCriteria?>" type="text" name="mailer_name" class="drip--fileds" maxlength="80">
        <div id='mailer_name_error_<?php echo $mailCriteria?>' class="drip--form-fileds campaign_errors"></div>
    </div>
  </div>
  <?php } ?>
  
  <div>
    <input type="hidden" id='mailerId_<?php echo $widgetNumber?>' type="text" maxlength = 1000 name="mailer_id[]" class="drip--fileds" style>
  </div>
  
  <div class="drip--form_data">
    <label for="">Subject:<span class="redText">*</span></label>
    <div class="drip--form-fileds">
      <input id='subject_name_<?php echo $mailCriteria?>_<?php echo $widgetNumber?>' type="text" maxlength = 1000 name="subject_name[]" class="drip--fileds">
       <div id='subject_name_error_<?php echo $mailCriteria?>_<?php echo $widgetNumber?>' class="drip--form-fileds campaign_errors">
    </div>
    </div>
  </div>

  <div class="drip--form_data">
   <label for="">Sender Name:<span class="redText">*</span></label>
   <div class="drip--form-fileds">
     <input id="sender_name_<?php echo $mailCriteria?>_<?php echo $widgetNumber?>" type="text" maxlength = 125 name="sender_name[]" class="drip--fileds">
      <div id="sender_name_error_<?php echo $mailCriteria?>_<?php echo $widgetNumber?>" class="drip--form-fileds campaign_errors">
   </div>
   </div>
 </div>

 <div class="drip--form_data">
  <label for="">Select Mail Template:</label>
  <div class="drip--form-fileds">
    <select id='selected_mail_template_<?php echo $mailCriteria?>_<?php echo $widgetNumber?>' class="drip--fileds" name = "selected_mail_template[]">
      <?php
      foreach ($templateData as $value){
        ?> 
        <option value="<?php echo $value['id']; ?>"  <?php if ($value['id'] == $mailerData['parentMailerData']['parentTemplateId'])  echo "selected"?>>
          <?php echo $value['name'].' , '.$value['id']; 
          ?>
          
        </option>
        <?php
      }
      ?>
    </select>
  </div>
</div>

<?php if($widgetNumber == 1){ ?>
<div class="drip--form_data">
 <label for="">Sender  Email Id:</label>
 <div class="drip--form-fileds">
   <select id="sendor_email_id_<?php echo $mailCriteria?>_<?php echo $widgetNumber?>" class="drip--fileds" name= "sendor_email_id">
    <?php
    foreach ($senderEmailIds as $value){
      ?> <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
      <?php
    }
    ?>
  </select>
</div>
</div>
<?php } ?>

<div class="drip--form_data">
  <label for="">Test Email id:<span class="redText">*</span></label>
  <div class="drip--form-fileds">
    <input id="test_email_id_<?php echo $mailCriteria?>_<?php echo $widgetNumber?>" type="text" name="testemail<?php echo $mailCriteria?>[]" class="drip--fileds">
    <div id="test_email_id_error_<?php echo $mailCriteria?>_<?php echo $widgetNumber?>" class="drip--form-fileds campaign_errors">
      </div>
    <div class="test-btn">
      
      <button id="shiksha_test_button_<?php echo $mailCriteria?>_<?php echo $widgetNumber?>" type="button"  onclick="sendTestMail('shiksha',<?php echo $mailCriteria?>,<?php echo $widgetNumber?>);">Send Test Email</button>
    </div>

    <div class="test-btn">
      <p id = "OR_<?php echo $mailCriteria?>_<?php echo $widgetNumber?>" style="display: none">OR</p>
     <button style="display: none;" id="amazon_test_button_<?php echo $mailCriteria?>_<?php echo $widgetNumber?>" type="button"  onclick="sendTestMail('amazon',<?php echo $mailCriteria?>,<?php echo $widgetNumber?>);">Send Test Email By Amazon</button>
   </div>
   <div style="float:left;font-size:12px;padding:5px 0 0 5px;width:100%;display: none;" id="AWSTimer_<?php echo $mailCriteria?>_<?php echo $widgetNumber?>">
    <div>
      <p style="display: inline-block;">(Above Button will enable in </label>
      <span style="color:red"><label id="minute_<?php echo $mailCriteria?>_<?php echo $widgetNumber?>" style="top:0px">5</label>:<label id="second_<?php echo $mailCriteria?>_<?php echo $widgetNumber?>" style="top: 0px;">00</label> minutes</span> )
    </div>
  </div>

  <div id="test_mail_error_<?php echo $mailCriteria?>_<?php echo $widgetNumber?>" class="campaign_errors" style="display: none;">

  </div>

</div>
</div>

<?php if($widgetNumber ==1) { ?>
</div>
<?php } ?>
