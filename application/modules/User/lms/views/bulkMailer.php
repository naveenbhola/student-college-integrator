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
<p>Hi,</p>
<p>Following students have expressed interest in your institute. You might want to contact them at the number mentioned or email them to further explore his admission interest.</p>
<p>
<b>Details of the Students : </b><br/><br/>
<?php
echo $content;
?>
</p>
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
