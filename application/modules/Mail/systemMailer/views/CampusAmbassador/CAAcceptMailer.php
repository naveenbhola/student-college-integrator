     <td width="564" bgcolor="#ffffff" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#585c5f; text-align:left;">
        <tr>
          <td colspan="3" height="15"></td>
        </tr>
        <tr>
          <td width="28"><img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2013/shiksha/verify3oct/images/spacer.gif" width="10" height="1" vspace="0" hspace="0" align="left" /></td>
          <td width="508" align="center"><table border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#444648; line-height:18px; text-align:left;" width="100%">
              <tr>
                <td height="15"></td>
              </tr>
              <tr>
                <td width="508">Congratulations <?php echo $userDetails['firstname'];?>,<br />
                  <br />
                  You have been accepted as <?php echo (($badge=='CurrentStudent')?'Current Student':$badge);?> for <?php echo $courseObj->getName();?> in <?php echo $courseObj->getInstituteName();?>.<br />
                  <br />
                  You can now guide and mentor students interested in knowing more about your institute and get gifts and certificate of recognition.</td>
              </tr>
            </table></td>
          <td width="28"><img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2013/shiksha/verify3oct/images/spacer.gif" width="10" height="1" vspace="0" hspace="0" align="left" /></td>
        </tr>
        <tr>
          <td colspan="3" height="11"></td>
        </tr>
        <tr>
          <td></td>
          <td><table width="225" cellspacing="0" cellpadding="0" border="0" style="font-family:Arial;font-size:13px;color:rgb(71,74,75);display:block">
              <tbody>
                <tr>
                  <td width="225" bgcolor="#ffda3e" align="center" height="45" style="border:1px solid #e8b363;border-radius:2px"><a target="_blank" style="text-decoration:none;color:rgb(68,70,72);display:block;font-size:14px;line-height:45px" title="Participate in the discussion" href="<?php echo $urlOfLandingPage;?><!-- #AutoLogin --><!-- AutoLogin# -->"><b>Participate in the discussion</b></a></td>
                </tr>
              </tbody>
            </table></td>
          <td></td>
        </tr>
        <tr>
          <td height="11" colspan="3"></td>
        </tr>
        <tr>
          <td></td>
          <td bgcolor="#eeeeee" style="padding:10px;"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#444648; line-height:24px; text-align:left;">
              <tr>
                <td><strong>Your Login details are as follows</strong>:</td>
              </tr>
              <tr>
                <td>Username:<?php echo $userDetails['email']?></td>
              </tr>
              <tr>
                <td><a href="<?php echo $resetlink;?>" target="_blank" style="text-decoration:none;">Generate Password</a></td> 
              </tr>
            </table></td>
          <td></td>
        </tr>
        <tr>
          <td height="20" colspan="3"></td>
        </tr>
        <tr>
          <td></td>
          <td height="11">Best wishes,<br />
            <font color="#c76d32">Shiksha.</font><font color="#426491">com</font></td>
          <td></td>
