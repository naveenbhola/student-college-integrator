<p class="plog">Payment Log :</p>
<span class="success spn">Success - <?php echo (count($insertData)>0 && count($log) <=0) ? str_pad(count($insertData), 2, "0", STR_PAD_LEFT): 0?></span><span class="failed spn">Failed - <?php echo (count($log)>0) ? str_pad(count($log), 2, "0", STR_PAD_LEFT): 0?> <?php if(count($log)>0){?> <span style="font-size:12px;font-weight:normal !important;">( File has incorrect data ! please try again. )</span><?php }?></span>
<?php if(count($log) > 0){?>
<table cellspacing="0" 
cellpadding="1" border="0" height="25" width="100%" style="border: 1px 
solid #E6E7E9" id="mainIncentiveListTbl">
                                                 <tr height="30" 
class="dcms_outerBrd" style="border: 1px solid #E6E7E9;font-weight:600;">
                                                   <td width="10%" 
align="center" style="border-right: 1px solid #E6E7E9;">User ID</td>
                                                   <td width="15%" 
align="center" style="border-right: 1px solid #E6E7E9;">Total Earnings (Remaining)</td>
                                                   <td width="16%" 
align="center" style="border-right: 1px solid #E6E7E9;">Paid Amount</td>
						    <td width="9%" 
align="center" style="border-right: 1px solid #E6E7E9;">Cheque No</td>
                                                   <td width="9%" 
align="center" style="border-right: 1px solid #E6E7E9;">Status</td>
                                                 </tr>
                                    
                                               
<?php $even = 1;if(count($log) > 0){foreach($log as $value){?>
                                                 <tr height="45" 
style="border-style: dashed; border-width: medium;border-color: lime;<?php if(($even % 2) == 0){?> background-color:#eef7fe <?php };?>">
                                                   <td align="center" 
style="border-right: 1px solid #E6E7E9; vertical-align: top; padding: 
10px;<?php if(!is_numeric($value['userId']) || !preg_match('/^([0-9]*)$/', $value['userId'])){?> color:red; <?php }?>"><?php echo ($value['userId'] !='') ? $value['userId'] : '- -';?></td>
                                                   <td align="center" 
style="border-right: 1px solid #E6E7E9; vertical-align: top; padding: 
10px;"><?php echo $value['creadited'];?></td>
                                                   <td align="center" 
style="border-right: 1px solid #E6E7E9; vertical-align: top; padding: 
10px;color:red;"><?php echo (trim($value['debiting']) !== '') ? $value['debiting'] : '- -';?></td>
                                                   <td align="center" 
style="border-right: 1px solid #E6E7E9; vertical-align: top; padding: 
10px; <?php if($value['checkNo'] ==''){?> color:red; <?php }?>" id="payDate-<?php echo $value['userId'];?>"><?php echo  ($value['checkNo'] !='') ? $value['checkNo'] : '- -';?></td>
						   <td align="center" 
style="border-right: 1px solid #E6E7E9; vertical-align: top; padding: 
10px;color:red">Failed</td>	   
						   
						 </tr>
                                               <?php $even++;}}else{ ?>
					       
					       <tr>
				<td colspan="8" align='center' style="padding:10px;">
				No Payment Log available.
				</td>
					       </tr>
				<?php }?>	       
			       </table>
<p style=" position: relative;top: 12px"><b>Note:</b> Paid amount should not be greater than total earnings.</p>
<?php }?>