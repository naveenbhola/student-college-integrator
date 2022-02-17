<?php
$reportsPerPage = isset($countOffset)?$countOffset:5;
  $number5 = '';$number10='';$number20='';$number30='';
  switch($reportsPerPage){
  case 5: $number5 = "selected";
	      break;
  case 10: $number10 = "selected";
	      break;
  case 20: $number20 = "selected";
	      break;
  case 30: $number30 = "selected";
	      break;
  }
$totalSubmission = count($result['result']['result']);
?>
<script>
var parameterObj = eval(<?php echo $parameterObj; ?>);
</script>
<!--Pagination Related hidden fields Starts-->
     <input type="hidden" autocomplete="off" id="incentiveDataStartFrom" 
value="<?php echo $startFrom;?>"/>
     <input type="hidden" autocomplete="off" id="incentiveDataCountOffset" 
value="<?php echo $countOffset;?>"/>
     <input type="hidden" autocomplete="off" id="methodName" 
value="showIncentiveData"/>
<!--Pagination Related hidden fields Ends  -->

     <div style="float:left; width:100%; padding-bottom:86px;">
         <div class="raised_lgraynoBG">
                 <div class="lineSpace_10">&nbsp;</div>
                 <!--className boxcontent_lgraynoBG-->
                 <div class="" id="mainContainer">
		    <table width="99%" cellspacing="0" cellpadding="0" border="0" height="25" style="display: none;">
							<tr>
								<td align="right" style="padding-top: 15px">
									<table cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td><div class="pagingID" id="paginataionPlace1" style="line-height:23px;"></div></td>
										<td style="line-height:23px;display:none;"></span>
											<select name="countOffset" id="countOffset_DD1" class="normaltxt_11p_blk_arial" onChange="updateCountOffset(this,'incentiveDataStartFrom','incentiveDataCountOffset');">
												<option value="5" <?php echo $number5;?>>5</option>
												<option value="10" <?php echo $number10;?>>10</option>
												<option value="20" <?php echo $number20;?>>20</option>
												<option value="30" <?php echo $number30;?>>30</option>
											</select>					
										</td>
									</tr>
									</table>
								</td>
							</tr>
		    </table>
                         <div class="new-task-form">
                             <h2 style="border:0 none;" class="">Incentives received by Campus Representatives</h2>
                         </div>
			        
                                       <div class="" style="float: left; 
width: 100%; margin-top: 20px;">

                                           <div>

                                               <div>
						<form>	    
						     <table cellspacing="0" 
cellpadding="1" border="0" height="25" width="100%" style="border: 1px 
solid #E6E7E9" id="mainIncentiveListTbl">
                                                 <tr height="30" 
class="dcms_outerBrd" style="border: 1px solid #E6E7E9">
                                                   <td width="10%" 
align="center" style="border-right: 1px solid #E6E7E9;">User ID</td>
                                                   <td width="15%" 
align="center" style="border-right: 1px solid #E6E7E9;">User Name</td>
                                                   <td width="16%" 
align="center" style="border-right: 1px solid #E6E7E9;">Institute Name</td>
						    <td width="9%" 
align="center" style="border-right: 1px solid #E6E7E9;">Last Pay Date</td>
                                                   <td width="9%" 
align="center" style="border-right: 1px solid #E6E7E9;">Total Payout</td>
						   <td width="10%" 
align="center" style="border-right: 1px solid #E6E7E9;">Total Earnings</td>
                                                   <td width="13%" 
align="center" style="border-right: 1px solid #E6E7E9;">Cheque No</td>
						   <td width="18%" 
align="center" style="border-right: 1px solid #E6E7E9;">Action</td>
                                                 </tr>
                                    
                                               
