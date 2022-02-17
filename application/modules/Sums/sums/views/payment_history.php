<html>
<head>
<title>
	Showing Paymant History For Payment ID :: <?php echo $PaymentId; ?> Part Number :: <?php echo $PartNumber; ?>
</title>
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("mainStyle"); ?>" type="text/css" rel="stylesheet" />
</head>
<body>
<div class="lineSpace_28">&nbsp;</div>
<div class="OrgangeFont fontSize_14p bld"><span>Payment History For </span><span style="margin-left:15%;">Payment ID : <?php echo $PaymentId; ?><span style="margin-left:15%;">Part Number : <?php echo $PartNumber; ?></span></div>
<div class="grayLine"></div>
<div class="lineSpace_28">&nbsp;</div>
<table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
<tr class="tableHeaderSums">
                    <td width="8%" align="center"><strong>Payment Amount</strong></td>
                    <td width="8%" align="center"><strong>TDS Amount</strong></td>	
                    <td width="10%" align="center"><strong>Payment Mode</strong></td>
                    <td width="15%" align="center"><strong>Receipt Date</strong></td>
                    <td width="15%" align="center"><strong>Cheque/DD Date</strong></td>
		    <td width="14%" align="center"><strong>Check Bank-Number</strong></td>	
                    <td width="14%" align="center"><strong>Status</strong></td>
		    <td width="12%" align="center"><strong>Deposited By</strong></td>
			<td width="12%" align="center"><strong>Edited By</strong></td>
                    <td width="12%" align="center"><strong>Cheque/DD Comments</strong></td>
		    <td width="12%" align="center"><strong>Time Of Modification</strong></td>	
</tr>
<?php
	$paymentLogs = $PaymentHistory[0]['paymentLogs'];
	$i = 1;
	foreach($paymentLogs as $val):
	$i++;
?>
<tr class="<?php if(($i%2) == 0) echo 'tRow2Sums'; else echo 'tRow1Sums'; ?>">
                        <td width="8%" align="center"><?php echo $val['Amount_Received']; ?></td>
                        <td width="8%" align="center"><?php echo $val['TDS_Amount']; ?></td>
                        <td width="10%" align="center"><?php echo $val['Payment_Mode']; ?></td>
                        <td width="15%" align="center"><?php echo $Cheque_Date ; ?></td>
                        <td width="15%" align="center"><?php echo $Cheque_Receiving_Date; ?></td>
			<td width="14%" align="center"><?php echo $checkDetails; ?></td>
                        <td width="14%" align="center"><?php echo $val['isPaid']; ?></td>
			<td width="12%" align="center"><?php echo ($val['displayname'] != '')?$val['displayname']:'N.A.'; ?></td>
			<td width="12%" align="center"><?php echo ($val['EditedBy'] != '')?$val['EditedBy']:'N.A.'; ?></td>
                        <td width="12%" align="center"><?php echo $val['Cheque_DD_Comments']; ?></td>
			<td width="12%" align="center"><?php echo strtotime($val['Modify_Date']); ?></td>
                    </tr>
<?php endforeach; ?>
<?php
	$recentPayment = $PaymentHistory[0]['recentPayment'];
	$i = 1;
	foreach($recentPayment as $val):
	$i++;
?>
<tr class="<?php if(($i%2) == 0) echo 'tRow2Sums'; else echo 'tRow1Sums'; ?>">
                        <td width="8%" align="center"><?php echo $val['Amount_Received']; ?></td>
                        <td width="8%" align="center"><?php echo $val['TDS_Amount']; ?></td>
                        <td width="10%" align="center"><?php echo $val['Payment_Mode']; ?></td>
                        <td width="15%" align="center"><?php echo $Cheque_Date ; ?></td>
                        <td width="15%" align="center"><?php echo $Cheque_Receiving_Date; ?></td>
			<td width="14%" align="center"><?php echo $checkDetails; ?></td>
                        <td width="14%" align="center"><?php echo $val['isPaid']; ?></td>
			<td width="12%" align="center"><?php echo ($val['displayname'] != '')?$val['displayname']:'N.A.'; ?></td>
			<td width="12%" align="center"><?php echo ($val['EditedBy'] != '')?$val['EditedBy']:'N.A.'; ?></td>
                        <td width="12%" align="center"><?php echo $val['Cheque_DD_Comments']; ?></td>
			<td width="12%" align="center"><?php echo date('d-m-Y H:i:s',strtotime($val['Payment_Modify_Date'])); ?></td>	
                    </tr>
<?php endforeach; ?>		
</table>
</body>
</html>
