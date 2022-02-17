

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
<form method="POST" action="/sums/Manage/submitEditPaymet" id="submitEditPayment" onSubmit="return validateEditPayment();">
<input type="hidden" value="<?php echo $pageInfo['id'];?>" name="Quotation_Id">
<input type="hidden" value="<?php echo $clientDetails['Transaction_Id'];?>" id="Transaction_Id" name="Transaction_Id">
<div style="line-height:10px">&nbsp;</div>
<div class="mar_full_10p">
   <div style="width:223px; float:left">
	
	<?php 
		$leftPanelViewValue = 'leftPanelFor'.$prodId;
		$this->load->view('sums/'.$leftPanelViewValue); 
	?>
   </div>
   <div style="margin-left:233px">
     <div class="OrgangeFont fontSize_14p bld">Edit Payment Details</div>
	 <div class="grayLine"></div>
	 <div class="lineSpace_10">&nbsp;</div>
	 <div>
	    <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Sale Type:</div>
	    <div style="margin-left:200px">
	       <select name="Sale_Type" id="Sale_Type">
		  <option value="">Select Sale Type</option>
		  <option value="Part Payment" <?php if ($Payment[0]['Sale_Type']=="Part Payment") echo "selected"; ?>>Part Payment</option>
		  <option value="Credit" <?php if ($Payment[0]['Sale_Type']=="Credit") echo "selected"; ?> >Credit</option>
		  <option value="Full Payment"  <?php if ($Payment[0]['Sale_Type']=="Full Payment") echo "selected"; ?> >Full Payment</option>
		  <option value="Trial" <?php if ($Payment[0]['Sale_Type']=="Trial") echo "selected"; ?> >Trial</option>
	    	</select>
	    </div>
	 </div>
	 <div class="lineSpace_10">&nbsp;</div>
	 <div>
	    <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Final Sales Amount:</div>
	    <div class="" style="margin-left:200px">
	       <input type="text" name="Final_Sales_Amount" id="Final_Sales_Amount" value="<?php echo $Payment[0]['TotalTransactionPrice'];?>" readonly>
	    </div>
	 </div>
	 <div class="lineSpace_10">&nbsp;</div>
	<div class="OrgangeFont fontSize_14p bld">Edit Part Payments</div>
	<div class="grayLine"></div>
	<div class="lineSpace_10">&nbsp;</div>
	<input type="hidden" name="PaymentId" id="editPayment_Id" value="<?php echo $Payment[0]['Payment_Id'];?>" />
	
	
	
	
	 <?php 
		//echo "<pre>";print_r($Payment);echo "</pre>";
		$partPaymentsArray = array();
		$noOfSubPartPayments = array();
		$subLoopCount = ceil($noOfMaxPartPayment/count($Payment));
		$serialEditPartNumber = -1;
		 for($i=0;$i<count($Payment);$i++) { 	
		$isPaid = (isset($Payment[$i]['isPaid']) && ($Payment[$i]['isPaid'] == 1))?1:0;
		$Tds_Amount_Temp = ($Payment[$i]['TDS_Amount'] != '')?$Payment[$i]['TDS_Amount']:0;
		array_push($partPaymentsArray,$isPaid);
	?>
	<input type="hidden" name="editPartNumber<?php echo $i; ?>" id="editPartNumber<?php echo $i; ?>" value="<?php echo $Payment[$i]['Part_Number'];?>" />
	 <input type="hidden" name="PartPaymentValue<?php echo $i; ?>" id="PartPaymentValue<?php echo $i; ?>" value="<?php echo ($Payment[$i]['Amount_Received']+$Tds_Amount_Temp); ?>" />
	<div>
	<div class="float_L" style="border-right:1px solid #E2E2E2; padding-right:20px; margin-right:20px">
	 <div <?php if ($i>0 && count($Payment)<=$i) echo "style='display:none'"; ?> id="part<?php echo $i;?>" >
	   <div>
	    <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Payment Mode:</div>
	    <div class="" style="margin-left:200px">
	    	<select name="Payment_Mode<?php echo $i;?>" id="Payment_Mode<?php echo $i;?>" disabled="true">
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
		  <input type="text" name="Cheque_No<?php echo $i;?>" id="Cheque_No<?php echo $i;?>" value="<?php echo $Payment[$i]['Cheque_No'];?>" disabled="true" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Receipt Date:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" readonly name="Cheque_Date<?php echo $i;?>" id="Cheque_Date<?php echo $i;?>" value="<?php echo substr($Payment[$i]['Cheque_Date'],0,10);?>" onclick="cal.select($('Cheque_Date<?php echo $i; ?>'),'cd<?php echo $i;?>','yyyy-MM-dd');" disabled="true"><img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="cd<?php echo $i;?>" onClick="cal.select($('Cheque_Date<?php echo $i;?>'),'cd<?php echo $i;?>','yyyy-MM-dd');" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">CHEQUE City:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" name="Cheque_City<?php echo $i;?>" id="Cheque_City<?php echo $i;?>" value="<?php echo $Payment[$i]['Cheque_City'];?>" disabled="true" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">CHEQUE Bank:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" name="Cheque_Bank<?php echo $i;?>" id="Cheque_Bank<?php echo $i;?>" value="<?php echo $Payment[$i]['Cheque_Bank'];?>" disabled="true" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Cheque/DD Date:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" readonly name="Cheque_Receiving_Date<?php echo $i;?>" id="Cheque_Receiving_Date<?php echo $i;?>" value="<?php echo substr($Payment[$i]['Cheque_Receiving_Date'],0,10);?>" onclick="cal.select($('Cheque_Receiving_Date<?php echo $i;?>'),'rd<?php echo $i;?>','yyyy-MM-dd');" disabled="true" ><img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="rd<?php echo $i;?>" onClick="cal.select($('Cheque_Receiving_Date<?php echo $i;?>'),'rd<?php echo $i;?>','yyyy-MM-dd');" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Payment Amount:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" name="Amount_Received<?php echo $i;?>" id="Amount_Received<?php echo $i;?>" value="<?php echo $Payment[$i]['Amount_Received'];?>" disabled="true" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">TDS Amount:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" name="TDS_Amount<?php echo $i;?>" id="TDS_Amount<?php echo $i;?>" value="<?php echo $Payment[$i]['TDS_Amount'];?>" disabled="true" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Deposited By:</div>
	       <div class="" style="margin-left:200px">
		  <select name="Deposited_By<?php echo $i;?>" id="Deposited_By<?php echo $i;?>" size="4" disabled="true">
				<option value="">Select User Name</option> 
                                <?php foreach ($quoteUsers as $user) {
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
		   <select name="Deposited_Branch<?php echo $i;?>" id="Deposited_Branch<?php echo $i;?>" size="4" disabled="true">
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
		  <textarea name="Cheque_DD_Comments<?php echo $i;?>" id="Cheque_DD_Comments<?php echo $i;?>" disabled="true" ><?php echo $Payment[$i]['Cheque_DD_Comments'];?></textarea>
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Payment Status:</div>
	       <div style="margin-left:200px">
		 <select name="Payment_Status<?php echo $i;?>" id="Payment_Status<?php echo $i;?>" disabled="true">
			<option value="">None</option>
			<option value="Un-paid" <?php if ($Payment[$i]['isPaid']=="Un-paid"){ echo "selected=\"true\""; } ?> >Un-paid</option>
			<option value="In-process"  <?php if ($Payment[$i]['isPaid']=="In-process"){ echo "selected=\"true\""; } ?>>In-process</option>
			<option value="Paid" <?php if ($Payment[$i]['isPaid']=="Paid"){ echo "selected=\"true\""; } ?> >Paid</option>
	    	</select>
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>	
	    <div id="editPaymentMsg<?php echo $i;?>" class="errorMsg"></div>	
	 </div>
	</div>
	<div class="flaot_L">
		<strong>Part Number : <?php echo $Payment[$i]['Part_Number'] ?></strong>	
		
		
<!--				<div class="lineSpace_28">&nbsp;</div>
-->

<div class="lineSpace_28">&nbsp;</div>
	
		<div class="lineSpace_28">&nbsp;</div>
				<div class="lineSpace_28">&nbsp;</div>
	
		<div class="clear_L">&nbsp;</div>
	</div>
	<!--   **************************************** 
		Start of sub payments of part payment 
	-->
	<?php 
		$subPartPaymentsArray = array();
		 for($j=0;$j<$subLoopCount;$j++) { 
	?>
	<div <?php if ($j>=0) echo "style='display:none'"; ?> id="subPart<?php echo $i.$j;?>">
	<div class="grayLine"></div>	    
	<div class="lineSpace_10">&nbsp;</div>	
	<div class="float_L" style="border-right:1px solid #E2E2E2; padding-right:20px; margin-right:20px">
	 <div>
	   <div>
	    <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Payment Mode:</div>
	    <div class="" style="margin-left:200px">
	    	<select name="Payment_Mode<?php echo $i.$j;?>" id="Payment_Mode<?php echo $i.$j;?>">
	    		<option value="" >Select Payment Mode</option>
			<option value="Cash">Cash</option>
			<option value="Cheque">Cheque</option>
			<option value="Demand Draft">Demand Draft</option>
			<option value="Telegraphic Transfer">Telegraphic Transfer</option>
			<option value="Credit Card(Offline)">Credit Card(Offline)</option>
			<option value="Later">Later</option>
	    	</select>
	    </div>
	 </div>
	 <div class="lineSpace_10">&nbsp;</div>	
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">CHEQUE/DD/TT No./ORDER No:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" name="Cheque_No<?php echo $i.$j;?>" id="Cheque_No<?php echo $i.$j;?>" value="" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Receipt Date:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" readonly name="Cheque_Date<?php echo $i.$j;?>" id="Cheque_Date<?php echo $i.$j;?>" value="" onclick="cal.select($('Cheque_Date<?php echo $i.$j;?>'),'cd<?php echo $i.$j;?>','yyyy-MM-dd');"><img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="cd<?php echo $i.$j;?>" onClick="cal.select($('Cheque_Date<?php echo $i.$j;?>'),'cd<?php echo $i.$j;?>','yyyy-MM-dd');" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">CHEQUE City:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" name="Cheque_City<?php echo $i.$j;?>" id="Cheque_City<?php echo $i.$j;?>" value="" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">CHEQUE Bank:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" name="Cheque_Bank<?php echo $i.$j;?>" id="Cheque_Bank<?php echo $i.$j;?>" value="" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Cheque/DD Date:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" readonly name="Cheque_Receiving_Date<?php echo $i.$j;?>" id="Cheque_Receiving_Date<?php echo $i.$j;?>" value="" onclick="cal.select($('Cheque_Receiving_Date<?php echo $i.$j;?>'),'rd<?php echo $i.$j;?>','yyyy-MM-dd');"><img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="rd<?php echo $i.$j;?>" onClick="cal.select($('Cheque_Receiving_Date<?php echo $i.$j;?>'),'rd<?php echo $i.$j;?>','yyyy-MM-dd');" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Payment Amount:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" name="Amount_Received<?php echo $i.$j;?>" id="Amount_Received<?php echo $i.$j;?>" value="" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">TDS Amount:</div>
	       <div class="" style="margin-left:200px">
		  <input type="text" name="TDS_Amount<?php echo $i.$j;?>" id="TDS_Amount<?php echo $i.$j;?>" value="" />
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Deposited By:</div>
	       <div class="" style="margin-left:200px">
		   <select name="Deposited_By<?php echo $i.$j;?>" id="Deposited_By<?php echo $i.$j;?>" size="4">
				<option value="">Select User Name</option> 
                                <?php foreach ($quoteUsers as $user) {
                                        ?>
                                        <option value="<?php echo $user['userid'];?>"><?php echo $user['displayname'];?></option> 
                            <?php } ?>
                  </select>		
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Deposited Branch:</div>
	       <div class="" style="margin-left:200px">
		  <select name="Deposited_Branch<?php echo $i.$j;?>" id="Deposited_Branch<?php echo $i.$j;?>" size="4">
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
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Comments:</div>
	       <div class="" style="margin-left:200px">
		  <textarea name="Cheque_DD_Comments<?php echo $i.$j;?>" id="Cheque_DD_Comments<?php echo $i.$j;?>"></textarea>
	       </div>
	    </div>
	    <div class="lineSpace_10">&nbsp;</div>
	    <div>
	       <div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Payment Status:</div>
	       <div style="margin-left:200px">
		 <select name="Payment_Status<?php echo $i.$j;?>" id="Payment_Status<?php echo $i.$j;?>">
			<option value="Un-paid">Un-paid</option>
			<option value="In-process">In-process</option>
			<option value="Paid">Paid</option>
	    	</select>
	       </div>
	    </div>
	    	
	    <div class="lineSpace_10">&nbsp;</div>
		<div id="editPaymentMsg<?php echo $i.$j;?>" class="errorMsg"></div>
	    <div class="lineSpace_10">&nbsp;</div>	
	    </div>
	</div>	
	    <div class="float_L">
			<strong>Enter Details for new part payment.</strong>	
		<div class="lineSpace_28">&nbsp;</div>
	    </div>	
		<div class="clear_L">&nbsp;</div>
	  </div>
	 <?php } ?>
		<!-- ***************************************
			 End of sub payemnts of part payment 
		-->
		<span id="buttonHolder<?php echo $i; ?>" style="display:none;">
		<input type="button" onclick="javascript:addSubPart(<?php echo $i; ?>);" value="Break Payment Id <?php echo $Payment[$i]['Part_Number']; ?> into Part Payment" id="addPartBut<?php echo $i; ?>" <?php if ($Payment[$i]['isPaid']=="Paid"){ echo "style=\"display:none;\""; } ?> />
		<input type="button" onclick="javascript:hideSubPart(<?php echo $i; ?>);" value="Hide This Part Payment" id="hidePartBut<?php echo $i; ?>" disabled="true" <?php if ($Payment[$i]['isPaid']=="Paid"){ echo "style=\"display:none;\""; } ?> /></span>
		<?php 
			if($editPartNumber == $Payment[$i]['Part_Number']){ 
				$serialEditPartNumber = $i;
			}
		?>	
			
		<?php if (count($Payment)>$i){ ?>
		<div class="lineSpace_10">&nbsp;</div>	
		<div class="grayLine"></div>	    
	    	<div class="lineSpace_10">&nbsp;</div>	
	 <?php }  } ?>

	 <div class="lineSpace_10">&nbsp;</div>

	 <!-- Other Transaction Details -->
	 <div id="contact_details_error" class="errorMsg"></div>
	 <div class="lineSpace_10">&nbsp;</div>
	 <div class="errorMsg" id="error"></div>
	 <input type="hidden" name="noOfMainPartPayment" id="noOfMainPartPayment" value="<?php echo count($Payment); ?>" />	
	 <input type="hidden" name="noOfSubParts" id="noOfSubParts" value=""/> 
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
	<input type="hidden" name="Deposited_By_MakePayment" id="Deposited_By_MakePayment" value="" />
	<input type="hidden" name="Deposited_Branch_MakePayment" id="Deposited_Branch_MakePayment" value="" />
	<input type="hidden" name="Payment_Status_MakePayment" id="Payment_Status_MakePayment" value="" />	
	<input type="hidden" name="Cheque_DD_Comments_MakePayment" id="Cheque_DD_Comments_MakePayment" value="" />	
	<input type="hidden" name="Payment_Id_MakePayment" id="Payment_Id_MakePayment" value=""/>
	<input type="hidden" name="partNumber_MakePayment" id="partNumber_MakePayment" value=""/>	
</form>
</body>
</html>
<?php 
	$jsonPartArray = json_encode($partPaymentsArray);	
?>
<script>
<?php if($editvariable == 'true')  {?>
alert("Receipt Date can not be less than today's date.");
<?php } ?>
var propCount = <?php if(isset($Payment)) echo count($Payment); else echo "1";?>;
var propMaxCount = 10;
var partEdit = <?php echo $editPartNumber; ?>; 
var viewTrans = <?php echo (isset($viewTrans) && ($viewTrans == 1))?1:0; ?>;
var subLoopCount = <?php echo $subLoopCount; ?>;
var partPaymentArray = eval(<?php echo $jsonPartArray; ?>);
var pageUrl = '/sums/Quotation/viewTransaction/<?php echo $UIQuotationId.'/'.$prodId; ?>';
var trialSelected = 0;
var subPartPaymentArray = new Array();	
var q = 0;
<?php for($i=0;$i<count($Payment);$i++) { ?>
 subPartPaymentArray[q] = 0;
 q++;
<?php } ?>	
function addSubPart(partNum)
{
	$('subPart'+partNum+subPartPaymentArray[partNum]).style.display = "";
	subPartPaymentArray[partNum]++;
	if(subPartPaymentArray[partNum] == 1){
		$('hidePartBut'+partNum).disabled = false;
	}
	if(subPartPaymentArray[partNum] == subLoopCount){
		$('addPartBut'+partNum).disabled = true;
	}
}
function hideSubPart(partNum)
{
	if(subPartPaymentArray[partNum] > 0)
		subPartPaymentArray[partNum]--;
	
	$('subPart'+partNum+subPartPaymentArray[partNum]).style.display = "none";
	if(subPartPaymentArray[partNum] == 0){
		$('hidePartBut'+partNum).disabled = true;
	}
	if(subPartPaymentArray[partNum] < subLoopCount){
		$('addPartBut'+partNum).disabled = false;
	}
}
function hideAllSubPart(partNum)
{
	if(subPartPaymentArray[partNum] > 0)
		subPartPaymentArray[partNum]--;
	
	for(var j=0;j<=subPartPaymentArray[partNum];j++)
	{
		if($('subPart'+partNum+j))
			$('subPart'+partNum+j).style.display = "none";
	}
	$('buttonHolder'+partNum).style.display = "none";
	$('hidePartBut'+partNum).disabled = true;
	$('addPartBut'+partNum).disabled = false;
}

function setFieldsAttributes(partNum,setVal)
{
	var objForm = document.getElementById('MakePaymentForm');
	for(var formElementsCount=0; formElementsCount<objForm.elements.length; formElementsCount++) {
        	var formElement = objForm.elements[formElementsCount];
		var StringLocation = formElement.id.indexOf('_MakePayment');
		var basicId = formElement.id.substring(0,StringLocation)+partNum;
		if((setVal)&&(document.getElementById(basicId)))
		{
			enableElement(basicId);
		}
		else if(document.getElementById(basicId))
		{
			disableElement(basicId);
		}
		
	}
}
function setEditParams(partNum)
{	
	$('cancelThisPart'+partNum).checked = false;
	if($('editThisPart'+partNum).checked == true)
	{
		$('buttonHolder'+partNum).style.display="";
		setFieldsAttributes(partNum,true);
	}else{
		hideAllSubPart(partNum);
		setFieldsAttributes(partNum,false);
	}
}
function setCancelParams(partNum)
{
	hideAllSubPart(partNum);
	setFieldsAttributes(partNum,false);
	$('editThisPart'+partNum).checked = false;
	if($('cancelThisPart'+partNum).checked == true)
	{
		enableElement('Cheque_DD_Comments'+partNum);
	}
	else
	{
		disableElement('Cheque_DD_Comments'+partNum);
	}	
}
function addPayment(tdsAmount,amountEntered){
	var tdsAmount = parseFloat(tdsAmount);
	var amountEntered = parseFloat(amountEntered);
	if(isNaN(tdsAmount)){	
		tdsAmount = 0;
	}	
	if(isNaN(amountEntered)){
		amountEntered = 0;
	}
	var temp = 0;
	temp = parseFloat(tdsAmount) + parseFloat(amountEntered);
	return temp;			
}

function datediff(strDate1,strDate2)
    {
        if ((strDate1 == '00-00-0000') || (strDate2 == '00-00-0000')) { return true;}
        var _datediff = false;
        var monthnamejs = new Array("01","02","03","04","05","06","07","08","09","10","11","12");
        strDate1 = strDate1.split("-");
        starttime = new Date(strDate1[2],monthnamejs[strDate1[1]-1] -1 ,strDate1[0]);
        starttime = new Date(starttime.valueOf());
        strDate2 = strDate2.split("-");
        endtime = new Date(strDate2[2],monthnamejs[strDate2[1]-1] - 1,strDate2[0]);
        endtime = new Date(endtime.valueOf());
        if(endtime > starttime)
        {
            _datediff = true;
        }
        return _datediff;
    }
    
function checkFiledsVlaues(subPartNum)
{
   
	 
	if($('Amount_Received'+subPartNum).value == "")
	{
		$('editPaymentMsg'+subPartNum).innerHTML = "Please fill the Amount.";
		$('error').innerHTML = "Found some errors in the form please correct it to proceed.";
	 	return false;
	}	
	if($('Cheque_Date'+subPartNum).value == "")
	{
		$('editPaymentMsg'+subPartNum).innerHTML = "Please fill the Receipt Date.";
		$('error').innerHTML = "Found some errors in the form please correct it to proceed.";
	 	return false;
	}
	if($('Payment_Mode'+subPartNum).value == "")
	{
		$('editPaymentMsg'+subPartNum).innerHTML = "Please select the Payment mode.";
		$('error').innerHTML = "Found some errors in the form please correct it to proceed.";
	 	return false;
	}
	var currentTime = new Date();
            var month = currentTime.getMonth() + 1 ;
            var day = currentTime.getDate();
            var year = currentTime.getFullYear();
            if(day<10) day = "0" + day ;
            if(month<10) month= "0" + month ;
            if(year<1000) year+=1900 ;
            var toDaydateStr = year + '-' + month + '-'+day;
	    var mydate = new Date(); 
	    var recipt_date = new Date($('Cheque_Date'+subPartNum).value);
	    var check = datediff($('Cheque_Date'+subPartNum).value,toDaydateStr);
	    
	 <?php 
	 if($validationcheck == 'check'){
	 ?>
	 
	    if(check == true) 
	    { 
		    $('editPaymentMsg'+subPartNum).innerHTML = "Receipt Date can not be less than today's date."; 
		    $('error').innerHTML = "Found some errors in the form please correct it to proceed."; 
		    return false; 
	    } 		
	    if($('Cheque_Date'+subPartNum).value < $('Cheque_Receiving_Date'+subPartNum).value)
	    {
		    $('editPaymentMsg'+subPartNum).innerHTML = "Receipt Date can not be less than Cheque/DD Date";
		    $('error').innerHTML = "Found some errors in the form please correct it to proceed.";
		    return false;
	    }
	    <?php }?>
	    
	return true;
}
function validateFieldsForEditPayment()
{
   var Amount = 0;	
   for(var i=0;i<propCount;i++)
   {
	if($('editThisPart'+i).checked == true)
	{
		if(!checkFiledsVlaues(i))
		{
			return false;
		}
	}
	var Amount1 = 0;
	var temp = addPayment($('TDS_Amount'+i).value,$('Amount_Received'+i).value);
	var PartPaymentValue = parseFloat($('PartPaymentValue'+i).value);
	Amount1 += temp;
	for(var j=0;j<subPartPaymentArray[i];j++)
	{
		var suffix = i.toString()+j.toString();
		if($('editThisPart'+i).checked == true)
		{
			if(!checkFiledsVlaues(suffix))
			{
				return false;
			}
		}
		
		var temp1 = addPayment($('TDS_Amount'+suffix).value,$('Amount_Received'+suffix).value)
		Amount1 += temp1;
	}
	Amount += Amount1;
	if(PartPaymentValue != Amount1)
	{
		if(subPartPaymentArray[i] > 0)
		{
			var j = subPartPaymentArray[i] - 1;
			var suffix = i.toString()+j.toString();
		}else{
			var suffix = i.toString();
		}	
		$('editPaymentMsg'+suffix).innerHTML = "Please fill the Amount Properly";
	 	return false;
	}
   }		
   if((Amount != parseFloat($('Final_Sales_Amount').value)) && (partEdit == -1))
   {
	 $('error').innerHTML = "Please fill the Amount Properly";
	 return false;
   }
   if($('Cheque_DD_Comments0').value.length < 10)
   {		
	$('editPaymentMsg0').innerHTML = 'Please enter comments with minimum 10 characters for this payment.';
	$('error').innerHTML = 'Error found on form.Please correct it to proceed.';
 	return false;
   } 	
   return true;
}
function validateEditPayment()
{
	clearErrorFields();
	$('editSubmit').disabled = true;
	$('noOfSubParts').value = '';
	for(i=0;i<subPartPaymentArray.length;i++){
		$('noOfSubParts').value += subPartPaymentArray[i]+'#';
	}
	if(!validateFieldsForEditPayment())
	{
		$('editSubmit').disabled = false;
		return false;
	}
	for(i=0;i< $('noOfMainPartPayment').value;i++)
	{
		$('TDS_Amount'+i).disabled = false;
		$('Amount_Received'+i).disabled = false;	
	}
	return true;
}
function clearErrorFields()
{	
	var errorPlaces = getElementsByClassName(document.body,'div','errorMsg');
	for(var i=0;i<errorPlaces.length;i++)
	{
		errorPlaces[i].innerHTML = '';
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
		if(partPaymentArray[i] == 1)
		{
			for(var formElementsCount=0; formElementsCount<objForm.elements.length; formElementsCount++) {
		        	var formElement = objForm.elements[formElementsCount];
				var StringLocation = formElement.id.indexOf('_MakePayment');
				var basicId = formElement.id.substring(0,StringLocation);
				disableElement(basicId+i)
			}
			disableElement('PaymentDone'+i);
		}
		
		/* else
		{
			disableElement('Cheque_Date'+i);
			disableElement('Amount_Received'+i);
			disableElement('TDS_Amount'+i);
		} */
	}	
	disableElement('Address');
	disableElement('Country');
	disableElement('City');
	disableElement('Pincode');
	disableElement('Phone_No');
	disableElement('Email_Address');
}
<?php  if($serialEditPartNumber != -1){  
		echo "window.scrollTo(0,document.getElementById('part".$serialEditPartNumber."').offsetTop);";	
		if($Payment[$serialEditPartNumber]['isPaid']!="Cancelled"){
			echo "document.getElementById('editThisPart".$serialEditPartNumber."').checked=true;";
			echo "setEditParams(".$serialEditPartNumber.");";
		}else{
			echo "setCancelParams(".$serialEditPartNumber.");";
		}
	} 
?>
</script>
