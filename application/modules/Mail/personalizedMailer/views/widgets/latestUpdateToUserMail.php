<?php 
  $totalPagesRows =  floor(count($snippetPages)/4);
  $linksInlastRow =  count($snippetPages) % 4;
?>

  <tr>
     <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#5a595c;text-align:left"><strong>Hi <?=ucfirst($userName)?></strong>, <br/><br/>We thought the following might be helpful to you:</td>
  </tr>
  <tr>
    <td valign="top" height="13"></td>
  </tr>
  <tr>
    <td width="600" valign="top" align="center"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="border-right:1px solid #e2dfdf; border-bottom:1px solid #e2dfdf;"> 
  <tr>
    <td height="13"></td>
  </tr>
  <tr>
    <td valign="top" align="center"><table width="96%" border="0" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
  <tr>
   <td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; text-align:left; color:#333333;"><?php echo $heading;?></td>
  </tr>
  <tr>
     <td height="10"></td>
  </tr>
  <?php 
    $row = 1;
  foreach($updatesData as $key=>$details){
	 $textMsg = !empty($details['announce_url'])?'<a style="color:#03818d;text-decoration:none;" href="'.$details['announce_url'].'">'.$details['update_text'].'</a>':$details['update_text'];
    ?>
  <tr>
    <td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; text-align:left; line-height:18px; color:#888888;"><?=$textMsg;?></td>
  </tr> 
  <tr>   
    <td height="11"></td>
  </tr>
  
  <?php if($row != count($updatesData)){?>
  <tr>
    <td valign="top" align="center"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
      <tr>
        <td valign="top" align="center">
          <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse">
            <tr>
              <td style="border-bottom:1px solid #e0dddd;">
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table></td>
  </tr>
  <?php } ?>
  <tr>
    <td height="12"></td>
  </tr>
  <?php 
    $row++;
    } ?>
  <tr>
    <td style="font-family:Arial, Helvetica, sans-serif; font-size:13px; font-weight:bold; text-align:right; color:#03818d;"><a href="<?php echo $update_url?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank" style="color:#03818d;">View all updates about <?=$examName;?></a></td>
  </tr>  
  </table></td>
  </tr>
  <tr>
    <td height="15"></td>
  </tr>
</table>
</td>
  </tr>
   <tr>
     <td height="10"></td>
   </tr>
   <?php if($totalPagesRows>0 || $linksInlastRow>0){?>
   <tr>
    <td width="600" valign="top" align="center"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="border-right:2px solid #e2dfdf; border-bottom:1px solid #e2dfdf;">
  <tr>
    <td height="13"></td>
  </tr>
  
  <tr>
    <td valign="top" align="center"><table width="96%" border="0" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
  <tr>
    <td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; text-align:left; color:#333333;">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
  <tr>
    <td width="3"></td>
    <td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; text-align:left; color:#333333;">More Information about <?=$examName;?></td>
  </tr>
  </table>
</td>
  </tr>
  <tr>
    <td height="8"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
    <?php 
    $count = 0;
    for($i=0;$i<$totalPagesRows;$i++){
      ?>
      <tr>
         <?php for($j=0;$j<4;$j++){?>
          <td width="3"></td>
          <td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; line-height:19px; text-align:left; color:#03818d;"><a href="<?=$snippetPages[$count]['sectionUrl']?><!-- #AutoLogin --><!-- AutoLogin# -->" style="color:#03818d;text-decoration:none;" target='_blank'><?=$snippetPages[$count]['sectionName']?></a></td>
          <td width="8%"></td>
        <?php 
        $count++;
      } ?>
      </tr>
    <?php } ?>
     <?php if($linksInlastRow>0){?>
      <tr>
        <?php for($j=0;$j<$linksInlastRow;$j++){?>
        <td width="3"></td>
        <td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; line-height:19px; text-align:left; color:#03818d;"><a href="<?=$snippetPages[$count]['sectionUrl']?><!-- #AutoLogin --><!-- AutoLogin# -->" style="color:#03818d;text-decoration:none;" target='_blank'><?=$snippetPages[$count]['sectionName']?></a></td>
        <td width="8%"></td>
        <?php 
        $count++;
      } ?>
      </tr>
    <?php } ?>
    <tr><td height="17"></td></tr>
    </table>
    </td>
  </tr>
  </table></td>
              <td width="14"></td>
             </tr>
            </table>
           </td>
          </tr>
          <?php } ?>

   
   
