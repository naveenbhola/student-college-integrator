<?php $this->load->view('common/mailContentHeader'); ?>
<style>
ul,p{font-family:Arial, Helvetica, sans-serif;font-size:12px}
ul.ml_0{margin-left:0; list-style-position:inside}
a{color:#0066FF}
</style>
<div style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#1A1A1A; padding-bottom: 10px; line-height:20px;">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=us-ascii">
</head>
<body>
<div style="width:550px">
<table cellpadding="0" cellspacing="0" width="100%" border="0" style="font-size:12px; font-family:Arial,Helvetica,sans-serif">
<tbody>
<tr>
<td>
<p>Dear <?php echo $contactDetails['clientName']; ?>,</p>
<p>Please find the detail of <?php echo count($responses); ?> new <b style="color: #008489">Naukri Learning</b> lead<?php echo count($responses) > 1 ? 's':''; ?>:</p>
<p>
	<b>New lead details (<?php echo count($responses); ?>) </b><br/><br/>
	<?php
		foreach ($responses as $response) {
			$this->load->view('response/NaukriLeadMailerView', array('response' => $response));
		}
	?>

</p>
<br/>
<p>To view lead details please login to <a href="<?php echo $SHIKSHA_HOME; ?>">Shiksha.com</a></p>
<br/>
<br/>
<p>Best Regards<br/>Shiksha.com Team </p>
</td>
</tr>
</tbody>
</table>
</div>
</body>
</html>
<?php $this->load->view('common/mailContentFooter'); ?>
