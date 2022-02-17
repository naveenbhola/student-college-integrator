<?php if ($users == NULL) { ?>
    <div class="mar_top_6p">No results found.</div>
<?php } else { ?>

    <div class="OrgangeFont fontSize_14p bld"><strong>View Client(s) Summary</strong></div>
    <div class="grayLine"></div>
    <div>
        <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
            <tr>
                <td colspan="7" bgcolor="#FFFFFF">&nbsp;</td>
            </tr>
            <tr>
                <td width="4%" height="25" valign="top" bgcolor="#EEEEEE" style="padding:5px 5px 0px 5px"><img
                        src="/public/images/space.gif" width="23" height="30"/></td>
                <td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>CLIENT
                        ID</strong></td>
                <td width="17%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>COLLEGE /
                        INSTITUTE / UNIVERSITY NAME</strong></td>
                <td width="33%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>LOGIN EMAIL
                        ID</strong></td>
                <td width="12%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>DISPLAY
                        NAME</strong></td>
                <td width="13%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>CONTACT
                        NAME</strong></td>
                <td width="13%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>CONTACT
                        NO</strong></td>
            </tr>
        </table>
        <form id="selectedUser" method = "post" action="">

            <div style="overflow:auto; height:115px">
                <table width="98%" border="0" cellspacing="3" cellpadding="0">
                    <tr>
                        <td width="4%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15"
                                                                           height="1"/></td>
                        <td width="8%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15"
                                                                           height="1"/></td>
                        <td width="17%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15"
                                                                            height="1"/></td>
                        <td width="33%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15"
                                                                            height="1"/></td>
                        <td width="12%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15"
                                                                            height="1"/></td>
                        <td width="13%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15"
                                                                            height="1"/></td>
                        <td width="11%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15"
                                                                            height="1"/></td>
                    </tr>
                    <?php
                    $i = 1;
                    foreach ($users as $key => $oneUser) { ?>
                        <tr>

                            <td height="25" valign="top" style="padding:0"><input id="userNo_<?php echo $i; ?>"
                                                                                  type="radio"
                                                                                  value="<?php echo $key; ?>"
                                                                                  name="selectedUserId" <?php if(count($users) == 1) { echo "checked"; }?>/></td>
                            <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $key; ?></td>
                            <td valign="top"
                                style="padding:5px 10px 0px 10px"><?php echo $oneUser['collegeName']; ?></td>
                            <?php if (strlen(trim($oneUser['email'])) > 43) {
                                $EMAIL = substr(trim($oneUser['email']), 0, 40) . "...";
                            } else {
                                $EMAIL = trim($oneUser['email']);
                            }
                            ?>
                            <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $EMAIL; ?></td>
                            <td valign="top"
                                style="padding:5px 10px 0px 10px"><?php echo $oneUser['displayname']; ?></td>
                            <td valign="top"
                                style="padding:5px 10px 0px 10px"><?php echo $oneUser['contactName']; ?></td>
                            <td valign="top"
                                style="padding:5px 10px 0px 10px"><?php echo $oneUser['contactNumber']; ?></td>
                        </tr>
                        <?php $i++;
                    } ?>

                </table>
            </div>
            <div class="lineSpace_5">&nbsp;</div>
            <div id="radio_unselect_error" style="display:none;color:red;"></div>
            <br/>

                <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                    <tr>
                        <td width="100%" height="20" colspan="7" bgcolor="#EEEEEE">&nbsp;</td>
                    </tr>
                    <tr>
                        <td height="20" colspan="7" align="right">
                            <input type="submit"
                                   onclick="MainListing.validateAndSendRadioForm('/nationalCategoryList/CategoryProductEnterprise/showClientInstitutes/set', 'selectedUser', 'radio_unselect_error');"
                                   value="Set Main Institute" class="orange-button">
                            <input type="submit"
                                   onclick="MainListing.validateAndSendRadioForm('/nationalCategoryList/CategoryProductEnterprise/showClientInstitutes/unset', 'selectedUser', 'radio_unselect_error');"
                                   value="Unset Main Institute" class="orange-button">
                        </td>
                    </tr>
                </table>

        </form>
    </div>
    <div class="clear_L"></div>
<?php } ?>

