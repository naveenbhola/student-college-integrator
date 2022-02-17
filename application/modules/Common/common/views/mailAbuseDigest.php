<div width="600">
Dear All,<br/>
<p><?php echo $totalNumber;?> Answer/Question/Reply/Comment were reported as abuse on <?php echo $yesterday;?>.</p>
<?php 
if(is_int($totalNumber) && $totalNumber>0){
?>
<p>Please find attached the document containing the report abuse details.</p>
<?php } ?>
<?php if(is_array($smsReport)){ ?>
<p><b>SMS Report:</b></p>
<p>Total SMS sent: <?php echo $smsReport['totalSMSSent'];?></p>
<p>Total SMS failed: <?php echo $smsReport['totalSMSFailure'];?></p>
<p>Total Registrations made: <?php echo $smsReport['totalRegisteredUsers'];?></p>
<p>Total mobile verified: <?php echo $smsReport['mobileVerified'];?></p>
<br>
<p>SMS sent within 5 min: <?php echo $smsReport['count_5min'];?></p>
<p>SMS sent within half an hour: <?php echo $smsReport['count_30min'];?></p>
<p>SMS sent within 2 hours: <?php echo $smsReport['count_120min'];?></p>
<?php } ?>
<br>
<p><b>Email Report</b></p>
<p>Mail sent within 5 min: <?php echo $smsReport['mail_count_5min'];?></p>
<p>Mail sent within 30 min: <?php echo $smsReport['mail_count_30min'];?></p>
<p>Mail sent within 2 hours: <?php echo $smsReport['mail_count_120min'];?></p>
<br/>

<br>
<p><b>Facebook Share Report</b></p>
<p>Total Facebook Share: <?php echo $FbShareCount;?></p>
<p>Total Users registered through FConnect: <?php echo $FbConnectCount;?></p>
<br/>

<br>
<p><b>Search Agent Report</b></p>
<p>Total no. of leads that went for matching: <?php echo $smsReport['leadsMatched'];?></p>
<p>Total no. of leads allocated: <?php echo $smsReport['leadsAllocated'];?></p>
<p>Count of leads allocated to agents within 24 hours: <?php echo $smsReport['allocated_lead_count'];?></p>
<p>Count of search agents who has leads allocated to them within 24 hours: <?php echo $smsReport['allocated_lead_agents_count'];?></p>
<p>Count of search agents who has not allocated any leads within 24 hour: <?php echo $smsReport['unallocated_lead_agents_count'];?></p>
<p>Count of search agents whose leads are delivered to them within 24 hours: <?php echo $smsReport['delivered_lead_agents_count'];?></p>
<br/>

<br>
<p><b>Responses Report</b></p>
<p>Total no. of responses made within 24 hours: <?php echo $smsReport['responses']['totalResponses']; unset($smsReport['responses']['totalResponses']); ?></p>
<?php foreach ($smsReport['responses'] as $action=>$count) { ?>
<p><?php echo $action;?> : <?php echo $count;?></p>
<?php } ?>
<br/>

<br>
<p><b>Mobile ANA Report</b></p>
<p>Questions asked: <?=$mobileQuestionCount?></p>
<p>Answers given: <?=$mobileAnswerCount?></p>
<br/>

Best Regards<br/>
Shiksha.com team
</div>
