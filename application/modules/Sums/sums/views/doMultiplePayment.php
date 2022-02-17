<?php
   $headerComponents = array(
      'css'			=> array('headerCms','raised_all','mainStyle','footer','cal_style'),
      'js'			=> array('user','tooltip','common','prototype','scriptaculous','utils','CalendarPopup'),
      'title'			=> 'SUMS - Add Base Product',
      'product' 		=> '',
      'displayname'	=> (isset($sumsUserInfo['validity'][0]['displayname'])?$sumsUserInfo['validity'][0]['displayname']:""),
   );
   $this->load->view('enterprise/headerCMS', $headerComponents);
   $this->load->view('enterprise/cmsTabs',$sumsUserInfo);
   $this->load->view('common/calendardiv');
?>
<script>
    var cal = new CalendarPopup("calendardiv");
    cal.offsetX = 20;
    cal.offsetY = 0;
</script>
<form method="POST" action="/sums/Manage/submitMultiplePayments" id="submitPayment" onSubmit="return validateMultiplePayments();">
<input type="hidden" value="<?php echo $pageInfo['id'];?>" name="Quotation_Id">
<input type="hidden" value="<?php echo $Transaction['TransactionId'];?>" name="Transaction_Id">
<div style="line-height:10px">&nbsp;</div>
<div class="mar_full_10p">
   <div style="width:223px; float:left">
	<?php 
		$leftPanelViewValue = 'leftPanelFor'.$prodId;
		$this->load->view('sums/'.$leftPanelViewValue); 
	?>
   </div>
   <div style="margin-left:233px">
	<table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td colspan="3" bgcolor="#FFFFFF">&nbsp;</td>
                </tr>	
		<tr>
                    <td colspan="3" bgcolor="#FFFFFF">&nbsp;</td>
                </tr>
                <tr>
                    <td width="10%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Payment ID</strong></td>
		    <td width="10%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Part Number</strong></td>
		    <td width="30%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Next Payment Date</strong>&nbsp;(Necessary only if amount to be paid is less than total amount.)</td>		
                    <td width="25%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Total Amount</strong></td>
		    <td width="25%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Amount To be Paid</strong></td>	
                </tr>
		<?php $i=0; foreach($PaymentDetails as $val){ 
				if($val['isPaid'] != 'Un-paid')
					continue;
				
				if(!in_array($val['Part_Number'],$PartNumbers[$val['Payment_Id']]))
					continue;
				
				$i++; 
		?>
		<tr>	
		    <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['Payment_Id'] ?></td>
		    <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['Part_Number'] ?></td>	
		    <td><input type="text" readonly name="nextPaymetDate_<?php echo $i; ?>" id="nextPaymetDate_<?php echo $i; ?>" value="" onclick="cal.select($('nextPaymetDate_<?php echo $i; ?>'),'cd_<?php echo $i; ?>','yyyy-MM-dd');"><img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="cd_<?php echo $i; ?>" onClick="cal.select($('nextPaymetDate_<?php echo $i; ?>'),'cd_<?php echo $i; ?>','yyyy-MM-dd');" /></td>		
                    <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['Amount_Received'] ?></td>
		    <td valign="top" style="padding:5px 10px 0px 10px"><input type="text" name="PaymentAmount_<?php echo $i; ?>" id="PaymentAmount_<?php echo $i; ?>"value = "" /><input type="hidden" name="paymentIdDetails_<?php echo $i; ?>"  id="paymentIdDetails_<?php echo $i; ?>" value="<?php echo $val['Payment_Id'].'#'.$val['Part_Number']; ?>" /><input type="hidden" name="totalAmount_<?php echo $i; ?>"  id="totalAmount_<?php echo $i; ?>" value="<?php echo $val['Amount_Received'] ?>" /></td>
		</tr>
		<?php }?>	
		<tr>
			<td colspan="5" align="left">
				<div id="PaymentAmtMsg" class="errorMsg"></div>	
			</td>
		</tr>
            </table>
		<input type="hidden" name="numberOfPayments" id="numberOfPayments" value="<?php echo $i; ?>" autocomplete="off" />
	    <div class="OrgangeFont fontSize_14p bld">Payment Details</div>
	 <div class="grayLine"></div>
	 <div class="lineSpace_10">&nbsp;</div>	
	 <div id="Payment_Details" >
	   <div>
	    <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Payment Mode:</div>
	    <div class="" style="margin-left:200px">
	    	<select name="Payment_Mode" id="Payment_Mode" formField="true">
	    		<option value="" >Select Payment Mode</option>
			<option value="Cash">Cash</option>
			<option value="Cheque">Cheque</option>
			<option value="Demand Draft">Demand Draft</option>
			<option value="Telegraphic Transfer">Telegraphic Transfer</option>
			<option value="Credit Card(Offline)">Credit Card(Offline)</option>
	    	</select>
	    </div>
	 </div>
	 <div class="lineSpace_10">&nbsp;</div>	
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">CHEQUE/DD/TT No./ORDER No:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" name="Cheque_No" id="Cheque_No" formField="true" value="" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Receipt Date:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" readonly name="Cheque_Date" id="Cheque_Date" formField="true" value="" onclick="cal.select($('Cheque_Date'),'cd','yyyy-MM-dd');"><img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="cd" onClick="cal.select($('Cheque_Date'),'cd','yyyy-MM-dd');" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">CHEQUE City:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" name="Cheque_City" id="Cheque_City" formField="true" value="" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">CHEQUE Bank:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" name="Cheque_Bank" id="Cheque_Bank" formField="true" value="" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Cheque/DD Date:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" readonly name="Cheque_Receiving_Date" formField="true" id="Cheque_Receiving_Date" value="" onclick="cal.select($('Cheque_Receiving_Date'),'rd','yyyy-MM-dd');"><img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="rd" onClick="cal.select($('Cheque_Receiving_Date'),'rd','yyyy-MM-dd');" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Payment Amount:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" name="Amount_Received" id="Amount_Received" formField="true" value="" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">TDS Amount:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" name="TDS_Amount" id="TDS_Amount" formField="true" value="" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Comments:</div>
	       <div class="" style="margin-left:200px">
		  <textarea name="Cheque_DD_Comments" id="Cheque_DD_Comments" formField="true"></textarea>
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Deposited By:</div>
	       <div class="" style="margin-left:200px">
		  <select name="Depositedby" id="Depositedby" formField="true" size="4">
				<option value="">Select User Name</option> 
                                <?php foreach ($quoteUsers as $user) {
                                        ?>
                                        <option value="<?php echo $user['userid'];?>" <?php echo $selected; ?>><?php echo $user['displayname']." : ".$user['BranchName'];?></option> 
                            <?php } ?>
                  </select>
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Branch Name:</div>
	       <div class="" style="margin-left:200px">
		<select name="branch_name" id="branch_name" formField="true" size="4">
				<option value="">Select Branch</option> 
                                <?php foreach ($branchList as $branch) {
                                        ?>
                                        <option value="<?php echo $branch['BranchId'];?>" <?php echo $selected; ?>><?php echo $branch['BranchName'];?></option> 
                            <?php } ?>
                  </select>
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>		
		
	     <div>
		<button class="btn-submit19" type="Submit"  style="width:190px">
                            <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Submit Multiple Payment</p></div>
                        </button>
	
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>	
	    <div id="MutiplePaymentMsg" class="errorMsg"></div>	
	    <div id="error" class="errorMsg"></div>	
	    <div class="grayLine"></div>	    
	    <div class="lineSpace_10">&nbsp;</div>
	 </div>

