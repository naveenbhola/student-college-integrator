<?php ?>
<p> Hi, </p>
<p> Listing Downgrade Cron Status :</p>

<p> 
CRON start time  : <b><?=$logData['CronStartTime']?></b></br>
CRON end time  : <b><?=$logData['CronEndTime']?></b></br>
CRON Execution time  : <b><?=$logData['CronExecutionTime']?></b></br>
CRON Peak Memory Usages time  : <b><?=$logData['CronPeakMemoryUsages']?></b></br>
</p>

<?php if(!empty($logData['error'])) {
 echo "Failure Reason Might be  :".$this->logData['error']."  </br>";
}
?>

<?php 
  echo "Courses to expire :";
$errorEntries = $logData['coursesToExpire'];
if(!empty($errorEntries)) {
$coulmnHeader = array_keys($errorEntries[0]); ?>
<table style="margin-left:50px;margin-top:10px;width:auto;border:1px solid black;border-collapse:collapse;">
<tr style="border:1px solid black;border-collapse:collapse;padding:5px;">
<?php foreach($coulmnHeader as $column) {?>
<th style="border:1px solid black;border-collapse:collapse;padding:5px;"><?=$column; ?></th>
<?php }?>
</tr>
<?php foreach($errorEntries as $rowData) {?>
<tr style="border:1px solid black;border-collapse:collapse;padding:5px;">
<?php foreach($coulmnHeader as $coulmn) {?>
<td style="border:1px solid black;border-collapse:collapse;padding:5px;"><?= is_array($rowData[$coulmn]) ? _p($rowData[$coulmn]): $rowData[$coulmn] ?></td>
<?php }?>
</tr>
<?php }?>
</table>
<?php } else { echo "<br><b>NO DATA FOUND!!</b><br>"; }?>

<br/>
<?php
 echo "Courses actually expired :";
$errorEntries = $logData['courseExpired'];
if(!empty($errorEntries)) {
$coulmnHeader = array_keys($errorEntries[0]); ?>
<table  style="margin-left:50px;margin-top:10px;width:auto;border:1px solid black;border-collapse:collapse;">
<tr style="border:1px solid black;border-collapse:collapse;padding:5px;">
<?php foreach($coulmnHeader as $column) {?>
<th style="border:1px solid black;border-collapse:collapse;padding:5px;"><?=$column; ?></th>
<?php }?>
</tr>
<?php foreach($errorEntries as $rowData) {?>
<tr style="border:1px solid black;border-collapse:collapse;padding:5px;">
<?php foreach($coulmnHeader as $coulmn) {?>
<td style="border:1px solid black;border-collapse:collapse;padding:5px;"><?= is_array($rowData[$coulmn]) ? _p($rowData[$coulmn]): $rowData[$coulmn] ?></td>
<?php }?>
</tr>
<?php }?>
</table>
<?php } else { echo "<br><b>NO DATA FOUND!!</b><br>"; }?>

<br/>
<?php
 if($logData['CronType'] == 'ZeroExpiryDate') {
 	echo 'Courses not expired : reason might be Subscription Product Mapping having active status for subscription id,Subscription End Date might be greater thn current date,skipped in code';
 } else {
 	echo "Courses not expired due to booking date before : " .$logData['bookingStartDate'] ;
 }

$errorEntries = $logData['filteredCourses'];
if(!empty($errorEntries)) {
$coulmnHeader = array_keys($errorEntries[0]); ?>

<table style="margin-left:50px;margin-top:10px;width:auto;border:1px solid black;border-collapse:collapse;">
<tr style="border:1px solid black;border-collapse:collapse;padding:5px;">
<?php foreach($coulmnHeader as $column) {?>
<th style="border:1px solid black;border-collapse:collapse;padding:5px;"><?=$column; ?></th>
<?php }?>
</tr>
<?php foreach($errorEntries as $rowData) {?>
<tr style="border:1px solid black;border-collapse:collapse;padding:5px;">
<?php foreach($coulmnHeader as $coulmn) {?>
<td style="border:1px solid black;border-collapse:collapse;padding:5px;"><?= is_array($rowData[$coulmn]) ? _p($rowData[$coulmn]): $rowData[$coulmn] ?></td>
<?php }?>
</tr>
<?php }?>
</table>
<?php } else { echo "<br><b>NO DATA FOUND!!</b><br>"; }?>

<?php
if($logData['CronType'] == 'ZeroExpiryDate') {
echo "Courses with zero subscription id  :";	
$errorEntries = $logData['coursesHavingZeroSubscriptionId'];
if(!empty($errorEntries)) {
$coulmnHeader = array_keys($errorEntries[0]); ?>

<table style="margin-left:50px;margin-top:10px;width:auto;border:1px solid black;border-collapse:collapse;">
<tr style="border:1px solid black;border-collapse:collapse;padding:5px;">
<?php foreach($coulmnHeader as $column) {?>
<th style="border:1px solid black;border-collapse:collapse;padding:5px;"><?=$column; ?></th>
<?php }?>
</tr>
<?php foreach($errorEntries as $rowData) {?>
<tr style="border:1px solid black;border-collapse:collapse;padding:5px;">
<?php foreach($coulmnHeader as $coulmn) {?>
<td style="border:1px solid black;border-collapse:collapse;padding:5px;"><?= is_array($rowData[$coulmn]) ? _p($rowData[$coulmn]): $rowData[$coulmn] ?></td>
<?php }?>
</tr>
<?php }?>
</table>
<?php } else { echo "<br><b>NO DATA FOUND!!</b><br>"; }
$errorEntries = $logData['skippedSubscriptionIds'];
if(!empty($errorEntries)) {
echo "Subscription ids skipped manually in Code : ";
 $coulmnHeader = array_keys('SubscriptionId');
 ?>	

<table style="margin-left:50px;margin-top:10px;width:auto;border:1px solid black;border-collapse:collapse;">
<tr style="border:1px solid black;border-collapse:collapse;padding:5px;">
<?php foreach($coulmnHeader as $column) {?>
<th style="border:1px solid black;border-collapse:collapse;padding:5px;"><?=$column; ?></th>
<?php }?>
</tr>
<?php foreach($errorEntries as $rowData) {?>
<tr style="border:1px solid black;border-collapse:collapse;padding:5px;">
<td style="border:1px solid black;border-collapse:collapse;padding:5px;"><?= $rowData?></td>
</tr>
<?php }?>
</table>

<?php }

}
?>

<p> --Listing Downgrade Notification. </p>