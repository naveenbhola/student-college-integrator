<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
	<p>Dear <?=$crName?>,<br></p>
	<p>You can win a total of Rs. <?=$tasks['grand_total']?> by participating in the following tasks:</p>
	<?php
	foreach($tasks['endDateTasks'] as $task)
	{
	?>
	    <p>
		<strong><?=$task['name']?></strong> - Ending on <?=date('d/m/Y',strtotime($task['end_date']))?> - You can earn Rs. <?=$task['total_prize']?> - <a href="<?=$task['urlOfLandingPage'];?><!-- #AutoLogin --><!-- AutoLogin# -->">Participate now</a>
	    </p>
	<?php
	}
	foreach($tasks['noEndDateTasks'] as $task)
	{
	?>
	    <p>
		<strong><?=$task['name']?></strong> - You can earn Rs. <?=$task['total_prize']?> - <a href="<?=$task['urlOfLandingPage'];?><!-- #AutoLogin --><!-- AutoLogin# -->">Participate now</a>
	    </p>
	<?php
	}
	?>
	<p>Best wishes,<br>
	Shiksha.com</p>
</body>
</html>
