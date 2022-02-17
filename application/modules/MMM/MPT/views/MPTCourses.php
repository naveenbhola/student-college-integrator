<?php 
if($params['mailerDetails']['mailer_id'] == '46' || $params['mailerDetails']['mailer_id'] == '48' || $params['mailerDetails']['mailer_id'] == '121' || $params['mailerDetails']['mailer_type'] == 'MMM') { // for Reco, Newsletter, MEA and all MMM mails
    $extraParams = "?fromwhere=".$params['mailerDetails']['mailer_id']."&utm_source=Shiksha&utm_medium=email&utm_campaign=".$params['mailerDetails']['mailer_name']."_MPT<!-- #widgettracker --><!-- widgettracker# -->";
} else {
    $extraParams = "?fromwhere=".$params['mailerDetails']['mailer_id']."&utm_campaign=".$params['mailerDetails']['mailer_name']."_MPT<!-- #AutoLogin --><!-- AutoLogin# -->";
} 

if(!empty($courseInfo)) { ?>
<tr>
    <td valign="top" height="18"></td>       
</tr>
<tr>
  <td valign="top" align="center">
    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; min-width:280px;"> 
      <tbody>
        <tr>
          <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#000000; font-weight:bold; text-align:left;">Featured Colleges</td>
        </tr>

        <?php
        foreach ($courseInfo as $key => $value) {         ?>

        <tr>
          <td valign="top" height="4"></td>
        </tr>
  
        <tr>
          <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;border:1px solid #e1dddd; border-radius:2px; -moz-border-radius:2px; -webkit-border-radius:2px;">
              <tbody>
                <tr>
                  <td valign="top" align="center">                   
                    <table width="96%" align="center" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                      <tbody>
                        <tr>
                          <td valign="top" height="11"></td>
                        </tr>
                        <tr>
                          <td valign="top">

                            <table style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" width="85%" cellspacing="0" cellpadding="0" align="left">
                              <tbody>
                                <tr>
                                  <td valign="top">
                                    <table width="100%" align="left" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; min-width:265px;">
                                      <tbody>
                                        <tr>
                                          <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px;color:#000; text-align:left;"><a href="<?php echo $value['courseUrl'].$extraParams; ?>" target="_blank" style="text-decoration:none; display:block;color:#2e3030;"><?php echo $value['instituteName'] ?></a></td>
                                        </tr>
                                        <tr>
                                          <td valign="top" height="2"></td>
                                        </tr>
                                        <tr>
                                          <td valign="top" align="left">
                                           <table border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                            <tbody>
                                              <tr>
                                                <td width="25" align="left" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; color:#a0a1a1;">
                                                <?php if($value['rating']>=3.5){
                                                  echo number_format($value['rating'],1);
                                                } ?></td>
                                                
                                                <td align="left">
                                                  <?php
                                                   if(!empty($value['rating']) && $value['rating']>=3.5){
                                                    ?>
                                                    <div style="background: url('<?php echo SHIKSHA_HOME; ?>/public/images/mmm_ratingicon.png')no-repeat;background-position: 0 -10px;width: 65px;height: 10px;display: inline-block;background-size: 65px 20px;">
                                                      <div style="background-position: 0px 0px;background: url('<?php echo SHIKSHA_HOME; ?>/public/images/mmm_ratingicon.png')no-repeat;height: 10px;display: block;background-size: 65px 20px;width: <?php echo ($value['rating']*20).'%'; ?>"></div>
                                                    </div>
                                                   <?php
                                                   }
                                                  ?>
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>

                                </td>
                                <td width="10"></td>
                              </tr>
                            </tbody>
                          </table>

                          <table align="left" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                            <tbody>
                              <tr>
                                <td valign="top" height="20" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#04828c; text-decoration:underline;"><a href="<?php echo $value['courseUrl'].$extraParams; ?>" target="_blank" style="text-decoration:none; display:block;color:#04828c;">Apply Now</a></td>
                              </tr>
                            </tbody>
                          </table>

                        </td>
                      </tr>
                      
                      <tr>
                        <td valign="top" height="10"></td>
                      </tr>
                    </tbody>

                  </table>
            
                </td>
              </tr>
              <tr>
                <td valign="top" height="2" bgcolor="#e1dddd"></td>
              </tr>
              </tbody>
            </table>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </td>
</tr>

<?php 
}
?>


