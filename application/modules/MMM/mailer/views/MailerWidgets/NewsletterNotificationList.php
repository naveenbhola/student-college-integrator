<table border="0" cellspacing="0" cellpadding="0" align="center" style="max-width:600px;border:1px #dddddd solid;">
  <?php foreach($articles as $article) {
    if($article['blogId'] == $notification_first_id) {
  ?>
  <tr>
    <td width="600" background="<?php echo SHIKSHA_HOME; ?>/public/images/newsletter/main_bg.gif" bgcolor="#588ac1" valign="top" style="border-top:5px #ffffff solid">
      <table border="0" cellspacing="0" cellpadding="0" align="center" style="max-width:600px;">
	<tr>
	  <td width="20"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/newsletter/spacer.gif" width="10" height="1" vspace="0" hspace="0" align="left" /></td>
	  <td width="560" valign="top" align="center">
	    <table width="289" border="0" cellspacing="0" cellpadding="0" align="center">
	      <tr>
		<td align="center"><a href="https://www.shiksha.com/" target="_blank"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/logomnews.png" alt="Shiksha.com" width="289" height="52" hspace="0" vspace="0" border="0" align="left" style="font-family:Arial, Helvetica, sans-serif;font-size:34px;color:#ffffff" /></a></td>
	      </tr>
	    </table>
	  </td>
	  <td width="20"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/newsletter/spacer.gif" width="10" height="1" vspace="0" hspace="0" align="left" /></td>
	</tr>
	<tr>
	  <td></td>
	  <td height="30"></td>
	  <td></td>
	</tr>
	<tr>
	  <td></td>
	  <td valign="top">
	    <table width="310" border="0" cellspacing="0" cellpadding="0" align="left">
	      <tr>
	        <td style="line-height:35px;">
		  <span style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif;font-size:35px;color:#d4ff82; word-wrap: break-word;">
		    <strong><?php echo trim($article['mailerTitle']) ? trim($article['mailerTitle']) : $article['blogTitle']; ?></strong>
		  </span>
		</td>
	      </tr>
	      <tr>
		<td height="20"></td>
	      </tr>
	      <tr>
		<td width="310" style="font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#ffffff;">
		  <?php
		    $articleSummary = trim($article['mailerSnippet']) ? trim($article['mailerSnippet']) : $article['summary'];
		    if(strlen($articleSummary) > 100) {
			$articleSummary = substr($articleSummary,0,100).'...';
		    }
		    echo $articleSummary;
		  ?>
		  <br />
		</td>
	      </tr>
	      <tr>
		<td height="20"></td>
	      </tr>
	      <tr>
		<td valign="top">
		  <table width="170" border="0" cellspacing="0" cellpadding="0" align="left">
		    <tr>
		      <td width="19"><a href="<?php echo SHIKSHA_HOME.$article['url']; ?>~NewsletterNotificationList<!-- #widgettracker --><!-- widgettracker# -->" title="Read more" target="_blank" style="font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#282828;text-decoration:none;line-height:41px;display:block"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/newsletter/link_left.gif" width="19" height="41" hspace="0" vspace="0" border="0" align="left" /></a></td>
		      <td width="86" bgcolor="#bbe88f" align="center"><a href="<?php echo SHIKSHA_HOME.$article['url']; ?>~NewsletterNotificationList<!-- #widgettracker --><!-- widgettracker# -->" title="Read more" target="_blank" style="font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#282828;text-decoration:none;line-height:41px;display:block">Read more</a></td>
		      <td width="65"><a href="<?php echo SHIKSHA_HOME.$article['url']; ?>~NewsletterNotificationList<!-- #widgettracker --><!-- widgettracker# -->" title="Read more" target="_blank" style="font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#282828;text-decoration:none;line-height:41px;display:block"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/newsletter/link_right.gif" width="65" height="41" hspace="0" vspace="0" border="0" align="right" /></a></td>
		    </tr>
		  </table>
		</td>
	      </tr>
	      <tr>
		<td height="30"></td>
	      </tr>
	    </table>
	    <table width="233" border="0" cellspacing="0" cellpadding="0" align="left">
	      <tr>
		<td colspan="3" valign="top"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/newsletter/img_1.gif" width="233" height="36" hspace="0" vspace="0" border="0" align="left" /></td>
	      </tr>
	      <tr>
		<td width="57" valign="top"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/newsletter/img_2.gif" width="57" height="104" hspace="0" vspace="0" border="0" align="left" /></td>
		<td width="100" bgcolor="#ffffff" align="center" valign="top" style="line-height:23px;">
		  <span style="font-family:Tahoma, Geneva, sans-serif;font-size:18px;color:#454545;"><?php echo (($article['relatedDate']) ? ucwords(date('l',strtotime($article['relatedDate']))) : ucwords(date('l'))); ?></span><br />
		  <span style="font-family:Tahoma, Geneva, sans-serif;font-size:58px;color:#454545; line-height:42px;"><strong><?php echo (($article['relatedDate']) ? date('d',strtotime($article['relatedDate'])) : date('d')); ?></strong></span><br />
		  <span style="font-family:Tahoma, Geneva, sans-serif;font-size:14px;color:#454545;"><?php echo (($article['relatedDate']) ? ucwords(date('M Y',strtotime($article['relatedDate']))) : ucwords(date('M Y'))); ?></span></td>
		<td width="77" valign="top"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/newsletter/img_3.gif" width="77" height="104" hspace="0" vspace="0" border="0" align="left" /></td>
	      </tr>
	      <tr>
		<td colspan="3" valign="top"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/newsletter/img_4.gif" width="233" height="216" hspace="0" vspace="0" border="0" align="left" /></td>
	      </tr>
	    </table>
	  </td>
	  <td></td>
	</tr>
      </table>
    </td>
  </tr>
  <?php }
  } ?>
  <tr>
    <td background="<?php echo SHIKSHA_HOME; ?>/public/images/newsletter/lower_bg.jpg" bgcolor="#e4e6d6" style="border-top:3px #ffffff solid" valign="top">
      <table border="0" cellspacing="0" cellpadding="0" align="center" style="max-width:600px;">
	<?php foreach($articles as $article) {
		if($article['blogId'] !== $notification_first_id) {
	?>
	<tr>
	  <td width="20"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/newsletter/spacer.gif" width="10" height="1" vspace="0" hspace="0" align="left" /></td>
	  <td width="560" height="30"></td>
	  <td width="20"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/newsletter/spacer.gif" width="10" height="1" vspace="0" hspace="0" align="left" /></td>
	</tr>
	<tr>
	  <td></td>
	  <td valign="top" width="560">
	    <table width="277" border="0" cellspacing="0" cellpadding="0" align="left">
	      <tr>
		<td></td>
		<td width="257" bgcolor="#ffffff" align="center" style="border:2px #ffffff solid"><img src="<?php echo $article['blogImageURL'] ? MEDIA_SERVER.$article['blogImageURL'] : SHIKSHA_HOME.'/public/images/newsletter/img1.jpg' ?>" width="257" height="145" vspace="0" hspace="0" align="left" alt="Image cannot be displayed" style="font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#999999" /></td>
		<td width="20"></td>
	      </tr>
	      <tr>
		<td></td>
		<td height="25"></td>
		<td></td>
	      </tr>
	    </table>
	    <table width="258" border="0" cellspacing="0" cellpadding="0" align="left">
	      <tr>
		<td width="258"><a href="<?php echo SHIKSHA_HOME.$article['url']; ?>~NewsletterNotificationList<!-- #widgettracker --><!-- widgettracker# -->" target="_blank" style="font-family:Georgia, 'Times New Roman', Times, serif;font-size:17px;color:#43433e;text-decoration:none"><?php echo trim($article['mailerTitle']) ? trim($article['mailerTitle']) : $article['blogTitle']; ?></a></td>
	      </tr>
	      <tr>
		<td height="12"></td>
	      </tr>
	      <tr>
		<td width="258" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;color:#6a6a6a;">
		  <?php
		    $articleSummary = trim($article['mailerSnippet']) ? trim($article['mailerSnippet']) : $article['summary'];
		    if(strlen($articleSummary) > 100) {
			$articleSummary = substr($articleSummary,0,100).'...';
		    }
		    echo $articleSummary;
		  ?>
		</td>
	      </tr>
	      <tr>
		<td height="15"></td>
	      </tr>
	      <tr>
		<td valign="top">
		  <table width="92" border="0" cellspacing="0" cellpadding="0" align="left">
		    <tr>
		      <td height="30" bgcolor="#618bb4" style="border-radius:5px;border:1px #ffffff solid" align="center"><a href="<?php echo SHIKSHA_HOME.$article['url']; ?>~NewsletterNotificationList<!-- #widgettracker --><!-- widgettracker# -->" title="Read more" target="_blank" style="font-family:Arial, Helvetica, sans-serif;font-size:14px;color:#ffffff;text-decoration:none;line-height:30px;display:block">Read more</a></td>
		    </tr>
		  </table>
		</td>
	      </tr>
	      <tr>
		<td height="25"></td>
	      </tr>
	    </table>
	  </td>
	  <td></td>
	</tr>
	<tr>
	  <?php if($article['blogId'] !== $notification_last_id) { ?>
	    <td></td>
	    <td style="display: block; border-top:2px #e1b9a3 dotted"></td>
	    <td></td>
	  <?php } ?>
	</tr>
	<?php }
	} ?>
	<tr>
	  <td></td>
	  <td height="10"></td>
	  <td></td>
	</tr>
      </table>
    </td>
  </tr>
</table>