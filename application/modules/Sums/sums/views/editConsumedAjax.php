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


    if ($result==NULL) { ?>
    <div class="mar_top_6p">No results found.</div>
    <?php } else { ?>

    <div class="OrgangeFont fontSize_14p bld"><strong>Change Start/End(Expiry) date of a Consumption</strong></div>
    <div class="grayLine"></div>
    <div class="lineSpace_10">&nbsp;</div>

    <?php $FLAG_EDIT=false;
        if(array_key_exists(44,$sumsUserInfo['sumsuseracl'])){
            if($sumsUserInfo['sumsuserinfo'][0]['Role']==3){
                if(in_array($transactionInfo[0]['SalesBranch'],$sumsuserinfo[0]['BranchIds'])){
                    $FLAG_EDIT=true;
                }
            }else{
                $FLAG_EDIT=true;
            }
        }
        $FLAG_EDIT=true;
    ?>
    <div>
        <table width="98%" border="0" cellspacing="1" cellpadding="0">                        
            <tr>
                <td width="4%" height="25" valign="top" bgcolor="#EEEEEE" style="display:none;padding:0"><input id="selectAll" type="checkbox" onClick="selectAllKeyIds();" /></td>
                <td width="6%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Consumed Id</strong> </td>
                <td width="6%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Consumed Type</strong> </td>
                <td width="6%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Consumed Name/Title</strong> </td>
                <td width="6%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Quantity Consumed</strong> </td>
                <td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Start-Date</strong> </td>
                <td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Expiry-Date</strong> </td>
                <?php if($FLAG_EDIT){?>
                <td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Change-Dates</strong> </td>
                <!--<td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Status</strong> </td> -->
                <?php } ?>
                <td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Last-Modified</strong> </td>
                <td width="6%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Old Start-Date</strong> </td>
                <td width="6%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Old End-Date</strong> </td>
                <td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Client-Name</strong> </td>
                <td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>On-behalf-of CMS User</strong> </td>
            </tr>
            <tr>
                <td width="4%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                <td width="12%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                <td width="12%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
            </tr>
            <?php 
                $i=1; 
                $colorFlag = false;
            ?> 

            <?php foreach ($result as $key=>$val) { 
                            $colorFlag = !$colorFlag;
                    ?>
                    <?php if($colorFlag == false){?>
                    <tr bgcolor="#f7f7f9" >
                        <?php }else{ ?>
                        <tr bgcolor="#e2e2e7" >
                            <?php } ?>
                    <?php 
                        $startDate = explode(" ",$val['ConsumptionStartDate']);
                        $endDate = explode(" ",$val['ConsumptionEndDate']);
                    ?>
                        <td width="4%" height="25" valign="top"  style="display:none;padding:0"><input id="userNo_<?php echo $i; ?>" type="checkbox" value="" name="ConsumedIds<?php echo $i; ?>" onClick="selectAllCheck();"/></td>
                    <input type="hidden" id="subs_id<?php echo $i; ?>" value="<?php echo $val['SubscriptionId']; ?>" />
                    <td width="6%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['ConsumedId']; ?></td>
                    <input type="hidden" id="consumed_id<?php echo $i; ?>" value="<?php echo $val['ConsumedId']; ?>" />
                    <td width="6%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['ConsumedIdType']; ?></td>
                    <input type="hidden" id="consumed_type<?php echo $i; ?>" value="<?php echo $val['ConsumedIdType']; ?>" />
                    <td width="6%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['IdTitleOrName']; ?></td>
                    <td width="6%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['NumberConsumed']; ?></td>
                    <td valign="top" style="padding:5px 10px 0px 10px">
                        <input type="text" readonly id="subs_start_date<?php echo $i; ?>" name="subsStartDate<?php echo $i; ?>" size="8" onclick="cal.select($('subs_start_date<?php echo $i; ?>'),'sd_<?php echo $i; ?>','yyyy-MM-dd');" value="<?php echo $startDate[0]; ?>"><img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="sd_<?php echo $i; ?>" onClick="cal.select($('subs_start_date<?php echo $i; ?>'),'sd_<?php echo $i; ?>','yyyy-MM-dd');" />
                    </td>
                    <td valign="top" style="padding:5px 10px 0px 10px">
                        <input type="text" readonly id="subs_end_date<?php echo $i; ?>" name="subsEndDate<?php echo $i; ?>" size="8" onclick="cal.select($('subs_end_date<?php echo $i; ?>'),'ed_<?php echo $i; ?>','yyyy-MM-dd');" value="<?php echo $endDate[0]; ?>"><img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="ed_<?php echo $i; ?>" onClick="cal.select($('subs_end_date<?php echo $i; ?>'),'ed_<?php echo $i; ?>','yyyy-MM-dd');" />
                    </td>

                    <?php if($FLAG_EDIT){?>
                    <td width="8%" valign="top" style="padding:5px 10px 0px 10px">
                    <button class="btn-submit19" onclick="changeDates(<?php echo $i; ?>);" type="button" value="" style="width:100px">
                    <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Change Dates<div>
                    </button>
                    </td>
                    <?php } ?>
                    <!--<td width="8%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['Status']; ?></td> -->
                    <input type="hidden" id="status_<?php echo $i; ?>" value="<?php echo $val['Status']; ?>" /> 
                    <td width="8%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['ConsumptionTime']; ?></td>
                    <td width="8%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['oldConsumptionStartDate']; ?></td>
                    <td width="8%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['oldConsumptionEndDate']; ?></td>
                    <td width="6%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['ClientName']; ?></td>
                    <td width="6%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['onBehalfOfCMSuser']; ?></td>
                </tr>
                <?php 
                $i++;
            } ?>

        </table>
    </div>

    <input type="hidden" id="totalUserCount" name="totalUserCount" value="<?php echo $i-1; ?>" />
    <div class="lineSpace_5">&nbsp;</div>
    <div id="checkbox_unselect_error" style="display:none;color:red;"></div><br/>
    <div id="dateSanityCheck" style="display:none;color:red;"></div><br/>
    <div id="FinalSanityCheck" style="display:none;color:red;"></div><br/>
    <div>
        <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE" style="display:none;">
            <tr>
                <td width="100%" height="20" colspan="8" bgcolor="#EEEEEE">&nbsp;</td>
            </tr>
            <tr>
                <?php if($FLAG_EDIT){?>
                <td height="20" colspan="7" align="left" wdith="80%">
                    <button class="btn-submit19" onclick="showHideCancelComment('show');" type="button" value="" style="width:190px">
                        <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Disable Subscriptions</p></div>
                    </button>
                </td>
                <?php } ?>
            </tr>
        </table>

    </div>
    <div class="clear_L"></div>
    <div id="cancelDiv" style="display:none;">
        <b> Please input reason for Disapproval .. </b><br/>
        <textarea type="text" name="CancelComments" id="CancelComments" minlength="0" validate="validateStr" maxlength="5000" style="height:130px;" caption="Cancel Comments" /></textarea><br/>
        <button class="btn-submit19" onclick="$('frmSelectTransact').action='/sums/Subscription/disableSubscriptions';validateFormSumsSubs();" type="button" value="" style="width:140px">
            <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Disable Now</p></div>
        </button>
        <button class="btn-submit19" onclick="showHideCancelComment('hide');" type="button" value="" style="width:90px">
            <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Reset</p></div>
        </button>
    </div>
    <?php } ?>