<?php $even = 1;if($totalSubmission > 0){foreach($result['result']['result'] as $value){?>
                                                 <tr height="45" 
style="border-style: dashed; border-width: medium;border-color: lime;<?php if(($even % 2) == 0){?> background-color:#eef7fe <?php };?>">
                                                   <td align="center" 
style="border-right: 1px solid #E6E7E9; vertical-align: top; padding: 
10px;"><?php echo $value['userId'];?></td>
                                                   <td align="center" 
style="border-right: 1px solid #E6E7E9; vertical-align: top; padding: 
10px;"><?php echo ucwords($value['displayName']);?></td>
                                                   <td align="center" 
style="border-right: 1px solid #E6E7E9; vertical-align: top; padding: 
10px;"><?php echo $result[$value['userId']]['instituteName'];?></td>
                                                   <td align="center" 
style="border-right: 1px solid #E6E7E9; vertical-align: top; padding: 
10px;" id="payDate-<?php echo $value['userId'];?>"><?php echo (strtotime($value['lastPaydate'])>0) ? date('d/m/Y',strtotime($value['lastPaydate'])):'';?></td>
						   <td align="center" 
style="border-right: 1px solid #E6E7E9; vertical-align: top; padding: 
10px;">
<input type="hidden" id="totalPaid-<?php echo $value['userId'];?>" value="<?php echo ($value['paidRewards']>0) ? $value['paidRewards'] : 0;?>" style="width: 90px;">
<span  id="totalPaidText-<?php echo $value['userId'];?>"><?php echo ($value['paidRewards']>0) ? $value['paidRewards'] : 0;?></span></td>	   
						   <td align="center" 
style="border-right: 1px solid #E6E7E9; vertical-align: top; padding: 
10px;">
<input type="hidden" id="totaEarning-<?php echo $value['userId'];?>" value="<?php echo ($value['earnedRewards']) ? ($value['earnedRewards'] - $value['paidRewards']) :0;?>" style="width: 90px;">
<span id="totaEarningText-<?php echo $value['userId'];?>"><?php echo ($value['earnedRewards']) ? ($value['earnedRewards'] - $value['paidRewards']) :0;?></span>
                                                     
                                                   </td>
						   <td align="center" 
style="border-right: 1px solid #E6E7E9; vertical-align: top; padding: 
10px;"><input type="text" id="chequeNumber-<?php echo $value['userId'];?>" maxlength="10" size="5" style="width: 90px;">
<span id="errorCheque-<?php echo $value['userId'];?>"></span>
<br>
<input type="text" id="amount-<?php echo $value['userId'];?>" maxlength="10" size="5" style="width: 90px;">
<span id="errorAmount-<?php echo $value['userId'];?>"></span>                                                     
                                                   </td>
						   <td align="center" 
style="border-right: 1px solid #E6E7E9; vertical-align: top; padding: 
10px;"><a class="orange-button" onclick="makeCrPay(<?php echo $value['userId'].','.$value['earnedRewards'];?>)" id="btn-<?php echo $value['userId'];?>">Make Payment</a>
						   </td>
						 </tr>
                                               <?php $even++;}}else{ ?>
					       
					       <tr>
				<td colspan="8" align='center' style="padding:10px;">
				No data available.
				</td>
					       </tr>
				<?php }?>	       
			       </table>
						     </form>
			       </div>
                                               <div 
class="lineSpace_2">&nbsp;</div>
                                               <div 
class="lineSpace_10">&nbsp;</div>
                                               <div 
class="lineSpace_10">&nbsp;</div>
					       <div 
class="lineSpace_10">&nbsp;</div>
                                           </div>

                                       </div>
                               </div>
                         </div>
                        
                         <!-- code for pagination start -->
                         <div class="mar_full_10p">
                             <div class="row" style="line-height:24px">
				<div style="float:right;">
                                 <div style="display:none; float: left; margin-right: 5px">
                                         Show Rows: <select name="countOffset" id="countOffset_DD2" onChange="updateCountOffset(this,'incentiveDataStartFrom','incentiveDataCountOffset');">
                                             <option value="5" <?php 
echo $number5;?>>5</option>
                                             <option value="10" <?php 
echo $number10;?>>10</option>
                                             <option value="20" <?php 
