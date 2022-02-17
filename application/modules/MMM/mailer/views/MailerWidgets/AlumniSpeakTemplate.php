<tr>
   <td></td>
   <td bgcolor="#ffffff">
<table width="92%" border="0" cellspacing="0" cellpadding="0" align = "center" bgcolor="#ffffff">
<tr bgcolor="#e1e4e8">
    <td height="30" style=padding-left:10px;><font face="Arial, Helvetica, sans-serif" color="#474a4b" style="font-size:16px;">Alumni Speak</font></td>
  </tr>
<tr>
<td style="padding-left: 10px;">
<?php
	foreach($alumniReviews as $reviewCriteria => $reviewCriteriaData) {
		$reviews = $reviewCriteriaData['reviews'];
		$review = $reviews[0];
		$averageRating = $reviewCriteriaData['averageRating'];
?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="font-family:Arial, Helvetica, sans-serif; color:#5d6264; font-size:14px; line-height:18px; display:block;">
        <tr>
			<td style="border-bottom:1px solid #e0e0e0;">
			<table width=100% align="left" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="font-family:Arial, Helvetica, sans-serif; color:#5d6264; font-size:14px; line-height:18px;" >
				<tr>
					<td height="40" valign="bottom" width="330">
						<font color="#444648"><strong><?=$reviewCriteria?></strong></font>
					</td>
				</tr>
			</table>
			
			<table width="172" style="font-family:Arial, Helvetica, sans-serif; color:#5d6264; font-size:14px; line-height:18px;">
				<tr>
					<td style="padding-top:19px 0px 10px;">
						Rating
						<?php
							for($i=1; $i<=5; $i++){
								if($i <= $averageRating){
									echo '<img src="'.SHIKSHA_HOME.'/public/images/starg.gif" />';
								}
								else{
									echo '<img src="'.SHIKSHA_HOME.'/public/images/stars.gif" />';
								}
							}
						?>
						<span style="font-size:12px; padding-left: 10px;"><?=$averageRating?>/5</span>
					</td>
				</tr>
			</table>
			</td>
        </tr>
        <tr>
			<td style="padding:7px 0 7px 0;">
				<strong><?=$review['reviewerName']?></strong>,
				Class of <?=$review['courseCompletionYear']?> - <?=$review['courseName']?>
			</td>
        </tr>
      </table>
	</td>
	</tr>
	<tr>
	<td style="font-family:Arial, Helvetica, sans-serif; color:#5d6264; font-size: 14px; padding-left: 10px;" >		
		<?=$review['feedback']?>
<?php
	}
?>
	</td>
</tr>

<tr>
<?php if(count($alumniReviews) > 0){ ?>
<td  height="25" style="font-family:Arial, Helvetica, sans-serif;">
    <table cellspacing="0" cellpadding="0" border="0" width="210" style="font-family:Arial; font-size:13px; color:#474a4b;">
              <tbody>
<tr>
<td height="15"></td>
</tr>
<tr>
                <td height="35" width="216" valign="top" bgcolor="#ffda3e" align="center" style="border:1px solid #e8b363; border-radius:2px;"><a style="text-decoration:none; font-size:16px; color:#4b4b4b; line-height:32px; display:block" target="_blank" title="More Alumni feedback" href="<?php echo $alumniTabUrl; ?>~alumnimore<!-- #widgettracker --><!-- widgettracker# -->"><strong>More Alumni feedback</strong> &gt;&gt;</a></td>
              </tr>
            </tbody></table>
    
    
</td>
<?php } ?>
</tr>

<tr>
<td height="30"></td>
</tr>
</table>
</td>
   <td></td>
</tr>