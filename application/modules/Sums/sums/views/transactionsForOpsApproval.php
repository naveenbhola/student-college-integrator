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

    <div>
        <table width="98%" border="0" cellspacing="1" cellpadding="0">                        
            <tr>
                <td width="4%" height="25" valign="top" bgcolor="#EEEEEE" style="padding:5px 5px 0px 5px"><img src="/public/images/space.gif" width="23" height="30" /></td>
                <td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Trans. Id</strong> </td>
                <td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Product Name</strong> </td>	
                <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Trans. Date</strong></td>
                <td width="11%" valign="top"  bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Client Id</strong></td>
                <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Institute Name</strong></td>
                <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Sale By :Userid</strong></td>
                <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Sale Amount</strong></td>
                <?php if($searchTypeForTransaction == 'Validate'){ ?>	
                <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Disc Limit</strong></td>
                <?php } ?>	
                <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Disc Given</strong></td>
                <?php if($queueType=="OPS"){ ?> 
                <td width="11%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Subscription Start Date</strong></td>
                <?php } ?>
            </tr>
            <tr>
                <td width="4%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                <td width="12%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                <td width="12%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                <td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                <td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                <td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                <td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                <td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                <?php if($searchTypeForTransaction == 'Validate'){ ?>
                <td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                <?php } ?>
                <td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                <?php if($queueType=="OPS"){ ?> 
                <td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                <?php } ?>
            </tr>
            <?php $i=1; 
                $colorFlag = false;
            ?> 

            <?php foreach ($transactions as $key=>$val) {  
                    foreach($val['derivedProducts'] as $newVal) { 
                    ?>
                    <?php if($colorFlag == false){?>
                    <tr bgcolor="#f7f7f9" >
                        <?php }else{ ?>
                        <tr bgcolor="#e2e2e7" >
                            <?php } ?>
                            <input type="hidden" id="transId_<?php echo $i; ?>" value="<?php echo $val['TransactionId']; ?>" />
                            <input type="hidden" id="derProdName_<?php echo $i; ?>" value="<?php echo $newVal['DerivedProdName']; ?>" />
                        <td width="8%" height="25" valign="top"  style="padding:0"><input id="userNo_<?php echo $i; ?>" type="checkbox" value="<?php echo $val['TransactionId'].'##'.$newVal['DerivedProdId']; ?>" name="TransactionId<?php echo $i; ?>" /></td>
                        <td width="11%" valign="top" style="padding:5px 10px 0px 10px">
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
                    <td width="11%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $newVal['DerivedProdName'].' :'.$newVal['DerivedProdId']; ?></td>
                    <td width="11%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['TransactTime']; ?></td>
                    <td width="11%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['ClientId']; ?></td>
                    <td width="11%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['businessCollege']; ?></td>
                    <td width="11%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['SalesPersonName'].' :'.$val['SalesBy']; ?></td>
                    <td width="11%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['FinalSalesAmount']; ?></td>
                    <?php if($searchTypeForTransaction == 'Validate'){ ?>
                    <td width="11%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['DiscountLimit'].'%'; ?></td>
                    <?php } ?>
                    <td width="11%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['Discount'].'%'; ?></td>
                    <?php if($queueType=="OPS"){ ?> 
                    <td valign="top" style="padding:5px 10px 0px 10px">
                        <input type="text" readonly id="subs_start_date<?php echo $i; ?>" name="subsStartDate<?php echo $i; ?>" size="8" onclick="cal.select($('subs_start_date<?php echo $i; ?>'),'sd_<?php echo $i; ?>','yyyy-MM-dd');"><img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="sd_<?php echo $i; ?>" onClick="cal.select($('subs_start_date<?php echo $i; ?>'),'sd_<?php echo $i; ?>','yyyy-MM-dd');" />
                    </td>
                    <?php } ?>
                </tr>
                <?php $i++; 
                }
                $colorFlag = !$colorFlag;
            } ?>

        </table>
    </div>

    <input type="hidden" id="totalUserCount" name="totalUserCount" value="<?php echo $i-1; ?>" />
    <div class="lineSpace_5">&nbsp;</div>
    <div id="checkbox_unselect_error" style="display:none;color:red;"></div><br/>
    <div>
        <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
            <tr>
                <td width="100%" height="20" colspan="8" bgcolor="#EEEEEE">&nbsp;</td>
            </tr>
            <tr>
                <td height="20" colspan="7" align="right" width="80%">
                    <button class="btn-submit19" onclick="$('frmSelectTransact').action='/sums/Manage/approveOps';validateFormSumsSubs();" type="button" value="" style="width:190px">
                        <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">CREATE SUBSCRIPTION</p></div>
                    </button>
                </td>
                <?php if(array_key_exists(17,$sumsUserInfo['sumsuseracl'])){?>
                <td height="20" colspan="7" align="right" wdith="80%" style="display:none;">
                    <button class="btn-submit19" onclick="showHideCancelComment('show');" type="button" value="" style="width:190px">
                        <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">CANCEL TRANSACTION</p></div>
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
        <button class="btn-submit19" onclick="$('frmSelectTransact').action='/sums/Manage/cancelTransaction';validateFormSumsSubs();" type="button" value="" style="width:190px">
            <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">CANCEL TRANSACTION</p></div>
        </button>
        <button class="btn-submit19" onclick="showHideCancelComment('hide');" type="button" value="" style="width:190px">
            <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">RESET</p></div>
        </button>
    </div>
    <?php } ?>
