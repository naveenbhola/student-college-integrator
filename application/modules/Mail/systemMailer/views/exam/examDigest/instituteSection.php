<?php
$heading = "Institutes accepting ".$examName;
if($totalInstituteCount==1){
	$heading = "Institute accepting ".$examName;
}
?>
<tr>
  <td width="20">&nbsp;</td>
  <td width="516" valign="top">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
  <tr>
  <td valign="top" height="16"></td>
  </tr>
<tr>
 <td valign="top">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
   <tr>
    <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-weight:bold; font-size:16px; color:#666666;"><?php echo $totalCount;?> <?php echo $heading;?></td>
   </tr>
   <tr>
    <td valign="top" height="13"></td>
   </tr>
   <tr>
    <td valign="top">
     <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
    <?php
    $instituteLoopCount = 0;
    if($totalInstituteCount>1){
      foreach($instCourseMapping as $key=>$data){

            if(($totalInstituteCount - $instituteLoopCount)==1){
                    break;
            }
            //echo ($totalInstituteCount-1).'=='.$instituteLoopCount.'<br/>';
            if(strpos($data['instituteName'], $data['mainLocation']) === false)
            {
                    $data['instituteName'] .= ', '.$data['mainLocation'];
            }
          ?>
          <tr>
           <td valign="top" width="16"><img src="http://ieplads.com/mailers/2017/shiksha/exam-digest-07sept/images/bullet.png" /></td>                    
           <td valign="top" width="300"><a href="<?php echo $data['instituteUrl'];?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#1f65a6; text-decoration:none;"><?php echo $data['instituteName'];?></a></td>
           <td></td>
          </tr>
          <tr>
           <td valign="top" height="6" colspan="3"></td>
          </tr>
    <?php   
        $instituteLoopCount++;
      } 
    } 
    ?>
      <tr>
       <td valign="top" colspan="3">
        <table width="68%" align="left" border="0" cellspacing="0" cellpadding="0">
         <tr>
          <td width="16" valign="top"><img src="http://ieplads.com/mailers/2017/shiksha/exam-digest-07sept/images/bullet.png" /></td>
          <td valign="top"><a href="<?php echo $instCourseMapping[$instituteLoopCount]['instituteUrl'];?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#1f65a6; text-decoration:none;"><?php echo $instCourseMapping[$instituteLoopCount]['instituteName'];?></a></td>
         </tr>
         <tr>
          <td valign="top" height="10" colspan="2"></td>
         </tr>
        </table>
	<?php if($totalCount>5){ ?>
        <table width="150" align="left" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
         <tr>
          <td valign="top"><a href="<?php echo $srpUrl;?><!-- #AutoLogin --><!-- AutoLogin# -->" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#1f65a6; font-weight:bold;" target="_blank">View All Institutes</a></td>
         </tr>
         <tr>
          <td valign="top" height="10"></td>
         </tr>
        </table>
	<?php } ?>
       </td>
      </tr>
      <tr>
       <td valign="top" height="7" colspan="3"></td>
      </tr>
      <tr>
       <td valign="top" height="1" bgcolor="#e0dddd" colspan="3"></td>
      </tr>
    </table>

    </td>
   </tr>
  </table>
 </td>
</tr>
