<tr>
<td></td>
<td><font face="Georgia, Times New Roman, Times, serif" style="font-size:18px;">What's popular among users</font></td>
<td></td>
</tr>

<?php foreach($discussions as $discussion) { ?>

<tr>
<td colspan="3" height="8"></td>
</tr>
<tr>
<td></td>
<td>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="display:block; border:1px solid #ececec; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#474a4b;" bgcolor="#f0f0f0">
  <tr>
	<td width="63" bgcolor="#ffffff" align="center" valign="top" rowspan="2">
	<?php
	if($discussion['fromOthers'] == 'user') {
		echo '<font face="Georgia, Times New Roman, Times, serif" style="font-size:93px;" color="#e2e2e2">?</font>';	
	}
	else {
		echo '<img src="'.SHIKSHA_HOME.'/public/images/newsletter/comIC.gif" width="54" height="79" vspace="0" hspace="0" align="absmiddle" />';
	}
	?>
	</td>
	<td width="441" style="padding:7px 10px;" valign="top"><a href="<?php echo $discussion['URL']; ?>~NewsletterDiscussionList<!-- #widgettracker --><!-- widgettracker# -->" style="font-size:14px; color:#0065e8; font-family:Arial, Helvetica, sans-serif; text-decoration:none;"><?php echo $discussion['msgTxt']; ?></a></td>
  </tr>
  <tr>
	<td style="padding:0px 10px 7px 10px;">
		<div style="padding-bottom:5px;">
		<?php
			$description = $discussion['description'];
			if(strlen($description) > 200) {
				$description = substr($description,0,200).'...';
			}
			echo $description;
		?>
		</div>	
	<table width="105" border="0" cellspacing="0" cellpadding="0" align="right">
	  <tr>
		<td width="105" height="25"><a href="<?php echo $discussion['URL']; ?>~NewsletterDiscussionList<!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#0065e8; text-decoration:none;">Continue reading</a></td>
	  </tr>
	</table>
	<table width="160" border="0" cellspacing="0" cellpadding="0" align="left">
	  <tr>
		<td width="36"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/newsletter/comment1IC.gif" width="36" height="21" vspace="0" hspace="0" align="left" /></td>
		<td width="125" bgcolor="#f8edb1" style="border:1px solid #d3d3d3; border-left:none;"><a href="<?php echo $discussion['URL']; ?>~NewsletterDiscussionList<!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#474a4b; line-height:18px; display:block; text-decoration:none;">
		<?php
		if($discussion['fromOthers'] == 'user') {
			$commentTxt = 'answer';
		}
		else {
			$commentTxt = 'comment';
		}
		
		echo $discussion['commentCount']." ".$commentTxt;
		if($discussion['commentCount'] != 1) {
			echo 's';
		}
		?>
		</a></td>
	  </tr>
	</table>
	
	</td>
  </tr>
</table>
</td>
<td></td>
</tr>
<?php } ?>

<tr>
<td colspan="3" height="15"></td>
</tr>
<?php /*
<tr>
<td></td>
<td><div style="width:100%" align="center"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/newsletter/banner.jpg" vspace="0" hspace="0" align="absmiddle" style="max-width:468px; width:inherit; max-height:60px;" /></div></td>
<td></td>
</tr>
<tr>
<td colspan="3" height="28"></td>
</tr>
*/
?>