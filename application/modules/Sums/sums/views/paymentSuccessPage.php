<?php
   $headerComponents = array(
      'css'			=> array('headerCms','raised_all','mainStyle','footer'),
      'js'			=> array('user','common'),
      'title'			=> "SUMS - ".$pageInfo['product']." ".$pageInfo['type']."ed",
      'product' 		=> '',
      'displayname'	=> (isset($sumsUserInfo['validity'][0]['displayname'])?$sumsUserInfo['validity'][0]['displayname']:""),
   );
   $this->load->view('enterprise/headerCMS', $headerComponents);
   $this->load->view('enterprise/cmsTabs',$sumsUserInfo);
?>
<?php if($typeOfSuccessPage == 'editPayment'): ?>
<div class="mar_full_10p">
    <div class="lineSpace_10">&nbsp;</div>
   <div>
	<?php if($flag): ?>	
	<h2>You have successfully updated the payment <?php echo $paymentId; ?></h2>
	<?php else: ?>
	<h2>Some error encountered in updating payment <?php echo $paymentId; ?> !!! Please try again.</h2>
	<?php endif; ?>
    </div>
</div>
<?php elseif($typeOfSuccessPage == 'multiplePayment'): ?>
<div class="mar_full_10p">
    <div class="lineSpace_10">&nbsp;</div>
   <div>
	<h2>You have successfully made the payments for following payments.</h2>
	<br />
	<table width="40%" border="1">
		<tr>	
			<td><strong>Payment ID</strong></td>
			<td><strong>Part Number</strong></td>
		</tr>
		<?php foreach($paymentDoneArray as $temp): ?>
		<tr>	
			<td><?php echo $temp['Payment_Id']; ?></td>
			<td><?php echo $temp['Part_Number']; ?></td>
		</tr>	
		<?php endforeach; ?>
	</table>
    </div>
</div>
<?php endif; ?>	
</body>
</html>
