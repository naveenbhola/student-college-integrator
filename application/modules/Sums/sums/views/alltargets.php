<?php if (count($result) <= 0 ) { ?>
	<div class="mar_top_6p">No results found.</div>
        <?php } else { ?>
        <div class="OrgangeFont fontSize_14p bld"><strong>Summary( Total Executives <?php echo count($result);?> )</strong></div>
        <div class="grayLine"></div>
        <div class="lineSpace_10">
            <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td colspan="5" bgcolor="#FFFFFF">&nbsp;</td>
                </tr>
                <tr>
                    <td width="30%" align='center' valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Branch</strong> </td>
                    <td width="25%" align='center' valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Executive</strong></td>
                    <td width="10%" align='center' valign="top"  bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Quarter</strong></td>
                    <td width="10%" align='center' valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Year</strong></td>
                    <td width="25%" align='center' valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Target</strong></td>
                </tr>
            </table>

            <div style="overflow:auto; height:115px">
                <table width="98%" border="0" cellspacing="3" cellpadding="0">
                    <tr>
                        <td width="30%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="25%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="10%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="10%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="25%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                    </tr>
        <?php
        foreach ($result as $key=>$val) {
		?>
				<tr>
					<td valign="top" align='center' style="padding:5px 10px 0px 10px"><?php echo $val['BranchName']; ?></td>
					<td valign="top" align='center' style="padding:5px 10px 0px 10px"><?php echo $val['executive_name']; ?></td>
					<td valign="top" align='center' style="padding:5px 10px 0px 10px"><?php if ($val['quarter'] == '4,5,6' ) { echo "First Quarter";}elseif ($val['quarter'] == '7,8,9') {echo "Second Quarter";}elseif ($val['quarter'] == '10,11,12') {echo "Third Quarter";}elseif ($val['quarter'] == '1,2,3') {echo "Fourth Quarter";} ?></td>
					<td valign="top" align='center' style="padding:5px 10px 0px 10px"><?php echo $val['year']; ?></td>
					<td valign="top" align='center' style="padding:5px 10px 0px 10px"><input type="text" name="exe_target[]" style="width:120px" value="<?php if ( $val['executive_target'] == "-1" )  { echo 0; } else { echo $val['executive_target'];} ?>" />.</td>
				</tr>
				<input type="hidden" id="exe_id" name ="exe_id[]" value="<?php echo $val['executive_id']; ?>" />
				<input type="hidden" id="exe_id" name ="tar_id[]" value="<?php echo $val['targetId']; ?>" />
		<?php
		}
		?>
		<input type="hidden" id="branchid" name ="branchid" value="<?php echo $val['Branchid']; ?>" />
		<input type="hidden" id="year" name ="year" value="<?php echo $val['year']; ?>" />
		<input type="hidden" id="quarter" name ="quarter" value="<?php echo  $val['quarter']; ?>" />
				</table>
            </div>
        <div class="lineSpace_5">&nbsp;</div>
        <div id="radio_unselect_error" style="display:none;color:red;"></div><br/>
            <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td width="100%" height="20" colspan="5" bgcolor="#EEEEEE">&nbsp;</td>
                </tr>
                <tr>
                    <td height="20" colspan="5" align="right">
                        <input type="button"  value="Save Target" onclick="$('TargetSubmitForm').action='/index.php/sums/targetInput/savetargets';SaveTargets();">
                    </td>
                </tr>
            </table>
        </div>
        <div class="clear_L"></div>
        <?php } ?>