<div class="drip_accordion">

  <div class="drip_accordion-titl">
    <h2>Drip Campaign <span>(optional)</span></h2>
    <p>Resend this mail based on user activity on the above email. Select desired segment below
      <i class="pwaicono-exclamationCircle default-exclCircle">
        <label class="help-text-popup">Mailer name for each drip campaign activity will follow nomenclature - &lt;Parent Mailer Name&gt; appended by &lt;Campaign Activity&gt;</label>
      </i>
    </p>
  </div>

  <div class="drip--drodowns">

    <?php
    if($resendMailer ==1){
      $mailCriteria =1;
    }
    for($i=2;$i<=5;$i++) {
      $data['widgetNumber'] = $i;
      $data["mailCriteria"] = $mailCriteria;
      $data['data'] = $subscriptionDetails;
      $disable = "";
      $disableClass = "";
      if (in_array($i-1, $indexToHide)){
        $disableClass = "disableCheckBox" ;
        $disable = 'disabled=disabled';
      }
      if($i==2) {
        $title = "Users who have <strong>Opened</strong>";
      } else if($i==3) {
        $title = "Users who have <strong>Opened & Clicked</strong>";
      } else if($i==4) {
        $title = "Users who have <strong>Opened & Not Clicked</strong>";
      } else if($i==5) {
        $title = "Users who have <strong>Not Opened & Not Clicked</strong>";
      }
    ?>

      <div class="drip--dropdowns_list <?php echo $disableClass ;?>"  >
        <input type="checkbox" name="dripdropdpwn_<?php echo $i;?>" id="<?php echo 'dripSectionId_'.$mailCriteria.'_'.$i;?>" onclick="toggleScheduleSection(this, '<?php echo $mailCriteria;?>', '<?php echo $i;?>');" <?php echo $disable?> >
        <label id="<?php echo 'dripSectionHeadingId_'.$mailCriteria.'_'.$i;?>" for="<?php echo 'dripSectionId_'.$mailCriteria.'_'.$i;?>" class="drip_lable"><?php echo $title;?></label>
        <div class="drip--dropdowns_data">            
            <?php $this->load->view('mailer/MailSchedulerWidgets/dripCampaignChild',$data); ?>
        </div>
      </div>
    <?php } ?>

  </div>

  <div class="bgClr--btns">
    <?php if ($resendMailer !=1){?>
    <button type="button" name="saveButton_<?php echo $mailCriteria;?>" class="button button--orange" onclick="saveMailer('<?php echo $mailCriteria;?>')">Save Mailer as Draft
    </button>
  <?php }?>
    <button type="button" name="scheduleButton_<?php echo $mailCriteria;?>" class="button button--orange" onclick="scheduleMailer('<?php echo $mailCriteria;?>')">Schedule</button>
   </div>

</div>


