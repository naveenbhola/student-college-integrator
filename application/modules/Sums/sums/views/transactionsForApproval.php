<?php 
function generateTransactionId($transactionId)
{
	$tranlen=strlen($transactionId);
	if($tranlen<11)
	{
		for($i=0;$i<(11-$tranlen);$i++)
		{
			$transactionId="0".$transactionId;
		}
	}
	return $transactionId;
}


if ($transactions==NULL) { ?>
	<div class="mar_top_6p">No results found.</div>
        <?php } else { ?>

        <div class="OrgangeFont fontSize_14p bld"><strong>Select a Transaction for Approval</strong></div>
        <div class="grayLine"></div>
            <div class="lineSpace_10">&nbsp;</div>
            <!--<table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td colspan="7" bgcolor="#FFFFFF">&nbsp;</td>
                </tr>
                <tr>
                    <td width="4%" height="25" valign="top" bgcolor="#EEEEEE" style="padding:5px 5px 0px 5px"><img src="/public/images/space.gif" width="23" height="30" /></td>
                    <td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>TRANSACTION ID</strong> </td>
                    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>TRANSACTION DATE</strong></td>
                    <td width="11%" valign="top"  bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>CLIENT ID</strong></td>
                    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>COLLEGE NAME</strong></td>
                    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>PRODUCTS SOLD</strong></td>
                    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>SALE BY :USERID</strong></td>
                    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>SALE AMOUNT</strong></td>
                    <?php if($queueType=="FINANCE"){ ?>
                    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>SALE TYPE</strong></td>
                    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>PAYMENT MODES</strong></td>
                    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>PAYMENT DATES</strong></td>
                    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>PAYMENT AMMOUNT</strong></td>
                    <?php }?>
		    <?php if($searchTypeForTransaction == 'Validate'){ ?>	
                    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>DISCOUNT ALLOWED</strong></td>
		   <?php } ?>	
                    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>DISCOUNT GIVEN</strong></td>
		    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Comments</strong></td>	
                    <?php if($queueType=="OPS"){ ?> 
                    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>SUBSCRIPTION START DATE</strong></td>
                    <?php } ?>
		    <?php if($searchTypeForTransaction == 'View'){ ?>
		   <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Status</strong></td>			
		    <?php } ?>	
                </tr>
            </table> -->
        
            <div>
                <table width="98%" border="0" cellspacing="3" cellpadding="0">
                                <tr>
                    <td colspan="7" bgcolor="#FFFFFF">&nbsp;</td>
                </tr>
                <tr>
                    <td width="4%" height="25" valign="top" bgcolor="#EEEEEE" style="padding:5px 5px 0px 5px"><img src="/public/images/space.gif" width="23" height="30" /></td>
                    <td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>TRANSACTION ID</strong> </td>
                    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>TRANSACTION DATE</strong></td>
                    <td width="11%" valign="top"  bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>CLIENT ID</strong></td>
                    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>COLLEGE NAME</strong></td>
                    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>PRODUCTS SOLD</strong></td>
                    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>SALE BY :USERID</strong></td>
                    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>SALE AMOUNT</strong></td>
                    <?php if($queueType=="FINANCE"){ ?>
                    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>SALE TYPE</strong></td>
                    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>PAYMENT MODES</strong></td>
                    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>PAYMENT DATES</strong></td>
                    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>PAYMENT AMMOUNT</strong></td>
                    <?php }?>
		    <?php if($searchTypeForTransaction == 'Validate'){ ?>	
                    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>DISCOUNT ALLOWED</strong></td>
		   <?php } ?>	
                    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>DISCOUNT GIVEN</strong></td>
		    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Comments</strong></td>		
                    <?php if($queueType=="OPS"){ ?> 
                    <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>SUBSCRIPTION START DATE</strong></td>
                    <?php } ?>
		    <?php if($searchTypeForTransaction == 'View'){ ?>
		   <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Status</strong></td>			
                   <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Approving Manager</strong></td>			
                   <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Cancellation Time</strong></td>		
                   <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Cancelled By</strong></td>			
		    <?php } ?>	
                </tr>                        
                    <tr>
                        <td width="4%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="8%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <?php if($queueType=="FINANCE"){ ?>
                        <!--td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td-->
                        <?php }?>
			<?php if($searchTypeForTransaction == 'Validate'){ ?>
                        <td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
			<?php } ?>
                        <td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
			<td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <?php if($queueType=="OPS"){ ?> 
                        <td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <?php } ?>
			<?php if($searchTypeForTransaction == 'View'){ ?>
			<td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
			<td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
			<td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
			<?php } ?>		
                    </tr>
        <?php $i=1; ?> 
        
        <?php foreach ($transactions as $key=>$val) { ?>
                    <tr>
                        <?php if($queueType=="OPS"){ ?> 
                        <td height="25" valign="top"  style="padding:0"><input id="userNo_<?php echo $i; ?>" type="radio" value="<?php echo $i.'##'.$val['TransactionId']; ?>" name="TransactionId" <?php if($val['State'] == 'CANCELLED') echo "disabled" ?> /></td>
                    <?php }else if($queueType=="MANAGER"){ ?>
                    <td  height="25" valign="top"  style="padding:0"><input id="userNo_<?php echo $i; ?>" type="checkbox" value="<?php echo $val['TransactionId']; ?>" name="TransactionId[]" <?php if($val['State'] == 'CANCELLED') echo "disabled" ?> /></td>
                     <?php }else { ?>
                    <td  height="25" valign="top"  style="padding:0"><input id="userNo_<?php echo $i; ?>" type="radio" value="<?php echo $val['TransactionId']; ?>" name="TransactionId" <?php if($val['State'] == 'CANCELLED') echo "disabled" ?> /></td>
                    <?php } ?>
                    <td width="8%" valign="top" style="padding:5px 10px 0px 10px">
			<?php if(array_key_exists(16,$sumsUserInfo['sumsuseracl'])){?>
			<?php if($val['QuoteType']=="CUSTOMIZED"){ ?>
                        <a href="/sums/MIS/viewTransactionDetails/<?php echo $val['TransactionId']; ?>" target="_blank"><?php echo generateTransactionId($val['TransactionId']); ?></a>
                        <?php }else{ ?>
		        <a href="/sums/MIS/viewTransactionDetails/<?php echo $val['TransactionId']; ?>" target="_blank"><?php echo generateTransactionId($val['TransactionId']); ?></a>
			<?php }
                        }
				else
				{
					echo generateTransactionId($val['TransactionId']);
				}
			 ?>
			</td>
                        <td width="11%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['TransactTime']; ?></td>
                        <td width="11%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['ClientId']; ?></td>
                        <td width="11%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['businessCollege']; ?></td>
                        <td width="11%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['ProductSelected']; ?></td>
                        <td width="11%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['SalesPersonName'].' :'.$val['SalesBy']; ?></td>
                        <td width="11%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['FinalSalesAmount']; ?></td>
                        <?php if($queueType=="FINANCE"){ ?>
                        	<td width="11%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['Sale_Type']; ?></td>
                        	<td width="11%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['Payment_Modes']; ?></td>
                        	<td width="11%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['Payment_Date']; ?></td>
                        	<td width="11%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['Payment_Ammount']; ?></td>
                        <?php }?>
			<?php if($searchTypeForTransaction == 'Validate'){ ?>
                        <td width="11%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['DiscountLimit'].'%'; ?></td>
			<?php } ?>
                        <td width="11%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['Discount'].'%'; ?></td>
			<td width="11%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['CancelCommets']; ?></td>
                        <?php if($queueType=="OPS"){ ?> 
                        <td width="11%" valign="top" style="padding:5px 10px 0px 10px">
                            <input type="text" readonly id="subs_start_date<?php echo $i; ?>" name="subsStartDate<?php echo $i; ?>" size="8" onclick="cal.select($('subs_start_date<?php echo $i; ?>'),'sd_<?php echo $i; ?>','yyyy-MM-dd');"><img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="sd_<?php echo $i; ?>" onClick="cal.select($('subs_start_date<?php echo $i; ?>'),'sd_<?php echo $i; ?>','yyyy-MM-dd');" />
                       </td>
                    <?php } ?>
		    <?php if($searchTypeForTransaction == 'View'){ ?>
                        <td width="11%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['State']; ?></td>
                        <td width="11%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['pendingInManager']; ?></td>
                        <td width="11%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['CancellationTime']; ?></td>
                        <td width="11%" valign="top" style="padding:5px 10px 0px 10px"><?php if($val['CancellerId']!=NULL) { echo $val['CancellerName'].' :'.$val['CancellerId']; } ?></td>
		   <?php } ?>	
                    </tr>
	<?php $i++; 
        } ?>

                </table>
            </div>
        <input type="hidden" id="totalUserCount" value="<?php echo $i-1; ?>" />
        <div class="lineSpace_5">&nbsp;</div>
        <div id="radio_unselect_error" style="display:none;color:red;"></div><br/>
        <div>
        <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td width="100%" height="20" colspan="8" bgcolor="#EEEEEE">&nbsp;</td>
                </tr>
                <tr>
                    <?php if($queueType=="MANAGER"){ ?>
                    <td height="20" colspan="" align="right" width="80%">
                        <button class="btn-submit19" onclick="$('frmSelectTransact').action='/sums/Manage/approveManager';validateFormSums();" type="button" value="" style="width:190px">
                            <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">APPROVE FOR FINANCE</p></div>
                        </button>
                    </td>
                    <?php } ?>
                    <?php if($queueType=="FINANCE"){ ?>
                    <td height="20" colspan="" align="right" width="80%">
                        <button class="btn-submit19" onclick="$('frmSelectTransact').action='/sums/Manage/approveFinance';validateFormSums();" type="button" value="" style="width:190px">
                            <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">APPROVE FOR OPS TEAM</p></div>
                        </button>
                    </td>
                    <?php } ?>
                    <?php if($queueType=="OPS"){ ?>
                    <td height="20" colspan="" align="right" width="80%">
                        <button class="btn-submit19" onclick="$('frmSelectTransact').action='/sums/Manage/approveOps';validateFormSums();" type="button" value="" style="width:190px">
                            <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">CREATE SUBSCRIPTION</p></div>
                        </button>
                    </td>
                    <?php } ?>
		<?php if(array_key_exists(17,$sumsUserInfo['sumsuseracl']) && ($queueType!="subsView") ){?>
                <td height="20" colspan="" align="right">
                        <button class="btn-submit19" onclick="showHideCancelComment('show');" type="button" value="" style="width:190px">
                            <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">CANCEL TRANSACTION</p></div>
                        </button>
                    </td>
		<?php } ?>
                <?php if((array_key_exists(43,$sumsUserInfo['sumsuseracl']) || array_key_exists(44,$sumsUserInfo['sumsuseracl'])) && ($queueType=="subsView") ){?>
                <td height="20" colspan="" align="right">
                    <?php if($flowType=='Subscription'){ ?>
                        <button class="btn-submit19" onclick="$('frmSelectTransact').action='/sums/Subscription/subscriptionsForTrans';validateFormSums();" type="button" value="" style="width:190px">
                            <?php } ?>
                            <?php if($flowType=='Consumption'){ ?>
                            <button class="btn-submit19" onclick="$('frmSelectTransact').action='/sums/Subscription/subscriptionsForConsumptions';validateFormSums();" type="button" value="" style="width:190px">
                            <?php } ?>
                            <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Show Subscriptions</p></div>
                        </button>
                    </td>
		<?php } ?>
                </tr>
            </table>

        </div>
        <div class="clear_L"></div>
        <div id="cancelDiv" style="display:none;">
            <b> Please input reason for Disapproval .. </b><br/>
            <textarea type="text" name="CancelComments" id="CancelComments" minlength="0" validate="validateStr" maxlength="5000" style="height:130px;" caption="Cancel Comments" /></textarea>
            <button class="btn-submit19" onclick="$('frmSelectTransact').action='/sums/Manage/cancelTransaction';validateFormSums();" type="button" value="" style="width:200px">
                <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Submit Comments & Cancel</p></div>
            </button>
            <button class="btn-submit19" onclick="showHideCancelComment('hide');" type="button" value="" style="width:100px">
                <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">RESET</p></div>
            </button>
        </div>
        <?php } ?>
