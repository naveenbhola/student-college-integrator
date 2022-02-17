<?php if ($countresult == NULL) { ?>
	<div class="mar_top_6p">No results found.</div>
        <?php } else { ?>

        <div class="OrgangeFont fontSize_18p bld"><strong>View Templates Summary</strong></div>
        
        <div style="margin-top:10px;" id='templateOuter'>
            <table width="740" border="0" cellpadding="0" cellspacing="10" bordercolor="#EEEEEE">
                <tr>
                    <td width="30" height="15" bgcolor="#EEEEEE" style="padding:0px 5px 0px 5px"><img src="/public/images/space.gif" width="23" height="30" /></td>
                    <td width="40" bgcolor="#EEEEEE" style="padding:0px 10px 0px 10px; font-size: 13px;"><strong>ID</strong> </td>
                    <td width="200" bgcolor="#EEEEEE" style="padding:0px 10px 0px 10px; font-size: 13px;"><strong>Name</strong></td>
                    <td width="130" bgcolor="#EEEEEE" style="padding:0px 10px 0px 10px; font-size: 13px;"><strong>Updated On</strong></td>
                    <td width="100" bgcolor="#EEEEEE" style="padding:0px 10px 0px 10px; font-size: 13px;"><strong>Created By</strong></td>
                    <td width="130" bgcolor="#EEEEEE" style="padding:0px 10px 0px 10px; font-size: 13px;"><strong>Delete</strong></td>
                </tr>
            </table>

            <div style="overflow:auto; height:350px; width:755px;">
                <table width="740" border="0" cellspacing="10" cellpadding="0">
					<?php
					$i=1;
					foreach ($resultSet as $val) {
					?>
						<tr id="template<?php echo $val['id']; ?>">
							<td width="30" height="25" valign="top"  style="padding:0px 5px 0px 5px"><input id="TmpNo_<?php echo $i; ?>" type="radio" value="<?php echo $val['id']; ?>" name="selectedTmpId" /></td>
							<td width="45" valign="top" style="padding:5px 10px 0px 10px; font-size: 13px;"><?php echo $val['id']; ?></td>
							<td width="190" valign="top" style="padding:5px 10px 0px 10px; font-size: 13px;"><div style="word-wrap:break-word; width:180px;"><p><?php echo $val['name']; ?></p></div></td>
							<td width="133" valign="top" style="padding:5px 10px 0px 10px; font-size: 13px;"><?php echo $val['updatedOn']; ?></td>
							<td width="100" valign="top" style="padding:5px 10px 0px 10px; font-size: 13px;"><?php echo $allAdminData[$val['createdBy']]['displayname']; ?></td>
                            <td valign="top" width="130" style="padding:0px 10px 0px 10px; font-size: 13px;"><div id="deleteMailTemplate<?php echo $val['id']; ?>"><input type='button' value='Delete' class="deleteTemplateButton" onclick="deleteTemplate('<?php echo $val['id']; ?>','<?php echo $templateType; ?>',<?php echo $i; ?>);" /></div></td>
						</tr>
					<?php
						$i++;
					}
					?>
                </table>
            </div>
        <input type="hidden" id="deleteCounter" value = "<?php echo $i-1; ?>" />
        <input type="hidden" id="totalTempCount" value="<?php echo $i-1; ?>" />
        <div id="radio_unselect_error" style="display:none;color:red;"></div>
            <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td width="100%" height="20" colspan="7" bgcolor="#EEEEEE">&nbsp;</td>
                </tr>
                <tr>
                    <td height="20" colspan="7" align="right" style="padding-top:10px;">

                        <input type="button"  value="Edit Template" onclick="$('frmSelectTemplate').action='/mailer/Mailer/EditTemplate';validateFormSums();">
                        <!--  send it to mail var edit template with tem id-->
                        <!-- <input type="button"  value="Use Template To Send" onclick="$('frmSelectTemplate').action='/mailer/Mailer/setVariables_from_home';validateFormSums();"> -->

                    </td>
                </tr>
            </table>
        </div>
        <div class="clear_L"></div>
        <?php } ?>
