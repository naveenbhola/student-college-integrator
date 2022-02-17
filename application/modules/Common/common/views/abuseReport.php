<?php $this->load->view('common/mailContentHeader'); ?>
<style>
ul,p{font-family:Arial, Helvetica, sans-serif;font-size:12px}
ul.ml_0{margin-left:0; list-style-position:inside}
a{color:#0066FF}
</style>
<style>
table.report {
	border-width: 1px 1px 1px 1px;
	border-style: outset outset outset outset;
	border-color: gray gray gray gray;
	border-collapse: separate;
	background-color: white;
	font-family:Arial, Helvetica, sans-serif;
	font-size:11px;
}
table.report th {
	border-width: 1px 1px 1px 1px;
	border-style: inset inset inset inset;
	border-color: gray gray gray gray;
	background-color: white;
	-moz-border-radius: 0px 0px 0px 0px;
}
table.report td {
	border-width: 1px 1px 1px 1px;
	border-style: inset inset inset inset;
	border-color: gray gray gray gray;
	background-color: white;
	-moz-border-radius: 0px 0px 0px 0px;
}
</style>
<div width="600">
<br/>
<span style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:orange; ">&nbsp;Abuse Report</span>
<br/>
<div >
<table width="100%" border='0' cellpadding='0' cellspacing='0' class='report'>
<tr>
  <td width="26%" align="center"><b>Entity text</b></td>
  <td width="8%" align="center"><b>Entity ID</b></td>
  <td width="8%" align="center"><b>User ID</b></td>
  <td width="15%" align="center"><b>User Level</b></td>
  <td width="8%" align="center"><b>Points</b></td>
  <td width="20%" align="center"><b>Reason</b></td>
  <td width="15%" align="center"><b>Date</b></td>
</tr>
<?php
foreach($abuseList as $report){
  if(is_array($report)){
  ?>
    <tr>
      <td><a href="javascript:void(0);" onClick="window.open('<?php echo $report['url']; ?>');" class="fontSize_10p"><?php echo isset($report['msgTxt'])?nl2br($report['msgTxt']):''; ?></a></td>
      <td align="center"><?php echo $report['entityId'];?></td>
      <td align="center"><?php echo $report['userId'];?></td>
      <td align="center"><?php echo $report['userLevel'];?></td>
      <td align="center"><?php echo $report['pointsAdded'];?></td>
      <td><?php echo $report['abuseReason'];?></td>
      <td align="center"><?php echo $report['creationDate'];?></td>
    </tr>
<?php }
}  ?>
</table>
</div>
</div>


<?php $this->load->view('common/mailContentFooter'); ?>
