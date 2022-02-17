<?php
$heading = "Questions & Answer related to ".$examName;
if($totalCount==1){
        $heading = "Question & Answer related to ".$examName;
}
?>
<tr>
                 <td valign="top" align="center">
                  <table width="96%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                   <tr>
                    <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-weight:bold; font-size:16px; color:#666666;text-align:left;"><?php echo $heading;?></td>
                   </tr>
                   <tr>
                    <td valign="top" height="13"></td>
                   </tr>
                   <tr>
                    <td valign="top" align="center">
                     <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
			<?php
                        $anaLoopCount = 0;
		        foreach($ana['questionsDetail'] as $key=>$data){
		                if($anaLoopCount==3){
                                	break;
				}
				if($anaLoopCount==2 && $totalCount>3){ ?>
					 <tr>
                                              <td valign="top" colspan="3">
                                                <table width="100%" align="left" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
				<?php } ?>
		                           <tr>
                       <td valign="top" width="16"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/personalizedMailers/lean_mailer_bullet.png" /></td>
                       <td valign="top" colspan="2" align="left"><a href="<?php echo $data['URL'];?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#03818d; text-decoration:none;text-align:left;"><?php echo $data['title'];?></a></td>
                      </tr>
                      <?php if($anaLoopCount==2 && $totalCount>3){ ?>
                                <tr>
                                    <td valign="top" height="10" colspan="2"></td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                      <?php }else{ ?>
                      <tr>
                       <td valign="top" height="6" colspan="3"></td>
                      </tr>
                      <?php } ?>
			<?php $anaLoopCount++;} ?>
		      <?php if($totalCount>3){ ?>            
                      <tr>
                        <td valign="top" colspan="3">
                          <table width="130" border="0" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border:1px solid #03818d;">
                            <tr>
                              <td height="25" align="center"><a href="<?php echo $ana['allQuestionURL'];?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank" style="font-family:Arial, sans-serif; color:#03818d; font-size:12px;display:block; line-height:25px; font-weight:bold; text-decoration:none;text-align:center;">View All Questions</a></td>
                              
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
