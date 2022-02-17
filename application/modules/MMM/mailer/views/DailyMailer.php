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
    <td><b>Hi <?php echo $displayname?>,</b><br/><br/>
Thank you for visiting Shiksha<span style="font-size: 2px;"> </span>.com Ask & Answers and <?php echo $action?>.As you may already know you earn 10 points for answering a question and and 10 bonus points if your answer get 10 upvotes. Moreover if your answer gets 100 upvotes then its qualified as a High Quality(HQ) answer and you earn additional 30 points. Accumulating these points will help you advance your level on Shiksha and make recognition for yourself as an expert.<br/><br/> Below are some of the questions we think you can answer and help other Shiksha<span style="font-size: 2px;"> </span>.com users. Please write an answer and share your knowledge.</td>
  </tr>
  <tr>
    <td height="16"></td>
  </tr>
  <tr>
    <td>
<?php foreach($questionData as $question){?>
<?php 
//echo print_r($question,true)
?>
<table width="584" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#EFF7FF" align="center" style="border:1px solid #E1E6E8; padding:5px;"><table width="560" border="0" cellspacing="0" cellpadding="0" style="text-align: left; font-family: Arial,verdana; font-size: 12px;">
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
<?php } ?>
	</td>
   </tr>
  <tr>
    <td height="16">More the number of questions you answer, higher your chances of becoming a Shiksha expert. So, why wait? <a href="<?php echo $StartAnsweringLink?>" style="text-decoration:underline; color:#F5840F;"><b>Start answering now</b></a>.</td>
  </tr>
  <tr>
    <td height="16"></td>
  </tr>
  </table></td>
  </tr>
  <tr>
    <td colspan="2" style="font-size:13px; font-family: Arial,verdana; padding-left:10px;">Best regards,<br />
      Shiksha.com team<br /></td>
  </tr>
  <tr>
    <td height="16" colspan="2"></td>
  </tr>
</table>
