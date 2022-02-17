<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>
<body>
<table cellspacing="0" cellpadding="0" border="0" align="center" style="max-width:600px;text-align:center">
  <tbody>
    <tr>
      <td width="600" style="padding-bottom:10px"><font face="Arial" color="#9d9d9d" style="font-size:12px">This email is sent to you because you are Shiksha<span style="font-size:1px;"> </span>.com member</font></td>
    </tr>
  </tbody>
</table>
 <table border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#e3e5e8" style="max-width:600px; -webkit-text-size-adjust: 100%;">
  <tr>
    <td></td>
    <td>
    	<table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tbody><tr>
        <td align="right" style="padding:10px 5px;text-align: right;">
          <table width="290" cellspacing="0" cellpadding="0" border="0" align="right">
            <tbody><tr>
              <!--<td width="125"><a target="_blank" style="font-family:Arial;font-size:13px;color:#0065e8;text-decoration:none" href="#">View mail in browser</a></td>-->
              <td align="right" style="text-align: right;"><a target="_blank" style="font-family:Arial;font-size:12px;color:#0065e8;text-decoration:none" href="<!-- #Unsubscribe --><!-- Unsubscribe# -->">Unsubscribe</a></td>
              <!--<td width="82" align="center" style="border-left:1px solid #b2b2b2"><!--<a target="_blank" style="font-family:Arial;font-size:12px;color:#0065e8;text-decoration:none" href="#">Report spam</a></td>-->
            </tr>
          </tbody></table></td>
      </tr>
    </tbody></table>
    </td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td valign="bottom"><table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#333333">
        <tr>
          <td valign="top" width="280"><img src="<?php echo IEPLADS_DOMAIN;?>/mailers/2013/shiksha/verify3oct/images/img1.gif" width="6" height="6" vspace="0" hspace="0" align="left" /></td>
          <td valign="top" width="280" align="right"><img src="<?php echo IEPLADS_DOMAIN;?>/mailers/2013/shiksha/verify3oct/images/img2.gif" width="6" height="6" vspace="0" hspace="0" align="right" /></td>
        </tr>
      </table></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td bgcolor="#333333" style="padding-left:18px;" height="75"><a href="https://www.shiksha.com/" target="_blank"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/mailer_logo_2015_white.png" width="214" height="48" vspace="0" hspace="0" align="left" border="0" title="shiksha.com" alt="Shiksha.com" style="font-family:Arial, Helvetica, sans-serif; font-size:30px; color:#fdb350; font-weight:bold;" /></a></td>
    <td></td>
  </tr>
  <tr>
    <td width="18"><img src="<?php echo IEPLADS_DOMAIN;?>/mailers/2013/shiksha/verify3oct/images/spacer.gif" width="8" height="1" vspace="0" hspace="0" align="left"></td>
    <td width="564" bgcolor="#ffffff" align="center">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#585c5f;">
          <tr>
            <td colspan="3" height="20"></td>
          </tr>
          <tr>
            <td width="20"><img src="<?php echo IEPLADS_DOMAIN;?>/mailers/2013/shiksha/verify3oct/images/spacer.gif" width="6" height="1" vspace="0" hspace="0" align="left" /></td>
            <td width="524" align="center">
            	<table border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:14px; color:#444648; line-height:18px; text-align:left;" width="100%">
                  <tr>
                    <td>Hi <strong><?php echo $firstname;?>!</strong></td>
                  </tr>
                  <tr>
                    <td height="15"></td>
                  </tr>
                  <tr>
                    <td width="524">You showed interest in a career as <?php echo checkifvowel($mainCareerName);?>&nbsp;<strong><?php echo $mainCareerName;?></strong>. We thought you might consider these career options as well:</td>
                  </tr>
                </table>
			</td>
            <td width="20"><img src="<?php echo IEPLADS_DOMAIN;?>/mailers/2013/shiksha/verify3oct/images/spacer.gif" width="6" height="1" vspace="0" hspace="0" align="left" /></td>
          </tr>
          <tr>
            <td></td>
            <td valign="top">
            <?php if(!empty($finalArr)){
                    foreach($finalArr as $key=>$value){
                            $minSalInLacs= $value['minSalInLacs'];
                            $maxSalInLacs= $value['maxSalInLacs'];
                            $minSalInThousand= $value['minSalInThousand'];
                            $maxSalInThousand= $value['maxSalInThousand'];
                            $difficulityLevel = $value['difficulityLevel'];
                            $autoLoginURL = $value['autoLoginURL'];
                            $careerName = $value['careerName'];
                            $imageUrl = $value['imageUrl'];
			    $shortDescription = $value['shortDescription'];
                    ?>
            	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="display:block;">
                  <tr>
                    <td height="15"></td>
                  </tr>
                  <tr>
                    <td width="524" valign="top">
                    	<table width="140" border="0" cellspacing="0" cellpadding="0" align="left">
                          <tr>
                            <td><img src="<?php echo $imageUrl;?>" width="120" height="139" vspace="0" hspace="0" align="left" /></td>
                          </tr>
                          <tr>
                            <td height="5">&nbsp;</td>
                          </tr>
                        </table>
						<table border="0" cellspacing="0" cellpadding="0" align="left" style="font-family:Arial; font-size:13px; color:#474a4b; max-width:377px;">
                          <tr>
                            <td><a href="<?php echo $autoLoginURL;?><!-- #AutoLogin --><!-- AutoLogin# -->" target="_blank" style="font-family:Georgia, 'Times New Roman', Times, serif; color:#0065e8; font-size:20px; text-decoration:none;"><font color="#0065e8"><?php echo $careerName;?></font></a></td>
                          </tr>
                          <tr>
                            <td width="377"><?php echo substr($shortDescription,0,170);?> ...</td>
                          </tr>
                          <tr>
                            <td height="5"></td>
                          </tr>
			  <?php
			    if(!empty($minSalInLacs) || !empty($maxSalInLacs) || !empty($minSalInThousand) || !empty($maxSalInThousand))
			    {
				    $minSalToDisplay = '';
				    if($minSalInLacs>1){$minLakh = ' Lakhs ';}else{$minLakh = ' Lakh ';}
				    if($maxSalInLacs>1){$maxLakh = ' Lakhs ';}else{$maxLakh = ' Lakh ';}
				    if(!empty($minSalInLacs)){$minSalToDisplay=$minSalInLacs+($minSalInThousand/100).$minLakh; }else{$minSalToDisplay=$minSalInThousand.' Thousand ';}
				    $maxSalToDisplay = '';
				    if(!empty($maxSalInLacs)){$maxSalToDisplay=$maxSalInLacs+($maxSalInThousand/100).$maxLakh; }else{$maxSalToDisplay=$maxSalInThousand.' Thousand ';}
			    ?>
                          <tr>
                            <td style="font-size:12px; color:#525252;"><img src="<?php echo SHIKSHA_HOME;?>/public/images/careers/rupeeIC.gif" width="18" height="16" vspace="0" hspace="0" align="absmiddle" /><font color="#7e7e7e">Salary:</font>
			    <strong>
				<?php if((!empty($minSalInLacs) || !empty($minSalInThousand)) && (!empty($maxSalInLacs) || !empty($maxSalInThousand))){
				echo $minSalToDisplay;?> to <?php echo $maxSalToDisplay;?> per annum
				<?php } ?>

				<?php if((empty($minSalInLacs) && empty($minSalInThousand)) && (!empty($maxSalInLacs) || !empty($maxSalInThousand))){
				?> Up to <?php echo $maxSalToDisplay;?> per annum
				<?php } ?>

				<?php if((!empty($minSalInLacs) || !empty($minSalInThousand)) && (empty($maxSalInLacs) && empty($maxSalInThousand))){
				?> Starts from <?php echo $minSalToDisplay;?> per annum
				<?php } ?>	
				
			    </strong></td>
                          </tr>
			   <?php 
			    }
			    ?>
                          <tr>
                            <td height="5"></td>
                          </tr>
                          <tr>
			    <?php if($difficulityLevel=='High'){
				$color = "color:#b40000";
			    }else if($difficulityLevel=='Medium'){
				$color = "color:#169406";
			    }else{
				$color = "color:#169406";
			    }
			    ?>
                            <td style="font-size:12px;"><img src="<?php echo SHIKSHA_HOME;?>/public/images/careers/acadmicIC.gif" width="18" height="16" vspace="0" hspace="0" align="absmiddle" /><font color="#7e7e7e">Academic Difficulty:</font> <font style="<?php echo $color;?>"><strong><?php echo $difficulityLevel;?></strong></font></td>
                          </tr>
                          <tr>
                            <td height="8"></td>
                          </tr>
                          <tr>
                            <td>
                            	<table width="100" cellspacing="0" cellpadding="0" border="0" style="font-family:Arial;font-size:13px;color:rgb(71,74,75);display:block">
    <tbody><tr>
      <td width="100" bgcolor="#ffda3e" align="center" height="28" style="border:1px solid #e8b363;border-radius:2px"><a target="_blank" style="text-decoration:none;color:rgb(75,75,75);display:block;font-size:14px;line-height:26px" title="Read more" href="<?php echo $autoLoginURL;?><!-- #AutoLogin --><!-- AutoLogin# -->"><b>Read more</b></a></td>
    </tr>
  </tbody></table>
                            </td>
                          </tr>
                        </table>

                    </td>
                  </tr>
                  <tr>
                  	<td height="8" style="border-bottom:1px solid #e4e4e4;"><img src="<?php echo IEPLADS_DOMAIN;?>/mailers/2013/shiksha/verify3oct/images/spacer.gif" width="1" height="8" vspace="0" hspace="0" align="left" /></td>
                  </tr>
                </table>
       <?php }} ?>
			</td>
            <td></td>
          </tr>
        </table>
    </td>
    <td width="18"><img src="<?php echo IEPLADS_DOMAIN;?>/mailers/2013/shiksha/verify3oct/images/spacer.gif" width="8" height="1" vspace="0" hspace="0" align="left"></td>
  </tr>
  <tr>
    <td></td>
    <td bgcolor="#ffffff" height="28"></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td bgcolor="#ffffff" align="center"><table width="93%" border="0" 