<div class="lineSpace_10">&nbsp;</div>
</form>
</body>
</html>
	
<script>
<?php if($editvariable == 'true')  {?>
alert("Receipt Date can not be less than today's date.");
<?php } ?>

function checkAmount()
{
   var noOfPayments = $('numberOfPayments').value;
   var Amount = 0;
   for(var i=1;i<=noOfPayments;i++){
	 if ($('PaymentAmount_'+i).value != "") {
		var amountEntered = parseFloat($('PaymentAmount_'+i).value);	
		if(isNaN(amountEntered)){
			amountEntered = 0;
		}		
	       Amount += parseFloat(amountEntered);
	 }
   }	
   var totalAmount = 0;
   var Received_Amount = parseFloat($('Amount_Received').value);
   if(isNaN(Received_Amount)){
	Received_Amount = 0;
   }
   totalAmount += Received_Amount;		
   var tdsAmountEntered = parseFloat($('TDS_Amount').value);	
   if(isNaN(tdsAmountEntered)){
	tdsAmountEntered = 0;
   }
   totalAmount += tdsAmountEntered;		
   if (Amount != totalAmount)
   {
	 $('error').innerHTML = "The summation of Payment Amount and TDS amount should be equal to summation of all the (Amount To Be Paid) fields.";
	 return false;
   }
   return true;
}

