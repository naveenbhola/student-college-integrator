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
<p>Please find the detail of the <?php echo $response_count; ?> new response for your subscription:</p>
<p>
<b>Details of the Students : </b><br/><br/>
<?php

	foreach ($user_data as $data) {
		$exam_name  ='';
		$group_name  ='';
		
		foreach ($user_group_map[$data['userId']] as $group_id) {
			$data['group_id'] = $group_id;
			$data['exam_name'] = $group_name_data[$group_id]['name'];
			$data['group_name'] = $group_name_data[$group_id]['groupName'];

			$this->load->view('response/ExamResponseMailerView',array('temp'=>$data));
		}

	}
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
