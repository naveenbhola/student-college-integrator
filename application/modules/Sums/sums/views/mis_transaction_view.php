<?php	
    $headerComponents = array(
        'css'      =>        array('headerCms','raised_all','mainStyle','footer'),
        'js'         =>            array('user','common','CalendarPopup'),
        'title'      =>        'SUMS - MIS - Transaction View',
        'tabName'          =>        'Register',
        'taburl' =>  SITE_URL_HTTPS.'enterprise/Enterprise/register',
        'metaKeywords'  =>'Some Meta Keywords',
        'product' => '',
        'search'=>false,
        'displayname'=> (isset($sumsUserInfo['validity'][0]['displayname'])?$sumsUserInfo['validity'][0]['displayname']:""),
        'callShiksha'=>1
    ); 
    $this->load->view('enterprise/headerCMS', $headerComponents);
    $this->load->view('enterprise/cmsTabs',$sumsUserInfo);
?>
<!-- <link href="/public/css/print1.css" rel="stylesheet" type="text/css" media="print1"> -->
		
		<?php print_r($data); ?>
		
	    <div style="margin:0 10% 0 10%;" id="mainDivElement">
			<div class="lineSpace_10">&nbsp;</div>
			<div class="OrgangeFont fontSize_14p bld">Transaction Details</div>
	 		<div class="grayLine"></div>
	 		<div class="lineSpace_10">&nbsp;</div>
			<div align="center">
				<span class="bld">Transaction ID : <?php echo $transactionId; ?></span>
			</div>
			<div class="lineSpace_10">&nbsp;</div>
			<div>
				<div class="float_R">
					<span class="bld">Sale Type : <?php echo isset($paymentDetails[0]['Sale_Type'])?$paymentDetails[0]['Sale_Type']:''; ?></span>
				</div>
				<div class="float_L" style="margin-right:300px;">
					<span class="bld">Client Name :<?php echo $clientDetails['displayName']; ?></span>
				</div>
				<div class="clear_B"></div>
			</div>
			<div class="lineSpace_10">&nbsp;</div>
			<div>
				
	    
				
			</div>
			<div class="lineSpace_10">&nbsp;</div>

			<div>
				
				<div class="float_L" style="margin-right:300px;">
					<span class="bld">Currency Name : </span><span class="fontSize_12p bld ">
					
					<?php
					if ($transactionDetails[0]['CurrencyId'] == '1') {					    
					    echo "INR";
					}
					elseif($transactionDetails[0]['CurrencyId'] == '2'){					    
					    echo "USD";
					}
					
					    ?>
					
					</span>
				</div>
				<div class="clear_B"></div>
			</div>
	
		<div class="lineSpace_10">&nbsp;</div><div class="lineSpace_10">&nbsp;</div><div class="lineSpace_10">&nbsp;</div><div class="lineSpace_10">&nbsp;</div><div class="lineSpace_10">&nbsp;</div>
		<div class="lineSpace_10">&nbsp;</div>
		<div class="lineSpace_10">&nbsp;</div>
	
		<table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td colspan="6" bgcolor="#FFFFFF">&nbsp;</td>
                </tr>
                <tr class="tableHeaderSums">
                    <td width="5%"  align="center"><strong>Sr. No.</strong> </td>
                    <td width="50%" align="center"><strong>Products</strong></td>
                    <td width="8%"  align="center"><strong>Total Price</strong></td>
                  
                </tr>                      
                    <tr>
			<td width="4%" valign="top" style="padding:0" align="center"><img src="/public/images/space.gif" width="15" height="1" /></td>	
                        <td width="50%" valign="top" style="padding:0" align="center"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="8%" valign="top" style="padding:0" align="center"><img src="/public/images/space.gif" width="15" height="1" /></td>
                       
                    </tr>

		
		 <?php 
			$i=1; $sumDiscount=0;$sumSP=0;
			foreach($transactionDetails as $val): 
			//echo "<pre>";print_r($val);echo "</pre>";
			$pricePerProduct = $val['Quantity']*$val['ManagementPrice'];
			$spPerProduct = $pricePerProduct - $val['Discount'];
			$sumDiscount += $val['Discount'];
			$sumSP += $spPerProduct;
			$i++; endforeach; 
		?>
		
		
		<tr class="<?php if(($i%2) == 0) echo 'tRow2Sums'; else echo 'tRow1Sums'; ?>">
	<?php	//_p($val); ?>
                    	<td width="5%" valign="top" align="center"><?php echo "1"; ?></td>
                        <td width="50%" valign="top" align="center"><?php echo $val['DerivedProductName']; ?></td>
                        <td width="8%" valign="top" align="center"><?php echo $val['TotalTransactionPrice']; ?></td>
                      
                    </tr>
		
		
		<?php 
			
			$FinalSalesAmount = $val['FinalSalesAmount'];
		?>

		   	
		</table>
		
		<div class="lineSpace_10">&nbsp;</div>
		<div class="lineSpace_10">&nbsp;</div>
		<div class="lineSpace_10">&nbsp;</div>
		<div class="lineSpace_10">&nbsp;</div>
		
		
		
		<!-- start of payments. -->
		<div class="lineSpace_18">&nbsp;</div>
		<div class="grayLine"></div>
		<div class="lineSpace_10">&nbsp;</div>
		<table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
		<tr>
                    <td colspan="6"><strong>Payment ID : <?php echo $paymentDetails[0]['Payment_Id'] ?></strong></td>
                </tr>
                <tr>
                    <td colspan="6" bgcolor="#FFFFFF">&nbsp;</td>
                </tr>
                <tr class="tableHeaderSums">
                    <td width="4%"  align="center"><strong>Part Number</strong> </td>
                    <td width="8%" align="center"><strong>Payment Amount</strong></td>
                    <td width="8%" align="center"><strong>TDS Amount</strong></td>	
                    <td width="10%" align="center"><strong>Payment Mode</strong></td>
                    <td width="15%" align="center"><strong>Receipt Date</strong></td>
                    <td width="15%" align="center"><strong>Cheque/DD Date</strong></td>
		    <td width="14%" align="center"><strong>Check Bank-Number</strong></td>	
                    <td width="14%" align="center"><strong>Status</strong></td>
		    <td width="12%" align="center"><strong>Deposited By</strong></td>
			<td width="12%" align="center"><strong>Edited By</strong></td>
                </tr>            
                <tr>
			<td width="4%" valign="top" style="padding:0" align="center"><img src="/public/images/space.gif" width="15" height="1" /></td>	
                        <td width="8%" valign="top" style="padding:0" align="center"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="8%" valign="top" style="padding:0" align="center"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="10%" valign="top" style="padding:0" align="center"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="15%" valign="top" style="padding:0" align="center"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="15%" valign="top" style="padding:0" align="center"><img src="/public/images/space.gif" width="15" height="1" /></td>
			<td width="14%" valign="top" style="padding:0" align="center"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="14%" valign="top" style="padding:0" align="center"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="12%" valign="top" style="padding:0" align="center"><img src="/public/images/space.gif" width="15" height="1" /></td>
      <td width="12%" valign="top" style="padding:0" align="center"><img src="/public/images/space.gif" width="15" height="1" /></td>
			<td width="12%" valign="top" style="padding:0" align="center"><img src="/public/images/space.gif" width="15" height="1" /></td>
			<td width="12%" valign="top" style="padding:0" align="center"><img src="/public/images/space.gif" width="15" height="1" /></td>
                 </tr>
		 <?php 
			$i=1; $totalPaid=0;
			//echo "<pre>";print_r($paymentDetails);echo "</pre>";
			foreach($paymentDetails as $val): 
				$Cheque_Date = (($val['Cheque_Date'] != '0000-00-00 00:00:00')&&($val['Cheque_Date'] != ''))?date('d - m - Y',strtotime($val['Cheque_Date'])):'Not Entered';
				$Cheque_Receiving_Date = (($val['Cheque_Receiving_Date'] != '0000-00-00 00:00:00')&&($val['Cheque_Receiving_Date'] != ''))?date('d - m - Y',strtotime($val['Cheque_Receiving_Date'])):'Not Entered';
				$checkDetails = 'Not Available';
				if(($val['Cheque_Bank']) && ($val['Cheque_No']))
					$checkDetails = $val['Cheque_Bank'].'-'.$val['Cheque_No'];
				if($val['isPaid'] == 'Paid')
					$totalPaid += ($val['Amount_Received']+$val['TDS_Amount']);
		?>
		
		<tr class="<?php if(($i%2) == 0) echo 'tRow2Sums'; else echo 'tRow1Sums'; ?>">
                    	<td width="4%"  align="center"><?php echo $val['Part_Number']; ?></td>
                        <td width="8%" align="center"><?php echo $val['Amount_Received']; ?></td>
                        <td width="8%" align="center"><?php echo $val['TDS_Amount']; ?></td>
                        <td width="10%" align="center"><?php echo $val['Payment_Mode']; ?></td>
                        <td width="15%" align="center"><?php echo $Cheque_Date ; ?></td>
                        <td width="15%" align="center"><?php echo $Cheque_Receiving_Date; ?></td>
			<td width="14%" align="center"><?php echo $checkDetails; ?></td>
                        <td width="14%" align="center"><?php echo $val['isPaid']; ?></td>
			<td width="12%" align="center"><?php echo ($val['displayName'] != '')?$val['displayName']:'N.A.'; ?></td>
			<td width="12%" align="center"><?php echo $val['EditedBy']; ?></td>
                    </tr>
		<?php $i++; endforeach; ?>
		    <tr>	
                        <td colspan="9"><div class="grayLine"></div></td>
		    </tr> 						
		    <tr>
                        <td valign="top" align="center" colspan="8"><strong>Total Collected : <?php echo $totalPaid; ?></strong></td>
                    </tr>
		    <tr>
                        <td valign="top" align="center" colspan="8"><strong>OutStanding Amount : <?php echo ($FinalSalesAmount - $totalPaid); ?></strong></td>
                    </tr>	
		</table>
		
				<div class="lineSpace_10">&nbsp;</div>
		<div class="lineSpace_10">&nbsp;</div>
		<div class="lineSpace_10">&nbsp;</div>
		<div class="lineSpace_10">&nbsp;</div>
		

		<div class="lineSpace_28">&nbsp;</div>
<!-- <a href="#" onClick="javascript:printIt(); return false">Print this page</a> -->
<script>
//this is for providing the print of document.
var leftMain = 0;16%
function classNameAttach()
{
	
}
function classNameDettach()
{
	document.getElementById('headerDiv').style.left = leftMain;
	document.getElementById('headerDiv').className="";
}
function printIt()
{
	window.print();	
	document.getElementById('mainDivElement').print();
	return false;
}
//window.onbeforeprint=classNameAttach;
//window.onafterprint=classNameDettach;
</script>

