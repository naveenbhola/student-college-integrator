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
        'css'	=>	array('headerCms','raised_all','mainStyle','footer','cal_style'),
        'js'	=>	array('common','enterprise','home','CalendarPopup','prototype','discussion','events','listing'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'	=>	'',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'	=>''
    );
    $this->load->view('enterprise/headerCMS', $headerComponents);
?>
</head>

<?php $this->load->view('enterprise/cmsTabs'); ?>

<script>
    var subscriptionsList = eval(<?php echo json_encode($subscriptionDetails); ?>);
    var productInfo = <?php echo json_encode($productInfo); ?>;
</script>




<body style="margin:0 10px">
    <?php if(isset($userDetails)){ ?>
    <form id="selectCountrySetInst" method="post" action="/index.php/enterprise/Enterprise/setSponsored">
    <table width="100%" cellspacing="2">
        <tr>
            <td><span class="OrgangeFont bld">Email:</span> <b><?php echo $userDetails['email']; ?></b> </td>
            <td><span class="OrgangeFont bld">Client Id:</span><b> <?php echo $userDetails['clientUserId']; ?></b> </td>
            <td><span class="OrgangeFont bld">Display Name:</span><b> <?php echo $userDetails['displayname']; ?></b> </td>
            <td><span class="OrgangeFont bld">Country:</span><select name="countryRequested" onchange="this.form.submit();"><option value="national" <?php if($countryRequested == 'national' || $countryRequested == ''){echo "selected";}?>>India</option><option  value="abroad" <?php if($countryRequested == 'abroad'){echo "selected";}?>>Abroad</option></select></td>
        </tr>
<input type="hidden" name="onBehalfOf" value="true" />
<input type="hidden" name="selectedUserId" value="<?php echo $userDetails['clientUserId']; ?>" />
<input type="hidden" id="clientUserId" value="<?php echo $userDetails['clientUserId']; ?>" />
<input type="hidden" id="cmsUserId" value="<?php echo $userid; ?>" />
    </table>
    </form>
    <?php }else{ ?>
<input type="hidden" id="clientUserId" value="<?php echo $userid; ?>" />
<input type="hidden" id="cmsUserId" value="" />
   <?php }
    ?>

<div class="lineSpace_10" style="width:100%">&nbsp;</div>


<table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse;" bordercolor="#999999">
    <tr>
        <td width="3%" height="25" align="center" valign="middle" bgcolor="#99CCFF">&nbsp;</td>
        <td width="23%" align="left" valign="middle" bgcolor="#99CCFF" style="padding-left:10px"><strong>Listing Type ID</strong></td>
        <td width="25%" align="left" valign="middle" bgcolor="#99CCFF" style="padding-left:10px"><strong>Listing Title</strong> </td>
        <td width="25%" align="left" valign="middle" bgcolor="#99CCFF" style="padding-left:10px"><strong>Listing Type</strong> </td>
        <td width="24%" align="left" valign="middle" bgcolor="#99CCFF" style="padding-left:10px"><strong>Expiry Date</strong> </td>
    </tr>
</table>

<div style="overflow:auto; height:115px">
    <table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse;" bordercolor="#999999">
        <?php
            $i =0;
            foreach($clientListings as $key => $val){ ?>
        <tr>
            <td width="3%" valign="top"><input type="radio" name="r1" value="<?php echo $val['listing_type_id']; ?>" id="selectedListingId_<?php echo $i; ?>" name="selectedListingId" onClick="setValInListingInfoDiv(<?php echo $i;?>);"  /></td>
            <td width="23%" valign="top" style="padding:0 10px"><?php echo $val['listing_type_id']; ?></td>
            <td width="25%" valign="top" style="padding:0 10px"><?php echo $val['listing_title']; ?></td>
            <td width="25%" valign="top" style="padding:0 10px"><?php echo $val['listing_type']; ?></td>
            <td width="25%" valign="top" style="padding:0 10px"><?php echo $val['expiry_date']; ?></td>
            <td> 
                <input type="hidden" id="selectedListingTypeId_<?php echo $i; ?>" value="<?php echo $val['listing_type_id']; ?>" /> 
                <input type="hidden" id="selectedListingType_<?php echo $i; ?>" value="<?php echo $val['listing_type']; ?>" /> 
            </td>
        </tr>
        <?php $i++; 
        } ?>

    </table>
</div>
<input type="hidden" id="totalListings" value="<?php echo $i; ?>" />
<div id="listingInfo">
<input type="hidden" id="selectedListingTypeId" value="" />
<input type="hidden" id="selectedListingType" value="" />

