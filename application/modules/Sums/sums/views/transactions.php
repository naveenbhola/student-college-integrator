<?php if ($results==NULL) { ?>
	<div class="mar_top_6p">No results found.</div>
        <?php } else { ?>

	<div class="OrgangeFont fontSize_14p bld"><strong>View Transaction(s) Summary</strong></div>
        <div class="grayLine"></div>
	<div style="overflow:auto;height:300px">
            <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td colspan="7" bgcolor="#FFFFFF">&nbsp;</td>
                </tr>
                <tr>
                    <td width="12%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>CLIENT ID</strong> </td>
                    <td width="12%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>COLLEGE/ INSTITUTE/ UNIVERSITY NAME</strong></td>
                    <td width="16%" valign="top"  bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>LOGIN EMAIL ID</strong></td>
		    <td width="12%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Transaction ID</strong></td>
		    <td width="12%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Transaction DATE</strong></td>
                    <td width="12%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>PRODUCTS SELECTED</strong></td>
                    <td width="12%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>AMOUNT</strong></td>
		    <td width="12%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Sales BY</strong></td>
                </tr>
                    <tr>
		       <td width="12%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="12%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="16%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="12%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="12%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="12%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="12%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="12%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                    </tr>
        <?php $i=1; ?>
	<?php foreach ($results as $key=>$val) { ?>
                    <tr>
		       <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['ClientUserId']; ?></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['businessCollege']; ?></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['email']; ?></td>
			<td valign="top" style="padding:5px 10px 0px 10px"><a href="#"><?php echo $val['TransactionId']; ?></a></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['CreatedTime']; ?></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['ProductSelected']; ?></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['TotalTransactionPrice']; ?></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['SalesBy']; ?></td>
                    </tr>
	<?php $i++; 
        } ?>

                </table>
            </div>
        <input type="hidden" id="totalUserCount" value="<?php echo $i-1; ?>" />
        <div class="clear_L"></div>
        <?php } ?>
