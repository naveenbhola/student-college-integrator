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

    <div class="OrgangeFont fontSize_14p bld"><strong>Select a Subscription for Edit</strong></div>
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
                <?php if($flowType!='Consumption'){ ?>
                <td width="4%" height="25" valign="top" bgcolor="#EEEEEE" style="padding:0"><input id="selectAll" type="checkbox" onClick="selectAllKeyIds();" /></td>
                <?php } ?>
                <td width="6%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Subs. Id</strong> </td>
                <td width="6%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Base ProdName</strong> </td>
                <td width="6%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Quantity Given</strong> </td>
                <td width="6%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Quantity Remaining</strong> </td>
                <?php if($flowType!='Consumption'){ ?>
                <td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Start-Date</strong> </td>
                <td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>End-Date</strong> </td>
                <?php if($FLAG_EDIT){?>
                <td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Change-Dates</strong> </td>
                <?php } ?>
                <td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Status</strong> </td>
                <?php if($FLAG_EDIT){?>
                <td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Activate/Deactivate</strong> </td>
                <?php } ?>
                <td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Last-Modified</strong> </td>
                <?php } ?>
                <td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Client-Name</strong> </td>
                <td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Ops Approver</strong> </td>
                <?php if($flowType=='Consumption'){ ?>
                <td width="6%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Show Posted</strong> </td>
                <?php } ?>
            </tr>
            <tr>
                <td width="4%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                <td width="12%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                <td width="12%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
            </tr>
            <?php 
                $i=1; 
                $colorFlag = true;
                $oldDervId = 0;
            ?> 

            <?php foreach ($result as $key=>$val) { 
                      if($val['DerivedProductId'] != $oldDervId){
                            $colorFlag = !$colorFlag;
                      }
                    ?>
                    <?php if($colorFlag == false){?>
                    <tr bgcolor="#f7f7f9" >
                        <?php }else{ ?>
                        <tr bgcolor="#e2e2e7" >
                            <?php } ?>
                    <?php 
                        $startDate = explode(" ",$val['SubscriptionStartDate']);
                        $endDate = explode(" ",$val['SubscriptionEndDate']);
                    ?>
                <?php if($flowType!='Consumption'){ ?>
                        <td width="4%" height="25" valign="top"  style="padding:0"><input id="userNo_<?php echo $i; ?>" type="checkbox" value="<?php echo $val['SubscriptionId']; ?>" name="SubscriptionIds<?php echo $i; ?>" onClick="selectAllCheck();"/></td>
                        <?php } ?>
                    <td width="6%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['SubscriptionId']; ?></td>
                    <td width="6%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['BaseProdCategory'].'-'.$val['BaseProdSubCategory']; ?></td>
                    <td width="6%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['TotalBaseProdQuantity']; ?></td>
                    <td width="6%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['BaseProdRemainingQuantity']; ?></td>
                <?php if($flowType!='Consumption'){ ?>
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
                    <td width="8%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['Status']; ?></td>
                    <input type="hidden" id="status_<?php echo $i; ?>" value="<?php echo $val['Status']; ?>" />
                    <?php if($FLAG_EDIT){?>
                    <td width="8%" valign="top" style="padding:5px 10px 0px 10px">
                    <button class="btn-submit19" onclick="changeStatus(<?php echo $i; ?>);" type="button" value="" style="width:90px">
                    <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20"><?php if($val['Status']=='ACTIVE'){echo 'Deactivate';} if($val['Status']=='INACTIVE'){echo 'Activate';} ?><div>
                    </button>
                    </td>
                    <?php } ?>
                    <td width="8%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['SubscrLastModifyTime']; ?></td>
                    <?php } ?>
                    <td width="8%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['ClientName']; ?></td>
                    <td width="8%" valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['SumsUserName']; ?></td>
                    <?php if($flowType=='Consumption'){ ?>
                    <td width="8%" valign="top" style="padding:5px 10px 0px 10px">
                        <button class="btn-submit19" onclick="window.location='/sums/Subscription/consumedForSubs/<?php echo $val['SubscriptionId'];?>'" type="button" value="" style="width:100px">
                        <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Show Posted<div>
                    </button>
                </td>
                <?php } ?>
                </tr>
                <?php 
                $i++;
                $oldDervId = $val['DerivedProductId'] ;
            } ?>

        </table>
    </div>

    <input type="hidden" id="totalUserCount" name="totalUserCount" value="<?php echo $i-1; ?>" />
    <div class="lineSpace_5">&nbsp;</div>
    <div id="checkbox_unselect_error" style="display:none;color:red;"></div><br/>
    <div id="dateSanityCheck" style="display:none;color:red;"></div><br/>
    <div id="FinalSanityCheck" style="display:none;color:red;"></div><br/>
    <div>
      <?php if($flowType!='Consumption'){ ?>
        <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
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
  <?php } ?>

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