cellspacing="0" cellpadding="0" bgcolor="#333333" style="font-size:12px; color:#FFFFFF; text-align:left;">
        <tr>
          <td width="6"></td>
          <td align="left" style="padding:6px 0;"><font face="Georgia, Times New Roman, Times, serif" color="#ffffff">Want to explore more about your desired career? <strong><a
href="<?php echo CAREER_HOME_PAGE;?><!-- #AutoLogin --><!-- AutoLogin# -->" 
target="_blank" style="font-size:12px; text-decoration:underline; color:#ffe474;">Go to Career Central now!</a></strong></font></td>
        </tr>
      </table></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td bgcolor="#FFFFFF" align="center"><table width="92%" border="0" cellspacing="0" cellpadding="0" style="font-size:12px; color:#FFFFFF; text-align:left;">
        <tr>
          <td height="10"></td>
        </tr>
        <tr>
          <td width="501"><font face="Tahoma, Arial, sans-serif" color="#9d9d9d" style="font-size:10px; line-height:14px;">This is a system generated email, please do not reply. For more information, visit <a href="https://www.shiksha.com/shikshaHelp/ShikshaHelp/termCondition" target="_blank" style="font-size:10px; text-decoration:none; color:#3465e8;">Terms &amp;
            Conditions</a><br>
            <br>
            You received this email because you're Shiksha<span style="font-size:1px;"> </span>.com member. You can <a href="<!-- #Unsubscribe --><!-- Unsubscribe# -->" target="_blank" style="font-size:10px; text-decoration:none; color:#3465e8;">unsubscribe</a> after logging in to your account.</font></td>
        </tr>
        <tr>
          <td height="10"></td>
        </tr>
      </table></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td><table width="100%" border="0" cellspacing="0" bgcolor="#FFFFFF" cellpadding="0">
        <tr>
          <td valign="bottom"><img src="<?php echo IEPLADS_DOMAIN;?>/mailers/2013/shiksha/verify3oct/images/img9.gif" width="6" height="6" vspace="0" hspace="0" align="left" /></td>
          <td></td>
          <td valign="bottom"><img src="<?php echo IEPLADS_DOMAIN;?>/mailers/2013/shiksha/verify3oct/images/img10.gif" width="6" height="6" vspace="0" hspace="0" align="right" /></td>
        </tr>
      </table></td>
    <td></td>
  </tr>
  <tr>
    <td height="20" colspan="3"></td>
  </tr>
</table>
</body>
</html>
