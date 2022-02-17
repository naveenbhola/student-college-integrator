
    <tr>
     <td valign="top" align="center">
      <table width="91%" align="center" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
       <tbody>
       <tr>
        <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#333333; text-align:left;">Hi <?php echo ucwords($userName); ?>,</td>
       </tr>
       <tr>
        <td valign="top" height="6"></td>
       </tr>
       <tr>
        <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#333333; text-align:left; line-height:18px;">Thank you for answering on <strong>Shiksha Ask and Answer</strong> platform. </td>
       </tr>
       <tr>
        <td valign="top" height="17"></td>
       </tr>
       <tr>
        <td valign="top">
         <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;border:1px solid #f1f1f1">
          <tbody><tr>
           <td width="13"></td>
           <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
             <tbody><tr>
              <td valign="top" height="5"></td>
             </tr>
             <tr>
              <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; color:#333333; font-weight:bold; text-align:left; line-height:18px;">Unfortunately, the answer posted by you does not meet our quality parameters. This is because: </td>
             </tr>
             <tr>
              <td valign="top" height="6"></td>
             </tr>
             <tr>
              <td valign="top" height="1" bgcolor="#f1f1f1"></td>
             </tr>
             <tr>
              <td valign="top" height="9"></td>
             </tr>
             <tr>
              <td valign="top">
               <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                <tbody>
                <?php foreach ($reasonArr as $key => $value) { ?>
                <tr>
                 <td valign="top" width="12" align="left"><img src="http://ieplads.com/mailers/2018/shiksha/contributor-06feb/images/bullet.png"></td>
                 <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#333333; text-align:left; line-height:18px;"><?=$value?></td>
                </tr>
                <tr>
                 <td valign="top" height="4" colspan="2"></td>
                </tr>
                <?php } ?>
               </tbody></table>
              </td>
             </tr>
             <tr>
              <td valign="top" height="4"></td>
             </tr>
             <?php if(!empty($commentText)){?>
             <tr>
              <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#333333; text-align:left; line-height:18px;"><strong style="text-decoration:underline;">Moderator's Advice:</strong> <?=$commentText?>.</td>
             </tr>
             <tr>
              <td valign="top" height="12"></td>
             </tr>
             <?php } ?>
            </tbody></table>
           </td>
           <td width="13"></td>
          </tr>
         </tbody></table>
        </td>
       </tr>
       <tr>
        <td valign="top" height="14"></td>
       </tr>
       <tr>
        <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#333333; text-align:left; line-height:18px;">We would, therefore, urge you to take out a quick minute and edit your answer by<br>clicking this link:</td>
       </tr>
       <tr>
        <td valign="top" height="8"></td>
       </tr>
       <tr>
        <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:15px; color:#333333; text-align:left; font-weight:bold;"><?=$questionText?></td>
       </tr>
       <tr>
        <td valign="top" height="6"></td>
       </tr>
       <tr>
        <td valign="top">
         <table width="100" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #03818d;">
          <tbody><tr>
           <td align="center" height="28"><a href="<?=$seoURL?><!-- #AutoLogin --><!-- AutoLogin# -->" style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; color:#03818d; text-decoration:none; display:block; line-height:28px;">Edit Answer</a></td>
          </tr>
         </tbody></table>
        </td>
       </tr>
       <tr>
        <td valign="top" height="16"></td>
       </tr>
       <tr>
        <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#333333; text-align:left; line-height:18px;"><strong>What makes an answer good?:</strong> We have seen that answers that are more descriptive and well substantiated tend to be received better by readers. Also, contributors that write more detailed/well-rounded answers tend to get higher number of followers on Shiksha</td>
       </tr>
       <tr>
        <td valign="top" height="14"></td>
       </tr>
       <tr>
        <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#333333; text-align:left; line-height:18px;"><strong>Note:</strong> Please make necessary edits within the next <strong><?=$timeLimit?> hours</strong> to prevent automatic<br><strong>answer deletion.</strong></td>
       </tr>
       <tr>
        <td valign="top" height="11"></td>
       </tr>
       <tr>
        <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#333333; text-align:left; line-height:18px;">We value your
         interest and the time you have taken out to give this answer.
        </td>
       </tr>
       <tr>
        <td valign="top" height="31"></td>
       </tr>
       <tr>
        <td valign="top" style="font-family:Tahoma, Geneva, sans-serif; font-size:13px; color:#333333; text-align:left; line-height:18px;">Thank you and keep answering,<br><strong>Shiksha Moderation Team</strong>
        </td>
       </tr>
      </tbody></table>
     </td>
    </tr>
    
  