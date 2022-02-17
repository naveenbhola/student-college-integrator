<table width="600" border="0" cellspacing="0" cellpadding="0" align="center" style="border:8px solid rgb(73, 107, 151);">
   <tr>
      <td style="padding: 15px 0px 0px 15px;" valign="top" height="75" width="300">
         <?php echo Modules::run('systemMailer/SystemMailer/getAbroadMailerHeaderLogo'); ?>
      </td>
      <td align="right" width="285" valign="top" style="padding: 15px 15px 0px 0px; font-family: Arial; font-size: 12px;"></td>
   </tr>
   <tr>
      <td bgcolor="#ededed" height="1" colspan="2"></td>
   </tr>
   <tr>
      <td height="25" colspan="2"></td>
   </tr>
   <tr>
      <td align="center" colspan="2">
         <table cellspacing="0" cellpadding="0" border="0" width="556" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; color: rgb(26, 26, 26); line-height: 18px; text-align: left;">
            <tbody>
               <tr>
                  <td>
                     <style>
                        ul,p{font-family:Arial, Helvetica, sans-serif;font-size:12px} ul.ml_0{margin-left:0; list-style-position:inside} a{color:#0066FF} 
                     </style>
                     <div style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#1A1A1A; padding-bottom: 10px; line-height:20px;">
                     <div width="600">
                        Dear Admin<br/> 
                        <p>A user has shared the following feedback on studyabroad.shiksha.com.</p>
                        <p><b>User Email:</b> <?=htmlentities($email)?></p>
                        <p><b>User Mobile:</b> <?=htmlentities($mobile)?></p>
                        <p><b>User Feedback:</b> <?=htmlentities($feedback_comment)?></p>
                        Regards<br/> <b><span style='color:#F68936'>Shiksha</span> <span style='color:#3C54A4'>Study Abroad</span></b>
						<p>We care about your career - Visit us at <a href="<?php echo SHIKSHA_STUDYABROAD_HOME; ?>">studyabroad.shiksha.com</a></p>
						   </div>
                     </div>
                  </td>
               </tr>
            </tbody>
         </table>
      </td>
   </tr>
   <tr>
      <td valign="top" colspan="2" align="center" style="border-top: 1px solid rgb(217, 217, 217); font-family: Arial,Helvetica,sans-serif; font-size: 10px; padding: 10px; color: rgb(102, 102, 102); line-height: 14px;">
         For any technical, legal or marketing related queries, please write to us at <a href="mailto:support@shiksha.com" style="color:#231E19">support@shiksha.com</a><br/>
         <br/> Copyright &copy; 2014 Shiksha.com. All rights reserved.<br/>Shiksha.com is located at B-8, Sector-132, NOIDA, UP 201301, INDIA
      </td>
   </tr>
</table>