function validateMultiplePayments(){
	var noOfPayments = $('numberOfPayments').value;
	$('PaymentAmtMsg').innerHTML = '';
	$('MutiplePaymentMsg').innerHTML = '';	
	$('error').innerHTML = '';
	for(var i=1;i<=noOfPayments;i++){
		if($('PaymentAmount_'+i).value == "")
		{
			$('PaymentAmtMsg').innerHTML = "Please fill the payment amounts for all payments.";
			return false;
		}
		if(parseFloat($('PaymentAmount_'+i).value) > parseFloat($('totalAmount_'+i).value))
		{
			$('PaymentAmtMsg').innerHTML = "Payment amount to be paid can not be greater than total amount.";
			return false;
		}	
		if(parseFloat($('PaymentAmount_'+i).value) != parseFloat($('totalAmount_'+i).value))
		{
			var tempDate = new Date();
			var currDate = tempDate.getFullYear()+'/'+(tempDate.getMonth()+1)+'/'+tempDate.getDate();
			var tempUserDate = $('nextPaymetDate_'+i).value;
			tempUserDate = 	tempUserDate.replace(/-/g,'/');
			var dateRes = compareDate(currDate,tempUserDate);
			var paymentIdInfo = ($('paymentIdDetails_'+i).value).split("#");
			if($('nextPaymetDate_'+i).value==""){
				$('MutiplePaymentMsg').innerHTML = "Please fill the dates of next payment for payment ID "+paymentIdInfo[0]+" and  part number "+paymentIdInfo[1]+".";
				return false;
			}else if(dateRes != 2){
				$('MutiplePaymentMsg').innerHTML = "Next payment date can not be less than or equal to current date for payment ID "+paymentIdInfo[0]+" and  part number "+paymentIdInfo[1]+".";
				return false;				
			}
		}	
	}
	if($('Payment_Mode').value == "")
	{
		$('MutiplePaymentMsg').innerHTML = "Please select the payment mode.";
		return false;
	}
	if($('Cheque_DD_Comments').value.length < 10)
	{
		$('MutiplePaymentMsg').innerHTML = "Please enter comments with minimum 10 characters.";
		return false;
	}
	if($('Cheque_Date').value == ""){
		$('MutiplePaymentMsg').innerHTML = "Please enter comments with minimum 10 characters.";
	 	return false;
	}else{
		var tempDate = new Date();
		var currDate = tempDate.getFullYear()+'/'+(tempDate.getMonth()+1)+'/'+tempDate.getDate();
		var tempUserDate = $('Cheque_Date').value;
		tempUserDate = 	tempUserDate.replace(/-/g,'/');
		var dateRes = compareDate(currDate,tempUserDate);
		if(dateRes == 1){
			$('MutiplePaymentMsg').innerHTML = "Receipt date can not be prior to current date.";
		 	return false;	
		}	
	}	
	if(!checkFieldsForPaymentModes($('Payment_Mode').value))	
		return false;
	
	if(!checkAmount())
		return false;
	
	return true;
}	

function in_array(needle, haystack, strict) {
    for(var i = 0; i < haystack.length; i++) {
        if(strict) {
            if(haystack[i] === needle) {
                return true;
            }
        } else {
            if(haystack[i] == needle) {
                return true;
            }
        }
    }

    return false;
}

function checkFieldsForPaymentModes(paymentModeChoice)
{
	var cashArray = new Array('Cheque_Date','Amount_Received','Depositedby','branch_name');
	var ttCreditArray = new Array('Cheque_Date','Cheque_Receiving_Date','Amount_Received','Cheque_No','Depositedby','branch_name');	
	var checkDDArray = new Array('Cheque_Date','Cheque_Receiving_Date','Amount_Received','Cheque_No','Cheque_City','Cheque_Bank','Depositedby','branch_name');
	var objForm = $('submitPayment');
	for(var formElementsCount=0; formElementsCount<objForm.elements.length; formElementsCount++)
	{
        	var formElement = objForm.elements[formElementsCount];
		if(formElement.getAttribute('formField') && (formElement.getAttribute('formField') == "true"))	
			var basicId = formElement.id;
	
		switch(paymentModeChoice)
		{
			case 'Cash': 	
					if(in_array(basicId,cashArray,false) && ($(basicId).value == ''))
					{	
					$('error').innerHTML = 'Please enter Receipt Date,Cheque/DD Date,payment amount,deposited by and branch name for given mode of payment';
					return false;
					}
					break;
			case 'Cheque':
					if(in_array(basicId,checkDDArray,false) && ($(basicId).value == ''))
					{	
					$('error').innerHTML = 'Please enter cheque/DD no.,Receipt Date,cheque/DD city,check/DD bank,Cheque/DD Date,payment amount,deposited by and branch name for given mode of payment';
					return false;
					}
					break;
			case 'Demand Draft':
					if(in_array(basicId,checkDDArray,false) && ($(basicId).value == ''))
					{
					$('error').innerHTML = 'Please enter cheque/DD no.,Receipt Date,cheque/DD city,check/DD bank,Cheque/DD Date,payment amount,deposited by and branch name for given mode of payment';
					return false;
					}
					break;
			case 'Telegraphic Transfer':
					if(in_array(basicId,ttCreditArray,false) && ($(basicId).value == ''))
					{	
					$('error').innerHTML = 'Please enter TT/Order no.,Receipt Date,Cheque/DD Date,payment amount,deposited by and branch name for given mode of payment';
					return false;
					}
					break;		
			case 'Credit Card(Offline)':
					if(in_array(basicId,ttCreditArray,false) && ($(basicId).value == ''))
					{	
					$('error').innerHTML = 'Please enter TT/Order no.,Receipt Date,Cheque/DD Date,payment amount,deposited by and branch name for given mode of payment';
					return false;
					}
					break;
					
		}
	}
	return true;
}
</script>
