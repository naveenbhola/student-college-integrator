<?php
if(is_array($headerContentaarray) && count($headerContentaarray)>0) {
	foreach ($headerContentaarray as $content) {
		echo $content;
	}
}
?>
<style type="text/css">
	table.priTable { border-left:1px solid #ccc; border-top:1px solid #ccc; margin-bottom: 20px;}
	table.priTable th { border-right:1px solid #ccc; border-bottom:1px solid #ccc; font:bold 13px arial; text-align: left; padding:4px; background:#f1f1f1;}
	table.priTable td { border-right:1px solid #ccc; border-bottom:1px solid #ccc; font:normal 13px arial; text-align: left; padding:4px;}
	ul.primenu {margin-bottom: 20px; border-bottom: 1px solid #ccc;}
	ul.primenu li {float: left; background: #eee; font:normal 14px arial; margin-right: 5px; margin-left: 10px; border-top:1px solid #ccc; border-left:1px solid #ccc; border-right:1px solid #ccc;}
	ul.primenu li a {display: block; padding:8px; font:normal 14px arial; text-decoration: none;}
	li#priactivemenu {background: #fff;}
	li#priactivemenu a {color:#333; font-weight:bold;}
</style>

<div style="width: 950px;padding:10px;font-family:Arial,Helvetica,sans-serif;font-size:12px;">
	
<ul class='primenu'>
	<li><a href='/enterprise/PRILine/index'>Map Numbers</a></li>
	<li id='priactivemenu'><a href='/enterprise/PRILine/viewMapped'>View Mapped Numbers</a></li>
	<div style="clear:both;"></div>
</ul>	
	
	
<div class="orangeColor fontSize_14p bld" style="width: 950px;"><span><b>Currently Mapped Numbers</b></span><br/>
<div class="lineSpace_10">&nbsp;</div>
</div>

<?php if($PRIMapping) { ?>
<table cellpadding='0' cellspacing='0' width='950' class="priTable">
	<tr>
		<th width="100">PRI Number</th>
		<th width="120">Set On</th>
		<th width="120">Client</th>
		<th width="200">Institute</th>
		<th>Course</th>
		<th width="80">City</th>
		<th width="80">Category</th>
	</tr>
	<?php foreach($PRIMapping as $PRIAssignment) { ?>
	<tr>
		<td><?php echo $PRIAssignment['PRINumber']; ?></td>
		<td><?php echo date('M j Y, H:i',strtotime($PRIAssignment['setOn'])); ?></td>
		<td><?php echo $PRIAssignment['clientName']; ?></td>
		<td><?php echo $PRIAssignment['instituteName']; ?></td>
		<td><?php echo $PRIAssignment['courseName']; ?></td>
		<td><?php echo $PRIAssignment['cityName']; ?></td>
		<td><?php echo $PRIAssignment['categoryName']; ?></td>
	</tr>
	<?php } ?>
</table>
<?php } else { ?>
	<h2 style="font:bold 13px arial; color:red; margin-bottom: 20px; display: block; background: #f8f8f8; padding:15px;">No PRI number is currently mapped.</h2>
<?php } ?>

<?php if($PRIMappingHistory) { ?>
<div class="orangeColor fontSize_14p bld" style="width: 950px;"><span><b>History</b></span><br/>
<div class="lineSpace_10">&nbsp;</div>
</div>
<table cellpadding='0' cellspacing='0' width='950' class="priTable">
	<tr>
		<th width="100">PRI Number</th>
		<th width="80">Set On</th>
		<th width="80">Reset On</th>
		<th width="120">Client</th>
		<th width="200">Institute</th>
		<th>Course</th>
		<th width="80">City</th>
		<th width="100">Category</th>
	</tr>
	<?php $currentNumber = NULL; $bg = '#f6f6f6';  foreach($PRIMappingHistory as $PRIAssignment) { ?>
	<?php
	if($currentNumber != $PRIAssignment['PRINumber']) {
		$bg = $bg == '#ffffff' ? '#f6f6f6' : '#ffffff';
		$currentNumber = $PRIAssignment['PRINumber'];
	}
	?>
	<tr style='background: <?php echo $bg; ?>;'>
		<td><?php echo $PRIAssignment['PRINumber']; ?></td>
		<td><?php echo date('M j Y, H:i',strtotime($PRIAssignment['setOn'])); ?></td>
		<td><?php echo date('M j Y, H:i',strtotime($PRIAssignment['resetOn'])); ?></td>
		<td><?php echo $PRIAssignment['clientName']; ?></td>
		<td><?php echo $PRIAssignment['instituteName']; ?></td>
		<td><?php echo $PRIAssignment['courseName']; ?></td>
		<td><?php echo $PRIAssignment['cityName']; ?></td>
		<td><?php echo $PRIAssignment['categoryName']; ?></td>
	</tr>
	<?php } ?>
</table>
<?php } ?>


</div>
<?php $this->load->view('common/leanFooter');?>
