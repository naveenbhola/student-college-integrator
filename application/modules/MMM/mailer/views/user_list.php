<?php if (count($result) <= 0 ) { ?>
	<div class="mar_top_6p">No results found.</div>
        <?php } else { ?>

        <div class="OrgangeFont fontSize_14p bld"><strong>View User Lists</strong></div>
        <div class="grayLine"></div>
        <div>
            <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td colspan="7" bgcolor="#FFFFFF">&nbsp;</td>
                </tr>
                <tr>
                    <td width="5%" height="25" valign="top" bgcolor="#EEEEEE" style="padding:5px 5px 0px 5px"><img src="/public/images/space.gif" width="23" height="30" /></td>
                    <td width="10%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>List Name</strong> </td>
                    <td width="25%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>List Description</strong></td>
                    <td width="20%" valign="top"  bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>CREATED DATE</strong></td>
                    <td width="20%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Number of Users In List</strong></td>

                </tr>
            </table>

            <div style="overflow:auto; height:115px">
                <table width="98%" border="0" cellspacing="3" cellpadding="0">
                    <tr>
                        <td width="5%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="10%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="25%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="20%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="20%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                    </tr>

        <?php $i=1;
        foreach ($result as $val) {
        ?>
                    <tr>
                        <td height="25" valign="top"  style="padding:0"><input id="TmpNo_<?php echo $i; ?>" type="radio" value="<?php echo $val['id']; ?>" name="selectedTmpId" /></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['name']; ?></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['description']; ?></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['createdOn']; ?></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['numUsers']; ?></td>
                    </tr>
	<?php
	$i++;
        } ?>

                </table>
            </div>
        <input type="hidden" id="totalTempCount" value="<?php echo $i-1; ?>" />
        <div class="lineSpace_5">&nbsp;</div>
        <div id="radio_unselect_error" style="display:none;color:red;"></div><br/>
            <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td width="100%" height="20" colspan="7" bgcolor="#EEEEEE">&nbsp;</td>
                </tr>
                <tr>
                    <td height="20" colspan="7" align="right">

                    </td>
                </tr>
            </table>
        </div>
        <div class="clear_L"></div>
        <?php } ?>
