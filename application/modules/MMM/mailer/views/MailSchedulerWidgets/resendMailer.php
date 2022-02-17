<div id = "mailCriteria_1" name = "mailCriteria_1">
  <div class="drip_compaign">
         <div class="drip_data">
           <h2>Drip Campaign</h2>
           <p>Details for mailer selected against which drip compaign is to be done</p>
         </div>
         <div class="drip_table">
           <div class="drip_table_row">
             <div class="drip_table_cell">Campaign Name</div>
             <div class="drip_table_cell"  ><strong><?php echo $mailerData['parentMailerData']['campaignName']?></strong></div>
           </div>
           <div class="drip_table_row">
             <div class="drip_table_cell">Campaign Id</div>
             <div class="drip_table_cell" ><strong><?php echo $mailerData['parentMailerData']['campaignId']?></strong></div>
           </div>
           <div class="drip_table_row">
             <div class="drip_table_cell">Parent Mail Id</div>
             <div class="drip_table_cell" ><strong><?php echo $mailerData['parentMailerData']['id'] ?></strong></div>
           </div>
           <div class="drip_table_row">
             <div class="drip_table_cell">Parent Mail Name</div>
             <div class="drip_table_cell"><strong><?php echo $mailerData['parentMailerData']['mailerName'] ?></strong></div>
           </div>
            <div class="drip_table_row">
             <div class="drip_table_cell">Included Userset</div>
             <div class="drip_table_cell"  ><strong><?php 
             if (!empty($mailerData['csvList'])){
                echo $mailerData['csvList'];
             }
             else{
                echo $mailerData['includedUserSet'] ;
             }
             ?></strong></div>
           </div>
            <div class="drip_table_row">
             <div class="drip_table_cell">Excluded Userset</div>
             <div class="drip_table_cell"  ><strong><?php echo $mailerData['excludedUserSet']?></strong></div>
           </div>
            
           <!-- <div class="drip_table_row">
             <div class="drip_table_cell">Sent to Userset</div>
             <div class="drip_table_cell"><strong><?php echo $mailerData['parentMailerData']['criteria'] ?></strong></div>
           </div> -->

           <div class="drip_table_row">
             <div class="drip_table_cell">Parent Mailer Scheduled Time</div>
             <div class="drip_table_cell"><strong><?php echo $mailerData['parentMailerData']['time'] ?></strong></div>
           </div>
         </div>

        <input type="text" style="display: none" id = "clientId" name="clientId" value = "<?php echo $mailerData['parentMailerData']['clientId'] ?>"> 
        <input type="text" style="display: none" id = "resendMailer" name="resendMailer" value = "1">
        <input id="mailer_name_1" style="display: none;" type="text" name="mailer_name" value = "<?php echo $mailerData['parentMailerData']['mailerName'] ?>" class="drip--fileds">
        <input id="subject_name_1_1" style="display: none" value = "<?php echo $mailerData['parentMailerData']['subject'] ?>" maxlength="1000" type="text" name="subject_name[]" class="drip--fileds">
        <input id="sender_name_1_1" style="display: none" value = "<?php echo $mailerData['parentMailerData']['sendername'] ?>" maxlength="1000" type="text" name="sender_name[]" class="drip--fileds">
        <input type="text" style="display: none" id="campaignName" name = "campaignName"  value="<?php echo $mailerData['parentMailerData']['campaignName']?>">
        <input type="text" style="display: none" id="campaignId" value ="<?php echo $mailerData['parentMailerData']['campaignId']?>" name = "campaignId">
        <input type="text" style="display: none" name = "parentMailerId" id= "parentMailerId" value = "<?php echo $mailerData['parentMailerData']['id']?>" >
        <input type="text" style="display: none" name = "parentMailerName" id = "parentMailerName" value = "<?php echo $mailerData['parentMailerData']['mailerName']?>">
        <input type="text" style="display: none" name = "criteria_1" id= "criteria" value = "<?php echo $mailerData['parentMailerData']['criteria']?>">
        <input type="text" style="display: none" name = "user_count_1" id= "user_count_1" value = "<?php echo $mailerData['parentMailerData']['totalUsersInCriteria']?>">
        <input type="text" style="display: none" name = "parentMailerDate" id= "parentMailerDate" value = "<?php echo $mailerData['parentMailerData']['time']?>">
           <input type="text" style="display: none" name = "sendor_email_id" id= "sendor_email_id" value = "<?php echo $mailerData['parentMailerData']['senderMail']?>">
         <input type="text" style="display: none" name = "selected_mail_template[]" id= "selected_mail_template" value = "<?php echo $mailerData['parentMailerData']['templateId']?>">
          <input type="text" style="display: none" name = "listId" id= "listId" value = "<?php echo $mailerData['parentMailerData']['listId']?>">



    <?php $this->load->view('mailer/MailSchedulerWidgets/dripCampaign',$mailerData); ?>
   
  </div>
</div>
