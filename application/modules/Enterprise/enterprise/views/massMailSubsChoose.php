<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/11009/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php if($usergroup == "cms"){ ?>
<title>CMS Control Page</title>
<?php } ?>
<?php if($usergroup == "enterprise"){ ?>
<title>Enterprise Control Page</title>
<?php } ?>

<?php
    $headerComponents = array(
        'css'	=>	array('headerCms','raised_all','mainStyle','footer','searchCriteria'),
        'js'	=>	array('common','enterprise','home','CalendarPopup','prototype','searchCriteria'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'	=>	'',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'	=>''
    );
    $this->load->view('enterprise/headerCMS', $headerComponents);
?>
</head>

<body>
	<div class="mar_full_10p"  style="margin-top: 15px; min-height: 500px;">
    
    <div class="OrgangeFont fontSize_18p bld"><strong>Choose Subscription</strong></div><br />

<?php //$this->load->view('enterprise/cmsTabs'); ?>

<script>
    var subscriptionsList = eval(<?php echo json_encode($subscriptionDetails); ?>);
    //var productInfo = <?php echo json_encode($productInfo); ?>;
</script>



<form id="frmSelectUser" action='' method="post">
<input type="hidden" id="extraInfoArr" name="extraInfoArray" value="<?php echo $extraInfoArray; ?>" />
    <?php if(isset($userDetails)){ ?>
    <table width="100%" cellspacing="2">
        <tr>
            <td><span class="OrgangeFont bld">Chosen client Email:</span> <b><?php echo $userDetails['email']; ?></b> </td>
            <td><span class="OrgangeFont bld">Client Id:</span><b> <?php echo $userDetails['clientUserId']; ?></b> </td>
            <td><span class="OrgangeFont bld">Client Display Name:</span><b> <?php echo $userDetails['displayname']; ?></b> </td>
        </tr>
<input type="hidden" id="clientUserId" name="clientUser" value="<?php echo $userDetails['clientUserId']; ?>" />
<input type="hidden" id="cmsUserId" name="cmsUser" value="<?php echo $userid; ?>" />
    </table>
    <?php }else{ ?>
<input type="hidden" id="clientUserId" name="clientUser" value="<?php echo $userid; ?>" />
<input type="hidden" id="cmsUserId" name="cmsUser" value="" />
   <?php }
    ?>

<div class="lineSpace_10" style="width:100%">&nbsp;</div>

<table width="100%" cellspacing="2" cellpadding="0" style="border:1px solid #CCCCCC; padding-bottom: 10px;">
    <tr>
        <td width="51%" valign="top" style="padding-left:10px">
            <div class="lineSpace_10">&nbsp;</div>
            <strong class="OrgangeFont">Choose Subscription:</strong> 
            <select onchange="massMailSubsChange();" id="selectedSubscription" name='selectedSubs' validate="validateSelect" minlength="1" maxlength="100" caption="Pack" >
                <option value="" selected>Select Subscription</option>
                <?php
                    foreach($subscriptionDetails as $key=>$vals){
                        if( ($vals['BaseProdCategory']=='Mass-Mailer')||($vals['BaseProdCategory']=='Mass-SMS')){
                        ?>
                        <option value="<?php echo $key; ?>" ><?php echo $vals['BaseProdCategory'],"-",$vals['BaseProdSubCategory']," : ",$key; ?></option>
                        <?php 
                        }
                    } ?>
                </select>

                <div class="row errorPlace">
                    <div class="r1">&nbsp;</div>
                    <div class="r2 errorMsg" id="userPack_error" ></div>
                    <div class="clear_L"></div>
                </div>

            </td>
            <td width="49%" valign="top">
                <div class="lineSpace_10">&nbsp;</div>
                <div id="selected_pack_info" style="display:none;">  
                    <strong class="OrgangeFont">Features of Selected Subscription <img src="/public/images/blkArrow.gif" align="absmiddle"  /></strong>
                    <div style="margin:10px 10px 10px 0; padding:10px; border:1px solid #CCCCCC; background:#F8F8F8i;">
                        <div style="line-height:20px"><strong style="padding-right:5px">Subscription ID:</strong><div id="SubscriptionId" name="SubscriptionId"></div></div>
                        <div style="line-height:20px"><strong style="padding-right:5px">Subscription Start Date:</strong><div id="SubsStartDate" name="SubsStartDate"></div></div>
                        <div style="line-height:20px"><strong style="padding-right:5px">Subscription Expiry Date:</strong><div id="SubsEndDate" name="SubsEndDate"></div></div>
                        <div style="line-height:20px"><strong style="padding-right:5px">Total Quantity:</strong><div id="TotalQty" name="TotalQty"></div></div>
                        <div style="line-height:20px"><strong style="padding-right:5px">Remaining Quantity:</strong><div id="RemainingQty" name="RemainingQty"></div></div>
                        <!--  <div style="line-height:20px"><strong style="padding-right:5px">Other Product Properties:</strong><div id="OtherProps" ></div></div> -->
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div id="mailerProceed" style="display:none;">
        <div class="lineSpace_10">&nbsp;</div>
        <div style="background:#f0f0f0; text-align: center; padding:5px 0;">
        <button id="featuredButton" class="btn-submit19" onclick="$('frmSelectUser').action='/mailer/Mailer/SelectUserList'; mailerProceed();" type="button" value="" style="width:140px;">
            <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Proceed to Mailer</p></div>
        </button>
        </div>
    </div>

<div id="subs_unselect_error" style="display:none;color:red;"></div><br/>

<div class="lineSpace_10" style="width:100%">&nbsp;</div>

</form>

    </div>
</body>
</html>

<script>
function massMailSubsChange()
{
    fetchLatestSubscriptionInfo();
    var subscriptInfo = subscriptionsList;
    var subsId = document.getElementById('selectedSubscription').value;
    if(subsId != ''){
        populateFeaturesForSubscription(subscriptInfo,subsId);
    }
}

function populateFeaturesForSubscription(subscriptInfo,subsId)
{
    if(subsId !=''){
        document.getElementById('selected_pack_info').style.display = 'inline';
        document.getElementById('SubscriptionId').style.display = 'inline';
        document.getElementById('SubscriptionId').innerText = subscriptInfo[subsId].SubscriptionId;

        document.getElementById('SubsStartDate').style.display = 'inline';
        document.getElementById('SubsStartDate').innerText = subscriptInfo[subsId].SubscriptionStartDate;
        document.getElementById('SubsEndDate').style.display = 'inline';
        document.getElementById('SubsEndDate').innerText = subscriptInfo[subsId].SubscriptionEndDate;
        document.getElementById('TotalQty').style.display = 'inline';
        document.getElementById('TotalQty').innerText = subscriptInfo[subsId].TotalBaseProdQuantity;
        document.getElementById('RemainingQty').style.display = 'inline';
        document.getElementById('RemainingQty').innerText = subscriptInfo[subsId].BaseProdRemainingQuantity;
        document.getElementById('mailerProceed').style.display = 'inline';
    }else{
        document.getElementById('selected_pack_info').style.display = 'none';
        document.getElementById('mailerProceed').style.display = 'none';
    }
}

function fetchLatestSubscriptionInfo()
{
    var url = '/enterprise/Enterprise/fetchLatestSubscriptionInfo';
    var data = 'userid='+document.getElementById('clientUserId').value;
    new Ajax.Request (url,{ method:'post', parameters: (data), onSuccess:function (response) {
                    try{
                      if(response.responseText.length > 0){
                         subscriptionsList = eval("eval("+response.responseText+")");
                         var subsId = document.getElementById('selectedSubscription').value;
                         populateFeaturesForSubscription(subscriptionsList,subsId);
                      }
                     } catch (e) {} 
            }});
}

function mailerProceed(){
    subsSelVal = document.getElementById('selectedSubscription').value;
    if(subsSelVal != ''){
        $('frmSelectUser').submit();
    }else{
        document.getElementById('subs_unselect_error').innerHTML = "Please select a Subscription to continue !!";
        document.getElementById('subs_unselect_error').style.display = 'inline';
    }
}

</script>
