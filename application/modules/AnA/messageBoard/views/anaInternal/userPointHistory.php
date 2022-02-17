<!DOCTYPE html>
<html>
<head>
	<title>Shiksha - User Point History</title>
	<script type="text/javascript"
src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
     <script type="text/javascript" src="//<?php echo JSURL;
?>/public/js/<?php echo getJSWithVersion("bootstrap_min"); ?>"></script>


	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<link href="/public/css/<?php echo getCSSWithVersion('bootstrap');?>" type="text/css" rel="stylesheet" />

</head>
<body>
<div style="font-size: 21px; margin-bottom: 15px; text-align: center;"><b>User activity log</b></div>
<div class="container">
<form class="form-horizontal" name="myform" action="userPointHistory" method="post" onsubmit="if(validateForm() == false){return false;}">
<div class="form-group">
<label class="col-sm-2">Displayname</label>
<div class="col-sm-10"><input type="text" name="userName" id="userName" minlength="1"  maxlength="50" caption="Name" value="<?php echo $_POST['userName']; ?>" placeholder = "Enter Display Name"></div>
<div class="col-sm-offset-2 col-sm-10 text-danger error_div" id="error_displayName_div"></div>
</div>
<div class="form-group">
<label class="col-sm-2">UserId</label>
<div class="col-sm-10"><input type="text" name="userId" id="userId" onmouseout="validateUserId()" minlength="1" maxlength="50" caption="userId" value="<?php echo $_POST['userId']; ?>" placeholder = "Enter User ID"></div>
<div class="col-sm-offset-2 col-sm-10 text-danger error_div" id="error_userId_div"></div>
</div>
<div class="form-group">
<label class="col-sm-2">Email</label>
<div class="col-sm-10"><input type="text" name="emailAddress" id="emailAddress" minlength="1" maxlength="50" caption="email Id" value="<?php echo $_POST['emailAddress']; ?>" placeholder = "Enter Email ID"></div>
<div class="col-sm-offset-2 col-sm-10 text-danger error_div" id="error_email_div"></div>
</div>
<div class="form-group">
<label class="col-sm-2">Start Date</label>
  <div class="col-sm-10"><input readonly="readonly" type="text" id="startDate" class="span2 datepicker" name="startDate" value="<?php echo $_POST['startDate']!=''?$_POST['startDate']:date('Y-m-d', strtotime('-7 days')); ?>"></div>
  <div class="col-sm-offset-2 col-sm-10 text-danger error_div" id="error_start_div"></div>
</div>
<div class="form-group">
<label class="col-sm-2">End Date</label>
  <div class="col-sm-10"><input readonly="readonly" type="text" id="endDate" class="span2 datepicker" name="endDate"  value="<?php echo $_POST['endDate']!=''?$_POST['endDate']:date('Y-m-d'); ?>"></div>
  <div class="col-sm-offset-2 col-sm-10 text-danger error_div" id="error_endDate_div"></div>
</div>
<div class="form-group">
<div class="col-sm-offset-2 col-sm-10">
	<input type="submit" name="showData" value="Get Data">
</div>
</form>
<hr/><hr/>
<?php if($userHistory == "No result found."){
	echo $userHistory;
}
?>

<?php if(!empty($userHistory) && $userHistory != "No result found.") {?>
<div>
<p><b> Name: </b> 
<?php echo $userHistory[0]['firstname'].' '.$userHistory[0]['lastname']; ?>
</p>
<p><b> Level Name: </b> 
<?php echo $userHistory[0]['levelName']; ?>
</p>
	<table class="table table-striped table-hover">
	<tr>
		<td>S.No.</td>
		<td>Points Added</td>
		<td>Action</td>
		<td>Time</td>
		<td>URL/EntityId</td>
	</tr>	
	<?php
	$count = 1;
	foreach ($userHistory as $key => $value) {?>
	<tr>
		<td><?php echo $count;?></td>
		<td><?php echo $value['pointvalue'];?></td>
		<td><?php echo $value['action'];?></td>
		<td><?php echo date('d M, Y h:i A', strtotime($value['timestamp']));?></td>
		<td><?php if(!($value['entityId'] == 0 || $value['action'] == 'tagFollow' || $value['action'] == 'userFollow' || $value['action'] == 'deleteDiscussion' || $value['action'] == 'deleteQuestion')){?><a href = "<?=$url[$key];?>" target = "_blank"><?php } ?><?php echo $url[$key];?><?php if(!($value['entityId'] == 0 || $value['action'] == 'tagFollow' || $value['action'] == 'userFollow' || $value['action'] == 'deleteDiscussion' || $value['action'] == 'deleteQuestion')){?></a><?php } ?></td>
	</tr>
	<?php $count++;}
	?>
	</table>
</div>
<?php } ?>

</div>
<script type="text/javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion("userHistoryModeration"); ?>">
	
</script>
</body>
</html>