</div>
<div class="lineSpace_10" style="width:100%">&nbsp;</div>
 <?php if($i > 0){ ?>
<table width="100%" cellspacing="2" cellpadding="0" style="border:1px solid #CCCCCC">
    <tr>
        <td width="51%" valign="top" style="padding-left:10px">
            <div class="lineSpace_10">&nbsp;</div>
            <strong class="OrgangeFont">Choose Subscription:</strong>
            <select onchange="keywordNsponsorPackChange();" id="selectedSubscription" validate="validateSelect" minlength="1" maxlength="100" caption="Pack" >
                <option value="" selected>Select Subscription</option>
                <?php
                    foreach($subscriptionDetails as $key=>$vals){
                        if( ($vals['BaseProdCategory']=='Keywords-Featured Panel') || ($vals['BaseProdCategory']=='Keywords-Sponsored Listing') || ($vals['BaseProdCategory']=='Category Pages')){
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
                    <div style="margin:10px 10px 10px 0; padding:10px; border:1px solid #CCCCCC; background:#F8F8F8;">
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
<?php }else{?>
     <?php if($countryRequested == 'abroad'){
		$noInstMsg = "Sorry! No University found for this client.  Please select National from drop-down.";
	    }else{
		$noInstMsg = "Sorry! No Institute found for this client. Please select Abroad from drop-down.";
	    }?>
        <p align="center" valign="middle"><?=$noInstMsg;?></p>
<?php }?>

<div class="lineSpace_10" style="width:100%">&nbsp;</div>

<div id="keywordsTable" style="display:none;">
    <h5 style="color:red;">You cannot set search sponsored/featured from this page, Please go back and choose set sponsored option.</h5>
    <div class="lineSpace_20" style="width:100%">&nbsp;</div>
    <!--
    <strong class="OrgangeFont">Base Product Name:</strong> 
    <b><div id="keywordSubCategory"></div></b>
    <table width="100%" cellspacing="10" style="border:1px solid #CCCCCC" >
        <tr>
            <td  align="left" valign="top" width="200px"><strong>Input Keyword(s)</strong></td>
            	<td valign="top">
                	<textarea id="keywordsCSV" rows="10" cols="45"></textarea>
                </td>
        </tr>
        <tr>
                <td align ="left" valign="top" width="200px"><strong>Input Location</strong></td>
                <td  align ="left" valign="top">
                <input type="text" name="location" id="location"></input>
                </td>
         </tr>
         <tr>
                <td>
                <div class="lineSpace_10">&nbsp;</div>
                <button id="sponsoredButton" class="btn-submit19" onclick="setKeyword('sponsor');" type="button" value="" style="width:140px;display:none;">
                    <div class="btn-submit19" ><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Set as Sponsored</p></div>
                </button>
                <button id="featuredButton" class="btn-submit19" onclick="setKeyword('featured');" type="button" value="" style="width:140px;display:none;">
                    <div class="btn-submit19" ><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Set as Featured</p></div>
                </button>
                </td>
                <td>&nbsp;</td>
         </tr>
    </table>
    -->
</div>

<div id="keyPagesTable" style="display:none;">
    <strong class="OrgangeFont">Base Product Name:</strong> 
    <b><div id="maincollegeSubCategory"></div></b>
    <table width="100%" cellspacing="2" style="border:1px solid #CCCCCC">
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
           <!--  <td width="18%" align="right" valign="top"><strong>Set for Category Keypage</strong></td> -->
            <td width="82%" valign="top" colspan="2">
                <div id="noData_error" class="errorMsg"></div>
                <div id="mainCollegeLinkDiv">
                <div class="lineSpace_10">&nbsp;</div>
                <!-- <div id="keyPagesDiv"> </div> -->
                <div>
                    <span id="testPrepPlace" style="display:none;">
                        <strong>
                            Choose Test Preperation Category :
                        </strong>
                    </span>
                    <span id="countryPlace" style="display:none;">
                        <strong>
                            Choose Country :
                        </strong>
                    </span>
                    <span id="cityPlace" style="display:none;">
                        <strong>
                            Choose City :
                        </strong>
                    </span>
                    <span id="statePlace" style="display:none;">
                        <strong>
                            Choose State :
                        </strong>
                    </span>
                    <span id="categoryPlace" style="display:none;">
                        <strong>
                            Choose Category :
                        </strong>
                    </span>
                    <span id="subcategoryPlace" style="display:none;">
                        <strong>
                            Choose Sub-Category :
                        </strong>
                    </span>
                </div>
                <div class="lineSpace_5">&nbsp;</div>
                <div id="setKeyPage_error" class="errorMsg"></div>
                <div class="lineSpace_10">&nbsp;</div>
                    <?php if($countryRequested == 'abroad') { ?>
                <button class="btn-submit19" onclick="setKeyPage();" type="button" value="" style="width:140px">
                    <div class="btn-submit19" ><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Set Key-Page</p></div>
                </button>
                    <?php } ?>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
    </table>
</div>
<div class="lineSpace_20" style="width:100%">&nbsp;</div>

</body>
</html>