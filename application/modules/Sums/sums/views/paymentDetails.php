<?php
   $headerComponents = array(
      'css'			=> array('headerCms','raised_all','mainStyle','footer','cal_style'),
      'js'			=> array('user','tooltip','common','newcommon','listing','prototype','scriptaculous','utils','CalendarPopup'),
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
<form method="POST" action="/sums/Manage/submitPayment" id="submitPayment" >
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
     <div class="OrgangeFont fontSize_14p bld">Payment Details</div>
	 <div class="grayLine"></div>
	 <div class="lineSpace_10">&nbsp;</div>
	 <div>
	    <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Sale Type:</div>
	    <div style="margin-left:200px">
	       <select name="Sale_Type" id="Sale_Type" onchange="javascript:showPartOption();">
		  <option value="">Select Sale Type</option>
		  <option value="Part Payment" <?php if ($Transaction['Sale_Type']=="Part Payment") echo "selected"; ?>>Part Payment</option>
		  <option value="Credit" <?php if ($Transaction['Sale_Type']=="Credit") echo "selected"; ?> >Credit</option>
		  <option value="Full Payment"  <?php if ($Transaction['Sale_Type']=="Full Payment") echo "selected"; ?> >Full Payment</option>
		  <option value="Trial" <?php if ($Transaction['Sale_Type']=="Trial") echo "selected"; ?> >Trial</option>
	    	</select>
	    </div>
	 </div>
	 <div class="lineSpace_10">&nbsp;</div>
	 <div>
	    <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Final Sales Amount:</div>
	    <div class="" style="margin-left:200px">
	       <input type="text" name="Final_Sales_Amount" id="Final_Sales_Amount" value="<?php echo $TotalPrice;?>" readonly>
	    </div>
	 </div>
	 <div class="lineSpace_10">&nbsp;</div>
	 <?php $partPaymentsArray = array();
		 for($i=0;$i<10;$i++) { 
		$isPaid = (isset($Payment[$i]['isPaid']) && ($Payment[$i]['isPaid'] == 1))?1:0;	
		array_push($partPaymentsArray,$isPaid);
	?>
	<input type="hidden" name="Payment_Id[]" id="Payment_Id<?php echo $i;?>" value="<?php echo isset($Payment[$i]['Payment_Id'])?$Payment[$i]['Payment_Id']:'';?>" />		
	 <div <?php if ($i>0 && count($Payment)<=$i) echo "style='display:none'"; ?> id="part<?php echo $i;?>" >
	   <div>
	    <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Payment Mode:</div>
	    <div class="" style="margin-left:200px">
	    	<select name="Payment_Mode[]" id="Payment_Mode<?php echo $i;?>">
	    		<option value="" >Select Payment Mode</option>
			<option value="Cash"  <?php if ($Payment[$i]['Payment_Mode']=="Cash") echo "selected"; ?>>Cash</option>
			<option value="Cheque" <?php if ($Payment[$i]['Payment_Mode']=="Cheque") echo "selected"; ?>>Cheque</option>
			<option value="Demand Draft" <?php if ($Payment[$i]['Payment_Mode']=="Demand Draft") echo "selected"; ?>>Demand Draft</option>
			<option value="Telegraphic Transfer" <?php if ($Payment[$i]['Payment_Mode']=="Telegraphic Transfer") echo "selected"; ?>>Telegraphic Transfer</option>
			<option value="Credit Card(Offline)" <?php if ($Payment[$i]['Payment_Mode']=="Credit Card(Offline)") echo "selected"; ?>>Credit Card(Offline)</option>
			<option value="Later" <?php if ($Payment[$i]['Payment_Mode']=="Later") echo "selected"; ?>>Later</option>
	    	</select>
	    </div>
	 </div>
	 <div class="lineSpace_10">&nbsp;</div>	
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">CHEQUE/DD/TT No./ORDER No:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" name="Cheque_No[]" id="Cheque_No<?php echo $i;?>" value="<?php echo $Payment[$i]['Cheque_No'];?>" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Receipt Date:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" readonly name="Cheque_Date[]" id="Cheque_Date<?php echo $i;?>" value="<?php echo substr($Payment[$i]['Cheque_Date'],0,10);?>" onclick="cal.select($('Cheque_Date<?php echo $i; ?>'),'cd<?php echo $i;?>','yyyy-MM-dd');"><img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="cd<?php echo $i;?>" onClick="cal.select($('Cheque_Date<?php echo $i;?>'),'cd<?php echo $i;?>','yyyy-MM-dd');" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">CHEQUE City:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" name="Cheque_City[]" id="Cheque_City<?php echo $i;?>" value="<?php echo $Payment[$i]['Cheque_City'];?>" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">CHEQUE Bank:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" name="Cheque_Bank[]" id="Cheque_Bank<?php echo $i;?>" value="<?php echo $Payment[$i]['Cheque_Bank'];?>" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Cheque/DD Date:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" readonly name="Cheque_Receiving_Date[]" id="Cheque_Receiving_Date<?php echo $i;?>" value="<?php echo substr($Payment[$i]['Cheque_Receiving_Date'],0,10);?>" onclick="cal.select($('Cheque_Receiving_Date<?php echo $i;?>'),'rd<?php echo $i;?>','yyyy-MM-dd');"><img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="rd<?php echo $i;?>" onClick="cal.select($('Cheque_Receiving_Date<?php echo $i;?>'),'rd<?php echo $i;?>','yyyy-MM-dd');" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Payment Amount:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" name="Amount_Received[]" id="Amount_Received<?php echo $i;?>" value="<?php echo $Payment[$i]['Amount_Received'];?>" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">TDS Amount:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" name="TDS_Amount[]" id="TDS_Amount<?php echo $i;?>" value="<?php echo $Payment[$i]['TDS_Amount'];?>" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Deposited By:</div>
	       <div class="" style="margin-left:200px">
		  <select name="Deposited_By[]" id="Deposited_By<?php echo $i;?>" size="4">
				<option value="">Select User Name</option> 
                                <?php foreach ($SalesUsers as $user) {
                                        if ($Payment[$i]['Deposited_By']==$user['userid']) {
                                            $selected = "selected";
                                        } else {
                                            $selected = "";
                                        } 
                                        ?>
                                        <option value="<?php echo $user['userid'];?>" <?php echo $selected; ?>><?php echo $user['displayname']." : ".$user['BranchName'];?></option> 
                            <?php } ?>
                  </select>
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Deposited Branch:</div>
	       <div class="" style="margin-left:200px">
		   <select name="Deposited_Branch[]" id="Deposited_Branch<?php echo $i;?>" size="4">
				<option value="">Select Branch</option> 
                                <?php foreach ($branchList as $branch) {
                                        if ($Payment[$i]['Deposited_Branch'] == $branch['BranchId']) {
                                            $selected = "selected";
                                        } else {
                                            $selected = "";
                                        } 
                                        ?>
                                        <option value="<?php echo $branch['BranchId'];?>" <?php echo $selected; ?>><?php echo $branch['BranchName'];?></option> 
                            <?php } ?>
                  </select>
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Comments:</div>
	       <div class="" style="margin-left:200px">
		  <textarea name="Cheque_DD_Comments[]" id="Cheque_DD_Comments<?php echo $i;?>"><?php echo $Payment[$i]['Cheque_DD_Comments'];?></textarea>
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	     <div>
	      <?php if(array_key_exists(22,$sumsUserInfo['sumsuseracl']))
		{
			if(($isPaid == 0) && isset($viewTrans) && ($viewTrans == 1)) {?>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px"><span <?php if(($isPaid == 0) && (isset($viewTrans) && ($viewTrans == 1)) ) echo "style=\"display:none;\""; ?> >Payment Done:</span></div>
	       <div class="" style="margin-left:200px;display:none">
		  <span>
		 
		  <input type="checkbox" name="PaymentDone[]" id="PaymentDone<?php echo $i;?>" value="1" <?php if($isPaid == 1) echo "checked"; else if(isset($viewTrans) && ($viewTrans == 1)) echo "style=\"display:none;\"";  ?> /> </span>
		  <span style="margin-left:60%;">
		
			
		<input type="button" onclick="javascript:MakePayment(<?php echo $i;?>);" value="Make Payment" id="MakePaymentBut<?php echo $i;?>">
		</span>
		
			
	       </div>
	       <?php }
	       else
	       {?>
	       	 <div class="bld txt_align_r fontSize_12p float_L" style="width:191px;display:none;"><span <?php if(($isPaid == 0) && (isset($viewTrans) && ($viewTrans == 1)) ) echo "style=\"display:none;\""; ?> >Payment Done:</span></div>
	       <div class="" style="margin-left:200px;display:none;">
		  <span>
		 
		  <input type="checkbox" name="PaymentDone[]" id="PaymentDone<?php echo $i;?>" value="1" <?php if($isPaid == 1) echo "checked"; else if(isset($viewTrans) && ($viewTrans == 1)) echo "style=\"display:none;\"";  ?> /> </span>
		  <span style="margin-left:60%;">
		
			
		<input type="button" onclick="javascript:MakePayment(<?php echo $i;?>);" value="Make Payment" id="MakePaymentBut<?php echo $i;?>">
		</span>
		
			
	       </div>
	       <?php }
	        }
	       else
	       {?>
	       	 <div class="bld txt_align_r fontSize_12p float_L" style="width:191px;display:none;"><span <?php if(($isPaid == 0) && (isset($viewTrans) && ($viewTrans == 1)) ) echo "style=\"display:none;\""; ?> >Payment Done:</span></div>
	       <div class="" style="margin-left:200px;display:none;">
		  <span>
		 
		  <input type="checkbox" name="PaymentDone[]" id="PaymentDone<?php echo $i;?>" value="1" <?php if($isPaid == 1) echo "checked"; else if(isset($viewTrans) && ($viewTrans == 1)) echo "style=\"display:none;\"";  ?> /> </span>
		  <span style="margin-left:60%;">
		
			
		<input type="button" onclick="javascript:MakePayment(<?php echo $i;?>);" value="Make Payment" id="MakePaymentBut<?php echo $i;?>">
		</span>
		
			
	       </div>
	       <?php } ?>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>	
	    <div id="MakePaymentMsg<?php echo $i;?>" class="errorMsg"></div>	
	    <div class="grayLine"></div>	    
	    <div class="lineSpace_10">&nbsp;</div>
	 </div>
	 <?php } ?>
	 <div id="partOption" <?php if((count($Payment)==0) ||(isset($viewTrans) && ($viewTrans == 1))) echo "style=\"display:none;\"";?>><input type="button" onclick="javascript:addPart(true);" value="Add Part Payment" id="addPartBut" /><input type="button" onclick="javascript:addPart(false);" value="Hide Part Payment" id="cancelPartBut" disabled="true"/></div>

	 <div class="lineSpace_10">&nbsp;</div>

	 <div class="OrgangeFont fontSize_14p bld">Other Transaction Details</div>
	 <div class="grayLine"></div>
	 <div class="lineSpace_10">&nbsp;</div>
	 <div>
	    <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Sale Made By:</div>
	    <div class="" style="margin-left:200px">
		<?php if(!isset($SalesBy)){ ?>
	       <select name="Sales_By" id="Sales_By" size="4" onchange="fetchBranchesForExecutive();">
		  <?php foreach ($SalesUsers as $user) { ?>
                  <option value="<?php echo $user['userid'];?>"><?php echo $user['displayname']." : ".$user['BranchName'];?></option> 
		  <?php } ?>
	       </select>
	      <?php }else{  ?>
		<span class="OrgangeFont fontSize_12p bld"><?php echo $SalesBy; ?></span>	
		<?php } ?>
	    </div>
	 </div>
	 <div class="lineSpace_10">&nbsp;</div>
	 <div id="Sales_By_error" class="errorMsg"></div>	 
	 <div>
             <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Sales Branch:</div>
             <div class="" style="margin-left:200px" id="Sales_Branch_div">
                <select name="Sales_Branch" size="1">
	       </select>
	    </div>
	 </div>
	 <div class="lineSpace_10">&nbsp;</div>
         <div id="Sales_Branch_error" class="errorMsg"></div>	 
	 <div class="OrgangeFont fontSize_14p bld">Customer Billing Address</div>
	 <div class="grayLine"></div>
	 <div class="lineSpace_10">&nbsp;</div>
	 <div>
	    <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Contact Address:</div>
	    <div class="" style="margin-left:200px">
                <textarea name="Address" id="Address" ><?php echo $ClientDetails['contactAddress'];?></textarea>
	    </div>
	 </div>
	 <div class="lineSpace_10">&nbsp;</div>
	 <div>
	    <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Country:</div>
	    <div class="" style="margin-left:200px">
                <input type="text" name="Country" id="Country" value="<?php echo $ClientDetails['countryName'];?>" />
	    </div>
	 </div>
	 <div class="lineSpace_10">&nbsp;</div>
	 <div>
	    <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">City:</div>
	    <div class="" style="margin-left:200px">
                <input type="text" name="City" id="City" value="<?php echo $ClientDetails['cityName'];?>" />
	    </div>
	 </div>
	 <div class="lineSpace_10">&nbsp;</div>
	 <div>
	    <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Pincode:</div>
	    <div class="" style="margin-left:200px">
                <input type="text" name="Pincode" id="Pincode" value="<?php echo $ClientDetails['pincode'];?>" />
	    </div>
	 </div>
	 <div class="lineSpace_10">&nbsp;</div>
	 <div>
	    <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Phone No:</div>
	    <div class="" style="margin-left:200px">
                <input type="text" name="Phone_No" id="Phone_No" value="<?php echo $ClientDetails['mobile'];?>" />
	    </div>
	 </div>
	 <div class="lineSpace_10">&nbsp;</div>
	 <div>
	    <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Email Address:</div>
	    <div class="" style="margin-left:200px">
                <input type="text" name="Email_Address" id="Email_Address" value="<?php echo $ClientDetails['email'];?>" />
	    </div>
	 </div>
	 <div class="lineSpace_10">&nbsp;</div>
	 <div id="contact_details_error" class="errorMsg"></div>
	 <div class="lineSpace_10">&nbsp;</div>
	 <div class="errorMsg" id="error"></div>
	 <div id="hiddenVals"></div>
	 <?php if(!(isset($viewTrans) && ($viewTrans == 1))) { ?>
	 <input type="button" value="Submit" onclick="createHiddenVals();">
	<?php } ?>	
</div>
<div class="lineSpace_10">&nbsp;</div>
<div class="lineSpace_10">&nbsp;</div>
<div class="lineSpace_10">&nbsp;</div>
</form>
<form name="MakePaymentForm" id="MakePaymentForm" action="" method="POST" style="display:none;">
	<input type="hidden" name="Payment_Mode_MakePayment" id="Payment_Mode_MakePayment" value="" />
	<input type="hidden" name="Cheque_No_MakePayment" id="Cheque_No_MakePayment" value="" />
	<input type="hidden" name= "Cheque_Date_MakePayment" id="Cheque_Date_MakePayment" value=""/>
	<input type="hidden" name="Cheque_City_MakePayment" id="Cheque_City_MakePayment" value="" />
	<input type="hidden" name="Cheque_Bank_MakePayment" id="Cheque_Bank_MakePayment" value="" />
	<input type="hidden" name="Cheque_Receiving_Date_MakePayment" id="Cheque_Receiving_Date_MakePayment" value="" />
	<input type="hidden" name="Amount_Received_MakePayment" id="Amount_Received_MakePayment" value="" />
	<input type="hidden" name="TDS_Amount_MakePayment" id="TDS_Amount_MakePayment" value="" />
	<input type="hidden" name="Cheque_DD_Comments_MakePayment" id="Cheque_DD_Comments_MakePayment" value="" />	
	<input type="hidden" name="Payment_Id_MakePayment" id="Payment_Id_MakePayment" value=""/>
	<input type="hidden" name="partNumber_MakePayment" id="partNumber_MakePayment" value=""/>	
	<input type="hidden" name="Deposited_By_MakePayment" id="Deposited_By_MakePayment" value=""/>
	<input type="hidden" name="Deposited_Branch_MakePayment" id="Deposited_Branch_MakePayment" value=""/>
</form>
</body>
</html>
<?php 
	$jsonPartArray = json_encode($partPaymentsArray);	
?>
<script>
var propCount = <?php if(isset($Payment)) echo count($Payment); else echo "1";?>;
var propMaxCount = 10;
var viewTrans = <?php echo (isset($viewTrans) && ($viewTrans == 1))?1:0; ?>;
var partPaymentArray = eval(<?php echo $jsonPartArray; ?>);
var pageUrl = '/sums/Quotation/viewTransaction/<?php echo $UIQuotationId.'/'.$prodId; ?>';
var trialSelected = 0;
function addPart(valueToBeSet)
{
	if(valueToBeSet)
	{
		$('part'+propCount).style.display = "";
		propCount++;
		$('cancelPartBut').disabled = false;
		if (propCount == propMaxCount)
		{
			$('addPartBut').disabled = true;
		}
	}
	else
	{
		propCount--;
		$('part'+propCount).style.display = "none";
		$('addPartBut').disabled = false;
		if (propCount == 1)
		{
			$('cancelPartBut').disabled = true;
		}
	}
}

function disableFieldsForViewTransaction()
{
	disableElement('Sale_Type');
	disableElement('Payment_Mode');
	disableElement('Final_Sales_Amount');	
	var objForm = $('MakePaymentForm');
	for(var i=0;i<partPaymentArray.length;i++)
	{	
		for(var formElementsCount=0; formElementsCount<objForm.elements.length; formElementsCount++) {
	        	var formElement = objForm.elements[formElementsCount];
			var StringLocation = formElement.id.indexOf('_MakePayment');
			var basicId = formElement.id.substring(0,StringLocation);
			disableElement(basicId+i)
		}
		disableElement('PaymentDone'+i);
	}	
	disableElement('Address');
	disableElement('Country');
	disableElement('City');
	disableElement('Pincode');
	disableElement('Phone_No');
	disableElement('Email_Address');
}

if(viewTrans == 1)
{
	disableFieldsForViewTransaction();
}	

function MakePayment(partNum)
{
	$('MakePaymentMsg'+partNum).innerHTML = '';
	if($('Payment_Mode'+partNum) && ($('Payment_Mode'+partNum).value == ''))
	{
	$('MakePaymentMsg'+partNum).innerHTML = 'Please select the payment mode first';	
	$('error').innerHTML = 'Error found on form.Please correct it to proceed.';
	return false;
	}
	$('partNumber_MakePayment').value = partNum;
	var objForm = $('MakePaymentForm');
	for(var formElementsCount=0; formElementsCount<objForm.elements.length; formElementsCount++)
	{
        	var formElement = objForm.elements[formElementsCount];
		var StringLocation = formElement.id.indexOf('_MakePayment');
		var basicId = formElement.id.substring(0,StringLocation);
		
		if(!checkFieldsForPaymentModes(partNum,basicId))
			return false;
		else if($(basicId+partNum))
			$(basicId+'_MakePayment').value = $(basicId+partNum).value;
		
	}
	disableAllPaymentButtons();
	new Ajax.Updater('MakePaymentMsg'+partNum,'/sums/Quotation/MakePartPayment',{parameters:Form.serialize($('MakePaymentForm')), onComplete:function (request) { javascript:makePaymentResult(request.responseText)}
		}); return false;
}

function disableAllPaymentButtons()
{
	for(i=0;i<propCount;i++)
	{
		disableElement('MakePaymentBut'+i);
	}
	return true;
}

function checkFieldsForPaymentModes(partNum,basicId)
{
	var cashArray = new Array('Cheque_Date','Cheque_Receiving_Date','Amount_Received');
	var ttCreditArray = new Array('Cheque_Date','Cheque_Receiving_Date','Amount_Received','Cheque_No');	
	var checkDDArray = new Array('Cheque_Date','Cheque_Receiving_Date','Amount_Received','Cheque_No','Cheque_City','Cheque_Bank');
	var laterArray = new Array('Cheque_Date','Amount_Received');	
	if($(basicId+partNum))
	{
		var paymentChoice = $('Payment_Mode'+partNum).value;
		switch(paymentChoice)
		{
			case 'Cash': 	
					if(in_array(basicId,cashArray,false) && ($(basicId+partNum).value == ''))
					{	
					$('MakePaymentMsg'+partNum).innerHTML = 'Please enter Receipt Date,Cheque/DD Date and payment amount for given mode of payment';
					$('error').innerHTML = 'Error found on form.Please correct it to proceed.';
					return false;
					}
					break;
			case 'Cheque':
					if(in_array(basicId,checkDDArray,false) && ($(basicId+partNum).value == ''))
					{	
					$('MakePaymentMsg'+partNum).innerHTML = 'Please enter cheque/DD no.,Receipt Date,cheque/DD city,check/DD bank,Cheque/DD Date and payment amount for given mode of payment';
					$('error').innerHTML = 'Error found on form.Please correct it to proceed.';
					return false;
					}
					break;
			case 'Demand Draft':
					if(in_array(basicId,checkDDArray,false) && ($(basicId+partNum).value == ''))
					{
					$('MakePaymentMsg'+partNum).innerHTML = 'Please enter cheque/DD no.,Receipt Date,cheque/DD city,check/DD bank,Cheque/DD Date and payment amount for given mode of payment';
					$('error').innerHTML = 'Error found on form.Please correct it to proceed.';		
					return false;
					}
					break;
			case 'Telegraphic Transfer':
					if(in_array(basicId,ttCreditArray,false) && ($(basicId+partNum).value == ''))
					{	
					$('MakePaymentMsg'+partNum).innerHTML = 'Please enter TT/Order no.,Receipt Date,Cheque/DD Date and payment amount for given mode of payment';
					$('error').innerHTML = 'Error found on form.Please correct it to proceed.';	
					return false;
					}
					break;		
			case 'Credit Card(Offline)':
					if(in_array(basicId,ttCreditArray,false) && ($(basicId+partNum).value == ''))
					{	
					$('MakePaymentMsg'+partNum).innerHTML = 'Please enter TT/Order no.,Receipt Date,Cheque/DD Date and payment amount for given mode of payment';
					$('error').innerHTML = 'Error found on form.Please correct it to proceed.';
					return false;
					}
					break;
			
			case 'Later':
					if(in_array(basicId,laterArray,false) && ($(basicId+partNum).value == ''))
					{	
					$('MakePaymentMsg'+partNum).innerHTML = 'Please enter Receipt Date,payment amount for given mode of payment';
					$('error').innerHTML = 'Error found on form.Please correct it to proceed.';
					return false;
					}
					break;
					
		}
	}
	return true;
}

function makePaymentResult(response)
{
	if(trim(response) == 1)
		window.location = pageUrl;
}

function checkAmount()
{
   var Amount = 0;
   for(i=0;i<propCount;i++)
   {
	 if ($('Amount_Received'+i).value != "") {
		var tdsAmount = parseFloat($('TDS_Amount'+i).value);
		var amountEntered = parseFloat($('Amount_Received'+i).value);
	       if(isNaN(tdsAmount)){	
			tdsAmount = 0;
		}	
		if(isNaN(amountEntered)){
			amountEntered = 0;
		}
		var temp = 0;
		temp = parseFloat(tdsAmount) + parseFloat(amountEntered);			
	       Amount += temp;
	 }
	 }	
   if (Amount != $('Final_Sales_Amount').value)
   {
	 $('error').innerHTML = "Please fill the Amount Properly";
	 return false;
   }
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
function clearErrorFields()
{	
	var errorPlaces = getElementsByClassName(document.body,'div','errorMsg');
	for(var i=0;i<errorPlaces.length;i++)
	{
		errorPlaces[i].innerHTML = '';
	}
}
function validatePaymentDetails()
{
	clearErrorFields();
	if($('Sale_Type').value == '')
	{	
		$('MakePaymentMsg0').innerHTML = 'Please select sale type to contine';
		$('error').innerHTML = 'Error found on form.Please correct it to proceed.';
		return false;
	}	
	else if($('Final_Sales_Amount').value == '')
	{	
		$('MakePaymentMsg0').innerHTML = 'Please enter final sales amount to contine';
		$('error').innerHTML = 'Error found on form.Please correct it to proceed.';
		return false;
	}
	for(i=0;i<propCount;i++)
	{	
		if($('Sale_Type').value == 'Trial'){
			break;
		}
		else if($('Sale_Type').value == 'Credit'){
			if(($('Cheque_Date'+i).value=='') || ($('Amount_Received'+i).value==''))
			{
				$('MakePaymentMsg'+i).innerHTML = 'Please enter Receipt Date and amount';
				$('error').innerHTML = 'Error found on form.Please correct it to proceed.';
				return false;
			}
			continue;
		}
		$('MakePaymentMsg'+i).innerHTML = '';
		if($('Payment_Mode'+i) && ($('Payment_Mode'+i).value == ''))
		{
		$('MakePaymentMsg'+i).innerHTML = 'Please select the payment mode first';	
		$('error').innerHTML = 'Error found on form.Please correct it to proceed.';
		return false;
		}
		var objForm = $('MakePaymentForm');
		for(var formElementsCount=0; formElementsCount<objForm.elements.length; formElementsCount++)
		{
	        	var formElement = objForm.elements[formElementsCount];
			var StringLocation = formElement.id.indexOf('_MakePayment');
			var basicId = formElement.id.substring(0,StringLocation);
			if(formElementsCount > 7)
				continue;
			
			if(!checkFieldsForPaymentModes(i,basicId))
				return false;
		}
	 <?php 
	 if($validationcheck == 'check'){
	 ?>
		if($('Cheque_Date'+i).value == ""){
			$('MakePaymentMsg'+i).innerHTML = 'Receipt date can not be prior to current date.';	
			$('error').innerHTML = 'Error found on form.Please correct it to proceed.';
			return false;
		}else{
			var tempDate = new Date();
			var currDate = tempDate.getFullYear()+'/'+(tempDate.getMonth()+1)+'/'+tempDate.getDate();
			var tempUserDate = $('Cheque_Date'+i).value;
			tempUserDate = 	tempUserDate.replace(/-/g,'/');
			var dateRes = compareDate(currDate,tempUserDate);
			if(dateRes == 1){
				$('MakePaymentMsg'+i).innerHTML = 'Receipt date can not be prior to current date.';	
				$('error').innerHTML = 'Error found on form.Please correct it to proceed.';
				return false;	
			}
                        if($('Cheque_Date'+i).value < $('Cheque_Receiving_Date'+i).value)
                     {
                             $('MakePaymentMsg'+i).innerHTML = "Receipt Date can not be less than Cheque/DD Date";
                             $('error').innerHTML = "Error found on form.Please correct it to proceed.";
                             return false;
                     }
	  
		     
		  
		
		}
		
	<?php }?> 	
		
	}
	if($('Cheque_DD_Comments0').value.length < 10)
	{
		$('MakePaymentMsg0').innerHTML = 'Please enter comments for this payment (min 10 chars).';
		$('error').innerHTML = 'Error found on form.Please correct it to proceed.';
		return false;		
	}
	if($('Sales_By').value == '')
	{	
                $('Sales_By_error').innerHTML = 'Please select the user who made the sale.';
		$('error').innerHTML = 'Error found on form.Please correct it to proceed.';
		return false;
	}	
        if($('Sales_Branch').value == '')
	{	
                $('Sales_Branch_error').innerHTML = 'Please select the branch where the Sale should be registered.';
		$('error').innerHTML = 'Error found on form.Please correct it to proceed.';
		return false;
	}	
	var addressArray = new Array('Address','Country','City','Pincode','Phone_No','Email_Address');
	for(var i=0;i<addressArray.length;i++)
	{
		if($(addressArray[i]).value == '')
		{	
			$('contact_details_error').innerHTML = 'Please enter the customer billing address and contact details properly.';
			return false;
		}
	}
	return true;
}

function createHiddenVals()
{  	
   if(!validatePaymentDetails())
	return false;
	
   if($('Sale_Type').value == 'Trial')
   {
	var tot = document.createElement('input');
	tot.type='hidden';
	tot.value =propCount;
	tot.name = 'totalParts';
	$('hiddenVals').appendChild(tot);
	setValuesForTrial(false);
	$('submitPayment').submit();
	return true;
   }
		
   if (checkAmount())
   {
	 var tot = document.createElement('input');
	 tot.type='hidden';
	 tot.value =propCount;
	 tot.name = 'totalParts';
	 $('hiddenVals').appendChild(tot);
	 $('submitPayment').submit();
   }
}

function setValuesForTrial(valueToSet)
{
	$('PaymentDone0').disabled = valueToSet;
	var objForm = $('MakePaymentForm');
	for(var formElementsCount=0; formElementsCount<objForm.elements.length; formElementsCount++)
	{
        	var formElement = objForm.elements[formElementsCount];
		var StringLocation = formElement.id.indexOf('_MakePayment');
		var basicId = formElement.id.substring(0,StringLocation);
		if($(basicId+'0'))
			$(basicId+'0').disabled = valueToSet;
	}
}

function showPartOption()
{
   if((trialSelected == 1) && ($('Sale_Type').value != 'Trial'))
   {
	selectComboBox($('Payment_Mode0'),'');
	$('Amount_Received0').value = '';
	$('PaymentDone0').checked = false;
	setValuesForTrial(false);
	trialSelected = 0;		
   } 		
   if($('Sale_Type').value == 'Trial')	
   {
	$('Amount_Received0').value = 0;
	$('PaymentDone0').checked = true;
	setValuesForTrial(true);
	$('Cheque_DD_Comments0').disabled = false;
	trialSelected = 1;	
   }  	
   var saleType = $('Sale_Type').value;
   if (saleType=="Part Payment" || saleType=="Credit")
   {
	 $('partOption').style.display = "";
   }
   else
   {
	 $('partOption').style.display = "none";
	 propCount = 1;
	 for (i=propCount;i<propMaxCount;i++)
	 {
	       $('part'+i).style.display = "none";
	 }
   }
}
function fetchBranchesForExecutive(){
        var execId = document.getElementById('Sales_By').value;
        var xmlHttp = getXMLHTTPObject();
        document.getElementById('Sales_Branch_div').innerHTML = '<div style="width:100%" align="center"><img src="/public/images/ajax-loader.gif" /></div>';
        xmlHttp.onreadystatechange=function() {
                if(xmlHttp.readyState==4) {
                    document.getElementById('Sales_Branch_div').innerHTML = xmlHttp.responseText;
                }
        }
        var url = '/sums/Quotation/fetchBranchesForExecutive/'+execId;
        xmlHttp.open("GET",url,true);
        xmlHttp.send(null);
}

</script>
