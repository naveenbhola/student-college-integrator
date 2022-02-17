<?php
$headerComponents = array(
    'css'          => array('headerCms', 'mainStyle'),
    'js'           => array('common', 'category-sponsor'),
    'displayname'  => (isset($displayName) ? $displayName : ""),
    'tabName'      => '',
    'title'        => 'CMS - Select an institute to set as Main',
    'taburl'       => site_url('enterprise/Enterprise'),
    'metaKeywords' => ''
);
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs');
?>

<table width="100%" cellspacing="2">
    <tr>
        <td><span class="OrgangeFont bld">Email:</span> <b><?php echo $clientEmail; ?></b> </td>
        <td><span class="OrgangeFont bld">Client Id:</span><b> <?php echo $clientId; ?></b> </td>
        <td><span class="OrgangeFont bld">Display Name:</span><b> <?php echo $clientDisplayName; ?></b> </td>
        <td><span class="OrgangeFont bld">Country:</span><b>India</b></td>
    </tr>
</table>

<input type="hidden" id="clientUserId" value="<?php echo $clientId; ?>"/>
<input type="hidden" id="cmsUserId" value="<?php echo $userid; ?>"/>

<div class="lineSpace_10" style="width:100%">&nbsp;</div>



<table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse;" bordercolor="#999999">
    <tr>
        <td width="3%" height="25" align="center" valign="middle" bgcolor="#99CCFF">&nbsp;</td>
        <td width="23%" align="left" valign="middle" bgcolor="#99CCFF" style="padding-left:10px"><strong>Listing Type
                ID</strong></td>
        <td width="25%" align="left" valign="middle" bgcolor="#99CCFF" style="padding-left:10px"><strong>Listing
                Title</strong></td>
        <td width="25%" align="left" valign="middle" bgcolor="#99CCFF" style="padding-left:10px"><strong>Listing
                Type</strong></td>
        <td width="24%" align="left" valign="middle" bgcolor="#99CCFF" style="padding-left:10px"><strong>Expiry
                Date</strong></td>
    </tr>
</table>

<div style="overflow:auto; height:115px">
    <table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse;"
           bordercolor="#999999" id="listingIdContainer">
        <?php
        $i = 0;
        foreach ($clientListings as $key => $val) { ?>
            <tr>
                <td width="3%" valign="top"><input type="radio" name="selectedListingTypeId" value="<?php echo $val['listing_type_id']; ?>"
                                                   id="selectedListingId_<?php echo $i; ?>" name="selectedListingId" onchange="MainListing.resetSubscription('subscription');"/></td>
                <td width="23%" valign="top" style="padding:0 10px"><?php echo $val['listing_type_id']; ?></td>
                <td width="25%" valign="top" style="padding:0 10px"><?php echo $val['listing_title']; ?></td>
                <td width="25%" valign="top" style="padding:0 10px"><?php echo $val['listing_type']; ?></td>
                <td width="25%" valign="top" style="padding:0 10px"><?php echo $val['expiry_date']; ?></td>
            </tr>
            <?php $i++;
        } ?>

    </table>
</div>

<div class="lineSpace_10" style="width:100%">&nbsp;</div>
<?php if ($i > 0) { ?>
    <table width="100%" cellspacing="2" cellpadding="0" style="border:1px solid #CCCCCC">
        <tr>
            <td width="51%" valign="top" style="padding-left:10px">
                <div class="lineSpace_10">&nbsp;</div>
                <strong class="OrgangeFont">Choose Subscription:</strong>
                <select id="subscription" onchange="MainListing.selectSubscription(this);"
                        validate="validateSelect"
                        minlength="1" maxlength="100" caption="Pack">
                    <option value="" selected>Select Subscription</option>
                    <?php
                    foreach ($subscriptionDetails as $key => $vals) {
                        if (($vals['BaseProdCategory'] == 'Keywords-Featured Panel') || ($vals['BaseProdCategory'] == 'Keywords-Sponsored Listing') || ($vals['BaseProdCategory'] == 'Category Pages')) {
                            ?>
                            <option
                                value="<?php echo implode("|", $vals); ?>"><?php echo $vals['BaseProdCategory'], "-", $vals['BaseProdSubCategory'], " : ", $key; ?></option>
                            <?php
                        }
                    } ?>
                </select>

                <div class="row errorPlace">
                    <div class="r1">&nbsp;</div>
                    <div class="r2 errorMsg" id="userPack_error"></div>
                    <div class="clear_L"></div>
                </div>

            </td>
            <td width="49%" valign="top">
                <div class="lineSpace_10">&nbsp;</div>
                <div id="selected_pack_info" style="display:none;">
                    <strong class="OrgangeFont">Features of Selected Subscription <img src="/public/images/blkArrow.gif"
                                                                                       align="absmiddle"/></strong>

                    <div style="margin:10px 10px 10px 0; padding:10px; border:1px solid #CCCCCC; background:#F8F8F8;">
                        <div style="line-height:20px"><strong style="padding-right:5px">Subscription ID:</strong>

                            <span id="SubscriptionId" name="SubscriptionId"></span>
                        </div>
                        <div style="line-height:20px"><strong style="padding-right:5px">Subscription Start
                                Date:</strong>

                            <span id="SubsStartDate" name="SubsStartDate"></span>
                        </div>
                        <div style="line-height:20px"><strong style="padding-right:5px">Subscription Expiry
                                Date:</strong>

                            <span id="SubsEndDate" name="SubsEndDate"></span>
                        </div>
                        <div style="line-height:20px"><strong style="padding-right:5px">Total Quantity:</strong>

                            <span id="TotalQty" name="TotalQty"></span>
                        </div>
                        <div style="line-height:20px"><strong style="padding-right:5px">Remaining Quantity:</strong>

                            <span id="RemainingQty" name="RemainingQty"></span>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
<?php } else { ?>
    <p align="center" valign="middle"><?php echo "Sorry! No Institute found for this client"; ?></p>
<?php } ?>

<div class="lineSpace_10" style="width:100%">&nbsp;</div>

<div id="keywordsTable" style="display:none;">
    <h5 style="color:red;">You cannot set search sponsored/featured from this page, Please go back and choose set
        sponsored option.</h5>

    <div class="lineSpace_20" style="width:100%">&nbsp;</div>

</div>

<div id="keyPagesTable" style="display:none;">
    <strong class="OrgangeFont">Base Product Name:</strong>
    <b>
        <div id="maincollegeSubCategory"></div>
    </b>
    <table width="100%" cellspacing="2" style="border:1px solid #CCCCCC">
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td width="82%" valign="top" colspan="2">
                <div id="noData_error" class="errorMsg"></div>
                <div id="mainCollegeLinkDiv">
                    <div class="lineSpace_10">&nbsp;</div>
                    <div>
                        <select name="cities" id="cities">
                            <option value="">Choose City :</option>
                        </select>
                        <select name="states" id="states">
                            <option value="">Choose State :</option>
                        </select>
                        <select name="criterion" id="criterion">
                            <option value="">Choose Category :</option>
                        </select>

                    </div>
                    <div class="lineSpace_5">&nbsp;</div>
                    <div id="setKeyPage_error" class="errorMsg"></div>
                    <div class="lineSpace_10">&nbsp;</div>
                    <input type="submit" id="submit" class="orange-button" value="Set Main Listing" onclick="MainListing.setMainListing();"/>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
    </table>
</div>
<div class="lineSpace_20" style="width:100%">&nbsp;</div>
<?php $this->load->view('enterprise/footer'); ?>
