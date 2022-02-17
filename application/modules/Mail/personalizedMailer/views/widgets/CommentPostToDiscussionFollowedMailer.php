

<tr>
  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top" bgcolor="#f1f1f2"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #e8e8e8; border-radius:5px 5px 5px 5px; background-color:#ffffff; box-shadow: -2px 0 5px #e0e0e0;">
            <tr>
              <td width="24"></td>
              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="30"></td>
                  </tr>
                  <tr>
                    <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:12px; color:#333333;">
                    	<strong>Last 24 hour activity on the Discussions you follow</strong></td>
                  </tr>

                  <?php foreach($result['discussionDetails'] as $key=>$discussionDetail){?>
                  <tr>
                    <td height="15"></td>
                  </tr>

                  <tr>
                    <td align="left">
                    	<table border="0" cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr>
                              <td width="22" align="left" valign="top">
                              	<img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2016/shiksha/app-11may/images/comm.jpg" width="22" height="15" alt=""/>
                              </td>
                              <td style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:12px; line-height:17px; color:#666666;"><strong><?=$discussionDetail['discussionTxt']?></strong></td>
                            </tr>
                          </tbody>
                        </table>
                    </td>
                  </tr>
                  <tr>
                    <td height="10"></td>
                  </tr>
                  <tr>
                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="44%" align="center" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:17px; color:#666666;">New Comments in last 24 hours :<font style="color:#333333;"><?=$discussionDetail['commentCountIn24']?></font></td>
                          
                          <td width="28%" align="center" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:11px; line-height:17px; color:#666666; border-left:1px solid #e1e1e1;border-right:1px solid #e1e1e1;">Total comments :<font style="color:#333333;"><?=$discussionDetail['totalCommentCount']?></font></td>
                          <td width="28%" align="center" style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:12px; line-height:17px; color:#009999;"><a style="font-family:open sens, Tahoma, Helvetica, sans-serif; font-size:12px; line-height:17px; color:#009999; text-decoration:none;" href="<?=$discussionDetail['seoUrl']?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank">View all comments</a></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td height="20" style="border-bottom:1px solid #e3e3e3;"></td>
                  </tr>
                  <?php } ?>

                  <tr>
                    <td height="15"></td>
                  </tr>
                  
                  <tr>
                    <td height="15"></td>
                  </tr>
                </table></td>
              <td width="25"></td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td height="14" align="top"></td>
      </tr>
    </table></td>
</tr>





















