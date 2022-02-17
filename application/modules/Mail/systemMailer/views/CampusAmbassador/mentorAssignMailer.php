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
                <td width="508">  Hi <?php echo ucwords($menteeInfo[0]['firstname']);?>,<br />
                  <br />
                  I'm <?=ucwords($mentorInfo[0]['firstname']).' '.ucwords($mentorInfo[0]['lastname']);?> from <?=$collegeName; ?> & studying <?=$courseName; ?>. I'm in my <?=$mentorInfo[0]['semester']; ?> semester.<br/>
                  
Shiksha has matched me to be your mentor based on your preferred location, branch & entrance exams. I will guide you on engineering exams preparation, admission process and branch/college selection.<br />
<br/>
		Feel free to ask questions and scheduling chat sessions right away from your<a target="_blank" style="font-size:14px;" href="<?php echo $menteeLandingPage;?><!-- #AutoLogin --><!-- AutoLogin# -->"><b> Accounts & Settings</b></a> page on your desktop or "Connect with my mentor" link on the right panel on your mobile.<br />
                <br/>
                  </td>
              </tr>
            </table></td>
          <td width="28"><img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2013/shiksha/verify3oct/images/spacer.gif" width="10" height="1" vspace="0" hspace="0" align="left" /></td>
        </tr>
        
        <tr>
          <td height="11" colspan="3"></td>
        </tr>
        <tr>
          <td></td>
          <td bgcolor="#eeeeee" style="padding:10px;"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#444648; line-height:24px; text-align:left;">
              <tr>
                <td><strong>Your Login details are</strong>:</td>
              </tr>
              <tr>
                <td>Username: <?php echo $menteeInfo[0]['email']?></td>
              </tr>
              <tr>
                <td>Password: <?php echo $menteeInfo[0]['textpassword'];?></td>
              </tr>
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
                <td><strong>Get to know me better, my details are as below</strong>:</td>
              </tr>
              <tr>
                <td><?php echo ucwords($mentorInfo[0]['firstname']).' '.ucwords($mentorInfo[0]['lastname']); ?></td>
              </tr>
              <tr>
                <td><?php echo $collegeName;?></td>
              </tr>
              <tr>
                <td><?php echo $courseName;?></td>
              </tr>
            </table></td>
          <td></td>
        </tr>
        <tr>
          <td height="20" colspan="3"></td>
        </tr>
        <tr>
          <td></td>
          <td><table width="508" cellspacing="0" cellpadding="0" border="0" style="font-family:Arial;font-size:13px;color:rgb(71,74,75);display:block">
              <tbody>
                <tr>
                  <td>Check out my profile:</td>
                </tr>
                <tr><td height="10" colspan="3"></td></tr>
                <tr>
                  <td align="left" height="45"><a target="_blank" style="text-decoration:none;color:rgb(68,70,72);display:inline-block;font-size:14px;line-height:45px;padding:0 10px; border:1px solid #e8b363;border-radius:2px;background-color:#ffda3e;" title="Visit Your Dashboard" href="<?php echo $userProfilePageUrl;?><!-- #AutoLogin --><!-- AutoLogin# -->"><b>View Profile</b></a></td>
                </tr>
               
              </tbody>
            </table></td>
          <td></td>
        </tr>
        
        <tr>
          <td height="20" colspan="3"></td>
        </tr>
        <tr>
          <td></td>
          <td height="11">Best Regards,<br />
            <font color="#c76d32"><?=ucwords($mentorInfo[0]['firstname']);?></font><font color="#426491"></font></td>
          <td></td>