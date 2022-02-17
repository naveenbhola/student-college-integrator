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
    <td><b>Hi <?php echo $displayname ?>,</b><br />
<br />
We are delighted to have you as a member of the Shiksha .com  family. On the site, the popular Ask &amp; Answer section is a knowledge  reservoir where you can get answers to all your career queries. <br />
<br />
<b>More About Ask &amp;  Answer </b><br />
<table width="520" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
  <tr>
    <td height="5"colspan="2"></td>
  </tr>
  <tr>
    <td width="30" height="22" align="center">&bull;</td>
    <td>Get your questions answered by Shiksha counselors, experts and other members.</td>
  </tr>
  <tr>
    <td width="30" height="22" align="center">&bull;</td>
    <td>Answer questions and browse the section to find relevant answers.</td>
  </tr>
  <tr>
    <td width="30" height="22" align="center">&bull;</td>
    <td>Rate the answers given by others</td>
  </tr>
</table>
<br />
And a lot more! Explore <b><a href="<?php echo $StartAnsweringLink?>" style="text-decoration:underline; color:#F5840F;">Ask &amp; Answer</a></b> now.<br />
<br />
Here is a glimpse of what members are asking and answering:
</td>
  </tr>
  <tr>
    <td height="16"></td>
  </tr>
  <tr>
    <td>
