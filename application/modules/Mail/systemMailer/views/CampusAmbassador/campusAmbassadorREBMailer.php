<td width="564" bgcolor="#ffffff" align="center">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#585c5f;">
          <tr>
            <td colspan="3" height="20"></td>
          </tr>
          <tr>
            <td width="20"><img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2013/shiksha/verify3oct/images/spacer.gif" width="6" height="1" vspace="0" hspace="0" align="left" /></td>
            <td width="524" align="center">
            	<table border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#444648; line-height:18px; text-align:left;" width="100%">
                  <tr>
                    <td>Dear <?php echo $userDetails['firstname'];?> <?php echo $userDetails['lastname'];?>,</td>
                  </tr>
                  <tr>
                    <td height="15"></td>
                  </tr>
                  <tr>
                    <td width="524">We now have a current student to address your queries for <?php echo $courseObj->getName();?>, <?php echo $courseObj->getInstituteName();?>.</td>
                  </tr>
                </table>
			</td>
            <td width="20"><img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2013/shiksha/verify3oct/images/spacer.gif" width="6" height="1" vspace="0" hspace="0" align="left" /></td>
          </tr>
          <tr>
            <td></td>
            <td valign="top">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="display:block;">
                  <tr>
                    <td height="15"></td>
                  </tr>
                  <tr>
                    <td width="524" valign="top">
                    	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
                          <tr>
                            <td width="72" valign="top"><img src="<?php if($caUserInfo[0]['ca']['imageURL']!=''){ echo $caUserInfo[0]['ca']['imageURL'];}else{ echo SHIKSHA_HOME.'/public/images/dummyImg.gif';}?>" width="62" height="52" vspace="0" hspace="0" align="left" style="border:1px solid #e4e6e9;" /></td>	
                            <td width="452">
                            	<table border="0" cellspacing="0" cellpadding="0" align="left" style="font-family:Arial; font-size:14px; color:#474a4b;" width="100%">
                          <tr>
                            <td height="22">
                            	<font color="#444648"><strong><?php echo $caUserInfo[0]['ca']['displayName'];?></strong></font>
                                <?php if($badge!=''){ ?>
                            	<span style="background:#fff; color:#555555; text-transform:uppercase; font:bold 9px Tahoma, Geneva, sans-serif; padding:4px 6px; display:inline-block; letter-spacing:1px; border: 1px solid #c4c4c4"><?php echo $badge;?></span>
                                <?php } ?>
                            </td>
                          </tr>
                          <tr>
                            <td height="3"></td>
                          </tr>
                          <tr>
                            <td><?php echo $courseObj->getInstituteName();?></td>
                          </tr>
                        </table>
                            
                            </td>
                          </tr>
                          <tr>
                          	<td colspan="2" height="18"></td>
                          </tr>
                          <tr>
                          	<td colspan="2"><table width="237" cellspacing="0" cellpadding="0" border="0" style="font-family:Arial;font-size:13px;color:rgb(71,74,75);display:block">
    <tbody><tr>
      <td width="237" bgcolor="#ffda3e" align="center" height="40" style="border:1px solid #e8b363;border-radius:2px"><a target="_blank" style="text-decoration:none;color:rgb(75,75,75);display:block;font-size:14px;line-height:38px" title="Read more" href="<?php echo $autoLoginUrl;?>"><b>Ask your question</b></a></td>
    </tr>
  </tbody></table></td>
                          </tr>
                        </table>
						

                    </td>
                  </tr>
                </table>
            </td>
            <td></td>
