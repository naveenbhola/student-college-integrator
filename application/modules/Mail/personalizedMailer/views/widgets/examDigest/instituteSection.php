<?php
$heading = "Institutes accepting ".$examName;
if($totalInstituteCount==1){
        $heading = "Institute accepting ".$examName;
}

$headingText = $totalCount > 1 ? $headingText : $totalCount;
?>
<tr>
 <td valign="top" align="center">
  <table width="96%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
   <tr>
    <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-weight:bold; font-size:16px; color:#666666;text-align:left;"><?php echo $headingText;?> <?php echo $heading;?></td>
   </tr>
   <tr>
    <td valign="top" height="13"></td>
   </tr>
   <tr>
    <td valign="top" align="center">
     <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
	<?php
    $instituteLoopCount = 0;
      foreach($instCourseMapping as $key=>$data){
            if(strpos($data['instituteName'], $data['mainLocation']) === false)
            {
                    $data['instituteName'] .= ', '.$data['mainLocation'];
            }
         
	if(($totalInstituteCount - $instituteLoopCount)==1){ ?>
		<tr>
		    <td valign="top" colspan="3">
		      <table width="100%" align="left" border="0" cellspacing="0" cellpadding="0">
	<?php } ?>
              <tr>
           <td valign="top" width="16"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/personalizedMailers/lean_mailer_bullet.png" /></td>                    
           <td valign="top" align="left"><a href="<?php echo $data['instituteUrl'];?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#03818d; text-decoration:none;text-align:left;"><?php echo $data['instituteName'];?></a></td>
           <td></td>
          </tr>
	<?php if(($totalInstituteCount - $instituteLoopCount)==1){ ?>
          <tr>
          	<td valign="top" height="10" colspan="2"></td>
          </tr>
	   </tr>
		</table>
	      
	      </td>
	  </tr>
	<?php }else{ ?>
	  <tr>
		<td valign="top" height="6" colspan="3"></td>
          </tr>
	<?php }
        $instituteLoopCount++;
      }
    ?>
 <?php if($totalCount>5){ ?>
      <tr>
        <td valign="top" colspan="3">
          <table width="130" border="0" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border:1px solid #03818d;">
            <tr>
              <td height="25" align="center"><a href="<?php echo $srpUrl;?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank" style="font-family:Arial, sans-serif; color:#03818d; font-size:12px;display:block; line-height:25px; font-weight:bold; text-decoration:none;text-align:center;">View All Institutes</a></td>
              
              </tr>
            </table>
  </td>
      </tr>
 <?php } ?>
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
