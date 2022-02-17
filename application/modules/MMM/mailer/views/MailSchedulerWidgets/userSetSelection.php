
<div class="mailer_wraper" id="email_audience_<?php echo $mailCriteria?>">
  <div class="maile_data_title"> 
      Email Audience
     <p>Select User Sets to send emails to</p>
  </div>

   <div class="drip--form_data">
    <!--NEW html -->
    <div class="mar_full_10p" style="margin-top:15px;">

      <div class="r2_2 errorMsg campaign_errors" id= "clk_one_error_<?php echo $mailCriteria?>">
      </div>

      <div class="clear_L"></div>

      <div id="add_new_user_set_<?php echo $mailCriteria?>"></div>

      <form id="formForTestmail_Template_<?php echo $mailCriteria?>"  name  = "usersetForm_<?php echo $mailCriteria?>"  enctype="multipart/form-data" action="/mailer/MailScheduler/processUserSetCSV/" method="POST">
        <table style="font-size:13px; margin-bottom: 10px;margin-left: 60px;width: 100%;">
          <tr>
            <td width="145">
              <span>Mailer userset : </span>
            </td>
            <td width="200"><input type="radio" checked id="use_userset_<?php echo $mailCriteria?>" name="user_list_template_<?php echo $mailCriteria?>" value="use_userset_<?php echo $mailCriteria?>" onclick="view_user_list_template(this,<?php echo $mailCriteria?>)"> Select Userset </td>
            <?php 
             
            ?>
            <td width="550"><input type="radio" id="upload_csv_<?php echo $mailCriteria?>" name="user_list_template_<?php echo $mailCriteria?>" value="upload_csv_<?php echo $mailCriteria?>" onclick="view_user_list_template(this,<?php echo $mailCriteria?>)"> Upload CSV </td>
            <?php 
             
            ?>
          </tr>
        </table>
        <!-- LISTING Start -->
        <div id="select_userset_tmp_<?php echo $mailCriteria?>" >
            <div class="row">
              <?php $this->load->view('mailer/MailSchedulerWidgets/usersetSelect'); ?>
            </div>
            <div class="lineSpace_10">&nbsp;</div>
        </div>
     
        <!-- LISTING End -->

        <!-- UPLOAD CSV Form Start -->
        <div id="upload_csv_select_user_tmp_<?php echo $mailCriteria?>"  style="display:none;" >
          <div class="lineSpace_10">&nbsp;</div>
          <div class="row">
            <fieldset>
              <div style="display:inline;float:left;width:100%">
                <div class="disclaimer">Drip mailers will be send only for users registered on shiksha</div>
                <div class="r1_1 bld">Upload CSV:&nbsp;&nbsp;</div>
                <div class="r2_2">
                  <input type="file" name="files" id="c_csv_<?php echo $mailCriteria?>" size="5" style="" />
                </div>
                <div class="clear_L"></div>
                <div class="row errorPlace" style="margin-top:2px;">
                  <div class="r1_1">&nbsp;</div>
                  <div class="clear_L"></div>
                </div>
              </div>
            </fieldset>
          </div>
          <div class="lineSpace_10">&nbsp;</div>
          <div class="lineSpace_10">&nbsp;</div>
          <div class="r1_1 bld">&nbsp;</div>
          <div class="row" style="text-align: center;">
            <button class="button button--orange" type="button" onclick="validateAndProcessDownloadListing(<?php echo $mailCriteria?>);" value="">Download Invalid Email
            </button>

            <button style="margin-left: 6px;" class="btnSecondary" type="button" onclick="validateAndProcessUsersetCSV(<?php echo $mailCriteria?>);" id="getUserCountInCSV_<?php echo $mailCriteria?>" value="">
                    Get User Count
            </button>
             <span id="userCountInCSV_<?php echo $mailCriteria?>" style="margin-left: 10px; font:bold 14px arial;position: absolute;"></span>
          </div>
          <div class="lineSpace_10">&nbsp;</div>
          <div class="clear_L"></div>
          <div class="lineSpace_10">&nbsp;</div>
        </div>
          <li>
             <label>&nbsp;</label>
          </li>

        <div class="txt_align_r float_L fontSize_12p" id="total_mails_tobe_send_<?php echo $mailCriteria?>" style="width: 165px;  font-size:13px; color:#333; padding-top:4px;">No. of Mails: &nbsp;</div>
        <div class="float_L" style="padding-top:4px;">
          <input type="radio" checked id="mails_limit_all_<?php echo $mailCriteria?>" name="mails_limit_<?php echo $mailCriteria?>" onclick="select_user_limit(this,<?php echo $mailCriteria?>)" value="<?php echo $totalUsersInCriteria; ?>" <?=$allmails?> /> All &nbsp;&nbsp;
        </div>
        <div class="float_L" style="padding-top:4px;">
          <input type="radio" id="mails_limit_no_<?php echo $mailCriteria?>" name="mails_limit_<?php echo $mailCriteria?>" onclick="select_user_limit(this,<?php echo $mailCriteria?>)" value="<?php echo $numUsers; ?>" <?=$limitmails?>/>
        </div>
        <div class="float_L">
          <input type="number" id="mails_limit_text_<?php echo $mailCriteria?>" disabled="true" name="mails_limit_text_<?php echo $mailCriteria?>" style="margin-left:4px;border:1px solid #ccc; padding:5px 2px; font-size: 13px;" align="absmiddle" placeholder="Specify mailer count" />
         </div>

         <br/><br/>
         <div id='mails_limit_text_error_<?php echo $mailCriteria?>' class="drip--form-fileds campaign_errors"></div>
         <div class="clearFix"></div>
        <!-- UPLOAD CSV Form End -->
      
       
        <input type="hidden" id  = "temp_id<?php echo $mailCriteria?>_<?php echo $mailCriteria?>" name="temp_id" value="16765" />
        <input type="hidden" id  = "templateType_<?php echo $mailCriteria?>" name="templateType"  value="mail">
        <input type="hidden" id="download_check_<?php echo $mailCriteria?>" name="download_check" value="0" />
        <input type="hidden" id="user_count_<?php echo $mailCriteria?>" name="user_count_<?php echo $mailCriteria?>" value="0" />
        <input type="hidden" id="list_id_<?php echo $mailCriteria?>" name="list_id_<?php echo $mailCriteria?>" value="" />

        <input type="hidden" id="campaign_id" name="campaign_id" value="" />
        
      </form>
     
    <!--nEW html END-->    
   </div>
 </div>
</div>
