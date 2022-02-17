<?php 
  if(!empty($courseName)){
    $msgString =  $courseName.', '.$instituteName;
  }else{
    $msgString =  $instituteName;
  }
?>
    <td width="564" bgcolor="#ffffff" align="center">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#585c5f;">
          <tr>
            <td colspan="3" height="20"></td>
          </tr>
          <tr>
            <td width="20"><img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2013/test/AP20decShik/images/spacer.gif" width="6" height="1" vspace="0" hspace="0" align="left" /></td>
            <td width="524">
            	<table border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#44c4648; line-height:18px; text-align:left;" width="93%">
                  <tr>
                    <td>Hi <?=$campusRepName;?>, </td>
                  </tr>
                  <tr>
                    <td height="15"></td>
                  </tr>
                  <tr>
                    <td width="500">A question has been posted by <strong><?= $name;?></strong> related to <strong> <?=$msgString; ?></strong>:</td>
                  </tr>
		  <tr>
                    <td height="15"></td>
                  </tr>
                </table>
			</td>
            <td width="20"><img src="<?php echo IEPLADS_DOMAIN; ?>/mailers/2013/test/AP20decShik/images/spacer.gif" width="6" height="1" vspace="0" hspace="0" align="left" /></td>
          </tr>
          <tr>
            <td colspan="3" height="12"></td>
          </tr>
          <tr>
            <td></td>
            
            <td></td>
          </tr>
          <tr>
            <td colspan="3" height="5"></td>
          </tr>
          <tr>
            <td></td>
            <td valign="top">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="display:block; border:1px solid #ececec; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#474a4b;" bgcolor="#f0f0f0">
                  <tr>
                    <td width="73" bgcolor="#ffffff" align="center"><font face="Georgia, Times New Roman, Times, serif" style="font-size:33px;" color="#e2e2e2">Q.</font></td>
                    <td width="15"><img src="<?php echo SHIKSHA_HOME_URL;?>/public/images/mailer/nobe.gif" width="10" height="30" vspace="0" hspace="0" align="left" /></td>
                    <td width="435" style="padding:5px 0;">
                        <a href="<?=$urlOfLandingPage;?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank" style="text-decoration:none; color:#474a4b">
                        <?=nl2br($msgTxt); ?>
                        </a>
                    </td>
                  </tr>
                </table>
            </td>
            <td></td>
          </tr>
         
          <tr>
            <td colspan="3" height="12"></td>
          </tr>
          <tr>
            <td></td>
            <td valign="top">
            	<table width="145" cellspacing="0" cellpadding="0" border="0" style="font-family:Arial;font-size:13px;color:rgb(71,74,75);display:block">
    <tbody><tr>
      <td width="145" bgcolor="#ffda3e" align="center" height="40" style="border:1px solid #e8b363;border-radius:2px"><a target="_blank" style="text-decoration:none;color:rgb(75,75,75);display:block;font-size:14px;line-height:38px" title="Read more" href="<?=$urlOfLandingPage;?><!-- #AutoLogin --><!-- AutoLogin# -->"<b>Answer this now!</b></a></td>
    </tr>
  </tbody></table>
            </td>
            <td></td>
          
     