<?php foreach($popularQuestions[0]['results'] as $pq){?>
<?php 
//echo "<pre>".print_r($pq,true)."</pre>"
?>
	<table width="584" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#EFF7FF" align="center" style="border:1px solid #E1E6E8; padding:5px;"><table width="560" border="0" cellspacing="0" cellpadding="0" style="text-align:left; font-size:12px;">
  <tr>
    <td width="35" valign="top"><div style="height:20px; width:22px; border:1px solid #B0D0FD;"><img src="https://mailer.shiksha.com/mailers/shiksha/askandanswer_aug_09/images/img2.gif" width="22" height="20" hspace="0" vspace="0" align="left" alt="Q." /></div></td>
    <td valign="bottom" width="525" style="padding-top:8px;"><a href="<?php echo $pq['questionLink']?>" target="_blank" style="text-decoration:none; color:#333333"><?php echo ((strlen($pq['msgTxt'])>400)?substr($pq['msgTxt'],0,400)."<span style=\"color:#0265DD;\"> read more...</span>":$pq['msgTxt'])?></a></td>
  </tr>
  <tr>
    <td height="20">&nbsp;</td>
    <td valign="bottom"><span style="color:#84868C;">Asked by</span><span style="color:#0265DD;"> <?php echo $pq['displayname']?>, <?php echo $pq['answerCount']?> Answers</span></td>
  </tr>
  <tr>
    <td height="7" colspan="2"></td>
  </tr>
    </table></td>
  </tr>
  <tr>
    <td height="16"></td>
  </tr>
</table>
	<table width="560" align="center" border="0" cellspacing="0" cellpadding="0" style="text-align:left; font-family:Arial, verdana; font-size:12px;">
      <tr>
    <td width="35"><div style="height:20px; width:22px; border:1px solid #B0D0FD; background:#EFF8FF;"><img src="https://mailer.shiksha.com/mailers/shiksha/askandanswer_aug_09/images/img3.gif" width="22" height="20" hspace="0" vspace="0" align="left" alt="Q." /></div></td>
    <td colspan="2" style="color:#757272;">By <span style="color:#0065DE;"><?php echo $pq['bestAnsArray']['Results'][$pq['threadId']]['displayname']?></span> - <span style="color:#FF7E00;"><?php echo $pq['bestAnsArray']['Results'][$pq['threadId']]['level']?></span></td>
  </tr>
	  <tr>
    <td>&nbsp;</td>
    <td colspan="2"><a href="<?php echo $pq['questionLink']?>" target="_blank" style="text-decoration:none; color:#333"><?php echo ((strlen($pq['bestAnsArray']['Results'][$pq['threadId']]['msgTxt'])>400)?substr($pq['bestAnsArray']['Results'][$pq['threadId']]['msgTxt'],0,400)."<span style=\"color:#0265DD;\"> read more...</span>":$pq['bestAnsArray']['Results'][$pq['threadId']]['msgTxt'])?></a></td>
  </tr>
	  <tr>
    <td colspan="3" height="5"></td>
  </tr>
	  <tr>
    <td>&nbsp;</td>
    <td width="369"><table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="0" style="border:1px solid #E5E3E6; text-align:center; font-family:arial; font-size:12px;">
  <tr>
    <td width="25" height="29"><?php echo $pq['bestAnsArray']['Results'][$pq['threadId']]['digUp']?></td>
    <td width="17"><img src="https://mailer.shiksha.com/mailers/shiksha/askandanswer_aug_09/images/img_up.gif" width="17" height="25" /></td>
    <td width="7"><img src="https://mailer.shiksha.com/mailers/shiksha/askandanswer_aug_09/images/img4.gif" width="7" height="25" /></td>
    <td width="25"><?php echo $pq['bestAnsArray']['Results'][$pq['threadId']]['digDown']?></td>
    <td width="20"><img src="https://mailer.shiksha.com/mailers/shiksha/askandanswer_aug_09/images/img_down.gif" width="17" height="25" align="left" /></td>
  </tr>
</table></td>
    <td>
<?php if($pq['bestAnsArray']['Results'][$pq['threadId']]['typeOfAnswer']=='best') { ?>
<table border="0" cellspacing="0" cellpadding="0" style="border:1px solid #E5E3E6; border-left:0px; text-align:center; font-family:arial; font-size:12px;">
  <tr>
    <td width="142" height="29"><img src="https://mailer.shiksha.com/mailers/shiksha/askandanswer_aug_09/images/yalo_star.gif" width="23" height="25" hspace="4" align="absmiddle" /><span style="color:#0563DD;">Voted Best Answer</span></td>
</tr>
</table>
<?php } ?>
  </td>
  </tr>
</table></td>
    <td width="156">&nbsp;</td>
  </tr>
	  <tr>
    <td height="20" colspan="3"></td>
  </tr>
    </table>
<?php } ?>
</td>
  </tr>
  <tr>
    <td background="https://mailer.shiksha.com/mailers/shiksha/askandanswer_aug_09/images/dot.gif" height="12"></td>
  </tr>
  <tr>
    <td height="16"></td>
  </tr>
  <tr>
    <td>Also, considering your profile, we think you can help enhance this knowledge pool by answering a few questions:</td>
  </tr>
  <tr>
    <td height="16"></td>
  </tr>
  <tr>
    <td>
		<?php 
			foreach($questionData as $question)
			{
		?>
		<table width="584" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#EFF7FF" align="center" style="border:1px solid #E1E6E8; padding:5px;"><table width="560" border="0" cellspacing="0" cellpadding="0" style="text-align:left; font-size:12px;">
  <tr>
    <td width="35" valign="top"><div style="height:20px; width:22px; border:1px solid #B0D0FD;"><img src="https://mailer.shiksha.com/mailers/shiksha/askandanswer_aug_09/images/img2.gif" width="22" height="20" hspace="0" vspace="0" align="left" alt="Q." /></div></td>
    <td valign="bottom" width="525" style="padding-top:8px;"><a href="<?php echo $question['questionLink']?>" target="_blank" style="text-decoration:none; color:#333333"><?php echo ((strlen($question['questiontext'])>400)?substr($question['questiontext'],0,400)."<span style=\"color:#0265DD;\"> read more...</span>":$question['questiontext'])?></a></td>
  </tr>
  <tr>
    <td height="20">&nbsp;</td>
    <td valign="bottom"><span style="color:#84868C;">Asked by</span><span style="color:#0265DD;"> <?php echo $question['askedBy']?>, <?php echo $question['numAnswers']?> Answers</span></td>
  </tr>
  <tr>
    <td height="7" colspan="2"></td>
  </tr>
    </table></td>
  </tr>
  <tr>
    <td height="16"></td>
  </tr>
  <tr>
    <td align="right"><table width="93" border="0" cellspacing="0" cellpadding="0" style="margin-right:20px;">
              <tr>
                <td height="23" align="center" valign="top" bgcolor="#F5840F" background="https://mailer.shiksha.com/mailers/shiksha/askandanswer_aug_09/images/org_sml_button.gif"><div style="font-family: Arial, verdana; font-size:12px; line-height:23px; text-align:center;"><a href="<?php echo $question['AnswerLink']?>" target="_blank" style="text-decoration:none; color:#ffffff;"><b>Answer Now</b></a></div></td>
              </tr>
            </table></td>
  </tr>
  <tr>
    <td height="16"></td>
  </tr>
</table>
		<?php }?>
	</td>
  </tr>
  <tr>
    <td height="16">More the number of questions you answer, higher your chances of becoming a Shiksha expert. So, why wait? <a href="<?php echo  $StartAnsweringLink?>" style="text-decoration:underline; color:#F5840F;"><b>Start answering now</b></a>.</td>
  </tr>
  <tr>
    <td height="16"></td>
  </tr>
  <tr>
    <td style="font-size:13px; font-family: Arial,verdana;">Best regards,<br />
      Shiksha.com team<br /></td>
  </tr>
  <tr>
    <td height="16"></td>
  </tr>
    </table></td>
  </tr>
</table>
</table>