echo $number20;?>>20</option>
                                             <option value="30" <?php 
echo $number30;?>>30</option>
                                         </select>
                                 </div>
					 
                                 <div class="pagingID" id="paginataionPlace2" style="float: left"></div>
				 </div>
                             </div>
                         </div>
                         <div class="lineSpace_10">&nbsp;</div>
                         <!-- code for pagination ends -->
                         </div>
             </div>
             <div class="lineSpace_11">&nbsp;</div>


<style>
.disabled {
   pointer-events: none;
   cursor: default;
}
</style>

<script>
function toggleCADetails(id){
   if(document.getElementById(id).style.display == 'none'){
     document.getElementById(id).style.display = 'block';
     document.getElementById(id+'Toggler').src = 
'/public/images/closedocument.gif';
   }
   else{
     document.getElementById(id).style.display = 'none';
     document.getElementById(id+'Toggler').src = 
'/public/images/plusSign.gif';
   }
}

function makeCrPay(userId)
{
    var str = $j('#chequeNumber-'+userId).val().trim();
    var totalEarnings = parseInt($j('#totaEarning-'+userId).val());
    var amnt = $j('#amount-'+userId).val().trim();
	if (str =='') {
	    $j('#errorCheque-'+userId).text('Enter Cheque No').css('color','red');
	    $j('#chequeNumber-'+userId).val('').focus();
	    $j('#amount-'+userId).val('');
	    return false;
	}else if(/^[a-zA-Z0-9]*$/.test(str) == false || str.length < 5){
	    $j('#errorCheque-'+userId).text('Enter Valid No').css('color','red');
	    $j('#chequeNumber-'+userId).focus();
	    $j('#amount-'+userId).val('');
	    return false;
	}else{
	    $j('#errorCheque-'+userId).text('');
	}
    
	if(amnt =='') {
	    $j('#errorAmount-'+userId).text('Enter Amount').css('color','red');
	    $j('#amount-'+userId).val('').focus();
	    return false;
	}else if(/^[0-9]*$/.test(amnt) == false || amnt.length < 1 || parseInt(amnt) <= 0 || parseInt(amnt) > 100000 || parseInt(amnt) > totalEarnings){
	    $j('#errorAmount-'+userId).text('Enter Valid Amount').css('color','red');
	    $j('#amount-'+userId).focus();
	    return false;
	}else{
	    $j('#errorAmount-'+userId).text('');
	}
	
	$j.ajax({
		url: "/CA/CRDashboard/paidCR",
		type: "POST",
		data: {'userId': userId,'paidAmount': amnt,'chequeNumber': $j('#chequeNumber-'+userId).val().trim()},
		beforeSend : function(){$j('#btn-'+userId).addClass('disabled');$j('#btn-'+userId).text('Wait..');},
		success: function(data){
		    if(data>0){
		        
			$j('#btn-'+userId).text('Make Payment').removeClass('disabled');
			$j('#chequeNumber-'+userId+' ,#amount-'+userId).val('');
			var sumA = (parseInt($j('#totalPaid-'+userId).val()) + parseInt(amnt));
			var sumEarning = (parseInt($j('#totaEarning-'+userId).val()) - parseInt(amnt));
			$j('#totalPaidText-'+userId).text(sumA);
			$j('#totaEarningText-'+userId).text(sumEarning);
			$j('#totalPaid-'+userId).val(sumA);
			$j('#totaEarning-'+userId).val(sumEarning);
			$j('#payDate-'+userId).text('<?php echo date('d/m/Y');?>');
			alert('Your cheque has been submitted successfully.');
			//location.reload();
		    }
		}
	    });
}

</script>
<?php
echo "<script>
    setStartOffset(0,'incentiveDataStartFrom','incentiveDataCountOffset');
doPagination(".$totalCount.",'incentiveDataStartFrom','incentiveDataCountOffset','paginataionPlace1','paginataionPlace2','methodName',3);
    </script>";
?>
