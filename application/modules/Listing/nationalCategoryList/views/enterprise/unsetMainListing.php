    <?php
    $headerComponents = array(
        'css'          => array('headerCms', 'mainStyle'),
        'js'           => array('common'),
        'displayname'  => (isset($cmsUserInfo['validity'][0]['displayname']) ? $cmsUserInfo['validity'][0]['displayname'] : ""),
        'tabName'      => '',
        'title'        => 'CMS - Select and unset a client main listing',
        'taburl'       => site_url('enterprise/Enterprise'),
        'metaKeywords' => ''
    );
    $this->load->view('enterprise/headerCMS', $headerComponents);
    $this->load->view('enterprise/cmsTabs');

    $actionURL = site_url() . 'nationalCategoryList/CategoryProductEnterprise/unsetMainListing';
    ?>
    <style type="text/css">
        .mainInstTable {
            font: normal 12px Arial, Helvetica, sans-serif;
            color: #000
        }

        .mainInstTable tr th {
            background: #e8e8e8;
            font-size: 14px;
            font-weight: normal;
            padding: 8px 6px
        }

        .gray-rule {
            background: #dfdfdf;
            height: 1px;
            overflow: hidden;
            width: 100%;
            margin: 8px 0
        }

        .alt-rowBg {
            background: #f5f5f5
        }
    </style>

<form name="frm1" action="<?= $actionURL ?>" onsubmit="return validateData();" method="post">
    <div style="margin: 10px 15px;">
        <h2 style="font:normal 20px 'Trebuchet MS', Arial, Helvetica, sans-serif; margin-bottom:15px; color:#fd7f04; display:block">
            Unset Main Institute</h2>
        <?php // echo "Cookie = ".$_COOKIE['thanksMsgCookie'];
        if (isset($_COOKIE['thanksMsgCookie']) && $_COOKIE['thanksMsgCookie'] != "") {
            ?>
            <p style="font:normal 18px 'Trebuchet MS', Arial, Helvetica, sans-serif; margin-bottom:15px; color:red; text-align:center;"><?= $_COOKIE['thanksMsgCookie'] ?></p>
            <?php
        } // End of if( isset( $_COOKIE['thanksMsgCookie'] ) && $_COOKIE['thanksMsgCookie'] != "" ).
        ?>
        <div style="border:1px solid #cdcdcd; padding:12px">
            <table width="100%" cellspacing="2">
                <tr>
                    <td><span class="OrgangeFont bld">Email:</span> <b><?php echo $clientEmail; ?></b> </td>
                    <td><span class="OrgangeFont bld">Client Id:</span><b> <?php echo $clientId; ?></b> </td>
                    <td><span class="OrgangeFont bld">Display Name:</span><b> <?php echo $clientDisplayName; ?></b> </td>
                    <td><span class="OrgangeFont bld">Country:</span><b>India</b></td>
                </tr>
            </table>
            <input type="hidden" name="clientUserId" id="clientUserId" value="<?php echo $clientId;?>"/>
            <input type="hidden" name="cmsUserId" id="cmsUserId" value="<?php echo $userid; ?>"/>
            <div class="lineSpace_10" style="width:100%">&nbsp;</div>
            <?php

            if ($clientListings == 'NO_INSTITUTE_FOUND' || $clientListings == "") { ?>
                <div class="lineSpace_10" style="width:100%">&nbsp;</div>
                <table width="100%" border="0" cellspacing="0" cellpadding="6" class="mainInstTable">
                    <tr>
                        <td align="center" valign="middle"><?php echo "Sorry! No Institute is set as Main Institute for this client"; ?></td>
                    </tr>
                </table>
                <?php
            } else {
                ?>
                <table width="100%" border="0" cellspacing="0" cellpadding="6" class="mainInstTable">
                    <tr>
                        <th width="3%" align="center" valign="middle"><input type="checkbox" name="selectAll"
                                                                             onChange="toggleAll(this);"/></th>
                        <th width="16%" align="left" valign="middle">Listing Name</th>
                        <th width="15%" align="left" valign="middle">Criterion</th>
                        <th width="10%" align="left" valign="middle">Location</th>
                        <th width="10%" align="left" valign="middle">Start Date</th>
                        <th align="left" valign="middle">Expiry Date</th>
                    </tr>

                    <?php
                    $count = 0;
                    foreach ($clientListings as $oneListing) {
                        if ($count % 2 == 1) {
                            $class = ' class="alt-rowBg"';
                        } else {
                            $class = '';
                        }
                        ?>
                        <tr<?= $class ?>>
                            <td width="3%" valign="top"><input type="checkbox" name="selectedInstitutesChkbox[]"
                                                               value="<?php echo $oneListing['listing_subs_id']; ?>"
                                                               id="selectedListingId_<?php echo $count; ?>"/></td>
                            <td width="16%" valign="top"><?php echo $oneListing['institute_name']; ?></td>
                            <td width="15%" valign="top"><?php echo $oneListing['criterion_name']; ?></td>
                            <td width="10%" valign="top"><?php echo $oneListing['location']; ?></td>
                            <td width="10%" valign="top"><?php echo $oneListing['start_date']; ?></td>
                            <td width="10%" valign="top"><?php echo $oneListing['end_date']; ?></td>
                        </tr>
                        <?php
                        $count++;
                    } ?>
                    <tr>
                        <td colspan="9">
                            <div class="gray-rule"></div>
                            <div class="lineSpace_10" style="width:100%">&nbsp;</div>
                            <input type="submit" value="Unset Main Institute" name="bttnSubmit" id="bttnSubmit"
                                   class="orange-button"/></td>
                    </tr>
                </table>
                <?php
            } // End of if($clientListings == 'NO_INSTITUTE_FOUND').
            ?>
        </div>
    </div>
    <input type="hidden" name="totalListings" id="totalListings" value="<?php echo $count; ?>"/>

    <div class="lineSpace_10" style="width:100%">&nbsp;</div>
</form>
<?php $this->load->view('enterprise/footer'); ?>

<script type="text/javascript">
    var totalListings = $("totalListings").value;
    function toggleAll(objtest) {
        var elementId;
        for (var i = 0; i < totalListings; i++) {
            elementId = "selectedListingId_" + i;
            // alert("elementId = "+elementId+" , chkd = "+$(elementId).checked);
            if ($(objtest).checked) {
                $(elementId).checked = true;
            } else {
                $(elementId).checked = false;
            }
        }
    }

    function validateData() {

        // alert("totalListings = "+totalListings);
        var elementId;
        var checked = 0;
        for (var i = 0; i < totalListings; i++) {
            elementId = "selectedListingId_" + i;
            // alert("elementId = "+elementId+" , chkd = "+$(elementId).checked);
            if ($(elementId).checked) {
                checked = 1;
                break;
            }
        }

        if (checked == 0) {
            alert("Please select at least 1 institute to unset!");
            return false;
        } else {
            if (confirm("Are you sure, you want to unset the Main Institute on the selected combination(s)?")) {
                return true;
            } else {
                return false;
            }
        }
    }
</script>