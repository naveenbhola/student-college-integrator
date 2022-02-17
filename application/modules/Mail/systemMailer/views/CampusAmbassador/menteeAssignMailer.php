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
                <td width="508">  Hi <?php echo ucwords($mentorInfo[0]['firstname']);?>,<br />
                  <br />
                  I'm <?=ucwords($menteeInfo[0]['firstname']).' '.ucwords($menteeInfo[0]['lastname']); ?> planning to do engineering in <?=$menteeInfo[0]['startEngYear'] ?>.<br />
                  I would need your guidance for knowing more about engineering entrance exams, college & branch selection and admission process.</td>
              </tr>
            </table></td>
          <td width="28"><img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2013/shiksha/verify3oct/images/spacer.gif" width="10" height="1" vspace="0" hspace="0" align="left" /></td>
        </tr>
        <tr>
          <td height="11" colspan="3"></td>
        </tr>
        <tr>
          <td></td>
          <td width="508" align="center"><table border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#444648; line-height:18px; text-align:left;" width="100%">
              <tr>
                <td height="15"></td>
              </tr>
              <tr>
                <td width="508">My preferences for engineering are:<br />
                  
                  <strong>Preferred Branches:</strong> <?php if(isset($menteeInfo[0]['prefEngBranche1']) && $menteeInfo[0]['prefEngBranche1']!= ''){ echo $menteeInfo[0]['prefEngBranche1'];}?><?php if(isset($menteeInfo[0]['prefEngBranche2']) && $menteeInfo[0]['prefEngBranche2'] != ''){echo ','.' '.$menteeInfo[0]['prefEngBranche2'];} ?><?php if(isset($menteeInfo[0]['prefEngBranche3']) && $menteeInfo[0]['prefEngBranche3'] != ''){echo ','.' '.$menteeInfo[0]['prefEngBranche3'];} ?><br />
                  
                  <strong>Preferred Location:</strong> <?php if(isset($menteeInfo[0]['prefCollegeLocation1']) && $menteeInfo[0]['prefCollegeLocation1'] != ''){echo $menteeInfo[0]['prefCollegeLocation1'];}?><?php if(isset($menteeInfo[0]['prefCollegeLocation2']) && $menteeInfo[0]['prefCollegeLocation2'] != ''){echo ','.' '.$menteeInfo[0]['prefCollegeLocation2'];} ?><?php if(isset($menteeInfo[0]['prefCollegeLocation3']) && $menteeInfo[0]['prefCollegeLocation3'] != ''){echo ','.' '.$menteeInfo[0]['prefCollegeLocation3'];} ?><br />
                  
                  <?php if(isset($examString) && $examString != '' ){?>
                  <strong>Exams taken/planned:</strong> <?=$examString;?>
                  <?php } ?>
                  <br />
                  <br />
                  
                </td>
              </tr>
            </table></td>
          
          <td width="28"><img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2013/shiksha/verify3oct/images/spacer.gif" width="10" height="1" vspace="0" hspace="0" align="left" /></td>
        </tr>
        <tr>
          <td></td>
          <td><table width="508" cellspacing="0" cellpadding="0" border="0" style="font-family:Arial;font-size:13px;color:rgb(71,74,75);display:block">
              <tbody>
                <tr>
                  <td>You can see my details on your Dashboard too:</td>
                </tr>
                <tr><td height="10" colspan="3"></td></tr>
                <tr>
                  <td align="left" height="45"><a target="_blank" style="text-decoration:none;color:rgb(68,70,72);display:inline-block;font-size:14px;line-height:45px;padding:0 10px; border:1px solid #e8b363;border-radius:2px;background-color:#ffda3e;" title="Visit Your Dashboard" href="<?php echo $urlOfLandingPage;?><!-- #AutoLogin --><!-- AutoLogin# -->"><b>Visit Your Dashboard</b></a></td>
                </tr>
                <tr>
                  <td height="20" colspan="3"></td>
                </tr>
                <tr>
                <td width="508">Request you to set up a suitable time to chat with me:<br /><br />
                  <a target="_blank" style="display:block;" href="<?php echo $urlOfLandingPage;?><!-- #AutoLogin --><!-- AutoLogin# -->"><b>Set chat availability</b></a>
                  <br />
                </td>
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
            <font color="#c76d32"><?=ucwords($menteeInfo[0]['firstname']);?></font></td>
          <td></td>