<tr>
  <td></td>
  <td>
	  <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="text-align:left;">
<tr>
<td><font face="Georgia, Times New Roman, Times, serif" color="#585c5f" style="font-size:18px;">Upcoming Event</font></td>
</tr>
<tr>
<td height="10"></td>
</tr>
<tr>
<td width="524">
<?php foreach($events as $event) { ?>	
<table align="left" width="260" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
	  <td width="91" valign="top" align="center" height="101"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/newsletter/ECI.jpg" width="81" height="81" hspace="0" vspace="3" align="left" /></td>			
	  <td width="169" valign="top">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#474a4b">
		<tr>
		  <td><font style="font-size:13px;" color="#0065e8" face="Georgia"><strong><?php echo $event['event_title']; ?></strong></font></td>
		</tr>
		<tr>
		  <td height="5"></td>
		</tr>
		<tr>
		  <td><?php echo $event['Address_Line1'] ? $event['Address_Line1'].', ' : ''; ?><?php echo $event['city']; ?> <?php echo date('jS F Y',strtotime($event['start_date'])); ?></td>
		</tr>
		<tr>
		  <td height="12"></td>
		</tr>
		<tr>
		  <td><a href="<?php echo $event['URL']; ?>" target="_blank" style="font-size:12px; color:#0065e8; text-decoration:none;">More details</a></td>
		</tr>
	  </table>
	  </td>
	</tr>
  </table>
<?php } ?>
</td>
</tr>
</table>
  </td>
  <td></td>
</tr>