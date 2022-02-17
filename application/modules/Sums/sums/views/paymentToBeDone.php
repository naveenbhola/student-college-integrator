<?php 
if ($payments==NULL) { ?>
	<div class="mar_top_6p">No results found.</div>
        <?php } else { ?>

        <div class="OrgangeFont fontSize_14p bld"><strong>Make Muliple Payments</strong></div>
        <div class="grayLine"></div>
            <div class="lineSpace_10">&nbsp;</div>
            <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td colspan="14" bgcolor="#FFFFFF">&nbsp;</td>
                </tr>
                <tr>
                    <td width="4%" height="25" valign="top" bgcolor="#EEEEEE" style="padding:5px 5px 0px 5px"><strong>Select</strong><input id="paymentMain" name="paymentMain" type="checkbox" onClick="javascript:selectAll(this);"/></td>	
                    <td width="7%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Payment Id</strong> </td>
                    <td width="7%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Part Number</strong></td>
                    <td width="7%" valign="top"  bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>CLIENT ID</strong></td>
                    <td width="7%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Tra. Id</strong></td>
                    <td width="10%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Receipt Date</strong></td>		    
		    <td width="10%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Cheque/DD Date</strong></td>
		    <td width="7%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Cheque Details</strong></td>
		    <td width="7%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Payment Mode</strong></td>
		     <td width="7%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Sale Type</strong></td>		
                    <td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>AMOUNT</strong></td>
		    <td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Deposited By</strong></td>
		    <td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Deposited Branch</strong></td>		
		    <td width="5%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Status</strong></td>	
                    <td width="6%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>ACTION</strong></td>
                </tr>
                    <tr>
			<td width="4%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>	
                        <td width="7%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="7%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="7%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="7%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="10%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="10%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
			<td width="7%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
			<td width="7%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
			<td width="7%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
			<td width="8%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
			<td width="8%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="8%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="5%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
			<td width="6%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                    </tr>
        <?php $i=1; ?> 
        <?php 
		foreach ($payments as $key=>$val) { 
			$checkDetails = '';
			if(($val['Cheque_Bank'] == '') || ($val['Cheque_No']))
				$checkDetails = $val['Cheque_Bank'].'-'.$val['Cheque_No'];	
	?>
                    <tr>
                    <td width="4%"><input id="payment_<?php echo $i; ?>" type="checkbox" value="<?php echo $val['Payment_Id'].'#'.$val['Part_Number']; ?>" name="PaymentId[]" <?php if($val['isPaid'] != 'Un-paid') echo "disabled=\"true\""; ?> /><input type="hidden" name="clientId_<?php echo $i; ?>" id="clientId_<?php echo $i; ?>" value="<?php echo $val['ClientId'] ;?>" /></td>
                    	<td width="7%" valign="top" align="center"><?php echo $val['Payment_Id']; ?></td>
                        <td width="7%" valign="top" align="center"><?php echo $val['Part_Number']; ?></td>
                        <td width="7%" valign="top" align="center"><?php echo $val['ClientId']; ?></td>
                        <td width="7%" valign="top" align="center"><?php echo $val['Transaction_Id']; ?></td>
                        <td width="10%" valign="top" align="center"><?php echo $val['Cheque_Date']; ?></td>
                        <td width="10%" valign="top" align="center"><?php echo $val['Cheque_Receiving_Date']; ?></td>
			<td width="7%" valign="top" align="center"><?php echo $checkDetails; ?></td>
			<td width="7%" valign="top" align="center"><?php echo $val['Payment_Mode']; ?></td>
			<td width="7%" valign="top" align="center"><?php echo $val['Sale_Type']; ?></td>
                        <td width="8%" valign="top" align="center"><?php echo $val['Amount_Received']; ?></td>
			<td width="8%" valign="top" align="center"><?php echo $val['DepositedByName']; ?></td>
			<td width="8%" valign="top" align="center"><?php echo $val['BranchName']; ?></td>
			<td width="5%" valign="top" align="center"><?php echo $val['isPaid']; ?></td>
                        <td width="6%" valign="top" align="center"><a href="/sums/Manage/editPayment/<?php echo $val['Payment_Id'].'/'.$val['Part_Number'].'/'.$prodId.'/'.$val['Transaction_Id']; ?>">Edit</a></td>
                    </tr>
		    <tr>	
			<td colspan="14"><div class="grayLine"></div></td>
		    </tr> 		
	<?php $i++; 
        } ?>
		<tr>
                    <td colspan="14">
				<div id="checkSelect_error" class="errorMsg"></div>
			</td>
                </tr>
		<tr>
                    <td colspan="14">
				<input type="hidden" name="noOfRes" id="noOfRes" value="<?php echo $i; ?>" />	 
				 <button class="btn-submit19" onclick="$('frmSelectTransact').action='/sums/Manage/doMultiplePayment';return validatePayementFormSums();" type="button"  style="width:190px">
                            <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Make Multiple Payment</p></div>
                        </button>
			<button class="btn-submit19" onclick="$('frmSelectTransact').action='/sums/Manage/updateMultiplePaymentStatus';return validatePayementFormSums(true);" type="button"  style="width:190px">
                            <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Upgrade To Paid</p></div>
                        </button>
			</td>
                </tr>
                </table>
        <?php } ?>
