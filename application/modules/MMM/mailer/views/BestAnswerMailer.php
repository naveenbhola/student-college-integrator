<table width="600" border="0" cellspacing="0" cellpadding="0" align="center" style="border:1px solid #ccc;">
  <tr>
    <td colspan="2" align="right"><img src="https://mailer.shiksha.com/mailers/shiksha/askandanswer_aug_09/images/naukri_logo.gif" width="186" height="19" hspace="0" vspace="0" align="right" alt="a naukri.com group company" /></td>
  </tr>
  <tr>
  	<td><img src="https://mailer.shiksha.com/mailers/shiksha/askandanswer_aug_09/images/logo.gif" width="278" height="75" hspace="0" vspace="0" align="right" alt="Shiksha.com" /></td>
    <td><img src="https://mailer.shiksha.com/mailers/shiksha/askandanswer_aug_09/images/img1.gif" width="322" height="75" hspace="0" vspace="0" align="left" /></td>
  </tr>
  <tr>
    <td colspan="2"><table width="584" border="0" cellspacing="0" cellpadding="0" align="center" style="text-align:left; font-family:Arial, verdana; font-size:12px;">
  <tr>
    <td height="30"></td>
  </tr>
  <tr>
    <td><b>Hi <?php echo $displayName?>,</b><br />
<br />
You've received <?php echo $numAnswer?> answers for the question you posted in the Ask &amp; Answer section on Shiksha<span style="font-size:3px;"> </span>.com<br />
<br />

Shiksha<span style="font-size:3px;"> </span>.com allows you to choose one of the answers as the Best Answer. By selecting the best answer you express appreciation for the efforts taken by Shiksha community members in resolving your query and help build a mutually collaborative community.</td>
  </tr>
  <tr>
    <td height="16"></td>
  </tr>
  <tr>
    <td><b>Please click on the link below to select the best answer for your question and also <span style="color:#FF8200;">earn 20 bonus points.</span></b></td>
  </tr>
  <tr>
    <td height="16"></td>
  </tr>
  <tr>
    <td bgcolor="#EFF7FF" align="center" style="border:1px solid #E1E6E8; padding:5px;"><table width="560" border="0" cellspacing="0" cellpadding="0" style="text-align:left; font-size:12px; font-family:arial;">
  <tr>
    <td width="35" valign="top"><div style="height:20px; width:22px; border:1px solid #B0D0FD;"><img src="https://mailer.shiksha.com/mailers/shiksha/askandanswer_aug_09/images/img2.gif" width="22" height="20" hspace="0" vspace="0" align="left" alt="Q." /></div></td>
    <td valign="bottom" width="525" style="padding-top:8px;"><a href="<?php echo $questionLink?>" target="_blank" style="text-decoration:none; color:#333333"><?php echo ((strlen($questiontext)>400)?substr($questiontext,0,400)."<span style=\"color:#0265DD;\"> read more...</span>":$questiontext)?></a></td>
  </tr>
  <tr>
    <td height="20">&nbsp;</td>
    <td valign="bottom"><span style="color:#84868C;">Asked by</span><span style="color:#0265DD;"> <?php echo $displayName?>, <?php echo $numAnswer?> Answers</span></td>
  </tr>
<?php if($numAnswer==5)
{
?>
  <tr>
    <td height="7" colspan="2"></td>
  </tr>
    </table></td>
  </tr>
  <tr>
    <td height="16"></td>
  </tr>
  <tr>
    <td><b style="color:#F78717;">Answers for <?php echo $displayName?></b> (<?php echo $numAnswer?>)</td>
  </tr>
  <tr>
    <td background="https://mailer.shiksha.com/mailers/shiksha/askandanswer_aug_09/images/dot.gif" height="12"></td>
  </tr>

<?php
for($i=0;$i<$numAnswer;$i++)
{
?>
  <tr>
    <td><table width="560" align="center" border="0" cellspacing="0" cellpadding="0" style="text-align:left; font-family:Arial, verdana; font-size:12px;">
      <tr>
    <td width="35"><div style="height:20px; width:22px; border:1px solid #B0D0FD; background:#EFF8FF;"><img src="https://mailer.shiksha.com/mailers/shiksha/askandanswer_aug_09/images/img3.gif" width="22" height="20" hspace="0" vspace="0" align="left" alt="Q." /></div></td>
    <td colspan="2" style="color:#757272;">By <span style="color:#0065DE;"><?php echo $answerBy[$i]?></span> - <span style="color:#FF7E00;"><?php echo $userLevel[$i]?></span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2"><a href="<?php echo $questionLink?>" target="_blank" style="text-decoration:none; color:#333"><?php echo ((strlen($answerText[$i])>400)?substr($answerText[$i],0,400)."<span style=\"color:#0265DD;\"> read more...</span>":$answerText[$i])?></a></td>
  </tr>
  <tr>
    <td colspan="3" height="5"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="369"><table width="100" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #E5E3E6; text-align:center; font-size:12px; font-family:arial;">
  <tr>
    <td><?php echo $digUp[$i]?></td>
    <td><img src="https://mailer.shiksha.com/mailers/shiksha/askandanswer_aug_09/images/img_up.gif" width="17" height="25" /></td>
    <td><img src="https://mailer.shiksha.com/mailers/shiksha/askandanswer_aug_09/images/img4.gif" width="7" height="25" /></td>
    <td><?php echo $digDown[$i]?></td>
    <td><img src="https://mailer.shiksha.com/mailers/shiksha/askandanswer_aug_09/images/img_down.gif" width="17" height="25" /></td>
  </tr>
</table>
</td>
    <td width="156"><img src="https://mailer.shiksha.com/mailers/shiksha/askandanswer_aug_09/images/img_star.gif" width="20" height="20" vspace="2" hspace="3" align="absmiddle" /><a href="<?php echo $bestAnswerLink[$i]?>" style="text-decoration:none; color:#0563DD;">Select as Best Answer</a></td>
  </tr>
  <tr>
    <td height="20" colspan="3"></td>
  </tr>
    </table>
<?php
}?></td>
  </tr>
  <tr>
    <td background="https://mailer.shiksha.com/mailers/shiksha/askandanswer_aug_09/images/dot.gif" height="12"></td>
  </tr>

<?
	}
else
{
?>
<tr>
    <td height="7" colspan="2"></td>
  </tr>
    </table></td>
  </tr>
  <tr>
    <td height="16" colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2" style="padding-left:10px;"><table width="173" border="0" cellspacing="0" cellpadding="0" style="margin-right:20px;">
              <tr>
                <td height="23" valign="top" bgcolor="#F5840F" background="https://mailer.shiksha.com/mailers/shiksha/askandanswer_aug_09/images/org_b_button.gif"><div style="font-family: Arial, verdana; font-size:12px; line-height:23px; text-align:center; padding-left:18px;"><a href="<?php echo $questionLink?>" target="_blank" style="text-decoration:none; color:#ffffff;"><b>Select a Best Answer</b></a></div></td>
              </tr>
            </table></td>
  </tr>
	<?php }?>
  <tr>
    <td height="16" colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2" style="font-size:13px; font-family: Arial,verdana; padding-left:10px;">Best regards,<br />
      Shiksha.com team<br /></td>
  </tr>
  <tr>
    <td height="16" colspan="2"></td>
  </tr>
</table>