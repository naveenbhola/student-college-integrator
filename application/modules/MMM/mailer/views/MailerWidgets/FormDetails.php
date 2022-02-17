<tr>
   <td></td>
   <td bgcolor="#ffffff" align="center">
<table width="92%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="text-align:left">
<tr>
<?php if($onlineFormLink) {?>
<td height="50"><a target="_blank" style="font-size:14px; text-decoration:none; color:#0065e8; font-family:Arial;" href="<?php echo THIS_CLIENT_IP."/getListingDetail/".$instituteId."/institute~FormDetails";  ?><!-- #widgettracker --><!-- widgettracker# -->"><?php  echo $instituteName; ?></a><font color="#474a4b" face="Arial, Helvetica, sans-serif" style="font-size:14px;">, <?php echo $instituteLocation; ?></font></td>
<td valign="bottom">
    
    <table cellspacing="0" cellpadding="0" border="0" width="147" style="font-family:Arial; font-size:13px; color:#474a4b;">
              <tbody>
 
<tr>
                <td height="34" width="216" bgcolor="#ffda3e" align="center" style="border:1px solid #e8b363; border-radius:2px;"><a href="<?php echo $onlineFormLink; ?>~FormDetails.php<!-- #widgettracker --><!-- widgettracker# -->" title="Apply online now" target="_blank" style="text-decoration:none; font-size:16px; color:#4b4b4b; line-height:30px; display:block"><strong>Apply online now</strong></a></td>
              </tr>
            </tbody></table>
    </td>
        <?php } else {?>
                <td height="50" valign="bottom"><a style="font-size:20px; text-decoration:none; color:#0065e8; font-family:Georgia, 'Times New Roman', Times, serif;" target="_blank" href="<?php echo THIS_CLIENT_IP."/getListingDetail/".$instituteId."/institute~FormDetails";  ?><!-- #widgettracker --><!-- widgettracker# -->"><?php  echo $instituteName; ?></a><font color="#474a4b" face="Arial, Helvetica, sans-serif" style="font-size:16px;">, <?php echo $instituteLocation; ?></font></td> 
                <?php  }?>
                 </tr>
        </table>
</td>
   <td></td>
</tr>
<tr>
   <td></td>
   <td bgcolor="#ffffff" height="20"></td>
   <td></td>
</tr>